{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" id="warehouse_key" value="{$warehouse->id}" />
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Locations{/t} <span id="areas_view" style="{if $view!='areas'}display:none{/if}">({t}Areas{/t})</span></span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_warehouse.php?id={$warehouse->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Warehouse{/t}</button> {/if} <button onclick="window.location='warehouse_stats.php?id={$warehouse->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button onclick="window.location='warehouse_map.php?id={$warehouse->id}'"><img src="art/icons/application_view_gallery.png" alt=""> {t}Map{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Warehouse{/t}: {$warehouse->get('Warehouse Name')} ({$warehouse->get('Warehouse Code')})</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $view=='locations'}selected{/if}" id="locations"> <span> {t}Locations{/t}</span></span></li>
		<li> <span class="item {if $view=='areas'}selected{/if}" id="areas"> <span> {t}Areas{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='shelfs'}selected{/if}" id="shelfs"> <span> {t}Shelfs{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='map'}selected{/if}" id="map"><span> {t}Map{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='movements'}selected{/if}" id="movements"> <span> {t}Movements{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='stats'}selected{/if}" id="stats"> <span> {t}Stats{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_locations" style="{if $view!='locations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
			<span class="clean_table_title">{t}Locations{/t}</span> 
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Yellow}selected{/if} label_page_type" id="elements_Yellow">{t}Yellow{/t} (<span id="elements_Yellow_number">{$elements_number.Yellow}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Red}selected{/if} label_page_type" id="elements_Red">{t}Red{/t} (<span id="elements_Red_number">{$elements_number.Red}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Purple}selected{/if} label_page_type" id="elements_Purple">{t}Purple{/t} (<span id="elements_Purple_number">{$elements_number.Purple}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Pink}selected{/if} label_page_type" id="elements_Pink">{t}Pink{/t} (<span id="elements_Pink_number">{$elements_number.Pink}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Orange}selected{/if} label_page_type" id="elements_Orange">{t}Orange{/t} (<span id="elements_Orange_number">{$elements_number.Orange}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Green}selected{/if} label_page_type" id="elements_Green">{t}Green{/t} (<span id="elements_Green_number">{$elements_number.Green}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Blue}selected{/if} label_page_type" id="elements_Blue">{t}Blue{/t} (<span id="elements_Blue_number">{$elements_number.Blue}</span>)</span> 
			</div>
			<div class="table_top_bar" style="margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
	<div id="block_areas" style="{if $view!='areas'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="the_table1" class="data_table" style="margin:0px 0px;clear:both">
			<span class="clean_table_title">{t}Warehouse Areas{/t}</span> 
			<div class="table_top_bar" style="margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
	<div id="block_map" style="{if $view!='map'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div style="border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
			<img src="_warehouse.png" name="printable_map" /> 
		</div>
	</div>
</div>
<div id="block_shelfs" style="{if $view!='shelfs'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_movements" style="{if $view!='movements'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_stats" style="{if $view!='stats'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
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
{include file='footer.tpl'} 