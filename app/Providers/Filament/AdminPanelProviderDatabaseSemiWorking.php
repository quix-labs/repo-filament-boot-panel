<?php

namespace App\Providers\Filament;

use App\Models\Tenant;
use App\Models\User;
use Carbon\Laravel\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\PanelRegistry;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProviderDatabaseSemiWorking extends ServiceProvider
{
    // Instead of using register function to handle my database tenants, I use boot function

    public function boot(): void
    {
        /**
         * See 2024_11_18_094503_create_tenants_table migration
         *
         *
         * Illuminate\Database\DatabaseServiceProvider has booted, I don't have database error on query
         *
         * But filamentPHP don't register the panel because Filament\FilamentServiceProvider is already booted
         * and this facade function don't trigger the registering after:
         *
         * static::getFacadeApplication()->resolving(  <- resolving already passed
         *     PanelRegistry::class,
         *     fn (PanelRegistry $registry) => $registry->register(value($panel)),
         *  );
         */

        $tenants = Tenant::query()->get()->toArray();
        foreach ($tenants as $tenant) {
            Filament::registerPanel(
                fn(): Panel => $this->panel(Panel::make(), $tenant->toArray()),
            );
        }
    }

    public function panel(Panel $panel, array $tenant): Panel
    {
        return $panel
            ->default()
            ->id($tenant['id'])
            ->path($tenant['path'])
            ->login()
            ->colors([
                'primary' => $tenant['color'],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}
