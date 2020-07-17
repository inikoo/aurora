<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 16:30:43 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use App\Http\Controllers\HomeController;

return function ($app) {
    $app->get('/', [HomeController::class, 'index']);
};
