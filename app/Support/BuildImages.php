<?php

namespace App\Support;

use App\Models\Build;
use App\Models\SiteImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuildImages
{
    public static function placeholderUrl(?string $label = null): string
    {
        $label = static::shortLabel($label);
        $labelSvg = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 720" fill="none">
  <defs>
    <linearGradient id="bg" x1="72" y1="54" x2="628" y2="642" gradientUnits="userSpaceOnUse">
      <stop stop-color="#0f172a"/>
      <stop offset="0.52" stop-color="#312e81"/>
      <stop offset="1" stop-color="#7c3aed"/>
    </linearGradient>
    <linearGradient id="glow" x1="182" y1="194" x2="504" y2="462" gradientUnits="userSpaceOnUse">
      <stop stop-color="#f59e0b"/>
      <stop offset="1" stop-color="#ef4444"/>
    </linearGradient>
  </defs>
  <rect width="720" height="720" rx="56" fill="url(#bg)"/>
  <rect x="44" y="44" width="632" height="632" rx="42" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.16)" stroke-width="2"/>
  <rect x="146" y="142" width="428" height="298" rx="34" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.18)" stroke-width="3"/>
  <path d="M186 392L278 292C294 275 320 275 336 292L402 358L450 310C466 294 492 294 508 310L574 376V404C574 427 555 446 532 446H188C165 446 146 427 146 404V392H186Z" fill="url(#glow)"/>
  <circle cx="474" cy="236" r="42" fill="rgba(255,255,255,0.92)"/>
  <rect x="92" y="92" width="198" height="54" rx="27" fill="rgba(15,23,42,0.82)" stroke="rgba(255,255,255,0.18)"/>
  <text x="191" y="126" fill="white" font-size="28" font-family="Manrope, Arial, sans-serif" font-weight="800" text-anchor="middle">BUILD</text>
  <rect x="164" y="506" width="392" height="74" rx="37" fill="rgba(255,255,255,0.1)"/>
  <text x="360" y="552" fill="white" font-size="34" font-family="Manrope, Arial, sans-serif" font-weight="800" text-anchor="middle">{$labelSvg}</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

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

    protected static function shortLabel(?string $label): string
    {
        $label = trim(preg_replace('/\s+/', ' ', (string) $label) ?? '');

        if ($label === '') {
            return 'NO PHOTO';
        }

        if (mb_strlen($label) <= 22) {
            return $label;
        }

        return rtrim(mb_substr($label, 0, 22)) . '...';
    }
}
