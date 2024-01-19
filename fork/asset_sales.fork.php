<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 5 October 2016 at 12:12:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_asset_sales($job) {

    global $account,$db;

    if (!$_data = get_fork_metadata($job)) {

        print "fork_asset_sales shit";

        return true;
    }



    list($account, $db, $data,$editor,$ES_hosts) = $_data;

    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    $account->redis=$redis;

    //print_r($data);
   //return true;


    switch ($data['type']) {


        case 'update_stores_previous_intervals':
            include_once 'class.Store.php';

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

                    $category->update_product_category_sales(
                        $data['interval'], $this_year, $last_year
                    );
                }
            }
            break;


        case 'update_part_categories_previous_intervals':
            include_once 'class.Category.php';

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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

            if (!in_array(
                $data['intervals'], array(
                                      'Quarters',
                                      'Years'
                                  )
            )) {
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




        case 'update_invoice_products_sales_data':
            update_invoice_products_sales_data($db, $account, $data);

            break;
        case 'update_edited_invoice_products_sales_data':


            update_edited_invoice_products_sales_data($db, $data, $account);













            break;

    }


    return false;
}




function update_invoice_products_sales_data($db, $account, $data) {

    global $editor;

    require_once 'conf/timeseries.php';
    require_once 'class.Timeserie.php';

    include_once 'class.Product.php';
    include_once 'class.Customer.php';
    include_once 'class.Category.php';
    include_once 'class.Store.php';
    include_once 'class.Invoice.php';


    $account->update_sales_from_invoices('Total', true, false);
    $account->update_sales_from_invoices('Week To Day', true, false);
    $account->update_sales_from_invoices('Month To Day', true, false);
    $account->update_sales_from_invoices('Quarter To Day', true, false);
    $account->update_sales_from_invoices('Year To Day', true, false);
    $account->update_sales_from_invoices('Today', true, false);



    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Account'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Account';
        $time_series_data['Timeseries Parent Key'] = 1;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $account->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


    }


    $categories     = array();
    $categories_bis = array();
    //  print_r($data);

    $customer = get_object('Customer',$data['customer_key']);
    $customer->update_product_bridge();


    $store = get_object('Store', $data['store_key']);

    $store->update_sales_from_invoices('Total', true, false);
    $store->update_sales_from_invoices('Week To Day', true, false);
    $store->update_sales_from_invoices('Month To Day', true, false);
    $store->update_sales_from_invoices('Quarter To Day', true, false);
    $store->update_sales_from_invoices('Year To Day', true, false);
    $store->update_sales_from_invoices('Today', true, false);


    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Store'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Store';
        $time_series_data['Timeseries Parent Key'] = $store->id;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $store->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


    }



    if(!empty($data['invoice_key'])) {

        $invoice = new Invoice($data['invoice_key']);
        $invoice->categorize();

        if (($invoice->get('Invoice Category Key'))) {

            $invoice_category = new Category($invoice->get('Invoice Category Key'));

            //  $invoice_category->load_acc_data();


            $invoice_category->update_invoice_category_sales('Total', true, false);

            $invoice_category->update_invoice_category_sales('Year To Day', true, false);
            $invoice_category->update_invoice_category_sales('Quarter To Day', true, false);
            $invoice_category->update_invoice_category_sales('Month To Day', true, false);
            $invoice_category->update_invoice_category_sales('Week To Day', true, false);
            $invoice_category->update_invoice_category_sales('Today', true, false);
        }


        $sql = sprintf(
            "SELECT `Product ID`,`Invoice Date` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $data['invoice_key']
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $product = new Product('id', $row['Product ID']);

                if($product->id){
                    $date = gmdate('Y-m-d H:i:s');
                    $sql  = sprintf(
                        'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                        prepare_mysql($date),
                        prepare_mysql($date),
                        prepare_mysql('product_sales'),
                        $product->id,
                        prepare_mysql($date)

                    );
                    $db->exec($sql);


                    $categories = $categories + $product->get_categories();
                }



            }
        }

    }

    foreach ($categories as $category_key) {

        $category = new Category($category_key);

        if($category->id) {
            $sql = sprintf(
                'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
                prepare_mysql($date),
                prepare_mysql($date),
                prepare_mysql('product_family_sales'),
                $category->id,
                prepare_mysql($date)

            );
            $db->exec($sql);


            $categories_bis = $categories_bis + $category->get_categories();

        }
    }

    foreach ($categories_bis as $category_key) {

        $sql = sprintf(
            'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ',
            prepare_mysql($date),
            prepare_mysql($date),
            prepare_mysql('product_department_sales'),
            $category_key,
            prepare_mysql($date)

        );
        $db->exec($sql);


    }




}




