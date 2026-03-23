@extends('layouts.storefront')

@section('title', $product['name'].' | Kondor PC')
@section('description', $product['tagline'])

@section('content')
    <section class="page-hero page-hero--product">
        <div class="container breadcrumb">
            <a href="{{ route('store.home') }}">Головна</a>
            <span>/</span>
            <a href="{{ route('store.catalog', ['tier' => $product['tier_slug']]) }}">Каталог</a>
            <span>/</span>
            <strong>{{ $product['name'] }}</strong>
        </div>
    </section>

    <section class="section section--dense">
        <div class="container product-layout">
            <div class="product-gallery" data-gallery>
                @foreach ($product['gallery'] as $index => $slide)
                    <div data-gallery-panel @if ($index > 0) hidden @endif>
                        @include('partials.rig-visual', [
                            'product' => $product,
                            'variant' => 'gallery',
                            'caption' => $slide['text'],
                        ])
                    </div>
                @endforeach

                <div class="gallery-thumbs">
                    @foreach ($product['gallery'] as $index => $slide)
                        <button class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}" type="button" data-gallery-thumb>
                            <strong>{{ $slide['title'] }}</strong>
                            <span>{{ $slide['text'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <aside class="product-summary">
                <span class="eyebrow">{{ $product['series'] }}</span>
                <h1>{{ $product['name'] }}</h1>
                <p>{{ $product['intro'] }}</p>

                <div class="summary-price">
                    <strong>{{ number_format($product['price'], 0, ',', ' ') }} ₴</strong>
                    <span>{{ number_format($product['old_price'], 0, ',', ' ') }} ₴</span>
                </div>

                <div class="summary-badges">
                    <span>{{ $product['badge'] }}</span>
                    <span>{{ $product['availability'] }}</span>
                    <span>{{ $product['colors'] }}</span>
                </div>

                <div class="summary-actions">
                    <a class="button button--primary button--block" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">
                        Замовити консультацію
                    </a>
                    <a class="button button--ghost button--block" href="tel:{{ $store['brand']['phone'] }}">
                        Зателефонувати
                    </a>
                </div>

                <ul class="summary-highlights">
                    @foreach ($product['highlights'] as $highlight)
                        <li>{{ $highlight }}</li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container product-sections">
            <article class="surface-card">
                <div class="section-heading section-heading--tight">
                    <div>
                        <span class="eyebrow">Характеристики</span>
                        <h2>Основна конфігурація</h2>
                    </div>
                </div>

                <div class="spec-grid">
                    @foreach ($product['specs'] as $spec)
                        <div class="spec-card">
                            <span>{{ $spec['label'] }}</span>
                            <strong>{{ $spec['value'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="surface-card">
                <div class="section-heading section-heading--tight">
                    <div>
                        <span class="eyebrow">Апгрейди</span>
                        <h2>Що можна змінити перед замовленням</h2>
                    </div>
                </div>

                <div class="option-list">
                    @foreach ($product['options'] as $option)
                        <div class="option-row">
                            <strong>{{ $option['title'] }}</strong>
                            <span>{{ $option['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="section section--accent">
        <div class="container dual-grid">
            <article class="surface-card">
                <span class="eyebrow">Що входить</span>
                <h2>Що клієнт отримує разом із ПК</h2>
                <ul class="check-list">
                    @foreach ($product['included'] as $included)
                        <li>{{ $included }}</li>
                    @endforeach
                </ul>
            </article>

            <article class="surface-card">
                <span class="eyebrow">Орієнтовна продуктивність</span>
                <h2>FPS-орієнтири для цього класу збірки</h2>
                <div class="performance-list">
                    @foreach ($product['performance'] as $row)
                        <div class="performance-row">
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
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Схожі збірки</span>
                    <h2>Ще кілька конфігурацій у цьому стилі</h2>
                </div>
                <a class="button button--ghost" href="{{ route('store.catalog') }}">Назад у каталог</a>
            </div>

            <div class="product-grid">
                @foreach ($relatedProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endsection
