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


        if ($dn_key and $order_picked) {
            $charges_array = $this->get_charges($dn_key);
        } else {
            $charges_array = $this->get_charges();
        }


        $charges_to_delete = array();
        $charges_to_ignore = array();

        $sql = sprintf(
            'select `Order No Product Transaction Fact Key`,`Order No Product Transaction Pinned`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" and `Type`="Order"    ', $this->id


        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Order No Product Transaction Pinned'] == 'Yes') {
                    $charges_to_ignore[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

                } else {
                    $charges_to_delete[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $total_charges_net = 0;
        $total_charges_tax = 0;


        foreach ($charges_array as $charge_data) {


            $net               = $charge_data['Charge Net Amount'];
            $tax               = $charge_data['Charge Net Amount'] * $this->data['Order Tax Rate'];
            $total_charges_net += $net;
            $total_charges_tax += $tax;

            if (!($net == 0 and $tax == 0)) {

                $sql = sprintf(
                    'select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" and `Transaction Type Key`=%d and `Type`="Order" ', $this->id, $charge_data['Charge Key']


                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {


                        if (!in_array($row['Order No Product Transaction Fact Key'], $charges_to_ignore)) {


                            unset($charges_to_delete[$row['Order No Product Transaction Fact Key']]);

                            $sql = sprintf(
                                'update `Order No Product Transaction Fact` set `Transaction Description`=%s ,`Transaction Gross Amount`=%.2f,`Transaction Net Amount`=%.2f,`Tax Category Code`=%s,`Transaction Tax Amount`=%.2f ,
                            `Currency Exchange`=%f,`Metadata`=%s,`Delivery Note Key`=%s

                          where `Order No Product Transaction Fact Key`=%d ', prepare_mysql($charge_data['Charge Description']), $charge_data['Charge Net Amount'], $charge_data['Charge Net Amount'], prepare_mysql($this->data['Order Tax Code']), $tax,
                                $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key), $row['Order No Product Transaction Fact Key']


                            );


                            $this->db->exec($sql);
                        }


                    } else {
                        $sql = sprintf(
                            "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)

					VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%f,%s,%s)  ", $this->id, prepare_mysql($this->data['Order Date']), prepare_mysql('Charges'), $charge_data['Charge Key'], prepare_mysql($charge_data['Charge Description']), $charge_data['Charge Net Amount'],
                            $charge_data['Charge Net Amount'], prepare_mysql($this->data['Order Tax Code']), $tax,

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

        }

        foreach ($charges_to_delete as $onpt_key) {
            $sql = sprintf(
                'delete from `Order No Product Transaction Fact`  where `Order No Product Transaction Fact Key`=%d ', $onpt_key
            );
            $this->db->exec($sql);

            $sql = sprintf(
                'delete from `Order No Product Transaction Deal Bridge`  where `Order No Product Transaction Fact Key`=%d ', $onpt_key
            );
            $this->db->exec($sql);
        }


        $net = 0;
        $tax = 0;


        $sql = sprintf(
            'select sum(`Transaction Net Amount`) as net ,  sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $net = $row['net'];
                $tax = $row['tax'];
            }
        }


        $this->fast_update(
            array(
                'Order Charges Net Amount' => $net,
                'Order Charges Tax Amount' => $tax
            )
        );


    }


    function get_charges_public_info() {

        $charges_public_info = '';
        $sql                 =
            sprintf('select `Charge Public Description`,`Charge Name` from `Order No Product Transaction Fact` ONPTF  left join `Charge Dimension` C on (C.`Charge Key`=`Transaction Type Key` ) where `Order Key`=%d and `Transaction Type`="Charges"', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $charges_public_info .= ', <h3>'.$row['Charge Name'].'</h3><p>'.$row['Charge Public Description']."</p>";
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $charges_public_info = preg_replace('/^, /', '', $charges_public_info);

        return $charges_public_info;

    }


    function get_charges($dn_key = false) {


        $charges = array();;
        if ($this->data['Order Number Items'] == 0) {

            return $charges;
        }


        $sql = sprintf(
            "SELECT * FROM `Charge Dimension` WHERE `Charge Trigger`='Order' AND (`Charge Trigger Key`=%d  OR `Charge Trigger Key` IS NULL) AND `Store Key`=%d  and `Charge Active`='Yes' ", $this->id, $this->data['Order Store Key']
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $apply_charge = false;

                $order_amount = $this->data[$row['Charge Terms Type']];


                if ($dn_key) {
                    switch ($row['Charge Terms Type']) {

                        case 'Order Items Net Amount':

                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0", $this->id, $dn_key
                            );


                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
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
                                "SELECT sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0", $this->id, $dn_key
                            );

                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
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


        $sql = sprintf(
            'delete `Order No Product Transaction Fact`  from `Order No Product Transaction Fact`      left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`) 

       
        
        where  `Charge Scope`="Hanging" and  `Transaction Type`="Charges" and `Order Key`=%d  ',


            $this->id
        );

        $this->db->exec($sql);


        $this->update_charges();
        $this->update_totals();

    }


    function update_hanging_charges_amount($value, $dn_key = false) {
        $value = sprintf("%.2f", $value);


        $sql = sprintf(
            'select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact`  left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`)   
                where  `Charge Scope`="Hanging" and  `Transaction Type`="Charges" and `Order Key`=%d  ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $tax = $value * $this->data['Order Tax Rate'];


                $sql = sprintf(
                    'update `Order No Product Transaction Fact`    set `Order No Product Transaction Pinned`="Yes" ,  `Transaction Gross Amount`=%.2f ,`Transaction Net Amount`=%.2f ,`Transaction Tax Amount`=%.2f where  `Order No Product Transaction Fact Key`=%d  ',
                    $value, $value, $tax, $row['Order No Product Transaction Fact Key']
                );


                $this->db->exec($sql);

            } else {


                $sql = sprintf(
                    'select `Charge Key` from `Charge Dimension`   where  `Charge Scope`="Hanging" and  `Charge Store Key`=%d  ', $this->get('Store Key')
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {


                        $charge = get_object('Charge', $row2['Charge Key']);
                        $tax    = $charge->get('Charge Metadata') * $this->data['Order Tax Rate'];

                        $sql = sprintf(
                            "INSERT INTO `Order No Product Transaction Fact` (`Order No Product Transaction Pinned`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,
                        `Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
                        `Currency Code`,`Currency Exchange`,`Metadata`)

					VALUES (%s,%d,%s,%s,%d,
					%s,%.2f,%.2f,%s,%.2f,
					
					%s,%f,%s)  ", prepare_mysql('Yes'), $this->id, prepare_mysql($this->data['Order Date']), prepare_mysql('Charges'), $charge->id,

                            prepare_mysql($charge->get('Charge Description')), $value, $value, prepare_mysql($this->data['Order Tax Code']), $tax,

                            prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata'])

                        );


                        $this->db->exec($sql);

                    }
                }


            }
        }


        $this->update_charges($dn_key);


        $this->update_totals();

    }


    function add_charge($charge) {


        $sql = sprintf(
            'select `Order No Product Transaction Fact Key`,`Transaction Net Amount`  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" and `Transaction Type Key`=%d and `Type`="Order" ', $this->id, $charge->id

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $transaction_data = array(
                    'onptf_key' => $row['Order No Product Transaction Fact Key'],
                    'amount'    => money($row['Transaction Net Amount'], $this->data['Order Currency'])

                );

            } else {
                $tax = $charge->get('Charge Metadata') * $this->data['Order Tax Rate'];


                $sql = sprintf(
                    "INSERT INTO `Order No Product Transaction Fact` (`Order No Product Transaction Pinned`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,
                        `Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
                        `Currency Code`,`Currency Exchange`,`Metadata`)

					VALUES (%s,%d,%s,%s,%d,
					%s,%.2f,%.2f,%s,%.2f,
					
					%s,%f,%s)  ", prepare_mysql('Yes'), $this->id, prepare_mysql($this->data['Order Date']), prepare_mysql('Charges'), $charge->id,

                    prepare_mysql($charge->get('Charge Description')), $charge->get('Charge Metadata'), $charge->get('Charge Metadata'), prepare_mysql($this->data['Order Tax Code']), $tax,

                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata'])

                );


                $this->db->exec($sql);


                $transaction_data = array(
                    'onptf_key' => $this->db->lastInsertId(),
                    'amount'    => money($charge->get('Charge Metadata'), $this->data['Order Currency'])

                );

            }
        }


        if ($charge->get('Charge Scope') == 'Premium') {
            $this->fast_update(
                array(
                    'Order Priority Level' => 'PaidPremium'
                )
            );
        }
        if ($charge->get('Charge Scope') == 'Insurance') {
            $this->fast_update(
                array(
                    'Order Care Level' => 'PaidPremium'
                )
            );
        }


        $net = 0;
        $tax = 0;


        $sql = sprintf(
            'select sum(`Transaction Net Amount`) as net ,  sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $net = $row['net'];
                $tax = $row['tax'];
            }
        }


        $this->fast_update(
            array(
                'Order Charges Net Amount' => $net,
                'Order Charges Tax Amount' => $tax
            )
        );


        $this->update_totals();


        return $transaction_data;

    }

    function remove_charge($charge) {


        $sql = sprintf(
            'delete from  `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" and `Transaction Type Key`=%d and `Type`="Order" ', $this->id, $charge->id

        );


        $this->db->exec($sql);


        if ($charge->get('Charge Scope') == 'Premium') {
            $this->fast_update(
                array(
                    'Order Priority Level' => 'Normal'
                )
            );
        }
        if ($charge->get('Charge Scope') == 'Insurance') {
            $this->fast_update(
                array(
                    'Order Care Level' => 'Normal'
                )
            );
        }

        $net = 0;
        $tax = 0;


        $sql = sprintf(
            'select sum(`Transaction Net Amount`) as net ,  sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact`  where `Order Key`=%d and `Transaction Type`="Charges" ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $net = $row['net'];
                $tax = $row['tax'];
            }
        }


        $this->fast_update(
            array(
                'Order Charges Net Amount' => $net,
                'Order Charges Tax Amount' => $tax
            )
        );


        $this->update_totals();

        $transaction_data = array(
            'amount' => ''
        );

        return $transaction_data;

    }

}



