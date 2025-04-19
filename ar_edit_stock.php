<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 July 2016 at 13:51:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/aiku_stand_alone_process_aiku_fetch.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'msg'   => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}
//print_r($_REQUEST);

$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'move_all_parts_from_location':
        $data = prepare_values(
            $_REQUEST, array(

                         'from_location_key' => array('type' => 'key'),
                         'to_location_key'   => array('type' => 'key'),
                         'remove_after'      => array('type' => 'string')

                     )
        );

        move_all_parts_from_location($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'get_part_locations_html':
        $data = prepare_values(
            $_REQUEST, array(

                         'part_sku' => array('type' => 'key'),

                     )
        );

        get_part_locations_html($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_part_linked_locations':

        $data = prepare_values(
            $_REQUEST, array(

                         'part_sku'            => array('type' => 'key'),
                         'locations_to_add'    => array('type' => 'json array'),
                         'locations_to_remove' => array('type' => 'json array')
                     )
        );


        edit_part_linked_locations($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_part_stock_check':

        $data = prepare_values(
            $_REQUEST, array(

                         'part_sku'        => array('type' => 'key'),
                         'stock_to_update' => array('type' => 'json array')
                     )
        );


        edit_part_stock_check($data, $editor, $smarty);
        break;
    case 'edit_part_move_stock':

        $data = prepare_values(
            $_REQUEST, array(
                         'part_sku'  => array('type' => 'key'),
                         'movements' => array('type' => 'json array')
                     )
        );


        edit_part_move_stock($editor, $data, $smarty);
        break;
    case 'set_as_picking_location':

        $data = prepare_values(
            $_REQUEST, array(
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),

                     )
        );

        set_as_picking_location($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'place_part':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'          => array('type' => 'string'),
                         'key'             => array('type' => 'key'),
                         'transaction_key' => array('type' => 'key'),
                         'part_sku'        => array('type' => 'key'),
                         'location_key'    => array('type' => 'key'),
                         'qty'             => array('type' => 'numeric'),
                         'note'            => array('type' => 'string'),

                     )
        );


        place_part($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'new_part_location':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'       => array('type' => 'string'),
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),

                     )
        );


        new_part_location($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'add_part_to_location':

        $data = prepare_values(
            $_REQUEST, array(
                         'stock'        => array('type' => 'string'),
                         'note'         => array('type' => 'string'),
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),

                     )
        );


        add_part_to_location($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'disassociate_location_part':

        $data = prepare_values(
            $_REQUEST, array(
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),

                     )
        );


        disassociate_location_part($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_part_location_stock':

        $data = prepare_values(
            $_REQUEST, array(
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),
                         'qty'          => array('type' => 'string'),
                         'note'         => array('type' => 'string'),

                     )
        );


        edit_part_location_stock($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_part_location_note':

        $data = prepare_values(
            $_REQUEST, array(
                         'part_location_code' => array('type' => 'string'),
                         'note'               => array('type' => 'string'),
                     )
        );


        edit_part_location_note($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_stock':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'               => array('type' => 'string'),
                         'key'                  => array('type' => 'key'),
                         //'field'=>array('type'=>'string'),
                         //'value'=>array('type'=>'string'),
                         'parts_locations_data' => array('type' => 'json array'),
                         'movements'            => array('type' => 'json array'),

                     )
        );


        edit_stock($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_leakages':

        $data = prepare_values(
            $_REQUEST, array(
                         'note'     => array('type' => 'string'),
                         'part_sku' => array('type' => 'key'),
                         'type'     => array('type' => 'string'),
                         'qty'      => array('type' => 'string'),

                     )
        );


        edit_leakages($data, $editor, $smarty);
        break;
    case 'send_to_production':

        $data = prepare_values(
            $_REQUEST, array(
                         'note'         => array('type' => 'string'),
                         'part_sku'     => array('type' => 'key'),
                         'location_key' => array('type' => 'key'),
                         'qty'          => array('type' => 'string'),

                     )
        );


        send_to_production($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'itf_cost':

        $data = prepare_values(
            $_REQUEST, array(
                         'key'   => array('type' => 'key'),
                         'value' => array('type' => 'string'),

                     )
        );


        itf_cost($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'set_delivery_costing':

        $data = prepare_values(
            $_REQUEST, array(
                         'key'        => array('type' => 'key'),
                         'exchange'   => array('type' => 'numeric'),
                         'items_data' => array('type' => 'json array'),

                     )
        );


        set_delivery_costing($account, $db, $user, $editor, $data, $smarty);
        break;
    default:
        $response = array(
            'state' => 405,
            'msg'   => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function move_all_parts_from_location($account, $db, $user, $editor, $data, $smarty) {

    if ($data['from_location_key'] == $data['to_location_key']) {
        $response = array(
            'state' => 400,
            'msg'   => 'Same location'
        );
        echo json_encode($response);

        return;
    }

    $location_from = get_object('Location', $data['from_location_key']);


    $location_to = get_object('Location', $data['to_location_key']);

    $number_parts = 0;

    foreach ($location_from->get_parts('part_location_object') as $part_location_from) {

        $part_location_from->editor = $editor;

        $part_location_data = array(
            'Location Key' => $location_to->id,
            'Part SKU'     => $part_location_from->part->id,
            'editor'       => $editor
        );
        $number_parts++;

        new PartLocation('find', $part_location_data, 'create');


        $part_location_from->move_stock(
            array(
                'Destination Key'  => $location_to->id,
                'Quantity To Move' => $part_location_from->get('Quantity On Hand')
            )
        );

        if ($data['remove_after'] == 'Yes') {
            $part_location_from->disassociate();
        }


    }
    $response = array(
        'state' => 200,
        'msg'   => sprintf(
            ngettext('%s part was moved to %s', '%s parts were moved to %s', $number_parts), number($number_parts), sprintf(
                                                                                               '<span class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $location_to->get('Location Warehouse Key'), $location_to->id, $location_to->get('Code')
                                                                                           )
        )
    );
    echo json_encode($response);
    exit;

}


function set_delivery_costing($account, $db, $user, $editor, $data, $smarty) {

    //print_r($data);


    $delivery         = get_object('SupplierDelivery', $data['key']);
    $delivery->editor = $editor;
    $parts_data       = array();


    $sql = sprintf(
        'select ANY_VALUE(`Supplier Part Part SKU`) as `Supplier Part Part SKU`,
        ANY_VALUE(`Purchase Order Transaction Fact Key`) as `Purchase Order Transaction Fact Key`,
        ANY_VALUE(`Metadata`) as `Metadata` from `Purchase Order Transaction Fact`  POTF left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) where `Supplier Delivery Key`=%d group by `Supplier Part Part SKU` ',
        $delivery->id
    );
    if ($result = $db->query($sql)) {
        $all_parts_min_date = '';
        foreach ($result as $row) {




            $amount_paid = (($data['items_data'][$row['Supplier Part Part SKU']][0] + $data['items_data'][$row['Supplier Part Part SKU']][1]) / $data['exchange']) + $data['items_data'][$row['Supplier Part Part SKU']][2];
            $sql         = sprintf(
                'update `Purchase Order Transaction Fact` set `Supplier Delivery Net Amount`=%.2f ,`Supplier Delivery Extra Cost Amount`=%.2f, `Supplier Delivery Extra Cost Account Currency Amount`=%.2f   where `Purchase Order Transaction Fact Key`=%d    ',
                $data['items_data'][$row['Supplier Part Part SKU']][0], $data['items_data'][$row['Supplier Part Part SKU']][1], $data['items_data'][$row['Supplier Part Part SKU']][2], $row['Purchase Order Transaction Fact Key']

            );


            $db->exec($sql);


            if ($row['Metadata'] != '') {
                $metadata = json_decode($row['Metadata'], true);
                //  print_r($metadata);

                if (isset($metadata['placement_data'])) {


                    $min_date     = '';
                    $total_placed = 0;
                    foreach ($metadata['placement_data'] as $placement_data) {


                        $sql = sprintf('select `Date` from `Inventory Transaction Fact`    where `Inventory Transaction Key`=%d', $placement_data['oif_key']);

                        if ($result2 = $db->query($sql)) {
                            foreach ($result2 as $row2) {
                                $date = gmdate('U', strtotime($row2['Date']));
                                if ($min_date == '') {
                                    $min_date = $date;

                                } elseif ($date < $min_date) {
                                    $min_date = $date;
                                }
                                if ($all_parts_min_date == '') {
                                    $all_parts_min_date = $date;

                                } elseif ($date < $all_parts_min_date) {
                                    $all_parts_min_date = $date;
                                }


                            }
                        }


                        $total_placed += $placement_data['qty'];


                    }

                    //   {"placement_data":[{"oif_key":"44589259","wk":"1","lk":"14158","l":"Unit 3","qty":"540"}]}

                    $parts_data[$row['Supplier Part Part SKU']] = ($min_date != '' ? gmdate('Y-m-d', $min_date) : '');


                    if ($total_placed > 0) {
                        foreach ($metadata['placement_data'] as $placement_data) {
                            $sql = sprintf(
                                'update `Inventory Transaction Fact`  set `Inventory Transaction Amount`=%f   where `Inventory Transaction Key`=%d', $amount_paid * $placement_data['qty'] / $total_placed, $placement_data['oif_key']
                            );
                            $db->exec($sql);

                            stand_alone_process_aiku_fetch($db,'OrgStockMovement',$placement_data['oif_key']);

                            $sql = sprintf('insert into `ITF POTF Costing Done Bridge`   (`ITF POTF Costing Done ITF Key`,`ITF POTF Costing Done POTF Key`) values (%d,%d)  ', $placement_data['oif_key'], $row['Purchase Order Transaction Fact Key']);


                            $db->exec($sql);


                        }


                    }

                }

            }


        }
    }

    $delivery->fast_update(
        array('Supplier Delivery Currency Exchange' => 1 / $data['exchange'])
    );
    $delivery->update_state('InvoiceChecked');

    $delivery->update_totals();


    include_once 'utils/new_fork.php';


    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'               => 'update_parts_stock_run',
        'parts_data'         => $parts_data,
        'editor'             => $editor,
        'all_parts_min_date' => ($all_parts_min_date != '' ? gmdate('Y-m-d', $all_parts_min_date) : ''),
    ), $account->get('Account Code')
    );

    stand_alone_process_aiku_fetch($db,'SupplierDelivery',$delivery->id);


    $response = array(
        'state' => 200
    );
    echo json_encode($response);

}


function get_part_locations_html($account, $db, $user, $editor, $data, $smarty) {


    $part = get_object('Part', $data['part_sku']);
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }


    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);

    $smarty->assign('locations_data', $part->get_locations('data'));


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));


    $response = array(
        'state'          => 200,
        'part_locations' => $smarty->fetch('part_locations.edit.tpl')
    );


    echo json_encode($response);
    exit;


}

