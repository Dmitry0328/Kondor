@php
    $compareValidSlugs = collect($validCompareSlugs ?? \App\Support\StorefrontBuilds::all())
        ->map(static fn ($item) => is_array($item) ? ($item['slug'] ?? null) : $item)
        ->map(static fn ($slug): string => trim((string) $slug))
        ->filter()
        ->values()
        ->all();
    $comparePageItems = collect($pageCompareItems ?? [])
        ->map(static fn ($slug): string => trim((string) $slug))
        ->filter()
        ->values()
        ->all();
@endphp

<div
    hidden
    data-compare-config
    data-compare-url="{{ route('catalog.compare') }}"
    data-compare-limit="3"
    data-compare-valid-slugs='@json($compareValidSlugs)'
    @if ($comparePageItems !== [])
        data-compare-page-items='@json($comparePageItems)'
    @endif
></div>
