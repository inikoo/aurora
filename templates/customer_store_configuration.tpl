{include file='header.tpl'} 
<input type="hidden" id="Custom_Field_Store_Key" value="{$store_key}"> 
<input type="hidden" id="Custom_Field_Table" value="Customer"> 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}{$store->get('Store Code')} {t}Customers{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">
			{t}Customers Configuration{/t} <span class="id">{$store->get('Store Code')}</span>
		</span>
				
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $view=='new_custom_fields'}selected{/if}" id="new_custom_fields"> <span> {t}Adding New Custom Fields{/t}</span></span></li>
		<li> <span class="item {if $view=='email_config'}selected{/if}" id="email_config"> <span> {t}Email Configuration{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px">
		<div id="block_new_custom_fields" style="{if $view!='new_custom_fields'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
			<div style="xdisplay:none;width:640px">
				<table class="edit" border="1" style="width:100%;margin-bottom:0px">
					<input type="hidden" value="{$store_key}" id="Store_Key" />
					<tr class="first">
						<td style="width:120px" class="label">{t}Field Label{/t}:</td>
						<td style="text-align:left;width:450px"> 
						<div>
							<input style="text-align:left;" id="Custom_Field_Name" value="" ovalue=""> 
							<div id="Custom_Field_Name_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr style="display:none">
						<td style="width:120px" class="label">{t}Default Value{/t}:</td>
						<td style="text-align:left;width:450px"> 
						<div>
							<input style="text-align:left;" id="Default_Value" value="" ovalue=""> 
							<div id="Default_Value_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td class="label" style="width:200px">{t}Value Type{/t}:</td>
						<input type="hidden" value="Text" id="Custom_Field_Type" />
						<input type="hidden" value="Yes" id="Custom_Field_In_New_Subject" />
						<input type="hidden" value="Yes" id="Custom_Field_In_Showcase" />
						<input type="hidden" value="No" id="Custom_Field_In_Registration" />
						<input type="hidden" value="No" id="Custom_Field_In_Profile" />
						<td> 
						<div class="buttons small left">
							<button class="option selected" onclick="change_allow(this,'Custom_Field_Type','Text')">{t}Short Text{/t}</button> <button class="option" onclick="change_allow(this,'Custom_Field_Type','Longtext')">{t}Long Text{/t}</button> <button class="option" onclick="change_allow(this,'Custom_Field_Type','Mediumint')">{t}Number{/t}</button> <button class="option" onclick="change_allow(this,'Custom_Field_Type','Enum')">{t}Yes/No{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">{t}Display in{/t} ...</td>
					</tr>
					<tr>
						<td class="label">... {t}new customer form{/t}:</td>
						<td> 
						<div class="buttons small left">
							<button class="option selected" onclick="change_allow(this,'Custom_Field_In_New_Subject','Yes')">{t}Yes{/t}</button> <button class="option" onclick="change_allow(this,'Custom_Field_In_New_Subject','No')">{t}No{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">... {t}customer showcase{/t}:</td>
						<td> 
						<div class="buttons small left">
							<button class="option selected" onclick="change_allow(this,'Custom_Field_In_Showcase','Yes')">{t}Yes{/t}</button> <button class="option" onclick="change_allow(this,'Custom_Field_In_Showcase','No')">{t}No{/t}</button> 
						</div>
						</td>
					</tr>
					<td class="label">... {t}registration form{/t}:</td>
					<td> 
					<div class="buttons small left">
						<button class="option " onclick="change_allow(this,'Custom_Field_In_Registration','Yes')">{t}Yes{/t}</button> <button class="option selected" onclick="change_allow(this,'Custom_Field_In_Registration','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label">... {t}customer profile{/t}:</td>
					<td> 
					<div class="buttons small left">
						<button class="option " onclick="change_allow(this,'Custom_Field_In_Profile','Yes')">{t}Yes{/t}</button> <button class="option selected" onclick="change_allow(this,'Custom_Field_In_Profile','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> <span style="float:right;display:none" id="processing"><img src="art/loading.gif" alt="" /> {t}Processing Request{/t}</span> 
					<div class="buttons">
						<button id="save_new_custom_field" class="disabled positive">{t}Save{/t}</button> <button id="cancel_add_custom_field" class="negative">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div id="block_email_config" style="{if $view!='email_config'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<input type="hidden" value="{$store_key}" id="Store_Key" />
		<input type="hidden" value="customers_store" id="Customer_Type" />
		<div style="float:left;width:400px;display:none;" id="edit_default_email">
			<table class="edit" border="0" style="width:100%;margin-bottom:0px">
				<tbody>
					<tr>
						<td></td>
						<td> </td>
					</tr>
					<tr>
						<td> 
						<div class="general_options" style="float:right">
							<span style="margin-right:10px;visibility:hidden" id="save_edit_email_field" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" id="reset_edit_email_field" class="state_details">{t}Reset{/t}</span> 
						</div>
						</td>
					</tr>
					<tr class="label first">
						<td colspan="2">{t}Default Contact Email{/t}</td>
					</tr>
					<tr class="top">
						<td style="width:120px" class="label">{t}Email{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Email" value="" ovalue="" valid="0"> 
							<div id="Email_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}Password{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" type="password" id="Password" value="" ovalue="" valid="0"> 
							<div id="Password_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}Incoming Mail Server{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Incoming_Mail_Server" value="" ovalue="" valid="0"> 
							<div id="Incoming_Mail_Server_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:1200px" class="label">{t}Outgoing Mail Server{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Outgoing_Mail_Server" value="" ovalue="" valid="0"> 
							<div id="Outgoing_Mail_Server_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="float:left;width:400px;display:none;" id="edit_default_email">
			<table class="edit" border="0" style="width:100%;margin-bottom:0px">
				<tbody>
					<tr>
						<td></td>
						<td> </td>
					</tr>
					<tr>
						<td> 
						<div class="general_options" style="float:right">
							<span style="margin-right:10px;visibility:hidden" id="save_edit_email_field" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" id="reset_edit_email_field" class="state_details">{t}Reset{/t}</span> 
						</div>
						</td>
					</tr>
					<tr class="label first">
						<td colspan="2">{t}Default Contact Email{/t}</td>
					</tr>
					<tr class="top">
						<td style="width:120px" class="label">{t}Email{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Email" value="" ovalue="" valid="0"> 
							<div id="Email_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}Password{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" type="password" id="Password" value="" ovalue="" valid="0"> 
							<div id="Password_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}Incoming Mail Server{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Incoming_Mail_Server" value="" ovalue="" valid="0"> 
							<div id="Incoming_Mail_Server_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
					<tr>
						<td style="width:1200px" class="label">{t}Outgoing Mail Server{/t}:</td>
						<td style="text-align:left;width:350px"> 
						<div>
							<input style="text-align:left;" id="Outgoing_Mail_Server" value="" ovalue="" valid="0"> 
							<div id="Outgoing_Mail_Server_Container">
							</div>
						</div>
						</td>
						<td style="width:70px"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="clear:both;height:40px">
		</div>
	</div>
</div>
</div>
{include file='footer.tpl'} 