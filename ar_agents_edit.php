<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 August 2018 at 21:10:37 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/supplier_order_functions.php';


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

    case 'update_item_problems':
        $data = prepare_values(
            $_REQUEST, array(
                         'transaction_key' => array('type' => 'key'),
                         'value'           => array('type' => 'json array')
                     )
        );

        update_item_problems($db, $data, $editor);
        break;

    case 'confirm_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        confirm_item($db, $data, $editor);
        break;
    case 'mark_as_received':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        mark_as_received($db, $data, $editor);
        break;
    case 'unmark_as_received':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        unmark_as_received($db, $data, $editor);
        break;
    case 'undo_mark_as_received':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        undo_mark_as_received($db, $data, $editor);
        break;

    case 'unconfirm_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        unconfirm_item($db, $data, $editor);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'tipo not found: '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function confirm_item($db, $data, $editor) {

    $sql = sprintf(
        'select POTF.`Purchase Order Key`,`Agent Supplier Purchase Order Key`,`Purchase Order Submitted Units`,`Supplier Part Unit Cost`,`Supplier Part Reference`,`Metadata`,`Agent Supplier Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Purchase Order Parent Key`,`Purchase Order Parent`,POTF.`Purchase Order Key`,`Purchase Order Transaction State` 
      from `Purchase Order Transaction Fact` POTF left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) 
      left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`) 
      where `Purchase Order Transaction Fact Key`=%d  ',
        $data['key']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

            $row['Currency Code']=$agent_supplier_purchase_order->get('Agent Supplier Purchase Order Currency Code');

            if ($row['Purchase Order Parent'] != 'Agent' or $row['Purchase Order Parent Key'] != $editor['Author Key']) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Forbidden')
                );
                echo json_encode($response);
                exit;
            }
            //print_r($row);
            //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'
            switch ($row['Purchase Order Transaction State']) {
                case 'Submitted':
                    $sql = sprintf(
                        'update  `Purchase Order Transaction Fact`  set  `Purchase Order Transaction State`="Confirmed" where `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Transaction Fact Key']
                    );

                    $db->exec($sql);

                    $purchase_order                = get_object('Purchase_Order', $row['Purchase Order Key']);
                    $agent_supplier_purchase_order = get_object('Agent_Supplier_Purchase_Order', $row['Agent Supplier Purchase Order Key']);
                    $purchase_order->update_totals();
                    $agent_supplier_purchase_order->update_totals();
                    $row['Purchase Order Transaction State'] = 'Confirmed';
                    break;

            }


            list(
                $back_operations, $forward_operations, $state) = get_agent_purchase_order_transaction_data($row);

            $response = array(
                'state'            => 200,
                'updated_metadata' => array(
                    'index'=>$agent_supplier_purchase_order->get('State Index'),
                    'class_html' => array(
                        'back_operations_'.$row['Purchase Order Transaction Fact Key']    => $back_operations,
                        'forward_operations_'.$row['Purchase Order Transaction Fact Key'] => $forward_operations,
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key']  => $state,
                        'Confirm_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Confirm Date or Percentage')
                    )
                )
            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

}


function unconfirm_item($db, $data, $editor) {


    $sql = sprintf(
        'select POTF.`Purchase Order Key`,`Agent Supplier Purchase Order Key`,`Purchase Order Submitted Units`,`Supplier Part Unit Cost`,`Supplier Part Reference`,`Metadata`,`Agent Supplier Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Purchase Order Parent Key`,`Purchase Order Parent`,POTF.`Purchase Order Key`,`Purchase Order Transaction State` 
      from `Purchase Order Transaction Fact` POTF left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) 
      left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`) 
      where `Purchase Order Transaction Fact Key`=%d  ',
        $data['key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {



            if ($row['Purchase Order Parent'] != 'Agent' or $row['Purchase Order Parent Key'] != $editor['Author Key']) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Forbidden')
                );
                echo json_encode($response);
                exit;
            }

            $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

            $row['Currency Code']=$agent_supplier_purchase_order->get('Agent Supplier Purchase Order Currency Code');

            //print_r($row);
            //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'
            switch ($row['Purchase Order Transaction State']) {
                case 'Confirmed':
                    $sql = sprintf(
                        'update  `Purchase Order Transaction Fact`  set  `Purchase Order Transaction State`="Submitted" where `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Transaction Fact Key']
                    );

                    $db->exec($sql);

                    $purchase_order                = get_object('Purchase_Order', $row['Purchase Order Key']);
                    $agent_supplier_purchase_order = get_object('Agent_Supplier_Purchase_Order', $row['Agent Supplier Purchase Order Key']);
                    $purchase_order->update_totals();
                    $agent_supplier_purchase_order->update_totals();
                    $row['Purchase Order Transaction State'] = 'Submitted';
                    break;

            }
            list(
                $back_operations, $forward_operations, $state

                ) = get_agent_purchase_order_transaction_data($row);

            $response = array(
                'state'            => 200,
                'updated_metadata' => array(
                    'index'=>$agent_supplier_purchase_order->get('State Index'),
                    'class_html' => array(
                        'back_operations_'.$row['Purchase Order Transaction Fact Key']    => $back_operations,
                        'forward_operations_'.$row['Purchase Order Transaction Fact Key'] => $forward_operations,
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key']  => $state,
                        'Confirm_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Confirm Date or Percentage')
                    )
                )
            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

}



