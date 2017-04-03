<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 5 October 2016 at 12:12:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_asset_sales($job) {


    if (!$_data = get_fork_metadata($job)) {

        print "fork_asset_sales shit";

        return true;
    }


    list($account, $db, $data) = $_data;

    // print_r($data);

    switch ($data['type']) {


        case 'update_stores_previous_intervals':
            include_once 'class.Store.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $store = new Store('id', $row['Store Key']);
                    $store->load_acc_data();


                    if ($data['intervals'] == 'Quarters') {
                        $store->update_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $store->update_previous_years_data();
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $account->load_acc_data();

            if ($data['intervals'] == 'Quarters') {
                $account->update_previous_quarters_data();

            } elseif ($data['intervals'] = 'Years') {
                $account->update_previous_years_data();
            }


            break;


        case 'update_stores_sales_data':
            include_once 'class.Store.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $store = new Store('id', $row['Store Key']);
                    $store->load_acc_data();
                    $store->update_sales_from_invoices($data['interval'], $this_year, $last_year);
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $account->load_acc_data();
            $account->update_sales_from_invoices($data['interval'], $this_year, $last_year);

            break;


        case 'update_invoices_categories_sales_data':
            include_once 'class.Category.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Invoice' ");


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    $category->update_invoice_category_sales(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;

        case 'update_invoices_categories_previous_intervals':
            include_once 'class.Category.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Invoice' ");


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $category->update_invoice_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $category->update_invoice_previous_years_data();
                    }


                }
            }




            break;


        case 'update_suppliers_previous_intervals':
            include_once 'class.Supplier.php';
            include_once 'class.Agent.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }



            $sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $supplier = new Supplier($row['Supplier Key']);
                    $supplier->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $supplier->update_previous_quarters_data();
                    } elseif ($data['intervals'] = 'Years') {
                        $supplier->update_previous_years_data();
                    }

                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }



            $sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension`  ');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $agent = new Agent($row['Agent Key']);
                    $agent->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $agent->update_previous_quarters_data();
                    } elseif ($data['intervals'] = 'Years') {
                        $agent->update_previous_years_data();
                    }

                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }




            break;




        case 'update_suppliers_sales_data':
            include_once 'class.Supplier.php';
            include_once 'class.Agent.php';


            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }




            $sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $supplier = new Supplier($row['Supplier Key']);

                    $supplier->load_acc_data();

                    $supplier->update_sales(
                        $data['interval'], $this_year, $last_year
                    );

                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension`  ');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $agent = new Agent($row['Agent Key']);
                    $agent->load_acc_data();
                    $agent->update_sales(
                        $data['interval'], $this_year, $last_year
                    );

                }

            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            break;



        case 'update_products_previous_intervals':
            include_once 'class.Product.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Product ID` FROM `Product Dimension`  ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $product = new Product('id', $row['Product ID']);
                    $product->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $product->update_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $product->update_previous_years_data();
                    }

                }
            }
            break;





        case 'update_products_sales_data':
            include_once 'class.Product.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf("SELECT `Product ID` FROM `Product Dimension`  ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $product = new Product('id', $row['Product ID']);
                    $product->load_acc_data();
                    $product->update_sales_from_invoices($data['interval'], $this_year, $last_year);
                }
            }
            break;


        case 'update_parts_previous_intervals':
            include_once 'class.Part.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }


            $sql = sprintf("SELECT `Part SKU` FROM `Part Dimension`  ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $part = new Part($row['Part SKU']);
                    $part->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $part->update_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $part->update_previous_years_data();
                    }
                }
            }


            break;





        case 'update_parts_sales_data':
            include_once 'class.Part.php';


            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }

            $sql = sprintf("SELECT `Part SKU` FROM `Part Dimension`  ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $part = new Part($row['Part SKU']);
                    $part->load_acc_data();
                    $part->update_sales_from_invoices(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;

        case 'update_product_categories_previous_intervals':
            include_once 'class.Category.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Product' ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    if ($data['intervals'] == 'Quarters') {
                        $category->update_product_category_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $category->update_product_category_previous_years_data();
                    }

                }
            }
            break;


        case 'update_product_categories_sales_data':
            include_once 'class.Category.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf(
                "SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Product' "
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    $category->update_part_category_sales(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;



        case 'update_part_categories_previous_intervals':
            include_once 'class.Category.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Part' ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $category->update_part_category_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $category->update_part_category_previous_years_data();
                    }

                }
            }
            break;

        case 'update_part_categories_sales_data':
            include_once 'class.Category.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Part' ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    $category->update_part_category_sales(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;

        case 'update_suppliers_categories_previous_intervals':
            include_once 'class.Category.php';

            if(!in_array($data['intervals'],array('Quarters','Years'))){
                return;
            }

            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Supplier' ");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    if ($data['intervals'] == 'Quarters') {
                        $category->update_supplier_category_previous_quarters_data();

                    } elseif ($data['intervals'] = 'Years') {
                        $category->update_supplier_category_previous_years_data();
                    }

                }
            }
            break;

        case 'update_supplier_categories_sales_data':
            include_once 'class.Category.php';

            if (!isset($data['mode'])) {
                $this_year = true;
                $last_year = true;
            } else {
                $this_year = $data['mode'][0];
                $last_year = $data['mode'][1];
            }


            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Supplier' ");

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->load_acc_data();

                    $category->update_supplier_category_sales(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;


        case 'update_delivery_note_part_sales_data':

            include_once 'class.Part.php';
            include_once 'class.Customer.php';
            include_once 'class.Category.php';

            $categories = array();
            //print_r($data);

            $customer = new Customer($data['customer_key']);
            $customer->update_part_bridge();

            $sql = sprintf(
                "SELECT `Part SKU` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d", $data['delivery_note_key']
            );

            //print "$sql\n";

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $part = new Part($row['Part SKU']);


                    //print $part->get('Reference')."\n";

                    $part->load_acc_data();

                    $part->update_sales_from_invoices('Total', true, false);
                    $part->update_sales_from_invoices(
                        'Week To Day', true, false
                    );
                    $part->update_sales_from_invoices(
                        'Month To Day', true, false
                    );
                    $part->update_sales_from_invoices(
                        'Quarter To Day', true, false
                    );
                    $part->update_sales_from_invoices(
                        'Year To Day', true, false
                    );

                    $part->update_sales_from_invoices('Today', true, false);

                    $categories = $categories + $part->get_categories();


                }
            }

            foreach ($categories as $category_key) {

                $category = new Category($category_key);


                // print $category->get('Code')."\n";

                $category->update_part_category_sales('Total', true, false);
                $category->update_part_category_sales(
                    'Week To Day', true, false
                );
                $category->update_part_category_sales(
                    'Month To Day', true, false
                );
                $category->update_part_category_sales(
                    'Quarter To Day', true, false
                );
                $category->update_part_category_sales(
                    'Year To Day', true, false
                );

                $category->update_part_category_sales('Today', true, false);

            }


            break;

        case 'update_invoice_products_sales_data':
            update_invoice_products_sales_data($db, $account, $data);

            break;
        case 'update_deleted_invoice_products_sales_data':
            update_deleted_invoice_products_sales_data($db, $data, $account);


            break;

    }


    return false;
}


