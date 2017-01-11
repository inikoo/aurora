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
            $this->data ['Delivery Note Date']
                = $dn_data ['Delivery Note Date'];
        } else {
            $this->data ['Delivery Note Date'] = '';
        }


        if (isset($dn_data ['Delivery Note Dispatch Method'])) {
            $this->data ['Delivery Note Dispatch Method']
                = $dn_data ['Delivery Note Dispatch Method'];
        } else {
            $this->data ['Delivery Note Dispatch Method'] = 'Unknown';
        }

        if (isset($dn_data ['Delivery Note Weight'])) {
            $this->data ['Delivery Note Weight']
                = $dn_data ['Delivery Note Weight'];
        } else {
            $this->data ['Delivery Note Weight'] = '';
        }

        if (isset($dn_data ['Delivery Note Order Date Placed'])) {
            $this->data ['Delivery Note Order Date Placed']
                = $dn_data ['Delivery Note Order Date Placed'];
        } else {
            $this->data ['Delivery Note Order Date Placed'] = '';
        }


        if (isset($dn_data ['Delivery Note Customer Contact Name'])) {
            $this->data ['Delivery Note Customer Contact Name']
                = $dn_data ['Delivery Note Customer Contact Name'];
        } else {
            $this->data ['Delivery Note Customer Contact Name'] = '';
        }

        if (isset($dn_data ['Delivery Note Telephone'])) {
            $this->data ['Delivery Note Telephone']
                = $dn_data ['Delivery Note Telephone'];
        } else {
            $this->data ['Delivery Note Telephone'] = '';
        }

        if (isset($dn_data ['Delivery Note Email'])) {
            $this->data ['Delivery Note Email']
                = $dn_data ['Delivery Note Email'];
        } else {
            $this->data ['Delivery Note Email'] = '';
        }


        if (isset($dn_data ['Delivery Note XHTML Pickers'])) {
            $this->data ['Delivery Note XHTML Pickers']
                = $dn_data ['Delivery Note XHTML Pickers'];
        } else {
            $this->data ['Delivery Note XHTML Pickers'] = '';
        }

        if (isset($dn_data ['Delivery Note Number Pickers'])) {
            $this->data ['Delivery Note Number Pickers']
                = $dn_data ['Delivery Note Number Pickers'];
        } else {
            $this->data ['Delivery Note Number Pickers'] = '';
        }

        if (isset($dn_data ['Delivery Note Pickers IDs'])) {
            $this->data ['Delivery Note Pickers IDs']
                = $dn_data ['Delivery Note Pickers IDs'];
        } else {
            $this->data ['Delivery Note Pickers IDs'] = '';
        }

        if (isset($dn_data ['Delivery Note Warehouse Key'])) {
            $this->data ['Delivery Note Warehouse Key']
                = $dn_data ['Delivery Note Warehouse Key'];
        } else {
            $this->data ['Delivery Note Warehouse Key'] = 1;
        }


        if (isset($dn_data ['Delivery Note XHTML Packers'])) {
            $this->data ['Delivery Note XHTML Packers']
                = $dn_data ['Delivery Note XHTML Packers'];
        } else {
            $this->data ['Delivery Note XHTML Packers'] = '';
        }

        if (isset($dn_data ['Delivery Note Number Packers'])) {
            $this->data ['Delivery Note Number Packers']
                = $dn_data ['Delivery Note Number Packers'];
        } else {
            $this->data ['Delivery Note Number Packers'] = '';
        }

        if (isset($dn_data ['Delivery Note Packers IDs'])) {
            $this->data ['Delivery Note Packers IDs']
                = $dn_data ['Delivery Note Packers IDs'];
        } else {
            $this->data ['Delivery Note Packers IDs'] = '';
        }

        $this->data ['Delivery Note ID'] = $dn_data ['Delivery Note ID'];
        $this->data ['Delivery Note File As']
                                         = $dn_data ['Delivery Note File As'];

        $customer = new Customer ($dn_data['Delivery Note Customer Key']);


        $this->data ['Delivery Note Customer Key'] = $customer->id;
        $this->data ['Delivery Note Customer Name']
                                                   = $customer->data['Customer Name'];
        $this->data ['Delivery Note Store Key']
                                                   = $customer->data['Customer Store Key'];
        $store                                     = new Store(
            $this->data ['Delivery Note Store Key']
        );

        $this->data['Delivery Note Show in Warehouse Orders']
            = $store->data['Store Show in Warehouse Orders'];


        if (isset($dn_data ['Delivery Note Metadata'])) {
            $this->data ['Delivery Note Metadata']
                = $dn_data ['Delivery Note Metadata'];
        } else {
            if ($order) {

                $this->data ['Delivery Note Metadata']
                    = $order->data ['Order Original Metadata'];
            } else {
                $this->data ['Delivery Note Metadata'] = '';
            }
        }

        if (isset($dn_data ['Delivery Note Date Created'])) {
            $this->data ['Delivery Note Date Created']
                = $dn_data ['Delivery Note Date Created'];
        } else {
            $this->data ['Delivery Note Date Created'] = gmdate('Y-m-d H:i:s');
        }
        if (isset($dn_data ['Delivery Note State'])) {
            $this->data ['Delivery Note State']
                = $dn_data ['Delivery Note State'];
        } else {
            $this->data ['Delivery Note State'] = 'Ready to be Picked';
        }


        $this->data ['Delivery Note Type']  = $dn_data ['Delivery Note Type'];
        $this->data ['Delivery Note Title'] = $dn_data ['Delivery Note Title'];

        $this->data ['Delivery Note Dispatch Method']
            = $dn_data ['Delivery Note Dispatch Method'];


        if ($this->data ['Delivery Note Dispatch Method'] == 'Collection') {

            $this->data ['Delivery Note Shipper Code'] = '';
            $store                                     = new Store(
                $this->data['Delivery Note Store Key']
            );
            $collection_address                        = new Address(
                $store->data['Store Collection Address Key']
            );
            if ($collection_address->id) {
                $this->data ['Delivery Note Country 2 Alpha Code']
                                                            = $collection_address->data['Address Country 2 Alpha Code'];
                $this->data ['Delivery Note Country Code']
                                                            = $collection_address->data['Address Country Code'];
                $this->data ['Delivery Note World Region Code']
                                                            = $collection_address->get(
                    'Address World Region Code'
                );
                $this->data ['Delivery Note Town']
                                                            = $collection_address->data['Address Town'];
                $this->data ['Delivery Note Postal Code']
                                                            = $collection_address->data['Address Postal Code'];
                $this->data ['Delivery Note XHTML Ship To'] = '<div><b>'._(
                        'For collection'
                    ).'</b></div><div style="color:#777">'.$collection_address->display('xhtml').'</div>';


            } else {

                include_once 'class.Country.php';
                $country = new Country(
                    '2alpha', $store->data['Store Home Country Code 2 Alpha']
                );


                $this->data ['Delivery Note Country 2 Alpha Code']
                                                            = $country->data['Country 2 Alpha Code'];
                $this->data ['Delivery Note Country Code']
                                                            = $country->data['Country Code'];
                $this->data ['Delivery Note World Region Code']
                                                            = $country->data['World Region Code'];
                $this->data ['Delivery Note Town']          = '';
                $this->data ['Delivery Note Postal Code']   = '';
                $this->data ['Delivery Note XHTML Ship To'] = '<div><b>'._(
                        'For collection'
                    ).'</b></div>';

            }


            $this->data ['Delivery Note Ship To Key'] = 0;


        } else {

            if (isset($dn_data ['Delivery Note Shipper Code'])) {
                $this->data ['Delivery Note Shipper Code']
                    = $dn_data ['Delivery Note Shipper Code'];
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

            $this->data ['Delivery Note Ship To Key'] = $ship_to->id;
            $this->data ['Delivery Note XHTML Ship To']
                                                      = $ship_to->data['Ship To XHTML Address'];
            $this->data ['Delivery Note Country 2 Alpha Code']
                                                      = ($ship_to->data['Ship To Country 2 Alpha Code'] == '' ? 'XX' : $ship_to->data['Ship To Country 2 Alpha Code']);

            $this->data ['Delivery Note Country Code']
                                                            = ($ship_to->data['Ship To Country Code'] == '' ? 'UNK' : $ship_to->data['Ship To Country Code']);
            $this->data ['Delivery Note World Region Code'] = $ship_to->get(
                'World Region Code'
            );
            $this->data ['Delivery Note Town']
                                                            = $ship_to->data['Ship To Town'];
            $this->data ['Delivery Note Postal Code']
                                                            = $ship_to->data['Ship To Postal Code'];


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
            $this->id
                                              = $this->data ['Delivery Note Key'];
            $this->get_data('id', $this->id);


        } else {
            exit ("$sql \n Error can not create dn header");
        }

    }




    function get($key) {


        if (!$this->id) {
            return '';
        }

        switch ($key) {

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






    //  New methods


    function update_item($data) {


        switch ($data['field']) {
            case 'Picked':
                return $this->update_item_picked_quantity($data);
                break;
            case 'Supplier Delivery Checked Quantity':
                return $this->update_item_delivery_checked_quantity($data);
                break;
            case 'Supplier Delivery Placed Quantity':
                return $this->update_item_delivery_placed_quantity($data);
                break;
            default:

                break;
        }


    }



function update_state($value, $options = '', $metadata = array()) {


        $sql=spintf('selct');
}


    function update_item_picked_quantity($data) {

        include_once('class.Location.php');


        print_r($data);

        $date=gmdate('Y-m-d H:i:s');

        $item_key          = $data['item_key'];
        $qty               = $data['qty'];


        $sql=sprintf('select `Picked`,`Required`,`Given`,`Location Key` from `Inventory Transaction Fact` ITF where `Inventory Transaction Key`=%d',$data['transaction_key']);

       // print "$sql";

        $transaction_value=0;

        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $qty_delta=$qty-$row['Picked'];

                if($qty_delta>0){

                    $location=new Location($row['Location Key']);

                    $note='xx';


                    $sql = sprintf("update `Inventory Transaction Fact` set `Note`=%s,`Picked`=%f,`Inventory Transaction Quantity`=%f,`Inventory Transaction Amount`=%f,`Date Picked`=%s,`Date`=%s ,`Picker Key`=%s where `Inventory Transaction Key`=%d  ",
                         prepare_mysql ($note),
                         $qty,
                         -1*$qty,
                         $transaction_value,
                         prepare_mysql ($date),
                         prepare_mysql ($date),
                         prepare_mysql ($data['picker_key']),
                        $data['transaction_key']
                    );


                    print $sql;
                    //       $this->exec($sql);


                }else{
                    
                }







        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }




        exit;

        // Todo calculate taxed, 0 tax for now
        //include_once 'class.TaxCategory.php';
        //$tax_category=new TaxCategory($data['tax_code']);
        //$tax_amount=$tax_category->calculate_tax($data ['amount']);


        include_once 'class.SupplierPart.php';
        $supplier_part = new SupplierPart($item_key);


        $date            = gmdate('Y-m-d H:i:s');
        $transaction_key = '';

        if ($qty == 0) {

            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d AND `Supplier Part Key`=%d ",
                $this->id, $item_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    if ($row['Purchase Order Key'] == '') {

                        $sql = sprintf(
                            "DELETE FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Transaction Fact Key`=%d ", $row['Purchase Order Transaction Fact Key']
                        );
                        $this->db->exec($sql);
                    } else {

                        $sql = sprintf(
                            "UPDATE  `Purchase Order Transaction Fact` SET  `Supplier Delivery Key`=NULL,`Supplier Delivery Received Location Key`=1, `Supplier Delivery Quantity`=0,`Supplier Delivery Checked Quantity`=0,`Supplier Delivery Damaged Quantity`=0,`Supplier Delivery Placed Quantity`=0,`Supplier Delivery Net Amount`=0,`Supplier Delivery Tax Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Counted`='No' WHERE `Purchase Order Transaction Fact Key`=%d ",
                            $row['Purchase Order Transaction Fact Key']
                        );

                        print $sql;
                        $this->db->exec($sql);

                    }
                    $transaction_key = $row['Purchase Order Transaction Fact Key'];

                }
            }


            $amount    = 0;
            $subtotals = '';


        } else {


            $amount = $qty * $supplier_part->get('Supplier Part Unit Cost') * $supplier_part->part->get('Part Units Per Package') * $supplier_part->get('Supplier Part Packages Per Carton');
            if (is_numeric($supplier_part->get('Supplier Part Carton CBM'))) {
                $cbm = $qty * $supplier_part->get('Supplier Part Carton CBM');
            } else {
                $cbm = 'NULL';
            }


            if (is_numeric($supplier_part->part->get('Part Package Weight'))) {
                $weight = $qty * $supplier_part->part->get(
                        'Part Package Weight'
                    ) * $supplier_part->get(
                        'Supplier Part Packages Per Carton'
                    );
            } else {
                $weight = 'NULL';
            }


            // Todo calculate taxed, 0 tax for now
            $tax_amount = 0;
            $tax_code   = '';


            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d AND `Supplier Part Key`=%d ", $this->id,
                $item_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $sql = sprintf(
                        "update`Purchase Order Transaction Fact` set  `Supplier Delivery Quantity`=%f,`Supplier Delivery Last Updated Date`=%s,`Supplier Delivery Net Amount`=%f ,`Supplier Delivery Tax Amount`=%f ,`Supplier Delivery CBM`=%s,`Supplier Delivery Weight`=%s  where  `Purchase Order Transaction Fact Key`=%d ",
                        $qty, prepare_mysql($date), $amount, $tax_amount, $cbm, $weight, $row['Purchase Order Transaction Fact Key']
                    );

                    $this->db->exec($sql);

                    $transaction_key = $row['Purchase Order Transaction Fact Key'];
                } else {

                    $sql = sprintf(
                        "INSERT INTO `Purchase Order Transaction Fact` (`Supplier Part Key`,`Supplier Part Historic Key`,`Currency Code`,`Supplier Delivery Last Updated Date`,`Supplier Delivery Transaction State`,
					`Supplier Key`,`Agent Key`,`Supplier Delivery Key`,`Supplier Delivery Quantity`,`Supplier Delivery Net Amount`,`Supplier Delivery Tax Amount`,`Note to Supplier`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,
					`User Key`,`Creation Date`
					)
					VALUES (%d,%d,%s,%s,%s,
					 %d,%s,%d,%.6f,%.2f,%.2f,%s,%s,%s,
					 %d,%s
					 )", $item_key, $item_historic_key, prepare_mysql(
                        $this->get('Supplier Delivery Currency Code')
                    ), prepare_mysql($date), prepare_mysql($this->get('Supplier Delivery State')),

                        $supplier_part->get('Supplier Part Supplier Key'), ($supplier_part->get('Supplier Part Agent Key') == ''
                        ? 'Null'
                        : sprintf(
                            "%d", $supplier_part->get('Supplier Part Agent Key')
                        )), $this->id, $qty, $amount, $tax_amount, prepare_mysql(
                            $supplier_part->get(
                                'Supplier Part Note to Supplier'
                            ), false
                        ), $cbm, $weight, $this->editor['User Key'], prepare_mysql($date)


                    );

                    $this->db->exec($sql);
                    $transaction_key = $this->db->lastInsertId();


                }


                $subtotals = money(
                    $amount, $this->get('Supplier Delivery Currency Code')
                );

                if ($weight > 0) {
                    $subtotals .= ' '.weight($weight);
                }
                if ($cbm > 0) {
                    $subtotals .= ' '.number($cbm).' m³';
                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }

        $this->update_totals();

        $this->update_metadata = array(
            'class_html' => array(
                'Supplier_Delivery_Total_Amount'                  => $this->get('Total Amount'),
                'Supplier_Delivery_Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                'Supplier_Delivery_Weight'                        => $this->get(
                    'Weight'
                ),
                'Supplier_Delivery_CBM'                           => $this->get(
                    'CBM'
                ),

            )
        );


        return array(
            'transaction_key' => $transaction_key,
            'subtotals'       => $subtotals,
            'to_charge'       => money(
                $amount, $this->data['Purchase Order Currency Code']
            ),
            'qty'             => $qty + 0
        );


    }



}


?>
