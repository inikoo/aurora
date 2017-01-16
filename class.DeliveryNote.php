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
        $result = mysql_query($sql);
        if ($this->data = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id = $this->data['Delivery Note Key'];

        }

    }


    protected function create($dn_data, $order = false) {
        global $myconf;

        if (isset($dn_data ['Delivery Note Date'])) {
            $this->data ['Delivery Note Date'] = $dn_data ['Delivery Note Date'];
        } else {
            $this->data ['Delivery Note Date'] = '';
        }


        if (isset($dn_data ['Delivery Note Dispatch Method'])) {
            $this->data ['Delivery Note Dispatch Method'] = $dn_data ['Delivery Note Dispatch Method'];
        } else {
            $this->data ['Delivery Note Dispatch Method'] = 'Unknown';
        }

        if (isset($dn_data ['Delivery Note Weight'])) {
            $this->data ['Delivery Note Weight'] = $dn_data ['Delivery Note Weight'];
        } else {
            $this->data ['Delivery Note Weight'] = '';
        }

        if (isset($dn_data ['Delivery Note Order Date Placed'])) {
            $this->data ['Delivery Note Order Date Placed'] = $dn_data ['Delivery Note Order Date Placed'];
        } else {
            $this->data ['Delivery Note Order Date Placed'] = '';
        }


        if (isset($dn_data ['Delivery Note Customer Contact Name'])) {
            $this->data ['Delivery Note Customer Contact Name'] = $dn_data ['Delivery Note Customer Contact Name'];
        } else {
            $this->data ['Delivery Note Customer Contact Name'] = '';
        }

        if (isset($dn_data ['Delivery Note Telephone'])) {
            $this->data ['Delivery Note Telephone'] = $dn_data ['Delivery Note Telephone'];
        } else {
            $this->data ['Delivery Note Telephone'] = '';
        }

        if (isset($dn_data ['Delivery Note Email'])) {
            $this->data ['Delivery Note Email'] = $dn_data ['Delivery Note Email'];
        } else {
            $this->data ['Delivery Note Email'] = '';
        }


        if (isset($dn_data ['Delivery Note XHTML Pickers'])) {
            $this->data ['Delivery Note XHTML Pickers'] = $dn_data ['Delivery Note XHTML Pickers'];
        } else {
            $this->data ['Delivery Note XHTML Pickers'] = '';
        }

        if (isset($dn_data ['Delivery Note Number Pickers'])) {
            $this->data ['Delivery Note Number Pickers'] = $dn_data ['Delivery Note Number Pickers'];
        } else {
            $this->data ['Delivery Note Number Pickers'] = '';
        }

        if (isset($dn_data ['Delivery Note Pickers IDs'])) {
            $this->data ['Delivery Note Pickers IDs'] = $dn_data ['Delivery Note Pickers IDs'];
        } else {
            $this->data ['Delivery Note Pickers IDs'] = '';
        }

        if (isset($dn_data ['Delivery Note Warehouse Key'])) {
            $this->data ['Delivery Note Warehouse Key'] = $dn_data ['Delivery Note Warehouse Key'];
        } else {
            $this->data ['Delivery Note Warehouse Key'] = 1;
        }


        if (isset($dn_data ['Delivery Note XHTML Packers'])) {
            $this->data ['Delivery Note XHTML Packers'] = $dn_data ['Delivery Note XHTML Packers'];
        } else {
            $this->data ['Delivery Note XHTML Packers'] = '';
        }

        if (isset($dn_data ['Delivery Note Number Packers'])) {
            $this->data ['Delivery Note Number Packers'] = $dn_data ['Delivery Note Number Packers'];
        } else {
            $this->data ['Delivery Note Number Packers'] = '';
        }

        if (isset($dn_data ['Delivery Note Packers IDs'])) {
            $this->data ['Delivery Note Packers IDs'] = $dn_data ['Delivery Note Packers IDs'];
        } else {
            $this->data ['Delivery Note Packers IDs'] = '';
        }

        $this->data ['Delivery Note ID']      = $dn_data ['Delivery Note ID'];
        $this->data ['Delivery Note File As'] = $dn_data ['Delivery Note File As'];

        $customer = new Customer ($dn_data['Delivery Note Customer Key']);


        $this->data ['Delivery Note Customer Key']  = $customer->id;
        $this->data ['Delivery Note Customer Name'] = $customer->data['Customer Name'];
        $this->data ['Delivery Note Store Key']     = $customer->data['Customer Store Key'];
        $store                                      = new Store(
            $this->data ['Delivery Note Store Key']
        );

        $this->data['Delivery Note Show in Warehouse Orders'] = $store->data['Store Show in Warehouse Orders'];


        if (isset($dn_data ['Delivery Note Metadata'])) {
            $this->data ['Delivery Note Metadata'] = $dn_data ['Delivery Note Metadata'];
        } else {
            if ($order) {

                $this->data ['Delivery Note Metadata'] = $order->data ['Order Original Metadata'];
            } else {
                $this->data ['Delivery Note Metadata'] = '';
            }
        }

        if (isset($dn_data ['Delivery Note Date Created'])) {
            $this->data ['Delivery Note Date Created'] = $dn_data ['Delivery Note Date Created'];
        } else {
            $this->data ['Delivery Note Date Created'] = gmdate('Y-m-d H:i:s');
        }
        if (isset($dn_data ['Delivery Note State'])) {
            $this->data ['Delivery Note State'] = $dn_data ['Delivery Note State'];
        } else {
            $this->data ['Delivery Note State'] = 'Ready to be Picked';
        }


        $this->data ['Delivery Note Type']  = $dn_data ['Delivery Note Type'];
        $this->data ['Delivery Note Title'] = $dn_data ['Delivery Note Title'];

        $this->data ['Delivery Note Dispatch Method'] = $dn_data ['Delivery Note Dispatch Method'];


        if ($this->data ['Delivery Note Dispatch Method'] == 'Collection') {

            $this->data ['Delivery Note Shipper Code'] = '';
            $store                                     = new Store(
                $this->data['Delivery Note Store Key']
            );
            $collection_address                        = new Address(
                $store->data['Store Collection Address Key']
            );
            if ($collection_address->id) {
                $this->data ['Delivery Note Country 2 Alpha Code'] = $collection_address->data['Address Country 2 Alpha Code'];
                $this->data ['Delivery Note Country Code']         = $collection_address->data['Address Country Code'];
                $this->data ['Delivery Note World Region Code']    = $collection_address->get(
                    'Address World Region Code'
                );
                $this->data ['Delivery Note Town']                 = $collection_address->data['Address Town'];
                $this->data ['Delivery Note Postal Code']          = $collection_address->data['Address Postal Code'];
                $this->data ['Delivery Note XHTML Ship To']        = '<div><b>'._(
                        'For collection'
                    ).'</b></div><div style="color:#777">'.$collection_address->display('xhtml').'</div>';


            } else {

                include_once 'class.Country.php';
                $country = new Country(
                    '2alpha', $store->data['Store Home Country Code 2 Alpha']
                );


                $this->data ['Delivery Note Country 2 Alpha Code'] = $country->data['Country 2 Alpha Code'];
                $this->data ['Delivery Note Country Code']         = $country->data['Country Code'];
                $this->data ['Delivery Note World Region Code']    = $country->data['World Region Code'];
                $this->data ['Delivery Note Town']                 = '';
                $this->data ['Delivery Note Postal Code']          = '';
                $this->data ['Delivery Note XHTML Ship To']        = '<div><b>'._(
                        'For collection'
                    ).'</b></div>';

            }


            $this->data ['Delivery Note Ship To Key'] = 0;


        } else {

            if (isset($dn_data ['Delivery Note Shipper Code'])) {
                $this->data ['Delivery Note Shipper Code'] = $dn_data ['Delivery Note Shipper Code'];
            } else {
                $this->data ['Delivery Note Shipper Code'] = '';
            }


            if ($order and $order->data ['Order Ship To Key To Deliver']) {
                $ship_to = new Ship_To(
                    $order->data ['Order Ship To Key To Deliver']
                );
            } else {
                $ship_to = $customer->get_ship_to(
                    $this->data ['Delivery Note Date Created']
                );
            }

            $this->data ['Delivery Note Ship To Key']          = $ship_to->id;
            $this->data ['Delivery Note XHTML Ship To']        = $ship_to->data['Ship To XHTML Address'];
            $this->data ['Delivery Note Country 2 Alpha Code'] = ($ship_to->data['Ship To Country 2 Alpha Code'] == '' ? 'XX' : $ship_to->data['Ship To Country 2 Alpha Code']);

            $this->data ['Delivery Note Country Code']      = ($ship_to->data['Ship To Country Code'] == '' ? 'UNK' : $ship_to->data['Ship To Country Code']);
            $this->data ['Delivery Note World Region Code'] = $ship_to->get(
                'World Region Code'
            );
            $this->data ['Delivery Note Town']              = $ship_to->data['Ship To Town'];
            $this->data ['Delivery Note Postal Code']       = $ship_to->data['Ship To Postal Code'];


        }


        $this->create_header();
        $this->update_xhtml_state();
        if ($order) {

            $this->update_order_transaction_after_create_dn($order);

        }


    }

    function create_header() {
        $sql = sprintf(
            "INSERT INTO `Delivery Note Dimension` (
		`Delivery Note Customer Contact Name`,`Delivery Note Telephone`,`Delivery Note Email`,
		`Delivery Note Order Date Placed`,`Delivery Note Show in Warehouse Orders`,`Delivery Note Warehouse Key`,`Delivery Note State`,`Delivery Note Date Created`,`Delivery Note Dispatch Method`,`Delivery Note Store Key`,`Delivery Note XHTML Orders`,`Delivery Note XHTML Invoices`,`Delivery Note Date`,`Delivery Note ID`,`Delivery Note File As`,`Delivery Note Customer Key`,`Delivery Note Customer Name`,`Delivery Note XHTML Ship To`,`Delivery Note Ship To Key`,`Delivery Note Metadata`,`Delivery Note Weight`,`Delivery Note XHTML Pickers`,`Delivery Note Number Pickers`,`Delivery Note XHTML Packers`,`Delivery Note Number Packers`,`Delivery Note Type`,`Delivery Note Title`,`Delivery Note Shipper Code`,
                         `Delivery Note Country 2 Alpha Code`,
                         `Delivery Note Country Code`,
                         `Delivery Note World Region Code`,
                         `Delivery Note Town`,
                         `Delivery Note Postal Code`

                        ) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,'','',%s,%s,%s,%s,%s,%s,%s,%s,%f,%s,%d,%s,%d,%s,%s,%s,%s      ,%s,%s,%s,%s )"

            , prepare_mysql($this->data ['Delivery Note Customer Contact Name']), prepare_mysql($this->data ['Delivery Note Telephone']), prepare_mysql($this->data ['Delivery Note Email'])


            , prepare_mysql($this->data ['Delivery Note Order Date Placed'])

            , prepare_mysql(
                $this->data ['Delivery Note Show in Warehouse Orders']
            ), $this->data ['Delivery Note Warehouse Key']

            , prepare_mysql($this->data ['Delivery Note State'])

            , prepare_mysql($this->data ['Delivery Note Date Created']), prepare_mysql($this->data ['Delivery Note Dispatch Method']), prepare_mysql($this->data ['Delivery Note Store Key']),
            prepare_mysql($this->data ['Delivery Note Date']), prepare_mysql($this->data ['Delivery Note ID']), prepare_mysql($this->data ['Delivery Note File As']),
            prepare_mysql($this->data ['Delivery Note Customer Key']), prepare_mysql($this->data ['Delivery Note Customer Name'], false), prepare_mysql($this->data ['Delivery Note XHTML Ship To']),
            prepare_mysql($this->data ['Delivery Note Ship To Key']), prepare_mysql($this->data ['Delivery Note Metadata']), $this->data ['Delivery Note Weight'],
            prepare_mysql($this->data ['Delivery Note XHTML Pickers']), $this->data ['Delivery Note Number Pickers'], prepare_mysql($this->data ['Delivery Note XHTML Packers']),
            $this->data ['Delivery Note Number Packers'], prepare_mysql($this->data ['Delivery Note Type']), prepare_mysql($this->data ['Delivery Note Title']),
            prepare_mysql($this->data ['Delivery Note Shipper Code'])

            , prepare_mysql($this->data ['Delivery Note Country 2 Alpha Code']), prepare_mysql($this->data ['Delivery Note Country Code']),
            prepare_mysql($this->data ['Delivery Note World Region Code']), prepare_mysql($this->data ['Delivery Note Town']), prepare_mysql($this->data ['Delivery Note Postal Code'])

        );

        //print $sql;
        if (mysql_query($sql)) {

            $this->data ['Delivery Note Key'] = mysql_insert_id();
            $this->id                         = $this->data ['Delivery Note Key'];
            $this->get_data('id', $this->id);


        } else {
            exit ("$sql \n Error can not create dn header");
        }

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {

            case 'parcels_weight':

                $this->set_weight($value);
                break;
            case('Delivery Note XHTML Invoices'):
                $this->update_xhtml_invoices();
                break;
            case('Delivery Note XHTML Orders'):
                $this->update_xhtml_orders();
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


    //  New methods

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


                if ($qty <= $row['pending']) {

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
                      $this->msg   = 'Trying to pick more items than required';

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
                $pending, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock')
            ),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'), $part_location->part->get('Part Current On Hand Stock')
            ),
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


                    if($row['Picked']<$qty){
                        $updating_picking=true;
                        $picked=$qty;
                        $transaction_value=$picked*$row['Part Cost'];


                    }else{
                        $updating_picking=false;
                    }

                    $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);

                    if($updating_picking){
                        $sql = sprintf(
                            "UPDATE `Inventory Transaction Fact` SET  `Inventory Transaction Type`='Sale' ,`Inventory Transaction Section`='Out',`Picked`=%f,`Inventory Transaction Quantity`=%f,`Inventory Transaction Amount`=%f,`Date Picked`=%s WHERE `Inventory Transaction Key`=%d  ",
                            $picked, -1 * $picked, $transaction_value, prepare_mysql($date), $data['transaction_key']
                        );

                        $this->db->exec($sql);


                        $part_location->update_stock();
                    }

                    $sql = sprintf(
                        "UPDATE `Inventory Transaction Fact` SET  `Packed`=%f,`Date Picked`=%s,`Date`=%s ,`Packer Key`=%s WHERE `Inventory Transaction Key`=%d  ",
                        $qty, prepare_mysql($date), prepare_mysql($date), prepare_mysql($data['packer_key']), $data['transaction_key']
                    );

                    $this->db->exec($sql);


                    //   print $_pending;


                } else {
                     $this->error = true;
                      $this->msg   = 'Trying to pack more items than required';

                       return;

                }



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
                $pending, $part_location->get('Quantity On Hand'), $row['Inventory Transaction Key'], $row['Part SKU'], $picked, $part_location->part->get('Part Current On Hand Stock')
            ),
            'location_components'        => get_item_location(
                $pending, $part_location->get('Quantity On Hand'), $date, $part_location->location->id, $part_location->location->get('Code'), $part_location->part->get('Part Current On Hand Stock')
            ),
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
            case 'Picked Percentage or Date':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Delivery Note Date Start Picking') == '') {
                        return '';
                    }


                    if ($this->get('Delivery Note Date Finish Picking') != '') {
                        return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 20) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Picked Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {
                            return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Finish Picking')));
                        } else {
                            return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Start Picking')));
                        }

                    } else {
                        return sprintf(
                            '<span title="%s">%s</span>', $this->get('Delivery Note Number Picked Items').'/'.$this->get('Delivery Note Number To Pick Items'),
                            percentage($this->get('Delivery Note Number Picked Items'), $this->get('Delivery Note Number To Pick Items'))
                        );
                    }


                }


                break;

            case 'Packed Percentage or Date':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Delivery Note Date Start Picking') == '') {
                        return '';
                    }


                    if ($this->get('Delivery Note Date Finish Picking') != '') {
                        return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Finish Picking')));
                    } else {
                        return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Start Picking')));
                    }


                } elseif ($this->get('State Index') < 40) {
                    return '';
                } else {


                    if ($this->get('Delivery Note Number Packed Items') == $this->get('Delivery Note Number To Pick Items')) {

                        if ($this->get('Delivery Note Date Start Picking') == '') {
                            return '';
                        }


                        if ($this->get('Delivery Note Date Finish Picking') != '') {
                            return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Finish Picking')));
                        } else {
                            return strftime("%e %b %Y", strtotime($this->get('Delivery Note Date Start Picking')));
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
            case('Dispatched Date'):
                return strftime(
                    "%e %b %y %H:%M", strtotime($this->data['Delivery Note Date'].' +0:00')
                );
                break;
            case('Creation Date'):
                return strftime(
                    "%e %b %y %H:%M", strtotime(
                                        $this->data['Delivery Note Date Created'].' +0:00'
                                    )
                );
                break;
            case('Start Picking Date'):
            case('Finish Picking Date'):
            case('Start Packing Date'):
            case('Finish Packing Date'):

                $key = 'Date '.preg_replace('/ Date$/', '', $key);

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


            default:
                exit('unknown state '.$value);
                break;
        }


        $this->update_totals();
        // $purchase_order->update_totals();


        $this->update_metadata = array(
            'class_html'  => array(
                'Supplier_Delivery_State'                   => $this->get('State'),
                'Supplier_Delivery_Dispatched_Date'         => '&nbsp;'.$this->get('Dispatched Date'),
                'Supplier_Delivery_Received_Date'           => '&nbsp;'.$this->get('Received Date'),
                'Supplier_Delivery_Checked_Date'            => '&nbsp;'.$this->get('Checked Date'),
                'Supplier_Delivery_Number_Dispatched_Items' => $this->get('Number Dispatched Items'),
                'Supplier_Delivery_Number_Received_Items'   => $this->get('Number Received Items')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );


    }

    function update_totals() {


        $ordered = 0;
        $picked  = 0;
        $packed  = 0;
        $to_pick = 0;

        // if($this->id){
        $sql = sprintf(
            'SELECT sum(`Required`+`Given`) AS ordered,sum(`Required`+`Given`-`Out of Stock`) AS to_pick, sum(`Picked`) AS picked,sum(`Packed`) AS packed   FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $ordered = $row['ordered'];
                $picked  = $row['picked'];
                $packed  = $row['packed'];
                $to_pick = $row['to_pick'];
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
            ), 'no_options'
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


}


?>
