<?php /* Smarty version Smarty-3.1.5, created on 2014-01-30 10:34:44
         compiled from "templates/profile_orders.tpl" */ ?>
<?php /*%%SmartyHeaderCode:198391162052ea2ac4378857-60022735%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86ba98c4cf946152e137056401ea9747b6744c01' => 
    array (
      0 => 'templates/profile_orders.tpl',
      1 => 1334345398,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '198391162052ea2ac4378857-60022735',
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
  'unifunc' => 'content_52ea2ac43ff3c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ea2ac43ff3c')) {function content_52ea2ac43ff3c($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />
<input type="hidden" id="customer_key"  value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
"/>

<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'orders'), 0);?>
  

<div id="dialog_orders"    class="dialog_inikoo logged"  >
<h2><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Orders<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</h2>

<div style="border:1px solid #ccc;padding:20px;width:700px;float:left">
<?php echo $_smarty_tpl->getSubTemplate ('table_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('table_id'=>0,'filter_name'=>'','filter_value'=>'','no_filter'=>true), 0);?>

    <div  id="table0"   class="data_table_container dtable btable "> </div>

</div>


</div>



<?php }} ?>