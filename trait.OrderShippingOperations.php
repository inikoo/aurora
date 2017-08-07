<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:56:02 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderShippingOperations {

   function update_shipping_amount($value, $dn_key = false) {
        $value = sprintf("%.2f", $value);


        $this->update_field_switcher('Order Shipping Method', 'Set', 'no_history');


        $this->data['Order Shipping Net Amount'] = $value;
        $this->update_shipping($dn_key);

        $this->updated   = true;
        $this->new_value = $value;

        $this->update_totals();
        $this->apply_payment_from_customer_account();

    }

     function update_shipping($dn_key = false, $order_picked = true) {

        if (!$dn_key) {
            $dn_key = '';
        }


        if ($dn_key and $order_picked) {
            list(
                $shipping, $shipping_key, $shipping_method
                ) = $this->get_shipping($dn_key);
        } else {
            list(
                $shipping, $shipping_key, $shipping_method
                ) = $this->get_shipping();
        }


        //print "$shipping,$shipping_key,$shipping_method";
        if (!is_numeric($shipping)) {

            $this->data['Order Shipping Net Amount'] = 0;
            $this->data['Order Shipping Tax Amount'] = 0;
        } else {

            $this->data['Order Shipping Net Amount'] = $shipping;
            $this->data['Order Shipping Tax Amount'] = $shipping * $this->data['Order Tax Rate'];
        }


        $this->update_field_switcher('Order Shipping Method', $shipping_method, 'no_history');


        if (!$dn_key) {

            $sql = sprintf(
                'DELETE FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Transaction Type`="Shipping" ', $this->id
            );
        } else {
            $sql = sprintf(
                'DELETE FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Transaction Type`="Shipping"  AND `Delivery Note Key`=%d AND `Invoice Key` IS NULL', $this->id, $dn_key
            );


        }


        $this->db->exec($sql);

        if (!($this->data['Order Shipping Net Amount'] == 0 and $this->data['Order Shipping Tax Amount'] == 0)) {
            $sql = sprintf(
                "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,
				`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
				`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)  VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ", $this->id, prepare_mysql($this->data['Order Date']),
                prepare_mysql('Shipping'), $shipping_key, prepare_mysql(_('Shipping')), $this->data['Order Shipping Net Amount'], $this->data['Order Shipping Net Amount'],
                prepare_mysql($this->data['Order Tax Code']), $this->data['Order Shipping Tax Amount'],


                prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key)

            );

            $this->db->exec($sql);
        }


      

    }

    function get_shipping($dn_key = false) {


        if ($this->data['Order Number Items'] == 0) {
            return array(
                0,
                0,
                'No Applicable'
            );
        }


        if ($this->data['Order For Collection'] == 'Yes') {
            return array(
                0,
                0,
                'No Applicable'
            );
        }

        if ($this->data['Order Shipping Method'] == 'Set') {

            //print $this->data['Order Shipping Net Amount'].'xx';
            return array(
                ($this->data['Order Shipping Net Amount'] == '' ? 0 : $this->data['Order Shipping Net Amount']),
                0,
                'Set'
            );
        }


        if (in_array(
            $this->data['Order Ship To Country Code'], array(
                                                         'GBR',
                                                         'JEY',
                                                         'GGY',
                                                         'IMN'
                                                     )
        )) {
            include_once 'utils/geography_functions.php';

            $postcode = gbr_postcode_first_part(
                $this->data['Order Ship To Postal Code']
            );
        } else {
            $postcode = $this->data['Order Ship To Postal Code'];
        }


        $sql = sprintf(
            "SELECT `Shipping Destination Metadata`,`Shipping Key`,`Shipping Metadata`,`Shipping Price Method`  FROM `Shipping Dimension`  WHERE (SELECT %s LIKE `Shipping Destination Metadata` ) AND  `Shipping Destination Type`='Country' AND `Shipping Destination Code`=%s  AND `Shipping Secondary Destination Check`='Post Code' AND `Store Key`=%d ",
            prepare_mysql($postcode), prepare_mysql($this->data['Order Ship To Country Code']), $this->data['Order Store Key']

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                list($shipping, $method) = $this->get_shipping_from_method(
                    $row['Shipping Price Method'], $row['Shipping Metadata'], $dn_key
                );

                return array(
                    $shipping,
                    $row['Shipping Key'],
                    $method
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT `Shipping Key`,`Shipping Metadata`,`Shipping Price Method` FROM `Shipping Dimension`  LEFT JOIN kbase.`Country Dimension` ON (`Country Code`=`Shipping Destination Code`)   WHERE  `Shipping Destination Type`='Country' AND `Country 2 Alpha Code`=%s  AND   `Shipping Secondary Destination Check`='None'  AND `Store Key`=%d  ",
            prepare_mysql($this->data['Order Delivery Address Country 2 Alpha Code']), $this->data['Order Store Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                list($shipping, $method) = $this->get_shipping_from_method(
                    $row['Shipping Price Method'], $row['Shipping Metadata'], $dn_key
                );

                return array(
                    $shipping,
                    $row['Shipping Key'],
                    $method
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            0,
            0,
            'TBC'
        );


    }

    function get_shipping_from_method($type, $metadata, $dn_key = false) {


        switch ($type) {

            case('Step Order Items Net Amount'):
                return $this->get_shipping_Step_Order_Items_Net_Amount(
                    $metadata, $dn_key
                );
                break;

            case('Step Order Items Gross Amount'):
                return $this->get_shipping_Step_Order_Items_Gross_Amount(
                    $metadata, $dn_key
                );
                break;
            case('On Request'):
                return array(
                    0,
                    'TBC'
                );
                break;

        }

    }

    function get_shipping_Step_Order_Items_Net_Amount($metadata, $dn_key = false) {

        if ($dn_key) {
            $sql = sprintf(
                "SELECT sum( `Order Transaction Amount`*(`Delivery Note Quantity`/`Order Quantity`)  ) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                $this->id, $dn_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $amount = $row['amount'];
                } else {
                    $amount = 0;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        } else {
            $amount = $this->data['Order Items Net Amount'];
        }

        if ($amount == 0) {

            return array(
                0,
                'Calculated'
            );

        }
        $data = preg_split('/\;/', $metadata);

        foreach ($data as $item) {

            list($min, $max, $value) = preg_split('/\,/', $item);
            //print "$min,$max,$value\n";
            if ($min == '') {
                if ($amount < $max) {
                    return array(
                        $value,
                        'Calculated'
                    );
                }
            } elseif ($max == '') {
                if ($amount >= $min) {
                    return array(
                        $value,
                        'Calculated'
                    );
                }
            } elseif ($amount < $max and $amount >= $min) {
                return array(
                    $value,
                    'Calculated'
                );

            }


        }

        return array(
            0,
            'TBC'
        );

    }
    
     function use_calculated_shipping() {


        $this->update_field_switcher('Order Shipping Method', 'Calculated', 'no_history');

        $this->update_shipping();
        $this->updated = true;
        $this->update_totals();
        $this->apply_payment_from_customer_account();
        $this->new_value = $this->data['Order Shipping Net Amount'];

    }


}


?>
