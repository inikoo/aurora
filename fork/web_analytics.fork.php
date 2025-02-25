<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

include_once 'utils/send_zqm_message.class.php';

/**
 * @throws \ZMQSocketException
 */
function fork_web_analytics($job): bool {

    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata_v2($job)) {
        return true;
    }

    list($account, $db, $data, $editor) = $_data;



    switch ($data['type']) {

        case 'website_user_visit':


            if(empty($data['session_data']['website_key'])){
                return false;
            }

            include_once 'utils/network_functions.php';
            include_once 'utils/parse_user_agent.php';
            require_once 'utils/real_time_functions.php';

            include_once 'utils/ip_geolocation.php';

            $ip = 'unknown';
            if (!empty($data['server_data']['HTTP_CF_CONNECTING_IP'])) {
                $ip = $data['server_data']['HTTP_CF_CONNECTING_IP'];

            } elseif (!empty($data['server_data']['REMOTE_ADDR'])) {
                $ip = $data['server_data']['REMOTE_ADDR'];

            }


            $user_agent_data  = parse_user_agent($data['server_data']['HTTP_USER_AGENT'], $db);
            $geolocation_data = get_ip_geolocation(trim($ip), $db);


            //  print_r($user_agent_data);
            //print_r($geolocation_data);
            //  print_r($webpage_data);
            //print_r($data);


        $device_label='';
        if($user_agent_data and is_array($user_agent_data)){
            $device_label=sprintf('<i title="%s" class="far fa-fw %s"></i>', $user_agent_data['Software'].($user_agent_data['Software Details'] != '' ? ' ('.$user_agent_data['Software Details'].')' : ''), $user_agent_data['Icon']);
        }

            $webuser_data = array(
                'os'           => $user_agent_data['OS Code'],
                'device'       => $data['device'],
                'device_label' => $device_label,
                //'ip'           => $ip,
                //'geo_location' => $geolocation_data,
                //'server_data'  => $data['server_data']

            );


            if (empty($geolocation_data['Location'])) {

                if (!empty($data['server_data']['HTTP_CF_IPCOUNTRY'])) {
                    $webuser_data['location'] = '<img src="/art/flags/'.strtolower($data['server_data']['HTTP_CF_IPCOUNTRY']).'.png">';

                } else {
                    $webuser_data['location'] = '<img alt="'._('Unknown').'" src="/art/flags/zz.png"> <span class="italic very_discreet">'._('Unknown').'</span>';

                }


            } else {
                $webuser_data['location'] = $geolocation_data['Location'];
                /*

                 $webuser_data['flag']     = strtolower($geolocation_data['Country Code']);
                 $webuser_data['location'] = $geolocation_data['Town'];
                 if ($geolocation_data['Region Name'] != '') {
                     if ($webuser_data['location'] != '') {
                         $webuser_data['location'] .= ' ('.$geolocation_data['Region Name'].')';
                     } else {
                         $webuser_data['location'] = $geolocation_data['Region Name\''];
                     }
                 }

                */

            }

            $webpage_label = '';
            $sql           = 'select `Webpage Code`, `Webpage Scope` ,`Webpage Scope Key`,`Webpage URL` ,`Page Key`  from `Page Store Dimension` where `Page Key`=? ';
            $stmt          = $db->prepare($sql);
            $stmt->execute(
                array($data['webpage_key'])
            );
            if ($row = $stmt->fetch()) {


                if ($row['Webpage Code'] == 'home.sys') {
                    $webpage_label = '<i class="fal fa-home"></i> '._('Home');
                } elseif ($row['Webpage Code'] == 'basket.sys' or $row['Webpage Code'] == 'client_basket.sys' or $row['Webpage Code'] == 'client_order_new.sys') {
                    $webpage_label = '<i class="fal fa-shopping-basket"></i> '._('Basket');
                } elseif ($row['Webpage Code'] == 'profile.sys') {
                    $webpage_label = '<i class="fal fa-user"></i> '._('Profile');
                } elseif ($row['Webpage Code'] == 'clients_orders.sys') {
                    $webpage_label = '<i class="fal fa-shopping-cart"></i> '._('Orders');
                } elseif ($row['Webpage Code'] == 'portfolio.sys') {
                    $webpage_label = '<i class="fal fa-store-alt"></i> '._('Portfolio');
                } elseif ($row['Webpage Code'] == 'client.sys') {
                    $webpage_label = '<i class="fal fa-user"></i> '._('Client');
                } elseif ($row['Webpage Code'] == 'shipping.sys') {
                    $webpage_label = '<i class="fal fa-shipping-fast"></i> '._('Shipping');
                } elseif ($row['Webpage Code'] == 'faq') {
                    $webpage_label = '<i class="fal fa-question"></i> '._('FAQ');
                } elseif ($row['Webpage Code'] == 'clients.sys') {
                    $webpage_label = '<i class="fal fa-user"></i> '._('Clients');
                } elseif ($row['Webpage Code'] == 'checkout.sys') {
                    $webpage_label = '<i class="fal fa-scanner-keyboard"></i> '._('Checkout');
                } elseif ($row['Webpage Code'] == 'thanks.sys') {
                    $webpage_label = '<i class="fal fa-glass-cheers"></i> '._('Sale completed');
                } elseif ($row['Webpage Code'] == 'search.sys') {
                    $webpage_label = '<i class="fal fa-search"></i> '._('Search');
                } elseif ($row['Webpage Code'] == 'not_found.sys') {
                    $webpage_label = '<i class="fal fa-compass-slash"></i> '._('Not found');
                } elseif ($row['Webpage Scope'] == 'Product') {
                    $webpage_label = '<i class="fal fa-cube"></i> '.strtolower($row['Webpage Code']);
                } elseif ($row['Webpage Scope'] == 'Category Products') {
                    $webpage_label = '<i class="fal fa-cubes"></i> '.strtolower($row['Webpage Code']);
                } elseif ($row['Webpage Scope'] == 'Category Categories') {
                    $webpage_label = '<i class="fal fa-folder-tree"></i> '.strtolower($row['Webpage Code']);
                } else {
                    $webpage_label = strtolower($row['Webpage Code']);
                }


                $webuser_data['webpage_label'] = sprintf('<span class="button" onclick="change_view(\'website/%d/webpage/%d\')">%s</span>', $data['session_data']['website_key'], $data['webpage_key'], $webpage_label);
                $webuser_data['webpage_key']   = $row['Page Key'];
                $webuser_data['webpage_url']   = $row['Webpage URL'];

            }

            $sql  = 'select `Customer Store Key`,`Customer Name`, `Customer Key`  from `Customer Dimension`   where `Customer Key`=? ';
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array($data['session_data']['customer_key'])
            );
            if ($row = $stmt->fetch()) {


                $webuser_data['customer_label'] = sprintf('<span class="button" onclick="change_view(\'customers/%d/%d\')">%s</span>', $row['Customer Store Key'], $row['Customer Key'], $row['Customer Name']);
                $webuser_data['customer_key']   = $row['Customer Key'];

            }

            $sql = "SELECT `Order Key`,`Order Total Net Amount`,`Order Currency`,`Order Public ID` ,`Order Store Key` FROM `Order Dimension` WHERE `Order Customer Key`=? AND `Order State`='InBasket' ";

            $stmt = $db->prepare($sql);
            $stmt->execute(
                array($data['session_data']['customer_key'])
            );
            if ($row = $stmt->fetch()) {


                $webuser_data['order_net']           = $row['Order Total Net Amount'];
                $webuser_data['order_net_formatted'] =
                    sprintf('<span title="%s" class="button" onclick="change_view(\'orders/%d/%d\')">%s</span>', $row['Order Public ID'], $row['Order Store Key'], $row['Order Key'], money($row['Order Total Net Amount'], $row['Order Currency']));


            } else {
                $webuser_data['order_net']           = '';
                $webuser_data['order_net_formatted'] = '';

            }


            $redis = new Redis();


            if ($redis->connect(REDIS_HOST, REDIS_PORT)) {


                /*
                $sql  = 'select `Website Key` from `Website Dimension`';
                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array()
                );
                while ($row = $stmt->fetch()) {
                    $redis->zRemRangeByScore('_WU'.$account->get('Code').'|'.$row['Website Key'], 0, gmdate('U') - 300);
                }
                */


                $key  = '_WU'.$account->get('Code').'|'.$data['session_data']['website_key'];
                $_key = '_WUO'.$account->get('Code').'|'.$data['session_data']['customer_key'];

                $redis->zadd($key, gmdate('U'), $_key);
                $redis->set($_key, json_encode($webuser_data));
                $redis->expire($_key, 300);


                $real_time_website_users_data = get_website_users_read_time_data($redis, $account, $data['session_data']['website_key']);
                //print_r($real_time_website_users_data);
                //exit;

                include_once 'utils/send_zqm_message.class.php';
                send_zqm_message(
                    json_encode(
                        array(
                            'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                            'd3'      => array(
                                array(
                                    'type'        => 'current_website_users',
                                    'website_key' => $data['session_data']['website_key']??'',
                                    'data'        => $real_time_website_users_data
                                )
                            ),
                            'objects' => array(
                                array(
                                    'object'          => 'customer',
                                    'key'             => $data['session_data']['customer_key']??'',
                                    'update_metadata' => array(
                                        'class_html' => array(
                                            'webpage_label'        => $webpage_label,
                                            'device_label'         => $webuser_data['device_label']??'',
                                            'user_location'        => $webuser_data['location']??'',
                                            'customer_online_icon' => '<i title="'._('Online').'" class="far success fa-globe"></i>',
                                        ),
                                        'hide'       => array('customer_web_info_log_out'),
                                        'show'       => array('customer_web_info_log_in'),
                                    )
                                )

                            ),
                            'tabs'    => array(
                                array(
                                    'tab' => 'websites',

                                    'cell' => array(
                                        'website_rt_user_'.$data['session_data']['website_key'] => count($real_time_website_users_data['real_time_users'])
                                    )
                                )

                            ),

                        )
                    )
                );


            }

            $sql = 'update `Customer Dimension` set `Customer Last Website Visit`=? where `Customer Key`=? ';

            $db->prepare($sql)->execute(
                array(
                    $data['datetime'],
                    $data['session_data']['customer_key']
                )
            );
            break;

    }

    $db = null;

    return false;

}