<?php /* Smarty version Smarty-3.1.5, created on 2014-05-27 10:31:31
         compiled from "templates/payment_service_provider_splinter_Sofort.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185019055353834a16914e92-86957045%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '191b6199cb23826aedc23df2bd8b8aa8f2594c75' => 
    array (
      0 => 'templates/payment_service_provider_splinter_Sofort.tpl',
      1 => 1401179447,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185019055353834a16914e92-86957045',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53834a169341f',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53834a169341f')) {function content_53834a169341f($_smarty_tpl) {?> 
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
</form>
<div id="payment_account_info_Sofort" style="display:none">
	<h2 style="padding-bottom:10px">
		Online Bank Transfer 
	</h2>
	<span>Your payment will be processed securely by Sofort</span> 
	<div style="font-size:80%;margin-top:20px">
		With solfort transfer data is carried over automatically so that you only need to enter your bank's sort code along with your usual online banking login details and finally the confirmation code in order to authorise the transfer. 
	</div>
</div>
<?php }} ?>