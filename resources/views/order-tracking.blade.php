<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Відстеження замовлення | KondorPC</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,700,800|space-grotesk:500,700" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo e(asset('css/storefront-cart.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/cart-page.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/order-tracking.css')); ?>">
        <?php echo $__env->make('partials.theme-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body>
        <div class="cart-site-shell">
            <div class="topbar">
                <div class="container topbar__inner">
                    <div class="topbar__links">
                        <a href="<?php echo e(url('/')); ?>#about">Про нас</a>
                        <a href="#contacts">Контакти</a>
                        <a href="<?php echo e(url('/')); ?>#faq">FAQ</a>
                    </div>
                    <div class="topbar__meta">
                        <div class="topbar__contacts">
                            <a href="tel:+380633631066">+380633631066</a>
                        </div>

                        <div class="topbar__socials" aria-label="Соціальні мережі">
                            <a class="topbar__social-link" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>
                                </svg>
                            </a>

                            <a class="topbar__social-link" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 4L3 11.2L10.2 13.8L12.8 21L21 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M10.2 13.8L14.2 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.storefront-header')

            <main class="page">
                <div class="tracking-shell">
                    <main class="tracking-page">
                        <section class="tracking-hero">
                            <p class="tracking-hero__eyebrow">Відстеження замовлення</p>
                            <h1>Перевір статус замовлення за 3 дні</h1>
                            <p>Для доступу до інформації про замовлення введіть номер телефону та пароль, який ви отримали після оформлення замовлення на сайті.</p>
                        </section>

                        <section class="tracking-layout">
                            <div class="tracking-card">
                                <h2>Знайти замовлення</h2>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('credentials')): ?>
                                    <div class="tracking-alert tracking-alert--error"><?php echo e($errors->first('credentials')); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <form class="tracking-form" method="post" action="<?php echo e(route('orders.track.lookup')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <label class="tracking-field">
                                        <span>Номер замовлення</span>
                                        <input type="text" name="number" value="<?php echo e(old('number', $prefilledNumber)); ?>" placeholder="KP-260406-00001" required>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>

                                    <label class="tracking-field">
                                        <span>Номер телефону</span>
                                        <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="+380..." required>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>

                                    <label class="tracking-field">
                                        <span>Пароль</span>
                                        <input type="text" name="tracking_password" value="<?php echo e(old('tracking_password')); ?>" placeholder="AB12CD34" required>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tracking_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>

                                    <button type="submit">Показати статус</button>
                                </form>
                            </div>

                            <div class="tracking-card tracking-card--info">
                                <h2>Як знайти замовлення</h2>
                                <ul class="tracking-list">
                                    <li>поточний статус замовлення</li>
                                    <li>дату замовлення</li>
                                    <li>ТТН після відправлення</li>
                                    <li>склад замовлення та суму</li>
                                </ul>
                                <p>Якщо одного з цих параметрів немає, статус замовлення не буде відображено.</p>
                            </div>
                        </section>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order): ?>
                            <section class="tracking-result">
                                <div class="tracking-card">
                                    <div class="tracking-result__head">
                                        <div>
                                            <p class="tracking-result__eyebrow">Замовлення <?php echo e($order->number); ?></p>
                                            <h2><?php echo e($order->status_label); ?></h2>
                                        </div>
                                        <span class="tracking-badge tracking-badge--<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
                                    </div>

                                    <div class="tracking-stats">
                                        <div class="tracking-stat">
                                            <span>Дата замовлення</span>
                                            <div class="tracking-stat__value">
                                                <strong><?php echo e($order->ordered_at?->format('d.m.Y H:i') ?? '—'); ?></strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>Телефон</span>
                                            <div class="tracking-stat__value">
                                                <strong><?php echo e($order->phone); ?></strong>
                                            </div>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>ТТН</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_ttn): ?>
                                                <button
                                                    class="tracking-stat__value tracking-ttn"
                                                    type="button"
                                                    data-copy-ttn
                                                    data-ttn="<?php echo e($order->shipping_ttn); ?>"
                                                    data-default-label="<?php echo e($order->shipping_ttn); ?>"
                                                    aria-label="Скопіювати ТТН"
                                                >
                                                    <strong><?php echo e($order->shipping_ttn); ?></strong>
                                                    <span>Натисни, щоб скопіювати</span>
                                                </button>
                                            <?php else: ?>
                                                <div class="tracking-stat__value">
                                                    <strong>Ще не додано</strong>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="tracking-stat">
                                            <span>Сума</span>
                                            <div class="tracking-stat__value">
                                                <strong><?php echo e(number_format((int) $order->total_amount, 0, '', ' ')); ?> ₴</strong>
                                            </div>
                                        </div>
                                    </div>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_ttn && $order->shipment_tracking_url): ?>
                                                <div class="tracking-shipment">
                                                    <div class="tracking-shipment__meta">
                                                        <span>Відстеження посилки</span>
                                                        <p>Перевір місцезнаходження відправлення Нової пошти, але не забудьте скопіювати номер ТТН!</p>
                                                    </div>

                                            <a
                                                class="tracking-shipment__link"
                                                href="<?php echo e($order->shipment_tracking_url); ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Перевірити місцезнаходження
                                            </a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->comment): ?>
                                        <div class="tracking-note">
                                            <span>Коментар до замовлення</span>
                                            <p><?php echo e($order->comment); ?></p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="tracking-card">
                                    <h2>Склад замовлення</h2>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->items->isEmpty()): ?>
                                        <p class="tracking-empty">Менеджер ще не додав позиції до цього замовлення.</p>
                                    <?php else: ?>
                                        <div class="tracking-items">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <article class="tracking-item">
                                                    <div>
                                                        <strong><?php echo e($item->build_name); ?></strong>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item->meta['configuration_summary'])): ?>
                                                            <ul>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = (array) $item->meta['configuration_summary']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                    <li><?php echo e($line); ?></li>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                            </ul>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                    <div class="tracking-item__meta">
                                                        <span><?php echo e($item->quantity); ?> шт.</span>
                                                        <strong><?php echo e(number_format((int) $item->line_total, 0, '', ' ')); ?> ₴</strong>
                                                    </div>
                                                </article>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </section>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </main>
                </div>
            </main>

            <footer class="footer" id="contacts">
                <div class="container">
                    <div class="footer__grid">
                        <div class="footer__brand">
                            <div class="footer__logo">
                                <span class="footer__brand-name">KondorPC</span>
                                <span class="footer__brand-sub">Твоя база геймінгу</span>
                            </div>
                            <div class="footer__contacts">
                                <a href="tel:+380633631066">+380 63 363 10 66</a>
                                <a href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer">@kondor_channeI</a>
                            </div>
                            <div class="footer__socials">
                                <a class="footer__social" href="https://www.instagram.com/kondor_pc/" target="_blank" rel="noreferrer" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.8" r="1.1" fill="currentColor"/></svg></a>
                                <a class="footer__social" href="https://t.me/kondor_channeI" target="_blank" rel="noreferrer" aria-label="Telegram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 4.8L3.9 11.1L8.8 12.9L10.6 18L20.2 4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8.8 12.9L13.9 8.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></a>
                                <a class="footer__social" href="tel:+380633631066" aria-label="Подзвонити"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8.2 5.8L10.9 8.5C11.3 8.9 11.4 9.5 11.1 10L10.1 11.8C10.9 13.5 12.3 14.9 14 15.8L15.8 14.8C16.3 14.5 16.9 14.6 17.3 15L20 17.7C20.5 18.2 20.5 19 20 19.5L18.8 20.7C18.1 21.4 17.1 21.7 16.1 21.5C9.8 20.1 4.9 15.2 3.5 8.9C3.3 7.9 3.6 6.9 4.3 6.2L5.5 5C6 4.5 6.8 4.5 7.3 5L8.2 5.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                            </div>
                        </div>
                        <div class="footer__column footer__column--about">
                            <h2 class="footer__title">Про нас</h2>
                            <nav class="footer__nav">
                                <a href="<?php echo e(url('/')); ?>#about">Що таке KondorPC</a>
                                <a href="#contacts">Контакти</a>
                                <a href="#contacts">Доставка</a>
                                <a href="#contacts">Оплата</a>
                                <a href="#contacts">Повернення та обмін</a>
                            </nav>
                        </div>
                        <div class="footer__column">
                            <h2 class="footer__title">Основне</h2>
                            <nav class="footer__nav">
                                <a href="<?php echo e(url('/')); ?>">Головна</a>
                                <a href="<?php echo e(route('catalog')); ?>">Каталог</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="container footer__bottom-inner"><?php echo e(date('Y')); ?> KondorPC | Всі права захищені</div>
                </div>
            </footer>
        </div>

        <script src="<?php echo e(asset('js/storefront-cart.js')); ?>?v=<?php echo e(filemtime(public_path('js/storefront-cart.js'))); ?>"></script>
        <?php echo $__env->make('partials.theme-toggle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <script>
            document.querySelectorAll('[data-copy-ttn]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const ttn = button.dataset.ttn ?? '';
                    const defaultLabel = button.dataset.defaultLabel ?? button.textContent.trim();

                    if (!ttn) {
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(ttn);
                        button.innerHTML = '<strong>' + ttn + '</strong><span>ТТН скопійовано</span>';
                    } catch (error) {
                        const input = document.createElement('input');
                        input.value = ttn;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        button.innerHTML = '<strong>' + ttn + '</strong><span>ТТН скопійовано</span>';
                    }

                    window.setTimeout(() => {
                        button.innerHTML = '<strong>' + defaultLabel + '</strong><span>Натисни, щоб скопіювати</span>';
                    }, 1600);
                });
            });
        </script>
    </body>
</html>
<?php /**PATH D:\OSPanel\home\kondor\resources\views/order-tracking.blade.php ENDPATH**/ ?>
