<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2016 at 12:18:51 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'utils/date_functions.php';


trait ProductCategory {

    function get_see_also_data() {
        return $this->webpage->get_see_also_data();
    }


    function get_related_products_data() {
        return $this->webpage->get_related_products_data();

    }


    function get_webpage() {

        $page_key = 0;
        include_once 'class.Page.php';


        $category_key = $this->id;

        include_once 'class.Store.php';
        $store = new Store($this->get('Category Store Key'));

        // Migration
        if ($this->get('Category Root Key') == $store->get(
                'Store Family Category Key'
            )
        ) {


            $sql = sprintf(
                "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $this->get('Category Store Key'),
                prepare_mysql($this->get('Category Code'))
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $category_key = $row['Product Family Key'];
                }
            }


        }


        $sql = sprintf(
            'SELECT `Page Key` FROM `Page Store Dimension` WHERE `Page Store Section Type`="Family"  AND  `Page Parent Key`=%d ', $category_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $page_key = $row['Page Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $this->webpage         = new Page($row['Page Key']);
        $this->webpage->editor = $this->editor;


        // Temporal should be take off bcuse page should be created when product is createss
        /*
        if (!$this->webpage->id) {

            $page_data=array(
                'Page Store Content Display Type'=>'Template',
                'Page Store Content Template Filename'=>'product',
                'Page State'=>'Online'

            );
            include_once 'class.Store.php';

            $store=new Store($this->get('Product Store Key'));

            foreach ($store->get_sites('objects') as $site) {

                $product_page_key=$site->add_product_page($this->id, $page_data);
                $this->webpage=new Page($product_page_key);
            }


        }

        */

    }


    function create_product_timeseries($data, $fork_key = 0) {

        if ($this->get('Category Branch Type') == 'Root') {
            if ($fork_key) {


                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s WHERE `Fork Key`=%d ",
                    0,
                    prepare_mysql('0'),
                    $fork_key
                );

                $this->db->exec($sql);

            }

            return;
        }


        $data['Timeseries Parent']     = 'Category';
        $data['Timeseries Parent Key'] = $this->id;

        $timeseries = new Timeseries('find', $data, 'create');
        if ($timeseries->id) {


            if ($this->data['Product Category Valid From'] != '') {
                $from = date(
                    'Y-m-d', strtotime($this->get('Product Category Valid From'))
                );

            } else {
                $from = '';
            }

            if ($this->get('Product Category Status') == 'Discontinued') {
                $to = date(
                    'Y-m-d', strtotime($this->get('Product Category Valid To'))
                );
            } else {
                $to = date('Y-m-d');
            }

            $sql = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ',
                $timeseries->id, prepare_mysql($from)
            );

            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );

            }

            $sql = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ', $timeseries->id, prepare_mysql($to)
            );

            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );

            }

            if ($from and $to) {

                $this->update_product_timeseries_record($timeseries, $to, $from, $fork_key);


            }

            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }


        }

    }


    function update_product_timeseries_record($timeseries, $to, $from, $fork_key) {

        if ($this->get('Category Branch Type') == 'Root') {


            if ($fork_key) {


                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ",
                    0,
                    $timeseries->id,
                    $fork_key
                );

                $this->db->exec($sql);

            }

            return;
        }

        $dates = date_frequency_range(
            $this->db, $timeseries->get('Timeseries Frequency'), $from, $to
        );

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ",
                count($dates),
                $timeseries->id,
                $fork_key
            );

            $this->db->exec($sql);
        }

        $timeseries->update(
            array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
        );


        $index = 0;

        foreach ($dates as $date_frequency_period) {
            $index ++;
            list($invoices, $customers, $net, $dc_net)
                = $this->get_product_timeseries_record_data(
                $timeseries, $date_frequency_period
            );


            $_date = gmdate(
                'Y-m-d', strtotime($date_frequency_period['from'].' +0:00')
            );

            if ($invoices != 0 or $customers != 0 or $net != 0) {
                list($timeseries_record_key, $date)
                    = $timeseries->create_record(
                    array('Timeseries Record Date' => $_date)
                );
                $sql = sprintf(
                    'UPDATE `Timeseries Record Dimension` SET `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,`Timeseries Record Float A`=%.2f ,`Timeseries Record Float B`=%.2f ,`Timeseries Record Type`=%s WHERE `Timeseries Record Key`=%d',
                    $invoices, $customers, $net, $dc_net, prepare_mysql('Data'), $timeseries_record_key

                );

                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();

                if ($update_sql->rowCount() or $date == date('Y-m-d')) {
                    $timeseries->update(
                        array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                    );
                }

            } else {
                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                );

                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();
                if ($update_sql->rowCount()) {
                    $timeseries->update(
                        array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                    );

                }

            }

            if ($fork_key) {
                $skip_every = 1;
                if ($index % $skip_every == 0) {
                    $sql = sprintf(
                        "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $index, $fork_key
                    );
                    $this->db->exec($sql);
                    print "$sql\n";

                }

            }

            $timeseries->update_stats();


        }

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index,
                $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }


    function get_product_timeseries_record_data($timeseries, $date_frequency_period) {


        $product_ids = $this->get_product_ids();


        if ($product_ids == '') {
            return array(
                0,
                0,
                0,
                0
            );
        }

        if ($timeseries->get('Timeseries Scope') == 'Sales') {


            $sql = sprintf(
                "SELECT count(DISTINCT `Invoice Key`)  AS invoices,count(DISTINCT `Customer Key`)  AS customers,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net

			FROM `Order Transaction Fact` WHERE `Product ID` IN (%s)  AND `Invoice Key`>0 AND  `Invoice Date`>=%s  AND   `Invoice Date`<=%s  ", $product_ids,
                prepare_mysql($date_frequency_period['from']), prepare_mysql($date_frequency_period['to'])
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $invoices  = $row['invoices'];
                    $customers = $row['customers'];
                    $net       = $row['net'];
                    $dc_net    = $row['dc_net'];
                } else {
                    $invoices  = 0;
                    $customers = 0;
                    $net       = 0;
                    $dc_net    = 0;
                }

                return array(
                    $invoices,
                    $customers,
                    $net,
                    $dc_net
                );

            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


    }

    function get_product_ids() {

        $product_ids  = '';
        $sql          = sprintf(
            'SELECT `Subject Key` FROM `Category Bridge` WHERE `Category Key`=%d AND `Subject Key`>0 ', $this->id
        );
        $product_ids  = '';
        $subject_type = '';
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $product_ids .= $row['Subject Key'].',';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $product_ids = preg_replace('/\,$/', '', $product_ids);

        if ($product_ids != '' and $this->get('Category Subject') == 'Category') {
            $category_ids = $product_ids;
            $product_ids  = '';
            $sql          = sprintf(
                'SELECT `Subject Key`  FROM `Category Bridge` WHERE `Category Key` IN (%s) AND `Subject Key`>0 ', $category_ids
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $product_ids .= $row['Subject Key'].',';
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
            $product_ids = preg_replace('/\,$/', '', $product_ids);

        }

        //print $product_ids;

        return $product_ids;

    }


    function update_product_category_sales($interval, $this_year = true, $last_year = true) {

        include_once 'utils/date_functions.php';


        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)
            = calculate_interval_dates($this->db, $interval);

        if ($this_year) {

            $sales_product_category_data
                = $this->get_product_category_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Product Category $db_interval Acc Customers"          => $sales_product_category_data['customers'],
                "Product Category $db_interval Acc Invoices"           => $sales_product_category_data['invoices'],
                "Product Category $db_interval Acc Profit"             => $sales_product_category_data['profit'],
                "Product Category $db_interval Acc Invoiced Amount"    => $sales_product_category_data['net'],
                "Product Category $db_interval Acc Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $db_interval Acc Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $db_interval Acc Quantity Delivered" => $sales_product_category_data['delivered'],
                "Product Category DC $db_interval Acc Profit"          => $sales_product_category_data['dc_net'],
                "Product Category DC $db_interval Acc Invoiced Amount" => $sales_product_category_data['dc_profit']
            );
            $this->update($data_to_update, 'no_history');

        }

        if ($from_date_1yb and $last_year) {

            $sales_product_category_data
                = $this->get_product_category_sales_data(
                $from_date_1yb, $to_1yb
            );

            $data_to_update = array(
                "Product Category $db_interval Acc 1YB Customers"          => $sales_product_category_data['customers'],
                "Product Category $db_interval Acc 1YB Invoices"           => $sales_product_category_data['invoices'],
                "Product Category $db_interval Acc 1YB Profit"             => $sales_product_category_data['profit'],
                "Product Category $db_interval Acc 1YB Invoiced Amount"    => $sales_product_category_data['net'],
                "Product Category $db_interval Acc 1YB Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $db_interval Acc 1YB Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $db_interval Acc 1YB Quantity Delivered" => $sales_product_category_data['delivered'],
                "Product Category DC $db_interval Acc 1YB Profit"          => $sales_product_category_data['dc_net'],
                "Product Category DC $db_interval Acc 1YB Invoiced Amount" => $sales_product_category_data['dc_profit']
            );
            $this->update($data_to_update, 'no_history');

        }


        if (in_array(
            $db_interval, [
            'Total',
            'Year To Date',
            'Quarter To Date',
            'Week To Date',
            'Month To Date',
            'Today'
        ]
        )) {

            $this->update(['Product Category Acc To Day Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

        } elseif (in_array(
            $db_interval, [
            '1 Year',
            '1 Month',
            '1 Week',
            '1 Quarter'
        ]
        )) {

            $this->update(['Product Category Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        } elseif (in_array(
            $db_interval, [
            'Last Month',
            'Last Week',
            'Yesterday',
            'Last Year'
        ]
        )) {

            $this->update(['Product Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        }


    }

    function get_product_category_sales_data($from_date, $to_date) {

        $sales_product_category_data = array(
            'customers' => 0,
            'invoices'  => 0,
            'net'       => 0,
            'profit'    => 0,
            'ordered'   => 0,
            'invoiced'  => 0,
            'delivered' => 0,
            'dc_net'    => 0,
            'dc_profit' => 0,

        );

        $product_ids = $this->get_product_ids();


        if ($product_ids != '' and $this->get('Category Branch Type') != 'Root') {

            $sql = sprintf(
                "SELECT
		ifnull(count(DISTINCT `Customer Key`),0) AS customers,
		ifnull(count(DISTINCT `Invoice Key`),0) AS invoices,
		round(ifnull(sum( `Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` +(  `Cost Supplier`/`Invoice Currency Exchange Rate`)  ),0),2) AS profit,
		round(ifnull(sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`),0),2) AS net ,
		round(ifnull(sum(`Shipped Quantity`),0),1) AS delivered,
		round(ifnull(sum(`Order Quantity`),0),1) AS ordered,
		round(ifnull(sum(`Invoice Quantity`),0),1) AS invoiced,
		round(ifnull(sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`),0),2) AS dc_net,
		round(ifnull(sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Cost Supplier`)*`Invoice Currency Exchange Rate`),0),2) AS dc_profit
		FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE    `Invoice Key` IS NOT NULL  AND  `Product ID` IN (%s) %s %s ", $product_ids, ($from_date ? sprintf(
                'and `Invoice Date`>=%s', prepare_mysql($from_date)
            ) : ''), ($to_date ? sprintf(
                'and `Invoice Date`<%s', prepare_mysql($to_date)
            ) : '')

            );
            //print "$sql\n";
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $sales_product_category_data['customers']
                                                            = $row['customers'];
                    $sales_product_category_data['invoices']
                                                            = $row['invoices'];
                    $sales_product_category_data['net']     = $row['net'];
                    $sales_product_category_data['profit']  = $row['profit'];
                    $sales_product_category_data['ordered'] = $row['ordered'];
                    $sales_product_category_data['invoiced']
                                                            = $row['invoiced'];
                    $sales_product_category_data['delivered']
                                                            = $row['delivered'];
                    $sales_product_category_data['dc_net']  = $row['dc_net'];
                    $sales_product_category_data['dc_profit']
                                                            = $row['dc_profit'];


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            //print "$sql\n";
        }

        return $sales_product_category_data;
    }


    function get_products_subcategories_status_numbers($options = '') {

        $elements_numbers = array(
            'InUse'    => 0,
            'NotInUse' => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num ,`Product Category Status` FROM  `Product Category Dimension` P LEFT JOIN `Category Dimension` C ON (C.`Category Key`=P.`Product Category Key`)  WHERE `Category Parent Key`=%d  GROUP BY  `Product Category Status`   ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($options == 'Formatted') {
                    $elements_numbers[$row['Product Category Status']] = number(
                        $row['num']
                    );

                } else {
                    $elements_numbers[$row['Product Category Status']]
                        = $row['num'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $elements_numbers;

    }

    function update_product_category_status() {

        $elements_numbers = array(
            'In Process'    => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinuing' => 0,
            'Discontinued'  => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num ,`Product Status` AS status FROM  `Product Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Product ID`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Product' GROUP BY  `Product Status`   ",
            $this->id

        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $elements_numbers[$row['status']] = number($row['num']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($elements_numbers['Discontinued'] > 0 and $elements_numbers['Active'] == 0) {
            $this->data['Product Category Status'] = 'Discontinued';
        } elseif ($elements_numbers['Discontinuing'] > 0 and $elements_numbers['Active'] == 0) {
            $this->data['Product Category Status'] = 'Discontinuing';
        } elseif ($elements_numbers['Suspended'] > 0 and $elements_numbers['Active'] == 0) {
            $this->data['Product Category Status'] = 'Suspended';
        } elseif ($elements_numbers['In Process'] > 0 and $elements_numbers['Active'] == 0) {
            $this->data['Product Category Status'] = 'In Process';
        } else {
            if ($elements_numbers['Active'] > 0) {
                $this->data['Product Category Status'] = 'Active';
            } else {
                $this->data['Product Category Status'] = 'In Process';

            }
        }

        $sql = sprintf(
            "UPDATE `Product Category Dimension` SET `Product Category Status`=%s,`Product Category In Process Products`=%d,`Product Category Active Products`=%d,`Product Category Suspended Products`=%d,`Product Category Discontinued Products`=%d  WHERE `Product Category Key`=%d",
            prepare_mysql($this->data['Product Category Status']), $elements_numbers['In Process'], $elements_numbers['Active'], $elements_numbers['Suspended'], $elements_numbers['Discontinued'],

            $this->id
        );
        //print "$sql\n";

        $this->db->exec($sql);


    }

    function update_product_stock_status() {

        $elements_numbers = array(
            'Surplus'      => 0,
            'Optimal'      => 0,
            'Low'          => 0,
            'Critical'     => 0,
            'Out_Of_Stock' => 0,
            'Error'        => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num ,`Part Stock Status` FROM  `Part Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Part SKU`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Part' GROUP BY  `Part Stock Status`   ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $elements_numbers[$row['Part Stock Status']] = number(
                    $row['num']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `Product Category Dimension` SET `Product Category Number Surplus Parts`=%d ,`Product Category Number Optimal Parts`=%d ,`Product Category Number Low Parts`=%d ,`Product Category Number Critical Parts`=%d ,`Product Category Number Out Of Stock Parts`=%d ,`Product Category Number Error Parts`=%d  WHERE `Product Category Key`=%d",
            $elements_numbers['Surplus'], $elements_numbers['Optimal'], $elements_numbers['Low'], $elements_numbers['Critical'], $elements_numbers['Out_Of_Stock'], $elements_numbers['Error'],
            $this->id
        );

        $this->db->exec($sql);


    }

    function update_product_category_previous_years_data() {

        $data_1y_ago = $this->get_product_category_sales_data(
            date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00')
        );
        $data_2y_ago = $this->get_product_category_sales_data(
            date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year'))
        );
        $data_3y_ago = $this->get_product_category_sales_data(
            date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year'))
        );
        $data_4y_ago = $this->get_product_category_sales_data(
            date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year'))
        );
        $data_5y_ago = $this->get_product_category_sales_data(
            date('Y-01-01 00:00:00', strtotime('-5 year')), date('Y-01-01 00:00:00', strtotime('-4 year'))
        );

        $data_to_update = array(
            "Product Category 1 Year Ago Customers"          => $data_1y_ago['customers'],
            "Product Category 1 Year Ago Invoices"           => $data_1y_ago['invoices'],
            "Product Category 1 Year Ago Profit"             => $data_1y_ago['profit'],
            "Product Category 1 Year Ago Invoiced Amount"    => $data_1y_ago['net'],
            "Product Category 1 Year Ago Quantity Ordered"   => $data_1y_ago['ordered'],
            "Product Category 1 Year Ago Quantity Invoiced"  => $data_1y_ago['invoiced'],
            "Product Category 1 Year Ago Quantity Delivered" => $data_1y_ago['delivered'],
            "Product Category DC 1 Year Ago Profit"          => $data_1y_ago['dc_net'],
            "Product Category DC 1 Year Ago Invoiced Amount" => $data_1y_ago['dc_profit'],

            "Product Category 2 Year Ago Customers"          => $data_2y_ago['customers'],
            "Product Category 2 Year Ago Invoices"           => $data_2y_ago['invoices'],
            "Product Category 2 Year Ago Profit"             => $data_2y_ago['profit'],
            "Product Category 2 Year Ago Invoiced Amount"    => $data_2y_ago['net'],
            "Product Category 2 Year Ago Quantity Ordered"   => $data_2y_ago['ordered'],
            "Product Category 2 Year Ago Quantity Invoiced"  => $data_2y_ago['invoiced'],
            "Product Category 2 Year Ago Quantity Delivered" => $data_2y_ago['delivered'],
            "Product Category DC 2 Year Ago Profit"          => $data_2y_ago['dc_net'],
            "Product Category DC 2 Year Ago Invoiced Amount" => $data_2y_ago['dc_profit'],

            "Product Category 3 Year Ago Customers"          => $data_3y_ago['customers'],
            "Product Category 3 Year Ago Invoices"           => $data_3y_ago['invoices'],
            "Product Category 3 Year Ago Profit"             => $data_3y_ago['profit'],
            "Product Category 3 Year Ago Invoiced Amount"    => $data_3y_ago['net'],
            "Product Category 3 Year Ago Quantity Ordered"   => $data_3y_ago['ordered'],
            "Product Category 3 Year Ago Quantity Invoiced"  => $data_3y_ago['invoiced'],
            "Product Category 3 Year Ago Quantity Delivered" => $data_3y_ago['delivered'],
            "Product Category DC 3 Year Ago Profit"          => $data_3y_ago['dc_net'],
            "Product Category DC 3 Year Ago Invoiced Amount" => $data_3y_ago['dc_profit'],

            "Product Category 4 Year Ago Customers"          => $data_4y_ago['customers'],
            "Product Category 4 Year Ago Invoices"           => $data_4y_ago['invoices'],
            "Product Category 4 Year Ago Profit"             => $data_4y_ago['profit'],
            "Product Category 4 Year Ago Invoiced Amount"    => $data_4y_ago['net'],
            "Product Category 4 Year Ago Quantity Ordered"   => $data_4y_ago['ordered'],
            "Product Category 4 Year Ago Quantity Invoiced"  => $data_4y_ago['invoiced'],
            "Product Category 4 Year Ago Quantity Delivered" => $data_4y_ago['delivered'],
            "Product Category DC 4 Year Ago Profit"          => $data_4y_ago['dc_net'],
            "Product Category DC 4 Year Ago Invoiced Amount" => $data_4y_ago['dc_profit'],

            "Product Category 5 Year Ago Customers"          => $data_5y_ago['customers'],
            "Product Category 5 Year Ago Invoices"           => $data_5y_ago['invoices'],
            "Product Category 5 Year Ago Profit"             => $data_5y_ago['profit'],
            "Product Category 5 Year Ago Invoiced Amount"    => $data_5y_ago['net'],
            "Product Category 5 Year Ago Quantity Ordered"   => $data_5y_ago['ordered'],
            "Product Category 5 Year Ago Quantity Invoiced"  => $data_5y_ago['invoiced'],
            "Product Category 5 Year Ago Quantity Delivered" => $data_5y_ago['delivered'],
            "Product Category DC 5 Year Ago Profit"          => $data_5y_ago['dc_net'],
            "Product Category DC 5 Year Ago Invoiced Amount" => $data_5y_ago['dc_profit']
        );
        $this->update($data_to_update, 'no_history');
        $this->update(['Part Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

    }

    function update_product_category_previous_quarters_data() {


        include_once 'utils/date_functions.php';


        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_product_category_data
                = $this->get_product_category_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_product_category_data_1yb
                = $this->get_product_category_sales_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $data_to_update = array(

                "Product Category $i Quarter Ago Customers"          => $sales_product_category_data['customers'],
                "Product Category $i Quarter Ago Invoices"           => $sales_product_category_data['invoices'],
                "Product Category $i Quarter Ago Profit"             => $sales_product_category_data['profit'],
                "Product Category $i Quarter Ago Invoiced Amount"    => $sales_product_category_data['net'],
                "Product Category $i Quarter Ago Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $i Quarter Ago Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $i Quarter Ago Quantity Delivered" => $sales_product_category_data['delivered'],
                "Product Category DC $i Quarter Ago Profit"          => $sales_product_category_data['dc_net'],
                "Product Category DC $i Quarter Ago Invoiced Amount" => $sales_product_category_data['dc_profit'],

                "Product Category $i Quarter Ago 1YB Customers"          => $sales_product_category_data_1yb['customers'],
                "Product Category $i Quarter Ago 1YB Invoices"           => $sales_product_category_data_1yb['invoices'],
                "Product Category $i Quarter Ago 1YB Profit"             => $sales_product_category_data_1yb['profit'],
                "Product Category $i Quarter Ago 1YB Invoiced Amount"    => $sales_product_category_data_1yb['net'],
                "Product Category $i Quarter Ago 1YB Quantity Ordered"   => $sales_product_category_data_1yb['ordered'],
                "Product Category $i Quarter Ago 1YB Quantity Invoiced"  => $sales_product_category_data_1yb['invoiced'],
                "Product Category $i Quarter Ago 1YB Quantity Delivered" => $sales_product_category_data_1yb['delivered'],
                "Product Category DC $i Quarter Ago 1YB Profit"          => $sales_product_category_data_1yb['dc_net'],
                "Product Category DC $i Quarter Ago 1YB Invoiced Amount" => $sales_product_category_data_1yb['dc_profit']


            );
            $this->update($data_to_update, 'no_history');
        }
        $this->update(['Part Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

    }

    function get_categories($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.Category.php';
        }

        $type = 'Category';

        $categories = array();


        $sql = sprintf(
            "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject`=%s AND `Subject Key`=%d AND `Category Branch Type`!='Root'",
            prepare_mysql($type), $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $categories[$row['Category Key']] = new Category(
                        $row['Category Key']
                    );
                } else {
                    $categories[$row['Category Key']] = $row['Category Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $categories;


    }

    function get_category_data() {


        $type = 'Category';

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id, prepare_mysql($type)
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d", $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'   => $root_label,
                    'root_code'    => $root_code,
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'value'        => $value,
                    'category_key' => $row['Category Key']
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $category_data;
    }


}

?>
