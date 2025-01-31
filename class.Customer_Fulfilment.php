<?php

/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 26 june 2021 14:12 Kuala Lumpur Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Customer_Fulfilment extends DB_Table
{


    function __construct($a1, $a2 = false)
    {
        global $db;
        $this->db         = $db;
        $this->error_code = '';

        $this->table_name    = 'Customer Fulfilment';
        $this->ignore_fields = array('Customer Fulfilment Customer Key');

        $this->get_data($a1, $a2);
    }

    function get_data($tipo, $tag)
    {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Fulfilment Dimension` WHERE `Customer Fulfilment Customer Key`=%d",
                $tag
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Customer Fulfilment Customer Key'];
            }
        }
    }

    function get($key = '')
    {
        if (!$this->id) {
            return false;
        }

        /*
        switch ($key) {


        }
        */

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Customer Fulfilment '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Customer Fulfilment Allow Part Procurement':
                $label = _('Full product procurement service');
                break;
            case 'Customer Fulfilment Allow Pallet Storing':
                $label = _('Asset storing');
                break;
            default:
                $label = $field;
        }

        return $label;
    }

    function create_customer_delivery($_data): Fulfilment_Delivery
    {
        exit();
        $warehouse = get_object('Warehouse', $_data['warehouse_key']);

        $customer = get_object('Customer', $this->id);
        $store    = get_object('Store', $customer->get('Customer Store Key'));

        $delivery_data = array(
            'Fulfilment Delivery Type'                  => ($store->get('Store Type') == 'Dropshipping' ? 'Part' : 'Asset'),
            'Fulfilment Delivery Customer Key'          => $this->id,
            'Fulfilment Delivery Store Key'             => $customer->get('Customer Store Key'),
            'Fulfilment Delivery Customer Name'         => $customer->get('Name'),
            'Fulfilment Delivery Customer Contact Name' => $customer->get('Main Contact Name'),
            'Fulfilment Delivery Customer Email'        => $customer->get('Main Plain Email'),
            'Fulfilment Delivery Customer Telephone'    => $customer->get('Preferred Contact Number Formatted Number'),
            'Fulfilment Delivery Customer Address'      => $customer->get('Contact Address Formatted'),
            'Fulfilment Delivery Customer Country Code' => $customer->get('Contact Address Country 2 Alpha Code'),
            'Fulfilment Delivery Warehouse Key'         => $warehouse->data['Warehouse Key'],
            'editor'                                    => $this->editor,
        );


        $delivery = new Fulfilment_Delivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;
        }


        return $delivery;
    }


    function update_rent_order()
    {
        exit();
        $number_assets = 0;

        $sql = "select count(*) as num from `Fulfilment Asset Dimension`  where `Fulfilment Asset Customer Key`=? and `Fulfilment Asset State` in ('BookedIn','BookedOut')   ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        if ($row = $stmt->fetch()) {
            $number_assets = $row['num'];
        }

        if ($number_assets == 0 and !$this->data['Customer Fulfilment Current Rent Order Key']) {
            return;
        }


        $customer = get_object('Customer', $this->id);
        if (!$this->data['Customer Fulfilment Current Rent Order Key']) {
            $order = $customer->create_order();
            $this->fast_update([
                                   'Customer Fulfilment Current Rent Order Key' => $order->id
                               ]);


            $order->update_state('InProcess');
        } else {
            $order = get_object('Order', $this->data['Customer Fulfilment Current Rent Order Key']);

            if ($order->get('Order State') != 'InProcess') {
                $order = $customer->create_order();
                $this->fast_update([
                                       'Customer Fulfilment Current Rent Order Key' => $order->id
                                   ]);
            }
        }
        $order->fast_update([
                                'Order Type' => 'FulfilmentRent'
                            ]);
        $order->update_state('InProcess');
        $this->create_rent_transactions();
    }


    function create_rent_transactions()
    {
        exit();
        $account = get_object('Account', 1);
        $account->load_acc_data();


        $order = get_object('Order', $this->data['Customer Fulfilment Current Rent Order Key']);

        $sql  = "select `Fulfilment Asset Key` from `Fulfilment Asset Dimension`  where `Fulfilment Asset Customer Key`=? and `Fulfilment Asset State` in  ('BookedIn','BookedOut')   ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->id
            ]
        );
        while ($row = $stmt->fetch()) {


            $asset = get_object('Fulfilment_Asset', $row['Fulfilment Asset Key']);

            if ($asset->get('Fulfilment Asset Last Rent Order Date') == '') {
                $from = new DateTime(gmdate('Y-m-d', strtotime($asset->get('Fulfilment Asset From').' +0:00')));
            } else {
                $tmp = new DateTime($asset->get('Fulfilment Asset Last Rent Order Date'));

                $from  = $tmp->add(new DateInterval('P1D'));



            }

            if ($asset->get('Fulfilment Asset To') == '') {
                $to = new DateTime(gmdate('Y-m-d'));
            } else {
                $tmp = new DateTime($asset->get('Fulfilment Asset To'));


                $to  = $tmp->add(new DateInterval('P1D'));



            }


            $pos_diff = $from->diff($to)->format("%r%a") + 1;



            if ($pos_diff >= 0) {
                //print $asset->id."  {$from->format('Y-m-d')} {$to->format('Y-m-d')}  $pos_diff  \n";


                if ($asset->get('Fulfilment Asset Type') == 'Box') {
                    $product_id = $account->properties('fulfilment_box_rent');
                }elseif ($asset->get('Fulfilment Asset Type') == 'Oversize') {
                    $product_id = $account->properties('fulfilment_oversize_rent');
                } else {
                    $product_id = $account->properties('fulfilment_pallet_rent');
                }


                $sql = "insert into `Fulfilment Rent Transaction Fact`  (`Fulfilment Rent Transaction Asset Key`,`Fulfilment Rent Transaction Order Key`,`Fulfilment Rent Transaction Product ID`,
                                                 `Fulfilment Rent Transaction From`,`Fulfilment Rent Transaction To`,`Fulfilment Rent Transaction Units`
                                                 ) values (?,?, ?,?,?,?) ON DUPLICATE KEY UPDATE `Fulfilment Rent Transaction Product ID`=?,`Fulfilment Rent Transaction From`=?,`Fulfilment Rent Transaction To`=?,`Fulfilment Rent Transaction Units`=?  ";



                $this->db->prepare($sql)->execute(
                    [
                        $asset->id,
                        $this->data['Customer Fulfilment Current Rent Order Key'],
                        $product_id,

                        $from->format('Y-m-d'),
                        $to->format('Y-m-d'),
                        round($pos_diff, 6),


                        $product_id,
                        $from->format('Y-m-d'),
                        $to->format('Y-m-d'),
                        round($pos_diff, 6)
                    ]
                );
            }
        }

        $sql = "select  count(`Fulfilment Rent Transaction Asset Key`) as assets ,  sum(`Fulfilment Rent Transaction Units`) as units ,`Fulfilment Rent Transaction Product ID` from `Fulfilment Rent Transaction Fact` where `Fulfilment Rent Transaction Order Key`=? group by `Fulfilment Rent Transaction Product ID` ";


        $stmt2 = $this->db->prepare($sql);
        $stmt2->execute(
            [
                $this->data['Customer Fulfilment Current Rent Order Key'],
            ]
        );
        while ($row2 = $stmt2->fetch()) {
            //print_r($row2);

            if ($row2['units'] >= 0) {
                $quantity          = $row2['units'];
                $dispatching_state = 'In Process';
                $payment_state     = 'Waiting Payment';
                $product           = get_object('Product', $row2['Fulfilment Rent Transaction Product ID']);
                $data              = array(
                    'date'                      => gmdate('Y-m-d H:i:s'),
                    'item_historic_key'         => $product->get('Product Current Key'),
                    'item_key'                  => $product->id,
                    'Metadata'                  => '',
                    'qty'                       => $quantity,
                    'Current Dispatching State' => $dispatching_state,
                    'Current Payment State'     => $payment_state,
                    'Order Type'                => 'Order'
                );


                $transaction_data = $order->update_item($data);
                $sql              = "update `Order Transaction Fact` set `Order Transaction Metadata`=? where `Order Transaction Fact Key`=?";


                $this->db->prepare($sql)->execute(
                    [
                        json_encode([
                                        'lock'      => true,
                                        'sub_items' => [
                                            'qty'  => $row2['assets'],
                                            'type' => 'rent_assets'
                                        ]
                                    ]),
                        $transaction_data['otf_key']
                    ]
                );


                $unit_cost = 0;
                $sql       = "select `Order Transaction Amount`  from `Order Transaction Fact`  where `Order Transaction Fact Key`=?";
                $stmt3     = $this->db->prepare($sql);
                $stmt3->execute(
                    [
                        $transaction_data['otf_key']
                    ]
                );
                while ($row3 = $stmt3->fetch()) {
                    $unit_cost = $row3['Order Transaction Amount'] / $row2['units'];
                }

                $sql = "update `Fulfilment Rent Transaction Fact` set `Fulfilment Rent Transaction OTF Key`=? ,`Fulfilment Rent Transaction Unit Price`=? where  `Fulfilment Rent Transaction Order Key`=? and  `Fulfilment Rent Transaction Product ID`=?";

                //print $sql;

                $this->db->prepare($sql)->execute(
                    [
                        $transaction_data['otf_key'],
                        $unit_cost,
                        $this->data['Customer Fulfilment Current Rent Order Key'],
                        $row2['Fulfilment Rent Transaction Product ID']
                    ]
                );
            }
        }
    }


    function update_stats()
    {

//        $stored_assets = 0;
//
//        $sql = "select count(*) as num from `Fulfilment Asset Dimension`  where `Fulfilment Asset Customer Key`=? and `Fulfilment Asset State` in ('BookedIn')  ";
//
//        $stmt = $this->db->prepare($sql);
//        $stmt->execute([$this->id]);
//        if ($row = $stmt->fetch()) {
//            $stored_assets = $row['num'];
//        }
//
//        $this->fast_update(
//            [
//                'Customer Fulfilment Stored Assets' => $stored_assets
//            ]
//        );
    }

}