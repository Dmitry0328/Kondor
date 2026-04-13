@php
    $caseVariants = collect((array) ($build['case_variants'] ?? []))
        ->filter(fn ($variant): bool => is_array($variant) && (bool) ($variant['enabled'] ?? false))
        ->mapWithKeys(fn (array $variant, $key): array => [(string) $key => $variant]);
    $hasCaseChoices = $caseVariants->count() > 1;
    $defaultCaseVariantKey = trim((string) ($build['default_case_variant'] ?? ''));

    if (! $caseVariants->has($defaultCaseVariantKey)) {
        $defaultCaseVariantKey = (string) ($caseVariants->keys()->first() ?? '');
    }

    $selectedCaseVariant = $defaultCaseVariantKey !== '' && is_array($caseVariants->get($defaultCaseVariantKey))
        ? $caseVariants->get($defaultCaseVariantKey)
        : null;
@endphp

@if ($hasCaseChoices)
    <div class="build-card__case-picker" aria-label="Варіанти кольору збірки">
        <div class="build-card__case-options">
            @foreach ($caseVariants as $caseKey => $caseVariant)
                @php
                    $caseLabel = $caseVariant['label'] ?? ($caseKey === 'white' ? 'Біла збірка' : 'Чорна збірка');
                    $caseVisualLabel = $caseKey === 'white' ? 'Біла' : 'Чорна';
                    $caseDescription = trim((string) ($caseVariant['description'] ?? ''));
                    $caseImageUrl = trim((string) ($caseVariant['image_url'] ?? ''));
                    $caseGalleryImages = collect((array) ($caseVariant['gallery_images'] ?? []))
                        ->filter(fn ($url): bool => is_string($url) && trim($url) !== '')
                        ->map(fn ($url): string => trim((string) $url))
                        ->values();

                    if ($caseImageUrl !== '' && ! $caseGalleryImages->contains($caseImageUrl)) {
                        $caseGalleryImages = $caseGalleryImages->prepend($caseImageUrl);
                    }

                    $caseGalleryPayload = $caseGalleryImages->unique()->values()->all();
                    $isActiveCase = $caseKey === $defaultCaseVariantKey;
                @endphp
                <button
                    class="build-card__case-option{{ $isActiveCase ? ' is-active' : '' }}"
                    type="button"
                    data-build-case-option
                    data-build-case-key="{{ $caseKey }}"
                    data-build-case-label="{{ $caseLabel }}"
                    data-build-case-description="{{ $caseDescription }}"
                    data-build-case-url="{{ route('product.show', ['slug' => $build['slug'], 'case' => $caseKey]) }}"
                    data-build-case-image-key="build.{{ $build['slug'] }}.case.{{ $caseKey }}.cover"
                    data-build-case-image="{{ $caseImageUrl }}"
                    data-build-case-gallery='@json($caseGalleryPayload)'
                    aria-pressed="{{ $isActiveCase ? 'true' : 'false' }}"
                    aria-label="{{ $caseLabel }}"
                    title="{{ $caseLabel }}"
                >
                    <span class="build-card__case-dot build-card__case-dot--{{ $caseKey }}" aria-hidden="true"></span>
                    <span class="build-card__case-option-label">{{ $caseVisualLabel }}</span>
                </button>
            @endforeach
        </div>

        <p class="build-card__case-description" data-build-case-description>Обери свій колір корпусу.</p>
    </div>
@endif
