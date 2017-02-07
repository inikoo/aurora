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
        locations(get_table_parameters(), $db, $user);
        break;
    case 'replenishments':
        replenishments(get_table_parameters(), $db, $user);
        break;
    case 'parts':
        parts(get_table_parameters(), $db, $user);
        break;

    case 'stock_transactions':
        stock_transactions(get_table_parameters(), $db, $user);
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

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
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

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
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


function locations($_data, $db, $user) {


    $rtext_label = 'location';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {
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


        if ($data['Location Max Weight'] == '' or $data['Location Max Weight'] <= 0) {
            $max_weight = _('Unknown');
        } else {
            $max_weight = number($data['Location Max Weight'])._('Kg');
        }
        if ($data['Location Max Volume'] == '' or $data['Location Max Volume'] <= 0) {
            $max_vol = _('Unknown');
        } else {
            $max_vol = number($data['Location Max Volume'])._('L');
        }


        $adata[] = array(
            'id'                 => (integer)$data['Location Key'],
            'warehouse_key'      => (integer)$data['Location Warehouse Key'],
            'warehouse_area_key' => (integer)$data['Location Warehouse Area Key'],
            'code'               => $data['Location Code'],
            'flag'               => ($data['Warehouse Flag Key'] ? sprintf(
                '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
            ) : '<i class="fa fa-flag-o discret" aria-hidden="true"></i>'),
            'flag_key'           => $data['Warehouse Flag Key'],
            'area'               => $data['Warehouse Area Code'],
            'max_weight'         => $max_weight,
            'max_volume'         => $max_vol,
            'parts'              => number($data['Location Distinct Parts']),
            'used_for'           => $used_for
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

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    foreach ($db->query($sql) as $data) {


        switch ($data['Warehouse Flag']) {
            case 'Blue':
                $flag = "<img  src='/art/icons/flag_blue.png' title='".$data['Warehouse Flag']."' />";
                break;
            case 'Green':
                $flag = "<img  src='/art/icons/flag_green.png' title='".$data['Warehouse Flag']."' />";
                break;
            case 'Orange':
                $flag = "<img src='/art/icons/flag_orange.png' title='".$data['Warehouse Flag']."'  />";
                break;
            case 'Pink':
                $flag = "<img  src='/art/icons/flag_pink.png' title='".$data['Warehouse Flag']."'/>";
                break;
            case 'Purple':
                $flag = "<img src='/art/icons/flag_purple.png' title='".$data['Warehouse Flag']."'/>";
                break;
            case 'Red':
                $flag = "<img src='/art/icons/flag_red.png' title='".$data['Warehouse Flag']."'/>";
                break;
            case 'Yellow':
                $flag = "<img src='/art/icons/flag_yellow.png' title='".$data['Warehouse Flag']."'/>";
                break;
            default:
                $flag = '';

        }


        $stock          = '<div border=0 style="xwidth:150px">';
        $locations_data = preg_split('/,/', $data['location_data']);

        foreach ($locations_data as $raw_location_data) {
            if ($raw_location_data != '') {
                $_locations_data = preg_split('/\:/', $raw_location_data);
                if ($_locations_data[0] != $data['Location Key']) {
                    $stock .= '<div style="clear:both">';
                    $stock .= '<div style="float:left;min-width:100px;"><a href="location.php?id='.$_locations_data[0].'">'.$_locations_data[1]
                        .'</a></div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                    $stock .= '</div>';
                }
            }
        }
        $stock .= '</div>';

        $pl_data = '<span style="font-weight:800">'.number(
                $data['Quantity On Hand']
            ).'</span>  {'.number($data['Minimum Quantity']).','.number(
                $data['Maximum Quantity']
            ).'}';


        $adata[] = array(
            'id'           => (integer)$data['Location Key'],
            'location'     => $flag.' '.$data['Location Code'],
            'location_key' => $data['Location Key'],
            'part'         => $data['Part Reference'],
            'part_sku'     => $data['Part SKU'],
            'stock'        => $stock,
            'pl_data'      => $pl_data
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


function parts($_data, $db, $user) {


    if ($_data['parameters']['tab'] == 'warehouse.parts') {
        $rtext_label = 'part location';
    } else {
        $rtext_label = 'part';

    }


    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;


    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            // 'id'=>(integer) $data['Part SKU'],
            'reference'        => $data['Part Reference'],
            'unit_description' => $data['Part Unit Description'],
            'location'         => $data['Location Code'],
            'location_key'     => $data['Location Key'],
            'warehouse_key'    => $data['Part Location Warehouse Key'],
            'part_sku'         => $data['Part SKU'],
            'can_pick'         => ($data['Can Pick'] == 'Yes'
                ? _('Yes')
                : _(
                    'No'
                )),
            'quantity'         => number($data['Quantity On Hand'])

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

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            //MossRB-04 227330 Taken from: 11A1

            $note  = $data['Note'];
            $stock = $data['Inventory Transaction Quantity'];
            switch ($data['Inventory Transaction Type']) {
                case 'OIP':
                    $type
                        = '<i class="fa  fa-clock-o discret fa-fw" aria-hidden="true"></i>';

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
                    $type
                        = '<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>';
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
                    $type
                        = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Audit':


                    $type
                        = '<i class="fa fa-fw fa-dot-circle-o" aria-hidden="true"></i>';

                    $stock = sprintf('<b>'.$data['Part Location Stock'].'</b>');
                    break;
                case 'Adjust':

                    if ($stock > 0) {
                        $stock = '+'.number($stock);
                    }

                    $type
                        = '<i class="fa fa-fw fa-sliders" aria-hidden="true"></i>';


                    break;

                case 'Move':
                    $stock = '±'.number($data['Metadata']);
                    $type
                           = '<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $type
                        = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
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


    $rtext_label = 'part location with errors';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    //print $sql;


    foreach ($db->query($sql) as $data) {

        $table_data[] = array(
            // 'id'=>(integer) $data['Part SKU'],
            'reference'        => $data['Part Reference'],
            'unit_description' => $data['Part Unit Description'],
            'location'         => $data['Location Code'],
            'location_key'     => $data['Location Key'],
            'warehouse_key'    => $data['Part Location Warehouse Key'],
            'part_sku'         => $data['Part SKU'],
            'can_pick'         => ($data['Can Pick'] == 'Yes'
                ? _('Yes')
                : _(
                    'No'
                )),
            'quantity'         => '<span class="error">'.number(
                    $data['Quantity On Hand']
                ),
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

function part_locations_with_errors($_data, $db, $user) {


    $rtext_label = 'part location with errors';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    //print $sql;


    foreach ($db->query($sql) as $data) {

        $table_data[] = array(
            // 'id'=>(integer) $data['Part SKU'],
            'reference'        => $data['Part Reference'],
            'unit_description' => $data['Part Unit Description'],
            'location'         => $data['Location Code'],
            'location_key'     => $data['Location Key'],
            'warehouse_key'    => $data['Part Location Warehouse Key'],
            'part_sku'         => $data['Part SKU'],
            'can_pick'         => ($data['Can Pick'] == 'Yes'
                ? _('Yes')
                : _(
                    'No'
                )),
            'quantity'         => '<span class="error">'.number(
                    $data['Quantity On Hand']
                ),
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
