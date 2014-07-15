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
<table width="100%" style="font-size: 9pt;" border="0">
	<tr>
		<tr>
	
		<td style="width:250px;padding-left:10px;">{$store->get('Store Name')} 
			<div style="font-size:7pt">
				{$store->get('Store Address')|nl2br} 
			</div>
			<div style="font-size:7pt">
				{$store->get('Store URL')} 
			</div>
			</td>
			<td style="text-align: right;">{t}Proforma Invoice No.{/t}<br />
			<span style="font-weight: bold; font-size: 12pt;">{$order->get('Order Public ID')}</span></td>
		</tr>
	</table>
	</htmlpageheader> <htmlpagefooter name="myfooter"> 
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	</div>
	<table width="100%">
		<tr>
			<tr>
				<td width="33%" style="color:#000;text-align: left;"> <small> {$store->get('Store Company Name')}<br> {if $store->get('Store VAT Number')!=''}{t}VAT Number{/t}: {$store->get('Store VAT Number')}<br>{/if} {if $store->get('Store Company Number')!=''}{t}Registration Number{/t}: {$store->get('Store Company Number')}{/if} </small></td>
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
				{t}Proforma Invoice{/t}
			</h1>
			</td>
			<td style="text-align: right"> 
			<div>
				{t}Date{/t}:<b>{$order->get_date('Order Date')}</b> 
			</div>
			 </td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="0">
		<tr>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;"> 
			<div style="text-align: right">
				{t}Payment State{/t}:<b> {$order->get_formated_payment_state()}</b>
			</div>
			<div style="text-align: right">
				{t}Customer{/t}:<b> {$order->get('Order Customer Name')}</b> ({$order->get('Order Customer Key')})
			</div>
			</td>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;text-align: right"> 
			<div style="text-align: right">
				{t}Weight{/t}:<b> {$order->get('Weight')}</b>
			</div>
			 </td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="10">
		<tr>
			<td width="45%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Billing to{/t}:</span> 
			<div>
				{$order->get('Order XHTML Billing Tos')}
			</div>
			</td>
			<td width="10%">&nbsp;</td>
			<td width="45%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivering to{/t}:</span> 
			<div>
				{$order->get('Order XHTML Ship Tos')}
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
				<td style="width:8%;text-align:right">{t}Discount{/t}</td>
				<td style="width:8%;text-align:right">{t}Quantity{/t}</td>
				<td style="width:10%;text-align:right">{t}Amount{/t}</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$transactions item=transaction name=products} 
			<tr class="{if $smarty.foreach.products.last}last{/if}">
				<td style="width:8%;text-align:left">{$transaction['Product Code']}</td>
				<td style="text-align:left">{$transaction['Product XHTML Short Description']}</td>
				<td style="width:8%;text-align:right">{$transaction['Discount']}</td>
				<td style="width:8%;text-align:right">{$transaction['Order Quantity']}</td>
				<td style="width:10%;text-align:right">{$transaction['Amount']}</td>
			</tr>
			{/foreach} 
		</tbody>
		<tbody class="totals">
			<tr>
				<td style="border:none" colspan="2" rowspan="10"></td>
				<td colspan="2">{t}Items Net{/t}</td>
				<td>{$order->get('Items Net Amount')}</td>
			</tr>
			{if $order->get('Order Refund Net Amount')!=0 } 
			<tr>
				<td colspan="2">{t}Refunds{/t}</td>
				<td>{$order->get('Refund Net Amount')}</td>
			</tr>
			{/if} 
			
			{if $order->get('Order Insurance Net Amount')!=0} 
			<tr>
				<td colspan="2">{t}Insurance{/t}</td>
				<td>{$order->get('Insurance Net Amount')}</td>
			</tr>
			{/if} 
			
			<tr>
				<td colspan="2">{t}Shipping{/t}</td>
				<td>{$order->get('Shipping Net Amount')}</td>
			</tr>
			{if $order->get('Order Charges Net Amount')!=0} 
			<tr>
				<td colspan="2">{t}Charges{/t}</td>
				<td>{$order->get('Charges Net Amount')}</td>
			</tr>
			{/if} {if $order->get('Order Total Net Adjust Amount')!=0} 
			<tr>
				<td colspan="2">{t}Adjusts{/t}</td>
				<td>{$order->get('Total Net Adjust Amount')}</td>
			</tr>
			{/if} 
			<tr class="total_net">
				<td colspan="2">{t}Total Net{/t}</td>
				<td>{$order->get('Total Net Amount')}</td>
			</tr>
			{foreach from=$tax_data item=tax } 
			<tr>
				<td class="totals" colspan="2">{$tax.name}</td>
				<td class="totals">{$tax.amount}</td>
			</tr>
			{/foreach} 
			<tr class="total">
				<td colspan="2"><b>{t}Total{/t}</b></td>
				<td>{$order->get('Total Amount')}</td>
			</tr>
			{if $order->get('Order To Pay Amount')!=0}
			<tr class="total">
				<td colspan="2"><b>{t}Paid{/t}</b></td>
				<td>{$order->get('Payments Amount')}</td>
			</tr>
			
			<tr class="total">
				<td colspan="2"><b>{t}To Pay{/t}</b></td>
				<td>{$order->get('To Pay Amount')}</td>
			</tr>
			{/if}
			
			
		</tbody>
	</table>
	<br> 
		 <table class="items" width="100%" style="display:none;font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<tr class="title">
		<td colspan=5>{t}Payments{/t}</td>
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
		<tbody >
			{foreach from=$order->get_payment_objects('',true,true) item=payment name=payments} 
			<tr class="{if $smarty.foreach.payments.last}last{/if}">
				<td style="text-align:left">{$payment->get('Method')} ({$payment->payment_service_provider->get('Payment Service Provider Name')})</td>
				<td style="text-align:right">{$payment->get('Created Date')}</td>
				<td style="text-align:left">{$payment->get('Transaction Status')}</td>
								<td style="text-align:left">{if $payment->get('Payment Type')=='Refund'}{$payment->get_parent_info()|strip_tags}{/if}{if $payment->get('Payment Transaction ID')!='' and $payment->get('Payment Type')=='Refund'}, {/if}{$payment->get('Payment Transaction ID')|strip_tags}</td>

				<td style="text-align:right">{$payment->formated_amount}</td>

			</tr>
			{/foreach} 
		</tbody>
	</table>
	
	
	<div style="text-align: center; font-style: italic;">
	{include file="string:{$store->get('Store Invoice Message')}" } 
	
	
	</div>
	<br> 
	</body>
	</html>
