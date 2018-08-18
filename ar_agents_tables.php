<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 20:13:55 GMT+7, Bangkok Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


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

    case 'client_order.items':
        client_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'client_order.suppliers':
        client_order_suppliers(get_table_parameters(), $db, $user, $account);
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


function client_order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'class.PurchaseOrder.php';
    $purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    $exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true" title="'._('Surplus').'" ></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true" title="'._('Optimal').'"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true" title="'._('Low').'"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true" title="'._('Critical').'"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true" title="'._('Out of stock').'"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true" title="'._('Error').'"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }

            $quantity = number($data['Purchase Order Quantity']);


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
            if ($data['Purchase Order Quantity'] > 0) {

                $subtotals .= $data['Purchase Order Quantity'] * $units_per_carton.'u. '.$data['Purchase Order Quantity'] * $skos_per_carton.'pkg. ';


                $amount = $data['Purchase Order Quantity'] * $units_per_carton * $data['Supplier Part Unit Cost'];

                $subtotals .= money(
                    $amount, $purchase_order->get('Purchase Order Currency Code')
                );

                if ($data['Supplier Part Currency Code'] != $account->get(
                        'Account Currency'
                    )) {
                    $subtotals .= ' <span class="">('.money(
                            $amount * $purchase_order->get(
                                'Purchase Order Currency Exchange'
                            ), $account->get('Account Currency')
                        ).')</span>';

                }

                if ($data['Part Package Weight'] > 0) {
                    $subtotals .= ' | '.weight(
                            $data['Part Package Weight'] * $data['Purchase Order Quantity'] * $data['Supplier Part Packages Per Carton']
                        );
                }
                if ($data['Supplier Part Carton CBM'] > 0) {
                    $subtotals .= ' | '.number(
                            $data['Purchase Order Quantity'] * $data['Supplier Part Carton CBM']
                        ).' m³';
                }
            }
            $subtotals .= '</span>';


            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$units_per_carton.'</b>', '<b>'.$skos_per_carton.'</b>'
            );

            if (!$data['Supplier Delivery Key']) {

                $delivery_qty = $data['Purchase Order Quantity'];

                $delivery_quantity = sprintf(
                    '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                    $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                );
            } else {
                $delivery_quantity = number(
                    $data['Supplier Delivery Quantity']
                );

            }


            $description = ($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '').$data['Supplier Part Description'];


            /*
                        $quantity = sprintf(
                            '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                        <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                        <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s">
                        <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',
                            $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
                        );
            */
            $quantity = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Quantity'] + 0, $data['Purchase Order Quantity'] + 0
            );

            if ($data['Part Main Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/image_root.php?id=%d&r=50x50" style="display: block;
  max-width:50px;
  max-height:50px;
  width: auto;
  height: auto;">', $data['Part Main Image Key']
                );
            } else {
                $image = '';
            }


            $table_data[] = array(

                'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
                'item_index'        => $data['Purchase Order Item Index'],
                'parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                'parent_type'       => strtolower($purchase_order->get('Purchase Order Parent')),
                'supplier_part_key' => (integer)$data['Supplier Part Key'],
                'supplier_key'      => (integer)$data['Supplier Key'],
                'checkbox'          => sprintf('<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),
                'reference'         => $data['Supplier Part Reference'],

                'image'     => $image,
                'reference' => sprintf(
                    '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" >%s</span>  ', $data['Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']
                ),

                'packing' => $packing,

                'packed_in'        => $data['Part Units Per Package'],
                'units_per_carton' => $units_per_carton,
                'sko_per_carton'   => $skos_per_carton,

                'description'       => $description,
                'quantity'          => $quantity,
                'delivery_quantity' => $delivery_quantity,
                'subtotals'         => $subtotals,
                'ordered'           => number($data['Purchase Order Quantity']),
                'supplier'          => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'unit_cost'         => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'qty_units'         => number($units_per_carton * $data['Purchase Order Quantity']),
                'qty_cartons'       => number($data['Purchase Order Quantity']),
                'amount'            => money($data['Supplier Part Unit Cost'] * $data['Purchase Order Quantity'] * $units_per_carton, $purchase_order->get('Purchase Order Currency Code')),

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function client_order_suppliers($_data, $db, $user, $account) {


    $rtext_label = 'supplier';

    //include_once 'class.PurchaseOrder.php';
    //$purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref  $group_by order by $order $order_direction limit $start_from,$number_results";



    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $table_data[] = array(

                'id' => (integer)$data['Supplier Key'],

                'code' => sprintf('<span class="link" onclick="change_view(\'order/%d/supplier/%d\')">%s</span>', $data['Purchase Order Key'], $data['Supplier Key'], $data['Supplier Code']),
                'name' => $data['Supplier Name'],
                'amount'            => money($data['amount'], $data['Purchase Order Currency Code']),
                'products'            => number($data['products']),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
