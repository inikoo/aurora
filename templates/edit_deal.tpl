{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="deal_key" value="{$deal->id}"/>
	<input type="hidden" id="store_key" value="{$store->id}"/>
	
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="store_deals.php?store={$store->id}">{t}Offers{/t}</a></span> &rarr; <a href="deal.php?id={$deal->id}">{$deal->get('Deal Code')}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Edit Offer{/t}: <span id="title_deal_code" class="id">{$deal->get('Deal Code')}</span></span> 
		</div>
		<div class="buttons">
			<button onclick="window.location='deal.php?id={$deal->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table class="edit" style="margin-top:20px" border01>
		<tr class="title">
			<td colspan="3">{t}Deal State{/t}</td>
		</tr>
		<tr>
			<td class="label">{t}Status{/t}:</td>
			<td> 
			<div class="buttons left small">
				<button class="{if $deal->get('Deal Status')!='Suspended'}selected{/if}">{t}Active{/t}</button> <button class="{if $deal->get('Deal Status')=='Suspended'}selected{/if}">{t}Suspended{/t}</button> 
			</div>
			</td>
			<td id="state_msg"></td>
		</tr>
		<tr>
			<td class="label">{t}Start Date{/t}:</td>
			<td> 
			<input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$deal->get('Deal Begin Date')}" />
			<img style="bottom:1px;left:-17px;" id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> </td>
			<td></td>
		</tr>
		<tr>
			<td class="label">{t}End Date{/t}:</td>
			<td> 
			<div id="end_date_permanent_msg" style="{if $deal->get('Deal Expiration Date')!=''}display:none{/if}">
				<span>{t}Permanent{/t}</span> <img style="corsor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}"> 
			</div>
			<div id="end_date_input" style="{if $deal->get('Deal Expiration Date')==''}display:none{/if}">
				<input id="v_calpop2" type="text" class="text" size="11" maxlength="10" name="from" value="{$deal->get('Deal Expiration Date')}" />
				<img style="bottom:1px;left:-17px;" id="calpop2" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> 
			</div>
			</td>
			<td></td>
		</tr>
		<tr style="height:10px">
			<td colspan="3"></td>
		</tr>
		<tr class="title">
			<td colspan="3">{t}Deal Description{/t}</td>
		</tr>
		<tr>
			<td class="label">{t}Code{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:370px" id="deal_code" value="{$deal->get('Deal Code')}" ovalue="{$deal->get('Deal Code')}"> 
				<div id="deal_code_Container">
				</div>
			</div>
			</td>
			<td> 
			<div style="float:left;width:180px" id="deal_code_msg" class="edit_td_alert">
			</div>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td style="text-align:left;width:400px"> 
			<div>
				<input style="text-align:left;width:370px" id="deal_name" value="{$deal->get('Deal Name')}" ovalue="{$deal->get('Deal Name')} >
       <div id=" deal_name_container">
			</div>
		</div>
		</td>
		<td> 
		<div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert">
		</div>
	</tr>
	<td class="label">{t}Description{/t}:</td>
	<td style="text-align:left;width:400px"> 
	<div>
<textarea style="text-align:left;width:370px" id="deal_description" ovalue="{$deal->get('Deal Description')}">{$deal->get('Deal Description')}</textarea> 
		<div id="deal_description_Container">
		</div>
	</div>
	</td>
	<td> 
	<div style="float:left;width:180px" id="deal_description_msg" class="edit_td_alert">
	</div>
</tr>
<tr>
	<td colspan="2"> 
	<div class="buttons">
		<button id="save_edit_deal" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_deal" class="negative disabled">{t}Reset{/t}</button> 
	</div>
	</td>
</tr>
<tr style="height:10px">
	<td colspan="3"></td>
</tr>
<tr class="title">
	<td colspan="2">{t}Deal Terms & Allowances{/t}</td>
	<td></td>
</tr>
</table>
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
</div>
{include file='footer.tpl'} 