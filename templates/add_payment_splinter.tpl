 
<div id="dialog_add_payment" style="padding:20px 20px 10px 20px">
	<div style="margin-bottom:5px">
		{t}Account{/t}: 
	</div>
	<div id="add_payment_payment_account_container" style="clear:both" class="buttons left small">
		{foreach from=$store->get_payment_accounts_data() item=payment_account } <button valid_payment_methods="{$payment_account.valid_payment_methods}" class="item" onclick="add_payment_change_account('{$payment_account.key}')" id="add_payment_payment_account_{$payment_account.key}"><img style="height:14px;width:auto;display:none" src="art/icons/psp_{$payment_account.service_provider_code}.png" /> {$payment_account.service_provider_name}</button> {/foreach} 
	</div>
	<div id="payment_methods" style="display:none">
		<div style="clear:both;margin-top:30px;margin-bottom:5px">
			{t}Payment Method{/t}: 
		</div>
		<div id="type_of_payment" class="buttons left small">
			<button class="item" tag="Credit Card" id="add_payment_payment_method_CreditCard"><img src="art/icons/creditcards.png" /> {t}Credit Card{/t}</button> <button class="item" tag="Bank Transfer" id="add_payment_payment_method_BankTransfer"><img src="art/icons/monitor_go.png" /> {t}Bank Transfer{/t}</button> <button class="item" tag="Paypal" id="add_payment_payment_method_Paypal"><img style="width:37px;height:15px" src="art/icons/paypal.png" /> PayPal</button> <button class="item" tag="Cash" id="add_payment_payment_method_Cash"><img src="art/icons/money.png" /> {t}Cash{/t}</button> <button class="item" tag="Check" id="add_payment_payment_method_Check"><img src="art/icons/cheque.png" /> {t}Cheque{/t}</button> <button class="item" tag="Other" id="add_payment_payment_method_Other">{t}Other{/t}</button> <button class="item" tag="Customer Account" id="add_payment_payment_method_CustomerAccount">{t}Customer Account{/t}</button> 
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
			<td style="text-align:right"> 
			<input onkeyup="update_add_payment_amount(this)" type="text" style="display:none;text-align:right" id="add_payment_amount" value=""> <span style="font-weight:800" id="add_payment_amount_formated"></span> </td>
			<td> 
			<div class="buttons small">
				<button id="show_other_amount_field" onclick="add_payment_show_other_amount_field()">{t}Other Amount{/t}</button> <button id="add_payment_pay_max_amount" style="display:none" onclick="add_payment_pay_max_amount()">{t}Pay All{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td>{t}Reference{/t}:</td>
			<td> 
			<input onkeyup="can_submit_payment()" id="add_payment_reference"></td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="save_add_payment_wait" style="display:none"><img src="art/loading.gif" alt="" /> {t}Processing Request{/t}</span> <button id="save_add_payment" class="positive disabled" id="save_add_payment" onclick="save_add_payment()">{t}Save{/t}</button> <button id="close_add_payment" class="negative" onclick="hide_add_payment()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_refund_payment" style="padding:20px 20px 10px 20px">
	<input type="hidden" value="" id="refund_payment_key"> 
	<input type="hidden" value="" id="refund_payment_max_amount"> 
	<input type="hidden" value="" id="refund_payment_outstanding_amount"> 

	<input type="hidden" value="" id="refund_payment_method"> 

	<table style="font-size:110%;border-top:1px solid #ccc">
		<tr>
			<td>{t}Amount{/t}:</td>
			<td style="text-align:right"> 
			
			<input onkeyup="update_refund_payment_amount(this)" type="text" style="display:none;text-align:right" id="refund_payment_amount" value=""> <span style="font-weight:800" id="refund_payment_amount_formated"></span> </td>
			<td> 
			<div class="buttons small">
				<button id="refund_payment_show_other_amount_field" onclick="refund_payment_show_other_amount_field()">{t}Other Amount{/t}</button> 
				<button id="refund_payment_pay_max_amount" style="display:none" onclick="refund_payment_pay_max_amount()">{t}Refund All{/t}</button> 
				<button id="refund_payment_pay_outstanding_amount" style="display:none" onclick="refund_payment_pay_outstanding_amount()">{t}Refund Outstanding{/t}</button> 

			</div>
			</td>
		</tr>
		
		<tr id="refund_payment_method_tr">
		<td>{t}Method{/t}:</td>
		<td colspan="2">
		
		<div class="buttons small left">
			<button onClick="change_refund_payment('online')" id="refund_payment_online">{t}Online{/t}</button>
			<button onClick="change_refund_payment('manual')" id="refund_payment_manual">{t}Manual{/t}</button>
		</div>
		
		</td>
		</tr>
		
		
		<tr id="refund_payment_reference_tr">
			<td>{t}Reference{/t}:</td>
			<td> 
			<input onkeyup="can_submit_refund()" id="refund_payment_reference"></td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="save_refund_payment_wait" style="display:none"><img src="art/loading.gif" alt="" /> {t}Processing Request{/t}</span> <button id="save_refund_payment" class="positive disabled" id="save_refund_payment" onclick="save_refund_payment()">{t}Save{/t}</button> <button id="close_refund_payment" class="negative" onclick="hide_refund_payment()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>


<div id="dialog_credit_payment" style="padding:20px 20px 10px 20px">
	<input type="hidden" value="" id="credit_payment_key"> 
	<input type="hidden" value="" id="credit_payment_max_amount"> 
	<input type="hidden" value="" id="credit_payment_outstanding_amount"> 

	<table style="font-size:110%;border-top:1px solid #ccc">
		<tr>
			<td>{t}Amount{/t}:</td>
			<td style="text-align:right"> 
			
			<input onkeyup="update_credit_payment_amount(this)" type="text" style="display:none;text-align:right" id="credit_payment_amount" value=""> <span style="font-weight:800" id="credit_payment_amount_formated"></span> </td>
			<td> 
			<div class="buttons small">
				<button id="credit_payment_show_other_amount_field" onclick="credit_payment_show_other_amount_field()">{t}Other Amount{/t}</button>
				<button id="credit_payment_pay_max_amount" style="display:none" onclick="credit_payment_pay_max_amount()">{t}Credit All{/t}</button>
			    <button id="credit_payment_pay_outstanding_amount" style="display:none" onclick="credit_payment_pay_outstanding_amount()">{t}Credit Outstanding{/t}</button> 

			</div>
			</td>
		</tr>
		
	
		
		
		<tr id="credit_payment_reference_tr">
			<td>{t}Note{/t}:</td>
			<td> 
			<input onkeyup="can_submit_credit()" id="credit_payment_reference"></td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="save_credit_payment_wait" style="display:none"><img src="art/loading.gif" alt="" /> {t}Processing Request{/t}</span> <button id="save_credit_payment" class="positive disabled" id="save_credit_payment" onclick="save_credit_payment()">{t}Save{/t}</button> <button id="close_credit_payment" class="negative" onclick="hide_credit_payment()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>


<div id="dialog_complete_payment" style="padding:20px 20px 10px 20px">

	<input type="hidden" value="" id="complete_payment_payment_key"> 



	<table style="font-size:110%;border-top:1px solid #ccc">
		
		<tr>
			<td>{t}Reference{/t}:</td>
			<td> 
			<input onkeyup="can_submit_complete_payment()" id="complete_payment_reference"></td>
		</tr>
		<tr style="height:5px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<span id="save_complete_payment_wait" style="display:none"><img src="art/loading.gif" alt="" /> {t}Processing Request{/t}</span> <button id="save_complete_payment" class="positive disabled" id="save_complete_payment" onclick="complete_payment()">{t}Save{/t}</button> <button id="close_complete_payment" class="negative" onclick="hide_complete_payment()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>