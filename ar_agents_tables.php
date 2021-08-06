<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 20:13:55 GMT+7, Bangkok Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

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
    case 'agent_client_orders':
        agent_client_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'client_order.items':
        client_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'client_order.suppliers':
        client_order_suppliers(get_table_parameters(), $db, $user, $account);
        break;
    case 'agent_supplier_order.items':
        agent_supplier_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'agent_deliveries':
        agent_deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'agent.items_in_warehouse':
        agent_items_in_warehouse(get_table_parameters(), $db, $user, $account);
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


function agent_client_orders($_data, $db, $user) {

    if ($user->get('User Type') != 'Agent') {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }
    $_data['parameters']['agent_key'] = $user->get('User Parent Key');


    $rtext_label = 'client order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Purchase Order State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;

                case 'Submitted':
                    $state = sprintf('%s', _('Submitted'));
                    break;
                case 'Confirmed':
                    $state = sprintf('%s', _('Confirmed'));
                    break;
                case 'In Warehouse':
                    $state = sprintf('%s', _('In Warehouse'));
                    break;
                case 'Done':
                    $state = sprintf('%s', _('Done'));
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Purchase Order State'];
                    break;
            }

            $table_data[] = array(
                'id' => (integer)$data['Purchase Order Key'],


                'public_id'    => sprintf('<span class="link" onclick="change_view(\'client_order/%d\')">%s</span>', $data['Purchase Order Key'], $data['Purchase Order Public ID']),
                'date'         => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'        => $state,
                'items'        => number($data['Purchase Order Number Items']),
                'suppliers'    => number($data['Purchase Order Number Suppliers']),
                // 'problems'=>number('Purchase Order Number Items'),
                'total_amount' => money(
                    $data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']
                )


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


function client_order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'class.PurchaseOrder.php';
    $purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    //$exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            /*
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

            */

            //$units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            //$skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
            if ($data['Purchase Order Submitted Units'] > 0) {

                $subtotals .= $data['Purchase Order Submitted Units'].'u. '.$data['Purchase Order Submitted Units'] / $data['Part Units Per Package'].'pkg. ';


                $amount = $data['Purchase Order Submitted Units'] * $data['Supplier Part Unit Cost'];

                $subtotals .= money(
                    $amount, $purchase_order->get('Purchase Order Currency Code')
                );

                if ($data['Supplier Part Currency Code'] != $account->get(
                        'Account Currency'
                    )) {
                    $subtotals .= ' <span >('.money(
                            $amount * $purchase_order->get(
                                'Purchase Order Currency Exchange'
                            ), $account->get('Account Currency')
                        ).')</span>';

                }

                if ($data['Part Package Weight'] > 0) {
                    $subtotals .= ' | '.weight(
                            $data['Part Package Weight'] * $data['Purchase Order Submitted Units'] / $data['Part Units Per Package']
                        );
                }
                if ($data['Supplier Part Carton CBM'] > 0) {
                    $subtotals .= ' | '.number(
                            $data['Purchase Order Submitted Units'] * $data['Supplier Part Carton CBM'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']
                        ).' m³';
                }
            }
            $subtotals .= '</span>';


            $amount = money(
                $amount, $purchase_order->get('Purchase Order Currency Code')
            );


            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</b>', '<b>'.$data['Supplier Part Packages Per Carton'].'</b>'
            );


            /*

            if (!$data['Supplier Delivery Key']) {

                $delivery_qty = $data['Purchase Order Submitted Units'];

                $delivery_quantity = sprintf(
                    '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                    $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                );
            } else {
                $delivery_quantity = number(
                    $data['Supplier Delivery Units']
                );

            }

            */


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
                '<span    data-settings=\'{"field": "Purchase Order Submitted Units", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Submitted Units'] + 0, $data['Purchase Order Submitted Units'] + 0
            );

            if ($data['Part Main Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/image.php?id=%d&s=50x50" style="display: block;
  max-width:50px;
  max-height:50px;
  width: auto;
  height: auto;">', $data['Part Main Image Key']
                );
            } else {
                $image = '';
            }


            $xhtml_materials = '';
            if ($data['Part Materials'] != '') {


                $materials_data = json_decode($data['Part Materials'], true);


                foreach ($materials_data as $material_data) {
                    if (!array_key_exists('id', $material_data)) {
                        continue;
                    }

                    if ($material_data['may_contain'] == 'Yes') {
                        $may_contain_tag = '±';
                    } else {
                        $may_contain_tag = '';
                    }


                    $xhtml_materials .= sprintf(
                        ', %s%s', $may_contain_tag, $material_data['name']
                    );


                    if ($material_data['ratio'] > 0) {
                        $xhtml_materials .= sprintf(
                            ' (%s)', percentage($material_data['ratio'], 1)
                        );
                    }
                }

                $xhtml_materials = ucfirst(
                    preg_replace('/^\, /', '', $xhtml_materials)
                );

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
                'units_per_carton' => $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'],
                'sko_per_carton'   => $data['Supplier Part Packages Per Carton'],

                'description' => $description,
                'quantity'    => $quantity,
                //   'delivery_quantity' => $delivery_quantity,
                'subtotals'   => $subtotals,
                'ordered'     => number($data['Purchase Order Submitted Units']),
                'supplier'    => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'unit_cost'   => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'qty_units'   => number($data['Purchase Order Submitted Units']),
                'qty_cartons' => number($data['Purchase Order Submitted Units'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']),
                'amount'      => $amount,

                'barcode'     => $data['Part Barcode Number'],
                'sko_barcode' => $data['Part SKO Barcode'],
                'materials'   => $xhtml_materials

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

    $rtext_label = 'order';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Agent Supplier Purchase Order State']) {
                case 'InProcess':
                    $state = _('In Process');
                    break;
                case 'Confirmed':
                    $state = _('Confirmed');
                    break;
                case 'InWarehouse':
                    $state = _('In warehouse');
                    break;
                case 'InDelivery':
                    $state = _('In delivery');
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;

                default:
                    return $this->data['Agent Supplier Purchase Order State'];
                    break;
            }


            $table_data[] = array(
                'id'       => (integer)$data['Agent Supplier Purchase Order Key'],
                'order'    => sprintf('<span class="link" onclick="change_view(\'client_order/%d/%d\')">%s</span>', $data['Agent Supplier Purchase Order Purchase Order Key'], $data['Agent Supplier Purchase Order Key'], $data['Agent Supplier Purchase Order Public ID']),
                'supplier' => sprintf(
                    '<span class="link" onclick="change_view(\'supplier/%d\')" title="%s">%s</span>', $data['Agent Supplier Purchase Order Purchase Order Key'], $data['Agent Supplier Purchase Order Key'], $data['Supplier Name'], $data['Supplier Code']
                ),

                'state'    => $state,
                'amount'   => money($data['Agent Supplier Purchase Order Amount'], $data['Agent Supplier Purchase Order Currency Code']),
                'products' => number($data['Agent Supplier Purchase Order Products']),
                'problems' => sprintf('<span class="%s">%s</span>', ($data['Agent Supplier Purchase Order Products With Problem'] == 0 ? 'very_discreet' : 'error'), number($data['Agent Supplier Purchase Order Products With Problem'])),

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


function agent_supplier_order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    $purchase_order = get_object('AgentSupplierPurchaseOrder', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';
    include_once 'utils/supplier_order_functions.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
   // $exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
            if ($data['Purchase Order Submitted Units'] > 0) {

                $subtotals .= $data['Purchase Order Submitted Units'] .'u. '.$data['Purchase Order Submitted Units'] * $data['Part Units Per Package'] .'pkg. ';


                $amount = $data['Purchase Order Submitted Units'] * $units_per_carton * $data['Supplier Part Unit Cost'];

                $subtotals .= money($amount, $purchase_order->get('Purchase Order Currency Code'));

                if ($data['Supplier Part Currency Code'] != $account->get(
                        'Account Currency'
                    )) {
                    $subtotals .= ' <span >('.money(
                            $amount * $purchase_order->get(
                                'Purchase Order Currency Exchange'
                            ), $account->get('Account Currency')
                        ).')</span>';

                }

                if ($data['Part Package Weight'] > 0) {
                    $subtotals .= ' | '.weight(
                            $data['Part Package Weight'] * $data['Purchase Order Submitted Units'] / $data['Part Units Per Package']
                        );
                }
                if ($data['Supplier Part Carton CBM'] > 0) {
                    $subtotals .= ' | '.number(
                            $data['Purchase Order Submitted Units'] * $data['Supplier Part Carton CBM']/ $data['Part Units Per Package']/$data['Supplier Part Packages Per Carton']
                        ).' m³';
                }
            }
            $subtotals .= '</span>';


            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$units_per_carton.'</b>', '<b>'.$skos_per_carton.'</b>'
            );

            if (!$data['Supplier Delivery Key']) {

                $delivery_qty = $data['Purchase Order Submitted Units'];

                $delivery_quantity = sprintf(
                    '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                    $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                );
            } else {
                $delivery_quantity = number(
                    $data['Supplier Delivery Units']
                );

            }


            $description = ($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '').$data['Supplier Part Description'];


            $quantity = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Submitted Units", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Submitted Units'] + 0, $data['Purchase Order Submitted Units'] + 0
            );

            if ($data['Part Main Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/image.php?id=%d&s=50x50" style="display: block;max-width:50px;max-height:50px;width: auto;height: auto;">', $data['Part Main Image Key']
                );
            } else {
                $image = '';
            }


            list(
                $_back_operations, $_forward_operations, $_state

                ) = get_agent_purchase_order_transaction_data($data);


            $back_operations    = '<span class="back_operations_'.$data['Purchase Order Transaction Fact Key'].'">';
            $forward_operations = '<span class="forward_operations_'.$data['Purchase Order Transaction Fact Key'].'">';

            $back_operations    .= $_back_operations;
            $forward_operations .= $_forward_operations;

            $back_operations    .= '</span>';
            $forward_operations .= '</span>';

            $state = '<span class="transaction_state_'.$data['Purchase Order Transaction Fact Key'].'">';
            $state .= $_state;
            $state .= '</span>';


            $xhtml_materials = '';
            if ($data['Part Materials'] != '') {


                $materials_data = json_decode($data['Part Materials'], true);


                foreach ($materials_data as $material_data) {
                    if (!array_key_exists('id', $material_data)) {
                        continue;
                    }

                    if ($material_data['may_contain'] == 'Yes') {
                        $may_contain_tag = '±';
                    } else {
                        $may_contain_tag = '';
                    }


                    $xhtml_materials .= sprintf(
                        ', %s%s', $may_contain_tag, $material_data['name']
                    );


                    if ($material_data['ratio'] > 0) {
                        $xhtml_materials .= sprintf(
                            ' (%s)', percentage($material_data['ratio'], 1)
                        );
                    }
                }

                $xhtml_materials = ucfirst(
                    preg_replace('/^\, /', '', $xhtml_materials)
                );

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

                'description'        => $description,
                'quantity'           => $quantity,
                'delivery_quantity'  => $delivery_quantity,
                'subtotals'          => $subtotals,
                'ordered'            => number($data['Purchase Order Submitted Units']),
                'supplier'           => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'unit_cost'          => money($data['Supplier Part Unit Cost'], $purchase_order->get('Agent Supplier Purchase Order Currency Code')),
                'qty_units'          => number($units_per_carton * $data['Purchase Order Submitted Units']),
                'qty_cartons'        => number($data['Purchase Order Submitted Units']),
                'amount'             => money($data['Supplier Part Unit Cost'] * $data['Purchase Order Submitted Units'] * $units_per_carton, $purchase_order->get('Agent Supplier Purchase Order Currency Code')),
                'state'              => $state,
                'back_operations'    => $back_operations,
                'forward_operations' => $forward_operations,
                'barcode'            => $data['Part Barcode Number'],
                'sko_barcode'        => $data['Part SKO Barcode'],
                'materials'          => $xhtml_materials

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


function agent_deliveries($_data, $db, $user) {


    $rtext_label = 'delivery';


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;
                case 'Submitted':
                    $state = sprintf('%s', _('Submitted'));
                    break;
                case 'Confirmed':
                    $state = sprintf('%s', _('Confirmed'));
                    break;
                case 'In Warehouse':
                    $state = sprintf('%s', _('In Warehouse'));
                    break;
                case 'Done':
                    $state = sprintf('%s', _('Done'));
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Supplier Delivery State'];
                    break;
            }

            $table_data[] = array(
                'id'        => (integer)$data['Supplier Delivery Key'],
                'public_id' => sprintf('<span class="link" onClick="change_view(\'agent_delivery/%d\')">%s</span>', $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']),
                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),
                'state'     => $state,

                'total_amount' => money($data['Supplier Delivery Total Amount'], $data['Supplier Delivery Currency Code'])


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


function agent_items_in_warehouse($_data, $db, $user, $account) {


    $rtext_label = 'item';

    $purchase_order = get_object('AgentSupplierPurchaseOrder', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';
    include_once 'utils/supplier_order_functions.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    $exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            if ($data['Metadata'] == '') {
                $metadata = array();
            } else {
                $metadata = json_decode($data['Metadata'], true);
            }

            $data['Currency Code'] = $purchase_order->get('Agent Supplier Purchase Order Currency Code');

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

            $quantity = number($data['Purchase Order Submitted Units']);


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
            if ($data['Purchase Order Submitted Units'] > 0) {

                $subtotals .= $data['Purchase Order Submitted Units'] * $units_per_carton.'u. '.$data['Purchase Order Submitted Units'] * $skos_per_carton.'pkg. ';


                $amount = $data['Purchase Order Submitted Units'] * $units_per_carton * $data['Supplier Part Unit Cost'];

                $subtotals .= money(
                    $amount, $purchase_order->get('Purchase Order Currency Code')
                );

                if ($data['Supplier Part Currency Code'] != $account->get(
                        'Account Currency'
                    )) {
                    $subtotals .= ' <span >('.money(
                            $amount * $purchase_order->get(
                                'Purchase Order Currency Exchange'
                            ), $account->get('Account Currency')
                        ).')</span>';

                }

                if ($data['Part Package Weight'] > 0) {
                    $subtotals .= ' | '.weight(
                            $data['Part Package Weight'] * $data['Purchase Order Submitted Units'] * $data['Supplier Part Packages Per Carton']
                        );
                }
                if ($data['Supplier Part Carton CBM'] > 0) {
                    $subtotals .= ' | '.number(
                            $data['Purchase Order Submitted Units'] * $data['Supplier Part Carton CBM']
                        ).' m³';
                }
            }
            $subtotals .= '</span>';


            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$units_per_carton.'</b>', '<b>'.$skos_per_carton.'</b>'
            );

            if (!$data['Supplier Delivery Key']) {

                $delivery_qty = $data['Purchase Order Submitted Units'];

                $delivery_quantity = sprintf(
                    '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                    $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                );
            } else {
                $delivery_quantity = number(
                    $data['Supplier Delivery Units']
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
                '<span    data-settings=\'{"field": "Purchase Order Submitted Units", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Submitted Units'] + 0, $data['Purchase Order Submitted Units'] + 0
            );

            if ($data['Part Main Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/image.php?id=%d&s=50x50" style="display: block;max-width:50px;max-height:50px;width: auto;height: auto;">', $data['Part Main Image Key']
                );
            } else {
                $image = '';
            }


            list(
                $_back_operations, $_forward_operations, $_state

                ) = get_agent_purchase_order_transaction_data($data);


            $back_operations    = '<span class="back_operations_'.$data['Purchase Order Transaction Fact Key'].'">';
            $forward_operations = '<span class="forward_operations_'.$data['Purchase Order Transaction Fact Key'].'">';

            $back_operations    .= $_back_operations;
            $forward_operations .= $_forward_operations;

            $back_operations    .= '</span>';
            $forward_operations .= '</span>';

            $state = '<span class="transaction_state_'.$data['Purchase Order Transaction Fact Key'].'">';
            $state .= $_state;
            $state .= '</span>';

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

                'description'        => $description,
                'quantity'           => $quantity,
                'delivery_quantity'  => $delivery_quantity,
                'subtotals'          => $subtotals,
                'ordered'            => number($data['Purchase Order Submitted Units']),
                'supplier'           => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'unit_cost'          => money($data['Supplier Part Unit Cost'], $purchase_order->get('Agent Supplier Purchase Order Currency Code')),
                'qty_units'          => number($units_per_carton * $data['Purchase Order Submitted Units']),
                'qty_cartons'        => number($data['Purchase Order Submitted Units']),
                'amount'             => money($data['Supplier Part Unit Cost'] * $data['Purchase Order Submitted Units'] * $units_per_carton, $purchase_order->get('Agent Supplier Purchase Order Currency Code')),
                'state'              => $state,
                'back_operations'    => $back_operations,
                'forward_operations' => $forward_operations

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
