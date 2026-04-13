<?php

namespace App\Support;

class BuildResolutions
{
    public static function resolutionDefinitions(): array
    {
        return [
            'full-hd' => [
                'id' => 'full-hd',
                'label' => 'Full HD',
                'title' => '1080p gaming',
                'eyebrow' => 'Starter tier',
                'description' => 'Для 1920x1080, кіберспортивних ігор та комфортного щоденного геймінгу.',
            ],
            'full-hd-plus' => [
                'id' => 'full-hd-plus',
                'label' => 'Full HD+',
                'title' => '1080p ultra',
                'eyebrow' => 'High refresh',
                'description' => 'Для Full HD на високих/ультра налаштуваннях і моніторів із підвищеною герцовкою.',
            ],
            '2k' => [
                'id' => '2k',
                'label' => '2K',
                'title' => '1440p sweet spot',
                'eyebrow' => 'Balanced power',
                'description' => 'Для 2560x1440, сюжетних AAA-ігор і більш вимогливих графічних сценаріїв.',
            ],
            '4k' => [
                'id' => '4k',
                'label' => '4K',
                'title' => 'Maximum detail',
                'eyebrow' => 'Flagship tier',
                'description' => 'Для 3840x2160, запасу по продуктивності та преміального рівня якості.',
            ],
        ];
    }

    public static function adminOnlyDefinitions(): array
    {
        return [
            'top5' => [
                'id' => 'top5',
                'label' => 'TOP5',
                'title' => 'Homepage top picks',
                'eyebrow' => 'Admin only',
                'description' => 'Показувати збірку в блоці Топ 5 збірок на головній сторінці.',
            ],
        ];
    }

    public static function definitions(): array
    {
        return [
            ...static::resolutionDefinitions(),
            ...static::adminOnlyDefinitions(),
        ];
    }

    public static function options(): array
    {
        $options = [];

        foreach (static::definitions() as $id => $definition) {
            $options[$id] = (string) ($definition['label'] ?? $id);
        }

        return $options;
    }

    public static function allFilter(): array
    {
        return [
            'id' => 'all',
            'label' => 'All builds',
            'title' => 'Усі збірки',
            'eyebrow' => 'Catalog view',
            'description' => 'Показати весь каталог без обмеження по роздільній здатності.',
        ];
    }

    public static function catalogFilters(): array
    {
        return [
            static::allFilter(),
            ...array_values(static::resolutionDefinitions()),
        ];
    }

    public static function normalize(array $values): array
    {
        $definitions = static::definitions();
        $normalized = [];

        foreach ($values as $value) {
            $value = trim((string) $value);

            if ($value === '' || ! array_key_exists($value, $definitions)) {
                continue;
            }

            $normalized[$value] = $value;
        }

        return array_values($normalized);
    }

    public static function exists(?string $value): bool
    {
        return array_key_exists(trim((string) $value), static::definitions());
    }

    public static function countByTag(array $builds): array
    {
        $counts = ['all' => count($builds)];

        foreach (array_keys(static::resolutionDefinitions()) as $tag) {
            $counts[$tag] = 0;
        }

        foreach ($builds as $build) {
            foreach (static::normalize((array) ($build['resolution_tags'] ?? [])) as $tag) {
                if (! array_key_exists($tag, $counts)) {
                    $counts[$tag] = 0;
                }

                $counts[$tag]++;
            }
        }

        return $counts;
    }
}
