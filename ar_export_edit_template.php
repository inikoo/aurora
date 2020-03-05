<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 13:57:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/new_fork.php';


$_data = prepare_values(
    $_REQUEST, array(

        'parent'      => array('type' => 'string'),
        'parent_key'  => array('type' => 'key'),
        'parent_code' => array('type' => 'string'),
        'objects'     => array('type' => 'string'),
        'type'        => array('type' => 'string'),
        'fields'      => array('type' => 'json array'),
        'metadata'    => array('type' => 'json array')
    )
);

$do_not_save_table_state = true;
$_data['nr']           = 1000000;
$_data['page']         = 1;


if ($_data['type'] == 'excel') {
    $output = 'xls';
} else {
    $output = $_data['type'];
}




$sql ="INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Type`,`Download Creator Key`) VALUES (?,?,?,?) ";

$db->prepare($sql)->execute(
array(
    gmdate('Y-m-d H:i:s'),
    $output,
    'User',
    $user->id
)
);


$download_key = $db->lastInsertId();




$export_data = array(
    'download_key'   => $download_key,
    'output'      => $output,
    'user_key'    => $user->id,
    'parent'      => $_data['parent'],
    'parent_key'  => $_data['parent_key'],
    'parent_code' => $_data['parent_code'],
    'objects'     => $_data['objects'],
    'fields'      => $_data['fields'],
    'metadata'    => $_data['metadata'],
);



new_housekeeping_fork(
    'au_export_edit_template', $export_data, $account->get('Account Code')
);


$response = array(
    'state'           => 200,
    'download_key' => $download_key,
    'txt'=>'<i class="fa background fa-spinner fa-spin"></i> '._('Queued').'</span>',


);
echo json_encode($response);
