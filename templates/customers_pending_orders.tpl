{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Pending Orders{/t}</span> 
	</div>
	<div id="top_page_menu" class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Pending Orders{/t}</a>
		</div>
		<div class="buttons" style="float:right">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="orders_table" class="data_table" style="clear:both;margin-top:23px">
		<span class="clean_table_title">{t}Pending Orders{/t} <img id="export_csv0" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Packed}selected{/if} label_Packed" id="elements_Packed" table_type="Packed">{t}Packed{/t} (<span id="elements_Packed_number">{$elements_number.Packed}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InWarehouse}selected{/if} label_InWarehouse" id="elements_InWarehouse" table_type="InWarehouse">{t}In Warehouse{/t} (<span id="elements_InWarehouse_number">{$elements_number.InWarehouse}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.SubmittedbyCustomer}selected{/if} label_SubmittedbyCustomer" id="elements_SubmittedbyCustomer" table_type="SubmittedbyCustomer">{t}Submitted by Customer{/t} (<span id="elements_SubmittedbyCustomer_number">{$elements_number.SubmittedbyCustomer}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcess}selected{/if} label_InProcess" id="elements_InProcess" table_type="InProcess">{t}In Process{/t} (<span id="elements_InProcess_number">{$elements_number.InProcess}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcessbyCustomer}selected{/if} label_InProcessbyCustomer" id="elements_InProcessbyCustomer" table_type="InProcessbyCustomer">{t}In Website{/t} (<span id="elements_InProcessbyCustomer_number">{$elements_number.InProcessbyCustomer}</span>)</span> 
			</div>
		</div>
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
		</div>
		<table style="float:left;margin:0 0 0 0px ;padding:0;height:15px;" class="options">
			<tr>
			</tr>
		</table>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
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
{include file='footer.tpl'} 

