 {include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 

<input type="hidden" value="splinter_type" id="{$splinter_type}" />
<input type="hidden" value="splinter_key" id="{$splinter_key}" />

	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a> &rarr; {/if}<img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> {$site->get('Site URL')} (<a href="store.php?id={$store->id}"> {$store->get('Store Code')}</a>)</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{$title}</span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit_block=='description'}selected{/if}" id="description"> <span> {t}Content{/t}</span></span></li>
		<li> <span class="item {if $edit_block=='images'}selected{/if}" id="images"> <span> {t}Images{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="description_block" style="{if $edit_block!='description'}display:none{/if}">
			
			<table style="margin:0; width:100%" class="edit" border="0">
				
			<tr class="title">
						<td colspan="2"> 
						<div class="buttons left">
						</div>
						<div style="float:right" id="html_editor_msg">
						</div>
						<div class="buttons small">
							<button style="display:none" id="download_page_content">{t}Download{/t}</button> <button id="show_upload_page_content"> <img src="art/icons/page_save.png" alt="" /> {t}Import{/t}</button> <button class="positive disabled" id="save_edit_page_content">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_page_content">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:5px 0"> 
						<form onsubmit="return false;">
							<textarea id="html_editor">{$splinter.content}</textarea> 
						</form>
						</td>
					</tr>
				<tr class="buttons">
				<td colspan=2>
				<div class="buttons">
				<button style="margin-right:10px" id="save_edit_location_description" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px" id="reset_edit_location_description" class="negative disabled">{t}Reset{/t}</button> 
			</div>
				</td>
				</tr>
				
			
				
				
			</table>
		</div>
		<div id="images_block" style="{if $edit_block!='images'}display:none{/if}">
			
		</div>
	</div>
</div>

{include file='footer.tpl'} 