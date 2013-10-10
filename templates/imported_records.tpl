{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<input type="hidden" id="search_type" value="{$search_type}"> 
		<input type="hidden" id="subject" value="{$subject}"> 
		<input type="hidden" id="parent" value="{$parent}"> 
		<input type="hidden" id="parent_key" value="{$parent_key}"> 
				<input type="hidden" id="imported_records_key" value="{$imported_records->id}"> 
		<input type="hidden" id="state_records" value="{$state_records}" />
		<input type="hidden" id="gettext_strings" value="{$gettext_strings}" />

		
		{if $subject=='customers'} {include file='contacts_navigation.tpl'} 
		<div style="display:" class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; 
			{if $imported_records->get('Imported Records State')=='Finished'}<a href="import.php?subject={$subject}&parent={$parent}&parent_key={$parent_key}">{t}Imported Records{/t}</a> &rarr; {$imported_records->get('Imported Records File Name')}{else}{t}Importing Customers{/t} (3/3){/if}</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				<div class="buttons" style="float:left">
					<span class="main_title no_buttons">{t}Imported Records{/t} <span class="subtitle">{$imported_records->get('Imported Records File Name')}</span></span> 
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
	<div class="tabs_base">
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
			<table class="report_sales" style="margin-top:20px;{if $imported_records->get('Imported Records State')!='Finished'}display:none{/if}">
				<tr>
					<td>{t}Filename{/t}:</td>
					<td>{$imported_records->get('Imported Records File Name')} ({$imported_records->get('Filesize')})</td>
				</tr>
						<tr>
					<td>{t}Date{/t}:</td>
					<td class="aright">{$imported_records->get('Imported Records Finish Date')}</td>
				</tr>
				
				<tr>
					<td>{t}Imported Records{/t}:</td>
					<td class="aright">{$imported_records->get('Imported')}</td>
				</tr>
				<tr>
					<td>{t}Ignored{/t}:</td>
					<td class="aright">{$imported_records->get('Ignored')}</td>
				
				</tr>
				<tr>
					<td>{t}Errors{/t}</td>
					<td class="aright">{$imported_records->get('Errors')}</td>
				</tr>
			</table>
			
			
			
		</div>
		
				<div id="block_records" class="data_table" style="{if $block_view!='records'}display:none{/if};clear:both;padding:20px 0px">
			<div>
				<span class="clean_table_title">{t}Records{/t} </span> 
				<div class="elements_chooser">
					<div id="part_use_chooser">
											<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Error}selected{/if} " id="elements_Error" table_type="Error">{t}Error{/t} (<span id="elements_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Ignored}selected{/if} " id="elements_Ignored" table_type="Ignored">{t}Ignored{/t} (<span id="elements_Ignored_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
								<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Waiting}selected{/if} " id="elements_Waiting" table_type="Waiting">{t}Waiting{/t} (<span id="elements_Waiting_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Importing}selected{/if} " id="elements_Importing" table_type="Importing">{t}Importing{/t} (<span id="elements_Importing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Imported}selected{/if} " id="elements_Imported" table_type="Imported">{t}Imported{/t} (<span id="elements_Imported_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

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