{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Lists{/t}</span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Customers Lists{/t} <span calss="id">{$store->data['Store Code']}</span></span>
		</div>
		<div class="buttons">
			<button onclick="window.location='new_customers_list.php?store={$store->id}'"><img src="art/icons/add.png" alt=""> {t}New List{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<input type="hidden" id="store_id" value="{$store->id}" />
	<div id="the_table" class="data_table" style="margin-top:0px;clear:both;">
		<span class="clean_table_title">{t}Customers Lists{/t} </span> 
		<div class="table_top_bar space">
		</div>
	</div>
	{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
	<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
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


{include file='footer.tpl'} 