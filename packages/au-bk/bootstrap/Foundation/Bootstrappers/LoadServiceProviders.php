<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:33:50 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

use App\Providers\ServiceProvider;

class LoadServiceProviders extends Bootstrapper
{
    public function boot()
    {
        $app = $this->app;
        $providers = config('app.providers');

        if ($app->bootedViaHttpRequest()) {
            $providers = [...$providers, \App\Providers\RouteServiceProvider::class];
        } else if ($app->bootedViaConsole()) {
            $providers = [...$providers, \App\Providers\ConsoleServiceProvider::class];
        }

        ServiceProvider::setup($app, $providers);
    }
}
