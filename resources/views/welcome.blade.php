<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>KindorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <style>
            :root {
                --bg: #ffffff;
                --bg-muted: #f6f7fb;
                --surface: #ffffff;
                --text: #18202a;
                --muted: #646d79;
                --line: #dfe3eb;
                --dark: #262225;
                --primary: #6f10c9;
                --primary-dark: #5809a7;
                --shadow: 0 18px 45px rgba(24, 32, 42, 0.08);
                --container: min(100% - 28px, 1920px);
                --win-border: #c9d0da;
                --win-surface-top: #ffffff;
                --win-surface-bottom: #eef2f6;
                --win-surface-hover: #f7f9fc;
                --win-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95), 0 1px 2px rgba(16, 24, 40, 0.08);
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
                background: var(--bg);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            button,
            input {
                font: inherit;
            }

            .page-shell {
                width: 100%;
                margin: 0;
                background: var(--bg);
                border: 0;
                min-height: 100vh;
            }

            .container {
                width: var(--container);
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
            .topbar__socials,
            .header__inner,
            .header__actions,
            .header-cart,
            .brand,
            .hero__layout,
            .hero__actions,
            .dropdown__columns,
            .dropdown__group {
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
                justify-content: center;
                width: 18px;
                height: 18px;
            }

            .header {
                position: relative;
                border-bottom: 1px solid var(--line);
                background: var(--surface);
                z-index: 20;
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
            }

            .header__inner {
                justify-content: space-between;
                gap: 18px;
                min-height: 78px;
            }

            .brand {
                gap: 16px;
                min-width: 180px;
            }

            .brand__name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 31px;
                font-weight: 700;
                letter-spacing: -0.04em;
                color: #11151a;
            }

            .brand__sub {
                display: block;
                margin-top: 2px;
                color: var(--muted);
                font-size: 12px;
                font-weight: 700;
            }

            .header__actions {
                flex: 1;
                justify-content: flex-end;
                gap: 12px;
            }

            .header-button {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 42px;
                padding: 0 20px;
                border: 1px solid var(--win-border);
                border-radius: 14px;
                background: linear-gradient(180deg, var(--win-surface-top), var(--win-surface-bottom));
                color: #1a212d;
                font-size: 14px;
                font-weight: 800;
                cursor: pointer;
                box-shadow: var(--win-shadow);
                transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.18s ease;
            }

            .header-button svg {
                flex: none;
            }

            .header-button--primary {
                border-color: #4b19a1;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(17, 24, 39, 0.12);
            }

            .header-button--primary:hover,
            .header-button--primary:focus-visible,
            .header-button--primary.is-open {
                border-color: #4b19a1;
                background: linear-gradient(180deg, #8f2fff, #7420d3);
                color: #ffffff;
            }

            .header-button:not(.header-button--primary):hover,
            .header-button:not(.header-button--primary).is-open {
                border-color: #bbc4d1;
                background: linear-gradient(180deg, #ffffff, var(--win-surface-hover));
            }

            .header-button:active {
                transform: translateY(1px);
                box-shadow: inset 0 2px 4px rgba(16, 24, 40, 0.12);
            }

            .search-box {
                display: flex;
                align-items: center;
                width: min(100%, 430px);
                min-height: 42px;
                border: 1px solid var(--win-border);
                border-radius: 999px;
                background: linear-gradient(180deg, #ffffff, #f4f7fb);
                box-shadow: var(--win-shadow);
                overflow: hidden;
            }

            .search-box input {
                flex: 1;
                min-width: 0;
                height: 42px;
                padding: 0 16px;
                border: 0;
                outline: none;
                background: transparent;
                color: var(--text);
            }

            .search-box button {
                width: 42px;
                height: 42px;
                border: 0;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                cursor: pointer;
                box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.14);
            }

            .header-cart {
                justify-content: center;
                gap: 9px;
                min-height: 42px;
                padding: 0 16px;
                border: 1px solid var(--line);
                border-radius: 999px;
                background: #ffffff;
                color: #1a212d;
                font-size: 14px;
                font-weight: 800;
                box-shadow: 0 10px 24px rgba(24, 32, 42, 0.08);
                white-space: nowrap;
            }

            .header-cart:hover {
                border-color: #ccd3dd;
                background: #fbfcfe;
            }

            .header-cart svg {
                color: #9298a5;
            }

            .dropdown {
                position: absolute;
                top: calc(100% - 1px);
                left: 50%;
                width: min(1080px, calc(100% - 46px));
                transform: translateX(-50%);
                border: 1px solid var(--line);
                background: #fff;
                box-shadow: var(--shadow);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.18s ease;
            }

            .dropdown.is-open {
                opacity: 1;
                pointer-events: auto;
            }

            .dropdown__columns {
                align-items: stretch;
                gap: 48px;
                padding: 30px 34px;
            }

            .dropdown__group {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                min-width: 200px;
            }

            .dropdown__group h3 {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 19px;
            }

            .dropdown__group a {
                color: #242c37;
                font-size: 16px;
                font-weight: 600;
                transition: color 0.2s ease;
            }

            .dropdown__group a:hover {
                color: var(--primary);
            }

            .dropdown--consultation {
                width: 230px;
                left: 0;
                transform: none;
            }

            .dropdown--consultation .dropdown__columns {
                display: block;
                padding: 12px 0;
            }

            .dropdown--consultation .dropdown__group {
                min-width: 0;
                gap: 0;
            }

            .dropdown--consultation .dropdown__group a {
                display: block;
                width: 100%;
                padding: 18px 34px;
                color: #1d2430;
                font-size: 16px;
                font-weight: 600;
                line-height: 1.35;
            }

            .dropdown--consultation .dropdown__group a:hover {
                background: #faf7ff;
                color: var(--primary);
            }

            .hero {
                padding: 12px 0 42px;
            }

            .hero__layout {
                align-items: stretch;
                gap: 26px;
                min-height: calc(100vh - 120px);
            }

            .hero__copy {
                flex: 1 1 58%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 30px 20px 46px;
            }

            .hero__copy-inner {
                max-width: 430px;
            }

            .hero__copy h1 {
                margin: 0 0 14px;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(38px, 5vw, 58px);
                line-height: 1.04;
                letter-spacing: -0.04em;
            }

            .hero__copy p {
                margin: 0;
                color: var(--muted);
                font-size: 17px;
                line-height: 1.78;
            }

            .hero__actions {
                gap: 14px;
                margin-top: 24px;
                flex-wrap: wrap;
            }

            .hero__visual {
                flex: 0 0 39.5%;
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 720px;
                background:
                    radial-gradient(circle at 18% 24%, rgba(7, 153, 246, 0.68), transparent 38%),
                    radial-gradient(circle at 84% 18%, rgba(31, 227, 166, 0.82), transparent 42%),
                    linear-gradient(135deg, #1b7fd2 2%, #1096ff 24%, #05cf8d 100%);
            }

            .hero__visual::after {
                content: '';
                position: absolute;
                inset: auto 40px 24px 40px;
                height: 10px;
                background: rgba(255, 255, 255, 0.9);
                opacity: 0.92;
            }

            .rig {
                position: relative;
                width: min(590px, 88%);
                aspect-ratio: 0.82;
                transform: translateY(12px);
            }

            .rig__shadow {
                position: absolute;
                inset: auto 8% 2% 8%;
                height: 28px;
                background: rgba(0, 0, 0, 0.22);
                filter: blur(16px);
                border-radius: 999px;
            }

            .rig__case {
                position: absolute;
                inset: 0;
                border: 3px solid #181b1e;
                border-radius: 8px;
                background: linear-gradient(180deg, #101214, #0b0f12);
                box-shadow: 0 26px 40px rgba(0, 0, 0, 0.22);
            }

            .rig__glass {
                position: absolute;
                top: 6%;
                left: 4%;
                width: 72%;
                height: 82%;
                border: 2px solid rgba(122, 132, 145, 0.6);
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.01)),
                    radial-gradient(circle at 30% 20%, rgba(63, 255, 112, 0.12), transparent 40%);
                overflow: hidden;
            }

            .rig__glass::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 38%);
            }

            .rig__frame {
                position: absolute;
                background: #0f1419;
            }

            .rig__frame--top {
                top: 5%;
                left: 7%;
                width: 56%;
                height: 18px;
            }

            .rig__frame--bottom {
                bottom: 10%;
                left: 8%;
                width: 58%;
                height: 18px;
            }

            .rig__frame--gpu {
                top: 57%;
                left: 15%;
                width: 48%;
                height: 36px;
                box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
            }

            .rig__gpu-text {
                position: absolute;
                top: 8px;
                left: 10px;
                color: rgba(255, 255, 255, 0.6);
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.08em;
            }

            .rig__gpu-badge {
                position: absolute;
                top: 8px;
                right: 12px;
                color: #42ff68;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.06em;
            }

            .rig__cooler {
                position: absolute;
                top: 24%;
                left: 31%;
                width: 74px;
                height: 98px;
                border: 2px solid rgba(188, 195, 205, 0.55);
                background: linear-gradient(180deg, rgba(36, 41, 47, 0.96), rgba(15, 18, 21, 0.96));
            }

            .rig__cooler::before,
            .rig__cooler::after {
                content: '';
                position: absolute;
                left: 10px;
                right: 10px;
                height: 2px;
                background: rgba(144, 151, 160, 0.45);
            }

            .rig__cooler::before {
                top: 26px;
            }

            .rig__cooler::after {
                bottom: 26px;
            }

            .rig__fan {
                position: absolute;
                width: 90px;
                aspect-ratio: 1;
                border-radius: 50%;
                background:
                    radial-gradient(circle at center, rgba(210, 255, 214, 0.34) 0 11%, transparent 13% 100%),
                    radial-gradient(circle at center, rgba(83, 255, 89, 0.94) 0 35%, rgba(20, 30, 24, 0.2) 36% 52%, rgba(88, 255, 96, 0.9) 53% 100%);
                box-shadow:
                    0 0 18px rgba(88, 255, 96, 0.72),
                    inset 0 0 18px rgba(24, 29, 31, 0.8);
            }

            .rig__fan::before {
                content: '';
                position: absolute;
                inset: 11px;
                border: 1px solid rgba(217, 255, 218, 0.34);
                border-radius: 50%;
            }

            .rig__fan--rear {
                top: 28%;
                left: 6%;
                width: 84px;
            }

            .rig__fan--front-top {
                top: 16%;
                right: 6%;
            }

            .rig__fan--front-middle {
                top: 40%;
                right: 6%;
            }

            .rig__fan--front-bottom {
                top: 64%;
                right: 6%;
            }

            .rig__fan--top-1,
            .rig__fan--top-2,
            .rig__fan--top-3 {
                top: 4%;
                width: 82px;
            }

            .rig__fan--top-1 {
                left: 11%;
            }

            .rig__fan--top-2 {
                left: 28%;
            }

            .rig__fan--top-3 {
                left: 45%;
            }

            .rig__strip {
                position: absolute;
                border-radius: 999px;
                background: linear-gradient(90deg, rgba(123, 255, 85, 0.88), rgba(65, 255, 101, 0.25));
                box-shadow: 0 0 14px rgba(65, 255, 101, 0.5);
            }

            .rig__strip--top {
                top: 10%;
                left: 12%;
                width: 52%;
                height: 4px;
            }

            .rig__strip--side {
                top: 18%;
                right: 20%;
                width: 4px;
                height: 58%;
            }

            .rig__strip--bottom {
                bottom: 12%;
                left: 11%;
                width: 48%;
                height: 4px;
            }

            .menu-toggle {
                display: none;
                width: 42px;
                height: 40px;
                border: 1px solid var(--win-border);
                border-radius: 14px;
                background: linear-gradient(180deg, var(--win-surface-top), var(--win-surface-bottom));
                box-shadow: var(--win-shadow);
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
                background: #fff;
            }

            .mobile-menu.is-open {
                display: block;
            }

            .mobile-menu__inner {
                display: grid;
                gap: 10px;
                padding: 16px 0 20px;
            }

            .mobile-menu a,
            .mobile-menu button {
                display: flex;
                align-items: center;
                min-height: 44px;
                padding: 0 14px;
                border: 1px solid var(--win-border);
                border-radius: 14px;
                background: linear-gradient(180deg, var(--win-surface-top), var(--win-surface-bottom));
                color: #1a212d;
                font-weight: 700;
                box-shadow: var(--win-shadow);
            }

            @media (max-width: 1320px) {
                .search-box {
                    width: 300px;
                }

                .hero__layout {
                    min-height: 620px;
                }
            }

            @media (max-width: 1080px) {
                .header__actions > .header-button,
                .search-box {
                    display: none;
                }

                .menu-toggle {
                    display: inline-block;
                }

                .hero__layout {
                    flex-direction: column;
                    min-height: unset;
                }

                .hero__copy,
                .hero__visual {
                    flex: unset;
                    width: 100%;
                }

                .hero__copy {
                    justify-content: flex-start;
                    padding-inline: 0;
                }

                .hero__copy-inner {
                    max-width: 620px;
                }
            }

            @media (max-width: 760px) {
                .container {
                    width: min(100% - 20px, 100%);
                }

                .topbar__inner,
                .topbar__links,
                .topbar__meta,
                .topbar__contacts {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 8px;
                }

                .topbar__inner {
                    padding: 10px 0;
                }

                .header__inner {
                    min-height: 72px;
                }

                .brand__name {
                    font-size: 26px;
                }

                .hero {
                    padding-bottom: 24px;
                }

                .hero__visual {
                    min-height: 480px;
                }

                .rig {
                    width: min(450px, 94%);
                }

                .dropdown {
                    width: calc(100% - 20px);
                }

                .dropdown__columns {
                    flex-direction: column;
                    gap: 28px;
                    padding: 24px 20px;
                }
            }

            @media (max-width: 560px) {
                .hero__actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .header-button,
                .hero__actions a {
                    width: 100%;
                }

                .rig__fan {
                    width: 72px;
                }

                .rig__fan--rear {
                    width: 68px;
                }

                .rig__fan--top-1,
                .rig__fan--top-2,
                .rig__fan--top-3 {
                    width: 64px;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="#about">Про нас</a>
                        <a href="#contacts">Контакти</a>
                        <a href="#faq">FAQ</a>
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
                    <a class="brand" href="/">
                        <div>
                            <div class="brand__name">KindorPC</div>
                            <span class="brand__sub">Твоя база геймінгу</span>
                        </div>
                    </a>

                    <div class="header__actions">
                        <a class="header-button header-button--primary" href="#builds">
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

                        <div class="search-box" role="search">
                            <input type="search" placeholder="Пошук збірок">
                            <button type="button" aria-label="Пошук">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M20 20L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <a class="header-cart" href="#cart" aria-label="Кошик">
                            <span>0 ₴</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="9" cy="19" r="1.6" fill="currentColor"/>
                                <circle cx="17" cy="19" r="1.6" fill="currentColor"/>
                                <path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                <div class="dropdown" id="builds-dropdown" data-dropdown-panel="builds">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <h3>Готові збірки</h3>
                            <a href="#builds">1080p Start</a>
                            <a href="#builds">1440p Core</a>
                            <a href="#builds">4K / Creator</a>
                            <a href="#builds">Streaming Build</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Стиль збірки</h3>
                            <a href="#builds">Black Edition</a>
                            <a href="#builds">White Edition</a>
                            <a href="#builds">RGB Showcase</a>
                            <a href="#builds">Minimal Build</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Під замовлення</h3>
                            <a href="#contacts">Підбір під бюджет</a>
                            <a href="#contacts">Апгрейд конфігурації</a>
                            <a href="#contacts">Збірка для стриму</a>
                            <a href="#contacts">Консультація</a>
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
                        <a href="#about">Про нас</a>
                        <a href="#builds">Наші збірки</a>
                        <button type="button" data-mobile-dropdown-toggle>Каталог збірок</button>
                        <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
                        <a href="#contacts">Контакти</a>
                        <a href="#faq">FAQ</a>
                    </div>
                </div>
            </header>

            <section class="hero" id="about">
                <div class="container hero__layout">
                    <div class="hero__copy">
                        <div class="hero__copy-inner">
                            <h1>Твоя база геймінгу</h1>
                            <p>
                                Ласкаво просимо до KindorPC. Тут будуть тільки готові збірки ПК та конфігурації під замовлення.
                                Ми робимо акцент на продуктивність, чисту збірку й ефектний зовнішній вигляд.
                            </p>

                            <div class="hero__actions">
                                <a class="header-button header-button--primary" href="#builds">Наші збірки</a>
                                <a class="header-button" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Написати в Telegram</a>
                            </div>

                        </div>
                    </div>

                    <div class="hero__visual" aria-hidden="true">
                        <div class="rig">
                            <div class="rig__shadow"></div>
                            <div class="rig__case"></div>
                            <div class="rig__glass"></div>
                            <div class="rig__frame rig__frame--top"></div>
                            <div class="rig__frame rig__frame--bottom"></div>
                            <div class="rig__frame rig__frame--gpu">
                                <span class="rig__gpu-text">GEFORCE RTX</span>
                                <span class="rig__gpu-badge">RGB</span>
                            </div>
                            <div class="rig__cooler"></div>
                            <div class="rig__fan rig__fan--rear"></div>
                            <div class="rig__fan rig__fan--front-top"></div>
                            <div class="rig__fan rig__fan--front-middle"></div>
                            <div class="rig__fan rig__fan--front-bottom"></div>
                            <div class="rig__fan rig__fan--top-1"></div>
                            <div class="rig__fan rig__fan--top-2"></div>
                            <div class="rig__fan rig__fan--top-3"></div>
                            <div class="rig__strip rig__strip--top"></div>
                            <div class="rig__strip rig__strip--side"></div>
                            <div class="rig__strip rig__strip--bottom"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <script>
            (() => {
                const header = document.querySelector('.header');
                const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
                const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                let closeTimer;

                const positionConsultationPanel = () => {
                    const trigger = document.querySelector('[data-dropdown-trigger="consultation"]');
                    const panel = document.querySelector('[data-dropdown-panel="consultation"]');

                    if (!header || !trigger || !panel) {
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

                const closeAllDropdowns = () => {
                    triggers.forEach((trigger) => {
                        trigger.classList.remove('is-open');
                        trigger.setAttribute('aria-expanded', 'false');
                    });

                    panels.forEach((panel) => {
                        panel.classList.remove('is-open');
                    });
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

                const clearCloseTimer = () => {
                    if (closeTimer) {
                        window.clearTimeout(closeTimer);
                        closeTimer = undefined;
                    }
                };

                const scheduleClose = () => {
                    clearCloseTimer();
                    closeTimer = window.setTimeout(() => {
                        closeAllDropdowns();
                    }, 120);
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

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
                        closeAllDropdowns();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeAllDropdowns();
                    }
                });

                window.addEventListener('resize', positionConsultationPanel);

                mobileToggle?.addEventListener('click', () => {
                    const isOpen = mobileMenu.classList.toggle('is-open');
                    mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            })();
        </script>
    </body>
</html>
