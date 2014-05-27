<?php /* Smarty version Smarty-3.1.5, created on 2014-05-23 19:12:03
         compiled from "templates/empty_basket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:120281730537f7ff2bf7003-82863208%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '78dd7610517dcb98affb6e5c951be0498f9e067e' => 
    array (
      0 => 'templates/empty_basket.tpl',
      1 => 1400865122,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '120281730537f7ff2bf7003-82863208',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_537f7ff2c2d55',
  'variables' => 
  array (
    'cancelled' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537f7ff2c2d55')) {function content_537f7ff2c2d55($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><div id="order_container" >
	<div id="control_panel" style="height:300px">
		<?php if ($_smarty_tpl->tpl_vars['cancelled']->value){?>
		<h1><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Your order has been cancelled<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</h1>
		<?php }else{ ?>
		<h1><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Your basket is empty<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</h1>
		<?php }?>
	</div>
</div>	
	<?php }} ?>