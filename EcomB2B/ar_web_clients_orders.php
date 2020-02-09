<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sat 26 Oct 2019 01:52:41 +0800 MYT MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
require_once 'utils/table_functions.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'clients_orders':


        $_data                             = get_table_parameters();


        if($_data['parameters']['parent']=='customer'){
            $_data['parameters']['parent_key'] = $customer->id;
        }elseif($_data['parameters']['parent']=='client'){

            $sql="select `Customer Client Key` from `Customer Client Dimension` where `Customer Client Key`=?  and  `Customer Client Customer Key`=? ";
            $stmt = $db->prepare($sql);
            $stmt->execute([$_data['parameters']['parent_key'],$customer->id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $_data['parameters']['parent_key'] = $row['Customer Client Key'];
            }else{
                exit('Forbidden A123');
            }



        }

        clients_orders($_data, $db);


        break;

}


function clients_orders($_data, $db) {




    $rtext_label = 'order';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Order State']) {
                case('InBasket'):
                    $state = _('In Basket');
                    break;
                case('InProcess'):
                    $state = _('Submitted');
                    break;
                case('InWarehouse'):
                    $state = _('In Warehouse');
                    break;
                case('PackedDone'):
                    $state = _('Packed & Closed');
                    break;
                case('Dispatch Approved'):
                    $state = _('Dispatch Approved');
                    break;
                case('Dispatched'):
                    $state = _('Dispatched');
                    break;
                case('Cancelled'):
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Order State'];

            }


            $adata[] = array(
                'id' => (integer)$data['Order Key'],

                'public_id' => sprintf('<a href="client_order.sys?id=%d">%s</a>',  $data['Order Key'], $data['Order Public ID']),
                'state'     => $state,

                'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
                'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
                'customer'       => sprintf('<a href="client.sys?id=%d">%s</a>',  $data['Order Customer Client Key'], $data['Customer Client Name']),
               // 'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
               // 'payment_state'  => get_order_formatted_payment_state($data),
                'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
               // 'margin'         => sprintf('<span title="%s: %s">%s</span>', _('Profit'), money($data['Order Profit Amount'], $data['Order Currency']), percentage($data['Order Margin'], 1)),


            );


        }

    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

