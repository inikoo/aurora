<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:38:12 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';

require_once 'utils/object_functions.php';

if (!$user->can_view('locations')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


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
    case 'external_warehouse_replenishes':
        external_warehouse_replenishes(get_table_parameters(), $db, $user);
        break;
    case 'parts_to_replenish_picking_location':
        part_locations_to_replenish_picking_location(get_table_parameters(), $db, $user);
        break;
    case 'warehouses':
        warehouses(get_table_parameters(), $db, $user);
        break;
    case 'areas':
        areas(get_table_parameters(), $db, $user);
        break;
    case 'locations':
        locations(get_table_parameters(), $db, $user, $account);
        break;
    case 'replenishments':
        replenishments(get_table_parameters(), $db, $user);
        break;
    case 'part_locations':
    case 'parts':
        parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock_transactions':
        stock_transactions(get_table_parameters(), $db, $user);
        break;
    case 'part_locations_with_errors':
        part_locations_with_errors(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock_leakages':
        stock_leakages(get_table_parameters(), $db, $user, $account);
        break;
    case 'leakages_transactions':
        leakages_transactions(get_table_parameters(), $db, $user, $account);
        break;
    case 'warehouse.parts_with_unknown_location':
        parts_with_unknown_location(get_table_parameters(), $db, $user, $account);
        break;
    case 'shippers':
        shippers(get_table_parameters(), $db, $user, $account);
        break;
    case 'returns':
        returns(get_table_parameters(), $db, $user, $account);
        break;
    case 'return.checking_items':
        return_checking_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'return.items_done':
        return_items_done(get_table_parameters(), $db, $user, $account);
        break;
    case 'consignments':
        consignments(get_table_parameters(), $db, $user, $account);
        break;
    case 'feedback':
        feedback(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_deliveries':
        production_deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'deleted_locations':
        deleted_locations(get_table_parameters(), $db, $user, $account);
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

function warehouses($_data, $db, $user) {

    $rtext_label = 'warehouse';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'access' => (in_array($data['Warehouse Key'], $user->warehouses) ? '' : '<i class="fa fa-lock error"></i>'),
            'id'     => (integer)$data['Warehouse Key'],
            'code'   => $data['Warehouse Code'],
            'name'   => $data['Warehouse Name'],
        );

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

function areas($_data, $db, $user) {

    $rtext_label = 'warehouse area';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    foreach ($db->query($sql) as $data) {

        if ($data['Warehouse Area Place'] == 'External') {
            $type = ' <i  title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car   "></i>';
        } else {
            $type = '';
        }

        $adata[] = array(
            'access'    => (in_array($data['Warehouse Area Warehouse Key'], $user->warehouses) ? '' : '<i class="fa fa-lock error"></i>'),
            'id'        => (integer)$data['Warehouse Area Key'],
            'type'      => $type,
            'code'      => sprintf(
                '<span class="link" onClick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Warehouse Area Warehouse Key'], $data['Warehouse Area Key'], $data['Warehouse Area Code']
            ),
            'name'      => $data['Warehouse Area Name'],
            'locations' => number($data['Warehouse Area Number Locations']),
            'parts'     => number($data['Warehouse Area Distinct Parts']),
        );

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


function locations($_data, $db, $user, $account) {


    $rtext_label = 'location';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    // print_r($_data);

    switch ($_data['parameters']['parent']) {
        case 'warehouse_area':

            $warehouse_area = get_object('WarehouseArea', $_data['parameters']['parent_key']);

            $link = 'warehouse/'.$warehouse_area->get('Warehouse Key').'/areas/'.$_data['parameters']['parent_key'].'/location/';
            break;
        case 'warehouse':
            $link = 'locations/'.$_data['parameters']['parent_key'].'/';
            break;
    }


    foreach ($db->query($sql) as $data) {


        if ($data['Location Max Weight'] == '' or $data['Location Max Weight'] <= 0) {
            $max_weight = '<span class="super_discreet italic">'._('Unknown').'</span>';
        } else {
            $max_weight = number($data['Location Max Weight'])._('Kg');
        }
        if ($data['Location Max Volume'] == '' or $data['Location Max Volume'] <= 0) {
            $max_vol = '<span class="super_discreet italic">'._('Unknown').'</span>';
        } else {
            $max_vol = number($data['Location Max Volume']).'m³';
        }


        $code = sprintf('<span class="link" onclick="change_view(\'%s/%d\')">%s</span>', $link, $data['Location Key'], $data['Location Code']);
        $area = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Warehouse Area Key'], $data['Warehouse Area Code']);

        if ($data['Location Place'] == 'External') {
            $type = ' <i  title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car   "></i>';
        } else {
            $type = '';
        }


        $adata[] = array(
            'id'          => (integer)$data['Location Key'],
            'code'        => $code,
            'flag'        => ($data['Warehouse Flag Key'] ? sprintf(
                '<i id="flag_location_%d" class="fa fa-flag %s button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" location_key="%d" title="%s"></i>', $data['Location Key'], strtolower($data['Warehouse Flag Color']), $data['Location Key'],
                $data['Warehouse Flag Label']
            ) : '<i id="flag_location_'.$data['Location Key'].'"  class="far fa-flag super_discreet button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" key="" ></i>'),
            'flag_key'    => $data['Warehouse Flag Key'],
            'area'        => $area,
            'max_weight'  => $max_weight,
            'max_volume'  => $max_vol,
            'type'        => $type,
            'parts'       => number($data['Location Distinct Parts']),
            'stock_value' => money($data['Location Stock Value'], $account->get('Account Currency')),

            // 'used_for'           => $used_for
        );

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


function replenishments($_data, $db, $user) {


    $rtext_label = 'replenishment';
    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {

        $locations_data = preg_split('/,/', $data['location_data']);


        $stock = '<div border=0 style="xwidth:150px">';

        foreach ($locations_data as $raw_location_data) {
            if ($raw_location_data != '') {
                $_locations_data = preg_split('/\:/', $raw_location_data);
                if ($_locations_data[0] != $data['Location Key']) {
                    $stock .= '<div style="clear:both">';
                    $stock .= '<div style="float:left;min-width:100px;">
<span class="link"  onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$_locations_data[0].'\')" >'.$_locations_data[1].'</span>
</div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                    $stock .= '</div>';
                }
            }
        }
        $stock .= '</div>';


        if ($data['Part Next Deliveries Data'] == '') {
            $next_deliveries_array = array();
        } else {
            $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
        }


        $next_deliveries = '';

        foreach ($next_deliveries_array as $next_delivery) {


            $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_5" style="min-width: 100px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_10 as_cell strong" style="text-align: right;min-width: 40px" title="'._('SKOs ordered')
                .'">+'.number(
                    $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


        }


        $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


        $reference = sprintf(
            '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
            ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );

        if ($data['Part Symbol'] != '') {
            if ($data['Part Symbol'] != '') {

                switch ($data['Part Symbol']) {
                    case 'star':
                        $symbol = '&#9733;';
                        break;

                    case 'skull':
                        $symbol = '&#9760;';
                        break;
                    case 'radioactive':
                        $symbol = '&#9762;';
                        break;
                    case 'peace':
                        $symbol = '&#9774;';
                        break;
                    case 'sad':
                        $symbol = '&#9785;';
                        break;
                    case 'gear':
                        $symbol = '&#9881;';
                        break;
                    case 'love':
                        $symbol = '&#10084;';
                        break;
                    default:
                        $symbol = '';

                }
                $reference .= ' '.$symbol;
            }

        }


        $adata[] = array(
            'id'                    => (integer)$data['Location Key'],
            'location'              => ($data['Warehouse Flag Key'] ? sprintf(
                    '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
                ) : '<i class="far fa-flag super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key'].'\')">'.$data['Location Code'].'</span>',
            'part'                  => $reference,
            'other_locations_stock' => $stock,

            'quantity'             => number($data['Quantity On Hand']),
            'ordered_quantity'     => number($data['ordered_quantity']),
            'effective_stock'      => number($data['effective_stock']),
            'recommended_quantity' => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity']).'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number(
                    $data['Maximum Quantity']
                ).'</span>)</span>',
            'next_deliveries'      => $next_deliveries

        );

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


function parts($_data, $db, $user, $account) {


    if ($_data['parameters']['tab'] == 'warehouse.parts') {
        $rtext_label = 'part location';
    } else {
        $rtext_label = 'part';

    }


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {

        $reference = sprintf(
            '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );


        $adata[] = array(


            'reference' => $reference,
            'location'  => sprintf('<span class="link" onCLick="change_view(\'locations/%d/%d\')" >%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'sko_description' => $data['Part Package Description'],


            'can_pick' => ($data['Can Pick'] == 'Yes' ? _('Yes') : _('No')),

            'link' => '<span id="link_'.$data['Part SKU'].'"><i class="fa fa-unlink '.($data['Quantity On Hand'] != 0 ? 'invisible' : 'button').'" aria-hidden="true" part_sku="'.$data['Part SKU'].'" onclick="location_part_disassociate_from_table(this)"></i>',


            'sko_cost'    => money($data['Part Cost in Warehouse'], $account->get('Account Currency')),
            'stock_value' => '<span id="stock_value_'.$data['Part SKU'].'">'.money($data['Stock Value'], $account->get('Account Currency')).'</span>',
            'quantity'    => sprintf(
                '<span id="quantity_'.$data['Part SKU']
                .'"><span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell  location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onClick="open_location_part_stock_quantity_dialog(this)">%s</span></span>', '', $data['Part SKU'],
                $data['Location Key'], $data['Quantity On Hand'], number($data['Quantity On Hand'])
            )


        );

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


function stock_transactions($_data, $db, $user) {


    $rtext_label = 'transaction';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            //MossRB-04 227330 Taken from: 11A1

            $note  = $data['Note'];
            $stock = $data['Inventory Transaction Quantity'];
            switch ($data['Inventory Transaction Type']) {
                case 'OIP':
                    $type = '<i class="fa  fa-clock discreet fa-fw" aria-hidden="true"></i>';

                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _('%s %s (%s) to be taken from %s'),

                            number($data['Required']), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-fw fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'locations/%d/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Key'], $data['Location Code']
                            )


                        );
                    } else {
                        $note = sprintf(
                            _('%sx %s (%s) to be taken from %s'), number($data['Required']),

                            ($parameters['parent'] == 'part'
                                ? sprintf(
                                    '<i class="fa fa-box" aria-hidden="true"></i> %s', $data['Part Reference']
                                )
                                : sprintf(
                                    '<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference']
                                )), sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'locations/%d/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Key'], $data['Location Code']
                            )

                        );
                    }


                    break;
                case 'Restock':
                    $type = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    $note = sprintf(
                        _('%s (%s) returned from %s'), number($data['Inventory Transaction Quantity']).'  <span title="'._('Stock keeping outers').'">SKO</span>',

                        sprintf(
                            '<span class="button strong" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']
                        )


                        , sprintf(
                            '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                        ), sprintf(
                            '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                        )

                    );
                    break;
                case 'FailSale':
                case 'Sale':
                    $type = '<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>';

                    $note = sprintf(
                        _('%s (%s) picked for %s'), number(-1 * $data['Inventory Transaction Quantity']).'  <span title="'._('Stock keeping outers').'">SKO</span>',

                        sprintf(
                            '<span class="button strong" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']
                        )


                        , sprintf(
                            '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                        ), sprintf(
                            '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                        )

                    );

                    if ($data['Inventory Transaction Type'] == 'FailSale') {
                        $note .= ' <span class="warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '._('Returned').'</span>';
                    }

                    break;
                case 'In':
                    $type = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Audit':


                    $type = '<i class="fa fa-fw fa-dot-circle" aria-hidden="true"></i>';

                    $stock = sprintf('<b>'.$data['Part Location Stock'].'</b>');
                    break;
                case 'Adjust':

                    if ($stock > 0) {
                        $stock = '+'.number($stock);
                    }

                    $type = '<i class="fa fa-fw fa-sliders" aria-hidden="true"></i>';


                    break;

                case 'Move':
                    $stock = '±'.number($data['Metadata']);
                    $type  = '<i class="fa fa-sync fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $type = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $type = $data['Inventory Transaction Section'];
                    break;
            }


            $adata[] = array(
                'id'   => (integer)$data['Inventory Transaction Key'],
                'date' => strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')
                ),
                'user' => sprintf(
                    '<span title="%s">%s</span>', $data['User Alias'], $data['User Handle']
                ),

                'change' => $stock,
                'note'   => $note,
                'type'   => $type,

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
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

function part_locations_to_replenish_picking_location($_data, $db, $user) {


    $rtext_label = 'picking locations needed to replenish for ordered parts';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    foreach ($db->query($sql) as $data) {

        //print_r($data);
        $storing_locations = '';
        if ($data['storing_locations_data'] != '') {
            foreach (preg_split('/\,/', $data['storing_locations_data']) as $storing_locations_data_set) {
                $storing_locations_data = preg_split('/\|/', $storing_locations_data_set);

                if ($storing_locations_data[1] == 1) {
                    $storing_locations .= ', '.sprintf(
                            '<span   >%s</span> <span class="discreet" title="'._('Stock').'">(%s)</span>', $storing_locations_data[2], number($storing_locations_data[3])
                        );
                } else {
                    $storing_locations .= ', '.sprintf(
                            '<span  class="link"  onclick="change_view(\'locations/%d/%d\')">%s</span> <span class="discreet" title="'._('Stock').'">(%s)</span>', $storing_locations_data[0], $storing_locations_data[1], $storing_locations_data[2],
                            number($storing_locations_data[3])
                        );
                }


            }
        }

        $storing_locations = preg_replace('/^, /', '', $storing_locations);


        if ($data['Part Next Deliveries Data'] == '') {
            $next_deliveries_array = array();
        } else {
            $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
        }


        $next_deliveries = '';

        foreach ($next_deliveries_array as $next_delivery) {


            $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_5" style="min-width: 120px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_10 as_cell strong" style="text-align: right;min-width: 60px" title="'._('SKOs ordered')
                .'">+'.number(
                    $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


        }


        $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


        $reference = sprintf(
            '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
            ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );


        $table_data[] = array(
            'reference' => $reference,
            'location'  => sprintf('<span  class="link"  onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'quantity_in_picking' => number(floor($data['Quantity On Hand'])),
            'to_pick'             => number(ceil($data['to_pick'])),

            'total_stock'        => number(floor($data['Part Current On Hand Stock'])),
            '_storing_locations' => $storing_locations,
            'next_deliveries'    => $next_deliveries

        );

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

function part_locations_with_errors($_data, $db, $user) {


    $rtext_label = 'part location with errors';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    //print $sql;


    foreach ($db->query($sql) as $data) {

        $reference = sprintf(
            '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
            ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );


        $locations_data = preg_split('/,/', $data['other_locations']);


        $other_locations = '<div border=0 style="xwidth:150px">';

        foreach ($locations_data as $raw_location_data) {
            if ($raw_location_data != '') {
                $_locations_data = preg_split('/\|/', $raw_location_data);


                if ($_locations_data[0] != $data['Location Key']) {
                    $other_locations .= '<div style="clear:both">';
                    $other_locations .= '<div style="float:left;min-width:100px;">
<span class="link"  onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$_locations_data[1].'\')" >'.$_locations_data[2].'</span>
</div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                    $other_locations .= '</div>';
                }
            }
        }
        $other_locations .= '</div>';


        $table_data[] = array(

            'reference' => $reference,
            'location'  => sprintf('<span  class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),

            'other_locations' => $other_locations,
            'can_pick'        => ($data['Can Pick'] == 'Yes' ? _('Yes') : _('No')),
            'quantity'        => '<span class="error">'.number($data['Quantity On Hand']),
            '</span>'

        );

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


function stock_leakages($_data, $db, $user, $account) {


    $skip_get_table_totals = true;


    //print_r($_data);

    include_once 'prepare_table/init.php';

    include_once 'utils/natural_language.php';
    include_once 'class.Store.php';

    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label       = 'year';
        $_group_by         = ' group by Year(`Date`) ';
        $sql_totals_fields = 'Year(`Date`)';
    } elseif ($_data['parameters']['frequency'] == 'quarterly') {
        $rtext_label       = 'quarter';
        $_group_by         = '  group by YEAR(`Date`), QUARTER(`Date`) ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y %q")';
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label       = 'month';
        $_group_by         = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label       = 'week';
        $_group_by         = ' group by Yearweek(`Date`,3) ';
        $sql_totals_fields = 'Yearweek(`Date`,3)';
    } elseif ($_data['parameters']['frequency'] == 'daily') {
        $rtext_label = 'day';

        $_group_by         = ' group by Date(`Date`) ';
        $sql_totals_fields = '`Date`';
    }

    switch ($_data['parameters']['parent']) {
        case 'warehouse':
            $warehouse  = get_object('Warehouse', $_data['parameters']['parent_key']);
            $currency   = $account->get('Account Currency');
            $from       = ($warehouse->get('Warehouse Leakage Timeseries From') != '' ? $warehouse->get('Warehouse Leakage Timeseries From') : $warehouse->get('Warehouse Valid From'));
            $to         = gmdate('Y-m-d');
            $date_field = '`Timeseries Record Date`';
            break;

        default:
            print_r($_data);
            exit('parent not configured '.$_data['parameters']['parent']);
            break;
    }


    $sql_totals = sprintf(
        'SELECT count(DISTINCT %s) AS num FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s) ', $sql_totals_fields, prepare_mysql($from), prepare_mysql($to)

    );

    //print $sql;

    list($rtext, $total, $filtered) = get_table_totals(
        $db, $sql_totals, '', $rtext_label, false
    );


    $sql = sprintf(
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s', prepare_mysql($from), prepare_mysql($to), $_group_by, "`Date` $order_direction ", "$start_from,$number_results"
    );


    $record_data = array();

    $from_date = '';
    $to_date   = '';
    if ($result = $db->query($sql)) {


        foreach ($result as $data) {

            if ($to_date == '') {
                $to_date = $data['Date'];
            }
            $from_date = $data['Date'];


            if ($_data['parameters']['frequency'] == 'annually') {
                $date  = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $date  = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {


                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = strftime("%b %Y", strtotime($data['Date'].' +0:00'));

            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
                $_date = date('Y-m-d', strtotime($data['Date'].' +0:00'));
            }


            $record_data[$_date] = array(
                'up_amount'              => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'down_amount'            => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'up_commercial_amount'   => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'down_commercial_amount' => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'up_transactions'        => '<span class="very_discreet">'.number(0).'</span>',
                'down_transactions'      => '<span class="very_discreet">'.number(0).'</span>',

                'date' => $date


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    switch ($_data['parameters']['parent']) {

        case 'warehouse':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate("Y-01-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.'  -1 year  +0:00'));
                $to_date   = gmdate("Y-m-01", strtotime($to_date.' + 3 month +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.' -1 year  +0:00'));
                $to_date   = gmdate("Y-m-01", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate("Y-m-d", strtotime($from_date.'  -1 year  +0:00'));
                $to_date   = gmdate("Y-m-d", strtotime($to_date.'  +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = gmdate("Y-m-d", strtotime($from_date.' - 1 year +0:00'));
                $to_date   = $to_date;
            }
            $group_by = '';

            break;
        default:
            print_r($_data);
            exit('Parent not configured '.$_data['parameters']['parent']);
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s order by $date_field    ", $date_field, prepare_mysql($from_date), $date_field, prepare_mysql($to_date), " $group_by "
    );

    $last_year_data = array();


    //print $sql;
    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            if ($_data['parameters']['frequency'] == 'annually') {
                $_date           = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $_date           = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = 'Q'.ceil(date('n', strtotime($data['Date'].' - 1 year')) / 3).' '.strftime("%Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $_date           = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%b %Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $_date           = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y%W ", strtotime($data['Date'].' - 1 year'));
                $date            = strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $_date           = date('Y-m-d', strtotime($data['Date'].' +0:00'));
                $_date_last_year = date('Y-m-d', strtotime($data['Date'].'  -1 year'));
                $date            = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
            }

            $last_year_data[$_date] = array('_up_amount' => $data['up_amount']);


            if (array_key_exists($_date, $record_data)) {


                if (in_array(
                    $_data['parameters']['frequency'], array(
                                                         'annually',
                                                         'quarterly',
                                                         'monthly',
                                                         'weekly',
                                                         'daily'
                                                     )
                )) {
                    $up_amount = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/leakages/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'], money($data['up_amount'], $currency)
                    );

                    $down_amount       = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/leakages/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'], '<span class="error">'.money($data['down_amount'], $currency).'</span>'
                    );
                    $up_transactions   = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/leakages/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'], number($data['up_transactions'])
                    );
                    $down_transactions = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/leakages/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'], '<span class="error">'.number($data['down_transactions']).'</span>'
                    );


                } else {
                    $up_amount         = money($data['up_amount'], $currency);
                    $down_amount       = '<span class="error">'.money($data['down_amount'], $currency).'</span>';
                    $up_transactions   = number($data['up_transactions']);
                    $down_transactions = '<span class="error">'.number($data['down_transactions']).'</span>';

                }

                $record_data[$_date]['up_amount']         = $up_amount;
                $record_data[$_date]['down_amount']       = $down_amount;
                $record_data[$_date]['up_transactions']   = $up_transactions;
                $record_data[$_date]['down_transactions'] = $down_transactions;


            }


            if (isset($last_year_data[$_date_last_year])) {
                $record_data[$_date]['delta_up_amount_1yb'] = '<span title="'.money($last_year_data[$_date_last_year]['_up_amount'], $currency).'">'.delta($data['up_amount'], $last_year_data[$_date_last_year]['_up_amount']).' '.delta_icon(
                        $data['up_amount'], $last_year_data[$_date_last_year]['_up_amount']
                    ).'</span>';
            }

            //    print_r($record_data);
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => array_values($record_data),
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function leakages_transactions($_data, $db, $user, $account) {


    $rtext_label = 'transaction';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $note = $data['Note'];

            if ($data['Inventory Transaction Quantity'] > 0) {
                $stock = '+'.number($data['Inventory Transaction Quantity']);
            } else {
                $stock = number($data['Inventory Transaction Quantity']);

            }
            if ($data['Inventory Transaction Amount'] > 0) {
                $amount = '+'.money($data['Inventory Transaction Amount'], $account->get('Currency Code'));
            } else {
                $amount = money($data['Inventory Transaction Amount'], $account->get('Currency Code'));

            }

            // $type =

            if ($data['Part Reference'] == '') {
                $reference = sprintf('<span class="very_discreet italic" >%s</span>', _('deleted'));
                $note      = preg_replace('/note_data/', '', $note);
            } else {
                $reference = sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']);

            }


            $record_data[] = array(
                'id'          => (integer)$data['Inventory Transaction Key'],
                'reference'   => $reference,
                'description' => $data['Part Package Description'],
                'note'        => $note,

                'location' => sprintf('<span class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Warehouse Key'], $data['Location Key'], $data['Location Code']),

                'date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')),
                'user' => sprintf('<span title="%s">%s</span>', $data['User Alias'], ucwords($data['User Handle'])),

                'change'        => $stock,
                'change_amount' => $amount,

                //   'note'   => $note,
                //   'type'   => $type,

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function parts_with_unknown_location($_data, $db, $user, $account) {


    $rtext_label = 'part';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {

        if ($data['Part Status'] == 'Not In Use') {
            $part_status = '<i class="far fa-box fa-fw  very_discreet" aria-hidden="true"></i> ';

        } elseif ($data['Part Status'] == 'Discontinuing') {
            $part_status = '<i class="far fa-box fa-fw  very_discreet" aria-hidden="true"></i> ';

        } else {
            $part_status = '<i class="far fa-box fa-fw " aria-hidden="true"></i> ';
        }


        $locations_data = preg_split('/,/', $data['location_data']);


        $locations = '<div border=0 style="xwidth:150px">';


        foreach ($locations_data as $raw_location_data) {
            if ($raw_location_data != '') {
                $_locations_data = preg_split('/\:/', $raw_location_data);

                if ($_locations_data[2] == '') {
                    $last_audit = '<span title="'._('Never been audited').'">-</span> <i class="far fa-clock padding_right_10" aria-hidden="true"></i> ';
                } else {
                    $last_audit = sprintf(
                            '<span title="%s">%s</span>', sprintf(_('Last audit %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($_locations_data[2].' +0:00')), $_locations_data[2]),
                            ($_locations_data[4] > 999 ? '<span class="error">+999</span>' : number($_locations_data[4]))
                        ).' <i class="far fa-clock padding_right_10" aria-hidden="true"></i>';


                }


                if ($_locations_data[0] != $data['Location Key']) {
                    $locations .= '<div style="clear:both">';
                    $locations .= '<div style="float:left;min-width:100px;"><span class="link"  onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$_locations_data[0].'\')" >'.$_locations_data[1].'</span></div>
                                   <div style="float:left;min-width:50px;text-align:right">'.$last_audit.'</div>
                                   <div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                    $locations .= '</div>';
                }
            }
        }
        $locations .= '</div>';


        $reference = sprintf(
            '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
            ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );


        $adata[] = array(


            'reference' => $reference,


            'description' => $data['Part Package Description'],

            'part_status' => $part_status,
            'locations'   => $locations,

            'sko_cost'    => money($data['Part Cost in Warehouse'], $account->get('Account Currency')),
            'stock_value' => money($data['Stock Value'], $account->get('Account Currency')),
            'quantity'    => sprintf(
                '<span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell  location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" >%s</span>', '', $data['Part SKU'], 1, $data['Quantity On Hand'],
                '<strong class="'.($data['Quantity On Hand'] < 0 ? 'success' : 'error').'" >'.($data['Quantity On Hand'] < 0 ? '+' : '').number(-$data['Quantity On Hand']).'</strong>'
            )


        );

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


function shippers($_data, $db, $user, $account) {


    $rtext_label = 'shipping company';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Shipper Status']) {
                case 'Active':
                    $status = sprintf('<i class="fa fa-play success" title="%s"></i>', _('Active'));

                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-pause discreet error" title="%s"></i>', _('Suspended'));
                    break;
                default:
                    $status = '';
            }


            $code = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/shippers/%d\')">%s</span>', $data['Shipper Warehouse Key'], $data['Shipper Key'], $data['Shipper Code']);


            $record_data[] = array(
                'id'               => (integer)$data['Shipper Key'],
                'code'             => $code,
                'status'           => $status,
                'name'             => $data['Shipper Name'],
                'consignments'     => number($data['Shipper Consignments']),
                'parcels'          => number($data['Shipper Number Parcels']),
                'weight'           => weight($data['Shipper Dispatched Weight']),
                'last_consignment' => ($data['Shipper Last Consignment'] == '' ? '' : strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Shipper Last Consignment'].' +0:00'))),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function returns($_data, $db, $user) {
    $rtext_label = 'delivery';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {

                case 'Received':
                    $state = sprintf('%s', _('Received'));
                    break;
                case 'Checked':
                    $state = sprintf('%s', _('Checked'));
                    break;
                case 'Placed':

                    $state = _('Booked in');
                    break;
                case 'Costing':
                case 'InvoiceChecked':
                    $state = _('Booked in');
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;
                case 'Dispatched':
                    $state = _('Waiting to receive return');
                    break;
                default:
                    $state = $data['Supplier Delivery State'];
                    break;
            }

            $table_data[] = array(
                'id'        => (integer)$data['Supplier Delivery Key'],

                //'public_id'   => $data['Supplier Delivery Public ID'],
                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),
                // 'parent_name' => $data['Supplier Delivery Parent Name'],


                'store'     => sprintf('<span class="link" onclick="change_view(\'/store/%d\')" >%s</span>  ', $data['Order Store Key'], $data['Store Code']),
                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'warehouse/%d/returns/%d\')" >%s</span>  ', $data['Supplier Delivery Warehouse Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
                ),


                'state'        => $state,
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

function return_checking_items($_data, $db, $user, $account) {


    $rtext_label = 'item';
    include_once 'utils/supplier_order_functions.php';

    $db->exec('SET SESSION group_concat_max_len = 1000000;');


    $supplier_delivery = get_object('Supplier_Delivery', $_data['parameters']['parent_key']);


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            // $quantity = number($data['Supplier Delivery Units']);


            $data['units_qty'] = $data['Supplier Delivery Units'];

            $data['account_currency_code'] = $account->get('Account Currency');
            $data['currency_code']         = $supplier_delivery->get('Supplier Delivery Currency Code');
            $data['exchange']              = $supplier_delivery->get('Supplier Delivery Currency Exchange');


            $subtotals = sprintf('<span  class="subtotals" >');
            if ($data['Supplier Delivery Units'] > 0) {
                $subtotals .= money(
                    $data['Supplier Delivery Net Amount'], $data['Currency Code']
                );

                if ($data['Supplier Delivery Weight'] > 0) {
                    $subtotals .= ' '.weight($data['Supplier Delivery Weight']);
                }
                if ($data['Supplier Delivery CBM'] > 0) {
                    $subtotals .= ' '.number($data['Supplier Delivery CBM']).' m³';
                }
            }
            $subtotals .= '</span>';


            $description = '<div style="font-size:90%" >';

            $description = $description.'<span >'.$data['Part Units Per Package'].'</span><span class="discreet ">x</span> '.$data['Part Recommended Product Unit Name'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span title="'._('SKOs per carton').'">, sko/C: <b>'.$data['Part Units Per Package'].'</b></span>';


            $number_locations = 0;
            $locations        = '';
            if ($data['location_data'] != '') {
                $locations_data = preg_split('/,/', $data['location_data']);


                $locations = '<div  class="part_locations mini_table left " transaction_key="'.$data['Purchase Order Transaction Fact Key'].'" >';

                foreach ($locations_data as $location_data) {
                    $number_locations++;
                    $location_data = preg_split('/\:/', $location_data);
                    $locations     .= ' <div class="part_location button" style="clear:both;" onClick="set_placement_location(this)"  location_key="'.$location_data[0].'" >
				<div  class="code data w150"  >'.$location_data[1].'</div>
				<div class="data w30 aright" >'.number($location_data[3]).'</div>
				</div>';

                }
                $locations .= '<div style="clear:both"></div></div>';
            }

            if ($locations != '') {
                $description .= '<br><i style="margin-left:4px" class="fa fa-inventory button discreet  hide'.($number_locations == 0 ? 'hide' : '').'" aria-hidden="true" title="'._('Show locations').'"  show_title="'._('Show locations').'" hide_title="'._(
                        'Hide locations'
                    ).'"    onClick="show_part_locations(this)" ></i>';


                $description .= $locations;

            }


            if ($data['Supplier Delivery Checked Units'] == '') {

                $sko_checked_quantity = '';

            } else {

                $sko_checked_quantity = ($data['Supplier Delivery Checked Units'] / $data['Part Units Per Package']) + 0;


            }


            $edit_sko_checked_quantity = sprintf(
                '<span class="%s" ondblclick="show_check_dialog(this)">%s</span>
                <span data-settings=\'{"field": "Supplier Delivery Checked Units", "sko_factor":%d, "transaction_key":%d,"part_sku":%d ,"on":1 }\' class="checked_quantity %s"  >
                    <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                    <input   class="checked_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                    <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button %s" aria-hidden="true">
                </span>', ($supplier_delivery->get('Supplier Delivery State') == 'Placed' ? '' : 'hide'), number($sko_checked_quantity), $data['Part Units Per Package'], $data['Purchase Order Transaction Fact Key'], $data['Part SKU'],
                ($supplier_delivery->get('Supplier Delivery State') == 'Placed' ? 'hide' : ''), $sko_checked_quantity, $sko_checked_quantity, ''
            );


            // print_r($data);

            $quantity = ($data['Supplier Delivery Checked Units'] - $data['Supplier Delivery Placed Units']) / $data['Part Units Per Package'];


            if ($data['Metadata'] == '') {
                $metadata = array();
            } else {
                $metadata = json_decode($data['Metadata'], true);
            }


            $placement = '<div class="placement" ><div  class="placement_data mini_table right no_padding" style="padding-right:2px">';

            if (isset($metadata['placement_data'])) {

                foreach ($metadata['placement_data'] as $placement_data) {


                    $placement .= '<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._('SKO').' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


                }
            }
            $placement      .= '<div style="clear:both"></div></div>';
            $placement_note = '<input type="hidden" class="note" /><i class="far add_note fa-sticky-note padding_right_5 button" aria-hidden="true"  onClick="show_placement_note(this)" ></i>';
            $placement      .= '
			    <div style="clear:both"  id="place_item_'.$data['Purchase Order Transaction Fact Key'].'" class="place_item  '.($data['Supplier Delivery Checked Units'] != '' ? '' : 'invisible').'  '.($data['Supplier Delivery Transaction Placed'] == 'No' ? '' : 'hide')
                .' " part_sku="'.$data['Part SKU'].'" transaction_key="'.$data['Purchase Order Transaction Fact Key'].'"  >

			    '.$placement_note.'

			    <input class="place_qty width_50 changed" value="'.($quantity + 0).'" ovalue="'.($quantity + 0).'"  min="1" max="'.round($quantity, 2).'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="place_item_button  fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                </div>
                </div>
			';

            $items_qty = sprintf(
                '<span  id="part_sko_item_%d"  data-barcode_settings=\'{"reference":"%s","description":"%s" ,"image_src":"%s" ,"units":"%s" ,"formatted_units":"%s"   }\'  _checked="%s"   barcode="%s" data-metadata=\'{"qty":%d}\' onClick="copy_qty(this)" class="button part_sko_item"  >%s</span>',
                $data['Part SKU'], $data['Part Reference'], base64_encode($data['Part Package Description']), $data['Part SKO Image Key'], $data['Supplier Delivery Units'], number($data['Supplier Delivery Units']), $data['Supplier Delivery Checked Units'],


                $data['Part SKO Barcode'], $data['Supplier Delivery Units'] / $data['Part Units Per Package'], number($data['Supplier Delivery Units'] / $data['Part Units Per Package'])

            );


            $table_data[] = array(

                'id'       => (integer)$data['Purchase Order Transaction Fact Key'],
                'part_sku' => (integer)$data['Part SKU'],
                'checkbox' => sprintf(
                    '<i key="%d" class="far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']
                ),

                'operations' => sprintf(
                    '<i key="%d" class="fal fa-fw fa-clipboard fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']
                ),


                'part_reference' => sprintf('<span class="link" onclick="change_view(\'/part/%d\')" >%s</span>  ', $data['Part SKU'], $data['Part Reference']),


                'description' => $description,

                'sko_edit_checked_quantity' => $edit_sko_checked_quantity,
                'sko_checked_quantity'      => number($sko_checked_quantity),
                'subtotals'                 => $subtotals,
                'qty'                       => number($quantity),
                'items_qty'                 => $items_qty,


                'placement' => $placement
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


function return_items_done($_data, $db, $user) {


    $rtext_label = 'part';

    $account = get_object('Account', 1);


    $supplier_delivery = get_object('SupplierDelivery', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref $group_by  order by $order $order_direction  limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $items_amount = money($data['items_amount'], $data['Currency Code']);

            $extra_amount = money($data['extra_amount'], $data['Currency Code']);

            $extra_amount_account_currency = money($data['extra_amount_account_currency'], $account->get('Currency Code'));


            if ($data['skos_in'] != 0) {
                $sko_cost = sprintf('<span id="sko_cost_%d"  class="sko_cost" >%s/sko</span>', $data['Part SKU'], money($data['paid_amount'] / $data['skos_in'], $account->get('Currency Code')));
            } else {
                $sko_cost = sprintf('<span id="sko_cost_%d"  class="sko_cost" ></span>', $data['Part SKU']);
            }

            $total_paid = money($data['paid_amount'], $account->get('Currency Code'));


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/part/%d\')" title="%s" >%s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
            );
            $quantity  = ($data['Supplier Delivery Checked Units'] - $data['Supplier Delivery Placed Units']) / $data['Part Units Per Package'];


            if ($data['Metadata'] == '') {
                $metadata = array();
            } else {
                $metadata = json_decode($data['Metadata'], true);
            }


            $placement = '<div class="placement" ><div  class="placement_data mini_table right no_padding" style="padding-right:2px">';

            if (isset($metadata['placement_data'])) {

                foreach ($metadata['placement_data'] as $placement_data) {


                    $placement .= '<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._('SKO').' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


                }
            }
            $placement      .= '<div style="clear:both"></div></div>';
            $placement_note = '<input type="hidden" class="note" /><i class="far add_note fa-sticky-note padding_right_5 button" aria-hidden="true"  onClick="show_placement_note(this)" ></i>';
            $placement      .= '
			    <div style="clear:both"  id="place_item_'.$data['Purchase Order Transaction Fact Key'].'" class="place_item  '.($data['Supplier Delivery Checked Units'] != '' ? '' : 'invisible').'  '.($data['Supplier Delivery Transaction Placed'] == 'No' ? '' : 'hide')
                .' " part_sku="'.$data['Part SKU'].'" transaction_key="'.$data['Purchase Order Transaction Fact Key'].'"  >

			    '.$placement_note.'

			    <input class="place_qty width_50 changed" value="'.($quantity + 0).'" ovalue="'.($quantity + 0).'"  min="1" max="'.round($quantity, 2).'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="place_item_button  fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                </div>
                </div>
			';

            $table_data[] = array(

                'id'                            => (integer)$data['Part SKU'],
                'part_reference'                => $reference,
                'description'                   => $data['Part Package Description'],
                'received_quantity'             => number($data['skos_in']),
                'checked_quantity'              => number($data['checked_quantity']),
                'items_amount'                  => $items_amount,
                'extra_amount'                  => $extra_amount,
                'extra_amount_account_currency' => $extra_amount_account_currency,
                'paid_account_currency'         => money($data['Supplier Delivery Net Amount'] * $supplier_delivery->get('Supplier Delivery Currency Exchange'), $account->get('Currency Code')),

                'total_paid' => $total_paid,
                'sko_cost'   => $sko_cost,
                'placement'  => $placement
            );


        }
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


function consignments($_data, $db, $user) {


    $rtext_label = 'delivery_note';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $notes = '';

            switch ($data['Delivery Note State']) {


                case 'Ready to be Picked':
                    $state = _('Waiting');
                    break;
                case 'Picker Assigned':
                    $state = _('Picker assigned');
                    break;
                case 'Picking':
                    $state = _('Picking');
                    break;
                case 'Picked':
                    $state = _('Picked');
                    break;
                case 'Packing':
                    $state = _('Packing');
                    break;
                case 'Packed':
                    $state = _('Packed');
                    break;
                case 'Approved':
                    $state = _('Approved');
                    $notes = sprintf(
                        '<a class="pdf_link " target=\'_blank\' href="/pdf/dn.pdf.php?id=%d"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>', $data['Delivery Note Key']
                    );
                    break;
                case 'Dispatched':
                    $state = _('Dispatched');
                    $notes = sprintf(
                        '<a class="pdf_link " target=\'_blank\' href="/pdf/dn.pdf.php?id=%d"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>', $data['Delivery Note Key']
                    );
                    break;
                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                case 'Cancelled to Restock':
                    $state = _('Cancelled to restock');
                    break;
                case 'Packed Done':
                    $state = _('Packed & Closed');
                    break;
                default:
                    $state = $data['Delivery Note State'];
                    break;
            }

            switch ($data['Delivery Note Type']) {
                case('Order'):
                    $type      = _('Order');
                    $type_icon = '<i class="fa fa-truck " title="'.$type.'"></i>';
                    break;
                case('Sample'):
                    $type      = _('Sample');
                    $type_icon = '<i class="fa fa-truck " title="'.$type.'"></i>';

                    break;
                case('Donation'):
                    $type      = _('Donation');
                    $type_icon = '<i class="fa fa-truck " title="'.$type.'"></i>';

                    break;
                case('Replacement'):
                case('Replacement & Shortages'):
                    $type      = _('Replacement');
                    $type_icon = '<i class="fa fa-truck error" title="'.$type.'"></i>';

                    break;
                case('Shortages'):
                    $type      = _('Shortages');
                    $type_icon = '<i class="fa fa-truck error" title="'.$type.'"></i>';

                    break;
                default:
                    $type = $data['Delivery Note Type'];

            }

            switch ($data['Delivery Note Parcel Type']) {
                case('Pallet'):
                    $parcel_type = ' <i class="fa fa-calendar  fa-flip-vertical" aria-hidden="true"></i>';
                    break;
                case('Envelope'):
                    $parcel_type = ' <i class="fa fa-envelope" aria-hidden="true"></i>';
                    break;
                default:
                    $parcel_type = ' <i class="fa fa-archive" aria-hidden="true"></i>';

            }

            if ($data['Delivery Note Number Parcels'] == '') {
                $parcels = '?';
            } elseif ($data['Delivery Note Parcel Type'] == 'Pallet' and $data['Delivery Note Number Boxes']) {
                $parcels = number($data['Delivery Note Number Parcels']).$parcel_type.' ('.$data['Delivery Note Number Boxes'].' b)';
            } else {
                $parcels = number($data['Delivery Note Number Parcels']).$parcel_type;
            }


            $adata[] = array(
                'id' => (integer)$data['Delivery Note Key'],


                'number'   => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Key'], $data['Delivery Note ID']),
                'customer' => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Customer Key'], $data['Delivery Note Customer Name']),

                'date'      => ($data['Delivery Note Date Dispatched'] == '' ? '' : strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Dispatched'].' +0:00'))),
                'weight'    => weight($data['Delivery Note Weight']),
                'parcels'   => $parcels,
                'type'      => $type,
                'type_icon' => $type_icon,

                'state'    => $state,
                'notes'    => $notes,
                'tracking' => $data['Delivery Note Shipper Tracking']

            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
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


function feedback($_data, $db, $user, $account) {


    $rtext_label = 'issue';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $record_data[] = array(
                'id'        => (integer)$data['Feedback Key'],
                'reference' => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
                'date'      => strftime("%a %e %b %Y", strtotime($data['Feedback Date']." +00:00")),
                'note'      => $data['Feedback Message'],
                'author'    => $data['User Alias'],

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function production_deliveries($_data, $db, $user) {
    $rtext_label = 'production delivery';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {
                case 'InProcess':
                case 'Consolidated':
                case 'Dispatched':
                case 'Received':
                    $state = sprintf('%s', _('Items manufactured'));
                    break;

                case 'Checked':
                    $state = sprintf('%s', _('Checked'));
                    break;
                case 'Placed':
                case 'InvoiceChecked':
                    $state = _('Booked in');
                    break;
                case 'Costing':
                    $state = _('Booked in').', '._('checking costing');
                    break;

                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Supplier Delivery State'];
                    break;
            }

            $table_data[] = array(
                'id' => (integer)$data['Supplier Delivery Key'],

                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),


                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'/warehouse/%d/production_deliveries/%s/%d\')" >%s</span>  ', $data['Supplier Delivery Warehouse Key'], $_data['parameters']['section'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
                ),


                'state'        => $state,
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


function deleted_locations($_data, $db, $user, $account) {


    $rtext_label = 'location';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $link = 'locations/'.$_data['parameters']['parent_key'].'/';

    foreach ($db->query($sql) as $data) {


        $code = sprintf('<span class="link" onclick="change_view(\'%s/%d\')">%s</span>', $link, $data['Location Deleted Key'], $data['Location Deleted Code']);

        if ($data['Warehouse Area Key']) {
            $area = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Location Deleted Warehouse Key'], $data['Location Deleted Warehouse Area Key'], $data['Warehouse Area Code']);

        } elseif ($data['Location Deleted Warehouse Area Code'] != '') {
            $area = sprintf('<span class="discreet italic">%s</span>', $data['Location Deleted Warehouse Area Code']);

        } else {
            $area = '<span class="super_discreet italic">'._('Unknown').'</span>';
        }

        $adata[] = array(
            'id'   => (integer)$data['Location Deleted Key'],
            'code' => $code,
            'area' => $area,
            'note' => '<span class="small">'.$data['Location Deleted Note'].'</span>',
            'date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Location Deleted Date'].' +0:00')),

        );

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


function external_warehouse_replenishes($_data, $db, $user) {


    $rtext_label = 'part needed to replenish from external warehouses';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();



    foreach ($db->query($sql) as $data) {

        $locations        = '';
        if ($data['location_data'] != '') {
            $locations_data = preg_split('/,/', $data['location_data']);

            $number_locations=0;

            $locations = '<div  class="part_locations mini_table left "  >';

            foreach ($locations_data as $location_data) {
                $number_locations++;
                $location_data = preg_split('/\:/', $location_data);
                $locations     .= ' <div class="part_location button" style="clear:both;"  location_key="'.$location_data[0].'" >
				<div  class="code data w150"  >'.$location_data[1].' '.($location_data[4]=='External'?'<i style="color: tomato" class="fal padding_left_5 small fa-garage-car"></i>':'').' </div>

				<div class="data w30 aright" >'.number($location_data[3]).'</div>
				</div>';

            }
            $locations .= '<div style="clear:both"></div></div>';
        }




        /*
         *
         *   if ($data['Part Next Deliveries Data'] == '') {
            $next_deliveries_array = array();
        } else {
            $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
        }
        $next_deliveries = '';

        foreach ($next_deliveries_array as $next_delivery) {


            $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_5" style="min-width: 120px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_10 as_cell strong" style="text-align: right;min-width: 60px" title="'._('SKOs ordered')
                .'">+'.number(
                    $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


        }


        $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';
*/

        $reference = sprintf(
            '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
            ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
        );


        $table_data[] = array(
            'id'=>(integer) $data['Part SKU'],
            'reference' => $reference,

            'stock_external' => number(floor($data['Part Current On Hand Stock External'] )),
            'stock_local' => number(floor($data['Part Current On Hand Stock']-$data['Part Current On Hand Stock External'] )),
            'to_pick'             => number(ceil($data['to_pick'])),
            'total_stock'        => number(floor($data['Part Current On Hand Stock'])),
            'locations' => $locations,
           // 'next_deliveries'    => $next_deliveries

        );

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
