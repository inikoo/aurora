{include file='header.tpl'}
<div id="bd" >
{include file='orders_navigation.tpl'}
<div > 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$customer->get_formated_id()} ({t}In Warehouse{/t})</span>
</div>
     <div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="border:0px solid #ddd;width:400px;float:left"> 
         <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order Public ID')}</h1>

         <h2 style="padding:0">{$order->get('Order Customer Name')} (<a href="customer.php?id={$order->get("Order Customer Key")}">{$customer->get_formated_id()}</a>)</h2>
         {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	 <div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000"><b>{$order->get('Order Customer Contact Name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	 <div style="float:left;line-height: 1.0em;margin:5px 0 0 30px;color:#444"><span style="font-weight:500;color:#000">{t}Shipped to{/t}</span>:<br/>{$order->get('Order XHTML Ship Tos')}</div>
	 
	<div style="clear:both"></div>
       </div>
       
       <div style="border:0px solid #ddd;width:190px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   {if $order->get('Order Items Discount Amount')!=0 }
	   <tr><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright">{$order->get('Items Gross Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright">-{$order->get('Items Discount Amount')}</td></tr>
	   
	   {/if}
	   <tr><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright">{$order->get('Items Net Amount')}</td></tr>
	   {if $order->get('Order Net Credited Amount')!=0  }
	   <tr><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$order->get('Net Credited Amount')}</td></tr>
	   {/if}
	   {if  $order->get('Order Charges Net Amount')}<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$order->get('Charges Net Amount')}</td></tr>{/if}
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$order->get('Shipping Net Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Net{/t}</td><td width=100 class="aright">{$order->get('Total Net Amount')}</td></tr>
	   
	   
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright">{$order->get('Total Tax Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$order->get('Total Amount')}</b></td></tr>
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:290px;float:right">
	 {if $note}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Order Date{/t}:</td><td class="aright">{$order->get('Date')}</td></tr>
	   
	   <tr><td>{t}Invoices{/t}:</td><td class="aright">{$order->get('Order XHTML Invoices')}</td></tr>
	   <tr><td>{t}Delivery Notes{/t}:</td><td class="aright">{$order->get('Order XHTML Delivery Notes')}</td></tr>
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
     </div>

   <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>
	<div id="table_type">
	 
	 
	</div>
<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <div id="list_options0"> 
      
      

      
      <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}Order{/t}</td>
	  <td {if $view=='handing'}class="selected"{/if}  id="handing"  >{t}Handing{/t}</td>
	 
	</tr>
      </table>
    
    
    </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>
</div>


	    
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
