{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
	<div style="margin-top:5px;float:right;border: 0px solid #ddd;text-align:right">
	  <form  id="prod_search_form" action="assets_index.php" method="GET" >
	    <label>{t}Order Search{/t}:</label><input size="12" class="text search" id="prod_search" value="" name="name"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
	  </form>
	</div>
      <h1>{$tipo_f} {$public_id}</h1>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;margin:0px;padding:10px;margin: 0 0 10px 0">
	<table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:120px" >
	  <tr><td  class="aright" >{t}Items Cost{/t}</td><td width=100 class="aright">{$items_vateable}</td></tr>
	  {if $credits_vateable  }<tr><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$credits_vateable}</td></tr>{/if}
	  {if $other_charges_vateable  }<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$other_charges_vateable}</td></tr>{/if}
	  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$shipping_vateable}</td></tr>
	  <tr><td  class="aright" >{t}Net{/t}</td><td width=100 class="aright">{$net}</td></tr>


	  <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright">{$tax}</td></tr>
	  <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$total}</b></td></tr>

	</table>

	<table border=0  style="width:100%,padding-right:20px;margin:0 30px;float:right" >
<tr><td>{t}Order Number{/t}:</td></tr>
<tr><td>{t}Payment Method{/t}:</td></tr>

	</table>


	<h2>{$customer_name}</h2>
<div style="margin-left:10px">

	{$contact}<br/>
        {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	{if $address_delbill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}B&D Address{/t}</span>:<br/>{$address_delbill}</div>{/if}

	{if $address_del!=''}<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000">{t}Delivery Address{/t}</span>:<br/>{$address_del}</div>{/if}
	{if $address_bill!=''}<div style="float:left;line-height: 1.0em;margin:5px 0 5px 10px;color:#444"><span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>:<br/>{$address_bill}</div>{/if}
</div>
	<div style="clear:both"></div>

      </div>

      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>
      <div    style="border:1px solid #ccc;width:200px;float:right;padding:0;margin:0;border-top:none;">
	<table border=0  style="width:100%,padding:0;margin:0;float:right" >
	  {if $credit!=0  }<tr><td   class="aright" >{t}Credits{/t}</td><td width=100 class="aright" style="padding-right:20px">{$fcredit}</td></tr>{/if}
	  <tr><td  class="aright" >{t}Order Value{/t}</td><td width=100 class="aright"style="padding-right:20px">{$items_vateable}</td></tr>

	  {if $charges_vateable  }<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 style="padding-right:20px" class="aright">{$charges_vateable}</td></tr>{/if}
	  <tr><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright"style="padding-right:20px">{$shipping_vateable}</td></tr>
	  <tr><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright"style="padding-right:20px">{$vat}</td></tr>
	  <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"style="padding-right:20px">{$total}</td></tr>

	</table>
      </div>

	    
    </div>
{if $items_out_of_stock}
<div style="clear:both;margin-bottom:50px" >
<h1>{t}Items Out of Stock{/t}</h1>
<div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
</div>
{/if}
  </div>
</div>
</div> 
{include file='footer.tpl'}
