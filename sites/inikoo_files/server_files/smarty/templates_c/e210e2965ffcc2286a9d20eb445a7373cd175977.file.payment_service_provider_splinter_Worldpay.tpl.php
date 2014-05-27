<?php /* Smarty version Smarty-3.1.5, created on 2014-05-27 10:37:06
         compiled from "templates/payment_service_provider_splinter_Worldpay.tpl" */ ?>
<?php /*%%SmartyHeaderCode:125927711553834f98e604b6-58143873%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e210e2965ffcc2286a9d20eb445a7373cd175977' => 
    array (
      0 => 'templates/payment_service_provider_splinter_Worldpay.tpl',
      1 => 1401179820,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '125927711553834f98e604b6-58143873',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53834f98e7b99',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53834f98e7b99')) {function content_53834f98e7b99($_smarty_tpl) {?><form name="_xclickWP" action="" method="POST" id="Worldpay_form" />
      <input type="hidden" name="instId" value="" id="Worldpay_Payment_Account_ID">
      <input type="hidden" name="cartId" value="" id="Worldpay_Payment_Account_Cart_ID">
      <input type="hidden" name="currency" value="" id="Worldpay_Order_Currency">
      <input type="hidden" name="name" value="" id="Worldpay_Customer_Main_Contact_Name">
      <input type="hidden" name="email" value="" id="Worldpay_Customer_Main_Plain_Email">
      <input type="hidden" name="MC_business" value="" id="Worldpay_Payment_Account_Business_Name">
      <input type="hidden" name="MC_customerId" value="" id="Worldpay_Customer_Key">
      <input type="hidden" name="address1" value="" id="Worldpay_Customer_Billing_Address_Line_1">
      <input type="hidden" name="address2" value="" id="Worldpay_Customer_Billing_Address_Line_2">
      <input type="hidden" name="address3" value="" id="Worldpay_Customer_Billing_Address_Line_3">
      <input type="hidden" name="town" value="" id="Worldpay_Customer_Billing_Address_Town">
      <input type="hidden" name="postcode" value="" id="Worldpay_Customer_Billing_Address_Postal_Code">
      <input type="hidden" name="country" value="" id="Worldpay_Customer_Billing_Address_2_Alpha_Country_Code">
      <input type="hidden" name="tel" value="" id="Worldpay_Customer_Main_Plain_Telephone">
      <input type="hidden" name="normalAmount" value="" id="Worldpay_Order_Balance_Total_Amount1">
      <input type="hidden" name="initialAmount" value="" id="Worldpay_Order_Balance_Total_Amount2">
      <input type="hidden" name="amount" value="" id="Worldpay_Order_Balance_Total_Amount3">
      <input type="hidden" name="desc" value="" id="Worldpay_Description">
      
      <input type="hidden" name="signature" value="" id="Worldpay_signature">
      
      <input type="hidden" name="testMode" value="100" id="Worldpay_Test_Mode">      
      <input type="hidden" name="option" value="0" id="Worldpay_option">
      <input type="hidden" name="startDelayUnit" value="4" id="Worldpay_startDelayUnit">
      <input type="hidden" name="startDelayMult" value="1" id="Worldpay_startDelayMult">
      <input type="hidden" name="intervalMult" value="1" id="Worldpay_intervalMult">
      <input type="hidden" name="intervalUnit" value="4" id="Worldpay_intervalUnit">
      
      <input type="hidden" name="MC_PaymentAccountKey" value="" id="Worldpay_Payment_Service_Provider_Key">      
      <input type="hidden" name="MC_orderId" value="" id="Worldpay_Payment_Key">  
        
 
</form>



	<div id="payment_account_info_Worldpay" style="display:none">
		
			<h2 style="padding-bottom:10px">Debid/Credit Card Payment</h2>
			<span>Your payment will be processed securely by Worldpay</span>
		
		</div>
		
		<?php }} ?>