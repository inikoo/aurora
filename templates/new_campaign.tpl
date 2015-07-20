{include file='header.tpl'} 

<div id="bd">
	<input type="hidden" id="store_key" value="{$store->id}" />
	<input type="hidden" id="invalid_date" value="{t}Invalid date{/t}" />
	<input type="hidden" id="invalid_description" value="{t}Invalid description{/t}" />
	<input type="hidden" id="invalid_name" value="{t}Invalid name{/t}" />
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; {t}New Campaign{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}New Campaign{/t}</span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='marketing.php?store={$store->id}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table id="new_campaign" class="edit"  border="0">
		{*}
		<tr>
			<td class="label" style="width:120px">{t}Code{/t}:</td>
			<td class="input"> 
				<div>
					<input class="short" id="campaign_code" value="" ovalue=""> 
					<div id="campaign_code_Container">
					</div>
				</div>
			</td>
			<td class="messages"> 
				<div id="campaign_code_msg" class="edit_td_alert">
				</div>
			</td>
		</tr>
		{*}
		<tr>
			<td class="label"><span class="required">*</span> {t}Name{/t}:</td>
			<td class="input"> 
				<div>
					<input  id="campaign_name" value="" ovalue=""> 
					<div id="campaign_name_Container">
					</div>
				</div>
			</td>
			<td class="messages"> 
				<div id="campaign_name_msg" class="edit_td_alert">
				</div>
			</td>
		</tr>
		<tr id="campaign_description_tr">
			<td class="label">{t}Description{/t}:<br><small>{t}Internal use{/t}</small></td>
			<td class="input"> 
				<div>
					<textarea id="campaign_description" value="" ovalue=""></textarea> 
					<div id="campaign_description_Container">
					</div>
				</div>
			</td>
			<td class="messages"> 
				<div id="campaign_description_msg" class="edit_td_alert">
				</div>
			</td>
		</tr>
		<tr>
			<td class="label"><span class="required">*</span> {t}Start{/t}:</td>
			<td style="text-align:left;width:400px"> 
				<div class="buttons small left">
					<input id="v_calpop1" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="" ovalue="" />
					<div id="campaign_from_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img id="calpop1" class="calendar_pop"  src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="start_now">{t}Start now{/t}</button> 
				</div>
			</td>
			<td> 
				<div style="float:left;width:180px" id="campaign_from_msg" class="edit_td_alert">
				</div>
			</td>
		</tr>
		<tr>
			<td class="label"><span class="required">*</span> {t}Until{/t}:</td>
			<td style="text-align:left;width:400px"> 
				<div class="buttons small left">
					<input id="v_calpop2" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="" ovalue="" />
					<div id="campaign_to_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img id="calpop2" class="calendar_pop" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="to_permanent" >{t}Permanent{/t}</button> 
				</div>
			</td>
			<td> 
				<div style="float:left;width:180px" id="campaign_to_msg" class="edit_td_alert">
				</div>
			</td>
		</tr>
		<tr class="buttons">
			<td></td>
			<td style="text-align:right"> <span style="display:none" id="waiting"><img src='art/loading.gif' alt=''> {t}Processing Request{/t}</span> 
				<div id="form_buttons" class="buttons left" style="margin-left:240px;">
					<button style="" id="reset_new_campaign" onclick="window.location='{$link_back}'" class="negative">{t}Cancel{/t}</button> 
					<button style="" id="save_new_campaign" class="positive disabled">{t}Save{/t}</button> 
				</div>
			</td>
			<td></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 