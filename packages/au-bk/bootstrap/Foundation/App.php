<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sun Jul 19 2020 13:13:53 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/


namespace Boot\Foundation;

class App extends \Slim\App
{
    public function bootedViaConsole()
    {
        return $this->has('bootedViaConsole')
            ? $this->resolve('bootedViaConsole')
            : false;
    }

    public function bootedViaHttpRequest()
    {
        return $this->has('bootedViaHttp')
            ? $this->resolve('bootedViaHttp')
            : false;
    }

    public function call(...$parameters)
    {
        return $this->getContainer()->call(...$parameters);
    }

    public function has(...$parameters)
    {
        return $this->getContainer()->has(...$parameters);
    }

    public function bind(...$parameters)
    {
        return $this->getContainer()->set(...$parameters);
    }

    public function make(...$parameters)
    {
        return $this->getContainer()->make(...$parameters);
    }

    public function resolve(...$parameters)
    {
        return $this->getContainer()->get(...$parameters);
    }
}