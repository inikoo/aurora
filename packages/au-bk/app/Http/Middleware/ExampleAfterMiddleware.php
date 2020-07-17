<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:39:16 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Http\Middleware;

use Psr\Http\Server\RequestHandlerInterface as Handle;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExampleAfterMiddleware
{
    public function __invoke(Request $request, Handle $handler)
    {
        $response = $handler->handle($request);

        $response->getBody()->write("\n After Middleware");

        return $response;
    }
}