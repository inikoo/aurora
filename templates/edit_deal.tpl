{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" value="{$session_data}" id="session_data" />
	<input type="hidden" id="deal_key" value="{$deal->id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
	<input type="hidden" id="label_invalid_label" value="{t}Invalid label{/t}"> 
	<input type="hidden" id="label_invalid_name" value="{t}Invalid name{/t}"> 
	<input type="hidden" id="label_invalid_date" value="{t}Invalid date{/t}"> {include file='assets_navigation.tpl'} 
		<input id="subject" type="hidden" value="deal"  />
	<input id="subject_key" type="hidden" value="{$deal->id}"  />

	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.png" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="campaign.php?id={$campaign->id}">{$campaign->get('Deal Campaign Name')}</a> &rarr; <a id="title_deal_name_bis" href="deal.php?id={$deal->id}">{$deal->get('Deal Name')}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Edit Offer{/t}: <span id="title_deal_name" class="id">{$deal->get('Deal Name')}</span> 

			<span id="suspended_tag" style="color:#ba0000;font-size:80%;font-style:italic;{if  $deal->get('Deal Status')!='Suspended'}display:none{/if}">({t}Suspended{/t})</span> 
			<span id="finished_tag" style="color:#777;font-size:80%;font-style:italic;{if  $deal->get('Deal Status')!='Finish'}display:none{/if}">({t}Finished{/t})</span> 
			
			</span> 
		</div>
		<div class="buttons small" style="position:relative;top:5px">
		
	
			<button onclick="window.location='deal.php?id={$deal->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 

			<button id="deal_status_Active" onclick="edit_status('Active')" class="positive" style="{if $deal->get('Deal Status')!='Suspended' or $deal->get('Deal Status')=='Finish'  }display:none{/if}"><img  id="deal_status_Active_wait" src="art/icons/tick.png">  {t}Activate{/t}</button>
			<button id="deal_status_Suspended" onclick="edit_status('Suspended')" class="negative" style="{if $deal->get('Deal Status')=='Suspended'  or $deal->get('Deal Status')=='Finish' }display:none{/if}"><img  id="deal_status_Suspended_wait" src="art/icons/stop.png">  {t}Suspend{/t}</button> 
			<button id="deal_delete" onclick="delete_deal()" class="negative" style="{if $deal->get('Deal Total Acc Applied Orders')>0 }display:none{/if}"><img  id="deal_delete_wait" src="art/icons/cross.png">  {t}Delete{/t}</button> 
<div id="deal_delete_msg" class="error" style="float:right;font-size:70%;margin-right:10px"></div>

		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit_block_view=='state'}selected{/if}" id="state"> <span> {t}Dates{/t}</span></span></li>
		<li> <span class="item {if $edit_block_view=='description'}selected{/if}" id="description"><span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit_block_view=='allowances'}selected{/if}" id="allowances"><span> {t}Allowances{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div id="d_state" class="edit_block" style="padding:0 20px;{if $edit_block_view!='state'}display:none{/if}">
			<table class="edit" style="margin-top:20px;width:800px" border="0">
				
				
				<tr style="{if $deal->get('Deal Status')!='Finish'}display:none{/if}">
					<td>{t}This offer is finish{/t}</td>
				</tr>
				<tbody id="deal_dates" style="{if  $deal->get('Deal Status')=='Finish'}display:none{/if}">
					<tr class="title">
						<td colspan="3">{t}Dates{/t}, <small style="font-weight:400">campaign validity: {$campaign->get('Interval')}</small></td>
					</tr>
					<tr class="space20" style="{if $deal->get('Deal Status')!='Waiting'  }display:none{/if}">
						<td class="label">{t}Start{/t}:</td>
						<td style="text-align:left;width:400px"> 
						<div class="buttons small left">
							<input id="v_calpop1" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="{$deal->get_from_date()}" ovalue="{$deal->get_from_date()}" />
							<div id="deal_from_Container" style="position:absolute;display:none; z-index:2">
							</div>
							<img id="calpop1" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="start_now">{t}Start now{/t}</button> 
						</div>
						</td>
						<td> 
						<div style="float:left;width:180px" id="deal_from_msg" class="edit_td_alert">
						</div>
						</td>
					</tr>
					<tr class="space20" style="{if $deal->get('Deal Status')=='Waiting'}display:none{/if}">
						<td class="label" style=";width:200px">{t}Started{/t}:</td>
						<td style="text-align:left;width:150px"> {$deal->get_from_date()} </td>
					</tr>
					<tr>
						<td class="label">{t}Until{/t}:</td>
						<td>
						<input id="v_calpop2" style="text-align:right;float:left;{if $deal->get_to_date()==''}display:none{/if}" class="text" type="text" size="10" maxlength="10" value="{$deal->get_to_date()}" ovalue="{$deal->get_to_date()}" />
							<div id="deal_to_Container" style="position:absolute;display:none; z-index:2">
							</div>
							<img id="calpop2" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px;{if $deal->get_to_date()==''}display:none{/if}" src="art/icons/calendar_view_month.png" align="top" alt="" /> 

