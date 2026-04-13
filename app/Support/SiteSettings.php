<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SiteSettings
{
    protected static ?Collection $records = null;

    public static function string(string $key, string $default = ''): string
    {
        $value = static::records()->get($key);

        if ($value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    public static function set(string $key, ?string $value): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        static::flush();
    }

    public static function flush(): void
    {
        static::$records = null;
    }

    protected static function records(): Collection
    {
        if (static::$records !== null) {
            return static::$records;
        }

        if (! Schema::hasTable('site_settings')) {
            return static::$records = collect();
        }

        return static::$records = SiteSetting::query()
            ->pluck('value', 'key');
    }
}
