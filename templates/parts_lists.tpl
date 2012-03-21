{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}   &rarr; <a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a>     &rarr; {t}Parts Lists{/t}</span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Parts Lists{/t} <span class="id">{$warehouse->get('Warehouse Name')}</span></span> 
		</div>
		<div class="buttons">
			<button onclick="window.location='new_parts_list.php?warehouse_id={$warehouse->id}'"><img src="art/icons/add.png" alt=""> {t}New List{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none">
		<span class="clean_table_title">Parts List</span> 
		<div id="table_type">
			<a style="float:right" class="table_type state_details" href="products_lists_csv.php">{t}Export (CSV){/t}</a> 
		</div>
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
		</div>
	</div>
	{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=true } 
	<div id="table0" class="data_table_container dtable btable ">
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
</div>
{include file='footer.tpl'} 