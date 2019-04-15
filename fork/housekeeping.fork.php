<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_housekeeping($job) {


    //print "fork_housekeeping  original skypping\n";

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $session) = $_data;

    print $data['type']."\n";
    //return true;
    switch ($data['type']) {

        case 'update_currency_exchange':


            include_once 'utils/currency_functions.php';

            $exchange=currency_conversion($db,$data['currency_from'],$data['currency_to']);

            print $exchange;

            break;

        case 'update_parts_inventory_snapshot_fact':


            //  print_r($data);

            foreach ($data['parts_data'] as $part_sku => $from_date) {
                $part = get_object('Part', $part_sku);
                $part->redo_inventory_snapshot_fact($from_date);
            }

            $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $warehouse = get_object('Warehouse', $row2['Warehouse Key']);
                    $warehouse->update_inventory_snapshot($data['all_parts_min_date'], gmdate('Y-m-d'));
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            break;
        case 'update_parts_stock_run':

            //  print_r($data);

            foreach ($data['parts_data'] as $part_sku => $from_date) {
                $part         = get_object('Part', $part_sku);
                $part->editor = $data['editor'];
                $part->update_stock_run();
                $part->redo_inventory_snapshot_fact($from_date);

                if ($account->get('Account Add Stock Value Type') == 'Last Price') {
                    // update if is last placement


                    $sql = sprintf(
                        'select `Date`,(`Inventory Transaction Amount`/`Inventory Transaction Quantity`) as value_per_sko from  `Inventory Transaction Fact` ITF  where  `Inventory Transaction Amount`>0 and `Inventory Transaction Quantity`>0 and  ( `Inventory Transaction Section`=\'In\' or ( `Inventory Transaction Type`=\'Adjust\' and `Inventory Transaction Quantity`>0 and `Location Key`>1 )  )  and ITF.`Part SKU`=%d  order by `Date` desc, FIELD(`Inventory Transaction Type`, \'In\',\'Adjust\')  limit 1 ',
                        $part->id
                    );

                    // print $sql;

                    if ($result = $db->query($sql)) {
                        foreach ($result as $row) {

                            //  print_r($row);

                            $part->update_field_switcher('Part Cost in Warehouse', $row['value_per_sko'], 'no_history');
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }


            }

            $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $warehouse = get_object('Warehouse', $row2['Warehouse Key']);
                    $warehouse->update_inventory_snapshot($data['all_parts_min_date'], gmdate('Y-m-d'));
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            break;

        case 'update_part_status':


            $part = get_object('Part', $data['part_sku']);

            $part->editor = $data['editor'];


            $part->update_stock_status();
            $part->update_available_forecast();


            $sql = sprintf(
                "SELECT `Category Key` FROM `Category Bridge` WHERE `Subject`='Part' AND `Subject Key`=%d", $part->sku
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


            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");


            switch ($part->get('Part Status')) {
                case 'In Use':
                    $part_status = sprintf('<i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-box title="%s"></i>', $part->sku, _('Active, click to discontinue'));
                    break;
                case 'Discontinuing':
                    $part_status = sprintf('<i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-skull" title="%s"></i>', $part->sku, _('Discontinuing, click to set as an active part'));
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


            $socket->send(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                        'tabs' => array(
                            array(
                                'tab'   => 'inventory.discontinuing_parts',
                                'rtext' => sprintf(ngettext('%s discontinuing part', '%s discontinuing parts', $account->get('Account Discontinuing Parts Number')), number($account->get('Account Discontinuing Parts Number'))),

                                'cell' => array(
                                    'part_status_'.$part->sku => $part_status
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
                       // $sql = sprintf(
                       //     'SELECT `Order Key`  FROM `Order Dimension` where  `Current Dispatching State`="In Process"  group by `Order Key`',
                       //     $deal->get('Deal Trigger Key')
                       // );
                        break;
                }


                if ($result = $db->query($sql)) {
                    foreach ($result as $row) {


                        $order = get_object('Order', $row['Order Key']);
                        $old_used_deals = $order->get_used_deals();
                        $order->update_totals();

                        $order->update_discounts_items();
                        $order->update_totals();

                        $order->update_shipping(false, false);
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

                            if($campaign_key>0){
                                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                print "$sql\n";
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
                            if($deal_key>0) {
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
                            if($deal_component_key>0) {
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

                            $order->update_shipping(false, false);
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

                                if($campaign_key>0){
                                    $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                                    print "$sql\n";
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
                                if($deal_key>0) {
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
                                if($deal_component_key>0) {
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
                $deal_component = get_object('DealComponent',$deal_component_key);
                $deal_component->update_usage();
            }

            foreach ($data['deals'] as $deal_key) {
                $deal = get_object('Deal',$deal_key);
                $deal->update_usage();
            }

            foreach ($data['campaigns'] as $campaign_key) {
                $campaign = get_object('DealCampaign',$campaign_key);
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


            $account = get_object('Account', '');
            $store   = get_object('Store', $payment->get('Payment Store Key'));

            $customer->update_payments();
            $store->update_orders();
            $store->update_payments();
            $account->update_orders();

            break;


        case 'update_orders_in_basket_data': // remove after migration
            $store   = get_object('Store', $data['store_key']);
            $account = get_object('Account', '');
            $store->update_orders_in_basket_data();
            $account->update_orders_in_basket_data();

            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $socket->send(
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
        case 'order_items_changed':
            $order = get_object('Order', $data['order_key']);



            $account = get_object('Account', '');
            $store   = get_object('Store', $order->get('Store Key'));


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
                case 'PackedDone':
                    $store->update_orders_packed_data();
                    $account->update_orders_packed_data();

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

            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $socket->send(
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
            $store = get_object('Store', $data['store_key']);
            $store->update_customers_data();

            break;
        case 'order_state_changed':
            $order   = get_object('Order', $data['order_key']);
            $account = get_object('Account', '');
            $store   = get_object('Store', $order->get('Store Key'));


            $store->update_orders();
            $account->update_orders();


            break;


        case 'order_created':
            include_once 'class.Order.php';
            include_once 'class.Customer.php';
            include_once 'class.Store.php';
            $order = new Order($data['subject_key']);

            $customer               = new Customer($order->get('Order Customer Key'));
            $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
            $customer->editor       = $data['editor'];
            $customer->add_history_new_order($order);
            $customer->update_orders();
            $store = new Store($order->get('Order Store Key'));


            $store->update_orders();
            $order->update_full_search();

            $account = get_object('Account', '');
            $account->update_orders();


            break;


        case 'order_cancelled':

            $order    = get_object('Order', $data['order_key']);
            $customer = get_object('Customer', $order->get('Order Customer Key'));
            $store    = get_object('Store', $order->get('Order Store Key'));
            $account  = get_object('Account', '');

            $sql = sprintf('SELECT `Transaction Type Key` FROM `Order No Product Transaction Fact` WHERE `Transaction Type`="Charges" AND   `Order Key`=%d  ', $order->id);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $charge = get_object('Charge', $row['Transaction Type Key']);
                    $charge->update_charge_usage();

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $customer->update_orders();
            $store->update_orders();
            $account->update_orders();

            $deals     = array();
            $campaigns = array();
            $sql       = sprintf(
                "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d", $order->id
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $component = get_object('DealComponent',$row['Deal Component Key']);
                    $component->update_usage();
                    $deals[$row['Deal Key']]              = $row['Deal Key'];
                    $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            foreach ($deals as $deal_key) {
                $deal = get_object('Deal',$deal_key);
                $deal->update_usage();
            }

            foreach ($campaigns as $campaign_key) {
                $campaign = get_object('DealCampaign',$campaign_key);
                $campaign->update_usage();
            }


            break;
        case 'website_launched':


            $website                = get_object('Website', $data['website_key']);
            $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
            $website->editor        = $data['editor'];


            $sql = sprintf(
                "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Scope`  IN ('Category Products','Category Categories') AND `Webpage State`='Ready'  ",
                $website->id
            );

            $sql = sprintf(
                "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Scope`  IN ('Category Products','Category Categories')   ",
                $website->id
            );

            if ($result = $website->db->query($sql)) {
                foreach ($result as $row) {

                    $webpage         = get_object('Webpage', $row['Page Key']);
                    $webpage->editor = $website->editor;

                    // print $webpage->get('Webpage Code')."\n";

                    if ($webpage->get('Webpage State') == 'Ready') {
                        $webpage->publish();

                    }


                }
            }


            $sql = sprintf(
                "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Scope`  IN ('Product') AND `Webpage State`='Ready'  ", $website->id
            );

            if ($result = $website->db->query($sql)) {
                foreach ($result as $row) {

                    $webpage         = get_object('Webpage', $row['Page Key']);
                    $webpage->editor = $website->editor;
                    // print $webpage->get('Webpage Code')." ** \n";

                    if ($webpage->get('Webpage State') == 'Ready') {
                        $webpage->publish();

                    }


                }
            }


            break;

        case 'customer_created_migration':
            //todo  delete when migrate

            $customer = get_object('Customer', $data['customer_key']);


            $sql = sprintf(
                'select `Prospect Key` from `Prospect Dimension`  where `Prospect Store Key`=%d and `Prospect Main Plain Email`=%s and `Prospect Customer Key` is  NULL ', $customer->get('Store Key'), prepare_mysql($customer->get('Customer Main Plain Email'))

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
                                    "INSERT INTO `Customer History Bridge` VALUES (%d,%d,%s,%s,%s)", $customer->id, $row2['History Key'], prepare_mysql($row2['Deletable']), prepare_mysql($row2['Strikethrough']), prepare_mysql($row2['Type'])
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


            $customer->update_full_search();
            $customer->update_location_type();
            $store->update_customers_data();

            if ($website_user->id) {
                $website = get_object('Website', $website_user->get('Website User Website Key'));

                $website->update_users_data();

            }


            $sql = sprintf(
                'select `Prospect Key` from `Prospect Dimension`  where `Prospect Store Key`=%d and `Prospect Main Plain Email`=%s and `Prospect Customer Key` is  NULL ', $customer->get('Store Key'), prepare_mysql($customer->get('Customer Main Plain Email'))

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
                                    "INSERT INTO `Customer History Bridge` VALUES (%d,%d,%s,%s,%s)", $customer->id, $row2['History Key'], prepare_mysql($row2['Deletable']), prepare_mysql($row2['Strikethrough']), prepare_mysql($row2['Type'])
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


        case 'update_web_state_slow_forks':

            include_once 'class.Product.php';
            $product = new Product('id', $data['product_id']);

            if (isset($data['editor'])) {
                $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
                $product->editor        = $data['editor'];
            } else {
                $product->editor = $editor;
            }

            $product->update_web_state_slow_forks($data['web_availability_updated']);

            break;


        case 'full_after_part_stock_update':

            // return true;

            //

            /*
                        $editor = array(


                            'Author Type'  => '',
                            'Author Key'   => '',
                            'User Key'     => 0,
                            'Date'         => gmdate('Y-m-d H:i:s'),
                            'Subject'=>'System',
                            'Subject Key'=>0,
                            'Author Name'=>'System (Stock change)',
                            'Author Alias' => 'System (Stock change)',
                            'v'=>3

            'email_tacking_ses','product_web_state_legacy','update_part_products_availability','part_stock_in_paid_orders','full_after_part_stock_update_legacy'

                        );
                        */

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

            return true;

            // todo remove after migration
            // for use in pre migration inikoo

            $part = get_object('Part', $data['part_sku']);

            if (isset($data['editor'])) {
                $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
                $part->editor           = $data['editor'];
            } else {
                $part->editor = $editor;
            }

            $part->activate();
            $part->discontinue_trigger();


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
                $product->update_availability(true);
            }

            break;

        case 'update_part_products_availability':

            $part = get_object('Part', $data['part_sku']);

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

            break;


        case 'order_payment_changed':
            // this can be removed after all inikoo gone


            $order = get_object('Order', $data['order_key']);

            $store = get_object('Store', $order->get('Order Store Key'));
            $store->update_orders();


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE `Order Key`=%d  ', $data['order_key']
            );
            // print "$sql\n";
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $date = gmdate('Y-m-d H:i:s');
                    $sql  = sprintf(
                        'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                        prepare_mysql($date),
                        prepare_mysql($date),
                        prepare_mysql('part_stock_in_paid_orders'),
                        $row['Product Part Part SKU'],
                        prepare_mysql($date)

                    );
                    $db->exec($sql);

                    // $part = get_object('Part', $row['Product Part Part SKU']);
                    // $part->update_stock_in_paid_orders();

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;

        case 'payment_added_order':


            // $order    = get_object('Order', $data['order_key']);

            $store = get_object('Store', $data['store_key']);
            $store->update_orders();


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE `Order Key`=%d  ', $data['order_key']
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


            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE  `Delivery Note Key`=%d  ', $data['delivery_note_key']
            );
            // print "$sql\n";

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                    // print $part->get('Reference')."\n";
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
            break;

        case 'replacement_created':
            $order   = get_object('Order', $data['order_key']);
            $account = get_object('Account', '');
            $store   = get_object('Store', $order->get('Store Key'));

            // todo review if other operations has to be done

            $store->update_orders();
            $account->update_orders();


            break;

        case 'order_submitted_by_client':
            $order    = get_object('Order', $data['order_key']);
            $website  = get_object('Website', $data['website_key']);
            $customer = get_object('Customer', $data['customer_key']);
            $store    = get_object('Store', $customer->get('Customer Store Key'));

            $customer->editor = $editor;


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
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)', $order->id, $published_email_template->email_tracking->id, prepare_mysql('Order Notification')
                );

                $db->exec($sql);
            }


            $smarty = new Smarty();
            $base   = '';
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

                /*

                include_once 'utils/email_notification.class.php';

                $email_notification = new email_notification();
                foreach ($recipients as $recipient) {
                    $email_notification->mail->addAddress($recipient);

                }


                $subject    = _('New order').' '.$store->get('Name');
                $title      = '<b>'._('New order').'</b> '.$order->get('Total Amount').' '.$store->get('Name');
                $link_label = _('Link to order');

                $link = sprintf(
                    '%s/orders/%d/%d',
                    $account->get('Account System Public URL'),
                    $store->id,
                    $order->id
                );


                $info = sprintf(
                    _('New order %s (%s) has been placed by %s'),
                    '<a href="'.$link.'">'.$order->get('Public ID').'</a>',
                    '<b>'.$order->get('Total Amount').'</b>',
                    '<b>'.$customer->get('Name').'</b>'

                );


                $smarty->assign('type', 'Success');

                $smarty->assign('store', $store);
                $smarty->assign('account', $account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                $smarty->assign('info', $info);
                $smarty->assign('customer', $customer);
                $smarty->assign('order', $order);

                $email_notification->mail->Subject = $subject;

                try {
                    $email_notification->mail->msgHTML($smarty->fetch('notification_emails/new_order.ntfy.tpl'));
                    $email_notification->mail->AltBody = strip_tags($info);

                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }


                $email_notification->send();

                */

            }


            break;

        case 'order_dispatched':
            $order         = get_object('Order', $data['order_key']);
            $delivery_note = get_object('Delivery_Note', $data['delivery_note_key']);


            $account = get_object('Account', '');
            $store   = get_object('Store', $order->get('Store Key'));


            $store->update_orders();
            $account->update_orders();

            $order->send_review_invitation();


            $customer         = get_object('Customer', $order->get('Order Customer Key'));
            $customer->editor = $editor;


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


            break;
        case 'invoice_created':

            update_invoice_products_sales_data($db, $account, $data);
            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_invoices();

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


            $poll_option = get_object('Customer_Poll_Query_Option', $data['poll_option_key']);
            $poll_option->update_customers();
            $poll = get_object('Customer_Poll_Query', $poll_option->get('Customer Poll Query Option Query Key'));
            $poll->update_answers();
            break;
        case 'update_sent_emails_data':

            if (!empty($data['email_template_key'])) {
                $email_template = get_object('email_template', $data['email_template_key']);
                $email_template->update_sent_emails_totals();
            }
            if (!empty($data['email_template_type_key'])) {
                $email_template_type = get_object('email_template_type', $data['email_template_type_key']);
                $email_template_type->update_sent_emails_totals();
            }
            if (!empty($data['email_mailshot_key'])) {
                $email_campaign = get_object('email_campaign', $data['email_mailshot_key']);
                $email_campaign->update_sent_emails_totals();
            }


            break;

        case 'send_mailshot':

            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $email_campaign = get_object('email_campaign', $data['mailshot_key']);


            if ($email_campaign->id) {
                $email_campaign->socket = $socket;
                $email_campaign->update_estimated_recipients();
                $email_campaign->send_mailshot();
            }
            break;

        case 'resume_mailshot':

            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");


            $email_campaign = get_object('email_campaign', $data['mailshot_key']);


            if ($email_campaign->id) {
                $email_campaign->socket = $socket;

                $email_campaign->resume_mailshot();
            }
            break;


        case 'create_and_send_mailshot':

            //$sql = 'truncate `Email Tracking Email Copy`; ';
            //$db->exec($sql);
            //$sql = 'truncate `Email Campaign Dimension`; ';
            //$db->exec($sql);
            //$sql = 'delete from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`>0; ';
            //$db->exec($sql);
            $email_template_type = get_object('email_template_type', $data['email_template_type_key']);
            $email_campaign      = $email_template_type->create_mailshot();

            if (is_object($email_campaign) and $email_campaign->id) {
                $email_campaign->update_state('ComposingEmail');
                $email_campaign->update_state('Ready');
                $email_campaign->update_estimated_recipients();

                $email_campaign->update_state('Sending');

                $email_campaign->send_mailshot();
            }


            break;

        case 'delivery_note_packed_done':


            $customer = get_object('Customer', $data['customer_key']);

            $customer->update_part_bridge();


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
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
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


            return;


            // down here is real time
            //todo option to do real time

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

            require_once 'conf/timeseries.php';
            require_once 'class.Timeserie.php';

            $timeseries = get_time_series_config();


            //$sql = sprintf('select `Part SKU`  FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d  and `Inventory Transaction Type`="Sale" ', $data['delivery_note_key']);
            $sql = sprintf('select `Part SKU`,`Inventory Transaction Type`  FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d   ', $data['delivery_note_key']);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['Inventory Transaction Type'] == 'Sale') {

                        $part = get_object('Part', $row['Part SKU']);


                        foreach ($intervals as $interval) {
                            $part->update_sales_from_invoices($interval, true, false);
                        }

                        foreach ($part->get_suppliers() as $suppliers_key) {
                            $suppliers[$suppliers_key] = $suppliers_key;
                        }
                        foreach ($part->get_categories() as $part_category_key) {
                            $part_categories[$part_category_key] = $part_category_key;
                        }
                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

            foreach ($part_categories as $part_category_key) {
                $category = get_object('Category', $part_category_key);
                if ($category->get('Category Branch Type') != 'Root') {
                    foreach ($intervals as $interval) {
                        $category->update_sales_from_invoices($interval, true, false);
                    }
                }


                $timeseries_data = $timeseries['PartCategory'];
                foreach ($timeseries_data as $time_series_data) {


                    $time_series_data['Timeseries Parent']     = 'Category';
                    $time_series_data['Timeseries Parent Key'] = $category->id;
                    $time_series_data['editor']                = $editor;


                    $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                    $category->update_part_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


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
                    $supplier->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


                }


                foreach ($intervals as $interval) {
                    $supplier->update_sales_from_invoices($interval, true, false);
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


            //print_r($part_categories);
            //print_r($suppliers);
            //print_r($suppliers_categories);

            break;


        case 'delivery_note_cancelled':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);

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


            break;
        case 'update_ISF':


            $date = gmdate('Y-m-d H:i:s');
            $sql  = sprintf(
                'insert into `Stack BiKey Dimension` (`Stack BiKey Creation Date`,`Stack BiKey Last Update Date`,`Stack BiKey Operation`,`Stack BiKey Object Key One`,`Stack BiKey Object Key Two`) values (%s,%s,%s,%d,%d) 
                      ON DUPLICATE KEY UPDATE `Stack BiKey Last Update Date`=%s ,`Stack BiKey Counter`=`Stack BiKey Counter`+1 ',
                prepare_mysql($date),
                prepare_mysql($date),
                prepare_mysql('update_ISF'),
                $data['part_sku'],
                $data['location_key'],
                prepare_mysql($date)

            );
            $db->exec($sql);


            return true;

            include_once 'class.PartLocation.php';

            $part_location = new PartLocation($data['part_sku'].'_'.$data['location_key']);

            $date = gmdate('Y-m-d');

            $part_location->update_stock_history_date($date);


            $warehouse = get_object('Warehouse', $part_location->location->get('Location Warehouse Key'));
            $warehouse->update_inventory_snapshot($date);


            if ($part_location->get('Quantity On Hand') < 0) {

                $suppliers = $part_location->part->get_suppliers();
                foreach ($suppliers as $supplier_key) {
                    $supplier_production = get_object('Supplier_Production', $supplier_key);

                    if ($supplier_production->id) {
                        $supplier_production->update_locations_with_errors();
                    }
                }
            }


            break;

        case 'create_today_ISF':

            include_once 'class.PartLocation.php';


            $sql = sprintf(
                "SELECT `Part SKU`,`Location Key` from `Part Location Dimension`"
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
                    $part_location->update_stock_history_date(date("Y-m-d"));

                }
            }

            $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $warehouse = get_object('Warehouse', $row['Warehouse Key']);
                    $warehouse->update_inventory_snapshot(date("Y-m-d"));
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;

        case 'create_yesterday_timeseries':

            require_once 'class.Timeserie.php';


            require_once 'conf/timeseries.php';

            $timeseries = get_time_series_config();


            $sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Part" ORDER BY  `Category Key` DESC');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = get_object('Category', $row['Category Key']);
                    if ($category->get('Part Category Status') != 'NotInUse' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Part Category Valid To').' +0:00'))) {
                        if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                            continue;
                        }

                        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
                        //print_r($timeseries_data);
                        foreach ($timeseries_data as $timeserie_data) {

                            $editor['Date']                          = gmdate('Y-m-d H:i:s');
                            $timeserie_data['editor']                = $editor;
                            $timeserie_data['Timeseries Parent']     = 'Category';
                            $timeserie_data['Timeseries Parent Key'] = $category->id;
                            $timeseries                              = new Timeseries(
                                'find', $timeserie_data, 'create'
                            );
                            $category->update_part_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
                        }
                    }
                }

            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" ORDER BY  `Category Key` DESC');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = get_object('Category', $row['Category Key']);
                    $category->update_product_category_new_products();
                    if ($category->get('Product Category Status') != 'Discontinued' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Product Category Valid To').' +0:00'))) {
                        if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                            continue;
                        }

                        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
                        //print_r($timeseries_data);
                        foreach ($timeseries_data as $timeserie_data) {

                            $editor['Date']                          = gmdate('Y-m-d H:i:s');
                            $timeserie_data['editor']                = $editor;
                            $timeserie_data['Timeseries Parent']     = 'Category';
                            $timeserie_data['Timeseries Parent Key'] = $category->id;
                            $timeseries                              = new Timeseries('find', $timeserie_data, 'create');
                            $category->update_product_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
                        }
                    }
                }

            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            break;


        case 'start_purge':

            $context = new ZMQContext();
            $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");


            $purge         = get_object('purge', $data['purge_key']);
            $purge->editor = $editor;
            if ($purge->id) {
                $purge->socket = $socket;
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
                $sql = sprintf(
                    "SELECT `Supplier Part Key` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=%d", $po->id
                );


                if ($result = $db->query($sql)) {
                    foreach ($result as $row) {
                        $supplier_part         = get_object('SupplierPart', $row['Supplier Part Key']);
                        $supplier_part->editor = $editor;
                        if (isset($supplier_part->part)) {
                            $supplier_part->part->update_next_deliveries_data();
                        }


                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }
            }


            break;

        case 'update_active_parts_commercial_value':

            $sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  where `Part Status` ="In Use" ORDER BY `Part SKU` desc');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Part SKU']);
                    $part->update_commercial_value();
                }

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
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            foreach ($purchase_orders as $purchase_order_key) {
                $purchase_order = get_object('Purchase Order', $purchase_order_key);
                $purchase_order->update_totals();
            }


            break;
        case 'update_parts_cost':


            $sql = sprintf(
                'SELECT `Part SKU` FROM `Part Dimension`    where `Part Status`!="Not In Use" '
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = get_object('Part', $row['Part SKU']);

                    $part->update_cost();


                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
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

            $product->update_part_numbers();
            $product->update_availability();
            $product->update_cost();


            foreach ($product->get_parts('objects') as $part) {
                $part->update_products_data();
                $part->update_commercial_value();
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


                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` SET  `Product Key`=%d, `Product Code`=%s, `Order Transaction Gross Amount`=%.2f, `Order Transaction Total Discount Amount`=0	, `Order Transaction Amount`=%.2f  WHERE `Order Transaction Fact Key`=%d',
                        $product->get('Product Current Key'), prepare_mysql($product->get('Product Code')), $product->get('Product Price') * $row['Order Quantity'], $product->get('Product Price') * $row['Order Quantity'],

                        $row['Order Transaction Fact Key']
                    );


                    $db->exec($sql);

                    $order = get_object('Order', $row['Order Key']);
                    $old_used_deals = $order->get_used_deals();


                    $order->update_number_products();
                    $order->update_insurance();

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

                        if($campaign_key>0){
                            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                            print "$sql\n";
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
                        if($deal_key>0) {
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
                        if($deal_component_key>0) {
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

                    $order->update_number_products();
                }


            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;


        case 'clean_webpage_cache':


            $webpage = get_object('Webpage', $data['webpage_key']);

            $smarty_web = new Smarty();


            $account = get_object('Account', 1);
            $base    = 'base_dirs/_home.'.strtoupper($account->get('Account Code')).'/';


            $smarty_web->setTemplateDir($base.'EcomB2B/templates');

            $smarty_web->setCompileDir($base.'EcomB2B/server_files/smarty/templates_c');
            $smarty_web->setCacheDir($base.'EcomB2B/server_files/smarty/cache');
            $smarty_web->setConfigDir($base.'EcomB2B/server_files/smarty/configs');
            $smarty_web->addPluginsDir('./smarty_plugins');


            $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


            $cache_id = $webpage->get('Webpage Website Key').'|'.$webpage->id;
            $smarty_web->clearCache(null, $cache_id);


            $redis = new Redis();


            if ($redis->connect('127.0.0.1', 6379)) {


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


            $store = get_object('Store', $data['store_key']);


            $smarty = new Smarty();
            $base   = '';
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
            /*
                        if (count($recipients) > 0) {
                            include_once 'utils/email_notification.class.php';

                            $email_notification = new email_notification();
                            foreach ($recipients as $recipient) {
                                $email_notification->mail->addAddress($recipient);

                            }


                            $deleted_by = get_object('User', $invoice->get('Invoice Deleted User Key'));

                            if ($invoice->get('Invoice Type') == 'Invoice') {
                                $subject              = _('Invoice deleted').' '.$store->get('Name');
                                $title                = '<b>'._('Invoice deleted').'</b> '.$store->get('Name');
                                $link_label           = _('Link to deleted invoice');
                                $deleted_invoice_info = sprintf(
                                    _('Invoice %s (%s, %s) has been deleted by %s.'),
                                    $invoice->get('Public ID'),
                                    '<b>'.$invoice->get('Total Amount').'</b>',
                                    $invoice->get_date('Invoice Date'), $deleted_by->get('Alias')

                                );

                            } else {
                                $subject = _('Refund deleted').' '.$store->get('Name');
                                $title   = _('Refund deleted').' '.$store->get('Name');

                                $link_label           = _('Link to deleted refund');
                                $deleted_invoice_info = sprintf(
                                    _('Refund %s (%s, %s) has been deleted by %s.'),
                                    $invoice->get('Public ID'),
                                    '<b>'.$invoice->get('Total Amount').'</b>',
                                    $invoice->get_date('Invoice Date'), $deleted_by->get('Alias')

                                );
                            }


                            $deleted_invoice_link = sprintf(
                                '%s/orders/%d/%d/invoice/%d',
                                $account->get('Account System Public URL'),

                                $store->id,
                                $invoice->get('Invoice Order Key'),
                                $invoice->id
                            );

                            $smarty->assign('type', 'Warning');

                            $smarty->assign('store', $store);
                            $smarty->assign('account', $account);
                            $smarty->assign('title', $title);
                            $smarty->assign('subject', $subject);
                            $smarty->assign('link_label', $link_label);
                            $smarty->assign('deleted_invoice_link', $deleted_invoice_link);
                            $smarty->assign('deleted_invoice_info', $deleted_invoice_info);

                            $email_notification->mail->Subject = $subject;

                            try {
                                $email_notification->mail->msgHTML($smarty->fetch('notification_emails/alert.ntfy.tpl'));
                                $email_notification->mail->AltBody = strip_tags($deleted_invoice_info);

                            } catch (Exception $e) {
                                echo 'Caught exception: ', $e->getMessage(), "\n";
                            }


                            $email_notification->send();


                        }

            */
            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_invoices();


            break;
        case 'delivery_note_dispatched':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);

            $shipper = get_object('Shipper', $delivery_note->get('Delivery Note Shipper Key'));
            $shipper->update_shipper_usage();

            break;
        case 'delivery_note_un_dispatched':

            $delivery_note = get_object('delivery_note', $data['delivery_note_key']);


            $store = get_object('Store', $delivery_note->get('Delivery Note Store Key'));


            $shipper = get_object('Shipper', $delivery_note->get('Delivery Note Shipper Key'));
            $shipper->update_shipper_usage();


            $smarty = new Smarty();
            $base   = '';
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

            break;


        case 'customer_registered':

            $customer = get_object('customer', $data['customer_key']);
            $website  = get_object('website', $data['website_key']);


            $store = get_object('Store', $customer->get('Customer Store Key'));


            $smarty = new Smarty();
            $base   = '';
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

                    //  print_r($published_email_template->msg);


                }
                /*


            include_once 'utils/email_notification.class.php';

            $email_notification = new email_notification();
            foreach ($recipients as $recipient) {
                $email_notification->mail->addAddress($recipient);

            }


                            $subject    = _('New customer registration').' '.$store->get('Name');
                            $title      = '<b>'._('New customer registration').'</b> '.$store->get('Name');
                            $link_label = _('Link to customer');


                            $info = sprintf(
                                _('%s (%s) has registered'),
                                '<b>'.$customer->get('Name').'</b>',
                                '<a href="href="mailto:'.$customer->get('Customer Main Plain Email').'"">'.$customer->get('Customer Main Plain Email').'</a>'

                            );

                            $link = sprintf(
                                '%s/customers/%d/%d',
                                $account->get('Account System Public URL'),
                                $store->id,
                                $customer->id
                            );

                            $smarty->assign('type', 'Success');

                            $smarty->assign('store', $store);
                            $smarty->assign('account', $account);
                            $smarty->assign('title', $title);
                            $smarty->assign('subject', $subject);
                            $smarty->assign('link_label', $link_label);
                            $smarty->assign('link', $link);
                            $smarty->assign('info', $info);


                            $email_notification->mail->Subject = $subject;

                            try {
                                $email_notification->mail->msgHTML($smarty->fetch('notification_emails/alert.ntfy.tpl'));
                                $email_notification->mail->AltBody = strip_tags($info);

                            } catch (Exception $e) {
                                echo 'Caught exception: ', $e->getMessage(), "\n";
                            }


                            $email_notification->send();
            */

            }


            break;

        case 'update_deals_status_from_dates':

            $sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension`  left join `Store Dimension` on (`Deal Store Key`=`Store Key`) where `Store Version`=2 and `Deal Expiration Date` is not null  and `Deal Status` not in ('Finished')");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $deal = get_object('Deal', $row['Deal Key']);


                    $deal->update_status_from_dates(false);
                    foreach ($deal->get_deal_components('objects', 'all') as $component) {
                        $component->update_status_from_dates();
                    }


                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;


        default:
            break;

    }


    return false;
}

?>
