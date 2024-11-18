<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::tenanted(function () {
    Route::get('/test', function () {
        dd(Route::getRoutes());
    });
});
