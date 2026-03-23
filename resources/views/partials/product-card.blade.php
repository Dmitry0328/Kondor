<article class="product-card">
    @include('partials.rig-visual', [
        'product' => $product,
        'variant' => 'card',
        'caption' => $product['gpu'],
    ])

    <div class="product-card__body">
        <h3>{{ $product['name'] }}</h3>
        <p>{{ $product['tagline'] }}</p>

        <div class="product-card__specs">
            <span>{{ $product['cpu'] }}</span>
            <span>{{ $product['memory'] }}</span>
            <span>{{ $product['storage'] }}</span>
        </div>

        <div class="product-card__footer">
            <div class="price-block">
                <strong>{{ number_format($product['price'], 0, ',', ' ') }} ₴</strong>
                <span>{{ number_format($product['old_price'], 0, ',', ' ') }} ₴</span>
            </div>

            <a class="button button--primary button--small" href="{{ route('store.product', $product['slug']) }}">
                Детальніше
            </a>
        </div>
    </div>
</article>
