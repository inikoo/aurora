<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sun Jul 19 2020 17:41:32 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

use App\Http\HttpKernel;
use Boot\Foundation\Kernel;
use App\Console\ConsoleKernel;

class LoadEnvironmentDetector extends Bootstrapper
{
    const HTTP_ENV = HttpKernel::class;
    const CONSOLE_ENV = ConsoleKernel::class;

    public function boot()
    {
        $http = class_basename(self::HTTP_ENV) === class_basename($this->kernel);
        $console = class_basename(self::CONSOLE_ENV) === class_basename($this->kernel);

        $this->app->bind('bootedViaHttp', fn () => $http);
        $this->app->bind('bootedViaConsole', fn () => $console);

        $this->app->bind(Kernel::class, fn () => $this->kernel);
    }
}