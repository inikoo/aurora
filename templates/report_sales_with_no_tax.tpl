{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
			<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}No Tax Report{/t}</span> 
		</div>
		
		{include file='calendar_splinter.tpl'}
		
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons" style="float:left;margin-bottom:4px" ">
				<span class="main_title"> {$title}, <span class="id">{$period}</span> <img id="show_calendar_browser" style="cursor:pointer;vertical-align:text-bottom;position:relative;top:-3px;{if $tipo=='f'}display:none{/if}" src="art/icons/calendar.png" alt="calendar" /> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $view=='overview'}selected{/if}" id="overview"> <span> {t}Sales Overview{/t}</span></span></li>
		<li> <span class="item {if $view=='customers'}selected{/if}" id="customers"> <span> {t}Customers{/t}</span></span></li>
		<li> <span class="item {if $view=='invoices'}selected{/if}" id="invoices"> <span> {t}Invoices{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Billing Country{/t}/{t}Tax Categories{/t}</span> 
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=true} 
		<div id="table2" class="data_table_container dtable btable with_total">
		</div>
	</div>
	<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		
						<span class="clean_table_title">{t}Customers{/t} <a href="report_sales_with_no_tax_customers_csv.php"><img id="export_csv1" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span> 
<div id="table_type" class="table_type">
				<div style="font-size:90%" >
				
					
						
					{foreach from=$tax_categories item=tax_category} 
						<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $tax_category.selected}selected{/if} label_customer_history_changes" id="elements_tax_category_{$tax_category.code}_customers" table_type="changes">{$tax_category.code}{$tax_category.name} (<span id="elements_tax_category_{$tax_category.code}_customers_number"><img src="art/loading.gif" style="height:12.9px"/></span>)</span>
					{/foreach} 

					
						<span style="float:right;margin-left:2px;margin-right:15px" class=" table_type transaction_type state_details">]</span> 
					{if $corporate_country_code=='GB'} 
						<span style="float:right;margin-left:2px;" class=" table_type transaction_type state_details {if $regions_selected.GBIM}selected{/if} label_region_GBIM" id="elements_region_GBIM_customers" table_type="GBIM">GB+IM</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.EU}selected{/if} label_region_EU" id="elements_region_EU_customers" table_type="EU">EU (no GB,IM)</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.NOEU}selected{/if} label_region_NOEU" id="elements_region_NOEU_customers" table_type="NOEU">No EU</span> 
					{elseif $corporate_country_code=='ES'} 
						<span style="float:right;margin-left:2px;" class=" table_type transaction_type state_details {if $regions_selected.ES}selected{/if} label_region_ES" id="elements_region_ES_customers" table_type="ES">ES</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.EU}selected{/if} label_region_EU" id="elements_region_EU_customers" table_type="EU">EU (no ES)</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.NOEU}selected{/if} label_region_NOEU" id="elements_region_NOEU_customers" table_type="NOEU">No EU</span>
						{/if} 
					<span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 

					
								
					
				</div>
			</div>

<div class="table_top_bar" style="margin-bottom:15px"></div>
	
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
		<div id="table1" style="font-size:90%" class="data_table_container dtable btable with_total">
		</div>
	</div>
	<div id="block_invoices" style="{if $view!='invoices'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
						<span class="clean_table_title">{t}Invoices{/t} <a style="display:none" href="report_sales_with_no_tax_orders_csv.php"><img id="export_csv1" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span> 

<div id="table_type" class="table_type">
				<div style="font-size:90%" >
				
					
						
					{foreach from=$tax_categories item=tax_category} 
						<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $tax_category.selected}selected{/if} label_invoice_history_changes" id="elements_tax_category_{$tax_category.code}_invoices" table_type="changes">{$tax_category.code}{$tax_category.name} (<span id="elements_tax_category_{$tax_category.code}_invoices_number"><img src="art/loading.gif" style="height:12.9px"/></span>)</span>
					{/foreach} 

					
						<span style="float:right;margin-left:2px;margin-right:15px" class=" table_type transaction_type state_details">]</span> 
					{if $corporate_country_code=='GB'} 
						<span style="float:right;margin-left:2px;" class=" table_type transaction_type state_details {if $regions_selected.GBIM}selected{/if} label_region_GBIM" id="elements_region_GBIM_invoices" table_type="GBIM">GB+IM</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.EU}selected{/if} label_region_EU" id="elements_region_EU_invoices" table_type="EU">EU (no GB,IM)</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.NOEU}selected{/if} label_region_NOEU" id="elements_region_NOEU_invoices" table_type="NOEU">No EU</span> 
					{elseif $corporate_country_code=='ES'} 
						<span style="float:right;margin-left:2px;" class=" table_type transaction_type state_details {if $regions_selected.ES}selected{/if} label_region_ES" id="elements_region_ES_invoices" table_type="ES">ES</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.EU}selected{/if} label_region_EU" id="elements_region_EU_invoices" table_type="EU">EU (no ES)</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> 
						<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $regions_selected.NOEU}selected{/if} label_region_NOEU" id="elements_region_NOEU_invoices" table_type="NOEU">No EU</span>
						{/if} 
					<span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 

					
								
					
				</div>
			</div>

<div class="table_top_bar" style="margin-bottom:15px"></div>
			

	
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:0px solid #999">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
		<div id="table0" class="data_table_container dtable btable with_total" style="font-size:90%">
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
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
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
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
{include file='footer.tpl'} 