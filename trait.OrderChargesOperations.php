<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
use Aurora\Models\Utils\TaxCategory;

trait OrderChargesOperations
{

    var Order $this;

    function get_charges_public_info()
    {
        $charges_public_info = '';

        $sql  = "select `Charge Public Description`,`Charge Name` from `Order No Product Transaction Fact` ONPTF  left join `Charge Dimension` C on (C.`Charge Key`=`Transaction Type Key` ) where `Order Key`=? and `Transaction Type`='Charges'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $charges_public_info .= ', <h3>'.$row['Charge Name'].'</h3><p>'.$row['Charge Public Description']."</p>";
        }


        return preg_replace('/^, /', '', $charges_public_info);
    }

    /**
     * @throws Exception
     */
    function use_calculated_items_charges()
    {
        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }

        $sql = "delete `Order No Product Transaction Fact`  from `Order No Product Transaction Fact` left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`)  where `Transaction Type`='Charges'    and `Order Key`=?  and (`Charge Scope`='Hanging' or `Transaction Type Key` is null)  ";

        $this->db->prepare($sql)->execute(array(
                                              $this->id
                                          ));


        $this->update_charges();
        $this->update_totals();
    }

    /**
     * @throws Exception
     */
    function update_charges($dn_key = false, $order_picked = true)
    {
        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }

        if ($dn_key and $order_picked) {
            $charges_array = $this->get_charges($dn_key);
        } else {
            $charges_array = $this->get_charges();
        }


        $charges_to_delete = [];
        $charges_to_ignore = [];

        $sql = "select `Order No Product Transaction Fact Key`,`Order No Product Transaction Pinned`  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Charges' and `Type`='Order'";
        /** @noinspection DuplicatedCode */
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            if ($row['Order No Product Transaction Pinned'] == 'Yes') {
                $charges_to_ignore[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];
            } else {
                $charges_to_delete[$row['Order No Product Transaction Fact Key']] = $row['Order No Product Transaction Fact Key'];
            }
        }


        $total_charges_net = 0;
        $total_charges_tax = 0;

        $tax_category = new TaxCategory($this->db);
        $tax_category->loadWithKey($this->data['Order Tax Category Key']);

        foreach ($charges_array as $charge_data) {
            $net               = $charge_data['Charge Net Amount'];
            $tax               = $charge_data['Charge Net Amount'] * $tax_category->get('Tax Category Rate');
            $total_charges_net += $net;
            $total_charges_tax += $tax;

            if (!($net == 0 and $tax == 0)) {
                $sql = "select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Charges' and `Transaction Type Key`=? and `Type`='Order'";


                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                                   $this->id,
                                   $charge_data['Charge Key']
                               ]);
                if ($row = $stmt->fetch()) {
                    if (!in_array($row['Order No Product Transaction Fact Key'], $charges_to_ignore)) {
                        unset($charges_to_delete[$row['Order No Product Transaction Fact Key']]);

                        $sql = "update `Order No Product Transaction Fact` set `Transaction Description`=? ,`Transaction Gross Amount`=?,`Transaction Net Amount`=?,`Order No Product Transaction Tax Category Key`=?,`Tax Category Code`=?,`Transaction Tax Amount`=? ,
                            `Currency Exchange`=?,`Metadata`=?,`Delivery Note Key`=?

                          where `Order No Product Transaction Fact Key`=? ";

                        $this->db->prepare($sql)->execute([
                                                              $charge_data['Charge Description'],
                                                              round($charge_data['Charge Net Amount'], 2),
                                                              round($charge_data['Charge Net Amount'], 2),
                                                              $tax_category->id,
                                                              $this->data['Order Tax Code'],
                                                              round($tax, 2),
                                                              $this->data['Order Currency Exchange'],
                                                              $this->data['Order Original Metadata'],
                                                              (!$dn_key ? null : $dn_key),
                                                              $row['Order No Product Transaction Fact Key']
                                                          ]);
                    }
                } else {
                    $sql = "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)  ";


                    $this->db->prepare($sql)->execute([
                                                          $this->id,
                                                          $this->data['Order Date'],
                                                          'Charges',
                                                          $charge_data['Charge Key'],
                                                          $charge_data['Charge Description'],
                                                          round($charge_data['Charge Net Amount'], 2),
                                                          round($charge_data['Charge Net Amount'], 2),
                                                          $tax_category->id,
                                                          $this->data['Order Tax Code'],
                                                          round($tax, 2),

                                                          $this->data['Order Currency'],
                                                          $this->data['Order Currency Exchange'],
                                                          $this->data['Order Original Metadata'],
                                                          (!$dn_key ? null : $dn_key)
                                                      ]);
                }
            }
        }

        foreach ($charges_to_delete as $order_no_product_transaction_key) {
            $sql = "delete from `Order No Product Transaction Fact`  where `Order No Product Transaction Fact Key`=?";
            $this->db->prepare($sql)->execute([
                                                  $order_no_product_transaction_key
                                              ]);


            $sql = "delete from `Order No Product Transaction Deal Bridge`  where `Order No Product Transaction Fact Key`=?";
            $this->db->prepare($sql)->execute([
                                                  $order_no_product_transaction_key
                                              ]);
        }


        $this->update_order_charge_total_amounts();
    }

    function get_charges($dn_key = false)
    {
        $charges = [];
        if ($this->data['Order Number Items'] == 0) {
            return $charges;
        }

        $tax_category = new TaxCategory($this->db);
        $tax_category->loadWithKey($this->data['Order Tax Category Key']);

        $sql = sprintf(
            "SELECT * FROM `Charge Dimension` WHERE `Charge Trigger`='Order' AND (`Charge Trigger Key`=%d  OR `Charge Trigger Key` IS NULL) AND `Store Key`=%d  and `Charge Active`='Yes' ",
            $this->id,
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
                                "SELECT sum(`Order Transaction Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id,
                                $dn_key
                            );


                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            }

                            break;


                        case 'Order Items Gross Amount':
                        default:
                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id,
                                $dn_key
                            );

                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            }


                            break;
                    }
                }


                $terms_components = preg_split('/;/', $row['Charge Terms Metadata']);
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
                    $charge_tax_amount = $row['Charge Metadata'] * $tax_category->get('Tax Category Rate');
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
        }


        return $charges;
    }


    /**
     * @throws Exception
     */
    function update_hanging_charges_amount($value, $dn_key = false)
    {
        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }

        $tax_category = new TaxCategory($this->db);
        $tax_category->loadWithKey($this->data['Order Tax Category Key']);

        $value = round($value, 2);


        $sql  = "select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact`  left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`)  where  `Charge Scope`='Hanging' and  `Transaction Type`='Charges' and `Order Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        if ($row = $stmt->fetch()) {
            $tax = round($value * $tax_category->get('Tax Category Rate'), 2);


            $sql = "update `Order No Product Transaction Fact`    set `Order No Product Transaction Pinned`='Yes' ,  `Transaction Gross Amount`=? ,`Transaction Net Amount`=? ,`Transaction Tax Amount`=? where  `Order No Product Transaction Fact Key`=? ";


            $this->db->prepare($sql)->execute(array(
                                                  $value,
                                                  $value,
                                                  $tax,
                                                  $row['Order No Product Transaction Fact Key']
                                              ));


            $this->db->exec($sql);
        } else {
            $sql = "select `Charge Key` from `Charge Dimension`   where  `Charge Scope`='Hanging' and  `Charge Store Key`=?";

            $stmt2 = $this->db->prepare($sql);
            $stmt2->execute(array(
                                $this->get('Store Key')
                            ));
            if ($row2 = $stmt2->fetch()) {
                $charge             = get_object('Charge', $row2['Charge Key']);
                $charge_id          = $charge->id;
                $charge_description = $charge->get('Charge Description');
            } else {
                $charge_id          = null;
                $charge_description = '';
            }

            $tax = round($value * $tax_category->get('Tax Category Rate'), 2);

            $sql = "INSERT INTO `Order No Product Transaction Fact` (`Order No Product Transaction Pinned`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,
                        `Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,`Transaction Tax Amount`,
                        `Currency Code`,`Currency Exchange`,`Metadata`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)  ";

            $this->db->prepare($sql)->execute(array(
                                                  'Yes',
                                                  $this->id,
                                                  $this->data['Order Date'],
                                                  'Charges',
                                                  $charge_id,

                                                  $charge_description,
                                                  $value,
                                                  $value,
                                                  $tax_category->id,
                                                  $this->data['Order Tax Code'],
                                                  $tax,

                                                  $this->data['Order Currency'],
                                                  $this->data['Order Currency Exchange'],
                                                  $this->data['Order Original Metadata']
                                              ));
        }


        $this->update_charges($dn_key);


        $this->update_totals();
    }


    /**
     * @throws Exception
     */
    function add_charge($charge,$amount=false)
    {


        $_charge_amount=$charge->get('Charge Metadata');
        if($amount){
            $_charge_amount=$amount;
        }

        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return false;
        }




        $tax_category = new TaxCategory($this->db);
        $tax_category->loadWithKey($this->data['Order Tax Category Key']);


        $sql = "select `Order No Product Transaction Fact Key`,`Transaction Net Amount`  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Charges' and `Transaction Type Key`=? and `Type`='Order'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
                           $this->id,
                           $charge->id
                       ]);
        if ($row = $stmt->fetch()) {
            $transaction_data = array(
                'onptf_key' => $row['Order No Product Transaction Fact Key'],
                'amount'    => money($row['Transaction Net Amount'], $this->data['Order Currency'])

            );
        } else {
            $tax = $_charge_amount * $tax_category->get('Tax Category Rate');


            $sql = "INSERT INTO `Order No Product Transaction Fact` (`Order No Product Transaction Pinned`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,
                    `Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,`Transaction Tax Amount`,
                    `Currency Code`,`Currency Exchange`,`Metadata`)

                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)  ";


            $this->db->prepare($sql)->execute([
                                                  'Yes',
                                                  $this->id,
                                                  $this->data['Order Date'],
                                                  'Charges',
                                                  $charge->id,

                                                  $charge->get('Charge Description'),
                                                  round($_charge_amount, 2),
                                                  round($_charge_amount, 2),
                                                  $tax_category->id,
                                                  $this->data['Order Tax Code'],
                                                  round($tax, 2),

                                                  $this->data['Order Currency'],
                                                  $this->data['Order Currency Exchange'],
                                                  $this->data['Order Original Metadata']
                                              ]);


            $transaction_data = array(
                'onptf_key' => $this->db->lastInsertId(),
                'amount'    => money($_charge_amount, $this->data['Order Currency'])

            );
        }


        if ($charge->get('Charge Scope') == 'Premium') {
            $this->fast_update(array(
                                   'Order Priority Level' => 'PaidPremium'
                               ));
        }
        if ($charge->get('Charge Scope') == 'Insurance') {
            $this->fast_update(array(
                                   'Order Care Level' => 'PaidPremium'
                               ));
        }


        if ($charge->get('Charge Scope') == 'Tracking') {
            $this->fast_update(array(
                                   'Order Shipping Level' => 'Tracking'
                               ));
        }


        $this->update_order_charge_total_amounts();


        return $transaction_data;
    }

    /**
     * @throws Exception
     */
    function remove_charge($charge)
    {
        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return false;
        }

        $sql = "delete from  `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Charges' and `Transaction Type Key`=? and `Type`='Order'";


       // print_r($sql);

        $this->db->prepare($sql)->execute([
                                              $this->id,
                                              $charge->id
                                          ]);


        if ($charge->get('Charge Scope') == 'Premium') {
            $this->fast_update(array(
                                   'Order Priority Level' => 'Normal'
                               ));
        }
        if ($charge->get('Charge Scope') == 'Insurance') {
            $this->fast_update(array(
                                   'Order Care Level' => 'Normal'
                               ));
        }
        if ($charge->get('Charge Scope') == 'Tracking') {
            $this->fast_update(array(
                                   'Order Shipping Level' => 'Normal'
                               ));
        }

        $this->update_order_charge_total_amounts();

        return array(
            'amount' => ''
        );
    }

    /**
     * @throws Exception
     */
    private function update_order_charge_total_amounts(): void
    {
        $net = 0;
        $tax = 0;


        $sql = "select sum(`Transaction Net Amount`) as net ,  sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact`  where `Order Key`=? and `Transaction Type`='Charges'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
                           $this->id
                       ]);
        if ($row = $stmt->fetch()) {
            $net = $row['net'];
            $tax = $row['tax'];
        }


        $this->fast_update(array(
                               'Order Charges Net Amount' => $net,
                               'Order Charges Tax Amount' => $tax
                           ));


        $this->update_totals();
    }

}