function edit_part_stock_check($data, $editor, $smarty) {


    $part         = get_object('Part', $data['part_sku']);
    $part->editor = $editor;
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }


    foreach ($data['stock_to_update'] as $stock_to_update_data) {
        $location = get_object('Location', $stock_to_update_data['location_key']);
        if ($location->id) {

            $part_location = get_object('PartLocation', $part->id.'_'.$location->id);

            $note = $stock_to_update_data['note'];


            $stock=$stock_to_update_data['stock'];
            if($stock==''){
                $stock=0;
            }

            if ($part_location->ok) {
                $part_location->editor = $editor;
                $part_location->audit($stock, $note);

            }


        }
    }


    $part = get_object('Part', $data['part_sku']);
    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);
    $smarty->assign('locations_data', $part->get_locations('data'));


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));


    $response = array(
        'state'          => 200,
        'part_locations' => $smarty->fetch('part_locations.edit.tpl')
    );

    $response['Part_Unknown_Location_Stock'] = $part->get('Part Unknown Location Stock');

    $response['updated_fields'] = array(
        'Current_On_Hand_Stock'    => $part->get('Current On Hand Stock'),
        'Stock_Status_Icon'        => $part->get('Stock Status Icon'),
        'Current_Stock'            => $part->get('Current Stock'),
        'Current_Stock_Picked'     => $part->get('Current Stock Picked'),
        'Current_Stock_In_Process' => $part->get('Current Stock In Process'),
        'Current_Stock_Available'  => $part->get('Current Stock Available'),
        'Available_Forecast'       => $part->get('Available Forecast'),
        'Part_Status'              => $part->get('Status'),
        'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse'),
        'Unknown_Location_Stock'   => $part->get('Unknown Location Stock'),


    );


    echo json_encode($response);
    exit;


}


