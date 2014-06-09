 
<input type="hidden" id="order_key" value="{$order->id}" />
<input type="hidden" id="label_code" value="{t}Code{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_quantity" value="{t}Quantity{/t}" />
<input type="hidden" id="label_gross" value="{t}Amount{/t}" />
<input type="hidden" id="label_discount" value="{t}Discount{/t}" />
<input type="hidden" id="label_to_charge" value="{t}To Charge{/t}" />
<input type="hidden" id="label_net" value="{t}Net{/t}" />
<input type="hidden" id="payment_account_key" value="" />
<input type="hidden" id="payment_service_provider_code" value="" />
<input type="hidden" id="last_basket_page_key" value="{$last_basket_page_key}" />
<div id="order_container">
	<div class="buttons right">
		<h1 style="margin:0px;padding:0;font-size:20px;float:left">
			{t}Order{/t} {$order->get('Order Public ID')} <span style="display:none;font-size:50%;font-weight:400">{$order->id}</span> 
		</h1>
		<button style="position:relative;bottom:3px" onclick="location.href='basket.php'">{t}Go Back Basket{/t}</button> 
	</div>
	<div style="clear:both;margin-bottom:1px">
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2>
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:-1px"> {$order->get('order customer name')}, {$customer->get('Customer Main Contact Name')}, <span class="id">C{$customer->get_formated_id()}</span> 
			</h2>
			<div class="address">
				<div style="margin-bottom:5px">
					{t}Billing Address{/t}: 
				</div>
				<div class="address_box">
					{$customer->get('Customer XHTML Billing Address')} 
				</div>
			</div>
			<div class="address" style="margin-left:15px">
				<div style="margin-bottom:5px">
					{if $order->get('Order For Collection')=='Yes'}<b>{t}For collection{/t}</b>{else}{t}Delivery Address{/t}:{/if}
				</div>
				<div class="address_box">
					{$order->get('Order XHTML Ship Tos')} 
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
				<tr {if $order->
					get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
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
					<td id="order_charges" width="100" class="aright">{$charges_deal_info}{$order->get('Charges Net Amount')}</td>
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
			<img src="art/info.png" style="height:14px;position:relative;bottom:-1px" />  {t}Please note, the prices are just a reference value, your payment will be in {/t} <b>{$store->get('Store Currency Code')}</b> with a total of <b>{$total_in_store_currency}</b>
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="cancelled_payments_container" style="{if $order->get_number_payments('Cancelled')==0}display:none{/if}">
		<table id="cancelled_payments">
			<tr>
				<td colspan="6">
				<h3 style="padding-left:0px">
					{t}Cancelled payments{/t}
				</h3>
				</td>
			</tr>
			<tr class="title">
				<td>{t}Payment ID{/t}</td>
				<td>{t}Amount{/t}</td>
				<td>{t}Service Provider{/t}</td>
				<td>{t}Cancelled Date{/t}</td>
				<td></td>
				<td>{t}Notes{/t}</td>
			</tr>
			{foreach from=$order->get_payment_objects('Cancelled',true,true) item=payment} 
			<tr id="payment_{$payment->get('Payment Key')}" class="payment" payment_key="{$payment->get('Payment Key')}">
				<td>{$payment->get('Payment Key')}</td>
				<td>{$payment->get('Amount')}</td>
				<td>{$payment->payment_service_provider->get('Payment Service Provider Name')}</td>
				<td id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Cancelled Date')}</td>
				<td id="payment_date_interval_{$payment->get('Payment Key')}">{$payment->get_formated_time_lapse('Cancelled Date')}</td>
				<td>{$payment->get('Payment Transaction Status Info')}</td>
			</tr>
			{/foreach} 
		</table>
	</div>
	<div id="payment_chooser">
		<h2 style="margin-bottom:10px">
			{t}Choose payment method{/t}: 
		</h2>
		
		
		{foreach from=$payment_options item=payment_option name=payment_options}
		
	
		
		{include file="checkout_payment_account_splinter_`$payment_option.payment_service_provider_code`.tpl" payment_service_provider_code=$payment_option.payment_service_provider_code payment_account_key=$payment_option.payment_account_key first=$smarty.foreach.payment_options.first payment_account=$payment_option.payment_account}
		{/foreach}




		<div style="clear:both">
		</div>
		<div id="confirm_order" style="margin-top:30px;min-height:100px">
			<div class="buttons right">
				<button class="" id="confirm_payment">{t}Confirm Payment{/t} <img id="confirm_payment_img" style="position:relative;top:2px" src="art/icons/arrow_right.png"></button> 
				<button style="display:none" class="positive" id="place_order">{t}Place Order{/t}  <img id="place_order_img" style="position:relative;top:2px" src="art/icons/arrow_right.png"></button> 
				<div id="info_payment_account" style="display:none">
				</div>
				<div id="payment_account_not_selected" style="display:none">
					<h2>
						<img style="margin-right:5px" src="art/choose_payment_account.png"> Please select a payment method, from the boxes above 
					</h2>
				</div>
			</div>
		</div>
	</div>
	<div style="clear:both;">
	</div>
</div>
