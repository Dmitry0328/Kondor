<article class="product-card">
    @include('partials.rig-visual', [
        'product' => $product,
        'variant' => 'card',
        'caption' => $product['tagline'],
    ])

    <div class="product-card__body">
        <div class="product-card__meta">
            <span class="eyebrow">{{ $product['badge'] }}</span>
            <span class="availability">{{ $product['availability'] }}</span>
        </div>

        <h3>{{ $product['name'] }}</h3>
        <p>{{ $product['tagline'] }}</p>

        <ul class="product-card__specs">
            <li>
                <span>CPU</span>
                <strong>{{ $product['cpu'] }}</strong>
            </li>
            <li>
                <span>GPU</span>
                <strong>{{ $product['gpu'] }}</strong>
            </li>
            <li>
                <span>RAM / SSD</span>
                <strong>{{ $product['memory'] }} / {{ $product['storage'] }}</strong>
            </li>
        </ul>

        <div class="product-card__footer">
            <div class="price-block">
                <strong>{{ number_format($product['price'], 0, ',', ' ') }} ₴</strong>
                <span>{{ number_format($product['old_price'], 0, ',', ' ') }} ₴</span>
            </div>

            <a class="button button--primary" href="{{ route('store.product', $product['slug']) }}">
                Дивитися збірку
            </a>
        </div>
    </div>
</article>
