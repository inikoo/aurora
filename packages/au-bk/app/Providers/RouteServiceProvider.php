<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 18:19:39 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Providers;

use App\Support\RouteGroup;
use App\Support\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::setup($this->app);

        $this->bind(RouteGroup::class, fn () => new RouteGroup($this->app));
    }

    public function boot()
    {
        $this->apiRouteGroup()->register();
        $this->webRouteGroup()->register();
    }

    public function apiRouteGroup() : RouteGroup
    {
        $get = routes_path('api.php');
        $add = $this->resolve('middleware');
        $api = $this->resolve(RouteGroup::class);

        return $api->routes($get)->prefix('/api')->middleware([
            ...$add['api'],
            ...$add['global']
        ]);
    }

    public function webRouteGroup() : RouteGroup
    {
        $get = routes_path('web.php');
        $add = $this->resolve('middleware');
        $web = $this->resolve(RouteGroup::class);

        return $web->routes($get)->prefix('')->middleware([
            ...$add['web'],
            ...$add['global']
        ]);
    }
}