<?php /* Smarty version Smarty-3.1.5, created on 2014-02-05 14:07:52
         compiled from "templates/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:656675326528614364fae44-44290753%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd882aa11801c5fcab6b6eddcbf25003b28e12815' => 
    array (
      0 => 'templates/search.tpl',
      1 => 1391605670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '656675326528614364fae44-44290753',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_52861436527e1',
  'variables' => 
  array (
    'query' => 0,
    'formated_number_results' => 0,
    'did_you_mean' => 0,
    'results' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52861436527e1')) {function content_52861436527e1($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?>
 <div class="dialog_inikoo" style="padding:20px">
<input type="hidden" id="query" value="<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
" />
	<div class="search_results">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Searching for<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="code"><?php echo $_smarty_tpl->tpl_vars['query']->value;?>
</span>. <?php echo $_smarty_tpl->tpl_vars['formated_number_results']->value;?>
 
		<?php if ($_smarty_tpl->tpl_vars['did_you_mean']->value!=''){?>
		<p style="margin-top:20px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Did you mean<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: <i><a href="search.php?q=<?php echo $_smarty_tpl->tpl_vars['did_you_mean']->value;?>
" class="code"><?php echo $_smarty_tpl->tpl_vars['did_you_mean']->value;?>
</a></i>?</p>
		<?php }?>
		
		
		<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
?> 
		<div class="result" style="margin-bottom:20px;clear:both;margin-top:30px">
			
			<div style="height:125px;width:145px;float:left;text-align:center;;margin:0px 15px 0px 5px">
			<div style="height:125px;width:145px;border:1px solid #ccc;padding:0px;vertical-align:middle;text-align:center;display: table-cell;">
			<?php if ($_smarty_tpl->tpl_vars['result']->value['image']!=''){?><a href="<?php echo $_smarty_tpl->tpl_vars['result']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['result']->value['image'];?>
" style="max-height:110px;max-width: 130px;overflow:hidden;"></a><?php }?>
			</div>
			</div>
			<div style="margin-left:140px">
				<h3 style="margin-bottom:2.5px">
					<a href="<?php echo $_smarty_tpl->tpl_vars['result']->value['url'];?>
" class="result_title"><?php echo $_smarty_tpl->tpl_vars['result']->value['title'];?>
</a>
				</h3>
				<p style="margin:0px">
					<?php echo $_smarty_tpl->tpl_vars['result']->value['description'];?>

				</p>
				<p style="margin:5px 0px">
					<?php echo $_smarty_tpl->tpl_vars['result']->value['asset_description'];?>

				</p>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<?php } ?> 
	</div>
</div>
<?php }} ?>