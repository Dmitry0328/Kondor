<!DOCTYPE html>
<html lang="uk" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $store['brand']['name'].' PC')</title>
        <meta name="description" content="@yield('description', $store['hero']['subtitle'])">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('storefront.css') }}">
        <script defer src="{{ asset('storefront.js') }}"></script>
    </head>
    <body>
        <div class="site-shell">
            <div class="utility-bar">
                <div class="container utility-bar__inner">
                    <div class="utility-bar__message">
                        Продаж ігрових ПК та збірок під замовлення
                    </div>

                    <div class="utility-bar__links">
                        <a href="tel:{{ $store['brand']['phone'] }}">{{ $store['brand']['phone_display'] }}</a>
                        <a href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Telegram</a>
                        <a href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Instagram</a>
                    </div>
                </div>
            </div>

            <header class="site-header">
                <div class="container site-header__inner">
                    <a class="brand" href="{{ route('store.home') }}">
                        <span class="brand__logo">K</span>
                        <span class="brand__text">
                            <strong>{{ $store['brand']['name'] }}</strong>
                            <small>Готові збірки ПК</small>
                        </span>
                    </a>

                    <nav class="desktop-nav" aria-label="Основна навігація">
                        <a class="{{ request()->routeIs('store.home') ? 'is-active' : '' }}" href="{{ route('store.home') }}">Головна</a>
                        <a class="{{ request()->routeIs('store.catalog') ? 'is-active' : '' }}" href="{{ route('store.catalog') }}">Наші збірки</a>
                        <a class="{{ request()->routeIs('store.contacts') ? 'is-active' : '' }}" href="{{ route('store.contacts') }}">Контакти</a>
                    </nav>

                    <div class="header-actions">
                        <button class="theme-toggle" type="button" data-theme-toggle aria-label="Перемкнути тему">
                            <span class="theme-toggle__icons" aria-hidden="true">
                                <span></span>
                                <span></span>
                            </span>
                            <span data-theme-label>Світла</span>
                        </button>

                        <a class="button button--primary desktop-only" href="{{ route('store.catalog') }}">Перейти до збірок</a>

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
                    <a href="{{ route('store.catalog') }}">Наші збірки</a>
                    <a href="{{ route('store.contacts') }}">Контакти</a>

                    <div class="mobile-menu__cta">
                        <a class="button button--primary button--block" href="{{ route('store.catalog') }}">Відкрити каталог</a>
                        <a class="button button--secondary button--block" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Написати в Telegram</a>
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
                            <span class="brand__logo">K</span>
                            <span class="brand__text">
                                <strong>{{ $store['brand']['name'] }}</strong>
                                <small>Інтернет-магазин збірок ПК</small>
                            </span>
                        </a>
                        <p class="site-footer__copy">
                            Шаблон магазину під готові комп’ютерні збірки. Наступним етапом можна підключити базу товарів, замовлення й адмінку.
                        </p>
                    </div>

                    <div>
                        <h3>Сторінки</h3>
                        <div class="footer-links">
                            <a href="{{ route('store.home') }}">Головна</a>
                            <a href="{{ route('store.catalog') }}">Наші збірки</a>
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
