<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2016 at 11:48:20 GMT+8, Cyberyaja, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function parse_request($_data, $db, $user = '') {

    $request          = $_data['request'];
    $request          = preg_replace('/\/+/', '/', $request);
    $original_request = preg_replace('/^\//', '', $request);
    $view_path        = preg_split('/\//', $original_request);



    $code   = 'home';


    $state = [
        'code'    => $code,
        'request' => $request

    ];


    return $state;

}


?>
