<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 20:13:55 GMT+7, Bangkok Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


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
    case 'supplier_parts':
        supplier_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'parts_to_replenish_picking_location':
        part_locations_to_replenish_picking_location(get_table_parameters(), $db, $user);
        break;
    case 'agent_parts':
        agent_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier.order.items':
        order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier.order.items_in_process':
        order_items_in_process(get_table_parameters(), $db, $user, $account);
        break;
    case 'order.items':
        agent_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'client_order.items':
        client_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'invoice.items':
        invoice_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery.items':
        delivery_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery.checking_items':
        delivery_checking_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'suppliers':
        suppliers(get_table_parameters(), $db, $user, $account);
        break;
    case 'suppliers_edit':
        suppliers_edit(get_table_parameters(), $db, $user, $account);
        break;
    case 'agents':
        agents(get_table_parameters(), $db, $user, $account);
        break;
    case 'categories':
        categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders':
        orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'agent_orders':
        agent_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'agent_deliveries':
        agent_deliveries(get_table_parameters(), $db, $user, $account);
        break;

    case 'deliveries':
        deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier.order.supplier_parts':
        order_supplier_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'category_all_suppliers':
        category_all_suppliers(get_table_parameters(), $db, $user, $account);
        break;
    case 'order.supplier_parts':
        order_supplier_all_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'deleted.order.items':
        deleted_order_items(get_table_parameters(), $db, $user, $account);
        break;
    case 'sales_history':
        sales_history(get_table_parameters(), $db, $user, $account);
        break;
    case 'part_locations_with_errors':
        part_locations_with_errors(
            get_table_parameters(), $db, $user, $account
        );
        break;
    case 'surplus_parts':
        parts_by_stock_status(
            'Surplus', get_table_parameters(), $db, $user, $account
        );
        break;
    case 'todo_parts':

        parts_by_stock_status('Todo', get_table_parameters(), $db, $user, $account);
        break;
    case 'todo_paid_parts':
        todo_paid_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier_categories':
        supplier_categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier_timeseries_drill_down_parts':
        timeseries_drill_down_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'supplier_timeseries_drill_down_families':
        timeseries_drill_down_families(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery.costing':
        delivery_costing(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery.items_done':
        delivery_items_done(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery.items_mismatch':
        delivery_items_mismatch(get_table_parameters(), $db, $user, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'tipo not found: '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function suppliers($_data, $db, $user, $account) {


    if ($user->get('User Type') == 'Agent') {

        if (!($_data['parameters']['parent'] == 'agent' and $_data['parameters']['parent_key'] == $user->get(
                'User Parent Key'
            ))) {
            echo json_encode(
                array(
                    'state' => 405,
                    'resp'  => 'Forbidden'
                )
            );
            exit;
        }


    } else {


        if (!$user->can_view('suppliers')) {
            echo json_encode(
                array(
                    'state' => 405,
                    'resp'  => 'Forbidden'
                )
            );
            exit;
        }

    }


    $rtext_label = 'supplier';
    include_once 'prepare_table/init.php';


    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            if ($_data['parameters']['parent'] == 'agent') {
                $operations = sprintf(
                    '<i agent_key="%d" supplier_key="%d"  class="fa fa-unlink button" aria-hidden="true"  onClick="bridge_supplier(this)" ></i>', $_data['parameters']['parent_key'], $data['Supplier Key']
                );
            } else {
                $operations = '';
            }

            /*
			$sales=money($data["Supplier $db_period Acc Invoiced Amount"], $account->get('Account Currency'));

			if (in_array($parameters['f_period'], array('all', '3y', 'three_year'))) {
				$delta_sales='';
			}else {
				$delta_sales='<span title="'.money($data["Supplier $db_period Acc 1Yb Invoiced Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier $db_period Acc Invoiced Amount"], $data["Supplier $db_period Acc 1Yb Invoiced Amount"]).'</span>';
			}

			$profit=money($data["Supplier $db_period Acc Profit"], $account->get('Account Currency'));
			$profit_after_storing=money($data["Supplier $db_period Acc Profit After Storing"], $account->get('Account Currency'));
			$cost=money($data["Supplier $db_period Acc Cost"], $account->get('Account Currency'));
			$margin=percentage($data["Supplier $db_period Acc Margin"], 1);
			$sold=number($data["Supplier $db_period Acc Sold"], 0);
			$required=number($data["Supplier $db_period Acc Required"], 0);
*/

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']
            );

            $table_data[] = array(
                'id'         => (integer)$data['Supplier Key'],
                'operations' => $operations,
                'associated' => $associated,

                'code' => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'name' => $data['Supplier Name'].' <span class="italic discreet">'.$data['Supplier Nickname'].'</span>',

                'supplier_parts'        => number(
                    $data['Supplier Number Parts']
                ),
                'active_supplier_parts' => number(
                    $data['Supplier Number Active Parts']
                ),
                'surplus'               => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                ) > .75
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Surplus Parts'])
                ),
                'optimal'               => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']
                ), number($data['Supplier Number Optimal Parts'])
                ),
                'low'                   => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Low Parts'])
                ),
                'critical'              => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Critical Parts'])
                ),
                'out_of_stock'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Out Of Stock Parts'])
                ),


                'location'  => $data['Supplier Location'],
                'email'     => $data['Supplier Main Plain Email'],
                'telephone' => $data['Supplier Preferred Contact Number Formatted Number'],
                'contact'   => $data['Supplier Main Contact Name'],
                'company'   => $data['Supplier Company Name'],


                'active_supplier_parts' => number(
                    $data['Supplier Number Active Parts']
                ),
                'surplus'               => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']) > .75
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Surplus Parts'])
                ),
                'optimal'               => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']
                ), number($data['Supplier Number Optimal Parts'])
                ),
                'low'                   => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Low Parts'])
                ),
                'critical'              => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Critical Parts'])
                ),
                'out_of_stock'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Out Of Stock Parts'])
                ),


                'sales'    => '<span class="highlight">'.money(
                        $data['sales'], $account->get('Currency Code')
                    ).'</span>',
                'sales_1y' => '<span class="highlight" title="'.money(
                        $data['sales_1y'], $account->get('Currency Code')
                    ).'">'.delta($data['sales'], $data['sales_1y']).'</span>',

                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier Year To Day Acc Invoiced Amount"], $data["Supplier Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 1 Year Ago Invoiced Amount"], $data["Supplier 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 2 Year Ago Invoiced Amount"], $data["Supplier 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 3 Year Ago Invoiced Amount"], $data["Supplier 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 4 Year Ago Invoiced Amount"], $data["Supplier 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier Quarter To Day Acc Invoiced Amount"], $data["Supplier Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 1 Quarter Ago Invoiced Amount"], $data["Supplier 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 2 Quarter Ago Invoiced Amount"], $data["Supplier 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 3 Quarter Ago Invoiced Amount"], $data["Supplier 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Supplier 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Supplier 4 Quarter Ago Invoiced Amount"], $data["Supplier 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),

                //'sold'=>$sold,
                //'required'=>$required,
                //'origin'=>$data['Supplier Products Origin Country Code'],

                //'delivery_time'=>seconds_to_string(3600*24*$data['Supplier Average Delivery Days']),

                //'sales'=>$sales,
                //'delta_sales'=>$delta_sales,
                //'profit'=>$profit,
                //'profit_after_storing'=>$profit_after_storing,
                //'cost'=>$cost,
                //'pending_pos'=>number($data['Supplier Number Open Purchase Orders']),
                //'margin'=>$margin,


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


