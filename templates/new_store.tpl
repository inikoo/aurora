{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" value="{t}Invalid Code{/t}" id="invalid_store_code"> 
	<input type="hidden" value="{t}Invalid Name{/t}" id="invalid_store_name"> 
	<input type="hidden" value="{t}of{/t}" id="label_of"> 
	<input type="hidden" value="{t}Pages{/t}" id="label_Pages"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Stores{/t} ({t}New Store{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons left" style="float:left">
			<span class="main_title">{t}Adding new store{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='edit_stores.php'" class="negative"><img src="art/icons/door_out.png" alt="" /> {t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="edit_messages">
	</div>
	<div id="new_store_messages" style="float:left;padding:5px;border:1px solid #ddd;width:480px;margin-bottom:15px;display:none">
	</div>
	<table border="0" class="edit" style="width:900px;margin-top:10px">
		<tr class="first">
			<td class="label" style="width:150px">{t}Code{/t}:</td>
			<td style="width:340px"> 
			<input style="text-align:left;width:100px" id="Code" value="" ovalue="" valid="0"> 
			<div id="Code_Container">
			</div>
			</td>
			<td style="width:460px;font-size:90%" class="error" id="Code_msg"></td>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td> 
			<input style="text-align:left;width:300px" id="Name" value="" ovalue="" valid="0"> 
			<div id="Name_Container">
			</div>
			</td>
			<td style="width:300px;font-size:90%" class="error" id="Name_msg"></td>
		</tr>
		<tr class="space10">
			<td class="label">{t}Country{/t}:</td>
			<td colspan="2"> 
			<input type="hidden" style="text-align:left;width:50px" id="Country" value="{$inikoo_account->get('Account Country 2 Alpha Code')}" ovalue="" valid="0"> 
			<div class="styled-select">
				<select id="country_select" onchange="set_country(this.value)">
					{include file='common_country_select.tpl' country=$inikoo_account->get('Account Country 2 Alpha Code')} {include file='country_select.tpl'} 
				</select>
			</div>
			<span style="width:300px;font-size:90%" class="error" id="Country_msg"></span> 
		</tr>
		<tr id="locale_tr">
			<td class="label">{t}Locale{/t}:</td>
			<td> 
			<input type="hidden" value="{$inikoo_account->get('Account Locale')}" ovalue="{$inikoo_account->get('Account Locale')}" id="locale"> 
			<div class="styled-select">
				<select id="locale_select" onchange="set_locate(this.value)">
					{foreach from=$locales item=locale key=locale_key} 
					<option style="min-width:200px;margin-bottom:5px;clear:left" class="radio {if $locale_key==$inikoo_account->get('Account Locale')} selected{/if}" id="radio_shelf_type_{$locale_key}" radio_value="{$locale_key}">{$locale.description}</option>
					{/foreach} 
				</select>
			</div>
			</td>
		</tr>
		<tr class="buttons">
			<td></td>
			<td colspan=2> 
			<div class="buttons left">
				<span id="waiting_add_store" style="display:none"><img src="art/loading.gif"/> {t}Processing request{/t}</span>
				<button id="close_add_store" class="negative">{t}Cancel{/t}</button> <button id="save_new_store" class="positive disabled">{t}Save{/t}</button> 
				<span id="error_message" class="error"></span>
			</div>
			</td>
			
		</tr>
	</table>
</div>

{include file='footer.tpl'} 