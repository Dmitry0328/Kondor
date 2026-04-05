<?php

namespace App\Filament\Resources\Builds;

use App\Filament\Resources\Builds\Pages\CreateBuild;
use App\Filament\Resources\Builds\Pages\EditBuild;
use App\Filament\Resources\Builds\Pages\ListBuilds;
use App\Models\Build;
use App\Models\Component;
use App\Support\AdminFormPreview;
use App\Support\AdminSlug;
use App\Support\BuildAbout;
use App\Support\BuildConfigurator;
use App\Support\BuildImages;
use App\Support\FpsCatalog;
use App\Support\FpsProfiles;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class BuildResource extends Resource
{
    protected static ?string $model = Build::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Збірки';
    }

    public static function getModelLabel(): string
    {
        return 'збірка';
    }

    public static function getPluralModelLabel(): string
    {
        return 'збірки';
    }

    public static function form(Schema $schema): Schema
    {
        $fpsOptions = static::fpsCatalogOptions();
        $fpsDefaultRow = [
            'game' => $fpsOptions['defaults']['game'],
        ];

        return $schema->components([
            Grid::make([
                'default' => 1,
                'xl' => 12,
            ])->schema([
                Group::make([
                    static::basicFormSection(),
                    static::cardFormSection(),
                    static::configuratorFormSection(),
                    static::productSpecsFormSection(),
                    static::aboutFormSection(),
                    static::fpsFormSection($fpsOptions, $fpsDefaultRow),
                ])
                    ->extraAttributes(['class' => 'admin-build-form-stack'])
                    ->columnSpan([
                    'default' => 1,
                    'xl' => 12,
                ]),
                Section::make('Живий превʼю')
                    ->description('Показує, як збірка виглядатиме на storefront під час редагування.')
                    ->schema([
                        Placeholder::make('live_preview')
                            ->hiddenLabel()
                            ->content(fn (callable $get, ?Build $record): HtmlString => new HtmlString(
                                view('filament.previews.build-live-preview', [
                                    'preview' => static::livePreviewData($get, $record),
                                ])->render()
                            )),
                    ])
                    ->extraAttributes(['class' => 'admin-build-live-preview-shell'])
                    ->columnSpan([
                        'default' => 1,
                        'xl' => 12,
                    ]),
            ]),
        ]);
    }

    protected static function basicFormSection(): Section
    {
        return Section::make('Основне')
            ->description('Основні поля збірки для каталогу, картки товару та сортування в адмінці.')
            ->schema([
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
                    ->helperText("Використовується в URL. Якщо змінити slug, прив'язані фото збірки також перейдуть на новий ключ.")
                    ->rule(fn ($record): Unique => Rule::unique('builds', 'slug')->ignore($record)),
                TextInput::make('product_code')
                    ->label('Код товару')
                    ->required()
                    ->live(debounce: 300)
                    ->maxLength(64)
                    ->rule(fn ($record): Unique => Rule::unique('builds', 'product_code')->ignore($record))
                    ->helperText('Показується в картці збірки, у каталозі та на сторінці товару.'),
                Select::make('tone')
                    ->label('Колір картки')
                    ->required()
                    ->live()
                    ->options([
                        'violet' => 'Violet',
                        'magenta' => 'Magenta',
                        'amber' => 'Amber',
                        'peach' => 'Peach',
                        'emerald' => 'Emerald',
                    ])
                    ->default('violet'),
                TextInput::make('price')
                    ->label('Ціна, ₴')
                    ->numeric()
                    ->live()
                    ->minValue(0)
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Опубліковано')
                    ->helperText('Увімкнено — збірка видима на сайті. Вимкнено — це чернетка.')
                    ->live()
                    ->default(true),
            ])
            ->columns(2);
    }

    protected static function cardFormSection(): Section
    {
        return Section::make('Картка збірки')
            ->description('Ці поля показуються в картках на головній, у каталозі, на сторінці товару та в кошику.')
            ->schema([
                Placeholder::make('gallery_uploads_guide')
                    ->label('Як це працює')
                    ->content(fn (): HtmlString => static::galleryUploadGuideHtmlAscii())
                    ->columnSpanFull(),
                FileUpload::make('gallery_uploads')
                    ->label('Галерея збірки')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->openable()
                    ->downloadable()
                    ->previewable()
                    ->appendFiles()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('220')
                    ->maxFiles(12)
                    ->hint('Перше фото буде головним, а порядок можна змінювати перетягуванням.')
                    ->disk('public')
                    ->directory('site-images')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->fetchFileInformation(true)
                    ->live()
                    ->deletable()
                    ->columnSpanFull()
                    ->helperText('JPG/PNG/WEBP до 10MB. Можна додати кілька фото: перше буде головним у картці, а решта зʼявиться в галереї товару.'),
                Placeholder::make('gallery_uploads_note')
                    ->hiddenLabel()
                    ->hidden()
                    ->content(fn (): HtmlString => new HtmlString('<div style="padding:10px 12px;border:1px dashed #cfd8e3;border-radius:14px;background:#f8fbff;color:#516072;font-size:13px;line-height:1.55;">JPG/PNG/WEBP до 10MB. Можна додати кілька фото: перше буде головним у картці, а решта зʼявиться в галереї на головній, у каталозі та на сторінці товару.</div>'))
                    ->columnSpanFull(),
                TextInput::make('gpu')
                    ->label('Відеокарта')
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::syncBaseComponentFromLegacyField('gpu', $state, $get, $set))
                    ->helperText('Можна залишити порожнім, якщо відеокарта задається через базову комплектуючу конфігуратора.'),
                Select::make('gpu_component_picker')
                    ->label('Обрати відеокарту зі списку')
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->options(fn (): array => static::componentOptionsByType('gpu'))
                    ->afterStateHydrated(function (Select $component, $state, ?Build $record): void {
                        if (filled($state) || (! $record)) {
                            return;
                        }

                        $component->state(BuildConfigurator::inferBaseComponentIdForText('gpu', (string) $record->gpu) ?: null);
                    })
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::applyLegacyCardComponentSelection('gpu', (int) $state, $get, $set))
                    ->helperText('Підтягує відеокарти з бібліотеки комплектуючих.'),
                Textarea::make('cpu')
                    ->label('Процесор')
                    ->rows(3)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::syncBaseComponentFromLegacyField('cpu', $state, $get, $set))
                    ->helperText('Можна залишити порожнім, якщо процесор береться з базової комплектуючої конфігуратора.'),
                Select::make('cpu_component_picker')
                    ->label('Обрати процесор зі списку')
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->options(fn (): array => static::componentOptionsByType('cpu'))
                    ->afterStateHydrated(function (Select $component, $state, ?Build $record): void {
                        if (filled($state) || (! $record)) {
                            return;
                        }

                        $component->state(BuildConfigurator::inferBaseComponentIdForText('cpu', (string) $record->cpu) ?: null);
                    })
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::applyLegacyCardComponentSelection('cpu', (int) $state, $get, $set))
                    ->helperText('Підтягує процесори з бібліотеки комплектуючих.'),
                TextInput::make('ram')
                    ->label("Оперативна пам'ять")
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::syncBaseComponentFromLegacyField('ram', $state, $get, $set))
                    ->helperText('Якщо не вказати, на сайті буде показано, що інформація про комплектуючу відсутня.'),
                Select::make('ram_component_picker')
                    ->label('Обрати ОЗП зі списку')
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->options(fn (): array => static::componentOptionsByType('ram'))
                    ->afterStateHydrated(function (Select $component, $state, ?Build $record): void {
                        if (filled($state) || (! $record)) {
                            return;
                        }

                        $component->state(BuildConfigurator::inferBaseComponentIdForText('ram', (string) $record->ram) ?: null);
                    })
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::applyLegacyCardComponentSelection('ram', (int) $state, $get, $set))
                    ->helperText('Підтягує комплекти ОЗП з бібліотеки комплектуючих.'),
                TextInput::make('storage')
                    ->label('Накопичувач')
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::syncBaseComponentFromLegacyField('storage', $state, $get, $set))
                    ->helperText('Якщо не вказати, на сайті буде показано, що інформація про комплектуючу відсутня.'),
                Select::make('storage_component_picker')
                    ->label('Обрати накопичувач зі списку')
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->options(fn (): array => static::componentOptionsByType('storage'))
                    ->afterStateHydrated(function (Select $component, $state, ?Build $record): void {
                        if (filled($state) || (! $record)) {
                            return;
                        }

                        $component->state(BuildConfigurator::inferBaseComponentIdForText('storage', (string) $record->storage) ?: null);
                    })
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => static::applyLegacyCardComponentSelection('storage', (int) $state, $get, $set))
                    ->helperText('Підтягує накопичувачі з бібліотеки комплектуючих.'),
            ])
            ->columns(2);
    }

    protected static function configuratorFormSection(): Section
    {
        return Section::make('Комплектуючі')
            ->description('Тут задаються базові комплектуючі та варіанти заміни для конкретної збірки.')
            ->schema(static::configuratorSlotsSchema())
            ->columnSpanFull();
    }

    protected static function productSpecsFormSection(): Section
    {
        return Section::make('Характеристики сторінки товару')
            ->description('Список характеристик для сторінки товару.')
            ->schema([
                Repeater::make('product_specs')
                    ->label('Список характеристик')
                    ->default([])
                    ->schema([
                        Select::make('icon')
                            ->label('Іконка')
                            ->required()
                            ->options([
                                'gpu' => 'GPU',
                                'cpu' => 'CPU',
                                'ram' => 'RAM',
                                'motherboard' => 'Motherboard',
                                'storage' => 'Storage',
                                'case' => 'Case',
                                'psu' => 'PSU',
                            ])
                            ->default('gpu'),
                        TextInput::make('label')
                            ->label('Назва')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('value')
                            ->label('Значення')
                            ->required()
                            ->rows(2),
                    ])
                    ->columns(3)
                    ->grid(1)
                    ->addActionLabel('Додати характеристику')
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }

    protected static function aboutFormSection(): Section
    {
        return Section::make('Блок "Про збірку"')
            ->description('Кожен рядок у списках — окремий пункт. Для вступу роби абзаци через порожній рядок.')
            ->schema([
                Textarea::make('about_intro_text')
                    ->label('Вступні абзаци')
                    ->rows(6)
                    ->live(debounce: 300),
                Textarea::make('about_notes_text')
                    ->label('Короткі примітки')
                    ->rows(5)
                    ->live(debounce: 300)
                    ->helperText('По одному пункту на рядок.'),
                TextInput::make('about_setup_title')
                    ->label('Заголовок блоку "Що буде зроблено"')
                    ->maxLength(255),
                Textarea::make('about_setup_items_text')
                    ->label('Що буде зроблено')
                    ->rows(4)
                    ->helperText('По одному пункту на рядок.'),
                TextInput::make('about_delivery_title')
                    ->label('Заголовок блоку "Оплата та доставка"')
                    ->maxLength(255),
                Textarea::make('about_delivery_items_text')
                    ->label('Пункти доставки')
                    ->rows(4)
                    ->helperText('По одному пункту на рядок.'),
                Textarea::make('about_delivery_steps_text')
                    ->label('Кроки / варіанти оплати')
                    ->rows(4)
                    ->helperText('По одному пункту на рядок.'),
                TextInput::make('about_warranty_title')
                    ->label('Заголовок блоку "Гарантія та повернення"')
                    ->maxLength(255),
                Textarea::make('about_warranty_items_text')
                    ->label('Пункти гарантії')
                    ->rows(4)
                    ->helperText('По одному пункту на рядок.'),
            ])
            ->columns(2);
    }

    protected static function fpsFormSection(array $fpsOptions, array $fpsDefaultRow): Section
    {
        return Section::make('FPS (ручне керування)')
            ->description("Обери гру і заповни матрицю FPS. Порожнє поле означає: 'FPS тест відсутній'.")
            ->columnSpanFull()
            ->schema([
                Repeater::make('fps_games_matrix')
                    ->label('Матриця FPS')
                    ->default([$fpsDefaultRow])
                    ->schema(static::fpsGameMatrixSchema($fpsOptions))
                    ->itemLabel(function (array $state) use ($fpsOptions): string {
                        $gameId = (string) ($state['game'] ?? '');

                        return $fpsOptions['games'][$gameId] ?? 'Гра';
                    })
                    ->columns(1)
                    ->grid(1)
                    ->reorderable(false)
                    ->collapsible()
                    ->addActionLabel('Додати гру')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ViewColumn::make('cover')
                    ->label('Фото')
                    ->view('filament.tables.columns.admin-image-preview')
                    ->viewData(function (Build $record): array {
                        $imageUrl = static::coverImageUrl($record);

                        return [
                            'imageUrl' => $imageUrl,
                            'placeholderUrl' => static::coverPlaceholderUrl(),
                            'hasImage' => filled($imageUrl),
                            'caption' => (string) $record->name,
                            'alt' => (string) $record->name,
                        ];
                    }),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_code')
                    ->label('Код товару')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('tone')
                    ->label('Тон')
                    ->badge(),
                TextColumn::make('price')
                    ->label('Ціна')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴')
                    ->sortable(),
                TextColumn::make('trade_in_requests_count')
                    ->label('Trade-in')
                    ->counts('tradeInRequests')
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),
                TextColumn::make('fps_score')
                    ->label('FPS (дефолт)')
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (bool) $state ? 'Опубліковано' : 'Чернетка')
                    ->color(fn ($state): string => (bool) $state ? 'success' : 'gray'),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
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
            'index' => ListBuilds::route('/'),
            'create' => CreateBuild::route('/create'),
            'edit' => EditBuild::route('/{record}/edit'),
        ];
    }

    protected static function livePreviewData(callable $get, ?Build $record): array
    {
        $name = AdminFormPreview::cleanText($get('name') ?: $record?->name, 'Назва збірки');
        $galleryUrls = AdminFormPreview::imageUrls($get('gallery_uploads'));

        if ($galleryUrls === [] && $record instanceof Build) {
            $galleryUrls = BuildImages::urlsForSlug((string) $record->slug);
        }

        $aboutSource = [
            'about' => $record?->about,
            'about_intro_text' => $get('about_intro_text'),
            'about_notes_text' => $get('about_notes_text'),
            'about_setup_title' => $get('about_setup_title'),
            'about_setup_items_text' => $get('about_setup_items_text'),
            'about_delivery_title' => $get('about_delivery_title'),
            'about_delivery_items_text' => $get('about_delivery_items_text'),
            'about_delivery_steps_text' => $get('about_delivery_steps_text'),
            'about_warranty_title' => $get('about_warranty_title'),
            'about_warranty_items_text' => $get('about_warranty_items_text'),
        ];

        if (filled($aboutSource['about_intro_text']) || filled($aboutSource['about_notes_text']) || filled($aboutSource['about_setup_title']) || filled($aboutSource['about_setup_items_text']) || filled($aboutSource['about_delivery_title']) || filled($aboutSource['about_delivery_items_text']) || filled($aboutSource['about_delivery_steps_text']) || filled($aboutSource['about_warranty_title']) || filled($aboutSource['about_warranty_items_text'])) {
            $aboutSource = static::collapseAboutFromForm($aboutSource);
        }

        $about = BuildAbout::resolve($aboutSource);
        $specs = collect((array) ($get('product_specs') ?? $record?->product_specs ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->map(static function (array $row): array {
                return [
                    'label' => trim((string) ($row['label'] ?? '')),
                    'value' => trim((string) ($row['value'] ?? '')),
                ];
            })
            ->filter(fn (array $row): bool => $row['label'] !== '' && $row['value'] !== '')
            ->take(6)
            ->values()
            ->all();

        return [
            'name' => $name,
            'product_code' => AdminFormPreview::cleanText($get('product_code') ?: $record?->product_code, '000000'),
            'tone' => (string) ($get('tone') ?: $record?->tone ?: 'violet'),
            'price' => AdminFormPreview::formatPrice($get('price') ?? $record?->price ?? 0, '₴'),
            'image_urls' => $galleryUrls !== [] ? $galleryUrls : [BuildImages::placeholderUrl($name)],
            'gpu' => static::previewTextValue($get('gpu') ?: $record?->gpu, 'Відеокарта'),
            'cpu' => static::previewTextValue($get('cpu') ?: $record?->cpu, 'Процесор'),
            'ram' => static::previewTextValue($get('ram') ?: $record?->ram, "Оперативна памʼять"),
            'storage' => static::previewTextValue($get('storage') ?: $record?->storage, 'Накопичувач'),
            'specs' => $specs,
            'about' => $about,
            'is_active' => (bool) (($get('is_active') ?? $record?->is_active) ?? true),
        ];
    }

    protected static function previewTextValue(mixed $value, string $fallback): string
    {
        return AdminFormPreview::cleanText($value, $fallback);
    }

    public static function galleryImagePathsForSlug(?string $slug): array
    {
        return BuildImages::pathsForSlug($slug);
    }

    public static function syncGalleryImages(Build $build, mixed $galleryUploads): void
    {
        BuildImages::sync($build, $galleryUploads);
    }

    protected static function galleryUploadGuideHtml(): HtmlString
    {
        return static::galleryUploadGuideHtmlAscii();
    }

    protected static function cleanGalleryUploadGuideHtml(): HtmlString
    {
        return static::galleryUploadGuideHtmlAscii();
    }

    protected static function galleryUploadGuideHtmlAscii(): HtmlString
    {
        return new HtmlString(static::decodeHtml(
            '<div style="display:grid;gap:12px;padding:16px 18px;border:1px solid #e2e8f0;border-radius:20px;background:linear-gradient(180deg,#fff9f0,#f8fbff);">'
            . '<div style="display:flex;flex-wrap:wrap;gap:10px;">'
            . '<span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:#fff3cd;color:#8a5b00;font-size:12px;font-weight:800;">1. &#1047;&#1072;&#1074;&#1072;&#1085;&#1090;&#1072;&#1078; &#1092;&#1086;&#1090;&#1086;</span>'
            . '<span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:#eef4ff;color:#23406d;font-size:12px;font-weight:800;">2. &#1055;&#1077;&#1088;&#1077;&#1090;&#1103;&#1075;&#1085;&#1080; &#1087;&#1086;&#1088;&#1103;&#1076;&#1086;&#1082;</span>'
            . '<span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:#eefbf3;color:#157347;font-size:12px;font-weight:800;">3. &#1055;&#1077;&#1088;&#1096;&#1077; &#1092;&#1086;&#1090;&#1086; = &#1075;&#1086;&#1083;&#1086;&#1074;&#1085;&#1077;</span>'
            . '</div>'
            . '<div style="display:grid;gap:6px;color:#516072;font-size:13px;line-height:1.55;">'
            . '<div>&#1055;&#1077;&#1088;&#1096;&#1077; &#1092;&#1086;&#1090;&#1086; &#1074;&#1080;&#1082;&#1086;&#1088;&#1080;&#1089;&#1090;&#1086;&#1074;&#1091;&#1108;&#1090;&#1100;&#1089;&#1103; &#1103;&#1082; &#1075;&#1086;&#1083;&#1086;&#1074;&#1085;&#1072; &#1082;&#1072;&#1088;&#1090;&#1080;&#1085;&#1082;&#1072; &#1079;&#1073;&#1110;&#1088;&#1082;&#1080; &#1085;&#1072; &#1075;&#1086;&#1083;&#1086;&#1074;&#1085;&#1110;&#1081;, &#1091; &#1082;&#1072;&#1090;&#1072;&#1083;&#1086;&#1079;&#1110;, &#1085;&#1072; &#1089;&#1090;&#1086;&#1088;&#1110;&#1085;&#1094;&#1110; &#1090;&#1086;&#1074;&#1072;&#1088;&#1091; &#1090;&#1072; &#1074; &#1082;&#1086;&#1096;&#1080;&#1082;&#1091;.</div>'
            . '<div>&#1030;&#1085;&#1096;&#1110; &#1092;&#1086;&#1090;&#1086; &#1072;&#1074;&#1090;&#1086;&#1084;&#1072;&#1090;&#1080;&#1095;&#1085;&#1086; &#1087;&#1086;&#1090;&#1088;&#1072;&#1087;&#1083;&#1103;&#1102;&#1090;&#1100; &#1091; &#1075;&#1072;&#1083;&#1077;&#1088;&#1077;&#1102;. &#1031;&#1093; &#1084;&#1086;&#1078;&#1085;&#1072; &#1073;&#1091;&#1076;&#1077; &#1087;&#1077;&#1088;&#1077;&#1075;&#1086;&#1088;&#1090;&#1072;&#1090;&#1080; &#1074; &#1082;&#1072;&#1088;&#1090;&#1094;&#1110; &#1090;&#1086;&#1074;&#1072;&#1088;&#1091; &#1090;&#1072; &#1085;&#1072; &#1089;&#1090;&#1086;&#1088;&#1110;&#1085;&#1094;&#1110; &#1079;&#1073;&#1110;&#1088;&#1082;&#1080;.</div>'
            . '</div>'
            . '</div>'
        ));
    }

    protected static function decodeText(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    protected static function decodeHtml(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function normalizeConfiguratorFromForm(array $data): array
    {
        if (array_key_exists('configurator_slots', $data)) {
            [$baseComponents, $groups] = static::collapseConfiguratorSlotsFromForm((array) ($data['configurator_slots'] ?? []));
            $data['base_components'] = $baseComponents;
            $data['configurator_groups'] = $groups;
            unset($data['configurator_slots']);

            return $data;
        }

        $data['base_components'] = BuildConfigurator::normalizeBaseComponents($data['base_components'] ?? null);
        $data['configurator_groups'] = BuildConfigurator::normalizeGroups($data['configurator_groups'] ?? null);

        return $data;
    }

    public static function expandConfiguratorForForm(array $data): array
    {
        $baseComponents = BuildConfigurator::normalizeBaseComponents((array) ($data['base_components'] ?? [])) ?? [];
        $inferredBaseComponents = BuildConfigurator::inferBaseComponents([
            'gpu' => $data['gpu'] ?? null,
            'cpu' => $data['cpu'] ?? null,
            'ram' => $data['ram'] ?? null,
            'storage' => $data['storage'] ?? null,
            'product_specs' => $data['product_specs'] ?? null,
        ]) ?? [];
        $groups = BuildConfigurator::normalizeGroups((array) ($data['configurator_groups'] ?? [])) ?? [];

        $data['configurator_slots'] = static::buildConfiguratorSlotsState(
            [...$inferredBaseComponents, ...$baseComponents],
            $groups,
        );

        return $data;
    }

    public static function expandAboutForForm(array $data): array
    {
        $about = BuildAbout::resolve($data);

        return [
            'about_intro_text' => implode("\n\n", $about['intro'] ?? []),
            'about_notes_text' => implode("\n", $about['notes'] ?? []),
            'about_setup_title' => $about['setup_title'] ?? null,
            'about_setup_items_text' => implode("\n", $about['setup_items'] ?? []),
            'about_delivery_title' => $about['delivery_title'] ?? null,
            'about_delivery_items_text' => implode("\n", $about['delivery_items'] ?? []),
            'about_delivery_steps_text' => implode("\n", $about['delivery_steps'] ?? []),
            'about_warranty_title' => $about['warranty_title'] ?? null,
            'about_warranty_items_text' => implode("\n", $about['warranty_items'] ?? []),
        ];
    }

    public static function collapseAboutFromForm(array $data): array
    {
        $about = [
            'intro' => static::splitParagraphs($data['about_intro_text'] ?? null),
            'notes' => static::splitLines($data['about_notes_text'] ?? null),
            'setup_title' => static::nullableString($data['about_setup_title'] ?? null),
            'setup_items' => static::splitLines($data['about_setup_items_text'] ?? null),
            'delivery_title' => static::nullableString($data['about_delivery_title'] ?? null),
            'delivery_items' => static::splitLines($data['about_delivery_items_text'] ?? null),
            'delivery_steps' => static::splitLines($data['about_delivery_steps_text'] ?? null),
            'warranty_title' => static::nullableString($data['about_warranty_title'] ?? null),
            'warranty_items' => static::splitLines($data['about_warranty_items_text'] ?? null),
        ];

        unset(
            $data['about_intro_text'],
            $data['about_notes_text'],
            $data['about_setup_title'],
            $data['about_setup_items_text'],
            $data['about_delivery_title'],
            $data['about_delivery_items_text'],
            $data['about_delivery_steps_text'],
            $data['about_warranty_title'],
            $data['about_warranty_items_text'],
        );

        $about = array_filter(
            $about,
            static fn ($value): bool => match (true) {
                is_array($value) => $value !== [],
                default => filled($value),
            },
        );

        $data['about'] = $about !== [] ? $about : null;

        return $data;
    }

    public static function expandFpsProfilesForForm(array $data): array
    {
        $fpsOptions = static::fpsCatalogOptions();
        $profiles = FpsProfiles::normalize((array) ($data['fps_profiles'] ?? []), $fpsOptions['catalog']);
        $rows = [];

        foreach ($profiles as $profile) {
            $game = (string) ($profile['game'] ?? '');
            $display = (string) ($profile['display'] ?? '');
            $preset = (string) ($profile['preset'] ?? '');
            $fps = (int) ($profile['fps'] ?? 0);

            if ($game === '' || $display === '' || $preset === '' || $fps < 1) {
                continue;
            }

            if (! isset($rows[$game])) {
                $rows[$game] = ['game' => $game];
            }

            $rows[$game][static::fpsCellFieldName($display, $preset)] = $fps;
        }

        if ($rows === []) {
            $rows[] = ['game' => (string) ($fpsOptions['defaults']['game'] ?? '')];
        }

        $data['fps_games_matrix'] = array_values($rows);

        return $data;
    }

    public static function normalizeFpsProfilesFromForm(array $data): array
    {
        $fpsOptions = static::fpsCatalogOptions();
        $catalog = $fpsOptions['catalog'];
        $rows = (array) ($data['fps_games_matrix'] ?? []);
        $profiles = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $game = trim((string) ($row['game'] ?? ''));

            if ($game === '') {
                continue;
            }

            foreach ((array) ($catalog['displays'] ?? []) as $display) {
                $displayId = (string) ($display['id'] ?? '');

                if ($displayId === '') {
                    continue;
                }

                foreach ((array) ($catalog['presets'] ?? []) as $preset) {
                    $presetId = (string) ($preset['id'] ?? '');

                    if ($presetId === '') {
                        continue;
                    }

                    $field = static::fpsCellFieldName($displayId, $presetId);
                    $fps = (int) round((float) ($row[$field] ?? 0));

                    if ($fps < 1) {
                        continue;
                    }

                    $profiles[] = [
                        'game' => $game,
                        'display' => $displayId,
                        'preset' => $presetId,
                        'fps' => $fps,
                    ];
                }
            }
        }

        $profiles = FpsProfiles::normalize($profiles, $catalog);
        $defaults = FpsProfiles::defaultState($catalog, $profiles);
        $lookup = FpsProfiles::makeLookup($profiles);

        unset($data['fps_games_matrix']);

        $data['fps_profiles'] = $profiles !== [] ? $profiles : null;
        $data['fps_score'] = $profiles !== []
            ? FpsProfiles::resolve(
                $lookup,
                $profiles,
                (string) ($defaults['game'] ?? ''),
                (string) ($defaults['display'] ?? ''),
                (string) ($defaults['preset'] ?? ''),
                0,
            )
            : 0;

        return $data;
    }

    protected static function fpsGameMatrixSchema(array $fpsOptions): array
    {
        $catalog = (array) ($fpsOptions['catalog'] ?? []);
        $displays = array_values((array) ($catalog['displays'] ?? []));
        $presets = array_values((array) ($catalog['presets'] ?? []));
        $schema = [
            Select::make('game')
                ->label('Гра')
                ->required()
                ->label('Гра')
                ->options($fpsOptions['games'])
                ->native(true),
        ];

        if ($displays === [] || $presets === []) {
            return $schema;
        }

        $tableColumns = count($displays) + 1;

        $headerCells = [
            Placeholder::make('__fps_header_axis')
                ->label('')
                ->content('Графіка / Монітор'),
        ];

        $headerCells[0] = Placeholder::make('__fps_header_axis')
            ->label('')
            ->content('Графіка / Монітор');

        foreach ($displays as $display) {
            $displayId = (string) ($display['id'] ?? '');

            if ($displayId === '') {
                continue;
            }

            $displayLabel = trim((string) ($display['mobile_name'] ?? $display['name'] ?? $displayId));

            $headerCells[] = Placeholder::make('__fps_header_' . $displayId)
                ->label('')
                ->content($displayLabel);
        }

        if (count($headerCells) > 1) {
            $schema[] = Grid::make($tableColumns)
                ->schema($headerCells)
                ->columnSpanFull();
        }

        foreach ($presets as $preset) {
            $presetId = (string) ($preset['id'] ?? '');

            if ($presetId === '') {
                continue;
            }

            $presetName = trim((string) ($preset['name'] ?? 'Графіка'));
            $rowFields = [
                Placeholder::make('__fps_row_' . $presetId)
                    ->label('')
                    ->content($presetName),
            ];

            foreach ($displays as $display) {
                $displayId = (string) ($display['id'] ?? '');

                if ($displayId === '') {
                    continue;
                }

                $displayLabel = trim((string) ($display['mobile_name'] ?? $display['name'] ?? $displayId));

                $rowFields[] = TextInput::make(static::fpsCellFieldName($displayId, $presetId))
                    ->label($displayLabel . ' / ' . $presetName)
                    ->hiddenLabel()
                    ->numeric()
                    ->minValue(1)
                    ->step(1)
                    ->placeholder('-');
            }

            $schema[] = Grid::make($tableColumns)
                ->schema($rowFields)
                ->columnSpanFull();
        }

        return $schema;
    }

    protected static function fpsCellFieldName(string $displayId, string $presetId): string
    {
        $normalize = static function (string $value): string {
            $value = strtolower(trim($value));
            $value = preg_replace('/[^a-z0-9]+/i', '_', $value) ?? '';

            return trim($value, '_');
        };

        return 'fps_' . $normalize($displayId) . '_' . $normalize($presetId);
    }

    protected static function fpsCatalogOptions(): array
    {
        $catalog = FpsCatalog::all();

        $games = collect($catalog['games'] ?? [])
            ->mapWithKeys(static fn (array $row): array => [(string) ($row['id'] ?? '') => (string) ($row['name'] ?? '')])
            ->filter(static fn (string $label, string $id): bool => $id !== '' && $label !== '')
            ->all();

        $displays = collect($catalog['displays'] ?? [])
            ->mapWithKeys(static fn (array $row): array => [(string) ($row['id'] ?? '') => (string) ($row['name'] ?? '')])
            ->filter(static fn (string $label, string $id): bool => $id !== '' && $label !== '')
            ->all();

        $presets = collect($catalog['presets'] ?? [])
            ->mapWithKeys(static fn (array $row): array => [(string) ($row['id'] ?? '') => (string) ($row['name'] ?? '')])
            ->filter(static fn (string $label, string $id): bool => $id !== '' && $label !== '')
            ->all();

        $defaults = FpsProfiles::defaultState($catalog, []);

        if (($defaults['game'] ?? '') === '' && $games !== []) {
            $defaults['game'] = (string) array_key_first($games);
        }

        if (($defaults['display'] ?? '') === '' && $displays !== []) {
            $defaults['display'] = (string) array_key_first($displays);
        }

        if (($defaults['preset'] ?? '') === '' && $presets !== []) {
            $defaults['preset'] = (string) array_key_first($presets);
        }

        return [
            'catalog' => $catalog,
            'games' => $games,
            'displays' => $displays,
            'presets' => $presets,
            'defaults' => $defaults,
        ];
    }

    protected static function configuratorSlotsSchema(): array
    {
        $sections = [];

        foreach (BuildConfigurator::slotDefinitions() as $slot => $definition) {
            $sections[] = static::configuratorSlotSection($slot, $definition);
        }

        return $sections;
    }

    protected static function configuratorSlotSection(string $slot, array $definition): Section
    {
        $basePath = "configurator_slots.$slot.base_component_id";
        $descriptionPath = "configurator_slots.$slot.description";
        $selectedIdsPath = "configurator_slots.$slot.option_component_ids";
        $optionsPath = "configurator_slots.$slot.options";
        $optionsLabel = mb_strtolower((string) ($definition['label'] ?? 'комплектуючі'));

        return Section::make(BuildConfigurator::defaultGroupTitle($slot))
            ->description('Спочатку обирається базова комплектуюча збірки, потім — додаткові варіанти для конфігуратора.')
            ->schema([
                Placeholder::make("configurator_slot_guide_$slot")
                    ->label('Як це працює')
                    ->content(fn (): HtmlString => static::configuratorSlotGuideHtml((string) ($definition['label'] ?? $slot), $optionsLabel))
                    ->columnSpanFull(),

                Placeholder::make("configurator_slot_status_$slot")
                    ->label('Статус блоку')
                    ->content(fn (callable $get): HtmlString => static::configuratorSlotStatusHtml((int) ($get($basePath) ?? 0)))
                    ->columnSpanFull(),

                Placeholder::make("configurator_slot_empty_library_$slot")
                    ->label('Бібліотека компонентів')
                    ->hidden(fn (): bool => static::activeComponentsByType(BuildConfigurator::componentTypeForSlot($slot))->isNotEmpty())
                    ->content(static::configuratorEmptyLibraryHtml()),

                Placeholder::make("configurator_slot_summary_$slot")
                    ->label('Що побачить покупець')
                    ->content(fn (callable $get): HtmlString => static::configuratorOptionsSummaryHtml(
                        (string) ($definition['label'] ?? $slot),
                        (int) ($get($basePath) ?? 0),
                        (array) ($get($selectedIdsPath) ?? []),
                        (array) ($get($optionsPath) ?? []),
                    ))
                    ->columnSpanFull(),

                Select::make($basePath)
                    ->label('Базова комплектуюча')
                    ->options(static::componentOptionsByType($slot))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->live()
                    ->helperText('Саме ця комплектуюча буде базовою для цієї збірки на сайті.')
                    ->afterStateUpdated(function ($state, callable $get, callable $set) use ($slot, $selectedIdsPath): void {
                        $selectedIds = array_values(array_filter(
                            array_map('intval', (array) ($get($selectedIdsPath) ?? [])),
                            fn (int $id): bool => $id > 0 && $id !== (int) $state,
                        ));

                        static::syncConfiguratorOptionsForSlot($slot, $selectedIds, $set, $get);
                    }),

                Placeholder::make("configurator_slot_preview_$slot")
                    ->label('Як виглядає база')
                    ->content(fn (callable $get): HtmlString => static::componentPreviewHtml((int) ($get($basePath) ?? 0), 'Відсутня базова комплектуюча. Обери комплектуючу за замовчуванням.')),

                Textarea::make($descriptionPath)
                    ->label('Опис блоку на сайті')
                    ->rows(3)
                    ->helperText('Це коротке пояснення покупець побачить на сторінці товару над варіантами.')
                    ->placeholder('Необовʼязково. Коротке пояснення для покупця.'),

                Select::make($selectedIdsPath)
                    ->label('Додаткові ' . $optionsLabel)
                    ->multiple()
                    ->default([])
                    ->options(static::componentOptionsByType($slot))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->live()
                    ->hidden(fn (callable $get): bool => (int) ($get($basePath) ?? 0) < 1)
                    ->helperText('Після вибору ці комплектуючі автоматично з’являться нижче як варіанти конфігуратора.')
                    ->afterStateUpdated(function ($state, callable $get, callable $set) use ($slot): void {
                        static::syncConfiguratorOptionsForSlot($slot, (array) $state, $set, $get);
                    }),

                Placeholder::make("configurator_slot_options_hint_$slot")
                    ->label('Що далі')
                    ->hidden(fn (callable $get): bool => (int) ($get($basePath) ?? 0) < 1 || count(array_filter(array_map('intval', (array) ($get($selectedIdsPath) ?? [])))) > 0)
                    ->content(fn (): HtmlString => static::configuratorOptionSelectionHintHtml($optionsLabel))
                    ->columnSpanFull(),

                Repeater::make($optionsPath)
                    ->label('Варіанти у конфігураторі')
                    ->default([])
                    ->hidden(fn (callable $get): bool => (int) ($get($basePath) ?? 0) < 1)
                    ->schema([
                        Hidden::make('component_id'),
                        Placeholder::make('component_preview')
                            ->label('Комплектуюча')
                            ->content(fn (callable $get): HtmlString => static::componentPreviewHtml((int) ($get('component_id') ?? 0), 'Комплектуюча недоступна або видалена.'))
                            ->columnSpanFull(),
                        TextInput::make('label')
                            ->label('Назва в інтерфейсі')
                            ->maxLength(255)
                            ->columnSpan(5)
                            ->placeholder('Можна лишити порожнім — тоді беремо назву комплектуючої.'),
                        TextInput::make('price_delta')
                            ->label('Доплата, ₴')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->columnSpan(3),
                        Toggle::make('is_active')
                            ->label('Активний')
                            ->default(true)
                            ->columnSpan(4),
                        Textarea::make('description')
                            ->label('Опис варіанту')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->itemLabel(fn (array $state): string => static::configuratorOptionItemLabel($state))
                    ->columns(12)
                    ->grid(1)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->collapsible()
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->columnSpanFull()
            ->collapsible();
    }

    protected static function buildConfiguratorSlotsState(array $baseComponents, array $groups): array
    {
        $groupsBySlot = collect($groups)
            ->filter(fn ($group): bool => is_array($group))
            ->keyBy(fn (array $group): string => (string) ($group['slot'] ?? ''));

        $state = [];

        foreach (BuildConfigurator::slotDefinitions() as $slot => $definition) {
            $group = (array) ($groupsBySlot->get($slot) ?? []);
            $baseComponentId = (int) ($baseComponents[$slot] ?? static::detectBaseComponentFromGroup($group));
            $options = collect((array) ($group['options'] ?? []))
                ->map(function ($option) use ($baseComponentId): ?array {
                    if (! is_array($option)) {
                        return null;
                    }

                    $componentId = (int) ($option['component_id'] ?? 0);

                    if ($componentId < 1 || $componentId === $baseComponentId || ($option['is_default'] ?? false)) {
                        return null;
                    }

                    return [
                        'component_id' => $componentId,
                        'label' => trim((string) ($option['label'] ?? '')),
                        'description' => static::nullableString($option['description'] ?? null),
                        'price_delta' => max(0, (int) round((float) ($option['price_delta'] ?? 0))),
                        'is_active' => array_key_exists('is_active', $option) ? (bool) $option['is_active'] : true,
                    ];
                })
                ->filter()
                ->values()
                ->all();

            $state[$slot] = [
                'description' => static::nullableString($group['description'] ?? null),
                'base_component_id' => $baseComponentId > 0 ? $baseComponentId : null,
                'option_component_ids' => array_values(array_unique(array_map(static fn (array $option): int => (int) $option['component_id'], $options))),
                'options' => $options,
            ];
        }

        return $state;
    }

    protected static function collapseConfiguratorSlotsFromForm(array $slots): array
    {
        $components = static::activeComponentsById();
        $baseComponents = [];
        $groups = [];

        foreach (BuildConfigurator::slotDefinitions() as $slot => $definition) {
            $slotState = is_array($slots[$slot] ?? null) ? $slots[$slot] : [];
            $baseComponentId = (int) ($slotState['base_component_id'] ?? 0);
            $componentType = BuildConfigurator::componentTypeForSlot($slot);
            $description = static::nullableString($slotState['description'] ?? null);

            if ($baseComponentId > 0) {
                $baseComponent = $components->get($baseComponentId);

                if ($baseComponent instanceof Component && $baseComponent->type === $componentType) {
                    $baseComponents[$slot] = $baseComponentId;
                } else {
                    $baseComponentId = 0;
                }
            }

            $options = [];
            $usedKeys = [];

            foreach ((array) ($slotState['options'] ?? []) as $optionState) {
                if (! is_array($optionState)) {
                    continue;
                }

                $componentId = (int) ($optionState['component_id'] ?? 0);
                $component = $components->get($componentId);

                if (! $component instanceof Component || $component->type !== $componentType || $componentId === $baseComponentId) {
                    continue;
                }

                $label = trim((string) ($optionState['label'] ?? $component->name));

                if ($label === '') {
                    continue;
                }

                $key = 'component-' . $componentId;
                $baseKey = $key;
                $suffix = 2;

                while (isset($usedKeys[$key])) {
                    $key = $baseKey . '-' . $suffix;
                    $suffix++;
                }

                $usedKeys[$key] = true;

                $options[] = [
                    'key' => $key,
                    'component_id' => $componentId,
                    'label' => $label,
                    'description' => static::nullableString($optionState['description'] ?? $component->summary),
                    'price_delta' => max(0, (int) round((float) ($optionState['price_delta'] ?? 0))),
                    'is_default' => false,
                    'is_active' => array_key_exists('is_active', $optionState) ? (bool) $optionState['is_active'] : true,
                ];
            }

            if ($baseComponentId < 1 && $description === null && $options === []) {
                continue;
            }

            $groups[] = [
                'key' => $slot,
                'title' => BuildConfigurator::defaultGroupTitle($slot),
                'description' => $description,
                'slot' => $slot,
                'options' => $options,
            ];
        }

        return [
            $baseComponents !== [] ? $baseComponents : null,
            $groups !== [] ? $groups : null,
        ];
    }

    protected static function syncBaseComponentFromLegacyField(string $slot, mixed $state, callable $get, callable $set): void
    {
        $basePath = "configurator_slots.$slot.base_component_id";
        $selectedIdsPath = "configurator_slots.$slot.option_component_ids";

        if ((int) ($get($basePath) ?? 0) > 0) {
            return;
        }

        $componentId = BuildConfigurator::inferBaseComponentIdForText($slot, (string) $state);

        if ($componentId < 1) {
            return;
        }

        $set($basePath, $componentId);

        $selectedIds = array_values(array_filter(
            array_map('intval', (array) ($get($selectedIdsPath) ?? [])),
            fn (int $id): bool => $id > 0 && $id !== $componentId,
        ));

        static::syncConfiguratorOptionsForSlot($slot, $selectedIds, $set, $get);
    }

    protected static function applyLegacyCardComponentSelection(string $slot, int $componentId, callable $get, callable $set): void
    {
        if ($componentId < 1) {
            return;
        }

        $component = static::activeComponentsById()->get($componentId);

        if (! $component instanceof Component || $component->type !== BuildConfigurator::componentTypeForSlot($slot)) {
            return;
        }

        $set($slot, (string) $component->name);

        static::syncBaseComponentFromLegacyField($slot, (string) $component->name, $get, $set);
    }

    protected static function syncConfiguratorOptionsForSlot(string $slot, array $selectedIds, callable $set, callable $get): void
    {
        $basePath = "configurator_slots.$slot.base_component_id";
        $selectedIdsPath = "configurator_slots.$slot.option_component_ids";
        $optionsPath = "configurator_slots.$slot.options";
        $baseComponentId = (int) ($get($basePath) ?? 0);
        $componentType = BuildConfigurator::componentTypeForSlot($slot);
        $availableComponents = static::activeComponentsById();

        $selectedIds = collect($selectedIds)
            ->map(fn ($id): int => (int) $id)
            ->filter(fn (int $id): bool => $id > 0 && $id !== $baseComponentId)
            ->unique()
            ->values()
            ->all();

        $existingRows = collect((array) ($get($optionsPath) ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->keyBy(fn (array $row): string => (string) ($row['component_id'] ?? ''));

        $rows = [];

        foreach ($selectedIds as $componentId) {
            $component = $availableComponents->get($componentId);

            if (! $component instanceof Component || $component->type !== $componentType) {
                continue;
            }

            $existing = (array) ($existingRows->get((string) $componentId) ?? []);
            $rows[] = [
                'component_id' => $componentId,
                'label' => trim((string) ($existing['label'] ?? '')),
                'description' => static::nullableString($existing['description'] ?? $component->summary),
                'price_delta' => max(0, (int) round((float) ($existing['price_delta'] ?? 0))),
                'is_active' => array_key_exists('is_active', $existing) ? (bool) $existing['is_active'] : true,
            ];
        }

        $set($selectedIdsPath, array_values(array_map('intval', array_column($rows, 'component_id'))));
        $set($optionsPath, $rows);
    }

    protected static function detectBaseComponentFromGroup(array $group): int
    {
        foreach ((array) ($group['options'] ?? []) as $option) {
            if (! is_array($option)) {
                continue;
            }

            if (($option['is_default'] ?? false) && (int) ($option['component_id'] ?? 0) > 0) {
                return (int) $option['component_id'];
            }
        }

        return 0;
    }

    protected static function configuratorSlotGuideHtml(string $slotLabel, string $optionsLabel): HtmlString
    {
        $slotLabel = e(trim($slotLabel) !== '' ? $slotLabel : 'комплектуюча');
        $optionsLabel = e(trim($optionsLabel) !== '' ? $optionsLabel : 'варіанти');

        return new HtmlString(
            '<div style="display:grid;gap:12px;padding:16px 18px;border:1px solid #e4ebf5;border-radius:20px;background:linear-gradient(180deg,#fbfdff,#f5f8fc);">'
            . '<div style="display:flex;flex-wrap:wrap;gap:10px;">'
            . '<span style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:#eef4ff;color:#23406d;font-size:12px;font-weight:800;">1. База збірки</span>'
            . '<span style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:#fff5e8;color:#9a5b00;font-size:12px;font-weight:800;">2. Доступні апгрейди</span>'
            . '<span style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:#eefbf3;color:#157347;font-size:12px;font-weight:800;">3. Ціна та опис</span>'
            . '</div>'
            . '<div style="display:grid;gap:6px;color:#516072;font-size:13px;line-height:1.5;">'
            . "<div><strong style=\"color:#111827;\">{$slotLabel}</strong> обирається як базова комплектуюча для цієї збірки.</div>"
            . "<div>Після цього нижче додаються <strong style=\"color:#111827;\">{$optionsLabel}</strong>, які покупець зможе вибрати в конфігураторі.</div>"
            . '</div>'
            . '</div>'
        );
    }

    protected static function configuratorOptionSelectionHintHtml(string $optionsLabel): HtmlString
    {
        $optionsLabel = e(trim($optionsLabel) !== '' ? $optionsLabel : 'варіанти');

        return new HtmlString(
            '<div style="display:grid;gap:8px;padding:14px 16px;border:1px dashed #dbe5f2;border-radius:18px;background:#fafcff;color:#516072;">'
            . '<strong style="font-size:14px;color:#334155;">Додай апгрейди для конфігуратора</strong>'
            . "<span style=\"font-size:13px;line-height:1.5;\">Обери додаткові <strong style=\"color:#111827;\">{$optionsLabel}</strong> зі списку вище. Після цього нижче зʼявляться картки варіантів, де можна задати назву, доплату та опис.</span>"
            . '</div>'
        );
    }

    protected static function configuratorOptionsSummaryHtml(string $slotLabel, int $baseComponentId, array $selectedComponentIds, array $configuredOptions): HtmlString
    {
        $slotLabel = e(trim($slotLabel) !== '' ? $slotLabel : 'комплектуюча');
        $selectedCount = collect($selectedComponentIds)
            ->map(fn ($id): int => (int) $id)
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->count();
        $configuredCount = collect($configuredOptions)
            ->filter(fn ($row): bool => is_array($row) && (int) ($row['component_id'] ?? 0) > 0)
            ->count();
        $activeCount = collect($configuredOptions)
            ->filter(fn ($row): bool => is_array($row) && (int) ($row['component_id'] ?? 0) > 0 && (! array_key_exists('is_active', $row) || (bool) $row['is_active']))
            ->count();
        $baseReady = $baseComponentId > 0;
        $visibilityLabel = $baseReady
            ? 'Блок показуватиметься на сторінці товару.'
            : 'Поки база не вибрана, блок буде прихований.';

        return new HtmlString(
            '<div style="display:grid;gap:10px;padding:16px 18px;border:1px solid #e2e8f0;border-radius:20px;background:linear-gradient(180deg,#fbfdff,#f5f8fc);">'
            . '<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;">'
            . '<div style="padding:12px 14px;border-radius:16px;background:' . ($baseReady ? '#eefbf3' : '#fff7eb') . ';border:1px solid ' . ($baseReady ? '#bde4c8' : '#f2d39b') . ';"><div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:800;">База</div><div style="margin-top:4px;font-size:18px;font-weight:900;color:#111827;">' . ($baseReady ? 'Готово' : 'Не задано') . '</div></div>'
            . '<div style="padding:12px 14px;border-radius:16px;background:#eef4ff;border:1px solid #d7e5ff;"><div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:800;">Обрано апгрейдів</div><div style="margin-top:4px;font-size:18px;font-weight:900;color:#111827;">' . $selectedCount . '</div></div>'
            . '<div style="padding:12px 14px;border-radius:16px;background:#fff9ef;border:1px solid #f5dfb4;"><div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:800;">Активних на сайті</div><div style="margin-top:4px;font-size:18px;font-weight:900;color:#111827;">' . $activeCount . ' / ' . $configuredCount . '</div></div>'
            . '</div>'
            . "<div style=\"font-size:13px;line-height:1.55;color:#516072;\"><strong style=\"color:#111827;\">{$slotLabel}</strong>: {$visibilityLabel}</div>"
            . '</div>'
        );
    }

    protected static function configuratorEmptyLibraryHtml(): HtmlString
    {
        return new HtmlString(
            '<div style="display:grid;gap:8px;padding:16px 18px;border:1px dashed #f3c98b;border-radius:18px;background:#fff8ef;color:#9a5b00;">'
            . '<strong style="font-size:14px;">У бібліотеці ще немає комплектуючих цього типу.</strong>'
            . '<span style="font-size:13px;line-height:1.5;">Спочатку додай комплектуючі в окремому розділі, а потім повернись до налаштування апгрейдів для збірки.</span>'
            . '</div>'
        );
    }

    protected static function configuratorSlotStatusHtml(int $baseComponentId): HtmlString
    {
        if ($baseComponentId > 0) {
            return new HtmlString(
                '<div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid #bde4c8;border-radius:18px;background:#eefaf1;color:#157347;">'
                . '<span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:999px;background:#16a34a;color:#fff;font-weight:900;">✓</span>'
                . '<div style="display:grid;gap:2px;">'
                . '<strong style="font-size:14px;">Базова комплектуюча вже задана</strong>'
                . '<span style="font-size:13px;line-height:1.45;">Цей блок можна показувати на сторінці товару й додавати до нього апгрейди.</span>'
                . '</div></div>'
            );
        }

        return new HtmlString(
            '<div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid #f2d39b;border-radius:18px;background:#fff7eb;color:#9a5b00;">'
            . '<span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:999px;background:#f59e0b;color:#fff;font-weight:900;">!</span>'
            . '<div style="display:grid;gap:2px;">'
            . '<strong style="font-size:14px;">Спочатку потрібно обрати базову комплектуючу</strong>'
            . '<span style="font-size:13px;line-height:1.45;">Поки база не задана, цей блок не буде показуватись покупцю на сторінці товару.</span>'
            . '</div></div>'
        );
    }

    protected static function configuratorOptionItemLabel(array $state): string
    {
        $componentId = (int) ($state['component_id'] ?? 0);
        $component = static::activeComponentsById()->get($componentId);
        $priceDelta = max(0, (int) ($state['price_delta'] ?? 0));
        $customLabel = trim((string) ($state['label'] ?? ''));

        if ($customLabel === '' && $component instanceof Component) {
            $customLabel = $component->name;
        }

        if ($customLabel === '') {
            $customLabel = 'Варіант конфігуратора';
        }

        $parts = [$customLabel];

        if ($priceDelta > 0) {
            $parts[] = '+' . number_format($priceDelta, 0, '', ' ') . ' ₴';
        }

        $parts[] = (! array_key_exists('is_active', $state) || (bool) $state['is_active'])
            ? 'активний'
            : 'вимкнений';

        return implode(' • ', $parts);
    }

    protected static function componentPreviewHtml(int $componentId, string $emptyText): HtmlString
    {
        $component = static::activeComponentsById()->get($componentId);

        if (! $component instanceof Component) {
            return new HtmlString(
                '<div style="display:grid;gap:8px;padding:16px 18px;border:1px dashed #d6dfeb;border-radius:20px;background:#fafcff;color:#64748b;">'
                . '<strong style="font-size:14px;color:#334155;">Комплектуюча ще не вибрана</strong>'
                . '<span style="font-size:13px;line-height:1.5;">' . e($emptyText) . '</span>'
                . '</div>'
            );
        }

        $imageUrl = e($component->imageUrl());
        $name = e($component->name);
        $type = e(strtoupper((string) $component->type));
        $meta = e(static::componentMetaLabel($component));
        $summary = e(trim((string) ($component->summary ?? '')));
        $imageLink = trim((string) $component->imageUrl());

        return new HtmlString(
            "<div style=\"display:flex;gap:14px;align-items:flex-start;padding:14px 16px;border:1px solid #dbe5f2;border-radius:20px;background:linear-gradient(180deg,#fbfdff,#f4f8fd);box-shadow:inset 0 1px 0 rgba(255,255,255,0.85);\">"
            . ($imageLink !== ''
                ? "<a href=\"" . e($imageLink) . "\" target=\"_blank\" rel=\"noreferrer noopener\" title=\"Відкрити фото\" style=\"display:inline-flex;flex:none;border-radius:18px;\">"
                    . "<img src=\"{$imageUrl}\" alt=\"{$name}\" style=\"width:76px;height:76px;border-radius:18px;object-fit:cover;background:#fff;border:1px solid #d6dfeb;box-shadow:0 10px 20px rgba(15,23,42,0.08);cursor:zoom-in;\">"
                    . "</a>"
                : "<img src=\"{$imageUrl}\" alt=\"{$name}\" style=\"width:76px;height:76px;border-radius:18px;object-fit:cover;background:#fff;border:1px solid #d6dfeb;flex:none;box-shadow:0 10px 20px rgba(15,23,42,0.08);\">")
            . "<div style=\"display:grid;gap:5px;min-width:0;\">"
            . "<div style=\"display:inline-flex;align-items:center;gap:8px;\"><span style=\"display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#5b3df5;font-size:11px;letter-spacing:.08em;text-transform:uppercase;font-weight:800;\">{$type}</span></div>"
            . "<div style=\"font-size:18px;line-height:1.3;color:#111827;font-weight:800;\">{$name}</div>"
            . ($meta !== '' ? "<div style=\"font-size:12px;color:#516072;font-weight:700;line-height:1.4;\">{$meta}</div>" : '')
            . ($summary !== '' ? "<div style=\"font-size:12px;color:#475569;line-height:1.5;\">{$summary}</div>" : '')
            . "</div></div>"
        );
    }

    protected static function componentMetaLabel(Component $component): string
    {
        $meta = is_array($component->meta ?? null) ? $component->meta : [];

        $parts = array_filter([
            trim((string) ($component->vendor ?? '')),
            static::componentSpecLabel($component),
            trim((string) ($meta['color'] ?? '')),
            trim((string) ($meta['family'] ?? '')),
            trim((string) ($meta['platform'] ?? '')),
            trim((string) ($component->sku ?? '')),
        ]);

        return implode(' • ', array_slice(array_values($parts), 0, 3));
    }

    protected static function componentSpecLabel(Component $component): string
    {
        return match ((string) $component->type) {
            'gpu' => (int) ($component->gpu_length_mm ?? 0) > 0 ? ((int) $component->gpu_length_mm . 'mm') : '',
            'cpu' => trim(implode(' / ', array_filter([(string) ($component->socket ?? ''), (int) ($component->cpu_tdp_w ?? 0) > 0 ? ((int) $component->cpu_tdp_w . 'W') : '']))),
            'motherboard' => trim(implode(' / ', array_filter([(string) ($component->form_factor ?? ''), (string) ($component->socket ?? '')]))),
            'ram' => trim(implode(' / ', array_filter([(int) ($component->memory_capacity_gb ?? 0) > 0 ? ((int) $component->memory_capacity_gb . 'GB') : '', (int) ($component->memory_speed_mhz ?? 0) > 0 ? ((int) $component->memory_speed_mhz . 'MHz') : '']))),
            'storage' => trim(implode(' / ', array_filter([(string) ($component->storage_interface ?? ''), (int) ($component->memory_capacity_gb ?? 0) > 0 ? ((int) $component->memory_capacity_gb . 'GB') : '']))),
            'psu' => (int) ($component->psu_wattage ?? 0) > 0 ? ((int) $component->psu_wattage . 'W') : '',
            'cooler' => trim(implode(' / ', array_filter([(int) ($component->radiator_size_mm ?? 0) > 0 ? ((int) $component->radiator_size_mm . 'mm') : '', (int) ($component->max_cooler_height_mm ?? 0) > 0 ? ((int) $component->max_cooler_height_mm . 'mm') : '']))),
            'case' => trim(implode(' / ', array_filter([(string) ($component->form_factor ?? ''), (int) ($component->max_gpu_length_mm ?? 0) > 0 ? ('GPU ' . (int) $component->max_gpu_length_mm . 'mm') : '']))),
            default => '',
        };
    }

    protected static function componentOptionsByType(string $slot): array
    {
        $type = BuildConfigurator::componentTypeForSlot($slot);

        return static::activeComponentsByType($type)
            ->mapWithKeys(fn (Component $component): array => [
                $component->id => static::componentOptionHtml($component),
            ])
            ->all();
    }

    protected static function componentOptionHtml(Component $component): string
    {
        $imageUrl = e($component->imageUrl());
        $name = e($component->name);
        $meta = e(static::componentMetaLabel($component));

        $metaLine = $meta !== ''
            ? "<div style=\"font-size:11px;color:#64748b;font-weight:700;line-height:1.35;\">{$meta}</div>"
            : '';

        return
            "<div style=\"display:flex;align-items:center;gap:10px;\">"
            . "<img src=\"{$imageUrl}\" alt=\"{$name}\" style=\"width:42px;height:42px;border-radius:10px;object-fit:cover;background:#fff;border:1px solid #dbe3f0;flex:none;\">"
            . "<div style=\"display:grid;gap:2px;min-width:0;\">"
            . "<div style=\"font-size:13px;line-height:1.35;color:#111827;font-weight:800;\">{$name}</div>"
            . $metaLine
            . "</div>"
            . "</div>";
    }

    protected static function activeComponentsByType(string $type): Collection
    {
        static $cache = [];

        if (! array_key_exists($type, $cache)) {
            $cache[$type] = Component::query()
                ->where('is_active', true)
                ->where('type', $type)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        }

        return $cache[$type];
    }

    protected static function activeComponentsById(): Collection
    {
        static $cache;

        if (! $cache instanceof Collection) {
            $cache = Component::query()
                ->where('is_active', true)
                ->orderBy('type')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->keyBy('id');
        }

        return $cache;
    }

    protected static function splitParagraphs(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $item): string => trim($item),
            preg_split("/(?:\r\n|\r|\n){2,}/", $value) ?: [],
        )));
    }

    protected static function splitLines(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $item): string => trim($item),
            preg_split("/\r\n|\r|\n/", $value) ?: [],
        )));
    }

    protected static function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    protected static function coverImageUrl(Build $record): ?string
    {
        return BuildImages::coverUrlForSlug((string) $record->slug);
    }

    protected static function coverPlaceholderUrl(): string
    {
        static $placeholderUrl = null;

        if (is_string($placeholderUrl)) {
            return $placeholderUrl;
        }

        $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" fill="none">
  <defs>
    <linearGradient id="bg" x1="36" y1="28" x2="286" y2="286" gradientUnits="userSpaceOnUse">
      <stop stop-color="#111827"/>
      <stop offset="1" stop-color="#312e81"/>
    </linearGradient>
    <linearGradient id="badge" x1="110" y1="132" x2="248" y2="222" gradientUnits="userSpaceOnUse">
      <stop stop-color="#f59e0b"/>
      <stop offset="1" stop-color="#ef4444"/>
    </linearGradient>
  </defs>
  <rect width="320" height="320" rx="36" fill="url(#bg)"/>
  <rect x="28" y="28" width="264" height="264" rx="26" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.14)" stroke-width="2"/>
  <rect x="88" y="74" width="144" height="112" rx="20" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.16)" stroke-width="2"/>
  <path d="M110 166L142 132C148 125 159 125 165 132L188 155L204 139C210 133 220 133 226 139L248 160V180C248 191 239 200 228 200H108C97 200 88 191 88 180V166H110Z" fill="url(#badge)"/>
  <circle cx="202" cy="110" r="18" fill="rgba(255,255,255,0.92)"/>
  <rect x="82" y="222" width="156" height="34" rx="17" fill="rgba(255,255,255,0.1)"/>
  <text x="160" y="244" fill="white" font-size="20" font-family="Arial, sans-serif" font-weight="700" text-anchor="middle">NO PHOTO</text>
</svg>
SVG;

        return $placeholderUrl = 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

}
