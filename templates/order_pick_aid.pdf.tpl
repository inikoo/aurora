<html>
<head>
	<style>
		{literal}
		body {font-family: sans-serif;
			font-size: 8pt;
		}
		p {    margin: 0pt;
		}
		td { vertical-align: top; }
		.items td {
			border-left: 0.1mm solid #000000;
			border-right: 0.1mm solid #000000;
			border-bottom: 0.1mm solid #b0b0b0;
		}


		table thead td { background-color: #EEEEEE;
			text-align: center;
			border: 0.1mm solid #000000;
		}

		.items tr.last td {

			border-bottom: 0.1mm solid #000000;
		}

		.items tr.even  td {

			background-color: #FAFAFA;
		}

		.items tr.multiple_partsx td{
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

		div.inline { float:left; }

		div.clearBoth { clear:both; }


		hr {
			border-top: 0.1mm solid #000000;
			height:1px;

		}
		#order_pick_aid_data {width:100%; border-spacing:0; border-collapse:collapse;}
		#order_pick_aid_data tr{border-bottom: 0.1mm solid #000000}
		#order_pick_aid_data td{padding-bottom:4px;padding-top:5px}
		#order_pick_aid_data td.label{border-bottom: 0.1mm solid #000000}
		#order_pick_aid_data td.to_fill{border-bottom: 0.1mm solid #000000;}

		.hide{display: none}

		.address_label{font-size: 7pt; color: #555555; font-family: sans-serif;}

		.address_value{font-size:12px;}

		{/literal}
	</style>

</head>
<body>


<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="50%" style="color:#000;"><span style="font-weight: bold; font-size: 14pt;">{t}Order Pick Aid{/t} {$delivery_note->get('Delivery Note ID')}</span><br />


(C{"%05d"|sprintf:$delivery_note->get('Delivery Note Customer Key')})  {$delivery_note->get('Delivery Note Customer Name')|strip_tags|escape}<br />
</td>



<td width="50%" style="text-align: right;">
{if $delivery_note->get('Delivery Note Order Date Placed')}
<div style="text-align: right">{t}Order date{/t}: {$delivery_note->get('Order Datetime Placed')}</div>
{/if}
<div style="text-align: right">{t}Delivery note date{/t}: {$delivery_note->get('Creation Date')}</div>
</td>
</tr></table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
	<small style="font-size: 7pt;">{t}Created{/t}: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S %Z'}</small>
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}
	</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />

<table width="100%" style="font-family: sans-serif;" cellpadding="10">
	<tr>
		<td width="45%" style="border: 0.1mm solid #888888;">
			<div >
				{if $customer->get('Customer Preferred Contact Number')=='Mobile'}
					<div  class=" {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
						<span class="address_label">{t}Mobile{/t}</span> <span class="address_value"  >{$customer->get('Main XHTML Mobile')}</span>
					</div>
					<div class=" {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
						<span class="address_label">{t}Phone{/t}</span>  <span class="address_value">{$customer->get('Main XHTML Telephone')}</span>
					</div>
				{else}


					<div class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
						<span class="address_label">{t}Phone{/t}</span> <span class="address_value">{$customer->get('Main XHTML Telephone')}</span>
					</div class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
					<div class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
						<span class="address_label">{t}Mobile{/t}</span> <span class="address_value" >{$customer->get('Main XHTML Mobile')}</span>
					</div>
				{/if}

			</div>


			<div class="data_field small {if $customer->get('Customer Main Plain Email')==''}hide{/if}" style="margin-top:5px">

				<span class="address_label">{t}Email{/t}</span>  <span class="address_value">{$customer->get('Customer Main Plain Email')}</span>

			</div>

			<div style="height: 5px;border:0px solid red;font-size: 5px">&nbsp;</div>

			{if $delivery_note->get('Delivery Note Dispatch Method')=='Collection'}
				<div style="margin-top: 10px;padding-top: 10px;">
					<span class="address_label">{t}For collection{/t}:</span><br />
				</div>
				<div class="address_value"></div>
			{else}

			<div style="margin-top: 10px;padding-top: 10px;">
			<span class="address_label">{t}Delivery Address{/t}:</span><br />
			</div>
			<div class="address_value">{$delivery_note->get('Delivery Note Address Postal Label')|nl2br}</div>
			{/if}
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="border: 0.1mm solid #888888;font-size:9pt">
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
	</tr>
</table>
<br>

<div style="float:left;height:150px;border:0.2mm  solid #000;margin-bottom:20px;padding:10px;width: 143.5mm;">
	{assign expected_payment $order->get('Expected Payment')}
	{if $expected_payment!=''}<div style="font-size: 7pt;font-family: sans-serif;">{$expected_payment}</div>{/if}
	<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Notes{/t}:</span>

	{if $urgent or $fragile or $use_tracking}
		<div style="font-size: x-large">
			{if $urgent}
				<b>{t}Priority dispatch{/t}</b>
			{/if}
			{if $fragile}
				<b>{if $urgent}-{/if} {t}Pack with extra care{/t}</b>
			{/if}
			{if $use_tracking}
				<b>{if $urgent or $fragile}-{/if} {t}Use full tracking{/t}</b>
			{/if}
		</div>
	{/if}

	{if $dangerous_goods|@count gt 0}
	<div style="padding-top: 1mm">
	{foreach from=$dangerous_goods item=dangerous_good name=dangerous_goods}
		{if $dangerous_good.un_number>1}<span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;">&nbsp;{$dangerous_good.un_number|strip_tags}&nbsp;</span> {/if}
		{if $dangerous_good.part_packing_group!='None'}PG <b>{$dangerous_good.part_packing_group}</b> {/if}

		({$dangerous_good.parts}) {if $smarty.foreach.dangerous_goods.last}{else},{/if}

	{/foreach}
	</div>
	{/if}
	{if $order->get('Order Delivery Sticky Note')!=''}<br> {$order->get('Order Delivery Sticky Note')|nl2br}<br>{/if}
	<br> {$delivery_note->get('Delivery Note Warehouse Note')|nl2br}<br>
</div>
<barcode style="float:left;margin-left: 20px;border:0px solid #ccc" code="{$qr_data}" type="QR" />



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
							<td style="border:none;font-style: italic;padding-top:8px;text-align:right;padding-right: 10px"><span >{$transaction.stock_in_picking}</span></td>
						</tr>
				</table>
			</td>
			<td align="center">{$transaction.reference}</td>

			<td align="left" style="padding: 0px">
				<table style="width:100%; border-spacing:0; border-collapse:collapse;">
					{foreach from=$transaction.locations item=locations name=locations}
						<tr>

							<td style="padding-left:10px;border:none;{if $smarty.foreach.locations.first}padding-top:8px;{else}border-top:.1mm solid #b0b0b0{/if}">{if $locations[2]=='Yes' }<b>{$locations[1]}</b>{else}{$locations[1]}{/if}</td>
							<td style="border:none;font-style: italic;{if $smarty.foreach.locations.first}padding-top:8px;{else}border-top:.1mm solid #b0b0b0;{/if}text-align:right;padding-right: 10px">{$locations[3]}</td>
						</tr>
					{/foreach}
				</table>
			</td>
			<td align="left">{$transaction.description}</td>
			<td align="center" style="font-size:10pt" >{$transaction.qty}</td>
			<td align="left" style="font-size: 6pt;">
				{if $transaction.un_number>1}<span style="background-color:#f6972a;border:.5px solid #231e23;color:#231e23;">&nbsp;{$transaction.un_number|strip_tags}&nbsp;</span> {/if}
				{if $transaction.part_packing_group!='None'}PG <b>{$transaction.part_packing_group}</b> {/if}

				{$transaction.notes}
			</td>
		</tr>
	{/foreach}
	</tbody>
</table>
<br>
</body>
</html>
