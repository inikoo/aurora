<?php
$where    = 'where true';
$table    = '`Delivery Note Dimension` D ';
$wheref   = '';
$group_by = '';


if (isset($parameters['awhere']) and $parameters['awhere']) {
    $tmp = preg_replace('/\\\"/', '"', $parameters['awhere']);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data              = json_decode($tmp, true);
    $raw_data['store_key'] = $store;
    list($where, $table) = dn_awhere($raw_data);

    $where_type     = '';
    $where_interval = '';
} //print $where_interval;exit;
elseif ($parameters['parent'] == 'list') {
    $sql = sprintf(
        "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );

    $res = mysql_query($sql);
    if ($dn_list_data = mysql_fetch_assoc($res)) {
        $awhere = false;
        if ($dn_list_data['List Type'] == 'Static') {
            $table
                        = '`List Delivery Note Bridge` DB left join `Delivery Note Dimension` D  on (DB.`Delivery Note Key`=D.`Delivery Note Key`)';
            $where_type = sprintf(
                ' and `List Key`=%d ', $parameters['parent_key']
            );

        } else {
            $tmp = preg_replace('/\\\"/', '"', $dn_list_data['List Metadata']);
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);

            $raw_data['store_key'] = $store;
            list($where, $table) = dn_awhere($raw_data);
        }

    } else {
        exit("error");
    }
} elseif ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {
        $where = sprintf(
            ' where  `Delivery Note Store Key`=%d ', $parameters['parent_key']
        );
        include_once 'class.Store.php';
        $store    = new Store($parameters['parent_key']);
        $currency = $store->data['Store Currency Code'];
    } else {
        $where = sprintf(' where  false');
    }


} elseif ($parameters['parent'] == 'account') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {

        if (count($user->stores) == 0) {
            $where = ' where false';
        } else {

            $where = sprintf(
                'where  `Delivery Note Store Key` in (%s)  ', join(',', $user->stores)
            );
        }
    }
} elseif ($parameters['parent'] == 'part') {
    global $corporate_currency;
    $where = sprintf(' where  `Part SKU`=%d ', $parameters['parent_key']);

    $table
              = '`Inventory Transaction Fact` ITF left join  `Delivery Note Dimension` D  on (ITF.`Delivery Note Key`=D.`Delivery Note Key`) ';
    $group_by = ' group by ITF.`Delivery Note Key`';
    $currency = $corporate_currency;


} elseif ($parameters['parent'] == 'order') {

    $table
           = '`Order Delivery Note Bridge` B left join  `Delivery Note Dimension` D  on (D.`Delivery Note Key`=B.`Delivery Note Key`)  ';
    $where = sprintf('where  B.`Order Key`=%d  ', $parameters['parent_key']);


} elseif ($parameters['parent'] == 'invoice') {

    $table
           = '`Invoice Delivery Note Bridge` B left join  `Delivery Note Dimension` D  on (D.`Delivery Note Key`=B.`Delivery Note Key`)  ';
    $where = sprintf('where  B.`Invoice Key`=%d  ', $parameters['parent_key']);


} else {
    exit("unknown parent (dn)\n");
}

if (isset($parameters['period'])) {
    include_once('utils/date_functions.php');
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, '`Delivery Note Date`');
    $where .= $where_interval['mysql'];
}


