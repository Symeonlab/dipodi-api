<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName(config('app.name', 'Dipoddi'))
            ->favicon(asset('favicon.ico'))

            // Auth configuration
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            // ->emailVerification() // Enable when User model implements MustVerifyEmail

            // Appearance
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Rose,
                'gray' => Color::Zinc,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->darkMode(condition: true)
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce('500ms')

            // Navigation groups
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.dashboard'))
                    ->icon('heroicon-o-home')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.user_management'))
                    ->icon('heroicon-o-users')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.workout_training'))
                    ->icon('heroicon-o-fire')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.health_wellness'))
                    ->icon('heroicon-o-heart')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.content_management'))
                    ->icon('heroicon-o-document-text')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label(fn (): string => __('filament.nav.settings_config'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label(fn (): string => __('admin.nav.help_documentation'))
                    ->icon('heroicon-o-book-open')
                    ->collapsed(true),
            ])

            // UX enhancements
            ->spa()
            ->unsavedChangesAlerts()
            ->revealablePasswords()

            // Database notifications
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')

            // Auth guard
            ->authGuard('web')

            // Resources, Pages, Widgets discovery
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')

            // Middleware (with rate limiting for brute-force protection)
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
                'throttle:60,1',
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
