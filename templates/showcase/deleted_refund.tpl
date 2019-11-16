{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 February 2019 at 16:58:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{assign payments $invoice->get_payments('objects','Completed')}

<div id="order" class="order" style="display: flex;" >
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


        <div class="orders " >


            <div class="node" style="text-align: center">
            <span style="position:relative;top:5px;">
                         <i class="fa fa-shopping-cart  fa-fw " aria-hidden="true"></i>
                <span class="link" onClick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')">{$order->get('Public ID')}</span>
                    </span>
            </div>

            <div class="node" style="text-align: center">
            <span style="position:relative;top:5px;" title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</span>
            </div>
            <div class="node error" style="text-align: center;background-color: rgba(255,0,0,.1)">
                <span style="position:relative;top:5px;" title="{$invoice->get('Deleted Date')}">{t}Deleted{/t} <span class="strong">{$invoice->get_date('Invoice Deleted Date')}</span></span>
            </div>

        </div>








    </div>

    <div class="block " style="align-items: stretch;flex: 1;">


        <div style="margin-bottom: 5px" class="payments  ">





        </div>


        <table class="totals" style="width: 100%">



        </table>


    </div>
    <div class="block italic discreet" style="align-items: stretch;flex: 1;">


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

