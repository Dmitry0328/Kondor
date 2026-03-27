<div class="header-cart-shell" data-cart-shell>
    <a class="header-cart" href="{{ route('cart') }}" aria-label="Кошик">
        <span data-cart-amount>0 ₴</span>
        <span class="header-cart__badge" data-cart-count hidden>0</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="9" cy="19" r="1.6" fill="currentColor"/>
            <circle cx="17" cy="19" r="1.6" fill="currentColor"/>
            <path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>

    <div class="cart-preview" data-cart-preview>
        <div class="cart-preview__empty" data-cart-empty>
            <p>Кошик поки порожній.</p>
            <a class="cart-preview__button" href="{{ route('catalog') }}">Перейти в каталог</a>
        </div>

        <div class="cart-preview__content" data-cart-content hidden>
            <div class="cart-preview__items" data-cart-items></div>

            <div class="cart-preview__summary">
                <span>Загальна сума</span>
                <strong data-cart-total>0 ₴</strong>
            </div>

            <a class="cart-preview__button cart-preview__button--primary" href="{{ route('cart') }}">
                Перейти у кошик
            </a>
        </div>
    </div>
</div>
