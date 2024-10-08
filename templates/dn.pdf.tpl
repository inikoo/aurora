{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2017 at 01:31:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<html>
<head>
<style>
{literal}


body {font-family: sans-serif;
    font-size: 10pt;
}
p {    margin: 0pt;
}
h1 {font-size:14pt}

td { vertical-align: top;}
.items td {
    border-left: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
    border-bottom: 0.1mm solid #000000;
     padding-bottom:4px;padding-top:5px;
     font-size:8pt
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

table thead td { background-color: #EEEEEE;
    text-align: center;
    border: 0.1mm solid #000000;
}
.items td.blanktotal {
    background-color: #FFFFFF;
    border: 0mm none #000000;
    border-top: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
}

div.inline { float:left; }
.clearBoth { clear:both; }
{/literal}</style> 
</head>
<body>
<htmlpageheader name="myheader"> 
<table width="100%" style="font-size: 9pt;" >
	<tr>
		<tr>
			{if file_exists("art/invoice_logo.{$store->get('Store Code')}.jpg")}
			<td style="width:150px;"><img style="width:150px" src="art/invoice_logo.{$store->get('Store Code')}.jpg" title="" alt=""></td>
			{/if} 
			<td style="width:250px;padding-left:10px;">{$delivery_note->properties('store_name')}
			<div style="font-size:7pt">
				{$delivery_note->properties('store_address')|nl2br}
			</div>
			<div style="font-size:7pt">
				{$delivery_note->properties('store_url')}
			</div>
			</td>
			<td style="text-align: right;">{t}Delivery Note No.{/t}<br />
			<span style="font-weight: bold; font-size: 12pt;">{$delivery_note->get('Delivery Note ID')}</span></td>
		</tr>
	</table>
	</htmlpageheader> <htmlpagefooter name="myfooter"> 
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	</div>
	<table width="100%">
		<tr>
			<tr>
				<td width="33%" style="color:#000;text-align: left;"> <small> {$delivery_note->properties('store_company_name')}<br> {if $delivery_note->properties('store_vat_number')!=''}{t}VAT Number{/t}: {$delivery_note->properties('store_vat_number')}<br>{/if} {if $delivery_note->properties('store_company_number')!=''}{t}Registration number{/t}: {$delivery_note->properties('store_company_number')}{/if} </small></td>
				<td width="33%" style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
				<td width="34%" style="text-align: right;"> <small> {if $delivery_note->properties('store_telephone')!=''}{$delivery_note->properties('store_telephone')}<br>{/if} {if $delivery_note->properties('store_email')!=''}{$delivery_note->properties('store_email')}{/if} </small></td>
			</tr>
		</table>
	</div>
	</htmlpagefooter> <sethtmlpageheader name="myheader" value="on" show-this-page="1" /><sethtmlpagefooter name="myfooter" value="on" /> 
	<table width="100%">
		<tr>
			<td> 
			<h1>
				{t}Delivery Note{/t} 
			</h1>
			</td>
			<td style="text-align: right"> 
			<div>
				{t}Dispatch Date{/t}:<b>{$delivery_note->get('Date')}</b>
			</div>
			</td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="0">
		<tr>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;"> 
			<div style="text-align: right">
				{t}Customer{/t}:<b> {$delivery_note->get('Delivery Note Customer Name')|strip_tags|escape}</b> ({"%05d"|sprintf:$delivery_note->get('Delivery Note Customer Key')})
			</div>
			</td>

		</tr>
	</table>




	<table width="100%" style="font-family: sans-serif;" cellpadding="10">
		<tr>
			<td width="40%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivery address{/t}:</span>
			<div>
				{$delivery_note->get('Delivery Note Address Postal Label')|nl2br}
			</div>
			</td>
			<td width="5%">&nbsp;</td>
			<td  style="text-align: right">

				{assign expected_payment $order->get('Expected Payment')}
				{if $expected_payment!=''}
					<div><b>{$expected_payment}</b></div>
				{/if}

			<div style="text-align: right; {if $delivery_note->get_formatted_parcels()==''}display:none{/if}">
				{t}Parcels{/t}:<b> {$delivery_note->get_formatted_parcels()}</b>
			</div>
			<div style="text-align: right">
				{t}Weight{/t}: <b>{$delivery_note->get('Weight')}</b>
			</div>
			<div style="text-align: right;{if $consignment==''}display:none{/if}">
				{t}Courier{/t}: <b> <span id="formatted_consignment">{$consignment|strip_tags}</span></span> </b>
			</div>


				{if $dangerous_goods|@count gt 0}
					<div style="font-size: x-small"><br>
						{foreach from=$dangerous_goods item=dangerous_good name=dangerous_goods}
							{if $dangerous_good.un_number>1}<span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;">&nbsp;{$dangerous_good.un_number|strip_tags}&nbsp;</span> {/if}
							{if $dangerous_good.part_packing_group!='None'}PG <b>{$dangerous_good.part_packing_group}</b> {/if}

							({$dangerous_good.parts}) {if $smarty.foreach.dangerous_goods.last}{else},{/if}

						{/foreach}
					</div>
				{/if}

			</td>




		</tr>
	</table>
	<br> 
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
			<tr>
				<td style="width:15%;text-align:left">{t}Code{/t}</td>
				<td style="text-align:left">{t}Description{/t}</td>
				<td style="width:15%;text-align:right">{t}Required{/t}</td>
				<td style="width:15%;text-align:right">{t}Dispatched{/t}</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$transactions item=transaction name=products} 
			<tr class="{if $smarty.foreach.products.last}last{/if}">
				<td style="width:15%;text-align:left">{$transaction['Part Reference']}
					{if $transaction['Part SKO Barcode']!='' and false}
						<barcode code="{$transaction['Part SKO Barcode']}" type="C128B" class="barcode" />
					{/if}
				</td>
				<td style="text-align:left">{$transaction['Part Package Description']}<br/><small>{t}From product{/t}: <b>{$transaction['Product Code']}</b> {$transaction['Product Description']}  ({t}Ordered{/t}:{$transaction['Ordered']})</small>

					{if $transaction.un_number>1 or $transaction.part_packing_group!='None'}
					{if $transaction.un_number>1}<span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;">&nbsp;{$transaction.un_number|strip_tags}&nbsp;</span> {/if}
					{if $transaction.part_packing_group!='None'}PG <b>{$transaction.part_packing_group}</b> {/if}
					{/if}

				</td>

				<td style="width:15%;text-align:right">{$transaction['Required']}</td>
				<td style="width:15%;text-align:right"><b>{$transaction['dispatched']}</b></td>
			</tr>
			{/foreach} 
		</tbody>
	</table>
	<br> <br> 
	</body>
	</html>
