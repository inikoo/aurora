 
<input type="hidden" id="order_key" value="{$order->id}" />
<input type="hidden" id="label_code" value="{t}Code{/t}" />
<input type="hidden" id="label_price_per_outer" value="{t}Price per outer{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_quantity" value="{t}Quantity{/t}" />
<input type="hidden" id="label_gross" value="{t}Amount{/t}" />
<input type="hidden" id="label_discount" value="{t}Discount{/t}" />
<input type="hidden" id="label_to_charge" value="{t}To Charge{/t}" />
<input type="hidden" id="label_net" value="{t}Net{/t}" />
<input type="hidden" id="products_display_type" value="ordered_products" />
<input type="hidden" id="items_table_index" value="0" />
<input type="hidden" id="last_basket_page_key" value="{$last_basket_page_key}" />
<input type="hidden" id="subject" value="customer"> 
<input type="hidden" id="subject_key" value="{$customer->id}"> 
<input type="hidden" id="default_country_2alpha" value="{$store->get('Store Home Country Code 2 Alpha')}" />
<input type="hidden" id="customer_key" value="{$customer->id}"> 
<div id="order_container">
	<span id="gretings" style="margin-left:5px;position:relative;bottom:5px">{$greetings}</span> 
	<div id="control_panel">
		<div id="addresses">
			<h1 style="padding:0 0 5px 0;font-size:140%">
				Order {$order->get('Order Public ID')} 
			</h1>
			<h2>
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:-1px"> {$order->get('Order Customer Name')}, {$customer->get('Customer Main Contact Name')}, <span class="id">C{$customer->get_formated_id()}</span> 
			</h2>
			<div class="address">
				<div style="margin-bottom:5px">
					{t}Billing Address{/t}: 
				</div>
				<div class="address_box" id="billing_address">
					{$order->get('Order XHTML Billing Tos')} 
				</div>
				<div style="margin-top:2px" class="buttons left">
					<button id="change_billing_address">{t}Change{/t}</button> 
				</div>
			</div>
			<div class="address" style="margin-left:15px">
				<div id="title_delivery_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if};margin-bottom:5px">
					{t}Delivery Address{/t}: 
				</div>
				<div id="title_for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-bottom:5px">
					<b>{t}For collection{/t}</b> 
				</div>
				<div class="address_box" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none{/if};margin-top:2px" class="buttons left">
					<button id="change_delivery_address">{t}Change{/t}</button> <button style="{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Collect{/t}</button> 
				</div>
				<div id="for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-top:2px" class="buttons left">
					<button id="set_for_shipping" class="state_details" onclick="change_shipping_type('No')">{t}Set Delivery Address{/t}</button> 
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="totals">
			<span style="display:none" id="ordered_products_number"></span> 
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr {if $order->
					get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
					<td class="aright">{t}Items Gross{/t}</td>
					<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td>
				</tr>
				<tr {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td>
				</tr>
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td width="100" class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td>
				</tr>
				<tr id="tr_order_credits" {if $order->
					get('Order Net Credited Amount')==0}style="display:none"{/if}> 
					<td class="aright"> {t}Credits{/t}</td>
					<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
				</tr>
				<tr id="tr_order_items_charges">
					<td class="aright"> {t}Charges{/t}</td>
					<td id="order_charges" width="100" class="aright"><span id="charges_deal_info">{$charges_deal_info}</span>{$order->get('Charges Net Amount')}</td>
				</tr>
				<tr id="tr_order_shipping">
					<td class="aright"> {t}Shipping{/t}</td>
					<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
				</tr>
				<tr {if $order->get('Order Insurance Net Amount')==0 }style="display:none"{/if} id="tr_order_insurance" > 
					<td class="aright"> {t}Insurance{/t}</td>
					<td id="order_insurance" width="100" class="aright">{$order->get('Insurance Net Amount')}</td>
				</tr>
				
				<tr style="border-top:1px solid #777">
					<td class="aright">{t}Net{/t}</td>
					<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
				</tr>
				<tr id="tr_order_tax" style="border-bottom:1px solid #777">
					<td class="aright"> <span id="tax_info">{$order->get_formated_tax_info()}</span></td>
					<td id="order_tax" width="100" class="aright">{$order->get('Balance Tax Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Total{/t}</td>
					<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
				</tr>
			</table>
			<div style="text-align:right;padding-top:4px;;clear:both;{if $distinct_set_currency}display:none{/if}">
				<img src="art/info.png" style="height:14px;position:relative;bottom:-1px" /> {t}Please note, the prices are just a reference value, your payment will be in {/t} <b>{$store->get('Store Currency Code')}</b> with a total of <b><span id="store_currency_total_balance">{$total_in_store_currency}</span></b> 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:20px">
		<h2 style="float:left">
			{t}Items{/t} 
		</h2>
		<div style="float:right;padding-right:20px;{if $order->get('Order Number Products')<10}display:none{/if}">
			<img src="art/info.png" style="height:14px;position:relative;bottom:-1px" /> {t}To <b>update basket</b> please, click on the product quantity{/t}. 
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
		<div id="table0" class="data_table_container dtable btable" style="font-size:95%">
		</div>
	</div>
	

	
	<div style="float:left;">
		<span style="float:left;cursor:pointer;{if $order->get('Order Balance Total Amount')==0}display:none{/if}" id="show_cancel_order_dialog"><img style="position:relative;bottom:-1px" src="art/bin.png" title="{t}Cancel order{/t}" alt="Cancel order" /> {t}Clear order{/t} <span id="cancel_order_info" style="display:none">, {t}your order will be cancelled{/t} <img id="cancel_order_img" style="height:16px;position:relative;bottom:-2px" "cancel_order_img" style="height:16px" src="art/emotion_sad.png"></span></span> 
	</div>
	<div style="float:right;padding-right:20px;{if $order->get('Order Number Products')==0}display:none{/if}">
		<img src="art/info.png" style="height:14px;position:relative;bottom:-1px" /> {t}To <b>update basket</b> please, click on the product quantity{/t}. 
	</div>
	
	
		<div style="clear:both;padding-top:10px">
	{foreach from=$insurances item=insurance}
	<div class="insurance_row">
		{$insurance['Insurance Description']} (<b>{$insurance['Insurance Formated Net Amount']}<b>)<span style="widht:100px">
		<img insurance_key="{$insurance['Insurance Key']}" onptf_key="{$insurance['Order No Product Transaction Fact Key']}" id="insurance_checked_{$insurance['Insurance Key']}"   onclick="remove_insurance(this)" style="{if !$insurance['Order No Product Transaction Fact Key']}display:none{/if}"  class="checkbox"  src="art/icons/checkbox_checked.png">
		<img insurance_key="{$insurance['Insurance Key']}" id="insurance_unchecked_{$insurance['Insurance Key']}"  onclick="add_insurance(this)"  style="{if $insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox"  src="art/icons/checkbox_unchecked.png"></span>
		<img insurance_key="{$insurance['Insurance Key']}" id="insurance_wait_{$insurance['Insurance Key']}" style="display:none" class="checkbox"  src="art/loading.gif">

	</div>
	{/foreach}
	</div>
	
	
	<div style="margin-top:10px;margin-bottom:50px;border:1px solid white">
		<div style="float:left;position:relative;bottom:10px">
			<span>{t}Special intructions{/t}:</span><br> <textarea id="special_instructions" style="resize: none;border:1px solid #ccc;width:400px;height:100px;color:#555;padding:5px">{$order->get('Order Customer Message')}</textarea> 
			<div style="display:none" id="special_instructions_container">
			</div>
			<div style="text-align:right;position:relative;bottom:-110px;width:400px;height:20px">
				<span id="special_instructions_wait" style="font-size:85%;color:#aaa;display:none"><img style="width:12px;position:relative;top:1.5px" src="art/loading.gif" />{t}Saving{/t}</span> <span id="special_instructions_saved" style="font-size:85%;color:#aaa;display:none">{t}Saved{/t}</span> 
			</div>
		</div>
		<div class="buttons right" style="{if $order->get('Order Balance Total Amount')==0}display:none{/if}">
			<button onclick="location.href='checkout.php'" class="positive">{t}Proceed to Checkout{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both;margin-bottom:50px">
		&nbsp; 
	</div>
</div>
<div id="dialog_confirm_cancel" style="padding:0px 20px 20px 20px">
	<h2>
		{t}Do you want to cancel your order?{/t} 
	</h2>
	<div class="buttons">
		<span id="wait_cancel" style="float:right;display:none"><img style="position:relative;top:4px" src="art/loading.gif"> {t}Cancelling your order{/t}</span> <button id="cancel_order">{t}Yes{/t}</button> <button id="close_cancel_order_dialog">{t}No{/t}</button> 
	</div>
</div>
<div id="edit_delivery_address_splinter_dialog" class="edit_block" style="position:absolute;left:-1000px;width:890px;padding:20px 20px 20px 20px;background:#fff;">
	
	{include file='edit_delivery_address_splinter.tpl' default_country_2alpha=$store->get('Store Home Country Code 2 Alpha') parent='order' order_key={$order->id}} 
	<div class="buttons">
		<button id="close_edit_delivery_address_dialog" onclick="close_edit_delivery_address_dialog()" class="negative">{t}Close{/t}</button> 
	</div>
</div>
<div id="edit_billing_address_splinter_dialog" class="edit_block" style="position:absolute;left:-1000px;width:890px;padding:20px 20px 20px 20px;background:#fff;" >
	
	{include file='edit_billing_address_splinter.tpl' default_country_2alpha=$store->get('Store Home Country Code 2 Alpha') parent='order' order_key={$order->id}} 
	<div class="buttons">
		<button id="close_edit_billing_address_dialog" onclick="close_edit_billing_address_dialog()" class="negative">{t}Close{/t}</button> 
	</div>
</div>
<div id="dialog_check_tax_number" style="position:absolute;left:-1000px;padding:10px 20px 10px 10px;width:300px">
		<table style="width:100%;margin:5px auto;padding:0px 10px" class="edit">
			<tr class="title">
				<td colspan="2">{t}Tax Number:{/t} <span id="tax_number_to_check">{$customer->get('Customer Tax Number')}</span> </td>
			</tr>
			<tr id="check_tax_number_result_tr" style="display:none">
				<td colspan="2" id="check_tax_number_result"> </td>
			</tr>
			<tr id="check_tax_number_name_tr" style="display:none">
				<td>{t}Name:{/t}</td>
				<td id="check_tax_number_name"> </td>
			</tr>
			<tr id="check_tax_number_address_tr" style="display:none">
				<td>{t}Address:{/t}</td>
				<td id="check_tax_number_address"> </td>
			</tr>
			<tr id="check_tax_number_wait">
				<td colspan="2"> <img src="art/loading.gif" alt=""> {t}Processing Request{/t} </td>
			</tr>
			<tr id="check_tax_number_buttons" style="display:none">
				<td colspan="2"> 
				<div class="buttons" style="margin-top:10px">
					<button id="save_tax_details_match">{t}Details Match{/t}</button> <button id="save_tax_details_not_match">{t}Details not match{/t}</button> <button id="close_check_tax_number">{t}Close{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>


