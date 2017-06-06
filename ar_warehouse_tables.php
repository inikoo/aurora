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
    case 'parts':
        parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock_transactions':
        stock_transactions(get_table_parameters(), $db, $user);
        break;
    case 'part_locations_with_errors':
        part_locations_with_errors(get_table_parameters(), $db, $user, $account);
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

    $rtext_label = 'area';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'access'    => (in_array(
                $data['Warehouse Area Warehouse Key'], $user->warehouses
            ) ? '' : '<i class="fa fa-lock error"></i>'),
            'id'        => (integer)$data['Warehouse Area Key'],
            'code'      => sprintf(
                '<span class="link" onClick="change_view(\'warehouse/%d/area/%d\')">%s</span>', $data['Warehouse Area Warehouse Key'], $data['Warehouse Area Key'], $data['Warehouse Area Code']
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

    foreach ($db->query($sql) as $data) {

        /*

        switch ($data['Location Mainly Used For']) {
            case 'Picking':
                $used_for = _('Picking');
                break;
            case 'Storing':
                $used_for = _('Storing');
                break;
            case 'Loading':
                $used_for = _('Loading');
                break;
            case 'Displaying':
                $used_for = _('Displaying');
                break;
            case 'Other':
                $used_for = _('Other');
                break;
            default:
                $used_for = $data['Location Mainly Used For'];
                break;
        }
*/

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


        $adata[] = array(
            'id'                 => (integer)$data['Location Key'],
            'warehouse_key'      => (integer)$data['Location Warehouse Key'],
            'warehouse_area_key' => (integer)$data['Location Warehouse Area Key'],
            'code'               => $data['Location Code'],
            'flag'               => ($data['Warehouse Flag Key'] ? sprintf(
                '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
            ) : '<i class="fa fa-flag-o super_discreet" aria-hidden="true"></i>'),
            'flag_key'           => $data['Warehouse Flag Key'],
            'area'               => $data['Warehouse Area Code'],
            'max_weight'         => $max_weight,
            'max_volume'         => $max_vol,
            'parts'              => number($data['Location Distinct Parts']),
            'stock_value'        => money($data['Location Stock Value'], $account->get('Account Currency')),

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

    //print $sql;

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


        $adata[] = array(
            'id'                    => (integer)$data['Location Key'],
            'location'              => ($data['Warehouse Flag Key'] ? sprintf(
                    '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
                ) : '<i class="fa fa-flag-o super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key']
                .'\')">'.$data['Location Code'].'</span>',
            'part'                  => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'other_locations_stock' => $stock,

            'quantity'             => number($data['Quantity On Hand']).' '.number($data['Part Current On Hand Stock']),
            'ordered_quantity'     => number($data['ordered_quantity']),
            'effective_stock'      => number($data['effective_stock']),
            'recommended_quantity' => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity'])
                .'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number($data['Maximum Quantity']).'</span>)</span>'

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

    //print $sql;


    foreach ($db->query($sql) as $data) {

        $adata[] = array(


            'reference' => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span class="link" onCLick="change_view(\'locations/%d/%d\')" >%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'sko_description' => $data['Part Package Description'],


            'can_pick' => ($data['Can Pick'] == 'Yes' ? _('Yes') : _('No')),

            'sko_cost'    => money($data['Part Cost in Warehouse'], $account->get('Account Currency')),
            'stock_value' => money($data['Stock Value'], $account->get('Account Currency')),
            'quantity'    => sprintf(
                '<span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell  location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onClick="open_location_part_stock_quantity_dialog(this)">%s</span>',
                '', $data['Part SKU'], $data['Location Key'], $data['Quantity On Hand'], number($data['Quantity On Hand'])
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
                    $type = '<i class="fa  fa-clock-o discreet fa-fw" aria-hidden="true"></i>';

                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _('%s %s (%s) to be taken from %s'),

                            number($data['Required']), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-fw fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                            )


                        );
                    } else {
                        $note = sprintf(
                            _('%sx %s (%s) to be taken from %s'), number($data['Required']),

                            ($parameters['parent'] == 'part'
                                ? sprintf(
                                    '<i class="fa fa-square" aria-hidden="true"></i> %s', $data['Part Reference']
                                )
                                : sprintf(
                                    '<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference']
                                )), sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                            )

                        );
                    }


                    break;
                case 'Sale':
                    $type = '<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>';
                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _('%s %s (%s) taken from %s'),

                            number(
                                -1 * $data['Inventory Transaction Quantity']
                            ), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                            )


                        );
                    } else {
                        $note = sprintf(
                            _('%sx %s (%s) taken from %s'), number(
                            -1 * $data['Inventory Transaction Quantity']
                        ),

                            ($parameters['parent'] == 'part'
                                ? sprintf(
                                    '<i class="fa fa-square" aria-hidden="true"></i> %s', $data['Part Reference']
                                )
                                : sprintf(
                                    '<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference']
                                )), sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                            )

                        );
                    }


                    break;
                case 'In':
                    $type = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Audit':


                    $type = '<i class="fa fa-fw fa-dot-circle-o" aria-hidden="true"></i>';

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
                    $type  = '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>';
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


    //print $sql;


    foreach ($db->query($sql) as $data) {

        $table_data[] = array(
            'reference' => sprintf('<span class="link"  title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span  class="link"  onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'quantity_in_picking' => number(floor($data['Quantity On Hand'])),
            'to_pick'             => number(ceil($data['to_pick'])),

            'total_stock'       => number(floor($data['Part Current Stock'])),
            'storing_locations' => $data['storing_locations']


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

        $table_data[] = array(

            'reference' => sprintf('<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span  class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'can_pick' => ($data['Can Pick'] == 'Yes' ? _('Yes') : _('No')),
            'quantity' => '<span class="error">'.number($data['Quantity On Hand']),
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


?>
