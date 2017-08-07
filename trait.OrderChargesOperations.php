<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderChargesOperations {

   
    function update_charges($dn_key = false, $order_picked = true) {

        if (!$dn_key) {
            $dn_key = '';
            $sql    = sprintf(
                'DELETE FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Transaction Type`="Charges" AND `Delivery Note Key` IS NULL AND `Invoice Key` IS NULL', $this->id
            );
        } else {
            $sql = sprintf(
                'DELETE FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Transaction Type`="Charges" AND `Delivery Note Key`=%d AND `Invoice Key` IS NULL', $this->id, $dn_key
            );


        }

        $this->db->exec($sql);


        if ($dn_key and $order_picked) {
            $charges_array = $this->get_charges($dn_key);
        } else {
            $charges_array = $this->get_charges();
        }


        $total_charges_net = 0;
        $total_charges_tax = 0;
        foreach ($charges_array as $charge_data) {
            $total_charges_net += $charge_data['Charge Net Amount'];
            $total_charges_tax += $charge_data['Charge Tax Amount'];

            if (!($charge_data['Charge Net Amount'] == 0 and $charge_data['Charge Tax Amount'] == 0)) {
                $sql = sprintf(
                    "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)

					VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ", $this->id, prepare_mysql($this->data['Order Date']), prepare_mysql('Charges'), $charge_data['Charge Key'],
                    prepare_mysql($charge_data['Charge Description']), $charge_data['Charge Net Amount'], $charge_data['Charge Net Amount'], prepare_mysql($this->data['Order Tax Code']),
                    $charge_data['Charge Tax Amount'],

                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key)

                );

                $this->db->exec($sql);


            }

        }


        $this->data['Order Charges Net Amount'] = $total_charges_net;
        $this->data['Order Charges Tax Amount'] = $total_charges_tax;


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Charges Net Amount`=%s ,`Order Charges Tax Amount`=%.2f WHERE `Order Key`=%d", $this->data['Order Charges Net Amount'],
            $this->data['Order Charges Tax Amount'], $this->id
        );
        $this->db->exec($sql);

    }

    function get_charges($dn_key = false) {
        $charges = array();;
        if ($this->data['Order Number Items'] == 0) {

            return $charges;
        }


        $sql = sprintf(
            "SELECT * FROM `Charge Dimension` WHERE `Charge Trigger`='Order' AND (`Charge Trigger Key`=%d  OR `Charge Trigger Key` IS NULL) AND `Store Key`=%d", $this->id,
            $this->data['Order Store Key']
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $apply_charge = false;

                $order_amount = $this->data[$row['Charge Terms Type']];


                if ($dn_key) {
                    switch ($row['Charge Terms Type']) {

                        case 'Order Items Net Amount':

                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id, $dn_key
                            );


                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            break;


                        case 'Order Items Gross Amount':
                        default:
                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id, $dn_key
                            );

                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            break;
                    }
                }


                $terms_components = preg_split(
                    '/;/', $row['Charge Terms Metadata']
                );
                $operator         = $terms_components[0];
                $amount           = $terms_components[1];

                //print_r($order_amount);


                switch ($operator) {
                    case('<'):
                        if ($order_amount < $amount) {
                            $apply_charge = true;
                        }
                        break;
                    case('>'):
                        if ($order_amount > $amount) {
                            $apply_charge = true;
                        }
                        break;
                    case('<='):
                        if ($order_amount <= $amount) {
                            $apply_charge = true;
                        }
                        break;
                    case('>='):
                        if ($order_amount >= $amount) {
                            $apply_charge = true;
                        }
                        break;
                }


                if ($row['Charge Type'] == 'Amount') {
                    $charge_net_amount = $row['Charge Metadata'];
                    $charge_tax_amount = $row['Charge Metadata'] * $this->data['Order Tax Rate'];
                } else {

                    exit("still to do");
                }


                if ($apply_charge) {
                    $charges[] = array(
                        'Charge Net Amount'  => $charge_net_amount,
                        'Charge Tax Amount'  => $charge_tax_amount,
                        'Charge Key'         => $row['Charge Key'],
                        'Charge Description' => $row['Charge Name']
                    );
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $charges;

    }
    
      function use_calculated_items_charges() {

        $this->update_charges();
        $this->updated = true;
        $this->update_totals();
        $this->apply_payment_from_customer_account();
        $this->new_value = $this->data['Order Charges Net Amount'];

    }


}


?>
