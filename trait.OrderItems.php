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

        $account = get_object('Account', '');

        $gross = 0;

        $otf_key = 0;

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
                'why'     => 'Order State:'.$this->data['Order State']

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
            "SELECT `Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Key`=%d ", $this->id,
            $data['item_historic_key']
        );


        if ($dn_key) {
            $sql .= sprintf(' and `Delivery Note Key`=%d', $dn_key);
        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $otf_key = $row['Order Transaction Fact Key'];

                $old_quantity   = $row['Order Quantity'];
                $old_net_amount = $row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'];

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
                    $cost             = round($quantity * $product->get('Product Cost'), 4);

                    $sql = sprintf(
                        "update `Order Transaction Fact` set  `Estimated Weight`=%s,`Order Quantity`=%f,`Order Bonus Quantity`=%f,`Order Last Updated Date`=%s,`Order Transaction Gross Amount`=%.2f ,`Order Transaction Total Discount Amount`=%.2f,`Order Transaction Amount`=%.2f,`Current Dispatching State`=%s  ,`Cost Supplier`=%.4f where `Order Transaction Fact Key`=%d ",
                        $estimated_weight, $quantity, $bonus_quantity, prepare_mysql(gmdate('Y-m-d H:i:s')), $gross, 0, $gross, prepare_mysql($data['Current Dispatching State']), $cost, $row['Order Transaction Fact Key']

                    );


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

                $old_quantity = 0;

                $old_net_amount = 0;


                $total_quantity = $quantity + $bonus_quantity;


                //print 'Q'.$quantity.' B'.$bonus_quantity.'|';


                if ($total_quantity > 0) {

                    $product          = get_object('Product', $data['item_historic_key'], 'historic_key');
                    $gross            = round($quantity * $product->data['Product History Price'], 2);
                    $estimated_weight = $total_quantity * $product->data['Product Package Weight'];
                    $cost             = round($total_quantity * $product->get('Product Cost'), 4);


                    $sql = sprintf(
                        "INSERT INTO `Order Transaction Fact` ( `OTF Category Department Key`,`OTF Category Family Key`,  `Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Date`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Quantity`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Store Key`,`Units Per Case`,`Delivery Note Key`,`Cost Supplier`,`Order Transaction Metadata`)
VALUES (%s,%s,%f,%s,%f,%s,%s,%s,%s,%s,
	%d,%d,%s,%d,%d,
	%s,%s,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%f,%s,%.4f,'{}')   ", prepare_mysql($product->get('Product Department Category Key')), prepare_mysql($product->get('Product Department Category Key')), $bonus_quantity, prepare_mysql($order_type), $tax_rate, prepare_mysql($tax_code),
                        prepare_mysql($this->data['Order Currency']), $estimated_weight, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gmdate('Y-m-d H:i:s')), $product->historic_id, $product->data['Product ID'], prepare_mysql($product->data['Product Code']), 0, 0,
                        prepare_mysql($data['Current Dispatching State']), prepare_mysql($data['Current Payment State']), prepare_mysql($this->data['Order Customer Key']), prepare_mysql($this->data['Order Key']), $quantity, $gross, 0, $gross,
                        prepare_mysql($this->data['Order Store Key']), $product->data['Product Units Per Case'], prepare_mysql($dn_key), $cost
                    );


                    //  print $sql;

                    $this->db->exec($sql);

                    $otf_key = $this->db->lastInsertId();


                    if ($dn_key) {

                        $sql = sprintf(
                            "UPDATE  `Order Transaction Fact` SET `Estimated Weight`=%f,`Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s WHERE `Order Transaction Fact Key`=%d", $estimated_weight,
                            prepare_mysql($dn->get('Delivery Note ID')), $dn_key, prepare_mysql(
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

        if (in_array($this->data['Order State'], array('InBasket'))) {
            $this->update_field('Order Date', gmdate('Y-m-d H:i:s'), 'no_history');


        } else {
            $history_abstract = '';
            if ($delta_qty > 0) {
                $history_abstract = sprintf(
                    _('%1$s %2$s added'), $delta_qty, sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
                );
            } elseif ($delta_qty < 0) {

                if ($quantity == 0) {
                    $history_abstract = sprintf(
                        _('%s %s removed, none in the order anymore'), -$delta_qty, sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
                    );

                } else {

                    $history_abstract = sprintf(
                        _('%s %s removed'), -$delta_qty, sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
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


        if (!$this->skip_update_after_individual_transaction) {


            $old_used_deals = $this->get_used_deals();


            $this->update_totals();
            $this->update_discounts_items();
            $this->update_totals();
            $this->update_shipping($dn_key, false);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items();
            $this->update_deal_bridge();

            $new_used_deals = $this->get_used_deals();


            $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
            $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

            $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
            $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

            $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
            $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));


            if (count($campaigns_diff) > 0 or count($deal_diff) > 0 or count($deal_components_diff) > 0) {
                $account = get_object('Account', '');

                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'            => 'update_deals_usage',
                    'campaigns'       => $campaigns_diff,
                    'deals'           => $deal_diff,
                    'deal_components' => $deal_components_diff,


                ), $account->get('Account Code'), $this->db
                );
            }

            $this->update_totals();


            if ($dn_key) {


                $dn->update_inventory_transaction_fact($otf_key, $quantity, $bonus_quantity);

                $dn->update_totals();

            }


            //print "xx $gross $gross_discounts ";


            $net_amount = $gross;


            if ($this->get('Order State') == 'InBasket') {


                if ($this->get('Order Number Items') == 0) {
                    $operations = array(
                        'cancel_operations',
                    );
                } else {
                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'submit_operations',
                        'proforma_operations'
                    );
                }


            } elseif ($this->get('Order State') == 'InProcess') {

                if ($this->get('Order Number Items') == 0) {
                    $operations = array(
                        'cancel_operations',
                        'undo_submit_operations'
                    );
                } else {
                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'undo_submit_operations',
                        'proforma_operations'
                    );
                }


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


            //  print_r($operations);

            $hide         = array();
            $show         = array();
            $add_class    = array();
            $remove_class = array();


            if ($this->get('Order Charges Net Amount') == 0) {

                $add_class['order_charges_container'] = 'very_discreet';

                $hide[] = 'order_charges_info';
            } else {
                $remove_class['order_charges_container'] = 'very_discreet';

                $show[] = 'order_charges_info';
            }


            if ($this->get('Order Items Discount Amount') == 0) {


                $hide[] = 'order_items_discount_container';
            } else {

                $show[] = 'order_items_discount_container';
            }


            if ($this->get('Order Charges Discount Amount') == 0) {
                $hide[] = 'Charges_Discount_Amount_tr';
            } else {
                $show[] = 'Charges_Discount_Amount_tr';
            }


            if ($this->get('Order Deal Amount Off') == 0) {
                $hide[] = 'Deal_Amount_Off_tr';
            } else {
                $show[] = 'Deal_Amount_Off_tr';
            }

            $this->update_metadata = array(

                'class_html'   => array(
                    'Order_State'                   => $this->get('State'),
                    'Items_Net_Amount'              => $this->get('Items Net Amount'),
                    'Items_Discount_Amount'         => $this->get('Items Discount Amount'),
                    'Deal_Amount_Off'               => $this->get('Deal Amount Off'),
                    'Items_Gross_Amount'            => $this->get('Items Gross Amount'),
                    'Items_Discount_Percentage'     => $this->get('Items Discount Percentage'),
                    'Shipping_Net_Amount'           => $this->get('Shipping Net Amount'),
                    'Charges_Net_Amount'            => $this->get('Charges Net Amount'),
                    'Total_Net_Amount'              => $this->get('Total Net Amount'),
                    'Total_Tax_Amount'              => $this->get('Total Tax Amount'),
                    'Total_Amount'                  => $this->get('Total Amount'),
                    'Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                    'To_Pay_Amount'                 => $this->get('To Pay Amount'),
                    'Payments_Amount'               => $this->get('Payments Amount'),

                    'Profit_Amount'                  => $this->get('Profit Amount'),
                    'Order_Margin'                   => $this->get('Margin'),
                    'Order_Number_items'             => $this->get('Number Items'),
                    'Order_Number_Items_with_Deals'  => $this->get('Number Items with Deals'),
                    'Charges_Discount_Amount'        => $this->get('Charges Discount Amount'),
                    'Charges_Discount_Percentage'    => $this->get('Charges Discount Percentage'),
                    'Amount_Off_Discount_Percentage' => $this->get('Amount Off Percentage'),
                    'To_Pay_Amount_Absolute'        => $this->get('To Pay Amount Absolute'),
                    'Order_Estimated_Weight'        => $this->get('Estimated Weight'),


                ),
                'hide'         => $hide,
                'show'         => $show,
                'add_class'    => $add_class,
                'remove_class' => $remove_class,

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
                    $row['Order Transaction Fact Key'], percentage($gross_discounts, $gross)
                );
            }
            $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($gross_discounts == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                    $gross_discounts, $gross
                ).'</span> <span class="'.($gross_discounts == 0 ? 'hide' : '').'">'.money($gross_discounts, $this->data['Order Currency']).'</span></span>';


            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'      => 'order_items_changed',
                'order_key' => $this->id,
            ), $account->get('Account Code'), $this->db
            );


            return array(
                'updated'             => true,
                'otf_key'             => $otf_key,
                'to_charge'           => money($net_amount, $this->data['Order Currency']),
                'item_discounts'      => $discounts,
                'net_amount'          => $net_amount,
                'delta_net_amount'    => $net_amount - $old_net_amount,
                'qty'                 => $quantity,
                'delta_qty'           => $quantity - $old_quantity,
                'bonus qty'           => $bonus_quantity,
                'discount_percentage' => ($gross_discounts > 0 ? percentage($gross_discounts, $gross, $fixed = 1, $error_txt = 'NA', $percentage_sign = '') : '')
            );

        } else {

            $net_amount = $gross;
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
                    $row['Order Transaction Fact Key'], percentage($gross_discounts, $gross)
                );
            }
            $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($gross_discounts == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                    $gross_discounts, $gross
                ).'</span> <span class="'.($gross_discounts == 0 ? 'hide' : '').'">'.money($gross_discounts, $this->data['Order Currency']).'</span></span>';


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


    }

    function delete_transaction($otf_key) {
        $sql = sprintf(
            "DELETE FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d", $otf_key
        );


        $del = $this->db->prepare($sql);
        $del->execute();


        if ($del->rowCount()) {
            $this->deleted_otfs[] = $otf_key;
        }


        $sql = sprintf(
            "DELETE FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d", $otf_key
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key`=%d", $otf_key
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Order Transaction Out of Stock in Basket Bridge` WHERE `Order Transaction Fact Key`=%d", $otf_key
        );
        $this->db->exec($sql);


    }

    function get_items() {


        $sql = sprintf(
            'SELECT  `Category Code`,`Product Price`,    (select group_concat(`Deal Info`) from `Order Transaction Deal Bridge` B  where B.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key` ) as deal_info,  `Order State`,`Delivery Note Quantity`,`Current Dispatching State`,`Deal Info`,OTF.`Product ID`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` 
FROM `Order Transaction Fact` OTF 
LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`)
 LEFT JOIN `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  
  left join  `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`)
    left join       `Order Dimension` O ON (O.`Order Key`=OTF.`Order Key`) 
      left join       `Category Dimension` C ON (C.`Category Key`=PD.`Product Family Category Key`) 
 WHERE OTF.`Order Key`=%d  ORDER BY `Product Code File As` ', $this->id
        );

        $items = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $deal_info = '<div id="transaction_deal_info_'.$row['Order Transaction Fact Key'].'" class="deal_info">'.$row['deal_info'].'</div>';


                if ($row['Current Dispatching State'] == 'Out of Stock in Basket') {
                    $out_of_stock_info = '<div> <span class="error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Product out of stock, removed from basket').'</span></div>';
                    $edit_quantity     = sprintf(
                        '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'>
                        <i  class="fa minus fa-minus fa-fw like_button "  style="visibility:hidden;cursor:pointer" aria-hidden="true"></i>
                        <input readonly class=" width_50 " style="text-align: center" value="%s" ovalue="%s"> 
                        <i  class="fa plus  fa-plus fa-fw like_button "  style="visibility:hidden;ccursor:pointer" aria-hidden="true"></i></span>', $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0,
                        $row['Order Quantity'] + 0
                    );

                } else {
                    $edit_quantity     = sprintf(
                        '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'>
                        <i onClick="save_item_qty_change(this)" class="fa minus fa-minus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i>
                        <input class="order_qty width_50 " style="text-align: center" value="%s" ovalue="%s"> 
                        <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i></span>', $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0,
                        $row['Order Quantity'] + 0
                    );
                    $out_of_stock_info = '';
                }


                if ($row['Order State'] == 'Dispatched' or $row['Order State'] == 'Approved' or $row['Order State'] == 'PackedDone') {
                    $qty = number($row['Delivery Note Quantity']);

                } else {
                    $qty = number($row['Order Quantity']);

                }


                $items[$row['Order Transaction Fact Key']] = array(
                    'code'             => sprintf('<a href="/%s">%s</a>', strtolower($row['Product Code']), $row['Product Code']),
                    'code_description' => '<b class="item_code">'.$row['Product Code'].'</b> <span class="item_description">'.$row['Product History Units Per Case'].'x '.$row['Product History Name'].$deal_info.'</span>'.$out_of_stock_info,
                    'description'      => $row['Product History Units Per Case'].'x '.$row['Product History Name'].$deal_info.$out_of_stock_info,
                    'price_raw'        => $row['Product Price'],
                    'qty'              => $qty,
                    'qty_raw'          => $row['Order Quantity'] + 0,
                    'pid'              => $row['Product ID'],
                    'otf_key'          => $row['Order Transaction Fact Key'],
                    'edit_qty'         => $edit_quantity,
                    'amount'           => '<span id="transaction_item_net_'.$row['Order Transaction Fact Key'].'" class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>',
                    'state'            => $row['Current Dispatching State'],
                    'analytics_data'   => json_encode(
                        array(
                            'id'       => $row['Product Code'],
                            'name'     => ($row['Product History Units Per Case'] > 1 ? $row['Product History Units Per Case'].'x ' : '').$row['Product Name'],
                            'category' => $row['Category Code'],
                            'price'    => $row['Product Price'],
                            'quantity' => $row['Order Quantity']
                        )

                    )
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



