<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2018 at 15:17:28 GMT+8, Kuala Lumour, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_purchase_order_items_qty($data) {

    $items_qty = $data['units_qty'].'<span class="small discreet">u.</span> | ';


    if ($data['Part Units Per Package'] != 0 and $data['Supplier Part Packages Per Carton'] != 0) {

        if ($data['units_qty'] % $data['Part Units Per Package'] != 0) {
            $items_qty .= '<span class="error">'.number($data['units_qty'] / $data['Part Units Per Package'], 3).'<span class="small discreet">sko.</span></span> | ';

        } else {
            $items_qty .= number($data['units_qty'] / $data['Part Units Per Package'], 3).'<span class="small discreet">sko.</span> | ';

        }

        if ($data['units_qty'] % ($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton']) != 0) {
            $items_qty .= '<span class="error">'.number($data['units_qty'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton'], 3).'<span title="'._('Cartons').'" class="small discreet">C.</span></span>';

        } else {
            $items_qty .= number($data['units_qty'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton'], 3).'<span title="'._('Cartons').'" class="small discreet">C.</span>';

        }

    }


    return $items_qty;
}

function get_purchase_order_subtotals($data) {
    $subtotals = sprintf('<span  class="subtotals" style="font-size:90%%"  >');
    if ($data['units_qty'] > 0) {


        $subtotals .= get_purchase_order_items_qty($data);


        $amount = $data['units_qty'] * $data['Supplier Part Unit Cost'];

        $subtotals .= '<br>'.money($amount, $data['currency_code']);

        if ($data['currency_code'] != $data['account_currency_code']) {
            $subtotals .= ' <span >('.money($amount * $data['exchange'], $data['account_currency_code']).')</span>';

        }

        if ($data['Part Package Weight'] > 0) {
            $subtotals .= ' '.weight(
                    $data['Part Package Weight'] * $data['units_qty'] / $data['Part Units Per Package']
                );
        }
        if ($data['Supplier Part Carton CBM'] > 0) {
            $subtotals .= ' '.number(
                    $data['units_qty'] * $data['Supplier Part Carton CBM'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton']
                ).' m³';
        }
    }
    $subtotals .= '</span>';

    return $subtotals;

}

function get_agent_purchase_order_transaction_data($data) {

    //print_r($data);


    if ($data['Metadata'] == '') {
        $metadata = array();
    } else {
        $metadata = json_decode($data['Metadata'], true);
    }


    $back_operations    = '';
    $forward_operations = '';
    switch ($data['Purchase Order Transaction State']) {

        case 'Submitted':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Submitted Units']).'C', $data['Purchase Order Transaction Fact Key'], $data['Metadata'],
                _('Log problem with supplier')
            );

            $forward_operations .= sprintf('<span onclick="confirm_item(%d)" class="button padding_left_20" title="%s"><i class="fa fa-check-circle fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Confirm'));

            break;
        case 'ProblemSupplier':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Submitted Units']).'C', $data['Purchase Order Transaction Fact Key'], $data['Metadata'],
                _('Log problem with supplier')
            );


            break;
        case 'Confirmed':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Submitted Units']).'C', $data['Purchase Order Transaction Fact Key'], $data['Metadata'],
                _('Log problem with supplier')
            );

            $back_operations    .= sprintf('<span onclick="unconfirm_item(%d)" class="button padding_left_10" title="%s"><i class="fa fa-arrow-circle-left fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Unconfirm'));
            $forward_operations .= sprintf('<span onclick="mark_as_received(%d)" class="button padding_left_20" title="%s"><i class="fa fa-arrow-circle-down fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Mark as received'));

            break;
        case 'ReceivedAgent':

            $back_operations    .= sprintf('<span onclick="unmark_as_received(%d)" style="margin-left:47px" class="button " title="%s"><i class="fa fa-undo fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Unconfirm'));
            $forward_operations .= sprintf('<span onclick="add_to_delivery(%d)" class="button padding_left_20" title="%s"><i class="far fa-truck-container fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Add to delivery'));

            break;
        default:

            break;
    }
    $state = '';

    switch ($data['Purchase Order Transaction State']) {

        case 'Submitted':
            $state .= sprintf('<span  title="%s">%s</span>', _('Received order from client, submitting to supplier'), _('In Process'));
            break;
        case 'ProblemSupplier':
            $state .= sprintf('<span class="error" title="%s">%s</span>', _('Problem with supplier supplier'), _('Problem'));
            // print_r($metadata);
            if (isset($metadata['item_problems'])) {


                $problems_state = '';
                foreach ($metadata['item_problems']['problems'] as $problem => $problem_data) {
                    if ($problem_data['selected']) {
                        switch ($problem) {
                            case 'price_increase':
                                $problems_state .= _('Price increase');


                                if (is_numeric($problem_data['note']) and $problem_data['note'] > $data['Supplier Part Unit Cost']) {
                                    $problems_state .= ' <b title="'.money($problem_data['note'], $data['Currency Code']).'">'.delta($problem_data['note'], $data['Supplier Part Unit Cost']).'</b>, ';
                                } elseif ($problem_data['note'] != '') {
                                    $problems_state .= ' <em>('.$problem_data['note'].')</em>, ';
                                }


                                break;
                            case 'discontinued':
                                $problems_state .= _('Discontinued').', ';
                                break;
                            case 'low_stock':
                                $problems_state .= _('Low stock').', ';
                                break;
                            case 'long_wait':
                                $problems_state .= _('Out of stock').', ';
                                break;
                            case 'min_order':
                                $problems_state .= _('Minimum order not meet').', ';
                                break;
                            case 'other':
                                $problems_state .= _('Other').', ';
                                break;
                            default:
                                $problems_state .= $problem.', ';

                        }
                    }
                }

                $problems_state = preg_replace('/\, $/', '', $problems_state);
                $state          .= '<div style="line-height: normal;font-size: x-small" class="error" >'.$problems_state.'</div>';
            }

            break;
        case 'Confirmed':
            $state .= sprintf('<span title="%s">%s</span>', _('Confirmed by supplier'), _('Confirmed'));
            break;
        case 'ReceivedAgent':
            $state .= sprintf('<span title="%s">%s</span>', _('Goods received from supplier'), _('In warehouse'));

            break;
        case 'InDelivery':
            $state .= _('Loading Delivery');
            break;
        case 'Inputted':
            $state .= _('Delivery inputted');
            break;
        case 'Dispatched':
            $state .= _('In transit');
            break;
        case 'Received':
            $state .= _('Received');
            break;
        case 'Checked':
            $state .= _('Checked');
            break;
        case 'Placed':
            $state .= _('Booked in');
            break;
        case 'Cancelled':
            $state .= _('Cancelled');
            break;
        default:
            $state .= $data['Purchase Order Transaction State'];
            break;
    }

    return array(
        $back_operations,
        $forward_operations,
        $state

    );

}


