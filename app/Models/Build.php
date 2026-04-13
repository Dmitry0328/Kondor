<?php

namespace App\Models;

use App\Support\BuildConfigurator;
use App\Support\BuildImages;
use App\Support\BuildResolutions;
use App\Support\FpsCatalog;
use App\Support\FpsProfiles;
use App\Support\SiteImages;
use App\Support\StorefrontBuilds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Build extends Model
{
    protected $fillable = [
        'slug',
        'tone',
        'name',
        'product_code',
        'gpu',
        'cpu',
        'ram',
        'storage',
        'price',
        'fps_score',
        'fps_profiles',
        'product_specs',
        'about',
        'base_components',
        'configurator_groups',
        'resolution_tags',
        'case_variants',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'fps_score' => 'integer',
            'fps_profiles' => 'array',
            'product_specs' => 'array',
            'about' => 'array',
            'base_components' => 'array',
            'configurator_groups' => 'array',
            'resolution_tags' => 'array',
            'case_variants' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Build $build): void {
            if ($build->wasChanged('slug')) {
                static::renameSiteImages((string) $build->getOriginal('slug'), (string) $build->slug);
            }

            StorefrontBuilds::flush();
        });

        static::deleted(function (Build $build): void {
            static::deleteSiteImagesForSlug((string) $build->slug);
            StorefrontBuilds::flush();
        });
    }

    public function toStorefrontPayload(): array
    {
        $catalog = FpsCatalog::all();
        $baseComponentIds = BuildConfigurator::normalizeBaseComponents((array) ($this->base_components ?? [])) ?? [];
        $baseComponentIds = [
            ...(BuildConfigurator::inferBaseComponents([
                'gpu' => $this->gpu,
                'cpu' => $this->cpu,
                'ram' => $this->ram,
                'storage' => $this->storage,
                'product_specs' => $this->product_specs,
            ]) ?? []),
            ...$baseComponentIds,
        ];
        $baseComponents = Component::query()
            ->whereIn('id', array_values($baseComponentIds))
            ->get()
            ->keyBy('id');
        $fallbackFps = max(0, (int) ($this->fps_score ?? 0));
        $fpsProfiles = FpsProfiles::normalize((array) ($this->fps_profiles ?? []), $catalog);
        $fpsLookup = FpsProfiles::makeLookup($fpsProfiles);
        $fpsDefaults = FpsProfiles::defaultState($catalog, $fpsProfiles);
        $baseFps = $fpsProfiles !== []
            ? FpsProfiles::resolve(
                $fpsLookup,
                $fpsProfiles,
                (string) ($fpsDefaults['game'] ?? ''),
                (string) ($fpsDefaults['display'] ?? ''),
                (string) ($fpsDefaults['preset'] ?? ''),
                $fallbackFps,
            )
            : 0;

        $gpu = $this->displayComponentValue('gpu', (string) $this->gpu, $baseComponentIds, $baseComponents);
        $cpu = $this->displayComponentValue('cpu', (string) $this->cpu, $baseComponentIds, $baseComponents);
        $ram = $this->displayComponentValue('ram', (string) $this->ram, $baseComponentIds, $baseComponents);
        $storage = $this->displayComponentValue('storage', (string) $this->storage, $baseComponentIds, $baseComponents);

        $galleryImages = BuildImages::urlsForSlug((string) $this->slug);
        $coverImageUrl = $galleryImages[0] ?? BuildImages::placeholderUrl((string) $this->name);
        $caseVariants = static::normalizeCaseVariants((array) ($this->case_variants ?? []), (string) $this->slug);

        return [
            'slug' => $this->slug,
            'tone' => $this->tone,
            'name' => $this->name,
            'product_code' => $this->resolveProductCode(),
            'image_url' => $coverImageUrl,
            'gallery_images' => $galleryImages,
            'gpu' => $gpu,
            'cpu' => $cpu,
            'ram' => $ram,
            'storage' => $storage,
            'price' => StorefrontBuilds::formatPrice($this->price),
            'fps_score' => $baseFps,
            'fps_profiles' => $fpsProfiles,
            'fps_lookup' => $fpsLookup,
            'fps_defaults' => $fpsDefaults,
            'product_specs' => $this->product_specs ?: null,
            'about' => $this->about ?: null,
            'base_components' => $baseComponentIds ?: null,
            'configurator_groups' => $this->configurator_groups ?: null,
            'resolution_tags' => BuildResolutions::normalize((array) ($this->resolution_tags ?? [])),
            'case_variants' => $caseVariants,
            'default_case_variant' => static::defaultCaseVariant($caseVariants),
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'price_raw' => $this->price,
        ];
    }

    protected static function normalizeCaseVariants(array $variants, string $slug = ''): array
    {
        $definitions = [
            'black' => 'Чорна збірка',
            'white' => 'Біла збірка',
        ];

        $normalized = [];

        foreach ($definitions as $key => $defaultLabel) {
            $variant = is_array($variants[$key] ?? null) ? $variants[$key] : [];
            $galleryPaths = BuildImages::normalizeUploadState($variant['gallery'] ?? []);
            $imagePath = trim((string) ($variant['image'] ?? $variant['image_path'] ?? ''));
            $galleryUrls = collect((array) ($variant['gallery_images'] ?? []))
                ->map(static fn ($value): string => trim((string) $value))
                ->filter()
                ->values()
                ->all();
            $imageUrl = trim((string) ($variant['image_url'] ?? ''));
            $siteImageKey = $slug !== '' ? 'build.' . trim($slug) . '.case.' . $key . '.cover' : null;
            $inlineImageUrl = $siteImageKey ? SiteImages::url($siteImageKey) : null;

            if ($imagePath === '' && $galleryPaths !== []) {
                $imagePath = (string) ($galleryPaths[0] ?? '');
            }

            if ($imagePath !== '' && ! in_array($imagePath, $galleryPaths, true)) {
                array_unshift($galleryPaths, $imagePath);
            }

            if (is_string($inlineImageUrl) && trim($inlineImageUrl) !== '') {
                $imageUrl = trim($inlineImageUrl);
            }

            if ($imageUrl === '' && $imagePath !== '') {
                $imageUrl = static::publicUploadUrl($imagePath);
            }

            if ($imageUrl === '' && $galleryUrls !== []) {
                $imageUrl = (string) ($galleryUrls[0] ?? '');
            }

            if ($imageUrl !== '' && ! in_array($imageUrl, $galleryUrls, true)) {
                array_unshift($galleryUrls, $imageUrl);
            }

            $enabled = (bool) ($variant['enabled'] ?? false) && ($imagePath !== '' || $galleryPaths !== [] || $imageUrl !== '' || $galleryUrls !== []);

            $normalized[$key] = [
                'key' => $key,
                'enabled' => $enabled,
                'label' => trim((string) ($variant['label'] ?? $defaultLabel)) ?: $defaultLabel,
                'description' => trim((string) ($variant['description'] ?? '')),
                'image_path' => $imagePath !== '' ? $imagePath : null,
                'image_url' => $imageUrl,
                'gallery_paths' => $galleryPaths,
                'gallery_images' => $galleryUrls !== []
                    ? $galleryUrls
                    : array_values(array_filter(array_map(
                        static fn (string $path): string => static::publicUploadUrl($path),
                        $galleryPaths,
                    ))),
            ];
        }

        return $normalized;
    }

    protected static function defaultCaseVariant(array $variants): ?string
    {
        foreach (['black', 'white'] as $key) {
            if ((bool) ($variants[$key]['enabled'] ?? false)) {
                return $key;
            }
        }

        return null;
    }

    protected static function renameSiteImages(string $oldSlug, string $newSlug): void
    {
        $oldPrefix = 'build.' . trim($oldSlug) . '.';
        $newPrefix = 'build.' . trim($newSlug) . '.';

        if ($oldPrefix === 'build..' || $newPrefix === 'build..' || $oldPrefix === $newPrefix) {
            return;
        }

        SiteImage::query()
            ->where('key', 'like', $oldPrefix . '%')
            ->get()
            ->each(function (SiteImage $image) use ($oldPrefix, $newPrefix): void {
                $image->update([
                    'key' => $newPrefix . substr($image->key, strlen($oldPrefix)),
                ]);
            });

        SiteImages::flush();
    }

    protected static function deleteSiteImagesForSlug(string $slug): void
    {
        $prefix = 'build.' . trim($slug) . '.';

        if ($prefix === 'build..') {
            return;
        }

        SiteImage::query()
            ->where('key', 'like', $prefix . '%')
            ->get()
            ->each(function (SiteImage $image): void {
                if ($image->path) {
                    Storage::disk($image->disk ?: 'public')->delete($image->path);
                }

                $image->delete();
            });

        SiteImages::flush();
    }

    protected function displayComponentValue(string $slot, string $legacyValue, array $baseComponentIds, $baseComponents): string
    {
        $legacyValue = trim($legacyValue);

        if ($legacyValue !== '') {
            return $legacyValue;
        }

        $baseComponentId = (int) ($baseComponentIds[$slot] ?? 0);
        $baseComponent = $baseComponentId > 0 ? $baseComponents->get($baseComponentId) : null;

        if ($baseComponent instanceof Component) {
            return (string) $baseComponent->name;
        }

        return 'Відсутня інформація про комплектуючу';
    }
    protected static function publicUploadUrl(string $path): string
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

    protected function resolveProductCode(): string
    {
        $productCode = trim((string) ($this->product_code ?? ''));

        if ($productCode !== '') {
            return $productCode;
        }

        return (string) (570000 + (int) $this->getKey());
    }

    public function tradeInRequests(): HasMany
    {
        return $this->hasMany(TradeInRequest::class);
    }
}
