{include file='header.tpl'} 
<div id="bd" style="padding:0">
	<div style="padding:0 20px">
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {$account_label}</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_hr.php'"><img src="art/icons/cog.png" alt=""> {t}Edit Organization{/t}</button> <button id="new_staff"><img src="art/icons/add.png" alt=""> {t}Add Employee{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title"><img src="art/icons/organization.png" style="height:20px" /> {$account_name}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
	{$$block_view}
		<li> <span class="item {if $block_view=='employees'}selected{/if}" id="employees"> <span> {t}Employees{/t}</span></span></li>
		<li> <span class="item {if $block_view=='areas'}selected{/if}" id="areas"> <span> {t}Areas{/t}</span></span></li>
		<li> <span class="item {if $block_view=='departments'}selected{/if}" id="departments"> <span> {t}Departments{/t}</span></span></li>
		<li> <span class="item {if $block_view=='positions'}selected{/if}" id="positions"> <span> {t}Positions{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0 20px;border:1px solid #FFF">
		<div id="block_employees" class="data_table" style="{if $block_view!='employees'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<div style="clear:both;">
				<span class="clean_table_title">{t}Staff List{/t} <img id="export_csv0" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="elements_chooser">
					<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $elements.NotWorking}selected{/if} label_customer_history_notworking" id="elements_notworking" table_type="notworking">{t}Not Working{/t} (<span id="elements_notworking_number">{$elements_number.NotWorking}</span>)</span> <span style="float:right;margin-left:20px;" class="table_type transaction_type state_details {if $elements.Working}selected{/if} label_customer_history_working" id="elements_working" table_type="working">{t}Working{/t} (<span id="elements_working_number">{$elements_number.Working}</span>)</span> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="block_areas" style="{if $block_view!='areas'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span class="clean_table_title">{t}Areas List{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_departments" style="{if $block_view!='departments'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span class="clean_table_title">{t}Departments List{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_positions" style="{if $block_view!='positions'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span class="clean_table_title">{t}Positions{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
			<div id="table3" class="data_table_container dtable btable">
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
<div id="dialog_new_staff" style="padding:20px 20px 10px 20px ">
	<div id="new_staff_msg">
	</div>
	<div class="buttons">
		<button class="positive" onclick="window.location='new_staff.php?ref=hr'">{t}Manually{/t}</button> <button class="positive" onclick="window.location='import.php?subject=staff'">{t}Import from file{/t}</button> <button class="negative" onclick="dialog_new_staff.hide()">{t}Cancel{/t}</button> 
	</div>
</div>
{include file='footer.tpl'} 