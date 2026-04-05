<?php

namespace App\Support;

use App\Models\Component;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class BuildConfigurator
{
    public const BASE_SLOTS = [
        'cpu',
        'gpu',
        'motherboard',
        'ram',
        'storage',
        'psu',
        'case',
        'cooler',
        'fans',
        'lighting',
        'network',
        'sound',
        'capture',
        'adapters',
        'modding',
        'other',
    ];

    public static function slotDefinitions(): array
    {
        return [
            'cpu' => ['label' => 'Процесор', 'group_title' => 'Заміна процесора', 'component_type' => 'cpu'],
            'gpu' => ['label' => 'Відеокарта', 'group_title' => 'Заміна відеокарти', 'component_type' => 'gpu'],
            'motherboard' => ['label' => 'Материнська плата', 'group_title' => 'Заміна материнської плати', 'component_type' => 'motherboard'],
            'ram' => ['label' => "Оперативна пам'ять", 'group_title' => 'Зміна ОЗП', 'component_type' => 'ram'],
            'storage' => ['label' => 'Накопичувач', 'group_title' => 'Заміна накопичувача', 'component_type' => 'storage'],
            'psu' => ['label' => 'Блок живлення', 'group_title' => 'Покращення БЖ', 'component_type' => 'psu'],
            'case' => ['label' => 'Корпус', 'group_title' => 'Корпус', 'component_type' => 'case'],
            'cooler' => ['label' => 'Охолодження CPU', 'group_title' => 'Встановлення охолодження CPU', 'component_type' => 'cooler'],
            'fans' => ['label' => 'Вентилятори', 'group_title' => 'Вентилятори', 'component_type' => 'fans'],
            'lighting' => ['label' => 'Підсвічування', 'group_title' => 'Підсвічування', 'component_type' => 'lighting'],
            'network' => ['label' => 'Мережеві модулі', 'group_title' => 'Мережеві модулі', 'component_type' => 'network'],
            'sound' => ['label' => 'Звук', 'group_title' => 'Аудіо / звукові плати', 'component_type' => 'sound'],
            'capture' => ['label' => 'Capture / expansion', 'group_title' => 'Capture / expansion', 'component_type' => 'capture'],
            'adapters' => ['label' => 'Адаптери', 'group_title' => 'Додавання адаптерів', 'component_type' => 'adapters'],
            'modding' => ['label' => 'Моддинг', 'group_title' => 'Моддинг', 'component_type' => 'modding'],
            'other' => ['label' => 'Інші апгрейди', 'group_title' => 'Інші апгрейди', 'component_type' => 'other'],
        ];
    }

    public static function slotOptions(): array
    {
        return collect(static::slotDefinitions())
            ->mapWithKeys(fn (array $definition, string $slot): array => [$slot => $definition['label']])
            ->all();
    }

    public static function defaultGroupTitle(string $slot): string
    {
        return static::slotDefinitions()[$slot]['group_title'] ?? (static::slotOptions()[$slot] ?? 'Опція');
    }

    public static function componentTypeForSlot(string $slot): string
    {
        return static::slotDefinitions()[$slot]['component_type'] ?? 'other';
    }

    public static function componentTypeOptions(): array
    {
        return [
            'cpu' => 'Процесори',
            'gpu' => 'Відеокарти',
            'motherboard' => 'Материнські плати',
            'ram' => 'Оперативна пам’ять',
            'storage' => 'Накопичувачі',
            'psu' => 'Блоки живлення',
            'case' => 'Корпуси',
            'cooler' => 'Охолодження CPU',
            'fans' => 'Вентилятори',
            'lighting' => 'Підсвітка',
            'network' => 'Мережеві модулі',
            'sound' => 'Звук',
            'capture' => 'Capture / expansion',
            'adapters' => 'Адаптери',
            'modding' => 'Моддинг',
            'other' => 'Інші апгрейди',
        ];
    }

    public static function componentTypeIcon(?string $type): Heroicon
    {
        return match ($type) {
            'all' => Heroicon::OutlinedSquares2x2,
            'cpu' => Heroicon::OutlinedCpuChip,
            'gpu' => Heroicon::OutlinedPhoto,
            'motherboard' => Heroicon::OutlinedServerStack,
            'ram' => Heroicon::OutlinedQueueList,
            'storage' => Heroicon::OutlinedCircleStack,
            'psu' => Heroicon::OutlinedBolt,
            'case' => Heroicon::OutlinedArchiveBox,
            'cooler' => Heroicon::OutlinedSparkles,
            'fans' => Heroicon::OutlinedBars3BottomLeft,
            'lighting' => Heroicon::OutlinedSwatch,
            'network' => Heroicon::OutlinedWifi,
            'sound' => Heroicon::OutlinedSpeakerWave,
            'capture' => Heroicon::OutlinedVideoCamera,
            'adapters' => Heroicon::OutlinedPuzzlePiece,
            'modding' => Heroicon::OutlinedPaintBrush,
            default => Heroicon::OutlinedCubeTransparent,
        };
    }

    public static function componentTypeLabel(string $type): string
    {
        return static::componentTypeOptions()[$type] ?? strtoupper($type);
    }

    public static function componentOptions(): array
    {
        return static::componentQuery()
            ->get()
            ->mapWithKeys(fn (Component $component): array => [
                $component->id => '[' . strtoupper($component->type) . '] ' . $component->name,
            ])
            ->all();
    }

    public static function normalizeBaseComponents(?array $state): ?array
    {
        if (! is_array($state)) {
            return null;
        }

        $components = static::componentQuery()->get()->keyBy('id');
        $normalized = [];

        foreach (self::BASE_SLOTS as $slot) {
            $componentId = (int) ($state[$slot] ?? 0);
            $component = $components->get($componentId);

            if (
                $componentId > 0 &&
                $component instanceof Component &&
                $component->type === static::componentTypeForSlot($slot)
            ) {
                $normalized[$slot] = $componentId;
            }
        }

        return $normalized !== [] ? $normalized : null;
    }

    public static function inferBaseComponents(array $build): ?array
    {
        $normalized = [];

        foreach (self::BASE_SLOTS as $slot) {
            $componentId = static::inferBaseComponentIdForSlot($build, $slot);

            if ($componentId > 0) {
                $normalized[$slot] = $componentId;
            }
        }

        return $normalized !== [] ? $normalized : null;
    }

    public static function inferBaseComponentIdForText(string $slot, ?string $text): int
    {
        $text = trim((string) $text);

        if ($text === '') {
            return 0;
        }

        return static::matchComponentIdByText(
            static::componentTypeForSlot($slot),
            $text,
        );
    }

    public static function normalizeGroups(?array $groups): ?array
    {
        if (! is_array($groups)) {
            return null;
        }

        $components = static::componentQuery()
            ->get()
            ->keyBy('id');
        $normalized = [];

        foreach (array_values($groups) as $groupIndex => $group) {
            if (! is_array($group)) {
                continue;
            }

            $slot = trim((string) ($group['slot'] ?? ''));
            $slot = array_key_exists($slot, static::slotOptions()) ? $slot : 'other';
            $title = trim((string) ($group['title'] ?? static::defaultGroupTitle($slot)));
            $groupKey = Str::slug((string) ($group['key'] ?? $slot ?: $title));

            if ($groupKey === '') {
                $groupKey = $slot !== '' ? $slot : ('group-' . ($groupIndex + 1));
            }

            $options = [];
            $usedOptionKeys = [];

            foreach ((array) ($group['options'] ?? []) as $optionIndex => $option) {
                if (! is_array($option)) {
                    continue;
                }

                $component = null;
                $componentId = (int) ($option['component_id'] ?? 0);

                if ($componentId > 0) {
                    $component = $components->get($componentId);

                    if (! $component instanceof Component) {
                        continue;
                    }

                    if ($component->type !== static::componentTypeForSlot($slot)) {
                        continue;
                    }
                }

                $label = trim((string) ($option['label'] ?? ($component?->name ?? '')));

                if ($label === '') {
                    continue;
                }

                $optionKey = Str::slug((string) ($option['key'] ?? ($component ? ('component-' . $component->id) : $label)));

                if ($optionKey === '') {
                    $optionKey = 'option-' . ($optionIndex + 1);
                }

                $baseOptionKey = $optionKey;
                $duplicateIndex = 2;

                while (isset($usedOptionKeys[$optionKey])) {
                    $optionKey = $baseOptionKey . '-' . $duplicateIndex;
                    $duplicateIndex++;
                }

                $usedOptionKeys[$optionKey] = true;

                $options[] = [
                    'key' => $optionKey,
                    'component_id' => $component?->id,
                    'label' => $label,
                    'description' => static::nullableText($option['description'] ?? ($component?->summary ?? null)),
                    'price_delta' => max(0, (int) round((float) ($option['price_delta'] ?? 0))),
                    'is_default' => (bool) ($option['is_default'] ?? false),
                    'is_active' => array_key_exists('is_active', $option) ? (bool) $option['is_active'] : true,
                ];
            }

            if ($options !== []) {
                $defaultFound = false;

                foreach ($options as $index => $option) {
                    if ($option['is_default'] && ! $defaultFound) {
                        $defaultFound = true;
                        continue;
                    }

                    $options[$index]['is_default'] = false;
                }

                if (! $defaultFound) {
                    $options[0]['is_default'] = true;
                }
            }

            $normalized[] = [
                'key' => $groupKey,
                'title' => $title !== '' ? $title : static::defaultGroupTitle($slot),
                'description' => static::nullableText($group['description'] ?? null),
                'slot' => $slot,
                'options' => $options,
            ];
        }

        return $normalized !== [] ? $normalized : null;
    }

    public static function storefrontPayload(array $build): array
    {
        $baseComponentIds = static::normalizeBaseComponents((array) ($build['base_components'] ?? [])) ?? [];
        $groups = static::normalizeGroups((array) ($build['configurator_groups'] ?? [])) ?? [];

        if ($groups === [] || $baseComponentIds === []) {
            return [
                'enabled' => false,
                'groups' => [],
                'client' => [
                    'enabled' => false,
                    'baseComponents' => new \stdClass(),
                    'groups' => [],
                    'components' => new \stdClass(),
                    'defaults' => new \stdClass(),
                    'slotLabels' => static::slotOptions(),
                ],
                'defaults' => [],
                'selectedSummary' => [],
                'additionalPrice' => 0,
                'totalPrice' => (int) ($build['price_raw'] ?? 0),
                'compatibility' => [
                    'is_valid' => true,
                    'messages' => [],
                ],
            ];
        }

        $componentIds = collect($baseComponentIds)
            ->merge(
                collect($groups)
                    ->flatMap(fn (array $group): array => collect($group['options'] ?? [])
                        ->pluck('component_id')
                        ->filter()
                        ->map(fn ($id) => (int) $id)
                        ->all())
            )
            ->filter()
            ->unique()
            ->values()
            ->all();

        $components = static::componentQuery()
            ->whereIn('id', $componentIds)
            ->get()
            ->keyBy('id');

        $componentMap = $components
            ->map(fn (Component $component): array => static::componentPayload($component))
            ->all();

        $defaults = [];
        $resolvedGroups = [];

        foreach ($groups as $group) {
            $slot = (string) ($group['slot'] ?? '');
            $baseComponentId = (int) ($baseComponentIds[$slot] ?? 0);

            if ($baseComponentId < 1 || ! isset($componentMap[$baseComponentId])) {
                continue;
            }

            $baseComponent = $componentMap[$baseComponentId];
            $resolvedOptions = [];

            foreach ((array) ($group['options'] ?? []) as $option) {
                if (! is_array($option) || ! ($option['is_active'] ?? true)) {
                    continue;
                }

                $componentId = (int) ($option['component_id'] ?? 0);

                if ($componentId === $baseComponentId) {
                    continue;
                }

                $componentPayload = $componentId && isset($componentMap[$componentId]) ? $componentMap[$componentId] : null;
                $label = trim((string) ($option['label'] ?? ($componentPayload['name'] ?? '')));

                if ($label === '') {
                    continue;
                }

                $resolvedOptions[] = [
                    ...$option,
                    'component' => $componentPayload,
                    'label' => $label,
                    'description' => static::nullableText($option['description'] ?? ($componentPayload['summary'] ?? null)),
                    'price' => (int) ($option['price_delta'] ?? 0),
                    'is_default' => false,
                ];
            }

            if ($resolvedOptions === []) {
                continue;
            }

            $baseOption = [
                'key' => 'base',
                'component_id' => $baseComponentId,
                'label' => $baseComponent['name'] ?? static::slotLabel($slot),
                'description' => 'Базова комплектуюча збірки.',
                'price_delta' => 0,
                'price' => 0,
                'is_default' => true,
                'is_active' => true,
                'component' => $baseComponent,
            ];

            $groupKey = (string) ($group['key'] ?? $slot);
            $defaults[$groupKey] = 'base';

            $resolvedGroups[] = [
                ...$group,
                'key' => $groupKey,
                'title' => trim((string) ($group['title'] ?? static::defaultGroupTitle($slot))) ?: static::defaultGroupTitle($slot),
                'description' => static::nullableText($group['description'] ?? null),
                'options' => [$baseOption, ...$resolvedOptions],
            ];
        }

        $payload = [
            'enabled' => $resolvedGroups !== [],
            'groups' => $resolvedGroups,
            'client' => [
                'enabled' => $resolvedGroups !== [],
                'baseComponents' => $baseComponentIds,
                'groups' => $resolvedGroups,
                'components' => $componentMap,
                'defaults' => $defaults,
                'slotLabels' => static::slotOptions(),
            ],
            'defaults' => $defaults,
            'price_raw' => (int) ($build['price_raw'] ?? 0),
        ];
        $resolvedState = static::resolvePayloadSelection($payload, $defaults);

        return [
            ...$payload,
            'selectedSummary' => $resolvedState['summary'],
            'additionalPrice' => $resolvedState['additional_price'],
            'totalPrice' => $resolvedState['total_price'],
            'compatibility' => $resolvedState['compatibility'],
        ];
    }

    public static function resolveSelection(array $build, ?array $selection = null): array
    {
        return static::resolvePayloadSelection(
            static::storefrontPayload($build),
            $selection ?? [],
        );
    }

    public static function resolvePayloadSelection(array $payload, ?array $selection = null): array
    {
        $client = is_array($payload['client'] ?? null) ? $payload['client'] : [];
        $groups = is_array($payload['groups'] ?? null) ? $payload['groups'] : [];
        $basePrice = (int) ($payload['price_raw'] ?? $payload['totalPrice'] ?? 0);

        if (! ((bool) ($client['enabled'] ?? false)) || $groups === []) {
            return [
                'enabled' => false,
                'selection' => [],
                'summary' => [],
                'additional_price' => 0,
                'total_price' => $basePrice,
                'compatibility' => [
                    'is_valid' => true,
                    'messages' => [],
                    'component_ids' => is_array($client['baseComponents'] ?? null) ? $client['baseComponents'] : [],
                ],
            ];
        }

        $selection = is_array($selection) ? $selection : [];
        $defaults = is_array($payload['defaults'] ?? null) ? $payload['defaults'] : [];
        $components = is_array($client['components'] ?? null) ? $client['components'] : [];
        $baseComponents = is_array($client['baseComponents'] ?? null) ? $client['baseComponents'] : [];
        $normalizedSelection = [];
        $summary = [];
        $additionalPrice = 0;

        foreach ($groups as $group) {
            $groupKey = (string) ($group['key'] ?? '');
            $slot = (string) ($group['slot'] ?? '');
            $options = array_values(array_filter(
                (array) ($group['options'] ?? []),
                static fn ($option): bool => is_array($option) && (($option['is_active'] ?? true) !== false),
            ));

            if ($groupKey === '' || $options === []) {
                continue;
            }

            $selectedKey = (string) ($selection[$groupKey] ?? $defaults[$groupKey] ?? '');
            $selectedOption = collect($options)->firstWhere('key', $selectedKey);
            $selectedOption ??= collect($options)->firstWhere('is_default', true);
            $selectedOption ??= $options[0];

            $normalizedSelection[$groupKey] = (string) ($selectedOption['key'] ?? '');
            $optionPrice = (int) ($selectedOption['price'] ?? $selectedOption['price_delta'] ?? 0);
            $isDefaultOption = (bool) ($selectedOption['is_default'] ?? false);
            $additionalPrice += $optionPrice;

            if ($optionPrice > 0 || (! $isDefaultOption) || ! in_array($slot, ['modding', 'adapters', 'other'], true)) {
                $summary[] = static::slotLabel($slot) . ': ' . (string) ($selectedOption['label'] ?? 'Вибрано');
            }
        }

        $compatibility = static::evaluateSelection(
            $components,
            $baseComponents,
            $groups,
            $normalizedSelection,
        );

        return [
            'enabled' => true,
            'selection' => $normalizedSelection,
            'summary' => $summary,
            'additional_price' => $additionalPrice,
            'total_price' => $basePrice + $additionalPrice,
            'compatibility' => $compatibility,
        ];
    }

    public static function cartKey(string $slug, ?array $selection = null): string
    {
        $slug = trim($slug);
        $selection = is_array($selection) ? $selection : [];

        if ($slug === '' || $selection === []) {
            return $slug !== '' ? $slug . ':default' : 'build:default';
        }

        ksort($selection);

        return $slug . ':' . base64_encode(json_encode($selection, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public static function evaluateSelection(array $components, array $baseComponents, array $groups, array $selection): array
    {
        $componentIds = $baseComponents;
        $messages = [];

        foreach ($groups as $group) {
            $selectedKey = (string) ($selection[$group['key']] ?? '');
            $option = collect($group['options'])->firstWhere('key', $selectedKey);
            $option ??= collect($group['options'])->firstWhere('is_default', true);
            $option ??= $group['options'][0] ?? null;

            if (! is_array($option)) {
                continue;
            }

            $slot = (string) ($group['slot'] ?? '');
            $componentId = (int) ($option['component_id'] ?? 0);

            if ($slot !== '' && $componentId > 0 && in_array($slot, self::BASE_SLOTS, true)) {
                $componentIds[$slot] = $componentId;
            }
        }

        $cpu = static::componentFromSlot('cpu', $componentIds, $components);
        $gpu = static::componentFromSlot('gpu', $componentIds, $components);
        $motherboard = static::componentFromSlot('motherboard', $componentIds, $components);
        $ram = static::componentFromSlot('ram', $componentIds, $components);
        $psu = static::componentFromSlot('psu', $componentIds, $components);
        $case = static::componentFromSlot('case', $componentIds, $components);
        $cooler = static::componentFromSlot('cooler', $componentIds, $components);

        if ($cpu && $motherboard) {
            $cpuSocket = trim((string) ($cpu['socket'] ?? ''));
            $boardSocket = trim((string) ($motherboard['socket'] ?? ''));

            if ($cpuSocket !== '' && $boardSocket !== '' && $cpuSocket !== $boardSocket) {
                $messages[] = 'Процесор не сумісний із сокетом материнської плати.';
            }
        }

        if ($ram && $motherboard) {
            $ramType = trim((string) ($ram['ram_type'] ?? ''));
            $boardRamType = trim((string) ($motherboard['ram_type'] ?? ''));

            if ($ramType !== '' && $boardRamType !== '' && $ramType !== $boardRamType) {
                $messages[] = "Оперативна пам'ять не підходить до материнської плати.";
            }
        }

        if ($motherboard && $case) {
            $formFactor = trim((string) ($motherboard['form_factor'] ?? ''));
            $supported = array_filter((array) ($case['supported_mb_form_factors'] ?? []));

            if ($formFactor !== '' && $supported !== [] && ! in_array($formFactor, $supported, true)) {
                $messages[] = 'Обрана материнська плата не поміщається в цей корпус.';
            }
        }

        if ($gpu && $case) {
            $gpuLength = (int) ($gpu['gpu_length_mm'] ?? 0);
            $caseLimit = (int) ($case['max_gpu_length_mm'] ?? 0);

            if ($gpuLength > 0 && $caseLimit > 0 && $gpuLength > $caseLimit) {
                $messages[] = 'Відеокарта завелика для цього корпусу.';
            }
        }

        if ($cooler && $cpu) {
            $cpuSocket = trim((string) ($cpu['socket'] ?? ''));
            $supportedSockets = array_filter((array) ($cooler['supported_sockets'] ?? []));

            if ($cpuSocket !== '' && $supportedSockets !== [] && ! in_array($cpuSocket, $supportedSockets, true)) {
                $messages[] = 'Охолодження CPU не підтримує сокет обраного процесора.';
            }
        }

        if ($cooler && $case) {
            $radiatorSize = (int) ($cooler['radiator_size_mm'] ?? 0);
            $supportedRadiators = collect((array) ($case['supported_radiator_sizes'] ?? []))
                ->map(fn ($value) => (int) $value)
                ->filter()
                ->values()
                ->all();
            $coolerHeight = (int) ($cooler['max_cooler_height_mm'] ?? 0);
            $caseCoolerLimit = (int) ($case['max_cooler_height_mm'] ?? 0);

            if ($radiatorSize > 0 && $supportedRadiators !== [] && ! in_array($radiatorSize, $supportedRadiators, true)) {
                $messages[] = 'Корпус не підтримує цей радіатор СВО.';
            }

            if ($radiatorSize === 0 && $coolerHeight > 0 && $caseCoolerLimit > 0 && $coolerHeight > $caseCoolerLimit) {
                $messages[] = 'Повітряний кулер не поміщається в корпус по висоті.';
            }
        }

        if ($gpu && $psu) {
            $requiredConnectors = (int) ($gpu['gpu_power_connectors'] ?? 0);
            $availableConnectors = (int) ($psu['pcie_power_connectors'] ?? 0);

            if ($requiredConnectors > 0 && $availableConnectors > 0 && $requiredConnectors > $availableConnectors) {
                $messages[] = 'Блок живлення не має достатньо PCIe-конекторів для цієї відеокарти.';
            }
        }

        if ($cpu && $gpu && $psu) {
            $requiredWattage = (int) ceil((((int) ($cpu['cpu_tdp_w'] ?? 0)) + ((int) ($gpu['gpu_power_w'] ?? 0))) * 1.35 + 80);
            $psuWattage = (int) ($psu['psu_wattage'] ?? 0);

            if ($requiredWattage > 0 && $psuWattage > 0 && $psuWattage < $requiredWattage) {
                $messages[] = 'Потужності блока живлення замало для поточної конфігурації.';
            }
        }

        $messages = array_values(array_unique(array_filter($messages)));

        return [
            'is_valid' => $messages === [],
            'messages' => $messages,
            'component_ids' => $componentIds,
        ];
    }

    protected static function inferBaseComponentIdForSlot(array $build, string $slot): int
    {
        $text = match ($slot) {
            'gpu' => (string) ($build['gpu'] ?? ''),
            'cpu' => (string) ($build['cpu'] ?? ''),
            'ram' => (string) ($build['ram'] ?? ''),
            'storage' => (string) ($build['storage'] ?? ''),
            'motherboard' => static::productSpecValue($build, 'motherboard'),
            'case' => static::productSpecValue($build, 'case'),
            'psu' => static::productSpecValue($build, 'psu'),
            'cooler' => static::productSpecValue($build, 'cooler'),
            default => '',
        };

        if ($text === '') {
            return 0;
        }

        return static::inferBaseComponentIdForText($slot, $text);
    }

    protected static function componentQuery()
    {
        return Component::query()
            ->where('is_active', true)
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    protected static function componentPayload(Component $component): array
    {
        return [
            'id' => $component->id,
            'type' => $component->type,
            'name' => $component->name,
            'vendor' => $component->vendor,
            'summary' => $component->summary,
            'image_url' => ComponentImages::urlForComponent($component),
            'image_urls' => ComponentImages::urlsForComponent($component),
            'socket' => $component->socket,
            'ram_type' => $component->ram_type,
            'form_factor' => $component->form_factor,
            'supported_mb_form_factors' => $component->supported_mb_form_factors ?: [],
            'supported_sockets' => $component->supported_sockets ?: [],
            'supported_radiator_sizes' => $component->supported_radiator_sizes ?: [],
            'max_gpu_length_mm' => (int) ($component->max_gpu_length_mm ?? 0),
            'max_cooler_height_mm' => (int) ($component->max_cooler_height_mm ?? 0),
            'gpu_length_mm' => (int) ($component->gpu_length_mm ?? 0),
            'gpu_power_w' => (int) ($component->gpu_power_w ?? 0),
            'gpu_power_connectors' => (int) ($component->gpu_power_connectors ?? 0),
            'cpu_tdp_w' => (int) ($component->cpu_tdp_w ?? 0),
            'psu_wattage' => (int) ($component->psu_wattage ?? 0),
            'pcie_power_connectors' => (int) ($component->pcie_power_connectors ?? 0),
            'radiator_size_mm' => (int) ($component->radiator_size_mm ?? 0),
            'memory_modules' => (int) ($component->memory_modules ?? 0),
            'memory_capacity_gb' => (int) ($component->memory_capacity_gb ?? 0),
            'memory_speed_mhz' => (int) ($component->memory_speed_mhz ?? 0),
            'storage_interface' => $component->storage_interface,
        ];
    }

    protected static function slotLabel(string $slot): string
    {
        return static::slotOptions()[$slot] ?? 'Опція';
    }

    protected static function nullableText(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    protected static function matchComponentIdByText(string $type, string $text): int
    {
        $needle = static::normalizeComparableText($text);

        if ($needle === '') {
            return 0;
        }

        $components = static::componentQuery()
            ->where('type', $type)
            ->get();

        $exact = $components->first(function (Component $component) use ($needle): bool {
            return static::normalizeComparableText((string) $component->name) === $needle;
        });

        if ($exact instanceof Component) {
            return (int) $exact->id;
        }

        $contains = $components->first(function (Component $component) use ($needle): bool {
            $name = static::normalizeComparableText((string) $component->name);

            return $name !== '' && (
                str_contains($name, $needle)
                || str_contains($needle, $name)
            );
        });

        if ($contains instanceof Component) {
            return (int) $contains->id;
        }

        $queryTokens = static::tokenizeComparableText($needle);

        if ($queryTokens === []) {
            return 0;
        }

        $bestComponentId = 0;
        $bestScore = 0.0;

        foreach ($components as $component) {
            $haystack = trim(implode(' ', array_filter([
                (string) $component->name,
                (string) $component->vendor,
                (string) $component->summary,
            ])));

            $componentTokens = static::tokenizeComparableText($haystack);

            if ($componentTokens === []) {
                continue;
            }

            $overlap = count(array_intersect($queryTokens, $componentTokens));

            if ($overlap < min(2, count($queryTokens))) {
                continue;
            }

            $score = $overlap / count($queryTokens);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestComponentId = (int) $component->id;
            }
        }

        return $bestScore >= 0.55 ? $bestComponentId : 0;
    }

    protected static function productSpecValue(array $build, string $icon): string
    {
        foreach ((array) ($build['product_specs'] ?? []) as $spec) {
            if (! is_array($spec)) {
                continue;
            }

            if ((string) ($spec['icon'] ?? '') !== $icon) {
                continue;
            }

            $value = trim((string) ($spec['value'] ?? ''));

            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    protected static function normalizeComparableText(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = str_replace(
            ['"', "'", '’', '`', "\r", "\n", "\t"],
            '',
            $value,
        );
        $value = preg_replace('/\s+/', ' ', $value) ?? '';

        return trim($value);
    }

    protected static function tokenizeComparableText(string $value): array
    {
        $value = static::normalizeComparableText($value);

        if ($value === '') {
            return [];
        }

        return array_values(array_unique(array_filter(
            preg_split('/[^[:alnum:]\+]+/u', $value) ?: [],
            static fn (string $token): bool => mb_strlen($token) >= 2,
        )));
    }

    protected static function componentFromSlot(string $slot, array $componentIds, array $components): ?array
    {
        $componentId = (int) ($componentIds[$slot] ?? 0);

        return $componentId > 0 ? ($components[$componentId] ?? null) : null;
    }
}
