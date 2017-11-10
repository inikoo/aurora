{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 November 2017 at 15:12:11 GMT+8, Semijyak, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div class="invoice">
    <div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-fw  fa-user" title="{t}Customer name{/t}"  ></i> <span class="Invoice_Customer_Name">{$invoice->get('Invoice Customer Name')}</span>
            </div>

            <div class="data_field ">
                <i class="fa fa-fw  fa-black-tie" title="{t}Tax number{/t}"></i> <span class="Invoice_Tax_Number">{if $invoice->get('Invoice Tax Number')!=''}{$invoice->get('Invoice Tax Number')}{else}<span style="font-style: italic" class="super_discreet">No tax number provided</span>{/if}</span>
            </div>

            <div class="data_field ">
                <i class="fa fa-fw fa-university" title="{t}Registration number{/t}"></i> <span class="Invoice_Registration_Number">{if $invoice->get('Invoice Registration Number')!=''}{$invoice->get('Invoice Registration Number')}{else}<span style="font-style: italic" class="super_discreet">No registration number provided</span>{/if}</span>
            </div>

        </div>

        <div style="clear:both">
        </div>
        <div id="billing_address_container" class="data_container" style="">
            <div style="min-height:80px;float:left;width:16px">
                <i style="position: relative;top:3px" class="fa fa-map-marker"></i>
            </div>
            <div style="min-width:150px;max-width:220px;margin-left: 25px">
                {$invoice->get('Invoice Address Formatted')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>


    <div id="totals" class="block totals"  style="width: 300px">


        <table border="0">

            <tr>
                <td class="aright">{t}Refund items net{/t}</td>
                <td class="aright">{$invoice->get('Refund Items Net Amount')}</td>
            </tr>



            {if $invoice->get('Invoice Charges Net Amount')!=0}
            <tr>
                <td class="aright">{t}Refund charges net{/t}</td>
                <td class="aright">{$invoice->get('Refund Charges Net Amount')}</td>
            </tr>
            {/if}
            {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Refund adjusts net{/t}</td>
                    <td class="aright">{$invoice->get('Refund Total Net Adjust Amount')}</td>
                </tr>
            {/if}
            {if $invoice->get('Invoice Shipping Net Amount')!=0}
            <tr>
                <td class="aright">{t}Refund shipping net{/t}</td>
                <td class="aright">{$invoice->get('Refund Shipping Net Amount')}</td>
            </tr>
            {/if}
            {if $invoice->get('Invoice Insurance Net Amount')!=0}

            <tr>
                <td class="aright">{t}Refund insurance net{/t}</td>
                <td class="aright">{$invoice->get('Refund Insurance Net Amount')}</td>
            </tr>
            {/if}


            <tr class="top-border">
                <td class="aright">{t}Refund total net{/t}</td>
                <td class="aright">{$invoice->get('Refund Total Net Amount')}</td>
            </tr>
            {foreach from=$tax_data item=tax }
                <tr>
                    <td class="aright">{t}Refund {/t} {$tax.name}</td>
                    <td class="aright">{$tax.amount}</td>
                </tr>
            {/foreach}
            {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Refund Adjust Tax{/t}</td>
                    <td class="aright">{$invoice->get('Refund Total Tax Adjust Amount')}</td>
                </tr>
            {/if}
            <tr class="top-strong-border {if $account->get('Account Currency')==$invoice->get('Invoice Currency')}bottom-strong-border{/if}">
                <td class="aright">{t}Refund total{/t}</td>
                <td class="aright"><b>{$invoice->get('Refund Total Amount')}</b></td>
            </tr>
            <tr style="{if $account->get('Account Currency')==$invoice->get('Invoice Currency')}display:none{/if}"
                class="exchange bottom-strong-border">
                <td class="aright">{$account->get('Account Currency')}
                    /{$invoice->get('Invoice Currency')} {(1/$invoice->get('Invoice Currency Exchange'))|string_format:"%.3f"}</td>
                <td class="aright">{$invoice->get('Corporate Currency Total Amount')}</td>
            </tr>


            <tr id="tr_order_total_to_pay"
                style="{if $invoice->get('Invoice Outstanding Total Amount')==0}display:none{/if}">
                <td class="aright">
                    <div class="buttons small left">
                        <button style="{if $invoice->get('Invoice Outstanding Total Amount')==0}display:none{/if}"
                                id="show_add_payment" amount="{$invoice->get('Invoice Outstanding Total Amount')}"
                                onclick="add_payment('invoice','{$invoice->id}')"><img
                                    src="art/icons/add.png"> {t}Payment{/t}</button>
                    </div>
                    <span style="{if $invoice->get('Invoice Outstanding Total Amount')>0}display:none{/if}"
                          id="to_refund_label">{t}To Refund{/t}</span>
                    <span style="{if $invoice->get('Invoice Outstanding Total Amount')<0}display:none{/if}"
                          id="to_pay_label">{t}To Pay{/t}</span></td>
                <td id="order_total_to_pay" width="100" class="aright"
                    style="font-weight:800">{$invoice->get('Outstanding Total Amount')}</td>
            </tr>
        </table>

        <div id="sticky_note_div" class="sticky_note pink"
             style="position:relative;left:-20px;width:270px;{if $invoice->get('Sticky Note')==''}display:none{/if}">
            <img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif">
            <div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
                {$invoice->get('Sticky Note')}
            </div>
        </div>


        <div style="clear:both">
        </div>

    </div>
    <div id="dates" class="block dates" style="width: 300px">
        <table border="0" class="date_and_state">
            <tr class="date">
                <td class="button"  onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> {$order->get('Public ID')}</td>
            </tr>


            <tr class="date">
                <td title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</td>
            </tr>


            <tr class="date">
                <td><a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img style="width: 50px;height:16px" src="/art/pdf.gif"></a></td>

            </tr>
            <tr class="state">
                <td>{$invoice->get_formatted_payment_state()}</td>
            </tr>

        </table>





        </table>
    </div>


    <div style="clear:both">
    </div>
</div>
<script>

    $('#totals').height($('#object_showcase').height())
    $('#dates').height($('#object_showcase').height())
</script>