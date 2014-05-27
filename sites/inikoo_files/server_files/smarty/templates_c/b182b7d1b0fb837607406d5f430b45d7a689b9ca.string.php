<?php /* Smarty version Smarty-3.1.5, created on 2014-04-01 18:39:43
         compiled from "b182b7d1b0fb837607406d5f430b45d7a689b9ca" */ ?>
<?php /*%%SmartyHeaderCode:166030313533af9df273fb9-93023594%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b182b7d1b0fb837607406d5f430b45d7a689b9ca' => 
    array (
      0 => 'b182b7d1b0fb837607406d5f430b45d7a689b9ca',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '166030313533af9df273fb9-93023594',
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
  'unifunc' => 'content_533af9df3b69b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_533af9df3b69b')) {function content_533af9df3b69b($_smarty_tpl) {?><div id="top_bar">
                        <img style="float:left" id="top_bar_logo" src="public_image.php?id=70789" />
                        <div  style="float:right">
                        <?php echo $_smarty_tpl->tpl_vars['page']->value->display_top_bar();?>

                        </div>
                    </div>
                    <div id="header" style="padding:0;margin:0;position:relative;z-index:3">
                    <div style="cursor:pointer;width:130px;height:60px" onclick="window.location='index.php'"></div>
                    
                        <div id="search">
                            <?php echo $_smarty_tpl->tpl_vars['page']->value->display_search();?>

                        </div>
                        <h1 id="header_title" ><?php echo $_smarty_tpl->tpl_vars['page']->value->display_title();?>
</h1>
                        <div id="menu_bar"><?php echo $_smarty_tpl->tpl_vars['page']->value->display_menu();?>
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