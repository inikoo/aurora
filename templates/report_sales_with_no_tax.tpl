{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input type="hidden" id="corporate_country_code" value="{$corporate_country_code}"> 
	<input type="hidden" id="to" value="{$to}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="calendar_id" value="{$calendar_id}" />
	<input type="hidden" id="subject" value="report_sales_with_no_tax" />
	<input type="hidden" id="subject_key" value="" />
		<input type="hidden" id="encoded_regions_selected" value="{$encoded_regions_selected}" />
		<input type="hidden" id="encoded_tax_category_selected" value="{$encoded_tax_category_selected}" />

	
	
	<div style="padding:0 20px">
		<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}No Tax Report{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons" style="float:left;margin-bottom:4px">
				<span class="main_title"> {$title}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $view=='overview'}selected{/if}" id="overview"> <span> {t}Sales Overview{/t}</span></span></li>
		<li> <span class="item {if $view=='customers'}selected{/if}" id="customers"> <span> {t}Customers{/t}</span></span></li>
		<li> <span class="item {if $view=='invoices'}selected{/if}" id="invoices"> <span> {t}Invoices{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;">
		<div id="period_label_container" style="{if $period==''}display:none{/if}">
			<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
		</div>
		{include file='calendar_splinter.tpl' } 
		<div style="clear:both">
		</div>
	</div>
	<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Billing Country{/t}/{t}Tax Categories{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=true} 
		<div id="table2" class="data_table_container dtable btable with_total" style="font-size:90%">
		</div>
	</div>
	<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Customers{/t} <a href="report_sales_with_no_tax_customers_csv.php"><img id="export_csv1" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span> 
		<div class="elements_chooser" id="elements_chooser_customers">
			
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
		<div id="table1" style="font-size:90%" class="data_table_container dtable btable with_total">
		</div>
	</div>
	<div id="block_invoices" style="{if $view!='invoices'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Invoices{/t} <a style="display:none" href="report_sales_with_no_tax_orders_csv.php"><img id="export_csv1" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span> 
		<div class="elements_chooser"  id="elements_chooser_invoices">

	</div>
		<div class="table_top_bar space">
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