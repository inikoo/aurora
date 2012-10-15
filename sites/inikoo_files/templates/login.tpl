<input type="hidden" id="block" value="{$block}"> 

<input type="hidden" id="store_key" value="{$store->id}"> 
<input type="hidden" id="site_key" value="{$site->id}"> 
<div class="dialog_inikoo">
	<input type="hidden" value="{$St}" id="ep" />
	<input type="hidden" value="{$referral}" id="referral" />

	<div id="dialog_login" style="{if $block!='login'}display:none;{/if}min-height:400px;float:left">
		<h2>
			{t}Login{/t} 
		</h2>
		<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">
			
			<form name="loginform" id="loginform" action="" method="post" onsubmit="return false">
				<fieldset>
					<table style="margin-bottom:10px">
						<tr>
							<td class="label">{t}Email{/t}: </td>
							<td id="email_placeholder"> 
							<input type="email" name="email" id="email"/>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Password{/t}: </td>
							<td id="password_placeholder"> 
							<input type="password" name="password" id="password"/>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Remember Me{/t}: </td>
							<td style="text-align:left;"> 
							<input style="width:20px;border:none" type="checkbox" name="remember_me" id="remember_me" value="0" />
							</td>
						</tr>
						<tr class="button space" style="">
							<td colspan="2"> 
							<div class="buttons">
								<button id="submit_login" class="positive">{t}Log In{/t}</button> <button style="display:none" class="negative" id="hide_login_dialog">{t}Close{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</fieldset>
			</form>
		</div>
		<div id="message_log_out" class="ok_block" style="{if !isset($logged_out)}display:none;{/if}width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}You have successfully logged out, see you soon{/t} 
		</div>
		<div id="message_login_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Fill all fields please{/t}. 
		</div>
		<div id="message_forgot_password_send" class="ok_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<p>{t}An email has been sent to you with a link to access your account{/t}.</p>
			<p>{t}The link will expire in 24 hours{/t}.</p>

		</div>
		<div id="message_login_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Email address invalid{/t}. 
		</div>
		<div id="invalid_credentials" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Invalid username or password!{/t} 
		</div>
		<div id="wrong_password" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Wrong password{/t} 
		</div>
		<div id="wrong_email" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Email is not in our records{/t} 
		</div>
		
		<div style="clear:both">
		</div>
		<table style="clear:left;float;left;margin-top:10px;">
			<tr class="link space">
				<td colspan="2">{t}Forgot your password?{/t} <span class="link" id="link_forgot_password_from_login">{t}Click Here{/t}</span></td>
			</tr>
			<tr id="tr_link_register_from_login" class="link">
				<td colspan="2">{t}First visit?{/t} <span class="link" onclick='window.location="registration.php"' id="link_register_from_login">{t}Register Here{/t}</span></td>
			</tr>
			<tr style="display:none" id="tr_link_register_from_login2" class="link">
				<td colspan="2">{t}Use another email{/t}, <span class="link" id="link_register_from_login2">{t}Register Here{/t}</span></td>
			</tr>
			{if $site->get_login_help_page_key()} 
			<tr class="link">
				<td colspan="2">{t}Troubles with Log in?{/t} <span class="link" onclick='window.location="page.php?id={$site->get_login_help_page_key()}"' id="link_register_from_login">{t}Read Here{/t}</span></td>
			</tr>
			{/if} 
		</table>
	</div>

	<div id="dialog_forgot_password" style="{if $block!='forgot_password'}display:none;{/if}float:left">
		<h2>
			{t}Forgotten password{/t} 
		</h2>
		<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">
			<div class="dialog_inikoo">
				<table>
					<tbody id="forgot_password_form">
						<tr>
							<td class="label" style="text-align:right;width:120px">{t}Email{/t}: </td>
							<td> 
							<input id="forgot_password_handle"></td>
						</tr>
						<tr>
							<td class="label" style="text-align:left;width:120px"> <img id="captcha2" src="art/x.png" style="height:40px;width:115px" alt="CAPTCHA Image" /> <span class="captcha_change_image" onclick="document.getElementById('captcha2').src = 'securimage_show.php?' + Math.random(); return false">{t}Change Image{/t}</span> </td>
							<td style="vertical-align:top"> <span style="font-size:10px">{t}input the letters shown on the left{/t}</span><br />
							<input type="text" id="captcha_code2" name="captcha_code" style="width:50%" />
							</td>
						</tr>
					</tbody>
					<tr class="button space">
						<td id="forgot_password_buttons" colspan="2"> 
						<div class="buttons">
							<button id="submit_forgot_password" class="positive">{t}Send Email{/t}</button> <button id="cancel_forgot_password" class="negative">{t}Cancel{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="processing_change_password" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<img style="vertical-align:top" src="art/loading.gif" alt=""> {t}Processing Request{/t} 
		</div>
		<div id="message_forgot_password_error" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Sorry, an automatic password reset could not be done, try later or call us{/t}. 
		</div>
		<div id="message_forgot_password_not_found" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Sorry, that email is not in our records{/t} 
		</div>
		<div id="message_forgot_password_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Fill all fields please{/t}. 
		</div>
		<div id="message_forgot_password_error_captcha" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}The Captcha field is incorrect{/t}. 
		</div>
		<div id="message_forgot_password_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			{t}Email address invalid{/t}. 
		</div>
		
		<div style="clear:both">
		</div>
		<table style="margin-top:15px;clear:left">
			<tr class="link space">
				<td colspan="2">{t}Want to try to login again?{/t} <span class="link" id="show_login_dialog2">{t}Click Here{/t}</span></td>
			</tr>
			<tr id="tr_link_register_from_login" class="link">
				<td colspan="2">{t}First visit?{/t} <span class="link" onclick='window.location="registration.php"' id="link_register_from_login">{t}Register Here{/t}</span></td>
			</tr>
			{if $site->get_login_help_page_key()} 
			<tr class="link">
				<td colspan="2">{t}Troubles with Log in?{/t} <span class="link" onclick='window.location="page.php?id={$site->get_login_help_page_key()}"' id="link_register_from_login">{t}Read Here{/t}</span></td>
			</tr>
			{/if} 
		</table>
	</div>
	<div style="clear:both;margin-bottom:30px">
	</div>
</div>
