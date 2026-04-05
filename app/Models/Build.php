<?php

namespace App\Models;

use App\Support\BuildConfigurator;
use App\Support\BuildImages;
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
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'price_raw' => $this->price,
        ];
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
