<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>

<div class="top_page_menu" style="padding:10px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button  onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  class="selected" onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>
</div>
<div style="clear:both">
</div>
</div>


<input type="hidden" id="invoice_key" value="{$id}"/>
<div  class="buttons">
 <button  onclick="window.location='invoice.pdf.php?id={$invoice->id}'">PDF Invoice</button>
</div>

  <div id="yui-main">


    <div  class="yui-b">

    <div id="main_details" class="yui-b" style="position:relative;border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">
    {if $invoice->get('Invoice Has Been Paid In Full')=='Yes'}<img style="position:absolute;top:20px;left:220px;z-index:4" src="art/stamp.paid.en.png"/>{/if}

    <div style="width:340px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Invoice{/t} {$invoice->get('Invoice Public ID')}</h1>
        <h2 style="padding:0">{$invoice->get('Invoice Customer Name')} ({$page->customer->id})</h2>
	
	
	<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><b>{$invoice->get('Invoice Main Contact Name')}</b><br/>{$invoice->get('Invoice XHTML Address')}</div>
	<div style="clear:both"></div>
       </div>
    <div style="border:0px solid #ddd;width:250px;float:right">

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
  <tr  style="border-top:1px solid #777;border-bottom:1px solid #777"     ><td    class="aright" >{t}Total Net{/t}</td><td width=100 class="aright">{$invoice->get('Total Net Amount')}</td></tr>
  
 
  {foreach from=$tax_data item=tax } 
  <tr ><td  class="aright" >{t}{$tax.name}{/t}</td><td width=100 class="aright">{$tax.amount}</td></tr>
  {/foreach}
   {if $invoice->get('Invoice Total Tax Adjust Amount')!=0}
  <tr  style="color:red"><td  class="aright" >{t}Adjust Tax{/t}</td><td width=100 class="aright">{$invoice->get('Total Tax Adjust Amount')}</td></tr>
  {/if}
  <tr style="border-top:1px solid #777"><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$invoice->get('Total Amount')}</b></td></tr>

	</table>
      </div>
    <div style="border:0px solid #ddd;width:300px;float:right">



<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:20px;margin-right:30px;float:right" >

<tr><td>{t}Invoice Date{/t}:</td><td class="aright">{$invoice->get('Invoice Date')}</td></tr>

<tr style="display:none"><td>{t}Order{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Orders')}</td></tr>
<tr style="display:none"><td>{t}Delivery Notes{/t}:</td><td class="aright">{$invoice->get('Invoice XHTML Delivery Notes')}</td></tr>
<tr><td>{t}Payment Method{/t}:</td><td class="aright">{$invoice->get('Payment Method')}</td></tr>
<tr><td>{t}Payment State{/t}:</td><td class="aright">{$invoice->get('Payment State')}</td></tr>

</table>

      </div>


<div style="clear:both"></div>
      </div>








<div id="data_table">
<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0;font-size:90%"></div>

	    
    </div>
</div>

  </div>



