<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

use Aurora\Models\Utils\TaxCategory;

trait OrderItems
{

    /**
     * @throws Exception
     */
    function update_item($data): array
    {
        $product = get_object('Product', $data['item_historic_key'], 'historic_key');
        if(!$product->id){
            return array(
                'updated' => false,
                'why'     => 'Historic product not found:'.$data['item_historic_key']

            );
        }

        $gross = 0;

        $otf_key = 0;

        $gross_discounts = 0;


        $order_type = $data['Order Type'] ?? $this->data['Order Type'];


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


        if (!in_array($this->data['Order State'], array(
            'InProcess',
            'InBasket',
            'InWarehouse',
            'PackedDone',
        ))) {
            return array(
                'updated' => false,
                'why'     => 'Order State:'.$this->data['Order State']

            );
        }


        if (in_array($this->data['Order State'], array(
            'InWarehouse',
            'PackedDone',
        ))) {
            //todo this is too bad!!!!! you need to choose the proper DN

            $_deliveries = $this->get_deliveries();
            $dn_key      = array_pop($_deliveries);
            /** @var DeliveryNote $dn */
            $dn = get_object('DeliveryNote', $dn_key);
        } else {
            $dn_key = 0;
            $dn     = false;
        }


        $sql = "SELECT `Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF WHERE `Order Key`=? AND `Product Key`=? ";


        $params = [
            $this->id,
            $data['item_historic_key']
        ];

        if ($dn_key) {
            $sql      .= ' and `Delivery Note Key`=?';
            $params[] = $dn_key;
        }


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            $params
        );
        if ($row = $stmt->fetch()) {
            $otf_key = $row['Order Transaction Fact Key'];

            $old_quantity   = $row['Order Quantity'];
            $old_net_amount = $row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'];

            $delta_qty -= $old_quantity;

            if (!$quantity_set) {
                $quantity = $old_quantity;
            }


            $total_quantity = $quantity + $bonus_quantity;


            if ($total_quantity == 0) {
                $this->delete_transaction($otf_key);
                $otf_key = 0;
                $gross   = 0;
            } else {
                $gross = round($quantity * $product->data['Product History Price'], 2);

                $product_cost = (is_numeric($product->get('Product Cost')) ? $product->get('Product Cost') : 0);
                $cost         = round($total_quantity * $product_cost, 4);


                $sql = "update `Order Transaction Fact` set 
                                    `Order Quantity`=?,`Order Bonus Quantity`=?,`Order Last Updated Date`=?,`Order Transaction Gross Amount`=?,`Order Transaction Total Discount Amount`=?,`Order Transaction Amount`=?,`Current Dispatching State`=? ,`Cost Supplier`=?
                                    where `Order Transaction Fact Key`=? ";


                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                                   $quantity,
                                   $bonus_quantity,
                                   gmdate('Y-m-d H:i:s'),
                                   $gross,
                                   0,
                                   $gross,
                                   $data['Current Dispatching State'],
                                   $cost,
                                   $otf_key
                               ]);
                if ($stmt->rowCount()) {
                    $this->fast_update(array('Order Last Updated Date' => gmdate('Y-m-d H:i:s')));
                }


