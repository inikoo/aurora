<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

use Aurora\Models\Utils\TaxCategory;

trait Order_Calculate_Totals
{


    function update_totals()
    {
        $old_total = $this->get('Order Total Amount');


        $number_items                    = 0;
        $number_with_deals               = 0;
        $number_items_with_out_of_stock  = 0;
        $number_with_problems            = 0;
        $total_items_gross               = 0;
        $total_items_out_of_stock_amount = 0;
        $total_items_net                 = 0;
        $total_items_discounts           = 0;

        $replacement_costs = 0;
        $items_cost        = 0;


        $number_products = 0;
        $number_services = 0;

        $sql  = "select count(*) as num,`Order Transaction Product Type` FROM `Order Transaction Fact` WHERE `Order Key`=? AND `Order Transaction Type`='Order'  and `Order Quantity`>0 group by `Order Transaction Product Type` ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Order Transaction Product Type'] == 'Product') {
                $number_products = $row['num'];
            } elseif ($row['Order Transaction Product Type'] == 'Service') {
                $number_services = $row['num'];
            }
        }


        $sql = "SELECT
		count(*) AS number_items,
		sum(`Cost Supplier`) AS cost,
		sum(`Order Transaction Amount`) AS net ,
		sum(`Order Transaction Total Discount Amount`) AS discounts ,sum(`Order Transaction Gross Amount`) AS gross ,
		sum(`Order Transaction Out of Stock Amount`) AS out_of_stock ,
		sum(if(`Order Transaction Total Discount Amount`!=0,1,0)) AS number_with_deals ,
		sum(if(`No Shipped Due Out of Stock`!=0,1,0)) AS number_items_with_out_of_stock
		FROM `Order Transaction Fact` WHERE `Order Key`=? AND `Order Transaction Type`='Order' ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $number_items                   = $row['number_items'];
            $number_with_deals              = $row['number_with_deals'];
            $number_items_with_out_of_stock = $row['number_items_with_out_of_stock'];


            $total_items_out_of_stock_amount = $row['out_of_stock'];
            $total_items_gross               = $row['gross'];
            $total_items_net                 = $row['net'];
            $total_items_discounts           = $row['discounts'];
        }


        $items_net_for_profit_calculation = 0;

        $sql = "SELECT sum(`Order Transaction Amount`) AS net ,sum(`Cost Supplier`) AS cost FROM `Order Transaction Fact` WHERE `Order Key`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $items_net_for_profit_calculation = $row['net'];
            $items_cost                       = $row['cost'];
        }


        $sql  = "SELECT sum(`Delivery Note Items Cost`) AS replacements_cost FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=? AND `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $replacement_costs = $row['replacements_cost'];
        }


        $profit = $items_net_for_profit_calculation - $items_cost - $replacement_costs;


        // to do AU-105 Order Refund/Replacement insights
        // $number_with_problems = ??

        $total_net = 0;
        $total_tax = 0;
        $data      = array();


        $sql = "SELECT  `Order Transaction Tax Category Key`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Order Key`=?  AND `Order Transaction Type`='Order' GROUP BY  `Order Transaction Tax Category Key`";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $data[$row['Order Transaction Tax Category Key']] = $row['net'];

            if ($this->data['Order Deal Amount Off'] != 0) {
                $data[$row['Order Transaction Tax Category Key']] -= $this->data['Order Deal Amount Off'];
            }
        }


        $sql  = "SELECT  `Order No Product Transaction Tax Category Key`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE `Order Key`=?  AND `Type`='Order' GROUP BY `Order No Product Transaction Tax Category Key`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if (isset($data[$row['Order No Product Transaction Tax Category Key']])) {
                $data[$row['Order No Product Transaction Tax Category Key']] += $row['net'];
            } else {
                $data[$row['Order No Product Transaction Tax Category Key']] = $row['net'];
            }
        }


        foreach ($data as $tax_category_key => $amount) {
            $total_net += round($amount, 2);


            $tax_category = new TaxCategory($this->db);
            $tax_category->loadWithKey($tax_category_key);

            if ($tax_category->id) {
                $tax = round($tax_category->get('Tax Category Rate') * $amount, 2);
            } else {
                $tax = 0;
            }

            $total_tax += $tax;
        }

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


        $sql =
            "SELECT `Transaction Type`,  sum(`Transaction Net Amount`) AS net , sum(`Transaction Gross Amount`) AS gross ,sum(`Transaction Total Discount Amount`) AS discounts  FROM `Order No Product Transaction Fact` WHERE `Order Key`=? AND `Type`='Order' GROUP BY `Transaction Type`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
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


        $payments = 0;

        $sql  = "SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Order Key`=? AND `Payment Transaction Status`='Completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $payments = round($row['amount'], 2);
        }


        $total_refunds = 0;

        $sql  = "SELECT sum(`Invoice Total Amount`) AS amount FROM `Invoice Dimension` WHERE `Invoice Order Key`=? AND `Invoice Type`='Refund'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $total_refunds = round($row['amount'], 2);
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
                'Order Replacement Cost'     => $replacement_costs,

                'Order Number Ordered Products' => $number_products,
                'Order Number Services'         => $number_services


            )
        );




        $this->update_payment_state();
        $this->update_order_estimated_weight();


        if ($old_total != $this->get('Order Total Amount')) {
            $this->fork_index_elastic_search();
        }
    }


    function update_order_estimated_weight()
    {
        $order_estimated_weight = 0;

        $sql =
            "SELECT sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Package Weight`) as order_estimated_weight  FROM `Order Transaction Fact` OTF left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  WHERE `Order Key`=? AND `Order Transaction Type`='Order'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        if ($row = $stmt->fetch()) {
            $order_estimated_weight = $row['order_estimated_weight'];
        }

        $this->fast_update(
            array(
                'Order Estimated Weight' => $order_estimated_weight,
            )
        );
    }


}



