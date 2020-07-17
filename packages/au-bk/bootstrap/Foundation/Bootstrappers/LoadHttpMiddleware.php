<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:32:26 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

use Boot\Foundation\Kernel;

class LoadHttpMiddleware extends Bootstrapper
{
    public function boot()
    {
        $kernel = $this->app->getContainer()->get(Kernel::class);

        $this->app->getContainer()->set('middleware', fn () => [
            'global' => $kernel->middleware,
            'api' => $kernel->middlewareGroups['api'],
            'web' => $kernel->middlewareGroups['web']
        ]);
    }
}
