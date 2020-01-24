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

        if($this->get('State Index') >= 90 or $this->get('State Index') <=0  ){
            return;
        }

        $value = sprintf("%.2f", $value);


        $this->update_field_switcher('Order Shipping Method', 'Set', 'no_history');


        $this->data['Order Shipping Net Amount'] = $value;


        $this->update_shipping($dn_key);

        $this->updated   = true;
        $this->new_value = $value;

        $this->update_totals();
        //$this->apply_payment_from_customer_account();

    }

    function update_shipping($dn_key = false, $order_picked = true) {

        if($this->get('State Index') >= 90 or $this->get('State Index') <=0  ){
            return;
        }


        if (!$dn_key) {
            $dn_key = '';
        }


        if ($dn_key and $order_picked) {
            list($shipping, $shipping_key, $shipping_method) = $this->get_shipping();
        } else {
            list($shipping, $shipping_key, $shipping_method) = $this->get_shipping();
        }


        if (!is_numeric($shipping)) {

            $net = 0;
            $tax = 0;
        } else {

            $net = $shipping;
            $tax= $shipping * $this->data['Order Tax Rate'];
        }


        $this->update_field_switcher('Order Shipping Method', $shipping_method, 'no_history');


        $shipping_to_delete = array();
        $shipping_to_ignore = array();


        $sql = sprintf(
            'select `Order No Product Transaction Fact Key`,`Order No Product Transaction Pinned`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Shipping" and `Type`="Order"    ', $this->id


        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Order No Product Transaction Pinned'] == 'Yes') {
                    $shipping_to_ignore[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

                } else {
                    $shipping_to_delete[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }





        if (!($net == 0 and $tax == 0)) {

            $sql = sprintf(
                'select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Shipping" and `Transaction Type Key`=%d and `Type`="Order" ', $this->id, $shipping_key
            );
            // is is only one posibvle shipping entry we will use this one


            $sql = sprintf(
                'select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Shipping" and `Type`="Order" ', $this->id
            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    if (!in_array($row['Order No Product Transaction Fact Key'], $shipping_to_ignore)) {


                        unset($shipping_to_delete[$row['Order No Product Transaction Fact Key']]);

                        $sql = sprintf(
                            'update `Order No Product Transaction Fact` set `Transaction Description`=%s ,`Transaction Gross Amount`=%.2f,`Transaction Total Discount Amount`=0,`Transaction Net Amount`=%.2f,`Tax Category Code`=%s,`Transaction Tax Amount`=%.2f ,
                            `Currency Exchange`=%f,`Metadata`=%s,`Delivery Note Key`=%s,`Transaction Type Key`=%d 

                          where `Order No Product Transaction Fact Key`=%d ',  prepare_mysql(_('Shipping')), $net,$net, prepare_mysql($this->data['Order Tax Code']), $tax,
                            $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key), $shipping_key,$row['Order No Product Transaction Fact Key']


                        );


                        $this->db->exec($sql);
                    }


                } else {
                    $sql = sprintf(
                        "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,
				`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
				`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`,`Order No Product Transaction Version`)  VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s,2)  ", $this->id, prepare_mysql($this->data['Order Date']), prepare_mysql('Shipping'),
                        $shipping_key, prepare_mysql(_('Shipping')), $net, $net, prepare_mysql($this->data['Order Tax Code']), $tax,


                        prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key)

                    );

                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }

        foreach ($shipping_to_delete as $onpt_key) {
            $sql = sprintf(
                'delete from `Order No Product Transaction Fact`  where `Order No Product Transaction Fact Key`=%d ', $onpt_key
            );
            $this->db->exec($sql);

            $sql = sprintf(
                'delete from `Order No Product Transaction Deal Bridge`  where `Order No Product Transaction Fact Key`=%d ', $onpt_key
            );
            $this->db->exec($sql);
        }




    }

    function get_shipping($shipping_zone_schema_key=false) {


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

            return array(
                ($this->data['Order Shipping Net Amount'] == '' ? 0 : $this->data['Order Shipping Net Amount']),
                0,
                'Set'
            );
        }


        if(!$shipping_zone_schema_key){
            $store = get_object('Store', $this->get('Order Store Key'));
            $shipping_zone_schema_key = $store->properties['current_shipping_zone_schema'];
        }


        $_data = array(
            'shipping_zone_schema_key'  => $shipping_zone_schema_key,
            'Order Data' => array(
                'Order Items Net Amount'                      => $this->data['Order Items Net Amount'],
                'Order Delivery Address Postal Code'          => $this->data['Order Delivery Address Postal Code'],
                'Order Delivery Address Country 2 Alpha Code' => $this->data['Order Delivery Address Country 2 Alpha Code'],
            )


        );


        //print_r($_data);

        include_once 'nano_services/shipping_for_order.ns.php';

        $shipping_data = (new shipping_for_order($this->db))->get($_data);


        return array(
            $shipping_data['price'],
            $shipping_data['shipping_zone_key'],
            $shipping_data['method'],
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
                "SELECT sum( `Order Transaction Amount`*(`Delivery Note Quantity`/`Order Quantity`)  ) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0", $this->id, $dn_key
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


        if($this->get('State Index') >= 90 or $this->get('State Index') <=0  ){
            return;
        }
        $this->update_field_switcher('Order Shipping Method', 'Calculated', 'no_history');

        $this->update_shipping();
        $this->updated = true;
        $this->update_totals();
        //$this->apply_payment_from_customer_account();
        $this->new_value = $this->data['Order Shipping Net Amount'];

    }


}



