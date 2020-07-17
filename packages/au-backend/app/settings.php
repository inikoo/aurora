<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:53:07 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use \Psr\Container\ContainerInterface;

return function ($container) {
    $container->set('settings', function () {
        return [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true
        ];
    });
};
