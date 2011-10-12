<?php /* Smarty version 2.6.22, created on 2011-10-11 16:23:26
         compiled from edit_delivery_address_splinter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'edit_delivery_address_splinter.tpl', 4, false),)), $this); ?>

   
   
     <?php if ($this->_tpl_vars['return_to_order']): ?><div style="text-align:right;cursor:pointer;" onClick="back_to_take_order(<?php echo $this->_tpl_vars['return_to_order']; ?>
)" class="quick_button"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Order<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div><?php endif; ?>

     <div style="width:540px;float:right;text-align:right">
     <div style="border-bottom:1px solid #777">
       <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery Address Library<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_cancel_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_save_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       </div>
       <div style="margin-top:5px">
       <span id="add_new_delivery_address" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add New Delivery Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       </div>
       <table id="new_delivery_address_table" border=0 style="width:540px;display:none">
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'edit_address_splinter.tpl', 'smarty_include_vars' => array('close_if_reset' => true,'address_identifier' => 'delivery_','address_type' => 'Shop','show_tel' => true,'show_contact' => true,'address_function' => 'Shipping','hide_type' => true,'hide_description' => true,'show_form' => false,'show_components' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     </table>

      <table>
       <tr id="tr_address_showcase">
	    <td colspan=2 style="xborder:1px solid black"  id="delivery_address_showcase">
	      <div  style="display:none" class="address_container"  id="address_container0">
		<div class="address_display" id="address_display0"></div>
		<div  class="address_buttons" id="address_buttons0" >
		  <span class="small_button small_button_edit" style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" ><img src="art/icons/person.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contacts<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/></span>
		  
		  <input type="checkbox" class='Is_Main' /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Main<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		  <span class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(event,this)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Remove<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  <span class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Edit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		</div>
	      </div>
	      

	      <div class="address_container"  style="display:none" id="delivery_address_container0">
	      
	    <div class="delivery_address_tel_div" id="delivery_address_tel_div0" style="color:#777;font-size:90%;"><span class="delivery_address_tel_label" id="delivery_address_tel_label0" style="visibility:hidden"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Tel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: </span><span  class="delivery_address_tel" id="delivery_address_tel0"></span></div>

		<div class="address_display"  id="delivery_address_display0"></div>
		<div class="address_buttons" id="delivery_address_buttons0">
		  <span  style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contacts<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephones<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/>
		  </span>
		  <span id="delivery_set_main0" style="float:left" class="<?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Main Delivery Address Key')): ?>hide<?php endif; ?>  delivery_set_main small_button small_button_edit"  onClick="change_main_address(0,<?php echo '{'; ?>
type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $this->_tpl_vars['customer']->get('Customer Key'); ?>
<?php echo '}'; ?>
)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set as Main<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  <span  class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(0,'delivery_')" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Remove<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  <span  class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0,'delivery_')" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Edit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		</div>
	      </div>
	      
	      

	      <?php $_from = $this->_tpl_vars['customer']->get_delivery_address_objects(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['address']):
?>
	      
	      <div class="address_container"  id="delivery_address_container<?php echo $this->_tpl_vars['address']->id; ?>
">

		      <div id="delivery_address_tel_div<?php echo $this->_tpl_vars['address']->id; ?>
" style="color:#777;font-size:90%;"><span id="delivery_address_tel_label<?php echo $this->_tpl_vars['address']->id; ?>
"  style="<?php if (! $this->_tpl_vars['address']->get_principal_telecom_key('Telephone')): ?>visibility:hidden;<?php endif; ?>" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Tel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: </span><span id="delivery_address_tel<?php echo $this->_tpl_vars['address']->id; ?>
"><?php echo $this->_tpl_vars['address']->get_formated_principal_telephone(); ?>
</span></div>

	<div class="address_display"  id="delivery_address_display<?php echo $this->_tpl_vars['address']->id; ?>
"><?php echo $this->_tpl_vars['address']->display('xhtml'); ?>
</div>
		<div style="clear:both" class="address_buttons" id="delivery_address_buttons<?php echo $this->_tpl_vars['address']->id; ?>
">
		  <span  style="float:left" id="contacts_address_button<?php echo $this->_tpl_vars['address']->id; ?>
" address_id="<?php echo $this->_tpl_vars['address']->id; ?>
" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contacts<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button<?php echo $this->_tpl_vars['address']->id; ?>
" address_id="<?php echo $this->_tpl_vars['address']->id; ?>
" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephones<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/>
		  </span>
		  <span id="delivery_set_main<?php echo $this->_tpl_vars['address']->id; ?>
" style="float:left" class="<?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Main Delivery Address Key')): ?>hide<?php endif; ?>  delivery_set_main small_button small_button_edit"  onClick="change_main_address(<?php echo $this->_tpl_vars['address']->id; ?>
,<?php echo '{'; ?>
type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $this->_tpl_vars['customer']->get('Customer Key'); ?>
<?php echo '}'; ?>
)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set as Main<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Main Address Key')): ?><img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>	  <?php else: ?>
		 		  <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Billing Address Key')): ?><img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>	  <?php endif; ?>
<?php endif; ?>
		 <span <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Main Address Key') || $this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Billing Address Key')): ?>style="display:none"<?php endif; ?> class="small_button small_button_edit" id="delete_address_button<?php echo $this->_tpl_vars['address']->id; ?>
" address_id="<?php echo $this->_tpl_vars['address']->id; ?>
" onClick="delete_address(<?php echo $this->_tpl_vars['address']->id; ?>
,<?php echo '{'; ?>
type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:<?php echo $this->_tpl_vars['customer']->get('Customer Key'); ?>
<?php echo '}'; ?>
)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Remove<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  <span <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Main Address Key') || $this->_tpl_vars['key'] == $this->_tpl_vars['customer']->get('Customer Billing Address Key')): ?>style="display:none"<?php endif; ?> class="small_button small_button_edit" id="edit_address_button<?php echo $this->_tpl_vars['address']->id; ?>
" address_id="<?php echo $this->_tpl_vars['address']->id; ?>
" onclick="display_edit_delivery_address(<?php echo $this->_tpl_vars['address']->id; ?>
,'delivery_')" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Edit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
		  
		</div>
	
	      </div>
	      
	      <?php endforeach; endif; unset($_from); ?>
	    </td>
       </tr>
	  </table>
      
      </div>

     <div style="width:260px">
       <div style="border-bottom:1px solid #777">
       <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Current Delivery Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_cancel_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       <span class="state_details" style="float:right;display:none" address_key="" id="delivery_save_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       </div>
    
   <div id="delivery_current_address" style="font-size:120%;margin-top:15px">
      
 <?php echo $this->_tpl_vars['customer']->display_delivery_address('xhtml'); ?>

     </div>
 </div>
<div style="clear:both"></div>
  
   