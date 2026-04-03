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

            .tradein-panel {
                display: grid;
                gap: 14px;
                padding: 40px 34px;
                border: 1px solid #dde5ef;
                border-radius: 32px;
                background: #fff;
                box-shadow: 0 18px 38px rgba(24, 32, 42, 0.06);
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
                font-size: 19px;
                font-weight: 700;
                line-height: 1.55;
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

                .tradein-target,
                .tradein-panel__back {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        @php
            $storefrontBuilds = \App\Support\StorefrontBuilds::all();
            $headerBuilds = array_slice($storefrontBuilds, 0, 4);
            $selectedBuild = request()->query('build')
                ? \App\Support\StorefrontBuilds::findBySlug((string) request()->query('build'))
                : null;
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

            <main class="tradein-page">
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
                if (window.KondorCart) {
                    window.KondorCart.renderPreviews();
                }
            })();
        </script>
    </body>
</html>
