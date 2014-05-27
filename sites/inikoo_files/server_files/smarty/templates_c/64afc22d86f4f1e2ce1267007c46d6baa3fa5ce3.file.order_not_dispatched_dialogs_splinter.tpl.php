<?php /* Smarty version Smarty-3.1.5, created on 2014-05-21 18:25:12
         compiled from "templates/order_not_dispatched_dialogs_splinter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10790117545252e16f8f6573-79351680%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64afc22d86f4f1e2ce1267007c46d6baa3fa5ce3' => 
    array (
      0 => 'templates/order_not_dispatched_dialogs_splinter.tpl',
      1 => 1390516705,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10790117545252e16f8f6573-79351680',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5252e16fc4a36',
  'variables' => 
  array (
    'order' => 0,
    'store' => 0,
    'tax_categories' => 0,
    'tax_category' => 0,
    'credit' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5252e16fc4a36')) {function content_5252e16fc4a36($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><div id="dialog_cancel" style="padding:15px 20px 5px 10px;width:200px">
	<div id="cancel_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel Order<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Reason of cancellation<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea> </td>
		</tr>
		<tr id="cancel_buttons">
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save('cancel')" id="cancel_save" class="positive disabled"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Continue<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" onclick="close_dialog('cancel')"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Go Back<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="cancel_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processig Request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
		</tr>
	</table>
</div>
<div id="dialog_edit_shipping" style="border:1px solid #ccc;text-align:left;padding:10px;">
	<div id="edit_shipping_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr id="calculated_shipping_tr">
			<td colspan="3" style="text-align:right;border-bottom:1px solid #ccc"> 
			<div class="buttons small">
				<button id="use_calculate_shipping"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Use auto value<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td style="padding-top:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set Shipping<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:10px;width:100px"> 
			<input id="shipping_amount" style="text-align:right" value="<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Shipping Net Amount');?>
" />
			</td>
			<td style="padding-top:10px;width:65px"> 
			<div class="buttons small">
				<img id="save_set_shipping_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /><button id="save_set_shipping" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_edit_items_charges" style="border:1px solid #ccc;text-align:left;padding:10px;">
	<div id="edit_items_charges_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr id="calculated_items_charges_tr">
			<td colspan="3" style="text-align:right;border-bottom:1px solid #ccc"> 
			<div class="buttons small">
				<button id="use_calculate_items_charges"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Use auto value<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td style="padding-top:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set Charges<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:10px;width:100px"> 
			<input id="items_charges_amount" style="text-align:right" value="<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Charges Net Amount');?>
" />
			</td>
			<td style="padding-top:10px;width:65px"> 
			<div class="buttons">
				<img id="save_set_items_charges_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /><button id="save_set_items_charges"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_sending_to_warehouse" style="border:1px solid #ccc;text-align:left;padding:20px;">
	<div id="sending_to_warehouse_waiting">
		<img src="art/loading.gif" alt="" /> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processing Request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	</div>
	<div id="sending_to_warehouse_msg">
	</div>
</div>
<div id="change_staff_discount" style="padding:10px 20px 0px 10px">
	<input type="hidden" id="change_discount_transaction_key" value="" />
	<input type="hidden" id="change_discount_record_key" value="" />
	<div class="bd">
		<table class="edit" border="0">
			<tr class="title">
				<td colspan="3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Product Discount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:20px; border:none; ">
				<td style="padding-right:25px "><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Discount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td style="text-align:right;padding:0"> 
				<input style="text-align:right;padding-right:2px" onkeyup="validate_discount_percentage(this)" type="text" id="change_discount_value" value="" />
				</td>
				<td style="text-align:left;padding-left:2px">%</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr id="change_staff_discount_buttons">
				<td colspan="3"> 
				<div class="buttons">
					<button id="change_discount_save" class="positive disabled"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="change_discount_cancel" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tr id="change_staff_discount_waiting" style="display:none">
				<td colspan="3" style="text-align:right;"> <img src="art/loading.gif"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processing your request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			</tr>
		</table>
	</div>
</div>
<div id="edit_delivery_address_splinter_dialog" class="edit_block" style="width:890px;padding:20px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
	<div style="display:none;text-align:right;margin-bottom:15px">
		<span onclick="close_edit_delivery_address_dialog()" class="state_details"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span> 
	</div>
	<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['order']->value->id;?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ('edit_delivery_address_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('default_country_2alpha'=>$_smarty_tpl->tpl_vars['store']->value->get('Store Home Country Code 2 Alpha'),'parent'=>'order','order_key'=>$_tmp1), 0);?>
 
	<div class="buttons">
		<button onclick="close_edit_delivery_address_dialog()" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
	</div>
</div>
<div id="dialog_add_credit" style="border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px">
	<div id="edit_shipping_msg">
	</div>
	<table class="edit" style="margin:10px;width:400px" border="0">
		<tr>
			<td class="label" style="padding-top:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Tax<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:10px;"> 
			<div class="buttons left small" id="add_credit_tax_categories_options">
				<input id="add_credit_tax_category" value="<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Tax Code');?>
" type="hidden" />
				<?php  $_smarty_tpl->tpl_vars['tax_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tax_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tax_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tax_category']->key => $_smarty_tpl->tpl_vars['tax_category']->value){
$_smarty_tpl->tpl_vars['tax_category']->_loop = true;
?> <button tax_category="<?php echo $_smarty_tpl->tpl_vars['tax_category']->value['code'];?>
" onclick="change_tax_category_add_credit(this)" class="item <?php if ($_smarty_tpl->tpl_vars['tax_category']->value['selected']){?>selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['tax_category']->value['label'];?>
</button> <?php } ?> 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:4px;width:80px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Net Amount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:4px;"> 
			<input id="add_credit_amount" style="text-align:right;width:80px" value="" />
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-bottom:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Description<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-bottom:10px;width:300px"> 
			<input id="add_credit_description" style="width:95%" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<img id="save_add_credit_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /> <button id="save_add_credit" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="close_add_credit" class="negative" onclick="close_dialog_add_credit()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_edit_credits" style="border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px">
	<div id="edit_shipping_msg">
	</div>
	<table class="edit" style="margin:10px;width:400px" border="0">
		<input id="credit_transaction_key" value="<?php echo $_smarty_tpl->tpl_vars['credit']->value['transaction_key'];?>
" type="hidden" />
		<tr class="top title">
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Credit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td>
			<div class="buttons small">
				<button id="remove_credit" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove Credit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Tax<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:10px;"> 
			<div class="buttons left small" id="edit_credit_tax_categories_options">
				<input id="edit_credit_tax_category" value="<?php echo $_smarty_tpl->tpl_vars['credit']->value['tax_code'];?>
" type="hidden" />
				<?php  $_smarty_tpl->tpl_vars['tax_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tax_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tax_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tax_category']->key => $_smarty_tpl->tpl_vars['tax_category']->value){
$_smarty_tpl->tpl_vars['tax_category']->_loop = true;
?> <button tax_category="<?php echo $_smarty_tpl->tpl_vars['tax_category']->value['code'];?>
" onclick="change_tax_category_edit_credit(this)" class="item <?php if ($_smarty_tpl->tpl_vars['tax_category']->value['code']==$_smarty_tpl->tpl_vars['credit']->value['tax_code']){?>selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['tax_category']->value['label'];?>
</button> <?php } ?> 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:4px;width:80px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Net Amount<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-top:4px;"> 
			<input id="edit_credit_amount" style="text-align:right;width:80px" value="<?php echo $_smarty_tpl->tpl_vars['credit']->value['net'];?>
" />
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-bottom:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Description<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td style="padding-bottom:10px;width:300px"> 
			<input id="edit_credit_description" style="width:95%" value="<?php echo $_smarty_tpl->tpl_vars['credit']->value['description'];?>
" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<img id="save_edit_credit_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /> <button id="save_edit_credit" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="close_edit_credit" class="negative" onclick="close_dialog_edit_credit()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"></td>
		</tr>
		<tr class="top title">
			<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Credit from previous orders<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
		</tr>
	</table>
</div>
<div id="dialog_edit_tax_category" style="border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px">
	<div class="buttons">
		<input type="hidden" id="original_tax_code" value="<?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Tax Code');?>
"> <?php  $_smarty_tpl->tpl_vars['tax_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tax_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tax_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tax_category']->key => $_smarty_tpl->tpl_vars['tax_category']->value){
$_smarty_tpl->tpl_vars['tax_category']->_loop = true;
?> <button tax_category="<?php echo $_smarty_tpl->tpl_vars['tax_category']->value['code'];?>
" onclick="change_tax_category(this)" class="item <?php if ($_smarty_tpl->tpl_vars['tax_category']->value['selected']){?>selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['tax_category']->value['label'];?>
</button> <?php } ?> 
	</div>
</div>
<?php }} ?>