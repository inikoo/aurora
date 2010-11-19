{include file="$head_template"}
<body class="yui-skin-sam kaktus">
  <div id="container" >
    {include file="templates/checkout_header.en_GB.tpl"}
   
      
      
     <div class="order_edit" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 0px 0">

<div class="order_edit_block" >

<div class="payment">
<h2>Payment Method</h2>
{$order->get('Order Payment Method')}
</div>
<span class="state_details options">Change Payment Method</span>
</div>


<div class="order_edit_block" style="margin-left:20px" >
<div class="address">
<h2>Billing Address</h2>
{$order->get('Order XHTML Ship Tos')}
</div>
<span id="change_billing_address" class="state_details options" >{t}Change Billing Address{/t}</span>

</div>


<div class="order_edit_block" style="margin-left:20px">

<div class="address">
<h2>Delivery Address</h2>






{$order->get('Order XHTML Ship Tos')}




</div>
<span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px" onclick="window.location.href='edit_del_address.php'">{t}Change Delivery Address{/t}</span>
<span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set this order is for collection{/t}</span>

<div id="for_collection"  style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
<span>{t}For collection{/t}</span>
<span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</span>
</div>



</div>


     

          

       
       
       <div style="clear:both"></div>
      </div>
	
	
 
    
	<div style="clear:both;height:20px"></div>
	<div style="border:0px solid #ddd;width:210px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross"  ><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td></tr>
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if}   id="tr_order_items_discounts"  ><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright"  id="order_items_discount">-{$order->get('Items Discount Amount')}</td></tr>
	   
	   
	   <tr style="display:none"><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td></tr>
	 
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
	
	<div class="data_table"  style="clear:both;margin-bottom:40px">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>

	<div id="table_type">






<div  class="clean_table_caption"  style="clear:both;">
<div class="table_top_bar" ></div>
<span   style="float:right;margin-left:80px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>

	 <div style="float:left;">
	   <div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div>
	 </div>
	 <div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show0" {if $filter_show0}style="display:none"{/if}>{t}filter results{/t}</div>
	 <div class="clean_table_filter" id="clean_table_filter0" {if !$filter_show0}style="display:none"{/if}>
	   <div class="clean_table_info" style="padding-bottom:1px; ">
	     <span id="filter_name0" class="filter_name"  style="margin-right:5px">{$filter_name0}:</span>
	     <input style="border-bottom:none;width:6em;" id='f_input0' value="{$filter_value0}" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide0" style="margin-left:8px">{t}Hide filter{/t}</span>
	     <div id='f_container0'></div>
	   </div>
	 </div>
	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
       
              <div  id="table0"   class="data_table_container dtable btable with_total"> </div>



	 </div>
	
     

     
    <div id="list_options0"> 
      

 
  </div>


    
	
	
    {include  file="$footer_template"}
 </body>
