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

    case 'parts_no_sko_barcode':
        parts_no_sko_barcode(get_table_parameters(), $db, $user, '', $account);
        break;
    case 'parts_weight_errors':
        parts_weight_errors(get_table_parameters(), $db, $user, '', $account);
        break;
    case 'parts_barcode_errors':
        parts_barcode_errors(get_table_parameters(), $db, $user, '', $account);
        break;
    case 'parts':
        parts(get_table_parameters(), $db, $user, '', $account);
        break;
    case 'active_parts':
        parts(get_table_parameters(), $db, $user, 'active', $account);
        break;
    case 'discontinued_parts':
        parts_discontinued(get_table_parameters(), $db, $user, 'discontinued', $account);
        break;
    case 'discontinuing_parts':
        parts_discontinuing(get_table_parameters(), $db, $user, $account);
        break;
    case 'in_process_parts':
        parts(get_table_parameters(), $db, $user, 'in_process', $account);
        break;
    case 'category_discontinued_parts':
        parts(get_table_parameters(), $db, $user, 'discontinued', $account);
        break;
    case 'stock_transactions':
        stock_transactions(get_table_parameters(), $db, $user);
        break;
    case 'stock_history':
        stock_history(get_table_parameters(), $db, $user, $account);
        break;
    case 'inventory_stock_history':
        inventory_stock_history(get_table_parameters(), $db, $user, $account);
        break;

    case 'barcodes':
        barcodes(get_table_parameters(), $db, $user);
        break;
    case 'supplier_parts':
        supplier_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'part_families':
        part_families(get_table_parameters(), $db, $user, $account);
        break;
    //  case 'categories':
    //    categories(get_table_parameters(), $db, $user,$account);
    //    break;
    case 'product_families':
        product_families(get_table_parameters(), $db, $user);
        break;
    case 'category_all_available_parts':
        category_all_available_parts(get_table_parameters(), $db, $user);
        break;
    case 'category_all_parts':
        category_all_parts(get_table_parameters(), $db, $user);
        break;
    case 'sales_history':
        sales_history(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock.history.day':
        stock_history_day(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock_cost':
        stock_cost(get_table_parameters(), $db, $user, $account);
        break;
    case 'part_family_part_locations':
        part_family_part_locations(get_table_parameters(), $db, $user, $account);
        break;
    case 'part_locations':
        part_locations(get_table_parameters(), $db, $user, $account);
        break;
    case 'feedback':
        feedback(get_table_parameters(), $db, $user, $account);
        break;
    case 'feedback_per_part':
        feedback_per_part(get_table_parameters(), $db, $user, $account);
        break;
    case 'feedback_per_part_family':
        feedback_per_part_family(get_table_parameters(), $db, $user, $account);
        break;
    case 'parts_no_products':
        parts_no_products(get_table_parameters(), $db, $user, $account);
        break;
    case 'parts_forced_not_for_sale_on_website':
        parts_forced_not_for_sale_on_website(get_table_parameters(), $db, $user, $account);
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


function parts($_data, $db, $user, $type, $account) {


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
        $rtext_label = 'part';

    } elseif ($type == 'discontinuing') {
        $extra_where = ' and `Part Status`="Discontinuing"';
        $rtext_label = 'part';

    } elseif ($type == 'discontinued') {
        $extra_where = ' and `Part Status`="Not In Use"';
        $rtext_label = 'discontinued part';

    } elseif ($type == 'in_process') {
        $extra_where = ' and `Part Status`="In Process"';
        $rtext_label = 'part in process';

    } else {
        $extra_where = ' and `Part Status`!="Not In Use"';
        $rtext_label = 'part';

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
                    $data['Part Days Available Forecast'] / 7, 0
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
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']
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
                    '<span class="link" onclick="change_view(\'category/%d/part/%d\')">%s</span>', $_data['parameters']['parent_key'], $data['Part SKU'],
                    ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
                );

            } else {
                $reference = sprintf(
                    '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
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
                    '<span>%s</span> %s', money(
                    $data['Part Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Year To Day Acc Invoiced Amount"], $data["Part Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Year Ago Invoiced Amount"], $data["Part 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Year Ago Invoiced Amount"], $data["Part 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Year Ago Invoiced Amount"], $data["Part 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Year Ago Invoiced Amount"], $data["Part 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money($data['Part Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')), delta_icon($data["Part Quarter To Day Acc Invoiced Amount"], $data["Part Quarter To Day Acc 1YB Invoiced Amount"])
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Quarter Ago Invoiced Amount"], $data["Part 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Quarter Ago Invoiced Amount"], $data["Part 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Quarter Ago Invoiced Amount"], $data["Part 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Quarter Ago Invoiced Amount"], $data["Part 4 Quarter Ago 1YB Invoiced Amount"]
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


function stock_history($_data, $db, $user, $account) {

    include_once 'elastic/isf.elastic.php';


    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label       = 'year';
        $date_format       = "%Y";
        $calendar_interval = '1y';
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label       = 'month';
        $date_format       = "%b %Y";
        $calendar_interval = '1M';
    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label       = 'week';
        $date_format       = "(%e %b) %Y %W";
        $calendar_interval = '1w';
    } else {
        $rtext_label       = 'day';
        $date_format       = "%a %e %b %Y";
        $calendar_interval = '1d';
    }

    include_once 'prepare_table/init.php';


    $results = get_part_inventory_transaction_fact('stock_history', $_data['parameters']['parent_key'], $calendar_interval);


    list($rtext, $total, $filtered) = get_table_totals(
        $db, false, '', $rtext_label, [
               'filtered'      => 0,
               'total_records' => $results['hits']['total']['value'],
               'total'         => $results['hits']['total']['value']
           ]
    );


    $record_data = array();


    foreach ($results['aggregations']['stock_per_day']['buckets'] as $data) {


        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
            $value = money($data['stock_cost']['value'], $account->get('Currency Code'));
        } else {
            $value = money($data['stock_value_at_day_cost']['value'], $account->get('Currency Code'));
        }
        $record_data[] = array(


            'date'  => strftime($date_format, strtotime($data['key_as_string'].' +0:00')),
            'stock' => number($data['stock']['value']),
            'value' => $value,
            'in'    => number($data['book_in']['value']),
            'sold'  => number($data['sold']['value']),
            'lost'  => number($data['lost']['value'])

        );


    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $order,
            'sort_dir'      => $order_direction,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function inventory_stock_history($_data, $db, $user, $account) {

    include_once 'elastic/warehouse_isf_date_histogram.elastic.php';


    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label = 'year';

        $date_format = "%Y";
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label = 'month';
        $date_format = "%e %b %Y";

    } elseif ($_data['parameters']['frequency'] == 'quarterly') {
        $rtext_label = 'quarter';

        $date_format = "%e %b %Y";

    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label = 'week';

        $date_format = "(%e %b) %Y %W";

    } else {
        $rtext_label = 'day';

        $date_format = "%a %e %b %Y";

    }


    include_once 'prepare_table/init.php';


    $results = get_warehouse_isf($_data);


    list($rtext, $total, $filtered) = get_table_totals(
        $db, false, '', $rtext_label, [
               'filtered'      => 0,
               'total_records' => $results['total']['value'],
               'total'         => $results['total']['value']
           ]
    );


    $record_data = array();
    $i           = 0;
    foreach ($results['hits'] as $result) {

        $data = $result['_source'];

        $date = strftime($date_format, strtotime($data['date'].' +0:00'));

        if ($_data['parameters']['frequency'] == 'daily') {

            $date = sprintf(
                '<span class="link" onclick="change_view(\'inventory/stock_history/day/%s\')" >%s</span>', $data['date'], $date
            );
        }

        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
            $value = money($data['stock_cost'], $account->get('Currency Code'));
        } else {
            $value = money($data['stock_value_at_day_cost'], $account->get('Currency Code'));
        }

        $record_data[] = array(
            'id'               => $i++,
            'date'             => $date,
            'parts'            => number($data['parts']),
            'locations'        => number($data['locations']),
            'value'            => $value,
            'commercial_value' => money($data['stock_commercial_value'], $account->get('Currency Code')),
            // 'in_po'            => sprintf('<span class="%s">%s</span>', ($data['in_po'] == 0 ? 'super_discreet' : ''), money($data['in_po'], $account->get('Currency Code'))),
            // 'in_other'         => sprintf('<span class="%s">%s</span>', ($data['in_other'] == 0 ? 'super_discreet' : ''), money($data['in_other'], $account->get('Currency Code'))),
            //'out_sales'        => sprintf('<span class="%s">%s</span>', ($data['out_sales'] == 0 ? 'super_discreet' : ''), money($data['out_sales'], $account->get('Currency Code'))),
            //'out_other'        => sprintf('<span class="%s">%s</span>', ($data['out_other'] == 0 ? 'super_discreet' : ''), money($data['out_other'], $account->get('Currency Code'))),


        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $order,
            'sort_dir'      => $order_direction,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function stock_history_day($_data, $db, $user, $account) {


    $rtext_label = 'part';

    include_once 'elastic/part_isf_date.elastic.php';
    include_once 'prepare_table/init.php';


    $results = get_elastic_stock_history_day($_data);


    list($rtext, $total, $filtered) = get_table_totals(
        $db, false, '', $rtext_label, [
               'filtered'      => 0,
               'total_records' => $results['total']['value'],
               'total'         => $results['total']['value']
           ]
    );


    $record_data = array();


    foreach ($results['hits'] as $row) {

        $data = $row['_source'];


        $record_data[] = array(
            'part_reference' => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['sku'], $data['part_reference']),

            'description' => $data['part_description'],

            'stock_on_hand' => number($data['stock_on_hand']),
            'stock_cost'    => money($data['stock_cost'], $account->get('Currency Code')),

            'stock_value_at_day_cost' => money($data['stock_value_at_day_cost'], $account->get('Currency Code')),


            'sko_cost'              => money($data['sko_cost'], $account->get('Currency Code')),
            'book_in'               => number($data['book_in']),
            'sold'                  => number($data['sold']),
            'lost'                  => number($data['lost']),
            // 'given'                 => number($data['given']),
            'stock_left_1_year_ago' => (($data['stock_left_1_year_ago'] == '' or $data['no_sales_1_year_icon'] == 'fal fa-seedling') ? '' : number($data['stock_left_1_year_ago'])),
            'no_sales_1_year'       => sprintf('<i class="%s"></i>', $data['no_sales_1_year_icon'])
        );


    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => 'Date',
            'sort_dir'      => $order_direction,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function stock_history_day_old($_data, $db, $user, $account) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $_datagms_1_year_back = gmdate('U', strtotime($parameters['parent_key'].' - 1 year'));


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if (gmdate('U', strtotime($data['Part Valid From'])) > $_datagms_1_year_back) {

                $no_sales_1_year       = '<span class="super_discreet"><i class="fal fa-seedling"></i></span>';
                $stock_left_1_year_ago = '<span class="super_discreet"><i class="fal fa-seedling"></i></span>';


            } else {
                switch ($data['no_sales_1_year']) {
                    case 'Yes':
                        $no_sales_1_year = '<span class="error"><i class="fa fa-snooze"></i></span>';
                        break;
                    case 'No':
                        $no_sales_1_year = '<span class="success"><i class="fa fa-check"></i></span>';
                        break;
                    case '':
                        $no_sales_1_year = '<span class="error"><i class="fa fa-question"></i></span>';
                        break;
                    default:
                        $no_sales_1_year = $data['no_sales_1_year'];
                }
                $stock_left_1_year_ago = ($data['stock_left_1_year_ago'] == '' ? '' : number($data['stock_left_1_year_ago']));
            }


            $record_data[] = array(
                'reference' => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),

                'description' => $data['Part Package Description'],

                'stock'                 => number($data['stock']),
                'stock_value'           => money($data['stock_value'], $account->get('Currency Code')),
                'cost'                  => money($data['cost'], $account->get('Currency Code')),
                'in'                    => number($data['book_in']),
                'sold'                  => number($data['sold']),
                'lost'                  => number($data['lost']),
                'given'                 => number($data['given']),
                'stock_left_1_year_ago' => $stock_left_1_year_ago,
                'no_sales_1_year'       => $no_sales_1_year
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


function stock_transactions($_data, $db, $user) {


    $rtext_label = 'transaction';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            //MossRB-04 227330 Taken from: 11A1

            $note   = $data['Note'];
            $change = $data['Inventory Transaction Quantity'];
            $stock  = $data['Running Stock'];


            switch ($data['Inventory Transaction Type']) {
                case 'Order In Process':


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

                    $change = '';

                    break;


                case 'Restock':
                    $type = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _('%s returned to %s from cancelled %s'),

                            number(
                                $data['Inventory Transaction Quantity']
                            ).' <span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button strong" onClick="change_view(\'locations/%d/%d\')">%s</span>', $data['Warehouse Key'], $data['Location Key'], $data['Location Code']
                            ),

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
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
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code']
                            )

                        );
                    }


                    break;

                case 'Sale':
                case 'FailSale':
                    $type = '<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>';
                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _('%s taken from %s for %s'),

                            number(
                                -1 * $data['Inventory Transaction Quantity']
                            ).' <span title="'._('Stock keeping outers').'">SKO</span>', sprintf(
                                '<span class="button strong" onClick="change_view(\'locations/%d/%d\')">%s</span>', $data['Warehouse Key'], $data['Location Key'], $data['Location Code']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
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
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            ), sprintf(
                                '<span class="button" onClick="change_view(\'locations/%d/%d\')">%s</span>', $data['Warehouse Key'], $data['Location Key'], $data['Location Code']
                            )

                        );
                    }
                    $pending = $data['pending'];
                    if ($pending > 0) {
                        $note .= ' <span class="discreet italic">('.sprintf(_('%s to be picked'), $pending).')</span>';

                    }
                    if ($data['Inventory Transaction Type'] == 'FailSale') {
                        $note .= ' <span class="warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '._('Returned').'</span>';
                    }

                    break;
                case 'In':
                    $type = '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Audit':


                    $type = '<i class="fa fa-fw fa-dot-circle" aria-hidden="true"></i>';

                    $change = sprintf('<b>'.$data['Part Location Stock'].'</b>');
                    $stock  = '';
                    break;
                case 'Adjust':

                    if ($change > 0) {
                        $change = '+'.number($change);
                    }

                    $type = '<i class="fa fa-fw fa-sliders" aria-hidden="true"></i>';


                    break;

                case 'Move':
                    $change = '±'.number($data['Metadata']);
                    $type   = '<i class="fa fa-sync fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $type = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Lost':
                    $type = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Broken':
                    $type = '<i class="fa fa-cross error fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Production':
                    $type = '<i class="far fa-hand-rock  fa-fw" title="'._('Send to production').'" aria-hidden="true"></i>';
                    break;
                case 'Other Out':
                    $type = '<i class="fa fa-exclamation-circle  error fa-fw" aria-hidden="true"></i>';
                    break;

                case 'No Dispatched':

                    $change = '';
                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _(
                                "%s requested %s <b>couldn't be dispatched</b> (%s)"
                            ),

                            number($data['Required']), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            )


                        );
                    } else {
                        $note = sprintf(
                            _(
                                "%s requested %s %s <b>couldn't be dispatched</b> (%s)"
                            ), number($data['Required']),

                            sprintf(
                                '<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference']
                            ), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']
                            )

                        );
                    }


                    $type = '<i class="fa fa-circle error fa-fw" aria-hidden="true"></i>';
                    break;

                default:
                    $type = $data['Inventory Transaction Section'];
                    break;
            }


            $record_data[] = array(
                'id'   => (integer)$data['Inventory Transaction Key'],
                'date' => strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')
                ),
                'user' => sprintf(
                    '<span title="%s">%s</span>', $data['User Alias'], $data['User Handle']
                ),

                'change' => $change,
                'stock'  => $stock,
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function supplier_parts($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';


    $rtext_label = 'supplier part';
    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
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

            $reference = sprintf('<span class="link" onClick="change_view(\'supplier/%d/part/%d\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']);


            $operations = '';

            if ($data['Part Main Supplier Part Key'] == $data['Supplier Part Key']) {


                $principal = '<i class="fa fa-trophy principal_'.$data['Supplier Part Key'].'"  title="'._('Preferred supplier').'"  ></i>';


                $operations = '';
            } else {

                $principal  = '<i class="fal fa-snooze principal_'.$data['Supplier Part Key'].'" title="'._('Backup supplier').'"  ></i>';
                $operations .= sprintf(
                    '<div style="margin-bottom:10px"><span class="button" id="set_as_principal_supplier_part_button_%d" onClick="set_as_principal_supplier_part(%d,%d)">%s</span></div>', $data['Supplier Part Key'], $data['Supplier Part Part SKU'],
                    $data['Supplier Part Key'], _('Set as').' <i class="fal fa-trophy padding_right_5"</i>'
                );


            }


            $record_data[] = array(
                'id' => (integer)$data['Supplier Part Key'],
                //  'data' => '<span id="item_data_'.$data['Supplier Part Key'].'" class="item_data" data-key="'.$data['Supplier Part Key'].'" ></span>',

                'supplier_code' => sprintf('<span class="link" onClick="change_view(\'supplier/%d/\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Code']),
                'principal'     => $principal,

                'reference' => $reference,

                'cbm' => ($data['Supplier Part Carton CBM'] != '' ? $data['Supplier Part Carton CBM'].'m³' : '<i class="fa fa-exclamation-circle error"></i>'),


                'description'    => '<span  data-field="Supplier Part Description"  data-item_class="item_Supplier_Part_Description" class="table_item_editable item_Supplier_Part_Description"  >'.$data['Supplier Part Description'].'</span>',
                'status'         => $status,
                'cost'           => sprintf(
                    '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>', $data['Supplier Part Key'], $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'],
                    money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'])
                ),
                'delivered_cost' => '<span title="'.$exchange_info.'">'.money(
                        $exchange * ($data['Supplier Part Unit Cost'] + $data['Supplier Part Unit Extra Cost']), $account->get('Account Currency')
                    ).'</span>',
                'sko_per_carton' => '
				 <span title="'._('Units per carton').'"><i style="font-size: 80%;margin-right: 1px" class="fal fa-stop-circle very_discreet"></i><i style="position: relative;top:1px;margin-right: 3px" class="fal fa-times very_discreet"></i>'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>
				<span class="discreet '.($data['Part Units Per Package'] == 1 ? 'hide' : '').' " title="'._('Packages (SKOs) per carton').'" > ('.$data['Supplier Part Packages Per Carton'].')</span>
				</div>
				 '),

                'operations' => $operations,


                'next_deliveries' => $next_deliveries,


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


