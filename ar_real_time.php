<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24-06-2019 14:04:25 MYT Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';


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


    $real_time_users = array();
    foreach ($redis->zrange('_IU'.$account->get('Code'), 0, 100, 'WITHSCORES') as $user_key => $timestamp ) {


       $_user=get_object('User',$user_key);



        $real_time_users[]=array(
            'Key'=>$_user->id,
            'Alias'=>$_user->get('User Alias'),
            'Date'=>strftime("%a %e %b %Y %H:%M %Z", $timestamp),
        );
    }


    $html='<table>';
    foreach($real_time_users as $user_data){


        if(gmdate('U')-$timestamp<200){
            $icon='<i class="fa fa-fw fa-circle success" title="'.$user_data['Date'].'"></i>';
        }else{
            $icon='<i class="far fa-fw fa-circle success" title="'.$user_data['Date'].'"></i>';

        }

        $html.='<tr><td>'.$icon.'</td><td>'.$user_data['Alias'].'</td></tr>';
    }
    $html.='</table>';

    $response = array(
        'state' => 200,
        'html'  => $html
    );
    echo json_encode($response);
    exit;


}