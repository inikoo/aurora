<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2015 at 15:00:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once 'utils/table_functions.php';
include_once 'utils/new_fork.php';

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


if ($_data['tipo'] == 'portfolio_items') {
    $_tipo = 'portfolio_items';
} else {
    exit($_data['tipo'].' not set');
}

$export_fields = get_export_fields($_tipo);

if (count($export_fields) == 0) {
    $response = array(
        'state' => 405,
        'resp'  => 'field set not found: '.$_tipo
    );
    echo json_encode($response);
    exit;
}

$field_set = $export_fields;


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

$_sql = "INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Type`,`Download Creator Key`) VALUES (?,?,?,?) ";

$db->prepare($_sql)->execute(
    array(
        gmdate('Y-m-d H:i:s'),
        $_export_data['tipo'],
        'Customer',
        $customer->id
    )
);


$download_key = $db->lastInsertId();


$export_data = array(
    'table'        => $_export_data['tipo'],
    'download_key' => $download_key,
    'output'       => $output,
    'ws_key'       => md5(DNS_ACCOUNT_CODE.'-'.$_SESSION['website_key'].'-'.$customer->id.'-'.crc32($customer->id.'v1')),
    'sql_count'    => $sql_totals,
    'sql_data'     => $sql,
    'fetch_type'   => 'simple',
    'fields'       => $_export_data['fields'],
    'field_set'    => $field_set,
);


new_housekeeping_fork(
    'au_export', $export_data, DNS_ACCOUNT_CODE
);


$response = array(
    'state'        => 200,
    'download_key' => $download_key,
    'txt'          => '<i class="fa background fa-spinner fa-spin"></i> '._('Queued').'</span>',

    'type' => $_export_data['type'],
    'tipo' => $_export_data['tipo']
);
echo json_encode($response);


