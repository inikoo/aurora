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

    case 'parts':
        parts(get_table_parameters(), $db, $user, '', $account);
        break;
    case 'active_parts':
        parts(get_table_parameters(), $db, $user, 'active', $account);
        break;
    case 'discontinued_parts':
        parts(get_table_parameters(), $db, $user, 'discontinued', $account);
        break;
    case 'discontinuing_parts':
        parts(get_table_parameters(), $db, $user, 'discontinuing', $account);
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
    case 'part_categories':
        part_categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'categories':
        categories(get_table_parameters(), $db, $user);
        break;
    case 'product_families':
        product_families(get_table_parameters(), $db, $user);
        break;
    case 'category_all_availeable_parts':
        category_all_availeable_parts(get_table_parameters(), $db, $user);
        break;
    case 'category_all_parts':
        category_all_parts(get_table_parameters(), $db, $user);
        break;
    case 'sales_history':

        sales_history(get_table_parameters(), $db, $user, $account);
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

    $adata = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status       = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Surplus');
                    break;
                case 'Optimal':
                    $stock_status       = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Ok');
                    break;
                case 'Low':
                    $stock_status       = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Low');
                    break;
                case 'Critical':
                    $stock_status       = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Critical');
                    break;
                case 'Out_Of_Stock':
                    $stock_status       = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
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


            if ($data['Part Current Stock'] <= 0) {
                $weeks_available = '-';
            } else {
                $weeks_available = number(
                    $data['Part Days Available Forecast'] / 7, 0
                );
            }


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

            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']
            );


            $adata[] = array(
                'id'                 => (integer)$data['Part SKU'],
                'associated'         => $associated,
                'reference'          => $data['Part Reference'],
                'sko_description'    => $data['Part Package Description'],
                'status'             => $status,
                'stock_status'       => $stock_status,
                'stock_status_label' => $stock_status_label,
                'stock'              => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',

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


                'sales_total'                      => money(
                    $data['Part Total Acc Invoiced Amount'], $account->get('Account Currency')
                ),
                'dispatched_total'                 => number(
                    $data['Part Total Acc Dispatched'], 0
                ),
                'customer_total'                   => number(
                    $data['Part Total Acc Customers'], 0
                ),
                'percentage_repeat_customer_total' => percentage(
                    $data['Part Total Acc Repeat Customers'], $data['Part Total Acc Customers']
                ),


                'weeks_available'     => $weeks_available,
                'dispatched_per_week' => $dispatched_per_week,
                'valid_from'          => strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Part Valid From'].' +0:00')
                ),
                'valid_to'            => strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Part Valid From'].' +0:00')
                ),
                'active_from'         => strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Part Active From'].' +0:00')
                ),
                'has_stock'           => ($data['Part Current On Hand Stock'] > 0 ? '<i class="fa fa-check success" aria-hidden="true"></i>'
                    : '<i class="fa fa-minus super_discreet" aria-hidden="true"></i>'),
                'has_picture'         => ($data['Part Main Image Key'] > 0 ? '<i class="fa fa-check success" aria-hidden="true"></i>' : '<i class="fa fa-minus super_discreet" aria-hidden="true"></i>')
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