function update_invoice_products_sales_data($db, $account, $data) {

    include_once 'class.Product.php';
    include_once 'class.Customer.php';
    include_once 'class.Category.php';
    include_once 'class.Store.php';
    include_once 'class.Invoice.php';


    $account->load_acc_data();
    $account->update_sales_from_invoices('Total', true, false);
    $account->update_sales_from_invoices('Week To Day', true, false);
    $account->update_sales_from_invoices('Month To Day', true, false);
    $account->update_sales_from_invoices('Quarter To Day', true, false);
    $account->update_sales_from_invoices('Year To Day', true, false);
    $account->update_sales_from_invoices('Today', true, false);

    $categories     = array();
    $categories_bis = array();
    //  print_r($data);

    $customer = new Customer($data['customer_key']);
    $customer->update_product_bridge();


    $store = new Store($data['store_key']);

    $store->load_acc_data();
    $store->update_sales_from_invoices('Total', true, false);
    $store->update_sales_from_invoices('Week To Day', true, false);
    $store->update_sales_from_invoices('Month To Day', true, false);
    $store->update_sales_from_invoices('Quarter To Day', true, false);
    $store->update_sales_from_invoices('Year To Day', true, false);
    $store->update_sales_from_invoices('Today', true, false);


    $invoice = new Invoice($data['invoice_key']);
    $invoice->categorize();


    $invoice_category = new Category($invoice->get('Invoice Category Key'));


    $invoice_category->load_acc_data();


    $invoice_category->update_invoice_category_sales('Total', true, false);

    $invoice_category->update_invoice_category_sales('Year To Day', true, false);
    $invoice_category->update_invoice_category_sales('Quarter To Day', true, false);
    $invoice_category->update_invoice_category_sales('Month To Day', true, false);
    $invoice_category->update_invoice_category_sales('Week To Day', true, false);
    $invoice_category->update_invoice_category_sales('Today', true, false);

    $sql = sprintf(
        "SELECT `Product ID`,`Invoice Date` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $data['invoice_key']
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);


            //print $product->get('Code')."\n";

            $product->load_acc_data();

            $product->update_sales_from_invoices('Total', true, false);
            $product->update_sales_from_invoices('Week To Day', true, false);
            $product->update_sales_from_invoices('Month To Day', true, false);
            $product->update_sales_from_invoices('Quarter To Day', true, false);
            $product->update_sales_from_invoices('Year To Day', true, false);

            $product->update_sales_from_invoices('Today', true, false);

            $categories = $categories + $product->get_categories();


        }
    }


    foreach ($categories as $category_key) {

        $category = new Category($category_key);
        $category->load_acc_data();


        //print $category->get('Code')."\n";

        $category->update_product_category_sales('Total', true, false);
        $category->update_product_category_sales('Week To Day', true, false);
        $category->update_product_category_sales('Month To Day', true, false);
        $category->update_product_category_sales('Quarter To Day', true, false);
        $category->update_product_category_sales('Year To Day', true, false);

        $category->update_product_category_sales('Today', true, false);

        $categories_bis = $categories_bis + $category->get_categories();


    }

    foreach ($categories_bis as $category_key) {

        $category = new Category($category_key);
        $category->load_acc_data();


        //print $category->get('Code')."\n";

        $category->update_product_category_sales('Total', true, false);
        $category->update_product_category_sales('Week To Day', true, false);
        $category->update_product_category_sales('Month To Day', true, false);
        $category->update_product_category_sales('Quarter To Day', true, false);
        $category->update_product_category_sales('Year To Day', true, false);
        $category->update_product_category_sales('Today', true, false);


    }


}


