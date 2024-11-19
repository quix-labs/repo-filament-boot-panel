<?php

namespace App;

use App\SproutListeners\PrepareSproutForNextOperation;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Laravel\Octane\Events\RequestHandled;
use Sprout\Contracts\BootableServiceOverride;
use Sprout\Contracts\DeferrableServiceOverride;
use Sprout\Contracts\Tenancy;
use Sprout\Contracts\Tenant;
use Sprout\Sprout;

/**
 * Session Override
 *
 * This class provides the override/multitenancy extension/features for Laravels
 * session service.
 *
 * @package Overrides
 */
final class OctaneOverride implements BootableServiceOverride, DeferrableServiceOverride
{
    public function boot(Application $app, Sprout $sprout): void
    {
        $app->singleton(PrepareSproutForNextOperation::class);

        // Disable session re-injection for Laravel Octane, in fact we need to remove all tenant-aware core service
        $config = $app->make('config');
        $config->set('octane.warm', array_diff($config->array('octane.warm', []), [
            'session',
            'session.store',
        ]));

    }

    public static function service(): string
    {
        return 'octane';
    }

    public function setup(Tenancy $tenancy, Tenant $tenant): void
    {
        // Registering octane listeners
        Event::listen(RequestHandled::class, PrepareSproutForNextOperation::class);
    }

    public function cleanup(Tenancy $tenancy, Tenant $tenant): void
    {
        // TODO REMOVE event (but optional)
    }
}
