<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24-06-2019 14:04:25 MYT Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/real_time_functions.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'users':
        real_time_users($redis, $account);
        break;
    case 'website_users':

        $data = prepare_values(
            $_REQUEST, array(
                         'website_key' => array('type' => 'key'),

                     )
        );
        real_time_website_users($data, $redis, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function real_time_users($redis, $account) {


    $response = array(
        'state' => 200,
        'users_data'  => get_users_read_time_data($redis, $account)
    );
    echo json_encode($response);
}


function real_time_website_users($data, $redis, $account) {

    $real_time_website_users_data=get_website_users_read_time_data($redis,$account,$data['website_key']);
    $real_time_website_users_data['state']=200;

    echo json_encode($real_time_website_users_data);
    exit;
}

