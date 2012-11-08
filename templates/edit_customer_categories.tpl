{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customer_categories.php?store_id={$store->id}&id=0">{t}Categories{/t}</a> &rarr; {t}Edit Categories{/t}</span> 
	</div>
	<div class="top_page_menu">
		{if isset($parent_list)}<img onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Category{/t} {$prev.name}" onclick="window.location='edit_customer_categories.php?{$parent_info}id={$prev.id}&store_id={$store->id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" alt="<" style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
		<div class="buttons" style="float:left">
		</div>
		<div class="buttons">
			{if isset($parent_list)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Category{/t} {$next.name}" onclick="window.location='edit_customer_categories.php?{$parent_info}id={$next.id}&store_id={$store->id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt="<" style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
			<button style="margin-left:10px" onclick="window.location='customer_categories.php?store={$store->id}&id=0{if isset($parent_list)}&p={$parent_list}{/if}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
			<button id="new_category"><img src="art/icons/add.png" alt="" /> {t}Add Subcategory{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<h1 style="clear:both">
		{t}Editing Main Categories{/t} 
	</h1>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='subcategory'}selected{/if}" id="subcategory"> <span> {t}Subcategories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" {if $edit!='subcategory' }style="display:none" {/if} id="d_subcategory">
			<div class="data_table" sxtyle="margin:25px 20px">
				<span class="clean_table_title">{t}Subcategories{/t}</span> 
				<div class="clean_table_caption" style="clear:both;">
					<div style="float:left;">
						<div id="table_info0" class="clean_table_info">
							<span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg" id="filter_msg0"></span> 
						</div>
					</div>
					<div class="clean_table_filter" style="display:none" id="clean_table_filter0">
						<div class="clean_table_info">
							<span id="filter_name0" class="filter_name">{$filter_name0}</span>: 
							<input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size="10" />
							<div id='f_container0'>
							</div>
						</div>
					</div>
					<div class="clean_table_controls">
						<div>
							<span style="margin:0 5px" id="paginator0"></span> 
						</div>
					</div>
				</div>
				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
	</div>
	{*}
	<div id="the_table1" class="data_table" style="clear:both">
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable">
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