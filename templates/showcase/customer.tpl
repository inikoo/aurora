<div id="contact_data" style="float:left">
	<div class="data_container">
		<div class="data_field  {if $customer->get('Customer Type')!='Company'}hide{/if}">
			<i class="fa fa-building-o"></i> <span>{$customer->get('Customer Name')}</span>
		</div>
		<div class="data_field {if !$customer->get('Customer Registration Number')}hide{/if}">
			<i style="visibility:hidden" class="fa fa-building-o"></i> <span d="Customer_Registration_Number">{$customer->get('Customer Registration Number')}</span>
		</div>
		<div class="data_field">
			<i class="fa fa-user"></i> <span>{$customer->get('Customer Main Contact Name')}</span>
		</div>
		<div class="data_field {if !$customer->get('Customer Tax Number')}hide{/if}">
			<i class="fa fa-black-tie"></i></i> <span>{$customer->get('Customer Tax Number')}</span>
		</div>
	</div>
	<div class="data_container">
		<div class="data_field {if !$customer->get('Customer Main Plain Email')}hide{/if}">
			<i class="fa fa-at"></i> <span>{$customer->get('Customer Main XHTML Email')}</span>
		</div>
		<div class="data_field {if !$customer->get('Customer Main XHTML Telephone')}hide{/if}">
			<i class="fa fa-phone"></i> <span>{$customer->get('Customer Main XHTML Telephone')}</span>
		</div>
		<div class="data_field {if !$customer->get('Customer Main XHTML Mobile')}hide{/if}">
			<i class="fa fa-mobile"></i> <span>{$customer->get('Customer Main XHTML Mobile')}</span>
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div class="data_container">
		<div style="min-height:80px;float:left;width:28px">
			<i class="fa fa-map-marker"></i>
		</div>
		<div style="float:left;min-width:272px">
			{$customer->get('Customer Main XHTML Address')} 
		</div>
	</div>
	<div class="data_container {if $customer->get('Sticky Note')==''}hide{/if} ">
		<div style="min-height:80px;float:left;width:28px">
			<i class="fa fa-sticky-note"></i>
		</div>
		<div style="float:left;max-width:242px;border:1px solid #ccc;padding:5px 10px">
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
<div style="margin-top:3px;max-width:370px;float:left">
	<div id="overviews" style="">
		<div id="customer_overview" style="float:left;margin-bottom:10px;">
			<table border="0" style="padding:0 5px;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
				<tr id="account_balance_tr">
					<td id="account_balance_label">{t}Account Balance{/t}</td>
					<td id="account_balance" class="aright" style="padding-right:20px;font-weight:800"><img id="edit_account_balance_button" src="/art/icons/add_bw.png" style="visibility:hidden;cursor:pointer"> {$customer->get('Account Balance')} </td>
				</tr>
				<tr id="last_credit_note_tr" style="display:none">
					<td colspan="2" class="aright" style="padding-right:20px">{t}Credit note{/t}: <span id="account_balance_last_credit_note"></span></td>
				</tr>
				<tr style="{if $customer->get_pending_payment_amount_from_account_balance()==0}display:none{/if}">
					<td id="account_balance_label">{t}Payments in Process{/t}</td>
					<td id="account_balance" class="aright" style="padding-right:20px;font-weight:800"> {$customer->get_formated_pending_payment_amount_from_account_balance()} </td>
				</tr>
			</table>
		</div>
		<div id="orders_overview" style="float:left;;margin-right:40px;width:300px">
			<table border="0" style="padding:0;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
				{if $customer->get('Customer Level Type')=='VIP'} 
				<td></td>
				<td class="id" style="font-weight:800">{t}VIP Customer{/t}</td>
				{/if} {if $customer->get('Customer Level Type')=='Partner'} 
				<td></td>
				<td class="id" style="font-weight:800">{t}Partner Customer{/t}</td>
				{/if} {if $customer->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td colspan="2">{t}Losing Customer{/t}</td>
				</tr>
				{elseif $customer->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td>{t}Lost Customer{/t}</td>
					<td>{$customer->get('Lost Date')}</td>
				</tr>
				{/if} 
				<tr>
					<td>{t}Contact Since{/t}:</td>
					<td>{$customer->get('First Contacted Date')}</td>
				</tr>
				{assign var="correlation_msg" value=$customer->get_correlation_info()} {if $correlation_msg} 
				<tr>
					<td>{$correlation_msg}</td>
				</tr>
				{/if} {if $customer->get('Customer Send Newsletter')=='No' or $customer->get('Customer Send Email Marketing')=='No' or $customer->get('Customer Send Postal Marketing')=='No'} 
				<tr>
					<td> 
					<div>
						{if $customer->get('Customer Send Newsletter')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send newsletters{/t}</span><br />
						{/if} {if $customer->get('Customer Send Email Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send marketing by email{/t}</span><br />
						{/if} {if $customer->get('Customer Send Postal Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send marketing by post{/t}</span><br />
						{/if} 
					</div>
					</td>
				</tr>
				{/if} {foreach from=$customer->get_category_data() item=item key=key} 
				<tr>
					<td>{$item.root_label}:</td>
					<td>{$item.value}</td>
				</tr>
				{/foreach} 
			</table>
		</div>
		{if $customer->get('Customer Orders')>0} 
		<div id="customer_overview" style="float:left;margin-top:10px;">
			<table style="padding:0 5px;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
				{if $customer->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span></td>
				</tr>
				{/if} {if $customer->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing customer{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td> {if $customer->get('Customer Orders')==1} {$customer->get('Customer Name')} {t}has place one order{/t}. {elseif $customer->get('Customer Orders')>1 } {$customer->get('customer name')} {if $customer->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$customer->get('Customer Orders')}</b> {if $customer->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$customer->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$customer->get('Total Net Per Order')} {t}per order{/t}). {if $customer->get('Customer Orders Invoiced')}<br />
					{if $customer->get('Customer Type by Activity')=='Lost'}{t}This customer used to place an order every{/t}{else}{t}This customer usually places an order every{/t}{/if} {$customer->get('Order Interval')}.{/if} {else} Customer has not place any order yet. {/if} </td>
				</tr>
			</table>
		</div>
		{/if} 
	</div>
</div>
<div style="clear:both">
</div>
