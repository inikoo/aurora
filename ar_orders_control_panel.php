<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  20 May 2020  21:52::03  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/
require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/new_fork.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'pdf_picking_aids':

        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_notes_keys' => array('type' => 'json array'),
                         'type'                => array('type' => 'string'),
                     )
        );

        $_sql = "INSERT INTO `Download Dimension` (`Download Date`,`Download Type`,`Download Creator Key`) VALUES (?,?,?) ";

        $db->prepare($_sql)->execute(
            array(
                gmdate('Y-m-d H:i:s'),
                'picking_aids',
                $user->id
            )
        );
        $download_key = $db->query("SELECT LAST_INSERT_ID()")->fetchColumn();


        $data['download_key'] = $download_key;
        $data['user_key']     = $user->id;

        new_housekeeping_fork(
            'au_orders_control_panel', $data, DNS_ACCOUNT_CODE
        );

        $response = array(
            'state' => 200,
            'download_key' => $download_key,
            'txt'          => '<i class="fa background fa-spinner fa-spin"></i> '._('Queued').'</span>',

        );
        echo json_encode($response);


}