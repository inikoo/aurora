<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 16:46:40 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Http\Controllers;

class HomeController{
    public function index($response) {
        $response->getBody()->write('Welcome');
        return $response;
    }
}
