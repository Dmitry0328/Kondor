<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class AdminFormPreview
{
    public static function imageUrls(mixed $state, string $disk = 'public'): array
    {
        if (is_string($state) || is_object($state)) {
            $state = [$state];
        }

        if (! is_array($state)) {
            return [];
        }

        $urls = [];

        foreach ($state as $value) {
            $url = static::resolveImageUrl($value, $disk);

            if ($url !== null) {
                $urls[] = $url;
            }
        }

        return array_values(array_unique($urls));
    }

    public static function resolveImageUrl(mixed $value, string $disk = 'public'): ?string
    {
        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return null;
            }

            if (
                str_starts_with($value, 'data:')
                || str_starts_with($value, 'http://')
                || str_starts_with($value, 'https://')
                || str_starts_with($value, '/')
            ) {
                return $value;
            }

            return Storage::disk($disk)->url($value);
        }

        if (! is_object($value)) {
            return null;
        }

        if (method_exists($value, 'temporaryUrl')) {
            try {
                $temporaryUrl = $value->temporaryUrl();

                if (is_string($temporaryUrl) && trim($temporaryUrl) !== '') {
                    return $temporaryUrl;
                }
            } catch (\Throwable) {
                return null;
            }
        }

        if (method_exists($value, 'getFilename')) {
            $filename = trim((string) $value->getFilename());

            if ($filename !== '') {
                return Storage::disk($disk)->url($filename);
            }
        }

        return null;
    }

    public static function cleanText(mixed $value, string $fallback = ''): string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value) ?? '');

        return $value !== '' ? $value : $fallback;
    }

    public static function formatPrice(mixed $value, string $currency): string
    {
        return number_format(max(0, (int) round((float) $value)), 0, '.', ' ') . ' ' . $currency;
    }

    public static function splitLines(mixed $value): array
    {
        return array_values(array_filter(array_map(
            static fn (string $line): string => trim($line),
            preg_split('/\r\n|\r|\n/', trim((string) $value)) ?: [],
        )));
    }
}
