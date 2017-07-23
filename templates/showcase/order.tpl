{assign deliveries $order->get_deliveries('objects')}
{assign invoices $order->get_invoices('objects')}
{assign payments $order->get_payments('objects')}



<div class="timeline_horizontal with_time  {if $order->get('Order Current Dispatch State')=='Cancelled'}hide{/if}  ">

    <ul class="timeline hide" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Order_Submitted_Date">&nbsp;{$order->get('Submitted by Customer Date')}</span> <span
                        class="start_date">{$order->get('Created Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>
    </ul>

    {foreach from=$deliveries item=dn name=delivery_notes}
    <ul class="timeline hide" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Order_Submitted_Date">&nbsp;{$order->get('Submitted by Customer Date')}</span> <span
                        class="start_date">{$order->get('Created Date')} </span>
            </div>
            <div class="dot">
            </div>
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
            <div class="truck">
            </div>
        </li>

        <li id="warehouse_node" class="li  {if $dn->get('State Index')>=70}complete{/if}">
            <div class="label">
                <span class="state ">&nbsp;{$dn->get('State')}&nbsp;<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Order_Inputted_Date">&nbsp;<span
                    class="">{$dn->get('Picking and Packing Percentage or Date')}</span>
                    &nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li class="li {if $order->get('State Index')==100}complete{/if}">
            <div class="label">
                <span class="state">{t}Dispatch approved{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span
                            class="{if $smarty.foreach.delivery_notes.index != 0}hide{/if}">{$dn->get('Dispatched Date')}</span>
                    &nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li class="li {if $order->get('State Index')==100}complete{/if}">
            <div class="label">
                <span class="state">{t}Dispatched{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span
                    class="{if $smarty.foreach.delivery_notes.index != 0}hide{/if}">{$dn->get('Dispatched Date')}</span>
                    &nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>
    </ul>
    {/foreach}
</div>


<div class="timeline_horizontal  {if $order->get('Order Current Dispatch State')!='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span
                        class="start_date">{$order->get('Created Date')} </span>
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

<div id="order" class="order" style="display: flex;" data-object="{$object_data}" order_key="{$order->id}">
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  "   style="margin-bottom:10px"  >

              <span class="button" onclick="change_view('customers/{$order->get('Order Store Key')}/{$order->get('Order Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                        class="button Order_Customer_Name">{$order->get('Order Customer Name')}</span> <span

                        class="link Order_Customer_Key">{$order->get('Order Customer Key')|string_format:"%05d"}</span>
              </span>
            </div>

            <div class="data_field small {if $order->get('Telephone')==''}hide{/if}  " style="margin-top:5px"   >
                <div class=""><i class="fa fa-phone fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Telephone">{$order->get('Telephone')}</span></div>



            </div>

            <div class="data_field small {if $order->get('Email')==''}hide{/if}" style="margin-top:5px" >


                <div class=""><i class="fa fa-envelope fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Email">{$order->get('Email')}</span></div>

            </div>

            <div class="data_field  " style="padding:10px 0px 20px 0px;">
                <div style="float:left;padding-bottom:20px;padding-right:20px" class="Delivery_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-truck button" aria-hidden="true""></i>{t}Deliver to{/t}</div>
                    <div class="small Order_Delivery_Address " style="max-width: 140px;" >{$order->get('Order Delivery Address Formatted')}</div>
                </div>
                <div style="float:right;padding-bottom:20px;" class="Billing_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-dollar button" aria-hidden="true""></i>{t}Billed to{/t}</div>
                    <div class="small Order_Invoice_Address" style="max-width: 140px;">{$order->get('Order Invoice Address Formatted')}</div>
                </div>
                <div style="clear:both">
                </div>
            </div>


        </div>
        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $order->get('Order Current Dispatch State')!='InProcess'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}delete{/t}">
                        <i class="fa fa-trash very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "PurchaseOrder", "key":"{$order->id}"}'
                                                         id="delete_save_buttons" class="error save button"
                                                         onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $order->get('Order Current Dispatch State')=='Cancelled'}hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2">{t}Cancel order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td>
                                    <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i>
                                </td>
                                <td class="aright">
                                    <span
                                            data-data='{ "field": "Order Current Dispatch State","value": "Cancelled","dialog_name":"cancel"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)">
                                        <span class="label">{t}Cancel{/t}</span>
                                        <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_submit_operations"
                     class="order_operation {if $order->get('Order Current Dispatch State')!='Submitted'}hide{/if}">
                    <div class="square_button left" title="{t}Undo submit{/t}">
												<span class="fa-stack"
                                                      onclick="toggle_order_operation_dialog('undo_submit')">
						<i class="fa fa-paper-plane-o discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>


                        <table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Undo submition{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Order State","value": "InProcess","dialog_name":"undo_submit"}'
                                            id="undo_submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_send_operations" class="order_operation {if $order->get('Order Current Dispatch State')!='Send'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}Unmark as send{/t}">
						<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_send')">
						<i class="fa fa-plane discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
                        <table id="undo_send_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Unmark as send{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_send')"></i></td>
                                <td class="aright"><span id="undo_send_save_buttons" class="valid save button"
                                                         onclick="save_order_operation('undo_send','Submitted')"><span
                                                class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                    aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Order_State"> {$order->get('State')} </span>
            <div id="forward_operations">
                <div id="submit_operations"
                     class="order_operation {if $order->get('Order Current Dispatch State')!='InProcess'}hide{/if}">
                    <div id="submit_operation"
                         class="square_button right {if $order->get('Order Number Items')==0}hide{/if} "
                         title="{t}Submit{/t}">
                        <i class="fa fa-paper-plane-o   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Submit order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Order State","value": "Submitted","dialog_name":"submit"}'
                                            id="submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <table border="0" class="info_block acenter">

            <tr>

                <td>
                    <span style=""><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span
                                class="Order_Number_items">{$order->get('Number Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Deals">{$order->get('Number Items with Deals')}</span></span>
                    <span class="error {if $order->get('Order Number Items Out of Stock')==0}hide{/if}"
                          style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Out_of_Stock">{$order->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $order->get('Order Number Items Returned')==0}hide{/if}"
                          style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   "
                                                       aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$order->get('Number Items Returned')}</span></span>
                </td>
            </tr>


        </table>

    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">

        <div class="delivery_notes" style="margin-bottom:10px;position:relative;top:-5px;">
            <div id="create_delivery"
                 class="delivery_node {if {$order->get('State Index')|intval}>30 or ( {$order->get('State Index')|intval}<10)   }hide{/if}"
                 style="height:30px;clear:both;border-bottom:1px solid #ccc">

                <div id="back_operations"></div>
                <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i class="fa fa-truck  button" aria-hidden="true"></i> {t}Delivery note{/t} </span>
                <div id="forward_operations" ">

                    <div id="received_operations"
                         class="order_operation ">
                        <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                             title="{t}Send to warehouse{/t}">
                            <i class="fa fa-plus" aria-hidden="true" onclick="create_delivery_note()"></i>

                        </div>
                    </div>


                </div>

            </div>
            {foreach from=$deliveries item=dn}
                <div class="delivery_node {if !$dn->id}hide{/if}" style="height:30px;clear:both;border-bottom:1px solid #ccc"   >
                    <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button"
                                                                                      onClick="change_view('delivery_notes/{$dn->get('Delivery Note Store Key')}/{$dn->id}')"> <i
                                    class="fa fa-truck fa-fw "
                                    aria-hidden="true"></i> {$dn->get('ID')}</span> ({$dn->get('Abbreviated State')}) </span>

                    <div id="forward_operations"  class="order_operation {if $dn->get('Delivery Note State')!='Packed'  }hide{/if}">
                        <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                             title="{t}Approve dispatch{/t}">
                            <i class="fa fa-thumbs-up" aria-hidden="true" dn_key="{$dn->id}" onclick="approve_dispatch(this)"></i>
                        </div>

                    </div>

                </div>


            {/foreach}
        </div>
        <div class="invoices" style="margin-bottom:10px">
            <div id="create_invoice"
                 class="delivery_node {if {$order->get('State Index')|intval}<30 or ($order->get('Order Ordered Number Items')-$order->get('Order Number Supplier Delivery Items'))==0  }hide{/if}"
                 style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">

                <div id="back_operations"></div>
                <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i
                            class="fa fa-truck  button" aria-hidden="true"></i> {t}Delivery note{/t} </span>
                <div id="forward_operations">

                    <div id="received_operations"
                         class="order_operation {if !($order->get('Order Current Dispatch State')=='Submitted' or  $order->get('Order Current Dispatch State')=='Send') }hide{/if}">
                        <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                             title="{t}Input delivery note{/t}">
                            <i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i>

                        </div>
                    </div>


                </div>

            </div>
            {foreach from=$invoices item=invoice}
                <div class="delivery_node"
                     style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                    <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button"
                                                                                      onClick="change_view('order/{$order->id}/invoice/{$invoice->id}')"> <i
                                    class="fa fa-file-text-o fa-fw  "
                                    aria-hidden="true"></i> {$invoice->get('Public ID')}</span> ({$invoice->get('State')}
                        ) </span>
                </div>
            {/foreach}
        </div>

        <div class="payments" style="margin-bottom:20px">
            <div id="create_payment" class="delivery_node"
                 style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">

                <div id="back_operations"></div>
                <span style="float:left;padding-left:10px;padding-top:5px"
                      class="very_discreet italic">{t}Payments{/t}</span>
                <div id="forward_operations">

                    <div id="received_operations" class="order_operation ">
                        <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                             title="{t}Input delivery note{/t}">
                            <i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i>

                        </div>
                    </div>


                </div>

            </div>
            {foreach from=$payments item=payment}
                {if $payment->get('Payment Transaction Status')=='Completed'}
                    <div class="payment" style="height:30px;clear:both;border-bottom:1px solid #ccc">
                        <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button"
                                                                                          onClick="change_view('{$order->get('Order Parent')|lower}/{$order->get('Order Parent Key')}/delivery/{$invoice->id}')">{$payment->get('Payment Account Code')}</span> </span>
                        <span style="float:right;padding-right:10px;padding-top:5px"> {$payment->get('Amount')}</span>
                    </div>
                {/if}
            {/foreach}
        </div>

        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="totals" style="position:relative;top:-5px">

            <tr>
                <td class="label">{t}Items{/t}</td>
                <td class="aright Items_Net_Amount">{$order->get('Items Net Amount')}</td>
            </tr>
            <tr>
                <td class="label">{t}Charges{/t}</td>
                <td class="aright Charges_Net_Amount">{$order->get('Charges Net Amount')}</td>
            </tr>
            <tr>
                <td class="label">{t}Shipping{/t}</td>
                <td class="aright Shipping_Net_Amount">{$order->get('Shipping Net Amount')}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">{t}Net{/t}</td>
                <td class="aright Total_Net_Amount">{$order->get('Total Net Amount')}</td>
            </tr>

            <tr class="subtotal">
                <td class="label">{t}Tax{/t}</td>
                <td class="aright Total_Tax_Amount">{$order->get('Total Tax Amount')}</td>
            </tr>

            <tr class="total">
                <td class="label">{t}Total{/t}</td>
                <td class="aright Total_Amount">{$order->get('Total Amount')}</td>
            </tr>
            <tr class="{if $account->get('Account Currency')==$order->get('Order Currency Code')}hide{/if}">
                <td colspan="2"
                    class="Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
            </tr>

        </table>
        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>

<script>

    function create_delivery_note(element){

        var request = '/ar_edit_orders.php?tipo=create_delivery_note&object=order&key='+$('#order').attr('order_key')
        $.getJSON(request, function (data) {
            if(data.state==200){


            }
        })
    }

    function approve_dispatch(element){

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$(element).attr('dn_key')+'&value=Approved'
        $.getJSON(request, function (data) {
            if(data.state==200){


            }
        })
    }

</script>