function barcodes($_data, $db, $user) {


    $rtext_label = 'barcodes';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Barcode Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-barcode fa-fw" ></i> %s', _('Available')
                    );
                    break;
                case 'Used':
                    $status = sprintf(
                        '<span class="disabled"><i class="fa fa-cube fa-fw " ></i> %s', _('Used').'</span>'
                    );

                    break;
                case 'Reserved':
                    $status = sprintf(
                        '<span class="disabled"> <i class="fa fa-shield fa-fw " ></i> %s', _('Reserved').'</span>'
                    );

                    break;
                default:
                    $status = $data['Barcode Status'];
                    break;
            }
            if ($data['parts'] != '') {
                $_parts = preg_split('/,/', $data['parts']);
                $assets = sprintf(
                    '<i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $_parts[0], $_parts[1]
                );
            } else {
                $assets = '';
            }

            $record_data[] = array(
                'id'     => (integer)$data['Barcode Key'],
                'link'   => '<i class="fa fa-barcode "></i>',
                // '<span class="fa-stack fa-lg"><i class="fa fa-barcode fa-stack-1x"></i><i class="fa fa-angle-down fa-inverse fa-stack-1x"></i></span>',
                'number' => $data['Barcode Number'],

                'status' => $status,
                'notes'  => $data['Barcode Sticky Note'],
                'assets' => $assets

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


function part_families($_data, $db, $user, $account) {


    if ($_data['parameters']['parent_key'] == $account->get('Account Part Family Category Key')) {
        $rtext_label = 'family';
    } else {
        $rtext_label = 'category';
    }


    // print_r($_data);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    //print $sql;
    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Part Category Status']) {
                case 'InUse':
                    $status = '<i class="far fa-fw fa-boxes" title="'._('Active').'"></i>';
                    break;
                case 'Discontinuing':
                    $status = '<i class="far fa-boxes discreet warning" title="'._('Discontinuing').'"></i>';
                    break;
                case 'NotInUse':
                    $status = '<i class="fal error fa-boxes  discreet" title="'._('Discontinued').'"></i>';
                    break;
                case 'InProcess':
                    $status = '<i class="fal fa-seedling" title="'._('In process').'"></i>';
                    break;
                default:
                    $status = '';
                    break;
            }

            $code = sprintf(
                '<span class="link" onclick="change_view(\'category/%d\')">%s</span>', $data['Category Key'], ($data['Category Code'] == '' ? '<i class="fa error fa-exclamation-circle"> <span class="discreet italic">'._('No code set').'</span>' : $data['Category Code'])
            );

            $record_data[] = array(
                'id'        => (integer)$data['Category Key'],
                'store_key' => (integer)$data['Category Store Key'],
                'code'      => $code,
                'label'     => $data['Category Label'],

                'in_process'    => number($data['Part Category In Process']),
                'active'        => number($data['Part Category Active']),
                'discontinuing' => number($data['Part Category Discontinuing']),
                'discontinued'  => number($data['Part Category Discontinued']),
                'status'        => $status,

                'subcategories'       => number($data['Category Children']),
                'percentage_assigned' => percentage($data['Category Number Subjects'], ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])),

                'surplus'      => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Part Category Number Surplus Parts'], $data['Category Number Active Subjects']
                ) > .75
                    ? 'error'
                    : (ratio(
                        $data['Part Category Number Surplus Parts'], $data['Category Number Active Subjects']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Part Category Number Surplus Parts'], $data['Category Number Active Subjects']
                    ), number($data['Part Category Number Surplus Parts'])
                ),
                'optimal'      => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Part Category Number Optimal Parts'], $data['Category Number Active Subjects']
                ), number($data['Part Category Number Optimal Parts'])
                ),
                'low'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Part Category Number Low Parts'], $data['Category Number Active Subjects']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Part Category Number Low Parts'], $data['Category Number Active Subjects']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Part Category Number Low Parts'], $data['Category Number Active Subjects']
                    ), number($data['Part Category Number Low Parts'])
                ),
                'critical'     => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Part Category Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Part Category Number Critical Parts'], $data['Category Number Active Subjects']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Part Category Number Critical Parts'], $data['Category Number Active Subjects']
                    ), number($data['Part Category Number Critical Parts'])
                ),
                'out_of_stock' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Part Category Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Part Category Number Out Of Stock Parts'], $data['Category Number Active Subjects']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Part Category Number Out Of Stock Parts'], $data['Category Number Active Subjects']
                    ), number($data['Part Category Number Out Of Stock Parts'])
                ),
                'stock_error'  => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Part Category Number Error Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Part Category Number Error Parts'], $data['Category Number Active Subjects']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Part Category Number Error Parts'], $data['Category Number Active Subjects']
                    ), number($data['Part Category Number Error Parts'])
                ),

                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => delta(
                    $data['dispatched'], $data['dispatched_1yb']
                ),
                'sales'          => money(
                    $data['sales'], $account->get('Account Currency')
                ),
                'sales_1yb'      => delta($data['sales'], $data['sales_1yb']),

                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category Year To Day Acc Invoiced Amount"], $data["Part Category Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 1 Year Ago Invoiced Amount"], $data["Part Category 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 2 Year Ago Invoiced Amount"], $data["Part Category 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 3 Year Ago Invoiced Amount"], $data["Part Category 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 4 Year Ago Invoiced Amount"], $data["Part Category 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category Quarter To Day Acc Invoiced Amount"], $data["Part Category Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 1 Quarter Ago Invoiced Amount"], $data["Part Category 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 2 Quarter Ago Invoiced Amount"], $data["Part Category 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 3 Quarter Ago Invoiced Amount"], $data["Part Category 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Category 4 Quarter Ago Invoiced Amount"], $data["Part Category 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


                'dispatched_year0' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category Year To Day Acc Dispatched']), delta_icon(
                                            $data["Part Category Year To Day Acc Dispatched"], $data["Part Category Year To Day Acc 1YB Dispatched"]
                                        )
                ),
                'dispatched_year1' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 1 Year Ago Dispatched']), delta_icon(
                                            $data["Part Category 1 Year Ago Dispatched"], $data["Part Category 2 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year2' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 2 Year Ago Dispatched']), delta_icon(
                                            $data["Part Category 2 Year Ago Dispatched"], $data["Part Category 3 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year3' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 3 Year Ago Dispatched']), delta_icon(
                                            $data["Part Category 3 Year Ago Dispatched"], $data["Part Category 4 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year4' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 4 Year Ago Dispatched']), delta_icon(
                                            $data["Part Category 4 Year Ago Dispatched"], $data["Part Category 5 Year Ago Dispatched"]
                                        )
                ),

                'dispatched_quarter0' => sprintf(
                    '<span>%s</span> %s', number(
                    $data['Part Category Quarter To Day Acc Dispatched']
                ), delta_icon(
                        $data["Part Category Quarter To Day Acc Dispatched"], $data["Part Category Quarter To Day Acc 1YB Dispatched"]
                    )
                ),
                'dispatched_quarter1' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 1 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part Category 1 Quarter Ago Dispatched"], $data["Part Category 1 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter2' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 2 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part Category 2 Quarter Ago Dispatched"], $data["Part Category 2 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter3' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 3 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part Category 3 Quarter Ago Dispatched"], $data["Part Category 3 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter4' => sprintf(
                    '<span>%s</span> %s', number($data['Part Category 4 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part Category 4 Quarter Ago Dispatched"], $data["Part Category 4 Quarter Ago 1YB Dispatched"]
                                        )
                ),


                'sales_total'         => money($data['Part Category Total Acc Invoiced Amount'], $account->get('Account Currency')),
                'dispatched_total'    => number($data['Part Category Total Acc Dispatched'], 0),
                'customer_total'      => number($data['Part Category Total Acc Customers'], 0),
                'percentage_no_stock' => percentage($data['percentage_no_stock'], 1),


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
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

/*
function categories($_data, $db, $user,$account) {

    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $currency   = $account->get('Account Currency');


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {
//'NotInUse','InUse','InProcess','Discontinuing'
            switch ($data['Part Category Status']) {
                case 'InProcess':
                    $status = _('In process');
                    break;
                case 'InUse':
                    $status = _('Active');
                    break;

                case 'NotInUse':
                    $status = _('Discontinued');
                    break;
                case 'Discontinuing':
                    $status = _('Discontinuing');
                    break;
                default:
                    $status = $data['Part Category Status'];
                    break;
            }


            $record_data[] = array(
                'id'                  => (integer)$data['Category Key'],
                'code'                => sprintf('<span class="link" onclick="change_view(\'inventory/category/%d\')">%s</span>',$data['Category Key'],$data['Category Code']),
                'label'               => $data['Category Label'],
                'status'           => $status,
                'parts'         => number($data['parts']),
                'in_process'       => number($data['Part Category In Process Products']),
                'active'           => number($data['Part Category Active Products']),
                'suspended'        => number($data['Part Category Suspended Products']),
                'discontinuing'    => number($data['Part Category Discontinuing Products']),
                'discontinued'     => number($data['Part Category Discontinued Products']),
                'sales'            => money($data['sales'], $data['Part Category Currency Code']),
                'sales_1yb'        => delta($data['sales'], $data['sales_1yb']),
                'dispatched'     => number($data['dispatched']),
                'dispatched_1yb' => delta($data['dispatched'], $data['dispatched_1yb']),


                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money($data['Part Category Year To Day Acc Invoiced Amount'], $data['Part Category Currency Code']),
                    delta_icon($data["Part Category Year To Day Acc Invoiced Amount"], $data["Part Category Year To Day Acc 1YB Invoiced Amount"])
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money($data['Part Category 1 Year Ago Invoiced Amount'], $data['Part Category Currency Code']), delta_icon($data["Part Category 1 Year Ago Invoiced Amount"], $data["Part Category 2 Year Ago Invoiced Amount"])
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money($data['Part Category 2 Year Ago Invoiced Amount'], $data['Part Category Currency Code']), delta_icon($data["Part Category 2 Year Ago Invoiced Amount"], $data["Part Category 3 Year Ago Invoiced Amount"])
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 3 Year Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 3 Year Ago Invoiced Amount"], $data["Part Category 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 4 Year Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 4 Year Ago Invoiced Amount"], $data["Part Category 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category Quarter To Day Acc Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category Quarter To Day Acc Invoiced Amount"], $data["Part Category Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 1 Quarter Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 1 Quarter Ago Invoiced Amount"], $data["Part Category 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 2 Quarter Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 2 Quarter Ago Invoiced Amount"], $data["Part Category 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 3 Quarter Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 3 Quarter Ago Invoiced Amount"], $data["Part Category 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Category 4 Quarter Ago Invoiced Amount'], $data['Part Category Currency Code']
                ), delta_icon(
                        $data["Part Category 4 Quarter Ago Invoiced Amount"], $data["Part Category 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
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
*/

function category_all_available_parts($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['associated']) {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']
                );
            } else {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']
                );
            }


            $record_data[] = array(
                'id'          => (integer)$data['Part SKU'],
                'associated'  => $associated,
                'reference'   => $data['Part Reference'],
                'description' => $data['Part Package Description'],
                'family'      => $data['Category Code']
            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
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


function category_all_parts($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Part Status']) {
                case 'In Use':
                    $status = sprintf('<span >%s</span>', _('Active'));
                    break;
                case 'Not in Use':
                    $status = sprintf(
                        '<span class="warning" ></span>', _('Discontinued')
                    );

                    break;

                default:
                    $status = $data['Part Status'];
                    break;
            }

            $record_data[] = array(
                'id'          => (integer)$data['Part SKU'],
                'reference'   => $data['Part Reference'],
                'description' => $data['Part Package Description'],
                'family'      => ($data['Category Code'] == '' ? '<span class="very_discreet italic">'._('Not associated').'</span>' : '<span class="link" onClick="change_view(\'category/'.$data['Category Key'].'\')">'.$data['Category Code'].'</span>'),
                'status'      => $status
            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
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


function product_families($_data, $db, $user) {


    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    //print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            //print_r($data);

            if ($data['category_data'] == '') {

                $label = sprintf(_('New family for %s'), $data['Store Code']);


                $family          = '<span class="super_discreet">'._('Family not set').'</span>';
                $number_products = '<span class="super_discreet">-</span>';
                $operations      =
                    (in_array($data['Store Key'], $user->stores) ? '<i class="fa fa-plus button" aria-hidden="true" title="'._('Create family').'" onClick="open_new_product_family(this,'.$data['Store Key'].',\''.$label.'\')" ></i>' : '<i class="fa fa-lock "></i>');
                $code            = sprintf('<span >%s</span>', $data['Store Code']).($data['Store Type'] == 'B2BC' ? ' <i class="fa fa-dropbox" aria-hidden="true" title="'._("Carton's store").'"  ></i>' : '');

            } else {
                $family_data = preg_split('/,/', $data['category_data']);

                $label           = sprintf(_('Adding missing products in %s'), $data['Store Code']);
                $family          = sprintf('<span class="button" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Store Key'], $family_data[0], $family_data[1]);
                $number_products = number($data['number_products']);
                $operations      = (in_array($data['Store Key'], $user->stores) ? '<i class="fa fa-sync button" aria-hidden="true" title="'._('Add new parts to family').'" onClick="open_new_product_family(this,'.$data['Store Key'].',\''.$label.'\')" ></i>'
                    : '<i class="fa fa-lock "></i>');
                $code            =
                    sprintf('<span class="button" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Store Key'], $family_data[0], $data['Store Code']).($data['Store Type'] == 'B2BC' ? '<i class="fa fa-dropbox" title="'._("Carton's store")
                        .'"  aria-hidden="true"></i>' : '');
            }


            $record_data[] = array(
                'operations' => $operations,

                'id'              => (integer)$data['Store Key'],
                'code'            => $code,
                'name'            => $data['Store Name'],
                'family'          => $family,
                'number_products' => $number_products
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


function sales_history($_data, $db, $user, $account) {

    $skip_get_table_totals = true;

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
        case 'part':
            include_once 'class.Part.php';
            $part       = new Part($_data['parameters']['parent_key']);
            $currency   = $account->get('Account Currency');
            $from       = $part->get('Part Valid From');
            $to         = ($part->get('Part Status') == 'Not In Use' ? $part->get('Part Valid To') : gmdate('Y-m-d'));
            $date_field = '`Date`';
            break;
        case 'category':
            include_once 'class.Category.php';
            $category   = new Category($_data['parameters']['parent_key']);
            $currency   = $account->get('Account Currency');
            $from       = $category->get('Part Category Valid From');
            $to         = ($category->get('Part Category Status') == 'NotInUse' ? $category->get('Part Category Valid To') : gmdate('Y-m-d'));
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

    //print $sql_totals;

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
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00'));
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
                $_date = date('Y-m-d', strtotime($data['Date'].' +0:00'));
            }

            $record_data[$_date] = array(
                'sales'      => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'skos'       => '<span class="very_discreet">'.number(0).'</span>',
                'deliveries' => '<span class="very_discreet">'.number(0).'</span>',
                'date'       => $date


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    switch ($_data['parameters']['parent']) {
        case 'part':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate("Y-01-01 00:00:00", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31 23:59:59", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $from_date = gmdate("Y-m-01 00:00:00", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-m-01 00:00:00", strtotime($to_date.' + 3 month +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01 00:00:00", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-m-01 00:00:00", strtotime($to_date.' + 1 month +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate("Y-m-d 00:00:00", strtotime($from_date.'  -1 week  +0:00'));
                $to_date   = gmdate("Y-m-d 00:00:00", strtotime($to_date.' + 1 week +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.' 00:00:00';
                $to_date   = $to_date.' 23:59:59';
            }
            break;
        case 'category':
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

            break;
        default:
            print_r($_data);
            exit('parent not configured '.$_data['parameters']['parent']);
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s order by $date_field    ",

        $date_field, prepare_mysql($from_date), $date_field, prepare_mysql($to_date), $group_by
    );


    // print $sql;

    $last_year_data = array();

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


            $last_year_data[$_date] = array('_sales' => $data['sales']);


            if (array_key_exists($_date, $record_data)) {


                $record_data[$_date] = array(

                    'sales'      => money($data['sales'], $currency),
                    'skos'       => number($data['skos']),
                    'deliveries' => number($data['deliveries']),
                    'date'       => $record_data[$_date]['date']


                );

                if (isset($last_year_data[$_date_last_year])) {
                    $record_data[$_date]['delta_sales_1yb'] = '<span title="'.money($last_year_data[$_date_last_year]['_sales'], $currency).'">'.delta($data['sales'], $last_year_data[$_date_last_year]['_sales']).' '.delta_icon(
                            $data['sales'], $last_year_data[$_date_last_year]['_sales']
                        ).'</span>';
                }
            }
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


function parts_no_sko_barcode($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $record_data[] = array(
                'id'          => (integer)$data['Part SKU'],
                'reference'   => $data['Part Reference'],
                'description' => $data['Part Package Description'],
                'barcode'     => sprintf(
                    '<input class="sko_barcode" style="width:200px" part_sku="%d"> <i class="fa save_sko_barcode fa-cloud very_discreet" aria-hidden="true"></i> <span class="sko_barcode_msg error" ></span>', $data['Part SKU']
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function parts_barcode_errors($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['Part Status'] == 'In Use') {
                $status = _('Active');
            } elseif ($data['Part Status'] == 'Discontinuing') {
                $status = _('Discontinuing');

            } elseif ($data['Part Status'] == 'Not In Use') {
                $status = _('Discontinued');
            } elseif ($data['Part Status'] == 'In Process') {
                $status = _('In process');


            } else {
                $status = $data['Part Status'];
            }


            switch ($data['Part Barcode Number Error']) {
                case 'Duplicated':
                    $error = '<span class="barcode_number_error error">'._('Duplicated').'</span>';
                    break;
                case 'Size':
                    $error = '<span class="barcode_number_error error">'._('Barcode should be 13 digits').'</span>';
                    break;
                case 'Short_Duplicated':
                    $error = '<span class="barcode_number_error error">'._('Check digit missing, will duplicate').'</span>';
                    break;
                case 'Checksum_missing':
                    $error = '<span class="barcode_number_error error">'._('Check digit missing').'</span>';
                    break;
                case 'Checksum':
                    $error = '<span class="barcode_number_error error">'._('Invalid check digit').'</span>';
                    break;
                default:
                    $error = '<span class="barcode_number_error error">'.$data['Part Barcode Number Error'].'</span>';
            }

            $record_data[] = array(
                'id'          => (integer)$data['Part SKU'],
                'reference'   => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
                'error'       => $error,
                'status'      => $status,
                'description' => $data['Part Package Description'],
                'barcode'     => sprintf(
                    '<input class="barcode_number" style="width:200px" value="%s" part_sku="%d" > <i class="fa save_barcode_number fa-cloud very_discreet" aria-hidden="true"></i> <span class="barcode_number_msg error" ></span>', $data['Part Barcode Number'],
                    $data['Part SKU']
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function stock_cost($_data, $db, $user, $account) {


    $rtext_label = 'transaction';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $change = $data['Inventory Transaction Quantity'];


            if ($data['Inventory Transaction Amount'] == 0) {
                $cost_per_sko = '-';
            } else {
                if ($data['Inventory Transaction Quantity'] != 0) {
                    $cost_per_sko = ''.money($data['Inventory Transaction Amount'] / $data['Inventory Transaction Quantity'], $account->get('Account Currency Code'));

                } else {
                    $cost_per_sko = '?';
                }
            }


            /*
                        if ($data['Supplier Delivery Parent'] != '') {
                            $note = sprintf(
                                '<span class="link" onclick="change_view(\'%s/%d/delivery/%d\')" >%s</span>  ', strtolower($data['Supplier Delivery Parent']), $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
                            );
                        } else {
                            $note = _('Stock audit').' '.$data['Note'];
                        }
            */
            $note = $data['Note'];


            if ($data['costing_done']) {
                $sko_cost = sprintf(
                    '<span  class="part_cost_per_sko "  >%s</span>', $cost_per_sko
                );
                $cost     = sprintf(
                    '<span  class="part_cost button"  data-itf_key="%d" data-cost="%s"  data-skos="%s"  data-currency_symbol="%s"  data-cost_per_sko="%s" onClick="open_edit_cost(this)">%s</span>', $data['Inventory Transaction Key'],
                    $data['Inventory Transaction Amount'], $data['Inventory Transaction Quantity'], $account->get('Account Currency Symbol'), $cost_per_sko, money($data['Inventory Transaction Amount'], $account->get('Account Currency Code'))
                );
            } else {
                $sko_cost = sprintf(
                    '<i class="error fa fa-fw fa-exclamation-circle"></i> <span  class="part_cost_per_sko error italic"  >%s</span>  ', $cost_per_sko
                );
                $cost     = '<i class="error fa fa-question-circle "></i>';
            }

            //   ($data['costing_done']?'<i class="success fa fa-fw fa-tick"></i>':'<i class="error fa fa-fw fa-exclamation-circle"></i>');


            $record_data[] = array(
                'id'       => (integer)$data['Inventory Transaction Key'],
                'date'     => strftime("%a %e %b %Y %R", strtotime($data['Date'].' +0:00')),
                'delivery' => $note,
                'skos'     => $change,
                'cost'     => $cost,
                'sko_cost' => $sko_cost,

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

function part_family_part_locations($_data, $db, $user, $account) {


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


function part_locations($_data, $db, $user, $account) {


    if ($_data['parameters']['tab'] == 'warehouse.parts') {
        $rtext_label = 'part location';
    } else {
        $rtext_label = 'part';

    }


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {


        $notes = sprintf(
            '<span class="button" key="%s" onclick="open_part_location_notes(this)" id="pl_notes_%d" >%s<span class="note">%s</span></span>', $data['Part SKU'].'_'.$data['Location Key'], $data['Part SKU'].'_'.$data['Location Key'],
            '<i class="far fa-sticky-note very_discreet '.($data['Part Location Note'] != '' ? 'hide' : '').'" aria-hidden="true"></i> ', ($data['Part Location Note'])
        );


        $adata[] = array(


            'reference' => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span class="link" onCLick="change_view(\'locations/%d/%d\')" >%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'sko_description' => $data['Part Package Description'],


            'can_pick' => ($data['Can Pick'] == 'Yes' ? '<i class="fa fa-shopping-basket"></i>' : ''),

            'link' => '<span id="link_'.$data['Part SKU'].'"><i class="fa fa-unlink '.($data['Quantity On Hand'] != 0 ? 'invisible' : 'button').'" aria-hidden="true" part_sku="'.$data['Part SKU'].'" onclick="location_part_disassociate_from_table(this)"></i>',

            'last_audit' => ($data['Part Location Last Audit'] == '' ? '<span class="very_discreet italic ">'._('No audited yet').'</span>' : strftime("%a %e %b %Y", strtotime($data['Part Location Last Audit'].' +0:00'))),

            'sko_cost'    => money($data['Part Cost in Warehouse'], $account->get('Account Currency')),
            'stock_value' => '<span id="stock_value_'.$data['Part SKU'].'">'.money($data['Stock Value'], $account->get('Account Currency')).'</span>',
            'quantity'    => sprintf(
                '<span id="quantity_'.$data['Part SKU']
                .'"><span style="padding-left:3px;padding-right:7.5px" class="table_edit_cell  location_part_stock" title="%s" part_sku="%d" location_key="%d"  qty="%s" onClick="open_location_part_stock_quantity_dialog(this)">%s</span></span>', '', $data['Part SKU'],
                $data['Location Key'], $data['Quantity On Hand'], number($data['Quantity On Hand'])
            ),
            'notes'       => $notes


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


function parts_discontinuing($_data, $db, $user, $account) {


    if (!$user->can_view('parts')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $db->exec('SET SESSION group_concat_max_len = 1000000;');

    $rtext_label = 'discontinuing part';


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if ($data['Part Current On Hand Stock'] <= 0) {
                $weeks_available = '-';
            } else {
                $weeks_available = number(
                    $data['Part Days Available Forecast'] / 7, 0
                );
            }


            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );


            $cost = money($data['Part Cost'], $account->get('Account Currency'));

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';


            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $account->get('Account Currency'));


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


            if ($data['Part Current On Hand Stock'] < 0) {
                $stock_weight = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == '') {
                $stock_weight = '<span class=" error italic">'._('Unknown').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == 0) {
                $stock_weight = '<span class=" warning italic">'._('SKO weight is 0').'</span> <i class="warning fa fa-fw fa-exclamation-triangle"></i>';


            } else {
                $stock_weight = weight($data['Part Package Weight'] * $data['Part Current On Hand Stock'], ' Kg', 0);


            }


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            $stock_status = sprintf('<span class="part_status_%d"><i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-skull" title="%s"></i></span>', $data['Part SKU'], $data['Part SKU'], _('Discontinuing, click to set as an active part'));


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


            $products        = '';
            $number_products = 0;


            //   print $data['products_data']."\n";

            if ($data['products_data'] != '') {
                $products_data = preg_split('/,/', $data['products_data']);


                $products = '<div  class=" mini_table no_padding no_top_border left "  >';


                foreach ($products_data as $product_data) {

                    $product_data = preg_split('/\:/', $product_data);

                    // print_r($product_data);

                    if ($product_data[8] == 'Active' or $product_data[8] == 'Discontinuing') {


                        $number_products++;

                        //'For Sale','Out of Stock','Discontinued','Offline'
                        switch ($product_data[5]) {
                            case 'Offline':
                                $web_state = sprintf('<i class="fa-fw far fa-globe super_discreet" title="%s"></i>', _('Offline'));
                                break;
                            case 'Discontinued':
                                $web_state = sprintf('<i class="fa-fw far fa-globe very_discreet" title="%s"></i>', _('Show as discontinued'));
                                break;
                            case 'Out of Stock':
                                $web_state = sprintf('<i class="fa-fw far fa-globe error discreet" title="%s"></i>', _('Show as out of stock'));
                                break;
                            case 'For Sale':
                                $web_state = sprintf('<i class="fa-fw far fa-globe success" title="%s"></i>', _('Online'));
                                break;
                            default:
                                $web_state = $product_data[5];
                        }


                        $products .= ' <div style="clear:both;"    >
				<div  class="store_code data w30"  >'.$product_data[1].'</div>
				<div  class="code data w150 link"   onclick="change_view(\'part/'.$data['Part SKU'].'/product/'.$product_data[3].'\')"  >'.$product_data[4].'</div>
				<div  class="web_state data w30"  >'.$web_state.'</div>

				<div class="data w30 aright" >'.money($product_data[6], $product_data[2]).'</div>
				</div>';
                    }
                }
                $products .= '<div style="clear:both"></div></div>';
            }


            if ($number_products == 0) {
                $products = '<i class="fa error fa-exclamation-circle"></i>  <span class="error italic">'._('No active products').'</span>';
            }


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
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


            $record_data[] = array(
                'id' => (integer)$data['Part SKU'],

                'products'        => $products,
                'reference'       => $reference,
                'sko_description' => $data['Part Package Description'],
                'stock_status'    => $stock_status,
                'stock_value'     => $stock_value,
                'stock_weight'    => $stock_weight,
                'products'        => $products,

                'available_forecast' => $available_forecast,

                'stock' => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',

                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => delta(
                    $data['dispatched'], $data['dispatched_1yb']
                ),
                'sales'          => money(
                    $data['sales'], $account->get('Account Currency')
                ),
                'sales_1yb'      => delta($data['sales'], $data['sales_1yb']),

                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Year To Day Acc Invoiced Amount"], $data["Part Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Year Ago Invoiced Amount"], $data["Part 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Year Ago Invoiced Amount"], $data["Part 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Year Ago Invoiced Amount"], $data["Part 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Year Ago Invoiced Amount"], $data["Part 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Quarter To Day Acc Invoiced Amount"], $data["Part Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Quarter Ago Invoiced Amount"], $data["Part 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Quarter Ago Invoiced Amount"], $data["Part 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Quarter Ago Invoiced Amount"], $data["Part 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Quarter Ago Invoiced Amount"], $data["Part 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


                'dispatched_year0' => sprintf(
                    '<span>%s</span> %s', number($data['Part Year To Day Acc Dispatched']), delta_icon(
                                            $data["Part Year To Day Acc Dispatched"], $data["Part Year To Day Acc 1YB Dispatched"]
                                        )
                ),
                'dispatched_year1' => sprintf(
                    '<span>%s</span> %s', number($data['Part 1 Year Ago Dispatched']), delta_icon(
                                            $data["Part 1 Year Ago Dispatched"], $data["Part 2 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year2' => sprintf(
                    '<span>%s</span> %s', number($data['Part 2 Year Ago Dispatched']), delta_icon(
                                            $data["Part 2 Year Ago Dispatched"], $data["Part 3 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year3' => sprintf(
                    '<span>%s</span> %s', number($data['Part 3 Year Ago Dispatched']), delta_icon(
                                            $data["Part 3 Year Ago Dispatched"], $data["Part 4 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year4' => sprintf(
                    '<span>%s</span> %s', number($data['Part 4 Year Ago Dispatched']), delta_icon(
                                            $data["Part 4 Year Ago Dispatched"], $data["Part 5 Year Ago Dispatched"]
                                        )
                ),

                'dispatched_quarter0' => sprintf(
                    '<span>%s</span> %s', number($data['Part Quarter To Day Acc Dispatched']), delta_icon(
                                            $data["Part Quarter To Day Acc Dispatched"], $data["Part Quarter To Day Acc 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter1' => sprintf(
                    '<span>%s</span> %s', number($data['Part 1 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 1 Quarter Ago Dispatched"], $data["Part 1 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter2' => sprintf(
                    '<span>%s</span> %s', number($data['Part 2 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 2 Quarter Ago Dispatched"], $data["Part 2 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter3' => sprintf(
                    '<span>%s</span> %s', number($data['Part 3 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 3 Quarter Ago Dispatched"], $data["Part 3 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter4' => sprintf(
                    '<span>%s</span> %s', number($data['Part 4 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 4 Quarter Ago Dispatched"], $data["Part 4 Quarter Ago 1YB Dispatched"]
                                        )
                ),


                'sales_total'                      => money($data['Part Total Acc Invoiced Amount'], $account->get('Account Currency')),
                'dispatched_total'                 => number($data['Part Total Acc Dispatched'], 0),
                'customer_total'                   => number($data['Part Total Acc Customers'], 0),
                'percentage_repeat_customer_total' => percentage($data['Part Total Acc Repeat Customers'], $data['Part Total Acc Customers']),


                'weeks_available'     => $weeks_available,
                'dispatched_per_week' => $dispatched_per_week,
                'valid_from'          => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'valid_to'            => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'active_from'         => strftime("%a %e %b %Y", strtotime($data['Part Active From'].' +0:00')),

                'cost'            => $cost,
                'sko_stock_value' => $sko_stock_value,
                'margin'          => '<span class="'.($data['Part Margin'] <= 0 ? 'error' : '').'">'.percentage($data['Part Margin'], 1).'</span>',
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function parts_discontinued($_data, $db, $user, $type, $account) {


    if (!$user->can_view('parts')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'discontinued part';


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $cost      = money($data['Part Cost'], $account->get('Account Currency'));
            $reference = sprintf(
                '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
                ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
            );


            $record_data[] = array(
                'id'              => (integer)$data['Part SKU'],
                'reference'       => $reference,
                'sko_description' => $data['Part Package Description'],


                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => delta(
                    $data['dispatched'], $data['dispatched_1yb']
                ),
                'sales'          => money(
                    $data['sales'], $account->get('Account Currency')
                ),
                'sales_1yb'      => delta($data['sales'], $data['sales_1yb']),

                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Year To Day Acc Invoiced Amount"], $data["Part Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Year Ago Invoiced Amount"], $data["Part 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Year Ago Invoiced Amount"], $data["Part 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Year Ago Invoiced Amount"], $data["Part 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Year Ago Invoiced Amount"], $data["Part 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part Quarter To Day Acc Invoiced Amount"], $data["Part Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 1 Quarter Ago Invoiced Amount"], $data["Part 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 2 Quarter Ago Invoiced Amount"], $data["Part 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 3 Quarter Ago Invoiced Amount"], $data["Part 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Part 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Part 4 Quarter Ago Invoiced Amount"], $data["Part 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


                'dispatched_year0' => sprintf(
                    '<span>%s</span> %s', number($data['Part Year To Day Acc Dispatched']), delta_icon(
                                            $data["Part Year To Day Acc Dispatched"], $data["Part Year To Day Acc 1YB Dispatched"]
                                        )
                ),
                'dispatched_year1' => sprintf(
                    '<span>%s</span> %s', number($data['Part 1 Year Ago Dispatched']), delta_icon(
                                            $data["Part 1 Year Ago Dispatched"], $data["Part 2 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year2' => sprintf(
                    '<span>%s</span> %s', number($data['Part 2 Year Ago Dispatched']), delta_icon(
                                            $data["Part 2 Year Ago Dispatched"], $data["Part 3 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year3' => sprintf(
                    '<span>%s</span> %s', number($data['Part 3 Year Ago Dispatched']), delta_icon(
                                            $data["Part 3 Year Ago Dispatched"], $data["Part 4 Year Ago Dispatched"]
                                        )
                ),
                'dispatched_year4' => sprintf(
                    '<span>%s</span> %s', number($data['Part 4 Year Ago Dispatched']), delta_icon(
                                            $data["Part 4 Year Ago Dispatched"], $data["Part 5 Year Ago Dispatched"]
                                        )
                ),

                'dispatched_quarter0' => sprintf(
                    '<span>%s</span> %s', number($data['Part Quarter To Day Acc Dispatched']), delta_icon(
                                            $data["Part Quarter To Day Acc Dispatched"], $data["Part Quarter To Day Acc 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter1' => sprintf(
                    '<span>%s</span> %s', number($data['Part 1 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 1 Quarter Ago Dispatched"], $data["Part 1 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter2' => sprintf(
                    '<span>%s</span> %s', number($data['Part 2 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 2 Quarter Ago Dispatched"], $data["Part 2 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter3' => sprintf(
                    '<span>%s</span> %s', number($data['Part 3 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 3 Quarter Ago Dispatched"], $data["Part 3 Quarter Ago 1YB Dispatched"]
                                        )
                ),
                'dispatched_quarter4' => sprintf(
                    '<span>%s</span> %s', number($data['Part 4 Quarter Ago Dispatched']), delta_icon(
                                            $data["Part 4 Quarter Ago Dispatched"], $data["Part 4 Quarter Ago 1YB Dispatched"]
                                        )
                ),


                'sales_total'                      => money($data['Part Total Acc Invoiced Amount'], $account->get('Account Currency')),
                'dispatched_total'                 => number($data['Part Total Acc Dispatched'], 0),
                'customer_total'                   => number($data['Part Total Acc Customers'], 0),
                'percentage_repeat_customer_total' => percentage($data['Part Total Acc Repeat Customers'], $data['Part Total Acc Customers']),


                'valid_from'  => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'valid_to'    => strftime("%a %e %b %Y", strtotime($data['Part Valid To'].' +0:00')),
                'active_from' => strftime("%a %e %b %Y", strtotime($data['Part Active From'].' +0:00')),

                'cost'   => $cost,
                'margin' => '<span class="'.($data['Part Margin'] <= 0 ? 'error' : '').'">'.percentage($data['Part Margin'], 1).'</span>',
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


function parts_weight_errors($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


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


            switch ($data['Part Package Weight Status']) {
                case 'Missing':
                    $status = '<span class="sko_weight_msg "><span class="error">'._('Missing weight').'</span></span>';
                    break;
                case 'Underweight Web':
                    $status = '<span class="sko_weight_msg "><span class="error">'.sprintf(
                            _('Probably underweight <b>or</b> %s high'), '<span title="'._('Unit weight shown on website').'"><i class=" fal fa-weight-hanging"></i><i style="font-size: x-small" class="  fal fa-globe"></i></span>'
                        ).'</span></span>';
                    break;
                case 'Overweight Web':
                    $status = '<span class="sko_weight_msg "><span class="error">'.sprintf(
                            _('Probably overweight <b>or</b> %s low'), '<span title="'._('Unit weight shown on website').'"><i class=" fal fa-weight-hanging"></i><i style="font-size: x-small" class="  fal fa-globe"></i></span>'
                        ).'</span></span>';

                    break;
                case 'Underweight Cost':
                    $status = '<span class="sko_weight_msg "><span class="error">'._('Probably underweight').' <i class="margin_left_5 fal fa-box-usd"></i></span></span>';
                    break;
                case 'Overweight Cost':
                    $status = '<span class="sko_weight_msg "><span class="error">'._('Probably overweight').' <i class="margin_left_5 fal fa-box-usd"></i></span></span>';
                    break;
                case 'OK':
                    $status = '<span class="sko_weight_msg error"></span>';
                    break;
                default:
                    $status = '<span class="sko_weight_msg error">'.$data['Part Package Weight Status'].'</span>';
            }


            $record_data[] = array(
                'id'          => (integer)$data['Part SKU'],
                'reference'   => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
                'status'      => $status,
                'image'       => $image,
                'description' => $data['Part Package Description'],
                'weight'      => sprintf(
                    '<input class="sko_weight" style="width:100px" value="%s" part_sku="%d" > <i class="fa save_sko_weight fa-cloud very_discreet" aria-hidden="true"></i> <span class="weight_msg error" ></span>', $data['Part Package Weight'], $data['Part SKU']
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
            'data'          => $record_data,
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


function feedback_per_part($_data, $db, $user, $account) {


    $rtext_label = 'issue';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";

    //print $sql;
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $record_data[] = array(
                'id'              => (integer)$data['Part SKU'],
                'reference'       => sprintf('<span class="link" onClick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
                'date'            => strftime("%a %e %b %Y", strtotime($data['date']." +00:00")),
                'number_feedback' => number($data['number_feedback']),
                'amount'          => money($data['amount'], $account->get('Account Currency'))

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


function feedback_per_part_family($_data, $db, $user, $account) {


    $rtext_label = 'issue';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";

    //print $sql;
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $record_data[] = array(
                'id'              => (integer)$data['Category Key'],
                'code'            => sprintf('<span class="link" onClick="change_view(\'category/%d\')">%s</span>', $data['Category Key'], $data['Category Code']),
                'date'            => strftime("%a %e %b %Y", strtotime($data['date']." +00:00")),
                'number_feedback' => number($data['number_feedback']),
                'amount'          => money($data['amount'], $account->get('Account Currency'))

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


function parts_no_products($_data, $db, $user, $account) {


    if (!$user->can_view('parts')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $db->exec('SET SESSION group_concat_max_len = 1000000;');

    $rtext_label = 'part';


    include_once 'prepare_table/init.php';

    if ($order == 'P.`Part SKU`') {
        $sql = "select $fields from $table $where $wheref  limit $start_from,$number_results";

    } else {
        $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    }

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $cost = money($data['Part Cost'], $account->get('Account Currency'));

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';


            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $account->get('Account Currency'));


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


            if ($data['Part Current On Hand Stock'] < 0) {
                $stock_weight = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == '') {
                $stock_weight = '<span class=" error italic">'._('Unknown').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == 0) {
                $stock_weight = '<span class=" warning italic">'._('SKO weight is 0').'</span> <i class="warning fa fa-fw fa-exclamation-triangle"></i>';


            } else {
                $stock_weight = weight($data['Part Package Weight'] * $data['Part Current On Hand Stock'], ' Kg', 0);


            }


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            $stock_status = sprintf('<span class="part_status_%d"><i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-skull" title="%s"></i></span>', $data['Part SKU'], $data['Part SKU'], _('Discontinuing, click to set as an active part'));


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
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


            $record_data[] = array(
                'id' => (integer)$data['Part SKU'],

                'reference'       => $reference,
                'sko_description' => $data['Part Package Description'],
                'stock_status'    => $stock_status,
                'stock_value'     => $stock_value,
                'stock_weight'    => $stock_weight,


                'stock' => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',

                'valid_from'  => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'valid_to'    => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'active_from' => strftime("%a %e %b %Y", strtotime($data['Part Active From'].' +0:00')),

                'cost'            => $cost,
                'sko_stock_value' => $sko_stock_value,
                'next_deliveries' => $next_deliveries
            );


        }
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

function parts_forced_not_for_sale_on_website($_data, $db, $user, $account) {


    if (!$user->can_view('parts')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }

    $db->exec('SET SESSION group_concat_max_len = 1000000;');


    $rtext_label = 'part';


    include_once 'prepare_table/init.php';

    if ($order == 'P.`Part SKU`') {
        $sql = "select $fields from $table $where $wheref  limit $start_from,$number_results";

    } else {
        $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    }


    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $cost = money($data['Part Cost'], $account->get('Account Currency'));

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';


            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $account->get('Account Currency'));


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


            if ($data['Part Current On Hand Stock'] < 0) {
                $stock_weight = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == '') {
                $stock_weight = '<span class=" error italic">'._('Unknown').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


            } elseif ($data['Part Package Weight'] == 0) {
                $stock_weight = '<span class=" warning italic">'._('SKO weight is 0').'</span> <i class="warning fa fa-fw fa-exclamation-triangle"></i>';


            } else {
                $stock_weight = weight($data['Part Package Weight'] * $data['Part Current On Hand Stock'], ' Kg', 0);


            }


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            $stock_status = sprintf('<span class="part_status_%d"><i onclick="set_discontinuing_part_as_active(this,%d)" class="far button fa-fw fa-skull" title="%s"></i></span>', $data['Part SKU'], $data['Part SKU'], _('Discontinuing, click to set as an active part'));


            $products        = '';
            $number_products = 0;


            //   print $data['products_data']."\n";

            if ($data['products_data'] != '') {
                $products_data = preg_split('/,/', $data['products_data']);


                $products = '<div  class=" mini_table no_padding no_top_border left "  >';


                foreach ($products_data as $product_data) {

                    $product_data = preg_split('/\:/', $product_data);

                    // print_r($product_data);

                    if ($product_data[8] == 'Active' or $product_data[8] == 'Discontinuing') {


                        $number_products++;

                        //'For Sale','Out of Stock','Discontinued','Offline'
                        switch ($product_data[5]) {
                            case 'Offline':
                                $web_state = sprintf('<i class="fa-fw far fa-globe super_discreet" title="%s"></i>', _('Offline'));
                                break;
                            case 'Discontinued':
                                $web_state = sprintf('<i class="fa-fw far fa-globe very_discreet" title="%s"></i>', _('Show as discontinued'));
                                break;
                            case 'Out of Stock':
                                $web_state = sprintf('<i class="fa-fw far fa-globe error discreet" title="%s"></i>', _('Show as out of stock'));
                                break;
                            case 'For Sale':
                                $web_state = sprintf('<i class="fa-fw far fa-globe success" title="%s"></i>', _('Online'));
                                break;
                            default:
                                $web_state = $product_data[5];
                        }


                        $products .= ' <div style="clear:both;"    >
				<div  class="store_code data w30"  >'.$product_data[1].'</div>
				<div  class="code data w150 link"   onclick="change_view(\'part/'.$data['Part SKU'].'/product/'.$product_data[3].'\')"  >'.$product_data[4].'</div>
				<div  class="web_state data w30"  >'.$web_state.'</div>

				<div class="data w30 aright" >'.money($product_data[6], $product_data[2]).'</div>
				</div>';
                    }
                }
                $products .= '<div style="clear:both"></div></div>';
            }


            if ($number_products == 0) {
                $products = '<i class="fa error fa-exclamation-circle"></i>  <span class="error italic">'._('No active products').'</span>';
            }


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
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


            $record_data[] = array(
                'id' => (integer)$data['Part SKU'],

                'products'        => $products,
                'reference'       => $reference,
                'sko_description' => $data['Part Package Description'],
                'stock_status'    => $stock_status,
                'stock_value'     => $stock_value,
                'stock_weight'    => $stock_weight,
                'products'        => $products,


                'stock' => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',


                'valid_from'  => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'valid_to'    => strftime("%a %e %b %Y", strtotime($data['Part Valid From'].' +0:00')),
                'active_from' => strftime("%a %e %b %Y", strtotime($data['Part Active From'].' +0:00')),

                'cost'            => $cost,
                'sko_stock_value' => $sko_stock_value,
                // 'margin'          => '<span class="'.($data['Part Margin'] <= 0 ? 'error' : '').'">'.percentage($data['Part Margin'], 1).'</span>',
                'next_deliveries' => $next_deliveries
            );


        }
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
