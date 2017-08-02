<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_housekeeping($job) {


    // print "fork_housekeeping  original skypping\n";

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    list($account, $db, $data) = $_data;

    	//print_r($data);

    switch ($data['type']) {

        case 'order_created':
            include_once 'class.Order.php';
            include_once 'class.Customer.php';
            include_once 'class.Store.php';
            $order = new Order($data['subject_key']);

            $customer=new Customer($order->get('Order Customer Key'));
            $customer->editor=$data['editor'];
            $customer->add_history_new_order($order);
            $customer->update_orders();
            $store=new Store($order->get('Order Store Key'));
            $store->load_acc_data();



            $store->update_orders();
            $order->update_full_search();
            break;

        case 'website_launched':





            $website = get_object('Website',$data['website_key']);
            $website->editor=$data['editor'];


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

                    $webpage         =  get_object('Webpage',$row['Page Key']);
                    $webpage->editor = $website->editor;

                   // print $webpage->get('Webpage Code')."\n";

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

                    $webpage         = get_object('Webpage',$row['Page Key']);
                    $webpage->editor = $website->editor;
                   // print $webpage->get('Webpage Code')." ** \n";

                    if ($webpage->get('Webpage State') == 'Ready') {
                        $webpage->publish();

                    }


                }
            }
          

            break;

        case 'customer_created':
           

            $customer = get_object('Customer',$data['customer_key']);
            $store    = get_object('Store',$customer->get('Customer Store Key'));
            $website_user = get_object('Website_User',$data['website_user_key']);



            $customer->update_full_search();
            $customer->update_location_type();
            $store->update_customers_data();

            if($website_user->id){
                $website    = get_object('Website',$website_user->get('Website User Website Key'));

                $website->update_users_data();

            }


            break;


        case 'update_orders_in_basket_data':

            include_once 'class.Store.php';
            $store = new Store($data['store_key']);
            $store->load_acc_data();
            $store->update_orders_in_basket_data();
            $account->load_acc_data();
            $account->update_orders_in_basket_data();


            break;

        case 'update_web_state_slow_forks':

            include_once 'class.Product.php';
            $product = new Product('id', $data['product_id']);
            $product->update_web_state_slow_forks(
                $data['web_availability_updated']
            );

            break;

        case 'update_part_products_availability':

            include_once 'class.Part.php';
            $part = new Part($data['part_sku']);
            foreach ($part->get_products('objects') as $product) {
                $product->update_availability($use_fork = false);
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
                    $supplier_production = new Supplier_Production(
                        $supplier_key
                    );
                    if ($supplier_production->id) {
                        $supplier_production->update_locations_with_errors();
                    }
                }
            }
            break;
        case 'order_payment_changed':

            include_once 'class.Part.php';

            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE `Order Key`=%d  ',
                $data['order_key']
            );
            // print "$sql\n";
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = new Part($row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                    //   print $part->get('Reference')."\n";
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;
        case 'order_send_to_warehouse':

            include_once 'class.Part.php';

            $sql = sprintf(
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE  `Order Key`=%d  ',
                $data['order_key']
            );
            // print "$sql\n";

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = new Part($row['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                    // print $part->get('Reference')."\n";
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            break;

    }


    return false;
}

?>
