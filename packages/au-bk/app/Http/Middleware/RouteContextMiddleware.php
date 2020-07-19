<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sun Jul 19 2020 12:58:12 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/


namespace App\Http\Middleware;

use App\Support\FormRequest;
use App\Support\Redirect;
use App\Support\RequestInput;
use Slim\Routing\RouteContext;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Psr\Http\Message\ServerRequestInterface as Request;

class RouteContextMiddleware
{
    public function __invoke(Request $request, Handle $handler)
    {
        $route = RouteContext::fromRequest($request)->getRoute();

        throw_when(empty($route), "Route not found in request");

        app()->bind(Redirect::class, fn (ResponseFactory $factory) => new Redirect($factory));

        $input = new RequestInput($request, $route);
        app()->bind(RequestInput::class, $input);

       

        return $handler->handle($request);
    }
}