function get_purchase_order_transaction_data($data) {

    //print_r($data);


    if (empty($data['Metadata'])) {
        $metadata = array();
    } else {
        $metadata = json_decode($data['Metadata'], true);
    }


    $state = '';


    $delivery_link = ' ('.sprintf('<span class="link" onclick="change_view(\'%s/%d/delivery/%d\')">%s</span>)', strtolower($data['Supplier Delivery Parent']), $data['Supplier Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']);

    switch ($data['Purchase Order Transaction State']) {
        case 'InProcess':
            $state .= _('In process');
            break;
        case 'Submitted':
            $state .= sprintf('<span  title="%s">%s</span>', _('Submitted to agent'), _('Submitted'));
            $state .= '<br/> <i class="fa error fa-minus-circle button "  title="'._('Cancel').'"  onclick="cancel_purchase_order_submitted_item(this,'.$data['Purchase Order Transaction Fact Key'].')" ></i>';
            break;
        case 'ProblemSupplier':
            $state .= sprintf('<span class="error" title="%s">%s</span>', _('Problem with supplier supplier'), _('Problem'));
            // print_r($metadata);
            if (isset($metadata['item_problems'])) {


                $problems_state = '';
                foreach ($metadata['item_problems']['problems'] as $problem => $problem_data) {
                    if ($problem_data['selected']) {
                        switch ($problem) {
                            case 'price_increase':
                                $problems_state .= _('Price increase');


                                if (is_numeric($problem_data['note']) and $problem_data['note'] > $data['Supplier Part Unit Cost']) {
                                    $problems_state .= ' <b title="'.money($problem_data['note'], $data['Currency Code']).'">'.delta($problem_data['note'], $data['Supplier Part Unit Cost']).'</b>, ';
                                } elseif ($problem_data['note'] != '') {
                                    $problems_state .= ' <em>('.$problem_data['note'].')</em>, ';
                                }


                                break;
                            case 'discontinued':
                                $problems_state .= _('Discontinued').', ';
                                break;
                            case 'low_stock':
                                $problems_state .= _('Low stock').', ';
                                break;
                            case 'long_wait':
                                $problems_state .= _('Out of stock').', ';
                                break;
                            case 'min_order':
                                $problems_state .= _('Minimum order not meet').', ';
                                break;
                            case 'other':
                                $problems_state .= _('Other').', ';
                                break;
                            default:
                                $problems_state .= $problem.', ';

                        }
                    }
                }

                $problems_state = preg_replace('/\, $/', '', $problems_state);
                $state          .= '<div style="line-height: normal;font-size: x-small" class="error" >'.$problems_state.'</div>';
                $state          .= '<div style="line-height: normal;margin:7px 0px;font-size: x-small" ><span style="border:1px solid #ccc;padding:2px 10px;" class="button unselectable">'._('Action').'</span></div>';

            }

            break;
        case 'Confirmed':
            $state .= sprintf('<span title="%s">%s</span>', _('Confirmed by supplier'), _('Confirmed'));
            break;
        case 'ReceivedAgent':
            $state .= sprintf('<span title="%s">%s</span>', _('Goods received from supplier'), _('In agent warehouse'));

            break;
        case 'InDelivery':
            $state .= _('In transit');
            break;
        case 'Inputted':
            $state .= _('Delivery in process').$delivery_link;
            break;
        case 'Dispatched':
            $state .= _('In transit').$delivery_link;
            break;
        case 'Received':
            $state .= _('Received').$delivery_link;
            break;
        case 'Checked':
            $state .= _('Checked').$delivery_link;
            break;
        case 'Placed':
            $state .= _('Booked in').$delivery_link;
            break;
        case 'InvoiceChecked':
            $state .= _('Booked in, costing done').$delivery_link;
            break;
        case 'Cancelled':
            $state .= _('Cancelled');
            break;
        case 'NoReceived':
            $state .= _('No received');
            break;
        default:
            $state .= $data['Purchase Order Transaction State'];
            break;
    }


    return array(

        $state

    );

}


