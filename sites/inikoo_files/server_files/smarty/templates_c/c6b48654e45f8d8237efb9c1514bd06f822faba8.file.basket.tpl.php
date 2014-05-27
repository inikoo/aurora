<?php /* Smarty version Smarty-3.1.5, created on 2014-05-27 08:16:43
         compiled from "templates/basket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19589652375252db06e607f2-92614754%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c6b48654e45f8d8237efb9c1514bd06f822faba8' => 
    array (
      0 => 'templates/basket.tpl',
      1 => 1401171402,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19589652375252db06e607f2-92614754',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5252db06e61f2',
  'variables' => 
  array (
    'order' => 0,
    'customer' => 0,
    'charges_deal_info' => 0,
    'filter_name0' => 0,
    'filter_value0' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5252db06e61f2')) {function content_5252db06e61f2($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
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
<div id="order_container">
	<div id="control_panel">
		<div id="addresses">
			<h1 style="padding:0 0 5px 0;font-size:140%">
				Order <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Order Public ID');?>
 
			</h1>
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
	<div style="margin-top:20px">
		<h2>
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Items<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		</h2>
		<div class="table_top_bar space">
		</div>
		<?php echo $_smarty_tpl->getSubTemplate ('table_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('table_id'=>0,'filter_name'=>$_smarty_tpl->tpl_vars['filter_name0']->value,'filter_value'=>$_smarty_tpl->tpl_vars['filter_value0']->value,'no_filter'=>1), 0);?>
 
		<div id="table0" class="data_table_container dtable btable" style="font-size:95%">
		</div>
	</div>
	<table class="items_totals" border="0" style="display:none">
		<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Shipping<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			<td class="aright total"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Shipping Net Amount');?>
 </td>
		</tr>
			<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Charges<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			<td class="aright total"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Charges Net Amount');?>
 </td>
		</tr>
		<tr>
			<td class="hidden"> </td>
			<td class="total_tr aright description"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Net<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			<td class=" total_tr aright total"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Net Amount');?>
 </td>
		</tr>
		<tr style="">
			<td class="hidden"> </td>
			<td class="aright description"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Vat<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			<td class="aright total"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Tax Amount');?>
 </td>
		</tr>
		<tr >
			<td class="hidden"> </td>
			<td class="total_tr total_balance aright description"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Total<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			<td class="total_tr total_balance aright total"> <?php echo $_smarty_tpl->tpl_vars['order']->value->get('Balance Total Amount');?>
 </td>
		</tr>
		
	</table>
	<div style="margin-top:20px">
		<span style="float:left;cursor:pointer" id="cancel_order"><img src="art/bin.png" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel order<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" alt="Cancel order" /> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Clear order<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span id="cancel_order_info" style="display:none">, <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
your order will be cancelled<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <img id="cancel_order_img" style="height:16px;position:relative;bottom:-2px" "cancel_order_img" style="height:16px" src="art/emotion_sad.png"></span></span> 
		<div class="buttons right">
			<button onclick="location.href='checkout.php'" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Go to Checkout<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
		</div>
		
	</div>
	
	
</div>
<?php }} ?>