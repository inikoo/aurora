		<div style="{if $first}margin-left:0px{/if}" id="payment_account_container_Sofort" class="payment_method_button glow" onclick="choose_payment_account('{$payment_service_provider_code}',{$payment_account_key})">
			<h2 style=" position:relative;left:-18px;">
				<img style="margin-right:5px" src="art/sprinter.png"> {t}Online Bank Transfer{/t} 
			</h2>
			<div>
				<img style="position:absolute;top:65px;left:100px;width:85px;float:right;border:0px solid red" src="art/powered_by_sofort.png"> 
			</div>
		</div>

<form name="Sofort_payment_form" method="post" action="" id="Sofort_form">
	<input name="amount" type="hidden" value="" id="Sofort_Order_Balance_Total_Amount" />
	<input name="currency_id" type="hidden" value="" id="Sofort_Order_Currency" />
	<input name="reason_1" type="hidden" value="" id="Sofort_Description" />
	<input name="reason_2" type="hidden" value="" id="Sofort_Description2" />
	<input name="sender_holder" type="hidden" value="" id="Sofort_Order_Customer_Name" />
	<input name="sender_country_id" type="hidden" value="" id="Sofort_Order_Billing_To_Country_2_Alpha_Code" />
	<input name="user_id" type="hidden" value="" id="Sofort_Payment_Account_ID" />
	<input name="project_id" type="hidden" value="" id="Sofort_Payment_Account_Login" />
	<input name="user_variable_0" type="hidden" value="" id="Sofort_Payment_Random_String" />
	<input name="user_variable_1" type="hidden" value="" id="Sofort_Payment_Key" />
	<input name="user_variable_2" type="hidden" value="" id="Sofort_Order_Key" />
</form>
<div id="payment_account_info_Sofort" style="display:none">
	<h2 style="padding-bottom:10px">
		{t}Online Bank Transfer{/t} 
	</h2>
	<h3>
		{t}Your payment will be processed securely by Sofort{/t}
	</h3>
	<p>
		{t}The new cool way to pay securely online. Simply enter the same bank login details (you use on your bank website) to complete the payment within the Sofort system{/t}
	</p>
	<p>
		{t}We can process your order immediately with this method. Click confirm to go to Sofort.{/t}
	</p>
</div>