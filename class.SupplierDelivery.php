<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2016 at 19:05:57 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';
include_once 'trait.SupplierDeliveryAiku.php';

class SupplierDelivery extends DB_Table {
    use AttachmentSubject;
    use NotesSubject;
    use SupplierDeliveryAiku;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Supplier Delivery';
        $this->ignore_fields = array('Supplier Delivery Key');


        if (is_string($arg1)) {
            if (preg_match('/new|create/i', $arg1)) {
                $this->find($arg2, 'create');

                return;
            }
            if (preg_match('/find/i', $arg1)) {
                $this->find($arg2, $arg3);

                return;
            }
        }

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

    }


    function find($raw_data, $options) {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;
        $create          = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = "SELECT `Supplier Delivery Key` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Public ID`=?  AND `Supplier Delivery Parent`=? AND `Supplier Delivery Parent Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Supplier Delivery Public ID'],
                $data['Supplier Delivery Parent'],
                $data['Supplier Delivery Parent Key']

            )
        );
        if ($row = $stmt->fetch()) {
            $this->found     = true;
            $this->found_key = $row['Supplier Delivery Key'];

            $this->found     = true;
            $this->found_key = $row['Supplier Delivery Key'];
            $this->get_data('id', $this->found_key);
            $this->duplicated_field = 'Supplier Delivery Public ID';
        }


        if ($this->found_key) {
            $this->get_data('id', $this->found_key);
        }

        if ($create and !$this->found_key) {

            $this->create($data);

        }


    }

    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Key`=%d", $id
            );
        } elseif ($key == 'public id' or $key == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Public ID`=%s", prepare_mysql($id)
            );
        } else {
            exit('Supplier delivery get_data unknown key:'.$key);
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Delivery Key'];
        }
    }

    function create($data) {

        $account = get_object('Account', 1);


        $parent = get_object(
            $data['Supplier Delivery Parent'], $data['Supplier Delivery Parent Key']
        );


        if (!$parent->id) {
            $this->error = true;
            $this->msg   = 'wrong parent';

            return;
        }


        include_once 'utils/currency_functions.php';
        $data['Supplier Delivery Currency Exchange'] = currency_conversion(
            $this->db, $data['Supplier Delivery Currency Code'], $account->get('Account Currency'), '- 15 minutes'
        );


        $data['Supplier Delivery Date']              = gmdate('Y-m-d H:i:s');
        $data['Supplier Delivery Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Supplier Delivery Last Updated Date'] = gmdate('Y-m-d H:i:s');
        $data['Supplier Delivery Date Type']         = 'Creation';

        $data['Supplier Delivery File As'] = get_file_as($data['Supplier Delivery Public ID']);


        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                if ($value == '') {
                    $base_data[$key] = null;
                } else {
                    $base_data[$key] = _trim($value);
                }

            }
        }


        $sql = sprintf(
            "INSERT INTO `Supplier Delivery Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }

        if ($stmt->execute()) {


            $this->new = 1;

            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);


            $history_data = array(
                'History Abstract' => _('Supplier delivery created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            if ($this->data['Supplier Delivery Parent'] != 'Order') {
                $parent->update_purchase_orders();
            }

            $this->model_updated( 'new', $this->id);

        } else {
            print_r($stmt->errorInfo());
            print "Error can not create supplier delivery\n $sql\n";
        }


    }


    function get_order_data() {


        $sql = sprintf(
            'SELECT * FROM `Purchase Order Dimension` WHERE `Purchase Order Key`=%d ', $this->get('Supplier Delivery Purchase Order Key')
        );


        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }


    }


    function get($key = '') {

        global $account;
        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case ('State'):
                switch ($this->data['Supplier Delivery State']) {


                    // //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Cancelled'

                    case 'InProcess':
                        return _('In process');
                        break;
                    case 'Consolidated':
                        return _('Consolidated');
                        break;
                    case 'Dispatched':
                        return _('Dispatched');
                        break;

                    case 'Received':
                        return _('Received');
                        break;
                    case 'Checked':
                        return _('Checked');
                        break;
                    case 'Placed':
                        return _('Booked in');
                        break;
                    case 'Costing':


                        return _('Booked in').', '._('checking costing');


                        break;
                    case 'InvoiceChecked':
                        if ($this->data['Supplier Delivery Type'] == 'Production') {
                            return _('Booked in');
                        } else {

                            if ($this->data['Supplier Delivery Invoice Public ID'] != '' and $this->data['Supplier Delivery Invoice Date'] != '') {
                                return _('Booked in').', '._('costing done').' <i class="fa fa-check success"></i>';

                            } else {
                                return _('Booked in').', '._('costing done');

                            }

                        }

                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    default:
                        break;
                }

                break;

            case ('Agent State'):
                switch ($this->data['Supplier Delivery State']) {
                    case 'InProcess':
                        return _('In process');
                        break;
                    case 'Consolidated':
                        return _('Consolidated');
                        break;
                    case 'Dispatched':
                        return _('Dispatched');
                        break;
                    case 'Received':
                        return _('Received by client');
                        break;
                    case 'Checked':
                        return _('Received by client').' ('._('checked').')';
                        break;
                    case 'Placed':
                        return _('Received by client').' ('._('Booked in').')';
                        break;
                    case 'Costing':
                        return _('Received by client').' ('._('Checking costs').')';
                        break;
                    case 'InvoiceChecked':
                        return _('Done');


                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    default:
                        break;
                }

                break;
            case ('Type'):
                switch ($this->data['Supplier Delivery Type']) {
                    case 'Parcel':
                        return _('Parcel');
                    case 'Container':
                        return _('Container');
                    case 'Production':
                        return _('Job order');
                    default:
                        return $this->data['Supplier Delivery Type'];
                }
            case ('Return State'):
                switch ($this->data['Supplier Delivery State']) {


                    // //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Cancelled'

                    case 'Dispatched':
                        return _('Sent by customer');
                        break;

                    case 'Received':
                        return _('Received');
                        break;
                    case 'Checked':
                        return _('Checked');
                        break;
                    case 'Placed':
                    case 'Costing':
                    case 'InvoiceChecked':
                        return _('Booked in');
                        break;

                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    default:
                        break;
                }

                break;
            case 'State Index':


                switch ($this->data['Supplier Delivery State']) {
                    case 'InProcess':
                        return 10;

                    case 'Consolidated':
                        return 20;



                    case 'Dispatched':
                        return 30;

                    case 'Received':
                        return 40;

                    case 'Checked':
                        return 50;

                    case 'Placed':
                        return 100;

                    case 'Costing':
                        return 105;

                    case 'InvoiceChecked':
                        return 110;

                    case 'Cancelled':
                        return -10;

                    default:
                        return 0;

                }


            case 'Progress Date':

                switch ($this->data['Supplier Delivery State']) {
                    case 'InProcess':
                        return $this->get('Creation Date');
                        break;
                    case 'Dispatched':
                        return $this->get('Dispatched Date');
                        break;
                    case 'Received':
                    case 'Checked':
                        return $this->get('Received Date');
                        break;
                    case 'Placed':
                        return $this->get('Placed Date');
                        break;
                }


            case 'Progress':

                switch ($this->data['Supplier Delivery State']) {
                    case 'Dispatched':
                        return '('._('Dispatched').')';
                        break;
                    case 'Received':
                        $progress = '('._('Received').')';

                        if ($this->get('Supplier Delivery Number Checked Items') > 0) {
                            $progress = '('._('Checking').' '.percentage($this->get('Supplier Delivery Number Checked Items'), $this->get('Supplier Delivery Number Dispatched Items'), 0).')';

                        }
                        if ($this->get('Supplier Delivery Number Placed Items') > 0) {
                            $progress = '('._('Placing').' '.percentage($this->get('Supplier Delivery Number Placed Items'), $this->get('Supplier Delivery Number Dispatched Items'), 0).')';

                        }

                        return $progress;
                        break;
                    case 'Checked':
                        $progress = '('._('Checked').')';

                        if ($this->get('Supplier Delivery Number Placed Items') > 0) {
                            $progress = '('._('Placing').' '.percentage($this->get('Supplier Delivery Number Placed Items'), $this->get('Supplier Delivery Number Dispatched Items'), 0).')';

                        }

                        return $progress;
                        break;
                }

                break;
            case 'Checked Percentage or Date':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Supplier Delivery Checked Date') == '') {
                        return '';
                    }

                    return strftime("%e %b %Y", strtotime($this->get('Supplier Delivery Checked Date')));

                } elseif ($this->get('State Index') < 40) {
                    return '';
                } elseif ($this->get('State Index') < 50) {
                    return percentage($this->get('Supplier Delivery Number Checked Items'), $this->get('Supplier Delivery Number Dispatched Items'));
                } else {
                    if ($this->data['Supplier Delivery Checked Date'] == '') {
                        return '';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->data['Supplier Delivery Checked Date'].' +0:00')
                    );
                }

                break;
            case 'Placed Percentage or Date':


                if ($this->get('State Index') < 40) {
                    return '';
                } elseif ($this->get('State Index') < 50) {
                    if ($this->get('Supplier Delivery Number Placed Items') > 0) {
                        return percentage(
                            $this->get('Supplier Delivery Number Placed Items'), $this->get(
                            'Supplier Delivery Number Dispatched Items'
                        )
                        );
                    } else {
                        return '';
                    }
                } elseif ($this->get('State Index') < 100) {
                    return percentage($this->get('Supplier Delivery Number Placed Items'), $this->get('Supplier Delivery Number Received and Checked Items'));
                } else {
                    if ($this->data['Supplier Delivery Checked Date'] == '') {
                        return '';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->data['Supplier Delivery Placed Date'].' +0:00')
                    );
                }

                break;
            case 'Costing Date':
                if ($this->get('State Index') <= 100) {
                    return '';
                } elseif ($this->get('State Index') == 105) {
                    if ($this->data['Supplier Delivery Start Costing Date'] == '') {
                        $date = '';
                    } else {
                        $date = strftime(
                            "%e %b %Y", strtotime($this->data['Supplier Delivery Start Costing Date'].' +0:00')
                        );
                    }

                    return sprintf('<span title="%s" class="very_discreet italic">%s</span>', $date, _('In process'));


                } else {
                    if ($this->data['Supplier Delivery Invoice Checked Date'] == '') {
                        return '';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->data['Supplier Delivery Invoice Checked Date'].' +0:00')
                    );
                }

                break;
            case 'Weight':
                include_once 'utils/natural_language.php';


                if ($this->data['Supplier Delivery Weight'] == '') {
                    if ($this->get('Supplier Delivery Number Items') > 0) {
                        return '<i class="fa fa-exclamation-circle error"></i> <span class="italic very_discreet error">'._('Unknown weight').'</span>';
                    }
                } else {
                    return weight($this->get('Supplier Delivery Weight'));
                }


                break;


            case 'CBM':
                if ($this->data['Supplier Delivery CBM'] == '') {
                    if ($this->get('Supplier Delivery Number Items') > 0) {
                        return '<i class="fa fa-exclamation-circle error"></i> <span class="italic very_discreet error">'._('Unknown CBM').'</span>';
                    }
                } else {
                    return ($this->get('Supplier Delivery Missing CBMs') > 0 ? '<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's products without CBM").'" ></i> ' : '').number($this->data['Supplier Delivery CBM']).' m³';
                }
                break;


            case 'Formatted Invoice Public ID':
                if ($this->data['Supplier Delivery Invoice Public ID'] == '') {

                    if ($this->data['Supplier Delivery State'] == 'InvoiceChecked') {
                        return sprintf('<span class="italic very_discreet" title="%s">%s</span>', _('Supplier delivery invoice number missing'), _('No invoice number'));
                    } else {
                        return '';

                    }
                }

                return $this->data['Supplier Delivery Invoice Public ID'];

                break;
            case 'Formatted Invoice Date':
                if ($this->data['Supplier Delivery Invoice Date'] == '') {

                    if ($this->data['Supplier Delivery State'] == 'InvoiceChecked') {
                        return sprintf('<span class="error discreet" title="%s">%s</span>', _('Supplier delivery invoice date missing'), _('Date missing'));
                    } else {
                        return '';

                    }
                }

                return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery Invoice Date'].' +0:00'));

                break;

            case 'Estimated Receiving Date':
            case 'Creation Date':
            case 'Checked Date':
            case 'Dispatched Date':
            case 'Invoice Date':

            case 'Placed Date':
            case 'Cancelled Date':
                if ($this->data['Supplier Delivery '.$key] == '') {
                    return '';
                }

                return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery '.$key].' +0:00'));

                break;
            case 'Received Date':
                if ($this->get('State Index') < 0) {
                    if ($this->get('Supplier Delivery Received Date') == '') {
                        return '';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->get('Supplier Delivery Received Date'))
                    );

                } elseif ($this->get('State Index') >= 40) {

                    if ($this->get('Supplier Delivery Received Date') == '') {
                        return 'Error';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->get('Supplier Delivery Received Date'))
                    );
                } else {

                    if ($this->data['Supplier Delivery Estimated Receiving Date']) {
                        return '<span class="discreet"><i class="fa fa-thumbtack" aria-hidden="true"></i> '.strftime(
                                "%e %b %Y", strtotime($this->get('Estimated Receiving Date'))
                            ).'</span>';
                    } else {

                        if ($this->data['Supplier Delivery State'] == 'InProcess') {
                            $parent = get_object(
                                $this->data['Supplier Delivery Parent'], $this->data['Supplier Delivery Parent Key']
                            );
                            if ($parent->get(
                                    $parent->table_name.' Delivery Days'
                                ) and is_numeric(
                                    $parent->get(
                                        $parent->table_name.' Delivery Days'
                                    )
                                )) {
                                return '<span class="discreet italic">'.strftime(
                                        "%d-%m-%Y", strtotime(
                                                      'now +'.$parent->get(
                                                          $parent->table_name.' Delivery Days'
                                                      ).' days'
                                                  )
                                    ).'</span>';

                            } else {
                                return '&nbsp;';
                            }
                        } else {

                            $parent = get_object(
                                $this->data['Supplier Delivery Parent'], $this->data['Supplier Delivery Parent Key']
                            );
                            if ($parent->get(
                                    $parent->table_name.' Delivery Days'
                                ) and is_numeric(
                                    $parent->get(
                                        $parent->table_name.' Delivery Days'
                                    )
                                )) {
                                return '<span class="discreet italic">'.strftime(
                                        "%d-%m-%Y", strtotime(
                                                      $this->get(
                                                          'Supplier Delivery Submitted Date'
                                                      ).' +'.$parent->get(
                                                          $parent->table_name.' Delivery Days'
                                                      ).' days'
                                                  )
                                    ).'</span>';

                            } else {
                                return '<span class="super_discreet">'._(
                                        'Unknown'
                                    ).'</class>';
                            }
                        }

                    }
                }

                break;

            case 'Items Amount':
            case 'Extra Costs Amount':
            case 'Total Amount':
            case 'Purchase Order Amount':

                return money(
                    $this->data['Supplier Delivery '.$key], $this->data['Supplier Delivery Currency Code']
                );
                break;

            case 'AC Subtotal Amount':
            case 'AC Extra Costs Amount':
            case 'AC Total Amount':


                return money(
                    $this->data['Supplier Delivery '.$key], $account->get('Account Currency')
                );
                break;

            case 'PO Creation Date':
            case 'PO Submitted Date':


                $key = preg_replace('/^PO /', '', $key);

                if ($this->data['Purchase Order '.$key] == '') {
                    return '';
                }

                return strftime(
                    "%e %b %Y", strtotime($this->data['Purchase Order '.$key].' +0:00')
                );

                break;


            case 'Number Items':
            case 'Number Ordered Items':
            case 'Number Items Without PO':
                return number($this->data ['Supplier Delivery '.$key]);
                break;


        }


        if (preg_match(
            '/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/', $key
        )) {
            $amount = 'Supplier Delivery '.$key;

            // print $amount;

            return money(
                $this->data[$amount], $this->data['Supplier Delivery Currency Code']
            );
        }

        if (preg_match(
            '/^(Total|Items|(Shipping |Charges )?Net).*(Amount Account Currency)$/', $key
        )) {
            $key    = preg_replace('/ Account Currency/', '', $key);
            $amount = 'Supplier Delivery '.$key;

            return money(
                $this->data['Supplier Delivery Currency Exchange'] * $this->data[$amount], $account->get('Account Currency')
            );
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists('Supplier Delivery '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return '';

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {
        switch ($field) {
            case 'Supplier Delivery State':
                $this->update_state($value, $options, $metadata);
                break;
            case 'Supplier Delivery Estimated Receiving Date':
                $this->update_field($field, $value, $options);

                $sql = sprintf(
                    "SELECT `Supplier Part Key`,`Supplier Delivery Units` FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d", $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $supplier_part         = get_object('SupplierPart', $row['Supplier Part Key']);
                        $supplier_part->editor = $this->editor;
                        if (isset($supplier_part->part)) {
                            $supplier_part->part->update_next_deliveries_data();
                        }


                    }
                }

                break;

            case 'Supplier Delivery Invoice Public ID':

                $this->update_field($field, $value, $options);

                $this->update_metadata = array(
                    'class_html' => array(
                        'Formatted_Invoice_Public_ID' => $this->get('Formatted Invoice Public ID'),
                        'Formatted_Invoice_Date'      => $this->get('Formatted Invoice Date'),
                        'Supplier_Delivery_State'     => $this->get('State'),

                    )
                );


                break;
            case 'Supplier Delivery Invoice Date':

                $this->update_field($field, $value, $options);


                $sql = 'update `Purchase Order Transaction Fact` set `Purchase Order Transaction Invoice Date`=? where `Supplier Delivery Key`=? ';


                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $value,
                        $this->id
                    ]
                );

                $this->update_metadata = array(
                    'class_html' => array(
                        'Formatted_Invoice_Public_ID' => $this->get('Formatted Invoice Public ID'),
                        'Formatted_Invoice_Date'      => $this->get('Formatted Invoice Date'),
                        'Supplier_Delivery_State'     => $this->get('State'),

                    )
                );


                break;
            case 'History Note':
                $this->add_note($value, '', '', $metadata['deletable']);
                break;
            default:


                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }

                break;
        }


    }

    function update_state($value, $options = '', $metadata = array()) {

        $date = gmdate('Y-m-d H:i:s');


        $skip_update_totals = false;

        $operations = array();

        if ($value == 'InProcess or Dispatched') {

            if ($this->get('Supplier Delivery Placed Items') == 'Yes') {
                $this->error = true;
                $this->msg   = "Can't roll back delivery status with placed items";

                return;
            }


            if ($this->get('Supplier Delivery Dispatched Date') == '') {
                $value = 'InProcess';
            } else {
                $value    = 'Dispatched';
                $metadata = array(
                    'Supplier Delivery Dispatched Date' => $this->get(
                        'Supplier Delivery Dispatched Date'
                    )
                );
            }
        }


        switch ($value) {
            case 'InProcess':

                $this->fast_update(
                    array(
                        'Supplier Delivery Dispatched Date' => '',
                        'Supplier Delivery State'           => $value,
                    )
                );


                $sql = sprintf(
                    'UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Last Updated Date`=%s WHERE `Supplier Delivery Key`=%d ', prepare_mysql($date), $this->id
                );
                $this->db->exec($sql);

                $this->update_supplier_delivery_items_state();

                $operations = array(
                    'delete_operations',
                    'received_operations',
                    'dispatched_operations'
                );

                $history_data = array(
                    'History Abstract' => _('Supplier delivery set as in process'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );

                break;


            case 'Dispatched':


                $this->update_field(
                    'Supplier Delivery Dispatched Date', $date, 'no_history'
                );
                $this->update_field(
                    'Supplier Delivery State', $value, 'no_history'
                );
                foreach ($metadata as $key => $_value) {

                    $this->update_field($key, $_value, 'no_history');
                }


                $sql = sprintf(
                    'UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Last Updated Date`=%s WHERE `Supplier Delivery Key`=%d ', prepare_mysql($date), $this->id
                );
                $this->db->exec($sql);


                $this->update_supplier_delivery_items_state();


                $operations = array(
                    'cancel_operations',
                    'undo_dispatched_operations',
                    'received_operations'
                );

                $history_data = array(
                    'History Abstract' => _('Supplier delivery set as dispatched'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );

                break;
            case 'Received':


                $this->update_field(
                    'Supplier Delivery Received Date', $date, 'no_history'
                );
                $this->update_field(
                    'Supplier Delivery State', $value, 'no_history'
                );
                foreach ($metadata as $key => $_value) {
                    $this->update_field($key, $_value, 'no_history');
                }

                $operations = array(
                    'cancel_operations',


                );

                $sql = sprintf(
                    'UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Last Updated Date`=%s WHERE `Supplier Delivery Key`=%d ', prepare_mysql($date), $this->id
                );
                $this->db->exec($sql);


                $this->update_supplier_delivery_items_state();


                $parent = get_object(
                    $this->data['Supplier Delivery Parent'], $this->data['Supplier Delivery Parent Key']
                );


                if ($parent->get('Parent Skip Checking') == 'Yes') {

                    $sql = sprintf(
                        'SELECT `Purchase Order Transaction Fact Key`,`Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Delivery Units` FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d  ', $this->id
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $item_data = array(
                                'field'             => 'Supplier Delivery Checked Units',
                                'item_key'          => $row['Supplier Part Key'],
                                'item_historic_key' => $row['Supplier Part Historic Key'],
                                'transaction_key'   => $row['Purchase Order Transaction Fact Key'],
                                'qty'               => $row['Supplier Delivery Units']

                            );
                            $this->update_item($item_data);

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());

                    }


                    $operations = array(
                        'cancel_operations',
                        'undo_send_operations',
                        'received_operations'
                    );

                }

                $history_data = array(
                    'History Abstract' => _('Supplier delivery set as received'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );

                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );


                break;
            case 'Checked':

                $this->update_field(
                    'Supplier Delivery Checked Date', $date, 'no_history'
                );
                $this->update_field(
                    'Supplier Delivery State', $value, 'no_history'
                );
                foreach ($metadata as $key => $_value) {
                    $this->update_field($key, $_value, 'no_history');
                }

                $operations = array(
                    'cancel_operations',
                    'undo_send_operations',
                    'received_operations'
                );

                $history_data = array(
                    'History Abstract' => _('Supplier delivery set as checked'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );


                break;
            case 'Placed':


                if ($this->data['Supplier Delivery State'] != 'Placed') {


                    $this->fast_update(
                        array(
                            'Supplier Delivery Placed Date' => $date,
                            'Supplier Delivery State'       => $value,
                        )
                    );


                    foreach ($metadata as $key => $_value) {

                        $this->fast_update(
                            array(
                                $key => $_value
                            )
                        );

                    }


                    $operations = array('costing_operations');


                    $manufacturer_key = 0;

                    $sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array('Archived')
                    );
                    if ($row = $stmt->fetch()) {
                        $manufacturer_key = $row['Supplier Production Supplier Key'];

                    }
                    if ($this->get('Supplier Delivery Parent') == 'Supplier' and $this->get('Supplier Delivery Parent Key') == $manufacturer_key) {


                        $sql = sprintf(
                            'select `Supplier Part Part SKU`,`Purchase Order Transaction Fact Key`,`Metadata` from `Purchase Order Transaction Fact`  POTF left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) 
                                        where `Supplier Delivery Key`=%d   group by `Supplier Part Part SKU` ', $this->id
                        );
                        if ($result = $this->db->query($sql)) {

                            foreach ($result as $row) {

                                if ($row['Metadata'] != '') {
                                    $metadata = json_decode($row['Metadata'], true);


                                    if (isset($metadata['placement_data'])) {
                                        foreach ($metadata['placement_data'] as $placement_data) {
                                            $sql = "insert into `ITF POTF Costing Done Bridge` (`ITF POTF Costing Done ITF Key`,`ITF POTF Costing Done POTF Key`)  values (?,?) ";
                                            $this->db->prepare($sql)->execute(
                                                array(
                                                    $placement_data['oif_key'],
                                                    $row['Purchase Order Transaction Fact Key']
                                                )
                                            );


                                        }


                                    }

                                }

                            }


                        }


                        $this->fast_update(
                            array(
                                'Supplier Delivery State'                => 'InvoiceChecked',
                                'Supplier Delivery Invoice Checked Date' => gmdate('Y-m-d H:i:s'),
                            )
                        );

                        $this->update_totals();

                        $operations = array();

                    }

                    $history_data = array(
                        'History Abstract' => _('Supplier delivery set as placed'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                }


                break;

            case 'Costing':


                if ($this->data['Supplier Delivery State'] == 'Placed' or $this->data['Supplier Delivery State'] == 'InvoiceChecked') {

                    $this->fast_update(
                        array(
                            'Supplier Delivery Start Costing Date'   => $date,
                            'Supplier Delivery State'                => $value,
                            'Supplier Delivery Invoice Checked Date' => '',
                        )
                    );

                    $operations = array('undo_costing_operations');

                    $history_data = array(
                        'History Abstract' => _('Setting costs'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                }


                break;
            case 'RedoCosting':


                if ($this->data['Supplier Delivery State'] == 'InvoiceChecked') {

                    $this->fast_update(
                        array(
                            'Supplier Delivery Start Costing Date'   => $date,
                            'Supplier Delivery State'                => 'Costing',
                            'Supplier Delivery Invoice Checked Date' => '',
                        )
                    );

                    $operations = array('');

                    $history_data = array(
                        'History Abstract' => _('Redoing costing'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );

                }

                $skip_update_totals = true;

                break;

            case 'InvoiceChecked':


                if ($this->data['Supplier Delivery State'] == 'Costing') {


                    $this->fast_update(
                        array(
                            'Supplier Delivery State'                => $value,
                            'Supplier Delivery Invoice Checked Date' => $date,
                        )
                    );
                    $this->update_supplier_delivery_items_state();

                    $operations = array('');

                    $history_data = array(
                        'History Abstract' => _('Costing done'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );

                }


                break;
            case 'Cancelled':


                if ($this->get('Supplier Delivery Placed Items') == 'Yes') {
                    $this->error = true;
                    $this->msg   = "Can't cancel delivery with placed items";

                    return;
                }

                $this->fast_update(
                    array(
                        'Supplier Delivery State'          => $value,
                        'Supplier Delivery Cancelled Date' => $date,
                    )
                );


                $sql = sprintf(
                    "UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Key`=NULL ,`Supplier Delivery Units`=0 ,`Supplier Delivery Checked Units`=NULL,`Supplier Delivery Placed Units`=NULL,`Supplier Delivery Net Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Transaction Placed`=NULL,`Supplier Delivery CBM`=NULL,`Supplier Delivery CBM`=NULL  ,`Supplier Delivery Last Updated Date`=NULL  WHERE `Supplier Delivery Key`=%d  ",
                    $this->id
                );
                $this->db->exec($sql);


                $history_data = array(
                    'History Abstract' => _('Supplier delivery cancelled'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );
                $operations = array();

                $this->update_supplier_delivery_items_state();


                $purchase_order = get_object('Purchase Order', $this->get('Supplier Delivery Purchase Order Key'));
                $purchase_order->update_purchase_order_items_state();

                break;

            default:
                exit('unknown state '.$value);
                break;
        }

        if (!$skip_update_totals) {
            $this->update_totals();

        }


        require_once 'utils/new_fork.php';
        $account = get_object('Account', 1);
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'                  => 'supplier_delivery_state_changed',
            'supplier_delivery_key' => $this->id,
            'editor'                => $this->editor
        ), $account->get('Account Code'), $this->db
        );


        $this->update_metadata = array(
            'class_html'  => array(
                'Supplier_Delivery_State'           => $this->get('State'),
                'Supplier_Delivery_Dispatched_Date' => '&nbsp;'.$this->get('Dispatched Date'),
                'Supplier_Delivery_Received_Date'   => '&nbsp;'.$this->get('Received Date'),
                'Supplier_Delivery_Checked_Date'    => '&nbsp;'.$this->get('Checked Date'),
                'Supplier_Delivery_Costing_Date'    => '&nbsp;'.$this->get('Costing Date'),

                'Supplier_Delivery_Number_Dispatched_Items' => $this->get('Number Dispatched Items'),
                'Supplier_Delivery_Number_Received_Items'   => $this->get('Number Received Items')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );

    }

    function update_supplier_delivery_items_state() {


        $sql  = "select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        while ($row = $stmt->fetch()) {
            $this->update_supplier_delivery_item_state($row['Purchase Order Transaction Fact Key']);


        }

    }

    function update_supplier_delivery_item_state($transaction_key, $update_part_next_deliveries_data = true) {

        $sql  = "select `Purchase Order Transaction Fact Key`,POTF.`Supplier Delivery Key` ,`Supplier Delivery Units`,`Supplier Delivery Checked Units`,`Supplier Delivery Placed Units`,
       `Supplier Delivery State`,`Purchase Order Key`,
                `Supplier Delivery Transaction State`,`Purchase Order Transaction Part SKU`
                from `Purchase Order Transaction Fact`  POTF left join 
                `Supplier Delivery Dimension` SD on (POTF.`Supplier Delivery Key`=SD.`Supplier Delivery Key`) 
                where POTF.`Purchase Order Transaction Fact Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($transaction_key)
        );
        if ($row = $stmt->fetch()) {


            $old_state = $row['Supplier Delivery Transaction State'];

            $sent    = $row['Supplier Delivery Units'];
            $checked = $row['Supplier Delivery Checked Units'];
            $placed  = $row['Supplier Delivery Placed Units'];


            $state = $this->get_supplier_order_item_state($sent, $checked, $placed);


            $sql = "update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`=? where `Purchase Order Transaction Fact Key`=? ";

            $this->db->prepare($sql)->execute(
                array(
                    $state,
                    $row['Purchase Order Transaction Fact Key']
                )
            );

            if ($old_state != $state) {
                /**
                 * @var $purchase_order \PurchaseOrder
                 */
                $purchase_order = get_object('Purchase Order', $row['Purchase Order Key']);
                $purchase_order->update_purchase_order_item_state($row['Purchase Order Transaction Fact Key'], false);

                if ($update_part_next_deliveries_data) {

                    /**
                     * @var $part \Part
                     */
                    $part = get_object('Part', $row['Purchase Order Transaction Part SKU']);
                    $part->update_next_deliveries_data();
                }
            }


        }

    }

    private function get_supplier_order_item_state($sent, $checked, $placed) {


        if ($this->data['Supplier Delivery State'] == 'Cancelled') {
            return 'Cancelled';

        }


        if ($checked == '' and $placed == '') {
            //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'


            if ($this->data['Supplier Delivery State'] == 'InProcess' or $this->data['Supplier Delivery State'] == 'Consolidated') {
                $state = 'InProcess';
            } elseif ($this->data['Supplier Delivery State'] == 'Dispatched') {

                if ($sent > 0) {
                    $state = 'Dispatched';
                } else {
                    $state = 'Cancelled';
                }

            } else {

                if ($sent > 0) {
                    $state = 'Received';
                } else {
                    $state = 'Cancelled';
                }

            }


        } elseif ($placed == '') {

            if ($sent == 0) {
                $state = 'Cancelled';
            } elseif ($checked == 0) {
                $state = 'NoReceived';
            } else {
                $state = 'Checked';

            }


        } else {
            if ($placed >= $checked) {
                $state = 'Placed';

            } else {
                $state = 'Checked';
            }

        }

        if ($this->data['Supplier Delivery State'] == 'InvoiceChecked' and $state == 'Placed') {
            $state = 'CostingDone';
        }

        //print "$sent,$checked,$placed $state\n";


        return $state;


    }

    function update_item($data) {

        switch ($data['field']) {
            /*
            case 'Supplier Delivery Units':
                return $this->update_item_delivery_units($data);
                break;
            */ case 'Supplier Delivery Checked Units':
            return $this->update_item_delivery_checked_units($data);

            case 'Supplier Delivery Placed SKOs':
                return $this->update_item_delivery_placed_skos($data);

            default:
                return false;
                break;
        }


    }

    function update_item_delivery_checked_units($data) {


        $date            = gmdate('Y-m-d H:i:s');
        $transaction_key = $data['transaction_key'];


        $units_qty = $data['qty'];


        $sql = "SELECT `Supplier Delivery Transaction Placed`,`Part SKU`,POTF.`Purchase Order Transaction Fact Key`,`Supplier Delivery Placed Units`,POTF.`Metadata`,`Purchase Order Transaction Part SKU`,POTF.`Supplier Part Key`
		      FROM `Purchase Order Transaction Fact`  POTF
              LEFT JOIN  `Part Dimension` P ON (P.`Part SKU`=POTF.`Purchase Order Transaction Part SKU`)
              WHERE  `Purchase Order Transaction Fact Key`=? ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($transaction_key)
        );
        if ($row = $stmt->fetch()) {
            /**
             * @var $part \Part
             */
            $part = get_object('Part', $row['Purchase Order Transaction Part SKU']);

            $sko_qty = $units_qty / $part->get('Part Units Per Package');
            if ($sko_qty < 0) {
                $sko_qty   = 0;
                $units_qty = 0;
            }

            $placement_sko_qty = $this->get_placement_quantity($transaction_key);

            $placement_units_qty = $placement_sko_qty * $part->get('Part Units Per Package');


            // print  $qty.' '.$placement_qty ;

            if (round($units_qty, 2) < round($placement_units_qty, 2)) {
                $this->error = true;
                $this->msg   = sprintf(_("%d SKOs have been already placed, checked SKOs can't be set up to %d"), $placement_sko_qty, $sko_qty);

                return false;


            }


            //$this->db->exec($sql);
            if ($units_qty == 0) {

                if (round($placement_units_qty, 2) > round($units_qty, 2)) {
                    $placed = 'Yes';
                } else {
                    $placed = 'NA';
                }
            } else {

                if (round($placement_units_qty, 2) >= round($units_qty, 2)) {
                    $placed = 'Yes';
                } else {
                    $placed = 'No';
                }

            }


            $sql = sprintf(
                "UPDATE `Purchase Order Transaction Fact` SET  `Supplier Delivery Checked Units`=%f,`Supplier Delivery Last Updated Date`=%s ,`Supplier Delivery Transaction Placed`=%s WHERE  `Purchase Order Transaction Fact Key`=%d ", $units_qty, prepare_mysql($date),
                prepare_mysql($placed), $transaction_key
            );
            //print "$sql\n";
            $this->db->exec($sql);


            $this->update_supplier_delivery_item_state($transaction_key, false);

            $quantity = ($sko_qty - $placement_sko_qty);


            if ($row['Metadata'] == '') {
                $metadata = array();
            } else {
                $metadata = json_decode($row['Metadata'], true);
            }


            $placement = '<div  class="placement_data mini_table right no_padding" style="padding-right:2px">';
            if (isset($metadata['placement_data'])) {

                foreach ($metadata['placement_data'] as $placement_data) {
                    $placement .= '<div style="clear:both;">
                                <div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
                                <div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._(
                            'SKO'
                        ).' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
                                </div>';


                }
            }
            $placement .= '<div style="clear:both"></div>';

            $placement_note = '<input type="hidden" class="note" /><i class="far add_note fa-sticky-note padding_right_5 button" aria-hidden="true"  onClick="show_placement_note(this)" ></i>';

            $placement .= '
                                <div style="clear:both"  id="place_item_'.$row['Purchase Order Transaction Fact Key'].'" class="place_item '.($placed == 'No' ? '' : 'hide').' " part_sku="'.$row['Part SKU'].'" transaction_key="'
                .$row['Purchase Order Transaction Fact Key'].'"  >
                    '.$placement_note.'
                
                                <input class="place_qty width_50 changed" value="'.($quantity + 0).'" ovalue="'.($quantity + 0).'"  min="1" max="'.$quantity.'"  >
                                <input class="location_code"  placeholder="'._('Location code').'"  >
                                <i  class="fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                                <div>';


            $part->update_next_deliveries_data();


            $this->update_totals();


            $this->update_state($this->get_state());


            if ($this->data['Supplier Delivery Placed Items'] == 'Yes') {
                $operations = array();

            } else {
                $operations = array('cancel_operations');

            }


            $this->update_metadata = array(
                'class_html'    => array(
                    'Supplier_Delivery_State'                             => $this->get('State'),
                    'Supplier_Delivery_Number_Placed_Items'               => $this->get('Number Placed Items'),
                    'Supplier_Delivery_Number_Received_and_Checked_Items' => $this->get('Number Received and Checked Items'),
                    'Supplier_Delivery_Checked_Percentage_or_Date'        => '&nbsp;'.$this->get('Checked Percentage or Date'),
                    'Supplier_Delivery_Placed_Percentage_or_Date'         => '&nbsp;'.$this->get('Placed Percentage or Date'),
                    'Supplier_Delivery_Number_Under_Delivered_Items'      => $this->get('Number Under Delivered Items'),
                    'Supplier_Delivery_Number_Over_Delivered_Items'       => $this->get('Number Over Delivered Items'),

                ),
                'checked_items' => $this->get('Supplier Delivery Number Received and Checked Items'),

                'placement'   => $placement,
                'operations'  => $operations,
                'state_index' => $this->get('State Index'),


            );


            if ($this->get('Supplier Delivery Number Received and Checked Items') == 0) {
                $this->update_metadata['hide'] = array('Mismatched_Items');
            } else {
                $this->update_metadata['show'] = array('Mismatched_Items');
            }


            return array(
                'transaction_key' => $transaction_key,
                'qty'             => $sko_qty + 0
            );

        } else {
            return false;
        }


    }

    function get_placement_quantity($transaction_key) {

        $placement_quantity = 0;

        $sql = sprintf(
            'SELECT POTF.`Metadata` FROM `Purchase Order Transaction Fact` POTF  WHERE  `Purchase Order Transaction Fact Key`=%d ', $transaction_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                if ($row['Metadata'] == '') {
                    $metadata = array();
                } else {
                    $metadata = json_decode($row['Metadata'], true);
                }

                if (isset($metadata['placement_data'])) {
                    foreach ($metadata['placement_data'] as $item) {
                        $placement_quantity += $item['qty'];
                    }

                }

            }

        }

        return $placement_quantity;
    }

    function update_totals() {


        $account=get_object('Account',1);

        $sql = sprintf(
            "SELECT  sum(if(`Supplier Delivery Transaction Placed`='Yes',1,0)) AS placed_items, 
        sum(if(`Supplier Delivery Transaction Placed`='No',1,0)) AS no_placed_items,  
        sum(`Supplier Delivery Weight`) AS  weight,sum(`Supplier Delivery CBM` )AS cbm ,
		 sum( if( `Supplier Delivery Checked Units` IS NULL,0,1)) AS checked_items,
		 sum( if( `Supplier Delivery Checked Units`>0,1,0)) AS received_checked_items,
		 sum(if(`Purchase Order Key`>0,1,0)) AS ordered_items,
		sum(if(`Supplier Delivery Units`>0,1,0))  num_items,

		sum(`Supplier Delivery Placed Units`) AS placed_qty,



		sum(`Purchase Order Net Amount`+`Purchase Order Extra Cost Amount`) AS po_items_amount,


		sum(`Supplier Delivery Net Amount`) AS items_amount,
		sum(`Supplier Delivery Extra Cost Amount`) AS extra_cost_amount,
		sum(`Supplier Delivery Extra Cost Account Currency Amount`) AS ac_extra_cost_amount
		 
		 
		 FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d", $this->id
        );





        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                if($account->get('Account Currency')==$this->get('Supplier Delivery Currency Code')){
                    $row['extra_cost_amount']=$row['extra_cost_amount']+$row['ac_extra_cost_amount'];
                    $row['ac_extra_cost_amount']=0;
                }


                if ($this->get('State Index') >= 30) {
                    $dispatched_items = $row['num_items'];
                } else {
                    $dispatched_items = 0;
                }

                $items_amount         = ($row['items_amount'] == '' ? 0 : $row['items_amount']);
                $extra_amount         = ($row['extra_cost_amount'] == '' ? 0 : $row['extra_cost_amount']);
                $ac_extra_cost_amount = ($row['ac_extra_cost_amount'] == '' ? 0 : $row['ac_extra_cost_amount']);



                $this->fast_update(
                    array(
                        'Supplier Delivery Purchase Order Amount' => ($row['po_items_amount'] == '' ? 0 : $row['po_items_amount']),

                        'Supplier Delivery Items Amount'          => $items_amount,
                        'Supplier Delivery Extra Costs Amount'    => $extra_amount,
                        'Supplier Delivery Total Amount'          => $items_amount + $extra_amount,
                        'Supplier Delivery AC Subtotal Amount'    => ($items_amount + $extra_amount) * $this->get('Supplier Delivery Currency Exchange'),
                        'Supplier Delivery AC Extra Costs Amount' => $ac_extra_cost_amount,
                        'Supplier Delivery AC Total Amount'       => $ac_extra_cost_amount + ($items_amount + $extra_amount) * $this->get('Supplier Delivery Currency Exchange'),

                        'Supplier Delivery Number Items'                      => $row['num_items'],
                        'Supplier Delivery Number Dispatched Items'           => $dispatched_items,
                        'Supplier Delivery Number Checked Items'              => $row['checked_items'],
                        'Supplier Delivery Number Received and Checked Items' => $row['received_checked_items'],
                        'Supplier Delivery Number Placed Items'               => $row['placed_items'],
                        'Supplier Delivery Number Ordered Items'              => $row['ordered_items'],
                        'Supplier Delivery Number Items Without PO'           => $row['num_items'] - $row['ordered_items'],
                        'Supplier Delivery Weight'                            => $row['weight'],
                        'Supplier Delivery CBM'                               => $row['cbm'],

                        'Supplier Delivery Placed Items' => ($row['placed_qty'] > 0 ? 'Yes' : 'No')
                    )
                );


            }
        }


        $over  = 0;
        $under = 0;

        $sql =
            "SELECT  sum( if(`Supplier Delivery Checked Units` > `Supplier Delivery Units` ,1,0))  over_qty, sum( if( `Supplier Delivery Checked Units` < `Supplier Delivery Units` ,1,0))  under_qty FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=?   and `Supplier Delivery Checked Units` is not null  ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        while ($row = $stmt->fetch()) {
            $over  = $row['over_qty'];
            $under = $row['under_qty'];
        }

        $this->fast_update(
            array(
                'Supplier Delivery Number Over Delivered Items'  => $over,
                'Supplier Delivery Number Under Delivered Items' => $under

            )
        );


    }

    function get_state() {

        //'InProcess','Dispatched','Received','Checked','Placed','Cancelled'

        if (in_array(
            $this->get('Supplier Delivery State'), array(
                                                     'InProcess',
                                                     'Dispatched',
                                                     'Costing',
                                                     'InvoiceChecked',
                                                     'Cancelled'
                                                 )
        )) {

            $state = $this->get('Supplier Delivery State');
        } else {


            $items = 0;

            $state = 'Placed';
            $sql   = sprintf(
                'SELECT `Purchase Order Transaction Fact Key`,`Supplier Part Key`,`Purchase Order Transaction State`,`Supplier Delivery Units`,`Supplier Delivery Checked Units`,`Supplier Delivery Placed Units`  FROM  `Purchase Order Transaction Fact`  WHERE `Supplier Delivery Key` =%d',
                $this->id
            );

            if ($result = $this->db->query($sql)) {

                $items_no_received = 0;

                foreach ($result as $row) {

                    $items++;
                    // print_r($row);

                    if ($row['Supplier Delivery Checked Units'] == '') {
                        $state = 'Received';
                        // print_r($row);
                        break;
                    } else {
                        if ($row['Supplier Delivery Checked Units'] == 0) {
                            $items_no_received++;
                            if ($items_no_received == $items) {

                                $state = 'Cancelled';
                            }
                        } else {


                            if (round($row['Supplier Delivery Checked Units'], 2) > round($row['Supplier Delivery Placed Units'], 2)) {
                                //exit;
                                $state = 'Checked';


                                break;
                            } else {

                                $state = 'Placed';

                                //  print "Pla \n";
                            }


                        }

                    }
                }

                // print $state."\n";

            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            //  print $state;exit;


        }
        //  print $state."\n";
        // exit;
        return $state;

    }

    function update_item_delivery_placed_skos($data) {


        //   print_r($data);

        $date            = gmdate('Y-m-d H:i:s');
        $transaction_key = $data['transaction_key'];


        $sql = sprintf(
            'SELECT `Purchase Order Transaction Part SKU`,`Purchase Order Key`,POTF.`Purchase Order Transaction Fact Key`,`Part SKU`,`Supplier Delivery Placed Units`,POTF.`Supplier Part Key`,`Supplier Delivery Checked Units`,`Metadata`

		 FROM `Purchase Order Transaction Fact`  POTF LEFT JOIN  `Part Dimension` P ON (P.`Part SKU`=POTF.`Purchase Order Transaction Part SKU`)

		  WHERE  `Purchase Order Transaction Fact Key`=%d ', $transaction_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $placement_sko_qty = $this->get_placement_quantity($transaction_key);

                $part      = get_object('Part', $row['Purchase Order Transaction Part SKU']);
                $sko_qty   = $data['qty'] + $placement_sko_qty;
                $units_qty = $sko_qty * $part->get('Part Units Per Package');
                if ($units_qty < 0) {
                    $units_qty = 0;
                }


                if (round($row['Supplier Delivery Checked Units'], 2) < round($units_qty, 2)) {
                    $this->error = true;
                    $this->msg   = 'Placed qty > than delivery qty';

                    return false;
                } elseif ($row['Supplier Delivery Checked Units'] == $units_qty) {
                    $placed = 'Yes';
                } else {
                    $placed = 'No';
                }

                if ($row['Metadata'] == '') {
                    $metadata = array('placement_data' => array($data['placement_data']));
                } else {
                    $metadata = json_decode($row['Metadata'], true);
                    if (isset($metadata['placement_data'])) {
                        $metadata['placement_data'][] = $data['placement_data'];
                    } else {
                        $metadata['placement_data'] = array($data['placement_data']);
                    }
                }

                $placement_data = $metadata['placement_data'];

                $encoded_metadata = json_encode($metadata);


                $sql = sprintf(
                    "update`Purchase Order Transaction Fact` set  `Supplier Delivery Placed Units`=%f,`Supplier Delivery Last Updated Date`=%s ,`Supplier Delivery Transaction Placed`=%s ,
                  
                    `Metadata`=%s 
                    where  `Purchase Order Transaction Fact Key`=%d ", $units_qty, prepare_mysql($date), prepare_mysql($placed), prepare_mysql($encoded_metadata), $transaction_key
                );


                $this->db->exec($sql);

                $place_qty = ($row['Supplier Delivery Checked Units'] - $units_qty) / $part->get('Part Units Per Package');

                if ($placed == 'Yes') {
                    $sql = sprintf(
                        'UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Placed SKOs`=%f  WHERE `Purchase Order Transaction Fact Key`=%d ', $sko_qty, $row['Purchase Order Transaction Fact Key']
                    );

                } else {
                    $sql = sprintf(
                        'UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Placed SKOs`=null   WHERE `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Transaction Fact Key']
                    );
                }
                $this->db->exec($sql);


                $this->update_supplier_delivery_item_state($row['Purchase Order Transaction Fact Key'], false);


                $placement = '<div  class="placement_data mini_table right no_padding" style="padding-right:2px">';
                if (isset($metadata['placement_data'])) {

                    foreach ($metadata['placement_data'] as $placement_data) {
                        $placement .= '<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._(
                                'SKO'
                            ).' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


                    }
                }
                $placement .= '<div style="clear:both"></div>';


                $placement .= '
			    <div style="clear:both"  id="place_item_'.$row['Purchase Order Transaction Fact Key'].'" class="place_item '.($placed == 'No' ? '' : 'hide').' " part_sku="'.$row['Part SKU'].'" transaction_key="'.$row['Purchase Order Transaction Fact Key'].'"  >
			    <input class="place_qty width_50 changed" value="'.($place_qty + 0).'" ovalue="'.($place_qty + 0).'"  min="1" max="'.$place_qty.'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                <div>';


                $part->update_next_deliveries_data();


                if ($row['Purchase Order Key']) {
                    $purchase_order = get_object('Purchase Order', $row['Purchase Order Key']);
                    $purchase_order->update_totals();
                }

            } else {
                $this->error = true;
                $this->msg   = 'po transaction not found';

                return;
            }


        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update_totals();

        /*
                if ($this->get('Supplier Delivery State') == 'Checked') {
                    if ($this->get('Supplier Delivery Number Placed Items') == $this->get('Supplier Delivery Number Received and Checked Items')) {
                        $this->update_state('Placed');
                    }

                }

        */
        $this->update_state($this->get_state());


        $operations = array();


        if ($this->get('State Index') == 100) {


            if ($this->data['Supplier Delivery Parent'] == 'Order') {

                $this->update_state('Costing');

                $sql = sprintf(
                    'select `Purchase Order Transaction Part SKU`,`Purchase Order Transaction Fact Key`,`Metadata` ,`Supplier Delivery Net Amount` from `Purchase Order Transaction Fact`  POTF  where `Supplier Delivery Key`=%d group by `Purchase Order Transaction Part SKU` ',
                    $this->id
                );
                if ($result = $this->db->query($sql)) {
                    $all_parts_min_date = '';
                    foreach ($result as $row) {


                        if ($row['Metadata'] != '') {
                            $metadata = json_decode($row['Metadata'], true);
                            //  print_r($metadata);

                            if (isset($metadata['placement_data'])) {


                                $min_date     = '';
                                $total_placed = 0;
                                foreach ($metadata['placement_data'] as $placement_data) {


                                    $sql = sprintf('select `Date` from `Inventory Transaction Fact`    where `Inventory Transaction Key`=%d', $placement_data['oif_key']);

                                    if ($result2 = $this->db->query($sql)) {
                                        foreach ($result2 as $row2) {
                                            $date = gmdate('U', strtotime($row2['Date']));
                                            if ($min_date == '') {
                                                $min_date = $date;

                                            } elseif ($date < $min_date) {
                                                $min_date = $date;
                                            }
                                            if ($all_parts_min_date == '') {
                                                $all_parts_min_date = $date;

                                            } elseif ($date < $all_parts_min_date) {
                                                $all_parts_min_date = $date;
                                            }


                                        }
                                    } else {
                                        print_r($error_info = $this->db->errorInfo());
                                        print "$sql\n";
                                        exit;
                                    }


                                    $total_placed += $placement_data['qty'];


                                }

                                $parts_data[$row['Purchase Order Transaction Part SKU']] = ($min_date != '' ? gmdate('Y-m-d', $min_date) : '');


                                if ($total_placed > 0) {
                                    foreach ($metadata['placement_data'] as $placement_data) {
                                        $sql = sprintf(
                                            'update `Inventory Transaction Fact`  set `Inventory Transaction Amount`=%f   where `Inventory Transaction Key`=%d', $row['Supplier Delivery Net Amount'] * $placement_data['qty'] / $total_placed, $placement_data['oif_key']
                                        );
                                        $this->db->exec($sql);

                                        $this->process_aiku_fetch('OrgStockMovement',$placement_data['oif_key']);

                                    }


                                }

                            }

                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $this->update_state('InvoiceChecked');

                $this->update_totals();

                global $account;
                include_once 'utils/new_fork.php';


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'               => 'update_parts_stock_run',
                    'parts_data'         => $parts_data,
                    'editor'             => $this->editor,
                    'all_parts_min_date' => ($all_parts_min_date != '' ? gmdate('Y-m-d', $all_parts_min_date) : ''),
                ), $account->get('Account Code')
                );


            } else {
                $operations = array('costing_operations');
            }


        }


        $this->update_metadata = array(
            'class_html'  => array(
                'Supplier_Delivery_State'                      => $this->get('State'),
                'Supplier_Delivery_Number_Placed_Items'        => $this->get('Number Placed Items'),
                'Supplier_Delivery_Checked_Percentage_or_Date' => '&nbsp;'.$this->get('Checked Percentage or Date'),
                'Supplier_Delivery_Placed_Percentage_or_Date'  => '&nbsp;'.$this->get('Placed Percentage or Date')

            ),
            'placement'   => $placement,
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );


        return array(
            'transaction_key' => $transaction_key,
            'qty'             => $sko_qty + 0,
            'placement_data'  => $placement_data,
            'placed'          => $placed,
            'place_qty'       => $place_qty
        );


    }

    function delete() {

        if ($this->data['Supplier Delivery State'] == 'InProcess') {


            $items = array();
            $sql   = sprintf(
                "SELECT POTF.`Supplier Part Historic Key`,`Purchase Order Ordering Units`,`Supplier Part Reference`,POTF.`Supplier Part Key`,`Supplier Part Part SKU` FROM `Purchase Order Transaction Fact` POTF
			LEFT JOIN `Supplier Part Historic Dimension` SPH ON (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
            LEFT JOIN  `Supplier Part Dimension` SP ON (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

			WHERE `Supplier Delivery Key`=%d", $this->id
            );


            if ($result = $this->db->query($sql)) {

                foreach ($result as $row) {
                    $items[] = array(
                        $row['Supplier Part Historic Key'],
                        $row['Supplier Part Reference'],
                        $row['Purchase Order Ordering Units'],
                        $row['Supplier Part Key'],
                        $row['Supplier Part Part SKU'],
                    );
                }
            }


            $sql = sprintf(
                "DELETE FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Key`=%d", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`=%d AND `Purchase Order Key` IS NULL  ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "UPDATE `Purchase Order Transaction Fact` SET `Supplier Delivery Key`=NULL ,`Supplier Delivery Units`=0 ,`Supplier Delivery Checked Units`=NULL,`Supplier Delivery Placed Units`=NULL,`Supplier Delivery Net Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Transaction Placed`=NULL,`Supplier Delivery CBM`=NULL,`Supplier Delivery CBM`=NULL  ,`Supplier Delivery Last Updated Date`=NULL  WHERE `Supplier Delivery Key`=%d  ",
                $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "SELECT `Attachment Bridge Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Subject`='Supplier Delivery' AND `Subject Key`=%d", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    include_once 'class.Attachment.php';
                    $sql = sprintf(
                        "DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d", $row['Attachment Bridge Key']
                    );
                    $this->db->exec($sql);
                    $attachment = new Attachment($row['Attachment Key']);
                    $attachment->delete();

                }
            }


            $purchase_order = get_object('PurchaseOrder', $this->get('Supplier Delivery Purchase Order Key'));
            $purchase_order->update_purchase_order_items_state();


            $this->deleted = true;

            foreach ($items as $item) {
                $part = get_object('Part', $item[4]);
                $part->update_next_deliveries_data();

            }


            return sprintf(
                '%s/%d/order/%s', strtolower($purchase_order->get('Purchase Order Parent')), $purchase_order->get('Purchase Order Parent Key'), $purchase_order->id
            );


        } else {
            $this->error = true;
            $this->msg   = 'Can not deleted submitted Supplier Delivery';
        }
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Supplier Delivery Public ID':
                $label = _('public Id');
                break;
            case 'Supplier Delivery Incoterm':
                $label = _('Incoterm');
                break;
            case 'Supplier Delivery Port of Export':
                $label = _('port of export');
                break;
            case 'Supplier Delivery Port of Import':
                $label = _('port of import');
                break;
            case 'Supplier Delivery Estimated Receiving Date':
                $label = _('estimated receiving date');
                break;
            case 'Supplier Delivery Dispatched Date':
                $label = _('dispatched date');
                break;
            case 'Supplier Delivery Invoice Date':
                $label = _('invoice date');
                break;
            case 'Supplier Delivery Invoice Public ID':
                $label = _('Invoice number');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


}


?>
