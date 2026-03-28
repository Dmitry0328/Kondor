<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\MenuItem;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->databaseNotifications()
            ->databaseNotificationsPolling('5s')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationItems([
                NavigationItem::make('На сайт')
                    ->group('Storefront')
                    ->icon(Heroicon::OutlinedHome)
                    ->url(url('/'))
                    ->sort(999),
            ])
            ->userMenuItems([
                'site' => MenuItem::make()
                    ->label('На сайт')
                    ->icon(Heroicon::OutlinedHome)
                    ->url(url('/'))
                    ->sort(-10),
            ])
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => $this->renderAdminSiteNotifications(),
            )
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    protected function renderAdminSiteNotifications(): string
    {
        $user = auth()->user();

        if (! $user || ! $user->is_admin) {
            return '';
        }

        $feedUrl = e(route('admin.notifications.feed'));
        $scriptUrl = e(asset('js/admin-site-notifications.js'));

        return <<<HTML
<style>
    .admin-site-toast-stack { position: fixed; left: 18px; bottom: max(18px, env(safe-area-inset-bottom, 0px) + 12px); z-index: 10050; display: grid; gap: 12px; width: min(372px, calc(100vw - 28px)); max-height: calc(100vh - 28px); overflow-y: auto; pointer-events: none; }
    .admin-site-toast { pointer-events: auto; display: grid; gap: 10px; padding: 14px 16px 14px; border: 1px solid rgba(87, 99, 120, .18); border-radius: 18px; background: rgba(17, 22, 30, .94); color: #f6f8fb; box-shadow: 0 18px 42px rgba(10, 14, 22, .34); backdrop-filter: blur(14px); }
    .admin-site-toast__top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .admin-site-toast__eyebrow { display: inline-flex; align-items: center; gap: 8px; color: #b7c1d0; font-size: 11px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .admin-site-toast__eyebrow::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #7f22ff; box-shadow: 0 0 0 6px rgba(127, 34, 255, .16); }
    .admin-site-toast__close { flex: none; width: 30px; height: 30px; border: 1px solid rgba(255, 255, 255, .12); border-radius: 999px; background: rgba(255, 255, 255, .04); color: #d8dfeb; font-size: 20px; line-height: 1; cursor: pointer; }
    .admin-site-toast__title { margin: 0; color: #fff; font-size: 18px; font-weight: 800; line-height: 1.2; }
    .admin-site-toast__body { margin: 0; color: #cfd7e2; font-size: 14px; line-height: 1.45; }
    .admin-site-toast__time { color: #94a0b1; font-size: 12px; font-weight: 700; }
    .admin-site-toast__actions { display: flex; align-items: center; gap: 10px; }
    .admin-site-toast__link { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 16px; border-radius: 12px; background: linear-gradient(180deg, #8f2fff, #7420d3); color: #fff; font-size: 14px; font-weight: 800; text-decoration: none; box-shadow: 0 12px 24px rgba(116, 32, 211, .22); }
    .admin-site-toast__link:hover { background: linear-gradient(180deg, #9b41ff, #7f22ff); }
    @media (max-width: 640px) {
        .admin-site-toast-stack { left: 12px; right: 12px; bottom: max(12px, env(safe-area-inset-bottom, 0px) + 8px); width: auto; max-height: calc(100vh - 20px); }
        .admin-site-toast { gap: 9px; padding: 13px 14px; border-radius: 16px; }
        .admin-site-toast__title { font-size: 16px; }
        .admin-site-toast__body { font-size: 13px; }
        .admin-site-toast__actions { display: grid; gap: 8px; }
        .admin-site-toast__link { width: 100%; }
    }
</style>
<div class="admin-site-toast-stack" data-admin-site-notifications data-feed-url="{$feedUrl}"></div>
<script src="{$scriptUrl}"></script>
HTML;
    }
}
