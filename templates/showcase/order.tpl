{assign deliveries $order->get_deliveries('objects')}
{assign invoices $order->get_invoices('objects')}
{assign payments $order->get_payments('objects','Completed')}

{$order->get('State Index')}
<div class="timeline_horizontal with_time   {if $order->get('Order Current Dispatch State')=='Cancelled'}hide{/if}  ">

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
                <span class="state" >&nbsp;{t}In warehouse{/t}&nbsp;<span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Order_In_Warehouse" ">&nbsp;

                    <span class="Order_Send_to_Warehouse_Date">&nbsp;{$order->get('Send to Warehouse Date')}</span>

                &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li class="li ">
            <div class="label">
                <span class="state">{t}Packed{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Packed_Date_or_Percentage">&nbsp;{$order->get('Packed Date or Percentage')}</span>
                    </span>
            </div>
            <div class="dot"></div>
        </li>

        <li class="li ">
            <div class="label">
                <span class="state">{t}Invoiced{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class=""></span>
                    &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li class="li ">
            <div class="label">
                <span class="state">{t}Dispatched{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class=""></span>
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

            <li id="warehouse_node" class="li  {if $dn->get('State Index')>=70}complete{/if}">
                <div class="label">
                    <span class="state ">&nbsp;{$dn->get('State')}&nbsp;<span></i></span></span>
                </div>
                <div class="timestamp">
                <span class="Order_Inputted_Date">&nbsp;<span class="">{$dn->get('Picking and Packing Percentage or Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>

            <li class="li {if $order->get('State Index')==100}complete{/if}">
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


<div class="timeline_horizontal  {if $order->get('Order Current Dispatch State')!='Cancelled'}hide{/if}">
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

<div id="order" class="order" style="display: flex;" data-object="{$object_data}" order_key="{$order->id}">
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  " style="margin-bottom:10px">

              <span class="button" onclick="change_view('customers/{$order->get('Order Store Key')}/{$order->get('Order Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                          class="button Order_Customer_Name">{$order->get('Order Customer Name')}</span> <span

                          class="link Order_Customer_Key">{$order->get('Order Customer Key')|string_format:"%05d"}</span>
              </span>
            </div>

            <div class="data_field small {if $order->get('Telephone')==''}hide{/if}  " style="margin-top:5px">
                <div class=""><i class="fa fa-phone fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Telephone">{$order->get('Telephone')}</span></div>


            </div>

            <div class="data_field small {if $order->get('Email')==''}hide{/if}" style="margin-top:5px">


                <div class=""><i class="fa fa-envelope fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Email">{$order->get('Email')}</span></div>

            </div>

            <div class="data_field  " style="padding:10px 0px 20px 0px;">
                <div style="float:left;padding-bottom:20px;padding-right:20px" class="Delivery_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-truck fa-flip-horizontal button" aria-hidden="true""></i>{t}Deliver to{/t}</div>
                    <div class="small Order_Delivery_Address " style="max-width: 140px;">{$order->get('Order Delivery Address Formatted')}</div>
                </div>
                <div style="float:right;padding-bottom:20px;" class="Billing_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-dollar button" aria-hidden="true""></i>{t}Billed to{/t}</div>
                    <div class="small Order_Invoice_Address" style="max-width: 140px;">{$order->get('Order Invoice Address Formatted')}</div>
                </div>
                <div style="clear:both"></div>
            </div>


        </div>
        <div style="clear:both"></div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="delete_operations" class="order_operation {if $order->get('Order Current Dispatch State')!='InProcess'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}delete{/t}">
                        <i class="fa fa-trash very_discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "PurchaseOrder", "key":"{$order->id}"}' id="delete_save_buttons" class="error save button" onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw  " aria-hidden="true"></i></span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations" class="order_operation {if $order->get('Order Current Dispatch State')=='Cancelled'}hide{/if}">
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
                                    <span data-data='{ "field": "Order Current Dispatch State","value": "Cancelled","dialog_name":"cancel"}' id="cancel_save_buttons" class="error save button"
                                          onclick="save_order_operation(this)">
                                        <span class="label">{t}Cancel{/t}</span>
                                        <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_submit_operations" class="order_operation {if $order->get('Order Current Dispatch State')!='Submitted'}hide{/if}">
                    <div class="square_button left" title="{t}Undo submit{/t}">
												<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_submit')">
						<i class="fa fa-paper-plane-o discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>


                        <table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Undo submition{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order Current Dispatch State","value": "InProcess","dialog_name":"undo_submit"}' id="undo_submit_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_send_operations" class="order_operation {if $order->get('Order Current Dispatch State')!='Send'}hide{/if}">
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
            <div id="forward_operations">
                <div id="send_to_warehouse_operations" class="order_operation {if {$order->get('State Index')|intval}>30 or  {$order->get('State Index')|intval}<10   or $order->get('Order Number Items')==0   }hide{/if}">
                    <div  class="square_button right  " title="{t}Send to warehouse{/t}">
                        <i class="fa fa-map   " aria-hidden="true" onclick="toggle_order_operation_dialog('send_to_warehouse')"></i>
                        <table id="send_to_warehouse_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send to warehouse{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('send_to_warehouse')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order Current Dispatch State","value": "In Warehouse","dialog_name":"send_to_warehouse"}' id="send_to_warehouse_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="submit_operations" class="order_operation {if $order->get('State Index')!=10   or  $order->get('Order Number Items')==0 }hide{/if}">
                    <div  class="square_button right  " title="{t}Submit{/t}">
                        <i class="fa fa-paper-plane-o   " aria-hidden="true" onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Submit order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('submit')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order Current Dispatch State","value": "Submitted by Customer","dialog_name":"submit"}' id="submit_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


            </div>
        </div>

        <table border="0" class="info_block acenter">

            <tr>

                <td>
                    <span style=""><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="Order_Number_items">{$order->get('Number Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " aria-hidden="true"></i> <span class="Order_Number_Items_with_Deals">{$order->get('Number Items with Deals')}</span></span>
                    <span class="error {if $order->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Out_of_Stock">{$order->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $order->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$order->get('Number Items Returned')}</span></span>
                </td>
            </tr>

            <table border="0" class="totals" style="position:relative;top:20px">


                <tr class="subtotal first">
                    <td class="label">{t}Discounts{/t}</td>
                    <td class="aright Items_Discount_Amount">{$order->get('Items Discount Amount')}</td>
                </tr>

                <tr class="subtotal last">
                    <td class="label"></td>
                    <td class="aright Items_Discount_Percentage">{$order->get('Items Discount Percentage')}</td>
                </tr>
            </table>




        </table>

    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">

        <div id="delivery_notes" class="delivery_notes {if $deliveries|@count == 0}hide{/if}" style="margin-bottom:10px;position:relative;top:-5px;">



        {foreach from=$deliveries item=dn}

            <div class="node" id="delivery_node_{$dn->id}">
                    <span class="node_label" >
                         <i class="fa fa-truck fa-flip-horizontal fa-fw " aria-hidden="true"></i> <span class="link" onClick="change_view('delivery_notes/{$dn->get('Delivery Note Store Key')}/{$dn->id}')">{$dn->get('ID')}</span>
                        (<span class="Delivery_Note_State">{$dn->get('Abbreviated State')}</span>)
                    </span>
            </div>
        {/foreach}

    </div>
        <div class="invoices {if {$order->get('State Index')|intval}<30 or ($order->get('Order Ordered Number Items')-$order->get('Order Number Supplier Delivery Items'))==0  }hide{/if}" style="margin-bottom:10px">
        <div id="create_invoice" class="delivery_node " style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">

            <div id="back_operations"></div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i class="fa fa-truck fa-flip-horizontal  button" aria-hidden="true"></i> {t}Delivery note{/t} </span>
            <div id="forward_operations">

                <div id="received_operations" class="order_operation {if !($order->get('Order Current Dispatch State')=='Submitted' or  $order->get('Order Current Dispatch State')=='Send') }hide{/if}">
                    <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}Input delivery note{/t}">
                        <i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i>

                    </div>
                </div>


            </div>

        </div>
        {foreach from=$invoices item=invoice}
            <div class="delivery_node" style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                    <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button" onClick="change_view('order/{$order->id}/invoice/{$invoice->id}')"> <i class="fa fa-file-text-o fa-fw  "
                                                                                                                                                                            aria-hidden="true"></i> {$invoice->get('Public ID')}</span> ({$invoice->get('State')}
                        ) </span>
            </div>
        {/foreach}
    </div>


        <div class="payments {if $order->get('Order Number Items')==0}hide{/if}  "  >
        <div id="create_payment" class="payment node"">


        <span class="node_label very_discreet italic">{t}Payments{/t}</span>


            <div class="payment_operation {if $order->get('Order To Pay Amount')<=0     }hide{/if}  ">
                <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}add payment{/t}">
                    <i class="fa fa-plus" aria-hidden="true" onclick="show_add_payment_to_order()"></i>

                </div>
            </div>




    </div>

        <div id="payment_nodes">
            {foreach from=$payments item=payment}
                <div class="payment node">
                    <span class="node_label"> <span class="link" onClick="change_view('order/{$order->id}/payment/{$payment->id}')">{$payment->get('Payment Account Code')}</span> </span>
                    <span class="node_amount"> {$payment->get('Transaction Amount')}</span>
                </div>
            {/foreach}
        </div>
    </div>

<div style="clear:both"></div></div>
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
            <td class="aright Total_Amount  button " amount="{$order->get('Order To Pay Amount')}" onclick="try_to_pay(this)">{$order->get('Total Amount')}</td>
        </tr>


        <tr class="   {if $account->get('Account Currency')==$order->get('Order Currency Code')}hide{/if}">
            <td colspan="2" class="Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
        </tr>
        <tr class="total Order_Payments_Amount  {if $order->get('Order To Pay Amount')==0   or $order->get('Order Payments Amount')==0  }hide{/if}  ">
            <td class="label">{t}Paid{/t}</td>
            <td class="aright Payments_Amount">{$order->get('Payments Amount')}</td>
        </tr>
        <tr class="total  Order_To_Pay_Amount {if $order->get('Order To Pay Amount')==0  or $order->get('Order Payments Amount')==0   }hide{/if} button" amount="{$order->get('Order To Pay Amount')}"
            onclick="try_to_pay(this)">
            <td class="label">{t}To pay{/t}</td>
            <td class="aright To_Pay_Amount   ">{$order->get('To Pay Amount')}</td>
        </tr>
        <tr class="total success  Order_Paid {if $order->get('Order To Pay Amount')!=0 or $order->get('Order Total Amount')==0 }hide{/if}">

            <td colspan="2" class="align_center "><i class="fa fa-check-circle" aria-hidden="true"></i> {t}Paid{/t}</td>
        </tr>


    </table>
    <div style="clear:both"></div>
</div>
<div style="clear:both"></div></div>

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
                            <div onclick="select_payment_account(this)"
                                 data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"" , "payment_method":"{$payment_account->get('Default Payment Method')}" }' class="new_payment_payment_account_button unselectable
                        button {if $payment_account->get('Payment Account Block')=='Accounts' and $customer->get('Customer Account Balance')<=0  }hide{/if}" style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{$payment_account->get('Name')}</div>
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

<script>

    function create_delivery_note(element) {

        var request = '/ar_edit_orders.php?tipo=create_delivery_note&object=order&key=' + $('#order').attr('order_key')
        $.getJSON(request, function (data) {
            if (data.state == 200) {


            }
        })
    }

    function approve_dispatch(element) {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $(element).attr('dn_key') + '&value=Approved'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


            }
        })
    }


    function select_payment_account(element) {

        var settings = $(element).data('settings')

        console.log(settings)
        $('#new_payment_payment_account_key').val(settings.payment_account_key)
        $('#new_payment_payment_method').val(settings.payment_method)

        $("#add_payment :input").attr("disabled", false);
        $("#add_payment .payment_fields").removeClass("just_hinted");


        $('.new_payment_payment_account_button').addClass('super_discreet')
        $(element).removeClass('super_discreet')


        if ($('#new_payment_amount').val() == '') {
            $('#new_payment_amount').focus()
        } else {
            $('#new_payment_reference').focus()
        }

    }


    function try_to_pay(element) {

        if ($(element).attr('amount') > 0) {

            if ($('#add_payment').hasClass('hide')) {
                show_add_payment_to_order()


            }
            $('#new_payment_amount').val($(element).attr('amount'))
        }


    }


    function show_add_payment_to_order() {


        if ($('#add_payment').hasClass('hide')) {

            change_tab('order.payments', {
                'add_payment': 1
            })
            $('#tabs').addClass('hide')


            $("#add_payment :input").attr("disabled", true);
            $(".payment_fields").addClass("just_hinted");


            $('#add_payment').removeClass('hide')

            $('#new_payment_payment_account_key').val('')
            $('#new_payment_payment_method').val('')

            $('.new_payment_payment_account_button').removeClass('super_discreet')


            // $('#delivery_number').val('').focus()

        }

    }


    function close_add_payment_to_order() {

        $('#add_payment').addClass('hide')

    }


    $('#add_payment').on('input propertychange', '.new_payment_field', function (evt) {

        validate_new_payment();
    })


    function validate_new_payment() {

        console.log($('#new_payment_reference').val() != '')
        console.log(!validate_number($('#new_payment_amount').val(), 0, 999999999))
        console.log($('#new_payment_payment_account_key').val() > 0)

        if ($('#new_payment_reference').val() != '' && !validate_number($('#new_payment_amount').val(), 0, 999999999) && $('#new_payment_payment_account_key').val() > 0) {
            console.log('xx')
            $('#save_new_payment').addClass('valid changed')
        } else {
            $('#save_new_payment').removeClass('valid changed')
        }


    }

    function save_new_payment() {


        if ($('#save_new_payment').hasClass('wait')) {
            return;
        }
        $('#save_new_payment').addClass('wait')

        $('#save_new_payment i').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'new_payment')
        ajaxData.append("parent", 'order')

        ajaxData.append("parent_key", '{$order->id}')
        ajaxData.append("payment_account_key", $('#new_payment_payment_account_key').val())
        ajaxData.append("amount", $('#new_payment_amount').val())
        ajaxData.append("payment_method", $('#new_payment_payment_method').val())

        ajaxData.append("reference", $('#new_payment_reference').val())


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                $('#save_new_payment').removeClass('wait')
                $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


                console.log(data)

                if (data.state == '200') {

                    $('#new_payment_amount').val('')
                    $('#new_payment_reference').val('')

                    validate_new_payment()

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    if (data.metadata.to_pay == 0 || data.metadata.payments == 0) {
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
                        $('.payment_operation').addClass('hide')

                    } else {
                        $('.payment_operation').removeClass('hide')
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


                    if (state.tab == 'order.payments') {
                        rows.fetch({
                            reset: true
                        });
                    }

                    close_add_payment_to_order()


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


            }, error: function () {
                $('#save_new_payment').removeClass('wait')
                $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

            }
        });


    }


</script>