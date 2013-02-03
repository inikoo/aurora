{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	
	<input type="hidden" value="{t}Invalid Code{/t}" id="invalid_store_code">
		<input type="hidden" value="{t}Invalid Name{/t}" id="invalid_store_name">

	
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
	<table class="edit" style="width:900px;margin-top:10px">
		<tr class="first" style="width:100px">
			<td class="label">{t}Code{/t}:</td>
			<td style="width:500px"> 
			
			<div>
			     <input style="text-align:left;width:200px" id="Code" value="" ovalue="" valid="0"> 
				<div id="Code_Container">
			</div>
			
			</td>
			<td style="width:300px;font-size:90%" class="error"  id="Code_msg" ></td>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td> 
			<div>
			     <input style="text-align:left;width:100%" id="Name" value="" ovalue="" valid="0"> 
				<div id="Name_Container">
			</div>
			</td>
			<td style="width:300px;font-size:90%" class="error"  id="Name_msg" ></td>
		</tr>
		<tr>
			<td class="label">{t}Locale{/t}:</td>
			<td> 
			<input type="hidden" value="{$default_locale}" ovalue="{$default_locale}" id="locale"> 
			<div class="buttons small left" style="margin:5px 0" id="locale_container">
				{foreach from=$locales item=locale key=locale_key} 
					<button onClick="change_locate(this)" style="min-width:200px;margin-bottom:5px;clear:left" class="radio {if $locale_key==$default_locale} selected{/if}" id="radio_shelf_type_{$locale_key}" radio_value="{$locale_key}">{$locale.description}</button> 
				{/foreach} 
			</div>
			</td>
		</tr>
		<tr>
		<td colspan=2>
		<div class="buttons">
			<button id="save_new_store" class="positive disabled" >{t}Save{/t}</button>
			<button id="close_add_store" class="negative" >{t}Cancel{/t}</button>

		</div>
		</td><td></td>
		</tr>
		
		
		
	</table>
</div>
</div>
{include file='footer.tpl'} 