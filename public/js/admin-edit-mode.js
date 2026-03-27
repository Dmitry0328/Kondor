(function () {
    const toggles = Array.from(document.querySelectorAll('[data-admin-edit-mode-toggle]'));

    if (!toggles.length) {
        return;
    }

    const storageKey = 'kondor-admin-edit-mode';
    const root = document.documentElement;
    document.body.classList.add('has-admin-edit-mode-toggle');

    const readMode = () => {
        try {
            return window.localStorage.getItem(storageKey) === 'on';
        } catch (error) {
            return false;
        }
    };

    const writeMode = (enabled) => {
        try {
            window.localStorage.setItem(storageKey, enabled ? 'on' : 'off');
        } catch (error) {
            // Ignore storage write issues and keep in-memory UI state.
        }
    };

    const syncToggles = (enabled) => {
        toggles.forEach((toggle) => {
            const label = toggle.querySelector('[data-admin-edit-mode-label]');

            toggle.dataset.editMode = enabled ? 'on' : 'off';
            toggle.setAttribute('aria-pressed', enabled ? 'true' : 'false');

            if (label) {
                label.textContent = enabled
                    ? (toggle.dataset.adminEditModeOnLabel || 'Edit mode: ON')
                    : (toggle.dataset.adminEditModeOffLabel || 'Edit mode: OFF');
            }
        });
    };

    const applyMode = (enabled) => {
        root.dataset.adminEditMode = enabled ? 'on' : 'off';
        syncToggles(enabled);
        document.dispatchEvent(new CustomEvent('kondor:admin-edit-mode-change', {
            detail: { enabled },
        }));
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const nextMode = root.dataset.adminEditMode !== 'on';
            writeMode(nextMode);
            applyMode(nextMode);
        });
    });

    applyMode(readMode());
})();