function update_deleted_invoice_products_sales_data($db, $data) {

    include_once 'class.Product.php';
    include_once 'class.Customer.php';
    include_once 'class.Category.php';
    include_once 'class.Store.php';
    include_once 'class.Invoice.php';


    $categories     = array();
    $categories_bis = array();
    //print_r($data);

    $customer = new Customer($data['customer_key']);
    $customer->update_product_bridge();


    $store = new Store($data['store_key']);


    $store->update_sales_from_invoices('Total');
    $store->update_sales_from_invoices('Week To Day');
    $store->update_sales_from_invoices('Month To Day');
    $store->update_sales_from_invoices('Quarter To Day');
    $store->update_sales_from_invoices('Year To Day');

    $store->update_sales_from_invoices('1 Year');
    $store->update_sales_from_invoices('1 Quarter');
    $store->update_sales_from_invoices('1 Month');
    $store->update_sales_from_invoices('1 Week');
    $store->update_sales_from_invoices('Today');


    //todo don't calculate the ones not applicable
    $store->update_sales_from_invoices('Yesterday');
    $store->update_sales_from_invoices('Last Week');
    $store->update_sales_from_invoices('Last Month');


    $store->update_previous_years_data();
    $store->update_previous_quarters_data();

    //------


    $invoice_category = new Category($data['invoice_category_key']);
    $invoice_category->load_acc_data();


    $invoice_category->update_invoice_category_sales('Total');
    $invoice_category->update_invoice_category_sales('Year To Day');
    $invoice_category->update_invoice_category_sales('Quarter To Day');
    $invoice_category->update_invoice_category_sales('Month To Day');
    $invoice_category->update_invoice_category_sales('Week To Day');


    $invoice_category->update_invoice_category_sales('1 Year');
    $invoice_category->update_invoice_category_sales('1 Quarter');
    $invoice_category->update_invoice_category_sales('1 Month');
    $invoice_category->update_invoice_category_sales('1 Week');
    $invoice_category->update_invoice_category_sales('Today');

    $invoice_category->update_invoice_category_sales('Last Month');
    $invoice_category->update_invoice_category_sales('Last Week');
    $invoice_category->update_invoice_category_sales('Yesterday');

    $invoice_category->update_invoice_previous_years_data();
    $invoice_category->update_invoice_previous_quarters_data();


    foreach ($data['products'] as $product_id => $tmp) {
        $product = new Product('id', $product_id);


        $product->load_acc_data();

        $product->update_sales_from_invoices('Total');
        $product->update_sales_from_invoices('Week To Day');
        $product->update_sales_from_invoices('Month To Day');
        $product->update_sales_from_invoices('Quarter To Day');
        $product->update_sales_from_invoices('Year To Day');
        $product->update_sales_from_invoices('1 Year');
        $product->update_sales_from_invoices('1 Quarter');
        $product->update_sales_from_invoices('1 Month');
        $product->update_sales_from_invoices('1 Week');
        $product->update_sales_from_invoices('Today');

        //todo don't calculate the ones not applicable
        $product->update_sales_from_invoices('Yesterday');
        $product->update_sales_from_invoices('Last Week');
        $product->update_sales_from_invoices('Last Month');
        $product->update_previous_quarters_data();
        $product->update_previous_years_data();
        //------


        $categories = $categories + $product->get_categories();


    }


    foreach ($categories as $category_key) {

        $category = new Category($category_key);
        $category->load_acc_data();


        //print $category->get('Code')."\n";

        $category->update_product_category_sales('Total');
        $category->update_product_category_sales('Week To Day');
        $category->update_product_category_sales('Month To Day');
        $category->update_product_category_sales('Quarter To Day');
        $category->update_product_category_sales('Year To Day');
        $category->update_product_category_sales('1 Year');
        $category->update_product_category_sales('1 Quarter');
        $category->update_product_category_sales('1 Month');
        $category->update_product_category_sales('1 Week');
        $category->update_product_category_sales('Today');

        //todo don't calculate the ones not applicable
        $category->update_product_category_sales('Yesterday');
        $category->update_product_category_sales('Last Week');
        $category->update_product_category_sales('Last Month');
        $category->update_product_category_previous_quarters_data();
        $category->update_product_category_previous_years_data();
        //------

        $categories_bis = $categories_bis + $category->get_categories();


    }

    foreach ($categories_bis as $category_key) {

        $category = new Category($category_key);
        $category->load_acc_data();


        //print $category->get('Code')."\n";

        $category->update_product_category_sales('Total');
        $category->update_product_category_sales('Week To Day');
        $category->update_product_category_sales('Month To Day');
        $category->update_product_category_sales('Quarter To Day');
        $category->update_product_category_sales('Year To Day');

        $category->update_product_category_sales('1 Year');
        $category->update_product_category_sales('1 Quarter');
        $category->update_product_category_sales('1 Month');
        $category->update_product_category_sales('1 Week');
        $category->update_product_category_sales('Today');

        //todo don't calculate the ones not applicable
        $category->update_product_category_sales('Yesterday');
        $category->update_product_category_sales('Last Week');
        $category->update_product_category_sales('Last Month');
        $category->update_product_category_previous_quarters_data();
        $category->update_product_category_previous_years_data();
        //------

    }


}


?>
