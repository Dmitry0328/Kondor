<?php

namespace App\Models;

use App\Support\AccessoryCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Accessory extends Model
{
    protected $fillable = [
        'type',
        'name',
        'slug',
        'vendor',
        'sku',
        'price',
        'summary',
        'gallery_paths',
        'specs',
        'package_items',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery_paths' => 'array',
            'specs' => 'array',
            'package_items' => 'array',
            'price' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function uploadedImageUrls(): array
    {
        return array_values(array_filter(array_map(function ($path): ?string {
            $path = trim((string) $path);

            return $path !== '' ? Storage::disk('public')->url($path) : null;
        }, (array) ($this->gallery_paths ?? []))));
    }

    public function imageUrls(): array
    {
        $urls = $this->uploadedImageUrls();

        if ($urls !== []) {
            return $urls;
        }

        return [$this->placeholderUrl()];
    }

    public function primaryImageUrl(): string
    {
        return $this->imageUrls()[0];
    }

    public function hasUploadedImages(): bool
    {
        return $this->uploadedImageUrls() !== [];
    }

    public function placeholderUrl(): string
    {
        $name = e($this->name ?: AccessoryCatalog::typeLabel((string) $this->type));
        $type = e(strtoupper(AccessoryCatalog::typeLabel((string) $this->type)));
        $accent = match ((string) $this->type) {
            'keyboard' => '#7c3aed',
            'mouse' => '#2563eb',
            'pad' => '#d97706',
            default => '#6f10c9',
        };

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none">
  <defs>
    <linearGradient id="bg" x1="80" y1="60" x2="540" y2="580" gradientUnits="userSpaceOnUse">
      <stop stop-color="#ffffff"/>
      <stop offset="1" stop-color="#eef3fb"/>
    </linearGradient>
    <linearGradient id="accent" x1="130" y1="150" x2="510" y2="470" gradientUnits="userSpaceOnUse">
      <stop stop-color="{$accent}"/>
      <stop offset="1" stop-color="#111827"/>
    </linearGradient>
  </defs>
  <rect width="640" height="640" rx="48" fill="url(#bg)"/>
  <rect x="56" y="56" width="528" height="528" rx="36" fill="white" stroke="#d8e1ee" stroke-width="4"/>
  <rect x="136" y="168" width="368" height="208" rx="28" fill="url(#accent)" opacity="0.12"/>
  <rect x="152" y="184" width="336" height="176" rx="24" fill="url(#accent)" opacity="0.18"/>
  <rect x="176" y="208" width="288" height="128" rx="18" stroke="{$accent}" stroke-width="10"/>
  <text x="320" y="120" text-anchor="middle" fill="{$accent}" font-size="34" font-family="Arial, sans-serif" font-weight="700" letter-spacing="2">{$type}</text>
  <text x="320" y="454" text-anchor="middle" fill="#111827" font-size="38" font-family="Arial, sans-serif" font-weight="700">{$name}</text>
  <text x="320" y="510" text-anchor="middle" fill="#64748b" font-size="24" font-family="Arial, sans-serif" font-weight="600">photo placeholder</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public function toStorefrontPayload(): array
    {
        return [
            'id' => (int) $this->getKey(),
            'type' => (string) $this->type,
            'type_label' => AccessoryCatalog::typeLabel((string) $this->type),
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'vendor' => trim((string) ($this->vendor ?? '')),
            'sku' => trim((string) ($this->sku ?? '')),
            'price_raw' => (int) ($this->price ?? 0),
            'price' => number_format((int) ($this->price ?? 0), 0, '.', ' '),
            'summary' => trim((string) ($this->summary ?? '')),
            'image_url' => $this->primaryImageUrl(),
            'image_urls' => $this->imageUrls(),
            'has_uploaded_images' => $this->hasUploadedImages(),
            'specs' => array_values(array_filter(array_map(function ($row): ?array {
                if (! is_array($row)) {
                    return null;
                }

                $label = trim((string) ($row['label'] ?? ''));
                $value = trim((string) ($row['value'] ?? ''));

                if ($label === '' || $value === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'value' => $value,
                    'is_highlighted' => (bool) ($row['is_highlighted'] ?? false),
                ];
            }, (array) ($this->specs ?? [])))),
            'package_items' => array_values(array_filter(array_map(function ($row): ?array {
                if (! is_array($row)) {
                    return null;
                }

                $label = trim((string) ($row['label'] ?? ''));

                if ($label === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'icon' => AccessoryCatalog::packageIcon((string) ($row['icon'] ?? 'generic')),
                    'is_highlighted' => (bool) ($row['is_highlighted'] ?? false),
                ];
            }, (array) ($this->package_items ?? [])))),
        ];
    }
}
