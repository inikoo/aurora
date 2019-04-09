<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  23 January 2019 at 13:23:13 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


class data_entry_picking_aid {

    /** @var PDO */
    private $db;
    private $editor;
    /** @var Account */
    private $account;
    private $data;
    private $level;
    private $dn;
    /** @var Staff */
    private $picker;
    /** @var Staff */
    private $packer;
    private $shipper;

    function __construct($data, $editor, $db, $account) {
        $this->db      = $db;
        $this->editor  = $editor;
        $this->data    = $data;
        $this->account = $account;

        $this->level = $this->data['level'];


        $this->dn         = get_object('delivery_note', $this->data['delivery_note_key']);
        $this->dn->editor = $editor;


    }


    function parse_input_data() {


        if (empty($this->data['fields']['Delivery Note Assigned Picker Key'])) {
            $response = array(
                'state' => 400,
                'msg'   => 'delivery note assigned picker key missing'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );


        }


        if (empty($this->data['fields']['Delivery Note Assigned Packer Key'])) {
            $response = array(
                'state' => 400,
                'msg'   => 'delivery note assigned packer key missing'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }

        if (!isset($this->data['fields']['Delivery Note Shipper Key'])) {
            $response = array(
                'state' => 400,
                'msg'   => 'delivery note shipper key missing'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }

        if (!isset($this->data['fields']['Delivery Note Shipper Tracking'])) {
            $response = array(
                'state' => 400,
                'msg'   => 'delivery note tracking missing'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }

        if (!isset($this->data['fields']['Delivery Note Number Parcels'])) {
            $response = array(
                'state' => 400,
                'msg'   => 'delivery note number parcels missing'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }


        if ($this->level >= 10) {
            if (!is_numeric($this->data['fields']['Delivery Note Number Parcels']) or $this->data['fields']['Delivery Note Number Parcels'] < 0) {

                $response = array(
                    'state' => 400,
                    'msg'   => 'invalid number of parcels'
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

            }
        } else {
            if (!((is_numeric($this->data['fields']['Delivery Note Number Parcels']) and $this->data['fields']['Delivery Note Number Parcels'] >= 0) or $this->data['fields']['Delivery Note Number Parcels'] == '')) {

                $response = array(
                    'state' => 400,
                    'msg'   => 'invalid number of parcels'
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

            }
        }


        if ($this->level < 30 and $this->data['fields']['Delivery Note Shipper Key'] == '__none__') {
            $this->data['fields']['Delivery Note Shipper Key'] = '';
        }


        $this->picker = get_object('staff', $this->data['fields']['Delivery Note Assigned Picker Key']);

        if (!$this->picker->id) {
            $response = array(
                'state' => 400,
                'msg'   => 'picker not found'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }


        $this->packer = get_object('staff', $this->data['fields']['Delivery Note Assigned Packer Key']);

        if (!$this->packer->id) {
            $response = array(
                'state' => 400,
                'msg'   => 'packer not found'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }


        if ($this->data['fields']['Delivery Note Shipper Key'] != '') {

            $this->shipper = get_object('shipper', $this->data['fields']['Delivery Note Shipper Key']);

            if (!$this->shipper->id) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'shipper not found'
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

            }
        } else {
            $this->data['fields']['Delivery Note Shipper Tracking'] = '';

        }


        $itf_keys = array();
        $sql      = 'SELECT `Inventory Transaction Key` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=? ';
        $stmt     = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->dn->id
            )
        )) {
            while ($row = $stmt->fetch()) {
                $itf_keys[$row['Inventory Transaction Key']] = $row['Inventory Transaction Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


        $missing_itf_keys = $itf_keys;
        $extra_itf_keys   = array();
        foreach ($this->data['items'] as $_transaction) {


            foreach ($_transaction as $transaction) {


                if ($transaction['itf_key'] > 0) {
                    if (isset($missing_itf_keys[$transaction['itf_key']])) {
                        unset($missing_itf_keys[$transaction['itf_key']]);
                    } else {
                        $extra_itf_keys[$transaction['itf_key']] = $transaction['itf_key'];
                    }
                }


                if ($transaction['location_key'] <= 0) {

                    $response = array(
                        'state' => 400,
                        'msg'   => 'transaction with wrong location'
                    );

                    return array(
                        'valid'    => false,
                        'response' => $response
                    );
                }

                if ($transaction['part_sku'] <= 0) {

                    $response = array(
                        'state' => 400,
                        'msg'   => 'part key invalid'
                    );

                    return array(
                        'valid'    => false,
                        'response' => $response
                    );
                }

                if ($transaction['qty'] < 0) {

                    $response = array(
                        'state' => 400,
                        'msg'   => 'part quantity negative'
                    );

                    return array(
                        'valid'    => false,
                        'response' => $response
                    );
                }

            }


        }


        if (count($extra_itf_keys) > 0) {
            $response = array(
                'state' => 400,
                'msg'   => 'extra itf keys'
            );

            return array(
                'valid'    => false,
                'response' => $response
            );

        }


        return array(
            'valid'    => true,
            'response' => ''
        );

    }


    function update_delivery_note() {

        $this->dn->fast_update(
            array(
                'Delivery Note Assigned Picker Key'   => $this->picker->id,
                'Delivery Note Assigned Picker Alias' => $this->picker->get('Alias')
            )
        );


        $this->dn->fast_update(
            array(
                'Delivery Note Assigned Packer Key'   => $this->packer->id,
                'Delivery Note Assigned Packer Alias' => $this->packer->get('Alias')
            )
        );


        if (!isset($this->shipper)) {
            $this->dn->fast_update(
                array(
                    'Delivery Note Shipper Key' => '',
                )
            );
        } else {


            $this->dn->update(
                array(
                    'Delivery Note Shipper Key' => $this->shipper->id,
                ), 'no_history'
            );
        }


        $this->dn->fast_update(
            array(
                'Delivery Note Shipper Tracking' => $this->data['fields']['Delivery Note Shipper Tracking']
            )
        );


        if (!empty($this->data['fields']['Delivery Note Weight']) and is_numeric($this->data['fields']['Delivery Note Weight']) and $this->data['fields']['Delivery Note Weight'] > 0) {
            $this->dn->fast_update(
                array(
                    'Delivery Note Weight'        => $this->data['fields']['Delivery Note Weight'],
                    'Delivery Note Weight Source' => 'Given'
                )
            );
        } else {
            $this->dn->fast_update(
                array(
                    'Delivery Note Weight'        => '',
                    'Delivery Note Weight Source' => 'Estimated'
                )
            );
        }


        if (!empty($this->data['fields']['Delivery Note Number Parcels']) and is_numeric($this->data['fields']['Delivery Note Number Parcels']) and $this->data['fields']['Delivery Note Number Parcels'] > 0) {
            $this->dn->fast_update(
                array(
                    'Delivery Note Number Parcels' => $this->data['fields']['Delivery Note Number Parcels'],
                )
            );
        } else {
            $this->dn->fast_update(
                array(
                    'Delivery Note Number Parcels' => ''
                )
            );
        }


    }


    function process_transactions() {
        $this->clean_transactions();
        $this->recreate_itfs();
        $this->pack_transactions();
    }

    function clean_transactions() {


        foreach ($this->data['items'] as $part_sku => $_transaction) {


            foreach ($_transaction as $_key => $transaction) {

                if (!$transaction['qty'] > 0 and !$transaction['itf_key'] > 0) {
                    unset($this->data['items'][$part_sku][$_key]);
                }
            }
        }


    }

    function recreate_itfs() {


        foreach ($this->data['items'] as $part_sku => $_transaction) {

            $transactions_with_diff = 0;

            $itf_indexed_data = array();
            $total_qty        = 0;
            foreach ($_transaction as $transaction) {
                $total_qty += $transaction['qty'];
                if ($transaction['itf_key'] != '') {
                    $itf_indexed_data[$transaction['itf_key']] = $transaction;
                } else {
                    $transactions_with_diff++;
                }

            }


            $total_required    = 0;
            $part_transactions = array();
            $otf_maps          = array();

            $sql = 'SELECT `Required`,`Given`,`Map To Order Transaction Fact Key`,`Location Key`,`Inventory Transaction Key`, `Map To Order Transaction Fact Metadata` FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=? and `Part SKU`=? ';

            $stmt = $this->db->prepare($sql);
            if ($stmt->execute(
                array(
                    $this->dn->id,
                    $part_sku
                )
            )) {
                while ($row = $stmt->fetch()) {
                    $_required      = $row['Required'] + $row['Given'];
                    $total_required += $_required;

                    $part_transactions[$row['Inventory Transaction Key']]              = $row;
                    $part_transactions[$row['Inventory Transaction Key']]['_required'] = $_required;


                    if (isset($itf_indexed_data[$row['Inventory Transaction Key']]['qty'])) {
                        $_diff = $itf_indexed_data[$row['Inventory Transaction Key']]['qty'] - $_required;
                    } else {
                        $_diff = -$_required;
                    }

                    $part_transactions[$row['Inventory Transaction Key']]['_diff'] = $_diff;

                    if ($_diff != 0) {
                        $transactions_with_diff++;

                    }

                    if ($_diff < 0) {


                        $original_diff = $_diff;
                        //  $original_qty  = $row['Required'] + $row['Given'];

                        $_diff = abs($_diff);
                        $tmp   = min($row['Required'], $_diff);
                        //$required = $row['Required'] - $tmp;

                        $_required = $tmp;
                        $_given    = 0;
                        //$given     = $row['Given'];
                        $_diff = -$_diff - $tmp;
                        if ($_diff > 0) {
                            //$given  = $given - $tmp;
                            $_given = min($row['Given'], $_diff);
                        }


                        $_qty = $_given + $_required;

                        $otf_maps[] = array(
                            'diff'             => $original_diff,
                            'required'         => $_required,
                            'given'            => $_given,
                            'qty'              => $_qty,
                            'otf_map'          => $row['Map To Order Transaction Fact Key'],
                            'itf_key'          => $row['Inventory Transaction Key'],
                            'otf_map_metadata' => $row['Map To Order Transaction Fact Metadata']
                        );


                        //    if ($original_qty > abs($original_diff)) {

                        $sql = 'update  `Inventory Transaction Fact` set `Required`=`Required`-? ,`Given`=`Given`-? where `Inventory Transaction Key`=? ';


                        $stmt = $this->db->prepare($sql);
                        if (!$stmt) {
                            print_r($this->db->errorInfo());
                        }


                        if (!$stmt->execute(
                            [
                                $_required,
                                $_given,
                                $row['Inventory Transaction Key']
                            ]
                        )) {
                            print_r($stmt->errorInfo());
                        }


                        // } else {
                        //     $sql = 'delete from  `Inventory Transaction Fact` WHERE  `Inventory Transaction Key` =?';
                        //     $stmt = $this->db->prepare($sql);
                        //     $stmt->bindParam(1, $row['Inventory Transaction Key']);
                        //     $stmt->execute();
                        // }


                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit();
            }


            foreach ($_transaction as $index_transaction => $transaction) {

                if ($transaction['itf_key'] == '') {


                    $qty = $transaction['qty'];


                    // print_r($transaction);

                    foreach ($otf_maps as $_key => $otf_map) {
                        //  print_r($otf_map);

                        if ($otf_map['qty'] == $qty) {

                            // print '==a==';

                            $itf_key = $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);


                            $this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;

                            unset($otf_maps[$_key]);

                            break;

                        } elseif ($otf_map['qty'] < $qty) {

                            //  print '==b==';
                            //$itf_key = $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);

                            $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);
                            unset($otf_maps[$_key]);
                            //$this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;

                            $qty = $qty - $otf_map['qty'];

                            unset($otf_maps[$_key]);


                        } else {
                            //  print '==c==';
                            $taken_off_required = min($otf_map['required'], $qty);
                            $_required          = $otf_map['required'] - $taken_off_required;

                            $qty = $qty - $taken_off_required;

                            $taken_off_given = min($otf_map['required'], $qty);
                            $_given          = $otf_map['given'] - $taken_off_given;

                            // $qty = $qty - $taken_off_given;

                            $itf_key                     = $this->create_itf($part_sku, $transaction['location_key'], $taken_off_required, $taken_off_given, $otf_map['otf_map'], $otf_map['otf_map_metadata']);
                            $otf_maps[$_key]['required'] = $otf_maps[$_key]['required'] - $_required;
                            $otf_maps[$_key]['given']    = $otf_maps[$_key]['given'] - $_given;
                            $otf_maps[$_key]['qty']      = $otf_maps[$_key]['required'] + $otf_maps[$_key]['given'];
                            $otf_maps[$_key]['diff']     = $otf_maps[$_key]['diff'] + $taken_off_required + $taken_off_given;

                            $this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;


                            // $otf_maps[]=$otf_map;


                            break;


                        }


                    }


                }
            }


            foreach ($otf_maps as $otf_map) {

                if ($otf_map['itf_key'] > 0) {


                    $sql = 'update  `Inventory Transaction Fact` set `Required`=`Required`+? ,`Given`=`Given`+? where `Inventory Transaction Key`=? ';


                    $stmt = $this->db->prepare($sql);
                    if (!$stmt) {
                        print_r($this->db->errorInfo());
                    }


                    if (!$stmt->execute(
                        [
                            $otf_map['required'],
                            $otf_map['given'],
                            $otf_map['itf_key']
                        ]
                    )) {
                        print_r($stmt->errorInfo());
                    }


                }
            }


        }


    }

    function create_itf($part_sku, $location_key, $required, $given, $map_to_otf_key, $map_to_otf_metadata) {

        $part = get_object('Part', $part_sku);
        list($supplier_key, $supplier_part_key, $supplier_part_historic_key) = $part->get_stock_supplier_data();


        $weight = $part->get('Part Package Weight') * ($required + $given);


        $sql = "INSERT INTO `Inventory Transaction Fact`  (
					`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Inventory Transaction Fact Delivery 2 Alpha Code`,`Inventory Transaction Weight`,`Date Created`,`Date`,`Delivery Note Key`,`Part SKU`,`Location Key`,

					`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,

					`Metadata`,`Note`,`Supplier Product ID`,`Supplier Product Historic Key`,`Supplier Key`,`Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`)
					VALUES (
					?,?,?,
					?,?,?,?,?,?,
					?,?,?,?,?,?,
                    ?,?,?,?,?,?,?
					) ";
        $this->db->prepare($sql)->execute(
            [
                'Movement',
                'OIP',
                $this->dn->get('Delivery Note Address Country 2 Alpha Code'),
                $weight,
                $this->dn->get('Delivery Note Date'),
                $this->dn->get('Delivery Note Date'),
                $this->dn->id,
                $part_sku,
                $location_key,
                0,
                'Order In Process',
                0,
                $required,
                $given,
                0,
                $this->dn->data['Delivery Note Metadata'],
                '',
                $supplier_part_key,
                $supplier_part_historic_key,
                $supplier_key,
                $map_to_otf_key,
                $map_to_otf_metadata
            ]
        );


        return $this->db->lastInsertId();

    }

    function pack_transactions() {
        include_once 'utils/new_fork.php';


        foreach ($this->data['items'] as $part_sku => $_transaction) {

            foreach ($_transaction as $transaction) {


                if ($transaction['itf_key'] != '') {
                    $sql = sprintf('SELECT `Part SKU`,`Required`,`Given`,`Map To Order Transaction Fact Key`,`Location Key` FROM `Inventory Transaction Fact` WHERE `Inventory Transaction Key`=%d AND `Delivery Note Key`=%d ', $transaction['itf_key'], $this->dn->id);

                    if ($result = $this->dn->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $date = gmdate('Y-m-d H:i:s');


                            if ($transaction['qty'] == '') {
                                $transaction['qty'] = 0;
                            }

                            $part = get_object('Part', $row['Part SKU']);

                            $qty    = -1 * $transaction['qty'];
                            $cost   = $qty * $part->get('Part Cost in Warehouse');
                            $weight = $transaction['qty'] * $part->get('Part Package Weight');


                            $out_of_stock = $row['Required'] + $row['Given'] + -$transaction['qty'];


                            if ($out_of_stock == 0) {
                                $out_of_stock_tag = 'No';
                            } else {
                                $out_of_stock_tag = 'Yes';
                            }

                            $state = 'Done';

                            if ($transaction['qty'] == 0) {
                                $transaction_type    = 'No Dispatched';
                                $transaction_section = 'NoDispatched';
                                $date_picked         = '';
                                $date_packed         = '';
                            } else {
                                $transaction_type    = 'Sale';
                                $transaction_section = 'Out';
                                $date_picked         = $date;
                                $date_packed         = $date;
                            }


                            $sql = 'UPDATE  `Inventory Transaction Fact`  SET  
                                  `Date Picked`=? ,`Date Packed`=?,`Date`=? ,`Location Key`=? ,`Inventory Transaction Type`=? ,`Inventory Transaction Section`=? ,
                                  `Inventory Transaction Quantity`=?, `Inventory Transaction Amount`=?,`Inventory Transaction Weight`=?,
                                  `Picked`=?,`Packed`=?,`Out of Stock`=?,`Out of Stock Tag`=?,
                                  `Picker Key`=?,`Packer Key`=?,`Inventory Transaction State`=? WHERE `Inventory Transaction Key`=? ';


                            $this->db->prepare($sql)->execute(
                                [
                                    $date_picked,
                                    $date_packed,
                                    $date,
                                    $transaction['location_key'],
                                    $transaction_type,
                                    $transaction_section,
                                    $qty,
                                    $cost,
                                    $weight,
                                    $transaction['qty'],
                                    $transaction['qty'],
                                    $out_of_stock,
                                    $out_of_stock_tag,
                                    $this->dn->get('Delivery Note Assigned Picker Key'),
                                    $this->dn->get('Delivery Note Assigned Packer Key'),
                                    $state,
                                    $transaction['itf_key']

                                ]
                            );

                            $this->db->exec($sql);

                            $cost = 0;

                            $sql = sprintf('SELECT sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d ', $row['Map To Order Transaction Fact Key']);
                            if ($result2 = $this->dn->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    if ($row2['amount'] == '') {
                                        $row2['amount'] = 0;
                                    }

                                    $cost = -1 * $row2['amount'];
                                }
                            } else {
                                print_r($error_info = $this->dn->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }

                            $sql = sprintf('UPDATE `Order Transaction Fact` SET `Cost Supplier`=%f  WHERE  `Order Transaction Fact Key`=%d', $cost, $row['Map To Order Transaction Fact Key']);
                            //print "$sql\n";
                            $this->dn->db->exec($sql);


                            new_housekeeping_fork(
                                'au_housekeeping', array(
                                'type'         => 'update_part_location_stock',
                                'part_sku'     => $row['Part SKU'],
                                'location_key' => $row['Location Key'],
                            ), $this->account->get('Account Code')
                            );


                        }
                    } else {
                        print_r($error_info = $this->dn->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                }


            }

        }

    }

    function finish_packing() {

        $date = gmdate('Y-m-d H:i:s');

        $this->dn->fast_update(
            array(
                'Delivery Note Date Finish Picking' => $date,
                'Delivery Note Date Date'           => $date
            )
        );

        $this->dn->update_state('Packed');

        if ($this->level >= 10) {
            $this->dn->update_state('Packed Done');
        }


        if ($this->level >= 20 and $this->dn->get('Delivery Note Type') == 'Order') {
            $order         = get_object('order', $this->data['order_key']);
            $order->editor = $this->editor;
            $order->update_state('Approved');
            $this->dn->get_data('id', $this->dn->id);
        }
        if ($this->level >= 30) {
            $this->dn->update_state('Dispatched');
        }

    }


}