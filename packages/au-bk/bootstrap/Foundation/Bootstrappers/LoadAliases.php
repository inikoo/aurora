<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sat Jul 18 2020 00:53:21 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace Boot\Foundation\Bootstrappers;

class LoadAliases extends Bootstrapper
{
    public function boot()
    {
        $aliases = config('app.aliases');

        array_walk($aliases, fn ($path, $alias) => class_alias($path, $alias, true));
    }
}