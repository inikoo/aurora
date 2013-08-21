<div id="dialog_quick_edit_Subject_Main_Contact_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Contact Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Subject_Main_Contact_Name" value="{$subject->get('Main Contact Name')}" ovalue="{$subject->get('Main Contact Name')}" valid="0"> 
				<div id="Subject_Main_Contact_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Main_Contact_Name_msg" class="edit_td_alert"></span> <button class="positive" id="save_quick_edit_main_contact_name">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_main_contact_name">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Subject_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Subject Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Subject_Name" value="{$subject->get('Name')}" ovalue="{$subject->get('Name')}" valid="0"> 
				<div id="Subject_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Name_msg"></span> <button class="positive" id="save_quick_edit_name">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_name">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Subject_Main_Email" style="padding:10px">
	<table style="margin:10px">
		<tr style="{if !$subject->get_principal_email_comment()}display:none{/if}">
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_Email_comment" value="{$subject->get_principal_email_comment()}" ovalue="{$subject->get_principal_email_comment()}" valid="0"> 
				<div id="Subject_Main_Email_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td>{t}Contact Email:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_Email" value="{$subject->get('Main Plain Email')}" ovalue="{$subject->get('Main Plain Email')}" valid="0"> 
				<div id="Subject_Main_Email_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:right"> 
			<div class="buttons" style="margin-top:10px" id="Subject_Main_Email_buttons">
				<span style="display:none" id="Subject_Main_Email_wait"><span id="Subject_Main_Email_msg"></span>{t}Processing request{/t}</span> <button class="positive" id="save_quick_edit_email">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_email">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{foreach from=$subject->get_other_emails_data() item=other_email key=key} 
<div id="dialog_quick_edit_Subject_Email{$key}" style="padding:10px">
	<table style="margin:10px">
		{if $other_email.label} 
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Email{$key}_comment" value="{$other_email.label}" ovalue="{$other_email.label}" valid="0"> 
				<div id="Subject_Email{$key}_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Other Email:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Email{$key}" value="{$other_email.email}" ovalue="{$other_email.email}" valid="0"> 
				<div id="Subject_Email{$key}_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Email{$key}_msg"></span> <button class="positive" onclick="save_quick_edit_other_email({$key})">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_email{$key}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/foreach} 
<div id="dialog_quick_edit_Subject_Main_Telephone" style="padding:10px">
	<table style="margin:10px" border=1>
		
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" style="width:100%" id="Subject_Main_Telephone_comment" value="{$subject->get_principal_telecom_comment('Telephone')}" ovalue="{$subject->get_principal_telecom_comment('Telephone')}" valid="0"> 
				<div id="Subject_Main_Telephone_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		
		<tr>
			<td>{t}Telephone:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" style="width:100%" id="Subject_Main_Telephone" value="{$subject->get('Main XHTML Telephone')}" ovalue="{$subject->get('Main XHTML Telephone')}" valid="0"> 
				<div id="Subject_Main_Telephone_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Main_Telephone_msg" class="quick_msg" ></span> <button class="disabled positive" id="save_edit_quick_telephone">{t}Save{/t}</button> <button class="negative" id="close_edit_quick_telephone">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{foreach from=$subject->get_other_telephones_data() item=other_telephone key=key} 
<div id="dialog_quick_edit_Subject_Telephone{$key}" style="padding:10px">
	<table style="margin:10px">
		{if $other_tel.label} 
		<tr>
			<td>{t}Other Telephone:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Telephone{$key}_comment" value="{$other_tel.label}" ovalue="{$other_tel.label}" valid="0"> 
				<div id="Subject_Telephone{$key}_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Other Telephone:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Telephone{$key}" value="{$other_telephone.number}" ovalue="{$other_telephone.number}" valid="0"> 
				<div id="Subject_Telephone{$key}_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Telephone{$key}_msg"></span> <button class="positive" onclick="save_quick_edit_other_telephone({$key})">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_telephone{$key}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/foreach} 
{if $subject_tag=='Customer'}
<div id="dialog_quick_edit_Subject_Main_Mobile" style="padding:10px">
	<table style="margin:10px">
		{if $subject->get_principal_telecom_comment('Mobile')} 
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_Mobile_comment" value="{$subject->get_principal_telecom_comment('Mobile')}" ovalue="{$subject->get_principal_telecom_comment('Mobile')}" valid="0"> 
				<div id="Subject_Main_Mobile_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Mobile:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_Mobile" value="{$subject->get('Main XHTML Mobile')}" ovalue="{$subject->get('Main XHTML Mobile')}" valid="0"> 
				<div id="Subject_Main_Mobile_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Main_Mobile_msg"></span> <button class="positive" id="save_quick_edit_mobile">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_mobile">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{foreach from=$subject->get_other_mobiles_data() item=other_mobile key=key} 
