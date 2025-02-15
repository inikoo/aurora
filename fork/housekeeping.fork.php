<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

include_once 'utils/send_zqm_message.class.php';

function fork_housekeeping($job)
{
    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


    print $data['type']."\n";
    //return true;
    switch ($data['type']) {
        case 'update_rent_order':

            $customer         = get_object('Customer_Fulfilment', $data['customer_key']);
            $customer->editor = $data['editor'];

            $customer->update_rent_order();


            break;
        case 'website_user_visit':

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

            $webuser_data = array(
                'os'           => $user_agent_data['OS Code'],
                'device'       => $data['device'],
                'device_label' => sprintf('<i title="%s" class="far fa-fw %s"></i>', $user_agent_data['Software'].($user_agent_data['Software Details'] != '' ? ' ('.$user_agent_data['Software Details'].')' : ''), $user_agent_data['Icon']),
                //'ip'           => $ip,
                //'geo_location' => $geolocation_data,
                //'server_data'  => $data['server_data']

            );


            if ($geolocation_data['Location'] == '') {
                if (!empty($data['server_data']['HTTP_CF_IPCOUNTRY'])) {
                    $webuser_data['location'] = '<img src="/art/flags/'.strtolower($data['server_data']['HTTP_CF_IPCOUNTRY']).'.png">';
                } else {
                    $webuser_data['location'] = '<img src="/art/flags/zz.png"> <span class="italic very_discreet">'._('Unknown').'</span>';
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
                                    'website_key' => $data['session_data']['website_key'],
                                    'data'        => $real_time_website_users_data
                                )
                            ),
                            'objects' => array(
                                array(
                                    'object'          => 'customer',
                                    'key'             => $data['session_data']['customer_key'],
                                    'update_metadata' => array(
                                        'class_html' => array(
                                            'webpage_label'        => $webpage_label,
                                            'device_label'         => $webuser_data['device_label'],
                                            'user_location'        => $webuser_data['location'],
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


        case 'update_basket_orders':


            $date = gmdate('Y-m-d H:i:s');

            $sql = sprintf(
                "SELECT `Order Key` FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Key`=%d order by `Order Last Updated Date` desc ",
                $data['store_key']
            );

            $counter = 0;
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($counter < 100) {
                        $operation = 'update_order_in_basket';
                    } else {
                        $operation = 'update_order_in_basket_low_priority';
                    }

                    $sql = sprintf(
                        'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                        prepare_mysql($date),
                        prepare_mysql($date),
                        prepare_mysql($operation),
                        $row['Order Key'],
                        prepare_mysql($date)

                    );
                    $db->exec($sql);
                    $counter++;
                }
            }


            break;
        case 'feedback':

            foreach ($data['feedback'] as $feedback) {
                $feedback_data = array(
                    'Feedback Date'       => gmdate('Y-m-d H:i:s'),
                    'Feedback User Key'   => $data['user_key'],
                    'Feedback Parent'     => $data['parent'],
                    'Feedback Parent Key' => $data['parent_key'],
                    'Feedback Message'    => $feedback['feedback']
                );

                foreach ($feedback['scopes'] as $scope) {
                    $feedback_data['Feedback '.$scope] = 'Yes';
                }

                $sql = sprintf(
                    "INSERT INTO `Feedback Dimension` (%s) values (%s)",
                    '`'.join('`,`', array_keys($feedback_data)).'`',
                    join(',', array_fill(0, count($feedback_data), '?'))
                );

                $stmt = $db->prepare($sql);


                $i = 1;
                foreach ($feedback_data as $key => $value) {
                    $stmt->bindValue($i, $value);
                    $i++;
                }

                if ($stmt->execute()) {
                    $feedback_id = $db->lastInsertId();


                    if (isset($feedback['itf'])) {
                        $feedback_itf_data = array(
                            'Feedback ITF Feedback Key' => $feedback_id,
                        );

                        $feedback_otf_data = array(
                            'Feedback OTF Feedback Key' => $feedback_id,
                            'Feedback OTF Original Key' => '',
                            'Feedback OTF Store Key'    => $data['store_key']
                        );

                        $sql = sprintf(
                            'select `Inventory Transaction Key`,`Map To Order Transaction Fact Key` from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d',
                            $feedback['original_itf']
                        );

                        if ($result = $db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $feedback_itf_data['Feedback ITF Original Key'] = $row['Inventory Transaction Key'];
                                $feedback_otf_data['Feedback OTF Original Key'] = $row['Map To Order Transaction Fact Key'];
                            }
                        }

                        $sql = sprintf(
                            'select `Inventory Transaction Key`,`Map To Order Transaction Fact Key` from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d',
                            $feedback['itf']
                        );

                        if ($result = $db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $feedback_itf_data['Feedback ITF Post Operation Key'] = $row['Inventory Transaction Key'];

                                if ($row['Map To Order Transaction Fact Key'] != $feedback_otf_data['Feedback OTF Original Key']) {
                                    $feedback_otf_data['Feedback OTF Post Operation Key'] = $row['Map To Order Transaction Fact Key'];
                                }
                            }
                        }


                        $sql = sprintf(
                            "INSERT INTO `Feedback ITF Bridge` (%s) values (%s)",
                            '`'.join('`,`', array_keys($feedback_itf_data)).'`',
                            join(',', array_fill(0, count($feedback_itf_data), '?'))
                        );

                        $stmt = $db->prepare($sql);


                        $i = 1;
                        foreach ($feedback_itf_data as $key => $value) {
                            $stmt->bindValue($i, $value);
                            $i++;
                        }

                        $stmt->execute();


                        $sql = sprintf(
                            "INSERT INTO `Feedback OTF Bridge` (%s) values (%s)",
                            '`'.join('`,`', array_keys($feedback_otf_data)).'`',
                            join(',', array_fill(0, count($feedback_otf_data), '?'))
                        );

                        $stmt = $db->prepare($sql);

                        //  print_r($feedback_otf_data);

                        $i = 1;
                        foreach ($feedback_otf_data as $key => $value) {
                            $stmt->bindValue($i, $value);
                            $i++;
                        }

                        $stmt->execute();
                    } elseif (isset($feedback['otf'])) {
                        $feedback_otf_data = array(
                            'Feedback OTF Feedback Key'       => $feedback_id,
                            'Feedback OTF Original Key'       => (isset($feedback['original_otf']) ? $feedback['original_otf'] : ''),
                            'Feedback OTF Store Key'          => $data['store_key'],
                            'Feedback OTF Post Operation Key' => $feedback['otf']
                        );


                        $sql = sprintf(
                            "INSERT INTO `Feedback OTF Bridge` (%s) values (%s)",
                            '`'.join('`,`', array_keys($feedback_otf_data)).'`',
                            join(',', array_fill(0, count($feedback_otf_data), '?'))
                        );

                        $stmt = $db->prepare($sql);

                        //  print_r($feedback_otf_data);

                        $i = 1;
                        foreach ($feedback_otf_data as $key => $value) {
                            $stmt->bindValue($i, $value);
                            $i++;
                        }

                        $stmt->execute();

                        $sql = sprintf(
                            'select `Inventory Transaction Key` from `Inventory Transaction Fact` where `Map To Order Transaction Fact Key`=%d',
                            $feedback['original_otf']
                        );

                        if ($result = $db->query($sql)) {
                            foreach ($result as $row) {
                                $feedback_itf_data = array(
                                    'Feedback ITF Feedback Key' => $feedback_id,
                                    'Feedback ITF Original Key' => $row['Inventory Transaction Key'],
                                );


                                $sql = sprintf(
                                    "INSERT INTO `Feedback ITF Bridge` (%s) values (%s)",
                                    '`'.join('`,`', array_keys($feedback_itf_data)).'`',
                                    join(',', array_fill(0, count($feedback_itf_data), '?'))
                                );

                                $stmt = $db->prepare($sql);


                                $i = 1;
                                foreach ($feedback_itf_data as $key => $value) {
                                    $stmt->bindValue($i, $value);
                                    $i++;
                                }

                                $stmt->execute();
                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    } elseif (isset($feedback['onptf'])) {
                        $feedback_otf_data = array(
                            'Feedback ONPTF Feedback Key'       => $feedback_id,
                            'Feedback ONPTF Original Key'       => (isset($feedback['original_onptf']) ? $feedback['original_onptf'] : ''),
                            'Feedback ONPTF Store Key'          => $data['store_key'],
                            'Feedback ONPTF Post Operation Key' => $feedback['onptf']
                        );


                        $sql = sprintf(
                            "INSERT INTO `Feedback ONPTF Bridge` (%s) values (%s)",
                            '`'.join('`,`', array_keys($feedback_otf_data)).'`',
                            join(',', array_fill(0, count($feedback_otf_data), '?'))
                        );

                        $stmt = $db->prepare($sql);

                        //  print_r($feedback_otf_data);

                        $i = 1;
                        foreach ($feedback_otf_data as $key => $value) {
                            $stmt->bindValue($i, $value);
                            $i++;
                        }

                        $stmt->execute();
                    }
                }
            }


            break;
        case 'update_currency_exchange':


            include_once 'utils/currency_functions.php';

            $exchange = currency_conversion($db, $data['currency_from'], $data['currency_to']);

            print $exchange;

            break;

        case 'update_parts_inventory_snapshot_fact':


            //  print_r($data);

            foreach ($data['parts_data'] as $part_sku => $from_date) {
                /** @var $part \Part */
                $part = get_object('Part', $part_sku);
                $part->update_part_inventory_snapshot_fact($from_date);
            }

            $sql = 'SELECT `Warehouse Key` FROM `Warehouse Dimension`';
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $warehouse = get_object('Warehouse', $row2['Warehouse Key']);
                    $warehouse->update_inventory_snapshot($data['all_parts_min_date'], gmdate('Y-m-d'));
                }
            }

            break;
        case 'update_parts_stock_run':
            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_long_operations',
                array(
                    'type'               => 'update_parts_stock_run',
                    'parts_data'         => $data['parts_data'],
                    'editor'             => $editor,
                    'all_parts_min_date' => $data['all_parts_min_date'],
                ),
                $account->get('Account Code')
            );

            break;

        case 'part_stock_run':

            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_long_operations',
                array(
                    'type'     => 'part_stock_run',
                    'part_sku' => $data['part_sku'],
                    'editor'   => $editor,
                ),
                $account->get('Account Code')
            );

            break;


        case 'update_part_status':

            /**
             * @var $part \Part
             */ $part = get_object('Part', $data['part_sku']);

            $part->editor = $data['editor'];


            $part->update_stock_status();
            $part->update_available_forecast();


            $sql = sprintf(
                "SELECT `Category Key` FROM `Category Bridge` WHERE `Subject`='Part' AND `Subject Key`=%d",
                $part->id
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category         = get_object('Category', $row['Category Key']);
                    $category->editor = $data['editor'];

                    $category->update_part_category_status();
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $products = $part->get_products('objects');
            foreach ($products as $product) {
                $product->editor = $data['editor'];
                $product->update_status_from_parts();
            }

            $account->update_parts_data();
            $account->update_active_parts_stock_data();


            switch ($part->get('Part Status')) {
                case 'In Use':
                    $part_status = sprintf('<i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-box title="%s"></i>', $part->id, _('Active, click to discontinue'));
                    break;
                case 'Discontinuing':
                    $part_status = sprintf('<i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-skull" title="%s"></i>', $part->id, _('Discontinuing, click to set as an active part'));
                    break;
                case 'Discontinued':
                    $part_status = sprintf('<i  class="far  fa-fw fa-tombstone" title="%s"></i>', _('Discontinued'));
                    break;
                case 'In Process':
                    $part_status = sprintf('<i  class="far  fa-fw fa-seedling" title="%s"></i>', _('In process'));
                    break;
                default:
                    $part_status = $part->get('Part Status');
            }


            include_once 'utils/send_zqm_message.class.php';
            send_zqm_message(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                        'tabs' => array(
                            array(
                                'tab'   => 'inventory.discontinuing_parts',
                                'rtext' => sprintf(ngettext('%s discontinuing part', '%s discontinuing parts', $account->get('Account Discontinuing Parts Number')), number($account->get('Account Discontinuing Parts Number'))),

                                'cell' => array(
                                    'part_status_'.$part->id => $part_status
                                )
                            )

                        ),

                    )
                )
            );


            break;

        case 'deal_created':


            $deal     = get_object('Deal', $data['deal_key']);
            $campaign = get_object('Campaign', $deal->get('Deal Campaign Key'));
            if ($deal->get('Deal Status') == 'Active' and !$deal->get('Deal Voucher Key')) {
                switch ($campaign->get('Deal Campaign Code')) {
                    case 'CA':


                        $sql = sprintf(
                            'SELECT `Order Key`  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Category Key`=%d and `Current Dispatching State`="In Process"  group by `Order Key`',
                            $deal->get('Deal Trigger Key')
                        );
                        break;


                    default:
                        $sql = sprintf(
                            'SELECT `Order Key`  FROM `Order Dimension` where  `Order State`="InBasket"  and `Order Store Key`=%d ',
                            $deal->get('Deal Store Key')
                        );
                        break;
                }


                if ($result = $db->query($sql)) {
                    foreach ($result as $row) {
                        $order          = get_object('Order', $row['Order Key']);
                        $old_used_deals = $order->get_used_deals();
                        $order->update_totals();

                        $order->update_discounts_items();
                        $order->update_totals();

                        $order->update_shipping();
                        $order->update_charges(false, false);
                        $order->update_discounts_no_items();
                        $order->update_deal_bridge();

                        $new_used_deals = $order->get_used_deals();


                        $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
                        $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

                        $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
                        $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

                        $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
                        $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));

                        $date = gmdate('Y-m-d H:i:s');

                        foreach ($campaigns_diff as $campaign_key) {
                            if ($campaign_key > 0) {
                                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

                                $db->prepare($sql)->execute(
                                    [
                                        $date,
                                        $date,
                                        'deal_campaign',
                                        $campaign_key,
                                        $date,

                                    ]
                                );
                            }
                        }

                        foreach ($deal_diff as $deal_key) {
                            if ($deal_key > 0) {
                                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                $db->prepare($sql)->execute(
                                    [
                                        $date,
                                        $date,
                                        'deal',
                                        $deal_key,
                                        $date,

                                    ]
                                );
                            }
                        }

                        foreach ($deal_components_diff as $deal_component_key) {
                            if ($deal_component_key > 0) {
                                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                $db->prepare($sql)->execute(
                                    [
                                        $date,
                                        $date,
                                        'deal_component',
                                        $deal_component_key,
                                        $date,

                                    ]
                                );
                            }
                        }
                        $order->update_totals();
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }
            }
            $campaign->update_number_of_deals();


            break;

        case 'deal_updated':


            $deal = get_object('Deal', $data['deal_key']);


            //print $deal->get('Deal Trigger');


            switch ($deal->get('Deal Trigger')) {
                case 'Category':


                    $sql = sprintf(
                        'SELECT `Order Key`  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Category Key`=%d and `Current Dispatching State`="In Process"  group by `Order Key`',
                        $deal->get('Deal Trigger Key')
                    );


                    // print $sql;


                    if ($result = $db->query($sql)) {
                        foreach ($result as $row) {
                            //print_r($row);
                            $order = get_object('Order', $row['Order Key']);

                            $old_used_deals = $order->get_used_deals();
                            $order->update_totals();

                            $order->update_discounts_items();
                            $order->update_totals();

                            $order->update_shipping();
                            $order->update_charges(false, false);
                            $order->update_discounts_no_items();
                            $order->update_deal_bridge();
                            $new_used_deals = $order->get_used_deals();


                            $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
                            $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

                            $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
                            $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

                            $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
                            $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));

                            $date = gmdate('Y-m-d H:i:s');

                            foreach ($campaigns_diff as $campaign_key) {
                                if ($campaign_key > 0) {
                                    $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

                                    $db->prepare($sql)->execute(
                                        [
                                            $date,
                                            $date,
                                            'deal_campaign',
                                            $campaign_key,
                                            $date,

                                        ]
                                    );
                                }
                            }

                            foreach ($deal_diff as $deal_key) {
                                if ($deal_key > 0) {
                                    $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                    $db->prepare($sql)->execute(
                                        [
                                            $date,
                                            $date,
                                            'deal',
                                            $deal_key,
                                            $date,

                                        ]
                                    );
                                }
                            }

                            foreach ($deal_components_diff as $deal_component_key) {
                                if ($deal_component_key > 0) {
                                    $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                    $db->prepare($sql)->execute(
                                        [
                                            $date,
                                            $date,
                                            'deal_component',
                                            $deal_component_key,
                                            $date,

                                        ]
                                    );
                                }
                            }

                            $order->update_totals();
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    break;
                default:
                    break;
            }


            //exit;

            break;

        case 'update_deals_usage':


            foreach ($data['deal_components'] as $deal_component_key) {
                $deal_component = get_object('DealComponent', $deal_component_key);
                $deal_component->update_usage();
            }

            foreach ($data['deals'] as $deal_key) {
                $deal = get_object('Deal', $deal_key);
                $deal->update_usage();
            }

            foreach ($data['campaigns'] as $campaign_key) {
                $campaign = get_object('DealCampaign', $campaign_key);
                $campaign->update_usage();
            }

            break;

        case 'payment_created':
        case 'payment_updated':


            $payment                  = get_object('Payment', $data['payment_key']);
            $payment_account          = get_object('Payment_Account', $payment->get('Payment Account Key'));
            $payment_service_provider = get_object('Payment_Service_Provider', $payment->get('Payment Service Provider Key'));

            $customer = get_object('Customer', $payment->get('Payment Customer Key'));

            $payment_account->update_payments_data();
            $payment_service_provider->update_payments_data();


            $store = get_object('Store', $payment->get('Payment Store Key'));

            $customer->update_payments();
            $store->update_orders();
            $store->update_payments();
            $account->update_orders();

            break;

        /*
        case 'update_orders_in_basket_data':


            // remove after migration
            $store   = get_object('Store', $data['store_key']);
            $store->update_orders_in_basket_data();
            $account->update_orders_in_basket_data();

           include_once 'utils/send_zqm_message.class.php';
            send_zqm_message(
                json_encode(
                    array(
                        'channel'  => 'real_time.'.strtolower($account->get('Account Code')),
                        'sections' => array(
                            array(
                                'section' => 'dashboard',

                                'update_metadata' => array(
                                    'class_html' => array(
                                        'Orders_In_Basket_Number' => $account->get('Orders In Basket Number'),
                                        'Orders_In_Basket_Amount' => $account->get('DC Orders In Basket Amount'),
                                    )
                                )

                            )

                        ),


                    )
                )
            );

            break;
        */

        case 'order_items_changed':
            $order = get_object('Order', $data['order_key']);


            $store = get_object('Store', $order->get('Store Key'));


            switch ($order->get('Order State')) {
                case 'InBasket':
                    $store->update_orders_in_basket_data();
                    $account->update_orders_in_basket_data();

                    break;
                case 'InProcess':
                    $store->update_orders_in_process_data();
                    $account->update_orders_in_process_data();

                    break;
                case 'InWarehouse':
                    $store->update_orders_in_warehouse_data();
                    $account->update_orders_in_warehouse_data();

                    break;
                case 'Packed':
                    $store->update_orders_packed_data();
                    $account->update_orders_packed_data();

                    break;
                case 'PackedDone':
                    $store->update_orders_packed_done_data();
                    $account->update_orders_packed_done_data();

                    break;
                case 'Approved':
                    $store->update_orders_approved_data();
                    $account->update_orders_approved_data();

                    break;


                default:
                    break;
            }

            $account->load_acc_data();
            $store->load_acc_data();

            include_once 'utils/send_zqm_message.class.php';
            send_zqm_message(
                json_encode(
                    array(
                        'channel'  => 'real_time.'.strtolower($account->get('Account Code')),
                        'sections' => array(
                            array(
                                'section' => 'dashboard',

                                'update_metadata' => array(
                                    'class_html' => array(
                                        'Account .Orders_In_Basket_Number' => $account->get('Orders In Basket Number'),
                                        'Account .Orders_In_Basket_Amount' => $account->get('DC Orders In Basket Amount'),
                                    )
                                )

                            )

                        ),


                    )
                )
            );


            break;
        case 'update_charges_data':

            $sql = sprintf('SELECT `Charge Key` FROM `Charge Dimension` WHERE `Charge Store Key`=%d  ', $data['store_key']);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $charge = get_object('Charge', $row['Charge Key']);
                    $charge->update_charge_usage();
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            break;
        case 'customer_deleted':


            $sql = sprintf(
                "DELETE FROM `Customer Correlation` WHERE `Customer A Key`=%d OR `Customer B Key`=%s",
                $data['customer_key'],
                $data['customer_key']
            );
            $db->exec($sql);

            $db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `List Customer Bridge` WHERE `Customer Key`=%d",
                $data['customer_key']
            );
            $db->exec($sql);

            $sql = sprintf(
                "DELETE FROM `Customer Send Post` WHERE `Customer Key`=%d",
                $data['customer_key']
            );
            $db->exec($sql);


            $sql = sprintf(
                "DELETE FROM `Category Bridge` WHERE `Subject`='Customer' AND `Subject Key`=%d",
                $data['customer_key']
            );
            $db->exec($sql);


            $website_user         = get_object('Website_User', $data['website_user']);
            $website_user->editor = $data['editor'];

            $website_user->delete();


            $store = get_object('Store', $data['store_key']);
            $store->update_customers_data();

            break;
        case 'order_state_changed':
            $order = get_object('Order', $data['order_key']);
            $store = get_object('Store', $order->get('Store Key'));


            $store->update_orders();
            $account->update_orders();


            break;


        case 'order_created':

            $order    = get_object('Order', $data['subject_key']);
            $customer = get_object('Customer', $order->get('Order Customer Key'));
            $store    = get_object('Store', $order->get('Order Store Key'));


            $data['editor']['Date'] = gmdate('Y-m-d H:i:s');

            if ($customer->id) {
                $customer->editor = $data['editor'];
                $customer->update_orders();
                $customer->update_activity();
            }
            if ($order->get('Order Customer Client Key') > 0) {
                $customer_client = get_object('CustomerClient', $order->get('Order Customer Client Key'));
                $customer_client->update_customer_client_orders();
            }

            $store->update_customers_data();
            $store->update_orders();

            $account->update_orders();


            break;


        case 'order_cancelled':

            $order    = get_object('Order', $data['order_key']);
            $customer = get_object('Customer', $order->get('Order Customer Key'));
            $store    = get_object('Store', $order->get('Order Store Key'));

            $sql = sprintf("SELECT `Transaction Type Key` FROM `Order No Product Transaction Fact` WHERE `Transaction Type`='Charges' AND   `Order Key`=%d  ", $order->id);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $charge = get_object('Charge', $row['Transaction Type Key']);
                    $charge->update_charge_usage();
                }
            }

            if ($customer->id) {
                $customer->update_orders();
                $customer->update_activity();
            }

            $store->update_customers_data();
            $store->update_orders();
            $account->update_orders();

            $deals     = array();
            $campaigns = array();
            $sql       = sprintf(
                "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d",
                $order->id
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $component = get_object('DealComponent', $row['Deal Component Key']);
                    $component->update_usage();
                    $deals[$row['Deal Key']]              = $row['Deal Key'];
                    $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
                }
            }


            foreach ($deals as $deal_key) {
                $deal = get_object('Deal', $deal_key);
                $deal->update_usage();
            }

            foreach ($campaigns as $campaign_key) {
                $campaign = get_object('DealCampaign', $campaign_key);
                $campaign->update_usage();
            }


            break;
        case 'website_launched':


            $website                = get_object('Website', $data['website_key']);
            $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
            $website->editor        = $data['editor'];


            $sql = sprintf(
                "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Scope`  IN ('Category Products','Category Categories')   ",
                $website->id
            );

            if ($result = $website->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage         = get_object('Webpage', $row['Page Key']);
                    $webpage->editor = $website->editor;


                    if ($webpage->get('Webpage State') == 'Ready') {
                        $webpage->publish();
                    }
                }
            }


            $sql = sprintf(
                "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Scope`  IN ('Product') AND `Webpage State`='Ready'  ",
                $website->id
            );

            if ($result = $website->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage         = get_object('Webpage', $row['Page Key']);
                    $webpage->editor = $website->editor;

                    if ($webpage->get('Webpage State') == 'Ready') {
                        $webpage->publish();
                    }
                }
            }


            break;

        case 'customer_created':

            $customer     = get_object('Customer', $data['customer_key']);
            $store        = get_object('Store', $customer->get('Customer Store Key'));
            $website_user = get_object('Website_User', $data['website_user_key']);

            $customer->editor     = $data['editor'];
            $store->editor        = $data['editor'];
            $website_user->editor = $data['editor'];


            if ($customer->get('Customer Tax Number') != '') {
                $customer->update_tax_number_valid('Auto');
            }


            $customer->update_location_type();
            $store->update_customers_data();

            if ($website_user->id) {
                $website = get_object('Website', $website_user->get('Website User Website Key'));

                $website->update_users_data();
            }


            $sql = sprintf(
                'select `Prospect Key` from `Prospect Dimension`  where `Prospect Store Key`=%d and `Prospect Main Plain Email`=%s and `Prospect Customer Key` is  NULL ',
                $customer->get('Store Key'),
                prepare_mysql($customer->get('Customer Main Plain Email'))

            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prospect         = get_object('Prospect', $row['Prospect Key']);
                    $prospect->editor = $data['editor'];
                    if ($prospect->id) {
                        $sql = sprintf('select `History Key`,`Type`,`Deletable`,`Strikethrough` from `Prospect History Bridge` where `Prospect Key`=%d ', $prospect->id);
                        if ($result2 = $db->query($sql)) {
                            foreach ($result2 as $row2) {
                                $sql = sprintf(
                                    "INSERT INTO `Customer History Bridge` VALUES (%d,%d,%s,%s,%s)",
                                    $customer->id,
                                    $row2['History Key'],
                                    prepare_mysql($row2['Deletable']),
                                    prepare_mysql($row2['Strikethrough']),
                                    prepare_mysql($row2['Type'])
                                );
                                //print "$sql\n";
                                $db->exec($sql);
                            }
                        }


                        $prospect->update_status('Registered', $customer);
                    }
                }
            }


            break;
        case 'customer_client_created':
        case 'customer_client_deleted':
            /** @var \Customer $customer */
            $customer = get_object('Customer', $data['customer_key']);

            $customer->update_clients_data();
            break;
        case 'update_web_state_slow_forks':


            $product = get_object('Product', $data['product_id']);

            if (isset($data['editor'])) {
                $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
                $product->editor        = $data['editor'];
            } else {
                $product->editor = $editor;
            }

            $product->update_web_state_slow_forks($data['web_availability_updated']);

            break;


        case 'full_after_part_stock_update':

            return true;

            /*

             $date = gmdate('Y-m-d H:i:s');
             $sql  = sprintf(
                 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d)
                       ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                 prepare_mysql($date),
                 prepare_mysql($date),
                 prepare_mysql('full_after_part_stock_update_legacy'),
                 $data['part_sku'],
                 prepare_mysql($date)

             );
             $db->exec($sql);

             */

            break;

        case 'update_part_products_availability':

            /**
             * @var $part \Part
             */

            $part = get_object('Part', $data['part_sku']);
            if ($part->id) {
                // print $part->get('Reference')."\n";

                if (isset($data['editor'])) {
                    $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
                    $part->editor           = $data['editor'];
                } else {
                    $part->editor = $editor;
                }


                $part->update_available_forecast();
                $part->update_stock_status();

                foreach ($part->get_products('objects') as $product) {
                    if (isset($data['editor'])) {
                        $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
                        $product->editor        = $data['editor'];
                    } else {
                        $product->editor = $editor;
                    }

                    $product->fork = true;

                    $product->update_availability(false);
                }
            }

            break;
        case 'product_availability':
            $product         = get_object('Product', $data['product_id']);
            $product->editor = $editor;
            $product->fork   = true;
            $product->update_availability(false);


            break;

        case 'payment_added_order':


            // $order    = get_object('Order', $data['order_key']);

            $store = get_object('Store', $data['store_key']);
            $store->update_orders();


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE `Order Key`=%d  ',
                $data['order_key']
            );
            // print "$sql\n";
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                    //   print $part->get('Reference')."\n";
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;
        case 'delivery_note_created':


            $store = get_object('Store', $data['store_key']);


            $store->load_acc_data();
            $account->load_acc_data();

            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();

            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();

            $store->update_orders();
            $account->update_orders();


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE  `Delivery Note Key`=%d  ',
                $data['delivery_note_key']
            );
            // print "$sql\n";

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                    // print $part->get('Reference')."\n";
                }
            }

            $delivery_note = get_object('Delivery Note', $data['delivery_note_key']);

            $delivery_note->update_shippers_services();


            break;

        case 'replacement_created':
            $order = get_object('Order', $data['order_key']);
            $store = get_object('Store', $order->get('Store Key'));
            $store->load_acc_data();


            $store->update_orders();
            $account->update_orders();


            $account->load_acc_data();
            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();


            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();

            $delivery_note = get_object('Delivery Note', $data['delivery_note_key']);

            $delivery_note->update_shippers_services();


            break;

        case 'order_submitted_by_client':
            $order    = get_object('Order', $data['order_key']);
            $website  = get_object('Website', $data['website_key']);
            $customer = get_object('Customer', $data['customer_key']);
            /** @var $store \Store */
            $store = get_object('Store', $customer->get('Customer Store Key'));

            $customer->editor = $editor;


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE `Order Key`=%d  ',
                $order->id
            );
            // print "$sql\n";
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                }
            }


            $email_template_type      = get_object('Email_Template_Type', 'Order Confirmation|'.$website->get('Website Store Key'), 'code_store');
            $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
            $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


            $send_data = array(
                'Email_Template_Type' => $email_template_type,
                'Email_Template'      => $email_template,
                'Order'               => $order,
                'Order Info'          => $data['order_info'],
                'Pay Info'            => $data['pay_info']

            );


            $published_email_template->send($customer, $send_data);
            if ($published_email_template->sent) {
                $sql = sprintf(
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)',
                    $order->id,
                    $published_email_template->email_tracking->id,
                    prepare_mysql('Order Notification')
                );

                $db->exec($sql);
            }


            $smarty               = new Smarty();
            $smarty->caching_type = 'redis';
            $base                 = '';
            $smarty->setTemplateDir($base.'templates');
            $smarty->setCompileDir($base.'server_files/smarty/templates_c');
            $smarty->setCacheDir($base.'server_files/smarty/cache');
            $smarty->setConfigDir($base.'server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');


            $recipients = $store->get_notification_recipients_objects('New Order');


            if (count($recipients) > 0) {
                $email_template_type      = get_object('Email_Template_Type', 'New Order|'.$website->get('Website Store Key'), 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'order_key'           => $order->id,
                    'customer_key'        => $customer->id,

                );


                foreach ($recipients as $recipient) {
                    $published_email_template->send($recipient, $send_data, $smarty);
                }
            }

            $customer->update_orders();
            $customer->update_activity();

            break;
        case 'order_completed':
            /** @var Order $order */
            $order = get_object('Order', $data['order_key']);


            $store = get_object('Store', $order->get('Store Key'));


            $store->update_orders();
            $account->update_orders();

            $order->send_review_invitation();


            $customer         = get_object('Customer', $order->get('Order Customer Key'));
            $customer->editor = $editor;

            //todo send email invoice done


            $account->update_inventory_dispatched_data('ytd');
            $account->update_inventory_dispatched_data('qtd');
            $account->update_inventory_dispatched_data('all');

            break;
        case 'order_dispatched':
            /** @var Order $order */
            $order         = get_object('Order', $data['order_key']);
            $delivery_note = get_object('Delivery_Note', $data['delivery_note_key']);


            $store = get_object('Store', $order->get('Store Key'));


            $store->update_orders();
            $account->update_orders();

            $order->send_review_invitation();


            $customer         = get_object('Customer', $order->get('Order Customer Key'));
            $customer->editor = $editor;


            $marketplace = false;


            $sql  = "select `Order Source Type` from `Order Source Dimension` where `Order Source Key`=? ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $order->get('Order Source Key')
                ]
            );
            while ($row = $stmt->fetch()) {
                if ($row['Order Source Type'] == 'marketplace') {
                    $marketplace = true;
                }
            }


            if ($order->get('Order For Collection') == 'No' and !$marketplace) {
                $email_template_type      = get_object('Email_Template_Type', 'Delivery Confirmation|'.$order->get('Order Store Key'), 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'Order'               => $order,
                    'Delivery_Note'       => $delivery_note,

                );


                $published_email_template->send($customer, $send_data);


                if ($published_email_template->sent) {
                    $stmt = $db->prepare("INSERT INTO `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) VALUES (?, ?, ?)");
                    $stmt->execute(
                        array(
                            $order->id,
                            $published_email_template->email_tracking->id,
                            'Dispatch Notification'
                        )
                    );
                }
            }

            $account->update_inventory_dispatched_data('ytd');
            $account->update_inventory_dispatched_data('qtd');
            $account->update_inventory_dispatched_data('all');

            break;
        case 'invoice_created':
            $account->load_acc_data();

            update_invoice_products_sales_data($db, $account, $data);
            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_invoices();

            $store = get_object('Store', $customer->get('Store Key'));
            $store->update_invoices();


            require_once 'conf/timeseries.php';
            require_once 'class.Timeserie.php';

            $timeseries      = get_time_series_config();
            $timeseries_data = $timeseries['Customer'];
            foreach ($timeseries_data as $time_series_data) {
                $time_series_data['Timeseries Parent']     = 'Customer';
                $time_series_data['Timeseries Parent Key'] = $customer->id;
                $time_series_data['editor']                = $editor;


                $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                $customer->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));
            }

            $customer->index_elastic_search(
                $ES_hosts,
                false,
                [
                    'assets',
                    'assets_interval'
                ]
            );


            include_once 'class.google_drive.php';


            if ($account->properties('google_drive_folder_key')) {
                $google_drive = new google_drive($account, 'keyring/goggle_drive.'.DNS_ACCOUNT_CODE.'.token.json');

                $invoice = get_object('Invoice', $data['invoice_key']);
                $invoice->upload_pdf_to_google_drive($google_drive);
            }

            break;

        case 'update_warehouse_leakages':

            $warehouse = get_object('warehouse', $data['warehouse_key']);
            $warehouse->update_current_timeseries_record('WarehouseStockLeakages');
            break;

        case 'update_poll_data':

            $poll = get_object('Customer_Poll_Query', $data['poll_key']);
            $poll->update_answers();
            break;

        case 'update_poll_option_data':

            /**
             * @var $poll_option \Customer_Poll_Query_Option
             */ $poll_option = get_object('Customer_Poll_Query_Option', $data['poll_option_key']);
            $poll_option->update_poll_query_option_customers();
            $poll = get_object('Customer_Poll_Query', $poll_option->get('Customer Poll Query Option Query Key'));
            $poll->update_answers();
            break;


        case 'update_sent_emails_data':

            if (!empty($data['email_mailshot_key'])) {
                $email_campaign = get_object('email_campaign', $data['email_mailshot_key']);
                $email_campaign->update_sent_emails_totals();

                include_once 'utils/send_zqm_message.class.php';
                send_zqm_message(
                    json_encode(
                        array(
                            'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                            'objects' => array(
                                array(
                                    'object' => 'mailshot',
                                    'key'    => $email_campaign->id,

                                    'update_metadata' => array(
                                        'class_html' => array(
                                            '_Sent_Emails_Info'    => $email_campaign->get('Sent Emails Info'),
                                            '_Email_Campaign_Sent' => $email_campaign->get('Sent'),
                                        )
                                    )

                                )

                            ),

                            'tabs' => array(

                                array(
                                    'tab'        => 'email_campaign_type.mailshots',
                                    'parent'     => 'store',
                                    'parent_key' => $email_campaign->get('Email Campaign Store Key'),
                                    'cell'       => array(
                                        'date_'.$email_campaign->id  => strftime("%a, %e %b %Y %R", strtotime($email_campaign->get('Email Campaign Last Updated Date')." +00:00")),
                                        'state_'.$email_campaign->id => $email_campaign->get('State'),
                                        'sent_'.$email_campaign->id  => $email_campaign->get('Sent')
                                    )


                                ),

                            ),


                        )
                    )
                );
            }

            if (!empty($data['email_template_key'])) {
                $email_template = get_object('email_template', $data['email_template_key']);
                $email_template->update_sent_emails_totals();
            }
            if (!empty($data['email_template_type_key'])) {
                $email_template_type = get_object('email_template_type', $data['email_template_type_key']);
                $email_template_type->update_sent_emails_totals();
            }


            break;

        case 'send_mailshot':


            $email_campaign         = get_object('email_campaign', $data['mailshot_key']);
            $email_campaign->editor = $data['editor'];

            if ($email_campaign->id) {
                $email_campaign->update_estimated_recipients();
                $email_campaign->send_mailshot();
            }
            break;

        case 'resume_mailshot':


            $mailshot = get_object('mailshot', $data['mailshot_key']);


            if ($mailshot->id) {
                $max_thread = 1;

                $sql = "select `Email Tracking Thread` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=?  and `Email Tracking State`='Ready' group by `Email Tracking Thread`";

                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        $mailshot->id
                    )
                );
                while ($row = $stmt->fetch()) {
                    $client        = new GearmanClient();
                    $fork_metadata = json_encode(
                        array(
                            'code' => addslashes($account->get('Code')),
                            'data' => array(
                                'mailshot' => $mailshot->id,
                                'thread'   => $row['Email Tracking Thread'],
                            )
                        )
                    );
                    include_once 'keyring/au_deploy_conf.php';
                    $servers = explode(",", GEARMAN_SERVERS);
                    shuffle($servers);
                    $servers = implode(",", $servers);
                    $client->addServers($servers);
                    $client->doBackground('au_send_mailshot', $fork_metadata);

                    if ($row['Email Tracking Thread'] >= $max_thread) {
                        $max_thread = $row['Email Tracking Thread'] + 1;
                    }
                }


                $mailshot->send_mailshot($max_thread);
            }
            break;


        case 'delivery_note_packed_done':


            $customer = get_object('Customer', $data['customer_key']);

            $customer->update_part_bridge();

            $dn = get_object('DeliveryNote', $data['delivery_note_key']);
            $dn->update_picking_packing_bands();

            $suppliers            = array();
            $suppliers_categories = array();
            $part_categories      = array();


            $sql = sprintf('select `Part SKU`,`Inventory Transaction Type`  FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d   ', $data['delivery_note_key']);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Inventory Transaction Type'] == 'Sale') {
                        $part = get_object('Part', $row['Part SKU']);


                        if ($part->id) {
                            $date = gmdate('Y-m-d H:i:s');
                            $sql  = sprintf(
                                'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                                prepare_mysql($date),
                                prepare_mysql($date),
                                prepare_mysql('part_sales'),
                                $part->id,
                                prepare_mysql($date)

                            );
                            $db->exec($sql);


                            foreach ($part->get_suppliers() as $suppliers_key) {
                                $suppliers[$suppliers_key] = $suppliers_key;
                            }
                            foreach ($part->get_categories() as $part_category_key) {
                                $part_categories[$part_category_key] = $part_category_key;
                            }
                        }
                    }
                }
            }


            foreach ($part_categories as $part_category_key) {
                $date = gmdate('Y-m-d H:i:s');
                $sql  = sprintf(
                    'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                    prepare_mysql($date),
                    prepare_mysql($date),
                    prepare_mysql('part_category_sales'),
                    $part_category_key,
                    prepare_mysql($date)

                );
                $db->exec($sql);
            }


            foreach ($suppliers as $supplier_key) {
                $supplier = get_object('Supplier', $supplier_key);

                if ($supplier->id) {
                    $date = gmdate('Y-m-d H:i:s');
                    $sql  = sprintf(
                        'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                        prepare_mysql($date),
                        prepare_mysql($date),
                        prepare_mysql('supplier_sales'),
                        $supplier->id,
                        prepare_mysql($date)

                    );
                    $db->exec($sql);

                    foreach ($supplier->get_categories() as $supplier_category_key) {
                        $suppliers_categories[$supplier_category_key] = $supplier_category_key;
                    }
                }
            }

            foreach ($suppliers_categories as $supplier_category_key) {
                $date = gmdate('Y-m-d H:i:s');
                $sql  = sprintf(
                    'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                    prepare_mysql($date),
                    prepare_mysql($date),
                    prepare_mysql('supplier_category_sales'),
                    $supplier_category_key,
                    prepare_mysql($date)

                );
                $db->exec($sql);
            }


            break;


        case 'delivery_note_cancelled':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);
            //print $delivery_note->get('Delivery Note ID')."\n";

            $store = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
            $store->load_acc_data();

            $shipper = get_object('Shipper', $delivery_note->get('Delivery Note Shipper Key'));
            $shipper->update_shipper_usage();


            //update_cancelled_delivery_note_products_sales_data sectoion
            include_once 'class.PartLocation.php';

            $returned_parts = array();

            foreach ($data['returned_part_locations'] as $returned_part_locations) {
                $part_location = get_object('Part_Location', $returned_part_locations);
                $part_location->update_stock();

                $returned_parts[$part_location->part->id] = $part_location->part->id;
            }

            if (count($returned_parts) > 0) {
                $customer = get_object('Customer', $data['customer_key']);
                $customer->update_part_bridge();


                if ($data['date'] == gmdate('Y-m-d')) {
                    $intervals = array(
                        'Total',
                        'Year To Day',
                        'Quarter To Day',
                        'Month To Day',
                        'Week To Day',
                        'Today',
                        '1 Year',
                        '1 Month',
                        '1 Week',


                    );
                } else {
                    $intervals = array(
                        'Total',
                        'Year To Day',
                        'Quarter To Day',
                        'Month To Day',
                        'Week To Day',
                        'Today',
                        '1 Year',
                        '1 Month',
                        '1 Week',
                        'Yesterday',
                        //todo don't calculate the ones not applicable
                        'Last Week',
                        //todo don't calculate the ones not applicable
                        'Last Month'
                        //todo don't calculate the ones not applicable

                    );
                }


                require_once 'conf/timeseries.php';
                require_once 'class.Timeserie.php';

                $timeseries = get_time_series_config();


                $suppliers            = array();
                $suppliers_categories = array();
                $part_categories      = array();


                foreach ($returned_parts as $part_sku) {
                    $part = get_object('Part', $part_sku);


                    foreach ($intervals as $interval) {
                        $part->update_sales_from_invoices($interval, true, false);
                    }

                    if ($data['date'] != gmdate('Y-m-d')) {
                        //todo don't calculate the ones not applicable
                        $part->update_previous_quarters_data();
                        $part->update_previous_years_data();
                    }

                    foreach ($part->get_suppliers() as $suppliers_key) {
                        $suppliers[$suppliers_key] = $suppliers_key;
                    }
                    foreach ($part->get_categories() as $part_category_key) {
                        $part_categories[$part_category_key] = $part_category_key;
                    }
                }


                foreach ($part_categories as $part_category_key) {
                    $category = get_object('Category', $part_category_key);
                    if ($category->get('Category Branch Type') != 'Root') {
                        foreach ($intervals as $interval) {
                            $category->update_sales_from_invoices($interval, true, false);
                        }
                    }

                    if ($data['date'] != gmdate('Y-m-d')) {
                        //todo don't calculate the ones not applicable
                        $category->update_part_category_previous_quarters_data();
                        $category->update_part_category_previous_years_data();
                    }

                    $timeseries_data = $timeseries['PartCategory'];
                    foreach ($timeseries_data as $time_series_data) {
                        $time_series_data['Timeseries Parent']     = 'Category';
                        $time_series_data['Timeseries Parent Key'] = $category->id;
                        $time_series_data['editor']                = $editor;


                        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                        $category->update_part_timeseries_record($object_timeseries, $data['date'], gmdate('Y-m-d'));
                    }
                }


                foreach ($suppliers as $supplier_key) {
                    $supplier = get_object('Supplier', $supplier_key);


                    $timeseries_data = $timeseries['Supplier'];
                    foreach ($timeseries_data as $time_series_data) {
                        $time_series_data['Timeseries Parent']     = 'Supplier';
                        $time_series_data['Timeseries Parent Key'] = $supplier->id;
                        $time_series_data['editor']                = $editor;


                        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                        $supplier->update_timeseries_record($object_timeseries, $data['date'], gmdate('Y-m-d'));
                    }


                    foreach ($intervals as $interval) {
                        $supplier->update_sales_from_invoices($interval, true, false);

                        if ($data['date'] != gmdate('Y-m-d')) {
                            //todo don't calculate the ones not applicable
                            $supplier->update_previous_quarters_data();
                            $supplier->update_previous_years_data();
                        }
                    }
                    foreach ($supplier->get_categories() as $supplier_category_key) {
                        $suppliers_categories[$supplier_category_key] = $supplier_category_key;
                    }
                }

                foreach ($suppliers_categories as $supplier_category_key) {
                    $category = get_object('Category', $supplier_category_key);
                    if ($category->get('Category Branch Type') != 'Root') {
                        foreach ($intervals as $interval) {
                            $category->update_sales_from_invoices($interval, true, false);
                        }
                        if ($data['date'] != gmdate('Y-m-d')) {
                            //todo don't calculate the ones not applicable
                            $category->update_part_category_previous_quarters_data();
                            $category->update_part_category_previous_years_data();
                        }
                    }

                    // todo supplier categories timeseries still not done
                    /*
                    $timeseries_data = $timeseries['PartCategory'];
                    foreach ($timeseries_data as $time_series_data) {


                        $time_series_data['Timeseries Parent']     = 'Category';
                        $time_series_data['Timeseries Parent Key'] = $category->id;
                        $time_series_data['editor']                = $editor;


                        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                        $category->update_supplier_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


                    }
                    */
                }
            }


            $account->load_acc_data();
            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();
            $account->update_orders();
            $store->update_orders();
            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();


            break;
        case 'update_ISF':


            $date = gmdate('Y-m-d H:i:s');


            $sql = sprintf(
                'insert into `Stack BiKey Dimension` (`Stack BiKey Creation Date`,`Stack BiKey Last Update Date`,`Stack BiKey Operation`,`Stack BiKey Object Key One`,`Stack BiKey Object Key Two`) values (%s,%s,%s,%d,%d) 
                      ON DUPLICATE KEY UPDATE `Stack BiKey Last Update Date`=%s ,`Stack BiKey Counter`=`Stack BiKey Counter`+1 ',
                prepare_mysql($date),
                prepare_mysql($date),
                prepare_mysql('update_part_location_isf'),
                $data['part_sku'],
                $data['location_key'],
                prepare_mysql($date)

            );
            $db->exec($sql);


            break;


        case 'start_purge':

            $purge         = get_object('purge', $data['purge_key']);
            $purge->editor = $editor;
            if ($purge->id) {
                $purge->sockets = get_zqm_message_sockets();
                $purge->purge();
            }
            break;
        case 'supplier_delivery_state_changed':


            $supplier_delivery = get_object('supplier_delivery', $data['supplier_delivery_key']);


            if ($supplier_delivery->get('Supplier Delivery Purchase Order Key')) {
                $po = get_object('purchase_order', $supplier_delivery->get('Supplier Delivery Purchase Order Key'));

                $po->editor = $editor;
                if ($po->id) {
                    $po->update_totals();
                }
            }


            break;
        case 'update_parts_next_delivery':
            $po         = get_object('purchase_order', $data['po_key']);
            $po->editor = $editor;
            if ($po->id) {
                $sql = "SELECT `Supplier Part Key` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=?";

                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        $po->id
                    )
                );
                while ($row = $stmt->fetch()) {
                    $supplier_part         = get_object('SupplierPart', $row['Supplier Part Key']);
                    $supplier_part->editor = $editor;
                    if (isset($supplier_part->part)) {
                        $supplier_part->part->update_next_deliveries_data();
                    }
                }
            }


            break;

        case 'update_active_parts_commercial_value':

            $sql = "SELECT `Part SKU` FROM `Part Dimension`  where `Part Status` ='In Use' ORDER BY `Part SKU` desc";

            $stmt = $db->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $part = get_object('Part', $row['Part SKU']);
                $part->update_commercial_value();
            }


            break;

        case 'supplier_part_unit_costs_updated':
            //todo move to vip_aurora workers


            $purchase_orders = array();


            $sql = sprintf(
                'select `Purchase Order Key`,`Purchase Order Transaction Fact Key` ,`Supplier Part Unit Cost`,`Purchase Order Ordering Units`,`Supplier Part Unit Extra Cost` from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`) where `Purchase Order Transaction State`="InProcess" and SPD.`Supplier Part Key`=%d ',
                $data['supplier_part_key']
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        'update `Purchase Order Transaction Fact` set `Purchase Order Net Amount`=%.2f ,`Purchase Order Extra Cost Amount`=%.2f where `Purchase Order Transaction Fact Key`=%d  ',

                        $row['Supplier Part Unit Cost'] * $row['Purchase Order Ordering Units'],
                        $row['Supplier Part Unit Extra Cost'] * $row['Purchase Order Ordering Units'],
                        $row['Purchase Order Transaction Fact Key']
                    );

                    //print "$sql\n";
                    $db->exec($sql);

                    $purchase_orders[$row['Purchase Order Key']] = $row['Purchase Order Key'];
                }
            }


            foreach ($purchase_orders as $purchase_order_key) {
                /**
                 * @var $purchase_order \PurchaseOrder
                 */
                $purchase_order = get_object('Purchase Order', $purchase_order_key);
                if ($purchase_order->id) {
                    $purchase_order->update_totals();
                }
            }


            break;


        case 'product_created':

            $product = get_object('product', $data['product_id']);
            $store   = get_object('store', $product->get('Product Store Key'));

            foreach ($product->get_parts('objects') as $part) {
                $part->update_products_data();
                $part->update_commercial_value();
            }
            $store->update_product_data();
            $store->update_new_products();

            break;
        case 'product_part_list_updated':

            $product = get_object('product', $data['product_id']);


            $product->fast_update(array('Product XHTML Parts' => $product->get('Parts')));


            $product->update_availability();
            $product->update_cost();
            $product->updating_packing_data();


            foreach ($product->get_parts('objects') as $part) {
                $part->update_products_data();
                $part->update_commercial_value();
            }

            break;
        case 'update_product_webpages':

            $product = get_object('product', $data['product_id']);


            if ($product->id) {
                $product->editor = $data['editor'];
                $product->update_webpages($data['scope']);
            }

            break;
        case 'product_price_updated':

            $product          = get_object('product', $data['product_id']);
            $states_to_change = "'In Process','Out of Stock in Basket'";
            $sql              = sprintf(
                "SELECT `Order Key`,`Delivery Note Key`,`Order Quantity`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF  WHERE `Product ID`=%d   AND `Product Key`!=%d AND  `Current Dispatching State` IN (%s) AND `Invoice Key` IS NULL ",
                $product->id,
                $product->get('Product Current Key'),
                $states_to_change

            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $sql = "UPDATE `Order Transaction Fact` SET  `Product Key`=?,  `Order Transaction Gross Amount`=?, `Order Transaction Total Discount Amount`=0	, `Order Transaction Amount`=?  WHERE `Order Transaction Fact Key`=?";

                    $db->prepare($sql)->execute(
                        array(
                            $product->get('Product Current Key'),
                            round($product->get('Product Price') * $row['Order Quantity'], 2),
                            round($product->get('Product Price') * $row['Order Quantity'], 2),
                            $row['Order Transaction Fact Key']
                        )
                    );


                    $order          = get_object('Order', $row['Order Key']);
                    $old_used_deals = $order->get_used_deals();


                    $order->update_discounts_items();
                    $order->update_totals();
                    $order->update_shipping($row['Delivery Note Key'], false);
                    $order->update_charges($row['Delivery Note Key'], false);
                    $order->update_discounts_no_items($row['Delivery Note Key']);
                    $order->update_deal_bridge();
                    $order->update_totals();


                    $new_used_deals = $order->get_used_deals();


                    $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
                    $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

                    $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
                    $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

                    $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
                    $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));

                    $date = gmdate('Y-m-d H:i:s');

                    foreach ($campaigns_diff as $campaign_key) {
                        if ($campaign_key > 0) {
                            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

                            $db->prepare($sql)->execute(
                                [
                                    $date,
                                    $date,
                                    'deal_campaign',
                                    $campaign_key,
                                    $date,

                                ]
                            );
                        }
                    }

                    foreach ($deal_diff as $deal_key) {
                        if ($deal_key > 0) {
                            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                            $db->prepare($sql)->execute(
                                [
                                    $date,
                                    $date,
                                    'deal',
                                    $deal_key,
                                    $date,

                                ]
                            );
                        }
                    }

                    foreach ($deal_components_diff as $deal_component_key) {
                        if ($deal_component_key > 0) {
                            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                            $db->prepare($sql)->execute(
                                [
                                    $date,
                                    $date,
                                    'deal_component',
                                    $deal_component_key,
                                    $date,

                                ]
                            );
                        }
                    }
                }
            }


            break;


        case 'webpage_published':


            $webpage = get_object('Webpage', $data['webpage_key']);


            $webpage_type = get_object('Webpage_Type', $webpage->get('Webpage Type Key'));
            $webpage_type->update_number_webpages();

            $webpage->clear_cache();


            $redis = new Redis();


            if ($redis->connect(REDIS_HOST, REDIS_PORT)) {
                $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$webpage->get('Webpage Website Key').'_'.$webpage->get('Webpage Code');
                $redis->set($url_cache_key, $webpage->id);
                $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$webpage->get('Webpage Website Key').'_'.strtoupper($webpage->get('Webpage Code'));
                $redis->set($url_cache_key, $webpage->id);
                $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$webpage->get('Webpage Website Key').'_'.strtolower($webpage->get('Webpage Code'));
                $redis->set($url_cache_key, $webpage->id);
            }


            break;

        case 'update_part_location_stock':

            $part_location = get_object('Part_Location', $data['part_sku'].'_'.$data['location_key']);
            $part_location->update_stock();
            break;

        case 'invoice_deleted':

            $invoice = get_object('Invoice_deleted', $data['invoice_key']);
            /** @var $store \Store */

            $store = get_object('Store', $data['store_key']);
            $store->update_invoices();

            $smarty               = new Smarty();
            $smarty->caching_type = 'redis';
            $base                 = '';
            $smarty->setTemplateDir($base.'templates');
            $smarty->setCompileDir($base.'server_files/smarty/templates_c');
            $smarty->setCacheDir($base.'server_files/smarty/cache');
            $smarty->setConfigDir($base.'server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');

            $recipients = $store->get_notification_recipients_objects('Invoice Deleted');

            if (count($recipients) > 0) {
                $email_template_type      = get_object('Email_Template_Type', 'Invoice Deleted|'.$store->id, 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'invoice_key'         => $invoice->id,
                    'user_key'            => $invoice->get('Invoice Deleted User Key'),

                );


                foreach ($recipients as $recipient) {
                    $published_email_template->send($recipient, $send_data, $smarty);
                }
            }

            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_invoices();
            $customer->index_elastic_search(
                $ES_hosts,
                false,
                [
                    'assets',
                    'assets_interval'
                ]
            );

            break;
        case 'delivery_note_dispatched':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);
            $store         = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
            $store->load_acc_data();

            $shipper = get_object('Shipper', $delivery_note->get('Delivery Note Shipper Key'));
            $shipper->update_shipper_usage();


            $account->load_acc_data();
            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();
            $account->update_orders();
            $store->update_orders();
            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();


            $smarty               = new Smarty();
            $smarty->caching_type = 'redis';
            $base                 = '';
            $smarty->setTemplateDir($base.'templates');
            $smarty->setCompileDir($base.'server_files/smarty/templates_c');
            $smarty->setCacheDir($base.'server_files/smarty/cache');
            $smarty->setConfigDir($base.'server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');

            $recipients = $store->get_notification_recipients_objects('Delivery Note Dispatched');

            if (count($recipients) > 0) {
                $email_template_type      = get_object('Email_Template_Type', 'Delivery Note Dispatched|'.$store->id, 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'delivery_note_key'   => $delivery_note->id,

                );


                foreach ($recipients as $recipient) {
                    $published_email_template->send($recipient, $send_data, $smarty);
                }
            }


            break;
        case 'delivery_note_un_dispatched':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);

            /** @var $store \Store */
            $store = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
            $store->load_acc_data();

            $shipper = get_object('Shipper', $delivery_note->get('Delivery Note Shipper Key'));
            $shipper->update_shipper_usage();


            $smarty               = new Smarty();
            $smarty->caching_type = 'redis';
            $base                 = '';
            $smarty->setTemplateDir($base.'templates');
            $smarty->setCompileDir($base.'server_files/smarty/templates_c');
            $smarty->setCacheDir($base.'server_files/smarty/cache');
            $smarty->setConfigDir($base.'server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');

            $recipients = $store->get_notification_recipients_objects('Delivery Note Undispatched');

            if (count($recipients) > 0) {
                $email_template_type      = get_object('Email_Template_Type', 'Delivery Note Undispatched|'.$store->id, 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'delivery_note_key'   => $delivery_note->id,
                    'user_key'            => $data['user_key'],
                    'note'                => $data['note'],

                );


                foreach ($recipients as $recipient) {
                    $published_email_template->send($recipient, $send_data, $smarty);
                }
            }

            $account->load_acc_data();
            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();
            $account->update_orders();
            $store->update_orders();
            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();

            break;


        case 'customer_registered':

            $customer = get_object('customer', $data['customer_key']);
            $website  = get_object('website', $data['website_key']);


            $store = get_object('Store', $customer->get('Customer Store Key'));


            $smarty               = new Smarty();
            $smarty->caching_type = 'redis';
            $base                 = '';
            $smarty->setTemplateDir($base.'templates');
            $smarty->setCompileDir($base.'server_files/smarty/templates_c');
            $smarty->setCacheDir($base.'server_files/smarty/cache');
            $smarty->setConfigDir($base.'server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');


            $email_template_type      = get_object('Email_Template_Type', 'Registration|'.$website->get('Website Store Key'), 'code_store');
            $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
            $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


            $send_data = array(
                'Email_Template_Type' => $email_template_type,
                'Email_Template'      => $email_template,

            );

            $published_email_template->send($customer, $send_data);


            $recipients = $store->get_notification_recipients_objects('New Customer');


            if (count($recipients) > 0) {
                $email_template_type      = get_object('Email_Template_Type', 'New Customer|'.$website->get('Website Store Key'), 'code_store');
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'customer_key'        => $customer->id,

                );


                foreach ($recipients as $recipient) {
                    $published_email_template->send($recipient, $send_data, $smarty);
                }
            }


            break;


        case 'customer_approval_done':

            $customer = get_object('customer', $data['customer_key']);

            $email_template_type = get_object('Email_Template_Type', ($data['email_template_code']).'|'.$customer->get('Customer Store Key'), 'code_store');
            if ($email_template_type->id and $email_template_type->get('Email Campaign Type Status') == 'Active') {
                $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
                $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));
                if ($published_email_template->id) {
                    $send_data = array(
                        'Email_Template_Type' => $email_template_type,
                        'Email_Template'      => $email_template,

                    );

                    $published_email_template->send($customer, $send_data);
                }
            }

            break;


        case 'update_marketing_customers':


            if ($data['object'] == 'category') {
                $category = get_object('Category', $data['key']);

                switch ($data['tipo']) {
                    case 'targeted':
                        $category->update_product_category_targeted_marketing_customers();
                        $_customers      = ' ('.$category->properties('targeted_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_targeted_mailshot' => _('Create precision mailshot').$_customers);
                        break;
                    case 'spread':
                        $category->update_product_category_spread_marketing_customers();
                        $_customers      = ' ('.$category->properties('spread_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_spread_mailshot' => _('Create mail bomb').$_customers);
                        break;
                    case 'donut':
                        $category->update_product_category_donut_marketing_customers();
                        $_customers      = ' ('.$category->properties('donut_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_donut_mailshot' => _('Create donut mailshot').$_customers);
                        break;
                }
            } elseif ($data['object'] == 'product') {
                /** @var Product $product */
                $product = get_object('Product', $data['key']);

                switch ($data['tipo']) {
                    case 'targeted':
                        $product->update_product_targeted_marketing_customers();
                        $_customers      = ' ('.$product->properties('targeted_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_targeted_mailshot' => _('Create precision mailshot').$_customers);
                        break;
                    case 'spread':
                        $product->update_product_spread_marketing_customers();
                        $_customers      = ' ('.$product->properties('spread_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_spread_mailshot' => _('Create mail bomb').$_customers);

                        break;
                    case 'donut':
                        $product->update_product_donut_marketing_customers();
                        $_customers      = ' ('.$product->properties('donut_marketing_customers').' '._('customers').')';
                        $update_metadata = array('new_donut_mailshot' => _('Create donut mailshot').$_customers);

                        break;
                }
            }

            include_once 'utils/send_zqm_message.class.php';
            send_zqm_message(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                        'objects' => array(
                            array(
                                'object'          => $data['object'],
                                'key'             => $data['key'],
                                'update_metadata' => array('titles' => $update_metadata)
                            )
                        )
                    )
                )
            );


            break;
        case 'customer_portfolio_changed':

            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_portfolio();
            if (!empty($data['product_id'])) {
                $customer->process_aiku_fetch('Customer', $data['product_id'], 'sync_portfolio');
            }

            break;
        case 'update_portfolio_aiku':

            $customer = get_object('Customer', $data['customer_key']);
            $customer->process_aiku_fetch('Customer', $data['product_id'], 'sync_portfolio');

            break;
        case 'unsubscribe_prospect':
            /**
             * @var $prospect \Prospect
             */

            $prospect = get_object('Prospect', $data['prospect_key']);

            $editor = array(
                'Author Name'  => $prospect->get('Name'),
                'Author Alias' => $prospect->get('Name'),
                'Author Type'  => 'Prospect',
                'Author Key'   => $prospect->id,
                'Subject'      => 'Prospect',
                'Subject Key'  => $prospect->id,
                'User Key'     => 0,
                'Date'         => $data['date']
            );


            $prospect->editor = $editor;
            $prospect->opt_out($data['date']);


            break;
        case 'deal_campaign_changed':

            /**
             * @var  $deal_campaign \DealCampaign
             */


            $deal_campaign = get_object('DealCampaign', $data['deal_campaign_key']);
            switch ($data['field']) {
                case 'Deal Campaign Name':

                    if ($deal_campaign->data['Deal Campaign Code'] == 'VL' or $deal_campaign->data['Deal Campaign Code'] == 'OR') {
                        $sql  = "select  `Deal Key` from   `Deal Dimension`  WHERE `Deal Campaign Key`=?";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(
                            array(
                                $deal_campaign->id
                            )
                        );
                        while ($row = $stmt->fetch()) {
                            $deal         = get_object('Deal', $row['Deal Key']);
                            $deal->editor = $editor;
                            $deal->update(
                                array(
                                    'Deal Name Label' => $deal_campaign->get('Deal Campaign Name')
                                )
                            );
                        }
                    }
                    break;
            }
            break;
        case 'refresh_cache_webpage_category_children':
            /**
             * @var $webpage \Page
             */

            $webpage = get_object('Webpage', $data['webpage_key']);

            $children = [];
            foreach ($webpage->get_category_children_webpage_keys() as $child_webpage_key) {
                $children[] = get_object('Webpage', $child_webpage_key);
            }

            chdir(AU_PATH);
            foreach ($children as $_webpage) {
                $_webpage->refresh_cache();
            }


            break;
        case 'order_replacements_updated':

            $order = get_object('Order', $data['order_key']);

            $store = get_object('Store', $order->get('Store Key'));
            $store->update_orders_in_warehouse_data();
            $account->update_orders_in_warehouse_data();


            if ($data['update_in_warehouse']) {
                $store->update_orders_in_warehouse_data();
                $account->update_orders_in_warehouse_data();
            }
            if ($data['update_packed_done']) {
                $store->update_orders_packed_done_data();
                $account->update_orders_packed_done_data();
            }
            if ($data['update_approved']) {
                $store->update_orders_approved_data();
                $account->update_orders_approved_data();
            }
            if ($data['update_dispatched_today']) {
                $store->update_orders_dispatched_today();
                $account->update_orders_dispatched_today();
            }

            break;

        case 'update_delivery_note_warehouse_state':
            $account->load_acc_data();
            $account->update_dispatching_time_data('1m');
            $account->update_sitting_time_in_warehouse();
            $account->update_orders();

            $store = get_object('Store', $data['store_key']);
            $store->load_acc_data();
            $store->update_orders();
            $store->update_dispatching_time_data('1m');
            $store->update_sitting_time_in_warehouse();

            break;

        case 'create_elastic_index_object':


            $object = get_object($data['object'], $data['object_key']);
            if ($object->id) {
                try {
                    $object->index_elastic_search($ES_hosts, false, $data['indices']);
                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }

            break;
        case 'delete_elastic_index_object':
            $object = get_object($data['object'], $data['object_key']);
            if ($object->id) {
                $object->delete_index_elastic_search($ES_hosts);
            }
            break;
        case 'clear_smarty_web_cache':
            $webpage = get_object('Webpage', $data['webpage_key']);
            chdir(AU_PATH);
            $webpage->clear_cache();
            break;
        case 'update_public_db':
            $image       = get_object('Image', $data['image_key']);
            $image->fork = true;
            $image->update_public_db();
            break;
        case 'add_barcode_range':

            include_once 'class.Barcode.php';

            $account->editor = $editor;

            $range = $data['range'];

            $_data['editor'] = $editor;

            $ws_key        = $data['ws_key'];
            $sockets       = get_zqm_message_sockets();
            $show_feedback = (float)microtime(true) + .250;


            $added      = 0;
            $duplicated = 0;
            $error      = 0;

            for ($i = $range[0]; $i <= $range[1]; $i++) {
                $_data['Barcode Number']    = $i;
                $_data['Barcode Used From'] = gmdate('Y-m-d H:i:s');
                $_data['editor']['Date']    = gmdate('Y-m-d H:i:s');

                $barcode = new Barcode('find', $_data, 'create');
                if ($barcode->new) {
                    $added++;
                } elseif ($barcode->found) {
                    $duplicated++;
                } else {
                    $error++;
                }


                if (microtime(true) > $show_feedback) {
                    $msg = _('Adding barcodes');
                    $msg .= ' <span style="font-weight: bold" class="'.($added > 0 ? 'success' : '').'">'.sprintf(ngettext('%s barcode added', '%s barcodes added', $added), number($added)).'</span>';
                    if ($duplicated > 0) {
                        $msg .= ' <span class="error">'.sprintf(ngettext('%s duplicated', '%s duplicates', $duplicated), number($duplicated)).'</span>';
                    }
                    if ($error > 0) {
                        $msg .= ' <span class="error">'.sprintf(ngettext('%s error', '%s errors', $error), number($error)).'</span>';
                    }
                    foreach ($sockets as $socket) {
                        $socket->send(
                            json_encode(
                                array(
                                    'channel' => $ws_key,
                                    'id_html' => array(
                                        'inline_new_object_msg' => $msg


                                    ),


                                )
                            )
                        );
                    }

                    $show_feedback = (float)microtime(true) + .250;
                }
            }


            $msg = '<span class="success"><i class="fa fa-check"></i> '._('Completed').'</span>';
            $msg .= ' <span style="font-weight: bold" class="success">'.sprintf(ngettext('%s barcode added', '%s barcodes added', $added), number($added)).'</span>';
            if ($duplicated > 0) {
                $msg .= ' <span class="error">'.sprintf(ngettext('%s duplicated', '%s duplicates', $duplicated), number($duplicated)).'</span>';
            }
            if ($error > 0) {
                $msg .= ' <span class="error">'.sprintf(ngettext('%s error', '%s errors', $error), number($error)).'</span>';
            }
            foreach ($sockets as $socket) {
                $socket->send(
                    json_encode(
                        array(
                            'channel' => $ws_key,
                            'id_html' => array(
                                'inline_new_object_msg' => $msg


                            ),


                        )
                    )
                );
            }


            break;
        case 'website_created':

            include_once 'class.Page.php';
            /** @var $website \Website */
            $website = get_object('Website', $data['website_key']);
            /** @var $store \Store */
            $store = get_object('Store', $website->get('Website Store Key'));


            $sql  = "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`=? and `Product Status`!='Discontinued' and `Product Public`='Yes' ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $store->id
                )
            );
            while ($row = $stmt->fetch()) {
                $website->create_product_webpage($row['Product ID']);
            }


            $store->update_websites_data();

            $account->update_stores_data();

            break;
        case 'update_operative_stats':


            $operative = get_object('Operative', $data['operative_key']);
            if ($operative->id) {
                $operative->update_operative_purchase_order_stats();
                $operative->update_operative_transaction_stats();
            }


            break;
        case 'update_production_job_orders_stats':


            $account->update_production_job_orders_stats();

            $sql  = "select `Purchase Order Transaction Operator Key` from `Purchase Order Transaction Fact` where `Purchase Order Key`=? group by `Purchase Order Transaction Operator Key` ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $data['po_key']
                )
            );
            while ($row = $stmt->fetch()) {
                $operative = get_object('Operative', $row['Purchase Order Transaction Operator Key']);
                $operative->update_operative_purchase_order_stats();
                $operative->update_operative_transaction_stats();
            }


            break;
        case 'update_supplier_part_on_demand':

            $supplier_part         = get_object('Supplier_Part', $data['supplier_part_key']);
            $supplier_part->editor = $data['editor'];
            $supplier_part->update(
                array('Supplier Part On Demand' => $data['value'])
            );


            break;
        case 'set_store_pricing_policy':
            $store = get_object('Store', $data['store_key']);

            $sql  = "select `Product ID` from `Product Dimension`  where `Product Store Key`=?  and `Product Status`!='Discontinued'   ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $store->id
                ]
            );
            while ($row = $stmt->fetch()) {
                $product = get_object('Product', $row['Product ID']);
                print $product->get('Code')."\n";
                $product->editor = $data['editor'];
                $product->update(['Product Pricing Policy Key' => $store->data['Store Default Product Pricing Policy Key']]);
            }


            break;
        default:
            break;
    }

    $db = null;

    return false;
}


