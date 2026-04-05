@php
    $previewUrl = filled($imageUrl ?? null) ? (string) $imageUrl : (string) $placeholderUrl;
    $hasImage = filled($imageUrl ?? null);
    $title = $hasImage ? 'Відкрити фото' : 'Фото відсутнє';
@endphp

<div x-data="{ open: false }" data-build-cover-preview>
    <button
        type="button"
        x-on:click.stop="open = true"
        title="{{ $title }}"
        style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;padding:0;border:1px solid #d7deea;border-radius:14px;background:#fff;overflow:hidden;cursor:zoom-in;box-shadow:0 8px 18px rgba(15,23,42,0.08);"
    >
        <img
            src="{{ $previewUrl }}"
            alt="{{ $buildName ?? 'Build cover' }}"
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
    </button>

    <template x-if="open">
        <div
            x-on:click.self="open = false"
            x-on:keydown.escape.window="open = false"
            style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:32px;background:rgba(15,23,42,0.78);backdrop-filter:blur(4px);"
        >
            <button
                type="button"
                x-on:click="open = false"
                aria-label="Закрити"
                style="position:absolute;top:20px;right:20px;display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:0;border-radius:999px;background:rgba(255,255,255,0.14);color:#fff;font-size:28px;line-height:1;cursor:pointer;"
            >
                &times;
            </button>

            <figure style="margin:0;display:grid;gap:14px;justify-items:center;">
                <img
                    src="{{ $previewUrl }}"
                    alt="{{ $buildName ?? 'Build cover' }}"
                    style="display:block;max-width:min(92vw,1100px);max-height:84vh;border-radius:24px;background:#fff;box-shadow:0 24px 64px rgba(15,23,42,0.28);"
                >
                <figcaption style="color:#fff;font-size:14px;font-weight:600;text-align:center;">
                    {{ $buildName ?? 'Збірка' }}
                    @unless ($hasImage)
                        <span style="opacity:0.76;">• заглушка</span>
                    @endunless
                </figcaption>
            </figure>
        </div>
    </template>
</div>
