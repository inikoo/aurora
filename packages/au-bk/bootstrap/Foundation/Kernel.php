<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:22:18 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation;

abstract class Kernel
{
    public $app;

    /**
     * Register application Bootstrap Loaders
     *
     * @var array
     */
    public array $bootstrappers = [];


    public function __construct(&$app)
    {
        
        $this->app = $app;
    }

    public function bootstrapApplication()
    {
        
        $app = $this->getApp();
        $kernel = $this->getKernel();
        $bootstrappers = $this->bootstrappers;

        Bootstrappers\Bootstrapper::setup($app, $kernel, $bootstrappers);
    }

    public function getKernel()
    {
        return $this;
    }

    public function getApp()
    {
        return $this->app;
    }

}
