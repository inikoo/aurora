{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}
  <input type="hidden" value="{$order->get('Order Shipping Method')}" id="order_shipping_method"  />
  <input type="hidden" value="{$store->id}" id="store_id"  />
  <input type="hidden" value="{$order->id}" id="order_key"  />
  <input type="hidden" value="{$order->get('Order Current Dispatch State')}" id="dispatch_state"  />
  <input type="hidden" value="{$order->get('Order Number Items')}" id="ordered_products_number"  />
  <input type="hidden" value="{$path}" id="path"  />
  
 <input type="hidden" value="{$products_display_type}" id="products_display_type"  />
<div > 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({$order->get('Current Dispatch State')})</span>
</div>
 
 <div style="clear:both"></div>
 
    
      <div style="border:1px solid #ccc;text-align:left;padding:10px;">

       <div style="width:350px;float:left"> 
        <h1 style="padding:0">{t}Order{/t} {$order->get('Order Public ID')}</h1>
        <h2 style="padding:0">{$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a></h2>
      
	<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
	{$customer->get('Customer Main Contact Name')}
	<div style="margin-top:5px">
	{$customer->get('Customer Main XHTML Address')}
	</div>
	</div>
	<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
	<span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>:<div style="margin-top:5px"id="delivery_address">{$order->get('Order XHTML Ship Tos')}</div>



   <span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</span>




	<span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set for collection{/t}</span>

</div>
<div id="for_collection"  style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
<span>{t}For collection{/t}</span>
<span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</span>

</div>


<div style="clear:both">


</div>

       </div>



          <div style="width:210px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross"  ><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td></tr>
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if}   id="tr_order_items_discounts"  ><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright"  >-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td></tr>
	   
	   
	   <tr><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td></tr>
	 
	   <tr  {if $order->get('Order Net Credited Amount')==0}style="display:none"{/if}><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright" id="order_credits"  >{$order->get('Net Credited Amount')}</td></tr>
	   
	   <tr  id="tr_order_items_charges"    ><td  class="aright" >{t}Charges{/t}</td><td id="order_charges"  width=100 class="aright">{$order->get('Charges Net Amount')}</td></tr>
	   
	   <tr id="tr_order_shipping"><td  class="aright" > <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="art/icons/edit.gif" id="edit_button_shipping"  /> {t}Shipping{/t}</td>
	   <td id="order_shipping" width=100 class="aright">{$order->get('Shipping Net Amount')}</td>
	   
	   
	   </tr>
	   
	
	   
	   
	   <tr style="border-top:1px solid #777"><td  class="aright" >{t}Net{/t}</td><td id="order_net" width=100 class="aright">{$order->get('Total Net Amount')}</td></tr>
	   
	   
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td id="order_tax" width=100 class="aright">{$order->get('Total Tax Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Total{/t}</td><td id="order_total" width=100 class="aright" style="font-weight:800">{$order->get('Total Amount')}</td></tr>
	   
	 </table>
       </div>

       <div style="width:250px;float:right">
	 {if $note}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Order Date{/t}:</td><td class="aright">{$order->get('Date')}</td></tr>
	   
	  
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
      </div>


    

 <table class="quick_button" style="clear:both;margin-top:4px;">
    <tr>
        <td {if $order->get('Order Current Dispatch State')!='In Process'}style="display:none"{/if} id="import_transactions_mals_e" >{t}Import from Mals-e{/t}</td>
        <td {if $order->get('Order Current Dispatch State')!='In Process'}style="display:none"{/if} id="done">{t}Send to Warehouse{/t}</td>
         <td {if $order->get('Order Current Dispatch State')=='In Process'}style="display:none"{/if} id="modify_order">{t}Modify Order{/t}</td>

 <td id="cancel" style="padding-left:10px">{t}Cancel Order{/t}</td>
    </tr>
</table>

       

 



      <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>

  <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='ordered_products'}selected{/if} label_ordered_products"  id="ordered_products"  >{t}Ordered Products{/t} (<span id="ordered_products_number">{$order->get('Number Items')}</span>)</span>
 
        </div>
     </div>
	

<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>





    <div id="list_options0"> 


   
      
      <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Discounts{/t}</td>
	  <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Properties{/t}</td>
	</tr>
      </table>
      <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
      <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>
</div>
<div>
<button onClick="checkout()" style="align:right; margin-left:20px">Checkout</button>
<img src="{$path}inikoo_files/art/icons/btn_xpressCheckout.gif" align="left" style="margin-right:7px; align=:right">
</div>      
      
    </div>
    {if $items_out_of_stock}
    <div style="clear:both;margin:30px 0" >
      <h2>{t}Items Out of Stock{/t}</h2>
      <div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
    </div>
    {/if}




<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



</div> 


<div id="dialog_cancel">
  <div style="text-align:left;margin-left:5px">{t}Reason of cancellation{/t}</div>
  <div id="cancel_msg"></div>
  
  <table >
    <tr><td colspan=2>
	<textarea style="height:100px" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea>
      </td>
    </tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text state_details" onClick="close_dialog('cancel')" >{t}Go Back{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('cancel')" id="cancel_save"  class="unselectable_text state_details"     style="visibility:hidden;" >{t}Continue{/t}</span></td></tr>
</table>
</div>


<div id="dialog_edit_shipping" style="border:1px solid #ccc;text-align:left;padding:10px">
  <div id="edit_shipping_msg"></div>
  
  
  <table style="margin:10px" border=0>
<tr id="calculated_shipping_tr">
<td colspan=3 style="text-align:right;border-bottom:1px solid #ccc">
 <button id="use_calculate_shipping">{t}Use calculated value{/t}</button>
</td>
</tr>

<tr>
<td  style="padding-top:10px">{t}Set Shipping{/t}:</td>
 <td style="padding-top:10px"> <input id="shipping_amount" style="text-align:right" value="{$order->get('Order Shipping Net Amount')}"/></td>
 
<td style="padding-top:10px">
 <button id="save_set_shipping">{t}Save{/t}</button>
 </td>
</tr>
  </table>


</div>



<div id="dialog_import_transactions_mals_e" style="border:1px solid #ccc;text-align:left;padding:10px">
  <div id="import_transactions_mals_e_msg"></div>
  
  
  <table style="margin:10px" border=0>
<tr>
<td  style="padding-top:10px">{t}Copy and paste the Emals-e email here{/t}:</td>
</tr>
<tr>
 <td style="padding-top:10px"> <textarea style="width:100%" id="transactions_mals_e" ></textarea>
 </td>
 </tr>
 <tr>
<td style="padding-top:10px">
 <button style="cursor:pointer" id="save_import_transactions_mals_e">{t}Import{/t}</button>
 </td>
</tr>

  </table>


</div>




<div id="change_staff_discount" style="display:nonex;position:absolute;xleft:-100px;xtop:-150px;background:#fff;padding:5px;border:1px solid #777;font-size:90%">
  <div class="bd" >
    <h2 >{t}Select Discount{/t}</h2>
 <table class="edit inbox" border=0 >
      
   
      <tr style="height:20px; border:none; " > <td style="padding-right:25px ">{t}Discount{/t}: </td><td style="text-align:left;"><input onKeyup="change_discount_function(this.value,'change_discount')" style="width:6em" type="text" id="change_discount_value" value=""/></td><td>%</td></tr>
     
 
    <tr class="buttons" ><td style="text-align:left"><span id="change_discount_cancel"  style="margin-left:0px;" class="unselectable_text button" onClick="close_change_discount_dialog()">{t}Cancel{/t}<img src="art/icons/cross.png"/></span></td><td><span  onclick="change_discount_function2()" id="change_discount_save"   class="unselectable_text button"     style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" /></span></td><td></td></tr>
  </table>
  </div>
</div>
<div  id="edit_delivery_address_splinter_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;position:relative;" id="edit_address_dialog">
<div style="text-align:right;margin-bottom:15px;margin-top:30px"><span onClick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span></div>
 {include file='edit_delivery_address_splinter.tpl'}
</div>
{include file='footer.tpl'}
