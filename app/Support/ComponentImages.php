<?php

namespace App\Support;

use App\Models\Component;
use App\Models\SiteImage;
use Illuminate\Support\Facades\Storage;

class ComponentImages
{
    public static function key(?string $slug): ?string
    {
        $slug = trim((string) $slug);

        return $slug !== '' ? 'component.' . $slug . '.cover' : null;
    }

    public static function legacyPath(?string $slug): ?string
    {
        $key = static::key($slug);

        if ($key === null) {
            return null;
        }

        return SiteImage::query()->where('key', $key)->value('path');
    }

    public static function path(?string $slug): ?string
    {
        return static::legacyPath($slug);
    }

    public static function uploadedUrl(?string $slug): ?string
    {
        $path = static::legacyPath($slug);

        return filled($path) ? static::urlForPath((string) $path) : null;
    }

    public static function urlsForComponent(Component $component): array
    {
        return static::urlsForData(
            $component->gallery_paths ?? [],
            (string) $component->slug,
            (string) $component->type,
            (string) $component->name,
        );
    }

    public static function urlForComponent(Component $component): string
    {
        return static::urlsForComponent($component)[0];
    }

    public static function primaryUploadedUrlForComponent(Component $component): ?string
    {
        $paths = static::sourcePathsForComponent($component);

        if ($paths === []) {
            return null;
        }

        return static::urlForPath($paths[0]);
    }

    public static function urlsForData(array $galleryPaths, ?string $slug, ?string $type, ?string $label = null): array
    {
        $paths = static::normalizePaths($galleryPaths);

        if ($paths === []) {
            $legacyPath = static::legacyPath($slug);

            if (filled($legacyPath)) {
                $paths = [(string) $legacyPath];
            }
        }

        if ($paths === []) {
            return [static::placeholderUrl($type, $label)];
        }

        return array_values(array_map(
            static fn (string $path): string => static::urlForPath($path),
            $paths,
        ));
    }

    public static function urlForData(?string $slug, ?string $type, ?string $label = null): string
    {
        return static::urlsForData([], $slug, $type, $label)[0];
    }

