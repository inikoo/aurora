<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2017 at 13:57:01 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/parse_natural_language.php';
include_once 'utils/data_entry_picking_aid.class.php';

$data = prepare_values(
    $_REQUEST, array(
                 'delivery_note_key' => array('type' => 'key'),
                 'order_key'         => array('type' => 'key'),
                 'level'             => array('type' => 'numeric'),
                 'items'             => array('type' => 'json array'),
                 'fields'            => array('type' => 'json array'),
             )
);

$data_entry_picking_aid = new data_entry_picking_aid($data, $editor, $db, $account);
$validation = $data_entry_picking_aid->parse_input_data();
if (!$validation['valid']) {
    echo json_encode($validation['response']);
    exit;
}

$data_entry_picking_aid->update_delivery_note();
$data_entry_picking_aid->process_transactions();
$data_entry_picking_aid->finish_packing();

$response = array(
    'state' => 200
);

echo json_encode($response);