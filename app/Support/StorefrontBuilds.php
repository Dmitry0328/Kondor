<?php

namespace App\Support;

use App\Models\Build;
use Illuminate\Support\Facades\Schema;

class StorefrontBuilds
{
    protected static ?array $cache = null;

    public static function all(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        if (Schema::hasTable('builds')) {
            $builds = Build::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($builds->isNotEmpty()) {
                return static::$cache = $builds
                    ->map(fn (Build $build): array => $build->toStorefrontPayload())
                    ->all();
            }
        }

        return static::$cache = collect(config('kondor_storefront.builds', []))
            ->values()
            ->map(fn (array $build, int $index): array => static::normalizeConfigBuild($build, $index))
            ->all();
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (static::all() as $build) {
            if (($build['slug'] ?? null) === $slug) {
                return $build;
            }
        }

        return null;
    }

    public static function formatPrice(int $value): string
    {
        return number_format($value, 0, '', ' ') . ' ₴';
    }

    public static function flush(): self
    {
        static::$cache = null;

        return new self();
    }

    protected static function normalizeConfigBuild(array $build, int $index): array
    {
        $catalog = FpsCatalog::all();

        $rawScore = (int) ($build['fps_score'] ?? 0);
        $fallbackScore = max(0, $rawScore);
        $profiles = FpsProfiles::normalize((array) ($build['fps_profiles'] ?? []), $catalog);
        $lookup = FpsProfiles::makeLookup($profiles);
        $defaults = FpsProfiles::defaultState($catalog, $profiles);

        $build['fps_score'] = $profiles !== []
            ? FpsProfiles::resolve(
                $lookup,
                $profiles,
                (string) ($defaults['game'] ?? ''),
                (string) ($defaults['display'] ?? ''),
                (string) ($defaults['preset'] ?? ''),
                $fallbackScore,
            )
            : 0;
        $build['fps_profiles'] = $profiles;
        $build['fps_lookup'] = $lookup;
        $build['fps_defaults'] = $defaults;

        if (isset($build['price']) && is_int($build['price'])) {
            $build['price'] = static::formatPrice($build['price']);
        }

        $build['sort_order'] = $build['sort_order'] ?? ($index + 1);
        $build['is_active'] = $build['is_active'] ?? true;

        return $build;
    }
}
