<input type="hidden" value="{$site->id}" id="site_id" />
<input type="hidden" id="Email_Provider" value="{$email_credentials.Email_Provider}" ovalue="{$email_credentials.Email_Provider}"  />
<span id="Email_Provider_msg" style="display:none"></span>

<table class="edit" border="0" style="width:100%">
<tr class="title">
		<td colspan="2">{t}Email Provider{/t}</td>
		<td> 

		</td>
	</tr>
	<tr class="top" >
		<td class="label"></td>
		<td colspan=2> 
		<div class="buttons " id="site_email_providers" >
			<button id="gmail_btn" class="site_email_provider {if $email_credentials.Email_Provider=='Gmail' or $email_credentials.Email_Provider=='Gmail'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Gmail{/t}</button> 
			<button id="inikoo_btn" class="site_email_provider {if $email_credentials.Email_Provider=='Inikoo'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Inikoo Mail{/t}</button>
			<button id="php_mail_btn" class="site_email_provider {if $email_credentials.Email_Provider=='PHPMail'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}PHP Mail{/t}</button>  
			<button id="other_btn" class="site_email_provider {if $email_credentials.Email_Provider=='Other'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Other{/t}</button>  
			<button id="madmimi_btn" class="site_email_provider {if $email_credentials.Email_Provider=='MadMimi'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Mad Mimi{/t}</button>  
		</div>
		</td>
	</tr>

