<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Каталог збірок | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <style>
            :root {
                --bg: #ffffff;
                --surface: #ffffff;
                --text: #18202a;
                --muted: #646d79;
                --line: #dfe3eb;
                --primary: #6f10c9;
                --primary-dark: #5809a7;
                --shadow: 0 18px 45px rgba(24, 32, 42, 0.08);
                --container: min(calc(100% - 28px), 1920px);
                --catalog-container: min(calc(100% - 28px), 1440px);
                --win-border: #c9d0da;
                --win-surface-top: #ffffff;
                --win-surface-bottom: #eef2f6;
                --win-surface-hover: #f7f9fc;
                --win-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95), 0 1px 2px rgba(16, 24, 40, 0.08);
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                min-width: 320px;
                font-family: 'Manrope', sans-serif;
                color: var(--text);
                background: linear-gradient(180deg, #f7f9fc 0%, #eef3f9 100%);
            }

            body.is-fps-sheet-open {
                overflow: hidden;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            button,
            input {
                font: inherit;
            }

            .page-shell {
                width: 100%;
                min-height: 100vh;
                background: var(--bg);
            }

            .container {
                width: var(--container);
                margin: 0 auto;
            }

            .catalog-wrap {
                width: var(--catalog-container);
                margin: 0 auto;
            }

            .topbar {
                background: #2b272b;
                color: #ffffff;
                font-size: 14px;
            }

            .topbar__inner,
            .topbar__links,
            .topbar__meta,
            .topbar__contacts,
            .topbar__socials,
            .header__inner,
            .header__actions,
            .header-cart,
            .brand,
            .dropdown__columns,
            .dropdown__group {
                display: flex;
                align-items: center;
            }

            .topbar__inner {
                justify-content: space-between;
                min-height: 38px;
                gap: 22px;
            }

            .topbar__links {
                gap: 26px;
            }

            .topbar__meta {
                margin-left: auto;
                gap: 30px;
            }

            .topbar__contacts {
                gap: 20px;
            }

            .topbar__socials {
                gap: 16px;
            }

            .topbar a {
                font-weight: 700;
                opacity: 0.96;
                line-height: 1;
            }

            .topbar__social-link {
                justify-content: center;
                width: 18px;
                height: 18px;
            }

            .header {
                position: sticky;
                top: 0;
                border-bottom: 1px solid var(--line);
                background: var(--surface);
                z-index: 80;
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
                transition: background-color 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease, backdrop-filter 0.22s ease;
            }

            .header.is-stuck {
                background: rgba(255, 255, 255, 0.84);
                border-bottom-color: rgba(214, 222, 234, 0.88);
                box-shadow: 0 14px 28px rgba(24, 32, 42, 0.1);
            }

            .header__inner {
                justify-content: space-between;
                gap: 18px;
                min-height: 78px;
            }

            .brand {
                gap: 16px;
                min-width: 180px;
            }

            .brand__name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 31px;
                font-weight: 700;
                letter-spacing: -0.04em;
                color: #11151a;
            }

            .brand__sub {
                display: block;
                margin-top: 2px;
                color: var(--muted);
                font-size: 12px;
                font-weight: 700;
            }

            .header__actions {
                flex: 1;
                justify-content: flex-end;
                gap: 12px;
            }

            .header-button {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 44px;
                padding: 0 24px;
                border: 1px solid var(--win-border);
                border-radius: 12px;
                background: #ffffff;
                color: #1a212d;
                font-size: 14px;
                font-weight: 800;
                cursor: pointer;
                box-shadow: 0 6px 16px rgba(24, 32, 42, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.92);
                transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.18s ease;
            }

            .header-button svg {
                flex: none;
            }

            .header-button--primary {
                border-color: #4b19a1;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 8px 18px rgba(105, 22, 203, 0.24);
            }

            .header-button--primary:hover,
            .header-button--primary:focus-visible,
            .header-button--primary.is-open {
                border-color: #4b19a1;
                background: linear-gradient(180deg, #8f2fff, #7420d3);
                color: #ffffff;
            }

            .header-button:not(.header-button--primary):hover,
            .header-button:not(.header-button--primary).is-open {
                border-color: #bcc7d6;
                background: #ffffff;
                box-shadow: 0 8px 18px rgba(24, 32, 42, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.92);
            }

            .header-button:active {
                transform: translateY(1px);
                box-shadow: inset 0 2px 4px rgba(16, 24, 40, 0.12);
            }

            .search-box {
                display: flex;
                align-items: center;
                width: min(100%, 430px);
                min-height: 42px;
                border: 1px solid var(--win-border);
                border-radius: 999px;
                background: linear-gradient(180deg, #ffffff, #f4f7fb);
                box-shadow: var(--win-shadow);
                overflow: hidden;
            }

            .search-box input {
                flex: 1;
                min-width: 0;
                height: 42px;
                padding: 0 16px;
                border: 0;
                outline: none;
                background: transparent;
                color: var(--text);
            }

            .search-box button {
                width: 42px;
                height: 42px;
                border: 0;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #fff;
                cursor: pointer;
                box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.14);
            }

            .header-cart {
                justify-content: center;
                gap: 9px;
                min-height: 42px;
                padding: 0 16px;
                border: 1px solid var(--line);
                border-radius: 999px;
                background: #ffffff;
                color: #1a212d;
                font-size: 14px;
                font-weight: 800;
                box-shadow: 0 10px 24px rgba(24, 32, 42, 0.08);
                white-space: nowrap;
            }

            .header-cart:hover {
                border-color: #ccd3dd;
                background: #fbfcfe;
            }

            .header-cart svg {
                color: #9298a5;
            }

            .dropdown {
                position: absolute;
                top: calc(100% - 1px);
                left: 50%;
                width: min(1080px, calc(100% - 46px));
                transform: translateX(-50%);
                border: 1px solid var(--line);
                background: #fff;
                box-shadow: var(--shadow);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.18s ease;
            }

            .dropdown.is-open {
                opacity: 1;
                pointer-events: auto;
            }

            .dropdown__columns {
                align-items: stretch;
                gap: 48px;
                padding: 30px 34px;
            }

            .dropdown__group {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                min-width: 200px;
            }

            .dropdown__group h3 {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 19px;
            }

            .dropdown__group a {
                color: #242c37;
                font-size: 16px;
                font-weight: 600;
                transition: color 0.2s ease;
            }

            .dropdown__group a:hover {
                color: var(--primary);
            }

            .dropdown--consultation {
                width: 230px;
                left: 0;
                transform: none;
            }

            .dropdown--consultation .dropdown__columns {
                display: block;
                padding: 12px 0;
            }

            .dropdown--consultation .dropdown__group {
                min-width: 0;
                gap: 0;
            }

            .dropdown--consultation .dropdown__group a {
                display: block;
                width: 100%;
                padding: 18px 34px;
                color: #1d2430;
                font-size: 16px;
                font-weight: 600;
                line-height: 1.35;
            }

            .dropdown--consultation .dropdown__group a:hover {
                background: #faf7ff;
                color: var(--primary);
            }

            .page {
                padding: 28px 0 72px;
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
                overflow-wrap: anywhere;
            }

            .catalog-hero p {
                max-width: 760px;
                margin: 0;
                color: #5f6875;
                font-size: 18px;
                line-height: 1.6;
            }

            .builds {
                padding: 10px 0 0;
            }

            .fps-lab {
                position: relative;
                margin-bottom: 34px;
                padding: 14px;
                border-radius: 30px;
                background:
                    radial-gradient(circle at 12% 22%, rgba(132, 36, 240, 0.08), transparent 24%),
                    radial-gradient(circle at 88% 38%, rgba(48, 215, 255, 0.1), transparent 24%),
                    linear-gradient(135deg, #ffffff 0%, #f4f7fb 55%, #eef3f8 100%);
                border: 1px solid #e1e8f0;
                box-shadow: 0 18px 42px rgba(24, 32, 42, 0.08);
                overflow: hidden;
            }

            .fps-lab::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(115deg, transparent 0 42%, rgba(255, 255, 255, 0.55) 42% 43%, transparent 43% 100%),
                    linear-gradient(180deg, rgba(255, 255, 255, 0.42), transparent 22%);
                pointer-events: none;
            }

            .fps-lab__mobile-overlay,
            .fps-lab__mobile-sheet-head {
                display: none;
            }

            .fps-lab__inner {
                position: relative;
                display: grid;
                grid-template-columns: minmax(0, 1.5fr) minmax(250px, 336px);
                gap: 14px;
                align-items: stretch;
            }

            .fps-lab__controls,
            .fps-lab__scene {
                position: relative;
                border: 1px solid #dde5ef;
                border-radius: 24px;
                overflow: hidden;
            }

            .fps-lab__controls {
                display: grid;
                gap: 14px;
                padding: 18px 18px 14px;
                background: linear-gradient(180deg, #ffffff, #f7f9fc);
            }

            .fps-lab__eyebrow {
                color: #1a212d;
                font-size: 15px;
                font-weight: 800;
                letter-spacing: 0.06em;
                text-transform: uppercase;
            }

            .fps-lab__fields {
                display: grid;
                grid-template-columns: minmax(220px, 1.2fr) repeat(2, minmax(180px, 1fr));
                gap: 12px;
            }

            .fps-lab__field {
                display: grid;
                gap: 6px;
            }

            .fps-lab__field--game {
                position: relative;
            }

            .fps-lab__field span {
                color: #6b7584;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .fps-lab__field select {
                appearance: none;
                width: 100%;
                min-height: 50px;
                padding: 0 48px 0 16px;
                border: 1px solid #d7dee8;
                border-radius: 16px;
                background:
                    linear-gradient(180deg, #ffffff, #f6f8fc),
                    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 18 18' fill='none'%3E%3Cpath d='M4 6.75L9 11.25L14 6.75' stroke='%231a212d' stroke-opacity='0.72' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 16px center / 18px 18px;
                color: #1a212d;
                font-size: 15px;
                font-weight: 700;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 4px 12px rgba(24, 32, 42, 0.04);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .fps-lab__field select:hover,
            .fps-lab__field select:focus {
                border-color: rgba(132, 36, 240, 0.4);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.96), 0 0 0 3px rgba(132, 36, 240, 0.1);
                outline: none;
            }

            .fps-lab__field select option {
                color: #151c25;
                background: #ffffff;
            }

            .fps-lab__note {
                margin: 0;
                color: #7a8391;
                font-size: 12px;
                text-align: center;
            }

            .fps-lab__scene {
                --scene-from: #0f182f;
                --scene-to: #2b1211;
                --scene-accent: #f4dc39;
                display: grid;
                align-content: end;
                min-height: 154px;
                padding: 18px;
                background: linear-gradient(135deg, #ffffff 0%, #f5f7fb 100%);
                color: #18202a;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92);
            }

            .fps-lab__scene::before,
            .fps-lab__scene::after {
                content: '';
                position: absolute;
                pointer-events: none;
            }

            .fps-lab__scene::before {
                inset: auto -10% -32% auto;
                width: 240px;
                height: 240px;
                background: radial-gradient(circle, var(--scene-accent) 0%, transparent 68%);
                filter: blur(10px);
                opacity: 0.16;
            }

            .fps-lab__scene::after {
                inset: 0;
                background:
                    linear-gradient(145deg, transparent 0 55%, rgba(24, 32, 42, 0.04) 55% 56%, transparent 56% 100%),
                    repeating-linear-gradient(115deg, transparent 0 18px, rgba(24, 32, 42, 0.03) 18px 20px);
                opacity: 0.55;
            }

            .fps-lab__scene-badge,
            .fps-lab__scene-title,
            .fps-lab__scene-meta {
                position: relative;
                z-index: 1;
            }

            .fps-lab__scene-badge {
                display: inline-flex;
                align-items: center;
                width: fit-content;
                min-height: 28px;
                margin-bottom: 10px;
                padding: 0 12px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.86);
                border: 1px solid #dce4ee;
                color: #526071;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                backdrop-filter: blur(10px);
            }

            .fps-lab__scene-title {
                display: block;
                margin: 0;
                max-width: 11ch;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(24px, 2.5vw, 34px);
                line-height: 0.96;
                letter-spacing: -0.04em;
                color: #18202a;
            }

            .fps-lab__scene-meta {
                display: block;
                margin-top: 8px;
                color: #5d6877;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.02em;
            }

            .fps-lab__mobile-sheet-head {
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            .fps-lab__mobile-sheet-copy {
                display: grid;
                gap: 4px;
            }

            .fps-lab__mobile-sheet-label {
                color: #6b7584;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .fps-lab__mobile-sheet-title {
                color: #18202a;
                font-size: 20px;
                font-weight: 800;
                line-height: 1.15;
            }

            .fps-lab__mobile-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border: 1px solid #d7dee8;
                border-radius: 14px;
                background: #ffffff;
                color: #1a212d;
                cursor: pointer;
                box-shadow: 0 8px 18px rgba(24, 32, 42, 0.08);
            }

            .builds__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                margin-bottom: 26px;
            }

            .builds__sort {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                flex-wrap: wrap;
                gap: 12px;
                margin: 0 0 16px;
            }

            .builds__sort-label {
                flex: none;
                color: #6c7482;
                font-size: 15px;
                font-weight: 700;
                white-space: nowrap;
            }

            .builds__sort-control {
                width: min(100%, 260px);
            }

            .builds__sort-select {
                appearance: none;
                width: 100%;
                min-height: 44px;
                padding: 0 46px 0 16px;
                border: 1px solid #d7dee8;
                border-radius: 12px;
                background:
                    linear-gradient(180deg, #ffffff, #f6f8fc),
                    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 18 18' fill='none'%3E%3Cpath d='M4 6.75L9 11.25L14 6.75' stroke='%231a212d' stroke-opacity='0.88' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 14px center / 18px 18px;
                color: #18202a;
                font-size: 15px;
                font-weight: 700;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.94), 0 10px 22px rgba(24, 32, 42, 0.08);
                cursor: pointer;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .builds__sort-select:hover,
            .builds__sort-select:focus {
                border-color: rgba(132, 36, 240, 0.42);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.96), 0 0 0 3px rgba(132, 36, 240, 0.1);
                outline: none;
            }

            .builds__sort-select option {
                color: #18202a;
                background: #ffffff;
            }

            .builds__header h2 {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(32px, 3vw, 44px);
                letter-spacing: -0.03em;
            }

            .catalog-cta {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                padding: 0 30px;
                border: 1px solid #4b19a1;
                border-radius: 12px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                letter-spacing: 0.03em;
                text-transform: uppercase;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 8px 18px rgba(105, 22, 203, 0.24);
                transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            }

            .catalog-cta:hover {
                background: linear-gradient(180deg, #8f2fff, #7420d3);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 10px 22px rgba(105, 22, 203, 0.28);
                transform: translateY(-1px);
            }

            .builds__grid {
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 24px;
            }

            .build-card {
                --build-start: #595fff;
                --build-end: #18c3ff;
                --build-glow: rgba(110, 136, 255, 0.96);
                --fps-ratio: 0.55;
                --fps-size: 34px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                border: 1px solid #e7ebf1;
                border-radius: 26px;
                background: linear-gradient(180deg, #ffffff, #fbfbfd);
                box-shadow: 0 18px 40px rgba(24, 32, 42, 0.08);
            }

            .build-card[data-product-url] {
                cursor: pointer;
            }

            .build-card[data-product-url]:focus-visible {
                outline: 3px solid rgba(111, 16, 201, 0.3);
                outline-offset: 3px;
            }

            .build-card--violet {
                --build-start: #5e67ff;
                --build-end: #1fa7ff;
                --build-glow: rgba(104, 119, 255, 0.96);
            }

            .build-card--magenta {
                --build-start: #8f58ff;
                --build-end: #ff56cc;
                --build-glow: rgba(241, 94, 255, 0.96);
            }

            .build-card--amber {
                --build-start: #6e4937;
                --build-end: #d18a54;
                --build-glow: rgba(255, 178, 84, 0.94);
            }

            .build-card--peach {
                --build-start: #b07064;
                --build-end: #ff9f69;
                --build-glow: rgba(255, 152, 110, 0.94);
            }

            .build-card--emerald {
                --build-start: #18564b;
                --build-end: #2dcc98;
                --build-glow: rgba(94, 255, 166, 0.92);
            }

            .build-card__media {
                position: relative;
                display: block;
                min-height: 242px;
                background:
                    radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.22), transparent 32%),
                    linear-gradient(135deg, var(--build-start), var(--build-end));
            }

            .build-card__media::before {
                content: '';
                position: absolute;
                left: 50%;
                top: 12%;
                width: 58%;
                height: 76%;
                transform: translateX(-50%);
                border: 3px solid #161a1d;
                border-radius: 14px;
                background:
                    radial-gradient(circle at 72% 20%, var(--build-glow) 0 9%, rgba(255, 255, 255, 0.08) 10% 13%, transparent 14%),
                    radial-gradient(circle at 72% 48%, var(--build-glow) 0 9%, rgba(255, 255, 255, 0.08) 10% 13%, transparent 14%),
                    radial-gradient(circle at 72% 76%, var(--build-glow) 0 9%, rgba(255, 255, 255, 0.08) 10% 13%, transparent 14%),
                    radial-gradient(circle at 30% 34%, var(--build-glow) 0 10%, rgba(255, 255, 255, 0.08) 11% 14%, transparent 15%),
                    linear-gradient(180deg, rgba(12, 15, 18, 0.96), rgba(6, 9, 12, 0.96));
                box-shadow: 0 20px 28px rgba(0, 0, 0, 0.24);
            }

            .build-card__media::after {
                content: '';
                position: absolute;
                left: 50%;
                bottom: 11%;
                width: 28%;
                height: 6px;
                transform: translateX(-50%);
                border-radius: 999px;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.88), transparent);
            }

            .build-card__body {
                display: flex;
                flex: 1;
                flex-direction: column;
                padding: 18px 20px 22px;
            }

            .build-card__title {
                margin: 0 0 14px;
                font-size: 24px;
                line-height: 1.08;
                letter-spacing: -0.03em;
            }

            .build-card__content {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 74px;
                gap: 14px;
                align-items: start;
                margin-bottom: auto;
            }

            .build-card__info {
                min-width: 0;
            }

            .build-card__copy-wrap {
                position: relative;
            }

            .build-card__copy-wrap.is-collapsible {
                overflow: hidden;
            }

            .build-card__copy-wrap.is-collapsible.is-collapsed {
                max-height: var(--build-copy-max, 176px);
            }

            .build-card__copy-wrap.is-collapsible.is-collapsed::after {
                content: '';
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 44px;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0), #ffffff 88%);
                pointer-events: none;
            }

            .build-card__specs {
                display: grid;
                gap: 12px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .build-card__specs li {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                color: #27303c;
                font-size: 16px;
                line-height: 1.42;
            }

            .build-card__specs svg {
                flex: none;
                width: 17px;
                height: 17px;
                margin-top: 3px;
                color: #46515f;
            }

            .build-card__copy-toggle {
                display: none;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: fit-content;
                max-width: 100%;
                min-height: 36px;
                margin: 10px auto 0;
                padding: 0 10px;
                border: 0;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.28);
                color: #5c6778;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: -0.01em;
                text-align: center;
                backdrop-filter: blur(6px);
                -webkit-backdrop-filter: blur(6px);
                box-shadow: none;
                cursor: pointer;
                transition: transform 0.18s ease, background 0.18s ease, color 0.18s ease;
            }

            .build-card__copy-toggle:hover {
                transform: translateY(-1px);
                background: rgba(255, 255, 255, 0.38);
                color: #4a5567;
            }

            .build-card__copy-toggle-label {
                display: block;
                line-height: 1.1;
            }

            .build-card__copy-toggle-chevron {
                flex: none;
                width: 12px;
                height: 12px;
                color: currentColor;
                transition: transform 0.18s ease;
            }

            .build-card__copy-toggle.is-expanded .build-card__copy-toggle-chevron {
                transform: rotate(180deg);
            }

            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            .build-card__fps-side {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            @media (min-width: 761px) {
                .build-card__content {
                    flex: 1 1 auto;
                    margin-bottom: 0;
                    align-items: stretch;
                }

                .build-card__fps-side {
                    justify-content: flex-end;
                }
            }

            .build-card__fps {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-end;
                min-height: 162px;
                padding: 10px 10px 12px;
                border-radius: 16px;
                border: 1px solid #dbe3ee;
                background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.94), 0 8px 18px rgba(24, 32, 42, 0.05);
                overflow: hidden;
            }

            .build-card__fps-value {
                min-height: 40px;
                color: #111111;
                font-family: 'Space Grotesk', sans-serif;
                font-size: var(--fps-size);
                font-weight: 700;
                line-height: 1;
                letter-spacing: -0.04em;
                text-align: center;
                transform-origin: center bottom;
                transition: font-size 0.45s cubic-bezier(0.22, 1, 0.36, 1), transform 0.32s ease;
            }

            .build-card__fps-scale {
                position: relative;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                width: 16px;
                height: 92px;
                margin: 10px 0 8px;
            }

            .build-card__fps-scale::before {
                content: '';
                position: absolute;
                inset: 0 5px;
                border-radius: 999px;
                background: #e6ebf2;
            }

            .build-card__fps-fill {
                position: absolute;
                left: 5px;
                right: 5px;
                bottom: 0;
                height: calc(14px + (var(--fps-ratio) * 78px));
                border-radius: 999px;
                background: linear-gradient(180deg, #ff4f97 0%, #8a46ff 55%, #2f9cff 100%);
                box-shadow: 0 0 14px rgba(131, 70, 255, 0.16);
                transition: height 0.5s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease;
            }

            .build-card__fps-label {
                color: #334050;
                font-size: 14px;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
            }

            .build-card__fps-more {
                display: none;
                align-items: center;
                justify-content: center;
                min-height: 36px;
                padding: 8px 10px;
                border: 1px solid #d8e0ea;
                border-radius: 12px;
                background: linear-gradient(180deg, #ffffff, #f5f8fc);
                color: #243041;
                font-size: 12px;
                font-weight: 800;
                line-height: 1.2;
                text-align: center;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.94), 0 6px 14px rgba(24, 32, 42, 0.06);
                cursor: pointer;
            }

            .build-card.is-fps-animating .build-card__fps-value {
                transform: scale(1.09);
            }

            .build-card.is-fps-high .build-card__fps-fill {
                box-shadow: 0 0 16px rgba(133, 70, 255, 0.22);
            }

            .build-card__price-label {
                display: block;
                margin-top: 20px;
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
                margin-top: 0;
                padding: 0 22px;
                border: 1px solid #4b19a1;
                border-radius: 14px;
                background: linear-gradient(180deg, #8424f0, #6816cb);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18), 0 8px 18px rgba(105, 22, 203, 0.24);
            }

            .build-card__actions {
                display: grid;
                gap: 10px;
                margin-top: 18px;
            }

            .build-card__action--cart {
                border-color: #d6deea;
                background: linear-gradient(180deg, #ffffff, #f7faff);
                color: #1d2430;
                box-shadow: 0 8px 18px rgba(24, 32, 42, 0.08);
            }

            .build-card__action--cart:hover {
                border-color: #c6d3e4;
                background: linear-gradient(180deg, #ffffff, #f1f6ff);
                box-shadow: 0 10px 22px rgba(24, 32, 42, 0.1);
            }

            .build-card__action--cart.is-added {
                border-color: #178f57;
                background: linear-gradient(180deg, #2fbe75, #169659);
                color: #ffffff;
                box-shadow: 0 10px 22px rgba(22, 150, 89, 0.2);
            }

            .footer {
                position: relative;
                padding: 84px 0 0;
                background:
                    radial-gradient(circle at 12% 22%, rgba(132, 36, 240, 0.08), transparent 22%),
                    radial-gradient(circle at 86% 78%, rgba(48, 215, 255, 0.06), transparent 24%),
                    #ffffff;
                border-top: 1px solid #e7ebf2;
            }

            .footer__grid {
                display: grid;
                grid-template-columns: minmax(260px, 320px) minmax(170px, 220px) minmax(220px, 1fr);
                gap: 44px 52px;
                align-items: flex-start;
                padding-bottom: 52px;
            }

            .footer__brand {
                display: grid;
                gap: 20px;
            }

            .footer__logo {
                display: inline-flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }

            .footer__brand-name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(34px, 4vw, 48px);
                font-weight: 700;
                letter-spacing: -0.05em;
                color: #161c25;
            }

            .footer__brand-sub {
                color: #6c7583;
                font-size: 15px;
                font-weight: 700;
            }

            .footer__contacts {
                display: grid;
                gap: 12px;
            }

            .footer__contacts a {
                color: #1a212d;
                font-size: 17px;
                font-weight: 600;
                transition: color 0.18s ease;
            }

            .footer__contacts a:hover,
            .footer__nav a:hover {
                color: #6f10c9;
            }

            .footer__socials {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .footer__social {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                height: 46px;
                border: 1px solid #dbe2ec;
                border-radius: 50%;
                background: #ffffff;
                color: #1a212d;
                box-shadow: 0 10px 20px rgba(24, 32, 42, 0.06);
                transition: transform 0.18s ease, border-color 0.18s ease, color 0.18s ease;
            }

            .footer__social:hover {
                transform: translateY(-2px);
                border-color: #c6d1df;
                color: #6f10c9;
            }

            .footer__column {
                display: grid;
                gap: 20px;
                padding-top: 10px;
            }

            .footer__column--about {
                justify-self: start;
            }

            .footer__title {
                margin: 0;
                color: #151c25;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 34px;
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .footer__nav {
                display: grid;
                gap: 14px;
            }

            .footer__nav a {
                color: #1a212d;
                font-size: 18px;
                font-weight: 600;
                line-height: 1.35;
                transition: color 0.18s ease;
            }

            .footer__bottom {
                background: #2b272b;
                color: rgba(255, 255, 255, 0.96);
            }

            .footer__bottom-inner {
                min-height: 54px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                font-size: 15px;
                font-weight: 700;
            }

            .menu-toggle {
                display: none;
                width: 42px;
                height: 40px;
                border: 1px solid var(--win-border);
                border-radius: 14px;
                background: linear-gradient(180deg, var(--win-surface-top), var(--win-surface-bottom));
                box-shadow: var(--win-shadow);
                cursor: pointer;
            }

            .menu-toggle span {
                display: block;
                width: 18px;
                height: 2px;
                margin: 4px auto;
                background: #1c2430;
            }

            .mobile-menu {
                display: none;
                border-bottom: 1px solid var(--line);
                background: #fff;
            }

            .mobile-menu.is-open {
                display: block;
            }

            .mobile-menu__inner {
                display: grid;
                gap: 10px;
                padding: 16px 0 20px;
            }

            .mobile-menu a {
                display: flex;
                align-items: center;
                min-height: 44px;
                padding: 0 14px;
                border: 1px solid var(--win-border);
                border-radius: 14px;
                background: linear-gradient(180deg, var(--win-surface-top), var(--win-surface-bottom));
                color: #1a212d;
                font-weight: 700;
                box-shadow: var(--win-shadow);
            }

            @media (max-width: 1320px) {
                .search-box {
                    width: 300px;
                }

                .fps-lab__inner {
                    grid-template-columns: minmax(0, 1fr) 290px;
                }

                .fps-lab__fields {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }

                .builds__grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }

            @media (max-width: 1100px) {
                .builds__grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (max-width: 1080px) {
                .header__actions > .header-button,
                .search-box {
                    display: none;
                }

                .menu-toggle {
                    display: inline-block;
                }

                .fps-lab__inner {
                    grid-template-columns: 1fr;
                }

                .fps-lab__fields {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .fps-lab__scene {
                    min-height: 170px;
                }

                .footer__grid {
                    grid-template-columns: minmax(220px, 280px) minmax(170px, 210px) minmax(200px, 1fr);
                    gap: 38px 44px;
                }

                .footer__title {
                    font-size: 30px;
                }
            }

            @media (max-width: 820px) {
                .builds__grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 760px) {
                .container,
                .catalog-wrap {
                    width: calc(100% - 20px);
                }

                .topbar {
                    display: none;
                }

                .header__inner {
                    display: grid;
                    grid-template-columns: 48px minmax(0, 1fr) 48px;
                    align-items: center;
                    gap: 10px;
                    min-height: 78px;
                }

                .header__actions {
                    display: contents;
                }

                .brand {
                    grid-column: 2;
                    grid-row: 1;
                    align-self: center;
                    justify-self: center;
                    min-width: 0;
                }

                .brand > div {
                    text-align: center;
                }

                .brand__name {
                    font-size: 22px;
                }

                .brand__sub {
                    font-size: 11px;
                }

                .header-cart-shell {
                    grid-column: 3;
                    grid-row: 1;
                    align-self: center;
                    justify-self: end;
                }

                .header-cart {
                    width: 44px;
                    min-height: 44px;
                    padding: 0;
                    gap: 0;
                    border: 0;
                    border-radius: 0;
                    background: transparent;
                    box-shadow: none;
                }

                .header-cart span {
                    display: none;
                }

                .header-cart svg {
                    width: 32px;
                    height: 32px;
                    color: #7c8592;
                }

                .menu-toggle {
                    grid-column: 1;
                    grid-row: 1;
                    align-self: center;
                    justify-self: start;
                    display: inline-flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 5px;
                    width: 44px;
                    height: 44px;
                    padding: 0;
                    border: 0;
                    border-radius: 0;
                    background: transparent;
                    box-shadow: none;
                }

                .menu-toggle span {
                    width: 28px;
                    height: 3px;
                    margin: 0;
                    border-radius: 999px;
                    background: #596270;
                }

                .menu-toggle:hover,
                .header-cart:hover {
                    background: transparent;
                }

                .dropdown {
                    width: calc(100% - 20px);
                }

                .dropdown__columns {
                    flex-direction: column;
                    gap: 28px;
                    padding: 24px 20px;
                }

                .fps-lab {
                    padding: 0;
                    margin-bottom: 0;
                    border: 0;
                    border-radius: 0;
                    background: transparent;
                    box-shadow: none;
                    overflow: visible;
                }

                .fps-lab::before {
                    display: none;
                }

                .fps-lab__mobile-overlay {
                    display: block;
                    position: fixed;
                    inset: 0;
                    padding: 0;
                    border: 0;
                    background: rgba(12, 16, 22, 0.44);
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.24s ease;
                    z-index: 110;
                }

                .fps-lab__inner {
                    position: fixed;
                    left: 12px;
                    right: 12px;
                    bottom: 12px;
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 12px;
                    max-height: calc(100vh - 24px);
                    padding: 18px;
                    border: 1px solid #dbe3ee;
                    border-radius: 28px;
                    background: #f7f9fc;
                    box-shadow: 0 24px 52px rgba(24, 32, 42, 0.2);
                    transform: translateY(calc(100% + 24px));
                    opacity: 0;
                    pointer-events: none;
                    overflow-y: auto;
                    transition: transform 0.28s ease, opacity 0.24s ease;
                    z-index: 111;
                }

                .fps-lab.is-mobile-open .fps-lab__mobile-overlay {
                    opacity: 1;
                    pointer-events: auto;
                }

                .fps-lab.is-mobile-open .fps-lab__inner {
                    transform: translateY(0);
                    opacity: 1;
                    pointer-events: auto;
                }

                .fps-lab__mobile-sheet-head {
                    display: flex;
                }

                .fps-lab__controls {
                    padding: 18px;
                }

                .fps-lab__fields {
                    grid-template-columns: 1fr;
                }

                .fps-lab__scene {
                    min-height: 0;
                    padding: 18px;
                }

                .builds {
                    padding-bottom: 54px;
                }

                .builds__header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .builds__sort {
                    justify-content: flex-end;
                    gap: 10px;
                }

                .builds__sort-control {
                    width: min(100%, 228px);
                }

                .build-card__content {
                    grid-template-columns: minmax(0, 1fr) 68px;
                    gap: 12px;
                }

                .build-card__fps {
                    min-height: 154px;
                }

                .build-card__fps-side {
                    gap: 8px;
                }

                .build-card__copy-toggle.is-visible {
                    display: inline-flex;
                }

                .build-card__fps-more {
                    display: inline-flex;
                }

                .footer {
                    padding-top: 64px;
                }

                .footer__grid {
                    grid-template-columns: 1fr;
                    gap: 34px;
                    padding-bottom: 40px;
                }

                .footer__column {
                    padding-top: 0;
                }

                .footer__title {
                    font-size: 28px;
                }
            }

            @media (max-width: 560px) {
                .page {
                    padding-top: 16px;
                }

                .catalog-hero {
                    border-radius: 22px;
                    padding: 18px;
                }

                .catalog-hero h1 {
                    font-size: 22px;
                    line-height: 1.02;
                    letter-spacing: -0.04em;
                }

                .catalog-hero p {
                    font-size: 16px;
                }

                .builds__sort-label {
                    font-size: 13px;
                }

                .builds__sort-select {
                    min-height: 42px;
                    padding-inline: 14px 40px;
                    font-size: 14px;
                }

                .builds__grid {
                    grid-template-columns: 1fr;
                }

                .build-card__media {
                    min-height: 200px;
                }

                .build-card__body {
                    padding-inline: 18px;
                }

                .build-card__content {
                    grid-template-columns: minmax(0, 1fr) 74px;
                }

                .build-card__title {
                    font-size: 22px;
                }

                .build-card__fps-more {
                    min-height: 34px;
                    padding-inline: 8px;
                    font-size: 11px;
                }

                .build-card__copy-toggle {
                    min-height: 34px;
                    margin-top: 8px;
                    padding-inline: 12px;
                    border-radius: 11px;
                    font-size: 11px;
                }

                .footer__brand-name {
                    font-size: 36px;
                }

                .footer__nav a,
                .footer__contacts a {
                    font-size: 17px;
                }

                .footer__bottom-inner {
                    min-height: 64px;
                    padding: 10px 0;
                    font-size: 14px;
                }
            }
        </style>
    </head>
    <body>
        @php
            $fpsGames = [
                ['id' => 'cyberpunk-2077', 'name' => 'Cyberpunk 2077', 'difficulty' => 0.72, 'accent' => '#f4dc39', 'from' => '#0f182f', 'to' => '#2b1211', 'badge' => 'Night City benchmark'],
                ['id' => 'gta-5', 'name' => 'GTA 5', 'difficulty' => 1.12, 'accent' => '#8cff7c', 'from' => '#10151d', 'to' => '#183625', 'badge' => 'Los Santos test'],
                ['id' => 'counter-strike-2', 'name' => 'Counter-Strike 2', 'difficulty' => 1.65, 'accent' => '#ffb35c', 'from' => '#10151d', 'to' => '#31200f', 'badge' => 'Premier smoke test'],
                ['id' => 'fortnite', 'name' => 'Fortnite', 'difficulty' => 1.38, 'accent' => '#57d8ff', 'from' => '#10162a', 'to' => '#15384a', 'badge' => 'Island benchmark'],
                ['id' => 'valorant', 'name' => 'Valorant', 'difficulty' => 1.92, 'accent' => '#ff637b', 'from' => '#14131d', 'to' => '#321019', 'badge' => 'Ranked preset'],
                ['id' => 'stalker-2', 'name' => 'S.T.A.L.K.E.R. 2', 'difficulty' => 0.68, 'accent' => '#a3ff63', 'from' => '#131816', 'to' => '#2b2210', 'badge' => 'Zone benchmark'],
                ['id' => 'red-dead-redemption-2', 'name' => 'Red Dead Redemption 2', 'difficulty' => 0.84, 'accent' => '#ff8f5a', 'from' => '#161117', 'to' => '#3a1b13', 'badge' => 'Frontier cinematic'],
                ['id' => 'rust', 'name' => 'Rust', 'difficulty' => 0.96, 'accent' => '#ff9759', 'from' => '#12161d', 'to' => '#362117', 'badge' => 'Survival session'],
            ];

            $fpsDisplays = [
                ['id' => '1080p', 'name' => '1920 x 1080 (Full HD)', 'mobile_name' => 'Full HD', 'multiplier' => 1.22],
                ['id' => '1440p', 'name' => '2560 x 1440 (2K)', 'mobile_name' => '2K', 'multiplier' => 1.0],
                ['id' => '4k', 'name' => '3840 x 2160 (4K)', 'mobile_name' => '4K', 'multiplier' => 0.7],
            ];

            $fpsPresets = [
                ['id' => 'medium', 'name' => 'Середні', 'multiplier' => 1.18],
                ['id' => 'high', 'name' => 'Високі', 'multiplier' => 1.0],
                ['id' => 'ultra', 'name' => 'Ультра', 'multiplier' => 0.84],
            ];

            $defaultFpsGame = 'cyberpunk-2077';
            $defaultFpsDisplay = '1440p';
            $defaultFpsPreset = 'high';

            $fpsIndexById = static function (array $items): array {
                $indexed = [];

                foreach ($items as $item) {
                    $indexed[$item['id']] = $item;
                }

                return $indexed;
            };

            $fpsGameMap = $fpsIndexById($fpsGames);
            $fpsDisplayMap = $fpsIndexById($fpsDisplays);
            $fpsPresetMap = $fpsIndexById($fpsPresets);

            $computeFps = static function (int $score, string $gameId, string $displayId, string $presetId) use ($fpsGameMap, $fpsDisplayMap, $fpsPresetMap): int {
                $rawFps = $score
                    * ($fpsGameMap[$gameId]['difficulty'] ?? 1)
                    * ($fpsDisplayMap[$displayId]['multiplier'] ?? 1)
                    * ($fpsPresetMap[$presetId]['multiplier'] ?? 1);

                return (int) max(38, min(320, round($rawFps)));
            };

            $getFpsRatio = static fn (int $fps): float => max(0.18, min(1, $fps / 220));
            $getFpsSize = static fn (int $fps): int => (int) round(22 + (max(0, min(1, ($fps - 40) / 170)) * 10));
            $getFpsState = static function (int $fps): string {
                if ($fps < 70) {
                    return 'low';
                }

                if ($fps < 120) {
                    return 'mid';
                }

                return 'high';
            };

            $builds = config('kondor_storefront.builds', []);

            foreach ($builds as $index => $build) {
                $initialFps = $computeFps($build['fps_score'], $defaultFpsGame, $defaultFpsDisplay, $defaultFpsPreset);
                $builds[$index]['sort_index'] = $index;
                $builds[$index]['price_value'] = (int) preg_replace('/\D+/', '', $build['price']);
                $builds[$index]['fps_value'] = $initialFps;
                $builds[$index]['fps_ratio'] = $getFpsRatio($initialFps);
                $builds[$index]['fps_size'] = $getFpsSize($initialFps);
                $builds[$index]['fps_state'] = $getFpsState($initialFps);
            }

            $fpsClientConfig = [
                'defaults' => [
                    'game' => $defaultFpsGame,
                    'display' => $defaultFpsDisplay,
                    'preset' => $defaultFpsPreset,
                ],
                'games' => $fpsGames,
                'displays' => $fpsDisplays,
                'presets' => $fpsPresets,
            ];
        @endphp

        <div class="page-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="{{ url('/') }}#about">Про нас</a>
                        <a href="#contacts">Контакти</a>
                        <a href="{{ url('/') }}#faq">FAQ</a>
                    </div>

                    <div class="topbar__meta">
                        <div class="topbar__contacts">
                            <a href="tel:+380633631066">+380633631066</a>
                        </div>

                        <div class="topbar__socials" aria-label="Соціальні мережі">
                            <a class="topbar__social-link" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>
                                </svg>
                            </a>

                            <a class="topbar__social-link" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 4L3 11.2L10.2 13.8L12.8 21L21 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M10.2 13.8L14.2 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <header class="header">
                <div class="container header__inner">
                    <a class="brand" href="{{ url('/') }}">
                        <div>
                            <div class="brand__name">KondorPC</div>
                            <span class="brand__sub">Твоя база геймінгу</span>
                        </div>
                    </a>

                    <div class="header__actions">
                        <a class="header-button header-button--primary" href="#builds">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Наші збірки
                        </a>

                        <button class="header-button" type="button" data-dropdown-trigger="builds" aria-expanded="false" aria-controls="builds-dropdown" aria-haspopup="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                            Каталог збірок
                        </button>

                        <button class="header-button" type="button" data-dropdown-trigger="consultation" aria-expanded="false" aria-controls="consultation-dropdown" aria-haspopup="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 10V12L13.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Консультація
                        </button>

                        @auth
                            @if (auth()->user()?->is_admin)
                                <a class="header-button" href="{{ url('/admin') }}">Адмінка</a>
                            @endif
                        @endauth

                        <div class="search-box" role="search">
                            <input type="search" placeholder="Пошук збірок">
                            <button type="button" aria-label="Пошук">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M20 20L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <a class="header-cart" href="#contacts" aria-label="Кошик">
                            <span>0 ₴</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="9" cy="19" r="1.6" fill="currentColor"/>
                                <circle cx="17" cy="19" r="1.6" fill="currentColor"/>
                                <path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        @include('partials.header-cart')

                        <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                <div class="dropdown" id="builds-dropdown" data-dropdown-panel="builds">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <h3>Готові збірки</h3>
                            <a href="#builds">1080p Start</a>
                            <a href="#builds">1440p Core</a>
                            <a href="#builds">4K / Creator</a>
                            <a href="#builds">Streaming Build</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Стиль збірки</h3>
                            <a href="#builds">Black Edition</a>
                            <a href="#builds">White Edition</a>
                            <a href="#builds">RGB Showcase</a>
                            <a href="#builds">Minimal Build</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Під замовлення</h3>
                            <a href="#contacts">Підбір під бюджет</a>
                            <a href="#contacts">Апгрейд конфігурації</a>
                            <a href="#contacts">Збірка для стриму</a>
                            <a href="#contacts">Консультація</a>
                        </div>
                    </div>
                </div>

                <div class="dropdown dropdown--consultation" id="consultation-dropdown" data-dropdown-panel="consultation">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Telegram</a>
                            <a href="#contacts">Контактна форма</a>
                            <a href="tel:+380633631066">+380 63 363 10 66</a>
                            <a href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer">Instagram</a>
                        </div>
                    </div>
                </div>

                <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
                    <div class="container mobile-menu__inner">
                        <a href="{{ url('/') }}#about">Про нас</a>
                        <a href="#builds">Наші збірки</a>
                        <a href="#builds">Каталог збірок</a>
                        <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
                        <a href="#contacts">Контакти</a>
                        @auth
                            @if (auth()->user()?->is_admin)
                                <a href="{{ url('/admin') }}">Адмінка</a>
                            @endif
                        @endauth
                        <a href="{{ url('/') }}#faq">FAQ</a>
                    </div>
                </div>
            </header>

            <main class="page">
                <div class="catalog-wrap">
                    <section class="catalog-hero">
                        <span class="catalog-hero__eyebrow">Каталог</span>
                        <h1>Більше збірок KondorPC</h1>
                        <p>
                            Тут зібрані готові ігрові ПК та конфігурації для різних бюджетів. Далі ми можемо розширити цю сторінку
                            фільтрами, категоріями, окремими сторінками товару та реальною базою даних.
                        </p>
                    </section>

                    <section class="builds" id="builds">
                        <div
                            class="fps-lab"
                            data-fps-lab
                            style="--scene-from: {{ $fpsGameMap[$defaultFpsGame]['from'] }}; --scene-to: {{ $fpsGameMap[$defaultFpsGame]['to'] }}; --scene-accent: {{ $fpsGameMap[$defaultFpsGame]['accent'] }};"
                        >
                            <button class="fps-lab__mobile-overlay" type="button" data-fps-mobile-close aria-label="Закрити налаштування FPS"></button>

                            <div class="fps-lab__inner">
                                <div class="fps-lab__mobile-sheet-head">
                                    <div class="fps-lab__mobile-sheet-copy">
                                        <span class="fps-lab__mobile-sheet-label">Мобільний FPS-тест</span>
                                        <strong class="fps-lab__mobile-sheet-title">Оберіть гру та параметри</strong>
                                    </div>

                                    <button class="fps-lab__mobile-close" type="button" data-fps-mobile-close aria-label="Закрити налаштування FPS">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="fps-lab__controls">
                                    <div class="fps-lab__eyebrow">Виберіть гру і налаштування</div>

                                    <div class="fps-lab__fields">
                                        <label class="fps-lab__field fps-lab__field--game">
                                            <span>Гра</span>
                                            <select data-fps-game>
                                                @foreach ($fpsGames as $game)
                                                    <option value="{{ $game['id'] }}" @selected($game['id'] === $defaultFpsGame)>{{ $game['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </label>

                                        <label class="fps-lab__field">
                                            <span>Монітор / роздільна здатність</span>
                                            <select data-fps-display>
                                                @foreach ($fpsDisplays as $display)
                                                    <option value="{{ $display['id'] }}" @selected($display['id'] === $defaultFpsDisplay)>{{ $display['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </label>

                                        <label class="fps-lab__field">
                                            <span>Графіка</span>
                                            <select data-fps-preset>
                                                @foreach ($fpsPresets as $preset)
                                                    <option value="{{ $preset['id'] }}" @selected($preset['id'] === $defaultFpsPreset)>{{ $preset['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>

                                    <p class="fps-lab__note">*Показники FPS є усередненими і служать для демонстрації відносної продуктивності систем.</p>
                                </div>

                                <div class="fps-lab__scene">
                                    <span class="fps-lab__scene-badge" data-fps-scene-badge>{{ $fpsGameMap[$defaultFpsGame]['badge'] }}</span>
                                    <strong class="fps-lab__scene-title" data-fps-scene-title>{{ $fpsGameMap[$defaultFpsGame]['name'] }}</strong>
                                    <span class="fps-lab__scene-meta" data-fps-scene-meta>{{ $fpsDisplayMap[$defaultFpsDisplay]['name'] }} · {{ $fpsPresetMap[$defaultFpsPreset]['name'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="builds__sort" aria-label="Сортування каталогу">
                            <span class="builds__sort-label">Сортувати по</span>

                            <div class="builds__sort-control">
                                <select class="builds__sort-select" data-build-sort>
                                    <option value="popular">Популярності</option>
                                    <option value="price-desc">За зменшенням ціни</option>
                                    <option value="price-asc">За збільшенням ціни</option>
                                </select>
                            </div>
                        </div>

                        <div class="builds__header">
                            <h2>Каталог збірок KondorPC</h2>
                        </div>

                        <div class="builds__grid" data-build-grid>
                            @foreach ($builds as $build)
                                <article
                                    class="build-card build-card--{{ $build['tone'] }} is-fps-{{ $build['fps_state'] }}"
                                    data-fps-card
                                    data-product-url="{{ route('product.show', ['slug' => $build['slug']]) }}"
                                    role="link"
                                    tabindex="0"
                                    data-fps-score="{{ $build['fps_score'] }}"
                                    data-sort-index="{{ $build['sort_index'] }}"
                                    data-sort-price="{{ $build['price_value'] }}"
                                    data-build-slug="{{ $build['slug'] }}"
                                    data-build-name="{{ $build['name'] }}"
                                    data-build-price="{{ $build['price_value'] }}"
                                    data-build-tone="{{ $build['tone'] }}"
                                    data-current-fps="{{ $build['fps_value'] }}"
                                    style="--fps-ratio: {{ number_format($build['fps_ratio'], 4, '.', '') }}; --fps-size: {{ $build['fps_size'] }}px;"
                                >
                                    <div class="build-card__media" aria-hidden="true"></div>

                                    <div class="build-card__body">
                                        <h2 class="build-card__title">{{ $build['name'] }}</h2>

                                        <div class="build-card__content">
                                            <div class="build-card__info">
                                                <div class="build-card__copy-wrap" data-build-copy-wrap>
                                                    <ul class="build-card__specs" data-build-copy>
                                                        <li>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                <rect x="7" y="7" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                                <path d="M9 3V6M15 3V6M9 18V21M15 18V21M3 9H6M18 9H21M3 15H6M18 15H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                            <span>{{ $build['gpu'] }}</span>
                                                        </li>
                                                        <li>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                <path d="M12 3L19 8V16L12 21L5 16V8L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                                <path d="M12 9V15M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                            <span>{{ $build['cpu'] }}</span>
                                                        </li>
                                                        <li>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                <rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                                                <path d="M8 10H10M14 10H16M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                            <span>{{ $build['ram'] }}</span>
                                                        </li>
                                                        <li>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                                <path d="M7 12H17M7 15H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                            <span>{{ $build['storage'] }}</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <button class="build-card__copy-toggle" type="button" data-build-copy-toggle aria-expanded="false" aria-label="Показати характеристики" hidden>
                                                    <span class="build-card__copy-toggle-label" data-build-copy-toggle-label>Показати характеристики</span>
                                                    <svg class="build-card__copy-toggle-chevron" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                                        <path d="M2.25 4.5L6 8.25L9.75 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="build-card__fps-side">
                                                <div class="build-card__fps" aria-label="Поточний FPS">
                                                    <span class="build-card__fps-value" data-fps-value>{{ $build['fps_value'] }}</span>
                                                    <span class="build-card__fps-scale" aria-hidden="true">
                                                        <span class="build-card__fps-fill"></span>
                                                    </span>
                                                    <span class="build-card__fps-label">FPS</span>
                                                </div>

                                                <button class="build-card__fps-more" type="button" data-fps-mobile-open aria-label="Переглянути більше FPS даних">
                                                    Більше FPS
                                                </button>
                                            </div>
                                        </div>

                                        <span class="build-card__price-label">Ціна за збірку</span>
                                        <span class="build-card__price">{{ $build['price'] }}</span>
                                        <div class="build-card__actions">
                                            <button class="catalog-cta build-card__action build-card__action--cart" type="button" data-build-add>
                                                Додати в кошик
                                            </button>
                                            <a class="catalog-cta build-card__action" href="{{ route('product.show', ['slug' => $build['slug']]) }}">Детальніше</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                </div>
            </main>

            <footer class="footer" id="contacts">
                <div class="container">
                    <div class="footer__grid">
                        <div class="footer__brand">
                            <div class="footer__logo">
                                <span class="footer__brand-name">KondorPC</span>
                                <span class="footer__brand-sub">Твоя база геймінгу</span>
                            </div>

                            <div class="footer__contacts">
                                <a href="tel:+380633631066">+380 63 363 10 66</a>
                                <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">@kondor_channeI</a>
                            </div>

                            <div class="footer__socials">
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/>
                                        <circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/>
                                        <circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/>
                                    </svg>
                                </a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                </a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="Подзвонити">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">Про нас</h2>
                            <nav class="footer__nav" aria-label="Інформація про KondorPC">
                                <a href="{{ url('/') }}#about">Що таке KondorPC</a>
                                <a href="#contacts">Контакти</a>
                                <a href="#contacts">Доставка</a>
                                <a href="#contacts">Оплата</a>
                                <a href="#contacts">Повернення та обмін</a>
                            </nav>
                        </div>

                        <div class="footer__column">
                            <h2 class="footer__title">Основне</h2>
                            <nav class="footer__nav" aria-label="Основна навігація">
                                <a href="{{ url('/') }}">Головна</a>
                                <a href="#builds">Комп'ютери</a>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">
                        {{ date('Y') }} KondorPC | Всі права захищені
                    </div>
                </div>
            </footer>
        </div>

        <script src="{{ asset('js/storefront-cart.js') }}"></script>
        <script>
            (() => {
                const header = document.querySelector('.header');
                const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
                const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                const fpsLab = document.querySelector('[data-fps-lab]');
                const fpsGameSelect = document.querySelector('[data-fps-game]');
                const fpsDisplaySelect = document.querySelector('[data-fps-display]');
                const fpsPresetSelect = document.querySelector('[data-fps-preset]');
                const fpsSceneBadge = document.querySelector('[data-fps-scene-badge]');
                const fpsSceneTitle = document.querySelector('[data-fps-scene-title]');
                const fpsSceneMeta = document.querySelector('[data-fps-scene-meta]');
                const fpsMobileOpenButtons = Array.from(document.querySelectorAll('[data-fps-mobile-open]'));
                const fpsMobileCloseButtons = Array.from(document.querySelectorAll('[data-fps-mobile-close]'));
                const fpsCards = Array.from(document.querySelectorAll('[data-fps-card]'));
                const buildsGrid = document.querySelector('[data-build-grid]');
                const buildSortSelect = document.querySelector('[data-build-sort]');
                const buildCopyWrappers = Array.from(document.querySelectorAll('[data-build-copy-wrap]'));
                const addToCartButtons = Array.from(document.querySelectorAll('[data-build-add]'));
                const fpsConfig = @json($fpsClientConfig);
                const fpsGames = Object.fromEntries((fpsConfig.games ?? []).map((game) => [game.id, game]));
                const fpsDisplays = Object.fromEntries((fpsConfig.displays ?? []).map((display) => [display.id, display]));
                const fpsPresets = Object.fromEntries((fpsConfig.presets ?? []).map((preset) => [preset.id, preset]));
                const fpsAnimationFrames = new WeakMap();
                let closeTimer;

                const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
                const mobileBuildCopyLimit = 176;

                const resolveFpsState = (fps) => {
                    if (fps < 70) {
                        return 'low';
                    }

                    if (fps < 120) {
                        return 'mid';
                    }

                    return 'high';
                };

                const resolveFpsRatio = (fps) => clamp(fps / 220, 0.18, 1);
                const resolveFpsSize = (fps) => Math.round(22 + (clamp((fps - 40) / 170, 0, 1) * 10));

                const computeFps = (score, state) => {
                    const game = fpsGames[state.game];
                    const display = fpsDisplays[state.display];
                    const preset = fpsPresets[state.preset];

                    if (!game || !display || !preset) {
                        return Math.round(score);
                    }

                    return Math.round(clamp(score * game.difficulty * display.multiplier * preset.multiplier, 38, 320));
                };

                const renderFpsCard = (card, fps) => {
                    const valueElement = card.querySelector('[data-fps-value]');
                    const fpsState = resolveFpsState(fps);
                    const roundedFps = Math.round(fps);

                    card.style.setProperty('--fps-ratio', resolveFpsRatio(fps).toFixed(4));
                    card.style.setProperty('--fps-size', `${resolveFpsSize(fps)}px`);
                    card.classList.remove('is-fps-low', 'is-fps-mid', 'is-fps-high');
                    card.classList.add(`is-fps-${fpsState}`);
                    card.dataset.currentFps = `${roundedFps}`;

                    if (valueElement) {
                        valueElement.textContent = `${roundedFps}`;
                    }
                };

                const animateFpsCard = (card, targetFps, immediate = false) => {
                    const valueElement = card.querySelector('[data-fps-value]');
                    const currentFps = Number(card.dataset.currentFps ?? valueElement?.textContent ?? targetFps);
                    const activeFrame = fpsAnimationFrames.get(card);

                    if (activeFrame) {
                        window.cancelAnimationFrame(activeFrame);
                    }

                    if (immediate || currentFps === targetFps) {
                        card.dataset.currentFps = `${targetFps}`;
                        card.classList.remove('is-fps-animating');
                        renderFpsCard(card, targetFps);
                        fpsAnimationFrames.delete(card);
                        return;
                    }

                    const startedAt = performance.now();
                    const duration = 520;
                    card.classList.add('is-fps-animating');

                    const tick = (now) => {
                        const progress = clamp((now - startedAt) / duration, 0, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        const nextValue = currentFps + ((targetFps - currentFps) * eased);

                        renderFpsCard(card, nextValue);

                        if (progress < 1) {
                            fpsAnimationFrames.set(card, window.requestAnimationFrame(tick));
                            return;
                        }

                        card.dataset.currentFps = `${targetFps}`;
                        card.classList.remove('is-fps-animating');
                        renderFpsCard(card, targetFps);
                        fpsAnimationFrames.delete(card);
                    };

                    fpsAnimationFrames.set(card, window.requestAnimationFrame(tick));
                };

                const updateFpsScene = (state) => {
                    const game = fpsGames[state.game];
                    const display = fpsDisplays[state.display];
                    const preset = fpsPresets[state.preset];

                    if (!fpsLab || !game || !display || !preset) {
                        return;
                    }

                    fpsLab.style.setProperty('--scene-from', game.from);
                    fpsLab.style.setProperty('--scene-to', game.to);
                    fpsLab.style.setProperty('--scene-accent', game.accent);

                    if (fpsSceneBadge) {
                        fpsSceneBadge.textContent = game.badge;
                    }

                    if (fpsSceneTitle) {
                        fpsSceneTitle.textContent = game.name;
                    }

                    if (fpsSceneMeta) {
                        fpsSceneMeta.textContent = `${display.name} · ${preset.name}`;
                    }
                };

                const syncFpsCards = (immediate = false) => {
                    const state = {
                        game: fpsGameSelect?.value ?? fpsConfig.defaults?.game,
                        display: fpsDisplaySelect?.value ?? fpsConfig.defaults?.display,
                        preset: fpsPresetSelect?.value ?? fpsConfig.defaults?.preset,
                    };

                    updateFpsScene(state);

                    fpsCards.forEach((card) => {
                        const score = Number(card.dataset.fpsScore ?? 0);

                        if (!score) {
                            return;
                        }

                        animateFpsCard(card, computeFps(score, state), immediate);
                    });
                };

                const applyBuildSort = (mode) => {
                    if (!buildsGrid) {
                        return;
                    }

                    const sortedCards = [...fpsCards].sort((leftCard, rightCard) => {
                        const leftIndex = Number(leftCard.dataset.sortIndex ?? 0);
                        const rightIndex = Number(rightCard.dataset.sortIndex ?? 0);
                        const leftPrice = Number(leftCard.dataset.sortPrice ?? 0);
                        const rightPrice = Number(rightCard.dataset.sortPrice ?? 0);

                        if (mode === 'price-desc') {
                            return (rightPrice - leftPrice) || (leftIndex - rightIndex);
                        }

                        if (mode === 'price-asc') {
                            return (leftPrice - rightPrice) || (leftIndex - rightIndex);
                        }

                        return leftIndex - rightIndex;
                    });

                    const fragment = document.createDocumentFragment();
                    sortedCards.forEach((card) => fragment.appendChild(card));
                    buildsGrid.appendChild(fragment);
                };

                const collapsedBuildCopyLabel = 'Показати характеристики';
                const expandedBuildCopyLabel = 'Сховати характеристики';

                const syncBuildCopyToggles = () => {
                    const isMobile = window.innerWidth <= 760;

                    buildCopyWrappers.forEach((wrapper) => {
                        const content = wrapper.querySelector('[data-build-copy]');
                        const toggle = wrapper.parentElement?.querySelector('[data-build-copy-toggle]');
                        const toggleLabel = toggle?.querySelector('[data-build-copy-toggle-label]');

                        if (!content || !toggle) {
                            return;
                        }

                        const expanded = wrapper.dataset.expanded === 'true';
                        wrapper.classList.remove('is-collapsible', 'is-collapsed');
                        wrapper.style.removeProperty('--build-copy-max');
                        toggle.hidden = true;
                        toggle.classList.remove('is-visible');

                        if (!isMobile) {
                            wrapper.dataset.expanded = 'false';
                            toggle.classList.remove('is-expanded');
                            toggle.setAttribute('aria-expanded', 'false');
                            toggle.setAttribute('aria-label', collapsedBuildCopyLabel);
                            if (toggleLabel) {
                                toggleLabel.textContent = collapsedBuildCopyLabel;
                            }
                            return;
                        }

                        const needsToggle = content.scrollHeight > mobileBuildCopyLimit + 6;

                        if (!needsToggle) {
                            wrapper.dataset.expanded = 'false';
                            toggle.classList.remove('is-expanded');
                            toggle.setAttribute('aria-expanded', 'false');
                            toggle.setAttribute('aria-label', collapsedBuildCopyLabel);
                            if (toggleLabel) {
                                toggleLabel.textContent = collapsedBuildCopyLabel;
                            }
                            return;
                        }

                        wrapper.classList.add('is-collapsible');
                        wrapper.style.setProperty('--build-copy-max', `${mobileBuildCopyLimit}px`);

                        if (!expanded) {
                            wrapper.classList.add('is-collapsed');
                        }

                        toggle.hidden = false;
                        toggle.classList.add('is-visible');
                        toggle.classList.toggle('is-expanded', expanded);
                        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                        toggle.setAttribute('aria-label', expanded ? expandedBuildCopyLabel : collapsedBuildCopyLabel);
                        if (toggleLabel) {
                            toggleLabel.textContent = expanded ? expandedBuildCopyLabel : collapsedBuildCopyLabel;
                        }
                    });
                };

                const openFpsMobileSheet = () => {
                    if (!fpsLab) {
                        return;
                    }

                    fpsLab.classList.add('is-mobile-open');
                    document.body.classList.add('is-fps-sheet-open');
                };

                const closeFpsMobileSheet = () => {
                    if (!fpsLab) {
                        return;
                    }

                    fpsLab.classList.remove('is-mobile-open');
                    document.body.classList.remove('is-fps-sheet-open');
                };

                const syncHeaderState = () => {
                    if (!header) {
                        return;
                    }

                    header.classList.toggle('is-stuck', window.scrollY > 10);
                };

                const clearCloseTimer = () => {
                    if (closeTimer) {
                        window.clearTimeout(closeTimer);
                        closeTimer = undefined;
                    }
                };

                const closeAllDropdowns = () => {
                    triggers.forEach((trigger) => {
                        trigger.classList.remove('is-open');
                        trigger.setAttribute('aria-expanded', 'false');
                    });

                    panels.forEach((panel) => {
                        panel.classList.remove('is-open');
                    });
                };

                const positionConsultationPanel = () => {
                    const trigger = document.querySelector('[data-dropdown-trigger="consultation"]');
                    const panel = document.querySelector('[data-dropdown-panel="consultation"]');

                    if (!header || !trigger || !panel || window.innerWidth <= 760) {
                        if (panel) {
                            panel.style.left = '';
                        }

                        return;
                    }

                    const headerRect = header.getBoundingClientRect();
                    const triggerRect = trigger.getBoundingClientRect();
                    const panelWidth = panel.offsetWidth || 230;
                    const idealLeft = triggerRect.left - headerRect.left + ((triggerRect.width - panelWidth) / 2);
                    const maxLeft = headerRect.width - panelWidth - 12;
                    const nextLeft = Math.max(12, Math.min(idealLeft, maxLeft));

                    panel.style.left = `${nextLeft}px`;
                };

                const openDropdown = (name) => {
                    const nextPanel = document.querySelector(`[data-dropdown-panel="${name}"]`);
                    const nextTrigger = document.querySelector(`[data-dropdown-trigger="${name}"]`);

                    if (!nextPanel || !nextTrigger) {
                        return;
                    }

                    closeAllDropdowns();
                    nextTrigger.classList.add('is-open');
                    nextTrigger.setAttribute('aria-expanded', 'true');
                    nextPanel.classList.add('is-open');

                    if (name === 'consultation') {
                        positionConsultationPanel();
                    }
                };

                const scheduleClose = () => {
                    clearCloseTimer();
                    closeTimer = window.setTimeout(() => {
                        closeAllDropdowns();
                    }, 120);
                };

                const closeMobileMenu = () => {
                    if (!mobileToggle || !mobileMenu) {
                        return;
                    }

                    mobileToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.remove('is-open');
                };

                triggers.forEach((trigger) => {
                    const name = trigger.dataset.dropdownTrigger;
                    const panel = document.querySelector(`[data-dropdown-panel="${name}"]`);

                    if (!name || !panel) {
                        return;
                    }

                    trigger.addEventListener('mouseenter', () => {
                        clearCloseTimer();
                        openDropdown(name);
                    });

                    trigger.addEventListener('mouseleave', scheduleClose);
                    trigger.addEventListener('focus', () => openDropdown(name));

                    trigger.addEventListener('click', () => {
                        const isOpen = panel.classList.contains('is-open');

                        if (isOpen) {
                            closeAllDropdowns();
                            return;
                        }

                        openDropdown(name);
                    });

                    panel.addEventListener('mouseenter', clearCloseTimer);
                    panel.addEventListener('mouseleave', scheduleClose);
                });

                fpsMobileOpenButtons.forEach((button) => {
                    button.addEventListener('click', openFpsMobileSheet);
                });

                fpsMobileCloseButtons.forEach((button) => {
                    button.addEventListener('click', closeFpsMobileSheet);
                });

                document.querySelectorAll('[data-build-copy-toggle]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const wrapper = button.parentElement?.querySelector('[data-build-copy-wrap]');

                        if (!wrapper) {
                            return;
                        }

                        const nextExpanded = wrapper.dataset.expanded !== 'true';
                        wrapper.dataset.expanded = nextExpanded ? 'true' : 'false';
                        syncBuildCopyToggles();
                    });
                });

                addToCartButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const card = button.closest('[data-fps-card]');

                        if (!card || !window.KondorCart) {
                            return;
                        }

                        const slug = card.dataset.buildSlug;
                        const name = card.dataset.buildName;
                        const productUrl = card.dataset.productUrl;
                        const price = Number(card.dataset.buildPrice ?? 0);

                        if (!slug || !name || !productUrl || !price) {
                            return;
                        }

                        window.KondorCart.addItem({
                            slug,
                            name,
                            price,
                            quantity: 1,
                            url: productUrl,
                            tone: card.dataset.buildTone ?? 'violet',
                        });

                        if (button.dataset.defaultLabel === undefined) {
                            button.dataset.defaultLabel = button.textContent?.trim() ?? 'Додати в кошик';
                        }

                        button.classList.add('is-added');
                        button.textContent = 'Додано';

                        window.setTimeout(() => {
                            button.classList.remove('is-added');
                            button.textContent = button.dataset.defaultLabel ?? 'Додати в кошик';
                        }, 1400);
                    });
                });

                fpsCards.forEach((card) => {
                    const productUrl = card.dataset.productUrl;

                    if (!productUrl) {
                        return;
                    }

                    card.addEventListener('click', (event) => {
                        if (event.target.closest('a, button, input, select, textarea, summary, label')) {
                            return;
                        }

                        window.location.href = productUrl;
                    });

                    card.addEventListener('keydown', (event) => {
                        if (event.target !== card) {
                            return;
                        }

                        if (event.key !== 'Enter' && event.key !== ' ') {
                            return;
                        }

                        event.preventDefault();
                        window.location.href = productUrl;
                    });
                });

                mobileToggle?.addEventListener('click', () => {
                    const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';

                    mobileToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    mobileMenu?.classList.toggle('is-open', !isExpanded);
                    closeAllDropdowns();
                });

                mobileMenu?.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        closeMobileMenu();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
                        closeAllDropdowns();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') {
                        return;
                    }

                    closeAllDropdowns();
                    closeMobileMenu();
                    closeFpsMobileSheet();
                });

                window.addEventListener('scroll', syncHeaderState, { passive: true });
                window.addEventListener('resize', () => {
                    syncHeaderState();
                    positionConsultationPanel();

                    if (window.innerWidth > 760) {
                        closeFpsMobileSheet();
                    }

                    if (window.innerWidth > 1080) {
                        closeMobileMenu();
                    }

                    syncBuildCopyToggles();
                });

                [fpsGameSelect, fpsDisplaySelect, fpsPresetSelect].forEach((select) => {
                    select?.addEventListener('change', () => syncFpsCards());
                });

                buildSortSelect?.addEventListener('change', () => {
                    applyBuildSort(buildSortSelect.value);
                });

                syncHeaderState();
                positionConsultationPanel();
                syncFpsCards(true);
                applyBuildSort(buildSortSelect?.value ?? 'popular');
                syncBuildCopyToggles();
                if (window.KondorCart) {
                    window.KondorCart.renderPreviews();
                }

                window.addEventListener('load', syncBuildCopyToggles);
            })();
        </script>
        @include('partials.admin-site-notifications')
    </body>
</html>
