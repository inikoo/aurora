<div class="invoice">
    <div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-fw  fa-user" title="{t}Customer name{/t}"  ></i> <span onclick="change_view('/customers/{$invoice->get('Invoice Store Key')}/{$invoice->get('Invoice Customer Key')}')" class="link Invoice_Customer_Name">{$invoice->get('Invoice Customer Name')}</span>
            </div>

            <div class="data_field ">
                <i class="fab fa-fw  fa-black-tie" title="{t}Tax number{/t}"></i> <span class="Invoice_Tax_Number">{if $invoice->get('Invoice Tax Number')!=''}{$invoice->get('Invoice Tax Number')}{else}<span style="font-style: italic" class="super_discreet">No tax number provided</span>{/if}</span>
            </div>

            <div class="data_field ">
                <i class="fa fa-fw fa-university" title="{t}Registration number{/t}"></i> <span class="Invoice_Registration_Number">{if $invoice->get('Invoice Registration Number')!=''}{$invoice->get('Invoice Registration Number')}{else}<span style="font-style: italic" class="super_discreet">No registration number provided</span>{/if}</span>
            </div>

        </div>

        <div style="clear:both">
        </div>
        <div id="billing_address_container" class="data_container" style="">
            <div style="min-height:80px;float:left;width:16px">
                <i style="position: relative;top:3px" class="fa fa-inventory"></i>
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
            {if $invoice->get('Invoice Items Discount Amount')!=0 }
                <tr>
                    <td class="aright">{t}Items Gross{/t}</td>
                    <td class="aright">{$invoice->get('Items Gross Amount')}</td>
                </tr>
                <tr>
                    <td class="aright">{t}Discounts{/t}</td>
                    <td class="aright">-{$invoice->get('Items Discount Amount')}</td>
                </tr>
            {/if}
            <tr>
                <td class="aright">{t}Items Net{/t}</td>
                <td class="aright">{$invoice->get('Items Net Amount')}</td>
            </tr>

            {if $invoice->get('Invoice Net Amount Off')!=0 }
                <tr>
                    <td class="aright">{t}Amount Off{/t}</td>
                    <td class="aright">{$invoice->get('Net Amount Off')}</td>
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
                    <td class="aright">{$tax.name}</td>
                    <td class="aright">{$tax.amount}</td>
                </tr>
            {/foreach}
            {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
                <tr style="color:red">
                    <td class="aright">{t}Adjust Tax{/t}</td>
                    <td class="aright">{$invoice->get('Total Tax Adjust Amount')}</td>
                </tr>
            {/if}
            <tr class="top-strong-border {if $account->get('Account Currency')==$invoice->get('Invoice Currency')}bottom-strong-border{/if}">
                <td class="aright">{t}Total{/t}</td>
                <td class="aright"><b>{$invoice->get('Total Amount')}</b></td>
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

    <div  class="block totals"  style="width: 250px">


        <table border="0" >


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
        <table border="0" class="date_and_state">
            <tr class="date">
                <td class="button"  onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> {$order->get('Public ID')}</td>
            </tr>


            <tr class="date">
                <td title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</td>
            </tr>
            <tr class="state">
                <td>{$invoice->get_formatted_payment_state()}</td>
            </tr>

            <tr class="date">
                <td>
                    <img class="button pdf_link" onclick="download_pdf($('.pdf_invoice_dialog img'))" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"> <i onclick="show_pdf_invoice_dialog(this)" title="{t}PDF invoice display settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>

                    <div class="pdf_invoice_dialog options_dialog  hide" style="min-width: 150px;text-align: left" data-data='{ "type":"invoice","invoice_key":{$invoice->id}}'>
                        <i onclick="$('.pdf_invoice_dialog').addClass('hide')" style="float: right;margin-left: 10px" class="fa fa-window-close button"></i>
                        <h2 class="unselectable">{t}PDF Invoice{/t}</h2>

                        <table>
                            <tbody>
                            <tr data-field='rrp' class="button pdf_option" onclick="check_field_value(this)">
                                <td>
                                    <i class="far {if $pdf_with_rrp}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_rrp}class="discreet"{/if}>{t}Recommended retail prices{/t}</span>
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

                </td>
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