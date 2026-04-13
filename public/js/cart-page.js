(function () {
    const page = document.querySelector('[data-cart-page]');

    if (!page || !window.KondorCart) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const shareEndpoint = page.dataset.shareEndpoint ?? '';
    const checkoutEndpoint = page.dataset.checkoutEndpoint ?? '';
    const cartUrl = page.dataset.cartUrl ?? '/cart';
    const mode = page.dataset.cartMode === 'shared' ? 'shared' : 'local';
    const header = document.querySelector('.header');
    const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
    const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));
    const mobileToggle = document.querySelector('[data-mobile-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const orderAccessStorageKey = 'kondor-latest-order-access-v1';
    const orderAccessStorageLifetimeMs = 1000 * 60 * 60 * 12;
    const emptyOrderAccessValue = '\u2014';
    let closeTimer;

    const parseItems = (value) => {
        if (!value) {
            return [];
        }

        try {
            const parsed = JSON.parse(value);

            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    };

    const parseMap = (value) => {
        if (!value) {
            return {};
        }

        try {
            const parsed = JSON.parse(value);

            return parsed && typeof parsed === 'object' ? parsed : {};
        } catch (error) {
            return {};
        }
    };

    const normalizeItems = (items) => items
        .map((item) => ({
            itemType: `${item.itemType ?? item.item_type ?? 'build'}`,
            slug: `${item.slug ?? ''}`,
            cartKey: `${item.cartKey ?? item.cart_key ?? item.slug ?? ''}`,
            name: `${item.name ?? ''}`,
            price: Math.max(0, Math.round(Number(item.price) || 0)),
            quantity: Math.max(1, Math.min(Number.parseInt(`${item.quantity ?? 1}`, 10) || 1, 99)),
            url: `${item.url ?? ''}`,
            tone: `${item.tone ?? 'violet'}`,
            configuration: item.configuration && typeof item.configuration === 'object' && !Array.isArray(item.configuration) ? item.configuration : {},
            configurationSummary: Array.isArray(item.configurationSummary ?? item.configuration_summary)
                ? (item.configurationSummary ?? item.configuration_summary).map((entry) => `${entry ?? ''}`.trim()).filter(Boolean).slice(0, 8)
                : [],
        }))
        .filter((item) => item.slug && item.name && item.cartKey);

    const buildCoverImages = parseMap(page.dataset.buildCoverImages);

    let stateItems = mode === 'shared'
        ? normalizeItems(parseItems(page.dataset.sharedCart))
        : normalizeItems(window.KondorCart.loadCart());

    const emptyState = page.querySelector('[data-cart-empty-state]');
    const contentState = page.querySelector('[data-cart-content-state]');
    const itemsContainer = page.querySelector('[data-cart-items-page]');
    const pageTotal = page.querySelector('[data-cart-page-total]');
    const checkoutTotal = page.querySelector('[data-cart-checkout-total]');
    const clearButton = page.querySelector('[data-cart-clear]');
    const shareButton = page.querySelector('[data-cart-share]');
    const shareButtonLabel = shareButton?.querySelector('[data-cart-share-label]');
    const importButton = page.querySelector('[data-cart-import]');
    const checkoutForm = page.querySelector('[data-checkout-form]');
    const checkoutFeedback = page.querySelector('[data-checkout-feedback]');
    const orderAccess = page.querySelector('[data-order-access]');
    const orderAccessCards = Array.from(page.querySelectorAll('[data-order-access-copy]'));
    const orderAccessNumber = page.querySelector('[data-order-access-number-value]');
    const orderAccessPhone = page.querySelector('[data-order-access-phone-value]');
    const orderAccessPassword = page.querySelector('[data-order-access-password-value]');
    const orderAccessLink = page.querySelector('[data-order-access-link]');
    const orderAccessMeta = page.querySelector('[data-order-access-meta]');
    const shareModal = document.querySelector('[data-share-modal]');
    const shareLinkInput = shareModal?.querySelector('[data-share-link]');
    const shareMeta = shareModal?.querySelector('[data-share-meta]');
    const shareCopyButton = shareModal?.querySelector('[data-share-copy]');
    const shareCloseButtons = Array.from(document.querySelectorAll('[data-share-close]'));

    const setFeedback = (message, tone = '') => {
        if (!checkoutFeedback) {
            return;
        }

        checkoutFeedback.textContent = message;
        checkoutFeedback.classList.remove('is-success', 'is-error');

        if (tone) {
            checkoutFeedback.classList.add(`is-${tone}`);
        }
    };

    const buildOrderAccessState = (data, payload = {}) => {
        const orderNumber = `${data.order_number ?? data.orderNumber ?? ''}`.trim();
        const phone = `${payload.phone ?? data.phone ?? ''}`.trim();
        const trackingPassword = `${data.tracking_password ?? data.trackingPassword ?? ''}`.trim();
        const trackingUrl = `${data.tracking_url ?? data.trackingUrl ?? ''}`.trim();

        return {
            order_number: orderNumber,
            phone,
            tracking_password: trackingPassword,
            tracking_url: trackingUrl,
            email_sent: Boolean(data.email_sent ?? data.emailSent),
        };
    };

    const setOrderAccessCardValue = (type, value, copiedLabel = '') => {
        const card = orderAccessCards.find((item) => item.dataset.orderAccessCopy === type);

        if (!card) {
            return;
        }

        card.dataset.copyValue = value;
        card.classList.remove('is-copied');

        const hint = card.querySelector('small');

        if (hint) {
            hint.textContent = copiedLabel || 'Натисни, щоб скопіювати';
        }
    };

    const persistOrderAccess = (data, payload) => {
        if (!window.sessionStorage) {
            return;
        }

        const normalized = buildOrderAccessState(data, payload);
        const orderNumber = normalized.order_number;
        const phone = normalized.phone;
        const trackingPassword = normalized.tracking_password;

        if (!orderNumber || !phone || !trackingPassword) {
            window.sessionStorage.removeItem(orderAccessStorageKey);

            return;
        }

        try {
            window.sessionStorage.setItem(orderAccessStorageKey, JSON.stringify({
                order_number: orderNumber,
                phone,
                tracking_password: trackingPassword,
                tracking_url: normalized.tracking_url,
                email_sent: normalized.email_sent,
                expires_at: Date.now() + orderAccessStorageLifetimeMs,
            }));
        } catch (error) {
            // Ignore storage errors and keep the checkout flow working.
        }
    };

    const loadOrderAccess = () => {
        if (!window.sessionStorage) {
            return null;
        }

        try {
            const rawValue = window.sessionStorage.getItem(orderAccessStorageKey);

            if (!rawValue) {
                return null;
            }

            const parsed = JSON.parse(rawValue);

            if (!parsed || typeof parsed !== 'object') {
                window.sessionStorage.removeItem(orderAccessStorageKey);

                return null;
            }

            if (Number(parsed.expires_at) < Date.now()) {
                window.sessionStorage.removeItem(orderAccessStorageKey);

                return null;
            }

            if (!`${parsed.order_number ?? ''}`.trim() || !`${parsed.phone ?? ''}`.trim() || !`${parsed.tracking_password ?? ''}`.trim()) {
                window.sessionStorage.removeItem(orderAccessStorageKey);

                return null;
            }

            return parsed;
        } catch (error) {
            window.sessionStorage.removeItem(orderAccessStorageKey);

            return null;
        }
    };

    const showOrderAccess = (data, payload) => {
        if (!orderAccess) {
            return;
        }

        const normalized = buildOrderAccessState(data, payload);

        if (orderAccessNumber) {
            orderAccessNumber.textContent = normalized.order_number || emptyOrderAccessValue;
        }

        if (orderAccessPhone) {
            orderAccessPhone.textContent = normalized.phone || emptyOrderAccessValue;
        }

        if (orderAccessPassword) {
            orderAccessPassword.textContent = normalized.tracking_password || emptyOrderAccessValue;
        }

        setOrderAccessCardValue('number', normalized.order_number);
        setOrderAccessCardValue('phone', normalized.phone);
        setOrderAccessCardValue('password', normalized.tracking_password);

        if (orderAccessLink && normalized.tracking_url) {
            orderAccessLink.href = normalized.tracking_url;
        }

        if (orderAccessMeta) {
            orderAccessMeta.textContent = normalized.email_sent
                ? '\u041c\u0438 \u0442\u0430\u043a\u043e\u0436 \u043d\u0430\u0434\u0456\u0441\u043b\u0430\u043b\u0438 \u0446\u0456 \u0434\u0430\u043d\u0456 \u043d\u0430 \u0432\u043a\u0430\u0437\u0430\u043d\u0438\u0439 email. \u0412\u043e\u043d\u0438 \u0442\u0438\u043c\u0447\u0430\u0441\u043e\u0432\u043e \u0437\u0431\u0435\u0440\u0435\u0436\u0435\u043d\u0456 \u0442\u0456\u043b\u044c\u043a\u0438 \u0432 \u0446\u044c\u043e\u043c\u0443 \u0432\u0456\u043a\u043d\u0456 \u0431\u0440\u0430\u0443\u0437\u0435\u0440\u0430.'
                : '\u0411\u0435\u0437 \u043d\u043e\u043c\u0435\u0440\u0430 \u0437\u0430\u043c\u043e\u0432\u043b\u0435\u043d\u043d\u044f, \u0442\u0435\u043b\u0435\u0444\u043e\u043d\u0443 \u0442\u0430 \u043f\u0430\u0440\u043e\u043b\u044f \u0441\u0442\u043e\u0440\u0456\u043d\u043a\u0430 \u0441\u0442\u0430\u0442\u0443\u0441\u0443 \u043d\u0435 \u0432\u0456\u0434\u043a\u0440\u0438\u0454\u0442\u044c\u0441\u044f. \u0414\u0430\u043d\u0456 \u0442\u0438\u043c\u0447\u0430\u0441\u043e\u0432\u043e \u0437\u0431\u0435\u0440\u0435\u0436\u0435\u043d\u0456 \u0442\u0456\u043b\u044c\u043a\u0438 \u0432 \u0446\u044c\u043e\u043c\u0443 \u0432\u0456\u043a\u043d\u0456 \u0431\u0440\u0430\u0443\u0437\u0435\u0440\u0430.';
        }

        orderAccess.hidden = false;
    };

    const syncHeaderState = () => {
        if (!header) {
            return;
        }

        header.classList.toggle('is-stuck', window.scrollY > 10);
    };

    const clearCloseTimer = () => {
        if (closeTimer) {
            window.clearTimeout(closeTimer);
            closeTimer = undefined;
        }
    };

    const closeAllDropdowns = () => {
        triggers.forEach((trigger) => {
            trigger.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        });

        panels.forEach((panel) => {
            panel.classList.remove('is-open');
        });
    };

    const positionConsultationPanel = () => {
        const trigger = document.querySelector('[data-dropdown-trigger="consultation"]');
        const panel = document.querySelector('[data-dropdown-panel="consultation"]');

        if (!header || !trigger || !panel || window.innerWidth <= 760) {
            if (panel) {
                panel.style.left = '';
            }

            return;
        }

        const headerRect = header.getBoundingClientRect();
        const triggerRect = trigger.getBoundingClientRect();
        const panelWidth = panel.offsetWidth || 230;
        const idealLeft = triggerRect.left - headerRect.left + ((triggerRect.width - panelWidth) / 2);
        const maxLeft = headerRect.width - panelWidth - 12;
        const nextLeft = Math.max(12, Math.min(idealLeft, maxLeft));

        panel.style.left = `${nextLeft}px`;
    };

    const openDropdown = (name) => {
        const nextPanel = document.querySelector(`[data-dropdown-panel="${name}"]`);
        const nextTrigger = document.querySelector(`[data-dropdown-trigger="${name}"]`);

        if (!nextPanel || !nextTrigger) {
            return;
        }

        closeAllDropdowns();
        nextTrigger.classList.add('is-open');
        nextTrigger.setAttribute('aria-expanded', 'true');
        nextPanel.classList.add('is-open');

        if (name === 'consultation') {
            positionConsultationPanel();
        }
    };

    const scheduleClose = () => {
        clearCloseTimer();
        closeTimer = window.setTimeout(() => {
            closeAllDropdowns();
        }, 120);
    };

    const closeMobileMenu = () => {
        if (!mobileToggle || !mobileMenu) {
            return;
        }

        mobileToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.classList.remove('is-open');
    };

    const openShareModal = (url, expiresAt = '') => {
        if (!shareModal || !shareLinkInput || !shareMeta) {
            return;
        }

        shareLinkInput.value = url;
        shareMeta.textContent = expiresAt
            ? `Посилання активне до ${new Date(expiresAt).toLocaleDateString('uk-UA')}.`
            : 'Посилання активне 30 днів.';
        shareModal.hidden = false;
        shareCopyButton?.focus();
    };

    const closeShareModal = () => {
        if (shareModal) {
            shareModal.hidden = true;
        }
    };

    const render = () => {
        const total = window.KondorCart.getTotal(stateItems);
        const isEmpty = stateItems.length === 0;

        if (emptyState) {
            emptyState.hidden = !isEmpty;
        }

        if (contentState) {
            contentState.hidden = isEmpty;
        }

        if (pageTotal) {
            pageTotal.textContent = window.KondorCart.formatPrice(total);
        }

        if (checkoutTotal) {
            checkoutTotal.textContent = window.KondorCart.formatPrice(total);
        }

        if (clearButton) {
            clearButton.disabled = isEmpty;
        }

        if (shareButton) {
            shareButton.disabled = isEmpty;
        }

        if (!itemsContainer) {
            return;
        }

        if (isEmpty) {
            itemsContainer.innerHTML = '';
            return;
        }

        itemsContainer.innerHTML = stateItems.map((item) => `
            <article class="cart-item" data-cart-item="${item.cartKey}">
                <div
                    class="cart-item__thumb cart-item__thumb--${item.tone} site-image-target${buildCoverImages[item.slug] ? ' has-site-image' : ''}"
                    data-site-image-key="build.${item.slug}.cover"
                    ${buildCoverImages[item.slug] ? `style="--site-image-url: url('${buildCoverImages[item.slug]}')"` : ''}
                    aria-hidden="true"
                ></div>

                <div class="cart-item__copy">
                    <strong class="cart-item__title">${item.name}</strong>
                    ${item.configurationSummary.length ? `<ul class="cart-item__summary">${item.configurationSummary.map((entry) => `<li>${entry}</li>`).join('')}</ul>` : ''}
                    <span class="cart-item__meta">${window.KondorCart.formatPrice(item.price)} за одиницю</span>
                    ${item.url ? `<a class="cart-item__link" href="${item.url}">Детальніше про збірку</a>` : ''}
                </div>

                <div class="cart-item__controls">
                    <div class="cart-qty" aria-label="Кількість">
                        <button type="button" data-cart-qty-minus="${item.cartKey}" aria-label="Зменшити">−</button>
                        <strong>${item.quantity}</strong>
                        <button type="button" data-cart-qty-plus="${item.cartKey}" aria-label="Збільшити">+</button>
                    </div>

                    <strong class="cart-item__line-total">${window.KondorCart.formatPrice(item.price * item.quantity)}</strong>
                    <button class="cart-remove" type="button" data-cart-remove="${item.cartKey}">Видалити</button>
                </div>
            </article>
        `).join('');
    };

    const setItems = (items, persist = mode === 'local') => {
        stateItems = normalizeItems(items);

        if (persist) {
            window.KondorCart.setCart(stateItems);
        }

        render();
    };

    triggers.forEach((trigger) => {
        const name = trigger.dataset.dropdownTrigger;
        const panel = document.querySelector(`[data-dropdown-panel="${name}"]`);

        if (!name || !panel) {
            return;
        }

        trigger.addEventListener('mouseenter', () => {
            clearCloseTimer();
            openDropdown(name);
        });

        trigger.addEventListener('mouseleave', scheduleClose);
        trigger.addEventListener('focus', () => openDropdown(name));
        trigger.addEventListener('click', () => {
            const isOpen = panel.classList.contains('is-open');

            if (isOpen) {
                closeAllDropdowns();
                return;
            }

            openDropdown(name);
        });

        panel.addEventListener('mouseenter', clearCloseTimer);
        panel.addEventListener('mouseleave', scheduleClose);
    });

    mobileToggle?.addEventListener('click', () => {
        const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';

        mobileToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
        mobileMenu?.classList.toggle('is-open', !isExpanded);
        closeAllDropdowns();
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            closeMobileMenu();
        });
    });

    itemsContainer?.addEventListener('click', (event) => {
        const target = event.target.closest('[data-cart-qty-minus], [data-cart-qty-plus], [data-cart-remove]');

        if (!target) {
            return;
        }

        const removeSlug = target.getAttribute('data-cart-remove');

        if (removeSlug) {
            setItems(stateItems.filter((item) => item.cartKey !== removeSlug));
            return;
        }

        const plusSlug = target.getAttribute('data-cart-qty-plus');

        if (plusSlug) {
            setItems(stateItems.map((item) => item.cartKey === plusSlug ? { ...item, quantity: Math.min(item.quantity + 1, 99) } : item));
            return;
        }

        const minusSlug = target.getAttribute('data-cart-qty-minus');

        if (!minusSlug) {
            return;
        }

        setItems(stateItems.flatMap((item) => {
            if (item.cartKey !== minusSlug) {
                return [item];
            }

            if (item.quantity <= 1) {
                return [];
            }

            return [{ ...item, quantity: item.quantity - 1 }];
        }));
    });

    clearButton?.addEventListener('click', () => {
        setItems([]);
    });

    importButton?.addEventListener('click', () => {
        window.KondorCart.setCart(stateItems);
        window.location.href = cartUrl;
    });

    shareButton?.addEventListener('click', async () => {
        if (!stateItems.length || !shareEndpoint) {
            return;
        }

        const defaultLabel = shareButtonLabel?.textContent ?? shareButton.textContent ?? 'Поділитися кошиком';
        shareButton.disabled = true;
        if (shareButtonLabel) {
            shareButtonLabel.textContent = 'Створюємо посилання...';
        } else {
            shareButton.textContent = 'Створюємо посилання...';
        }

        try {
            const response = await fetch(shareEndpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ items: stateItems }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.url) {
                throw new Error(payload.message || 'Не вдалося створити посилання.');
            }

            openShareModal(payload.url, payload.expires_at ?? '');
        } catch (error) {
            setFeedback(error.message || 'Не вдалося створити посилання на кошик.', 'error');
        } finally {
            shareButton.disabled = false;
            if (shareButtonLabel) {
                shareButtonLabel.textContent = defaultLabel;
            } else {
                shareButton.textContent = defaultLabel;
            }
        }
    });

    shareCopyButton?.addEventListener('click', async () => {
        if (!shareLinkInput?.value) {
            return;
        }

        try {
            await navigator.clipboard.writeText(shareLinkInput.value);
            shareCopyButton.textContent = 'Скопійовано';

            window.setTimeout(() => {
                shareCopyButton.textContent = 'Копіювати посилання';
            }, 1400);
        } catch (error) {
            shareLinkInput.select();
            document.execCommand('copy');
        }
    });

    shareCloseButtons.forEach((button) => {
        button.addEventListener('click', closeShareModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeShareModal();
            closeAllDropdowns();
            closeMobileMenu();
        }
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
            closeAllDropdowns();
        }
    });

    orderAccessCards.forEach((card) => {
        card.addEventListener('click', async () => {
            const value = `${card.dataset.copyValue ?? ''}`.trim();

            if (!value || value === emptyOrderAccessValue) {
                return;
            }

            try {
                await navigator.clipboard.writeText(value);
            } catch (error) {
                const input = document.createElement('input');
                input.value = value;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
            }

            const hint = card.querySelector('small');

            card.classList.add('is-copied');

            if (hint) {
                hint.textContent = 'Скопійовано';
            }

            window.setTimeout(() => {
                card.classList.remove('is-copied');

                if (hint) {
                    hint.textContent = 'Натисни, щоб скопіювати';
                }
            }, 1600);
        });
    });

    window.addEventListener('scroll', syncHeaderState, { passive: true });
    window.addEventListener('resize', () => {
        syncHeaderState();
        positionConsultationPanel();

        if (window.innerWidth > 1080) {
            closeMobileMenu();
        }
    });

    checkoutForm?.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!stateItems.length || !checkoutEndpoint) {
            setFeedback('Додай хоча б одну збірку в кошик.', 'error');
            return;
        }

        const formData = new FormData(checkoutForm);
        const payload = {
            customer_name: `${formData.get('customer_name') ?? ''}`.trim(),
            phone: `${formData.get('phone') ?? ''}`.trim(),
            email: `${formData.get('email') ?? ''}`.trim(),
            messenger_contact: `${formData.get('messenger_contact') ?? ''}`.trim(),
            comment: `${formData.get('comment') ?? ''}`.trim(),
            payment_method: `${formData.get('payment_method') ?? 'cash_on_delivery'}`,
            items: stateItems,
        };

        const submitButton = checkoutForm.querySelector('button[type="submit"]');
        const defaultLabel = submitButton?.textContent ?? 'Оформити замовлення';

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Оформлюємо...';
        }

        setFeedback('');

        try {
            const response = await fetch(checkoutEndpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (!response.ok) {
                const message = data.message || Object.values(data.errors ?? {}).flat()[0] || 'Не вдалося оформити замовлення.';
                throw new Error(message);
            }

            showOrderAccess(data, payload);
            persistOrderAccess(data, payload);
            setFeedback(`Замовлення ${data.order_number ?? ''} оформлено. Дані для відстеження збережено нижче.`, 'success');
            checkoutForm.reset();

            window.dispatchEvent(new CustomEvent('kondor-admin-notifications-refresh'));

            if (mode === 'local') {
                window.KondorCart.clear();
            }

            setItems([], false);
        } catch (error) {
            setFeedback(error.message || 'Не вдалося оформити замовлення.', 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = defaultLabel;
            }
        }
    });

    if (mode === 'local') {
        window.KondorCart.subscribe((items) => {
            stateItems = normalizeItems(items);
            render();
        });
    } else {
        render();
    }

    const savedOrderAccess = loadOrderAccess();

    if (savedOrderAccess) {
        showOrderAccess(savedOrderAccess, savedOrderAccess);
    }

    syncHeaderState();
    positionConsultationPanel();
})();
