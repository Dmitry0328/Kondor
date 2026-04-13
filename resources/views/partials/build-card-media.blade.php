@php
    $buildCoverImageUrl = trim((string) ($build['image_url'] ?? ''));
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
    $selectedCaseImageUrl = trim((string) ($selectedCaseVariant['image_url'] ?? ''));
    $displayCoverImageUrl = trim((string) ($selectedCaseImageUrl !== '' ? $selectedCaseImageUrl : $buildCoverImageUrl));
    $displayImageKey = $hasCaseChoices && $defaultCaseVariantKey !== ''
        ? 'build.' . $build['slug'] . '.case.' . $defaultCaseVariantKey . '.cover'
        : 'build.' . $build['slug'] . '.cover';
@endphp

<div
    class="build-card__media site-image-target{{ $displayCoverImageUrl !== '' ? ' has-site-image' : '' }}"
    data-build-media
    data-site-image-key="{{ $displayImageKey }}"
    @if ($displayCoverImageUrl !== '')
        style="--site-image-url: url('{{ $displayCoverImageUrl }}');"
    @endif
    aria-hidden="true"
></div>
