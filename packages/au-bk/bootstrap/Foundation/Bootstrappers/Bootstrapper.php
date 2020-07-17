<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:27:56 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/


namespace Boot\Foundation\Bootstrappers;

use Slim\App;

class Bootstrapper
{
    public App $app;

    final public function __construct(App &$app)
    {
        $this->app = $app;
    }

    final public static function setup(App &$app, array $loaders)
    {
        $loaders = array_map(fn ($loader) => new $loader($app), $loaders);

        array_walk($loaders, fn (Bootstrapper $boot) => $boot->beforeBoot());
        array_walk($loaders, fn (Bootstrapper $boot) => $boot->boot());
        array_walk($loaders, fn (Bootstrapper $boot) => $boot->afterBoot());
    }

    public function beforeBoot()
    {
        //
    }

    public function boot()
    {
        //
    }

    public function afterBoot()
    {
        //
    }
}