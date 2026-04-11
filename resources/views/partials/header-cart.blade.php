@php
    $cartValidSlugs = collect(\App\Support\StorefrontBuilds::all())
        ->pluck('slug')
        ->filter(fn ($slug): bool => is_string($slug) && trim($slug) !== '')
        ->values();
    $hideTrackingLink = (bool) ($hideTrackingLink ?? false);

    $trackingLabel = "\u{0421}\u{0442}\u{0430}\u{0442}\u{0443}\u{0441} \u{0437}\u{0430}\u{043C}\u{043E}\u{0432}\u{043B}\u{0435}\u{043D}\u{043D}\u{044F}";
    $themeToggleLabel = "\u{041F}\u{0435}\u{0440}\u{0435}\u{043C}\u{043A}\u{043D}\u{0443}\u{0442}\u{0438} \u{0442}\u{0435}\u{043C}\u{0443}";
    $themeToggleText = "\u{0422}\u{0435}\u{043C}\u{043D}\u{0430} \u{0442}\u{0435}\u{043C}\u{0430}";
    $cartLabel = "\u{041A}\u{043E}\u{0448}\u{0438}\u{043A}";
    $cartEmptyText = "\u{041A}\u{043E}\u{0448}\u{0438}\u{043A} \u{043F}\u{043E}\u{043A}\u{0438} \u{043F}\u{043E}\u{0440}\u{043E}\u{0436}\u{043D}\u{0456}\u{0439}.";
    $toCatalogLabel = "\u{041F}\u{0435}\u{0440}\u{0435}\u{0439}\u{0442}\u{0438} \u{0432} \u{043A}\u{0430}\u{0442}\u{0430}\u{043B}\u{043E}\u{0433}";
    $totalLabel = "\u{0417}\u{0430}\u{0433}\u{0430}\u{043B}\u{044C}\u{043D}\u{0430} \u{0441}\u{0443}\u{043C}\u{0430}";
    $toCartLabel = "\u{041F}\u{0435}\u{0440}\u{0435}\u{0439}\u{0442}\u{0438} \u{0443} \u{043A}\u{043E}\u{0448}\u{0438}\u{043A}";
    $emptyPriceLabel = "0 \u{20B4}";
@endphp

<div
    data-cart-valid-slugs='@json($cartValidSlugs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'
    hidden
></div>

@unless ($hideTrackingLink)
    <a class="header-button" href="{{ route('orders.track') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M9 17H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        {{ $trackingLabel }}
    </a>
@endunless

<div class="header-utility-shell">
    <button class="theme-toggle-button" type="button" data-theme-toggle aria-label="{{ $themeToggleLabel }}">
        <span class="theme-toggle-button__icon theme-toggle-button__icon--sun" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="4.5" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 2.5V5.2M12 18.8V21.5M21.5 12H18.8M5.2 12H2.5M18.7 5.3L16.8 7.2M7.2 16.8L5.3 18.7M18.7 18.7L16.8 16.8M7.2 7.2L5.3 5.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </span>
        <span class="theme-toggle-button__icon theme-toggle-button__icon--moon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M20 14.2A8.5 8.5 0 0 1 9.8 4 8.7 8.7 0 1 0 20 14.2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
        </span>
        <span class="sr-only" data-theme-toggle-label>{{ $themeToggleText }}</span>
    </button>

    <div class="header-cart-shell" data-cart-shell>
        <a class="header-cart" href="{{ route('cart') }}" aria-label="{{ $cartLabel }}">
            <span data-cart-amount>{{ $emptyPriceLabel }}</span>
            <span class="header-cart__badge" data-cart-count hidden>0</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="9" cy="19" r="1.6" fill="currentColor"/>
                <circle cx="17" cy="19" r="1.6" fill="currentColor"/>
                <path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <div class="cart-preview" data-cart-preview>
            <div class="cart-preview__empty" data-cart-empty>
                <p>{{ $cartEmptyText }}</p>
                <a class="cart-preview__button" href="{{ route('catalog') }}">{{ $toCatalogLabel }}</a>
            </div>

            <div class="cart-preview__content" data-cart-content hidden>
                <div class="cart-preview__items" data-cart-items></div>

                <div class="cart-preview__summary">
                    <span>{{ $totalLabel }}</span>
                    <strong data-cart-total>{{ $emptyPriceLabel }}</strong>
                </div>

                <a class="cart-preview__button cart-preview__button--primary" href="{{ route('cart') }}">
                    {{ $toCartLabel }}
                </a>
            </div>
        </div>
    </div>
</div>