<tbody id="block_gmail" style="{if $email_credentials.Email_Provider!='Gmail'}display:none;{/if}">
	<tr class="title">
		<td colspan="2">{t}Email Credentials Gmail{/t}</td>
		<td> 
		<div class="buttons">
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Gmail'}display:block{else}display:none{/if}" onClick="show_dialog_test_email_credentials(this)" id="test_email_credentials" class="positive">{t}Test{/t}</button> 
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Gmail'}display:block{else}display:none{/if}" id="delete_email_credentials" class="negative">{t}Delete{/t}</button> 
			<button  id="save_edit_email_credentials" class="positive disabled">{t}Save{/t}</button> 
			<button  id="reset_edit_email_credentials" class="negative">{t}Reset{/t}</button> 
		</div>
		</td>
	</tr>

	

		<tr class="top">
			<td class="label">{t}Email Address{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Address" value="{$email_credentials.Email_Address_Gmail}" ovalue="{$email_credentials.Email_Address_Gmail}" valid="0"> 
				<div id="Email_Address_Container">
				</div>
			</div>
			</td>
			<td id="Email_Address_msg" class="edit_td_alert"></td>
		</tr>

		<tr>
			<td class="label">{t}Password{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input type="password" style="text-align:left;width:100%" id="Email_Password" value="{$email_credentials.Password_Gmail}" ovalue="{$email_credentials.Password_Gmail}" valid="0"> 
				<div id="Email_Password_Container">
				</div>
			</div>
			</td>
			<td id="Email_Password_msg" class="edit_td_alert"></td>
		</tr>
	
</tbody>

<tbody id="block_MadMimi" style="{if $email_credentials.Email_Provider!='MadMimi'}display:none;{/if}">
	<tr class="title">
		<td colspan="2">{t}Email Credentials Mad Mimi{/t}</td>
		<td> 

		</td>
	</tr>

	

		<tr class="top">
			<td class="label">{t}API Email{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="API_Email_Address_MadMimi" value="{$email_credentials.API_Email_Address_MadMimi}" ovalue="{$email_credentials.API_Email_Address_MadMimi}" valid="0"> 
				<div id="API_Email_Address_MadMimi_Container">
				</div>
			</div>
			</td>
			<td id="API_Email_Address_MadMimi_msg" class="edit_td_alert"></td>
		</tr>

		<tr>
			<td class="label">{t}API Key{/t}:</td>
			<td style="text-align:left;width:350px""> 
			<div>
				<input type="password" style="text-align:left;width:100%" id="API_Key_MadMimi" value="{$email_credentials.API_Key_MadMimi}" ovalue="{$email_credentials.API_Key_MadMimi}" valid="0"> 
				<div id="API_Key_MadMimi_Container">
				</div>
			</div>
			</td>
			<td id="API_Key_MadMimi_msg" class="edit_td_alert"></td>
		</tr>

		<tr>
			<td class="label">{t}Sender Email{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Address_MadMimi" value="{$email_credentials.Email_Address_MadMimi}" ovalue="{$email_credentials.Email_Address_MadMimi}" valid="0"> 
				<div id="Email_Address_MadMimi_Container">
				</div>
			</div>
			</td>
			<td id="Email_Address_MadMimi_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
		<td></td>
		<td>
				<div class="buttons">
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='MadMimi'}display:block{else}display:none{/if}" onClick="show_dialog_test_email_credentials(this)" id="test_email_credentials" class="positive">{t}Test{/t}</button> 
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='MadMimi'}display:block{else}display:none{/if}" id="delete_email_credentials_MadMimi" class="negative">{t}Delete{/t}</button> 
			<button  id="save_edit_email_credentials_MadMimi" class="positive disabled">{t}Save{/t}</button> 
			<button  id="reset_edit_email_credentials_MadMimi" class="negative">{t}Reset{/t}</button> 
		</div>
		</td>
		</tr>
	
</tbody>

<tbody id="block_other" style="{if $email_credentials.Email_Provider!='Other'}display:none;{/if}">
	<tr class="title">
		<td colspan="2">{t}Email Credentials Other{/t}</td>
		<td> 
		<div class="buttons">
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Other'}display:block{else}display:none{/if}" onClick="show_dialog_test_email_credentials(this)" id="test_email_credentials_other" class="positive">{t}Test{/t}</button> 
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Other'}display:block{else}display:none{/if}" id="delete_email_credentials_other" class="negative">{t}Delete{/t}</button> 
			<button  id="save_edit_email_credentials_other" class="positive disabled">{t}Save{/t}</button> 
			<button  id="reset_edit_email_credentials_other" class="negative">{t}Reset{/t}</button> 
		</div>
		</td>
	</tr>



		<tr class="top">
			<td class="label">{t}Email Address{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Address_other" value="{$email_credentials.Email_Address_Other}" ovalue="{$email_credentials.Email_Address_Other}" valid="0"> 
				<div id="Email_Address_other_Container">
				</div>
			</div>
			</td>
			<td id="Email_Address_other_msg" class="edit_td_alert"></td>
		</tr>

		<tr>
			<td class="label">{t}Login{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Login_other" value="{$email_credentials.Login_Other}" ovalue="{$email_credentials.Login_Other}" valid="0"> 
				<div id="Email_Login_other_Container">
				</div>
			</div>
			</td>
			<td id="Email_Login_other_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Password{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input type="password" style="text-align:left;width:100%" id="Email_Password_other" value="{$email_credentials.Password_Other}" ovalue="{$email_credentials.Password_Other}" valid="0"> 
				<div id="Email_Password_other_Container">
				</div>
			</div>
			</td>
			<td id="Email_Password_other_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Incoming Mail Server{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Incoming_Server_other" value="{$email_credentials.Incoming_Mail_Server}" ovalue="{$email_credentials.Incoming_Mail_Server}" valid="0"> 
				<div id="Incoming_Server_other_Container">
				</div>
			</div>
			</td>
			<td id="Incoming_Server_other_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Outgoing Mail Server{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Outgoing_Server_other" value="{$email_credentials.Outgoing_Mail_Server}" ovalue="{$email_credentials.Outgoing_Mail_Server}" valid="0"> 
				<div id="Outgoing_Server_other_Container">
				</div>
			</div>
			</td>
			<td id="Outgoing_Server_other_msg" class="edit_td_alert"></td>
		</tr>


</tbody>
<tbody id="block_direct" style="{if $email_credentials.Email_Provider!='PHPMail'}display:none;{/if}">
	<tr class="title">
		<td colspan="2">{t}Email Credentials Direct Mail{/t}</td>
		
	</tr>
	

		<tr class="top">
			<td class="label">{t}Email Address{/t}:</td>
			<td style="text-align:left;width:350px"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Address_direct_mail" value="{$email_credentials.Email_Address_Direct_Mail}" ovalue="{$email_credentials.Email_Address_Direct_Mail}" valid="0"> 
				<div id="Email_Address_direct_mail_Container">
				</div>
			</div>
			
			
			
			</td>
			<td >
			<div class="buttons small left">
			
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='PHPMail'}display:block{else}display:none{/if}" id="delete_email_credentials_direct_mail" class="negative">{t}Delete{/t}</button> 


			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='PHPMail'}display:block{else}display:none{/if}" onClick="show_dialog_test_email_credentials(this)" id="test_email_credentials_direct_mail" >{t}Test{/t}</button> 
		</div>
		<span id="Email_Address_direct_mail_msg" class="edit_td_alert"></span>
			
			</td>
		</tr>
	
	<tr>
	<td></td>
	<td> <div class="buttons">
				<button  id="save_edit_email_credentials_direct_mail" class="disabled positive">{t}Save{/t}</button> 

				<button  id="reset_edit_email_credentials_direct_mail" class="negative">{t}Reset{/t}</button> 

		</div>
		</td>
		<td></td>
	</tr>
	
</tbody>
<tbody id="block_inikoo" style="{if $email_credentials.Email_Provider!='Inikoo'}display:none;{/if}">
	<tr class="title">
		<td colspan="2">{t}Email Credentials Inikoo Mail{/t}</td>
		<td> 
		<div class="buttons">
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Inikoo'}display:block{else}display:none{/if}" onClick="show_dialog_test_email_credentials(this)" id="test_email_credentials_direct_mail" class="positive">{t}Test{/t}</button> 
			<button style="{if $site->get_email_credential_key() and $site->get_credential_type()=='Inikoo'}display:block{else}display:none{/if}" id="delete_email_credentials_direct_mail" class="negative">{t}Delete{/t}</button> 
			<button id="save_edit_email_credentials_inikoo_mail" class="positive disabled">{t}Save{/t}</button> 
			<button  id="reset_edit_email_credentials_inikoo_mail" class="negative">{t}Reset{/t}</button> 
		</div>
		</td>
	</tr>
	

		<tr class="top" height="100px">
			<td class="label">{t}Inikoo Mail Key{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<textarea style="text-align:left;width:100%; height:100px" id="Email_Address_inikoo_mail" value="{$email_credentials.Email_Address_Amazon_Mail}" ovalue="{$email_credentials.Email_Address_Amazon_Mail}" valid="0">{$email_credentials.Email_Address_Amazon_Mail}</textarea>
				<div id="Email_Address_inikoo_mail_Container">
				</div>
			</div>
			</td>
			<td id="Email_Address_inikoo_mail_msg" class="edit_td_alert"></td>
		</tr>
	
</tbody>
</table>
<div id="dialog_test_email_credentials" style="padding:30px 10px 10px 10px;width:320px">
<input type="hidden" value="" id="email_type"/>
	<table style="margin:0 auto">
		<tr>
			<td colspan="2">{t}Send test message{/t}</td>
		</tr>

		<tr class="buttons small" id="site_email_types">
			<td colspan="2">
				<button class="site_email_type" id="btn_plain">Plain</button>
				<button class="site_email_type" id="btn_html" >HTML</button>
			</td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>{t}From{/t}:</td>
			<td>{if $email_credentials.Email_Provider=='Gmail'}{$email_credentials.Email_Address_Gmail}{elseif $email_credentials.Email_Provider=='Other'}{$email_credentials.Email_Address_Other}{elseif $email_credentials.Email_Provider=='Inikoo'}{$email_credentials.Email_Address_Amazon_Mail}{elseif $email_credentials.Email_Provider=='PHPMail'}{$email_credentials.Email_Address_Direct_Mail}{elseif $email_credentials.Email_Provider=='MadMimi'}{$email_credentials.Email_Address_MadMimi}{/if}</td>
		</tr>
		<tr>
			<td>{t}To{/t}:</td>
			<td><input style="width:100%" id="test_message_to"/></td>
		</tr>
		<tr style="display:{if $email_credentials.Email_Provider!='MadMimi'}none{/if}">
			<td>{t}Prmotion Name{/t}:</td>
			<td><input style="width:100%" id="promotion_name"/></td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"></td>
		</tr>
		<tr>
			
			<td colspan=2>
			<div class="buttons">
				<button onclick="send_test_message()">{t}Send{/t}</button>
			</div>
			</td>
		</tr>
	</table>
</div>
