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
                '<i id="flag_location_%d" class="fa fa-flag %s button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" location_key="%d" title="%s"></i>', $data['Location Key'], strtolower($data['Warehouse Flag Color']), $data['Location Key'],
                $data['Warehouse Flag Label']
            ) : '<i id="flag_location_'.$data['Location Key'].'"  class="far fa-flag super_discreet button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" key="" ></i>'),
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
                ) : '<i class="far fa-flag super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key'].'\')">'.$data['Location Code'].'</span>',
            'part'                  => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'other_locations_stock' => $stock,

            'quantity'             => number($data['Quantity On Hand']),
            'ordered_quantity'     => number($data['ordered_quantity']),
            'effective_stock'      => number($data['effective_stock']),
            'recommended_quantity' => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity']).'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number(
                    $data['Maximum Quantity']
                ).'</span>)</span>'

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

        $adata[] = array(


            'reference' => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
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
                                    '<i class="fa fa-square" aria-hidden="true"></i> %s', $data['Part Reference']
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


        $table_data[] = array(
            'reference' => sprintf('<span class="link"  title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span  class="link"  onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'quantity_in_picking' => number(floor($data['Quantity On Hand'])),
            'to_pick'             => number(ceil($data['to_pick'])),

            'total_stock'       => number(floor($data['Part Current Stock'])),
            'storing_locations' => $storing_locations


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
                $record_data[$_date]['delta_up_amount_1yb'] = '<span class="" title="'.money($last_year_data[$_date_last_year]['_up_amount'], $currency).'">'.delta($data['up_amount'], $last_year_data[$_date_last_year]['_up_amount']).' '.delta_icon(
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

    //  print $sql;


    foreach ($db->query($sql) as $data) {

        if ($data['Part Status'] == 'Not In Use') {
            $part_status = '<i class="fa fa-square fa-fw  very_discreet" aria-hidden="true"></i> ';

        } elseif ($data['Part Status'] == 'Discontinuing') {
            $part_status = '<i class="fa fa-square fa-fw  very_discreet" aria-hidden="true"></i> ';

        } else {
            $part_status = '<i class="fa fa-square fa-fw " aria-hidden="true"></i> ';
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


        $adata[] = array(


            'reference' => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),


            'description' => $data['Part Package Description'],

            'part_status' => $part_status,
            'locations'   => $locations,

            'sko_cost'    => money($data['Part Cost in Warehouse'], $account->get('Account Currency')),
            'stock_value' => money($data['Stock Value'], $account->get('Account Currency')),
            'quantity'    => sprintf(
                '<span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell  location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onxClick="open_location_part_stock_quantity_dialog(this)">%s</span>', '', $data['Part SKU'], 1,
                $data['Quantity On Hand'], '<strong class="'.($data['Quantity On Hand'] < 0 ? 'success' : 'error').'" >'.($data['Quantity On Hand'] < 0 ? '+' : '').number(-$data['Quantity On Hand']).'</strong>'
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


            switch ($data['Shipper Status']){
                case 'Active':
                    $status=sprintf('<i class="fa fa-play success" title="%s"></i>',_('Active'));

                    break;
                case 'Active':
                    $status=sprintf('<i class="fa fa-pause discreet error" title="%s"></i>',_('Suspended'));
                    break;
                default:
                    $status='';
            }


            $code = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/shipper/%d\')">%s</span>', $data['Shipper Warehouse Key'],$data['Shipper Key'], $data['Shipper Code']);


            $record_data[] = array(
                'id'               => (integer)$data['Shipper Key'],
                'code'             => $code,
                'status'             => $status,
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


?>
