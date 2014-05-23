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
		
			<h2 >
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:-1px"> {$order->get('order customer name')}, <span class="id">C{$customer->get_formated_id()}</span> 
			</h2>
			<div class="address" >
				<div style="margin-bottom:5px">{t}Billing Address{/t}:</div> <b>{$customer->get('Customer Main Contact Name')}</b><br />
				{$customer->get('Customer XHTML Billing Address')} 
			</div>
			<div class="address" style="margin-left:15px">
				<div style="margin-bottom:5px">{t}Shipping Address{/t}:</div> {$order->get('Order XHTML Ship Tos')} 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
		<div id="totals">
			<table border="0" class="data_block">
				{if $order->get('Order Items Discount Amount')!=0 } 
				<tr>
					<td class="aright">{t}Items Gross{/t}</td>
					<td width="100" class="aright">{$order->get('Items Gross Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-{$order->get('Items Discount Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td width="100" class="aright">{$order->get('Items Net Amount')}</td>
				</tr>
				{if $order->get('Order Net Credited Amount')!=0 } 
				<tr>
					<td class="aright">{t}Credits{/t}</td>
					<td width="100" class="aright">{$order->get('Net Credited Amount')}</td>
				</tr>
				{/if} {if $order->get('Order Charges Net Amount')} 
				<tr>
					<td class="aright">{t}Charges{/t}</td>
					<td width="100" class="aright">{$order->get('Charges Net Amount')}</td>
				</tr>
				{/if} 
				<tr style="border-bottom:1px solid #777">
					<td class="aright">{t}Shipping{/t}</td>
					<td width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Net{/t}</td>
					<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				<tr style="border-bottom:1px solid #777">
					<td class="aright">{t}VAT{/t}</td>
					<td width="100" class="aright">{$order->get('Total Tax Amount')}</td>
				</tr>
				<tr>
					<td style="padding-top:3px" class="aright">{t}Total{/t}</td>
					<td style="padding-top:3px" width="100" class="aright"><b>{$order->get('Total Amount')}</b></td>
				</tr>
			</table>
		</div>
		
		<div style="clear:both">
		</div>
	</div>
	
	<div style="margin-top:20px">
	<h2>{t}Items{/t}</h2>  
			
			<div class="table_top_bar space">
			</div>
		
	        {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:95%">
			</div>
	</div>
	
	<div style="margin-top:20px">
	<span style="float:left;cursor:pointer" id="cancel_order" ><img src="art/bin.png" title="{t}Cancel order{/t}" alt="Cancel order"/> Clear order</span>
	</div>
	
</div>