<div id="dialog_quick_edit_Subject_Mobile{$key}" style="padding:10px">
	<table style="margin:10px">
		{if $other_mobile.label} 
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Mobile{$key}_comment" value="{$other_mobile.label}" ovalue="{$other_mobile.label}" valid="0"> 
				<div id="Subject_Mobile{$key}_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Other Mobile:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Mobile{$key}" value="{$other_mobile.number}" ovalue="{$other_mobile.number}" valid="0"> 
				<div id="Subject_Mobile{$key}_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Mobile{$key}_msg"></span> <button class="positive" onclick="save_quick_edit_other_mobile({$key})">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_other_mobile{$key}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/foreach} 
{/if}

<div id="dialog_quick_edit_Subject_Website" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Website:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Website" value="{$subject->get('Website')}" ovalue="{$subject->get('Website')}" valid="0"> 
				<div id="Subject_Website_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Website_msg"></span> <button class="positive" id="save_quick_edit_web">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_web">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<div id="dialog_quick_edit_Subject_Main_FAX" style="padding:10px">
	<table style="margin:10px">
		{if $subject->get_principal_telecom_comment('FAX')} 
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_FAX_comment" value="{$subject->get_principal_telecom_comment('FAX')}" ovalue="{$subject->get_principal_telecom_comment('FAX')}" valid="0"> 
				<div id="Subject_Main_FAX_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Fax:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_Main_FAX" value="{$subject->get('Main XHTML FAX')}" ovalue="{$subject->get('Main XHTML FAX')}" valid="0"> 
				<div id="Subject_Main_FAX_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_Main_FAX_msg"></span> <button class="positive" id="save_quick_edit_fax">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_fax">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{foreach from=$subject->get_other_faxes_data() item=other_fax key=key} 
<div id="dialog_quick_edit_Subject_FAX{$key}" style="padding:10px">
	<table style="margin:10px">
		{if $other_fax.label} 
		<tr>
			<td>{t}Comment:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_FAX{$key}_comment" value="{$other_fax.label}" ovalue="{$other_fax.label}" valid="0"> 
				<div id="Subject_FAX{$key}_comment_Container">
				</div>
			</div>
			</td>
		</tr>
		{/if} 
		<tr>
			<td>{t}Other FAX:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Subject_FAX{$key}" value="{$other_fax.number}" ovalue="{$other_fax.number}" valid="0"> 
				<div id="Subject_FAX{$key}_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Subject_FAX{$key}_msg"></span> <button class="positive" onclick="save_quick_edit_other_fax({$key})">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_other_fax{$key}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/foreach} 
<div id="dialog_quick_edit_Subject_Main_Address" style="float:left;xborder:1px solid #ddd;width:430px;padding:20px 10px 10px 10px">
	<table border="0" style="margin-top:20px; width:100%">
		{include file='edit_address_splinter.tpl' address_identifier='contact_' hide_description=true hide_buttons=false default_country_2alpha="$default_country_2alpha" show_form=1 show_default_country=1 address_type=false function_value='' address_function='' show_contact=false show_tel=false close_if_reset=false hide_type=true hide_description=true show_components=true} 
	</table>
	<div style="display:none" id='contact_current_address'>
	</div>
	<div style="display:none" id='contact_address_display{$subject->get("Subject Main Address Key")}'>
	</div>
</div>
<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_comment">
	<div id="comment_msg">
	</div>
	<input type="hidden" value="" id="comment_scope" />
	<input type="hidden" value="" id="comment_scope_key" />
	<input type="hidden" value="" id="comment" />
	<input type="hidden" value="{$subject->get_principal_telecom_comment('Telephone')}" id="comment_telephone" />
	<input type="hidden" value="{$subject->get_principal_telecom_comment('FAX')}" id="comment_fax" />
	{if $subject_tag=='Customer'}
	<input type="hidden" value="{$subject->get_principal_telecom_comment('Mobile')}" id="comment_mobile" />
	{/if}
	<input type="hidden" value="{$subject->get_principal_email_comment()}" id="comment_email" />
</div>