function get_job_order_transaction_data($data) {


    $state = '';


    $delivery_link = ' ('.sprintf('<span class="link" onclick="change_view(\'%s/%d/delivery/%d\')">%s</span>)', strtolower($data['Supplier Delivery Parent']), $data['Supplier Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']);

    switch ($data['Purchase Order Transaction State']) {
        case 'InProcess':
            $state .= _('In process');
            break;
        case 'Submitted':
            $state .= '<i class="fa error fa-minus-circle button  "  title="'._('Cancel').'"  onclick="cancel_purchase_order_submitted_item(this,'.$data['Purchase Order Transaction Fact Key'].')" ></i>';

            $state .= sprintf('<span class="padding_left_10" title="%s">%s</span>', _('Queued'), _('Queued'));
            break;

        case 'Confirmed':
            $state = '<span class="action_container">';

            $state .= _('Manufacturing');

            $state .= ' <i class="action_container_trigger_finish_manufacture  button fal padding_left_10 padding_right_10  fa-flag-checkered"  data-action="finish_manufacture" data-key="'.$data['Purchase Order Transaction Fact Key']
                .'" onclick="job_order_item_action(this)" ></i>';
            $state .= '</span>';
            $state .= '<span class="follow_on_finish_manufacture hide">'._('Finishing').'</span>';


            break;
        case 'Manufactured':


            if($data['Purchase Order Manufactured Units']==0){

                $state = _('Not manufactured');

            }else{
                $state = '<span class="action_container">';
                $state .= ' <i class="action_container_trigger_undo_manufactured error button fal padding_left_10 padding_right_10  fa-flip-horizontal fa-flag-checkered"  data-action="undo_manufactured" data-key="'.$data['Purchase Order Transaction Fact Key']
                    .'" onclick="job_order_item_action(this)" ></i> ';

                $state .= _('Manufactured');
                $state .= ' <i class="action_container_trigger_qc_check  button fal padding_left_10 padding_right_10  fa-siren-on"  data-action="qc_check" data-key="'.$data['Purchase Order Transaction Fact Key'].'" onclick="job_order_item_action(this)" ></i>';
                $state .= '</span>';
                $state .= '<span class="follow_on_qc_check hide">'._('QC check').'</span>';
            }



            break;
        case 'QC_Pass':
            $state = '<span class="action_container">';
            $state .= ' <i class="action_container_trigger_undo_qc_pass error button fal padding_left_10 padding_right_10 fa-siren"  data-action="undo_qc_pass" data-key="'.$data['Purchase Order Transaction Fact Key'].'" onclick="job_order_item_action(this)" ></i> ';

            $state .= _('QC passed');
            $state .= '</span>';
            break;
        case 'ReceivedAgent':
            $state .= sprintf('<span title="%s">%s</span>', _('Goods received from supplier'), _('In agent warehouse'));

            break;
        case 'InDelivery':
            $state .= _('In transit');
            break;
        case 'Inputted':
            $state .= _('Delivery in process').$delivery_link;
            break;
        case 'Dispatched':
            $state .= '<i class="error fal padding_right_10  fa-flag-checkered"></i> '._('Manufactured').' <i class="far padding_left_10 padding_right_10  fa-siren-on"></i>';
            break;
        case 'Received':
            $state .= _('Received').$delivery_link;
            break;
        case 'Checked':
            $state .= _('Checked').$delivery_link;
            break;
        case 'Placed':
            $state .= _('Booked in').$delivery_link;
            break;
        case 'InvoiceChecked':
            $state .= _('Booked in, costing done').$delivery_link;
            break;
        case 'Cancelled':
            $state .= _('Cancelled');
            break;
        case 'NoReceived':
            $state .= _('No received');
            break;
        default:
            $state .= $data['Purchase Order Transaction State'];
            break;
    }


    return $state;

}


