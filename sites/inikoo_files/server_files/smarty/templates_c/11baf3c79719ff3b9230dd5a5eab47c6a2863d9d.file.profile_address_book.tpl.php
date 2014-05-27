<?php /* Smarty version Smarty-3.1.5, created on 2014-05-22 21:34:54
         compiled from "templates/profile_address_book.tpl" */ ?>
<?php /*%%SmartyHeaderCode:838075820537e515edb59d6-94602857%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11baf3c79719ff3b9230dd5a5eab47c6a2863d9d' => 
    array (
      0 => 'templates/profile_address_book.tpl',
      1 => 1328631179,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '838075820537e515edb59d6-94602857',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'store' => 0,
    'site' => 0,
    'page' => 0,
    'address' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_537e515f0e5b1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537e515f0e5b1')) {function content_537e515f0e5b1($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />

<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'address_book'), 0);?>
 



       
<div id="address_book_block" >




<table class="edit" border=1 style="clear:both;margin-bottom:40px;width:100%">
<tr ><td style="width:33%"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td><td style="width:33%"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Billing Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td><td style="width:33%"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Delivery Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td></tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main XHTML Address');?>
</td>
<td><?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Billing Address Key')==$_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Address Key')){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Same as Contact Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }elseif($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Billing Address Key')==$_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Delivery Address Key')){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Same as Delivery Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->display_billing_address('xhtml');?>
<?php }?></td>
<td><?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Delivery Address Key')==$_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Address Key')){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Same as Contact Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }elseif($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Billing Address Key')==$_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Delivery Address Key')){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Same as Billing Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->display_delivery_address('xhtml');?>
<?php }?></td>
</tr>
<tr><td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=contact_&index=<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Address Key');?>
'><img src="art/icons/edit.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=billing_'><img src="art/icons/add.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Add<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=billing_&index=<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Billing Address Key');?>
'><img src="art/icons/edit.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=delivery_'><img src="art/icons/add.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Add<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=delivery_&index=<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Delivery Address Key');?>
'><img src="art/icons/edit.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>

</td>
</tr>
<tr>
<td>
</td>
<td><table>

<?php  $_smarty_tpl->tpl_vars['address'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['address']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value->customer->get_billing_address_objects(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['address']->key => $_smarty_tpl->tpl_vars['address']->value){
$_smarty_tpl->tpl_vars['address']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['address']->key;
?>
<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Billing Address Key')!=$_smarty_tpl->tpl_vars['address']->value->id){?>
<tr><td><?php echo $_smarty_tpl->tpl_vars['address']->value->display('xhtml');?>
</td></tr>
<tr><td>


<div class="buttons" style="float:left">
<button onClick="change_main_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'billing',prefix:'billing_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
})""><img src="art/icons/chart_pie.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set as Main<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=billing_&index=<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
'><img src="art/icons/edit.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'billing',prefix:'billing_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
})"><img src="art/icons/cross.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


</td></tr>
<?php }?>
<?php } ?>

</table></td>





<td><table>

<?php  $_smarty_tpl->tpl_vars['address'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['address']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value->customer->get_delivery_address_objects(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['address']->key => $_smarty_tpl->tpl_vars['address']->value){
$_smarty_tpl->tpl_vars['address']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['address']->key;
?>
<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Delivery Address Key')!=$_smarty_tpl->tpl_vars['address']->value->id){?>
<tr><td><?php echo $_smarty_tpl->tpl_vars['address']->value->display('xhtml');?>
</td></tr>
<tr><td>

<div class="buttons" style="float:left">
<button onClick="change_main_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
})"><img src="art/icons/chart_pie.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set as Main<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>



<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=delivery_&index=<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
'><img src="art/icons/edit.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>



<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
})"><img src="art/icons/cross.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
</div>


</td></tr>
<?php }?>
<?php } ?>


</table></td>
</tr>
</table>



</div>     

<?php }} ?>