function edit_part_linked_locations($account, $db, $user, $editor, $data, $smarty) {


    $part = get_object('Part', $data['part_sku']);
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }


    include_once 'class.PartLocation.php';


    foreach ($data['locations_to_add'] as $location_key) {
        $location = get_object('Location', $location_key);
        if ($location->id) {

            $part_location_data = array(
                'Location Key' => $location->id,
                'Part SKU'     => $part->id,
                'editor'       => $editor
            );
            new PartLocation('find', $part_location_data, 'create');


        }
    }


    foreach ($data['locations_to_remove'] as $location_key) {
        $location = get_object('Location', $location_key);
        if ($location->id) {

            $part_location = new PartLocation($part->id, $location->id);

            if ($part_location->ok) {
                $part_location->editor = $editor;
                $part_location->delete();

            }


        }
    }

    $part           = get_object('Part', $data['part_sku']);
    $part_locations = $part->get_locations('part_location_object', 'stock');


    // print_r($part_locations);

    $has_picking_location = false;
    foreach ($part_locations as $part_location) {
        if ($part_location->get('Can Pick') == 'Yes') {
            $has_picking_location = true;
            break;
        }


    }


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);

    if (!$has_picking_location) {
        foreach ($part_locations as $part_location) {
            if ($part_location->location->id != $warehouse->get('Warehouse Unknown Location Key')) {
                $part_location->update(array('Can Pick' => 'Yes'));
                break;
            }
        }
    }


    $part = get_object('Part', $data['part_sku']);
    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));

    $smarty->assign('locations_data', $part->get_locations('data'));


    $response = array(
        'state'          => 200,
        'part_locations' => $smarty->fetch('part_locations.edit.tpl')
    );


    echo json_encode($response);
    exit;


}


