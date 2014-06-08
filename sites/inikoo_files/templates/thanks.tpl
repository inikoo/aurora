 
<input type="hidden" id="order_key" value="{$order->id}" />
<input type="hidden" id="label_code" value="{t}Code{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_quantity" value="{t}Quantity{/t}" />
<input type="hidden" id="label_gross" value="{t}Amount{/t}" />
<input type="hidden" id="label_discount" value="{t}Discount{/t}" />
<input type="hidden" id="label_to_charge" value="{t}To Charge{/t}" />
<input type="hidden" id="label_net" value="{t}Net{/t}" />
<input type="hidden" id="last_basket_page_key" value="{$last_basket_page_key}" />

<div id="order_container">
	<div class="thanks_message">
		<h1>
			{t}Thank you! We are delighted to receive your order at {/t} {$store->data['Store Name']}. 
		</h1>
		<p>
			{t}Rest assured we are already beavering away to get your order to you just as soon as we can{/t}. 
		</p>
		<p>
			{$payment_info}
		</p>
		<p>
			{t}Your order details are listed below, if you have any questions please email our team at:{/t} <a class="highlight" href="mailto:{$store->get('Store Email')}">{$store->get('Store Email')}</a> {t}or you can use the online chat on our website just quote this{/t} <span class="highlight">Order Number {$order->get('Order Public ID')}</span> {t}so we can help you{/t}. 
		</p>
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h1 style="padding:0 0 5px 0;font-size:140%">
				Order {$order->get('Order Public ID')} 
			</h1>
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
					{t}Delivery Address{/t}: 
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
			<img src="art/info.png" style="height:14px;position:relative;bottom:-1px" />  {t}Please note, the prices are just a reference value, your order is in {/t} <b>{$store->get('Store Currency Code')}</b> with a total of <b>{$total_in_store_currency}</b>
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:20px;font-size:80%">
		<h2>
			{t}Items{/t} 
		</h2>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
		<div id="table0" class="data_table_container dtable btable" xstyle="font-size:85%">
		</div>
	</div>
	<table class="items_totals" border="0" style="display:none">
		<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> {t}Shipping{/t} </td>
			<td class="aright total"> {$order->get('Shipping Net Amount')} </td>
		</tr>
		<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> {t}Charges{/t} </td>
			<td class="aright total"> {$order->get('Charges Net Amount')} </td>
		</tr>
		<tr>
			<td class="hidden"> </td>
			<td class="total_tr aright description"> {t}Net{/t} </td>
			<td class=" total_tr aright total"> {$order->get('Balance Net Amount')} </td>
		</tr>
		<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> {t}Vat{/t} </td>
			<td class="aright total"> {$order->get('Balance Tax Amount')} </td>
		</tr>
		<tr>
			<td class="hidden"> </td>
			<td class="total_tr total_balance aright description"> {t}Total{/t} </td>
			<td class="total_tr total_balance aright total"> {$order->get('Balance Total Amount')} </td>
		</tr>
	</table>
	
	<div class="thanks_message" style="margin-top:40px">
	<p>
	{t}Thanks again for trading with us, we really do appreciate your business :){/t}
	{*}
	Remember, order within 30 days to maintain your <a class="highlight" href="http://www.ancientwisdom.biz/gold-reward-scheme">Gold Reward</a> status.
	{*}
</p>

<p>
Ancient Wisdom Team 
</p>
<div>
	
</div>
