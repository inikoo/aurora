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

    function DeliveryNote($arg1 = false, $arg2 = false, $arg3 = false, $arg4 = false) {


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
        if (preg_match('/(create|new).*(replacements?|shortages?)/i', $arg1)) {
            $this->create_replacement($arg2, $arg3, $arg4);

            return;
        }
        if (preg_match('/create|new/i', $arg1)) {
            $this->create($arg2, $arg3, $arg4);

            return;
        }
        //    if(preg_match('/find/i',$arg1)){
        //  $this->find($arg2,$arg1);
        //  return;
        // }
        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Delivery Note Dimension` WHERE  `Delivery Note Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Delivery Note Dimension` WHERE  `Delivery Note Public ID`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }
        //   print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Delivery Note Key'];
        }


    }


    protected function create($dn_data, $order) {




        $base_data = $this->base_data();

        foreach ($dn_data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values (';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/xxxxxx/i', $key)) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Delivery Note Dimension` $keys  $values";



        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
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
                        "UPDATE  `Order Transaction Fact` SET `Estimated Weight`=%f,`Order Last Updated Date`=%s,`Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s WHERE `Order Transaction Fact Key`=%d",
                        $estimated_weight, prepare_mysql($this->data['Delivery Note Date Created']), prepare_mysql($this->data['Delivery Note ID'])


                        , $this->data['Delivery Note Key'], prepare_mysql($this->data['Delivery Note Country 2 Alpha Code']), $row['Order Transaction Fact Key']

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
                        "UPDATE  `Order No Product Transaction Fact` SET `Delivery Note Date`=%s,`Delivery Note Key`=%d WHERE `Order No Product Transaction Fact Key`=%d",
                        prepare_mysql($this->data['Delivery Note Date Created']), $this->id, $row['Order No Product Transaction Fact Key']

                    );
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                'SELECT OTF.`Product Code`,OTF.`Order Quantity`,`No Shipped Due No Authorized`,OTF.`Product ID`,`Product Package Weight`,`Order Quantity`,`Supplier Metadata`,`Order Bonus Quantity`,`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PH  ON (OTF.`Product Key`=PH.`Product Key`)  LEFT JOIN `Product Dimension` P  ON (PH.`Product ID`=P.`Product ID`)
		WHERE `Order Key`=%d  AND `Current Dispatching State` IN ("Submitted by Customer","In Process")  ', $order->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    //     print_r($row);

                    $items_to_dispatch = $row['Order Quantity'] + $row['Order Bonus Quantity'] - $row['No Shipped Due No Authorized'];


                    $this->create_inventory_transaction_fact_item(
                        $row['Product ID'], $row['Order Transaction Fact Key'], $items_to_dispatch, $this->get('Delivery Note Date'), $row['Supplier Metadata'], $row['Order Bonus Quantity']
                    );


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $this->update_totals();


        } else {
            exit ("$sql \n Error can not create dn header");
        }

        return $this;


    }

    function create_inventory_transaction_fact_item($product_id, $map_to_otf_key, $to_sell_quantity, $date, $supplier_metadata_array, $bonus_qty) {


        $product = new Product('id', $product_id);

        $part_list = $product->get_parts_data();

        $state = 'Ready to Pick';

        $sql   = sprintf(
            "UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s WHERE `Order Transaction Fact Key`=%d  ", prepare_mysql($state),

            $map_to_otf_key
        );
        $this->db->exec($sql);

        $part_index = 0;
        $location_index=0;
        //  $debug_txt=sprintf("creating itf %s %s",$product->data['Product Code'],$sql);
        //  $xsql=sprintf("insert into debugtable (`text`,`date`) values (%s,NOW())",prepare_mysql($debug_txt));mysql_query($xsql);


        $multipart_data              = sprintf('<a href="product.php?id=%d">%s</a>', $product->id, $product->data['Product Code']);
        $multipart_data_multiplicity = count($part_list);


        // print_r($part_list);

        foreach ($part_list as $part_data) {


            $part = new Part ('sku', $part_data['Part SKU']);
            if ($part->sku) {

                $quantity_to_be_taken = $part_data['Ratio'] * $to_sell_quantity;


                $location_key = $part->get_picking_location_key();


                if ($supplier_metadata_array != '') {
                    $supplier_metadata = unserialize($supplier_metadata_array);
                    if (!is_array($supplier_metadata)) {
                        $supplier_metadata = array();
                    }
                } else {
                    $supplier_metadata = array();

                }


                //print "P ".$product->pid."  art:".$part_data['Part SKU']."  p:".$part->sku." \n";

                if (array_key_exists($part->sku, $supplier_metadata) and $supplier_metadata[$part->sku]) {
                    //print "xxx\n";
                    //print_r($supplier_metadata[$part->sku]);
                    //print "-xxx\n";

                    $supplier_part_key          = $supplier_metadata[$part->sku]['supplier_part_key'];
                    $supplier_part_historic_key = $supplier_metadata[$part->sku]['supplier_part_historic_key'];
                    $supplier_key               = $supplier_metadata[$part->sku]['supplier_key'];

                } else {


                    list($supplier_key, $supplier_part_key, $supplier_part_historic_key) = $part->get_stock_supplier_data();


                }


                //  $quantity_taken_from_location = $location_data['qty'];

                if ($bonus_qty > 0) {
                    if ($bonus_qty >= $quantity_to_be_taken) {
                        $given     = $quantity_to_be_taken;
                        $required  = 0;
                        $bonus_qty = $quantity_to_be_taken - $bonus_qty;
                    } else {
                        $given     = $bonus_qty;
                        $required  = $quantity_to_be_taken - $given;
                        $bonus_qty = 0;
                    }


                } else {
                    $given    = 0;
                    $required = $quantity_to_be_taken;

                }


                $note = '';


                $picking_note = $part_data['Note'];

                $weight = $part->get('Part Package Weight') * ($required + $given);


                $sql = sprintf(
                    "INSERT INTO `Inventory Transaction Fact`  (
					`Map To Order Transaction Fact Parts Multiplicity`,`Map To Order Transaction Fact XHTML Info`,`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Inventory Transaction Fact Delivery 2 Alpha Code`,`Picking Note`,

					`Inventory Transaction Weight`,`Date Created`,`Date`,`Delivery Note Key`,`Part SKU`,`Location Key`,

					`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,

					`Metadata`,`Note`,`Supplier Product ID`,`Supplier Product Historic Key`,`Supplier Key`,`Map To Order Transaction Fact Key`,`Map To Order Transaction Fact Metadata`)
					VALUES (
					%d,%s,%s,%s,%s,%s,
					%f,%s,%s,%d,%s,%d,
					%s,%s,%.2f,%f,%f,%f,

					%s,%s,%d,%d,%d,%d,%s) ", $multipart_data_multiplicity, prepare_mysql($multipart_data), "'Movement'", "'OIP'", prepare_mysql($this->data['Delivery Note Address Country 2 Alpha Code']),
                    prepare_mysql($picking_note),

                    $weight, prepare_mysql($this->get('Delivery Note Date')), prepare_mysql($this->get('Delivery Note Date')), $this->id, prepare_mysql($part_data['Part SKU']), $location_key,


                    0, "'Order In Process'", 0, $required, $given, 0,


                    prepare_mysql($this->data['Delivery Note Metadata']), prepare_mysql($note),

                    $supplier_part_key,

                    $supplier_part_historic_key,

                    $supplier_key, $map_to_otf_key, prepare_mysql($part_index.';'.$part_data['Ratio'].';'.$location_index)
                );
                $this->db->exec($sql);


                 print $sql;


                if ($this->update_stock) {


                    //$part_location=new PartLocation($part_data['Part SKU'].'_'.$location_key);
                    //$part_location->update_stock();
                }
                //print "$sql\n";
                $location_index++;


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
                switch ($this->data['Delivery Note State']) {
                    case 'Ready to be Picked':
                    case 'Picker & Packer Assigned':
                        return 10;
                        break;
                    case 'Picker Assigned':
                    case 'Picking & Packing':
                    case 'Picking':
                        return 20;
                        break;

                    case 'Picked':
                        return 30;
                        break;

                    case 'Packer Assigned':
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
                        return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 20) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Picked Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {
                            return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                        } else {
                            return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                        }

                    } else {
                        return sprintf(
                            '<span title="%s">%s</span>', $this->get('Delivery Note Number Picked Items').'/'.$this->get('Delivery Note Number To Pick Items'),
                            percentage($this->get('Delivery Note Number Picked Items'), $this->get('Delivery Note Number To Pick Items'))
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
                        return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 40) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {
                            return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Finish Picking')));
                        } else {
                            return strftime("%e %b %Y %H:%M", strtotime($this->get('Delivery Note Date Start Picking')));
                        }

                    } else {
                        return sprintf(
                            '<span title="%s">%s</span>', $this->get('Delivery Note Number Packed Items').'/'.$this->get('Delivery Note Number To Pick Items'),
                            percentage($this->get('Delivery Note Number Packed Items'), $this->get('Delivery Note Number To Pick Items'))
                        );
                    }


                }


                break;


            case ('State'):
                switch ($this->data['Delivery Note State']) {

                    case 'Picker & Packer Assigned':
                        return _('Picker & packer assigned');
                        break;
                    case 'Picking & Packing':
                        return _('Picking & packing');
                        break;
                    case 'Packer Assigned':
                        return _('Packer assigned');
                        break;
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
                        return _('Dispatched');
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Cancelled to Restock':
                        return _('Cancelled to restock');
                        break;
                    case 'Packed Done':
                        return _('Packed done');
                        break;
                    default:
                        return $this->data['Delivery Note State'];
                        break;
                }
                break;
            case ('Abbreviated State'):
                switch ($this->data['Delivery Note State']) {

                    case 'Picker & Packer Assigned':
                        return _('Picker & packer assigned');
                        break;
                    case 'Picking & Packing':
                        return _('Picking & packing');
                        break;
                    case 'Packer Assigned':
                        return _('Packer assigned');
                        break;
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
                        return _('Dispatched');
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Cancelled to Restock':
                        return _('Cancelled to restock');
                        break;
                    case 'Packed Done':
                        return _('Packed done');
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
            case('Date'):

                return strftime(
                    "%e %b %y", strtotime($this->data['Delivery Note Date'].' +0:00')
                );

                break;
            case('Creation Date'):
                return strftime(
                    "%e %b %y %H:%M", strtotime($this->data['Delivery Note Date Created'].' +0:00')
                );
                break;
            case('Start Picking Datetime'):
            case('Finish Picking Datetime'):
            case('Start Packing Datetime'):
            case('Finish Packing Datetime'):
            case('Dispatched Approved Datetime'):
            case('Dispatched Datetime'):
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
                $consignment = $this->data['Delivery Note Shipper Consignment'];
                if ($this->data['Delivery Note Shipper Code'] != '') {
                    $consignment .= sprintf(
                        ' [<a href="shipper.php?code=%s">%s</a>]', $this->data['Delivery Note Shipper Code'], $this->data['Delivery Note Shipper Code']
                    );
                }

                return $consignment;
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

        // if($this->id){
        $sql = sprintf(
            'SELECT sum(`Inventory Transaction Weight`) AS estimated_weight,   count(DISTINCT `Part SKU`) AS ordered_parts, sum(`Required`+`Given`) AS ordered, sum(`Required`+`Given`) AS ordered,sum(`Required`+`Given`-`Out of Stock`) AS to_pick, sum(`Picked`) AS picked,sum(`Packed`) AS packed   FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);

                $ordered          = $row['ordered'];
                $picked           = $row['picked'];
                $packed           = $row['packed'];
                $to_pick          = $row['to_pick'];
                $ordered_parts    = $row['ordered_parts'];
                $estimated_weight = $row['estimated_weight'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Delivery Note Number Picked Items'  => $picked,
                'Delivery Note Number Packed Items'  => $packed,
                'Delivery Note Number Ordered Items' => $ordered,
                'Delivery Note Number To Pick Items' => $to_pick,
                'Delivery Note Number Ordered Parts' => $ordered_parts,
                'Delivery Note Estimated Weight'     => $estimated_weight
            ), 'no_options'
        );


    }


    //  New methods

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {

            case 'parcels_weight':

                $this->set_weight($value);
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

    function update_item($data) {


        switch ($data['field']) {
            case 'Picked':
                return $this->update_item_picked_quantity($data);
                break;
            case 'Out_of_stock':
                return $this->update_item_out_of_stock_quantity($data);
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


        if ($this->get('State Index') == 10) {
            $this->set_state('Picking');
        }


        //  print_r($data);

        $date = gmdate('Y-m-d H:i:s');

        //$item_key        = $data['item_key'];
        $qty             = $data['qty'];
        $transaction_key = $data['transaction_key'];

        $sql = sprintf(
            'SELECT `Part Cost`,`Inventory Transaction Key`,ITF.`Part SKU`,`Picked`,`Required`,`Given`,`Location Key`,`Required`+`Given`-`Picked`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` AS pending,(`Required`+`Given`) AS quantity FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Inventory Transaction Key`=%d',
            $data['transaction_key']
        );

        //   print "$sql";


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);


                $transaction_value = $row['Part Cost'] * $qty;

                $to_pick = $row['pending'] + $row['Picked'];


                // $pending = $row['pending'];


                if ($qty <= $to_pick) {

                    //   $location = new Location($row['Location Key']);

                    // $qty = $row['Picked'] + $qty;

                    $sql = sprintf(
                        "UPDATE `Inventory Transaction Fact` SET  `Inventory Transaction Type`='Sale' ,`Inventory Transaction Section`='Out',`Picked`=%f,`Inventory Transaction Quantity`=%f,`Inventory Transaction Amount`=%f,`Date Picked`=%s,`Date`=%s ,`Picker Key`=%s WHERE `Inventory Transaction Key`=%d  ",
                        $qty, -1 * $qty, $transaction_value, prepare_mysql($date), prepare_mysql($date), prepare_mysql($data['picker_key']), $data['transaction_key']
                    );

                    $this->db->exec($sql);


                    //   print $_pending;


                } else {
                    $this->error = true;
                    $this->msg   = 'Error, trying to pick more items than required';

                    return;

                }

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


        if ($this->get('Delivery Note Number Picked Items') == $this->get('Delivery Note Number To Pick Items')) {
            if ($this->get('State Index') == 20) {
                $this->set_state('Picked');
            }
        }


        $this->update_metadata = array(
            'state_index'                => $this->get('State Index'),
            'picked_quantity_components' => get_item_picked(
                $pending, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock'),
                $part_location->part->get('Part SKO Barcode'), $part_location->part->get('Part Reference'), $part_location->part->get('Part Reference'), $part_location->part->get('Part Reference'),
                base64_encode(
                    $part_location->part->get('Part Package Description').($part_location->part->get('Picking Note') != '' ? ' <span>('.$part_location->part->get('Picking Note').'</span>' : '')
                ), $part_location->part->get('Part Main Image Key')
            ),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'), $part_location->part->get('Part Current On Hand Stock')
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
            $smarty->assign('dn', $this);
            $this->update_metadata['class_html']['picking_options'] = $smarty->fetch('delivery_note.options.picking.tpl');
        }


        return array(
            'transaction_key' => $transaction_key,
            'qty'             => $qty + 0
        );


    }

    function set_state($value, $options = '', $metadata = array()) {
        $date = gmdate('Y-m-d H:i:s');

        $operations = array();

        switch ($value) {
            case 'Picked':

                if ($this->get('State Index') > 30 or $this->get('State Index') < 10) {
                    return;
                }

                $this->update_field(
                    'Delivery Note Date Finish Picking', $date, 'no_history'
                );
                //$this->update_field('Supplier Delivery Estimated Receiving Date', '', 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );


                break;
            case 'Picking':

                if ($this->get('State Index') != 10) {
                    return;
                }

                $this->update_field(
                    'Delivery Note Date Start Picking', $date, 'no_history'
                );
                //$this->update_field('Supplier Delivery Estimated Receiving Date', '', 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );

                break;
            case 'Packing':

                if ($this->get('State Index') >= 40) {
                    return;
                }

                $this->update_field(
                    'Delivery Note Date Start Packing', $date, 'no_history'
                );


                if ($this->get('State Index') == 30) {
                    $this->update_field(
                        'Delivery Note State', $value, 'no_history'
                    );
                }

                break;
            case 'Packed':

                if ($this->get('State Index') > 70 or $this->get('State Index') < 10) {
                    return;
                }
                $this->update_field('Delivery Note Date Finish Packing', $date, 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );


                //   $order=

                //   print "----";

                break;

            case 'Approved':

                if ($this->get('State Index') != 70) {
                    return;
                }
                $this->update_field('Delivery Note Date Dispatched Approved', $date, 'no_history');
                $this->update_field(
                    'Delivery Note State', $value, 'no_history'
                );


                break;
            case 'Dispatched':

                if ($this->get('State Index') != 90) {
                    return;
                }
                $this->update_field('Delivery Note Date Dispatched', $date, 'no_history');
                $this->update_field('Delivery Note Date', $date, 'no_history');
                $this->update_field('Delivery Note State', $value, 'no_history');


                break;

            default:
                exit('unknown state '.$value);
                break;
        }


        $this->update_totals();
        // $purchase_order->update_totals();


        $this->update_metadata = array(
            'class_html'  => array(
                'Delivery_Note_State'                       => $this->get('State'),
                'Delivery_Note_Dispatched_Date'             => '&nbsp;'.$this->get('Dispatched Date'),
                'Supplier_Delivery_Number_Dispatched_Items' => $this->get('Number Dispatched Items'),
                'Delivery_Note_Start_Picking_Datetime'      => '<i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->get('Start Picking Datetime'),
                'Delivery_Note_Start_Packing_Datetime'      => '<i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->get('Start Packing Datetime'),
                'Delivery_Note_Finish_Picking_Datetime'     => $this->get('Finish Picking Datetime'),
                'Delivery_Note_Finish_Packing_Datetime'     => $this->get('Finish Packing Datetime'),
                'Delivery_Note_Picked_Label'                => ($this->get('State Index') == 20 ? _('Picking') : _('Picked')),


                'Delivery_Note_Picked_Percentage_or_Datetime' => '&nbsp;'.$this->get('Picked Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Packed_Percentage_or_Datetime' => '&nbsp;'.$this->get('Packed Percentage or Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Approved_Datetime'  => '&nbsp;'.$this->get('Dispatched Approved Datetime').'&nbsp;',
                'Delivery_Note_Dispatched_Datetime'           => '&nbsp;'.$this->get('Dispatched Datetime').'&nbsp;',

                'Delivery_Note_State' => $this->get('State')


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );


    }

    function update_item_out_of_stock_quantity($data) {

        include_once('class.Location.php');
        include_once('class.PartLocation.php');


        if ($this->get('State Index') == 10) {
            $this->set_state('Picking');
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
                        "UPDATE `Inventory Transaction Fact` SET `Out of Stock`=%f ,`Out of Stock Lost Amount`=%f ,`Out of Stock Tag`=%s ,`Date`=%s ,`Picker Key`=%s WHERE `Inventory Transaction Key`=%d  ",
                        $qty, $transaction_value, prepare_mysql(($qty == 0 ? 'No' : 'Yes')), prepare_mysql($date), prepare_mysql($data['picker_key']), $data['transaction_key']
                    );

                    $this->db->exec($sql);


                    $pending = $to_pick - $qty;
                    $picked  = $qty;

                } else {
                    $this->error = true;
                    $this->msg   = 'Trying to pick more items than required';

                    return;

                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        //=========


        $location_stock_icon_class = 'button ';
        $stock_in_location         = sprintf(_('Stock in location: %s'), $part_location->get('Quantity On Hand'));

        $stock_quantity_safe_limit = ceil($pending * 1.2);
        if ($stock_quantity_safe_limit > 10) {
            $stock_quantity_safe_limit;
        }


        if ($pending == 0) {
            $picked_time = sprintf(_('Picked: %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($date.' +0:00')));
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
                $this->set_state('Picked');
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


    }

    function update_item_packed_quantity($data) {


        include_once('class.Location.php');
        include_once('class.PartLocation.php');
        include_once('utils/order_handing_functions.php');


        if ($this->get('State Index') == 30) {
            $this->set_state('Packing');
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
                        "UPDATE `Inventory Transaction Fact` SET  `Packed`=%f,`Date Picked`=%s,`Date`=%s ,`Packer Key`=%s WHERE `Inventory Transaction Key`=%d  ", $qty, prepare_mysql($date),
                        prepare_mysql($date), prepare_mysql($data['packer_key']), $data['transaction_key']
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

        $this->set_state('Packed');
        if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {
            if ($this->get('State Index') < 70 and $this->get('State Index') > 10) {
                $this->set_state('Packed');
            }
        }


        $this->update_metadata = array(
            'state_index'                => $this->get('State Index'),
            'picked_quantity_components' => get_item_picked(
                $pending_picking, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock')
            ),
            'packed_quantity_components' => get_item_packed($pending, $row['Inventory Transaction Key'], $row['Part SKU'], $packed),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'), $part_location->part->get('Part Current On Hand Stock')
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

    function delete() {

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
            "UPDATE   `Order Transaction Fact` SET  `Current Dispatching State`='In Process' ,  `Delivery Note Key`=NULL  WHERE `Delivery Note Key`=%d  AND `Current Dispatching State`='Ready to Pick'  ",
            $this->id
        );
        $this->db->exec($sql);


        foreach ($parts_to_update_stock as $part_to_update_stock) {
            $part_location = new PartLocation($part_to_update_stock);
            $part_location->update_stock();
        }

        $order         = new Order($this->get('Delivery Note Order Key'));
        $order->editor = $this->editor;
        // $invoices=$this->get_invoices_objects();

        $store_key = $this->data['Delivery Note Store Key'];

        $sql = sprintf(
            "DELETE FROM  `Delivery Note Dimension` WHERE `Delivery Note Key`=%d  ", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM  `Order Delivery Note Bridge` WHERE `Delivery Note Key`=%d  ", $this->id
        );
        $this->db->exec($sql);

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


            $sql = sprintf("DELETE FROM `Order Transaction Fact` WHERE `Delivery Note Key`=%d AND `Order Transaction Type`='Resend'", $this->id);
            $this->db->exec($sql);

        } else {

            $order->update(
                array(
                    'Order Current Dispatch State' => 'In Process'
                )
            );

        }


        return 'orders/'.$order->get('Order Store Key').'/'.$order->id;

    }


}


?>
