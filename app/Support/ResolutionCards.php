<?php

namespace App\Support;

use App\Models\ResolutionCard;
use Illuminate\Support\Facades\Schema;

class ResolutionCards
{
    public static function defaults(): array
    {
        return [
            'full-hd' => [
                'key' => 'full-hd',
                'label' => 'Full HD',
                'eyebrow' => 'Starter tier',
                'description' => 'Для 1920x1080, кіберспортивних ігор та комфортного щоденного геймінгу.',
                'accent_color' => '#45d9ff',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 10,
                'is_active' => true,
            ],
            'full-hd-plus' => [
                'key' => 'full-hd-plus',
                'label' => 'Full HD+',
                'eyebrow' => 'High refresh',
                'description' => 'Для Full HD на високих/ультра налаштуваннях і моніторів із підвищеною герцовкою.',
                'accent_color' => '#8b5cf6',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 20,
                'is_active' => true,
            ],
            '2k' => [
                'key' => '2k',
                'label' => '2K',
                'eyebrow' => 'Balanced power',
                'description' => 'Для 2560x1440, сюжетних AAA-ігор і більш вимогливих графічних сценаріїв.',
                'accent_color' => '#ff8a3d',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 30,
                'is_active' => true,
            ],
            '4k' => [
                'key' => '4k',
                'label' => '4K',
                'eyebrow' => 'Flagship tier',
                'description' => 'Для 3840x2160, запасу по продуктивності та преміального рівня якості.',
                'accent_color' => '#ff4f8b',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 40,
                'is_active' => true,
            ],
        ];
    }

    public static function forStorefront(array $counts = []): array
    {
        $cards = static::records();

        return array_values(array_map(function (array $card) use ($counts): array {
            $id = (string) ($card['key'] ?? '');

            return [
                ...$card,
                'id' => $id,
                'count' => (int) ($counts[$id] ?? 0),
            ];
        }, $cards));
    }

    public static function records(): array
    {
        $defaults = static::defaults();

        if (! Schema::hasTable('resolution_cards')) {
            return array_values($defaults);
        }

        $records = ResolutionCard::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function (ResolutionCard $card): array {
                return [
                    (string) $card->key => [[
                        'key' => (string) $card->key,
                        'label' => (string) $card->label,
                        'eyebrow' => (string) $card->eyebrow,
                        'description' => (string) $card->description,
                        'accent_color' => (string) $card->accent_color,
                        'image_url' => $card->image_url,
                        'button_label' => (string) $card->button_label,
                        'sort_order' => (int) $card->sort_order,
                        'is_active' => (bool) $card->is_active,
                    ]],
                ];
            })
            ->all();

        foreach ($defaults as $key => $default) {
            if (! array_key_exists($key, $records)) {
                $records[$key] = $default;
            } else {
                $records[$key] = [
                    ...$default,
                    ...$records[$key],
                ];
            }
        }

        $records = array_filter($records, fn (array $card): bool => (bool) ($card['is_active'] ?? true));

        uasort($records, function (array $left, array $right): int {
            return [(int) ($left['sort_order'] ?? 0), (string) ($left['key'] ?? '')]
                <=>
                [(int) ($right['sort_order'] ?? 0), (string) ($right['key'] ?? '')];
        });

        return array_values($records);
    }
}
