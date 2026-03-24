<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Каталог збірок | KindorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <style>
            :root {
                --bg: #f6f8fc;
                --surface: #ffffff;
                --text: #18202a;
                --muted: #66707d;
                --line: #dfe5ee;
                --primary: #6f10c9;
                --primary-dark: #5809a7;
                --shadow: 0 18px 40px rgba(24, 32, 42, 0.08);
                --container: min(100% - 28px, 1440px);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-width: 320px;
                font-family: 'Manrope', sans-serif;
                color: var(--text);
                background: linear-gradient(180deg, #f7f9fc 0%, #eef3f9 100%);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .container {
                width: var(--container);
                margin: 0 auto;
            }

            .page {
                padding: 28px 0 72px;
            }

            .catalog-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                margin-bottom: 28px;
                padding: 18px 22px;
                border: 1px solid var(--line);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: var(--shadow);
            }

            .catalog-brand {
                display: grid;
                gap: 4px;
            }

            .catalog-brand__name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(28px, 3vw, 40px);
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .catalog-brand__sub {
                color: var(--muted);
                font-size: 15px;
                font-weight: 700;
            }

            .catalog-back {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                padding: 0 22px;
                border: 1px solid #4b19a1;
                border-radius: 14px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 8px 18px rgba(105, 22, 203, 0.24);
            }

            .catalog-hero {
                margin-bottom: 26px;
                padding: 26px;
                border: 1px solid var(--line);
                border-radius: 28px;
                background:
                    radial-gradient(circle at top right, rgba(95, 208, 255, 0.18), transparent 28%),
                    radial-gradient(circle at left center, rgba(142, 81, 255, 0.14), transparent 30%),
                    var(--surface);
                box-shadow: var(--shadow);
            }

            .catalog-hero__eyebrow {
                color: #6c7684;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            }

            .catalog-hero h1 {
                margin: 10px 0 12px;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(34px, 4vw, 56px);
                line-height: 0.96;
                letter-spacing: -0.05em;
            }

            .catalog-hero p {
                max-width: 760px;
                margin: 0;
                color: #5f6875;
                font-size: 18px;
                line-height: 1.6;
            }

            .builds-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 24px;
            }

            .build-card {
                --build-start: #595fff;
                --build-end: #18c3ff;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                border: 1px solid #e7ebf1;
                border-radius: 26px;
                background: linear-gradient(180deg, #ffffff, #fbfbfd);
                box-shadow: var(--shadow);
            }

            .build-card--violet {
                --build-start: #5e67ff;
                --build-end: #1fa7ff;
            }

            .build-card--magenta {
                --build-start: #8f58ff;
                --build-end: #ff56cc;
            }

            .build-card--amber {
                --build-start: #6e4937;
                --build-end: #d18a54;
            }

            .build-card--peach {
                --build-start: #cb8d79;
                --build-end: #ff9b64;
            }

            .build-card--emerald {
                --build-start: #167f6f;
                --build-end: #31d19d;
            }

            .build-card__media {
                position: relative;
                min-height: 220px;
                background: linear-gradient(135deg, var(--build-start), var(--build-end));
            }

            .build-card__media::before {
                content: '';
                position: absolute;
                inset: 28px 22% 40px 22%;
                border-radius: 18px;
                background: #0d1017;
                box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.06);
            }

            .build-card__media::after {
                content: '';
                position: absolute;
                left: 50%;
                bottom: 44px;
                width: 92px;
                height: 8px;
                border-radius: 999px;
                transform: translateX(-50%);
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.92), transparent);
            }

            .build-card__body {
                display: flex;
                flex: 1;
                flex-direction: column;
                padding: 20px;
            }

            .build-card__title {
                margin: 0 0 12px;
                font-size: 24px;
                line-height: 1.1;
                letter-spacing: -0.03em;
            }

            .build-card__specs {
                display: grid;
                gap: 10px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .build-card__specs li {
                color: #2a3440;
                font-size: 16px;
                line-height: 1.42;
            }

            .build-card__price-label {
                display: block;
                margin-top: 18px;
                color: #6a7380;
                font-size: 15px;
                font-weight: 700;
            }

            .build-card__price {
                display: inline-block;
                margin-top: 6px;
                color: #1d2430;
                font-size: 24px;
                font-weight: 800;
                text-decoration: underline;
                text-underline-offset: 3px;
            }

            .build-card__action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                min-height: 44px;
                margin-top: 18px;
                padding: 0 22px;
                border: 1px solid #4b19a1;
                border-radius: 14px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 8px 18px rgba(105, 22, 203, 0.24);
            }

            @media (max-width: 1100px) {
                .builds-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (max-width: 820px) {
                .catalog-top {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .catalog-back {
                    width: 100%;
                }

                .builds-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 560px) {
                .page {
                    padding-top: 16px;
                }

                .catalog-top,
                .catalog-hero {
                    border-radius: 22px;
                    padding: 18px;
                }

                .catalog-hero p {
                    font-size: 16px;
                }

                .builds-grid {
                    grid-template-columns: 1fr;
                }

                .build-card__media {
                    min-height: 200px;
                }
            }
        </style>
    </head>
    <body>
        @php
            $builds = [
                ['tone' => 'violet', 'name' => 'Ігровий ПК "Phantom"', 'gpu' => 'Nvidia RTX 4070 Super', 'cpu' => 'AMD Ryzen 7 7700', 'ram' => '32GB DDR5 6000 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '69 990 ₴'],
                ['tone' => 'magenta', 'name' => 'Ігровий ПК "Nova"', 'gpu' => 'AMD Radeon RX 7800 XT', 'cpu' => 'AMD Ryzen 7 7800X3D', 'ram' => '32GB DDR5 6000 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '82 990 ₴'],
                ['tone' => 'amber', 'name' => 'Ігровий ПК "Vector"', 'gpu' => 'Nvidia RTX 4060 Ti 16GB', 'cpu' => 'Intel Core i5-14600KF', 'ram' => '32GB DDR5 6400 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '61 990 ₴'],
                ['tone' => 'peach', 'name' => 'Ігровий ПК "Crystal"', 'gpu' => 'Nvidia RTX 5070', 'cpu' => 'AMD Ryzen 7 9700X', 'ram' => '32GB DDR5 6400 MHz', 'storage' => 'SSD M.2 NVMe 2TB', 'price' => '94 990 ₴'],
                ['tone' => 'emerald', 'name' => 'Ігровий ПК "Storm"', 'gpu' => 'AMD Radeon RX 7900 GRE', 'cpu' => 'AMD Ryzen 5 9600X', 'ram' => '32GB DDR5 6000 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '74 990 ₴'],
                ['tone' => 'violet', 'name' => 'Ігровий ПК "Orbit"', 'gpu' => 'Nvidia RTX 3060 12GB', 'cpu' => 'Intel Core i5-13400F', 'ram' => '16GB DDR4 3600 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '42 990 ₴'],
                ['tone' => 'magenta', 'name' => 'Ігровий ПК "Titan"', 'gpu' => 'Nvidia RTX 5080', 'cpu' => 'Intel Core i7-14700KF', 'ram' => '32GB DDR5 7200 MHz', 'storage' => 'SSD M.2 NVMe 2TB', 'price' => '129 990 ₴'],
                ['tone' => 'amber', 'name' => 'Ігровий ПК "Frost"', 'gpu' => 'Nvidia RTX 4070 Ti Super', 'cpu' => 'AMD Ryzen 7 8700F', 'ram' => '32GB DDR5 6000 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '78 990 ₴'],
                ['tone' => 'peach', 'name' => 'Ігровий ПК "Pulse"', 'gpu' => 'AMD Radeon RX 7700 XT', 'cpu' => 'AMD Ryzen 5 7600', 'ram' => '32GB DDR5 5600 MHz', 'storage' => 'SSD M.2 NVMe 1TB', 'price' => '58 990 ₴'],
                ['tone' => 'emerald', 'name' => 'Ігровий ПК "Atlas"', 'gpu' => 'Nvidia RTX 4090', 'cpu' => 'AMD Ryzen 9 9950X', 'ram' => '64GB DDR5 6400 MHz', 'storage' => 'SSD M.2 NVMe 4TB', 'price' => '189 990 ₴'],
            ];
        @endphp

        <main class="page">
            <div class="container">
                <div class="catalog-top">
                    <div class="catalog-brand">
                        <span class="catalog-brand__name">KindorPC</span>
                        <span class="catalog-brand__sub">Каталог збірок</span>
                    </div>

                    <a class="catalog-back" href="{{ url('/') }}">На головну</a>
                </div>

                <section class="catalog-hero">
                    <span class="catalog-hero__eyebrow">Каталог</span>
                    <h1>Більше збірок KondorPC</h1>
                    <p>
                        Тут зібрані готові ігрові ПК та конфігурації для різних бюджетів. Далі ми можемо розширити цю сторінку
                        фільтрами, категоріями, окремими сторінками товару та реальною базою даних.
                    </p>
                </section>

                <section class="builds-grid">
                    @foreach ($builds as $build)
                        <article class="build-card build-card--{{ $build['tone'] }}">
                            <div class="build-card__media" aria-hidden="true"></div>

                            <div class="build-card__body">
                                <h2 class="build-card__title">{{ $build['name'] }}</h2>

                                <ul class="build-card__specs">
                                    <li>{{ $build['gpu'] }}</li>
                                    <li>{{ $build['cpu'] }}</li>
                                    <li>{{ $build['ram'] }}</li>
                                    <li>{{ $build['storage'] }}</li>
                                </ul>

                                <span class="build-card__price-label">Ціна за збірку</span>
                                <span class="build-card__price">{{ $build['price'] }}</span>
                                <a class="build-card__action" href="{{ url('/') }}">Детальніше</a>
                            </div>
                        </article>
                    @endforeach
                </section>
            </div>
        </main>
    </body>
</html>
