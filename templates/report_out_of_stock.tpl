{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="calendar_id" value="{$calendar_id}" />
		<input type="hidden" id="subject" value="report_part_out_of_stock" />
		<input type="hidden" id="subject_key" value="" />		
		<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Out of Stock{/t}</span> 
		</div>
		
		<div class="top_page_menu">
			<div class="buttons">
			</div>
			<div class="buttons" >
				<span class="main_title no_buttons">{$title}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
		
		<div id="calendar_container" style="padding:0 0px;padding-bottom:0px;">
			<div id="period_label_container" style="{if $period==''}display:none{/if}">
				<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span>
			</div>
			{include file='calendar_splinter.tpl'} 
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<div style="float:left;font-size:80%;text-align:center;padding:10px 20px 20px 20px">
	
		
	
	
		<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
			{t}Out of Stock Parts{/t} 
			<div id="number_out_of_stock_parts" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		
			<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
			{t}Transactions Afected{/t} 
			<div id="number_out_of_stock_transactions" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		
		<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px;display:none">
			{t}Deliveries Affected{/t} 
			<div id="number_out_of_stock_dn" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		
		<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
			{t}Orders Affected{/t} 
			<div id="number_out_of_stock_orders" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>	
		
		<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
			{t}Customers Affected{/t} 
			<div id="number_out_of_stock_customers" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		
		<div style="margin-left:10px;border:1px solid #777;float:left;width:150px;padding:5px 0px">
			{t}Revenue Affected{/t} 
			<div id="lost_revenue" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		
	</div>
	
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
	
		<li onclick="change_block('transactions')"> <span class="item {if $block=='transactions'}selected{/if}" id="transactions_tab"> <span> {t}Inventory Transactions{/t}</span></span></li>
		<li onclick="change_block('parts')"> <span class="item {if $block=='parts'}selected{/if}" id="parts_tab"> <span> {t}Parts{/t}</span></span></li>
		<li onclick="change_block('orders')"> <span class="item {if $block=='orders'}selected{/if}" id="orders_tab"> <span> {t}Orders{/t}</span></span></li>
		<li onclick="change_block('customers')"> <span class="item {if $block=='customers'}selected{/if}" id="customers_tab"> <span> {t}Customers{/t}</span></span></li>

	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0 20px 40px 20px">
		<div id="transactions" class="data_table" style="clear:both;margin-top:15px;{if $block!='transactions'}display:none{/if}">
			<span class="clean_table_title">{t}Transactions with Out of Stock{/t}</span> 
			<div class="table_top_bar space">
					</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="parts" class="data_table" style="clear:both;margin-top:15px;{if $block!='parts'}display:none{/if}">
			<span class="clean_table_title">{t}Parts Marked as Out of Stock{/t}</span> 
			<div class="table_top_bar space">
					</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="customers" class="data_table" style="clear:both;margin-top:15px;{if $block!='customers'}display:none{/if}">
			<span class="clean_table_title">{t}Customers affected{/t}</span> 
			<div class="table_top_bar space">
					</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="orders" class="data_table" style="clear:both;margin-top:15px;{if $block!='orders'}display:none{/if}">
			<span class="clean_table_title">{t}Orders affected{/t}</span> 
			<div class="table_top_bar space">
					</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
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

<div id="filtermenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},3)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

{include file='footer.tpl'} 