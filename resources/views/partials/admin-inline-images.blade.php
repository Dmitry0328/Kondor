@auth
    @if (auth()->user()?->is_admin)
        @include('partials.admin-edit-mode-toggle')
        <div
            data-admin-inline-images
            data-upload-endpoint="{{ route('site-images.store') }}"
            data-csrf-token="{{ csrf_token() }}"
            hidden
        >
            <input data-admin-inline-images-input type="file" accept="image/*">
        </div>
        <script src="{{ asset('js/admin-edit-mode.js') }}"></script>
        <script src="{{ asset('js/admin-inline-images.js') }}"></script>
    @endif
@endauth
