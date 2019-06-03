<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 12 March 2018 at 22:07:23 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/

$account = get_object('Account', 1);


if (empty($_REQUEST['action'])) {
    $response = log_api_key_access_failure(
        $db, $api_key_key, 'Fail_Operation', "Action missing"
    );
    echo json_encode($response);
    exit;
}

include_once 'api_stock_picking_common_actions.php';


switch ($_REQUEST['action']) {

    case 'update_part_symbol':

        if (!isset($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        $part = get_object('part', $_REQUEST['part_sku']);
        $part->editor = $editor;

        if (!$part->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found ('.$_REQUEST['part_sku'].')  '
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['symbol'])) {


            $response = array(
                'state' => 'Error',
                'msg'   => 'symbol needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!in_array(
            $_REQUEST['symbol'], array(
                                   'none',
                                   'star',
                                   'skull',
                                   'radioactive',
                                   'peace',
                                   'gear',
                                   'love'
                               )
        )) {


            $response = array(
                'state' => 'Error',
                'msg'   => 'Invalid symbol value ('.$_REQUEST['symbol'].')  '
            );
            echo json_encode($response);
            exit;
        }


        $part->update(
            array(
                'Part Symbol' => $_REQUEST['symbol']
            )
        );

        $response = array(
            'state' => 'OK',
            'data'  => $part->get('Part Symbol')
        );
        echo json_encode($response);
        exit;
        break;


    default:


        $response = array(
            'state' => 'Error',
            'msg'   => "Action ".$_REQUEST['action'].' not found',
        );
        echo json_encode($response);
        exit;



}


