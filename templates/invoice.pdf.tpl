<html>
<head> x
    <style>
        {literal}


        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        p {
            margin: 0pt;
        }

        h1 {
            font-size: 14pt
        }

        td {
            vertical-align: top;
        }

        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
            border-bottom: 0.1mm solid #cfcfcf;
            padding-bottom: 4px;
            padding-top: 5px;
        }


        .items tbody.out_of_stock td {
            color: #777;
            font-style: italic
        }

        .items tbody.totals td {
            text-align: right;
            border: 0.1mm solid #222;
        }

        .items tr.total_net td {
            border-top: 0.3mm solid #000;
        }

        .items tr.total td {
            border-top: 0.3mm solid #000;
            border-bottom: 0.3mm solid #000;
        }

        .items tr.last td {

            border-bottom: 0.1mm solid #000000;
        }

        table thead td, table tr.title td {
            background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }

        .items td.blanktotal {
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }


        div.inline {
            float: left;
        }

        .clearBoth {
            clear: both;
        }
        .hide{display:none}
        {/literal}</style>
</head>
<body>
<htmlpageheader name="myheader">
    <table width="100%" style="font-size: 9pt;" >

        <tr>

            <td style="width:250px;padding-left:10px;">{$invoice->metadata('store_name')}
                <div style="font-size:7pt">
                    {$invoice->metadata('store_address')|nl2br}
                </div>
                <div style="font-size:7pt">
                    {$invoice->metadata('store_url')}
                </div>
            </td>

            {if $number_orders==1}

            <td style="text-align: right;">{t}Order Number{/t}<br/>


                     <b>{$order->get('Order Public ID')}</b>
                </td>

            {/if}


        </tr>
    </table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; "></div>
    <table width="100%">
        <tr>
        <tr>
            <td width="33%" style="color:#000;text-align: left;">
                <small> {$invoice->metadata('store_company_name')}<br> {if $invoice->metadata('store_vat_number')!=''}{t}VAT Number{/t}:
                        <b>{$invoice->metadata('store_vat_number')}</b>
                        <br>
                    {/if} {if $invoice->metadata('store_company_number')!=''}{t}Registration Number{/t}: {$invoice->metadata('store_company_number')}{/if} </small>
            </td>
            <td width="33%" style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
            <td width="34%" style="text-align: right;">
                <small> {if $invoice->metadata('store_telephone')!=''}{$invoice->metadata('store_telephone')}<br>{/if} {if $invoice->metadata('store_email')!=''}{$invoice->metadata('store_email')}{/if} </small>
            </td>
        </tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1"/>
<sethtmlpagefooter name="myfooter" value="on"/>
<table width="100%">
    <tr>
        <td>
            <h1>
                {$label_title} {$invoice->get('Invoice Public ID')}
            </h1>
        </td>
        <td style="text-align: right">
            <div>
                {t}Invoice Date{/t}:<b>{$invoice->get_date('Invoice Date')}</b>
            </div>

            {if   !empty($pastpay_due_date)}
            <div>
                {t}Payment Due Date{/t}:<b>{$pastpay_due_date}</b>
            </div>
            {/if}

            <div style="text-align: right">
                {t}Tax liability date{/t}" <b>{$invoice->get_date('Invoice Tax Liability Date')}</b>
            </div>
            {if $number_orders==1}


            {if $order->get('Order Date')|strtotime <= $invoice->get('Invoice Date')|strtotime}
                {t}Order Date{/t}: <b>{$order->get_date('Order Date')}</b>
               {else}

                *{t}Order Date{/t}: <b>{$invoice->get_date('Invoice Tax Liability Date')}</b>
                {/if}




                {if $invoice->get('Invoice Type')!='Invoice'}
                <div style="text-align: right">
                    {t}Invoice Number{/t}: <b>{$original_invoice->get('Invoice Public ID')}</b>
                </div>
                {/if}
                {if $order->get('Order Customer Purchase Order ID')!=''}
                    <div style="text-align: right">
                        {t}Customer's PO Reference{/t}: <b>{$order->get('Order Customer Purchase Order ID')}</b>
                    </div>
                {/if}
            {/if} </td>
    </tr>
