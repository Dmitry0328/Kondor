<?php

namespace App\Filament\Resources\Components;

use App\Filament\Clusters\ConfiguratorCluster;
use App\Filament\Resources\Components\Pages\ManageComponents;
use App\Models\Component;
use App\Support\BuildConfigurator;
use App\Support\ComponentImages;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $cluster = ConfiguratorCluster::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?int $navigationSort = 0;

    protected const SOCKET_OPTIONS = [
        'AM4' => 'AM4',
        'AM5' => 'AM5',
        'LGA1700' => 'LGA1700',
        'LGA1851' => 'LGA1851',
    ];

    protected const FORM_FACTOR_OPTIONS = [
        'ATX' => 'ATX',
        'mATX' => 'mATX',
        'mini-ITX' => 'mini-ITX',
        'E-ATX' => 'E-ATX',
    ];

    protected const MEMORY_TYPE_OPTIONS = [
        'DDR4' => 'DDR4',
        'DDR5' => 'DDR5',
    ];

    protected const STORAGE_INTERFACE_OPTIONS = [
        'NVMe' => 'NVMe',
        'SATA' => 'SATA',
    ];

    protected const RADIATOR_SIZE_OPTIONS = [
        120 => '120 мм',
        240 => '240 мм',
        280 => '280 мм',
        360 => '360 мм',
    ];

    protected const POSITION_START = '__start';

    public static function getNavigationLabel(): string
    {
        return 'Компоненти';
    }

    public static function getModelLabel(): string
    {
        return 'компонент';
    }

    public static function getPluralModelLabel(): string
    {
        return 'компоненти';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            static::basicFormSection(),
            static::categoryDetailsSection(),
            static::compatibilitySection(),
        ]);
    }

    protected static function basicFormSection(): Section
    {
        return Section::make('Основне')
            ->schema([
                Select::make('type')
                    ->label('Категорія')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->options(BuildConfigurator::componentTypeOptions())
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('position_after_id', static::defaultPositionAfterId((string) $state));
                    }),
                TextInput::make('name')
                    ->label('Назва')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('vendor')
                    ->label('Бренд')
                    ->maxLength(255),
                TextInput::make('sku')
                    ->label('SKU / артикул')
                    ->maxLength(255),
                Textarea::make('summary')
                    ->label('Короткий опис')
                    ->rows(3)
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
                    ->disk('public')
                    ->directory('components')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->helperText('Можна завантажити кілька фото. Перше фото буде головним і відкриватиметься першим у картці товару.')
                    ->columnSpanFull(),
                Select::make('position_after_id')
                    ->label('Позиція в списку')
                    ->native(false)
                    ->options(fn (callable $get, ?Component $record): array => static::positionOptions((string) ($get('type') ?? ''), $record))
                    ->visible(fn (callable $get, ?Component $record): bool => static::hasPositionChoices((string) ($get('type') ?? ''), $record))
                    ->helperText('Обереш, після якого компонента в цій категорії поставити поточний.')
                    ->default(fn (callable $get, ?Component $record): string | int | null => static::defaultPositionAfterId((string) ($get('type') ?? ''), $record)),
                Toggle::make('is_active')
                    ->label('Активний')
                    ->default(true),
            ])
            ->columns(2);
    }

    protected static function categoryDetailsSection(): Section
    {
        return Section::make('Характеристики категорії')
            ->description('Показуємо тільки ті поля, які потрібні для вибраної категорії.')
            ->schema([
                CheckboxList::make('socket')
                    ->label('Socket')
                    ->options(static::SOCKET_OPTIONS)
                    ->columns(2)
                    ->maxItems(1)
                    ->hidden(fn (callable $get): bool => ! in_array($get('type'), ['cpu', 'motherboard'], true))
                    ->helperText('Оберіть сокет для процесора або материнської плати.')
                    ->afterStateHydrated(fn (CheckboxList $component, $state): CheckboxList => $component->state(static::explodeSingleChoice($state)))
                    ->dehydrateStateUsing(fn ($state): ?string => static::implodeSingleChoice($state)),
                CheckboxList::make('form_factor')
                    ->label('Form factor')
                    ->options(static::FORM_FACTOR_OPTIONS)
                    ->columns(2)
                    ->maxItems(1)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'motherboard')
                    ->helperText('Форм-фактор самої материнської плати.')
                    ->afterStateHydrated(fn (CheckboxList $component, $state): CheckboxList => $component->state(static::explodeSingleChoice($state)))
                    ->dehydrateStateUsing(fn ($state): ?string => static::implodeSingleChoice($state)),
                CheckboxList::make('ram_type')
                    ->label("Тип пам'яті")
                    ->options(static::MEMORY_TYPE_OPTIONS)
                    ->columns(2)
                    ->maxItems(1)
                    ->hidden(fn (callable $get): bool => ! in_array($get('type'), ['motherboard', 'ram'], true))
                    ->helperText("Для материнської плати або комплекту RAM. Обирається як одна сумісна пам'ять.")
                    ->afterStateHydrated(fn (CheckboxList $component, $state): CheckboxList => $component->state(static::explodeSingleChoice($state)))
                    ->dehydrateStateUsing(fn ($state): ?string => static::implodeSingleChoice($state)),
                CheckboxList::make('supported_mb_form_factors')
                    ->label('Підтримувані форм-фактори плат')
                    ->options(static::FORM_FACTOR_OPTIONS)
                    ->columns(2)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'case')
                    ->helperText('Для корпусу: які материнські плати фізично помістяться всередині.'),
                CheckboxList::make('supported_sockets')
                    ->label('Підтримувані сокети')
                    ->options(static::SOCKET_OPTIONS)
                    ->columns(2)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'cooler')
                    ->helperText('Для кулера: які сокети процесора він підтримує.'),
                CheckboxList::make('supported_radiator_sizes')
                    ->label('Підтримувані радіатори СВО')
                    ->options(static::RADIATOR_SIZE_OPTIONS)
                    ->columns(2)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'case')
                    ->helperText('Для корпусу: які розміри радіатора водяного охолодження можна встановити.'),
                TextInput::make('max_gpu_length_mm')
                    ->label('Макс. довжина GPU, мм')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'case')
                    ->helperText('Максимальна довжина відеокарти, яка влізе в корпус.'),
                TextInput::make('max_cooler_height_mm')
                    ->label('Ліміт висоти кулера, мм')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => ! in_array($get('type'), ['case', 'cooler'], true))
                    ->helperText('Для корпусу це ліміт, для повітряного кулера це його фактична висота.'),
                TextInput::make('gpu_length_mm')
                    ->label('Довжина GPU, мм')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'gpu'),
                TextInput::make('radiator_size_mm')
                    ->label('Розмір радіатора, мм')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'cooler')
                    ->helperText('Для СВО. Для повітряного кулера можна лишити порожнім.'),
                TextInput::make('memory_modules')
                    ->label('Кількість модулів')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'ram')
                    ->helperText('Наприклад: 2 для комплекту 2x16 GB.'),
                TextInput::make('memory_capacity_gb')
                    ->label('Обсяг, GB')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => ! in_array($get('type'), ['ram', 'storage'], true))
                    ->helperText("Для RAM це сумарний обсяг комплекту, для накопичувача — місткість диска."),
                TextInput::make('memory_speed_mhz')
                    ->label('Частота RAM, MHz')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'ram'),
                Select::make('storage_interface')
                    ->label('Інтерфейс накопичувача')
                    ->native(false)
                    ->options(static::STORAGE_INTERFACE_OPTIONS)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'storage'),
            ])
            ->columns(2);
    }

    protected static function compatibilitySection(): Section
    {
        return Section::make('Сумісність і додатково')
            ->description('Необов’язкові поля. Якщо не заповнювати, перевірка сумісності просто буде менш точною.')
            ->schema([
                TextInput::make('gpu_power_connectors')
                    ->label('PCIe-конектори GPU')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'gpu')
                    ->helperText('Скільки PCIe-конекторів живлення потрібно відеокарті.'),
                TextInput::make('cpu_tdp_w')
                    ->label('TDP CPU, W')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'cpu')
                    ->helperText('Теплопакет процесора. Якщо не знаєте значення, можна лишити порожнім.'),
                TextInput::make('psu_wattage')
                    ->label('Потужність БЖ, W')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'psu')
                    ->helperText('Номінальна потужність блока живлення.'),
                TextInput::make('pcie_power_connectors')
                    ->label('PCIe-конектори БЖ')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (callable $get): bool => $get('type') !== 'psu')
                    ->helperText('Скільки окремих PCIe-конекторів живлення є у блока живлення.'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('type')->orderBy('sort_order')->orderBy('name'))
            ->columns([
                ViewColumn::make('preview')
                    ->label('Фото')
                    ->view('filament.tables.columns.admin-image-preview')
                    ->viewData(function (Component $record): array {
                        $imageUrl = ComponentImages::primaryUploadedUrlForComponent($record);

                        return [
                            'imageUrl' => $imageUrl,
                            'placeholderUrl' => ComponentImages::placeholderUrl((string) $record->type, (string) $record->name),
                            'hasImage' => $imageUrl !== null,
                            'caption' => (string) $record->name,
                            'alt' => (string) $record->name,
                        ];
                    }),
                TextColumn::make('type')
                    ->label('Категорія')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => BuildConfigurator::componentTypeLabel($state)),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(['name', 'vendor', 'sku', 'slug'])
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('socket')
                    ->label('Socket')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ram_type')
                    ->label('Тип пам’яті')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('form_factor')
                    ->label('Form factor')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ManageComponents::route('/'),
        ];
    }

    public static function makeCreateAction(): CreateAction
    {
        return CreateAction::make()
            ->label('Новий компонент')
            ->modalWidth('7xl')
            ->mutateDataUsing(fn (array $data): array => static::mutateFormData($data))
            ->using(function (array $data, string $model): Component {
                $positionAfterId = $data['position_after_id'] ?? null;

                unset($data['position_after_id']);

                /** @var Component $record */
                $record = new $model();
                $record->fill($data);
                $record->sort_order = static::nextSortOrderForType((string) $record->type);
                $record->save();

                static::resequenceRecord($record, $positionAfterId);

                return $record;
            });
    }

    public static function makeEditAction(): EditAction
    {
        return EditAction::make()
            ->modalWidth('7xl')
            ->mutateRecordDataUsing(fn (array $data, Component $record): array => static::mutateRecordDataForEdit($data, $record))
            ->mutateDataUsing(fn (array $data, Component $record): array => static::mutateFormData($data, $record))
            ->using(function (Component $record, array $data): Component {
                $positionAfterId = $data['position_after_id'] ?? static::defaultPositionAfterId((string) $record->type, $record);
                $previousType = (string) $record->type;

                unset($data['position_after_id']);

                $record->fill($data);
                $record->save();

                static::resequenceRecord($record, $positionAfterId, $previousType);

                return $record;
            });
    }

    protected static function mutateRecordDataForEdit(array $data, Component $record): array
    {
        $data['gallery_paths'] = static::galleryPathsForRecord($record);
        $data['position_after_id'] = static::defaultPositionAfterId((string) ($data['type'] ?? $record->type), $record);

        return $data;
    }

    protected static function mutateFormData(array $data, ?Component $record = null): array
    {
        $type = trim((string) ($data['type'] ?? $record?->type ?? ''));

        $data['type'] = $type;
        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['slug'] = trim((string) ($data['slug'] ?? ''));
        $data['vendor'] = static::nullableText($data['vendor'] ?? null);
        $data['sku'] = static::nullableText($data['sku'] ?? null);
        $data['summary'] = static::nullableText($data['summary'] ?? null);
        $data['gallery_paths'] = static::normalizeStringList($data['gallery_paths'] ?? []);
        $data['socket'] = static::implodeSingleChoice($data['socket'] ?? null);
        $data['ram_type'] = static::implodeSingleChoice($data['ram_type'] ?? null);
        $data['form_factor'] = static::implodeSingleChoice($data['form_factor'] ?? null);
        $data['supported_mb_form_factors'] = static::normalizeStringList($data['supported_mb_form_factors'] ?? []);
        $data['supported_sockets'] = static::normalizeStringList($data['supported_sockets'] ?? []);
        $data['supported_radiator_sizes'] = static::normalizeIntegerList($data['supported_radiator_sizes'] ?? []);
        $data['max_gpu_length_mm'] = static::nullableInt($data['max_gpu_length_mm'] ?? null);
        $data['max_cooler_height_mm'] = static::nullableInt($data['max_cooler_height_mm'] ?? null);
        $data['gpu_length_mm'] = static::nullableInt($data['gpu_length_mm'] ?? null);
        $data['gpu_power_w'] = static::nullableInt($data['gpu_power_w'] ?? $record?->gpu_power_w);
        $data['gpu_power_connectors'] = static::nullableInt($data['gpu_power_connectors'] ?? null);
        $data['cpu_tdp_w'] = static::nullableInt($data['cpu_tdp_w'] ?? null);
        $data['psu_wattage'] = static::nullableInt($data['psu_wattage'] ?? null);
        $data['pcie_power_connectors'] = static::nullableInt($data['pcie_power_connectors'] ?? null);
        $data['radiator_size_mm'] = static::nullableInt($data['radiator_size_mm'] ?? null);
        $data['memory_modules'] = static::nullableInt($data['memory_modules'] ?? null);
        $data['memory_capacity_gb'] = static::nullableInt($data['memory_capacity_gb'] ?? null);
        $data['memory_speed_mhz'] = static::nullableInt($data['memory_speed_mhz'] ?? null);
        $data['storage_interface'] = static::nullableText($data['storage_interface'] ?? null);
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        foreach (static::specFieldDefaults() as $field => $default) {
            if (static::fieldBelongsToType($field, $type)) {
                continue;
            }

            $data[$field] = $default;
        }

        return $data;
    }

    protected static function galleryPathsForRecord(Component $record): array
    {
        $paths = static::normalizeStringList($record->gallery_paths ?? []);

        if ($paths !== []) {
            return $paths;
        }

        $legacyPath = ComponentImages::legacyPath((string) $record->slug);

        return filled($legacyPath) ? [(string) $legacyPath] : [];
    }

    protected static function positionOptions(string $type, ?Component $record = null): array
    {
        $components = static::positionableComponents($type, $record);

        if ($components->isEmpty()) {
            return [];
        }

        $options = [
            static::POSITION_START => '[0] На початок списку',
        ];

        foreach ($components->values() as $index => $component) {
            $options[$component->getKey()] = '[' . ($index + 1) . '] ' . $component->name;
        }

        return $options;
    }

    protected static function hasPositionChoices(string $type, ?Component $record = null): bool
    {
        return static::positionableComponents($type, $record)->isNotEmpty();
    }

    protected static function defaultPositionAfterId(string $type, ?Component $record = null): string | int | null
    {
        $type = trim($type);

        if ($type === '') {
            return null;
        }

        $ordered = Component::query()
            ->where('type', $type)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        if ($ordered->isEmpty()) {
            return null;
        }

        if (! $record?->exists || $record->type !== $type) {
            return $ordered->last()?->getKey();
        }

        $currentIndex = $ordered->search(fn (Component $item): bool => $item->is($record));

        if ($currentIndex === false) {
            return $ordered->last()?->getKey();
        }

        $previous = $ordered->slice(0, $currentIndex)->last();
        $otherCount = $ordered->count() - 1;

        if ($otherCount < 1) {
            return null;
        }

        return $previous?->getKey() ?? static::POSITION_START;
    }

    protected static function positionableComponents(string $type, ?Component $record = null)
    {
        $type = trim($type);

        if ($type === '') {
            return Component::query()->whereRaw('1 = 0')->get();
        }

        return Component::query()
            ->where('type', $type)
            ->when($record?->exists, fn (Builder $query): Builder => $query->whereKeyNot($record->getKey()))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    protected static function nextSortOrderForType(string $type): int
    {
        $lastSortOrder = (int) Component::query()
            ->where('type', trim($type))
            ->max('sort_order');

        return max(10, ((int) floor($lastSortOrder / 10) + 1) * 10);
    }

    protected static function resequenceRecord(Component $record, mixed $positionAfterId, ?string $previousType = null): void
    {
        $siblings = Component::query()
            ->where('type', $record->type)
            ->whereKeyNot($record->getKey())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        $normalizedPosition = static::normalizePositionAfterId($positionAfterId);
        $ordered = collect();
        $inserted = false;

        if ($normalizedPosition === 0) {
            $ordered->push($record);
            $inserted = true;
        }

        foreach ($siblings as $sibling) {
            $ordered->push($sibling);

            if (! $inserted && $normalizedPosition !== null && $normalizedPosition > 0 && $sibling->getKey() === $normalizedPosition) {
                $ordered->push($record);
                $inserted = true;
            }
        }

        if (! $inserted) {
            $ordered->push($record);
        }

        foreach ($ordered->values() as $index => $item) {
            $item->forceFill([
                'sort_order' => ($index + 1) * 10,
            ])->saveQuietly();
        }

        if (filled($previousType) && $previousType !== $record->type) {
            static::resequenceType((string) $previousType);
        }
    }

    protected static function resequenceType(string $type): void
    {
        Component::query()
            ->where('type', $type)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values()
            ->each(function (Component $component, int $index): void {
                $component->forceFill([
                    'sort_order' => ($index + 1) * 10,
                ])->saveQuietly();
            });
    }

    protected static function normalizePositionAfterId(mixed $value): ?int
    {
        if ($value === static::POSITION_START) {
            return 0;
        }

        if ($value === null || $value === '') {
            return null;
        }

        $value = (int) $value;

        return $value >= 0 ? $value : null;
    }

    protected static function explodeSingleChoice(mixed $state): array
    {
        if (is_array($state)) {
            return static::normalizeStringList($state);
        }

        $value = trim((string) $state);

        return $value !== '' ? [$value] : [];
    }

    protected static function implodeSingleChoice(mixed $state): ?string
    {
        if (is_array($state)) {
            foreach ($state as $value) {
                $value = trim((string) $value);

                if ($value !== '') {
                    return $value;
                }
            }

            return null;
        }

        $value = trim((string) $state);

        return $value !== '' ? $value : null;
    }

    protected static function normalizeStringList(mixed $values): array
    {
        $values = is_array($values) ? $values : [];

        return array_values(array_unique(array_filter(array_map(static function ($value): ?string {
            $value = trim((string) $value);

            return $value !== '' ? $value : null;
        }, $values))));
    }

    protected static function normalizeIntegerList(mixed $values): array
    {
        $values = is_array($values) ? $values : [];

        return array_values(array_unique(array_filter(array_map(static function ($value): ?int {
            if ($value === null || $value === '') {
                return null;
            }

            $value = (int) $value;

            return $value > 0 ? $value : null;
        }, $values))));
    }

    protected static function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = (int) $value;

        return $value > 0 ? $value : null;
    }

    protected static function nullableText(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    protected static function fieldBelongsToType(string $field, string $type): bool
    {
        return in_array($field, static::specFieldsForType($type), true);
    }

    protected static function specFieldsForType(string $type): array
    {
        return [
            'cpu' => ['socket', 'cpu_tdp_w'],
            'gpu' => ['gpu_length_mm', 'gpu_power_w', 'gpu_power_connectors'],
            'motherboard' => ['socket', 'ram_type', 'form_factor'],
            'ram' => ['ram_type', 'memory_modules', 'memory_capacity_gb', 'memory_speed_mhz'],
            'storage' => ['memory_capacity_gb', 'storage_interface'],
            'psu' => ['psu_wattage', 'pcie_power_connectors'],
            'case' => ['supported_mb_form_factors', 'supported_radiator_sizes', 'max_gpu_length_mm', 'max_cooler_height_mm'],
            'cooler' => ['supported_sockets', 'max_cooler_height_mm', 'radiator_size_mm'],
        ][$type] ?? [];
    }

    protected static function specFieldDefaults(): array
    {
        return [
            'socket' => null,
            'ram_type' => null,
            'form_factor' => null,
            'supported_mb_form_factors' => [],
            'supported_sockets' => [],
            'supported_radiator_sizes' => [],
            'max_gpu_length_mm' => null,
            'max_cooler_height_mm' => null,
            'gpu_length_mm' => null,
            'gpu_power_w' => null,
            'gpu_power_connectors' => null,
            'cpu_tdp_w' => null,
            'psu_wattage' => null,
            'pcie_power_connectors' => null,
            'radiator_size_mm' => null,
            'memory_modules' => null,
            'memory_capacity_gb' => null,
            'memory_speed_mhz' => null,
            'storage_interface' => null,
        ];
    }
}
