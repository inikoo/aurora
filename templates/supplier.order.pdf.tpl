<html>
<head>
    <style>


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

        .warning {
            color: #ED9121;
        }

        .error {
            color: tomato;
        }

    </style>
</head>
<body>
<htmlpageheader name="myheader">
    <table width="100%" style="font-size: 9pt;" border="0">
        <tr>
        <tr>

            <td style="width:250px;padding-left:10px;">{$purchase_order->get('Warehouse Company Name')}
                <div style="font-size:7pt">
                    {$purchase_order->get('Warehouse Address')|nl2br}
                </div>

            </td>
            <td style="text-align: right;">{t}Purchase order no.{/t}<br/>
                <span style="font-weight: bold; font-size: 12pt;">{$purchase_order->get('Public ID')}</span></td>
        </tr>
    </table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
    </div>
    <table width="100%">
        <tr>
        <tr>
            <td width="33%" style="color:#000;text-align: left;">
                <small> {$purchase_order->get('Warehouse Company Name')}
                    <br> {if $purchase_order->get('Warehouse VAT Number')!=''}{t}VAT Number{/t}:
                        <b>{$purchase_order->get('Warehouse VAT Number')}</b>
                        <br>
                    {/if} {if $purchase_order->get('Warehouse Company Number')!=''}{t}Registration Number{/t}: {$purchase_order->get('Warehouse Company Number')}{/if}
                </small>
            </td>
            <td width="33%" style="color:#000;text-align: left;">
                <small> {$purchase_order->get('Account Name')}</small>
            </td>
            <td width="33%"
                style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
            <td width="34%" style="text-align: right;">
                <small> {if $purchase_order->get('Warehouse Telephone')!=''}{$purchase_order->get('Warehouse Telephone')}
                        <br>
                    {/if} {if $purchase_order->get('Warehouse Email')!=''}{$purchase_order->get('Warehouse Email')}{/if}
                </small>
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
                {t}Purchase Order{/t}
            </h1>
        </td>
        <td style="text-align: right">

            {if $purchase_order->get('Purchase Order State')=='Cancelled' }
                <div>
                    {t}Cancelled{/t}: <b>{$purchase_order->get('Cancelled Date')}</b>
                </div>
            {else if $purchase_order->get('Purchase Order State')=='InProcess' }
                <div>
                    <b class="error">{t}Preview only, not submitted yet!{/t}</b>
                </div>
                <div>
                    {t}Date{/t}: <b>{$smarty.now|date_format:"%e %b %Y"}</b>
                </div>
            {else}
                </div>
                {t}Submitted{/t}:
                <b>{$purchase_order->get('Submitted Date')}</b>
                </div>
            {/if}


        </td>
    </tr>
</table>
<table width="100%" style="margin-top:10px;font-family: sans-serif;" cellpadding="0">
    <tr>
        <td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;">

            <div style="text-align: right">
                <b>{$purchase_order->get('Parent Name')}</b>{if $purchase_order->get('Parent Name')!=$purchase_order->get('Parent Code') } ({$purchase_order->get('Parent Code')}){/if}
            </div>

        </td>
        <td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;text-align: right">

            <div style="text-align: right; {if $purchase_order->get('Account Number')==''}display:none{/if}">
                {t}Account no.{/t} <b>{$purchase_order->get('Account Number')}</b>

            </div>
            <div style="text-align: right">
                ({$purchase_order->get('Warehouse Code')}) <b>{$purchase_order->get('Warehouse Company Name')}</b>

            </div>

        </td>
    </tr>
</table>


<table width="100%" style="font-family: sans-serif;" cellpadding="0">
    <tr>
        <td width="45%" style="border: 0.1mm solid #888888;padding:5pt 5pt 10pt 10pt"><span
                    style="font-size: 7pt; color: #777777; font-family: sans-serif;">{t}Supplier's address{/t}:</span>
            <div style="margin-top:100pt">
                {$purchase_order->get('Parent Address')}
            </div>
        </td>
        <td width="10%">&nbsp;</td>
        <td width="45%" style="border: 0.1mm solid #888888;padding:5pt 5pt 10pt 10pt"><span
                    style="font-size: 7pt; color: #777777; font-family: sans-serif;">{t}Ship to address{/t}:</span>
            <div>
                {$purchase_order->get('Warehouse Address')|nl2br}
            </div>
        </td>
    </tr>
</table>
<br>
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
    <thead>
    <tr>
        <td style="width:8%;text-align:left">{t}Reference{/t}</td>
        <td style="text-align:left">{t}Unit description{/t}</td>
        <td style="width:12%;text-align:right">{t}Units{/t}</td>
        <td style="width:8%;text-align:right">{t}Cartons{/t}</td>
        <td style="width:8%;text-align:right">{t}Amount{/t}</td>

    </tr>
    </thead>
    <tbody>
    {foreach from=$transactions item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if}">
            <td style="width:8%;text-align:left">{$transaction.reference}</td>
            <td style="text-align:left">{$transaction.description}</td>
            <td style="width:8%;text-align:right;font-size:95%">{$transaction.subtotals}</td>

            <td style="width:8%;text-align:right">{$transaction.ordered}</td>
            <td style="width:8%;text-align:right">{$transaction.amount}</td>
        </tr>
    {/foreach}
    </tbody>

    <tbody class="totals">
    <tr>
        <td style="border:none" colspan="2" rowspan="10"></td>
        <td colspan="2">{t}Items Net{/t}</td>
        <td>{$purchase_order->get('Items Net Amount')}</td>
    </tr>


    {if $purchase_order->get('Invoice Net Amount Off')!=0 }
        <tr>
            <td colspan="2">{t}Amount Off{/t}</td>
            <td>{$purchase_order->get('Net Amount Off')}</td>
        </tr>
    {/if}

    {if $purchase_order->get('Invoice Refund Net Amount')!=0 }
        <tr>
            <td colspan="2">{t}Refunds{/t}</td>
            <td>{$purchase_order->get('Refund Net Amount')}</td>
        </tr>
    {/if}
    <tr>
        <td colspan="2">{t}Shipping{/t}</td>
        <td>{$purchase_order->get('Shipping Net Amount')}</td>
    </tr>
    {if $purchase_order->get('Invoice Charges Net Amount')!=0}
        <tr>
            <td colspan="2">{t}Charges{/t}</td>
            <td>{$purchase_order->get('Charges Net Amount')}</td>
        </tr>
    {/if} {if $purchase_order->get('Invoice Insurance Net Amount')!=0}
        <tr>
            <td colspan="2">{t}Insurance{/t}</td>
            <td>{$purchase_order->get('Insurance Net Amount')}</td>
        </tr>
    {/if} {if $purchase_order->get('Invoice Total Net Adjust Amount')!=0}
        <tr>
            <td colspan="2">{t}Adjusts{/t}</td>
            <td>{$purchase_order->get('Total Net Adjust Amount')}</td>
        </tr>
    {/if}
    <tr class="total_net">
        <td colspan="2">{t}Total Net{/t}</td>
        <td>{$purchase_order->get('Total Net Amount')}</td>
    </tr>
    {foreach from=$tax_data item=tax }
        <tr>
            <td class="totals" colspan="2">{$tax.name}</td>
            <td class="totals">{$tax.amount}</td>
        </tr>
    {/foreach}
    <tr class="total">
        <td colspan="2"><b>{t}Total{/t}</b></td>
        <td>{$purchase_order->get('Total Amount')}</td>
    </tr>

    </tbody>
</table>
<br>

<br>
<div>
    {include file="string:{$purchase_order->get('Terms and Conditions')}" }
</div>
<br>
</body>
</html>
