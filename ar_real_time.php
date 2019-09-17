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
        real_time_users($redis, $account, $user);
        break;
    case 'website_users':

        $data = prepare_values(
            $_REQUEST, array(
                         'website_key' => array('type' => 'key'),

                     )
        );
        real_time_website_users($data, $redis, $account, $user);
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


function real_time_users($redis, $account, $user) {


    $response = array(
        'state' => 200,
        'users_data'  => get_users_read_time_data($redis, $account)
    );
    echo json_encode($response);
    exit;

    /*
    $html            = '<table class="real_time_users">';
    $real_time_users = $redis->ZREVRANGE('_IU'.$account->get('Code'), 0, 100, 'WITHSCORES');


    foreach ($real_time_users as $user_key => $timestamp) {


        $_user = $redis->hgetall('_IUObj'.$account->get('Code').':'.$user_key);


        if (isset($_user['alias'])) {


            $date = strftime("%H:%M %Z", $timestamp);


            if ($user_key != $user->id) {

                if ($_user['logged_in']) {

                    if ((gmdate('U') - $timestamp) < 300) {
                        $icon = '<i class="fa fa-fw fa-circle success" title="'.$date.'"></i>';
                    } else {
                        $icon = '<i class="far fa-fw fa-circle success" title="'.$date.'"></i>';

                    }

                    if (!empty($_user['web_location'])) {
                        $web_location = $_user['web_location'];

                        if (!empty($_user['request'])) {
                            $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td onclick="change_view(\''.$_user['request'].'\')" class="button">'.$web_location.'</td>';

                        } else {
                            $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td >'.$web_location.' **</td>';

                        }


                    } else {
                        if (!empty($_user['request'])) {
                            $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td onclick="change_view(\''.$_user['request'].'\')" class="button"><i class="fal fa-eye-evil"></i></td>';
                        } else {
                            $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td ><i class="fal fa-eye-evil"></i> **</td>';

                        }
                    }
                } else {
                    $html .= '<tr><td><i class="far fa-fw fa-circle-notch error" title="'.$date.'"></i></td><td>'.$_user['alias'].'</td><td class="discreet error">'._('Log out').'</td>';

                }


            }


            $html .= '</tr>';

        }


    }

    $html .= '</table>';


    $response = array(
        'state' => 200,
        'html'  => $html
    );
    echo json_encode($response);
    exit;
*/

}


function real_time_website_users($data, $redis, $account, $user) {

    $real_time_website_users_data=get_website_users_read_time_data($redis,$account,$data['website_key']);
    $real_time_website_users_data['state']=200;


    echo json_encode($real_time_website_users_data);
    exit;


}

