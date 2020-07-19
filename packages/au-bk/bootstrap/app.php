<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:20:05 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use DI\Container;
use App\Http\HttpKernel;
use App\Console\ConsoleKernel;
use Boot\Foundation\AppFactoryBridge as App;

$app = App::create(new Container);

$http_kernel = new HttpKernel($app);
$console_kernel = new ConsoleKernel($app);

$app->bind(HttpKernel::class, $http_kernel);
$app->bind(ConsoleKernel::class, $console_kernel);

return $app;
