<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Девайси | KondorPC</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-inline-images.css') }}">
        @include('partials.theme-head')
        <style>
            body { margin: 0; font-family: 'Manrope', sans-serif; background: #09111d; color: #f5f7fb; }
            a { color: inherit; text-decoration: none; }
            .page { min-height: 100vh; background: radial-gradient(circle at top center, rgba(119, 45, 255, 0.18), transparent 36%), linear-gradient(180deg, #0c1624 0%, #070d16 100%); }
            .hero { padding: 42px 0 30px; }
            .hero__eyebrow { display: inline-block; margin-bottom: 12px; color: #a78bfa; font-size: 12px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; }
            .hero__title { margin: 0; font-size: clamp(42px, 8vw, 84px); line-height: 0.95; letter-spacing: -0.05em; }
            .hero__text { max-width: 780px; margin: 18px 0 0; color: #9aa6bd; font-size: 18px; line-height: 1.7; }
            .filters { display: flex; flex-wrap: wrap; gap: 14px; margin: 34px 0 22px; }
            .filter { padding: 16px 18px; border-radius: 22px; border: 1px solid rgba(146, 159, 186, 0.18); background: rgba(15, 23, 36, 0.72); min-width: 190px; transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease; }
            .filter:hover, .filter.is-active { transform: translateY(-2px); border-color: rgba(124, 58, 237, 0.55); box-shadow: 0 18px 32px rgba(76, 29, 149, 0.18); }
            .filter__label { display: block; font-size: 18px; font-weight: 800; }
            .filter__meta { display: block; margin-top: 8px; color: #8f9cb7; font-size: 13px; line-height: 1.5; }
            .filter__count { display: inline-flex; margin-top: 12px; padding: 6px 10px; border-radius: 999px; background: rgba(124, 58, 237, 0.18); color: #e9ddff; font-size: 12px; font-weight: 800; }
            .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px 28px; padding: 18px 0 72px; align-items: start; }
            .card { display: flex; flex-direction: column; min-height: 100%; padding: 18px; border-radius: 28px; background: linear-gradient(180deg, rgba(22, 28, 39, 0.96), rgba(12, 18, 29, 0.98)); border: 1px solid rgba(145, 158, 185, 0.14); box-shadow: 0 24px 50px rgba(2, 8, 18, 0.38); }
            .card__media { display: block; aspect-ratio: 1 / 1; padding: 18px; border-radius: 22px; background: #fff; }
            .card__media img { width: 100%; height: 100%; object-fit: contain; }
            .card__vendor { margin-top: 18px; color: #a78bfa; font-size: 12px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; }
            .card__title { margin: 10px 0 8px; font-size: 22px; line-height: 1.1; }
            .card__summary { margin: 0; color: #9aa6bd; line-height: 1.65; flex: 1; }
            .card__price { margin: 18px 0 0; font-size: 32px; font-weight: 800; }
            .card__actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
            .button { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 0 18px; border-radius: 16px; border: 1px solid rgba(124, 58, 237, 0.34); background: rgba(255, 255, 255, 0.03); color: #fff; font-weight: 800; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
            .button:hover { transform: translateY(-1px); box-shadow: 0 16px 26px rgba(109, 40, 217, 0.26); }
            .button--primary { background: linear-gradient(135deg, #7c3aed, #9f67ff); box-shadow: 0 14px 28px rgba(124, 58, 237, 0.28); }
            .empty { padding: 36px 0 72px; color: #9aa6bd; font-size: 18px; }
            @media (max-width: 900px) { .grid { gap: 22px; } }
        </style>
    </head>
    <body>
        <div class="cart-site-shell">
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

            @include('partials.storefront-header')

            <main class="page">
                <div class="container">
                    <section class="hero">
                        <span class="hero__eyebrow">Kondor Device</span>
                        <h1 class="hero__title">Девайси. Збери весь комплект</h1>
                        <p class="hero__text">Обирайте клавіатури, миші, килимки, кейкапи й кабелі всередині вашого сайту. Кожен товар можна відкрити, переглянути характеристики, змінити кількість і додати в кошик.</p>
                    </section>

                    <section class="filters" aria-label="Категорії девайсів">
                        <a class="filter{{ $activeType === '' ? ' is-active' : '' }}" href="{{ route('accessories.index') }}">
                            <span class="filter__label">Усі девайси</span>
                            <span class="filter__meta">Повний каталог аксесуарів</span>
                            <span class="filter__count">{{ count($accessories) }} товарів</span>
                        </a>
                        @foreach ($types as $type)
                            <a class="filter{{ $activeType === $type['key'] ? ' is-active' : '' }}" href="{{ route('accessories.index', ['type' => $type['key']]) }}">
                                <span class="filter__label">{{ $type['label'] }}</span>
                                <span class="filter__meta">{{ $type['meta'] }}</span>
                                <span class="filter__count">{{ $type['count'] }} товарів</span>
                            </a>
                        @endforeach
                    </section>

                    @if ($accessories !== [])
                        <section class="grid">
                            @foreach ($accessories as $accessory)
                                <article class="card">
                                    <a class="card__media" href="{{ $accessory['product_url'] }}">
                                        <img src="{{ $accessory['image_url'] }}" alt="{{ $accessory['name'] }}">
                                    </a>
                                    <span class="card__vendor">{{ $accessory['vendor'] !== '' ? $accessory['vendor'] : 'Kondor Device' }}</span>
                                    <h2 class="card__title">{{ $accessory['name'] }}</h2>
                                    <p class="card__summary">{{ $accessory['summary'] !== '' ? $accessory['summary'] : 'Детальна інформація доступна на сторінці товару.' }}</p>
                                    <div class="card__price">{{ $accessory['price'] }} ₴</div>
                                    <div class="card__actions">
                                        <button
                                            class="button"
                                            type="button"
                                            data-accessory-add
                                            data-accessory-slug="{{ $accessory['slug'] }}"
                                            data-accessory-name="{{ $accessory['name'] }}"
                                            data-accessory-price="{{ $accessory['price_raw'] }}"
                                            data-accessory-url="{{ $accessory['product_url'] }}"
                                        >
                                            Обрати
                                        </button>
                                        <a class="button button--primary" href="{{ $accessory['product_url'] }}">Інформація</a>
                                    </div>
                                </article>
                            @endforeach
                        </section>
                    @else
                        <div class="empty">У цій категорії поки немає активних девайсів.</div>
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
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        <script src="{{ asset('js/storefront-cart.js') }}"></script>
        <script>
            document.querySelector('[data-mobile-toggle]')?.addEventListener('click', () => {
                const toggle = document.querySelector('[data-mobile-toggle]');
                const menu = document.querySelector('[data-mobile-menu]');

                if (!toggle || !menu) {
                    return;
                }

                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                menu.classList.toggle('is-open', !isExpanded);
            });

            document.querySelectorAll('[data-accessory-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!window.KondorCart) {
                        return;
                    }

                    window.KondorCart.addItem({
                        itemType: 'accessory',
                        slug: button.dataset.accessorySlug ?? '',
                        cartKey: `accessory:${button.dataset.accessorySlug ?? ''}`,
                        name: button.dataset.accessoryName ?? '',
                        price: Number(button.dataset.accessoryPrice ?? 0),
                        quantity: 1,
                        url: button.dataset.accessoryUrl ?? '',
                        tone: 'violet',
                        configurationSummary: ['Девайс'],
                    }, 1);

                    button.textContent = 'Додано';
                    button.classList.add('button--primary');

                    window.setTimeout(() => {
                        button.textContent = 'Обрати';
                        button.classList.remove('button--primary');
                    }, 1200);
                });
            });

            if (window.KondorCart) {
                window.KondorCart.renderPreviews();
            }
        </script>
    </body>
</html>
