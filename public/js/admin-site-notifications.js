(function () {
    const stack = document.querySelector('[data-admin-site-notifications]');

    if (!stack) {
        return;
    }

    const feedUrl = stack.dataset.feedUrl ?? '';
    const dismissedKey = 'kondor-admin-site-notifications-dismissed-v2';
    const rendered = new Set();
    const dismissed = new Set((() => {
        try {
            const parsed = JSON.parse(window.localStorage.getItem(dismissedKey) ?? '[]');

            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    })());

    const persistDismissed = () => {
        try {
            window.localStorage.setItem(dismissedKey, JSON.stringify(Array.from(dismissed).slice(-100)));
        } catch (error) {
            // ignore storage failures
        }
    };

    const dismiss = (id) => {
        if (!id) {
            return;
        }

        dismissed.add(id);
        persistDismissed();
    };

    const createToast = (notification) => {
        const toast = document.createElement('article');
        toast.className = 'admin-site-toast';
        toast.dataset.notificationId = notification.id || '';
        toast.innerHTML = `
            <div class="admin-site-toast__top">
                <div>
                    <span class="admin-site-toast__eyebrow">Сповіщення для адміна</span>
                </div>
                <button class="admin-site-toast__close" type="button" aria-label="Закрити">×</button>
            </div>
            <h3 class="admin-site-toast__title"></h3>
            <p class="admin-site-toast__body"></p>
            <div class="admin-site-toast__actions">
                <a class="admin-site-toast__link" href="#" target="_blank" rel="noreferrer">Відкрити</a>
                <span class="admin-site-toast__time"></span>
            </div>
        `;

        toast.querySelector('.admin-site-toast__title').textContent = notification.title || 'Нове сповіщення';
        toast.querySelector('.admin-site-toast__body').textContent = notification.body || '';
        toast.querySelector('.admin-site-toast__time').textContent = notification.created_at || 'щойно';

        const link = toast.querySelector('.admin-site-toast__link');

        if (notification.url) {
            link.href = notification.url;
        } else {
            link.removeAttribute('href');
            link.style.display = 'none';
        }

        const remove = () => {
            if (notification.id) {
                rendered.delete(notification.id);
            }

            toast.remove();
        };

        toast.querySelector('.admin-site-toast__close').addEventListener('click', () => {
            dismiss(notification.id);
            remove();
        });

        link.addEventListener('click', () => {
            dismiss(notification.id);
            remove();
        });

        return toast;
    };

    const showNotification = (notification) => {
        if (!notification || (!notification.id && !notification.title)) {
            return;
        }

        if (notification.id && (dismissed.has(notification.id) || rendered.has(notification.id))) {
            return;
        }

        if (notification.id) {
            rendered.add(notification.id);
        }

        stack.prepend(createToast(notification));
    };

    const loadNotifications = async () => {
        if (!feedUrl) {
            return;
        }

        try {
            const response = await fetch(feedUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();

            (payload.notifications ?? []).forEach(showNotification);
        } catch (error) {
            // silent poll fail
        }
    };

    window.addEventListener('kondor-admin-notification', (event) => {
        showNotification(event.detail ?? {});
    });

    window.addEventListener('kondor-admin-notifications-refresh', () => {
        loadNotifications();
    });

    loadNotifications();
    window.setInterval(loadNotifications, 5000);
})();
