{include file='header.tpl'}
<div id="bd" >
<input type="hidden" id="order_key" value="{$order->id}"/>
 {include file='orders_navigation.tpl'}
<div  class="branch"> 
<span>{if $user->get_number_stores()>1}<a  href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Delivery Notes{/t}</a> &rarr; {$dn->get('Delivery Note ID')} ({$dn->get_state()})</span>
</div>

<div  class="buttons">
 <button  onclick="window.location='delivery_notes.pdf.php?id={$dn->id}'">PDF Delivery Note</button>
</div>


     <div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="border:0px solid #ddd;width:350px;float:left"> 
         <h1 style="padding:0 0 0px 0">{t}Delivery Note{/t} {$dn->get('Delivery Note ID')}</h1>
	 <h3 >{$dn->get('Delivery Note Title')}</h3>

         <h2 style="padding:0">{$dn->get('Delivery Note Customer Name')} (<a href="customer.php?id={$dn->get("Order Customer ID")}">{$customer->get_formated_id()}</a>)</h2>
       
	 <div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000"><b>{$dn->get('Order Customer Contact Name')}</b></div>
	
	 
	<div style="clear:both"></div>
       </div>
       
       <div style="border:0px solid #ddd;width:290px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	 	   <tr><td  class="aright" >{t}Estimated Weight{/t}</td><td width=100 class="aright">{$dn->get('Estimated Weight')}</td></tr>

	
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:250px;float:right">
	 {if isset($note)}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Creation Date{/t}:</td><td class="aright">{$dn->get('Date Created')}</td></tr>
	   <tr><td>{t}Orders{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Orders')}</td></tr>
	   {if $dn->get('Delivery Note XHTML Invoices')!=''}
	   <tr><td>{t}Invoices{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Invoices')}</td></tr>
	    {/if}
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
     </div>



<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>

	    
    </div>

  </div>
</div>
</div> 
{include file='footer.tpl'}
