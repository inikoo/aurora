<html>
<head>
    <style>
        {literal}
        body {
            font-family: sans-serif;
            font-size: 8pt;
        }

        p {
            margin: 0pt;
        }

        td {
            vertical-align: top;
        }

        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
            border-bottom: 0.1mm solid #b0b0b0;
        }


        table thead td {
            background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }

        .items tr.last td {

            border-bottom: 0.1mm solid #000000;
        }

        .items tr.even td {

            background-color: #FAFAFA;
        }

        .items tr.multiple_partsx td {
            border-top: 0.5mm solid #000000;
            border-bottom: 0.5mm solid #000000;

        }

        .items td.multiple_parts {
            background-color: #FCFCFC;

            border: 0.5mm solid #000000;
        }

        .items td.blanktotal {
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }

        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }

        div.inline {
            float: left;
        }

        div.clearBoth {
            clear: both;
        }


        hr {
            border-top: 0.1mm solid #000000;
            height: 1px;

        }

        #order_pick_aid_data {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        #order_pick_aid_data tr {
            border-bottom: 0.1mm solid #000000
        }

        #order_pick_aid_data td {
            padding-bottom: 4px;
            padding-top: 5px
        }

        #order_pick_aid_data td.label {
            border-bottom: 0.1mm solid #000000
        }

        #order_pick_aid_data td.to_fill {
            border-bottom: 0.1mm solid #000000;
        }

        .hide {
            display: none
        }

        .address_label {
            font-size: 7pt;
            color: #555555;
            font-family: sans-serif;
        }

        .address_value {
            font-size: 12px;
        }

        {/literal}
    </style>

</head>
<body>


<htmlpageheader name="myheader">
    <table width="100%">
        <tr>
            <td width="50%" style="color:#000;font-size: 7.pt;">
                <div style=";">{t}Order Pick Aid{/t} <b>{$delivery_note->get('Delivery Note ID')}</b> (C{"%05d"|sprintf:$delivery_note->get('Delivery Note Customer Key')}) {$delivery_note->get('Delivery Note Customer Name')|strip_tags|escape}</div>

            </td>


            <td width="50%" style="text-align: right;">
                {if $delivery_note->get('Delivery Note Order Date Placed')}
                    <div style="text-align: right;font-size: 7.pt;">{t}Order date{/t}: {$delivery_note->get('Order Datetime Placed')}</div>
                {/if}

            </td>
        </tr>
    </table>

</htmlpageheader>


<htmlpagefooter name="myfooter">

    <div style="float:left;width: 50%"><small style="font-size: 7pt;">{t}Created{/t}: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S %Z'}</small></div>
    <div style="float:right;text-align: right;"><small >{t}Delivery note date{/t}: {$delivery_note->get('Creation Date')}</small></div>
    <div style="clear:both;border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
        {t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}
    </div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1"/>
<sethtmlpagefooter name="myfooter" value="on"/>

<div style="height: 45mm;margin-bottom: 2mm">
    <table width="100%" style="font-family: sans-serif;height: 45mm" cellpadding="0">
        <tr>
            <td style="width:78mm;height:45mm;;margin:10mm;padding:15px 10px">


                <div class="address_value">{$delivery_note->get('Delivery Note Address Postal Label')|nl2br}</div>
