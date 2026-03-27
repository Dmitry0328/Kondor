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
        if (isset($build['price']) && is_int($build['price'])) {
            $build['price'] = static::formatPrice($build['price']);
        }

        $build['sort_order'] = $build['sort_order'] ?? ($index + 1);
        $build['is_active'] = $build['is_active'] ?? true;

        return $build;
    }
}
