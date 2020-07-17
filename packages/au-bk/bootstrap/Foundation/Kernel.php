<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:22:18 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation;

use Slim\App;

abstract class Kernel
{
    public App $app;

    /**
     * Register application Bootstrap Loaders
     *
     * @var array
     */
    public array $bootstrap = [];

    public function __construct(App &$app)
    {
        $this->app = $app;

        $this->app->getContainer()->set(self::class, $this);

        Bootstrappers\Bootstrapper::setup($this->app, $this->bootstrap);
    }

    public static function bootstrap(App &$app)
    {
        return new static($app);
    }

    public function getApplication() : App
    {
        return $this->app;
    }

}