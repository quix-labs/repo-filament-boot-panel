<?php
declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Sprout\Contracts\BootableServiceOverride;
use Sprout\Contracts\Tenancy;
use Sprout\Contracts\Tenant;
use Sprout\Sprout;

class LivewireOverride implements BootableServiceOverride
{
    public function boot(Application $app, Sprout $sprout): void
    {
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/{tenants_path}/livewire/update', $handle)
                        ->middleware(
                            'web',
                            'sprout.tenanted'
                        );
        });
    }

    public function setup(Tenancy $tenancy, Tenant $tenant): void
    {
        // TODO: Implement setup() method.
    }

    public function cleanup(Tenancy $tenancy, Tenant $tenant): void
    {
        // TODO: Implement cleanup() method.
    }
}
