<?php

namespace App\Filament\Resources\Accessories;

use App\Filament\Resources\Accessories\Pages\ManageAccessories;
use App\Models\Accessory;
use App\Support\AdminFormPreview;
use App\Support\AdminSlug;
use App\Support\AccessoryCatalog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class AccessoryResource extends Resource
{
    protected static ?string $model = Accessory::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?int $navigationSort = 16;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Девайси';
    }

    public static function getModelLabel(): string
    {
        return 'девайс';
    }

    public static function getPluralModelLabel(): string
    {
        return 'девайси';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'xl' => 12,
            ])->schema([
                Group::make([
                    Section::make('Основне')
                        ->schema([
                            Select::make('type')
                                ->label('Категорія')
                                ->required()
                                ->native(false)
                                ->live()
                                ->options(AccessoryCatalog::typeOptions()),
                            TextInput::make('name')
                                ->label('Назва')
                                ->required()
                                ->live(debounce: 300)
                                ->afterStateUpdated(fn ($state, $old, callable $get, callable $set) => AdminSlug::syncFromSource($state, $old, $get, $set))
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->live(debounce: 300)
                                ->maxLength(255)
                                ->dehydrateStateUsing(fn ($state): string => AdminSlug::normalize($state))
                                ->unique(ignoreRecord: true),
                            TextInput::make('vendor')
                                ->label('Бренд')
                                ->live(debounce: 300)
                                ->maxLength(255),
                            TextInput::make('sku')
                                ->label('SKU / артикул')
                                ->live(debounce: 300)
                                ->maxLength(255),
                            TextInput::make('price')
                                ->label('Ціна, грн')
                                ->numeric()
                                ->live()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                            Textarea::make('summary')
                                ->label('Короткий опис')
                                ->rows(3)
                                ->live(debounce: 300)
                                ->columnSpanFull(),
                            FileUpload::make('gallery_paths')
                                ->label('Галерея фото')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->openable()
                                ->previewable()
                                ->appendFiles()
                                ->fetchFileInformation(true)
                                ->live()
                                ->disk('public')
                                ->directory('accessories')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(10240)
                                ->helperText('Можна завантажити кілька фото. Перше буде головним і показуватиметься в картці девайса.')
                                ->columnSpanFull(),
                            TextInput::make('sort_order')
                                ->label('Порядок')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                            Toggle::make('is_active')
                                ->label('Активний')
                                ->live()
                                ->default(true),
                        ])
                        ->columns(2),
                    Section::make('Попап "Інформація"')
                        ->description('Заповни характеристики, які відкриватимуться в попапі на сторінці збірки.')
                        ->schema([
                            Repeater::make('specs')
                                ->label('Загальні характеристики')
                                ->default([])
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Назва')
                                        ->required()
                                        ->live(debounce: 300)
                                        ->maxLength(255),
                                    Textarea::make('value')
                                        ->label('Значення')
                                        ->required()
                                        ->rows(2)
                                        ->live(debounce: 300),
                                    Toggle::make('is_highlighted')
                                        ->label('Акцентний рядок')
                                        ->live()
                                        ->default(false),
                                ])
                                ->columns(2)
                                ->grid(1)
                                ->addActionLabel('Додати характеристику')
                                ->collapsible()
                                ->columnSpanFull(),
                        ]),
                    Section::make('Попап "Комплектація"')
                        ->description('Список того, що йде в комплекті. Іконку можна вибрати для кожного рядка.')
                        ->schema([
                            Repeater::make('package_items')
                                ->label('Комплектація')
                                ->default([])
                                ->schema([
                                    Select::make('icon')
                                        ->label('Іконка')
                                        ->required()
                                        ->native(false)
                                        ->live()
                                        ->options(AccessoryCatalog::packageIconOptions())
                                        ->default('generic'),
                                    Textarea::make('label')
                                        ->label('Текст')
                                        ->required()
                                        ->rows(2)
                                        ->live(debounce: 300),
                                    Toggle::make('is_highlighted')
                                        ->label('Акцентний рядок')
                                        ->live()
                                        ->default(false),
                                ])
                                ->columns(2)
                                ->grid(1)
                                ->addActionLabel('Додати елемент комплекту')
                                ->collapsible()
                                ->columnSpanFull(),
                        ]),
                ])->columnSpan([
                    'default' => 1,
                    'xl' => 12,
                ]),
                Section::make('Живий превʼю')
                    ->description('Праворуч одразу видно, як девайс виглядатиме на сайті.')
                    ->schema([
                        Placeholder::make('live_preview')
                            ->hiddenLabel()
                            ->content(fn (callable $get, ?Accessory $record): HtmlString => new HtmlString(
                                view('filament.previews.accessory-live-preview', [
                                    'preview' => static::livePreviewData($get, $record),
                                ])->render()
                            )),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'xl' => 12,
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('type')->orderBy('sort_order')->orderBy('name'))
            ->columns([
                ViewColumn::make('preview')
                    ->label('Фото')
                    ->view('filament.tables.columns.admin-image-preview')
                    ->viewData(fn (Accessory $record): array => [
                        'imageUrl' => $record->hasUploadedImages() ? $record->primaryImageUrl() : null,
                        'imageUrls' => $record->hasUploadedImages() ? $record->imageUrls() : [],
                        'placeholderUrl' => $record->placeholderUrl(),
                        'hasImage' => $record->hasUploadedImages(),
                        'caption' => (string) $record->name,
                        'alt' => (string) $record->name,
                        'clickToOpen' => $record->hasUploadedImages(),
                    ]),
                TextColumn::make('type')
                    ->label('Категорія')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => AccessoryCatalog::typeLabel($state)),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(['name', 'vendor', 'sku', 'slug'])
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Ціна')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '.', ' ') . ' грн')
                    ->sortable(),
                TextColumn::make('summary')
                    ->label('Опис')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Активний')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                static::makeEditAction(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccessories::route('/'),
        ];
    }

    public static function makeCreateAction(): CreateAction
    {
        return CreateAction::make()
            ->label('Новий девайс')
            ->modalWidth('7xl');
    }

    public static function makeEditAction(): EditAction
    {
        return EditAction::make()
            ->modalWidth('7xl');
    }

    protected static function livePreviewData(callable $get, ?Accessory $record): array
    {
        $type = (string) ($get('type') ?: $record?->type ?: 'keyboard');
        $name = AdminFormPreview::cleanText($get('name') ?: $record?->name, 'Назва девайсу');
        $fallbackAccessory = $record instanceof Accessory
            ? $record
            : new Accessory([
                'type' => $type,
                'name' => $name,
            ]);

        $imageUrls = AdminFormPreview::imageUrls($get('gallery_paths'));

        if ($imageUrls === [] && $record instanceof Accessory) {
            $imageUrls = $record->imageUrls();
        }

        return [
            'type' => $type,
            'type_label' => AccessoryCatalog::typeLabel($type),
            'type_meta' => AccessoryCatalog::typeMeta($type),
            'name' => $name,
            'vendor' => AdminFormPreview::cleanText($get('vendor') ?: $record?->vendor, 'Kondor'),
            'sku' => AdminFormPreview::cleanText($get('sku') ?: $record?->sku),
            'summary' => trim((string) ($get('summary') ?: $record?->summary ?: 'Короткий опис зʼявиться тут одразу під час редагування.')),
            'price' => AdminFormPreview::formatPrice($get('price') ?? $record?->price ?? 0, 'грн'),
            'image_urls' => $imageUrls !== [] ? $imageUrls : [$fallbackAccessory->placeholderUrl()],
            'specs' => collect((array) ($get('specs') ?? $record?->specs ?? []))
                ->filter(fn ($row): bool => is_array($row))
                ->map(static function (array $row): array {
                    return [
                        'label' => trim((string) ($row['label'] ?? '')),
                        'value' => trim((string) ($row['value'] ?? '')),
                        'is_highlighted' => (bool) ($row['is_highlighted'] ?? false),
                    ];
                })
                ->filter(fn (array $row): bool => $row['label'] !== '' && $row['value'] !== '')
                ->values()
                ->all(),
            'package_items' => collect((array) ($get('package_items') ?? $record?->package_items ?? []))
                ->filter(fn ($row): bool => is_array($row))
                ->map(static function (array $row): array {
                    return [
                        'label' => trim((string) ($row['label'] ?? '')),
                        'icon' => AccessoryCatalog::packageIcon((string) ($row['icon'] ?? 'generic')),
                        'is_highlighted' => (bool) ($row['is_highlighted'] ?? false),
                    ];
                })
                ->filter(fn (array $row): bool => $row['label'] !== '')
                ->values()
                ->all(),
            'is_active' => (bool) (($get('is_active') ?? $record?->is_active) ?? true),
        ];
    }
}
