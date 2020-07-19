<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:40:03 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Http\Middleware;

use DB;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Routing\RouteContext;

class SetDatabaseMiddleware
{
    public function __invoke(Request $request, Handle $handler): Response
    {
        $route = RouteContext::fromRequest($request)->getRoute();

        throw_when(empty($route), "Route not found in request");
        
        $tenant=$route->getArguments()['tenant'];

        $db = app()->getContainer()->get(DB::class);

        $options = data_get(config('database.connections'), config('database.default'));

        $capsule = app()->getContainer()->get(DB::class);
        $options['database'] = $route->getArguments()['tenant'];
        $capsule->addConnection($options);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();



        return $handler->handle($request);
    }
}
