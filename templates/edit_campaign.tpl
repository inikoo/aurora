{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" value="{$session_data}" id="session_data" />

	<input type="hidden" id="deal_key" value="{$campaign->id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
	<input type="hidden" id="campaign_key" value="{$campaign->id}" />
	
	<input type="hidden" id="campaign_status" value="{$campaign->get('Deal Campaign Status')}" />
	<input type="hidden" id="campaign_valid_to" value="{$campaign->get('Deal Campaign Valid To')}" />


	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; 
			{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} 
			<a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; 
			<a id="title_deal_code_bis" href="campaign.php?id={$campaign->id}">{$campaign->get('Deal Campaign Code')}</a> ({t}Editing{/t})</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Edit Campaign{/t}: <span id="title_deal_code" class="id">{$campaign->get('Deal Campaign Code')}</span></span> 
			</div>
			<div class="buttons">
				<button onclick="window.location='campaign.php?id={$campaign->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button class="negative" style="{if $campaign->get_number_deals()>0}display:none{/if}" id="delete_campaign"><img src="art/icons/cross.png" alt="" /> {t}Delete{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<ul class="tabs" id="chooser_ul" style="clear:both">
			<li> <span class="item {if $edit_block_view=='state'}selected{/if}" id="state"> <span> {t}State{/t}</span></span></li>
			<li> <span class="item {if $edit_block_view=='description'}selected{/if}" id="description"><span> {t}Description{/t}</span></span></li>
		</ul>
		<div class="tabbed_container no_padding">
			<div id="d_state" class="edit_block" style="padding:0 20px;{if $edit_block_view!='state'}display:none{/if}">
				<table class="edit" style="margin-top:20px;width:900px" border="0">
					<tr class="title">
						<td colspan="3">{t}Campaign State{/t} ({$campaign->get_formated_status()})</td>

					</tr>
					{*}
					<tr id="campaign_status_tr" class="space10" style="{if $campaign->get('Deal Campaign Status')=='Waiting' or $campaign->get('Deal Campaign Status')=='Finish'}display:none{/if}">
						<td class="label" style="width:150px">{t}Status{/t}:</td>
						<td> 
							<div class="buttons left small">
								<button class="{if $campaign->get('Campaign Deal Status')!='Suspended'}selected{/if}">{t}Active{/t}</button> <button class="{if $campaign->get('Deal Status')=='Suspended'}selected{/if}">{t}Suspended{/t}</button> 
							</div>
						</td>
						<td id="state_msg"></td>
					</tr>
					{*}

					<tr class="space15" style="{if $campaign->get('Deal Campaign Status')!='Waiting'}display:none{/if}">
						<td class="label">{t}Start{/t}:</td>
						<td style="text-align:left;width:400px"> 
							<div class="buttons small left">
								<input type="hidden" id="state_from" value="Waiting"> 

								<input id="v_calpop1" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="{$campaign->get_from_date()}" ovalue="{$campaign->get_from_date()}" />
								<div id="campaign_from_Container" style="position:absolute;display:none; z-index:2">
								</div>
								<img id="calpop1" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
								<button id="start_now">{t}Start now{/t}</button> 
							</div>
						</td>
						<td> <span style="display:none" id="state_from_msg"></span>
							<div style="float:left;width:180px" id="v_calpop1_msg" class="edit_td_alert">
							</div>
						</td>
					</tr>
					<tr class="space15" style="{if $campaign->get('Deal Campaign Status')=='Waiting'}display:none{/if}">
						<td class="label" style="width:200px">{t}Started{/t}:</td>
						<td style="text-align:left;width:400px"> {$campaign->get_from_date()} </td>
						<td> </td>
					</tr>
					<tr style="{if $campaign->get('Deal Campaign Status')=='Finish'}display:none{/if}">
						<td class="label">{t}Until{/t}:</td>
						<td style="text-align:left;width:200px">
						<input type="hidden" id="state_to" value="{if $campaign->get('Deal Campaign Valid To')==''}Permanent{else}Date{/if}"  ovalue="{if $campaign->get('Deal Campaign Valid To')==''}Permanent{else}Date{/if}"> 
							<div class="buttons small left">
								<input style="{if $campaign->get('Deal Campaign Valid To')==''}display:none{/if}" id="v_calpop2"  class="text" type="text" size="10" maxlength="10" value='{$campaign->get_to_date()}' ovalue="{$campaign->get_to_date()}" />
								<div id="campaign_to_Container" style="position:absolute;display:none; z-index:2">
								</div>
								<img style="{if $campaign->get('Deal Campaign Valid To')==''}display:none{/if}" id="calpop2"  src="art/icons/calendar_view_month.png" align="top" alt="" /> 
								<button id="permanent" class="{if $campaign->get('Deal Campaign Valid To')==''}selected{/if}">{t}Permanent{/t}</button> 
								<button style="{if $campaign->get('Deal Campaign Valid To')!=''}display:none{/if}" id="change_valid_to" >{t}Set date{/t}</button> 
								<button id="finish" >{t}Finish{/t}</button> 
							</div>
						</td>
						<td> <span style="display:none" id="state_to_msg"></span>
							<div style="float:left;width:180px" id="v_calpop2_msg" class="edit_td_alert">
							</div>
						</td>
					</tr>
				</tr>
				<tr class="space15" style="{if $campaign->get('Deal Campaign Status')!='Finish'}display:none{/if}">
					<td class="label" style="width:200px">{t}Finished{/t}:</td>
					<td style="text-align:left;width:400px"> {$campaign->get_to_date()} </td>
					<td> </td>
				</tr>
				<tr class="buttons" style="{if $campaign->get('Deal Campaign Status')=='Finish'}display:none{/if}">
					<td></td>
					<td colspan="2"> 
						<div class="buttons left">
							<button id="reset_edit_campaign_state" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_campaign_state" class="positive disabled">{t}Save{/t}</button> 
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div id="d_description" class="edit_block" style="padding:0 20px;{if $edit_block_view!='description'}display:none{/if}">
			<table class="edit" style="margin-top:20px;width:900px" border="0">
				<tr class="title">
					<td colspan="3">{t}Campaign Description{/t}</td>
				</tr>
				<tr class="space10">
					<td class="label" style="150px">{t}Code{/t}:</td>
					<td style="text-align:left;width:370px"> 
						<input style="text-align:left;width:370px" id="campaign_code" value="{$campaign->get('Deal Campaign Code')}" ovalue="{$campaign->get('Deal Campaign Code')}"> 
						<div id="campaign_code_Container">
						</div>
					</td>
					<td style="width:350px"> 
						<div id="campaign_code_msg" class="edit_td_alert">
						</div>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td> 
						<input style="text-align:left;width:370px" id="campaign_name" value="{$campaign->get('Deal Campaign Name')}" ovalue="{$campaign->get('Deal Campaign Name')}"> 
						<div id="campaign_name_Container">
						</div>
					</td>
					<td> 
						<div id="campaign_name_msg" class="edit_td_alert">
						</div>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Description{/t}:</td>
					<td style="height:100px"> 
						<div>
							<textarea style="text-align:left;width:370px;height:100px" id="campaign_description" ovalue="{$campaign->get('Deal Campaign Description')}">{$campaign->get('Deal Campaign Description')}</textarea> 
							<div id="campaign_description_Container">
							</div>
						</div>
					</td>
					<td> 
						<div id="campaign_description_msg" class="edit_td_alert">
						</div>
					</td>
				</tr>
				<tr class="buttons" style="{if $campaign->get('Deal Campaign Status')=='Finish'}display:none{/if}">
					<td colspan="2"> 
						<div class="buttons">
							<button id="save_edit_campaign_description" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_campaign_description" class="negative disabled">{t}Reset{/t}</button> 
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div id="dialog_delete_campaign" style="padding:20px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Delete Campaign{/t} 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div id="delete_campaign_msg"></div>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting campaign, wait please{/t} 
	</div>
	<div id="delete_campaign_buttons" class="buttons">
		<button id="save_delete_campaign" class="positive">{t}Yes, delete it!{/t}</button> <button id="cancel_delete_campaign" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
{include file='footer.tpl'} 