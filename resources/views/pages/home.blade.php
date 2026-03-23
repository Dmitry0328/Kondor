@extends('layouts.storefront')

@section('title', 'Kondor PC | Головна')
@section('description', 'Адаптивний шаблон магазину Kondor з каталогом, сторінками збірок і темною/світлою темою.')

@section('content')
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__content">
                <span class="eyebrow">{{ $store['hero']['eyebrow'] }}</span>
                <h1>{{ $store['hero']['title'] }}</h1>
                <p>{{ $store['hero']['subtitle'] }}</p>

                <div class="hero__actions">
                    <a class="button button--primary" href="{{ route('store.catalog') }}">Дивитися каталог</a>
                    <a class="button button--ghost" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Замовити консультацію</a>
                </div>

                <div class="hero__stats">
                    @foreach ($heroProduct['hero_stats'] as $stat)
                        <article class="stat-card">
                            <strong>{{ $stat['value'] }}</strong>
                            <span>{{ $stat['label'] }}</span>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="hero__feature">
                @include('partials.rig-visual', [
                    'product' => $heroProduct,
                    'variant' => 'hero',
                    'caption' => $heroProduct['intro'],
                ])

                <div class="feature-card">
                    <span class="eyebrow">{{ $heroProduct['badge'] }}</span>
                    <h2>{{ $heroProduct['name'] }}</h2>
                    <p>{{ $heroProduct['tagline'] }}</p>
                    <div class="feature-card__price">
                        <strong>{{ number_format($heroProduct['price'], 0, ',', ' ') }} ₴</strong>
                        <span>{{ number_format($heroProduct['old_price'], 0, ',', ' ') }} ₴</span>
                    </div>
                    <a class="button button--primary button--block" href="{{ route('store.product', $heroProduct['slug']) }}">Відкрити сторінку товару</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Фокус магазину</span>
                    <h2>Структура, близька до реального магазину ПК</h2>
                </div>
                <p>Ми заклали домашню сторінку, каталог, сторінку товару, контакти, CTA-блоки та основу під подальший движок і адмінку.</p>
            </div>

            <div class="benefits-grid">
                @foreach ($benefits as $benefit)
                    <article class="info-card">
                        <h3>{{ $benefit['title'] }}</h3>
                        <p>{{ $benefit['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--dense">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Вітрина збірок</span>
                    <h2>Популярні конфігурації Kondor</h2>
                </div>
                <a class="button button--ghost" href="{{ route('store.catalog') }}">Увесь каталог</a>
            </div>

            <div class="product-grid">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Категорії</span>
                    <h2>Каталог побудований як магазин, а не як лендинг</h2>
                </div>
                <p>Окремі категорії під збірки, компоненти, монітори й периферію допоможуть далі спокійно перейти до повноцінного каталогу.</p>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <article class="category-card">
                        <span>{{ $category['section'] }}</span>
                        <h3>{{ $category['name'] }}</h3>
                        <p>{{ $category['description'] }}</p>
                        <a href="{{ route('store.catalog') }}">Перейти</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--accent">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Як це працює</span>
                    <h2>Від першого повідомлення до готової збірки</h2>
                </div>
                <p>Цей блок уже готовий для реального наповнення і пізніше легко буде керуватися з адмінки.</p>
            </div>

            <div class="workflow-grid">
                @foreach ($workflow as $item)
                    <article class="workflow-card">
                        <span>{{ $item['step'] }}</span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container social-banner">
            <div>
                <span class="eyebrow">Kondor в соцмережах</span>
                <h2>Веди клієнта туди, де він може побачити ваш стиль збірок</h2>
                <p>Telegram і Instagram уже інтегровані в шаблон як основні точки контакту. Далі можна додати віджети, галереї, відгуки та реальний контент.</p>
            </div>

            <div class="social-banner__actions">
                <a class="button button--primary" href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Відкрити Instagram</a>
                <a class="button button--ghost" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Написати в Telegram</a>
            </div>
        </div>
    </section>
@endsection
