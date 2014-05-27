<?php /* Smarty version Smarty-3.1.5, created on 2013-10-07 18:29:35
         compiled from "templates/edit_delivery_address_splinter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18470182665252e16fc588f8-51437404%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e2d87b74346d52804ff3a4760f525e9f74841bf' => 
    array (
      0 => 'templates/edit_delivery_address_splinter.tpl',
      1 => 1326188700,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18470182665252e16fc588f8-51437404',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'return_to_order' => 0,
    'key' => 0,
    'customer' => 0,
    'address' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5252e17000926',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5252e17000926')) {function content_5252e17000926($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?>
   
   
     <?php if ($_smarty_tpl->tpl_vars['return_to_order']->value){?><div style="text-align:right;cursor:pointer;" onClick="back_to_take_order({ $return_to_order})" class="quick_button"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Order<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</div><?php }?>

     <div style="width:540px;float:right;text-align:right">
     <div style="border-bottom:1px solid #777">
       <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Delivery Address Library<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_cancel_edit_address"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_save_edit_address"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
       </div>
       <div style="margin-top:5px">
       <span id="add_new_delivery_address" class="state_details"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Add New Delivery Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
       </div>
       <table id="new_delivery_address_table" border=0 style="width:540px;display:none">
       <?php echo $_smarty_tpl->getSubTemplate ('edit_address_splinter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('close_if_reset'=>true,'address_identifier'=>'delivery_','address_type'=>'Shop','show_tel'=>true,'show_contact'=>true,'address_function'=>'Shipping','hide_type'=>true,'hide_description'=>true,'show_form'=>false,'show_components'=>false), 0);?>

     </table>

      <table>
       <tr id="tr_address_showcase">
	    <td colspan=2 style="xborder:1px solid black"  id="delivery_address_showcase">
	      <div  style="display:none" class="address_container"  id="address_container0">
		<div class="address_display" id="address_display0"></div>
		<div  class="address_buttons" id="address_buttons0" >
		  <span class="small_button small_button_edit" style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" ><img src="art/icons/person.png" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contacts<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"/></span>
		  
		  <input type="checkbox" class='Is_Main' /> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Main<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		  <span class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(event,this)" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  <span class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0)" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		</div>
	      </div>
	      

	      <div class="address_container"  style="display:none" id="delivery_address_container0">
	      
	    <div class="delivery_address_tel_div" id="delivery_address_tel_div0" style="color:#777;font-size:90%;"><span class="delivery_address_tel_label" id="delivery_address_tel_label0" style="visibility:hidden"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Tel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </span><span  class="delivery_address_tel" id="delivery_address_tel0"></span></div>

		<div class="address_display"  id="delivery_address_display0"></div>
		<div class="address_buttons" id="delivery_address_buttons0">
		  <span  style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contacts<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephones<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"/>
		  </span>
		  <span id="delivery_set_main0" style="float:left" class="<?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Delivery Address Key')){?>hide<?php }?>  delivery_set_main small_button small_button_edit"  onClick="change_main_address(0,{type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['customer']->value->get('Customer Key');?>
})" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set as Main<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  <span  class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(0,'delivery_')" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  <span  class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0,'delivery_')" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		</div>
	      </div>
	      
	      

	      <?php  $_smarty_tpl->tpl_vars['address'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['address']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['customer']->value->get_delivery_address_objects(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['address']->key => $_smarty_tpl->tpl_vars['address']->value){
$_smarty_tpl->tpl_vars['address']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['address']->key;
?>
	      
	      <div class="address_container"  id="delivery_address_container<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
">

		      <div id="delivery_address_tel_div<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" style="color:#777;font-size:90%;"><span id="delivery_address_tel_label<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
"  style="<?php if (!$_smarty_tpl->tpl_vars['address']->value->get_principal_telecom_key('Telephone')){?>visibility:hidden;<?php }?>" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Tel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </span><span id="delivery_address_tel<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['address']->value->get_formated_principal_telephone();?>
</span></div>

	<div class="address_display"  id="delivery_address_display<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['address']->value->display('xhtml');?>
</div>
		<div style="clear:both" class="address_buttons" id="delivery_address_buttons<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
">
		  <span  style="float:left" id="contacts_address_button<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" address_id="<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contacts<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" address_id="<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephones<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"/>
		  </span>
		  <span id="delivery_set_main<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" style="float:left" class="<?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Delivery Address Key')){?>hide<?php }?>  delivery_set_main small_button small_button_edit"  onClick="change_main_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['customer']->value->get('Customer Key');?>
})" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Set as Main<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Address Key')){?><img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>	  <?php }else{ ?>
		 		  <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Billing Address Key')){?><img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Billing<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>	  <?php }?>
<?php }?>
		 <span <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Address Key')||$_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Billing Address Key')){?>style="display:none"<?php }?> class="small_button small_button_edit" id="delete_address_button<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" address_id="<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" onClick="delete_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,{type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $_smarty_tpl->tpl_vars['customer']->value->get('Customer Key');?>
})" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remove<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  <span <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Main Address Key')||$_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['customer']->value->get('Customer Billing Address Key')){?>style="display:none"<?php }?> class="small_button small_button_edit" id="edit_address_button<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" address_id="<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
" onclick="display_edit_delivery_address(<?php echo $_smarty_tpl->tpl_vars['address']->value->id;?>
,'delivery_')" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
		  
		</div>
	
	      </div>
	      
	      <?php } ?>
	    </td>
       </tr>
	  </table>
      
      </div>

     <div style="width:260px">
       <div style="border-bottom:1px solid #777">
       <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Current Delivery Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_cancel_edit_address"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_save_edit_address"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
       </div>
    
   <div id="delivery_current_address" style="font-size:120%;margin-top:15px">
      
 <?php echo $_smarty_tpl->tpl_vars['customer']->value->display_delivery_address('xhtml');?>

     </div>
 </div>
<div style="clear:both"></div>
  
   
<?php }} ?>