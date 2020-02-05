<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2015 at 15:00:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/new_fork.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}




$_data = prepare_values(
    $_REQUEST, array(
                 'state'      => array('type' => 'json array'),
                 'type'       => array('type' => 'string'),
                 'ar_file'    => array('type' => 'string'),
                 'tipo'       => array('type' => 'string'),
                 'parameters' => array('type' => 'json array'),
                 'fields'     => array('type' => 'json array')
             )
);

$dont_save_table_state = true;
$_data['nr']           = 1000000;
$_data['page']         = 1;

$_data['type'] = strtolower($_data['type']);

include 'conf/export_fields.php';


if ($_data['tipo'] == 'timeserie_records') {

    include_once 'class.Timeserie.php';
    $timeseries = new Timeseries($_data['parameters']['parent_key']);

    $field_set = get_export_fields('timeserie_records_'.$timeseries->get('Type'));

    if ($timeseries->get('Type') == 'StoreSales') {
        $field_set[1]['label'] .= ' '.$timeseries->parent->get('Currency Code');
        $field_set[2]['label'] .= ' '.$account->get('Currency Code');
        if ($timeseries->parent->get('Currency Code') == $account->get(
                'Currency'
            )) {
            unset($field_set[2]);
        }
    }


} else {


    if ($_data['tipo'] == 'billingregion_taxcategory.invoices' or $_data['tipo'] == 'billingregion_taxcategory.refunds') {
        $_tipo = 'invoices';
    } elseif ($_data['tipo'] == 'in_process_parts' or $_data['tipo'] == 'active_parts' or $_data['tipo'] == 'discontinuing_parts' or $_data['tipo'] == 'discontinued_parts') {
        $_tipo = 'parts';
    } elseif ($_data['tipo'] == 'parts_barcode_errors') {
        $_tipo = 'part_barcode_errors';
    } elseif ($_data['tipo'] == 'parts_to_replenish_picking_location') {
        $_tipo = 'warehouse_parts_to_replenish_picking_location';
    } elseif ($_data['tipo'] == 'abandoned_cart') {
        $_tipo = 'abandoned_cart.mail_list';
    } elseif ($_data['tipo'] == 'newsletter_mail_list') {
        $_tipo = 'mail_list';
    } elseif ($_data['tipo'] == 'stock.history.day') {
        $_tipo = 'inventory_stock_history_day';
    } elseif ($_data['tipo'] == 'client_order.items') {
        $_tipo = 'client_order_items';
    } elseif ($_data['tipo'] == 'agent_supplier_order.items') {
        $_tipo = 'client_order_items';
    } elseif ($_data['tipo'] == 'supplier.order.items_in_process') {
        $_tipo = 'supplier.order.items';

    } elseif ($_data['tipo'] == 'sent_emails') {
        //print_r($_data);
        // todo parse request for prospects (ise state to find out)
        $_tipo = 'customer_sent_emails';
    } else {
        $_tipo = $_data['tipo'];
    }

    $export_fields=get_export_fields($_tipo);

    if (count($export_fields)==0) {
        $response = array(
            'state' => 405,
            'resp'  => 'field set not found: '.$_tipo
        );
        echo json_encode($response);
        exit;
    }

    $field_set = $export_fields;
}


$group_by     = '';
$_export_data = $_data;
include_once 'prepare_table/init.php';


$fields = '';

foreach ($_export_data['fields'] as $_key => $field_key) {

    if ($field_key == '') {
        unset($_export_data['fields'][$_key]);
        continue;
    }

    if (isset($field_set[$field_key])) {
        $fields .= $field_set[$field_key]['name'].',';
    }
}


$fields = trim(preg_replace('/,$/', '', $fields));

if ($fields == '') {
    $fields = '*';
}

$sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction ";


if ($_export_data['type'] == 'excel') {
    $output = 'xls';
} else {
    $output = $_export_data['type'];
}


if ($_export_data['tipo'] == 'products') {

    $placeholders = array(
        '[image_address]' => $account->get('Account System Public URL').'/wi.php?id='
    );

    $sql = strtr($sql, $placeholders);
}

$_sql = "INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Key`) VALUES (?,?,?) ";

$db->prepare($_sql)->execute(
    array(
        gmdate('Y-m-d H:i:s'),
        $_export_data['tipo'],
        $user->id
    )
);


$download_key = $db->lastInsertId();


$export_data = array(
    'table'        => $_export_data['tipo'],
    'download_key' => $download_key,
    'output'       => $output,
    'user_key'     => $user->id,
    'sql_count'    => $sql_totals,
    'sql_data'     => $sql,
    'fetch_type'   => 'simple',
    'fields'       => $_export_data['fields'],
    'field_set'    => $field_set,
);


new_housekeeping_fork(
    'au_export', $export_data, $account->get('Account Code')
);


$response = array(
    'state'        => 200,
    'download_key' => $download_key,
    'txt'          => '<i class="fa background fa-spinner fa-spin"></i> '._('Queued').'</span>',

    'type' => $_export_data['type'],
    'tipo' => $_export_data['tipo']
);
echo json_encode($response);


