{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" value="{$store->id}" id="store_key"> 
	
	<input type="hidden" value="{t}Invalid Code{/t}" id="invalid_site_code"> 
	<input type="hidden" value="{t}Invalid Name{/t}" id="invalid_site_name"> 
	<input type="hidden" value="{t}Invalid URL{/t}" id="invalid_site_url"> 
	

	<div class="branch">
				<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; {t}New website{/t}</span> 

	</div>
	<div class="top_page_menu">
		<div class="buttons left" style="float:left">
			<span class="main_title">{t}Adding new website{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='edit_store.php?id={$store->id}'" class="negative"><img src="art/icons/door_out.png" alt="" /> {t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="edit_messages">
	</div>
	<div id="new_site_messages" style="float:left;padding:5px;border:1px solid #ddd;width:480px;margin-bottom:15px;display:none">
	</div>
	<table border=0 class="edit" style="width:900px;margin-top:10px">
		<tr class="first" >
			<td class="label" style="width:150px">{t}Code{/t}:</td>
			<td style="width:500px"> 
			<input style="text-align:left;width:200px" id="Code" value="" ovalue="" valid="0"> 
			<div id="Code_Container">
			</div>
			</td>
			<td style="width:300px;font-size:90%" class="error" id="Code_msg"></td>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td> 
			<input style="text-align:left;width:500px" id="Name" value="" ovalue="" valid="0"> 
			<div id="Name_Container">
			</div>
			</td>
			<td style="width:300px;font-size:90%" class="error" id="Name_msg"></td>
		</tr>
	<tr>
			<td class="label">{t}URL{/t}:</td>
			<td> 
			<input style="text-align:left;width:500px" id="URL" value="" ovalue="" valid="0"> 
			<div id="URL_Container">
			</div>
			</td>
			<td style="width:300px;font-size:90%" class="error" id="URL_msg"></td>
		</tr>		
	
		
		<tr>
			<td class="label">{t}Locale{/t}:</td>
			<td> 
			<input type="hidden" value="{$default_locale}" ovalue="{$default_locale}" id="locale"> 
			<div class="buttons small left" style="margin:5px 0" id="locale_container">
				{foreach from=$locales item=locale key=locale_key} <button onclick="change_locate(this)" style="min-width:200px;margin-bottom:5px;clear:left" class="radio {if $locale_key==$default_locale} selected{/if}" id="radio_shelf_type_{$locale_key}" radio_value="{$locale_key}">{$locale.description}</button> {/foreach} 
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button id="save_new_site" class="positive disabled">{t}Save{/t}</button> <button id="close_add_site" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
			<td></td>
		</tr>
	</table>
</div>

{include file='footer.tpl'} 