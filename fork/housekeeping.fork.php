<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_housekeeping($job) {


    if (!$_data = get_fork_metadata($job)) {
        return;
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
                'SELECT `Product Part Part SKU` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)  WHERE OTF.`Current Dispatching State` IN ("Submitted by Customer","In Process") AND  `Current Payment State`="Paid" AND `Order Key`=%d  ',
                $data['part_sku']
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $part = new Part($data['Product Part Part SKU']);
                    $part->update_stock_in_paid_orders();
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            break;

    }


    return false;
}


?>
