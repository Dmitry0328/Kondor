(function () {
    const editor = document.querySelector('[data-admin-inline-images]');

    if (!editor) {
        return;
    }

    const endpoint = editor.dataset.uploadEndpoint ?? '';
    const csrfToken = editor.dataset.csrfToken ?? '';
    const input = editor.querySelector('[data-admin-inline-images-input]');
    let activeKey = null;
    let activeTargets = [];

    if (!endpoint || !csrfToken || !input) {
        return;
    }

    const setLoading = (elements, isLoading) => {
        elements.forEach((element) => {
            element.classList.toggle('is-site-image-uploading', isLoading);

            const badgeLabel = element.querySelector('[data-site-image-edit-label]');

            if (badgeLabel) {
                badgeLabel.textContent = isLoading ? 'Завантаження...' : 'Змінити фото';
            }
        });
    };

    const applyImage = (key, url) => {
        document.querySelectorAll(`[data-site-image-key="${key}"]`).forEach((element) => {
            element.classList.add('has-site-image');
            element.style.setProperty('--site-image-url', `url("${url}")`);
            element.dataset.siteImageUrl = url;
        });
    };

    const openUploaderFor = (element) => {
        activeKey = element.dataset.siteImageKey ?? '';
        activeTargets = Array.from(document.querySelectorAll(`[data-site-image-key="${activeKey}"]`));
        input.click();
    };

    const decorateTarget = (element) => {
        if (element.dataset.siteImageBound === '1') {
            return;
        }

        element.dataset.siteImageBound = '1';
        element.classList.add('site-image-target');

        if (element.hasAttribute('data-site-image-passive')) {
            return;
        }

        element.classList.add('is-site-image-editable');

        let badge = element.querySelector('.site-image-edit-badge');

        if (!badge) {
            badge = document.createElement('button');
            badge.type = 'button';
            badge.className = 'site-image-edit-badge';
            badge.setAttribute('aria-label', 'Змінити фото');
            badge.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h2.1l1.1-1.4A2 2 0 0 1 11.3 3h1.4a2 2 0 0 1 1.6.6L15.4 5h2.1A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.7"/></svg><span data-site-image-edit-label>Змінити фото</span>';
            element.appendChild(badge);
        }

        if (badge.dataset.siteImageBound === '1') {
            return;
        }

        badge.dataset.siteImageBound = '1';

        badge.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            openUploaderFor(element);
        });
    };

    const scanTargets = (root) => {
        if (!(root instanceof Element || root instanceof Document)) {
            return;
        }

        if (root instanceof Element && root.hasAttribute('data-site-image-key')) {
            decorateTarget(root);
        }

        root.querySelectorAll?.('[data-site-image-key]').forEach((target) => {
            decorateTarget(target);
        });
    };

    scanTargets(document);

    const observer = new MutationObserver((records) => {
        records.forEach((record) => {
            record.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    scanTargets(node);
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });

    input.addEventListener('change', async () => {
        const file = input.files?.[0];

        if (!file || !activeKey || !activeTargets.length) {
            input.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('key', activeKey);
        formData.append('image', file);

        setLoading(activeTargets, true);

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: formData,
                credentials: 'same-origin',
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok || !payload.url) {
                throw new Error(payload.message || 'Не вдалося завантажити фото.');
            }

            applyImage(activeKey, payload.url);
        } catch (error) {
            window.alert(error instanceof Error ? error.message : 'Не вдалося завантажити фото.');
        } finally {
            setLoading(activeTargets, false);
            input.value = '';
            activeKey = null;
            activeTargets = [];
        }
    });
})();
