{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<input type="hidden" id="search_type" value="{$search_type}" />
		<input type="hidden" id="subject" value="{$subject}" />
		<input type="hidden" id="parent" value="{$parent}" />
		<input type="hidden" id="parent_key" value="{$parent_key}" />
		{if $subject=='customers'} {include file='contacts_navigation.tpl'} 
		<div class="branch">
			<span>{if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Import Customers{/t} (1/3)</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Import customers{/t}</span> 
			</div>
			<div class="buttons" style="float:right">
				<a class="negative" href="customers.php?store={$store->id}">{t}Cancel{/t}</a> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		{/if} 
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='upload_file'}selected{/if}" id="upload_file"> <span> {t}Upload File{/t}</span></span></li>
		<li> <span class="item {if $block_view=='import_history'}selected{/if}" id="import_history"> <span> {t}Previous imported records{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-top:1px solid #ccc">
	</div>
	<div style="padding:0 20px;padding-bottom:30px">
		<div id="block_upload_file" class="data_table" style="{if $block_view!='upload_file'}display:none{/if};clear:both;">
			<table class="edit" style="margin-top:20px;width:100%" border="0">
				<form enctype="multipart/form-data" method="post" id="upload_form">
					<tr>
						<td style="width:70px" class="label">{t}CSV File{/t}:</td>
						<td style="width:250px"> 
						<input id="upload_import_file" style="border:1px solid #ddd;" type="file" name="import_file" />
						</td>
						<td class="error" id="upload_msg"> </td>
					</tr>
				</form>
				<tr class="buttons">
					<td colspan="2"> 
					<div class="buttons">
						<button class="positive disabled" id="save_upload_button">{t}Upload & Preview{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div id="block_import_history" class="data_table" style="{if $block_view!='import_history'}display:none{/if};clear:both;padding:20px 0px">
		
			<span class="clean_table_title">{t}Import History{/t} 
			</span> 
			<div class="elements_chooser">
				<div id="part_use_chooser" >
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Uploading}selected{/if} " id="elements_Uploading" table_type="Uploading">{t}Uploading{/t} (<span id="elements_Uploading_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Review}selected{/if} " id="elements_Review" table_type="Review">{t}Reviewing{/t} (<span id="elements_Review_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Queued}selected{/if} " id="elements_Queued" table_type="Queued">{t}Queued{/t} (<span id="elements_Queued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.InProcess}selected{/if} " id="elements_InProcess" table_type="InProcess">{t}Importing{/t} (<span id="elements_InProcess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Finished}selected{/if} " id="elements_Finished" table_type="Finished">{t}Imported{/t} (<span id="elements_Finished_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

				</div>
				
			</div>
			<div class="table_top_bar space">
			</div>
		
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table2" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		
		</div>
	</div>
</div>	
	{include file='footer.tpl'} 