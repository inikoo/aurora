<?php /* Smarty version Smarty-3.1.5, created on 2014-05-21 18:25:12
         compiled from "templates/table_splinter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11802728575252e12cb99a53-27683996%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be80832c97157d77a1518636b3aa7ca9591f1124' => 
    array (
      0 => 'templates/table_splinter.tpl',
      1 => 1393340805,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11802728575252e12cb99a53-27683996',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5252e12cc787f',
  'variables' => 
  array (
    'hide_caption' => 0,
    'table_id' => 0,
    'no_filter' => 0,
    'filter_show' => 0,
    'filter_value' => 0,
    'filter_name' => 0,
    'hide_paginator' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5252e12cc787f')) {function content_5252e12cc787f($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><div  class="clean_table_caption"  style="clear:both;<?php if ((isset($_smarty_tpl->tpl_vars['hide_caption']->value)&&$_smarty_tpl->tpl_vars['hide_caption']->value)){?>display:none<?php }?>">
	<div style="float:left;">
		<div id="table_info<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" class="clean_table_info"><span id="rtext<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
"></span> <span class="rtext_rpp" id="rtext_rpp<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
"></span> <span class="filter_msg"  id="filter_msg<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
"></span></div>
	</div>
	<div style="<?php if ((isset($_smarty_tpl->tpl_vars['no_filter']->value)&&$_smarty_tpl->tpl_vars['no_filter']->value)){?>display:none<?php }?>">
	<div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" <?php if ((isset($_smarty_tpl->tpl_vars['filter_show']->value)&&$_smarty_tpl->tpl_vars['filter_show']->value)||$_smarty_tpl->tpl_vars['filter_value']->value!=''){?>style="display:none"<?php }?>><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
filter results<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</div>

	<div class="clean_table_filter" id="clean_table_filter<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" <?php if (!(isset($_smarty_tpl->tpl_vars['filter_show']->value)&&$_smarty_tpl->tpl_vars['filter_show']->value)&&$_smarty_tpl->tpl_vars['filter_value']->value==''){?>style="display:none"<?php }?>>
	  <div class="clean_table_info" style="padding-bottom:1px; ">
	    <span id="filter_name<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" class="filter_name"  style="margin-right:5px"><?php echo $_smarty_tpl->tpl_vars['filter_name']->value;?>
:</span>
	    <input style="border-bottom:none;width:70px;" id='f_input<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
' value="<?php echo $_smarty_tpl->tpl_vars['filter_value']->value;?>
" /> 
	    <span class="clean_table_filter_show" id="clean_table_filter_hide<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close filter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
	    <div id='f_container<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
'></div>
	  </div>
	</div>	
	</div>
	<div class="clean_table_controls" style="margin:0 5px;<?php if (isset($_smarty_tpl->tpl_vars['hide_paginator']->value)){?>display:none<?php }?>" >
	    <div><span  style="margin:0 5px;" id="paginator<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
"></span></div>
	 </div>
</div>
<?php }} ?>