@php
    $themeJsVersion = filemtime(public_path('js/theme-toggle.js'));
@endphp

<script src="{{ asset('js/theme-toggle.js') }}?v={{ $themeJsVersion }}"></script>
