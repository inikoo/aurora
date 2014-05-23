{include file='header.tpl'} 
<div id="bd" style="background-image:url('art/stamp.cancel.en.png');background-repeat:no-repeat;background-position:300px 50px">
	{include file='orders_navigation.tpl'} 
	<input type="hidden" id="order_key" value="{$order->id}" />
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr; {$order->get('Order Public ID')} ({$order->get('Current Dispatch State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if isset($order_prev)}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title no_buttons">{t}Order{/t} <span >{$order->get('Order Public ID')}</span></span> 
		</div>
		<div class="buttons">
			{if isset($order_next)}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} 
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
			<table border="0"class="info_block" >
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
			{if $order->get_notes()} 
			<div class="notes">
				{ $order->get_notes()} 
			</div>
			{/if} 
			<table border="0" class="info_block">
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr>
					<td>{t}Cancel Date{/t}:</td>
					<td class="aright">{$order->get('Cancel Date')}</td>
				</tr>
			</table>
			{if {$order->get('Order Cancel Note')}!=''} 
			<div style="text-align:right;color:#b51616;margin-right:30px;zborder:1px solid black;clear:both">
				{t}Order Cancelled{/t}: {$order->get('Order Cancel Note')} 
			</div>
			{/if} 
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
{include file='footer.tpl'} 