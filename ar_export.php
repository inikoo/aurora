<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2015 at 15:00:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

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
$extra_where='';

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

if($_data['tipo']=='customers'){
    exit();
}

$dont_save_table_state = true;
$_data['nr']           = 1000000;
$_data['page']         = 1;

$_data['type'] = strtolower($_data['type']);


include 'conf/export_fields.php';

if ($_data['type'] == 'excel') {
    $output = 'xls';
} else {
    $output = $_data['type'];
}


if ($_data['tipo'] == 'stock.history.day') {


    $_sql = "INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Key`) VALUES (?,?,?) ";

    $db->prepare($_sql)->execute(
        array(
            gmdate('Y-m-d H:i:s'),
            'inventory_stock_history_day',
            $user->id
        )
    );


    $download_key = $db->lastInsertId();

    unset($_data['parameters']['view']);
    unset($_data['parameters']['rpp_options']);

    $export_data = array(
        'tipo'         => 'ISH_day',
        'download_key' => $download_key,
        'output'       => $output,
        'ws_key'       => 'real_time.'.strtolower(DNS_ACCOUNT_CODE).'.'.$user->id,
        'parameters'   => $_data['parameters'],

        'fields'    => $_data['fields'],
        'field_set' => get_export_fields('inventory_stock_history_day'),
    );


    new_housekeeping_fork(
        'au_export_from_elastic_search', $export_data, DNS_ACCOUNT_CODE
    );


    $response = array(
        'state'        => 200,
        'download_key' => $download_key,
        'txt'          => '<i class="fa background fa-spinner fa-spin"></i> '._('Queued').'</span>',

        'type' => $_data['type'],
        'tipo' => $_data['tipo']
    );
    echo json_encode($response);
    exit;


}
elseif ($_data['tipo'] == 'timeserie_records') {

    $timeseries = get_object('Timeseries',$_data['parameters']['parent_key']);

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

}
else {


    if ($_data['tipo'] == 'billingregion_taxcategory.invoices' or $_data['tipo'] == 'billingregion_taxcategory.refunds') {
        $_tipo = 'invoices';
    } elseif ($_data['tipo'] == 'in_process_parts' or $_data['tipo'] == 'active_parts' or $_data['tipo'] == 'discontinuing_parts' or $_data['tipo'] == 'discontinued_parts') {
        $_tipo = 'parts';

        if ($_data['tipo'] == 'active_parts') {
            $extra_where = ' and `Part Status`="In Use"';

        } elseif ( $_data['tipo'] == 'discontinuing_parts') {
            $extra_where = ' and `Part Status`="Discontinuing"';

        } elseif ( $_data['tipo'] == 'discontinued_parts') {
            $extra_where = ' and `Part Status`="Not In Use"';

        } elseif ($_data['tipo'] == 'in_process_parts') {
            $extra_where = ' and `Part Status`="In Process"';

        }


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
    } elseif ($_data['tipo'] == 'job_order.items') {
        $_tipo = 'supplier.order.items';
    }  elseif ($_data['tipo'] == 'part_families') {
        $_tipo = 'part_categories';
    } elseif ($_data['tipo'] == 'asset_customers') {
        $_tipo = 'customers';
    } elseif ($_data['tipo'] == 'sent_emails') {
        //print_r($_data);
        // todo parse request for prospects (ise state to find out)
        $_tipo = 'customer_sent_emails';
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

/** @var string $table */
/** @var string $where */
/** @var string $wheref */
/** @var string $order */
/** @var string $order_direction */
/** @var string $sql_totals */


$sql = "select $fields from $table $where $extra_where $wheref  $group_by order by $order $order_direction ";



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
    'ws_key'       => 'real_time.'.strtolower(DNS_ACCOUNT_CODE).'.'.$user->id,
    'sql_count'    => $sql_totals,
    'sql_data'     => $sql,
    'fetch_type'   => 'simple',
    'fields'       => $_export_data['fields'],
    'field_set'    => $field_set,
    'sql_table'    => "$table",
    'sql_where'    => "$where $wheref"

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


