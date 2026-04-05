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
    $rootId = 'admin-image-preview-' . substr(md5($previewUrl . '|' . $caption . '|' . json_encode($imageUrls)), 0, 12);
@endphp

<div
    data-admin-image-preview-root
    data-preview-id="{{ $rootId }}"
    data-image-urls='@json($imageUrls)'
    data-active-index="0"
>
    @if ($clickToOpen)
        <button
            type="button"
            data-admin-image-preview-open
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
            hidden
            data-admin-image-preview-modal
            style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:32px;background:rgba(15,23,42,0.78);backdrop-filter:blur(4px);"
        >
            <button
                type="button"
                data-admin-image-preview-close
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
                                data-admin-image-preview-prev
                                aria-label="Previous image"
                                style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.16);color:#fff;font-size:26px;line-height:1;cursor:pointer;"
                            >
                                &#8249;
                            </button>
                            <div
                                data-admin-image-preview-counter
                                style="color:#fff;font-size:13px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;"
                            >
                                1 / {{ count($imageUrls) }}
                            </div>
                            <button
                                type="button"
                                data-admin-image-preview-next
                                aria-label="Next image"
                                style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.16);color:#fff;font-size:26px;line-height:1;cursor:pointer;"
                            >
                                &#8250;
                            </button>
                        </div>
                    @endif

                    <img
                        src="{{ $previewUrl }}"
                        alt="{{ $alt }}"
                        data-admin-image-preview-image
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
                                data-admin-image-preview-thumb
                                data-image-index="{{ $imageIndex }}"
                                aria-label="Open image {{ $imageIndex + 1 }}"
                                style="padding:0;border-radius:16px;border:2px solid {{ $loop->first ? '#ffffff' : 'transparent' }};background:transparent;overflow:hidden;cursor:pointer;"
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

@once
    <script>
        (() => {
            if (window.__adminImagePreviewInitialized) {
                return;
            }

            window.__adminImagePreviewInitialized = true;

            const getRoot = (target) => target.closest('[data-admin-image-preview-root]');
            const getUrls = (root) => {
                try {
                    const parsed = JSON.parse(root?.dataset.imageUrls ?? '[]');

                    return Array.isArray(parsed) ? parsed.filter((item) => typeof item === 'string' && item.trim() !== '') : [];
                } catch (error) {
                    return [];
                }
            };
            const getIndex = (root) => {
                const index = Number.parseInt(root?.dataset.activeIndex ?? '0', 10);

                return Number.isNaN(index) ? 0 : index;
            };
            const setIndex = (root, nextIndex) => {
                const urls = getUrls(root);
                const safeIndex = urls.length > 0 ? Math.max(0, Math.min(nextIndex, urls.length - 1)) : 0;

                root.dataset.activeIndex = `${safeIndex}`;

                const modalImage = root.querySelector('[data-admin-image-preview-image]');
                const counter = root.querySelector('[data-admin-image-preview-counter]');
                const thumbs = root.querySelectorAll('[data-admin-image-preview-thumb]');

                if (modalImage && urls[safeIndex]) {
                    modalImage.src = urls[safeIndex];
                }

                if (counter) {
                    counter.textContent = `${safeIndex + 1} / ${Math.max(urls.length, 1)}`;
                }

                thumbs.forEach((thumb) => {
                    const thumbIndex = Number.parseInt(thumb.dataset.imageIndex ?? '0', 10);
                    const isActive = thumbIndex === safeIndex;

                    thumb.style.borderColor = isActive ? '#ffffff' : 'transparent';
                    thumb.style.boxShadow = isActive ? '0 10px 24px rgba(15,23,42,0.22)' : 'none';
                });
            };
            const openModal = (root) => {
                const modal = root?.querySelector('[data-admin-image-preview-modal]');

                if (!modal) {
                    return;
                }

                setIndex(root, 0);
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
            };
            const closeModal = (root) => {
                const modal = root?.querySelector('[data-admin-image-preview-modal]');

                if (!modal) {
                    return;
                }

                modal.hidden = true;

                if (!document.querySelector('[data-admin-image-preview-modal]:not([hidden])')) {
                    document.body.style.overflow = '';
                }
            };

            document.addEventListener('click', (event) => {
                const openButton = event.target.closest('[data-admin-image-preview-open]');

                if (openButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    openModal(getRoot(openButton));
                    return;
                }

                const closeButton = event.target.closest('[data-admin-image-preview-close]');

                if (closeButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    closeModal(getRoot(closeButton));
                    return;
                }

                const prevButton = event.target.closest('[data-admin-image-preview-prev]');

                if (prevButton) {
                    event.preventDefault();
                    event.stopPropagation();

                    const root = getRoot(prevButton);
                    const urls = getUrls(root);

                    if (urls.length > 1) {
                        setIndex(root, (getIndex(root) + urls.length - 1) % urls.length);
                    }

                    return;
                }

                const nextButton = event.target.closest('[data-admin-image-preview-next]');

                if (nextButton) {
                    event.preventDefault();
                    event.stopPropagation();

                    const root = getRoot(nextButton);
                    const urls = getUrls(root);

                    if (urls.length > 1) {
                        setIndex(root, (getIndex(root) + 1) % urls.length);
                    }

                    return;
                }

                const thumbButton = event.target.closest('[data-admin-image-preview-thumb]');

                if (thumbButton) {
                    event.preventDefault();
                    event.stopPropagation();

                    const root = getRoot(thumbButton);
                    const nextIndex = Number.parseInt(thumbButton.dataset.imageIndex ?? '0', 10);

                    if (!Number.isNaN(nextIndex)) {
                        setIndex(root, nextIndex);
                    }

                    return;
                }

                const modal = event.target.closest('[data-admin-image-preview-modal]');

                if (modal && event.target === modal) {
                    closeModal(getRoot(modal));
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    document
                        .querySelectorAll('[data-admin-image-preview-modal]:not([hidden])')
                        .forEach((modal) => closeModal(getRoot(modal)));
                }
            });
        })();
    </script>
@endonce
