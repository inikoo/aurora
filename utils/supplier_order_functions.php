<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2018 at 15:17:28 GMT+8, Kuala Lumour, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_agent_purchase_order_transaction_data($data) {

    //print_r($data);


    if($data['Metadata']==''){
        $metadata=array();
    }else{
        $metadata=json_decode($data['Metadata'],true);
    }





    $back_operations    = '';
    $forward_operations = '';
    switch ($data['Purchase Order Transaction State']) {

        case 'Submitted':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Quantity']).'C', $data['Purchase Order Transaction Fact Key'],
                $data['Metadata'], _('Log problem with supplier')
            );

            $forward_operations .= sprintf('<span onclick="confirm_item(%d)" class="button padding_left_20" title="%s"><i class="fa fa-check-circle fa-fw"></i></span>', $data['Purchase Order Transaction Fact Key'], _('Confirm'));

            break;
        case 'ProblemSupplier':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Quantity']).'C', $data['Purchase Order Transaction Fact Key'],
                $data['Metadata'], _('Log problem with supplier')
            );


            break;
        case 'Confirmed':
            $back_operations .= sprintf(
                '<span onclick="log_problem_with_supplier_item(this)" data-title="%s" data-transaction_key="%d" data-metadata=\'%s\'  class="button padding_left_20" title="%s"><i class="fa fa-exclamation-circle fa-fw error"></i></span>',

                $data['Supplier Part Reference'].' ('.money($data['Supplier Part Unit Cost'], $data['Currency Code']).') '.number($data['Purchase Order Quantity']).'C', $data['Purchase Order Transaction Fact Key'],
                $data['Metadata'], _('Log problem with supplier')
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
    $state='';

    switch ($data['Purchase Order Transaction State']) {

        case 'Submitted':
            $state .= sprintf('<span  title="%s">%s</span>', _('Received order from client, submitting to supplier'), _('In Process'));
            break;
        case 'ProblemSupplier':
            $state .= sprintf('<span class="error" title="%s">%s</span>', _('Problem with supplier supplier'), _('Problem'));
            // print_r($metadata);
            if(isset($metadata['item_problems'])){





                $problems_state='';
                foreach($metadata['item_problems']['problems'] as $problem=>$problem_data){
                    if($problem_data['selected']){
                        switch ($problem){
                            case 'price_increase':
                                $problems_state.=_('Price increase');



                                if(is_numeric($problem_data['note']) and $problem_data['note']>$data['Supplier Part Unit Cost']){
                                    $problems_state.=' <b title="'.money($problem_data['note'], $data['Currency Code']).'">'.delta($problem_data['note'],$data['Supplier Part Unit Cost']).'</b>, ';
                                }elseif($problem_data['note']!=''){
                                    $problems_state.=' <em>('.$problem_data['note'].')</em>, ';
                                }


                                break;
                            case 'discontinued':
                                $problems_state.=_('Discontinued').', ';
                                break;
                            case 'low_stock':
                                $problems_state.=_('Low stock').', ';
                                break;
                            case 'long_wait':
                                $problems_state.=_('Out of stock').', ';
                                break;
                            case 'min_order':
                                $problems_state.=_('Minimum order not meet').', ';
                                break;
                            case 'other':
                                $problems_state.=_('Other').', ';
                                break;
                            default:
                                $problems_state.=$problem.', ';

                        }
                    }
                }

                $problems_state=preg_replace('/\, $/','',$problems_state);
                $state.='<div style="line-height: normal;font-size: x-small" class="error" >'.$problems_state.'</div>';
            }

            break;
        case 'Confirmed':
            $state .= sprintf('<span class="" title="%s">%s</span>', _('Confirmed by supplier'), _('Confirmed'));
            break;
        case 'ReceivedAgent':
            $state .= sprintf('<span class="" title="%s">%s</span>', _('Goods received from supplier'), _('In warehouse'));

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


    if(empty($data['Metadata'])){
        $metadata=array();
    }else{
        $metadata=json_decode($data['Metadata'],true);
    }





    $state='';

    switch ($data['Purchase Order Transaction State']) {
        case 'InProcess':
            $state .= _('In process');
            break;
        case 'Submitted':
            $state .= sprintf('<span  title="%s">%s</span>', _('Submitted to agent'), _('Submitted'));
            break;
        case 'ProblemSupplier':
            $state .= sprintf('<span class="error" title="%s">%s</span>', _('Problem with supplier supplier'), _('Problem'));
            // print_r($metadata);
            if(isset($metadata['item_problems'])){





                $problems_state='';
                foreach($metadata['item_problems']['problems'] as $problem=>$problem_data){
                    if($problem_data['selected']){
                        switch ($problem){
                            case 'price_increase':
                                $problems_state.=_('Price increase');



                                if(is_numeric($problem_data['note']) and $problem_data['note']>$data['Supplier Part Unit Cost']){
                                    $problems_state.=' <b title="'.money($problem_data['note'], $data['Currency Code']).'">'.delta($problem_data['note'],$data['Supplier Part Unit Cost']).'</b>, ';
                                }elseif($problem_data['note']!=''){
                                    $problems_state.=' <em>('.$problem_data['note'].')</em>, ';
                                }




                                break;
                            case 'discontinued':
                                $problems_state.=_('Discontinued').', ';
                                break;
                            case 'low_stock':
                                $problems_state.=_('Low stock').', ';
                                break;
                            case 'long_wait':
                                $problems_state.=_('Out of stock').', ';
                                break;
                            case 'min_order':
                                $problems_state.=_('Minimum order not meet').', ';
                                break;
                            case 'other':
                                $problems_state.=_('Other').', ';
                                break;
                            default:
                                $problems_state.=$problem.', ';

                        }
                    }
                }

                $problems_state=preg_replace('/\, $/','',$problems_state);
                $state.='<div style="line-height: normal;font-size: x-small" class="error" >'.$problems_state.'</div>';
                $state.='<div style="line-height: normal;margin:7px 0px;font-size: x-small" ><span style="border:1px solid #ccc;padding:2px 10px;" class="button unselectable">'._('Action').'</span></div>';

            }

            break;
        case 'Confirmed':
            $state .= sprintf('<span class="" title="%s">%s</span>', _('Confirmed by supplier'), _('Confirmed'));
            break;
        case 'ReceivedAgent':
            $state .= sprintf('<span class="" title="%s">%s</span>', _('Goods received from supplier'), _('In agent warehouse'));

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

        $state

    );

}

?>