function get_job_order_operations($data) {

    $skos_per_carton = $data['Purchase Order Submitted SKOs Per Carton'];
    $units_per_sko   = $data['Purchase Order Submitted Units Per SKO'];
    $transaction_key = $data['Purchase Order Transaction Fact Key'];


    if ($data['Purchase Order Transaction State'] == 'Confirmed') {
        list($units_qty, $skos_qty, $cartons_qty, $class) = get_job_order_operations_quantities($data['Purchase Order Submitted Units'], $data);

        $operation_units = sprintf(
            '<span   class="invisible delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{ "key":%d,  "type": "Units", "sko_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input  data-qty_type="unit"  class=" create_delivery_item_qty order_units_qty %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


            $transaction_key, $transaction_key, $units_per_sko, $skos_per_carton * $units_per_sko, $class, ($units_qty == 0 ? '' : $units_qty + 0), ($units_qty == 0 ? '' : $units_qty + 0)

        );

        $operation_skos = sprintf(
            '<span  class="invisible delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{"type": "SKOs", "unit_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input data-qty_type="sko" class=" create_delivery_item_qty order_skos_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i>
                <i class="padding_left_10 padding_right_10 fa-fw save changed fa fa-cloud" data-action="finish_manufacture" onclick="save_job_order_forward_action(this)" ></i>
                </span>
                
                ',


            $transaction_key, $units_per_sko, $skos_per_carton, $class, ($skos_qty == 0 ? '' : $skos_qty + 0), ($skos_qty == 0 ? '' : $skos_qty + 0)

        );

        $operation_cartons = sprintf(
            '<span   class="invisible delivery_quantity_%d delivery_quantity_item_container on"   data-settings=\'{"type": "Cartons", "unit_factor":%d , "sko_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input data-qty_type="carton"  class=" create_delivery_item_qty order_cartons_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


            $transaction_key, $units_per_sko * $skos_per_carton, $skos_per_carton, $class, ($cartons_qty == 0 ? '' : $cartons_qty + 0), ($cartons_qty == 0 ? '' : $cartons_qty + 0)

        );

    } elseif ($data['Purchase Order Transaction State'] == 'Manufactured') {
        list($units_qty, $skos_qty, $cartons_qty, $class) = get_job_order_operations_quantities($data['Purchase Order Manufactured Units'], $data);

        $operation_units = sprintf(
            '<span   class="invisible delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{ "key":%d,  "type": "Units", "sko_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input  data-qty_type="unit"  class=" create_delivery_item_qty order_units_qty %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


            $transaction_key, $transaction_key, $units_per_sko, $skos_per_carton * $units_per_sko, $class, ($units_qty == 0 ? '' : $units_qty + 0), ($units_qty == 0 ? '' : $units_qty + 0)

        );

        $operation_skos = sprintf(
            '<span  class="invisible delivery_quantity_%d delivery_quantity_item_container on"    data-settings=\'{"type": "SKOs", "unit_factor":%d , "carton_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input data-qty_type="sko" class=" create_delivery_item_qty order_skos_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i>
                <i class="padding_left_10 padding_right_10 fa-fw save changed fa fa-cloud"  data-action="qc_check" onclick="save_job_order_forward_action(this)" ></i>
                </span>
                
                ',


            $transaction_key, $units_per_sko, $skos_per_carton, $class, ($skos_qty == 0 ? '' : $skos_qty + 0), ($skos_qty == 0 ? '' : $skos_qty + 0)

        );

        $operation_cartons = sprintf(
            '<span   class="invisible delivery_quantity_%d delivery_quantity_item_container on"   data-settings=\'{"type": "Cartons", "unit_factor":%d , "sko_factor":%d }\'   >
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa minus  fa-minus fa-fw button" aria-hidden="true"></i>
                <input data-qty_type="carton"  class=" create_delivery_item_qty order_cartons_qty  %s width_50" style="text-align: center" value="%s" ovalue="%s">
                <i onClick="create_delivery_item_icon_clicked(this)" class="fa plus  fa-plus fa-fw button" aria-hidden="true"></i></span>',


            $transaction_key, $units_per_sko * $skos_per_carton, $skos_per_carton, $class, ($cartons_qty == 0 ? '' : $cartons_qty + 0), ($cartons_qty == 0 ? '' : $cartons_qty + 0)

        );

    } else {
        $operation_units   = '';
        $operation_skos    = '';
        $operation_cartons = '';
    }


    return [
        $operation_units,
        $operation_skos,
        $operation_cartons
    ];


}