function update_invoice_products_sales_data_reaL_time($db, $account, $data) {

    global $editor;

    require_once 'conf/timeseries.php';
    require_once 'class.Timeserie.php';

    include_once 'class.Product.php';
    include_once 'class.Customer.php';
    include_once 'class.Category.php';
    include_once 'class.Store.php';
    include_once 'class.Invoice.php';


    $account->update_sales_from_invoices('Total', true, false);
    $account->update_sales_from_invoices('Week To Day', true, false);
    $account->update_sales_from_invoices('Month To Day', true, false);
    $account->update_sales_from_invoices('Quarter To Day', true, false);
    $account->update_sales_from_invoices('Year To Day', true, false);
    $account->update_sales_from_invoices('Today', true, false);



    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Account'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Account';
        $time_series_data['Timeseries Parent Key'] = 1;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $account->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


    }


    $categories     = array();
    $categories_bis = array();
    //  print_r($data);

    $customer = get_object('Customer',$data['customer_key']);
    $customer->update_product_bridge();


    $store = get_object('Store', $data['store_key']);

    $store->update_sales_from_invoices('Total', true, false);
    $store->update_sales_from_invoices('Week To Day', true, false);
    $store->update_sales_from_invoices('Month To Day', true, false);
    $store->update_sales_from_invoices('Quarter To Day', true, false);
    $store->update_sales_from_invoices('Year To Day', true, false);
    $store->update_sales_from_invoices('Today', true, false);


    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Store'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Store';
        $time_series_data['Timeseries Parent Key'] = $store->id;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $store->update_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


    }



    if(!empty($data['invoice_key'])) {

        $invoice = new Invoice($data['invoice_key']);
        $invoice->categorize();

        if (($invoice->get('Invoice Category Key'))) {

            $invoice_category = new Category($invoice->get('Invoice Category Key'));

            //  $invoice_category->load_acc_data();


            $invoice_category->update_invoice_category_sales('Total', true, false);

            $invoice_category->update_invoice_category_sales('Year To Day', true, false);
            $invoice_category->update_invoice_category_sales('Quarter To Day', true, false);
            $invoice_category->update_invoice_category_sales('Month To Day', true, false);
            $invoice_category->update_invoice_category_sales('Week To Day', true, false);
            $invoice_category->update_invoice_category_sales('Today', true, false);
        }


        $sql = sprintf(
            "SELECT `Product ID`,`Invoice Date` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $data['invoice_key']
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $product = new Product('id', $row['Product ID']);


                //print $product->get('Code')."\n";

                //  $product->load_acc_data();

                $product->update_sales_from_invoices('Total', true, false);
                $product->update_sales_from_invoices('Week To Day', true, false);
                $product->update_sales_from_invoices('Month To Day', true, false);
                $product->update_sales_from_invoices('Quarter To Day', true, false);
                $product->update_sales_from_invoices('Year To Day', true, false);

                $product->update_sales_from_invoices('Today', true, false);

                $categories = $categories + $product->get_categories();


            }
        }

    }

    foreach ($categories as $category_key) {

        $category = new Category($category_key);
        //$category->load_acc_data();


        //  print $category->get('Code')."\n";

        $category->update_product_category_sales('Total', true, false);
        $category->update_product_category_sales('Week To Day', true, false);
        $category->update_product_category_sales('Month To Day', true, false);
        $category->update_product_category_sales('Quarter To Day', true, false);
        $category->update_product_category_sales('Year To Day', true, false);

        $category->update_product_category_sales('Today', true, false);

        $categories_bis = $categories_bis + $category->get_categories();




        $timeseries      = get_time_series_config();
        $timeseries_data = $timeseries['ProductCategory'];
        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;
            $time_series_data['editor']                = $editor;


            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $category->update_product_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


        }


    }

    foreach ($categories_bis as $category_key) {

        $category = new Category($category_key);
        // $category->load_acc_data();


        //print $category->get('Code')."\n";

        $category->update_product_category_sales('Total', true, false);
        $category->update_product_category_sales('Week To Day', true, false);
        $category->update_product_category_sales('Month To Day', true, false);
        $category->update_product_category_sales('Quarter To Day', true, false);
        $category->update_product_category_sales('Year To Day', true, false);
        $category->update_product_category_sales('Today', true, false);


        $timeseries      = get_time_series_config();
        $timeseries_data = $timeseries['ProductCategory'];
        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;
            $time_series_data['editor']                = $editor;


            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $category->update_product_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


        }



    }




}


