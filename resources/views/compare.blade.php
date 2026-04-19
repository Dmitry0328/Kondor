<!DOCTYPE html>
<html lang="uk">
    <head>
        @php
            $compareTitle = count($selectedBuilds) > 0
                ? 'Порівняння збірок | KondorPC'
                : 'Порівняння збірок порожнє | KondorPC';
            $compareDescription = count($selectedBuilds) > 0
                ? 'Порівняй до трьох збірок KondorPC за основними характеристиками, FPS та ціною.'
                : 'Додай до трьох збірок KondorPC у порівняння, щоб побачити характеристики поруч.';
            $compareImage = collect($selectedBuilds)
                ->pluck('image_url')
                ->filter(static fn ($url) => is_string($url) && trim($url) !== '')
                ->first() ?: asset('images/kondor-mark-black.svg');
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $compareTitle }}</title>
        @include('partials.seo', [
            'title' => $compareTitle,
            'description' => $compareDescription,
            'canonical' => route('catalog.compare', ['items' => implode(',', $selectedCompareSlugs)]),
            'image' => $compareImage,
            'type' => 'website',
        ])
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}?v={{ filemtime(public_path('css/storefront-cart.css')) }}">
        <link rel="stylesheet" href="{{ asset('css/admin-inline-images.css') }}">
        @include('partials.theme-head')
        <style>
            :root {
                --bg: #ffffff;
                --surface: #ffffff;
                --surface-soft: #f7f9fd;
                --panel: #ffffff;
                --text: #18202a;
                --muted: #667487;
                --line: #dce4ef;
                --line-strong: #cfd8e6;
                --primary: #6f10c9;
                --primary-strong: #8424f0;
                --danger: #b42318;
                --shadow: 0 18px 44px rgba(24, 32, 42, 0.08);
                --container: min(calc(100% - 28px), 1920px);
                --content: min(calc(100% - 28px), 1440px);
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                min-width: 320px;
                font-family: 'Manrope', sans-serif;
                color: var(--text);
                background: linear-gradient(180deg, #f7f9fc 0%, #eef3f9 100%);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            button {
                font: inherit;
            }

            .page-shell {
                min-height: 100vh;
                background: var(--bg);
            }

            .container {
                width: var(--container);
                margin: 0 auto;
            }

            .compare-wrap {
                width: var(--content);
                margin: 0 auto;
            }

            .topbar {
                background: #2b272b;
                color: #ffffff;
                font-size: 14px;
            }

            .topbar__inner,
            .topbar__links,
            .topbar__meta,
            .topbar__contacts,
            .topbar__socials {
                display: flex;
                align-items: center;
            }

            .topbar__inner {
                justify-content: space-between;
                min-height: 38px;
                gap: 22px;
            }

            .topbar__links {
                gap: 26px;
            }

            .topbar__meta {
                margin-left: auto;
                gap: 30px;
            }

            .topbar__contacts {
                gap: 20px;
            }

            .topbar__socials {
                gap: 16px;
            }

            .topbar a {
                font-weight: 700;
                opacity: 0.96;
                line-height: 1;
            }

            .topbar__social-link {
                display: inline-flex;
                justify-content: center;
                width: 18px;
                height: 18px;
            }

            .page {
                padding: 28px 0 72px;
            }

            .compare-breadcrumbs {
                display: inline-flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 18px;
                color: var(--muted);
                font-size: 13px;
                font-weight: 800;
            }

            .compare-breadcrumbs a:hover {
                color: var(--primary);
            }

            .compare-hero {
                display: grid;
                gap: 16px;
                margin-bottom: 22px;
                padding: 30px;
                border: 1px solid var(--line);
                border-radius: 30px;
                background:
                    radial-gradient(circle at top right, rgba(95, 208, 255, 0.14), transparent 26%),
                    radial-gradient(circle at left center, rgba(142, 81, 255, 0.12), transparent 30%),
                    linear-gradient(180deg, #ffffff, #f9fbff);
                box-shadow: var(--shadow);
            }

            .compare-hero__eyebrow {
                color: #7a4bff;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .compare-hero__title {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(34px, 4vw, 56px);
                line-height: 0.96;
                letter-spacing: -0.05em;
            }

            .compare-hero__text {
                margin: 0;
                max-width: 820px;
                color: var(--muted);
                font-size: 18px;
                line-height: 1.6;
            }

            .compare-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 18px;
            }

            .compare-toolbar__meta {
                display: grid;
                gap: 4px;
            }

            .compare-toolbar__meta strong {
                color: var(--text);
                font-size: 22px;
                font-weight: 900;
            }

            .compare-toolbar__meta span {
                color: var(--muted);
                font-size: 14px;
                font-weight: 700;
            }

            .compare-toolbar__actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .compare-toolbar__button,
            .compare-cell__button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 48px;
                padding: 0 18px;
                border: 1px solid var(--line-strong);
                border-radius: 16px;
                background: #ffffff;
                color: var(--text);
                font-size: 14px;
                font-weight: 800;
                box-shadow: 0 10px 18px rgba(24, 32, 42, 0.06);
                cursor: pointer;
                transition: transform 0.18s ease, border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease, color 0.18s ease;
            }

            .compare-toolbar__button:hover,
            .compare-cell__button:hover {
                transform: translateY(-1px);
                border-color: #c6d3e4;
                background: #fbfcfe;
                box-shadow: 0 14px 24px rgba(24, 32, 42, 0.08);
            }

            .compare-toolbar__button--primary,
            .compare-cell__button--primary {
                border-color: #6f10c9;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
                box-shadow: 0 14px 28px rgba(104, 22, 203, 0.24);
            }

            .compare-toolbar__button--primary:hover,
            .compare-cell__button--primary:hover {
                border-color: #6517c9;
                background: linear-gradient(180deg, #8f2fff, #7420d3);
            }

            .compare-cell__button--danger {
                border-color: #ecd2d2;
                color: var(--danger);
            }

            .compare-empty {
                display: grid;
                gap: 16px;
                justify-items: start;
                padding: 28px;
                border: 1px solid var(--line);
                border-radius: 28px;
                background: linear-gradient(180deg, #ffffff, #f7f9fd);
                box-shadow: var(--shadow);
            }

            .compare-empty__title {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 32px;
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .compare-empty__text {
                margin: 0;
                max-width: 640px;
                color: var(--muted);
                font-size: 16px;
                font-weight: 700;
                line-height: 1.6;
            }

            .compare-table-shell {
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .compare-table {
                width: 100%;
                min-width: 1180px;
                border-collapse: separate;
                border-spacing: 0 12px;
            }

            .compare-table th {
                width: 220px;
                padding: 18px 18px 18px 0;
                color: #617084;
                font-size: 13px;
                font-weight: 900;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                text-align: left;
                vertical-align: top;
            }

            .compare-table td {
                padding: 0;
                vertical-align: top;
            }

            .compare-cell {
                display: grid;
                gap: 14px;
                min-height: 100%;
                padding: 20px;
                border: 1px solid var(--line);
                border-radius: 24px;
                background: linear-gradient(180deg, #ffffff, #f7f9fd);
                box-shadow: 0 16px 30px rgba(24, 32, 42, 0.08);
            }

            .compare-cell__media {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                min-height: 240px;
                max-height: 240px;
                padding: 18px;
                border: 1px solid #e4ebf4;
                border-radius: 22px;
                background: linear-gradient(180deg, #f8fbff, #edf3fb);
                overflow: hidden;
            }

            .compare-cell__media img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .compare-cell__meta {
                display: grid;
                gap: 8px;
            }

            .compare-cell__title {
                margin: 0;
                font-size: 24px;
                font-weight: 900;
                line-height: 1.05;
                letter-spacing: -0.03em;
            }

            .compare-cell__code {
                color: var(--muted);
                font-size: 13px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .compare-cell__price {
                color: var(--text);
                font-family: 'Space Grotesk', sans-serif;
                font-size: 32px;
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .compare-cell__actions {
                display: grid;
                gap: 10px;
            }

            .compare-cell__button {
                width: 100%;
            }

            .compare-cell__button.is-added {
                border-color: #178f57;
                background: linear-gradient(180deg, #2fbe75, #169659);
                color: #ffffff;
                box-shadow: 0 12px 24px rgba(21, 150, 88, 0.18);
            }

            .compare-cell__value {
                color: var(--text);
                font-size: 15px;
                font-weight: 800;
                line-height: 1.55;
            }

            .compare-cell__value--muted {
                color: var(--muted);
            }

            .footer {
                position: relative;
                margin-top: 72px;
                padding: 84px 0 0;
                background: radial-gradient(circle at 12% 22%, rgba(132, 36, 240, 0.08), transparent 22%), radial-gradient(circle at 86% 78%, rgba(48, 215, 255, 0.06), transparent 24%), #fff;
                border-top: 1px solid #e7ebf2;
            }

            .footer__grid {
                display: grid;
                grid-template-columns: minmax(260px, 320px) minmax(170px, 220px) minmax(220px, 1fr);
                gap: 44px 52px;
                align-items: flex-start;
                padding-bottom: 52px;
            }

            .footer__brand,
            .footer__column,
            .footer__contacts,
            .footer__nav {
                display: grid;
            }

            .footer__brand {
                gap: 20px;
            }

            .footer__logo {
                display: inline-flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }

            .footer__brand-name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(34px, 4vw, 48px);
                font-weight: 700;
                letter-spacing: -0.05em;
                color: #161c25;
            }

            .footer__brand-sub {
                color: #6c7583;
                font-size: 15px;
                font-weight: 700;
            }

            .footer__contacts {
                gap: 12px;
            }

            .footer__contacts a {
                color: #1a212d;
                font-size: 17px;
                font-weight: 600;
                transition: color 0.18s ease;
            }

            .footer__contacts a:hover,
            .footer__nav a:hover {
                color: var(--primary);
            }

            .footer__socials {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .footer__social {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                height: 46px;
                border: 1px solid #dbe2ec;
                border-radius: 50%;
                background: #fff;
                color: #1a212d;
                box-shadow: 0 10px 20px rgba(24, 32, 42, 0.06);
                transition: transform 0.18s ease, border-color 0.18s ease, color 0.18s ease;
            }

            .footer__social:hover {
                transform: translateY(-2px);
                border-color: #c6d1df;
                color: var(--primary);
            }

            .footer__column {
                gap: 20px;
                padding-top: 10px;
            }

            .footer__title {
                margin: 0;
                color: #151c25;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 34px;
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .footer__nav {
                gap: 14px;
            }

            .footer__nav a {
                color: #1a212d;
                font-size: 18px;
                font-weight: 600;
                line-height: 1.35;
                transition: color 0.18s ease;
            }

            .footer__bottom {
                background: #2b272b;
                color: rgba(255, 255, 255, 0.96);
            }

            .footer__bottom-inner {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 54px;
                text-align: center;
                font-size: 15px;
                font-weight: 700;
            }

            .menu-toggle {
                display: none;
                width: 42px;
                height: 40px;
                border: 1px solid var(--line-strong);
                border-radius: 14px;
                background: linear-gradient(180deg, #ffffff, #eef2f6);
                box-shadow: 0 10px 20px rgba(24, 32, 42, 0.06);
                cursor: pointer;
            }

            .menu-toggle span {
                display: block;
                width: 18px;
                height: 2px;
                margin: 4px auto;
                background: #1c2430;
            }

            .mobile-menu {
                display: none;
                border-bottom: 1px solid var(--line);
                background: #ffffff;
            }

            .mobile-menu.is-open {
                display: block;
            }

            .mobile-menu__inner {
                display: grid;
                gap: 10px;
                padding: 16px 0 20px;
            }

            .mobile-menu a {
                display: flex;
                align-items: center;
                min-height: 44px;
                padding: 0 14px;
                border: 1px solid var(--line-strong);
                border-radius: 14px;
                background: linear-gradient(180deg, #ffffff, #eef2f6);
                color: #1a212d;
                font-weight: 700;
                box-shadow: 0 10px 20px rgba(24, 32, 42, 0.06);
            }

            html[data-theme="dark"] body {
                background: linear-gradient(180deg, #08111f 0%, #0d1727 100%);
            }

            html[data-theme="dark"] .page-shell {
                background: #08111f;
            }

            html[data-theme="dark"] .compare-hero,
            html[data-theme="dark"] .compare-empty,
            html[data-theme="dark"] .compare-cell {
                border-color: rgba(148, 163, 184, 0.2);
                background: linear-gradient(180deg, rgba(12, 20, 33, 0.96), rgba(10, 16, 28, 0.98));
                box-shadow: 0 18px 38px rgba(2, 6, 23, 0.34);
            }

            html[data-theme="dark"] .compare-hero__title,
            html[data-theme="dark"] .compare-toolbar__meta strong,
            html[data-theme="dark"] .compare-empty__title,
            html[data-theme="dark"] .compare-cell__title,
            html[data-theme="dark"] .compare-cell__price,
            html[data-theme="dark"] .compare-cell__value {
                color: #f3f7ff;
            }

            html[data-theme="dark"] .compare-hero__text,
            html[data-theme="dark"] .compare-toolbar__meta span,
            html[data-theme="dark"] .compare-empty__text,
            html[data-theme="dark"] .compare-cell__code,
            html[data-theme="dark"] .compare-cell__value--muted,
            html[data-theme="dark"] .compare-breadcrumbs,
            html[data-theme="dark"] .compare-table th {
                color: #b9c6d9;
            }

            html[data-theme="dark"] .compare-cell__media {
                border-color: rgba(148, 163, 184, 0.18);
                background: linear-gradient(180deg, rgba(17, 27, 42, 0.96), rgba(12, 20, 31, 0.98));
            }

            html[data-theme="dark"] .compare-toolbar__button,
            html[data-theme="dark"] .compare-cell__button {
                border-color: rgba(148, 163, 184, 0.22);
                background: rgba(30, 41, 59, 0.92);
                color: #eff6ff;
                box-shadow: 0 12px 24px rgba(2, 6, 23, 0.26);
            }

            html[data-theme="dark"] .compare-toolbar__button:hover,
            html[data-theme="dark"] .compare-cell__button:hover {
                border-color: rgba(143, 81, 255, 0.46);
                background: rgba(36, 50, 73, 0.96);
            }

            html[data-theme="dark"] .compare-toolbar__button--primary,
            html[data-theme="dark"] .compare-cell__button--primary {
                border-color: #6f10c9;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
            }

            html[data-theme="dark"] .footer {
                background: radial-gradient(circle at 12% 22%, rgba(132, 36, 240, 0.12), transparent 22%), radial-gradient(circle at 86% 78%, rgba(48, 215, 255, 0.08), transparent 24%), #091220;
                border-top-color: rgba(148, 163, 184, 0.18);
            }

            html[data-theme="dark"] .footer__brand-name,
            html[data-theme="dark"] .footer__title,
            html[data-theme="dark"] .footer__contacts a,
            html[data-theme="dark"] .footer__nav a,
            html[data-theme="dark"] .footer__social {
                color: #eff6ff;
            }

            html[data-theme="dark"] .footer__brand-sub {
                color: #b8c6da;
            }

            html[data-theme="dark"] .footer__social {
                border-color: rgba(148, 163, 184, 0.2);
                background: rgba(15, 23, 42, 0.92);
                box-shadow: 0 10px 20px rgba(2, 6, 23, 0.28);
            }

            html[data-theme="dark"] .menu-toggle {
                border-color: rgba(148, 163, 184, 0.22);
                background: linear-gradient(180deg, rgba(30, 41, 59, 0.92), rgba(15, 23, 42, 0.96));
                box-shadow: 0 12px 24px rgba(2, 6, 23, 0.28);
            }

            html[data-theme="dark"] .menu-toggle span {
                background: #eff6ff;
            }

            html[data-theme="dark"] .mobile-menu {
                border-bottom-color: rgba(148, 163, 184, 0.18);
                background: #08111f;
            }

            html[data-theme="dark"] .mobile-menu a {
                border-color: rgba(148, 163, 184, 0.22);
                background: linear-gradient(180deg, rgba(30, 41, 59, 0.92), rgba(15, 23, 42, 0.96));
                color: #eff6ff;
                box-shadow: 0 12px 24px rgba(2, 6, 23, 0.26);
            }

            @media (max-width: 1080px) {
                .compare-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .footer__grid {
                    grid-template-columns: minmax(220px, 280px) minmax(170px, 210px) minmax(200px, 1fr);
                    gap: 38px 44px;
                }

                .footer__title {
                    font-size: 30px;
                }
            }

            @media (max-width: 760px) {
                .container,
                .compare-wrap {
                    width: calc(100% - 20px);
                }

                .topbar {
                    display: none;
                }

                .page {
                    padding-top: 18px;
                }

                .compare-hero,
                .compare-empty {
                    padding: 22px;
                }

                .compare-hero__text {
                    font-size: 16px;
                }

                .compare-toolbar__actions {
                    width: 100%;
                }

                .compare-toolbar__button {
                    width: 100%;
                }

                .footer {
                    padding-top: 64px;
                }

                .footer__grid {
                    grid-template-columns: 1fr;
                    gap: 34px;
                    padding-bottom: 40px;
                }

                .footer__column {
                    padding-top: 0;
                }

                .footer__title {
                    font-size: 28px;
                }
            }

            @media (max-width: 560px) {
                .compare-breadcrumbs {
                    gap: 8px;
                    font-size: 12px;
                }

                .compare-hero__title {
                    font-size: 34px;
                }

                .compare-cell__media {
                    min-height: 180px;
                    max-height: 180px;
                }

                .compare-cell__title {
                    font-size: 20px;
                }

                .compare-cell__price {
                    font-size: 28px;
                }

                .footer__brand-name {
                    font-size: 36px;
                }

                .footer__nav a,
                .footer__contacts a {
                    font-size: 17px;
                }

                .footer__bottom-inner {
                    min-height: 64px;
                    padding: 10px 0;
                    font-size: 14px;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-shell">
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

            @include('partials.storefront-header', ['showSearch' => true])

            <main class="page">
                <div class="compare-wrap">
                    <nav class="compare-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Головна</a>
                        <span>/</span>
                        <a href="{{ route('catalog') }}">Каталог</a>
                        <span>/</span>
                        <span>Порівняння</span>
                    </nav>

                    <section class="compare-hero">
                        <span class="compare-hero__eyebrow">Порівняння збірок</span>
                        <h1 class="compare-hero__title">Порівняй збірки поруч</h1>
                        <p class="compare-hero__text">До трьох збірок одночасно: основні характеристики, FPS, код товару та ціна на одному екрані. Тут зручно зрозуміти, яка збірка краще підходить саме під твій бюджет і задачі.</p>
                    </section>

                    <div class="compare-toolbar">
                        <div class="compare-toolbar__meta">
                            <strong>Обрано {{ count($selectedBuilds) }} з 3 збірок</strong>
                            <span>Можеш повернутись у каталог, додати ще збірку або прибрати зайве прямо тут.</span>
                        </div>

                        <div class="compare-toolbar__actions">
                            <a class="compare-toolbar__button compare-toolbar__button--primary" href="{{ route('catalog') }}">Повернутись до збірок</a>
                            @if (count($selectedBuilds) > 0)
                                <button class="compare-toolbar__button" type="button" data-compare-clear>Очистити порівняння</button>
                            @endif
                        </div>
                    </div>

                    @if (count($selectedBuilds) < 1)
                        <section class="compare-empty">
                            <h2 class="compare-empty__title">Порівняння поки порожнє</h2>
                            <p class="compare-empty__text">Додай у порівняння до трьох збірок із каталогу або зі сторінки товару, і тут з’явиться зручна таблиця характеристик.</p>
                            <a class="compare-toolbar__button compare-toolbar__button--primary" href="{{ route('catalog') }}">Відкрити каталог</a>
                        </section>
                    @else
                        <section class="compare-table-shell" aria-labelledby="compare-table-title">
                            <h2 id="compare-table-title" class="sr-only">Таблиця порівняння збірок</h2>
                            <table class="compare-table">
                                <tbody>
                                    <tr>
                                        <th>Збірка</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td>
                                                <div class="compare-cell">
                                                    <a class="compare-cell__media" href="{{ route('product.show', ['slug' => $build['slug']]) }}">
                                                        <img src="{{ $build['image_url'] ?? asset('images/kondor-mark-black.svg') }}" alt="{{ $build['name'] }}">
                                                    </a>
                                                    <div class="compare-cell__meta">
                                                        <span class="compare-cell__code">Код {{ $build['product_code'] ?? '—' }}</span>
                                                        <h3 class="compare-cell__title">{{ $build['name'] }}</h3>
                                                    </div>
                                                    <div class="compare-cell__price">{{ $build['price'] }}</div>
                                                    <div class="compare-cell__actions">
                                                        <a class="compare-cell__button" href="{{ route('product.show', ['slug' => $build['slug']]) }}">Відкрити збірку</a>
                                                        <button
                                                            class="compare-cell__button compare-cell__button--primary"
                                                            type="button"
                                                            data-compare-add
                                                            data-build-slug="{{ $build['slug'] }}"
                                                            data-build-name="{{ $build['name'] }}"
                                                            data-build-price="{{ $build['price_value'] ?? 0 }}"
                                                            data-build-url="{{ route('product.show', ['slug' => $build['slug']]) }}"
                                                            data-build-tone="{{ $build['tone'] ?? 'violet' }}"
                                                        >
                                                            Додати в кошик
                                                        </button>
                                                        <button class="compare-cell__button compare-cell__button--danger" type="button" data-compare-remove data-compare-slug="{{ $build['slug'] }}">Прибрати з порівняння</button>
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Відеокарта</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ $build['gpu'] ?: '—' }}</div></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Процесор</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ $build['cpu'] ?: '—' }}</div></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>ОЗП</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ $build['ram'] ?: '—' }}</div></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Накопичувач</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ $build['storage'] ?: '—' }}</div></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>FPS</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ (int) ($build['fps_score'] ?? 0) > 0 ? ((int) $build['fps_score'] . ' FPS') : 'Тест відсутній' }}</div></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Ціна</th>
                                        @foreach ($selectedBuilds as $build)
                                            <td><div class="compare-cell__value">{{ $build['price'] }}</div></td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    @endif
                </div>
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
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/>
                                        <circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/>
                                        <circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/>
                                    </svg>
                                </a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                </a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="Подзвонити">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">Про нас</h2>
                            <nav class="footer__nav" aria-label="Інформація про KondorPC">
                                <a href="{{ url('/') }}#about">Що таке KondorPC</a>
                                <a href="#contacts">Контакти</a>
                                <a href="#contacts">Доставка</a>
                                <a href="#contacts">Оплата</a>
                                <a href="#contacts">Повернення та обмін</a>
                            </nav>
                        </div>

                        <div class="footer__column">
                            <h2 class="footer__title">Основне</h2>
                            <nav class="footer__nav" aria-label="Основна навігація">
                                <a href="{{ url('/') }}">Головна</a>
                                <a href="{{ route('catalog') }}">Комп'ютери</a>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        @include('partials.storefront-compare-tools', [
            'pageCompareItems' => $selectedCompareSlugs,
            'validCompareSlugs' => $validCompareSlugs,
        ])
        <script src="{{ asset('js/storefront-cart.js') }}?v={{ filemtime(public_path('js/storefront-cart.js')) }}"></script>
        <script src="{{ asset('js/storefront-compare.js') }}?v={{ filemtime(public_path('js/storefront-compare.js')) }}"></script>
        <script>
            (() => {
                const header = document.querySelector('.header');
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                const addToCartButtons = Array.from(document.querySelectorAll('[data-compare-add]'));

                const syncHeaderState = () => {
                    if (!header) {
                        return;
                    }

                    header.classList.toggle('is-stuck', window.scrollY > 10);
                };

                const closeMobileMenu = () => {
                    if (!mobileToggle || !mobileMenu) {
                        return;
                    }

                    mobileToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.remove('is-open');
                };

                mobileToggle?.addEventListener('click', () => {
                    const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';
                    mobileToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    mobileMenu?.classList.toggle('is-open', !isExpanded);
                });

                mobileMenu?.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', closeMobileMenu);
                });

                addToCartButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        if (!window.KondorCart) {
                            return;
                        }

                        const slug = button.dataset.buildSlug ?? '';
                        const name = button.dataset.buildName ?? '';
                        const url = button.dataset.buildUrl ?? '';
                        const price = Number(button.dataset.buildPrice ?? 0);

                        if (!slug || !name || !url || !price) {
                            return;
                        }

                        window.KondorCart.addItem({
                            slug,
                            name,
                            price,
                            quantity: 1,
                            url,
                            tone: button.dataset.buildTone ?? 'violet',
                        });

                        const defaultLabel = button.dataset.defaultLabel ?? button.textContent?.trim() ?? 'Додати в кошик';
                        button.dataset.defaultLabel = defaultLabel;
                        button.classList.add('is-added');
                        button.textContent = 'Додано в кошик';

                        window.setTimeout(() => {
                            button.classList.remove('is-added');
                            button.textContent = button.dataset.defaultLabel ?? defaultLabel;
                        }, 1400);
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-mobile-toggle]') && !event.target.closest('[data-mobile-menu]')) {
                        closeMobileMenu();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeMobileMenu();
                    }
                });

                window.addEventListener('scroll', syncHeaderState, { passive: true });
                window.addEventListener('resize', () => {
                    syncHeaderState();

                    if (window.innerWidth > 1080) {
                        closeMobileMenu();
                    }
                });

                syncHeaderState();

                if (window.KondorCart) {
                    window.KondorCart.renderPreviews();
                }
            })();
        </script>
        @include('partials.theme-toggle')
        @include('partials.cookie-consent')
        @include('partials.admin-site-notifications')
        @include('partials.admin-inline-images')
        @include('partials.online-visitors-tracker')
    </body>
</html>
