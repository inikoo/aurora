{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2017 at 12:44:12 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{assign deliveries $order->get_deliveries('objects')}
{assign invoices $order->get_invoices('objects')}
{assign payments $order->get_payments('objects','Completed')}

<div id="order" class="order" style="display: flex;" data-object='{$object_data}' order_key="{$order->id}">
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
                <div ><i class="fa fa-phone fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Telephone">{$order->get('Telephone')}</span></div>


            </div>

            <div class="data_field small {if $order->get('Email')==''}hide{/if}" style="margin-top:5px">


                <div ><i class="fa fa-envelope fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Email">{$order->get('Email')}</span></div>

            </div>

            <div class="data_field  " style="padding:10px 0px 20px 0px;">

                <div style="float:left;padding-bottom:20px;" class="Billing_Address">
                    <div style="margin-bottom:10px"><i class="fa fa-dollar-sign button" aria-hidden="true""></i>{t}Billed to{/t}</div>
                    <div class="small Order_Invoice_Address" style="max-width: 140px;">{$order->get('Order Invoice Address Formatted')}</div>
                </div>
                <div style="clear:both"></div>
            </div>


        </div>
        <div style="clear:both"></div>
    </div>

    <div class="block " style="align-items: stretch;flex: 1 ">

        <div id="delivery_notes" class="delivery_notes {if $deliveries|@count == 0}hide{/if}" style="position:relative;top:-5px;">



        {foreach from=$deliveries item=dn}

            <div class="node" id="delivery_node_{$dn->id}">
                    <span class="node_label" >
                         <i class="fa fa-truck fa-flip-horizontal fa-fw " aria-hidden="true"></i> <span class="link" onClick="change_view('delivery_notes/{$dn->get('Delivery Note Store Key')}/{$dn->id}')">{$dn->get('ID')}</span>
                        (<span class="Delivery_Note_State">{$dn->get('Abbreviated State')}</span>)
                        <a class="pdf_link {if $dn->get('State Index')<90 }hide{/if}" target='_blank' href="/pdf/dn.pdf.php?id={$dn->id}"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                    </span>
            </div>
        {/foreach}

        </div>


        <div id="invoices" class="invoices {if $invoices|@count == 0}hide{/if}" style="margin-bottom:10px;">



            {foreach from=$invoices item=invoice}

                <div class="node" id="invoice_{$invoice->id}">
                    <span class="node_label" >
                        <i class="fal fa-file-invoice-dollar fa-fw " aria-hidden="true"></i>
                        <span class="link" onClick="change_view('invoices/{$invoice->get('Invoice Store Key')}/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span>
                        <a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                    </span>
                </div>
            {/foreach}

        </div>








        <div class="payments {if $order->get('Order Number Items')==0  or $order->get('State Index')<0 }hide{/if}  "  >


            {assign expected_payment $order->get('Expected Payment')}


            <div id="expected_payment" class="payment node  {if $expected_payment==''}hide{/if} " >




                <span class="node_label   ">{$expected_payment}</span>







            </div>



            <div id="create_payment" class="payment node">


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
                    <span class="node_label"> <span class="link" onClick="change_view('order/{$order->id}/payment/{$payment->id}')">{if $payment->payment_account->get('Payment Account Block')=='Accounts'  }{t}Credit{/t}{else}{$payment->get('Payment Account Code')}{/if}</span> </span>
                    <span class="node_amount"> {$payment->get('Transaction Amount')}</span>
                </div>
            {/foreach}
            <div class="payment node" style="border-top:1px solid #555;border-bottom:1px solid #555 ">
                <span class="node_label">{t}To pay{/t} </span>
                <span class="node_amount"> {$order->get('To Pay Amount')}</span>
            </div>
        </div>




    </div>

<div style="clear:both"></div></div>
    <div class="block " style="align-items: stretch;flex: 1 ">
    <table class="totals" style="position:relative;top:-5px">

        <tr class="total ">

            <td colspan="2" class="align_center ">{t}Original order{/t}</td>
        </tr>


        <tr>
            <td class="label">{t}Items{/t}</td>
            <td class="aright Items_Net_Amount">{$order->get('Items Net Amount')}</td>
        </tr>
       
        <tr>
            <td class="label"  id="Charges_Net_Amount_label" >{t}Charges{/t}</td>
            <td class="aright "><span id="Charges_Net_Amount_form" class="hide"><i id="set_charges_as_auto" class="fa fa-magic button" onClick="set_charges_as_auto()" aria-hidden="true"></i>  <input value="{$order->get('Order Charges Net Amount')}" ovalue="{$order->get('Order Charges Net Amount')}"  style="width: 100px" id="Charges_Net_Amount_input"  > <i id="Charges_Net_Amount_save" class="fa fa-cloud save" onClick="save_charges_value()" aria-hidden="true"></i> </span><span id="Charges_Net_Amount" class="Charges_Net_Amount button" >{$order->get('Charges Net Amount')}<span></td>
        </tr>
        <tr>
            <td class="label"  id="Shipping_Net_Amount_label" >{t}Shipping{/t}</td>
            <td class="aright "><span id="Shipping_Net_Amount_form" class="hide"><i id="set_shipping_as_auto" class="fa fa-magic button" onClick="set_shipping_as_auto()" aria-hidden="true"></i>  <input value="{$order->get('Order Shipping Net Amount')}" ovalue="{$order->get('Order Shipping Net Amount')}"  style="width: 100px" id="Shipping_Net_Amount_input"  > <i id="Shipping_Net_Amount_save" class="fa fa-cloud save" onClick="save_shipping_value()" aria-hidden="true"></i> </span><span id="Shipping_Net_Amount" class="Shipping_Net_Amount button" >{$order->get('Shipping Net Amount')}<span></td>
        </tr>
        <tr class="subtotal">
            <td class="label">{t}Net{/t}</td>
            <td class="aright Total_Net_Amount">{$order->get('Total Net Amount')}</td>
        </tr>

        <tr class="subtotal">
            <td class="label">{t}Tax{/t} {if $order->get('Order Total Tax Amount')>0} <span onclick="refund_tax_only()" class="small show_refund_tax_only very_discreet_on_hover button">(Refund tax only)</span>{else}<span class="show_refund_tax_only"></span>{/if} </td>
            <td class="aright Total_Tax_Amount">{$order->get('Total Tax Amount')}</td>
        </tr>

        <tr class="total">
            <td class="label">{t}Total{/t}</td>
            <td class="aright Total_Amount  button " amount="{$order->get('Order To Pay Amount')}" onclick="try_to_pay(this)">{$order->get('Total Amount')}</td>
        </tr>




        </tbody>



    </table>
    <div style="clear:both"></div>
</div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state create_net_and_tax_refund" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Order_State"> {t}Creating refund{/t} </span>
            <div id="forward_operations">

                <div id="create_refund_operations" class="order_operation {if {$order->get('State Index')}<100  }hide{/if}">
                    <div  class="square_button right  " title="{t}Create refund{/t}">
                        <i class="fa fa-cloud save   open_create_refund_dialog_button" aria-hidden="true" onclick="create_refund(this)"></i>

                    </div>
                </div>



            </div>
        </div>


        <div class="state hide create_tax_only_refund  " style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Order_State"> {t}Create tax only refund{/t} </span>
            <div >

                <div i class="order_operation {if {$order->get('State Index')}<100  }hide{/if}">
                    <div  class="square_button right  " title="{t}Create tax only refund{/t}">
                        <i class="fa fa-cloud save   open_create_refund_dialog_button" aria-hidden="true" onclick="create_tax_only_refund(this)"></i>

                    </div>
                </div>



            </div>
        </div>

        <table class="info_block acenter">

            <tr>

                <td>
                    <span ><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="affected_items">0</span> / <span class="Order_Number_items">{$order->get('Number Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-percent fa-fw  " aria-hidden="true"></i> <span class="percentage_refunded">0.00</span>%</span>


                </td>
            </tr>

            <table class="totals" style="position:relative;top:20px">


                <tr class="subtotal first">
                    <td class="label">{t}Net{/t}</td>
                    <td class="aright Refund_Net_Amount">{$zero_amount}</td>
                </tr>
                <tr class="subtotal first">
                    <td class="label">{t}Tax{/t}</td>
                    <td class="aright Refund_Tax_Amount">{$zero_amount}</td>
                </tr>
                <tr class="total first">
                    <td class="label">{t}Total{/t}</td>
                    <td class="aright Refund_Total_Amount">{$zero_amount}</td>
                </tr>

            </table>




        </table>

    </div>
<div style="clear:both"></div></div>

<div id="add_payment" class="table_new_fields hide">

    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-bottom: 1px solid #ccc;position: relative">

        <i style="position:absolute;top:10px;" class="fa fa-window-close fa-flip-horizontal button" aria-hidden="true" onclick="close_add_payment_to_order()"></i>

        <table style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td style="width: 500px">
                <td>
                <td></td>

                <td>
                    <div id="new_payment_payment_account_buttons">
                        {foreach from=$store->get_payment_accounts('objects','Active') item=payment_account}

                            {if $payment_account->get('Payment Account Block')=='Accounts'}
                                <div class="button  {if $customer->get('Customer Account Balance')<=0}hide{/if} {$payment_account->get('Payment Account Block')}" onclick="select_payment_account(this)"
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"{$customer->get('Customer Account Balance')}" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }' class="new_payment_payment_account_button unselectable
                        button {if $payment_account->get('Payment Account Block')=='Accounts' and $customer->get('Customer Account Balance')<=0  }hide{/if}" style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{t}Customer credit{/t} <span class="discreet padding_left_10">{$customer->get('Account Balance')}</span></div>
                                {else}
                                <div  class="button" onclick="select_payment_account(this)"
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }' class="new_payment_payment_account_button unselectable
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

<style>
    .feedback_otf_form .scope{
        margin-right: 10px;
    }
</style>

<div class="feedback_otf_form hide" style="position:absolute;background-color:#fff;z-index:2000;padding:10px 0px 10px 10px;width: 500px;border:1px solid #ccc"  data-feedback_set_label="<i class='fa fa-comment-alt-exclamation padding_right_5'></i> {t}Feedback set{/t}">
    <table style="width: 100%">
        <tr>
            <td>
                <span  onclick="feedback_otf_scope_clicked(this)" data-label="{t}Supplier{/t}" data-scope="Supplier" class="scope button very_discreet_on_hover Supplier"> <i class="far fa-square"></i> {t}Supplier{/t}</span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)" data-label="{t}Picker{/t}" data-scope="Picker" class="scope button very_discreet_on_hover Picker"><i class="far fa-square"></i> {t}Picker{/t} </span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)" data-label="{t}Packer{/t}" data-scope="Packer" class="scope button very_discreet_on_hover Packer"><i class="far fa-square"></i> {t}Packer{/t} </span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)" data-label="{t}Warehouse{/t}" data-scope="Warehouse" class="scope button very_discreet_on_hover Warehouse"><i class="far fa-square"></i> {t}Warehouse{/t} </span>
            </td>


            </td>
        </tr>
        <tr>

            <td>
                <span onclick="feedback_otf_scope_clicked(this)" data-label="{t}Courier{/t}" data-scope="Courier" class="scope button very_discreet_on_hover Courier"><i class="far fa-square"></i> {t}Courier{/t} </span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)"  data-label="{t}Marketing{/t}" data-scope="Marketing" class="scope button very_discreet_on_hover Marketing"><i class="far fa-square "></i> {t}Marketing{/t} </span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)"  data-label="{t}Customer{/t}" data-scope="Customer" class="scope button very_discreet_on_hover Customer"><i class="far fa-square"></i> {t}Customer{/t} </span>
            </td>
            <td>
                <span onclick="feedback_otf_scope_clicked(this)"  data-label="{t}Management{/t}" data-scope="Other" class="scope button very_discreet_on_hover Other"><i class="far fa-square"></i> {t}Management{/t} </span>
            </td>




        </tr>
        <tr>
            <td colspan=4 style="padding-right: 25px"><textarea  style="width: 100%;" placeholder="{t}Feedback{/t}"></textarea></td>
        </tr>
        <tr>
            <td colspan=2 class="aleft padding_left_10"><span onclick="close_otf_feedback()" class="close_feedback  button very_discreet_on_hover">{t}Close{/t}</span></td>

            <td colspan=2 class="aright padding_right_10"><span class="save save_feedback "><i class="fa fa-check "></i> {t}Ok{/t}</span></td>
        </tr>

    </table>

</div>




<script type="text/javascript" src="/js/injections/refund.new.js"></script>

<script>
    close_refund_tax_only()
</script>