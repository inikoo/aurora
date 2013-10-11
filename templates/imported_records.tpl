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
		<input type="hidden" id="imported_record_state" value="{$imported_records->get('Imported Records State')}"> 
		<input type="hidden" id="fork_key" value="{$imported_records->get('Imported Records Fork Key')}"> {if $subject=='customers'} {include file='contacts_navigation.tpl'} 
		<div style="display:" class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; 
			
			<span id="branch_finished" style="{if $imported_records->is_in_process()}display:none{/if}"><a href="import.php?subject={$subject}&parent={$parent}&parent_key={$parent_key}">{t}Imported Records{/t}</a> &rarr; {$imported_records->get('Imported Records File Name')}</span>
		
			<span id="branch_wait" style="{if !$imported_records->is_in_process()}display:none{/if}">{t}Importing Customers{/t} (3/3)</span>
			</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				<div class="buttons" style="float:left">
					<span id="title_container" class="main_title {if !$imported_records->is_in_process()}no_buttons{/if}">
					<span id="title_finished" style="{if $imported_records->is_in_process()}display:none{/if}">{t}Imported Records{/t} <span id="title_cancelled" style="{if $imported_records->get('Imported Records State')=='Finished'}display:none{/if}">({t}Cancelled{/t})</span> <span class="subtitle">{$imported_records->get('Imported Records File Name')}</span></span> 

					<span id="title_wait" style="{if !$imported_records->is_in_process()}display:none{/if}">{t}Importing{/t} <img style="height:10px;position:relative;bottom:4px" src="art/progressbar.gif"/></span> </span> 
					
				</div>
			</div>
			<div class="buttons" style="float:right">
				 <button id="cancel_import" class="negative" style="{if !$imported_records->is_in_process()}display:none{/if}" ><img src="art/icons/cross.png" alt="">{t}Cancel{/t}</button> 
				 <button id="cancelling_import" style="display:none" ><img src="art/loading.gif" alt=""> {t}Cancelling{/t}</button> 

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
		<div id="block_overview"  style="{if $block_view!='overview'}display:none{/if};clear:both;">
			<div style="width:400px">
			
			
			
			<table id="info_wait"class="show_info_product" style="margin-top:20px;{if  !$imported_records->is_in_process()}display:none{/if}">
				<tr class="title first overtitle">
					<td colspan=3 class="aright" id="waiting_msg"> <img src="art/loading.gif"/> <span id="progress"></span></td>
				</tr>
				
				
				<tr>
					<td>{t}Filename{/t}:</td>
					<td class="aright">{$imported_records->get('Imported Records File Name')} ({$imported_records->get('Filesize')})</td>
				</tr>
				
				<tr style="display:none" id="start_date_tr">
					<td>{t}Start Date{/t}:</td>
					<td class="aright"  id="start_date">{$imported_records->get('Start Date')}</td>
				</tr>
				
				<tr class="section">
					<td>{t}To do records:{/t}</td>
					<td class="aright" id="records_todo">{$imported_records->get('Todo')}</td>
					<td id="records_todo_comments"></td>
				</tr>
				<tbody style="display:none" id="info_wait_tbody">
				<tr >
					<td>{t}Done{/t}</td>
					<td  class="aright"id="records_done">0</td>
					<td id="records_imported_comments"></td>
				</tr>
				<tr>
					<td>{t}Ignored{/t}</td>
					<td  class="aright"id="records_ignored">0</td>
					<td id="records_ignored_comments"></td>
				</tr>
				<tr>
					<td>{t}Errors{/t}</td>
					<td class="aright" id="records_error">0</td>
					<td id="records_error_comments"></td>
				</tr>
				</tbody>
			</table>
			<table id="info_finished" class="show_info_product" style="margin-top:20px;{if  $imported_records->is_in_process()}display:none{/if}">
				<tr>
					<td>{t}Filename{/t}:</td>
					<td class="aright">{$imported_records->get('Imported Records File Name')} ({$imported_records->get('Filesize')})</td>
				</tr>
					<tr>
					<td>{t}List{/t}:</td>
					<td class="aright" id="finished_list_link"><a href="list.php?id={$imported_records->get('Imported Records Subject List Key')}">{$imported_records->get('Imported Records Subject List Name')}</a></td>
				</tr>
				<tbody id="dates_finished" style="{if $imported_records->get('Imported Records State')!='Finished'}display:none{/if}">
				<tr>
					<td>{t}Imported Date{/t}:</td>
					<td class="aright"  id="finished_date">{$imported_records->get('Finish Date')}</td>
				</tr>
				</tbody>
				<tbody id="dates_cancelled" style="{if $imported_records->get('Imported Records State')=='Finished'}display:none{/if}">
				<tr>
					<td>{t}Start Date{/t}:</td>
					<td class="aright"  id="start_date">{$imported_records->get('Start Date')}</td>
				</tr>
				<tr>
					<td>{t}Cancelled Date{/t}:</td>
					<td class="aright"  id="cancelled_date">{$imported_records->get('Cancelled Date')}</td>
				</tr>
				</tbody>
				
			
				<tr class="section">
					<td>{t}Imported Records{/t}:</td>
					<td class="aright" id="finished_records_done">{$imported_records->get('Imported')}</td>
				</tr>
				<tr>
					<td>{t}Ignored{/t}:</td>
					<td class="aright" id="finished_records_ignored">{$imported_records->get('Ignored')}</td>
				</tr>
				<tr>
					<td>{t}Errors{/t}</td>
					<td class="aright" id="finished_records_error">{$imported_records->get('Errors')}</td>
				</tr>
				<tr id="finished_records_cancelled_tr" style="{if $imported_records->get('Imported Records State')=='Finished'}display:none{/if}">
					<td>{t}Cancelled{/t}</td>
					<td class="aright" id="finished_records_cancelled">{$imported_records->get('Cancelled')}</td>
				</tr>				
			</table>
	</div>
	</div>
	
	<div id="block_records" class="data_table" style="{if $block_view!='records'}display:none{/if};clear:both;padding:20px 0px">
			<div>
				<span class="clean_table_title">{t}Records{/t} </span> 
				<div class="elements_chooser">
					<div id="part_use_chooser">
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Error}selected{/if} " id="elements_Error" table_type="Error">{t}Error{/t} (<span id="elements_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Ignored}selected{/if} " id="elements_Ignored" table_type="Ignored">{t}Ignored{/t} (<span id="elements_Ignored_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Waiting}selected{/if} " id="elements_Waiting" table_type="Waiting">{t}Waiting{/t} (<span id="elements_Waiting_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Importing}selected{/if} " id="elements_Importing" table_type="Importing">{t}Importing{/t} (<span id="elements_Importing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
												<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Cancelled}selected{/if} " id="elements_Cancelled" table_type="Cancelled">{t}Cancelled{/t} (<span id="elements_Cancelled_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

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