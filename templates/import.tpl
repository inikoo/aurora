{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="search_type" value="{$search_type}" />
	<input type="hidden" id="subject" value="{$subject}" />
	<input type="hidden" id="parent" value="{$parent}" />
	<input type="hidden" id="parent_key" value="{$parent_key}" />
	{if $subject=='customers'} {include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Import Customers{/t} (1/3)</span> 
	</div>
	<div id="top_page_menu" class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Import customers from CSV file{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<a class="negative" href="customers.php?store={$store->id}">{t}Cancel{/t}</a> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} 
	
		<table class="edit" style="margin-top:20px;width:100%" border="0">
			<form enctype="multipart/form-data" method="post" id="upload_form">
			<tr>
				<td style="width:70px" class="label">{t}CSV File{/t}:</td>
				<td style="width:250px"> 
				<input id="upload_import_file" style="border:1px solid #ddd;" type="file" name="import_file" />
				</td>
				<td class="error" id="upload_msg"> </td>
			</tr>
			</form>
			<tr class="buttons">
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive disabled" id="save_upload_button">{t}Upload & Preview{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	
</div>
{include file='footer.tpl'} 