function update_edited_invoice_products_sales_data($db, $data) {

    global $editor;

    require_once 'conf/timeseries.php';
    require_once 'class.Timeserie.php';


    include_once 'class.Product.php';
    include_once 'class.Customer.php';
    include_once 'class.Category.php';
    include_once 'class.Store.php';
    include_once 'class.Invoice.php';


    $account = get_object('Account', '');


    $account->update_sales_from_invoices('Total');
    $account->update_sales_from_invoices('Week To Day');
    $account->update_sales_from_invoices('Month To Day');
    $account->update_sales_from_invoices('Quarter To Day');
    $account->update_sales_from_invoices('Year To Day');

    $account->update_sales_from_invoices('1 Year');
    $account->update_sales_from_invoices('1 Quarter');
    $account->update_sales_from_invoices('1 Month');
    $account->update_sales_from_invoices('1 Week');
    $account->update_sales_from_invoices('Today');


    //todo don't calculate the ones not applicable
    $account->update_sales_from_invoices('Yesterday');
    $account->update_sales_from_invoices('Last Week');
    $account->update_sales_from_invoices('Last Month');


    $account->update_previous_years_data();
    $account->update_previous_quarters_data();





    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Account'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Account';
        $time_series_data['Timeseries Parent Key'] = 1;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $account->update_timeseries_record($object_timeseries, $data['invoice_date'], gmdate('Y-m-d'));


    }



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

    $timeseries      = get_time_series_config();
    $timeseries_data = $timeseries['Store'];
    foreach ($timeseries_data as $time_series_data) {


        $time_series_data['Timeseries Parent']     = 'Store';
        $time_series_data['Timeseries Parent Key'] = $store->id;
        $time_series_data['editor']                = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $store->update_timeseries_record($object_timeseries, $data['invoice_date'], gmdate('Y-m-d'));


    }



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



        $timeseries      = get_time_series_config();
        $timeseries_data = $timeseries['ProductCategory'];
        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;
            $time_series_data['editor']                = $editor;


            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $category->update_product_timeseries_record($object_timeseries, $data['invoice_date'], gmdate('Y-m-d'));


        }



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

        $timeseries      = get_time_series_config();
        $timeseries_data = $timeseries['ProductCategory'];
        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;
            $time_series_data['editor']                = $editor;


            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $category->update_product_timeseries_record($object_timeseries, $data['invoice_date'], gmdate('Y-m-d'));


        }


    }


}


?>
