{include file='header.tpl'} 
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="parent_key" value="{$store->id}" />
<input type="hidden" id="parent" value="store" />
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}{t}Customers{/t} ({$store->get('Store Code')})</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons">
				<button style="height:25px;width:27px" onclick="window.location='customer_store_configuration.php?store={$store->id}'"><img style="position:relative;width:18px;height:18px;top:-2px" src="art/icons/cog.png" alt=""></button> {if $modify} <button id="new_customer"><img src="art/icons/add.png" alt=""> {t}Add Customer{/t}</button> <button onclick="window.location='edit_customers.php?store={$store->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Customers{/t}</button> {/if} <button onclick="window.location='customers_stats.php?store={$store->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button onclick="window.location='customers_lists.php?store={$store->id}'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button onclick="window.location='customer_categories.php?id=0&store={$store->id}'"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title"><img src="art/icons/agenda.png" style="height:18px;position:relative;bottom:2px"/> <span class="id">{$store->get('Store Code')}</span> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='dashboard'}selected{/if}" id="dashboard"> <span> {t}Dashboard{/t}</span></span></li>
			<li> <span class="item {if $block_view=='contacts'}selected{/if}" id="contacts"> <span> {t}Contacts{/t}</span></span></li>
			<li> <span class="item {if $block_view=='pending_orders'}selected{/if}" id="pending_orders"> <span> {t}Pending Orders{/t}</span></span></li>
			<li> <span class="item {if $block_view=='pending_post'}selected{/if}" id="pending_post"> <span> {t}Pending Post{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div style="padding:15px 0 30px 0;{if !($block_view=='dashboard') }display:none{/if}" id="dashboard_block">
			<div id="overview_all_contacts" style="margin:15px 0 10px 0">
				<div style="width:400px;float:left">
					<p style="padding:2px 10px;border-top:1px solid black;border-bottom:1px solid black">
						{$overview_all_contacts_text} 
					</p>
				</div>
				<div style="float:left;font-size:80%;text-align:center">
					<div style="margin-left:20px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
						{t}Total Contacts{/t} 
						<div style="font-size:120%;font-weight:800">
							{$store->get('Contacts')} 
						</div>
						<div style="margin-top:2px;color:#555">
							{t}Total with orders{/t} 
							<div style="font-size:120%">
								<span style="font-weight:800">{$store->get('Contacts With Orders')}</span> <span>({$store->get('Percentage Total With Orders')})</span> 
							</div>
						</div>
					</div>
					<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
						<a href="new_customers_list.php?store={$store->get('Store Key')}&active=1&auto=1">{t}Active Customers{/t} 
						<div style="font-size:120%;font-weight:800">
							{$store->get('Active Contacts')} 
						</div>
						</a> 
					</div>
					<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
						<a href="new_customers_list.php?store={$store->get('Store Key')}&lost=1&auto=1">{t}Lost Customers{/t} 
						<div style="font-size:120%;font-weight:800">
							{$store->get('Lost Contacts')} 
						</div>
						</a> 
					</div>
					<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
						<a href="new_customers_list.php?store={$store->get('Store Key')}&potential=1&auto=1">{t}Potential Customers{/t} 
						<div style="font-size:120%;font-weight:800">
							{$store->get('Potential Customers')} 
						</div>
						</a> 
					</div>
				</div>
				<div style="clear:both">
				</div>
			</div>
		</div>
		<div style="padding:15px 0 30px 0;{if !($block_view=='contacts')}display:none{/if}" id="contacts_block">
			<div style="clear:both">
				<span class="clean_table_title">{t}Customers List{/t} 
				<img id="export_customers" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif">
				</span> 
				<img style="float:right;margin-left:15px;cursor:pointer;position:relative;bottom:-7px;right:3px" id="customer_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="table_type" class="table_type">
					<div style="font-size:90%" id="customer_type_chooser">
						<div id="customer_activity_chooser" style="{if $elements_customers_elements_type!='activity'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_activity.Lost}selected{/if} label_all_contacts_lost" id="elements_Lost" table_type="lost">{t}Lost{/t} (<span id="elements_Lost_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_activity.Losing}selected{/if} label_all_contacts_losing" id="elements_Losing" table_type="losing">{t}Losing{/t} (<span id="elements_Losing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_activity.Active}selected{/if} label_all_contacts_active" id="elements_Active" table_type="active">{t}Active{/t} (<span id="elements_Active_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="customer_level_type_chooser" style="{if $elements_customers_elements_type!='level_type'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.VIP}selected{/if} label_customer_VIP" id="elements_VIP" table_type="VIP">{t}VIP{/t} (<span id="elements_VIP_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Partner}selected{/if} label_customer_Partner" id="elements_Partner" table_type="Partner">{t}Partner{/t} (<span id="elements_Partner_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Staff}selected{/if} label_customer_Staff" id="elements_Staff" table_type="Staff">{t}Staff{/t} (<span id="elements_Staff_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Normal}selected{/if} label_customer_Normal" id="elements_Normal" table_type="Normal">{t}Normal{/t} (<span id="elements_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="customer_orders_chooser">
							<span style="float:right;margin-left:2px;margin-right:10px" class=" table_type transaction_type state_details">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if  $orders_type=='contacts_with_orders'}selected{/if}" id="elements_orders_type_contacts_with_orders" table_type="contacts_with_orders" title="{t}Contacts with Orders{/t}">{t}With Orders{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $orders_type=='all_contacts'}selected{/if}" id="elements_orders_type_all_contacts" table_type="all_contacts" title="{t}All Contacts{/t}">{t}All{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 
						</div>
					</div>
				</div>
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $view=='general'}selected{/if}" id="general">{t}General{/t}</button> <button class="table_option {if $view=='contact'}selected{/if}" id="contact">{t}Contact{/t}</button> <button class="table_option {if $view=='address'}selected{/if}" id="address">{t}Address{/t}</button> <button class="table_option {if $view=='balance'}selected{/if}" id="balance">{t}Balance{/t}</button> <button class="table_option {if $view=='rank'}selected{/if}" id="rank">{t}Ranking{/t}</button> <button class="table_option {if $view=='weblog'}selected{/if}" id="weblog">{t}WebLog{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div style="padding:15px 0 30px 0;{if !($block_view=='pending_orders')  }display:none{/if}" id="pending_orders_block">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Pending Orders{/t} <img id="export_csv0" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div id="table_type" class="table_type">
					<div style="font-size:90%" id="transaction_chooser">
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Packed}selected{/if} label_Packed" id="elements_Packed" table_type="Packed">{t}Packed{/t} (<span id="elements_Packed_number">{$elements_number.Packed}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InWarehouse}selected{/if} label_InWarehouse" id="elements_InWarehouse" table_type="InWarehouse">{t}In Warehouse{/t} (<span id="elements_InWarehouse_number">{$elements_number.InWarehouse}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.SubmittedbyCustomer}selected{/if} label_SubmittedbyCustomer" id="elements_SubmittedbyCustomer" table_type="SubmittedbyCustomer">{t}Submitted by Customer{/t} (<span id="elements_SubmittedbyCustomer_number">{$elements_number.SubmittedbyCustomer}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcess}selected{/if} label_InProcess" id="elements_InProcess" table_type="InProcess">{t}In Process{/t} (<span id="elements_InProcess_number">{$elements_number.InProcess}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcessbyCustomer}selected{/if} label_InProcessbyCustomer" id="elements_InProcessbyCustomer" table_type="InProcessbyCustomer">{t}In Website{/t} (<span id="elements_InProcessbyCustomer_number">{$elements_number.InProcessbyCustomer}</span>)</span> 
					</div>
				</div>
				<div class="table_top_bar" style="margin-bottom:15px">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
				<div id="table1" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div style="padding:15px 0 30px 0;{if !($block_view=='pending_post')  }display:none{/if}" id="pending_post_block">
			<span class="clean_table_title">{t}Pending Post{/t} <img id="export_data" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"> <img src="art/pdf.gif" style="position:relative;top:-3px;left:10px;height:12px;cursor:pointer;" title="{t}Label Address{/t}" onclick="window.location='customers_address_label.pdf.php?label=l7159&type=send_post&id={$store->id}'"></span> 
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $pending_post_elements.Send}selected{/if} label_page_type" id="elements_Send">{t}Send{/t} (<span id="elements_Send_number">{$pending_post_elements_number.Send}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $pending_post_elements.ToSend}selected{/if} label_page_type" id="elements_ToSend">{t}To Send{/t} (<span id="elements_ToSend_number">{$pending_post_elements_number.ToSend}</span>)</span> 
			</div>
			<div class="table_top_bar" style="margin-bottom:10px">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
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
<div id="dialog_new_customer" style="padding:20px 20px 10px 20px ">
	<div id="new_customer_msg">
	</div>
	<div class="buttons">
		<button class="positive" onclick="new_customer()">{t}Manually{/t}</button> <button class="positive" onclick="new_customer_from_file()">{t}Import from file{/t}</button> <button class="negative" id="close_dialog_new_customer">{t}Cancel{/t}</button> 
	</div>
</div>
{include file='export_splinter.tpl' id='customers' export_fields=$export_customer_fields}
<div id="dialog_change_customers_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Customers group by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="customers_element_chooser_activity" style="float:none;margin:0px auto;min-width:120px" onclick="change_customers_element_chooser('activity')" class="{if $elements_customers_elements_type=='activity'}selected{/if}"> {t}Activity Status{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="customers_element_chooser_level_type" style="float:none;margin:0px auto;min-width:120px" onclick="change_customers_element_chooser('level_type')" class="{if $elements_customers_elements_type=='level_type'}selected{/if}"> {t}Type{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 