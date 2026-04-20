@php
    $phoneHref = 'tel:+380633631066';
    $phoneLabel = '+380 63 363 10 66';
    $instagramUrl = 'https://www.instagram.com/kondor_pc/';
    $telegramUrl = 'https://t.me/kondor_channeI';
    $brandMarkLight = asset('images/kondor-mark-black.svg');
    $brandMarkDark = asset('images/kondor-mark-white.svg');

    $routeName = request()->route()?->getName();
    $isHome = $routeName === 'home' || request()->url() === url('/');
    $isCatalog = request()->routeIs('catalog') || request()->routeIs('catalog.compare');
    $showBuildCompare = \App\Support\SiteSettings::bool('build.compare.enabled', true);
@endphp

<header class="header">
    <div class="container header__inner">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand__mark" aria-hidden="true">
                <img class="brand__mark-image brand__mark-image--light" src="{{ $brandMarkLight }}" alt="">
                <img class="brand__mark-image brand__mark-image--dark" src="{{ $brandMarkDark }}" alt="">
            </span>
            <div>
                <div class="brand__name">KondorPC</div>
                <span class="brand__sub">Твоя база геймінгу</span>
            </div>
        </a>

        <div class="header__actions">
            <nav class="header-nav" aria-label="Основна навігація">
                <a class="header-nav__link{{ $isHome ? ' is-active' : '' }}" href="{{ url('/') }}">Головна</a>
                <a class="header-nav__link{{ $isCatalog ? ' is-active' : '' }}" href="{{ route('catalog') }}">Каталог</a>
                <a class="header-nav__link" href="{{ url('/') }}#about">Про нас</a>
                <a class="header-nav__link" href="{{ url('/') }}#faq">FAQ</a>
                @auth
                    @if (auth()->user()?->is_admin)
                        <a class="header-nav__link header-nav__link--admin" href="{{ url('/admin') }}">Адмінка</a>
                    @endif
                @endauth
            </nav>

            <div class="header-meta">
                <div class="header-socials" aria-label="Соціальні мережі">
                    <a class="header-social" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>
                        </svg>
                    </a>
                    <a class="header-social" href="{{ $telegramUrl }}" target="_blank" rel="noreferrer" aria-label="Telegram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M21 4L3 11.2L10.2 13.8L12.8 21L21 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M10.2 13.8L14.2 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>

                <a class="header-phone" href="{{ $phoneHref }}">{{ $phoneLabel }}</a>

                @if ($showBuildCompare)
                    <a class="header-compare" href="{{ route('catalog.compare') }}" data-compare-link aria-label="Порівняння збірок" title="Порівняння збірок">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M9 5H5A2 2 0 0 0 3 7V19A2 2 0 0 0 5 21H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 5H19A2 2 0 0 1 21 7V19A2 2 0 0 1 19 21H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 8H16M8 12H16M8 16H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span class="header-compare__count" data-compare-count hidden>0</span>
                    </a>
                @endif

                @include('partials.header-cart', ['hideTrackingLink' => true])
            </div>

            <a class="header-mobile-phone" href="{{ $phoneHref }}">{{ $phoneLabel }}</a>

            <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
        <div class="container mobile-menu__inner">
            <div class="mobile-menu__panel">
                <div class="mobile-menu__topbar">
                    <div class="mobile-menu__theme-copy">
                        <strong>Тема сайту</strong>
                        <span>Світла або темна</span>
                    </div>

                    <button class="theme-toggle-button mobile-menu__theme-toggle" type="button" data-theme-toggle aria-label="Перемкнути тему">
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
                        <span class="sr-only" data-theme-toggle-label>Темна тема</span>
                    </button>
                </div>

                <div class="mobile-menu__links">
                    <a href="{{ url('/') }}">Головна</a>
                    <a href="{{ route('catalog') }}">Каталог</a>
                    @if ($showBuildCompare)
                        <a class="mobile-menu__compare" href="{{ route('catalog.compare') }}" data-compare-link>
                            <span>Порівняння</span>
                            <span class="mobile-menu__compare-count" data-compare-count hidden>0</span>
                        </a>
                    @endif
                    <a href="{{ url('/') }}#about">Про нас</a>
                    <a href="{{ url('/') }}#faq">FAQ</a>
                    @auth
                        @if (auth()->user()?->is_admin)
                            <a href="{{ url('/admin') }}">Адмінка</a>
                        @endif
                    @endauth
                </div>

                <div class="mobile-menu__meta">
                    <a class="mobile-menu__phone" href="{{ $phoneHref }}">{{ $phoneLabel }}</a>

                    <div class="mobile-menu__socials" aria-label="Соціальні мережі">
                        <a class="mobile-menu__social" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>
                            </svg>
                        </a>
                        <a class="mobile-menu__social" href="{{ $telegramUrl }}" target="_blank" rel="noreferrer" aria-label="Telegram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M21 4L3 11.2L10.2 13.8L12.8 21L21 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M10.2 13.8L14.2 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@include('partials.storefront-disclaimer')
