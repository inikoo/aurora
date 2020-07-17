<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:26:57 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Http\Controllers;

class ApiController
{
    public function index($response)
    {
        $response->getBody()->write(json_encode([
            'hello' => 'world'
        ], JSON_PRETTY_PRINT));

        return $response;
    }
}