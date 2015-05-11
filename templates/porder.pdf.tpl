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
			{if file_exists("art/invoice_logo.{$po->get('Purchase Order Warehouse Code')}.jpg")}
			<td style="width:150px;"><img style="width:150px" src="art/invoice_logo.{$warehouse->get('Store Code')}.jpg" border="0" title="" alt=""></td>
			{/if} 
			<td style="width:250px;padding-left:10px;">{$po->get('Purchase Order Warehouse Name')} 
			<div style="font-size:7pt">
				{$po->get('Purchase Order Warehouse Address')|nl2br} 
			</div>
			
			</td>
			<td style="text-align: right;">{t}Purchase Order No.{/t}<br />
			<span style="font-weight: bold; font-size: 12pt;">{$po->get('Purchase Order Public ID')}</span></td>
		</tr>
	</table>
	</htmlpageheader> <htmlpagefooter name="myfooter"> 
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	</div>
	<table width="100%">
		<tr>
			<tr>
				<td width="33%" style="color:#000;text-align: left;"> <small> {$po->get('Purchase Order Warehouse Company Name')}<br> {if $po->get('Purchase Order Warehouse VAT Number')!=''}{t}VAT Number{/t}: {$po->get('Purchase Order Warehouse VAT Number')}<br>{/if} {if $po->get('Purchase Order Warehouse Company Number')!=''}{t}Registration number{/t}: {$po->get('Purchase Order Warehouse Company Number')}{/if} </small></td>
				<td width="33%" style="color:#000;text-align: center">{t}Page{/t} {literal}{PAGENO}{/literal} {t}of{/t} {literal}{nbpg}{/literal}</td>
				<td width="34%" style="text-align: right;"> <small> {if $po->get('Purchase Order Warehouse Telephone')!=''}{$po->get('Purchase Order Warehouse Telephone')}<br>{/if} {if $po->get('Purchase Order Warehouse Email')!=''}{$po->get('Purchase Order Warehouse Email')}{/if} </small></td>
			</tr>
		</table>
	</div>
	</htmlpagefooter> <sethtmlpageheader name="myheader" value="on" show-this-page="1" /><sethtmlpagefooter name="myfooter" value="on" /> 
	<table width="100%">
		<tr>
			<td> 
			<h1>
				{t}Purchase Order{/t} 
			</h1>
			</td>
			<td style="text-align: right"> 
			<div>
				{t}Date{/t}:<b>{$po->get_date('Purchase Order Submitted Date')}</b> 
			</div>
			</td>
		</tr>
	</table>
	<table border=0 width="100%" style="font-family: sans-serif;" cellpadding="0">
		<tr>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;"> 
			<div style="text-align: right">
				{t}Supplier{/t}:<b> {$po->get('Purchase Order Supplier Name')}</b> ({$po->get('Purchase Order Supplier Code')}) 
			</div>
			</td>
			<td width="50%" style="vertical-align:bottom;border: 0mm solid #888888;text-align: right"> 
			
			</td>
		</tr>
	</table>
	<table border=0 width="100%" style="font-family: sans-serif;" cellpadding="10">
		<tr>
			
			
			<td width="45%" style="border: 0.1mm solid #888888;"> 
			{if $po->get('Purchase Order Supplier Contact Name')!=''}<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Contact{/t}:</span> {$po->get('Purchase Order Supplier Contact Name')}<br>{/if}
			{if $po->get('Purchase Order Supplier Email')!=''}<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Email{/t}:</span> {$po->get('Purchase Order Supplier Email')}<br>{/if}
			{if $po->get('Purchase Order Supplier Telephone')!=''}<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Telephone{/t}:</span> {$po->get('Purchase Order Supplier Telephone')}<br>{/if}
			{if $po->get('Purchase Order Supplier Address')!=''}<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Address{/t}:</span> 
			<div>
				{$po->get('Purchase Order Supplier Address')} 
			</div>
			{/if}
			</td>
			
			
			<td width="45%" style="text-align: right"> 
			<div style="text-align: right">
				{t}Currency{/t}:<b>{$po->get('Purchase Order Currency Code')}</b> 
			</div>
			<div style="text-align: right;{if $po->get('Purchase Order Incoterm')==''}display:none{/if}">
				{t}Incoterm{/t}: <b>{$po->get('Purchase Order Incoterm')}</b> 
			</div>
			<div style="text-align: right;{if $po->get('Purchase Order Port of Export')==''}display:none{/if}">
				{t}Export Port{/t}: <b>{$po->get('Purchase Order Port of Export')}</b> 
			</div>
			<div style="text-align: right;{if $po->get('Purchase Order Port of Import')==''}display:none{/if}">
				{t}Import Port{/t}: <b>{$po->get('Purchase Order Port of Import')}</b> 
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
				<td style="width:6%;text-align:right">{t}Inner{/t}</td>
				<td style="width:7%;text-align:right">{t}U/Caton{/t}</td>
				<td style="width:7%;text-align:right">{t}Price/U{/t}</td>
				<td style="width:7%;text-align:right">{t}Cartons{/t}</td>
				<td style="width:7%;text-align:right">{t}Amount{/t}</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$transactions item=transaction name=products} 
			<tr class="{if $smarty.foreach.products.last}last{/if}">
				<td style="width:8%;text-align:left">{$transaction['Code']}</td>
				<td style="text-align:left">{$transaction['Description']}</td>
				<td style="width:6%;text-align:right">{$transaction['Inners']}</td>
				<td style="width:7%;text-align:right">{$transaction['Units']}</td>
				<td style="width:7%;text-align:right">{$transaction['Price_Unit']}</td>
				<td style="width:7%;text-align:right"><b>{$transaction['Cartons']}</b></td>
				<td style="width:7%;text-align:right">{$transaction['Amount']}</td>
			</tr>
			{/foreach} 
		</tbody>
				<tbody class="totals">

			<tr class="total">
			<td style="border:none" colspan="2" ></td>
				<td colspan="4"><b>{t}Total{/t}</b></td>
				<td>{$po->get('Total Amount')}</td>
			</tr>
		</tbody>
	</table>
	<br> <br>
	
	<div style="font-size: 9pt;{if $po->get('Purchase Order Terms and Conditions')==''}display:none{/if}">
	<h3>{t}Terms & Conditions{/t}</h3>
	        <p>
	    {$po->get('Purchase Order Terms and Conditions')}
	    <p>
	</div>
	 
	</body>
	</html>
