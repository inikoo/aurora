<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 15:20:05 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

use DI\Container;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container;
$setttings = require __DIR__ . '/../app/settings.php';
$setttings($container);

AppFactory::setContainer($container);

$app = AppFactory::create();
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);


$app->get('/', function (Request $request, Response $response, $parameters) {
    $response->getBody()->write('Hello World');
    return $response;
});

$app->run();
