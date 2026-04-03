<?php

namespace App\Support;

use App\Models\Build;
use App\Models\SiteImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuildImages
{
    public static function pathsForSlug(?string $slug): array
    {
        return static::recordsForSlug($slug)
            ->pluck('path')
            ->filter(fn ($path): bool => is_string($path) && trim($path) !== '')
            ->values()
            ->all();
    }

    public static function urlsForSlug(?string $slug): array
    {
        return static::recordsForSlug($slug)
            ->pluck('url')
            ->filter(fn ($url): bool => is_string($url) && trim($url) !== '')
            ->values()
            ->all();
    }

    public static function coverPathForSlug(?string $slug): ?string
    {
        return static::pathsForSlug($slug)[0] ?? null;
    }

    public static function coverUrlForSlug(?string $slug): ?string
    {
        return static::urlsForSlug($slug)[0] ?? null;
    }

    public static function urlsForUploadState(mixed $state): array
    {
        return array_values(array_map(
            static fn (string $path): string => static::urlForPath($path),
            static::normalizeUploadState($state),
        ));
    }

    public static function normalizeUploadState(mixed $state): array
    {
        if (is_string($state)) {
            $state = [$state];
        }

        if (! is_array($state)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(static function ($path): ?string {
            if (! is_string($path)) {
                return null;
            }

            $path = trim($path);

            return $path !== '' ? $path : null;
        }, $state))));
    }

    public static function sync(Build|string $build, mixed $uploadState): void
    {
        $slug = trim((string) ($build instanceof Build ? $build->slug : $build));

        if ($slug === '') {
            return;
        }

        $desiredPaths = static::normalizeUploadState($uploadState);
        $existingRecords = static::recordsForSlug($slug)->keyBy('key');
        $oldFiles = $existingRecords
            ->filter(fn (SiteImage $image): bool => filled($image->path))
            ->mapWithKeys(fn (SiteImage $image): array => [(string) $image->path => (string) ($image->disk ?: 'public')])
            ->all();

        $desiredByKey = [];

        foreach ($desiredPaths as $index => $path) {
            $desiredByKey[static::keyForIndex($slug, $index)] = $path;
        }

        foreach ($desiredByKey as $key => $path) {
            SiteImage::query()->updateOrCreate(
                ['key' => $key],
                [
                    'disk' => 'public',
                    'path' => $path,
                    'updated_by' => Auth::id(),
                ],
            );
        }

        foreach ($existingRecords as $key => $record) {
            if (array_key_exists($key, $desiredByKey)) {
                continue;
            }

            $record->delete();
        }

        foreach ($oldFiles as $path => $disk) {
            if (in_array($path, $desiredPaths, true)) {
                continue;
            }

            Storage::disk($disk !== '' ? $disk : 'public')->delete($path);
        }

        SiteImages::flush();
        StorefrontBuilds::flush();
    }

    public static function keyForIndex(string $slug, int $index): string
    {
        $slug = trim($slug);

        if ($index <= 0) {
            return static::coverKey($slug);
        }

        return static::galleryPrefix($slug) . $index;
    }

    public static function coverKey(string $slug): string
    {
        return 'build.' . trim($slug) . '.cover';
    }

    protected static function recordsForSlug(?string $slug): Collection
    {
        $slug = trim((string) $slug);

        if ($slug === '') {
            return collect();
        }

        $prefix = 'build.' . $slug . '.';

        return SiteImage::query()
            ->where('key', 'like', $prefix . '%')
            ->get()
            ->filter(fn (SiteImage $image): bool => static::isImageKeyForSlug((string) $image->key, $slug))
            ->sortBy(fn (SiteImage $image): string => static::sortableKeyForImageKey((string) $image->key))
            ->values();
    }

    protected static function isImageKeyForSlug(string $key, string $slug): bool
    {
        return $key === static::coverKey($slug)
            || str_starts_with($key, static::galleryPrefix($slug));
    }

    protected static function galleryPrefix(string $slug): string
    {
        return 'build.' . trim($slug) . '.gallery.';
    }

    protected static function sortableKeyForImageKey(string $key): string
    {
        if (str_ends_with($key, '.cover')) {
            return '000-cover';
        }

        if (preg_match('/\.gallery\.(\d+)$/', $key, $matches) === 1) {
            return sprintf('100-%06d', (int) ($matches[1] ?? 0));
        }

        $suffix = preg_replace('/^.*\.gallery\./', '', $key) ?? '';
        $legacyOrder = [
            'performance' => 1,
            'inside' => 2,
            'detail' => 3,
        ];

        if (isset($legacyOrder[$suffix])) {
            return sprintf('200-%02d', $legacyOrder[$suffix]);
        }

        return '900-' . $suffix;
    }

    protected static function urlForPath(string $path): string
    {
        $url = Storage::disk('public')->url($path);

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $parsed = parse_url($url);
            $path = $parsed['path'] ?? '';

            if ($path !== '') {
                $url = $path;

                if (! empty($parsed['query'])) {
                    $url .= '?' . $parsed['query'];
                }
            }
        }

        return $url;
    }
}
