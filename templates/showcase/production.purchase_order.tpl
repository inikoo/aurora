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

<div class="timeline_horizontal {if $order->get('Purchase Order State')=='Cancelled'   }hide{/if}">


    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span
                        class="start_date">{$order->get('Creation Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>


        <li id="production_node"
            class="li   {if $order->get('State Index')>=60}hide{/if}  {if $order->get('State Index')>=70 and $order->get('Max State Index')>=70   }complete{elseif $order->get('Max State Index')>=70 }semi_complete{/if}">
            <div class="label">
                <span class="state ">
                                        <span class="state ">{t}Manufacturing{/t} <span></i></span></span>
                </span>
            </div>
            <div class="timestamp">

                <span class="Purchase_Order_Estimated_Production_Date ">
                    {if $order->get('Estimated Production Formatted Date')==''}<span title="{t}No estimated production date{/t}"><i class="very_discreet far fa-exclamation-circle" ></i> <span class="very_discreet  italic">{$no_production_date_label}</span> </span>{else}{$order->get('Estimated Production Formatted Date')}{/if}
                </span>



            </div>
            <div class="industry">
            </div>
        </li>


        <li id="delivery_node"
            class="li  {if $order->get('State Index')<60}hide{/if}   {if $order->get('State Index')>=70 and $order->get('Max State Index')>=70   }complete{elseif $order->get('Max State Index')>=70 }semi_complete{/if}">
            <div class="label">
                <span class="state ">&nbsp;
                </span>
            </div>
            <div class="timestamp">
                <span >&nbsp;

                    {foreach from=$deliveries item=dn name=dns}
                        <span class="link" onclick="change_view('{$order->get('Purchase Order Parent')|lower}/{$order->get('Purchase Order Parent Key')}/delivery/{$dn->id}')">{$dn->get('Public ID')}</span>{if $smarty.foreach.dns.last}{else},{/if}

                    {/foreach}

                </span>
            </div>
            <div class="truck">
            </div>
        </li>



            <li id="dispatched_node"
                class="li  {if $order->get('State Index')>=70 and $order->get('Max State Index')>=70   }complete{elseif $order->get('Max State Index')>=70 }semi_complete{/if}">
                <div class="label">
                    <span class="state ">{t}Dispatched{/t} <span></i></span></span>
                </div>
                <div class="timestamp">
                    <span class="Supplier_Delivery_Dispatched_Date">&nbsp;</span>
                </div>
                <div class="dot">
                </div>
            </li>



            <li id="estimated_send_node" class=" li {if $order->get('State Index')>=80  }hide{/if}"  ">
                <div class="label">
                    <span class="state ">{t}Estimated delivery date{/t} <span></i></span></span>
                </div>
                <div class="timestamp">
                    <span class="Purchase_Order_Estimated_Receiving_Date">&nbsp;
                        {if $order->get('Estimated Receiving Formatted Date')==''}<span class="error" title="{t}No estimated delivery date{/t}"><i class=" fa fa-exclamation-circle" ></i> <span class="error discreet italic">{t}No estimated delivery{/t}</span> {else}{$order->get('Estimated Receiving Formatted Date')}{/if} &nbsp;</span>
                </div>
                <div class="dot">
                </div>
            </li>

        <li id="send_node" class=" li {if $order->get('State Index')>=80 and $order->get('Max State Index')>=80   }complete{elseif $order->get('Max State Index')>=80 }semi_complete{/if} ">
            <div class="label">
                <span class="state ">{t}Received{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Receiving_Date">&nbsp; &nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>

            <li id="checked_node"
                class="li  {if $order->get('State Index')>=90 and $order->get('Max State Index')>=90   }complete{elseif $order->get('Max State Index')>=90 }semi_complete{/if} ">
                <div class="label">
                    <span class="state">{t}Checked{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Supplier_Delivery_Checked_Percentage_or_Date">&nbsp;</span>
                </div>
                <div class="dot">
                </div>
            </li>




            <li id="placed_node"
                class="li {if $order->get('State Index')>=100 and $order->get('Max State Index')>=100   }complete{elseif $order->get('Max State Index')>=100 }semi_complete{/if} ">
                <div class="label">
                    <span class="state">{t}Booked in{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Supplier_Delivery_Placed_Percentage_or_Date">&nbsp;</span>
                </div>
                <div class="dot">
                </div>
            </li>

            <li id="costing_done_node"
                class="li {if $order->get('State Index')>=110 and $order->get('Max State Index')>=110   }complete{elseif $order->get('Max State Index')>=110 }semi_complete{/if} ">
                <div class="label">
                    <span class="state">{t}Costing done{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Supplier_Delivery_Costing_Date">&nbsp;</span>
                </div>
                <div class="dot">
                </div>
            </li>









    </ul>
</div>

<div class="timeline_horizontal  {if $order->get('Purchase Order State')!='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span
                        class="start_date">{$order->get('Creation Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="send_node" class="li  cancelled">
            <div class="label">
                <span class="state ">{t}Cancelled{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Cancelled_Date">{$order->get('Cancelled Date')} </span>
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
                    <span ><i class="fa fa-bars fa-fw discreet" aria-hidden="true"></i> <span class="Purchase_Order_Number_Items">{$order->get('Number Items')}</span></span>
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
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
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
                        <span class="fa-stack" onclick="toggle_order_operation_dialog('undo_submit')"> <i
                                    class="fa fa-fill-drip discreet " aria-hidden="true"></i> <i
                                    class="fa fa-ban fa-stack-1x discreet error"></i> </span>
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

            </div>
            <span style="float: left;padding-left: 10px" class="Purchase_Order_State"> {$order->get('State')} </span>
            <div id="forward_operations">


                <div id="submit_operations" class="order_operation {if $order->get('Purchase Order State')!='InProcess'  or  $order->get('Purchase Order Number Items')==0 }hide{/if}">
                    <div id="submit_operation"
                         class="square_button right"
                         title="{t}Start manufacturing{/t}">
                        <i class="fa fa-fill-drip   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Start manufacturing{/t}</td>
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

                <div id="quick_create_delivery_operations"
                     class="order_operation {if ( $order->get('State Index') < 20 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0) or $parent->get('Parent Skip Inputting')=='No' }hide{/if}">
                    <div id="quick_create_delivery_operation" class="square_button right  "
                         title="{t}Fill production sheet{/t}">
                        <i class="fa fa-clipboard-check   " aria-hidden="true" onclick="quick_create_delivery()"></i>
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
                            <i class="fal fa-clipboard-check"></i> {$dn->get('Public ID')}</span> <span class="small">({$dn->get('State')})</span></span>
                </div>
            {/foreach}
        </div>



            <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>

<style>


    </style>

<div id="new_delivery" class="table_new_fields hide">
    <div style="align-items: stretch;flex: 1;padding:20px 5px;">




    </div>
    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
        <table style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td class="label ">{t}Delivery number{/t}</td>
                <td>
                    <input class="new_delivery_field" id="delivery_number" placeholder="{t}Delivery number{/t}">
                </td>
            </tr>
            <tr>
                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                       onclick="close_create_delivery()"></i></td>
                <td class="buttons save" onclick="save_create_delivery(this)"><span>{t}Save{/t}</span> <i
                            class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true"></i>
                    <span class="error_msg"></span>
                </td>
            </tr>
        </table>
    </div>
</div>
