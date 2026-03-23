@extends('layouts.storefront')

@section('title', 'Kondor PC | Головна')
@section('description', 'Магазин готових ігрових збірок Kondor з адаптивним каталогом та сторінками товарів.')

@section('content')
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__content">
                <span class="hero__eyebrow">Kondor PC</span>
                <h1>Готові ігрові ПК та збірки під замовлення</h1>
                <p>Підбираємо комп’ютери під 1080p, 1440p та 4K. На цій версії сайту залишаємо тільки збірки: каталог, сторінки товарів, контакти та консультацію.</p>

                <div class="hero__actions">
                    <a class="button button--primary" href="{{ route('store.catalog') }}">Наші збірки</a>
                    <a class="button button--secondary" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Консультація в Telegram</a>
                </div>

                <div class="hero__badges">
                    <span>Гарантія та тестування</span>
                    <span>Підбір під бюджет</span>
                    <span>Готово до відправки</span>
                </div>
            </div>

            <div class="hero__showcase">
                @include('partials.rig-visual', [
                    'product' => $heroProduct,
                    'variant' => 'hero',
                    'caption' => $heroProduct['tagline'],
                ])
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-header__eyebrow">Наші збірки</span>
                    <h2>Популярні конфігурації</h2>
                </div>
                <a class="section-header__link" href="{{ route('store.catalog') }}">Дивитися всі збірки</a>
            </div>

            <div class="product-grid">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--muted">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-header__eyebrow">Чому Kondor</span>
                    <h2>Збірки без зайвих категорій і шуму</h2>
                </div>
            </div>

            <div class="advantage-grid">
                @foreach ($benefits as $benefit)
                    <article class="advantage-card">
                        <h3>{{ $benefit['title'] }}</h3>
                        <p>{{ $benefit['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container cta-banner">
            <div>
                <span class="section-header__eyebrow">Потрібна інша конфігурація?</span>
                <h2>Підберемо збірку під ваші ігри та бюджет</h2>
                <p>Якщо хочете змінити корпус, процесор, відеокарту, обсяг пам’яті або стиль підсвітки, напишіть нам у Telegram або Instagram.</p>
            </div>

            <div class="cta-banner__actions">
                <a class="button button--primary" href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Написати в Telegram</a>
                <a class="button button--secondary" href="{{ route('store.contacts') }}">Контакти</a>
            </div>
        </div>
    </section>
@endsection
