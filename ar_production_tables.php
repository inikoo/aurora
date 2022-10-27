<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:56:43 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';

/*
if (!$user->can_view('staff')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}
*/

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
    case 'active_parts':
        parts(get_table_parameters(), $db, $user, 'active', $account);
        break;
    case 'in_process_parts':
        parts(get_table_parameters(), $db, $user, 'in_process', $account);
        break;
    case 'discontinuing_parts':
        parts(get_table_parameters(), $db, $user, 'discontinuing', $account);
        break;
    case 'discontinued_parts':
        parts(get_table_parameters(), $db, $user, 'discontinued', $account);
        break;
    case 'bill_of_materials':
        bill_of_materials(get_table_parameters(), $db, $user, $account);
        break;
    case 'suppliers':
        suppliers(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_parts':
        production_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'raw_materials':
        raw_materials(get_table_parameters(), $db, $user, $account);
        break;
    case 'operatives':
        operatives(get_table_parameters(), $db, $user);
        break;
    case 'manufacture_tasks':
        manufacture_tasks(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_deliveries':
        production_deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_orders':
        production_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'deliveries_with_part':
        production_deliveries_with_part(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_with_part':
        production_orders_with_part(get_table_parameters(), $db, $user, $account);
        break;
    case 'replenishments':
        replenishments(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_urgent_to_do':
        production_urgent_to_do(get_table_parameters(), $db, $user, $account);
        break;
    case 'todo_parts':

        todo_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_external_products':
        external_products(get_table_parameters(), $db, $user, $account);
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

function parts($_data, $db, $user, $type, $account)
{
    if (!$user->can_view('parts')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    if ($type == 'active') {
        $extra_where = ' and `Part Status`="In Use"';
        $rtext_label = 'product';
    } elseif ($type == 'discontinuing') {
        $extra_where = ' and `Part Status`="Discontinuing"';
        $rtext_label = 'product';
    } elseif ($type == 'discontinued') {
        $extra_where = ' and `Part Status`="Not In Use"';
        $rtext_label = 'product';
    } elseif ($type == 'in_process') {
        $extra_where = ' and `Part Status`="In Process"';
        $rtext_label = 'product';
    } else {
        $extra_where = ' and `Part Status`!="Not In Use"';
        $rtext_label = 'product';
    }


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
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


            if ($data['Part Current On Hand Stock'] <= 0) {
                $weeks_available = '-';
            } else {
                $weeks_available = number(
                    $data['Part Days Available Forecast'] / 7,
                    0
                );
            }


            if ($data['Part Status'] == 'In Use') {
                $status = _('Active');
            } elseif ($data['Part Status'] == 'Discontinuing') {
                $status             = _('Discontinuing');
                $stock_status       = '<i class="fa fa-box warning fa-fw" aria-hidden="true"></i>';
                $stock_status_label = _('Discontinuing');
            } elseif ($data['Part Status'] == 'Not In Use') {
                $status = _('Discontinued');
            } elseif ($data['Part Status'] == 'In Process') {
                $status = _('In process');
            } else {
                $status = $data['Part Status'];
            }

            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52,
                0
            );

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>',
                $data['Part SKU']
            );


            $sko_cost = money($data['Part Cost'], $account->get('Account Currency'));

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';
            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $account->get('Account Currency'));
            }


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
                        '%s',
                        '<span  title="'.sprintf("%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
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
                            '%s',
                            '<span  title="'.sprintf(
                                "%s %s",
                                number($data['Part Days Available Forecast'], 1),
                                ngettext(
                                    "day",
                                    "days",
                                    intval($data['Part Days Available Forecast'])
                                )
                            ).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';
                }
            }


            if ($data['Part Cost in Warehouse'] == '') {
                $stock_value = '<span class=" error italic">'._('Unknown cost').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';
            } elseif ($data['Part Cost in Warehouse'] == 0) {
                $stock_value = '<span class=" error italic">'._('Cost is zero').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';
            } elseif ($data['Part Current On Hand Stock'] < 0) {
                $stock_value = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';
            } else {
                $stock_value = money($data['Part Cost in Warehouse'] * $data['Part Current On Hand Stock'], $account->get('Account Currency'));
            }


            if ($_data['parameters']['parent'] == 'category') {
                $reference = sprintf(
                    '<span class="link" onclick="change_view(\'category/%d/part/%d\')">%s</span>',
                    $_data['parameters']['parent_key'],
                    $data['Part SKU'],
                    ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
                );
            } else {
                $reference = sprintf(
                    '<span class="link" onclick="change_view(\'part/%d\')">%s</span>',
                    $data['Part SKU'],
                    ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
                );
            }


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


            $record_data[] = array(
                'id'                 => (integer)$data['Part SKU'],
                'associated'         => $associated,
                'reference'          => $reference,
                'sko_description'    => $data['Part Package Description'],
                'status'             => $status,
                'stock_status'       => $stock_status,
                'stock_status_label' => $stock_status_label,
                'stock'              => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',
                'stock_value'        => $stock_value,

                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => delta($data['dispatched'], $data['dispatched_1yb']),
                'sales'          => money($data['sales'], $account->get('Account Currency')),
                'sales_1yb'      => delta($data['sales'], $data['sales_1yb']),

                'sales_year0' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part Year To Day Acc Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part Year To Day Acc Invoiced Amount"],
                        $data["Part Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 1 Year Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 1 Year Ago Invoiced Amount"],
                        $data["Part 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 2 Year Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 2 Year Ago Invoiced Amount"],
                        $data["Part 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 3 Year Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 3 Year Ago Invoiced Amount"],
                        $data["Part 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 4 Year Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 4 Year Ago Invoiced Amount"],
                        $data["Part 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s',
                    money($data['Part Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')),
                    delta_icon($data["Part Quarter To Day Acc Invoiced Amount"], $data["Part Quarter To Day Acc 1YB Invoiced Amount"])
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 1 Quarter Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 1 Quarter Ago Invoiced Amount"],
                        $data["Part 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 2 Quarter Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 2 Quarter Ago Invoiced Amount"],
                        $data["Part 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 3 Quarter Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 3 Quarter Ago Invoiced Amount"],
                        $data["Part 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s',
                    money(
                        $data['Part 4 Quarter Ago Invoiced Amount'],
                        $account->get('Account Currency')
                    ),
                    delta_icon(
                        $data["Part 4 Quarter Ago Invoiced Amount"],
                        $data["Part 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


                'dispatched_year0' => sprintf('<span>%s</span> %s', number($data['Part Year To Day Acc Dispatched']), delta_icon($data["Part Year To Day Acc Dispatched"], $data["Part Year To Day Acc 1YB Dispatched"])),
                'dispatched_year1' => sprintf('<span>%s</span> %s', number($data['Part 1 Year Ago Dispatched']), delta_icon($data["Part 1 Year Ago Dispatched"], $data["Part 2 Year Ago Dispatched"])),
                'dispatched_year2' => sprintf('<span>%s</span> %s', number($data['Part 2 Year Ago Dispatched']), delta_icon($data["Part 2 Year Ago Dispatched"], $data["Part 3 Year Ago Dispatched"])),
                'dispatched_year3' => sprintf('<span>%s</span> %s', number($data['Part 3 Year Ago Dispatched']), delta_icon($data["Part 3 Year Ago Dispatched"], $data["Part 4 Year Ago Dispatched"])),
                'dispatched_year4' => sprintf('<span>%s</span> %s', number($data['Part 4 Year Ago Dispatched']), delta_icon($data["Part 4 Year Ago Dispatched"], $data["Part 5 Year Ago Dispatched"])),

                'dispatched_quarter0' => sprintf('<span>%s</span> %s', number($data['Part Quarter To Day Acc Dispatched']), delta_icon($data["Part Quarter To Day Acc Dispatched"], $data["Part Quarter To Day Acc 1YB Dispatched"])),
                'dispatched_quarter1' => sprintf('<span>%s</span> %s', number($data['Part 1 Quarter Ago Dispatched']), delta_icon($data["Part 1 Quarter Ago Dispatched"], $data["Part 1 Quarter Ago 1YB Dispatched"])),
                'dispatched_quarter2' => sprintf('<span>%s</span> %s', number($data['Part 2 Quarter Ago Dispatched']), delta_icon($data["Part 2 Quarter Ago Dispatched"], $data["Part 2 Quarter Ago 1YB Dispatched"])),
                'dispatched_quarter3' => sprintf('<span>%s</span> %s', number($data['Part 3 Quarter Ago Dispatched']), delta_icon($data["Part 3 Quarter Ago Dispatched"], $data["Part 3 Quarter Ago 1YB Dispatched"])),
                'dispatched_quarter4' => sprintf('<span>%s</span> %s', number($data['Part 4 Quarter Ago Dispatched']), delta_icon($data["Part 4 Quarter Ago Dispatched"], $data["Part 4 Quarter Ago 1YB Dispatched"])),


                'sales_total'                      => money($data['Part Total Acc Invoiced Amount'], $account->get('Account Currency')),
                'dispatched_total'                 => number($data['Part Total Acc Dispatched'], 0),
                'customer_total'                   => number($data['Part Total Acc Customers'], 0),
                'percentage_repeat_customer_total' => percentage($data['Part Total Acc Repeat Customers'], $data['Part Total Acc Customers']),


                'weeks_available'     => $weeks_available,
                'dispatched_per_week' => $dispatched_per_week,
                'valid_from'          => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'valid_to'            => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'active_from'         => strftime("%a %e %b %Y", strtotime($data['Part Active From'].' +0:00')),
                'has_stock'           => ($data['Part Current On Hand Stock'] > 0 ? '<i class="fa fa-check success" aria-hidden="true"></i>' : '<i class="fa fa-minus super_discreet" aria-hidden="true"></i>'),
                'has_picture'         => ($data['Part Main Image Key'] > 0 ? '<i class="fa fa-check success" aria-hidden="true"></i>' : '<i class="fa fa-minus super_discreet" aria-hidden="true"></i>'),
                'has_products'        => ($data['Part Number Active Products'] > 0 ? '<i class="fa fa-check success" aria-hidden="true"></i>' : '<i class="fa fa-minus super_discreet" aria-hidden="true"></i>'),

                'sko_cost'             => $sko_cost,
                'sko_stock_value'      => $sko_stock_value,
                'sko_commercial_value' => ($data['Part Commercial Value'] == '' ? '' : money($data['Part Commercial Value'], $account->get('Account Currency'))),

                'margin'             => '<span class="'.($data['Part Margin'] <= 0 ? 'error' : '').'">'.percentage($data['Part Margin'], 1).'</span>',
                'next_deliveries'    => $next_deliveries,
                'available_forecast' => $available_forecast,


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

function replenishments($_data, $db, $user, $account)
{
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


        $adata[] = array(
            'id'                    => (integer)$data['Location Key'],
            'location'              => ($data['Warehouse Flag Key'] ? sprintf(
                    '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>',
                    strtolower($data['Warehouse Flag Color']),
                    $data['Warehouse Flag Label']
                ) : '<i class="far fa-flag super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key'].'\')">'.$data['Location Code'].'</span>',
            'part'                  => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'other_locations_stock' => $stock,
            'description'           => $data['Part Package Description'],
            'quantity'              => number($data['Quantity On Hand']),
            'ordered_quantity'      => number($data['ordered_quantity']),
            'effective_stock'       => number($data['effective_stock']),
            'recommended_quantity'  => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity']).'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number(
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


function operatives($_data, $db, $user)
{
    $rtext_label = 'worker';
    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $adata[] = array(
                'id'               => $data['Staff Key'],
                'payroll_id'       => $data['Staff ID'],
                'name'             => $data['Staff Name'],
                'code'             => sprintf('<span class="link" onclick="change_view(\'production/%d/operatives/%d\')">%s</span>', $_data['parameters']['production_key'], $data['Staff Key'], $data['Staff Alias']),
                'po_queued'        => ($data['Staff Operative Purchase Orders Queued'] > 0 ? number($data['Staff Operative Purchase Orders Queued']) : '<span class="super_discreet">0</span>'),
                'po_manufacturing' => ($data['Staff Operative Purchase Orders Manufacturing'] > 0 ? number($data['Staff Operative Purchase Orders Manufacturing']) : '<span class="super_discreet">0</span>'),
                'po_manufactured'  => ($data['po_manufactured'] > 0 ? number($data['po_manufactured']) : '<span class="super_discreet">0</span>'),
                'po_placing'       => ($data['Staff Operative Purchase Orders Waiting Placing'] > 0 ? number($data['Staff Operative Purchase Orders Waiting Placing']) : '<span class="super_discreet">0</span>'),
                'po_placed'        => ($data['Staff Operative Purchase Orders'] > 0 ? number($data['Staff Operative Purchase Orders']) : '<span class="super_discreet">0</span>'),

                'products_queued'        => ($data['Staff Operative Products Queued'] > 0 ? number($data['Staff Operative Products Queued']) : '<span class="super_discreet">0</span>'),
                'products_manufacturing' => ($data['Staff Operative Products Manufacturing'] > 0 ? number($data['Staff Operative Products Manufacturing']) : '<span class="super_discreet">0</span>'),
                'products_manufactured'  => ($data['products_manufactured'] > 0 ? number($data['products_manufactured']) : '<span class="super_discreet">0</span>'),
                'products_placing'       => ($data['Staff Operative Products Waiting Placing'] > 0 ? number($data['Staff Operative Products Waiting Placing']) : '<span class="super_discreet">0</span>'),
                'products_placed'        => ($data['Staff Operative Products'] > 0 ? number($data['Staff Operative Products']) : '<span class="super_discreet">0</span>'),


                'transactions_queued'        => ($data['Staff Operative Transactions Queued'] > 0 ? number($data['Staff Operative Transactions Queued']) : '<span class="super_discreet">0</span>'),
                'transactions_manufacturing' => ($data['Staff Operative Transactions Manufacturing'] > 0 ? number($data['Staff Operative Transactions Manufacturing']) : '<span class="super_discreet">0</span>'),
                'transactions_manufactured'  => ($data['transactions_manufactured'] > 0 ? number($data['transactions_manufactured']) : '<span class="super_discreet">0</span>'),
                'total_transactions'         => ($data['Staff Operative Transactions'] > 0 ? number($data['Staff Operative Transactions']) : '<span class="super_discreet">0</span>'),


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

function manufacture_tasks($_data, $db, $user, $account)
{
    $rtext_label = 'manufacture task';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $adata[] = array(
                'id'        => (integer)$data['Manufacture Task Key'],
                'name'      => $data['Manufacture Task Name'],
                'work_cost' => ($data['Manufacture Task Work Cost'] != '' ? money(
                    $data['Manufacture Task Work Cost'],
                    $account->get('Currency Code')
                ) : _('NA')),

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

function suppliers($_data, $db, $user, $account)
{
    if (!$user->can_view('production')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'supplier';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($_data['parameters']['parent'] == 'agent') {
                $operations = sprintf(
                    '<i agent_key="%d" supplier_key="%d"  class="fa fa-unlink button" aria-hidden="true"  onClick="bridge_supplier(this)" ></i>',
                    $_data['parameters']['parent_key'],
                    $data['Supplier Key']
                );
            } else {
                $operations = '';
            }


            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>',
                $data['Supplier Key']
            );

            $adata[] = array(
                'id'         => (integer)$data['Supplier Key'],
                'operations' => $operations,
                'associated' => $associated,

                'code'                  => $data['Supplier Code'],
                'name'                  => $data['Supplier Name'],
                'supplier_parts'        => number(
                    $data['Supplier Number Parts']
                ),
                'active_supplier_parts' => number(
                    $data['Supplier Number Active Parts']
                ),

                'surplus'      => sprintf(
                    '<span class="%s" title="%s">%s</span>',
                    (ratio(
                        $data['Supplier Number Surplus Parts'],
                        $data['Supplier Number Parts']
                    ) > .75
                        ? 'error'
                        : (ratio(
                            $data['Supplier Number Surplus Parts'],
                            $data['Supplier Number Parts']
                        ) > .5 ? 'warning' : '')),
                    percentage(
                        $data['Supplier Number Surplus Parts'],
                        $data['Supplier Number Parts']
                    ),
                    number($data['Supplier Number Surplus Parts'])
                ),
                'optimal'      => sprintf(
                    '<span  title="%s">%s</span>',
                    percentage(
                        $data['Supplier Number Optimal Parts'],
                        $data['Supplier Number Parts']
                    ),
                    number($data['Supplier Number Optimal Parts'])
                ),
                'low'          => sprintf(
                    '<span class="%s" title="%s">%s</span>',
                    (ratio(
                        $data['Supplier Number Low Parts'],
                        $data['Supplier Number Parts']
                    ) > .5
                        ? 'error'
                        : (ratio(
                            $data['Supplier Number Low Parts'],
                            $data['Supplier Number Parts']
                        ) > .25 ? 'warning' : '')),
                    percentage(
                        $data['Supplier Number Low Parts'],
                        $data['Supplier Number Parts']
                    ),
                    number($data['Supplier Number Low Parts'])
                ),
                'critical'     => sprintf(
                    '<span class="%s" title="%s">%s</span>',
                    ($data['Supplier Number Critical Parts'] == 0
                        ? ''
                        : (ratio(
                            $data['Supplier Number Critical Parts'],
                            $data['Supplier Number Parts']
                        ) > .25 ? 'error' : 'warning')),
                    percentage(
                        $data['Supplier Number Critical Parts'],
                        $data['Supplier Number Parts']
                    ),
                    number($data['Supplier Number Critical Parts'])
                ),
                'out_of_stock' => sprintf(
                    '<span class="%s" title="%s">%s</span>',
                    ($data['Supplier Number Out Of Stock Parts'] == 0
                        ? ''
                        : (ratio(
                            $data['Supplier Number Out Of Stock Parts'],
                            $data['Supplier Number Parts']
                        ) > .10 ? 'error' : 'warning')),
                    percentage(
                        $data['Supplier Number Out Of Stock Parts'],
                        $data['Supplier Number Parts']
                    ),
                    number($data['Supplier Number Out Of Stock Parts'])
                ),


                'location'  => $data['Supplier Location'],
                'email'     => $data['Supplier Main Plain Email'],
                'telephone' => $data['Supplier Preferred Contact Number Formatted Number'],
                'contact'   => $data['Supplier Main Contact Name'],
                'company'   => $data['Supplier Company Name'],


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

function production_parts($_data, $db, $user, $account)
{
    include_once 'utils/currency_functions.php';


    $rtext_label = 'production product';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    if ($data['Supplier Part Status'] == 'Discontinued') {
                    }

                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }

            $stock = number(floor($data['Part Current On Hand Stock']))." $stock_status";


            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>',
                        _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>',
                        _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>',
                        _('Discontinued')
                    );

                    if ($data['Part Current On Hand Stock'] == 0) {
                        $stock = '';
                    } else {
                        $stock = '<span class="error">'.number(floor($data['Part Current On Hand Stock'])).'</span>';
                    }


                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }


            $description_and_packing = $data['Supplier Part Description'].'<div class="very_discreet">'.sprintf(_('%s units per SKO'), $data['Part Units per Package']).' | '.sprintf(_('%s SKO per Carton'), $data['Supplier Part Packages Per Carton']).'</div>';


            $next_deliveries = '';

            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);
                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {
                        $next_deliveries .= sprintf(
                            ', '.$delivery['formatted_link']
                        );
                    }
                }
            }
            $next_deliveries = preg_replace('/^, /', '', $next_deliveries);


            $adata[] = array(
                'id'        => (integer)$data['Production Part Supplier Part Key'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'/production/%d/part/%d\')">%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']),

                'description' => $data['Supplier Part Description'],

                'description_and_packing' => $description_and_packing,
                'status'                  => $status,
                'cost'                    => money(
                    $data['Supplier Part Unit Cost'],
                    $data['Supplier Part Currency Code']
                ),

                'packing'    => '
				   <div style="float:right;min-width:30px;text-align:right" title="'._('Units per part').'"> <span class="strong" >'.($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span></div>
				   <div style="float:right;min-width:40px;text-align:center;"><i class="far fa-equals"></i></div>
				<div style="float:right;min-width:20px;text-align:right;" title="'._('Packages per part').'"><span>'.$data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:40px;text-align:center;"><i class="far fa-times"></i></div>
				<div style="float:right;min-width:20px;text-align:right" title="'._('Packed in (Units per packages)').'"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
                'stock'      => $stock,
                'components' => number($data['Production Part Raw Materials Number']),
                'tasks'      => number($data['Production Part Tasks Number']),

                'units_per_sko'   => number($data['Part Units per Package']),
                'sko_per_carton'  => number($data['Supplier Part Packages Per Carton']),
                'units_per_batch' => (!$data['Production Part Batch Size'] ? sprintf('<span class="italic discret error">%s</span>', _('Not set up')) : number($data['Production Part Batch Size'])),
                'next_deliveries' => $next_deliveries


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


function bill_of_materials($_data, $db, $user, $account)
{
    include_once 'utils/currency_functions.php';


    $rtext_label = 'components';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $production_key = $_SESSION['current_production'];


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $qty_units = $data['Bill of Materials Quantity'] * $data['Part Units per Package'];
            $qty_edit  = sprintf(
                '<span    data-settings=\'{"field": "Units", "item_key":%d  }\'   >
            <input class="bill_of_materials_item width_50" type="number" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_bill_of_materials_item_change(this)" class="fa save  fa-cloud fa-fw button" ></i></span>',
                $data['Part SKU'],
                $qty_units,
                $qty_units
            );


            $adata[] = array(
                'id'        => (integer)$data['Part SKU'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'/production/%d/materials/%d\')">%s</span>', $production_key, $data['Part SKU'], $data['Part Reference']),


                'description' => $data['Part Recommended Product Unit Name'].' <span class="italic very_discreet">('.sprintf(_('%s units per SKO'), $data['Part Units per Package']).')</span>',
                'cost_unit'   => money($data['Part Cost in Warehouse'] * $data['Bill of Materials Quantity'], $account->get('Account Currency')),
                'qty'         => number($qty_units),
                'qty_edit'    => $qty_edit,
                'qty_skos'    => number($data['Bill of Materials Quantity'], 4),

                'stock'                => number(floor($data['Part Current On Hand Stock'])),
                'stock_status'         => $stock_status,
                'available_to_make_up' => '<span class="item_available_to_make_up">'.number($data['Part Current On Hand Stock'] / $data['Bill of Materials Quantity'], 0).'</span>>'


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


function raw_materials($_data, $db, $user, $account)
{
    include_once 'utils/currency_functions.php';


    $rtext_label = 'raw material';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Raw Material Stock Status']) {
                case 'Unlimited':
                    $stock_status = '<i class="fa  fa-sparkles fa-fw" title="'._('Unlimited').'"></i>';
                    break;
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" title="'._('Surplus').'"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" title="'._('Ok').'"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" title="'._('Low').'""></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" title="'._('Critical').'"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" title="'._('Out of stock').'"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" title="'._('Error').'"></i>';
                    break;
                default:
                    $stock_status = $data['Raw Material Stock Status'];
                    break;
            }

            if ($data['Raw Material Type'] == 'Part') {
                $object = sprintf('<i class="fal button fa-box" onclick="change_view(\'part/%d\')"></i>', $data['Raw Material Type Key']);
            } elseif ($data['Raw Material Type'] == 'Consumable') {
                $object = sprintf('<i class="fal button fa-water" onclick="change_view(\'consumable/%d\')"></i>', $data['Raw Material Type Key']);
            } else {
                $object = '';
            }


            $stock_label = '<span class="small discreet">'.strtolower($data['Raw Material Unit Label']).'</span>';

            $adata[] = array(
                'id'               => (integer)$data['Raw Material Key'],
                'reference'        => sprintf('<span class="link" onclick="change_view(\'/production/%d/raw_materials/%d\')">%s</span>', $data['Raw Material Production Supplier Key'], $data['Raw Material Key'], $data['Raw Material Code']),
                'description'      => $data['Raw Material Description'],
                'stock_units'      => $stock_label.' '.number($data['Raw Material Stock']),
                'stock_status'     => $stock_status,
                'object'           => $object,
                'production_parts' => number($data['Raw Material Production Parts Number'])


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


function production_deliveries($_data, $db, $user)
{
    $rtext_label = 'production sheet';


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
                    '<span class="link" onclick="change_view(\'/production/%d/delivery/%d\')" >%s</span>  ',
                    $data['Supplier Delivery Parent Key'],
                    $data['Supplier Delivery Key'],
                    $data['Supplier Delivery Public ID']
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


function production_orders($_data, $db, $user, $account)
{
    $rtext_label = 'job order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $notes = '';
            //'Cancelled','NoReceived','InProcess','Submitted','Confirmed','Manufactured','QC_Pass','Inputted','Dispatched','Received','Checked','Placed','Costing','InvoiceChecked'
            switch ($data['Purchase Order State']) {
                case 'InProcess':
                    $state = _('Planning');
                    if ($data['Purchase Order Estimated Start Production Date'] != '') {
                        $notes .= ' <span class="super_discreet" title="'._('Scheduled start production date').'"><i class="fal fa-play success small"></i> <span class="discreet italic">'.strftime(
                                "%a %e %b",
                                strtotime($data['Purchase Order Estimated Start Production Date'].' +0:00')
                            ).'</span>';
                    }
                    break;
                case 'Submitted':
                    $state = _('Queued');

                    if ($data['Purchase Order Estimated Start Production Date'] != '') {
                        $notes .= ' <span title="'._('Scheduled start production date').'"><i class="fal fa-play success small"></i> <span class="discreet italic">'.strftime(
                                "%a %e %b",
                                strtotime($data['Purchase Order Estimated Start Production Date'].' +0:00')
                            ).'</span>';
                    }
                    break;

                case 'Confirmed':
                    $state = _('Manufacturing');

                    if ($data['Purchase Order Estimated Receiving Date'] != '') {
                        $notes .= ' <span title="'._('Estimated production date').'"><i class="fal fa-play  purple small"></i> <span class="discreet italic">'.strftime(
                                "%a %e %b",
                                strtotime($data['Purchase Order Estimated Receiving Date'].' +0:00')
                            ).'</span>';
                    }

                    break;
                case 'Manufactured':
                    $state = _('Manufactured');
                    break;
                case 'QC Pass':
                    $state = _('QC passed');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                case 'Checked':
                    $state = _('Delivered');

                    break;
                case 'Placed':
                case 'InvoiceChecked':
                case 'Costing':
                    $state = _('Placed');
                    break;


                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order State'];
                    break;
            }

            if ($data['Staff Alias'] == '') {
                $worker = '<span class="super_discreet italic">'._('Not set').'</span>';
            } else {
                $worker = sprintf('<span class="link" onclick="change_view(\'production/%d/operatives/%d\')" >%s</span>', $data['Purchase Order Parent Key'], $data['Purchase Order Operator Key'], $data['Staff Alias']);
            }


            if ($data['Purchase Order Total Amount'] == '' or $data['Purchase Order Total Amount'] == 0) {
                $total_amount = '';
            } else {
                $total_amount = money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code'], $locale = false, 'NO_FRACTION_DIGITS');
            }


            $table_data[] = array(
                'id'        => (integer)$data['Purchase Order Key'],
                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'production/%d/order/%d\')" >%s</span>  ',
                    $data['Purchase Order Parent Key'],
                    $data['Purchase Order Key'],
                    ($data['Purchase Order Public ID'] == '' ? '<i class="fa fa-exclamation-circle error"></i> <span class="very_discreet italic">'._('empty').'</span>' : $data['Purchase Order Public ID'])
                ),
                'date'      => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'     => $state,
                'weight'    => weight($data['Purchase Order Weight'], '', 0, true),
                'products'  => $data['Purchase Order Ordered Number Items'],

                'total_amount' => $total_amount,
                'worker'       => $worker,
                'notes'        => $notes


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


function production_deliveries_with_part($_data, $db, $user)
{
    $rtext_label = 'production sheet';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Purchase Order Transaction State']) {
                case 'InProcess':
                    $state = _('Planning');
                    break;
                case 'Submitted':
                    $state = _('Manufacturing');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                    $state = _('In quality control');
                    break;
                case 'Checked':
                    $state = _('In quality control').' ('._('Check done').')';
                    break;
                case 'Placed':
                case 'InvoiceChecked':

                    $state = _('Booked in');
                    break;
                case 'Costing':

                    $state = _('Booked in').' ('._('Review costing').')';
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order Transaction State'];
                    break;
            }


            $table_data[] = array(
                'id' => (integer)$data['Supplier Delivery Key'],

                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),


                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'/production/%d/delivery/%d\')" >%s</span>  ',
                    $data['Supplier Delivery Parent Key'],
                    $data['Supplier Delivery Key'],
                    $data['Supplier Delivery Public ID']
                ),


                'state' => $state,
                //'total_amount' => money($data['Supplier Delivery Total Amount'], $data['Supplier Delivery Currency Code'])


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


function production_orders_with_part($_data, $db, $user, $account)
{
    if (!$user->can_view('production')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'job order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Purchase Order Transaction State']) {
                case 'InProcess':
                    $state = _('Planning');
                    break;
                case 'Submitted':
                    $state = _('Manufacturing');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                    $state = _('In quality control');
                    break;
                case 'Checked':
                    $state = _('In quality control').' ('._('Check done').')';
                    break;
                case 'Placed':
                case 'InvoiceChecked':

                    $state = _('Booked in');
                    break;
                case 'Costing':

                    $state = _('Booked in').' ('._('Review costing').')';
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order Transaction State'];
                    break;
            }


            if ($data['Purchase Order State'] == 'InProccess') {
                $qty_units = number($data['Purchase Order Ordering Units']);
                $qty_skos  = number($data['Purchase Order Ordering Units'] / $data['Part Units Per Package']);
            } else {
                $qty_units = number($data['Purchase Order Submitted Units']);
                $qty_skos  = number($data['Purchase Order Submitted Units'] / $data['Purchase Order Submitted Units Per SKO']);
            }


            $table_data[] = array(
                'id'        => (integer)$data['Purchase Order Key'],
                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'production/%d/order/%d\')" >%s</span>  ',
                    $data['Purchase Order Parent Key'],
                    $data['Purchase Order Key'],
                    ($data['Purchase Order Public ID'] == '' ? '<i class="fa fa-exclamation-circle error"></i> <span class="very_discreet italic">'._('empty').'</span>' : $data['Purchase Order Public ID'])
                ),
                'date'      => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'     => $state,
                'qty_units' => $qty_units,
                'qty_skos'  => $qty_skos

                //'total_amount' => money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']),


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


function production_urgent_to_do($_data, $db, $user)
{
    $rtext_label = 'part to produce as soon as possible';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $next_deliveries = '';

            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);
                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {
                        $next_deliveries .= sprintf(
                            ', '.$delivery['formatted_link']
                        );
                    }
                }
            }
            $next_deliveries = preg_replace('/^, /', '', $next_deliveries);


            $stock_available = $data['Part Current On Hand Stock'] - $data['Part Current Stock In Process'] - $data['Part Current Stock Ordered Paid'];


            $stock = '<span class="very_discreet small padding_right_10"><i class="fal fa-inventory"></i> '.number($data['Part Current On Hand Stock']).' <i class="fal fa-shopping-cart"></i> '.number(
                    $data['Part Current Stock In Process'] + $data['Part Current Stock Ordered Paid']
                ).'</span>';

            $stock .= '<b>'.number($stock_available).'</b>';


            $table_data[] = array(
                'id'           => (integer)$data['Supplier Part Key'],
                'supplier_key' => (integer)$data['Supplier Part Supplier Key'],


                'reference' => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Supplier Part Part SKU'], $data['Part Reference']),

                'description'     => $data['Part Package Description'],
                'cost'            => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing'         => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock_status'    => $stock_status,
                'stock'           => $stock,
                'required'        => number(ceil($data['required']), 0),
                'next_deliveries' => $next_deliveries

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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function todo_parts($_data, $db, $user)
{
    $rtext_label = 'part with critical stock or out of stock';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql_totals;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>',
                        _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>',
                        _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>',
                        _('Discontinued')
                    );

                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }

            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $description = $data['Part Package Description'];


            $description .= '

  <div class="as_table asset_sales discreet">

          <div class="as_row header">
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -12 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -9 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -6 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -3 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			</div>
		 <div class="as_row header">
			<div class="as_cell width_75">'.number(
                    $data['Part 4 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 3 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 2 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 1 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part Quarter To Day Acc Dispatched']
                ).'</div>
			</div>
			</div>



			';


            $available_forecast = seconds_to_until(
                $data['Part Days Available Forecast'] * 86400
            );


            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52,
                0
            );


            $next_deliveries = '';

            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);
                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {
                        $next_deliveries .= sprintf(
                            ', '.$delivery['formatted_link']
                        );
                    }
                }
            }
            $next_deliveries = preg_replace('/^, /', '', $next_deliveries);

            $stock_available = $data['Part Current On Hand Stock'] - $data['Part Current Stock In Process'] - $data['Part Current Stock Ordered Paid'];


            $stock = '<span class="very_discreet small padding_right_10"><i class="fal fa-inventory"></i> '.number($data['Part Current On Hand Stock']).' <i class="fal fa-shopping-cart"></i> '.number(
                    $data['Part Current Stock In Process'] + $data['Part Current Stock Ordered Paid']
                ).'</span>';

            $stock .= '<b>'.number($stock_available).'</b>';


            $table_data[] = array(
                'id' => (integer)$data['Supplier Part Key'],

                'reference' => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Supplier Part Part SKU'], $data['Part Reference']),

                'description'         => $description,
                'simple_description'  => $data['Part Package Description'],
                'status'              => $status,
                'cost'                => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing'             => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'               => $stock,
                'available_forecast'  => $available_forecast,
                'dispatched_per_week' => $dispatched_per_week,
                'next_deliveries'     => $next_deliveries


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function external_products($_data, $db, $user)
{
    $rtext_label = 'low stock parts '.$_data['parameters']['parent_key'];


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql_totals;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>',
                        _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>',
                        _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>',
                        _('Discontinued')
                    );

                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }

            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $description = $data['Part Package Description'];


            $description .= '

  <div class="as_table asset_sales discreet">

          <div class="as_row header">
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -12 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -9 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -6 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(
                    strtotime('now -3 months')
                ).'</div>
			<div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			</div>
		 <div class="as_row header">
			<div class="as_cell width_75">'.number(
                    $data['Part 4 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 3 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 2 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part 1 Quarter Ago Dispatched']
                ).'</div>
			<div class="as_cell width_75">'.number(
                    $data['Part Quarter To Day Acc Dispatched']
                ).'</div>
			</div>
			</div>



			';


            $available_forecast = seconds_to_until(
                $data['Part Days Available Forecast'] * 86400
            );


            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52,
                0
            );


            $next_deliveries = '';

            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);


                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {
                        $next_deliveries .= sprintf(
                            '<div> '.$delivery['order_id'].'<br/> ('.$delivery['formatted_state'].')  <b>'.$delivery['qty'].'</b></div>'
                        );
                    }
                }
            }

            $stock_available = $data['Part Current On Hand Stock'] - $data['Part Current Stock In Process'] - $data['Part Current Stock Ordered Paid'];


            $stock = '<span class="very_discreet small padding_right_10"><i class="fal fa-inventory"></i> '.number($data['Part Current On Hand Stock']).' <i class="fal fa-shopping-cart"></i> '.number(
                    $data['Part Current Stock In Process'] + $data['Part Current Stock Ordered Paid']
                ).'</span>';

            $stock .= '<b>'.number($stock_available).'</b>';


            if($data['own_data']==''){
                $own_data=[
                    '-','-'
                ];
                $reference='<span class="error">'.$data['Part Reference'].'</span>';
            }else{
                $own_data = explode(',', $data['own_data']);

                $reference=sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $own_data[0], $data['Part Reference']);
            }





            $table_data[] = array(
                'id' => (integer)$data['Supplier Part Key'],

                'reference' =>$reference,


                'stock_status'        => $stock_status,
                'description'         => $description,
                'simple_description'  => $data['Part Package Description'],
                'status'              => $status,
                'cost'                => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing'             => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'               => $stock,
                'available_forecast'  => $available_forecast,
                'dispatched_per_week' => $dispatched_per_week,
                'next_deliveries'     => $next_deliveries,
                'our_stock'           => number($own_data[1])


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