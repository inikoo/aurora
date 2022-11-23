<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  23 January 2019 at 13:23:13 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

/**
 * Class data_entry_picking_aid
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
    private $parcels;

    function __construct($data, $editor, $db, $account) {
        $this->db      = $db;
        $this->editor  = $editor;
        $this->data    = $data;
        $this->account = $account;
        $this->parcels = [];

        $this->level = $this->data['level'];


        $this->dn         = get_object('delivery_note', $this->data['delivery_note_key']);
        $this->dn->editor = $editor;


    }


    function parse_input_data(): array {


        if ($this->dn->get('State Index') < 70) {


            if (empty($this->data['fields']['Delivery Note Assigned Picker Key'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Picker missing')
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );


            }


            if (empty($this->data['fields']['Delivery Note Assigned Packer Key'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Packer missing')
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

            }

            if (!isset($this->data['fields']['Delivery Note Shipper Key'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Courier missing')
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

            }

            if (!isset($this->data['fields']['Delivery Note Shipper Tracking'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Tracking missing')
                );

                return array(
                    'valid'    => false,
                    'response' => $response
                );

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

                if ($this->shipper->get('Shipper API Key') != '' and $this->shipper->get('Code') == 'Whistl') {


                    if (count($this->data['parcels']) > 1) {
                        $response = array(
                            'state' => 400,
                            'msg'   => 'only 1 parcel allowed'
                        );

                        return array(
                            'valid'    => false,
                            'response' => $response
                        );
                    }

                    if ($this->data['parcels'][0]['weight'] > 2) {
                        $response = array(
                            'state' => 400,
                            'msg'   => 'Max weight is 2Kg'
                        );

                        return array(
                            'valid'    => false,
                            'response' => $response
                        );

                    }

                    $dim = [
                        $this->data['parcels'][0]['dim_0'],
                        $this->data['parcels'][0]['dim_1'],
                        $this->data['parcels'][0]['dim_2']
                    ];


                    if ($dim[0] > 61 or $dim[1] > 26 or $dim[2] > 26) {
                        $response = array(
                            'state' => 400,
                            'msg'   => 'Max allowed dimension is 61x26x26 cm'
                        );

                        return array(
                            'valid'    => false,
                            'response' => $response
                        );

                    }

                    if ($dim[0] == 0 or $dim[1] == 0 or $dim[2] == 0 or $dim[0] == '' or $dim[1] == '' or $dim[2] == '') {

                        $response = array(
                            'state' => 400,
                            'msg'   => 'Dimensions can not be zero'
                        );

                        return array(
                            'valid'    => false,
                            'response' => $response
                        );

                    }


                    $service    = [
                        'ServiceId'          => '78109',
                        'ServiceProviderId'  => '77',
                        'ServiceCustomerUID' => '21753',
                        //'21753',
                    ];
                    $reference2 = 'packet';
                    if ($this->data['parcels'][0]['weight'] < .75 and $dim[0] <= 35 and $dim[1] <= 25 and $dim[1] <= 2.5) {
                        $service    = [
                            'ServiceId'          => '78108',
                            'ServiceProviderId'  => '77',
                            'ServiceCustomerUID' => '21751',
                            //'21751',
                        ];
                        $reference2 = 'envelop';
                    }

                    /*
                    $reference2='yodel';
                    $service    = [
                        'ServiceId'          => '663',
                        'ServiceProviderId'  => '10',
                        'ServiceCustomerUID' => '31107',
                    ];
                    */

                    $this->data['service']    = json_encode($service);
                    $this->data['reference2'] = $reference2;


                }


                /*
                 *  foreach ($this->data['parcels'] as $parcel_data) {
                          if (is_numeric($parcel_data['weight']) and $parcel_data['weight'] > 0) {
                              $weight += $parcel_data['weight'];
                          }

                          $this->parcels[] = [
                              'weight' => $parcel_data['weight'],
                              'height' => $parcel_data['dim_0'],
                              'width'  => $parcel_data['dim_1'],
                              'depth'  => $parcel_data['dim_2'],

                          ];

                      }
                 */


            } else {
                $this->data['fields']['Delivery Note Shipper Tracking'] = '';

            }
        }


        if ($this->level > 5) {

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


                    if ($transaction['location_key'] <= 0 and $transaction['qty'] > 0) {

                        $response = array(
                            'state' => 400,
                            'msg'   => 'ohh no, transaction with wrong location again !!! :('
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
        }

        return array(
            'valid'    => true,
            'response' => ''
        );

    }


    /**
     * @throws \Exception
     */
    function update_delivery_note() {

        if ($this->dn->get('State Index') < 70) {
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


            if (empty($this->data['parcels']) or count($this->data['parcels']) == 0) {
                $this->dn->fast_update(
                    array(
                        'Delivery Note Weight'         => '',
                        'Delivery Note Weight Source'  => 'Estimated',
                        'Delivery Note Parcel Type'    => 'Other',
                        'Delivery Note Number Parcels' => 1
                    )
                );
            } else {

                $weight = 0;
                foreach ($this->data['parcels'] as $parcel_data) {
                    if (is_numeric($parcel_data['weight']) and $parcel_data['weight'] > 0) {
                        $weight += $parcel_data['weight'];
                    }

                    $this->parcels[] = [
                        'weight' => $parcel_data['weight'],
                        'height' => $parcel_data['dim_0'],
                        'width'  => $parcel_data['dim_1'],
                        'depth'  => $parcel_data['dim_2'],

                    ];

                }

                $number_parcels = count($this->parcels);

                if ($weight == 0) {

                    foreach ($this->parcels as $key => $value) {
                        $this->parcels[$key]['weight'] = $this->dn->get('Delivery Note Estimated Weight') / $number_parcels;
                    }
                    $weight_source = 'Estimated';
                    $weight        = '';
                } else {
                    $weight_source = 'Given';

                }

                $this->dn->fast_update(
                    array(
                        'Delivery Note Weight'         => $weight,
                        'Delivery Note Weight Source'  => $weight_source,
                        'Delivery Note Parcel Type'    => 'Box',
                        'Delivery Note Number Parcels' => $number_parcels
                    )
                );

                $this->dn->fast_update_json_field('Delivery Note Properties', 'parcels', json_encode($this->parcels));

            }
        }

        /*

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

        if (!empty($this->data['fields']['Delivery Note Parcel Type']) and in_array(
                $this->data['fields']['Delivery Note Parcel Type'], array(
                                                                      'Box',
                                                                      'Pallet',
                                                                      'Envelope',
                                                                      'Small Parcel',
                                                                      'Other',
                                                                      'None'
                                                                  )
            )) {
            $this->dn->fast_update(
                array(
                    'Delivery Note Parcel Type' => $this->data['fields']['Delivery Note Parcel Type'],
                )
            );
        } else {
            $this->dn->fast_update(
                array(
                    'Delivery Note Parcel Type' => 'Other'
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

        */


    }


    function process_transactions($options = '{}') {

        if ($this->level > 5) {
            $options = json_decode($options, true);
            if (!empty($options['date'])) {
                $date = $options['date'];
            } else {
                $date = gmdate('Y-m-d H:i:s');
            }


            $this->clean_transactions();
            $this->recreate_itf_records();

            $this->pack_transactions($date);
        }
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

    function recreate_itf_records() {


        foreach ($this->data['items'] as $part_sku => $_transaction) {

            $transactions_with_diff = 0;

            $itf_indexed_data = array();
            $total_qty        = 0;
            foreach ($_transaction as $transaction) {

                if ($transaction['qty'] == '') {
                    $transaction['qty'] = 0;
                }

                $total_qty += $transaction['qty'];
                if ($transaction['itf_key'] != '') {
                    $itf_indexed_data[$transaction['itf_key']] = $transaction;
                } else {
                    $transactions_with_diff++;
                }

            }


            $total_required = 0;
            //$part_transactions = array();
            $otf_maps = array();

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

                    //$part_transactions[$row['Inventory Transaction Key']]              = $row;
                    //$part_transactions[$row['Inventory Transaction Key']]['_required'] = $_required;

                    if (isset($itf_indexed_data[$row['Inventory Transaction Key']]['qty']) and is_numeric($itf_indexed_data[$row['Inventory Transaction Key']]['qty'])) {
                        $_diff = $itf_indexed_data[$row['Inventory Transaction Key']]['qty'] - $_required;
                    } else {
                        $_diff = -$_required;
                    }

                    //$part_transactions[$row['Inventory Transaction Key']]['_diff'] = $_diff;

                    if ($_diff != 0) {
                        $transactions_with_diff++;

                    }

                    if ($_diff < 0) {


                        $original_diff = $_diff;

                        $_diff = abs($_diff);
                        $tmp   = min($row['Required'], $_diff);

                        $_required = $tmp;
                        $_given    = 0;
                        $_diff     = -$_diff - $tmp;
                        if ($_diff > 0) {
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


                        $sql = 'update `Inventory Transaction Fact` set `Required`=`Required`-? ,`Given`=`Given`-? where `Inventory Transaction Key`=? ';
                        /*
                        print $sql;

                        print_r(
                            [
                                $_required,
                                $_given,
                                $row['Inventory Transaction Key']
                            ]
                        );
                        */


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


                    }


                }
            }

            //print_r($otf_maps);
            foreach ($_transaction as $index_transaction => $transaction) {

                if ($transaction['itf_key'] == '') {


                    $qty = $transaction['qty'];


                    // print_r($transaction);

                    foreach ($otf_maps as $_key => $otf_map) {
                        //print_r($otf_map);
                        //print "$qty\n";

                        if ($otf_map['qty'] == $qty) {

                            //print '==a==';
                            //exit;

                            $itf_key = $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);


                            $this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;

                            unset($otf_maps[$_key]);

                            break;

                        } elseif ($otf_map['qty'] < $qty) {

                            //print '==b==';
                            //exit;
                            //$itf_key = $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);

                            $this->create_itf($part_sku, $transaction['location_key'], $otf_map['required'], $otf_map['given'], $otf_map['otf_map'], $otf_map['otf_map_metadata']);
                            unset($otf_maps[$_key]);
                            //$this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;

                            $qty = $qty - $otf_map['qty'];

                            unset($otf_maps[$_key]);


                        } else {
                            //print '==c==';
                            //print_r($otf_map);
                            //print '==qty=='.$qty."\n";

                            //exit;
                            $taken_off_required = min($otf_map['required'], $qty);

                            //print '==$taken_off_required=='.$taken_off_required."\n";

                            $_required = $otf_map['required'] - $taken_off_required;

                            //print '==$_required=='.$_required."\n";


                            $qty = $qty - $taken_off_required;

                            $taken_off_given = min($otf_map['required'], $qty);
                            $_given          = $otf_map['given'] - $taken_off_given;

                            // $qty = $qty - $taken_off_given;

                            $itf_key = $this->create_itf($part_sku, $transaction['location_key'], $taken_off_required, $taken_off_given, $otf_map['otf_map'], $otf_map['otf_map_metadata']);

                            //$otf_maps[$_key]['required'] = $otf_maps[$_key]['required'] - $_required;
                            //$otf_maps[$_key]['given']    = $otf_maps[$_key]['given'] - $_given;

                            $otf_maps[$_key]['required'] = $_required;
                            $otf_maps[$_key]['given']    = $_given;
                            $otf_maps[$_key]['qty']      = $otf_maps[$_key]['required'] + $otf_maps[$_key]['given'];
                            $otf_maps[$_key]['diff']     = $otf_maps[$_key]['diff'] + $taken_off_required + $taken_off_given;

                            $this->data['items'][$part_sku][$index_transaction]['itf_key'] = $itf_key;


                            // $otf_maps[]=$otf_map;


                            break;


                        }


                    }


                }
            }

            //  continue;

            //print_r($otf_maps);


            foreach ($otf_maps as $otf_map) {

                if ($otf_map['itf_key'] > 0) {


                    $sql = 'update  `Inventory Transaction Fact` set `Required`=`Required`+? ,`Given`=`Given`+? where `Inventory Transaction Key`=? ';
                    /*
                    print $sql;
                    print_r(
                        [
                            $otf_map['required'],
                            $otf_map['given'],
                            $otf_map['itf_key']
                        ]
                    );
                    */
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

    function create_itf($part_sku, $location_key, $required, $given, $map_to_otf_key, $map_to_otf_metadata): string {

        /**
         * @var Part $part
         */
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
                'Info',
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

    function pack_transactions($date) {
        include_once 'utils/new_fork.php';


        foreach ($this->data['items'] as $_transaction) {

            foreach ($_transaction as $transaction) {


                if ($transaction['itf_key'] != '') {
                    $sql = sprintf('SELECT `Part SKU`,`Required`,`Given`,`Map To Order Transaction Fact Key`,`Location Key` FROM `Inventory Transaction Fact` WHERE `Inventory Transaction Key`=%d AND `Delivery Note Key`=%d ', $transaction['itf_key'], $this->dn->id);

                    if ($result = $this->dn->db->query($sql)) {
                        if ($row = $result->fetch()) {


                            if ($transaction['qty'] == '') {
                                $transaction['qty'] = 0;
                            }

                            $part = get_object('Part', $row['Part SKU']);

                            $qty    = -1 * $transaction['qty'];
                            $cost   = $qty * $part->get('Part Cost in Warehouse');
                            $weight = $transaction['qty'] * $part->get('Part Package Weight');


                            $out_of_stock = $row['Required'] + $row['Given'] + -$transaction['qty'];


                            if ($transaction['qty'] == 0) {
                                $transaction_record_type = 'Info';
                                $transaction_type        = 'No Dispatched';
                                $transaction_section     = 'NoDispatched';
                                $date_picked             = '';
                                $date_packed             = '';
                            } else {
                                $transaction_record_type = 'Movement';
                                $transaction_type        = 'Sale';
                                $transaction_section     = 'Out';
                                $date_picked             = $date;
                                $date_packed             = $date;
                            }


                            $sql = 'UPDATE  `Inventory Transaction Fact`  SET  `Inventory Transaction Record Type`=?,`Date Picked`=? ,`Date Packed`=?,`Date`=? ,`Location Key`=? ,`Inventory Transaction Type`=? ,`Inventory Transaction Section`=? ,
                                  `Inventory Transaction Quantity`=?, `Inventory Transaction Amount`=?,`Inventory Transaction Weight`=?,
                                  `Picked`=?,`Packed`=?,`Out of Stock`=?,
                                  `Picker Key`=?,`Packer Key`=? WHERE `Inventory Transaction Key`=? ';


                            $this->db->prepare($sql)->execute(
                                [
                                    $transaction_record_type,
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
                                    $this->dn->get('Delivery Note Assigned Picker Key'),
                                    $this->dn->get('Delivery Note Assigned Packer Key'),
                                    $transaction['itf_key']

                                ]
                            );


                            $cost = 0;

                            $sql = 'SELECT sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=?';

                            $stmt2 = $this->dn->db->prepare($sql);
                            $stmt2->execute(
                                array(
                                    $row['Map To Order Transaction Fact Key']
                                )
                            );
                            while ($row2 = $stmt2->fetch()) {
                                if ($row2['amount'] == '') {
                                    $row2['amount'] = 0;
                                }

                                $cost = -1 * $row2['amount'];
                            }


                            $sql = 'UPDATE `Order Transaction Fact` SET `Cost Supplier`=?  WHERE  `Order Transaction Fact Key`=?';


                            $this->dn->db->prepare($sql)->execute(
                                array(
                                    $cost,
                                    $row['Map To Order Transaction Fact Key']
                                )
                            );


                            new_housekeeping_fork(
                                'au_housekeeping', array(
                                'type'         => 'update_part_location_stock',
                                'part_sku'     => $row['Part SKU'],
                                'location_key' => $row['Location Key'],
                            ), $this->account->get('Account Code')
                            );


                        }
                    }
                }


            }

        }

    }

    /**
     * @throws \Exception
     */
    function finish_packing($options = '{}') {

        $state_index = $this->dn->get('State Index');

        $options = json_decode($options, true);
        if (!empty($options['date'])) {
            $date = $options['date'];
        } else {
            $date = gmdate('Y-m-d H:i:s');
        }

        if ($state_index < 70) {

            $this->dn->fast_update(
                array(
                    'Delivery Note Date Finish Picking' => $date,
                    'Delivery Note Date Finish Packing' => $date
                )
            );

            $this->dn->update_state('Packed', json_encode(array('date' => $date)));
        }
        if ($this->level >= 10) {
            $this->dn->update_state('Packed Done', json_encode(array('date' => $date)));
        }

        if ($state_index < 70) {

            if (isset($this->shipper) and $this->shipper->id and $this->shipper->get('Shipper API Key') != '') {
                $service = '';
                if (isset($this->data['service'])) {
                    $service = $this->data['service'];
                }
                $reference2 = '';
                if (isset($this->data['reference2'])) {
                    $reference2 = $this->data['reference2'];
                }


                $this->dn->get_label($service, $reference2);
            }
        }

        if ($this->level >= 20 and $this->dn->get('Delivery Note Type') == 'Order') {
            $order         = get_object('order', $this->data['order_key']);
            $order->editor = $this->editor;
            $order->update_state('Approved', json_encode(array('date' => $date)));
            $this->dn->get_data('id', $this->dn->id);
        }
        if ($this->level >= 30) {
            $this->dn->update_state('Dispatched', json_encode(array('date' => $date)));
        }

    }


}