</div>
</td>
<td style="width:24mm;height:45mm">&nbsp;</td>
<td style="width:78mm;height:45mm;font-size:9pt;padding:15px 10px 15px 0px">
    <table>
        <tr>

            <td style="font-size: 7.pt;">
                <div style="margin-left: 30px;padding-left: 30px">
                <div style="margin-top: 10px;padding-top: 10px">
                    <b>{t}Dispatched by{/t}:</b>
                </div>
                {if $store->get('Store Type')=='Dropsshiping'}

                <div style="margin-bottom: 20px;margin-top:10px">{$delivery_note->get('Delivery Note Customer Name')}</div>
                <div>
                    {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                        <div class=" {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                            <span class="address_label">{t}Mobile{/t}</span> <span class="address_value">{$customer->get('Main XHTML Mobile')}</span>
                        </div>
                        <div class=" {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                            <span class="address_label">{t}Phone{/t}</span> <span class="address_value">{$customer->get('Main XHTML Telephone')}</span>
                        </div>
                    {else}
                        <div class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                            <span style="font-size: 7.pt;" class="address_label">{t}Phone{/t}</span> <span style="font-size: 7.pt;" class="address_value">{$customer->get('Main XHTML Telephone')}</span>
                        </div>
                        <div class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                            <span style="font-size: 7.pt;" class="address_label">{t}Mobile{/t}</span> <span style="font-size: 7.pt;" class="address_value">{$customer->get('Main XHTML Mobile')}</span>
                        </div>
                    {/if}

                </div>


                <div class="data_field small {if $customer->get('Customer Main Plain Email')==''}hide{/if}" style="margin-top:5px">

                    <span style="font-size: 7.pt;" class="address_label">{t}Email{/t}</span> <span style="font-size: 7.pt;" class="address_value">{$customer->get('Customer Main Plain Email')}</span>

                </div>


                {else}
                    {$store->get('Store Name')}
                {/if}
                <br><br>
                <div style="margin-top: 10px;padding-top: 10px">
                    <b>{t}If undelivered return to{/t}:</b>
                </div>
                <div style="margin-top: 10px;">
                    {$store->get('Store Address')}
                </div>
                </div>
                <br><br>
                <barcode  code="AU_{$account->get('Code')}_{$delivery_note->id}" type="C128B" size=".75" />


            </td>
        </tr>
    </table>

</td>

</tr>
</table>
</div>


<div style="float:left;height:140px;border:0.2mm  solid #000;margin-bottom:20px;padding:10px;width: 98.5mm;">
    {assign expected_payment $order->get('Expected Payment')}
    {if $expected_payment!=''}
        <div style="font-size: 7pt;font-family: sans-serif;">{$expected_payment}</div>{/if}
    <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Notes{/t}:</span>

    {if $urgent or $fragile}
        <div style="font-size: x-large">
            {if $urgent}
                <b>{t}Priority dispatch{/t}</b>
            {/if}
            {if $fragile}
                <b>{if $urgent}-{/if} {t}Pack with extra care{/t}</b>
            {/if}
        </div>
    {/if}

    {if $order->get('Order Delivery Sticky Note')!=''}<br>{$order->get('Order Delivery Sticky Note')|nl2br}<br>{/if}
    <br> {$delivery_note->get('Delivery Note Warehouse Note')|nl2br}<br>
</div>

<div style="float:left;height:140px;border:0.2mm  solid #000;border-left:none;margin-bottom:20px;padding:10px;width: 70mm;">
    <table id="order_pick_aid_data" cellspacing="0" cellpadding="0">
        <tr>
            <td class="label">{t}Picker{/t}:</td>
            <td class="to_fill"></td>
        </tr>
        <tr>
            <td class="label">{t}Packer{/t}:</td>
            <td class="to_fill"></td>
        </tr>
        <tr>
            <td class="label">{t}Weight{/t}:</td>
            <td class="to_fill">{$delivery_note->get('Weight')}</td>
        </tr>
        <tr>
            <td class="label">{t}Parcels{/t}:</td>
            <td class="to_fill"></td>
        </tr>
        <tr>
            <td class="label">{t}Courier{/t}:</td>
            <td class="to_fill"></td>
        </tr>
        <tr>
            <td class="label">{t}Consignment{/t}:</td>
            <td class="to_fill"></td>
        </tr>
    </table>
</div>


<div style=" clear:both;font-size: 9pt;margin-bottom:2pt">{$formatted_number_of_items}, {$formatted_number_of_picks}</div>

<table class="items" width="100%" style="font-size: 7pt; border-collapse: collapse;" cellpadding="8">
    <thead>
    <tr>
        <td align="left" width="14%">{t}Location{/t}</td>
        <td align="center" width="14%">{t}Reference{/t}</td>
        <td align="left" width="14%">{t}Alt Locations{/t}</td>
        <td align="left">{t}SKO description{/t}</td>
        <td align="center" width="7%">SKOs</td>
        <td align="left" width="16%">{t}Notes{/t}</td>
    </tr>
    </thead>
    <tbody>
    {foreach from=$transactions item=transaction name=products}
        <tr class="{if $smarty.foreach.products.last}last{/if} {if $smarty.foreach.products.iteration is even} even{/if} ">
            <td style="padding: 0px">
                <table style="width:100%; border-spacing:0; border-collapse:collapse;">
                    <tr>

                        <td style="padding-left:10px;border:none;padding-top:8px"><b>{$transaction.location}</b></td>
                        <td style="border:none;font-style: italic;padding-top:8px;text-align:right;padding-right: 10px"><span>{$transaction.stock_in_picking}</span></td>
                    </tr>
                </table>
            </td>
            <td align="center">{$transaction.reference}</td>

            <td align="left" style="padding: 0px">
                <table style="width:100%; border-spacing:0; border-collapse:collapse;">
                    {foreach from=$transaction.locations item=locations name=locations}
                        <tr>

                            <td style="padding-left:10px;border:none;{if $smarty.foreach.locations.first}padding-top:8px;{else}border-top:.1mm solid #b0b0b0{/if}">{if $locations[2]=='Yes' }
                                    <b>{$locations[1]}</b>{else}{$locations[1]}{/if}</td>
                            <td style="border:none;font-style: italic;{if $smarty.foreach.locations.first}padding-top:8px;{else}border-top:.1mm solid #b0b0b0;{/if}text-align:right;padding-right: 10px">{$locations[3]}</td>
                        </tr>
                    {/foreach}
                </table>
            </td>
            <td align="left">{$transaction.description}</td>
            <td align="center">{$transaction.qty}</td>
            <td align="left" style="font-size: 6pt;">
                {if $transaction.un_number>1}<span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;">&nbsp;{$transaction.un_number|strip_tags}&nbsp;</span> {/if}
                {if $transaction.part_packing_group!='None'}PG<b>{$transaction.part_packing_group}</b>{/if}

                {$transaction.notes}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

</body>
</html>