if (isset($parameters['elements'])) {

    $elements = $parameters['elements'];

    switch ($parameters['elements_type']) {
        case('dispatch'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach ($elements['dispatch']['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $num_elements_checked++;
                    if ($_key == 'Ready') {
                        $_elements .= ",'Ready to be Picked'";

                    } elseif ($_key == 'Picking') {
                        $_elements .= ",'Picking','Picking & Packing','Picked','Picker Assigned','Picker & Packer Assigned'";
                    } elseif ($_key == 'Packing') {
                        $_elements .= ",'Packing','Packed','Packer Assigned','Packed Done'";
                    } elseif ($_key == 'Done') {
                        $_elements .= ",'Approved'";
                    } elseif ($_key == 'Send') {
                        $_elements .= ",'Dispatched'";
                    } elseif ($_key == 'Returned') {
                        $_elements .= ",'Cancelled','Cancelled to Restock'";
                    }
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Delivery Note State` in ('.$_elements.')';
            }
            break;

        case('type'):


            $_elements            = '';
            $num_elements_checked = 0;
            foreach ($elements['type']['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $num_elements_checked++;

                    if ($_key == 'Replacements') {
                        $_elements .= ", 'Replacement','Replacement & Shortages'  ";

                    } else {

                        $_elements .= ", '$_key'";
                    }
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 5) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Delivery Note Type` in ('.$_elements.')';
            }
            break;

    }
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Delivery Note Date Created`';
} elseif ($order == 'id') {
    $order = '`Delivery Note File As`';
} elseif ($order == 'customer') {
    $order = '`Delivery Note Customer Name`';
} elseif ($order == 'type') {
    $order = '`Delivery Note Type`';
} elseif ($order == 'weight') {
    $order = '`Delivery Note Weight`';
} elseif ($order == 'parcels') {
    $order = '`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';
} else {
    $order = 'D.`Delivery Note Key`';
}


if ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Delivery Note Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Delivery Note ID` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'country' and $f_value != '') {
    if ($f_value == 'UNK') {
        $wheref .= " and  `Delivery Note Country Code`='".$f_value."'    ";
        $find_data = ' '._('a unknown country');
    } else {
        $f_value = Address::parse_country($f_value);
        if ($f_value != 'UNK') {
            $wheref .= " and  `Delivery Note Country Code`='".$f_value."'    ";
            $country   = new Country('code', $f_value);
            $find_data = ' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code'])
                .'.gif" alt="'.$country->data['Country Code'].'"/>';
        }
    }
}

$fields = '*';
$sql_totals
        = "select count(Distinct D.`Delivery Note Key`) as num from $table $where ";


function dn_awhere($awhere) {


    $where_data = array(
        //'product_ordered1'=>'∀',
        'weight'                        => array(),
        'state'                         => array(),
        'note_type'                     => array(),
        'dispatch_method'               => array(),
        'parcel_type'                   => array(),
        'created_date_from'             => '',
        'created_date_to'               => '',
        'start_picking_date_from'       => '',
        'start_picking_date_to'         => '',
        'finish_picking_date_from'      => '',
        'finish_picking_date_to'        => '',
        'start_packing_date_from'       => '',
        'start_packing_date_to'         => '',
        'finish_packing_date_from'      => '',
        'finish_packing_date_to'        => '',
        'dispatched_approved_date_from' => '',
        'dispatched_approved_date_to'   => '',
        'delivery_note_date_from'       => '',
        'delivery_note_date_to'         => '',
        'billing_geo_constraints'       => '',
        'weight_lower'                  => '',
        'weight_upper'                  => ''
    );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key => $item) {
        $where_data[$key] = $item;
    }


    $where = 'where true';
    $table = '`Delivery Note Dimension` D ';

    $use_product = false;
    //$use_categories =false;
    $use_otf = false;


    $weight_where = '';
    foreach ($where_data['weight'] as $weight) {
        switch ($weight) {
            case 'less':
                $weight_where .= sprintf(
                    " and `Delivery Note Weight`<'%s' ", $where_data['weight_lower']
                );
                break;
            case 'equal':
                $weight_where .= sprintf(
                    " and `Delivery Note Weight`='%s'  ", $where_data['weight_lower']
                );
                break;
            case 'more':
                $weight_where .= sprintf(
                    " and `Delivery Note Weight`>'%s'  ", $where_data['weight_upper']
                );
                break;
            case 'between':
                $weight_where .= sprintf(
                    " and  `Delivery Note Weight`>'%s'  and `Delivery Note Weight`<'%s'", $where_data['weight_lower'], $where_data['weight_upper']
                );
                break;
        }
    }
    $weight_where = preg_replace('/^\s*and/', '', $weight_where);

    if ($weight_where != '') {
        $where .= " and ($weight_where)";
    }

    $state_where = '';
    foreach ($where_data['state'] as $state) {
        switch ($state) {
            case 'picking_and_packing':
                $state_where .= sprintf(
                    " or `Delivery Note State`='Picking & Packing' "
                );
                break;
            case 'packer_assigned':
                $state_where .= sprintf(
                    " or `Delivery Note State`='Packer Assigned'  "
                );
                break;
            case 'ready_to_be_picked':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Ready to be Picked'  "
                );
                break;
            case 'picker_assigned':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Picker Assigned'  "
                );
                break;
            case 'picking':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Picking' "
                );
                break;
            case 'picked':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Picked'  "
                );
                break;
            case 'packing':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Packing'  "
                );
                break;
            case 'packed':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Packed'  "
                );
                break;
            case 'approved':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Approved'  "
                );
                break;
            case 'dispatched':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Dispatched'  "
                );
                break;
            case 'cancelled':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Cancelled'  "
                );
                break;
            case 'cancelled_to_restock':
                $state_where .= sprintf(
                    " or  `Delivery Note State`='Cancelled to Restock'  "
                );
                break;

        }
    }
    $state_where = preg_replace('/^\s*or/', '', $state_where);
    if ($state_where != '') {
        $where .= " and ($state_where)";
    }

    $note_type_where = '';
    foreach ($where_data['note_type'] as $note_type) {
        switch ($note_type) {
            case 'replacement_and_shortages':
                $note_type_where .= sprintf(
                    " or `Delivery Note Type`='Replacement & Shortages' "
                );
                break;
            case 'order':
                $note_type_where .= sprintf(
                    " or `Delivery Note Type`='Order'  "
                );
                break;
            case 'replacement':
                $note_type_where .= sprintf(
                    " or  `Delivery Note Type`='Replacement'  "
                );
                break;
            case 'shortages':
                $note_type_where .= sprintf(
                    " or  `Delivery Note Type`='Shortages'  "
                );
                break;
            case 'sample':
                $note_type_where .= sprintf(
                    " or  `Delivery Note Type`='Sample' "
                );
                break;
            case 'donation':
                $note_type_where .= sprintf(
                    " or  `Delivery Note Type`='Donation'  "
                );
                break;

        }
    }
    $note_type_where = preg_replace('/^\s*or/', '', $note_type_where);
    if ($note_type_where != '') {
        $where .= " and ($note_type_where)";
    }


    $dispatch_method_where = '';
    foreach ($where_data['dispatch_method'] as $dispatch_method) {
        switch ($dispatch_method) {
            case 'dispatch':
                $dispatch_method_where .= sprintf(
                    " or `Delivery Note Dispatch Method`='Dispatch' "
                );
                break;
            case 'collection':
                $dispatch_method_where .= sprintf(
                    " or `Delivery Note Dispatch Method`='Collection'  "
                );
                break;
            case 'unknown':
                $dispatch_method_where .= sprintf(
                    " or  `Delivery Note Dispatch Method`='Unknown'  "
                );
                break;
            case 'na':
                $dispatch_method_where .= sprintf(
                    " or  `Delivery Note Dispatch Method`='NA'  "
                );
                break;

        }
    }
    $dispatch_method_where = preg_replace(
        '/^\s*or/', '', $dispatch_method_where
    );
    if ($dispatch_method_where != '') {
        $where .= " and ($dispatch_method_where)";
    }


    $parcel_type_where = '';
    foreach ($where_data['parcel_type'] as $parcel_type) {
        switch ($parcel_type) {
            case 'box':
                $parcel_type_where .= sprintf(
                    " or `Delivery Note Parcel Type`='Box' "
                );
                break;
            case 'pallet':
                $parcel_type_where .= sprintf(
                    " or `Delivery Note Parcel Type`='Pallet'  "
                );
                break;
            case 'envelope':
                $parcel_type_where .= sprintf(
                    " or  `Delivery Note Parcel Type`='Envelope'  "
                );
                break;

        }
    }
    $parcel_type_where = preg_replace('/^\s*or/', '', $parcel_type_where);
    if ($parcel_type_where != '') {
        $where .= " and ($parcel_type_where)";
    }


    $date_interval_created             = prepare_mysql_dates(
        $where_data['created_date_from'], $where_data['created_date_to'], '`Delivery Note Date Created`', 'only_dates'
    );
    $date_interval_start_picking       = prepare_mysql_dates(
        $where_data['start_picking_date_from'], $where_data['start_picking_date_to'], '`Delivery Note Date Start Picking`', 'only_dates'
    );
    $date_interval_finish_picking      = prepare_mysql_dates(
        $where_data['finish_picking_date_from'], $where_data['finish_picking_date_to'], '`Delivery Note Date Finish Picking`', 'only_dates'
    );
    $date_interval_start_packing       = prepare_mysql_dates(
        $where_data['start_packing_date_from'], $where_data['start_packing_date_to'], '`Delivery Note Date Start Packing`', 'only_dates'
    );
    $date_interval_finish_packing      = prepare_mysql_dates(
        $where_data['finish_packing_date_from'], $where_data['finish_packing_date_to'], '`Delivery Note Date Finish Packing`', 'only_dates'
    );
    $date_interval_dispatched_approved = prepare_mysql_dates(
        $where_data['dispatched_approved_date_from'], $where_data['dispatched_approved_date_to'], '`Delivery Note Date Dispatched Approved`', 'only_dates'
    );
    $date_interval_delivery_note       = prepare_mysql_dates(
        $where_data['delivery_note_date_from'], $where_data['delivery_note_date_to'], '`Delivery Note Date`', 'only_dates'
    );


    $where .= $date_interval_created['mysql'].$date_interval_start_picking['mysql'].$date_interval_finish_picking['mysql'].$date_interval_start_packing['mysql'].$date_interval_finish_packing['mysql']
        .$date_interval_dispatched_approved['mysql'].$date_interval_delivery_note['mysql'];


    $where_billing_geo_constraints = '';
    if ($where_data['billing_geo_constraints'] != '') {
        $where_billing_geo_constraints = sprintf(
            " and `Order Billing To Country 2 Alpha Code`='%s'", $where_data['billing_geo_constraints']
        );
    }


    //print $table. $where; exit;

    return array(
        $where,
        $table
    );
}


?>
