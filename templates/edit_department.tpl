{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input id="department_key" value="{$department->id}" type="hidden" />
	<input type='hidden' id="number_sites" value="{$store->get('Store Websites')}"> 
	<input type='hidden' id="site_key" value="{$store->get_site_key()}"> 
	<input type='hidden' id="store_key" value="{$store->id}"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; {$department->get('Product Department Name')}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
			<button style="margin-left:0px" onclick="window.location='department.php?id={$department->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button style="margin-left:0px; {if !$can_delete}display:none{/if}" onclick="delete_department()"><img src="art/icons/delete.png" alt="" /> {t}Delete{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Department{/t} <span class="id" id="title_name">{$department->get('Product Department Name')}</span> <span class="id" id="title_code">({$department->get('Product Department Code')})</span> </span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table>
		<tr style="display:">
			<td></td>
			<td style="text-align:right;color:#777;font-size:90%"> 
			<div id="delete_department_warning" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none">
				<h2>
					{t}Delete Department{/t} 
				</h2>
				<p>
					{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
				</p>
				<p id="delete_department_msg">
				</p>
				<span id="cancel_delete_department" style="cursor:pointer;display:none;font-weight:800">{t}No i dont want to delete it{/t}</span> <span id="save_delete_department" style="cursor:pointer;display:none;margin-left:20px;">{t}Yes, delete it!{/t}</span> 
				<p id="deleting" style="display:none;">
					{t}Deleting department, wait please{/t} 
				</p>
			</div>
			</td>
			<td> </td>
		</tr>
	</table>
	<div style="clear:left;margin:0 0px">
	</div>
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='discounts'}selected{/if}" id="discounts"> <span> {t}Discounts{/t}</span></span></li>
		<li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"><span> {t}Pictures{/t}</span></span></li>
		<li> <span class="item {if $edit=='families'}selected{/if}" id="families"><span> {t}Families{/t}</span></span></li>
		<li> <span class="item {if $edit=='web'}selected{/if} " id="web"><span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<span style="display:none" id="description_num_changes"></span> 
		<div id="description_errors">
		</div>
		<div id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}">
			
			<table border="0" style="clear:both;width:100%" class="edit">
				<tr>
					<td style="width:200px" class="label">{t}Department Code{/t}:</td>
					<td> 
					<div>
						<input id="code" changed="0" type='text' class='text' maxlength="16" value="{$department->get('Product Department Code')}" ovalue="{$department->get('Product Department Code')}" />
						<div id="code_Container">
						</div>
					</div>
					</td>
					<td id="code_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr>
					<td class="label">{t}Department Name{/t}:</td>
					<td> 
					<div>
						<input id="name" changed="0" type='text' maxlength="255" class='text' value="{$department->get('Product Department Name')}" ovalue="{$department->get('Product Department Name')}" />
						<div id="name_Container">
						</div>
					</div>
					</td>
					<td id="name_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr class="buttons">
				<td colspan=2>
				<div class="buttons">
				<button onclick="save_edit_general('department')" id="save_edit_department" class="positive disabled">{t}Save{/t}</button> <button onclick="reset_edit_general('department')" id="reset_edit_department" class="negative disabled">{t}Reset{/t}</button> 
			</div>
				</td>
				</tr>
			</table>
		</div>
		<div id="d_pictures" class="edit_block" style="{if $edit!='pictures'}display:none{/if}">
			{include file='edit_images_splinter.tpl' parent=$department} 
		</div>
		<div id="d_families" class="edit_block" style="{if $edit!='families'}display:none{/if}">
			<div class="buttons small">
				<button id="show_new_family_dialog_button" onclick="show_new_family_dialog()">Create Family</button> <button onclick="window.location='import.php?subject=department&subject_key={$department->id}'">Import Families (CSV)</button> 
			</div>
			<div style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;display:none" id="new_family_dialog">
				<div class="buttons">
					<button id="save_new_family" class="positive disabled">{t}Save New Family{/t}</button> <button id="cancel_new_family" class="negative disabled">{t}Close New Family{/t}</button> 
				</div>
				<div id="new_family_messages" class="messages_block">
				</div>
				<table class="edit" style="width:100%">
					<tr>
						<td></td>
						<td id="new_family_dialog_msg"></td>
					</tr>
					<tr>
						<td style="width:160px" class="label">{t}Family Code{/t}:</td>
						<td> 
						<div>
							<input id="family_code" changed="0" type='text' class='text' maxlength="16" value="" ovalue="" />
							<div id="family_code_Container">
							</div>
						</div>
						</td>
						<td id="family_code_msg" class="edit_td_alert" style="width:300px"></td>
					</tr>
					<tr>
						<td class="label">{t}Family Name{/t}:</td>
						<td> 
						<div>
							<input id="family_name" changed="0" type='text' maxlength="255" class='text' value="" ovalue="" />
							<div id="family_name_Container">
							</div>
						</div>
						</td>
						<td id="family_name_msg" class="edit_td_alert" style="width:300px"></td>
					</tr>
					<tr style="display:none">
						<td class="label">{t}Special Characteristic{/t}:</td>
						<td> 
						<div>
							<input id="family_special_char" changed="0" type='text' maxlength="255" class='text' value="" ovalue="" />
							<div id="family_special_char_Container">
							</div>
						</div>
						</td>
						<td id="family_special_char_msg" class="edit_td_alert" style="width:300px"></td>
					</tr>
					<tr>
						<td class="label">{t}Description{/t}:</td>
						<td> <textarea style="width:100%" id="family_description" ovalue=""></textarea> </td>
						<td id="family_description_msg" class="edit_td_alert" style="width:300px"></td>
					</tr>
				</table>
			</div>
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Families{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="d_web" class="edit_block" style="{if $edit!='web'}display:none{/if}">
			<div class="buttons">
				<button id="new_department_page" class="positive">{t}Create Page{/t}</button> 
			</div>
			<div style="clear:both">
				<span class="clean_table_title">{t}Pages{/t}</span> 
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $pages_view=='page_properties'}selected{/if}" id="page_properties">{t}Page Properties{/t}</button> <button class="{if $pages_view=='page_html_head'}selected{/if}" id="page_html_head">{t}HTML Head{/t}</button> <button class="{if $pages_view=='page_header'}selected{/if}" id="page_header">{t}Header{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
				<div id="table6" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="d_discounts" class="edit_block" style="{if $edit!='discounts'}display:none{/if}">
		</div>
	</div>
		<div class="buttons small">
	<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
	<span class="clean_table_title">{t}Changelog{/t}</span> 
	<div class="table_top_bar space">
	</div>
	{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
	<div id="table1" class="data_table_container dtable btable history" >
	</div>
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