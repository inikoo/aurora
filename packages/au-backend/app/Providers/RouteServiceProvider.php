<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 18:19:39 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Providers;

use App\Support\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::setup($this->app);
    }

    public function boot()
    {
        $app = Route::$app;

        require routes_path('web.php');
    }
}