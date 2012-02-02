<input type="hidden" value="{$site->id}" id="site_id"/>
<input type="hidden" value="{literal}smtp.gmail.com{/literal}" id="outgoing_server"/>
<input type="hidden" value="{literal}{imap.gmail.com:993/imap/ssl/novalidate-cert}{/literal}" id="incoming_server"/>



<table class="edit" border=0 style="width:100%">
<tr >
<td></td>
<td colspan=2>

 <div class="buttons">
		<button  style="display:{if !$site->get_site_email_credentials()}none{/if}"  id="test_email_credentials" class="positive">{t}Test{/t}</button>
		<button  style="display:{if !$site->get_site_email_credentials()}none{/if}"  id="delete_email_credentials" class="negative">{t}Delete{/t}</button>
	        <button  style="visibility:hidden"  id="save_edit_email_credentials" class="positive">{t}Save{/t}</button>
	        <button style="visibility:hidden" id="reset_edit_email_credentials" class="negative">{t}Reset{/t}</button>
		
 </div>
</td>
</tr>
<tr class="top"><td class="label">{t}Select Mail Server{/t}:
</td><td>

<div class="buttons" id="site_email_servers" style="float:left">
<button  id="other"  class="site_email_server"><img src="art/icons/email.png" alt=""/> {t}Other{/t}</button>
<button  id="gmail" class="site_email_server selected" ><img src="art/icons/email.png" alt=""/> {t}Gmail{/t}</button>
</div>
     
</td>
<td style="width:300px"></td>


<tr>
<td class="label">{t}Email Address{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Email_Address" value="{$email_credentials.Email_Address}" ovalue="{$email_credentials.Email_Address}" valid="0">
       <div id="Email_Address_Container"  ></div>
     </div>

</td>
<td id="Email_Address_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Password{/t}:</td>
<td  style="text-align:left">
     <div>
       <input type="password" style="text-align:left;width:100%" id="Email_Password" value="{$email_credentials.Password}" ovalue="{$email_credentials.Password}" valid="0">
       <div id="Email_Password_Container"  ></div>
     </div>

</td>
<td id="Email_Password_msg" class="edit_td_alert"></td>
</tr>

</tr>	
<tbody id="other_tbody" style="display:none">



<tr>
<td class="label">{t}Incoming Mail Server{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Incoming_Server" value="{$email_credentials.Incoming_Mail_Server}" ovalue="{$email_credentials.Incoming_Mail_Server}" valid="0">
       <div id="Incoming_Server_Container"  ></div>
     </div>

</td>
<td id="Incoming_Server_msg" class="edit_td_alert"></td>
</tr>
<tr>
<td class="label">{t}Outgoing Mail Server{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Outgoing_Server" value="{$email_credentials.Outgoing_Mail_Server}" ovalue="{$email_credentials.Outgoing_Mail_Server}" valid="0">
       <div id="Outgoing_Server_Container"  ></div>
     </div>

</td>
<td id="Outgoing_Server_msg" class="edit_td_alert"></td>
</tr>
</tbody>

<tr>
<td class="label">{t}Welcome Email Subject{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="welcome_subject" value="{$site->get('Site Welcome Email Subject')}" ovalue="{$site->get('Site Welcome Email Subject')}" valid="0">
       <div id="welcome_subject_Container"  ></div>
     </div>

</td>
<td id="welcome_subject_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Welcome Email Body Plain Text{/t}:</td>
<td  style="text-align:left">
     <div style="height:50px">
       <textarea rows='2' cols="20"  id="welcome_body_plain" value="{$site->get('Site Welcome Email Plain Body')}" ovalue="{$site->get('Site Welcome Email Plain Body')}" valid="0">{$site->get('Site Welcome Email Plain Body')}</textarea>
       <div id="welcome_body_plain_Container"  ></div>
     </div>

</td>
<td id="welcome_body_plain_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Welcome Email Body HTML{/t}:</td>
<td  style="text-align:left">
     <div style="height:50px">
       <textarea rows='2' cols="20"  id="welcome_body_html" value="{$site->get('Site Welcome Email HTML Body')}" ovalue="{$site->get('Site Welcome Email HTML Body')}" valid="0">{$site->get('Site Welcome Email HTML Body')}</textarea>
       <div id="welcome_body_html_Container"  ></div>
     </div>

</td>
<td id="welcome_body_html_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Forgot Password Email Subject{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="forgot_password_subject" value="{$site->get('Site Forgot Password Email Subject')}" ovalue="{$site->get('Site Forgot Password Email Subject')}" valid="0">
       <div id="forgot_password_subject_Container"  ></div>
     </div>

</td>
<td id="forgot_password_subject_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Forgot Password Email Body Plain Text{/t}:</td>
<td  style="text-align:left">
     <div style="height:50px">
       <textarea rows='2' cols="20"  id="forgot_password_body_plain" value="{$site->get('Site Forgot Password Email Plain Body')}" ovalue="{$site->get('Site Forgot Password Email Plain Body')}" valid="0">{$site->get('Site Forgot Password Email Plain Body')}</textarea>
       <div id="forgot_password_body_plain_Container"  ></div>
     </div>

</td>
<td id="forgot_password_body_plain_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Forgot Password Email Body HTML{/t}:</td>
<td  style="text-align:left">
     <div style="height:50px">
        <textarea rows='2' cols="20"  id="forgot_password_body_html" value="{$site->get('Site Forgot Password Email HTML Body')}" ovalue="{$site->get('Site Forgot Password Email HTML Body')}" valid="0">{$site->get('Site Forgot Password Email HTML Body')}</textarea>
       <div id="forgot_password_body_html_Container"  ></div>
     </div>

</td>
<td id="forgot_password_body_html_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}Welcome Source{/t}:</td>
<td  style="text-align:left">
     <div style="height:50px">
        <textarea rows='2' cols="20"  id="welcome_source" value="{$site->get('Site Welcome Source')}" ovalue="{$site->get('Site Welcome Source')}" valid="0">{$site->get('Site Welcome Source')}</textarea>
       <div id="welcome_source_Container"  ></div>
     </div>

</td>
<td id="welcome_source_msg" class="edit_td_alert"></td>
</tr>

</table>

<div id="dialog_test_email_credentials" style="padding:30px 10px 10px 10px;width:320px">

 <table style="margin:0 auto">
<tr><td colspan=2>{t}Sending test email to {/t}:{$email_credentials.Email_Address}</td></tr>
<tr><td>{t}Test Message{/t}:</td><td><textarea rows='2' cols="20" id="test_message"></textarea></td></tr>
<tr><td></td><td><div class="buttons small" ><button onClick="send_test_message()">{t}Send{/t}</button></td></tr>
 </table>
</div>
