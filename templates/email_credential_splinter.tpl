<input type="hidden" value="{$site->id}" id="site_id" />
<input type="hidden" value="" id="Email_Provider" value="{$email_credentials.Email_Provider}" />
<table class="edit" border="0" style="width:100%">
	<tr class="title">
		<td colspan="2">{t}Email Credentials{/t}</td>
		<td> 
		<div class="buttons">
			<button style="{if !$site->get_email_credentials_key()}display:none{/if}" id="test_email_credentials" class="positive">{t}Test{/t}</button> <button style="{if !$site->get_email_credentials_key()}display:none{/if}" id="delete_email_credentials" class="negative">{t}Delete{/t}</button> <button style="visibility:hidden" id="save_edit_email_credentials" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_email_credentials" class="negative">{t}Reset{/t}</button> 
		</div>
		</td>
	</tr>
	<tr class="top">
		<td class="label">{t}Select Mail Server{/t}: </td>
		<td> 
		<div class="buttons" id="site_email_servers" style="float:left">
			<button id="other" class="site_email_server {if $email_credentials.Email_Provider=='Other' or $email_credentials.Email_Provider==''}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Other{/t}</button> <button id="gmail" class="site_email_server {if $email_credentials.Email_Provider=='Gmail'}selected{/if}"><img src="art/icons/email.png" alt="" /> {t}Gmail{/t}</button> 
		</div>
		</td>
		<td style="width:300px">
		<td id="Email_Provider_msg" class="edit_td_alert"></td>
		</td>
		<tr>
			<td class="label">{t}Email Address{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Address" value="{$email_credentials.Email_Address}" ovalue="{$email_credentials.Email_Address}" valid="0"> 
				<div id="Email_Address_Container">
				</div>
			</div>
			</td>
			<td id="Email_Address_msg" class="edit_td_alert"></td>
		</tr>
		<tr id="tr_email_login" style="{if $email_credentials.Email_Provider=='Gmail'}display:none{/if}">
			<td class="label">{t}Login{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Email_Login" value="{$email_credentials.Login}" ovalue="{$email_credentials.Login}" valid="0"> 
				<div id="Email_Login_Container">
				</div>
			</div>
			</td>
			<td id="Email_Login_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Password{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input type="password" style="text-align:left;width:100%" id="Email_Password" value="{$email_credentials.Password}" ovalue="{$email_credentials.Password}" valid="0"> 
				<div id="Email_Password_Container">
				</div>
			</div>
			</td>
			<td id="Email_Password_msg" class="edit_td_alert"></td>
		</tr>
	</tr>
	<tbody id="other_tbody" style="{if $email_credentials.Email_Provider=='Gmail'}display:none{/if}">
		<tr>
			<td class="label">{t}Incoming Mail Server{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Incoming_Server" value="{$email_credentials.Incoming_Mail_Server}" ovalue="{$email_credentials.Incoming_Mail_Server}" valid="0"> 
				<div id="Incoming_Server_Container">
				</div>
			</div>
			</td>
			<td id="Incoming_Server_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Outgoing Mail Server{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="Outgoing_Server" value="{$email_credentials.Outgoing_Mail_Server}" ovalue="{$email_credentials.Outgoing_Mail_Server}" valid="0"> 
				<div id="Outgoing_Server_Container">
				</div>
			</div>
			</td>
			<td id="Outgoing_Server_msg" class="edit_td_alert"></td>
		</tr>
	</tbody>
</table>
<div id="dialog_test_email_credentials" style="padding:30px 10px 10px 10px;width:320px">
	<table style="margin:0 auto">
		<tr>
			<td colspan="2">{t}Sending test email to {/t}:{$email_credentials.Email_Address}</td>
		</tr>
		<tr>
			<td>{t}Test Message{/t}:</td>
			<td><textarea rows='2' cols="20" id="test_message"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>
			<div class="buttons small">
				<button onclick="send_test_message()">{t}Send{/t}</button>
			</div>
			</td>
		</tr>
	</table>
</div>