function get_job_order_operations_quantities($qty, $data) {
    $skos_per_carton = $data['Purchase Order Submitted SKOs Per Carton'];
    $units_per_sko   = $data['Purchase Order Submitted Units Per SKO'];

    $units_qty = $qty;
    if ($skos_per_carton != 0) {
        $skos_qty = $units_qty / $units_per_sko;
    } else {
        $skos_qty = 0;
    }
    if ($skos_per_carton != 0) {
        $cartons_qty = $skos_qty / $skos_per_carton;
    } else {
        $cartons_qty = 0;

    }


    if (floor($qty) != $qty or floor($skos_qty) != $skos_qty) {
        $class = 'error';
    } else {
        $class = '';
    }

    return [
        $units_qty,
        $skos_qty,
        $cartons_qty,
        $class
    ];
}

function get_purchase_order_transaction_ordered_data($data) {

    $skos_per_carton  = $data['Purchase Order Submitted SKOs Per Carton'];
    $units_per_sko    = $data['Purchase Order Submitted Units Per SKO'];

    $_items_qty = $data['Purchase Order Submitted Units'] - $data['Purchase Order Submitted Cancelled Units'];


    $items_qty     = $_items_qty.'<span class="small discreet">u.</span> | ';
    $ordered_units = $_items_qty;

    if ($units_per_sko == 0) {
        $items_qty .= '<span class="error">Units per SKO=0</span> ';
    } else {

        $ordered_skos='';
        if ($data['Purchase Order Submitted Cancelled Units'] > 0) {
            $ordered_skos = '<span class="discreet strikethrough">'.number($data['Purchase Order Submitted Cancelled Units'] / $units_per_sko, 3).'</span>';


        }


        if ($_items_qty > 0) {
            $ordered_skos .= ' '.number($_items_qty / $units_per_sko, 3);


            if($data['Purchase Order Transaction Type']=='Production'){

                $manufactured_skos='';
                $qc_passed_skos='';

                if($data['Purchase Order Manufactured Units']!=''){
                    $manufactured_skos .= '<i style="font-size: x-small" class=" fal fa-flag-checkered"></i> '.number($data['Purchase Order Manufactured Units'] / $units_per_sko, 3);
                }

                if($data['Purchase Order QC Pass Units']!=''   ){
                    $qc_passed_skos .= '<i style="font-size: x-small" class=" fal fa-siren-on"></i> '.number($data['Purchase Order QC Pass Units'] / $units_per_sko, 3);
                    if( $data['Purchase Order Manufactured Units']==$data['Purchase Order Manufactured Units'] ){
                        $manufactured_skos='';
                    }

                }

                if($manufactured_skos!='' or $qc_passed_skos!=''){
                    $ordered_skos='<span style="font-size: x-small">'.$ordered_skos.'</span>';
                }

                if($manufactured_skos!=''){
                    $ordered_skos.=' | '.$manufactured_skos;
                }
                if($qc_passed_skos!=''){
                    $ordered_skos.=' | '.$qc_passed_skos;
                }


            }

        }




        if ($_items_qty % $units_per_sko != 0) {
            $items_qty .= '<span class="error">'.$ordered_skos.'<span class="small discreet">sko.</span></span> | ';

        } else {
            $items_qty .= number($_items_qty / $units_per_sko, 3).'<span class="small discreet">sko.</span> | ';

        }

        $ordered_cartons = number($_items_qty / $units_per_sko / $skos_per_carton, 3);

        if ($_items_qty % ($units_per_sko * $skos_per_carton) != 0) {
            $items_qty .= '<span class="error">'.$ordered_cartons.'<span title="'._('Cartons').'" class="small discreet">C.</span></span>';

        } else {
            $items_qty .= $ordered_cartons.'<span title="'._('Cartons').'" class="small discreet">C.</span>';

        }
    }

    $items_qty = '<span class="submitted_items_qty" onclick="use_submitted_qty_in_delivery(this,'.$_items_qty.')">'.$items_qty.'</span>';
    return [$items_qty,$ordered_units,$ordered_skos,$ordered_cartons];

}