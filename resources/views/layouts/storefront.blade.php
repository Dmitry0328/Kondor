<!DOCTYPE html>
<html lang="uk" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $store['brand']['name'].' PC')</title>
        <meta name="description" content="@yield('description', $store['hero']['subtitle'])">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:400,500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('storefront.css') }}">
        <script defer src="{{ asset('storefront.js') }}"></script>
    </head>
    <body>
        @php
            $categoryGroups = $categories->groupBy('section');
        @endphp

        <div class="site-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__copy">
                        <span class="topbar__pill">Responsive storefront</span>
                        <span>Преміальні збірки, кастомні конфіги та швидка консультація.</span>
                    </div>

                    <div class="topbar__links">
                        <a href="tel:{{ $store['brand']['phone'] }}">{{ $store['brand']['phone_display'] }}</a>
                        <a href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Telegram</a>
                        <a href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Instagram</a>
                    </div>
                </div>
            </div>

            <header class="site-header">
                <div class="container site-header__inner">
                    <a class="brand" href="{{ route('store.home') }}">
                        <span class="brand__mark">K</span>
                        <span>
                            <strong>{{ $store['brand']['name'] }}</strong>
                            <small>{{ $store['brand']['tagline'] }}</small>
                        </span>
                    </a>

                    <nav class="desktop-nav" aria-label="Основна навігація">
                        <a class="{{ request()->routeIs('store.home') ? 'is-active' : '' }}" href="{{ route('store.home') }}">Головна</a>
                        <a class="{{ request()->routeIs('store.catalog') ? 'is-active' : '' }}" href="{{ route('store.catalog') }}">Каталог</a>

                        <details class="mega-menu">
                            <summary>Категорії</summary>
                            <div class="mega-menu__panel">
                                @foreach ($categoryGroups as $section => $group)
                                    <div class="mega-menu__group">
                                        <h3>{{ $section }}</h3>
                                        @foreach ($group as $category)
                                            <a href="{{ route('store.catalog') }}">
                                                <strong>{{ $category['name'] }}</strong>
                                                <span>{{ $category['description'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </details>

                        <a class="{{ request()->routeIs('store.contacts') ? 'is-active' : '' }}" href="{{ route('store.contacts') }}">Контакти</a>
                    </nav>

                    <div class="header-actions">
                        <button class="theme-toggle" type="button" data-theme-toggle aria-label="Перемкнути тему">
                            <span class="theme-toggle__icons" aria-hidden="true">
                                <span></span>
                                <span></span>
                            </span>
                            <span data-theme-label>Темна</span>
                        </button>

                        <a class="button button--ghost button--small desktop-only" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">
                            Консультація
                        </a>

                        <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </header>

            <div class="mobile-menu" id="mobile-menu" data-menu>
                <div class="mobile-menu__panel">
                    <a href="{{ route('store.home') }}">Головна</a>
                    <a href="{{ route('store.catalog') }}">Каталог</a>
                    <a href="{{ route('store.contacts') }}">Контакти</a>

                    @foreach ($categoryGroups as $section => $group)
                        <div class="mobile-menu__group">
                            <strong>{{ $section }}</strong>
                            @foreach ($group as $category)
                                <a href="{{ route('store.catalog') }}">{{ $category['name'] }}</a>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="mobile-menu__cta">
                        <a class="button button--primary button--block" href="tel:{{ $store['brand']['phone'] }}">Зателефонувати</a>
                        <a class="button button--ghost button--block" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Написати в Telegram</a>
                    </div>
                </div>
            </div>

            <main>
                @yield('content')
            </main>

            <footer class="site-footer">
                <div class="container site-footer__grid">
                    <div>
                        <a class="brand brand--footer" href="{{ route('store.home') }}">
                            <span class="brand__mark">K</span>
                            <span>
                                <strong>{{ $store['brand']['name'] }}</strong>
                                <small>{{ $store['brand']['tagline'] }}</small>
                            </span>
                        </a>
                        <p class="site-footer__copy">
                            Шаблон storefront для майбутнього магазину з адмінкою, каталогом, сторінкою товару та світлою/темною темою.
                        </p>
                    </div>

                    <div>
                        <h3>Навігація</h3>
                        <div class="footer-links">
                            <a href="{{ route('store.home') }}">Головна</a>
                            <a href="{{ route('store.catalog') }}">Каталог</a>
                            <a href="{{ route('store.contacts') }}">Контакти</a>
                        </div>
                    </div>

                    <div>
                        <h3>Зв’язок</h3>
                        <div class="footer-links">
                            <a href="tel:{{ $store['brand']['phone'] }}">{{ $store['brand']['phone_display'] }}</a>
                            <a href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Telegram</a>
                            <a href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Instagram</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
