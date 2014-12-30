{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="deal_key" value="{$campaign->id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
	<input type="hidden" id="campaign_key" value="{$campaign->id}" />
	<input type="hidden" id="invalid_campaign_name" value="{t}Invalid name{/t}">
	<input type="hidden" id="invalid_campaign_description" value="{t}Invalid description{/t}">
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="store_deals.php?store={$store->id}">{t}Offers{/t}</a></span> &rarr; <a href="campaign.php?id={$campaign->id}">{$campaign->get('Deal Campaign Code')}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Edit Campaign{/t}: <span id="title_deal_code" class="id">{$campaign->get('Deal Code')}</span></span> 
		</div>
		<div class="buttons">
			<button onclick="window.location='deal.php?id={$campaign->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
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
			<table class="edit" style="margin-top:20px;width:900px" border="1">
				<tr class="title">
					<td colspan="3">{t}Campaign State{/t}</td>
				</tr>
				<tr id="campaign_status_tr" class="space10" style="{if $campaign->get('Deal Campaign Status')=='Waiting'}display:none{/if}">
					<td class="label" style="width:150px">{t}Status{/t}:</td>
					<td> 
					<div class="buttons left small">
						<button class="{if $campaign->get('Campaign Deal Status')!='Suspended'}selected{/if}">{t}Active{/t}</button> <button class="{if $campaign->get('Deal Status')=='Suspended'}selected{/if}">{t}Suspended{/t}</button> 
					</div>
					</td>
					<td id="state_msg"></td>
				</tr>
						<tr class="space15">
			<td class="label">{t}Start{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div class="buttons small left">
				<input id="v_calpop1" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="{$campaign->get_from_date()}" ovalue="{$campaign->get_from_date()}" />
				<div id="campaign_from_Container" style="position:absolute;display:none; z-index:2">
				</div>
				<img id="calpop1" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="start_now">{t}Start now{/t}</button> 
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="campaign_from_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
			
					<tr>
			<td class="label">{t}Until{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div class="buttons small left">
				<input id="v_calpop2" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value='{$campaign->get_to_date()}' ovalue="{$campaign->get_to_date()}" />
				<div id="campaign_to_Container" style="position:absolute;display:none; z-index:2">
				</div>
				<img id="calpop2" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="to_permanent" >{t}Permanent{/t}</button> 
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="campaign_to_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
			
				<tr class="buttons">
					<td></td>
					<td colspan="2"> 
					<div class="buttons left">
						<button id="reset_edit_status" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_status" class="positive disabled">{t}Save{/t}</button> 
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
					<div  id="campaign_code_msg" class="edit_td_alert">
					</div>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td > 
					<input style="text-align:left;width:370px" id="campaign_name" value="{$campaign->get('Deal Campaign Name')}" ovalue="{$campaign->get('Deal Campaign Name')}"> 
					<div id="campaign_name_Container">
					</div>
					</td>
					<td> 
					<div  id="campaign_name_msg" class="edit_td_alert">
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
					<div  id="campaign_description_msg" class="edit_td_alert">
					</div>
					</td>
				</tr>
				<tr class="buttons">
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

{include file='footer.tpl'} 