function suppliers_edit($_data, $db, $user, $account) {


    $rtext_label = 'supplier';
    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $table_data[] = array(
                'id'   => (integer)$data['Supplier Key'],
                'link' => $data['Supplier Code'],

                'checkbox'   => sprintf(
                    '<i key="" class="far fa-square fa-fw button" aria-hidden="true"></i>', $data['Supplier Key']
                ),
                'operations' => sprintf(
                    '<i key="" class="fa fa-fw fa-cloud hide button" aria-hidden="true"></i>', $data['Supplier Key']
                ),
                'code'       => $data['Supplier Code'],
                'name'       => $data['Supplier Name'],

                'email'     => $data['Supplier Main Plain Email'],
                'mobile'    => $data['Supplier Main XHTML Mobile'],
                'telephone' => $data['Supplier Main XHTML Telephone'],
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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function agents($_data, $db, $user, $account) {

    if (!$user->can_view('suppliers')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }

    $rtext_label = 'agent';
    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $table_data[] = array(
                'id'             => (integer)$data['Agent Key'],
                'code'           => sprintf('<span class="link" onclick="change_view(\'agent/%d\')">%s</span>', $data['Agent Key'], $data['Agent Code']),
                'name'           => $data['Agent Name'],
                'suppliers'      => number($data['Agent Number Suppliers']),
                'supplier_parts' => number($data['Agent Number Parts']),


                'surplus'      => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Agent Number Surplus Parts'], $data['Agent Number Parts']
                ) > .75
                    ? 'error'
                    : (ratio(
                        $data['Agent Number Surplus Parts'], $data['Agent Number Parts']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Agent Number Surplus Parts'], $data['Agent Number Parts']
                    ), number($data['Agent Number Surplus Parts'])
                ),
                'optimal'      => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Agent Number Optimal Parts'], $data['Agent Number Parts']
                ), number($data['Agent Number Optimal Parts'])
                ),
                'low'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Agent Number Low Parts'], $data['Agent Number Parts']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Agent Number Low Parts'], $data['Agent Number Parts']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Agent Number Low Parts'], $data['Agent Number Parts']
                    ), number($data['Agent Number Low Parts'])
                ),
                'critical'     => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Agent Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Agent Number Critical Parts'], $data['Agent Number Parts']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Agent Number Critical Parts'], $data['Agent Number Parts']
                    ), number($data['Agent Number Critical Parts'])
                ),
                'out_of_stock' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Agent Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Agent Number Out Of Stock Parts'], $data['Agent Number Parts']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Agent Number Out Of Stock Parts'], $data['Agent Number Parts']
                    ), number($data['Agent Number Out Of Stock Parts'])
                ),


                'location'  => $data['Agent Location'],
                'email'     => $data['Agent Main Plain Email'],
                'telephone' => $data['Agent Preferred Contact Number Formatted Number'],
                'contact'   => $data['Agent Main Contact Name'],
                'company'   => $data['Agent Company Name'],

                'sales'    => '<span class="highlight">'.money(
                        $data['sales'], $account->get('Currency Code')
                    ).'</span>',
                'sales_1y' => '<span class="highlight" title="'.money(
                        $data['sales_1y'], $account->get('Currency Code')
                    ).'">'.delta($data['sales'], $data['sales_1y']).'</span>',

                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent Year To Day Acc Invoiced Amount"], $data["Agent Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 1 Year Ago Invoiced Amount"], $data["Agent 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 2 Year Ago Invoiced Amount"], $data["Agent 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 3 Year Ago Invoiced Amount"], $data["Agent 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 4 Year Ago Invoiced Amount"], $data["Agent 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent Quarter To Day Acc Invoiced Amount"], $data["Agent Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 1 Quarter Ago Invoiced Amount"], $data["Agent 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 2 Quarter Ago Invoiced Amount"], $data["Agent 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 3 Quarter Ago Invoiced Amount"], $data["Agent 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Agent 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
                ), delta_icon(
                        $data["Agent 4 Quarter Ago Invoiced Amount"], $data["Agent 4 Quarter Ago 1YB Invoiced Amount"]
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
            'data'          => $table_data,
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

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

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


            $table_data[] = array(
                'id'                  => (integer)$data['Category Key'],
                'store_key'           => (integer)$data['Category Store Key'],
                'code'                => $data['Category Code'],
                'label'               => $data['Category Label'],
                'subjects'            => number(
                    $data['Category Number Subjects']
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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function orders($_data, $db, $user, $account) {

    if (!$user->can_view('suppliers')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'purchase order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Purchase Order State']) {


                case 'InProcess':
                    $state = _('In Process');
                    break;
                case 'SubmittedAgent':
                case 'Submitted':
                    $state = _('Submitted');
                    break;
                case 'Editing_Submitted':
                    $state = _('Submitted').' ('._('editing').')';
                    break;
                case 'Inputted':
                    $state = _('Delivery in process');
                    break;
                case 'Dispatched':
                    $state = _('Delivery dispatched');
                    break;
                case 'Received':
                    $state = _('Received');
                    break;
                case 'Checked':
                    $state = _('Checked');
                    break;
                case 'Placed':
                    $state = _('Booked in');
                    break;
                case 'Costing':
                    $state = _('Booked in').' ('._('Review costing').')';
                    break;
                case 'InvoiceChecked':
                    $state = _('Booked in').' ('._('Costing done').')';
                    break;
                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order State'];
                    break;
            }


            $table_data[] = array(
                'id'              => (integer)$data['Purchase Order Key'],
                'parent'          => sprintf('<span class="link" onclick="change_view(\'/%s/%d\')" >%s</span>  ', strtolower($data['Purchase Order Parent']), $data['Purchase Order Parent Key'], $data['Purchase Order Parent Name']),
                'public_id'       => sprintf(
                    '<span class="link" onclick="change_view(\'suppliers/order/%d\')" >%s</span>  ', $data['Purchase Order Key'],
                    ($data['Purchase Order Public ID'] == '' ? '<i class="fa fa-exclamation-circle error"></i> <span class="very_discreet italic">'._('empty').'</span>' : $data['Purchase Order Public ID'])
                ),
                'date'            => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date'       => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'           => $state,
                'total_amount'    => money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']),
                'total_ac_amount' => money($data['Purchase Order Total Amount'] * $data['Purchase Order Currency Exchange'], $account->get('Currency Code')),
                'user'            => $data['Purchase Order Main Buyer Name']

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


function agent_orders($_data, $db, $user) {


    if (!$user->can_view('suppliers')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'purchase order';


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Purchase Order State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;

                case 'Submitted':
                    $state = sprintf('%s', _('Submitted'));
                    break;
                case 'Confirmed':
                    $state = sprintf('%s', _('Confirmed'));
                    break;
                case 'In Warehouse':
                    $state = sprintf('%s', _('In Warehouse'));
                    break;
                case 'Done':
                    $state = sprintf('%s', _('Done'));
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Purchase Order State'];
                    break;
            }

            $table_data[] = array(
                'id' => (integer)$data['Purchase Order Key'],


                'public_id' => sprintf('<span class="link" onclick="change_view(\'agent/%d/order/%d\')">%s</span>', $data['Purchase Order Parent Key'], $data['Purchase Order Key'], $data['Purchase Order Public ID']),
                'date'      => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'     => $state,

                'total_amount' => money(
                    $data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']
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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function agent_deliveries($_data, $db, $user) {

    /*
        if ($user->get('User Type') != 'Agent') {
            echo json_encode(
                array(
                    'state' => 405,
                    'resp'  => 'Forbidden'
                )
            );
            exit;
        }


        $_data['parameters']['agent_key'] = $user->get('User Parent Key');
    */

    $rtext_label = 'delivery';


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;
                case 'Submitted':
                    $state = sprintf('%s', _('Submitted'));
                    break;
                case 'Confirmed':
                    $state = sprintf('%s', _('Confirmed'));
                    break;
                case 'In Warehouse':
                    $state = sprintf('%s', _('In Warehouse'));
                    break;
                case 'Done':
                    $state = sprintf('%s', _('Done'));
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Supplier Delivery State'];
                    break;
            }

            $table_data[] = array(
                'id'        => (integer)$data['Supplier Delivery Key'],
                'public_id' => sprintf('<span class="link" onClick="change_view(\'agent/%d/delivery/%d\')">%s</span>', $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']),
                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),
                'state'     => $state,

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


function deliveries($_data, $db, $user) {
    $rtext_label = 'delivery';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;
                case 'Consolidated':
                    $state = sprintf('%s', _('Consolidated'));
                    break;
                case 'Dispatched':
                    $state = sprintf('%s', _('Dispatched'));
                    break;
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
                    $state = _('Booked in').', '._('checking costing');
                    break;
                case 'InvoiceChecked':
                    $state = _('Booked in').', '._('costing done').' <i class="fa fa-check success"></i>';
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
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


                'parent'    => sprintf('<span class="link" onclick="change_view(\'/%s/%d\')" >%s</span>  ', strtolower($data['Supplier Delivery Parent']), $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Parent Name']),
                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'%s/%d/delivery/%d\')" >%s</span>  ', strtolower($data['Supplier Delivery Parent']), $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
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


function order_items_in_process($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'utils/supplier_order_functions.php';

    $purchase_order = get_object('PurchaseOrder', $_data['parameters']['parent_key']);


    include_once 'utils/supplier_order_functions.php';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    //$exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            /*
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
            */

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


            $transaction_key  = '';
            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            if (!$data['Supplier Delivery Key']) {
                /*
                                $delivery_qty = $data['Purchase Order Ordering Units'];

                                $delivery_quantity = sprintf(
                                    '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                                    $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                                );
                */
                $delivery_quantity = '';
            } else {
                $delivery_quantity = number($data['Supplier Delivery Units']);

            }


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" title="%s" >%s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link" onclick="change_view(\'part/%d\')" title="%s"><i class="far fa-box"></i> %s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link" onclick="change_view(\'part/%d\')" ><i class="far fa-box" title="%s"></i> </span>', $data['Part SKU'], _('Part reference is same as supplier product code')
                );
            }

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


            list($_state) = get_purchase_order_transaction_data($data);


            $state = '<span class="transaction_state_'.$data['Purchase Order Transaction Fact Key'].'">';
            $state .= $_state;
            $state .= '</span>';

            $data['units_qty'] = $data['Purchase Order Ordering Units'];

            $data['account_currency_code'] = $account->get('Account Currency');
            $data['currency_code']         = $purchase_order->get('Purchase Order Currency Code');
            $data['exchange']              = $purchase_order->get('Purchase Order Currency Exchange');


            $subtotals = get_purchase_order_subtotals($data);


            $unit_price = $data['Supplier Part Unit Cost'];

            $unit_price_description = '<span class="discreet">'._('Unit cost').':</span> '.money($unit_price, $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $unit_price_description .= ' <span class="">('.money($unit_price * $data['exchange'], $data['account_currency_code']).')</span>';

            }


            $description = '<div style="font-size:90%" >'.($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '');


            $description_units = $description.' '.$data['Supplier Part Description'].'<div>'.$unit_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description .= sprintf(
                    ' <span class="discreet"><span title="%s">Min order</span>:%s<span>', _('Minimum order (units)'), number($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'] * $data['Supplier Part Minimum Carton Order'])
                );
            }


            $sko_price_description = '<span class="discreet">'._('SKO cost').':</span> '.money($unit_price * $data['Part Units Per Package'], $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $sko_price_description .= ' <span class="">('.money($unit_price * $data['Part Units Per Package'] * $data['exchange'], $data['account_currency_code']).')</span>';

            }


            $description_skos = $description.'<span class="">'.$data['Part Units Per Package'].'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<div>'.$sko_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_skos .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }


            $cartons_price_description = '<span class="discreet">'._('Carton cost').':</span> '.money($unit_price * $data['Part Units Per Package'] * $skos_per_carton, $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $cartons_price_description .= ' <span class="">('.money($unit_price * $data['Part Units Per Package'] * $skos_per_carton * $data['exchange'], $data['account_currency_code']).')</span>';

            }

            $description_cartons = $description.'<span class="">'.$units_per_carton.'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<div>'.$cartons_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_cartons .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }


            if ($data['Part Stock Status'] == 'Out_Of_Stock' or $data['Part Stock Status'] == 'Error') {
                $available_forecast = '';
            } else {
                if (in_array(
                    $data['Part Products Web Status'], array(
                                                         'No Products',
                                                         'Offline',
                                                         'Out of Stock'
                                                     )
                )) {
                    $available_forecast = '';

                } elseif ($data['Part On Demand'] == 'Yes') {

                    $available_forecast = '<span >'.sprintf(
                            _('%s in stock'),
                            '<span  title="'.sprintf("%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';

                    if ($data['Part Fresh'] == 'No') {
                        $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    } else {
                        $available_forecast = ' <i class="far fa-lemon padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    }
                } else {
                    $available_forecast = '<span >'.sprintf(
                            _('%s availability'), '<span  title="'.sprintf(
                                                    "%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))
                                                ).'">'.seconds_to_until(
                                                    $data['Part Days Available Forecast'] * 86400
                                                ).'</span>'
                        ).'</span>';


                }
            }

            $average_sales_per_year_units = '&lang;'.number($data['Part 1 Year Acc Dispatched'] * $data['Part Units Per Package']).' u/y&rang;';
            $average_sales_per_year_skos  = '&lang;'.number($data['Part 1 Year Acc Dispatched']).' SKOs/y&rang;';


            if ($data['Supplier Part Packages Per Carton'] != 0) {
                $average_sales_per_year_cartons = '&lang;'.number($data['Part 1 Year Acc Dispatched'] / $data['Supplier Part Packages Per Carton']).' C/y&rang;';

            } else {
                $average_sales_per_year_cartons = '';
            }


            $description_units = $description_units.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (units)').'">'.number($data['Part Current On Hand Stock'] * $data['Part Units Per Package']).'<span class="small">u.</span></span> '
                .$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_units.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched'] * $data['Part Units Per Package']).'</div>
			    </div>
			</div>';

            $description_skos = $description_skos.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (SKOs)').'">'.number($data['Part Current On Hand Stock']).'<span class="small">sko.</span></span> '.$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_skos.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched']).'</div>
			    </div>
			</div>';

            if ($data['Supplier Part Packages Per Carton'] != 0) {
                $description_cartons = $description_cartons.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (cartons)').'">'.number($data['Part Current On Hand Stock'] / $data['Supplier Part Packages Per Carton'])
                    .'<span class="small discreet">C.</span></span> '.$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_cartons.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			    </div>
			</div>';
            }


            $units_qty = $data['Purchase Order Ordering Units'];

            if ($data['Part Units Per Package'] != 0 and $data['Supplier Part Packages Per Carton'] != 0) {
                $skos_qty    = $units_qty / $data['Part Units Per Package'];
                $cartons_qty = $skos_qty / $data['Supplier Part Packages Per Carton'];
            } else {
                $skos_qty    = '';
                $cartons_qty = '';
            }


            if ($data['Part Units Per Package'] == 0 or $data['Purchase Order Ordering Units'] % ($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton']) != 0 or $data['Purchase Order Ordering Units'] % ($data['Part Units Per Package']) != 0) {
                $class = 'error';
            } else {
                $class = '';
            }


            $quantity_units = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Units", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_units_qty %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($units_qty == 0 ? '' : $units_qty + 0), ($units_qty == 0 ? '' : $units_qty + 0)

            );

            $quantity_skos = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order SKOs", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_skos_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($skos_qty == 0 ? '' : $skos_qty + 0), ($skos_qty == 0 ? '' : $skos_qty + 0)

            );

            $quantity_cartons = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Cartons", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_cartons_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($cartons_qty == 0 ? '' : $cartons_qty + 0), ($cartons_qty == 0 ? '' : $cartons_qty + 0)

            );


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries = array();
            } else {
                $next_deliveries = json_decode($data['Part Next Deliveries Data'], true);
            }


            $other_deliveries_units   = '';
            $other_deliveries_skos    = '';
            $other_deliveries_cartons = '';


            foreach ($next_deliveries as $next_delivery) {
                if (isset($next_delivery['po_key']) and $next_delivery['po_key'] != $purchase_order->id) {

                    $other_deliveries_units   .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Units ordered').'">+'.number($next_delivery['raw_units_qty'])
                        .'<span style="font-weight: normal" class="small discreet">u.</span></div></div>';
                    $other_deliveries_skos    .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                            $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                        ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';
                    $other_deliveries_cartons .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Cartons ordered').'">+'.number(
                            $next_delivery['raw_units_qty'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']
                        ).'<span style="font-weight: normal" class="small discreet">C.</span></div></div>';


                }
            }


            $other_deliveries_units   = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_units.'</div>';
            $other_deliveries_skos    = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_skos.'</div>';
            $other_deliveries_cartons = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_cartons.'</div>';


            //   $image = '';
            $table_data[] = array(

                'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
                'item_index'        => $data['Purchase Order Item Index'],
                'parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                'parent_type'       => strtolower($purchase_order->get('Purchase Order Parent')),
                'supplier_part_key' => (integer)$data['Supplier Part Key'],
                'supplier_key'      => (integer)$data['Supplier Key'],
                'checkbox'          => sprintf('<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),
                'operations'        => sprintf('<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']),


                'reference' => $reference,

                'unit_description'         => $data['Supplier Part Description'].'<br><span class="discreet">'._('Unit cost').':</span> '.money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'units_per_sko'            => number($data['Part Units Per Package']),
                'unit_cost'                => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'skos_per_carton'          => number($skos_per_carton),
                'delivery_quantity'        => $delivery_quantity,
                'subtotals'                => $subtotals,
                'supplier_key'             => $data['Supplier Key'],
                'supplier'                 => $data['Supplier Code'],
                'unit'                     => $data['Supplier Part Description'],
                'image'                    => $image,
                'state'                    => $state,
                'description_units'        => $description_units,
                'description_skos'         => $description_skos,
                'description_cartons'      => $description_cartons,
                'quantity_units'           => $quantity_units,
                'quantity_skos'            => $quantity_skos,
                'quantity_cartons'         => $quantity_cartons,
                'other_deliveries_units'   => $other_deliveries_units,
                'other_deliveries_skos'    => $other_deliveries_skos,
                'other_deliveries_cartons' => $other_deliveries_cartons,


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


function order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'utils/supplier_order_functions.php';

    $purchase_order = get_object('PurchaseOrder', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    // $exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if ($data['Part Units Per Package'] == 0) {
                continue;

            }


            $current_units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $current_skos_per_carton  = $data['Supplier Part Packages Per Carton'];


            $units_per_carton = $data['Purchase Order Submitted Units Per SKO'] * $data['Purchase Order Submitted SKOs Per Carton'];
            $skos_per_carton  = $data['Purchase Order Submitted SKOs Per Carton'];
            $units_per_sko    = $data['Purchase Order Submitted Units Per SKO'];

            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" title="%s" >%s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link" onclick="change_view(\'part/%d\')" title="%s"><i class="far fa-box"></i> %s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link" onclick="change_view(\'part/%d\')" ><i class="far fa-box" title="%s"></i> </span>', $data['Part SKU'], _('Part reference is same as supplier product code')
                );
            }

            if ($purchase_order->get('Purchase Order Parent') == 'Agent') {
                $reference .= sprintf(
                    '<br><span class="small link" onclick="change_view(\'supplier/%d\')" ><i class="fal fa-hand-holding-box" title="%s"></i> %s</span>', $data['Supplier Key'], _('Supplier').': '.$data['Supplier Name'], $data['Supplier Code']
                );
            }

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


            list($_state) = get_purchase_order_transaction_data($data);


            $state = '<span class="transaction_state_'.$data['Purchase Order Transaction Fact Key'].'">';
            $state .= $_state;
            $state .= '</span>';


            $items_qty = $data['Purchase Order Submitted Units'].'<span class="small discreet">u.</span> | ';

            if ($units_per_sko == 0) {
                $items_qty .= '<span class="error">Units per SKO=0</span> ';
            } else {


                if ($data['Purchase Order Submitted Units'] % $units_per_sko != 0) {
                    $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $units_per_sko, 3).'<span class="small discreet">sko.</span></span> | ';

                } else {
                    $items_qty .= number($data['Purchase Order Submitted Units'] / $units_per_sko, 3).'<span class="small discreet">sko.</span> | ';

                }


                if ($data['Purchase Order Submitted Units'] % ($units_per_sko * $skos_per_carton) != 0) {
                    $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $units_per_sko / $skos_per_carton, 3).'<span title="'._('Cartons').'" class="small discreet">C.</span></span>';

                } else {
                    $items_qty .= number($data['Purchase Order Submitted Units'] / $units_per_sko / $skos_per_carton, 3).'<span title="'._('Cartons').'" class="small discreet">C.</span>';

                }
            }

            $items_qty = '<span class="submitted_items_qty" onclick="use_submitted_qty_in_delivery(this,'.$data['Purchase Order Submitted Units'].')">'.$items_qty.'</span>';


            $amount = money($data['Purchase Order Net Amount'], $purchase_order->get('Purchase Order Currency Code'));


            if ($data['Supplier Part Currency Code'] != $account->get('Account Currency')) {


                $amount .= ' <span class="">('.money($data['Purchase Order Net Amount'] * $purchase_order->get('Purchase Order Currency Exchange'), $account->get('Account Currency')).')</span>';

            }

            if ($data['Purchase Order Submitted Unit Cost'] != $data['Supplier Part Unit Cost']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Unit cost changed').' 
                <span title="'._('Submitted unit cost').'">'.money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code')).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current unit cost').'">'.money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'cost\','
                    .$data['Purchase Order Transaction Fact Key'].')" class="button fa fa-sync-alt" title="'._('Update item amount to current price').'"></i></div>';
            }


            if ($data['Purchase Order Submitted Unit Extra Cost Percentage'] != $data['Supplier Part Unit Extra Cost Percentage']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Extra cost % changed').' 
                <span title="'._('Submitted extra cost %').'">'.percentage($data['Purchase Order Submitted Unit Extra Cost Percentage'], 1, 1).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current extra cost %').'">'.percentage($data['Supplier Part Unit Extra Cost Percentage'], 1, 1).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'extra_cost\','
                    .$data['Purchase Order Transaction Fact Key'].')" class="button fa fa-sync-alt" title="'._('Update item amount to current extra cost').'"></i></div>';
            }

            $amount = '<span class="po_amount_'.$data['Purchase Order Transaction Fact Key'].'">'.$amount.'</span>';

            if ($data['Part Package Weight'] > 0) {
                $weight = weight(
                    $data['Part Package Weight'] * $data['Purchase Order Submitted Units'] / $units_per_sko
                );
            } else {
                $weight = '<i class="error fa fa-exclamation-circle"></i>';
            }
            if ($data['Supplier Part Carton CBM'] > 0) {
                $cbm = number(
                        $data['Purchase Order Submitted Units'] * $data['Supplier Part Carton CBM'] / $units_per_sko / $skos_per_carton
                    ).' m³';
            } else {
                $cbm = '<i class="error fa fa-exclamation-circle"></i>';

            }


            $description = '<div style="font-size:90%" >'.($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '');


            $description_units = $description.' '.$data['Supplier Part Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $units_per_sko).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_units .= sprintf(
                    ' <span class="discreet"><span title="%s">Min order</span>:%s<span>', _('Minimum order (units)'), number($units_per_sko * $skos_per_carton * $data['Supplier Part Minimum Carton Order'])
                );
            }

            $description_skos = $description.'<span class="">'.$units_per_sko.'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $units_per_sko).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_skos .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }

            $description_cartons = $description.'<span class="">'.$units_per_carton.'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $units_per_sko).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_cartons .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }


            $units_qty       = $data['Purchase Order Submitted Units'];
            $skos_qty        = $units_qty / $units_per_sko;
            $cartons_qty     = $skos_qty / $skos_per_carton;
            $transaction_key = $data['Purchase Order Transaction Fact Key'];

            if (

                floor($data['Purchase Order Submitted Units']) != $data['Purchase Order Submitted Units'] or floor($skos_qty) != $skos_qty

            ) {
                $class = 'error';
            } else {
                $class = '';
            }

            /*
                        if ($data['Part Next Deliveries Data'] == '') {
                            $next_deliveries = array();
                        } else {
                            $next_deliveries = json_decode($data['Part Next Deliveries Data'], true);
                        }


                        $other_deliveries_units   = '';
                        $other_deliveries_skos    = '';
                        $other_deliveries_cartons = '';


                        foreach ($next_deliveries as $next_delivery) {
                            if (isset($next_delivery['po_key']) and $next_delivery['po_key'] != $purchase_order->id) {

                                $other_deliveries_units   .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Units ordered').'">+'.number($next_delivery['raw_units_qty'])
                                    .'<span style="font-weight: normal" class="small discreet">u.</span></div></div>';
                                $other_deliveries_skos    .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                                        $next_delivery['raw_units_qty'] / $units_per_sko
                                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';
                                $other_deliveries_cartons .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Cartons ordered').'">+'.number(
                                        $next_delivery['raw_units_qty'] / $units_per_sko / $skos_per_carton
                                    ).'<span style="font-weight: normal" class="small discreet">C.</span></div></div>';


                            }
                        }


                        $other_deliveries_units   = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_units.'</div>';
                        $other_deliveries_skos    = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_skos.'</div>';
                        $other_deliveries_cartons = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_cartons.'</div>';
            */

            //   $image = '';

            if ($data['Purchase Order Transaction State'] == 'Submitted') {
                $operations = sprintf('<i key="%d" class="far fa-fw fa-check-square  button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']);

                $quantity_units = sprintf(
                    '<span   class="delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{ "key":%d,  "type": "Units", "sko_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input  class=" create_delivery_item_qty order_units_qty %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                    $transaction_key, $transaction_key, $units_per_sko, $skos_per_carton * $units_per_sko, $class, ($units_qty == 0 ? '' : $units_qty + 0), ($units_qty == 0 ? '' : $units_qty + 0)

                );

                $quantity_skos = sprintf(
                    '<span  class="delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{"type": "SKOs", "unit_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class=" create_delivery_item_qty order_skos_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                    $transaction_key, $units_per_sko, $skos_per_carton, $class, ($skos_qty == 0 ? '' : $skos_qty + 0), ($skos_qty == 0 ? '' : $skos_qty + 0)

                );

                $quantity_cartons = sprintf(
                    '<span   class="delivery_quantity_%d delivery_quantity_item_container on"   data-settings=\'{"type": "Cartons", "unit_factor":%d , "sko_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class=" create_delivery_item_qty order_cartons_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                    $transaction_key, $units_per_sko * $skos_per_carton, $skos_per_carton, $class, ($cartons_qty == 0 ? '' : $cartons_qty + 0), ($cartons_qty == 0 ? '' : $cartons_qty + 0)

                );
            } else {
                $operations       = '';
                $quantity_units   = '';
                $quantity_skos    = '';
                $quantity_cartons = '';
            }


            $description_skos .= '<i  onclick="show_item_po_note(this)" class="item_po_note_new  very_discreet_on_hover  '.($data['Note to Supplier'] == '' ? '' : 'hide')
                .'   margin_left_10 fal fa-sticky-note button"></i> <i onclick="save_po_note(this)" class="item_po_note_save hide save fa fa-cloud"></i><div ><textarea  data-potfk="'.$data['Purchase Order Transaction Fact Key']
                .'" class="item_po_note hide"  style="height: 50px;width: 80%">'.$data['Note to Supplier'].'</textarea>  </div>

<span style="background-color: yellow;padding:1px 10px"  onclick="show_item_po_note(this)" class="item_po_note_display button '.($data['Note to Supplier'] == '' ? 'hide' : '').'">'.$data['Note to Supplier'].'</span>


';


            $table_data[] = array(

                'id'         => (integer)$data['Purchase Order Transaction Fact Key'],
                //  'item_index'        => $data['Purchase Order Item Index'],
                //  'parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                //  'parent_type'       => strtolower($purchase_order->get('Purchase Order Parent')),
                //  'supplier_part_key' => (integer)$data['Supplier Part Key'],
                //  'supplier_key'      => (integer)$data['Supplier Key'],
                // 'checkbox'          => sprintf('<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),
                'operations' => $operations,


                'reference' => $reference,

                'unit_description' => $data['Supplier Part Description'].'<br><span class="discreet">'._('Unit cost').':</span> '.money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'units_per_sko'    => number($units_per_sko),
                'unit_cost'        => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                'skos_per_carton'  => number($skos_per_carton),
                //'ordered_skos'     => number($skos_per_carton * $data['Purchase Order Quantity']),
                //'ordered_cartons'  => number($data['Purchase Order Quantity']),
                //'delivery_quantity' => $delivery_quantity,
                'supplier'         => $data['Supplier Code'],

                'image'               => $image,
                'state'               => $state,
                'description_units'   => $description_units,
                'description_skos'    => $description_skos,
                'description_cartons' => $description_cartons,
                'quantity_units'      => $quantity_units,
                'quantity_skos'       => $quantity_skos,
                'quantity_cartons'    => $quantity_cartons,
                //'other_deliveries_units'   => $other_deliveries_units,
                //'other_deliveries_skos'    => $other_deliveries_skos,
                //'other_deliveries_cartons' => $other_deliveries_cartons,
                'items_qty'           => $items_qty,


                'amount' => $amount,
                'weight' => $weight,
                'cbm'    => $cbm,


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


function agent_order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'class.PurchaseOrder.php';
    $purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    //$exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            /*
                        switch ($data['Part Stock Status']) {
                            case 'Surplus':
                                $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true" title="'._('Surplus').'" ></i>';
                                break;
                            case 'Optimal':
                                $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true" title="'._('Optimal').'"></i>';
                                break;
                            case 'Low':
                                $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true" title="'._('Low').'"></i>';
                                break;
                            case 'Critical':
                                $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true" title="'._('Critical').'"></i>';
                                break;
                            case 'Out_Of_Stock':
                                $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true" title="'._('Out of stock').'"></i>';
                                break;
                            case 'Error':
                                $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true" title="'._('Error').'"></i>';
                                break;
                            default:
                                $stock_status = $data['Part Stock Status'];
                                break;
                        }

                        $quantity = number($data['Purchase Order Quantity']);
            */

            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];

            /*
                        $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
                        if ($data['Purchase Order Quantity'] > 0) {

                            $subtotals .= $data['Purchase Order Quantity'] * $units_per_carton.'u. '.$data['Purchase Order Quantity'] * $skos_per_carton.'pkg. ';


                            $amount = $data['Purchase Order Quantity'] * $units_per_carton * $data['Supplier Part Unit Cost'];

                            $subtotals .= money(
                                $amount, $purchase_order->get('Purchase Order Currency Code')
                            );

                            if ($data['Supplier Part Currency Code'] != $account->get(
                                    'Account Currency'
                                )) {
                                $subtotals .= ' <span class="">('.money(
                                        $amount * $purchase_order->get(
                                            'Purchase Order Currency Exchange'
                                        ), $account->get('Account Currency')
                                    ).')</span>';

                            }

                            if ($data['Part Package Weight'] > 0) {
                                $subtotals .= ' | '.weight(
                                        $data['Part Package Weight'] * $data['Purchase Order Quantity'] * $data['Supplier Part Packages Per Carton']
                                    );
                            }
                            if ($data['Supplier Part Carton CBM'] > 0) {
                                $subtotals .= ' | '.number(
                                        $data['Purchase Order Quantity'] * $data['Supplier Part Carton CBM']
                                    ).' m³';
                            }
                        }
                        $subtotals .= '</span>';
            */

            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$units_per_carton.'</b>', '<b>'.$skos_per_carton.'</b>'
            );
            /*
                        if (!$data['Supplier Delivery Key']) {

                            $delivery_qty = $data['Purchase Order Quantity'];

                            $delivery_quantity = sprintf(
                                '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                                $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                            );
                        } else {
                            $delivery_quantity = number(
                                $data['Supplier Delivery Units']
                            );

                        }
            */

            $description = ($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '').$data['Supplier Part Description'];


            /*
                        $quantity = sprintf(
                            '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                        <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                        <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s">
                        <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',
                            $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
                        );
            */

            /*

            $quantity = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Quantity'] + 0, $data['Purchase Order Quantity'] + 0
            );
            */

            $table_data[] = array(

                'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
                'item_index'        => $data['Purchase Order Item Index'],
                'parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                'parent_type'       => strtolower($purchase_order->get('Purchase Order Parent')),
                'supplier_part_key' => (integer)$data['Supplier Part Key'],
                'supplier_key'      => (integer)$data['Supplier Key'],
                'checkbox'          => sprintf('<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),
                'operations'        => sprintf('<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']),


                'reference' => sprintf(
                    '<span class="link" onclick="change_view(\'/%s/%d/part/%d\')" >%s</span>  ', strtolower($purchase_order->get('Purchase Order Parent')), $purchase_order->get('Purchase Order Parent Key'), $data['Supplier Part Key'], $data['Supplier Part Reference']
                ),

                'packing' => $packing,

                'packed_in'        => $data['Part Units Per Package'],
                'units_per_carton' => $units_per_carton,
                'sko_per_carton'   => $skos_per_carton,

                'description'       => $description,
                'quantity'          => $quantity,
                'delivery_quantity' => $delivery_quantity,
                'subtotals'         => $subtotals,
                // 'ordered'           => number($data['Purchase Order Quantity']),
                'supplier_key'      => $data['Supplier Key'],
                'supplier'          => $data['Supplier Code'],
                'unit_cost'         => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                //'qty_units'         => number($units_per_carton * $data['Purchase Order Quantity']),
                //'qty_cartons'       => number($data['Purchase Order Quantity']),
                //'amount'            => money($data['Supplier Part Unit Cost'] * $data['Purchase Order Quantity'] * $units_per_carton, $purchase_order->get('Purchase Order Currency Code')),

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


function client_order_items($_data, $db, $user, $account) {


    $rtext_label = 'item';

    include_once 'class.PurchaseOrder.php';
    $purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();
    // $exchange   = -1;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            /*
                        switch ($data['Part Stock Status']) {
                            case 'Surplus':
                                $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true" title="'._('Surplus').'" ></i>';
                                break;
                            case 'Optimal':
                                $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true" title="'._('Optimal').'"></i>';
                                break;
                            case 'Low':
                                $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true" title="'._('Low').'"></i>';
                                break;
                            case 'Critical':
                                $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true" title="'._('Critical').'"></i>';
                                break;
                            case 'Out_Of_Stock':
                                $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true" title="'._('Out of stock').'"></i>';
                                break;
                            case 'Error':
                                $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true" title="'._('Error').'"></i>';
                                break;
                            default:
                                $stock_status = $data['Part Stock Status'];
                                break;
                        }

                        $quantity = number($data['Purchase Order Quantity']);
            */

            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];
            $skos_per_carton  = $data['Supplier Part Packages Per Carton'];

            /*
                        $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
                        if ($data['Purchase Order Quantity'] > 0) {

                            $subtotals .= $data['Purchase Order Quantity'] * $units_per_carton.'u. '.$data['Purchase Order Quantity'] * $skos_per_carton.'pkg. ';


                            $amount = $data['Purchase Order Quantity'] * $units_per_carton * $data['Supplier Part Unit Cost'];

                            $subtotals .= money(
                                $amount, $purchase_order->get('Purchase Order Currency Code')
                            );

                            if ($data['Supplier Part Currency Code'] != $account->get(
                                    'Account Currency'
                                )) {
                                $subtotals .= ' <span class="">('.money(
                                        $amount * $purchase_order->get(
                                            'Purchase Order Currency Exchange'
                                        ), $account->get('Account Currency')
                                    ).')</span>';

                            }

                            if ($data['Part Package Weight'] > 0) {
                                $subtotals .= ' | '.weight(
                                        $data['Part Package Weight'] * $data['Purchase Order Quantity'] * $data['Supplier Part Packages Per Carton']
                                    );
                            }
                            if ($data['Supplier Part Carton CBM'] > 0) {
                                $subtotals .= ' | '.number(
                                        $data['Purchase Order Quantity'] * $data['Supplier Part Carton CBM']
                                    ).' m³';
                            }
                        }
                        $subtotals .= '</span>';
            */

            $packing = sprintf(
                '<i class="fa fa-fw fa-gift" aria-hidden="true" ></i> %ss, (%s <i class="far fa-fw fa-dot-circle discreet" aria-hidden="true" ></i>, %s <i class="fa fa-fw fa-gift " aria-hidden="true" ></i>)/<i class="fa fa-fw fa-dropbox" aria-hidden="true" ></i>',
                '<b>'.$data['Part Units Per Package'].'</b>', '<b>'.$units_per_carton.'</b>', '<b>'.$skos_per_carton.'</b>'
            );
            /*
                        if (!$data['Supplier Delivery Key']) {

                            $delivery_qty = $data['Purchase Order Quantity'];

                            $delivery_quantity = sprintf(
                                '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                                $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0, $delivery_qty + 0
                            );
                        } else {
                            $delivery_quantity = number(
                                $data['Supplier Delivery Units']
                            );

                        }
            */

            $description = ($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '').$data['Supplier Part Description'];


            /*
                        $quantity = sprintf(
                            '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                        <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                        <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s">
                        <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',
                            $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
                        );
            */
            /*
            $quantity = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'],
                $data['Purchase Order Quantity'] + 0, $data['Purchase Order Quantity'] + 0
            );
*/
            $table_data[] = array(

                'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
                'item_index'        => $data['Purchase Order Item Index'],
                'parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                'parent_type'       => strtolower($purchase_order->get('Purchase Order Parent')),
                'supplier_part_key' => (integer)$data['Supplier Part Key'],
                'supplier_key'      => (integer)$data['Supplier Key'],
                'checkbox'          => sprintf('<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),
                'reference'         => $data['Supplier Part Reference'],


                'reference' => sprintf(
                    '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" >%s</span>  ', $data['Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']
                ),

                'packing' => $packing,

                'packed_in'        => $data['Part Units Per Package'],
                'units_per_carton' => $units_per_carton,
                'sko_per_carton'   => $skos_per_carton,

                'description'       => $description,
                'quantity'          => $quantity,
                'delivery_quantity' => $delivery_quantity,
                'subtotals'         => $subtotals,
                // 'ordered'           => number($data['Purchase Order Quantity']),
                'supplier'          => sprintf('<span class="link" onclick="change_view(\'supplier/%d\')">%s</span>', $data['Supplier Key'], $data['Supplier Code']),
                'unit_cost'         => money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')),
                //'qty_units'         => number($units_per_carton * $data['Purchase Order Quantity']),
                //'qty_cartons'       => number($data['Purchase Order Quantity']),
                //'amount'            => money($data['Supplier Part Unit Cost'] * $data['Purchase Order Quantity'] * $units_per_carton, $purchase_order->get('Purchase Order Currency Code')),

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

function delivery_items($_data, $db, $user, $account) {

    include_once 'utils/supplier_order_functions.php';

    $rtext_label = 'item';

    //include_once 'class.PurchaseOrder.php';
    //$purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    $supplier_delivery = get_object('Supplier_Delivery', $_data['parameters']['parent_key']);


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            // $quantity = number($data['Supplier Delivery Units']);


            // $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


            $data['units_qty'] = $data['Supplier Delivery Units'];

            $data['account_currency_code'] = $account->get('Account Currency');
            $data['currency_code']         = $supplier_delivery->get('Supplier Delivery Currency Code');
            $data['exchange']              = $supplier_delivery->get('Supplier Delivery Currency Exchange');


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" title="%s" >%s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link" onclick="change_view(\'part/%d\')" title="%s"><i class="far fa-box"></i> %s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link" onclick="change_view(\'part/%d\')" ><i class="far fa-box" title="%s"></i> </span>', $data['Part SKU'], _('Part reference is same as supplier product code')
                );
            }


            $description = '<div style="font-size:90%" >'.($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '');


            $description_units = $description.' '.$data['Supplier Part Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$data['Supplier Part Packages Per Carton'].'</b></span>';


            $data['units_qty'] = $data['Supplier Delivery Units'];
            $items_qty         = get_purchase_order_items_qty($data);


            $amount = money($data['Supplier Delivery Net Amount'], $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $amount .= ' <span class="">('.money($data['Supplier Delivery Net Amount'] * $data['exchange'], $data['account_currency_code']).')</span>';

            }


            if ($data['Purchase Order Submitted Unit Cost'] != $data['Supplier Part Unit Cost']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Unit cost changed').' 
                <span title="'._('Submitted unit cost').'">'.money($data['Purchase Order Submitted Unit Cost'], $data['currency_code']).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current unit cost').'">'.money($data['Supplier Part Unit Cost'], $data['currency_code']).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'cost\','.$data['Purchase Order Transaction Fact Key']
                    .')" class="button fa fa-sync-alt" title="'._('Update item amount to current price').'"></i></div>';
            }


            if ($data['Purchase Order Submitted Unit Extra Cost Percentage'] != $data['Supplier Part Unit Extra Cost Percentage']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Extra cost % changed').' 
                <span title="'._('Submitted extra cost %').'">'.percentage($data['Purchase Order Submitted Unit Extra Cost Percentage'], 1, 1).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current extra cost %').'">'.percentage($data['Supplier Part Unit Extra Cost Percentage'], 1, 1).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'extra_cost\','
                    .$data['Purchase Order Transaction Fact Key'].')" class="button fa fa-sync-alt" title="'._('Update item amount to current extra cost').'"></i></div>';
            }

            $amount = '<span class="po_amount_'.$data['Purchase Order Transaction Fact Key'].'">'.$amount.'</span>';


            if ($data['Part Package Weight'] > 0) {
                $weight = weight(
                    $data['Part Package Weight'] * $data['Supplier Delivery Units'] / $data['Part Units Per Package']
                );
            } else {
                $weight = '<i class="error fa fa-exclamation-circle"></i>';
            }
            if ($data['Supplier Part Carton CBM'] > 0) {
                $cbm = number(
                        $data['Supplier Delivery Units'] * $data['Supplier Part Carton CBM'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']
                    ).' m³';
            } else {
                $cbm = '<i class="error fa fa-exclamation-circle"></i>';

            }


            $table_data[] = array(

                'id' => (integer)$data['Purchase Order Transaction Fact Key'],
                //'supplier_part_key' => (integer)$data['Supplier Part Key'],
                //'checkbox'          => sprintf('<i key="%d" class="far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),

                //'operations' => sprintf(
                //    '<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']
                //),

                'reference'   => $reference,
                'description' => $description_units,
                'items_qty'   => $items_qty,


                'amount' => $amount,
                'weight' => $weight,
                'cbm'    => $cbm,

                //'quantity'  => $delivery_quantity,
                //'items_qty' => $subtotals,
                //'ordered'   => number($data['Purchase Order Quantity']),
                // 'qty'       => number($quantity)

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

function delivery_items_mismatch($_data, $db, $user, $account) {

    include_once 'utils/supplier_order_functions.php';

    $rtext_label = 'item';

    //include_once 'class.PurchaseOrder.php';
    //$purchase_order = new PurchaseOrder($_data['parameters']['parent_key']);

    $supplier_delivery = get_object('Supplier_Delivery', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            // $quantity = number($data['Supplier Delivery Units']);


            // $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


            $data['units_qty'] = $data['Supplier Delivery Units'];

            $data['account_currency_code'] = $account->get('Account Currency');
            $data['currency_code']         = $supplier_delivery->get('Supplier Delivery Currency Code');
            $data['exchange']              = $supplier_delivery->get('Supplier Delivery Currency Exchange');


            //  $subtotals = get_purchase_order_subtotals($data);
            /*

                        $delivery_quantity = sprintf(
                            '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                            $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $quantity, $quantity
                        );

            */
            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" title="%s" >%s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link" onclick="change_view(\'part/%d\')" title="%s"><i class="far fa-box"></i> %s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link" onclick="change_view(\'part/%d\')" ><i class="far fa-box" title="%s"></i> </span>', $data['Part SKU'], _('Part reference is same as supplier product code')
                );
            }


            $description_skos = $data['Part Package Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$data['Supplier Part Packages Per Carton'].'</b></span>';


            $data['units_qty'] = $data['Supplier Delivery Units'];

            $items_qty = get_purchase_order_items_qty($data);

            $data['units_qty'] = $data['Supplier Delivery Checked Units'];

            $checked_qty = get_purchase_order_items_qty($data);

            $diff_units = $data['Supplier Delivery Checked Units'] - $data['Supplier Delivery Units'];


            $table_data[] = array(

                'id'          => (integer)$data['Purchase Order Transaction Fact Key'],
                //'supplier_part_key' => (integer)$data['Supplier Part Key'],
                //'checkbox'          => sprintf('<i key="%d" class="far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),

                //'operations' => sprintf(
                //    '<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']
                //),
                'type'        => ($diff_units > 0 ? '<i class="far fa-box-open"></i>' : '<i class="fa fa-box-full"></i>'),
                'reference'   => $reference,
                'description' => $description_skos,
                'items_qty'   => $items_qty,
                'checked_qty' => $checked_qty,
                'diff'        => delta($data['Supplier Delivery Checked Units'], $data['Supplier Delivery Units']),
                'diff_units'  => ($diff_units > 0 ? '+' : '').$diff_units,
                'diff_skos'   => ($diff_units > 0 ? '+' : '').($diff_units) / $data['Part Units Per Package'],

                // 'amount' => $amount,
                // 'weight' => $weight,
                // 'cbm'    => $cbm,

                //'quantity'  => $delivery_quantity,
                //'items_qty' => $subtotals,
                //'ordered'   => number($data['Purchase Order Quantity']),
                // 'qty'       => number($quantity)

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

function delivery_checking_items($_data, $db, $user, $account) {


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


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


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


            $description = '<div style="font-size:90%" >'.($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '');

            $description = $description.'<span class="">'.$data['Part Units Per Package'].'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<br/> 
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$data['Part Units Per Package'].'</b></span>';


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
                <span data-settings=\'{"field": "Supplier Delivery Checked Units", "sko_factor":%d, "transaction_key":%d,"item_key":%d, "item_historic_key":%d ,"on":1 }\' class="checked_quantity %s"  >
                    <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                    <input   class="checked_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
                    <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button %s" aria-hidden="true">
                </span>', ($supplier_delivery->get('Supplier Delivery State') == 'Placed' ? '' : 'hide'), number($sko_checked_quantity), $data['Part Units Per Package'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'],
                $data['Supplier Part Historic Key'], ($supplier_delivery->get('Supplier Delivery State') == 'Placed' ? 'hide' : ''), $sko_checked_quantity, $sko_checked_quantity, ''
            );


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
                '<span  id="part_sko_item_%d"  data-barcode_settings=\'{"reference":"%s","description":"%s" ,"image_src":"%s" ,"qty":"%s" ,"cartons":"%s"  , "skos":"%s"  ,"units":"%s"   }\'  _checked="%s"   barcode="%s" data-metadata=\'{"qty":%d}\' onClick="copy_qty(this)" class="button part_sko_item"  >%s</span>',
                $data['Part SKU'], $data['Part Reference'], base64_encode($data['Part Package Description']), $data['Part SKO Image Key'], $data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units'], number($data['Supplier Delivery Units']),
                number($data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units']), number($data['Supplier Part Packages Per Carton'] * $data['Part Units Per Package'] * $data['Supplier Delivery Units']), $data['Supplier Delivery Checked Units'],


                $data['Part SKO Barcode'], $data['Supplier Delivery Units'] / $data['Part Units Per Package'], get_purchase_order_items_qty($data)

            );


            $table_data[] = array(

                'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
                'supplier_part_key' => (integer)$data['Supplier Part Key'],
                'part_sku'          => (integer)$data['Part SKU'],
                'checkbox'          => sprintf(
                    '<i key="%d" class="far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']
                ),

                'operations' => sprintf(
                    '<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']
                ),


                'reference' => sprintf(
                    '<span class="link" onclick="change_view(\'/%s/%d/part/%d\')" >%s</span>  ', strtolower($supplier_delivery->get('Supplier Delivery Parent')), $supplier_delivery->get('Supplier Delivery Parent Key'), $data['Supplier Part Key'],
                    $data['Supplier Part Reference']
                ),


                //  'reference'      => $data['Supplier Part Reference'],


                'part_reference' => sprintf('<span class="link" onclick="change_view(\'/part/%d\')" >%s</span>  ', $data['Part SKU'], $data['Part Reference']),


                // 'part_reference' => $data['Part Reference'],
                'description'    => $description,

                'sko_edit_checked_quantity' => $edit_sko_checked_quantity,
                'sko_checked_quantity'      => number($sko_checked_quantity),
                //'placement_notes'=>$placement_notes_field,
                'subtotals'                 => $subtotals,
                // 'ordered'                   => number($data['Purchase Order Quantity']),
                'qty'                       => number($quantity),
                'items_qty'                 => $items_qty,

                /*
                                'c_sko_u'   => sprintf(
                                    '<span  id="part_sko_item_%d"  data-barcode_settings=\'{"reference":"%s","description":"%s" ,"image_src":"%s" ,"qty":"%s" ,"cartons":"%s"  , "skos":"%s"  ,"units":"%s"   }\'  _checked="%s"   barcode="%s" data-metadata=\'{"qty":%d}\' onClick="copy_qty(this)" class="button part_sko_item"  ><span class="very_discreet">%s/</span> <span>%s</span> <span class="super_discreet">/%s</span></span>',

                                    $data['Part SKU'], $data['Part Reference'], base64_encode($data['Part Package Description']), $data['Part SKO Image Key'], $data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units'], number($data['Supplier Delivery Units']),
                                    number($data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units']), number($data['Supplier Part Packages Per Carton'] * $data['Part Units Per Package'] * $data['Supplier Delivery Units']), $data['Supplier Delivery Checked Units'],


                                    $data['Part SKO Barcode'], $data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units'],

                                    number($data['Supplier Delivery Units']), number($data['Supplier Part Packages Per Carton'] * $data['Supplier Delivery Units']), number($data['Supplier Part Packages Per Carton'] * $data['Part Units Per Package'] * $data['Supplier Delivery Units'])
                                ),
                */
                'placement'                 => $placement
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


function order_supplier_parts($_data, $db, $user) {

    $purchase_order = get_object(
        $_data['parameters']['parent'], $_data['parameters']['parent_key']
    );

    $rtext_label = 'supplier part';
    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    print $sql;


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

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

            $description = '<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Supplier Part Description'];


            $table_data[] = array(
                'id'               => (integer)$data['Supplier Part Key'],
                'supplier_key'     => (integer)$data['Supplier Part Supplier Key'],
                'supplier_code'    => $data['Supplier Code'],
                'part_key'         => (integer)$data['Supplier Part Part SKU'],
                'part_reference'   => $data['Part Reference'],
                'reference'        => $data['Supplier Part Reference'],
                'formatted_sku'    => sprintf(
                    "SKU%05d", $data['Supplier Part Part SKU']
                ),
                'part_description' => $description,

                'description' => $data['Supplier Part Description'],
                'status'      => $status,
                'cost'        => money(
                    $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']
                ),
                'packing'     => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'       => number(floor($data['Part Current On Hand Stock']))." $stock_status"
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


function category_all_suppliers($_data, $db, $user, $account) {


    $rtext_label = 'supplier';

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    $table_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['associated']) {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']
                );
            } else {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']
                );
            }


            $table_data[] = array(
                'id'             => (integer)$data['Supplier Key'],
                'operations'     => $associated,
                'code'           => $data['Supplier Code'],
                'name'           => $data['Supplier Name'],
                'supplier_parts' => number($data['Supplier Number Parts']),

                'surplus'      => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                ) > .75
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Surplus Parts'])
                ),
                'optimal'      => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']
                ), number($data['Supplier Number Optimal Parts'])
                ),
                'low'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Low Parts'])
                ),
                'critical'     => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Critical Parts'])
                ),
                'out_of_stock' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Out Of Stock Parts'])
                ),


                'location'   => $data['Supplier Location'],
                'email'      => $data['Supplier Main Plain Email'],
                'telephone'  => $data['Supplier Preferred Contact Number Formatted Number'],
                'contact'    => $data['Supplier Main Contact Name'],
                'company'    => $data['Supplier Company Name'],
                'revenue'    => '<span class="highlight">'.money(
                        $data['revenue'], $account->get('Currency Code')
                    ).'</span>',
                'revenue_1y' => '<span class="highlight" title="'.money(
                        $data['revenue_1y'], $account->get('Currency Code')
                    ).'">'.delta($data['revenue'], $data['revenue_1y']).'</span>',

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


