@php
    $themeCssVersion = filemtime(public_path('css/theme-toggle.css'));
@endphp

<script>
    (function () {
        const storageKey = 'kondor-theme';
        const root = document.documentElement;
        let theme = null;

        try {
            theme = window.localStorage.getItem(storageKey);
        } catch (error) {
            theme = null;
        }

        if (theme !== 'light' && theme !== 'dark') {
            theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        root.dataset.theme = theme;
    })();
</script>
<link rel="icon" type="image/svg+xml" href="{{ asset('images/kondor-mark-black.svg') }}">
<link rel="shortcut icon" href="{{ asset('images/kondor-mark-black.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('images/kondor-mark-black.svg') }}">
<link rel="stylesheet" href="{{ asset('css/theme-toggle.css') }}?v={{ $themeCssVersion }}">
