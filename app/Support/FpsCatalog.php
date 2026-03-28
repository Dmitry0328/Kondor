<?php

namespace App\Support;

use App\Models\FpsDisplay;
use App\Models\FpsGame;
use App\Models\FpsPreset;
use Illuminate\Support\Facades\Schema;

class FpsCatalog
{
    protected static ?array $cache = null;

    public static function all(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        if (Schema::hasTable('fps_games') && Schema::hasTable('fps_displays') && Schema::hasTable('fps_presets')) {
            $games = FpsGame::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $displays = FpsDisplay::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $presets = FpsPreset::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($games->isNotEmpty() && $displays->isNotEmpty() && $presets->isNotEmpty()) {
                $resolvedGames = $games->map(static function (FpsGame $game): array {
                    return [
                        'id' => $game->key,
                        'name' => $game->name,
                        'badge' => $game->badge ?: 'Benchmark',
                        'accent' => $game->accent ?: '#7f8ea3',
                        'from' => $game->scene_from ?: '#10151d',
                        'to' => $game->scene_to ?: '#222833',
                    ];
                })->all();

                $resolvedDisplays = $displays->map(static function (FpsDisplay $display): array {
                    return [
                        'id' => $display->key,
                        'name' => $display->name,
                        'mobile_name' => $display->mobile_name ?: $display->name,
                    ];
                })->all();

                $resolvedPresets = $presets->map(static function (FpsPreset $preset): array {
                    return [
                        'id' => $preset->key,
                        'name' => $preset->name,
                    ];
                })->all();

                return static::$cache = [
                    'games' => $resolvedGames,
                    'displays' => $resolvedDisplays,
                    'presets' => $resolvedPresets,
                    'defaults' => [
                        'game' => static::resolveDefaultKey($games, $resolvedGames, 'key'),
                        'display' => static::resolveDefaultKey($displays, $resolvedDisplays, 'key'),
                        'preset' => static::resolveDefaultKey($presets, $resolvedPresets, 'key'),
                    ],
                ];
            }
        }

        return static::$cache = static::fallback();
    }

    public static function flush(): self
    {
        static::$cache = null;

        return new self();
    }

    protected static function resolveDefaultKey($collection, array $resolved, string $attribute): string
    {
        $default = $collection->firstWhere('is_default', true);

        if ($default && is_string($default->{$attribute}) && $default->{$attribute} !== '') {
            return $default->{$attribute};
        }

        return $resolved[0]['id'] ?? '';
    }

    protected static function fallback(): array
    {
        $games = [
            ['id' => 'cyberpunk-2077', 'name' => 'Cyberpunk 2077', 'accent' => '#f4dc39', 'from' => '#0f182f', 'to' => '#2b1211', 'badge' => 'Night City benchmark'],
            ['id' => 'gta-5', 'name' => 'GTA 5', 'accent' => '#8cff7c', 'from' => '#10151d', 'to' => '#183625', 'badge' => 'Los Santos test'],
            ['id' => 'counter-strike-2', 'name' => 'Counter-Strike 2', 'accent' => '#ffb35c', 'from' => '#10151d', 'to' => '#31200f', 'badge' => 'Premier smoke test'],
            ['id' => 'fortnite', 'name' => 'Fortnite', 'accent' => '#57d8ff', 'from' => '#10162a', 'to' => '#15384a', 'badge' => 'Island benchmark'],
            ['id' => 'valorant', 'name' => 'Valorant', 'accent' => '#ff637b', 'from' => '#14131d', 'to' => '#321019', 'badge' => 'Ranked preset'],
            ['id' => 'stalker-2', 'name' => 'S.T.A.L.K.E.R. 2', 'accent' => '#a3ff63', 'from' => '#131816', 'to' => '#2b2210', 'badge' => 'Zone benchmark'],
            ['id' => 'red-dead-redemption-2', 'name' => 'Red Dead Redemption 2', 'accent' => '#ff8f5a', 'from' => '#161117', 'to' => '#3a1b13', 'badge' => 'Frontier cinematic'],
            ['id' => 'rust', 'name' => 'Rust', 'accent' => '#ff9759', 'from' => '#12161d', 'to' => '#362117', 'badge' => 'Survival session'],
        ];

        $displays = [
            ['id' => '1080p', 'name' => '1920 x 1080 (Full HD)', 'mobile_name' => 'Full HD'],
            ['id' => '1440p', 'name' => '2560 x 1440 (2K)', 'mobile_name' => '2K'],
            ['id' => '4k', 'name' => '3840 x 2160 (4K)', 'mobile_name' => '4K'],
        ];

        $presets = [
            ['id' => 'medium', 'name' => 'Середні'],
            ['id' => 'high', 'name' => 'Високі'],
            ['id' => 'ultra', 'name' => 'Ультра'],
        ];

        return [
            'games' => $games,
            'displays' => $displays,
            'presets' => $presets,
            'defaults' => [
                'game' => 'cyberpunk-2077',
                'display' => '1440p',
                'preset' => 'high',
            ],
        ];
    }
}
