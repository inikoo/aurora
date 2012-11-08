{include file='header.tpl'} 
<div id="bd" style="padding:0">
	<div style="padding:0 20px">
		{include file='users_navigation.tpl'} 
		<input id="user_key" value="{$site_user->id}" type="hidden" />
		<input id="site_key" value="{$site->id}" type="hidden" />
		<input id="store_key" value="{$site->get('Site Store Key')}" type="hidden" />
		<input id="customer_id" value="{$site_user->get('User Parent Key')}" type="hidden" />
		<input id="site_url" value="{$site->get('Site URL')}" type="hidden" />
		<input id="forgot_password_handle" value="{$site_user->get('User Handle')}" type="hidden" />
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a href="users.php">{t}Users{/t}</a> &rarr; <a href="users_site.php?site_key={$site->id}">{t}Site Users{/t} ({$site->get('Site Code')})</a> &rarr; {$site_user->get('User Handle')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button style="display:none" onclick="window.location='edit_site_user.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit User{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
			</div>
			<div class="buttons" style="float:right">
				<button id="show_dialog_change_password"><img src="art/icons/key.png" alt=""> {t}Modify Password{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<h1>
			{t}Site User{/t}: {$site_user->get('User Handle')} 
		</h1>
		<div style="clear:both">
		</div>
		<div style="width:270px;margin-top:0px;float:left">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Site{/t}:</td>
					<td><a href="site.php?id={$site->id}">{$site->get('Site Code')}</a> (<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a>)</td>
				</tr>
				<tr>
					<td>{t}Customer{/t}:</td>
					<td><a href="customer.php?id={$customer->id}">{$customer->get('Customer Name')}</a></td>
				</tr>
			</table>
		</div>
		<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Login Count{/t}:</td>
					<td>{$site_user->get('Login Count')}</td>
				</tr>
				<tr>
					<td>{t}Last Login{/t}:</td>
					<td>{$site_user->get('Last Login')}</td>
				</tr>
			</table>
		</div>
		<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Failed Login Count{/t}:</td>
					<td>{$site_user->get('Failed Login Count')}</td>
				</tr>
				<tr>
					<td>{t}Failed Last Login{/t}:</td>
					<td>{$site_user->get('Last Failed Login')}</td>
				</tr>
			</table>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $block_view=='login_history'}selected{/if}" id="login_history"> <span> {t}Login History{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pages_visit'}selected{/if}" id="pages_visit"> <span> {t}Pages Visit{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>

		<div id="block_pages_visit" style="{if $block_view!='pages_visit'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=2  }
			<div id="table2" class="data_table_container dtable btable" style="font-size:85%"> </div>
		</div>


		<div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Login History{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_access" style="{if $block_view!='access'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
	</div>
</div>
<div id="dialog_change_password" style="width:300px;padding:20px 20px 10px 20px ">
	<table style="margin: 0px auto">
		<tr id="dialog_change_password_buttons">
			<td> 
			<div class="buttons">
				<button class="positive" id="send_reset_password">{t}Send Email{/t}</button> <button class="positive" id="change_password">{t}Set Password{/t}</button> <button class="negative" id="close_dialog_change_password">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px" id="dialog_change_password_wait">
			<td class="aright" style="padding-right:10px"> <img src="art/loading.gif" /> {t}Processing Request{/t} </td>
		</tr>
		<tr style="height:22px" id="dialog_change_password_response">
			<td class="aright" style="padding-right:10px" id="dialog_change_password_response_msg"> </td>
		</tr>
	</table>
</div>
<div id="dialog_set_password" style="width:300px;padding:20px 20px 10px 20px ">
	<table border="0" class="edit" style="width:100%" >
		<tr class="title">
			<td colspan="2">{t}Change Password{/t} </td>
		</tr>
		<tr>
			<td style="width:120px" class="label">{t}Password{/t}: </td>
			<td> 
			<input type="password" id="change_password_password1"></td>
		</tr>
		<tr>
			<td style="width:120px" class="label">{t}Confirm{/t}: </td>
			<td> 
			<input type="password" id="change_password_password2"></td>
		</tr>
		<input id="epwcp1" value="" type="hidden" />
		<input id="epwcp2" value="" type="hidden" />
		<tr style="height:10px">
			<td colspan="2"> 
			
			</td>
		</tr>
		<tr id="tr_change_password_buttons" class="button space">
			<td colspan="2"> 
			<div class="buttons">
				<button id="submit_change_password" class="positive">{t}Change Password{/t}</button> <button id="cancel_change_password" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		<tr id="tr_change_password_messages">
			<td colspan="2" style="text-align:right;padding-right:10px"><span style="display:none" id="change_password_error_no_password">{t}Write new password{/t}</span><span style="display:none" id="change_password_error_password_not_march">{t}Passwords don't match{/t}</span><span style="display:none" id="change_password_error_password_too_short">{t}Password is too short{/t}</span><span> </span> </td>
		</tr>
		<tr id="tr_change_password_error_message" style="display:none">
			<td colspan="2" style="text-align:right;padding-right:10px" id="change_password_error_message"></td>
		</tr>
		<tr id="tr_change_password_wait" style="display:none" class="button">
			<td colspan="2" class="aright"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px">{t}Submitting changes{/t}</span></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 