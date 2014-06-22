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
<table width="100%" style="font-size: 9pt;" border="0">
	<tr>
		<tr>
			{if file_exists("art/invoice_logo.{$store->get('Store Code')}.jpg")}<td style="width:150px;"><img style="width:150px" src="art/invoice_logo.{$store->get('Store Code')}.jpg" border="0" title="" alt=""></td>{/if}
			<td style="width:250px;padding-left:10px;">{$store->get('Store Name')} 
			<div style="font-size:7pt">
				{$store->get('Store Address')|nl2br} 
			</div>
			<div style="font-size:7pt">
				{$store->get('Store URL')} 
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
				<td width="33%" style="color:#000;text-align: left;"> <small> {$store->get('Store Company Name')}<br> {if $store->get('Store VAT Number')!=''}{t}VAT Number{/t}: {$store->get('Store VAT Number')}<br>{/if} {if $store->get('Store Company Number')!=''}{t}Registration number{/t}: {$store->get('Store Company Number')}{/if} </small></td>
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
				{t}Delivery Note{/t}
			</h1>
			</td>
			<td style="text-align: right"> 
			<div>
				{t}Dispatch Date{/t}:<b>{$delivery_note->get_date('Delivery Note Date')}</b> 
			</div>
			</td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="0">
		<tr>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;"> 
			
			<div style="text-align: right">
				{t}Customer{/t}:<b> {$delivery_note->get('Delivery Note Customer Name')}</b> ({$delivery_note->get('Delivery Note Customer Key')})
			</div>
			</td>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;text-align: right"> {if $number_dns==1} 
			<div style="text-align: right">
				{t}Parcels{/t}:<b> {$delivery_note->get_formated_parcels()}</b> 
			</div>
			<div style="text-align: right">
				{t}Weight{/t}: <b>{$delivery_note->get('Weight')}</b> 
			</div>
			{/if} </td>
		</tr>
	</table>
	<table width="100%" style="font-family: sans-serif;" cellpadding="10">
		<tr>
			
			<td width="45%" style="border: 0.1mm solid #888888;"> <span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivery address{/t}:</span> 
			<div>
				{$delivery_note->get('Delivery Note XHTML Ship To')}
			</div>
			</td>
			<td width="10%">&nbsp;</td>
			<td width="45%" style="text-align: right" >
				<div style="text-align: right">
				{t}Parcels{/t}:<b> {$delivery_note->get_formated_parcels()}</b> 
			</div>
			<div style="text-align: right">
				{t}Weight{/t}: <b>{$delivery_note->get('Weight')}</b> 
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
				<td style="width:8%;text-align:right">{t}RRP{/t}</td>
				<td style="width:8%;text-align:right">{t}Ordered{/t}</td>
				<td style="width:8%;text-align:right">{t}Dispatched{/t}</td>

				<td style="text-align:right">{t}Notes{/t}</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$transactions item=transaction name=products} 
			<tr class="{if $smarty.foreach.products.last}last{/if}">
				<td style="width:8%;text-align:left">{$transaction['Product Code']}</td>
				<td style="text-align:left">{$transaction['Product XHTML Short Description']}</td>
				<td style="width:8%;text-align:right">{$transaction['RRP']}</td>
				<td style="width:8%;text-align:right">{$transaction['Required']}</td>
				<td style="width:8%;text-align:right"><b>{$transaction['dispatched']}</b></td>
								<td style="text-align:right">{$transaction['notes']}</td>

			</tr>
			{/foreach} 
		</tbody>
		
	</table>
	<br> 
	
	<br> 
	</body>
	</html>