function order_supplier_all_parts($_data, $db, $user, $account) {

    include_once 'utils/supplier_order_functions.php';

    $rtext_label = 'supplier part';

    $purchase_order = get_object('PurchaseOrder', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    //print $sql;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

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


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


            $skos_per_carton = $data['Supplier Part Packages Per Carton'];

            $data['units_qty']             = $data['Purchase Order Ordering Units'];
            $data['account_currency_code'] = $account->get('Account Currency');
            $data['currency_code']         = $purchase_order->get('Purchase Order Currency Code');
            $data['exchange']              = $purchase_order->get('Purchase Order Currency Exchange');

            $subtotals = get_purchase_order_subtotals($data);


            $transaction_key = '';


            $unit_price = $data['Supplier Part Unit Cost'];

            $unit_price_description = '<span class="discreet">'._('Unit cost').':</span> '.money($unit_price, $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $unit_price_description .= ' <span class="">('.money($unit_price * $data['exchange'], $data['account_currency_code']).')</span>';

            }

            $description = '<div style="font-size:90%" >'.($data['Supplier Part Reference'] != $data['Part Reference'] ? $data['Part Reference'].', ' : '');


            $description_units = $description.' '.$data['Supplier Part Description'].'<div>'.$unit_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description .= sprintf(
                    ' <span class="discreet"><span title="%s">Min order</span>:%s<span>', _('Minimum order (units)'), number($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'] * $data['Supplier Part Minimum Carton Order'])
                );
            }


            $sko_price_description = '<span class="discreet">'._('SKO cost').':</span> '.money($unit_price * $data['Part Units Per Package'], $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $sko_price_description .= ' <span class="">('.money($unit_price * $data['Part Units Per Package'] * $data['exchange'], $data['account_currency_code']).')</span>';

            }


            $description_skos = $description.'<span class="">'.$data['Part Units Per Package'].'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<div>'.$sko_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_skos .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }

            $cartons_price_description = '<span class="discreet">'._('Carton cost').':</span> '.money($unit_price * $data['Part Units Per Package'] * $skos_per_carton, $data['currency_code']);

            if ($data['currency_code'] != $data['account_currency_code']) {
                $cartons_price_description .= ' <span class="">('.money($unit_price * $data['Part Units Per Package'] * $skos_per_carton * $data['exchange'], $data['account_currency_code']).')</span>';

            }


            $description_cartons = $description.'<span class="">'.$units_per_carton.'</span><span class="discreet ">x</span> '.$data['Supplier Part Description'].'<div>'.$cartons_price_description.'</div>
             <span class="discreet">'.sprintf(_('Packed in <b>%ds</b>'), $data['Part Units Per Package']).' <span class="" title="'._('SKOs per carton').'">, sko/C: <b>'.$skos_per_carton.'</b></span>';


            if ($data['Supplier Part Minimum Carton Order'] > 0 and $data['Supplier Part Minimum Carton Order'] != 1) {
                $description_cartons .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }


            if ($data['Part Stock Status'] == 'Out_Of_Stock' or $data['Part Stock Status'] == 'Error') {
                $available_forecast = '';
            } else {
                if (in_array(
                    $data['Part Products Web Status'], array(
                                                         'No Products',
                                                         'Offline',
                                                         'Out of Stock'
                                                     )
                )) {
                    $available_forecast = '';

                } elseif ($data['Part On Demand'] == 'Yes') {

                    $available_forecast = '<span >'.sprintf(
                            _('%s in stock'),
                            '<span  title="'.sprintf("%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';

                    if ($data['Part Fresh'] == 'No') {
                        $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    } else {
                        $available_forecast = ' <i class="far fa-lemon padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    }
                } else {
                    $available_forecast = '<span >'.sprintf(
                            _('%s availability'), '<span  title="'.sprintf(
                                                    "%s %s", number($data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($data['Part Days Available Forecast']))
                                                ).'">'.seconds_to_until(
                                                    $data['Part Days Available Forecast'] * 86400
                                                ).'</span>'
                        ).'</span>';


                }
            }

            $average_sales_per_year_units = '&lang;'.number($data['Part 1 Year Acc Dispatched'] * $data['Part Units Per Package']).' u/y&rang;';
            $average_sales_per_year_skos  = '&lang;'.number($data['Part 1 Year Acc Dispatched']).' SKOs/y&rang;';


            $average_sales_per_year_cartons = '&lang;'.number($data['Part 1 Year Acc Dispatched'] / $data['Supplier Part Packages Per Carton']).' C/y&rang;';


            $description_units = $description_units.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (units)').'">'.number($data['Part Current On Hand Stock'] * $data['Part Units Per Package']).'<span class="small">u.</span></span> '
                .$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_units.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched'] * $data['Part Units Per Package']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched'] * $data['Part Units Per Package']).'</div>
			    </div>
			</div>';

            $description_skos = $description_skos.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (SKOs)').'">'.number($data['Part Current On Hand Stock']).'<span class="small">sko.</span></span> '.$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_skos.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched']).'</div>
			    </div>
			</div>';

            $description_cartons = $description_cartons.'<div style="margin-top:10px" >
                       
                       <span style="display: inline-block; min-width: 50px;color:black" class="strong padding_right_10" title="'._('Stock (cartons)').'">'.number($data['Part Current On Hand Stock'] / $data['Supplier Part Packages Per Carton'])
                .'<span class="small discreet">C.</span></span> '.$stock_status.'
                        <span>'.$available_forecast.'</span>
                        <span class="discreet padding_left_10" title="'._('Cartons dispatched by year').'">'.$average_sales_per_year_cartons.'</span>
                    </div>
                    <div class="as_table asset_sales">
                        <div class="as_row header">
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -12 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -9 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -6 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now -3 months')).'</div>
			            <div class="as_cell width_75">'.get_quarter_label(strtotime('now')).'</div>
			        </div>
		            <div class="as_row header">
			            <div class="as_cell width_75">'.number($data['Part 4 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 3 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 2 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			            <div class="as_cell width_75">'.number($data['Part 1 Quarter Ago Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			        <div class="as_cell width_75">'.number($data['Part Quarter To Day Acc Dispatched'] / $data['Supplier Part Packages Per Carton']).'</div>
			    </div>
			</div>';


            $units_qty   = $data['Purchase Order Ordering Units'];
            $skos_qty    = $units_qty / $data['Part Units Per Package'];
            $cartons_qty = $skos_qty / $data['Supplier Part Packages Per Carton'];


            if ($data['Purchase Order Ordering Units'] % ($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton']) != 0 or $data['Purchase Order Ordering Units'] % ($data['Part Units Per Package']) != 0) {
                $class = 'error';
            } else {
                $class = '';
            }


            $quantity_units = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Units", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_units_qty %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($units_qty == 0 ? '' : $units_qty + 0), ($units_qty == 0 ? '' : $units_qty + 0)

            );

            $quantity_skos = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order SKOs", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_skos_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($skos_qty == 0 ? '' : $skos_qty + 0), ($skos_qty == 0 ? '' : $skos_qty + 0)

            );

            $quantity_cartons = sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Cartons", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
                <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input class="order_qty order_cartons_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s"> 
                <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


                $transaction_key, $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $class, ($cartons_qty == 0 ? '' : $cartons_qty + 0), ($cartons_qty == 0 ? '' : $cartons_qty + 0)

            );

            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/supplier/%d/part/%d\')" title="%s" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link" onclick="change_view(\'part/%d\')" title="%s"><i class="far fa-box"></i> %s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link" onclick="change_view(\'part/%d\')" ><i class="far fa-box" title="%s"></i> </span>', $data['Part SKU'], _('Part reference is same as supplier product code')
                );
            }


            if ($data['Part Main Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/image.php?id=%d&s=50x50" style="display: block;  max-width:50px; max-height:50px; width: auto; height: auto;">', $data['Part Main Image Key']
                );
            } else {
                $image = '';
            }


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries = array();
            } else {
                $next_deliveries = json_decode($data['Part Next Deliveries Data'], true);
            }


            $other_deliveries_units   = '';
            $other_deliveries_skos    = '';
            $other_deliveries_cartons = '';


            foreach ($next_deliveries as $next_delivery) {
                if (isset($next_delivery['po_key']) and $next_delivery['po_key'] != $purchase_order->id) {

                    $other_deliveries_units   .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Units ordered').'">+'.number($next_delivery['raw_units_qty'])
                        .'<span style="font-weight: normal" class="small discreet">u.</span></div></div>';
                    $other_deliveries_skos    .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('SKOs ordered').'">+'.number(
                            $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                        ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';
                    $other_deliveries_cartons .= '<div class="as_row "><div class="as_cell" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" title="'._('Cartons ordered').'">+'.number(
                            $next_delivery['raw_units_qty'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']
                        ).'<span style="font-weight: normal" class="small discreet">C.</span></div></div>';


                }
            }


            $other_deliveries_units   = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_units.'</div>';
            $other_deliveries_skos    = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_skos.'</div>';
            $other_deliveries_cartons = '<div border="0" style="font-size: small" class="as_table">'.$other_deliveries_cartons.'</div>';

            $table_data[] = array(
                'id'               => (integer)$data['Supplier Part Key'],
                'supplier_key'     => (integer)$data['Supplier Part Supplier Key'],
                'supplier_code'    => $data['Supplier Code'],
                'part_key'         => (integer)$data['Supplier Part Part SKU'],
                'part_reference'   => $data['Part Reference'],
                'parent_key'       => $purchase_order->get('Purchase Order Parent Key'),
                'parent_type'      => strtolower(
                    $purchase_order->get('Purchase Order Parent')
                ),
                'reference'        => $reference,
                'part_description' => '<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Part Package Description'],


                'status'  => $status,
                'cost'    => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing' => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'   => number(floor($data['Part Current On Hand Stock']))." $stock_status",

                'description_units'        => $description_units,
                'description_skos'         => $description_skos,
                'description_cartons'      => $description_cartons,
                'quantity_units'           => $quantity_units,
                'quantity_skos'            => $quantity_skos,
                'quantity_cartons'         => $quantity_cartons,
                'other_deliveries_units'   => $other_deliveries_units,
                'other_deliveries_skos'    => $other_deliveries_skos,
                'other_deliveries_cartons' => $other_deliveries_cartons,

                'subtotals' => $subtotals,
                'image'     => $image


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


function deleted_order_items($_data, $db, $user) {

    $rtext_label = 'item';

    include_once 'class.PurchaseOrder.php';
    $purchase_order = new PurchaseOrder(
        'deleted', $_data['parameters']['parent_key']
    );

    $table_data = array();
    $total      = 0;

    foreach ($purchase_order->items as $data) {

        $total++;

        $table_data[] = array(
            'id'                         => $total,
            'supplier_part_historic_key' => $data[0],
            'parent_type'                => strtolower($purchase_order->get('Purchase Order Parent')),
            'parent_key'                 => $purchase_order->get(
                'Purchase Order Parent Key'
            ),
            'reference'                  => $data[1],
            'quantity'                   => $data[2]


        );


    }


    $rtext = sprintf(ngettext('%s item', '%s items', $total), number($total));

    $_order = 'code';
    $_dir   = '';


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


function sales_history($_data, $db, $user, $account) {


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
        case 'supplier':
            include_once 'class.Supplier.php';
            $supplier   = new Supplier($_data['parameters']['parent_key']);
            $currency   = $account->get('Account Currency');
            $from       = $supplier->get('Supplier Valid From');
            $to         = ($supplier->get('Part Type') == 'Archived' ? $supplier->get('Supplier Valid To') : gmdate('Y-m-d'));
            $date_field = '`Timeseries Record Date`';
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
                'sales'               => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'purchased_amount'    => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'dispatched'          => '<span class="very_discreet">'.number(0).'</span>',
                'deliveries'          => '<span class="very_discreet">'.number(0).'</span>',
                'supplier_deliveries' => '<span class="very_discreet">'.number(0).'</span>',

                'date' => $date


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    switch ($_data['parameters']['parent']) {

        case 'supplier':
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

            $last_year_data[$_date] = array('_sales' => $data['sales']);


            if (array_key_exists($_date, $record_data)) {


                if (in_array(
                        $_data['parameters']['frequency'], array(
                                                             'annually',
                                                             'quarterly',
                                                             'monthly'
                                                         )
                    ) and $_data['parameters']['parent'] == 'supplier') {
                    $dispatched = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'], number($data['dispatched'])
                    );

                    $deliveries = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'], $data['Timeseries Record Key'],
                        number($data['deliveries'])
                    );

                    $purchased_amount = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Timeseries Record Timeseries Key'], $data['Timeseries Record Key'],
                        money($data['purchased_amount'], $currency)
                    );


                } else {
                    $dispatched       = number($data['dispatched']);
                    $deliveries       = number($data['deliveries']);
                    $purchased_amount = money($data['purchased_amount'], $currency);


                }


                $record_data[$_date] = array(
                    'sales'            => money($data['sales'], $currency),
                    'purchased_amount' => $purchased_amount,

                    'deliveries'          => $deliveries,
                    'supplier_deliveries' => number($data['supplier_deliveries']),
                    'dispatched'          => $dispatched,
                    'date'                => $record_data[$_date]['date']


                );
            }


            if (isset($last_year_data[$_date_last_year])) {
                $record_data[$_date]['delta_sales_1yb'] =
                    '<span class="" title="'.money($last_year_data[$_date_last_year]['_sales'], $currency).'">'.delta($data['sales'], $last_year_data[$_date_last_year]['_sales']).' '.delta_icon($data['sales'], $last_year_data[$_date_last_year]['_sales']).'</span>';
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


function part_locations_with_errors($_data, $db, $user) {


    $rtext_label = 'part location with errors';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    //print $sql;


    foreach ($db->query($sql) as $data) {


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

        $table_data[] = array(
            // 'id'=>(integer) $data['Part SKU'],
            'reference' => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),

            'description' => $data['Part Package Description'],

            'can_pick'     => ($data['Can Pick'] == 'Yes' ? _('Yes') : _('No')),
            'quantity'     => '<span class="error">'.number($data['Quantity On Hand']),
            '</span>',
            'stock_status' => $stock_status

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


function parts_by_stock_status($stock_status, $_data, $db, $user) {

    $_stock_status = $stock_status;


    switch ($stock_status) {
        case 'Surplus':
            $rtext_label = 'part with excess stock';
            break;
        case 'Todo':
            $rtext_label = 'part with critical stock or out of stock';
            break;
        default:
            $rtext_label = 'part';

    }


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql_totals;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

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


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


            $transaction_key = '';


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
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );


            $next_deliveries = '';


            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);
                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {

                        if ($delivery['type'] == 'delivery') {
                            $next_deliveries .= sprintf(
                                ', <span class="link" onclick="change_view(\'%s\')"><i class="fa fa-industry" aria-hidden="true"></i> %s</span> <b>(%s)</b>', strtolower($delivery['link']), $delivery['order_id'], number($delivery['qty'])
                            );
                        } else {
                            $next_deliveries .= sprintf(
                                ', <span class="link" onclick="change_view(\'%s\')"><i class="fa fa-clipboard" aria-hidden="true"></i> %s</span> %s <b>(%s)</b>', $delivery['link'], $delivery['order_id'],
                                ($delivery['state'] != 'InProcess' ? '<i class="fa fa-paper-plane" aria-hidden="true"></i>' : ''), number($delivery['qty'])
                            );

                        }

                    }
                }


            }


            /*
                            $next_deliveries.= '||';

                            if ($data['in_deliveries'] != '') {
                                foreach (preg_split('/\,/', $data['in_deliveries']) as $delivery) {
                                    $delivery        = preg_split('/\|/', $delivery);
                                    $next_deliveries .= sprintf(
                                        ', <span class="link" onclick="change_view(\'%s/%d/delivery/%d\')"><i class="fa fa-industry" aria-hidden="true"></i> %s</span> <b>(%s)</b>', strtolower($delivery[0]), $delivery[1], $delivery[2], $delivery[3], number($delivery[4])
                                    );
                                }
                            }

                            if ($data['in_purchase_orders'] != '') {
                                foreach (preg_split('/\,/', $data['in_purchase_orders']) as $purchase_order) {
                                    $purchase_order  = preg_split('/\|/', $purchase_order);
                                    $next_deliveries .= sprintf(
                                        ', <span class="link" onclick="change_view(\'suppliers/order/%d\')"><i class="fa fa-clipboard" aria-hidden="true"></i> %s</span> %s <b>(%s)</b>', $purchase_order[0], $purchase_order[1],
                                        ($purchase_order[3] != 'InProcess' ? '<i class="fa fa-paper-plane" aria-hidden="true"></i>' : ''), number($purchase_order[2])
                                    );
                                }
                            }



            */
            $next_deliveries = preg_replace('/^, /', '', $next_deliveries);

            $table_data[] = array(
                'id'        => (integer)$data['Supplier Part Key'],
                // 'supplier_key'   => (integer)$data['Supplier Part Supplier Key'],
                //'part_key'       => (integer)$data['Supplier Part Part SKU'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Supplier Part Part SKU'], $data['Part Reference']),
                //'reference'      => $data['Supplier Part Reference'],
                //'formatted_sku'  => sprintf("SKU%05d", $data['Supplier Part Part SKU']),

                'description'         => $description,
                'simple_description'  => $data['Part Package Description'],
                'status'              => $status,
                'cost'                => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing'             => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'               => number(
                        floor($data['Part Current On Hand Stock'])
                    )." $stock_status",
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


function todo_paid_parts($_data, $db, $user) {


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


            $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


            $transaction_key = '';

            $description = $data['Supplier Part Description'].' <span class="discreet">('.number($units_per_carton).'/C '.money(
                    $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']
                ).')</span>';

            if ($data['Supplier Part Minimum Carton Order'] > 0) {
                $description .= sprintf(
                    ' <span class="discreet"><span title="%s">MOQ</span>:%s<span>', _('Minimum order (cartons)'), number($data['Supplier Part Minimum Carton Order'])
                );
            }

            $next_deliveries = '';

            if ($data['Part Next Deliveries Data'] != '') {
                $next_deliveries_data = json_decode($data['Part Next Deliveries Data'], true);
                if (count($next_deliveries_data) > 0) {
                    foreach ($next_deliveries_data as $delivery) {

                        if ($delivery['type'] == 'delivery') {
                            $next_deliveries .= sprintf(
                                ', <span class="link" onclick="change_view(\'%s\')"><i class="fa fa-industry" aria-hidden="true"></i> %s</span> <b>(%s)</b>', strtolower($delivery['link']), $delivery['order_id'], number($delivery['qty'])
                            );
                        } else {
                            $next_deliveries .= sprintf(
                                ', <span class="link" onclick="change_view(\'%s\')"><i class="fa fa-clipboard" aria-hidden="true"></i> %s</span> %s <b>(%s)</b>', $delivery['link'], $delivery['order_id'],
                                ($delivery['state'] != 'InProcess' ? '<i class="fa fa-paper-plane" aria-hidden="true"></i>' : ''), number($delivery['qty'])
                            );

                        }

                    }
                }


            }


            /*
            if ($data['in_deliveries'] != '') {
                foreach (preg_split('/\,/', $data['in_deliveries']) as $delivery) {
                    $delivery        = preg_split('/\|/', $delivery);
                    $next_deliveries .= sprintf(
                        ', <span class="link" onclick="change_view(\'%s/%d/delivery/%d\')"><i class="fa fa-industry" aria-hidden="true"></i> %s</span> <b>(%s)</b>', strtolower($delivery[0]), $delivery[1], $delivery[2], $delivery[3], number($delivery[4])
                    );
                }
            }

            if ($data['in_purchase_orders'] != '') {
                foreach (preg_split('/\,/', $data['in_purchase_orders']) as $purchase_order) {
                    $purchase_order  = preg_split('/\|/', $purchase_order);
                    $next_deliveries .= sprintf(
                        ', <span class="link" onclick="change_view(\'suppliers/order/%d\')"><i class="fa fa-clipboard" aria-hidden="true"></i> %s</span> %s <b>(%s)</b>', $purchase_order[0], $purchase_order[1],
                        ($purchase_order[3] != 'InProcess' ? '<i class="fa fa-paper-plane" aria-hidden="true"></i>' : ''), number($purchase_order[2])
                    );
                }
            }
            */

            $next_deliveries = preg_replace('/^, /', '', $next_deliveries);


            $table_data[] = array(
                'id'              => (integer)$data['Supplier Part Key'],
                'supplier_key'    => (integer)$data['Supplier Part Supplier Key'],


                //  'reference' => $data['Supplier Part Reference'],
                'reference'       => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Supplier Part Part SKU'], $data['Part Reference']),

                // 'description' => $description,
                'description'     => $data['Part Package Description'],
                'cost'            => money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
                'packing'         => '<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package']
                    .'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discreet">'
                    .($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span>'),
                'stock'           => number(floor($data['Part Current On Hand Stock']))." $stock_status",
                //'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['date'].' +0:00')),
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


function supplier_categories($_data, $db, $user) {

    global $account;

    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();


    foreach ($db->query($sql) as $data) {

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


        $table_data[] = array(
            'id'                  => (integer)$data['Category Key'],
            // 'position'=>$data['Category Position'],
            'store_key'           => (integer)$data['Category Store Key'],
            'code'                => sprintf(
                '<span class="link" onClick="change_view(\'category/%d\')">%s</span>', $data['Category Key'], $data['Category Code']
            ),
            'label'               => $data['Category Label'],
            'subjects'            => number($data['Category Number Subjects']),
            'level'               => $level,
            'subcategories'       => number($data['Category Children']),
            'percentage_assigned' => percentage(
                $data['Category Number Subjects'], ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
            ),

            'active_supplier_parts' => number(
                $data['Supplier Category Number Active Parts']
            ),
            'surplus'               => sprintf(
                '<span class="%s" title="%s">%s</span>', (ratio(
                $data['Supplier Category Number Surplus Parts'], $data['Supplier Category Number Parts']
            ) > .75
                ? 'error'
                : (ratio(
                    $data['Supplier Category Number Surplus Parts'], $data['Supplier Category Number Parts']
                ) > .5 ? 'warning' : '')), percentage(
                    $data['Supplier Category Number Surplus Parts'], $data['Supplier Category Number Parts']
                ), number($data['Supplier Category Number Surplus Parts'])
            ),
            'optimal'               => sprintf(
                '<span  title="%s">%s</span>', percentage(
                $data['Supplier Category Number Optimal Parts'], $data['Supplier Category Number Parts']
            ), number($data['Supplier Category Number Optimal Parts'])
            ),
            'low'                   => sprintf(
                '<span class="%s" title="%s">%s</span>', (ratio(
                $data['Supplier Category Number Low Parts'], $data['Supplier Category Number Parts']
            ) > .5
                ? 'error'
                : (ratio(
                    $data['Supplier Category Number Low Parts'], $data['Supplier Category Number Parts']
                ) > .25 ? 'warning' : '')), percentage(
                    $data['Supplier Category Number Low Parts'], $data['Supplier Category Number Parts']
                ), number($data['Supplier Category Number Low Parts'])
            ),
            'critical'              => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Supplier Category Number Critical Parts'] == 0
                ? ''
                : (ratio(
                    $data['Supplier Category Number Critical Parts'], $data['Supplier Category Number Parts']
                ) > .25 ? 'error' : 'warning')), percentage(
                    $data['Supplier Category Number Critical Parts'], $data['Supplier Category Number Parts']
                ), number($data['Supplier Category Number Critical Parts'])
            ),
            'out_of_stock'          => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Supplier Category Number Out Of Stock Parts'] == 0
                ? ''
                : (ratio(
                    $data['Supplier Category Number Out Of Stock Parts'], $data['Supplier Category Number Parts']
                ) > .10 ? 'error' : 'warning')), percentage(
                    $data['Supplier Category Number Out Of Stock Parts'], $data['Supplier Category Number Parts']
                ), number($data['Supplier Category Number Out Of Stock Parts'])
            ),


            'sales'    => '<span class="highlight">'.money(
                    $data['sales'], $account->get('Currency Code')
                ).'</span>',
            'sales_1y' => '<span class="highlight" title="'.money(
                    $data['sales_1y'], $account->get('Currency Code')
                ).'">'.delta($data['sales'], $data['sales_1y']).'</span>',

            'sales_year0' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category Year To Day Acc Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category Year To Day Acc Invoiced Amount"], $data["Supplier Category Year To Day Acc 1YB Invoiced Amount"]
                )
            ),
            'sales_year1' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 1 Year Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 1 Year Ago Invoiced Amount"], $data["Supplier Category 2 Year Ago Invoiced Amount"]
                )
            ),
            'sales_year2' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 2 Year Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 2 Year Ago Invoiced Amount"], $data["Supplier Category 3 Year Ago Invoiced Amount"]
                )
            ),
            'sales_year3' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 3 Year Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 3 Year Ago Invoiced Amount"], $data["Supplier Category 4 Year Ago Invoiced Amount"]
                )
            ),
            'sales_year4' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 4 Year Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 4 Year Ago Invoiced Amount"], $data["Supplier Category 5 Year Ago Invoiced Amount"]
                )
            ),

            'sales_quarter0' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category Quarter To Day Acc Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category Quarter To Day Acc Invoiced Amount"], $data["Supplier Category Quarter To Day Acc 1YB Invoiced Amount"]
                )
            ),
            'sales_quarter1' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 1 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 1 Quarter Ago Invoiced Amount"], $data["Supplier Category 1 Quarter Ago 1YB Invoiced Amount"]
                )
            ),
            'sales_quarter2' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 2 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 2 Quarter Ago Invoiced Amount"], $data["Supplier Category 2 Quarter Ago 1YB Invoiced Amount"]
                )
            ),
            'sales_quarter3' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 3 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 3 Quarter Ago Invoiced Amount"], $data["Supplier Category 3 Quarter Ago 1YB Invoiced Amount"]
                )
            ),
            'sales_quarter4' => sprintf(
                '<span>%s</span> %s', money(
                $data['Supplier Category 4 Quarter Ago Invoiced Amount'], $account->get('Account Currency')
            ), delta_icon(
                    $data["Supplier Category 4 Quarter Ago Invoiced Amount"], $data["Supplier Category 4 Quarter Ago 1YB Invoiced Amount"]
                )
            ),


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


