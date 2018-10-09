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


    list($account, $db, $data, $editor) = $_data;

    print_r($data);

   // return true;


    switch ($data['type']) {

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
                $part = get_object('Part', $part_sku);
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

        case 'deal_created':


            $deal = get_object('Deal', $data['deal_key']);


            if ($deal->get('Deal Status') == 'Active' and !$deal->get('Deal Voucher Key')) {


                switch ($deal->get('Deal Trigger')) {
                    case 'Category':


                        $sql = sprintf(
                            'SELECT `Order Key`  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Category Key`=%d and `Current Dispatching State`="In Process"  group by `Order Key`',
                            $deal->get('Deal Trigger Key')
                        );


                        if ($result = $db->query($sql)) {
                            foreach ($result as $row) {


                                $order = get_object('Order', $row['Order Key']);
                                $order->update_totals();

                                $order->update_discounts_items();


                                $order->update_shipping(false, false);
                                $order->update_charges(false, false);

                                $order->update_deal_bridge();


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

            }


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
                            $order->update_totals();

                            $order->update_discounts_items();


                            $order->update_shipping(false, false);
                            $order->update_charges(false, false);

                            $order->update_deal_bridge();


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

            $order->update_deals_usage();

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

            $order->update_deals_usage();


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


        case 'order_payment_changed': // this can be removed after all inikoo gone


            $order = get_object('Order', $data['order_key']);

            $store = get_object('Store', $order->get('Order Store Key'));
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
            $order            = get_object('Order', $data['order_key']);
            $website          = get_object('Website', $data['website_key']);
            $customer         = get_object('Customer', $data['customer_key']);
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

            break;

        case 'order_dispatched':
            $order   = get_object('Order', $data['order_key']);
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


            );


            $published_email_template->send($customer, $send_data);


            if ($published_email_template->sent) {
                $sql = sprintf(
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)', $order->id, $published_email_template->email_tracking->id, prepare_mysql('Dispatch Notification')
                );

                $db->exec($sql);
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


            $suppliers            = array();
            $suppliers_categories = array();
            $part_categories      = array();

            $sql = sprintf('select `Part SKU`  FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d  and `Inventory Transaction Type`="Sale" ', $data['delivery_note_key']);
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
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


        case 'update_cancelled_delivery_note_products_sales_data':

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

            include_once 'class.PartLocation.php';

            $part_location = new PartLocation(
                $data['part_sku'].'_'.$data['location_key']
            );

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


            $purge = get_object('purge', $data['purge_key']);
            $purge->editor=$editor;
            if ($purge->id) {
                $purge->socket = $socket;
                $purge->purge();
            }
            break;
        case 'supplier_delivery_state_changed':



            $supplier_delivery = get_object('supplier_delivery', $data['supplier_delivery_key']);

            $po= get_object('supplier_delivery', $supplier_delivery->get('Supplier Delivery Purchase Order Key'));

            $po->editor=$editor;
            if ($po->id) {
                $po->update_totals();
            }
            break;



        default:
            break;

    }


    return false;
}

?>
