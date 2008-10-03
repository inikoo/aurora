{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">

      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{$tipo_f} {$public_id}</h1>
        <h2 style="padding:0">{$customer_name}</h2>
        {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	{if $address_delbill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}B&D Address{/t}</span>:<br/>{$address_delbill}</div>{/if}

	{if $address_del!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}Delivery Address{/t}</span>:<br/>{$address_del}</div>{/if}
	{if $address_bill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0 5px 10px;color:#444"><span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>:<br/>{$address_bill}</div>{/if}
<div style="clear:both"></div>
       </div>

       <div style="border:0px solid #ddd;width:300px;float:left">
       {if $note}<div class="notes">{$note}</div>{/if}


<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:20px;margin:0 30px;float:right" >
<tr><td>{t}Invoice Date{/t}:</td><td class="aright">{$date_invoiced}</td></tr>
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


<div style="">
<table border=0  style="xcolor:#555;xfont-size:85%;border-top:1px solid #444;border-bottom:1px solid #777;width:100%,padding-right:20px;margin:5px 30px;float:left" >
<tr><td>{t}Processed by{/t}:</td><td class="aright">{$taken_by}</td></tr>
<tr><td>{t}Picked by{/t}:</td><td class="aright">{$picked_by}</td></tr>
<tr><td>{t}Packed by{/t}:</td><td class="aright">{$packed_by}</td></tr>
</table>
{t}This order was dispatched{/t}  {$dispatch_time}.
<div style="clear:both"></div>

</div>

<h2>{t}Dispatched items{/t}</h2>
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
