(() => {
    const storageKey = 'kondor-theme';
    const root = document.documentElement;
    const menu = document.querySelector('[data-menu]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const themeLabels = document.querySelectorAll('[data-theme-label]');

    const applyTheme = (theme) => {
        root.dataset.theme = theme;
        themeLabels.forEach((label) => {
            label.textContent = theme === 'dark' ? 'Темна' : 'Світла';
        });
    };

    const savedTheme = localStorage.getItem(storageKey);
    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    applyTheme(savedTheme || systemTheme);

    themeToggle?.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        applyTheme(nextTheme);
        localStorage.setItem(storageKey, nextTheme);
    });

    const closeMenu = () => {
        if (!menu || !menuToggle) {
            return;
        }

        menu.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-open');
    };

    menuToggle?.addEventListener('click', () => {
        if (!menu) {
            return;
        }

        const isOpen = menu.classList.toggle('is-open');
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.classList.toggle('menu-open', isOpen);
    });

    menu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    document.querySelectorAll('[data-gallery]').forEach((gallery) => {
        const panels = [...gallery.querySelectorAll('[data-gallery-panel]')];
        const thumbs = [...gallery.querySelectorAll('[data-gallery-thumb]')];

        const activate = (index) => {
            panels.forEach((panel, panelIndex) => {
                panel.hidden = panelIndex !== index;
            });

            thumbs.forEach((thumb, thumbIndex) => {
                thumb.classList.toggle('is-active', thumbIndex === index);
            });
        };

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', () => activate(index));
        });

        activate(0);
    });
})();
