{include file='header.tpl' } 
<input type="hidden" id="link_extra_argument" value="&store={$store->id}" />
<input type="hidden" id="block_view" value="{$block_view}" />

<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="store_id" value="{$store->id}" />
<input type="hidden" id="from" value="{$from}" />
<input type="hidden" id="to" value="{$to}" />
<input type="hidden" id="orders_table_id" value="0" />
<input type="hidden" id="invoices_table_id" value="1" />
<input type="hidden" id="dn_table_id" value="2" />
<input type="hidden" id="calendar_id" value="{$calendar_id}" />
<input type="hidden" id="subject" value="orders" />

<div id="bd" class="no_padding">
	<div id="navigation"></div> 
	
	
	<div id="calendar_container" style="clear:both;margin-top:10px;padding-top:10px">
		<div id="period_label_container" style="{if $period==''}display:none{/if}">
			<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
		</div>
		{include file='calendar_splinter.tpl' } 
		<div style="clear:both">
		</div>
	</div>
	<div id="blocks" style="padding:0 20px;padding-bottom:30px;clear:both;margin-top:10px">
		<div id="block_orders" class="block  {if $block_view!='orders'}hide{/if}">
			<span class="clean_table_title">{t}Orders{/t} <img id="export_orders" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"> </span> 
			<div class="elements_chooser">
				<img class="menu" id="order_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="order_dispatch_chooser" style="{if $elements_order_elements_type!='dispatch'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.Cancelled}selected{/if} label_elements_dispatch_Cancelled" id="elements_order_dispatch_Cancelled" table_type="Cancelled">{t}Cancelled{/t} (<span id="elements_order_dispatch_Cancelled_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.Suspended}selected{/if} label_elements_dispatch_Suspended" id="elements_order_dispatch_Suspended" table_type="Suspended">{t}Suspended{/t} (<span id="elements_order_dispatch_Suspended_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.Dispatched}selected{/if} label_elements_dispatch_Dispatched" id="elements_order_dispatch_Dispatched" table_type="Dispatched">{t}Dispatched{/t} (<span id="elements_order_dispatch_Dispatched_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.Warehouse}selected{/if} label_elements_dispatch_Warehouse" id="elements_order_dispatch_Warehouse" table_type="Warehouse">{t}Warehouse{/t} (<span id="elements_order_dispatch_Warehouse_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.InProcess}selected{/if} label_elements_dispatch_InProcess" id="elements_order_dispatch_InProcess" table_type="InProcess">{t}In Process{/t} (<span id="elements_order_dispatch_InProcess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_dispatch.InProcessCustomer}selected{/if} label_elements_dispatch_InProcessCustomer" id="elements_order_dispatch_InProcessCustomer" table_type="InProcessCustomer">{t}Shopping Cart{/t} (<span id="elements_order_dispatch_InProcessCustomer_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="order_type_chooser" style="{if $elements_order_elements_type!='type'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_type.Other}selected{/if} label_elements_type_Other" id="elements_order_type_Other" table_type="Other">{t}Other{/t} (<span id="elements_order_type_Other_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_type.Donation}selected{/if} label_elements_type_Donation" id="elements_order_type_Donation" table_type="Donation">{t}Donation{/t} (<span id="elements_order_type_Donation_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_type.Sample}selected{/if} label_elements_type_Sample" id="elements_order_type_Sample" table_type="Sample">{t}Sample{/t} (<span id="elements_order_type_Sample_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_type.Order}selected{/if} label_elements_type_Order" id="elements_order_type_Order" table_type="Order">{t}Order{/t} (<span id="elements_order_type_Order_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="order_source_chooser" style="{if $elements_order_elements_type!='source'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Internet}selected{/if} label_elements_source_Internet" id="elements_order_source_Internet" table_type="Internet">{t}Internet{/t} (<span id="elements_order_source_Internet_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Call}selected{/if} label_elements_source_Call" id="elements_order_source_Call" table_type="Call">{t}Call{/t} (<span id="elements_order_source_Call_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Store}selected{/if} label_elements_source_Store" id="elements_order_source_Store" table_type="Store">{t}Store{/t} (<span id="elements_order_source_Store_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Email}selected{/if} label_elements_source_Email" id="elements_order_source_Email" table_type="Email">{t}Email{/t} (<span id="elements_order_source_Email_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Fax}selected{/if} label_elements_source_Fax" id="elements_order_source_Fax" table_type="Fax">{t}Fax{/t} (<span id="elements_order_source_Fax_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_source.Other}selected{/if} label_elements_source_Other" id="elements_order_source_Other" table_type="Other">{t}Other{/t} (<span id="elements_order_source_Other_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="order_payment_chooser" style="{if $elements_order_elements_type!='payment'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_payment.Paid}selected{/if} label_elements_payment_Paid" id="elements_order_payment_Paid" table_type="Paid">{t}Paid{/t} (<span id="elements_order_payment_Paid_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_payment.PartiallyPaid}selected{/if} label_elements_payment_PartiallyPaid" id="elements_order_payment_PartiallyPaid" table_type="PartiallyPaid">{t}Partially Paid{/t} (<span id="elements_order_payment_PartiallyPaid_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_payment.WaitingPayment}selected{/if} label_elements_payment_WaitingPayment" id="elements_order_payment_WaitingPayment" table_type="WaitingPayment">{t}Waiting Payment{/t} (<span id="elements_order_payment_WaitingPayment_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_payment.NA}selected{/if} label_elements_payment_NA" id="elements_order_payment_NA" table_type="NA">{t}No Applicable{/t} (<span id="elements_order_payment_NA_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_order_payment.Unknown}selected{/if} label_elements_payment_Unknown" id="elements_order_payment_Unknown" table_type="Unknown">{t}Unknown{/t} (<span id="elements_order_payment_Unknown_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
			</div>
			<div id="list_options0">
				<div class="table_top_bar space">
				</div>
				<div>
					<table style="float:left;margin:0 0 0 0px ;padding:0;clear:left" class="options_mini">
					</table>
				</div>
				{*} 
				<div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">
					<form action="orders.php?" method="GET" style="margin-top:10px">
						<div style="position:relative;left:18px">
							<span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}</span> {t}Interval{/t}: 
							<input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}" />
							<img style="height:14px;bottom:1px;left:-15px;" id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <span class="calpop" style="margin-left:4px">&rarr;</span> 
							<input class="calpop" id="v_calpop2" size="11" maxlength="10" type="text" class="text" size="8" name="to" value="{$to}" />
							<img style="height:14px;bottom:1px;left:-33px;" id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval" alt="{t}Go{/t}" /> 
						</div>
					</form>
					<div id="cal1Container" style="position:absolute;display:none; z-index:2">
					</div>
					<div style="position:relative;right:-80px">
						<div id="cal2Container" style="display:none; z-index:2;position:absolute">
						</div>
					</div>
				</div>
				{*} 
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_invoices" class="block  {if $block_view!='invoices'}hide{/if}">
			<span class="clean_table_title">{t}Invoices{/t} <img id="export_invoices" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"> </span> 
			<div class="elements_chooser">
				<img class="menu" id="invoice_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="invoice_type_chooser" style="{if $elements_invoice_elements_type!='type'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Invoice}selected{/if} label_elements_type_Invoice" id="elements_invoice_type_Invoice" table_type="Invoice">{t}Invoices{/t} (<span id="elements_invoice_type_Invoice_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Refund}selected{/if} label_elements_type_Refund" id="elements_invoice_type_Refund" table_type="Refund">{t}Refunds{/t} (<span id="elements_invoice_type_Refund_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="invoice_payment_chooser" style="{if $elements_invoice_elements_type!='payment'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Yes}selected{/if} label_elements_payment_Yes" id="elements_invoice_payment_Yes" table_type="Yes">{t}Paid{/t} (<span id="elements_invoice_payment_Yes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Partially}selected{/if} label_elements_payment_Partially" id="elements_invoice_payment_Partially" table_type="Partially">{t}Partially Paid{/t} (<span id="elements_invoice_payment_Partially_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.No}selected{/if} label_elements_payment_No" id="elements_invoice_payment_No" table_type="No">{t}Waiting Payment{/t} (<span id="elements_invoice_payment_No_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="block_dn" class="block  {if $block_view!='dn'}hide{/if}">
			<span class="clean_table_title">{t}Delivery Note List{/t} <img id="export_dn" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"> </span> 
			<div class="elements_chooser">
				<img class="menu" id="dn_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="dn_type_chooser" style="{if $elements_dn_elements_type!='type'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Order}selected{/if} label_elements_type_Order" id="elements_dn_type_Order" table_type="Order">{t}Orders{/t} (<span id="elements_dn_type_Order_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Sample}selected{/if} label_elements_type_Sample" id="elements_dn_type_Sample" table_type="Sample">{t}Samples{/t} (<span id="elements_dn_type_Sample_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Donation}selected{/if} label_elements_type_Donation" id="elements_dn_type_Donation" table_type="Donation">{t}Donations{/t} (<span id="elements_dn_type_Donation_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Replacements}selected{/if} label_elements_type_Replacements" id="elements_dn_type_Replacements" table_type="Replacements">{t}Replacements{/t} (<span id="elements_dn_type_Replacements_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Shortages}selected{/if} label_elements_type_Shortages" id="elements_dn_type_Shortages" table_type="Shortages">{t}Shortages{/t} (<span id="elements_dn_type_Shortages_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="dn_dispatch_chooser" style="{if $elements_dn_elements_type!='dispatch'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Ready}selected{/if} label_elements_dispatch_Ready" id="elements_dn_dispatch_Ready" table_type="Ready">{t}Ready{/t} (<span id="elements_dn_dispatch_Ready_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Picking}selected{/if} label_elements_dispatch_Picking" id="elements_dn_dispatch_Picking" table_type="Picking">{t}Picking{/t} (<span id="elements_dn_dispatch_Picking_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Packing}selected{/if} label_elements_dispatch_Packing" id="elements_dn_dispatch_Packing" table_type="Packing">{t}Packing{/t} (<span id="elements_dn_dispatch_Packing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Done}selected{/if} label_elements_dispatch_Done" id="elements_dn_dispatch_Done" table_type="Done">{t}Done{/t} (<span id="elements_dn_dispatch_Done_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Send}selected{/if} label_elements_dispatch_Send" id="elements_dn_dispatch_Send" table_type="Send">{t}Send{/t} (<span id="elements_dn_dispatch_Send_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Returned}selected{/if} label_elements_dispatch_Returned" id="elements_dn_dispatch_Returned" table_type="Returned">{t}Returned{/t} (<span id="elements_dn_dispatch_Returned_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_payments" class="block  {if $block_view!='payments'}hide{/if}">
			<span class="clean_table_title">{t}Payments{/t} <img style="display:none" class="export_data_link" id="export_csv0" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div id="table_view_menu0">
					<div class="buttons small left cluster">
					</div>
				</div>
				<div class="buttons small cluster group">
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
			<div id="table3" class="data_table_container dtable btable" style="font-size:85%">
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
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_change_orders_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Group orders by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="orders_element_chooser_dispatch" style="float:none;margin:0px auto;min-width:120px" onclick="change_orders_element_chooser('dispatch')" class="{if $elements_order_elements_type=='dispatch'}selected{/if}"> {t}Dispatch{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="orders_element_chooser_source" style="float:none;margin:0px auto;min-width:120px" onclick="change_orders_element_chooser('source')" class="{if $elements_order_elements_type=='source'}selected{/if}"> {t}Source{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="orders_element_chooser_type" style="float:none;margin:0px auto;min-width:120px" onclick="change_orders_element_chooser('type')" class="{if $elements_order_elements_type=='type'}selected{/if}"> {t}Type{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="orders_element_chooser_payment" style="float:none;margin:0px auto;min-width:120px" onclick="change_orders_element_chooser('payment')" class="{if $elements_order_elements_type=='payment'}selected{/if}"> {t}Payment{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_change_invoices_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" binvoice="0" style="width:200px">
		<tr class="title">
			<td>{t}Group invoices by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="invoices_element_chooser_type" style="float:none;margin:0px auto;min-width:120px" onclick="change_invoices_element_chooser('type')" class="{if $elements_invoice_elements_type=='type'}selected{/if}"> {t}Type{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="invoices_element_chooser_payment" style="float:none;margin:0px auto;min-width:120px" onclick="change_invoices_element_chooser('payment')" class="{if $elements_invoice_elements_type=='payment'}selected{/if}"> {t}Payment{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_change_dns_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" bdn="0" style="width:200px">
		<tr class="title">
			<td>{t}Group delivery notes by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="dns_element_chooser_dispatch" style="float:none;margin:0px auto;min-width:120px" onclick="change_dns_element_chooser('dispatch')" class="{if $elements_dn_elements_type=='dispatch'}selected{/if}"> {t}Dispatch State{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="dns_element_chooser_type" style="float:none;margin:0px auto;min-width:120px" onclick="change_dns_element_chooser('type')" class="{if $elements_dn_elements_type=='type'}selected{/if}"> {t}Type{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='export_splinter.tpl' id='orders' export_fields=$export_orders_fields map=$export_orders_map is_map_default={$export_orders_map_is_default}} {include file='export_splinter.tpl' id='dn' export_fields=$export_dn_fields map=$export_dn_map is_map_default={$export_dn_map_is_default}} {include file='export_splinter.tpl' id='invoices' export_fields=$export_invoices_fields map=$export_invoices_map is_map_default={$export_invoices_map_is_default}} {include file='footer.tpl'} 