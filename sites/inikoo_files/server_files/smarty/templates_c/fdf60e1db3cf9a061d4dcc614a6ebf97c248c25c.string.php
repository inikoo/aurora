<?php /* Smarty version Smarty-3.1.5, created on 2014-04-01 17:12:50
         compiled from "fdf60e1db3cf9a061d4dcc614a6ebf97c248c25c" */ ?>
<?php /*%%SmartyHeaderCode:1956766585533ad772d684a7-07780346%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fdf60e1db3cf9a061d4dcc614a6ebf97c248c25c' => 
    array (
      0 => 'fdf60e1db3cf9a061d4dcc614a6ebf97c248c25c',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1956766585533ad772d684a7-07780346',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
    'found_in' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_533ad772e87d6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_533ad772e87d6')) {function content_533ad772e87d6($_smarty_tpl) {?><div id="top_bar" >
	<img style="float:left;height:22px" id="top_bar_logo" src="public_image.php?id=27087" alt="" /> 
	<div style="float:right;width:790px;">
		<?php echo $_smarty_tpl->tpl_vars['page']->value->display_top_bar();?>
 
	</div>
</div>
<div id="header" style="position:relative;z-index:3">
<a href="index.php"><img style="display:none;position:absolute;left:0" src="public_image.php?id=39991" alt="home"/></a>
	<div id="search">
		<?php echo $_smarty_tpl->tpl_vars['page']->value->display_search();?>
 
	</div>
	<h1 id="header_title">
		<?php echo $_smarty_tpl->tpl_vars['page']->value->display_title();?>

	</h1>
	<div id="menu_bar" >
		<?php echo $_smarty_tpl->tpl_vars['page']->value->display_menu();?>

	</div>
</div>
<div id="bottom_bar" style="position:relative;z-index:2;<?php if ($_smarty_tpl->tpl_vars['page']->value->get('Number See Also Links')==0&&$_smarty_tpl->tpl_vars['page']->value->get('Number Found In Links')==0){?>display:none<?php }?>" >
	
	<?php if ($_smarty_tpl->tpl_vars['page']->value->get('Number Found In Links')){?> 
	<div id="branch">
		<div id="parent_branch">
			<table>
				<?php  $_smarty_tpl->tpl_vars['found_in'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['found_in']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['page']->value->get_found_in(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['found_in']->key => $_smarty_tpl->tpl_vars['found_in']->value){
$_smarty_tpl->tpl_vars['found_in']->_loop = true;
?> 
				<tr>
					<td> <?php echo $_smarty_tpl->tpl_vars['found_in']->value['link'];?>
 </td>
				</tr>
				<?php } ?> 
			</table>
		</div>

	
	</div>
	<?php }?>
	<div style="clear:both">
	</div>
</div><?php }} ?>