                if ($dn_key) {
                    $sql = "UPDATE `Order Transaction Fact` SET `Delivery Note Key`=? WHERE `Order Transaction Fact Key`=?";
                    $this->db->prepare($sql)->execute([
                                                          $dn_key,
                                                          $otf_key
                                                      ]);
                }
            }
        } else {
            $old_quantity   = 0;
            $old_net_amount = 0;
            $total_quantity = $quantity + $bonus_quantity;


            if ($total_quantity > 0) {
                $tax_category = new TaxCategory($this->db);
                $tax_category->loadWithKey($this->data['Order Tax Category Key']);



                $gross = round($quantity * $product->data['Product History Price'], 2);

                $product_cost = (is_numeric($product->get('Product Cost')) ? $product->get('Product Cost') : 0);
                $cost         = round($total_quantity * $product_cost, 4);


                $sql = "INSERT INTO `Order Transaction Fact` ( 
                                      `OTF Category Department Key`,`OTF Category Family Key`,  `Order Bonus Quantity`,`Order Transaction Type`,
                                      `Order Transaction Tax Category Key`,
                                      `Transaction Tax Rate`,
                                      `Transaction Tax Code`,`Order Currency Code`,
                                      `Order Date`,`Order Last Updated Date`,
			                            `Product Key`,`Product ID`,`Current Dispatching State`,`Current Payment State`,`Customer Key`,
                                      `Order Key`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,
                                      `Store Key`,`Delivery Note Key`,`Cost Supplier`,`Order Transaction Metadata`,`Order Transaction Product Type`)
VALUES (?,?,?,?,?,? ,?,?, ?,?, ?,?,?,?,? ,?,?,?,?,? ,?,?,?,?,?)   ";


                $this->db->prepare($sql)->execute(array(
                                                      $product->get('Product Department Category Key'),
                                                      $product->get('Product Family Category Key'),
                                                      $bonus_quantity,
                                                      $order_type,
                                                      $tax_category->id,
                                                      $tax_category->get('Tax Category Rate'),
                                                      $tax_category->get('Tax Category Code'),

                                                      $this->data['Order Currency'],

                                                      gmdate('Y-m-d H:i:s'),
                                                      gmdate('Y-m-d H:i:s'),

                                                      $product->historic_id,
                                                      $product->data['Product ID'],
                                                      $data['Current Dispatching State'],
                                                      $data['Current Payment State'],
                                                      $this->data['Order Customer Key'],

                                                      $this->data['Order Key'],
                                                      $quantity,
                                                      $gross,
                                                      0,
                                                      $gross,

                                                      $this->data['Order Store Key'],
                                                      $dn_key,
                                                      $cost,
                                                      '{}',
                                                      $product->get('Product Type')
                                                  ));


                $otf_key = $this->db->lastInsertId();
                if (!$otf_key) {
                    throw new Exception('Error inserting OTF');
                }

                $this->new_otfs[] = $otf_key;

                if ($dn_key) {
                    $sql = "UPDATE  `Order Transaction Fact` SET `Delivery Note Key`=?  WHERE `Order Transaction Fact Key`=?";
                    $this->db->prepare($sql)->execute(array(
                                                          $dn_key,
                                                          $otf_key
                                                      ));
                }
            }
        }


        $this->update_field('Order Last Updated Date', gmdate('Y-m-d H:i:s'), 'no_history');

        if ($this->data['Order State'] == 'InBasket') {
            $this->update_field('Order Date', gmdate('Y-m-d H:i:s'), 'no_history');
        } else {
            $history_abstract = '';
            if ($delta_qty > 0) {
                $history_abstract = sprintf(
                    _('%1$s %2$s added'),
                    $delta_qty,
                    sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
                );
            } elseif ($delta_qty < 0) {
                if ($quantity == 0) {
                    $history_abstract = sprintf(
                        _('%s %s removed, none in the order anymore'),
                        -$delta_qty,
                        sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
                    );
                } else {
                    $history_abstract = sprintf(
                        _('%s %s removed'),
                        -$delta_qty,
                        sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $product->get('Store Key'), $product->id, $product->get('Product Code'))
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

            $account = get_object('Account', 1);
            $account->load_properties();

            if ($account->properties('tax_per_item')) {
                $this->update_tax();
            }

            $this->update_totals();
            $this->update_discounts_items();
            $this->update_totals();
            $this->update_shipping($dn_key);
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
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'            => 'update_deals_usage',
                        'campaigns'       => $campaigns_diff,
                        'deals'           => $deal_diff,
                        'deal_components' => $deal_components_diff,


                    ),
                    DNS_ACCOUNT_CODE,
                    $this->db
                );
            }

            $this->update_totals();


            if ($dn_key) {
                $dn->update_inventory_transaction_fact($otf_key, $quantity, $bonus_quantity);

                $dn->update_totals();
            }


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

                $this->update_aiku('Order Dimension', 'refresh_basket');
            } elseif ($this->get('Order State') == 'InProcess') {
                if ($this->get('Order Number Items') == 0) {
                    $operations = array(
                        'cancel_operations',
                        'undo_submit_operations'
                    );
                } else {
                    if ($this->get('Order Number Ordered Products') == 0) {
                        $operations = array(
                            'invoice_services_operations',
                            'cancel_operations',
                            'undo_submit_operations',
                            'proforma_operations'
                        );
                    } else {
                        $operations = array(
                            'send_to_warehouse_operations',
                            'cancel_operations',
                            'undo_submit_operations',
                            'proforma_operations'
                        );
                    }
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


            $add_class    = array();
            $remove_class = array();

            $show = array();
            $hide = array();


            if ($this->get('Order To Pay Amount') == 0) {
                $hide = array(
                    'Order_To_Pay_Amount',
                    'Order_Payments_Amount'
                );


                if ($this->get('Order Total Amount') == 0) {
                    array_push($hide, 'Order_Paid');
                } else {
                    $show = array('Order_Paid');
                }
            } elseif ($this->get('Order To Pay Amount') > 0) {
                $show = array(
                    'Order_To_Pay_Amount',
                    'To_Pay_Label',
                    'Order_Payments_Amount'
                );
                $hide = array(
                    'To_Refund_Label',
                    'Order_Paid'
                );
            } elseif ($this->get('Order To Pay Amount') < 0) {
                $show = array(
                    'Order_To_Pay_Amount',
                    'To_Refund_Label',
                    'Order_Payments_Amount'
                );
                $hide = array(
                    'To_Pay_Label',
                    'Order_Paid'
                );
            }


            if ($this->get('Order Charges Net Amount') == 0) {
                $add_class['order_charges_container'] = 'very_discreet';
                $hide[]                               = 'order_charges_info';
            } else {
                $remove_class['order_charges_container'] = 'very_discreet';
                $show[]                                  = 'order_charges_info';
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

            if ($this->get('Order Shipping Discount Amount') == 0) {
                $hide[] = 'Shipping_Gross_Amount';
            } else {
                $show[] = 'Shipping_Gross_Amount';
            }


            if ($this->get('Order Deal Amount Off') == 0) {
                $hide[] = 'Deal_Amount_Off_tr';
            } else {
                $show[] = 'Deal_Amount_Off_tr';
            }


            if ($this->get('Order Number Items') == 0) {
                $hide[] = 'order_payments_list';
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
                    'Shipping_Gross_Amount'         => $this->get('Shipping Gross Amount'),

                    'Profit_Amount'                  => $this->get('Profit Amount'),
                    'Order_Margin'                   => $this->get('Margin'),
                    'Order_Number_items'             => $this->get('Number Items'),
                    'Order_Number_Items_with_Deals'  => $this->get('Number Items with Deals'),
                    'Charges_Discount_Amount'        => $this->get('Charges Discount Amount'),
                    'Charges_Discount_Percentage'    => $this->get('Charges Discount Percentage'),
                    'Amount_Off_Discount_Percentage' => $this->get('Amount Off Percentage'),
                    'To_Pay_Amount_Absolute'         => $this->get('To Pay Amount Absolute'),
                    'Order_Estimated_Weight'         => $this->get('Estimated Weight'),
                    'Order_Number_Ordered_Products'  => $this->get('Number Ordered Products'),
                    'Order_Number_Services'          => $this->get('Number Services'),


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
        }


        if (in_array($this->get('Order State'), array(
            'Cancelled',
            'Approved',
            'Dispatched',
        ))) {
            $discounts_class = '';
            $discounts_input = '';
        } else {
            $discounts_class = 'button';
            $discounts_input = sprintf(
                '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d"  }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                $otf_key,
                percentage($gross_discounts, $gross)
            );
        }
        $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($gross_discounts == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                $gross_discounts,
                $gross
            ).'</span> <span class="'.($gross_discounts == 0 ? 'hide' : '').'">'.money($gross_discounts, $this->data['Order Currency']).'</span></span>';



        $this->fast_update_json_field('Order Metadata', 'family_order_distribution', json_encode($this->calculate_family_order_distribution()));



        return array(
            'updated'        => true,
            'otf_key'        => $otf_key,
            'to_charge'      => money($gross - $gross_discounts, $this->data['Order Currency']),
            'item_discounts' => $discounts,

            'net_amount'          => $gross,
            'delta_net_amount'    => $gross - $old_net_amount,
            'qty'                 => $quantity,
            'delta_qty'           => $quantity - $old_quantity,
            'bonus qty'           => $bonus_quantity,
            'discount_percentage' => ($gross_discounts > 0 ? percentage($gross_discounts, $gross, 1, 'NA', '') : '')
        );
    }

    function delete_transaction($otf_key)
    {
        $sql = sprintf(
            "DELETE FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d",
            $otf_key
        );


        $del = $this->db->prepare($sql);
        $del->execute();


        if ($del->rowCount()) {
            $this->deleted_otfs[] = $otf_key;
        }


        $sql = sprintf(
            "DELETE FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d",
            $otf_key
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key`=%d",
            $otf_key
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Order Transaction Out of Stock in Basket Bridge` WHERE `Order Transaction Fact Key`=%d",
            $otf_key
        );
        $this->db->exec($sql);
    }

    function get_items(): array
    {
        $sql = sprintf(
            'SELECT `Product Main Image Key`, `Category Code`,`Product Price`, is_variant,variant_parent_id,   (select group_concat(`Deal Info`) from `Order Transaction Deal Bridge` B  where B.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key` ) as deal_info,  `Order State`,`Delivery Note Quantity`,`Current Dispatching State`,`Deal Info`,OTF.`Product ID`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` 
FROM `Order Transaction Fact` OTF 
LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`)
 LEFT JOIN `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  
  left join  `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`)
    left join       `Order Dimension` O ON (O.`Order Key`=OTF.`Order Key`) 
      left join       `Category Dimension` C ON (C.`Category Key`=PD.`Product Family Category Key`) 
 WHERE OTF.`Order Key`=%d  ORDER BY `Product Code File As` ',
            $this->id
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
                        <i  class="fa plus  fa-plus fa-fw like_button "  style="visibility:hidden;cursor:pointer" aria-hidden="true"></i></span>',
                        $row['Order Transaction Fact Key'],
                        $row['Product ID'],
                        $row['Product Key'],
                        $row['Order Quantity'] + 0,
                        $row['Order Quantity'] + 0
                    );
                } else {
                    if ($this->get('Order Customer Client Key') > 0) {
                        $edit_quantity = sprintf(
                            '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'>
                        <i onClick="save_item_qty_change(this,{type:\'client_order\',client_key:'.$this->get('Order Customer Client Key').',order_key:'.$this->id.'})" class="fa minus fa-minus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i>
                        <input class="order_qty width_50 " style="text-align: center" value="%s" ovalue="%s"> 
                        <i onClick="save_item_qty_change(this,{type:\'client_order\',client_key:'.$this->get('Order Customer Client Key').',order_key:'.$this->id.'})"  class="fa plus  fa-plus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i></span>',
                            $row['Order Transaction Fact Key'],
                            $row['Product ID'],
                            $row['Product Key'],
                            $row['Order Quantity'] + 0,
                            $row['Order Quantity'] + 0
                        );
                    } else {
                        $edit_quantity = sprintf(
                            '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'>
                        <i onClick="save_item_qty_change(this)" class="fa minus fa-minus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i>
                        <input class="order_qty width_50 " style="text-align: center" value="%s" ovalue="%s"> 
                        <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw like_button "  style="cursor:pointer" aria-hidden="true"></i></span>',
                            $row['Order Transaction Fact Key'],
                            $row['Product ID'],
                            $row['Product Key'],
                            $row['Order Quantity'] + 0,
                            $row['Order Quantity'] + 0
                        );
                    }

                    $out_of_stock_info = '';
                }


                if ($row['Order State'] == 'Dispatched' or $row['Order State'] == 'Approved' or $row['Order State'] == 'PackedDone') {
                    $qty = number($row['Delivery Note Quantity']);
                } else {
                    $qty = number($row['Order Quantity']);
                }


                $code = sprintf('<a href="/'.strtolower($row['Product Code']).'">%s</a>', $row['Product Code']);
                if ($row['is_variant'] == 'Yes') {
                    $sql   = "select `Product Code` from `Product Dimension` where `Product ID`=? ";
                    $stmt2 = $this->db->prepare($sql);
                    $stmt2->execute(
                        [
                            $row['variant_parent_id']
                        ]
                    );
                    if ($row2 = $stmt2->fetch()) {
                        $code = sprintf('<a href="/'.strtolower($row2['Product Code']).'?variant=%s">%s</a>', $row['Product Code'], $row['Product Code']);
                    }
                }
                if ($row['Product History Units Per Case'] >= 1) {
                    $name = get_html_fractions($row['Product History Units Per Case']).'x '.$row['Product History Name'];
                } else {
                    $name = get_html_fractions($row['Product History Units Per Case']).' '.$row['Product History Name'];
                }
                $name .= $deal_info.$out_of_stock_info;

                $units_per_case=$row['Product History Units Per Case'];
                $units_per_case=$this->clean_num($units_per_case);
                $items[$row['Order Transaction Fact Key']] = array(
                    'code'             => $code,
                    'code_description' => '<b class="item_code _version_2">'.$row['Product Code'].'</b> <span class="item_description">'.$units_per_case.'x '.$row['Product History Name'].$deal_info.'</span>'.$out_of_stock_info,
                    'description'      => $name,
                    'price_raw'        => $row['Product Price'],
                    'qty'              => $qty,
                    'qty_raw'          => $row['Order Quantity'] + 0,
                    'pid'              => $row['Product ID'],
                    'otf_key'          => $row['Order Transaction Fact Key'],
                    'edit_qty'         => $edit_quantity,
                    'amount'           => '<span id="transaction_item_net_'.$row['Order Transaction Fact Key'].'" class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>',
                    'state'            => $row['Current Dispatching State'],
                    'image_key'=>$row['Product Main Image Key'],
                    'analytics_data'   => json_encode(array(
                                                          'id'       => $row['Product Code'],
                                                          'name'     => ($row['Product History Units Per Case'] > 1 ? $row['Product History Units Per Case'].'x ' : '').$row['Product Name'],
                                                          'category' => $row['Category Code'],
                                                          'price'    => $row['Product Price'],
                                                          'quantity' => $row['Order Quantity']
                                                      )

                    )
                );
            }
        }

        return $items;
    }

    function clean_num( $num ){
        $pos = strpos($num, '.');
        if($pos === false) { // it is integer number
            return $num;
        }else{ // it is decimal number
            return rtrim(rtrim($num, '0'), '.');
        }
    }

}



