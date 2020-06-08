{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  2:33 pm Tuesday, 25 February 2020 (MYT), Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}
{assign deliveries $order->get_deliveries('objects')}
{assign returns $order->get_returns('objects')}

{assign invoices $order->get_invoices('objects')}

{assign payments $order->get_payments('objects','Completed')}




{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="order_showcase_container {$data.type} {if !$data.show}hide{/if}" style="padding-bottom:{$bottom_margin}px">
    <div class="table_top">
        <span class="title">{t}Order{/t} <span class="Order_Public_ID"></span> </span>
    </div>

    <div style="clear:both;" class="timeline_horizontal with_time hide  {if $order->get('State Index')<0}hide{/if}  ">

        <ul class="timeline">
            <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
                <div class="label">
                    <span class="state ">{t}Submitted{/t}</span>
                </div>
                <div class="timestamp" >
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
                    <span class="state">{if $order->get('Order For Collection')=='Yes' }{t}Collected{/t}{else}{t}Dispatched{/t}{/if} </span>
                </div>
                <div class="timestamp">
                <span>&nbsp;<span class="Order_Dispatched_Date"> {$order->get('Dispatched Date')}</span>
                    &nbsp;</span>
                </div>
                <div class="dot"></div>
            </li>

        </ul>


    </div>

    <div class="timeline_horizontal hide  {if $order->get('State Index')>0}hide{/if}">
        <ul class="timeline" id="timeline">
            <li id="submitted_node" class="li complete">
                <div class="label">
                    <span class="state ">{t}Submitted{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date Compact')}</span> <span class="start_date">{$order->get('Created Date Compact')} </span>
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
    <div id="order" class="order dropshipping" style="display: flex;" order_key="{$order->id}"
         data-customer_key="{$order->get('Order Customer Key')}" data-state_index="{$order->get('State Index')}">
        <div class="block" style=" align-items: stretch;flex: 1">
            <div class="data_container" style="padding:5px 10px">
                <div class="data_field  " >

              <span  >
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i>
                  <a href="/client.sys?id={$customer_client->id}"><span class=" Customer_Client_Name">{$customer_client->get('Name')}</span> <span class="link Customer_Client_Code">{$customer_client->get('Code')}</span></a>
              </span>
                </div>
                <div class="data_field {if $customer_client->get('Customer Client Name')==$customer_client->get('Customer Client Main Contact Name')}hide{/if} " >
                    <i class="fa fa-fw  fa-male super_discreet" title="{t}Contact name{/t}"  ></i> <span  class=" Customer_Client_Main_Contact_Name">{$customer_client->get('Customer Client Main Contact Name')}</span>
                </div>


                <div class="data_field small  " style="margin-top:10px">

                    <span id="display_telephones"></span>
                    {if $customer_client->get('Customer Client Preferred Contact Number')=='Mobile'}
                        <div id="Customer_Main_Plain_Mobile_display"
                             class="data_field {if !$customer_client->get('Customer Client Main Plain Mobile')}hide{/if}">
                            <i class="fal fa-fw fa-mobile"></i> <span class="Customer_Main_Plain_Mobile">{$customer_client->get('Main XHTML Mobile')}</span>
                        </div>
                        <div id="Customer_Main_Plain_Telephone_display"
                             class="data_field {if !$customer_client->get('Customer Client Main Plain Telephone')}hide{/if}">
                            <i class="fal fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer_client->get('Main XHTML Telephone')}</span>
                        </div>
                    {else}
                        <div id="Customer_Main_Plain_Telephone_display"
                             class="data_field {if !$customer_client->get('Customer Client Main Plain Telephone')}hide{/if}">
                            <i title="Telephone" class="fa fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer_client->get('Main XHTML Telephone')}</span>
                        </div>
                        <div id="Customer_Main_Plain_Mobile_display"
                             class="data_field {if !$customer_client->get('Customer Client Main Plain Mobile')}hide{/if}">
                            <i title="Mobile" class="fal fa-fw fa-mobile"></i> <span
                                    class="Customer_Main_Plain_Mobile">{$customer_client->get('Main XHTML Mobile')}</span>
                        </div>
                    {/if}

                </div>

                <div class="data_field small {if $customer_client->get('Customer Client Main Plain Email')==''}hide{/if}" style="margin-top:5px">
                    <div >
                        <i class="fal fa-envelope fa-fw" title="{t}Email{/t}"></i>  {if $customer_client->get('Customer Client Main Plain Email')!=''}{mailto address=$customer_client->get('Customer Client Main Plain Email')}{/if}
                    </div>
                </div>
                <div class="data_field small Order_Tax_Number_display {if $order->get('Order Tax Number')==''}hide{/if} " style="margin-top:5px">
                    <i class="fal fa-fw fa-passport" title="{t}Tax number{/t}"></i> <span class="Order_Tax_Number_Formatted">{$order->get('Tax Number Formatted')}</span>
                </div>

                <div class="data_field Order_Registration_Number_display {if $order->get('Order Registration Number')==''}hide{/if}  small" style="margin-top:5px">
                    <i class="fal fa-fw fa-id-card" title="{t}Registration number{/t}"></i> <span class="Order_Registration_Number">{$order->get('Registration Number')}</span>
                </div>


                <div class="data_field  " style="padding:10px 0px 20px 0px;">
                    <div style="float:left;padding-bottom:20px;padding-right:20px" class="Delivery_Address   ">
                        <div class="small" style="margin-bottom:7px">
                        <span class="deliver_to_label {if $order->get('Order For Collection')=='Yes'}hide{/if}">

                                 <i class="far fa-parachute-box"></i>
                                 {t}Drop ship to{/t}

                        </span>
                            <span class="for_collection_label {if $order->get('Order For Collection')=='No'}hide{/if}"><i class="   far fa-hand-holding-box  " ></i>{t}Collection{/t}</span>

                        </div>

                        <div class="small Order_Delivery_Address {if $order->get('Order For Collection')=='Yes'}hide{/if} " >
                            {$order->get('Order Delivery Address Formatted')}
                        </div>
                    </div>

                </div>


            </div>
            <div style="clear:both"></div>
        </div>

        <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px">


            <div class="state warning_block  priority_label  {if $order->get('Order Priority Level')=='Normal' or $order->get('State Index')<=10 or $order->get('State Index')==100  }hide{/if}  "
                 style="height:30px;text-align: center;line-height: 30px">
                {t}Priority{/t} <i title="'._('Priority dispatching').'" style="font-size: 25px" class="fas fa-shipping-fast"></i>
            </div>

            <div class="state " style="height:30px;text-align: center">

                <span  class="Order_State"> {$order->get('State')} </span>



            </div>

            <table class="info_block acenter">

                <tr>

                    <td>
                        {if isset($delivery_note)}
                            {if $delivery_note->get('Delivery Note Weight Source')=='Given'}
                                <i class="fal fa-weight"></i>
                                <span title="{t}Delivery weight{/t}" class=" margin_right_10 DN_Weight">{$delivery_note->get('Weight')}</span>
                            {else}
                                <span title="{t}Estimated delivery weight{/t}" class="italic discreet margin_right_20 DN_Estimated_Weight">{$delivery_note->get('Weight')}</span>
                            {/if}
                            <span class="margin_right_20"> {$delivery_note->get('Number Parcels')}</span>
                        {else}
                            <span title="{t}Estimated weight{/t}" class="  margin_right_20 Order_Estimated_Weight">{$order->get('Estimated Weight')}</span>
                        {/if}
                        <span "><i class="fal fa-cube fa-fw " title="{t}Number of items{/t}"></i> <span class="Order_Number_items">{$order->get('Number Items')}</span></span>
                        <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " title="{t}Number discounted items{/t}"></i> <span
                                    class="Order_Number_Items_with_Deals">{$order->get('Number Items with Deals')}</span></span>
                        <span class="error {if $order->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px">
                        <i class="fa fa-cube fa-fw" title="{t}Number out of stock items{/t}"></i>
                        <span class="Order_Number_Items_with_Out_of_Stock">{$order->get('Number Items Out of Stock')}</span></span>
                        <span class="error {if $order->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px">
                        <i class="fa fa-thumbs-o-down fa-fw" title="{t}Number items returned{/t}"></i>
                        <span class="Order_Number_Items_with_Returned">{$order->get('Number Items Returned')}</span></span>
                    </td>
                </tr>
            </table>








            <table class="totals" style="margin-top:0px;border-top:none">


                <tr class="subtotal Items_Discount_Amount_tr  {if $order->get('Order Items Discount Amount')==0}hide{/if}" style="margin-top:0px;border-top:none">
                    <td class="label small" style="margin-top:0px;border-top:none">{t}Items{/t}</td>
                    <td class="aright" style="margin-top:0px;border-top:none"><span class="discreet italic">(<span class=" Items_Discount_Percentage">{$order->get('Items Discount Percentage')}</span>)</span> <span
                                class="padding_left_10 Items_Discount_Amount">{$order->get('Items Discount Amount')}</span></td>
                </tr>
                <tr class="subtotal Charges_Discount_Amount_tr  {if $order->get('Order Charges Discount Amount')==0}hide{/if} ">
                    <td class="label small">{t}Charges{/t}</td>
                    <td class="aright"><span class="discreet italic">(<span class=" Charges_Discount_Percentage">{$order->get('Charges Discount Percentage')}</span>)</span> <span
                                class="padding_left_10 Charges_Discount_Amount">{$order->get('Charges Discount Amount')}</span></td>
                </tr>
                <tr class="subtotal Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if} ">
                    <td class="label small">{t}Amount off{/t}</td>
                    <td class="aright"><span class="discreet italic">(<span class=" Amount_Off_Discount_Percentage">{$order->get('Amount Off Percentage')}</span>)</span> <span
                                class="padding_left_10 Deal_Amount_Off">{$order->get('Amount Off')}</span></td>
                </tr>
            </table>

            <div id="delivery_notes" class="delivery_notes {if $deliveries|@count == 0}hide{/if}" style="position:relative;;">


                {foreach from=$deliveries item=dn}
                    <div class="node" id="delivery_node_{$dn->id}">
                    <span class="node_label" {if  $dn->get('Delivery Note Type')!='Order'}error{/if}>
                         {$dn->get('Icon')}
                        <span class="small" >{$dn->get('ID')}</span>
                        <span style="font-size: 11px">(<span class=" Delivery_Note_State">{$dn->get('Abbreviated State')}</span>)</span>


                                   </span>







                    </div>
                {/foreach}

            </div>





            <div id="invoices" class="invoices {if $invoices|@count == 0}hide{/if}" >


                {foreach from=$invoices item=invoice}
                    <div class="node" id="invoice_{$invoice->id}">
                    <span class="node_label">
                        <i class="fal fa-file-invoice-dollar fa-fw {if $invoice->get('Invoice Type')=='Refund'}error {/if}" aria-hidden="true"></i>
                        <span class=" {if $invoice->get('Invoice Type')=='Refund'}error{/if}">{$invoice->get('Invoice Public ID')}</span>
                        <a target="_blank" href="ar_web_invoice.pdf.php?id={$invoice->id}"><img class=" pdf_link"  style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                    </span>
                        <div class="red" style="float: right;padding-right: 10px;padding-top: 5px">{if $invoice->get('Invoice Type')=='Refund'} {$invoice->get('Refund Total Amount')} {if $invoice->get('Invoice Paid')!='Yes'}
                                <i class="fa fa-exclamation-triangle warning fa-fw" aria-hidden="true" title="{t}Return payment pending{/t}"></i>
                            {/if}  {/if}</div>

                    </div>
                {/foreach}
            </div>





            <div class="customer_balance_showcase {if $customer->get('Customer Account Balance')==0 }hide{/if} ">
                <div style="margin-bottom: 0px" class="payments  customer_balance  {if $order->get('Order State')!='InBasket' }hide{/if}  ">
                    <div class="payment node  ">
                        <span class="node_label   ">{$customer->get('Account Balance')} {t}available credit{/t}</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 0px"  class="payments order_payments_list {if $order->get('Order Number Items')==0  or $order->get('State Index')<0   or ($order->get('Order State')=='InBasket' and empty($payments)  )  }hide{/if}  ">


                {assign expected_payment $order->get('Expected Payment')}


                <div id="expected_payment" class="payment node  {if $expected_payment==''}hide{/if} ">


                    <span class="node_label   ">{$expected_payment}</span>


                </div>





                <div id="payment_nodes">
                    {foreach from=$payments item=payment}
                        <div class="payment node">
                        <span class="node_label">
                            <span >{if $payment->payment_account->get('Payment Account Block')=='Accounts'  or  $payment->get('Payment Method')=='Account'    }{t}Credit{/t}{else}{$payment->get('Payment Account Code')}{/if}</span> </span>
                            <span class="node_amount"> {$payment->get('Transaction Amount')}</span>
                        </div>
                    {/foreach}
                </div>


            </div>

            <div style="clear:both"></div>


            <table class="totals  payment_overview  {if $order->get('Order State')=='InBasket' and empty($payments) }hide{/if}" style="width: 100%;margin-top: 10px">


                <tbody id="total_payments" class="{if $order->get('State Index')<0}hide{/if}">

                <tr class="total Order_Payments_Amount  {if $order->get('Order To Pay Amount')==0    }hide{/if}  ">
                    <td class="label">{t}Paid{/t}</td>
                    <td class="aright Payments_Amount">{$order->get('Payments Amount')}</td>
                </tr>
                <tr class="total strong  Order_To_Pay_Amount {if $order->get('Order To Pay Amount')==0    }hide{/if} "  >
                    <td class="label ">
                        <span class="To_Pay_Label {if $order->get('Order To Pay Amount')<0}hide{/if}">{t}To pay{/t}</span>
                        <span class="To_Refund_Label {if $order->get('Order To Pay Amount')>0}hide{/if}">{t}To credit / refund{/t}</span>
                    </td>
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



            <table class="totals" style="position:relative;top:-5px">

                <tr class="{if $order->get('Order Total Net Amount')==$order->get('Order Items Net Amount')  and $order->get('Order Charges Net Amount')==0  and  $order->get('Order Shipping Net Amount')==0 and $order->get('Order Items Discount Amount')==0 }hide{/if}">
                    <td class="label">{t}Items{/t}</td>
                    <td class="aright "><span class="{if $order->get('Order Items Discount Amount')==0}hide{/if} Items_Gross_Amount strikethrough">{$order->get('Items Gross Amount')}</span> <span
                                class="Items_Net_Amount">{$order->get('Items Net Amount')}</span></td>
                </tr>


                <tr class="{if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                    <td class="label" id="Charges_Net_Amount_label">{t}Charges{/t}</td>
                    <td class="aright "><span  id="Charges_Net_Amount" class="Charges_Net_Amount ">{$order->get('Charges Net Amount')}<span></td>
                </tr>
                <tr class="{if $order->get('Order Shipping Method')=='TBC' and $order->get('State Index')>=90 }hide{/if}">
                    <td class="label" >{t}Shipping{/t}</td>


                    <td class="aright ">

                        <span class="{if $order->get('Order Shipping Discount Amount')==0}hide{/if} Shipping_Gross_Amount strikethrough">{$order->get('Shipping Gross Amount')}</span>

                        <span id="Shipping_Net_Amount" class="Shipping_Net_Amount">{$order->get('Shipping Net Amount')}<span>
                    </td>
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
                    <td class="label">{t}Tax{/t} </td>
                    <td class="aright Total_Tax_Amount">{$order->get('Total Tax Amount')}</td>
                </tr>
                <tr class="subtotal" style="height: auto">
                    <td colspan=2 class="label " style="text-align: center;padding:0px 0px 5px 00px"><span class="small discreet Tax_Description">{$order->get('Tax Description')}</span></td>
                </tr>
                <tr class="total">
                    <td class="label">{t}Total{/t}</td>
                    <td class="aright Total_Amount  " amount="{$order->get('Order To Pay Amount')}" >{$order->get('Total Amount')}</td>
                </tr>


                <tr class="total  {if $order->get('Order Total Refunds')==0}hide{/if}">
                    <td class="label" title="{t}Total refunds{/t}">{t}Refunds{/t}</td>
                    <td class="aright Total_Refunds   ">{$order->get('Total Refunds')}</td>
                </tr>

                <tr class="total {if $order->get('Order Total Refunds')==0}hide{/if}">
                    <td class="label" title="{t}Total final balance after refunds{/t}">{t}Final balance{/t}</td>
                    <td class="aright Total_Balance   ">{$order->get('Total Balance')}</td>
                </tr>





            </table>
            <div style="clear:both"></div>
        </div>
        <div style="clear:both"></div>
    </div>
    <div id="table_container" style="margin-bottom: 70px"></div>

</div>

