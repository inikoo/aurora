{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 November 2017 at 15:12:11 GMT+8, Semijyak, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{assign payments $invoice->get_payments('objects','Completed')}

<div id="order" class="order" style="display: flex;" data-object='{$object_data}' invoice_key="{$invoice->id}">
    <div class="block" style=" align-items: stretch;flex: 2;padding: 20px">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-fw  fa-user" title="{t}Customer name{/t}"></i> <span class ="link" onclick="change_view('customers/{$invoice->get('Store Key')}/{$invoice->get('Customer Key')}')" class="Invoice_Customer_Name">{$invoice->get('Invoice Customer Name')}</span>
            </div>

            <div class="data_field ">
                <i class="fab fa-fw fa-black-tie" title="{t}Tax number{/t}"></i> <span class="Invoice_Tax_Number">{if $invoice->get('Invoice Tax Number')!=''}{$invoice->get('Invoice Tax Number')}{else}

                        <span style="font-style: italic" class="super_discreet">No tax number provided</span>
                    {/if}</span>
            </div>

            <div class="data_field ">
                <i class="fa fa-fw fa-university" title="{t}Registration number{/t}"></i> <span
                        class="Invoice_Registration_Number">{if $invoice->get('Invoice Registration Number')!=''}{$invoice->get('Invoice Registration Number')}{else}

                        <span style="font-style: italic" class="super_discreet">No registration number provided</span>
                    {/if}</span>
            </div>

        </div>

        <div id="billing_address_container" class="data_container" >
            <div style="min-height:80px;float:left;width:16px">
                <i style="position: relative;top:3px" class="fa fa-map-marker"></i>
            </div>
            <div style="min-width:150px;max-width:220px;margin-left: 25px">
                {$invoice->get('Invoice Address Formatted')}
            </div>
        </div>


    </div>

    <div class="block " style="align-items: stretch;flex: 1 ">


        <table class="date_and_state">
            <tr class="date" style="text-align: center">
                <td title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</td>
            </tr>


            <tr class="date" style="text-align: center">
                <td><a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img style="width: 50px;height:16px" src="/art/pdf.gif"></a></td>

            </tr>


        </table>


        <div class="orders " style="margin-top:5px">


            <div class="node" style="border-top: 1px solid #ccc;">
            <span class="node_label">
                         <i class="fa fa-shopping-cart  fa-fw " aria-hidden="true"></i>
                <span class="link" onClick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')">{$order->get('Public ID')}</span>
                    </span>
            </div>

        </div>


    </div>

    <div class="block " style="align-items: stretch;flex: 1;">


        <div style="margin-bottom: 5px" class="payments  ">


            <div id="create_payment" class="payment node">


                <span class="node_label very_discreet italic">{t}Return payments{/t}</span>


                <div class="payment_operation hide {if $invoice->get('Invoice To Pay Amount')==0     }hide{/if}  ">
                    <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}add payment{/t}">
                        <i class="fa fa-plus" aria-hidden="true" onclick="show_add_payment_to_order()"></i>

                    </div>
                </div>


            </div>


            <div id="payment_nodes">
                {foreach from=$payments item=payment}
                    <div class="payment node">
                        <span class="node_label"> <span class="link"
                                                        onClick="change_view('order/{$invoice->id}/payment/{$payment->id}')">{if $payment->payment_account->get('Payment Account Block')=='Accounts'  }{t}Credit{/t}{else}{$payment->get('Payment Account Code')}{/if}</span> </span>
                        <span class="node_amount"> {$payment->get('Transaction Amount')}</span>
                    </div>
                {/foreach}
            </div>


        </div>


        <table class="totals" style="width: 100%">


            <tbody id="total_payments" >

            <tr class="total Refund_Payments_Amount  {if $invoice->get('Invoice To Pay Amount')==0    }hide{/if}  ">
                <td class="label">{t}Paid back{/t}</td>
                <td class="aright Payments_Amount">{$invoice->get('Refund Payments Amount')}</td>
            </tr>
            <tr class="total  Refund_To_Pay_Amount {if $invoice->get('Invoice To Pay Amount')==0    }hide{/if} button" amount="{-1*$invoice->get('Invoice To Pay Amount')}"
                onclick="try_to_pay(this)">
                <td class="label">{t}To pay back{/t}</td>
                <td class="aright To_Pay_Amount   ">{$invoice->get('Refund To Pay Amount')}</td>
            </tr>
            <tr class="total success  Refund_Paid {if $invoice->get('Invoice To Pay Amount')!=0   or $invoice->get('Invoice Total Amount')==0  }hide{/if}">

                <td colspan="2" class="align_center "><i class="fa fa-check-circle" aria-hidden="true"></i> {t}Paid back{/t}</td>
            </tr>
            </tbody>


            <tbody id="total_payments_cancelled_refund" class="error {if $invoice->get('State Index')>0}hide{/if}">
            <tr class="total Refund_Payments_Amount  {if $invoice->get('InvoicePayments Amount')==0    }hide{/if}  "  style="background-color: rgba(255,0,0,.05); " >
                <td style="brefund-color:rgba(255,0,0,1)" class="label">{t}Paid{/t}</td>
                <td style="brefund-color:rgba(255,0,0,1)" class="aright Payments_Amount">{$invoice->get('Payments Amount')}</td>
            </tr>

            </tbody>
        </table>


    </div>
    <div class="block " style="align-items: stretch;flex: 1;">


        <table >

            <tr>
                <td class="aright">{t}Refund items net{/t}</td>
                <td class="aright error">{$invoice->get('Refund Items Net Amount')}</td>
            </tr>


            {if $invoice->get('Invoice Charges Net Amount')!=0}
                <tr>
                    <td class="aright">{t}Refund charges net{/t}</td>
                    <td class="aright error">{$invoice->get('Refund Charges Net Amount')}</td>
                </tr>
            {/if}
            {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Refund adjusts net{/t}</td>
                    <td class="aright error">{$invoice->get('Refund Total Net Adjust Amount')}</td>
                </tr>
            {/if}
            {if $invoice->get('Invoice Shipping Net Amount')!=0}
                <tr>
                    <td class="aright">{t}Refund shipping net{/t}</td>
                    <td class="aright error">{$invoice->get('Refund Shipping Net Amount')}</td>
                </tr>
            {/if}
            {if $invoice->get('Invoice Insurance Net Amount')!=0}
                <tr>
                    <td class="aright">{t}Refund insurance net{/t}</td>
                    <td class="aright error">{$invoice->get('Refund Insurance Net Amount')}</td>
                </tr>
            {/if}


            <tr class="top-border">
                <td class="aright">{t}Refund total net{/t}</td>
                <td class="aright error">{$invoice->get('Refund Total Net Amount')}</td>
            </tr>
            {foreach from=$tax_data item=tax }
                <tr>
                    <td class="aright">{t}Refund {/t} {$tax.name}</td>
                    <td class="aright error">{$tax.amount}</td>
                </tr>
            {/foreach}
            {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Refund Adjust Tax{/t}</td>
                    <td class="aright error">{$invoice->get('Refund Total Tax Adjust Amount')}</td>
                </tr>
            {/if}
            <tr class="top-strong-border {if $account->get('Account Currency')==$invoice->get('Invoice Currency')}bottom-strong-border{/if}">
                <td class="aright">{t}Refund total{/t}</td>
                <td class="aright  error"><b>{$invoice->get('Refund Total Amount')}</b></td>
            </tr>
            <tr style="{if $account->get('Account Currency')==$invoice->get('Invoice Currency')}display:none{/if}" class="exchange bottom-strong-border">
                <td class="aright">{$account->get('Account Currency')}
                    /{$invoice->get('Invoice Currency')} {(1/$invoice->get('Invoice Currency Exchange'))|string_format:"%.3f"}</td>
                <td class="aright">{$invoice->get('Corporate Currency Total Amount')}</td>
            </tr>



        </table>

        <div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $invoice->get('Sticky Note')==''}display:none{/if}">
            <img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif">
            <div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
                {$invoice->get('Sticky Note')}
            </div>
        </div>


        <div style="clear:both"></div>

    </div>


</div>


<div id="add_payment" class="table_new_fields hide">

    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-bottom: 1px solid #ccc;position: relative">

        <i style="position:absolute;top:10px;" class="fa fa-window-close fa-flip-horizontal button" aria-hidden="true" onclick="close_add_payment_to_order()"></i>

        <table style="width:50%;float:right;width:100%;">
            <tr>
                <td style="width: 500px">
                <td>
                <td></td>

                <td>
                    <div id="new_payment_payment_account_buttons">
                        {foreach from=$store->get_payment_accounts('objects','Active') item=payment_account}

                            {if $payment_account->get('Payment Account Block')=='Accounts'}
                                <div class="button  payment_button   {$payment_account->get('Payment Account Block')}" onclick="select_payment_account(this)"
                                     data-settings='{ "payment_account_key":"{$payment_account->id}", "max_amount":"" , "payment_method":"{$payment_account->get('Default Payment Method')}", "block":"{$payment_account->get('Payment Account Block')}" }'
                                     class="new_payment_payment_account_button unselectable
                        button " style="border:1px solid #ccc;padding:10px
                        5px;margin-bottom:2px">{t}Customer credit{/t} </div>
                            {else}
                                <div class="button payment_button    " onclick="select_payment_account(this)    "
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


<script>





</script>