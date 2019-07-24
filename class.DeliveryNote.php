<?php
/*
  File: Delivery Note.php


  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009,Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Order.php';
include_once 'class.Product.php';


class DeliveryNote extends DB_Table {

    var $update_stock = true;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Delivery Note';
        $this->ignore_fields = array('Delivery Note Key');

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'create') {
            $this->create($arg2, $arg3);

            return;
        } else {
            if ($arg1 == 'create replacement') {
                $this->create_replacement($arg2, $arg3);

                return;
            }
        }

        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Delivery Note Dimension` WHERE  `Delivery Note Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Delivery Note Dimension` WHERE  `Delivery Note ID`=%s", prepare_mysql($tag)
            );

        } else {

            // print
            return;
        }
        //   print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Delivery Note Key'];

            //todo remove this if when sure Delivery Note Metadata V2 always is a valid json
            if ($this->data['Delivery Note Metadata V2'] == '') {
                $this->medatata = array();
            } else {
                $this->medatata = json_decode($this->data['Delivery Note Metadata V2'], true);
            }


        }


    }

    protected function create($dn_data, $order) {


        $base_data = $this->base_data();

        $this->editor = $dn_data['editor'];
        unset($dn_data['editor']);

        foreach ($dn_data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Delivery Note Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id = $this->db->lastInsertId();


            $sql = sprintf(
                'UPDATE `Delivery Note Dimension`  SET  `Delivery Note Metadata V2`=JSON_SET(`Delivery Note Metadata V2`,"$.ver",2)
                             WHERE `Delivery Note Key`=%d ', $this->id
            );


            $this->db->exec($sql);

            $this->get_data('id', $this->id);


            $total_estimated_weight = 0;
            $distinct_items         = 0;


            $sql = sprintf(
                'SELECT `Order Bonus Quantity`,`Product Package Weight`,`Order Quantity`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PH  ON (OTF.`Product Key`=PH.`Product Key`)  LEFT JOIN `Product Dimension` P  ON (PH.`Product ID`=P.`Product ID`)     WHERE `Order Key`=%d  AND (`Delivery Note Key` IS NULL OR `Delivery Note Key`=0)',
                $order->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $estimated_weight       = ($row['Order Quantity'] + $row['Order Bonus Quantity']) * $row['Product Package Weight'];
                    $total_estimated_weight += $estimated_weight;
                    $distinct_items++;
                    $sql = sprintf(
                        "UPDATE  `Order Transaction Fact` SET `Estimated Weight`=%f,`Order Last Updated Date`=%s,`Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s WHERE `Order Transaction Fact Key`=%d", $estimated_weight,
                        prepare_mysql($this->data['Delivery Note Date Created']), prepare_mysql($this->data['Delivery Note ID'])


                        , $this->data['Delivery Note Key'], prepare_mysql($this->data['Delivery Note Address Country 2 Alpha Code']), $row['Order Transaction Fact Key']

                    );
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                'SELECT `Order No Product Transaction Fact Key` FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND (`Delivery Note Key` IS NULL  OR `Delivery Note Key`=0) ', $order->id
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        "UPDATE  `Order No Product Transaction Fact` SET `Delivery Note Date`=%s,`Delivery Note Key`=%d WHERE `Order No Product Transaction Fact Key`=%d", prepare_mysql($this->data['Delivery Note Date Created']), $this->id,
                        $row['Order No Product Transaction Fact Key']

                    );
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                'SELECT OTF.`Product Code`,OTF.`Order Quantity`,OTF.`Product ID`,`Product Package Weight`,`Order Quantity`,`Order Bonus Quantity`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PH  ON (OTF.`Product Key`=PH.`Product Key`)  LEFT JOIN `Product Dimension` P  ON (PH.`Product ID`=P.`Product ID`)
		WHERE `Order Key`=%d  AND `Current Dispatching State` IN ("Submitted by Customer","In Process")  ', $order->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    $this->create_inventory_transaction_fact_item(
                        $row['Product ID'], $row['Order Transaction Fact Key'], $row['Order Quantity'], $row['Order Bonus Quantity'], $this->get('Delivery Note Date')
                    );


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $history_data = array(
                'History Abstract' => _('Delivery note created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            $this->update_totals();


        } else {
            exit ("$sql \n Error can not create dn header");
        }

        return $this;


    }

    function create_inventory_transaction_fact_item($product_id, $map_to_otf_key, $to_sell_quantity, $bonus_qty, $date) {


        $product = new Product('id', $product_id);

        $part_list = $product->get_parts_data();

        $state = 'Ready to Pick';

        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s WHERE `Order Transaction Fact Key`=%d  ", prepare_mysql($state), $map_to_otf_key
        );
        $this->db->exec($sql);

        $part_index = 0;


        // print_r($part_list);

        foreach ($part_list as $part_data) {


            $part = new Part ('sku', $part_data['Part SKU']);
            if ($part->sku) {

                $required = $part_data['Ratio'] * $to_sell_quantity;
                $given    = $part_data['Ratio'] * $bonus_qty;
                $weight   = $part->get('Part Package Weight') * ($required + $given);


                $location_key = $part->get_picking_location_key();


                list($supplier_key, $supplier_part_key, $supplier_part_historic_key) = $part->get_stock_supplier_data();


                $note = '';


                $picking_note = $part_data['Note'];


                $sql = sprintf(
                    "INSERT INTO `Inventory Transaction Fact`  (
					`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Inventory Transaction Fact Delivery 2 Alpha Code`,`Picking Note`,

					`Inventory Transaction Weight`,`Date Created`,`Date`,`Delivery Note Key`,`Part SKU`,`Location Key`,

					`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,

					`Metadata`,`Note`,`Supplier Product ID`,`Supplier Product Historic Key`,`Supplier Key`,`Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`)
					VALUES (
					%s,%s,%s,%s,
					%f,%s,%s,%d,%s,%d,
					%s,%s,%.2f,%f,%f,%f,

					%s,%s,%d,%d,%d,%d,%s) ", "'Info'", "'OIP'", prepare_mysql($this->data['Delivery Note Address Country 2 Alpha Code']), prepare_mysql($picking_note),

                    $weight, prepare_mysql($this->get('Delivery Note Date')), prepare_mysql($this->get('Delivery Note Date')), $this->id, prepare_mysql($part_data['Part SKU']), $location_key,


                    0, "'Order In Process'", 0, $required, $given, 0,


                    prepare_mysql($this->data['Delivery Note Metadata']), prepare_mysql($note),

                    $supplier_part_key,

                    $supplier_part_historic_key,

                    $supplier_key, $map_to_otf_key, prepare_mysql($part_index.';'.$part_data['Ratio'].';0')
                );
                $this->db->exec($sql);


                //  print $sql;


                if ($this->update_stock) {


                    //$part_location=new PartLocation($part_data['Part SKU'].'_'.$location_key);
                    //$part_location->update_stock();
                }
                //print "$sql\n";


                $part_index++;

            }

            if ($part_index == 0) {

                exit ("\nWarning no part in product ".$product->pid." on $date\n");

            }


        }


    }

    function get($key) {


        if (!$this->id) {
            return '';
        }

        switch ($key) {


            case 'State Index':


                // print $this->data['Delivery Note State'].'x';

                switch ($this->data['Delivery Note State']) {
                    case 'Ready to be Picked':
                        return 10;
                        break;

                    case 'Picking':
                        return 20;
                        break;

                    case 'Picked':
                        return 30;
                        break;
                    case 'Packing':
                        return 40;
                        break;
                    case 'Packed':
                        return 70;
                        break;
                    case 'Packed Done':
                        return 80;
                        break;
                    case 'Approved':
                        return 90;
                        break;
                    case 'Dispatched':
                        return 100;
                        break;
                    case 'Cancelled':
                        return -20;
                        break;
                    case 'Cancelled to Restock':
                        return -10;
                        break;
                    default:
                        return 0;
                        break;
                }
                break;

            case 'Picking and Packing Percentage or Date':

                if ($this->get('State Index') < 40) {
                    return $this->get('Picked Percentage or Datetime');
                } else {
                    return $this->get('Packed Percentage or Datetime');

                }


                break;
            case 'Picked Percentage or Datetime':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Delivery Note Date Start Picking') == '') {
                        return '';
                    }


                    if ($this->get('Delivery Note Date Finish Picking') != '') {
                        return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 20) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Picked Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {

                            if ($this->get('Delivery Note Date Finish Picking') == $this->get('Delivery Note Date Finish Packing')) {
                                return '<i class="fa fa-arrow-right" aria-hidden="true"></i>';

                            } else {
                                return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));

                            }
                        } else {
                            return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                        }

                    } else {
                        return sprintf(
                            '<span title="%s">%s</span>', $this->get('Delivery Note Number Picked Items').'/'.$this->get('Delivery Note Number To Pick Items'), percentage($this->get('Delivery Note Number Picked Items'), $this->get('Delivery Note Number To Pick Items'))
                        );
                    }


                }


                break;

            case 'Packed Percentage or Datetime':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Delivery Note Date Start Picking') == '') {
                        return '';
                    }


                    if ($this->get('Delivery Note Date Finish Picking') != '') {
                        return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 40) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {
                            return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                        } else {
                            return strftime("%e %b %y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                        }

                    } else {
                        return sprintf(
                            '<span title="%s">%s</span>', $this->get('Delivery Note Number Packed Items').'/'.$this->get('Delivery Note Number To Pick Items'), percentage($this->get('Delivery Note Number Packed Items'), $this->get('Delivery Note Number To Pick Items'))
                        );
                    }


                }


                break;


            case ('State'):
                switch ($this->data['Delivery Note State']) {


                    case 'Ready to be Picked':
                        return _('Ready to be picked');
                        break;
                    case 'Picker Assigned':
                        return _('Picker assigned');
                        break;
                    case 'Picking':
                        return _('Picking');
                        break;
                    case 'Picked':
                        return _('Picked');
                        break;
                    case 'Packing':
                        return _('Packing');
                        break;
                    case 'Packed':
                        return _('Packed');
                        break;
                    case 'Approved':
                        return _('Approved');
                        break;
                    case 'Dispatched':
                        if ($this->data['Delivery Note Dispatch Method'] == 'Collection') {
                            return _('Collected');

                        } else {
                            return _('Dispatched');

                        }

                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Cancelled to Restock':
                        return _('Cancelled to restock');
                        break;
                    case 'Packed Done':
                        return _('Packed & Closed');
                        break;
                    default:
                        return $this->data['Delivery Note State'];
                        break;
                }
                break;
            case ('Abbreviated State'):
                switch ($this->data['Delivery Note State']) {


                    case 'Ready to be Picked':
                        return _('Waiting');
                        break;
                    case 'Picker Assigned':
                        return _('Picker assigned');
                        break;
                    case 'Picking':
                        return _('Picking');
                        break;
                    case 'Picked':
                        return _('Picked');
                        break;
                    case 'Packing':
                        return _('Packing');
                        break;
                    case 'Packed':
                        return _('Packed');
                        break;
                    case 'Approved':
                        return _('Approved');
                        break;
                    case 'Dispatched':
                        if ($this->data['Delivery Note Dispatch Method'] == 'Collection') {
                            return _('Collected');
                        } else {
                            return _('Dispatched');
                        }
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Cancelled to Restock':
                        return _('Cancelled to restock');
                        break;
                    case 'Packed Done':
                        return _('Packed & Closed');
                        break;
                    default:
                        return $this->data['Delivery Note State'];
                        break;
                }
                break;


            case 'Order Date Placed':
            case 'Date Created':

                return strftime("%e %b %y", strtotime($this->data['Delivery Note '.$key].' +0:00'));
                break;
            case 'Order Datetime Placed':

                return strftime("%e %b %y %H:%M %Z", strtotime($this->data['Delivery Note Order Date Placed'].' +0:00'));
                break;
            case('Date'):

                return strftime("%e %b %y", strtotime($this->data['Delivery Note Date'].' +0:00'));

                break;
            case('Creation Date'):
                return strftime("%e %b %y %H:%M %Z", strtotime($this->data['Delivery Note Date Created'].' +0:00'));
                break;
            case('Start Picking Datetime'):
            case('Finish Picking Datetime'):
            case('Start Packing Datetime'):
            case('Finish Packing Datetime'):
            case('Done Approved Datetime'):
            case('Dispatched Approved Datetime'):
            case('Dispatched Datetime'):
            case ('Cancelled Datetime'):
                $key = 'Date '.preg_replace('/ Datetime/', '', $key);


                if ($this->data["Delivery Note $key"] == '') {
                    return '';
                }

                return strftime(
                    "%e %b %y %H:%M", strtotime($this->data["Delivery Note $key"].' +0:00')
                );
                break;

            case('Estimated Weight'):
                include_once 'utils/natural_language.php';

                return weight($this->data['Delivery Note Estimated Weight']);
                break;

            case('Weight Details'):
                include_once 'utils/natural_language.php';

                if ($this->data['Delivery Note Weight Source'] == 'Given') {


                    if ($this->data['Delivery Note Estimated Weight'] < 1) {
                        $estimated_weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 3
                        );
                    } else {
                        $estimated_weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 0
                        );
                    }

                    $estimated_weight = "&#8494;".$estimated_weight;


                    return weight($this->data['Delivery Note Weight']).' <span style="font-style: italic" class="very_discreet">'.$estimated_weight.'</span>';
                } else {
                    if ($this->data['Delivery Note Estimated Weight'] < 1) {
                        $weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 3
                        );
                    } else {
                        $weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 0
                        );
                    }

                    return "&#8494;".$weight;
                }
                break;

            case('Weight'):
                include_once 'utils/natural_language.php';

                if ($this->data['Delivery Note Weight Source'] == 'Given') {
                    return weight($this->data['Delivery Note Weight']);
                } else {
                    if ($this->data['Delivery Note Estimated Weight'] < 1) {
                        $weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 3
                        );
                    } else {
                        $weight = weight(
                            $this->data['Delivery Note Estimated Weight'], 'Kg', 0
                        );
                    }

                    return "&#8494;".$weight;
                }
                break;

            case('Weight For Edit'):

                if ($this->data['Delivery Note Weight Source'] == 'Given') {
                    return $this->data['Delivery Note Weight'];
                } else {
                    return "";
                }
                break;


            case('Consignment'):


                if ($this->data['Delivery Note Dispatch Method'] != 'Collection') {


                    if ($this->data['Delivery Note Shipper Key'] != '') {
                        $shipper     = $this->get('Shipper');
                        $consignment = sprintf(
                            '<span class="link" onclick="change_view(\'warehouse/%d/shippers/%d\' title="%s")">%s</span>', $this->data['Delivery Note Warehouse Key'], $this->data['Delivery Note Shipper Key'], $shipper->get('Name'), $shipper->get('Code')

                        );

                        if ($this->data['Delivery Note Shipper Tracking'] != '') {
                            $consignment .= ' '.$this->data['Delivery Note Shipper Tracking'];
                        }

                    } else {
                        $consignment = '<span class="discreet italic">'._('Courier not set').'</span>';
                    }
                }else{
                    $consignment=_('Collected by customer');
                }

                return $consignment;
                break;

            case 'Number Parcels':

                if (!is_numeric($this->data['Delivery Note Number Parcels'])) {
                    return '';
                }


                switch ($this->data['Delivery Note Parcel Type']) {
                    case('Box'):
                        $parcel_type = sprintf('<i class="fal fa-archive" title="%s" aria-hidden="true"></i>\'', ngettext('box', 'boxes', $this->data['Delivery Note Number Parcels']));
                        break;
                    case('Pallet'):
                        $parcel_type = sprintf('<i class="fal fa-pallet" title="%s" aria-hidden="true"></i>', ngettext('pallet', 'pallets', $this->data['Delivery Note Number Parcels']));
                        break;
                    case('Envelope'):
                        $parcel_type = sprintf('<i class="fal fa-envelope" title="%s" aria-hidden="true"></i>\'', ngettext('envelope', 'envelopes', $this->data['Delivery Note Number Parcels']));
                        break;
                    case('Small Parcel'):
                        $parcel_type = sprintf('<i class="fal fa-hand-holding-box" title="%s" aria-hidden="true"></i>\'', ngettext('small parcel', 'small parcels', $this->data['Delivery Note Number Parcels']));
                        break;
                        break;


                    default:
                        $parcel_type = $this->data['Delivery Note Parcel Type'];
                }


                return number($this->data['Delivery Note Number Parcels']).' '.$parcel_type;

                break;

            case('Items Gross Amount'):
            case('Items Discount Amount'):
            case('Items Net Amount'):
            case('Items Tax Amount'):
            case('Refund Net Amount'):
            case('Charges Net Amount'):
            case('Shipping Net Amount'):

                return money($this->data['Delivery Note '.$key]);
                break;
            case('Fraction Packed'):
            case('Fraction Picked'):
                return percentage($this->data['Delivery Note'.' '.$key], 1);

            case 'Items Cost':
                $account = get_object('Account', 1);

                return money($this->data['Delivery Note '.$key], $account->get('Currency Code')).$account->get('Currency Code');
            case('Shipper'):

                return (!empty($this->data['Delivery Note Shipper Key']) ? get_object('Shipper', $this->data['Delivery Note Shipper Key']) : false);

                break;


            case 'Delivery Note Assigned Picker Name':
            case 'Delivery Note Assigned Packer Name':


                $staff_key = $this->get(preg_replace('/ Name$/', ' Key', $key));

                if (is_numeric($staff_key) and $staff_key > 0) {
                    $staff = get_object('Staff', $staff_key);

                    return $staff->get('Name');
                } else {
                    return '';
                }

                break;


        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Delivery Note '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }

    function update_totals() {


        $ordered          = 0;
        $picked           = 0;
        $packed           = 0;
        $to_pick          = 0;
        $ordered_parts    = 0;
        $estimated_weight = 0;
        $items_cost       = 0;

        // if($this->id){
        $sql = sprintf(
            'SELECT  sum(`Inventory Transaction Amount`) AS items_cost, sum(`Inventory Transaction Weight`) AS estimated_weight,   count(DISTINCT `Part SKU`) AS ordered_parts, sum(`Required`+`Given`) AS ordered, sum(`Required`+`Given`) AS ordered,sum(`Required`+`Given`-`Out of Stock`) AS to_pick, sum(`Picked`) AS picked,sum(`Packed`) AS packed   FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $ordered          = $row['ordered'];
                $picked           = $row['picked'];
                $packed           = $row['packed'];
                $to_pick          = $row['to_pick'];
                $ordered_parts    = $row['ordered_parts'];
                $estimated_weight = $row['estimated_weight'];
                $items_cost       = -1 * $row['items_cost'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Delivery Note Number Picked Items'  => $picked,
                'Delivery Note Number Packed Items'  => $packed,
                'Delivery Note Number Ordered Items' => $ordered,
                'Delivery Note Number To Pick Items' => $to_pick,
                'Delivery Note Number Ordered Parts' => $ordered_parts,
                'Delivery Note Estimated Weight'     => $estimated_weight,
                'Delivery Note Items Cost'           => $items_cost,

            )
        );


    }


    protected function create_replacement($dn_data, $transactions) {


        $base_data = $this->base_data();

        $this->editor = $dn_data['editor'];
        unset($dn_data['editor']);

        foreach ($dn_data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Delivery Note Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);


            $feedback = array();

            foreach ($transactions as $tansaction_key => $transaction_data) {


                if ($transaction_data['type'] == 'itf') {

                    $sql = sprintf(
                        'SELECT * FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P  ON (ITF.`Part SKU`=P.`Part SKU`)     WHERE `Inventory Transaction Key`=%d  ', $transaction_data['id']
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $part         = get_object('part', $row['Part SKU']);
                            $location_key = $part->get_picking_location_key();

                            $weight   = $transaction_data['amount'] * $part->get('Part Package Weight');
                            $required = $transaction_data['amount'];
                            $given    = 0;
                            list($supplier_key, $supplier_part_key, $supplier_part_historic_key) = $part->get_stock_supplier_data();


                            $picking_note = $row['Picking Note'];

                            $sql = sprintf(
                                "INSERT INTO `Inventory Transaction Fact`  (
					`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Inventory Transaction Fact Delivery 2 Alpha Code`,`Picking Note`,

					`Inventory Transaction Weight`,`Date Created`,`Date`,`Delivery Note Key`,`Part SKU`,`Location Key`,

					`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,

					`Metadata`,`Note`,`Supplier Product ID`,`Supplier Product Historic Key`,`Supplier Key`,`Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`)
					VALUES (
					%s,%s,%s,%s,
					%f,%s,%s,%d,%s,%d,
					%s,%s,%.2f,%f,%f,%f,

					%s,%s,%d,%d,%d,%d,%s) ", "'Info'", "'OIP'", prepare_mysql($this->data['Delivery Note Address Country 2 Alpha Code']), prepare_mysql($picking_note),

                                $weight, prepare_mysql($this->get('Delivery Note Date')), prepare_mysql($this->get('Delivery Note Date')), $this->id, prepare_mysql($part->id), $location_key,


                                0, "'Order In Process'", 0, $required, $given, 0,


                                prepare_mysql($this->data['Delivery Note Metadata']), prepare_mysql(''),

                                $supplier_part_key,

                                $supplier_part_historic_key,

                                $supplier_key, $row['Map To Order Transaction Fact Key'], prepare_mysql($row['Map To Order Transaction Fact Metadata'])
                            );


                            //  print $sql;

                            $this->db->exec($sql);

                            $replacement_itf = $this->db->lastInsertId();


                            $_feedback        = $transaction_data['feedback'];
                            $_feedback['itf'] = $replacement_itf;
                            $feedback[]       = $_feedback;


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
            }

            $account = get_object('Account', 1);


            require_once 'utils/new_fork.php';

            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'       => 'feedback',
                'feedback'   => $feedback,
                'user_key'   => $this->editor['User Key'],
                'parent'     => 'Replacement',
                'parent_key' => $this->id,
                'store_key'  => $this->get('Store Key'),
                'editor'     => $this->editor
            ), $account->get('Account Code'), $this->db
            );


            $this->update_totals();


        } else {
            exit ("$sql \n Error can not create dn header");
        }

        return $this;


    }


    //  New methods

    function update_inventory_transaction_fact($otf_key, $quantity_to_sell, $bonus_quantity) {

        $date = gmdate("Y-m-d H:i:s");

        $sql = sprintf(
            "SELECT `Inventory Transaction Key`,`Map To Order Transaction Fact Metadata`,`Part SKU` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d AND `Map To Order Transaction Fact Key`=%d ", $this->id, $otf_key
        );


        $transactions = 0;
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $transactions++;

                $metadata = preg_split('/\;/', $row['Map To Order Transaction Fact Metadata']);
                $ratio    = $metadata[1];
                $part     = get_object('Part', $row['Part SKU']);

                $required = $quantity_to_sell * $ratio;
                $given    = $bonus_quantity * $ratio;


                $weight = $part->get('Part Package Weight') * ($required + $given);

                $sql = sprintf(
                    "UPDATE `Inventory Transaction Fact` SET `Required`=%f,`Given`=%f,`Inventory Transaction Weight`=%f WHERE `Inventory Transaction Key`=%d ", $required, $given, $weight,

                    $row['Inventory Transaction Key']

                );


                $this->db->exec($sql);


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($transactions == 0) {

            $sql = sprintf(
                'SELECT OTF.`Product ID`,`Product Package Weight`,`Order Bonus Quantity`,`Order Quantity`,`Order Bonus Quantity`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PH  ON (OTF.`Product Key`=PH.`Product Key`)  LEFT JOIN `Product Dimension` P  ON (PH.`Product ID`=P.`Product ID`)     WHERE `Order Transaction Fact Key`=%d ',
                $otf_key
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    $this->create_inventory_transaction_fact_item(
                        $row['Product ID'], $row['Order Transaction Fact Key'], $row['Order Quantity'] + $row['Order Bonus Quantity'], $row['Order Bonus Quantity'], $date
                    );
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {


            case 'Delivery Note State':

                $this->update_state($value, $options, $metadata);
                break;
            case 'Delivery Note Shipper Key':

                $this->update_field($field, $value, $options);

                $shipper = $this->get('Shipper');

                if ($shipper) {
                    $this->update_metadata = array(
                        'class_html' => array(
                            'Shipper_Code' => $shipper->get('Code'),
                            'Shipper_Name' => $shipper->get('Name'),
                            'Consignment'  => $this->get('Consignment')

                        ),
                        'title'      => array(
                            'Shipper_Code' => $shipper->get('Name'),

                        ),
                    );

                    $shipper->update_shipper_usage();
                } else {

                    $this->fast_update(array('Delivery Note Shipper Tracking' => ''));
                    $this->update_metadata = array(
                        'class_html' => array(
                            'Shipper_Code' => _('Courier not set'),
                            'Shipper_Name' => '',
                            'Consignment'  => $this->get('Consignment')

                        ),
                        'title'      => array(
                            'Shipper_Code' => ''

                        ),
                    );
                }


                break;
            case 'Delivery Note Shipper Tracking':

                $this->update_field($field, $value, $options);
                $shipper = $this->get('Shipper');

                if ($shipper) {
                    $this->update_metadata = array(
                        'class_html' => array(
                            'Shipper_Code' => $shipper->get('Code'),
                            'Shipper_Name' => $shipper->get('Name'),
                            'Consignment'  => $this->get('Consignment')

                        ),
                        'title'      => array(
                            'Shipper_Code' => $shipper->get('Name'),

                        ),
                    );
                }

                break;

            case 'Delivery Note Parcel Type':
            case 'Delivery Note Number Parcels':

                $this->update_field($field, $value, $options);
                $this->update_metadata = array(
                    'class_html' => array(
                        'Delivery_Note_Number_Parcels' => $this->get('Number Parcels'),

                    ),
                );

                break;

            case 'Delivery Note Weight':

                $this->update_field('Delivery Note Weight', $value, $options);
                $this->update_field('Delivery Note Weight Source', 'Given', $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Weight_Details' => $this->get('Weight Details'),

                    ),
                );


                break;

            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function update_state($value, $options = '{}', $metadata = array()) {

        include_once 'utils/new_fork.php';


        $options = json_decode($options, true);
        if (!empty($options['date'])) {
            $date = $options['date'];
        } else {
            $date = gmdate('Y-m-d H:i:s');
        }
        $account = get_object('Account', 1);


        $operations = array();

        switch ($value) {


            case 'Ready to be Picked':

                if ($this->get('State Index') != 20) {
                    return;
                }

                $this->update_field(
                    'Delivery Note Date Start Picking', '', 'no_history'
                );
                //$this->update_field('Supplier Delivery Estimated Receiving Date', '', 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );


                $operations = array(
                    'delete_operations',
                    'start_picking_operations',

                );


                break;

            case 'Picking':

                if ($this->get('State Index') != 10) {
                    return;
                }


                if ($this->get('Delivery Note Date Start Picking') == '') {
                    $this->fast_update(
                        array('Delivery Note Date Start Picking' => $date)
                    );
                }
                $this->fast_update(
                    array(
                        'Delivery Note Date Finish Picking' => '',
                        'Delivery Note State'               => $value

                    )
                );


                $operations = array(
                    'delete_operations',
                    'undo_picking_operations',
                    'fast_track_packing_operations',

                );

                break;

            case 'Picked':

                if ($this->get('State Index') > 30 or $this->get('State Index') < 10) {
                    return;
                }

                $this->fast_update(

                    array(
                        'Delivery Note Date Finish Picking' => $date,
                        'Delivery Note State'               => $value
                    )

                );


                break;

            case 'Packing':


                if ($this->get('State Index') >= 20 or $this->get('State Index') <= 70) {

                    if ($this->data['Delivery Note Date Start Packing'] == '') {
                        $this->update_field(
                            'Delivery Note Date Start Packing', $date, 'no_history'
                        );
                    }


                    $this->update_field(
                        'Delivery Note State', $value, 'no_history'
                    );
                }

                break;
            case 'Packed':

                if ($this->get('State Index') > 70 or $this->get('State Index') < 10) {
                    return;
                }
                $this->fast_update(
                    array(
                        'Delivery Note Date Finish Packing' => $date,
                        'Delivery Note State'               => $value
                    )
                );

                if ($this->data['Delivery Note Type'] == 'Order') {
                    $order         = get_object('order', $this->data['Delivery Note Order Key']);
                    $order->editor = $this->editor;
                    $order->update_totals();
                }

                break;


            case 'Packed Done':

                if ($this->get('State Index') == 80) {
                    $this->error = true;
                    $this->msg   = 'Delivery note already closed';

                    return;
                }

                if ($this->get('State Index') > 70 or $this->get('State Index') < 70) {
                    $this->error = true;
                    $this->msg   = 'Delivery note must be fully packed before close it';

                    return;
                }
                $this->update_field('Delivery Note Date Done Approved', $date, 'no_history');
                $this->update_field('Delivery Note State', $value, 'no_history');
                $this->update_field('Delivery Note Approved Done', 'Yes', 'no_history');
                $this->update_field('Delivery Note Date', $date, 'no_history');

                $this->update_totals();
                if ($this->data['Delivery Note Type'] == 'Order') {


                    // todo make it work for multiple parts

                    $sql = sprintf(
                        'SELECT sum(`Packed`) as `Packed`,sum(`Required`) as `Required` ,sum(`Given`) as `Given`, `Map To Order Transaction Fact Key` FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d  group by `Map To Order Transaction Fact Key` ',
                        $this->id
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $to_pack = $row['Required'] + $row['Given'];

                            if ($to_pack == 0) {
                                $ratio_of_packing = 1;
                            } else {
                                $ratio_of_packing = $row['Packed'] / $to_pack;
                            }

                            // todo make get  `Order Transaction Amount` and do it properly to have exact cents

                            $otf = $row['Map To Order Transaction Fact Key'];
                            $sql = 'UPDATE `Order Transaction Fact`  SET  `Order Transaction Metadata`=JSON_SET(`Order Transaction Metadata`,\'$.ota_bk\',`Order Transaction Amount`) WHERE `Order Transaction Fact Key`=? ';
                            $this->db->prepare($sql)->execute([$otf]);


                            $sql = sprintf(
                                'UPDATE `Order Transaction Fact`  SET 
                            `Delivery Note Quantity`=ROUND((`Order Quantity`+`Order Bonus Quantity`)*%f ,8),
                             `No Shipped Due Out of Stock`=ROUND((`Order Quantity`+`Order Bonus Quantity`)*(1-%f),8),
                            `Order Transaction Out of Stock Amount`=`Order Transaction Amount`*(1-%f) ,
                               `Order Transaction Amount`=`Order Transaction Amount`*%f 
                                     WHERE `Order Transaction Fact Key`=%d ', $ratio_of_packing, $ratio_of_packing, $ratio_of_packing, $ratio_of_packing, $otf
                            );


                            $this->db->exec($sql);


                        }
                    }

                    $history_data = array(
                        'History Abstract' => _('Delivery note packed and closed'),
                        'History Details'  => '',
                    );


                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                    $order         = get_object('Order', $this->get('Delivery Note Order Key'));
                    $order->editor = $this->editor;

                    $order->update_state('PackedDone', json_encode(array('date' => $date)));
                    $order->update_totals();


                } else {


                    $history_data = array(
                        'History Abstract' => _('Replacement packed and closed'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                }


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'              => 'delivery_note_packed_done',
                    'delivery_note_key' => $this->id,
                    'customer_key'      => $this->get('Delivery Note Customer Key'),
                ), $account->get('Account Code')
                );

                $operations = array(
                    'undo_packed_done_operations',


                );

                break;


            case 'Undo Packed Done':
                if ($this->get('State Index') == 70) {
                    $this->error = true;
                    $this->msg   = 'Delivery note already open';

                    return;
                }


                if (in_array(
                    $this->data['Delivery Note Type'], array(
                    'Replacement & Shortages',
                    'Replacement',
                    'Shortages'
                )
                )) {

                    if (!($this->get('State Index') == 80 or $this->get('State Index') == 90)) {
                        $this->error = true;
                        $this->msg   = 'Replacement note must be closed or approved to dispatch';

                        return;
                    }

                } else {
                    if ($this->get('State Index') != 80) {
                        $this->error = true;
                        $this->msg   = 'Delivery note must be closed.';

                        return;
                    }

                }


                $this->update_field('Delivery Note Date Done Approved', '', 'no_history');
                $this->update_field('Delivery Note State', 'Packed', 'no_history');
                $this->update_field('Delivery Note Approved Done', 'No', 'no_history');
                $this->update_field('Delivery Note Date', $date, 'no_history');
                $this->update_field('Delivery Note Date Dispatched Approved', '', 'no_history');


                $this->update_totals();
                if ($this->data['Delivery Note Type'] == 'Order') {


                    // todo make it work for multiple parts


                    $sql = sprintf(
                        'SELECT `Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Transaction Out of Stock Amount`,`Packed`,`Required`,`Given`, `Out of Stock`,`No Authorized`,`Not Found`,`No Picked Other`,  `Map To Order Transaction Fact Key` ,`Order Transaction Metadata`
                          FROM `Inventory Transaction Fact` ITF left join `Order Transaction Fact`  on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) WHERE  ITF.`Delivery Note Key`=%d ', $this->id
                    );

                    //print "$sql\n";

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $metadata = json_decode($row['Order Transaction Metadata'], true);

                            if (isset($metadata['ota_bk'])) {
                                $order_transaction_amount_backup = $metadata['ota_bk'];
                            } else {
                                $order_transaction_amount_backup = $row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'];
                            }

                            $otf = $row['Map To Order Transaction Fact Key'];


                            $sql = 'UPDATE `Order Transaction Fact` SET `Delivery Note Quantity`=0 ,`No Shipped Due Out of Stock`=0,`Order Transaction Out of Stock Amount`=0 ,`Order Transaction Amount`=? WHERE `Order Transaction Fact Key`=? ';
                            $this->db->prepare($sql)->execute(
                                [
                                    $order_transaction_amount_backup,
                                    $otf
                                ]
                            );


                        }
                    }

                    $history_data = array(
                        'History Abstract' => _('Delivery note opened'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                    $order         = get_object('Order', $this->get('Delivery Note Order Key'));
                    $order->editor = $this->editor;
                    $order->update_totals();
                    $order->update(array('Order State' => 'Undo PackedDone'));
                    $order->update_totals();


                } else {

                    $history_data = array(
                        'History Abstract' => _('Replacement opened'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                }


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'              => 'delivery_note_packed_done',
                    'delivery_note_key' => $this->id,
                    'customer_key'      => $this->get('Delivery Note Customer Key'),
                ), $account->get('Account Code')
                );

                $operations = array(
                    'packed_done_operations',
                    'cancel_operations',

                );

                break;

            case 'Invoice Deleted':

                if ($this->get('State Index') != 90) {
                    return;
                }
                $this->update_field('Delivery Note Date Dispatched Approved', '', 'no_history');
                $this->update_field('Delivery Note State', 'Packed Done', 'no_history');


                $sql = sprintf(
                    "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=0 WHERE `Delivery Note Key`=%d ", $this->id
                );
                //print "$sql\n";
                $this->db->exec($sql);


                break;

            case 'Approved':

                if ($this->get('State Index') != 80 or in_array(
                        $this->data['Delivery Note Type'], array(
                        'Replacement & Shortages',
                        'Replacement',
                        'Shortages'
                    )
                    )) {
                    return;
                }
                $this->update_field('Delivery Note Date Dispatched Approved', $date, 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );


                $sql = sprintf("SELECT `Map To Order Transaction Fact Key` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d  GROUP BY `Map To Order Transaction Fact Key` ", $this->id);


                if ($result3 = $this->db->query($sql)) {
                    foreach ($result3 as $row3) {

                        $sql = sprintf(
                            "SELECT `Invoice Currency Exchange Rate`,`Order Transaction Fact Key`,`Order Transaction Amount`,`Delivery Note Quantity` FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d ", $row3['Map To Order Transaction Fact Key']
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {


                                $itf_transfer_factor = array();
                                $sum_itfs            = 0;

                                $sql = sprintf(
                                    'SELECT `Inventory Transaction Key`,`Inventory Transaction Quantity`,`Part Cost in Warehouse` FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Map To Order Transaction Fact Key`=%d ',
                                    $row['Order Transaction Fact Key']
                                );

                                if ($result2 = $this->db->query($sql)) {
                                    foreach ($result2 as $row2) {
                                        $itf_transfer_factor[$row2['Inventory Transaction Key']] = $row2['Part Cost in Warehouse'] * $row2['Inventory Transaction Quantity'];
                                        $sum_itfs                                                += $row2['Part Cost in Warehouse'] * $row2['Inventory Transaction Quantity'];
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }


                                $number_of_itf = count($itf_transfer_factor);

                                if ($number_of_itf == 1) {
                                    foreach ($itf_transfer_factor as $key => $value) {
                                        $itf_transfer_factor[$key] = 1;
                                    }
                                } else {

                                    if ($sum_itfs == 0 and $number_of_itf > 0) {
                                        foreach ($itf_transfer_factor as $key => $value) {
                                            $itf_transfer_factor[$key] = 1 / $number_of_itf;
                                        }
                                    } else {
                                        foreach ($itf_transfer_factor as $key => $value) {
                                            $itf_transfer_factor[$key] = $value / $sum_itfs;
                                        }
                                    }
                                }

                                $amount_in = $row['Invoice Currency Exchange Rate'] * $row['Order Transaction Amount'];

                                //print_r($itf_transfer_factor);

                                foreach ($itf_transfer_factor as $key => $value) {
                                    $sql = sprintf(
                                        "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=%f WHERE `Inventory Transaction Key`=%d ", $amount_in * $value, $key
                                    );

                                    $this->db->exec($sql);

                                }


                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    }


                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $history_data = array(
                    'History Abstract' => _('Delivery note approved for dispatch'),
                    'History Details'  => '',
                );

                $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                break;


            case 'Replacement Approved':

                if ($this->get('State Index') != 80) {
                    return;
                }
                $this->update_field('Delivery Note Date Dispatched Approved', $date, 'no_history');
                $this->update_field('Delivery Note State', 'Approved', 'no_history');


                break;
            case 'un_dispatch':

                if ($this->get('State Index') != 100) {
                    return;
                }

                $order         = get_object('Order', $this->data['Delivery Note Order Key']);
                $order->editor = $this->editor;


                if ($order->get('Order Invoice Key')) {
                    $value = 'Approved';
                } else {
                    $value = 'Packed Done';
                }


                $this->update_field('Delivery Note Date Dispatched', '', 'no_history');
                $this->update_field('Delivery Note Date', $date, 'no_history');
                $this->update_field('Delivery Note State', $value, 'no_history');


                $order->update(array('Order State' => 'un_dispatch'));

                $note = _('Delivery note un dispatched');


                if ($metadata['note'] != '') {
                    $note .= '. '.ucfirst($metadata['note']);
                }

                $history_data = array(
                    'History Abstract' => $note,
                    'History Details'  => '',
                );

                $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'              => 'delivery_note_un_dispatched',
                    'user_key'          => $this->editor['User Key'],
                    'delivery_note_key' => $this->id,
                    'note'              => $metadata['note']

                ), $account->get('Account Code')
                );


                break;
            case 'Dispatched':

                if ($this->data['Delivery Note Type'] == 'Order') {
                    if ($this->get('State Index') != 90) {
                        return;
                    }
                } else {
                    if (!($this->get('State Index') == 80 or $this->get('State Index') == 90)) {
                        return;
                    }
                }


                $this->update_field('Delivery Note Date Dispatched', $date, 'no_history');
                $this->update_field('Delivery Note Date', $date, 'no_history');
                $this->update_field('Delivery Note State', $value, 'no_history');

                $order         = get_object('Order', $this->data['Delivery Note Order Key']);
                $order->editor = $this->editor;
                if ($this->data['Delivery Note Type'] == 'Order') {


                    $order->update_state('Dispatched', json_encode(array('date' => $date)), array('delivery_note_key' => $this->id));


                    $history_data = array(
                        'History Abstract' => _('Delivery note dispatched'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                } else {


                    $history_data = array(
                        'History Abstract' => _('Replacement note dispatched'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                }


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'              => 'delivery_note_dispatched',
                    'user_key'          => $this->editor['User Key'],
                    'delivery_note_key' => $this->id,

                ), $account->get('Account Code')
                );

                break;
            case 'Cancelled':

                if ($this->get('State Index') >= 80) {
                    $this->error = true;
                    $this->msg   = 'Delivery note can not be cancelled if id closed or dispatched';

                    return;
                }


                // todo before cancel the picked stock has to go some cleaver way back to locations, (making fork update_cancelled_delivery_note_products_sales_data section in delivery_note_cancelled fork obsolete? )


                $returned_part_locations = array();

                $order         = get_object('Order', $this->data['Delivery Note Order Key']);
                $order->editor = $this->editor;

                if ($order->get('Order State') >= 90) {
                    return;
                }


                $this->fast_update(
                    array(
                        'Delivery Note Date Cancelled' => $date,
                        'Delivery Note State'          => $value
                    )
                );


                // todo: Manually choose where the stock will go


                $sql = sprintf(
                    'SELECT * FROM `Inventory Transaction Fact`  WHERE  `Delivery Note Key`=%d  AND `Inventory Transaction Section`="OIP"  ', $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $sql = sprintf('UPDATE `Inventory Transaction Fact`  SET   `Inventory Transaction Type`="FailSale" , `Inventory Transaction Section`="NoDispatched"  WHERE `Inventory Transaction Key`=%d  ', $row['Inventory Transaction Key']);
                        $this->db->exec($sql);


                    }
                }


                $sql = sprintf(
                    'SELECT * FROM `Inventory Transaction Fact`  WHERE  `Delivery Note Key`=%d  AND `Inventory Transaction Type`="Sale" AND `Inventory Transaction Section`="Out"  ', $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $sql = sprintf(
                            'UPDATE `Inventory Transaction Fact`  SET  `Inventory Transaction Record Type`="Movement",  `Inventory Transaction Type`="FailSale" , `Inventory Transaction Section`="NoDispatched"  WHERE `Inventory Transaction Key`=%d  ',
                            $row['Inventory Transaction Key']
                        );
                        $this->db->exec($sql);


                        //print "$sql\n";

                        $location_key = $row['Location Key'];


                        $note = '';

                        $picker_key = $row['Picker Key'];


                        $sql = sprintf(
                            "INSERT INTO `Inventory Transaction Fact`  (
					`Inventory Transaction Record Type`,`Inventory Transaction Section`,
					`Inventory Transaction Fact Delivery 2 Alpha Code`,`Picking Note`,

					`Inventory Transaction Weight`,`Date Created`,`Date`,`Delivery Note Key`,`Part SKU`,`Location Key`,

					`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,

					`Metadata`,`Note`,`Supplier Product ID`,`Supplier Product Historic Key`,`Supplier Key`,`Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`,`Relations`,`Picker Key`)
					VALUES (
					%s,%s,%s,%s,
					%f,%s,%s,%d,%s,%d,
					%s,%s,%.2f,%f,%f,%f,

					%s,%s,%d,%d,%d,%d,%s,%d,%d) ", "'Movement'", "'NoDispatched'", prepare_mysql(''), prepare_mysql(''),

                            $row['Inventory Transaction Weight'], prepare_mysql($date), prepare_mysql($date), $this->id, prepare_mysql($row['Part SKU']), $location_key,


                            -1 * $row['Inventory Transaction Quantity'], "'Restock'", -1 * $row['Inventory Transaction Amount'], 0, 0, 0,


                            prepare_mysql($row['Metadata']), prepare_mysql($note),

                            $row['Supplier Product ID'],

                            $row['Supplier Product Historic Key'], $row['Supplier Key'], $row['Map To Order Transaction Fact Key'],

                            prepare_mysql($row['Map To Order Transaction Fact Metadata']), $row['Inventory Transaction Key'], $picker_key
                        );

                        // print "$sql\n";

                        $this->db->exec($sql);


                        $returned_part_locations[] = $row['Part SKU'].'_'.$row['Location Key'];

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if (in_array(
                    $this->data['Delivery Note Type'], array(
                                                         'Replacement & Shortages',
                                                         'Replacement',
                                                         'Shortages'
                                                     )
                )) {


                    $history_data = array(
                        'History Abstract' => _('Replacement cancelled'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                } else {


                    $history_data = array(
                        'History Abstract' => _('Delivery note cancelled'),
                        'History Details'  => '',
                    );

                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


                    $order->update(array('Order State' => 'Delivery Note Cancelled'));


                }

                $_date = $this->data['Delivery Note Date Start Picking'];
                if ($_date == '') {
                    $_date = $this->data['Delivery Note Date Created'];
                }
                if ($_date == '') {
                    $_date = $date;


                }
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'                    => 'delivery_note_cancelled',
                    'date'                    => gmdate('Y-m-d', strtotime($_date.' +0:00')),
                    'returned_part_locations' => $returned_part_locations,
                    'customer_key'            => $this->get('Delivery Note Customer Key'),
                    'delivery_note_key'       => $this->id

                ), $account->get('Account Code')
                );


                break;

            default:
                exit('unknown state '.$value);
                break;
        }


        if ($this->data['Delivery Note Type'] == 'Replacement') {
            $order = get_object('Order', $this->get('Delivery Note Order Key'));
            $order->update_number_replacements();
        }


        $this->update_totals();


        $this->update_metadata = array(
            'class_html'  => array(
                'Delivery_Note_State'                       => $this->get('State'),
                'Delivery_Note_Dispatched_Date'             => '&nbsp;'.$this->get('Dispatched Date'),
                'Supplier_Delivery_Number_Dispatched_Items' => $this->get('Number Dispatched Items'),
                'Delivery_Note_Start_Picking_Datetime'      => '<i class="far fa-clock" aria-hidden="true"></i> '.$this->get('Start Picking Datetime'),
                'Delivery_Note_Start_Packing_Datetime'      => '<i class="far fa-clock" aria-hidden="true"></i> '.$this->get('Start Packing Datetime'),
                'Delivery_Note_Finish_Picking_Datetime'     => $this->get('Finish Picking Datetime'),
                'Delivery_Note_Finish_Packing_Datetime'     => $this->get('Finish Packing Datetime'),
                'Delivery_Note_Picked_Label'                => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),


                'Delivery_Note_Picked_Percentage_or_Datetime' => '&nbsp;'.$this->get('Picked Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Packed_Percentage_or_Datetime' => '&nbsp;'.$this->get('Packed Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Approved_Datetime'  => '&nbsp;'.$this->get('Dispatched Approved Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Datetime'           => '&nbsp;'.$this->get('Dispatched Datetime').'&nbsp;',
                'Items_Cost'                                  => $this->get('Items Cost'),


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );


    }

    function update_item($data) {


        switch ($data['field']) {
            case 'Picked':
                return $this->update_item_picked_quantity($data);
                break;
            case 'Out_of_stock':
                return $this->update_item_not_picked_quantity($data);
                break;
            case 'Packed':
                return $this->update_item_packed_quantity($data);
                break;
            default:

                break;
        }


    }


    function update_item_picked_quantity($data) {


        include_once('class.Location.php');
        include_once('class.PartLocation.php');
        include_once('utils/order_handing_functions.php');


        if ($this->get('State Index') >= 70) {
            $this->error = true;
            $this->msg   = 'delivery packed';

            return;
        }


        //  print_r($data);

        $date = gmdate('Y-m-d H:i:s');

        //$item_key        = $data['item_key'];
        $qty             = $data['qty'];
        $transaction_key = $data['transaction_key'];

        $sql = sprintf(
            'SELECT `Part Cost`,`Inventory Transaction Key`,ITF.`Part SKU`,`Picked`,`Packed`,`Required`,`Given`,`Location Key`,`Required`+`Given`-`Picked`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` AS pending,(`Required`+`Given`) AS quantity FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Inventory Transaction Key`=%d',
            $data['transaction_key']
        );

        //   print "$sql";


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);


                $transaction_value = $row['Part Cost'] * $qty;

                $to_pick = $row['pending'] + $row['Picked'];


                if ($qty > $to_pick) {
                    $this->error = true;
                    $this->msg   = 'Error, trying to pick more items than required';

                    return;
                }

                if ($qty < $row['Packed']) {
                    $this->error = true;
                    $this->msg   = 'Error, trying to set as picked '.$qty.' more items than packed '.$row['Packed'];

                    return;
                }


                //   $location = new Location($row['Location Key']);

                // $qty = $row['Picked'] + $qty;

                $sql = sprintf(
                    "UPDATE `Inventory Transaction Fact` SET  `Inventory Transaction Type`='Sale' ,`Inventory Transaction Section`='Out',`Picked`=%f,`Inventory Transaction Quantity`=%f,`Inventory Transaction Amount`=%f,`Date Picked`=%s,`Date`=%s ,`Picker Key`=%s WHERE `Inventory Transaction Key`=%d  ",
                    $qty, -1 * $qty, $transaction_value, prepare_mysql($date), prepare_mysql($date), prepare_mysql($data['picker_key']), $data['transaction_key']
                );

                $this->db->exec($sql);


                //   print $_pending;


                $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
                $part_location->update_stock();

                $pending = $to_pick - $qty;
                $picked  = $qty;

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_totals();


        $state = 'Picking';

        if ($this->get('Delivery Note Number Picked Items') == $this->get('Delivery Note Number To Pick Items')) {

            $state = 'Picked';

        }

        $this->update_state($state);


        $this->update_metadata = array(
            'state_index'                => $this->get('State Index'),
            'picked_quantity_components' => get_item_picked(
                $pending, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock'), $part_location->part->get('Part SKO Barcode'),
                $part_location->part->get('Part Reference'), $part_location->part->get('Part Reference'), $part_location->part->get('Part Reference'), base64_encode(
                    $part_location->part->get('Part Package Description').($part_location->part->get('Picking Note') != '' ? ' <span>('.$part_location->part->get('Picking Note').'</span>' : '')
                ), $part_location->part->get('Part Main Image Key')
            ),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'),

                $part_location->part->get('Part Current On Hand Stock'), $part_location->part->get('Part SKO Barcode'), $part_location->part->get('Part Distinct Locations'), $part_location->part->sku, $row['Inventory Transaction Key'], $this->id
            ),
            'pending'                    => $pending,

            'class_html' => array(
                'Delivery_Note_Picked_Label'                  => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),
                'Delivery_Note_Picked_Percentage_or_Datetime' => '&nbsp;'.$this->get('Picked Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Packed_Percentage_or_Datetime' => '&nbsp;'.$this->get('Packed Percentage or Datetime').'&nbsp;',
                'Delivery_Note_State'                         => $this->get('State')
            )
        );


        if ($this->get('State Index') >= 30) {
            global $smarty;
            if (isset($smarty)) {
                $smarty->assign('dn', $this);
                $this->update_metadata['class_html']['picking_options'] = $smarty->fetch('delivery_note.options.picking.tpl');
            }

        }


        return array(
            'transaction_key' => $transaction_key,
            'qty'             => $qty + 0
        );


    }

    function update_item_not_picked_quantity($data) {

        include_once('class.Location.php');
        include_once('class.PartLocation.php');


        if ($this->get('State Index') == 10) {
            $this->update_state('Picking');
        }


        //  print_r($data);

        $date = gmdate('Y-m-d H:i:s');

        //$item_key        = $data['item_key'];
        $qty             = $data['qty'];
        $transaction_key = $data['transaction_key'];

        $sql = sprintf(
            'SELECT `Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`,`Part Cost`,`Inventory Transaction Key`,ITF.`Part SKU`,`Picked`,`Required`,`Given`,`Location Key`,`Required`+`Given`-`Picked`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` AS pending,(`Required`+`Given`) AS quantity FROM `Inventory Transaction Fact` ITF  LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Inventory Transaction Key`=%d',
            $data['transaction_key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);


                $to_pick = $row['pending'] + $row['Picked'];


                if ($qty <= $row['pending']) {

                    $transaction_value = $row['Part Cost'] * $qty;

                    $sql = sprintf(
                        "UPDATE `Inventory Transaction Fact` SET `Out of Stock`=%f ,`Out of Stock Lost Amount`=%f ,`Date`=%s ,`Picker Key`=%s WHERE `Inventory Transaction Key`=%d  ", $qty, $transaction_value, prepare_mysql($date), prepare_mysql($data['picker_key']),
                        $data['transaction_key']
                    );

                    $this->db->exec($sql);


                    $pending = $to_pick - $qty;
                    $picked  = $qty;


                    $part_location             = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
                    $location_stock_icon_class = 'button ';
                    $stock_in_location         = sprintf(_('Stock in location: %s'), $part_location->get('Quantity On Hand'));

                    $stock_quantity_safe_limit = ceil($pending * 1.2);
                    if ($stock_quantity_safe_limit > 10) {
                        $stock_quantity_safe_limit;
                    }


                    if ($pending == 0) {
                        $picked_time = sprintf(_('Picked: %s'), strftime("%a %e %b %y %H:%M %Z", strtotime($date.' +0:00')));
                        $location    = sprintf('<i class="fa fa-fw fa-check super_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $picked_time);

                    } elseif ($part_location->get('Quantity On Hand') <= 0) {

                        if ($part_location->part->get('Part Current On Hand Stock') >= $pending) {
                            $location = sprintf('<i class="fa fa-fw fa-bookmark-o fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                        } else {
                            $location = sprintf('<i class="fa fa-fw fa-star-o error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                        }


                    } else {
                        if ($part_location->get('Quantity On Hand') < $pending) {
                            if ($part_location->part->get('Part Current On Hand Stock') >= $pending) {
                                $location = sprintf('<i class="fa fa-fw fa-bookmark-o fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                            } else {
                                if ($part_location->get('Quantity On Hand') < 1) {
                                    $location = sprintf('<i class="fa  fa-fw no_stock_location  fa-circle error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                                } else {
                                    $location = sprintf('<i class="fa fa-fw fa-star-half-o error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);
                                }


                            }

                        } else {
                            if ($part_location->get('Quantity On Hand') < $stock_quantity_safe_limit) {
                                $location = sprintf('<i class="fa fa-fw fa-star warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);
                            } else {
                                $location = sprintf('<i class="fa fa-fw fa-star success very_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);
                            }
                        }
                    }

                    $location .= sprintf(
                        '<span class="%s location"  location_key = "%d" >%s </span >', ($pending > 0 ? 'discreet' : ''), $part_location->location->id, $part_location->location->get('Code')
                    );


                    //=======

                    $picked = sprintf(
                        '<span class="picked_quantity_done %s">  
 <input class="picked_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_picked " aria-hidden="true"/></span><span data-settings=\'{"field": "Picked", "transaction_key":%d,"item_key":%d ,"on":1 }\' class="picked_quantity %s"  >
                    <input class="picked_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true">
                </span>', ($pending == 0 ? '' : 'hide'), number($picked), $row['Inventory Transaction Key'], $row['Part SKU'], ($pending != 0 ? '' : 'hide'), $picked, $picked, ''
                    );

                    //=========

                    $this->update_totals();


                    if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {
                        if ($this->get('State Index') == 20) {
                            $this->update_state('Picked');
                        }
                    }

                    $this->update_metadata = array(
                        'state_index'                => $this->get('State Index'),
                        'picked_quantity_components' => $picked,
                        'location_components'        => $location,
                        'pending'                    => $pending,

                        'class_html' => array(
                            'Delivery_Note_Picked_Label'              => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),
                            'Delivery_Note_Picked_Percentage_or_Date' => $this->get('Picked Percentage or Date')

                        )
                    );


                    return array(
                        'transaction_key' => $transaction_key,
                        'qty'             => $qty + 0
                    );


                } else {
                    $this->error = true;
                    $this->msg   = 'Trying to set as not picked more items than required';

                    return;

                }


            } else {
                $this->error = true;
                $this->msg   = 'Itf not found';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        //=========


    }

    function update_item_packed_quantity($data) {


        include_once('class.Location.php');
        include_once('class.PartLocation.php');
        include_once('utils/order_handing_functions.php');


        //  print_r($data);

        $date = gmdate('Y-m-d H:i:s');

        //$item_key        = $data['item_key'];
        $qty             = $data['qty'];
        $transaction_key = $data['transaction_key'];

        $sql = sprintf(
            'SELECT `Part Cost`,`Inventory Transaction Key`,ITF.`Part SKU`,`Picked`,`Packed`,`Required`,`Given`,`Location Key`,`Required`+`Given`-`Picked`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` AS pending,(`Required`+`Given`) AS quantity FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Inventory Transaction Key`=%d',
            $data['transaction_key']
        );

        //   print "$sql";


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $to_pick = $row['pending'] + $row['Picked'];


                if ($qty <= $to_pick) {

                    //   $location = new Location($row['Location Key']);

                    // $qty = $row['Picked'] + $qty;


                    if ($row['Picked'] < $qty) {
                        $updating_picking  = true;
                        $picked            = $qty;
                        $transaction_value = $picked * $row['Part Cost'];


                    } else {
                        $updating_picking = false;
                        $picked           = $row['Picked'];
                    }

                    $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);

                    if ($updating_picking) {
                        $sql = sprintf(
                            "UPDATE `Inventory Transaction Fact` SET  `Inventory Transaction Type`='Sale' ,`Inventory Transaction Section`='Out',`Picked`=%f,`Inventory Transaction Quantity`=%f,`Inventory Transaction Amount`=%f,`Date Picked`=%s WHERE `Inventory Transaction Key`=%d  ",
                            $picked, -1 * $picked, $transaction_value, prepare_mysql($date), $data['transaction_key']
                        );

                        $this->db->exec($sql);


                        $part_location->update_stock();
                    }

                    $sql = sprintf(
                        "UPDATE `Inventory Transaction Fact` SET  `Packed`=%f,`Date Picked`=%s,`Date`=%s ,`Packer Key`=%s WHERE `Inventory Transaction Key`=%d  ", $qty, prepare_mysql($date), prepare_mysql($date), prepare_mysql($data['packer_key']),
                        $data['transaction_key']
                    );

                    $this->db->exec($sql);


                    //   print $_pending;


                } else {
                    $this->error = true;
                    $this->msg   = 'Error, trying to pack more items than required';

                    return;

                }


                $pending         = $to_pick - $qty;
                $pending_picking = $to_pick - $picked;
                $packed          = $qty;


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_totals();

        $state = 'Packing';


        if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {
            $state = 'Packed';

        }


        $this->update_state($state);


        $this->update_metadata = array(
            'state_index'                => $this->get('State Index'),
            'picked_quantity_components' => get_item_picked(
                $pending_picking, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock'), $part_location->part->get('Part SKO Barcode'),
                $part_location->part->get('Part Reference'), $part_location->part->get('Part Package Description'), $part_location->part->get('Part Main Image Key')


            ),
            'packed_quantity_components' => get_item_packed(
                $pending, $row['Inventory Transaction Key'], $row['Part SKU'], $packed
            ),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'), $part_location->part->get('Part Current On Hand Stock'), $part_location->part->get('Part SKO Barcode'),
                $part_location->part->get('Part Distinct Locations'), $part_location->part->sku, $row['Inventory Transaction Key'], $this->id
            ),
            'pending'                    => $pending,

            'class_html' => array(
                'Delivery_Note_Picked_Label'                  => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),
                'Delivery_Note_Packed_Label'                  => ($this->get('State Index') == 40 ? _('Packing') : _('Packed')),
                'Delivery_Note_Picked_Percentage_or_Datetime' => $this->get('Picked Percentage or Datetime'),
                'Delivery_Note_Packed_Percentage_or_Datetime' => $this->get('Packed Percentage or Datetime')

            )
        );


        return array(
            'transaction_key' => $transaction_key,
            'qty'             => $qty + 0
        );


    }

    function delete($fix_mode = false) {

        $customer = get_object('Customer', $this->get('Delivery Note Customer Key'));


        include_once 'class.PartLocation.php';
        $parts_to_update_stock = array();

        $sql = sprintf(
            "SELECT `Part SKU`,`Location Key` FROM  `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d  AND `Inventory Transaction Type`='Order In Process'  ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $parts_to_update_stock[] = $row['Part SKU'].'_'.$row['Location Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "DELETE FROM  `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d  AND `Inventory Transaction Type`='Order In Process'  ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE   `Order Transaction Fact` SET  `Current Dispatching State`='In Process' ,  `Delivery Note Key`=NULL  WHERE `Delivery Note Key`=%d  AND `Current Dispatching State`='Ready to Pick'  ", $this->id
        );
        $this->db->exec($sql);


        foreach ($parts_to_update_stock as $part_to_update_stock) {
            $part_location = new PartLocation($part_to_update_stock);
            $part_location->update_stock();
        }

        $order         = get_object('Order', $this->get('Delivery Note Order Key'));
        $order->editor = $this->editor;


        $sql = sprintf(
            "DELETE FROM  `Delivery Note Dimension` WHERE `Delivery Note Key`=%d  ", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM  `Order Delivery Note Bridge` WHERE `Delivery Note Key`=%d  ", $this->id
        );
        $this->db->exec($sql);


        if (!$fix_mode) {

            if (in_array(
                $this->data['Delivery Note Type'], array(
                                                     'Replacement & Shortages',
                                                     'Replacement',
                                                     'Shortages'
                                                 )
            )) {
                $sql = sprintf(
                    "UPDATE `Order Post Transaction Dimension` SET `State`=%s  WHERE `Delivery Note Key`=%d   ", prepare_mysql('In Process'), $this->id
                );
                $this->db->exec($sql);


            } else {


                if ($order->get('Order State') != 'Cancelled') {
                    $order->update(
                        array(
                            'Order State' => 'Delivery_Note_deleted'
                        )
                    );
                }


            }
        }

        $customer->update_last_dispatched_order_key();

        $order->update_number_replacements();


        return 'orders/'.$order->get('Order Store Key').'/'.$order->id;

    }

    function consolidate($transactions) {


        $date = gmdate('Y-m-d H:i:s');

        foreach ($transactions as $transaction) {
            $sql = sprintf('SELECT `Part SKU`,`Required`,`Given`,`Map To Order Transaction Fact Key`,`Location Key` FROM `Inventory Transaction Fact` WHERE `Inventory Transaction Key`=%d AND `Delivery Note Key`=%d ', $transaction['transaction_key'], $this->id);

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    if ($transaction['qty'] == '') {
                        $transaction['qty'] = 0;
                    }

                    $part = get_object('Part', $row['Part SKU']);

                    $qty          = -1 * $transaction['qty'];
                    $cost         = $qty * $part->get('Part Cost in Warehouse');
                    $weight       = $transaction['qty'] * $part->get('Part Package Weight');
                    $out_of_stock = $row['Required'] + $row['Given'] + -$transaction['qty'];


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


                    $sql = sprintf(
                        'UPDATE  `Inventory Transaction Fact`  SET  
                                  `Date Picked`=%s ,`Date Packed`=%s ,`Date`=%s ,`Location Key`=%d ,`Inventory Transaction Type`=%s ,`Inventory Transaction Section`=%s ,
                                  `Inventory Transaction Quantity`=%f, `Inventory Transaction Amount`=%.3f,`Inventory Transaction Weight`=%f,
                                  `Picked`=%f,`Packed`=%f,`Out of Stock`=%f,
                                  `Picker Key`=%d,`Packer Key`=%d
                                  
                                  
                                  WHERE `Inventory Transaction Key`=%d ',

                        prepare_mysql($date_picked), prepare_mysql($date_packed), prepare_mysql($date), $transaction['location_key'], prepare_mysql($transaction_type), prepare_mysql($transaction_section), $qty, $cost, $weight, $transaction['qty'], $transaction['qty'],
                        $out_of_stock, $this->get('Delivery Note Assigned Picker Key'), $this->get('Delivery Note Assigned Packer Key'), $transaction['transaction_key']
                    );


                    $this->db->exec($sql);

                    $cost = 0;

                    $sql = sprintf('SELECT sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d ', $row['Map To Order Transaction Fact Key']);
                    if ($result2 = $this->db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            if ($row2['amount'] == '') {
                                $row2['amount'] = 0;
                            }

                            $cost = -1 * $row2['amount'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                    $sql = sprintf('UPDATE `Order Transaction Fact` SET `Cost Supplier`=%f  WHERE  `Order Transaction Fact Key`=%d', $cost, $row['Map To Order Transaction Fact Key']);
                    //print "$sql\n";
                    $this->db->exec($sql);

                    // todo: fork this
                    $part_location = get_object('Part_Location', $row['Part SKU'].'_'.$row['Location Key']);
                    $part_location->update_stock();


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }


        $this->update_field('Delivery Note Date Finish Picking', $date, 'no_history');
        $this->update_field('Delivery Note Date Finish Packing', $date, 'no_history');

        $this->update_field('Delivery Note Date', $date, 'no_history');

        $this->update_field('Delivery Note State', 'Packed');


        $this->update_totals();


        $operations = array(
            'cancel_operations',
            'packed_done_operations'
        );

        $this->update_metadata = array(
            'class_html'  => array(
                'Delivery_Note_State'                       => $this->get('State'),
                'Delivery_Note_Dispatched_Date'             => '&nbsp;'.$this->get('Dispatched Date'),
                'Supplier_Delivery_Number_Dispatched_Items' => $this->get('Number Dispatched Items'),
                'Delivery_Note_Start_Picking_Datetime'      => '<i class="far fa-clock" aria-hidden="true"></i> '.$this->get('Start Picking Datetime'),
                'Delivery_Note_Start_Packing_Datetime'      => '<i class="far fa-clock" aria-hidden="true"></i> '.$this->get('Start Packing Datetime'),
                'Delivery_Note_Finish_Picking_Datetime'     => $this->get('Finish Picking Datetime'),
                'Delivery_Note_Finish_Packing_Datetime'     => $this->get('Finish Packing Datetime'),
                'Delivery_Note_Picked_Label'                => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),

                'Delivery_Note_Packed_Done_Datetime' => '&nbsp;'.$this->get('Done Approved Datetime').'&nbsp;',


                'Delivery_Note_Picked_Percentage_or_Datetime' => '&nbsp;'.$this->get('Picked Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Packed_Percentage_or_Datetime' => '&nbsp;'.$this->get('Packed Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Approved_Datetime'  => '&nbsp;'.$this->get('Dispatched Approved Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Datetime'           => '&nbsp;'.$this->get('Dispatched Datetime').'&nbsp;',

                'Delivery_Note_State' => $this->get('State'),
                'Items_Cost'          => $this->get('Items Cost')


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );


    }

    function get_formatted_parcels() {

        if (!is_numeric($this->data['Delivery Note Number Parcels'])) {
            return '';
        }

        switch ($this->data['Delivery Note Parcel Type']) {
            case('Box'):
                $parcel_type = ngettext('box', 'boxes', $this->data['Delivery Note Number Parcels']);
                break;
            case('Pallet'):
                $parcel_type = ngettext('pallet', 'pallets', $this->data['Delivery Note Number Parcels']);
                break;
            case('Envelope'):
                $parcel_type = ngettext('envelope', 'envelopes', $this->data['Delivery Note Number Parcels']);
                break;
            case('Small Parcel'):
                $parcel_type = ngettext('small parcel', 'small parcels', $this->data['Delivery Note Number Parcels']);
                break;
            case('Other'):
                $parcel_type = ngettext('container (other)', 'containers (other)', $this->data['Delivery Note Number Parcels']);
                break;

            case('None'):
                return;
                break;

            default:
                $parcel_type = $this->data['Delivery Note Parcel Type'];
        }


        return number($this->data['Delivery Note Number Parcels']).' '.$parcel_type;
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Delivery Note Number Parcels':
                $label = _('number parcels');
                break;
            case 'Delivery Note Parcel Type':
                $label = _('parcel type');
                break;
            case 'Delivery Note Weight':
                $label = _('weight');
                break;


            default:
                $label = $field;

        }

        return $label;

    }


}


?>
