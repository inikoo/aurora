<?php /* Smarty version Smarty-3.1.5, created on 2014-05-26 14:10:29
         compiled from "templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:195623541953832f35f0aaf1-11869181%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9206ffe216f3f7c2e7655782292928f7d20e8be5' => 
    array (
      0 => 'templates/footer.tpl',
      1 => 1326188700,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '195623541953832f35f0aaf1-11869181',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_53832f35f25fe',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53832f35f25fe')) {function content_53832f35f25fe($_smarty_tpl) {?>
<div id="footer_contact" >
<div id="footer_address"><?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Contact Address');?>
</div>
<div id="footer_telephone"><?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Contact Telephone');?>
</div>
</div>
<?php }} ?>