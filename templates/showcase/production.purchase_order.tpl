{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-07-2019 12:04:03 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

{assign deliveries $order->get_deliveries('objects')}
<div class="showcase_purchase_order">
<div class="timeline_horizontal  {if $order->get('Purchase Order State')=='Cancelled'   }hide{/if}">
    {$order->get('State Index')} {$order->get('Max State Index')}

    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state ">{t}Sent to queue{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Production_Submitted_Formatted_Date"> {$order->get('Production Submitted Formatted Date')}</span>
                <span style="left: -155%"  class="Production_Creation_Formatted_Date start_date">{$order->get('Production Creation Formatted Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>
        <li id="confirm_node"
            class="li  {if $order->get('State Index')>=40  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Start{/t} </span>
            </div>
            <div class="timestamp">
                <span class="Production_Confirmed_Formatted_Date">{$order->get('Production Confirmed Formatted Date')}&nbsp;</span>
            </div>
            <div class="industry">
            </div>
        </li>

        <li id="production_node"  class="li   {if $order->get('State Index')>=50 }complete{elseif  $order->get('Max State Index')>=50}semi_complete{/if} ">
            <div class="label" title="{t}Finish Manufacturing{/t}">
                <span class="state "><span class="state ">{t}Finish{/t} </span></span>
            </div>
            <div class="timestamp">

                <span class="Production_Manufactured_Formatted_Date ">{$order->get('Production Manufactured Formatted Date')}</span>



            </div>
            <div class="industry">
            </div>
        </li>


        <li id="checked_node"  class="li   {if $order->get('State Index')>=55 }complete{elseif  $order->get('Max State Index')>=55}semi_complete{/if} ">

        <div class="label" title="{t}Quality control passed{/t}">
                <span class="state "><span class="state ">{t}QC Passed{/t} </span></span>
            </div>
            <div class="timestamp">

                <span class="Production_QC_Pass_Formatted_Date ">{$order->get('Production QC Pass Formatted Date')}&nbsp;</span>


            </div>
            <div class="dot">
            </div>
        </li>

        <li id="delivered_node"
            class="li   {if $order->get('State Index')>=80 }complete{/if}">
            <div class="label" title="{t}Delivered{/t}">
                <span class="state ">
                                        <span class="state ">{t}Delivered{/t} <span></i></span></span>
                </span>
            </div>
            <div class="timestamp">

                <span class="Production_Delivered_Formatted_Date ">{$order->get('Production Delivered Formatted Date')}&nbsp;</span>


            </div>
            <div class="dot">
            </div>
        </li>


            <li id="estimated_send_node" class=" li {if $order->get('State Index')>=100 }complete{/if}  ">
                <div class="label">
                    <span class="state ">{t}Placed{/t} <span></i></span></span>
                </div>
                <div class="timestamp">
                    <span class="Purchase_Order_Estimated_Receiving_Date">&nbsp; {$order->get('Production In Location Formatted Date')}
                </div>
                <div class="dot">
                </div>
            </li>









    </ul>
</div>

<div class="timeline_horizontal production  {if $order->get('Purchase Order State')!='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Production_Submitted_Formatted_Date"> {$order->get('Production Submitted Formatted Date')}</span>
                <span style="left: -155%"  class="Production_Creation_Formatted_Date start_date">{$order->get('Production Creation Formatted Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="send_node" class="li  cancelled">
            <div class="label">
                <span class="state ">{t}Cancelled{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Cancelled_Date">{$order->get('Production Cancelled Formatted Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>




    </ul>
</div>
<div class="order" style="display: flex;" data-object='{$object_data}'>
    <div style=" align-items: stretch;flex: 1;" class="block ">


        <table style="margin-bottom: 20px" >


            <tr>
                <td style="text-align: center" colspan="2">
                    <span ><i class="fa fa-bars fa-fw discreet" aria-hidden="true"></i> <span class="Purchase_Order_Ordered_Number_Items">{$order->get('Ordered Number Items')}</span></span>
                    <span class="{if $order->get('State Index')<60}super_discreet{/if}" style="padding-left:20px"><i
                                class="fa fa-arrow-circle-down  fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Supplier_Delivery_Items">{$order->get('Number Supplier Delivery Items')}</span></span>
                    <span class="{if $order->get('State Index')<80}super_discreet{/if}" style="padding-left:20px"><i
                                class="fa fa-inventory fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Placed_Items">{$order->get('Number Placed Items')}</span></span>
                </td>
            </tr>
            <tr>

                <td  style="text-align: center" class=" Purchase_Order_Weight" title="{t}Weight{/t}">{$order->get('Weight')}</td>
                <td style="text-align: center" class="Purchase_Order_CBM" title="{t}CBM{/t}">{$order->get('CBM')}</td>





        </table>

        <div style="clear:both">
        </div>
    </div>



    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px">
        <div class="state" style="height:30px;margin-bottom:0px;;line-height: 30px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $order->get('State Index')!=10    }hide{/if}">
                    <div class="square_button left"
                         title="{t}delete{/t}">
                        <i class="far fa-trash-alt very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete purchase order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright">
                                    <span data-data='{ "object": "PurchaseOrder", "key":"{$order->id}"}'
                                                         id="delete_save_buttons" class="error save button"
                                                         onclick="delete_object(this)">
                                        <span class="label">{t}Delete{/t}</span>
                                        <i class="far fa-trash-alt fa-fw  " aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if  $order->get('State Index')>30  or  $order->get('State Index')<20   }hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error "   onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" class="order_operation_dialog hide">
                            <tr class="top ">
                                <td class="label" colspan="2">{t}Cancel purchase order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td>
                                    <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i>
                                </td>
                                <td class="aright">
                                    <span
                                            data-data='{  "field": "Purchase Order State","value": "Cancelled","dialog_name":"cancel"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)">
                                        <span class="label">{t}Cancel{/t}</span>
                                        <i class="fa fa-cloud fa-fw" aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_submit_operations"
                     class="order_operation {if   $order->get('State Index')!=30   }hide{/if}">
                    <div class="square_button left" title="{t}Stop manufacturing, send back to planning{/t}">
                        <i class="fal fa-user-clock error "   onclick="toggle_order_operation_dialog('undo_submit')"></i>


                        <table id="undo_submit_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send back to planning{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "InProcess","dialog_name":"undo_submit"}'
                                            id="undo_submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_confirm_operations"
                     class="order_operation {if   $order->get('State Index')!=40   }hide{/if}">
                    <div class="square_button left" title="{t}Send back to queue{/t}">
                        <i class="fal  fa-fill error "  onclick="toggle_order_operation_dialog('undo_confirm')"></i>

                        <table id="undo_confirm_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send back to queue{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_confirm')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "undo_confirm","dialog_name":"undo_confirm"}'
                                            id="undo_confirm_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_dispatch_operations"
                     class="order_operation {if   $order->get('State Index')!=50   }hide{/if}">
                    <div class="square_button left" title="{t}Send back to manufacturing{/t}">
                        <i class="fal  fa-flag-checkered error fa-flip-horizontal"  onclick="toggle_order_operation_dialog('undo_dispatch')"></i>

                        <table id="undo_dispatch_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send back to manufacturing{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_dispatch')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "undo_manufactured","dialog_name":"undo_dispatch"}'
                                            id="undo_dispatch_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_qc_pass_operations"
                     class="order_operation {if   $order->get('State Index')!=55   }hide{/if}">
                    <div class="square_button left" title="{t}Back to quality control{/t}">
                        <i class="fal  fa-siren error fa-flip-horizontal"  onclick="toggle_order_operation_dialog('undo_qc_pass')"></i>

                        <table id="undo_qc_pass_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Back to quality control{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_qc_pass')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "undo_qc_pass","dialog_name":"undo_qc_pass"}'
                                            id="undo_qc_pass_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float: left;padding-left: 10px" class="Purchase_Order_State"> {$order->get('State')} </span>
            <div id="forward_operations">


                <div id="submit_operations" class="order_operation {if $order->get('Purchase Order State')!='InProcess'  or  $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="submit_operation"
                         class="square_button right"
                         title="{t}Send to queue{/t}">
                        <i class="fal fa-user-clock   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send to queue{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Submitted","dialog_name":"submit"}'
                                            id="submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="confirm_operations" class="order_operation {if $order->get('Purchase Order State')!='Submitted'  or  $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="confirm_operation"
                         class="square_button right"
                         title="{t}Start manufacturing{/t}">
                        <i class="fa fa-fill-drip   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('confirm')"></i>
                        <table id="confirm_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Start manufacturing{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('confirm')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Confirmed","dialog_name":"confirm"}'
                                            id="confirm_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="dispatch_operations" class="order_operation {if $order->get('Purchase Order State')!='Confirmed'  or  $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="dispatch_operation"
                         class="square_button right"
                         title="{t}Set manufacturing as{/t}">
                        <i class="fa fa-flag-checkered   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('dispatch')"></i>
                        <table id="dispatch_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Job order done{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('dispatch')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Manufactured","dialog_name":"dispatch"}'
                                            id="dispatch_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>




                <div id="deliver_operations" class="order_operation {if !(  $order->get('State Index')==55  or $order->get('Max State Index')==55   ) or $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="deliver_operation"
                         class="square_button right"
                         title="{t}Deliver{/t}">
                        <i class="far fa-hand-holding-box   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('deliver')"></i>
                        <table id="deliver_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Deliver QC pass items{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('deliver')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Deliver_Production","dialog_name":"deliver"}'
                                            id="deliver_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="check_operations" class="order_operation {if  $order->get('State Index')<50 or $order->get('State Index')>=55 or  $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="check_operation"
                         class="square_button right"
                         title="{t}Quality control{/t}">
                        <i class="far fa-siren-on   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('check')"></i>
                        <table id="check_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Set all manufactured product as QC pass{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('check')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "QC_Pass","dialog_name":"check"}'
                                            id="check_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                
                
            </div>
        </div>


        <table >


            <tr class="processing_times  {if  $order->get('State Index') >= 10 }hide{/if} " style="    border-bottom: 1px solid #ccc;">
                <td style="width:50%;text-align: center;padding: 10px;xheight: 35px;line-height: 18px">
                    <span class="discreet">{t}Production time{/t}</span><br>{$parent->get('Production Time')}


                </td>
                <td style="width:50%;text-align: center;padding: 0px;;xheight: 35px;line-height: 18px" >
                    <span class="discreet">{t}Delivery time{/t}</span><br>{$parent->get('Delivery Time')}



                </td>
            </tr>


            <tr class="pdf_purchase_order_container  {if  $order->get('State Index') < 20 }hide{/if} " style="    border-bottom: 1px solid #ccc;">
                <td style="text-align: center;padding: 0px" colspan="2">
                    <a href="/pdf/production.purchase_order.pdf.php?id={$order->id}" target="_blank"><img class="button pdf_link"  style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>



                </td>
            </tr>





        </table>

    </div>

    <div class="block " style="align-items: stretch;flex: 1 ;padding-top: 0px">

        <div id="create_delivery"
             class="delivery_node {if   ({$order->get('State Index')|intval} < 20 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0) or $parent->get('Parent Skip Inputting')=='Yes' }hide{/if}"
             style="height:30px;clear:both;border-bottom:1px solid #ccc">
            <div id="back_operations">
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i class="fa fa-truck" aria-hidden="true"></i> {t}Delivery{/t}</span>
            <div id="forward_operations">
                <div id="received_operations"
                     class="order_operation">
                    <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                         title="{t}Input delivery note{/t}">
                        <i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i>
                    </div>
                </div>
            </div>
        </div>
        <div>

            {foreach from=$deliveries item=dn}

                <div class="delivery_node" style="height:30px;clear:both;border-bottom:1px solid #ccc">
                    <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button" onclick="change_view('production/{$order->get('Purchase Order Parent Key')}/delivery/{$dn->id}')">
                            <i class="fal fa-hand-holding-heart padding_right_5"></i> {$dn->get('Public ID')}</span> <span class="small">({$dn->get('State')})</span></span>
                </div>
            {/foreach}
        </div>



            <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px ">



        <table style="margin-bottom: 20px" >

            <tr style="border-bottom:1px solid #ccc;" >
                <td style="padding-top: 0px;padding-bottom: 0px" class="label">{t}Stock value{/t}</td>
                <td style="padding-top: 0px;padding-bottom: 0px"  class="aright Purchase_Order_Total_Amount">{$order->get('Total Amount')}</td>
            </tr>






        </table>


        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>
</div>

<div id="new_delivery" class="table_new_fields hide" style="margin:20px 0px;border:none">


    <div style="flex-grow: 1;border:none;text-align: right;padding-right: 30px"  >
        <span class="error_msg"></span>
        {t}Production sheet number{/t}
        <input class="new_delivery_field" id="delivery_number" style="width: 300px" value="{$order->get('Public ID')}" placeholder="{t}Production sheet number{/t}"/>
        <span style="flex-grow: 4" class="buttons save" onclick="save_create_delivery(this)"  ><span>{t}Save{/t}</span> <i class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true"></i></span>

    </div>




</div>
