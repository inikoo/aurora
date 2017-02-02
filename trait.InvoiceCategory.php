<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 22:12:35 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


trait InvoiceCategory {

    function update_invoice_category_sales($interval, $this_year = true, $last_year = true) {

        $to_date = '';

        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)
            = calculate_interval_dates($this->db, $interval);


        if ($this_year) {

            $sales_data = $this->get_invoice_sales_data($from_date, $to_date);
            //print "$from_date, $to_date\n";
            //print_r($sales_data);
            $data_to_update = array(
                "Invoice Category $db_interval Acc Discount Amount" => $sales_data['discount_amount'],
                "Invoice Category $db_interval Acc Amount"          => $sales_data['amount'],
                "Invoice Category $db_interval Acc Refunded Amount" => $sales_data['amount_refunded'],
                "Invoice Category $db_interval Acc Invoices"        => $sales_data['invoices'],
                "Invoice Category $db_interval Acc Refunds"         => $sales_data['refunds'],

                "Invoice Category $db_interval Acc Profit"             => $sales_data['profit'],
                "Invoice Category DC $db_interval Acc Amount"          => $sales_data['dc_amount'],
                "Invoice Category DC $db_interval Acc Refunded Amount" => $sales_data['dc_amount_refunded'],
                "Invoice Category DC $db_interval Acc Discount Amount" => $sales_data['dc_discount_amount'],
                "Invoice Category DC $db_interval Acc Profit"          => $sales_data['dc_profit']
            );

            $this->update($data_to_update, 'no_history');

        }

        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_invoice_sales_data(
                $from_date_1yb, $to_date_1yb
            );

            $data_to_update = array(
                "Invoice Category $db_interval Acc 1YB Discount Amount" => $sales_data['discount_amount'],
                "Invoice Category $db_interval Acc 1YB Amount"          => $sales_data['amount'],
                "Invoice Category $db_interval Acc 1YB Refunded Amount" => $sales_data['amount_refunded'],
                "Invoice Category $db_interval Acc 1YB Invoices"        => $sales_data['invoices'],
                "Invoice Category $db_interval Acc 1YB Refunds"         => $sales_data['refunds'],

                "Invoice Category $db_interval Acc 1YB Profit"             => $sales_data['profit'],
                "Invoice Category DC $db_interval Acc 1YB Amount"          => $sales_data['dc_amount'],
                "Invoice Category DC $db_interval Acc 1YB Refunded Amount" => $sales_data['dc_amount_refunded'],
                "Invoice Category DC $db_interval Acc 1YB Discount Amount" => $sales_data['dc_discount_amount'],
                "Invoice Category DC $db_interval Acc 1YB Profit"          => $sales_data['dc_profit']
            );

