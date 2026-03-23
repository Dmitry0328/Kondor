<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>KindorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <style>
            :root {
                --bg: #ffffff;
                --bg-muted: #f6f7fb;
                --surface: #ffffff;
                --text: #18202a;
                --muted: #646d79;
                --line: #dfe3eb;
                --dark: #262225;
                --primary: #6f10c9;
                --primary-dark: #5809a7;
                --shadow: 0 18px 45px rgba(24, 32, 42, 0.08);
                --container: min(100% - 28px, 1920px);
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
                background: var(--bg);
            }

            body.is-gallery-open {
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
                margin: 0;
                background: var(--bg);
                border: 0;
                min-height: 100vh;
            }

            .container {
                width: var(--container);
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
            .hero__layout,
            .hero__actions,
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
                position: relative;
                border-bottom: 1px solid var(--line);
                background: var(--surface);
                z-index: 20;
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
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

            .hero {
                padding: 12px 0 42px;
            }

            .hero__layout {
                align-items: stretch;
                gap: 26px;
                min-height: calc(100vh - 120px);
            }

            .hero__copy {
                flex: 1 1 58%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 30px 20px 46px;
            }

            .hero__copy-inner {
                max-width: 430px;
            }

            .hero__copy h1 {
                margin: 0 0 14px;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(38px, 5vw, 58px);
                line-height: 1.04;
                letter-spacing: -0.04em;
            }

            .hero__copy p {
                margin: 0;
                color: var(--muted);
                font-size: 17px;
                line-height: 1.78;
            }

            .hero__actions {
                gap: 14px;
                margin-top: 24px;
                flex-wrap: wrap;
            }

            .hero__visual {
                flex: 0 0 39.5%;
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 720px;
                background:
                    radial-gradient(circle at 18% 24%, rgba(7, 153, 246, 0.68), transparent 38%),
                    radial-gradient(circle at 84% 18%, rgba(31, 227, 166, 0.82), transparent 42%),
                    linear-gradient(135deg, #1b7fd2 2%, #1096ff 24%, #05cf8d 100%);
            }

            .hero__visual::after {
                content: '';
                position: absolute;
                inset: auto 40px 24px 40px;
                height: 10px;
                background: rgba(255, 255, 255, 0.9);
                opacity: 0.92;
            }

            .rig {
                position: relative;
                width: min(590px, 88%);
                aspect-ratio: 0.82;
                transform: translateY(12px);
            }

            .rig__shadow {
                position: absolute;
                inset: auto 8% 2% 8%;
                height: 28px;
                background: rgba(0, 0, 0, 0.22);
                filter: blur(16px);
                border-radius: 999px;
            }

            .rig__case {
                position: absolute;
                inset: 0;
                border: 3px solid #181b1e;
                border-radius: 8px;
                background: linear-gradient(180deg, #101214, #0b0f12);
                box-shadow: 0 26px 40px rgba(0, 0, 0, 0.22);
            }

            .rig__glass {
                position: absolute;
                top: 6%;
                left: 4%;
                width: 72%;
                height: 82%;
                border: 2px solid rgba(122, 132, 145, 0.6);
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.01)),
                    radial-gradient(circle at 30% 20%, rgba(63, 255, 112, 0.12), transparent 40%);
                overflow: hidden;
            }

            .rig__glass::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 38%);
            }

            .rig__frame {
                position: absolute;
                background: #0f1419;
            }

            .rig__frame--top {
                top: 5%;
                left: 7%;
                width: 56%;
                height: 18px;
            }

            .rig__frame--bottom {
                bottom: 10%;
                left: 8%;
                width: 58%;
                height: 18px;
            }

            .rig__frame--gpu {
                top: 57%;
                left: 15%;
                width: 48%;
                height: 36px;
                box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
            }

            .rig__gpu-text {
                position: absolute;
                top: 8px;
                left: 10px;
                color: rgba(255, 255, 255, 0.6);
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.08em;
            }

            .rig__gpu-badge {
                position: absolute;
                top: 8px;
                right: 12px;
                color: #42ff68;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.06em;
            }

            .rig__cooler {
                position: absolute;
                top: 24%;
                left: 31%;
                width: 74px;
                height: 98px;
                border: 2px solid rgba(188, 195, 205, 0.55);
                background: linear-gradient(180deg, rgba(36, 41, 47, 0.96), rgba(15, 18, 21, 0.96));
            }

            .rig__cooler::before,
            .rig__cooler::after {
                content: '';
                position: absolute;
                left: 10px;
                right: 10px;
                height: 2px;
                background: rgba(144, 151, 160, 0.45);
            }

            .rig__cooler::before {
                top: 26px;
            }

            .rig__cooler::after {
                bottom: 26px;
            }

            .rig__fan {
                position: absolute;
                width: 90px;
                aspect-ratio: 1;
                border-radius: 50%;
                background:
                    radial-gradient(circle at center, rgba(210, 255, 214, 0.34) 0 11%, transparent 13% 100%),
                    radial-gradient(circle at center, rgba(83, 255, 89, 0.94) 0 35%, rgba(20, 30, 24, 0.2) 36% 52%, rgba(88, 255, 96, 0.9) 53% 100%);
                box-shadow:
                    0 0 18px rgba(88, 255, 96, 0.72),
                    inset 0 0 18px rgba(24, 29, 31, 0.8);
            }

            .rig__fan::before {
                content: '';
                position: absolute;
                inset: 11px;
                border: 1px solid rgba(217, 255, 218, 0.34);
                border-radius: 50%;
            }

            .rig__fan--rear {
                top: 28%;
                left: 6%;
                width: 84px;
            }

            .rig__fan--front-top {
                top: 16%;
                right: 6%;
            }

            .rig__fan--front-middle {
                top: 40%;
                right: 6%;
            }

            .rig__fan--front-bottom {
                top: 64%;
                right: 6%;
            }

            .rig__fan--top-1,
            .rig__fan--top-2,
            .rig__fan--top-3 {
                top: 4%;
                width: 82px;
            }

            .rig__fan--top-1 {
                left: 11%;
            }

            .rig__fan--top-2 {
                left: 28%;
            }

            .rig__fan--top-3 {
                left: 45%;
            }

            .rig__strip {
                position: absolute;
                border-radius: 999px;
                background: linear-gradient(90deg, rgba(123, 255, 85, 0.88), rgba(65, 255, 101, 0.25));
                box-shadow: 0 0 14px rgba(65, 255, 101, 0.5);
            }

            .rig__strip--top {
                top: 10%;
                left: 12%;
                width: 52%;
                height: 4px;
            }

            .rig__strip--side {
                top: 18%;
                right: 20%;
                width: 4px;
                height: 58%;
            }

            .rig__strip--bottom {
                bottom: 12%;
                left: 11%;
                width: 48%;
                height: 4px;
            }

            .builds {
                padding: 10px 0 76px;
            }

            .builds__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                margin-bottom: 26px;
            }

            .builds__header h2 {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(32px, 3vw, 44px);
                letter-spacing: -0.03em;
            }

            .builds__button {
                min-width: 148px;
                text-align: center;
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
                overflow: hidden;
                border: 1px solid #e7ebf1;
                border-radius: 26px;
                background: linear-gradient(180deg, #ffffff, #fbfbfd);
                box-shadow: 0 18px 40px rgba(24, 32, 42, 0.08);
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
                padding: 18px 20px 22px;
            }

            .build-card__title {
                margin: 0 0 16px;
                font-size: 22px;
                line-height: 1.05;
                letter-spacing: -0.03em;
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
                gap: 10px;
                color: #27303c;
                font-size: 14px;
                line-height: 1.35;
            }

            .build-card__specs svg {
                flex: none;
                margin-top: 2px;
                color: #5e6672;
            }

            .build-card__price {
                display: inline-block;
                margin-top: 18px;
                color: #1d2430;
                font-size: 22px;
                font-weight: 800;
                text-decoration: underline;
                text-underline-offset: 3px;
            }

            .build-card__action {
                width: 100%;
                margin-top: 18px;
            }

            .advantages {
                padding: 34px 0 96px;
            }

            .advantages__inner {
                max-width: 1120px;
                margin: 0 auto;
            }

            .advantages__title {
                margin: 0 0 34px;
                text-align: center;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(30px, 3vw, 42px);
                letter-spacing: -0.03em;
            }

            .advantages__grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 34px;
            }

            .advantages__card {
                text-align: center;
                padding: 10px 20px 0;
            }

            .advantages__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 58px;
                height: 58px;
                margin-bottom: 18px;
                color: #20252d;
            }

            .advantages__card h3 {
                margin: 0 0 14px;
                font-size: clamp(28px, 2vw, 36px);
                line-height: 1.08;
                letter-spacing: -0.04em;
            }

            .advantages__card p {
                margin: 0;
                color: #5f6875;
                font-size: 17px;
                line-height: 1.68;
            }

            .gallery {
                --gallery-group-width: min(1460px, calc(100vw - 88px));
                --gallery-row-height: clamp(220px, 19vw, 352px);
                position: relative;
                padding: 84px 0 96px;
                background:
                    radial-gradient(circle at 18% 18%, rgba(132, 36, 240, 0.14), transparent 24%),
                    radial-gradient(circle at 86% 12%, rgba(48, 215, 255, 0.08), transparent 18%),
                    linear-gradient(180deg, #332d35 0%, #241f28 52%, #18151b 100%);
                color: #ffffff;
                overflow: hidden;
            }

            .gallery__header {
                display: grid;
                grid-template-columns: auto 1fr auto;
                align-items: center;
                gap: 24px;
                margin-bottom: 30px;
            }

            .gallery__socials {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
            }

            .gallery__social {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                min-height: 50px;
                padding: 0 20px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.04);
                color: #ffffff;
                font-size: 18px;
                font-weight: 700;
                transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.18s ease;
            }

            .gallery__social:hover {
                border-color: rgba(255, 255, 255, 0.16);
                background: rgba(255, 255, 255, 0.08);
                transform: translateY(-1px);
            }

            .gallery__social svg {
                flex: none;
            }

            .gallery__title-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 14px;
                text-align: center;
            }

            .gallery__title {
                margin: 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: clamp(32px, 3.2vw, 50px);
                letter-spacing: -0.04em;
            }

            .gallery__line {
                width: 152px;
                height: 4px;
                border-radius: 999px;
                background: linear-gradient(90deg, #8424f0, #30d7ff);
            }

            .gallery__controls {
                display: flex;
                align-items: center;
                justify-self: end;
                gap: 12px;
            }

            .gallery__control {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 56px;
                height: 56px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.04);
                color: #ffffff;
                cursor: pointer;
                transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.18s ease;
            }

            .gallery__control:hover {
                border-color: rgba(255, 255, 255, 0.16);
                background: rgba(255, 255, 255, 0.08);
                transform: translateY(-1px);
            }

            .gallery__viewport {
                overflow-x: auto;
                overscroll-behavior-x: contain;
                scroll-snap-type: x proximity;
                scrollbar-width: none;
                padding-bottom: 8px;
            }

            .gallery__viewport::-webkit-scrollbar {
                display: none;
            }

            .gallery__track {
                display: flex;
                gap: 18px;
                width: max-content;
                padding-right: 18px;
            }

            .gallery__group {
                flex: 0 0 var(--gallery-group-width);
                min-width: var(--gallery-group-width);
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                grid-template-rows: repeat(2, var(--gallery-row-height));
                gap: 16px;
                scroll-snap-align: start;
            }

            .gallery-card {
                position: relative;
                display: block;
                height: 100%;
                padding: 0;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 24px;
                background: #151922;
                overflow: hidden;
                cursor: pointer;
                box-shadow: 0 24px 50px rgba(0, 0, 0, 0.26);
                transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            }

            .gallery-card:hover {
                transform: translateY(-4px);
                border-color: rgba(132, 36, 240, 0.32);
                box-shadow: 0 28px 58px rgba(0, 0, 0, 0.32);
            }

            .gallery-card--pattern-a {
                grid-column: 1 / span 2;
                grid-row: 1;
            }

            .gallery-card--pattern-b {
                grid-column: 3;
                grid-row: 1;
            }

            .gallery-card--pattern-c {
                grid-column: 4;
                grid-row: 1;
            }

            .gallery-card--pattern-d {
                grid-column: 1;
                grid-row: 2;
            }

            .gallery-card--pattern-e {
                grid-column: 2;
                grid-row: 2;
            }

            .gallery-card--pattern-f {
                grid-column: 3 / span 2;
                grid-row: 2;
            }

            .gallery-card__art,
            .gallery-card__art svg {
                display: block;
                width: 100%;
                height: 100%;
            }

            .gallery-card__art {
                background: #10141b;
            }

            .gallery-card__badge {
                position: absolute;
                top: 16px;
                left: 16px;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(9, 11, 15, 0.72);
                border: 1px solid rgba(255, 255, 255, 0.08);
                color: rgba(255, 255, 255, 0.92);
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                backdrop-filter: blur(10px);
            }

            .gallery-card__zoom {
                position: absolute;
                right: 16px;
                bottom: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(9, 11, 15, 0.72);
                border: 1px solid rgba(255, 255, 255, 0.08);
                color: #ffffff;
                backdrop-filter: blur(10px);
            }

            .gallery-modal {
                position: fixed;
                inset: 0;
                z-index: 100;
                display: grid;
                place-items: center;
                padding: 18px;
                background: rgba(7, 7, 10, 0.9);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.2s ease, visibility 0.2s ease;
            }

            .gallery-modal.is-open {
                opacity: 1;
                visibility: visible;
            }

            .gallery-modal__dialog {
                width: min(100%, 1640px);
                height: min(100%, 944px);
                display: grid;
                grid-template-columns: minmax(0, 1fr) 148px;
                gap: 18px;
            }

            .gallery-modal__stage {
                position: relative;
                min-width: 0;
                min-height: 0;
                border-radius: 28px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                background: linear-gradient(180deg, #151118, #0f0d13);
                overflow: hidden;
                box-shadow: 0 32px 80px rgba(0, 0, 0, 0.42);
            }

            .gallery-modal__viewer {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 32px;
            }

            .gallery-modal__viewer svg {
                width: 100%;
                height: 100%;
                max-width: 100%;
                max-height: 100%;
            }

            .gallery-modal__close,
            .gallery-modal__nav {
                position: absolute;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 52px;
                height: 52px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 50%;
                background: rgba(10, 12, 17, 0.72);
                color: #ffffff;
                cursor: pointer;
                backdrop-filter: blur(10px);
                transition: background-color 0.2s ease, transform 0.18s ease;
            }

            .gallery-modal__close:hover,
            .gallery-modal__nav:hover {
                background: rgba(132, 36, 240, 0.18);
                transform: translateY(-1px);
            }

            .gallery-modal__close {
                top: 18px;
                right: 18px;
                z-index: 2;
            }

            .gallery-modal__nav {
                top: 50%;
                transform: translateY(-50%);
                z-index: 2;
            }

            .gallery-modal__nav:hover {
                transform: translateY(-50%) scale(1.02);
            }

            .gallery-modal__nav--prev {
                left: 18px;
            }

            .gallery-modal__nav--next {
                right: 18px;
            }

            .gallery-modal__meta {
                position: absolute;
                left: 24px;
                right: 24px;
                bottom: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                padding: 16px 18px;
                border-radius: 18px;
                background: rgba(7, 9, 13, 0.68);
                border: 1px solid rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(12px);
            }

            .gallery-modal__caption {
                color: #ffffff;
                font-size: 18px;
                font-weight: 700;
            }

            .gallery-modal__counter {
                color: rgba(255, 255, 255, 0.72);
                font-size: 14px;
                font-weight: 700;
                white-space: nowrap;
            }

            .gallery-modal__aside {
                min-height: 0;
            }

            .gallery-modal__thumbs {
                height: 100%;
                display: flex;
                flex-direction: column;
                gap: 12px;
                overflow: auto;
                padding-right: 4px;
            }

            .gallery-modal__thumb {
                position: relative;
                width: 100%;
                flex: 0 0 auto;
                padding: 0;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 18px;
                background: #10141b;
                overflow: hidden;
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
            }

            .gallery-modal__thumb:hover,
            .gallery-modal__thumb.is-active {
                opacity: 1;
                border-color: rgba(132, 36, 240, 0.4);
                transform: translateY(-1px);
            }

            .gallery-modal__thumb-art,
            .gallery-modal__thumb-art svg {
                display: block;
                width: 100%;
                height: auto;
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

            .mobile-menu a,
            .mobile-menu button {
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

                .hero__layout {
                    min-height: 620px;
                }

                .builds__grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }

                .gallery {
                    --gallery-group-width: min(1320px, calc(100vw - 48px));
                    --gallery-row-height: clamp(210px, 20vw, 320px);
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

                .hero__layout {
                    flex-direction: column;
                    min-height: unset;
                }

                .hero__copy,
                .hero__visual {
                    flex: unset;
                    width: 100%;
                }

                .hero__copy {
                    justify-content: flex-start;
                    padding-inline: 0;
                }

                .hero__copy-inner {
                    max-width: 620px;
                }

                .builds__grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }

                .advantages__grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .gallery {
                    --gallery-group-width: min(1120px, calc(100vw - 32px));
                    --gallery-row-height: 250px;
                }

                .gallery-modal__dialog {
                    grid-template-columns: minmax(0, 1fr) 120px;
                }
            }

            @media (max-width: 760px) {
                .container {
                    width: min(100% - 20px, 100%);
                }

                .topbar__inner,
                .topbar__links,
                .topbar__meta,
                .topbar__contacts {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 8px;
                }

                .topbar__inner {
                    padding: 10px 0;
                }

                .header__inner {
                    min-height: 72px;
                }

                .brand__name {
                    font-size: 26px;
                }

                .hero {
                    padding-bottom: 24px;
                }

                .hero__visual {
                    min-height: 480px;
                }

                .rig {
                    width: min(450px, 94%);
                }

                .dropdown {
                    width: calc(100% - 20px);
                }

                .dropdown__columns {
                    flex-direction: column;
                    gap: 28px;
                    padding: 24px 20px;
                }

                .builds {
                    padding-bottom: 54px;
                }

                .builds__header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .builds__grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .advantages {
                    padding-bottom: 68px;
                }

                .advantages__grid {
                    grid-template-columns: 1fr;
                    gap: 28px;
                }

                .gallery {
                    --gallery-group-width: min(980px, calc(100vw - 20px));
                    --gallery-row-height: 220px;
                    padding-block: 70px;
                }

                .gallery__header {
                    grid-template-columns: 1fr;
                    align-items: flex-start;
                }

                .gallery__title-wrap {
                    align-items: flex-start;
                    text-align: left;
                }

                .gallery__controls {
                    justify-self: start;
                }

                .gallery-modal {
                    padding: 12px;
                }

                .gallery-modal__dialog {
                    grid-template-columns: 1fr;
                    grid-template-rows: minmax(0, 1fr) auto;
                    height: min(100%, 920px);
                }

                .gallery-modal__viewer {
                    padding: 18px;
                }

                .gallery-modal__thumbs {
                    flex-direction: row;
                    height: auto;
                    padding-right: 0;
                    padding-bottom: 4px;
                }

                .gallery-modal__thumb {
                    width: 112px;
                }

                .gallery-modal__meta {
                    left: 14px;
                    right: 14px;
                    bottom: 14px;
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            @media (max-width: 560px) {
                .hero__actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .header-button,
                .hero__actions a {
                    width: 100%;
                }

                .rig__fan {
                    width: 72px;
                }

                .rig__fan--rear {
                    width: 68px;
                }

                .rig__fan--top-1,
                .rig__fan--top-2,
                .rig__fan--top-3 {
                    width: 64px;
                }

                .builds__grid {
                    grid-template-columns: 1fr;
                }

                .build-card__body {
                    padding-inline: 18px;
                }

                .advantages__card {
                    padding-inline: 8px;
                }

                .advantages__card p {
                    font-size: 16px;
                }

                .gallery__socials {
                    width: 100%;
                }

                .gallery__social {
                    width: 100%;
                    justify-content: center;
                }

                .gallery {
                    --gallery-group-width: 920px;
                    --gallery-row-height: 190px;
                }

                .gallery__control {
                    width: 48px;
                    height: 48px;
                }

                .gallery-modal__close,
                .gallery-modal__nav {
                    width: 44px;
                    height: 44px;
                }

                .gallery-modal__nav--prev {
                    left: 10px;
                }

                .gallery-modal__nav--next {
                    right: 10px;
                }

                .gallery-modal__thumb {
                    width: 90px;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="#about">Про нас</a>
                        <a href="#contacts">Контакти</a>
                        <a href="#faq">FAQ</a>
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
                    <a class="brand" href="/">
                        <div>
                            <div class="brand__name">KindorPC</div>
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

                        <div class="search-box" role="search">
                            <input type="search" placeholder="Пошук збірок">
                            <button type="button" aria-label="Пошук">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M20 20L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <a class="header-cart" href="#cart" aria-label="Кошик">
                            <span>0 ₴</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="9" cy="19" r="1.6" fill="currentColor"/>
                                <circle cx="17" cy="19" r="1.6" fill="currentColor"/>
                                <path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

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
                        <a href="#about">Про нас</a>
                        <a href="#builds">Наші збірки</a>
                        <button type="button" data-mobile-dropdown-toggle>Каталог збірок</button>
                        <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
                        <a href="#contacts">Контакти</a>
                        <a href="#faq">FAQ</a>
                    </div>
                </div>
            </header>

            <section class="hero" id="about">
                <div class="container hero__layout">
                    <div class="hero__copy">
                        <div class="hero__copy-inner">
                            <h1>Твоя база геймінгу</h1>
                            <p>
                                Ласкаво просимо до KindorPC. Тут будуть тільки готові збірки ПК та конфігурації під замовлення.
                                Ми робимо акцент на продуктивність, чисту збірку й ефектний зовнішній вигляд.
                            </p>

                            <div class="hero__actions">
                                <a class="header-button header-button--primary" href="#builds">Наші збірки</a>
                                <a class="header-button" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Написати в Telegram</a>
                            </div>

                        </div>
                    </div>

                    <div class="hero__visual" aria-hidden="true">
                        <div class="rig">
                            <div class="rig__shadow"></div>
                            <div class="rig__case"></div>
                            <div class="rig__glass"></div>
                            <div class="rig__frame rig__frame--top"></div>
                            <div class="rig__frame rig__frame--bottom"></div>
                            <div class="rig__frame rig__frame--gpu">
                                <span class="rig__gpu-text">GEFORCE RTX</span>
                                <span class="rig__gpu-badge">RGB</span>
                            </div>
                            <div class="rig__cooler"></div>
                            <div class="rig__fan rig__fan--rear"></div>
                            <div class="rig__fan rig__fan--front-top"></div>
                            <div class="rig__fan rig__fan--front-middle"></div>
                            <div class="rig__fan rig__fan--front-bottom"></div>
                            <div class="rig__fan rig__fan--top-1"></div>
                            <div class="rig__fan rig__fan--top-2"></div>
                            <div class="rig__fan rig__fan--top-3"></div>
                            <div class="rig__strip rig__strip--top"></div>
                            <div class="rig__strip rig__strip--side"></div>
                            <div class="rig__strip rig__strip--bottom"></div>
                        </div>
                    </div>
                </div>
            </section>

            @php
                $featuredBuilds = [
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

                $galleryArt = function (string $variant, string $primary, string $secondary, string $accent): string {
                    $seed = substr(md5($variant . $primary . $secondary . $accent), 0, 8);
                    $bgId = "bg-{$seed}";
                    $screenId = "screen-{$seed}";
                    $lineId = "line-{$seed}";
                    $blurId = "blur-{$seed}";

                    $towerLarge = <<<SVG
<g>
    <ellipse cx="1140" cy="842" rx="260" ry="34" fill="#030507" opacity="0.48"/>
    <rect x="928" y="152" width="408" height="614" rx="28" fill="#0c1016"/>
    <rect x="960" y="186" width="286" height="536" rx="18" fill="rgba(255,255,255,0.02)" stroke="rgba(220,230,255,0.14)" stroke-width="6"/>
    <rect x="1210" y="186" width="94" height="536" rx="16" fill="#090c12"/>
    <circle cx="1258" cy="276" r="48" fill="rgba(255,255,255,0.04)" stroke="{$primary}" stroke-width="9"/>
    <circle cx="1258" cy="454" r="48" fill="rgba(255,255,255,0.03)" stroke="{$secondary}" stroke-width="9"/>
    <circle cx="1258" cy="632" r="48" fill="rgba(255,255,255,0.03)" stroke="{$accent}" stroke-width="9"/>
    <circle cx="1064" cy="546" r="42" fill="rgba(255,255,255,0.03)" stroke="{$secondary}" stroke-width="8"/>
    <circle cx="1142" cy="546" r="42" fill="rgba(255,255,255,0.03)" stroke="{$primary}" stroke-width="8"/>
    <circle cx="1220" cy="546" r="42" fill="rgba(255,255,255,0.03)" stroke="{$accent}" stroke-width="8"/>
    <path d="M1008 222H1190" stroke="url(#{$lineId})" stroke-width="6" stroke-linecap="round" opacity="0.72"/>
    <path d="M1020 686H1210" stroke="url(#{$lineId})" stroke-width="10" stroke-linecap="round"/>
    <rect x="1020" y="514" width="190" height="30" rx="12" fill="#131922"/>
</g>
SVG;

                    $towerCompact = <<<SVG
<g>
    <ellipse cx="406" cy="842" rx="196" ry="30" fill="#030507" opacity="0.42"/>
    <rect x="180" y="220" width="402" height="560" rx="26" fill="#0c1016"/>
    <rect x="214" y="254" width="286" height="472" rx="18" fill="rgba(255,255,255,0.03)" stroke="rgba(220,230,255,0.14)" stroke-width="6"/>
    <rect x="466" y="254" width="84" height="472" rx="16" fill="#090c12"/>
    <circle cx="508" cy="330" r="42" fill="rgba(255,255,255,0.03)" stroke="{$primary}" stroke-width="8"/>
    <circle cx="508" cy="500" r="42" fill="rgba(255,255,255,0.03)" stroke="{$secondary}" stroke-width="8"/>
    <circle cx="508" cy="670" r="42" fill="rgba(255,255,255,0.03)" stroke="{$accent}" stroke-width="8"/>
    <circle cx="328" cy="480" r="56" fill="rgba(255,255,255,0.02)" stroke="{$primary}" stroke-width="10"/>
    <path d="M254 690H446" stroke="url(#{$lineId})" stroke-width="10" stroke-linecap="round"/>
</g>
SVG;

                    $whiteRig = <<<SVG
<g>
    <ellipse cx="392" cy="834" rx="188" ry="28" fill="#030507" opacity="0.36"/>
    <rect x="152" y="392" width="420" height="312" rx="22" fill="#f4f7ff"/>
    <rect x="190" y="424" width="250" height="212" rx="16" fill="rgba(124,66,255,0.04)" stroke="rgba(130,140,160,0.55)" stroke-width="6"/>
    <circle cx="304" cy="530" r="52" fill="rgba(255,255,255,0.46)" stroke="{$primary}" stroke-width="9"/>
    <path d="M242 644H518" stroke="rgba(134,144,160,0.64)" stroke-width="10" stroke-linecap="round"/>
    <path d="M472 430V632" stroke="rgba(134,144,160,0.34)" stroke-width="6"/>
</g>
SVG;

                    $monitorDesk = <<<SVG
<g>
    <ellipse cx="390" cy="720" rx="208" ry="24" fill="#030507" opacity="0.46"/>
    <rect x="90" y="204" width="624" height="352" rx="24" fill="#0b1017" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
    <rect x="120" y="232" width="564" height="296" rx="18" fill="url(#{$screenId})"/>
    <path d="M158 472C256 340 372 540 506 390C572 318 618 340 668 294" stroke="{$accent}" stroke-width="18" stroke-linecap="round" opacity="0.72"/>
    <path d="M162 434C266 312 374 510 522 356C582 292 622 312 664 274" stroke="{$primary}" stroke-width="10" stroke-linecap="round" opacity="0.82"/>
    <rect x="362" y="558" width="80" height="28" rx="14" fill="#11161f"/>
    <rect x="390" y="584" width="24" height="76" rx="12" fill="#151b25"/>
    <rect x="286" y="652" width="232" height="18" rx="9" fill="#18202a"/>
</g>
SVG;

                    switch ($variant) {
                        case 'monitor':
                            $scene = $monitorDesk . $towerLarge;
                            break;

                        case 'duo':
                            $scene = $whiteRig . $towerLarge;
                            break;

                        case 'stack':
                            $scene = $towerCompact . $towerLarge;
                            break;

                        default:
                            $scene = <<<SVG
<g>
    <ellipse cx="814" cy="848" rx="354" ry="38" fill="#030507" opacity="0.46"/>
    <rect x="438" y="144" width="676" height="648" rx="34" fill="#0c1016"/>
    <rect x="478" y="184" width="430" height="554" rx="20" fill="rgba(255,255,255,0.03)" stroke="rgba(220,230,255,0.14)" stroke-width="6"/>
    <rect x="894" y="184" width="178" height="554" rx="18" fill="#090c12"/>
    <circle cx="982" cy="288" r="62" fill="rgba(255,255,255,0.03)" stroke="{$primary}" stroke-width="10"/>
    <circle cx="982" cy="470" r="62" fill="rgba(255,255,255,0.03)" stroke="{$secondary}" stroke-width="10"/>
    <circle cx="982" cy="652" r="62" fill="rgba(255,255,255,0.03)" stroke="{$accent}" stroke-width="10"/>
    <circle cx="628" cy="560" r="58" fill="rgba(255,255,255,0.02)" stroke="{$secondary}" stroke-width="10"/>
    <circle cx="744" cy="560" r="58" fill="rgba(255,255,255,0.02)" stroke="{$primary}" stroke-width="10"/>
    <circle cx="860" cy="560" r="58" fill="rgba(255,255,255,0.02)" stroke="{$accent}" stroke-width="10"/>
    <path d="M520 226H852" stroke="url(#{$lineId})" stroke-width="8" stroke-linecap="round" opacity="0.76"/>
    <path d="M546 706H848" stroke="url(#{$lineId})" stroke-width="12" stroke-linecap="round"/>
</g>
SVG;
                            break;
                    }

                    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 1000" fill="none" preserveAspectRatio="xMidYMid slice">
    <defs>
        <linearGradient id="{$bgId}" x1="120" y1="80" x2="1500" y2="960" gradientUnits="userSpaceOnUse">
            <stop stop-color="#111722"/>
            <stop offset="1" stop-color="#090d14"/>
        </linearGradient>
        <linearGradient id="{$screenId}" x1="160" y1="180" x2="1440" y2="900" gradientUnits="userSpaceOnUse">
            <stop stop-color="{$primary}"/>
            <stop offset="0.54" stop-color="{$secondary}"/>
            <stop offset="1" stop-color="{$accent}"/>
        </linearGradient>
        <linearGradient id="{$lineId}" x1="320" y1="200" x2="1320" y2="760" gradientUnits="userSpaceOnUse">
            <stop stop-color="{$primary}"/>
            <stop offset="0.5" stop-color="{$secondary}"/>
            <stop offset="1" stop-color="{$accent}"/>
        </linearGradient>
        <filter id="{$blurId}" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="40"/>
        </filter>
    </defs>
    <rect width="1600" height="1000" fill="url(#{$bgId})"/>
    <circle cx="260" cy="160" r="220" fill="{$primary}" opacity="0.16" filter="url(#{$blurId})"/>
    <circle cx="1370" cy="860" r="240" fill="{$secondary}" opacity="0.14" filter="url(#{$blurId})"/>
    <rect y="808" width="1600" height="192" fill="#0a0d13"/>
    <rect y="792" width="1600" height="2" fill="rgba(255,255,255,0.12)"/>
    {$scene}
</svg>
SVG;
                };

                $galleryItems = [
                    ['title' => 'Blue Studio', 'layout' => 'hero', 'badge' => 'Kondor build', 'art' => $galleryArt('monitor', '#7b42ff', '#2fd5ff', '#eef7ff')],
                    ['title' => 'Arctic White', 'layout' => 'square', 'badge' => 'Kondor build', 'art' => $galleryArt('duo', '#78d5ff', '#4fa2ff', '#f5fbff')],
                    ['title' => 'Orange Core', 'layout' => 'medium', 'badge' => 'Kondor build', 'art' => $galleryArt('showcase', '#ff8a3d', '#ffbf66', '#ffe8cf')],
                    ['title' => 'Neon Duo', 'layout' => 'wide', 'badge' => 'Kondor build', 'art' => $galleryArt('duo', '#8424f0', '#30d7ff', '#f2efff')],
                    ['title' => 'Purple Frame', 'layout' => 'square', 'badge' => 'Kondor build', 'art' => $galleryArt('stack', '#8c4dff', '#42d6ff', '#f7f2ff')],
                    ['title' => 'Crimson Glass', 'layout' => 'hero', 'badge' => 'Kondor build', 'art' => $galleryArt('showcase', '#ff4f8b', '#ff7a2d', '#ffe3d6')],
                    ['title' => 'Violet Desk', 'layout' => 'medium', 'badge' => 'Kondor build', 'art' => $galleryArt('monitor', '#9b56ff', '#6f7cff', '#f1f2ff')],
                    ['title' => 'Night Tower', 'layout' => 'square', 'badge' => 'Kondor build', 'art' => $galleryArt('stack', '#3964ff', '#6bd5ff', '#ffffff')],
                    ['title' => 'Aqua Showroom', 'layout' => 'hero', 'badge' => 'Kondor build', 'art' => $galleryArt('showcase', '#45d9ff', '#5a77ff', '#f4fbff')],
                    ['title' => 'Monochrome Setup', 'layout' => 'wide', 'badge' => 'Kondor build', 'art' => $galleryArt('monitor', '#6f7cff', '#a86cff', '#eef2ff')],
                    ['title' => 'Frost Chamber', 'layout' => 'square', 'badge' => 'Kondor build', 'art' => $galleryArt('duo', '#6dd9ff', '#7d8dff', '#ffffff')],
                    ['title' => 'Cyber Purple', 'layout' => 'medium', 'badge' => 'Kondor build', 'art' => $galleryArt('stack', '#7b42ff', '#30d7ff', '#f3f7ff')],
                ];

                $galleryPattern = ['pattern-a', 'pattern-b', 'pattern-c', 'pattern-d', 'pattern-e', 'pattern-f'];
                $galleryGroups = array_chunk($galleryItems, 6);
            @endphp

            <section class="builds" id="builds">
                <div class="container">
                    <div class="builds__header">
                        <h2>Обрані комп'ютерні збірки</h2>
                        <a class="catalog-cta builds__button" href="#builds">Всі збірки</a>
                    </div>

                    <div class="builds__grid">
                        @foreach ($featuredBuilds as $build)
                            <article class="build-card build-card--{{ $build['tone'] }}">
                                <div class="build-card__media" aria-hidden="true"></div>

                                <div class="build-card__body">
                                    <h3 class="build-card__title">{{ $build['name'] }}</h3>

                                    <ul class="build-card__specs">
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

                                    <span class="build-card__price">{{ $build['price'] }}</span>
                                    <a class="catalog-cta build-card__action" href="#builds">Детальніше</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="advantages" id="advantages">
                <div class="container">
                    <div class="advantages__inner">
                        <h2 class="advantages__title">Наші переваги</h2>

                        <div class="advantages__grid">
                            <article class="advantages__card">
                                <div class="advantages__icon" aria-hidden="true">
                                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 3L14.7 5.1L18 5.4L18.6 8.6L20.9 11L18.6 13.4L18 16.6L14.7 16.9L12 19L9.3 16.9L6 16.6L5.4 13.4L3.1 11L5.4 8.6L6 5.4L9.3 5.1L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <circle cx="12" cy="11" r="3.3" stroke="currentColor" stroke-width="1.8"/>
                                    </svg>
                                </div>
                                <h3>Індивідуальний підхід</h3>
                                <p>Ми розуміємо, що кожен гравець унікальний. Тому ми пропонуємо широкий вибір конфігурацій, щоб кожен міг знайти свій ідеальний ігровий ПК.</p>
                            </article>

                            <article class="advantages__card">
                                <div class="advantages__icon" aria-hidden="true">
                                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 15L14.5 5.5C15.6 4.4 17.4 4.4 18.5 5.5C19.6 6.6 19.6 8.4 18.5 9.5L9 19H5V15Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M13 7L17 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M5 19L3.5 20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <h3>Якість і тестування</h3>
                                <p>Кожен комп'ютер, що покидає нашу майстерню, проходить строге тестування та перевірку якості. Ми прагнемо до повної впевненості в тому, що кожен продукт відповідає нашим високим стандартам.</p>
                            </article>

                            <article class="advantages__card">
                                <div class="advantages__icon" aria-hidden="true">
                                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 3L18 5V10.2C18 14.7 15.4 18.9 12 20.7C8.6 18.9 6 14.7 6 10.2V5L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M9.5 11.8L11.3 13.6L14.8 10.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3>Гнучка система гарантій</h3>
                                <p>Ми стоїмо за якістю наших виробів і надаємо гнучкі гарантійні умови. Ваша задоволеність - наш пріоритет.</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="gallery" id="gallery">
                <div class="container">
                    <div class="gallery__header">
                        <div class="gallery__socials">
                            <a class="gallery__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="12" cy="12" r="12" fill="#7E2DF1"/>
                                    <path d="M17.8 7.4L6.5 11.8L10.1 13.1L11.4 16.9L17.8 7.4Z" stroke="#fff" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M10.1 13.1L13.8 9.6" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <span>Наш Telegram</span>
                            </a>

                            <a class="gallery__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="2.5" y="2.5" width="19" height="19" rx="6" fill="url(#gallery-instagram-fill)" stroke="rgba(255,255,255,0.3)"/>
                                    <circle cx="12" cy="12" r="4.2" stroke="#fff" stroke-width="1.8"/>
                                    <circle cx="17.4" cy="6.7" r="1.1" fill="#fff"/>
                                    <defs>
                                        <linearGradient id="gallery-instagram-fill" x1="4" y1="4" x2="20" y2="20" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#8424f0"/>
                                            <stop offset="0.55" stop-color="#ff4f92"/>
                                            <stop offset="1" stop-color="#ffb347"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <span>Наш Instagram</span>
                            </a>
                        </div>

                        <div class="gallery__title-wrap">
                            <h2 class="gallery__title">Наші роботи</h2>
                            <span class="gallery__line"></span>
                        </div>

                        <div class="gallery__controls">
                            <button class="gallery__control" type="button" data-gallery-scroll-prev aria-label="Прокрутити галерею вліво">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>

                            <button class="gallery__control" type="button" data-gallery-scroll-next aria-label="Прокрутити галерею вправо">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="gallery__viewport" data-gallery-viewport>
                        <div class="gallery__track">
                            @foreach ($galleryGroups as $groupIndex => $group)
                                <div class="gallery__group">
                                    @foreach ($group as $patternIndex => $item)
                                        @php
                                            $absoluteIndex = ($groupIndex * 6) + $patternIndex;
                                        @endphp
                                        <button
                                            class="gallery-card gallery-card--{{ $galleryPattern[$patternIndex] }}"
                                            type="button"
                                            data-gallery-item
                                            data-gallery-index="{{ $absoluteIndex }}"
                                            data-gallery-title="{{ $item['title'] }}"
                                            aria-label="Відкрити {{ $item['title'] }}"
                                        >
                                            <span class="gallery-card__art">{!! $item['art'] !!}</span>
                                            <span class="gallery-card__badge">{{ $item['badge'] }}</span>
                                            <span class="gallery-card__zoom" aria-hidden="true">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                                    <path d="M10 4H4V10M14 4H20V10M20 14V20H14M10 20H4V14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <div class="gallery-modal" data-gallery-modal aria-hidden="true">
                <div class="gallery-modal__dialog">
                    <div class="gallery-modal__stage">
                        <button class="gallery-modal__close" type="button" data-gallery-close aria-label="Закрити галерею">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <button class="gallery-modal__nav gallery-modal__nav--prev" type="button" data-gallery-prev aria-label="Попереднє фото">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div class="gallery-modal__viewer" data-gallery-main></div>

                        <button class="gallery-modal__nav gallery-modal__nav--next" type="button" data-gallery-next aria-label="Наступне фото">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div class="gallery-modal__meta">
                            <span class="gallery-modal__caption" data-gallery-caption></span>
                            <span class="gallery-modal__counter" data-gallery-counter></span>
                        </div>
                    </div>

                    <aside class="gallery-modal__aside">
                        <div class="gallery-modal__thumbs">
                            @foreach ($galleryItems as $index => $item)
                                <button class="gallery-modal__thumb" type="button" data-gallery-thumb="{{ $index }}" aria-label="Відкрити {{ $item['title'] }}">
                                    <span class="gallery-modal__thumb-art">{!! $item['art'] !!}</span>
                                </button>
                            @endforeach
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        <script>
            (() => {
                const header = document.querySelector('.header');
                const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
                const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                const galleryItems = Array.from(document.querySelectorAll('[data-gallery-item]'));
                const galleryModal = document.querySelector('[data-gallery-modal]');
                const galleryMain = document.querySelector('[data-gallery-main]');
                const galleryCaption = document.querySelector('[data-gallery-caption]');
                const galleryCounter = document.querySelector('[data-gallery-counter]');
                const galleryClose = document.querySelector('[data-gallery-close]');
                const galleryPrev = document.querySelector('[data-gallery-prev]');
                const galleryNext = document.querySelector('[data-gallery-next]');
                const galleryThumbs = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
                const galleryViewport = document.querySelector('[data-gallery-viewport]');
                const galleryScrollPrev = document.querySelector('[data-gallery-scroll-prev]');
                const galleryScrollNext = document.querySelector('[data-gallery-scroll-next]');
                let closeTimer;
                let activeGalleryIndex = 0;

                const positionConsultationPanel = () => {
                    const trigger = document.querySelector('[data-dropdown-trigger="consultation"]');
                    const panel = document.querySelector('[data-dropdown-panel="consultation"]');

                    if (!header || !trigger || !panel) {
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

                const closeAllDropdowns = () => {
                    triggers.forEach((trigger) => {
                        trigger.classList.remove('is-open');
                        trigger.setAttribute('aria-expanded', 'false');
                    });

                    panels.forEach((panel) => {
                        panel.classList.remove('is-open');
                    });
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

                const clearCloseTimer = () => {
                    if (closeTimer) {
                        window.clearTimeout(closeTimer);
                        closeTimer = undefined;
                    }
                };

                const scheduleClose = () => {
                    clearCloseTimer();
                    closeTimer = window.setTimeout(() => {
                        closeAllDropdowns();
                    }, 120);
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

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
                        closeAllDropdowns();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (galleryModal?.classList.contains('is-open')) {
                        if (event.key === 'Escape') {
                            galleryModal.classList.remove('is-open');
                            galleryModal.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('is-gallery-open');
                        }

                        if (event.key === 'ArrowRight') {
                            activeGalleryIndex = (activeGalleryIndex + 1) % galleryItems.length;
                            updateGallery();
                        }

                        if (event.key === 'ArrowLeft') {
                            activeGalleryIndex = (activeGalleryIndex - 1 + galleryItems.length) % galleryItems.length;
                            updateGallery();
                        }

                        return;
                    }

                    if (event.key === 'Escape') {
                        closeAllDropdowns();
                    }
                });

                window.addEventListener('resize', positionConsultationPanel);

                mobileToggle?.addEventListener('click', () => {
                    const isOpen = mobileMenu.classList.toggle('is-open');
                    mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                const updateGallery = () => {
                    const currentItem = galleryItems[activeGalleryIndex];

                    if (!currentItem || !galleryMain) {
                        return;
                    }

                    const art = currentItem.querySelector('.gallery-card__art')?.innerHTML ?? '';
                    const title = currentItem.dataset.galleryTitle ?? '';

                    galleryMain.innerHTML = art;

                    const modalSvg = galleryMain.querySelector('svg');

                    if (modalSvg) {
                        modalSvg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
                    }

                    if (galleryCaption) {
                        galleryCaption.textContent = title;
                    }

                    if (galleryCounter) {
                        galleryCounter.textContent = `${activeGalleryIndex + 1} / ${galleryItems.length}`;
                    }

                    galleryThumbs.forEach((thumb, index) => {
                        thumb.classList.toggle('is-active', index === activeGalleryIndex);

                        if (index === activeGalleryIndex) {
                            thumb.scrollIntoView({ block: 'nearest', inline: 'center' });
                        }
                    });
                };

                const closeGallery = () => {
                    galleryModal?.classList.remove('is-open');
                    galleryModal?.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('is-gallery-open');
                };

                const openGallery = (index) => {
                    activeGalleryIndex = index;
                    updateGallery();
                    galleryModal?.classList.add('is-open');
                    galleryModal?.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('is-gallery-open');
                };

                const stepGallery = (direction) => {
                    if (!galleryItems.length) {
                        return;
                    }

                    activeGalleryIndex = (activeGalleryIndex + direction + galleryItems.length) % galleryItems.length;
                    updateGallery();
                };

                galleryItems.forEach((item, index) => {
                    item.addEventListener('click', () => openGallery(index));
                });

                galleryThumbs.forEach((thumb, index) => {
                    thumb.addEventListener('click', () => {
                        activeGalleryIndex = index;
                        updateGallery();
                    });
                });

                galleryClose?.addEventListener('click', closeGallery);
                galleryPrev?.addEventListener('click', () => stepGallery(-1));
                galleryNext?.addEventListener('click', () => stepGallery(1));
                galleryScrollPrev?.addEventListener('click', () => {
                    galleryViewport?.scrollBy({ left: -Math.max(galleryViewport.clientWidth * 0.92, 420), behavior: 'smooth' });
                });
                galleryScrollNext?.addEventListener('click', () => {
                    galleryViewport?.scrollBy({ left: Math.max(galleryViewport.clientWidth * 0.92, 420), behavior: 'smooth' });
                });

                galleryModal?.addEventListener('click', (event) => {
                    if (event.target === galleryModal) {
                        closeGallery();
                    }
                });
            })();
        </script>
    </body>
</html>
