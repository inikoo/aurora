{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-07-2019 12:21:00 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}
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
            font-size: 8pt;
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
        }%e %b %Y %H:

        .error {
            color: tomato;
        }

    </style>
</head>
<body>
<htmlpageheader name="myheader">
    <table width="100%" style="font-size: 9pt;" border="0">

        <tr>

            <td style="width:250px;">
                <span style="font-weight: bold; font-size: 12pt;">{$title}</span>




            </td>
            <td style="text-align: right;">

                {if $purchase_order->get('Purchase Order State')=='Cancelled' }
                    <div>
                        {t}Cancelled{/t}: <b>{$purchase_order->get('Cancelled Date')}</b>
                    </div>
                {elseif $purchase_order->get('Purchase Order State')=='InProcess' }
                    <div>
                        <b class="error">{t}Preview only, not submitted yet!{/t}</b>
                    </div>
                    <div>
                        {t}Date{/t}: <b>{$smarty.now|date_format:"%e %b %Y"}</b>
                    </div>
                {else}
                    </div>
                    {t}Submitted{/t}:
                    <b>{$purchase_order->get('Submitted Formatted Date')}</b>
                    </div>
                    {if $purchase_order->get('Estimated Production Date')!=''}
                        </div>
                        {t}Expected dispatch{/t}:
                        <b>{$purchase_order->get('Estimated Production Date')}</b>
                        </div>
                    {/if}



                {/if}




            </td>

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
                {$purchase_order->get('Warehouse Company Name')}
            </td>
            <td width="33%"
                style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
            <td width="34%" style="text-align: right;">
                <small>
                    {if $purchase_order->get('Purchase Order Main Buyer Name')!=''}
                    {t}Submitted by{/t} {$purchase_order->get('Purchase Order Main Buyer Name')}
                        <br>
                    {/if}

                </small>
            </td>

        </tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1"/>
<sethtmlpagefooter name="myfooter" value="on"/>




<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
    <thead>
    <tr>
        <td style="width:12%;text-align:left">{t}Code{/t}</td>
        <td style="text-align:left">{t}Unit description{/t}</td>
        <td style="width:8%;text-align:right">{t}Units{/t}</td>
        <td style="width:7%;text-align:right">{t}SKOs{/t}</td>
        <td style="width:10%;">{t}Worker{/t}</td>
        <td style="width:10%;text-align:center">W</td>

        <td style="width:5%;text-align:right">{t}QC{/t}</td>

    </tr>
    </thead>
    <tbody>
    {foreach from=$transactions item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if}">
            <td style="text-align:left">{$transaction.reference}</td>
            <td style="text-align:left">{$transaction.description}</td>
            <td style="text-align:right">{$transaction.units}</td>
            <td style="text-align:right">{$transaction.skos}</td>
            <td style="text-align:right"></td>
            <td style="text-align:right"></td>
            <td style="text-align:right"></td>
        </tr>
    {/foreach}
    </tbody>


</table>


<br><br>
</body>
</html>