<span id="permanent_tag" style="{if $deal->get_to_date()!=''}display:none{/if}">{t}Permanent{/t}</span>




						</td>
						<td style="text-align:left;width:400px"> 
						<div class="buttons small left">
														
							<button id="change_to_date" style="{if $deal->get_to_date()!=''}display:none{/if}">{t}Edit Finish Date{/t}</button> 
							<button id="to_permanent" style="{if $deal->get_to_date()==''}display:none{/if}">{t}Set as permanent{/t}</button> 
							<button id="finish_now" class="negative">{t}Finish Now{/t}</button> 
						</div>
						</td>
						<td> 
						<div style="float:left;width:180px" id="v_calpop1_msg" class="edit_td_alert">
						</div>
						<div style="float:left;width:180px" id="v_calpop2_msg" class="edit_td_alert">
						</div>
						</td>
					</tr>
					<tr class="buttons">
						<td></td>
						<td colspan="2"> 
						<div class="buttons left">
							<button id="reset_edit_deal_dates" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_deal_dates" class="positive disabled">{t}Save{/t}</button> 
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="d_description" class="edit_block" style="padding:0 20px;{if $edit_block_view!='description'}display:none{/if}">
			<table class="edit" style="margin-top:20px;width:800px" border="0">
				<tr class="title">
					<td colspan="3">{t}Deal Description{/t}</td>
				</tr>
			
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td style="text-align:left;width:400px"> 
					<div>
						<input style="text-align:left;width:370px" id="deal_name" value="{$deal->get('Deal Name')}" ovalue="{$deal->get('Deal Name')}"> 
						<div id="deal_name_Container">
						</div>
					</div>
					</td>
					<td> 
					<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
					</div>
					</td>
				</tr>
				<tr>
				<td class="label">{t}Description{/t}:</td>
				<td style="text-align:left;width:400px;height:100px"> 
				<div>
					<textarea style="text-align:left;width:370px;height:100px" id="deal_description" ovalue="{$deal->get('Deal Description')}">{$deal->get('Deal Description')}</textarea>
					<div id="deal_description_Container">
					</div>
				</div>
				</td>
				<td> 
				<div style="float:left;width:180px" id="deal_description_msg" class="edit_td_alert">
				</div>
				</td>
			</tr>
			
			<tr>
					<td class="label">{t}Terms Label{/t}:<br><span style="font-size:70%;color:777;font-style:italic">{$deal->get('Deal Terms Description')}</td>
					<td style="text-align:left;width:400px"> 
					<div>
						<input style="text-align:left;width:370px" id="deal_terms_label" value="{$deal->get('Deal XHTML Terms Description Label')}" ovalue="{$deal->get('Deal XHTML Terms Description Label')}"> 
						<div id="deal_terms_label_Container">
						</div>
					</div>
					</td>
					<td> 
					<div style="float:left;width:180px" id="deal_terms_label_msg" class="edit_td_alert">
					</div>
					</td>
				</tr>
			
			<tr class="buttons" style="{if $deal->get('Deal Status')=='Finish'}display:none{/if}">
				<td colspan="2"> 
				<div class="buttons" style="margin-right:80px">
					<button id="save_edit_deal_description" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_deal_description" class="negative disabled">{t}Reset{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div id="d_allowances" class="edit_block" style="width:890px;padding:20px 20px;{if $edit_block_view!='allowances'}display:none{/if}">
		
		<span style="position:relative;top:2.5px;left:5px;{if !$deal->get('Deal Mirror Key')}display:none{/if}">
		 {t}Mirroring{/t}: {$deal->get_mirrow_formated_link()}
		</span>
		
		<div style="{if $deal->get('Deal Mirror Key')}display:none{/if}">
		<span class="clean_table_title" style="margin-right:5px">{t}Allowances{/t}</span> 
		<div class="buttons small left"  style="{if $deal->get('Deal Status')=='Finish' }display:none{/if}">
			<button s id="new_deal_component" onclick="new_deal_component()" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
		   
		</div>
		
		<div class="elements_chooser">
													<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $deal_component_status_elements.Waiting}selected{/if} " id="deal_component_status_elements_Waiting" table_type="Waiting">{t}Waiting{/t} (<span id="deal_component_status_elements_Waiting_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $deal_component_status_elements.Suspended}selected{/if} " id="deal_component_status_elements_Suspended" table_type="Suspended">{t}Suspended{/t} (<span id="deal_component_status_elements_Suspended_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $deal_component_status_elements.Finish}selected{/if} " id="deal_component_status_elements_Finish" table_type="Finish">{t}Finished{/t} (<span id="deal_component_status_elements_Finish_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $deal_component_status_elements.Active}selected{/if} " id="deal_component_status_elements_Active" table_type="Active">{t}Active{/t} (<span id="deal_component_status_elements_Active_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

						
						</div>
		
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
		</div>
		</div>
	</div>
</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 