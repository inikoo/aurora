<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2015 15:34:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/order_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/object_functions.php';

if (!$user->can_view('orders')) {
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
    case 'orders_in_process_not_paid':
        orders_in_process_not_paid(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_process_paid':
        orders_in_process_paid(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_process':
        orders_in_process(get_table_parameters(), $db, $user);
        break;

    case 'orders_in_warehouse':
        orders_in_warehouse(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_warehouse_no_alerts':
        orders_in_warehouse_no_alerts(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_warehouse_with_alerts':
        orders_in_warehouse_with_alerts(get_table_parameters(), $db, $user);
        break;
    case 'orders_packed_done':
        orders_packed_done(get_table_parameters(), $db, $user);
        break;
    case 'orders_approved':
        orders_approved(get_table_parameters(), $db, $user);
        break;
    case 'orders_dispatched_today':
        orders_dispatched_today(get_table_parameters(), $db, $user);
        break;

    case 'archived_orders':
        archived_orders(get_table_parameters(), $db, $user);
        break;


    case 'orders_server':
        orders_server(get_table_parameters(), $db, $user);
        break;
    case 'orders':
        orders(get_table_parameters(), $db, $user);
        break;

    case 'invoices':
        invoices(get_table_parameters(), $db, $user);
        break;
    case 'delivery_notes':
        delivery_notes(get_table_parameters(), $db, $user);
        break;
    case 'pending_delivery_notes':
        pending_delivery_notes(get_table_parameters(), $db, $user);
        break;
    case 'orders_index':
        orders_index(get_table_parameters(), $db, $user);
        break;
    case 'orders_group_by_store':
        orders_group_by_store(get_table_parameters(), $db, $user);
        break;
    case 'delivery_notes_group_by_store':
        delivery_notes_group_by_store(get_table_parameters(), $db, $user);
        break;
    case 'invoice_categories':
        invoice_categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'order.items':
        order_items(get_table_parameters(), $db, $user);
        break;
    case 'order.all_products':
        order_all_products(get_table_parameters(), $db, $user);
        break;
    case 'refund.new.items':
        refund_new_items(get_table_parameters(), $db, $user);
        break;
    case 'replacement.new.items':
        replacement_new_items(get_table_parameters(), $db, $user);
        break;
    case 'return.new.items':
        return_new_items(get_table_parameters(), $db, $user);
        break;
    case 'refund.items':
        refund_items(get_table_parameters(), $db, $user);
        break;
    case 'replacement.new.items':
        replacment_items(get_table_parameters(), $db, $user);
        break;
    case 'invoice.items':
        invoice_items(get_table_parameters(), $db, $user);
        break;
    case 'refund.items':
        refund_items(get_table_parameters(), $db, $user);
        break;
    case 'delivery_note_cancelled.items':
        delivery_note_cancelled_items(get_table_parameters(), $db, $user);
        break;
    case 'delivery_note.items':
        delivery_note_items(get_table_parameters(), $db, $user);
        break;
    case 'delivery_note.fast_track_packing':
        delivery_note_fast_track_packing(get_table_parameters(), $db, $user);
        break;

    case 'orders_in_website':
        orders_in_website(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_website_mailshots':
        orders_in_website_mailshots(get_table_parameters(), $db, $user);
        break;
    case 'order_sent_emails':
        order_sent_emails(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_website_purges':
        orders_in_website_purges(get_table_parameters(), $db, $user);
        break;
    case 'purged_orders':
        purged_orders(get_table_parameters(), $db, $user);
        break;
    case 'invoices_group_by_customer':
        invoices_group_by_customer(get_table_parameters(), $db, $user);
        break;
    case 'order.deals':
        order_deals(get_table_parameters(), $db, $user);
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


function orders_in_process_not_paid($_data, $db, $user) {
    $rtext_label = 'order submitted not paid';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        $payment_state = get_order_formatted_payment_state($data);
        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';
        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        $operations .= sprintf(
            "<i id=\"send_to_warehouse_button_%d\" class=\"%s fa fa-hand-lizard fa-flip-horizontal button edit \" onClick=\"create_delivery_note_from_list(this,%d)\" title='%s'></i>", $data['Order Key'], ($data['Order Number Items'] == 0 ? 'disabled' : ''),
            $data['Order Key'], _('Send for picking')
        );

        //$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$data['Order Key'],_('Edit Order'));

        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'        => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key' => (integer)$data['Order Store Key'],
            'public_id' => sprintf(
                '<span class="link"  onclick="change_view(\'/orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'  => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),

            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_in_process_paid($_data, $db, $user) {
    $rtext_label = 'order submitted paid';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        $payment_state = get_order_formatted_payment_state($data);


        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';
        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        $operations .= sprintf(
            "<i id=\"send_to_warehouse_button_%d\" class=\"%s fa fa-hand-lizard fa-flip-horizontal button edit \" onClick=\"create_delivery_note_from_list(this,%d)\" title='%s'></i>", $data['Order Key'], ($data['Order Number Items'] == 0 ? 'disabled' : ''),
            $data['Order Key'], _('Send for picking')
        );

        //$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$data['Order Key'],_('Edit Order'));

        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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

function orders_in_warehouse($_data, $db, $user) {
    $rtext_label = 'order warehouse';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        $payment_state = get_order_formatted_payment_state($data);

        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_in_warehouse_no_alerts($_data, $db, $user) {
    $rtext_label = 'order warehouse';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error hide  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal padding_right_10  button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        if ($data['Order Replacement State'] == 'InWarehouse') {
            $payment_state = '';
            $total_amount  = '';
            $deliveries    = '';

            if ($data['delivery_notes'] != '') {

                foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {


                    $_delivery_note_data = preg_split('/\|/', $delivery_note_data);


                    $deliveries = sprintf(
                        "<span class='padding_right_10 error link' onClick=\"change_view('delivery_notes/%d/%d')\"><i class=\"fa fa-truck   \" ></i> %s</span>", $data['Order Store Key'], $_delivery_note_data[0], $_delivery_note_data[1]

                    );
                }
            }
        } else {
            $payment_state = get_order_formatted_payment_state($data);
            $total_amount  = money($data['Order Total Amount'], $data['Order Currency']);
            $deliveries    = '';


            if($data['delivery_notes']!=''){
                foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
                    $_delivery_note_data = preg_split('/\|/', $delivery_note_data);

                    $deliveries = sprintf(
                        "<span class='padding_right_10 link' onClick=\"change_view('delivery_notes/%d/%d')\"><i class=\"fa fa-truck fa-flip-horizontal   \" ></i> %s</span>", $data['Order Store Key'], $_delivery_note_data[0], $_delivery_note_data[1]

                    );
                }
            }

        }


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], $data['Order Replacement State'], $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => $total_amount,
            'actions'        => $operations,
            'deliveries'     => $deliveries


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


function orders_in_warehouse_with_alerts($_data, $db, $user) {
    $rtext_label = 'order warehouse with alerts';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;
    foreach ($db->query($sql) as $data) {


        $payment_state = get_order_formatted_payment_state($data);

        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_packed_done($_data, $db, $user) {


    $rtext_label = 'order packed done';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {

        $payment_state = get_order_formatted_payment_state($data);
        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_approved($_data, $db, $user) {
    $rtext_label = 'order approved';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {

        $payment_state = get_order_formatted_payment_state($data);

        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_dispatched_today($_data, $db, $user) {
    $rtext_label = 'order dispatched today';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {

        $payment_state = get_order_formatted_payment_state($data);
        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        $operations .= '<div class="buttons small '.$class.'">';


        $operations .= sprintf(
            "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
        );

        foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
            $operations .= sprintf(
                "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

            );
        }


        $operations .= '</div>';


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
            ),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_in_process_old($_data, $db, $user) {
    $rtext_label = 'order';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {

        $payment_state = get_order_formatted_payment_state($data);


        include_once 'class.Order.php';

        $operations = '<div id="operations'.$data['Order Key'].'">';
        $class      = 'right';


        if ($data['Order State'] == 'InProcess') {
            $operations .= '<div class="buttons small '.$class.'">';
            $operations .= sprintf(
                "<i class=\"fa fa-minus-circle error padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
            );

            $operations .= sprintf(
                "<i id=\"send_to_warehouse_button_%d\" class=\"%s fa fa-hand-lizard fa-flip-horizontal button edit \" onClick=\"create_delivery_note_from_list(this,%d)\" title='%s'></i>", $data['Order Key'], ($data['Order Number Items'] == 0 ? 'disabled' : ''),
                $data['Order Key'], _('Send for picking')
            );

            //$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$data['Order Key'],_('Edit Order'));

            $operations .= '</div>';

        } elseif ($data['Order State'] == 'InBasket') {
            $operations .= '<div class="buttons small '.$class.'">';


            $operations .= sprintf(
                "<i class=\"fa fa-minus-circle error padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
            );

            if ($data['Order Number Items'] > 0) {

                $operations .= sprintf(
                    "<i id=\"send_to_warehouse_button_%d\" class=\"%s fa fa-hand-lizard fa-flip-horizontal button edit \" onClick=\"create_delivery_note_from_list(this,%d)\" title='%s'></i>", $data['Order Key'], ($data['Order Number Items'] == 0 ? 'disabled' : ''),
                    $data['Order Key'], _('Send for picking')
                );
            }


            $operations .= '</div>';

        } elseif (in_array(
            $data['Order State'], array(
                                    'InWarehouse',

                                )
        )) {

            $operations .= '<div class="buttons small '.$class.'">';


            $operations .= sprintf(
                "<i class=\"fa fa-minus-circle error  padding_right_10 button edit\" onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\" title='%s'></i>", $data['Order Key'], $data['Order Public ID'], $data['Order Customer Name'], _('Cancel')
            );

            foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
                $operations .= sprintf(
                    "<i class=\"fa fa-truck fa-flip-horizontal   button\" onClick=\"change_view('delivery_notes/%d/%d')\"></i>", $data['Order Store Key'], $delivery_note_data

                );
            }


            $operations .= '</div>';

        } elseif ($data['Order State'] == 'PackedDone') {

            $operations .= '<div class="buttons small '.$class.'">';
            if ($data['Order Invoiced'] == 'No') {
                $operations .= '<button  onClick="create_invoice(this,'.$data['Order Key'].')"><img id="create_invoice_img_'.$data['Order Key'].'" style="height:12px;width:12px" src="/art/icons/money.png"> '._('Create Invoice')."</button>";;
            } else {
                $operations .= '<button  onClick="approve_dispatching(this,'.$data['Order Key'].')"><img id="approve_dispatching_img_'.$data['Order Key'].'" style="height:12px;width:12px" src="/art/icons/package_green.png"> '._('Approve Dispatching')."</button>";;


            }
            $operations .= '</div>';

        } elseif ($data['Order State'] == 'Approved') {
            $operations .= '<div class="buttons small '.$class.'">';
            $order      = new Order($data['Order Key']);
            $dns        = $order->get_delivery_notes_objects();
            if (count($dns) == 1) {
                foreach ($dns as $dn) {

                    $operations .= '<button  onClick="set_as_dispatched('.$dn->data['Delivery Note Key'].','.$user->get_staff_key().',\'order\',\''.$data['Order Key'].'\')" ><img id="set_as_dispatched_img_'.$dn->data['Delivery Note Key']
                        .'" src="/art/icons/lorry_go.png" alt=""> '._(
                            'Mark as Dispatched'
                        )."</button>";
                }
            }

            $operations .= '</div>';

        }


        $operations .= '</div>';


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'        => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $data['Order Public ID'],
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations


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


function orders_in_website($_data, $db, $user) {
    $rtext_label = 'order in basket';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'           => (integer)$data['Order Key'],
            'checked'      => sprintf('<i class="fa fa-square fa-fw button"  aria-hidden="true" onClick="select_order(this)"></i>'),
            'public_id'    => sprintf('<span class="link" onClick="change_view(\'orders/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),
            'date'         => strftime("%e %b %Y", strtotime($data['Order Created Date'].' +0:00')),
            'last_updated' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'     => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'total_amount' => money($data['Order Total Amount'], $data['Order Currency']),
            'idle_time'    => number($data['idle_time'])


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


function archived_orders($_data, $db, $user) {
    $rtext_label = 'order';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {


        switch ($data['Order State']) {
            case 'Dispatched':
                $dispatch_state = '<i class="fa fa-paper-plane" aria-hidden="true" tile="'._('Dispatched').'" ></i>';
                break;
            case 'Cancelled':
                $dispatch_state = '<i class="fa fa-minus-circle error" aria-hidden="true" tile="'._('Cancelled').'" ></i>';
                break;
            default:
                $dispatch_state = '<i class="fa fa-question warning" aria-hidden="true" tile="'.$data['Order State'].'" ></i>';
                break;
        }

        $adata[] = array(
            'id' => (integer)$data['Order Key'],

            'dispatch_state' => $dispatch_state,
            'public_id'      => sprintf('<span class="link" onClick="change_view(\'orders/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),
            'date'           => strftime("%a %e %b %Y", strtotime($data['Order Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),


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


function orders_server($_data, $db, $user) {
    $rtext_label = 'order';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $link_format = '/orders/all/%d';


    //'InBasket','InProcess','InWarehouse','PackedDone','
    //
    //
    //DispatchAproved','Dispatched','Cancelled'


    foreach ($db->query($sql) as $data) {

        switch ($data['Order State']) {
            case('InBasket'):
                $state = _('In Basket');
                break;
            case('InProcess'):
                $state = _('Submitted');
                break;
            case('InWarehouse'):
                $state = _('In Warehouse');
                break;
            case('PackedDone'):
                $state = _('Packed Done');
                break;
            case('Dispatch Approved'):
                $state = _('Dispatch Approved');
                break;
            case('Dispatched'):
                $state = _('Dispatched');
                break;
            case('Cancelled'):
                $state = _('Cancelled');
                break;
            default:
                $state = $data['Order State'];

        }


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'store'          => sprintf('<span class="link" onClick="change_view(\'/orders/%d\')">%s</span>', $data['Order Store Key'], $data['Store Code']),
            'state'          => $state,
            'public_id'      => sprintf('<span class="link" onClick="change_view(\'/orders/all/%d\')">%s</span>', $data['Order Key'], $data['Order Public ID']),
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => get_order_formatted_payment_state($data),
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'margin'         => sprintf('<span title="%s: %s">%s</span>', _('Profit'), money($data['Order Profit Amount'], $data['Order Currency']), percentage($data['Order Margin'], 1)),


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

function orders($_data, $db, $user) {

    $rtext_label = 'order';


    include_once 'prepare_table/init.php';


    if ($parameters['parent'] == 'charge') {
        $rtext_label = 'submited orders with this charge';

    }


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($parameters['parent'] == 'store') {
        $link_format = '/orders/%d/%d';
    } else {
        $link_format = '/'.$parameters['parent'].'/%d/order/%d';
    }

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Order State']) {
                case('InBasket'):
                    $state = _('In Basket');
                    break;
                case('InProcess'):
                    $state = _('Submitted');
                    break;
                case('InWarehouse'):
                    $state = _('In Warehouse');
                    break;
                case('PackedDone'):
                    $state = _('Packed Done');
                    break;
                case('Dispatch Approved'):
                    $state = _('Dispatch Approved');
                    break;
                case('Dispatched'):
                    $state = _('Dispatched');
                    break;
                case('Cancelled'):
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Order State'];

            }


            $adata[] = array(
                'id' => (integer)$data['Order Key'],

                'public_id' => sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%s</span>', $parameters['parent_key'], $data['Order Key'], $data['Order Public ID']),
                'state'     => $state,

                'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
                'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
                'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
                'payment_state'  => get_order_formatted_payment_state($data),
                'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
                'margin'         => sprintf('<span title="%s: %s">%s</span>', _('Profit'), money($data['Order Profit Amount'], $data['Order Currency']), percentage($data['Order Margin'], 1)),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function delivery_notes($_data, $db, $user) {


    $rtext_label = 'delivery_note';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    foreach ($db->query($sql) as $data) {


        $notes = '';

        switch ($data['Delivery Note State']) {

            case 'Picker & Packer Assigned':
                $state = _('Picker & packer assigned');
                break;
            case 'Picking & Packing':
                $state = _('Picking & packing');
                break;
            case 'Packer Assigned':
                $state = _('Packer assigned');
                break;
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
                $state = _('Packed done');
                break;
            default:
                $state = $data['Delivery Note State'];
                break;
        }

        switch ($data['Delivery Note Type']) {
            case('Order'):
                $type = _('Order');
                break;
            case('Sample'):
                $type = _('Sample');
                break;
            case('Donation'):
                $type = _('Donation');
                break;
            case('Replacement'):
            case('Replacement & Shortages'):
                $type = _('Replacement');
                break;
            case('Shortages'):
                $type = _('Shortages');
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

            'date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Created'].' +0:00')),
            'state'   => $data['Delivery Note XHTML State'],
            'weight'  => weight($data['Delivery Note Weight']),
            'parcels' => $parcels,
            'type'    => $type,
            'state'   => $state,
            'notes'   => $notes,

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


function pending_delivery_notes($_data, $db, $user) {


    $rtext_label = 'delivery_note';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    foreach ($db->query($sql) as $data) {


        switch ($data['Delivery Note Type']) {
            case('Order'):
                $type = _('Order');
                break;
            case('Sample'):
                $type = _('Sample');
                break;
            case('Donation'):
                $type = _('Donation');
                break;
            case('Replacement'):
            case('Replacement & Shortages'):
                $type = _('Replacement');
                break;
            case('Shortages'):
                $type = _('Shortages');
                break;
            default:
                $type = $data['Delivery Note Type'];

        }

        switch ($data['Delivery Note Parcel Type']) {
            case('Pallet'):
                $parcel_type = 'P';
                break;
            case('Envelope'):
                $parcel_type = 'e';
                break;
            default:
                $parcel_type = 'b';

        }

        if ($data['Delivery Note Number Parcels'] == '') {
            $parcels = '?';
        } elseif ($data['Delivery Note Parcel Type'] == 'Pallet' and $data['Delivery Note Number Boxes']) {
            $parcels = number($data['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$data['Delivery Note Number Boxes'].' b)';
        } else {
            $parcels = number($data['Delivery Note Number Parcels']).' '.$parcel_type;
        }


        $adata[] = array(
            'id'           => (integer)$data['Delivery Note Key'],
            'store_key'    => (integer)$data['Delivery Note Store Key'],
            'customer_key' => (integer)$data['Delivery Note Customer Key'],

            'number'   => $data['Delivery Note ID'],
            'customer' => $data['Delivery Note Customer Name'],

            'date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Created'].' +0:00')),
            'state'   => $data['Delivery Note XHTML State'],
            'weight'  => weight($data['Delivery Note Weight']),
            'parcels' => $parcels,
            'type'    => $type,


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


function invoices($_data, $db, $user) {

    $rtext_label = 'invoice';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            if ($data['Invoice Paid'] == 'Yes') {
                $state = _('Paid');
            } elseif ($data['Invoice Paid'] == 'Partially') {
                $state = _('Partially Paid');
            } else {
                $state = _('No Paid');
            }


            if ($data['Invoice Type'] == 'Invoice') {
                $type = _('Invoice');
            } elseif ($data['Invoice Type'] == 'CreditNote') {
                $type = _('Credit Note');
            } else {
                $type = _('Refund');
            }

            switch ($data['Invoice Main Payment Method']) {
                default:
                    $method = $data['Invoice Main Payment Method'];
            }

            $adata[] = array(
                'id' => (integer)$data['Invoice Key'],


                'number' => sprintf('<span class="link" onclick="change_view(\'invoices/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Key'], $data['Invoice Public ID']),

                'customer'     => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Customer Key'], $data['Invoice Customer Name']),
                'date'         => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Invoice Date'].' +0:00')),
                'total_amount' => money($data['Invoice Total Amount'], $data['Invoice Currency']),
                'net'          => money($data['Invoice Total Net Amount'], $data['Invoice Currency']),
                'shipping'     => money($data['Invoice Shipping Net Amount'], $data['Invoice Currency']),
                'items'        => money($data['Invoice Items Net Amount'], $data['Invoice Currency']),
                'type'         => $type,
                'method'       => $method,
                'state'        => $state,


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function orders_group_by_store($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $total_orders     = 0;
    $total_invoices   = 0;
    $total_refunds    = 0;
    $total_in_basket  = 0;
    $total_in_process = 0;
    $total_sent       = 0;
    $total_cancelled  = 0;


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $total_orders     += $data['orders'];
            $total_invoices   += $data['Store Invoices'];
            $total_refunds    += $data['Store Refunds'];
            $total_in_basket  += $data['in_basket'];
            $total_in_process += $data['in_process'];

            $total_sent      += $data['sent'];
            $total_cancelled += $data['cancelled'];

            $adata[] = array(
                'store_key' => $data['Store Key'],
                'code'      => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], $data['Store Code']),
                'name'      => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], $data['Store Name']),
                'orders'    => number($data['orders']),
                'invoices'  => sprintf('<span class="link" onclick="change_view(\'invoices/%d\')">%s</span>', $data['Store Key'], number($data['Store Invoices'])),
                'refunds'   => sprintf('<span class="link" onclick="change_view(\'invoices/%d\')">%s</span>', $data['Store Key'], number($data['Store Refunds'])),

                'in_basket'  => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], number($data['in_basket'])),
                'in_process' => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], number($data['in_process'])),

                'sent'      => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], number($data['sent'])),
                'cancelled' => sprintf('<span class="link" onclick="change_view(\'orders/%d\')">%s</span>', $data['Store Key'], number($data['cancelled'])),

            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $adata[] = array(
        'store_key' => '',
        'name'      => '',
        'code'      => _('Total').($filtered > 0 ? ' '.'<i class="fa fa-filter fa-fw"></i>' : ''),

        'orders'   => number($total_orders),
        'invoices' => number($total_invoices),
        'refunds'  => number($total_refunds),

        'in_basket'  => number($total_in_basket),
        'in_process' => number($total_in_process),

        'sent'      => number($total_sent),
        'cancelled' => number($total_cancelled),

    );


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


function delivery_notes_group_by_store($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $total_deliveries   = 0;
    $total_replacements = 0;
    $total_in_warehouse = 0;
    $total_sent         = 0;
    $total_returned     = 0;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $total_deliveries   += $data['deliveries'];
            $total_replacements += $data['replacements'];
            $total_in_warehouse += $data['in_warehouse'];
            $total_sent         += $data['sent'];
            $total_returned     += $data['returned'];

            $adata[] = array(
                'store_key' => $data['Store Key'],
                'code'      => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d\')">%s</span>', $data['Store Key'], $data['Store Code']),
                'name'      => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d\')">%s</span>', $data['Store Key'], $data['Store Name']),

                'deliveries'   => number($data['deliveries']),
                'replacements' => number($data['replacements']),

                'in_warehouse' => number($data['in_warehouse']),
                'sent'         => number($data['sent']),
                'returned'     => number($data['returned']),

            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $adata[] = array(
        'store_key' => '',
        'name'      => '',
        'code'      => _('Total').($filtered > 0 ? ' '.'<i class="fa fa-filter fa-fw"></i>' : ''),

        'deliveries'   => number($total_deliveries),
        'replacements' => number($total_replacements),
        'in_warehouse' => number($total_in_warehouse),
        'sent'         => number($total_sent),
        'returned'     => number($total_returned),


    );


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

function orders_index($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $total_orders         = 0;
    $total_invoices       = 0;
    $total_delivery_notes = 0;
    $total_payments       = 0;


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $total_orders         += $data['orders'];
            $total_invoices       += $data['invoices'];
            $total_delivery_notes += $data['delivery_notes'];
            $total_payments       += $data['payments'];

            $adata[] = array(
                'store_key'      => $data['Store Key'],
                'code'           => $data['Store Code'],
                'name'           => $data['Store Name'],
                'orders'         => number($data['orders']),
                'delivery_notes' => number($data['delivery_notes']),
                'invoices'       => number($data['invoices']),
                'payments'       => number($data['payments']),
            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    if ($parameters['percentages']) {
        $sum_total        = '100.00%';
        $sum_active       = '100.00%';
        $sum_new          = '100.00%';
        $sum_lost         = '100.00%';
        $sum_contacts     = '100.00%';
        $sum_new_contacts = '100.00%';
    } else {

    }


    $adata[] = array(
        'store_key' => '',
        'name'      => '',
        'code'      => _('Total').($filtered > 0 ? ' '.'<i class="fa fa-filter fa-fw"></i>' : ''),

        'orders'         => number($total_orders),
        'delivery_notes' => number($total_delivery_notes),
        'invoices'       => number($total_invoices),
        'payments'       => number($total_payments),

    );


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


function order_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();
    foreach ($db->query($sql) as $data) {

        /*
                if ($data['Order Bonus Quantity'] != 0) {
                    if ($data['Order Quantity'] != 0) {
                        $quantity .= '<br/> +'.number($data['Order Bonus Quantity']).' '._('free');
                    } else {
                        $quantity = number($data['Order Bonus Quantity']).' '._('free');
                    }
                }
        */

        if (is_numeric($data['Product Availability'])) {
            $stock = number($data['Product Availability']);
        } else {
            $stock = '?';
        }




        if($data['Deal Info']!=''){
            $deal_info_data=preg_split('/\|\|/',$data['Deal Info']);


            $deal_info = '<div id="transaction_deal_info_'.$data['Order Transaction Fact Key'].'" class="deal_info">'.($deal_info_data[1]=='Yes'?'<i class="fas fa-thumbtack"></i> ':'').$deal_info_data[0].'</div>';

        }else{
            $deal_info='';
        }


        $units    = $data['Product Units Per Case'];
        $name     = $data['Product History Name'];
        $price    = $data['Product History Price'];
        $currency = $data['Product Currency'];


        $description = '';
        if ($units > 1) {
            $description = number($units).'x ';
        }
        $description .= ' '.$name;
        if ($price > 0) {
            $description .= ' ('.money($price, $currency, $_locale).')';
        }


        $description .= ' <span style="color:#777">['.$stock.']</span> '.$deal_info;


        if ($data['Current Dispatching State'] == 'Out of Stock in Basket') {
            $description .= '<br> <span class="warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Product out of stock, removed from basket').'</span>';
            $quantity    = number($data['Out of Stock Quantity']);

            $class = 'out_of_stock';

        }


        if (in_array(
            $customer_order->get('Order State'), array(
                                                   'InProcess',
                                                   'InWarehouse',
                                                   'InBasket'
                                               )
        )) {
            $quantity = sprintf(
                '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
            <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
            <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
            );
        } else {

            if ($data['Order Quantity'] != $data['Delivery Note Quantity']) {
                // $quantity = number($data['Delivery Note Quantity']).' <i class="fa fa-exclamation-circle error"></i> <span class="discreet " title="'._('Ordered quantity').'">('.$data['Order Quantity'].')</span>';
                $quantity = '<span class="discreet " title="'.sprintf(_('%s ordered by customer'), number($data['Order Quantity'])).'" >(<span class="strikethrough">'.number($data['Order Quantity']).'</span>)</span> '.number($data['Delivery Note Quantity']);

            } else {
                $quantity = number($data['Order Quantity']);

            }

        }


        if (in_array(
            $customer_order->get('Order State'), array(
                                                   'Cancelled',
                                                   'Approved',
                                                   'Dispatched',
                                               )
        )) {
            $discounts_class = '';
            $discounts_input = '';
        } else {
            $discounts_class = 'button';
            $discounts_input = sprintf(
                '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], percentage($data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount'])
            );
        }
        $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($data['Order Transaction Total Discount Amount'] == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                $data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount']
            ).'</span> <span class="'.($data['Order Transaction Total Discount Amount'] == 0 ? 'hide' : '').'">'.money($data['Order Transaction Total Discount Amount'], $data['Order Currency Code']).'</span></span>';


        $adata[] = array(

            'id'          => (integer)$data['Order Transaction Fact Key'],
            'product_pid' => (integer)$data['Product ID'],
            'code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product Code']),
            'description' => $description,
            'quantity'    => $quantity,
            'discounts'   => '<span id="transaction_discounts_'.$data['Order Transaction Fact Key'].'" class="_item_discounts">'.$discounts.'</span>',


            'net' => sprintf('<span  id="transaction_item_net_'.$data['Order Transaction Fact Key'].'" class="_order_item_net">%s</span>', money($data['Order Transaction Amount'], $data['Order Currency Code'])),


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


function invoice_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';
    include_once 'utils/geography_functions.php';

    include_once 'prepare_table/init.php';
    include_once 'class.Invoice.php';


    $invoice = new Invoice($_data['parameters']['parent_key']);
    if (in_array(
        $invoice->data['Invoice Delivery Country Code'], get_countries_EC_Fiscal_VAT_area($db)
    )) {
        $print_tariff_code = false;
    } else {
        $print_tariff_code = true;
    }


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    // print $sql;

    $adata = array();
    foreach ($db->query($sql) as $data) {

        $net = money(
            ($data['Order Transaction Amount']), $data['Invoice Currency Code']
        );

        $tax    = money(
            ($data['Invoice Transaction Item Tax Amount']), $data['Invoice Currency Code']
        );
        $amount = money(
            ($data['Invoice Transaction Gross Amount'] - $data['Invoice Transaction Total Discount Amount'] + $data['Invoice Transaction Item Tax Amount']), $data['Invoice Currency Code']
        );


        $discount = ($data['Invoice Transaction Total Discount Amount'] == 0
            ? ''
            : percentage(
                $data['Invoice Transaction Total Discount Amount'], $data['Invoice Transaction Gross Amount'], 0
            ));

        $units    = $data['Product Units Per Case'];
        $name     = $data['Product History Name'];
        $price    = $data['Product History Price'];
        $currency = $data['Product Currency'];

        $desc = '';
        if ($units > 1) {
            $desc = number($units).'x ';
        }
        $desc .= ' '.$name;
        if ($price > 0) {
            $desc .= ' ('.money($price, $currency, $_locale).')';
        }

        $description = $desc;

        if ($discount != '') {
            $description .= ' '._('Discount').':'.$discount;
        }

        if ($data['Product RRP'] != 0) {
            $description .= ' <br>'._('RRP').': '.money(
                    $data['Product RRP'], $data['Invoice Currency Code']
                );
        }

        if ($print_tariff_code and $data['Product Tariff Code'] != '') {
            $description .= '<br>'._('Tariff Code').': '.$data['Product Tariff Code'];
        }


        $quantity = number($data['Delivery Note Quantity']);


        $adata[] = array(
            'id'          => (integer)$data['Order Transaction Fact Key'],
            'product_pid' => (integer)$data['Product ID'],
            'code'        => $data['Product Code'],
            'description' => $description,
            'quantity'    => $quantity,
            'net'         => $net,
            'tax'         => $net,
            'amount'      => $net,


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


function delivery_note_fast_track_packing($_data, $db, $user) {

    //print_r($_data);

    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    $dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";
    // print $sql;
    $adata = array();
    foreach ($db->query($sql) as $data) {


        $to_pick = $data['quantity'] - $data['Picked'];
        $to_pack = $data['quantity'] - $data['Packed'];


        switch ($dn->data['Delivery Note State']) {
            case 'Dispatched':
                $state = _('dispatched');
                break;
            case 'Cancelled':
                $state = '';
                break;
            case 'Cancelled to Restock':
                $state = _('to be restocked');
                break;
            default:
                $state = _('to be dispatched');
                break;
        }


        $notes = '<b>'.number(-1 * $data['Inventory Transaction Quantity']).'</b> '.$state.'<br/>';

        if ($data['Out of Stock'] != 0) {
            $notes .= '<span style="margin-left:10px">'.number(
                    $data['Out of Stock']
                ).'</span> '._('out of stock').'<br/>';
        }
        if ($data['Not Found'] != 0) {
            $notes .= number($data['Not Found']).' '._('Not found').'<br/>';
        }
        if ($data['No Picked Other'] != 0) {
            $notes .= _('not picked (other)').' '.number(
                    $data['No Picked Other']
                ).'<br/>';
        }


        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</span>';
        }


        $quantity = '<div class="quantity_components">'.get_item_quantity($data['quantity'], $data['to_pick']).'</div>';

        $picked = '<div class="picked_quantity_components">'.get_item_picked(
                $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Reference'],
                base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']

            ).'</div>';


        $packed   = '<div class="packed_quantity_components">'.get_item_packed($to_pack, $data['Inventory Transaction Key'], $data['Part SKU'], $data['Packed']).'</div>';
        $location = '<div class="location_components">'.get_item_location(
                $data['pending'], $data['Quantity On Hand'], $data['Date Picked'], $data['Location Key'], $data['Location Code'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode']
            ).'</div>';


        if ($data['Picked'] == $data['quantity']) {
            $picked_info = '<i class="fa fa-fw fa-check success" aria-hidden="true"></i>';

        } else {
            $picked_info = '';
        }


        $picked_offline_input = '<div class="picked_quantity_components">'.get_picked_offline_input(
                $data['quantity'], $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Reference'],
                base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']

            ).'</div>';
        $adata[]              = array(
            'id' => (integer)$data['Inventory Transaction Key'],

            'reference'         => sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            //   'product_pid' => $data['Product ID'],
            'description'       => $description,
            'quantity'          => $quantity,
            'dispatched'        => number(-1 * $data['Inventory Transaction Quantity']),
            'overview_required' => number($data['Required']),

            'overview_packed'  => number($data['Packed']),
            'overview_picked'  => number($data['Picked']),
            'overview_problem' => number($data['Out of Stock']),


            'packed'               => $packed,
            'picked'               => $picked,
            'picked_info'          => $picked_info,
            'location'             => $location,
            'picked_offline_input' => $picked_offline_input


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


function delivery_note_items($_data, $db, $user) {

    //print_r($_data);

    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    $dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";
    // print $sql;
    $adata = array();
    foreach ($db->query($sql) as $data) {


        $to_pick = $data['quantity'] - $data['Picked'];
        $to_pack = $data['quantity'] - $data['Packed'];


        switch ($dn->data['Delivery Note State']) {
            case 'Dispatched':
                $state = _('dispatched');
                break;
            case 'Cancelled':
                $state = '';
                break;
            case 'Cancelled to Restock':
                $state = _('to be restocked');
                break;
            default:
                $state = _('to be dispatched');
                break;
        }


        $notes = '<b>'.number(-1 * $data['Inventory Transaction Quantity']).'</b> '.$state.'<br/>';

        if ($data['Out of Stock'] != 0) {
            $notes .= '<span style="margin-left:10px">'.number(
                    $data['Out of Stock']
                ).'</span> '._('out of stock').'<br/>';
        }
        if ($data['Not Found'] != 0) {
            $notes .= number($data['Not Found']).' '._('Not found').'<br/>';
        }
        if ($data['No Picked Other'] != 0) {
            $notes .= _('not picked (other)').' '.number(
                    $data['No Picked Other']
                ).'<br/>';
        }


        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</span>';
        }


        $quantity = '<div class="quantity_components">'.get_item_quantity($data['quantity'], $data['to_pick']).'</div>';

        $picked = '<div class="picked_quantity_components">'.get_item_picked(
                $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Reference'],
                base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']

            ).'</div>';


        $packed   = '<div class="packed_quantity_components">'.get_item_packed($to_pack, $data['Inventory Transaction Key'], $data['Part SKU'], $data['Packed']).'</div>';
        $location = '<div class="location_components">'.get_item_location(
                $data['pending'], $data['Quantity On Hand'], $data['Date Picked'], $data['Location Key'], $data['Location Code'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode']
            ).'</div>';


        if ($data['Picked'] == $data['quantity']) {
            $picked_info = '<i class="fa fa-fw fa-check success" aria-hidden="true"></i>';

        } else {
            $picked_info = '';
        }


        $state_picking = '';
        $state_packing = '';
        if ($data['Required'] > 0) {

            if ($data['Picked'] == $data['Required']) {

                $state_picking = sprintf('<i class="fa-dolly-flatbed-alt fa success discreet fa-fw " title="%s"></i>', _('Picked'));
            } elseif ($data['Picked'] > 0) {
                $state_picking = sprintf('<i class="fa-dolly-flatbed-alt fa discreet fa-fw " title="%s"></i>', _('Picking'));

            } else {
                $state_picking = sprintf('<i class="fa-dolly-flatbed-empty fa discreet fa-fw " title="%s"></i>', _('To be picked'));

            }


            if ($data['Packed'] == $data['Required']) {

                $state_packing = sprintf('<i class="fa-check-circle fa success fa-fw " title="%s"></i>', _('Packed'));
                $state_picking = '';
            } elseif ($data['Packed'] > 0) {
                $state_packing = sprintf('<i class="fa-arrow-alt-circle-down discreet fa fa-fw " title="%s"></i>', _('Packing'));
                $state_picking = '';
            } else {
                $state_packing = '';

            }


        }

        $state = '<span class="padding_left_20">'.$state_picking.' '.$state_packing.'</span>';

        $picked_offline_input = '<div class="picked_quantity_components">'.get_picked_offline_input(
                $data['quantity'], $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Reference'],
                base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']

            ).'</div>';
        $adata[]              = array(
            'id' => (integer)$data['Inventory Transaction Key'],

            'reference'         => sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            //   'product_pid' => $data['Product ID'],
            'description'       => $description,
            'quantity'          => $quantity,
            'dispatched'        => number(-1 * $data['Inventory Transaction Quantity']),
            'overview_required' => number($data['Required']).($data['Given'] != 0 ? '<i class="fa fa-gift padding_left_10"></i> '.number($data['Given']) : ''),

            'overview_packed'  => number($data['Packed']),
            'overview_picked'  => number($data['Picked']),
            'overview_problem' => number($data['Out of Stock']),


            'packed'               => $packed,
            'picked'               => $picked,
            'picked_info'          => $picked_info,
            'location'             => $location,
            'picked_offline_input' => $picked_offline_input,
            'overview_state'       => $state


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


function delivery_note_cancelled_items($_data, $db, $user) {

    //print_r($_data);

    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    $dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";
    // print $sql;
    $adata = array();
    foreach ($db->query($sql) as $data) {


        $description = $data['Part Package Description'];


        $adata[] = array(
            'id' => (integer)$data['Inventory Transaction Key'],

            'reference'         => sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            'description'       => $description,
            'overview_required' => number($data['Required']),

            'overview_packed'  => number($data['Packed']),
            'overview_picked'  => number($data['Picked']),
            'overview_problem' => number($data['Out of Stock']),
            'overview_restock' => sprintf(
                '%s returned to %s',

                number(-1 * $data['Inventory Transaction Quantity']).' SKO', sprintf('<span class="button strong" onclick="change_view(\'/locations/%d/%d\')"  >%s</span>', $data['Warehouse Key'], $data['Location Key'], $data['Location Code'])
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


function invoice_categories($_data, $db, $user, $account) {


    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $total_refunds            = 0;
    $total_refunds_1yb        = 0;
    $total_refunds_amount_1yb = 0;


    $total_invoices            = 0;
    $total_invoices_1yb        = 0;
    $total_invoices_amount_1yb = 0;

    $total_sales     = 0;
    $total_sales_1yb = 0;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $total_refunds  += $data['refunds'];
            $total_invoices += $data['invoices'];
            $total_sales    += $data['sales'];


            if ($data['refunds_1yb'] != '') {
                $total_refunds_1yb         += $data['refunds_1yb'];
                $total_refunds_amount_1yb  += $data['refunds_amount_1yb'];
                $total_invoices_1yb        += $data['invoices_1yb'];
                $total_invoices_amount_1yb += $data['invoices_amount_1yb'];
                $total_sales_1yb           += $data['sales_1yb'];
            }


            //    change_view('invoices/2' , { parameters:{ period:'ytd',elements_type:'type' } ,element:{ type:{ Refund:1,Invoice:''}} } )


            $adata[] = array(
                'id'          => (integer)$data['Category Key'],
                'code'        => sprintf(
                    '<span class="link" onclick="change_view(\'category/%d\', { tab:\'category.subjects\', parameters:{ period:\'%s\',elements_type:\'type\' } ,element:{ type:{ Invoice:1,Refund:1}} })" >%s</span>', $data['Category Key'],
                    $_data['parameters']['f_period'], $data['Category Code']
                ),
                'label'       => $data['Category Label'],
                'refunds'     => sprintf(
                    '<span class="link %s"  onclick="change_view(\'category/%d\', { tab:\'category.subjects\', parameters:{ period:\'%s\',elements_type:\'type\' } ,element:{ type:{ Refund:1,Invoice:\'\'}} })"  >%s</span>',

                    ($data['refunds'] == 0 ? 'very_discreet' : ''), $data['Category Key'], $_data['parameters']['f_period'], number($data['refunds'])
                ),
                'refunds_1yb' => ($data['refunds_1yb'] == ''
                    ? ''
                    : sprintf(
                        '<span title="%s" class="%s">%s %s</span>', sprintf(_('Refunds one year back %s, (%s)'), number($data['refunds_1yb']), money($data['refunds_amount_1yb'], $account->get('Account Currency Code'))),
                        (($data['refunds'] == 0 or $data['refunds_1yb'] == 0) ? 'very_discreet' : ''), delta($data['refunds'], $data['refunds_1yb']), delta_icon($data['refunds'], $data['refunds_1yb'])
                    )),


                'invoices' => sprintf(
                    '<span class="link %s"  onclick="change_view(\'category/%d\', { tab:\'category.subjects\', parameters:{ period:\'%s\',elements_type:\'type\' } ,element:{ type:{ Invoice:1,Refund:\'\'}} })"  >%s</span>',

                    ($data['invoices'] == 0 ? 'very_discreet' : ''), $data['Category Key'], $_data['parameters']['f_period'], number($data['invoices'])
                ),


                'invoices_1yb' => ($data['invoices_1yb'] == ''
                    ? ''
                    : sprintf(
                        '<span title="%s" class="%s">%s %s</span>', sprintf(_('Invoices one year back %s, (%s)'), number($data['invoices_1yb']), money($data['invoices_amount_1yb'], $account->get('Account Currency Code'))),
                        (($data['invoices'] == 0 or $data['invoices_1yb'] == 0) ? 'very_discreet' : ''), delta($data['invoices'], $data['invoices_1yb']), delta_icon($data['invoices'], $data['invoices_1yb'])
                    )),
                'sales'        => '<span class="'.($data['sales'] == 0 ? 'very_discreet' : '').'">'.money($data['sales'], $account->get('Account Currency Code')).'</span>',
                'sales_1yb'    => ($data['sales_1yb'] == ''
                    ? ''
                    : sprintf(
                        '<span title="%s" class="%s">%s %s</span>', sprintf(_('Sales one year back %s'), money($data['sales_1yb'], $account->get('Account Currency Code'))), (($data['sales'] == 0 or $data['sales_1yb'] == 0) ? 'very_discreet' : ''),
                        delta($data['sales'], $data['sales_1yb']), delta_icon($data['sales'], $data['sales_1yb'])
                    )),
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $db_period = get_interval_db_name($_data['parameters']['f_period']);
    if (in_array(
        $db_period, array(
                      'Total',
                      '3 Year'
                  )
    )) {
        $total_refunds_1yb  = '';
        $total_invoices_1yb = '';
    }

    $adata[] = array(
        'id'          => 'total',
        'code'        => _('Total'),
        'label'       => '',
        'refunds'     => '<span class="'.($total_refunds == 0 ? 'very_discreet' : '').'">'.number($total_refunds).'</span>',
        'refunds_1yb' => ($total_refunds_1yb == ''
            ? ''
            : sprintf(
                '<span title="%s" class="%s">%s %s</span>', sprintf(_('Refunds one year back %s, (%s)'), number($total_refunds_1yb), money($total_refunds_amount_1yb, $account->get('Account Currency Code'))),
                (($total_refunds == 0 or $total_refunds_1yb == 0) ? 'very_discreet' : ''), delta($total_refunds, $total_refunds_1yb), delta_icon($total_refunds, $total_refunds_1yb)
            )),
        'invoices'    => '<span class="'.($total_invoices == 0 ? 'very_discreet' : '').'">'.number($total_invoices).'</span>',

        'invoices_1yb' => ($total_invoices_1yb == ''
            ? ''
            : sprintf(
                '<span title="%s" class="%s">%s %s</span>', sprintf(_('invoices one year back %s, (%s)'), number($total_invoices_1yb), money($total_invoices_amount_1yb, $account->get('Account Currency Code'))),
                (($total_invoices == 0 or $total_invoices_1yb == 0) ? 'very_discreet' : ''), delta($total_invoices, $total_invoices_1yb), delta_icon($total_invoices, $total_invoices_1yb)
            )),

        'sales' => '<span class="'.($total_sales == 0 ? 'very_discreet' : '').'">'.money($total_sales, $account->get('Account Currency Code')).'</span>',

        'sales_1yb' => ($total_sales_1yb == ''
            ? ''
            : sprintf(
                '<span title="%s" class="%s">%s %s</span>', sprintf(_('sales one year back %s'), money($total_sales_1yb, $account->get('Account Currency Code'))), (($total_sales == 0 or $total_sales_1yb == 0) ? 'very_discreet' : ''),
                delta($total_sales, $total_sales_1yb), delta_icon($total_sales, $total_sales_1yb)
            )),

    );


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

function orders_in_website_mailshots($_data, $db, $user) {

    $rtext_label = 'mailshot';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {


        if ($parameters['parent'] == 'store') {
            $name = sprintf('<span class="link" onClick="change_view(\'orders/%d/dashboard/website/mailshots/%d\')">%s</span>', $data['Email Campaign Store Key'], $data['Email Campaign Key'], $data['Email Campaign Name']);
        } else {
            $name = sprintf('<span class="link" onClick="change_view(\'orders/all/dashboard/website/mailshots/%d\')">%s</span>', $data['Email Campaign Key'], $data['Email Campaign Name']);
        }

        switch ($data['Email Campaign State']) {
            case 'InProcess':
                $state = _('Setting up mailing list');
                break;
            case 'ComposingEmail':
                $state = _('Composing email');
                break;
            case 'Ready':
                $state = _('Ready to send');
                break;
            case 'Scheduled':
                $state = _('Scheduled to be send');
                break;

            case 'Sending':
                $state = _('Sending');

                break;
            case 'Cancelled':
                $state = _('Cancelled');
                break;
            case 'Send':
                $state = _('Send');
                break;


            default:
                $state = $data['Email Campaign State'];
                break;
        }


        $adata[] = array(
            'id'   => (integer)$data['Email Campaign Key'],
            'date' => strftime("%a %e %b %Y", strtotime($data['Email Campaign Last Updated Date'].' +0:00')),


            'name'   => $name,
            'state'  => $state,
            'emails' => number($data['Email Campaign Number Estimated Emails']),


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


function refund_new_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $items = 0;


    $adata = array();


    $sql = sprintf(
        "SELECT `Order No Product Transaction Fact Key`,`Transaction Description`,`Transaction Net Amount`,`Transaction Type` ,`Currency Code`FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d ", $_data['parameters']['parent_key']

    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $amount = $row['Transaction Net Amount'];

            if ($amount > 0) {

                $refund_net = sprintf('<input class="new_refund_item %s" style="width: 80px" transaction_type="onptf" transaction_id="%d"  max="%f"  />', ($amount <= 0 ? 'hide' : ''), $row['Order No Product Transaction Fact Key'], $amount);

                $adata[] = array(

                    'id'          => 'onptf_'.$row['Order No Product Transaction Fact Key'],
                    'code'        => '',
                    'description' => $row['Transaction Description'],
                    'quantity'    => '',
                    'refund_net'  => $refund_net,

                    'net' => sprintf('<span class="new_refund_order_item_net button "  amount="%f">%s</span>', $amount, money($amount, $row['Currency Code'])),


                );
                $items++;
            }

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    foreach ($db->query($sql) as $data) {


        if ($data['Order Transaction Amount'] > 0) {

            $units    = $data['Product Units Per Case'];
            $name     = $data['Product History Name'];
            $price    = $data['Product History Price'];
            $currency = $data['Product Currency'];


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $quantity = sprintf(
                    '<span class="new_refund_ordered_quantity button"  refunded_qty="0" unit_amount="%f"  max_qty="%f" max_amount="%f"   >', ($data['Order Quantity'] > 0 ? $data['Order Transaction Amount'] / $data['Order Quantity'] : 0),
                    $data['Order Transaction Amount'], $data['Order Quantity']
                ).number($data['Order Quantity']).'</span>';


            $refund_net = sprintf(
                '<input class="new_refund_item %s item" style="width: 80px"  transaction_type="otf" transaction_id="%d"  max="%f"  />', ($data['Order Transaction Amount'] <= 0 ? 'hide' : ''), $data['Order Transaction Fact Key'], $data['Order Transaction Amount']
            );

            $adata[] = array(

                'id'          => (integer)$data['Order Transaction Fact Key'],
                'product_pid' => (integer)$data['Product ID'],
                'code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product Code']),
                'description' => $description,
                'quantity'    => $quantity,
                'refund_net'  => $refund_net,

                'net' => sprintf('<span class="new_refund_order_item_net button  " amount="%f" >%s</span>', $data['Order Transaction Amount'], money($data['Order Transaction Amount'], $data['Order Currency Code'])),


            );

            $items++;
        }

    }

    $rtext = sprintf(
        ngettext('%s charged transaction', '%s charged transactions', $items), number($items)
    );

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


function refund_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';
    include_once 'utils/geography_functions.php';

    include_once 'prepare_table/init.php';
    include_once 'class.Invoice.php';


    $invoice = new Invoice($_data['parameters']['parent_key']);
    if (in_array(
        $invoice->data['Invoice Delivery Country Code'], get_countries_EC_Fiscal_VAT_area($db)
    )) {
        $print_tariff_code = false;
    } else {
        $print_tariff_code = true;
    }


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    // print $sql;

    $adata = array();
    foreach ($db->query($sql) as $data) {

        $net = money(
            (-1 * $data['Order Transaction Amount']), $data['Invoice Currency Code']
        );

        $tax    = money(
            ($data['Invoice Transaction Item Tax Amount']), $data['Invoice Currency Code']
        );
        $amount = money(
            ($data['Invoice Transaction Gross Amount'] - $data['Invoice Transaction Total Discount Amount'] + $data['Invoice Transaction Item Tax Amount']), $data['Invoice Currency Code']
        );


        $discount = ($data['Invoice Transaction Total Discount Amount'] == 0
            ? ''
            : percentage(
                $data['Invoice Transaction Total Discount Amount'], $data['Invoice Transaction Gross Amount'], 0
            ));

        $units    = $data['Product Units Per Case'];
        $name     = $data['Product History Name'];
        $price    = $data['Product History Price'];
        $currency = $data['Product Currency'];

        $desc = '';
        if ($units > 1) {
            $desc = number($units).'x ';
        }
        $desc .= ' '.$name;
        if ($price > 0) {
            $desc .= ' <br>'._('Price').': '.money($price, $currency, $_locale).'';
        }

        $description = $desc;


        if ($data['Product RRP'] != 0) {
            $description .= ', '._('RRP').': '.money(
                    $data['Product RRP'], $data['Invoice Currency Code']
                );
        }


        if ($discount != '') {
            $description .= ' '._('Discount').':'.$discount;
        }

        //if ($print_tariff_code and $data['Product Tariff Code'] != '') {
        //    $description .= '<br>'._('Tariff Code').': '.$data['Product Tariff Code'];
        //}


        $quantity = '<span class="italic discreet"><span >~</span>'.number(-1 * $data['Order Transaction Amount'] / $data['Product History Price']).'</span>';


        $adata[] = array(
            'id'          => (integer)$data['Order Transaction Fact Key'],
            'code'        => sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $data['Store Key'], $data['Product ID'], $data['Product History Code']),
            'description' => $description,
            'quantity'    => $quantity,
            'net'         => $net,
            'tax'         => $net,
            'amount'      => $net,


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


function replacement_new_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $items = 0;


    $adata = array();


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;
    foreach ($db->query($sql) as $data) {


        if ($data['Inventory Transaction Quantity'] != 0) {

            $units    = $data['Product Units Per Case'];
            $name     = $data['Product History Name'];
            $price    = $data['Product History Price'];
            $currency = $data['Product Currency'];


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $quantity = sprintf(
                    '<span class="new_replacement_ordered_quantity button"  refunded_qty="0"  max_qty="%f"    >',

                    -1 * $data['Inventory Transaction Quantity']
                ).number(-1 * $data['Inventory Transaction Quantity']).'</span>';


            $refund_net = sprintf(
                '<input class="new_replacement_item %s item" style="width: 80px"  transaction_type="itf" transaction_id="%d"  max="%f"  />', ($data['Inventory Transaction Quantity'] == 0 ? 'hide' : ''), $data['Inventory Transaction Key'],
                -1 * $data['Inventory Transaction Quantity']
            );

            $adata[] = array(

                'id'        => (integer)$data['Inventory Transaction Key'],
                'code'      => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product Code']),
                'reference' => sprintf('<span class="link" onclick="change_view(\'/parts/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),

                'product_description' => $description,
                'description'         => $data['Part Package Description'],
                'quantity'            => $quantity,
                'refund_net'          => $refund_net,
                'quantity_order'      => number($data['Order Quantity']),

                'net' => sprintf('<span class="new_refund_order_item_net button  " amount="%f" >%s</span>', $data['Order Transaction Amount'], money($data['Order Transaction Amount'], $data['Order Currency Code'])),

            );

            $items++;
        }

    }

    $rtext = sprintf(
        ngettext('%s send part', '%s send parts', $items), number($items)
    );

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


function return_new_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $items = 0;


    $adata = array();


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;
    foreach ($db->query($sql) as $data) {


        if ($data['Inventory Transaction Quantity'] != 0) {

            $units    = $data['Product Units Per Case'];
            $name     = $data['Product History Name'];
            $price    = $data['Product History Price'];
            $currency = $data['Product Currency'];


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $quantity = sprintf(
                    '<span class="new_return_ordered_quantity button"  refunded_qty="0"  max_qty="%f"    >',

                    -1 * $data['Inventory Transaction Quantity']
                ).number(-1 * $data['Inventory Transaction Quantity']).'</span>';


            $to_return = sprintf(
                '<input class="new_return_item %s item" style="width: 80px"  transaction_type="itf" transaction_id="%d"  max="%f"  />', ($data['Inventory Transaction Quantity'] == 0 ? 'hide' : ''), $data['Inventory Transaction Key'],
                -1 * $data['Inventory Transaction Quantity']
            );

            $adata[] = array(

                'id'        => (integer)$data['Inventory Transaction Key'],
                'code'      => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product Code']),
                'reference' => sprintf('<span class="link" onclick="change_view(\'/parts/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),

                'product_description' => $description,
                'description'         => $data['Part Package Description'],
                'quantity'            => $quantity,
                'to_return'          => $to_return,
                'quantity_order'      => number($data['Order Quantity']),

                'net' => sprintf('<span class="new_refund_order_item_net button  " amount="%f" >%s</span>', $data['Order Transaction Amount'], money($data['Order Transaction Amount'], $data['Order Currency Code'])),

            );

            $items++;
        }

    }

    $rtext = sprintf(
        ngettext('%s send part', '%s send parts', $items), number($items)
    );

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



function order_all_products($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    // print $sql;
    $adata = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if ($data['otf_data'] == '') {
                $deal_info = '';
                $quantity  = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
            <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
            <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', 0, $data['Product ID'], $data['Product Current Key'], '', ''
                );

                $discounts = '<span id="transaction_discounts_'.$data['Product ID'].'" class="_item_discounts"></span>';

                $net = sprintf('<span  id="transaction_item_net_'.$data['Product ID'].'" class="_order_item_net"></span>');


            } else {

                list(
                    $data['Order Transaction Fact Key'], $data['Order Quantity'], $data['Order Transaction Amount'], $data['Current Dispatching State'], $data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount']
                    ) = preg_split('/\|/', $data['otf_data']);


                $quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
            <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
            <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Current Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
                );


                $deal_info = '<div id="transaction_deal_info_'.$data['Order Transaction Fact Key'].'" class="deal_info">'.$data['Deal Info'].'</div>';

                $discounts_class = 'button';
                $discounts_input = sprintf(
                    '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                    $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Current Key'], percentage($data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount'])
                );

                $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($data['Order Transaction Total Discount Amount'] == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                        $data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount']
                    ).'</span> <span class="'.($data['Order Transaction Total Discount Amount'] == 0 ? 'hide' : '').'">'.money($data['Order Transaction Total Discount Amount'], $data['Product Currency']).'</span></span>';


                $discounts = '<span id="transaction_discounts_'.$data['Product ID'].'" class="_item_discounts">'.$discounts.'</span>';

                $net = sprintf('<span  id="transaction_item_net_'.$data['Product ID'].'" class="_order_item_net">%s</span>', money($data['Order Transaction Amount'], $data['Product Currency']));
            }


            if (is_numeric($data['Product Availability'])) {
                $stock = number($data['Product Availability']);
            } else {
                $stock = '?';
            }


            $units    = $data['Product Units Per Case'];
            $name     = $data['Product Name'];
            $price    = $data['Product Price'];
            $currency = $data['Product Currency'];


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $description .= ' <span style="color:#777">['.$stock.']</span> '.$deal_info;


            if ($data['otf_data'] != '') {
                if ($data['Current Dispatching State'] == 'Out of Stock in Basket') {
                    $description .= '<br> <span class="warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Product out of stock, removed from basket').'</span>';


                }
            }


            $adata[] = array(

                'id'          => (integer)$data['Product ID'],
                'code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Product Store Key'), $data['Product ID'], $data['Product Code']),
                'description' => $description,
                'quantity'    => $quantity,
                'discounts'   => $discounts,


                'net' => $net


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function order_sent_emails($_data, $db, $user) {

    $rtext_label = 'email';
    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);

    // print $sql;
    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Tracking State']) {
                case 'Ready':
                    $state = _('Ready to send');
                    break;
                case 'Sent to SES':
                    $state = _('Sending');
                    break;

                    break;
                case 'Delivered':
                    $state = _('Delivered');
                    break;
                case 'Opened':
                    $state = _('Opened');
                    break;
                case 'Clicked':
                    $state = _('Clicked');
                    break;
                case 'Error':
                    $state = '<span class="warning">'._('Error').'</span>';
                    break;
                case 'Hard Bounce':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Bounced').'</span>';
                    break;
                case 'Soft Bounce':
                    $state = '<span class="warning"><i class="fa fa-exclamation-triangle"></i>  '._('Probable bounce').'</span>';
                    break;
                case 'Spam':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                    break;
                default:
                    $state = $data['Email Tracking State'];
            }

            switch ($data['Order Sent Email Type']) {
                case 'Dispatch Notification':
                    $type = _('Dispatch notification');
                    break;
                case 'Order Notification':
                    $type = _('Order notification');
                    break;
                case 'Replacement Dispatch Notification':
                    $type = _('Replacement dispatch notification');
                    break;
                default:
                    $type = $data['Order Sent Email Type'];

            }


            $type = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d/email/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $type);


            $adata[] = array(
                'id'    => (integer)$data['Email Tracking Key'],
                'state' => $state,

                'type' => $type,
                'date' => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date']." +00:00")),


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


function orders_in_website_purges($_data, $db, $user) {

    $rtext_label = 'purge';
    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Order Basket Purge State']) {
                case 'In Process':
                    $state = _('In process');
                    break;
                case 'Purging':
                    $state = _('Purging');
                    break;
                case 'Finished':
                    $state = _('Finished');
                    break;
                case 'Cancelled':
                    $state = _('Cancelled');
                    break;

                default:
                    $state = $data['Order Basket Purge State'];
            }

            switch ($data['Order Basket Purge Type']) {
                case 'Scheduled':
                    $type = _('Scheduled');
                    break;
                case 'Manual':
                    $type = _('Manual');
                    break;

                default:
                    $type = $data['Order Basket Purge Type'];

            }


            $date = sprintf(
                '<span class="link" onclick="change_view(\'orders/%d/dashboard/website/purges/%d\')"  >%s</span>',
                $data['Order Basket Purge Store Key'], $data['Order Basket Purge Key'], strftime("%a, %e %b %Y %R", strtotime($data['Order Basket Purge Date']." +00:00"))
            );


            if ($data['Order Basket Purge State'] == 'In Process') {
                $orders       = sprintf('<span class="italic discreet" title="%s">%s</span>', _('Estimated'), number($data['Order Basket Purge Estimated Orders']));
                $transactions = sprintf('<span class="italic discreet" title="%s">%s</span>', _('Estimated'), number($data['Order Basket Purge Estimated Transactions']));
                $amount       = sprintf('<span class="italic discreet" title="%s">%s</span>', _('Estimated'), money($data['Order Basket Purge Estimated Amount'], $data['Store Currency Code']));
            } else {
                $orders       = number($data['Order Basket Purge Purged Orders']);
                $transactions = number($data['Order Basket Purge Purged Transactions']);
                $amount       = money($data['Order Basket Purge Purged Amount'], $data['Store Currency Code']);
            }


            $adata[] = array(
                'id'            => (integer)$data['Order Basket Purge Key'],
                'state'         => $state,
                'type'          => $type,
                'inactive_days' => number($data['Order Basket Purge Inactive Days']),
                'orders'        => $orders,
                'transactions'  => $transactions,
                'amount'        => $amount,
                'date'          => $date,


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

function purged_orders($_data, $db, $user) {

    $rtext_label = 'order';


    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Order Basket Purge Order Status']) {

                case('In Process'):
                    $purge_status = _('In process');
                    break;
                case('Purged'):
                    $purge_status = _('Purged');
                    break;
                case('Exculpated'):
                    $purge_status = _('Exculpated');
                    break;
                case('Cancelled'):
                    $purge_status = _('Purge cancelled');
                    break;
                default:
                    $purge_status = $data['Order Basket Purge Order Status'];

            }


            $adata[] = array(
                'id' => (integer)$data['Order Key'],

                'public_id'    => sprintf('<span class="link" onClick="change_view(\'orders/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),
                'purge_status' => sprintf('<span class="purged_status_%d">%s</span>', $data['Order Key'], $purge_status),

                'last_updated_date' => strftime("%a %e %b %Y", strtotime($data['Order Last Updated Date'].' +0:00')),
                'purged_date'       => sprintf(
                    '<span class="purged_date_%d">%s</span>', $data['Order Key'],
                    (($data['Order Basket Purge Purged Date'] == '' or ($data['Order Basket Purge Order Status'] == 'Exculpated')) ? '' : strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Basket Purge Purged Date'].' +0:00')))
                ),


                'customer'   => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'net_amount' => money($data['Order Total Net Amount'], $data['Order Currency']),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function invoices_group_by_customer($_data, $db, $user) {


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


            if ($parameters['parent'] == 'store') {
                $link_format  = '/customers/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);

            } elseif ($parameters['parent'] == 'customer_poll_query_option' or $parameters['parent'] == 'customer_poll_query' or $parameters['parent'] == 'sales_representative') {
                $link_format  = '/customers/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Key']);

            } else {
                $link_format = '/'.$parameters['parent'].'/%d/customer/%d';

                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);

            }


            $adata[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,
                'status'       => $activity,

                'name' => $data['Customer Name'],

                'invoices' => number($data['invoices']),
                'refunds'  => number($data['refunds']),


                'total_amount' => money($data['invoiced_amount'] + $data['refunded_amount'], $data['Store Currency Code']),


            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function order_deals($_data, $db, $user) {

    $rtext_label = 'offer';
    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    //print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


          //  print_r($data);




            if ($data['Deal Key']) {
                $name = sprintf('<span class="link" onclick="change_view(\'deals/%d/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Key'], $data['Deal Name']);




                    if ($data['Order Transaction Deal Pinned'] == 'Yes') {
                        $pin = sprintf('<i class="fa fa-thumbtack"></i>');

                    } else {
                        $pin = sprintf('<i class="fal fa-empty-set super_discreet"></i>');

                    }





                switch ($data['Deal Status']) {
                    case 'Waiting':
                        $status = sprintf(
                            '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                        );
                        break;
                    case 'Active':
                        $status = sprintf(
                            '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                        );
                        break;
                    case 'Suspended':
                        $status = sprintf(
                            '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                        );
                        break;
                    case 'Finish':
                        $status = sprintf(
                            '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                        );
                        break;
                    default:
                        $status = $data['Deal Status'];
                }

            } else {
                $name = '<span class="italic">'._('Custom made').'</span>';
                $pin  = sprintf('<i class="fa fa-thumbtack"></i>');
                $status='';
            }


            // $type = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d/email/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $type);


            $adata[] = array(
                'id'                  => (integer)$data['Order Transaction Fact Key'],
                'name'                => $name,
                'description'         => $data['Deal Info'],
                'current_deal_status'              => $status,
                'pin'                 => $pin,
                'items'               => number($data['items']),
                'bonus'               => number($data['bonus']),
                'discount_percentage' => percentage($data['discount_percentage'], 1),
                'amount_discounted'   => money($data['amount_discounted'], $data['Store Currency Code']),
                'pin'=>$pin
                //'state' => $state,

                //'type' => $type,
                //'date' => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date']." +00:00")),


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


?>
