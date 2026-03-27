@auth
    @if (auth()->user()?->is_admin)
        <button
            type="button"
            class="admin-edit-mode-toggle"
            data-admin-edit-mode-toggle
            data-admin-edit-mode-off-label="Edit mode: OFF"
            data-admin-edit-mode-on-label="Edit mode: ON"
            aria-pressed="false"
        >
            <span data-admin-edit-mode-label>Edit mode: OFF</span>
        </button>
    @endif
@endauth
