<?php /* Smarty version Smarty-3.1.5, created on 2014-05-26 14:21:18
         compiled from "templates/payment_form_sofort.tpl" */ ?>
<?php /*%%SmartyHeaderCode:36039592053833097dae354-86276719%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '623bb4290c2422ccd3588c6a3e6873b2780da5fc' => 
    array (
      0 => 'templates/payment_form_sofort.tpl',
      1 => 1401106628,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '36039592053833097dae354-86276719',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53833097dc28e',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53833097dc28e')) {function content_53833097dc28e($_smarty_tpl) {?> <form name="sofort_payment_form" method="post" action="" id="sofort_form">
	<input name="amount" type="hidden" value="" id="sofort_Order_Balance_Total_Amount" />
	<input name="currency_id" type="hidden" value="" id="sofort_Order_Currency" />
	<input name="reason_1" type="hidden" value="" id="sofort_Order_Public_ID" />
	<input name="reason_2" type="hidden" value="" id="sofort_Order_Customer_Key" />
	<input name="sender_holder" type="hidden" value="" id="sofort_Order_Customer_Name" />
	<input name="sender_country_id" type="hidden" value="" id="sofort_Order_Billing_To_Country_2_Alpha_Code" />
	<input name="user_id" type="hidden" value="" id="sofort_Payment_Account_ID" />
	<input name="project_id" type="hidden" value="" id="sofort_Payment_Account_Provider_Login" />
	<input name="user_variable_0" type="hidden" value="" id="sofort_ourPassRep" />
	<input name="user_variable_1" type="hidden" value="" id="sofort_Payment_Account_Key" />
</form>
<?php }} ?>