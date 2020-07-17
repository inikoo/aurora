<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:20:05 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container;
$setttings = require __DIR__ . '/../app/settings.php';
$setttings($container);

$app=SlimAppFactory::create($container);

$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

$routers = require __DIR__ . '/../app/routers.php';
$routers($app);

$app->run();
