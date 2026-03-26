<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Кошик | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
    </head>
    <body>
        @php
            $headerBuilds = array_slice(config('kondor_storefront.builds', []), 0, 4);
        @endphp

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

                        <a class="header-link--primary" href="{{ route('catalog') }}">До каталогу</a>
                        <a class="header-link" href="{{ url('/') }}">Головна</a>
                        <a class="header-link" href="{{ route('catalog') }}">Каталог збірок</a>
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

            <main class="page">
                <div
                    class="cart-page"
                    data-cart-page
                    data-cart-mode="{{ $sharedToken ? 'shared' : 'local' }}"
                    data-cart-url="{{ route('cart') }}"
                    data-share-endpoint="{{ route('cart.share') }}"
                    data-checkout-endpoint="{{ route('cart.checkout') }}"
                    data-shared-cart='@json($sharedCartItems, JSON_UNESCAPED_UNICODE)'
                >
                <section class="cart-hero">
                    <div>
                        <p class="cart-hero__eyebrow">Kondor Storefront</p>
                        <h1 class="cart-hero__title">Кошик магазину</h1>
                        <p class="cart-hero__text">Перевір склад замовлення, поділись кошиком або оформи покупку з оплатою при отриманні.</p>
                    </div>

                    @if ($sharedToken)
                        <div class="cart-shared-pill">
                            <span>Поділений кошик</span>
                            @if ($sharedExpiresAt)
                                <strong>Активний до {{ $sharedExpiresAt->format('d.m.Y') }}</strong>
                            @endif
                        </div>
                    @endif
                </section>

                <section class="cart-layout">
                    <div class="cart-main">
                        @if ($sharedToken)
                            <div class="cart-shared-banner">
                                <div>
                                    <strong>Ти відкрив поділений кошик.</strong>
                                    <span>Можеш зберегти його собі або одразу оформити замовлення.</span>
                                </div>
                                <button class="cart-secondary-button" type="button" data-cart-import>Зберегти цей кошик</button>
                            </div>
                        @endif

                        <section class="cart-panel" data-cart-empty-state hidden>
                            <div class="cart-empty-state">
                                <h2>Кошик порожній</h2>
                                <p>Додай збірку з головної, каталогу або сторінки товару, щоб продовжити.</p>
                                <a class="cart-primary-button" href="{{ route('catalog') }}">Перейти в каталог</a>
                            </div>
                        </section>

                        <section class="cart-panel" data-cart-content-state hidden>
                            <div class="cart-items-head">
                                <span>Збірки в кошику</span>
                                <button class="cart-inline-button" type="button" data-cart-clear>Очистити кошик</button>
                            </div>

                            <div class="cart-items" data-cart-items-page></div>

                            <div class="cart-main__footer">
                                <button class="cart-secondary-button cart-secondary-button--share" type="button" data-cart-share>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M10 14L14 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M8.5 17H7A4 4 0 1 1 7 9H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15 15H17A4 4 0 0 0 17 7H15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span data-cart-share-label>Поділитися кошиком</span>
                                </button>

                                <div class="cart-main__total">
                                    <span>Сума замовлення</span>
                                    <strong data-cart-page-total>0 ₴</strong>
                                </div>
                            </div>
                        </section>
                    </div>

                    <aside class="cart-panel cart-checkout">
                        <div class="cart-checkout__head">
                            <h2>Оформлення замовлення</h2>
                            <p>Зараз доступний один варіант оплати: <strong>Оплата при отриманні</strong>.</p>
                        </div>

                        <form class="cart-checkout__form" data-checkout-form>
                            <input type="hidden" name="payment_method" value="cash_on_delivery">

                            <label class="cart-field">
                                <span>Ім'я та прізвище</span>
                                <input type="text" name="customer_name" placeholder="Ваше ім'я" required>
                            </label>

                            <label class="cart-field">
                                <span>Телефон</span>
                                <input type="tel" name="phone" placeholder="+380..." required>
                            </label>

                            <label class="cart-field">
                                <span>Telegram / Viber</span>
                                <input type="text" name="messenger_contact" placeholder="@nickname або +380...">
                            </label>

                            <label class="cart-field">
                                <span>Коментар</span>
                                <textarea name="comment" rows="5" placeholder="Деталі по доставці, побажання до дзвінка тощо"></textarea>
                            </label>

                            <div class="cart-payment-card is-active">
                                <div>
                                    <strong>Оплата при отриманні</strong>
                                    <span>Підтверджуємо деталі замовлення і оплачуєш після отримання.</span>
                                </div>
                            </div>

                            <div class="cart-checkout__summary">
                                <span>До сплати</span>
                                <strong data-cart-checkout-total>0 ₴</strong>
                            </div>

                            <button class="cart-primary-button" type="submit">Оформити замовлення</button>
                            <p class="cart-feedback" data-checkout-feedback></p>
                        </form>
                    </aside>
                </section>
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

        <div class="cart-modal" data-share-modal hidden>
            <div class="cart-modal__backdrop" data-share-close></div>
            <div class="cart-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="share-cart-title">
                <button class="cart-modal__close" type="button" data-share-close aria-label="Закрити">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <h2 id="share-cart-title">Поділитися кошиком</h2>
                <p>Скопіюй посилання і надішли його клієнту або собі на інший пристрій.</p>

                <label class="cart-field">
                    <span>Посилання на кошик</span>
                    <input type="text" value="" readonly data-share-link>
                </label>

                <p class="cart-modal__meta" data-share-meta></p>
                <button class="cart-primary-button" type="button" data-share-copy>Копіювати посилання</button>
            </div>
        </div>

        @include('partials.admin-site-notifications')
        <script src="{{ asset('js/storefront-cart.js') }}"></script>
        <script src="{{ asset('js/cart-page.js') }}"></script>
    </body>
</html>
