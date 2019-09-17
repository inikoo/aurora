<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 09-09-2019 01:08:49 MYT Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


function get_website_users_read_time_data($redis, $account, $website_key) {

    $real_time_users = $redis->ZREVRANGE('_WU'.$account->get('Code').'|'.$website_key, 0, 1000, 'WITHSCORES');


    $users_desktop = 0;
    $users_mobile  = 0;
    $users_tablet  = 0;

    $users = array();

    foreach ($real_time_users as $_key => $timestamp) {
        $website_user = $redis->get($_key);
        if ($website_user) {
            $website_user = json_decode($website_user, true);

            if ($website_user['device'] == 'desktop') {
                $users_desktop++;
            }

            if ($website_user['device'] == 'mobile') {
                $users_mobile++;
            }
            if ($website_user['device'] == 'tablet') {
                $users_tablet++;

            }

            $users[] = $website_user;
            //  print_r($website_user);

        }
    }


    $total_users = $users_desktop + $users_mobile + $users_tablet;

    return array(

        'total_users'     => $total_users,
        'users'           => array(
            array(
                'device' => 'desktop',
                'users'  => $users_desktop
            ),
            array(
                'device' => 'mobile',
                'users'  => $users_mobile
            ),
            array(
                'device' => 'tablet',
                'users'  => $users_tablet
            ),

        ),
        'real_time_users' => $real_time_users,
        'users_data'      => $users
    );


}

function get_users_read_time_data($redis, $account) {

    $real_time_users_data = $redis->ZREVRANGE('_IU'.$account->get('Code'), 0, 100, 'WITHSCORES');


    $real_time_users = array();
    foreach ($real_time_users_data as $user_key => $timestamp) {


        $_user = $redis->hgetall('_IUObj'.$account->get('Code').':'.$user_key);


        if (isset($_user['alias'])) {


            $date = strftime("%H:%M %Z", $timestamp);


            if ($_user['logged_in']) {

                if ((gmdate('U') - $timestamp) < 300) {
                    $icon = '<i class="fa fa-fw fa-circle success" title="'.$date.'"></i>';
                } else {
                    $icon = '<i class="far fa-fw fa-circle success" title="'.$date.'"></i>';

                }

                $real_time_users[] = array(
                    'user_key'=>$user_key,
                    'type'=>'logged_in',
                    'icon'=>$icon,
                    'alias'=>$_user['alias'],
                    'request'=>$_user['request'],
                    'web_location'=>$_user['web_location'],
                );


            } else {

                $icon = '<i class="far fa-fw  fa-circle-notch error" title="'.$date.'"></i>';


                $real_time_users[] = array(
                    'user_key'=>$user_key,
                    'type'=>'logged_out',
                    'icon'=>$icon,
                    'alias'=>$_user['alias'],
                    'request'=>'',
                    'web_location'=>''
                );


            }

        }
    }

    return $real_time_users;


}