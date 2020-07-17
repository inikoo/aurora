<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 21:58:08 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Support;

use Slim\Routing\RouteCollectorProxy;

class RouteGroup
{
    public $app;
    public $prefix;
    public $routes;
    public array $middleware = [];

    public function __construct(&$app)
    {
        $this->app = $app;
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function routes($path = '')
    {
        $this->routes = $path;

        return $this;
    }

    public function middleware(array $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    public function register()
    {
        $group = $this->app->group($this->prefix, function (RouteCollectorProxy $group) {
           $app = Route::setup($group);

           require $this->routes;
        });

        array_walk($this->middleware, fn ($middleware) => $group->add(new $middleware));

        Route::setup($this->app);
    }
}