function stock_history($_data, $db, $user, $account) {


    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label = 'year';
        $date_format="%Y";
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label = 'month';
        $date_format="%b %Y";

    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label = 'week';
        $date_format="(%e %b) %Y %W";

    } else {
        $rtext_label = 'day';
        $date_format="%a %e %b %Y";

    }

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $adata[] = array(
               // 'date'       => strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00')),
               // 'year'       => strftime("%Y", strtotime($data['Date'].' +0:00')),
               // 'month_year' => strftime("%b %Y", strtotime($data['Date'].' +0:00')),
                //'week_year'  => strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')),

                'date'       => strftime($date_format, strtotime($data['Date'].' +0:00')),
                'stock'      => number($data['Quantity On Hand']),
                'value'      => money($data['Value At Day Cost'], $account->get('Currency')),
                'in'         => number($data['Quantity In']),
                'sold'       => number($data['Quantity Sold']),
                'lost'       => number($data['Quantity Lost']),

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


function inventory_stock_history($_data, $db, $user, $account) {



    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label = 'year';
        $date_format="%Y";
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label = 'month';
        $date_format="%b %Y";

    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label = 'week';
        $date_format="(%e %b) %Y %W";

    } else {
        $rtext_label = 'day';
        $date_format="%a %e %b %Y";

    }

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {



            $date=strftime($date_format, strtotime($data['Date'].' +0:00'));

            if ($_data['parameters']['frequency'] == 'daily') {

                $date=sprintf('<span class="link" onclick="change_view(\'inventory/stock_history/day/%s\')" >%s</span>',
                              $data['Date'],
                              $date
                              );
            }

            $adata[] = array(
                //'date'       => $data['Date'],


                // 'day'        => strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00')),
               // 'year'       => strftime("%Y", strtotime($data['Date'].' +0:00')),
               // 'month_year' => strftime("%b %Y", strtotime($data['Date'].' +0:00')),
               // 'week_year'  => strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')),

                'date'       => $date,

                'parts'      => number($data['Parts']),
                'locations'  => number($data['Locations']),

                'value'            => money($data['Value At Day Cost'], $account->get('Currency')),
                'commercial_value' => money($data['Value Commercial'], $account->get('Currency')),
                //'in'=>number($data['Quantity In']),
                //'sold'=>number($data['Quantity Sold']),
                //'lost'=>number($data['Quantity Lost']),

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
                case 'Order In Process':


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

                    $stock = '';

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

                case 'No Dispatched':

                    $stock = '';
                    if ($parameters['parent'] == 'part') {
                        $note = sprintf(
                            _(
                                "%s requested %s <b>couldn't be dispatched</b> (%s)"
                            ),

                            number($data['Required']), '<span title="'._('Stock keeping outers').'">SKO</span>',

                            sprintf(
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
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
                                '<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'],
                                $data['Delivery Note ID']
                            )

                        );
                    }


                    $type = '<i class="fa fa-circle error fa-fw" aria-hidden="true"></i>';
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


function supplier_parts($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';

    if ($user->get('User Type') == 'Agent') {
        // $_data['parameters']['parent']=='supplier' and $_data['parameters']['parent_key']==$user->get('User Parent Key')
        if (!$_data['parameters']['parent'] == 'supplier') {
            echo json_encode(
                array(
                    'state' => 405,
                    'resp'  => 'Forbidden'
                )
            );
            exit;
        } else {
            $sql = sprintf(
                'SELECT count(*) AS num FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=%d AND `Agent Supplier Supplier Key`=%d ', $user->get('User Parent Key'),
                $_data['parameters']['parent_key']
            );

            $ok = 0;
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $ok = $row['num'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
            if ($ok == 0) {
                echo json_encode(
                    array(
                        'state' => 405,
                        'resp'  => 'Forbidden'
                    )
                );
                exit;
            }

        }


    } elseif (!$user->can_view('suppliers')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'supplier part';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $exchange = -1;


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            if ($exchange < 0) {
                $exchange = currency_conversion(
                    $db, $data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day'
                );
            }

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

            if ($data['Part Status'] == 'Not In Use') {
                $part_status = '<i class="fa fa-square-o fa-fw  very_discreet" aria-hidden="true"></i> ';

            } elseif ($data['Part Status'] == 'Discontinuing') {
                $part_status = '<i class="fa fa-square fa-fw  very_discreet" aria-hidden="true"></i> ';

            } else {
                $part_status = '<i class="fa fa-square fa-fw " aria-hidden="true"></i> ';
            }

            $part_description = $part_status.'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'
                .$data['Part Reference'].'</span> ';

            $adata[] = array(
                'id'               => (integer)$data['Supplier Part Key'],
                'supplier_key'     => (integer)$data['Supplier Part Supplier Key'],
                'supplier_code'    => $data['Supplier Code'],
                'part_key'         => (integer)$data['Supplier Part Part SKU'],
                'part_reference'   => $data['Part Reference'],
                'reference'        => $data['Supplier Part Reference'],
                'part_description' => $part_description,


                'description'    => $data['Part Unit Description'],
                'status'         => $status,
                'cost'           => money(
                    $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']
                ),
                'delivered_cost' => '<span title="'.$exchange_info.'">'.money(
                        $exchange * ($data['Supplier Part Unit Cost'] + $data['Supplier Part Unit Extra Cost']), $account->get('Account Currency')
                    ).'</span>',
                'packing'        => '
				 <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package']
                        * $data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:70px;text-align:center;"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['
                        .$data['Supplier Part Packages Per Carton'].']</span></div>
				<div style="float:right;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
                'stock'          => number(floor($data['Part Current Stock']))." $stock_status",


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


function barcodes($_data, $db, $user) {


    $rtext_label = 'barcodes';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $adata = array();

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

            $adata[] = array(
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
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function part_categories($_data, $db, $user, $account) {


    if ($_data['parameters']['parent_key'] == $account->get(
            'Account Part Family Category Key'
        )
    ) {
        $rtext_label = 'family';
    } else {
        $rtext_label = 'category';
    }

    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Category Branch Type']) {
                case 'Root':
                    $level = _('Root');
                    break;
                case 'Head':
                    $level = _('Head');
                    break;
                case 'Node':
                    $level = _('Node');
                    break;
                default:
                    $level = $data['Category Branch Type'];
                    break;
            }
            $level = $data['Category Branch Type'];


            $adata[] = array(
                'id'                  => (integer)$data['Category Key'],
                'store_key'           => (integer)$data['Category Store Key'],
                'code'                => $data['Category Code'],
                'label'               => $data['Category Label'],
                'subjects'            => number(
                    $data['Category Number Subjects']
                ),
                'subjects_active'     => number(
                    $data['Category Number Active Subjects']
                ),
                'subjects_no_active'  => number(
                    $data['Category Number No Active Subjects']
                ),
                'level'               => $level,
                'subcategories'       => number($data['Category Children']),
                'percentage_assigned' => percentage(
                    $data['Category Number Subjects'], ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
                ),

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


                'sales_total'         => money(
                    $data['Part Category Total Acc Invoiced Amount'], $account->get('Account Currency')
                ),
                'dispatched_total'    => number(
                    $data['Part Category Total Acc Dispatched'], 0
                ),
                'customer_total'      => number(
                    $data['Part Category Total Acc Customers'], 0
                ),
                'percentage_no_stock' => percentage(
                    $data['percentage_no_stock'], 1
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
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function categories($_data, $db, $user) {

    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Category Branch Type']) {
                case 'Root':
                    $level = _('Root');
                    break;
                case 'Head':
                    $level = _('Head');
                    break;
                case 'Node':
                    $level = _('Node');
                    break;
                default:
                    $level = $data['Category Branch Type'];
                    break;
            }
            $level = $data['Category Branch Type'];


            $adata[] = array(
                'id'                  => (integer)$data['Category Key'],
                'store_key'           => (integer)$data['Category Store Key'],
                'code'                => $data['Category Code'],
                'label'               => $data['Category Label'],
                'subjects'            => number(
                    $data['Category Number Subjects']
                ),
                'subjects_active'     => number(
                    $data['Category Number Active Subjects']
                ),
                'subjects_no_active'  => number(
                    $data['Category Number No Active Subjects']
                ),
                'level'               => $level,
                'subcategories'       => number($data['Category Children']),
                'percentage_assigned' => percentage(
                    $data['Category Number Subjects'], ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
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
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function category_all_availeable_parts($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $adata = array();
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


            $adata[] = array(
                'id'               => (integer)$data['Part SKU'],
                'associated'       => $associated,
                'reference'        => $data['Part Reference'],
                'unit_description' => $data['Part Unit Description'],
                'family'           => $data['Category Code']
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


function category_all_parts($_data, $db, $user) {


    $rtext_label = 'part';

    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Part Status']) {
                case 'In Use':
                    $status = sprintf('<span class="" >%s</span>', _('Active'));
                    break;
                case 'Not in Use':
                    $status = sprintf(
                        '<span class="warning" ></span>', _('Discontined')
                    );

                    break;

                default:
                    $status = $data['Part Status'];
                    break;
            }

            $adata[] = array(
                'id'               => (integer)$data['Part SKU'],
                'reference'        => $data['Part Reference'],
                'unit_description' => $data['Part Unit Description'],
                'family'           => ($data['Category Code'] == ''
                    ? '<span class="very_discreet italic">'._('Not associated').'</span>'
                    : '<span class="link" onClick="change_view(\'category/'
                    .$data['Category Key'].'\')">'.$data['Category Code'].'</span>'),
                'status'           => $status
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


function product_families($_data, $db, $user) {


    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if ($data['category_data'] == '') {
                $family          = '<span class="super_discreet">'._('Family not set').'</span>';
                $number_products = '<span class="super_discreet">-</span>';
                $operations      = (in_array($data['Store Key'], $user->stores) ? '<i class="fa fa-plus button" aria-hidden="true" onClick="open_new_product_family('.$data['Store Key'].')" ></i>' : '<i class="fa fa-lock "></i>');
                $code= sprintf('<span >%s</span>', $data['Store Code']);

            } else {
                $family_data = preg_split('/,/', $data['category_data']);


                $family          = sprintf('<span class="button" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Store Key'], $family_data[0], $family_data[1]);
                $number_products = number($data['number_products']);
                $operations      = (in_array($data['Store Key'], $user->stores) ? '<i class="fa fa-refresh button" aria-hidden="true" onClick="open_new_product_family('.$data['Store Key'].')" )"></i>' : '<i class="fa fa-lock "></i>');
                $code= sprintf('<span class="button" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Store Key'], $family_data[0], $data['Store Code']);
            }


            $adata[] = array(
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
            'data'          => $adata,
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
    }  elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label       = 'month';
        $_group_by         = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label       = 'week';
        $_group_by         = ' group by Yearweek(`Date`) ';
        $sql_totals_fields = 'Yearweek(`Date`)';
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
            $to         = ($part->get('Part Status') == 'Not In Use' ? $product->get('Part Valid To') : gmdate('Y-m-d'));
            $date_field = '`Date`';
            break;
        case 'category':
            include_once 'class.Category.php';
            $category   = new Category($_data['parameters']['parent_key']);
            $currency   = $account->get('Account Currency');
            $from       = $category->get('Part Category Valid From');
            $to         = ($category->get('Part Category Status') == 'NotInUse' ? $product->get('Part Category Valid To') : gmdate('Y-m-d'));
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
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s',
        prepare_mysql($from),
        prepare_mysql($to),
        $_group_by,
        "`Date` $order_direction ",
        "$start_from,$number_results"
    );



    $adata = array();

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

                $date  = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00'))/3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            }elseif ($_data['parameters']['frequency'] == 'monthly') {
                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime(
                    "%a %e %b %Y", strtotime($data['Date'].' +0:00')
                );
                $_date = $date;
            }

            $adata[$_date] = array(
                'sales'     => '<span class="very_discreet">'.money(
                        0, $currency
                    ).'</span>',
                'customers' => '<span class="very_discreet">'.number(0).'</span>',
                'invoices'  => '<span class="very_discreet">'.number(0).'</span>',
                'date'      => $date


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
                $from_date = gmdate(
                    "Y-01-01 00:00:00", strtotime($from_date.' +0:00')
                );
                $to_date   = gmdate(
                    "Y-12-31 23:59:59", strtotime($to_date.' +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate(
                    "Y-m-01 00:00:00", strtotime($from_date.' +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-01 00:00:00", strtotime($to_date.' + 1 month +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate(
                    "Y-m-d 00:00:00", strtotime($from_date.'  -1 week  +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-d 00:00:00", strtotime($to_date.' + 1 week +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.' 00:00:00';
                $to_date   = $to_date.' 23:59:59';
            }
            break;
        case 'category':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate("Y-01-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate(
                    "Y-m-01", strtotime($to_date.' + 1 month +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate(
                    "Y-m-d", strtotime($from_date.'  -1 week  +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-d", strtotime($to_date.' + 1 week +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.'';
                $to_date   = $to_date.'';
            }

            break;
        default:
            print_r($_data);
            exit('parent not configured '.$_data['parameters']['parent']);
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s", $date_field, prepare_mysql($from_date), $date_field, prepare_mysql($to_date), " $group_by "
    );

    //print $sql;
    if ($result = $db->query($sql)) {


        foreach ($result as $data) {
            if ($_data['parameters']['frequency'] == 'annually') {
                $date  = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime(
                    "%a %e %b %Y", strtotime($data['Date'].' +0:00')
                );
                $_date = $date;
            }

            if (array_key_exists($_date, $adata)) {


                $adata[$_date] = array(
                    'sales'      => money($data['sales'], $currency),
                    'deliveries' => number($data['deliveries']),
                    'date'       => $date


                );
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
            'data'          => array_values($adata),
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
