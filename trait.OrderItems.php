<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderItems {


    function update_item($data) {

        $gross = 0;

        $otf_key         = 0;
        $net_amount      = 0;
        $gross_discounts = 0;

        $tax_code = $this->data['Order Tax Code'];
        $tax_rate = $this->data['Order Tax Rate'];

        if (array_key_exists('tax_code', $data)) {
            $tax_code = $data['tax_code'];
        }
        if (array_key_exists('tax_rate', $data)) {
            $tax_rate = $data['tax_rate'];
        }

        if (isset($data['Order Type'])) {
            $order_type = $data['Order Type'];
        } else {
            $order_type = $this->data['Order Type'];
        }


        if (array_key_exists('qty', $data)) {
            $quantity     = $data['qty'];
            $quantity_set = true;

        } else {
            $quantity     = 0;
            $quantity_set = false;
        }


        if (array_key_exists('bonus qty', $data)) {
            $bonus_quantity = $data['bonus qty'];
            // $bonus_quantity_set = true;
        } else {
            $bonus_quantity = 0;
            // $bonus_quantity_set = false;

        }


        $delta_qty = $quantity;


        if (!in_array(
            $this->data['Order State'], array(
                                          'InProcess',
                                          'InBasket',
                                          'InWarehouse',
                                          'PackedDone',
                                      )
        )) {
            return array(
                'updated' => false,

            );
        }


        if (in_array(
            $this->data['Order State'], array(
                                          'InWarehouse',

                                          'PackedDone',
                                      )
        )) {


            $dn_keys = $this->get_deliveries('keys');
            $dn_key  = array_pop($dn_keys);
            $dn      = get_object('DeliveryNote', $dn_key);


        } else {
            $dn_key = 0;
        }


        $sql = sprintf(
            "SELECT `Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Key`=%d ",
            $this->id, $data['item_historic_key']
        );


        if ($dn_key) {
            $sql .= sprintf(' and `Delivery Note Key`=%d', $dn_key);
        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $otf_key = $row['Order Transaction Fact Key'];

                $old_quantity       = $row['Order Quantity'];
                $old_bonus_quantity = $row['Order Bonus Quantity'];
                $old_net_amount     = $row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'];

                $delta_qty -= $old_quantity;

                if (!$quantity_set) {
                    $quantity = $old_quantity;
                }

                //if (!$bonus_quantity_set) {
                // $bonus_quantity=$old_bonus_quantity;
                //}
                $total_quantity = $quantity + $bonus_quantity;

                //   print "\n**** $old_quantity $old_bonus_quantity   ;  ($quantity_set,$bonus_quantity_set) ; QTY    $quantity ==     $total_quantity\n";

                $product = get_object('Product', $data['item_historic_key'], 'historic_key');

                if ($total_quantity == 0) {

                    $this->delete_transaction(
                        $row['Order Transaction Fact Key']
                    );
                    $otf_key = 0;
                    $gross   = 0;


                } else {


                    $estimated_weight = $total_quantity * $product->data['Product Package Weight'];
                    $gross            = round($quantity * $product->data['Product History Price'], 2);


                    $sql = sprintf(
                        "update`Order Transaction Fact` set  `Estimated Weight`=%s,`Order Quantity`=%f,`Order Bonus Quantity`=%f,`Order Last Updated Date`=%s,`Order Transaction Gross Amount`=%.2f ,`Order Transaction Total Discount Amount`=%.2f,`Order Transaction Amount`=%.2f,`Current Dispatching State`=%s  where `Order Transaction Fact Key`=%d ",
                        $estimated_weight, $quantity, $bonus_quantity, prepare_mysql(gmdate('Y-m-d H:i:s')), $gross, 0, $gross, prepare_mysql($data['Current Dispatching State']),
                        $row['Order Transaction Fact Key']

                    );


                    //  print $sql;

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $this->update_field(
                                'Order Last Updated Date', gmdate('Y-m-d H:i:s'), 'no_history'
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    if ($dn_key) {

                        $sql = sprintf(
                            "UPDATE  `Order Transaction Fact` SET `Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s WHERE `Order Transaction Fact Key`=%d",

                            prepare_mysql($dn->data['Delivery Note ID']), $dn_key, prepare_mysql(
                                $dn->get('Delivery Note Address Country 2 Alpha Code')
                            ), $row['Order Transaction Fact Key']

                        );
                        $this->db->exec($sql);
                    }


                    //   print "$sql  $otf_key  \n";
                    //    exit;
                }
            } else {
                //-----here

                $old_quantity       = 0;
                $old_bonus_quantity = 0;
                $old_net_amount     = 0;


                $total_quantity = $quantity + $bonus_quantity;

                if ($total_quantity > 0) {


                    $product          = get_object('Product', $data['item_historic_key'], 'historic_key');
                    $gross            = round($quantity * $product->data['Product History Price'], 2);
                    $estimated_weight = $total_quantity * $product->data['Product Package Weight'];

                    $sql = sprintf(
                        "INSERT INTO `Order Transaction Fact` (`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Invoice Currency Code`,`Estimated Weight`,`Order Date`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Quantity`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`,`Delivery Note Key`)
VALUES (%f,%s,%f,%s,%s,%s,%s,%s,%s,
	%d,%d,%s,%d,%d,
	%s,%s,%s,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%s,%f,'',%s)   ",

                        $bonus_quantity, prepare_mysql($order_type), $tax_rate, prepare_mysql($tax_code), prepare_mysql($this->data['Order Currency']), prepare_mysql($this->data['Order Currency']),
                        $estimated_weight, prepare_mysql(gmdate('Y-m-d H:i:s')),

                        prepare_mysql(gmdate('Y-m-d H:i:s')), $product->historic_id, $product->data['Product ID'], prepare_mysql($product->data['Product Code']), $product->data['Product Family Key'],
                        $product->data['Product Main Department Key'], prepare_mysql($data['Current Dispatching State']), prepare_mysql($data['Current Payment State']),
                        prepare_mysql($this->data['Order Customer Key']), prepare_mysql($this->data['Order Key']), prepare_mysql($this->data['Order Public ID']), $quantity, $gross, 0, $gross,
                        prepare_mysql($data['Metadata'], false), prepare_mysql($this->data['Order Store Key']), $product->data['Product Units Per Case'], prepare_mysql($dn_key)
                    );


                    $this->db->exec($sql);

                    $otf_key = $this->db->lastInsertId();


                    if ($dn_key) {

                        $sql = sprintf(
                            "UPDATE  `Order Transaction Fact` SET `Estimated Weight`=%f,`Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s WHERE `Order Transaction Fact Key`=%d",
                            $estimated_weight, prepare_mysql($dn->get('Delivery Note ID')), $dn_key, prepare_mysql(
                                $dn->get('Delivery Note Address Country 2 Alpha Code')
                            ), $otf_key

                        );
                        $this->db->exec($sql);
                    }
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_field('Order Last Updated Date', gmdate('Y-m-d H:i:s'), 'no_history');

        if (in_array(
            $this->data['Order State'], array(
                                          'InBasket'
                                      )
        )) {
            $this->update_field(
                'Order Date', gmdate('Y-m-d H:i:s'), 'no_history'
            );


        } else {
            $history_abstract = '';
            if ($delta_qty > 0) {
                $history_abstract = sprintf(
                    _('%1$s %2$s added'), $delta_qty, sprintf(
                                            '<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']
                                        )
                );
            } elseif ($delta_qty < 0) {

                if ($quantity == 0) {
                    $history_abstract = sprintf(
                        _('%s %s removed, none in the order anymore'), -$delta_qty, sprintf(
                                                                         '<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']
                                                                     )
                    );

                } else {

                    $history_abstract = sprintf(
                        _('%s %s removed'), -$delta_qty, sprintf(
                                              '<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']
                                          )
                    );
                }
            }

            if ($history_abstract != '') {

                $history_data = array(
                    'History Abstract' => $history_abstract,
                    'History Details'  => ''
                );
                $this->add_subject_history($history_data);
            }
        }


        if (array_key_exists('Supplier Metadata', $data)) {

            $sql = sprintf(
                "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ", prepare_mysql($data['Supplier Metadata']), $otf_key

            );
            $this->db->exec($sql);
        }


        if (!$this->skip_update_after_individual_transaction) {


            //$this->update_number_products();
            //$this->update_insurance();

            //$this->update_discounts_items();
            // $this->update_totals();


            //$this->update_shipping($dn_key, false);
            // $this->update_charges($dn_key, false);
            // $this->update_discounts_no_items($dn_key);


            // $this->update_deal_bridge();

            //$this->update_deals_usage();now forked

            $this->update_totals();

            $this->update_discounts_items();


            $this->update_shipping($dn_key, false);
            $this->update_charges($dn_key, false);

            $this->update_deal_bridge();


            $this->update_totals();

            // $this->update_number_products();

            //  $this->apply_payment_from_customer_account();

        }


        if ($dn_key) {


            $dn->update_inventory_transaction_fact($otf_key, $quantity, $bonus_quantity);

            $dn->update_totals();

        }


        //print "xx $gross $gross_discounts ";


        $net_amount = $gross;


        if ($this->get('Order State') == 'InBasket') {
            $operations = array(
                'send_to_warehouse_operations',
                'cancel_operations',
                'submit_operations'
            );

        } elseif ($this->get('Order State') == 'InProcess') {
            $operations = array(
                'send_to_warehouse_operations',
                'cancel_operations',
                'undo_submit_operations'
            );
        } elseif ($this->get('Order State') == 'InWarehouse') {
            $operations = array('cancel_operations');
        } elseif ($this->get('Order State') == 'PackedDone') {
            $operations = array(
                'invoice_operations',
                'cancel_operations'
            );
        } else {
            $operations = array();
        }


        $this->update_metadata = array(

            'class_html'  => array(
                'Order_State'      => $this->get('State'),
                'Items_Net_Amount' => $this->get('Items Net Amount'),

                'Items_Discount_Amount'         => $this->get('Items Discount Amount'),
                'Items_Discount_Percentage'     => $this->get('Items Discount Percentage'),
                'Shipping_Net_Amount'           => $this->get('Shipping Net Amount'),
                'Charges_Net_Amount'            => $this->get('Charges Net Amount'),
                'Total_Net_Amount'              => $this->get('Total Net Amount'),
                'Total_Tax_Amount'              => $this->get('Total Tax Amount'),
                'Total_Amount'                  => $this->get('Total Amount'),
                'Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                'To_Pay_Amount'                 => $this->get('To Pay Amount'),
                'Payments_Amount'               => $this->get('Payments Amount'),


                'Order_Number_items'            => $this->get('Number Items'),
                'Order_Number_Items_with_Deals' => $this->get('Number Items with Deals')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),
            'to_pay'      => $this->get('Order To Pay Amount'),
            'total'       => $this->get('Order Total Amount'),
            'payments'    => $this->get('Order Payments Amount'),
            'items'       => $this->get('Order Number Items'),
            'shipping'    => $this->get('Order Shipping Net Amount'),
            'charges'     => $this->get('Order Charges Net Amount'),

        );


        if (in_array(
            $this->get('Order State'), array(
            'Cancelled',
            'Approved',
            'Dispatched',
        )
        )) {
            $discounts_class = '';
            $discounts_input = '';
        } else {
            $discounts_class = 'button';
            $discounts_input = sprintf(
                '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d"  }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                $row['Order Transaction Fact Key'], percentage($gross_discounts, $row['Order Transaction Gross Amount'])
            );
        }
        $discounts =
            $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($gross_discounts == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                $gross_discounts, $row['Order Transaction Gross Amount']
            ).'</span> <span class="'.($gross_discounts == 0 ? 'hide' : '').'">'.money($gross_discounts, $row['Order Currency Code']).'</span></span>';


        return array(
            'updated'        => true,
            'otf_key'        => $otf_key,
            'to_charge'      => money($net_amount, $this->data['Order Currency']),
            'item_discounts' => $discounts,

            'net_amount'          => $net_amount,
            'delta_net_amount'    => $net_amount - $old_net_amount,
            'qty'                 => $quantity,
            'delta_qty'           => $quantity - $old_quantity,
            'bonus qty'           => $bonus_quantity,
            'discount_percentage' => ($gross_discounts > 0 ? percentage($gross_discounts, $gross, $fixed = 1, $error_txt = 'NA', $percentage_sign = '') : '')
        );


    }

    function delete_transaction($otf_key) {
        $sql = sprintf(
            "DELETE FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d", $otf_key
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d", $otf_key
        );
        $this->db->exec($sql);

    }

    function get_items() {

        $sql = sprintf(
            'SELECT OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`) LEFT JOIN `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  WHERE `Order Key`=%d  ORDER BY `Product Code File As` ',
            $this->id
        );

        $items = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $edit_quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw like_button button"  style="cursor:pointer" aria-hidden="true"></i></span>',
                    $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0, $row['Order Quantity'] + 0
                );


                $items[] = array(
                    'code'        => $row['Product Code'],
                    'description' => $row['Product History Units Per Case'].'x '.$row['Product History Name'],
                    'qty'         => number($row['Order Quantity']),
                    'edit_qty'    => $edit_quantity,
                    'amount'      => '<span class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>'

                );


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $items;

    }

}


?>
