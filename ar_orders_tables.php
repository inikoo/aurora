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
        orders_in_process_not_paid(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_in_process_paid':
        orders_in_process_paid(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_in_process':
        orders_in_process(get_table_parameters(), $db, $user);
        break;

    case 'orders_in_warehouse':
        orders_in_warehouse(get_table_parameters(), $db, $user);
        break;
    case 'orders_in_warehouse_no_alerts':
        orders_in_warehouse_no_alerts(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_in_warehouse_with_alerts':
        orders_in_warehouse_with_alerts(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_packed_done':
        orders_packed_done(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_approved':
        orders_approved(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_dispatched_today':
        orders_dispatched_today(get_table_parameters(), $db, $user, $account);
        break;


    case 'orders_server':
        orders_server(get_table_parameters(), $db, $user);
        break;
    case 'orders':
        orders(get_table_parameters(), $db, $user);
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

    case 'order.items':
        order_items(get_table_parameters(), $db, $user);
        break;
    case 'order.all_products':
        order_all_products(get_table_parameters(), $db, $user);
        break;
    case 'refund.new.items':
        refund_new_items(get_table_parameters(), $db, $user);
        break;
    case 'refund.new.items_tax':
        refund_new_items_tax(get_table_parameters(), $db, $user, $account);
        break;
    case 'replacement.new.items':
        replacement_new_items(get_table_parameters(), $db, $user);
        break;
    case 'return.new.items':
        return_new_items(get_table_parameters(), $db, $user);
        break;

    case 'replacement.new.items':
        replacment_items(get_table_parameters(), $db, $user);
        break;
    case 'invoice.items':
    case 'refund.items':

        invoice_items(get_table_parameters(), $db, $user);
        break;
    case 'refund.items_tax_only':

        refund_items_tax_only(get_table_parameters(), $db, $user);
        break;
    case 'deleted_invoice.items':
    case 'deleted_refund.items':

        deleted_invoice_items(get_table_parameters(), $db, $user);
        break;

    case 'delivery_note_cancelled.items':
        delivery_note_cancelled_items(get_table_parameters(), $db, $user);
        break;
    case 'delivery_note.items':
        delivery_note_items(get_table_parameters(), $db, $user);
        break;

    case 'delivery_note.picking_aid':
        delivery_note_picking_aid(get_table_parameters(), $db, $user);
        break;


    case 'delivery_note.fast_track_packing':
        delivery_note_fast_track_packing(get_table_parameters(), $db, $user);
        break;

    case 'orders_in_website':
        orders_in_website(get_table_parameters(), $db, $user, $account);
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


function orders_in_process_not_paid($_data, $db, $user, $account) {
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


        $public_id = sprintf(
            '<span class="link"  onclick="change_view(\'/orders/%s/dashboard/submitted_not_paid/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );

        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }

        $adata[] = array(
            'id'        => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key' => (integer)$data['Order Store Key'],
            'public_id' => $public_id,
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


function orders_in_process_paid($_data, $db, $user, $account) {
    $rtext_label = 'order submitted paid';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        $payments = '';
        if ($data['payments'] != '') {
            foreach (preg_split('/,/', $data['payments']) as $payment_data) {
                $payment_data = preg_split('/\|/', $payment_data);

                if (count($payment_data) == 2) {
                    if ($payment_data[1] == 'Accounts') {
                        $payment_name = _('Credit');
                    } else {
                        $payment_name = $payment_data[0];
                    }

                    $payments .= $payment_name.', ';
                }


            }
            $payments = preg_replace('/, $/', '', $payments);

        }


        // include_once 'class.Order.php';

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

        $public_id = sprintf(
            '<span class="link"  onclick="change_view(\'/orders/%s/dashboard/submitted/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );

        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }



        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payments'       => $payments,
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

        $public_id =sprintf(
            '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );
        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }

        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
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


function orders_in_warehouse_no_alerts($_data, $db, $user, $account) {
    $rtext_label = 'order warehouse';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //  print $sql;
    foreach ($db->query($sql) as $data) {


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


        $dn_keys=[];

        if ($data['Order Replacement State'] == 'InWarehouse') {
            $payment_state = '';
            $total_amount  = '';
            $deliveries    = '';

            if ($data['delivery_notes'] != '') {

                foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {


                    $_delivery_note_data = preg_split('/\|/', $delivery_note_data);

                    $dn_keys[]= $_delivery_note_data[0];

                    $deliveries = sprintf(
                        "<span class='padding_right_10 error link' onClick=\"change_view('delivery_notes/%d/%d')\"><i class=\"fa fa-truck   \" ></i> %s</span>", $data['Order Store Key'], $_delivery_note_data[0], $_delivery_note_data[1]

                    );
                }
            }
        } else {
            $payment_state = get_order_formatted_payment_state($data);
            $total_amount  = money($data['Order Total Amount'], $data['Order Currency']);
            $deliveries    = '';


            if ($data['delivery_notes'] != '') {
                foreach (preg_split('/,/', $data['delivery_notes']) as $delivery_note_data) {
                    $_delivery_note_data = preg_split('/\|/', $delivery_note_data);
                    $dn_keys[]= $_delivery_note_data[0];

                    $deliveries = sprintf(
                        "<span class='padding_right_10 link' onClick=\"change_view('delivery_notes/%d/%d')\"><i class=\"fa fa-truck fa-flip-horizontal   \" ></i> %s</span>", $data['Order Store Key'], $_delivery_note_data[0], $_delivery_note_data[1]

                    );
                }
            }

        }

        $public_id=sprintf(
            '<span class="link"  onclick="change_view(\'orders/%s/dashboard/in_warehouse/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );
        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }

        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d" data-pdf_scope_keys="%s" ></i>',$data['Order Key'],htmlspecialchars(json_encode($dn_keys), ENT_QUOTES, 'UTF-8')  ),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['submitted_date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], $data['Order Replacement State'], $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => $total_amount,
            'actions'        => $operations,
            'deliveries'     => $deliveries,
            'waiting_time'   => number($data['waiting_time'])


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


function orders_in_warehouse_with_alerts($_data, $db, $user, $account) {
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

        $public_id=sprintf(
            '<span class="link"  onclick="change_view(\'orders/%s/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );
        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
            'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
            'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
            'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
            'dispatch_state' => get_order_formatted_dispatch_state($data['Order State'], '', $data['Order Key']),
            'payment_state'  => $payment_state,
            'total_amount'   => money($data['Order Total Amount'], $data['Order Currency']),
            'actions'        => $operations,
            'waiting_time'   => number($data['waiting_time'])


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


function orders_packed_done($_data, $db, $user, $account) {


    $rtext_label = 'order packed done';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


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

        $public_id = sprintf(
            '<span class="link"  onclick="change_view(\'orders/%s/dashboard/packed_done/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );

        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }


        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
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


function orders_approved($_data, $db, $user, $account) {
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

        $public_id = sprintf(
            '<span class="link"  onclick="change_view(\'orders/%s/dashboard/approved/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
        );

        if ($data['Order Priority Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-shipping-fast"></i>';
        }

        if ($data['Order Care Level'] != 'Normal') {
            $public_id .= ' <i class="fal fa-fragile"></i>';

        }

        $adata[] = array(
            'id'             => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => $public_id,
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


function orders_dispatched_today($_data, $db, $user, $account) {
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
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'store_key'      => (integer)$data['Order Store Key'],
            'public_id'      => sprintf(
                '<span class="link"  onclick="change_view(\'orders/%s/dashboard/dispatched_today/%d\')" >%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']
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

function orders_in_website($_data, $db, $user, $account) {
    $rtext_label = 'order in basket';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //   print $sql;
    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'           => (integer)$data['Order Key'],
            'checked'   => sprintf('<i class="far fa-square fa-fw button order_select_box" data-order_key="%d"></i>',$data['Order Key']),
            'public_id'    => sprintf(
                '
            <span class="link" onClick="change_view(\'orders/%s/dashboard/website/%d\')">%s</span>', ($_data['parameters']['parent'] == 'store' ? $_data['parameters']['parent_key'] : 'all'), $data['Order Key'], $data['Order Public ID']


            ),
            'date'         => strftime("%e %b %Y", strtotime($data['Order Created Date'].' +0:00')),
            'last_updated' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated by Customer'].' +0:00')),
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
                $state = _('Packed & Closed');
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



    if ($parameters['parent'] == 'store' ) {
        $link_format = '/orders/%d/%d';
    }  else   if ($parameters['parent'] == 'customer_client' ){
        $customer_client=get_object('customer_client',$parameters['parent_key']);
        $parameters['parent_key']=$customer_client->get('Customer Client Customer Key');
        $link_format = '/customer/%d/order/%d';
    }else {
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
                    $state = _('Packed & Closed');
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

            if($data['Order Customer Client Key']>0){

                if($data['Customer Client Code']!=''){
                    $client_code=$data['Customer Client Code'];
                }else{
                    $client_code=sprintf('<span class="italic">%05d</span>',$data['Order Customer Client Key']);
                }
                $client=sprintf('<span class="link" onClick="change_view(\'customers/%d/%d/client/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Client Key'],$client_code);
            }else{
                $client='<span class="very_discreet italic">'._('Not set').'</span>>';
            }


            $adata[] = array(
                'id' => (integer)$data['Order Key'],

                'public_id' => sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%s</span>', $parameters['parent_key'], $data['Order Key'], $data['Order Public ID']),
                'state'     => $state,

                'date'           => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
                'last_date'      => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
                'customer'       => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'client'       => $client,

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


        if ($data['Product Availability State'] == 'OnDemand') {
            $stock = _('On demand');

        } else {

            if (is_numeric($data['Product Availability'])) {
                $stock = number($data['Product Availability']);
            } else {
                $stock = '?';
            }
        }


        if ($data['Deal Info'] != '') {
            $deal_info_data = preg_split('/\|\|/', $data['Deal Info']);


            $deal_info = '<div id="transaction_deal_info_'.$data['Order Transaction Fact Key'].'" class="deal_info">'.($deal_info_data[1] == 'Yes' ? '<i class="fas fa-thumbtack"></i> ' : '').$deal_info_data[0].'</div>';

        } else {
            $deal_info = '';
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


        if ($data['Product UN Number']) {

            $description .= ' <span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Product UN Number'].'</span>';
        }

        if ($price > 0) {
            $description .= ' ('.money($price, $currency, $_locale).')';
        }


        $description .= ' <span style="color:#777">['.$stock.']</span> '.$deal_info;


        if ($data['Current Dispatching State'] == 'Out of Stock in Basket') {
            $description .= '<br> <span class="warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Product out of stock, removed from basket').'</span>';
            //$quantity    = number($data['Out of Stock Quantity']);
            //$class = 'out_of_stock';

        }


        if ($data['Order Quantity'] != $data['Delivery Note Quantity'] and in_array(
                $customer_order->get('Order State'), array(
                                                       'PackedDone',
                                                       'Approved',
                                                       'Dispatched'
                                                   )
            )) {
            $quantity = '<span class="discreet " title="'.sprintf(_('%s ordered by customer'), number($data['Order Quantity'])).'" >(<span class="strikethrough">'.number($data['Order Quantity']).'</span>)</span> '.number($data['Delivery Note Quantity']);
            $weight   = weight($data['Product Package Weight'] * $data['Delivery Note Quantity'], 'Kg', 3, false, true);
        } else {
            $quantity = number($data['Order Quantity']);
            $weight   = weight($data['Product Package Weight'] * $data['Order Quantity'], 'Kg', 3, false, true);

        }

        if (in_array(
            $customer_order->get('Order State'), array(
                                                   'InProcess',
                                                   'InWarehouse',
                                                   'InBasket'
                                               )
        )) {
            $quantity_edit = sprintf(
                '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   >
            <i onClick="save_item_qty_change(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
            <input class="order_qty width_50" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_item_qty_change(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>', $data['Order Transaction Fact Key'], $data['Product ID'], $data['Product Key'], $data['Order Quantity'] + 0, $data['Order Quantity'] + 0
            );


        } else {


            $quantity_edit = '';
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

            'id'            => (integer)$data['Order Transaction Fact Key'],
            'code'          => sprintf('<span class="item_code"><span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span></span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product History Code']),
            'description'   => '<span class="item_description">'.$description.'</span>',
            'quantity'      => $quantity,
            'quantity_edit' => $quantity_edit,

            'discounts' => '<span id="transaction_discounts_'.$data['Order Transaction Fact Key'].'" class="_item_discounts">'.$discounts.'</span>',

            'weight'         => $weight,
            'package_weight' => weight($data['Product Package Weight'], 'Kg', 3, false, true),
            'tariff_code'    => $data['Product Tariff Code'],

            'net' => sprintf('<span  id="transaction_item_net_'.$data['Order Transaction Fact Key'].'" class="_order_item_net">%s</span>', money($data['Order Transaction Amount'], $data['Order Currency Code'])),


        );

    }


    $sql = sprintf(
        "select `Charge Key` ,`Charge Name`,`Charge Scope` ,`Charge Store Key`,`Charge Description` ,`Charge Metadata` , (select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Charges'  and `Transaction Type Key`=`Charge Key`  limit 1  ) as onptf_key   from `Charge Dimension` where `Charge Store Key`=%d and `Charge Trigger`  = 'Selected by Customer' ",
        $customer_order->id, $customer_order->get('Order Store Key')
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $onptf_key = $row['onptf_key'];

            $adata[] = array(

                'id'            => 'Charge_'.$row['Charge Key'],
                'code'          => sprintf('<span class="link"  onclick="change_view(\'/store/%d/charge/%d\')">%s</span>', $row['Charge Store Key'], $row['Charge Key'], $row['Charge Name']),
                'description'   => $row['Charge Description'].' ('.money($row['Charge Metadata'], $customer_order->get('Currency Code')).')',
                'quantity'      => '',
                'quantity_edit' => '<i onclick="toggle_selected_by_customer_charge(this)"  data-charge_key="'.$row['Charge Key'].'" data-onptf_key="'.$onptf_key.'"   style="margin-right: 20px" class="'.($onptf_key > 0 ? 'fa-toggle-on' : 'fa-toggle-off')
                    .' far button "></i>',

                'discounts' => '',


                'net' => sprintf('<span  class="  selected_by_customer_charge">%s</span>', ($onptf_key > 0 ? money($row['Charge Metadata'], $customer_order->get('Currency Code')) : '')),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $sql = sprintf(
        "select `Deal Name`,`Order Transaction Deal Key`,`Deal Component Allowance`,DCD.`Deal Component Key` ,`Order Transaction Deal Metadata` from `Order Transaction Deal Bridge` OTDB left join 
    `Deal Component Dimension` DCD on (DCD.`Deal Component Key`=OTDB.`Deal Component Key`) left join  `Deal Dimension` DD on (DCD.`Deal Component Deal Key`=DD.`Deal Key`) 
    
    where `Order Key`=%d and `Deal Component Allowance Type`='Get Free Customer Choose'  ", $customer_order->id
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            //  print_r($row);
            $allowances         = json_decode($row['Deal Component Allowance'], true);
            $selected_allowance = json_decode($row['Order Transaction Deal Metadata'], true);


            if (!empty($selected_allowance['selected'])) {
                $selected = $selected_allowance['selected'];
            } else {
                $selected = $allowances['default'];
            }

            //  print_r($allowances);

            $options = '<span data-selected="'.$selected.'"  data-deal_component_key="'.$row['Deal Component Key'].'"  data-order_transaction_deal_bridge_key="'.$row['Order Transaction Deal Key'].'" class="deal_component_choose_by_customer">';
            foreach ($allowances['options'] as $product_id => $option) {
                $options .= '<span onclick="select_deal_component_choose_by_customer(this)" data-product_id="'.$product_id.'" class="deal_component_item deal_component_item_'.$product_id.'   button margin_right_30"><i class="far '.($selected == $product_id
                        ? 'fa-dot-circle' : 'fa-circle').' "></i> <span  title="'.$option['Description'].'">'.$option['Code'].'</span></span>';
            }

            $options .= '</span>';

            $adata[] = array(

                'id'            => 'Choose_'.$row['Order Transaction Deal Key'],
                'code'          => $row['Deal Name'],
                'description'   => $options,
                'quantity'      => '',
                'quantity_edit' => '',

                'discounts' => '',


                'net' => ''


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

function refund_items_tax_only($_data, $db, $user) {


    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $sql = sprintf(
        "SELECT  `Tax Category Code`,`Order No Product Transaction Metadata`,`Currency Code` ,`Transaction Description`,`Order No Product Transaction Fact Key` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  ", $_data['parameters']['parent_key']
    );


    $adata = array();
    foreach ($db->query($sql) as $data) {


        $tax = 0;

        if ($data['Order No Product Transaction Metadata'] != '') {
            if ($metadata = json_decode($data['Order No Product Transaction Metadata'], true)) {
                if (isset($metadata['TORA'])) {
                    $tax = $metadata['TORA'];
                }
            }
        }
        $tax = money($tax, $data['Currency Code']);

        $description = $data['Transaction Description'];
        $adata[]     = array(
            'id'          => (integer)$data['Order No Product Transaction Fact Key'],
            'code'        => '',
            'description' => $description,
            'tax'         => $tax,
        );

    }


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    foreach ($db->query($sql) as $data) {


        $tax = 0;

        if ($data['Order Transaction Metadata'] != '') {
            if ($metadata = json_decode($data['Order Transaction Metadata'], true)) {
                if (isset($metadata['TORA'])) {
                    $tax = $metadata['TORA'];
                }
            }
        }
        $tax   = money($tax, $data['Order Currency Code']);
        $units = $data['Product Units Per Case'];
        $name  = $data['Product History Name'];

        $description = '';
        if ($units > 1) {
            $description = number($units).'x ';
        }
        $description .= ' '.$name;

        $adata[] = array(
            'id'          => (integer)$data['Order Transaction Fact Key'],
            'code'        => sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $data['Store Key'], $data['Product ID'], $data['Product History Code']),
            'description' => $description,
            'tax'         => $tax,
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


    $invoice = get_object('Invoice', $_data['parameters']['parent_key']);
    $type    = $invoice->get('Invoice Type');


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    // print $sql;

    if ($type == 'Invoice') {
        $factor = 1;
    } else {
        $factor = -1;
    }

    $adata = array();
    foreach ($db->query($sql) as $data) {

        $net = money(
            ($factor * $data['Order Transaction Amount']), $data['Order Currency Code']
        );


        $tax    = money(
            ($data['Order Transaction Amount'] * $data['Transaction Tax Rate']), $data['Order Currency Code']
        );
        $amount = money(
            ($data['Order Transaction Amount'] + ($data['Order Transaction Amount'] * $data['Transaction Tax Rate'])), $data['Order Currency Code']
        );


        $discount = ($data['Order Transaction Total Discount Amount'] == 0
            ? ''
            : percentage(
                $data['Order Transaction Total Discount Amount'], $data['Order Transaction Gross Amount'], 0
            ));


        $units    = $data['Product Units Per Case'];
        $name     = $data['Product History Name'];
        $price    = $data['Product History Price'];
        $currency = $data['Product Currency'];

        $description = '';
        if ($units > 1) {
            $description = number($units).'x ';
        }
        $description .= ' '.$name;


        $price = money($price, $currency, $_locale);


        if ($discount != '') {
            $description .= ' '._('Discount').':'.$discount;
        }


        if ($type == 'Invoice') {
            $quantity = number($data['Delivery Note Quantity']);

        } else {
            $quantity = '<span class="italic discreet"><span >~</span>'.number(-1 * $data['Order Transaction Amount'] / $data['Product History Price']).'</span>';

        }


        $adata[] = array(
            'id'          => (integer)$data['Order Transaction Fact Key'],
            'code'        => sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $data['Store Key'], $data['Product ID'], $data['Product History Code']),
            'description' => $description,
            'quantity'    => $quantity,
            'price'       => $price,

            'net'    => $net,
            'tax'    => $tax,
            'amount' => $amount,


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


function deleted_invoice_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';
    $invoice     = get_object('Invoice_Deleted', $_data['parameters']['parent_key']);


    $adata   = array();
    $counter = 0;
    foreach ($invoice->items as $data) {

        $data['id'] = $counter++;


        $adata[] = $data;

    }

    $rtext = ngettext('item', 'items', $counter);

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => 'id',
            'sort_dir'      => 'desc',
            'total_records' => $counter

        )
    );
    echo json_encode($response);
}


function delivery_note_fast_track_packing($_data, $db, $user) {


    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    //   $dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";


    $no_picked_amount       = 0;
    $no_picked_number_items = 0;
    $total_amount           = 0;
    $total_number_items     = 0;

    $adata         = array();
    $currency_code = '';

    foreach ($db->query($sql) as $data) {


        $currency_code = $data['Order Currency Code'];

        $total_number_items++;

        $pending = $data['Required']+$data['Given'];


        $available = $pending - $data['cant_pick'];

        if ($data['Location Key'] == 1) {

            $data['Quantity On Hand']           -= $data['Part Current On Hand Stock'];
            $data['Part Current On Hand Stock'] = 0;
            $available                          = 0;
            $data['Part Distinct Locations']    = $data['Part Distinct Locations'] - 1;
            $data['pl_ok']                      = '';
        }


        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</span>';
        }


        //$description=$data['Part Current On Hand Stock'];

        if ($pending != $available) {
            $_quantity = '<span class="strikethrough  discreet">'.number($pending).'</span> <span class="error discreet item_quantity_fast_track_packing button  "  qty="'.$available.'" >'.number($available).'</error>';

        } else {
            $_quantity = sprintf('<span class="item_quantity_fast_track_packing button" qty="%s"   >%s</span>', $pending, number($pending));

        }
        $quantity = '<div class="quantity_components">'.$_quantity.'</div>';

        $location = '<div class="location_components" style="margin-top: 2px">'.get_delivery_note_fast_track_packing_item_location(
                ($data['pl_ok'] == '' ? 'No' : 'Yes'), $pending, $data['Quantity On Hand'], $data['Date Picked'],

                                                       $data['Location Key'], $data['Location Code'],

                                                       $data['Part Current On Hand Stock'], $data['Part Distinct Locations'],

                                                       $data['Part SKU'], $data['Inventory Transaction Key'], $_data['parameters']['parent_key']
            ).'</div>';


        /*
        if ($data['Picked'] == $data['quantity']) {
            $picked_info = '<i class="fa fa-fw fa-check success" aria-hidden="true"></i>';

        } else {
            $picked_info = '';
        }

*/


        $total_pending = $pending;


        $total_amount += $data['Order Transaction Amount'];
        if ($data['Quantity On Hand'] < $total_pending) {


            $effective_stock = ($data['Quantity On Hand'] < 0 ? 0 : $data['Quantity On Hand']);
            $formatted_diff  = $effective_stock - $total_pending;

            $no_picked_qty = $total_pending - $effective_stock;
            if ($total_pending > 0) {
                $no_picked_amount += ($data['Order Transaction Amount'] * $no_picked_qty / $total_pending);

            }


            $status_icon = 'error fa-exclamation-circle';
            $no_picked_number_items++;


        } else {
            $formatted_diff = '';
            $no_picked_qty  = 0;
            $status_icon    = 'success  fa-check-circle';
        }


        $picked_offline_status = sprintf(
            '<span class="picked_offline_status_notes error" data-value_per_qty="%.4f"  data-no_picked_qty="%.2f" >%s</span> <i  class="picked_offline_status  fa %s " ></i>', ($total_pending == 0 ? 0 : $data['Order Transaction Amount'] / $total_pending), $no_picked_qty,
            $formatted_diff, $status_icon
        );


        $picked_offline_input = '<div class="picked_quantity_components" data-pending="'.$pending.'">'.get_delivery_note_fast_track_packing_input(
                ($data['pl_ok'] == '' ? 'No' : 'Yes'), $pending, 0, 0, $pending, $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Part Current On Hand Stock'], $data['Location Key']

            ).'</div>';


        $reference = sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']);


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
            'id' => (integer)$data['Inventory Transaction Key'],

            'reference'   => $reference,
            'description' => $description,
            'quantity'    => $quantity,

            'picked_offline_status' => $picked_offline_status,


            'location'             => $location,
            'picked_offline_input' => $picked_offline_input


        );

    }


    if ($total_number_items > 0) {
        $rtext .= '<span data-currency="'.$currency_code.'" data-total_items="'.$total_number_items.'"  data-total_items_amount="'.$total_amount.'"    class="not_picked_info small '.($no_picked_number_items == 0 ? 'hide' : '')
            .' padding_left_10 error"><i class="fa fa-exclamation-circle"></i> '._('not fully picked').': <span class="items_with_problems ">'.$no_picked_number_items.'</span> <span class="strong  percentage_items_with_problems">('.percentage(
                $no_picked_number_items, $total_number_items
            ).')</span>
        <span class=padding_left_10> <span ><span class="items_with_problems_amount">'.money($no_picked_amount, $currency_code).'</span> <span class="strong percentage_items_with_problems_amount">('.percentage($no_picked_amount, $total_amount)
            .')</span></span></span></span>';

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


function delivery_note_picking_aid($_data, $db, $user) {

    //print_r($_data);

    // todo show when it work
    exit();

    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    //$dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";
    // print $sql;
    $adata = array();
    foreach ($db->query($sql) as $data) {


        $to_pack = $data['quantity'] - $data['Packed'];
        /*

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
                 $notes .= _('not picked (other)').' '.number($data['No Picked Other']).'<br/>';
             }
     */

        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <small style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</small>';
        }


        $quantity = '<div class="quantity_components">'.get_item_quantity($data['quantity'], $data['to_pick']).'</div>';

        $picked = '<div class="picked_quantity_components">'.get_item_picked(
                $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Reference'],
                base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']

            ).'</div>';


        $packed   = '<div class="packed_quantity_components">'.get_item_packed($to_pack, $data['Inventory Transaction Key'], $data['Part SKU'], $data['Packed']).'</div>';
        $location = '<div class="location_components">'.get_item_location(
                $data['pending'], $data['Quantity On Hand'], $data['Date Picked'],

                $data['Location Key'], $data['Location Code'],

                $data['Part Current On Hand Stock'], $data['Part SKO Barcode'], $data['Part Distinct Locations'],

                $data['Part SKU'], $data['Inventory Transaction Key'], $_data['parameters']['parent_key']
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
                $data['quantity'], $data['pending'], $data['Quantity On Hand'], $data['Inventory Transaction Key'], $data['Part SKU'], $data['Location Key'], $data['Location Code'], $data['Picked'], $data['Part Current On Hand Stock'], $data['Part SKO Barcode'],
                $data['Part Reference'], base64_encode($data['Part Package Description'].($data['Picking Note'] != '' ? ' <span>('.$data['Picking Note'].'</span>' : '')), $data['Part Main Image Key']
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
            'overview_problem' => '<span class="'.($data['Out of Stock'] == 0 ? 'very_discreet' : 'error').'">'.number($data['Out of Stock']).'</span>',


            'packed'               => $packed,
            'picked'               => $picked,
            'picked_info'          => $picked_info,
            'location'             => $location,
            'picked_offline_input' => $picked_offline_input,

            'overview_state' => $state


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


    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff


    $rtext_label = 'item';


    //$dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";

   // print $sql;
    $adata = array();


    foreach ($db->query($sql) as $data) {


        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <small style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</small>';
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
                $state_packing = sprintf('<i class="fa-arrow-alt-circle-down error discreet fa fa-fw " title="%s"></i>', _('Packing'));
                $state_picking = '';
            } else {
                $state_packing = '';

            }


        }

        $state = '<span class="padding_left_20">'.$state_picking.' '.$state_packing.'</span>';


        $adata[] = array(
            'id'                => (integer)$data['Part SKU'],
            'reference'         => sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            'description'       => $description,
            'overview_required' => number($data['Required']).($data['Given'] != 0 ? '<i class="fa fa-gift padding_left_10"></i> '.number($data['Given']) : ''),
            'overview_packed'   => number($data['Packed']),
            'overview_picked'   => number($data['Picked']),
            'overview_problem'  => '<span class="'.($data['Out of Stock'] == 0 ? 'very_discreet' : 'error').'">'.number($data['Out of Stock']).'</span>',
            'overview_state'    => $state


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


    if ($customer_order->get('State Index') == 90) {
        $prefil_out_off_stock = true;

    } else {
        $prefil_out_off_stock = false;

    }


    $sql = sprintf(
        "SELECT `Order No Product Transaction Fact Key`,`Transaction Description`,`Transaction Net Amount`,`Transaction Type` ,`Currency Code`FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d ", $_data['parameters']['parent_key']
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $amount = $row['Transaction Net Amount'];

            if ($amount > 0) {

                $refund_net = sprintf('<input class="new_refund_item %s" style="width: 80px" transaction_type="onptf" transaction_id="%d"  max="%f"  />', ($amount <= 0 ? 'hide' : ''), $row['Order No Product Transaction Fact Key'], $amount);

                $description = $row['Transaction Description'].'<div class="hide small discreet" id="feedback_description_onptf_'.$row['Order No Product Transaction Fact Key'].'"></div>';

                $feedback = '<span data-empty_label="'._('Set feedback').'"  data-type="onptf" data-key="'.$row['Order No Product Transaction Fact Key'].'"  id="set_onptf_feedback_'.$row['Order No Product Transaction Fact Key']
                    .'" class="set_otf_feedback_button button very_discreet_on_hover italic hide padding_right_5">'._('Set feedback').'</span>';


                $adata[] = array(

                    'id'          => 'onptf_'.$row['Order No Product Transaction Fact Key'],
                    'code'        => '',
                    'description' => $description,
                    'quantity'    => '',
                    'refund_net'  => $refund_net,
                    'net'         => sprintf('<span class="new_refund_order_item_net button "  amount="%f">%s</span>', $amount, money($amount, $row['Currency Code'])),
                    'feedback'    => $feedback

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


            if ($prefil_out_off_stock) {
                $prefilled_value = $data['Order Transaction Out of Stock Amount'];

            } else {
                $prefilled_value = '';

            }

            $refund_net = sprintf(
                '<input class="new_refund_item %s item" style="width: 80px"  transaction_type="otf" transaction_id="%d"  max="%f" value="%s" />', ($data['Order Transaction Amount'] <= 0 ? 'hide' : ''), $data['Order Transaction Fact Key'],
                $data['Order Transaction Amount'], $prefilled_value
            );

            $feedback = '<span data-empty_label="'._('Set feedback').'" data-type="otf" data-key="'.$data['Order Transaction Fact Key'].'"  id="set_otf_feedback_'.$data['Order Transaction Fact Key']
                .'" class="set_otf_feedback_button button very_discreet_on_hover italic hide padding_right_5">'._('Set feedback').'</span>';


            $description = $description.'<div class="hide small discreet" id="feedback_description_otf_'.$data['Order Transaction Fact Key'].'"></div>';


            $adata[] = array(

                'id'          => (integer)$data['Order Transaction Fact Key'],
                'code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product History Code']),
                'description' => $description,
                'quantity'    => $quantity,
                'refund_net'  => $refund_net,
                'net'         => sprintf('<span class="new_refund_order_item_net button  " amount="%f" >%s</span>', $data['Order Transaction Amount'], money($data['Order Transaction Amount'], $data['Order Currency Code'])),
                'feedback'    => $feedback

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


function refund_new_items_tax($_data, $db, $user, $account) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $items = 0;

    $bigger_tax_item = 0;

    $bigger_tax = 0;

    $adata     = array();
    $total_tax = 0;

    $sql = sprintf(
        "SELECT *,`Order No Product Transaction Fact Key`,`Transaction Description`,`Transaction Net Amount`,`Transaction Type` ,`Currency Code` ,`Tax Category Rate` FROM `Order No Product Transaction Fact` ONPTF  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=ONPTF.`Tax Category Code` and `Tax Category Country Code`=%s)  WHERE `Order Key`=%d ",
        prepare_mysql($account->get('Account Country Code')), $_data['parameters']['parent_key']

    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $amount    = round($row['Transaction Net Amount'] * $row['Tax Category Rate'], 2);
            $total_tax += $amount;
            if ($amount > 0) {


                if ($bigger_tax < $amount) {
                    $bigger_tax_item = $items;
                    $bigger_tax      = $amount;
                }
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


            $tax       = round($data['Transaction Tax Rate'] * $data['Order Transaction Amount'], 2);
            $total_tax += $tax;


            if ($bigger_tax < $tax) {
                $bigger_tax_item = $items;
                $bigger_tax      = $tax;
            }
            $items++;
        }

    }


    $diff = round($customer_order->get('Order Total Tax Amount') - $total_tax, 2);


    $items = 0;


    $adata     = array();
    $total_tax = 0;

    $sql = sprintf(
        "SELECT *,`Order No Product Transaction Fact Key`,`Transaction Description`,`Transaction Net Amount`,`Transaction Type` ,`Currency Code` ,`Tax Category Rate` FROM `Order No Product Transaction Fact` ONPTF  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=ONPTF.`Tax Category Code` and `Tax Category Country Code`=%s)  WHERE `Order Key`=%d ",
        prepare_mysql($account->get('Account Country Code')), $_data['parameters']['parent_key']
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $amount = round($row['Transaction Net Amount'] * $row['Tax Category Rate'], 2);


            if ($diff != 0 and $items == $bigger_tax_item) {
                $amount = $amount + $diff;
            }


            if ($amount > 0) {

                $refund_tax = sprintf('<input class="new_refund_item_tax %s" style="width: 80px" transaction_type="onptf_tax" transaction_id="%d"  max="%f"  />', ($amount <= 0 ? 'hide' : ''), $row['Order No Product Transaction Fact Key'], $amount);

                $total_tax += $amount;

                $adata[] = array(

                    'id'          => 'onptf_'.$row['Order No Product Transaction Fact Key'],
                    'code'        => '',
                    'description' => $row['Transaction Description'],
                    'quantity'    => '',
                    'refund_tax'  => $refund_tax,

                    'tax' => sprintf('<span class="new_refund_order_item_tax button "  amount="%f">%s</span>', $amount, money($amount, $row['Currency Code'])),


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


            $tax = round($data['Transaction Tax Rate'] * $data['Order Transaction Amount'], 2);


            if ($diff != 0 and $items == $bigger_tax_item) {
                $tax = $tax + $diff;
            }


            $quantity = sprintf(
                    '<span class="new_refund_tax_ordered_quantity button"  refunded_qty="0" unit_amount="%f"  max_qty="%f" max_amount="%f"   >', ($data['Order Quantity'] > 0 ? $tax / $data['Order Quantity'] : 0), $tax, $data['Order Quantity']
                ).number($data['Order Quantity']).'</span>';


            $refund_tax = sprintf(
                '<input class="new_refund_item_tax %s item" style="width: 80px"  transaction_type="otf_tax" transaction_id="%d"  max="%f"  />', ($tax <= 0 ? 'hide' : ''), $data['Order Transaction Fact Key'], $tax
            );

            $adata[] = array(

                'id'          => (integer)$data['Order Transaction Fact Key'],
                'product_pid' => (integer)$data['Product ID'],
                'code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product History Code']),
                'description' => $description,
                'quantity'    => $quantity,
                'refund_tax'  => $refund_tax,

                'tax' => sprintf('<span class="new_refund_order_item_tax button  " amount="%f" >%s</span>', $tax, money($tax, $data['Order Currency Code'])),


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


function replacement_new_items($_data, $db, $user) {

    global $_locale;// fix this locale stuff

    $rtext_label = 'item';

    include_once 'prepare_table/init.php';


    $customer_order = get_object('Order', $_data['parameters']['parent_key']);


    $items = 0;


    $adata = array();


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

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


            $feedback = '<span data-empty_label="'._('Set feedback').'" data-itf="'.$data['Inventory Transaction Key'].'"  id="set_feedback_'.$data['Inventory Transaction Key'].'" class="set_feedback_button button very_discreet_on_hover italic hide padding_right_5">'._(
                    'Set feedback'
                ).'</span>';

            $description = $data['Part Package Description'].'<div class="hide small discreet" id="feedback_description_'.$data['Inventory Transaction Key'].'"></div>';
            $adata[]     = array(

                'id'        => (integer)$data['Inventory Transaction Key'],
                'code'      => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product History Code']),
                'reference' => sprintf('<span class="link" onclick="change_view(\'/parts/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),

                'product_description' => $description,
                'description'         => $description,
                'quantity'            => $quantity,
                'refund_net'          => $refund_net,
                'quantity_order'      => number($data['Order Quantity']),

                'net'      => sprintf('<span class="new_refund_order_item_net button  " amount="%f" >%s</span>', $data['Order Transaction Amount'], money($data['Order Transaction Amount'], $data['Order Currency Code'])),
                'feedback' => $feedback
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
                'code'      => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $customer_order->get('Order Store Key'), $data['Product ID'], $data['Product History Code']),
                'reference' => sprintf('<span class="link" onclick="change_view(\'/parts/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),

                'product_description' => $description,
                'description'         => $data['Part Package Description'],
                'quantity'            => $quantity,
                'to_return'           => $to_return,
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
                '<span class="link" onclick="change_view(\'orders/%d/dashboard/website/purges/%d\')"  >%s</span>', $data['Order Basket Purge Store Key'], $data['Order Basket Purge Key'], strftime("%a, %e %b %Y %R", strtotime($data['Order Basket Purge Date']." +0:00"))
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

                'last_updated_date' => strftime("%a %e %b %Y", strtotime($data['Order Last Updated by Customer'].' +0:00')),
                'purged_date'       => sprintf(
                    '<span class="purged_date_%d">%s</span>', $data['Order Key'],
                    (($data['Order Basket Purge Purged Date'] == '' or ($data['Order Basket Purge Order Status'] == 'Exculpated')) ? '' : strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Basket Purge Purged Date'].' +0:00')))
                ),


                'customer'   => sprintf('<span class="link" onClick="change_view(\'customers/%d/%d\')">%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'net_amount' => money($data['Order Total Net Amount'], $data['Order Currency']),


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


    $adata     = array();
    $deal_keys = array();

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


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
                $name   = '<span class="italic">'._('Custom made').'</span>';
                $pin    = sprintf('<i class="fa fa-thumbtack"></i>');
                $status = '';
            }


            // $type = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d/email/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $type);

            $deal_keys[$data['Deal Key']] = $data['Deal Key'];


            $adata[] = array(
                'id'                  => (integer)$data['Order Transaction Fact Key'],
                'name'                => $name,
                'description'         => $data['Deal Term Allowances Label'],
                'current_deal_status' => $status,
                'pin'                 => $pin,
                'items'               => number($data['items']),
                'bonus'               => number($data['bonus']),
                'discount_percentage' => percentage($data['discount_percentage'], 1),
                'amount_discounted'   => money($data['amount_discounted'], $data['Store Currency Code']),
                'pin'                 => $pin
                //'state' => $state,

                //'type' => $type,
                //'date' => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date']." +00:00")),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf("select `Deal Name` ,`Deal Status` ,`Deal Store Key`,B.`Deal Key`,`Deal Term Allowances Label` from `Order Deal Bridge`  B left join `Deal Dimension` D  on (D.`Deal Key`=B.`Deal Key`)  where `Order Key`=%d  ", $_data['parameters']['parent_key']);
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            if (!in_array($data['Deal Key'], $deal_keys)) {

                if ($data['Deal Key']) {
                    $name = sprintf('<span class="link" onclick="change_view(\'deals/%d/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Key'], $data['Deal Name']);

                    /*// to do
                                        if ($data['Order Transaction Deal Pinned'] == 'Yes') {
                                            $pin = sprintf('<i class="fa fa-thumbtack"></i>');

                                        } else {
                                            $pin = sprintf('<i class="fal fa-empty-set super_discreet"></i>');

                                        }
                    */
                    $pin = sprintf('<i class="fa fa-thumbtack"></i>');
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
                    $name   = '<span class="italic">'._('Custom made').'</span>';
                    $pin    = sprintf('<i class="fa fa-thumbtack"></i>');
                    $status = '';
                }


                $adata[] = array(
                    'id'                  => (integer)-1 * $data['Deal Key'],
                    'name'                => $name,
                    'description'         => $data['Deal Term Allowances Label'],
                    'current_deal_status' => $status,
                    'pin'                 => $pin,
                    'items'               => 0,
                    'bonus'               => 0,
                    'discount_percentage' => 0,
                    'amount_discounted'   => 'to do',
                    'pin'                 => $pin
                    //'state' => $state,

                    //'type' => $type,
                    //'date' => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date']." +00:00")),


                );


            }


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
