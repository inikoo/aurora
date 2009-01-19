{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">

      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order ID')}</h1>
        <h2 style="padding:0"><a href="customer.php?id={$order->get('order customer key')}">{$order->get('order customer name')} (ID:{$customer->get('customer id')})</a></h2>
        {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}To be paid by{/t}</span>:<br/>{$customer->get('Customer Main Company name')}<br/><b>{$customer->get('Customer Main Contact name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}To be shipped to{/t}</span>:<br/>{$customer->get('Order Main XHTML Ship to')}</div>
	{if $address_delbill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}B&D Address{/t}</span>:<br/>{$address_delbill}</div>{/if}

	{if $address_del!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}Delivery Address{/t}</span>:<br/>{$address_del}</div>{/if}
	{if $address_bill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0 5px 10px;color:#444"><span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>:<br/>{$address_bill}</div>{/if}
<div style="clear:both"></div>
       </div>

       <div style="border:0px solid #ddd;width:300px;float:left">
       {if $note}<div class="notes">{$note}</div>{/if}


<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:20px;margin:0 30px;float:right" >

<tr><td>{t}Received Date{/t}:</td><td class="aright">{$order->get('Date')}</td></tr>
<tr><td>{t}IP Address{/t}:</td><td class="aright">{$order_hist}</td></tr>


<tr  ><td>{t}Created Date{/t}:</td><td class="aright">{$order->get('Date Created')}</td></tr>

<tr><td>{t}Order Number{/t}:</td><td class="aright">{$order_hist}</td></tr>
<tr><td>{t}Payment Method{/t}:</td><td class="aright">{$payment_method}</td></tr>
<tr><td>{t}Delivered by{/t}:</td><td class="aright">{$deliver_by}</td></tr>
{if $w}<tr><td>{t}Weight{/t}:</td><td class="aright">{$w}</td></tr>{/if}
{if $parcels}<tr><td>{t}Parcels{/t}:</td><td class="aright">{$parcels}</td></tr>{/if}
</table>

      </div>





<div style="border:0px solid #ddd;width:250px;float:right">
<table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:120px" >
	  <tr><td  class="aright" >{t}Items Cost{/t}</td><td width=100 class="aright">{$items_vateable}</td></tr>
	  {if $credits_vateable  }<tr><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$credits_vateable}</td></tr>{/if}
	  {if $other_charges_vateable  }<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$other_charges_vateable}</td></tr>{/if}
	  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$shipping_vateable}</td></tr>
	  <tr><td  class="aright" >{t}Net{/t}</td><td width=100 class="aright">{$net}</td></tr>


	  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright">{$tax}</td></tr>
	  <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$total}</b></td></tr>

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
