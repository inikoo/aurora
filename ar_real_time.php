<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24-06-2019 14:04:25 MYT Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';


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


}


function real_time_website_users($data, $redis, $account, $user) {

    $real_time_users = $redis->ZREVRANGE('_WU'.$account->get('Code').'|'.$data['website_key'], 0, 100, 'WITHSCORES');


    $users_desktop=0;
    $users_mobile=0;
    $users_tablet=0;

    $users=array();

    foreach ($real_time_users as $_key => $timestamp) {
        $website_user=$redis->get($_key);
        if($website_user){
            $website_user=json_decode($website_user,true);

            if($website_user['device']=='desktop'){
                $users_desktop++;
            }

            if($website_user['device']=='mobile'){
                $users_mobile++;
            }
            if($website_user['device']=='tablet'){
                $users_tablet++;

            }

            $users[]=$website_user;
          //  print_r($website_user);

        }
    }


   $total_users=$users_desktop+$users_mobile+$users_tablet;

    $response = array(
        'state' => 200,
        'total_users'=>$total_users,
        'users'=>array(
            array('device'=>'desktop','users'=>$users_desktop),
            array('device'=>'mobile','users'=>$users_mobile),
            array('device'=>'tablet','users'=>$users_tablet),

        ),
        'real_time_users'=>$real_time_users,
        'users_data'=>$users
    );
    echo json_encode($response);
    exit;


}

