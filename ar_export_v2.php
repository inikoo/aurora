<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 30 Jul 2021 01:24:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Original: 29 December 2015 at 15:00:15 GMT+8, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/new_fork.php';

/** @var PDO $db */
/** @var \Account $account */
/** @var \User $user */

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$_data = prepare_values($_REQUEST, array(
                                     'state'      => array('type' => 'json array'),
                                     'type'       => array('type' => 'string'),
                                     'ar_file'    => array('type' => 'string'),
                                     'tipo'       => array('type' => 'string'),
                                     'parameters' => array('type' => 'json array'),
                                     'fields'     => array('type' => 'json array')
                                 ));



include 'conf/export_fields.php';

if ($_data['type'] == 'excel') {
    $output = 'xls';
} else {
    $output = $_data['type'];
}


if ($_data['tipo'] == 'billingregion_taxcategory.invoices' or $_data['tipo'] == 'billingregion_taxcategory.refunds') {
    $_tipo = 'invoices';
} else {
    $_tipo = $_data['tipo'];
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
$_export_data = $_data;




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

$_data['nr']           = 1000000;
$_data['page']         = 1;
$_data['type']         = strtolower($_data['type']);

if($_tipo=='invoices'){
    include_once 'prepare_table/invoices.ptc.php';
    $table=new prepare_table_invoices($db,$account,$user);
    $table->initialize($_data);
    $table->update_session();
    $table->prepare_table();
}else{
    $response = array(
        'state' => 405,
        'resp'  => 'tipo set not found: '.$_tipo
    );
    echo json_encode($response);
    exit;
}





$sql = "select $fields from $table->table $table->where $table->wheref  $table->group_by order by $table->order $table->order_direction ";




$_sql = "INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Key`) VALUES (?,?,?) ";

$db->prepare($_sql)->execute(array(
                                 gmdate('Y-m-d H:i:s'),
                                 $_export_data['tipo'],
                                 $user->id
                             ));

$download_key = $db->lastInsertId();


$export_data = array(
    'table'        => $_export_data['tipo'],
    'download_key' => $download_key,
    'output'       => $output,
    'ws_key'       => 'real_time.'.strtolower(DNS_ACCOUNT_CODE).'.'.$user->id,
    'sql_count'    => $table->sql_totals,
    'sql_data'     => $sql,
    'fetch_type'   => 'simple',
    'fields'       => $_export_data['fields'],
    'field_set'    => $field_set,
    'sql_table'    => "$table->table",
    'sql_where'    => "$table->where $table->wheref"

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


