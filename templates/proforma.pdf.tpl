{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 November 2017 at 21:05:39 GMT+8, Seminjak, Bali, Indonesia
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
    border-bottom: 0.1mm solid #cfcfcf;
     padding-bottom:4px;padding-top:5px;
}



.items tbody.out_of_stock td {
color:#777;font-style:italic
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

table thead td,table tr.title td{ background-color: #EEEEEE;
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
<table width="100%" style="font-size: 9pt;" border="0">
	<tr>
		<tr>
			{*} {if file_exists("art/invoice_logo.{$store->get('Store Code')}.jpg")} 
			<td style="width:150px;"><img style="width:150px" src="art/invoice_logo.{$store->get('Store Code')}.jpg" border="0" title="" alt=""></td>
			{/if} {*} 
			<td style="width:250px;padding-left:10px;">{$store->get('Store Name')} 
			<div style="font-size:7pt">
				{$store->get('Store Address')|nl2br} 
			</div>
			<div style="font-size:7pt">
				{$store->get('Store URL')} 
			</div>
			</td>
			<td style="text-align: right;">{$label_title_no}<br />
			<span style="font-weight: bold; font-size: 12pt;">{$order->get('Order Public ID')}</span></td>
		</tr>
	</table>
	</htmlpageheader> <htmlpagefooter name="myfooter"> 
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	</div>
	<table width="100%">
		<tr>
			<tr>
				<td width="33%" style="color:#000;text-align: left;"> <small> {$store->get('Store Company Name')}<br> {if $store->get('Store VAT Number')!=''}{t}VAT Number{/t}: <b>{$store->get('Store VAT Number')}</b><br>{/if} {if $store->get('Store Company Number')!=''}{t}Registration Number{/t}: {$store->get('Store Company Number')}{/if} </small></td>
				<td width="33%" style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
				<td width="34%" style="text-align: right;"> <small> {if $store->get('Store Telephone')!=''}{$store->get('Store Telephone')}<br>{/if} {if $store->get('Store Email')!=''}{$store->get('Store Email')}{/if} </small></td>
			</tr>
		</table>
	</div>
	</htmlpagefooter> <sethtmlpageheader name="myheader" value="on" show-this-page="1" /><sethtmlpagefooter name="myfooter" value="on" /> 
	<table width="100%">
		<tr>
			<td> 
			<h1>
				{$label_title} 
			</h1>
			</td>
			<td style="text-align: right"> 
			<div>
				{t}Proforma date{/t}: <b>{$order->get_date('Order Date')}</b>
			</div>


		 </td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="0">
		<tr>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;"> 

			<div style="text-align: right">
				{t}Customer{/t}: <b>{$order->get('Order Customer Name')}</b> ({$order->get('Order Customer Key')}) 
			</div>
			<div style="{if $order->get('Order Tax Number')==''}display:none{/if}">
                    {t}Tax Number{/t}: <b>{$order->get('Order Tax Number')}</b>
			</div>

				<div style="{if $order->get('Order Registration Number')==''}display:none{/if}">
                    {t}Registration Number{/t}: <b>{$order->get('Order Registration Number')}</b>
				</div>


			</td>

		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="10">
		<tr>
			<td width="45%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Billing address{/t}:</span> 
			<div>
				{$order->get('Invoice Address Formatted')}
			</div>
			</td>
			<td width="10%">&nbsp;</td>
			<td width="45%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivery address{/t}:</span>
			<div>
				{$order->get('Order Delivery Address Formatted')}
			</div>
			 </td>
		</tr>
	</table>
	<br> 
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
			<tr>
				<td style="width:8%;text-align:left">{t}Code{/t}</td>
				<td style="text-align:left">{t}Description{/t}</td>
				<td style="width:8%;text-align:right">{if $order->get('Order Type')=='Order'}{t}Discount{/t}{/if}</td>
				<td style="width:8%;text-align:right">{t}Quantity{/t}</td>
				<td style="width:10%;text-align:right">{t}Amount{/t}</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$transactions item=transaction name=products} 
			<tr class="{if $smarty.foreach.products.last}last{/if}">
				<td style="width:8%;text-align:left">{$transaction['Product Code']}</td>
				<td style="text-align:left">{$transaction['Product Description']}</td>
				<td style="width:8%;text-align:right">{$transaction['Discount']}</td>
				
				<td style="width:8%;text-align:right">{$transaction['Order Quantity']}</td>
				<td style="width:10%;text-align:right">{$transaction['Amount']}</td>
			</tr>
			{/foreach} 
		</tbody>
		{if $number_transactions_out_of_stock>0} 
		<tr class="title">
			<td colspan="5">{t}Ordered products not dispatched{/t}</td>
		</tr>
		{/if} 

		<tbody class="totals">
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Items Net{/t}</td>
				<td>{$order->get('Items Net Amount')}</td>
			</tr>
			{if $order->get('Order Net Amount Off')!=0 } 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Amount Off{/t}</td>
				<td>{$order->get('Net Amount Off')}</td>
			</tr>
			{/if}
			
			{if $order->get('Order Refund Net Amount')!=0 } 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Refunds{/t}</td>
				<td>{$order->get('Refund Net Amount')}</td>
			</tr>
			{/if} 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Shipping{/t}</td>
				<td>{$order->get('Shipping Net Amount')}</td>
			</tr>
			{if $order->get('Order Charges Net Amount')!=0} 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Charges{/t}</td>
				<td>{$order->get('Charges Net Amount')}</td>
			</tr>
			{/if} {if $order->get('Order Insurance Net Amount')!=0} 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Insurance{/t}</td>
				<td>{$order->get('Insurance Net Amount')}</td>
			</tr>
			{/if} {if $order->get('Order Total Net Adjust Amount')!=0} 
			<tr>
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Adjusts{/t}</td>
				<td>{$order->get('Total Net Adjust Amount')}</td>
			</tr>
			{/if} 
			<tr class="total_net">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Total Net{/t}</td>
				<td>{$order->get('Total Net Amount')}</td>
			</tr>
			<tr class="total_tsx">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Tax{/t}</td>
				<td>{$order->get('Total Tax Amount')}</td>
			</tr>


			<tr class="total">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2"><b>{t}Total{/t}</b></td>
				<td>{$order->get('Total Amount')}</td>
			</tr>


			<tr class="">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2"><b>{t}Paid{/t}</b></td>
				<td>{$order->get('Payments Amount')}</td>
			</tr>



			{if $order->get('Order Available Credit Amount')!=0}
			<tr class="">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2">{t}Credits{/t}</td>
				<td>{$order->get('Available Credit Amount')}</td>
			</tr>

			{/if}

			<tr class="total">
				<td style="border:none" colspan="2" ></td>
				<td colspan="2"><b>{t}To pay{/t}</b></td>
				<td>{$order->get('Basket To Pay Amount')}</td>
			</tr>

		</tbody>
	</table>
	<br>

	<div style="text-align: center; font-style: italic;">
		{include file="string:{$store->get('Store Proforma Message')}" }
	</div>
	<br>

<div style="text-align: center; font-style: italic;">
    {$extra_comments}
</div>
<br>

</body>
	</html>
