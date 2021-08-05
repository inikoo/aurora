<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:56:02 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
use Aurora\Models\Utils\TaxCategory;

trait OrderShippingOperations {


    function update_shipping_amount($value, $dn_key = false) {

        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }

        $value = sprintf("%.2f", $value);


        $this->update_field_switcher('Order Shipping Method', 'Set', 'no_history');


        $this->data['Order Shipping Net Amount'] = $value;


        $this->update_shipping($dn_key);

        $this->updated   = true;
        $this->new_value = $value;

        $this->update_totals();

    }

    function update_shipping($dn_key = false) {

        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }


        if (!$dn_key) {
            $dn_key = '';
        }


        list($shipping, $shipping_key, $shipping_method) = $this->get_shipping();


        $tax_category = new TaxCategory($this->db);
        $tax_category->loadWithKey($this->data['Order Tax Category Key']);

        if (!is_numeric($shipping)) {

            $net = 0;
            $tax = 0;
        } else {




            $net = $shipping;
            $tax = $shipping * $tax_category->get('Tax Category Rate');
        }


        $this->update_field_switcher('Order Shipping Method', $shipping_method, 'no_history');


        $shipping_to_delete = array();
        $shipping_to_ignore = array();


        $sql = "select `Order No Product Transaction Fact Key`,`Order No Product Transaction Pinned`  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Shipping' and `Type`='Order'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Order No Product Transaction Pinned'] == 'Yes') {
                $shipping_to_ignore[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

            } else {
                $shipping_to_delete[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

            }
        }


        if (!($net == 0 and $tax == 0)) {

            $net = round($net, 2);
            $tax = round($tax, 2);

            $sql = "select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Shipping' and `Type`='Order'";


            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );
            if ($row = $stmt->fetch()) {
                if (!in_array($row['Order No Product Transaction Fact Key'], $shipping_to_ignore)) {


                    unset($shipping_to_delete[$row['Order No Product Transaction Fact Key']]);

                    $sql =
                        "update `Order No Product Transaction Fact` set  `Order No Product Transaction Tax Category Key`=?,  `Transaction Description`=? ,`Transaction Gross Amount`=?,`Transaction Total Discount Amount`=0,`Transaction Net Amount`=?,`Tax Category Code`=?,`Transaction Tax Amount`=? ,`Currency Exchange`=?,`Metadata`=?,`Delivery Note Key`=?,`Transaction Type Key`=? where `Order No Product Transaction Fact Key`=?";


                    $this->db->prepare($sql)->execute(
                        array(
                            $tax_category->id,
                            _('Shipping'),
                            $net,
                            $net,
                            $tax_category->get('Tax Category Code'),
                            $tax,
                            $this->data['Order Currency Exchange'],
                            $this->data['Order Original Metadata'],
                            ($dn_key == '' ? null : $dn_key),
                            $shipping_key,
                            $row['Order No Product Transaction Fact Key']
                        )
                    );


                }

            } else {
                $sql = "INSERT INTO `Order No Product Transaction Fact` (`Order No Product Transaction Tax Category Key`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,
				`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
				`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`,`Order No Product Transaction Version`)  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)  ";


                $this->db->prepare($sql)->execute(
                    array(
                        $tax_category->id,
                        $this->id,
                        $this->data['Order Date'],
                        'Shipping',
                        $shipping_key,
                        'Shipping',
                        $net,
                        $net,
                        $tax_category->get('Tax Category Code'),
                        $tax,
                        $this->data['Order Currency'],
                        $this->data['Order Currency Exchange'],
                        $this->data['Order Original Metadata'],
                        ($dn_key == '' ? null : $dn_key),
                        2
                    )
                );


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

    function get_shipping($shipping_zone_schema_key = false) {


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


        if (!$shipping_zone_schema_key) {
            $store                    = get_object('Store', $this->get('Order Store Key'));
            $shipping_zone_schema_key = $store->properties['current_shipping_zone_schema'];
        }


        $_data = array(
            'shipping_zone_schema_key' => $shipping_zone_schema_key,
            'Order Data'               => array(
                'Order Items Net Amount'                      => $this->data['Order Items Net Amount'],
                'Order Estimated Weight'                      => $this->data['Order Estimated Weight'],
                'Order Delivery Address Postal Code'          => $this->data['Order Delivery Address Postal Code'],
                'Order Delivery Address Country 2 Alpha Code' => $this->data['Order Delivery Address Country 2 Alpha Code'],
            )


        );


        include_once 'nano_services/shipping_for_order.ns.php';

        $shipping_data = (new shipping_for_order($this->db))->get($_data);


        if ($shipping_data['price'] === 'TBC') {
            $shipping_data['price']  = 0;
            $shipping_data['method'] = 'TBC';
        }

        return array(
            $shipping_data['price'],
            $shipping_data['shipping_zone_key'],
            $shipping_data['method'],
        );


    }


    function use_calculated_shipping() {


        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }
        $this->update_field_switcher('Order Shipping Method', 'Calculated', 'no_history');

        $this->update_shipping();
        $this->updated = true;
        $this->update_totals();
        $this->new_value = $this->data['Order Shipping Net Amount'];

    }


}



