<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 16:09:14 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use Slim\App;

return function ($app) {

print 'caca';

    $app->addErrorMiddleware(
        config('middleware.error_details.displayErrorDetails'),
        config('middleware.error_details.logErrors'),
        config('middleware.error_details.logErrorDetails'),

    );
};
