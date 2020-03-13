<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2016 at 12:18:51 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'utils/date_functions.php';

/**
 * Trait ProductCategory
 */
trait ProductCategory {

    /**
     * @param     $data
     * @param int $fork_key
     */

    /**
     * @var \PDO
     */
    public $db;

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
        $data['editor']                = $this->editor;
        $timeseries                    = new Timeseries('find', $data, 'create');
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

                $timeseries->fast_update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                );

            }

            $sql = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ', $timeseries->id, prepare_mysql($to)
            );

            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->fast_update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                );

            }

            if ($from and $to) {

                $this->update_product_timeseries_record($timeseries, $to, $from, $fork_key);


            }

            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->fast_update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                );
            }


        }

    }

    /**
     * @param      $timeseries \Timeseries
     * @param      $to
     * @param      $from
     * @param bool $fork_key
     */
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
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates), $timeseries->id, $fork_key
            );

            $this->db->exec($sql);
        }

        $timeseries->fast_update(
            array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
        );


        $index = 0;

        foreach ($dates as $date_frequency_period) {
            $index++;


            // print_r($date_frequency_period);

            list($invoices, $customers, $net, $dc_net) = $this->get_product_timeseries_record_data(
                $timeseries, $date_frequency_period
            );


            // print "$invoices, $customers, $net, $dc_net \n";

            $_date = gmdate(
                'Y-m-d', strtotime($date_frequency_period['from'].' +0:00')
            );

            if ($invoices != 0 or $customers != 0 or $net != 0) {
                list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));


                $sql = "UPDATE `Timeseries Record Dimension` SET `Timeseries Record Integer A`=? ,`Timeseries Record Integer B`=?,`Timeseries Record Float A`=? ,`Timeseries Record Float B`=? ,`Timeseries Record Type`=? WHERE `Timeseries Record Key`=?";

                $update = $this->db->prepare($sql);

                $update->execute(
                    array(
                        $invoices,
                        $customers,
                        $net,
                        $dc_net,
                        'Data',
                        $timeseries_record_key
                    )
                );


                if ($update->rowCount() or $date == date('Y-m-d')) {
                    $timeseries->fast_update(
                        array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                    );
                }

            } else {
                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                );

                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();
                if ($update_sql->rowCount()) {
                    $timeseries->fast_update(
                        array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
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
            $date = gmdate('Y-m-d H:i:s');
            $sql  = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
            $this->db->prepare($sql)->execute(
                [
                    $date,
                    $date,
                    'timeseries_stats',
                    $timeseries->id,
                    $date,

                ]
            );


        }

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }

    /**
     * @param $timeseries \Timeseries
     * @param $date_frequency_period
     *
     * @return array
     */
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
                "SELECT count(DISTINCT `Invoice Key`)  AS invoices,count(DISTINCT `Customer Key`)  AS customers, round(ifnull(sum(`Order Transaction Amount`),0),2) AS net , 	round(ifnull(sum((`Order Transaction Amount`)*`Invoice Currency Exchange Rate`),0),2) AS dc_net FROM `Order Transaction Fact` WHERE `Product ID` IN (%s)  AND `Invoice Key`>0 AND  `Invoice Date`>=%s  AND   `Invoice Date`<=%s  ",
                $product_ids, prepare_mysql($date_frequency_period['from']), prepare_mysql($date_frequency_period['to'])
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

            }


        }

        return array(
            0,
            0,
            0,
            0
        );


    }

    function get_product_ids() {

        $product_ids = '';
        $sql         = "SELECT `Subject Key` FROM `Category Bridge` WHERE `Category Key`=? AND `Subject Key`>0";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $product_ids .= $row['Subject Key'].',';
        }

        $product_ids = preg_replace('/,$/', '', $product_ids);


        if ($product_ids != '' and $this->get('Category Subject') == 'Category') {
            $category_ids = preg_split('/\,/', $product_ids);
            $product_ids  = '';
            $sql          = "SELECT `Subject Key`  FROM `Category Bridge` WHERE `Category Key` IN (".str_pad('', count($category_ids) * 2 - 1, '?,').") AND `Subject Key`>0";
            $stmt         = $this->db->prepare($sql);
            $stmt->execute($category_ids);
            while ($row = $stmt->fetch()) {
                $product_ids .= $row['Subject Key'].',';
            }

            $product_ids = preg_replace('/,$/', '', $product_ids);

        }


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
                "Product Category $db_interval Acc Profit"             => round($sales_product_category_data['profit'], 2),
                "Product Category $db_interval Acc Invoiced Amount"    => round($sales_product_category_data['net'], 2),
                "Product Category $db_interval Acc Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $db_interval Acc Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $db_interval Acc Quantity Delivered" => $sales_product_category_data['delivered'],

            );

            $this->fast_update($data_to_update, 'Product Category Data');

            $data_to_update = array(
                "Product Category DC $db_interval Acc Profit"          => round($sales_product_category_data['dc_profit'], 2),
                "Product Category DC $db_interval Acc Invoiced Amount" => round($sales_product_category_data['dc_net'], 2)
            );
            $this->fast_update($data_to_update, 'Product Category DC Data');

        }

        if ($from_date_1yb and $last_year) {

            $sales_product_category_data = $this->get_product_category_sales_data(
                $from_date_1yb, $to_1yb
            );

            $data_to_update = array(
                "Product Category $db_interval Acc 1YB Customers"          => $sales_product_category_data['customers'],
                "Product Category $db_interval Acc 1YB Invoices"           => $sales_product_category_data['invoices'],
                "Product Category $db_interval Acc 1YB Profit"             => round($sales_product_category_data['profit'], 2),
                "Product Category $db_interval Acc 1YB Invoiced Amount"    => round($sales_product_category_data['net'], 2),
                "Product Category $db_interval Acc 1YB Quantity Ordered"   => $sales_product_category_data['ordered'],
                "Product Category $db_interval Acc 1YB Quantity Invoiced"  => $sales_product_category_data['invoiced'],
                "Product Category $db_interval Acc 1YB Quantity Delivered" => $sales_product_category_data['delivered'],

            );
            $this->fast_update($data_to_update, 'Product Category Data');

            $data_to_update = array(
                "Product Category DC $db_interval Acc 1YB Profit"          => round($sales_product_category_data['dc_profit'], 2),
                "Product Category DC $db_interval Acc 1YB Invoiced Amount" => round($sales_product_category_data['dc_net'], 2)
            );
            $this->fast_update($data_to_update, 'Product Category DC Data');

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

            $this->fast_update(['Product Category Acc To Day Updated' => gmdate('Y-m-d H:i:s')], 'Product Category Dimension');

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->fast_update(['Product Category Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')], 'Product Category Dimension');
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->fast_update(['Product Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'Product Category Dimension');
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
		round(ifnull(sum( `Order Transaction Amount` +(  `Cost Supplier`/`Invoice Currency Exchange Rate`)  ),0),2) AS profit,
		round(ifnull(sum(`Order Transaction Amount`),0),2) AS net ,
		round(ifnull(sum(`Delivery Note Quantity`),0),1) AS delivered,
		round(ifnull(sum(`Order Quantity`),0),1) AS ordered,
		round(ifnull(sum(`Delivery Note Quantity`),0),1) AS invoiced,
		round(ifnull(sum((`Order Transaction Amount`)*`Invoice Currency Exchange Rate`),0),2) AS dc_net,
		round(ifnull(sum((`Order Transaction Amount`+`Cost Supplier`)*`Invoice Currency Exchange Rate`),0),2) AS dc_profit
		FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE `Invoice Key` >0  AND  `Product ID` IN (%s) %s %s ", $product_ids, ($from_date ? sprintf('and `Invoice Date`>=%s', prepare_mysql($from_date)) : ''),
                ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

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
            "SELECT count(*) AS num ,`Product Category Status` FROM  `Product Category Dimension` P LEFT JOIN `Category Dimension` C ON (C.`Category Key`=P.`Product Category Key`)  WHERE `Category Parent Key`=%d  GROUP BY  `Product Category Status`   ", $this->id
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
                'SELECT count(*) AS num FROM `Product Dimension` WHERE `Product ID
                ` IN (%s) AND `Product Valid From` >= CURDATE() - INTERVAL 14 DAY', $product_ids

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $new = $row['num'];
                }
            }
        }

        $this->fast_update(array('Product Category New Products' => $new), 'Product Category Dimension');

    }

    function update_product_category_products_data() {


        $elements_status_numbers = array(
            'In Process'    => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinuing' => 0,
            'Discontinued'  => 0
        );
        $elements_availability_numbers = array(
            'Excess'    => 0,
            'Normal'        => 0,
            'Low'     => 0,
            'VeryLow' => 0,
            'OutofStock'  => 0,
            'Error'  => 0,
              'OnDemand'  => 0
        );

        $elements_active_web_status_numbers = array(
            'For Sale'     => 0,
            'Out of Stock' => 0,
            'Offline'      => 0

        );


        $category_status = 'In Process';

        $product_ids = $this->get_product_ids();


        if ($product_ids != '') {


            $product_ids = preg_split('/\,/', $product_ids);


            $sql = "SELECT count(*) AS num ,`Product Status` AS status FROM  `Product Dimension` P WHERE `Product ID` IN (".str_pad('', count($product_ids) * 2 - 1, '?,').")  GROUP BY  `Product Status`   ";


            $stmt = $this->db->prepare($sql);
            $stmt->execute($product_ids);
            while ($row = $stmt->fetch()) {
                $elements_status_numbers[$row['status']] = $row['num'];
            }


            //  print_r($elements_status_numbers);


            if ($elements_status_numbers['Discontinued'] > 0 and $elements_status_numbers['Active'] == 0 and $elements_status_numbers['Discontinuing'] == 0 and $elements_status_numbers['In Process'] == 0 and $elements_status_numbers['Suspended'] == 0) {
                $category_status = 'Discontinued';
            } elseif ($elements_status_numbers['Suspended'] > 0 and $elements_status_numbers['Active'] == 0 and $elements_status_numbers['Discontinuing'] == 0 and $elements_status_numbers['In Process'] == 0) {
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



            $sql = "SELECT count(*) AS num ,`Product Web State` AS web_state FROM  `Product Dimension` P WHERE `Product ID` IN (".str_pad('', count($product_ids) * 2 - 1, '?,').") AND `Product Status` IN ('Active','Discontinuing') GROUP BY  `Product Web State`   ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($product_ids);
            while ($row = $stmt->fetch()) {
                if ($row['web_state'] == 'Discontinued') {
                    $row['web_state'] = 'Offline';
                }
                $elements_active_web_status_numbers[$row['web_state']] += $row['num'];
            }

            $sql = "SELECT count(*) AS num ,`Product Availability State` AS availability_state FROM  `Product Dimension` P WHERE `Product ID` IN (".str_pad('', count($product_ids) * 2 - 1, '?,').")  GROUP BY  `Product Availability State`   ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($product_ids);
            while ($row = $stmt->fetch()) {

                $elements_availability_numbers[$row['availability_state']] += $row['num'];
            }


        }

        $update_data = array(
            'Product Category Status'                  => $category_status,
            'Product Category In Process Products'     => $elements_status_numbers['In Process'],
            'Product Category Active Products'         => $elements_status_numbers['Active'],
            'Product Category Suspended Products'      => $elements_status_numbers['Suspended'],
            'Product Category Discontinuing Products'  => $elements_status_numbers['Discontinuing'],
            'Product Category Discontinued Products'   => $elements_status_numbers['Discontinued'],
            'Product Category Active Web For Sale'     => $elements_active_web_status_numbers['For Sale'],
            'Product Category Active Web Out of Stock' => $elements_active_web_status_numbers['Out of Stock'],
            'Product Category Active Web Offline'      => $elements_active_web_status_numbers['Offline'],
            'Product Category Excess Availability Products'=>$elements_availability_numbers['Excess'],
            'Product Category Normal Availability Products'=>$elements_availability_numbers['Normal'],
            'Product Category Low Availability Products'=>$elements_availability_numbers['Low'],
            'Product Category Vary Low Availability Products'=>$elements_availability_numbers['VeryLow'],
            'Product Category Out of Stock Availability Products'=>$elements_availability_numbers['OutofStock'],
            'Product Category Error Availability Products'=>$elements_availability_numbers['Error'],
            'Product Category On Demand Availability Products'=>$elements_availability_numbers['OnDemand'],
        );


        $this->fast_update($update_data, 'Product Category Dimension');


        $active_web_families = 0;

        if ($this->data['Category Subject'] == 'Category') {
            $sql = "select count(*) as num from `Product Category Dimension` left join `Page Store Dimension` on (`Product Category Webpage Key`=`Page Key`)  where `Webpage State`='Online' and  `Product Category Department Category Key`=?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );
            if ($row = $stmt->fetch()) {
                $active_web_families = $row['num'];
            }

            $this->fast_update(['Product Category Active Web Families'=>$active_web_families], 'Product Category Dimension');
        }


        $this->get_data('id', $this->id);
        $sql = "SELECT B.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=? AND B.`Subject`='Category'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Category Key'] != $this->id) {
                $parent_category = get_object('Category', $row['Category Key']);
                $parent_category->update_product_category_products_data();
            }
        }


        $this->fork_index_elastic_search();


    }

    function get_webpage() {

        if (isset($this->data['Product Category Webpage Key'])) {
            $webpage_key = $this->data['Product Category Webpage Key'];
        } else {
            $webpage_key = 0;
        }


        $this->webpage         = get_object('Webpage', $webpage_key);
        $this->webpage->editor = $this->editor;


        return $this->webpage;

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

        $this->fast_update($data_to_update, 'Product Category Data');

        $this->fast_update(['Product Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'Product Category Dimension');


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

            // print_r($data_to_update);

            $this->fast_update($data_to_update, 'Product Category Data');
        }
        $this->fast_update(['Product Category Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'Product Category Dimension');

    }

    function get_categories($output = 'keys', $scope = 'all', $args = '') {

        $categories = array();
        $sql        = "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject Key`=? AND `Subject`='Category' AND `Category Branch Type`!='Root'";

        $params = array(
            $this->id
        );

        if ($scope == 'root_key') {
            $sql      .= ' and C.`Category Root Key`=?';
            $params[] = $args;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            $params
        );
        while ($row = $stmt->fetch()) {
            if ($output == 'objects') {
                $categories[$row['Category Key']] = get_object('Category', $row['Category Key']);
            } else {
                $categories[$row['Category Key']] = $row['Category Key'];
            }
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


    function get_deal_components($scope = 'keys', $options = 'Active') {

        switch ($options) {
            case 'Active':
                $where = 'AND `Deal Component Status`=\'Active\'';
                break;
            default:
                $where = '';
                break;
        }


        $deal_components = array();


        $sql = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension`  left join `Deal Campaign Dimension` on (`Deal Component Campaign Key`=`Deal Campaign Key`)   WHERE  `Deal Campaign Code`!='CU' and  `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=%d $where",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $deal_components[$row['Deal Component Key']] = get_object('DealComponent', $row['Deal Component Key']);
                } else {
                    $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $deal_components;


    }

    function update_product_category_history_records_data() {


        $table = 'Product Category History Bridge';

        $where_field = 'Category Key';


        $sql = sprintf(
            'SELECT count(*) AS num FROM `%s` WHERE  `%s`=%d ', $table, $where_field, $this->id
        );


        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array('Product Category Number History Records' => $number), 'Product Category Dimension'
        );


    }


    function update_product_category_donut_marketing_customers() {
        include_once 'utils/asset_marketing_customers.php';

        $store              = get_object('Store', $this->get('Store Key'));
        $targeted_threshold = min($store->properties('email_marketing_customers') * .05, 500);


        $targeted_customers = get_targeted_categories_customers(array(), $this->db, $this->id, $targeted_threshold);


        $spread_customers = get_spread_categories_customers(array(), $this->db, $this->id, 5 * $targeted_threshold);


        $customer = array_diff($spread_customers, $targeted_customers);


        $estimated_recipients = count($customer);


        $this->fast_update_json_field('Category Properties', 'donut_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Category Properties', 'donut_marketing_customers_last_updated', gmdate('U'));


    }

    function update_product_category_targeted_marketing_customers() {
        include_once 'utils/asset_marketing_customers.php';

        $store              = get_object('Store', $this->get('Store Key'));
        $targeted_threshold = min($store->properties('email_marketing_customers') * .05, 500);


        $estimated_recipients = count(get_targeted_categories_customers(array(), $this->db, $this->id, $targeted_threshold));


        $this->fast_update_json_field('Category Properties', 'targeted_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Category Properties', 'targeted_marketing_customers_last_updated', gmdate('U'));


    }

    function update_product_category_spread_marketing_customers() {
        include_once 'utils/asset_marketing_customers.php';

        $store                = get_object('Store', $this->get('Store Key'));
        $targeted_threshold   = 5 * min($store->properties('email_marketing_customers') * .05, 500);
        $estimated_recipients = count(get_spread_categories_customers(array(), $this->db, $this->id, $targeted_threshold));

        $this->fast_update_json_field('Category Properties', 'spread_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Category Properties', 'spread_marketing_customers_last_updated', gmdate('U'));

    }


}


