{include file='header.tpl'} 
<div id="bd">
	<div id="content">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category_data.CategoryDeletedKey}" />
		<div class="branch">
			<span> <a href="index.php"> <img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /> </a> &rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t} </a> &rarr; {/if}<a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t} </a> &rarr; <a href="part_categories.php?&warehouse_id={$warehouse->id}"> {t}Parts Categories{/t} </a> &rarr; {t}Deleted Category{/t} ({$category_data.CategoryDeletedCode})</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($parent_list)}<img style="vertical-align:xbottom;xfloat:none" class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Category{/t} {$prev.name}" onclick="window.location='category.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" onclick="window.location='category.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" />{/if} <span class="main_title">{t}Deleted Category{/t} <span class="id">{$category_data.CategoryDeletedCode}</span></span> 
			</div>
			{if isset($parent_list)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Category{/t} {$next.name}" onclick="window.location='category.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
			<div class="buttons" style="float:right">
			</div>
			<div style="clear:both">
			</div>
		</div>
		<span style="color:#777;float:right;font-style:italic">{t}deleted date{/t}: {$deleted_date}</span> 
		<table style="margin-top:20px">
			<tr>
				<td>{t}Label{/t}:</td>
				<td style="text-align:right">{$category_data.CategoryDeletedLabel}</td>
			</tr>
			<tr>
				<td>{t}Subcategories{/t}:</td>
				<td style="text-align:right">{$category_data.CategoryDeletedChildren}</td>
			</tr>
			<tr>
				<td>{t}Subjetcs{/t}:</td>
				<td style="text-align:right">{$category_data.CategoryDeletedNumberSubjects}</td>
			</tr>
		</table>
		<span class="clean_table_title"> {t}Changelog{/t} </span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="part_type_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Change}selected{/if} label_part_Change" id="elements_Change" table_type="Change">{t}Change{/t} (<span id="elements_Change_number">{$history_elements_number.Change}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_part_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable">
		</div>
		<div style="margin-top:20px">
			{$message} 
		</div>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 