function new_part_location($account, $db, $user, $editor, $data, $smarty) {


    $part = get_object('Part', $data['part_sku']);
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }

    $location = get_object('Location', $data['location_key']);
    if (!$location->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'location not found'
        );
        echo json_encode($response);
        exit;
    }

    include_once 'class.PartLocation.php';


    $part_location_data = array(
        'Location Key' => $data['location_key'],
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );


    $part_location = new PartLocation('find', $part_location_data, 'create');

    if ($part_location->new) {

        /*
                switch ($part_location->part->get('Location Mainly Used For')) {
                    case 'Picking':
                        $used_for = sprintf(
                            '<i class="fa fa-fw fa-shopping-basket" aria-hidden="true" title="%s" ></i>', _('Picking')
                        );
                        break;
                    case 'Storing':
                        $used_for = sprintf(
                            '<i class="fa fa-fw  fa-hdd" aria-hidden="true" title="%s"></i>', _('Storing')
                        );
                        break;
                    default:
                        $used_for = sprintf(
                            '<i class="fa fa-fw  fa-shopping-basket" aria-hidden="true" title="%s"></i>', $part_location->part->get('Location Mainly Used For')
                        );
                }
        */

        $picking_location_icon = sprintf(
            '<i onclick="set_as_picking_location(%d,%d)" class="fa fa-fw fa-shopping-basket %s" aria-hidden="true" title="%s" ></i></span>', $part_location->part->id, $part_location->location->id,
            ($part_location->get('Can Pick') == 'Yes' ? '' : 'super_discreet_on_hover button'), ($part_location->get('Can Pick') == 'Yes' ? _('Picking location') : _('Set as picking location'))

        );


        $response = array(
            'state'                 => 200,
            'part_sku'              => $part_location->part->id,
            'location_key'          => $part_location->location->id,
            'location_code'         => $part_location->location->get('Code'),
            'formatted_stock'       => number($part_location->get('Quantity On Hand'), 3),
            'stock'                 => ($part_location->get('Quantity On Hand') == 0 ? '' : $part_location->get('Quantity On Hand')),
            'picking_location_icon' => $picking_location_icon,
            // 'location_used_for'      => $part_location->location->get('Location Mainly Used For'),
            'location_link'         => sprintf('locations/%d/%d', $part_location->location->get('Warehouse Key'), $part_location->location->id),

            'formatted_min_qty'  => ($part_location->get('Minimum Quantity') != '' ? $part_location->get('Minimum Quantity') : '?'),
            'formatted_max_qty'  => ($part_location->get('Maximum Quantity') != '' ? $part_location->get('Maximum Quantity') : '?'),
            'formatted_move_qty' => ($part_location->get('Moving Quantity') != '' ? $part_location->get('Moving Quantity') : '?'),
            'min_qty'            => $part_location->get('Minimum Quantity'),
            'max_qty'            => $part_location->get('Maximum Quantity'),
            'move_qty'           => $part_location->get('Moving Quantity'),

            'can_pick' => $part_location->get('Can Pick')


        );
    } elseif ($part_location->ok) {
        $response = array(
            'state' => 400,
            'msg'   => _('Location already associated with the part')
        );

    } else {
        $response = array(
            'state' => 400,
            'msg'   => $part_location->msg
        );

    }


    echo json_encode($response);
    exit;


}


function disassociate_location_part($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location         = new PartLocation($data['part_sku'], $data['location_key']);
    $part_location->editor = $editor;

    if (!$part_location->ok) {
        $response = array(
            'state' => 200,
            'msg'   => _('Error, please try again').' location part not associated'
        );


        echo json_encode($response);
        exit;

    }

    $part_location->disassociate();

    $number_parts_in_location = count($part_location->location->get_parts());

    $response = array(
        'state' => 200,
        'rtext' => sprintf(ngettext('%s part', '%s parts', $number_parts_in_location), number($number_parts_in_location))
    );
    echo json_encode($response);


}


function edit_part_location_stock($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location         = new PartLocation($data['part_sku'], $data['location_key']);
    $part_location->editor = $editor;

    if (!$part_location->ok) {
        $response = array(
            'state' => 400,
            'msg'   => _('Error, please try again').' location part not associated'
        );


        echo json_encode($response);
        exit;

    }

    $part_location->audit($data['qty'], $data['note'], $editor['Date']);


    $update_metadata['location_part_stock_cell'] = sprintf(
        '<span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onClick="open_location_part_stock_quantity_dialog(this)">%s</span>', '', $data['part_sku'],
        $data['location_key'], $part_location->get('Quantity On Hand'), number($part_location->get('Quantity On Hand'))
    );


    $update_metadata['location_part_stock_value_cell'] = money($part_location->get('Stock Value'), $account->get('Account Currency'));

    $update_metadata['location_part_link_cell'] = '<i class="fa fa-unlink '.($part_location->get('Quantity On Hand') != 0 ? 'invisible' : 'button').'" aria-hidden="true" part_sku="'.$data['part_sku'].'" onclick="location_part_disassociate_from_table(this)"></i>';

    $response = array(
        'state'           => 200,
        'update_metadata' => $update_metadata,
        'part_sku'        => $part_location->part->id
    );
    echo json_encode($response);


}

