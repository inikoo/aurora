{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" id="store_key" value="{$store->id}"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}{$store->get('Store Name')}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Editing Store{/t}: <span class="id" id="title_name">{$store->get('Store Name')}</span> <span class="id" id="title_code">({$store->get('Store Code')})</span> </span> 
		</div>
		<div class="buttons">
			<button style="margin-left:0px" onclick="window.location='store.php?id={$store->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='invoice'}selected{/if}" id="invoice"> <span> {t}Invoicing{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $edit=='campaigns'}selected{/if}" id="campaigns"> <span> {t}Deal Templates{/t}</span></span></li>
		<li> <span class="item {if $edit=='discounts'}selected{/if}" id="discounts"> <span> {t}Deals{/t}</span></span></li>
		<li> <span class="item {if $edit=='charges'}selected{/if}" id="charges"> <span> {t}Charges{/t}</span></span></li>
		<li> <span class="item {if $edit=='shipping'}selected{/if}" id="shipping"> <span> {t}Shipping{/t}</span></span></li>
		<li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"><span> {t}Images{/t}</span></span></li>
		<li> <span class="item {if $edit=='departments'}selected{/if}" id="departments"><span> {t}Departments{/t}</span></span></li>
		<li> <span class="item {if $edit=='website'}selected{/if} " id="website"><span>{t}Web Sites{/t}</span></span></li>
		<li> <span class="item {if $edit=='communications'}selected{/if}" id="communications"> <span> {t}Customer Contact{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="info_name" style="margin-left:20px;float:left;width:260px;display:none">
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='invoice'}display:none{/if}" id="d_invoice">
			<table style="margin:0;clear:both;width:870px" class="edit">
				<tr class="title">
					<td colspan="3">{t}Invoice Details{/t} </td>
				</tr>
				<tr>
					<td style="width:200px" class="label">{t}Store VAT Number{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="Store_VAT_Number" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store VAT Number')}" ovalue="{$store->get('Store VAT Number')}" />
						<div id="Store_VAT_Number_Container">
						</div>
					</div>
					</td>
					<td id="Store_VAT_Number_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Store Company Number{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="Store_Company_Number" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Company Number')}" ovalue="{$store->get('Store Company Number')}" />
						<div id="Store_Company_Number_Container">
						</div>
					</div>
					</td>
					<td id="Store_Company_Number_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Store Company Name{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="Store_Company_Name" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Company Name')}" ovalue="{$store->get('Store Company Name')}" />
						<div id="Store_Company_Name_Container">
						</div>
					</div>
					</td>
					<td id="Store_Company_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Message Header{/t}:</td>
					<td> 
					<div style="height:120px">
<textarea style="width:100%" id="header" changed="0" olength="{$store->get('Store Invoice Message Header')}" value="{$store->get('Store Invoice Message Header')}" ovalue="{$store->get('Store Invoice Message Header')}" rows="6" cols="42">{$store->get('Store Invoice Message Header')}</textarea> 
						<div id="header_Container">
						</div>
					</div>
					</td>
					<td id="header_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Message{/t}:</td>
					<td> 
					<div style="height:120px">
<textarea style="width:100%" id="msg" changed="0" olength="{$store->get('Store Invoice Message')}" value="{$store->get('Store Invoice Message')}" ovalue="{$store->get('Store Invoice Message')}" rows="6" cols="42">{$store->get('Store Invoice Message')}</textarea> 
						<div id="msg_Container">
						</div>
					</div>
					</td>
					<td id="msg_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons">
						<button class="positive disabled" style="margin-right:10px;" id="save_edit_invoice">{t}Save{/t}</button> <button class="negative disabled" style="margin-right:10px;" id="reset_edit_invoice">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='website'}display:none{/if}" id="d_website">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title" style="margin-right:5px">{t}Sites{/t}</span> 
				<div class="buttons small left">
					<button id="new_site" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
				<div id="table6" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='description'}display:none{/if}" id="d_description">
			<table style="margin:0;clear:both;width:870px" class="edit">
				<tr class="title">
					<td colspan="3">{t}Store Details{/t} </td>
				</tr>
				<tr>
					<td style="width:200px" class="label">{t}Code{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="code" name="code" changed="0" type='text' class='text' maxlength="16" value="{$store->get('Store Code')}" ovalue="{$store->get('Store Code')}" />
						<div id="code_Container">
						</div>
					</div>
					</td>
					<td id="code_msg" class="edit_td_alert" style="xwidth:300px"></td>
				</tr>
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="name" name="name" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Name')}" ovalue="{$store->get('Store Name')}" />
						<div id="name_Container">
						</div>
					</div>
					</td>
					<td id="name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Slogan{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="slogan" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Slogan')}" ovalue="{$store->get('Store Slogan')}" />
						<div id="slogan_Container">
						</div>
					</div>
					</td>
					<td id="slogan_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Contact{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="contact" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Contact Name')}" ovalue="{$store->get('Store Contact Name')}" />
						<div id="contact_Container">
						</div>
					</div>
					</td>
					<td id="contact_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Email{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="email" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Email')}" ovalue="{$store->get('Store Email')}" />
						<div id="email_Container">
						</div>
					</div>
					</td>
					<td id="email_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Telephone{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="telephone" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Telephone')}" ovalue="{$store->get('Store Telephone')}" />
						<div id="telephone_Container">
						</div>
					</div>
					</td>
					<td id="telephone_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Fax{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="fax" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Fax')}" ovalue="{$store->get('Store Fax')}" />
						<div id="fax_Container">
						</div>
					</div>
					</td>
					<td id="fax_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Website{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="url" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store URL')}" ovalue="{$store->get('Store URL')}" />
						<div id="url_Container">
						</div>
					</div>
					</td>
					<td id="url_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Address{/t}:</td>
					<td> 
					<div style="height:120px">
<textarea style="width:100%" id="address" changed="0" olength="{$store->get('Store Address')}" value="{$store->get('Store Address')}" ovalue="{$store->get('Store Address')}" ohash="{$store->get('Product Description MD5 Hash')}" rows="6" cols="42">{$store->get('Store Address')}</textarea> 
						<div id="address_Container">
						</div>
					</div>
					</td>
					<td id="address_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Short Description{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="marketing_description" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Short Marketing Description')}" ovalue="{$store->get('Short Marketing Description')}" />
						<div id="marketing_description_Container">
						</div>
					</div>
					</td>
					<td id="marketing_description_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="buttons">
					<td colspan="2"> 
					<div class="buttons">
						<button class="positive disabled" style="margin-right:10px" id="save_edit_store">{t}Save{/t}</button> <button class="negative" style="margin-right:10px;" id="reset_edit_store">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='pictures'}display:none{/if}" id="d_pictures">
			<table class="edit" border="0" style="width:890px">
				<tr class="title">
					<td>{t}Logo{/t} <span id="store_logo_msg"></span></td>
					<td> </td>
				</tr>
				<tr>
					<td colspan="2" style="padding:5px 0 0 0 "> 
					<form id="logo_file_upload_form" onsubmit="return false;">
						<input type="file" id="logo_file_upload" name="logo" size="10" style="width: 300px"> 
					</form>
					</td>
					<td> </td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='discounts'}display:none{/if}" id="d_discounts">
			<div class="buttons">
				<button id="edit_deals_templates"><img src="art/icons/page_edit.png" alt=""> {t}Edit Deal Templates{/t}</button> <button style="display:none" class="positive" id="save_new_deal">{t}Save New Template{/t}</button> <button style="display:none" class="negative" id="cancel_add_deal">{t}Cancel{/t}</button> 
			</div>
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Deals{/t}</span> 
				<div class="buttons">
					<button id="add_deal"><img src="art/icons/add.png" alt=""> {t}New{/t}</button> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 } 
				<div id="table4" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='campaigns'}display:none{/if}" id="d_campaigns">
			<div class="buttons">
				<button id="add_campaign"><img src="art/icons/add.png" alt=""> {t}Add Template{/t}</button> <button id="close_edit_deals_templates"><img src="art/icons/page_edit.png" alt=""> {t}Edit Store Deals{/t}</button> <button style="display:none" class="positive" id="save_new_campaign">{t}Save New Template{/t}</button> <button style="display:none" class="negative" id="cancel_add_campaign">{t}Cancel{/t}</button> 
			</div>
			<div class="new_item_dialog" id="new_campaign_dialog" style="display:none">
				<div id="new_campaign_messages" class="messages_block">
				</div>
				<table class="edit">
					<tr>
						<td>{t}Template Name{/t}:</td>
						<td> 
						<input id="new_campaign_name" onkeyup="new_campaign_changed(this)" onmouseup="new_campaign_changed(this)" onchange="new_campaign_changed(this)" changed="0" type='text' class='text' maxlength="16" value="" />
						</td>
					</tr>
					<tr>
						<td>{t}Template Description{/t}:</td>
						<td> 
						<input id="new_campaign_description" onkeyup="new_campaign_changed(this)" onmouseup="new_campaign_changed(this)" onchange="new_campaign_changed(this)" changed="0" type='text' maxlength="255" class='text' value="" />
						</td>
					</tr>
				</table>
			</div>
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Deal Templates{/t}</span> 
				<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
				</div>
				{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
				<div id="table3" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='charges'}display:none{/if}" id="d_charges">
			<div class="data_table">
				<span class="clean_table_title">{t}Charges{/t}</span> 
				<div class="buttons small left">
					<button id="add_charge"><img src="art/icons/add.png" alt="">{t}New{/t}</button> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
				<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='shipping'}display:none{/if}" id="d_shipping">
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='departments'}display:none{/if}" id="d_departments">
			<div class="new_item_dialog" id="new_department_dialog" style="display:none">
				<div id="new_department_messages" class="messages_block" style="width:320px;float:right;border:0px solid red">
				</div>
				<table class="edit" border="0" style="width:500px">
					<tr class="title">
						<td> {t}New department{/t} 
						<td> 
					</tr>
					<tr>
						<td>{t}Code{/t}:</td>
						<td> 
						<input id="new_code" onkeyup="new_dept_changed(this)" onmouseup="new_dept_changed(this)" onchange="new_dept_changed(this)" name="code" changed="0" type='text' class='text' maxlength="16" value="" />
						</td>
					</tr>
					<tr>
						<td>{t}Full Name{/t}:</td>
						<td> 
						<input id="new_name" onkeyup="new_dept_changed(this)" onmouseup="new_dept_changed(this)" onchange="new_dept_changed(this)" name="name" changed="0" type='text' maxlength="255" class='text' value="" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td> 
						<div class="buttons small left">
							<button id="close_add_department" class="negative">{t}Close Dialog{/t}</button> <button id="save_new_department" class="disabled positive">{t}Add Department{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="data_table" sxtyle="margin:25px 20px">
				<span class="clean_table_title" style="margin-right:5px">{t}Departments{/t}</span> 
				<div class="buttons small left">
					<button id="add_department" class="state_details"><img src="art/icons/add.png"> {t}New{/t}</button> <button id="add_department_csv" onclick="window.location='import.php?subject=departments&parent=store&parent_key={$store->id}'"><img src="art/icons/table_add.png"> {t}Import (CSV){/t}</button> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='communications'}display:none{/if}" id="d_communications">
			<div class="general_options" style="float:right">
				<span style="margin-right:10px;visibility:hidden" id="save_edit_communications" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" id="reset_edit_communications" class="state_details">{t}Reset{/t}</span> 
			</div>
			<h2>
				{t}Store Emails Accounts{/t} 
			</h2>
			{*} {include file='email_credential_splinter.tpl' site=$store email_credentials=$email_credentials} {*} 
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
		<div id="table1" class="data_table_container dtable btable history">
		</div>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_edit_campaign" style="20px 10px 10px 10px; display:none">
	<table class="edit">
		<tr>
			<td class="label">{t}Name{/t}</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 