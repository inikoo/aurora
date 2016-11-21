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

    //	print_r($data);

    switch ($data['type']) {


        case 'update_basket_data':

            include_once 'class.Store.php';
            $store = new Store($data['store_key']);
            $store->update_orders_in_basket_data();
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

function fork_housekeeping2($job) {


    // print "fork_housekeeping xxxx \n";

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    list($account, $db, $data) = $_data;

    //	print_r($data);

    switch ($data['type']) {


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

function fork_housekeeping3($job) {


    print "fork_housekeeping 333  xxxx \n";

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    list($account, $db, $data) = $_data;

    //	print_r($data);

    switch ($data['type']) {


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

function fork_housekeeping4($job) {


    print "fork_housekeeping 4  xxxx \n";

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    list($account, $db, $data) = $_data;

    //	print_r($data);

    switch ($data['type']) {


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