            $this->update($data_to_update, 'no_history');


        }


    }

    function get_invoice_sales_data($from_date, $to_date) {

        $sales_data = array(
            'discount_amount'    => 0,
            'amount'             => 0,
            'amount_refunded'    => 0,
            'invoices'           => 0,
            'refunds'            => 0,
            'paid'               => 0,
            'to_pay'             => 0,
            'profit'             => 0,
            'dc_amount'          => 0,
            'dc_amount_refunded' => 0,
            'dc_discount_amount' => 0,
            'dc_profit'          => 0,

        );


        $sql = sprintf(
            "SELECT sum(if(`Invoice Paid`='Yes',1,0)) AS paid  ,sum(if(`Invoice Paid`='No',1,0)) AS to_pay  , sum(if(`Invoice Type`='Invoice',1,0)) AS invoices  ,sum(if(`Invoice Type`!='Invoice'  ,1,0)) AS refunds  ,IFNULL(sum(`Invoice Items Discount Amount`),0) AS discounts,IFNULL(sum(`Invoice Total Net Amount`),0) net  ,IFNULL(sum(`Invoice Total Profit`),0) AS profit ,IFNULL(sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`),0) AS dc_discounts,IFNULL(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),0) dc_net  ,IFNULL(sum(`Invoice Total Profit`*`Invoice Currency Exchange`),0) AS dc_profit,

		IFNULL(sum( if(`Invoice Type`!='Invoice',  `Invoice Total Net Amount`,0)  ),0) refund_net ,
		IFNULL(sum(  if(`Invoice Type`!='Invoice', `Invoice Total Net Amount`,0)  *`Invoice Currency Exchange`),0) dc_refund_net
		 FROM `Category Bridge` B LEFT JOIN  `Invoice Dimension` I  ON ( `Subject Key`=`Invoice Key`)  WHERE `Subject`='Invoice' AND `Category Key`=%d AND  `Invoice Store Key`=%d %s %s", $this->id,
            $this->data['Category Store Key'], ($from_date ? sprintf(
            'and `Invoice Date`>%s', prepare_mysql($from_date)
        ) : ''), ($to_date ? sprintf(
            'and `Invoice Date`<%s', prepare_mysql($to_date)
        ) : '')
        );

      //  print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['discount_amount'] = $row['discounts'];
                $sales_data['amount']          = $row['net'];
                $sales_data['amount_refunded'] = $row['refund_net'];

                $sales_data['profit']   = $row['profit'];
                $sales_data['invoices'] = $row['invoices'];
                $sales_data['refunds']  = $row['refunds'];

                $sales_data['paid']   = $row['paid'];
                $sales_data['to_pay'] = $row['to_pay'];

                $sales_data['dc_discount_amount'] = $row['dc_discounts'];
                $sales_data['dc_amount']          = $row['dc_net'];
                $sales_data['dc_amount_refunded'] = $row['dc_refund_net'];
                $sales_data['dc_profit']          = $row['dc_profit'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sales_data;


    }

    function get_number_invoices($from, $to) {
        $number_invoices = 0;
        if ($this->data['Category Subject'] == 'Invoice') {

            $where = sprintf(
                " where `Subject`='Invoice' and  `Category Key`=%d", $this->id
            );
            $table
                   = ' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';


            if ($from) {
                $from = $from.' 00:00:00';
            }
            if ($to) {
                $to = $to.' 23:59:59';
            }
            $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');
            $where .= $where_interval['mysql'];


            $sql
                = "select count(Distinct I.`Invoice Key`) as total from $table   $where  ";


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_invoices = $row['total'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }

        return $number_invoices;

    }


    function update_invoice_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_invoice_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                "Category Invoice $i Year Ago Discount Amount"    => $data_iy_ago['discount_amount'],
                "Category Invoice $i Year Ago Amount"             => $data_iy_ago['amount'],
                "Category Invoice $i Year Ago Refunded Amount"    => $data_iy_ago['amount_refunded'],
                "Category Invoice $i Year Ago Invoices"           => $data_iy_ago['invoices'],
                "Category Invoice $i Year Ago Refunds"            => $data_iy_ago['refunds'],
                "Category Invoice $i Year Ago Profit"             => $data_iy_ago['profit'],
                "Category Invoice DC $i Year Ago Amount"          => $data_iy_ago['dc_amount'],
                "Category Invoice DC $i Year Ago Refunded Amount" => $data_iy_ago['dc_amount_refunded'],
                "Category Invoice DC $i Year Ago Discount Amount" => $data_iy_ago['dc_discount_amount'],
                "Category Invoice DC $i Year Ago Profit"          => $data_iy_ago['dc_profit']
            );


            $this->update($data_to_update, 'no_history');
        }

    }


    function update_invoice_previous_quarters_data() {


        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_invoice_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_data_1yb = $this->get_invoice_sales_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $data_to_update = array(
                "Category Invoice $i Quarter Ago Discount Amount"    => $sales_data['discount_amount'],
                "Category Invoice $i Quarter Ago Amount"             => $sales_data['amount'],
                "Category Invoice $i Quarter Ago Refunded Amount"    => $sales_data['amount_refunded'],
                "Category Invoice $i Quarter Ago Invoices"           => $sales_data['invoices'],
                "Category Invoice $i Quarter Ago Refunds"            => $sales_data['refunds'],
                "Category Invoice $i Quarter Ago Profit"             => $sales_data['profit'],
                "Category Invoice DC $i Quarter Ago Amount"          => $sales_data['dc_amount'],
                "Category Invoice DC $i Quarter Ago Refunded Amount" => $sales_data['dc_amount_refunded'],
                "Category Invoice DC $i Quarter Ago Discount Amount" => $sales_data['dc_discount_amount'],
                "Category Invoice DC $i Quarter Ago Profit"          => $sales_data['dc_profit'],

                "Category Invoice $i Quarter Ago 1YB Discount Amount"    => $sales_data_1yb['discount_amount'],
                "Category Invoice $i Quarter Ago 1YB Amount"             => $sales_data_1yb['amount'],
                "Category Invoice $i Quarter Ago 1YB Refunded Amount"    => $sales_data_1yb['amount_refunded'],
                "Category Invoice $i Quarter Ago 1YB Invoices"           => $sales_data_1yb['invoices'],
                "Category Invoice $i Quarter Ago 1YB Refunds"            => $sales_data_1yb['refunds'],
                "Category Invoice $i Quarter Ago 1YB Profit"             => $sales_data_1yb['profit'],
                "Category Invoice DC $i Quarter Ago 1YB Amount"          => $sales_data_1yb['dc_amount'],
                "Category Invoice DC $i Quarter Ago 1YB Refunded Amount" => $sales_data_1yb['dc_amount_refunded'],
                "Category Invoice DC $i Quarter Ago 1YB Discount Amount" => $sales_data_1yb['dc_discount_amount'],
                "Category Invoice DC $i Quarter Ago 1YB Profit"          => $sales_data_1yb['dc_profit']
            );
            $this->update($data_to_update, 'no_history');
        }

    }


}

?>
