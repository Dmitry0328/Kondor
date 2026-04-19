@auth
    @if (auth()->user()?->is_admin)
        <div class="admin-tools-stack">
            <div
                class="admin-online-counter"
                data-online-visitors-display
                data-online-visitors-label-template="Онлайн: :count"
                aria-live="polite"
            >
                <span class="admin-online-counter__eyebrow">Зараз на сайті</span>
                <strong class="admin-online-counter__value" data-online-visitors-count>{{ \App\Support\OnlineVisitors::activeCount() }}</strong>
                <span class="admin-online-counter__label" data-online-visitors-label>
                    Онлайн: {{ \App\Support\OnlineVisitors::activeCount() }}
                </span>
            </div>

            <button
                type="button"
                class="admin-edit-mode-toggle"
                data-admin-edit-mode-toggle
                data-admin-edit-mode-off-label="Редагування: вимкнено"
                data-admin-edit-mode-on-label="Редагування: увімкнено"
                aria-pressed="false"
            >
                <span data-admin-edit-mode-label>Редагування: вимкнено</span>
            </button>
        </div>
    @endif
@endauth