function agent_parts($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';

    if ($user->get('User Type') != 'Agent') {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'product';
    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


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


            if ($data['Part Status'] == 'Not In Use' or $data['Part Status'] == 'Discontinuing') {
                $part_status = '<i class="fa fa-square fa-fw  very_discreet" title="'._("No longer required").'" aria-hidden="true"></i> ';
                $required    = false;
            } else {
                $part_status = '<i class="fa fa-shopping-basket fa-fw " aria-hidden="true"></i> ';
                $required    = true;

            }


            $record_data[] = array(
                'id'            => (integer)$data['Supplier Part Key'],
                'supplier_key'  => (integer)$data['Supplier Part Supplier Key'],
                'supplier_code' => sprintf('<span class="link " onCLick="change_view(\'/supplier/%d\')">%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Code']),
                'reference'     => sprintf('<span class="link %s " onCLick="change_view(\'/supplier/%d/part/%d\')">%s</span>', ($required ? '' : 'strikethrough'), $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']),


                'description' => $data['Supplier Part Description'],
                'status'      => $status,
                'part_status' => $part_status,

                'cost' => sprintf(
                    '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>', $data['Supplier Part Key'], $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'],
                    money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code'])
                ),

                'packing' => '
				 <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:70px;text-align:center;"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div>
				<div style="float:right;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),


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


function part_locations_to_replenish_picking_location($_data, $db, $user) {


    $rtext_label = 'picking locations needed to replenish for ordered parts';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    //print $sql;


    foreach ($db->query($sql) as $data) {

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

        $table_data[] = array(
            'reference' => sprintf('<span class="link"  title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
            'location'  => sprintf('<span  class="link"  onclick="change_view(\'locations/%d/%d\')">%s</span>', $data['Part Location Warehouse Key'], $data['Location Key'], $data['Location Code']),


            'quantity_in_picking' => number(floor($data['Quantity On Hand'])),
            'to_pick'             => number(ceil($data['to_pick'])),

            'total_stock'       => number(floor($data['Part Current On Hand Stock'])),
            'storing_locations' => $data['storing_locations'],
            'stock_status'      => $stock_status


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



function timeseries_drill_down_parts($_data, $db, $user, $account) {


    $rtext_label = 'part';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction limit $start_from,$number_results";

    // print $sql;

    $currency = $account->get('Currency Code');

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $diff = $data['Timeseries Record Drill Down Float A'] - $data['Timeseries Record Drill Down Float C'];


            $table_data[] = array(
                'id'                     => (integer)$data['Part SKU'],
                'reference'              => sprintf('<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
                'description'            => $data['Part Package Description'],
                'dispatched'             => number($data['Timeseries Record Drill Down Integer A']),
                'deliveries'             => number($data['Timeseries Record Drill Down Integer B']),
                'sales'                  => money($data['Timeseries Record Drill Down Float A'], $currency),
                'delta_sales_percentage' => delta_icon($data['Timeseries Record Drill Down Float A'], $data['Timeseries Record Drill Down Float C']).' '.percentage($diff, $data['Timeseries Record Drill Down Float C']),
                'delta_sales'            => '<span class="discreet '.($diff > 0 ? '' : ($diff < 0 ? 'error' : '')).'">'.($diff > 0 ? '+' : '').money($diff, $currency).'</span>',


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


function timeseries_drill_down_families($_data, $db, $user, $account) {


    $rtext_label = 'family';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction limit $start_from,$number_results";

    // print $sql;

    $currency = $account->get('Currency Code');

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $diff = $data['Timeseries Record Drill Down Float A'] - $data['Timeseries Record Drill Down Float C'];

            $table_data[] = array(
                'id'                     => (integer)$data['Category Key'],
                'code'                   => sprintf('<span class="link" onclick="change_view(\'category/%d\')">%s</span>', $data['Category Key'], $data['Category Code']),
                'label'                  => $data['Category Label'],
                'dispatched'             => number($data['Timeseries Record Drill Down Integer A']),
                'deliveries'             => number($data['Timeseries Record Drill Down Integer B']),
                'sales'                  => money($data['Timeseries Record Drill Down Float A'], $currency),
                'delta_sales_percentage' => delta_icon($data['Timeseries Record Drill Down Float A'], $data['Timeseries Record Drill Down Float C']).' '.percentage($diff, $data['Timeseries Record Drill Down Float C']),
                'delta_sales'            => '<span class="discreet '.($diff > 0 ? '' : ($diff < 0 ? 'error' : '')).'">'.($diff > 0 ? '+' : '').money($diff, $currency).'</span>',


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


function delivery_costing($_data, $db, $user) {


    $rtext_label = 'part';

    $account = get_object('Account', 1);


    $supplier_delivery = get_object('SupplierDelivery', $_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref $group_by  order by $order $order_direction  limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $items_amount = sprintf(
                '<input id="items_amount_%d" data-sku="%d" class="items_amount width_100" value="%s" ovalue="%s">',

                $data['Part SKU'], $data['Part SKU'],

                format_money_paid($data['items_amount']), $data['items_amount']
            );

            $extra_amount = sprintf(
                '<input class="extra_amount extra_amount_input  width_100" id="extra_amount_%d" data-sku="%d" value="%s" ovalue="%s"/>',

                $data['Part SKU'], $data['Part SKU'],

                format_money_paid($data['extra_amount']), $data['extra_amount']
            );

            $extra_amount_account_currency = sprintf(
                '<input  id="extra_amount_account_currency_%d" data-sku="%d" class="extra_amount_account_currency width_100" value="%s" ovalue="%s"/>', $data['Part SKU'], $data['Part SKU'],

                format_money_paid($data['extra_amount_account_currency']), $data['extra_amount_account_currency']
            );

            if ($data['skos_in'] != 0) {
                $sko_cost = sprintf('<span id="sko_cost_%d"  class="sko_cost" >%s/sko</span>', $data['Part SKU'], money($data['paid_amount'] / $data['skos_in'], $account->get('Currency Code')));
            } else {
                $sko_cost = sprintf('<span id="sko_cost_%d"  class="sko_cost" ></span>', $data['Part SKU']);
            }

            $total_paid = sprintf(
                '<span id="total_paid_%d" class="total_paid_amount %s" data-sku="%d" data-sko_in="%f" data-exchange="%f" data-items_amount="%f" data-extra_amount="%f" data-extra_amount_account_currency="%f"  data-changed=false >%s</span>',

                $data['Part SKU'], ($data['skos_in'] == 0 ? 'zero' : ''), $data['Part SKU'], $data['skos_in'], 1 / $supplier_delivery->get('Supplier Delivery Currency Exchange'), $data['items_amount'], $data['extra_amount'], $data['extra_amount_account_currency'],

                money($data['paid_amount'], $account->get('Currency Code'))
            );

            $reference = sprintf(
                '<span class="link" onclick="change_view(\'/part/%d\')" title="%s" >%s</span>', $data['Part SKU'], _('Part reference'), $data['Part Reference']
            );
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link"  onclick="change_view(\'/supplier/%d/part/%d\')"  title="%s"><i class="fa fa-square"></i> %s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link"  onclick="change_view(\'/supplier/%d/part/%d\')" ><i class="fa fa-square" title="%s"></i> </span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Part reference is same as supplier product code')
                );
            }

            $table_data[] = array(

                'id'             => (integer)$data['Part SKU'],


                //  'reference'      => $reference,
                'part_reference' => $reference,
                'description'    => $data['Part Package Description'],

                'received_quantity'             => number($data['skos_in']),
                'items_amount'                  => $items_amount,
                'extra_amount'                  => $extra_amount,
                'extra_amount_account_currency' => $extra_amount_account_currency,

                'ordered'               => number($data['Purchase Order Submitted Units']),
                //'qty'       => number($quantity),
                'paid_account_currency' => money($data['Supplier Delivery Net Amount'] * $supplier_delivery->get('Supplier Delivery Currency Exchange'), $account->get('Currency Code')),

                'total_paid' => $total_paid,
                'sko_cost'   => $sko_cost
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


function delivery_items_done($_data, $db, $user) {


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
            if ($data['Part Reference'] != $data['Supplier Part Reference']) {
                $reference .= sprintf(
                    '<br ><span class="small link"  onclick="change_view(\'/supplier/%d/part/%d\')"  title="%s"><i class="fa fa-square"></i> %s</span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Supplier product code'), $data['Supplier Part Reference']
                );
            } else {
                $reference .= sprintf(
                    ', <span class="small link"  onclick="change_view(\'/supplier/%d/part/%d\')" ><i class="fa fa-square" title="%s"></i> </span>', $data['Supplier Key'], $data['Supplier Part Key'], _('Part reference is same as supplier product code')
                );
            }


            $table_data[] = array(

                'id'                => (integer)$data['Part SKU'],
                'supplier_part_key' => (integer)$data['Supplier Part Key'],


                'part_reference' => $reference,
                'description'    => $data['Part Package Description'],

                'received_quantity'             => number($data['skos_in']),
                'items_amount'                  => $items_amount,
                'extra_amount'                  => $extra_amount,
                'extra_amount_account_currency' => $extra_amount_account_currency,

                // 'ordered'               => number($data['Purchase Order Quantity']),
                //'qty'       => number($quantity),
                'paid_account_currency'         => money($data['Supplier Delivery Net Amount'] * $supplier_delivery->get('Supplier Delivery Currency Exchange'), $account->get('Currency Code')),

                'total_paid' => $total_paid,
                'sko_cost'   => $sko_cost
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

function format_money_paid($number) {
    $str = number_format($number, 4, '.', '');

    return preg_replace('/(?<=\d{2})0+$/', '', $str);
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
                'SELECT count(*) AS num FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=%d AND `Agent Supplier Supplier Key`=%d ', $user->get('User Parent Key'), $_data['parameters']['parent_key']
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


            $next_deliveries = '<div border="0" style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


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
				 <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span></div>
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

