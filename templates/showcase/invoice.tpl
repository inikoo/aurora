<div class="invoice">
    <div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:350px;">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-fw  fa-user" title="{t}Customer name{/t}"  ></i> <span onclick="change_view('/customers/{$invoice->get('Invoice Store Key')}/{$invoice->get('Invoice Customer Key')}')" class="link Invoice_Customer_Name">{$invoice->get('Invoice Customer Name')}</span>
            </div>
            <div class="data_field {if $invoice->get('Invoice Customer Name')==$invoice->get('Invoice Customer Contact Name')}hide{/if} " >
                <i class="fa fa-fw  fa-male super_discreet" title="{t}Contact name{/t}"  ></i> <span  class=" Invoice_Customer_Contact_Name">{$invoice->get('Invoice Customer Contact Name')}</span>
            </div>
            <div class="data_field ">
                <i class="fal fa-fw fa-passport" title="{t}Tax number{/t}"></i> <span class="Invoice_Tax_Number_Formatted">{if $invoice->get('Invoice Tax Number')!=''}{$invoice->get('Tax Number Formatted')}{else}<span style="font-style: italic" class="super_discreet">No tax number provided</span>{/if}</span>
            </div>

            <div class="data_field ">
                <i class="fal fa-fw fa-id-card" title="{t}Registration number{/t}"></i> <span class="Invoice_Registration_Number">{if $invoice->get('Invoice Registration Number')!=''}{$invoice->get('Invoice Registration Number')}{else}<span style="font-style: italic" class="super_discreet">No registration number provided</span>{/if}</span>
            </div>



        </div>

        <div style="clear:both">
        </div>
        <div id="billing_address_container" class="data_container" >
            <div style="min-height:80px;float:left;width:16px">
                <i style="position: relative;top:3px" class="fa fa-map-marker"></i>
            </div>
            <div style="min-width:150px;max-width:220px;margin-left: 25px" class="Invoice_Address">
                {$invoice->get('Invoice Address Formatted')}
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>


    <div id="totals" class="block totals"  style="width: 322px">


        <table >
            {if $invoice->get('Invoice Items Discount Amount')!=0 }
                <tr>
                    <td class="aright">{t}Items Gross{/t}</td>
                    <td class="Items_Gross_Amount aright">{$invoice->get('Items Gross Amount')}</td>
                </tr>
                <tr>
                    <td class="aright">{t}Discounts{/t}</td>
                    <td class="Items_Discount_Amount aright">-{$invoice->get('Items Discount Amount')}</td>
                </tr>
            {/if}
            <tr>
                <td class="aright">{t}Items Net{/t}</td>
                <td class="Items_Net_Amount aright">{$invoice->get('Items Net Amount')}</td>
            </tr>

            {if $invoice->get('Invoice Net Amount Off')!=0 }
                <tr>
                    <td class="aright">{t}Amount Off{/t}</td>
                    <td class="Net_Amount_Off aright">{$invoice->get('Net Amount Off')}</td>
                </tr>
            {/if}


            {if $invoice->get('Invoice Refund Net Amount')!=0 }
                <tr>
                    <td class="aright">{t}Credits{/t}</td>
                    <td class="aright">{$invoice->get('Refund Net Amount')}</td>
                </tr>
            {/if}
            <tr>
                <td class="aright">{t}Charges{/t}</td>
                <td class="aright">{$invoice->get('Charges Net Amount')}</td>
            </tr>
            {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Adjust Net{/t}</td>
                    <td class="aright">{$invoice->get('Total Net Adjust Amount')}</td>
                </tr>
            {/if}
            <tr>
                <td class="aright">{t}Shipping{/t}</td>
                <td class="aright">{$invoice->get('Shipping Net Amount')}</td>
            </tr>
            <tr>
                <td class="aright">{t}Insurance{/t}</td>
                <td class="aright">{$invoice->get('Insurance Net Amount')}</td>
            </tr>
            {if $invoice->get('Invoice Credit Net Amount')!=0}
                <tr>
                    <td class="aright">{t}Credits{/t}</td>
                    <td class="aright">{$invoice->get('Credit Net Amount')}</td>
                </tr>
            {/if}

            <tr class="top-border">
                <td class="aright">{t}Total Net{/t}</td>
                <td class="aright">{$invoice->get('Total Net Amount')}</td>
            </tr>
            {foreach from=$tax_data item=tax }
                <tr>
                    <td class="aright">{t}Tax{/t} <span class="small discreet">({$tax.name})</span></td>
                    <td class="aright">{$tax.amount}</td>
                </tr>
            {/foreach}
            {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Adjust Tax{/t}</td>
                    <td class="Total_Tax_Adjust_Amount aright">{$invoice->get('Total Tax Adjust Amount')}</td>
                </tr>
            {/if}
            <tr class="top-strong-border {if $account->get('Account Currency')==$invoice->get('Invoice Currency')}bottom-strong-border{/if}">
                <td class="aright">{t}Total{/t}</td>
                <td class="aright"><b class="Total_Amount">{$invoice->get('Total Amount')}</b></td>
            </tr>
            <tr style="{if $account->get('Account Currency')==$invoice->get('Invoice Currency')}display:none{/if}"
                class="exchange bottom-strong-border">
                <td class="aright">{$invoice->get('account_currency_label')}/{$invoice->get('Invoice Currency')} {(1/$invoice->get('Invoice Currency Exchange'))|string_format:"%.3f"}</td>
                <td class="Corporate_Currency_Total_Amount aright">{$invoice->get('Corporate Currency Total Amount')}</td>
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

    <div  class="block totals"  style="width: 225px">

        <table >


            <tr class="bottom-border">
                <td class="aright">{t}Items Profit{/t}</td>
                <td class="aright">{$invoice->get('Total Profit')}</td>
            </tr>
            <tr class="bottom-border">
                <td class="aright">{t}Margin{/t}</td>
                <td class="aright">{$invoice->get('Margin')}</td>
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

    <div id="dates" class="block dates" >
        <table class="date_and_state">
            <tr class="date">
                <td class="button"  onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')" ><i class="fa fa-shopping-cart padding_right_5" aria-hidden="true"></i> {$order->get('Public ID')}</td>
            </tr>

            {assign 'category' $invoice->get('Category Object') }
            {if $category->id}
            <tr class="date">
                <td class="button"  onclick="change_view('invoices/category/{$category->id}')"  ><i class="fal fa-sitemap padding_right_5" ></i> {$category->get('Label')}</td>
            </tr>
            {/if}
            <tr class="date">
                <td title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</td>
            </tr>
            <tr class="state">
                <td>{$invoice->get_formatted_payment_state()}</td>
            </tr>

            <tr class="date pdf_label_container">
                <td class="top_pdf_label_mark">
                    <img class="button pdf_link left_pdf_label_mark" onclick="download_pdf_from_ui($('.pdf_asset_dialog.invoice'),'invoice',{$invoice->id},'invoice')" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"> <i onclick="show_pdf_settings_dialog(this,'invoice',{$invoice->id},'invoice')" title="{t}PDF invoice display settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>



                </td>
            </tr>



            {if isset($export_omega) and $export_omega}
                <tr class="state " >
                    <td>
                        <span  class=" button" onclick="export_omega()"><i class="fa fa-omega"></i> Omega export</span>
                    </td>

                </tr>
            {/if}

        </table>





        </table>
    </div>


    <div style="clear:both">
    </div>
</div>
{include file="pdf_asset_dialog.tpl" asset='invoice' type='invoice'}

<script>

    $('#totals').height($('#object_showcase').height())
    $('#dates').height($('#object_showcase').height())




    function export_omega(){

        window.open('/invoice.omega.txt.php?id={$invoice->id}', '_blank')
    }

</script>