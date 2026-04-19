(function () {
    const config = document.querySelector('[data-compare-config]');

    if (!config) {
        return;
    }

    const storageKey = 'kondor-compare-v1';
    const listeners = new Set();
    const compareUrlBase = `${config.dataset.compareUrl ?? '/catalog/compare'}`.trim() || '/catalog/compare';
    const compareLimit = Math.max(1, Math.min(Number.parseInt(config.dataset.compareLimit ?? '3', 10) || 3, 3));
    const comparePageItems = (() => {
        try {
            const parsed = JSON.parse(config.dataset.comparePageItems ?? '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    })();

    const parseValidSlugs = () => {
        try {
            const parsed = JSON.parse(config.dataset.compareValidSlugs ?? '[]');

            if (!Array.isArray(parsed)) {
                return new Set();
            }

            return new Set(
                parsed
                    .map((slug) => `${slug ?? ''}`.trim())
                    .filter(Boolean),
            );
        } catch (error) {
            return new Set();
        }
    };

    const validSlugs = parseValidSlugs();

    const normalizeItems = (value) => {
        const items = Array.isArray(value) ? value : [];
        const normalized = [];

        items.forEach((slug) => {
            const nextSlug = `${slug ?? ''}`.trim();

            if (!nextSlug) {
                return;
            }

            if (validSlugs.size && !validSlugs.has(nextSlug)) {
                return;
            }

            if (normalized.includes(nextSlug)) {
                return;
            }

            normalized.push(nextSlug);
        });

        return normalized.slice(0, compareLimit);
    };

    const load = () => {
        try {
            const raw = window.localStorage.getItem(storageKey);

            if (!raw) {
                return [];
            }

            return normalizeItems(JSON.parse(raw));
        } catch (error) {
            return [];
        }
    };

    const save = (items) => {
        const normalized = normalizeItems(items);

        try {
            window.localStorage.setItem(storageKey, JSON.stringify(normalized));
        } catch (error) {
            // Ignore storage failures and keep the page usable.
        }

        render(normalized);
        listeners.forEach((listener) => listener(normalized));

        return normalized;
    };

    const getCompareUrl = (items = load()) => {
        const normalized = normalizeItems(items);

        if (!normalized.length) {
            return compareUrlBase;
        }

        return `${compareUrlBase}?items=${encodeURIComponent(normalized.join(','))}`;
    };

    const setText = (element, value) => {
        if (!element) {
            return;
        }

        element.textContent = value;
    };

    const setCompareButtonState = (button, items) => {
        const slug = `${button.dataset.compareSlug ?? ''}`.trim();
        const isActive = slug !== '' && items.includes(slug);
        const defaultLabel = button.dataset.compareLabelDefault ?? 'Порівняти';
        const activeLabel = button.dataset.compareLabelActive ?? 'У порівнянні';
        const label = isActive ? activeLabel : defaultLabel;
        const labelNode = button.querySelector('[data-compare-label]');

        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-pressed', isActive ? 'true' : 'false');

        if (labelNode) {
            labelNode.textContent = label;
        } else {
            button.textContent = label;
        }
    };

    const showFeedback = (message, isError = false) => {
        const feedback = document.querySelector('[data-compare-feedback]');

        if (!feedback) {
            return;
        }

        feedback.hidden = !message;
        feedback.textContent = message || '';
        feedback.classList.toggle('compare-dock__feedback--error', isError);
    };

    const flashButtonText = (button, value) => {
        if (!button) {
            return;
        }

        const labelNode = button.querySelector('[data-compare-label]');
        const target = labelNode ?? button;
        const previous = target.textContent;

        target.textContent = value;

        window.setTimeout(() => {
            target.textContent = previous;
        }, 1200);
    };

    const render = (items = load()) => {
        const normalized = normalizeItems(items);

        document.querySelectorAll('[data-compare-count]').forEach((element) => {
            element.textContent = `${normalized.length}`;
            element.hidden = normalized.length < 1;
        });

        document.querySelectorAll('[data-compare-link]').forEach((element) => {
            if (element instanceof HTMLAnchorElement) {
                element.href = getCompareUrl(normalized);
            }

            element.classList.toggle('is-disabled', normalized.length < 1);
            element.setAttribute('aria-disabled', normalized.length < 1 ? 'true' : 'false');
        });

        document.querySelectorAll('[data-compare-toggle]').forEach((button) => {
            setCompareButtonState(button, normalized);
        });

        const dock = document.querySelector('[data-compare-dock]');
        const dockCount = document.querySelector('[data-compare-dock-count]');

        if (dockCount) {
            dockCount.textContent = `${normalized.length}`;
        }

        if (dock) {
            dock.hidden = normalized.length < 1;
        }

        document.body.classList.toggle('has-compare-dock', Boolean(dock) && normalized.length > 0);
    };

    const add = (slug) => {
        const nextSlug = `${slug ?? ''}`.trim();

        if (!nextSlug) {
            return { items: load(), status: 'invalid' };
        }

        const items = load();

        if (items.includes(nextSlug)) {
            return { items, status: 'exists' };
        }

        if (items.length >= compareLimit) {
            return { items, status: 'limit' };
        }

        return {
            items: save([...items, nextSlug]),
            status: 'added',
        };
    };

    const remove = (slug) => {
        const nextSlug = `${slug ?? ''}`.trim();
        const items = load().filter((item) => item !== nextSlug);

        return {
            items: save(items),
            status: 'removed',
        };
    };

    const clear = () => {
        return {
            items: save([]),
            status: 'cleared',
        };
    };

    const syncComparePage = () => {
        if (!config.dataset.comparePageItems) {
            return;
        }

        const currentUrl = new URL(window.location.href);
        const currentItems = normalizeItems(
            `${currentUrl.searchParams.get('items') ?? ''}`
                .split(',')
                .map((slug) => slug.trim()),
        );
        const nextItems = load();

        if (JSON.stringify(currentItems) === JSON.stringify(nextItems)) {
            return;
        }

        window.location.href = getCompareUrl(nextItems);
    };

    const handleToggle = (button) => {
        const slug = `${button.dataset.compareSlug ?? ''}`.trim();

        if (!slug) {
            return;
        }

        const isActive = load().includes(slug);
        const result = isActive ? remove(slug) : add(slug);

        if (result.status === 'limit') {
            flashButtonText(button, `Максимум ${compareLimit}`);
            showFeedback(`Можна порівнювати максимум ${compareLimit} збірки одночасно.`, true);
            return;
        }

        if (result.status === 'added') {
            showFeedback('Збірку додано до порівняння.');
        } else if (result.status === 'removed') {
            showFeedback('Збірку прибрано з порівняння.');
        } else {
            showFeedback('');
        }

        if (config.dataset.comparePageItems) {
            syncComparePage();
        }
    };

    const api = {
        storageKey,
        limit: compareLimit,
        load,
        save,
        add,
        remove,
        clear,
        getCompareUrl,
        render,
        subscribe(listener) {
            if (typeof listener !== 'function') {
                return () => {};
            }

            listeners.add(listener);
            listener(load());

            return () => listeners.delete(listener);
        },
    };

    window.KondorCompare = api;

    if (comparePageItems.length) {
        save(comparePageItems);
    }

    document.addEventListener('click', (event) => {
        const toggleButton = event.target.closest('[data-compare-toggle]');

        if (toggleButton) {
            event.preventDefault();
            event.stopPropagation();
            handleToggle(toggleButton);
            return;
        }

        const removeButton = event.target.closest('[data-compare-remove]');

        if (removeButton) {
            event.preventDefault();
            remove(removeButton.dataset.compareSlug ?? '');
            syncComparePage();
            return;
        }

        const clearButton = event.target.closest('[data-compare-clear]');

        if (clearButton) {
            event.preventDefault();
            clear();
            syncComparePage();
            return;
        }

        const compareLink = event.target.closest('[data-compare-link]');

        if (compareLink && compareLink.getAttribute('aria-disabled') === 'true') {
            event.preventDefault();
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => render(load()), { once: true });
    } else {
        render(load());
    }

    window.addEventListener('storage', (event) => {
        if (event.key !== storageKey) {
            return;
        }

        render(load());
        listeners.forEach((listener) => listener(load()));
    });
})();
