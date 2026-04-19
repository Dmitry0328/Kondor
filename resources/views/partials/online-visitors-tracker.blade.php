<div
    data-online-visitors-tracker
    data-endpoint="{{ route('online-visitors.ping') }}"
    data-csrf-token="{{ csrf_token() }}"
    data-context="{{ request()->is('admin*') ? 'admin' : 'storefront' }}"
    hidden
></div>
<script src="{{ asset('js/online-visitors.js') }}"></script>
