{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 July 2016 at 01:12:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div class="timeline_horizontal">

    <ul class="timeline" id="timeline">

        {if $delivery->get('Supplier Delivery Key')}
            <li id="purchase_order_node" class="li complete">
                <div class="label">
                    <span class="state "><i class="far fa-clipboard fa-fw"></i> {t}Submitted{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Purchase_Order_Submitted_Date">&nbsp;{$delivery->get('PO Submitted Date')}</span> <span
                            class="start_date Purchase_Order_Creation_Date">{$delivery->get('PO Creation Date')} </span>
                </div>
                <div class="dot">

                </div>
            </li>
            <li id="inputted_node" class="li  complete">
                <div class="label">
                    <span class="state">&nbsp; <span>{t}Created{/t}</span></span>
                </div>
                <div class="timestamp">
                    <span class="Supplier_Delivery_Creation_Date">&nbsp;{$delivery->get('Creation Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}

        {if $delivery->get('State Index')<0 and $delivery->get('Dispatched Date')=='' and  $delivery->get('Received Date')==''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}


        <li id="dispatched_node"
            class="li  {if $delivery->get('State Index')>=30 or ($delivery->get('State Index')<0 and ($delivery->get('Dispatched Date')!=''  or $delivery->get('Received Date')!=''))  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Dispatched_Date">&nbsp;{$delivery->get('Dispatched Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $delivery->get('State Index')<0 and $delivery->get('Dispatched Date')!='' and  $delivery->get('Received Date')=='' and    $delivery->get('Checked Date')==''     }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}


        <li id="received_node"
            class="li  {if $delivery->get('State Index')>=40  or ($delivery->get('State Index')<0 and  $delivery->get('Received Date')!='')  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Received{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Received_Date">&nbsp;{$delivery->get('Received Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $delivery->get('State Index')<0  and  $delivery->get('Received Date')!='' and  $delivery->get('Checked Date')==''   }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}

        <li id="checked_node"
            class="li  {if $delivery->get('State Index')>=50  or ($delivery->get('State Index')<0 and  $delivery->get('Checked Date')!='')   }complete{/if}">
            <div class="label">
                <span class="state">{t}Checked{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Checked_Percentage_or_Date">&nbsp;{$delivery->get('Checked Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $delivery->get('State Index')<0  and  $delivery->get('Checked Date')!=''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}


        <li id="placed_node"
            class=" {if $delivery->get('State Index')<0}hide{/if} li {if $delivery->get('State Index')>=100}complete{/if}">
            <div class="label">
                <span class="state">{t}Booked in{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Placed_Percentage_or_Date">&nbsp;{$delivery->get('Placed Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="costing_done_node"
            class=" {if $delivery->get('State Index')<0}hide{/if} li {if $delivery->get('State Index')>=110}complete{/if}">
            <div class="label">
                <span class="state">{t}Costing done{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Costing_Date">&nbsp;{$delivery->get('Costing Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>
    </ul>
</div>
<div class="order" style="display: flex;" data-object='{$object_data}'>
    <div class="block" style=" align-items: stretch;flex: 1">



        <div class="data_container" style="padding:5px 10px">
            <div class="data_field" style="margin:5px 0px 15px 0px ">
                <i class="fa fa-ship fa-fw" aria-hidden="true" title="{t}Supplier{/t}"></i> <span
                        onclick="change_view('{if $delivery->get('Supplier Delivery Parent')=='Supplier'}supplier{else}agent{/if}/{$delivery->get('Supplier Delivery Parent Key')}')"
                        class="link Supplier_Delivery_Parent_Name">{$delivery->get('Supplier Delivery Parent Name')}</span>
            </div>
            <div class="data_field">
                <i class="fa fa-share fa-fw" aria-hidden="true" title="Incoterm"></i> <span
                        class="Supplier_Delivery_Incoterm">{if $delivery->get('Supplier Delivery Incoterm')==''}<i class="error fa fa-exclamation-circle" style="margin-right: 0px"></i> <span class="very_discreet error italic">{t}Incoterm not set{/t}</span>{else}{$delivery->get('Supplier Delivery Incoterm')}{/if}</span>
            </div>
            <div class="data_field">
                <i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true" title="{t}Port of export{/t}"></i> <span
                        class="Supplier_Delivery_Port_of_Export">{if $delivery->get('Port of Export')==''}<i class="error fa fa-exclamation-circle" style="margin-right: 0px"></i> <span class="very_discreet error italic">{t}Port of export not set{/t}</span>{else}{$delivery->get('Port of Export')}{/if}</span>
            </div>
            <div class="data_field">
                <i class="fa fa-arrow-circle-left fa-fw" aria-hidden="true" title="{t}Port of import{/t}"></i> <span
                        class="Supplier_Delivery_Port_of_Import">{if $delivery->get('Port of Import')==''}<i class="error fa fa-exclamation-circle" style="margin-right: 0px"></i> <span class="very_discreet error italic">{t}Port of import not set{/t}</span>{else}{$delivery->get('Port of Import')}{/if}</span>
            </div>
        </div>
        <div style="clear:both">
        </div>
    </div>




    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='InProcess'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}delete{/t}">
                        <i class="far fa-trash-alt very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete delivery{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "object": "SupplierDelivery", "key":"{$delivery->id}"}'
                                            id="received_save_buttons" class="error save button"
                                            onClick="delete_object(this)"><span class="label">{t}Delete{/t}</span> <i
                                                class="fa fa-trash fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')=='InProcess' or $delivery->get('Supplier Delivery State')=='Cancelled' or $delivery->get('Supplier Delivery Placed Items')=='Yes'   }hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Cancel delivery{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "Cancelled","dialog_name":"cancel", "field": "Supplier Delivery State"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)"><span
                                                class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_dispatched_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='Dispatched'}hide{/if}">
                    <div class="square_button left" title="{t}Unmark as dispatched{/t}">
						<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_dispatched')">
						<i class="fa fa-arrow-circle-right discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
                        <table id="undo_dispatched_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Unmark as dispatched{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_dispatched')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "InProcess","dialog_name":"undo_dispatched", "field": "Supplier Delivery State"}'
                                            id="undo_dispatched_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_received_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='Received'  or $delivery->get('Supplier Delivery Placed Items')=='Yes' }hide{/if}">
                    <div class="square_button left" title="{t}Unmark as received{/t}">
						<span class="fa-stack" style="position:relative;top:-1px"
                              onclick="toggle_order_operation_dialog('undo_received')">
						<i class="fa fa-arrow-circle-down discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
                        <table id="undo_received_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Unmark as received{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_received')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "InProcess or Dispatched","dialog_name":"undo_received", "field": "Supplier Delivery State"}'
                                            id="undo_received_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_costing_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='InvoiceChecked'  }hide{/if}">
                    <div class="square_button left" title="{t}Redo costing{/t}">
						<span class="fa-stack" style="position:relative;top:-1px"
                              onclick="toggle_order_operation_dialog('undo_costing')">
						<i class="fal fa-box-usd super_discreet " aria-hidden="true"></i>
						<i class="far fa-undo-alt fa-stack-1x  "></i>
						</span>
                        <table id="undo_costing_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Go back to delivery costing{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_costing')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "RedoCosting","dialog_name":"undo_costing", "field": "Supplier Delivery State"}'
                                            id="undo_costing_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Redo costing{/t}</span> <i
                                                class="fa fa-undo-alt fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Supplier_Delivery_State"> {$delivery->get('State')} </span>
            <div id="forward_operations">

                <div id="received_operations"
                     class=" order_operation {if !($delivery->get('Supplier Delivery State')=='InProcess' or  $delivery->get('Supplier Delivery State')=='Dispatched') }hide{/if}">
                    <div class="square_button right" title="{t}Mark delivery as received{/t}">
                        <i class="fa fa-arrow-circle-down fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('received')"></i>
                        <table id="received_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Mark delivery as received{/t}</td>
                            </tr>
                            <tr class="top">
                                <td class="label">{t}Date{/t}</td>
                                <td>
                                    <input id="received_date" type="hidden" value="{$smarty.now|date_format:'%Y-%m-%d'}"
                                           ovalue="{$smarty.now|date_format:'%Y-%m-%d'}" has_been_valid="0"/>
                                    <input id="received_date_time" type="hidden"
                                           value="{$smarty.now|date_format:'%H:%M:%S'}"/>
                                    <input id="received_date_formatted" class="option_input_field"
                                           data-settings='{ "type": "datetime","id":"received_date", "field": "Supplier Delivery Received Date"}'
                                           style="width:8em" value="{$smarty.now|date_format:'%d/%m/%Y'}"/>
                                    <span id="received_date_msg" class="msg"></span>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="received_date_datepicker" class="hide datepicker">
                                    </div>
                                </td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('received')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Supplier Delivery State","value": "Received","dialog_name":"received"}'
                                            id="received_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="dispatched_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='InProcess'}hide{/if}">
                    <div class="square_button right" title="{t}Mark as dispatched{/t}">

                        <i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('dispatched')"></i>


                        <table id="dispatched_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label"
                                    colspan="2">{if $delivery->get('Supplier Delivery Parent')=='Supplier'}{t}Delivery dispatched by supplier{/t}{else}Delivery dispatched by agent{/if}</td>
                            </tr>
                            <tr class="top">
                                <td class="label">{t}Date{/t}</td>
                                <td>
                                    <input id="dispatched_date" type="hidden"
                                           value="{$smarty.now|date_format:'%Y-%m-%d'}"
                                           ovalue="{$smarty.now|date_format:'%Y-%m-%d'}" has_been_valid="0"/>
                                    <input id="dispatched_date_time" type="hidden"
                                           value="{$smarty.now|date_format:'%H:%M:%S'}"/>
                                    <input id="dispatched_date_formatted" class="option_input_field"
                                           data-settings='{ "type": "datetime","id":"dispatched_date", "field": "Supplier Delivery Dispatched Date"}'
                                           style="width:8em" value="{$smarty.now|date_format:'%d/%m/%Y'}"/>
                                    <span id="dispatched_date_msg" class="msg"></span>
                                    <script></script>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="dispatched_date_datepicker" class="hide datepicker">
                                    </div>
                                </td>
                            </tr>


                            <tr class="buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('dispatched')"></i></td>
                                <td class="aright">
								<span data-data='{ "value": "Dispatched","dialog_name":"dispatched", "field": "Supplier Delivery State"}'
                                      id="dispatched_save_buttons" class="valid save button changed"
                                      onclick="save_order_operation(this)">
								<span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                          aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="costing_operations"
                     class="order_operation {if $delivery->get('Supplier Delivery State')!='Placed'}hide{/if}">
                    <div class="square_button right" title="{t}Start checking costs{/t}">

                        <i class="far fa-box-usd fa-fw" aria-hidden="true" data-data='{ "value": "Costing","dialog_name":"costing", "field": "Supplier Delivery State"}'
                           onclick="save_order_operation(this)"></i>


                        <table id="costing_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label"
                                    colspan="2">{t}Start checking costs{/t}</td>
                            </tr>




                            <tr class="buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('costing')"></i></td>
                                <td class="aright">
								<span data-data='{ "value": "Costing","dialog_name":"costing", "field": "Supplier Delivery State"}'
                                      id="costing_save_buttons" class="valid save button changed"
                                      onclick="save_order_operation(this)">
								<span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                          aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>



        <table border="0" class="info_block acenter">

            <tr>

                <td style="text-align: center" colspan="2">

                    <span style="padding-right:20px"  title="{t}Number of items{/t}" ><i class="fa fa-bars fa-fw discreet"></i> <span class="Supplier_Delivery_Number_Items">{$delivery->get('Number Items')}</span></span>
                    <span><i class="fa fa-box-check fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Supplier_Delivery_Number_Received_and_Checked_Items">{$delivery->get('Number Received and Checked Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-inventory fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Supplier_Delivery_Number_Placed_Items">{$delivery->get('Number Placed Items')}</span></span>
                </td>
            </tr>

            <tr>

                <td style="text-align: center" title="{t}Weight{/t}"><i class="far fa-weight-hanging {if $delivery->get('Supplier Delivery Weight')==''}hide{/if}"></i> <span class="Supplier_Delivery_Weight">{$delivery->get('Weight')}</span></td>


                <td style="text-align: center" class=" Supplier_Delivery_CBM" title="{t}CBM{/t}">{$delivery->get('CBM')}</td>
            </tr>


            <tr  class="Mismatched_Items {if $delivery->get('Supplier Delivery Number Received and Checked Items')==0}hide{/if} ">
                <td style="text-align: center;padding-top: 20px"  title="{t}Items under delivered{/t}"><i class="far fa-box-open"></i>  <span class="Supplier_Delivery_Number_Under_Delivered_Items">{$delivery->get('Number Under Delivered Items')}</span></td>

                <td  style="text-align: center;padding-top: 20px" title="{t}Items over delivered{/t}"><i class="fa fa-box-full"></i>  <span  class=" Supplier_Delivery_Number_Over_Delivered_Items">{$delivery->get('Number Over Delivered Items')}</span></td>


            </tr>

        </table>



    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">


        <div class="node  Invoice_Info {if $delivery->get('State Index')!=110}hide{/if} " >
                    <span class="node_label">
                        <i class="fal fa-file-invoice-dollar fa-fw  aria-hidden="true"></i>
                        <span class="Formatted_Invoice_Public_ID margin_right_10">{$delivery->get('Formatted Invoice Public ID')}</span>
                                                <span class="italic Formatted_Invoice_Date">{$delivery->get('Formatted Invoice Date')}</span>

                    </span>


        </div>

        <div style="clear:both">

        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px ">



        <table border="0" class="info_block">


            <tr class="hide" style="border-bottom:1px solid #ccc;" >
                <td class="label">{t}Purchase order amount{/t} </td>
                <td class="aright Supplier_Delivery_Items_Amount">{$delivery->get('Purchase Order Amount')}</td>
            </tr>


            <tr style="height: 16px" class="{if $account->get('Account Currency')==$delivery->get('Supplier Delivery Currency Code')}hide{/if}">
                <td class="small" colspan="2" style="border-bottom:1px solid #ccc;text-align: center">
                    {t}Supplier invoice currency{/t} <b>{$delivery->get('Supplier Delivery Currency Code')}</b>
                </td>



            </tr>


            <tr style="border-bottom:1px solid #eee;" >
                <td class="label">{t}Items{/t} </td>
                <td class="aright Supplier_Delivery_Items_Amount">{$delivery->get('Items Amount')}</td>
            </tr>

            <tr  >
                <td class="label">{t}Extra costs{/t} </td>
                <td class="aright Supplier_Delivery_Extra_Costs_Amount">{$delivery->get('Extra Costs Amount')}</td>
            </tr>

            <tr style="border-bottom:1px solid #ccc;border-top:1px solid #ccc;" >
                <td class="label">{t}Total{/t} </td>
                <td class="aright Supplier_Delivery_Total_Amount">{$delivery->get('Total Amount')}</td>
            </tr>


            <tbody class="{if $account->get('Account Currency')==$delivery->get('Supplier Delivery Currency Code')}hide{/if}">

            <tr style="height: 16px" class="{if $account->get('Account Currency')==$delivery->get('Supplier Delivery Currency Code')}hide{/if}">
                <td class="small" colspan="2" style="border-bottom:1px solid #ccc;text-align: center">
                    1 {$account->get('Currency Code')}={math equation="1/x" x=$delivery->get('Supplier Delivery Currency Exchange') format="%.5f"} {$delivery->get('Supplier Delivery Currency Code')}

                </td>



            </tr>

            <tr  >
                <td class="label">{t}Subtotal{/t}</td>
                <td class="aright Supplier_Delivery_AC_Subtotal_Amount">{$delivery->get('AC Subtotal Amount')}</td>
            </tr>
            <tr style="border-top:1px solid #eee;" >
                <td class="label">{t}Extra costs{/t}</td>
                <td class="aright Supplier_Delivery_AC_Extra_Costs_Amount">{$delivery->get('AC Extra Costs Amount')}</td>
            </tr>


            <tr style="border-top:1px solid #ccc;" >

                <td class="label">{t}Total{/t}</td>


                <td class="Supplier_Delivery_AC_Total_Amount aright ">{$delivery->get('AC Total Amount')}</td>
            </tr>

            </tbody>

        </table>
        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>


<div id="new_delivery" class="table_new_fields hide">
    <div style="align-items: stretch;flex: 1;padding:20px 5px;border-right:1px solid #eee">
        <i key="" class="far fa-square fa-fw button" aria-hidden="true"></i> <span>{t}Select all{/t}</span>
    </div>
    <div style="align-items: stretch;flex: 1;padding:10px 20px;">
        <table border="0" style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td class="label ">{t}Delivery Number{/t}</td>
                <td>
                    <input class="new_delivery_field" id="delivery_number" placeholder="{t}Delivery number{/t}"></td>
            </tr>
            <tr>
                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                       onclick="close_create_delivery()"></i></td>
                <td class="buttons save" onclick="save_create_delivery()"><span>{t}Save{/t}</span>
                    <i class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true"></i>

                </td>
            </tr>
        </table>
    </div>
</div>

<div id="location_results_container" class="search_results_container" style="width:220px;">
    <table id="location_results" border="0" style="background:white;">
        <tr class="hide" style=";" id="location_search_result_template" field="" value="" formatted_value=""
            onclick="select_location_option(this)">
            <td class="label" style="padding-left:5px;"></td>
        </tr>
    </table>
</div>


<div id="assign_barcode_to_part_results_container" class="search_results_container" style="width:420px;">
    <table id="assign_barcode_to_part_results" border="0" style="background:white;">
        <tr class="hide" style=";" id="assign_barcode_to_part_search_result_template" field="" value="" formatted_value=""
            onclick="select_assign_barcode_to_part_option(this)">
            <td class="code" style="padding-left:5px;"></td>
            <td class="label" style="padding-left:5px;"></td>
        </tr>
    </table>
</div>


<script>

    $('#new_delivery').on('input propertychange', '.new_delivery_field', function (evt) {


        var field_id = $(this).attr('id')

        if (field_id == 'delivery_number') {

            $(this).closest('table').find('td.buttons i').addClass('fa-spinner fa-spin').removeClass('fa-cloud')


            var object_data = $('#object_showcase div.order').data("object")
            console.log(object_data)
            var value = $(this).val()

            var parent = object_data.order_parent
            var parent_key = object_data.order_parent_key
            var object = 'Supplier Delivery'
            var key = ''
            var field = 'Supplier Delivery Public ID'

            if (value == '') {
                $(this).closest('table').find('td.buttons').removeClass('changed')
            } else {

                $(this).closest('table').find('td.buttons').addClass('changed')
                var request = '/ar_validation.php?tipo=check_for_duplicates&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify({
                            option: 'creating_dn_from_po'
                        })

                console.log(request)


                $.getJSON(request, function (data) {


                    $('#' + field_id).removeClass('waiting invalid valid')
                    $('#' + field_id).closest('table').find('td.buttons').removeClass('waiting invalid valid')

                    $('#' + field_id).closest('table').find('td.buttons i').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


                    if (data.state == 200) {

                        var validation = data.validation
                        var msg = data.msg

                    } else {
                        var validation = 'invalid'
                        var msg = "Error, can't verify value on server"

                    }
                    $('#' + field_id).closest('table').find('td.buttons').addClass(validation)


                })
            }


        }

    });


    $(function () {
        $("#received_date_datepicker").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            defaultDate: new Date('{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}'),
            altField: "#received_date",
            altFormat: "yy-mm-dd",
            //minDate: new Date({$min_date_send_order}),
            //maxDate: 0,
            onSelect: function () {
                $('#received_date').change();
                $('#received_date_formatted').val('xx');

                //     var date = $(this).datepicker("getDate");
                $('#received_date_formatted').val($.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate")))

                $('#received_date_datepicker').addClass('hide')
            }
        });
    });


    $('#received_date_formatted').focusin(function () {
        $('#received_date_datepicker').removeClass('hide')

    });

    $('#received_date_formatted').on('input', function () {
        var date = chrono.parseDate($('#received_date_formatted').val())

        if (date == null) {
            var value = '';
        } else {
            var value = date.toISOString().slice(0, 10)
            $("#received_date_datepicker").datepicker("setDate", date);
        }


        $('#received_date').val(value)
        $('#received_date').change();

    });

    $('#received_date').on('change', function () {
        on_changed_value('received_date', $('#received_date').val())

    });

    if ('{$smarty.now|date_format:"%Y-%m-%d %H-%M-%S"}' == '') {
        $('#received_date').val('')
    }


    $(function () {
        $("#send_date_datepicker").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            defaultDate: new Date('{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}'),
            altField: "#send_date",
            altFormat: "yy-mm-dd",
            //minDate: new Date({$min_date_send_order}),
            //maxDate: 0,

            onSelect: function () {
                $('#send_date').change();
                $('#send_date_formatted').val('xx');

                //     var date = $(this).datepicker("getDate");
                $('#send_date_formatted').val($.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate")))

                $('#send_date_datepicker').addClass('hide')
            }
        });
    });

    $('#send_date_formatted').focusin(function () {
        $('#send_date_datepicker').removeClass('hide')

    });

    $('#send_date_formatted').on('input', function () {
        var date = chrono.parseDate($('#send_date_formatted').val())

        if (date == null) {
            var value = '';
        } else {
            var value = date.toISOString().slice(0, 10)
            $("#send_date_datepicker").datepicker("setDate", date);
        }


        $('#send_date').val(value)
        $('#send_date').change();

    });
    $('#send_date').on('change', function () {
        on_changed_value('send_date', $('#send_date').val())

    });

    if ('{$smarty.now|date_format:"%Y-%m-%d %H-%M-%S"}' == '') {
        $('#send_date').val('')
    }


    $('#tab').on('input propertychange', '.location_code', function (evt) {
        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_location_code_field($(this), delay)
    });
    $('#tab').on('input propertychange', '.place_qty', function (evt) {
        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_place_qty_field($(this), delay)
    });


</script>
