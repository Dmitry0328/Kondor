<?php

namespace App\Models;

use App\Support\SiteImages;
use App\Support\StorefrontBuilds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Build extends Model
{
    protected $fillable = [
        'slug',
        'tone',
        'name',
        'gpu',
        'cpu',
        'ram',
        'storage',
        'price',
        'fps_score',
        'product_specs',
        'about',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'fps_score' => 'integer',
            'product_specs' => 'array',
            'about' => 'array',
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
        return [
            'slug' => $this->slug,
            'tone' => $this->tone,
            'name' => $this->name,
            'gpu' => $this->gpu,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'price' => StorefrontBuilds::formatPrice($this->price),
            'fps_score' => $this->fps_score,
            'product_specs' => $this->product_specs ?: null,
            'about' => $this->about ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
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
}
