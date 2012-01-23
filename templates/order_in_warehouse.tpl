{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" value="{$order->get('Order Shipping Method')}" id="order_shipping_method" />
	<input type="hidden" value="{$store->id}" id="store_id" />
	<input type="hidden" value="{$store->id}" id="store_key" />
	<input type="hidden" value="{$order->id}" id="order_key" />
	<input type="hidden" value="{$order->get('Order Current Dispatch State')}" id="dispatch_state" />
	<input type="hidden" value="{$order->get('Order Customer Key')}" id="customer_key" />
	<input type="hidden" value="{$referral}" id="referral" />
	<input type="hidden" value="{$products_display_type}" id="products_display_type" />
	<div class="branch ">
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({$order->get('Current Dispatch State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if $referral=='store_pending_orders' or $order->get('Order Current Dispatch State')=='Ready to Pick' or $order->get('Order Current Dispatch State')=='Picking & Packing' or $order->get('Order Current Dispatch State')=='Packed' } 
			<button onclick="window.location='customers_pending_orders.php?store={$store->id}'"><img src="art/icons/basket.png" alt=""> {t}Pending Orders{/t}</button> {/if} 
			<button onclick="window.location='orders.php?store={$store->id}&view=orders'"><img src="art/icons/house.png" alt=""> {t}Orders{/t}</button> 
		</div>
		<div class="buttons">
					<button style="height:24px;" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> 

			<button id="modify_order">{t}Modify Order{/t}</button>
			<button id="cancel" class="negative">{t}Cancel Order{/t}</button> 

		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div style="border:1px solid #ccc;text-align:left;padding:10px;">
		<div style="width:320px;float:left">
			<h1 style="padding:0">
				{t}Order{/t} <span class="id">{$order->get('Order Public ID')}</span>
			</h1>
			<h2 style="padding:0">
				{$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
				{$customer->get('Customer Main Contact Name')} 
				<div style="margin-top:5px">
					{$customer->get('Customer Main XHTML Address')} 
				</div>
			</div>
			<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
				<span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>: 
				<div style="margin-top:5px" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				<span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</span> <span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set for collection{/t}</span> 
			</div>
			<div id="for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
				<span>{t}For collection{/t}</span> <span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="width:210px;float:right">
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr {if $order->
					get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
					<td class="aright">{t}Items Gross{/t}</td>
					<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td>
				</tr>
				<tr {if $order->
					get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td>
				</tr>
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td width="100" class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td>
				</tr>
				<tr {if $order->
					get('Order Net Credited Amount')==0}style="display:none"{/if}> 
					<td class="aright">{t}Credits{/t}</td>
					<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
				</tr>
				<tr id="tr_order_items_charges">
					<td class="aright">{t}Charges{/t}</td>
					<td id="order_charges" width="100" class="aright">{$order->get('Charges Net Amount')}</td>
				</tr>
				<tr id="tr_order_shipping">
					<td class="aright"> <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="art/icons/edit.gif" id="edit_button_shipping" /> {t}Shipping{/t}</td>
					<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
				</tr>
				<tr style="border-top:1px solid #777">
					<td class="aright">{t}Net{/t}</td>
					<td id="order_net" width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				<tr style="border-bottom:1px solid #777">
					<td class="aright">{t}VAT{/t}</td>
					<td id="order_tax" width="100" class="aright">{$order->get('Total Tax Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Total{/t}</td>
					<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Total Amount')}</td>
				</tr>
			</table>
		</div>
		<div style="width:300px;float:right">
			{if $order->get_notes()} 
			<div class="notes">
				{ $order->get_notes()} 
			</div>
			{/if} 
			<table border="0" style="border-top:1px solid #333;border-bottom:1px solid #333;width:280px,padding-right:0px;margin-right:20px;float:right">
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr style="{if $order->get('Order Current Dispatch State')=='In Process'}display:none;{/if}font-size:90%">
					<td>{t}Delivery Notes{/t}:</td>
					
					<td class="aright" >{$order->get('Order Current XHTML Dispatch State')}</td>

				
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="data_table" style="clear:both;margin-top:10px">
		<span id="table_title" class="clean_table_title">{t}Items{/t}</span> 
	
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:90%" class="data_table_container dtable btable ">
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
</div>
<div id="dialog_cancel" style="padding:15px 20px 5px 10px;width:200px">
	<div id="cancel_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2">{t}Cancel Order{/t}</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2">{t}Reason of cancellation{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea> </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save('cancel')" id="cancel_save" class="positive" style="visibility:hidden;">{t}Continue{/t}</button> <button class="negative" onclick="close_dialog('cancel')">{t}Go Back{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_edit_shipping" style="border:1px solid #ccc;text-align:left;padding:10px">
	<div id="edit_shipping_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr id="calculated_shipping_tr">
			<td colspan="3" style="text-align:right;border-bottom:1px solid #ccc"> <button id="use_calculate_shipping">{t}Use calculated value{/t}</button> </td>
		</tr>
		<tr>
			<td style="padding-top:10px">{t}Set Shipping{/t}:</td>
			<td style="padding-top:10px"> 
			<input id="shipping_amount" style="text-align:right" value="{$order->get('Order Shipping Net Amount')}" />
			</td>
			<td style="padding-top:10px"> <button id="save_set_shipping">{t}Save{/t}</button> </td>
		</tr>
	</table>
</div>
<div id="dialog_import_transactions_mals_e" style="border:1px solid #ccc;text-align:left;padding:10px">
	<div id="import_transactions_mals_e_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr>
			<td style="padding-top:10px">{t}Copy and paste the Emals-e email here{/t}:</td>
		</tr>
		<tr>
			<td style="padding-top:10px"> <textarea style="width:100%" id="transactions_mals_e"></textarea> </td>
		</tr>
		<tr>
			<td style="padding-top:10px"> <button style="cursor:pointer" id="save_import_transactions_mals_e">{t}Import{/t}</button> </td>
		</tr>
	</table>
</div>
<div id="change_staff_discount" style="display:nonex;position:absolute;xleft:-100px;xtop:-150px;background:#fff;padding:5px;border:1px solid #777;font-size:90%">
	<div class="bd">
		<h2>
			{t}Select Discount{/t} 
		</h2>
		<table class="edit inbox" border="0">
			<tr style="height:20px; border:none; ">
				<td style="padding-right:25px ">{t}Discount{/t}: </td>
				<td style="text-align:left;"> 
				<input onkeyup="change_discount_function(this.value,'change_discount')" style="width:6em" type="text" id="change_discount_value" value="" />
				</td>
				<td>%</td>
			</tr>
			<tr class="buttons">
				<td style="text-align:left"><span id="change_discount_cancel" style="margin-left:0px;" class="unselectable_text button" onclick="close_change_discount_dialog()">{t}Cancel{/t}<img src="art/icons/cross.png" /></span></td>
				<td><span onclick="change_discount_function2()" id="change_discount_save" class="unselectable_text button" style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" /></span></td>
				<td></td>
			</tr>
		</table>
	</div>
</div>
<div id="edit_delivery_address_splinter_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
	<div style="text-align:right;margin-bottom:15px">
		<span onclick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span> 
	</div>
	{include file='edit_delivery_address_splinter.tpl'} 
</div>
{include file='footer.tpl'} 