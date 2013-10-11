{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<input type="hidden" id="list_key" value="{$customer_list_id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
	{if $customer_list_id} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customers_lists.php?store={$store->id}">{t}Lists{/t}</a> &rarr; <a href="customers_list.php?id={$customer_list_id}">{$customer_list_name}</a> ({t}Editing{/t})</span> 
	</div>
	{else} 
	<div class="branch">
		<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Editing{/t}</span> 
	</div>
	{/if} 
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
		<div class="buttons left" style="float:left">
			<span class="main_title"> {t}Editing Customers{/t} <span class="id">{if $customer_list_id}{$customer_list_name}{else}{$store->get('Store Code')}{/if}</span> </span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='{if $customer_list_id}customers_list.php?id={$customer_list_id}{else}customers.php?store={$store->id}{/if}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button id="delete_all" class="negative" style="margin-left:20px;{if $user->id!=1}display:none{/if}"><img src="art/icons/cross.png" alt="" /> {t}Delete All Customers{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:left;margin:0 0px">
	</div>
	<div style="margin-top:15px">
		<div id="the_table" class="data_table" style="clear:both">
			<span class="clean_table_title">Customers List</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>

<div id="dialog_delete_all" style="padding:20px 10px 10px 10px">
	<table>
		<tbody id="delete_all_tbody">
			<tr>
				<td> 
				<p style="width:240px;color:red;font-weight:800">
					{if $customer_list_id} {t}Delete all customers without orders in the list below{/t}. {else} {t}Delete all customers without orders{/t}. {/if} 
				</p>
				<p style="width:240px;color:red">
					<img src="art/icons/exclamation.png"> {t}This operation can not be undone{/t}. 
				</p>
				</td>
			</tr>
			<tr>
				<td> 
				<div class="buttons">
					<button id="save_delete_all" class="positive">{t}Delete{/t}</button> <button id="close_delete_all" class="negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</tbody>
		<tbody id="deleting_all" style="display:none">
			<tr>
				<td> <img src="art/loading.gif" style="float:left;;margin-right:10px" alt="" /> 
				<p style="width:240px">
					{t}Wait please, i will take a couple of seconds to delete each customer{/t}. 
				</p>
				</td>
			</tr>
		</tbody>
	</table>
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
{include file='footer.tpl'} 