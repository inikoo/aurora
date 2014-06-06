 
<div style="{if $first}margin-left:0px{/if}" id="payment_account_container_Bank" class="payment_method_button glow" onclick="choose_payment_account('{$payment_service_provider_code}',{$payment_account_key})">
	<h2>
		<img style=" margin-right:5px" src="art/bank.png"> {t}Traditional Bank Transfer{/t} 
	</h2>
</div>


<div id="payment_account_info_Bank" style="display:none">
	<h2 style="padding-bottom:10px">
		{t}Traditional Bank Transfer{/t} 
	</h2>
	<p>
		{t}Please after place your order, go to your bank, or online bank and make this payment to our bank, details below{/t}. 
	</p>
	<div style="font-size:80%;margin-top:10px">
		{$payment_account->get_formated_bank_data()} 
	</div>
	<p>
		{t}Please note, we cannot process your order until payment arrives in our account{/t}. 
	</p>
</div>
