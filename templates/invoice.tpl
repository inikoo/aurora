{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">

      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

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
  {if $invoice->get('Invoice Total Net Adjust Amount')!=0}
  <tr style="color:red"><td  class="aright" >{t}Adjust Net{/t}</td><td width=100 class="aright">{$invoice->get('Total Net Adjust Amount')}</td></tr>
  {/if}
 {if $invoice->get('Invoice Shipping Net Amount')!=0}
  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$invoice->get('Shipping Net Amount')}</td></tr>	
 {/if}
  <tr  style="border-top:1px solid #777"     ><td    class="aright" >{t}Total Net{/t}</td><td width=100 class="aright">{$invoice->get('Total Net Amount')}</td></tr>
  
  {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
  <tr  style="color:red"><td  class="aright" >{t}Adjust Tax{/t}</td><td width=100 class="aright">{$invoice->get('Total Tax Adjust Amount')}</td></tr>
  {/if}
  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Tax{/t}</td><td width=100 class="aright">{$invoice->get('Total Tax Amount')}</td></tr>
  <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$invoice->get('Total Amount')}</b></td></tr>

	</table>
      </div>

 <div style="border:0px solid #ddd;width:290px;float:right">
       {if $note}<div class="notes">{$note}</div>{/if}


<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:20px;margin:0 30px;float:right" >

<tr><td>{t}Invoice Date{/t}:</td><td class="aright">{$invoice->get('Invoice Date')}</td></tr>

<tr><td>{t}Order{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Orders')}</td></tr>
<tr><td>{t}Delivery Notes{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Delivery Notes')}</td></tr>
</table>

      </div>


<div style="clear:both"></div>
      </div>



<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>

	    
    </div>
{if $items_out_of_stock}
<div style="clear:both;margin:30px 0" >
<h2>{t}Items Out of Stock{/t}</h2>
<div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
</div>
{/if}
  </div>
</div>
</div> 
{include file='footer.tpl'}
