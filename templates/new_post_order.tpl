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
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({t}Post Dispatch Operations{/t})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
		<span class="main_title">{t}PDOs of Order{/t} <span class="id">{$order->get('Order Public ID')}</span> </span>
		</div>
		<div class="buttons">
			<button id="cancel" style="display:none" class="negative">{t}Cancel Post Order{/t}</button> <button onclick="window.location='order.php?id={$order->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Go back to order{/t}</button> <button id="show_mark_all_for_refund">{t}Refund all order{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div style="border:1px solid #ccc;text-align:left;padding:10px;">
		<div style="width:320px;float:left">
			
			<h2 style="padding:0">
				{$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
				{$customer->get('Customer Main Contact Name')} 
				<div style="margin-top:5px">
					{$customer->get('Customer Main XHTML Address')} 
				</div>
			</div>
			<div style="{if $order_post_transactions_in_process.Resend.Distinct_Products==0}display:none;{/if}">
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
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="width:400px;float:right">
			<table border="0" style="width:100%;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr>
					<td></td>
					<td style="border-top:1px solid #333;border-top:1px solid #777" class="aright">{t}Net{/t}</td>
					<td style="border-top:1px solid #333;border-top:1px solid #777" id="order_net" width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				<tr>
					<td></td>
					<td style="border-bottom:1px solid #777" class="aright">{t}VAT{/t}</td>
					<td style="border-bottom:1px solid #777" id="order_tax" width="100" class="aright">{$order->get('Total Tax Amount')}</td>
				</tr>
				<tr>
					<td></td>
					<td style="border-bottom:1px solid #333;" class="aright">{t}Original Total{/t}</td>
					<td id="order_total" width="100" class="aright" style="border-bottom:1px solid #333;font-weight:800">{$order->get('Total Amount')}</td>
				</tr>
				<tr id="resend" style="{if $order_post_transactions_in_process.Resend.Distinct_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					<div class="buttons small">
						<button id="send">{t}Send to Warehouse{/t}</button> 
					</div>
					</td>
					<td class="aright">{t}Replacements Value{/t}:</td>
					<td id="Resend_Formated_Market_Value" class="aright">{$order_post_transactions_in_process.Resend.Formated_Market_Value}</td>
				</tr>
				<tr id="credit" style="{if $order_post_transactions_in_process.Credit.Distinct_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					<div class="buttons small">
						<button id="save_credit">{t}Submit Credit{/t}</button> 
					</div>
					</td>
					<td class="aright">{t}Proposed Credit{/t}:</td>
					<td id="Credit_Formated_Amount" class="aright">{$order_post_transactions_in_process.Credit.Formated_Amount}</td>
				</tr>
				<tr id="saved_credit" style="{if $order_post_transactions_in_process.Saved_Credit.Distinct_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					<div class="buttons small">
						<button id="cancel_saved_credit">{t}Cancel Credit{/t}</button> 
					</div>
					</td>
					<td class="aright">{t}Sumited Credit{/t}:</td>
					<td id="Saved_Credit_Formated_Amount" class="aright">{$order_post_transactions_in_process.Saved_Credit.Formated_Amount}</td>
				</tr>
				<tr id="refund" style="{if $order_post_transactions_in_process.Refund.Distinct_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					<div class="buttons small">
						<button id="save_refund">{t}Create Refund{/t}</button> 
					</div>
					</td>
					<td class="aright">{t}Refund Value{/t}:</td>
					<td id="Refund_Formated_Amount" class="aright">{$order_post_transactions_in_process.Refund.Formated_Amount}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="data_table" style="clear:both;margin-top:10px">
		<span id="table_title" class="clean_table_title">{t}Ordered Items{/t}</span> 
		<div class="table_top_bar" style="margin-bottom:5px">
		</div>
		<div id="list_options0">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable ">
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
<div id="edit_delivery_address_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
	<div style="text-align:right;margin-bottom:15px">
		<span onclick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span> 
	</div>
	{include file='edit_delivery_address_splinter.tpl'} 
</div>
<div id="dialog_mark_all_for_refund" style="padding:20px 20px 10px 20px ">
	<div id="mark_all_for_refund_msg">
	</div>
	<table border="0">
		<tr>
			<td colspan="3">{t}Are items expected to be returned?{/t}</td>
		</tr>
		<input type="hidden" id="refund_return_items" value="Yes" />
		<tr>
			<td colspan="3">
			<div class="buttons left small">
				<button id="mark_all_for_refund_return_yes" class="selected" onclick="mark_all_for_refund_return('Yes')">{t}Yes{/t}</button> <button id="mark_all_for_refund_return_no" onclick="mark_all_for_refund_return('No')">{t}No{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="3">{t}Reason{/t}:</td>
		</tr>
		<tr>
			<td colspan="3"> 
			<input type="hidden" id="refund_reason" value="" />
			<div class="buttons left small" id="change_refund_reason_buttons">
				<button class="reason_button" onclick="change_refund_reason('Damaged',this)">{t}Damaged{/t}</button> <button class="reason_button" onclick="change_refund_reason('Missing',this)">{t}Not received{/t}</button> <button class="reason_button" id="change_refund_reason('Do not Like',this)">{t}Don't like it{/t}</button> <button class="reason_button" id="change_refund_reason('Other',this)">{t}Other{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:4px">
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="3">{t}Refund{/t}:</td>
		</tr>
		<tr style="border-top:1px solid #ccc">
			<input type="hidden" id="refund_items_value" value="{$order->get('Order Invoiced Items Amount')}" />
			<td class="aright">{t}Items (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
			<td><img onClick="switch_refund_element(this)" id="refund_items_switch" valor='Yes' src="art/icons/accept.png" style="height:14px;cursor:pointer"></td>
		</tr>
		<tr>
			<input type="hidden" id="refund_shipping_value" value="{$order->get('Order Invoiced Shipping Amount')}" />
			<td class="aright">{t}Shipping (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
			<td><img  onClick="switch_refund_element(this)" id="refund_shipping_switch"   valor="{if $order->get('Order Invoiced Shipping Amount')==0}No{else}Yes{/if}"  src="art/icons/accept.png" style="height:14px"></td>
		</tr>
		<tr style="{if $order->get('Order Invoiced Charges Amount')==0}display:none{/if}">
			<input type="hidden" id="refund_charges_value" value="{$order->get('Order Invoiced Charges Amount')}" />
			<td class="aright">{t}Charges (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
			<td ><img onClick="switch_refund_element(this)" id="refund_charges_switch"  valor="{if $order->get('Order Invoiced Charges Amount')==0}No{else}Yes{/if}"  src="art/icons/accept.png" style="height:14px"></td>
		</tr>
		<tr class="adjust" style="{if $order->get('Order Invoiced Total Net Adjust Amount')==0}display:none{/if}">
			<input type="hidden" id="refund_net_adjusts_value" value="{$order->get('Order Invoiced Charges Amount')}" />
			<td class="aright">{t}Adjusts (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
			<td ><img onClick="switch_refund_element(this)" id="refund_net_adjusts_switch" valor="{if $order->get('Order Invoiced Total Net Adjust Amount')==0}No{else}Yes{/if}" src="art/icons/accept.png" style="height:14px"></td>
		</tr>
		<tr style="border-top:1px solid #ccc">
			<input type="hidden" id="refund_tax_value" value="{$order->get('Order Invoiced Tax Amount')}" />
			<td class="aright">{t}Tax{/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Tax Amount')}</td>
			<td><img  onClick="switch_refund_element(this)" id="refund_tax_switch" valor="Yes" src="art/icons/accept.png" style="height:14px;cursor:pointer"></td>
		</tr>
		<tr class="adjust" style="{if $order->get('Order Invoiced Total Tax Adjust Amount')==0}display:none{/if}">
			<input type="hidden" id="refund_tax_adjusts_value" value="{$order->get('Order Invoiced Total Tax Adjust Amount')}" />
			<td class="aright">{t}Tax Adjusts{/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Tax Adjust Amount')}</td>
			<td><img onClick="switch_refund_element(this)" id="refund_tax_adjusts_switch" valor="{if $order->get('Order Invoiced Total Tax Adjust Amount')==0}No{else}Yes{/if}" src="art/icons/accept.png" style="height:14px;cursor:pointer"></td>
		</tr>
		<tr style="border-top:1px solid #ccc">
			<td class="aright">{t}Total{/t}</td>
						<input type="hidden" id="refund_currency_symbol" value="{$order->get_currency_symbol()}" />

			<td width="100" class="aright"><b id="refund_total" >{$order->get('Invoiced Total Amount')}</b></td>
		</tr>
		<tr>
			<td colspan="3">{t}Refund{/t}:</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 