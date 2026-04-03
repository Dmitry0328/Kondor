(function () {
    const storageKey = 'kondor-cart-v1';
    const listeners = new Set();

    const clampQuantity = (value) => {
        const parsed = Number.parseInt(`${value ?? 1}`, 10) || 1;
        return Math.max(1, Math.min(parsed, 99));
    };

    const escapeHtml = (value) => `${value ?? ''}`
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const formatPrice = (value) => `${new Intl.NumberFormat('uk-UA').format(Math.round(Number(value) || 0)).replace(/\u00a0/g, ' ')} \u20b4`;

    const normalizeConfiguration = (value) => {
        if (!value || typeof value !== 'object' || Array.isArray(value)) {
            return {};
        }

        return Object.fromEntries(
            Object.entries(value)
                .map(([key, entry]) => [`${key}`, `${entry ?? ''}`])
                .filter(([key, entry]) => key && entry),
        );
    };

    const normalizeConfigurationSummary = (value) => Array.isArray(value)
        ? value.map((entry) => `${entry ?? ''}`.trim()).filter(Boolean).slice(0, 8)
        : [];

    const normalizeItem = (item) => {
        if (!item || !item.slug) {
            return null;
        }

        const cartKey = `${item.cartKey ?? item.cart_key ?? item.slug}`;

        return {
            slug: `${item.slug}`,
            cartKey: cartKey || `${item.slug}`,
            name: `${item.name ?? item.slug}`,
            price: Math.max(0, Math.round(Number(item.price) || 0)),
            quantity: clampQuantity(item.quantity),
            url: `${item.url ?? ''}`,
            tone: `${item.tone ?? 'violet'}`,
            configuration: normalizeConfiguration(item.configuration ?? item.meta?.configuration ?? {}),
            configurationSummary: normalizeConfigurationSummary(item.configurationSummary ?? item.configuration_summary ?? item.meta?.configuration_summary ?? []),
        };
    };

    const loadCart = () => {
        try {
            const raw = window.localStorage.getItem(storageKey);

            if (!raw) {
                return [];
            }

            const parsed = JSON.parse(raw);

            if (!Array.isArray(parsed)) {
                return [];
            }

            return parsed.map(normalizeItem).filter(Boolean);
        } catch (error) {
            return [];
        }
    };

    const getTotal = (items = loadCart()) => items.reduce((sum, item) => sum + ((Number(item.price) || 0) * (Number(item.quantity) || 0)), 0);

    const getCount = (items = loadCart()) => items.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0);

    const renderPreviews = (items = loadCart()) => {
        const total = getTotal(items);
        const count = getCount(items);

        document.querySelectorAll('[data-cart-amount]').forEach((element) => {
            element.textContent = formatPrice(total);
        });

        document.querySelectorAll('[data-cart-count]').forEach((element) => {
            element.textContent = `${count}`;
            element.hidden = count < 1;
        });

        document.querySelectorAll('.header-cart').forEach((element) => {
            element.setAttribute(
                'aria-label',
                count > 0 ? `Кошик, ${count} ${count === 1 ? 'товар' : count < 5 ? 'товари' : 'товарів'}` : 'Кошик',
            );
        });

        document.querySelectorAll('[data-cart-preview]').forEach((preview) => {
            const emptyState = preview.querySelector('[data-cart-empty]');
            const contentState = preview.querySelector('[data-cart-content]');
            const itemsContainer = preview.querySelector('[data-cart-items]');
            const totalElement = preview.querySelector('[data-cart-total]');

            if (!emptyState || !contentState || !itemsContainer || !totalElement) {
                return;
            }

            if (!items.length) {
                emptyState.hidden = false;
                contentState.hidden = true;
                itemsContainer.innerHTML = '';
                totalElement.textContent = formatPrice(0);
                return;
            }

            emptyState.hidden = true;
            contentState.hidden = false;
            totalElement.textContent = formatPrice(total);
            itemsContainer.innerHTML = items.map((item) => `
                <div class="cart-preview__item">
                    <span class="cart-preview__thumb cart-preview__thumb--${escapeHtml(item.tone)}" aria-hidden="true"></span>
                    <div class="cart-preview__copy">
                        <a href="${escapeHtml(item.url || '/cart')}">${escapeHtml(item.name)}</a>
                        ${item.configurationSummary.length ? `<small>${escapeHtml(item.configurationSummary[0])}</small>` : ''}
                        <span>${escapeHtml(formatPrice(item.price))} x ${escapeHtml(item.quantity)}</span>
                    </div>
                </div>
            `).join('');
        });
    };

    const notify = (items) => {
        renderPreviews(items);
        listeners.forEach((listener) => listener(items));
    };

    const setCart = (items) => {
        const normalized = Array.isArray(items) ? items.map(normalizeItem).filter(Boolean) : [];

        try {
            window.localStorage.setItem(storageKey, JSON.stringify(normalized));
        } catch (error) {
            // Ignore storage failures and keep current page interactive.
        }

        notify(normalized);
        return normalized;
    };

    const addItem = (item, quantity = 1) => {
        const normalized = normalizeItem({ ...item, quantity });

        if (!normalized) {
            return loadCart();
        }

        const items = loadCart();
        const existing = items.find((entry) => entry.cartKey === normalized.cartKey);

        if (existing) {
            existing.quantity = clampQuantity(existing.quantity + normalized.quantity);
            if (!existing.url && normalized.url) {
                existing.url = normalized.url;
            }
            if ((!existing.tone || existing.tone === 'violet') && normalized.tone) {
                existing.tone = normalized.tone;
            }
            if (!existing.configurationSummary.length && normalized.configurationSummary.length) {
                existing.configurationSummary = normalized.configurationSummary;
            }
            if (!Object.keys(existing.configuration).length && Object.keys(normalized.configuration).length) {
                existing.configuration = normalized.configuration;
            }
        } else {
            items.push(normalized);
        }

        return setCart(items);
    };

    const updateQuantity = (cartKey, quantity) => {
        const items = loadCart().map((item) => {
            if (item.cartKey !== cartKey) {
                return item;
            }

            return {
                ...item,
                quantity: clampQuantity(quantity),
            };
        });

        return setCart(items);
    };

    const removeItem = (cartKey) => setCart(loadCart().filter((item) => item.cartKey !== cartKey));

    const clear = () => setCart([]);

    const encodeSharedPayload = (items = loadCart()) => {
        try {
            return window.btoa(unescape(encodeURIComponent(JSON.stringify(items))));
        } catch (error) {
            return '';
        }
    };

    const decodeSharedPayload = (payload) => {
        if (!payload) {
            return [];
        }

        try {
            const decoded = decodeURIComponent(escape(window.atob(payload)));
            const parsed = JSON.parse(decoded);
            return Array.isArray(parsed) ? parsed.map(normalizeItem).filter(Boolean) : [];
        } catch (error) {
            return [];
        }
    };

    const api = {
        storageKey,
        formatPrice,
        loadCart,
        setCart,
        addItem,
        updateQuantity,
        removeItem,
        clear,
        getTotal,
        getCount,
        renderPreviews,
        encodeSharedPayload,
        decodeSharedPayload,
        subscribe(listener) {
            if (typeof listener !== 'function') {
                return () => {};
            }

            listeners.add(listener);
            listener(loadCart());

            return () => listeners.delete(listener);
        },
    };

    window.KondorCart = api;

    const boot = () => {
        renderPreviews(loadCart());
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    window.addEventListener('storage', (event) => {
        if (event.key !== storageKey) {
            return;
        }

        const items = loadCart();
        renderPreviews(items);
        listeners.forEach((listener) => listener(items));
    });
})();
