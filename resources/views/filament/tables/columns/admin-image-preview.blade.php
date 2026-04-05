@php
    $imageUrl = filled($imageUrl ?? null) ? (string) $imageUrl : null;
    $placeholderUrl = filled($placeholderUrl ?? null) ? (string) $placeholderUrl : '';
    $previewUrl = $imageUrl ?: $placeholderUrl;
    $imageUrls = collect((array) ($imageUrls ?? []))
        ->map(static fn ($url): string => trim((string) $url))
        ->filter()
        ->unique()
        ->values()
        ->all();

    if ($imageUrls === []) {
        $imageUrls = [$previewUrl];
    }

    $hasImage = array_key_exists('hasImage', get_defined_vars()) ? (bool) $hasImage : $imageUrl !== null;
    $caption = trim((string) ($caption ?? 'Photo'));
    $alt = trim((string) ($alt ?? $caption));
    $buttonTitle = count($imageUrls) > 1 ? 'Open gallery' : ($hasImage ? 'Open photo' : 'Open placeholder');
    $clickToOpen = array_key_exists('clickToOpen', get_defined_vars()) ? (bool) $clickToOpen : true;
@endphp

<div
    x-data='{
        open: false,
        activeIndex: 0,
        imageUrls: @json($imageUrls),
    }'
    data-admin-image-preview
>
    @if ($clickToOpen)
        <button
            type="button"
            x-on:click.stop.prevent="open = true; activeIndex = 0"
            x-on:keydown.enter.prevent="open = true; activeIndex = 0"
            x-on:keydown.space.prevent="open = true; activeIndex = 0"
            title="{{ $buttonTitle }}"
            style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;padding:0;border:1px solid #d7deea;border-radius:14px;background:#fff;overflow:hidden;cursor:zoom-in;box-shadow:0 8px 18px rgba(15,23,42,0.08);"
        >
            <img
                src="{{ $previewUrl }}"
                alt="{{ $alt }}"
                loading="lazy"
                style="display:block;width:100%;height:100%;object-fit:cover;background:linear-gradient(180deg,#eef2ff,#e2e8f0);"
            >

            @unless ($hasImage)
                <span
                    style="position:absolute;left:4px;right:4px;bottom:4px;padding:2px 6px;border-radius:999px;background:rgba(17,24,39,0.78);color:#fff;font-size:9px;font-weight:700;line-height:1.2;text-transform:uppercase;letter-spacing:0.04em;"
                >
                    No photo
                </span>
            @endunless

            @if (count($imageUrls) > 1)
                <span
                    style="position:absolute;top:4px;right:4px;min-width:18px;height:18px;padding:0 6px;border-radius:999px;background:rgba(15,23,42,0.82);color:#fff;font-size:10px;font-weight:700;line-height:18px;text-align:center;"
                >
                    {{ count($imageUrls) }}
                </span>
            @endif
        </button>
    @else
        <span
            title="{{ $buttonTitle }}"
            style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;padding:0;border:1px solid #d7deea;border-radius:14px;background:#fff;overflow:hidden;cursor:default;box-shadow:0 8px 18px rgba(15,23,42,0.08);"
        >
            <img
                src="{{ $previewUrl }}"
                alt="{{ $alt }}"
                loading="lazy"
                style="display:block;width:100%;height:100%;object-fit:cover;background:linear-gradient(180deg,#eef2ff,#e2e8f0);"
            >

            @unless ($hasImage)
                <span
                    style="position:absolute;left:4px;right:4px;bottom:4px;padding:2px 6px;border-radius:999px;background:rgba(17,24,39,0.78);color:#fff;font-size:9px;font-weight:700;line-height:1.2;text-transform:uppercase;letter-spacing:0.04em;"
                >
                    No photo
                </span>
            @endunless
        </span>
    @endif

    @if ($clickToOpen)
        <div
            x-cloak
            x-show="open"
            x-transition.opacity.duration.150ms
            x-on:click.self="open = false; activeIndex = 0"
            x-on:keydown.escape.window="open = false; activeIndex = 0"
            style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;padding:32px;background:rgba(15,23,42,0.78);backdrop-filter:blur(4px);"
        >
            <button
                type="button"
                x-on:click.stop="open = false; activeIndex = 0"
                aria-label="Close"
                style="position:absolute;top:20px;right:20px;display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.14);color:#fff;font-size:28px;line-height:1;cursor:pointer;"
            >
                &times;
            </button>

            <figure style="margin:0;display:grid;gap:14px;justify-items:center;">
                <div style="display:grid;gap:14px;justify-items:center;">
                    @if (count($imageUrls) > 1)
                        <div style="display:flex;align-items:center;gap:12px;">
                            <button
                                type="button"
                                x-on:click.stop="if (imageUrls.length > 1) activeIndex = (activeIndex + imageUrls.length - 1) % imageUrls.length"
                                aria-label="Previous image"
                                style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.16);color:#fff;font-size:26px;line-height:1;cursor:pointer;"
                            >
                                &#8249;
                            </button>
                            <div style="color:#fff;font-size:13px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;" x-text="`${activeIndex + 1} / ${imageUrls.length}`"></div>
                            <button
                                type="button"
                                x-on:click.stop="if (imageUrls.length > 1) activeIndex = (activeIndex + 1) % imageUrls.length"
                                aria-label="Next image"
                                style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.16);color:#fff;font-size:26px;line-height:1;cursor:pointer;"
                            >
                                &#8250;
                            </button>
                        </div>
                    @endif

                    <img
                        x-bind:src="imageUrls[activeIndex] ?? @js($previewUrl)"
                        alt="{{ $alt }}"
                        style="display:block;max-width:min(92vw,1100px);max-height:84vh;border-radius:24px;background:#fff;box-shadow:0 24px 64px rgba(15,23,42,0.28);"
                    >
                </div>
                <figcaption style="color:#fff;font-size:14px;font-weight:600;text-align:center;">
                    {{ $caption }}
                    @unless ($hasImage)
                        <span style="opacity:0.76;">&bull; placeholder</span>
                    @endunless
                </figcaption>

                @if (count($imageUrls) > 1)
                    <div style="display:flex;max-width:min(92vw,1100px);flex-wrap:wrap;justify-content:center;gap:10px;">
                        @foreach ($imageUrls as $imageIndex => $galleryImageUrl)
                            <button
                                type="button"
                                x-on:click.stop="activeIndex = {{ $imageIndex }}"
                                x-bind:style="activeIndex === {{ $imageIndex }} ? 'border:2px solid #fff;box-shadow:0 10px 24px rgba(15,23,42,0.22);' : 'border:2px solid transparent;'"
                                aria-label="Open image {{ $imageIndex + 1 }}"
                                style="padding:0;border-radius:16px;background:transparent;overflow:hidden;cursor:pointer;"
                            >
                                <img
                                    src="{{ $galleryImageUrl }}"
                                    alt="{{ $alt }} thumbnail {{ $imageIndex + 1 }}"
                                    loading="lazy"
                                    style="display:block;width:72px;height:72px;object-fit:cover;border-radius:14px;background:#fff;"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </figure>
        </div>
    @endif
</div>