function update_item_problems($db, $data, $editor) {


    $sql = sprintf(
        'select POTF.`Purchase Order Key`,`Agent Supplier Purchase Order Key`,`Purchase Order Submitted Units`,`Supplier Part Unit Cost`,`Supplier Part Reference`,`Metadata`,`Agent Supplier Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Purchase Order Parent Key`,`Purchase Order Parent`,POTF.`Purchase Order Key`,`Purchase Order Transaction State` 
      from `Purchase Order Transaction Fact` POTF left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) 
      left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`) 
      where `Purchase Order Transaction Fact Key`=%d  ',
        $data['transaction_key']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            if ($row['Purchase Order Parent'] != 'Agent' or $row['Purchase Order Parent Key'] != $editor['Author Key']) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Forbidden')
                );
                echo json_encode($response);
                exit;
            }

            $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

            $row['Currency Code']=$agent_supplier_purchase_order->get('Agent Supplier Purchase Order Currency Code');

            if ($row['Metadata'] == '') {
                $metadata = array();
            } else {
                $metadata = json_decode($row['Metadata'], true);
            }

            $metadata['item_problems'] = $data['value'];

            if ($data['value']['number_problem'] == 0) {


                if ($row['Purchase Order Transaction State'] == 'ProblemSupplier') {
                    $state = 'Submitted';
                } else {
                    $state = $row['Purchase Order Transaction State'];
                }


            } else {

                switch ($row['Purchase Order Transaction State']) {


                    //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'
                    case 'Confirmed':
                    case 'InProcess':
                    case 'ProblemSupplier':
                    case 'Submitted':


                        $state = 'ProblemSupplier';
                }
            }


            //print_r($row);
            //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'


            $sql = sprintf(
                'update  `Purchase Order Transaction Fact`  set  `Purchase Order Transaction State`=%s ,`Metadata`="%s" where `Purchase Order Transaction Fact Key`=%d ', prepare_mysql($state), addslashes(json_encode($metadata)),
                $row['Purchase Order Transaction Fact Key']
            );
           // print $sql;
            $db->exec($sql);

            $purchase_order                = get_object('Purchase_Order', $row['Purchase Order Key']);
            $agent_supplier_purchase_order = get_object('Agent_Supplier_Purchase_Order', $row['Agent Supplier Purchase Order Key']);
            $purchase_order->update_totals();
            $agent_supplier_purchase_order->update_totals();
            $row['Purchase Order Transaction State'] = $state;
            $row['Metadata'] = json_encode($metadata);

            list(
                $back_operations, $forward_operations, $state

                ) = get_agent_purchase_order_transaction_data($row);

            $response = array(
                'state'            => 200,
                'updated_metadata' => array(
                    'index'=>$agent_supplier_purchase_order->get('State Index'),
                    'class_html' => array(
                        'back_operations_'.$row['Purchase Order Transaction Fact Key']    => $back_operations,
                        'forward_operations_'.$row['Purchase Order Transaction Fact Key'] => $forward_operations,
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key']  => $state,
                        'Confirm_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Confirm Date or Percentage')
                    )
                )
            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

}


function mark_as_received($db, $data, $editor) {

    $sql = sprintf(
        'select POTF.`Purchase Order Key`,`Agent Supplier Purchase Order Key`,`Purchase Order Submitted Units`,`Supplier Part Unit Cost`,`Supplier Part Reference`,`Metadata`,`Agent Supplier Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Purchase Order Parent Key`,`Purchase Order Parent`,POTF.`Purchase Order Key`,`Purchase Order Transaction State` 
      from `Purchase Order Transaction Fact` POTF left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) 
      left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`) 
      where `Purchase Order Transaction Fact Key`=%d  ',
        $data['key']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

            $row['Currency Code']=$agent_supplier_purchase_order->get('Agent Supplier Purchase Order Currency Code');

            if ($row['Purchase Order Parent'] != 'Agent' or $row['Purchase Order Parent Key'] != $editor['Author Key']) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Forbidden')
                );
                echo json_encode($response);
                exit;
            }
            //print_r($row);
            //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'
            switch ($row['Purchase Order Transaction State']) {
                case 'Confirmed':
                    $sql = sprintf(
                        'update  `Purchase Order Transaction Fact`  set  `Purchase Order Transaction State`="ReceivedAgent" where `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Transaction Fact Key']
                    );

                    $db->exec($sql);

                    $purchase_order                = get_object('Purchase_Order', $row['Purchase Order Key']);
                    $agent_supplier_purchase_order = get_object('Agent_Supplier_Purchase_Order', $row['Agent Supplier Purchase Order Key']);
                    $purchase_order->update_totals();
                    $agent_supplier_purchase_order->update_totals();
                    $row['Purchase Order Transaction State'] = 'ReceivedAgent';
                    break;

            }


            list(
                $back_operations, $forward_operations, $state

                ) = get_agent_purchase_order_transaction_data($row);

            $response = array(
                'state'            => 200,
                'updated_metadata' => array(
                    'index'=>$agent_supplier_purchase_order->get('State Index'),
                    'class_html' => array(
                        'back_operations_'.$row['Purchase Order Transaction Fact Key']    => $back_operations,
                        'forward_operations_'.$row['Purchase Order Transaction Fact Key'] => $forward_operations,
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key']  => $state,
                        'Confirm_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Confirm Date or Percentage'),
                        'Received_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Received Date or Percentage')


                    )
                )
            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

}


