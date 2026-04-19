<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureAdminAccess;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->brandName(config('app.name'))
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::hex('#700B97'),
                'gray' => Color::Zinc,
            ])
            ->font('Figtree', 'https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('7xl')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('People & safety')
                    ->icon('heroicon-o-shield-check')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Jobs & hiring')
                    ->icon('heroicon-o-briefcase')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Community')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Specialties')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(false),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
                EnsureAdminAccess::class,
            ])
            ->authGuard('web')
            ->authPasswordBroker('users');
    }
}
