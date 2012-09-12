{include file='header.tpl'}
<div id="bd" >
<div  class="branch"> 
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  
		{if $user->get_number_stores()>1}<a href="orders_server.php?view=invoices">&#8704; {t}Invoices{/t}</a> &rarr; {/if}
		<a href="orders.php?store={$store->id}&view=invoices">{t}Invoices{/t} ({$store->get('Store Code')})</a> &rarr;
		{$invoice->get('Invoice Public ID')} ({t}Unpaid{/t})</span> 
	</div>

<input type="hidden" id="invoice_key" value="{$invoice->id}"/>
<div  class="buttons">
	<span class="state_details" id="done" style="float:right;margin-left:40px"><span style="color:#000;font-size:150%">To pay: {$invoice->get('Total Amount')}</span>  <button  style="margin-left:5px" id="charge"><img id="charge_img" src="art/icons/coins.png" alt=""> {t}Charge{/t}</button></span>

 <button  style="height:24px;" onclick="window.location='invoice.pdf.php?id={$invoice->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button>
 
 <button  class="negative" id="cancel">{t}Cancel{/t}</button>

</div>
<div style="clear:both"></div> 

  <div style="text-align:right">

      </div>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 10px 0 10px 0">

       <div style="width:400px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Invoice{/t} {$invoice->get('Invoice Public ID')}</h1>
        <h2 style="padding:0">{$invoice->get('Invoice Customer Name')} <a href="customer.php?id={$invoice->get('Invoice Customer Key')}">{$invoice->get('Invoice Customer Key')}</a></h2>
	
	
	<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><b>{$invoice->get('Invoice Main Contact Name')}</b><br/>{$invoice->get('Invoice XHTML Address')}</div>
	<div style="clear:both"></div>
       </div>


      





<div style="border:0px solid #ddd;width:190px;float:right">
<table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:120px" >
  {if $invoice->get('Invoice Items Discount Amount')!=0 }
  <tr><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright">{$invoice->get('Items Gross Amount')}</td></tr>
  <tr><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright">-{$invoice->get('Items Discount Amount')}</td></tr>
{/if}
  <tr><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright">{$invoice->get('Items Net Amount')}</td></tr>
  {if $invoice->get('Invoice Refund Net Amount')!=0 }
  <tr><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$invoice->get('Refund Net Amount')}</td></tr>
  {/if}
  {if $invoice->get('Invoice Charges Net Amount')!=0}
  <tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$invoice->get('Charges Net Amount')}</td></tr>
  {/if}
 {if $invoice->get('Invoice Shipping Net Amount')!=0}
  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$invoice->get('Shipping Net Amount')}</td></tr>	
 {/if}
  <tr  style="border-top:1px solid #777"     ><td    class="aright" >{t}Total Net{/t}</td><td width=100 class="aright">{$invoice->get('Total Net Amount')}</td></tr>
  
  
  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Tax{/t}</td><td width=100 class="aright">{$invoice->get('Total Tax Amount')}</td></tr>
  <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$invoice->get('Total Amount')}</b></td></tr>

	</table>
      </div>

 <div style="border:0px solid #ddd;width:290px;float:right">
       {if isset($note)}<div class="notes">{$note}</div>{/if}


<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:20px;margin:0 30px;float:right" >

<tr><td>{t}Invoice Date{/t}:</td><td class="aright">{$invoice->get('Date')}</td></tr>

<tr><td>{t}Order{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Orders')}</td></tr>
<tr><td>{t}Delivery Notes{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Delivery Notes')}</td></tr>
</table>

      </div>


<div style="clear:both"></div>
      </div>



<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>

	    
    
{if isset($items_out_of_stock) and $items_out_of_stock}
<div style="clear:both;margin:30px 0" >
<h2>{t}Items Out of Stock{/t}</h2>
<div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
</div>
{/if}
  

</div> 


<div  id="dialog_pay_invoice" style="padding:20px 20px 10px 20px">

<div id="type_of_payment" class="buttons left">
<button id="pay_by_creditcard"><img src="art/icons/creditcards.png"/> {t}Credit Card{/t}</button>
<button id="pay_by_bank_transfer"><img src="art/icons/monitor_go.png"/> {t}Bank Transfer{/t}</button>
<button id="pay_by_paypal"><img style="width:37px;height:15px" src="art/icons/paypal.png"/> PayPal</button>
<button id="pay_by_cash"><img src="art/icons/money.png"/> {t}Cash{/t}</button>
<button id="pay_by_cheque"><img src="art/icons/cheque.png"/> {t}Cheque{/t}</button>
<button id="pay_by_other">{t}Other{/t}</button>
</div>
<div style="clear:both;height:10px"></div>
<input type="hidden" value="" id="payment_method">
<input type="hidden" value="{$invoice->get('Invoice Total Amount')}" id="invoce_full_amount">

<table>
<tr>
<td>{t}Amount Paid{/t}:</td><td style="text-align:right"><span id="amount_paid_total">{$invoice->get('Total Amount')}</span><input  type="text" style="display:none;text-align:right" id="amount_paid" value="{$invoice->get('Invoice Total Amount')}"></td>

<td>
<div class="buttons small">
<button  id="show_other_amount_field" onClick="show_other_amount_field()">{t}Other Amount{/t}</button>
<button  id="pay_all" style="display:none"  onClick="pay_all()">{t}Pay All{/t}</button>

</div>
</td>
</tr>
<tr>
<td>{t}Reference{/t}:</td><td><input id="payment_reference"></td>
</tr>
<tr style="height:5px"><td colspan="2"></td></tr>
<tr>
<td colspan="2">
<div class="buttons">
<button class="positive disabled" id="save_paid" onClick="save_paid">{t}Save{/t}</button>
<button class="negative" onClick="hide_dialog_pay_invoice()">{t}Cancel{/t}</button>
</div>
</td>
</tr>

</table>



</div>

{include file='footer.tpl'}
