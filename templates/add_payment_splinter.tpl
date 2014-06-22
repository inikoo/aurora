 
<div id="dialog_add_payment" style="padding:20px 20px 10px 20px">
	<div style="margin-bottom:5px">
		{t}Account{/t}: 
	</div>
	<div id="add_payment_payment_account_container" style="clear:both" class="buttons left small">
		{foreach from=$store->get_payment_accounts_data() item=payment_account } 
		<button  valid_payment_methods="{$payment_account.valid_payment_methods}"  class="item"   onclick="add_payment_change_account('{$payment_account.key}')" id="add_payment_payment_account_{$payment_account.key}"><img style="height:14px;width:auto;display:none" src="art/icons/psp_{$payment_account.service_provider_code}.png" /> {$payment_account.service_provider_name}</button> {/foreach} 
	</div>
	
	<div id="payment_methods" style="display:none">
	<div style="clear:both;margin-top:30px;margin-bottom:5px">
		{t}Payment Method{/t}: 
	</div>
	<div id="type_of_payment" class="buttons left small">



		<button class="item" tag="Credit Card" id="add_payment_payment_method_CreditCard"><img src="art/icons/creditcards.png" /> {t}Credit Card{/t}</button> 
		<button class="item" tag="Bank Transfer" id="add_payment_payment_method_BankTransfer"><img src="art/icons/monitor_go.png" /> {t}Bank Transfer{/t}</button> 
		<button class="item" tag="Paypal" id="add_payment_payment_method_Paypal"><img style="width:37px;height:15px" src="art/icons/paypal.png" /> PayPal</button> 
		<button class="item" tag="Cash" id="add_payment_payment_method_Cash"><img src="art/icons/money.png" /> {t}Cash{/t}</button> 
		<button class="item" tag="Check" id="add_payment_payment_method_Check"><img src="art/icons/cheque.png" /> {t}Cheque{/t}</button> 
		<button class="item" tag="Other" id="add_payment_payment_method_Other">{t}Other{/t}</button> 
				<button class="item" tag="Customer Account" id="add_payment_payment_method_CustomerAccount">{t}Customer Account{/t}</button> 

	</div>
	</div>
	
	<div style="clear:both;height:10px">
	</div>
		<input type="hidden" value="" id="add_payment_payment_account_key"> 

	<input type="hidden" value="" id="add_payment_method"> 
	<input type="hidden" value="" id="add_payment_max_amount"> 
	<table style="font-size:110%;border-top:1px solid #ccc">
		<tr>
			<td>{t}Amount{/t}:</td>
			<td style="text-align:right"><span style="font-weight:800" id="add_payment_amount_formated"></span> 
			<input type="text" style="display:none;text-align:right" id="add_payment_amount" value=""></td>
			<td> 
			<div class="buttons small">
				<button id="show_other_amount_field" onclick="add_payment_show_other_amount_field()">{t}Other Amount{/t}</button> <button id="add_payment_pay_max_amount" style="display:none" onclick="dd_payment_pay_max_amount()">{t}Pay All{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td>{t}Reference{/t}:</td>
			<td> 
			<input id="add_payment_reference"></td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive disabled" id="save_add_payment" onclick="save_paid">{t}Save{/t}</button> <button class="negative" onclick="hide_add_payment()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
