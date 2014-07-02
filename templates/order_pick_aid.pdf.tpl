<html>
<head>
<style>
{literal}
body {font-family: sans-serif;
    font-size: 10pt;
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
.clearBoth { clear:both; }


hr {
border-top: 0.1mm solid #000000;
height:1px;

}
#order_pick_aid_data {width:100%; border-spacing:0; 
      border-collapse:collapse;  }
#order_pick_aid_data tr{border-bottom: 0.1mm solid #000000}

#order_pick_aid_data td{padding-bottom:4px;padding-top:5px}
#order_pick_aid_data td.label{border-bottom: 0.1mm solid #000000}

#order_pick_aid_data td.to_fill{border-bottom: 0.1mm solid #000000;width:100%}

{/literal}
</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="50%" style="color:#000;"><span style="font-weight: bold; font-size: 14pt;">{t}Order Pick Aid{/t} {$delivery_note->get('Delivery Note ID')}</span><br />(C{$delivery_note->get('Delivery Note Customer Key')})  {$delivery_note->get('Delivery Note Customer Name')}<br /></td>
<td width="50%" style="text-align: right;">
{if $delivery_note->get('Delivery Note Order Date Placed')}
<div style="text-align: right">{t}Order Date{/t}: {$delivery_note->get('Order Date Placed')}</div>
{/if}
<div style="text-align: right">{t}Delivery Note Date:{/t} {$delivery_note->get('Date Created')}</div>
</td>

</tr></table>
</htmlpageheader>

<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {literal}{PAGENO}{/literal} of {literal}{nbpg}{/literal}



</div> 



</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->

    
   
                                                                                                    



<table width="100%"  style="font-family: sans-serif;" cellpadding="10">
<tr>
<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Delivery Address{/t}:</span><br /><br />
{$delivery_note->get('Delivery Note XHTML Ship To')}
</td>
<td width="10%">&nbsp;</td>
<td width="45%" style="border: 0.1mm solid #888888;font-size:9pt">
<table id="order_pick_aid_data" cellspacing="0" cellpadding="0">
<tr><td class="label">{t}Picker{/t}:</td><td class="to_fill"></td></tr>
<tr><td class="label">{t}Packer{/t}:</td><td class="to_fill"></td></tr>
<tr><td class="label">{t}Weight{/t}:</td><td class="to_fill">{$delivery_note->get('Weight')}</td></tr>
<tr><td class="label">{t}Parcels{/t}:</td><td class="to_fill"></td></tr>
<tr><td class="label">{t}Courier{/t}:</td><td class="to_fill"></td></tr>
<tr><td class="label">{t}Consigment{/t}:</td><td class="to_fill"></td></tr>
</table>

</tr>


</table>

<br>

<div style="height:70px;border:0.1mm  solid #000;margin-bottom:20px;padding:10px">
<span style="font-size: 7pt; color: #555555; font-family: sans-serif;">{t}Notes{/t}:</span><br>

{$delivery_note->get('Delivery Note Warehouse Note')|nl2br}<br>

</div>

<table class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="8">
<thead>
<tr>
<td align="left" width="8%">{t}Location{/t}</td>
<td align="center" width="10%">{t}Reference{/t}</td>
<td  align="left" >{t}Descripion{/t}</td>
<td align="left" width="10%">{t}Stock{/t}</td>
<td align="center" width="10%">{t}To Pick{/t}</td>
<td align="center" width="10%">{t}N/P{/t}</td>
<td  align="left" width="18%">{t}Notes{/t}</td>
</tr>
</thead>
<tbody>

{foreach from=$transactions item=transaction name=products} 
<tr   class="{if $smarty.foreach.products.last}last{/if} {if $smarty.foreach.products.iteration is even} even{/if} ">
<td align="left">{$transaction.location}</td>
<td align="center">{$transaction.reference}</td>
<td align="left">{$transaction.description}</td>
<td align="left">{$transaction.stock}</td>
<td align="center">{$transaction.qty}</td>
<td align="right"></td>
<td  align="left">{$transaction.notes}</td>
</tr>
{/foreach}



</tbody>
</table>
<br>

</body>
</html>