<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 July 2016 at 13:51:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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
//print_r($_REQUEST);

$tipo = $_REQUEST['tipo'];

switch ($tipo) {
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
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function new_part_location($account, $db, $user, $editor, $data, $smarty) {

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
                    '<i class="fa fa-fw  fa-hdd-o" aria-hidden="true" title="%s"></i>', _('Storing')
                );
                break;
            default:
                $used_for = sprintf(
                    '<i class="fa fa-fw  fa-shopping-basket" aria-hidden="true" title="%s"></i>', $part_location->part->get('Location Mainly Used For')
                );
        }
*/

        $picking_location_icon=sprintf('<i onclick="set_as_picking_location(%d,%d)" class="fa fa-fw fa-shopping-basket %s" aria-hidden="true" title="%s" ></i></span>',
                                       $part_location->part->id,
                                       $part_location->location->id,
                                       ($part_location->get('Can Pick')=='Yes'?'':'super_discreet_on_hover button'),
                                       ($part_location->get('Can Pick')=='Yes'?_('Picking location'):_('Set as picking location'))

        );



        $response = array(
            'state'                  => 200,
            'part_sku'               => $part_location->part->id,
            'location_key'           => $part_location->location->id,
            'location_code'          => $part_location->location->get('Code'),
            'formatted_stock'        => number($part_location->get('Quantity On Hand'), 3),
            'stock'                  => ($part_location->get('Quantity On Hand') == 0 ? '' : $part_location->get('Quantity On Hand')),
           'picking_location_icon' => $picking_location_icon,
           // 'location_used_for'      => $part_location->location->get('Location Mainly Used For'),
            'location_link'          => sprintf('locations/%d/%d', $part_location->location->get('Warehouse Key'), $part_location->location->id),

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
            'msg'   => _(
                'Location already associated with the part'
            )
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

    $part_location->audit(
        $data['qty'], $data['note'], $editor['Date']
    );


    $update_metadata['location_part_stock_cell'] = sprintf(
        '<span  class="table_edit_cell location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onClick="open_location_part_stock_quantity_dialog(this)">%s</span>', '',
        $data['part_sku'], $data['location_key'], $part_location->get('Quantity On Hand'), number($part_location->get('Quantity On Hand'))
    );


    $response = array(
        'state'           => 200,
        'update_metadata' => $update_metadata
    );
    echo json_encode($response);


}

function edit_stock($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $parts_locations_data = $data['parts_locations_data'];

    $movement = $data['movements'];

    if (isset($movement['part_sku']) and isset($movement['from_location_key'])) {

        $part_location_from         = new PartLocation($movement['part_sku'], $movement['from_location_key']);
        $part_location_from->editor = $editor;


        if ($part_location_from->get('Quantity On Hand') != $movement['from_location_stock']) {
            $part_location_from->audit(
                $movement['from_location_stock'], ' '.$part_location_from->get('Quantity On Hand').'->'.$movement['from_location_stock'], $editor['Date']
            );

        }

        $part_location_to         = new PartLocation(
            $movement['part_sku'], $movement['to_location_key']
        );
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
            ), $editor['Date']
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
            if ($parts_locations_data[$key]['qty'] != $part_location->get(
                    'Quantity On Hand'
                ) or $parts_locations_data[$key]['audit']
            ) {


                $part_location->audit(
                    $parts_locations_data[$key]['qty'], (isset($parts_locations_data[$key]['note']) ? $parts_locations_data[$key]['note'] : ''), $editor['Date']
                );
            }
        }
    }


    $response = array('state' => 200);

    if ($data['object'] == 'part') {
        $part = get_object(
            $data['object'], $data['key'], $load_other_data = true
        );

        $smarty->assign('part_sku', $part->id);

        $smarty->assign('locations_data', $part->get_locations('data'));
        $part_locations = $smarty->fetch('part_locations.edit.tpl');

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
            'Part_Cost_in_Warehouse'   => $part->get('Cost in Warehouse')


        );
    }


    echo json_encode($response);


}


function place_part($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.PartLocation.php';


    $part_location_data = array(
        'Location Key' => $data['location_key'],
        'Part SKU'     => $data['part_sku'],
        'editor'       => $editor
    );

    $object = get_object($data['object'], $data['key']);
    $origin = sprintf(
        '<span class="link" onClick="change_view(\'delivery/%d\')" ><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> %s</span>', $object->id, $object->get('Public ID')
    );

    if ($data['note'] != '') {
        $origin .= ' <i class="fa fa-sticky-note-o" aria-hidden="true"></i> '.$data['note'];
    }

    $sql = sprintf(
        'SELECT `Purchase Order Transaction Fact Key`,`Supplier Delivery Checked Quantity`,`Supplier Delivery Placed Quantity` ,`Supplier Part Packages Per Carton`
	FROM
	  `Purchase Order Transaction Fact` POTF
LEFT JOIN `Supplier Part Historic Dimension` SPH ON (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 LEFT JOIN  `Supplier Part Dimension` SP ON (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

	 WHERE `Purchase Order Transaction Fact Key`=%d', $data['transaction_key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            if ($row['Supplier Delivery Placed Quantity'] == '') {
                $row['Supplier Delivery Placed Quantity'] = 0;
            }

            $part_location = new PartLocation(
                'find', $part_location_data, 'create'
            );

            if (!$part_location) {
                $response = array(
                    'state' => 400,
                    'msg'   => $part_location->msg
                );
                echo json_encode($response);
                exit;
            } else {


                if (($data['qty'] / $row['Supplier Part Packages Per Carton']) > ($row['Supplier Delivery Checked Quantity'] - $row['Supplier Delivery Placed Quantity'])) {
                    $response = array(
                        'state' => 400,
                        'msg'   => _(
                            'Placement quantity greater than the checked quantity'
                        )
                    );
                    echo json_encode($response);
                    exit;
                }


                $oif_key = $part_location->add_stock(
                    array(
                        'Quantity' => $data['qty'],
                        'Origin'   => $origin
                    ), gmdate('Y-m-d H:i:s')
                );


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
                        'wk'      => $part_location->location->get(
                            'Location Warehouse Key'
                        ),
                        'lk'      => $part_location->location->id,
                        'l'       => $part_location->location->get('Code'),
                        'qty'     => $data['qty']
                    )
                );


                $result_placement = $object->update_item_delivery_placed_quantity($_data);

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
                    'SELECT L.`Location Key`,L.`Location Code`,`Can Pick`,`Quantity On Hand` FROM `Part Location Dimension` PLD  LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PLD.`Location Key`) WHERE PLD.`Part SKU`=%d',
                    $data['part_sku']
                );
                if ($result = $db->query($sql)) {
                    foreach ($result as $row) {
                        $number_part_locations++;
                        $part_locations .= '<div class="button" onClick="set_placement_location(this)" style="clear:both;"  location_key="'.$row['Location Key'].'"><div  class="code data w150"  >'
                            .$row['Location Code'].'</div><div class="data w30 aright" >'.number(
                                $row['Quantity On Hand']
                            ).'</div></div>';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
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

            }

        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'po_transaction not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
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
                $part_location->update(array('Part Location Can Pick' => 'Yes'));
            } else {
                $part_location->update(array('Part Location Can Pick' => 'No'));
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


    echo json_encode($response);


}

?>
