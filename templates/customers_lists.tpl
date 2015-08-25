{include file='header.tpl'} 
	<input type="hidden" id="store_key" value="{$store->id}" />

<div id="bd" class="no_padding">
	
	  {include file='navigation.tpl' _content=$content} 	
	
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='user_created'}selected{/if}" id="user_created"> <span> {t}User Created{/t}</span></span></li>
			<li> <span class="item {if $block_view=='imported_records'}selected{/if}" id="imported_records"> <span> {t}Imported Records{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_user_created" class="data_table" style="margin-top:20px;clear:both;{if $block_view!='user_created'}display:none{/if}">
			<span class="clean_table_title">{t}Customers Lists{/t} </span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_imported_records" class="data_table" style="margin-top:20px;clear:both;{if $block_view!='imported_records'}display:none{/if}">
			<span class="clean_table_title">{t}Imported Records{/t} </span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
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
<div id="dialog_delete_customer_list" style="padding:10px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Delete list{/t} 
	</h2>
	<h2 style="padding-top:0px" id="dialog_delete_customer_list_data">
	</h2>
	<input type="hidden" id="dialog_delete_customer_list_key" value=""> 
	<input type="hidden" id="dialog_delete_customer_list_table_id" value=""> 
	<input type="hidden" id="dialog_delete_customer_list_recordIndex" value=""> 
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting list, wait please{/t} 
	</div>
	<div id="delete_store_buttons" class="buttons">
		<button onclick="save_delete('delete','customer_list')" class="positive">{t}Yes, delete it!{/t}</button> <button onclick="cancel_delete('delete','customer_list')" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
{include file='footer.tpl'} 