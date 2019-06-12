<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderCalculateTotals {

    function update_totals() {


        $number_items                    = 0;
        $number_with_deals               = 0;
        $number_items_with_out_of_stock  = 0;
        $number_with_problems            = 0;
        $total_items_gross               = 0;
        $total_items_out_of_stock_amount = 0;
        $total_items_net                 = 0;
        $total_items_discounts           = 0;
        $profit                          = 0;
        $replacement_costs               = 0;
        $items_cost                      = 0;


        $sql = sprintf(
            "SELECT
		count(*) AS number_items,
		sum(`Cost Supplier`) AS cost,
		sum(`Order Transaction Amount`) AS net ,
		sum(`Order Transaction Total Discount Amount`) AS discounts ,sum(`Order Transaction Gross Amount`) AS gross ,
		sum(`Order Transaction Out of Stock Amount`) AS out_of_stock ,
		sum(if(`Order Transaction Total Discount Amount`!=0,1,0)) AS number_with_deals ,
		sum(if(`No Shipped Due Out of Stock`!=0,1,0)) AS number_items_with_out_of_stock
		FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Order Transaction Type`='Order' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                //print_r($row);

                $number_items                   = $row['number_items'];
                $number_with_deals              = $row['number_with_deals'];
                $number_items_with_out_of_stock = $row['number_items_with_out_of_stock'];


                $total_items_out_of_stock_amount = $row['out_of_stock'];
                $total_items_gross               = $row['gross'];
                $total_items_net                 = $row['net'];
                $total_items_discounts           = $row['discounts'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $items_net_for_profit_calculation = 0;

        $sql = sprintf(
            "SELECT sum(`Order Transaction Amount`) AS net ,sum(`Cost Supplier`) AS cost
		
		FROM `Order Transaction Fact` WHERE `Order Key`=%d  ", $this->id
        );
        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $items_net_for_profit_calculation = $row['net'];
                $items_cost                       = $row['cost'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        ////'Replacement & Shortages','Order','Replacement','Shortages','Sample','Donation'

        $sql = sprintf(
            "SELECT
	
		sum(`Delivery Note Items Cost`) AS replacements_cost 
		
		FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=%d  AND `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages') ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $replacement_costs = $row['replacements_cost'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $profit = $items_net_for_profit_calculation - $items_cost - $replacement_costs;

        $sql = sprintf(
            "SELECT
		count(DISTINCT `Order Transaction Fact Key`) AS number_with_problems
		FROM `Order Post Transaction Dimension` WHERE `Order Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_with_problems = $row['number_with_problems'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        //add items group by tax rate


        $total_net = 0;
        $total_tax = 0;
        $data      = array();


        //$base_tax_code='';


        $sql = sprintf(
            "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE
		`Order Key`=%d  AND `Order Transaction Type`='Order' GROUP BY  `Transaction Tax Code`  ", $this->id
        );

        //  print $sql;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data[$row['Transaction Tax Code']] = $row['net'];

                //   $base_tax_code=$row['Transaction Tax Code'];//todo <-- this is used for assign the tax code to the amount off and may not work of is different taxt codes


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if ($this->data['Order Deal Amount Off'] != 0) {
            $data[$row['Transaction Tax Code']] -= $this->data['Order Deal Amount Off'];
        }


        $sql = sprintf(
            "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE
		`Order Key`=%d  AND `Type`='Order' GROUP BY  `Tax Category Code`     ", $this->id

        );

        //print $sql;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if (isset($data[$row['Tax Category Code']])) {
                    $data[$row['Tax Category Code']] += $row['net'];
                } else {
                    $data[$row['Tax Category Code']] = $row['net'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($data as $tax_code => $amount) {


            $total_net += round($amount, 2);

            $tax_category = get_object('Tax_Category', $tax_code);
            if ($tax_category->id) {
                $tax = round($tax_category->get('Tax Category Rate') * $amount, 2);
            } else {
                $tax = 0;
            }

            $total_tax += $tax;

        }


        //     print_r($total_net);


        $total = round($total_net + $total_tax, 2);

        $shipping  = 0;
        $charges   = 0;
        $insurance = 0;


        $total_charges_discounts   = 0;
        $total_shipping_discounts  = 0;
        $total_insurance_discounts = 0;

        $total_charges_gross_amount   = 0;
        $total_shipping_gross_amount  = 0;
        $total_insurance_gross_amount = 0;


        $sql = sprintf(
            "SELECT `Transaction Type`,  sum(`Transaction Net Amount`) AS net , sum(`Transaction Gross Amount`) AS gross ,sum(`Transaction Total Discount Amount`) AS discounts  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d  AND `Type`='Order' GROUP BY  `Transaction Type`  ",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($row['Transaction Type']) {
                    case 'Shipping':
                        $shipping                    += $row['net'];
                        $total_shipping_discounts    += $row['discounts'];
                        $total_shipping_gross_amount += $row['gross'];
                        break;
                    case 'Charges':
                        $charges                    += $row['net'];
                        $total_charges_discounts    += $row['discounts'];
                        $total_charges_gross_amount += $row['gross'];
                        break;
                    case 'Insurance':
                        $insurance                    += $row['net'];
                        $total_insurance_discounts    += $row['discounts'];
                        $total_insurance_gross_amount += $row['gross'];

                        break;

                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $payments = 0;

        $sql = sprintf(
            'SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Order Key`=%d AND `Payment Transaction Status`="Completed" ', $this->id
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $total_refunds = 0;

        $sql = sprintf('SELECT sum(`Invoice Total Amount`) AS amount FROM `Invoice Dimension` WHERE `Invoice Order Key`=%d AND `Invoice Type`="Refund"  ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_refunds = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $total_balance = $total + $total_refunds;


        $margin = ($total_items_net == 0 ? '' : $profit / $total_items_net);

        if ($this->data['Order State'] == 'Cancelled') {

            $profit            = 0;
            $margin            = '';
            $items_cost        = 0;
            $replacement_costs = 0;
        }


        $this->fast_update(
            array(
                'Order Number Items'              => $number_items,
                'Order Number Items with Deals'   => $number_with_deals,
                'Order Number Items Out of Stock' => $number_items_with_out_of_stock,

                'Order Number Items Returned' => $number_with_problems,
                'Order Items Net Amount'      => $total_items_net,
                'Order Items Gross Amount'    => $total_items_gross,
                'Order Items Discount Amount' => $total_items_discounts,


                'Order Charges Gross Amount'   => $total_charges_gross_amount,
                'Order Shipping Gross Amount'  => $total_shipping_gross_amount,
                'Order Insurance Gross Amount' => $total_insurance_gross_amount,

                'Order Charges Discount Amount'   => $total_charges_discounts,
                'Order Shipping Discount Amount'  => $total_shipping_discounts,
                'Order Insurance Discount Amount' => $total_insurance_discounts,

                'Order Items Out of Stock Amount' => $total_items_out_of_stock_amount,


                'Order Shipping Net Amount'  => $shipping,
                'Order Charges Net Amount'   => $charges,
                'Order Insurance Net Amount' => $insurance,
                'Order Total Net Amount'     => $total_net,
                'Order Total Tax Amount'     => $total_tax,
                'Order Total Amount'         => $total,
                'Order Payments Amount'      => $payments,
                'Order To Pay Amount'        => round($total_balance - $payments, 2),
                'Order Total Refunds'        => $total_refunds,
                'Order Total Balance'        => $total_balance,
                'Order Profit Amount'        => $profit,
                'Order Margin'               => $margin,
                'Order Items Cost'           => $items_cost,
                'Order Replacement Cost'     => $replacement_costs

            )
        );

        $this->update_payment_state();
        $this->update_order_estimated_weight();

    }


    function update_order_estimated_weight() {

        $order_estimated_weight = 0;
        $sql                    = sprintf(
            "SELECT sum(`Order Quantity`*`Product Package Weight`) as order_estimated_weight  FROM `Order Transaction Fact` OTF left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  WHERE `Order Key`=%d AND `Order Transaction Type`='Order' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $order_estimated_weight = $row['order_estimated_weight'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $this->fast_update(
            array(
                'Order Estimated Weight' => $order_estimated_weight,


            )
        );


    }

    /**
     *
     * To be used only for fixing errors in payments (e.g. migration)
     */
    function update_order_payments() {


        $total    = $this->get('Order Total Amount');
        $payments = 0;

        $sql = sprintf(
            'SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Order Key`=%d AND `Payment Transaction Status`="Completed" ', $this->id
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $total_refunds = 0;

        $sql = sprintf('SELECT sum(`Invoice Total Amount`) AS amount FROM `Invoice Dimension` WHERE `Invoice Order Key`=%d AND `Invoice Type`="Refund"  ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_refunds = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $total_balance = $total + $total_refunds;

        $this->fast_update(
            array(


                'Order Payments Amount' => $payments,
                'Order To Pay Amount'   => round($total_balance - $payments, 2),
                'Order Total Refunds'   => $total_refunds,
                'Order Total Balance'   => $total_balance,


            )
        );

        $this->update_payment_state();


    }


}


?>
