@php
    $cookieConsentVersion = filemtime(public_path('js/cookie-consent.js'));
@endphp

<div class="cookie-consent-root" data-cookie-consent-root>
    <div class="cookie-consent-banner" data-cookie-consent-banner>
        <span class="cookie-consent-banner__eyebrow">Cookie</span>
        <h2 class="cookie-consent-banner__title">Дозволити cookie на сайті?</h2>
        <p class="cookie-consent-banner__text">
            Базові cookie потрібні для кошика, входу та стабільної роботи сайту.
            Окремо можна дозволити налаштування, аналітику та маркетинг.
        </p>
        <div class="cookie-consent-banner__actions">
            <button class="cookie-consent-button cookie-consent-button--ghost" type="button" data-cookie-consent-open-settings>
                Налаштувати
            </button>
            <button class="cookie-consent-button" type="button" data-cookie-consent-necessary-only>
                Лише потрібні
            </button>
            <button class="cookie-consent-button cookie-consent-button--primary" type="button" data-cookie-consent-accept-all>
                Дозволити всі
            </button>
        </div>
    </div>
</div>

<button class="cookie-consent-manage" type="button" data-cookie-consent-manage hidden>
    Cookie
</button>

<div class="cookie-consent-modal" data-cookie-consent-modal>
    <div class="cookie-consent-modal__backdrop" data-cookie-consent-close></div>
    <div class="cookie-consent-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="cookie-consent-title">
        <span class="cookie-consent-modal__eyebrow">Налаштування cookie</span>
        <h2 class="cookie-consent-modal__title" id="cookie-consent-title">Оберіть, що можна зберігати</h2>
        <p class="cookie-consent-modal__text">
            Обов'язкові cookie завжди активні. Без них сайт не працюватиме як треба.
            Інші категорії можна вмикати окремо.
        </p>

        <div class="cookie-consent-modal__grid">
            <div class="cookie-consent-category" data-cookie-consent-category="necessary">
                <div>
                    <h3 class="cookie-consent-category__title">Необхідні</h3>
                    <p class="cookie-consent-category__text">
                        Кошик, вхід, захист форм і стабільна робота сайту.
                    </p>
                </div>
                <label class="cookie-consent-switch">
                    <input type="checkbox" checked disabled>
                    <span></span>
                </label>
            </div>

            <div class="cookie-consent-category" data-cookie-consent-category="preferences">
                <div>
                    <h3 class="cookie-consent-category__title">Налаштування</h3>
                    <p class="cookie-consent-category__text">
                        Запам'ятовування теми сайту та інших зручних дрібниць.
                    </p>
                </div>
                <label class="cookie-consent-switch">
                    <input type="checkbox">
                    <span></span>
                </label>
            </div>

            <div class="cookie-consent-category" data-cookie-consent-category="analytics">
                <div>
                    <h3 class="cookie-consent-category__title">Аналітика</h3>
                    <p class="cookie-consent-category__text">
                        Для статистики відвідувань і покращення сайту. Підключення можна додати пізніше.
                    </p>
                </div>
                <label class="cookie-consent-switch">
                    <input type="checkbox">
                    <span></span>
                </label>
            </div>

            <div class="cookie-consent-category" data-cookie-consent-category="marketing">
                <div>
                    <h3 class="cookie-consent-category__title">Маркетинг</h3>
                    <p class="cookie-consent-category__text">
                        Для рекламних пікселів і ремаркетингу. Категорія вже готова під майбутні підключення.
                    </p>
                </div>
                <label class="cookie-consent-switch">
                    <input type="checkbox">
                    <span></span>
                </label>
            </div>
        </div>

        <p class="cookie-consent-note">
            Вибір можна змінити будь-коли через кнопку <strong>Cookie</strong> внизу сторінки.
        </p>

        <div class="cookie-consent-modal__actions">
            <button class="cookie-consent-button cookie-consent-button--ghost" type="button" data-cookie-consent-close>
                Закрити
            </button>
            <button class="cookie-consent-button" type="button" data-cookie-consent-save-selected>
                Зберегти вибір
            </button>
            <button class="cookie-consent-button cookie-consent-button--primary" type="button" data-cookie-consent-save-all>
                Дозволити всі
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/cookie-consent.js') }}?v={{ $cookieConsentVersion }}"></script>