/**
 * @param $editor   array
 * @param $data     array
 * @param $smarty   \Smarty
 */
function edit_part_move_stock($editor, $data, $smarty) {


    $part = get_object('Part', $data['part_sku']);
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }


    include_once 'class.PartLocation.php';


    $movement = $data['movements'];

    if (isset($movement['from_location_key']) and isset($movement['to_location_key']) and isset($movement['move_qty'])) {


        $from_location = get_object('location', $movement['from_location_key']);
        if (!$from_location->id) {
            $response = array(
                'state' => 400,
                'msg'   => 'from location not found'
            );
            echo json_encode($response);
            exit;
        }

        $to_location = get_object('location', $movement['to_location_key']);
        if (!$to_location->id) {
            $response = array(
                'state' => 400,
                'msg'   => 'from location not found'
            );
            echo json_encode($response);
            exit;
        }


        if (!is_numeric($movement['move_qty'])) {
            $response = array(
                'state' => 400,
                'msg'   => _('Quantity must be numeric')
            );
            echo json_encode($response);
            exit;
        }

        if ($movement['move_qty'] <= 0) {
            $response = array(
                'state' => 400,
                'msg'   => _('Quantity must be more than zero')
            );
            echo json_encode($response);
            exit;
        }

        $part_location_from = new PartLocation($part->id, $movement['from_location_key']);

        if (!$part_location_from->ok) {
            $response = array(
                'state' => 400,
                'msg'   => 'from location_part not found'
            );
            echo json_encode($response);
            exit;

        }

        $part_location_from->editor = $editor;


        //$part_location_to         = new PartLocation($part->id, $movement['to_location_key']);
        //$part_location_to->editor = $editor;


        $part_location_from->move_stock(
            array(
                'Destination Key'  => $movement['to_location_key'],
                'Quantity To Move' => $movement['move_qty']
            )
        );

    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'missing arguments'
        );
        echo json_encode($response);
        exit;
    }

    if ($part_location_from->error) {
        $response = array(
            'state' => 400,
            'msg'   => $part_location_from->msg
        );
        echo json_encode($response);
        exit;
    }


    $response = array('state' => 200);


    $part = get_object('part', $part->id);

    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);

    $smarty->assign('locations_data', $part->get_locations('data'));


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));

    $part_locations = $smarty->fetch('part_locations.edit.tpl');


    $response['Part_Unknown_Location_Stock'] = $part->get('Part Unknown Location Stock');

    $response['updated_fields'] = array(
        'Current_On_Hand_Stock'    => $part->get('Current On Hand Stock'),
        'Stock_Status_Icon'        => $part->get('Stock Status Icon'),
        'Current_Stock'            => $part->get('Current Stock'),
        'Current_Stock_Picked'     => $part->get('Current Stock Picked'),
        'Current_Stock_In_Process' => $part->get('Current Stock In Process'),
        'Current_Stock_Available'  => $part->get('Current Stock Available'),
        'Available_Forecast'       => $part->get('Available Forecast'),
        'Part_Locations'           => $part_locations,
        'Part_Status'              => $part->get('Status'),
        'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse'),
        'Unknown_Location_Stock'   => $part->get('Unknown Location Stock'),


    );


    echo json_encode($response);


}

function edit_stock_to_delete($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $parts_locations_data = $data['parts_locations_data'];

    $movement = $data['movements'];

    if (isset($movement['part_sku']) and isset($movement['from_location_key'])) {

        $part_location_from         = new PartLocation($movement['part_sku'], $movement['from_location_key']);
        $part_location_from->editor = $editor;


        if ($part_location_from->get('Quantity On Hand') != $movement['from_location_stock']) {
            $part_location_from->audit($movement['from_location_stock'], ' '.$part_location_from->get('Quantity On Hand').'->'.$movement['from_location_stock'], $editor['Date']);
        }

        $part_location_to         = new PartLocation($movement['part_sku'], $movement['to_location_key']);
        $part_location_to->editor = $editor;

        if ($part_location_to->get('Quantity On Hand') != $movement['to_location_stock']) {
            $part_location_to->audit(
                $movement['to_location_stock'], '', $editor['Date']
            );
        }

        $part_location_from->move_stock(
            array(
                'Destination Key'  => $movement['to_location_key'],
                'Quantity To Move' => $movement['move_qty']
            )
        );

    }


    foreach ($parts_locations_data as $key => $part_locations_data) {

        $part_location         = new PartLocation($part_locations_data['part_sku'], $part_locations_data['location_key']);
        $part_location->editor = $editor;

        if (!$part_location->ok) {
            $response = array(
                'state' => 400,
                'msg'   => _('Error, please try again').' location part not associated'
            );


            echo json_encode($response);
            exit;

        }

        if ($parts_locations_data[$key]['disassociate']) {
            $part_location->delete();
        } else {
            if ($parts_locations_data[$key]['qty'] != $part_location->get('Quantity On Hand') or $parts_locations_data[$key]['audit']) {


                $part_location->audit(
                    $parts_locations_data[$key]['qty'], (isset($parts_locations_data[$key]['note']) ? $parts_locations_data[$key]['note'] : ''), $editor['Date']
                );
            }
        }
    }


    $response = array('state' => 200);

    if ($data['object'] == 'part') {
        $part = get_object($data['object'], $data['key']);

        $smarty->assign('part_sku', $part->id);
        $smarty->assign('part', $part);

        $smarty->assign('locations_data', $part->get_locations('data'));


        $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
        $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));

        $part_locations = $smarty->fetch('part_locations.edit.tpl');


        $response['Part_Unknown_Location_Stock'] = $part->get('Part Unknown Location Stock');

        $response['updated_fields'] = array(
            'Current_On_Hand_Stock'    => $part->get('Current On Hand Stock'),
            'Stock_Status_Icon'        => $part->get('Stock Status Icon'),
            'Current_Stock'            => $part->get('Current Stock'),
            'Current_Stock_Picked'     => $part->get('Current Stock Picked'),
            'Current_Stock_In_Process' => $part->get(
                'Current Stock In Process'
            ),
            'Current_Stock_Available'  => $part->get('Current Stock Available'),
            'Available_Forecast'       => $part->get('Available Forecast'),
            'Part_Locations'           => $part_locations,
            'Part_Status'              => $part->get('Status'),
            'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse'),
            'Unknown_Location_Stock'   => $part->get('Unknown Location Stock'),


        );
    }


    echo json_encode($response);


}


