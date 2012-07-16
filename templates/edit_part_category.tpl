{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" value="{$category_key}" id="category_key" />
	{if isset($category)} 
	<div class="branch">
		<span><a href="edit_part_category.php?warehouse_id={$warehouse->id}&id=0">{t}Part Categories{/t}</a> &rarr; {$category->get_smarty_tree('edit_part_category.php')} 
	</div>
	<h1 style="clear:both">
		{t}Editing Category{/t}: <span id="cat_title">{$category->get('Category Name')}</span> 
	</h1>
	{else} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; {t}Parts Categories{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Editing Main Categories{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button onclick="window.location='edit_part_category.php?warehouse_id={$warehouse->id}&id=0'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> <button id="new_category"><img src="art/icons/add.png" alt=""> {t}New Main Category{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} 
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" {if !isset($category)}style="display:none" {/if} id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='parts'}selected{/if}" id="parts"> <span> {t}Parts{/t}</span></span></li>
		<li> <span class="item {if $edit=='subcategory'}selected{/if}" id="subcategory"> <span> {t}Subcategories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		{if isset($category)} 
		<div class="edit_block" style="min-height:300px;{if $edit!='description'}display:none{/if}" id="d_description">
			<div class="general_options" style="float:right">
				<span style="margin-right:10px;visibility:hidden" id="save_edit_category" onclick="save_edit_general('category')" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" id="reset_edit_category" onclick="reset_edit_general('category')" class="state_details">{t}Reset{/t}</span> 
			</div>
			<div id="new_category_messages" class="messages_block">
			</div>
			<table class="edit">
				<tr class="first">
					<td class="label">{t}Category Name{/t}:</td>
					<td style="text-align:left"> 
					<div style="width:15em;position:relative;top:00px">
						<input style="text-align:left;width:18em" id="Category_Name" value="{$category->get('Category Name')}" ovalue="{$category->get('Category Name')}"> 
						<div id="Category_Name_Container">
						</div>
					</div>
					</td>
					<td id="Category_Name_msg" class="edit_td_alert"></td>
				</tr>
			</table>
		</div>
		{/if} 
		<div class="edit_block" style="min-height:300px;{if $edit!='subcategory'}display:none{/if}" id="d_subcategory">
			<span class="clean_table_title">{t}Subcategories{/t}</span> 
			<div class="table_top_bar" style="margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
			<div id="table0" class="data_table_container dtable btable ">
			</div>
		</div>
		<div class="edit_block" style="min-height:300px;{if $edit!='parts'}display:none{/if}" id="d_parts">
		</div>
	</div>
	{*} 
	<div id="the_table1" class="data_table" style="clear:both">
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id='_history' filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table_history" class="data_table_container dtable btable ">
		</div>
	</div>
	{*} 
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
{include file='footer.tpl'} {include file='new_category_splinter.tpl'} 