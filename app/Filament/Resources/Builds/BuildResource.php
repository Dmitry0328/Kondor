<?php

namespace App\Filament\Resources\Builds;

use App\Filament\Resources\Builds\Pages\CreateBuild;
use App\Filament\Resources\Builds\Pages\EditBuild;
use App\Filament\Resources\Builds\Pages\ListBuilds;
use App\Models\Build;
use App\Models\SiteImage;
use App\Support\FpsCatalog;
use App\Support\FpsProfiles;
use App\Support\SiteImages;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
                        ->helperText("Використовується в URL. Якщо змінити slug, прив'язані фото збірки також перейдуть на новий ключ.")
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
                    FileUpload::make('cover_upload')
                        ->label('Фото картки')
                        ->image()
                        ->disk('public')
                        ->directory('site-images')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(10240)
                        ->fetchFileInformation(true)
                        ->deletable()
                        ->columnSpanFull()
                        ->helperText('JPG/PNG/WEBP до 10MB. Якщо очистити поле, фото буде видалено.'),
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
                        ->collapsible(),
                ]),

            Section::make('Блок "Про збірку"')
                ->description('Кожен рядок у списках — окремий пункт. Для вступу роби абзаци через порожній рядок.')
                ->schema([
                    Textarea::make('about_intro_text')
                        ->label('Вступні абзаци')
                        ->rows(6),
                    Textarea::make('about_notes_text')
                        ->label('Короткі примітки')
                        ->rows(5)
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
                ->columns(2),

            Section::make('FPS (ручне керування)')
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
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('cover')
                    ->label('Фото')
                    ->square()
                    ->getStateUsing(fn (Build $record): ?string => SiteImages::url(static::coverImageKey($record->slug))),
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
                    ->label('FPS (дефолт)')
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

    public static function coverImagePathForSlug(?string $slug): ?string
    {
        $slug = trim((string) $slug);

        if ($slug === '') {
            return null;
        }

        return SiteImage::query()
            ->where('key', static::coverImageKey($slug))
            ->value('path');
    }

    public static function syncCoverImage(Build $build, mixed $coverUpload): void
    {
        $slug = trim((string) $build->slug);

        if ($slug === '') {
            return;
        }

        $key = static::coverImageKey($slug);
        $nextPath = is_string($coverUpload) ? trim($coverUpload) : null;
        $existing = SiteImage::query()->firstWhere('key', $key);

        if ($nextPath === null || $nextPath === '') {
            if (! $existing) {
                return;
            }

            if ($existing->path) {
                Storage::disk($existing->disk ?: 'public')->delete($existing->path);
            }

            $existing->delete();
            SiteImages::flush();

            return;
        }

        if ($existing && $existing->path === $nextPath) {
            return;
        }

        if ($existing?->path) {
            Storage::disk($existing->disk ?: 'public')->delete($existing->path);
        }

        SiteImage::query()->updateOrCreate(
            ['key' => $key],
            [
                'disk' => 'public',
                'path' => $nextPath,
                'updated_by' => Auth::id(),
            ],
        );

        SiteImages::flush();
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

    protected static function coverImageKey(string $slug): string
    {
        return 'build.' . trim($slug) . '.cover';
    }
}
