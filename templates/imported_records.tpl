{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<input type="hidden" id="search_type" value="{$search_type}"> 
		<input type="hidden" id="subject" value="{$subject}"> 
		<input type="hidden" id="parent" value="{$parent}"> 
		<input type="hidden" id="parent_key" value="{$parent_key}"> {if $subject=='customers'} {include file='contacts_navigation.tpl'} 
		<div style="display:" class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Importing Customers{/t} (3/3)</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				<div class="buttons" style="float:left">
					<span class="main_title"><img src="art/icons/agenda.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$store->get('Store Code')}</span> <span class="subtitle">{t}Imported Customers{/t}</span></span> 
				</div>
			</div>
			<div class="buttons" style="float:right">
				{if $imported_records->get('Imported Records State')!='Finished'} <button id="cancel_import" class="negative"><img src="art/icons/cross.png" alt=""> {t}Cancel{/t}</button> {/if} 
			</div>
			<div style="clear:both">
			</div>
		</div>
		{/if} 
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $block_view=='records'}selected{/if}" id="records"> <span> {t}Imported Records{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-top:1px solid #ccc">
	</div>
	<div style="padding:0 20px;padding-bottom:30px">
		<div id="block_overview" class="data_table" style="{if $block_view!='overview'}display:none{/if};clear:both;">
			<table class="report_sales" style="margin-top:20px;{if $imported_records->get('Imported Records State')=='Finished'}display:none{/if}">
				<tr>
					<td>{t}To do records{/t}</td>
					<td id="records_todo"></td>
					<td id="records_todo_comments"></td>
				</tr>
				<tr>
					<td>{t}Imported Records{/t}</td>
					<td id="records_imported"></td>
					<td id="records_imported_comments"></td>
				</tr>
				<tr>
					<td>{t}Ignored{/t}</td>
					<td id="records_ignored"></td>
					<td id="records_ignored_comments"></td>
				</tr>
				<tr>
					<td>{t}Errors{/t}</td>
					<td id="records_error"></td>
					<td id="records_error_comments"></td>
				</tr>
			</table>
		</div>
		
				<div id="block_records" class="data_table" style="{if $block_view!='records'}display:none{/if};clear:both;padding:20px 0px">
			<div>
				<span class="clean_table_title">{t}Records{/t} </span> 
				<div class="elements_chooser">
					<div id="part_use_chooser">
					
					'Ignored','Waiting','Importing','Imported','Error'
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Uploading}selected{/if} " id="elements_Uploading" table_type="Uploading">{t}Uploading{/t} (<span id="elements_Uploading_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Review}selected{/if} " id="elements_Review" table_type="Review">{t}Reviewing{/t} (<span id="elements_Review_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Queued}selected{/if} " id="elements_Queued" table_type="Queued">{t}Queued{/t} (<span id="elements_Queued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.InProcess}selected{/if} " id="elements_InProcess" table_type="InProcess">{t}Importing{/t} (<span id="elements_InProcess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Finished}selected{/if} " id="elements_Finished" table_type="Finished">{t}Imported{/t} (<span id="elements_Finished_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					</div>
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>

		
	</div>
</div>
{include file='footer.tpl'} 