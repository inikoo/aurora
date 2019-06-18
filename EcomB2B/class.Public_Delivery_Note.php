<?php
/*
   About:
  Author: Raul Perusquia <rulovico@gmail.com>
Created: 29 August 2018 at 04:13:33 GMT+8, Kuala Lumpur, Malaysia
  Copyright (c) 2009,Inikoo

  Version 2.0
*/


include_once 'class.DBW_Table.php';


class Public_Delivery_Note extends DBW_Table {

    var $update_stock = true;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $arg4 = false) {


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
                        return _('Dispatched');
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
                        return _('Dispatched');
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
            case('Date'):

                return strftime("%e %b %y", strtotime($this->data['Delivery Note Date'].' +0:00'));

                break;
            case('Creation Date'):
                return strftime("%e %b %y %H:%M", strtotime($this->data['Delivery Note Date Created'].' +0:00'));
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

                return $consignment;
                break;

            case 'Number Parcels':

                if (!is_numeric($this->data['Delivery Note Number Parcels'])) {
                    return '';
                }


                switch ($this->data['Delivery Note Parcel Type']) {
                    case('Box'):
                        $parcel_type = sprintf('<i class="fa fa-archive" title="%s" aria-hidden="true"></i>\'', ngettext('box', 'boxes', $this->data['Delivery Note Number Parcels']));
                        break;
                    case('Pallet'):
                        $parcel_type = sprintf('<i class="fa fa-calendar  fa-flip-vertical" title="%s" aria-hidden="true"></i>', ngettext('pallet', 'pallets', $this->data['Delivery Note Number Parcels']));
                        break;
                    case('Envelope'):
                        $parcel_type = sprintf('<i class="fa fa-envelope" title="%s" aria-hidden="true"></i>\'', ngettext('envelope', 'envelopes', $this->data['Delivery Note Number Parcels']));


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

        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Delivery Note '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
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
