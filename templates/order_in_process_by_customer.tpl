{include file='header.tpl'} 
<div id="bd" >
	{include file='orders_navigation.tpl'} 
	<input type="hidden" id="order_key" value="{$order->id}" />
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr; {$order->get('Order Public ID')} ({$order->get_formated_dispatch_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Order{/t} <span class="id">{$order->get('Order Public ID')}</span></span> 
		</div>
		<div class="buttons">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="position:relative;border:1px solid #ccc;text-align:left;padding:10px;margin: 5px 0 10px 0">
		<div style="border:0px solid #ddd;width:400px;float:left">
			<h2 style="padding:0">
				{$order->get('order customer name')} <a class="id" href="customer.php?id={$order->get('order customer key')}">{$customer->get_formated_id()}</a> 
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
				<span style="font-weight:500;color:#000;display:block;margin-bottom:2px">{t}Contact Address{/t}:</span> <b>{$customer->get('Customer Main Contact Name')}</b><br />
				{$customer->get('Customer Main XHTML Address')} 
			</div>
			<div style="float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
				<span style="font-weight:500;color:#000;display:block;margin-bottom:2px">{t}Shipping Address{/t}:</span> {$order->get('Order XHTML Ship Tos')} 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="border:0px solid #ddd;width:190px;float:right">
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
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
					<td class="aright">{t}Total{/t}</td>
					<td width="100" class="aright"><b>{$order->get('Total Amount')}</b></td>
				</tr>
			</table>
		</div>
		
		<div style="clear:both">
		</div>
	</div>
	<h2>
		{t}Items{/t} 
	</h2>
	<div id="table0" class="dtable btable" style="margin-bottom:0;font-size:80%">
	</div>
</div>

{include file='footer.tpl'} 