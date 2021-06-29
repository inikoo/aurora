<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 22:19 MYR , Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';

require_once 'utils/object_functions.php';

if (!$user->can_view('fulfilment')) {
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

    case 'locations':
        locations(get_table_parameters(), $db, $user, $account);
        break;
    case 'current_customers':
        current_customers(get_table_parameters(), $db, $user, $account);
        break;
    case 'all_customers':
        all_customers(get_table_parameters(), $db, $user, $account);
        break;
    case 'parts':
        parts(get_table_parameters(), $db, $user, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}


function locations($_data, $db, $user, $account) {


    $rtext_label = 'location';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();



    $link = 'fulfilment/locations/'.$_data['parameters']['parent_key'].'/';

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
        //$area = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Warehouse Area Key'], $data['Warehouse Area Code']);

        if ($data['Location Place'] == 'External') {
            $type = ' <i  title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car   "></i>';
        } else {
            $type = '';
        }


        $adata[] = array(
            'id'          => (integer)$data['Location Key'],
            'code'        => $code,
            //     'flag'        => ($data['Warehouse Flag Key'] ? sprintf(
            //         '<i id="flag_location_%d" class="fa fa-flag %s button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" location_key="%d" title="%s"></i>', $data['Location Key'], strtolower($data['Warehouse Flag Color']), $data['Location Key'],
            //         $data['Warehouse Flag Label']
            //     ) : '<i id="flag_location_'.$data['Location Key'].'"  class="far fa-flag super_discreet button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" key="" ></i>'),
            //    'flag_key'    => $data['Warehouse Flag Key'],
            //    'area'        => $area,
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

function current_customers($_data, $db, $user) {

    $rtext_label = 'customer';


    include_once 'prepare_table/init.php';

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Customer Type by Activity']) {
                case 'ToApprove':
                    $activity = _('To be approved');
                    break;
                case 'Inactive':
                    $activity = _('Lost');
                    break;
                case 'Active':
                    $activity = _('Active');
                    break;
                case 'Prospect':
                    $activity = _('Prospect');
                    break;
                default:
                    $activity = $data['Customer Type by Activity'];
                    break;
            }


            $link_format = '/'.$parameters['parent'].'/%d/customer/%d';

            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


            $adata[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name' => $data['Customer Name'],

                'location' => $data['Customer Location'],
                'activity' => $activity,
                'invoices' => number($data['invoices']),
                'orders'   => number($data['orders']),
                'amount'   => money($data['amount'], $data['Store Currency Code'])


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

function all_customers($_data, $db, $user) {

    $rtext_label = 'customer';


    include_once 'prepare_table/init.php';

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Customer Fulfilment Status']) {
                case 'ToApprove':
                    $activity = _('To be approved');
                    break;
                case 'Inactive':
                    $activity = _('Inactive');
                    break;
                case 'Active':
                    $activity = _('Active');
                    break;
                case 'Prospect':
                    $activity = _('Prospect');
                    break;
                default:
                    $activity = $data['Customer Type by Activity'];
                    break;
            }


            $link_format = '/fulfilment/%d/customers/%d';

            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


            $adata[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name' => $data['Customer Name'],

                'location' => $data['Customer Location'],
                'activity' => $activity,
                //'invoices' => number($data['invoices']),
                //'orders'   => number($data['orders']),
                //'amount'   => money($data['amount'], $data['Store Currency Code'])


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

function parts($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';





    $rtext_label = 'customer part';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();


    // $exchange = -1;

    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            //  if ($exchange < 0) {
            $exchange = 1.0 / currency_conversion(
                    $db, $account->get('Account Currency'), $data['Supplier Part Currency Code'], '- 1 hour'
                );
            //    }

            if ($exchange != 1) {

                $exchange_info = money(
                        ($data['Supplier Part Unit Cost'] + $data['Supplier Part Unit Extra Cost']), $data['Supplier Part Currency Code']
                    ).' @'.$data['Supplier Part Currency Code'].'/'.$account->get('Account Currency').' '.sprintf(
                        '%.6f', $exchange
                    );
            } else {
                $exchange_info = '';
            }

            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>', _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>', _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>', _('Discontinued')
                    );

                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }

            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status       = '<i class="fa  fa-plus-circle fa-fw warning discreet"  aria-hidden="true" title="'._('To much stock').'" ></i>';
                    $stock_status_label = _('Surplus');
                    break;
                case 'Optimal':
                    $stock_status       = '<i class="fa fa-check-circle fa-fw success" aria-hidden="true"  title="'._('Good level of stock').'"></i>';
                    $stock_status_label = _('Ok');
                    break;
                case 'Low':
                    $stock_status       = '<i class="fa fa-minus-circle fa-fw warning discreet" aria-hidden="true" title="'._('Low stock, order now').'"></i>';
                    $stock_status_label = _('Low');
                    break;
                case 'Critical':
                    $stock_status       = '<i class="fa error fa-minus-circle fa-fw error discreet" aria-hidden="true" title="'._('Critical low stock, will be out of stock anytime').'"></i>';
                    $stock_status_label = _('Critical');
                    break;
                case 'Out_Of_Stock':
                    $stock_status       = '<i class="fa error fa-ban fa-fw error" aria-hidden="true" title="'._('Out of stock').'"></i>';
                    $stock_status_label = _('Out of stock');
                    break;
                case 'Error':
                    $stock_status       = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Error');
                    break;
                default:
                    $stock_status       = $data['Part Stock Status'];
                    $stock_status_label = $data['Part Stock Status'];
                    break;
            }

            if ($data['Part Status'] == 'Not In Use') {
                $part_status = '<i class="fal fa-box fa-fw  error strikethrough" title="'._('Discontinued').'"></i> ';

            } elseif ($data['Part Status'] == 'Discontinuing') {
                $part_status = '<i class="fal fa-box fa-fw  error" title="'._('Discontinuing').'"></i> ';

            } else {
                $part_status = '<i class="fal fa-box fa-fw " aria-hidden="true"></i> ';
            }

            /*
                        if ($data['Part Cost in Warehouse'] == '') {
                            $stock_value = '<span class=" error italic">'._('Unknown cost').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } elseif ($data['Part Cost in Warehouse'] == 0) {
                            $stock_value = '<span class=" error italic">'._('Cost is zero').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } elseif ($data['Part Current On Hand Stock'] < 0) {
                            $stock_value = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } else {
                            $stock_value = money($data['Part Cost in Warehouse'] * $data['Part Current On Hand Stock'], $account->get('Account Currency'));


                        }
            */


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_10" style="min-width: 150px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" style="text-align: right;min-width: 70px" title="'._(
                        'SKOs ordered'
                    ).'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            if ($data['Part On Demand'] == 'Yes') {

                $available_forecast = '<span >'.sprintf(
                        '%s', '<span  title="'.sprintf("%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                    ).'</span>';

                if ($data['Part Fresh'] == 'No') {
                    $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5"  title="'._('On demand').'"></i>';
                } else {
                    $available_forecast = ' <i class="far fa-lemon padding_left_5"  title="'._('On demand').'"></i>';
                }
            } else {

                if ($data['Part Days Available Forecast'] == 0) {
                    $available_forecast = '';
                } else {

                    $available_forecast = '<span >'.sprintf(
                            '%s', '<span  title="'.sprintf(
                                    "%s %s", number($data['Part Days Available Forecast'], 1), ngettext(
                                               "day", "days", intval($data['Part Days Available Forecast'])
                                           )
                                ).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';

                }
            }


            $reference = sprintf('<span class="link" onClick="change_view(\'supplier/%d/part/%d\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']);
            if ($data['Supplier Part Reference'] != $data['Part Reference']) {
                $reference .= '<br><span  class="link '.($data['Part Status'] == 'Not In Use' ? 'strikethrough error' : '').'  " onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$part_status.' '.$data['Part Reference'].'</span> ';

            } else {
                $reference .= '<span  title="'._('Link to part').'" class="link margin_left_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$part_status.'</span> ';

            }

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';
            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $account->get('Account Currency'));
            }

            $record_data[] = array(
                'id'   => (integer)$data['Supplier Part Key'],
                'data' => '<span id="item_data_'.$data['Supplier Part Key'].'" class="item_data" data-key="'.$data['Supplier Part Key'].'" ></span>',

                'supplier_code'  => sprintf('<span class="link" onClick="change_view(\'supplier/%d/\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Code']),
                'part_reference' => $data['Part Reference'],
                'reference'      => $reference,


                'barcode'        => $data['Part Barcode Number'],
                'barcode_sko'    => $data['Part SKO Barcode'],
                'barcode_carton' => $data['Part Carton Barcode'],
                'weight_sko'     => ($data['Part Package Weight'] != '' ? weight($data['Part Package Weight'], 'Kg', 3, false, true) : '<i class="fa fa-exclamation-circle error"></i>'),
                'cbm'            => ($data['Supplier Part Carton CBM'] != '' ? $data['Supplier Part Carton CBM'].'m³' : '<i class="fa fa-exclamation-circle error"></i>'),


                'description'    => '<span  data-field="Supplier Part Description"  data-item_class="item_Supplier_Part_Description" class="table_item_editable item_Supplier_Part_Description"  >'.$data['Supplier Part Description'].'</span>',
                'status'         => $status,
                'cost'           => sprintf(
                    '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>', $data['Supplier Part Key'], $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'],
                    money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'])
                ),
                'delivered_cost' => '<span title="'.$exchange_info.'">'.money(
                        $exchange * ($data['Supplier Part Unit Cost'] + $data['Supplier Part Unit Extra Cost']), $account->get('Account Currency')
                    ).'</span>',
                'packing'        => '
				 <div style="float:right;min-width:30px;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:70px;text-align:center;" title="'._('Packages (SKOs) per carton').'" > <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div>
				<div style="float:right;min-width:20px;text-align:right" title="'._('Units per SKO').'"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
                'stock'          => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',


                //'stock_value'        => $stock_value,

                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => '<span title="'.sprintf(_('%s dispatched same interval last year'), number($data['dispatched_1yb'])).'">'.delta($data['dispatched'], $data['dispatched_1yb']).'</span>',
                'sales'          => money($data['sales'], $account->get('Account Currency')),
                'sales_1yb'      => '<span title="'.sprintf(_('%s amount sold same interval last year'), money($data['sales_1yb'], $account->get('Account Currency'))).'">'.delta($data['sales'], $data['sales_1yb']).'</span>',

                'sko_stock_value'      => $sko_stock_value,
                'sko_commercial_value' => ($data['Part Commercial Value'] == '' ? '' : money($data['Part Commercial Value'], $account->get('Account Currency'))),
                'stock_status'         => $stock_status,
                'stock_status_label'   => $stock_status_label,
                'next_deliveries'      => $next_deliveries,
                'available_forecast'   => $available_forecast,
                'dispatched_per_week'  => number($data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0)

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