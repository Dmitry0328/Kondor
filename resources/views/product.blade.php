<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $build['name'] }} | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/storefront-cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-inline-images.css') }}">
        <style>
            :root { --bg:#fff; --surface:#fff; --text:#18202a; --muted:#646d79; --line:#dfe3eb; --primary:#6f10c9; --shadow:0 18px 45px rgba(24,32,42,.08); --container:min(calc(100% - 28px),1920px); --content:min(calc(100% - 28px),1440px); --win-border:#c9d0da; --win-surface-top:#fff; --win-surface-bottom:#eef2f6; --win-shadow:inset 0 1px 0 rgba(255,255,255,.95),0 1px 2px rgba(16,24,40,.08); }
            * { box-sizing:border-box; }
            html { scroll-behavior:smooth; }
            body { margin:0; min-width:320px; font-family:'Manrope',sans-serif; color:var(--text); background:linear-gradient(180deg,#f7f9fc 0%,#eef3f9 100%); }
            a { color:inherit; text-decoration:none; }
            button, input { font:inherit; }
            .page-shell { min-height:100vh; background:var(--bg); }
            .container { width:var(--container); margin:0 auto; }
            .product-wrap { width:var(--content); margin:0 auto; }
            .topbar { background:#2b272b; color:#fff; font-size:14px; }
            .topbar__inner, .topbar__links, .topbar__meta, .topbar__contacts, .topbar__socials, .header__inner, .header__actions, .header-cart, .brand, .dropdown__columns, .dropdown__group, .footer__socials { display:flex; align-items:center; }
            .topbar__inner { justify-content:space-between; min-height:38px; gap:22px; }
            .topbar__links { gap:26px; }
            .topbar__meta { margin-left:auto; gap:30px; }
            .topbar__contacts { gap:20px; }
            .topbar__socials { gap:16px; }
            .topbar a { font-weight:700; opacity:.96; line-height:1; }
            .topbar__social-link { justify-content:center; width:18px; height:18px; }
            .header { position:sticky; top:0; z-index:80; border-bottom:1px solid var(--line); background:var(--surface); box-shadow:0 1px 0 rgba(255,255,255,.7); transition:background-color .22s ease, box-shadow .22s ease, border-color .22s ease, backdrop-filter .22s ease; }
            .header.is-stuck { background:rgba(255,255,255,.84); border-bottom-color:rgba(214,222,234,.88); box-shadow:0 14px 28px rgba(24,32,42,.1); }
            .header__inner { justify-content:space-between; gap:18px; min-height:78px; }
            .brand { gap:16px; min-width:180px; }
            .brand__name { font-family:'Space Grotesk',sans-serif; font-size:31px; font-weight:700; letter-spacing:-.04em; color:#11151a; }
            .brand__sub { display:block; margin-top:2px; color:var(--muted); font-size:12px; font-weight:700; }
            .header__actions { flex:1; justify-content:flex-end; gap:12px; }
            .header-link, .header-link--primary { display:none; }
            .header-button { position:relative; display:inline-flex; align-items:center; justify-content:center; gap:10px; min-height:44px; padding:0 24px; border:1px solid var(--win-border); border-radius:12px; background:#fff; color:#1a212d; font-size:14px; font-weight:800; cursor:pointer; box-shadow:0 6px 16px rgba(24,32,42,.08), inset 0 1px 0 rgba(255,255,255,.92); transition:background-color .2s ease, border-color .2s ease, color .2s ease, transform .18s ease; }
            .header-button svg { flex:none; }
            .header-button--primary { border-color:#4b19a1; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; box-shadow:inset 0 1px 0 rgba(255,255,255,.18),0 8px 18px rgba(105,22,203,.24); }
            .header-button--primary:hover, .header-button--primary:focus-visible, .header-button--primary.is-open { border-color:#4b19a1; background:linear-gradient(180deg,#8f2fff,#7420d3); color:#fff; }
            .header-button:not(.header-button--primary):hover, .header-button:not(.header-button--primary).is-open { border-color:#bcc7d6; background:#fff; box-shadow:0 8px 18px rgba(24,32,42,.1), inset 0 1px 0 rgba(255,255,255,.92); }
            .header-button:active { transform:translateY(1px); box-shadow:inset 0 2px 4px rgba(16,24,40,.12); }
            .search-box { display:flex; align-items:center; width:min(100%,430px); min-height:42px; border:1px solid var(--win-border); border-radius:999px; background:linear-gradient(180deg,#fff,#f4f7fb); box-shadow:var(--win-shadow); overflow:hidden; }
            .search-box input { flex:1; min-width:0; height:42px; padding:0 16px; border:0; outline:none; background:transparent; color:var(--text); }
            .search-box button { width:42px; height:42px; border:0; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; cursor:pointer; box-shadow:inset 1px 0 0 rgba(255,255,255,.14); }
            .header-cart { justify-content:center; gap:9px; min-height:42px; padding:0 16px; border:1px solid var(--line); border-radius:999px; background:#fff; color:#1a212d; font-size:14px; font-weight:800; box-shadow:0 10px 24px rgba(24,32,42,.08); white-space:nowrap; }
            .header-cart:hover { border-color:#ccd3dd; background:#fbfcfe; }
            .header-cart svg { color:#9298a5; }
            .dropdown { position:absolute; top:calc(100% - 1px); left:50%; width:min(1080px,calc(100% - 46px)); transform:translateX(-50%); border:1px solid var(--line); background:#fff; box-shadow:var(--shadow); opacity:0; pointer-events:none; transition:opacity .18s ease; }
            .dropdown.is-open { opacity:1; pointer-events:auto; }
            .dropdown__columns { align-items:stretch; gap:48px; padding:30px 34px; }
            .dropdown__group { align-items:flex-start; flex-direction:column; gap:14px; min-width:200px; }
            .dropdown__group h3 { margin:0; font-family:'Space Grotesk',sans-serif; font-size:19px; }
            .dropdown__group a { color:#242c37; font-size:16px; font-weight:600; transition:color .2s ease; }
            .dropdown__group a:hover { color:var(--primary); }
            .dropdown--consultation { width:230px; left:0; transform:none; }
            .dropdown--consultation .dropdown__columns { display:block; padding:12px 0; }
            .dropdown--consultation .dropdown__group { min-width:0; gap:0; }
            .dropdown--consultation .dropdown__group a { display:block; width:100%; padding:18px 34px; color:#1d2430; font-size:16px; font-weight:600; line-height:1.35; }
            .dropdown--consultation .dropdown__group a:hover { background:#faf7ff; color:var(--primary); }
            .menu-toggle { display:none; width:42px; height:40px; border:1px solid var(--win-border); border-radius:14px; background:linear-gradient(180deg,var(--win-surface-top),var(--win-surface-bottom)); box-shadow:var(--win-shadow); cursor:pointer; }
            .menu-toggle span { display:block; width:18px; height:2px; margin:4px auto; background:#1c2430; }
            .mobile-menu { display:none; border-bottom:1px solid var(--line); background:#fff; }
            .mobile-menu.is-open { display:block; }
            .mobile-menu__inner { display:grid; gap:10px; padding:16px 0 20px; }
            .mobile-menu a { display:flex; align-items:center; min-height:44px; padding:0 14px; border:1px solid var(--win-border); border-radius:14px; background:linear-gradient(180deg,var(--win-surface-top),var(--win-surface-bottom)); color:#1a212d; font-weight:700; box-shadow:var(--win-shadow); }
            .page { padding:28px 0 72px; }
            .product-hero { margin-bottom:18px; }
            .product-breadcrumbs { display:flex; flex-wrap:wrap; gap:10px; margin:0; color:#677183; font-size:13px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
            .product-showcase { display:grid; grid-template-columns:minmax(0,1.02fr) minmax(360px,.9fr); gap:36px; align-items:start; }
            .product-gallery { display:grid; gap:16px; align-content:start; }
            .product-gallery__stage { position:relative; aspect-ratio:1/1; padding:0; border:1px solid #dde5f0; border-radius:0; background:#fff; box-shadow:0 14px 30px rgba(24,32,42,.08); overflow:hidden; }
            .product-gallery__info { position:absolute; top:18px; left:18px; z-index:5; display:flex; align-items:flex-start; gap:10px; }
            .product-gallery__info-button { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; padding:0; border:1px solid rgba(255,255,255,.18); border-radius:999px; background:rgba(22,27,35,.92); color:#fff; box-shadow:0 10px 20px rgba(6,10,18,.18); cursor:pointer; transition:transform .18s ease, border-color .18s ease, background-color .18s ease; }
            .product-gallery__info-button:hover { transform:translateY(-1px); border-color:rgba(255,255,255,.32); background:rgba(22,27,35,.98); }
            .product-gallery__info-button span { font-family:Georgia,'Times New Roman',serif; font-size:20px; font-style:italic; line-height:1; transform:translateY(-1px); }
            .product-gallery__info-tooltip { max-width:min(460px, calc(100vw - 140px)); padding:10px 16px; border-radius:10px; background:rgba(37,37,39,.92); color:rgba(255,255,255,.88); font-size:14px; font-weight:700; line-height:1.35; box-shadow:0 16px 28px rgba(6,10,18,.18); opacity:0; visibility:hidden; transform:translateY(-2px); transition:opacity .18s ease, transform .18s ease, visibility .18s ease; pointer-events:none; }
            .product-gallery__info:hover .product-gallery__info-tooltip,
            .product-gallery__info:focus-within .product-gallery__info-tooltip,
            .product-gallery__info.is-open .product-gallery__info-tooltip { opacity:1; visibility:visible; transform:translateY(0); }
            .product-gallery__slide { position:absolute; inset:0; display:none; overflow:hidden; border-radius:0; background:radial-gradient(circle at 50% 18%, rgba(255,255,255,.48), transparent 34%), linear-gradient(180deg,var(--slide-from),var(--slide-to)); }
            .product-gallery__slide.is-active { display:block; }
            .product-gallery__nav { position:absolute; top:50%; z-index:4; display:inline-flex; align-items:center; justify-content:center; width:46px; height:46px; margin-top:-23px; border:1px solid rgba(255,255,255,.34); border-radius:50%; background:rgba(15,20,30,.2); color:#fff; backdrop-filter:blur(8px); cursor:pointer; transition:transform .18s ease, background-color .18s ease; }
            .product-gallery__nav:hover { transform:translateY(-1px); background:rgba(15,20,30,.34); }
            .product-gallery__nav--prev { left:26px; }
            .product-gallery__nav--next { right:26px; }
            .product-gallery__photo { position:relative; width:100%; height:100%; overflow:hidden; border-radius:0; }
            .product-gallery__photo::after { content:''; position:absolute; left:0; right:0; bottom:0; height:18px; background:linear-gradient(180deg,rgba(204,202,184,0),rgba(186,183,166,.9)); }
            .product-gallery__glow { position:absolute; inset:10% 12%; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.42), transparent 66%); filter:blur(20px); }
            .product-rig { position:absolute; left:50%; bottom:2%; width:min(72%,500px); aspect-ratio:.82; transform:translateX(-50%); }
            .product-rig__shadow { position:absolute; left:10%; right:10%; bottom:0; height:44px; border-radius:50%; background:rgba(4,10,18,.22); filter:blur(12px); }
            .product-rig__case { position:absolute; inset:5% 13% 8% 13%; border-radius:28px; background:linear-gradient(180deg,#fefefe,#dfe7f2); border:2px solid rgba(255,255,255,.52); box-shadow:0 28px 60px rgba(12,18,28,.18); }
            .product-rig__glass { position:absolute; inset:10% 18% 18% 21%; border-radius:22px; background:linear-gradient(180deg,rgba(255,255,255,.28),rgba(255,255,255,.06)); border:2px solid rgba(255,255,255,.55); backdrop-filter:blur(4px); }
            .product-rig__panel { position:absolute; top:10%; right:18%; bottom:18%; width:18%; border-radius:18px; background:linear-gradient(180deg,rgba(16,24,36,.12),rgba(14,22,34,.34)); }
            .product-rig__gpu { position:absolute; left:28%; right:31%; bottom:31%; height:12%; border-radius:18px; background:linear-gradient(180deg,#111826,#20293b); box-shadow:0 10px 26px rgba(12,16,24,.26); }
            .product-rig__gpu::before { content:''; position:absolute; inset:18% 10% 20% auto; width:30%; border-radius:12px; background:linear-gradient(180deg,var(--slide-accent),rgba(255,255,255,.88)); box-shadow:0 0 16px rgba(255,255,255,.2); }
            .product-rig__cooler { position:absolute; left:36%; top:24%; width:22%; height:16%; border-radius:18px; background:linear-gradient(180deg,#eef5ff,#d9e5f4); box-shadow:0 8px 18px rgba(10,14,22,.14); }
            .product-rig__tube { position:absolute; top:29%; width:11%; height:2%; border-radius:999px; background:rgba(239,244,250,.92); box-shadow:0 0 0 1px rgba(255,255,255,.32); }
            .product-rig__tube--a { left:46%; transform:rotate(24deg); }
            .product-rig__tube--b { left:45%; top:33%; transform:rotate(54deg); }
            .product-rig__motherboard { position:absolute; left:45%; top:20%; width:14%; height:28%; border-radius:14px; background:linear-gradient(180deg,#1a2432,#263244); box-shadow:0 12px 20px rgba(10,16,22,.22); }
            .product-rig__fan { position:absolute; width:18%; aspect-ratio:1; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.96) 0 10%, var(--slide-accent) 10% 32%, rgba(255,255,255,.12) 32% 56%, rgba(255,255,255,.04) 56%); box-shadow:0 0 0 12px rgba(255,255,255,.08), 0 0 34px rgba(255,255,255,.24); }
            .product-rig__fan--rear { left:22%; top:28%; }
            .product-rig__fan--side-top { right:22%; top:26%; }
            .product-rig__fan--side-bottom { right:22%; top:47%; }
            .product-rig__fan--front { right:22%; top:68%; }
            .product-rig__foot { position:absolute; bottom:2%; width:10%; height:6%; border-radius:14px; background:linear-gradient(180deg,#f7fafc,#d4dde8); box-shadow:0 8px 14px rgba(11,17,25,.18); }
            .product-rig__foot--left { left:28%; }
            .product-rig__foot--right { right:28%; }
            .product-benchmark { position:absolute; inset:0; background:radial-gradient(circle at 50% 12%, rgba(255,255,255,.2), transparent 26%), linear-gradient(180deg,#7e512f,#f5a95f); }
            .product-benchmark__panel { position:absolute; inset:16% 15% 16%; border:1px solid rgba(255,255,255,.24); border-radius:0; background:linear-gradient(180deg,rgba(198,214,226,.55),rgba(194,169,141,.42)); box-shadow:inset 0 1px 0 rgba(255,255,255,.26), 0 18px 34px rgba(0,48,90,.12); }
            .product-benchmark__panel::before { content:''; position:absolute; left:14%; right:14%; top:14%; height:18px; background:linear-gradient(90deg,rgba(255,255,255,.88),rgba(199,244,255,.8)); }
            .product-benchmark__rows { position:absolute; left:18%; right:18%; top:26%; bottom:16%; display:grid; align-content:start; gap:20px; }
            .product-benchmark__row { display:block; }
            .product-benchmark__bar { position:relative; height:14px; border-radius:999px; background:rgba(255,255,255,.18); overflow:hidden; }
            .product-benchmark__bar::before { content:''; position:absolute; inset:0; width:var(--benchmark-level, 72%); border-radius:inherit; background:linear-gradient(90deg,rgba(255,255,255,.94),rgba(123,229,255,.94)); }
            .product-closeup { position:absolute; inset:0; overflow:hidden; }
            .product-closeup__crop { position:absolute; inset:0; background:radial-gradient(circle at 34% 28%, rgba(255,255,255,.26), transparent 18%), linear-gradient(180deg,var(--slide-from),var(--slide-to)); }
            .product-closeup__crop::before { content:''; position:absolute; inset:12% 32% 8% 18%; border-radius:22px; background:linear-gradient(180deg,rgba(255,255,255,.26),rgba(255,255,255,.04)); border:2px solid rgba(255,255,255,.48); }
            .product-closeup__crop::after { content:''; position:absolute; right:17%; top:18%; width:22%; aspect-ratio:1; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.96) 0 9%, var(--slide-accent) 9% 30%, rgba(255,255,255,.15) 30% 56%, rgba(255,255,255,.04) 56%); box-shadow:0 0 0 14px rgba(255,255,255,.08), 0 0 36px rgba(255,255,255,.22); }
            .product-closeup__gpu { position:absolute; left:24%; right:22%; bottom:28%; height:16%; border-radius:20px; background:linear-gradient(180deg,#101725,#1f2938); box-shadow:0 12px 26px rgba(9,14,22,.22); }
            .product-closeup__gpu::before { content:''; position:absolute; left:10%; right:32%; top:22%; height:28%; border-radius:10px; background:rgba(255,255,255,.08); }
            .product-closeup__gpu::after { content:''; position:absolute; right:10%; top:20%; width:18%; height:30%; border-radius:10px; background:linear-gradient(180deg,var(--slide-accent),rgba(255,255,255,.92)); }
            .product-closeup__cable { position:absolute; left:45%; bottom:18%; width:8%; height:24%; border-radius:999px; border:5px solid rgba(240,244,248,.92); border-top:0; border-left-color:rgba(227,235,242,.95); background:transparent; transform:skew(-8deg); }
            .product-closeup__fan { position:absolute; width:19%; aspect-ratio:1; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.96) 0 10%, var(--slide-accent) 10% 33%, rgba(255,255,255,.12) 33% 55%, rgba(255,255,255,.04) 55%); box-shadow:0 0 0 13px rgba(255,255,255,.08), 0 0 34px rgba(255,255,255,.2); }
            .product-closeup--inside .product-closeup__fan--a { left:10%; top:26%; }
            .product-closeup--inside .product-closeup__fan--b { right:10%; top:38%; }
            .product-closeup--detail .product-closeup__crop::before { inset:10% 12% 6% 42%; }
            .product-closeup--detail .product-closeup__crop::after { right:10%; top:16%; width:20%; }
            .product-closeup--detail .product-closeup__gpu { left:40%; right:8%; bottom:34%; height:14%; }
            .product-closeup--detail .product-closeup__cable { left:52%; bottom:12%; height:32%; }
            .product-closeup--detail .product-closeup__fan--a { left:72%; top:20%; }
            .product-closeup--detail .product-closeup__fan--b { left:72%; top:48%; }
            .product-gallery__thumbs { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:12px; }
            .product-gallery__thumb { display:block; padding:0; border:1px solid #d6dfeb; border-radius:0; background:#fff; cursor:pointer; box-shadow:0 10px 18px rgba(24,32,42,.04); transition:border-color .18s ease, transform .18s ease, box-shadow .18s ease; overflow:hidden; appearance:none; line-height:0; }
            .product-gallery__thumb:hover { transform:translateY(-2px); border-color:#c8d4e3; }
            .product-gallery__thumb.is-active { border-color:rgba(111,16,201,.46); box-shadow:0 0 0 2px rgba(111,16,201,.12); }
            .product-gallery__thumb-preview { display:block; width:100%; aspect-ratio:1/1; border-radius:0; background:linear-gradient(180deg,var(--slide-from),var(--slide-to)); position:relative; overflow:hidden; }
            .product-gallery__thumb-preview--hero::before { content:''; position:absolute; inset:14% 22% 18% 22%; border-radius:16px; background:linear-gradient(180deg,rgba(255,255,255,.92),rgba(214,224,236,.96)); box-shadow:0 10px 20px rgba(12,18,28,.18); }
            .product-gallery__thumb-preview--hero::after { content:''; position:absolute; left:50%; bottom:14px; width:46%; height:10px; transform:translateX(-50%); border-radius:50%; background:rgba(5,10,18,.18); filter:blur(5px); }
            .product-gallery__thumb-preview--performance::before { content:''; position:absolute; inset:12%; border-radius:12px; background:linear-gradient(180deg,rgba(255,255,255,.16),rgba(255,255,255,.08)); box-shadow:inset 0 1px 0 rgba(255,255,255,.22); }
            .product-gallery__thumb-preview--performance::after { content:''; position:absolute; left:20%; right:20%; top:24%; bottom:22%; background:repeating-linear-gradient(180deg, rgba(255,255,255,.82) 0 5px, transparent 5px 14px); opacity:.85; }
            .product-gallery__thumb-preview--inside::before, .product-gallery__thumb-preview--detail::before { content:''; position:absolute; inset:18% 16% auto auto; width:34%; aspect-ratio:1; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.94) 0 10%, rgba(118,233,255,.94) 10% 34%, rgba(255,255,255,.12) 34% 58%, rgba(255,255,255,.04) 58%); box-shadow:0 0 0 10px rgba(255,255,255,.08); }
            .product-gallery__thumb-preview--inside::after { content:''; position:absolute; left:18%; right:14%; bottom:22%; height:18%; border-radius:14px; background:linear-gradient(180deg,#101725,#1f2938); }
            .product-gallery__thumb-preview--detail::after { content:''; position:absolute; left:50%; right:12%; top:16%; bottom:16%; border-radius:14px; background:linear-gradient(180deg,rgba(255,255,255,.22),rgba(255,255,255,.04)); border:2px solid rgba(255,255,255,.38); }
            .product-fps { --product-fps-ratio:.44; display:grid; gap:14px; padding:18px 20px 20px; border:1px solid #dde4ee; background:#fff; box-shadow:0 16px 28px rgba(24,32,42,.06); }
            .product-fps__note { margin:0; color:#556171; font-size:14px; line-height:1.45; font-weight:700; }
            .product-fps__row { display:grid; grid-template-columns:minmax(0,.92fr) minmax(0,1.08fr) minmax(0,.82fr) minmax(0,.94fr); gap:14px; align-items:end; }
            .product-fps__field { position:relative; display:grid; gap:7px; }
            .product-fps__field span, .product-fps__meter-kicker { color:#596574; font-size:11px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
            .product-fps__field::after { content:''; position:absolute; right:18px; bottom:21px; width:10px; height:10px; border-right:2px solid #4c5766; border-bottom:2px solid #4c5766; transform:rotate(45deg); pointer-events:none; }
            .product-fps__field select { width:100%; min-height:50px; padding:0 48px 0 16px; border:1px solid #d5dde8; border-radius:0; background:#fff; color:#18202a; font-size:15px; font-weight:800; appearance:none; outline:none; transition:border-color .18s ease, background-color .18s ease, box-shadow .18s ease; }
            .product-fps__field select:hover, .product-fps__field select:focus { border-color:#bfc9d8; background:#fff; box-shadow:0 0 0 1px rgba(111,16,201,.08); }
            .product-fps__field select option { color:#18202a; background:#fff; }
            .product-fps__meter-wrap { display:grid; gap:7px; }
            .product-fps__meter { min-height:50px; display:grid; grid-template-columns:auto minmax(0,1fr) auto; gap:14px; align-items:center; padding:0 2px; }
            .product-fps__meter-label { color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:26px; font-weight:700; letter-spacing:-.04em; }
            .product-fps__meter-track { position:relative; display:block; height:8px; background:#11151a; box-shadow:inset 0 0 0 1px rgba(17,21,26,.06); overflow:hidden; }
            .product-fps__meter-fill { display:block; width:calc(var(--product-fps-ratio) * 100%); height:100%; background:linear-gradient(90deg,#2e7bff 0%, #7a52ff 42%, #ff1a55 100%); transform-origin:left center; transition:width .28s ease; }
            .product-fps__value { min-width:48px; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:34px; font-weight:700; line-height:1; letter-spacing:-.05em; text-align:right; }
            .product-fps__value.is-empty { min-width:40px; color:#6a7789; font-size:30px; text-align:center; }
            .product-fps__status { display:none; margin-top:2px; color:#6a7789; font-size:12px; font-weight:800; letter-spacing:.01em; text-transform:uppercase; }
            .product-fps__status.is-visible { display:block; }
            .product-about { display:grid; gap:24px; padding:14px 2px 0; }
            .product-about--mobile { display:none; }
            .product-about__title { margin:0; font-family:'Space Grotesk',sans-serif; font-size:clamp(36px,4vw,54px); font-weight:700; letter-spacing:-.05em; color:#11151a; }
            .product-about__lead { margin:0; color:#18202a; font-size:18px; line-height:1.55; font-weight:800; }
            .product-about__note { margin:0; color:#18202a; font-size:18px; line-height:1.5; font-weight:800; }
            .product-about__section { display:grid; gap:10px; }
            .product-about__section-title { margin:0; color:#18202a; font-size:18px; font-weight:800; line-height:1.4; }
            .product-about__list { margin:0; padding:0; list-style:none; display:grid; gap:6px; color:#18202a; font-size:17px; line-height:1.5; font-weight:700; }
            .product-about__list--dash li, .product-about__list--square li { position:relative; padding-left:18px; }
            .product-about__list--dash li::before { content:'-'; position:absolute; left:0; top:0; color:#475160; }
            .product-about__list--square li::before { content:''; position:absolute; left:2px; top:.66em; width:6px; height:6px; background:#566171; }
            .product-about__steps { margin:0; padding-left:24px; display:grid; gap:4px; color:#18202a; font-size:17px; line-height:1.5; font-weight:700; }
            .product-aside { display:grid; gap:22px; align-content:start; }
            .product-aside__title { margin:0; font-family:'Space Grotesk',sans-serif; font-size:clamp(38px,4vw,58px); line-height:.98; letter-spacing:-.05em; color:#11151a; }
            .product-aside__code { display:inline-flex; align-items:center; gap:8px; width:fit-content; padding:8px 12px; border:1px solid #dbe3ee; border-radius:999px; background:#f8fbff; color:#516072; font-size:13px; font-weight:800; letter-spacing:.02em; text-transform:uppercase; }
            .product-section-title { margin:4px 0 0; font-family:'Space Grotesk',sans-serif; font-size:clamp(30px,3vw,42px); font-weight:700; letter-spacing:-.04em; color:#11151a; }
            .product-specs { display:grid; gap:18px; }
            .product-spec { display:grid; grid-template-columns:32px minmax(0,1fr); gap:14px; align-items:start; }
            .product-spec__icon { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; color:#55606f; }
            .product-spec__icon svg { width:28px; height:28px; }
            .product-spec__body { display:grid; gap:4px; }
            .product-spec__value { font-size:16px; font-weight:800; line-height:1.42; color:#18202a; }
            .product-spec__label { color:#5f6b79; font-size:14px; font-weight:600; line-height:1.35; }
            .product-options__intro { margin:6px 0 2px; color:#5c6675; font-size:16px; font-weight:700; line-height:1.45; }
            .product-options { display:grid; gap:14px; }
            .product-option { border:1px solid #dde4ee; border-radius:22px; background:#fff; box-shadow:0 10px 24px rgba(24,32,42,.05); overflow:hidden; transition:border-color .18s ease, box-shadow .18s ease, background-color .18s ease; }
            .product-option.is-invalid { border-color:rgba(212,56,56,.44); background:linear-gradient(180deg,#fffdfd,#fff4f4); box-shadow:0 16px 28px rgba(181,53,53,.1); }
            .product-option summary { list-style:none; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:19px 22px; cursor:pointer; font-size:18px; font-weight:800; color:#18202a; }
            .product-option.is-invalid summary { color:#8f2222; }
            .product-option summary::-webkit-details-marker { display:none; }
            .product-option summary::after { content:''; width:11px; height:11px; margin-right:4px; border-right:2px solid #273140; border-bottom:2px solid #273140; transform:rotate(45deg); transition:transform .2s ease; }
            .product-option.is-invalid summary::after { border-color:#a12626; }
            .product-option[open] summary::after { transform:rotate(-135deg); margin-top:6px; }
            .product-option__panel { display:grid; gap:12px; padding:0 18px 18px; }
            .product-option__group-note { margin:2px 2px 0; color:#667282; font-size:13px; font-weight:700; line-height:1.5; }
            .product-option__error { margin:0 2px; padding:10px 12px; border:1px solid rgba(212,56,56,.22); border-radius:14px; background:linear-gradient(180deg,#fff7f7,#fff1f1); color:#8f2222; font-size:13px; font-weight:800; line-height:1.5; }
            .product-option__error[hidden] { display:none; }
            .product-choice { display:grid; grid-template-columns:auto 94px minmax(0,1fr) auto; gap:14px; align-items:flex-start; padding:14px 16px; border:1px solid #e3e8f0; border-radius:16px; background:#fbfcfe; transition:border-color .18s ease, background-color .18s ease, box-shadow .18s ease; }
            .product-choice:hover { border-color:#d0d9e6; }
            .product-choice.is-selected { border-color:rgba(111,16,201,.28); background:#faf5ff; box-shadow:0 10px 18px rgba(105,22,203,.08); }
            .product-choice.is-conflict { border-color:rgba(212,56,56,.44); background:linear-gradient(180deg,#fff8f8,#fff1f1); box-shadow:0 14px 24px rgba(181,53,53,.12); }
            .product-choice.is-conflict .product-choice__label,
            .product-choice.is-conflict .product-choice__price { color:#8f2222; }
            .product-choice.is-conflict .product-choice__preview { border-color:rgba(212,56,56,.35); box-shadow:0 14px 24px rgba(181,53,53,.1); }
            .product-choice input { margin-top:4px; accent-color:var(--primary); }
            .product-choice__media { display:block; }
            .product-choice__preview { display:block; width:94px; aspect-ratio:1/1; padding:0; border:1px solid #d7e0ec; border-radius:18px; background:linear-gradient(180deg,#f9fbfe,#edf3f9); box-shadow:0 10px 18px rgba(24,32,42,.05); overflow:hidden; cursor:zoom-in; transition:border-color .18s ease, transform .18s ease, box-shadow .18s ease; }
            .product-choice__preview:hover { transform:translateY(-1px); border-color:#c8d4e3; box-shadow:0 14px 24px rgba(24,32,42,.09); }
            .product-choice__preview img { display:block; width:100%; height:100%; object-fit:contain; background:#fff; }
            .product-choice__body { display:grid; gap:4px; }
            .product-choice__label { font-size:15px; font-weight:800; color:#18202a; }
            .product-choice__meta { color:#6d7787; font-size:13px; line-height:1.5; }
            .product-choice__price { align-self:center; font-size:14px; font-weight:800; color:#1d2531; white-space:nowrap; }
            .product-choice.is-selected .product-choice__price { color:var(--primary); }
            .product-option-lightbox { position:fixed; inset:0; z-index:140; display:none; align-items:center; justify-content:center; padding:28px; background:rgba(11,18,32,.78); backdrop-filter:blur(10px); }
            .product-option-lightbox.is-open { display:flex; }
            .product-option-lightbox__dialog { position:relative; display:grid; gap:16px; width:min(92vw,1080px); max-height:92vh; padding:22px; border:1px solid rgba(255,255,255,.18); border-radius:28px; background:rgba(12,18,28,.96); box-shadow:0 28px 70px rgba(3,7,18,.42); }
            .product-option-lightbox__close { position:absolute; top:16px; right:16px; display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; padding:0; border:1px solid rgba(255,255,255,.18); border-radius:999px; background:rgba(255,255,255,.08); color:#fff; font-size:28px; line-height:1; cursor:pointer; transition:background-color .18s ease, transform .18s ease; }
            .product-option-lightbox__close:hover { background:rgba(255,255,255,.14); transform:translateY(-1px); }
            .product-option-lightbox__nav { position:absolute; top:50%; z-index:2; display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; margin-top:-24px; padding:0; border:1px solid rgba(255,255,255,.2); border-radius:999px; background:rgba(11,18,32,.38); color:#fff; font-size:30px; line-height:1; cursor:pointer; transition:background-color .18s ease, transform .18s ease, opacity .18s ease; }
            .product-option-lightbox__nav:hover { background:rgba(11,18,32,.58); transform:translateY(-1px); }
            .product-option-lightbox__nav[hidden] { display:none; }
            .product-option-lightbox__nav--prev { left:16px; }
            .product-option-lightbox__nav--next { right:16px; }
            .product-option-lightbox__image { display:block; width:100%; max-height:calc(92vh - 170px); object-fit:contain; border-radius:20px; background:linear-gradient(180deg,#111827,#0f172a); }
            .product-option-lightbox__thumbs { display:grid; grid-template-columns:repeat(auto-fit, minmax(72px, 90px)); gap:10px; justify-content:center; }
            .product-option-lightbox__thumb { display:block; padding:0; border:1px solid rgba(255,255,255,.14); border-radius:16px; background:rgba(255,255,255,.04); cursor:pointer; overflow:hidden; transition:border-color .18s ease, transform .18s ease, box-shadow .18s ease; }
            .product-option-lightbox__thumb:hover { transform:translateY(-1px); border-color:rgba(255,255,255,.3); }
            .product-option-lightbox__thumb.is-active { border-color:rgba(255,255,255,.5); box-shadow:0 0 0 2px rgba(255,255,255,.08); }
            .product-option-lightbox__thumb img { display:block; width:100%; aspect-ratio:1/1; object-fit:cover; background:linear-gradient(180deg,#111827,#0f172a); }
            .product-option-lightbox__meta { display:grid; gap:6px; justify-items:center; }
            .product-option-lightbox__caption { margin:0; color:#eef2ff; font-size:14px; font-weight:700; line-height:1.45; text-align:center; }
            .product-option-lightbox__counter { margin:0; color:rgba(226,232,240,.74); font-size:12px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; text-align:center; }
            .product-compatibility { display:grid; gap:10px; margin-top:2px; padding:16px 18px; border:1px solid rgba(224,84,84,.18); border-radius:18px; background:linear-gradient(180deg,#fff8f8,#fff2f2); box-shadow:0 10px 22px rgba(181,53,53,.06); }
            .product-compatibility[hidden] { display:none; }
            .product-compatibility__title { color:#a12626; font-size:14px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
            .product-compatibility__list { margin:0; padding-left:18px; display:grid; gap:6px; color:#5c2222; font-size:14px; font-weight:700; line-height:1.45; }
            .product-pricing { display:grid; gap:0; border:1px solid #dde4ef; border-radius:26px; background:linear-gradient(180deg,#fff,#f7f9fd); box-shadow:0 14px 28px rgba(24,32,42,.06); overflow:hidden; }
            .product-pricing__row { display:grid; grid-template-columns:minmax(0,1fr) auto; gap:16px; align-items:end; padding:22px; }
            .product-pricing__row + .product-pricing__row { border-top:1px solid #e8edf4; }
            .product-pricing__label { color:#6d7787; font-size:16px; line-height:1.35; }
            .product-pricing__value { font-family:'Space Grotesk',sans-serif; font-size:clamp(28px,4vw,40px); font-weight:700; letter-spacing:-.04em; color:#121822; }
            .product-pricing__row--addons .product-pricing__value { font-size:clamp(26px,3.2vw,36px); }
            .product-pricing__row--total .product-pricing__value { font-size:clamp(34px,5vw,46px); }
            .product-pricing__row--total .product-pricing__label { color:#5a6472; font-weight:700; }
            .product-pricing__note { padding:18px 22px 22px; border-top:1px solid #edf1f7; color:#6d7787; font-size:14px; line-height:1.6; }
            .product-actions { display:grid; gap:12px; }
            .product-actions__controls { display:grid; grid-template-columns:92px minmax(0,1fr) minmax(0,.78fr); gap:14px; }
            .product-actions__qty { width:100%; min-height:58px; padding:0 14px; border:1px solid #cfd7e2; border-radius:16px; background:#fff; color:#18202a; font-size:20px; font-weight:800; text-align:center; box-shadow:0 10px 18px rgba(24,32,42,.05); }
            .product-actions__button { display:inline-flex; align-items:center; justify-content:center; min-height:58px; padding:0 24px; border:0; border-radius:16px; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; font-size:18px; font-weight:800; cursor:pointer; box-shadow:0 16px 28px rgba(105,22,203,.22); transition:transform .18s ease, box-shadow .18s ease, opacity .18s ease; }
            .product-actions__button:hover { transform:translateY(-1px); box-shadow:0 18px 30px rgba(105,22,203,.26); }
            .product-actions__button:disabled { opacity:.55; cursor:not-allowed; transform:none; box-shadow:0 10px 18px rgba(105,22,203,.12); }
            .product-actions__button--secondary { background:linear-gradient(180deg,#9872df,#7b59cb); box-shadow:0 14px 24px rgba(123,89,203,.14); }
            .product-actions__button.is-added { background:linear-gradient(180deg,#2fbf75,#159658); box-shadow:0 16px 28px rgba(21,150,88,.22); }
            .product-actions__feedback { min-height:20px; color:#687385; font-size:14px; }
            .product-actions__feedback.is-error { color:#a12626; font-weight:800; }
            .product-actions__share { display:flex; justify-content:flex-end; padding-top:2px; }
            .product-actions__share-button { display:inline-flex; align-items:center; justify-content:center; gap:10px; min-width:260px; min-height:52px; padding:0 20px; border:1px solid #d8e0eb; border-radius:16px; background:#fff; color:#18202a; font-size:15px; font-weight:800; box-shadow:0 10px 18px rgba(24,32,42,.05); cursor:pointer; transition:border-color .18s ease, transform .18s ease, box-shadow .18s ease, background-color .18s ease; }
            .product-actions__share-button svg { color:#5f6b79; }
            .product-actions__share-button:hover { transform:translateY(-1px); border-color:#c8d2df; background:#fbfcfe; box-shadow:0 14px 24px rgba(24,32,42,.08); }
            .product-shared-pill { display:inline-flex; align-items:center; flex-wrap:wrap; gap:12px; margin-top:12px; padding:12px 16px; border:1px solid #dbe5f1; border-radius:16px; background:linear-gradient(180deg,#fff,#f8fbff); color:#415065; box-shadow:0 10px 22px rgba(24,32,42,.05); }
            .product-shared-pill strong { color:#18202a; font-size:14px; font-weight:800; }
            .product-shared-pill span { color:#687385; font-size:13px; font-weight:700; }
            .product-shared-pill__status { display:inline-flex; align-items:center; min-height:30px; padding:0 12px; border-radius:999px; background:#eef4ff; color:#25426d; font-size:12px; font-weight:900; letter-spacing:.04em; text-transform:uppercase; }
            .product-shared-pill__feedback { display:block; width:100%; color:#3d4d61; font-size:13px; font-weight:700; }
            .product-shared-pill__feedback--success { color:#0f7a43; }
            .product-shared-pill__feedback--error { color:#b42318; }
            .preview-dock { position:fixed; left:50%; bottom:calc(env(safe-area-inset-bottom, 0px) + 18px); z-index:2100; display:flex; align-items:center; justify-content:space-between; gap:18px; width:min(calc(100vw - 28px), 1160px); padding:14px 16px; border:1px solid rgba(203,214,229,.92); border-radius:20px; background:rgba(255,255,255,.94); box-shadow:0 22px 44px rgba(15,23,42,.16); backdrop-filter:blur(18px); transform:translateX(-50%); }
            .preview-dock__meta { display:grid; gap:4px; min-width:0; }
            .preview-dock__eyebrow { display:inline-flex; align-items:center; gap:8px; color:#687385; font-size:11px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
            .preview-dock__eyebrow::before { content:''; width:8px; height:8px; border-radius:999px; background:#6f10c9; box-shadow:0 0 0 6px rgba(111,16,201,.12); }
            .preview-dock__title { color:#17202b; font-size:16px; font-weight:900; line-height:1.2; }
            .preview-dock__text { color:#5f6d80; font-size:13px; font-weight:700; line-height:1.45; }
            .preview-dock__feedback { color:#0f7a43; font-size:13px; font-weight:800; }
            .preview-dock__feedback--error { color:#b42318; }
            .preview-dock__actions { display:flex; align-items:center; justify-content:flex-end; gap:10px; flex-wrap:wrap; }
            .preview-dock__form { margin:0; }
            .preview-dock__button { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 18px; border:1px solid #d7e1ee; border-radius:999px; background:#fff; color:#1a2330; font-size:14px; font-weight:800; box-shadow:0 10px 18px rgba(24,32,42,.06); cursor:pointer; }
            .preview-dock__button:hover { background:#fbfcfe; border-color:#cbd7e6; }
            .preview-dock__button--primary { border-color:#6f10c9; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; box-shadow:0 14px 28px rgba(104,22,203,.24); }
            .preview-dock__button--primary:hover { background:linear-gradient(180deg,#8f2fff,#7420d3); border-color:#6517c9; }
            body.has-preview-dock { padding-bottom:120px; }
            body.has-preview-dock.has-admin-edit-mode-toggle { padding-bottom:168px; }
            body.has-preview-dock .admin-edit-mode-toggle { bottom:calc(env(safe-area-inset-bottom, 0px) + 94px); }
            .product-share-modal[hidden] { display:none; }
            .product-share-modal { position:fixed; inset:0; z-index:150; }
            .product-share-modal__backdrop { position:absolute; inset:0; background:rgba(15,22,34,.52); backdrop-filter:blur(6px); }
            .product-share-modal__dialog { position:relative; z-index:1; width:min(520px, calc(100% - 28px)); margin:10vh auto 0; display:grid; gap:16px; padding:26px; border:1px solid #dce4ef; border-radius:24px; background:#fff; box-shadow:0 28px 60px rgba(17,24,39,.18); }
            .product-share-modal__close { position:absolute; top:16px; right:16px; display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; padding:0; border:1px solid #d8e0eb; border-radius:14px; background:#fff; color:#5f6b79; font-size:22px; line-height:1; cursor:pointer; transition:border-color .18s ease, transform .18s ease, background-color .18s ease; }
            .product-share-modal__close:hover { transform:translateY(-1px); border-color:#c8d2df; background:#fbfcfe; }
            .product-share-modal__title { margin:0; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:32px; font-weight:700; letter-spacing:-.04em; }
            .product-share-modal__text { margin:0; color:#687385; font-size:15px; font-weight:700; line-height:1.55; }
            .product-share-modal__field { display:grid; gap:8px; }
            .product-share-modal__field span { color:#5f6b79; font-size:13px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; }
            .product-share-modal__field input { width:100%; min-height:54px; padding:0 18px; border:1px solid #d8e0eb; border-radius:16px; background:#fff; color:#18202a; font-size:16px; font-weight:700; box-shadow:inset 0 1px 0 rgba(255,255,255,.94); }
            .product-share-modal__meta { margin:0; color:#687385; font-size:14px; font-weight:700; }
            .product-share-modal__copy { display:inline-flex; align-items:center; justify-content:center; min-height:54px; padding:0 24px; border:0; border-radius:16px; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; font-size:16px; font-weight:800; cursor:pointer; box-shadow:0 16px 28px rgba(105,22,203,.22); transition:transform .18s ease, box-shadow .18s ease; }
            .product-share-modal__copy:hover { transform:translateY(-1px); box-shadow:0 18px 30px rgba(105,22,203,.26); }
            .product-gear { display:grid; gap:16px; padding:18px; border:1px solid #dde4ee; border-radius:22px; background:linear-gradient(180deg,#fff,#f8fbff); box-shadow:0 12px 24px rgba(24,32,42,.05); }
            .product-gear__copy { display:grid; gap:6px; }
            .product-gear__eyebrow { color:#6f10c9; font-size:12px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
            .product-gear__title { color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:28px; font-weight:700; line-height:1; letter-spacing:-.04em; }
            .product-gear__text { margin:0; color:#5f6b79; font-size:14px; font-weight:700; line-height:1.5; }
            .product-gear__grid { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:10px; }
            .product-gear__item { display:grid; gap:8px; align-content:start; min-height:100%; padding:14px 12px; border:1px solid #dbe3ee; border-radius:16px; background:#fff; box-shadow:0 8px 16px rgba(24,32,42,.04); transition:transform .18s ease, border-color .18s ease, box-shadow .18s ease, background-color .18s ease; cursor:pointer; text-align:left; appearance:none; }
            .product-gear__item:hover { transform:translateY(-1px); border-color:#cbd6e4; background:#fbfcfe; box-shadow:0 14px 22px rgba(24,32,42,.08); }
            .product-gear__item:focus-visible { outline:2px solid rgba(111,16,201,.28); outline-offset:2px; }
            .product-gear__icon { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:12px; background:linear-gradient(180deg,#f3ebff,#efe6ff); color:#6f10c9; }
            .product-gear__icon svg { width:18px; height:18px; }
            .product-gear__label { color:#18202a; font-size:14px; font-weight:800; line-height:1.25; }
            .product-gear__meta { color:#667282; font-size:12px; font-weight:700; line-height:1.4; }
            .product-gear__selection { color:#6f10c9; font-size:12px; font-weight:900; line-height:1.45; }
            .product-gear__item.is-selected { border-color:#cfaeff; background:linear-gradient(180deg,#fff,#faf6ff); box-shadow:0 16px 26px rgba(105,22,203,.1); }
            .product-gear__count { display:inline-flex; align-items:center; width:fit-content; min-height:24px; padding:0 10px; border-radius:999px; background:#f4efff; color:#6f10c9; font-size:11px; font-weight:900; letter-spacing:.06em; text-transform:uppercase; }
            .accessory-modal[hidden], .accessory-sheet[hidden] { display:none; }
            .accessory-modal, .accessory-sheet { position:fixed; inset:0; display:flex; align-items:center; justify-content:center; padding:24px; background:rgba(15,23,42,.58); backdrop-filter:blur(8px); }
            .accessory-modal { z-index:130; }
            .accessory-sheet { z-index:135; }
            .accessory-modal__dialog, .accessory-sheet__dialog { position:relative; width:min(1120px,calc(100vw - 32px)); max-height:calc(100vh - 40px); overflow:auto; padding:34px 36px; border:1px solid rgba(218,226,238,.86); border-radius:34px; background:linear-gradient(180deg,#fff,#f9fbff); box-shadow:0 30px 80px rgba(15,23,42,.24); }
            .accessory-sheet__dialog { width:min(980px,calc(100vw - 32px)); padding:36px 40px; }
            .accessory-modal__close, .accessory-sheet__close { position:absolute; top:18px; right:18px; display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; padding:0; border:0; background:transparent; color:#1b1f27; font-size:48px; line-height:1; cursor:pointer; }
            .accessory-modal__header, .accessory-sheet__header { display:grid; gap:8px; margin-bottom:22px; }
            .accessory-modal__eyebrow { color:#6f10c9; font-size:12px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
            .accessory-modal__title, .accessory-sheet__title { margin:0; color:#1b1f27; font-size:clamp(30px,3vw,42px); font-weight:900; letter-spacing:-.04em; }
            .accessory-modal__text { margin:0; color:#5f6b79; font-size:16px; font-weight:700; line-height:1.55; }
            .accessory-modal__grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; }
            .accessory-card { display:grid; gap:16px; padding:18px; border:1px solid #dae3ee; border-radius:24px; background:#fff; box-shadow:0 12px 26px rgba(24,32,42,.06); }
            .accessory-card__media { display:block; width:100%; padding:0; border:1px solid #d8e1ee; border-radius:22px; background:#fff; overflow:hidden; cursor:zoom-in; box-shadow:0 10px 22px rgba(24,32,42,.08); }
            .accessory-card__media img { display:block; width:100%; aspect-ratio:1 / 1; object-fit:cover; background:linear-gradient(180deg,#f4f7fb,#e8eef7); }
            .accessory-card__body { display:grid; gap:10px; }
            .accessory-card__vendor { color:#6f10c9; font-size:11px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
            .accessory-card__name { margin:0; color:#18202a; font-size:24px; font-weight:900; line-height:1.08; letter-spacing:-.03em; }
            .accessory-card__summary { margin:0; color:#5f6b79; font-size:14px; font-weight:700; line-height:1.55; }
            .accessory-card__price { margin:0; color:#18202a; font-size:18px; font-weight:900; line-height:1.15; }
            .accessory-card__actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:4px; }
            .accessory-card__button { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 16px; border:1px solid #d7deea; border-radius:14px; background:#fff; color:#18202a; font-size:14px; font-weight:800; cursor:pointer; box-shadow:0 10px 18px rgba(24,32,42,.05); transition:transform .18s ease, border-color .18s ease, box-shadow .18s ease, background-color .18s ease; }
            .accessory-card__button:hover { transform:translateY(-1px); border-color:#c8d2df; background:#fbfcfe; box-shadow:0 14px 22px rgba(24,32,42,.08); }
            .accessory-card__button--primary { border-color:#6f10c9; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; box-shadow:0 16px 28px rgba(105,22,203,.18); }
            .accessory-card__button--primary:hover { border-color:#6916cb; background:linear-gradient(180deg,#8f2fff,#7420d3); }
            .accessory-card__button--select { border-color:#18202a; background:#18202a; color:#fff; box-shadow:0 16px 24px rgba(24,32,42,.16); }
            .accessory-card__button--select:hover { border-color:#111723; background:#111723; }
            .accessory-card__button--select.is-selected { border-color:#0f8a48; background:linear-gradient(180deg,#12a150,#0f8a48); color:#fff; box-shadow:0 16px 24px rgba(15,138,72,.18); }
            .accessory-modal__empty { display:grid; gap:10px; padding:22px; border:1px dashed #d8e1ee; border-radius:24px; background:#fff; color:#5f6b79; font-size:15px; font-weight:700; line-height:1.55; }
            .accessory-modal__empty strong { color:#18202a; font-size:20px; }
            .accessory-sheet__rows { display:grid; gap:8px; }
            .accessory-sheet__spec { display:grid; grid-template-columns:minmax(180px,240px) minmax(0,1fr); gap:18px; align-items:start; padding:0 0 8px; color:#1b1f27; font-size:18px; font-weight:800; }
            .accessory-sheet__spec-label { display:block; padding:8px 0 0; }
            .accessory-sheet__spec-value { display:block; padding:8px 0 0; font-weight:900; }
            .accessory-sheet__spec.is-highlighted .accessory-sheet__spec-label,
            .accessory-sheet__spec.is-highlighted .accessory-sheet__spec-value { display:inline-flex; align-items:center; min-height:40px; padding:6px 16px; border-radius:14px; background:#ffb414; }
            .accessory-sheet__package-item { display:flex; align-items:flex-start; gap:16px; color:#1b1f27; font-size:18px; font-weight:900; }
            .accessory-sheet__package-badge { display:inline-flex; align-items:center; gap:14px; width:max-content; max-width:100%; padding:8px 14px; border-radius:14px; }
            .accessory-sheet__package-item.is-highlighted .accessory-sheet__package-badge { background:#ffb414; }
            .accessory-sheet__package-icon { display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; color:#1b1f27; flex:none; }
            .accessory-sheet__package-icon svg { width:22px; height:22px; }
            .product-tradein { display:grid; gap:16px; margin-top:28px; padding:28px; border:1px solid #dce5f0; border-radius:28px; background:linear-gradient(180deg,#fff,#f7fbff); box-shadow:0 16px 34px rgba(24,32,42,.06); }
            .product-tradein__eyebrow { color:#7a28dc; font-size:13px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
            .product-tradein__title { margin:0; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:clamp(28px,3vw,40px); font-weight:700; letter-spacing:-.04em; line-height:1.08; }
            .product-tradein__text { margin:0; color:#5c6675; font-size:17px; font-weight:700; line-height:1.55; }
            .product-tradein__button { display:inline-flex; align-items:center; justify-content:center; width:max-content; min-width:260px; min-height:54px; padding:0 24px; border-radius:16px; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; font-size:17px; font-weight:800; box-shadow:0 14px 24px rgba(105,22,203,.2); transition:transform .18s ease, box-shadow .18s ease; }
            .product-tradein__button:hover { transform:translateY(-1px); box-shadow:0 16px 28px rgba(105,22,203,.24); }
            .product-custom-cta { display:grid; justify-items:center; gap:16px; padding:72px 0 18px; text-align:center; }
            .product-custom-cta__title { margin:0; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:clamp(32px,3.6vw,52px); font-weight:700; letter-spacing:-.04em; }
            .product-custom-cta__text { margin:0; color:#5c6675; font-size:18px; font-weight:700; line-height:1.45; }
            .product-custom-cta__button { display:inline-flex; align-items:center; justify-content:center; min-width:290px; min-height:58px; padding:0 30px; border-radius:16px; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; font-size:18px; font-weight:800; box-shadow:0 16px 28px rgba(105,22,203,.22); transition:transform .18s ease, box-shadow .18s ease; }
            .product-custom-cta__button:hover { transform:translateY(-1px); box-shadow:0 18px 30px rgba(105,22,203,.26); }
            .product-related { display:grid; gap:26px; padding:58px 0 10px; }
            .product-related__title { margin:0; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:clamp(34px,3.5vw,50px); font-weight:700; letter-spacing:-.04em; text-align:center; }
            .product-related__grid { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:18px; }
            .product-related-card { display:grid; align-content:start; min-height:100%; border:1px solid #dfe5ef; border-radius:24px; background:linear-gradient(180deg,#fff,#f8fbff); box-shadow:0 18px 34px rgba(24,32,42,.06); overflow:hidden; transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
            .product-related-card:hover { transform:translateY(-3px); border-color:#ced8e6; box-shadow:0 24px 42px rgba(24,32,42,.08); }
            .product-related-card__media { position:relative; aspect-ratio:1/1.05; overflow:hidden; background:radial-gradient(circle at 50% 18%, rgba(255,255,255,.44), transparent 34%), linear-gradient(180deg,var(--related-from),var(--related-to)); }
            .product-related-card__glow { position:absolute; inset:18% 16% 14%; background:radial-gradient(circle, rgba(255,255,255,.32), transparent 68%); filter:blur(18px); }
            .product-related-card__tower { position:absolute; left:22%; right:22%; top:10%; bottom:8%; border-radius:18px; background:linear-gradient(180deg,#111826,#1b2534); box-shadow:0 24px 38px rgba(10,16,24,.22); }
            .product-related-card__tower::before { content:''; position:absolute; inset:10% 16% 16% 20%; border-radius:14px; background:linear-gradient(180deg,rgba(255,255,255,.18),rgba(255,255,255,.05)); border:1px solid rgba(255,255,255,.24); }
            .product-related-card__tower::after { content:''; position:absolute; left:50%; bottom:10px; width:42%; height:10px; transform:translateX(-50%); border-radius:50%; background:rgba(5,10,18,.3); filter:blur(5px); }
            .product-related-card__gpu { position:absolute; left:34%; right:30%; bottom:30%; height:10%; border-radius:14px; background:linear-gradient(180deg,#1a2432,#263244); box-shadow:0 10px 18px rgba(10,16,22,.22); }
            .product-related-card__gpu::after { content:''; position:absolute; right:10%; top:18%; width:20%; height:34%; border-radius:9px; background:linear-gradient(180deg,var(--related-accent),rgba(255,255,255,.9)); }
            .product-related-card__fans { position:absolute; top:20%; right:27%; bottom:18%; width:22%; background:
                radial-gradient(circle at 50% 12%, rgba(255,255,255,.96) 0 10%, var(--related-accent) 10% 28%, rgba(255,255,255,.12) 28% 50%, transparent 50% 100%),
                radial-gradient(circle at 50% 50%, rgba(255,255,255,.96) 0 10%, var(--related-accent) 10% 28%, rgba(255,255,255,.12) 28% 50%, transparent 50% 100%),
                radial-gradient(circle at 50% 88%, rgba(255,255,255,.96) 0 10%, var(--related-accent) 10% 28%, rgba(255,255,255,.12) 28% 50%, transparent 50% 100%);
                filter:drop-shadow(0 0 12px rgba(255,255,255,.16)); }
            .product-related-card__fan-back { position:absolute; left:26%; top:24%; width:16%; aspect-ratio:1; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.96) 0 12%, var(--related-accent) 12% 34%, rgba(255,255,255,.14) 34% 56%, rgba(255,255,255,.04) 56%); box-shadow:0 0 0 9px rgba(255,255,255,.06); }
            .product-related-card__body { display:grid; gap:16px; padding:18px 18px 20px; }
            .product-related-card__name { margin:0; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:clamp(24px,2vw,32px); font-weight:700; line-height:.98; letter-spacing:-.04em; }
            .product-related-card__specs { margin:0; padding:0; list-style:none; display:grid; gap:8px; }
            .product-related-card__specs li { display:grid; grid-template-columns:14px minmax(0,1fr); gap:10px; align-items:start; color:#435061; font-size:14px; font-weight:700; line-height:1.38; }
            .product-related-card__specs svg { width:14px; height:14px; color:#5c6674; margin-top:2px; }
            .product-related-card__price { margin-top:auto; color:#18202a; font-family:'Space Grotesk',sans-serif; font-size:clamp(28px,2.6vw,36px); font-weight:700; letter-spacing:-.05em; text-decoration:underline; text-decoration-thickness:2px; text-underline-offset:5px; }
            .product-related__footer { display:flex; justify-content:center; padding-top:6px; }
            .product-related__button { display:inline-flex; align-items:center; justify-content:center; min-width:180px; min-height:58px; padding:0 26px; border-radius:16px; background:linear-gradient(180deg,#8424f0,#6816cb); color:#fff; font-size:18px; font-weight:800; text-transform:uppercase; box-shadow:0 16px 28px rgba(105,22,203,.22); transition:transform .18s ease, box-shadow .18s ease; }
            .product-related__button:hover { transform:translateY(-1px); box-shadow:0 18px 30px rgba(105,22,203,.26); }
            .footer { position:relative; margin-top:72px; padding:84px 0 0; background:radial-gradient(circle at 12% 22%, rgba(132,36,240,.08), transparent 22%), radial-gradient(circle at 86% 78%, rgba(48,215,255,.06), transparent 24%), #fff; border-top:1px solid #e7ebf2; }
            .footer__grid { display:grid; grid-template-columns:minmax(260px,320px) minmax(170px,220px) minmax(220px,1fr); gap:44px 52px; align-items:flex-start; padding-bottom:52px; }
            .footer__brand, .footer__column, .footer__contacts, .footer__nav { display:grid; }
            .footer__brand { gap:20px; }
            .footer__logo { display:inline-flex; flex-direction:column; align-items:flex-start; gap:6px; }
            .footer__brand-name { font-family:'Space Grotesk',sans-serif; font-size:clamp(34px,4vw,48px); font-weight:700; letter-spacing:-.05em; color:#161c25; }
            .footer__brand-sub { color:#6c7583; font-size:15px; font-weight:700; }
            .footer__contacts { gap:12px; }
            .footer__contacts a { color:#1a212d; font-size:17px; font-weight:600; transition:color .18s ease; }
            .footer__contacts a:hover, .footer__nav a:hover { color:#6f10c9; }
            .footer__socials { flex-wrap:wrap; gap:12px; }
            .footer__social { display:inline-flex; align-items:center; justify-content:center; width:46px; height:46px; border:1px solid #dbe2ec; border-radius:50%; background:#fff; color:#1a212d; box-shadow:0 10px 20px rgba(24,32,42,.06); transition:transform .18s ease, border-color .18s ease, color .18s ease; }
            .footer__social:hover { transform:translateY(-2px); border-color:#c6d1df; color:#6f10c9; }
            .footer__column { gap:20px; padding-top:10px; }
            .footer__column--about, .footer__grid > .footer__column:first-of-type { justify-self:start; }
            .footer__title { margin:0; color:#151c25; font-family:'Space Grotesk',sans-serif; font-size:34px; font-weight:700; letter-spacing:-.04em; }
            .footer__nav { gap:14px; }
            .footer__nav a { color:#1a212d; font-size:18px; font-weight:600; line-height:1.35; transition:color .18s ease; }
            .footer__bottom { background:#2b272b; color:rgba(255,255,255,.96); }
            .footer__bottom-inner { display:flex; align-items:center; justify-content:center; min-height:54px; text-align:center; font-size:15px; font-weight:700; }
            @media (max-width:1320px) { .search-box { width:300px; } }
            @media (max-width:1180px) { .product-showcase { grid-template-columns:1fr; gap:28px; } .product-actions__controls { grid-template-columns:92px minmax(0,1fr) minmax(0,1fr); } .product-related__grid { grid-template-columns:repeat(2, minmax(0,1fr)); } }
            @media (max-width:1080px) { .header__actions > .header-button, .search-box { display:none; } .menu-toggle { display:inline-block; } .footer__grid { grid-template-columns:minmax(220px,280px) minmax(170px,210px) minmax(200px,1fr); gap:38px 44px; } .footer__title { font-size:30px; } .preview-dock { width:min(calc(100vw - 20px), 720px); padding:14px; gap:14px; } .preview-dock__title { font-size:15px; } .preview-dock__actions { justify-content:flex-start; } }
            @media (max-width:760px) {
                .container, .product-wrap { width:calc(100% - 20px); }
                .topbar { display:none; }
                .header__inner { display:grid; grid-template-columns:48px minmax(0,1fr) 48px; align-items:center; gap:10px; min-height:78px; }
                .header__actions { display:contents; }
                .brand { grid-column:2; grid-row:1; align-self:center; justify-self:center; min-width:0; }
                .brand > div { text-align:center; }
                .brand__name { font-size:22px; }
                .brand__sub { font-size:11px; }
                .header-cart-shell { grid-column:3; grid-row:1; align-self:center; justify-self:end; }
                .header-cart { width:44px; min-height:44px; padding:0; gap:0; border:0; border-radius:0; background:transparent; box-shadow:none; }
                .header-cart [data-cart-amount] { display:none; }
                .header-cart svg { width:32px; height:32px; color:#7c8592; }
                .menu-toggle { grid-column:1; grid-row:1; align-self:center; justify-self:start; display:inline-flex; flex-direction:column; align-items:center; justify-content:center; gap:5px; width:44px; height:44px; padding:0; border:0; border-radius:0; background:transparent; box-shadow:none; }
                .menu-toggle span { width:28px; height:3px; margin:0; border-radius:999px; background:#596270; }
                .menu-toggle:hover, .header-cart:hover { background:transparent; }
                .dropdown { width:calc(100% - 20px); }
                .dropdown__columns { flex-direction:column; gap:28px; padding:24px 20px; }
                .page { padding-top:18px; }
                .product-showcase { gap:22px; }
                .product-gallery__stage { padding:0; border-radius:0; }
                .product-gallery__info { top:14px; left:14px; }
                .product-gallery__slide { inset:0; border-radius:0; }
                .product-gallery__nav { width:40px; height:40px; margin-top:-20px; }
                .product-gallery__nav--prev { left:18px; }
                .product-gallery__nav--next { right:18px; }
                .product-rig { width:min(74%,420px); }
                .product-benchmark__panel { inset:15% 13% 15%; }
                .product-benchmark__rows { left:16%; right:16%; top:24%; bottom:15%; gap:18px; }
                .product-gallery__thumbs { gap:10px; }
                .product-fps { gap:12px; padding:16px; }
                .product-fps__row { grid-template-columns:1fr 1fr; }
                .product-fps__meter-wrap { grid-column:1 / -1; }
                .product-about--desktop { display:none; }
                .product-about--mobile { display:grid; }
                .product-about { gap:22px; padding-top:10px; }
                .product-about__title { font-size:34px; }
                .product-about__lead, .product-about__note, .product-about__section-title, .product-about__list, .product-about__steps { font-size:16px; }
                .product-option summary { padding:17px 18px; font-size:17px; }
                .product-option__panel { padding:0 14px 14px; }
                .product-choice { grid-template-columns:auto 76px minmax(0,1fr); }
                .product-choice__preview { width:76px; border-radius:16px; }
                .product-choice__price { white-space:normal; }
                .product-choice__price { grid-column:3; justify-self:start; }
                .product-option-lightbox { padding:16px; }
                .product-option-lightbox__dialog { width:100%; padding:18px 18px 16px; border-radius:24px; }
                .product-option-lightbox__nav { width:42px; height:42px; margin-top:-21px; font-size:26px; }
                .product-option-lightbox__nav--prev { left:10px; }
                .product-option-lightbox__nav--next { right:10px; }
                .product-option-lightbox__image { max-height:calc(88vh - 206px); border-radius:18px; }
                .product-option-lightbox__thumbs { grid-template-columns:repeat(4, minmax(0, 1fr)); gap:8px; }
                .product-option-lightbox__thumb { border-radius:14px; }
                .product-share-modal__dialog { width:min(100%, calc(100% - 20px)); margin-top:12vh; padding:22px 18px 18px; border-radius:20px; }
                .product-share-modal__title { font-size:26px; }
                .product-share-modal__field input { min-height:50px; font-size:15px; }
                .product-share-modal__copy { width:100%; min-height:52px; font-size:15px; }
                .product-pricing { padding:18px; }
                .product-actions__controls { grid-template-columns:1fr; }
                .product-actions__share { justify-content:stretch; }
                .product-actions__share-button { width:100%; min-width:0; }
                .product-gear__grid { grid-template-columns:repeat(2, minmax(0,1fr)); }
                .product-custom-cta { padding-top:56px; }
                .product-custom-cta__title { font-size:34px; }
                .product-custom-cta__text { font-size:16px; }
                .product-related { gap:22px; padding-top:46px; }
                .product-related__title { font-size:34px; }
                .footer { padding-top:64px; }
                .footer__grid { grid-template-columns:1fr; gap:34px; padding-bottom:40px; }
                .footer__column { padding-top:0; }
                .footer__title { font-size:28px; }
                .preview-dock { left:12px; right:12px; bottom:calc(env(safe-area-inset-bottom, 0px) + 12px); width:auto; transform:none; display:grid; gap:12px; }
                .preview-dock__actions { display:grid; gap:8px; }
                .preview-dock__form { width:100%; }
                .preview-dock__button { width:100%; }
                body.has-preview-dock { padding-bottom:232px; }
                body.has-preview-dock.has-admin-edit-mode-toggle { padding-bottom:284px; }
                body.has-preview-dock .admin-edit-mode-toggle { bottom:calc(env(safe-area-inset-bottom, 0px) + 196px); }
            }
            @media (max-width:560px) {
                .product-fps { gap:12px; padding:14px; }
                .product-fps__note { font-size:12px; }
                .product-fps__row { grid-template-columns:1fr; gap:12px; }
                .product-fps__field span, .product-fps__meter-kicker { font-size:10px; }
                .product-fps__field select { min-height:48px; font-size:14px; }
                .product-fps__field::after { right:16px; bottom:20px; width:9px; height:9px; }
                .product-fps__meter { min-height:48px; gap:10px; }
                .product-fps__meter-label { font-size:24px; }
                .product-fps__value { min-width:42px; font-size:30px; }
            }
            @media (max-width:560px) { .page { padding-top:16px; } .product-breadcrumbs { gap:8px; font-size:12px; } .product-gallery__stage { padding:0; border-radius:0; } .product-gallery__slide { inset:0; border-radius:0; } .product-gallery__info { top:12px; left:12px; gap:8px; } .product-gallery__info-button { width:30px; height:30px; } .product-gallery__info-button span { font-size:17px; } .product-gallery__info-tooltip { max-width:min(280px, calc(100vw - 72px)); padding:8px 12px; font-size:12px; } .product-gallery__nav { width:36px; height:36px; margin-top:-18px; } .product-gallery__nav--prev { left:12px; } .product-gallery__nav--next { right:12px; } .product-rig { width:min(78%,360px); } .product-benchmark__panel { inset:14% 12% 14%; } .product-benchmark__rows { left:15%; right:15%; top:23%; bottom:14%; gap:14px; } .product-gallery__thumbs { display:flex; overflow-x:auto; padding-bottom:2px; } .product-gallery__thumb { flex:0 0 96px; } .product-about { gap:18px; padding-top:8px; } .product-about__title { font-size:30px; } .product-about__lead, .product-about__note, .product-about__section-title, .product-about__list, .product-about__steps { font-size:15px; } .product-aside__title { font-size:34px; } .product-aside__code { font-size:12px; } .product-section-title { font-size:28px; } .product-spec { grid-template-columns:28px minmax(0,1fr); gap:12px; } .product-spec__icon { width:28px; height:28px; } .product-spec__icon svg { width:24px; height:24px; } .product-options__intro { font-size:15px; } .product-pricing__label { font-size:15px; } .product-pricing__value { font-size:28px; } .product-actions__button { font-size:16px; } .product-actions__share-button { min-height:50px; padding:0 18px; font-size:15px; } .product-gear { gap:14px; padding:16px; } .product-gear__title { font-size:24px; } .product-gear__grid { grid-template-columns:1fr; } .product-gear__item { padding:12px; } .product-tradein { gap:14px; margin-top:22px; padding:18px; border-radius:22px; } .product-tradein__title { font-size:26px; } .product-tradein__text { font-size:15px; } .product-tradein__button { width:100%; min-width:0; min-height:52px; font-size:15px; } .product-custom-cta { gap:14px; padding:44px 0 6px; } .product-custom-cta__title { font-size:28px; } .product-custom-cta__text { font-size:15px; } .product-custom-cta__button { width:100%; min-width:0; min-height:54px; padding:0 20px; font-size:16px; } .product-related { gap:18px; padding-top:34px; } .product-related__title { font-size:28px; } .product-related__grid { grid-template-columns:1fr; } .product-related-card__body { gap:14px; padding:16px 16px 18px; } .product-related-card__specs li { font-size:13px; } .product-related__button { width:100%; min-width:0; min-height:54px; font-size:16px; } .product-placeholder__copy { font-size:16px; } .footer__brand-name { font-size:36px; } .footer__nav a, .footer__contacts a { font-size:17px; } .footer__bottom-inner { min-height:64px; padding:10px 0; font-size:14px; } }
            @media (max-width:760px) {
                .accessory-modal,
                .accessory-sheet { padding:14px; }
                .accessory-modal__dialog,
                .accessory-sheet__dialog { width:min(100%,calc(100vw - 12px)); max-height:calc(100vh - 20px); padding:22px 18px; border-radius:26px; }
                .accessory-modal__close,
                .accessory-sheet__close { top:12px; right:12px; width:38px; height:38px; font-size:40px; }
                .accessory-modal__header,
                .accessory-sheet__header { margin-bottom:18px; }
                .accessory-modal__title,
                .accessory-sheet__title { padding-right:36px; font-size:28px; }
                .accessory-modal__text { font-size:15px; }
                .accessory-modal__grid { grid-template-columns:1fr; gap:14px; }
                .accessory-card { gap:14px; padding:14px; border-radius:20px; }
                .accessory-card__name { font-size:20px; }
                .accessory-card__actions { gap:8px; }
                .accessory-card__button { width:100%; }
                .accessory-sheet__spec { grid-template-columns:1fr; gap:8px; font-size:15px; }
                .accessory-sheet__spec-label { padding-top:0; }
                .accessory-sheet__spec-value { padding:10px 14px; border-radius:14px; }
                .accessory-sheet__package-badge { width:100%; justify-content:flex-start; }
                .accessory-sheet__package-item { gap:12px; font-size:15px; }
                .accessory-sheet__package-icon { width:36px; height:36px; border-radius:12px; }
            }
        </style>
    </head>
    <body @class(['has-preview-dock' => $isPreview ?? false])>
        @php
            $isPreview = (bool) ($isPreview ?? false);
            $previewGalleryImages = collect((array) ($build['preview_gallery_images'] ?? []))
                ->map(static fn ($value): string => trim((string) $value))
                ->filter()
                ->values()
                ->all();
            $storedGalleryImages = collect((array) ($build['gallery_images'] ?? []))
                ->map(static fn ($value): string => trim((string) $value))
                ->filter()
                ->values()
                ->all();
            $buildGalleryImages = $previewGalleryImages !== [] ? $previewGalleryImages : $storedGalleryImages;
            $hasUploadedBuildGallery = $buildGalleryImages !== [];
            $storefrontBuilds = \App\Support\StorefrontBuilds::all();
            $headerBuilds = array_slice($storefrontBuilds, 0, 4);
            $priceFormatter = static fn (int $value): string => number_format($value, 0, '', ' ') . ' ₴';
            $basePrice = (int) preg_replace('/\D+/', '', $build['price']);
            $tonePalettes = [
                'violet' => ['from' => '#5f6fff', 'to' => '#1d9bff', 'accent' => '#d357ff', 'panel' => '#f5f0ff'],
                'magenta' => ['from' => '#8a4dff', 'to' => '#ff4fb2', 'accent' => '#ffbf6b', 'panel' => '#fff1f8'],
                'amber' => ['from' => '#8a5b3b', 'to' => '#f4a65e', 'accent' => '#ffd36a', 'panel' => '#fff5ea'],
                'peach' => ['from' => '#b8726f', 'to' => '#ff9a67', 'accent' => '#ffc06e', 'panel' => '#fff3ef'],
                'emerald' => ['from' => '#1f7f69', 'to' => '#35c79d', 'accent' => '#8dffbc', 'panel' => '#edfff7'],
            ];
            $palette = $tonePalettes[$build['tone']] ?? $tonePalettes['violet'];
            $caseLabel = match ($build['tone']) {
                'violet' => 'Panoramic RGB case',
                'magenta' => 'Showcase glass case',
                'amber' => 'Airflow bronze edition',
                'peach' => 'Creator airflow case',
                'emerald' => 'Storm mesh chassis',
                default => 'KondorPC custom case',
            };
            $boardBase = str_contains($build['cpu'], 'AMD') ? 'B650 Wi-Fi' : 'Z790 Wi-Fi';
            $powerBase = $basePrice >= 100000 ? '850W 80+ Gold' : '650W 80+ Bronze';
            $powerUpgrade = $basePrice >= 100000 ? '1000W 80+ Gold' : '850W 80+ Gold';
            $appearanceHint = 'Зовнішній вигляд комп\'ютера залежить від обраних комплектуючих.';
            $accessoryGroups = is_array($accessoryGroups ?? null) ? $accessoryGroups : \App\Support\AccessoryCatalog::storefrontGroups();
            /* Legacy device redirects removed from storefront.
            $deviceLinks = [
                [
                    'label' => 'Клавіатури',
                    'meta' => 'Kondor Orion та інші серії',
                    'href' => 'https://www.kondordevice.com/catalog/klaviaturi-orion',
                    'icon' => 'keyboard',
                ],
                [
                    'label' => 'Миші',
                    'meta' => 'Під ігри, шутери та daily use',
                    'href' => $deviceCatalogUrl,
                    'icon' => 'mouse',
                ],
                [
                    'label' => 'Килимки',
                    'meta' => 'Ігрові поверхні для сетапу',
                    'href' => $deviceCatalogUrl,
                    'icon' => 'pad',
                ],
            ];
            */
            $performanceLine = match (true) {
                $build['fps_score'] >= 160 => 'забезпечують високий FPS у сучасних іграх',
                $build['fps_score'] >= 120 => 'забезпечують впевнений FPS у сучасних іграх',
                default => 'дають збалансовану продуктивність у сучасних іграх',
            };
            $benchmarkResolution = $build['fps_score'] >= 140
                ? '2K роздільній здатності (2560×1440 / 1440p)'
                : 'Full HD роздільній здатності (1920×1080 / 1080p)';
            $productSpecs = $build['product_specs'] ?? [
                ['icon' => 'gpu', 'label' => 'Відеокарта', 'value' => $build['gpu']],
                ['icon' => 'cpu', 'label' => 'Процесор', 'value' => $build['cpu']],
                ['icon' => 'ram', 'label' => 'Оперативна пам\'ять', 'value' => $build['ram']],
                ['icon' => 'storage', 'label' => 'Накопичувач', 'value' => $build['storage']],
            ];
            $productAbout = $build['about'] ?? [
                'intro' => [
                    $build['name'] . ' — потужна та стильна ігрова збірка.',
                    $build['gpu'] . ' і ' . $build['cpu'] . ' ' . $performanceLine . ', а ' . $build['ram'] . ' та ' . $build['storage'] . ' гарантують швидку та стабільну роботу системи.',
                ],
                'notes' => [
                    'Можливий у білому та чорному виконанні',
                    'Тести було записано в ' . $benchmarkResolution,
                    'Важливо: всі деталі нові',
                    '* ГАРАНТІЯ на комп\'ютер — 24/36 місяців *',
                ],
                'setup_title' => 'На комп\'ютері буде зроблено',
                'setup_items' => [
                    'Встановлення Windows 11 Pro та повний набір драйверів',
                    'Ретельне тестування ПК перед відправкою',
                    'ПК буде повністю готовий до використання',
                ],
                'delivery_title' => 'Оплата та Доставка',
                'delivery_items' => [
                    'Самовивіз з нашого офісу (м. Київ, вул. Дмитра Багалія 4)',
                    'Доставка Новою поштою',
                ],
                'delivery_steps' => [
                    'Накладним платежем з оплатою на пошті',
                    'З повною передоплатою звичайною доставкою',
                ],
                'warranty_title' => 'Умови гарантії та повернення',
                'warranty_items' => [
                    '14 днів на перевірку та повернення у разі відсутності пошкоджень та змін в конфігурації системи',
                    'Безкоштовна online-консультація протягом всього часу після купівлі збірки',
                    'Безкоштовний ремонт протягом всього гарантійного терміну, при дотриманні гарантійних умов',
                ],
            ];
            $productAbout = \App\Support\BuildAbout::resolve([
                ...$build,
                'about' => $productAbout,
            ]);
            $relatedBuilds = [];
            foreach ($storefrontBuilds as $candidateBuild) {
                if (($candidateBuild['slug'] ?? null) === ($build['slug'] ?? null)) {
                    continue;
                }

                $candidatePalette = $tonePalettes[$candidateBuild['tone'] ?? 'violet'] ?? $tonePalettes['violet'];
                $candidateBuild['price_value'] = (int) preg_replace('/\D+/', '', $candidateBuild['price'] ?? '');
                $candidateBuild['palette'] = $candidatePalette;
                $relatedBuilds[] = $candidateBuild;

                if (count($relatedBuilds) === 4) {
                    break;
                }
            }
            $productSlides = [
                ['id' => 'hero', 'thumb' => 'Головний вигляд', 'eyebrow' => 'Showcase build', 'title' => $build['name'], 'meta' => $build['gpu'] . ' • ' . $build['cpu'], 'variant' => 'hero'],
                ['id' => 'performance', 'thumb' => 'FPS profile', 'eyebrow' => '2K / High ready', 'title' => 'Під геймінг та стрім', 'meta' => 'FPS рейтинг ' . $build['fps_score'] . ' • ' . $build['ram'], 'variant' => 'performance'],
                ['id' => 'inside', 'thumb' => 'Внутрішній монтаж', 'eyebrow' => 'Cable management', 'title' => 'Чисте складання всередині', 'meta' => $caseLabel . ' • ' . $build['storage'], 'variant' => 'inside'],
                ['id' => 'detail', 'thumb' => 'Деталі', 'eyebrow' => 'Custom fit', 'title' => 'ARGB, airflow і запас під апгрейд', 'meta' => $boardBase . ' • ' . $powerUpgrade, 'variant' => 'detail'],
            ];
            if ($hasUploadedBuildGallery) {
                $productSlides = collect($buildGalleryImages)
                    ->values()
                    ->map(static function (string $imageUrl, int $index) use ($build): array {
                        $photoNumber = $index + 1;

                        return [
                            'id' => 'photo-' . $photoNumber,
                            'thumb' => $index === 0 ? 'Р“РѕР»РѕРІРЅРµ С„РѕС‚Рѕ' : 'Р¤РѕС‚Рѕ ' . $photoNumber,
                            'eyebrow' => $index === 0 ? 'РћСЃРЅРѕРІРЅРёР№ СЂР°РєСѓСЂСЃ' : 'Р Р°РєСѓСЂСЃ ' . $photoNumber,
                            'title' => (string) ($build['name'] ?? ''),
                            'meta' => 'Р РµР°Р»СЊРЅРµ С„РѕС‚Рѕ Р·Р±С–СЂРєРё',
                            'variant' => 'uploaded',
                            'image_url' => $imageUrl,
                        ];
                    })
                    ->all();
            }

            /*
            $productFpsGames = [
                ['id' => 'cyberpunk-2077', 'name' => 'Cyberpunk 2077', 'label' => 'Cyberpunk', 'difficulty' => 0.72, 'accent' => '#f4dc39', 'from' => '#0f182f', 'to' => '#2b1211'],
                ['id' => 'gta-5', 'name' => 'GTA 5', 'label' => 'GTA V', 'difficulty' => 1.12, 'accent' => '#8cff7c', 'from' => '#10151d', 'to' => '#183625'],
                ['id' => 'counter-strike-2', 'name' => 'Counter-Strike 2', 'label' => 'CS2', 'difficulty' => 1.65, 'accent' => '#ffb35c', 'from' => '#10151d', 'to' => '#31200f'],
                ['id' => 'fortnite', 'name' => 'Fortnite', 'label' => 'Fortnite', 'difficulty' => 1.38, 'accent' => '#57d8ff', 'from' => '#10162a', 'to' => '#15384a'],
                ['id' => 'valorant', 'name' => 'Valorant', 'label' => 'Valorant', 'difficulty' => 1.92, 'accent' => '#ff637b', 'from' => '#14131d', 'to' => '#321019'],
                ['id' => 'stalker-2', 'name' => 'S.T.A.L.K.E.R. 2', 'label' => 'Stalker 2', 'difficulty' => 0.68, 'accent' => '#a3ff63', 'from' => '#131816', 'to' => '#2b2210'],
            ];
            $productFpsDisplays = [
                ['id' => '1080p', 'name' => '1920 x 1080 (Full HD)', 'mobile_name' => 'Full HD', 'multiplier' => 1.22],
                ['id' => '1440p', 'name' => '2560 x 1440 (2K)', 'mobile_name' => '2K', 'multiplier' => 1.0],
                ['id' => '4k', 'name' => '3840 x 2160 (4K)', 'mobile_name' => '4K', 'multiplier' => 0.7],
            ];
            $productFpsPresets = [
                ['id' => 'ultra', 'name' => 'Ультра', 'multiplier' => 0.88],
                ['id' => 'high', 'name' => 'Високі', 'multiplier' => 1.0],
                ['id' => 'medium', 'name' => 'Середні', 'multiplier' => 1.14],
            ];
            $defaultProductFpsGame = 'cyberpunk-2077';
            $defaultProductFpsDisplay = '1440p';
            $defaultProductFpsPreset = 'high';
            $productFpsIndexById = static function (array $items): array {
                $indexed = [];

                foreach ($items as $item) {
                    $indexed[$item['id']] = $item;
                }

                return $indexed;
            };
            $productFpsGameMap = $productFpsIndexById($productFpsGames);
            $productFpsDisplayMap = $productFpsIndexById($productFpsDisplays);
            $productFpsPresetMap = $productFpsIndexById($productFpsPresets);

            */
            $fpsCatalog = \App\Support\FpsCatalog::all();
            $productFpsGames = $fpsCatalog['games'];
            $productFpsDisplays = $fpsCatalog['displays'];
            $productFpsPresets = $fpsCatalog['presets'];

            $productFpsIndexById = static function (array $items): array {
                $indexed = [];

                foreach ($items as $item) {
                    $indexed[$item['id']] = $item;
                }

                return $indexed;
            };

            $defaultProductFpsState = \App\Support\FpsProfiles::defaultState(
                $fpsCatalog,
                (array) ($build['fps_profiles'] ?? []),
            );
            $defaultProductFpsGame = $defaultProductFpsState['game'] ?? ($productFpsGames[0]['id'] ?? '');
            $defaultProductFpsDisplay = $defaultProductFpsState['display'] ?? ($productFpsDisplays[0]['id'] ?? '');
            $defaultProductFpsPreset = $defaultProductFpsState['preset'] ?? ($productFpsPresets[0]['id'] ?? '');

            $productFpsGameMap = $productFpsIndexById($productFpsGames);
            $productFpsDisplayMap = $productFpsIndexById($productFpsDisplays);
            $productFpsPresetMap = $productFpsIndexById($productFpsPresets);

            if (! isset($productFpsGameMap[$defaultProductFpsGame])) {
                $defaultProductFpsGame = array_key_first($productFpsGameMap);
            }

            if (! isset($productFpsDisplayMap[$defaultProductFpsDisplay])) {
                $defaultProductFpsDisplay = array_key_first($productFpsDisplayMap);
            }

            if (! isset($productFpsPresetMap[$defaultProductFpsPreset])) {
                $defaultProductFpsPreset = array_key_first($productFpsPresetMap);
            }

            $productFpsProfiles = \App\Support\FpsProfiles::normalize(
                (array) ($build['fps_profiles'] ?? []),
                $fpsCatalog,
            );
            $productFpsLookup = \App\Support\FpsProfiles::makeLookup($productFpsProfiles);
            $resolveProductFpsRatio = static fn (int $fps): float => $fps > 0 ? max(0.18, min(1, $fps / 220)) : 0;
            $initialProductFps = $productFpsProfiles !== []
                ? \App\Support\FpsProfiles::resolve(
                    $productFpsLookup,
                    $productFpsProfiles,
                    (string) ($defaultProductFpsGame ?? ''),
                    (string) ($defaultProductFpsDisplay ?? ''),
                    (string) ($defaultProductFpsPreset ?? ''),
                    0,
                )
                : 0;
            $productFpsClientConfig = [
                'defaults' => [
                    'game' => $defaultProductFpsGame,
                    'display' => $defaultProductFpsDisplay,
                    'preset' => $defaultProductFpsPreset,
                ],
                'games' => $productFpsGames,
                'displays' => $productFpsDisplays,
                'presets' => $productFpsPresets,
                'lookup' => $productFpsLookup,
            ];
            $sharedBuildLink = $sharedBuildLink ?? null;
            $sharedBuildPayload = is_array($sharedBuildPayload ?? null) ? $sharedBuildPayload : [];
            $sharedBuildSelection = is_array($sharedBuildSelection ?? null) ? $sharedBuildSelection : [];
            $sharedAccessorySelections = [];
            $productConfigurator = \App\Support\BuildConfigurator::storefrontPayload($build);
            $productConfiguratorEnabled = (bool) ($productConfigurator['enabled'] ?? false);
            $productConfiguratorClient = $productConfigurator['client'] ?? ['enabled' => false];
            $productCompatibility = $productConfigurator['compatibility'] ?? ['is_valid' => true, 'messages' => []];
            $productOptions = [];
            $defaultAdditionalPrice = 0;
            $previewToken = is_string($previewToken ?? null) ? trim((string) $previewToken) : '';
            $previewBackUrl = is_string($build['preview_back_url'] ?? null) ? trim((string) ($build['preview_back_url'] ?? '')) : '';
            $previewPersistUrl = $isPreview && $previewToken !== '' ? route('product.preview.persist', ['token' => $previewToken]) : '';
            $previewPersistState = is_array(session('previewPersistState')) ? session('previewPersistState') : [];
            $accessoryLookup = collect($accessoryGroups ?? [])
                ->flatMap(static fn (array $group) => collect($group['items'] ?? [])
                    ->mapWithKeys(static fn (array $item): array => [((string) ($item['slug'] ?? '')) => $item]))
                ->filter(static fn ($item, string $slug): bool => $slug !== '')
                ->all();
            $previewPersistError = trim((string) session('previewPersistError', ''));
            $previewStatusLabel = (bool) ($build['is_active'] ?? false) ? 'Опубліковано' : 'Чернетка';

            $initialTotalPrice = $basePrice + $defaultAdditionalPrice;

            if ($productConfiguratorEnabled) {
                $resolvedConfiguratorSelection = $productConfigurator['defaults'] ?? [];

                if ($sharedBuildSelection !== []) {
                    $sharedResolvedConfigurator = \App\Support\BuildConfigurator::resolvePayloadSelection($productConfigurator, $sharedBuildSelection);
                    $resolvedConfiguratorSelection = $sharedResolvedConfigurator['selection'] ?? $resolvedConfiguratorSelection;
                    $productCompatibility = $sharedResolvedConfigurator['compatibility'] ?? $productCompatibility;
                    $defaultAdditionalPrice = (int) ($sharedBuildPayload['additional_price'] ?? $sharedResolvedConfigurator['additional_price'] ?? 0);
                    $initialTotalPrice = (int) ($sharedBuildPayload['total_price'] ?? $sharedResolvedConfigurator['total_price'] ?? ($basePrice + $defaultAdditionalPrice));
                } else {
                    $defaultAdditionalPrice = (int) ($productConfigurator['additionalPrice'] ?? 0);
                    $initialTotalPrice = (int) ($productConfigurator['totalPrice'] ?? ($basePrice + $defaultAdditionalPrice));
                }

                $productConfigurator['defaults'] = $resolvedConfiguratorSelection;
                $productConfigurator['client']['defaults'] = $resolvedConfiguratorSelection;
                $productOptions = collect($productConfigurator['groups'] ?? [])
                    ->map(static function (array $group) use ($resolvedConfiguratorSelection): array {
                        $groupKey = (string) ($group['key'] ?? '');

                        return [
                            'id' => $groupKey,
                            'title' => (string) ($group['title'] ?? ''),
                            'slot' => (string) ($group['slot'] ?? ''),
                            'description' => $group['description'] ?? null,
                            'options' => collect($group['options'] ?? [])
                                ->map(static function (array $option) use ($resolvedConfiguratorSelection, $groupKey): array {
                                    $imageUrl = (string) (
                                        $option['component']['image_url']
                                        ?? \App\Support\ComponentImages::placeholderUrl(
                                            (string) ($option['component']['type'] ?? 'other'),
                                            (string) ($option['label'] ?? ''),
                                        )
                                    );

                                    return [
                                        'key' => (string) ($option['key'] ?? ''),
                                        'label' => (string) ($option['label'] ?? ''),
                                        'description' => (string) ($option['description'] ?? ($option['component']['summary'] ?? '')),
                                        'image_url' => $imageUrl,
                                        'image_urls' => array_values(array_filter(array_map(
                                            static fn ($value): string => trim((string) $value),
                                            is_array($option['component']['image_urls'] ?? null) ? $option['component']['image_urls'] : [$imageUrl],
                                        ))),
                                        'price' => (int) ($option['price'] ?? $option['price_delta'] ?? 0),
                                        'is_default' => (bool) ($option['is_default'] ?? false),
                                        'selected' => (($resolvedConfiguratorSelection[$groupKey] ?? ($option['is_default'] ?? false ? (string) ($option['key'] ?? '') : null)) === (string) ($option['key'] ?? '')),
                                        'component_id' => $option['component_id'] ?? null,
                                    ];
                                })
                                ->values()
                                ->all(),
                        ];
                    })
                    ->values()
                    ->all();
            }

            foreach ($sharedBuildSelection as $selectionKey => $selectionValue) {
                $selectionKey = trim((string) $selectionKey);

                if (! str_starts_with($selectionKey, 'accessory_')) {
                    continue;
                }

                $type = substr($selectionKey, strlen('accessory_'));
                $slug = trim((string) $selectionValue);
                $item = $accessoryLookup[$slug] ?? null;

                if ($type === '' || ! is_array($item)) {
                    continue;
                }

                $sharedAccessorySelections[$type] = [
                    'slug' => $slug,
                    'name' => (string) ($item['name'] ?? ''),
                    'price' => (int) ($item['price_raw'] ?? 0),
                ];
            }
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
                        <a class="header-button header-button--primary" href="{{ route('catalog') }}">
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
                        <a class="header-link--primary" href="{{ route('catalog') }}">До каталогу</a>
                        <a class="header-link" href="{{ url('/') }}">Головна</a>
                        <a class="header-link" href="{{ route('catalog') }}">Каталог збірок</a>
                        <a class="header-cart" href="#contacts" aria-label="Кошик"><span>0 ₴</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="9" cy="19" r="1.6" fill="currentColor"/><circle cx="17" cy="19" r="1.6" fill="currentColor"/><path d="M3 5H5L7.4 15H18.2L20.4 8H8.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                        @include('partials.header-cart')
                        <button class="menu-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu"><span></span><span></span><span></span></button>
                    </div>
                </div>
                <div class="dropdown" id="builds-dropdown" data-dropdown-panel="builds">
                    <div class="dropdown__columns">
                        <div class="dropdown__group">
                            <h3>Популярні збірки</h3>
                            @foreach ($headerBuilds as $menuBuild)
                                <a href="{{ route('product.show', ['slug' => $menuBuild['slug']]) }}">{{ $menuBuild['name'] }}</a>
                            @endforeach
                        </div>

                        <div class="dropdown__group">
                            <h3>Швидкі переходи</h3>
                            <a href="{{ route('catalog') }}">Всі збірки</a>
                            <a href="{{ url('/') }}">Головна</a>
                            <a href="{{ url('/') }}#gallery">Наші роботи</a>
                            <a href="{{ url('/') }}#faq">FAQ</a>
                        </div>

                        <div class="dropdown__group">
                            <h3>Під замовлення</h3>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Підбір під бюджет</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Апгрейд конфігурації</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Збірка для стріму</a>
                            <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Консультація</a>
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
                        <a href="{{ url('/') }}">Головна</a>
                        <a href="{{ route('catalog') }}">Каталог збірок</a>
                        <a href="{{ url('/') }}#about">Про нас</a>
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
                <div class="product-wrap">
                    <section class="product-hero">
                        <div class="product-breadcrumbs">
                            <a href="{{ url('/') }}">Головна</a>
                            <span>/</span>
                            <a href="{{ route('catalog') }}">Каталог</a>
                            <span>/</span>
                            <span>{{ $build['name'] }}</span>
                        </div>

                        @if ($sharedBuildLink)
                            <div class="product-shared-pill">
                                <strong>Поділена конфігурація</strong>
                                <span>Активна до {{ $sharedBuildLink->expires_at?->format('d.m.Y') }}</span>
                            </div>
                        @elseif ($isPreview)
                            <div class="product-shared-pill">
                                <strong>Перегляд чернетки</strong>
                                <span>Ти дивишся поточний стан форми до збереження змін.</span>
                                <span class="product-shared-pill__status">{{ $previewStatusLabel }}</span>
                                @if (($previewPersistState['message'] ?? '') !== '')
                                    <span class="product-shared-pill__feedback product-shared-pill__feedback--success">{{ $previewPersistState['message'] }}</span>
                                @elseif ($previewPersistError !== '')
                                    <span class="product-shared-pill__feedback product-shared-pill__feedback--error">{{ $previewPersistError }}</span>
                                @endif
                            </div>
                        @endif
                    </section>

                    <section class="product-showcase">
                        <div class="product-gallery">
                            <div class="product-gallery__stage">
                                <div class="product-gallery__info" data-gallery-info>
                                    <button class="product-gallery__info-button" type="button" data-gallery-info-toggle aria-label="Інформація про вигляд збірки" aria-expanded="false">
                                        <span aria-hidden="true">i</span>
                                    </button>
                                    <span class="product-gallery__info-tooltip" role="tooltip">{{ $appearanceHint }}</span>
                                </div>

                                @foreach ($productSlides as $slide)
                                    @php
                                        $slideImageUrl = trim((string) ($slide['image_url'] ?? ''));
                                        $slideImageKey = null;

                                        if ($slideImageUrl === '') {
                                            $slideImageKey = $slide['variant'] === 'hero'
                                                ? 'build.' . $build['slug'] . '.cover'
                                                : 'build.' . $build['slug'] . '.gallery.' . $slide['variant'];
                                            $slideImageUrl = (string) (\App\Support\SiteImages::url($slideImageKey) ?? '');
                                        }
                                    @endphp
                                    <div
                                        class="product-gallery__slide product-gallery__slide--{{ $slide['variant'] }}{{ $loop->first ? ' is-active' : '' }} site-image-target{{ $slideImageUrl ? ' has-site-image' : '' }}"
                                        @if ($slideImageKey)
                                            data-site-image-key="{{ $slideImageKey }}"
                                        @endif
                                        data-product-slide="{{ $loop->index }}"
                                        style="--slide-from: {{ $palette['from'] }}; --slide-to: {{ $palette['to'] }}; --slide-accent: {{ $palette['accent'] }};@if ($slideImageUrl) --site-image-url: url('{{ $slideImageUrl }}');@endif"
                                    >
                                        <div class="product-gallery__photo">
                                            @if ($slide['variant'] === 'performance')
                                                <div class="product-benchmark" aria-hidden="true">
                                                    <div class="product-benchmark__rows">
                                                        <div class="product-benchmark__row">
                                                            <span class="product-benchmark__bar" style="--benchmark-level: 72%;"></span>
                                                        </div>
                                                        <div class="product-benchmark__row">
                                                            <span class="product-benchmark__bar" style="--benchmark-level: 94%;"></span>
                                                        </div>
                                                        <div class="product-benchmark__row">
                                                            <span class="product-benchmark__bar" style="--benchmark-level: 78%;"></span>
                                                        </div>
                                                        <div class="product-benchmark__row">
                                                            <span class="product-benchmark__bar" style="--benchmark-level: 88%;"></span>
                                                        </div>
                                                        <div class="product-benchmark__row">
                                                            <span class="product-benchmark__bar" style="--benchmark-level: 64%;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($slide['variant'] === 'inside')
                                                <div class="product-closeup product-closeup--inside" aria-hidden="true">
                                                    <span class="product-closeup__crop"></span>
                                                    <span class="product-closeup__gpu"></span>
                                                    <span class="product-closeup__cable"></span>
                                                    <span class="product-closeup__fan product-closeup__fan--a"></span>
                                                    <span class="product-closeup__fan product-closeup__fan--b"></span>
                                                </div>
                                            @elseif ($slide['variant'] === 'detail')
                                                <div class="product-closeup product-closeup--detail" aria-hidden="true">
                                                    <span class="product-closeup__crop"></span>
                                                    <span class="product-closeup__gpu"></span>
                                                    <span class="product-closeup__cable"></span>
                                                    <span class="product-closeup__fan product-closeup__fan--a"></span>
                                                    <span class="product-closeup__fan product-closeup__fan--b"></span>
                                                </div>
                                            @else
                                                <span class="product-gallery__glow" aria-hidden="true"></span>
                                                <div class="product-rig" aria-hidden="true">
                                                    <span class="product-rig__shadow"></span>
                                                    <span class="product-rig__case"></span>
                                                    <span class="product-rig__glass"></span>
                                                    <span class="product-rig__panel"></span>
                                                    <span class="product-rig__motherboard"></span>
                                                    <span class="product-rig__cooler"></span>
                                                    <span class="product-rig__tube product-rig__tube--a"></span>
                                                    <span class="product-rig__tube product-rig__tube--b"></span>
                                                    <span class="product-rig__gpu"></span>
                                                    <span class="product-rig__fan product-rig__fan--rear"></span>
                                                    <span class="product-rig__fan product-rig__fan--side-top"></span>
                                                    <span class="product-rig__fan product-rig__fan--side-bottom"></span>
                                                    <span class="product-rig__fan product-rig__fan--front"></span>
                                                    <span class="product-rig__foot product-rig__foot--left"></span>
                                                    <span class="product-rig__foot product-rig__foot--right"></span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <button class="product-gallery__nav product-gallery__nav--prev" type="button" data-product-prev aria-label="Попереднє фото">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>

                                <button class="product-gallery__nav product-gallery__nav--next" type="button" data-product-next aria-label="Наступне фото">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="product-gallery__thumbs">
                                @foreach ($productSlides as $slide)
                                    @php
                                        $slideImageUrl = trim((string) ($slide['image_url'] ?? ''));
                                        $slideImageKey = null;

                                        if ($slideImageUrl === '') {
                                            $slideImageKey = $slide['variant'] === 'hero'
                                                ? 'build.' . $build['slug'] . '.cover'
                                                : 'build.' . $build['slug'] . '.gallery.' . $slide['variant'];
                                            $slideImageUrl = (string) (\App\Support\SiteImages::url($slideImageKey) ?? '');
                                        }
                                    @endphp
                                    <button
                                        class="product-gallery__thumb{{ $loop->first ? ' is-active' : '' }}"
                                        type="button"
                                        data-product-thumb="{{ $loop->index }}"
                                        aria-label="Показати {{ $slide['thumb'] }}"
                                        style="--slide-from: {{ $palette['from'] }}; --slide-to: {{ $palette['to'] }};"
                                    >
                                        <span
                                            class="product-gallery__thumb-preview product-gallery__thumb-preview--{{ $slide['variant'] }} site-image-target{{ $slideImageUrl ? ' has-site-image' : '' }}"
                                            @if ($slideImageKey)
                                                data-site-image-key="{{ $slideImageKey }}"
                                            @endif
                                            data-site-image-passive="true"
                                            @if ($slideImageUrl)
                                                style="--site-image-url: url('{{ $slideImageUrl }}');"
                                            @endif
                                            aria-hidden="true"
                                        ></span>
                                    </button>
                                @endforeach
                            </div>

                            <section
                                class="product-fps"
                                data-product-fps
                                data-product-fps-map='@json($productFpsLookup)'
                                data-product-fps-fallback="0"
                                style="--product-fps-ratio: {{ number_format($resolveProductFpsRatio($initialProductFps), 4, '.', '') }};"
                            >
                                <p class="product-fps__note">*Показники FPS є усередненими і служать для демонстрації відносної продуктивності системи.</p>

                                <div class="product-fps__row">
                                    <label class="product-fps__field">
                                        <span>Гра</span>
                                        <select data-product-fps-game aria-label="Оберіть гру для FPS">
                                            @foreach ($productFpsGames as $game)
                                                <option value="{{ $game['id'] }}" @selected($game['id'] === $defaultProductFpsGame)>{{ $game['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="product-fps__field">
                                        <span>Монітор / Роздільна здатність</span>
                                        <select data-product-fps-display aria-label="Оберіть монітор для FPS">
                                            @foreach ($productFpsDisplays as $display)
                                                <option value="{{ $display['id'] }}" @selected($display['id'] === $defaultProductFpsDisplay)>{{ $display['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="product-fps__field">
                                        <span>Графіка</span>
                                        <select data-product-fps-preset aria-label="Оберіть налаштування графіки для FPS">
                                            @foreach ($productFpsPresets as $preset)
                                                <option value="{{ $preset['id'] }}" @selected($preset['id'] === $defaultProductFpsPreset)>{{ $preset['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <div class="product-fps__meter-wrap">
                                        <span class="product-fps__meter-kicker">Поточний FPS</span>
                                        <div class="product-fps__meter" aria-label="Поточний FPS">
                                            <span class="product-fps__meter-label">FPS</span>
                                            <span class="product-fps__meter-track" aria-hidden="true">
                                                <span class="product-fps__meter-fill" data-product-fps-fill></span>
                                            </span>
                                            <strong class="product-fps__value{{ $initialProductFps > 0 ? '' : ' is-empty' }}" data-product-fps-value>{{ $initialProductFps > 0 ? $initialProductFps : '—' }}</strong>
                                        </div>
                                        <span class="product-fps__status{{ $initialProductFps > 0 ? '' : ' is-visible' }}" data-product-fps-status>{{ $initialProductFps > 0 ? '' : 'FPS тест відсутній' }}</span>
                                    </div>
                                </div>
                            </section>

                            <section class="product-about product-about--desktop" aria-labelledby="product-about-title">
                                <h2 class="product-about__title" id="product-about-title">Про збірку</h2>

                                @foreach ($productAbout['intro'] as $paragraph)
                                    <p class="product-about__lead">{{ $paragraph }}</p>
                                @endforeach

                                @foreach ($productAbout['notes'] as $note)
                                    <p class="product-about__note">{{ $note }}</p>
                                @endforeach

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['setup_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--dash">
                                        @foreach ($productAbout['setup_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['delivery_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--dash">
                                        @foreach ($productAbout['delivery_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                    <ol class="product-about__steps">
                                        @foreach ($productAbout['delivery_steps'] as $step)
                                            <li>{{ $step }}</li>
                                        @endforeach
                                    </ol>
                                </div>

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['warranty_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--square">
                                        @foreach ($productAbout['warranty_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </section>

                            <section class="product-tradein" aria-labelledby="product-tradein-title">
                                <span class="product-tradein__eyebrow">Trade-in</span>
                                <h2 class="product-tradein__title" id="product-tradein-title">Бажаєте замінити свій старий ПК з доплатою на {{ $build['name'] }}?</h2>
                                <p class="product-tradein__text">Запропонуйте свій поточний комп'ютер, а ми попередньо оцінимо його та підготуємо пропозицію по трейд-іну саме під цю збірку.</p>
                                <a
                                    class="product-tradein__button"
                                    href="{{ route('trade-in', ['build' => $build['slug']]) }}"
                                    data-product-tradein
                                    data-base-href="{{ route('trade-in', ['build' => $build['slug']]) }}"
                                    data-share-endpoint="{{ route('product.share', ['slug' => $build['slug']]) }}"
                                >Запропонувати свій ПК</a>
                            </section>
                        </div>

                        <aside class="product-aside">
                            <h1 class="product-aside__title">{{ $build['name'] }}</h1>
                            <div class="product-aside__code">Код товару: {{ $build['product_code'] ?? '—' }}</div>

                            <h2 class="product-section-title">Характеристики</h2>

                            <div class="product-specs">
                                @foreach ($productSpecs as $spec)
                                    <div class="product-spec">
                                        <span class="product-spec__icon" aria-hidden="true">
                                            @switch($spec['icon'] ?? '')
                                                @case('gpu')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <path d="M12 3L20 8V16L12 21L4 16V8L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                        <path d="M8 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @case('cpu')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <rect x="7" y="7" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M9 3V6M15 3V6M9 18V21M15 18V21M3 9H6M18 9H21M3 15H6M18 15H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @case('ram')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <rect x="7" y="4" width="10" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M10 8H14M10 12H14M10 16H14M4 7V9M4 11V13M4 15V17M20 7V9M20 11V13M20 15V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @case('motherboard')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M8 8H16M8 12H16M8 16H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @case('storage')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 10L8 5H16L19 10V17H5V10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                        <path d="M5 10H19M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @case('case')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <path d="M12 3L19 6.5V17.5L12 21L5 17.5V6.5L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                        <path d="M12 3V21M5 6.5L12 10M19 6.5L12 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    @break
                                                @case('psu')
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <path d="M12 3V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                        <path d="M7.05 5.05A7 7 0 1 0 16.95 5.05" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    @break
                                                @default
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                            @endswitch
                                        </span>
                                        <span class="product-spec__body">
                                            <span class="product-spec__label">{{ $spec['label'] }}</span>
                                            <span class="product-spec__value">{{ $spec['value'] }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <section class="product-about product-about--mobile" aria-labelledby="product-about-title-mobile">
                                <h2 class="product-about__title" id="product-about-title-mobile">Про збірку</h2>

                                @foreach ($productAbout['intro'] as $paragraph)
                                    <p class="product-about__lead">{{ $paragraph }}</p>
                                @endforeach

                                @foreach ($productAbout['notes'] as $note)
                                    <p class="product-about__note">{{ $note }}</p>
                                @endforeach

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['setup_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--dash">
                                        @foreach ($productAbout['setup_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['delivery_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--dash">
                                        @foreach ($productAbout['delivery_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                    <ol class="product-about__steps">
                                        @foreach ($productAbout['delivery_steps'] as $step)
                                            <li>{{ $step }}</li>
                                        @endforeach
                                    </ol>
                                </div>

                                <div class="product-about__section">
                                    <h3 class="product-about__section-title">{{ $productAbout['warranty_title'] }}</h3>
                                    <ul class="product-about__list product-about__list--square">
                                        @foreach ($productAbout['warranty_items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </section>

                            @if (! empty($productOptions))
                                <p class="product-options__intro">Тут можна змінити комплектуючі:</p>

                                <div class="product-options" data-product-configurator="{{ $productConfiguratorEnabled ? 'true' : 'false' }}">
                                    @foreach ($productOptions as $optionGroup)
                                        <details class="product-option" data-product-option data-option-slot="{{ $optionGroup['slot'] ?? '' }}" data-option-group-id="{{ $optionGroup['id'] }}">
                                            <summary>{{ $optionGroup['title'] }}</summary>

                                            <div class="product-option__panel">
                                                @if (!empty($optionGroup['description']))
                                                    <p class="product-option__group-note">{{ $optionGroup['description'] }}</p>
                                                @endif

                                                <p class="product-option__error" data-option-error hidden></p>

                                                @foreach ($optionGroup['options'] as $option)
                                                    <label class="product-choice{{ !empty($option['selected']) ? ' is-selected' : '' }}">
                                                        <input
                                                            type="radio"
                                                            name="product_option_{{ $optionGroup['id'] }}"
                                                            value="{{ $loop->index }}"
                                                            data-option-price="{{ $option['price'] }}"
                                                            data-option-group="{{ $optionGroup['id'] }}"
                                                            data-option-key="{{ $option['key'] ?? $loop->index }}"
                                                            @checked(!empty($option['selected']))
                                                        >
                                                        <span class="product-choice__media">
                                                            <button
                                                                type="button"
                                                                class="product-choice__preview"
                                                                data-option-image-trigger
                                                                data-option-image-src="{{ $option['image_url'] ?? '' }}"
                                                                data-option-image-gallery='@json($option['image_urls'] ?? [])'
                                                                data-option-image-alt="{{ $option['label'] }}"
                                                                aria-label="Відкрити фото {{ $option['label'] }}"
                                                            >
                                                                <img src="{{ $option['image_url'] ?? '' }}" alt="{{ $option['label'] }}" loading="lazy">
                                                            </button>
                                                        </span>
                                                        <span class="product-choice__body">
                                                            <span class="product-choice__label">{{ $option['label'] }}</span>
                                                            <span class="product-choice__meta">{{ $option['description'] }}</span>
                                                        </span>
                                                        <span class="product-choice__price">{{ $option['price'] > 0 ? '+' . $priceFormatter($option['price']) : (!empty($option['is_default']) ? 'Входить' : 'Без доплати') }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            @endif

                            <div class="product-compatibility{{ empty($productCompatibility['is_valid']) ? ' is-visible' : '' }}" data-product-compatibility @if (!empty($productCompatibility['is_valid'])) hidden @endif>
                                <strong class="product-compatibility__title">Перевірка сумісності</strong>
                                <ul class="product-compatibility__list" data-product-compatibility-list>
                                    @foreach (($productCompatibility['messages'] ?? []) as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="product-pricing" data-product-pricing data-base-price="{{ $basePrice }}" data-total-price="{{ $initialTotalPrice }}" data-configurator-enabled="{{ $productConfiguratorEnabled ? 'true' : 'false' }}">
                                <div class="product-pricing__row product-pricing__row--addons">
                                    <span class="product-pricing__label">Вартість додаткових опцій</span>
                                    <strong class="product-pricing__value" data-product-addons-price>{{ $priceFormatter($defaultAdditionalPrice) }}</strong>
                                </div>

                                <div class="product-pricing__row product-pricing__row--total">
                                    <span class="product-pricing__label">Загальна вартість</span>
                                    <strong class="product-pricing__value" data-product-total-price>{{ $priceFormatter($initialTotalPrice) }}</strong>
                                </div>

                                <div class="product-pricing__note">Можемо фінально погодити комплектацію, протестувати збірку та підготувати її до відправки після підтвердження.</div>
                            </div>

                            <div class="product-actions">
                                <div class="product-actions__controls">
                                    <input class="product-actions__qty" type="number" value="1" min="1" max="9" inputmode="numeric" data-product-qty aria-label="Кількість">
                                    <button class="product-actions__button" type="button" data-product-add @disabled($isPreview)>{{ $isPreview ? 'Збережи збірку для замовлення' : 'Додати в кошик' }}</button>
                                    <a class="product-actions__button product-actions__button--secondary" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Розстрочка online</a>
                                </div>

                                <div class="product-actions__feedback" data-product-feedback>{{ $isPreview ? 'Це лише попередній перегляд. Щоб товар працював на сайті як повноцінна збірка, збережи зміни в адмінці.' : 'Можемо зібрати, протестувати і відправити після узгодження обраних опцій.' }}</div>

                                @unless ($isPreview)
                                    <div class="product-actions__share">
                                        <button
                                            class="product-actions__share-button"
                                            type="button"
                                            data-build-share
                                            data-share-endpoint="{{ route('product.share', ['slug' => $build['slug']]) }}"
                                            aria-label="Скопіювати посилання на збірку"
                                        >
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M10 14L14 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M8.5 17H7A4 4 0 1 1 7 9H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15 15H17A4 4 0 0 0 17 7H15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span>Скопіювати посилання</span>
                                        </button>
                                    </div>
                                @endunless
                            </div>

                            <section class="product-gear" aria-labelledby="product-gear-title">
                                <div class="product-gear__copy">
                                    <span class="product-gear__eyebrow">Kondor Device</span>
                                    <strong class="product-gear__title" id="product-gear-title">Доповніть сетап</strong>
                                    <p class="product-gear__text">До цієї збірки можна одразу підібрати наші девайси: клавіатури, миші та ігрові поверхні.</p>
                                </div>

                                <div class="product-gear__grid">
                                    @foreach ($accessoryGroups as $accessoryGroup)
                                        <button class="product-gear__item" type="button" data-accessory-category-open="{{ $accessoryGroup['type'] }}" aria-label="{{ $accessoryGroup['label'] }}">
                                            <span class="product-gear__icon" aria-hidden="true">
                                                @switch($accessoryGroup['icon'])
                                                    @case('keyboard')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                            <path d="M7 11H7.01M10 11H10.01M13 11H13.01M16 11H16.01M7 14H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('mouse')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="7" y="3" width="10" height="18" rx="5" stroke="currentColor" stroke-width="2"/>
                                                            <path d="M12 7V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                                            <path d="M8 10H16M8 14H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                @endswitch
                                            </span>
                                            <span class="product-gear__label">{{ $accessoryGroup['label'] }}</span>
                                            <span class="product-gear__meta">{{ $accessoryGroup['meta'] }}</span>
                                            <span class="product-gear__selection" data-accessory-category-summary>Ще не обрано</span>
                                            <span class="product-gear__count">{{ count($accessoryGroup['items']) }} моделей</span>
                                        </button>
                                    @endforeach
                                </div>

                            </section>
                        </aside>
                    </section>

                    <section class="product-custom-cta" aria-labelledby="product-custom-cta-title">
                        <h2 class="product-custom-cta__title" id="product-custom-cta-title">Бажаєте щось більш унікальне?</h2>
                        <p class="product-custom-cta__text">Зберемо найкраще кастомізоване рішення саме під ваші потреби</p>
                        <a class="product-custom-cta__button" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">Замовити індивідуальну збірку</a>
                    </section>

                    <section class="product-related" aria-labelledby="product-related-title">
                        <h2 class="product-related__title" id="product-related-title">Дивіться також</h2>

                        <div class="product-related__grid">
                            @foreach ($relatedBuilds as $relatedBuild)
                                @php
                                    $relatedImageUrl = \App\Support\SiteImages::url('build.' . $relatedBuild['slug'] . '.cover');
                                @endphp
                                <a
                                    class="product-related-card"
                                    href="{{ route('product.show', ['slug' => $relatedBuild['slug']]) }}"
                                    style="--related-from: {{ $relatedBuild['palette']['from'] }}; --related-to: {{ $relatedBuild['palette']['to'] }}; --related-accent: {{ $relatedBuild['palette']['accent'] }};"
                                >
                                    <div
                                        class="product-related-card__media site-image-target{{ $relatedImageUrl ? ' has-site-image' : '' }}"
                                        data-site-image-key="build.{{ $relatedBuild['slug'] }}.cover"
                                        @if ($relatedImageUrl)
                                            style="--site-image-url: url('{{ $relatedImageUrl }}');"
                                        @endif
                                        aria-hidden="true"
                                    >
                                        <span class="product-related-card__glow"></span>
                                        <span class="product-related-card__tower"></span>
                                        <span class="product-related-card__fan-back"></span>
                                        <span class="product-related-card__gpu"></span>
                                        <span class="product-related-card__fans"></span>
                                    </div>

                                    <div class="product-related-card__body">
                                        <h3 class="product-related-card__name">{{ $relatedBuild['name'] }}</h3>

                                        <ul class="product-related-card__specs">
                                            <li>
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <rect x="7" y="7" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M9 3V6M15 3V6M9 18V21M15 18V21M3 9H6M18 9H21M3 15H6M18 15H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                <span>{{ $relatedBuild['gpu'] }}</span>
                                            </li>
                                            <li>
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M12 3L19 8V16L12 21L5 16V8L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <path d="M12 9V15M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                <span>{{ $relatedBuild['cpu'] }}</span>
                                            </li>
                                            <li>
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M8 10H10M14 10H16M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                <span>{{ $relatedBuild['ram'] }}</span>
                                            </li>
                                            <li>
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M7 12H17M7 15H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                <span>{{ $relatedBuild['storage'] }}</span>
                                            </li>
                                        </ul>

                                        <div class="product-related-card__price">{{ $priceFormatter($relatedBuild['price_value']) }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="product-related__footer">
                            <a class="product-related__button" href="{{ route('catalog') }}">Всі збірки</a>
                        </div>
                    </section>
                </div>
            </main>

            @if ($isPreview && $previewPersistUrl !== '')
                <div class="preview-dock" role="complementary" aria-label="Дії з чернеткою">
                    <div class="preview-dock__meta">
                        <span class="preview-dock__eyebrow">Режим превʼю</span>
                        <div class="preview-dock__title">Чернетка збірки готова до перевірки</div>
                        <div class="preview-dock__text">Статус зараз: <strong>{{ $previewStatusLabel }}</strong>. Після збереження можна одразу повернутися в адмінку й продовжити редагування.</div>
                        @if (($previewPersistState['message'] ?? '') !== '')
                            <div class="preview-dock__feedback">{{ $previewPersistState['message'] }}</div>
                        @elseif ($previewPersistError !== '')
                            <div class="preview-dock__feedback preview-dock__feedback--error">{{ $previewPersistError }}</div>
                        @endif
                    </div>
                    <div class="preview-dock__actions">
                        @if ($previewBackUrl !== '')
                            <a class="preview-dock__button" href="{{ $previewBackUrl }}">Повернутися до редагування</a>
                        @endif
                        <form class="preview-dock__form" method="POST" action="{{ $previewPersistUrl }}">
                            @csrf
                            <input type="hidden" name="mode" value="draft">
                            <button class="preview-dock__button" type="submit">Зберегти в чернетку</button>
                        </form>
                        <form class="preview-dock__form" method="POST" action="{{ $previewPersistUrl }}">
                            @csrf
                            <input type="hidden" name="mode" value="publish">
                            <button class="preview-dock__button preview-dock__button--primary" type="submit">Опублікувати</button>
                        </form>
                    </div>
                </div>
            @endif

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
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/></svg></a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="Подзвонити"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                            </div>
                        </div>
                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">Про нас</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}#about">Що таке KondorPC</a>
                                <a href="#contacts">Контакти</a>
                                <a href="#contacts">Доставка</a>
                                <a href="#contacts">Оплата</a>
                                <a href="#contacts">Повернення та обмін</a>
                            </nav>
                        </div>
                        <div class="footer__column">
                            <h2 class="footer__title">Основне</h2>
                            <nav class="footer__nav">
                                <a href="{{ url('/') }}">Головна</a>
                                <a href="{{ route('catalog') }}">Каталог</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner">{{ date('Y') }} KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        <div class="product-option-lightbox" data-option-image-modal hidden>
            <div class="product-option-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Фото комплектуючої">
                <button type="button" class="product-option-lightbox__close" data-option-image-close aria-label="Закрити">&times;</button>
                <button type="button" class="product-option-lightbox__nav product-option-lightbox__nav--prev" data-option-image-prev aria-label="Попереднє фото">
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="product-option-lightbox__nav product-option-lightbox__nav--next" data-option-image-next aria-label="Наступне фото">
                    <span aria-hidden="true">›</span>
                </button>
                <img src="" alt="" class="product-option-lightbox__image" data-option-image-modal-image>
                <div class="product-option-lightbox__thumbs" data-option-image-thumbs></div>
                <div class="product-option-lightbox__meta">
                    <p class="product-option-lightbox__caption" data-option-image-modal-caption></p>
                    <p class="product-option-lightbox__counter" data-option-image-modal-counter></p>
                </div>
            </div>
        </div>

        <div class="product-share-modal" data-build-share-modal hidden>
            <div class="product-share-modal__backdrop" data-build-share-close></div>
            <div class="product-share-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="build-share-title">
                <button type="button" class="product-share-modal__close" data-build-share-close aria-label="Закрити">&times;</button>
                <h2 class="product-share-modal__title" id="build-share-title">Поділитися збіркою</h2>
                <p class="product-share-modal__text">Скопіюй це посилання і надішли його клієнту або собі на інший пристрій. За лінком відкриється саме ця конфігурація збірки.</p>
                <label class="product-share-modal__field">
                    <span>Посилання на збірку</span>
                    <input type="text" value="" readonly data-build-share-link>
                </label>
                <p class="product-share-modal__meta" data-build-share-meta></p>
                <button type="button" class="product-share-modal__copy" data-build-share-copy>Копіювати посилання</button>
            </div>
        </div>

        @foreach ($accessoryGroups as $accessoryGroup)
            <div class="accessory-modal" data-accessory-category-modal="{{ $accessoryGroup['type'] }}" hidden>
                <div class="accessory-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="accessory-category-title-{{ $accessoryGroup['type'] }}">
                    <button type="button" class="accessory-modal__close" data-accessory-modal-close aria-label="Закрити">&times;</button>
                    <div class="accessory-modal__header">
                        <span class="accessory-modal__eyebrow">Kondor Device</span>
                        <h2 class="accessory-modal__title" id="accessory-category-title-{{ $accessoryGroup['type'] }}">{{ $accessoryGroup['label'] }}</h2>
                        <p class="accessory-modal__text">{{ $accessoryGroup['meta'] }}. Обирай модель прямо на сайті, переглядай фото та відкривай попапи з деталями.</p>
                    </div>

                    @if ($accessoryGroup['items'] !== [])
                        <div class="accessory-modal__grid">
                            @foreach ($accessoryGroup['items'] as $accessory)
                                <article class="accessory-card">
                                    <button
                                        type="button"
                                        class="accessory-card__media"
                                        data-option-image-trigger
                                        data-option-image-src="{{ $accessory['image_url'] }}"
                                        data-option-image-alt="{{ $accessory['name'] }}"
                                        data-option-image-gallery='@json($accessory['image_urls'])'
                                    >
                                        <img src="{{ $accessory['image_url'] }}" alt="{{ $accessory['name'] }}">
                                    </button>

                                    <div class="accessory-card__body">
                                        @if ($accessory['vendor'] !== '')
                                            <span class="accessory-card__vendor">{{ $accessory['vendor'] }}</span>
                                        @endif

                                        <h3 class="accessory-card__name">{{ $accessory['name'] }}</h3>

                                        @if ($accessory['summary'] !== '')
                                            <p class="accessory-card__summary">{{ $accessory['summary'] }}</p>
                                        @endif

                                        <p class="accessory-card__price">{{ $priceFormatter((int) ($accessory['price_raw'] ?? 0)) }}</p>

                                        <div class="accessory-card__actions">
                                            <button
                                                type="button"
                                                class="accessory-card__button accessory-card__button--select"
                                                data-accessory-select="{{ $accessory['slug'] }}"
                                                data-accessory-type="{{ $accessoryGroup['type'] }}"
                                                data-accessory-name="{{ $accessory['name'] }}"
                                                data-accessory-price="{{ (int) ($accessory['price_raw'] ?? 0) }}"
                                                aria-pressed="false"
                                            >
                                                Обрати
                                            </button>

                                            @if ($accessory['specs'] !== [])
                                                <button type="button" class="accessory-card__button accessory-card__button--primary" data-accessory-sheet-open="{{ $accessory['slug'] }}:specs">
                                                    Інформація
                                                </button>
                                            @endif

                                            @if ($accessory['package_items'] !== [])
                                                <button type="button" class="accessory-card__button" data-accessory-sheet-open="{{ $accessory['slug'] }}:package">
                                                    Комплектація
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="accessory-modal__empty">
                            <strong>Поки що порожньо</strong>
                            <span>У цій категорії ще немає активних девайсів. Додай їх в адмінці в розділі “Девайси”, і вони одразу зʼявляться тут.</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @foreach ($accessoryGroups as $accessoryGroup)
            @foreach ($accessoryGroup['items'] as $accessory)
                @if ($accessory['specs'] !== [])
                    <div class="accessory-sheet" data-accessory-sheet="{{ $accessory['slug'] }}:specs" hidden>
                        <div class="accessory-sheet__dialog" role="dialog" aria-modal="true" aria-labelledby="accessory-specs-title-{{ $accessory['slug'] }}">
                            <button type="button" class="accessory-sheet__close" data-accessory-sheet-close aria-label="Закрити">&times;</button>
                            <div class="accessory-sheet__header">
                                <h3 class="accessory-sheet__title" id="accessory-specs-title-{{ $accessory['slug'] }}">Загальні характеристики</h3>
                            </div>
                            <div class="accessory-sheet__rows">
                                @foreach ($accessory['specs'] as $spec)
                                    <div class="accessory-sheet__spec{{ $spec['is_highlighted'] ? ' is-highlighted' : '' }}">
                                        <span class="accessory-sheet__spec-label">{{ $spec['label'] }}</span>
                                        <strong class="accessory-sheet__spec-value">{{ $spec['value'] }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($accessory['package_items'] !== [])
                    <div class="accessory-sheet" data-accessory-sheet="{{ $accessory['slug'] }}:package" hidden>
                        <div class="accessory-sheet__dialog" role="dialog" aria-modal="true" aria-labelledby="accessory-package-title-{{ $accessory['slug'] }}">
                            <button type="button" class="accessory-sheet__close" data-accessory-sheet-close aria-label="Закрити">&times;</button>
                            <div class="accessory-sheet__header">
                                <h3 class="accessory-sheet__title" id="accessory-package-title-{{ $accessory['slug'] }}">Комплектація</h3>
                            </div>
                            <div class="accessory-sheet__rows">
                                @foreach ($accessory['package_items'] as $packageItem)
                                    <div class="accessory-sheet__package-item{{ $packageItem['is_highlighted'] ? ' is-highlighted' : '' }}">
                                        <div class="accessory-sheet__package-badge">
                                            <span class="accessory-sheet__package-icon" aria-hidden="true">
                                                @switch($packageItem['icon'])
                                                    @case('cable')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <path d="M8 7V6A4 4 0 0 1 12 2A4 4 0 0 1 16 6V10A4 4 0 0 1 12 14A4 4 0 0 1 8 10V9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                            <path d="M8 14V18A4 4 0 0 0 12 22A4 4 0 0 0 16 18V17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('switch')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="6" y="4" width="12" height="12" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                                            <path d="M9 20H15M12 16V20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('tool')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <path d="M14 5L19 10L10 19L5 14L14 5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                            <path d="M13 6L18 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('manual')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <path d="M6 4.5H16A2.5 2.5 0 0 1 18.5 7V19.5H8A2 2 0 0 0 6 21.5V4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                            <path d="M8.5 8H15M8.5 11.5H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('sticker')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <path d="M12 3L19 7V17L12 21L5 17V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                            <path d="M9 12H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('keycap')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="7" y="3" width="10" height="18" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                                            <path d="M10 8H14M10 12H14M10 16H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @case('dongle')
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <rect x="7" y="6" width="10" height="9" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                                            <path d="M10 15V18M14 15V18M9 4V6M15 4V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/>
                                                        </svg>
                                                @endswitch
                                            </span>
                                            <span>{{ $packageItem['label'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach

        <script src="{{ asset('js/storefront-cart.js') }}"></script>
        <script>
            (() => {
                const header = document.querySelector('.header');
                const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
                const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
                const mobileToggle = document.querySelector('[data-mobile-toggle]');
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                const productSlides = Array.from(document.querySelectorAll('[data-product-slide]'));
                const productThumbs = Array.from(document.querySelectorAll('[data-product-thumb]'));
                const productPrevButton = document.querySelector('[data-product-prev]');
                const productNextButton = document.querySelector('[data-product-next]');
                const galleryInfo = document.querySelector('[data-gallery-info]');
                const galleryInfoButton = document.querySelector('[data-gallery-info-toggle]');
                const productFpsRoot = document.querySelector('[data-product-fps]');
                const productFpsGameSelect = document.querySelector('[data-product-fps-game]');
                const productFpsDisplaySelect = document.querySelector('[data-product-fps-display]');
                const productFpsPresetSelect = document.querySelector('[data-product-fps-preset]');
                const productFpsValue = document.querySelector('[data-product-fps-value]');
                const productFpsFill = document.querySelector('[data-product-fps-fill]');
                const productFpsStatus = document.querySelector('[data-product-fps-status]');
                const productOptions = Array.from(document.querySelectorAll('[data-product-option]'));
                const optionInputs = Array.from(document.querySelectorAll('[data-option-price]'));
                const optionChoices = Array.from(document.querySelectorAll('.product-choice'));
                const optionImageTriggers = Array.from(document.querySelectorAll('[data-option-image-trigger]'));
                const optionImageModal = document.querySelector('[data-option-image-modal]');
                const optionImageModalImage = document.querySelector('[data-option-image-modal-image]');
                const optionImageModalCaption = document.querySelector('[data-option-image-modal-caption]');
                const optionImageModalCounter = document.querySelector('[data-option-image-modal-counter]');
                const optionImageModalClose = document.querySelector('[data-option-image-close]');
                const optionImageModalPrev = document.querySelector('[data-option-image-prev]');
                const optionImageModalNext = document.querySelector('[data-option-image-next]');
                const optionImageModalThumbs = document.querySelector('[data-option-image-thumbs]');
                const buildShareButton = document.querySelector('[data-build-share]');
                const buildShareButtonLabel = buildShareButton?.querySelector('span');
                const productTradeInButton = document.querySelector('[data-product-tradein]');
                const buildShareModal = document.querySelector('[data-build-share-modal]');
                const buildShareLinkInput = document.querySelector('[data-build-share-link]');
                const buildShareMeta = document.querySelector('[data-build-share-meta]');
                const buildShareCopyButton = document.querySelector('[data-build-share-copy]');
                const buildShareCloseButtons = Array.from(document.querySelectorAll('[data-build-share-close]'));
                const accessoryCategoryButtons = Array.from(document.querySelectorAll('[data-accessory-category-open]'));
                const accessoryCategoryModals = Array.from(document.querySelectorAll('[data-accessory-category-modal]'));
                const accessorySheetButtons = Array.from(document.querySelectorAll('[data-accessory-sheet-open]'));
                const accessorySelectButtons = Array.from(document.querySelectorAll('[data-accessory-select]'));
                const accessorySheets = Array.from(document.querySelectorAll('[data-accessory-sheet]'));
                const accessoryModalCloseButtons = Array.from(document.querySelectorAll('[data-accessory-modal-close]'));
                const accessorySheetCloseButtons = Array.from(document.querySelectorAll('[data-accessory-sheet-close]'));
                const pricingRoot = document.querySelector('[data-product-pricing]');
                const addonsPriceElement = document.querySelector('[data-product-addons-price]');
                const totalPriceElement = document.querySelector('[data-product-total-price]');
                const quantityInput = document.querySelector('[data-product-qty]');
                const addToCartButton = document.querySelector('[data-product-add]');
                const productFeedback = document.querySelector('[data-product-feedback]');
                const compatibilityRoot = document.querySelector('[data-product-compatibility]');
                const compatibilityList = document.querySelector('[data-product-compatibility-list]');
                const headerCartValue = document.querySelector('.header-cart span');
                const productCartItem = {
                    slug: @json($build['slug']),
                    name: @json($build['name']),
                    url: @json(route('product.show', ['slug' => $build['slug']])),
                    tone: @json($build['tone'] ?? 'violet'),
                };
                const accessorySelectionStorageKey = `kondor-accessory-selection:${productCartItem.slug}`;
                const sharedAccessorySelections = @json($sharedAccessorySelections);
                const productFpsConfig = @json($productFpsClientConfig);
                const productConfigurator = @json($productConfiguratorClient);
                const productFpsGames = Object.fromEntries((productFpsConfig.games ?? []).map((game) => [game.id, game]));
                const productFpsDisplays = Object.fromEntries((productFpsConfig.displays ?? []).map((display) => [display.id, display]));
                const productFpsPresets = Object.fromEntries((productFpsConfig.presets ?? []).map((preset) => [preset.id, preset]));
                const productFpsLookup = productFpsConfig.lookup ?? {};
                const csrfToken = @json(csrf_token());
                let closeTimer;
                let cartTotal = 0;
                let activeSlideIndex = 0;
                let optionImageGallery = [];
                let optionImageGalleryIndex = 0;
                let activeAccessoryCategory = '';
                let accessorySelections = {};

                const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
                const formatPrice = (value) => `${new Intl.NumberFormat('uk-UA').format(Math.round(value)).replace(/\u00a0/g, ' ')} ₴`;
                const resolveProductFpsRatio = (fps) => fps > 0 ? clamp(fps / 220, 0.18, 1) : 0;
                const productStateKey = (state) => `${state.game ?? ''}|${state.display ?? ''}|${state.preset ?? ''}`;
                const makeCartKey = (slug, selection = {}) => {
                    const normalizedSelection = Object.keys(selection ?? {})
                        .sort()
                        .reduce((carry, key) => {
                            if (selection[key]) {
                                carry[key] = selection[key];
                            }

                            return carry;
                        }, {});

                    try {
                        return `${slug}:${window.btoa(unescape(encodeURIComponent(JSON.stringify(normalizedSelection))))}`;
                    } catch (error) {
                        return `${slug}:default`;
                    }
                };

                const loadAccessorySelections = () => {
                    const normalizeSelections = (source) => {
                        if (!source || typeof source !== 'object' || Array.isArray(source)) {
                            return {};
                        }

                        return Object.entries(source).reduce((carry, [type, entry]) => {
                            if (!entry || typeof entry !== 'object' || Array.isArray(entry)) {
                                return carry;
                            }

                            const slug = `${entry.slug ?? ''}`.trim();
                            const name = `${entry.name ?? ''}`.trim();

                            if (!type || !slug || !name) {
                                return carry;
                            }

                            carry[type] = {
                                slug,
                                name,
                                price: Math.max(0, Math.round(Number(entry.price) || 0)),
                            };

                            return carry;
                        }, {});
                    };

                    try {
                        const raw = window.localStorage.getItem(accessorySelectionStorageKey);
                        const parsed = raw ? JSON.parse(raw) : {};

                        return {
                            ...normalizeSelections(parsed),
                            ...normalizeSelections(sharedAccessorySelections),
                        };
                    } catch (error) {
                        return normalizeSelections(sharedAccessorySelections);
                    }
                };

                const saveAccessorySelections = () => {
                    try {
                        window.localStorage.setItem(accessorySelectionStorageKey, JSON.stringify(accessorySelections));
                    } catch (error) {
                        // Ignore storage failures and keep the page usable.
                    }
                };

                const renderAccessorySelections = () => {
                    accessoryCategoryButtons.forEach((button) => {
                        const type = button.dataset.accessoryCategoryOpen ?? '';
                        const summary = button.querySelector('[data-accessory-category-summary]');
                        const selection = type ? accessorySelections[type] ?? null : null;

                        button.classList.toggle('is-selected', Boolean(selection));

                        if (summary) {
                            summary.textContent = selection?.name
                                ? `Обрано: ${selection.name}`
                                : 'Ще не обрано';
                        }
                    });

                    accessorySelectButtons.forEach((button) => {
                        const type = button.dataset.accessoryType ?? '';
                        const slug = button.dataset.accessorySelect ?? '';
                        const selection = type ? accessorySelections[type] ?? null : null;
                        const isSelected = Boolean(selection && selection.slug === slug);

                        button.classList.toggle('is-selected', isSelected);
                        button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
                        button.textContent = isSelected ? 'Обрано' : 'Обрати';
                    });
                };

                const syncAccessorySelectionLabels = () => {
                    accessoryCategoryButtons.forEach((button) => {
                        const type = button.dataset.accessoryCategoryOpen ?? '';
                        const summary = button.querySelector('[data-accessory-category-summary]');
                        const selection = type ? accessorySelections[type] ?? null : null;

                        if (!summary) {
                            return;
                        }

                        summary.textContent = selection?.name
                            ? `Обрано: ${selection.name}${Number(selection.price ?? 0) > 0 ? ` + ${formatPrice(selection.price)}` : ''}`
                            : 'Ще не обрано';
                    });

                    accessorySelectButtons.forEach((button) => {
                        const type = button.dataset.accessoryType ?? '';
                        const slug = button.dataset.accessorySelect ?? '';
                        const selection = type ? accessorySelections[type] ?? null : null;
                        const isSelected = Boolean(selection && selection.slug === slug);

                        button.textContent = isSelected ? 'Обрано' : 'Обрати';
                    });
                };

                const getAccessorySelectionState = () => {
                    const selection = {};
                    const summary = [];
                    const addons = Object.entries(accessorySelections).reduce((sum, [type, entry]) => {
                        if (!entry || typeof entry !== 'object') {
                            return sum;
                        }

                        const slug = `${entry.slug ?? ''}`.trim();
                        const name = `${entry.name ?? ''}`.trim();
                        const price = Math.max(0, Math.round(Number(entry.price) || 0));

                        if (!slug || !name) {
                            return sum;
                        }

                        selection[`accessory_${type}`] = slug;
                        summary.push(`Девайс (${type}): ${name}${price > 0 ? ` + ${formatPrice(price)}` : ''}`);

                        return sum + price;
                    }, 0);

                    return { selection, summary, addons };
                };

                const toggleAccessorySelection = (payload) => {
                    if (!payload.type || !payload.slug || !payload.name) {
                        return;
                    }

                    const currentSelection = accessorySelections[payload.type] ?? null;

                    if (currentSelection && currentSelection.slug === payload.slug) {
                        delete accessorySelections[payload.type];
                    } else {
                        accessorySelections[payload.type] = {
                            slug: payload.slug,
                            name: payload.name,
                            price: Math.max(0, Math.round(Number(payload.price) || 0)),
                        };
                    }

                    saveAccessorySelections();
                    renderAccessorySelections();
                    syncAccessorySelectionLabels();
                    syncProductPricing();
                    closeAccessoryCategoryModals();
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

                const setActiveSlide = (nextIndex) => {
                    if (!productSlides.length) {
                        return;
                    }

                    const safeIndex = clamp(nextIndex, 0, productSlides.length - 1);
                    activeSlideIndex = safeIndex;

                    productSlides.forEach((slide, index) => {
                        slide.classList.toggle('is-active', index === safeIndex);
                    });

                    productThumbs.forEach((thumb, index) => {
                        thumb.classList.toggle('is-active', index === safeIndex);
                        thumb.setAttribute('aria-pressed', index === safeIndex ? 'true' : 'false');
                    });
                };

                const closeGalleryInfo = () => {
                    if (!galleryInfo || !galleryInfoButton) {
                        return;
                    }

                    galleryInfo.classList.remove('is-open');
                    galleryInfoButton.setAttribute('aria-expanded', 'false');
                };

                const syncBodyLock = () => {
                    const optionImageOpen = optionImageModal && !optionImageModal.hidden && optionImageModal.classList.contains('is-open');
                    const buildShareOpen = buildShareModal && !buildShareModal.hidden;
                    const accessoryCategoryOpen = accessoryCategoryModals.some((modal) => !modal.hidden);
                    const accessorySheetOpen = accessorySheets.some((sheet) => !sheet.hidden);

                    document.body.style.overflow = optionImageOpen || buildShareOpen || accessoryCategoryOpen || accessorySheetOpen ? 'hidden' : '';
                };

                const normalizeOptionImageGallery = (rawGallery, fallbackSrc = '') => {
                    const gallery = Array.isArray(rawGallery) ? rawGallery : [];
                    const normalized = gallery
                        .map((value) => `${value ?? ''}`.trim())
                        .filter(Boolean);

                    if (normalized.length) {
                        return normalized;
                    }

                    return fallbackSrc ? [fallbackSrc] : [];
                };

                const renderOptionImageModal = (alt = '') => {
                    if (!optionImageModalImage || !optionImageGallery.length) {
                        return;
                    }

                    const safeIndex = clamp(optionImageGalleryIndex, 0, optionImageGallery.length - 1);
                    const currentImage = optionImageGallery[safeIndex] ?? '';

                    optionImageGalleryIndex = safeIndex;
                    optionImageModalImage.setAttribute('src', currentImage);
                    optionImageModalImage.setAttribute('alt', alt ?? '');

                    if (optionImageModalCaption) {
                        optionImageModalCaption.textContent = alt ?? '';
                    }

                    if (optionImageModalCounter) {
                        optionImageModalCounter.textContent = `${safeIndex + 1} / ${optionImageGallery.length}`;
                    }

                    if (optionImageModalPrev) {
                        optionImageModalPrev.hidden = optionImageGallery.length < 2;
                    }

                    if (optionImageModalNext) {
                        optionImageModalNext.hidden = optionImageGallery.length < 2;
                    }

                    if (optionImageModalThumbs) {
                        optionImageModalThumbs.innerHTML = '';

                        optionImageGallery.forEach((image, index) => {
                            const thumbButton = document.createElement('button');
                            const thumbImage = document.createElement('img');

                            thumbButton.type = 'button';
                            thumbButton.className = `product-option-lightbox__thumb${index === safeIndex ? ' is-active' : ''}`;
                            thumbButton.dataset.optionImageThumb = `${index}`;
                            thumbButton.setAttribute('aria-label', `Відкрити фото ${index + 1}`);

                            thumbImage.src = image;
                            thumbImage.alt = alt ?? '';

                            thumbButton.appendChild(thumbImage);
                            optionImageModalThumbs.appendChild(thumbButton);
                        });
                    }
                };

                const closeOptionImageModal = () => {
                    if (!optionImageModal || !optionImageModalImage) {
                        return;
                    }

                    optionImageModal.hidden = true;
                    optionImageModal.classList.remove('is-open');
                    optionImageModalImage.setAttribute('src', '');
                    optionImageModalImage.setAttribute('alt', '');

                    if (optionImageModalCaption) {
                        optionImageModalCaption.textContent = '';
                    }

                    if (optionImageModalCounter) {
                        optionImageModalCounter.textContent = '';
                    }

                    if (optionImageModalThumbs) {
                        optionImageModalThumbs.innerHTML = '';
                    }

                    optionImageGallery = [];
                    optionImageGalleryIndex = 0;

                    syncBodyLock();
                };

                const openOptionImageModal = (gallery, alt, startIndex = 0) => {
                    const normalizedGallery = normalizeOptionImageGallery(gallery);

                    if (!optionImageModal || !optionImageModalImage || !normalizedGallery.length) {
                        return;
                    }

                    optionImageGallery = normalizedGallery;
                    optionImageGalleryIndex = clamp(startIndex, 0, normalizedGallery.length - 1);
                    optionImageModal.hidden = false;
                    optionImageModal.classList.add('is-open');
                    renderOptionImageModal(alt ?? '');
                    syncBodyLock();
                };

                const openBuildShareModal = (url, expiresAt = '') => {
                    if (!buildShareModal || !buildShareLinkInput || !buildShareMeta) {
                        return;
                    }

                    buildShareLinkInput.value = url;
                    buildShareMeta.textContent = expiresAt
                        ? `Посилання активне до ${new Date(expiresAt).toLocaleDateString('uk-UA')}.`
                        : 'Посилання активне 30 днів.';
                    buildShareModal.hidden = false;
                    syncBodyLock();
                    buildShareCopyButton?.focus();
                };

                const closeBuildShareModal = () => {
                    if (!buildShareModal) {
                        return;
                    }

                    buildShareModal.hidden = true;
                    syncBodyLock();
                };

                const openAccessoryCategoryModal = (type) => {
                    const modal = document.querySelector(`[data-accessory-category-modal="${type}"]`);

                    if (!modal) {
                        return;
                    }

                    activeAccessoryCategory = type;
                    accessoryCategoryModals.forEach((item) => {
                        item.hidden = item !== modal;
                    });
                    syncBodyLock();
                };

                const closeAccessoryCategoryModals = () => {
                    accessoryCategoryModals.forEach((modal) => {
                        modal.hidden = true;
                    });
                    activeAccessoryCategory = '';
                    syncBodyLock();
                };

                const openAccessorySheet = (key) => {
                    const sheet = document.querySelector(`[data-accessory-sheet="${key}"]`);

                    if (!sheet) {
                        return;
                    }

                    accessorySheets.forEach((item) => {
                        item.hidden = item !== sheet;
                    });
                    syncBodyLock();
                };

                const closeAccessorySheets = () => {
                    accessorySheets.forEach((sheet) => {
                        sheet.hidden = true;
                    });
                    syncBodyLock();
                };

                const syncOptionSelections = () => {
                    optionChoices.forEach((choice) => {
                        const input = choice.querySelector('input');
                        choice.classList.toggle('is-selected', !!input?.checked);
                    });
                };

                const renderOptionConflicts = (invalidSlots = [], slotMessages = {}) => {
                    const activeSlots = new Set((Array.isArray(invalidSlots) ? invalidSlots : [])
                        .map((slot) => `${slot ?? ''}`.trim())
                        .filter(Boolean));

                    productOptions.forEach((optionRoot) => {
                        const slot = `${optionRoot.dataset.optionSlot ?? ''}`.trim();
                        const messages = slot ? (Array.isArray(slotMessages?.[slot]) ? slotMessages[slot] : []) : [];
                        const isInvalid = slot !== '' && activeSlots.has(slot);
                        const errorNode = optionRoot.querySelector('[data-option-error]');
                        const selectedChoice = optionRoot.querySelector('.product-choice input:checked')?.closest('.product-choice');

                        optionRoot.classList.toggle('is-invalid', isInvalid);

                        if (errorNode) {
                            errorNode.hidden = !isInvalid || messages.length === 0;
                            errorNode.textContent = isInvalid ? (messages[0] ?? '') : '';
                        }

                        optionRoot.querySelectorAll('.product-choice').forEach((choice) => {
                            choice.classList.remove('is-conflict');
                        });

                        if (isInvalid && selectedChoice) {
                            selectedChoice.classList.add('is-conflict');
                        }
                    });
                };

                const renderCompatibility = (messages = []) => {
                    if (!compatibilityRoot || !compatibilityList) {
                        return;
                    }

                    compatibilityList.innerHTML = '';

                    messages.forEach((message) => {
                        const item = document.createElement('li');
                        item.textContent = message;
                        compatibilityList.appendChild(item);
                    });

                    const visible = messages.length > 0;
                    compatibilityRoot.hidden = !visible;
                    compatibilityRoot.classList.toggle('is-visible', visible);
                };

                const getSelectedConfiguratorState = () => {
                    const selected = {};

                    optionInputs.forEach((input) => {
                        if (input.checked) {
                            selected[input.dataset.optionGroup ?? ''] = input.dataset.optionKey ?? input.value;
                        }
                    });

                    return selected;
                };

                const getConfiguratorEvaluation = () => {
                    if (!productConfigurator?.enabled) {
                        const selection = getSelectedConfiguratorState();
                        const summary = [];

                        const addons = optionInputs.reduce((sum, input) => {
                            if (!input.checked) {
                                return sum;
                            }

                            const price = Number(input.dataset.optionPrice ?? 0);
                            const groupTitle = input.closest('[data-product-option]')?.querySelector('summary')?.textContent?.trim() ?? 'Опція';
                            const optionLabel = input.closest('.product-choice')?.querySelector('.product-choice__label')?.textContent?.trim() ?? 'Вибрано';

                            if (price > 0) {
                                summary.push(`${groupTitle}: ${optionLabel}`);
                            }

                            return sum + price;
                        }, 0);

                        return {
                            isValid: true,
                            messages: [],
                            invalidSlots: [],
                            slotMessages: {},
                            addons,
                            selection,
                            summary,
                        };
                    }

                    const selection = getSelectedConfiguratorState();
                    const slotLabels = productConfigurator.slotLabels ?? {};
                    const baseComponents = { ...(productConfigurator.baseComponents ?? {}) };
                    const groups = Array.isArray(productConfigurator.groups) ? productConfigurator.groups : [];
                    const components = productConfigurator.components ?? {};
                    const componentIds = { ...baseComponents };
                    const summary = [];
                    let addons = 0;

                    groups.forEach((group) => {
                        const options = Array.isArray(group.options) ? group.options : [];
                        const selectedKey = selection[group.key] ?? productConfigurator.defaults?.[group.key];
                        const option = options.find((entry) => entry.key === selectedKey)
                            ?? options.find((entry) => entry.is_default)
                            ?? options[0];

                        if (!option) {
                            return;
                        }

                        const price = Number(option.price ?? option.price_delta ?? 0);
                        const slot = `${group.slot ?? ''}`;
                        const componentId = Number(option.component_id ?? 0);

                        addons += price;

                        if (slot && componentId > 0 && ['cpu', 'gpu', 'motherboard', 'ram', 'storage', 'psu', 'case', 'cooler'].includes(slot)) {
                            componentIds[slot] = componentId;
                        }

                        if (price > 0 || !['modding', 'adapters', 'other'].includes(slot)) {
                            summary.push(`${slotLabels[slot] ?? 'Опція'}: ${option.label ?? 'Вибрано'}`);
                        }
                    });

                    const componentFromSlot = (slot) => {
                        const componentId = Number(componentIds?.[slot] ?? 0);
                        return componentId > 0 ? (components[componentId] ?? null) : null;
                    };

                    const cpu = componentFromSlot('cpu');
                    const gpu = componentFromSlot('gpu');
                    const motherboard = componentFromSlot('motherboard');
                    const ram = componentFromSlot('ram');
                    const psu = componentFromSlot('psu');
                    const itemCase = componentFromSlot('case');
                    const cooler = componentFromSlot('cooler');
                    const messages = [];
                    const invalidSlots = new Set();
                    const slotMessages = {};

                    const addCompatibilityIssue = (message, slots = []) => {
                        if (!message) {
                            return;
                        }

                        messages.push(message);

                        slots.forEach((slot) => {
                            const normalizedSlot = `${slot ?? ''}`.trim();

                            if (!normalizedSlot) {
                                return;
                            }

                            invalidSlots.add(normalizedSlot);
                            slotMessages[normalizedSlot] ??= [];
                            slotMessages[normalizedSlot].push(message);
                        });
                    };

                    if (cpu && motherboard && cpu.socket && motherboard.socket && cpu.socket !== motherboard.socket) {
                        addCompatibilityIssue('Процесор не сумісний із сокетом материнської плати.', ['cpu', 'motherboard']);
                    }

                    if (ram && motherboard && ram.ram_type && motherboard.ram_type && ram.ram_type !== motherboard.ram_type) {
                        addCompatibilityIssue("Оперативна пам'ять не підходить до материнської плати.", ['ram', 'motherboard']);
                    }

                    if (motherboard && itemCase && motherboard.form_factor && Array.isArray(itemCase.supported_mb_form_factors) && itemCase.supported_mb_form_factors.length > 0 && !itemCase.supported_mb_form_factors.includes(motherboard.form_factor)) {
                        addCompatibilityIssue(
                            `Обрана материнська плата ${motherboard.form_factor} не поміщається в корпус, який підтримує ${itemCase.supported_mb_form_factors.join(', ')}.`,
                            ['motherboard', 'case'],
                        );
                    }

                    if (gpu && itemCase && Number(gpu.gpu_length_mm) > 0 && Number(itemCase.max_gpu_length_mm) > 0 && Number(gpu.gpu_length_mm) > Number(itemCase.max_gpu_length_mm)) {
                        addCompatibilityIssue(
                            `Відеокарта довжиною ${Number(gpu.gpu_length_mm)} мм не поміщається в корпус з лімітом ${Number(itemCase.max_gpu_length_mm)} мм.`,
                            ['gpu', 'case'],
                        );
                    }

                    if (cooler && cpu && cpu.socket && Array.isArray(cooler.supported_sockets) && cooler.supported_sockets.length > 0 && !cooler.supported_sockets.includes(cpu.socket)) {
                        addCompatibilityIssue('Охолодження CPU не підтримує сокет обраного процесора.', ['cooler', 'cpu']);
                    }

                    if (cooler && itemCase) {
                        const radiatorSize = Number(cooler.radiator_size_mm ?? 0);
                        const supportedRadiators = Array.isArray(itemCase.supported_radiator_sizes)
                            ? itemCase.supported_radiator_sizes.map((value) => Number(value)).filter((value) => value > 0)
                            : [];
                        const coolerHeight = Number(cooler.max_cooler_height_mm ?? 0);
                        const caseCoolerLimit = Number(itemCase.max_cooler_height_mm ?? 0);

                        if (radiatorSize > 0 && supportedRadiators.length > 0 && !supportedRadiators.includes(radiatorSize)) {
                            addCompatibilityIssue('Корпус не підтримує цей радіатор СВО.', ['cooler', 'case']);
                        }

                        if (radiatorSize === 0 && coolerHeight > 0 && caseCoolerLimit > 0 && coolerHeight > caseCoolerLimit) {
                            addCompatibilityIssue(
                                `Повітряний кулер висотою ${coolerHeight} мм не поміщається в корпус з лімітом ${caseCoolerLimit} мм.`,
                                ['cooler', 'case'],
                            );
                        }
                    }

                    if (gpu && psu && Number(gpu.gpu_power_connectors) > 0 && Number(psu.pcie_power_connectors) > 0 && Number(gpu.gpu_power_connectors) > Number(psu.pcie_power_connectors)) {
                        addCompatibilityIssue('Блок живлення не має достатньо PCIe-конекторів для цієї відеокарти.', ['gpu', 'psu']);
                    }

                    if (cpu && gpu && psu) {
                        const requiredWattage = Math.ceil(((Number(cpu.cpu_tdp_w ?? 0) + Number(gpu.gpu_power_w ?? 0)) * 1.35) + 80);
                        const psuWattage = Number(psu.psu_wattage ?? 0);

                        if (requiredWattage > 0 && psuWattage > 0 && psuWattage < requiredWattage) {
                            addCompatibilityIssue(
                                `Потужності блока живлення замало: потрібно приблизно ${requiredWattage} W, а обрано ${psuWattage} W.`,
                                ['cpu', 'gpu', 'psu'],
                            );
                        }
                    }

                    const uniqueMessages = [...new Set(messages)];
                    const normalizedSlotMessages = Object.fromEntries(
                        Object.entries(slotMessages).map(([slot, issues]) => [slot, [...new Set(issues)]])
                    );

                    return {
                        isValid: uniqueMessages.length === 0,
                        messages: uniqueMessages,
                        invalidSlots: [...invalidSlots],
                        slotMessages: normalizedSlotMessages,
                        addons,
                        selection,
                        summary,
                    };
                };

                const syncProductFps = () => {
                    if (!productFpsRoot) {
                        return;
                    }

                    const game = productFpsGames[productFpsGameSelect?.value ?? productFpsConfig.defaults?.game];
                    const display = productFpsDisplays[productFpsDisplaySelect?.value ?? productFpsConfig.defaults?.display];
                    const preset = productFpsPresets[productFpsPresetSelect?.value ?? productFpsConfig.defaults?.preset];

                    if (!game || !display || !preset) {
                        return;
                    }

                    const rawMap = productFpsRoot.dataset.productFpsMap ?? '{}';
                    let map = productFpsRoot.__fpsMap;

                    if (!map) {
                        try {
                            map = JSON.parse(rawMap);
                        } catch (error) {
                            map = {};
                        }

                        productFpsRoot.__fpsMap = map;
                    }

                    const selectedKey = productStateKey({
                        game: game.id,
                        display: display.id,
                        preset: preset.id,
                    });
                    const selectedFps = Number(map[selectedKey] ?? productFpsLookup[selectedKey] ?? 0);
                    const fallbackFps = Number(productFpsRoot.dataset.productFpsFallback ?? 0);
                    const fps = Math.round(clamp(selectedFps > 0 ? selectedFps : fallbackFps, 0, 320));
                    const hasFps = fps > 0;

                    productFpsRoot.style.setProperty('--product-fps-ratio', resolveProductFpsRatio(fps).toFixed(4));

                    if (productFpsValue) {
                        productFpsValue.classList.toggle('is-empty', !hasFps);
                        productFpsValue.textContent = hasFps ? `${fps}` : '—';
                    }

                    if (productFpsFill) {
                        productFpsFill.style.width = `${(resolveProductFpsRatio(fps) * 100).toFixed(2)}%`;
                    }

                    if (productFpsStatus) {
                        productFpsStatus.classList.toggle('is-visible', !hasFps);
                        productFpsStatus.textContent = hasFps ? '' : 'FPS тест відсутній';
                    }
                };

                const syncProductPricing = () => {
                    if (!pricingRoot) {
                        return { addons: 0, total: 0, isValid: true, messages: [], invalidSlots: [], slotMessages: {}, selection: {}, summary: [] };
                    }

                    const basePrice = Number(pricingRoot.dataset.basePrice ?? 0);
                    const evaluation = getConfiguratorEvaluation();
                    const accessoryState = getAccessorySelectionState();
                    const addons = evaluation.addons + accessoryState.addons;
                    const total = basePrice + addons;

                    pricingRoot.dataset.totalPrice = `${total}`;

                    if (addonsPriceElement) {
                        addonsPriceElement.textContent = formatPrice(addons);
                    }

                    if (totalPriceElement) {
                        totalPriceElement.textContent = formatPrice(total);
                    }

                    syncOptionSelections();
                    renderCompatibility(evaluation.messages ?? []);
                    renderOptionConflicts(evaluation.invalidSlots ?? [], evaluation.slotMessages ?? {});

                    if (addToCartButton) {
                        addToCartButton.disabled = !evaluation.isValid;
                    }

                    if (productFeedback) {
                        productFeedback.classList.toggle('is-error', !evaluation.isValid);
                        productFeedback.textContent = evaluation.isValid
                            ? 'Можемо зібрати, протестувати і відправити після узгодження обраних опцій.'
                            : `${evaluation.messages?.[0] ?? 'Перевір сумісність обраних комплектуючих.'} Зміни підсвічені блоки або напиши менеджеру для підбору сумісної заміни.`;
                    }

                    return {
                        ...evaluation,
                        addons,
                        total,
                        selection: {
                            ...(evaluation.selection ?? {}),
                            ...(accessoryState.selection ?? {}),
                        },
                        summary: [
                            ...(evaluation.summary ?? []),
                            ...(accessoryState.summary ?? []),
                        ],
                    };
                };

                const normalizeQuantity = () => {
                    if (!quantityInput) {
                        return 1;
                    }

                    const nextValue = clamp(Number.parseInt(quantityInput.value || '1', 10) || 1, 1, 9);
                    quantityInput.value = `${nextValue}`;

                    return nextValue;
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

                productThumbs.forEach((thumb, index) => {
                    thumb.addEventListener('click', () => {
                        setActiveSlide(index);
                    });
                });

                galleryInfoButton?.addEventListener('click', (event) => {
                    event.stopPropagation();

                    if (!galleryInfo) {
                        return;
                    }

                    const willOpen = !galleryInfo.classList.contains('is-open');
                    galleryInfo.classList.toggle('is-open', willOpen);
                    galleryInfoButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                [productFpsGameSelect, productFpsDisplaySelect, productFpsPresetSelect].forEach((select) => {
                    select?.addEventListener('change', () => {
                        syncProductFps();
                    });
                });

                productPrevButton?.addEventListener('click', () => {
                    if (!productSlides.length) {
                        return;
                    }

                    setActiveSlide((activeSlideIndex - 1 + productSlides.length) % productSlides.length);
                });

                productNextButton?.addEventListener('click', () => {
                    if (!productSlides.length) {
                        return;
                    }

                    setActiveSlide((activeSlideIndex + 1) % productSlides.length);
                });

                productOptions.forEach((item) => {
                    item.addEventListener('toggle', () => {
                        if (!item.open) {
                            return;
                        }

                        productOptions.forEach((other) => {
                            if (other !== item) {
                                other.open = false;
                            }
                        });
                    });
                });

                optionInputs.forEach((input) => {
                    input.addEventListener('change', () => {
                        syncProductPricing();
                    });
                });

                optionImageTriggers.forEach((trigger) => {
                    trigger.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        let gallery = [];

                        try {
                            gallery = JSON.parse(trigger.dataset.optionImageGallery ?? '[]');
                        } catch (error) {
                            gallery = [];
                        }

                        openOptionImageModal(
                            normalizeOptionImageGallery(gallery, trigger.dataset.optionImageSrc ?? ''),
                            trigger.dataset.optionImageAlt ?? '',
                        );
                    });
                });

                optionImageModalPrev?.addEventListener('click', () => {
                    if (optionImageGallery.length < 2) {
                        return;
                    }

                    optionImageGalleryIndex = (optionImageGalleryIndex - 1 + optionImageGallery.length) % optionImageGallery.length;
                    renderOptionImageModal(optionImageModalImage?.getAttribute('alt') ?? '');
                });

                optionImageModalNext?.addEventListener('click', () => {
                    if (optionImageGallery.length < 2) {
                        return;
                    }

                    optionImageGalleryIndex = (optionImageGalleryIndex + 1) % optionImageGallery.length;
                    renderOptionImageModal(optionImageModalImage?.getAttribute('alt') ?? '');
                });

                optionImageModalClose?.addEventListener('click', closeOptionImageModal);

                optionImageModalThumbs?.addEventListener('click', (event) => {
                    const button = event.target.closest('[data-option-image-thumb]');

                    if (!(button instanceof HTMLElement)) {
                        return;
                    }

                    const nextIndex = Number.parseInt(button.dataset.optionImageThumb ?? '0', 10);

                    if (!Number.isNaN(nextIndex)) {
                        optionImageGalleryIndex = nextIndex;
                        renderOptionImageModal(optionImageModalImage?.getAttribute('alt') ?? '');
                    }
                });

                optionImageModal?.addEventListener('click', (event) => {
                    if (event.target === optionImageModal) {
                        closeOptionImageModal();
                    }
                });

                quantityInput?.addEventListener('change', normalizeQuantity);
                quantityInput?.addEventListener('input', normalizeQuantity);

                addToCartButton?.addEventListener('click', () => {
                    const pricingState = syncProductPricing();
                    const { total } = pricingState;
                    const quantity = normalizeQuantity();
                    const lineTotal = total * quantity;

                    if (!pricingState.isValid) {
                        return;
                    }

                    if (window.KondorCart) {
                        window.KondorCart.addItem({
                            ...productCartItem,
                            price: total,
                            cartKey: makeCartKey(productCartItem.slug, pricingState.selection ?? {}),
                            configuration: pricingState.selection ?? {},
                            configurationSummary: pricingState.summary ?? [],
                        }, quantity);
                    } else {
                        cartTotal += lineTotal;

                        if (headerCartValue) {
                            headerCartValue.textContent = formatPrice(cartTotal);
                        }
                    }

                    if (productFeedback) {
                        productFeedback.textContent = `Додано ${quantity} шт. на суму ${formatPrice(lineTotal)}. Після підтвердження можемо перейти до оплати або розстрочки.`;
                    }

                    if (addToCartButton.dataset.defaultLabel === undefined) {
                        addToCartButton.dataset.defaultLabel = addToCartButton.textContent ?? 'Додати в кошик';
                    }

                    addToCartButton.classList.add('is-added');
                    addToCartButton.textContent = 'Додано';

                    window.setTimeout(() => {
                        addToCartButton.classList.remove('is-added');
                        addToCartButton.textContent = addToCartButton.dataset.defaultLabel ?? 'Додати в кошик';
                    }, 1600);
                });

                productTradeInButton?.addEventListener('click', async (event) => {
                    const baseHref = productTradeInButton.dataset.baseHref ?? productTradeInButton.href;
                    const shareEndpoint = productTradeInButton.dataset.shareEndpoint ?? '';

                    if (!shareEndpoint) {
                        return;
                    }

                    event.preventDefault();

                    const defaultLabel = productTradeInButton.dataset.defaultLabel ?? productTradeInButton.textContent?.trim() ?? 'Запропонувати свій ПК';
                    productTradeInButton.dataset.defaultLabel = defaultLabel;
                    productTradeInButton.style.pointerEvents = 'none';
                    productTradeInButton.textContent = 'Готуємо форму...';

                    try {
                        const pricingState = syncProductPricing();
                        const response = await fetch(shareEndpoint, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                selection: pricingState.selection ?? getSelectedConfiguratorState(),
                            }),
                        });

                        if (!response.ok) {
                            throw new Error('Unable to prepare shared build link.');
                        }

                        const payload = await response.json();
                        const nextUrl = new URL(baseHref, window.location.origin);

                        if (payload?.token) {
                            nextUrl.searchParams.set('shared_build', payload.token);
                        }

                        window.location.href = nextUrl.toString();
                        return;
                    } catch (error) {
                        window.location.href = baseHref;
                        return;
                    } finally {
                        productTradeInButton.style.pointerEvents = '';
                        productTradeInButton.textContent = productTradeInButton.dataset.defaultLabel ?? defaultLabel;
                    }
                });

                buildShareButton?.addEventListener('click', async () => {
                    const shareEndpoint = buildShareButton.dataset.shareEndpoint ?? '';

                    if (!shareEndpoint) {
                        return;
                    }

                    const pricingState = syncProductPricing();
                    const defaultLabel = buildShareButton.dataset.defaultLabel ?? buildShareButtonLabel?.textContent?.trim() ?? buildShareButton.textContent?.trim() ?? 'Скопіювати посилання';
                    buildShareButton.dataset.defaultLabel = defaultLabel;
                    buildShareButton.disabled = true;
                    if (buildShareButtonLabel) {
                        buildShareButtonLabel.textContent = 'Створюємо посилання...';
                    }

                    try {
                        const response = await fetch(shareEndpoint, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                selection: pricingState.selection ?? getSelectedConfiguratorState(),
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok || !payload.url) {
                            throw new Error(payload.message || 'Не вдалося створити посилання на збірку.');
                        }

                        openBuildShareModal(payload.url, payload.expires_at ?? '');
                    } catch (error) {
                        if (productFeedback) {
                            productFeedback.textContent = error.message || 'Не вдалося створити посилання на збірку.';
                        }
                    } finally {
                        buildShareButton.disabled = false;
                        if (buildShareButtonLabel) {
                            buildShareButtonLabel.textContent = buildShareButton.dataset.defaultLabel ?? defaultLabel;
                        }
                    }
                });

                buildShareCopyButton?.addEventListener('click', async () => {
                    if (!buildShareLinkInput?.value) {
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(buildShareLinkInput.value);
                        buildShareCopyButton.textContent = 'Скопійовано';

                        window.setTimeout(() => {
                            buildShareCopyButton.textContent = 'Копіювати посилання';
                        }, 1400);
                    } catch (error) {
                        buildShareLinkInput.select();
                        document.execCommand('copy');
                    }
                });

                buildShareCloseButtons.forEach((button) => {
                    button.addEventListener('click', closeBuildShareModal);
                });

                accessoryCategoryButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        openAccessoryCategoryModal(button.dataset.accessoryCategoryOpen ?? '');
                    });
                });

                accessorySheetButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        openAccessorySheet(button.dataset.accessorySheetOpen ?? '');
                    });
                });

                accessorySelectButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        toggleAccessorySelection({
                            type: button.dataset.accessoryType ?? '',
                            slug: button.dataset.accessorySelect ?? '',
                            name: button.dataset.accessoryName ?? '',
                            price: button.dataset.accessoryPrice ?? 0,
                        });
                    });
                });

                accessoryModalCloseButtons.forEach((button) => {
                    button.addEventListener('click', closeAccessoryCategoryModals);
                });

                accessorySheetCloseButtons.forEach((button) => {
                    button.addEventListener('click', closeAccessorySheets);
                });

                document.addEventListener('click', (event) => {
                    if (galleryInfo && !event.target.closest('[data-gallery-info]')) {
                        closeGalleryInfo();
                    }

                    if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
                        closeAllDropdowns();
                    }

                    if (event.target.matches('[data-accessory-category-modal]')) {
                        closeAccessoryCategoryModals();
                    }

                    if (event.target.matches('[data-accessory-sheet]')) {
                        closeAccessorySheets();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'ArrowLeft' && optionImageGallery.length > 1 && optionImageModal && !optionImageModal.hidden) {
                        event.preventDefault();
                        optionImageGalleryIndex = (optionImageGalleryIndex - 1 + optionImageGallery.length) % optionImageGallery.length;
                        renderOptionImageModal(optionImageModalImage?.getAttribute('alt') ?? '');
                        return;
                    }

                    if (event.key === 'ArrowRight' && optionImageGallery.length > 1 && optionImageModal && !optionImageModal.hidden) {
                        event.preventDefault();
                        optionImageGalleryIndex = (optionImageGalleryIndex + 1) % optionImageGallery.length;
                        renderOptionImageModal(optionImageModalImage?.getAttribute('alt') ?? '');
                        return;
                    }

                    if (event.key === 'Escape') {
                        closeGalleryInfo();
                        closeOptionImageModal();
                        closeAccessorySheets();
                        closeAccessoryCategoryModals();
                        closeBuildShareModal();
                        closeAllDropdowns();
                        closeMobileMenu();
                    }
                });

                window.addEventListener('scroll', syncHeaderState, { passive: true });
                window.addEventListener('resize', () => {
                    syncHeaderState();
                    positionConsultationPanel();

                    if (window.innerWidth > 1080) {
                        closeMobileMenu();
                    }
                });

                syncHeaderState();
                positionConsultationPanel();
                accessorySelections = loadAccessorySelections();
                renderAccessorySelections();
                syncAccessorySelectionLabels();
                syncProductPricing();
                normalizeQuantity();
                setActiveSlide(0);
                syncProductFps();
                if (window.KondorCart) {
                    window.KondorCart.renderPreviews();
                }
            })();
        </script>
        @include('partials.admin-site-notifications')
        @include('partials.admin-inline-images')
    </body>
</html>
