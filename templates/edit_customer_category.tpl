{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customer_categories.php?store={$store->id}&id=0">{t}Categories{/t}</a> &rarr; <a href="customer_categories.php?id={$category->id}">{$category->get_smarty_tree('customer_categories.php')}</a> &rarr; {t}Editing Category{/t}</span> 
	</div>
	<div class="top_page_menu">
		{if isset($prev)}<img onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" alt="<" style="margin-right:0px;float:left;height:22px;cursor:pointer;{if !$parent_list}display:none{/if};position:relative;top:2px" />{/if}
		<div class="buttons" style="float:left">
				<span class="main_title">{t}Editing Category{/t}: <span id="title_name" class="id">{$category->get('Category Label')}</span></span>

		</div>
		<div class="buttons">
				{if isset($next)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Customer{/t} {$next.name}" onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;{if !$parent_list}display:none;{/if}position:relative;top:2px" />{/if}

					<button style="margin-left:10px" onclick="window.location='customer_categories.php?id={$category->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 

			<button id="new_category"><img src="art/icons/add.png" alt="" /> {t}Add Subcategory{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" {if !$category}style="display:none" {/if} id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='subcategory'}selected{/if}" id="subcategory"> <span> {t}Subcategories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!="description"}display:none{/if}" id="d_description">
			
			<div style="display:none" id="new_category_messages" class="messages_block"></div>
			<table class="edit" style="width:100%">
			<tr class="title">
			<td colspan=3>
			<div class="buttons" >
				<button style="visibility:hidden" id="save_edit_category" onclick="save_edit_general('category')" class="positive">{t}Save{/t}</button> 
				<button style="visibility:hidden" id="reset_edit_category" onclick="reset_edit_general('category')" class="negative">{t}Reset{/t}</button> 
			</div>
			</td>
			</tr>
			
				<tr class="first">
					<td class="label" style="width:200px">{t}Category Label{/t}:</td>
					<td style="text-align:left"> 
					<div style="width:15em;position:relative;top:00px">
						<input style="text-align:left;width:18em" id="Category_Label" value="{$category->get('Category Label')}" ovalue="{$category->get('Category Label')}"> 
						<div id="Category_Label_Container">
						</div>
					</div>
					</td>
					<td id="Category_Label_msg" class="edit_td_alert"></td>
				</tr>

				<tr >
					<td class="label" style="width:200px">{t}Category Name{/t}:</td>
					<td style="text-align:left"> 
					<div style="width:15em;position:relative;top:00px">
						<input style="text-align:left;width:18em" id="Category_Name" value="{$category->get('Category Name')}" ovalue="{$category->get('Category Name')}"> 
						<div id="Category_Name_Container">
						</div>
					</div>
					</td>
					<td id="Category_Name_msg" class="edit_td_alert"></td>
				</tr>

				<tr >
					<td class="label" style="width:200px">{t}New Subject{/t}:</td>
					<td style="text-align:left"> 
					<div   class="buttons" style="width:10em;position:relative;top:00px">
						<button class="{if $category->get('Category Show New Subject')=='Yes'}selected{/if} positive" onclick="save_display_category('Category Show New Subject','Yes', {$category->id})" id="Category Show New Subject Yes">{t}Yes{/t}</button> 
						<button class="{if $category->get('Category Show New Subject')=='No'}selected{/if} negative"  onclick="save_display_category('Category Show New Subject','No', {$category->id})" id="Category Show New Subject No">{t}No{/t}</button>
					</div>
					</td>
					<td style="width:300px"></td>
				</tr>

				<tr >
					<td class="label" style="width:200px">{t}Public New Subject{/t}:</td>
					<td style="text-align:left"> 
					<div   class="buttons" style="width:10em;position:relative;top:00px">
						<button class="{if $category->get('Category Show Public New Subject')=='Yes'}selected{/if} positive" onclick="save_display_category('Category Show Public New Subject','Yes', {$category->id})" id="Category Show Public New Subject Yes">{t}Yes{/t}</button> 
						<button class="{if $category->get('Category Show Public New Subject')=='No'}selected{/if} negative"  onclick="save_display_category('Category Show Public New Subject','No', {$category->id})" id="Category Show Public New Subject No">{t}No{/t}</button>
					</div>
					</td>
					<td style="width:300px"></td>
				</tr>

				<tr >
					<td class="label" style="width:200px">{t}Public Edit{/t}:</td>
					<td style="text-align:left"> 
					<div   class="buttons" style="width:10em;position:relative;top:00px">
						<button class="{if $category->get('Category Show Public Edit')=='Yes'}selected{/if} positive" onclick="save_display_category('Category Show Public Edit','Yes', {$category->id})" id="Category Show Public Edit Yes">{t}Yes{/t}</button> 
						<button class="{if $category->get('Category Show Public Edit')=='No'}selected{/if} negative"  onclick="save_display_category('Category Show Public Edit','No', {$category->id})" id="Category Show Public Edit No">{t}No{/t}</button>
					</div>
					</td>
					<td style="width:300px"></td>
				</tr>

			</table>
		</div>
		<div class="edit_block" style="{if $edit!="subcategory"}display:none{/if}" id="d_subcategory">
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
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:11px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:11px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} {include file='new_category_splinter.tpl'} 