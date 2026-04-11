(function () {
    const storageKey = 'kondor-theme';
    const root = document.documentElement;
    const mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

    const getStoredTheme = () => {
        try {
            const theme = window.localStorage.getItem(storageKey);
            return theme === 'light' || theme === 'dark' ? theme : null;
        } catch (error) {
            return null;
        }
    };

    const getPreferredTheme = () => {
        if (mediaQuery && mediaQuery.matches) {
            return 'dark';
        }

        return 'light';
    };

    const getActiveTheme = () => root.dataset.theme === 'dark' ? 'dark' : 'light';

    const persistTheme = (theme) => {
        try {
            window.localStorage.setItem(storageKey, theme);
        } catch (error) {
            // Ignore storage write issues and keep the UI working.
        }
    };

    const applyTheme = (theme) => {
        root.dataset.theme = theme;
        const isDark = theme === 'dark';
        const toggleTitle = isDark ? '\u0423\u0432\u0456\u043C\u043A\u043D\u0443\u0442\u0438 \u0441\u0432\u0456\u0442\u043B\u0443 \u0442\u0435\u043C\u0443' : '\u0423\u0432\u0456\u043C\u043A\u043D\u0443\u0442\u0438 \u0442\u0435\u043C\u043D\u0443 \u0442\u0435\u043C\u0443';
        const toggleLabel = isDark ? '\u0421\u0432\u0456\u0442\u043B\u0430 \u0442\u0435\u043C\u0430' : '\u0422\u0435\u043C\u043D\u0430 \u0442\u0435\u043C\u0430';

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            const label = button.querySelector('[data-theme-toggle-label]');
            button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            button.setAttribute('aria-label', toggleTitle);
            button.setAttribute('title', toggleTitle);

            if (label) {
                label.textContent = toggleLabel;
            }
        });
    };

    const syncSystemTheme = () => {
        if (getStoredTheme()) {
            return;
        }

        applyTheme(getPreferredTheme());
    };

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-theme-toggle]');

        if (!button) {
            return;
        }

        const nextTheme = getActiveTheme() === 'dark' ? 'light' : 'dark';
        persistTheme(nextTheme);
        applyTheme(nextTheme);
    });

    if (mediaQuery) {
        const handleChange = () => syncSystemTheme();

        if (typeof mediaQuery.addEventListener === 'function') {
            mediaQuery.addEventListener('change', handleChange);
        } else if (typeof mediaQuery.addListener === 'function') {
            mediaQuery.addListener(handleChange);
        }
    }

    applyTheme(getStoredTheme() ?? getPreferredTheme());
})();
