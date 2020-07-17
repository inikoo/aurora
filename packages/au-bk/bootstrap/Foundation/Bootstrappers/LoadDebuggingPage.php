<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:28:48 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

class LoadDebuggingPage extends Bootstrapper
{
    public function boot()
    {
        if (env('APP_DEBUG', false)) {
            $this->app->add(new WhoopsMiddleware());
        }
    }
}
