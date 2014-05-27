<?php /* Smarty version Smarty-3.1.5, created on 2014-04-01 19:44:49
         compiled from "templates/customer_badges.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1037046940533afb11d6e6c9-18041814%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '56f55ac40ffd0bdb18ab18879ae1e53e8e8056ea' => 
    array (
      0 => 'templates/customer_badges.tpl',
      1 => 1334345008,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1037046940533afb11d6e6c9-18041814',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'customer' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_533afb11e0378',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_533afb11e0378')) {function content_533afb11e0378($_smarty_tpl) {?><div id="badge_1" onClick="show_badge_info(this, 'dialog_badge_info_1')" style="float:left;;margin-left:20px;width:70px" title="Gold Reward. Place an order within 30 days of last order to keep this status" ><?php echo $_smarty_tpl->tpl_vars['customer']->value->display_badge(1);?>
</div>
<div id="badge_2" onClick="show_badge_info(this, 'dialog_badge_info_2')" style="float:left;;margin-left:20px;width:70px" title="Freedom Figther. Buy any product from Freeinc range and help the Freedom Fund"><?php echo $_smarty_tpl->tpl_vars['customer']->value->display_badge(2);?>
</div>
<div id="badge_3" onClick="show_badge_info(this, 'dialog_badge_info_3')" style="float:left;;margin-left:20px;width:70px" title="Let us know more about you"><?php echo $_smarty_tpl->tpl_vars['customer']->value->display_badge(3);?>
</div>
<div id="badge_4" onClick="show_badge_info(this, 'dialog_badge_info_4')" style="float:left;;margin-left:20px;width:70px" title="Receive newsletters & updates"><?php echo $_smarty_tpl->tpl_vars['customer']->value->display_badge(4);?>
</div>
<div id="badge_5" onClick="show_badge_info(this, 'dialog_badge_info_5')" style="float:left;;margin-left:20px;width:70px" title="Ten orders or more will light this badge"><?php echo $_smarty_tpl->tpl_vars['customer']->value->display_badge(5);?>
</div>
<?php }} ?>