function place_part($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';
    include_once 'utils/currency_functions.php';


    $part_location_data = array(
        'Location Key' => $data['location_key'],
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );


    $part = get_object('Part', $data['part_sku']);
    if (!$part->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'part not found'
        );
        echo json_encode($response);
        exit;
    }

    $location = get_object('Location', $data['location_key']);
    if (!$location->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'location not found'
        );
        echo json_encode($response);
        exit;
    }


    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;
    $origin         = sprintf(
        '<span class="link" onClick="change_view(\'delivery/%d\')" ><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> %s</span>', $object->id, $object->get('Public ID')
    );

    if ($data['note'] != '') {
        $origin .= ' <i class="fa fa-sticky-note" aria-hidden="true"></i> '.$data['note'];
    }


    $sql = sprintf(
        'SELECT `Purchase Order Transaction Type`,`Supplier Delivery Extra Cost Amount`,`Supplier Delivery Extra Cost Account Currency Amount`,`Supplier Delivery Key`,`Part Units Per Package`,`Supplier Part Unit Extra Cost`,`Supplier Part Unit Cost`,`Supplier Part Currency Code`,POTF.`Currency Code`,SP.`Supplier Part Key`,`Supplier Delivery Units`,`Supplier Delivery Net Amount`,`Purchase Order Transaction Fact Key`,`Supplier Delivery Checked Units`,`Supplier Delivery Placed Units` ,`Supplier Part Packages Per Carton` 
FROM	
  `Purchase Order Transaction Fact` POTF
LEFT JOIN `Supplier Part Historic Dimension` SPH ON (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 LEFT JOIN  `Supplier Part Dimension` SP ON (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
  LEFT JOIN  `Part Dimension` P ON (`Part SKU`=SP.`Supplier Part Part SKU`)


	 WHERE `Purchase Order Transaction Fact Key`=%d', $data['transaction_key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            if ($row['Supplier Delivery Placed Units'] == '') {
                $row['Supplier Delivery Placed Units'] = 0;
            }


            $part_location = new PartLocation('find', $part_location_data, 'create');


            $part_location->editor = $editor;
            if (!$part_location) {
                $response = array(
                    'state' => 400,
                    'msg'   => $part_location->msg
                );
                echo json_encode($response);
                exit;
            }

            // $data['qty'] <--- SKOs
            //


            $units_qty_placed = $data['qty'] * $row['Part Units Per Package'];


            if (round($units_qty_placed, 2) > round($row['Supplier Delivery Checked Units'] - $row['Supplier Delivery Placed Units'], 2)) {
                $response = array(
                    'state' => 400,
                    'msg'   => _('Placement quantity greater than the checked quantity')
                );
                echo json_encode($response);
                exit;
            }


            $exchange = $object->get('Supplier Delivery Currency Exchange');

            $amount_per_sko = round(
                $row['Part Units Per Package'] * ($exchange * ($row['Supplier Delivery Net Amount'] + $row['Supplier Delivery Extra Cost Amount']) + $row['Supplier Delivery Extra Cost Account Currency Amount']) / $row['Supplier Delivery Units'], 4
            );

            //print ($exchange * ($row['Supplier Delivery Net Amount'] + $row['Supplier Delivery Extra Cost Amount']) + $row['Supplier Delivery Extra Cost Account Currency Amount'])."\n";

            //exit($amount_per_sko);


            if ($row['Purchase Order Transaction Type'] == 'Return') {
                $transaction_type = 'Restock';
            } else {
                $transaction_type = 'In';
            }

            if ($account->get('Account Add Stock Value Type') == 'Last Price') {

                $oif_key = $part_location->add_stock(
                    array(
                        'Quantity'         => $data['qty'],
                        'Origin'           => $origin,
                        'Transaction Type' => $transaction_type
                    )
                );

            } else {


                $oif_key = $part_location->add_stock(
                    array(
                        'Quantity'         => $data['qty'],
                        'Origin'           => $origin,
                        'Amount'           => $data['qty'] * $amount_per_sko,
                        'Transaction Type' => $transaction_type
                    )
                );


                include_once 'utils/new_fork.php';


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'part_stock_run',
                    'part_sku' => $part_location->part->id,
                ), $account->get('Account Code')
                );


            }


            if ($part_location->error) {
                $response = array(
                    'state' => 400,
                    'msg'   => $part_location->msg
                );
                echo json_encode($response);
                exit;
            }

            $_data = array(
                'transaction_key' => $row['Purchase Order Transaction Fact Key'],
                'qty'             => $data['qty'],
                'placement_data'  => array(
                    'oif_key' => $oif_key,
                    'wk'      => $part_location->location->get('Location Warehouse Key'),
                    'lk'      => $part_location->location->id,
                    'l'       => $part_location->location->get('Code'),
                    'qty'     => $data['qty']
                )
            );


            $result_placement = $object->update_item_delivery_placed_skos($_data);

            if ($object->error) {
                $response = array(
                    'state' => 400,
                    'msg'   => $object->msg
                );
                echo json_encode($response);
                exit;
            }


            $number_part_locations = 0;
            $part_locations        = '';

            $sql = sprintf(
                'SELECT L.`Location Key`,L.`Location Code`,`Can Pick`,`Quantity On Hand` FROM `Part Location Dimension` PLD  LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PLD.`Location Key`) WHERE PLD.`Part SKU`=%d', $data['part_sku']
            );
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $number_part_locations++;
                    $part_locations .= '<div class="button" onClick="set_placement_location(this)" style="clear:both;"  location_key="'.$row['Location Key'].'"><div  class="code data w150"  >'.$row['Location Code'].'</div><div class="data w30 aright" >'.number(
                            $row['Quantity On Hand']
                        ).'</div></div>';
                }
            }


            $response = array(
                'state'                 => 200,
                'update_metadata'       => $object->get_update_metadata(),
                'part_locations'        => $part_locations,
                'number_part_locations' => $number_part_locations,
                'placed'                => $result_placement['placed'],
                'place_qty'             => $result_placement['place_qty']

            );
            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'po_transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    }


}


