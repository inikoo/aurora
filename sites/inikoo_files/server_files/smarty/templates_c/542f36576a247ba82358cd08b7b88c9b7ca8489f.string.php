<?php /* Smarty version Smarty-3.1.5, created on 2014-02-14 15:35:08
         compiled from "542f36576a247ba82358cd08b7b88c9b7ca8489f" */ ?>
<?php /*%%SmartyHeaderCode:158264861552fe37ace21307-29823972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '542f36576a247ba82358cd08b7b88c9b7ca8489f' => 
    array (
      0 => '542f36576a247ba82358cd08b7b88c9b7ca8489f',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '158264861552fe37ace21307-29823972',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
    'see_also' => 0,
    'found_in' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_52fe37ad052c9',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52fe37ad052c9')) {function content_52fe37ad052c9($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><div id="top_bar" style="position:relative;z-index:2">
	<img style="float:left" id="top_bar_logo" src="public_image.php?id=27087" alt="" /> 
	<div style="float:right;width:790px;">
		<?php echo $_smarty_tpl->tpl_vars['page']->value->display_top_bar();?>
 
	</div>
</div>
<div id="header" style="padding:0;margin:0;position:relative;z-index:3">
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
	<?php if ($_smarty_tpl->tpl_vars['page']->value->get('Number See Also Links')){?> 
	<div id="see_also">
		<div id="see_also_label">
			<span><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
See also<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</span>
		</div>
		<?php  $_smarty_tpl->tpl_vars['see_also'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['see_also']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['page']->value->get_see_also(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['see_also']->key => $_smarty_tpl->tpl_vars['see_also']->value){
$_smarty_tpl->tpl_vars['see_also']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['see_also']->value['link'];?>
 <?php } ?> 
	</div>
	<?php }?> 
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