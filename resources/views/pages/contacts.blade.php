@extends('layouts.storefront')

@section('title', 'Kondor PC | Контакти')
@section('description', 'Контакти магазину Kondor та консультація по збірках.')

@section('content')
    <section class="page-head">
        <div class="container page-head__inner">
            <div>
                <span class="section-header__eyebrow">Контакти</span>
                <h1>Зв’язок і консультація</h1>
                <p>Якщо хочете підібрати або змінити збірку, напишіть нам у Telegram чи Instagram або зателефонуйте.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container contact-grid">
            <article class="contact-card">
                <h2>Телефон</h2>
                <a href="tel:{{ $store['brand']['phone'] }}">{{ $store['brand']['phone_display'] }}</a>
            </article>

            <article class="contact-card">
                <h2>Telegram</h2>
                <a href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Перейти до Telegram</a>
            </article>

            <article class="contact-card">
                <h2>Instagram</h2>
                <a href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Відкрити Instagram</a>
            </article>
        </div>
    </section>

    <section class="section section--muted">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-header__eyebrow">FAQ</span>
                    <h2>Поширені питання</h2>
                </div>
            </div>

            <div class="faq-grid">
                @foreach ($faqs as $faq)
                    <article class="faq-card">
                        <h3>{{ $faq['question'] }}</h3>
                        <p>{{ $faq['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
