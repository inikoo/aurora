{include file='header.tpl'} 
<div id="bd" style="padding:0">
	<input type="hidden" id="site_key" value="{$site->id}" />
	<input type="hidden" id="site_id" value="{$site->id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
	<input type="hidden" id="page_key" value="{$page->id}" />
	<input type="hidden" id="redirect_review" value="{$redirect_review}" />
	<input type="hidden" id="take_snapshot" value="{$take_snapshot}" />
	<input type="hidden" id="content_height" value="{$page->get('Page Content Height')}" />
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a> &rarr;{/if} <img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> <a href="site.php?id={$site->id}">{$site->get('Site URL')}</a> (<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a>) &rarr; <img style="vertical-align:-1px;" src="art/icons/layout_bw.png" alt="" /> {$page->get('Page Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button style="margin-left:0px" onclick="window.location='page.php?id={$page->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button class="negative" id="delete_page"><img src="art/icons/cross.png" alt="" /> {t}Delete{/t}</button> <button id="show_upload_page_content"> <img src="art/icons/page_save.png" alt="" /> {t}Import{/t}</button> {if isset($referral_data)} <button onclick="{$referral_data.url}'"><img src="art/icons/door_out.png" alt="" /> {$referral_data.label}</button> {/if} <button onclick="window.location='page_preview.php?id={$page->id}&logged=1&update_heights=1'"><img src="art/icons/layout.png" alt=""> {t}View Page{/t}</button> <button style="display:none" id="show_dialog_template_list"><img src="art/icons/layout.png" alt=""> {t}Create{/t}</button> 
			</div>
			<div class="buttons left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} 
			
			<span class="main_title">
				<span class="id" id="title_code">{$page->get('Page Code')}</span> <span style="font-size:80%;color:#777" id="title_url">{$page->get('Page URL')}</span> 
			</span>
			
			</div>
			<div style="clear:both">
			</div>
		</div>
		
		<ul class="tabs" id="chooser_ul">
			<li> <span class="item {if $block_view=='setup'}selected{/if}" id="setup"><span> {t}Page Properties{/t}</span></span></li>
			<li style="display:none"><span class="item {if $block_view=='properties'}selected{/if}" id="properties"> <span>{t}HTML Setup{/t}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='page_header'}selected{/if}" id="page_header"> <span> {t}Header{/t}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='page_footer'}selected{/if}" id="page_footer"> <span> {t}Footer{/t}</span></span></li>
			<li> <span class="item {if $block_view=='content'}selected{/if}" id="content"><span> {if $page->get('Page Code')=='register'}Registration Form{else}{t}Content{/t}{/if}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='style'}selected{/if}" id="style"><span> {t}Style{/t}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='media'}selected{/if}" id="media"><span> {t}Media{/t}</span></span></li>
			<li> <span class="item {if $block_view=='url'}selected{/if}" id="url"><span>{t}Redirections{/t}</span></span></li>
		</ul>
	</div>
	<div id="tabbed_container" class="tabbed_container" style="padding:10px 0px;margin:0px {if $block_view=='content'}0px{else}20px{/if}">
		<div class="edit_block" style="{if $block_view!='url' }display:none{/if}" id="d_url">
			<table class="edit" style="width:880px;clear:both;margin-left:20px;margin-top:20px">
				<tr class="title">
					<td colspan="1">{t}Redirections{/t}</td>
					<td colspan="2"> 
					<div class="buttons ">
						<button id="show_dialog_add_redirection">Add Redirection</button> 
					</div>
					</td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
				<tr>
					<td style="width:120px" class="label">{t}301 Rederections{/t}:</td>
					<td style="width:700px"> 
					<table>
						{foreach from=$page->get_all_redirects_data(true) item=redirect} 
						<tr style="height:20px">
							<td style="padding:0">{$redirect.Source}</td>
							<td style="padding:0;padding-left:10px"> <img onclick="delete_redirect({$redirect.PageRedirectionKey})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}" /> </td>
							<td style="padding:0;padding-left:10px;"><a href="htaccess.php?page_key={$page->id}&redirection_key={$redirect.PageRedirectionKey}"> <img src="art/icons/page_white_h.png" alt="{t}.htaccess file{/t}" title="{t}.htacces file{/t}" /></a> </td>
							<td style="padding:0;padding-left:10px;"> <img style="{if $redirect.CanUpload=='Yes'}display:none{/if}" src="art/icons/error.png" alt="{t}Can't update .htaccess{/t}" title="{t}Can't update .htacces{/t}" /> <img style="{if $redirect.CanUpload=='No'}display:none{/if};cursor:pointer" src="art/icons/ftp_up.png" alt="{t}Upload .htaccess{/t}" title="{t}Upload .htacces{/t}" /> </td>
						</tr>
						{/foreach} 
					</table>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="{if $block_view!='setup' }display:none{/if}" id="d_setup">
			<table class="edit" border="0" id="edit_family_page" style="width:880px;clear:both;margin-left:20px;margin-top:0px" page_key="{$page->id}">
				
				<tr style="display:none">
					<td colspan="3"> 
					
					<div class="buttons small">
						<button id="show_more_configuration">{t}Show Advanced Configuration{/t}</button> <button style="display:none" id="hide_more_configuration">{t}Hide Advanced Configuration{/t}</button> 
					</div>
					
					</td>
				</tr>
					<tr class="title">
					<td colspan="3"> 
					
						{t}Page Configuration{/t}
					
					</td>
				</tr>
				
				<tr class="top">
					<td></td>
				</tr>
				
				<tr>
					<td style="width:120px" class="label">{t}Page Code{/t}:</td>
					<td style="width:400px"> 
					<div>
						<input style="width:100%" id="page_properties_page_code" value="{$page->get('Page Code')}" ovalue="{$page->get('Page Code')}" />
						<div id="page_properties_page_code_Container">
						</div>
					</div>
					</td>
					<td> 
					<div style="font-size:80%;color:red" id="page_properties_page_code_msg">
					</div>
					</td>
				</tr>
				<tr>
					<td style="width:120px" class="label">{t}Link Label{/t}:</td>
					<td style="width:400px"> 
					<div>
						<input style="width:100%" id="page_properties_link_title" value="{$page->get('Page Short Title')}" ovalue="{$page->get('Page Short Title')}" />
						<div id="page_properties_link_title_Container">
						</div>
					</div>
					</td>
					<td> 
					<div id="page_properties_link_title_msg">
					</div>
					</td>
				</tr>
				<tr style="height:87px">
					<td class="label" style="width:120px">{t}Description{/t}:</td>
					<td style="width:400px"> 
					<div >
<textarea id="page_html_head_resume" style="width:404px;height:80px" value="{$page->get('Page Store Resume')}" ovalue="{$page->get('Page Store Resume')}">{$page->get('Page Store Resume')}</textarea> 
						
						<div id="page_html_head_resume_Container">
						</div>
					</div>
					</td>
					<td><span id="page_html_head_resume_msg"></span>
					</td>
				</tr>
				<tbody id="advanced_configuration" >
					<tr style="display:none">
						<td style="width:120px" class="label">{t}Creation Method{/t}:</td>
						<td style="width:400px"> 
						<table>
							<tr>
								<td style="padding:0;" class="label">{t}External body & HTML HEAD{/t}:</td>
								<td> 
								<input layout="thumbnails" id="checkbox_thumbnails" type="checkbox" {if $page->get('Page Store Type') =="External Content and HTML HEAD"}checked="checked"{/if} ></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr style="display:none">
						<td style="width:120px" class="label">{t}Page Type{/t}:</td>
						<td style="width:400px"> 
						<table border="0">
							<tr>
								<td style="padding:0;" class="label">{t}Front Page Store{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Front Page Store"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Search{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Search"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Product Description{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Product Description"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Information{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Information"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Category Catalogue{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Category Catalogue"}checked="checked"{/if} ></td>
								<td>{if $page->get('Page Store Section') =="Category Catalogue"}{$page->get('Page Parent Code')}{/if} </td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Family Catalogue{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Family Catalogue"}checked="checked"{/if} ></td>
								<td style="padding:0;">{if $page->get('Page Store Section') =="Family Catalogue"}<a href="family.php?id={$page->get('Page Parent Key')}">{$page->get('Page Parent Code')}</a>{/if} <span id="edit_parent_family" style="color:#777;font-style:italic;cursor:pointer">({t}Change{/t})</span></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Department Catalogue{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Department Catalogue"}checked="checked"{/if} ></td>
								<td style="padding:0;">{if $page->get('Page Store Section') =="Department Catalogue"}<a href="department.php?id={$page->get('Page Parent Key')}">{$page->get('Page Parent Code')}</a>{/if} </td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Store Catalogue{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Store Catalogue"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Registration{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Registration"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Client Section{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Client Section"}checked="checked"{/if} ></td>
							</tr>
							<tr>
								<td style="padding:0;" class="label">{t}Check Out{/t}:</td>
								<td> 
								<input layout="thumbnails" id="radio_thumbnails" type="radio" {if $page->get('Page Store Section') =="Check Out"}checked="checked"{/if} ></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td class="label" style="width:120px">{t}Browser Title{/t}:</td>
						<td style="width:400px"> 
						<div>
							<input id="page_html_head_title" style="width:100%" maxlength="64" value="{$page->get('Page Title')}" ovalue="{$page->get('Page Title')}" />
							<div id="page_html_head_title_msg">
							</div>
							<div id="page_html_head_title_Container">
							</div>
						</div>
						</td>
						<td> </td>
					</tr>
					<tr style="height:87px">
						<td class="label" style="width:120px">{t}Page Keywords{/t}:</td>
						<td style="width:400px"> 
						<div>
<textarea id="page_html_head_keywords" style="width:404px;height:80px" maxlength="24" value="{$page->get('Page Keywords')}" ovalue="{$page->get('Page Keywords')}">{$page->get('Page Keywords')}</textarea> 
							
							<div id="page_html_head_keywords_Container">
							</div>
						</div>
						</td>
						<td>
						<div id="page_html_head_keywords_msg">
							</div>
						</td>
					</tr>
				</tbody>
				<tr style="height:10px"><td colspan="3"></td></tr>
				<tr>
					<td colspan="2"> 
					
					<div class="buttons">
						<button id="save_edit_page_properties" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_page_properties" class="negative disabled">{t}Reset{/t}</button> 
					</div>
					</td>
					<td></td>
				</tr>
				
			</table>
		</div>
		<div class="edit_block" style="{if $block_view!='properties' }display:none{/if}" id="d_properties">
			<table class="edit" border="0" id="properties_edit_table" style="width:100%">
				<tr>
					<td colspan="3"> 
					<div class="buttons">
						<button id="save_edit_page_html_head" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_page_html_head" class="negative disabled">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="{if $block_view!='page_header' }display:none{/if}" id="d_page_header">
		</div>
		<div class="edit_block" style="{if $block_view!='page_footer' }display:none{/if}" id="d_page_footer">
		</div>
		<div class="edit_block" style="{if $block_view!='content'}display:none;{/if}padding:0px 0px;margin:0px" id="d_content">
			<div style="border-bottom:1px dotted #ddd;padding-bottom:5px;margin:0 20px">
				<div class="buttons">
					<button style="{if $content_view=='overview'}display:none{/if}" id="show_page_content_overview_block"><img src="art/icons/layout.png" alt="" /> {t}Content Overview{/t}</button> 
				</div>
				<div class="buttons left">
					<button id="show_page_header_block" class="{if $content_view=='header'}selected{/if}"><img src="art/icons/layout_header.png" alt="" /> {t}Header{/t}</button> <button id="show_page_footer_block" class="{if $content_view=='footer'}selected{/if}"><img src="art/icons/layout_footer.png" alt="" /> {t}Footer{/t}</button> <button id="show_page_content_block" class="{if $content_view=='content'}selected{/if}"><img src="art/icons/layout_content2.png" alt="" /> {t}Content{/t}</button> <button id="show_page_product_list_block" class="{if $content_view=='product_list'}selected{/if}"><img src="art/icons/text_list_bullets.png" alt="" /> {t}Lists{/t}</button> <button id="show_page_product_buttons_block" class="{if $content_view=='product_buttons'}selected{/if}"><img src="art/icons/bricks.png" alt="" /> {t}Products{/t}</button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			<div id="page_content_overview_block" style="{if $content_view!='overview'}display:none{/if};margin:10px 20px">
				<img id="page_preview_snapshot_image" style="width:470px" src="image.php?id={$page->get('Page Preview Snapshot Image Key')}" alt="{t}No Snapshot Available{/t}" /> 
			</div>
			<div style="{if $content_view!='header'}display:none{/if};margin:10px 20px" id="page_header_block">
				<table class="edit" border="0" id="header_edit_table" style="width:100%">
					<tr class="title">
						<td>Title</td>
						<td colspan="2"> 
						<div class="buttons">
							<button id="save_edit_page_header" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_page_header" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr style="height:10px">
						<td colspan="3"></td>
					</tr>
					<tr>
						<td class="label" style="width:120px">{t}Header Title{/t}:</td>
						<td style="width:500px"> 
						<div>
							<input id="page_header_store_title" style="width:100%" maxlength="64" value="{$page->get('Page Store Title')}" ovalue="{$page->get('Page Store Title')}" />
							<div id="page_header_store_title_msg">
							</div>
							<div id="page_header_store_title_Container">
							</div>
						</div>
						</td>
					</tr>
					<tr style="height:20px">
						<td colspan="3"></td>
					</tr>
					<tr class="title">
						<td colspan="2">{t}Parent Pages{/t}</td>
						<td> 
						<div class="buttons">
							<button id="add_other_found_in_page">Add page</button> 
						</div>
						</td>
					</tr>
					<tr style="height:10px">
						<td colspan="3"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}Found in{/t}:</td>
						<td style="width:500px"> 
						<table>
							{foreach from=$page->get_found_in() item=found_in_page} 
							<tr style="Height:20px">
								<td style="padding:0">{$found_in_page.found_in_label}</td>
								<td style="padding:0;padding-left:10px"><img onclick="delete_found_in_page({$found_in_page.found_in_key})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}" /></td>
							</tr>
							{/foreach} 
						</table>
						</td>
						<td></td>
					</tr>
					<tr style="height:10px">
						<td colspan="3"></td>
					</tr>
					<tr class="title">
						<td colspan="2">{t}Related Pages{/t}</td>
						<td> 
						<div class="buttons">
							<button id="add_other_see_also_page" {if $page->get('Page Store See Also Type')=='Auto'}style="display:none"{/if} >{t}Add page{/t}</button> <button id="add_auto_see_also_page" {if $page->get('Page Store See Also Type')!='Auto'}style="display:none"{/if} >{t}Add Page{/t}</button> <button id="remove_auto_see_also_page" {if $page->get('Page Store See Also Type')!='Auto' or $page->get('Number See Also Links')==0}style="display:none"{/if} >{t}Remove Page{/t}</button> 
						</div>
						</td>
					</tr>
					<tr style="height:10px">
						<td colspan="3"></td>
					</tr>
					<tr>
						<td style="width:120px" class="label">{t}See also{/t}:</td>
						<td style="width:500px"> 
						<div id="see_also_type" default_cat="" class="buttons left">
							<button class="{if $page->get('Page Store See Also Type')=='Auto'}selected{/if}" onclick="save_see_also_type('Auto')" id="see_also_type_Auto">{t}Auto{/t}</button> <button class="{if $page->get('Page Store See Also Type')=='Manual'}selected{/if}" onclick="save_see_also_type('Manual')" id="see_also_type_Manual">{t}Manual{/t}</button> 
						</div>
						</td>
					</tr>
					<tr style="height:10px">
						<td colspan="3"></td>
					</tr>
					<tr>
						<td></td>
						<td> 
						<table>
							{foreach from=$page->get_see_also($site->get('Site URL')) item=see_also_page} 
							<tr style="Height:20px">
								<td style="padding:0">{$see_also_page.see_also_label} (<a href="page.php?id={$see_also_page.see_also_key}">{$see_also_page.see_also_code}</a>)</td>
								<td style="padding:0 10px;font-style:italic;color:#777">{$see_also_page.see_also_correlation_formated} {$see_also_page.see_also_correlation_formated_value}</td>
								<td style="padding:0;padding-left:10px;{if $page->get('Page Store See Also Type')=='Auto'}display:none{/if}"><img onclick="delete_see_also_page({$see_also_page.see_also_key})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}" /></td>
							</tr>
							{/foreach} 
						</table>
						</td>
						<td> </td>
					</tr>
				</table>
				<div style="clear:both">
					<span class="clean_table_title">{t}Headers{/t}</span> {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8 } 
					<div id="table8" class="data_table_container dtable btable">
					</div>
				</div>
			</div>
			<div style="{if $content_view!='footer'}display:none{/if};margin:10px 20px" id="page_footer_block">
				<span class="clean_table_title">{t}Footers{/t}</span> {include file='table_splinter.tpl' table_id=9 filter_name=$filter_name9 filter_value=$filter_value9 } 
				<div id="table9" class="data_table_container dtable btable">
				</div>
			</div>
			<div style="{if $content_view!='product_list'}display:none{/if};margin:10px 20px" id="page_product_list_block">
				<div id="product_lists" style="width:890px;margin-bottom:20px">
					<span class="clean_table_title">{t}Lists{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
					<div id="table2" style="font-size:80%" class="data_table_container dtable btable">
					</div>
				</div>
				{*} 
				<div id="product_lists" style="width:890px;margin-bottom:20px">
					<span class="clean_table_title">{t}List Items{/t}</span> {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8 } 
					<div id="table8" style="font-size:80%" class="data_table_container dtable btable">
					</div>
				</div>
				{*} 
			</div>
			<div style="{if $content_view!='product_buttons'}display:none{/if};margin:10px 20px" id="page_product_buttons_block">
				<div id="product_buttons" style="width:925px">
					<span class="clean_table_title">{t}Products{/t}</span> {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
					<div id="table3" class="data_table_container dtable btable" style="font-size:85%">
					</div>
				</div>
			</div>
			<div style="{if $content_view!='content'}display:none{/if};" style="display:none" id="page_content_block">
				<table class="edit" id="content_edit_table" style="width:810px;padding:0px;margin:0;position:relative;left:-2px">
					<tr class="title">
						<td colspan="2"> 
						<div class="buttons left">
						</div>
						<div style="float:right" id="html_editor_msg">
						</div>
						<div class="buttons">
							<button id="download_page_content">{t}Download{/t}</button> <button class="positive disabled" id="save_edit_page_content">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_page_content">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:5px 0"> 
						<form onsubmit="return false;">
<textarea id="html_editor">{$page->get('Page Store Source')}</textarea> 
						</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="edit_block" style="{if $block_view!='style' }display:none{/if}" id="d_style">
		</div>
		<div class="edit_block" style="{if $block_view!='media' }display:none{/if}" id="d_media">
		</div>
		<div class="edit_block" style="{if $block_view!='products' }display:none{/if}padding:20px;" id="d_products">
		</div>
	</div>
	
	<div style="clear:both;padding:20px">
		<div class="buttons small" style="margin-top:0">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id='1' filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
	
	</div>
	
	
	
	<div style="clear:both;height:20px">
	</div>
</div>
<div id="dialog_upload_page_content" style="padding:30px 10px 10px 10px;width:320px;">
	<table style="margin:0 auto">
		<form enctype="multipart/form-data" method="post" id="upload_page_content_form">
			<input type="hidden" name="parent_key" value="{$page->id}" />
			<input type="hidden" name="parent" value="page" />
			<input id="upload_page_content_use_file" type="hidden" name="use_file" value="" />
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_page_content_file" style="border:1px solid #ddd;" type="file" name="file" />
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="processing_upload_page_content" style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> <button class="positive" id="upload_page_content">{t}Upload{/t}</button> <button id="cancel_upload_page_content" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_add_redirection" style="padding:30px 20px 10px 10px;width:350px;position:absolute;top:-2000px;">
	<table style="margin:0 auto;width:100%" border="0">
		<tr>
			<td>{t}File{/t}:</td>
			<td> 
			<input id="add_redirect_source" style="border:1px solid #ddd;width:100%" type="text" name="file" />
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"> </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" id="save_add_redirection">{t}Add{/t}</button> <button id="cancel_add_redirection" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		<tr id="add_redirect_wait">
			<td colspan="2"> <span style="float:right;display:none"><img src="art/loading.gif" alt=""> {t}Processing{/t}</span> </td>
		</tr>
		<tr>
			<td colspan="2" id="add_redirect_msg"> </td>
		</tr>
	</table>
</div>
<div id="dialog_upload_page_content_files" style="padding:30px 10px 10px 10px;width:420px;position:absolute;top:-2000px;">
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
			<div id="upload_page_content_files" class="buttons left small">
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button id="cancel_upload_page_content_files" class="negative">{t}Cancel{/t}</button><br />
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_delete_page" style="padding:20px 10px 10px 10px;text-align:left;position:absolute;top:-2000px;width:320px">
	<h2 style="padding-top:0px">
		{t}Delete Page{/t} 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting page, wait please{/t} 
	</div>
	<div id="delete_page_buttons" class="buttons">
		<button id="save_delete_page" class="positive">{t}Yes, delete it!{/t}</button> <button id="cancel_delete_page" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
<div id="dialog_family_list" style="position:absolute;top:-2000px;">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4} 
			<div id="table4" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_page_list" style="position:absolute;top:-2000px;">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:500px">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Page List{/t}</span> {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7} 
			<div id="table7" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_template_list" style="position:absolute;top:-2000px;">
	<table border="0" style="margin-top:20px; width:100%">
		<tr>
			<td class="buttons"><button onclick="add_template('Template', '')">Add Custom Template</button></td>
		</tr>
		<tr>
			<td class="buttons"><button onclick="set_template('Source', '')">PPP</button></td>
		</tr>
		<tr>
			<td class="buttons"><button onclick="set_template('Template', 'template_list_left')">Template 1</button></td>
		</tr>
		<tr>
			<td class="buttons"><button onclick="set_template('Template', 'template_single_button')">Template 2</button></td>
		</tr>
	</table>
</div>
<iframe id="page_preview_iframe" src="page_preview.php?id={$page->id}&logged=1&take_snapshot={$take_snapshot}&update_heights=1" frameborder="1" style="position:absolute;top:-10000px;left:-200px;width:1x;height:1px" onload="redirect_to_preview()"> 
<p>
	{t}Your browser does not support iframes{/t}. 
</p>
</iframe> {include file='footer.tpl'} 