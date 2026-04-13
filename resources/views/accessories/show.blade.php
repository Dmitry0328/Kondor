<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $accessory['name'] }} | KondorPC</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-inline-images.css') }}">
        @include('partials.theme-head')
        <style>
            body { margin: 0; font-family: 'Manrope', sans-serif; background: #f6f8fc; color: #18202a; }
            a { color: inherit; text-decoration: none; }
            .page { min-height: 100vh; background: radial-gradient(circle at top right, rgba(124, 58, 237, 0.12), transparent 28%), linear-gradient(180deg, #f8fbff 0%, #eef3f9 100%); }
            .hero { padding: 8px 0 12px; }
            .back { display: inline-flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 999px; border: 1px solid #d8e1ee; background: rgba(255, 255, 255, 0.92); color: #364255; box-shadow: 0 12px 24px rgba(24, 32, 42, 0.06); }
            .layout { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(360px, 0.95fr); gap: 28px; padding: 0 0 70px; }
            .gallery, .panel, .section { border-radius: 30px; border: 1px solid #dce4ef; background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(246, 249, 253, 0.98)); box-shadow: 0 22px 46px rgba(24, 32, 42, 0.08); }
            .gallery { padding: 26px; }
            .gallery__main { display: grid; place-items: center; aspect-ratio: 1 / 1; border-radius: 24px; background: #fff; padding: 28px; }
            .gallery__main img { width: 100%; height: 100%; object-fit: contain; }
            .thumbs { display: grid; grid-template-columns: repeat(auto-fit, minmax(96px, 1fr)); gap: 12px; margin-top: 16px; }
            .thumbs button { padding: 8px; border-radius: 18px; border: 1px solid #dde5f0; background: rgba(255, 255, 255, 0.92); cursor: pointer; box-shadow: 0 10px 18px rgba(24, 32, 42, 0.05); }
            .thumbs button.is-active { border-color: rgba(124, 58, 237, 0.66); box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.18); }
            .thumbs img { width: 100%; height: 90px; object-fit: contain; background: #fff; border-radius: 14px; }
            .panel { padding: 30px; }
            .panel__eyebrow { color: #a78bfa; font-size: 12px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; }
            .panel__title { margin: 14px 0 10px; font-size: clamp(34px, 4.5vw, 58px); line-height: 0.96; letter-spacing: -0.05em; }
            .panel__summary { margin: 0; color: #627184; line-height: 1.75; font-size: 17px; }
            .panel__price { margin: 24px 0; font-size: 48px; font-weight: 800; }
            .buy { display: flex; gap: 12px; align-items: center; margin-top: 22px; flex-wrap: wrap; }
            .qty { display: inline-flex; align-items: center; gap: 8px; padding: 8px; border-radius: 18px; background: rgba(255, 255, 255, 0.92); border: 1px solid #dce4ef; box-shadow: 0 10px 22px rgba(24, 32, 42, 0.05); }
            .qty button { width: 42px; height: 42px; border: 0; border-radius: 14px; background: #eff3f9; color: #18202a; font-size: 24px; cursor: pointer; }
            .qty input { width: 52px; border: 0; background: transparent; color: #18202a; text-align: center; font-size: 20px; font-weight: 800; }
            .buy__button { min-height: 58px; padding: 0 28px; border: 0; border-radius: 20px; background: linear-gradient(135deg, #7c3aed, #9f67ff); color: #fff; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 0 18px 30px rgba(124, 58, 237, 0.28); }
            .feedback { margin-top: 14px; color: #627184; min-height: 24px; }
            .section { padding: 28px; }
            .section h2 { margin: 0 0 18px; font-size: 28px; }
            .specs { display: grid; gap: 12px; }
            .spec { display: grid; gap: 6px; padding: 14px 16px; border-radius: 18px; background: #f6f9fd; border: 1px solid #e1e8f2; }
            .spec strong { font-size: 15px; color: #627184; }
            .spec span { font-size: 18px; font-weight: 700; }
            .package { display: grid; gap: 12px; }
            .package div { padding: 14px 16px; border-radius: 18px; background: #f6f9fd; border: 1px solid #e1e8f2; }
            html[data-theme="dark"] body { background: #08111c; color: #f5f7fb; }
            html[data-theme="dark"] .page { background: radial-gradient(circle at top right, rgba(124, 58, 237, 0.2), transparent 28%), linear-gradient(180deg, #0d1726 0%, #070c14 100%); }
            html[data-theme="dark"] .back { border-color: transparent; background: rgba(255, 255, 255, 0.04); color: #dce3f2; box-shadow: none; }
            html[data-theme="dark"] .gallery,
            html[data-theme="dark"] .panel,
            html[data-theme="dark"] .section { border-color: rgba(145, 158, 185, 0.16); background: linear-gradient(180deg, rgba(18, 24, 35, 0.98), rgba(10, 15, 24, 0.98)); box-shadow: 0 24px 52px rgba(2, 8, 18, 0.36); }
            html[data-theme="dark"] .thumbs button { border-color: rgba(145, 158, 185, 0.18); background: rgba(255, 255, 255, 0.04); box-shadow: none; }
            html[data-theme="dark"] .panel__summary,
            html[data-theme="dark"] .feedback { color: #98a5be; }
            html[data-theme="dark"] .qty { background: rgba(255, 255, 255, 0.04); border-color: rgba(145, 158, 185, 0.16); box-shadow: none; }
            html[data-theme="dark"] .qty button { background: rgba(255, 255, 255, 0.05); color: #fff; }
            html[data-theme="dark"] .qty input { color: #fff; }
            html[data-theme="dark"] .spec,
            html[data-theme="dark"] .package div { background: rgba(255, 255, 255, 0.04); border-color: transparent; }
            html[data-theme="dark"] .spec strong { color: #b7c3db; }
            @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } }
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
                        <a class="back" href="{{ route('accessories.index', ['type' => $accessory['type']]) }}">← Назад до категорії</a>
                    </section>

                    <div class="layout">
                        <section class="gallery">
                            <div class="gallery__main">
                                <img src="{{ $accessory['image_url'] }}" alt="{{ $accessory['name'] }}" data-main-image>
                            </div>
                            @if (count($accessory['image_urls']) > 1)
                                <div class="thumbs">
                                    @foreach ($accessory['image_urls'] as $index => $imageUrl)
                                        <button type="button" class="{{ $index === 0 ? 'is-active' : '' }}" data-thumb="{{ $imageUrl }}">
                                            <img src="{{ $imageUrl }}" alt="{{ $accessory['name'] }} {{ $index + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <aside class="panel">
                            <span class="panel__eyebrow">{{ $accessory['vendor'] !== '' ? $accessory['vendor'] : 'Kondor Device' }}</span>
                            <h1 class="panel__title">{{ $accessory['name'] }}</h1>
                            <p class="panel__summary">{{ $accessory['summary'] !== '' ? $accessory['summary'] : 'Детальний опис товару.' }}</p>
                            <div class="panel__price">{{ $accessory['price'] }} ₴</div>

                            <div class="buy">
                                <div class="qty">
                                    <button type="button" data-qty-minus>−</button>
                                    <input type="number" min="1" max="99" value="1" data-qty-input>
                                    <button type="button" data-qty-plus>+</button>
                                </div>
                                <button class="buy__button" type="button" data-add-to-cart>Додати в кошик</button>
                            </div>
                            <div class="feedback" data-feedback></div>
                        </aside>

                        @if ($accessory['specs'] !== [])
                            <section class="section">
                                <h2>Характеристики</h2>
                                <div class="specs">
                                    @foreach ($accessory['specs'] as $spec)
                                        <div class="spec">
                                            <strong>{{ $spec['label'] }}</strong>
                                            <span>{{ $spec['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        @if ($accessory['package_items'] !== [])
                            <section class="section">
                                <h2>Комплектація</h2>
                                <div class="package">
                                    @foreach ($accessory['package_items'] as $item)
                                        <div>{{ $item['label'] }}</div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    </div>
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
            const qtyInput = document.querySelector('[data-qty-input]');
            const feedback = document.querySelector('[data-feedback]');
            const mainImage = document.querySelector('[data-main-image]');

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

            const normalizeQty = () => {
                const value = Math.max(1, Math.min(99, Number.parseInt(qtyInput?.value || '1', 10) || 1));
                if (qtyInput) {
                    qtyInput.value = `${value}`;
                }
                return value;
            };

            document.querySelectorAll('[data-thumb]').forEach((button) => {
                button.addEventListener('click', () => {
                    const src = button.dataset.thumb ?? '';
                    if (src && mainImage) {
                        mainImage.src = src;
                    }
                    document.querySelectorAll('[data-thumb]').forEach((thumb) => thumb.classList.remove('is-active'));
                    button.classList.add('is-active');
                });
            });

            document.querySelector('[data-qty-minus]')?.addEventListener('click', () => {
                if (qtyInput) {
                    qtyInput.value = `${normalizeQty() - 1}`;
                }
                normalizeQty();
            });

            document.querySelector('[data-qty-plus]')?.addEventListener('click', () => {
                if (qtyInput) {
                    qtyInput.value = `${normalizeQty() + 1}`;
                }
                normalizeQty();
            });

            qtyInput?.addEventListener('input', normalizeQty);
            qtyInput?.addEventListener('change', normalizeQty);

            document.querySelector('[data-add-to-cart]')?.addEventListener('click', () => {
                if (!window.KondorCart) {
                    return;
                }

                const quantity = normalizeQty();

                window.KondorCart.addItem({
                    itemType: 'accessory',
                    slug: @json($accessory['slug']),
                    cartKey: `accessory:${@json($accessory['slug'])}`,
                    name: @json($accessory['name']),
                    price: @json($accessory['price_raw']),
                    quantity,
                    url: @json($accessory['product_url']),
                    tone: 'violet',
                    configurationSummary: ['Девайс'],
                }, quantity);

                if (feedback) {
                    feedback.textContent = `Додано ${quantity} шт. у кошик.`;
                }
            });

            if (window.KondorCart) {
                window.KondorCart.renderPreviews();
            }
        </script>
        @include('partials.theme-toggle')
        @include('partials.admin-site-notifications')
        @include('partials.admin-inline-images')
    </body>
</html>
