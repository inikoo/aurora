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
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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

    case 'create_delivery_note':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),

                         'key' => array('type' => 'key'),

                     )
        );
        create_delivery_note($data, $editor, $smarty, $db,$account);
        break;

    case 'set_state':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),

                         'key' => array('type' => 'key'),
                         'value'         => array('type' => 'string'),

                     )
        );
        set_state($data, $editor, $smarty, $db);
        break;
    case 'set_picker':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Picker',$data, $editor, $smarty, $db);
        break;

    case 'set_packer':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Packer',$data, $editor, $smarty, $db);
        break;
    case 'edit_item_in_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'field'             => array('type' => 'string'),
                         'parent'            => array('type' => 'string'),
                         'parent_key'        => array('type' => 'key'),
                         'item_key'          => array('type' => 'key'),
                         'item_historic_key' => array('type' => 'key','optional'=>true),
                         'transaction_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'picker_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'packer_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'qty'               => array('type' => 'numeric'),

                     )
        );
        edit_item_in_order($account, $db, $user, $editor, $data, $smarty);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function edit_item_in_order($account, $db, $user, $editor, $data, $smarty) {

    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $transaction_data = $parent->update_item($data);

    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => $parent->msg
        );
    } else {

        $response = array(
            'state'            => 200,
            'transaction_data' => $transaction_data,
            'metadata'         => $parent->get_update_metadata()
        );
    }
    echo json_encode($response);

}

function set_order_handler($type,$data, $editor, $smarty, $db) {


    $dn         = get_object('delivery_note', $data['delivery_note_key']);
    $dn->editor = $editor;

    $staff = get_object('staff', $data['staff_key']);

    if ($staff->id) {
        $dn->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => $staff->id,
                'Delivery Note Assigned '.$type.' Alias' => $staff->get('Alias')
            )
        );
        $response = array(
            'state'       => 200,
            'staff_alias' => $staff->get('Alias'),
            'staff_key'   => $staff->id
        );
    }else{
        $response = array(
            'state'       => 400,
            'msg' => 'Staff not found'
        );
    }


    echo json_encode($response);

}

function set_state($data, $editor, $smarty, $db){


    $object        = get_object($data['object'], $data['key']);
    $object->editor = $editor;



    $object->set_state($data['value']);



    $response = array(
        'state'       => 200,
        'metadata'         => $object->get_update_metadata()
    );

    echo json_encode($response);

}

function create_delivery_note($data, $editor, $smarty, $db,$account){


    $order        = get_object('order', $data['key']);
    $order->editor = $editor;





    $dn=$order->send_to_warehouse();






    if (!$order->error) {
        include 'utils/new_fork.php';
        $msg=new_housekeeping_fork('send_to_warehouse',array('type'=>'send_to_warehouse','delivery_note_key'=>$dn->id),$account->get('Account Code'));

        $response=array(
            'state'=>200,
            'order_key'=>$order->id,
            'dn_key'=>$dn->id,
          //  'dispatch_state'=>get_order_formated_dispatch_state($order->get('Order Current Dispatch State'),$order->id),
           // 'operations'=>get_orders_operations($order->data,$user)

        );

    } else {

        $response=array('state'=>400,'msg'=>$order->msg,'number_items'=>$order->get('Order Number Items'),'order_key'=>$order->id);


    }




    echo json_encode($response);

}

?>
