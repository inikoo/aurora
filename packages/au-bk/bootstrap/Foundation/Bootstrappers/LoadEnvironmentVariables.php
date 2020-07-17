<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Fri Jul 17 2020 19:30:10 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class LoadEnvironmentVariables extends Bootstrapper
{
    public function boot()
    {
        try {
            $env = Dotenv::createImmutable(base_path());

            $env->load();
        } catch (InvalidPathException $e) {}
    }
}