function unmark_as_received($db, $data, $editor) {

    $sql = sprintf(
        'select POTF.`Purchase Order Key`,`Agent Supplier Purchase Order Key`,`Purchase Order Submitted Units`,`Supplier Part Unit Cost`,`Supplier Part Reference`,`Metadata`,`Agent Supplier Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Purchase Order Parent Key`,`Purchase Order Parent`,POTF.`Purchase Order Key`,`Purchase Order Transaction State` 
      from `Purchase Order Transaction Fact` POTF left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) 
      left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`) 
      where `Purchase Order Transaction Fact Key`=%d  ',
        $data['key']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

            $row['Currency Code']=$agent_supplier_purchase_order->get('Agent Supplier Purchase Order Currency Code');

            if ($row['Purchase Order Parent'] != 'Agent' or $row['Purchase Order Parent Key'] != $editor['Author Key']) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Forbidden')
                );
                echo json_encode($response);
                exit;
            }
            //print_r($row);
            //'InProcess','Submitted','ProblemSupplier','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'
            switch ($row['Purchase Order Transaction State']) {
                case 'ReceivedAgent':
                    $sql = sprintf(
                        'update  `Purchase Order Transaction Fact`  set  `Purchase Order Transaction State`="Confirmed" where `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Transaction Fact Key']
                    );

                    $db->exec($sql);

                    $purchase_order                = get_object('Purchase_Order', $row['Purchase Order Key']);
                    $agent_supplier_purchase_order = get_object('Agent_Supplier_Purchase_Order', $row['Agent Supplier Purchase Order Key']);
                    $purchase_order->update_totals();
                    $agent_supplier_purchase_order->update_totals();
                    $row['Purchase Order Transaction State'] = 'Confirmed';
                    break;

            }


            list(
                $back_operations, $forward_operations, $state

                ) = get_agent_purchase_order_transaction_data($row);

            $response = array(
                'state'            => 200,
                'updated_metadata' => array(
                    'index'=>$agent_supplier_purchase_order->get('State Index'),
                    'class_html' => array(
                        'back_operations_'.$row['Purchase Order Transaction Fact Key']    => $back_operations,
                        'forward_operations_'.$row['Purchase Order Transaction Fact Key'] => $forward_operations,
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key']  => $state,
                        'Confirm_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Confirm Date or Percentage'),
                        'Received_Date_or_Percentage'=>$agent_supplier_purchase_order->get('Received Date or Percentage')


                    )
                )
            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

}

?>
