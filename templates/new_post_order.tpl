{include file='header.tpl'}
<div id="bd" >
 {include file='orders_navigation.tpl'}

  <div id="yui-main">
    <div id="control_panel" class="yui-b">
    <div style="text-align:right">
	<span class="state_details" id="continue_later"><a href="order.php?id={$order->id}">Continue Later</a></span>
	<span class="state_details" id="cancel" style="margin-left:20px">Cancel</span>

      </div>
    <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;xheight:15em">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order Public ID')}</h1>
        <h2 style="padding:0"><a href="customer.php?id={$order->get('order customer key')}">{$order->get('Order Customer Name')} ({$customer->get_formated_id()})</a></h2>
      
	<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px"><b>{$customer->get('Customer Main Contact Name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	
	
<div style="clear:both"></div>
       </div>




          <div style="border:0px solid #ddd;width:430px;float:right;xdisplay:none">
	 <table border=0  style="width:100%;xborder-top:1px solid #333;xborder-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	  
	
<tbody id="resend" style="{if $order_post_transactions_in_process.Resend.Distinct_Products==0}display:none{/if}">	   
	   <tr  style="ont-size:90%;xborder-top:1px solid #ccc;border-bottom:1px solid #ccc">
	   <td >{t}Products to Resend{/t}</td>
	   <td style="width:150px;text-align:right" id="send"><span class="state_details">{t}Send to Warehouse{/t}</span></td>
	   </tr>
	        <tr><td  class="aright" >{t}Distinct Products{/t}:</td><td id="Resend_Distinct_Products"  class="aright">{$order_post_transactions_in_process.Resend.Distinct_Products}</td></tr>
	  <tr><td  class="aright" >{t}Market Value{/t}:</td><td id="Resend_Formated_Market_Value"  class="aright">{$order_post_transactions_in_process.Resend.Formated_Market_Value}</td></tr>
     <tr><td  class="aright" >{t}Shipping Address{/t}:
         

     </td><td id="delivery_address" class="aright">{$order->get('Order XHTML Ship Tos')}</td></tr>
<tr style="font-size:90%"><td><span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</span>
	<span id="set_for_collection" class="state_details" style="{if $order->get('Order For Collection')=='Yes'}display:none;{else}display:block;{/if}margin-top:4px" value="Yes">{t}Set this order is for collection{/t}</span>
<span id="set_for_shipping" class="state_details" style="{if $order->get('Order For Collection')=='No'}display:none;{else}display:block;{/if}margin-top:4px" value="No">{t}Set for shipping{/t}</span></td></tr>
</tbody>
<tbody id="refund" style="{if $order_post_transactions_in_process.Refund.Distinct_Products==0}display:none{/if}">	   
	    <tr style="font-size:90%;xborder-top:1px solid #ccc;border-bottom:1px solid #ccc">
	    <td >{t}Refunds{/t}</td>
	    <td style="text-align:right"><span id="save_refund" class="state_details">{t}Save{/t}</span></td>
	    </tr>
        <tr><td  class="aright" >{t}Distinct Products{/t}</td><td id="Refund_Distinct_Products"  class="aright">{$order_post_transactions_in_process.Refund.Distinct_Products}</td></tr>
	  <tr><td  class="aright" >{t}Amount{/t}</td><td id="Refund_Formated_Amount"  class="aright">{$order_post_transactions_in_process.Refund.Formated_Amount}</td></tr>
</tbody>
<tbody id="credit" style="{if $order_post_transactions_in_process.Credit.Distinct_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">	 
	     <tr style="font-size:90%;xborder-top:1px solid #ccc;border-bottom:1px solid #ccc">
	     <td  >{t}Credits{/t}</td>
	     <td style="text-align:right"><span class="state_details">{t}Save{/t}</span></td>
	     </tr>
	       <tr><td  class="aright" >{t}Distinct Products{/t}</td><td id="Credit_Distinct_Products"  class="aright">{$order_post_transactions_in_process.Credit.Distinct_Products}</td></tr>
	  <tr><td  class="aright" >{t}Amount{/t}</td><td id="Credit_Formated_Amount"  class="aright">{$order_post_transactions_in_process.Credit.Formated_Amount}</td></tr>
</tbody>
	   
	   

	   
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

<div  id="edit_delivery_address_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
<div style="text-align:right;margin-bottom:15px"><span onClick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span></div>
 {include file='edit_delivery_address_splinter.tpl'}
</div>


{include file='footer.tpl'}
