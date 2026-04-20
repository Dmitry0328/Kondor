@php
    $telegramUrl = 'https://t.me/kondor_channeI';
@endphp
<style>
    .storefront-disclaimer {
        position: fixed;
        inset: 0;
        z-index: 2400;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(4, 9, 17, 0.64);
        backdrop-filter: blur(8px);
    }

    .storefront-disclaimer.is-open {
        display: flex;
    }

    .storefront-disclaimer__dialog {
        width: min(100%, 720px);
        padding: 28px;
        border: 1px solid rgba(168, 183, 208, 0.28);
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(13, 18, 27, 0.98), rgba(8, 13, 20, 0.98));
        color: #f4f7ff;
        box-shadow: 0 36px 80px rgba(2, 6, 14, 0.46);
    }

    .storefront-disclaimer__title {
        margin: 0 0 14px;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.02;
        letter-spacing: -0.04em;
    }

    .storefront-disclaimer__text {
        margin: 0;
        color: rgba(230, 236, 247, 0.88);
        font-size: 17px;
        line-height: 1.75;
    }

    .storefront-disclaimer__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .storefront-disclaimer__button,
    .storefront-disclaimer__close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 52px;
        padding: 0 20px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }

    .storefront-disclaimer__button {
        border: 1px solid #7b2fff;
        background: linear-gradient(180deg, #8f2fff 0%, #6f21d5 100%);
        color: #fff;
        box-shadow: 0 18px 36px rgba(111, 33, 213, 0.32);
    }

    .storefront-disclaimer__close {
        border: 1px solid rgba(166, 180, 205, 0.22);
        background: rgba(255, 255, 255, 0.05);
        color: #f4f7ff;
    }

    .storefront-disclaimer__button:hover,
    .storefront-disclaimer__close:hover {
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    .storefront-disclaimer__note {
        margin: 18px 0 0;
        padding-top: 18px;
        border-top: 1px solid rgba(166, 180, 205, 0.16);
        color: rgba(230, 236, 247, 0.68);
        font-size: 14px;
        line-height: 1.65;
    }

    @media (max-width: 760px) {
        .storefront-disclaimer {
            padding: 14px;
            align-items: flex-end;
        }

        .storefront-disclaimer__dialog {
            padding: 22px 18px;
            border-radius: 24px;
        }

        .storefront-disclaimer__text {
            font-size: 15px;
            line-height: 1.7;
        }

        .storefront-disclaimer__actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .storefront-disclaimer__button,
        .storefront-disclaimer__close {
            width: 100%;
        }
    }
</style>

<div class="storefront-disclaimer" data-storefront-disclaimer aria-hidden="true">
    <div class="storefront-disclaimer__dialog" role="dialog" aria-modal="true" aria-labelledby="storefront-disclaimer-title">
        <h2 class="storefront-disclaimer__title" id="storefront-disclaimer-title">Важливо</h2>
        <p class="storefront-disclaimer__text">
            Цей шаблон на стадії розробки та не має жодного відношення до офіційного сайту бренду Kondor PC. Логотип і назва магазину Kondor PC взяті на тимчасове використання. Замовити ПК можна у Telegram магазину Kondor PC.
        </p>

        <div class="storefront-disclaimer__actions">
            <a class="storefront-disclaimer__button" href="{{ $telegramUrl }}" target="_blank" rel="noreferrer">Перейти в Telegram</a>
            <button class="storefront-disclaimer__close" type="button" data-storefront-disclaimer-close>Закрити</button>
        </div>

        <p class="storefront-disclaimer__note">
            p.s. Дякую Kondor PC за наданий тимчасовий дозвіл на використання назви магазину та логотипу.
        </p>
    </div>
</div>

<script>
    (() => {
        const storageKey = 'kondor_storefront_disclaimer_seen';
        const popup = document.querySelector('[data-storefront-disclaimer]');
        const closeButton = document.querySelector('[data-storefront-disclaimer-close]');

        if (!popup || window.localStorage.getItem(storageKey) === '1') {
            return;
        }

        const closePopup = () => {
            popup.classList.remove('is-open');
            popup.setAttribute('aria-hidden', 'true');
            window.localStorage.setItem(storageKey, '1');
        };

        window.requestAnimationFrame(() => {
            popup.classList.add('is-open');
            popup.setAttribute('aria-hidden', 'false');
        });

        closeButton?.addEventListener('click', closePopup);
        popup.addEventListener('click', (event) => {
            if (event.target === popup) {
                closePopup();
            }
        });
    })();
</script>
