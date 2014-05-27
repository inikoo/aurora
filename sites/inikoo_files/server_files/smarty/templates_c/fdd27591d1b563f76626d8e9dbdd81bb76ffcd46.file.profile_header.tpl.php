<?php /* Smarty version Smarty-3.1.5, created on 2014-05-22 21:34:49
         compiled from "templates/profile_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:48745737352ea2abb4425a8-76033195%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fdd27591d1b563f76626d8e9dbdd81bb76ffcd46' => 
    array (
      0 => 'templates/profile_header.tpl',
      1 => 1400787288,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48745737352ea2abb4425a8-76033195',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_52ea2abb4c5f8',
  'variables' => 
  array (
    'select' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ea2abb4c5f8')) {function content_52ea2abb4c5f8($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><div class="top_page_menu" style="padding:10px 20px 5px 20px;width:920px">
	<div class="buttons" style="float:left">
		<button  <?php if ($_smarty_tpl->tpl_vars['select']->value=='address_book'){?>class="selected"<?php }?> onclick="window.location='profile.php?view=address_book'"><img src="art/icons/book_addresses.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Address Book<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
		<button style="display:xnone"  <?php if ($_smarty_tpl->tpl_vars['select']->value=='products'){?>class="selected"<?php }?> onclick="window.location='profile.php?view=products'"><img src="art/icons/bricks.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Products Ordered<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 

		<button style="display:xnone"  <?php if ($_smarty_tpl->tpl_vars['select']->value=='orders'){?>class="selected"<?php }?> onclick="window.location='profile.php?view=orders'"><img src="art/icons/table.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
My Orders<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				<button <?php if ($_smarty_tpl->tpl_vars['select']->value=='change_password'){?>class="selected"<?php }?> onclick="window.location='profile.php?view=change_password'"><img src="art/icons/key.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Change Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 

		<button <?php if ($_smarty_tpl->tpl_vars['select']->value=='contact'){?>class="selected"<?php }?> onclick="window.location='profile.php?view=contact'"><img src="art/icons/chart_pie.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
My Account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
	</div>
	<div style="clear:both">
	</div>
</div><?php }} ?>