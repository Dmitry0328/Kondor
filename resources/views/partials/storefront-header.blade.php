@php
    $phoneHref = 'tel:+380633631066';
    $phoneLabel = '+380 63 363 10 66';
    $instagramUrl = 'https://www.instagram.com/kondor_pc/';
    $telegramUrl = 'https://t.me/kondor_channeI';
    $brandMarkLight = asset('images/kondor-mark-black.svg');
    $brandMarkDark = asset('images/kondor-mark-white.svg');

    $routeName = request()->route()?->getName();
    $isHome = $routeName === 'home' || request()->url() === url('/');
    $isCatalog = request()->routeIs('catalog');
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

                @include('partials.header-cart', ['hideTrackingLink' => true])
            </div>

            <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
        <div class="container mobile-menu__inner">
            <a href="{{ url('/') }}">Головна</a>
            <a href="{{ route('catalog') }}">Каталог</a>
            <a href="{{ url('/') }}#about">Про нас</a>
            <a href="{{ url('/') }}#faq">FAQ</a>
            @auth
                @if (auth()->user()?->is_admin)
                    <a href="{{ url('/admin') }}">Адмінка</a>
                @endif
            @endauth

            <div class="mobile-menu__meta">
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

                <a class="mobile-menu__phone" href="{{ $phoneHref }}">{{ $phoneLabel }}</a>
            </div>
        </div>
    </div>
</header>
