@extends('layouts.storefront')

@section('title', $product['name'].' | Kondor PC')
@section('description', $product['tagline'])

@section('content')
    <section class="page-head page-head--product">
        <div class="container breadcrumb">
            <a href="{{ route('store.home') }}">Головна</a>
            <span>/</span>
            <a href="{{ route('store.catalog') }}">Наші збірки</a>
            <span>/</span>
            <strong>{{ $product['name'] }}</strong>
        </div>
    </section>

    <section class="section">
        <div class="container product-layout">
            <div class="product-gallery" data-gallery>
                @foreach ($product['gallery'] as $index => $slide)
                    <div data-gallery-panel @if ($index > 0) hidden @endif>
                        @include('partials.rig-visual', [
                            'product' => $product,
                            'variant' => 'product',
                            'caption' => $slide['text'],
                        ])
                    </div>
                @endforeach

                <div class="product-gallery__thumbs">
                    @foreach ($product['gallery'] as $index => $slide)
                        <button class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}" type="button" data-gallery-thumb>
                            {{ $slide['title'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <aside class="product-summary">
                <span class="product-summary__badge">{{ $product['badge'] }}</span>
                <h1>{{ $product['name'] }}</h1>
                <p>{{ $product['intro'] }}</p>

                <div class="summary-price">
                    <strong>{{ number_format($product['price'], 0, ',', ' ') }} ₴</strong>
                    <span>{{ number_format($product['old_price'], 0, ',', ' ') }} ₴</span>
                </div>

                <div class="summary-short">
                    <span>{{ $product['cpu'] }}</span>
                    <span>{{ $product['gpu'] }}</span>
                    <span>{{ $product['memory'] }}</span>
                    <span>{{ $product['storage'] }}</span>
                </div>

                <div class="summary-actions">
                    <a class="button button--primary button--block" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Замовити консультацію</a>
                    <a class="button button--secondary button--block" href="tel:{{ $store['brand']['phone'] }}">Зателефонувати</a>
                </div>
            </aside>
        </div>
    </section>

    <section class="section section--muted">
        <div class="container product-info-grid">
            <article class="product-panel">
                <h2>Характеристики</h2>
                <div class="spec-list">
                    @foreach ($product['specs'] as $spec)
                        <div class="spec-list__row">
                            <span>{{ $spec['label'] }}</span>
                            <strong>{{ $spec['value'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="product-panel">
                <h2>Що входить</h2>
                <ul class="product-list">
                    @foreach ($product['included'] as $included)
                        <li>{{ $included }}</li>
                    @endforeach
                </ul>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="container product-info-grid">
            <article class="product-panel">
                <h2>Можливі апгрейди</h2>
                <div class="spec-list">
                    @foreach ($product['options'] as $option)
                        <div class="spec-list__row">
                            <span>{{ $option['title'] }}</span>
                            <strong>{{ $option['value'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="product-panel">
                <h2>Орієнтовна продуктивність</h2>
                <div class="spec-list">
                    @foreach ($product['performance'] as $row)
                        <div class="spec-list__row">
                            <span>{{ $row['game'] }}</span>
                            <strong>{{ $row['fps'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-header__eyebrow">Схожі збірки</span>
                    <h2>Інші варіанти</h2>
                </div>
                <a class="section-header__link" href="{{ route('store.catalog') }}">До каталогу</a>
            </div>

            <div class="product-grid">
                @foreach ($relatedProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endsection