</table>
<table width="100%" style="font-family: sans-serif;" cellpadding="0">
    <tr>
        <td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;">
            {if  !($hide_payment_status or $pastpay)}
            <div style="text-align: right">
                {t}Payment State{/t}: <b>{$invoice->get('Payment State')}</b>
            </div>
            {/if}
            <div style="text-align: right">
                {t}Customer{/t}: <b>{$invoice->get('Invoice Customer Name')|strip_tags|escape}</b> ({$invoice->get('Invoice Customer Key')})
            </div>
            <div >
                {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                    <div  class=" {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <span class="address_label">{t}Mobile{/t}:</span> <span class="address_value"  >{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                    <div class=" {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <span class="address_label">{t}Phone{/t}:</span>  <span class="address_value">{$customer->get('Main XHTML Telephone')}</span>
                    </div>
                {else}


                    <div class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <span class="address_label">{t}Phone{/t}:</span> <span class="address_value">{$customer->get('Main XHTML Telephone')}</span>
                    </div class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                    <div class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <span class="address_label">{t}Mobile{/t}:</span> <span class="address_value" >{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                {/if}

            </div>
            <div style="{if $invoice->get('Invoice Tax Number')==''}display:none{/if}">
                {t}Tax Number{/t}: <b>{$invoice->get('Invoice Tax Number')}</b>
            </div>

            <div style="{if $invoice->get('Invoice Registration Number')==''}display:none{/if}">
                {t}Registration Number{/t}: <b>{$invoice->get('Invoice Registration Number')}</b>
            </div>
            <div style="{if $invoice->get('Invoice EORI')==''}display:none{/if}">
                EORI: <b>{$invoice->get('Invoice EORI')}</b>
            </div>

        </td>
        <td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;text-align: right">
            {if $number_dns==1}
                <div style="text-align:right;{if !$delivery_note->get('Delivery Note Number Parcels')}display:none{/if}">
                    <b> {$delivery_note->get_formatted_parcels()}</b>
                </div>
                <div style="text-align: right">{t}Weight{/t}: <b>{$delivery_note->get('Weight')}</b></div>
                {if $delivery_note->data['Delivery Note Shipper Consignment']!=''}
                    <div style="text-align: right">
                        {t}Courier{/t}: <b> <span id="formatted_consignment">{$delivery_note->get('Consignment')|strip_tags}</span></span> </b>
                    </div>
                {/if}
            {/if}

        </td>
    </tr>
</table>
<table width="100%" style="font-family: sans-serif;" cellpadding="10">
    <tr>
        <td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Billing address{/t}:</span>
            <div>
                {$invoice->get('Invoice Address Formatted')}
            </div>
        </td>
        <td width="10%">&nbsp;</td>
        <td width="45%" style="border: 0.1mm solid #888888;"> {if isset($delivery_note)}
                <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivery address{/t}:</span>
                <div>
                    {$delivery_note->get('Delivery Note Address Formatted')}
                </div>
            {/if} </td>
    </tr>
</table>
<br>

{if $group_by_tariff_code}
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
    <thead>
    <tr>
        <td style="width:14%;text-align:left">{t}Code{/t}</td>
        <td style="width:14%;text-align:left">{t}Origin{/t}</td>

        <td style="text-align:left">{t}Description{/t}</td>
        <td style="text-align:left;width:20% ">{t}Codes{/t}</td>

        <td style="text-align:left">{t}Qty{/t}</td>

        <td style="width:10%;text-align:right">{t}Amount{/t}</td>
    </tr>
    </thead>
    <tbody>
    {foreach from=$transactions_grouped_by_tariff_code item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if}">
            <td style="text-align:left">{$transaction['Code']}</td>
            <td style="text-align:left">{$transaction['origin']}</td>

            <td style="text-align:left">{$transaction['Description']}</td>
            <td style="text-align:left">{$transaction['codes']}</td>

            <td style="text-align:right">{$transaction['Qty']}</td>

            <td style="text-align:right">{$transaction['Amount']}</td>
        </tr>
    {/foreach}
    </tbody>
    <tbody class="totals">
    <tr>
        <td style="border:none" colspan="4"></td>
        <td>{t}Items Net{/t}</td>
        <td>{$invoice->get('Items Net Amount')}</td>
    </tr>
    {if $invoice->get('Invoice Net Amount Off')!=0 }
        <tr>
            <td style="border:none" colspan="4"></td>
            <td colspan="2">{t}Amount Off{/t}</td>
            <td>{$invoice->get('Net Amount Off')}</td>
        </tr>
    {/if}

    {if $invoice->get('Invoice Refund Net Amount')!=0 }
        <tr>
            <td style="border:none" colspan="4"></td>
            <td >{t}Refunds{/t}</td>
            <td>{$invoice->get('Refund Net Amount')}</td>
        </tr>
    {/if}
    <tr>
        <td style="border:none" colspan="4"></td>
        <td >{t}Shipping{/t}</td>
        <td>{$invoice->get('Shipping Net Amount')}</td>
    </tr>
    {if $invoice->get('Invoice Charges Net Amount')!=0}
        <tr>
            <td style="border:none" colspan="4"></td>
            <td >{t}Charges{/t}</td>
            <td>{$invoice->get('Charges Net Amount')}</td>
        </tr>
    {/if} {if $invoice->get('Invoice Insurance Net Amount')!=0}
        <tr>
            <td style="border:none" colspan="4"></td>
            <td >{t}Insurance{/t}</td>
            <td>{$invoice->get('Insurance Net Amount')}</td>
        </tr>
    {/if} {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
        <tr>
            <td style="border:none" colspan="4"></td>
            <td >{t}Adjusts{/t}</td>
            <td>{$invoice->get('Total Net Adjust Amount')}</td>
        </tr>
    {/if}
    <tr class="total_net">
        <td style="border:none" colspan="4"></td>
        <td >{t}Total Net{/t}</td>
        <td>{$invoice->get('Total Net Amount')}</td>
    </tr>
    {foreach from=$tax_data item=tax }
        <tr>
            <td style="border:none" colspan="4">{$tax.base}</td>
            <td class="totals" >{$tax.name}</td>
            <td class="totals">{$tax.amount}</td>
        </tr>
    {/foreach}
    <tr class="total">
        <td style="border:none" colspan="4"></td>
        <td ><b>{t}Total{/t}</b></td>
        <td>{$invoice->get('Total Amount')}</td>
    </tr>
    </tbody>

</table>
{else}
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
    <thead>
    <tr>
        <td style="width:14%;text-align:left">{t}Code{/t}</td>
        <td style="text-align:left">{t}Description{/t}</td>
        {if $pro_mode}
            <td style="width:14%;text-align:right">{t}Unit price{/t}</td>
            <td style="width:11%;text-align:right">{t}Units{/t}</td>
         {else}
            <td style="width:10%;text-align:right;font-size: 10px">{if $invoice->get('Invoice Type')=='Invoice'}{t}Discount{/t}{/if}</td>
            <td style="width:11%;text-align:right">{t}Quantity{/t}</td>
        {/if}
        <td style="width:10%;text-align:right">{t}Amount{/t}</td>
    </tr>
    </thead>
    <tbody>
    {foreach from=$transactions item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if}">
            <td style="text-align:left">{$transaction['Product Code']}</td>
            <td style="text-align:left">{$transaction['Description']}</td>

            {if $pro_mode}

                <td style="text-align:right">{$transaction['Unit_Price']}</td>
                <td style="text-align:right">{$transaction['Qty_Units']}</td>
            {else}

                <td style="text-align:right">{$transaction['Discount']}</td>
            <td style="text-align:right">{$transaction['Qty']}</td>
            {/if}
            <td style="text-align:right">{$transaction['Amount']}</td>
        </tr>
    {/foreach}
    </tbody>
    {if $number_transactions_out_of_stock>0}
        <tr class="title">
            <td colspan="5">{t}Ordered products not dispatched{/t}</td>
        </tr>
    {/if}
    <tbody class="out_of_stock">
    {foreach from=$transactions_out_of_stock item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if}">
            <td style="text-align:left">{$transaction['Product Code']}</td>
            <td style="text-align:left">{$transaction['Description']}</td>
            <td colspan="2" style="text-align:right"><span>{t}Out of Stock{/t}</span> {$transaction['Quantity']}</td>
            <td style="text-align:right">{$transaction['Amount']}</td>
        </tr>
    {/foreach}
    </tbody>
    <tbody class="totals">
    <tr>
        <td style="border:none" colspan="2"></td>
        <td colspan="2">{t}Items Net{/t}</td>
        <td>{$invoice->get('Items Net Amount')}</td>
    </tr>
    {if $invoice->get('Invoice Net Amount Off')!=0 }
        <tr>
            <td style="border:none" colspan="2"></td>
            <td colspan="2">{t}Amount Off{/t}</td>
            <td>{$invoice->get('Net Amount Off')}</td>
        </tr>
    {/if}

    {if $invoice->get('Invoice Refund Net Amount')!=0 }
        <tr>
            <td style="border:none" colspan="2"></td>
            <td colspan="2">{t}Refunds{/t}</td>
            <td>{$invoice->get('Refund Net Amount')}</td>
        </tr>
    {/if}
    <tr>
        <td style="border:none" colspan="2"></td>
        <td colspan="2">{t}Shipping{/t}</td>
        <td>{$invoice->get('Shipping Net Amount')}</td>
    </tr>
    {if $invoice->get('Invoice Charges Net Amount')!=0}
        <tr>
            <td style="border:none" colspan="2"></td>
            <td colspan="2">{t}Charges{/t}</td>
            <td>{$invoice->get('Charges Net Amount')}</td>
        </tr>
    {/if} {if $invoice->get('Invoice Insurance Net Amount')!=0}
        <tr>
            <td style="border:none" colspan="2"></td>
            <td colspan="2">{t}Insurance{/t}</td>
            <td>{$invoice->get('Insurance Net Amount')}</td>
        </tr>
    {/if} {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
        <tr>
            <td style="border:none" colspan="2"></td>
            <td colspan="2">{t}Adjusts{/t}</td>
            <td>{$invoice->get('Total Net Adjust Amount')}</td>
        </tr>
    {/if}
    <tr class="total_net">
        <td style="border:none" colspan="2"></td>
        <td colspan="2">{t}Total Net{/t}</td>
        <td>{$invoice->get('Total Net Amount')}</td>
    </tr>
    {foreach from=$tax_data item=tax }
        <tr>
            <td style="border:none" colspan="2">{$tax.base}</td>
            <td class="totals" colspan="2">{$tax.name}</td>
            <td class="totals">{$tax.amount}</td>
        </tr>
    {/foreach}
    <tr class="total">
        <td style="border:none" colspan="2"></td>
        <td colspan="2"><b>{t}Total{/t}</b></td>
        <td>{$invoice->get('Total Amount')}</td>
    </tr>
    </tbody>
</table>
{/if}
<br> <br>


{assign "payments" $invoice->get_payments('objects','Completed')}
{if $payments|@count gt 0  and  !$pastpay}

<table class="items" width="100%" style="display:none;font-size: 9pt; border-collapse: collapse;" cellpadding="8">
    <tr class="title">
        <td colspan="5">{t}Payments{/t}</td>
    </tr>
    <thead>
    <tr>
        <td style="width:40%;text-align:left">{t}Method{/t}</td>
        <td style="text-align:right">{t}Date{/t}</td>
        <td style="text-align:left">{t}Status{/t}</td>
        <td style="text-align:left">{t}Reference{/t}</td>
        <td style=";text-align:right">{t}Amount{/t}</td>
    </tr>
    </thead>
    <tbody>

    {foreach from=$payments item=payment name=payments}
        <tr class="{if $smarty.foreach.payments.last}last{/if}">
            <td style="text-align:left">{if $payment->get('Payment Type')=='Credit'}{t}Credit{/t}{else}{$payment->get('Method')}{if $payment->get('Payment Type')=='Refund'} ({t}Refund{/t}){/if}{/if}</td>
            <td style="text-align:right">{$payment->get('Created Date')}</td>
            <td style="text-align:left">{$payment->get('Transaction Status')}</td>
            <td style="text-align:left">{$payment->get('Payment Transaction ID')|strip_tags}</td>
            <td style="text-align:right">{$payment->get('Transaction Amount')}</td>
        </tr>
    {/foreach}

    </tbody>
</table>
<br>
{/if}

{foreach from=$pastpay_notes item=pastpay_note}
    <div style="text-align: center; font-style: italic;">
        {$pastpay_note}
    </div>
    <br>
{/foreach}

{if $invoice->metadata('store_message')!=''}
    <div style="text-align: center; font-style: italic;">
        {include file="string:{$invoice->metadata('store_message')}" }
    </div>
    <br>
{/if}



{if $invoice->get('Invoice Message')!=''}
    <div style="text-align: center; font-style: italic;">
        {include file="string:{$invoice->get('Invoice Message')}" }
    </div><br>
{/if}
{if $account->get('Account Country 2 Alpha Code')=='SK'  and $invoice->get('Invoice Tax Number')!=''  and $invoice->get('Invoice Address Country 2 Alpha Code')!='SK'   }
    <div style="text-align: center; font-style: italic;">
        {t}Transfer of tax liability{/t}
    </div>
    <br>
{/if}
{if $extra_comments!=''}
    <div style="text-align: center; font-style: italic;">
        {$extra_comments}
    </div>
    <br>
{/if}


</body>
</html>
