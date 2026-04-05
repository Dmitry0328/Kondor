<?php

namespace App\Support;

use App\Models\Accessory;

class AccessoryCatalog
{
    protected const TYPE_DEFINITIONS = [
        'keyboard' => [
            'label' => 'Клавіатури',
            'meta' => 'Kondor Orion та інші серії',
            'icon' => 'keyboard',
        ],
        'mouse' => [
            'label' => 'Миші',
            'meta' => 'Під ігри, шутери та daily use',
            'icon' => 'mouse',
        ],
        'pad' => [
            'label' => 'Килимки',
            'meta' => 'Ігрові поверхні для сетапу',
            'icon' => 'pad',
        ],
    ];

    protected const PACKAGE_ICON_OPTIONS = [
        'generic' => 'Універсальна іконка',
        'cable' => 'Кабель',
        'switch' => 'Свічі / перемикачі',
        'tool' => 'Інструмент',
        'manual' => 'Інструкція',
        'sticker' => 'Стікер',
        'keycap' => 'Кейкапи / аксесуар',
        'dongle' => 'Донгл / адаптер',
    ];

    public static function typeDefinitions(): array
    {
        return static::TYPE_DEFINITIONS;
    }

    public static function typeOptions(): array
    {
        return collect(static::TYPE_DEFINITIONS)
            ->mapWithKeys(fn (array $definition, string $type): array => [$type => $definition['label']])
            ->all();
    }

    public static function typeLabel(string $type): string
    {
        return static::TYPE_DEFINITIONS[$type]['label'] ?? ucfirst($type);
    }

    public static function typeMeta(string $type): string
    {
        return static::TYPE_DEFINITIONS[$type]['meta'] ?? '';
    }

    public static function typeIcon(string $type): string
    {
        return static::TYPE_DEFINITIONS[$type]['icon'] ?? 'generic';
    }

    public static function packageIconOptions(): array
    {
        return static::PACKAGE_ICON_OPTIONS;
    }

    public static function packageIcon(string $icon): string
    {
        return array_key_exists($icon, static::PACKAGE_ICON_OPTIONS) ? $icon : 'generic';
    }

    public static function storefrontGroups(): array
    {
        $itemsByType = Accessory::query()
            ->active()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return collect(static::TYPE_DEFINITIONS)
            ->map(function (array $definition, string $type) use ($itemsByType): array {
                return [
                    'type' => $type,
                    'label' => $definition['label'],
                    'meta' => $definition['meta'],
                    'icon' => $definition['icon'],
                    'items' => $itemsByType->get($type, collect())
                        ->map(fn (Accessory $accessory): array => $accessory->toStorefrontPayload())
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }
}
