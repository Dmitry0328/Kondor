(function () {
    const consentCookieName = 'kondor_cookie_consent';
    const root = document.querySelector('[data-cookie-consent-root]');

    const parseConsent = () => {
        const match = document.cookie.match(new RegExp('(?:^|; )' + consentCookieName + '=([^;]*)'));

        if (!match) {
            return null;
        }

        try {
            const parsed = JSON.parse(decodeURIComponent(match[1]));

            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (error) {
            return null;
        }
    };

    const getDefaultState = () => ({
        necessary: true,
        preferences: false,
        analytics: false,
        marketing: false,
        updated_at: null,
    });

    const normalizeState = (state) => ({
        necessary: true,
        preferences: Boolean(state?.preferences),
        analytics: Boolean(state?.analytics),
        marketing: Boolean(state?.marketing),
        updated_at: state?.updated_at ?? null,
    });

    const hasConsent = (category) => {
        const state = normalizeState(parseConsent() ?? getDefaultState());

        if (category === 'necessary') {
            return true;
        }

        return Boolean(state[category]);
    };

    window.KondorCookieConsent = {
        cookieName: consentCookieName,
        getState: () => normalizeState(parseConsent() ?? getDefaultState()),
        hasConsent,
    };

    if (!root) {
        return;
    }

    const banner = root.querySelector('[data-cookie-consent-banner]');
    const modal = document.querySelector('[data-cookie-consent-modal]');
    const manageButton = document.querySelector('[data-cookie-consent-manage]');
    const settingsButton = root.querySelector('[data-cookie-consent-open-settings]');
    const acceptAllButton = root.querySelector('[data-cookie-consent-accept-all]');
    const necessaryOnlyButton = root.querySelector('[data-cookie-consent-necessary-only]');
    const modalAcceptAllButton = modal?.querySelector('[data-cookie-consent-save-all]');
    const modalSaveButton = modal?.querySelector('[data-cookie-consent-save-selected]');
    const modalCloseButtons = Array.from(document.querySelectorAll('[data-cookie-consent-close]'));
    const preferenceInputs = Array.from(document.querySelectorAll('[data-cookie-consent-category]'));

    const writeState = (state) => {
        const normalized = normalizeState({
            ...state,
            updated_at: new Date().toISOString(),
        });

        document.cookie = consentCookieName
            + '=' + encodeURIComponent(JSON.stringify(normalized))
            + '; path=/; max-age=' + (60 * 60 * 24 * 180)
            + '; SameSite=Lax';

        window.KondorCookieConsent.getState = () => normalized;
        window.KondorCookieConsent.hasConsent = (category) => {
            if (category === 'necessary') {
                return true;
            }

            return Boolean(normalized[category]);
        };

        document.dispatchEvent(new CustomEvent('kondor:cookie-consent-change', {
            detail: { consent: normalized },
        }));
    };

    const syncInputs = (state) => {
        const normalized = normalizeState(state);

        preferenceInputs.forEach((wrapper) => {
            const category = wrapper.dataset.cookieConsentCategory;
            const input = wrapper.querySelector('input[type="checkbox"]');

            if (!input || !category || category === 'necessary') {
                return;
            }

            input.checked = Boolean(normalized[category]);
        });
    };

    const hideBanner = () => {
        root.classList.add('is-hidden');
    };

    const showBanner = () => {
        root.classList.remove('is-hidden');
    };

    const openModal = () => {
        if (!modal) {
            return;
        }

        syncInputs(parseConsent() ?? getDefaultState());
        modal.classList.add('is-open');
        document.documentElement.style.overflow = 'hidden';
    };

    const closeModal = () => {
        if (!modal) {
            return;
        }

        modal.classList.remove('is-open');
        document.documentElement.style.overflow = '';
    };

    const finish = () => {
        hideBanner();

        if (manageButton) {
            manageButton.hidden = false;
        }

        closeModal();
    };

    const saveNecessaryOnly = () => {
        writeState({
            necessary: true,
            preferences: false,
            analytics: false,
            marketing: false,
        });

        finish();
    };

    const saveAll = () => {
        writeState({
            necessary: true,
            preferences: true,
            analytics: true,
            marketing: true,
        });

        finish();
    };

    const saveSelected = () => {
        const state = {
            necessary: true,
            preferences: false,
            analytics: false,
            marketing: false,
        };

        preferenceInputs.forEach((wrapper) => {
            const category = wrapper.dataset.cookieConsentCategory;
            const input = wrapper.querySelector('input[type="checkbox"]');

            if (!input || !category || category === 'necessary') {
                return;
            }

            state[category] = input.checked;
        });

        writeState(state);
        finish();
    };

    const existingConsent = parseConsent();

    if (existingConsent) {
        hideBanner();

        if (manageButton) {
            manageButton.hidden = false;
        }
    } else {
        showBanner();
    }

    settingsButton?.addEventListener('click', openModal);
    acceptAllButton?.addEventListener('click', saveAll);
    necessaryOnlyButton?.addEventListener('click', saveNecessaryOnly);
    modalAcceptAllButton?.addEventListener('click', saveAll);
    modalSaveButton?.addEventListener('click', saveSelected);
    manageButton?.addEventListener('click', openModal);

    modalCloseButtons.forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    modal?.addEventListener('click', (event) => {
        if (event.target === modal || event.target.hasAttribute('data-cookie-consent-close')) {
            closeModal();
        }
    });
})();
