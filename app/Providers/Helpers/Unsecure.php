<?php

namespace App\Providers\Helpers;

use Illuminate\Contracts\Foundation\Application;

class Unsecure
{
    public static function reorderProviderBoot(Application &$app, string $provider, string $target = null, string $position = "after"): void
    {
        $r = new \ReflectionProperty($app, 'serviceProviders');
        $providers = $r->getValue($app);

        if (!array_key_exists($provider, $providers) || !array_key_exists($target, $providers)) {
            return;
        }

        // Extract element from array
        $element = [$provider => $providers[$provider]];
        unset($providers[$provider]);

        // Regenerate ordered array
        $pos = array_search($target, array_keys($providers));

        if ($position === "after") {
            $providers = array_merge(
                array_slice($providers, 0, $pos + 1, true),
                $element,
                array_slice($providers, $pos - 1, null, true)
            );
        } elseif ($position === "before") {
            $providers = array_merge(
                array_slice($providers, 0, $pos, true),
                $element,
                array_slice($providers, $pos, null, true)
            );
        } else {
            throw new \RuntimeException("Unsupported position '$position'");
        }

        $r->setValue($app, $providers);
    }
}
