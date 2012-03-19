{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="site_key" value="{$site->id}" />
	<input type="hidden" id="site_id" value="{$site->id}" />
	<input type="hidden" id="store_key" value="{$store_key}" />
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a> &rarr; {/if}<img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> {$site->get('Site URL')} (<a href="store.php?id={$store->id}"> {$store->get('Store Code')}</a>)</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
			<button style="margin-left:0px" onclick="window.location='site.php?id={$site->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		<span class="main_title">
		{t}Editing Site{/t}: <span id="title_name">{$site->get('Site Name')}</span> (<span id="title_url">{$site->get('Site URL')}</span>) 
		</span>
		</div>
		<div class="buttons" style="float:right">
		
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $block_view=='general'}selected{/if}" id="general"> <span> {t}General{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='layout'}selected{/if}" id="layout"> <span> {t}Layout{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='style'}selected{/if}" id="style"> <span> {t}Style{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='sections'}selected{/if}" id="sections"> <span> {t}Sections{/t}</span></span></li>
		<li> <span class="item {if $block_view=='headers'}selected{/if}" id="headers"> <span> {t}Headers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='footers'}selected{/if}" id="footers"> <span> {t}Footers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='menu'}selected{/if}" id="menu"> <span> {t}Menu{/t}</span></span></li>
		<li> <span class="item {if $block_view=='website_search'}selected{/if}" id="website_search"> <span> {t}Search{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pages'}selected{/if}" id="pages"> <span> {t}Pages{/t}</span></span></li>
		<li> <span class="item {if $block_view=='email'}selected{/if}" id="email"> <span> {t}Registration{/t}</span></span></li>
		<li> <span class="item {if $block_view=='favicon'}selected{/if}" id="favicon"> <span> {t}Favicon{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">

		<div class="edit_block" style="{if $block_view!='favicon'}display:none{/if}" id="d_favicon">
			<table>
			<tr><td>
			<form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
				<input id="upload_image_input" style="border:1px solid #ddd;" type="file" name="testFile"/>
			</form>
			</td>
			<td>
			<div class="buttons left">
			<button  id="uploadButton" class="positive">{t}Upload{/t}</button>
			</div>
			</td></tr>
			</table>
			<div  id="images" class="edit_images" principal="{$site->get_main_image_key()}" >
			
			
			{foreach from=$site->get_images_slidesshow() item=image  name=foo}
			<div id="image_container{$image.id}" class="image"  image_id="{$image.id}" is_principal="{$image.is_principal}" >
			<div class="image_name" id="image_name{$image.id}">{$image.name}</div>
			<img class="delete" src="art/icons/delete.png" alt="{t}Delete{/t}" title="{t}Delete{/t}" onClick="delete_image(this)">
			<img class="picture" src="{$image.normal_url}"    /> 
			<div class="operations">
				
				<img id="img_principal{$image.id}"  style="{if $image.is_principal=='Yes'}{else}display:none{/if}"  title="{t}Main Image{/t}"  src="art/icons/bullet_star.png">
				<img id="img_set_principal{$image.id}" style="{if $image.is_principal=='Yes'}display:none{else}{/if}"  onClick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}"  src="art/icons/bullet_gray_star.png">
			
			<img id="img_edit_caption{$image.id}" onClick="edit_caption(this)" src="art/icons/caption.gif" alt="{t}Edit Caption{/t}" title="{t}Edit Caption{/t}">
			<img id="img_save_caption{$image.id}" style="display:none" onClick="save_caption(this)" src="art/icons/bullet_gray_disk.png" alt="{t}Save Caption{/t}" title="{t}Save Caption{/t}">
			<img id="img_reset_caption{$image.id}" style="display:none" onClick="reset_caption(this)" src="art/icons/bullet_come.png" alt="{t}Reset Caption{/t}" title="{t}Reset Caption{/t}">
			</div>
			<span class="caption" id="caption{$image.id}" >{$image.caption}</span> 
				<textarea class="edit_caption" style="display:none" onkeyup="caption_changed(this)" id="edit_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption}</textarea>
			
			</div>
			{/foreach}
			<div id="image_footer" style="clear:both"></div>
			</div>
		</div>

		<div class="edit_block" style="{if $block_view!='website_search'}display:none{/if}" id="d_website_search">
			<div class='buttons'>
				<button id="show_upload_search"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
			</div>
			<table class="edit" border="0" id="site_search_edit_table" style="width:100%">
				<tr>
					<td colspan="3"> 
					<div class="buttons">
						<button style="visibility:hidden" id="save_edit_site_search" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_site_search" class="negative">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
				<tr style="height:207px">
					<td class="label" style="width:120px">{t}Search HTML{/t}:</td>
					<td style="width:400px"> 
					<div>
<textarea id="site_search_html" style="width:100%;height:200px" value="{$site->get('Site Search HTML')|escape}" ovalue="{$site->get('Site Search HTML')|escape}">{$site->get('Site Search HTML')}</textarea> 
						<div id="site_search_html_Container">
						</div>
					</div>
					</td>
					<td style="width:200px"> 
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
			</table>
		</div>
		<div class="edit_block" style="{if $block_view!='menu'}display:none{/if}" id="d_menu">
			<div class='buttons'>
				<button id="show_upload_menu"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
			</div>
			<table class="edit" border="0" id="site_menu_edit_table" style="width:100%">
				<tr>
					<td colspan="3"> 
					<div class="buttons">
						<button style="visibility:hidden" id="save_edit_site_menu" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_site_menu" class="negative">{t}Reset{/t}</button> 
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
			</table>
		</div>
		<div class="edit_block" style="{if $block_view!='headers'}display:none{/if}" id="d_headers">
			<div class='buttons'>
				<button id="new_header"><img src="art/icons/add.png" alt=""> {t}New Header{/t}</button> <button id="show_upload_header"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
			</div>
			<div style="clear:both">
				<span class="clean_table_title">{t}Headers{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
				<div id="table2" class="data_table_container dtable btable ">
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='footers'}display:none{/if}" id="d_footers">
			<div class='buttons'>
				<button id="new_footer"><img src="art/icons/add.png" alt=""> {t}New Footer{/t}</button> <button id="show_upload_footer"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button> 
			</div>
			<div style="clear:both">
				<span class="clean_table_title">{t}Footer{/t}</span> {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
				<div id="table3" class="data_table_container dtable btable ">
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='general'}display:none{/if}" id="d_general">
			<table class="edit" border="0" style="width:100%">
				<tbody style="border-top: 5px solid white" id="Website_properties">
					<tr class="title">
						<td>{t}Website Properties{/t}</td>
						<td colspan="2"> 
						<div class="buttons">
							<button style="visibility:hidden" id="save_edit_site_properties" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_site_properties" class="negative">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Select Site Locale{/t}:</td>
						<td> 
						<input id="site_locale_method" value="sidebar" type="hidden" />
						<select class="buttons" id="site_locale_method_buttons" onchange="change_locale_method(this)" style="float:left">
							<option value="en_GB" id="locale_en_GB" {if $site->get('Site Locale')=='en_GB'}selected{/if}> {t}en_GB{/t}</option>
							<option value="de_DE" id="locale_de_DE" {if $site->get('Site Locale')=='de_DE'}selected{/if}> {t}de_DE{/t}</option>
							<option value="fr_FR" id="locale_fr_FR" {if $site->get('Site Locale')=='fr_FR'}selected{/if}> {t}fr_FR{/t}</option>
							<option value="es_ES" id="locale_es_ES" {if $site->get('Site Locale')=='es_ES'}selected{/if}> {t}es_ES{/t}</option>
							<option value="pl_PL" id="locale_pl_PL" {if $site->get('Site Locale')=='pl_PL'}selected{/if}> {t}pl_PL{/t}</option>
							<option value="it_IT" id="locale_it_IT" {if $site->get('Site Locale')=='it_IT'}selected{/if}> {t}it_IT{/t}</option>
						</select>
						</td>
					</tr>
					<tr>
						<td class="label" style="width:260px">{t}Website URL{/t}:</td>
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
						<td class="label">{t}Website Name{/t}:</td>
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
						<td class="label">{t}Website Slogan{/t}:</td>
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
						<td class="label">{t}Website Telephone{/t}:</td>
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
						<td class="label">{t}Website Address{/t}:</td>
						<td> 
						<div style="height:120px">
<textarea style="width:100%" id="address" changed="0" value="{$site->get('Site Address')}" ovalue="{$site->get('Site Contact Address')}" rows="6" cols="42">{$site->get('Site Contact Address')}</textarea> 
							<div id="address_Container">
							</div>
						</div>
						</td>
						<td id="address_msg" class="edit_td_alert"></td>
					</tr>
				</tbody>
				<tbody style="border-top: 5px solid white" id="website_profile">
					<tr class="title">
						<td>{t}Client Area{/t}</td>
					</tr>
					<tr>
						<td class="label">{t}Registration Method{/t}:</td>
						<td> 
						<input id="site_registration_method" value="sidebar" type="hidden" />
						<div class="buttons" id="site_registration_method_buttons" style="float:left">
							<button dbvalue="Wholesale" id="registration_wholesale" class="site_registration_method {if $site->get('Site Registration Method')=='Wholesale'}selected{/if}"> {t}Wholesale{/t}</button> <button dbvalue="Simple" id="registration_simple" class="site_registration_method {if $site->get('Site Registration Method')=='Simple'}selected{/if}"> {t}Simple{/t}</button> <button dbvalue="None" id="registration_none" class="site_registration_method {if $site->get('Site Registration Method')=='None'}selected{/if}"> {t}None{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Show Badges{/t}:</td>
						<td> 
						<input id="show_badges_method" value="sidebar" type="hidden" />
						<div class="buttons" id="show_badges_method_buttons" style="float:left">
							<button dbvalue="Yes" id="show_badges_Yes" class="show_badges_method {if $site->get('Show Site Badges')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="show_badges_No" class="show_badges_method {if $site->get('Show Site Badges')=='No'}selected{/if}"> {t}No{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Show Facebook{/t}:</td>
						<td> 
						<input id="show_facebook_method" value="sidebar" type="hidden" />
						<div class="buttons" id="show_facebook_method_buttons" style="float:left">
							<button dbvalue="Yes" id="show_facebook_Yes" class="show_facebook_method {if $site->get('Site Show Facebook')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="show_facebook_No" class="show_facebook_method {if $site->get('Site Show Facebook')=='No'}selected{/if}"> {t}No{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Show Twitter{/t}:</td>
						<td> 
						<input id="show_twitter_method" value="sidebar" type="hidden" />
						<div class="buttons" id="show_twitter_method_buttons" style="float:left">
							<button dbvalue="Yes" id="show_twitter_Yes" class="show_twitter_method {if $site->get('Site Show Twitter')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="show_twitter_No" class="show_twitter_method {if $site->get('Site Show Twitter')=='No'}selected{/if}"> {t}No{/t}</button> 
						</div>
						</td>
					</tr>
				</tbody>
				<tbody style="border-top: 10px solid white" id="website_checkout">
					<tr class="title">
						<td>{t}Checkout{/t}</td>
						<td colspan="2"> 
						<div class="buttons">
							<button style="visibility:hidden" id="save_edit_site_checkout" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_site_checkout" class="negative">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Select Checkout Method{/t}: </td>
						<td> 
						<input id="site_checkout_method" value="inikoo" type="hidden" />
						<div class="buttons" id="site_checkout_method_buttons" style="float:left">
							<button id="Mals" class="site_checkout_method {if $site->get('Site Checkout Method')=='Mals'}selected{/if}"><img src="art/icons/cart.png" alt="" /> {t}E-Mals Commerce{/t}</button> <button id="Inikoo" class="site_checkout_method {if $site->get('Site Checkout Method')=='Inikoo'}selected{/if}"><img src="art/icons/cart.png" alt="" /> {t}Inikoo{/t}</button> 
						</div>
						</td>
						<td style="width:300px"></td>
					</tr>
					<tr id="mals_id_tr" style="{if $site->get('Site Checkout Method')!='Mals'}display:none{/if}">
						<td class="label">{t}E-Mals Commerce ID{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100%" id="Site_Mals_ID" value="{$site->get_mals_data('id')}" ovalue="{$site->get_mals_data('id')}" valid="0"> 
							<div id="Site_Mals_ID_Container">
							</div>
						</div>
						</td>
						<td id="Site_Mals_ID_msg" class="edit_td_alert"></td>
					</tr>
					<tr id="mals_url1_tr" style="{if $site->get('Site Checkout Method')!='Mals'}display:none{/if}">
						<td class="label">{t}E-Mals Commerce URL{/t}</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100%" id="Site_Mals_URL" value="{$site->get_mals_data('url')}" ovalue="{$site->get_mals_data('url')}" valid="0"> 
							<div id="Site_Mals_URL_Container">
							</div>
						</div>
						</td>
						<td id="Site_Mals_URL_msg" class="edit_td_alert"></td>
					</tr>
					<tr id="mals_url2_tr" style="{if $site->get('Site Checkout Method')!='Mals'}display:none{/if}">
						<td class="label">{t}E-Mals Commerce URL (Multi){/t}</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100%" id="Site_Mals_URL_Multi" value="{$site->get_mals_data('url_multi')}" ovalue="{$site->get_mals_data('url_multi')}" valid="0"> 
							<div id="Site_Mals_URL_Multi_Container">
							</div>
						</div>
						</td>
						<td id="Site_Mals_URL_Multi_msg" class="edit_td_alert"></td>
					</tr>
				</tbody>
				<tbody style="border-top: 25px solid white" id="Website_ftp">
					<tr class="title">
						<td>{t}Website Ftp Credentials{/t}</td>
						<td colspan="2"> 
						<div class="buttons">
							<button style="visibility:hidden" id="save_edit_site_ftp" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_site_ftp" class="negative">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</tr>
				<tr>
					<td class="label">{t}Website FTP Protocol{/t}:</td>
					<td> 
					<input id="ftp_protocol_method" value="sidebar" type="hidden" />
					<div class="buttons" id="ftp_protocol_method_buttons" style="float:left">
						<button dbvalue="SFTP" id="ftp_protocol_SFTP" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='SFTP'}selected{/if}"> {t}SFTP{/t}</button> <button dbvalue="FTP" id="ftp_protocol_FTP" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='FTP'}selected{/if}"> {t}FTP{/t}</button> <button dbvalue="FTPS" id="ftp_protocol_FTPS" class="ftp_protocol_method {if $site->get('Site FTP Protocol')=='FTPS'}selected{/if}"> {t}FTPS{/t}</button> 
					</div>
					</td>
				</tr>
				<tr id="tbody_ftp_passive" style="display:{if $site->get('Site FTP Protocol')=='SFTP'}none{/if}">
					<td class="label">{t}Website FTP Passive{/t}:</td>
					<td> 
					<input id="ftp_passive_method" value="sidebar" type="hidden" />
					<div class="buttons" id="ftp_passive_method_buttons" style="float:left">
						<button dbvalue="Yes" id="ftp_passive_Yes" class="ftp_passive_method {if $site->get('Site FTP Passive')=='Yes'}selected{/if}"> {t}Yes{/t}</button> <button dbvalue="No" id="ftp_passive_No" class="ftp_passive_method {if $site->get('Site FTP Passive')=='No'}selected{/if}"> {t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Website FTP Server{/t}:</td>
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
					<td class="label">{t}Website FTP User{/t}:</td>
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
					<td class="label">{t}Website FTP Password{/t}:</td>
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
					<td class="label">{t}Website FTP Directory{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Site_FTP_Directory" value="{$site->get('Site FTP Directory')}" ovalue="{$site->get('Site FTP Directory')}"> 
						<div id="Site_FTP_Directory_Container">
						</div>
					</div>
					</td>
					<td id="Site_FTP_Directory_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Website FTP Port{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="ftp_port" value="{$site->get('Site FTP Port')}" ovalue="{$site->get('Site FTP Port')}"> 
						<div id="ftp_port_Container">
						</div>
					</div>
					</td>
					<td id="ftp_port_msg" class="edit_td_alert"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="edit_block" style="{if $block_view!='layout'}display:none{/if}" id="d_layout">
		<div class="todo" style="font-size:80%;width:50%">
			<h1>
				TO DO (KAKTUS-324) 
			</h1>
			<h2>
				Create Site Layouts 
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
		<div class="todo" style="font-size:80%;width:50%;margin-top:20px">
			<h1>
				TO DO (KAKTUS-325) 
			</h1>
			<h2>
				Edit Site Layout Form 
			</h2>
			<h3>
				Objective 
			</h3>
			<p>
				Form to edit Site default layout properties<br />
				<ul>
					<li>Choose layout type</li>
				</ul>
			</p>
		</div>
	</div>
	<div class="edit_block" style="{if $block_view!='style'}display:none{/if}" id="d_style">
		<div class="todo" style="font-size:80%;width:50%">
			<h1>
				TO DO (KAKTUS-326) 
			</h1>
			<h2>
				Site Style Properties (Colour,Backgrounds,Fonts) Edit Form 
			</h2>
			<h3>
				Objective 
			</h3>
			<p>
				Edit css properties for header, footer and content<br> 
				<ul>
					<li>Upload background images</li>
					<li>Colour Schemes</li>
				</ul>
			</p>
		</div>
	</div>
	<div class="edit_block" style="{if $block_view!='sections'}display:none{/if}" id="d_sections">
		<div class="todo" style="font-size:80%;width:50%">
			<h1>
				TO DO (KAKTUS-327) 
			</h1>
			<h2>
				Editable list of site sections 
			</h2>
			<h3>
				Objective 
			</h3>
			<p>
				YUI dynamic table with the site sections 
			</p>
			<h3>
				Notes 
			</h3>
			<p>
				DB table: `Page Store Section Dimension`<br> link to edit_site_section.php?id= 
			</p>
		</div>
	</div>
	<div class="edit_block" style="{if $block_view!='email'}display:none{/if}" id="d_email">
		{include file='email_credential_splinter.tpl' site=$site email_credentials=$email_credentials} 
		<table class="edit" border="0" style="width:100%">
			<tr class="title">
				<td colspan="2">{t}Welcome Email{/t}</td>
				<td> 
				<div class="buttons">
					<button style="visibility:hidden" id="save_edit_email_welcome" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_email_welcome" class="negative">{t}Reset{/t}</button> 
				</div>
				</td>
			</tr>
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
		<tr class="title">
			<td colspan="2">{t}Welcome Message{/t}</td>
			<td> 
			<div class="buttons">
				<button style="visibility:hidden" id="save_edit_welcome_message" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_welcome_message" class="negative">{t}Reset{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
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
		<tr class="title">
			<td colspan="2">{t}Forgot Password Email{/t}</td>
			<td> 
			<div class="buttons">
				<button style="visibility:hidden" id="save_edit_email_forgot" class="positive">{t}Save{/t}</button> <button style="visibility:hidden" id="reset_edit_email_forgot" class="negative">{t}Reset{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label">{t}Subject{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:100%" id="forgot_password_subject" value="{$site->get('Site Forgot Password Email Subject')|escape}" ovalue="{$site->get('Site Forgot Password Email Subject')|escape}" valid="0"> 
				<div id="forgot_password_subject_Container">
				</div>
			</div>
			</td>
			<td id="forgot_password_subject_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td class="label">{t}Body Plain Text{/t}: <span id="forgot_password_body_plain_msg"></spnn></td>
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
	</table>
</div>
<div class="edit_block" style="{if $block_view!='pages'}display:none{/if}" id="d_pages">
	<div class="general_options" style="float:right;display:none">
		TODO create page dialog from here <span style="margin-right:10px;" id="new_site_page" class="state_details">{t}Create Page{/t}</span> 
	</div>
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
<div id="rppmenu6" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu6 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},6)"> {$menu}</a></li>
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
{include file='footer.tpl'} 