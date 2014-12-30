{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		{if $scope_subject=='Store'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="store_deals.php?store={$store->id}">{t}Offers{/t}</a></span> &rarr; {t}New Offer{/t}</span> {elseif $scope_subject=='Family'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')}</a> &rarr; <a id="department_branch_link" href="department.php?id={$department->id}" title="{$department->get('Product Department Name')}">{$department->get('Product Department Code')}</a> &rarr; <a href="family.php?id={$family->id}" title="{$family->get('Product Family Name')}">{$family->get('Product Family Code')}</a> ({t}New offer{/t})</span> {/if} 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}New Offer{/t}</span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='store_offers.php?store={$store->id}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table class="edit" style="margin-top:20px;width:900px" border=0>
		<tr class="title">
			<td colspan="2">Campaign</td>
		</tr>
		<tr class="space10">
			<td class="label" style="width:300px">{t}Campaign{/t}:</td>
			<td style="text-align:left;width:320px"> 
			<div class="styled-select">
				<select id="campaigns_select" onchange="campaigns_changed(this.value)">
					<option value="choose_campaign">{t}Choose campaign{/t}</option>
					<option value="">&nbsp;</option>
					<option value="new_campaign">{t}New campaign{/t}</option>
					<option value="">&nbsp;</option>
					{foreach from=$campaigns item=campaign} 
					<option id="campaign_{$campaign.id}" value="{$campaign.code}">{$campaign.name}</option>
					{/foreach} 
				</select>
			</div>
			</td>
			<td style="width:400px"> 
			<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tbody id="campaign_fields" style="display:none">
		<tr>
			<td class="label ">{t}Campaign Code{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:120px" id="deal_campaign_code" value='' ovalue=""> 
				<div id="deal_campaign_code_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_campaign_code_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr>
			<td class="label ">{t}Campaign Name{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:300px" id="deal_campaign_name" value='' ovalue=""> 
				<div id="deal_campaign_name_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_campaign_name_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
				<tr>
			<td class="label">{t}From{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input type="hidden" id="campaign_from" value='' ovalue=""> 
				<input id="v_calpop1" style="text-align:right;" class="text" type="text" size="10" maxlength="10" value="" />
				<img id="calpop1" style="cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
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
			<div>
				<input type="hidden" id="campaign_to" value='' ovalue=""> 
				<input id="v_calpop2" style="text-align:right;" class="text" type="text" size="10" maxlength="10" value="" />
				<img id="calpop2" style="cursor:pointer;text-align:right;position:relative;bottom:1px" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="campaign_to_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		
		</tbody>
		<tr class="title space20">
			<td colspan="2">{t}Terms & Allowances{/t}{if $scope_subject=='Family'} [{t}Family{/t}: {$family->get('Product Family Code')}]{/if}</td>
		</tr>
		<tr class="space10">
			<td class="label" style="width:300px">{t}Terms{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div class="styled-select">
				<select id="terms_select" onchange="terms_changed(this.value)">
					{if $scope_subject=='Family'} 
					<option value="Family Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Voucher">{t}Voucher{/t}</option>
					{else} 
					<option value="Order Total Net Amount AND Order Number">{t}Order net amount and number of orders{/t}</option>
					<option value="Order Items Net Amount AND Shipping Country">{t}Order net amount and shipping country{/t}</option>
					<option value="Order Interval">{t}Last order placed{/t}</option>
					<option value="Product Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Family Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Total Amount">{t}Total amount{/t}</option>
					<option value="Order Number">{t}Number of orders{/t}</option>
					<option value="Total Amount AND Shipping Country">{t}Total amount and shipping country{/t}</option>
					<option value="Total Amount AND Order Number">{t}Total amount and number of orders{/t}</option>
					<option value="Voucher">{t}Voucher{/t}</option>
					{/if} 
				</select>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr>
			<td class="label">{t}If order more than{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:60px" id="deal_term" value='' ovalue=""> 
				<div id="deal_term_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_term_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="width:300px">{t}Allowances{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div class="styled-select">
				<select id="allowances_select" onchange="allowances_changed(this.value)">
					{if $scope_subject=='Family'} 
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Get Same Free">{t}Get free (same product){/t}</option>
					{else} 
					<option value="Percentage Off">{t}Order more than{/t}</option>
					<option value="Get Same Free">{t}Get free (same product){/t}</option>
					{/if} 
				</select>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr>
			<td class="label">{t}Discount{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:60px" id="deal_allowance" value='' ovalue=""> 
				<div id="deal_allowance_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_term_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		{*}
		<tr class="title space20">
			<td colspan="2">Duration</td>
		</tr>
				<tr class="space10">
			<td class="label">{t}From{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input type="hidden" id="deal_from" value='' ovalue=""> 
				<input id="v_calpop3" style="text-align:right;" class="text" type="text" size="10" maxlength="10" value="" />
				<img id="calpop3" style="cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_from_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr>
			<td class="label">{t}Until{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input type="hidden" id="deal_to" value='' ovalue=""> 
				<input id="v_calpop4" style="text-align:right;" class="text" type="text" size="10" maxlength="10" value="" />
				<img id="calpop4" style="cursor:pointer;text-align:right;position:relative;bottom:1px" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_to_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		{*}
		<tr class="title space20">
			<td colspan="2">Description</td>
		</tr>
		<tr class="space10">
			<td class="label ">{t}Code{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:120px" id="deal_name" value='' ovalue=""> 
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
			<td class="label">{t}Name{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:300px" id="deal_name" value='' ovalue=""> 
				<div id="deal_name_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
			</div>
			</td>
		</tr>
		<tr class="buttons">
			<td></td>
			<td style="text-align:right"> 
			<span style="display:none" id="waiting"><img src='art/loading.gif' alt=''> {t}Processing Request{/t}</span> 
			<div id="form_buttons" class="buttons" >
							<button style="margin-right:50px" id="save_new_deal" class="positive disabled" >{t}Save{/t}</button> 

				<button style="" id="reset_new_deal" onclick="window.location='{$link_back}'" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
			<td></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 