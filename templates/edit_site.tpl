{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="site_key" value="{$site->id}" />
	<input type="hidden" id="site_id" value="{$site->id}" />
	<input type="hidden" id="store_key" value="{$store_key}" />
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a> &rarr; {/if}<img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> {$site->get('Site URL')} ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
			<button style="margin-left:0px" onclick="window.location='site.php?id={$site->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <span class="main_title"> {t}Editing Site{/t}: <span id="title_name">{$site->get('Site Name')}</span> (<span id="title_url">{$site->get('Site URL')}</span>) </span> 
		</div>
		<div class="buttons" style="float:right">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $block_view=='general'}selected{/if}" id="general"> <span> {t}Configuration{/t}</span></span></li>
		<li> <span class="item {if $block_view=='components'}selected{/if}" id="components"> <span> {t}Components{/t}</span></span></li>
		<li> <span class="item {if $block_view=='users'}selected{/if}" id="users"> <span> {t}Users (Registration){/t}</span></span></li>
		<li> <span class="item {if $block_view=='theme'}selected{/if}" id="theme"> <span> {t}Theme{/t}</span></span></li>
		<li> <span class="item {if $block_view=='style'}selected{/if}" id="style"> <span> {t}Style{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pages'}selected{/if}" id="pages"> <span> {t}Pages{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div class="edit_block" style="{if $block_view!='users'}display:none{/if}" id="d_users">
		
		
			<table class="edit" border="0" style="width:890px;clear:both;margin:25px 0px 20px 20px">
						<tr class="title">
								<td colspan="3">{t}Registration{/t}</td>
							</tr>
						<tr>
							<td style="width:150px">{t}Registration Method{/t}:</td>
							<td style="width:250px"> 
							<input id="site_registration_method" value="sidebar" type="hidden" />
							<div class="buttons small" id="site_registration_method_buttons" style="float:left">
								<button dbvalue="Wholesale" id="registration_wholesale" class="site_registration_method {if $site->get('Site Registration Method')=='Wholesale'}selected{/if}"> {t}Wholesale{/t}</button> <button dbvalue="Simple" id="registration_simple" class="site_registration_method {if $site->get('Site Registration Method')=='Simple'}selected{/if}"> {t}Simple{/t}</button> <button dbvalue="None" id="registration_none" class="site_registration_method {if $site->get('Site Registration Method')=='None'}selected{/if}"> {t}None{/t}</button> 
							</div>
							</td>
							<td colspan="2"> 
							<div class="buttons left" style="margin-left:0px">
								<button id="reset_edit_registration" class="negative disabled">{t}Reset{/t}</button> 

								<button id="save_edit_registration" class="positive disabled">{t}Save{/t}</button> 
							</div>
							<span id="registration_msg"></span>
							</td>
							
							
						</tr>
						
						
						
				</table>
		
		
		<div class="buttons left small tabs" style="margin-top:0px;">
				 <button id="registration" class="first {if $users_block_view=='registration'}selected{/if}">{t}Registration{/t}</button> 
				 <button id="welcome" class="{if $users_block_view=='welcome'}selected{/if}">{t}Welcome{/t}</button> 
				 <button id="forgot_password" class="{if $users_block_view=='forgot_password'}selected{/if}">{t}Forgot Password{/t}</button> 
				 <button id="email_provider" class="{if $users_block_view=='email_provider'}selected{/if}">{t}Email Provider{/t}</button> 
				 
				 <button id="client_profile" class="{if $users_block_view=='client_profile'}selected{/if}">{t}Client Profile{/t}</button> 
				 
			</div>
		
		
		
		
		<div class="tabs_base">
			</div>
			
		
						
			
			
			<div class="edit_block_content">
				<div class="edit_subblock" style="{if $users_block_view!='registration'}display:none{/if}" id="d_registration">
			
				<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Registration Properties{/t}</td>
						</tr>
						
						<tr>
							<td class="label" style="width:200px">{t}Registration Disclaimer{/t}: <span id="registration_disclaimer_msg"></span></td>
							<td style="text-align:left" colspan="2" style="width:550px"> 
							<div style="height:265px">
<textarea style="height:260px;width:600px;background-image:url(art/text_email_guide.png);" id="registration_disclaimer" value="{$site->get('Site Registration Disclaimer')|escape}" ovalue="{$site->get('Site Registration Disclaimer')|escape}" valid="0">{$site->get('Site Registration Disclaimer')}</textarea> 
								<div id="registration_disclaimer_Container">
								</div>
							</div>
							</td>
						</tr>
						
				
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_client_area" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_client_area" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
					
				</div>
				<div class="edit_subblock" style="{if $users_block_view!='client_profile'}display:none{/if}" id="d_client_profile">
					<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Client Area{/t}</td>
						</tr>
						
						
						<tr>
							<td class="label"  style="width:200px">{t}Show Badges{/t}:</td>
							<td style="width:550px"> 
							<input id="show_badges_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_badges_method_buttons">
								<button dbvalue="Yes" id="show_badges_Yes" class="show_badges_method {if $site->get('Show Site Badges')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="show_badges_No" class="show_badges_method {if $site->get('Show Site Badges')=='No'}selected{/if}"> {t}No{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Facebook URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Facebook_URL" value="{$site->get('Site Facebook URL')}" ovalue="{$site->get('Site Facebook URL')}"> 
								<div id="Site_Facebook_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Facebook_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Facebook{/t}:</td>
							<td> 
							<input id="show_facebook_method" value="sidebar" type="hidden" />
							<div class="buttons small left" id="show_facebook_method_buttons">
								<button style="display:{if $site->get('Site Facebook URL')==''}none{/if}" class="{if $site->get('Site Show Facebook')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Facebook','Yes')" id="Site Show Facebook_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Facebook')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Facebook','No')" id="Site Show Facebook_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Twitter URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Twitter_URL" value="{$site->get('Site Twitter URL')}" ovalue="{$site->get('Site Twitter URL')}"> 
								<div id="Site_Twitter_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Twitter_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Twitter{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Twitter URL')==''}none{/if}" class="{if $site->get('Site Show Twitter')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Twitter','Yes')" id="Site Show Twitter_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Twitter')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Twitter','No')" id="Site Show Twitter_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Skype URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Skype_URL" value="{$site->get('Site Skype URL')}" ovalue="{$site->get('Site Skype URL')}"> 
								<div id="Site_Skype_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Skype_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Skype{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Skype URL')==''}none{/if}" class="{if $site->get('Site Show Skype')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Skype','Yes')" id="Site Show Skype_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Skype')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Skype','No')" id="Site Show Skype_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}LinkedIn URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_LinkedIn_URL" value="{$site->get('Site LinkedIn URL')}" ovalue="{$site->get('Site LinkedIn URL')}"> 
								<div id="Site_LinkedIn_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_LinkedIn_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show LinkedIn{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site LinkedIn URL')==''}none{/if}" class="{if $site->get('Site Show LinkedIn')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show LinkedIn','Yes')" id="Site Show LinkedIn_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show LinkedIn')=='No'}selected{/if} negative" onclick="save_social_media('Site Show LinkedIn','No')" id="Site Show LinkedIn_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Flickr URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Flickr_URL" value="{$site->get('Site Flickr URL')}" ovalue="{$site->get('Site Flickr URL')}"> 
								<div id="Site_Flickr_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Flickr_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Flickr{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Flickr URL')==''}none{/if}" class="{if $site->get('Site Show Flickr')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Flickr','Yes')" id="Site Show Flickr_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Flickr')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Flickr','No')" id="Site Show Flickr_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Blog URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Blog_URL" value="{$site->get('Site Blog URL')}" ovalue="{$site->get('Site Blog URL')}"> 
								<div id="Site_Blog_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Blog_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Blog{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Blog URL')==''}none{/if}" class="{if $site->get('Site Show Blog')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Blog','Yes')" id="Site Show Blog_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Blog')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Blog','No')" id="Site Show Blog_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Digg URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Digg_URL" value="{$site->get('Site Digg URL')}" ovalue="{$site->get('Site Digg URL')}"> 
								<div id="Site_Digg_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Digg_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Digg{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Digg URL')==''}none{/if}" class="{if $site->get('Site Show Digg')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Digg','Yes')" id="Site Show Digg_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Digg')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Digg','No')" id="Site Show Digg_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Google+ URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Google_URL" value="{$site->get('Site Google URL')}" ovalue="{$site->get('Site Google URL')}"> 
								<div id="Site_Google_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Google_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Google+{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Google URL')==''}none{/if}" class="{if $site->get('Site Show Google')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Google','Yes')" id="Site Show Google_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Google')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Google','No')" id="Site Show Google_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}RSS URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_RSS_URL" value="{$site->get('Site RSS URL')}" ovalue="{$site->get('Site RSS URL')}"> 
								<div id="Site_RSS_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_RSS_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show RSS{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site RSS URL')==''}none{/if}" class="{if $site->get('Site Show RSS')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show RSS','Yes')" id="Site Show RSS_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show RSS')=='No'}selected{/if} negative" onclick="save_social_media('Site Show RSS','No')" id="Site Show RSS_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Youtube URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Youtube_URL" value="{$site->get('Site Youtube URL')}" ovalue="{$site->get('Site Youtube URL')}"> 
								<div id="Site_Youtube_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_Youtube_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Show Youtube{/t}:</td>
							<td> 
							<input id="show_twitter_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="show_twitter_method_buttons">
								<button style="display:{if $site->get('Site Youtube URL')==''}none{/if}" class="{if $site->get('Site Show Youtube')=='Yes'}selected{/if} positive" onclick="save_social_media('Site Show Youtube','Yes')" id="Site Show Youtube_Yes">{t}Show{/t}</button> <button class="{if $site->get('Site Show Youtube')=='No'}selected{/if} negative" onclick="save_social_media('Site Show Youtube','No')" id="Site Show Youtube_No">{t}Hide{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Newsletter Custom Label{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Newsletter_Custom_Label" value="{$site->get('Site Newsletter Custom Label')}" ovalue="{$site->get('Site Newsletter Custom Label')}"> 
								<div id="Site_Newsletter_Custom_Label_Container">
								</div>
							</div>
							</td>
							<td id="Site_Newsletter_Custom_Label_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Email Marketing Custom Label{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Email_Marketing_Custom_Label" value="{$site->get('Site Email Marketing Custom Label')}" ovalue="{$site->get('Site Email Marketing Custom Label')}"> 
								<div id="Site_Email_Marketing_Custom_Label_Container">
								</div>
							</div>
							</td>
							<td id="Site_Welcome EmailEmail_Marketing_Custom_Label_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Postal Marketing Custom Label{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Postal_Marketing_Custom_Label" value="{$site->get('Site Postal Marketing Custom Label')}" ovalue="{$site->get('Site Postal Marketing Custom Label')}"> 
								<div id="Site_Postal_Marketing_Custom_Label_Container">
								</div>
							</div>
							</td>
							<td id="Site_Postal_Marketing_Custom_Label_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_client_area" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_client_area" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
	            <div class="edit_subblock" style="{if $users_block_view!='email_provider'}display:none{/if}" id="d_email_provider">
		{include file='email_credential_splinter.tpl' site=$site email_credentials=$email_credentials} 
	</div>
			 	<div class="edit_subblock" style="{if $users_block_view!='welcome'}display:none{/if}" id="d_welcome">
			 		<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="2">{t}Welcome Email{/t}</td>
							<td> </td>
						</tr>
						<tr>
							<td class="label" style="width:200px">{t}Subject{/t}:</td>
							<td style="text-align:left;width:400px"> 
							<div>
								<input style="text-align:left;width:100%" id="welcome_subject" value="{$site->get('Site Welcome Email Subject')|escape}" ovalue="{$site->get('Site Welcome Email Subject')|escape}" valid="0"> 
								<div id="welcome_subject_Container">
								</div>
							</div>
							</td>
							<td id="welcome_subject_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Body Plain Text{/t}: <span id="welcome_body_plain_msg"></span></td>
							<td style="text-align:left" colspan="2"> 
							<div style="height:265px">
<textarea style="height:260px;width:600px;background-image:url(art/text_email_guide.png);" id="welcome_body_plain" value="{$site->get('Site Welcome Email Plain Body')|escape}" ovalue="{$site->get('Site Welcome Email Plain Body')|escape}" valid="0">{$site->get('Site Welcome Email Plain Body')}</textarea> 
								<div id="welcome_body_plain_Container">
								</div>
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Body HTML{/t}:</td>
							<td style="text-align:left"> 
							<div style="height:307px">
<textarea style="height:300px;width:600px" id="welcome_body_html" value="{$site->get('Site Welcome Email HTML Body')|escape}" ovalue="{$site->get('Site Welcome Email HTML Body')|escape}" valid="0">{$site->get('Site Welcome Email HTML Body')}</textarea> 
								<div id="welcome_body_html_Container">
								</div>
							</div>
							</td>
							<td id="welcome_body_html_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="title" style="display:none">
							<td colspan="2">{t}Welcome Message{/t}</td>
							<td> 
							<div class="buttons">
								<button id="save_edit_welcome_message" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_welcome_message" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
						<tr style="display:none">
							<td class="label">{t}Message{/t}:</td>
							<td style="text-align:left"> 
							<div style="height:307px">
<textarea style="height:300px;width:600px" id="welcome_source" value="{$site->get('Site Welcome Source')|escape}" ovalue="{$site->get('Site Welcome Source')|escape}" valid="0">{$site->get('Site Welcome Source')}</textarea> 
								<div id="welcome_source_Container">
								</div>
							</div>
							</td>
							<td id="welcome_source_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td colspan="3"> 
							<div class="buttons">
								<button id="save_edit_email_welcome" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_email_welcome" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
	
					</table>
			 	</div>
				<div class="edit_subblock" style="{if $users_block_view!='forgot_password'}display:none{/if}" id="d_forgot_password">
						 	<table class="edit" border="0" style="width:100%">
						
						<tr class="title">
							<td colspan="2">{t}Forgot Password Email{/t}</td>
							<td> </td>
						</tr>
						<tr>
							<td class="label" style="width:200px">{t}Subject{/t}:</td>
							<td style="text-align:left" style="width:550px"> 
							<div>
								<input style="text-align:left;width:100%" id="forgot_password_subject" value="{$site->get('Site Forgot Password Email Subject')|escape}" ovalue="{$site->get('Site Forgot Password Email Subject')|escape}" valid="0"> 
								<div id="forgot_password_subject_Container">
								</div>
							</div>
							</td>
							<td id="forgot_password_subject_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Body Plain Text{/t}: <span id="forgot_password_body_plain_msg"></span></td>
							<td style="text-align:left" colspan="2"> 
							<div style="height:305px">
<textarea style="height:260px;width:600px;background-image:url(art/text_email_guide.png);" id="forgot_password_body_plain" value="{$site->get('Site Forgot Password Email Plain Body')|escape}" ovalue="{$site->get('Site Forgot Password Email Plain Body')|escape}" valid="0">{$site->get('Site Forgot Password Email Plain Body')}</textarea> 
								<div id="forgot_password_body_plain_Container">
								</div>
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Body HTML{/t}:</td>
							<td style="text-align:left"> 
							<div style="height:305px">
<textarea style="height:300px;width:600px" id="forgot_password_body_html" value="{$site->get('Site Forgot Password Email HTML Body')|escape}" ovalue="{$site->get('Site Forgot Password Email HTML Body')|escape}" valid="0">{$site->get('Site Forgot Password Email HTML Body')}</textarea> 
								<div id="forgot_password_body_html_Container">
								</div>
							</div>
							</td>
							<td id="forgot_password_body_html_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td colspan="3"> 
							<div class="buttons">
								<button id="save_edit_email_forgot" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_email_forgot" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>

			
			</div>
		</div>
	
		<div class="edit_block" style="{if $block_view!='components'}display:none{/if}" id="d_components">
			<div class="buttons left small tabs">
				<button id="head" class="first{if $components_block_view=='head'}selected{/if}">{t}Includes{/t}</button> <button id="headers" class="{if $components_block_view=='headers'}selected{/if}">{t}Headers{/t}</button> <button id="footers" class="{if $components_block_view=='footers'}selected{/if}">{t}Footers{/t}</button> <button id="menu" class="{if $components_block_view=='menu'}selected{/if}">{t}Menus{/t}</button> <button id="website_search" class="{if $components_block_view=='website_search'}selected{/if}">{t}Search{/t}</button>  <button id="checkout" class="{if $components_block_view=='checkout'}selected{/if}">{t}Checkout{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content">
				<div class="edit_subblock" style="{if $components_block_view!='headers'}display:none{/if}" id="d_headers">
					<div class='buttons small'>
						<button id="new_header"><img src="art/icons/add.png" alt=""> {t}New Header{/t}</button> <button id="show_upload_header"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
					</div>
					<div style="clear:both">
						<span class="clean_table_title">{t}Headers{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
						<div id="table2" class="data_table_container dtable btable">
						</div>
					</div>
				</div>
				<div class="edit_subblock" style="{if $components_block_view!='website_search'}display:none{/if}" id="d_website_search">
					<table class="edit" border="0" id="site_search_edit_table" style="width:100%">
						<tr class="title">
								<td colspan="3">{t}Search{/t}</td>
							</tr>
						
						<tr>
							<td class="label" style="width:120px">{t}Search method{/t}:</td>
							<td colspan="2"> 
							<input type="hidden" id="site_search_method" value="{$site->get('Site Search Method')}" ovalue="{$site->get('Site Search Method')}"> 
							<div id="site_search_method_buttons" class="buttons left small">
								<button id="Search_Inikoo" method="Inikoo" class="site_search_method {if $site->get('Site Search Method')=='Inikoo'}selected{/if}">{t}Inikoo{/t}</button> <button id="Search_Custome" method="Custome" class="site_search_method {if $site->get('Site Search Method')=='Custome'}selected{/if}">{t}Custome{/t}</button> 
							</div>
							<span id="site_search_method_msg"></span> </td>
						</tr>
						<tbody id="Search_Inikoo_tbody" style="{if $site->get('Site Search Method')!='Inikoo'}display:none{/if}">
						</tbody>
						<tbody id="Search_Custome_tbody" style="{if $site->get('Site Search Method')!='Custome'}display:none{/if}">
							<tr class="space10" style="height:207px">
								<td class="label" style="width:120px">{t}Search HTML{/t}:</td>
								<td style="width:400px"> 
								<div>
<textarea id="site_search_html" style="width:100%;height:200px" value="{$site->get('Site Search HTML')|escape}" ovalue="{$site->get('Site Search HTML')|escape}">{$site->get('Site Search HTML')}</textarea> 
									<div id="site_search_html_Container">
									</div>
								</div>
								</td>
								<td style="width:200px"> 
								<div class='buttons small'>
									<button id="show_upload_search"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
								</div>
								<div id="site_search_html_msg">
								</div>
								</td>
							</tr>
							<tr style="height:207px">
								<td class="label" style="width:120px">{t}Search CSS{/t}:</td>
								<td style="width:400px"> 
								<div>
<textarea id="site_search_css" style="width:100%;height:200px" value="{$site->get('Site Search CSS')|escape}" ovalue="{$site->get('Site Search CSS')|escape}">{$site->get('Site Search CSS')}</textarea> 
									<div id="site_search_css_Container">
									</div>
								</div>
								</td>
								<td style="width:200px"> 
								<div id="site_search_css_msg">
								</div>
								</td>
							</tr>
							<tr style="height:207px">
								<td class="label" style="width:120px">{t}Search Javascript{/t}:</td>
								<td style="width:400px"> 
								<div>
<textarea id="site_search_javascript" style="width:100%;height:200px" value="{$site->get('Site Search Javascript')|escape}" ovalue="{$site->get('Site Search Javascript'|escape)}">{$site->get('Site Search Javascript')}</textarea> 
									<div id="site_search_javascript_Container">
									</div>
								</div>
								</td>
								<td style="width:200px"> 
								<div id="site_search_javascript_msg">
								</div>
								</td>
							</tr>
						</tbody>
						<tr class="buttons">
							<td colspan="3"> 
							<div class="buttons left" style="margin-left:250px">
								<button id="reset_edit_site_search" class="disabled negative">{t}Reset{/t}</button> <button id="save_edit_site_search" class="disabled positive">{t}Save{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="edit_subblock" style="{if $components_block_view!='menu'}display:none{/if}" id="d_menu">
					<table class="edit" border="0" id="site_menu_edit_table" style="width:100%">
						<tr>
							<td colspan="3"> 
							<div class="buttons small">
								<button id="show_upload_menu"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
							</div>
							</td>
						</tr>
						<tr style="height:207px">
							<td class="label" style="width:120px">{t}Menu HTML{/t}:</td>
							<td style="width:400px"> 
							<div>
<textarea id="site_menu_html" style="width:100%;height:200px" value="{$site->get('Site Menu HTML')|escape}" ovalue="{$site->get('Site Menu HTML')|escape}">{$site->get('Site Menu HTML')}</textarea> 
								<div id="site_menu_html_Container">
								</div>
							</div>
							</td>
							<td style="width:200px"> 
							<div id="site_menu_html_msg">
							</div>
							</td>
						</tr>
						<tr style="height:207px">
							<td class="label" style="width:120px">{t}Menu CSS{/t}:</td>
							<td style="width:400px"> 
							<div>
<textarea id="site_menu_css" style="width:100%;height:200px" value="{$site->get('Site Menu CSS')|escape}" ovalue="{$site->get('Site Menu CSS')|escape}">{$site->get('Site Menu CSS')}</textarea> 
								<div id="site_menu_css_Container">
								</div>
							</div>
							</td>
							<td style="width:200px"> 
							<div id="site_menu_css_msg">
							</div>
							</td>
						</tr>
						<tr style="height:207px">
							<td class="label" style="width:120px">{t}Menu Javascript{/t}:</td>
							<td style="width:400px"> 
							<div>
<textarea id="site_menu_javascript" style="width:100%;height:200px" value="{$site->get('Site Menu Javascript')|escape}" ovalue="{$site->get('Site Menu Javascript')|escape}">{$site->get('Site Menu Javascript')}</textarea> 
								<div id="site_menu_javascript_Container">
								</div>
							</div>
							</td>
							<td style="width:200px"> 
							<div id="site_menu_javascript_msg">
							</div>
							</td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_menu" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_menu" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="edit_subblock" style="{if $components_block_view!='footers'}display:none{/if}" id="d_footers">
					<div class='buttons small'>
						<button id="new_footer"><img src="art/icons/add.png" alt=""> {t}New Footer{/t}</button> <button id="show_upload_footer"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
					</div>
					<div style="clear:both">
						<span class="clean_table_title">{t}Footer{/t}</span> {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
						<div id="table3" class="data_table_container dtable btable">
						</div>
					</div>
				</div>
				<div class="edit_subblock" style="{if $components_block_view!='checkout'}display:none{/if}" id="d_checkout">
					<table class="edit" border="0" style="width:100%">
						<tbody style="border-top: 0px solid white" id="website_checkout">
							<tr class="title">
								<td colspan="3">{t}Checkout{/t}</td>
							</tr>
							<tr>
								<td class="label" style="width:120px">{t}Checkout Method{/t}:</td>
								<td> 
								<input id="Site_Checkout_Method" value="{$site->get('Site Checkout Method')}" ovalue="{$site->get('Site Checkout Method')}" type="hidden" />
								<div class="buttons small" id="site_checkout_method_buttons" style="float:left">
									<button id="AW" class="site_checkout_method {if $site->get('Site Checkout Method')=='AW'}selected{/if}"><img src="art/icons/cart.png" alt="" /> {t}AW{/t}</button> <button id="Mals" class="site_checkout_method {if $site->get('Site Checkout Method')=='Mals'}selected{/if}"><img src="art/icons/cart.png" alt="" /> {t}E-Mals Commerce{/t}</button> <button id="Inikoo" class="site_checkout_method {if $site->get('Site Checkout Method')=='Inikoo'}selected{/if}"><img src="art/icons/cart.png" alt="" /> {t}Inikoo{/t}</button> 
								</div>
								</td>
								<td style="width:300px"> 
								<td id="Site_Checkout_Method_msg" class="edit_td_alert"></td>
								</td>
							</tr>
							<tr id="checkout_id_tr" style="{if $site->get('Site Checkout Method')!='Mals'}display:none{/if}">
								<td class="label">{t}Checkout ID{/t}:</td>
								<td style="text-align:left"> 
								<div>
									<input style="text-align:left;width:100%" id="Site_Checkout_ID" value="{$site->get_checkout_data('id')}" ovalue="{$site->get_checkout_data('id')}" valid="0"> 
									<div id="Site_Checkout_ID_Container">
									</div>
								</div>
								</td>
								<td id="Site_Checkout_ID_msg" class="edit_td_alert"></td>
							</tr>
							<tr id="checkout_url_tr" style="{if $site->get('Site Checkout Method')=='Inikoo'}display:none{/if}">
								<td class="label">{t}Checkout URL{/t}</td>
								<td style="text-align:left"> 
								<div>
									<input style="text-align:left;width:100%" id="Site_Checkout_URL" value="{$site->get_checkout_data('url')}" ovalue="{$site->get_checkout_data('url')}" valid="0"> 
									<div id="Site_Checkout_URL_Container">
									</div>
								</div>
								</td>
								<td id="Site_Checkout_URL_msg" class="edit_td_alert"></td>
							</tr>
						</tbody>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons left" style="margin-left:250px">
								<button id="reset_edit_site_checkout" class="negative disabled">{t}Reset{/t}</button> 

								<button id="save_edit_site_checkout" class="positive disabled">{t}Save{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="edit_subblock" style="{if $components_block_view!='head'}display:none{/if}" id="d_head">
					<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Code Includes{/t}</td>
						</tr>
						<tr class="first">
							<td class="label" style="width:150px">{t}Head{/t}:</td>
							<td style="width:600px"> 
							<div style="height:350px">
<textarea style="width:100%;height:100%" id="head_content" changed="0" value="{$site->get('Site Head Include')|escape}" ovalue="{$site->get('Site Head Include')|escape}">{$site->get('Site Head Include')}</textarea> 
								<div id="head_content_Container">
								</div>
							</div>
							</td>
							<td id="head_content_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="first">
							<td class="label" style="width:150px">{t}Body{/t}:</td>
							<td style="width:600px"> 
							<div style="height:350px">
<textarea style="width:100%;height:100%" id="body_content" changed="0" value="{$site->get('Site Body Include')|escape}" ovalue="{$site->get('Site Body Include')|escape}">{$site->get('Site Body Include')}</textarea> 
								<div id="body_content_Container">
								</div>
							</div>
							</td>
							<td id="body_content_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_includes" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_includes" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='general'}display:none{/if}" id="d_general">
			<div class="buttons left small tabs">
				<button id="website_properties" class="first {if $general_block_view=='website_properties'}selected{/if}">{t}Website Properties{/t}</button> <button id="website_ftp" class="{if $general_block_view=='website_ftp'}selected{/if}">{t}FTP Configuration{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content">
				<div class="edit_subblock" style="{if $general_block_view!='website_properties'}display:none{/if}" id="d_website_properties">
					<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Website Properties{/t}</td>
						</tr>
						<tr>
							<td class="label" style="width:200px">{t}Locale{/t}:</td>
							<td> 
							<input id="site_locale_method" value="sidebar" type="hidden" />
							<select class="buttons" id="site_locale_method_buttons" onchange="change_locale_method(this)" style="float:left">
								<option value="en_GB" id="locale_en_GB" {if $site->get('Site Locale')=='en_GB'}selected{/if}> en_GB</option>
								<option value="de_DE" id="locale_de_DE" {if $site->get('Site Locale')=='de_DE'}selected{/if}> de_DE</option>
								<option value="fr_FR" id="locale_fr_FR" {if $site->get('Site Locale')=='fr_FR'}selected{/if}> fr_FR</option>
								<option value="es_ES" id="locale_es_ES" {if $site->get('Site Locale')=='es_ES'}selected{/if}> es_ES</option>
								<option value="pl_PL" id="locale_pl_PL" {if $site->get('Site Locale')=='pl_PL'}selected{/if}> pl_PL</option>
								<option value="it_IT" id="locale_it_IT" {if $site->get('Site Locale')=='it_IT'}selected{/if}> it_IT</option>
							</select>
							</td>
						</tr>
						<tr>
							<td class="label">{t}URL{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_URL" value="{$site->get('Site URL')}" ovalue="{$site->get('Site URL')}" valid="0"> 
								<div id="Site_URL_Container">
								</div>
							</div>
							</td>
							<td id="Site_URL_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Name{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Name" value="{$site->get('Site Name')}" ovalue="{$site->get('Site Name')}" valid="0"> 
								<div id="Site_Name_Container">
								</div>
							</div>
							</td>
							<td id="Site_Name_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Slogan{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_Slogan" value="{$site->get('Site Slogan')}" ovalue="{$site->get('Site Slogan')}" valid="0"> 
								<div id="Site_Slogan_Container">
								</div>
							</div>
							</td>
							<td id="Site_Slogan_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Telephone{/t}:</td>
							<td> 
							<div>
								<input style="width:100%" id="telephone" changed="0" type='text' maxlength="255" class='text' value="{$site->get('Site Contact Telephone')}" ovalue="{$site->get('Site Contact Telephone')}" />
								<div id="telephone_Container">
								</div>
							</div>
							</td>
							<td id="telephone_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Address{/t}:</td>
							<td> 
							<div style="height:120px">
<textarea style="width:100%" id="address" changed="0" value="{$site->get('Site Address')}" ovalue="{$site->get('Site Contact Address')}" rows="6" cols="42">{$site->get('Site Contact Address')}</textarea> 
								<div id="address_Container">
								</div>
							</div>
							</td>
							<td id="address_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_properties" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_properties" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="edit_subblock" style="{if $general_block_view!='website_ftp'}display:none{/if}" id="d_website_ftp">
					<table class="edit" border="0" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Ftp Credentials{/t}</td>
						</tr>
						<tr>
							<td class="label" style="width:200px">{t}Protocol{/t}:</td>
							<td style="width:300px"> 
							<input id="ftp_protocol_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="ftp_protocol_method_buttons">
								<button dbvalue="SFTP" id="ftp_protocol_SFTP" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='SFTP'}selected{/if}"> {t}SFTP{/t}</button> <button dbvalue="FTP" id="ftp_protocol_FTP" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='FTP'}selected{/if}"> {t}FTP{/t}</button> <button dbvalue="FTPS" id="ftp_protocol_FTPS" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='FTPS'}selected{/if}"> {t}FTPS{/t}</button> 
							</div>
							</td>
						</tr>
						<tr id="tbody_ftp_passive" style="display:{if $site->get('Site FTP Protocol')=='SFTP'}none{/if}">
							<td class="label">{t}FTP Passive{/t}:</td>
							<td> 
							<input id="ftp_passive_method" value="sidebar" type="hidden" />
							<div class="buttons left small" id="ftp_passive_method_buttons">
								<button dbvalue="Yes" id="ftp_passive_Yes" class="ftp_passive_method {if $site->get('Site FTP Passive')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="ftp_passive_No" class="ftp_passive_method {if $site->get('Site FTP Passive')=='No'}selected{/if}"> {t}No{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td class="label">{t}Server{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_FTP_Server" value="{$site->get('Site FTP Server')}" ovalue="{$site->get('Site FTP Server')}"> 
								<div id="Site_FTP_Server_Container">
								</div>
							</div>
							</td>
							<td id="Site_FTP_Server_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Port{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="ftp_port" value="{$site->get('Site FTP Port')}" ovalue="{$site->get('Site FTP Port')}"> 
								<div id="ftp_port_Container">
								</div>
							</div>
							</td>
							<td id="ftp_port_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}User{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_FTP_User" value="{$site->get('Site FTP User')}" ovalue="{$site->get('Site FTP User')}"> 
								<div id="Site_FTP_User_Container">
								</div>
							</div>
							</td>
							<td id="Site_FTP_User_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Password{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input type="password" style="text-align:left;width:100%" id="Site_FTP_Password" value="{$site->get('Site FTP Password')}" ovalue="{$site->get('Site FTP Password')}"> 
								<div id="Site_FTP_Password_Container">
								</div>
							</div>
							</td>
							<td id="Site_FTP_Password_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Directory{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Site_FTP_Directory" value="{$site->get('Site FTP Directory')}" ovalue="{$site->get('Site FTP Directory')}"> 
								<div id="Site_FTP_Directory_Container">
								</div>
							</div>
							</td>
							<td id="Site_FTP_Directory_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button id="save_edit_site_ftp" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_site_ftp" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='theme'}display:none{/if}" id="d_theme">
			<div class="edit_block_content">
				<div class="todo" style="font-size:80%;width:50%">
					<h1>
						Themes 
					</h1>
					<h2>
						Here you will choose wish theme you want for your website 
					</h2>
					<h3>
						Objective 
					</h3>
					<p>
						The following site layouts should hard coded in smarty 
						<ul>
							<li>1)Header/Content Area (Left menu 20%)/ Footer. (ALREADY DONE! see the files in sites/templates) 
							<li>2)Header/Content Area (Right menu 20%)/ Footer. (TODO) 
							<li>3)Header/Content Area / Footer with site map . (TODO) 
							<li>4) Any other you can imagine (TODO) 
						</ul>
					</p>
					<h3>
						Notes 
					</h3>
					<p>
						Template 1 is already done in sites/templates (tpl files should be renamed so _left_menu is found in the tpl filename) 
					</p>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='style'}display:none{/if}" id="d_style">
			<div class="buttons small left tabs">
				<button id="background" class="first {if $style_block_view=='background'}selected{/if}">{t}Background{/t}</button> <button id="favicon" class="{if $style_block_view=='favicon'}selected{/if}">{t}Favicon{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content">
				<div class="edit_subblock" style="{if $style_block_view!='background'}display:none{/if}" id="d_background">
				</div>
				<div class="edit_subblock" style="{if $style_block_view!='favicon'}display:none{/if}" id="d_favicon">
					<table>
						<tr>
							<td> 
							<form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
								<input id="upload_image_input" style="border:1px solid #ddd;" type="file" name="testFile" />
							</form>
							</td>
							<td> 
							<div class="buttons left">
								<button id="uploadButton" class="positive">{t}Upload{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
					<div id="images" class="edit_images" principal="{$site->get_main_image_key()}">
						{foreach from=$site->get_images_slidesshow() item=image name=foo} 
						<div id="image_container{$image.id}" class="image" image_id="{$image.id}" is_principal="{$image.is_principal}">
							<div class="image_name" id="image_name{$image.id}">
								{$image.name} 
							</div>
							<img class="delete" src="art/icons/delete.png" alt="{t}Delete{/t}" title="{t}Delete{/t}" onclick="delete_image(this)"> <img class="picture" src="{$image.normal_url}" /> 
							<div class="operations">
								<img id="img_principal{$image.id}" style="{if $image.is_principal=='Yes'}{else}display:none{/if}" title="{t}Main Image{/t}" src="art/icons/bullet_star.png"> <img id="img_set_principal{$image.id}" style="{if $image.is_principal=='Yes'}display:none{else}{/if}" onclick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}" src="art/icons/bullet_gray_star.png"> <img id="img_edit_caption{$image.id}" onclick="edit_caption(this)" src="art/icons/caption.gif" alt="{t}Edit Caption{/t}" title="{t}Edit Caption{/t}"> <img id="img_save_caption{$image.id}" style="display:none" onclick="save_caption(this)" src="art/icons/bullet_gray_disk.png" alt="{t}Save Caption{/t}" title="{t}Save Caption{/t}"> <img id="img_reset_caption{$image.id}" style="display:none" onclick="reset_caption(this)" src="art/icons/bullet_come.png" alt="{t}Reset Caption{/t}" title="{t}Reset Caption{/t}"> 
							</div>
							<span class="caption" id="caption{$image.id}">{$image.caption}</span> <textarea class="edit_caption" style="display:none" onkeyup="caption_changed(this)" id="edit_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption}</textarea> 
						</div>
						{/foreach} 
						<div id="image_footer" style="clear:both">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='pages'}display:none{/if}" id="d_pages">
			<div class="edit_block_content">
				<div class="data_table" style="clear:both;">
					<span class="clean_table_title" style="margin-right:5px">{t}Pages{/t}</span> 
					<div class="buttons small left">
						<button id="new_page" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
					</div>
					<div class="table_top_bar">
					</div>
					<div class="clusters">
						<div class="buttons small left cluster">
							<button class="{if $pages_view=='page_properties'}selected{/if}" id="page_properties">{t}Page Properties{/t}</button> <button class="{if $pages_view=='page_html_head'}selected{/if}" id="page_html_head">{t}HTML Head{/t}</button> <button class="{if $pages_view=='page_header'}selected{/if}" id="page_header">{t}Header{/t}</button> 
						</div>
						<div style="clear:both">
						</div>
					</div>
					{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 } 
					<div id="table4" style="font-size:90%" class="data_table_container dtable btable">
					</div>
				</div>
			</div>
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
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
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
<div id="filtermenu4" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu4 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',4)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu5" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu5 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',5)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu7" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu7 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',7)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu6" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu6 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',6)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},3)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_upload_header" style="padding:30px 10px 10px 10px;width:320px">
	<table style="margin:0 auto">
		<form enctype="multipart/form-data" method="post" id="upload_header_form">
			<input type="hidden" name="parent_key" value="{$site->id}" />
			<input type="hidden" name="parent" value="site" />
			<input id="upload_header_use_file" type="hidden" name="use_file" value="" />
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_header_file" style="border:1px solid #ddd;" type="file" name="file" />
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="processing_upload_header" style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> <button class="positive" id="upload_header">{t}Upload{/t}</button> <button id="cancel_upload_header" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_footer" style="padding:30px 10px 10px 10px;width:320px">
	<table style="margin:0 auto">
		<form enctype="multipart/form-data" method="post" id="upload_footer_form">
			<input type="hidden" name="parent_key" value="{$site->id}" />
			<input type="hidden" name="parent" value="site" />
			<input id="upload_footer_use_file" type="hidden" name="use_file" value="" />
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_footer_file" style="border:1px solid #ddd;" type="file" name="file" />
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="processing_upload_footer" style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> <button class="positive" id="upload_footer">{t}Upload{/t}</button> <button id="cancel_upload_footer" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_menu" style="padding:30px 10px 10px 10px;width:320px">
	<table style="margin:0 auto">
		<form enctype="multipart/form-data" method="post" id="upload_menu_form">
			<input type="hidden" name="parent_key" value="{$site->id}" />
			<input type="hidden" name="parent" value="site" />
			<input id="upload_menu_use_file" type="hidden" name="use_file" value="" />
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_menu_file" style="border:1px solid #ddd;" type="file" name="file" />
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="processing_upload_menu" style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> <button class="positive" id="upload_menu">{t}Upload{/t}</button> <button id="cancel_upload_menu" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_search" style="padding:30px 10px 10px 10px;width:320px">
	<table style="margin:0 auto">
		<form enctype="multipart/form-data" method="post" id="upload_search_form">
			<input type="hidden" name="parent_key" value="{$site->id}" />
			<input type="hidden" name="parent" value="site" />
			<input id="upload_search_use_file" type="hidden" name="use_file" value="" />
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_search_file" style="border:1px solid #ddd;" type="file" name="file" />
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="processing_upload_search" style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> <button class="positive" id="upload_search">{t}Upload{/t}</button> <button id="cancel_upload_search" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_header_files" style="padding:30px 10px 10px 10px;width:420px">
	<table style="margin:0 auto">
		<tr>
			<td> 
			<div style="margin-bottom:10px">
				{t}Multiple files found, please select one{/t}. 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div id="upload_header_files" class="buttons left small">
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button id="cancel_upload_header_files" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_footer_files" style="padding:30px 10px 10px 10px;width:420px">
	<table style="margin:0 auto">
		<tr>
			<td> 
			<div style="margin-bottom:10px">
				{t}Multiple files found, please select one{/t}. 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div id="upload_footer_files" class="buttons left small">
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button id="cancel_upload_footer_files" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_menu_files" style="padding:30px 10px 10px 10px;width:420px">
	<table style="margin:0 auto">
		<tr>
			<td> 
			<div style="margin-bottom:10px">
				{t}Multiple files found, please select one{/t}. 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div id="upload_menu_files" class="buttons left small">
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button id="cancel_upload_menu_files" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_upload_search_files" style="padding:30px 10px 10px 10px;width:420px">
	<table style="margin:0 auto">
		<tr>
			<td> 
			<div style="margin-bottom:10px">
				{t}Multiple files found, please select one{/t}. 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div id="upload_search_files" class="buttons left small">
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button id="cancel_upload_search_files" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_new_page" style="padding:20px 20px 10px 20px ">
	<div id="new_page_msg" class="error" style="padding:5px 10px;padding-top:0px;display:none">
	</div>
	<div id="new_page_wait" style="display:none">
		<img src="art/loading.gif"> {t}Processing Request{/t} 
	</div>
	<div id="new_page_buttons" class="buttons small">
		<button id="show_department_list">{t}Department{/t}</button> <button id="show_family_list">{t}Family{/t}</button> <button id="show_product_list">{t}Product{/t}</button> <button id="show_family_category_list">{t}Family Category{/t}</button> <button id="show_product_category_list">{t}Product Category{/t}</button> <button onclick="new_page('site',0)">{t}Custome Information{/t}</button> <button class="negative" id="close_dialog_new_page">{t}Cancel{/t}</button> 
	</div>
</div>
<div id="dialog_department_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Department List{/t}</span> {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5} 
			<div id="table5" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_family_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6} 
			<div id="table6" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_product_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Product List{/t}</span> {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7} 
			<div id="table7" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_family_category_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family Categories List{/t}</span> {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8} 
			<div id="table8" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_product_category_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Product Categories List{/t}</span> {include file='table_splinter.tpl' table_id=9 filter_name=$filter_name9 filter_value=$filter_value9} 
			<div id="table9" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 