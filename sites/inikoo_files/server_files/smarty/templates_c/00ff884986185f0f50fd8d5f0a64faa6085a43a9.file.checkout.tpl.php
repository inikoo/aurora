<?php /* Smarty version Smarty-3.1.5, created on 2014-05-27 10:32:14
         compiled from "templates/checkout.tpl" */ ?>
<?php /*%%SmartyHeaderCode:54239278753822b96549f73-84068313%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00ff884986185f0f50fd8d5f0a64faa6085a43a9' => 
    array (
      0 => 'templates/checkout.tpl',
      1 => 1401179515,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54239278753822b96549f73-84068313',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53822b9654ac4',
  'variables' => 
  array (
    'order' => 0,
    'customer' => 0,
    'charges_deal_info' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53822b9654ac4')) {function content_53822b9654ac4($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?> 
<input type="hidden" id="order_key" value="<?php echo $_smarty_tpl->tpl_vars['order']->value->id;?>
" />
<input type="hidden" id="label_code" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Code<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_description" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Description<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_quantity" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Quantity<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_gross" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Amount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_discount" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Discount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_to_charge" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
To Charge<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_net" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Net<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="payment_account_key" value="" />
<input type="hidden" id="payment_service_provider_code" value="" />
<div id="order_container">
	<div class="buttons right">
		<h1 style="margin:0px;padding:0;font-size:20px;float:left">
			Order <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Public ID');?>
 
		</h1>
		<button style="position:relative;bottom:3px" onclick="location.href='basket.php'"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Go Back Basket<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
	</div>
	<div style="clear:both;margin-bottom:1px">
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2>
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:-1px"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('order customer name');?>
, <?php echo $_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Contact Name');?>
, <span class="id">C<?php echo $_smarty_tpl->tpl_vars['customer']->value->get_formated_id();?>
</span> 
			</h2>
			<div class="address">
				<div style="margin-bottom:5px">
					<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Billing Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: 
				</div>
				<div class="address_box">
					<?php echo $_smarty_tpl->tpl_vars['customer']->value->get('Customer XHTML Billing Address');?>
 
				</div>
			</div>
			<div class="address" style="margin-left:15px">
				<div style="margin-bottom:5px">
					<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Shipping Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: 
				</div>
				<div class="address_box">
					<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order XHTML Ship Tos');?>
 
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="totals">
			<span style="display:none" id="ordered_products_number"></span> 
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr <?php if ($_smarty_tpl->tpl_vars['order']->value->get('Order Items Discount Amount')==0){?>style="display:none"<?php }?> id="tr_order_items_gross" > 
					<td class="aright"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Items Gross<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td width="100" class="aright" id="order_items_gross"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Items Gross Amount');?>
</td>
				</tr>
				<tr <?php if ($_smarty_tpl->tpl_vars['order']->value->get('Order Items Discount Amount')==0){?>style="display:none"<?php }?> id="tr_order_items_discounts" > 
					<td class="aright"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Discounts<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td width="100" class="aright">-<span id="order_items_discount"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Items Discount Amount');?>
</span></td>
				</tr>
				<tr>
					<td class="aright"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Items Net<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td width="100" class="aright" id="order_items_net"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Items Net Amount');?>
</td>
				</tr>
				<tr id="tr_order_credits" <?php if ($_smarty_tpl->tpl_vars['order']->value->get('Order Net Credited Amount')==0){?>style="display:none"<?php }?>> 
					<td class="aright"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Credits<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td width="100" class="aright" id="order_credits"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Net Credited Amount');?>
</td>
				</tr>
				<tr id="tr_order_items_charges">
					<td class="aright"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Charges<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td id="order_charges" width="100" class="aright"><?php echo $_smarty_tpl->tpl_vars['charges_deal_info']->value;?>
<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Charges Net Amount');?>
</td>
				</tr>
				<tr id="tr_order_shipping">
					<td class="aright"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Shipping<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td id="order_shipping" width="100" class="aright"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Shipping Net Amount');?>
</td>
				</tr>
				<tr style="border-top:1px solid #777">
					<td class="aright"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Net<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td id="order_net" width="100" class="aright"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Net Amount');?>
</td>
				</tr>
				<tr id="tr_order_tax" style="border-bottom:1px solid #777">
					<td class="aright"> <span id="tax_info"><?php echo $_smarty_tpl->tpl_vars['order']->value->get_formated_tax_info();?>
</span></td>
					<td id="order_tax" width="100" class="aright"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Tax Amount');?>
</td>
				</tr>
				<tr>
					<td class="aright"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Total<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
					<td id="order_total" width="100" class="aright" style="font-weight:800"><?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Total Amount');?>
</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="payment_chooser">
		<h2 style="margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Choose payment method<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: 
		</h2>
		<div id="payment_account_container_Worldpay" class="payment_method_button glow" style="margin-left:0px;" onclick="choose_payment_account('Worldpay',2)">
			<h2>
				<img style="margin-right:5px" src="art/credit_cards.png"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Debit/Credit Card<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
			</h2>
			<div>
				<div>
					<img style="position:relative;top:15px;width:90px;;left:-7px;float:left;border:0px solid red" src="art/credit_cards_worldpay.png"> <img style="position:relative;top:35px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_wordlpay.gif"> 
				</div>
			</div>
		</div>
		<div id="payment_account_container_Paypal" class="payment_method_button glow" onclick="choose_payment_account('Paypal',1)">
			<h2 style="position:relative;left:-40px;">
			<img style="margin-right:5px" src="art/paypal.png"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Paypal<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</h2>
		<div>
			<img style="position:relative;top:30px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_paypal.png"> 
		</div>
	</div>
	<div id="payment_account_container_Sofort" class="payment_method_button glow" onclick="choose_payment_account('Sofort',8)">
			<h2 style=" position:relative;left:-30px;">
		<img style="margin-right:5px" src="art/sprinter.png"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Online Bank Transfer<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div>
		<img style="position:relative;top:5px;left:3px;width:85px;float:right;border:0px solid red" src="art/powered_by_sofort.png"> 
	</div>
</div>
<div id="payment_account_container_Bank" class="payment_method_button glow" onclick="choose_payment_account('Bank',11)">
			<h2>
				<img style=" margin-right:5px" src="art/bank.png">
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Traditional Bank Transfer<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
</h2>
</div>
<div style="clear:both">
</div>
<div id="confirm_order" style="margin-top:30px;min-height:100px">
	<div class="buttons right">
		<button class="" id="confirm_payment"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Confirm Payment<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <img id="confirm_payment_img" style="position:relative;top:2px" src="art/icons/arrow_right.png"></button>
		<button style="display:none" class="positive" id="place_order"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Place Order<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
		<div id="info_payment_account" style="display:none">
		</div>
		<div id="payment_account_not_selected" style="display:none">
			<h2>
				<img style="margin-right:5px" src="art/choose_payment_account.png"> Please select a payment method, from the boxes above 
			</h2>
		</div>
	</div>
</div>
</div>
<div style="clear:both;">
</div>
<?php echo $_smarty_tpl->getSubTemplate ('payment_service_provider_splinter_Sofort.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
<?php echo $_smarty_tpl->getSubTemplate ('payment_service_provider_splinter_Paypal.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
<?php echo $_smarty_tpl->getSubTemplate ('payment_service_provider_splinter_Worldpay.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
<?php echo $_smarty_tpl->getSubTemplate ('payment_service_provider_splinter_Bank.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
</div>
<?php }} ?>