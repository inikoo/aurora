<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:43:31 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/


/**
 * Autoload global dependencies and allow for auto-loading local dependencies via use
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Boot up application, AKA Turn the lights on.
 */
$app = require_once __DIR__ . '/../bootstrap/app.php';

/**
 * Resolve Http Kernel
 */

$kernel = $app->resolve(App\Http\HttpKernel::class);

/**
 * Bootstrap Our Http Application
 */

$kernel->bootstrapApplication();

/**
 * Passing our Request through the app
 */
$app->run();
