
		<div style="{if $first}margin-left:0px{/if}" id="payment_account_container_Paypal" class="payment_method_button glow" onclick="choose_payment_account('{$payment_service_provider_code}',{$payment_account_key})">
			<h2 style="position:relative;left:-40px;">
				<img style="margin-right:5px" src="art/paypal.png"> {t}Paypal{/t} 
			</h2>
			<div>
				<img style="position:absolute;top:70px;left:100px;width:85px;float:right;border:0px solid red" src="art/powered_by_paypal.png"> 
			</div>
		</div>
		
		<form name="_xclick" action="" id='Paypal_form' method="post">
	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="return" value="" id='Paypal_Payment_Account_Return_Link_Good'>
	<input type="hidden" name="cancel_return" value="" id='Paypal_Payment_Account_Return_Link_Bad'>
	<input type="hidden" name="lc" value="" id='Paypal_language_settings'>
	<input type="hidden" name="item_name_1" value="" id='Paypal_Description'>
	<input type="hidden" name="amount_1" value="" id='Paypal_Order_Balance_Total_Amount'>
	<input type="hidden" name="quantity_1" value="1">
	<input type="hidden" name="invoice" value="" id='Paypal_Order_Public_ID'>
	<input type="hidden" name="business" value="" id='Paypal_Payment_Account_Login'>
	<input type="hidden" name="currency_code" value="" id='Paypal_Order_Currency'>
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="custom" value="" id='Paypal_Payment_Key'>
	<input type="hidden" name="first_name" value="" id='Paypal_first_name'>
	<input type="hidden" name="last_name" value="" id='Paypal_last_name'>
	<input type="hidden" name="country" value="" id='Paypal_Customer_Billing_Address_2_Alpha_Country_Code'>
	<input type="hidden" name="address1" value="" id='Paypal_Customer_Billing_Address_Line_1'>
	<input type="hidden" name="address2" value="" id='Paypal_Customer_Billing_Address_Line_2'>
	<input type="hidden" name="city" value="" id='Paypal_Customer_Billing_Address_Town'>
	<input type="hidden" name="zip" value="" id='Paypal_Customer_Billing_Address_Postal_Code'>
	<input type="hidden" name="email" value="" id='Paypal_Customer_Main_Plain_Email'>
</form>
<div id="payment_account_info_Paypal" style="display:none">
	<h2 style="padding-bottom:10px">
		Paypal 
	</h2>
	<h3>{t}Your payment will be processed by Paypal{/t}</h3>
	<p>{t}Click confirm to be taken to Paypal{/t}.</p>
</div>

