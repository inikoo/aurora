<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 19 August 2018 at 10:08:34 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/
include_once 'class.DB_Table.php';


class AgentSupplierPurchaseOrder extends DB_Table {

    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Agent Supplier Purchase Order';
        $this->ignore_fields = array('Agent Supplier Purchase Order Key');


        if (is_string($arg1)) {
            if (preg_match(
                '/new|create/i', $arg1
            )) {
                $this->create_agent_supplier_purchase_order($arg2);

                return;
            }
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data(
            $arg1, $arg2
        );

    }


    function create_agent_supplier_purchase_order($data) {


        $this->editor = $data['editor'];


        unset($data['editor']);

        $data['Agent Supplier Purchase Order Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Agent Supplier Purchase Order Last Updated Date'] = gmdate('Y-m-d H:i:s');


        $data['Agent Supplier Purchase Order File As'] = $this->get_file_as($data['Agent Supplier Purchase Order Public ID']);


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {

            $value = trim($value);

            $keys .= "`$key`,";

            $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        $sql = sprintf(
            "INSERT INTO `Agent Supplier Purchase Order Dimension` %s %s", $keys, $values
        );
        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Agent Supplier Purchase Order Key`=%d  where `Purchase Order Key`=%d and `Supplier Key`=%d ', $this->id, $this->data['Agent Supplier Purchase Order Purchase Order Key'],
                $this->data['Agent Supplier Purchase Order Supplier Key']

            );

            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => _('Agent supplier purchase order created'),
                'History Details'  => '',
                'Action'           => 'created'
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


            $supplier = get_object('Supplier', $this->data['Agent Supplier Purchase Order Supplier Key']);
            $supplier->update_purchase_orders();

            $this->update_totals();


        } else {
            // print "Error can not create supplier $sql\n";
        }


    }


    function get_file_as($name) {

        return $name;
    }

    function get_data($key, $id) {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Agent Supplier Purchase Order Dimension` WHERE `Agent Supplier Purchase Order Key`=%d", $id
            );

        } elseif ($key == 'public id') {
            $sql = sprintf(
                "SELECT * FROM `Agent Supplier Purchase Order Dimension` WHERE `Agent Supplier Purchase Order Public ID`=%s", prepare_mysql($id)
            );

        } else {
            return;

        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Agent Supplier Purchase Order Key'];

            if ($this->data['Agent Supplier Purchase Order Metadata'] == '') {
                $this->metadata = array();
            } else {
                $this->metadata = json_decode(
                    $this->data['Agent Supplier Purchase Order Metadata'], true
                );
            }


        }

    }

    function update_totals() {

        $sql = sprintf(
            "SELECT sum(`Purchase Order Quantity`) as cartons, sum(POTF.`Purchase Order Weight`) AS  weight,sum(if(isNULL(`Purchase Order Weight`),1,0) )AS missing_weights ,sum(if(isNULL(`Purchase Order CBM`),1,0) )AS missing_cbms , sum(`Purchase Order CBM` )AS cbm ,count( distinct POTF.`Supplier Part Key` ) AS num_items  ,
sum(`Supplier Part Unit Cost`*`Purchase Order Quantity`*`Part Units Per Package`*`Supplier Part Packages Per Carton`) AS items_net from
             `Purchase Order Transaction Fact` POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join  `Part Data` PD on (PD.`Part SKU`=SP.`Supplier Part Part SKU`)
              
              WHERE `Agent Supplier Purchase Order Key`=%d", $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $this->fast_update(
                    array(
                        'Agent Supplier Purchase Order Amount'          => $row['items_net'],
                        'Agent Supplier Purchase Order Products'        => $row['num_items'],
                        'Agent Supplier Purchase Order Cartons'         => $row['cartons'],
                        'Agent Supplier Purchase Order Weight'          => $row['weight'],
                        'Agent Supplier Purchase Order CBM'             => $row['cbm'],
                        'Agent Supplier Purchase Order Missing Weights' => $row['missing_weights'],
                        'Agent Supplier Purchase Order Missing CBMs'    => $row['missing_cbms'],
                    )
                );
            }
        } else {
            print "$sql\n";
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $in_process    = 0;
        $with_problems = 0;
        $confirmed     = 0;
        $in_warehouse  = 0;
        $in_delivery   = 0;
        $cancelled     = 0;


        $sql = sprintf("select count(*) as num ,`Purchase Order Transaction State` from `Purchase Order Transaction Fact` POTF WHERE `Agent Supplier Purchase Order Key`=%d  group by `Purchase Order Transaction State`  ", $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($row['Purchase Order Transaction State']) {
                    case 'Submitted':
                        $in_process = $row['num'];
                        break;
                    case 'Confirmed':
                        $confirmed = $row['num'];
                        break;
                    case 'ProblemSupplier':
                        $with_problems = $row['num'];
                        break;
                    default:
                        break;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array(
                'Agent Supplier Purchase Order Products In Process'   => $in_process,
                'Agent Supplier Purchase Order Products With Problem' => $with_problems,
                'Agent Supplier Purchase Order Products Confirmed'    => $confirmed,
                'Agent Supplier Purchase Order Products Received'     => $in_warehouse,
                'Agent Supplier Purchase Order Products In Delivery'  => $in_delivery,
                'Agent Supplier Purchase Order Products Cancelled'    => $cancelled,
            )
        );


        $state = $this->get_state();

        $this->update_state($state);


    }

    function get_state() {

        if ($this->data['Agent Supplier Purchase Order Products'] == 0 or $this->data['Agent Supplier Purchase Order Products In Process'] > 0) {
            return 'InProcess';
        }

        if ($this->data['Agent Supplier Purchase Order Products'] == $this->data['Agent Supplier Purchase Order Products Cancelled']) {
            return 'Cancelled';
        }

        if ($this->data['Agent Supplier Purchase Order Products In Delivery'] > 0 and $this->data['Agent Supplier Purchase Order Products In Process'] == 0 and $this->data['Agent Supplier Purchase Order Products Confirmed'] == 0
            and $this->data['Agent Supplier Purchase Order Products Received'] == 0 and $this->data['Agent Supplier Purchase Order Products With Problem'] == 0) {
            return 'InDelivery';
        }

        if ($this->data['Agent Supplier Purchase Order Products Received'] > 0 and $this->data['Agent Supplier Purchase Order Products In Process'] == 0 and $this->data['Agent Supplier Purchase Order Products Confirmed'] == 0
            and $this->data['Agent Supplier Purchase Order Products With Problem'] == 0) {
            return 'InWarehouse';
        }

        if ($this->data['Agent Supplier Purchase Order Products Confirmed'] > 0 and $this->data['Agent Supplier Purchase Order Products In Process'] == 0 and $this->data['Agent Supplier Purchase Order Products With Problem'] == 0) {
            return 'Confirmed';
        }

        return 'InProcess';

    }

    function update_state($value, $options = '', $metadata = array()) {
        $date = gmdate('Y-m-d H:i:s');


        $old_value  = $this->get('Agent Supplier Purchase Order State');
        $operations = array();


        if ($value == 'Submitted' and $this->get('Agent Supplier Purchase Order Agent Key')) {
            if ($old_value == 'InProcess') {
                $value = 'SubmittedAgent';

            }

        }


        if ($old_value != $value) {
            switch ($value) {
                case 'InProcess':

                    $this->update_field(
                        'Agent Supplier Purchase Order Confirm Date', '', 'no_history'
                    );
                    $this->update_field(
                        'Agent Supplier Purchase Order Estimated Receiving Date', '', 'no_history'
                    );
                    $this->update_field(
                        'Agent Supplier Purchase Order State', $value, 'no_history'
                    );
                    $operations = array(
                        'delete_operations',
                        'submit_operations',
                        'all_available_items',
                        'new_item'
                    );


                    $history_data = array(
                        'History Abstract' => _(
                            'Purchase order send back to process'
                        ),
                        'History Details'  => '',
                        'Action'           => 'created'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                    break;
                case 'Confirmed':


                    $this->fast_update(
                        array(
                            'Agent Supplier Purchase Order State' => $value,

                            'Agent Supplier Purchase Order Confirm Date'      => $date,
                            'Agent Supplier Purchase Order Last Updated Date' => $date,
                            'Agent Supplier Purchase Order Received Date'     => '',

                        )

                    );




                    $operations = array(
                        'cancel_operations',
                        'undo_submit_operations',
                        'received_operations'
                    );

                    if ($old_value == 'Submitted') {

                        $history_abstract = _("Purchase order confirmed by supplier");


                        $history_data = array(
                            'History Abstract' => $history_abstract,
                            'History Details'  => '',
                            'Action'           => 'created'
                        );
                        $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    }

                    break;

                case 'Submitted':


                    $this->update_field(
                        'Agent Supplier Purchase Order Confirm Date', $date, 'no_history'
                    );
                    $this->update_field(
                        'Agent Supplier Purchase Order Send Date', '', 'no_history'
                    );

                    $this->update_field(
                        'Agent Supplier Purchase Order State', $value, 'no_history'
                    );
                    $operations = array(
                        'cancel_operations',
                        'undo_submit_operations',
                        'received_operations'
                    );

                    if ($old_value != 'Submitted') {
                        if ($this->get('State Index') <= 30) {
                            $history_abstract = _('Purchase order submitted');
                        } else {
                            $history_abstract = _(
                                'Purchase order set back as submitted'
                            );

                        }


                        $history_data = array(
                            'History Abstract' => $history_abstract,
                            'History Details'  => '',
                            'Action'           => 'created'
                        );
                        $this->add_subject_history(
                            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                        );

                    }

                    break;

                case 'Cancelled':

                    $this->update_field(
                        'Agent Supplier Purchase Order Locaked', 'Yes', 'no_history'
                    );

                    $this->update_field(
                        'Agent Supplier Purchase Order Cancelled Date', $date, 'no_history'
                    );
                    $this->update_field(
                        'Agent Supplier Purchase Order Estimated Receiving Date', '', 'no_history'
                    );
                    $this->update_field(
                        'Agent Supplier Purchase Order State', $value, 'no_history'
                    );


                    $history_data = array(
                        'History Abstract' => _('Purchase order cancelled'),
                        'History Details'  => '',
                        'Action'           => 'created'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                    break;


                case 'Checked':


                    $this->update_field(
                        'Agent Supplier Purchase Order Locaked', 'Yes', 'no_history'
                    );

                    $this->update_field(
                        'Agent Supplier Purchase Order State', $value, 'no_history'
                    );
                    foreach ($metadata as $key => $_value) {
                        $this->update_field(
                            $key, $_value, 'no_history'
                        );
                    }


                    break;

                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                case 'Placed':


                    $this->update_field(
                        'Agent Supplier Purchase Order State', $value, 'no_history'
                    );
                    foreach ($metadata as $key => $_value) {
                        $this->update_field(
                            $key, $_value, 'no_history'
                        );
                    }


                    break;


                default:
                    exit('unknown state:'.$value);
                    break;
            }


            $sql = sprintf(
                'UPDATE `Purchase Order Transaction Fact` SET `Agent Supplier Purchase Order Last Updated Date`=%s WHERE `Agent Supplier Purchase Order Key`=%d ', prepare_mysql($date), $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                'UPDATE `Purchase Order Transaction Fact` SET `Agent Supplier Purchase Order Transaction State`=%s WHERE `Agent Supplier Purchase Order Key`=%d ', prepare_mysql($value), $this->id
            );
            $this->db->exec($sql);

            $this->update_affected_parts();

            $parent = get_object('Supplier', $this->data['Agent Supplier Purchase Order Supplier Key']);


            $parent->update_purchase_orders();


        }

        $this->update_metadata = array(
            'class_html'                => array(
                'Purchase_Order_State'                => $this->get('State'),
                'Purchase_Order_Submitted_Date'       => '&nbsp;'.$this->get('Confirm Date'),
                'Purchase_Order_Submitted_Agent_Date' => '&nbsp;'.$this->get('Submitted Agent Date'),
                'Purchase_Order_Received_Date'        => '&nbsp;'.$this->get('Received Date'),
                'Purchase_Order_Send_Date'            => '&nbsp;'.$this->get('Send Date'),

            ),
            'operations'                => $operations,
            'state_index'               => $this->get('State Index'),
            'pending_items_in_delivery' => $this->get(
                    'Agent Supplier Purchase Order Ordered Number Items'
                ) - $this->get('Agent Supplier Purchase Order Number Supplier Delivery Items')
        );


    }

    function get($key = '') {
        global $account;

        if (!$this->id) {
            return false;
        }

        switch ($key) {

            case 'Estimated Receiving Datetime':


                include_once 'utils/object_functions.php';

                if ($this->data['Agent Supplier Purchase Order Estimated Receiving Date'] and in_array(
                        $this->data['Agent Supplier Purchase Order State'], array(
                                                               'Submitted',
                                                               'Confirmed'
                                                           )
                    )) {
                    return gmdate("Y-m-d H:i:s", strtotime($this->data['Agent Supplier Purchase Order Estimated Receiving Date']));
                } else {


                    if (in_array(
                        $this->data['Agent Supplier Purchase Order State'], array(
                                                               'Submitted',
                                                               'Confirmed',
                                                           )
                    )) {


                        $supplier = get_object('Supplier', $this->data['Agent Supplier Purchase Order Supplier Key']);


                        if ($supplier->get('Supplier Average Delivery Days') != '' and is_numeric($supplier->get('Supplier Average Delivery Days'))) {

                            if ($this->data['Agent Supplier Purchase Order State'] == 'Submitted') {
                                return gmdate("Y-m-d H:i:s", strtotime('now +'.$supplier->get('Supplier Average Delivery Days').' days'));
                            } else {


                                return gmdate("Y-m-d H:i:s", strtotime($this->get('Agent Supplier Purchase Order Confirm Date').' +'.$supplier->get('Supplier Average Delivery Days').' days'));

                            }

                        } else {


                            return '';
                        }
                    } else {

                        return '';
                    }

                }

                break;

            case 'Estimated Receiving Formatted Date':

                if ($this->get('Estimated Receiving Datetime')) {
                    return strftime("%d %b %Y", strtotime($this->get('Estimated Receiving Datetime')));
                } else {
                    return '';
                }


                break;
            case 'Received Date':

                if($this->get('State Index')>=90){
                    if ($this->data['Agent Supplier Purchase Order '.$key] == '') {
                        return '';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->data['Agent Supplier Purchase Order '.$key].' +0:00')
                    );
                }else{
                    if ($this->get('Estimated Receiving Datetime')) {
                        return '<span class="italic discreet">'.strftime("%d %b %Y", strtotime($this->get('Estimated Receiving Datetime'))).'</span>';
                    } else {
                        return '';
                    }
                }
            case 'Received Date or Percentage':

                if($this->get('State Index')>=90){
                    if ($this->data['Agent Supplier Purchase Order '.$key] == '') {
                        return '&nbsp;';
                    }

                    return strftime(
                        "%e %b %Y", strtotime($this->data['Agent Supplier Purchase Order '.$key].' +0:00')
                    );
                }else{

                    if($this->data['Agent Supplier Purchase Order Products Received']>0){
                        return '('.$this->data['Agent Supplier Purchase Order Products Received'].'/'.(floatval($this->data['Agent Supplier Purchase Order Products']) - floatval($this->data['Agent Supplier Purchase Order Products Cancelled'])).') '.

                            percentage($this->data['Agent Supplier Purchase Order Products Confirmed'], ($this->data['Agent Supplier Purchase Order Products'] - $this->data['Agent Supplier Purchase Order Products Cancelled']));
                    }else{
                        if ($this->get('Estimated Receiving Datetime')) {
                            return '<span class="italic discreet">'.strftime("%d %b %Y", strtotime($this->get('Estimated Receiving Datetime'))).'</span>';
                        } else {
                            return '&nbsp;';
                        }
                    }


                }




                break;

            case 'State Index':
                //'InProcess','Confirmed','InWarehouse','InDelivery','Cancelled'
                switch ($this->data['Agent Supplier Purchase Order State']) {
                    case 'InProcess':
                        return 10;
                        break;
                    case 'Confirmed':
                        return 50;
                        break;
                    case 'InWarehouse':
                        return 90;
                        break;
                    case 'InDelivery':
                        return 100;
                        break;

                    case 'Cancelled':
                        return -10;
                        break;


                    default:
                        return 0;
                        break;
                }
                break;

            case 'Weight':


                include_once 'utils/natural_language.php';
                if ($this->data['Agent Supplier Purchase Order Weight'] == '') {
                    if ($this->get('Agent Supplier Purchase Order Products') > 0) {
                        return '<span class="italic very_discreet">'._('Unknown Weight').'</span>';
                    }
                } else {
                    return ($this->get('Agent Supplier Purchase Order Missing Weights') > 0 ? '<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's parts without weight").'" ></i> ' : '').weight(
                            $this->get('Agent Supplier Purchase Order Weight')
                        );
                }
                break;
            case 'CBM':
                if ($this->data['Agent Supplier Purchase Order CBM'] == '') {
                    if ($this->get('Agent Supplier Purchase Order Products') > 0) {
                        return '<span class="italic very_discreet">'._('Unknown CBM').'</span>';
                    }
                } else {
                    return ($this->get('Agent Supplier Purchase Order Missing CBMs') > 0 ? '<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's parts without CBM").'" ></i> ' : '').number(
                            $this->data['Agent Supplier Purchase Order CBM']
                        ).' m³';
                }
                break;

            case 'Confirm Date or Percentage':
                if ($this->data['Agent Supplier Purchase Order State'] == 'InProcess') {

                    if ($this->data['Agent Supplier Purchase Order Products Confirmed'] == 0) {
                        return '&nbsp;';
                    }

                    return '('.$this->data['Agent Supplier Purchase Order Products Confirmed'].'/'.(floatval($this->data['Agent Supplier Purchase Order Products']) - floatval($this->data['Agent Supplier Purchase Order Products Cancelled'])).') '.

                        percentage($this->data['Agent Supplier Purchase Order Products Confirmed'], ($this->data['Agent Supplier Purchase Order Products'] - $this->data['Agent Supplier Purchase Order Products Cancelled']));
                } elseif ($this->get('State Index') > 10) {
                    return strftime(
                        "%e %b %Y", strtotime($this->data['Agent Supplier Purchase Order Confirm Date'].' +0:00')
                    );
                }

                break;
            case 'Estimated Receiving Date':
            case 'Agreed Receiving Date':
            case 'Creation Date':
            case 'Confirm Date':
            case 'Cancelled Date':
                if ($this->data['Agent Supplier Purchase Order '.$key] == '') {
                    return '';
                }

                return strftime(
                    "%e %b %Y", strtotime($this->data['Agent Supplier Purchase Order '.$key].' +0:00')
                );

                break;


            case ('State'):
                switch ($this->data['Agent Supplier Purchase Order State']) {
                    case 'InProcess':
                        return _('In Process');
                        break;
                    case 'Confirmed':
                        return _('Confirmed');
                        break;
                    case 'InWarehouse':
                        return _('In warehouse');
                        break;
                    case 'InDelivery':
                        return _('In delivery');
                        break;

                    case 'Cancelled':
                        return _('Cancelled');
                        break;



                    default:
                        return $this->data['Agent Supplier Purchase Order State'];
                        break;
                }

                break;


            case 'Amount':
                return money(
                    $this->data['Agent Supplier Purchase Order Amount'], $this->data['Agent Supplier Purchase Order Currency Code']
                );
                break;
            case 'Number Products':
                return number($this->data ['Agent Supplier Purchase Order Number Products']);
                break;

            default:


                if (preg_match(
                    '/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/', $key
                )) {
                    $amount = 'Agent Supplier Purchase Order '.$key;

                    return money(
                        $this->data[$amount], $this->data['Agent Supplier Purchase Order Currency Code']
                    );
                }

                if (preg_match(
                    '/^(Total|Items|(Shipping |Charges )?Net).*(Amount Account Currency)$/', $key
                )) {
                    $key    = preg_replace(
                        '/ Account Currency/', '', $key
                    );
                    $amount = 'Agent Supplier Purchase Order '.$key;

                    return money(
                        $this->data['Agent Supplier Purchase Order Currency Exchange'] * $this->data[$amount], $account->get('Account Currency')
                    );


                }


                break;
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists(
            'Agent Supplier Purchase Order '.$key, $this->data
        )) {
            return $this->data[$this->table_name.' '.$key];
        }


    }

    function update_affected_parts() {


        $sql = sprintf(
            "SELECT `Supplier Part Key` FROM `Purchase Order Transaction Fact` WHERE `Agent Supplier Purchase Order Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $supplier_part = get_object('SupplierPart', $row['Supplier Part Key']);
                $supplier_part->part->update_next_deliveries_data();


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function get_deliveries($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.SupplierDelivery.php';
        }


        $deliveries = array();
        $sql        = sprintf(
            "SELECT `Supplier Delivery Key` FROM `Purchase Order Transaction Fact` WHERE `Agent Supplier Purchase Order Key`=%d  GROUP BY `Supplier Delivery Key`", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Supplier Delivery Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $deliveries[$row['Supplier Delivery Key']] = new SupplierDelivery($row['Supplier Delivery Key']);

                } else {
                    $deliveries[$row['Supplier Delivery Key']] = $row['Supplier Delivery Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $deliveries;

    }

    function update_item($data) {

        switch ($data['field']) {
            case 'Agent Supplier Purchase Order Quantity':
                return $this->update_item_quantity($data);
                break;

            default:

                break;
        }

    }

    function update_item_quantity($data) {


        global $account;


        $item_key          = $data['item_key'];
        $item_historic_key = $data['item_historic_key'];
        $qty               = $data['qty'];

        include_once 'class.SupplierPart.php';
        $supplier_part = new SupplierPart($item_key);


        $date            = gmdate('Y-m-d H:i:s');
        $transaction_key = '';

        if ($qty == 0) {

            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Agent Supplier Purchase Order Key`=%d AND `Supplier Part Key`=%d ", $this->id, $item_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $sql = sprintf(
                        "DELETE FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Transaction Fact Key`=%d ", $row['Purchase Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                    $transaction_key = $row['Purchase Order Transaction Fact Key'];

                }
            }


            $amount    = 0;
            $subtotals = '';


        } else {


            $amount = $qty * $supplier_part->get('Supplier Part Unit Cost') * $supplier_part->part->get('Part Units Per Package') * $supplier_part->get('Supplier Part Packages Per Carton');

            $extra_amount = round($amount * floatval($supplier_part->get('Supplier Part Unit Extra Cost Percentage')) / 100, 2);
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


            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Agent Supplier Purchase Order Key`=%d AND `Supplier Part Key`=%d ", $this->id, $item_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $sql = sprintf(
                        "update`Purchase Order Transaction Fact` set  `Agent Supplier Purchase Order Quantity`=%f,`Agent Supplier Purchase Order Last Updated Date`=%s,
                        `Agent Supplier Purchase Order Net Amount`=%f ,
                        `Agent Supplier Purchase Order Extra Cost Amount`=%f ,
                        `Agent Supplier Purchase Order CBM`=%s,`Agent Supplier Purchase Order Weight`=%s  where  `Purchase Order Transaction Fact Key`=%d ", $qty, prepare_mysql($date), $amount, $extra_amount, $cbm, $weight, $row['Purchase Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                    /*

                    if ($row['Note to Supplier Locked']=='No' and $data['Note to Supplier']!='') {
                        $sql = sprintf( "update`Purchase Order Transaction Fact` set  `Note to Supplier`=%s where  `Purchase Order Transaction Fact Key`=%d ",
                            prepare_mysql ( $data['Note to Supplier']),
                            $row['Purchase Order Transaction Fact Key']
                        );

                        $this->db->exec($sql);
                    }
    */
                    $transaction_key = $row['Purchase Order Transaction Fact Key'];
                } else {


                    $item_index = 1;
                    $sql        = sprintf(
                        'SELECT max(`Agent Supplier Purchase Order Item Index`) item_index FROM `Purchase Order Transaction Fact` WHERE `Agent Supplier Purchase Order Key`=%d ', $this->id
                    );
                    if ($result2 = $this->db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            if (is_numeric($row2['item_index'])) {
                                $item_index = $row2['item_index'] + 1;
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "INSERT INTO `Purchase Order Transaction Fact` (`Agent Supplier Purchase Order Item Index`,`Supplier Part Key`,`Supplier Part Historic Key`,`Currency Code`,`Agent Supplier Purchase Order Last Updated Date`,`Agent Supplier Purchase Order Transaction State`,
					`Supplier Key`,`Agent Key`,`Agent Supplier Purchase Order Key`,`Agent Supplier Purchase Order Quantity`,`Agent Supplier Purchase Order Net Amount`,`Agent Supplier Purchase Order Extra Cost Amount`,`Note to Supplier`,`Agent Supplier Purchase Order CBM`,`Agent Supplier Purchase Order Weight`,
					`User Key`,`Creation Date`
					)
					VALUES (%d,%d,%d,%s,%s,%s,
					 %d,%s,%d,%.6f,%.2f,%.2f,%s,%s,%s,
					 %d,%s
					 )", $item_index, $item_key, $item_historic_key, prepare_mysql($this->get('Agent Supplier Purchase Order Currency Code')), prepare_mysql($date), prepare_mysql($this->get('Agent Supplier Purchase Order State')),

                        $supplier_part->get('Supplier Part Supplier Key'), ($supplier_part->get('Supplier Part Agent Key') == ''
                        ? 'Null'
                        : sprintf(
                            "%d", $supplier_part->get('Supplier Part Agent Key')
                        )), $this->id, $qty, $amount, $extra_amount, prepare_mysql(
                            $supplier_part->get(
                                'Supplier Part Note to Supplier'
                            ), false
                        ), $cbm, $weight, $this->editor['User Key'], prepare_mysql($date)


                    );
                    //print $sql;
                    $this->db->exec($sql);
                    $transaction_key = $this->db->lastInsertId();


                }

                /*

                $subtotals = money(
                    $amount, $this->get('Agent Supplier Purchase Order Currency Code')
                );

                if ($weight > 0) {
                    $subtotals .= ' '.weight($weight);
                }
                if ($cbm > 0) {
                    $subtotals .= ' '.number($cbm).' m³';
                }
                */

                $units_per_carton = $supplier_part->part->get('Part Units Per Package') * $supplier_part->get('Supplier Part Packages Per Carton');
                $skos_per_carton  = $supplier_part->get('Supplier Part Packages Per Carton');

                $subtotals = '';
                if ($qty > 0) {

                    $subtotals .= $qty * $units_per_carton.'u. | '.$qty * $skos_per_carton.'pkg. ';


                    $subtotals .= ' | '.money($amount, $this->get('Agent Supplier Purchase Order Currency Code'));

                    if ($this->get('Agent Supplier Purchase Order Currency Code') != $account->get(
                            'Account Currency'
                        )) {
                        $subtotals .= ' <span class="">('.money(
                                $amount * $this->get(
                                    'Agent Supplier Purchase Order Currency Exchange'
                                ), $account->get('Account Currency')
                            ).')</span>';

                    }

                    if ($weight > 0) {
                        $subtotals .= ' | '.weight($weight);
                    }
                    if ($cbm > 0) {
                        $subtotals .= ' | '.number($cbm).' m³';
                    }
                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }
        $supplier_part->part->update_next_deliveries_data();
        $this->update_totals();
        $operations = array();

        if ($this->get('State Index') == 10) {


            if ($this->get('Agent Supplier Purchase Order Products') == 0) {
                $operations = array(
                    'delete_operations',
                );
            } else {
                $operations = array(
                    'delete_operations',
                    'submit_operations'
                );
            }


        }

        /*
        elseif ($this->get('Order State') == 'InProcess') {

            if($this->get('Order Number Items')==0){
                $operations = array(
                    'cancel_operations',
                    'undo_submit_operations'
                );
            }else{
                $operations = array(
                    'send_to_warehouse_operations',
                    'cancel_operations',
                    'undo_submit_operations'
                );
            }


        } elseif ($this->get('Order State') == 'InWarehouse') {
            $operations = array('cancel_operations');
        } elseif ($this->get('Order State') == 'PackedDone') {
            $operations = array(
                'invoice_operations',
                'cancel_operations'
            );
        } else {
            $operations = array();
        }
        */

        $this->update_metadata = array(
            'class_html' => array(
                'Purchase_Order_Total_Amount'                  => $this->get('Total Amount'),
                'Purchase_Order_Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                'Purchase_Order_Weight'                        => $this->get('Weight'),
                'Purchase_Order_CBM'                           => $this->get('CBM'),
                'Purchase_Order_Number_Items'                  => $this->get('Number Items'),
            ),
            'operations' => $operations,
        );

        /*
        if ($this->get('Agent Supplier Purchase Order Products') == 0) {
            $this->update_metadata['hide'] = array('submit_operation');
        } else {
            $this->update_metadata['show'] = array('submit_operation');

        }
        */


        return array(
            'transaction_key' => $transaction_key,
            'subtotals'       => $subtotals,
            'to_charge'       => money($amount, $this->data['Agent Supplier Purchase Order Currency Code']),
            'qty'             => $qty + 0
        );


    }

    function mark_as_confirmed($data) {

        foreach ($data as $key => $value) {
            if (array_key_exists(
                $key, $this->data
            )) {
                $this->data[$key] = $value;
            }

        }

        $sql = sprintf(
            "UPDATE `Agent Supplier Purchase Order Dimension` SET `Agent Supplier Purchase Order Confirmed Date`=%s,`Agent Supplier Purchase Order Agreed Receiving Date`=%s ,`Agent Supplier Purchase Order Estimated Receiving Date`=%s,`Agent Supplier Purchase Order State`='Confirmed' WHERE `Agent Supplier Purchase Order Key`=%d",
            prepare_mysql($data['Agent Supplier Purchase Order Confirmed Date']), prepare_mysql($data['Agent Supplier Purchase Order Agreed Receiving Date']), prepare_mysql($data['Agent Supplier Purchase Order Agreed Receiving Date']), $this->id
        );


        $this->db->exec($sql);

        $sql = sprintf(
            "UPDATE `Purchase Order Transaction Fact` SET  `Agent Supplier Purchase Order Last Updated Date`=%s ,`Agent Supplier Purchase Order Transaction State`='Confirmed'  WHERE `Agent Supplier Purchase Order Key`=%d",
            prepare_mysql($data['Agent Supplier Purchase Order Confirmed Date']), $this->id
        );
        $this->db->exec($sql);

        $this->get_data(
            'id', $this->id
        );
        $this->update_affected_parts();

        $history_data = array(
            'History Abstract' => _('Purchase order marked as confirmed'),
            'History Details'  => ''
        );
        $this->add_subject_history($history_data);

    }

    function submit($data) {

        foreach ($data as $key => $value) {
            if (array_key_exists(
                $key, $this->data
            )) {
                $this->data[$key] = $value;
            }

        }

        $sql = sprintf(
            "UPDATE `Agent Supplier Purchase Order Dimension` SET `Agent Supplier Purchase Order Confirm Date`=%s,`Agent Supplier Purchase Order Estimated Receiving Date`=%s,`Agent Supplier Purchase Order Main Source Type`=%s,`Agent Supplier Purchase Order Main Buyer Key`=%s,`Agent Supplier Purchase Order Main Buyer Name`=%s,`Agent Supplier Purchase Order State`='Submitted' WHERE `Agent Supplier Purchase Order Key`=%d",
            prepare_mysql($data['Agent Supplier Purchase Order Confirm Date']), prepare_mysql($data['Agent Supplier Purchase Order Estimated Receiving Date']), prepare_mysql($data['Agent Supplier Purchase Order Main Source Type']),
            prepare_mysql($data['Agent Supplier Purchase Order Main Buyer Key']), prepare_mysql($data['Agent Supplier Purchase Order Main Buyer Name']), $this->id
        );


        $this->db->exec($sql);

        $sql = sprintf(
            "UPDATE `Purchase Order Transaction Fact` SET  `Agent Supplier Purchase Order Last Updated Date`=%s ,`Agent Supplier Purchase Order Transaction State`='Submitted'  WHERE `Agent Supplier Purchase Order Key`=%d",
            prepare_mysql($data['Agent Supplier Purchase Order Confirm Date']), $this->id
        );
        $this->db->exec($sql);


        $this->update_affected_parts();

        $history_data = array(
            'History Abstract' => _('Purchase order submitted'),
            'History Details'  => ''
        );
        $this->add_subject_history($history_data);

    }

    function mark_as_associated_with_sdn($sdn_key, $sdn_name) {

        $sql = sprintf(
            "UPDATE `Agent Supplier Purchase Order Dimension` SET `Agent Supplier Purchase Order State`='In Warehouse' WHERE `Agent Supplier Purchase Order Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $history_data = array(
            'History Abstract' => sprintf(
                _('Purchase order associated with delivery %s'), '<a href="supplier_dn.php?id='.$sdn_key.'">'.$sdn_name.'</a>'
            ),
            'History Details'  => ''
        );
        $this->add_subject_history($history_data);

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {
        switch ($field) {

            case 'Create Agent Supplier Orders':

                $this->create_agent_supplier_orders();


                break;
            case 'Agent Supplier Purchase Order State':
                $this->update_state(
                    $value, $options, $metadata
                );
                break;
            case 'Agent Supplier Purchase Order Estimated Receiving Date':
                $this->update_field($field, $value, $options);
                $this->update_affected_parts();

                $this->update_metadata = array(
                    'class_html' => array(
                        'Purchase_Order_Received_Date' => $this->get(
                            'Received Date'
                        ),

                    )
                );

                break;
            default:


                if (array_key_exists(
                    $field, $this->data
                )) {
                    if ($value != $this->data[$field]) {

                        $this->update_field(
                            $field, $value, $options
                        );
                    }
                }

                break;
        }


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Agent Supplier Purchase Order Public ID':
                $label = _('public Id');
                break;
            case 'Agent Supplier Purchase Order Incoterm':
                $label = _('Incoterm');
                break;
            case 'Agent Supplier Purchase Order Port of Export':
                $label = _('port of export');
                break;
            case 'Agent Supplier Purchase Order Port of Import':
                $label = _('port of import');
                break;
            case 'Agent Supplier Purchase Order Estimated Receiving Date':
                $label = _('estimated receiving date');
                break;
            case 'Agent Supplier Purchase Order Agreed Receiving Date':
                $label = _('agreed receiving date');
                break;
            case 'Agent Supplier Purchase Order Account Number':
                $label = _('Account number');
                break;
            case 'Agent Supplier Purchase Order Warehouse Address':
                $label = _('Delivery address');
                break;


            default:
                $label = $field;

        }

        return $label;

    }


}


?>
