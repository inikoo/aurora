<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2018, Inikoo
 Created: 11 May 2018 at 11:01:33 CEST, Mijas Costa, Spain

 Version 2.0
*/

$account = get_object('Account', 1);


$smarty               = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

if (empty($_REQUEST['action'])) {
    $response = log_api_key_access_failure(
        $db, $api_key_key, 'Fail_Operation', "Action missing"
    );
    echo json_encode($response);
    exit;
}

include_once 'api_stock_picking_common_actions.php';


switch ($_REQUEST['action']) {

    case 'initialize':

        $groups=preg_split('/,/',$user->get('User Groups') );

        $type='Invalid';
        if(in_array(17,$groups)){
            $type='Supervisor';
        }elseif(in_array(11,$groups)){
            $type='Worker';
        }



        $data=array(
            'account_code'=>$account->get('Code'),
            'account_name'=>$account->get('Name'),
            'locale'=>$account->get('Account Locale'),
            'worker_user_id'=>$user->id,
            'worker_alias'=>$user->get('Alias'),
            //'worker_type'=>$type
            'worker_type'=>'Supervisor'
        );

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);

        break;
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

    case 'set_number_of_boxes':
    case 'set_weight':

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


        if (!isset($_REQUEST['value'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'value needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['value']) or $_REQUEST['value'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'value must be a positive number: '.$_REQUEST['value']
            );
            echo json_encode($response);
            exit;
        }

        if ($_REQUEST['action'] == 'set_number_of_boxes') {
            if (!is_numeric($_REQUEST['value']) or $_REQUEST['value'] <= 0) {
                $response = array(
                    'state' => 'Error',
                    'msg'   => 'value must be an integer: '.$_REQUEST['value']
                );
                echo json_encode($response);
                exit;
            }
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


        if ($_REQUEST['action'] == 'set_number_of_boxes') {

            $field = 'Delivery Note Number Parcels';
        } else {
            $type = 'Delivery Note Weight';
        }

        $delivery_note->update(
            array(
                $field => $value,
            )
        );


        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;


    case 'close_boxes':
    case 'open_boxes':
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


        switch ($_REQUEST['action']) {
            case 'start_picking':
                $state = 'Picking';
                break;
            case 'close_boxes':
                $state = 'Packed Done';
                break;
            case 'open_boxes':
                $state = 'Undo Packed Done';
                break;

        }

        $delivery_note->update_state($state);

        if ($delivery_note->error) {
            $response = array(
                'state' => 'Error',
                'msg'   => $delivery_note->msg
            );
        } else {
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;


    case 'update_delivery_note_item_status':


        include 'api.includes/parse_arguments_dn_item_operations.inc.php';

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);


        $response = array(
            'state' => 'OK',
        );


        echo json_encode($response);
        exit;
        break;


    case 'pick_item':


        include 'api.includes/parse_arguments_dn_item_operations.inc.php';
        include 'api.includes/parse_arguments_dn_item_operations_qty.inc.php';

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_picked_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'picker_key'      => $user->get_staff_key()

            )
        );

        if ($delivery_note->error) {

            $response = array(
                'state' => 'Error',
                'data'  => $delivery_note->msg
            );
        } else {
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;
    case 'pack_item':


        include 'api.includes/parse_arguments_dn_item_operations.inc.php';



        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_packed_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'packer_key'      => $_REQUEST['staff_key'],

            )
        );


        if ($delivery_note->error) {

            $response = array(
                'state' => 'Error',
                'data'  => $delivery_note->msg
            );
        } else {
            $response = array(
                'state' => 'OK',
                'data'  => $delivery_note->get_update_metadata()
            );
        }

        echo json_encode($response);
        exit;
        break;

    case 'set_as_not_picked_item':

        include 'api.includes/parse_arguments_dn_item_operations.inc.php';

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);

        $delivery_note->update_item_not_picked_quantity(
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
            sprintf('select `Part Reference`,`Inventory Transaction Key`,P.`Part SKU`,`Part Package Description`,`Part Package Weight`,`Required`+`Given` as Required , `Picked`,`Out of Stock`,`Waiting`,`No Authorized`,`Not Found`,`No Picked Other`,  L.`Location Key`,`Part SKO Barcode` from `Inventory Transaction Fact` ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)  where `Delivery Note Key`=%d ', $_REQUEST['delivery_note_key']);

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $part_location_data = array();
                $sql                = sprintf('select `Quantity On Hand` from `Part Location Dimension`  where `Part SKU`=%d and `Location Key`=%d ', $row['Part SKU'], $row['Location Key']);

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
        }


        $response = array(
            'state' => 'OK',
            'data'  => $items
        );
        echo json_encode($response);
        exit;
        break;


    case 'get_pending_deliveries_stats':


        break;

    case 'get_deliveries_ready_to_be_picked':

        $response=get_deliveries($db,'Ready to be Picked');
        echo json_encode($response);
        exit;
        break;


    case 'get_staff':

        $staff_data=array();

        $sql="select `Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Main Image Key`,(select group_concat(`Role Code` SEPARATOR ',') from `Staff Role Bridge` SRB where SRB.`Staff Key`=S.`Staff Key`  ) as `Staff Roles`      from `Staff Dimension` S where  `Staff Type`='Employee' and `Staff Currently Working`='Yes'   ";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $staff_data[]=$row;
        }

        $response = array(
            'state' => 'OK',
            'data'  => $staff_data
        );
        echo json_encode($response);
        exit;
        break;
    case 'get_pickers':

        $staff_data=array();

        $sql="select S.`Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Main Image Key`  from `Staff Dimension` S   left join `Staff Role Bridge`  SRB on (S.`Staff Key`=SRB.`Staff Key`) where  `Role Code`='Pick'  `Staff Type`='Employee' and `Staff Currently Working`='Yes'   ";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $staff_data[]=$row;
        }

        $response = array(
            'state' => 'OK',
            'data'  => $staff_data
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


function get_deliveries($db,$state){

    $sql = 'select `Delivery Note Key`,`Delivery Note Customer Key`,`Delivery Note Type`,`Delivery Note Date Created`,`Delivery Note Estimated Weight`,`Delivery Note Store Key`,`Delivery Note ID`,`Delivery Note Customer Name`,`Store Code`,`Store Name`,`Delivery Note Number Ordered Parts` 
        from `Delivery Note Dimension` D left join `Store Dimension` on (`Store Key`=`Delivery Note Store Key`) where `Delivery Note State`=?
        ';

    $deliveries=array();

    $stmt = $db->prepare($sql);
    $stmt->execute([$state]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $deliveries[] = $row;
    }


     return array(
        'state' => 'OK',
        'data'  => $deliveries
    );

}
