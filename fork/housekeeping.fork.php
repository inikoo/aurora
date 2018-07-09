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

    //return true;


    switch ($data['type']) {


        case 'update_parts_stock_run':

            //  print_r($data);

            foreach ($data['parts_data'] as $part_sku => $from_date) {
                $part = get_object('Part', $part_sku);
                $part->update_stock_run();
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
            $account->update_orders();

            break;


        case 'update_orders_in_basket_data': // remove after migration
            $store   = get_object('Store', $data['store_key']);
            $account = get_object('Account', '');
            $store->update_orders_in_basket_data();
            $account->update_orders_in_basket_data();

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
        case 'replacement_created':
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
                $product->update_availability(true);
            }

            break;

        case 'update_part_products_availability':

            include_once 'class.Part.php';
            $part = new Part($data['part_sku']);

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
                $product->update_availability(true);
            }

            break;

        case 'part_location_changed':
            include_once 'class.PartLocation.php';
            include_once 'class.Supplier_Production.php';

            $part_location = new PartLocation(
                $data['part_sku'].'_'.$data['location_key']
            );

            if ($part_location->get('Quantity On Hand') < 0) {

                $suppliers = $part_location->part->get_suppliers();
                foreach ($suppliers as $supplier_key) {
                    $supplier_production = new Supplier_Production($supplier_key);

                    if ($supplier_production->id) {
                        $supplier_production->update_locations_with_errors();
                    }
                }
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
        case 'invoice_created':

            update_invoice_products_sales_data($db, $account, $data);
            $customer = get_object('Customer', $data['customer_key']);
            $customer->update_invoices();

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
            return false;
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

            $email_campaign = get_object('email_campaign', $data['mailshot_key']);

            if ($email_campaign->id) {
                $email_campaign->update_estimated_recipients();
                $email_campaign->send_mailshot();
            }
            break;

        case 'resume_mailshot':

            $email_campaign = get_object('email_campaign', $data['mailshot_key']);

            if ($email_campaign->id) {

                // $email_campaign->resume_mailshot();
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
        default:
            break;

    }


    return false;
}

?>
