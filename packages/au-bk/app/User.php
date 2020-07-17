<?php
/*
Author: Raul Perusquia (raul@inikoo.com)
Created:  Sat Jul 18 2020 00:52:31 GMT+0800 (Malaysia Time)
Copyright (c) 2020, Inikoo Ltd

Version 4
*/

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'User Dimension';
    protected $primaryKey = 'User Key';

    protected $fillable = [
        'first_name',
        'last_name',
        'email'
    ];

    protected $guarded = [
        'password'
    ];
}