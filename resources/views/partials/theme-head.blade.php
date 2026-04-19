@php
    $themeCssVersion = filemtime(public_path('css/theme-toggle.css'));
@endphp

<script>
    (function () {
        const consentCookieName = 'kondor_cookie_consent';
        const storageKey = 'kondor-theme';
        const root = document.documentElement;
        let theme = null;
        let preferencesAllowed = false;

        try {
            const match = document.cookie.match(new RegExp('(?:^|; )' + consentCookieName + '=([^;]*)'));

            if (match) {
                const consent = JSON.parse(decodeURIComponent(match[1]));
                preferencesAllowed = Boolean(consent?.preferences);
            }
        } catch (error) {
            preferencesAllowed = false;
        }

        if (preferencesAllowed) {
            try {
                theme = window.localStorage.getItem(storageKey);
            } catch (error) {
                theme = null;
            }
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
<link rel="stylesheet" href="{{ asset('css/cookie-consent.css') }}?v={{ filemtime(public_path('css/cookie-consent.css')) }}">
<link rel="stylesheet" href="{{ asset('css/theme-toggle.css') }}?v={{ $themeCssVersion }}">
