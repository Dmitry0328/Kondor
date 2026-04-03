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

            setFeedback(`Замовлення ${data.order_number ?? ''} оформлено. Ми зв'яжемося з тобою найближчим часом.`, 'success');
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

    syncHeaderState();
    positionConsultationPanel();
})();