function set_as_picking_location($account, $db, $user, $editor, $data, $smarty) {

    $part = get_object('part', $data['part_sku']);


    $ok = false;
    foreach ($part->get_locations('part_location_object') as $part_location) {
        if ($part_location->location_key == $data['location_key']) {
            $ok = true;
        }
    }

    if ($ok) {


        foreach ($part->get_locations('part_location_object') as $part_location) {


            if ($part_location->location_key == $data['location_key']) {
                $part_location->update(array('Can Pick' => 'Yes'));
            } else {
                $part_location->update(array('Can Pick' => 'No'));
            }


        }

        $response = array(
            'state'               => 200,
            'part_locations_data' => $part->get_locations('data')

        );

    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'location not found'
        );

    }

    $part->process_aiku_fetch('Part', $part->id);


    echo json_encode($response);


}


function edit_leakages($data, $editor, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location_data = array(
        'Location Key' => 1,
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );


    $part_location = new PartLocation('find', $part_location_data, 'create');


    $_data = array(
        'Quantity'         => -$data['qty'],
        'Transaction Type' => $data['type'],
        'Note'             => $data['note']
    );

    $part_location->stock_transfer($_data);


    $part = get_object('Part', $data['part_sku']);

    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);

    $smarty->assign('locations_data', $part->get_locations('data'));


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));


    $part_locations = $smarty->fetch('part_locations.edit.tpl');


    $response['Part_Unknown_Location_Stock'] = $part->get('Part Unknown Location Stock');

    $response['updated_fields'] = array(
        'Current_On_Hand_Stock'    => $part->get('Current On Hand Stock'),
        'Stock_Status_Icon'        => $part->get('Stock Status Icon'),
        'Current_Stock'            => $part->get('Current Stock'),
        'Current_Stock_Picked'     => $part->get('Current Stock Picked'),
        'Current_Stock_In_Process' => $part->get('Current Stock In Process'),
        'Current_Stock_Available'  => $part->get('Current Stock Available'),
        'Available_Forecast'       => $part->get('Available Forecast'),
        'Part_Locations'           => $part_locations,
        'Part_Status'              => $part->get('Status'),
        'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse'),
        'Unknown_Location_Stock'   => $part->get('Unknown Location Stock'),
        'Stock_Found_SKOs'         => $part->get('Stock Found SKOs'),
        'Stock_Errors_SKOs'        => $part->get('Stock Errors SKOs'),
        'Stock_Damaged_SKOs'       => $part->get('Stock Damaged SKOs'),
        'Stock_Lost_SKOs'          => $part->get('Stock Lost SKOs'),


    );


    echo json_encode($response);

}


