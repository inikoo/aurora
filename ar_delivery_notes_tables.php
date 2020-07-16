<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2015 15:34:56 GMT+8, Kuala Lumpur, Malaysia
 Refactored: 17 December 2018 at 15:25:20 GMT+8, Kuala Lumpur, Malaysia
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

    case 'delivery_notes_ready_to_pick':
        delivery_notes_ready_to_pick(get_table_parameters(), $db, $user);
        break;
    case 'delivery_notes_assigned':
        delivery_notes_ready_to_pick(get_table_parameters(), $db, $user);
        break;

    case 'delivery_notes':
        delivery_notes(get_table_parameters(), $db, $user);
        break;
    case 'pending_delivery_notes':
        pending_delivery_notes(get_table_parameters(), $db, $user);
        break;
   
    case 'delivery_notes_group_by_store':
        delivery_notes_group_by_store(get_table_parameters(), $db, $user);
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





function delivery_notes_ready_to_pick($_data, $db, $user) {


    $rtext_label = 'delivery_note';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();



    if ($result=$db->query($sql)) {
    		foreach ($result as $data) {

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


                $adata[] = array(
                    'id' => (integer)$data['Delivery Note Key'],


                    'number'   => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Key'], $data['Delivery Note ID']),
                    'store' => sprintf('<span class="link" onclick="change_view(\'store/%d\')" title="%s">%s</span>', $data['Delivery Note Store Key'], $data['Store Name'],$data['Store Code']),

                    'customer' => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Customer Key'], $data['Delivery Note Customer Name']),

                    'date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Created'].' +0:00')),
                    'weight'  => weight($data['Delivery Note Estimated Weight'],'Kg',0,false,true),
                    'parts'  => number($data['Delivery Note Number Ordered Parts']),
                    'type'    => $type,

                );
    		}
    }else {
    		print_r($error_info=$db->errorInfo());
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



function delivery_notes_assigned($_data, $db, $user) {


    $rtext_label = 'delivery_note';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();



    if ($result=$db->query($sql)) {
        foreach ($result as $data) {

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


            $adata[] = array(
                'id' => (integer)$data['Delivery Note Key'],


                'number'   => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Key'], $data['Delivery Note ID']),
                'store' => sprintf('<span class="link" onclick="change_view(\'store/%d\')" title="%s">%s</span>', $data['Delivery Note Store Key'], $data['Store Name'],$data['Store Code']),

                'customer' => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Customer Key'], $data['Delivery Note Customer Name']),

                'date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Created'].' +0:00')),
                'weight'  => weight($data['Delivery Note Estimated Weight'],'Kg',0,false,true),
                'parts'  => number($data['Delivery Note Number Ordered Parts']),
                'type'    => $type,

            );
        }
    }else {
        print_r($error_info=$db->errorInfo());
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

function delivery_note_fast_track_packing($_data, $db, $user) {

    //print_r($_data);

    include_once('class.DeliveryNote.php');
    include_once('utils/order_handing_functions.php');


    global $_locale;// fix this locale stuff

    $rtext_label = 'item';


    //   $dn = new DeliveryNote($_data['parameters']['parent_key']);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref  $group_by order by $order $order_direction  limit $start_from,$number_results";

    $adata = array();
    foreach ($db->query($sql) as $data) {

        // print_r($data);

        /*
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

        */
        $description = $data['Part Package Description'];


        if ($data['Part UN Number']) {
            $description .= ' <span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;padding:0px;font-size:90%">'.$data['Part UN Number'].'</span>';
        }


        $pending = $data['required'] - $data['Picked'];


        $available = $data['required'] - $data['cant_pick'];


        if ($data['required'] != $available) {
            $_quantity = '<span class="strikethrough  discreet">'.number($data['required']).'</span> <span class="error discreet item_quantity_fast_track_packing button  "  qty="'.$available.'" >'.number($available).'</error>';

        } else {
            $_quantity = sprintf('<span class="item_quantity_fast_track_packing button" qty="%s"   >%s</span>', $data['required'], number($data['required']));

        }
        $quantity = '<div class="quantity_components">'.$_quantity.'</div>';

        $location = '<div class="location_components" style="margin-top: 2px">'.get_item_location(
                $pending,
                $data['Quantity On Hand'],
                $data['Date Picked'],

                $data['Location Key'],
                $data['Location Code'],

                $data['Part Current On Hand Stock'],
                $data['Part SKO Barcode'],
                $data['Part Distinct Locations'],

                $data['Part SKU'],
                $data['Inventory Transaction Key'], $_data['parameters']['parent_key']
            ).'</div>';


        /*
        if ($data['Picked'] == $data['quantity']) {
            $picked_info = '<i class="fa fa-fw fa-check success" aria-hidden="true"></i>';

        } else {
            $picked_info = '';
        }

*/


        $picked_offline_done = sprintf(
            '<i onClick="set_picked_offline_item_as_done(this)" class="picked_offline_status  fa fa-check-circle %s "  aria-hidden="true"></i>',
            ($data['Part Current On Hand Stock'] < 1 && $pending > 0 ? 'success blocked' : 'super_discreet button')
        );

        //$total_qty,$total_picked,$picked_in_location, $quantity_on_location, $itf_key, $part_sku,  $part_stock
        $picked_offline_input = '<div class="picked_quantity_components" data-pending="'.$pending.'">'.get_picked_offline_input(
                $data['required'],
                $data['Picked'],
                $data['Picked'],
                $data['required']-$data['Picked'],
                $data['Quantity On Hand'],
                $data['Inventory Transaction Key'],
                $data['Part SKU'],
                $data['Part Current On Hand Stock'],
                $data['Location Key']

            ).'</div>';


        $adata[] = array(
            'id' => (integer)$data['Inventory Transaction Key'],

            'reference'            => sprintf('<span onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),
            //   'product_pid' => $data['Product ID'],
            'description'          => $description,
            'quantity'             => $quantity,
            //'dispatched'        => number(-1 * $data['Inventory Transaction Quantity']),
            //'overview_required' => number($data['Required']),

            //'overview_packed'  => number($data['Packed']),
            //'overview_picked'  => number($data['Picked']),
            //'overview_problem' => number($data['Out of Stock']),
            'picked_offline_done'  => $picked_offline_done,

            //  'packed'               => $packed,
            //    'picked'               => $picked,
            //  'picked_info'          => $picked_info,
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
        $location = '<div class="location_components">'.
            get_item_location(
                $data['pending'],
                $data['Quantity On Hand'],
                $data['Date Picked'],

                $data['Location Key'],
                $data['Location Code'],

                $data['Part Current On Hand Stock'],
                $data['Part SKO Barcode'],
                $data['Part Distinct Locations'],

                $data['Part SKU'],
                $data['Inventory Transaction Key'], $_data['parameters']['parent_key']
            ).
            '</div>';


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
                $data['Part Reference'],
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




function shippers($_data, $db, $user, $account) {


    $rtext_label = 'shipping company';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Shipper Status']) {
                case 'Active':
                    $status = sprintf('<i class="fa fa-play success" title="%s"></i>', _('Active'));

                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-pause discreet error" title="%s"></i>', _('Suspended'));
                    break;
                default:
                    $status = '';
            }


            $code = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/shippers/%d\')">%s</span>', $data['Shipper Warehouse Key'], $data['Shipper Key'], $data['Shipper Code']);


            $record_data[] = array(
                'id'               => (integer)$data['Shipper Key'],
                'code'             => $code,
                'status'           => $status,
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
