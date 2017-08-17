<div class="invoice">
    <div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;">
        <div class="data_container">
            <div class="data_field">
                <i class="fa fa-user"></i> <span>{$invoice->get('Invoice Customer Name')}</span>
            </div>
            {if $invoice->get('Invoice Tax Number')!=''}
            <div class="data_field ">
                <i class="fa fa-black-tie"></i></i> <span>{$invoice->get('Invoice Tax Number')}</span>
            </div>
            {/if}
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
    <div id="dates" class="block dates" style="width: 300px">
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
                <td><a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img style="width: 50px;height:16px" src="/art/pdf.gif"></a></td>

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