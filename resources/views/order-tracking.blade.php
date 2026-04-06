<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Р’С–РґСЃС‚РµР¶РµРЅРЅСЏ Р·Р°РјРѕРІР»РµРЅРЅСЏ | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
        <link rel="stylesheet" href="{{ asset('css/order-tracking.css') }}">
    </head>
    <body>
        <div class="cart-site-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="{{ url('/') }}#about">РџСЂРѕ РЅР°СЃ</a>
                        <a href="#contacts">РљРѕРЅС‚Р°РєС‚Рё</a>
                        <a href="{{ url('/') }}#faq">FAQ</a>
                    </div>
                    <div class="topbar__meta">
                        <div class="topbar__contacts">
                            <a href="tel:+380633631066">+380633631066</a>
                        </div>

                        <div class="topbar__socials" aria-label="РЎРѕС†С–Р°Р»СЊРЅС– РјРµСЂРµР¶С–">
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
                            <span class="brand__sub">РўРІРѕСЏ Р±Р°Р·Р° РіРµР№РјС–РЅРіСѓ</span>
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
                            РљРѕРЅСЃСѓР»СЊС‚Р°С†С–СЏ
                        </a>

                        <a class="header-link--primary" href="{{ route('orders.track') }}">Статус замовлення</a>
                        <a class="header-link" href="{{ url('/') }}">Р“РѕР»РѕРІРЅР°</a>
                        <a class="header-link" href="{{ route('orders.track') }}">Статус замовлення</a>
                        @auth
                            @if (auth()->user()?->is_admin)
                                <a class="header-button" href="{{ url('/admin') }}">РђРґРјС–РЅРєР°</a>
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
                            <p class="tracking-hero__eyebrow">Order Tracking</p>
                            <h1>РџРµСЂРµРІС–СЂ СЃС‚Р°С‚СѓСЃ Р·Р°РјРѕРІР»РµРЅРЅСЏ Р·Р° 3 РґР°РЅРёРјРё</h1>
                            <p>Р”Р»СЏ РґРѕСЃС‚СѓРїСѓ РїРѕС‚СЂС–Р±РЅС– РЅРѕРјРµСЂ Р·Р°РјРѕРІР»РµРЅРЅСЏ, РЅРѕРјРµСЂ С‚РµР»РµС„РѕРЅСѓ Р№ РїР°СЂРѕР»СЊ, СЏРєРёР№ РІРёРґР°РІ РјРµРЅРµРґР¶РµСЂ Р°Р±Рѕ СЏРєРёР№ С‚Рё РѕС‚СЂРёРјР°РІ РїС–СЃР»СЏ РѕС„РѕСЂРјР»РµРЅРЅСЏ РЅР° СЃР°Р№С‚С–.</p>
                        </section>

                        <section class="tracking-layout">
                            <div class="tracking-card">
                                <h2>Р—РЅР°Р№С‚Рё Р·Р°РјРѕРІР»РµРЅРЅСЏ</h2>

                                @if ($errors->has('credentials'))
                                    <div class="tracking-alert tracking-alert--error">{{ $errors->first('credentials') }}</div>
                                @endif

                                <form class="tracking-form" method="post" action="{{ route('orders.track.lookup') }}">
                                    @csrf

                                    <label class="tracking-field">
                                        <span>РќРѕРјРµСЂ Р·Р°РјРѕРІР»РµРЅРЅСЏ</span>
                                        <input type="text" name="number" value="{{ old('number', $prefilledNumber) }}" placeholder="KP-260406-00001" required>
                                        @error('number')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <label class="tracking-field">
                                        <span>РќРѕРјРµСЂ С‚РµР»РµС„РѕРЅСѓ</span>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+380..." required>
                                        @error('phone')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <label class="tracking-field">
                                        <span>РџР°СЂРѕР»СЊ</span>
                                        <input type="text" name="tracking_password" value="{{ old('tracking_password') }}" placeholder="AB12CD34" required>
                                        @error('tracking_password')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>

                                    <button type="submit">РџРѕРєР°Р·Р°С‚Рё СЃС‚Р°С‚СѓСЃ</button>
                                </form>
                            </div>

                            <div class="tracking-card tracking-card--info">
                                <h2>Р©Рѕ РїРѕР±Р°С‡РёС‚СЊ РєР»С–С”РЅС‚</h2>
                                <ul class="tracking-list">
                                    <li>РїРѕС‚РѕС‡РЅРёР№ СЃС‚Р°С‚СѓСЃ Р·Р°РјРѕРІР»РµРЅРЅСЏ</li>
                                    <li>РґР°С‚Сѓ Р·Р°РјРѕРІР»РµРЅРЅСЏ</li>
                                    <li>РўРўРќ РїС–СЃР»СЏ РІС–РґРїСЂР°РІРєРё</li>
                                    <li>СЃРєР»Р°Рґ Р·Р°РјРѕРІР»РµРЅРЅСЏ С– СЃСѓРјСѓ</li>
                                </ul>
                                <p>РЇРєС‰Рѕ РѕРґРЅРѕРіРѕ Р· С‚СЂСЊРѕС… РїР°СЂР°РјРµС‚СЂС–РІ РЅРµРјР°С”, СЃС‚РѕСЂС–РЅРєР° СЃС‚Р°С‚СѓСЃСѓ РЅРµ РІС–РґРєСЂРёС”С‚СЊСЃСЏ.</p>
                            </div>
                        </section>

                        @if ($order)
                            <section class="tracking-result">
                                <div class="tracking-card">
                                    <div class="tracking-result__head">
                                        <div>
                                            <p class="tracking-result__eyebrow">Р—Р°РјРѕРІР»РµРЅРЅСЏ {{ $order->number }}</p>
                                            <h2>{{ $order->status_label }}</h2>
                                        </div>
                                        <span class="tracking-badge tracking-badge--{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    </div>

                                    <div class="tracking-stats">
                                        <div class="tracking-stat">
                                            <span>Р”Р°С‚Р° Р·Р°РјРѕРІР»РµРЅРЅСЏ</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ $order->ordered_at?->format('d.m.Y H:i') ?? 'вЂ”' }}</strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>РўРµР»РµС„РѕРЅ</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ $order->phone }}</strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>РўРўРќ</span>
                                            @if ($order->shipping_ttn)
                                                <button
                                                    class="tracking-stat__value tracking-ttn"
                                                    type="button"
                                                    data-copy-ttn
                                                    data-ttn="{{ $order->shipping_ttn }}"
                                                    data-default-label="{{ $order->shipping_ttn }}"
                                                    aria-label="РЎРєРѕРїС–СЋРІР°С‚Рё РўРўРќ"
                                                >
                                                    <strong>{{ $order->shipping_ttn }}</strong>
                                                    <span>РќР°С‚РёСЃРЅРё, С‰РѕР± СЃРєРѕРїС–СЋРІР°С‚Рё</span>
                                                </button>
                                            @else
                                                <div class="tracking-stat__value">
                                                    <strong>Р©Рµ РЅРµ РґРѕРґР°РЅРѕ</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tracking-stat">
                                            <span>РЎСѓРјР°</span>
                                            <div class="tracking-stat__value">
                                                <strong>{{ number_format((int) $order->total_amount, 0, '', ' ') }} в‚ґ</strong>
                                            </div>
                                        </div>
                                    </div>

                                            @if ($order->shipping_ttn && $order->shipment_tracking_url)
                                                <div class="tracking-shipment">
                                                    <div class="tracking-shipment__meta">
                                                        <span>Р’С–РґСЃС‚РµР¶РµРЅРЅСЏ РїРѕСЃРёР»РєРё</span>
                                                        <p>РџРµСЂРµРІС–СЂ РјС–СЃС†РµР·РЅР°С…РѕРґР¶РµРЅРЅСЏ РІС–РґРїСЂР°РІР»РµРЅРЅСЏ РќРѕРІРѕС— РїРѕС€С‚Рё, Р°Р»Рµ РЅРµ Р·Р°Р±СѓРґСЊС‚Рµ СЃРєРѕРїС–СЋРІР°С‚Рё РЅРѕРјРµСЂ РўРўРќ!</p>
                                                    </div>

                                            <a
                                                class="tracking-shipment__link"
                                                href="{{ $order->shipment_tracking_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                РџРµСЂРµРІС–СЂРёС‚Рё РјС–СЃС†РµР·РЅР°С…РѕРґР¶РµРЅРЅСЏ
                                            </a>
                                        </div>
                                    @endif

                                    @if ($order->comment)
                                        <div class="tracking-note">
                                            <span>РљРѕРјРµРЅС‚Р°СЂ РґРѕ Р·Р°РјРѕРІР»РµРЅРЅСЏ</span>
                                            <p>{{ $order->comment }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="tracking-card">
                                    <h2>РЎРєР»Р°Рґ Р·Р°РјРѕРІР»РµРЅРЅСЏ</h2>

                                    @if ($order->items->isEmpty())
                                        <p class="tracking-empty">РњРµРЅРµРґР¶РµСЂ С‰Рµ РЅРµ РґРѕРґР°РІ РїРѕР·РёС†С–С— РґРѕ С†СЊРѕРіРѕ Р·Р°РјРѕРІР»РµРЅРЅСЏ.</p>
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
                                                        <span>{{ $item->quantity }} С€С‚.</span>
                                                        <strong>{{ number_format((int) $item->line_total, 0, '', ' ') }} в‚ґ</strong>
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
                                <span class="footer__brand-sub">РўРІРѕСЏ Р±Р°Р·Р° РіРµР№РјС–РЅРіСѓ</span>
                            </div>
                            <div class="footer__contacts">
                                <a href="tel:+380633631066">+380 63 363 10 66</a>
                                <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">@kondor_channeI</a>
                            </div>
                            <div class="footer__socials">
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/></svg></a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="РџРѕРґР·РІРѕРЅРёС‚Рё"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                            </div>
                        </div>
                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">РџСЂРѕ РЅР°СЃ</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}#about">Р©Рѕ С‚Р°РєРµ KondorPC</a>
                                <a href="#contacts">РљРѕРЅС‚Р°РєС‚Рё</a>
                                <a href="#contacts">Р”РѕСЃС‚Р°РІРєР°</a>
                                <a href="#contacts">РћРїР»Р°С‚Р°</a>
                                <a href="#contacts">РџРѕРІРµСЂРЅРµРЅРЅСЏ С‚Р° РѕР±РјС–РЅ</a>
                            </nav>
                        </div>
                        <div class="footer__column">
                            <h2 class="footer__title">РћСЃРЅРѕРІРЅРµ</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}">Р“РѕР»РѕРІРЅР°</a>
                                <a href="{{ route('catalog') }}">РљР°С‚Р°Р»РѕРі</a>
                                <a href="{{ route('orders.track') }}">РЎС‚Р°С‚СѓСЃ Р·Р°РјРѕРІР»РµРЅРЅСЏ</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Р’СЃС– РїСЂР°РІР° Р·Р°С…РёС‰РµРЅС–</div>
                </div>
            </footer>
        </div>

        <script src="{{ asset('js/storefront-cart.js') }}"></script>
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
                        button.innerHTML = '<strong>' + ttn + '</strong><span>РўРўРќ СЃРєРѕРїС–Р№РѕРІР°РЅРѕ</span>';
                    } catch (error) {
                        const input = document.createElement('input');
                        input.value = ttn;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        button.innerHTML = '<strong>' + ttn + '</strong><span>РўРўРќ СЃРєРѕРїС–Р№РѕРІР°РЅРѕ</span>';
                    }

                    window.setTimeout(() => {
                        button.innerHTML = '<strong>' + defaultLabel + '</strong><span>РќР°С‚РёСЃРЅРё, С‰РѕР± СЃРєРѕРїС–СЋРІР°С‚Рё</span>';
                    }, 1600);
                });
            });
        </script>
    </body>
</html>

