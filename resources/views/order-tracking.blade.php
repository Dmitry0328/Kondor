<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Відстеження замовлення | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
        <link rel="stylesheet" href="{{ asset('css/order-tracking.css') }}">
        @include('partials.theme-head')
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

            <header class="header">
                <div class="container header__inner">
                    <a class="brand" href="{{ url('/') }}">
                        <div>
                            <div class="brand__name">KondorPC</div>
                            <span class="brand__sub">Твоя база геймінгу</span>
                        </div>
                    </a>

                    <div class="header__actions">
                        <a class="header-button header-button--primary" href="{{ route('orders.track') }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 17H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Статус замовлення
                        </a>

                        <a class="header-button" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 10V12L13.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Консультація
                        </a>

                        <a class="header-link--primary" href="{{ route('orders.track') }}">Статус замовлення</a>
                        <a class="header-link" href="{{ url('/') }}">Головна</a>
                        <a class="header-link" href="{{ route('orders.track') }}">Статус замовлення</a>
                        @auth
                            @if (auth()->user()?->is_admin)
                                <a class="header-button" href="{{ url('/admin') }}">Адмінка</a>
                            @endif
                        @endauth
                        @include('partials.header-cart', ['hideTrackingLink' => true])
                    </div>
                </div>
            </header>

            <main class="page">
                <div class="tracking-shell">
                    <main class="tracking-page">
                        <section class="tracking-hero">
                            <p class="tracking-hero__eyebrow">Відстеження замовлення</p>
                            <h1>Перевір статус замовлення за 3 дні</h1>
                            <p>Для доступу до інформації про замовлення введіть номер телефону та пароль, який ви отримали після оформлення замовлення на сайті.</p>
                        </section>

                        <section class="tracking-layout">
                            <div class="tracking-card">
                                <h2>Знайти замовлення</h2>

                                @if ($errors->has('credentials'))
                                    <div class="tracking-alert tracking-alert--error">{{ $errors->first('credentials') }}</div>
                                @endif

                                <form class="tracking-form" method="post" action="{{ route('orders.track.lookup') }}">
                                    @csrf

                                    <label class="tracking-field">
                                        <span>Номер замовлення</span>
                                        <input type="text" name="number" value="{{ old('number', $prefilledNumber) }}" placeholder="KP-260406-00001" required>
                                        @error('number')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <label class="tracking-field">
                                        <span>Номер телефону</span>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+380..." required>
                                        @error('phone')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <label class="tracking-field">
                                        <span>Пароль</span>
                                        <input type="text" name="tracking_password" value="{{ old('tracking_password') }}" placeholder="AB12CD34" required>
                                        @error('tracking_password')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <button type="submit">Показати статус</button>
                                </form>
                            </div>

                            <div class="tracking-card tracking-card--info">
                                <h2>Як знайти замовлення</h2>
                                <ul class="tracking-list">
                                    <li>поточний статус замовлення</li>
                                    <li>дату замовлення</li>
                                    <li>ТТН після відправлення</li>
                                    <li>склад замовлення та суму</li>
                                </ul>
                                <p>Якщо одного з цих параметрів немає, статус замовлення не буде відображено.</p>
                            </div>
                        </section>

                        @if ($order)
                            <section class="tracking-result">
                                <div class="tracking-card">
                                    <div class="tracking-result__head">
                                        <div>
                                            <p class="tracking-result__eyebrow">Замовлення {{ $order->number }}</p>
                                            <h2>{{ $order->status_label }}</h2>
                                        </div>
                                        <span class="tracking-badge tracking-badge--{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    </div>

                                    <div class="tracking-stats">
                                        <div class="tracking-stat">
                                            <span>Дата замовлення</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ $order->ordered_at?->format('d.m.Y H:i') ?? '—' }}</strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>Телефон</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ $order->phone }}</strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>ТТН</span>
                                            @if ($order->shipping_ttn)
                                                <button
                                                    class="tracking-stat__value tracking-ttn"
                                                    type="button"
                                                    data-copy-ttn
                                                    data-ttn="{{ $order->shipping_ttn }}"
                                                    data-default-label="{{ $order->shipping_ttn }}"
                                                    aria-label="Скопіювати ТТН"
                                                >
                                                    <strong>{{ $order->shipping_ttn }}</strong>
                                                    <span>Натисни, щоб скопіювати</span>
                                                </button>
                                            @else
                                                <div class="tracking-stat__value">
                                                    <strong>Ще не додано</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tracking-stat">
                                            <span>Сума</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ number_format((int) $order->total_amount, 0, '', ' ') }} ₴</strong>
                                            </div>
                                        </div>
                                    </div>

                                            @if ($order->shipping_ttn && $order->shipment_tracking_url)
                                                <div class="tracking-shipment">
                                                    <div class="tracking-shipment__meta">
                                                        <span>Відстеження посилки</span>
                                                        <p>Перевір місцезнаходження відправлення Нової пошти, але не забудьте скопіювати номер ТТН!</p>
                                                    </div>

                                            <a
                                                class="tracking-shipment__link"
                                                href="{{ $order->shipment_tracking_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Перевірити місцезнаходження
                                            </a>
                                        </div>
                                    @endif

                                    @if ($order->comment)
                                        <div class="tracking-note">
                                            <span>Коментар до замовлення</span>
                                            <p>{{ $order->comment }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="tracking-card">
                                    <h2>Склад замовлення</h2>

                                    @if ($order->items->isEmpty())
                                        <p class="tracking-empty">Менеджер ще не додав позиції до цього замовлення.</p>
                                    @else
                                        <div class="tracking-items">
                                            @foreach ($order->items as $item)
                                                <article class="tracking-item">
                                                    <div>
                                                        <strong>{{ $item->build_name }}</strong>
                                                        @if (!empty($item->meta['configuration_summary']))
                                                            <ul>
                                                                @foreach ((array) $item->meta['configuration_summary'] as $line)
                                                                    <li>{{ $line }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                    <div class="tracking-item__meta">
                                                        <span>{{ $item->quantity }} шт.</span>
                                                        <strong>{{ number_format((int) $item->line_total, 0, '', ' ') }} ₴</strong>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </section>
                        @endif
                    </main>
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
                                <a href="{{ route('orders.track') }}">Статус замовлення</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        <script src="{{ asset('js/storefront-cart.js') }}?v={{ filemtime(public_path('js/storefront-cart.js')) }}"></script>
        @include('partials.theme-toggle')
        <script>
            document.querySelectorAll('[data-copy-ttn]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const ttn = button.dataset.ttn ?? '';
                    const defaultLabel = button.dataset.defaultLabel ?? button.textContent.trim();

                    if (!ttn) {
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(ttn);
                        button.innerHTML = '<strong>' + ttn + '</strong><span>ТТН скопійовано</span>';
                    } catch (error) {
                        const input = document.createElement('input');
                        input.value = ttn;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        button.innerHTML = '<strong>' + ttn + '</strong><span>ТТН скопійовано</span>';
                    }

                    window.setTimeout(() => {
                        button.innerHTML = '<strong>' + defaultLabel + '</strong><span>Натисни, щоб скопіювати</span>';
                    }, 1600);
                });
            });
        </script>
    </body>
</html>
