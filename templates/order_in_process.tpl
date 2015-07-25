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
		<div class="top_page_menu " style="border:none">
			<div class="buttons" style="float:left">
				{if $order_prev.id}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title no_buttons">{t}Order{/t} <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span></span> 
			</div>
			{if $order_next.id}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} 
			<div class="buttons small" style="position:relative;top:5px">
				<button onclick="window.open('proforma.pdf.php?id={$order->id}')"><img style="width:40px;height:12px;vertical-align:1px" src="art/pdf.gif" alt=""> {t}Proforma{/t}</button> <button id="sticky_note_button"><img src="art/icons/note_pink.png" alt=""> {t}Note{/t}</button> <button style="display:none;height:24px;" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> 
				<button id="import_transactions" >{t}Import{/t}</button> 
				<button {if $order->get('Order Current Dispatch State')!='In Process by Customer'}style="display:none"{/if} onclick="window.location='order.php?id={$order->id}'" >{t}Exit Modify Order{/t}</button> <button style="{if $order->has_products_without_parts()}display:none{/if}" class="{if {$order->get('Order Number Products')}==0}disabled{/if}" id="done"><img id="send_to_warehouse_img" src="art/icons/cart_go.png" alt=""> {t}Send to Warehouse{/t}</button> <button title="{t}Some products in the order don't have parts associated{/t}" style="{if !$order->has_products_without_parts()}display:none{/if}" class="disabled" id="done_error_order_with_products_without_parts"><img id="send_to_warehouse_img" src="art/icons/exclamation.png" alt=""> {t}Send to Warehouse{/t}</button> 
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
						<div id="title_billing_address" style="border-bottom:1px solid #ccc;margin-bottom:5px">
							{t}Billing to{/t}: 
						</div>
						<div id="billing_address">
							{$order->get('Order XHTML Billing Tos')} 
						</div>
						<div class="buttons small left">
							<button id="change_billing_address" class="state_details" style="display:block;margin-top:10px">{t}Change{/t}</button> 
						</div>
					</div>
					<div style="float:left;margin:5px 0 0 0px;color:#444;font-size:90%;width:140px">
						<div id="title_delivery_address" style="border-bottom:1px solid #ccc;{if $order->get('Order For Collection')=='Yes'}display:none;{/if};margin-bottom:5px">
							{t}Delivering to{/t}: 
						</div>
						<div id="title_for_collection" style="border-bottom:1px solid #ccc;{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-bottom:5px">
							&nbsp; 
						</div>
						<div class="address_box" id="delivery_address">
							{$order->get('Order XHTML Ship Tos')} 
						</div>
						<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none{/if};margin-top:10px" class="buttons left small">
							<button id="change_delivery_address">{t}Change{/t}</button> <br />
							<button style="margin-top:3px;{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Set for collection{/t}</button> 
						</div>
						<div id="for_collection" style="{if $order->get('Order For Collection')=='No' or $order->get('Order Invoiced')=='Yes'}display:none;{/if};margin-top:10px" class="buttons left small">
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
							<td>{t}Created{/t}:</td>
							<td class="aright">{$order->get('Created Date')}</td>
						</tr>
						{if $order->get('Order Current Dispatch State')=='In Process by Customer' } 
						<tr>
							<td>{t}Last updated{/t}:</td>
							<td class="aright">{$order->get('Last Updated Date')}</td>
						</tr>
						<tr style="border-top:1px solid #ccc">
							<td>{t}On website{/t}:</td>
							<td class="aright">{$order->get('Interval Last Updated Date')}</td>
						</tr>
						{elseif $order->get('Order Current Dispatch State')=='Waiting for Payment Confirmation'} 
						<tr>
							<td>{t}Submit Payment{/t}:</td>
							<td class="aright">{$order->get('Checkout Submitted Payment Date')}</td>
						</tr>
						{else} 
						<tr>
							<td>{t}Last updated{/t}:</td>
							<td class="aright">{$order->get('Last Updated Date')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Payment Method{/t}:</td>
							<td class="aright">{$order->get_formated_payment_state()}</td>
						</tr>
					</table>
					<div style="{if $customer->get('Sticky Note')==''}display:none{/if}">
						{t}Customer's Sticky Note{/t} 
						<div id="sticky_note_div" class="sticky_note" style="margin:0;">
							<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
								{$customer->get('Sticky Note')} 
							</div>
						</div>
					</div>
					<div id="deals_div" style="clear:both">
						{include file='order_deals_splinter.tpl'} 
					</div>
					<div id="vouchers_div">
						{include file='order_vouchers_splinter.tpl' modify_voucher=true} 
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
		<li> <span class="item {if $block_view=='items'}selected{/if}" id="items"> <span> {t}Order Items{/t} (<span style="display:inline;padding:0px" id="ordered_products_number">{$order->get('Number Products')}</span>)</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0px 20px">
		<div class="data_table" style="clear:both;margin-top:15px;{if $block_view!='items'}display:none{/if}" id="items_block">
			<span id="table_title_items" class="clean_table_title">{t}Items{/t}</span> 
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
			<div id="import_msg_div_bis" style="float:right;display:none" ><img id="import_msg_ok_bis" src='art/icons/accept.png'> <span id="import_msg_bis"></span></div>
 
			<div id="products_lookups">
				<div class="buttons small">
					<button id="lookup_family" onclick="lookup_family()">{t}Lookup Family{/t}</button> 
					<input id="lookup_family_query" style="width:100px;float:right" value="{$lookup_family}" />
					<span id="clear_lookup_family" onclick="clear_lookup_family()" style="cursor:pointer;float:right;margin-right:5px;color:#777;font-style:italic;font-size:80%;{if $lookup_family==''}display:none{/if}">{t}Clear{/t}</span> <button id="show_all_products" onclick="clear_lookup_family()" style="margin-right:50px">{t}Display all products{/t}</button> 
				</div>
			</div>
			<div class="table_top_bar space">
			</div>
			<div id="list_options1" style="display:none">
				<table style="float:left;margin:0 0 5px 0px ;padding:0" class="options">
					<tr>
						<td class="{if $products_view=='general'}selected{/if}" id="general">{t}General{/t}</td>
						<td class="{if $products_view=='stock'}selected{/if}" id="stock">{t}Discounts{/t}</td>
						<td class="{if $products_view=='sales'}selected{/if}" id="sales">{t}Properties{/t}</td>
					</tr>
				</table>
				<table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $products_view!='sales' };display:none{/if}" class="options_mini">
					<tr>
						<td class="{if $products_period=='all'}selected{/if}" period="all" id="period_all">{t}All{/t}</td>
						<td class="{if $products_period=='year'}selected{/if}" period="year" id="period_year">{t}1Yr{/t}</td>
						<td class="{if $products_period=='quarter'}selected{/if}" period="quarter" id="period_quarter">{t}1Qtr{/t}</td>
						<td class="{if $products_period=='month'}selected{/if}" period="month" id="period_month">{t}1M{/t}</td>
						<td class="{if $products_period=='week'}selected{/if}" period="week" id="period_week">{t}1W{/t}</td>
					</tr>
				</table>
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
		
		<div id="order_deal_bonus" style="font-size:85%;clear:both;padding-top:5px;{if !$order->has_deal_with_bonus() }display:none{/if};border:0px solid red">
			{include file='order_deal_bonus_splinter.tpl' order=$order} 
		</div>
		<div style="clear:both;padding-top:10px">
			{foreach from=$order->get_insurances() item=insurance} 
			<div class="insurance_row">
				{$insurance['Insurance Description']} (<b>{$insurance['Insurance Formated Net Amount']}</b>) <span style="widht:100px"> <img insurance_key="{$insurance['Insurance Key']}" onptf_key="{$insurance['Order No Product Transaction Fact Key']}" id="insurance_checked_{$insurance['Insurance Key']}" onclick="remove_insurance(this)" style="{if !$insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_checked.png" /> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_unchecked_{$insurance['Insurance Key']}" onclick="add_insurance(this)" style="{if $insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_unchecked.png" /> </span> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_wait_{$insurance['Insurance Key']}" style="display:none" class="checkbox" src="art/loading.gif" /> 
			</div>
			{/foreach} 
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