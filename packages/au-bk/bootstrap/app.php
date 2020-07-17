<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:20:05 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use DI\Container;
use App\Http\HttpKernel;
use DI\Bridge\Slim\Bridge as App;

$app = App::create(new Container);

return HttpKernel::bootstrap($app)->getApplication();