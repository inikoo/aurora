{assign deliveries $order->get_deliveries('objects')}
{assign returns $order->get_returns('objects')}

{assign invoices $order->get_invoices('objects')}
{assign deleted_invoices $order->get_deleted_invoices('objects')}

{assign payments $order->get_payments('objects','Completed')}


<div class="sticky_notes">


<div  class="sticky_note_container order_customer_sticky_note {if $order->get('Order Customer Message')==''}hide{/if}"    >
    <i style="top:30px" class="fas fa-clone button fa-fw copy_to_delivery_note_sticky_note" aria-hidden="true"></i>

    <div class="sticky_note" >{$order->get('Order Customer Message')|strip_tags|escape}</div>
</div>



{include file="sticky_note.tpl" _scope="order_sticky_note" value=$order->get('Sticky Note') object="Order" key="{$order->id}" field="Order_Sticky_Note"  }
{include file="sticky_note.tpl" _scope="delivery_note_sticky_note" value=$order->get('Delivery Sticky Note') object="Order" key="{$order->id}" field="Order_Delivery_Sticky_Note"  }
</div>
<div style="clear:both;" class="timeline_horizontal with_time   {if $order->get('State Index')<0}hide{/if}  ">

    <ul class="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Order_Submitted_Date">&nbsp;{$order->get('Submitted by Customer Date')}</span> <span class="start_date">{$order->get('Created Date')}</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="in_warehouse_node" class="li {if $order->get('State Index')>=40}complete{/if} ">
            <div class="label">
                <span class="state">&nbsp;{t}In warehouse{/t}&nbsp;<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Order_In_Warehouse" ">&nbsp;

                <span class="Order_Send_to_Warehouse_Date">&nbsp;{$order->get('Send to Warehouse Date')}</span>

                &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="packed_done_node" class="li {if $order->get('State Index')>=80}complete{/if} ">
            <div class="label">
                <span class="state">{t}Packed & Closed{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Packed_Done_Date">&nbsp;{$order->get('Packed Done Date')}</span></span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="approved_node" class="li {if $order->get('State Index')>=90}complete{/if} ">
            <div class="label">
                <span class="state">{t}Invoiced{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Invoiced_Date">&nbsp;{$order->get('Invoiced Date')}</span></span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="dispatched_node" class="li  {if $order->get('State Index')>=100}complete{/if}">
            <div class="label">
                <span class="state">{t}Dispatched{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Dispatched_Date"> {$order->get('Dispatched Date')}</span>
                    &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

    </ul>

    {foreach from=$deliveries item=dn name=delivery_notes}
        <ul class="timelinex hide">

            <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
                <div class="label">
                    <span class="state ">{t}Submitted{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Order_Submitted_Date">&nbsp;{$order->get('Submitted by Customer Date')}</span> <span class="start_date">{$order->get('Created Date')} </span>
                </div>
                <div class="dot"></div>
            </li>

            <li id="send_node" class="li  {if $dn->id}complete{/if} ">
                <div class="label">
                    <span class="state" style="position:relative;left:5px">&nbsp;{$dn->get('ID')}&nbsp;<span></i></span></span>
                </div>
                <div class="timestamp">
			<span class="Deliveries_Public_IDs" style="position:relative;left:5px">&nbsp;

                    <span class="">&nbsp;{$dn->get('Creation Date')}&nbsp;</span>

                &nbsp;</span>
                </div>
                <div class="truck"></div>
            </li>

            <li id="warehouse_node" class="li  {if $dn->get('State Index')>=80}complete{/if}">
                <div class="label">
                    <span class="state ">&nbsp;{$dn->get('State')}&nbsp;<span></i></span></span>
                </div>
                <div class="timestamp">
                <span class="Order_Inputted_Date">&nbsp;<span class="">{$dn->get('Picking and Packing Percentage or Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>

            <li class="li {if $order->get('State Index')>=90}complete{/if}">
                <div class="label">
                    <span class="state">{t}Dispatch approved{/t}</span>
                </div>
                <div class="timestamp">
                <span>&nbsp;<span class="{if $smarty.foreach.delivery_notes.index != 0}hide{/if}">{$dn->get('Dispatched Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>

            <li class="li {if $order->get('State Index')==100}complete{/if}">
                <div class="label">
                    <span class="state">{t}Dispatched{/t}</span>
                </div>
                <div class="timestamp">
                <span>&nbsp;<span class="{if $smarty.foreach.delivery_notes.index != 0}hide{/if}">{$dn->get('Dispatched Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>
        </ul>
    {/foreach}
</div>


<div class="timeline_horizontal  {if $order->get('State Index')>0}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span class="start_date">{$order->get('Created Date')} </span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="send_node" class="li  cancelled">
            <div class="label">
                <span class="state ">{t}Cancelled{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Cancelled_Date">{$order->get('Cancelled Date')} </span>
            </div>
            <div class="dot"></div>
        </li>


    </ul>
</div>

<div id="order" class="order" style="display: flex;" data-object='{$object_data}' order_key="{$order->id}"  data-state_index="{$order->get('State Index')}" >
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  " style="margin-bottom:10px">

              <span class="button" onclick="change_view('customers/{$order->get('Order Store Key')}/{$order->get('Order Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                          class="button Order_Customer_Name">{$order->get('Order Customer Name')}</span> <span

                          class="link Order_Customer_Key">{$order->get('Order Customer Key')|string_format:"%05d"}</span>
              </span>
            </div>



            <div class="data_field small  " style="margin-top:5px">

            <span id="display_telephones"></span>
                {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                <div id="Customer_Main_Plain_Mobile_display"
                     class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                    <i class="fal fa-fw fa-mobile"></i> <span class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                </div>
                <div id="Customer_Main_Plain_Telephone_display"
                     class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                    <i class="fal fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                </div>
                {else}
                <div id="Customer_Main_Plain_Telephone_display"
                     class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                    <i title="Telephone" class="fa fa-fw fa-phone"></i> <span  class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                </div>
                <div id="Customer_Main_Plain_Mobile_display"
                     class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                    <i title="Mobile" class="fal fa-fw fa-mobile"></i> <span
                            class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                </div>
                {/if}
            </div>

            <div class="data_field small {if $customer->get('Customer Main Plain Email')==''}hide{/if}" style="margin-top:5px">
                <div class="">
                    <i class="fal fa-envelope fa-fw" title="{t}Email{/t}"></i> {if $customer->get('Customer Main Plain Email')!=''}{mailto address=$customer->get('Customer Main Plain Email')}{/if}
                </div>
            </div>

            <div class="data_field  " style="padding:10px 0px 20px 0px;">
                <div style="float:left;padding-bottom:20px;padding-right:20px" class="Delivery_Address">
                    <div style="margin-bottom:10px">
                        <span class="{if $order->get('Order For Collection')=='Yes'}hide{/if}"><i class="   fa fa-truck fa-flip-horizontal button" aria-hidden="true""></i>{t}Deliver to{/t}</span>
                        <span class="{if $order->get('Order For Collection')=='No'}hide{/if}"><i class="   far fa-hand-rock fa-flip-horizontal button" aria-hidden="true""></i>{t}Collection{/t}</span>

                    </div>

                    <div class="small Order_Delivery_Address " style="max-width: 140px;">{$order->get('Order Delivery Address Formatted')}</div>
                </div>
                <div style="float:right;padding-bottom:20px;" class="Billing_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-dollar-sign button" aria-hidden="true""></i>{t}Billed to{/t}</div>
                    <div class="small Order_Invoice_Address" style="max-width: 140px;">{$order->get('Order Invoice Address Formatted')}</div>
                </div>
                <div style="clear:both"></div>
            </div>


        </div>
        <div style="clear:both"></div>
    </div>

    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px">


        <div class="state warning_block  priority_label  {if $order->get('Order Priority Level')=='Normal' or $order->get('State Index')<=10 or $order->get('State Index')==100  }hide{/if}  " style="height:30px;text-align: center;line-height: 30px">
            {t}Priority{/t}  <i title="'._('Priority dispatching').'" style="font-size: 25px" class="fas fa-shipping-fast"></i>
        </div>

        <div class="state" style="height:30px;">
            <div id="back_operations" >

                <div id="cancel_operations" class="order_operation {if $order->get('State Index')<0 or  $order->get('State Index')>=40 }hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true" onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2">{t}Cancel order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td>
                                    <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i>
                                </td>
                                <td class="aright">
                                    <span data-data='{ "field": "Order State","value": "Cancelled","dialog_name":"cancel"}' id="cancel_save_buttons" class="error save button" onclick="save_order_operation(this)">
                                        <span class="label">{t}Cancel{/t}</span>
                                        <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_submit_operations" class="order_operation {if $order->get('State Index')!=30}hide{/if}">
                    <div class="square_button left" title="{t}Send back to basket{/t}">


                        <i class="fal fa-shopping-basket discreet error button" aria-hidden="true"  onclick="toggle_order_operation_dialog('undo_submit')"></i>




                        <table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send back to basket{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "InBasket","dialog_name":"undo_submit"}' id="undo_submit_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_send_operations" class="order_operation {if $order->get('Order State')!='Send'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}Unmark as send{/t}">
						<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_send')">
						<i class="fa fa-plane discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
                        <table id="undo_send_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Unmark as send{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_send')"></i></td>
                                <td class="aright"><span id="undo_send_save_buttons" class="valid save button" onclick="save_order_operation('undo_send','Submitted')"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Order_State"> {$order->get('State')} </span>
            <div id="forward_operations" >





                <div id="proforma_operations" oncontextmenu="return false;"
                     class="order_operation {if  $order->get('State Index')<10 or  $order->get('State Index')>=80    or  $order->get('Order Number Items')==0 }hide{/if}">
                    <div class="square_button right proforma_button " data-order_key="{$order->id}" title="{t}Proforma invoice{/t}">
                        <span><i class="fal fa-file-alt   " style="color:darkseagreen" aria-hidden="true"></i></span>

                    </div>

                    <div class="proforma_dialog options_dialog  hide" style="min-width: 150px" data-data='{ "type":"proforma","order_key":{$order->id}}'>
                        <i onclick="$('.proforma_dialog').addClass('hide')" style="float: right;margin-left: 10px" class="fa fa-window-close button"></i>
                        <h2 class="unselectable">{t}Proforma invoice{/t}</h2>

                        <table>
                            <tbody>
                            <tr data-field='rrp' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far {if $pdf_with_rrp}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_rrp}class="discreet"{/if}>{t}Recommended retail prices{/t}</span>
                                </td>

                            </tr>
                            <tr data-field='parts' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Parts{/t}</span>
                                </td>
                            </tr>
                            <tr data-field='commodity' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far {if $pdf_with_commodity}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_commodity}class="discreet"{/if}>{t}Commodity codes{/t}</span>
                                </td>

                            </tr>
                            <tr data-field='weight' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Weight{/t}</span>
                                </td>
                            </tr>
                            <tr data-field='origin' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Country of origin{/t}</span>
                                </td>
                            </tr>

                            <tr data-field='locale' class="button pdf_option {if !$pdf_show_locale_option}hide{/if}" onclick="check_field_value(this)">
                                <td>
                                    <i class="far fa-square margin_right_10" data-value="en_GB"></i> <span class="discreet">{t}English{/t}</span>
                                </td>
                            </tr>
                            </tbody>
                            <tr>
                                <td>
                                    <img class="button" onclick="download_pdf(this)" style="width: 50px;height:16px;margin-top:10px" src="/art/pdf.gif">
                                </td>

                            </tr>
                        </table>


                    </div>


                </div>

                <div id="create_refund_operations" class="order_operation {if !($order->get('State Index')==100   )     }hide{/if}">
                    <div class="square_button right  " title="{t}Create refund{/t}">
                        <i class="fal fa-file-alt error " aria-hidden="true" onclick="toggle_order_operation_dialog('create_refund')"></i>
                        <table id="create_refund_dialog" border="0" class="order_operation_dialog hide" style="color:#777">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create refund{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('create_refund')"></i></td>
                                <td class="aright"><span id="create_refund_save_buttons" class="valid save button" onclick="change_view('orders/{$order->get('Store Key')}/{$order->id}/refund/new',{ tab:'refund.new.items'})"><span
                                                class="label">{t}Next{/t}</span> <i class="fa fa-arrow-right fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="create_credit_note_operations" class="order_operation hide {if !( $order->get('Order To Pay Amount')<=0)       }hide{/if}">
                    <div class="square_button right  " title="{t}Create credit note{/t}">
                        <i class="fal money-check-alt " aria-hidden="true" onclick="toggle_order_operation_dialog('create_credit_note')"></i>
                        <table id="create_credit_note_dialog" border="0" class="order_operation_dialog hide" style="color:#777">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create credit note{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('create_credit_note')"></i></td>
                                <td class="aright"><span id="create_credit_note_save_buttons" class="valid save button" onclick="change_view('orders/{$order->get('Store Key')}/{$order->id}/credit_note/new',{ tab:'credit_note.new.items'})"><span
                                                class="label">{t}Next{/t}</span> <i class="fa fa-arrow-right fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="create_replacement_operations"
                     class="order_operation {if {$order->get('State Index')}<100    }hide{/if}">
                    <div class="square_button right  " title="{t}Create replacement{/t}">
                        <i class="fa fa-truck red " aria-hidden="true" onclick="toggle_order_operation_dialog('create_replacement')"></i>
                        <table id="create_replacement_dialog" border="0" class="order_operation_dialog hide" style="color:#777">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create replacement{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('create_replacement')"></i></td>
                                <td class="aright"><span id="create_replacement_save_buttons" class="valid save button" onclick="change_view('orders/{$order->get('Store Key')}/{$order->id}/replacement/new')"><span
                                                class="label">{t}Next{/t}</span> <i class="fa fa-arrow-right fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="create_return_operations"
                     class="order_operation {if {$order->get('State Index')}<100  or $order->get('Order Return State')=='InWarehouse' or $order->get('Order Return State')=='PackedDone'  or $order->get('Order Return State')=='Approved'   }hide{/if}">
                    <div class="square_button right  " title="{t}Create return{/t}">
                        <i class="fas fa-backspace red " aria-hidden="true" onclick="toggle_order_operation_dialog('create_return')"></i>
                        <table id="create_return_dialog" border="0" class="order_operation_dialog hide" style="color:#777">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create return{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('create_return')"></i></td>
                                <td class="aright"><span id="create_return_save_buttons" class="valid save button" onclick="change_view('orders/{$order->get('Store Key')}/{$order->id}/return/new')"><span
                                                class="label">{t}Next{/t}</span> <i class="fa fa-arrow-right fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div id="create_invoice_operations" class="order_operation  {if  $order->get('State Index')!=80}hide{/if}">
                    <div class="square_button right  " title="{t}Create invoice{/t}">
                        <i class="fal fa-file-alt  " aria-hidden="true" onclick="toggle_order_operation_dialog('create_invoice')"></i>
                        <table id="create_invoice_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Invoice order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('create_invoice')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "Approved","dialog_name":"create_invoice"}' id="create_invoice_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="recreate_invoice_operations" class="order_operation {if ( $order->get('State Index')>80 and !$order->get('Order Invoice Key')>0 ) }{else}hide{/if}">
                    <div class="square_button right  " title="{t}Create invoice again{/t}">
                        <i class="fas fa-file-alt  " aria-hidden="true" onclick="toggle_order_operation_dialog('recreate_invoice')"></i>
                        <table id="recreate_invoice_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Invoice order again{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('recreate_invoice')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "ReInvoice","dialog_name":"recreate_invoice"}' id="recreate_invoice_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="send_to_warehouse_operations" class="order_operation {if {$order->get('State Index')|intval}>30 or  {$order->get('State Index')|intval}<10   or $order->get('Order Number Items')==0   }hide{/if}">
                    <div class="square_button right  " title="{t}Send to warehouse{/t}">
                        <i class="fas fa-dolly-flatbed-alt" aria-hidden="true" onclick="toggle_order_operation_dialog('send_to_warehouse')"></i>
                        <table id="send_to_warehouse_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send to warehouse{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('send_to_warehouse')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "InWarehouse","dialog_name":"send_to_warehouse"}' id="send_to_warehouse_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="submit_operations" class="order_operation {if $order->get('State Index')!=10   or  $order->get('Order Number Items')==0 }hide{/if}">
                    <div class="square_button right  " title="{t}Submit{/t}">
                        <i class="fa fa-paper-plane   " aria-hidden="true" onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Submit order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('submit')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "InProcess","dialog_name":"submit"}' id="submit_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="order_operation edit_order_in_warehouse {if $order->get('Order State')!='InWarehouse'  }hide{/if}">
                    <div class="square_button right" title="{t}Edit order items{/t}">
                        <i class="far fa-pencil-alt" aria-hidden="true" onclick="edit_order_in_warehouse()"></i>
                    </div>
                </div>

            </div>


        </div>

        <table border="0" class="info_block acenter">

            <tr>

                <td>
                    {if isset($delivery_note)}
                        {if $delivery_note->get('Delivery Note Weight Source')=='Given'}
                            <i class="fal fa-weight"></i> <span title="{t}Delivery weight{/t}" class=" margin_right_10 DN_Weight">{$delivery_note->get('Weight')}</span>

                        {else}
                            <span title="{t}Estimated delivery weight{/t}" class="italic discreet margin_right_20 DN_Estimated_Weight">{$delivery_note->get('Weight')}</span>

                        {/if}
                        <span class="margin_right_20"> {$delivery_note->get('Number Parcels')}</span>



                    {else}
                    <span title="{t}Estimated weight{/t}" class="  margin_right_20 Order_Estimated_Weight">{$order->get('Estimated Weight')}</span>
                    {/if}
                    <span "><i class="fal fa-cube fa-fw " title="{t}Number of items{/t}"></i> <span class="Order_Number_items">{$order->get('Number Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " title="{t}Number discounted items{/t}"></i> <span class="Order_Number_Items_with_Deals">{$order->get('Number Items with Deals')}</span></span>
                    <span class="error {if $order->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px">
                        <i class="fa fa-cube fa-fw" title="{t}Number out of stock items{/t}"></i>
                        <span class="Order_Number_Items_with_Out_of_Stock">{$order->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $order->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px">
                        <i class="fa fa-thumbs-o-down fa-fw"  title="{t}Number items returned{/t}"></i>
                        <span class="Order_Number_Items_with_Returned">{$order->get('Number Items Returned')}</span></span>
                </td>
            </tr>
        </table>
        <table border="0" class="totals" style=";">


            <tr class="subtotal first">
                <td class="label small">{t}Items profit{/t}</td>
                <td class="aright "><span class="Profit_Amount">{$order->get('Profit Amount')}</span> (<span class="Order_Margin">{$order->get('Margin')}</span>)</td>
            </tr>


        </table>


        <table border="0" class="totals  {if $order->get('Order Items Out of Stock Amount')==0}hide{/if} " style=";">


            <tr class="subtotal first error">
                <td class="label small"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> {t}lost revenue{/t}</td>
                <td class="aright Items_Out_of_Stock_Amount">{$order->get('Items Out of Stock Amount')}</td>
            </tr>


        </table>

        <div  class=" node" style="border-top: 1px solid #ccc">

            <div class="payment_operation  hide ">
                <div class="close_add_discounts square_button left " style="padding:0;margin:0;position:relative;top:0px" title="{t}Close{/t}">
                    <i class="fa fa-window-close  " aria-hidden="true" onclick="hide_add_deal_to_order()"></i>

                </div>
            </div>
            <span class="discounts_label node_label  ">{t}Discounts{/t}</span>

            <input style="float:left;height: 20px;border: none;position: relative;top:1px;width: 232px;" id="add_discount" class="hide " value="" placeholder="{t}Offer code/voucher{/t}">



            <div class="payment_operation {if $order->get('State Index')<0 or  $order->get('State Index')>=90   }hide{/if} ">
                <div class="open_add_discounts square_button right " style="padding:0;margin:0;position:relative;top:0px" title="{t}Add offer{/t}">
                    <i class="fa fa-plus  " aria-hidden="true" onclick="show_add_deal_to_order()"></i>

                </div>
                <div class="save_add_discounts square_button right save hide" style="padding:0;margin:0;position:relative;top:0px" title="{t}Save{/t}">
                    <i class="fa fa-cloud  " aria-hidden="true" onclick="save_add_deal_to_order()"></i>

                </div>
            </div>




            <div id="add_discount_results_container" class="search_results_container " style="width:400px;">

                <table id="add_discount_results" border="0" style="background:white;font-size:90%">
                    <tr class="hide" style="" id="add_discount_search_result_template" field="" item_key="" item_historic_key=""
                        formatted_value="" onClick="select_add_discount_option(this)">
                        <td class="code" style="padding-left:5px;"></td>
                        <td class="label" style="padding-left:5px;"></td>

                    </tr>
                </table>

            </div>
        </div>


        <table border="0" class="totals" style="margin-top:0px;border-top:none">




            <tr class="subtotal Items_Discount_Amount_tr  {if $order->get('Order Items Discount Amount')==0}hide{/if}" style="margin-top:0px;border-top:none">
                <td class="label small" style="margin-top:0px;border-top:none">{t}Items{/t}</td>
                <td class="aright" style="margin-top:0px;border-top:none"><span class="discreet italic">(<span class=" Items_Discount_Percentage">{$order->get('Items Discount Percentage')}</span>)</span> <span class="padding_left_10 Items_Discount_Amount">{$order->get('Items Discount Amount')}</span></td>
            </tr>
            <tr class="subtotal Charges_Discount_Amount_tr  {if $order->get('Order Charges Discount Amount')==0}hide{/if} ">
                <td class="label small">{t}Charges{/t}</td>
                <td class="aright"><span class="discreet italic">(<span class=" Charges_Discount_Percentage">{$order->get('Charges Discount Percentage')}</span>)</span> <span class="padding_left_10 Charges_Discount_Amount">{$order->get('Charges Discount Amount')}</span></td>
            </tr>
            <tr class="subtotal Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if} ">
                <td class="label small">{t}Amount off{/t}</td>
                <td class="aright"><span class="discreet italic">(<span class=" Amount_Off_Discount_Percentage">{$order->get('Amount Off Percentage')}</span>)</span> <span class="padding_left_10 Deal_Amount_Off">{$order->get('Amount Off')}</span></td>
            </tr>
        </table>


    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px ">

        <div id="delivery_notes" class="delivery_notes {if $deliveries|@count == 0}hide{/if}" style="position:relative;;">


            {foreach from=$deliveries item=dn}
                <div class="node" id="delivery_node_{$dn->id}">
                    <span class="node_label" {if  $dn->get('Delivery Note Type')!='Order'}error{/if}>
                         <i class="fa fa-truck fa-flip-horizontal fa-fw  {if  $dn->get('Delivery Note Type')!='Order'}error{/if}" aria-hidden="true"></i> <span
                                class="link  {if  $dn->get('Delivery Note Type')!='Order'}error{/if}" onClick="change_view('delivery_notes/{$dn->get('Delivery Note Store Key')}/{$dn->id}')">{$dn->get('ID')}</span>
                        (<span class="Delivery_Note_State">{$dn->get('Abbreviated State')}</span>)


                        <a title="{t}Picking sheet{/t}" class="pdf_link {if $dn->get('State Index')!=10 }hide{/if}" target="_blank" href="/pdf/order_pick_aid.pdf.php?id={$dn->id}"> <i class="fal fa-clipboard-list-check " style="font-size: larger"></i></a>
                        <a title="{t}Picking sheet with address labels{/t}" class="pdf_link {if $dn->get('State Index')!=10 }hide{/if}" target="_blank" href="/pdf/order_pick_aid.pdf.php?with_labels&id={$dn->id}"> <i class="fal fw fa-pager " style="font-size: larger"></i></a>

                        <a class="pdf_link {if $dn->get('State Index')<90 }hide{/if}" target='_blank' href="/pdf/dn.pdf.php?id={$dn->id}"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                    </span>


                    <div class="delivery_note_operation data_entry_delivery_note {if $dn->get('State Index')>=80 or $dn->get('State Index')<0  or $store->settings('data_entry_picking_aid')!='Yes' }hide{/if}">
                        <div class="square_button right" title="{t}Input picking sheet data{/t}">
                            <i class="fa fa-keyboard" aria-hidden="true" onclick="data_entry_delivery_note({$dn->id})"></i>
                        </div>
                    </div>


                    <div id="submit_operations" class="order_operation {if !($dn->get('State Index')==80   and  $dn->get('Delivery Note Type')!='Order') }hide{/if}">
                        <div class="square_button right  " title="{t}Approve dispatch{/t}">
                            <i class="fa fa-thumbs-up   " aria-hidden="true" onclick="toggle_order_operation_dialog('approve_replacement')"></i>
                            <table id="approve_replacement_dialog" border="0" class="order_operation_dialog hide">
                                <tr class="top">
                                    <td class="label" colspan="2">{t}Approve dispatch{/t}</td>
                                </tr>
                                <tr class="changed buttons">
                                    <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('approve_replacement')"></i></td>
                                    <td class="aright"><span data-data='{  "field": "Replacement State","value": "Replacement Approved","dialog_name":"approve_replacement","replacement_key":"{$dn->id}"}'
                                                             id="approve_replacement_save_buttons" class="valid save button" onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                    class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            {/foreach}

        </div>



        <div id="returns" class="returns {if $returns|@count == 0}hide{/if}" style="position:relative;;">


            {foreach from=$returns item=return}
                <div class="node" id="return_{$return->id}">
                    <span class="node_label" error>
                         <i class="fa fa-backspace fa-fw  error" aria-hidden="true"></i> <span
                                class="link  {if $return->get('Supplier Delivery State')=='Cancelled'}strikethrough{/if}" onClick="change_view('warehouse/{$return->get('Supplier Delivery Warehouse Key')}/returns/{$return->id}')">{$return->get('Public ID')}</span>
                        (<span class="Return_State">{$return->get('Return State')}</span>)

                    </span>



                </div>
            {/foreach}

        </div>



        <div id="invoices" class="invoices {if $invoices|@count == 0}hide{/if}" style="">


            {foreach from=$invoices item=invoice}
                <div class="node" id="invoice_{$invoice->id}">
                    <span class="node_label">
                        <i class="fal fa-file-alt fa-fw {if $invoice->get('Invoice Type')=='Refund'}error {/if}" aria-hidden="true"></i>
                        <span class="link {if $invoice->get('Invoice Type')=='Refund'}error{/if}" onClick="change_view('orders/{$invoice->get('Invoice Store Key')}/{$order->id}/{$invoice->get('Invoice Type')|lower}/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span>
                         <img class="button pdf_link" onclick="download_pdf_from_list({$invoice->id},$('.pdf_invoice_dialog img'))" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                         <i onclick="show_pdf_invoice_dialog(this,{$invoice->id})" title="{t}PDF invoice display settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>
                    <div class="red" style="float: right;padding-right: 10px;padding-top: 5px">{if $invoice->get('Invoice Type')=='Refund'} {$invoice->get('Refund Total Amount')} {if $invoice->get('Invoice Paid')!='Yes'}
                            <i class="fa fa-exclamation-triangle warning fa-fw" aria-hidden="true" title="{t}Return payment pending{/t}"></i>
                        {/if}  {/if}</div>

                </div>
            {/foreach}
        </div>

        <div id="deleted_invoices" class="invoices {if $deleted_invoices|@count == 0}hide{/if}" style="">


            {foreach from=$deleted_invoices item=invoice}
                <div class="node" id="invoice_{$invoice->id}">
                    <span class="node_label">
                        <i class="fal fa-file-alt fa-fw {if $invoice->get('Invoice Type')=='Refund'}error {/if}" aria-hidden="true"></i>
                        <span class="link  strikethrough {if $invoice->get('Invoice Type')=='Refund'}error{/if}" onClick="change_view('orders/{$invoice->get('Invoice Store Key')}/{$order->id}/{$invoice->get('Invoice Type')|lower}/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span>

                    </span>


                </div>
            {/foreach}
        </div>

        <div class="pdf_invoice_dialog options_dialog  hide" style="min-width: 150px" data-data='{ "type":"invoice_from_list"}'>
            <i onclick="$('.pdf_invoice_dialog').addClass('hide')" style="float: right;margin-left: 10px" class="fa fa-window-close button"></i>
            <h2 class="unselectable">{t}PDF Invoice{/t}</h2>

            <table>
                <tbody>
                <tr data-field='rrp' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far {if $pdf_with_rrp}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_rrp}class="discreet"{/if}>{t}Recommended retail prices{/t}</span>
                    </td>

                </tr>
                <tr data-field='parts' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far  {if $pdf_with_parts}fa-check-square{else}fa-square{/if}  margin_right_10"></i> <span class="discreet">{t}Parts{/t}</span>
                    </td>
                </tr>
                <tr data-field='commodity' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far {if $pdf_with_commodity}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_commodity}class="discreet"{/if}>{t}Commodity codes{/t}</span>
                    </td>

                </tr>
                <tr data-field='barcode' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far {if $pdf_with_barcode}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Product barcode{/t}</span>
                    </td>
                </tr>
                <tr data-field='weight' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far {if $pdf_with_weight}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Weight{/t}</span>
                    </td>
                </tr>
                <tr data-field='origin' class="button pdf_option" onclick="check_field_value(this)">
                    <td>
                        <i class="far {if $pdf_with_origin}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Country of origin{/t}</span>
                    </td>
                </tr>
                <tr data-field='locale' class="button pdf_option {if !$pdf_show_locale_option}hide{/if}" onclick="check_field_value(this)">
                    <td>
                        <i class="far fa-square margin_right_10" data-value="en_GB"></i> <span class="discreet">{t}English{/t}</span>
                    </td>
                </tr>
                </tbody>
                <tr>
                    <td>
                        <img class="button" onclick="download_pdf(this)" style="width: 50px;height:16px;margin-top:10px" src="/art/pdf.gif">
                    </td>

                </tr>
            </table>


        </div>

        <div style="margin-bottom: 0px" class="payments {if $order->get('Order State')!='InBasket' or $customer->get('Customer Account Balance')==0    }hide{/if}  ">
            <div  class="payment node  ">


                <span class="node_label   ">{$customer->get('Account Balance')} {t}available credit{/t}</span>


            </div>
        </div>


        <div style="margin-bottom: 15px" class="payments {if $order->get('Order Number Items')==0  or $order->get('State Index')<0   or ($order->get('Order State')=='InBasket' and empty($payments)  )  }hide{/if}  ">


            {assign expected_payment $order->get('Expected Payment')}


            <div id="expected_payment" class="payment node  {if $expected_payment==''}hide{/if} ">


                <span class="node_label   ">{$expected_payment}</span>


            </div>


            <div id="create_payment" class="payment node">


                <span class="node_label very_discreet italic">{t}Payments{/t}</span>


                <div class="payment_operation  ">
                    <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}Add payment{/t}">
                        <i class="add_payment_to_order_button fa {if $order->get('Order To Pay Amount')<=0   }fa-lock super_discreet{else}fa-plus{/if}  "
                           data-labels='{ "yes_text_no_stock":"{t}Unlock{/t}", "no_text_no_stock":"{t}Close{/t}", "title":"{t}Order already paid{/t}","text":"{t}Add payment at your own risk{/t}"}' aria-hidden="true" onclick="open_add_payment_to_order(this)"></i>

                    </div>
                </div>


            </div>


            <div id="payment_nodes">
                {foreach from=$payments item=payment}
                    <div class="payment node">
                        <span class="node_label">
                            <span class="link" onClick="change_view('order/{$order->id}/payment/{$payment->id}')">{if $payment->payment_account->get('Payment Account Block')=='Accounts'  or  $payment->get('Payment Method')=='Account'    }{t}Credit{/t}{else}{$payment->get('Payment Account Code')}{/if}</span> </span>
                        <span class="node_amount"> {$payment->get('Transaction Amount')}</span>
                    </div>
                {/foreach}
            </div>


        </div>

        <div style="clear:both"></div>






        <table border="0" class="totals {if $order->get('Order State')=='InBasket' and empty($payments) }hide{/if}" style="width: 100%">


            <tbody id="total_payments" class="{if $order->get('State Index')<0}hide{/if}">

            <tr class="total Order_Payments_Amount  {if $order->get('Order To Pay Amount')==0    }hide{/if}  ">
                <td class="label">{t}Paid{/t}</td>
                <td class="aright Payments_Amount">{$order->get('Payments Amount')}</td>
            </tr>
            <tr class="total  Order_To_Pay_Amount {if $order->get('Order To Pay Amount')==0    }hide{/if} button"  absolute_amount="{$order->get('Order To Pay Amount Absolute')}" amount="{$order->get('Order To Pay Amount')}" onclick="try_to_pay(this)">
                <td class="label">{if $order->get('Order To Pay Amount')>0}{t}To pay{/t}{else}To credit / refund{/if}</td>
                <td class="aright To_Pay_Amount_Absolute   ">{$order->get('To Pay Amount Absolute')}</td>
            </tr>
            <tr class="total success  Order_Paid {if $order->get('Order To Pay Amount')!=0   or $order->get('Order Total Amount')==0  }hide{/if}">

                <td colspan="2" class="align_center "><i class="fa fa-check-circle" aria-hidden="true"></i> {t}Paid{/t}</td>
            </tr>
            </tbody>


            <tbody id="total_payments_cancelled_order" class="error {if $order->get('State Index')>0}hide{/if}">
            <tr class="total Order_Payments_Amount  {if $order->get('Order Payments Amount')==0    }hide{/if}  " style="background-color: rgba(255,0,0,.05); ">
                <td style="border-color:rgba(255,0,0,1)" class="label">{t}Paid{/t}</td>
                <td style="border-color:rgba(255,0,0,1)" class="aright Payments_Amount">{$order->get('Payments Amount')}</td>
            </tr>

            </tbody>
        </table>


    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="totals" style="position:relative;top:-5px">

            <tr>
                <td class="label">{t}Items{/t}</td>
                <td class="aright "><span class="{if $order->get('Order Items Discount Amount')==0}hide{/if} Items_Gross_Amount strikethrough">{$order->get('Items Gross Amount')}</span> <span class="Items_Net_Amount">{$order->get('Items Net Amount')}</span></td>
            </tr>




            <tr>
                <td class="label" id="Charges_Net_Amount_label">{t}Charges{/t}</td>
                <td class="aright "><span id="Charges_Net_Amount_form" class="hide"><i id="set_charges_as_auto" class="fa fa-magic button" onClick="set_charges_as_auto()" aria-hidden="true"></i>  <input
                                value="{$order->get('Order Hanging Charges Net Amount')}" ovalue="{$order->get('Order Hanging Charges Net Amount')}" style="width: 100px" id="Charges_Net_Amount_input"> <i id="Charges_Net_Amount_save"
                                                                                                                                                                                            class="fa fa-cloud save"
                                                                                                                                                                                            onClick="save_charges_value()"
                                                                                                                                                                                            aria-hidden="true"></i> </span><span
                            id="Charges_Net_Amount" class="Charges_Net_Amount {if $order->get('State Index')<90 and $order->get('State Index')>0}button{/if}">{$order->get('Charges Net Amount')}<span></td>
            </tr>
            <tr>
                <td class="label" id="Shipping_Net_Amount_label">{t}Shipping{/t}</td>
                <td class="aright "><span id="Shipping_Net_Amount_form" class="hide"><i id="set_shipping_as_auto" class="fa fa-magic button" onClick="set_shipping_as_auto()" aria-hidden="true"></i>  <input
                                value="{$order->get('Order Shipping Net Amount')}" ovalue="{$order->get('Order Shipping Net Amount')}" style="width: 100px" id="Shipping_Net_Amount_input"><i id="Shipping_Net_Amount_save"
                                                                                                                                                                                               class="fa fa-cloud save"
                                                                                                                                                                                               onClick="save_shipping_value()"
                                                                                                                                                                                               aria-hidden="true"></i> </span><span
                            id="Shipping_Net_Amount" class="Shipping_Net_Amount {if $order->get('State Index')<90 and $order->get('State Index')>0}button{/if}">{$order->get('Shipping Net Amount')}<span></td>
            </tr>
            <tr class="Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if}">
                <td class="label">{t}Amount off{/t}</td>
                <td class="aright Deal_Amount_Off">{$order->get('Deal Amount Off')}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">{t}Net{/t}</td>
                <td class="aright Total_Net_Amount">{$order->get('Total Net Amount')}</td>
            </tr>

            <tr class="subtotal">
                <td class="label button" onclick="show_order_tax_dialog()" title="{t}Show order tax dialog{/t}">{t}Tax{/t}</td>
                <td class="aright Total_Tax_Amount">{$order->get('Total Tax Amount')}</td>
            </tr>

            <tr class="total">
                <td class="label">{t}Total{/t}</td>
                <td class="aright Total_Amount  {if $order->get('State Index')>0}button{/if} " amount="{$order->get('Order To Pay Amount')}" onclick="try_to_pay(this)">{$order->get('Total Amount')}</td>
            </tr>


            <tr class="total  {if $order->get('Order Total Refunds')==0}hide{/if}">
                <td class="label" title="{t}Total refunds{/t}">{t}Refunds{/t}</td>
                <td class="aright Total_Refunds  {if $order->get('State Index')>0}button{/if}  ">{$order->get('Total Refunds')}</td>
            </tr>

            <tr class="total {if $order->get('Order Total Refunds')==0}hide{/if}">
                <td class="label" title="{t}Total final balance afer refunds{/t}">{t}Final balance{/t}</td>
                <td class="aright Total_Balance  {if $order->get('State Index')>0}button{/if}  ">{$order->get('Total Balance')}</td>
            </tr>


            <tr class=" hide  {if $account->get('Account Currency')==$order->get('Order Currency Code')}hide{/if}">
                <td colspan="2" class="Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
            </tr>


        </table>
        <div style="clear:both"></div>
    </div>
    <div style="clear:both"></div>
</div>

<div id="add_payment" class="table_new_fields hide">

    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-bottom: 1px solid #ccc;position: relative">

        <i style="position:absolute;top:10px;" class="fa fa-window-close fa-flip-horizontal button" aria-hidden="true" onclick="close_add_payment_to_order()"></i>

        <table border="0" style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td style="width: 500px">
                <td>
                <td></td>

                <td>
                    <div id="new_payment_payment_account_buttons">
                        {foreach from=$store->get_payment_accounts('objects','Active') item=payment_account}

                            {if $payment_account->get('Payment Account Block')=='Accounts'}
                                <div class="button  {if $customer->get('Customer Account Balance')<=0}hide{/if} {$payment_account->get('Payment Account Block')}" onclick="select_payment_account(this)"
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"{$customer->get('Customer Account Balance')}" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }'
                                     class="new_payment_payment_account_button unselectable
                        button {if $payment_account->get('Payment Account Block')=='Accounts' and $customer->get('Customer Account Balance')<=0  }hide{/if}" style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{t}Customer credit{/t} <span class="discreet padding_left_10">{$customer->get('Account Balance')}</span></div>
                            {else}
                                <div class="button" onclick="select_payment_account(this)"
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }'
                                     class="new_payment_payment_account_button unselectable
                        button {if $payment_account->get('Payment Account Block')=='Accounts' and $customer->get('Customer Account Balance')<=0  }hide{/if}" style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{$payment_account->get('Name')}</div>
                            {/if}



                        {/foreach}
                    </div>
                    <input type="hidden" id="new_payment_payment_account_key" value="">
                    <input type="hidden" id="new_payment_payment_method" value="">


                </td>

                <td class="payment_fields " style="padding-left:30px;width: 300px">
                    <table>
                        <tr>
                            <td> {t}Amount{/t}</td>
                            <td style="padding-left:20px"><input disabled=true class="new_payment_field" id="new_payment_amount" placeholder="{t}Amount{/t}"></td>
                        </tr>
                        <tr>
                            <td>  {t}Reference{/t}</td>
                            <td style="padding-left:20px"><input disabled=true class="new_payment_field" id="new_payment_reference" placeholder="{t}Reference{/t}"></td>
                        </tr>
                    </table>
                </td>

                <td id="save_new_payment" class="buttons save" onclick="save_new_payment()"><span>{t}Save{/t}</span> <i class=" fa fa-cloud " aria-hidden="true"></i></td>
            </tr>

        </table>
    </div>
</div>

<div id="add_credit" class="table_new_fields hide">

    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-bottom: 1px solid #ccc;position: relative">

        <i style="position:absolute;top:10px;" class="fa fa-window-close fa-flip-horizontal button" aria-hidden="true" onclick="close_add_payment_to_order()"></i>

        <table border="0" style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td style="width: 400px">
                <td>



                        {foreach from=$store->get_payment_accounts('objects','Active') item=payment_account}

                            {if $payment_account->get('Payment Account Block')=='Accounts'}


                                <input type="hidden" id="new_credit_payment_account_key" value="{$payment_account->id}"">
                                <input type="hidden" id="new_credit_payment_method" value="{$payment_account->get('Default Payment Method')}">

                            {/if}



                        {/foreach}






                <td class="payment_fields " style="padding-left:30px;width: 400px">
                    <table>
                        <tr>
                            <td> {t}Amount to be credited{/t}</td>
                            <td style="padding-left:20px"><input class="new_payment_field" id="new_credit_amount" placeholder="{t}Amount{/t}"></td>
                        </tr>

                    </table>
                </td>

                <td id="save_new_credit" class="buttons save" onclick="save_new_credit()"><span>{t}Save{/t}</span> <i class=" fa fa-cloud " aria-hidden="true"></i></td>
            </tr>

        </table>
    </div>
</div>


<script>

    var out_of_stock_dialog_open=false;



    $(document).on('click', '#Shipping_Net_Amount', function (evt) {
        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }
        $('#Shipping_Net_Amount').addClass('hide')
        $('#Shipping_Net_Amount_form').removeClass('hide')

    })

    $(document).on('click', '#Shipping_Net_Amount_label', function (evt) {
        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }
        $('#Shipping_Net_Amount').removeClass('hide')
        $('#Shipping_Net_Amount_form').addClass('hide')

    })

    $(document).on('input propertychange', '#Shipping_Net_Amount_input', function (evt) {


        $('#Shipping_Net_Amount_save').addClass('changed')

        if (!validate_number($('#Shipping_Net_Amount_input').val(), 0, 999999999) || $('#Shipping_Net_Amount_input').val() == '') {
            $('#Shipping_Net_Amount_save').addClass('valid').removeClass('error')

        } else {
            $('#Shipping_Net_Amount_save').removeClass('valid').addClass('error ')

        }


    })

    function save_shipping_value() {

        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }

        if ($('#Shipping_Net_Amount_save').hasClass('wait')) {
            return;
        }
        $('#Shipping_Net_Amount_save').addClass('wait')

        $('#Shipping_Net_Amount_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


        if ($('#Shipping_Net_Amount_input').val() == '') {
            $('#Shipping_Net_Amount_input').val(0)
        }

        var amount = parseFloat($('#Shipping_Net_Amount_input').val())


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_shipping_value')

        ajaxData.append("order_key", '{$order->id}')
        ajaxData.append("amount", amount)


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                $('#Shipping_Net_Amount_save').removeClass('wait')
                $('#Shipping_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


                console.log(data)

                if (data.state == '200') {


                    $('#Shipping_Net_Amount').removeClass('hide')
                    $('#Shipping_Net_Amount_form').addClass('hide')

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue', data.metadata.shipping)
                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                    if (data.metadata.to_pay == 0) {
                        $('.Order_Payments_Amount').addClass('hide')
                        $('.Order_To_Pay_Amount').addClass('hide')

                    } else {
                        $('.Order_Payments_Amount').removeClass('hide')
                        $('.Order_To_Pay_Amount').removeClass('hide')

                    }

                    if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                        $('.Order_Paid').addClass('hide')
                    } else {
                        $('.Order_Paid').removeClass('hide')
                    }

                    if (data.metadata.to_pay <= 0) {
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                    if (data.metadata.to_pay == 0) {
                        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                    } else {
                        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                    }


                    $('#payment_nodes').html(data.metadata.payments_xhtml)


                    for (var key in data.metadata.class_html) {
                        $('.' + key).html(data.metadata.class_html[key])
                    }


                    for (var key in data.metadata.hide) {
                        $('#' + data.metadata.hide[key]).addClass('hide')
                    }
                    for (var key in data.metadata.show) {
                        $('#' + data.metadata.show[key]).removeClass('hide')
                    }


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


            }, error: function () {
                $('#Shipping_Net_Amount_save').removeClass('wait')
                $('#Shipping_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

            }
        });


    }


    function set_shipping_as_auto() {

        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }

        if ($('#set_shipping_as_auto').hasClass('wait')) {
            return;
        }
        $('#set_shipping_as_auto').addClass('wait')

        $('#set_shipping_as_auto').removeClass('fa-magic').addClass('fa-spinner fa-spin');


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_shipping_as_auto')

        ajaxData.append("order_key", '{$order->id}')


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                $('#set_shipping_as_auto').removeClass('wait')
                $('#set_shipping_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


                console.log(data)

                if (data.state == '200') {


                    $('#Shipping_Net_Amount').removeClass('hide')
                    $('#Shipping_Net_Amount_form').addClass('hide')

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue', data.metadata.shipping)
                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                    if (data.metadata.to_pay == 0) {
                        $('.Order_Payments_Amount').addClass('hide')
                        $('.Order_To_Pay_Amount').addClass('hide')

                    } else {
                        $('.Order_Payments_Amount').removeClass('hide')
                        $('.Order_To_Pay_Amount').removeClass('hide')

                    }

                    if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                        $('.Order_Paid').addClass('hide')
                    } else {
                        $('.Order_Paid').removeClass('hide')
                    }

                    if (data.metadata.to_pay <= 0) {
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                    if (data.metadata.to_pay == 0) {
                        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                    } else {
                        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                    }


                    $('#payment_nodes').html(data.metadata.payments_xhtml)


                    for (var key in data.metadata.class_html) {
                        $('.' + key).html(data.metadata.class_html[key])
                    }


                    for (var key in data.metadata.hide) {
                        $('#' + data.metadata.hide[key]).addClass('hide')
                    }
                    for (var key in data.metadata.show) {
                        $('#' + data.metadata.show[key]).removeClass('hide')
                    }


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


            }, error: function () {
                $('#set_shipping_as_auto').removeClass('wait')
                $('#set_shipping_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


            }
        });

    }


    $(document).on('click', '#Charges_Net_Amount', function (evt) {
        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }
        $('#Charges_Net_Amount').addClass('hide')
        $('#Charges_Net_Amount_form').removeClass('hide')

    })

    $(document).on('click', '#Charges_Net_Amount_label', function (evt) {
        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }
        $('#Charges_Net_Amount').removeClass('hide')
        $('#Charges_Net_Amount_form').addClass('hide')

    })

    $(document).on('input propertychange', '#Charges_Net_Amount_input', function (evt) {


        $('#Charges_Net_Amount_save').addClass('changed')

        if (!validate_number($('#Charges_Net_Amount_input').val(), 0, 999999999) || $('#Charges_Net_Amount_input').val() == '') {
            $('#Charges_Net_Amount_save').addClass('valid').removeClass('error')

        } else {
            $('#Charges_Net_Amount_save').removeClass('valid').addClass('error ')

        }


    })

    function save_charges_value() {


        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }


        if ($('#Charges_Net_Amount_save').hasClass('wait')) {
            return;
        }
        $('#Charges_Net_Amount_save').addClass('wait')

        $('#Charges_Net_Amount_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


        if ($('#Charges_Net_Amount_input').val() == '') {
            $('#Charges_Net_Amount_input').val(0)
        }

        var amount = parseFloat($('#Charges_Net_Amount_input').val())


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_hanging_charges_value')

        ajaxData.append("order_key", '{$order->id}')
        ajaxData.append("amount", amount)


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                $('#Charges_Net_Amount_save').removeClass('wait')
                $('#Charges_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


                console.log(data)

                if (data.state == '200') {


                    $('#Charges_Net_Amount').removeClass('hide')
                    $('#Charges_Net_Amount_form').addClass('hide')

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)
                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                    if (data.metadata.to_pay == 0) {
                        $('.Order_Payments_Amount').addClass('hide')
                        $('.Order_To_Pay_Amount').addClass('hide')

                    } else {
                        $('.Order_Payments_Amount').removeClass('hide')
                        $('.Order_To_Pay_Amount').removeClass('hide')

                    }

                    if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                        $('.Order_Paid').addClass('hide')
                    } else {
                        $('.Order_Paid').removeClass('hide')
                    }

                    if (data.metadata.to_pay <= 0) {
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                    if (data.metadata.to_pay == 0) {
                        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                    } else {
                        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                    }


                    $('#payment_nodes').html(data.metadata.payments_xhtml)


                    for (var key in data.metadata.class_html) {
                        $('.' + key).html(data.metadata.class_html[key])
                    }


                    for (var key in data.metadata.hide) {
                        $('#' + data.metadata.hide[key]).addClass('hide')
                    }
                    for (var key in data.metadata.show) {
                        $('#' + data.metadata.show[key]).removeClass('hide')
                    }


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


            }, error: function () {
                $('#Charges_Net_Amount_save').removeClass('wait')
                $('#Charges_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

            }
        });


    }

    function set_charges_as_auto() {

        if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
            return;
        }

        if ($('#set_charges_as_auto').hasClass('wait')) {
            return;
        }
        $('#set_charges_as_auto').addClass('wait')

        $('#set_charges_as_auto').removeClass('fa-magic').addClass('fa-spinner fa-spin');


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_charges_as_auto')

        ajaxData.append("order_key", '{$order->id}')


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                $('#set_charges_as_auto').removeClass('wait')
                $('#set_charges_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


                console.log(data)

                if (data.state == '200') {


                    $('#Charges_Net_Amount').removeClass('hide')
                    $('#Charges_Net_Amount_form').addClass('hide')

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)
                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                    if (data.metadata.to_pay == 0) {
                        $('.Order_Payments_Amount').addClass('hide')
                        $('.Order_To_Pay_Amount').addClass('hide')

                    } else {
                        $('.Order_Payments_Amount').removeClass('hide')
                        $('.Order_To_Pay_Amount').removeClass('hide')

                    }

                    if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                        $('.Order_Paid').addClass('hide')
                    } else {
                        $('.Order_Paid').removeClass('hide')
                    }

                    if (data.metadata.to_pay <= 0) {
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                    if (data.metadata.to_pay == 0) {
                        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                    } else {
                        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                    }


                    $('#payment_nodes').html(data.metadata.payments_xhtml)


                    for (var key in data.metadata.class_html) {
                        $('.' + key).html(data.metadata.class_html[key])
                    }


                    for (var key in data.metadata.hide) {
                        $('#' + data.metadata.hide[key]).addClass('hide')
                    }
                    for (var key in data.metadata.show) {
                        $('#' + data.metadata.show[key]).removeClass('hide')
                    }


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


            }, error: function () {
                $('#set_charges_as_auto').removeClass('wait')
                $('#set_charges_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


            }
        });

    }

    function edit_order_in_warehouse(){

        console.log($('#forward_operations .edit_order_in_warehouse i'))


        if($('#forward_operations .edit_order_in_warehouse i').hasClass('far')){

            Swal.fire({
                title: '{t}Are you sure you want to modify an order already in warehouse?{/t}',
                html: '{t}Please notify warehouse staff of changes{/t} <div style="margin-top:10px" class="small error">This is a <em>beta</em> (untested) feature</div>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{t}Yes, i want to modify it!{/t}'
            }).then((result) => {
                if (result.value) {

                grid.columns.findWhere({ name: 'quantity'} ).set("renderable", false)
                grid.columns.findWhere({ name: 'quantity_edit'} ).set("renderable", true)

                $('#forward_operations .edit_order_in_warehouse i').removeClass('far').addClass('fas')


            }
        })
        }else{
            grid.columns.findWhere({ name: 'quantity'} ).set("renderable", true)
            grid.columns.findWhere({ name: 'quantity_edit'} ).set("renderable", false)
            $('#forward_operations .edit_order_in_warehouse i').addClass('far').removeClass('fas')

        }




    }


    function show_add_deal_to_order(){
        $('.open_add_discounts').addClass('hide')
        $('.discounts_label').addClass('hide')

        $('.save_add_discounts').removeClass('hide')
        $('.close_add_discounts').removeClass('hide')
        $('#add_discount').removeClass('hide').focus()
        $('#add_item_results_container').removeClass('hide')


    }

    function hide_add_deal_to_order(){
        $('.open_add_discounts').removeClass('hide')
        $('.discounts_label').removeClass('hide')

        $('.save_add_discounts').addClass('hide')
        $('.close_add_discounts').addClass('hide')
        $('#add_discount').addClass('hide').focus()
        $('#add_item_results_container').addClass('hide')


    }

    function save_add_deal_to_order(){
     console.log('to do')

    }

</script>