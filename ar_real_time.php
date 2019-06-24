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
        real_time_users($redis, $account,$user);
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


function real_time_users($redis, $account,$user) {

    $html = '<table class="real_time_users">';
    $real_time_users = array();
    foreach ($redis->zrange('_IU'.$account->get('Code'), 0, 100, 'WITHSCORES') as $user_key => $timestamp) {



        $_user = $redis->hgetall('_IUObj'.$account->get('Code').':'.$user_key);


        if (isset($_user['alias'])) {



            $date= strftime("%a %e %b %Y %H:%M %Z", $timestamp);

            if ((gmdate('U') - $timestamp) < 200) {
                $icon = '<i class="fa fa-fw fa-circle success" title="'.$date.'"></i>';
            } else {
                $icon = '<i class="far fa-fw fa-circle success" title="'.$date.'"></i>';

            }

            if(!empty($_user['web_location'])  and  $user_key!=$user->id){
                $web_location=$_user['web_location'];
                $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td onclick="change_view(\''.$_user['request'].'\')" class="button">'.$web_location.'</td>';


            }else{
                $html .= '<tr><td>'.$icon.'</td><td>'.$_user['alias'].'</td><td></td>';

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