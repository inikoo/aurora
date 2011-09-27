{include file='header.tpl'}
<div id="bd" >
 {include file='orders_navigation.tpl'}
<div style=""> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$customer->get_formated_id()} ({t}In Process{/t})</span>
</div>
  <div id="yui-main">
    <div id="control_panel" class="yui-b">
      <div style="text-align:right">
	<span class="state_details" id="continue_later"><a href="customer.php?id={$order->get('order customer key')}">Continue Later</a></span>
	<span class="state_details" id="cancel" style="margin-left:20px">Cancel</span>
	<span class="state_details" id="done" style="margin-left:20px">Send to Warehouse</span>

      </div>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;height:15em">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order Public ID')}</h1>
        <h2 style="padding:0"><a href="customer.php?id={$order->get('order customer key')}">{$order->get('Order Customer Name')} ({$customer->get_formated_id()})</a></h2>
        {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px"><span style="font-weight:500;color:#000">{t}Contact Address{/t}</span>:<br/><b>{$customer->get('Customer Main Contact Name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
	<span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>:<div id="delivery_address">{$order->get('Order XHTML Ship Tos')}</div>



   <span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</span>




	<span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set this order is for collection{/t}</span>

</div>
<div id="for_collection"  style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
<span>{t}For collection{/t}</span>
<span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</span>

</div>


<div style="clear:both"></div>
       </div>

          <div style="border:0px solid #ddd;width:190px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross"  ><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td></tr>
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if}   id="tr_order_items_discounts"  ><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright"  id="order_items_discount">-{$order->get('Items Discount Amount')}</td></tr>
	   
	   
	   <tr><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td></tr>
	 
	   <tr  {if $order->get('Order Net Credited Amount')==0}style="display:none"{/if}><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright" id="order_credits"  >{$order->get('Net Credited Amount')}</td></tr>
	   
	   <tr {if  $order->get('Order Charges Net Amount')==0} style="display:none"{/if}  id="tr_order_items_charges"    ><td  class="aright" >{t}Charges{/t}</td><td id="order_charges"  width=100 class="aright">{$order->get('Charges Net Amount')}</td></tr>
	   
	   <tr id="tr_order_shipping" style="{if $order->get('Order Shipping Method')=='Calculated' and $order->get('Order Shipping Net Amount')!=''}{else}display:none;{/if}"><td  class="aright" >{t}Shipping{/t}</td>
	   <td id="order_shipping" width=100 class="aright">{$order->get('Shipping Net Amount')}</td>
	   
	   
	   </tr>
	   
	 <tr id="tr_order_shipping_on_demand" style="{if $order->get('Order Shipping Method')=='On Demand' or ( $order->get('Order Shipping Method')=='Calculated' and $order->get('Order Shipping Net Amount')=='')}{else}display:none;{/if}"><td  class="aright" >{t}Shipping{/t}</td>
	   <td  width=100 class="aright"><span id="given_shipping"  >{if $order->get('Order Shipping Net Amount')!=''}{$order->get('Shipping Net Amount')}</span>{/if}
	   
	   <br/><span class="state_details" id="set_shipping">{t}Change Shipping{/t}</span>

	   </td>
	   
	   
	   </tr>
	   
	   
	   <tr style="border-top:1px solid #777"><td  class="aright" >{t}Net{/t}</td><td id="order_net" width=100 class="aright">{$order->get('Total Net Amount')}</td></tr>
	   
	   
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td id="order_tax" width=100 class="aright">{$order->get('Total Tax Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Total{/t}</td><td id="order_total" width=100 class="aright"><b>{$order->get('Total Amount')}</b></td></tr>
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:290px;float:right">
	 {if $note}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Order Date{/t}:</td><td class="aright">{$order->get('Date')}</td></tr>
	   
	  
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
      </div>

      <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>
	<div id="table_type">
	  <span id="table_type_list" style="float:right;color:brown" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}Recomendations{/t}</span>
	 
	</div>
<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <div id="list_options0"> 


   

 <span   style="float:right;margin-left:20px" class="state_details {if !$show_all}selected{/if}" onClick="show_only_ordered_products()"  id="show_only_ordered_products"  >{t}Show only ordered{/t}</span>
   <span   style="float:right;margin-left:20px" class="state_details {if $show_all}selected{/if}" onClick="show_all_products()"  id="show_all_products"  >{t}Show All{/t}</span>
 <span class="state_details" style="float:right;visibility:hidden" id="showing_only_family">Showing only <span  style="  font-style: italic;" id="search_family_code"></span> Family Products</span>    
      

      
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
      
      
    </div>
    {if $items_out_of_stock}
    <div style="clear:both;margin:30px 0" >
      <h2>{t}Items Out of Stock{/t}</h2>
      <div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
    </div>
    {/if}
  </div>
</div>




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
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text state_details" onClick="close_dialog('cancel')" >{t}Go Back{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('cancel')" id="cancel_save"  class="unselectable_text state_details"     style="visibility:hidden;" >{t}Continue{/t}</span></td></tr>
</table>
</div>



<div id="dialog_edit_shipping" style="text-align:left;padding:10px">
  <div id="edit_shipping__msg"></div>
  {t}Set Shipping{/t}:
  
  <table style="margin:10px">
<tr>
 <td> <input id="shipping_amount" value=""></td>
 </tr>
 <tr>
 <td style="text-align:right">
 <button id="reset_set_shipping">{t}Reset{/t}</button>
 <button id="save_set_shipping">{t}Save{/t}</button>
 </td>
</tr>
  </table>


</div>

<div id="panel2" style="visibility:hidden"> 


<div>Search Family: <input id="family_search" value="" name="family_search"/></div>

<div id="search_error" style="position:relative; visibility:hidden;margin-bottom:10px">{t}You have entered unexisting family{/t}</div>
</div> 

<! ------------------------ discount search starts here ----------------------------------->
<div id="change_staff_discount" style="display:nonex;position:absolute;xleft:-100px;xtop:-150px;background:#fff;padding:5px;border:1px solid #777;font-size:90%">
  <div class="bd" >
    <h2 >{t}Select Discount{/t}</h2>
 <table class="edit inbox" border=0 >
      
   
      <tr style="height:20px; border:none; " > <td style="padding-right:25px ">{t}Discount{/t}: </td><td style="text-align:left;"><input onKeyup="change_discount_function(this.value,'change_discount')" style="width:6em" type="text" id="change_discount_value" value=""/></td><td>%</td></tr>
     
 
    <tr class="buttons" ><td style="text-align:left"><span id="change_discount_cancel"  style="margin-left:0px;" class="unselectable_text button" onClick="close_change_discount_dialog()">{t}Cancel{/t}<img src="art/icons/cross.png"></span></td><td><span  onclick="change_discount_function2()" id="change_discount_save"   class="unselectable_text button"     style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" ></span></td><td></td></tr>
  </table>
  </div>
</div>
<! -------------------------discount search ends here ------------------------------------>
<div  id="edit_delivery_address_splinter_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
<div style="text-align:right;margin-bottom:15px"><span onClick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span></div>
 {include file='edit_delivery_address_splinter.tpl'}
</div>
{include file='footer.tpl'}
