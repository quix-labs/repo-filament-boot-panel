<?php

namespace App\SproutListeners;

use Laravel\Octane\Events\RequestHandled;
use Sprout\Sprout;

class PrepareSproutForNextOperation
{
    public function handle(RequestHandled $event): void
    {
        if (!$event->sandbox->resolved("sprout")) {
            return;
        }

        /** @var Sprout $sprout */
        $sprout = $event->sandbox->make("sprout");

        if (!$sprout->hasCurrentTenancy()) {
            return;
        }

        foreach ($sprout->getAllCurrentTenancies() as $currentTenancy) {
            if ($tenant = $currentTenancy->tenant()) {
                $sprout->cleanupOverrides($currentTenancy, $tenant);
            }
        }

        $sprout->tenancies()->flushResolved();
    }
}