    public static function placeholderUrl(?string $type, ?string $label = null): string
    {
        $type = trim((string) $type) ?: 'other';
        $label = trim((string) $label);
        $label = $label !== '' ? $label : static::typeLabel($type);

        $palette = static::paletteForType($type);
        $shape = static::shapeForType($type);
        $typeText = htmlspecialchars(strtoupper(static::typeLabel($type)), ENT_QUOTES, 'UTF-8');
        $nameText = htmlspecialchars(static::shortLabel($label), ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 720" fill="none">
  <defs>
    <linearGradient id="bg" x1="120" y1="40" x2="620" y2="680" gradientUnits="userSpaceOnUse">
      <stop stop-color="{$palette['from']}"/>
      <stop offset="1" stop-color="{$palette['to']}"/>
    </linearGradient>
    <radialGradient id="glow" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(560 140) rotate(122.8) scale(320 300)">
      <stop stop-color="rgba(255,255,255,0.55)"/>
      <stop offset="1" stop-color="rgba(255,255,255,0)"/>
    </radialGradient>
    <filter id="shadow" x="70" y="120" width="580" height="430" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
      <feDropShadow dx="0" dy="24" stdDeviation="28" flood-color="rgba(11,18,32,0.24)"/>
    </filter>
  </defs>
  <rect width="720" height="720" rx="56" fill="url(#bg)"/>
  <rect width="720" height="720" rx="56" fill="url(#glow)"/>
  <rect width="720" height="720" rx="56" fill="rgba(255,255,255,0.02)"/>
  <rect x="44" y="44" width="632" height="632" rx="42" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
  <g filter="url(#shadow)">
    {$shape}
  </g>
  <rect x="64" y="64" width="180" height="54" rx="27" fill="rgba(12,18,30,0.86)" stroke="rgba(255,255,255,0.18)"/>
  <text x="94" y="98" fill="white" font-size="26" font-family="Manrope, Arial, sans-serif" font-weight="800" letter-spacing="1">{$typeText}</text>
  <text x="70" y="638" fill="white" font-size="34" font-family="Manrope, Arial, sans-serif" font-weight="800">{$nameText}</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public static function rename(string $oldSlug, string $newSlug): void
    {
        $oldKey = static::key($oldSlug);
        $newKey = static::key($newSlug);

        if ($oldKey === null || $newKey === null || $oldKey === $newKey) {
            return;
        }

        SiteImage::query()
            ->where('key', $oldKey)
            ->get()
            ->each(fn (SiteImage $image) => $image->update(['key' => $newKey]));

        SiteImages::flush();
    }

    public static function delete(Component|string $component): void
    {
        $slug = $component instanceof Component ? (string) $component->slug : (string) $component;

        $paths = $component instanceof Component
            ? static::normalizePaths($component->gallery_paths ?? [])
            : [];

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }

        $key = static::key($slug);

        if ($key !== null) {
            SiteImage::query()
                ->where('key', $key)
                ->get()
                ->each(function (SiteImage $image): void {
                    if ($image->path) {
                        Storage::disk($image->disk ?: 'public')->delete($image->path);
                    }

                    $image->delete();
                });
        }

        SiteImages::flush();
    }

    protected static function sourcePathsForComponent(Component $component): array
    {
        return static::sourcePathsForData(
            $component->gallery_paths ?? [],
            (string) $component->slug,
        );
    }

    protected static function sourcePathsForData(array $galleryPaths, ?string $slug): array
    {
        $paths = static::normalizePaths($galleryPaths);

        if ($paths !== []) {
            return $paths;
        }

        $legacyPath = static::legacyPath($slug);

        return filled($legacyPath) ? [(string) $legacyPath] : [];
    }

    protected static function normalizePaths(array $paths): array
    {
        return array_values(array_unique(array_filter(array_map(static function ($path): ?string {
            if (! is_string($path)) {
                return null;
            }

            $path = trim($path);

            return $path !== '' ? $path : null;
        }, $paths))));
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

    protected static function paletteForType(string $type): array
    {
        return match ($type) {
            'cpu' => ['from' => '#1d4ed8', 'to' => '#7c3aed'],
            'gpu' => ['from' => '#0f172a', 'to' => '#2563eb'],
            'motherboard' => ['from' => '#111827', 'to' => '#0ea5e9'],
            'ram' => ['from' => '#111827', 'to' => '#8b5cf6'],
            'psu' => ['from' => '#151515', 'to' => '#334155'],
            'storage' => ['from' => '#0f172a', 'to' => '#14b8a6'],
            'case' => ['from' => '#111827', 'to' => '#1f2937'],
            'cooler' => ['from' => '#0f172a', 'to' => '#ec4899'],
            'adapters' => ['from' => '#111827', 'to' => '#22c55e'],
            'modding' => ['from' => '#4c1d95', 'to' => '#db2777'],
            default => ['from' => '#111827', 'to' => '#475569'],
        };
    }

    protected static function shapeForType(string $type): string
    {
        return match ($type) {
            'cpu' => <<<SVG
<rect x="212" y="188" width="296" height="296" rx="48" fill="rgba(15,23,42,0.82)" stroke="rgba(255,255,255,0.58)" stroke-width="12"/>
<rect x="272" y="248" width="176" height="176" rx="28" fill="rgba(226,232,240,0.92)"/>
<path d="M252 152v54M324 152v54M396 152v54M468 152v54M252 514v54M324 514v54M396 514v54M468 514v54M152 252h54M152 324h54M152 396h54M152 468h54M514 252h54M514 324h54M514 396h54M514 468h54" stroke="rgba(255,255,255,0.78)" stroke-width="16" stroke-linecap="round"/>
SVG,
            'gpu' => <<<SVG
<rect x="118" y="254" width="484" height="174" rx="36" fill="rgba(15,23,42,0.9)" stroke="rgba(255,255,255,0.45)" stroke-width="10"/>
<circle cx="282" cy="341" r="56" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.78)" stroke-width="12"/>
<circle cx="442" cy="341" r="56" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.78)" stroke-width="12"/>
<rect x="596" y="290" width="30" height="96" rx="8" fill="rgba(226,232,240,0.88)"/>
<path d="M128 454h372" stroke="rgba(255,255,255,0.55)" stroke-width="12" stroke-linecap="round"/>
SVG,
            'motherboard' => <<<SVG
<rect x="172" y="156" width="376" height="408" rx="34" fill="rgba(15,23,42,0.82)" stroke="rgba(255,255,255,0.44)" stroke-width="10"/>
<rect x="236" y="214" width="148" height="148" rx="24" fill="rgba(226,232,240,0.86)"/>
<rect x="414" y="214" width="82" height="36" rx="12" fill="rgba(255,255,255,0.74)"/>
<rect x="414" y="272" width="82" height="36" rx="12" fill="rgba(255,255,255,0.56)"/>
<rect x="236" y="402" width="260" height="26" rx="13" fill="rgba(255,255,255,0.62)"/>
<rect x="236" y="454" width="260" height="26" rx="13" fill="rgba(255,255,255,0.62)"/>
<path d="M206 194v336M514 194v336M206 382h308" stroke="rgba(255,255,255,0.22)" stroke-width="8"/>
SVG,
            'ram' => <<<SVG
<rect x="134" y="318" width="452" height="82" rx="24" fill="rgba(255,255,255,0.9)" stroke="rgba(17,24,39,0.18)" stroke-width="8"/>
<rect x="166" y="262" width="452" height="82" rx="24" fill="rgba(255,255,255,0.94)" stroke="rgba(17,24,39,0.18)" stroke-width="8"/>
<path d="M182 360h336M214 304h336" stroke="rgba(17,24,39,0.18)" stroke-width="14" stroke-linecap="round"/>
<path d="M176 400v28M216 400v28M256 400v28M296 400v28M336 400v28M376 400v28M416 400v28M456 400v28M496 400v28M536 400v28" stroke="rgba(251,191,36,0.82)" stroke-width="10" stroke-linecap="round"/>
SVG,
            'psu' => <<<SVG
<rect x="170" y="208" width="382" height="282" rx="38" fill="rgba(15,23,42,0.88)" stroke="rgba(255,255,255,0.4)" stroke-width="10"/>
<circle cx="298" cy="348" r="88" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.74)" stroke-width="12"/>
<circle cx="298" cy="348" r="28" fill="rgba(255,255,255,0.84)"/>
<rect x="430" y="274" width="82" height="148" rx="16" fill="rgba(255,255,255,0.16)" stroke="rgba(255,255,255,0.36)" stroke-width="8"/>
<path d="M430 316h82M430 348h82M430 380h82" stroke="rgba(255,255,255,0.42)" stroke-width="8"/>
SVG,
            'storage' => <<<SVG
<rect x="166" y="258" width="388" height="198" rx="34" fill="rgba(15,23,42,0.9)" stroke="rgba(255,255,255,0.46)" stroke-width="10"/>
<rect x="222" y="304" width="188" height="28" rx="14" fill="rgba(255,255,255,0.82)"/>
<rect x="222" y="350" width="270" height="24" rx="12" fill="rgba(255,255,255,0.44)"/>
<circle cx="502" cy="354" r="26" fill="rgba(255,255,255,0.84)"/>
SVG,
            'case' => <<<SVG
<rect x="218" y="140" width="284" height="416" rx="36" fill="rgba(15,23,42,0.22)" stroke="rgba(255,255,255,0.56)" stroke-width="10"/>
<rect x="258" y="182" width="164" height="260" rx="24" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.38)" stroke-width="6"/>
<circle cx="446" cy="228" r="28" fill="rgba(255,255,255,0.82)"/>
<circle cx="446" cy="324" r="28" fill="rgba(255,255,255,0.82)"/>
<circle cx="446" cy="420" r="28" fill="rgba(255,255,255,0.82)"/>
<rect x="262" y="502" width="198" height="24" rx="12" fill="rgba(17,24,39,0.82)"/>
SVG,
            'cooler' => <<<SVG
<circle cx="360" cy="340" r="124" fill="rgba(15,23,42,0.18)" stroke="rgba(255,255,255,0.62)" stroke-width="12"/>
<circle cx="360" cy="340" r="44" fill="rgba(255,255,255,0.88)"/>
<path d="M360 224c28 0 50 26 50 54-22 10-60 14-88 4-6-34 10-58 38-58ZM242 340c0-28 26-50 54-50 10 22 14 60 4 88-34 6-58-10-58-38ZM360 456c-28 0-50-26-50-54 22-10 60-14 88-4 6 34-10 58-38 58ZM478 340c0 28-26 50-54 50-10-22-14-60-4-88 34-6 58 10 58 38Z" fill="rgba(255,255,255,0.82)"/>
SVG,
            'adapters' => <<<SVG
<rect x="190" y="222" width="340" height="220" rx="30" fill="rgba(15,23,42,0.86)" stroke="rgba(255,255,255,0.44)" stroke-width="10"/>
<rect x="236" y="272" width="146" height="120" rx="18" fill="rgba(255,255,255,0.16)" stroke="rgba(255,255,255,0.24)" stroke-width="6"/>
<path d="M462 250c42 26 62 74 52 126M438 292c22 16 34 42 32 68M204 474v42M244 474v42M284 474v42M324 474v42M364 474v42M404 474v42M444 474v42" stroke="rgba(255,255,255,0.82)" stroke-width="12" stroke-linecap="round"/>
SVG,
            'modding' => <<<SVG
<rect x="160" y="290" width="400" height="78" rx="39" fill="rgba(17,24,39,0.9)"/>
<rect x="188" y="318" width="344" height="22" rx="11" fill="rgba(255,255,255,0.86)"/>
<circle cx="240" cy="212" r="54" fill="rgba(255,255,255,0.16)"/>
<circle cx="360" cy="186" r="54" fill="rgba(255,255,255,0.16)"/>
<circle cx="480" cy="212" r="54" fill="rgba(255,255,255,0.16)"/>
SVG,
            default => <<<SVG
<rect x="206" y="196" width="308" height="308" rx="58" fill="rgba(15,23,42,0.86)" stroke="rgba(255,255,255,0.48)" stroke-width="10"/>
<rect x="274" y="264" width="172" height="172" rx="32" fill="rgba(255,255,255,0.82)"/>
SVG,
        };
    }

    protected static function typeLabel(string $type): string
    {
        return match ($type) {
            'cpu' => 'CPU',
            'gpu' => 'GPU',
            'motherboard' => 'MB',
            'ram' => 'RAM',
            'psu' => 'PSU',
            'storage' => 'SSD',
            'case' => 'CASE',
            'cooler' => 'COOLER',
            'adapters' => 'ADAPTER',
            'modding' => 'MOD',
            default => 'PART',
        };
    }

    protected static function shortLabel(string $label): string
    {
        $label = trim(preg_replace('/\s+/', ' ', $label) ?? '');

        if (mb_strlen($label) <= 24) {
            return $label;
        }

        return rtrim(mb_substr($label, 0, 24)) . '...';
    }
}
