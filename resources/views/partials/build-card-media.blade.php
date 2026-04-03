@php
    $buildCoverImageUrl = trim((string) ($build['image_url'] ?? ''));
    $buildGalleryImages = collect((array) ($build['gallery_images'] ?? []))
        ->filter(fn ($url): bool => is_string($url) && trim($url) !== '')
        ->map(fn ($url): string => trim((string) $url))
        ->values();

    if ($buildCoverImageUrl !== '' && ! $buildGalleryImages->contains($buildCoverImageUrl)) {
        $buildGalleryImages = $buildGalleryImages->prepend($buildCoverImageUrl);
    }

    $buildGalleryImages = $buildGalleryImages->unique()->values();
    $hasBuildGallery = $buildGalleryImages->isNotEmpty();
    $hasBuildGalleryControls = $buildGalleryImages->count() > 1;
@endphp

<div
    class="build-card__media site-image-target{{ $buildCoverImageUrl !== '' ? ' has-site-image' : '' }}{{ $hasBuildGallery ? ' has-gallery' : '' }}"
    data-site-image-key="build.{{ $build['slug'] }}.cover"
    @if ($buildCoverImageUrl !== '' && ! $hasBuildGallery)
        style="--site-image-url: url('{{ $buildCoverImageUrl }}');"
    @endif
    @if ($hasBuildGalleryControls)
        data-build-gallery
        data-gallery-index="0"
    @endif
    aria-hidden="true"
>
    @if ($hasBuildGallery)
        <div class="build-card__gallery" aria-hidden="true">
            @foreach ($buildGalleryImages as $imageIndex => $galleryImage)
                <div class="build-card__gallery-slide{{ $loop->first ? ' is-active' : '' }}" data-build-gallery-slide>
                    <img
                        src="{{ $galleryImage }}"
                        alt="{{ $build['name'] }} — фото {{ $imageIndex + 1 }}"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            @endforeach
        </div>
    @endif

    @if ($hasBuildGalleryControls)
        <div class="build-card__gallery-controls">
            <button class="build-card__gallery-button" type="button" data-build-gallery-prev aria-label="Попереднє фото">
                <svg viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M7.5 2.25L3.75 6L7.5 9.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="build-card__gallery-dots">
                @foreach ($buildGalleryImages as $imageIndex => $galleryImage)
                    <button
                        class="build-card__gallery-dot{{ $loop->first ? ' is-active' : '' }}"
                        type="button"
                        data-build-gallery-dot
                        data-build-gallery-index="{{ $imageIndex }}"
                        aria-label="Фото {{ $imageIndex + 1 }}"
                    ></button>
                @endforeach
            </div>

            <button class="build-card__gallery-button" type="button" data-build-gallery-next aria-label="Наступне фото">
                <svg viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M4.5 2.25L8.25 6L4.5 9.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    @endif
</div>
