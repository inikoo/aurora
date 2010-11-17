{include file='header.tpl'}
<div id="bd" >
 {include file='orders_navigation.tpl'}

  <div id="yui-main">
    <div class="yui-b">
      <div style="text-align:right">
	<span class="state_details" id="continue_later"><a href="customer.php?id={$order->get('order customer key')}">Continue Later</a></span>
	<span class="state_details" id="cancel" style="margin-left:20px">Cancel</span>
	<span class="state_details" id="done" style="margin-left:20px"><a href="customer.php?id={$order->get('order customer key')}">Send to Warehouse</a></span>

      </div>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;height:15em">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order Public ID')}</h1>
        <h2 style="padding:0"><a href="customer.php?id={$order->get('order customer key')}">{$order->get('Order Customer Name')} ({$customer->get_formated_id()})</a></h2>
        {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px"><span style="font-weight:500;color:#000">{t}Contact Address{/t}</span>:<br/><b>{$customer->get('Customer Main Contact Name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
	<span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>:<br/>{$order->get('Order XHTML Ship Tos')}



    <a href="customer.php?edit={$order->get('order customer key')}&return_to_order={$order->id}&edit_block=delivery"><span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</span></a>




	<span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set this order is for collection{/t}</span>

</div>
<div id="for_collection"  style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
<span>{t}For collection{/t}</span>
<span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</span>

</div>


<div style="clear:both"></div>
       </div>
 <div style="border:0px solid #ddd;width:190px;float:right;">
 {t}Free Replacement{/t}
 </div>


          <div style="border:0px solid #ddd;width:190px;float:right;display:none">
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
	<span id="table_title" class="clean_table_title">{t}Ordered Items{/t}</span>
	
<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <div id="list_options0"> 


   

    

      
     
    </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:80%"  class="data_table_container dtable btable "> </div>
</div>
      
      
    </div>
   
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


<div>Search Family: <input id="family_search" value=""/></div>

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

{include file='footer.tpl'}
