<?php /* Smarty version Smarty-3.1.5, created on 2014-05-26 16:28:40
         compiled from "templates/payment_service_provider_splinter_Bank.tpl" */ ?>
<?php /*%%SmartyHeaderCode:143203079653834f98e84131-42747227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0fd2598cbf64932ab27a30841c6d554ad8593b15' => 
    array (
      0 => 'templates/payment_service_provider_splinter_Bank.tpl',
      1 => 1401113126,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '143203079653834f98e84131-42747227',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'order' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53834f98eabb2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53834f98eabb2')) {function content_53834f98eabb2($_smarty_tpl) {?>		<div id="payment_account_info_Bank" style="display:none">
		
			<h2 style="padding-bottom:10px">Bank Transfer</h2>
			<div>Please pay to our bank <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Total Amount');?>
</div>
						<p style="margin-top:20px">Our Bank details are:</p>

			<div style="font-size:80%;margin-top:20px">
			Beneficiary: <b>Ancient Wisdom Marketing Limited</b><br>Bank: <b>Natwest</b><br>Address: <b>72 Middlewood Road Hillsborough Sheffield S6 4PB</b><br>Branch code: <b>NWBKGB2L</b><br>IBAN: <b>GB14 NWBK 60720835 5101 61</b> 
			
			</div>
		
		</div>
<?php }} ?>