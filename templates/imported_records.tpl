{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="search_type" value="{$search_type}">
	<input type="hidden" id="subject" value="{$subject}">
	<input type="hidden" id="parent" value="{$parent}">
	<input type="hidden" id="parent_key" value="{$parent_key}">
	{if $subject=='customers'} {include file='contacts_navigation.tpl'} 
		<div style="display:" class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Importing Customers{/t} (3/3)</span> 
	</div>
	<div id="top_page_menu" class="top_page_menu">
		<div class="buttons" style="float:left">
			<div class="buttons" style="float:left">
				<span class="main_title"><img src="art/icons/agenda.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$store->get('Store Code')}</span> <span class="subtitle">{t}Import Contacts From CSV File{/t}</span></span> 
			</div>
		</div>
		<div class="buttons" style="float:right">
			<button id="cancel_import" class="negative"><img src="art/icons/cross.png" alt=""> {t}Cancel{/t}</button> 

		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} 


	<table class="report_sales1" style="margin-top:20px">
		<tr>
			<td>{t}To do records{/t}</td>
			<td id="records_todo"></td>
			<td id="records_todo_comments"></td>
		</tr>
		<tr>
			<td>{t}Imported Records{/t}</td>
			<td id="records_imported"></td>
			<td id="records_imported_comments"></td>
		</tr>
		<tr>
			<td>{t}Ignored{/t}</td>
			<td id="records_ignored"></td>
			<td id="records_ignored_comments"></td>
		</tr>
		<tr>
			<td>{t}Errors{/t}</td>
			<td id="records_error"></td>
			<td id="records_error_comments"></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 