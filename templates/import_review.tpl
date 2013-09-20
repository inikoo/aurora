{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="search_type" value="{$search_type}"> 
	<input type="hidden" id="index" value="{$index}"> 

	<input type="hidden" id="parent" value="{$parent}"> 
	<input type="hidden" id="parent_key" value="{$parent_key}"> 
		<input type="hidden" id="subject" value="{$subject}"> 
		<input type="hidden" id="imported_records_key" value="{$imported_records->id}"> 
		<input type="hidden" id="reference" value="{$reference}"> 



	{if $subject=='customers'} 
	{include file='contacts_navigation.tpl'} 
	<div  class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Import Customers{/t} (2/3)</span> 
	</div>
	<div id="top_page_menu" class="top_page_menu">
		<div class="buttons" style="float:left">
			<div class="buttons" style="float:left">
				<span class="main_title"><img src="art/icons/agenda.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$store->get('Store Code')}</span> <span class="subtitle">{t}Import Contacts From CSV File{/t}</span></span> 
			</div>
		</div>
		<div class="buttons" style="float:right">
			<button class="positive" id="insert_data"><img src="art/icons/database_add.png" alt=""> {t}Insert data{/t}</button> 
			<button id="cancel_import" class="negative"><img src="art/icons/cross.png" alt=""> {t}Cancel{/t}</button> 

			<button id="browse_maps"><img src="art/icons/text_list_bullets.png" alt=""> {t}Pick a Field Map{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} {if $subject=='family' || $subject == 'department' || $subject == 'store'} {include file='contacts_navigation.tpl'} 
	<div id="top_page_menu" class="top_page_menu">
		<div class="buttons" style="float:left">
			<button onclick="window.location='customers.php?store={$store->id}'"><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button> 
		</div>
		<div class="buttons" style="float:right">
			<button class="positive" id="insert_data"><img src="art/icons/database_add.png" alt=""> {t}Insert data{/t}</button> <button id="browse_maps"><img src="art/icons/text_list_bullets.png" alt=""> {t}Pick a Field Map{/t}</button> <button id="new_map"><img src="art/icons/disk.png" alt=""> {t}Save this Field Map{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} 

	<div class="left3Quarters" style="text-align:right;margin-top:20px">
		<input type="hidden" name="form" value="form" />
		<div class="framedsection">
			<div id="call_table">
			</div>
		</div>
	</div>
</div>
<div id="dialog_map" style="width:220px;;padding:30px 20px 10px 20px">
	<div id="map_msg" style="width:100%;text-align:center;margin:0;padding:5px 20px;;display:none;">
	</div>
	<table class="edit" id="map_form_table" style="width:100%" border=0>
		<tbody id="map_error_used_map_name" style="display:none">
		<tr >
			<td colspan="2" id="map_form_text">{t}Map name already taken, please use another name{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons small">
				<button id="overwrite_map">{t}Overwrite map{/t}</button> 
			</div>
			</td>
		</tr>
		</tbody>
		<tr id="map_form_text_tr">
			<td colspan="2" id="map_form_text">{t}Please write the map name{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<input id="map_name" style="width:100%"/>
			</td>
		</tr>
		<tr class="space10">
			<td colspan="2"> 
			<div class="buttons">
				<button id="save_map" class="positive">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_map_select">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Map List{/t}</span> {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5} 
			<div id="table5" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 