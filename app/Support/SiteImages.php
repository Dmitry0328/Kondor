<?php

namespace App\Support;

use App\Models\SiteImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SiteImages
{
    protected static ?Collection $records = null;

    public static function url(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        return static::records()->get($key)?->url;
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

        if (! Schema::hasTable('site_images')) {
            return static::$records = collect();
        }

        return static::$records = SiteImage::query()
            ->get(['key', 'disk', 'path', 'updated_by', 'created_at', 'updated_at'])
            ->keyBy('key');
    }
}
