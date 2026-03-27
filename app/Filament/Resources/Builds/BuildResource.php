<?php

namespace App\Filament\Resources\Builds;

use App\Filament\Resources\Builds\Pages\CreateBuild;
use App\Filament\Resources\Builds\Pages\EditBuild;
use App\Filament\Resources\Builds\Pages\ListBuilds;
use App\Models\Build;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
        return $schema->components([
            Section::make('Основне')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Використовується в URL. Якщо зміниш slug, привʼязані фото збірки теж переїдуть на новий ключ.')
                        ->rule(fn ($record): Unique => Rule::unique('builds', 'slug')->ignore($record)),
                    Select::make('tone')
                        ->label('Колір картки')
                        ->required()
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
                        ->minValue(0)
                        ->required(),
                    TextInput::make('fps_score')
                        ->label('FPS score')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make('sort_order')
                        ->label('Порядок')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Показувати на сайті')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Картка збірки')
                ->description('Ці поля показуються в картках на головній, у каталозі та в кошику.')
                ->schema([
                    TextInput::make('gpu')
                        ->label('Відеокарта')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('cpu')
                        ->label('Процесор')
                        ->required()
                        ->rows(3),
                    TextInput::make('ram')
                        ->label("Оперативна пам'ять")
                        ->required()
                        ->maxLength(255),
                    TextInput::make('storage')
                        ->label('Накопичувач')
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(2),

            Section::make('Характеристики сторінки товару')
                ->description('Фото можна змінювати прямо на сайті кліком по зображенню, коли ти залогінений як адмін.')
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
                        ->collapsible(),
                ]),

            Section::make('Блок "Про збірку"')
                ->description('Кожен рядок у списках — окремим пунктом. Для вступу роби абзаци через порожній рядок.')
                ->schema([
                    Textarea::make('about_intro_text')
                        ->label('Вступні абзаци')
                        ->rows(6)
                        ->helperText('Наприклад: короткий опис збірки, далі абзац про продуктивність.'),
                    Textarea::make('about_notes_text')
                        ->label('Короткі примітки')
                        ->rows(5)
                        ->helperText('По одному пункту на рядок: колір, роздільна здатність, гарантія тощо.'),
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
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
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
                TextColumn::make('fps_score')
                    ->label('FPS')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
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

    public static function expandAboutForForm(?array $about): array
    {
        $about ??= [];

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
}
