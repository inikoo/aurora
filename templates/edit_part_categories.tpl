{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" value="{t}Invalid Category Code{/t}" id="msg_invalid_category_code" />
	<input type="hidden" value="{t}Invalid Category Label{/t}" id="msg_invalid_category_label" />
	<input type="hidden" value="{$warehouse->id}" id="warehouse_key" />
	<input type="hidden" value="Part" id="category_subject" />
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; <a href="part_categories.php?warehouse_id={$warehouse->id}&id=0">{t}Parts Categories{/t}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Editing Parts Categories{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button onclick="window.location='part_categories.php?warehouse_id={$warehouse->id}&id=0'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> <button id="new_category"><img src="art/icons/add.png" alt=""> {t}New Main Category{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='subcategory'}selected{/if}" style="{if !$create_subcategory}display:none{/if}" id="subcategory"> <span> {t}Categories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="min-height:300px;{if $edit!='subcategory'}display:none{/if}" id="d_subcategory">
			<span class="clean_table_title">{t}Categories{/t}</span> 
			<div id="table_type" class="table_type">
				<div style="font-size:90%" id="part_type_chooser">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Head}selected{/if} label_part_Head" id="elements_Head" table_type="Head">{t}Head{/t} (<span id="elements_Head_number">{$elements_number.Head}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Node}selected{/if} label_part_Node" id="elements_Node" table_type="Node">{t}Node{/t} (<span id="elements_Node_number">{$elements_number.Node}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Root}selected{/if} label_part_Root" id="elements_Root" table_type="Root">{t}Root{/t} (<span id="elements_Root_number">{$elements_number.Root}</span>)</span> 
				</div>
			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
	</div>
	<div class="buttons small" style="margin-top:0">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		<div  class="elements_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Changes}selected{/if} label_part_Changes" id="elements_Changes" table_type="Changes">{t}Changes{/t} (<span id="elements_Changes_number">{$history_elements_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_part_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		
		
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id='1' filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable">
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
<div id="dialog_delete_category_from_list" style="padding:5px 10px 10px 10px;">
	<input type="hidden" id="delete_from_list_category_key" value=''> 
	<h2 style="padding-top:0px">
		{t}Delete Category{/t} <span class="id" id="delete_from_list_category_code"></span> 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div id='delete_category_msg_from_list'>
	</div>
	<div style="display:none" id="deleting_from_list">
		<img src="art/loading.gif" alt=""> {t}Deleting category, wait please{/t} 
	</div>
	<div id="delete_category_buttons_from_list" class="buttons">
		<button id="save_delete_category_from_list" onclick="save_delete_category_from_list()" class="positive">{t}Yes, delete it!{/t}</button> <button onclick="cancel_delete_category_from_list()" id="cancel_delete_category_from_list" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
{include file='new_main_category_splinter.tpl' subject='Part'} {include file='footer.tpl'} 