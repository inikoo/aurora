<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2018, Inikoo
 Created: 11 May 2018 at 11:01:33 CEST, Mijas Costa, Spain

 Version 2.0
*/

$account = get_object('Account', 1);


require 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';

if (empty($_REQUEST['action'])) {
    $response = log_api_key_access_failure(
        $db, $api_key_key, 'Fail_Operation', "Action missing"
    );
    echo json_encode($response);
    exit;
}

include_once 'api_stock_picking_common_actions.php';


switch ($_REQUEST['action']) {





    case 'get_delivery_note_from_public_id':

        if (!isset($_REQUEST['public_id'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'public_id needed'
            );
            echo json_encode($response);
            exit;
        }


        include_once 'class.DeliveryNote.php';

        $delivery_note = new DeliveryNote('public_id', $_REQUEST['public_id']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $delivery_note->data;

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;


    case 'unset_picker':
    case 'unset_packer':
        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }


        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }


        if ($_REQUEST['action'] == 'unset_picker') {

            $type = 'Picker';
        } else {
            $type = 'Packer';
        }

        $delivery_note->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => '',
                'Delivery Note Assigned '.$type.' Alias' => ''
            )
        );


        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;

    case 'set_picker':
    case 'set_packer':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }


        if (!isset($_REQUEST['staff_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['staff_key']) or $_REQUEST['staff_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid staff_key: '.$_REQUEST['staff_key']
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }

        $staff = get_object('staff', $_REQUEST['staff_key']);
        if (!$staff->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff not found'
            );
            echo json_encode($response);
            exit;

        }


        if ($_REQUEST['action'] == 'set_picker') {

            $type = 'Picker';
        } else {
            $type = 'Packer';
        }

        $delivery_note->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => $staff->id,
                'Delivery Note Assigned '.$type.' Alias' => $staff->get('Alias')
            )
        );


        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;

    case 'packed_done':
    case 'start_picking':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);


        switch ($_REQUEST['action']){
            case 'start_picking':
                $state='Picking';
                break;
            case 'packed_done':
                $state='Packed Done';
                break;
        }

        $delivery_note->update_state($state);

        if($delivery_note->error){
            $response = array(
                'state' => 'Error',
                'msg'  => $delivery_note->msg
            );
        }else{
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;




    case 'pick_item':


        if (!isset($_REQUEST['staff_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_REQUEST['itf_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'itf_key needed'
            );
            echo json_encode($response);
            exit;
        }


        if (!isset($_REQUEST['quantity'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'quantity needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['staff_key']) or $_REQUEST['staff_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid staff_key: '.$_REQUEST['staff_key']
            );
            echo json_encode($response);
            exit;
        }


        if (!is_numeric($_REQUEST['itf_key']) or $_REQUEST['itf_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid itf_key: '.$_REQUEST['itf_key']
            );
            echo json_encode($response);
            exit;
        }


        $qty = intval($_REQUEST['quantity']);


        if ($qty < 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid quantity: '.$_REQUEST['quantity'].'=>',
                $qty
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_picked_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'picker_key'      => $_REQUEST['staff_key'],

            )
        );

        if($delivery_note->error){

            $response = array(
                'state' => 'Error',
                'data'  => $delivery_note->msg
            );
        }else{
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;
    case 'pack_item':


        if (!isset($_REQUEST['staff_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_REQUEST['itf_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'itf_key needed'
            );
            echo json_encode($response);
            exit;
        }


        if (!isset($_REQUEST['quantity'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'quantity needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['staff_key']) or $_REQUEST['staff_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid staff_key: '.$_REQUEST['staff_key']
            );
            echo json_encode($response);
            exit;
        }


        if (!is_numeric($_REQUEST['itf_key']) or $_REQUEST['itf_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid itf_key: '.$_REQUEST['itf_key']
            );
            echo json_encode($response);
            exit;
        }


        $qty = intval($_REQUEST['quantity']);


        if ($qty < 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid quantity: '.$_REQUEST['quantity'].'=>',
                $qty
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_packed_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'packer_key'      => $_REQUEST['staff_key'],

            )
        );


        if($delivery_note->error){

            $response = array(
                'state' => 'Error',
                'data'  => $delivery_note->msg
            );
        }else{
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;

    case 'set_as_out_of_stock_item':

        include 'api.includes/parse_arguments_dn_item_operations.inc.php';

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_picked_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'picker_key'      => $_REQUEST['staff_key'],

            )
        );

        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;

    case 'get_delivery_note_items':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        include_once 'class.PartLocation.php';
        $items = array();

        $sql =
            sprintf('select * from `Inventory Transaction Fact` ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)  where `Delivery Note Key`=%d ', $_REQUEST['delivery_note_key']);

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $part_location_data = array();
                $sql                = sprintf('select * from `Part Location Dimension`  where `Part SKU`=%d and `Location Key`=%d ', $row['Part SKU'], $row['Location Key']);

                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $part_location_data = $row2;
                    }
                }


                $part_location = new PartLocation($row['Part SKU'], $row['Location Key']);

                $items[] = array(
                    'item'          => $row,
                    'part_location' => $part_location_data
                );
            }
        } else {
            print_r($error_info = $db->errorInfo());
            //print "$sql\n";
            exit;
        }


        $response = array(
            'state' => 'OK',
            'data'  => $items
        );
        echo json_encode($response);
        exit;
        break;




    default:


        $response = array(
            'state' => 'Error',
            'msg'   => "Action ".$_REQUEST['action'].' not found'
        );
        echo json_encode($response);
        exit;


        //$response = log_api_key_access_failure($db, $api_key_key, 'Fail_Operation', "Action ".$_REQUEST['action'].' not found');
        echo json_encode($response);
        exit;

}




?>
