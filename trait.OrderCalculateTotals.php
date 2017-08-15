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

        $number_items             = 0;
        $number_with_deals        = 0;
        $number_with_out_of_stock = 0;
        $number_with_problems     = 0;
        $total_weight             = 0;
        $total_items_gross        = 0;
        $total_items_out_of_stock        = 0;
        $total_items_net          = 0;
        $total_items_discounts    = 0;


        $sql = sprintf(
            "SELECT
		count(*) AS number_items,
		sum(`Estimated Weight`) AS weight,
		sum(`Order Transaction Amount`) AS net ,sum(`Order Transaction Total Discount Amount`) AS discounts ,sum(`Order Transaction Gross Amount`) AS gross ,sum(`Order Transaction Gross Amount`) AS out_of_stock ,
		sum(if(`Order Transaction Total Discount Amount`!=0,1,0)) AS number_with_deals ,
		sum(if(`No Shipped Due Out of Stock`!=0,1,0)) AS number_with_out_of_stock
		FROM `Order Transaction Fact` WHERE `Order Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_items             = $row['number_items'];
                $number_with_deals        = $row['number_with_deals'];
                $number_with_out_of_stock = $row['number_with_out_of_stock'];


                $total_items_out_of_stock=  $row['out_of_stock'];
                $total_weight             = $row['weight'];
                $total_items_gross        = $row['gross'];
                $total_items_net          = $row['net'];
                $total_items_discounts    = $row['discounts'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }



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
        $data=array();

        $sql = sprintf(
            "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE
		`Order Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
        );



        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data[$row['Transaction Tax Code']] = $row['net'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE
		`Order Key`=%d  GROUP BY  `Tax Category Code`  ", $this->id
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

      //  print_r($data);

        foreach ($data as $tax_code => $amount) {

            $tax_category = get_object('Tax_Category', $tax_code);


            $total_net += round($amount,2);
            $tax       = round($tax_category->get('Tax Category Rate') * $amount, 2);
            $total_tax += $tax;

        }


        $total = round($total_net + $total_tax,2);

        $shipping  = 0;
        $charges   = 0;
        $insurance = 0;
        $others    = 0;

        $sql = sprintf(
            "SELECT `Transaction Type`,  sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE
		`Order Key`=%d  GROUP BY  `Transaction Type`  ", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($row['Transaction Type']) {
                    case 'Shipping':
                        $shipping = $row['net'];
                        break;
                    case 'Charges':
                        $charges = $row['net'];
                        break;
                    case 'Insurance':
                        $insurance = $row['net'];
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
            'SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Order Key`=%d AND `Payment Transaction Status`="Completed" ',
            $this->id
        );

       // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = round($row['amount'],2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }





        $this->update(
            array(
                'Order Number Items'              => $number_items,
                'Order Number Items with Deals'   => $number_with_deals,
                'Order Number Items Out of Stock' => $number_with_out_of_stock,
                'Order Number Items Returned'     => $number_with_problems,
                'Order Items Net Amount'          => $total_items_net,
                'Order Items Gross Amount'        => $total_items_gross,
                'Order Items Discount Amount'     => $total_items_discounts,
                'Order Items Out of Stock Amount'     => $total_items_out_of_stock,



                'Order Number Items Out of Stock'     => $number_with_out_of_stock,




                'Order Shipping Net Amount'       => $shipping,
                'Order Charges Net Amount'        => $charges,
                'Order Insurance Net Amount'      => $insurance,
                'Order Total Net Amount'          => $total_net,
                'Order Total Tax Amount'          => $total_tax,
                'Order Total Amount'              => $total,
                'Order Payments Amount'           => $payments,
                'Order To Pay Amount'             => round($total - $payments,2)


            ), 'no_history'
        );

       


    }

}


?>
