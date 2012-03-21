{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'}
	<input type="hidden" id="store_key" value="{$store->id}">
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}{$store->get('Store Name')}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
			<button style="margin-left:0px" onclick="window.location='store.php?id={$store->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		</div>
		<div class="buttons" style="float:right">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:left;margin:0 0px">
		<h1>
			{t}Editing Store{/t}: <span class="id" id="title_name">{$store->get('Store Name')}</span> <span class="id" id="title_code">({$store->get('Store Code')})</span>
		</h1>
	</div>
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='invoice'}selected{/if}" id="invoice"> <span> {t}Invoice{/t}</span></span></li>
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
					<td>{t}Invoice Details{/t} </td>
					<td colspan="2"> 
					<div class="buttons">
						<button class="positive" style="margin-right:10px;visibility:hidden" id="save_edit_invoice">{t}Save{/t}</button> <button class="negative" style="margin-right:10px;visibility:hidden" id="reset_edit_invoice">{t}Reset{/t}</button> 
					</div>
					</td>
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
			</table>
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='website'}display:none{/if}" id="d_website">
			<div class="buttons" >
				<button  id="new_store_page" class="positive">{t}Create Page{/t}</button> 
			</div>
			<input type='hidden' id="site_key" value="{$site_key}"> 
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Pages{/t}</span> 
				<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
				</div>
				<table style="float:left;margin:0 0 0 0px ;padding:0" class="options">
					<tr>
						<td class="{if $pages_view=='page_properties'}selected{/if}" id="page_properties">{t}Page Properties{/t}</td>
						<td class="{if $pages_view=='page_html_head'}selected{/if}" id="page_html_head">{t}HTML Head{/t}</td>
						<td class="{if $pages_view=='page_header'}selected{/if}" id="page_header">{t}Header{/t}</td>
					</tr>
				</table>
				{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
				<div id="table6" style="font-size:90%" class="data_table_container dtable btable ">
				</div>
			</div>
		</div>
		<div class="edit_block" style="min-height:200px;{if $edit!='description'}display:none{/if}" id="d_description">
			<table style="margin:0;clear:both;width:870px" class="edit">
				<tr class="title">
					<td>{t}Store Details{/t} </td>
					<td colspan="2"> 
					<div class="buttons">
						<button class="positive" style="margin-right:10px;visibility:hidden" id="save_edit_store">{t}Save{/t}</button> <button class="negative" style="margin-right:10px;visibility:hidden" id="reset_edit_store">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td style="width:120px" class="label">{t}Store Code{/t}:</td>
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
					<td class="label">{t}Store Name{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="name" name="name" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Store Name')}" ovalue="{$store->get('Store Name')}" />
						<div id="name_Container">
						</div>
					</div>
				</div>
				<td id="name_msg" class="edit_td_alert"></td>
			</tr>
			<tr>
				<td class="label">{t}Store Slogan{/t}:</td>
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
				<td class="label">{t}Store Contact{/t}:</td>
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
				<td class="label">{t}Store Email{/t}:</td>
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
				<td class="label">{t}Store Telephone{/t}:</td>
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
				<td class="label">{t}Store Fax{/t}:</td>
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
				<td class="label">{t}Store URL{/t}:</td>
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
				<td class="label">{t}Store Address{/t}:</td>
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
				<td class="label">{t}Short Marketing Description{/t}:</td>
				<td> 
				<div>
					<input style="width:100%" id="marketing_description" changed="0" type='text' maxlength="255" class='text' value="{$store->get('Short Marketing Description')}" ovalue="{$store->get('Short Marketing Description')}" />
					<div id="marketing_description_Container">
					</div>
				</div>
				</td>
				<td id="marketing_description_msg" class="edit_td_alert"></td>
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
			<button id="add_deal"><img src="art/icons/add.png" alt=""> {t}Add Deal{/t}</button> <button id="edit_deals_templates"><img src="art/icons/page_edit.png" alt=""> {t}Edit Deal Templates{/t}</button> <button style="display:none" class="positive" id="save_new_deal">{t}Save New Template{/t}</button> <button style="display:none" class="negative" id="cancel_add_deal">{t}Cancel{/t}</button> 
		</div>
		<div class="new_item_dialog" id="new_deal_dialog" style="display:none">
			<div id="new_deal_messages" class="messages_block">
			</div>
			<table class="edit">
				<tr>
					<td>{t}Deal Name{/t}:</td>
					<td>
					<input id="new_deal_name" onkeyup="new_deal_changed(this)" onmouseup="new_deal_changed(this)" onchange="new_deal_changed(this)" changed="0" type='text' class='text' maxlength="16" value="" />
					</td>
				</tr>
				<tr>
					<td>{t}Deal Description{/t}:</td>
					<td>
					<input id="new_deal_description" onkeyup="new_deal_changed(this)" onmouseup="new_deal_changed(this)" onchange="new_deal_changed(this)" changed="0" type='text' maxlength="255" class='text' value="" />
					</td>
				</tr>
			</table>
		</div>
		<div class="data_table" style="clear:both">
			<span class="clean_table_title">{t}Deals{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 } 
			<div id="table4" class="data_table_container dtable btable ">
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
			<div id="table3" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
	<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='charges'}display:none{/if}" id="d_charges">
		<div class="new_item_dialog" id="new_charge_dialog" style="display:none">
			<div id="new_charge_messages" class="messages_block">
			</div>
			<table class="edit">
				<tr>
					<td>{t}Charge Name{/t}:</td>
					<td>
					<input id="new_charge_name" onkeyup="new_charge_changed(this)" onmouseup="new_charge_changed(this)" onchange="new_charge_changed(this)" changed="0" type='text' class='text' maxlength="16" value="" />
					</td>
				</tr>
				<tr>
					<td>{t}Charge Description{/t}:</td>
					<td>
					<input id="new_charge_description" onkeyup="new_charge_changed(this)" onmouseup="new_charge_changed(this)" onchange="new_charge_changed(this)" changed="0" type='text' maxlength="255" class='text' value="" />
					</td>
				</tr>
			</table>
		</div>
		<div class="data_table" sxtyle="margin:25px 10px;">
			<div class="buttons">
				<button id="add_charge"><img src="art/icons/add.png" alt=""> Add Charge</button> 
			</div>
			<div style="clear:both">
				<span class="clean_table_title">{t}Charges{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
				<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
				</div>
			</div>
		</div>
	</div>
	<div class="edit_block" style="min-height:200px;margin:0;padding:0 0px;{if $edit!='shipping'}display:none{/if}" id="d_shipping">
	</div>
	<div class="edit_block" style="min-height:200px;{if $edit!='departments'}display:none{/if}" id="d_departments">
		<div class="general_options" style="float:right">
			<span style="margin-right:10px" id="add_department" class="state_details">Create Department</span> <span style="margin-right:10px;display:none" id="save_new_department" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;display:none" id="close_add_department" class="state_details">{t}Close Dialog{/t}</span> 
		</div>
		<div class="new_item_dialog" id="new_department_dialog" style="display:none">
			<div id="new_department_messages" class="messages_block">
			</div>
			<table class="edit">
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
			</table>
		</div>
		<div class="data_table" sxtyle="margin:25px 20px">
			<span class="clean_table_title">{t}Departments{/t}</span> 
			<div class="clean_table_caption" style="clear:both;">
				<div style="float:left;">
					<div id="table_info0" class="clean_table_info">
						<span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg" id="filter_msg0"></span>
					</div>
				</div>
				<div class="clean_table_filter" style="display:none" id="clean_table_filter0">
					<div class="clean_table_info">
						<span id="filter_name0" class="filter_name">{$filter_name0}</span>: 
						<input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size="10" />
						<div id='f_container0'>
						</div>
					</div>
				</div>
				<div class="clean_table_controls">
					<div>
						<span style="margin:0 5px" id="paginator0"></span>
					</div>
				</div>
			</div>
			<div id="table0" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
	<div class="edit_block" style="min-height:200px;{if $edit!='communications'}display:none{/if}" id="d_communications">
		<div class="general_options" style="float:right">
			<span style="margin-right:10px;visibility:hidden" id="save_edit_communications" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" id="reset_edit_communications" class="state_details">{t}Reset{/t}</span> 
		</div>
		<h2>
			Store Emails Accounts
		</h2>

	{include file='email_credential_splinter.tpl' site=$store email_credentials=$email_credentials} 
	</div>
</div>
<div id="the_table1" class="data_table">
	<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
	<div id="table1" class="data_table_container dtable btable ">
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