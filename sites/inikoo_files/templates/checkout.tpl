 
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
<div id="order_container">
	<div class="buttons right">
		<h1 style="margin:0px;padding:0;font-size:20px;float:left">
			Order {$order->get('Order Public ID')} 
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
					{t}Shipping Address{/t}: 
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
				<tr id="tr_order_credits" {if $order->get('Order Net Credited Amount')==0}style="display:none"{/if}> 
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
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="payment_chooser">
		<h2 style="margin-bottom:10px">
			{t}Choose payment method{/t}: 
		</h2>
		<div id="payment_account_container_Worldpay" class="payment_method_button glow" style="margin-left:0px;" onclick="choose_payment_account('Worldpay',2)">
			<h2>
				<img style="margin-right:5px" src="art/credit_cards.png"> {t}Debit/Credit Card{/t} 
			</h2>
			<div>
				<div>
					<img style="position:relative;top:15px;width:90px;;left:-7px;float:left;border:0px solid red" src="art/credit_cards_worldpay.png"> <img style="position:relative;top:35px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_wordlpay.gif"> 
				</div>
			</div>
		</div>
		<div id="payment_account_container_Paypal" class="payment_method_button glow" onclick="choose_payment_account('Paypal',1)">
			<h2 style="position:relative;left:-40px;">
			<img style="margin-right:5px" src="art/paypal.png"> {t}Paypal{/t} 
		</h2>
		<div>
			<img style="position:relative;top:30px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_paypal.png"> 
		</div>
	</div>
	<div id="payment_account_container_Sofort" class="payment_method_button glow" onclick="choose_payment_account('Sofort',8)">
			<h2 style=" position:relative;left:-30px;">
		<img style="margin-right:5px" src="art/sprinter.png"> {t}Online Bank Transfer{/t} 
	</h2>
	<div>
		<img style="position:relative;top:5px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_sofort.png"> 
	</div>
</div>
<div id="payment_account_container_Bank" class="payment_method_button glow" onclick="choose_payment_account('Bank',11)">
			<h2>
				<img style=" margin-right:5px" src="art/bank.png">
	{t}Traditional Bank Transfer{/t} 
</h2>
</div>
<div style="clear:both">
</div>
<div id="confirm_order" style="margin-top:30px;min-height:100px">
	<div class="buttons right">
		<button class="" id="confirm_payment">{t}Confirm Payment{/t} <img id="confirm_payment_img" style="position:relative;top:2px" src="art/icons/arrow_right.png"></button>
		<button style="display:none" class="positive" id="place_order">{t}Place Order{/t}</button> 
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
{include file='payment_service_provider_splinter_Sofort.tpl'} 
{include file='payment_service_provider_splinter_Paypal.tpl'} 
{include file='payment_service_provider_splinter_Worldpay.tpl'} 
{include file='payment_service_provider_splinter_Bank.tpl'} 
</div>
