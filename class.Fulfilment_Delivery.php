<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 July 2021 at 22:08:45 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';

class Fulfilment_Delivery extends DB_Table
{
    use  NotesSubject, AttachmentSubject;

    function __construct($arg1 = false, $arg2 = false)
    {
        global $db;
        $this->db = $db;

        $this->table_name    = 'Fulfilment Delivery';
        $this->ignore_fields = array('Fulfilment Delivery Key');

        $this->calculate_totals = true;

        if (is_string($arg1)) {
            if (preg_match('/new|create/i', $arg1)) {
                $this->create($arg2);

                return;
            }
        }

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);
    }


    function get_data($key, $id)
    {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Fulfilment Delivery Dimension` WHERE `Fulfilment Delivery Key`=%d",
                $id
            );
        } elseif ($key == 'public id' or $key == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Fulfilment Delivery Dimension` WHERE `Fulfilment Delivery Public ID`=%s",
                prepare_mysql($id)
            );
        } else {
            exit('Fulfilment Delivery get_data unknown key:'.$key);
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Fulfilment Delivery Key'];
        }
    }

    function create($data)
    {
        exit();
        $this->editor = $data['editor'];


        $data['Fulfilment Delivery Date']              = gmdate('Y-m-d H:i:s');
        $data['Fulfilment Delivery Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Fulfilment Delivery Last Updated Date'] = gmdate('Y-m-d H:i:s');
        $data['Fulfilment Delivery Date Type']         = 'Creation';


        $data['Fulfilment Delivery Metadata'] = '{}';

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
            "INTO `Fulfilment Delivery Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($base_data)).'`',
            join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare("INSERT ".$sql);


        $i = 1;
        foreach ($base_data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }

        if ($stmt->execute()) {
            $this->new = 1;

            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);


            $history_data = array(
                'History Abstract' => _('Fulfilment delivery created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );
        } else {
            print_r($stmt->errorInfo());
            print "Error can not create Fulfilment Delivery\n $sql\n";
        }
    }


    function get($key = '')
    {
        if (!$this->id) {
            return '';
        }

        switch ($key) {
            case 'Formatted ID':

                $formatted_id = sprintf('%06d', $this->id);
                if ($this->data['Fulfilment Delivery Public ID']) {
                    $formatted_id .= ' ('.$this->data['Fulfilment Delivery Public ID'].')';
                }

                return $formatted_id;
            case ('State'):
                switch ($this->data['Fulfilment Delivery State']) {
                    case 'InProcess':
                        return _('In process');
                    case 'Received':
                        return _('Received');
                    case 'BookedIn':
                        return _('Booked in');
                    case 'Invoicing':
                        return _('Invoicing');
                    case 'Invoiced':
                        return _('Invoiced');
                    case 'Cancelled':
                        return _('Cancelled');
                    default:
                        break;
                }

                break;

            case 'State Index':


                switch ($this->data['Fulfilment Delivery State']) {
                    case 'InProcess':
                        return 10;

                    case 'Received':
                        return 40;
                    case 'BookedIn':
                        return 60;
                    case 'Invoicing':
                        return 80;
                    case 'Invoiced':
                        return 100;
                    case 'Cancelled':
                        return -10;
                    default:
                        return 0;
                }


            case 'Progress Date':

                switch ($this->data['Fulfilment Delivery State']) {
                    case 'InProcess':
                        return $this->get('Creation Date');

                    case 'Received':

                        return $this->get('Received Date');
                    case 'BookedIn':
                        return $this->get('Booked In Date');
                    default:
                        return '';
                }


            case 'Progress':

                switch ($this->data['Fulfilment Delivery State']) {
                    case 'Dispatched':
                        return '('._('Dispatched').')';
                    case 'Received':
                        $progress = '('._('Received').')';

                        if ($this->get('Fulfilment Delivery Number Checked Items') > 0) {
                            $progress = '('._('Checking').' '.percentage($this->get('Fulfilment Delivery Number Checked Items'), $this->get('Fulfilment Delivery Number Dispatched Items'), 0).')';
                        }
                        if ($this->get('Fulfilment Delivery Number Placed Items') > 0) {
                            $progress = '('._('Placing').' '.percentage($this->get('Fulfilment Delivery Number Placed Items'), $this->get('Fulfilment Delivery Number Dispatched Items'), 0).')';
                        }

                        return $progress;
                }

                break;


            case 'Estimated or Received Date':

                if ($this->data['Fulfilment Delivery State'] == 'InProcess') {
                    return '<span class="italic very_discreet">℮ '.$this->get('Estimated Receiving Date').'</span>';
                } else {
                    return $this->get('Received Date');
                }


            case 'Creation Date':

            case 'Received Date':
            case 'Booked In Date':
            case 'Cancelled Date':
            case 'Estimated Receiving Date':
                if ($this->data['Fulfilment Delivery '.$key] == '') {
                    return '';
                }

                return strftime("%e %b %Y", strtotime($this->data['Fulfilment Delivery '.$key].' +0:00'));

            case 'Estimated Pallets':
            case 'Estimated Boxes':
                return ($this->data ['Fulfilment Delivery '.$key] == '' ? '<span class="super_discreet">?</span>' : number($this->data ['Fulfilment Delivery '.$key]));
            case 'Number Items':
            case 'Number Ordered Items':
            case 'Number Items Without PO':
                return number($this->data ['Fulfilment Delivery '.$key]);
            case 'Next Invoice Date':
                return strftime("%a %e %b %Y", strtotime('Next monday'));
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists('Fulfilment Delivery '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return '';
    }


    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        exit();
        switch ($field) {
            case 'Fulfilment Delivery Public ID':
                $this->update_field($field, $value, $options);

                $this->fast_update(
                    [
                        'Fulfilment Delivery File As' => $value
                    ]
                );


                $this->update_metadata = array(
                    'class_html' => array(
                        'Fulfilment_Delivery_Formatted_ID' => $this->get('Formatted ID'),

                    ),

                );
                break;
            case 'Fulfilment Delivery State':
                $this->update_state($value);
                break;
            case 'Fulfilment Delivery Received Date':

                $this->update_field($field, $value, $options);
                $this->update_metadata = [
                    'class_html' => [
                        'Fulfilment_Delivery_Estimated_or_Received_Date' => $this->get('Estimated or Received Date')
                    ]
                ];
                break;


            case 'Fulfilment Delivery Received Date':

                $this->update_field($field, $value, $options);

                $sql = "UPDATE `Fulfilment Asset Dimension`  SET `Fulfilment Asset From`=?  WHERE `Fulfilment Asset Fulfilment Delivery Key`=?  and `Fulfilment Asset State`='Received' ";
                $this->db->prepare($sql)->execute(
                    array(
                        $value,
                        $this->id
                    )
                );


                $this->update_metadata = [
                    'class_html' => [
                        'Fulfilment_Delivery_Estimated_or_Received_Date' => $this->get('Estimated or Received Date')
                    ]
                ];
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


    function update_state($value)
    {

        exit();
        $date = gmdate('Y-m-d H:i:s');

        $hide=[];
        $show=[];
        switch ($value) {
            case 'InProcess':

                $this->fast_update(
                    array(
                        'Fulfilment Delivery Dispatched Date' => '',
                        'Fulfilment Delivery State'           => $value,
                    )
                );


                $sql = "UPDATE `Fulfilment Asset Dimension` SET  `Fulfilment Asset State`='InProcess' ,`Fulfilment Asset From`=NULL  WHERE `Fulfilment Asset Fulfilment Delivery Key`=? ";
                $this->db->prepare($sql)->execute(
                    array(
                        $this->id
                    )
                );

                //$this->update_Fulfilment_Delivery_items_state();

                $operations = array(
                    'delete_operations',
                    'received_operations',
                    'dispatched_operations'
                );

                $history_data = array(
                    'History Abstract' => _('Fulfilment Delivery set as in process'),
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


                break;
            case 'Received':

                $old_value=$this->get('Fulfilment Delivery State');


                if (!in_array($this->get('Fulfilment Delivery State'), ['InProcess', 'BookedIn'])) {
                    $this->error = true;
                    $this->msg   = 'Invalid operation';
                    return;
                }


                $this->fast_update(
                    array(
                        'Fulfilment Delivery Received Date'     => $date,
                        'Fulfilment Delivery Last Updated Date' => $date,
                        'Fulfilment Delivery Date'              => gmdate('Y-m-d'),
                        'Fulfilment Delivery Date Type'         => 'Received',
                        'Fulfilment Delivery State'             => 'Received',
                        'Fulfilment Delivery Booked In Date'    => '',

                    )
                );


                $operations = array(
                    'cancel_operations',
                    'undo_received_operations',
                    'booked_in_operations',
                );

                $sql = "UPDATE `Fulfilment Asset Dimension` SET  `Fulfilment Asset State`='Received'  WHERE `Fulfilment Asset Fulfilment Delivery Key`=? ";
                $this->db->prepare($sql)->execute(
                    array(
                        $this->id
                    )
                );

                $sql = "UPDATE `Fulfilment Asset Dimension` SET `Fulfilment Asset From`=?  WHERE `Fulfilment Asset Fulfilment Delivery Key`=?  and `Fulfilment Asset From`=NULL ";
                $this->db->prepare($sql)->execute(
                    array(
                        $date,
                        $this->id
                    )
                );

                if($old_value!='InProcess') {
                    require_once 'utils/new_fork.php';

                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'         => 'update_rent_order',
                            'customer_key' => $this->get('Fulfilment Delivery Customer Key'),
                            'editor'       => $this->editor
                        ),
                        DNS_ACCOUNT_CODE,
                        $this->db
                    );
                }


                $history_data = array(
                    'History Abstract' => _('Fulfilment delivery set as received'),
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

                $hide[]='next_invoice';
                break;

            case 'BookedIn':


                $this->fast_update(
                    array(
                        'Fulfilment Delivery Booked In Date'    => $date,
                        'Fulfilment Delivery Last Updated Date' => $date,
                        'Fulfilment Delivery Date'              => gmdate('Y-m-d'),
                        'Fulfilment Delivery Date Type'         => 'BookedIn',
                        'Fulfilment Delivery State'             => 'BookedIn',
                    )
                );


                $operations = array(
                    'undo_booked_in_operations',
                );

                $sql = "UPDATE `Fulfilment Asset Dimension` SET  `Fulfilment Asset State`='BookedIn'   WHERE `Fulfilment Asset Fulfilment Delivery Key`=? ";
                $this->db->prepare($sql)->execute(
                    array(
                        $this->id
                    )
                );




                require_once 'utils/new_fork.php';

                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'update_rent_order',
                        'customer_key'=>$this->get('Fulfilment Delivery Customer Key'),
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE,
                    $this->db
                );


                $history_data = array(
                    'History Abstract' => _('Fulfilment delivery booked in'),
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
                $show[]='next_invoice';
                break;


            default:
                exit('unknown FD state '.$value);
        }

        $this->update_totals();


        $this->update_metadata = array(
            'class_html'  => array(
                'Fulfilment_Delivery_State'           => $this->get('State'),
                'Fulfilment_Delivery_Dispatched_Date' => '&nbsp;'.$this->get('Dispatched Date'),
                'Fulfilment_Delivery_Received_Date'   => '&nbsp;'.$this->get('Received Date'),
                'Fulfilment_Delivery_Booked_In_Date'  => '&nbsp;'.$this->get('Booked In Date'),
                'Fulfilment_Delivery_Invoicing_Date'  => '&nbsp;'.$this->get('Invoicing Date'),
                'Fulfilment_Delivery_Invoiced_Date'   => '&nbsp;'.$this->get('Invoiced Date'),

                'Fulfilment_Delivery_Number_Dispatched_Items'    => $this->get('Number Dispatched Items'),
                'Fulfilment_Delivery_Number_Received_Items'      => $this->get('Number Received Items'),
                'Fulfilment_Delivery_Estimated_or_Received_Date' => $this->get('Estimated or Received Date')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),
            'hide'=>$hide,
            'show'=>$show

        );
    }

    function update_totals()
    {
        $number_items = 0;
        if ($this->get('Fulfilment Delivery Type') == 'Asset') {
            $sql  = "select count(*) as num from `Fulfilment Asset Dimension` where `Fulfilment Asset Fulfilment Delivery Key`=? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );
            while ($row = $stmt->fetch()) {
                $number_items = $row['num'];
            }
        }

        $this->fast_update(
            [
                'Fulfilment Delivery Number Items' => $number_items
            ]
        );
    }

    /**
     * @throws \Exception
     */
    function delete(): string
    {
        if ($this->data['Fulfilment Delivery State'] == 'InProcess') {
            $sql = "DELETE FROM `Fulfilment Delivery Dimension` WHERE `Fulfilment Delivery Key`=?";
            $this->db->prepare($sql)->execute(
                array(
                    $this->id
                )
            );
            $sql = "DELETE FROM `Fulfilment Asset Dimension` WHERE `Fulfilment Asset Fulfilment Delivery Key`=?";
            $this->db->prepare($sql)->execute(
                array(
                    $this->id
                )
            );


            $sql  = "SELECT `Attachment Bridge Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Subject`='Fulfilment Delivery' AND `Subject Key`=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id

                )
            );
            while ($row = $stmt->fetch()) {
                $sql = "DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=?";
                $this->db->prepare($sql)->execute(
                    array(
                        $row['Attachment Bridge Key']
                    )
                );

                $this->db->exec($sql);
                $attachment = get_object('Attachment', $row['Attachment Key']);
                $attachment->delete();
            }


            $this->deleted = true;

            /*
            foreach ($items as $item) {
                $part = get_object('Part', $item[4]);
                $part->update_next_deliveries_data();
            }
            */


            $customer         = get_object('Customer', $this->data['Fulfilment Delivery Customer Key']);
            $customer->editor = $this->editor;

            $history_data = array(
                'History Abstract' => sprintf(_('Fulfilment delivery %s deleted'), $this->get('Formatted ID')),
                'History Details'  => '',
                'Action'           => 'deleted'
            );

            $customer->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $customer->get_object_name(),
                $customer->id
            );


            return sprintf(
                'fulfilment/%d/customers/%s/%d',
                $this->data['Fulfilment Delivery Warehouse Key'],
                ($this->data['Fulfilment Delivery Type'] == 'Part' ? 'dropshipping' : 'asset_keeping'),
                $this->data['Fulfilment Delivery Customer Key']
            );
        } else {
            $this->error = true;
            $this->msg   = 'Can not deleted submitted Fulfilment Delivery';

            return false;
        }
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Fulfilment Delivery Public ID':
                $label = _('customer delivery reference');
                break;
            case 'Fulfilment Delivery Estimated Receiving Date':
                $label = _('estimated delivery date');
                break;
            case 'Fulfilment Delivery Estimated Pallets':
                $label = _('estimated pallets');
                break;
            case 'Fulfilment Delivery Estimated Boxes':
                $label = _('estimated boxes');
                break;
            default:
                $label = $field;
        }

        return $label;
    }

    function create_multiple_fulfilment_asset($number, $data)
    {
        exit();
        $this->calculate_totals = false;

        if (!is_numeric($number) or $number < 1) {
            $this->msg = _('Invalid number of assets')." $number";

            return [
                'assets_added' => 0
            ];
        }

        if ($number > 500) {
            $this->msg = _('Max number (500) of new bulk assets exceeded');

            return [
                'assets_added' => 0
            ];
        }

        for ($i = 0; $i < $number; $i++) {
            $this->create_fulfilment_asset(
                [
                    'Fulfilment Asset Type' => $data['Fulfilment Asset Type'],
                    'editor'                => $this->editor
                ]
            );
        }

        return [
            'assets_added' => $number
        ];
    }

    function create_fulfilment_asset($data)
    {
        exit();
        $data['Fulfilment Asset Fulfilment Delivery Key'] = $this->id;
        $data['Fulfilment Asset Warehouse Key']           = $this->get('Fulfilment Delivery Warehouse Key');
        $data['Fulfilment Asset Customer Key']            = $this->get('Fulfilment Delivery Customer Key');
        $data['Fulfilment Asset Metadata']                = '{}';
        $data['editor']                                   = $this->editor;


        if($this->get('State Index')>=40){
            $data['Fulfilment Asset From']                = $this->get('Fulfilment Delivery Received Date');

        }


        $location_code = '';
        if (isset($data['Fulfilment Asset Location Code'])) {
            $location_code = $data['Fulfilment Asset Location Code'];
            unset($data['Fulfilment Asset Location Code']);
        }


        if (!empty($data['Fulfilment Asset Reference'])) {
            $sql  = 'SELECT count(*) AS num FROM `Fulfilment Asset Dimension` WHERE `Fulfilment Asset Reference`=? AND `Fulfilment Asset Customer Key`=?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $data['Fulfilment Asset Reference'],
                    $this->get('Fulfilment Delivery Customer Key')
                )
            );
            if ($row = $stmt->fetch() and $row['num'] > 0) {
                $this->error          = true;
                $this->msg            = sprintf(_('Duplicated reference (%s)'), $data['Fulfilment Asset Reference']);
                $this->error_code     = 'duplicate_fulfilment_asset_reference';
                $this->error_metadata = $data['Fulfilment Asset Reference'];

                return false;
            }
        }

        include_once 'class.Fulfilment_Asset.php';
        $fulfilment_asset = new Fulfilment_Asset('create', $data);

        if ($fulfilment_asset->id) {
            $this->new_object_msg = $fulfilment_asset->msg;
            $this->new_object     = true;





            if ($location_code != '') {
                $location = get_object('Location-code', $location_code);
                if ($location->id) {
                    $fulfilment_asset->update(
                        [
                            'Fulfilment Asset Location Key' => $location->id
                        ]
                    );
                } else {
                    $this->warning          = true;
                    $this->msg              = sprintf(_('Location not found (%s)'), $location_code);
                    $this->warning_code     = 'warning_asset_location_not_found';
                    $this->warning_metadata = $location_code;
                }
            }


            if ($this->calculate_totals = true) {
                $this->update_totals();
            }

            return $fulfilment_asset;
        } else {
            $this->error          = true;
            $this->msg            = $fulfilment_asset->msg;
            $this->error_code     = $fulfilment_asset->error_code;
            $this->error_metadata = $fulfilment_asset->error_metadata;

            return false;
        }
    }


}

