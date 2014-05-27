<?php /* Smarty version Smarty-3.1.5, created on 2014-05-23 19:29:04
         compiled from "548adbf23ea71f248b38c403078137ce0eb0b112" */ ?>
<?php /*%%SmartyHeaderCode:1953268576537f85600e55e0-12730407%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '548adbf23ea71f248b38c403078137ce0eb0b112' => 
    array (
      0 => '548adbf23ea71f248b38c403078137ce0eb0b112',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1953268576537f85600e55e0-12730407',
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
  'unifunc' => 'content_537f856019f6f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537f856019f6f')) {function content_537f856019f6f($_smarty_tpl) {?><div id="top_bar">
                        <img style="float:left" id="top_bar_logo" src="public_image.php?id=70789" />
                        <div  style="float:right">
                        <?php echo $_smarty_tpl->tpl_vars['page']->value->display_top_bar();?>

                        </div>
                    </div>
                    <div id="header" style="position:relative;z-index:3">
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