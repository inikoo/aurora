<?php /* Smarty version Smarty-3.1.5, created on 2014-05-22 21:17:01
         compiled from "templates/profile_products.tpl" */ ?>
<?php /*%%SmartyHeaderCode:598577361537e4d2dc04761-30840510%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '440982be2dc53d10c8de40424453205810c2b597' => 
    array (
      0 => 'templates/profile_products.tpl',
      1 => 1334398731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '598577361537e4d2dc04761-30840510',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'store' => 0,
    'site' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_537e4d2dcb866',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537e4d2dcb866')) {function content_537e4d2dcb866($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />
<input type="hidden" id="customer_key"  value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
"/>
<input type="hidden" id="label_dispatched" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Dispatched<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_description" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Description<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_subject" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Family<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />
<input type="hidden" id="label_orders" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Orders<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" />



<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'products'), 0);?>
  

<div id="dialog_orders"    class="dialog_inikoo logged"  >
<h2><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Products Ordered<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</h2>

<div style="border:1px solid #ccc;padding:20px;width:700px;float:left;margin-bottom:40px">
<?php echo $_smarty_tpl->getSubTemplate ('table_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('table_id'=>0,'filter_name'=>'','filter_value'=>'','no_filter'=>true), 0);?>

    <div  id="table0"   class="data_table_container dtable btable "> </div>

</div>


</div>



<?php }} ?>