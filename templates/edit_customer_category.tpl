{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customer_categories.php?store_id={$store->id}&id=0">{t}Categories{/t}</a> &rarr; <a href="customer_categories.php?id={$category->id}">{$category->get_smarty_tree('customer_categories.php')}</a> &rarr; {t}Editing Category{/t}</span> 
	</div>
	<div class="top_page_menu">
		<img onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" alt="<" style="margin-right:0px;float:left;height:22px;cursor:pointer;{if !$parent_list}display:none{/if};position:relative;top:2px" /> 
		<div class="buttons" style="float:left">
			<button style="margin-left:10px" onclick="window.location='customer_categories.php?id={$category->id}{if $parent_list}&p={$parent_list}{/if}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		</div>
		<div class="buttons">
			<button id="new_category"><img src="art/icons/add.png" alt="" /> {t}Add Subcategory{/t}</button> 
		</div>
		<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Customer{/t} {$next.name}" onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;{if !$parent_list}display:none;{/if}position:relative;top:2px" /> 
		<div style="clear:both">
		</div>
	</div>
	<h1 style="clear:both">
		{t}Editing Category{/t}: <span id="cat_title">{$category->get('Category Label')}</span>
	</h1>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" {if !$category}style="display:none" {/if} id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='subcategory'}selected{/if}" id="subcategory"> <span> {t}Subcategories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!=" description"}display:none{/if}" id="d_description">
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
						<input style="text-align:left;width:18em" id="Category_Name" value="{$category->get('Category Label')}" ovalue="{$category->get('Category Label')}"> 
						<div id="Category_Name_Container">
						</div>
					</div>
					</td>
					<td id="Category_Name_msg" class="edit_td_alert"></td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="{if $edit!=" subcategory"}display:none{/if}" id="d_subcategory">
			<div class="data_table" sxtyle="margin:25px 20px">
				<span class="clean_table_title">{t}Subcategories{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable ">
				</div>
			</div>
		</div>
	</div>
	<div id="the_table1" class="data_table" style="clear:both">
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable ">
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
{include file='footer.tpl'} {include file='new_category_splinter.tpl'} 