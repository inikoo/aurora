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

    function create_product_timeseries($data, $fork_key = 0) {

        if ($this->get('Category Branch Type') == 'Root') {
            if ($fork_key) {


                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s WHERE `Fork Key`=%d ", 0, prepare_mysql('0'), $fork_key
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
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ', $timeseries->id, prepare_mysql($from)
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


    function update_product_timeseries_record($timeseries, $to, $from, $fork_key = false) {

        if ($this->get('Category Branch Type') == 'Root') {


            if ($fork_key) {


                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", 0, $timeseries->id, $fork_key
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
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates),
                $timeseries->id, $fork_key
            );

            $this->db->exec($sql);
        }

        $timeseries->update(
            array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
        );


        $index = 0;

        foreach ($dates as $date_frequency_period) {
            $index++;
            list($invoices, $customers, $net, $dc_net) = $this->get_product_timeseries_record_data(
                $timeseries, $date_frequency_period
            );


            $_date = gmdate(
                'Y-m-d', strtotime($date_frequency_period['from'].' +0:00')
            );

            if ($invoices != 0 or $customers != 0 or $net != 0) {
                list($timeseries_record_key, $date) = $timeseries->create_record(
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


                }

            }

            $timeseries->update_stats();


        }

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
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


        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb) = calculate_interval_dates($this->db, $interval);

        if ($this_year) {

            $sales_product_category_data = $this->get_product_category_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Product Category $db_interval Acc Customers"          => $sales_product_category_data['customers'],
                "Product Category $db_interval Acc Invoices"           => $sales_product_category_data['invoices'],
                "Product Category $db_interval Acc Profit"             => $sales_product_category_data['profit'],
                "Product Category $db_interval Acc Invoiced Amount"    => $sales_product_category_data['net'],
                "Product Category $db_interval Acc Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $db_interval Acc Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $db_interval Acc Quantity Delivered" => $sales_product_category_data['delivered'],
                "Product Category DC $db_interval Acc Profit"          => $sales_product_category_data['dc_profit'],
                "Product Category DC $db_interval Acc Invoiced Amount" => $sales_product_category_data['dc_net']
            );
            $this->update($data_to_update, 'no_history');

        }

        if ($from_date_1yb and $last_year) {

            $sales_product_category_data = $this->get_product_category_sales_data(
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
                "Product Category DC $db_interval Acc 1YB Profit"          => $sales_product_category_data['dc_profit'],
                "Product Category DC $db_interval Acc 1YB Invoiced Amount" => $sales_product_category_data['dc_net']
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
		FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE    `Invoice Key` IS NOT NULL  AND  `Product ID` IN (%s) %s %s ", $product_ids,
                ($from_date ? sprintf('and `Invoice Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

            );
            //print "$sql\n";
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $sales_product_category_data['customers'] = $row['customers'];
                    $sales_product_category_data['invoices']  = $row['invoices'];
                    $sales_product_category_data['net']       = $row['net'];
                    $sales_product_category_data['profit']    = $row['profit'];
                    $sales_product_category_data['ordered']   = $row['ordered'];
                    $sales_product_category_data['invoiced']  = $row['invoiced'];
                    $sales_product_category_data['delivered'] = $row['delivered'];
                    $sales_product_category_data['dc_net']    = $row['dc_net'];
                    $sales_product_category_data['dc_profit'] = $row['dc_profit'];


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
                    $elements_numbers[$row['Product Category Status']] = $row['num'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $elements_numbers;

    }

    function update_product_category_new_products() {

        $new = 0;

        $product_ids = $this->get_product_ids();

        if ($product_ids != '') {

            $sql = sprintf(
                'SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Id` IN (%s) AND `Product Valid From` >= CURDATE() - INTERVAL 14 DAY', $product_ids

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $new = $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
        }

        $this->update(array('Store New Products' => $new), 'no_history');

    }

    function update_product_category_products_data() {


        $old_active_products = $this->get('Product Category Active Products');

        $elements_status_numbers = array(
            'In Process'    => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinuing' => 0,
            'Discontinued'  => 0
        );

        $elements_active_web_status_numbers = array(
            'For Sale'     => 0,
            'Out of Stock' => 0,
            'Offline'      => 0

        );


        $category_status = 'Empty';

        $product_ids = $this->get_product_ids();

        //  print  $product_ids;

        if ($product_ids != '') {

            $sql = sprintf(
                "SELECT count(*) AS num ,`Product Status` AS status FROM  `Product Dimension` P WHERE `Product ID` IN (%s)  GROUP BY  `Product Status`   ", $product_ids

            );

            //   print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $elements_status_numbers[$row['status']] = $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            //  print_r($elements_status_numbers);

            if ($elements_status_numbers['Discontinued'] > 0 and $elements_status_numbers['Active'] == 0 and $elements_status_numbers['Discontinuing'] == 0 and $elements_status_numbers['In Process']
                == 0 and $elements_status_numbers['Suspended'] == 0
            ) {
                $category_status = 'Discontinued';
            } elseif ($elements_status_numbers['Suspended'] > 0 and $elements_status_numbers['Active'] == 0 and $elements_status_numbers['Discontinuing'] == 0
                and $elements_status_numbers['In Process'] == 0
            ) {
                $category_status = 'Suspended';
            } elseif ($elements_status_numbers['Discontinuing'] > 0 and $elements_status_numbers['Active'] == 0 and $elements_status_numbers['In Process'] == 0) {
                $category_status = 'Discontinuing';
            } elseif ($elements_status_numbers['In Process'] > 0 and $elements_status_numbers['Active'] == 0) {
                $category_status = 'In Process';
            } else {
                if ($elements_status_numbers['Active'] > 0) {
                    $category_status = 'Active';
                } else {
                    $category_status = 'In Process';

                }
            }


            //'For Sale','Out of Stock','Discontinued','Offline'

            $sql = sprintf(
                "SELECT count(*) AS num ,`Product Web State` AS web_state FROM  `Product Dimension` P WHERE `Product ID` IN (%s) AND `Product Status` IN ('Active','Discontinuing') GROUP BY  `Product Web State`   ",
                $product_ids

            );

            //  print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['web_state'] == 'Discontinued') {
                        $row['web_state'] = 'Offline';
                    }
                    $elements_active_web_status_numbers[$row['web_state']] += $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }

        //  print_r($elements_status_numbers);
        //print_r($elements_active_web_status_numbers);

        //exit;


        $update_data = array(
            'Product Category Status'                 => $category_status,
            'Product Category In Process Products'    => $elements_status_numbers['In Process'],
            'Product Category Active Products'        => $elements_status_numbers['Active'],
            'Product Category Suspended Products'     => $elements_status_numbers['Suspended'],
            'Product Category Discontinuing Products' => $elements_status_numbers['Discontinuing'],
            'Product Category Discontinued Products'  => $elements_status_numbers['Discontinued'],

            'Product Category Active Web For Sale'     => $elements_active_web_status_numbers['For Sale'],
            'Product Category Active Web Out of Stock' => $elements_active_web_status_numbers['Out of Stock'],
            'Product Category Active Web Offline'      => $elements_active_web_status_numbers['Offline']


        );

        $this->update($update_data, 'no_history');


        if ($old_active_products != $this->get('Product Category Active Products')) {
            $webpage = $this->get_webpage();
            if ($webpage->id) {
                $webpage->reindex_items();
                if ($webpage->updated) {
                    $webpage->publish();
                }
            }
            $sql = sprintf(
                'SELECT `Category Webpage Index Webpage Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Key`=%d  GROUP BY `Category Webpage Index Webpage Key` ', $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage = new Page($row['Category Webpage Index Webpage Key']);
                    $webpage->reindex_items();
                    if ($webpage->updated) {
                        $webpage->publish();
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


        $type = 'Category';
        $sql  = sprintf(
            "SELECT B.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id, prepare_mysql($type)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Category Key'] != $this->id) {
                    $parent_category = new Category($row['Category Key']);
                    $parent_category->update_product_category_products_data();
                }
            }
        }


    }

    function get_webpage() {


        include_once 'class.Page.php';

        $this->webpage         = new Page('scope', ($this->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $this->id);
        $this->webpage->editor = $this->editor;


        return $this->webpage;

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

        $update_data = array(
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],
            'Product Category Number Surplus Parts' => $elements_numbers['Surplus'],

        );

        $this->update($update_data, 'no_history');

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


            $sales_product_category_data     = $this->get_product_category_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_product_category_data_1yb = $this->get_product_category_sales_data(
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


    function create_category_webpage_index($force_reindex = false) {

        if ($this->get('Category Branch Type') == 'Root') {

            return;
        }


        if ($this->get('Category Subject') == 'Product') {

            $this->get_webpage();

            $null_stacks = false;

            $sql = sprintf(
                "SELECT `Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)    WHERE  `Category Key`=%d  ORDER BY ifnull(`Product Category Index Stack`,99999999),`Product Code File As`",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Product Category Index Product ID'] == '') {
                        $null_stacks = true;

                        $sql = sprintf(
                            'INSERT INTO `Product Category Index` (`Product Category Index Category Key`,`Product Category Index Product ID`,`Product Category Index Website Key`) VALUES (%d,%d,%d) ',
                            $this->id, $row['Product ID'], $this->webpage->id
                        );
                        $this->db->exec($sql);

                    }

                    if ($row['Product Category Index Stack'] == '') {
                        $null_stacks = true;
                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            //   exit;

            $stack_index = 0;
            if ($null_stacks or $force_reindex) {

                $sql = sprintf(
                    "SELECT `Product Category Index Key`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)    WHERE  `Category Key`=%d  ORDER BY ifnull(`Product Category Index Stack`,99999999),`Product Code File As`",
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        //     print_r($row);

                        $stack_index++;
                        $sql = sprintf(
                            'UPDATE `Product Category Index` SET `Product Category Index Stack`=%d WHERE `Product Category Index Key`=%d', $stack_index, $row['Product Category Index Key']
                        );
                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }


        } else {
            if ($this->get('Category Subject') == 'Category') {



                include_once 'class.Public_Webpage.php';
                include_once 'class.Public_Category.php';
                include_once 'utils/website_functions.php';


                $this->get_webpage();

                if (!$this->webpage->id) {
                    return;
                }

                $sql = sprintf('DELETE FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d  ', $this->webpage->id);
                $this->db->exec($sql);


                $anchor_section_key = 0;


                $sql = sprintf(
                    "SELECT  `Subject Key` ,`Category Code`
                        FROM `Category Bridge` B 
                        LEFT JOIN `Category Dimension` CAT ON (B.`Subject Key`=CAT.`Category Key`)  
                        LEFT JOIN `Product Category Dimension` P ON (B.`Subject Key`=P.`Product Category Key`)   
                        WHERE B.`Category Key`=%d AND  `Product Category Public`='Yes'   ORDER BY  `Category Code`  ",
                    $this->id
                );

               $stack=0;

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {



                        $subject = new Category($row['Subject Key']);



                        // TODO replace with $category->get('Product Category Webpage Key')

                        $subject_webpage = new Public_Webpage('scope', ($subject->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $subject->id);
                        $subject_webpage_key=$subject_webpage->id;



                        if ($subject_webpage->id) {

                            $image_375x250     = '';
                            $images_slides_show = $subject->get_images_slidesshow();

                            foreach ($images_slides_show as $image_data) {
                                if ($image_data['ratio'] == 1.5 and $image_375x250 == '') {
                                    $image_375x250 = '/image_root.php?id='.$image_data['id'];
                                }
                            }

                            foreach ($images_slides_show as $image_data) {
                                if ($image_data['ratio'] < 1.6 and $image_data['ratio'] > 1.4 and $image_375x250 == '') {
                                    $image_375x250 = '/image_root.php?id='.$image_data['id'];
                                }
                            }

                            $_data = array(
                                'code'   => $subject->get('Code'),
                                'label'   => $subject->get('Label'),
                                'hover_code'   => $subject->get('Code'),
                                'hover_label'   => $subject->get('Label'),
                                'image_375x250' => $image_375x250,
                                'category_key' => $subject->id,
                                'webpage_key'=> $subject_webpage_key,
                                'tags'=>'',
                                'guest'=>false,


                            );

                            $sql = sprintf(
                                'INSERT INTO `Category Webpage Index` (`Category Webpage Index Section Key`,`Category Webpage Index Content Data`,`Category Webpage Index Parent Category Key`,`Category Webpage Index Category Key`,`Category Webpage Index Webpage Key`,`Category Webpage Index Category Webpage Key`,`Category Webpage Index Stack`) VALUES (%d,%s,%d,%d,%d,%d,%d) ',
                                $anchor_section_key, prepare_mysql(json_encode($_data)), $this->id, $row['Subject Key'], $this->webpage->id, $subject_webpage_key,$stack
                            );


                            $this->db->exec($sql);
                            $stack++;

                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
        }


    }



    function create_stack_index($force_reindex = false) {

        if ($this->get('Category Branch Type') == 'Root') {

            return;
        }


        if ($this->get('Category Subject') == 'Product') {

            $this->get_webpage();

            $null_stacks = false;

            $sql = sprintf(
                "SELECT `Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)    WHERE  `Category Key`=%d  ORDER BY ifnull(`Product Category Index Stack`,99999999),`Product Code File As`",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Product Category Index Product ID'] == '') {
                        $null_stacks = true;

                        $sql = sprintf(
                            'INSERT INTO `Product Category Index` (`Product Category Index Category Key`,`Product Category Index Product ID`,`Product Category Index Website Key`) VALUES (%d,%d,%d) ',
                            $this->id, $row['Product ID'], $this->webpage->id
                        );
                        $this->db->exec($sql);

                    }

                    if ($row['Product Category Index Stack'] == '') {
                        $null_stacks = true;
                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            //   exit;

            $stack_index = 0;
            if ($null_stacks or $force_reindex) {

                $sql = sprintf(
                    "SELECT `Product Category Index Key`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)    WHERE  `Category Key`=%d  ORDER BY ifnull(`Product Category Index Stack`,99999999),`Product Code File As`",
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        //     print_r($row);

                        $stack_index++;
                        $sql = sprintf(
                            'UPDATE `Product Category Index` SET `Product Category Index Stack`=%d WHERE `Product Category Index Key`=%d', $stack_index, $row['Product Category Index Key']
                        );
                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }


        } elseif ($this->get('Category Subject') == 'Category') {


            include_once 'class.Public_Webpage.php';
            include_once 'class.Public_Category.php';
            include_once 'utils/website_functions.php';


            $this->get_webpage();

            if (!$this->webpage->id) {
                return;
            }


            $null_stacks = false;


            $content_data       = $this->webpage->get('Content Data');
            $anchor_section_key = 0;


            if (isset($content_data['sections'])) {
                foreach ($content_data['sections'] as $_key => $_data) {

                    //  print_r($_data);

                    if ($_data['type'] == 'anchor') {
                        $anchor_section_key = $_data['key'];

                        break;
                    }

                }
            }


            $sql = sprintf(
                "SELECT `Category Webpage Index Webpage Key`,`Category Code`,`Subject Key`,`Category Webpage Index Content Data`,`Category Webpage Index Key`,`Category Webpage Index Category Key`,`Category Webpage Index Stack`  
            FROM `Category Bridge` B LEFT JOIN `Category Dimension` CAT ON (B.`Subject Key`=CAT.`Category Key`)  LEFT JOIN 
            `Product Category Dimension` P ON (B.`Subject Key`=P.`Product Category Key`)   
            LEFT JOIN `Category Webpage Index` ON (`Category Webpage Index Category Key`=`Subject Key`  AND `Category Webpage Index Webpage Key`=%d )  
                WHERE B.`Category Key`=%d AND  `Product Category Public`='Yes'   ORDER BY  ifnull(`Category Webpage Index Stack`,99999999)", $this->webpage->id, $this->id

            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Category Webpage Index Category Key'] == '') {
                        $null_stacks = true;

                        $subject = new Category($row['Subject Key']);

                        $subject_webpage = new Public_Webpage('scope', ($subject->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $subject->id);

                        if ($subject_webpage->id) {

                            $image_375x250     = '';
                            $images_slidesshow = $subject->get_images_slidesshow();

                            foreach ($images_slidesshow as $image_data) {
                                if ($image_data['ratio'] == 1.5 and $image_375x250 == '') {
                                    $image_375x250 = '/image_root.php?id='.$image_data['id'];
                                }
                            }

                            foreach ($images_slidesshow as $image_data) {
                                if ($image_data['ratio'] < 1.6 and $image_data['ratio'] > 1.4 and $image_375x250 == '') {
                                    $image_375x250 = '/image_root.php?id='.$image_data['id'];
                                }
                            }

                            $_data = array(
                                'category_key'  => $subject->id,
                                'header_text'   => $subject->get('Label'),
                                'image_375x250' => $image_375x250,
                                'footer_text'   => $subject->get('Code'),
                            );

                            $sql = sprintf(
                                'INSERT INTO `Category Webpage Index` (`Category Webpage Index Section Key`,`Category Webpage Index Content Data`,`Category Webpage Index Parent Category Key`,`Category Webpage Index Category Key`,`Category Webpage Index Webpage Key`,`Category Webpage Index Category Webpage Key`) VALUES (%d,%s,%d,%d,%d,%d) ',
                                $anchor_section_key, prepare_mysql(json_encode($_data)), $this->id, $row['Subject Key'], $this->webpage->id, $subject_webpage->id
                            );


                            $this->db->exec($sql);


                        }

                    }

                    if ($row['Category Webpage Index Stack'] == '') {
                        $null_stacks = true;
                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            //   print_r($panels);

            //exit;
            $stack_index = 0;
            if ($null_stacks or $force_reindex) {

                $sql = sprintf(
                    "SELECT `Subject Key`,`Category Webpage Index Content Data`,`Category Webpage Index Key`,`Category Webpage Index Category Key`,`Category Webpage Index Stack` FROM `Category Bridge` B  LEFT JOIN `Product Category Dimension` P ON (B.`Subject Key`=P.`Product Category Key`)   LEFT JOIN `Category Webpage Index` ON (`Category Webpage Index Category Key`=`Subject Key`) LEFT JOIN `Category Dimension` Cat  ON  (Cat.`Category Key`=`Subject Key`)  WHERE  B.`Category Key`=%d AND `Product Category Public`='Yes' ORDER BY  ifnull(`Category Webpage Index Stack`,99999999)",
                    $this->id

                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        $stack_index++;
                        $sql = sprintf(
                            'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d', $stack_index, $row['Category Webpage Index Key']
                        );
                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }


            if (isset($content_data['sections'])) {

                foreach ($content_data['sections'] as $section_stack_index => $section_data) {

                    $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);


                }
            }


            $this->webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

        }


    }


    function update_subject_stack($stack_index, $subject_key) {


        //print "$stack_index  $subject_key ";

        if ($this->get('Category Subject') == 'Product') {

            $subjects = array();

            $sql = sprintf(
                "SELECT `Product Category Index Stack`,`Product Category Index Key`,`Product Category Index Product ID` AS subject_key,`Product Category Index Category Key` FROM `Product Category Index`    WHERE  `Product Category Index Category Key`=%d ",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['subject_key'] == $subject_key) {

                        $row['Product Category Index Stack'] = $stack_index;

                    }
                    $subjects[$row['Product Category Index Stack']] = $row['Product Category Index Key'];;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            ksort($subjects);
            $stack_index = 0;


            //print_r($subjects);

            //exit;

            foreach ($subjects as $tmp => $product_category_stack_key) {
                $stack_index++;

                $sql = sprintf(
                    'UPDATE `Product Category Index` SET `Product Category Index Stack`=%d WHERE `Product Category Index Key`=%d ', $stack_index, $product_category_stack_key
                );

                //  print "$sql\n";

                $this->db->exec($sql);

            }

        } elseif ($this->get('Category Subject') == 'Category') {

            $this->get_webpage();
            $content_data = $this->webpage->get('Content Data');


            if (isset($content_data['page_breaks'])) {
                $page_breaks = $content_data['page_breaks'];
                ksort($page_breaks);
            } else {
                $page_breaks = array();
            }


            print_r($page_breaks);


            $subjects = array();

            $sql = sprintf(
                "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Parent Category Key`=%d ",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['subject_key'] == $subject_key) {

                        $original_stack_index                = $row['Category Webpage Index Stack'];
                        $row['Category Webpage Index Stack'] = $stack_index;


                    }
                    $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            ksort($subjects);
            print_r($subjects);


            //  print_r($subjects);


            //  print  $original_stack_index;


            if ($original_stack_index < $stack_index) {

                $original_block      = false;
                $new_block           = false;
                $next_original_block = false;
                $next_new_block      = false;
                foreach ($page_breaks as $page_break_stack_index => $page_break) {


                    print " $page_break_stack_index\n";

                    if ($page_break_stack_index <= $original_stack_index) {
                        $original_block = $page_break_stack_index;
                    }
                    if ($page_break_stack_index <= $stack_index) {


                        $new_block = $page_break_stack_index;
                    }

                    if ($original_block and $original_block != $page_break_stack_index and !$next_original_block) {
                        $next_original_block = $page_break_stack_index;
                    }

                    if ($new_block and $new_block != $page_break_stack_index and !$next_new_block) {
                        $next_new_block = $page_break_stack_index;
                    }

                }


                print "\n* $original_block - $new_block   $stack_index   $next_original_block $next_new_block\n";


                if ($original_block and $next_new_block and $original_block != $next_new_block) {


                    if ($next_original_block) {
                        $page_breaks[$next_original_block - 1] = $page_breaks[$next_original_block];
                        unset($page_breaks[$next_original_block]);
                    }
                    if ($next_new_block) {
                        // $page_breaks[$next_new_block+1] = $page_breaks[$next_new_block];
                        // unset($page_breaks[$next_new_block]);
                    }

                    print "xxxx  $original_block $next_new_block \n ";

                    $subjects[(string)($stack_index - 1)] = $subjects[$stack_index];
                    unset($subjects[$stack_index]);
                    ksort($subjects);
                }


            }
            print_r($page_breaks);

            print_r($subjects);

            $content_data['page_breaks'] = $page_breaks;

            //  $this->webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


            //exit;
            $stack_index = 0;
            foreach ($subjects as $tmp => $category_webpage_stack_key) {
                $stack_index++;

                $sql = sprintf(
                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                );

                print "$sql\n";

                //   $this->db->exec($sql);

            }

        }


    }


}

?>
