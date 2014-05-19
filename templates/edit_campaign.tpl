{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="deal_key" value="{$campaign->id}" />
	<input type="hidden" id="store_key" value="{$store->id}" />
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
		<li> <span class="item {if $edit_block_view=='terms'}selected{/if} " id="terms"><span> {t}Terms & allowances{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div id="d_state" class="edit_block" style="padding:0 20px;{if $edit_block_view!='state'}display:none{/if}">
			<table class="edit" style="margin-top:20px;width:800px" border="0">
				<tr class="title">
					<td colspan="3">{t}Campaign State{/t}</td>
				</tr>
				<tr>
					<td class="label" style="width:150px">{t}Status{/t}:</td>
					<td> 
					<div class="buttons left small">
						<button class="{if $campaign->get('Deal Status')!='Suspended'}selected{/if}">{t}Active{/t}</button> <button class="{if $campaign->get('Deal Status')=='Suspended'}selected{/if}">{t}Suspended{/t}</button> 
					</div>
					</td>
					<td id="state_msg"></td>
				</tr>
				<tr>
					<td class="label">{t}Start Date{/t}:</td>
					<td> 
					<input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$campaign->get('Deal Begin Date')}" />
					<img style="bottom:1px;left:-17px;" id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> </td>
					<td></td>
				</tr>
				<tr>
					<td class="label">{t}End Date{/t}:</td>
					<td> 
					<div id="end_date_permanent_msg" style="{if $campaign->get('Deal Expiration Date')!=''}display:none{/if}">
						<span>{t}Permanent{/t}</span> <img style="corsor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}"> 
					</div>
					<div id="end_date_input" style="{if $campaign->get('Deal Expiration Date')==''}display:none{/if}">
						<input id="v_calpop2" type="text" class="text" size="11" maxlength="10" name="from" value="{$campaign->get('Deal Expiration Date')}" />
						<img style="bottom:1px;left:-17px;" id="calpop2" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> 
					</div>
					</td>
					<td></td>
				</tr>
				
				<tr class="buttons">
				<td></td>
			<td colspan="2"> 
			<div class="buttons left">
							<button id="reset_edit_status" class="negative disabled">{t}Reset{/t}</button> 

				<button id="save_edit_status" class="positive disabled">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
				
			</table>
		</div>
		<div id="d_description" class="edit_block" style="padding:0 20px;{if $edit_block_view!='description'}display:none{/if}">
			<table class="edit" style="margin-top:20px" border="0">
				<tr class="title">
					<td colspan="3">{t}Campaign Description{/t}</td>
				</tr>
				<tr>
					<td class="label">{t}Code{/t}:</td>
					<td style="text-align:left;width:400px"> 
					<div>
						<input style="text-align:left;width:370px" id="deal_code" value="{$campaign->get('Deal Code')}" ovalue="{$campaign->get('Deal Code')}"> 
						<div id="deal_code_Container">
						</div>
					</div>
					</td>
					<td> 
					<div style="float:left;width:180px" id="deal_code_msg" class="edit_td_alert">
					</div>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td style="text-align:left;width:400px"> 
					<div>
						<input style="text-align:left;width:370px" id="deal_name" value="{$campaign->get('Deal Name')}" ovalue="{$campaign->get('Deal Name')} >
       <div id=" deal_name_container"> 
					</div>
			
				</td>
				<td> 
				<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
				</div>
				</td>
			</tr>
			<td class="label">{t}Description{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
<textarea style="text-align:left;width:370px" id="deal_description" ovalue="{$campaign->get('Deal Description')}">{$campaign->get('Deal Description')}</textarea> 
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
			<td colspan="2"> 
			<div class="buttons">
				<button id="save_edit_deal" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_deal" class="negative disabled">{t}Reset{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="d_terms" class="edit_block" style="padding:0 20px;{if $edit_block_view!='terms'}display:none{/if}">
	<table class="edit" style="margin-top:20px" border="0">
		<tr class="title">
			<td colspan="2">{t}Campaign Terms & Allowances{/t}</td>
			<td></td>
		</tr>
	</table>
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