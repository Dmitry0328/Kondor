(function () {
    const tracker = document.querySelector('[data-online-visitors-tracker]');

    if (!tracker) {
        return;
    }

    const endpoint = tracker.dataset.endpoint;
    const csrfToken = tracker.dataset.csrfToken;
    const context = tracker.dataset.context || 'storefront';
    const displays = Array.from(document.querySelectorAll('[data-online-visitors-display]'));
    const countNodes = Array.from(document.querySelectorAll('[data-online-visitors-count]'));
    let inFlight = false;

    if (!endpoint || !csrfToken) {
        return;
    }

    const render = (count) => {
        const safeCount = Number.isFinite(count) ? Math.max(0, count) : 0;

        countNodes.forEach((node) => {
            node.textContent = String(safeCount);
        });

        displays.forEach((display) => {
            const template = display.dataset.onlineVisitorsLabelTemplate || 'Онлайн: :count';
            const label = template.replace(':count', String(safeCount));

            display.setAttribute('aria-label', label);

            const labelNode = display.querySelector('[data-online-visitors-label]');

            if (labelNode) {
                labelNode.textContent = label;
            }
        });
    };

    const ping = () => {
        if (inFlight) {
            return;
        }

        inFlight = true;

        window.fetch(endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ context }),
            keepalive: true,
        })
            .then((response) => (response.ok ? response.json() : null))
            .then((payload) => {
                if (!payload || typeof payload.count !== 'number') {
                    return;
                }

                render(payload.count);
            })
            .catch(() => {
                // Ignore heartbeat issues and keep the last visible count.
            })
            .finally(() => {
                inFlight = false;
            });
    };

    ping();
    window.setInterval(ping, 60000);
    window.addEventListener('pageshow', ping);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            ping();
        }
    });
})();
