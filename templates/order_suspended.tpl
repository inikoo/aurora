{include file='header.tpl'}
<input type="hidden" id="session_data" value="{$session_data}"  />
 
	<input type="hidden" id="order_key" value="{$order->id}" />
	<input type="hidden" id="subject_key" value="{$order->id}"  />
	<input type="hidden" id="subject"  value="order" />
	<input type="hidden" id="history_table_id" value="3"> 
	<input type="hidden" id="customer_key" value="{$order->get('Order Customer Key')}"  />

<div id="bd" style="background-image:url('art/stamp.suspended.en.png');background-repeat:no-repeat;background-position:300px 50px">
	{include file='orders_navigation.tpl'} 
	<div class="branch ">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $referral=='spo'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> &rarr; {/if} <a href="store_pending_orders.php?id={$store->id}">{t}Pending Orders{/t} ({$store->get('Store Code')})</a> {else if $referral=='po'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> {/if} {else}{if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> {/if} &rarr; {$order->get('Order Public ID')}</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if $order_prev.id}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title no_buttons">{t}Order{/t} <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span></span> 
		</div>
			{if $order_next.id}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} 

			<div class="buttons small" style="position:relative;top:5px">
			<button id="activate" class="positive">{t}Activate Order{/t}</button> 
			
			<button id="cancel" class="negative">{t}Cancel Order{/t}</button> 
							<button id="sticky_note_button"><img src="art/icons/note_pink.png" alt=""> {t}Note{/t}</button> 

		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="control_panel">
		<div class="content">
		<div id="addresses">
			<h2 style="padding:0">
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> <span id="customer_name">{$order->get('Order Customer Name')}</span> <a class="id" href="customer.php?id={$order->get('order customer key')}">{$customer->get_formated_id()}</a> 
			</h2>
			<h3 id="customer_contact_name">
					{$order->get('Order Customer Contact Name')} 
				</h3>
			<div style="float:left;margin:5px 20px 0 0;color:#444;font-size:90%;width:140px">
				<span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>: 
				<div style="margin-top:5px" id="billing_address">
					{$order->get('Order XHTML Billing Tos')} 
				</div>
				
			</div>
			<div style="float:left;margin:5px 0 0 0px;color:#444;font-size:90%;width:140px">
				<div id="title_delivery_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if};margin-bottom:5px">
					{t}Delivery Address{/t}: 
				</div>
				<div id="title_for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-bottom:5px">
					<b>{t}For collection{/t}</b> 
				</div>
				<div class="address_box" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="totals">
			
				{include file='order_totals_splinter.tpl'} {include file='order_sticky_note_splinter.tpl'} 

		</div>
		<div id="dates">
			{if $order->get_notes()} 
			<div class="notes">
				{$order->get_notes()} 
			</div>
			{/if} 
			<table border="0" style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right">
				<tr>
					<td>{t}Created{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr>
					<td>{t}Suspended{/t}:</td>
					<td class="aright">{$order->get('Suspended Date')}</td>
				</tr>
			</table>
			<div style="text-align:right;color:#b51616;margin-right:30px;zborder:1px solid black;clear:both">
				{$order->get('Order Cancel Note')} 
			</div>
		</div>
		<div style="clear:both">
		</div>
		</div>
		{include file='order_more_info_spliner.tpl'} 
	</div>
	{include file='order_payments_splinter.tpl'} 
	<div style="margin-top:20px">
		<span id="table_title_items" class="clean_table_title" ">{t}Items{/t}</span> 
		<div class="table_top_bar space">
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
 {include file='notes_splinter.tpl'}
{include file='footer.tpl'} 