<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Трейд-ін | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-inline-images.css') }}">
        <style>
            .tradein-shell {
                min-height: 100vh;
                background: linear-gradient(180deg, #f7f9fc 0%, #eef3f9 100%);
            }

            .tradein-page {
                width: min(calc(100% - 28px), 1240px);
                margin: 0 auto;
                padding: 28px 0 72px;
            }

            .tradein-hero {
                display: grid;
                gap: 16px;
                margin-bottom: 24px;
                padding: 34px;
                border: 1px solid #dde5ef;
                border-radius: 32px;
                background: linear-gradient(180deg, #ffffff, #f8fbff);
                box-shadow: 0 20px 40px rgba(24, 32, 42, 0.08);
            }

            .tradein-hero__eyebrow {
                color: #7a28dc;
                font-size: 13px;
                font-weight: 800;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .tradein-hero__title {
                margin: 0;
                color: #18202a;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(34px, 4vw, 60px);
                font-weight: 700;
                letter-spacing: -.05em;
                line-height: 1.02;
            }

            .tradein-hero__text {
                margin: 0;
                max-width: 760px;
                color: #5f6b79;
                font-size: 18px;
                font-weight: 700;
                line-height: 1.55;
            }

            .tradein-target {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: max-content;
                padding: 12px 16px;
                border: 1px solid #d9e2ee;
                border-radius: 16px;
                background: #fff;
                color: #4c596a;
                font-size: 14px;
                font-weight: 800;
                box-shadow: 0 10px 20px rgba(24, 32, 42, 0.05);
            }

            .tradein-target strong {
                color: #18202a;
            }

            .tradein-grid {
                display: grid;
                grid-template-columns: minmax(0, 1.25fr) minmax(320px, .75fr);
                gap: 24px;
                align-items: start;
            }

            [data-tradein-main] > .tradein-hero:not(.tradein-hero--live),
            [data-tradein-main] > .tradein-panel:not(.tradein-panel--live) {
                display: none;
            }

            .tradein-panel {
                display: grid;
                gap: 20px;
                padding: 40px 34px;
                border: 1px solid #dde5ef;
                border-radius: 32px;
                background: #fff;
                box-shadow: 0 18px 38px rgba(24, 32, 42, 0.06);
            }

            .tradein-panel--aside {
                position: sticky;
                top: 112px;
            }

            .tradein-panel__eyebrow {
                color: #7a28dc;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .tradein-panel__title {
                margin: 0;
                color: #18202a;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(28px, 3vw, 42px);
                font-weight: 700;
                letter-spacing: -.04em;
            }

            .tradein-panel__text {
                margin: 0;
                color: #5f6b79;
                font-size: 18px;
                font-weight: 700;
                line-height: 1.55;
            }

            .tradein-alert {
                display: grid;
                gap: 8px;
                padding: 16px 18px;
                border-radius: 20px;
                border: 1px solid #cfe3d2;
                background: linear-gradient(180deg, #f6fff7, #f0fbf2);
                color: #165b2d;
                box-shadow: 0 10px 22px rgba(22, 91, 45, 0.08);
            }

            .tradein-alert--error {
                border-color: #f2c7c7;
                background: linear-gradient(180deg, #fff8f8, #fff1f1);
                color: #9f1d1d;
                box-shadow: 0 10px 22px rgba(159, 29, 29, 0.08);
            }

            .tradein-alert__title {
                font-size: 16px;
                font-weight: 900;
            }

            .tradein-alert__text,
            .tradein-alert__list {
                margin: 0;
                font-size: 14px;
                font-weight: 700;
                line-height: 1.6;
            }

            .tradein-alert__list {
                display: grid;
                gap: 6px;
                padding-left: 18px;
            }

            .tradein-form {
                display: grid;
                gap: 18px;
            }

            .tradein-form__grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .tradein-form__field {
                display: grid;
                gap: 8px;
            }

            .tradein-form__field--full {
                grid-column: 1 / -1;
            }

            .tradein-form__label {
                color: #18202a;
                font-size: 14px;
                font-weight: 900;
            }

            .tradein-form__input,
            .tradein-form__textarea,
            .tradein-form__select,
            .tradein-form__files {
                width: 100%;
                min-height: 56px;
                padding: 0 18px;
                border: 1px solid #d6e0ec;
                border-radius: 18px;
                background: #fbfdff;
                color: #18202a;
                font-size: 16px;
                font-weight: 700;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
                transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
            }

            .tradein-form__textarea {
                min-height: 180px;
                padding: 16px 18px;
                resize: vertical;
            }

            .tradein-form__files {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            .tradein-upload {
                display: grid;
                gap: 12px;
                padding: 16px 18px;
                border: 1px solid #d6e0ec;
                border-radius: 22px;
                background: linear-gradient(180deg, #ffffff, #f8fbff);
                box-shadow: inset 0 1px 0 rgba(255,255,255,.85);
                transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
                cursor: pointer;
            }

            .tradein-upload:hover {
                border-color: #c6d3e3;
                background: linear-gradient(180deg, #ffffff, #f4f8ff);
            }

            .tradein-upload:focus-within {
                border-color: #a26bf2;
                box-shadow: 0 0 0 4px rgba(122, 40, 220, 0.12);
                background: #fff;
            }

            .tradein-upload__row {
                display: flex;
                align-items: center;
                gap: 14px;
                min-width: 0;
            }

            .tradein-upload__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 46px;
                width: 46px;
                height: 46px;
                border-radius: 16px;
                background: linear-gradient(180deg, #f7ecff, #efe4ff);
                color: #7a28dc;
                box-shadow: 0 10px 18px rgba(122, 40, 220, 0.12);
            }

            .tradein-upload__icon svg {
                width: 22px;
                height: 22px;
            }

            .tradein-upload__copy {
                display: grid;
                gap: 2px;
                min-width: 0;
                flex: 1 1 auto;
            }

            .tradein-upload__title {
                color: #18202a;
                font-size: 15px;
                font-weight: 900;
                line-height: 1.35;
            }

            .tradein-upload__meta {
                color: #64748b;
                font-size: 13px;
                font-weight: 700;
                line-height: 1.5;
            }

            .tradein-upload__badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 42px;
                padding: 0 16px;
                border-radius: 14px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                font-size: 14px;
                font-weight: 900;
                white-space: nowrap;
                box-shadow: 0 12px 20px rgba(105, 22, 203, 0.2);
            }

            .tradein-upload__files {
                display: none;
                gap: 6px;
                padding-top: 2px;
                color: #445164;
                font-size: 13px;
                font-weight: 700;
                line-height: 1.5;
            }

            .tradein-upload__files.is-visible {
                display: grid;
            }

            .tradein-form__input:focus,
            .tradein-form__textarea:focus,
            .tradein-form__select:focus,
            .tradein-form__files:focus {
                outline: none;
                border-color: #a26bf2;
                box-shadow: 0 0 0 4px rgba(122, 40, 220, 0.12);
                background: #fff;
            }

            .tradein-form__hint {
                margin: 0;
                color: #64748b;
                font-size: 13px;
                font-weight: 700;
                line-height: 1.6;
            }

            .tradein-selection {
                display: grid;
                gap: 12px;
                padding: 18px;
                border: 1px solid #dddff4;
                border-radius: 22px;
                background: linear-gradient(180deg, #fcfaff, #f7f2ff);
                box-shadow: 0 12px 24px rgba(122, 40, 220, 0.08);
            }

            .tradein-selection__title {
                color: #18202a;
                font-size: 15px;
                font-weight: 900;
            }

            .tradein-selection__list {
                display: grid;
                gap: 8px;
                margin: 0;
                padding-left: 18px;
                color: #4d5a6b;
                font-size: 14px;
                font-weight: 700;
                line-height: 1.55;
            }

            .tradein-selection__meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .tradein-selection__badge,
            .tradein-selection__link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 38px;
                padding: 0 14px;
                border-radius: 999px;
                border: 1px solid #d8def0;
                background: #fff;
                color: #18202a;
                font-size: 13px;
                font-weight: 800;
            }

            .tradein-selection__link {
                text-decoration: none;
            }

            .tradein-form__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 14px;
                align-items: center;
            }

            .tradein-form__submit {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 240px;
                min-height: 56px;
                padding: 0 26px;
                border: 0;
                border-radius: 18px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                font-size: 17px;
                font-weight: 900;
                cursor: pointer;
                box-shadow: 0 16px 28px rgba(105, 22, 203, 0.22);
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .tradein-form__submit:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 32px rgba(105, 22, 203, 0.26);
            }

            .tradein-checklist {
                display: grid;
                gap: 12px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .tradein-checklist li {
                display: grid;
                grid-template-columns: 32px minmax(0, 1fr);
                gap: 12px;
                align-items: start;
                color: #18202a;
                font-size: 15px;
                font-weight: 800;
                line-height: 1.55;
            }

            .tradein-checklist span {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 12px;
                background: linear-gradient(180deg, #f8edff, #f1e7ff);
                color: #7a28dc;
                font-size: 14px;
                font-weight: 900;
            }

            .tradein-panel__back {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: max-content;
                min-height: 54px;
                padding: 0 24px;
                border: 1px solid #d6deea;
                border-radius: 16px;
                background: linear-gradient(180deg, #ffffff, #f6f9ff);
                color: #18202a;
                font-size: 16px;
                font-weight: 800;
                box-shadow: 0 12px 22px rgba(24, 32, 42, 0.06);
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .tradein-panel__back:hover {
                transform: translateY(-1px);
                box-shadow: 0 14px 24px rgba(24, 32, 42, 0.08);
            }

            @media (max-width: 980px) {
                .tradein-grid {
                    grid-template-columns: 1fr;
                }

                .tradein-panel--aside {
                    position: static;
                }
            }

            @media (max-width: 760px) {
                .tradein-page {
                    padding: 16px 0 48px;
                }

                .tradein-hero,
                .tradein-panel {
                    padding: 22px 18px;
                    border-radius: 24px;
                }

                .tradein-hero__text,
                .tradein-panel__text {
                    font-size: 16px;
                }

                .tradein-form__grid {
                    grid-template-columns: 1fr;
                }

                .tradein-upload__row {
                    flex-wrap: wrap;
                    align-items: flex-start;
                }

                .tradein-form__actions,
                .tradein-selection__meta {
                    display: grid;
                    grid-template-columns: 1fr;
                }

                .tradein-upload__badge {
                    width: 100%;
                }

                .tradein-selection__badge,
                .tradein-selection__link {
                    width: 100%;
                }

                .tradein-target,
                .tradein-panel__back,
                .tradein-form__submit {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        @php
            $storefrontBuilds = $storefrontBuilds ?? \App\Support\StorefrontBuilds::all();
            $headerBuilds = $headerBuilds ?? array_slice($storefrontBuilds, 0, 4);
            $selectedBuild = $selectedBuild ?? (request()->query('build')
                ? \App\Support\StorefrontBuilds::findBySlug((string) request()->query('build'))
                : null);
            $selectedBuildSlug = $selectedBuildSlug ?? (string) old('build_slug', $selectedBuild['slug'] ?? '');
            $selectedSharedBuildToken = $selectedSharedBuildToken ?? (string) old('shared_build_token', request()->query('shared_build', ''));
            $tradeInBuildSnapshotPreview = is_array($tradeInBuildSnapshotPreview ?? null) ? $tradeInBuildSnapshotPreview : [];
            $tradeInSuccess = $tradeInSuccess ?? session('tradeInSuccess');
        @endphp

        <div class="tradein-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="{{ url('/') }}#about">Про нас</a>
                        <a href="#contacts">Контакти</a>
                        <a href="{{ url('/') }}#faq">FAQ</a>
                    </div>
                    <div class="topbar__meta">
                        <div class="topbar__contacts">
                            <a href="tel:+380633631066">+380633631066</a>
                        </div>

                        <div class="topbar__socials" aria-label="Соціальні мережі">
                            <a class="topbar__social-link" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>
                                </svg>
                            </a>

                            <a class="topbar__social-link" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 4L3 11.2L10.2 13.8L12.8 21L21 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M10.2 13.8L14.2 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <header class="header">
                <div class="container header__inner">
                    <a class="brand" href="{{ url('/') }}">
                        <div>
                            <div class="brand__name">KondorPC</div>
                            <span class="brand__sub">Твоя база геймінгу</span>
                        </div>
                    </a>

                    <div class="header__actions">
                        <a class="header-button header-button--primary" href="{{ route('catalog') }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Наші збірки
                        </a>

                        <button class="header-button" type="button" data-dropdown-trigger="builds" aria-expanded="false" aria-controls="builds-dropdown" aria-haspopup="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                            Каталог збірок
                        </button>

                        <button class="header-button" type="button" data-dropdown-trigger="consultation" aria-expanded="false" aria-controls="consultation-dropdown" aria-haspopup="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 10V12L13.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Консультація
                        </button>

                        @auth
                            @if (auth()->user()?->is_admin)
                                <a class="header-button" href="{{ url('/admin') }}">Адмінка</a>
                            @endif
                        @endauth

                        @include('partials.header-cart')
                        <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu"><span></span><span></span><span></span></button>
                    </div>
                </div>

                <div class="dropdown" id="builds-dropdown" data-dropdown-panel="builds">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <h3>Популярні збірки</h3>
                            @foreach ($headerBuilds as $menuBuild)
                                <a href="{{ route('product.show', ['slug' => $menuBuild['slug']]) }}">{{ $menuBuild['name'] }}</a>
                            @endforeach
                        </div>

                        <div class="dropdown__group">
                            <h3>Швидкі переходи</h3>
                            <a href="{{ route('catalog') }}">Всі збірки</a>
                            <a href="{{ url('/') }}">Головна</a>
                            <a href="{{ url('/') }}#gallery">Наші роботи</a>
                            <a href="{{ url('/') }}#faq">FAQ</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Під замовлення</h3>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Підбір під бюджет</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Апгрейд конфігурації</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Збірка для стріму</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
                        </div>
                    </div>
                </div>

                <div class="dropdown dropdown--consultation" id="consultation-dropdown" data-dropdown-panel="consultation">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Telegram</a>
                            <a href="#contacts">Контактна форма</a>
                            <a href="tel:+380633631066">+380 63 363 10 66</a>
                            <a href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer">Instagram</a>
                        </div>
                    </div>
                </div>

                <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
                    <div class="container mobile-menu__inner">
                        <a href="{{ url('/') }}">Головна</a>
                        <a href="{{ route('catalog') }}">Каталог збірок</a>
                        <a href="{{ url('/') }}#about">Про нас</a>
                        <a href="{{ route('trade-in') }}">Трейд-ін</a>
                        <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
                        <a href="#contacts">Контакти</a>
                        @auth
                            @if (auth()->user()?->is_admin)
                                <a href="{{ url('/admin') }}">Адмінка</a>
                            @endif
                        @endauth
                        <a href="{{ url('/') }}#faq">FAQ</a>
                    </div>
                </div>
            </header>

            <main class="tradein-page" data-tradein-main>
                <section class="tradein-hero tradein-hero--live">
                    <span class="tradein-hero__eyebrow">Kondor Trade-in</span>
                    <h1 class="tradein-hero__title">Обмін старого ПК на нову збірку</h1>
                    <p class="tradein-hero__text">Окремо винесли трейд-ін у зручний сценарій: ти залишаєш опис і фото, а ми оцінюємо конфігурацію, стан комплектуючих, орієнтовну вартість і доплату під потрібну збірку.</p>

                    @if ($selectedBuild)
                        <div class="tradein-target">
                            <span>Цільова збірка:</span>
                            <strong>{{ $selectedBuild['name'] }}</strong>
                        </div>
                    @endif
                </section>

                <div class="tradein-grid">
                    <section class="tradein-panel tradein-panel--live">
                        <span class="tradein-panel__eyebrow">Trade-in Form</span>
                        <h2 class="tradein-panel__title">Опиши свій ПК і прикріпи фото</h2>
                        <p class="tradein-panel__text">Ми дивимось конфігурацію, стан корпусу, комплектність і вже після цього повертаємо попередню оцінку та варіант доплати під нову збірку.</p>

                        @if ($tradeInSuccess)
                            <div class="tradein-alert">
                                <strong class="tradein-alert__title">Заявку отримано</strong>
                                <p class="tradein-alert__text">{{ $tradeInSuccess['message'] ?? 'Ми вже отримали вашу заявку на трейд-ін.' }}</p>
                                @if (filled($tradeInSuccess['request_id'] ?? null))
                                    <p class="tradein-alert__text">Номер заявки: #{{ $tradeInSuccess['request_id'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="tradein-alert tradein-alert--error">
                                <strong class="tradein-alert__title">Перевір форму</strong>
                                <ul class="tradein-alert__list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="tradein-form" method="POST" action="{{ route('trade-in.submit') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="shared_build_token" value="{{ $selectedSharedBuildToken }}">

                            <div class="tradein-form__grid">
                                <div class="tradein-form__field tradein-form__field--full">
                                    <span class="tradein-form__label">Цільова збірка</span>
                                    <select class="tradein-form__select" name="build_slug">
                                        <option value="">Без прив’язки до конкретної збірки</option>
                                        @foreach ($storefrontBuilds as $buildOption)
                                            <option value="{{ $buildOption['slug'] }}" @selected($selectedBuildSlug === $buildOption['slug'])>{{ $buildOption['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <p class="tradein-form__hint">Якщо ти зайшов зі сторінки збірки, вона вже обрана автоматично.</p>
                                </div>

                                @if (($tradeInBuildSnapshotPreview['summary'] ?? []) !== [] || filled($tradeInBuildSnapshotPreview['shared_url'] ?? null))
                                    <div class="tradein-form__field tradein-form__field--full">
                                        <div class="tradein-selection">
                                            <span class="tradein-selection__title">У заявку піде саме ця конфігурація збірки</span>
                                            @if (($tradeInBuildSnapshotPreview['summary'] ?? []) !== [])
                                                <ul class="tradein-selection__list">
                                                    @foreach (($tradeInBuildSnapshotPreview['summary'] ?? []) as $summaryLine)
                                                        <li>{{ $summaryLine }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <div class="tradein-selection__meta">
                                                @if (filled($tradeInBuildSnapshotPreview['additional_price'] ?? null))
                                                    <span class="tradein-selection__badge">Додаткові опції: +{{ number_format((int) ($tradeInBuildSnapshotPreview['additional_price'] ?? 0), 0, '.', ' ') }} грн</span>
                                                @endif
                                                @if (filled($tradeInBuildSnapshotPreview['total_price'] ?? null))
                                                    <span class="tradein-selection__badge">Разом: {{ number_format((int) ($tradeInBuildSnapshotPreview['total_price'] ?? 0), 0, '.', ' ') }} грн</span>
                                                @endif
                                                @if (filled($tradeInBuildSnapshotPreview['shared_url'] ?? null))
                                                    <a class="tradein-selection__link" href="{{ $tradeInBuildSnapshotPreview['shared_url'] }}" target="_blank" rel="noreferrer">Відкрити точну конфігурацію</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <label class="tradein-form__field">
                                    <span class="tradein-form__label">Ім’я</span>
                                    <input class="tradein-form__input" type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Як до тебе звертатись" required>
                                </label>

                                <label class="tradein-form__field">
                                    <span class="tradein-form__label">Телефон</span>
                                    <input class="tradein-form__input" type="text" name="phone" value="{{ old('phone') }}" placeholder="+380..." required>
                                </label>

                                <div class="tradein-form__field tradein-form__field--full">
                                    <span class="tradein-form__label">Telegram / Viber</span>
                                    <input class="tradein-form__input" type="text" name="messenger_contact" value="{{ old('messenger_contact') }}" placeholder="@nickname або номер для переписки">
                                </div>

                                <div class="tradein-form__field tradein-form__field--full">
                                    <span class="tradein-form__label">Опис твого ПК</span>
                                    <textarea class="tradein-form__textarea" name="description" placeholder="Напиши конфігурацію, стан, що мінялося, які є дефекти, чи є коробки, гарантії та що саме хочеш обміняти." required>{{ old('description') }}</textarea>
                                    <p class="tradein-form__hint">Чим точніше опис, тим швидше ми дамо попередню оцінку.</p>
                                </div>

                                <label class="tradein-form__field tradein-form__field--full">
                                    <span class="tradein-form__label">Фото ПК</span>
                                    <label class="tradein-upload">
                                        <input class="tradein-form__files" type="file" name="photos[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple data-tradein-files-input>
                                        <span class="tradein-upload__row">
                                            <span class="tradein-upload__icon" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 16V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8.5 11.5L12 8L15.5 11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M5 16.5V17C5 18.1046 5.89543 19 7 19H17C18.1046 19 19 18.1046 19 17V16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <rect x="4" y="3" width="16" height="18" rx="4" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </span>
                                            <span class="tradein-upload__copy">
                                                <span class="tradein-upload__title" data-tradein-files-title>Додати фото ПК</span>
                                                <span class="tradein-upload__meta" data-tradein-files-meta>Натисни, щоб вибрати JPG, PNG або WEBP</span>
                                            </span>
                                            <span class="tradein-upload__badge">Вибрати файли</span>
                                        </span>
                                        <span class="tradein-upload__files" data-tradein-files-list></span>
                                    </label>
                                    <p class="tradein-form__hint">До 6 фото, кожне до 8MB. Приймаємо лише JPG, PNG або WEBP. На сервері перевіряємо реальний тип файлу й зберігаємо тільки очищену копію, а не оригінал напряму.</p>
                                </label>
                            </div>

                            <div class="tradein-form__actions">
                                <button class="tradein-form__submit" type="submit">Надіслати заявку на трейд-ін</button>

                                @if ($selectedBuild)
                                    <a class="tradein-panel__back" href="{{ route('product.show', ['slug' => $selectedBuild['slug']]) }}">Повернутися до {{ $selectedBuild['name'] }}</a>
                                @endif
                            </div>
                        </form>
                    </section>

                    <aside class="tradein-panel tradein-panel--live tradein-panel--aside">
                        <span class="tradein-panel__eyebrow">Як це працює</span>
                        <h2 class="tradein-panel__title">Що краще додати одразу</h2>
                        <p class="tradein-panel__text">Щоб не тягнути з оцінкою, краще з першого повідомлення дати максимум корисної інформації.</p>

                        <ul class="tradein-checklist">
                            <li><span>1</span><div>Фото корпусу спереду, збоку, ззаду та бажано всередині.</div></li>
                            <li><span>2</span><div>Точну конфігурацію: CPU, GPU, RAM, накопичувачі, блок живлення.</div></li>
                            <li><span>3</span><div>Стан системи: шум, перегрів, ремонти, дефекти, подряпини або відсутні елементи.</div></li>
                            <li><span>4</span><div>Що саме хочеш отримати взамін: конкретну збірку або орієнтир по бюджету.</div></li>
                        </ul>

                        <p class="tradein-form__hint">Ми свідомо не приймаємо SVG, архіви або нестандартні вкладення. Це не повноцінний антивірус на рівні інфраструктури, але серверна перевірка типу файлу та повторне збереження картинки суттєво знижують ризик шкідливих вкладень.</p>
                    </aside>
                </div>
                <section class="tradein-hero">
                    <span class="tradein-hero__eyebrow">Kondor Trade-in</span>
                    <h1 class="tradein-hero__title">Обмін старого ПК на нову збірку</h1>
                    <p class="tradein-hero__text">Окремо винесемо трейд-ін у зручний сценарій: оцінка, перевірка комплектуючих, доплата і підбір нової конфігурації під твій бюджет.</p>

                    @if ($selectedBuild)
                        <div class="tradein-target">
                            <span>Цільова збірка:</span>
                            <strong>{{ $selectedBuild['name'] }}</strong>
                        </div>
                    @endif
                </section>

                <section class="tradein-panel">
                    <h2 class="tradein-panel__title">Трейд-ін сторінка</h2>
                    <p class="tradein-panel__text">Тут буде можливість здати пк по трейд іну.</p>

                    @if ($selectedBuild)
                        <a class="tradein-panel__back" href="{{ route('product.show', ['slug' => $selectedBuild['slug']]) }}">Повернутися до {{ $selectedBuild['name'] }}</a>
                    @endif
                </section>
            </main>

            <footer class="footer" id="contacts">
                <div class="container">
                    <div class="footer__grid">
                        <div class="footer__brand">
                            <div class="footer__logo">
                                <span class="footer__brand-name">KondorPC</span>
                                <span class="footer__brand-sub">Твоя база геймінгу</span>
                            </div>
                            <div class="footer__contacts">
                                <a href="tel:+380633631066">+380 63 363 10 66</a>
                                <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">@kondor_channeI</a>
                            </div>
                            <div class="footer__socials">
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/></svg></a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="Подзвонити"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                            </div>
                        </div>
                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">Про нас</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}#about">Що таке KondorPC</a>
                                <a href="#contacts">Контакти</a>
                                <a href="#contacts">Доставка</a>
                                <a href="#contacts">Оплата</a>
                                <a href="#contacts">Повернення та обмін</a>
                            </nav>
                        </div>
                        <div class="footer__column">
                            <h2 class="footer__title">Основне</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}">Головна</a>
                                <a href="{{ route('catalog') }}">Каталог</a>
                                <a href="{{ route('trade-in') }}">Трейд-ін</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        @include('partials.admin-site-notifications')
        @include('partials.admin-inline-images')
        <script src="{{ asset('js/storefront-cart.js') }}"></script>
        <script>
            (() => {
                const header = document.querySelector('.header');
                const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
                const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                const tradeInFilesInput = document.querySelector('[data-tradein-files-input]');
                const tradeInFilesTitle = document.querySelector('[data-tradein-files-title]');
                const tradeInFilesMeta = document.querySelector('[data-tradein-files-meta]');
                const tradeInFilesList = document.querySelector('[data-tradein-files-list]');
                let closeTimer;

                const syncHeaderState = () => {
                    if (!header) {
                        return;
                    }

                    header.classList.toggle('is-stuck', window.scrollY > 10);
                };

                const clearCloseTimer = () => {
                    if (closeTimer) {
                        window.clearTimeout(closeTimer);
                        closeTimer = undefined;
                    }
                };

                const closeAllDropdowns = () => {
                    triggers.forEach((trigger) => {
                        trigger.classList.remove('is-open');
                        trigger.setAttribute('aria-expanded', 'false');
                    });

                    panels.forEach((panel) => {
                        panel.classList.remove('is-open');
                    });
                };

                const positionConsultationPanel = () => {
                    const trigger = document.querySelector('[data-dropdown-trigger="consultation"]');
                    const panel = document.querySelector('[data-dropdown-panel="consultation"]');

                    if (!header || !trigger || !panel || window.innerWidth <= 760) {
                        if (panel) {
                            panel.style.left = '';
                        }

                        return;
                    }

                    const headerRect = header.getBoundingClientRect();
                    const triggerRect = trigger.getBoundingClientRect();
                    const panelWidth = panel.offsetWidth || 230;
                    const idealLeft = triggerRect.left - headerRect.left + ((triggerRect.width - panelWidth) / 2);
                    const maxLeft = headerRect.width - panelWidth - 12;
                    const nextLeft = Math.max(12, Math.min(idealLeft, maxLeft));

                    panel.style.left = `${nextLeft}px`;
                };

                const openDropdown = (name) => {
                    const nextPanel = document.querySelector(`[data-dropdown-panel="${name}"]`);
                    const nextTrigger = document.querySelector(`[data-dropdown-trigger="${name}"]`);

                    if (!nextPanel || !nextTrigger) {
                        return;
                    }

                    closeAllDropdowns();
                    nextTrigger.classList.add('is-open');
                    nextTrigger.setAttribute('aria-expanded', 'true');
                    nextPanel.classList.add('is-open');

                    if (name === 'consultation') {
                        positionConsultationPanel();
                    }
                };

                const scheduleClose = () => {
                    clearCloseTimer();
                    closeTimer = window.setTimeout(() => {
                        closeAllDropdowns();
                    }, 120);
                };

                const closeMobileMenu = () => {
                    if (!mobileToggle || !mobileMenu) {
                        return;
                    }

                    mobileToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.remove('is-open');
                };

                triggers.forEach((trigger) => {
                    const name = trigger.dataset.dropdownTrigger;
                    const panel = document.querySelector(`[data-dropdown-panel="${name}"]`);

                    if (!name || !panel) {
                        return;
                    }

                    trigger.addEventListener('mouseenter', () => {
                        clearCloseTimer();
                        openDropdown(name);
                    });

                    trigger.addEventListener('mouseleave', scheduleClose);
                    trigger.addEventListener('focus', () => openDropdown(name));
                    trigger.addEventListener('click', () => {
                        const isOpen = panel.classList.contains('is-open');

                        if (isOpen) {
                            closeAllDropdowns();
                            return;
                        }

                        openDropdown(name);
                    });

                    panel.addEventListener('mouseenter', clearCloseTimer);
                    panel.addEventListener('mouseleave', scheduleClose);
                });

                mobileToggle?.addEventListener('click', () => {
                    const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';

                    mobileToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    mobileMenu?.classList.toggle('is-open', !isExpanded);
                    closeAllDropdowns();
                });

                mobileMenu?.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        closeMobileMenu();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
                        closeAllDropdowns();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') {
                        return;
                    }

                    closeAllDropdowns();
                    closeMobileMenu();
                });

                window.addEventListener('scroll', syncHeaderState, { passive: true });
                window.addEventListener('resize', () => {
                    syncHeaderState();
                    positionConsultationPanel();

                    if (window.innerWidth > 1080) {
                        closeMobileMenu();
                    }
                });

                syncHeaderState();
                positionConsultationPanel();

                if (tradeInFilesInput && tradeInFilesTitle && tradeInFilesMeta && tradeInFilesList) {
                    const syncTradeInFiles = () => {
                        const files = Array.from(tradeInFilesInput.files ?? []);

                        if (files.length === 0) {
                            tradeInFilesTitle.textContent = 'Додати фото ПК';
                            tradeInFilesMeta.textContent = 'Натисни, щоб вибрати JPG, PNG або WEBP';
                            tradeInFilesList.textContent = '';
                            tradeInFilesList.classList.remove('is-visible');
                            return;
                        }

                        tradeInFilesTitle.textContent = files.length === 1
                            ? 'Вибрано 1 фото'
                            : `Вибрано ${files.length} фото`;
                        tradeInFilesMeta.textContent = files.map((file) => file.name).join(' • ');
                        tradeInFilesList.textContent = files.map((file) => file.name).join(', ');
                        tradeInFilesList.classList.add('is-visible');
                    };

                    tradeInFilesInput.addEventListener('change', syncTradeInFiles);
                    syncTradeInFiles();
                }

                if (window.KondorCart) {
                    window.KondorCart.renderPreviews();
                }
            })();
        </script>
    </body>
</html>
