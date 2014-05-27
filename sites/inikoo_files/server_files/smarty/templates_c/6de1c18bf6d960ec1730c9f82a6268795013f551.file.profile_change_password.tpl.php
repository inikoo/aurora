<?php /* Smarty version Smarty-3.1.5, created on 2014-01-30 10:34:54
         compiled from "templates/profile_change_password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:73571727052ea2ace26c773-78579860%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6de1c18bf6d960ec1730c9f82a6268795013f551' => 
    array (
      0 => 'templates/profile_change_password.tpl',
      1 => 1334345109,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '73571727052ea2ace26c773-78579860',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'store' => 0,
    'site' => 0,
    'epwcp1' => 0,
    'rnd' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_52ea2ace376bf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ea2ace376bf')) {function content_52ea2ace376bf($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />

<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'change_password'), 0);?>
 

<div id="change_password_block" >



<div id="dialog_change_password"    class="dialog_inikoo logged"  >
<h2><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Change Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</h2>




<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">




<table border=0 id="change_password_form" >


<tr style="display:none;width:120px"><td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Current Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td><td><input type="password" id="current_password_password1"></td></tr>
<tr><td style="width:120px" class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
New Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td><td><input type="password" id="change_password_password1"></td></tr>
<tr><td style="width:120px" class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Confirm pwd<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td><td><input type="password" id="change_password_password2"></td></tr>
<input id="epwcp1" value="<?php echo $_smarty_tpl->tpl_vars['epwcp1']->value;?>
" type="hidden"/>
<input id="epwcp2" value="<?php echo $_smarty_tpl->tpl_vars['rnd']->value;?>
" type="hidden"/>


<tr  id="tr_change_password_buttons"  class="button space" >
<td colspan=2>

<div class="buttons" id="change_password_buttons" >
<button id="submit_change_password" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Submit Changes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
<button  id="cancel_change_password" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>
</td></tr>
<tr id="tr_change_password_wait"  style="display:none" class="button" ><td colspan=2><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Submitting changes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td></tr>




</table>
</div>
<div id="change_password_ok" class="ok_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Your password has been changed<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.
</div>
<div id="change_password_error_no_password" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Write new password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.
</div>
<div id="change_password_error_password_not_march" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Passwords don't match<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.
</div>
<div id="change_password_error_password_too_short" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Password is too short<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.
</div>
<div id="processing_change_password" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<img style="vertical-align:top" src="art/loading.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processing Request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

</div>

</div>





</div>     





<?php }} ?>