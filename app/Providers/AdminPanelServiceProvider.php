<?php

namespace App\Providers;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\View;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->viteTheme('resources/css/filament/admin/theme.scss')
            ->brandName('Biometric Tracker CMS')
            // ->brandLogo(asset('assets/img/cms_logo.png'))
            // ->darkModeBrandLogo(asset('assets/img/cms_logo-dark.png'))
            ->favicon(url('favicon.ico'))
            ->brandLogoHeight('2rem')
            ->id('admin')
            ->default()
            ->login()
            ->breadcrumbs(false)
            ->passwordReset(null, null)
            ->authPasswordBroker('admins')
            ->defaultThemeMode(ThemeMode::Light)
            ->databaseTransactions()

            // Resource Folders
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class])
            ->authGuard('cms_admin')
            ->navigationItems([
                NavigationItem::make('Logout')
                    ->url("javascript:document.querySelector('#logout').submit();")
                    ->group('System')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->sort(100),
            ])
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_END, function () {
                return View::make('filament.logout');
            })
            ->guessUsingSubdomain();
        // ->discoverFilamentFromModules() // Commented out - method doesn't exist in Filament v4
    }
}
