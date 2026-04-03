<?php

namespace App\Support;

use App\Filament\Resources\Builds\BuildResource;
use App\Models\Build;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BuildPreview
{
    protected const SESSION_KEY = 'admin.build_previews';

    protected const TTL_MINUTES = 90;

    public static function store(array $formData, ?Build $record = null, ?string $returnUrl = null): string
    {
        $token = Str::lower(Str::random(40));
        $previews = collect((array) session()->get(static::SESSION_KEY, []))
            ->filter(fn ($preview): bool => static::isValidPreview($preview));

        $hasGalleryField = array_key_exists('gallery_uploads', $formData);
        $galleryUploads = BuildImages::normalizeUploadState($formData['gallery_uploads'] ?? null);
        $persistData = static::normalizePersistData($formData);

        $previews->put($token, [
            'user_id' => (int) Auth::id(),
            'expires_at' => now()->addMinutes(static::TTL_MINUTES)->toIso8601String(),
            'record_id' => $record?->getKey(),
            'return_url' => filled($returnUrl) ? trim((string) $returnUrl) : null,
            'persist_data' => $persistData,
            'gallery_uploads' => $galleryUploads,
            'build' => static::makeStorefrontPayload($persistData, $record, $returnUrl, $hasGalleryField, $galleryUploads),
        ]);

        session()->put(static::SESSION_KEY, $previews->all());
        session()->save();

        return $token;
    }

    public static function find(string $token): ?array
    {
        $preview = session()->get(static::SESSION_KEY . '.' . trim($token));

        if (! static::isValidPreview($preview)) {
            static::forget($token);

            return null;
        }

        if ((int) ($preview['user_id'] ?? 0) !== (int) Auth::id()) {
            return null;
        }

        return is_array($preview['build'] ?? null) ? $preview['build'] : null;
    }

    public static function persist(string $token, bool $isActive): Build
    {
        $preview = static::resolvePreviewEnvelope($token);

        if ($preview === null) {
            throw ValidationException::withMessages([
                'preview' => 'Чернетка превʼю більше недоступна. Відкрий превʼю ще раз із адмінки.',
            ]);
        }

        $recordId = (int) ($preview['record_id'] ?? 0);
        $record = $recordId > 0 ? Build::query()->find($recordId) : null;
        $persistData = is_array($preview['persist_data'] ?? null) ? $preview['persist_data'] : [];
        $galleryUploads = BuildImages::normalizeUploadState($preview['gallery_uploads'] ?? null);

        $payload = static::validatePersistData($persistData, $record);
        $payload['is_active'] = $isActive;

        $build = $record ?? new Build();
        $build->fill($payload);
        $build->save();

        BuildImages::sync($build, $galleryUploads);

        static::refreshStoredPreview($token, $preview, $build, $payload, $galleryUploads);

        return $build;
    }

    public static function forget(string $token): void
    {
        $previews = collect((array) session()->get(static::SESSION_KEY, []));
        $previews->forget(trim($token));
        session()->put(static::SESSION_KEY, $previews->all());
        session()->save();
    }

    protected static function isValidPreview(mixed $preview): bool
    {
        if (! is_array($preview)) {
            return false;
        }

        $expiresAt = data_get($preview, 'expires_at');

        if (! is_string($expiresAt) || $expiresAt === '') {
            return false;
        }

        return now()->lt(Carbon::parse($expiresAt));
    }

    protected static function makeStorefrontPayload(
        array $persistData,
        ?Build $record = null,
        ?string $returnUrl = null,
        bool $hasGalleryField = false,
        array $galleryUploads = [],
    ): array
    {
        $previewAttributes = Arr::only($persistData, [
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
        ]);

        $build = $record ? $record->replicate() : new Build();
        $build->forceFill([
            ...($record?->attributesToArray() ?? []),
            ...$previewAttributes,
            'slug' => static::resolvePreviewSlug($previewAttributes, $record),
            'tone' => trim((string) ($previewAttributes['tone'] ?? $record?->tone ?? 'violet')) ?: 'violet',
            'name' => trim((string) ($previewAttributes['name'] ?? $record?->name ?? 'Чернетка збірки')) ?: 'Чернетка збірки',
            'product_code' => trim((string) ($previewAttributes['product_code'] ?? $record?->product_code ?? '')),
            'gpu' => trim((string) ($previewAttributes['gpu'] ?? $record?->gpu ?? '')),
            'cpu' => trim((string) ($previewAttributes['cpu'] ?? $record?->cpu ?? '')),
            'ram' => trim((string) ($previewAttributes['ram'] ?? $record?->ram ?? '')),
            'storage' => trim((string) ($previewAttributes['storage'] ?? $record?->storage ?? '')),
            'price' => max(0, (int) round((float) ($previewAttributes['price'] ?? $record?->price ?? 0))),
            'fps_score' => max(0, (int) ($previewAttributes['fps_score'] ?? $record?->fps_score ?? 0)),
            'fps_profiles' => $previewAttributes['fps_profiles'] ?? $record?->fps_profiles,
            'product_specs' => $previewAttributes['product_specs'] ?? $record?->product_specs,
            'about' => $previewAttributes['about'] ?? $record?->about,
            'base_components' => $previewAttributes['base_components'] ?? $record?->base_components,
            'configurator_groups' => $previewAttributes['configurator_groups'] ?? $record?->configurator_groups,
            'sort_order' => max(0, (int) ($previewAttributes['sort_order'] ?? $record?->sort_order ?? 0)),
            'is_active' => array_key_exists('is_active', $previewAttributes)
                ? (bool) $previewAttributes['is_active']
                : (bool) ($record?->is_active ?? true),
        ]);

        $payload = $build->toStorefrontPayload();
        $payload['is_preview'] = true;
        $payload['preview_back_url'] = filled($returnUrl) ? trim((string) $returnUrl) : null;

        if ($hasGalleryField) {
            $payload['gallery_images'] = [];
            $payload['image_url'] = null;
            $payload['preview_gallery_images'] = BuildImages::urlsForUploadState($galleryUploads);

            if ($payload['preview_gallery_images'] !== []) {
                $payload['gallery_images'] = $payload['preview_gallery_images'];
                $payload['image_url'] = $payload['preview_gallery_images'][0] ?? null;
            }
        }

        return $payload;
    }

    protected static function normalizePersistData(array $formData): array
    {
        $data = BuildResource::collapseAboutFromForm($formData);
        $data = BuildResource::normalizeConfiguratorFromForm($data);
        $data = BuildResource::normalizeFpsProfilesFromForm($data);

        unset($data['gallery_uploads']);

        return Arr::only($data, [
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
        ]);
    }

    protected static function validatePersistData(array $persistData, ?Build $record = null): array
    {
        $recordId = $record?->getKey();

        return Validator::make($persistData, [
            'slug' => ['required', 'string', 'max:255', Rule::unique('builds', 'slug')->ignore($recordId)],
            'tone' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:64', Rule::unique('builds', 'product_code')->ignore($recordId)],
            'gpu' => ['nullable', 'string'],
            'cpu' => ['nullable', 'string'],
            'ram' => ['nullable', 'string'],
            'storage' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'fps_score' => ['nullable', 'integer', 'min:0'],
            'fps_profiles' => ['nullable', 'array'],
            'product_specs' => ['nullable', 'array'],
            'about' => ['nullable', 'array'],
            'base_components' => ['nullable', 'array'],
            'configurator_groups' => ['nullable', 'array'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();
    }

    protected static function resolvePreviewEnvelope(string $token): ?array
    {
        $preview = session()->get(static::SESSION_KEY . '.' . trim($token));

        if (! static::isValidPreview($preview)) {
            static::forget($token);

            return null;
        }

        if ((int) ($preview['user_id'] ?? 0) !== (int) Auth::id()) {
            return null;
        }

        return is_array($preview) ? $preview : null;
    }

    protected static function refreshStoredPreview(
        string $token,
        array $preview,
        Build $build,
        array $persistData,
        array $galleryUploads,
    ): void {
        $returnUrl = BuildResource::getUrl('edit', ['record' => $build->getKey()], isAbsolute: false);

        $preview['record_id'] = $build->getKey();
        $preview['return_url'] = $returnUrl;
        $preview['persist_data'] = [
            ...$persistData,
            'is_active' => (bool) $build->is_active,
        ];
        $preview['gallery_uploads'] = $galleryUploads;
        $preview['build'] = static::makeStorefrontPayload(
            $preview['persist_data'],
            $build,
            $returnUrl,
            true,
            $galleryUploads,
        );

        session()->put(static::SESSION_KEY . '.' . trim($token), $preview);
        session()->save();
    }

    protected static function resolvePreviewSlug(array $attributes, ?Build $record = null): string
    {
        $slug = trim((string) ($attributes['slug'] ?? $record?->slug ?? ''));

        if ($slug !== '') {
            return $slug;
        }

        $name = trim((string) ($attributes['name'] ?? $record?->name ?? 'preview-build'));
        $generated = Str::slug($name);

        return $generated !== '' ? "{$generated}-preview" : 'preview-build';
    }
}
