<?php
declare(strict_types=1);

namespace App;

use Filament\Http\Controllers\Auth\EmailVerificationController;
use Filament\Http\Controllers\Auth\LogoutController;
use Filament\Http\Controllers\RedirectToHomeController;
use Filament\Http\Controllers\RedirectToTenantController;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Sprout\Contracts\DeferrableServiceOverride;
use Sprout\Contracts\ServiceOverride;
use Sprout\Contracts\Tenancy;
use Sprout\Contracts\Tenant;

class FilamentOverride implements ServiceOverride, DeferrableServiceOverride
{
    public static function service(): string
    {
        return PanelRegistry::class;
    }

    public function setup(Tenancy $tenancy, Tenant $tenant): void
    {
        /** @var Panel|null $panel */
        $panel = app(PanelRegistry::class)->get('tenant-panel');

        if ($panel !== null) {
            $this->panel($panel, $tenant);
        }

        dd(session());
    }

    public function cleanup(Tenancy $tenancy, Tenant $tenant): void
    {
        // TODO: Implement cleanup() method.
    }

    public function panel(Panel $panel, Models\Tenant $tenant): Panel
    {
        return $panel
            ->colors([
                'primary' => $tenant['color'],
            ]);
    }
}
