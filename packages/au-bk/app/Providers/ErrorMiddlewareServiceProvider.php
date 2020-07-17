<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 18:22:01 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/


namespace App\Providers;

use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;


class ErrorMiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (env('APP_DEBUG', false)) {
            $this->app->add(new WhoopsMiddleware());
        }
    }

    public function boot()
    {
        //
    }
}
