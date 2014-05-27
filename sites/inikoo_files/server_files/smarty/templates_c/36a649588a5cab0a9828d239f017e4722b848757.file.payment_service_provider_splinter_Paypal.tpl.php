<?php /* Smarty version Smarty-3.1.5, created on 2014-05-27 10:31:31
         compiled from "templates/payment_service_provider_splinter_Paypal.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168082065253834f98e44e20-61832667%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '36a649588a5cab0a9828d239f017e4722b848757' => 
    array (
      0 => 'templates/payment_service_provider_splinter_Paypal.tpl',
      1 => 1401179488,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168082065253834f98e44e20-61832667',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53834f98e5cf4',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53834f98e5cf4')) {function content_53834f98e5cf4($_smarty_tpl) {?><form name="_xclick" action="" id='Paypal_form' method="post">
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
	<span>Your payment will be processed securely by Paypal</span> 
</div>
<?php }} ?>