function send_to_production($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location_data = array(
        'Location Key' => $data['location_key'],
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );


    $part_location = new PartLocation('find', $part_location_data, 'create');


    $_data = array(
        'Quantity'         => -$data['qty'],
        'Transaction Type' => 'Production',
        'Note'             => $data['note']
    );

    $part_location->stock_transfer($_data);


    $part = get_object('Part', $data['part_sku']);


    $smarty->assign('part_sku', $part->id);
    $smarty->assign('part', $part);

    $smarty->assign('locations_data', $part->get_locations('data'));


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));

    $part_locations = $smarty->fetch('part_locations.edit.tpl');


    $response['Part_Unknown_Location_Stock'] = $part->get('Part Unknown Location Stock');


    $response['updated_fields'] = array(
        'Current_On_Hand_Stock'    => $part->get('Current On Hand Stock'),
        'Stock_Status_Icon'        => $part->get('Stock Status Icon'),
        'Current_Stock'            => $part->get('Current Stock'),
        'Current_Stock_Picked'     => $part->get('Current Stock Picked'),
        'Current_Stock_In_Process' => $part->get('Current Stock In Process'),
        'Current_Stock_Available'  => $part->get('Current Stock Available'),
        'Available_Forecast'       => $part->get('Available Forecast'),
        'Part_Locations'           => $part_locations,
        'Part_Status'              => $part->get('Status'),
        'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse'),
        'Unknown_Location_Stock'   => $part->get('Unknown Location Stock'),
        'Stock_Found_SKOs'         => $part->get('Stock Found SKOs'),
        'Stock_Errors_SKOs'        => $part->get('Stock Errors SKOs'),
        'Stock_Damaged_SKOs'       => $part->get('Stock Damaged SKOs'),
        'Stock_Lost_SKOs'          => $part->get('Stock Lost SKOs'),
    );


    echo json_encode($response);

}


function add_part_to_location($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location_data = array(
        'Location Key' => $data['location_key'],
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );


    $part_location         = new PartLocation('find', $part_location_data, 'create');
    $part_location->editor = $editor;

    $part_location->audit($data['stock'], $data['note'], $editor['Date']);

    $response = array('state' => 200);

    echo json_encode($response);
    exit;


}


function edit_part_location_note($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location         = new PartLocation($data['part_location_code']);
    $part_location->editor = $editor;

    if (!$part_location->ok) {
        $response = array(
            'state' => 400,
            'msg'   => _('Error, please try again').' location part not associated'
        );


        echo json_encode($response);
        exit;

    }

    $part_location->update_note($data['note']);


    $response = array(
        'state' => 200,
        'value' => $part_location->get('Part Location Note')
    );
    echo json_encode($response);


}

function itf_cost($account, $db, $user, $editor, $data, $smarty) {


    $sql = sprintf('SELECT `Inventory Transaction Key`,`Part SKU`,`Inventory Transaction Quantity`,`Inventory Transaction Amount` FROM `Inventory Transaction Fact`  WHERE `Inventory Transaction Key`=%d ', $data['key']);
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $amount = $data['value'];

            $sql = sprintf(
                'UPDATE `Inventory Transaction Fact` SET `Inventory Transaction Amount`=%.2f WHERE `Inventory Transaction Key`=%d ', $amount, $row['Inventory Transaction Key']
            );

            $itf_key=$row['Inventory Transaction Key'];

            $db->exec($sql);
            $part = get_object('Part', $row['Part SKU']);


            $part->update_stock_run();


            $cost_per_sko = $amount / $row['Inventory Transaction Quantity'];

            $cost     = sprintf(
                '<span  class="part_cost button"  data-itf_key="%d" data-cost="%s"  data-skos="%s"  data-currency_symbol="%s"  data-cost_per_sko="%s" onClick="open_edit_cost(this)">%s</span>', $row['Inventory Transaction Key'], $amount,
                $row['Inventory Transaction Quantity'], $account->get('Account Currency Symbol'), money($cost_per_sko, $account->get('Account Currency Code')), money($amount, $account->get('Account Currency Code'))
            );
            $sko_cost = sprintf(
                '<span  class="part_cost_per_sko "  >%s</span>', money($cost_per_sko, $account->get('Account Currency Code'))
            );


            $response = array(
                'state'                  => 200,
                'part_cost_cell'         => $cost,
                'part_cost_per_sko_cell' => $sko_cost,
                'class_html'             => array(
                    'Part_Cost_in_Warehouse' => $part->get('Cost in Warehouse')


                )
            );
            stand_alone_process_aiku_fetch($db,'OrgStockMovement',$itf_key);


            echo json_encode($response);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


?>
