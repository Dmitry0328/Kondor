<?php

namespace App\Models;

use App\Support\BuildConfigurator;
use App\Support\StorefrontBuilds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TradeInRequest extends Model
{
    protected $fillable = [
        'build_id',
        'build_slug',
        'build_name',
        'status',
        'customer_name',
        'phone',
        'messenger_contact',
        'description',
        'photo_paths',
        'build_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'build_id' => 'integer',
            'photo_paths' => 'array',
            'build_snapshot' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (TradeInRequest $request): void {
            $paths = array_values(array_filter(array_map(
                static fn ($path): string => trim((string) $path),
                (array) ($request->photo_paths ?? []),
            )));

            if ($paths !== []) {
                Storage::disk('public')->delete($paths);
            }
        });
    }

    public function build(): BelongsTo
    {
        return $this->belongsTo(Build::class);
    }

    public function photoUrls(): array
    {
        return array_values(array_filter(array_map(function ($path): ?string {
            $path = trim((string) $path);

            return $path !== '' ? Storage::disk('public')->url($path) : null;
        }, (array) ($this->photo_paths ?? []))));
    }

    public function primaryPhotoUrl(): ?string
    {
        return $this->photoUrls()[0] ?? null;
    }

    public function hasPhotos(): bool
    {
        return $this->primaryPhotoUrl() !== null;
    }

    public function placeholderUrl(): string
    {
        $buildName = e($this->target_build_label);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none">
  <defs>
    <linearGradient id="bg" x1="80" y1="60" x2="540" y2="580" gradientUnits="userSpaceOnUse">
      <stop stop-color="#ffffff"/>
      <stop offset="1" stop-color="#eef3fb"/>
    </linearGradient>
    <linearGradient id="accent" x1="120" y1="140" x2="520" y2="500" gradientUnits="userSpaceOnUse">
      <stop stop-color="#f59e0b"/>
      <stop offset="1" stop-color="#111827"/>
    </linearGradient>
  </defs>
  <rect width="640" height="640" rx="48" fill="url(#bg)"/>
  <rect x="56" y="56" width="528" height="528" rx="36" fill="white" stroke="#d8e1ee" stroke-width="4"/>
  <rect x="148" y="168" width="344" height="220" rx="32" fill="url(#accent)" opacity="0.14"/>
  <rect x="168" y="188" width="304" height="180" rx="26" stroke="#f59e0b" stroke-width="10"/>
  <circle cx="320" cy="278" r="46" stroke="#f59e0b" stroke-width="10"/>
  <path d="M212 418L274 346L330 398L388 332L428 418" stroke="#111827" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
  <text x="320" y="118" text-anchor="middle" fill="#f59e0b" font-size="34" font-family="Arial, sans-serif" font-weight="700" letter-spacing="2">TRADE-IN</text>
  <text x="320" y="504" text-anchor="middle" fill="#111827" font-size="34" font-family="Arial, sans-serif" font-weight="700">{$buildName}</text>
  <text x="320" y="554" text-anchor="middle" fill="#64748b" font-size="24" font-family="Arial, sans-serif" font-weight="600">no photo uploaded</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public function getTargetBuildLabelAttribute(): string
    {
        return (string) ($this->build?->name ?: $this->build_name ?: 'Без прив’язки до збірки');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ((string) $this->status) {
            'processing' => 'В роботі',
            'completed' => 'Закрито',
            'rejected' => 'Відхилено',
            default => 'Нова',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ((string) $this->status) {
            'processing' => 'warning',
            'completed' => 'success',
            'rejected' => 'danger',
            default => 'info',
        };
    }

    public function getPhotosCountAttribute(): int
    {
        return count((array) ($this->photo_paths ?? []));
    }

    public function snapshot(): array
    {
        return is_array($this->build_snapshot) ? $this->build_snapshot : [];
    }

    public function buildSummaryLines(): array
    {
        return array_values(array_filter(array_map(
            static fn ($line): string => trim((string) $line),
            (array) ($this->snapshot()['summary'] ?? []),
        )));
    }

    public function buildDetailLines(): array
    {
        $summary = $this->buildSummaryLines();

        if ($summary !== []) {
            return $summary;
        }

        $buildPayload = $this->build_slug ? StorefrontBuilds::findBySlug((string) $this->build_slug) : null;

        if (! is_array($buildPayload)) {
            return [];
        }

        $configuratorSummary = BuildConfigurator::resolveSelection($buildPayload)['summary'] ?? [];
        $configuratorSummary = array_values(array_filter(array_map(
            static fn ($line): string => trim((string) $line),
            (array) $configuratorSummary,
        )));

        if ($configuratorSummary !== []) {
            return $configuratorSummary;
        }

        return array_values(array_filter([
            filled($buildPayload['gpu'] ?? null) ? 'Відеокарта: ' . (string) $buildPayload['gpu'] : null,
            filled($buildPayload['cpu'] ?? null) ? 'Процесор: ' . (string) $buildPayload['cpu'] : null,
            filled($buildPayload['ram'] ?? null) ? "Оперативна пам'ять: " . (string) $buildPayload['ram'] : null,
            filled($buildPayload['storage'] ?? null) ? 'Накопичувач: ' . (string) $buildPayload['storage'] : null,
        ]));
    }

    public function snapshotSharedUrl(): ?string
    {
        $url = trim((string) ($this->snapshot()['shared_url'] ?? ''));

        return $url !== '' ? $url : null;
    }

    public function snapshotBuildUrl(): ?string
    {
        $url = trim((string) ($this->snapshot()['build_url'] ?? ''));

        if ($url !== '') {
            return $url;
        }

        return $this->build_slug ? route('product.show', ['slug' => $this->build_slug]) : null;
    }

    public function snapshotAdditionalPrice(): int
    {
        return max(0, (int) ($this->snapshot()['additional_price'] ?? 0));
    }

    public function snapshotTotalPrice(): ?int
    {
        $value = (int) ($this->snapshot()['total_price'] ?? 0);

        return $value > 0 ? $value : null;
    }
}
