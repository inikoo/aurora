<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:27:56 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

class Bootstrapper
{
    public $app;
    public $kernel;

    final public function __construct(&$app, &$kernel)
    {
        $this->app = $app;
        $this->kernel = $kernel;
    }

    final public static function setup(&$app, &$kernel, array $bootstrappers)
    {
        collect($bootstrappers)
            ->map(fn ($bootstrapper) => new $bootstrapper($app, $kernel))
            ->each(fn (Bootstrapper $bootstrapper) => $bootstrapper->beforeBoot())
            ->each(fn (Bootstrapper $bootstrapper) => $bootstrapper->boot())
            ->each(fn (Bootstrapper $bootstrapper) => $bootstrapper->afterBoot());
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
