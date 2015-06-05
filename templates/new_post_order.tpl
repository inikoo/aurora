{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" value="{$session_data}" id="session_data" />
	<input type="hidden" value="{$order->get('Order Shipping Method')}" id="order_shipping_method" />
	<input type="hidden" value="{$store->id}" id="store_id" />
	<input type="hidden" value="{$store->id}" id="store_key" />
	<input type="hidden" value="{$order->id}" id="order_key" />
	<input type="hidden" value="{$order->get('Order Current Dispatch State')}" id="dispatch_state" />
	<input type="hidden" value="{$order->get('Order Customer Key')}" id="customer_key" />
	<input type="hidden" value="{$referral}" id="referral" />
	<input type="hidden" value="{$products_display_type}" id="products_display_type" />
		<input type="hidden" id="error_no_reason" value="{t}Please indicate a reason of the refund{/t}" />

	
	
	<div class="branch ">
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({t}Post Dispatch Operations{/t})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}PDOs of Order{/t} <span class="id">{$order->get('Order Public ID')}</span> </span> 
		</div>
		<div class="buttons">
			<button id="cancel" style="display:none" class="negative">{t}Cancel Post Order{/t}</button> <button id="go_back_to_order" onclick="window.location='order.php?id={$order->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Go back to order{/t}</button> <button id="show_mark_all_for_refund" >{t}Refund/Credit{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div style="border:1px solid #ccc;text-align:left;padding:10px;">
		<div style="width:310px;float:left;xborder:1px solid red">
			<h2 style="padding:0">
				{$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
				{$customer->get('Customer Main Contact Name')} 
				<div style="margin-top:5px">
					{$customer->get('Customer Main XHTML Address')} 
				</div>
			</div>
			<div id="shipping_block" style="{if $order_post_transactions_in_process.Resend.Distinct_Products==0}display:none;{/if}">
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
		<div style="width:290px;float:left;">
		 	{include  file='post_order_dns_splinter.tpl' dns_data=$dns_data number_dns=$number_dns}
		 	{include  file='post_order_refunds_splinter.tpl' refunds_data=$refunds_data number_refunds=$number_refunds}
		
		</div>
		<div id="totals" style="width:300px;float:right;xborder:1px solid red">
			<table border="0" style="width:100%;width:100%,padding:0;margin:0;float:right;margin-left:0px">
			
			<tr>
					<td></td>
					<td id="order_items_net_label"  style="border-top:1px solid #333;" class="aright">{t}Items Net{/t}</td>
					<td style="border-top:1px solid #333;"  id="order_items_net" width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
				</tr>
				<tr>
					<td></td>
					<td style="border-top:1px solid #333;" class="aright">{t}Charges{/t}</td>
					<td style="border-top:1px solid #333;"  id="order_charges" width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
				</tr>
				
				
				<tr>
					<td></td>
					<td  class="aright">{t}Shipping{/t}</td>
					<td   id="order_charges" width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
				</tr>
				
				<tr style="{if $order->get('Order Invoiced Insurance Amount')==0}display:none{/if}">
					<td></td>
					<td  class="aright">{t}Insurance{/t}</td>
					<td  id="order_charges" width="100" class="aright">{$order->get('Invoiced Insurance Amount')}</td>
				</tr>
				
			
			<tr>
					<td></td>
					<td style="border-top:1px solid #ccc;"  class="aright">{t}Total Net{/t}</td>
					<td   style="border-top:1px solid #ccc;"  id="order_net" width="100" class="aright">{$order->get('Invoiced Total Net Amount')}</td>
				</tr>
				
			
				
				<tr>
					<td></td>
					<td style="border-bottom:1px solid #777" class="aright">{t}VAT{/t}</td>
					<td style="border-bottom:1px solid #777" id="order_tax" width="100" class="aright">{$order->get('Invoiced Total Tax Amount')}</td>
				</tr>
				<tr>
					<td></td>
					<td style="border-bottom:1px solid #333;" class="aright">{t}Original Total{/t}</td>
					<td id="order_total" width="100" class="aright" style="border-bottom:1px solid #333;font-weight:800">{$order->get('Invoiced Total Amount')}</td>
				</tr>
				
				
				<tr id="refunded" style="{if $order_post_transactions_in_process.Refund.Refunded_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					
					</td>
					<td class="aright">{t}Refunded{/t}:</td>
					<td id="Refunded_Formated_Amount" class="aright">{$order_post_transactions_in_process.Refund.Refunded_Formated_Total_Amount}</td>
				</tr>
				
				
				<tbody id="resend" style="{if $order_post_transactions_in_process.Resend.Distinct_Products==0}display:none;{/if};" >
				<tr id="resend" >
					<td> 
					
					</td>
					<td class="aright">{t}Replacements Value{/t}:</td>
					<td id="Resend_Formated_Market_Value" class="aright">{$order_post_transactions_in_process.Resend.Formated_Market_Value}</td>
				</tr>
				
				<tr style="border-bottom:1px solid #ccc;margin-bottom:10px">
				<td colspan=3>
				<div class="buttons small">
						<button style="{if $order_post_transactions_in_process.Resend.In_Process_Products==0}display:none{/if};margin-right:0px" id="send"><img id="send_to_warehouse_img" src="art/icons/cart_go.png" alt=""> {t}Send to Warehouse{/t}</button> 
					</div>
				</td>
				</tr>
				</tbody>
				
				
			
				<tr id="refund" style="{if $order_post_transactions_in_process.Refund.In_Process_Products==0}display:none;{/if};border-bottom:1px solid #ccc;margin-bottom:10px">
					<td> 
					<div class="buttons small">
						<button id="save_refund" onClick="show_dialog_marked_for_refund()" >{t}Refund/Credit{/t}</button> 
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
		<div class="table_top_bar space" ">
		</div>
		
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
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
<div id="edit_delivery_address_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
	<div style="text-align:right;margin-bottom:15px">
		<span onclick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span> 
	</div>
	{include file='edit_delivery_address_splinter.tpl' parent='order' order_key={$order->id}} 
</div>
<div id="dialog_mark_all_for_refund" style="padding:20px 20px 10px 20px ">
	<div id="mark_all_for_refund_msg">
	</div>
	<table border="0" id="refund_options">
		<tr style="height:4px">
			<td colspan="4"> 
						<input type="hidden" id="refund_currency_symbol" value="{$order->get_currency_symbol()}" />

			
			<input type="hidden" id="refund_marked_items_value" value="{$order_post_transactions_in_process.Refund.Net_Amount}" />
			<input type="hidden" id="refund_marked_items_tax_value" value="{$order_post_transactions_in_process.Refund.Tax_Amount}" />


			<input type="hidden" id="refund_items_value" value="{$order->get('Order Invoiced Items Amount')}" />
			<input type="hidden" id="refund_items_tax_value" value="{$order->get('Order Invoiced Items Tax Amount')}" />
			
			<input type="hidden" id="refund_shipping_value" value="{$order->get('Order Invoiced Shipping Amount')}" />
			<input type="hidden" id="refund_shipping_tax_value" value="{$order->get('Order Invoiced Shipping Tax Amount')}" />

			<input type="hidden" id="refund_charges_value" value="{$order->get('Order Invoiced Charges Amount')}" />
			<input type="hidden" id="refund_charges_tax_value" value="{$order->get('Order Invoiced Charges Tax Amount')}" />

			<input type="hidden" id="refund_insurance_value" value="{$order->get('Order Invoiced Insurance Amount')}" />
			<input type="hidden" id="refund_insurance_tax_value" value="{$order->get('Order Invoiced Insurance Tax Amount')}" />

			<input type="hidden" id="refund_net_adjusts_value" value="{$order->get('Order Invoiced Charges Amount')}" />
			<input type="hidden" id="refund_tax_adjusts_value" value="{$order->get('Order Invoiced Total Tax Adjust Amount')}" />


			</td>
		</tr>
		
		<tr id="bulk_items_info" style="{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}display:none{/if}">
			<td colspan="5"> 
			<table border="0" style="width:100%">
				<tr>
					<td class="aleft" style="padding-right:30px">
					<input type="hidden" id="refund_return_items" value="Yes" />
					{t}Are these items expected to be returned?{/t}</td>
				</tr>
				<tr>
					<td style="padding-right:30px"> 
					<div class="buttons left  small">
						<button id="mark_all_for_refund_return_yes" class="selected" onclick="mark_all_for_refund_return('Yes')">{t}Yes{/t}</button> <button id="mark_all_for_refund_return_no" onclick="mark_all_for_refund_return('No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="aleft" style="padding-right:30px" id="reason_label">{t}Reason{/t}: </td>
				</tr>
				<tr>
					<td style="padding-bottom:15px;padding-right:25px"> 
					<input type="hidden" id="refund_reason" value />
					<div class="buttons left small" id="change_refund_reason_buttons">
						<button class="reason_button" onclick="change_refund_reason('Damaged',this)">{t}Damaged{/t}</button> <button class="reason_button" onclick="change_refund_reason('Missing',this)">{t}Not received{/t}</button> <button class="reason_button" onclick="change_refund_reason('Do not Like',this)">{t}Don't like it{/t}</button> <button class="reason_button" onclick="change_refund_reason('Other',this)">{t}Other{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		
		<tr class="title">
			<td class="label"></td>
			<td class="original_values" class="aright">{t}Original Net{/t}</td>
			<td class="switch_element"></td>
			
			<td class="refund_values">{t}Refund{/t}</td>
		</tr>
		<tr id="refund_marked_items_tr" style="border-bottom:1px solid #eee;{if $order_post_transactions_in_process.Refund.In_Process_Products==0}display:none{/if}">
			<td class="label">{t}Marked Items{/t}</td>
			<td id="refund_marked_items_original_formated_value" class="original_values">{$order_post_transactions_in_process.Refund.Formated_Net_Amount}</td>
			<td class="switch_element"><img onclick="switch_refund_element(this)" id="refund_marked_items_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products==0}_bw{/if}.png"style="height:14px;cursor:pointer"></td>
			<td id="refund_marked_items_formated_value" class="refund_values">{$order_post_transactions_in_process.Refund.Formated_Net_Amount}</td>
			<td class="switch_element"></td>
		</tr>
		<tr id="refund_items_tr" style="{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}display:none{/if}">
			<td class="label"><span id="show_other_items_options" onclick="show_bulk_items_info()" style="display:none;margin-right:5px;cursor:pointer;font-size:90%;color:#777">{t}show{/t}</span> {t}Items{/t}</td>
			<td id="refund_items_original_formated_value" class="original_values">{$order_post_transactions_in_process.Refund.Formated_Other_Items_Amount}</td>
			<td class="switch_element"><img onclick="switch_refund_element(this)" id="refund_items_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products==0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}_bw{/if}.png"style="height:14px;cursor:pointer"></td>
			<td id="refund_items_formated_value" class="refund_values" >{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		
		<tr style="{if $order->get('Order Invoiced Shipping Amount')==0}display:none{/if}">

			<td class="aright">{t}Shipping (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_shipping_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products==0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}_bw{/if}.png" style="height:14px"></td>
			<td width="100" class="aright" id="shipping">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		<tr style="{if $order->get('Order Invoiced Charges Amount')==0}display:none{/if}">
			<td class="aright">{t}Charges (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_charges_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products==0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}_bw{/if}.png" style="height:14px"></td>
			<td width="100" class="aright" id="charges">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		
		
		<tr style="{if $order->get('Order Invoiced Insurance Amount')==0}display:none{/if}">
			<td class="aright">{t}Insurance (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Insurance Amount')}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_insurance_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products==0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}_bw{/if}.png" style="height:14px"></td>
			<td width="100" class="aright" id="insurance">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		
		<tr class="adjust" style="{if $order->get('Order Invoiced Total Net Adjust Amount')==0}display:none{/if}">
			<td class="aright">{t}Adjusts (N){/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_net_adjusts_switch" valor='{if $order_post_transactions_in_process.Refund.In_Process_Products==0}Yes{else}No{/if}' src="art/icons/accept{if $order_post_transactions_in_process.Refund.In_Process_Products!=0}_bw{/if}.png" style="height:14px"></td>
			<td width="100" class="aright" id="net_adjusts">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		
		<tr  class="totals">
			<td class="aright">{t}Total Net{/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Net Amount')}</td>
			<td></td>
			<td width="100" class="aright" id="refund_net_total">{$order_post_transactions_in_process.Refund.Formated_Net_Amount}</td>
			<td class="switch_element"><img onclick="switch_refund_element(this)" id="refund_total_net_switch" valor='Yes' src="art/icons/accept.png" style="height:14px"></td>

		</tr>
		
		<tr style="border-top:1px solid #ccc">
			<input type="hidden" id="refund_tax_value" value="{$order->get('Order Invoiced Tax Amount')}" />
			<td class="aright">{t}Tax{/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Tax Amount')}</td>
						<td></td>

			<td width="100" class="aright" id="tax">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_tax_switch" valor='Yes' src="art/icons/accept.png" style="height:14px;cursor:pointer"></td>

		</tr>
		<tr class="adjust" style="{if $order->get('Order Invoiced Total Tax Adjust Amount')==0}display:none{/if}">
			<td class="aright">{t}Tax Adjusts{/t}</td>
			<td width="100" class="aright">{$order->get('Invoiced Total Tax Adjust Amount')}</td>
			<td><img onclick="switch_refund_element(this)" id="refund_tax_adjusts_switch" valor='No' src="art/icons/accept_bw.png" style="height:14px;cursor:pointer"></td>
			<td width="100" class="aright" id="tax_adjusts">{$order_post_transactions_in_process.Refund.Formated_Zero_Amount}</td>
		</tr>
		<tr  class="totals">
			<td class="aright">{t}Total{/t}</td>
			<input type="hidden" id="refund_currency_symbol" value="{$order->get_currency_symbol()}" />
			<td width="100" class="aright">{$order->get('Invoiced Total Amount')}</td>
			<td></td>
			<td width="100" class="aright "><b  id="refund_total">{$order_post_transactions_in_process.Refund.Formated_Amount}</b></td>
		</tr>
		
		<tr class="buttons">
		<td colspan=2 id="full_refund_msg" class="error"></td>
			<td colspan="2"> 
			<div class="buttons">
				<span id="waiting_save_full_refund" style="display:none;float:right"><img src="art/loading.gif"> {t}Processing request{/t}</span>
				<button id="save_full_refund" class="positive disabled" onclick="save_full_refund()">{t}Proceed{/t}</button> <button id="cancel_full_refund" class="negative" onclick="cancel_full_refund()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

{include file='footer.tpl'} 