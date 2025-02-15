<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;

include_once 'class.Asset.php';
include_once 'trait.PartAiku.php';

class Part extends Asset
{

    use PartAiku;

    public bool $trigger_discontinued;

    function __construct($arg1, $arg2 = false, $arg3 = false, $_db = false)
    {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name           = 'Part';
        $this->ignore_fields        = array('Part SKU');
        $this->trigger_discontinued = true;

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if (preg_match('/^find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }

        if (preg_match('/^create/i', $arg1)) {
            $this->create($arg2);

            return;
        }


        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag)
    {
        if ($tipo == 'id' or $tipo == 'sku') {
            $sql = sprintf(
                "SELECT * FROM `Part Dimension` WHERE `Part SKU`=%d ",
                $tag
            );
        } elseif ($tipo == 'barcode') {
            $sql = sprintf(
                "SELECT * FROM `Part Dimension` WHERE `Part SKO Barcode`=%s ",
                prepare_mysql($tag)
            );
        } else {
            if ($tipo == 'code' or $tipo == 'reference') {
                $sql = sprintf(
                    "SELECT * FROM `Part Dimension` WHERE `Part Reference`=%s ",
                    prepare_mysql($tag)
                );
            } else {
                return;
            }
        }

        // print $sql;

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Part SKU'];
            $this->properties = json_decode($this->data['Part Properties'], true);
        }
    }

    function find($raw_data, $options)
    {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }


        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`=%s",
            prepare_mysql($data['Part Reference'])
        );


        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                print_r($row);

                $this->found     = true;
                $this->found_key = $row['Part SKU'];
                $this->get_data('id', $this->found_key);
            }
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }
    }

    function create($data)
    {
        include_once 'class.Account.php';
        include_once 'class.Category.php';

        $account = new Account($this->db);

        if (array_key_exists('Part Family Category Code', $data) and $data['Part Family Category Code'] != '') {
            $root_category = new Category(
                $account->get('Account Part Family Category Key')
            );
            if ($root_category->id) {
                $root_category->editor = $this->editor;
                $family                = $root_category->create_category(array('Category Code' => $data['Part Family Category Code']));
                if ($family->id) {
                    $data['Part Family Category Key'] = $family->id;
                }
            }
        }

        if (!isset($data['Part Valid From']) or $data['Part Valid From'] == '') {
            $data['Part Valid From'] = gmdate('Y-m-d H:i:s');
        }


        if (!isset($data['Part Recommended Packages Per Selling Outer'])) {
            $data['Part Recommended Packages Per Selling Outer'] = 1;
        }


        $base_data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        foreach ($base_data as $key => $value) {
            if ($value == '') {
                unset($base_data[$key]);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Part Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($base_data)).'`',
            join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($base_data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        //  print "$sql\n";

        if ($stmt->execute()) {
            $this->id  = $this->db->lastInsertId();
            $this->id  = $this->id;
            $this->new = true;

            $sql = "INSERT INTO `Part Data` (`Part SKU`) VALUES(".$this->id.");";
            $this->db->exec($sql);

            $this->fast_update(array('Part Properties' => '{}'));

            $this->get_data('id', $this->id);


            if (!empty($data['Part Barcode'])) {
                $this->update(array('Part Barcode' => $data['Part Barcode']), 'no_history');
            }
            $this->update_next_deliveries_data();

            $this->calculate_forecast_data();

            $this->update_products_web_status();

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _('Part created'),
                'History Details'  => ''
            );
            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );


            //  print 'x'.$this->get('Part Family Category Key')."\n";

            if ($this->get('Part Family Category Key')) {
                $family         = new Category(
                    $this->get('Part Family Category Key')
                );
                $family->editor = $this->editor;


                if ($family->id) {
                    $family->associate_subject($this->id);
                }
            }

            $this->validate_barcode();
            $this->update_weight_status();

            $this->fork_index_elastic_search();
            $this->model_updated( 'new', $this->id);
        } else {
            print "Error Part can not be created $sql\n";
            $this->msg = 'Error Part can not be created';
            exit;
        }
    }

    function update_next_deliveries_data()
    {
        $data = $this->get_next_deliveries_data();

        //print_r($data);

        $this->fast_update(array(
            'Part Next Deliveries Data'     => json_encode($data['deliveries']),
            'Part Next Shipment Date'       => ($data['next_delivery_time'] != '' ? gmdate('Y-m-d H:i:s', $data['next_delivery_time']) : ''),
            'Part Number Active Deliveries' => $data['number_non_draft_POs'],
            'Part Number Draft Deliveries'  => $data['number_draft_POs'],
            'Part Units in Deliveries'      => $data['units_in_deliveries']


        ));


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_next_shipment();
        }
    }

    function get_next_deliveries_data(): array
    {
        $units_in_deliveries = 0;

        $next_delivery_time       = 999999999999;
        $valid_next_delivery_time = false;


        $next_deliveries_data = array();

        $number_draft_POs     = 0;
        $number_non_draft_POs = 0;

        $supplier_parts = $this->get_supplier_parts();
        if (count($supplier_parts) > 0) {
            $sql = sprintf(
                "SELECT  `Purchase Order Transaction Type`,`Supplier Delivery Estimated Receiving Date`,`Supplier Part Packages Per Carton`,`Purchase Order Key`,`Supplier Delivery Transaction State`,`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,`Part Units Per Package`,
                `Supplier Delivery Units`, `Supplier Delivery Checked Units`,
                ifnull(`Supplier Delivery Placed Units`,0) AS placed,POTF.`Supplier Delivery Key`,`Supplier Delivery Public ID` FROM 
                `Purchase Order Transaction Fact` POTF LEFT JOIN 
                `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)  left join  
                `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
                WHERE POTF.`Supplier Part Key` IN (%s)  AND  POTF.`Supplier Delivery Key` IS NOT NULL AND `Supplier Delivery Transaction State` in ('InProcess','Dispatched','Received','Checked')
                

                
                 ",
                implode(',', $supplier_parts)
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    //print_r($row);

                    if ($row['Supplier Delivery Checked Units'] > 0 or $row['Supplier Delivery Checked Units'] == '') {
                        if ($row['Supplier Delivery Checked Units'] == '') {
                            $raw_units_qty = $row['Supplier Delivery Units'];
                        } else {
                            $raw_units_qty = $row['Supplier Delivery Checked Units'] - $row['placed'];
                        }
                        $units_in_deliveries += $raw_units_qty;

                        if ($raw_units_qty > 0) {
                            $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];

                            $supplier_delivery = get_object('SupplierDelivery', $row['Supplier Delivery Key']);


                            $number_non_draft_POs++;
                            $date = '';

                            if ($supplier_delivery->get('State Index') >= 40) {
                                $_next_delivery_time = strtotime('tomorrow');

                                if ($_next_delivery_time > gmdate('U') and $_next_delivery_time < $next_delivery_time) {
                                    $next_delivery_time       = $_next_delivery_time;
                                    $valid_next_delivery_time = true;
                                }
                            } else {
                                if ($row['Supplier Delivery Estimated Receiving Date'] != '') {
                                    $_next_delivery_time = strtotime($row['Supplier Delivery Estimated Receiving Date'].' +0:00');

                                    if ($_next_delivery_time > gmdate('U')) {
                                        if ($_next_delivery_time < $next_delivery_time) {
                                            $next_delivery_time       = $_next_delivery_time;
                                            $valid_next_delivery_time = true;
                                            $date                     = strftime("%e %b %y", strtotime($row['Supplier Delivery Estimated Receiving Date'].' +0:00'));
                                        }
                                    }
                                }
                            }


                            if ($row['Purchase Order Transaction Type'] == 'Production') {
                                $state = _('Delivered');


                                $link = sprintf('production/%d/delivery/%d', $row['Supplier Delivery Parent Key'], $row['Supplier Delivery Key']);

                                $formatted_link = sprintf(
                                    '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'%s\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-hand-holding-heart" title="%s" ></i> <span class="strong">%s</span>',
                                    $link,
                                    $row['Supplier Delivery Public ID'],
                                    _('Delivered'),
                                    number($row['Supplier Delivery Checked Units'] / $row['Part Units Per Package'])
                                );

                                $formatted_state = '<span class=" italic">'._('Delivered').'</span>';
                            } else {
                                switch ($row['Supplier Delivery Transaction State']) {
                                    case 'InProcess':
                                        $state = sprintf('%s', _('In Process'));
                                        break;
                                    case 'Consolidated':
                                        $state = sprintf('%s', _('Consolidated'));
                                        break;
                                    case 'Dispatched':
                                        $state = sprintf('%s', _('Dispatched'));
                                        break;
                                    case 'Received':
                                        $state = sprintf('%s', _('Received'));
                                        break;
                                    case 'Checked':
                                        $state = sprintf('%s', _('Checked'));
                                        break;


                                    default:
                                        $state = $row['Supplier Delivery State'];
                                        break;
                                }


                                $formatted_link = sprintf(
                                    '<i class="fal fa-truck fa-fw" ></i> <i style="visibility: hidden" class="fal fa-truck fa-fw" ></i> <span class="link" onclick="change_view(\'%s/%d/delivery/%d\')"> %s</span>',
                                    strtolower($row['Supplier Delivery Parent']),
                                    $row['Supplier Delivery Parent Key'],
                                    $row['Supplier Delivery Key'],
                                    $row['Supplier Delivery Public ID']
                                );
                                $link           = sprintf('%s/%d/delivery/%d', strtolower($row['Supplier Delivery Parent']), $row['Supplier Delivery Parent Key'], $row['Supplier Delivery Key']);

                                $formatted_state = ($date == '' ? '<span class=" italic">'.$row['Supplier Delivery Transaction State'].'</span>' : $date);
                            }


                            $next_deliveries_data[] = array(
                                'type'           => 'delivery',
                                'qty'            => '+'.number($raw_skos_qty),
                                'raw_sko_qty'    => $raw_skos_qty,
                                'raw_units_qty'  => $raw_units_qty,
                                'date'           => $date,
                                'formatted_link' => $formatted_link,
                                'link'           => $link,

                                'order_id'        => $row['Supplier Delivery Public ID'],
                                'formatted_state' => $formatted_state,
                                'state'           => $state,

                                'po_key' => $row['Purchase Order Key']
                            );
                        }
                    }
                }
            }


            $sql = sprintf(
                "SELECT (ifnull(`Purchase Order Submitted Units`,0)-ifnull(`Purchase Order Submitted Cancelled Units`,0)) submitted_units,`Purchase Order Manufactured Units`,`Purchase Order QC Pass Units`,`Purchase Order Estimated Receiving Date`,`Purchase Order Estimated Start Production Date`,`Supplier Part Packages Per Carton`,POTF.`Purchase Order Transaction State`,`Purchase Order Submitted Units`,`Supplier Delivery Key` ,`Purchase Order Estimated Receiving Date`,`Purchase Order Public ID`,POTF.`Purchase Order Key` ,
                `Part Units Per Package`,`Purchase Order Ordering Units`,`Purchase Order Transaction Type`,`Supplier Key`,`Purchase Order Type`
        FROM `Purchase Order Transaction Fact` POTF LEFT JOIN `Purchase Order Dimension` PO  ON (PO.`Purchase Order Key`=POTF.`Purchase Order Key`)  
          left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
        
        WHERE POTF.`Supplier Part Key`IN (%s) AND  POTF.`Supplier Delivery Key` IS NULL AND POTF.`Purchase Order Transaction State` NOT IN ('Placed','Cancelled','InvoiceChecked','NoReceived') ",
                implode(',', $supplier_parts)
            );


            // print $sql;

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Purchase Order Transaction State'] == 'InProcess') {
                        $number_draft_POs++;

                        $raw_units_qty = $row['Purchase Order Ordering Units'];


                        $units_in_deliveries += $raw_units_qty;

                        $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];

                        $_next_delivery_time = 0;
                        $date                = '';
                        $formatted_state     = '<span class="very_discreet italic">'._('Draft').'</span>';

                        if ($row['Purchase Order Transaction Type'] == 'Production') {
                            $formatted_link = sprintf(
                                '<i class="fal fa-fw  very_discreet fa-clipboard" ></i> <i class="fal fa-fw  very_discreet fa-seedling" title="%s" ></i> <span class="link very_discreet" onclick="change_view(\'production/%d/order/%d\')"> %s</span>',
                                _('In process'),
                                $row['Supplier Key'],
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                        } else {
                            $formatted_link = sprintf(
                                '<i class="fal fa-fw  very_discreet fa-clipboard" ></i> <i class="fal fa-fw  very_discreet fa-seedling" title="%s" ></i> <span class="link very_discreet" onclick="change_view(\'suppliers/order/%d\')"> %s</span>',
                                _('In process'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                        }


                        $qty = '<span class="very_discreet italic">+'.number($raw_skos_qty).'</span>';
                    } elseif ($row['Purchase Order Transaction State'] == 'Submitted') {
                        $number_draft_POs++;

                        $raw_units_qty = $row['submitted_units'];

                        $units_in_deliveries += $raw_units_qty;


                        $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];

                        $_next_delivery_time = 0;
                        $date                = '';


                        if ($row['Purchase Order Transaction Type'] == 'Production') {
                            $formatted_state = '<span class="very_discreet italic">'._('Queued').'</span>';
                            $formatted_link  = sprintf(
                                '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-user-clock" title="%s" ></i> <span class="strong">%s</span>',
                                $row['Supplier Key'],
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID'],
                                _('Queued'),
                                number($row['submitted_units'] / $row['Part Units Per Package'])
                            );

                            if ($row['Purchase Order Estimated Start Production Date'] != '') {
                                $formatted_link .= ' <span title="'._('Scheduled start production date').'"><i class="fal fa-play success small"></i> <span class="discreet italic">'.strftime(
                                        "%a %e %b",
                                        strtotime($row['Purchase Order Estimated Start Production Date'].' +0:00')
                                    ).'</span>';
                            }
                        } else {
                            $formatted_state = '<span class="very_discreet italic">'._('Submitted').'</span>';
                            $formatted_link  = sprintf(
                                '<i class="fal fa-fw  very_discreet fa-clipboard" ></i> <i class="fal fa-fw very_discreet fa-paper-plane" title="%s" ></i> <span class="link very_discreet" onclick="change_view(\'suppliers/order/%d\')"> %s</span>',
                                _('Submitted'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                        }


                        $qty = '<span class="very_discreet italic">+'.number($raw_skos_qty).'</span>';
                    } else {
                        if ($row['Purchase Order Transaction Type'] == 'Production') {
                            $_next_delivery_time = 0;
                            $date                = '';


                            //'','','','','','Confirmed','Manufactured','QC_Pass','','InDelivery','Inputted','Dispatched','Received','Checked','',''

                            switch ($row['Purchase Order Transaction State']) {
                                case 'Confirmed':
                                    $formatted_state = _('Manufacturing');
                                    $formatted_link  = sprintf(
                                        '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-fill-drip" title="%s" ></i> <span class="strong">%s</span>',
                                        $row['Supplier Key'],
                                        $row['Purchase Order Key'],
                                        $row['Purchase Order Public ID'],
                                        $formatted_state,
                                        number($row['submitted_units'] / $row['Part Units Per Package'])
                                    );

                                    if ($row['Purchase Order Estimated Receiving Date'] != '') {
                                        $formatted_link .= ' <span title="'._('Estimated production date').'"><i class="fal fa-play  purple small"></i> <span class="discreet italic">'.strftime(
                                                "%a %e %b",
                                                strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00')
                                            ).'</span>';
                                    }

                                    $qty = '+'.number($row['submitted_units'] / $row['Part Units Per Package']);

                                    $raw_units_qty       = $row['submitted_units'];
                                    $units_in_deliveries += $raw_units_qty;

                                    $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];

                                    break;
                                case 'Manufactured':
                                    $formatted_state = _('Manufactured');
                                    $formatted_link  = sprintf(
                                        '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-flag-checkered" title="%s" ></i> <span class="strong">%s</span>',
                                        $row['Supplier Key'],
                                        $row['Purchase Order Key'],
                                        $row['Purchase Order Public ID'],
                                        $formatted_state,
                                        number($row['Purchase Order Manufactured Units'] / $row['Part Units Per Package'])
                                    );
                                    $qty             = '+'.number($row['Purchase Order Manufactured Units'] / $row['Part Units Per Package']);

                                    $raw_units_qty       = $row['Purchase Order Manufactured Units'];
                                    $units_in_deliveries += $raw_units_qty;

                                    $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];
                                    break;
                                case 'QC_Pass':
                                    $formatted_state     = _('QC pass');
                                    $formatted_link      = sprintf(
                                        '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-siren-on" title="%s" ></i> <span class="strong">%s</span>',
                                        $row['Supplier Key'],
                                        $row['Purchase Order Key'],
                                        $row['Purchase Order Public ID'],
                                        $formatted_state,
                                        number($row['Purchase Order QC Pass Units'] / $row['Part Units Per Package'])
                                    );
                                    $qty                 = '+'.number($row['Purchase Order QC Pass Units'] / $row['Part Units Per Package']);
                                    $raw_units_qty       = $row['Purchase Order QC Pass Units'];
                                    $units_in_deliveries += $raw_units_qty;

                                    $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];
                                    break;
                                case 'Dispatched':
                                case 'InDelivery':
                                case 'Inputted':
                                case 'Received':
                                case 'Checked':

                                    $formatted_state     = _('Delivered');
                                    $formatted_link      = sprintf(
                                        '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span> <i class="fal fa-fw padding_left_5 fa-user-clock" title="%s" ></i> <span class="strong">%s</span>',
                                        $row['Supplier Key'],
                                        $row['Purchase Order Key'],
                                        $row['Purchase Order Public ID'],
                                        $formatted_state,
                                        number($row['Purchase Order QC Pass Units'] / $row['Part Units Per Package'])
                                    );
                                    $qty                 = '+'.number($row['Purchase Order QC Pass Units'] / $row['Part Units Per Package']);
                                    $raw_units_qty       = $row['Purchase Order QC Pass Units'];
                                    $units_in_deliveries += $raw_units_qty;

                                    $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];
                                    break;
                                default:
                                    $formatted_state = _('Unknown');
                                    $formatted_link  = sprintf(
                                        '<i class="fal fa-fw fa-clipboard" ></i> <span class="link " onclick="change_view(\'production/%d/order/%d\')"> %s</span>',
                                        $row['Supplier Key'],
                                        $row['Purchase Order Key'],
                                        $row['Purchase Order Public ID']
                                    );
                                    $qty             = '';
                                    $raw_units_qty   = '';
                                    $raw_skos_qty    = '';
                                    break;
                            }
                        } else {
                            //print_r($row);

                            $number_non_draft_POs++;
                            $raw_units_qty       = $row['submitted_units'];
                            $units_in_deliveries += $raw_units_qty;

                            $raw_skos_qty = $raw_units_qty / $row['Part Units Per Package'];


                            $formatted_link = sprintf(
                                '<i class="fal fa-fw  fa-clipboard" ></i> <i class="fal fa-fw  fa-calendar-check" title="%s" ></i> <span class="link" onclick="change_view(\'suppliers/order/%d\')">  %s</span>',
                                _('Confirmed'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                            $qty            = '+'.number($raw_skos_qty);


                            if ($row['Purchase Order Estimated Receiving Date'] != '') {
                                $_next_delivery_time = strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00');
                                $date                = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));
                                if ($_next_delivery_time < gmdate('U')) {
                                    $formatted_state = '<span class="discreet error italic" title="'.strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00')).'" >'._('Delayed').'</span>';
                                } else {
                                    $formatted_state = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));
                                }
                            } else {
                                $_next_delivery_time = 0;
                                $date                = '';
                                $formatted_state     = '';
                            }
                        }
                    }


                    $next_deliveries_data[] = array(
                        'type'          => 'po',
                        'qty'           => $qty,
                        'raw_sko_qty'   => $raw_skos_qty,
                        'raw_units_qty' => $raw_units_qty,

                        'date'            => $date,
                        'formatted_state' => $formatted_state,

                        'formatted_link' => $formatted_link,
                        'link'           => ($row['Purchase Order Transaction Type'] == 'Production' ? sprintf('production/%d/order/%d', $row['Supplier Key'], $row['Purchase Order Key']) : sprintf('suppliers/order/%d', $row['Purchase Order Key'])),
                        'order_id'       => $row['Purchase Order Public ID'],
                        'state'          => $row['Purchase Order Transaction State'],
                        'po_key'         => $row['Purchase Order Key']
                    );


                    if ($_next_delivery_time > gmdate('U') and $_next_delivery_time < $next_delivery_time) {
                        $next_delivery_time       = $_next_delivery_time;
                        $valid_next_delivery_time = true;
                    }
                }
            }
        }


        if (!$valid_next_delivery_time) {
            $next_delivery_time = 0;
        }

        // print_r($next_deliveries_data);

        return array(
            'deliveries'           => $next_deliveries_data,
            'next_delivery_time'   => (!$next_delivery_time ? '' : $next_delivery_time),
            'number_non_draft_POs' => $number_non_draft_POs,
            'number_draft_POs'     => $number_draft_POs,
            'units_in_deliveries'  => $units_in_deliveries

        );
    }


    function get_supplier_parts($scope = 'keys'): array
    {
        if ($scope == 'objects') {
            include_once 'class.SupplierPart.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ',
            $this->id
        );

        $supplier_parts = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $object = new SupplierPart('id', $row['Supplier Part Key'], false, $this->db);
                    $object->load_supplier();
                    $supplier_parts[$row['Supplier Part Key']] = $object;
                } else {
                    $supplier_parts[$row['Supplier Part Key']] = $row['Supplier Part Key'];
                }
            }
        }

        return $supplier_parts;
    }

    function get_products($scope = 'keys'): array
    {
        if ($scope == 'data' or $scope == 'products_data') {
            $fields = '*';
        } else {
            $fields = '`Product Part Product ID`';
        }

        $sql = sprintf(
            'SELECT %s FROM `Product Part Bridge` WHERE `Product Part Part SKU`=%d ',
            $fields,
            $this->id
        );

        $products = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $products[$row['Product Part Product ID']] = get_object('Product', $row['Product Part Product ID']);
                } elseif ($scope == 'data') {
                    $products[$row['Product Part Product ID']] = $row;
                } elseif ($scope == 'products_data') {
                    $product = get_object('Product', $row['Product Part Product ID']);
                    $store   = get_object('Store', $product->get('Product Store Key'));


                    $products[$row['Product Part Product ID']] = array(
                        'store_key'         => $store->id,
                        'store_code'        => $store->get('Code'),
                        'store_name'        => $store->get('Name'),
                        'product_id'        => $product->id,
                        'units_per_case'    => $product->get('Product Units Per Case'),
                        'name'              => $product->get('Product Name'),
                        'status'            => $product->get('Product Status'),
                        'web_status'        => $product->get('Product Web State'),
                        'parts_per_product' => $row['Product Part Ratio']

                    );
                } else {
                    $products[$row['Product Part Product ID']] = $row['Product Part Product ID'];
                }
            }
        }

        return $products;
    }

    function calculate_forecast_data()
    {
        $required_last_quarter = 0;

        $sql = sprintf('SELECT sum(`Required`) AS required FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Date`>= ( NOW() - INTERVAL 1 QUARTER ) ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $required_last_quarter = $row['required'];
            }
        }

        $forecast_metadata = json_encode(array(
            'method'                => 'Simple',
            'Required last quarter' => $required_last_quarter
        ));


        $this->update_field('Part Forecast Metadata', $forecast_metadata, 'no_history');
    }

    function update_products_web_status()
    {
        $products            = 0;
        $products_web_status = '';
        //'Offline','No Products','Online','Out of Stock'

        foreach ($this->get_products('objects') as $product) {
            if (!($product->get('Product Status') == 'Discontinued' or $product->get('Product Web State') == 'Discontinued')) {
                //'For Sale','Out of Stock','Discontinued','Offline'

                if ($product->get('Product Web State') == 'For Sale') {
                    $products_web_status = 'Online';
                    break;
                } elseif ($product->get('Product Web State') == 'Out of Stock') {
                    $products_web_status = 'Out of Stock';
                } elseif ($product->get('Product Web State') == 'Offline') {
                    if ($products_web_status == '') {
                        $products_web_status = 'Offline';
                    }
                }


                $products++;
            }
        }

        if ($products_web_status == '') {
            $products_web_status = 'No Products';
        }

        //print $this->get('Reference').' '.$products_web_status."\n";

        $this->fast_update(array(
            'Part Products Web Status' => $products_web_status
        ));
    }

    function get($key = '', $args = false)
    {
        $account = new Account($this->db);

        list($got, $result) = $this->get_asset_common($key, $args);
        if ($got) {
            return $result;
        }

        if (!$this->id) {
            return false;
        }


        switch ($key) {
            case 'Manufactured by':
                $supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));
                $supplier      = get_object('Supplier', $supplier_part->get('Supplier Part Supplier Key'));

                if ($supplier->get('Supplier Type') == 'Agent') {
                    foreach ($supplier->get_agents('data') as $agent_data) {
                        return $agent_data['Agent Name'];
                    }
                }

                return $supplier->get('Name');


            case 'Picking Band Key':
            case 'Packing Band Key':

                if (!$this->data['Part '.$key]) {
                    $account = get_object('Account', 1);
                    $account->load_acc_data();

                    if ($key == 'Picking Band Key') {
                        $_key = 'default_picking_band_amount';
                    } else {
                        $_key = 'default_packing_band_amount';
                    }

                    return _('Default').' '.'('.money($account->properties($_key), $account->get('Account Currency')).')';
                } else {
                    $band = get_object('PickingBand', $this->data['Part '.$key]);

                    return $band->get('Name').' ('.$band->get('Amount').')';
                }


            case 'Symbol':
                switch ($this->data['Part Symbol']) {
                    case 'star':
                        return '&#9733;';
                    case 'skull':
                        return '&#9760;';
                    case 'radioactive':
                        return '&#9762;';
                    case 'peace':
                        return '&#9774;';
                    case 'sad':
                        return '&#9785;';
                    case 'gear':
                        return '&#9881;';
                    case 'love':
                        return '&#10084;';
                }


                break;


            case 'Unknown Location Stock':

                if ($this->data['Part Unknown Location Stock'] > 0) {
                    return '<span class="error">'.number(-$this->data['Part Unknown Location Stock']).'</span>';
                } elseif ($this->data['Part Unknown Location Stock'] < 0) {
                    return '<span class="success">+'.number(-$this->data['Part Unknown Location Stock']).'</span>';
                } else {
                    return '<span class="discreet">'.number($this->data['Part Unknown Location Stock']).'</span>';
                }


            case 'Units Per Carton':

                return $this->data['Part Units Per Package'] * $this->data['Part SKOs per Carton'];


            case 'SKOs per Carton':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Supplier Part Packages Per Carton');

            /*
            $suppliers = '';

            foreach ($this->get_supplier_parts('objects') as $supplier_part) {
                $supplier_part->load_supplier();
                $suppliers .= $supplier_part->supplier->get('Code').' '.$supplier_part->get('Supplier Part Packages Per Carton').' '._("Supplier's product ordering SKOs per carton").', ';

            }
            $suppliers = preg_replace('/\, $/', '', $suppliers);

            if ($this->data['Part SKOs per Carton'] == '') {
                return '<span class="error">'._('Not set')."</span> <span class='italic very_discreet'>(".$suppliers.')</span>';

            } else {
                return number($this->data['Part SKOs per Carton'])." <span class='italic very_discreet'>(".$suppliers.')</span>';

            }
*/


            case 'Supplier Part Packages Per Carton':
            case 'Supplier Part Carton CBM':
            case 'Supplier Part Carton Barcode':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                $value = $main_supplier_part->get($key);
                if ($value == '') {
                    $sql = "`$key` from `Supplier Part Dimension` where `Supplier Part Part SKU`=? and  `Supplier Part Key`!=?  ";


                    $stmt = $this->db->prepare('select '.$sql);
                    $stmt->execute(array(
                        $this->id,
                        $this->get('Part Main Supplier Part Key')
                    ));
                    while ($row = $stmt->fetch()) {
                        if ($row[$key] != '') {
                            $value = $row[$key];
                            break;
                        }
                    }
                }


                return $value;


            case 'Supplier Part Carton Weight':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                $value = $main_supplier_part->get($key);
                if ($value == '') {
                    $sql = "select `Supplier Part Properties` from `Supplier Part Dimension` where `Supplier Part Part SKU`=? and  `Supplier Part Key`!=?  ";


                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                        $this->id,
                        $this->get('Part Main Supplier Part Key')
                    ));
                    while ($row = $stmt->fetch()) {
                        if ($row['Supplier Part Properties'] != '') {
                            $properties = json_decode($row['Supplier Part Properties'], true);


                            if (!empty($properties['carton_weight'])) {
                                $value = $properties['carton_weight'];
                                break;
                            }
                        }
                    }
                }

                return $value;


            case 'CBM per Unit':

                $value_sum = 0;
                $count     = 0;
                foreach ($this->get_supplier_parts('objects') as $supplier_part) {
                    if (is_numeric($supplier_part->get('Supplier Part Carton CBM')) and $supplier_part->get('Supplier Part Carton CBM') > 0 and $this->data['Part Units Per Package'] > 0 and $supplier_part->get('Supplier Part Packages Per Carton') > 0) {
                        $count++;
                        $value_sum += ($supplier_part->get('Supplier Part Carton CBM') / $supplier_part->get('Supplier Part Packages Per Carton') / $this->data['Part Units Per Package']);
                    }


                    if ($count > 0) {
                        return $value_sum / $count;
                    } else {
                        return '';
                    }
                }


                break;


            case 'Products Numbers':

                return number($this->data['Part Number Active Products']).",<span class=' very_discreet'>".number($this->data['Part Number No Active Products']).'</span>';


            case 'Stock Status Icon':

                if ($this->data['Part Status'] == 'In Process') {
                    return '';
                }

                switch ($this->data[$this->table_name.' Stock Status']) {
                    case 'Surplus':
                        $stock_status = '<i class="fa  fa-plus-circle fa-fw"  title="'._('Surplus stock').'"></i>';
                        break;
                    case 'Optimal':
                        $stock_status = '<i class="fa fa-check-circle fa-fw"  title="'._('Optimal stock').'"></i>';
                        break;
                    case 'Low':
                        $stock_status = '<i class="fa fa-minus-circle fa-fw"  title="'._('Low stock').'"></i>';
                        break;
                    case 'Critical':
                        $stock_status = '<i class="fa error fa-minus-circle fa-fw"   title="'._('Critical stock').'"></i>';
                        break;
                    case 'Out_Of_Stock':
                    case 'Out_of_Stock':
                        $stock_status = '<i class="fa error fa-ban fa-fw"   title="'._('Out of stock').'"></i>';
                        break;
                    case 'Error':
                        $stock_status = '<i class="fa fa-question-circle fa-fw"   title="'._('Error').'"></i>';
                        break;
                    default:
                        $stock_status = $this->data[$this->table_name.' Stock Status'];
                        break;
                }

                return $stock_status;

            case 'Part Family Category Code':

                if ($this->data['Part Family Category Key'] == '') {
                    return '';
                }

                include_once 'class.Category.php';

                $category = new Category(
                    $this->data['Part Family Category Key']
                );

                if ($category->id) {
                    return $category->get('Code');
                } else {
                    return '';
                }


            case 'Products Web Status':

                if ($this->data['Part Status'] == 'Not In Use') {
                    if ($this->data['Part Products Web Status'] == 'Online') {
                        return '<i class="fa fa-exclamation-circle error" ></i> '._('Online');
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {
                        return '<i class="fa fa-exclamation-circle warning" ></i> '._('Out of stock');
                    }
                } else {
                    if ($this->data['Part Products Web Status'] == 'Offline') {
                        return '<span class="warning"><i class="fa fa-exclamation-circle" ></i> '._('Offline').'</span>';
                    } elseif ($this->data['Part Products Web Status'] == 'No Products') {
                        return _('No products associated');
                    } elseif ($this->data['Part Products Web Status'] == 'Online') {
                        if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                            return '<span class="error"><i class="fa fa-exclamation-circle" ></i> '._('Online').'</span>';
                        } else {
                            return _('Online');
                        }
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {
                        if ($this->data['Part Status'] == 'In Process') {
                            return '';
                        } else {
                            return _('Out of stock');
                        }
                    } else {
                        return $this->data['Part Products Web Status'];
                    }
                }

                break;
            case 'Status':
                if ($this->data['Part Status'] == 'In Use') {
                    return _('Active');
                } elseif ($this->data['Part Status'] == 'Discontinuing') {
                    return _('Discontinuing');
                } elseif ($this->data['Part Status'] == 'Not In Use') {
                    return _('Discontinued');
                } elseif ($this->data['Part Status'] == 'In Process') {
                    return _('In process');
                } else {
                    return $this->data['Part Status'];
                }


            case 'Cost in Warehouse only':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = _('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf('<span title="%s">%s/SKO</span>', _('SKO stock value'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency')));
                }


                return $sko_cost;


            case 'Cost in Warehouse':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = _('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf('<span title="%s">%s /SKO</span>', _('SKO stock value'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency')));
                }


                $total_value = $this->data['Part Cost in Warehouse'] * $this->get('Part Current On Hand Stock');

                if ($total_value > 0) {
                    $total_value = sprintf('<span title="%s" class="hide_in_history" >%s %s</span>', _('total stock value'), money($total_value, $account->get('Account Currency')), _('total'));
                } else {
                    $total_value = '';
                }


                return ' <span class="discreet" style="margin-right:10px">'.$total_value.'</span>'.$sko_cost;

            case 'SKO Cost in Warehouse - Price':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = '<i class="fa fa-exclamation-circle error" ></i> '._('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf(
                        _('SKO stock value %s'),
                        money($this->data['Part Cost in Warehouse'], $account->get('Account Currency'))

                    );
                }


                $sko_recommended_price = sprintf(
                    _('recommended SKO commercial value: %s'),
                    ($this->data['Part Unit Price'] > 0 ? money($this->data['Part Unit Price'] * $this->data['Part Units Per Package'], $account->get('Account Currency')) : '<span class="italic discreet">'._('not set').'</span>')

                );


                if ($this->data['Part Units Per Package'] != 0 and is_numeric($this->data['Part Units Per Package']) and $this->data['Part Cost in Warehouse'] != '') {
                    $unit_margin = $this->data['Part Unit Price'] - ($this->data['Part Cost in Warehouse'] / $this->data['Part Units Per Package']);

                    $sko_recommended_price .= sprintf(
                        ' (<span class="'.($unit_margin < 0 ? 'error' : '').'">%s '._('margin').'</span>)',
                        percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }


                return $sko_cost.' <span class="discreet" style="margin-left:10px">'.$sko_recommended_price.'</span>';


            case 'Unit Price':
                if ($this->data['Part Unit Price'] == '') {
                    return '';
                }
                include_once 'utils/natural_language.php';
                $unit_price = money(
                    $this->data['Part Unit Price'],
                    $account->get('Account Currency')
                );

                if ($this->data['Part Cost in Warehouse'] != '') {
                    $cost           = $this->data['Part Cost in Warehouse'];
                    $formatted_cost = sprintf(_('Current stock value per unit in warehouse %s'), money($cost / $this->data['Part Units Per Package'], $account->get('Currency Code')));
                } else {
                    $cost           = $this->data['Part Cost'];
                    $formatted_cost = sprintf(_('Supplier unit cost %s'), money($cost / $this->data['Part Units Per Package'], $account->get('Currency Code')));
                }


                $price_other_info = '';
                if ($this->data['Part Units Per Package'] != 1 and is_numeric($this->data['Part Units Per Package'])) {
                    $price_other_info = '('.money(
                            $this->data['Part Unit Price'] * $this->data['Part Units Per Package'] * $this->data['Part Recommended Packages Per Selling Outer'],
                            $account->get('Account Currency')
                        ).' '._('per selling outer').'), ';
                }


                if ($this->data['Part Units Per Package'] != 0 and is_numeric($this->data['Part Units Per Package'])) {
                    $unit_margin = $this->data['Part Unit Price'] - ($cost / $this->data['Part Units Per Package']);

                    $price_other_info .= sprintf(
                        '<span title="%s" class="'.($unit_margin < 0 ? 'error' : '').'">'._('margin %s').'</span>',
                        $formatted_cost,
                        percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }

                $price_other_info = preg_replace(
                    '/^, /',
                    '',
                    $price_other_info
                );
                if ($price_other_info != '') {
                    $unit_price .= ' <span class="discreet">'.$price_other_info.'</span>';
                }

                return $unit_price;

            case 'Unit RRP':
                if ($this->data['Part Unit RRP'] == '') {
                    return '';
                }

                include_once 'utils/natural_language.php';
                $rrp = money(
                    $this->data['Part Unit RRP'],
                    $account->get('Account Currency')
                );


                $unit_margin    = $this->data['Part Unit RRP'] - $this->data['Part Unit Price'];
                $rrp_other_info = sprintf(
                    _('margin %s'),
                    percentage($unit_margin, $this->data['Part Unit RRP'])
                );


                $rrp_other_info = preg_replace('/^, /', '', $rrp_other_info);
                if ($rrp_other_info != '') {
                    $rrp .= ' <span class="'.($unit_margin < 0 ? 'error' : '').'  discreet">'.$rrp_other_info.'</span>';
                }

                return $rrp;

            case 'Barcode':

                if ($this->get('Part Barcode Number') == '') {
                    return '';
                }


                return '<i '.($this->get('Part Barcode Key') ? 'class="fa fa-barcode button" onClick="change_view(\'inventory/barcode/'.$this->get('Part Barcode Key').'\')"' : 'class="fa fa-barcode"').' ></i><span class="Part_Barcode_Number ">'.$this->get(
                        'Part Barcode Number'
                    ).'</span>';


            case 'Available Forecast':


                if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                    return '';
                }

                if (in_array($this->data['Part Products Web Status'], array(
                    'No Products',
                    'Offline',
                    'Out of Stock'
                ))) {
                    return '';
                }


                include_once 'utils/natural_language.php';

                if ($this->data['Part On Demand'] == 'Yes') {
                    $available_forecast = '<span >'.sprintf(
                            _('%s in stock'),
                            '<span  title="'.sprintf("%s %s", number($this->data['Part Days Available Forecast']), ngettext("day", "days", intval($this->data['Part Days Available Forecast']))).'">'.seconds_to_until(
                                $this->data['Part Days Available Forecast'] * 86400
                            ).'</span>'
                        ).'</span>';

                    if ($this->data['Part Fresh'] == 'No') {
                        $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5"  title="'._('On demand').'"></i>';
                    } else {
                        $available_forecast = ' <i class="far fa-lemon padding_left_5"  title="'._('On demand').'"></i>';
                    }
                } else {
                    $available_forecast = '<span >'.sprintf(
                            _('%s availability'),
                            '<span  title="'.sprintf(
                                "%s %s",
                                number($this->data['Part Days Available Forecast']),
                                ngettext(
                                    "day",
                                    "days",
                                    intval($this->data['Part Days Available Forecast'])
                                )
                            ).'">'.seconds_to_until($this->data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';
                }


                return $available_forecast;


            case 'Origin Country Code':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return '<img src="/art/flags/'.strtolower(
                            $country->get('Country 2 Alpha Code')
                        ).'.png" title="'.$country->get('Country Code').'"> '._(
                            $country->get('Country Name')
                        );
                } else {
                    return '';
                }


            case 'Origin Country':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return $country->get('Country Name');
                } else {
                    return '';
                }


            case 'Next Shipment':
                if ($this->data['Part Next Shipment Date'] == '') {
                    return '';
                } else {
                    $date = strftime("%a, %e %b %y", strtotime($this->data['Part Next Shipment Date'].' +0:00'));

                    if ($this->data['Part Next Shipment Object Key']) {
                        if ($this->data['Part Next Shipment Object'] == 'PurchaseOrder') {
                            $date = sprintf('<span class="button" onclick="change_view(\'suppliers/order/%d\')">%s</span>', $this->data['Part Next Shipment Object Key'], $date);
                        } elseif ($this->data['Part Next Shipment Object'] == 'SupplierDelivery') {
                            $date = sprintf('<span class="button" onclick="change_view(\'suppliers/delivery/%d\')">%s</span>', $this->data['Part Next Shipment Object Key'], $date);
                        }
                    }


                    return $date;
                }


            case('Current Stock Available'):

                return number(
                    $this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process'] - $this->data['Part Current Stock Ordered Paid'],
                    6
                );


            case('Current On Hand Stock'):
            case('Current Stock'):
            case ('Current Stock Picked'):
            case ('Current Stock In Process'):
            case ('Current Stock Ordered Paid'):
                return number($this->data['Part '.$key], 6);


            case('Valid From'):
            case('Valid From Datetime'):

                return strftime(
                    "%a %e %b %Y %H:%M %Z",
                    strtotime($this->data['Part Valid From'].' +0:00')
                );

            case('Valid To'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z",
                    strtotime($this->data['Part Valid To'].' +0:00')
                );

            case 'Package Description Image':


                if (!$this->data['Part SKO Image Key']) {
                    $image = '/art/nopic.png';
                } else {
                    $image = sprintf('<img src="/image.php?id=%d&s=25x20" alt=""> ', $this->data['Part SKO Image Key']);
                }


                return $image;


            case 'Acc To Day Updated':
            case 'Acc Ongoing Intervals Updated':
            case 'Acc Previous Intervals Updated':

                if ($this->data['Part '.$key] == '') {
                    $value = '';
                } else {
                    $value = strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Part '.$key].' +0:00'));
                }

                return $value;

            case 'Cost':
            case 'Commercial Value':
                return money($this->data['Part '.$key], $account->get('Currency Code'));

            case 'Margin':
                return percentage($this->data['Part '.$key], 1);


            case 'Barcode Number Error':

                if ($this->data['Part Barcode Number Error'] == '') {
                    return '';
                }


                switch ($this->data['Part Barcode Number Error']) {
                    case 'Duplicated':
                        $error = '<span class="barcode_number_error error">'._('Duplicated').'</span>';
                        break;
                    case 'Size':
                        $error = '<span class="barcode_number_error error">'._('Barcode should be 13 digits').'</span>';
                        break;
                    case 'Short_Duplicated':
                        $error = '<span class="barcode_number_error error">'._('Check digit missing, will duplicate').'</span>';
                        break;
                    case 'Checksum_missing':
                        $error = '<span class="barcode_number_error error">'._('Check digit missing').'</span>';
                        break;
                    case 'Checksum':
                        $error = '<span class="barcode_number_error error">'._('Invalid check digit').'</span>';
                        break;
                    default:
                        $error = '<span class="barcode_number_error error">'.$this->data['Part Barcode Number Error'].'</span>';
                }


                return '<i class="fa fa-exclamation-circle error" ></i> '.$error;


            case 'Barcode Number Error with Duplicates Links':

                $error = $this->get('Barcode Number Error');
                if ($error == '') {
                    return '';
                }


                $duplicates = '';


                if (strlen($this->data['Part Barcode Number']) >= 12 and strlen($this->data['Part Barcode Number']) < 14) {
                    $sql = "SELECT `Part SKU`,`Part Reference` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE ? AND `Part SKU`!=?";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                        $this->data['Part Barcode Number'].'%',
                        $this->id
                    ));
                    while ($row = $stmt->fetch()) {
                        $duplicates .= sprintf('<span class=" " style="cursor: pointer" onclick="change_view(\'part/%d\')">%s</span>, ', $row['Part SKU'], $row['Part Reference']);
                    }


                    if ($duplicates != '') {
                        $duplicates = ' ('.preg_replace('/, $/', ')', $duplicates);
                    }
                }

                return $error.$duplicates;

            case 'Next Deliveries Data':

                if ($this->data['Part Next Deliveries Data'] == '') {
                    return array();
                } else {
                    return json_decode($this->data['Part Next Deliveries Data'], true);
                }


            case 'Weight Status':
                switch ($this->data['Part Package Weight Status']) {
                    case 'Missing':
                        $status = '<span class=" error">'._('Missing weight').'</span>';
                        break;
                    case 'Underweight Web':
                        $status = '<span class="error">'.sprintf(
                                _('Probably underweight <b>or</b> %s high'),
                                '<span title="'._('Unit weight shown on website').'"><i class=" fal fa-weight-hanging"></i><i style="font-size: x-small" class="  fal fa-globe"></i></span>'
                            ).'</span>';

                        break;
                    case 'Overweight Web':
                        $status = '<span class="error">'.sprintf(
                                _('Probably overweight <b>or</b> %s low'),
                                '<span title="'._('Unit weight shown on website').'"><i class=" fal fa-weight-hanging"></i><i style="font-size: x-small" class="  fal fa-globe"></i></span>'
                            ).'</span>';

                        break;
                    case 'Underweight Cost':
                        $status = '<span class=" error">'._('Probably underweight').' <i class="margin_left_5 fal fa-box-usd"></i></span>';
                        break;
                    case 'Overweight Cost':
                        $status = '<span class=" error">'._('Probably overweight').' <i class="margin_left_5 fal fa-box-usd"></i></span>';
                        break;
                    case 'OK':
                        $status = '<span class=" success">'._('OK').'</span>';
                        break;
                    default:
                        $status = '<span class=" error">'.$this->data['Part Package Weight Status'].'</span>';
                }

                return $status;

            case 'Carton Weight':
                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Carton Weight');

            case 'Carton CBM':
                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Carton CBM');

            case 'Supplier Part Currency Code':
                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Supplier Part Currency Code');

            case 'Part Supplier Part Unit Cost':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Supplier Part Unit Cost');

            case 'Supplier Part Unit Cost':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Unit Cost');

            case 'Part Supplier Part Unit Extra Cost Percentage':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Supplier Part Unit Extra Cost Percentage');

            case 'Supplier Part Unit Extra Cost Percentage':

                $main_supplier_part = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));

                return $main_supplier_part->get('Unit Extra Cost Percentage');

            default:

                if (preg_match('/No Supplied$/', $key)) {
                    $_key = preg_replace('/ No Supplied$/', '', $key);
                    if (preg_match('/^Part /', $key)) {
                        return $this->data["$_key Required"] - $this->data["$_key Provided"];
                    } else {
                        return number(
                            $this->data["Part $_key Required"] - $this->data["Part $_key Provided"]
                        );
                    }
                }


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/',
                    $key
                )) {
                    $amount = 'Part '.$key;

                    return money(
                        $this->data[$amount],
                        $account->get('Account Currency')
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/',
                    $key
                )) {
                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    return money(
                            $_amount,
                            $account->get('Account Currency'),
                            false,
                            $fraction_digits
                        ).$suffix;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/',
                    $key
                )) {
                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);


                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    return money(
                            $_amount,
                            $account->get('Account Currency'),
                            false,
                            $fraction_digits
                        ).$suffix;
                }

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/',
                    $key
                )) {
                    $amount = 'Part '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired)$/',
                        $key
                    ) or $key == 'Current Stock') {
                    $amount = 'Part '.$key;

                    return number($this->data[$amount]);
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/',
                    $key
                )) {
                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/',
                    $key
                )) {
                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    $_number         = $this->data[$field];

                    return number($_number, $fraction_digits).$suffix;
                }


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Part '.$key, $this->data)) {
                    return $this->data['Part '.$key];
                }
        }

        return false;
    }

    function properties($key)
    {
        return (isset($this->properties[$key]) ? $this->properties[$key] : '');
    }

    function validate_barcode()
    {
        $barcode = $this->data['Part Barcode Number'];

        $error = '';


        if ($barcode != '') {
            if (strlen($barcode) != (12 + 1)) {
                $error = 'Size';
                if (strlen($barcode) == 12) {
                    $error = 'Checksum_missing';
                    $sql   = "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE ? AND `Part SKU`!=?";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                        $barcode.'%',
                        $this->id,
                    ));
                    while ($row = $stmt->fetch()) {
                        $error = 'Short_Duplicated';
                    }
                }
            } else {
                $digits = substr($barcode, 0, 12);

                $digits         = (string)$digits;
                $even_sum       = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
                $even_sum_three = $even_sum * 3;
                $odd_sum        = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
                $total_sum      = $even_sum_three + $odd_sum;
                $next_ten       = (ceil($total_sum / 10)) * 10;
                $check_digit    = $next_ten - $total_sum;

                if ($check_digit != substr($barcode, -1)) {
                    $error = 'Checksum';
                } else {
                    $sql = sprintf(
                        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d',
                        prepare_mysql($barcode),
                        $this->id
                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            /** @var $part Part */
                            $part = get_object('Part', $row['Part SKU']);
                            $part->fast_update(array('Part Barcode Number Error' => 'Duplicated'));
                            $error = 'Duplicated';
                        }
                    }
                }
            }
        }
        $this->fast_update(array('Part Barcode Number Error' => $error));

        global $account;
        $account->update_parts_data();
    }

    function update_weight_status()
    {
        $weight_status_old = $this->get('Part Package Weight Status');

        $max_value  = 0;
        $min_value  = 0;
        $avg_weight = 0;
        $sql        =
            "select avg(`Part Package Weight` ) as average_kg ,avg(`Part Cost`/`Part Package Weight` ) as average_cost_per_kg ,STD(`Part Cost`/`Part Package Weight`)  as sd_cost_per_kg from `Part Dimension` where  `Part Status`='In Use' and  `Part Package Weight`>0 and `Part Cost`>0 ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $max_value  = $row['average_cost_per_kg'] + (3 * $row['sd_cost_per_kg']);
            $min_value  = $row['average_cost_per_kg'] * 0.001;
            $avg_weight = $row['average_kg'];
        }


        $weight_status = 'OK';
        if ($this->get('Part Package Weight') == '' or $this->get('Part Package Weight') == 0) {
            $weight_status = 'Missing';
        } else {
            if ($this->get('Part Package Weight') > 0 and $this->get('Part Unit Weight') > 0) {
                $sko_weight_from_unit_weight = $this->get('Part Unit Weight') * $this->get('Part Units Per Package');
                if ($sko_weight_from_unit_weight > (2 * $this->get('Part Package Weight'))) {
                    $weight_status = 'Underweight Web';
                }
                if ($sko_weight_from_unit_weight < 0.5 * $this->get('Part Package Weight')) {
                    $weight_status = 'Overweight Web';
                }
            }


            if ($this->get('Part Cost') > 0 and $weight_status == 'OK' and $max_value > 0 and $max_value < ($this->get('Part Cost') / $this->get('Part Package Weight')) and $avg_weight * 0.1 > $this->get('Part Package Weight')) {
                //   print $this->get('Reference')." $max_value  ".$this->get('Part Package Weight')." ".$this->get('Part Cost')."  ".( $this->get('Part Cost')/ $this->get('Part Package Weight') )."    \n";

                $weight_status = 'Underweight Cost';
            }

            // print $avg_weight."\n";

            if ($this->get('Part Cost') > 0 and $weight_status == 'OK' and $min_value > 0 and $min_value > ($this->get('Part Cost') / $this->get('Part Package Weight')) and $avg_weight * 10 < $this->get('Part Package Weight')) {
                $weight_status = 'Overweight Cost';
                // print $this->get('Reference')." $min_value  ".$this->get('Part Package Weight')." ".$this->get('Part Cost')."  ".($this->get('Part Cost') / $this->get('Part Package Weight'))."    \n";

            }
        }


        //print $weight_status;


        $this->fast_update(array(
            'Part Package Weight Status' => $weight_status,
        ));


        if ($weight_status_old != $weight_status) {
            $account = get_object('Account', 1);
            $account->update_parts_data();
        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        global $account;

        if ($this->update_asset_field_switcher(
            $field,
            $value,
            $options,
            $metadata
        )) {
            return;
        }

        switch ($field) {
            case 'Part SKOs per Carton':
                $this->update_field($field, $value, $options);

                foreach ($this->get_products('objects') as $product) {
                    $product->editor = $this->editor;
                    $product->updating_packing_data();
                }
                break;

            case 'Part Picking Band Name':
                if ($value != '') {
                    $sql  = "select `Picking Band Key` from `Picking Band Dimension` where `Picking Band Name`=?  and `Picking Band Type`='Picking' ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                        $value
                    ));
                    if ($row = $stmt->fetch()) {
                        $this->update_field_switcher('Part Picking Band Key', $row['Picking Band Key'], $options);
                    } else {
                        $this->error = true;
                        $this->msg   = _('Picking Band not found').' ('.$value.')';
                    }

                    return;
                } else {
                    $this->update_field_switcher('Part Picking Band Key', '', $options);
                }
                break;
            case 'Part Packing Band Name':
                if ($value != '') {
                    $sql  = "select `Picking Band Key` from `Picking Band Dimension` where `Picking Band Name`=?  and `Picking Band Type`='Packing' ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                        $value
                    ));
                    if ($row = $stmt->fetch()) {
                        $this->update_field_switcher('Part Packing Band Key', $row['Picking Band Key'], $options);
                    } else {
                        $this->error = true;
                        $this->msg   = _('Packing Band not found').' ('.$value.')';
                    }

                    return;
                } else {
                    $this->update_field_switcher('Part Packing Band Key', '', $options);
                }
                break;
            case 'Part Picking Band Key':


                $old_value = $this->get('Part Picking Band Key');

                if ($old_value == $value) {
                    return;
                }

                if ($value) {
                    $band = get_object('PickingBand', $value);
                    if ($band->id) {
                        if ($band->get('Picking Band Type') != 'Picking') {
                            $this->error = true;
                            $this->msg   = _('Not a picking band');

                            return;
                        }
                        $this->update_field($field, $value, $options);
                        $this->fast_update([
                            'Part Picking Band Name' => $band->get('Picking Band Name')
                        ]);
                        $band->update_parts();
                    } else {
                        $this->error = true;
                        $this->msg   = _('Band name not found');

                        return;
                    }
                } else {
                    $this->update_field($field, '', $options);
                    $this->fast_update([
                        'Part Picking Band Name' => null
                    ]);
                }

                if ($old_value) {
                    $old_band = get_object('PickingBand', $old_value);
                    $old_band->update_parts();
                }

                break;
            case 'Part Packing Band Key':
                $old_value = $this->get('Part Picking Band Key');

                if ($old_value == $value) {
                    return;
                }


                if ($value) {
                    $band = get_object('PickingBand', $value);
                    if ($band->id) {
                        if ($band->get('Picking Band Type') != 'Packing') {
                            $this->error = true;
                            $this->msg   = _('Not a packing band');

                            return;
                        }
                        $this->update_field($field, $value, $options);
                        $this->fast_update([
                            'Part Packing Band Name' => $band->get('Picking Band Name')
                        ]);
                        $band->update_parts();
                    } else {
                        $this->error = true;
                        $this->msg   = _('Band name not found');

                        return;
                    }
                } else {
                    $this->update_field($field, '', $options);
                    $this->fast_update([
                        'Part Packing Band Name' => null
                    ]);
                }
                if ($old_value) {
                    $old_band = get_object('PickingBand', $old_value);
                    $old_band->update_parts();
                }
                break;
            case 'materials':
            case 'label unit':
            case 'label sko':


                $this->fast_update_json_field('Part Properties', preg_replace('/\s/', '_', $field), $value);

                break;


            case 'Supplier Part Packages Per Carton':
            case 'Supplier Part Carton CBM':
            case 'Supplier Part Carton Barcode':
            case 'Supplier Part Carton Weight':

                $this->updated = false;
                foreach ($this->get_supplier_parts('objects') as $supplier_part) {
                    $supplier_part->editor = $this->editor;
                    $supplier_part->update(array($field => $value), $options);
                    if ($supplier_part->updated) {
                        $this->updated = true;
                    }
                }
                break;

            case 'Part Supplier Part Unit Cost':

                $main_supplier_part         = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));
                $main_supplier_part->editor = $this->editor;
                $main_supplier_part->update(array('Supplier Part Unit Cost' => $value), $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'         => $main_supplier_part->get('Carton Cost'),
                        'SKO_Cost'            => $main_supplier_part->get('SKO Cost'),
                        'Unit_Cost_Amount'    => $main_supplier_part->get('Unit Cost Amount'),
                        'Unit_Delivered_Cost' => $main_supplier_part->get('Unit Delivered Cost'),

                    )
                );


                break;
            case 'Part Supplier Part Unit Expense':

                $main_supplier_part         = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));
                $main_supplier_part->editor = $this->editor;
                $main_supplier_part->update(array('Supplier Part Unit Expense' => $value), $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'         => $main_supplier_part->get('Carton Cost'),
                        'SKO_Cost'            => $main_supplier_part->get('SKO Cost'),
                        'Unit_Cost_Amount'    => $main_supplier_part->get('Unit Cost Amount'),
                        'Unit_Delivered_Cost' => $main_supplier_part->get('Unit Delivered Cost'),


                    )
                );

                $this->other_fields_updated = array(
                    'Part_Supplier_Part_Unit_Expense' => array(
                        'render'          => true,
                        'field'           => 'Part_Supplier_Part_Unit_Expense',
                        'value'           => $main_supplier_part->get('Supplier Part Unit Expense'),
                        'formatted_value' => $main_supplier_part->get('Unit Expense'),
                    ),


                );

                break;

            case 'Part Supplier Part Unit Extra Cost Percentage':

                $main_supplier_part         = get_object('Supplier_Part', $this->get('Part Main Supplier Part Key'));
                $main_supplier_part->editor = $this->editor;
                $main_supplier_part->update(array('Supplier Part Unit Extra Cost Percentage' => $value), $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'         => $main_supplier_part->get('Carton Cost'),
                        'SKO_Cost'            => $main_supplier_part->get('SKO Cost'),
                        'Unit_Cost_Amount'    => $main_supplier_part->get('Unit Cost Amount'),
                        'Unit_Delivered_Cost' => $main_supplier_part->get('Unit Delivered Cost'),

                    )
                );

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'formatted_value' => $main_supplier_part->get('Part Unit Price'),
                        'value'           => $main_supplier_part->get('Part Part Unit Price'),
                    ),


                );

                break;
            case 'Part Cost':

                $this->update_cost();


                break;


            case 'Part Cost in Warehouse':


                $this->update_field($field, $value, $options);


                $this->update_margin();


                foreach ($this->get_locations('part_location_object') as $part_location) {
                    $part_location->update_stock_value();
                }

                foreach ($this->get_products('objects') as $product) {
                    $product->editor = $this->editor;
                    $product->update_cost();
                }


                $hide = array();
                $show = array();

                if ($value == '') {
                    $hide[] = 'Part_Cost_in_Warehouse_info_set_up';
                    $show[] = 'Part_Cost_in_Warehouse_info_not_set_up';
                } else {
                    $show[] = 'Part_Cost_in_Warehouse_info_set_up';
                    $hide[] = 'Part_Cost_in_Warehouse_info_not_set_up';
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Webpage_State_Icon' => $this->get('State Icon'),


                    ),
                    'hide'       => $hide,
                    'show'       => $show
                );


                break;

            case 'Part Barcode Number':

                $old_value = $this->data['Part Barcode Number'];


                $this->update_field($field, $value, $options);
                $this->validate_barcode();

                $updated = $this->updated;


                $sql = sprintf(
                    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d',
                    prepare_mysql($old_value),
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        /**
                         * @var $part \Part
                         */
                        $part = get_object('Part', $row['Part SKU']);
                        $part->validate_barcode();
                    }
                }


                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Number' => $value),
                            $options.' from_part'
                        );
                    }
                }


                $this->updated = $updated;


                if (file_exists('widgets/inventory_alerts.wget.php')) {
                    include_once('widgets/inventory_alerts.wget.php');

                    global $smarty;

                    if (is_object($smarty)) {
                        $_data = get_widget_data(

                            $account->get('Account Parts with Barcode Number Error'),
                            $account->get('Account Parts with Barcode Number'),
                            0,
                            0
                        );


                        $smarty->assign('data', $_data);


                        try {
                            $this->update_metadata = array('parts_with_barcode_errors' => $smarty->fetch('dashboard/inventory.parts_with_barcode_errors.dbard.tpl'));
                        } catch (SmartyException $e) {
                            Sentry\captureException($e);
                        }
                    }
                }


                break;
            case 'Part Barcode Key':


                $this->update_field($field, $value, $options);


                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Key' => $value),
                            $options.' from_part'
                        );
                    }
                }


                $this->updated = $updated;


                break;


            case 'Part Unit Label':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit label missing');

                    return;
                }

                $this->update_field($field, $value, $options);

                break;


            case 'Part Package Description':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Outers (SKO) description');

                    return;
                }

                $this->update_field($field, $value, $options);
                $this->fork_index_elastic_search();
                break;

            case 'Part Reference':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Reference missing');

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Reference`=%s AND `Part SKU`!=%d ',
                    prepare_mysql($value),
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated reference (%s)'),
                                $value
                            );

                            return;
                        }
                    }
                }
                $this->update_field($field, $value, $options);
                $this->fork_index_elastic_search();

                break;
            case 'Part Unit Price':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended price (%s)'),
                        $value
                    );

                    return;
                }
                $this->update_field('Part Unit Price', $value, $options);
                if ($this->data['Part Commercial Value'] == '') {
                    $this->update_margin();
                }


                $this->other_fields_updated = array(

                    'Part_Unit_RRP' => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;


            case 'Part Unit RRP':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended RRP (%s)'),
                        $value
                    );

                    return;
                }


                $this->update_field('Part Unit RRP', $value, $options);


                break;

            case 'Part Units Per Package':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Units per SKO missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid units per SKO (%s)'),
                        $value
                    );

                    return;
                }

                $this->update_field('Part Units Per Package', $value, $options);

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    foreach (
                        $this->get_supplier_parts('objects') as $supplier_part
                    ) {
                        $supplier_part->update_historic_object();
                    }
                }

                if ($this->data['Part Commercial Value'] == '') {
                    $this->update_margin();
                }


                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'value'           => $this->get('Part Unit Price'),
                        'formatted_value' => $this->get('Unit Price'),
                    ),
                    'Part_Unit_RRP'   => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;

            case 'Part Family Code':
            case 'Part Family Category Code':
                $account = new Account($this->db);
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Family's code missing");

                    return;
                }

                include_once 'class.Category.php';


                $root_category = new Category(
                    $account->get('Account Part Family Category Key')
                );
                if ($root_category->id) {
                    $root_category->editor = $this->editor;
                    $family                = $root_category->create_category(array('Category Code' => $value));
                    if ($family->id) {
                        $this->update_field_switcher(
                            'Part Family Category Key',
                            $family->id,
                            $options
                        );
                    } else {
                        $this->error = true;
                        $this->msg   = _("Can't create family");

                        return;
                    }
                } else {
                    $this->error = true;
                    $this->msg   = _("Part's families not configured");

                    return;
                }


                break;
            case 'Part Family Category Key';

                $account = new Account($this->db);
                include_once 'class.Category.php';


                if ($value != '') {
                    $category = new Category($value);
                    if ($category->id and $category->get('Category Root Key') == $account->get('Account Part Family Category Key')) {
                        $category->associate_subject($this->id);
                    } else {
                        $this->error = true;
                        $this->msg   = 'wrong category';

                        return;
                    }
                } else {
                    if ($this->data['Part Family Category Key'] != '') {
                        $category = new Category(
                            $this->data['Part Family Category Key']
                        );

                        if ($category->id) {
                            $category->disassociate_subject($this->id);
                        }
                    }
                }
                $this->update_field(
                    'Part Family Category Key',
                    $value,
                    'no_history'
                );


                $categories = '';
                foreach ($this->get_category_data() as $item) {
                    $categories .= sprintf(
                        '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>',
                        $item['category_key'],
                        $item['label'],
                        $item['code']

                    );
                }
                $this->update_metadata = array(
                    'class_html' => array(
                        'Categories' => $categories,

                    )
                );


                break;
            case 'Part Materials':
                include_once 'utils/parse_materials.php';


                $materials_to_update = array();

                $sql = sprintf(
                    'SELECT `Material Key` FROM `Part Material Bridge` WHERE `Part SKU`=%d',
                    $this->id
                );
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $materials_to_update[$row['Material Key']] = true;
                    }
                }


                if ($value == '') {
                    $materials = '';


                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ",
                        $this->id
                    );
                    $this->db->exec($sql);
                } else {
                    $materials_data = parse_materials($value, $this->editor);

                    // print_r($materials_data);

                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ",
                        $this->id
                    );

                    $this->db->exec($sql);

                    foreach ($materials_data as $material_data) {
                        if ($material_data['id'] > 0) {
                            $sql = sprintf(
                                "INSERT INTO `Part Material Bridge` (`Part SKU`, `Material Key`, `Ratio`, `May Contain`) VALUES (%d, %d, %s, %s) ",
                                $this->id,
                                $material_data['id'],
                                prepare_mysql($material_data['ratio']),
                                prepare_mysql($material_data['may_contain'])

                            );
                            $this->db->exec($sql);

                            if (isset($materials_to_update[$material_data['id']])) {
                                $materials_to_update[$material_data['id']] = false;
                            } else {
                                $materials_to_update[$material_data['id']] = true;
                            }
                        }
                    }


                    $materials = json_encode($materials_data);
                }


                foreach ($materials_to_update as $material_key => $update) {
                    if ($update) {
                        /**
                         * @var $material Material
                         */
                        $material = get_object('Material', $material_key);
                        $material->update_stats();
                    }
                }


                $this->update_field('Part Materials', $materials, $options);
                $updated = $this->updated;

                $this->update(['materials' => $this->get('Part Materials')], 'no_history');


                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Materials' => $value),
                            $options.' from_part'
                        );
                    }
                }
                $this->fork_index_elastic_search();

                $this->updated = $updated;
                break;

            case 'Part Package Dimensions':
            case 'Part Unit Dimensions':


                include_once 'utils/parse_natural_language.php';

                $tag = preg_replace('/ Dimensions$/', '', $field);

                if ($value == '') {
                    $dim = '';
                    $vol = '';
                } else {
                    $dim = parse_dimensions($value);
                    if ($dim == '') {
                        $this->error = true;
                        $this->msg   = sprintf(
                            _("Dimensions can't be parsed (%s)"),
                            $value
                        );

                        return;
                    }
                    $_tmp = json_decode($dim, true);
                    $vol  = $_tmp['vol'];
                }

                $this->update_field($tag.' Dimensions', $dim, $options);
                $updated = $this->updated;
                $this->update_field($tag.' Volume', $vol, 'no_history');
                //$this->update_linked_products($field, $value, $options, $metadata);

                if ($field == 'Part Unit Dimensions') {
                    foreach ($this->get_products('objects') as $product) {
                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array('Product Unit Dimensions' => $value),
                                $options.' from_part'
                            );
                        }
                    }
                }
                $this->updated = $updated;

                break;
            case 'Part Package Weight':
            case 'Part Unit Weight':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid weight (%s)'), $value);

                    return;
                }


                $tag  = preg_replace('/ Weight$/', '', $field);
                $tag2 = preg_replace('/^Part /', '', $tag);
                $tag3 = preg_replace('/ /', '_', $tag);

                $this->update_field($field, $value, $options);
                $updated                    = $this->updated;
                $this->other_fields_updated = array(
                    $tag3.'_Dimensions' => array(
                        'field'           => $tag3.'_Dimensions',
                        'render'          => true,
                        'value'           => $this->get($tag.' Dimensions'),
                        'formatted_value' => $this->get($tag2.' Dimensions'),


                    )
                );
                //$this->update_linked_products($field, $value, $options, $metadata);


                if ($field == 'Part Package Weight') {
                    $purchase_order_keys = array();
                    $sql                 = sprintf(
                        "SELECT `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Ordering Units`,`Supplier Part Packages Per Carton` FROM `Purchase Order Transaction Fact`POTF 
                            LEFT JOIN `Supplier Part Dimension` S ON (POTF.`Supplier Part Key`=S.`Supplier Part Key`)  
                            LEFT JOIN `Part Dimension` P ON (S.`Supplier Part Part SKU`=P.`Part SKU`)  

                            WHERE `Supplier Part Part SKU`=%d  AND `Purchase Order Weight` IS NULL AND `Purchase Order Transaction State` IN ('InProcess','Submitted')  ",
                        $this->id
                    );
                    //print $sql;
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            //todo review if this is really necessary

                            $purchase_order_keys[$row['Purchase Order Key']] = $row['Purchase Order Key'];

                            if ($value != '') {
                                $sql = sprintf(
                                    'UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Weight`=%f WHERE `Purchase Order Transaction Fact Key`=%d',
                                    $this->get('Part Package Weight') * $row['Purchase Order Ordering Units'] / $row['Supplier Part Packages Per Carton'],
                                    $row['Purchase Order Transaction Fact Key']
                                );
                            } else {
                                $sql = sprintf(
                                    'UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Weight`=NULL WHERE `Purchase Order Transaction Fact Key`=%d',
                                    $row['Purchase Order Transaction Fact Key']
                                );
                            }


                            $this->db->exec($sql);
                        }

                        foreach ($purchase_order_keys as $purchase_order_key) {
                            $purchase_order = get_object('PurchaseOrder', $purchase_order_key);
                            $purchase_order->update_totals();
                        }
                    }

                    foreach ($this->get_products('objects') as $product) {
                        $product->update_weight();
                    }
                }

                if ($field == 'Part Unit Weight') {
                    foreach ($this->get_products('objects') as $product) {
                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array(
                                    'Product Unit Weight' => $this->get(
                                        'Part Unit Weight'
                                    )
                                ),
                                $options.' from_part'
                            );
                        }
                    }
                }
                $this->updated = $updated;

                $this->update_weight_status();


                if ($field == 'Part Package Weight') {
                    $this->update_metadata['part_weight_status'] = $this->get('Weight Status');


                    if (file_exists('widgets/inventory_alerts.wget.php')) {
                        include_once('widgets/inventory_alerts.wget.php');
                        global $smarty;

                        $account = get_object('Account', 1);
                        $account->load_acc_data();


                        if (is_object($smarty)) {
                            $all_active = $account->get('Account Active Parts Number') + $account->get('Account In Process Parts Number') + $account->get('Account Discontinuing Parts Number');


                            $_data = get_widget_data(

                                $account->get('Account Active Parts with SKO Invalid Weight'),
                                $all_active,
                                0,
                                0

                            );
                            if ($_data['ok']) {
                                $smarty->assign('data', $_data);
                                try {
                                    $this->update_metadata['parts_with_weight_error'] = $smarty->fetch('dashboard/inventory.parts_with_weight_errors.dbard.tpl');
                                } catch (SmartyException $e) {
                                    Sentry\captureException($e);
                                }
                            }
                        }
                    }
                }
                break;
            case('Part Tariff Code'):


                $this->update_field($field, $value, $options);
                $updated = $this->updated;

                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Tariff Code' => $this->get('Part Tariff Code')),
                            $options.' from_part'
                        );
                    }
                }

                $this->updated = $updated;

                break;
            case('Part HTSUS Code'):


                $this->update_field($field, $value, $options);
                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product HTSUS Code' => $this->get('Part HTSUS Code')),
                            $options.' from_part'
                        );
                    }
                }

                $this->updated = $updated;

                break;
            case 'Part SKO Barcode':


                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part SKO Barcode`=%s AND `Part SKU`!=%d ',
                    prepare_mysql($value),
                    $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated SKO barcode (%s)'),
                                $value
                            );

                            return;
                        }
                    }
                }


                $this->update_field($field, $value, $options);

                $account->update_parts_data();

                if (file_exists('widgets/inventory_alerts.wget.php')) {
                    include_once('widgets/inventory_alerts.wget.php');
                    global $smarty;

                    if (is_object($smarty)) {
                        $all_active = $account->get('Account Active Parts Number') + $account->get('Account In Process Parts Number') + $account->get('Account Discontinuing Parts Number');

                        $data = get_widget_data(

                            $all_active - $account->get('Account Active Parts with SKO Barcode Number'),
                            $all_active,
                            0,
                            0

                        );


                        $smarty->assign('data', $data);


                        try {
                            $this->update_metadata = array('parts_with_no_sko_barcode' => $smarty->fetch('dashboard/inventory.parts_with_no_sko_barcode.dbard.tpl'));
                        } catch (SmartyException $e) {
                            Sentry\captureException($e);
                        }
                    }
                }

                $this->fork_index_elastic_search();
                break;


            case 'Part UN Number':
            case 'Part UN Class':
            case 'Part Packing Group':
            case 'Part Proper Shipping Name':
            case 'Part Hazard Identification Number':
            case('Part CPNP Number'):
            case('Part UFI'):
            case('Part Duty Rate'):


                if ($field == 'Part Duty Rate' and is_numeric($value)) {
                    $value = percentage($value, 1);
                }


                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                //$this->update_linked_products($field, $value, $options, $metadata);


                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /',
                            'Product ',
                            $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)),
                            $options.' from_part'
                        );
                    }
                }


                $this->updated = $updated;
                break;

            case 'Part Origin Country Code':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Country of origin missing");

                    return;
                }


                include_once 'class.Country.php';
                $country = new Country('find', $value);
                if ($country->get('Country Code') == 'UNK') {
                    $this->error = true;
                    $this->msg   = sprintf(_("Country not found (%s)"), $value);

                    return;
                }


                $this->update_field(
                    $field,
                    $country->get('Country Code'),
                    $options
                );


                $updated = $this->updated;

                foreach ($this->get_products('objects') as $product) {
                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /',
                            'Product ',
                            $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)),
                            $options.' from_part'
                        );
                    }
                }


                $this->updated = $updated;
                break;

            case('Part Status'):


                if (!in_array($value, array(
                    'In Use',
                    'Not In Use',
                    'Discontinuing',
                    'In Process'
                ))) {
                    $this->error = true;
                    $this->msg   = _('Invalid part status').' ('.$value.')';

                    return;
                }

                /*
                                if ($this->get('Part Status') == 'In Process' and $value = 'In Use' and !($this->get('Part Current On Hand Stock') > 0)
                                ) {

                                    $this->error = true;
                                    $this->msg   = _("Part status can't be set to active until stock is set up");

                                    return;

                                }
                */

                if ($value == 'Not In Use') {
                    if ($this->get('Part Current On Hand Stock') > 0) {
                        $value = 'Discontinuing';
                    }
                }


                $this->update_status($value, $options);
                $this->fork_index_elastic_search();
                break;

            case 'Part Symbol':

                if ($value == 'none') {
                    $value = '';
                }
                $this->update_field($field, $value, $options);
                break;


            case 'Part Main Supplier Part Key':
                $old_value = $this->get('Part Main Supplier Part Key');

                if ($old_value == $value) {
                    return;
                }

                $supplier_part = get_object('Supplier Part', $value);
                if (!$supplier_part->id) {
                    $this->error = true;
                    $this->msg   = 'invalid supplier part key';
                }
                if ($supplier_part->get('Supplier Part Part SKU') != $this->id) {
                    $this->error = true;
                    $this->msg   = 'wrong supplier part key';
                }


                $supplier = get_object('Supplier', $supplier_part->get('Supplier Part Supplier Key'));
                $this->fast_update(array($field => $supplier_part->id));


                $history_data = array(
                    'History Abstract' => sprintf(
                        _("Part main supplier set to %s"),
                        '<span class="link" onClick="change_view(\'supplier/'.$supplier->id.'/part/'.$supplier_part->id.'\')">'.$supplier_part->get(
                            'Supplier Part Reference'
                        ).'</span> (<span class="link" onClick="change_view(\'supplier/'.$supplier->id.'\')">'.$supplier->get('Code').'</span>)'
                    ),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );

                $this->add_subject_history(
                    $history_data,
                    true,
                    'No',
                    'Changes',
                    $this->get_object_name(),
                    $this->id
                );


                $this->update_field('Part SKOs per Carton', $supplier_part->get('Supplier Part Packages Per Carton'), $options);

                foreach ($this->get_products('objects') as $product) {
                    $product->editor = $this->editor;
                    $product->updating_packing_data();
                }


                break;


            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                } elseif (array_key_exists($field, $this->base_data('Part Data'))) {
                    $this->update_table_field($field, $value, $options, 'Part Data', 'Part Data', $this->id);
                }
        }
    }

    function update_cost()
    {
        $account = new Account($this->db);

        $supplier_parts = $this->get_supplier_parts('objects');

        $cost_available    = array();
        $cost_no_available = array();
        $cost_discontinued = array();


        foreach ($supplier_parts as $supplier_part) {
            if ($supplier_part->get('Supplier Part Currency Code') != $account->get('Account Currency')) {
                include_once 'utils/currency_functions.php';
                $exchange = currency_conversion(
                    $this->db,
                    $account->get('Account Currency'),
                    $supplier_part->get('Supplier Part Currency Code'),
                    '- 1 hour'
                );
            } else {
                $exchange = 1;
            }

            $_cost = ($supplier_part->get('Supplier Part Unit Cost') + $supplier_part->get('Supplier Part Unit Extra Cost')) / $exchange;


            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $cost_available[] = $_cost;
            } elseif ($supplier_part->get('Supplier Part Status') == 'NoAvailable') {
                $cost_no_available[] = $_cost;
            } elseif ($supplier_part->get('Supplier Part Status') == 'Discontinued') {
                $cost_discontinued[] = $_cost;
            }
        }


        $cost     = 0;
        $cost_set = false;


        if (count($cost_available) > 0) {
            $cost     = array_sum($cost_available) / count($cost_available);
            $cost_set = true;
        }

        if (!$cost_set and count($cost_no_available) > 0) {
            $cost     = array_sum($cost_no_available) / count(
                    $cost_no_available
                );
            $cost_set = true;
        }

        if (!$cost_set and count($cost_discontinued) > 0) {
            $cost     = array_sum($cost_discontinued) / count(
                    $cost_discontinued
                );
            $cost_set = true;
        }


        if ($cost_set) {
            $cost = $cost * $this->data['Part Units Per Package'];
        }
        $this->update_field('Part Number Supplier Parts', count($supplier_parts), 'no_history');


        $this->update_field('Part Cost', $cost, 'no_history');


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_cost();
        }


        $this->update_margin();
    }

    function update_margin()
    {
        if ($this->data['Part Cost in Warehouse'] == '') {
            $cost = $this->data['Part Cost'];
        } else {
            $cost = $this->data['Part Cost in Warehouse'];
        }


        if ($this->data['Part Commercial Value'] == '') {
            $selling_price = $this->data['Part Unit Price'] * $this->data['Part Units Per Package'];
        } else {
            $selling_price = $this->data['Part Commercial Value'];
        }

        if ($selling_price == 0) {
            $margin = 0;
        } else {
            $margin = ($selling_price - $cost) / $selling_price;
        }

        $this->update_field_switcher('Part Margin', $margin, 'no_history');
    }

    function get_locations($scope = 'keys', $_order = '', $exclude_unknown = false): array
    {
        if ($scope == 'objects') {
            include_once 'class.Location.php';
        } elseif ($scope == 'part_location_object') {
            include_once 'class.PartLocation.php';
        }


        if ($_order == 'stock') {
            $_order = '`Quantity On Hand` desc';
        } elseif ($_order == 'can_pick') {
            $_order = '`Can Pick`,`Location File As` ';
        } else {
            $_order = '`Location File As` ';
        }


        if ($exclude_unknown) {
            $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
            $where     = sprintf('and PL.`Location Key`!=%d  ', $warehouse->get('Warehouse Unknown Location Key'));
        } else {
            $where = '';
        }


        $sql = sprintf(
            "SELECT `Location Place`,PL.`Location Key`,`Location Code`,`Quantity On Hand`,`Part Location Note`,`Location Warehouse Key`,`Part SKU`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Location Pipeline`,
        `Can Pick`, datediff(CURDATE(), `Part Location Last Audit`) AS days_last_audit,`Part Location Last Audit` 
        FROM `Part Location Dimension` PL LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PL.`Location Key`)  WHERE `Part SKU`=%d  %s
        ORDER BY %s",
            $this->id,
            $where,
            $_order
        );


        $part_locations = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'keys') {
                    $part_locations[$row['Location Key']] = $row['Location Key'];
                } elseif ($scope == 'objects') {
                    $part_locations[$row['Location Key']] = new Location($row['Location Key']);
                } elseif ($scope == 'part_location_object') {
                    $part_locations[$row['Location Key']] = new  PartLocation($this->id.'_'.$row['Location Key']);
                } else {
                    /*
                    $picking_location_icon = sprintf(
                        '<i onclick="set_as_picking_location('.$this->id.','.$row['Location Key'].')" class="fa fa-fw fa-shopping-basket %s"  title="%s" ></i></span>',
                        ($row['Can Pick'] == 'Yes' ? '' : 'super_discreet_on_hover button'),
                        ($row['Can Pick'] == 'Yes' ? _('Picking location') : _('Set as picking location'))

                    );
                    */

                    $part_locations[] = array(
                        'formatted_stock' => number($row['Quantity On Hand'], 3),
                        'stock'           => $row['Quantity On Hand'],
                        'warehouse_key'   => $row['Location Warehouse Key'],

                        'location_key' => $row['Location Key'],
                        'part_sku'     => $row['Part SKU'],

                        'location_code'          => $row['Location Code'],
                        'note'                   => $row['Part Location Note'],
                        'location_external_icon' => ($row['Location Place'] == 'External' ? ' <i style="color:tomato" class="small fal fa-garage-car"></i>' : ''),

                        //'picking_location_icon' => $picking_location_icon,
                        'formatted_min_qty'      => ($row['Minimum Quantity'] != '' ? $row['Minimum Quantity'] : '?'),
                        'formatted_max_qty'      => ($row['Maximum Quantity'] != '' ? $row['Maximum Quantity'] : '?'),
                        'formatted_move_qty'     => ($row['Moving Quantity'] != '' ? $row['Moving Quantity'] : '?'),
                        'min_qty'                => $row['Minimum Quantity'],
                        'max_qty'                => $row['Maximum Quantity'],
                        'move_qty'               => $row['Moving Quantity'],

                        'can_pick'        => $row['Can Pick'],
                        'is_pipeline'     => $row['Location Pipeline'] == 'Yes',
                        'label'           => ($row['Can Pick'] == 'Yes' ? _('Picking location') : _('Set as picking location')),
                        'days_last_audit' => ($row['days_last_audit'] == ''
                            ? '<span title="'._('Never been audited').'">-</span> <i class="far fa-clock padding_right_10" ></i> '
                            : sprintf(
                                '<span title="%s">%s</span>',
                                sprintf(_('Last audit %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Part Location Last Audit'].' +0:00')), $row['Part Location Last Audit']),
                                ($row['days_last_audit'] > 999 ? '<span class="error">+999</span>' : number($row['days_last_audit']))
                            ).' <i class="far fa-clock padding_right_10" ></i>')


                    );
                }
            }
        }


        return $part_locations;
    }

    function get_category_data(): array
    {
        $type = 'Part';

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id,
            prepare_mysql($type)
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=?";

                $stmt2 = $this->db->prepare($sql);
                $stmt2->execute(array(
                    $row['Category Root Key']
                ));
                if ($row2 = $stmt2->fetch()) {
                    $root_label = $row2['Category Label'];
                    $root_code  = $row2['Category Code'];
                } else {
                    $root_label = '';
                    $root_code  = '';
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'   => $root_label,
                    'root_code'    => $root_code,
                    'root_key'     => $row['Category Root Key'],
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'value'        => $value,
                    'category_key' => $row['Category Key']
                );
            }
        }


        return $category_data;
    }

    function update_status($value, $options = '', $force = false)
    {
        $old_value = $this->get('Part Status');

        if ($value == 'Not In Use' and ($this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process']) > 0) {
            $value = 'Discontinuing';
        }


        if ($value == $this->get('Part Status') and !$force) {
            return;
        }

        $this->update_field('Part Status', $value, $options);


        if ($old_value != $value) {
            if ($value == 'Discontinuing') {
                $this->discontinue_trigger();
            } elseif ($value == 'Not In Use') {
                foreach ($this->get_locations('part_location_object') as $part_location) {
                    $part_location->disassociate();
                }

                $this->update_stock();


                $this->update(
                    array('Part Valid To' => gmdate("Y-m-d H:i:s")),
                    'no_history'
                );


                $this->get_data('sku', $this->id);
            }

            $this->update_weight_status();

            include_once 'utils/new_fork.php';
            $account = get_object('Account', 1);


            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'     => 'update_part_status',
                    'part_sku' => $this->id,
                    'editor'   => $this->editor
                ),
                $account->get('Account Code')
            );

            $this->fork_index_elastic_search();
        }
    }

    function discontinue_trigger()
    {
        if ($this->trigger_discontinued) {
            if ($this->get('Part Status') == 'Discontinuing' and ($this->data['Part Current On Hand Stock'] <= 0 and $this->data['Part Current Stock In Process'] == 0)) {
                $this->update_status('Not In Use');

                return;
            }
            if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] > 0 or $this->data['Part Current Stock In Process'] > 0)) {
                $this->update_status('Discontinuing');

                return;
            }
            if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] < 0)) {
                $this->update_status('Not In Use', '', true);
            }
        }
    }

    function update_stock($force_update_part_products_availability = false)
    {
        $old_value             = $this->data['Part Current Value'];
        $old_stock_in_progress = $this->data['Part Current Stock In Process'];
        $old_stock_picked      = $this->data['Part Current Stock Picked'];
        $old_stock_on_hand     = $this->data['Part Current On Hand Stock'];
        //$old_stock_paid        = $this->data['Part Current Stock Ordered Paid'];


        $picked   = 0;
        $required = 0;

        $sql  = "SELECT sum(`Picked`) AS picked, sum(`Required`+`Given`) AS required FROM `Inventory Transaction Fact` WHERE `Part SKU`=? AND `Inventory Transaction Type`='Order In Process'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            $picked   = round($row['picked'], 3);
            $required = round($row['required'], 3);
        }


        //$required+=$this->data['Part Current Stock Ordered Paid'];

        $stock_data = $this->get_current_stock();


        $stock          = $stock_data[0];
        $value          = $stock_data[1];
        $stock_external = $stock_data[3];


        $this->fast_update(array(
            'Part Current Value'                  => $value,
            'Part Current Stock In Process'       => $required - $picked,
            'Part Current Stock Picked'           => $picked,
            'Part Current On Hand Stock'          => $stock,
            'Part Current On Hand Stock External' => $stock_external

        ));
        /*
                print "Stock $stock Picked  $picked\n";
                print "b* $old_value   ** ".$this->data['Part Current Value']."  \n"   ;
                print "b* $old_stock_in_progress   ** ".$this->data['Part Current Stock In Process']."  \n"   ;
                print "b* $old_stock_picked   ** ".$this->data['Part Current Stock Picked']."  \n"   ;
                print "b* $old_stock_on_hand   ** ".$this->data['Part Current On Hand Stock']."  \n"   ;
        */

        if ($force_update_part_products_availability or ($old_value != $this->data['Part Current Value'] or $old_stock_in_progress != $this->data['Part Current Stock In Process'] or $old_stock_picked != $this->data['Part Current Stock Picked'] or $old_stock_on_hand
                != $this->data['Part Current On Hand Stock'])

        ) {
            $this->activate();
            $this->discontinue_trigger();


            $this->update_stock_status();

            //todo find a way do it more efficient
            $account = get_object('Account', 1);
            if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
                $this->update_stock_run();
            }


            include_once 'utils/new_fork.php';
            /*
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'     => 'update_part_products_availability',
                    'part_sku' => $this->id,
                    'editor'   => $this->editor
                ),
                DNS_ACCOUNT_CODE,
                'Low'
            );
            */
            new_housekeeping_fork(
                'au_update_part_products_availability',
                array(
                    'part_sku' => $this->id,
                    'editor'   => $this->editor
                ),
                DNS_ACCOUNT_CODE
            );
        }
    }

    function get_current_stock(): array
    {
        $stock      = 0;
        $value      = 0;
        $in_process = 0;
        $external   = 0;


        $sql = "SELECT sum(`Quantity On Hand`) AS stock , sum(`Quantity In Process`) AS in_process , sum(`Stock Value`) AS value , sum(if(`Location Place`='External',`Quantity On Hand`,0)) as external
            
            FROM `Part Location Dimension` PL  left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`)   WHERE `Part SKU`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        while ($row = $stmt->fetch()) {
            $stock      = round($row['stock'], 3);
            $in_process = round($row['in_process'], 3);
            $value      = $row['value'];
            $external   = round($row['external'], 3);
        }


        return array(
            $stock,
            $value,
            $in_process,
            $external
        );
    }

    public function activate()
    {
        if ($this->get('Part Status') == 'In Process') {
            if ($this->data['Part Number Active Products'] > 0 and $this->get('Part Current On Hand Stock') > 0) {
                $this->update(
                    array(
                        'Part Status'      => 'In Use',
                        'Part Active From' => gmdate('Y-m-d H:i:s')
                    ),
                    'no_history'
                );

                return;
            }

            $sql = sprintf(
                'select count(*) as num, min(`Date`) as date  from `Inventory Transaction Fact` ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) where `Inventory Transaction Section` in ("In","Out")  and  ITF.`Part SKU`=%d',
                $this->id
            );

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            if ($row = $stmt->fetch()) {
                $number = $row['num'];
                $date   = $row['date'];

                if ($number > 0) {


                    $this->update(
                        array(
                            'Part Status'      => 'In Use',
                            'Part Active From' => $date
                        ),
                        'no_history'
                    );

                }
            }
        }
    }

    function update_stock_status()
    {
        //print 'Delivery days '.$this->data['Part Delivery Days']."\n";
        //print 'Part Days Available Forecast '.$this->data['Part Days Available Forecast']."\n";

        $old_value = $this->get('Part Stock Status');

        if ($this->data['Part Current On Hand Stock'] < 0) {
            $stock_state = 'Error';
        } elseif ($this->data['Part Current On Hand Stock'] == 0) {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Optimal';
            } else {
                $stock_state = 'Out_of_Stock';
            }
        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days']) {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Critical';
            }
        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days'] + 7) {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Low';
            }
        } elseif ($this->data['Part Days Available Forecast'] >= $this->data['Part Excess Availability Days Limit']) {
            $stock_state = 'Surplus';
        } else {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Optimal';
            }
        }

        //print $stock_state;


        $this->fast_update(array(
            'Part Stock Status' => $stock_state
        ));


        if ($stock_state != $old_value) {
            $account = get_object('Account', 1);


            $account->update_parts_data();
            $account->update_active_parts_stock_data();
        }
    }


    function update_stock_run()
    {
        $account = get_object('Account', 1);


        $running_stock = 0;
        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
            $running_stock_value  = 0;
            $current_cost_per_sko = 0;
            $booked_in            = false;


            $units_factor = $this->get('Part Units Per Package');

            $unit_decimals = 3;
            $threshold     = 0.1;


            $sql = "SELECT `Date`,`Note`,`Running Stock`,`Inventory Transaction Key`, `Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Inventory Transaction Type`,`Location Key`,`Inventory Transaction Section`,`Running Cost per SKO`,`Running Stock Value`,`Running Cost per SKO`
                FROM `Inventory Transaction Fact` WHERE `Part SKU`=?    ORDER BY `Date`   ";


            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($this->id));
            $data_to_update = array();
            while ($row = $stmt->fetch()) {
                // print_r($row);


                if ($row['Inventory Transaction Section'] == 'In' and $row['Inventory Transaction Type'] == 'In') {
                    $type      = 'in';
                    $booked_in = true;
                    $amount    = $row['Inventory Transaction Amount'];
                } else {
                    $amount = 0;
                    $type   = 'other';

                    if ($row['Inventory Transaction Quantity'] != 0) {
                        if (!$booked_in) {
                            $sql   =
                                "select `Inventory Transaction Amount`/`Inventory Transaction Quantity` as cost_per_sko  FROM `Inventory Transaction Fact` WHERE `Part SKU`=? and `Inventory Transaction Section`='In' and `Inventory Transaction Type`='In' order by `Date` limit 1 ";
                            $stmt2 = $this->db->prepare($sql);
                            $stmt2->execute(array($this->id));
                            if ($row2 = $stmt2->fetch()) {
                                $current_cost_per_sko = $row2['cost_per_sko'];
                                $booked_in            = true;
                            }
                        }


                        $amount = $row['Inventory Transaction Quantity'] * $current_cost_per_sko;
                        $sql    = "UPDATE `Inventory Transaction Fact` SET `Inventory Transaction Amount`=?  WHERE `Inventory Transaction Key`=? ";
                        /*
                        print "$sql\n";
                        print_r(array(
                                    $row['Inventory Transaction Quantity'],
                                    $current_cost_per_sko,
                                ));
                        */
                        $this->db->prepare($sql)->execute(array(
                            $amount,
                            $row['Inventory Transaction Key']
                        ));
                        $this->db->exec($sql);
                    }
                }

                $old_running_stock = $running_stock;

                $running_stock = $running_stock + $row['Inventory Transaction Quantity'];

                // print "!!!!!!!!  $running_stock_value  $amount \n";

                $running_stock_value = $running_stock_value + $amount;


                if ($old_running_stock > 0 and $running_stock > $threshold) {
                    $current_cost_per_sko = $running_stock_value / $running_stock;
                    //  print "+++++++  $amount $running_stock_value  $running_stock \n";

                } elseif ($type == 'in' and $row['Inventory Transaction Quantity'] > 0) {
                    $current_cost_per_sko = $row['Inventory Transaction Amount'] / $row['Inventory Transaction Quantity'];
                    //  print "AAAAAA  $current_cost_per_sko\n";

                    if ($old_running_stock < 0) {
                        $running_stock_value = $running_stock * $current_cost_per_sko;
                    }
                }


                $running_stock_units = round($units_factor * $running_stock, $unit_decimals);

                $running_stock = $running_stock_units / $units_factor;


                //print "Qty $running_stock  >> $units_factor  ($running_stock_units)u | $ $running_stock_value  ( $current_cost_per_sko )  Val/U  ".($running_stock_value/$running_stock_units)."  ".($units_factor*$running_stock_value/$running_stock_units)."     \n";


                $data_to_update[] = array(
                    $running_stock,
                    $running_stock_value,
                    $current_cost_per_sko,
                    $row['Inventory Transaction Key']
                );
            }

            $sql  = "UPDATE `Inventory Transaction Fact` SET `Running Stock`=?,`Running Stock Value`=?,`Running Cost per SKO`=?  WHERE `Inventory Transaction Key`=?";
            $stmt = $this->db->prepare($sql);


            foreach ($data_to_update as $_data) {
                $stmt->execute($_data);
            }


            $this->update_field_switcher('Part Cost in Warehouse', $current_cost_per_sko, 'no_history');
        } else {
            $sql = sprintf(
                'SELECT    ( select `ITF POTF Costing Done ITF Key` from `ITF POTF Costing Done Bridge` where `ITF POTF Costing Done ITF Key`=`Inventory Transaction Key`   ) as costing,  `Inventory Transaction Record Type`,`Date`,`Note`,`Running Stock`,`Inventory Transaction Key`, `Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Inventory Transaction Type`,`Location Key`,`Inventory Transaction Section`,`Running Cost per SKO`,`Running Stock Value`,`Running Cost per SKO` 
                FROM `Inventory Transaction Fact`   WHERE `Part SKU`=%d  ORDER BY `Date` ,`Inventory Transaction Key`  ',
                $this->id
            );


            $costing = false;

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['costing'] == '' and !$costing) {
                        $cost_per_sko = $this->get('Part Cost');
                    } elseif ($row['costing'] > 0 and $row['Inventory Transaction Quantity'] > 0 and $row['Inventory Transaction Amount'] > 0) {
                        $costing      = true;
                        $cost_per_sko = $row['Inventory Transaction Amount'] / $row['Inventory Transaction Quantity'];
                    }


                    if ($row['Inventory Transaction Record Type'] == 'Movement') {
                        $running_stock = $running_stock + $row['Inventory Transaction Quantity'];
                    }


                    $running_stock_value = $running_stock * $cost_per_sko;


                    $sql = sprintf(
                        'UPDATE `Inventory Transaction Fact` SET `Running Stock`=%f,`Running Stock Value`=%f,`Running Cost per SKO`=%s  WHERE `Inventory Transaction Key`=%d ',
                        $running_stock,
                        $running_stock_value,
                        prepare_mysql($cost_per_sko),
                        $row['Inventory Transaction Key']
                    );
                    //print "$sql\n";
                    $this->db->exec($sql);

                    //        " where ( `Inventory Transaction Section`='In' or ( `Inventory Transaction Type`='Adjust' and `Inventory Transaction Quantity`>0 and `Location Key`>1 )  )  and ITF.`Part SKU`=%d", $parameters['parent_key']


                    $sql = sprintf(
                        'UPDATE `Inventory Transaction Fact` SET `Inventory Transaction Amount`=%f  WHERE `Inventory Transaction Key`=%d ',
                        $cost_per_sko * $row['Inventory Transaction Quantity'],
                        $row['Inventory Transaction Key']
                    );

                    $this->db->exec($sql);
                }
            }
        }
    }


    function get_number_real_locations($unknown_location_key = '')
    {
        if (!$unknown_location_key) {
            $warehouse            = get_object('Warehouse', $_SESSION['current_warehouse']);
            $unknown_location_key = $warehouse->get('Warehouse Unknown Location Key');
        }


        $number_real_locations = 0;
        $sql                   = sprintf(
            "SELECT count(*) as num  FROM `Part Location Dimension` WHERE `Part SKU`=%d  and `Location Key`!=%d",
            $this->id,
            $unknown_location_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_real_locations = $row['num'];
            }
        }

        return $number_real_locations;
    }

    function update_available_forecast()
    {
        $this->load_acc_data();


        if ($this->data['Part Current On Hand Stock'] == '' or $this->data['Part Current On Hand Stock'] < 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = '?';
        } elseif ($this->data['Part Current On Hand Stock'] == 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = 0;
        } else {
            //print $this->data['Part 1 Quarter Acc Dispatched'];

            //   print $this->data['Part 1 Quarter Acc Dispatched']/(52/4)/7;


            if ($this->data['Part 1 Quarter Acc Required'] > 0) {
                $days_on_sale = 91.25;

                $from_since = (date('U') - strtotime($this->data['Part Valid From'])) / 86400;
                if ($from_since < 1) {
                    $from_since = 1;
                }


                if ($days_on_sale > $from_since) {
                    $days_on_sale = $from_since;
                }


                $this->data['Part Days Available Forecast']      = $this->data['Part Current On Hand Stock'] / ($this->data['Part 1 Quarter Acc Required'] / $days_on_sale);
                $this->data['Part XHTML Available For Forecast'] = number($this->data['Part Days Available Forecast'], 0).' '._('d');
            } else {
                $from_since = (date('U') - strtotime($this->data['Part Valid From'])) / 86400;


                // print $from_since;

                if ($from_since < ($this->data['Part Excess Availability Days Limit'] / 2)) {
                    $forecast = $this->data['Part Excess Availability Days Limit'] - 1;
                } else {
                    $forecast = $this->data['Part Excess Availability Days Limit'] + $from_since;
                }


                $this->data['Part Days Available Forecast']      = $forecast;
                $this->data['Part XHTML Available For Forecast'] = number($this->data['Part Days Available Forecast'], 0).' '._('d');
            }
        }


        $this->fast_update(array(
            'Part Days Available Forecast'      => $this->data['Part Days Available Forecast'],
            'Part XHTML Available for Forecast' => $this->data['Part XHTML Available For Forecast']
        ));


        $this->update_stock_status();
    }

    function load_acc_data()
    {
        $sql = sprintf(
            "SELECT * FROM `Part Data` WHERE `Part SKU`=%d",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        }
    }

    function update_delivery_days($options = '')
    {
        $sum_delivery_days     = 0;
        $number_supplier_parts = 0;
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $number_supplier_parts++;
                $sum_delivery_days += $supplier_part->get('Supplier Part Average Delivery Days');
            }
        }

        if ($number_supplier_parts == 0) {
            $average_delivery_days = 30;
        } else {
            $average_delivery_days = $sum_delivery_days / $number_supplier_parts;
        }

        $this->update(
            array(
                'Part Delivery Days' => $average_delivery_days
            ),
            $options
        );

        if ($this->updated) {
            $this->update_stock_status();
        }
    }

    function get_categories($scope = 'keys')
    {
        if ($scope == 'objects') {
            include_once 'class.Category.php';
        }


        $categories = array();


        $sql = sprintf(
            "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject`='Part' AND `Subject Key`=%d AND `Category Branch Type`!='Root'",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $categories[$row['Category Key']] = new Category(
                        $row['Category Key']
                    );
                } else {
                    $categories[$row['Category Key']] = $row['Category Key'];
                }
            }
        }

        return $categories;
    }

    function update_sko_image_key()
    {
        $image_key = '';

        $sql = "SELECT `Image Subject Image Key`  FROM `Image Subject Bridge` WHERE `Image Subject Object` = 'Part' AND `Image Subject Object Key` =? AND `Image Subject Object Image Scope`='SKO'  ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            $image_key = $row['Image Subject Image Key'];
        }


        $this->fast_update(array('Part SKO Image Key' => $image_key));
    }

    function get_production_suppliers($scope = 'keys')
    {
        if ($scope == 'objects') {
            include_once 'class.Supplier_Production.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension`LEFT JOIN `Supplier Production Dimension` ON (`Supplier Part Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Production Supplier Key` IS NOT NULL AND `Supplier Part Part SKU`=%d ',
            $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier_Production(
                        $row['Supplier Part Supplier Key']
                    );
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }
            }
        }

        return $suppliers;
    }


    function update_leakages($type = 'all')
    {
        if ($type == 'all' or $type == 'Lost') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Lost'",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            }

            $this->fast_update(array(
                'Part Stock Lost SKOs'  => -$skos,
                'Part Stock Lost Value' => -$amount


            ));
        }

        if ($type == 'all' or $type == 'Damaged') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Broken'",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            }

            $this->fast_update(array(
                'Part Stock Damaged SKOs'  => -$skos,
                'Part Stock Damaged Value' => -$amount


            ));
        }

        if ($type == 'all' or $type == 'Errors') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Other Out'  AND  `Inventory Transaction Quantity`<0  ",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            }

            $this->fast_update(array(
                'Part Stock Errors SKOs'  => -$skos,
                'Part Stock Errors Value' => -$amount


            ));
        }

        if ($type == 'all' or $type == 'Found') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d  AND `Inventory Transaction Type` in ('Other Out','Found')  AND  `Inventory Transaction Quantity`>0 ",
                $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            }

            $this->fast_update(array(
                'Part Stock Found SKOs'  => $skos,
                'Part Stock Found Value' => $amount


            ));
        }
    }

    function get_stock_supplier_data()
    {
        // todo create a way to know what is the supplier based in stock

        $supplier_key               = 0;
        $supplier_part_key          = 0;
        $supplier_part_historic_key = 0;


        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            $supplier_key               = $supplier_part->get('Supplier Part Supplier Key');
            $supplier_part_key          = $supplier_part->id;
            $supplier_part_historic_key = $supplier_part->get('Supplier Part Historic Key');
            break;
        }


        return array(
            $supplier_key,
            $supplier_part_key,
            $supplier_part_historic_key
        );
    }

    function update_on_demand()
    {
        $on_demand_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $on_demand_available = 'Yes';
                break;
            }
        }
        $this->update_field(
            'Part On Demand',
            $on_demand_available
        );


        $this->update_sales_from_invoices('1 Quarter', true, false);
        $this->update_stock();
        $this->update_available_forecast();


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_availability();
        }
    }

    function update_fresh()
    {
        $fresh_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part Fresh') == 'Yes' and $supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $fresh_available = 'Yes';
                break;
            }
        }
        $this->update_field('Part Fresh', $fresh_available, 'no_history');

        $this->update_stock_status();


        foreach ($this->get_suppliers('objects') as $supplier) {
            $supplier->update_supplier_parts();
        }
    }

    function get_suppliers($scope = 'keys')
    {
        if ($scope == 'objects') {
            include_once 'class.Supplier.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ',
            $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier('id', $row['Supplier Part Supplier Key'], false, $this->db);
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }
            }
        }

        return $suppliers;
    }


    function update_stock_in_paid_orders()
    {
        $old_value = $this->get('Part Current Stock Ordered Paid');

        $stock_in_paid_orders = 0;


        $sql =
            "SELECT sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio`) AS required  FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`) LEFT JOIN `Order Dimension` O ON (OTF.`Order Key`=O.`Order Key`)  WHERE `Order State`='InProcess'  and `Order To Pay Amount`<=0 and `Product Part Part SKU`=?   ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            $stock_in_paid_orders = $row['required'];
        }


        // print "$sql\n";
        $this->fast_update(array(
            'Part Current Stock Ordered Paid' => $stock_in_paid_orders

        ));

        if ($old_value != $stock_in_paid_orders) {
            $this->update_stock(true);
        }
    }


    function update_sales_from_invoices($interval, $this_year = true, $last_year = true)
    {
        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb
            ) = calculate_interval_dates($this->db, $interval);


        if ($this_year) {
            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Part $db_interval Acc Customers"        => $sales_data['customers'],
                "Part $db_interval Acc Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc Profit"           => $sales_data['profit'],
                "Part $db_interval Acc Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc Required"         => $sales_data['required'],
                "Part $db_interval Acc Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc Keeping Days"     => $sales_data['keep_days'],
                "Part $db_interval Acc With Stock Days"  => $sales_data['with_stock_days'],
            );


            $this->fast_update($data_to_update, 'Part Data');
        }
        if ($from_date_1yb and $last_year) {
            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);


            $data_to_update = array(

                "Part $db_interval Acc 1YB Customers"        => $sales_data['customers'],
                "Part $db_interval Acc 1YB Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc 1YB Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc 1YB Profit"           => $sales_data['profit'],
                "Part $db_interval Acc 1YB Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc 1YB Required"         => $sales_data['required'],
                "Part $db_interval Acc 1YB Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc 1YB Keeping Days"     => $sales_data['keep_days'],
                "Part $db_interval Acc 1YB With Stock Days"  => $sales_data['with_stock_days'],

            );
            $this->fast_update($data_to_update, 'Part Data');
        }

        if (in_array($db_interval, [
            'Total',
            'Year To Date',
            'Quarter To Date',
            'Week To Date',
            'Month To Date',
            'Today'
        ])) {
            $this->fast_update(['Part Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            '1 Year',
            '1 Month',
            '1 Week',
            '1 Quarter'
        ])) {
            $this->fast_update(['Part Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            'Last Month',
            'Last Week',
            'Yesterday',
            'Last Year'
        ])) {
            $this->fast_update(['Part Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        }
    }

    function get_sales_data($from_date, $to_date)
    {
        $sales_data = array(
            'invoiced_amount'  => 0,
            'profit'           => 0,
            'required'         => 0,
            'dispatched'       => 0,
            'deliveries'       => 0,
            'customers'        => 0,
            'repeat_customers' => 0,
            'keep_days'        => 0,
            'with_stock_days'  => 0,

        );


        if ($from_date == '' and $to_date == '') {
            $sales_data['repeat_customers'] = $this->get_customers_total_data();
        }


        $sql = sprintf(
            "SELECT count(DISTINCT `Delivery Note Customer Key`) AS customers, count( DISTINCT ITF.`Delivery Note Key`) AS deliveries, round(ifnull(sum(`Amount In`),0),2) AS invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) AS profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) AS dispatched,round(ifnull(sum(`Required`),0),1) AS required 
              FROM `Inventory Transaction Fact` ITF  LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) 
              WHERE `Inventory Transaction Type` LIKE 'Sale' AND `Part SKU`=%d %s %s",
            $this->id,
            ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['customers']       = $row['customers'];
                $sales_data['invoiced_amount'] = $row['invoiced_amount'];
                $sales_data['profit']          = $row['profit'];
                $sales_data['dispatched']      = -1.0 * $row['dispatched'];
                $sales_data['required']        = $row['required'];
                $sales_data['deliveries']      = $row['deliveries'];
            }
        }


        return $sales_data;
    }

    function get_customers_total_data()
    {
        $repeat_customers = 0;


        $sql = sprintf(
            'SELECT count(`Customer Part Customer Key`) AS num  FROM `Customer Part Bridge` WHERE `Customer Part Delivery Notes`>1 AND `Customer Part Part SKU`=%d    ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $repeat_customers = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $repeat_customers;
    }

    function update_previous_years_data()
    {
        foreach (range(1, 5) as $i) {
            $data_iy_ago    = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')),
                date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );
            $data_to_update = array(
                "Part $i Year Ago Customers"        => $data_iy_ago['customers'],
                "Part $i Year Ago Repeat Customers" => $data_iy_ago['repeat_customers'],
                "Part $i Year Ago Deliveries"       => $data_iy_ago['deliveries'],
                "Part $i Year Ago Profit"           => $data_iy_ago['profit'],
                "Part $i Year Ago Invoiced Amount"  => $data_iy_ago['invoiced_amount'],
                "Part $i Year Ago Required"         => $data_iy_ago['required'],
                "Part $i Year Ago Dispatched"       => $data_iy_ago['dispatched'],
                "Part $i Year Ago Keeping Days"     => $data_iy_ago['keep_days'],
                "Part $i Year Ago With Stock Days"  => $data_iy_ago['with_stock_days'],
            );

            $this->fast_update($data_to_update, 'Part Data');
        }
    }

    function update_previous_quarters_data()
    {
        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'],
                $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'],
                $dates_1yb['end']
            );

            $data_to_update = array(
                "Part $i Quarter Ago Customers"        => $sales_data['customers'],
                "Part $i Quarter Ago Repeat Customers" => $sales_data['repeat_customers'],
                "Part $i Quarter Ago Deliveries"       => $sales_data['deliveries'],
                "Part $i Quarter Ago Profit"           => $sales_data['profit'],
                "Part $i Quarter Ago Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $i Quarter Ago Required"         => $sales_data['required'],
                "Part $i Quarter Ago Dispatched"       => $sales_data['dispatched'],
                "Part $i Quarter Ago Keeping Days"     => $sales_data['keep_days'],
                "Part $i Quarter Ago With Stock Days"  => $sales_data['with_stock_days'],

                "Part $i Quarter Ago 1YB Customers"        => $sales_data_1yb['customers'],
                "Part $i Quarter Ago 1YB Repeat Customers" => $sales_data_1yb['repeat_customers'],
                "Part $i Quarter Ago 1YB Deliveries"       => $sales_data_1yb['deliveries'],
                "Part $i Quarter Ago 1YB Profit"           => $sales_data_1yb['profit'],
                "Part $i Quarter Ago 1YB Invoiced Amount"  => $sales_data_1yb['invoiced_amount'],
                "Part $i Quarter Ago 1YB Required"         => $sales_data_1yb['required'],
                "Part $i Quarter Ago 1YB Dispatched"       => $sales_data_1yb['dispatched'],
                "Part $i Quarter Ago 1YB Keeping Days"     => $sales_data_1yb['keep_days'],
                "Part $i Quarter Ago 1YB With Stock Days"  => $sales_data_1yb['with_stock_days'],
            );
            $this->fast_update($data_to_update, 'Part Data');
        }
    }

    function delete($metadata = false)
    {
        //todo dont delete if there is products with this part

        $sql = sprintf(
            'INSERT INTO `Part Deleted Dimension`  (`Part Deleted Key`,`Part Deleted Reference`,`Part Deleted Date`,`Part Deleted Metadata`) VALUES (%d,%s,%s,%s) ',
            $this->id,
            prepare_mysql($this->get('Part Reference')),
            prepare_mysql(gmdate('Y-m-d H:i:s')),
            prepare_mysql(gzcompress(json_encode($this->data), 9))

        );
        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Part Dimension`  WHERE `Part SKU`=%d ',
            $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _("Part record %s deleted"),
                $this->data['Part Reference']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data,
            true,
            'No',
            'Changes',
            $this->get_object_name(),
            $this->id
        );


        $this->deleted = true;


        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d  ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $supplier_part = get_object(
                    'Supplier Part',
                    $row['Supplier Part Key']
                );
                $supplier_part->delete();
            }
        }

        $this->fork_index_elastic_search('delete_elastic_index_object');
        $this->model_updated( 'deleted', $this->id);
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Part SKU':
                $label = _('SKU');
                break;
            case 'Part Status':
                $label = _('Status');
                break;
            case 'Part Reference':
                $label = _('reference');
                break;
            case 'Part Unit Label':
                $label = _('unit label');
                break;
            case 'Part Package Description':
                $label = _('SKO description');
                break;
            case 'Part Package Description Note':
                $label = _('SKO description note');
                break;
            case 'Part Package Image':
                $label = _('SKO image');
                break;
            case 'Part Unit Price':
                $label = _('unit recommended price');
                break;
            case 'Part Unit RRP':
                $label = _('unit recommended RRP');
                break;

            case 'Part Package Weight':
                $label = _('SKO weight');
                break;
            case 'Part Package Dimensions':
                $label = _('SKO dimensions');
                break;
            case 'Part Unit Weight':
                $label = _('Weight shown in website');
                break;
            case 'Part Unit Dimensions':
                $label = _('Dimensions shown in website');
                break;
            case 'Part Tariff Code':
                $label = _('tariff code');
                break;
            case 'Part Duty Rate':
                $label = _('duty rate');
                break;
            case 'Part UN Number':
                $label = _('UN number');
                break;
            case 'Part UN Class':
                $label = _('UN class');
                break;
            case 'Part Packing Group':
                $label = _('packing group');
                break;
            case 'Part Proper Shipping Name':
                $label = _('proper shipping name');
                break;
            case 'Part Hazard Identification Number':
                $label = _('hazard identification number');
                break;
            case 'Part Materials':
                $label = _('Materials/Ingredients');
                break;
            case 'Part Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Part Units Per Package':
                $label = _('units per SKO');
                break;
            case 'Part Barcode Number':
                $label = _('barcode');
                break;
            case 'Part CPNP Number':
                $label = _('CPNP number');
                break;
            case 'Part Cost in Warehouse':
                $label = _('Stock value (per SKO)');
                break;
            case 'Part Recommended Packages Per Selling Outer':
                $label = _('SKOs per selling outer (recommended)');
                break;
            //case 'Part SKOs per Carton':
            //    $label = _('SKOs per selling carton');
            //    break;
            case 'Part Recommended Product Unit Name':
                $label = _('Unit description');
                break;

            case 'Part Carton Barcode':
                $label = _('carton barcode');
                break;

            case 'Part Delivery Day':
                $label = _('average delivery days');
                break;
            case 'Part HTSUS Code':
                $label = 'HTSUS';
                break;
            case 'Part UFI':
                $label = 'UFI (Poison Centres)';
                break;
            case 'Part Picking Band Key':
                $label = _('picking band');
                break;
            case 'Part Packing Band Key':
                $label = _('packing band');
                break;
            case 'Part Seasonal':
                $label = _('sesonal product');
                break;
            case 'Part For Disconinue Review':
                $label = _('Propose for discontinue');
                break;
            case 'Part Attention':
                $label = _('for attention');
                break;
            case 'Part GPSR Manufacturer':
                $label = _('manufacturer');
                break;
            case 'Part GPSR EU Responsable':
                $label = _('EU responsible');
                break;
            case 'Part GPSR Warnings':
                $label = _('warnings');
                break;
            case 'Part GPSR Manual':
                $label = _('How to use');
                break;
            case 'Part GPSR Class Category Danger':
                $label = _('Class & category of danger');
                break;




            default:
                $label = $field;
        }

        return $label;
    }

    function create_raw_material()
    {
        include_once 'class.Raw_Material.php';

        $account = get_object('Account', 1);
        $account->load_acc_data();

        $raw_material_data = [
            'Raw Material Type'                    => 'Part',
            'Raw Material Creation Date'           => gmdate('Y-m-d H:i:s'),
            'Raw Material Type Key'                => $this->id,
            'Raw Material Code'                    => $this->get('Reference'),
            'Raw Material Unit Label'              => $this->get('Part Unit Label'),
            'Raw Material Description'             => $this->get('Part Recommended Product Unit Name'),
            'Raw Material Unit'                    => 'Unit',
            'Raw Material Unit Cost'               => ($this->get('Part Cost in Warehouse') == '' ? $this->get('Part Cost') : $this->get('Part Cost in Warehouse')),
            'Raw Material Production Supplier Key' => $account->properties('production_supplier_key'),
            'editor'                               => $this->editor
        ];

        $raw_material = new Raw_Material('find create', $raw_material_data);


        if ($raw_material->id) {
            $this->fast_update(['Part Raw Material Key' => $raw_material->id]);
            $raw_material->update_raw_material_stock($this);
        }
    }

    function create_supplier_part_record($data)
    {
        include_once 'class.Supplier.php';

        $data['editor'] = $this->editor;


        $supplier = new Supplier($data['Supplier Part Supplier Key']);
        if (!$supplier->id) {
            $this->error      = true;
            $this->error_code = 'supplier_not_found';
            $this->msg        = _('Supplier not found');
        }

        if ($data['Supplier Part Minimum Carton Order'] == '') {
            $data['Supplier Part Minimum Carton Order'] = 1;
        } else {
            $data['Supplier Part Minimum Carton Order'] = ceil(
                $data['Supplier Part Minimum Carton Order']
            );
        }


        $data['Supplier Part Currency Code'] = $supplier->get('Supplier Default Currency Code');


        $supplier_part = new SupplierPart('find', $data, 'create');


        if ($supplier_part->id) {
            $this->new_object_msg = $supplier_part->msg;

            if ($supplier_part->new) {
                $this->new_object = true;


                $supplier_part->update(array('Supplier Part Part SKU' => $this->id));
                $supplier_part->get_data('id', $supplier_part->id);

                $supplier->update_supplier_parts();

                $this->update_cost();
                $supplier_part->update_historic_object();
            } else {
                $this->error = true;
                if ($supplier_part->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($supplier_part->duplicated_field));

                    if ($supplier_part->duplicated_field == 'Supplier Part Reference') {
                        $this->msg = _("Duplicated supplier's product reference");
                    } else {
                        $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                    }
                } else {
                    $this->msg = $supplier_part->msg;
                }
            }

            return $supplier_part;
        } else {
            $this->error = true;

            if ($supplier_part->found) {
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(array($supplier_part->duplicated_field));

                if ($supplier_part->duplicated_field == 'Part Reference') {
                    $this->msg = _("Duplicated part reference");
                } else {
                    $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                }
            } else {
                $this->msg = $supplier_part->msg;
            }
        }

        return false;
    }

    function updated_linked_products()
    {
        foreach ($this->get_products('objects') as $product) {
            if (count($product->get_parts()) == 1) {
                $product->editor = $this->editor;
                $product->fork   = $this->fork;

                $product->fast_update(array(
                    'Product Tariff Code'                  => $this->get('Part Tariff Code'),
                    'Product HTSUS Code'                   => $this->get('Part HTSUS Code'),
                    'Product Duty Rate'                    => $this->get('Part Duty Rate'),
                    'Product Origin Country Code'          => $this->get('Part Origin Country Code'),
                    'Product UN Number'                    => $this->get('Part UN Number'),
                    'Product UN Class'                     => $this->get('Part UN Class'),
                    'Product Packing Group'                => $this->get('Part Packing Group'),
                    'Product Proper Shipping Name'         => $this->get('Part Proper Shipping Name'),
                    'Product Hazard Identification Number' => $this->get('Part Hazard Identification Number'),
                    'Product Unit Weight'                  => $this->get('Part Unit Weight'),
                    'Product Unit Dimensions'              => $this->get('Part Unit Dimensions'),
                    'Product Materials'                    => $this->data['Part Materials'],
                    'Product Barcode Number'               => $this->data['Part Barcode Number'],
                    'Product Barcode Key'                  => $this->data['Part Barcode Key'],
                    'Product CPNP Number'                  => $this->data['Part CPNP Number'],
                    'Product UFI'                          => $this->data['Part UFI'],

                ));

                $product->update_updated_markers('Data');

                $sql = "SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`='Part' AND `Image Subject Object Key`=? and `Image Subject Object Image Scope`='Marketing' ORDER BY `Image Subject Order` ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($this->id));
                while ($row = $stmt->fetch()) {
                    $product->link_image($row['Image Subject Image Key'], 'Marketing');
                }
            }
        }
    }

    function get_picking_location_key($store_scope = '')
    {
        if ($store_scope) {
            $sql  = "select `Location Key`,`Location Picking Pipeline Location Key`  
                    FROM `Location Picking Pipeline Bridge` B left join 
                        `Part Location Dimension` PL    on (PL.`Location Key`=B.`Location Picking Pipeline Location Key`) left join 
                        `Picking Pipeline Dimension` on (`Picking Pipeline Key`=`Location Picking Pipeline Picking Pipeline Key`)  
                    WHERE `Part SKU`=?  and `Picking Pipeline Store Key`=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array(
                $this->id,
                $store_scope
            ));
            if ($row = $stmt->fetch()) {
                return $row['Location Key'];
            }
        }

        $sql = "SELECT `Location Key` FROM `Part Location Dimension` WHERE `Part SKU`=? AND `Can Pick`='Yes'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            return $row['Location Key'];
        } else {
            return false;
        }
    }

    function update_products_data()
    {
        $active_products    = 0;
        $no_active_products = 0;
        $online_products    = 0;

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('InProcess','Active','Discontinuing') ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $active_products = $row['num'];
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('Suspended','Discontinued') ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $no_active_products = $row['num'];
            }
        }


        $this->fast_update(array(
                'Part Number Active Products'    => $active_products,
                'Part Number No Active Products' => $no_active_products,
                'Part Number Products Online'    => $online_products,
            )

        );
        $this->activate();
    }

    function update_commercial_value()
    {
        include_once 'utils/currency_functions.php';
        include_once 'utils/date_functions.php';

        $num_all_products        = 0;
        $num_products            = 0;
        $num_products_with_sales = 0;
        $sum_sales               = 0;
        $data                    = array();

        $account = get_object('Account', 1);

        foreach ($this->get_products('data') as $product_data) {
            $num_all_products++;
            /** @var  $product \Product */
            $product = get_object('product', $product_data['Product Part Product ID']);


            if (count($product->get_parts()) == 1) {
                $product->load_acc_data();
                $num_products++;

                if ($product->get('Product Status') == 'Discontinued') {
                    list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb) = calculate_interval_dates($this->db, '1 Year');


                    $invoiced = 0;


                    $sql = sprintf(
                        "SELECT round(ifnull(sum(`Delivery Note Quantity`),0),1) AS invoiced FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE `Invoice Key` >0 AND  `Product ID`=%d %s %s ",
                        $product->id,
                        ($from_date ? sprintf(
                            'and `Invoice Date`>=%s',
                            prepare_mysql($from_date)
                        ) : ''),
                        ($to_date ? sprintf(
                            'and `Invoice Date`<%s',
                            prepare_mysql($to_date)
                        ) : '')

                    );


                    // print "$sql\n";


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $invoiced = $row['invoiced'];
                        }
                    }


                    $sales = $invoiced * $product_data['Product Part Ratio'];
                } else {
                    $sales_1q = $product->get('Product 1 Year Acc Quantity Invoiced');
                    if ($sales_1q == '') {
                        $sales_1q = 0;
                    }
                    $product_ratio = $product_data['Product Part Ratio'];
                    if (!is_numeric($product_ratio) or $product_ratio < 0) {
                        $product_ratio = 1;
                    }

                    $sales = $sales_1q * $product_ratio;
                }


                // print $product->get('Product 1 Year Acc Quantity Invoiced');

                $exchange = 1 / currency_conversion($this->db, $account->get('Account Currency'), $product->get('Product Currency'));

                if ($sales > 0) {
                    $num_products_with_sales++;
                    $sum_sales += $sales;


                    $data[] = array(
                        'store'         => $product->get('Store Key'),
                        'sales'         => $sales,
                        'price'         => $product->get('Product Price'),
                        'selling_price' => $exchange * $product->get('Product Price') / $product_data['Product Part Ratio'],
                        'exchange'      => $exchange,
                        'currency'      => $product->get('Product Currency')
                    );
                } else {
                    $data[] = array(
                        'store'         => $product->get('Store Key'),
                        'sales'         => 0,
                        'price'         => $product->get('Product Price'),
                        'selling_price' => $exchange * $product->get('Product Price') / $product_data['Product Part Ratio'],
                        'exchange'      => $exchange,
                        'currency'      => $product->get('Product Currency')
                    );
                }
            }
        }

        // print_r($data);

        $commercial_value = '';

        if ($num_products_with_sales > 0) {
            $commercial_value = 0;

            foreach ($data as $item) {
                $commercial_value += $item['selling_price'] * $item['sales'] / $sum_sales;
                //print $item['selling_price'].' '.$item['sales']/$sum_sales."\n";
            }
        } elseif ($num_products > 0) {
            $commercial_value = 0;

            foreach ($data as $item) {
                $commercial_value += $item['selling_price'];
            }


            $commercial_value = $commercial_value / $num_products;
        }


        $this->fast_update(array('Part Commercial Value' => $commercial_value));
        $this->update_margin();
    }

    function update_number_locations()
    {
        $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);

        $locations = 0;

        $sql  = "SELECT count(*) as num FROM `Part Location Dimension` WHERE `Location Key`!=? AND `Part SKU`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $warehouse->get('Warehouse Unknown Location Key'),
            $this->id
        ]);
        if ($row = $stmt->fetch()) {
            $locations = $row['num'];
        }


        $this->fast_update([
            'Part Distinct Locations' => $locations,
        ]);
        $this->update_number_pipeline_locations();
    }


    function update_number_pipeline_locations()
    {
        $pipeline_locations = 0;


        $sql  = "SELECT count(*) as num FROM  `Location Picking Pipeline Bridge`   left join `Part Location Dimension`  on (`Location Picking Pipeline Location Key`=`Location Key`)   WHERE  `Part SKU`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $this->id
        ]);
        if ($row = $stmt->fetch()) {
            $pipeline_locations = $row['num'];
        }


        $this->fast_update([
            'Part Pipeline Locations' => $pipeline_locations
        ]);
    }


    function update_unknown_location()
    {
        $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);

        $stock = 0;
        $value = 0;
        $sql   = sprintf("SELECT `Quantity On Hand`,`Stock Value` FROM `Part Location Dimension` WHERE `Location Key`=%d AND `Part SKU`=%d ", $warehouse->get('Warehouse Unknown Location Key'), $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['Quantity On Hand'];
                $value = $row['Stock Value'];
            }
        }


        $this->fast_update(array(
            'Part Unknown Location Stock'       => $stock,
            'Part Unknown Location Stock Value' => $value,

        ));
    }

    function update_part_inventory_snapshot_fact($from = '', $to = '')
    {
        include_once "class.PartLocation.php";
        if ($from == '') {
            $from = $this->get('Part Valid From');
        }
        if ($to == '') {
            $to = ($this->get('Part Status') == 'Not In Use' ? $this->get('Part Valid To') : gmdate('Y-m-d H:i:s'));
        }


        $sql = sprintf(
            "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) ORDER BY `Date` DESC",
            prepare_mysql($from),
            prepare_mysql($to)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`",
                    $this->id,
                    prepare_mysql($row['Date'].' 23:59:59')
                );


                $locations = 0;


                $stock_on_hand           = 0;
                $stock_cost              = 0;
                $stock_value_at_day_cost = 0;
                $stock_commercial_value  = 0;

                $sold_amount = 0;

                $book_in = 0;
                $sold    = 0;
                $lost    = 0;

                $stock_value_in_purchase_order = 0;
                $stock_value_in_other          = 0;
                $stock_value_out_sales         = 0;
                $stock_value_out_other         = 0;


                $location_keys  = [];
                $warehouse_keys = [];

                $found_pl_in_date = false;
                if ($result3 = $this->db->query($sql)) {
                    foreach ($result3 as $row3) {
                        // print_r($row3);

                        $part_location               = new PartLocation($this->id.'_'.$row3['Location Key']);
                        $result_update_stock_history = $part_location->update_stock_history_date($row['Date']);
                        //print 'bye';

                        if ($result_update_stock_history['action'] == 'updated') {
                            $found_pl_in_date = true;
                            $locations++;


                            $location_keys[$part_location->location->id]                             = 1;
                            $warehouse_keys[$part_location->location->get('Location Warehouse Key')] = 1;

                            $stock_on_hand                 += $result_update_stock_history['data']['stock_on_hand'];
                            $stock_cost                    += $result_update_stock_history['data']['stock_cost'];
                            $stock_value_at_day_cost       += $result_update_stock_history['data']['stock_value_at_day_cost'];
                            $stock_commercial_value        += $result_update_stock_history['data']['stock_commercial_value'];
                            $sold_amount                   += $result_update_stock_history['data']['sold_amount'];
                            $book_in                       += $result_update_stock_history['data']['book_in'];
                            $sold                          += $result_update_stock_history['data']['sold'];
                            $lost                          += $result_update_stock_history['data']['lost'];
                            $stock_value_in_purchase_order += $result_update_stock_history['data']['stock_value_in_purchase_order'];
                            $stock_value_in_other          += $result_update_stock_history['data']['stock_value_in_other'];
                            $stock_value_out_sales         += $result_update_stock_history['data']['stock_value_out_sales'];
                            $stock_value_out_other         += $result_update_stock_history['data']['stock_value_out_other'];
                        }
                    }
                }


                if ($found_pl_in_date) {
                    $stock_left_1_year_ago = 0;
                    if ($stock_on_hand > 0) {
                        $date_1yr_back = gmdate('Y-m-d', strtotime($row['Date'].' -1 year'));
                        if (gmdate('U', strtotime($this->get('Part Valid From'))) < gmdate('U', strtotime($row['Date'].' -1 year'))) {
                            $sql = sprintf(
                                "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`",
                                $this->id,
                                prepare_mysql($date_1yr_back.' 23:59:59')
                            );
                            //print "$sql\n";
                            $stock_one_year_ago = 0;

                            if ($result3 = $this->db->query($sql)) {
                                foreach ($result3 as $row3) {
                                    $part_location      = new PartLocation($this->id.'_'.$row3['Location Key']);
                                    $stock_one_year_ago += $part_location->get_stock($date_1yr_back.' 23:59:59');
                                }
                            }


                            $total_out_1_year = 0;


                            $sql = sprintf(
                                "SELECT sum(`Inventory Transaction Quantity`) as qty_out FROM `Inventory Transaction Fact` WHERE `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Inventory Transaction Record Type`='Movement'  and  `Inventory Transaction Quantity`<0  ",
                                prepare_mysql($date_1yr_back.' 23:59:59'),
                                prepare_mysql($row['Date'].' 23:59:59'),
                                $this->id
                            );
                            //print "$sql\n";
                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    $total_out_1_year = $row2['qty_out'];
                                } else {
                                    $total_out_1_year = 0;
                                }
                            }

                            $total_out_1_year = -1 * $total_out_1_year;


                            if ($stock_one_year_ago > 0 and $stock_one_year_ago > $total_out_1_year) {
                                $stock_left_1_year_ago = $stock_one_year_ago - $total_out_1_year;
                            }
                        }
                    }


                    if (strtotime($this->data['Part Valid From']) <= strtotime($row['Date'].' 23:59:59 -1 year')) {
                        $sql = sprintf(
                            "SELECT `Inventory Transaction key`   FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Sale' AND `Date`>=%s AND `Date`<=%s  limit 1",
                            $this->id,
                            prepare_mysql(date("Y-m-d H:i:s", strtotime($row['Date'].' 23:59:59 -1 year'))),
                            prepare_mysql($row['Date'].' 23:59:59')
                        );
                        //print "$sql\n";


                        $dormant_1year = 'Yes';
                        if ($result3 = $this->db->query($sql)) {
                            if ($row3 = $result3->fetch()) {
                                $dormant_1year = 'No';
                            }
                        }
                    } else {
                        $dormant_1year = 'NA';
                    }


                    if (gmdate('U', strtotime($this->data['Part Valid From'])) > gmdate('U', strtotime($row['Date'].' - 1 year'))) {
                        $no_sales_1_year_icon = 'fal fa-seedling';
                    } else {
                        switch ($dormant_1year) {
                            case 'Yes':
                                $no_sales_1_year_icon = 'fa fa-snooze';
                                break;
                            case 'No':
                                $no_sales_1_year_icon = 'fa success fa-check';
                                break;
                            default:
                                $no_sales_1_year_icon = 'error fa fa-question';
                                break;
                        }
                    }


                    //print "======\n";

                    $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
                        ->setApiKey(ES_KEY1, ES_KEY2)->setSSLVerification(ES_SSL)
                        ->build();

                    $params = ['body' => []];


                    $params['body'][] = [
                        'index' => [
                            '_index' => 'au_part_isf_'.strtolower(DNS_ACCOUNT_CODE),
                            '_id'    => DNS_ACCOUNT_CODE.'.'.$this->id.'.'.$row['Date'],

                        ]
                    ];

                    $params['body'][] = [
                        'tenant'                  => strtolower(DNS_ACCOUNT_CODE),
                        'date'                    => $row['Date'],
                        '1st_day_year'            => (bool)preg_match('/\d{4}-01-01/ ', $row['Date']),
                        '1st_day_month'           => (bool)preg_match('/\d{4}-\d{2}-01/', $row['Date']),
                        '1st_day_quarter'         => (bool)preg_match('/\d{4}-(01|04|07|10)-01/', $row['Date']),
                        '1st_day_week'            => gmdate('w', strtotime($row['Date'])) == 0,
                        'sku'                     => $this->id,
                        'locations'               => array_keys($location_keys),
                        'warehouses'              => array_keys($warehouse_keys),
                        'stock_on_hand'           => $stock_on_hand,
                        'stock_cost'              => $stock_cost,
                        'stock_value_at_day_cost' => $stock_value_at_day_cost,
                        'stock_commercial_value'  => $stock_commercial_value,

                        'sold_amount' => $sold_amount,
                        'book_in'     => $book_in,
                        'sold'        => $sold,
                        'lost'        => $lost,

                        'stock_value_in_purchase_order' => $stock_value_in_purchase_order,
                        'stock_value_in_other'          => $stock_value_in_other,
                        'stock_value_out_sales'         => $stock_value_out_sales,
                        'stock_value_out_other'         => $stock_value_out_other,


                        'sko_cost'              => ($result_update_stock_history['cost_per_sko'] ?? $this->get('Part Cost in Warehouse')),
                        'stock_left_1_year_ago' => $stock_left_1_year_ago,
                        'no_sales_1_year'       => $dormant_1year == 'Yes',
                        'no_sales_1_year_icon'  => $no_sales_1_year_icon,
                        'part_reference'        => $this->data['Part Reference'],
                        'part_description'      => $this->data['Part Package Description'],

                    ];


                    // print_r($params);

                    $client->bulk($params);
                }
            }
        }
    }

    function get_aiku_params($field, $value = '')
    {
        $params = [
            'legacy_id' => $this->id,
        ];

        $url = AIKU_URL.'stocks/'.$this->id;

        switch ($field) {
            case 'Object':
                $url = AIKU_URL.'stocks/';

                $params += $this->get_aiku_params('Part Status')[1];
                $params += $this->get_aiku_params('Part Stock Status')[1];

                $params += $this->get_aiku_params('Part Reference', $this->data['Part Reference'])[1];
                $params += $this->get_aiku_params('Part Unit Label', $this->data['Part Unit Label'])[1];

                $params += $this->get_aiku_params('barcode')[1];
                $params += $this->get_aiku_params('unit_quantity')[1];

                $params['description']        = $this->data['Part Recommended Product Unit Name'];
                $params['packed_in']          = $this->data['Part Units Per Package'];
                $params['available_forecast'] = $this->data['Part Days Available Forecast'];


                $legacy_data      = [];
                $legacy_data      += json_decode($this->get_aiku_params('locations')[1]['legacy'], true);
                $params['legacy'] = json_encode($legacy_data);

                $params['data'] = json_encode([
                    'package' => [
                        'description' => $this->data['Part Package Description'],
                        'weight'      => $this->data['Part Package Weight'],
                        'dimensions'  => json_decode($this->data['Part Package Dimensions'], true),

                    ],
                    'unit'    => [
                        'weight'     => $this->data['Part Package Weight'],
                        'dimensions' => json_decode($this->data['Part Package Dimensions'], true),

                    ]
                ]);
                break;
            case 'locations':

                $locations = [];

                $sql = "SELECT `Location Place`,PL.`Location Key`,`Location Code`,`Quantity On Hand`,`Part Location Note`,`Location Warehouse Key`,`Part SKU`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Can Pick`, datediff(CURDATE(), `Part Location Last Audit`) AS days_last_audit,`Part Location Last Audit` FROM `Part Location Dimension` PL LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PL.`Location Key`)  WHERE `Part SKU`=? 
    order by `Can Pick` desc,`Quantity On Hand`
";


                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                    $this->id
                ));
                while ($row = $stmt->fetch()) {
                    $locations[] = $row;
                }
                $legacy_data              = [];
                $legacy_data['locations'] = $locations;

                $params['legacy'] = json_encode($legacy_data);

                break;
            case 'Part Status':
                $legacy_status_to_state = [
                    'In Use'        => 'active',
                    'Discontinuing' => 'discontinuing',
                    'In Process'    => 'creating',
                    'Not In Use'    => 'discontinued'
                ];
                $params['state']        = $legacy_status_to_state[$this->data['Part Status']];
                break;
            case 'Part Stock Status':
                $quantity_status = strtolower($this->data['Part Stock Status']);
                if ($quantity_status == 'out_of_stock') {
                    $quantity_status = 'outOfStock';
                }

                $params['quantity_status'] = $quantity_status;

                break;
            case 'Part Reference':
                if ($value == '') {
                    $value = 'empty_'.$this->data['Part SKU'];
                }
                $params['code'] = $value;
                break;
            case 'Part Unit Label':
                if ($value == '') {
                    $value = 'piece';
                }
                $params['unit_type'] = $value;
                break;
            case 'barcode':
                $barcode = $this->data['Part SKO Barcode'];
                if ($barcode == '') {
                    $barcode = $this->data['Part Barcode Number'];
                }
                $params['barcode'] = $barcode;

                break;
            case 'unit_quantity':
                $params['unit_quantity'] = $this->data['Part Current On Hand Stock'] * $this->data['Part Units Per Package'];
                break;
            case 'Part Recommended Product Unit Name':
                $params['description'] = $value;
                break;
            case 'Part Units Per Package':
                $params['packed_in'] = $value;
                break;
            case 'Part Days Available Forecast':
                $params['available_forecast'] = $value;
                break;


            case 'Part Package Description':
            case 'Part Package Weight':
            case 'Part Package Dimensions':
                $params['data'] = json_encode([
                    'package' => [
                        'description' => $this->data['Part Package Description'],
                        'weight'      => $this->data['Part Package Weight'],
                        'dimensions'  => json_decode($this->data['Part Package Dimensions'], true),

                    ]
                ]);
                break;
            case 'Part Unit Weight':
            case 'Part Unit Dimensions':
                $params['data'] = json_encode([
                    'unit' => [
                        'weight'     => $this->data['Part Unit Weight'],
                        'dimensions' => json_decode($this->data['Part Unit Dimensions'], true),

                    ]
                ]);
                break;

            default:
                return [
                    false,
                    false
                ];
        }


        return [
            $url,
            $params
        ];
    }

}



