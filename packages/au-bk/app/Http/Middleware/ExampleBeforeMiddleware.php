<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:40:03 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Http\Middleware;


use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;

class ExampleBeforeMiddleware
{
    public function __invoke(Request $request, Handle $handler): Response
    {
        $response = $handler->handle($request);
        $existing_body = (string) $response->getBody();

        $response = new Response;
        $response->getBody()->write("Before: {$existing_body}");

        return $response;
    }
}
