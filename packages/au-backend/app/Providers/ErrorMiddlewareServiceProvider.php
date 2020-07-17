<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 18:22:01 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Providers;

class ErrorMiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->addErrorMiddleware(
            config('middleware.error_details.displayErrorDetails'),
            config('middleware.error_details.logErrors'),
            config('middleware.error_details.logErrorDetails')
        );
    }

    public function boot()
    {
        //
    }
}