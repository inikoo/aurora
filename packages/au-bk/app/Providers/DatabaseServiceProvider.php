<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sat Jul 18 2020 00:50:55 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App\Providers;

use DB;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $options = data_get(config('database.connections'), config('database.default'));

        $capsule = new DB;
        $capsule->addConnection($options);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->bind(DB::class, fn () => $capsule);
    }

    public function boot()
    {
        //
    }
}