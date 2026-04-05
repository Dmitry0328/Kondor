<?php

namespace App\Support;

use Illuminate\Support\Str;

class AdminSlug
{
    public static function normalize(mixed $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        return Str::slug($value);
    }

    public static function syncFromSource(
        mixed $state,
        mixed $old,
        callable $get,
        callable $set,
        string $targetField = 'slug',
    ): void {
        $currentTarget = static::normalize($get($targetField));
        $oldNormalized = static::normalize($old);

        if ($currentTarget !== '' && $currentTarget !== $oldNormalized) {
            return;
        }

        $set($targetField, static::normalize($state));
    }
}
