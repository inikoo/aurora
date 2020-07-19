<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sun Jul 19 2020 12:59:53 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/



namespace App\Support;

use Psr\Http\Message\ResponseFactoryInterface;

class Redirect
{
    protected $response;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->response = $factory->createResponse(302);
    }

    public function __invoke(string $to)
    {
        $this->response = $this->response->withHeader('Location', $to);

        return $this->response;
    }
}
