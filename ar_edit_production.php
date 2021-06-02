<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 February 2017 at 00:47:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';


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


    case 'job_order_forward_item_action':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'      => array('type' => 'key'),
                         'action'   => array('type' => 'string'),
                         'qty'      => array('type' => 'numeric'),
                         'qty_type' => array('type' => 'string'),

                     )
        );
        job_order_forward_item_action($data, $db, $editor);
        break;
    case 'job_order_backward_item_action':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'    => array('type' => 'key'),
                         'action' => array('type' => 'string'),

                     )
        );
        job_order_backward_item_action($data, $db, $editor);
        break;
    case 'set_operator':
        $data = prepare_values(
            $_REQUEST, array(
                         'purchase_order_key'    => array('type' => 'key'),
                         'staff_key' => array('type' => 'key'),

                     )
        );
        set_operator($data, $editor);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function set_operator($data, $editor){

    $purchase_order         = get_object('Purchase_Order', $data['purchase_order_key']);
    $purchase_order->editor = $editor;
    $purchase_order->update(['Purchase Order Operator Key'=>$data['staff_key']]);

    $response = array(
        'state'            => 200,
        'update_metadata'  => $purchase_order->get_update_metadata(),
        'operator_key' => $purchase_order->get('Purchase Order Operator Key')

    );
    echo json_encode($response);

}

function job_order_forward_item_action($data, $db, $editor) {

    include_once 'utils/supplier_order_functions.php';

    $sql  =
        "select `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Transaction State`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton` from  `Purchase Order Transaction Fact` where `Purchase Order Transaction Fact Key`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['key']
        )
    );
    if ($row = $stmt->fetch()) {
        $purchase_order         = get_object('Purchase_Order', $row['Purchase Order Key']);
        $purchase_order->editor = $editor;


        switch ($data['qty_type']) {
            case 'sko':
                $qty = $data['qty'] * $row['Purchase Order Submitted Units Per SKO'];
                break;
            case 'carton':
                $qty = $data['qty'] * $row['Purchase Order Submitted Units Per SKO'] * $row['Purchase Order Submitted SKOs Per Carton'];
                break;
            default:
                $qty = $data['qty'];
        }


        switch ($data['action']) {

            case 'finish_manufacture':
                if ($row['Purchase Order Transaction State'] == 'Confirmed') {
                    $purchase_order->update_purchase_order_transaction($row['Purchase Order Transaction Fact Key'], 'Manufactured', $qty);

                } else {
                    $response = array(
                        'state' => 405,
                        'resp'  => 'Can not do this '.$row['Purchase Order Transaction State']
                    );
                    echo json_encode($response);
                    exit;
                }


                break;
            case 'qc_check':
                if ($row['Purchase Order Transaction State'] == 'Manufactured') {
                    $purchase_order->update_purchase_order_transaction($row['Purchase Order Transaction Fact Key'], 'QC_Pass', $qty);

                } else {
                    $response = array(
                        'state' => 405,
                        'resp'  => 'Can not do this '.$row['Purchase Order Transaction State']
                    );
                    echo json_encode($response);
                    exit;
                }
            default:
                break;
        }



    }


    $transaction_data=[];
    $sql  =
        "select `Supplier Delivery Public ID`,`Supplier Delivery Key`,`Supplier Key`,`Supplier Delivery Parent`,`Purchase Order Submitted Cancelled Units`,`Purchase Order Submitted Units`,`Purchase Order Manufactured Units`,`Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Transaction State`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton` from  
            `Purchase Order Transaction Fact` POTF left join `Supplier Delivery Dimension` SD on (POTF.`Supplier Delivery Key`=SD.`Supplier Delivery Key`)
            where `Purchase Order Transaction Fact Key`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['key']
        )
    );
    if ($row = $stmt->fetch()) {
        list($operations_units, $operations_skos, $operations_cartons) = get_job_order_operations($row);
        list($items_qty, $ordered_units, $ordered_skos, $ordered_cartons) = get_purchase_order_transaction_ordered_data($row);

        $transaction_data = [

            'state'              => '<span class="transaction_state_'.$row['Purchase Order Transaction Fact Key'].'">'.get_job_order_transaction_data($row).'</span>',
            'operations_units'   => $operations_units,
            'operations_skos'    => $operations_skos,
            'operations_cartons' => $operations_cartons,
            'ordered_units'      => $ordered_units,
            'ordered_skos'       => $ordered_skos,
            'ordered_cartons'    => $ordered_cartons,
            'items_qty'          => $items_qty,

        ];
    }

    $response = array(
        'state'            => 200,
        'update_metadata'  => $purchase_order->get_update_metadata(),
        'transaction_data' => $transaction_data

    );
    echo json_encode($response);
    exit;


}


function job_order_backward_item_action($data, $db, $editor) {


    $sql  =
        "select `Purchase Order Submitted Units`,`Purchase Order Manufactured Units`,`Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Transaction State`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton` from  `Purchase Order Transaction Fact` where `Purchase Order Transaction Fact Key`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['key']
        )
    );
    if ($row = $stmt->fetch()) {
        $purchase_order         = get_object('Purchase_Order', $row['Purchase Order Key']);
        $purchase_order->editor = $editor;


        switch ($data['action']) {

            case 'undo_manufactured':

                if ($row['Purchase Order Transaction State'] == 'Manufactured') {
                    $purchase_order->update_purchase_order_transaction($row['Purchase Order Transaction Fact Key'], 'undo_manufactured', $row['Purchase Order Submitted Units']);

                } else {
                    $response = array(
                        'state' => 405,
                        'resp'  => 'Can not do this '.$row['Purchase Order Transaction State']
                    );
                    echo json_encode($response);
                    exit;
                }


                break;
            case 'undo_qc_pass':

                if ($row['Purchase Order Transaction State'] == 'QC_Pass') {
                    $purchase_order->update_purchase_order_transaction($row['Purchase Order Transaction Fact Key'], 'Manufactured', $row['Purchase Order Manufactured Units']);

                } else {
                    $response = array(
                        'state' => 405,
                        'resp'  => 'Can not do this '.$row['Purchase Order Transaction State']
                    );
                    echo json_encode($response);
                    exit;
                }


                break;
            default:
                break;
        }


        $response = array(
            'state'           => 200,
            'update_metadata' => $purchase_order->get_update_metadata(),

        );
        echo json_encode($response);
        exit;
    }


}

