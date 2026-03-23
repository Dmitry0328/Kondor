@extends('layouts.storefront')

@section('title', 'Kondor PC | Каталог')
@section('description', 'Каталог готових збірок Kondor з адаптивною сіткою товарів, фільтрами та пошуком.')

@section('content')
    <section class="page-hero">
        <div class="container page-hero__inner">
            <div>
                <span class="eyebrow">Каталог Kondor</span>
                <h1>Готові збірки і напрямки для майбутнього магазину</h1>
                <p>У шаблоні вже є фільтрація, пошук, картки товарів та адаптивна сітка. Далі залишиться під’єднати базу, адмінку й справжні товари.</p>
            </div>

            <div class="page-hero__badge">
                <strong>{{ $filteredProducts->count() }}</strong>
                <span>активних збірок у шаблоні</span>
            </div>
        </div>
    </section>

    <section class="section section--dense">
        <div class="container">
            <form class="filters" action="{{ route('store.catalog') }}" method="get">
                <div class="search-box">
                    <label class="sr-only" for="catalog-search">Пошук по каталогу</label>
                    <input
                        id="catalog-search"
                        name="q"
                        type="search"
                        value="{{ $searchQuery }}"
                        placeholder="Пошук по назві, CPU або GPU"
                    >
                </div>

                <div class="filter-chips">
                    <a class="chip {{ $selectedTier === '' ? 'is-active' : '' }}" href="{{ route('store.catalog', ['q' => $searchQuery ?: null]) }}">Усі рівні</a>
                    @foreach ($tiers as $tier)
                        <a
                            class="chip {{ $selectedTier === $tier['slug'] ? 'is-active' : '' }}"
                            href="{{ route('store.catalog', ['tier' => $tier['slug'], 'q' => $searchQuery ?: null]) }}"
                        >
                            {{ $tier['name'] }}
                        </a>
                    @endforeach
                </div>

                <button class="button button--ghost button--small" type="submit">Застосувати</button>
            </form>

            @if ($selectedTierName || $searchQuery)
                <div class="catalog-state">
                    <span>Активні фільтри:</span>
                    @if ($selectedTierName)
                        <strong>{{ $selectedTierName }}</strong>
                    @endif
                    @if ($searchQuery)
                        <strong>“{{ $searchQuery }}”</strong>
                    @endif
                </div>
            @endif

            <div class="product-grid">
                @forelse ($filteredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="empty-state">
                        <h2>За цим фільтром поки нічого не знайдено</h2>
                        <p>Скиньте фільтр або відкрийте всі збірки, щоб продовжити перегляд.</p>
                        <a class="button button--primary" href="{{ route('store.catalog') }}">Показати всі збірки</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Напрямки каталогу</span>
                    <h2>Що ще можна буде розширити на наступному етапі</h2>
                </div>
                <p>Ці категорії вже закладені в навігацію й готові для майбутніх сторінок або прив’язки до бази даних.</p>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <article class="category-card category-card--compact">
                        <span>{{ $category['section'] }}</span>
                        <h3>{{ $category['name'] }}</h3>
                        <p>{{ $category['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
