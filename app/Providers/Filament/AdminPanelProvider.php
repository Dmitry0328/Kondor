<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Builds\Pages\ListBuilds;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\MenuItem;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\View\TablesRenderHook;
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
            ->homeUrl(fn (): string => \App\Filament\Resources\Builds\BuildResource::getUrl('index', isAbsolute: false))
            ->breadcrumbs()
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
            ->renderHook(
                TablesRenderHook::SELECTION_INDICATOR_ACTIONS_BEFORE,
                fn (): string => <<<'HTML'
<button
    type="button"
    wire:click="mountTableBulkAction('delete')"
    style="
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        min-height:38px;
        padding:0 14px;
        border:1px solid rgba(239,68,68,.2);
        border-radius:12px;
        background:#fff5f5;
        color:#dc2626;
        font-size:14px;
        font-weight:700;
        cursor:pointer;
    "
>
    <span aria-hidden="true">🗑</span>
    <span>Видалити вибране</span>
</button>
HTML,
                scopes: [ListBuilds::class],
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
    .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions,
    .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions,
    .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"],
    .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] {
        position: fixed !important;
        inset: auto auto calc(env(safe-area-inset-bottom, 0px) + 10px) 50% !important;
        top: auto !important;
        right: auto !important;
        bottom: calc(env(safe-area-inset-bottom, 0px) + 10px) !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        z-index: 10040 !important;
        display: inline-block !important;
        width: max-content !important;
        max-width: calc(100vw - 36px) !important;
        height: auto !important;
        max-height: none !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        backdrop-filter: none !important;
    }
    .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions .fi-ac,
    .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions .fi-ac,
    .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"] .fi-ac,
    .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] .fi-ac {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
        gap: 8px;
        width: max-content !important;
        max-width: calc(100vw - 36px);
        margin: 0 auto;
        padding: 7px 8px;
        border: 1px solid rgba(203, 214, 229, .92);
        border-radius: 16px;
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .12);
        backdrop-filter: blur(18px);
    }
    .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions .fi-btn,
    .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions .fi-btn,
    .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"] .fi-btn,
    .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] .fi-btn {
        width: auto;
        flex: 0 0 auto;
        justify-content: center;
        border-radius: 999px;
        min-height: 36px;
        padding-inline: 14px;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 6px 12px rgba(24, 32, 42, .05);
    }
    @media (max-width: 640px) {
        .admin-site-toast-stack { left: 12px; right: 12px; bottom: max(12px, env(safe-area-inset-bottom, 0px) + 8px); width: auto; max-height: calc(100vh - 20px); }
        .admin-site-toast { gap: 9px; padding: 13px 14px; border-radius: 16px; }
        .admin-site-toast__title { font-size: 16px; }
        .admin-site-toast__body { font-size: 13px; }
        .admin-site-toast__actions { display: grid; gap: 8px; }
        .admin-site-toast__link { width: 100%; }
        .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions,
        .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions,
        .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"],
        .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] {
            inset: auto 10px calc(env(safe-area-inset-bottom, 0px) + 8px) 10px !important;
            top: auto !important;
            right: 10px !important;
            bottom: calc(env(safe-area-inset-bottom, 0px) + 8px) !important;
            left: 10px !important;
            transform: none !important;
            width: auto !important;
        }
        .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions .fi-ac,
        .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions .fi-ac,
        .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"] .fi-ac,
        .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] .fi-ac {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            flex-wrap: nowrap;
            width: auto;
            max-width: 100%;
            padding: 6px 7px;
            border-radius: 14px;
            padding-bottom: 2px;
        }
        .fi-page.fi-resource-builds.fi-resource-edit-record-page #content\\.form-actions .fi-btn,
        .fi-page.fi-resource-builds.fi-resource-create-record-page #content\\.form-actions .fi-btn,
        .fi-page.fi-resource-builds.fi-resource-edit-record-page [wire\\:key$="form-actions"] .fi-btn,
        .fi-page.fi-resource-builds.fi-resource-create-record-page [wire\\:key$="form-actions"] .fi-btn {
            width: auto;
            white-space: nowrap;
            min-height: 34px;
            padding-inline: 12px;
            font-size: 12px;
        }
    }
</style>
<div class="admin-site-toast-stack" data-admin-site-notifications data-feed-url="{$feedUrl}"></div>
<script src="{$scriptUrl}"></script>
<script>
    window.addEventListener('open-admin-build-preview', (event) => {
        const url = event.detail?.url;

        if (! url) {
            return;
        }

        window.open(url, '_blank', 'noopener');
    });

    const applyBuildFormActionsPosition = () => {
        const page = document.querySelector('.fi-page.fi-resource-builds.fi-resource-edit-record-page, .fi-page.fi-resource-builds.fi-resource-create-record-page');

        if (! page) {
            return;
        }

        const formActions =
            page.querySelector('#content\\\\.form-actions') ??
            page.querySelector('[id="content.form-actions"]') ??
            page.querySelector('[wire\\:key$="form-actions"]');

        if (! formActions) {
            return;
        }

        formActions.style.setProperty('position', 'fixed', 'important');
        formActions.style.setProperty('top', 'auto', 'important');
        formActions.style.setProperty('right', 'auto', 'important');
        formActions.style.setProperty('bottom', '10px', 'important');
        formActions.style.setProperty('left', '50%', 'important');
        formActions.style.setProperty('transform', 'translateX(-50%)', 'important');
        formActions.style.setProperty('z-index', '10040', 'important');
        formActions.style.setProperty('display', 'inline-block', 'important');
        formActions.style.setProperty('width', 'max-content', 'important');
        formActions.style.setProperty('max-width', 'calc(100vw - 36px)', 'important');
        formActions.style.setProperty('height', 'auto', 'important');
        formActions.style.setProperty('max-height', 'none', 'important');
        formActions.style.setProperty('background', 'transparent', 'important');
        formActions.style.setProperty('box-shadow', 'none', 'important');

        if (window.innerWidth <= 640) {
            formActions.style.setProperty('left', '10px', 'important');
            formActions.style.setProperty('right', '10px', 'important');
            formActions.style.setProperty('bottom', '8px', 'important');
            formActions.style.setProperty('transform', 'none', 'important');
            formActions.style.setProperty('display', 'block', 'important');
            formActions.style.setProperty('width', 'auto', 'important');
            formActions.style.setProperty('max-width', 'none', 'important');
        }
    };

    window.addEventListener('DOMContentLoaded', applyBuildFormActionsPosition);
    window.addEventListener('load', applyBuildFormActionsPosition);
    window.addEventListener('resize', applyBuildFormActionsPosition);
    setInterval(applyBuildFormActionsPosition, 900);
</script>
HTML;
    }
}
