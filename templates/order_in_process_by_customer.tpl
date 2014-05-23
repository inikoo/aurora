{include file='header.tpl'} 
<div id="bd" >
	{include file='orders_navigation.tpl'} 
	<input type="hidden" id="order_key" value="{$order->id}" />
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr; {$order->get('Order Public ID')} ({$order->get_formated_dispatch_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Order{/t} <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span></span> 
		</div>
		<div class="buttons">
					 <button onclick="window.location='order.php?id={$order->id}&modify=1'"> {t}Modify Order{/t}</button> 

		 <button id="cancel" class="negative">{t}Cancel Order{/t}</button> 
	
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2 style="padding:0">
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px">  {$order->get('order customer name')} <a class="id" href="customer.php?id={$order->get('order customer key')}">{$customer->get_formated_id()}</a> 
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
		<div id="totals">
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
		<div id="dates">
				
				<table border="0" class="info_block">
					<tr>
						<td>{t}Created{/t}:</td>
						<td class="aright">{$order->get('Date')}</td>
					</tr>
					<tr>
						<td>{t}Last updated{/t}:</td>
						<td class="aright">{$order->get('Last Updated Date')}</td>
					</tr>
					<tr style="display:none">
						<td></td>
						<td class="aright">{$order->get('Interval Last Updated Date')}</td>
					</tr>
					
					
					
				</table>
			</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:20px">
	<span id="table_title_items" class="clean_table_title" ">{t}Items{/t}</span>  
			
			<div class="table_top_bar">
			</div>
			<div class="clusters">
					<div id="table_view_menu1" >
						<div class="buttons small left cluster">
							<button class="table_option {if $items_view=='basket'}selected{/if}" id="items_basket">{t}Basket{/t}</button> 
							<button class="table_option {if $items_view=='times'}selected{/if}" id="items_times">{t}Order times{/t}</button> 
						</div>
					
					</div>
					
					<div style="clear:both">
					</div>
				</div>
	{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:80%">
			</div>
	</div>
</div>

<div id="dialog_cancel" style="padding:15px 20px 5px 10px;width:200px">
	<div id="cancel_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2">{t}Cancel Order{/t}</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2">{t}Reason of cancellation{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea> </td>
		</tr>
		<tr id="cancel_buttons">
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save('cancel')" id="cancel_save" class="positive disabled">{t}Continue{/t}</button> <button class="negative" onclick="close_dialog('cancel')">{t}Go Back{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="cancel_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	</table>
</div>

{include file='footer.tpl'} 