<?php /* Smarty version Smarty-3.1.5, created on 2014-05-22 21:35:12
         compiled from "templates/profile_edit_address.tpl" */ ?>
<?php /*%%SmartyHeaderCode:639666743537e51704d0291-73545911%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'da240044a86b41fe81b8fa44921d73890d5bc028' => 
    array (
      0 => 'templates/profile_edit_address.tpl',
      1 => 1328631179,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '639666743537e51704d0291-73545911',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'store' => 0,
    'site' => 0,
    'index' => 0,
    'address_identifier' => 0,
    'page' => 0,
    'address_function' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_537e5170567cf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537e5170567cf')) {function content_537e5170567cf($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />
<input type="hidden" id="index"  value="<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
"/>
<input type="hidden" id="prefix"  value="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
"/>
<input type="hidden" id="customer_key"  value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
"/>

<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'address_book'), 0);?>
 


       
<div id="edit_address_block" >
    <div class="buttons" style="float:right">
            <button  onClick="window.location='profile.php?view=address_book'" ><img src="art/icons/door_out.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Exit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
    </div>
    <div style="clear:both"></div>

       <div id="dialog_new_billing_address" style="width:540px;margin-top:10px;padding:10px 0 0 0 ;border:1px solid #ccc;display:''">
       <table id="new_billing_address_table" border=0 style="width:500px;margin:0 auto">
       <?php echo $_smarty_tpl->getSubTemplate ('edit_address_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('close_if_reset'=>true,'address_identifier'=>$_smarty_tpl->tpl_vars['address_identifier']->value,'address_type'=>'Shop','show_tel'=>true,'show_contact'=>true,'address_function'=>$_smarty_tpl->tpl_vars['address_function']->value,'hide_type'=>true,'hide_description'=>true,'show_form'=>false,'show_components'=>true), 0);?>

     </table>
	</div>

</div>     




<?php }} ?>