@extends('layouts.storefront')

@section('title', 'Kondor PC | Наші збірки')
@section('description', 'Каталог ігрових збірок Kondor з фільтрами по класу продуктивності.')

@section('content')
    <section class="page-head">
        <div class="container page-head__inner">
            <div>
                <span class="section-header__eyebrow">Каталог</span>
                <h1>Наші збірки</h1>
                <p>Тільки готові ПК та конфігурації під замовлення. Без моніторів і супутніх категорій.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <form class="catalog-toolbar" action="{{ route('store.catalog') }}" method="get">
                <div class="catalog-search">
                    <label class="sr-only" for="catalog-search">Пошук по збірках</label>
                    <input
                        id="catalog-search"
                        name="q"
                        type="search"
                        value="{{ $searchQuery }}"
                        placeholder="Пошук по назві, CPU або GPU"
                    >
                </div>

                <div class="catalog-chips">
                    <a class="chip {{ $selectedTier === '' ? 'is-active' : '' }}" href="{{ route('store.catalog', ['q' => $searchQuery ?: null]) }}">Усі збірки</a>
                    @foreach ($tiers as $tier)
                        <a class="chip {{ $selectedTier === $tier['slug'] ? 'is-active' : '' }}" href="{{ route('store.catalog', ['tier' => $tier['slug'], 'q' => $searchQuery ?: null]) }}">
                            {{ $tier['name'] }}
                        </a>
                    @endforeach
                </div>

                <button class="button button--secondary button--small" type="submit">Пошук</button>
            </form>

            <div class="product-grid">
                @forelse ($filteredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="catalog-empty">
                        <h2>Нічого не знайдено</h2>
                        <p>Спробуйте прибрати фільтр або очистити пошук, щоб знову побачити всі збірки.</p>
                        <a class="button button--primary" href="{{ route('store.catalog') }}">Показати всі збірки</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
