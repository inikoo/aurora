 
<input type="hidden" id="order_key" value="{$order->id}" />
<input type="hidden" id="label_code" value="{t}Code{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_quantity" value="{t}Quantity{/t}" />
<input type="hidden" id="label_gross" value="{t}Amount{/t}" />
<input type="hidden" id="label_discount" value="{t}Discount{/t}" />
<input type="hidden" id="label_to_charge" value="{t}To Charge{/t}" />
<input type="hidden" id="label_net" value="{t}Net{/t}" />
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
				<tr id="tr_order_credits" {if $order->
					get('Order Net Credited Amount')==0}style="display:none"{/if}> 
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_credits" /> {t}Credits{/t}</td>
					<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
				</tr>
				<tr id="tr_order_items_charges">
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_items_charges" /> {t}Charges{/t}</td>
					<td id="order_charges" width="100" class="aright">{$charges_deal_info}{$order->get('Charges Net Amount')}</td>
				</tr>
				<tr id="tr_order_shipping">
					<td class="aright"> <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="art/icons/edit.gif" id="edit_button_shipping" /> {t}Shipping{/t}</td>
					<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
				</tr>
				<tr style="border-top:1px solid #777">
					<td class="aright">{t}Net{/t}</td>
					<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
				</tr>
				<tr id="tr_order_tax" style="border-bottom:1px solid #777">
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_tax" /> <span id="tax_info">{$order->get_formated_tax_info()}</span></td>
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
	<div id="payment_chooser" >
		<h2 style="margin-bottom:10px">
			{t}Choose payment method{/t}:
		</h2>
		<div id="world_pay_container" class="payment_method_button glow" style="margin-left:0px">
			<h2>
				{t}Credit card{/t} 
			</h2>
		</div>
		<div id="paypay_container" class="payment_method_button glow">
			<h2>
				{t}Pay by Paypal{/t} 
			</h2>
		</div>
		<div id="sofort_container" class="payment_method_button glow">
			<h2>
				{t}Online Bank to Bank Transfer{/t} 
			</h2>
		</div>
		<div id="bank_container" class="payment_method_button glow">
			<h2>
				{t}Traditional Bank Transfer{/t} 
			</h2>
		</div>
		<div style="clear:both">
		</div>
		<div id="confirm_order" style="margin-top:30px;min-height:100px">
		<div class="buttons right">
			<button class="" onclick="location.href='basket.php'">{t}Confirm Payment{/t}</button> 
		</div>
		</div>
		
	</div>
	<div style="clear:both;">
	</div>
</div>
