 
<input type="hidden" id="order_key" value="{$order->id}" />
<input type="hidden" id="label_code" value="{t}Code{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_quantity" value="{t}Quantity{/t}" />
<input type="hidden" id="label_gross" value="{t}Amount{/t}" />
<input type="hidden" id="label_discount" value="{t}Discount{/t}" />
<input type="hidden" id="label_to_charge" value="{t}To Charge{/t}" />
<input type="hidden" id="label_net" value="{t}Net{/t}" />
<div id="order_container">
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
	<div style="margin-top:20px">
		<h2>
			{t}Items{/t}
		</h2>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
		<div id="table0" class="data_table_container dtable btable" style="font-size:95%">
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
		<tr >
			<td class="hidden"> </td>
			<td class="total_tr total_balance aright description"> {t}Total{/t} </td>
			<td class="total_tr total_balance aright total"> {$order->get('Balance Total Amount')} </td>
		</tr>
		
	</table>
	<div style="margin-top:20px">
		<span style="float:left;cursor:pointer" id="cancel_order"><img src="art/bin.png" title="{t}Cancel order{/t}" alt="Cancel order" /> {t}Clear order{/t} <span id="cancel_order_info" style="display:none">, {t}your order will be cancelled{/t} <img id="cancel_order_img" style="height:16px;position:relative;bottom:-2px" "cancel_order_img" style="height:16px" src="art/emotion_sad.png"></span></span> 
		<div class="buttons right">
			<button onclick="location.href='checkout.php'" class="positive">{t}Go to Checkout{/t}</button>
		</div>
		
	</div>
	
	
</div>
