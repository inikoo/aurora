{include file='header.tpl'} 
<input type="hidden" id="session_data" value="{$session_data}"  />
<input type="hidden" id="order_shipping_method" value="{$order->get('Order Shipping Method')}"  />
<input type="hidden" id="store_id" value="{$store->id}"  />
<input type="hidden" id="store_key" value="{$store->id}"  />
<input type="hidden" id="order_key" value="{$order->id}"  />
<input type="hidden" id="dispatch_state" value="{$order->get('Order Current Dispatch State')}"  />
<input type="hidden" id="customer_key" value="{$order->get('Order Customer Key')}"  />
<input type="hidden" id="referral" value="{$referral}"  />
<input type="hidden" id="products_display_type" value="{$block_view}"  />
<input type="hidden" id="lookup_family" value="{$lookup_family}"  />

<input type="hidden" id="default_country_2alpha" value="{$store->get('Store Home Country Code 2 Alpha')}" />
<input type="hidden" id="items_table_index" value="0" />
<input type="hidden" id="currency_code" value="{$order->get('Order Currency')}" />
<input type="hidden" id="decimal_point" value="{$decimal_point}"  />
<input type="hidden" id="thousands_sep" value="{$thousands_sep}"  />
<input type="hidden" id="to_pay_label_amount" value="{$order->get('Order To Pay Amount')}"> 
<input type="hidden" id="session_data" value="{$session_data}"  />
<input type="hidden" id="subject" value="order" />
<input type="hidden" id="subject_key" value="{$order->id}" />
<input type="hidden" id="history_table_id" value="3"> 
<div id="bd" class="no_padding">
	<div style="padding:0px 20px">
		{include file='assets_navigation.tpl'} 
		
		
		<div class="branch ">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $referral=='spo'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> &rarr; {/if} <a href="store_pending_orders.php?id={$store->id}">{t}Pending Orders{/t} ({$store->get('Store Code')})</a> {else if $referral=='po'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> {/if} {else}{if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> {/if} &rarr; {$order->get('Order Public ID')}</span> 
		</div>
		<div class="top_page_menu" style="border:none">
			<div class="buttons" style="float:left">
				<span class="main_title" style="position:relative;top:3px">{t}Amending Order{/t} <class>{$order->get('Order Public ID')}</span> ({$order->get_formated_dispatch_state()})</span> 
			</div>
			<div class="buttons small" style="position:relative;top:5px">
				{*} <button style="height:24px;" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> {*} 
				<button id="exit_modify_order">{t}Exit Modify Order{/t}</button> <button style="display:none" id="cancel" class="negative">{t}Cancel Order{/t}</button> 
				<button id="import_transactions" >{t}Import{/t}</button> 
				<button id="sticky_note_button"><img src="art/icons/note_pink.png" alt=""> {t}Note{/t}</button> 

			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="clear:both">
		</div>
		<div id="control_panel">
		<div class="content">
			<div id="addresses">
				<h2 style="padding:0">
					<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> <span id="customer_name">{$order->get('Order Customer Name')}</span> <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
				</h2>
				<h3 id="customer_contact_name">
					{$order->get('Order Customer Contact Name')} 
				</h3>
				<div style="float:left;margin:5px 20px 0 0;color:#444;font-size:90%;width:140px">
					<span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>: 
					<div style="margin-top:5px" id="billing_address">
						{$order->get('Order XHTML Billing Tos')} 
					</div>
					<div class="buttons small left" style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}">
						<button id="change_billing_address" class="state_details" style="display:block;margin-top:10px">{t}Change{/t}</button> 
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
					<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes' or $order->get('Order Invoiced')=='Yes'}display:none{/if};margin-top:2px" class="buttons left small">
						<button id="change_delivery_address">{t}Change{/t}</button> <br />
						<button style="margin-top:3px;{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Set for collection{/t}</button> 
					</div>
					<div id="for_collection" style="{if $order->get('Order For Collection')=='No' or $order->get('Order Invoiced')=='Yes'}display:none;{/if};margin-top:2px" class="buttons left small">
						<button id="set_for_shipping" class="state_details" onclick="change_shipping_type('No')">{t}Set for delivery{/t}</button> 
					</div>
				</div>
			</div>
			<div id="totals">
							{include file='order_totals_splinter.tpl'} {include file='order_sticky_note_splinter.tpl'} 
			</div>
			<div id="dates">
				{if $order->get_notes()} 
				<div class="notes" style="border:1px solid #ccc;padding:5px;margin-bottom:5px">
					{$order->get_notes()} 
				</div>
				{/if} 
				<table border="0" class="info_block">
					<tr>
						<td>{t}Order Date{/t}:</td>
						<td class="aright">{$order->get('Date')}</td>
					</tr>
					<tr>
						<td>{t}Payment Method{/t}:</td>
						<td class="aright">{$order->get_formated_payment_state()}</td>
					</tr>
				</table>
				
				<div id="deals_div" style="clear:both">
						{include file='order_deals_splinter.tpl'} 

					</div>
					<div id="vouchers_div">
						{include file='order_vouchers_splinter.tpl' modify_voucher=false} 
					</div>
				
			</div>
			<div style="clear:both">
			</div>
			</div>
			{include file='order_more_info_spliner.tpl'} 
			
		</div>
	</div>
	<div id="payments_list">
		{include file='order_payments_splinter.tpl'} 
	</div>
	<ul class="tabs" id="items_chooser" >
		<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"> <span> {t}Products{/t} (<span style="display:inline;padding:0px" id="all_products_number">{$store->get_formated_products_for_sale()}</span>)</span></span></li>
		<li> <span class="item {if $block_view=='items'}selected{/if}" id="items"> <span> {t}Order Items{/t} (<span style="display:inline;padding:0px" id="ordered_products_number">{$order->get('Number Items')}</span>)</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0px 20px">
		<div class="data_table" style="clear:both;margin-top:15px;{if $block_view!='items'}display:none{/if}" id="items_block">
			<span id="table_title_items" class="clean_table_title" >{t}Items{/t}</span>
			<div id="import_msg_div" style="float:right;display:none" ><img id="import_msg_ok" src='art/icons/accept.png'> <span id="import_msg"></span></div> 
			<div class="table_top_bar space">
			</div>
			<div id="list_options0" style="display:none">
				<table style="float:left;margin:0 0 5px 0px ;padding:0" class="options">
					<tr>
						<td class="{if $items_view=='general'}selected{/if}" id="general">{t}General{/t}</td>
						<td class="{if $items_view=='stock'}selected{/if}" id="stock">{t}Discounts{/t}</td>
						<td class="{if $items_view=='sales'}selected{/if}" id="sales">{t}Properties{/t}</td>
					</tr>
				</table>
				<table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $items_view!='sales' };display:none{/if}" class="options_mini">
					<tr>
						<td class="{if $items_period=='all'}selected{/if}" period="all" id="period_all">{t}All{/t}</td>
						<td class="{if $items_period=='year'}selected{/if}" period="year" id="period_year">{t}1Yr{/t}</td>
						<td class="{if $items_period=='quarter'}selected{/if}" period="quarter" id="period_quarter">{t}1Qtr{/t}</td>
						<td class="{if $items_period=='month'}selected{/if}" period="month" id="period_month">{t}1M{/t}</td>
						<td class="{if $items_period=='week'}selected{/if}" period="week" id="period_week">{t}1W{/t}</td>
					</tr>
				</table>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
		<div class="data_table" style="clear:both;margin-top:15px;{if $block_view!='products'}display:none{/if}" id="products_block">
			<span id="table_title_products" class="clean_table_title">{t}Products{/t}</span> 
			<div id="products_lookups">
				<div class="buttons small">
					<button id="lookup_family" onclick="lookup_family()">{t}Lookup Family{/t}</button> 
					<input id="lookup_family_query" style="width:100px;float:right" value="{$lookup_family}" />
					<span id="clear_lookup_family" onclick="clear_lookup_family()" style="cursor:pointer;float:right;margin-right:5px;color:#777;font-style:italic;font-size:80%;{if $lookup_family==''}display:none{/if}">{t}Clear{/t}</span> <button id="show_all_products" onclick="show_all_products()" style="margin-right:50px">{t}Display all products{/t}</button> 
				</div>
			</div>
			<div id="import_msg_div_bis" style="float:right;display:none" ><img id="import_msg_ok_bis" src='art/icons/accept.png'> <span id="import_msg_bis"></span></div>
			<div class="table_top_bar space">
			</div>
			
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='add_payment_splinter.tpl' subject='order'} {include file='order_not_dispatched_dialogs_splinter.tpl'}  {include file='notes_splinter.tpl'} {include file='footer.tpl'} 