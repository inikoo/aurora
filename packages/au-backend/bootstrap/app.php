<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:20:05 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

$app = SlimAppFactory::create(new Container);

$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

$routers = require __DIR__ . '/../app/routers.php';
$routers($app);

return $app;
