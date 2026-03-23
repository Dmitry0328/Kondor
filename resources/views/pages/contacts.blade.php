@extends('layouts.storefront')

@section('title', 'Kondor PC | Контакти')
@section('description', 'Контакти магазину Kondor, канали зв’язку та FAQ по замовленню збірок.')

@section('content')
    <section class="page-hero">
        <div class="container page-hero__inner">
            <div>
                <span class="eyebrow">Контакти та консультація</span>
                <h1>Зв’яжіться з нами зручним способом</h1>
                <p>Сторінка вже готова під реальний магазин: контактні канали, сценарій замовлення та блок популярних запитань.</p>
            </div>

            <div class="page-hero__badge">
                <strong>24/7</strong>
                <span>Онлайн-запити через соцмережі</span>
            </div>
        </div>
    </section>

    <section class="section section--dense">
        <div class="container dual-grid">
            <article class="surface-card surface-card--contact">
                <span class="eyebrow">Основний канал</span>
                <h2>Телефон та швидка комунікація</h2>
                <div class="contact-list">
                    <a href="tel:{{ $store['brand']['phone'] }}">{{ $store['brand']['phone_display'] }}</a>
                    <a href="{{ $store['brand']['telegram'] }}" target="_blank" rel="noreferrer">Telegram канал / чат</a>
                    <a href="{{ $store['brand']['instagram'] }}" target="_blank" rel="noreferrer">Instagram з прикладами збірок</a>
                </div>
            </article>

            <article class="surface-card">
                <span class="eyebrow">Що можна узгодити</span>
                <h2>Перед стартом замовлення</h2>
                <ul class="check-list">
                    <li>Підбір конфігурації під бюджет і ігри</li>
                    <li>Стиль збірки: темний, світлий, stealth або showcase</li>
                    <li>План апгрейду на кілька років вперед</li>
                    <li>Підбір монітора та периферії під готовий ПК</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">FAQ</span>
                    <h2>Питання, які клієнти ставлять найчастіше</h2>
                </div>
                <p>Блок спеціально зроблений у форматі, який потім легко перенести в адмінку або CMS.</p>
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

    <section class="section section--accent">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Як будемо працювати далі</span>
                    <h2>Після шаблону можна переходити до функціоналу</h2>
                </div>
                <p>Каталог, сторінки товарів, тема й адаптивність уже готові. Наступний крок: база даних, адмінка, кошик, замовлення, динамічні фільтри.</p>
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
@endsection
