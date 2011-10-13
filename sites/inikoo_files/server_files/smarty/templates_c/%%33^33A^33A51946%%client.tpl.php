<?php /* Smarty version 2.6.22, created on 2011-10-12 10:05:28
         compiled from client.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'client.tpl', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="bd" >
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'contacts_navigation.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<input type="hidden" value="<?php echo $this->_tpl_vars['customer']->id; ?>
" id="customer_key"/>
<input type="hidden" value="<?php echo $this->_tpl_vars['registered_email']; ?>
" id="registered_email"/>

 <div id="no_details_title"  style="clear:left;xmargin:0 20px;<?php if ($this->_tpl_vars['details'] != 0): ?>display:none<?php endif; ?>">
    <h1><span style="color:SteelBlue"><?php echo $this->_tpl_vars['id']; ?>
</span>, <span id="title_name"><?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
</span></h1>
  </div>

<div style="padding:10px;background-color:#FAF8CC;width:300px;<?php if ($this->_tpl_vars['recent_merges'] == ''): ?>display:none<?php endif; ?>"><?php echo $this->_tpl_vars['recent_merges']; ?>
</div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item <?php if ($this->_tpl_vars['edit'] == 'details'): ?>selected<?php endif; ?>"  id="details">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customer Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
 <?php if ($this->_tpl_vars['customer_type'] == 'Company'): ?>
    <li> <span class="item <?php if ($this->_tpl_vars['edit'] == 'company'): ?>selected<?php endif; ?>" style="display:none"  id="company">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
 <?php endif; ?>
 <li> <span class="item <?php if ($this->_tpl_vars['edit'] == 'delivery'): ?>selected<?php endif; ?>"  id="delivery">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery Options<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
    <li> <span class="item <?php if ($this->_tpl_vars['edit'] == 'categories'): ?>selected<?php endif; ?>"  id="categories">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Categories<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
    <li> <span class="item <?php if ($this->_tpl_vars['edit'] == 'communications'): ?>selected<?php endif; ?>"  id="communications">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Communications<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
    <li style="display:none"> <span class="item <?php if ($this->_tpl_vars['edit'] == 'merge'): ?>selected<?php endif; ?>"  id="merge">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Merge<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
	<?php if ($this->_tpl_vars['site_customer']): ?>
	<li style="display:none"> <span class="item <?php if ($this->_tpl_vars['edit'] == 'password'): ?>selected<?php endif; ?>"  id="password" style="display:">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Site<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
	<?php endif; ?>
  </ul>
  
 <div class="tabbed_container" > 
 <?php if ($this->_tpl_vars['site_customer']): ?>
 <div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'password'): ?>display:none<?php endif; ?>;min-height:260px"  id="d_password">
 


   <table class="edit" border=0  style="width:100%">

    <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset Password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>

   <tr>
   <td style="width:300px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send an Email: (<?php echo $this->_tpl_vars['customer']->get('Customer Main Plain Email'); ?>
)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
   <td style="width:300px">

   <span   style="cursor:pointer" onClick="forget_password(this, '<?php echo $this->_tpl_vars['customer']->get('Customer Main Plain Email'); ?>
')"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send an Email to Reset password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </td>
   <td><span  style="cursor:pointer"  onClick="show_change_password_dialog(this, <?php echo $this->_tpl_vars['user_main_id']; ?>
)"  >Set Password</span></td>
   <td>
	<span id="password_msg" style="display:"></span></td>
	
	
	</tr>
    
	<?php $_from = $this->_tpl_vars['registered_email']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['email']):
        $this->_foreach['foo']['iteration']++;
?>
	   <tr>
   <td style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send an Email: (<?php echo $this->_tpl_vars['email']['email']; ?>
)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
   <td style="width:300px">

   <span   style="cursor:pointer" onClick="forget_password(this, '<?php echo $this->_tpl_vars['email']['email']; ?>
')"  email=<?php echo $this->_tpl_vars['email']['email']; ?>
 ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send an Email to Reset password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </td>
   <td><span   style="cursor:pointer" user_key=<?php echo $this->_tpl_vars['email']['user_key']; ?>
 onClick="show_change_password_dialog(this,<?php echo $this->_tpl_vars['email']['user_key']; ?>
)" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set Password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td>
   </tr>
   <tr><td><span id="password_msg_<?php echo $this->_tpl_vars['key']; ?>
" style="display:"></span></td></tr>
   <?php endforeach; endif; unset($_from); ?>
   

   
   
   <?php if ($this->_tpl_vars['unregistered_count'] > 0): ?>
   <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Unregistered Emails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>

   	<?php $_from = $this->_tpl_vars['unregistered_email']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['email']):
        $this->_foreach['foo']['iteration']++;
?>
	   <tr>
   <td style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['email']['email']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
   <td style="width:200px">
   <div>
       <input type="button" class="button" onclick="register_email(this)" email=<?php echo $this->_tpl_vars['email']['email']; ?>
 value="Register in Website"/>
   </div></td>
   <td><span id="register_msg" style="display:"></span></td>
   </tr>
   
   <?php endforeach; endif; unset($_from); ?>
   
   <?php endif; ?>
   
   </table>
 </div>
 <?php endif; ?>
 
   <div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'merge'): ?>display:none<?php endif; ?>;min-height:260px"  id="d_merge">
   
   <table class="edit" border=0  style="width:700px">
   <tr>
   <td style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Merge with: (Customer ID)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
   <td style="width:200px">
   
   
   <div>
       <input style="text-align:left;width:100%" id="customer_b_id" value="" ovalue="" >
       <div id="customer_b_id_Container" style="" ></div>
   </div>
   
   </td>
   <td style="width:300px"><a id="go_merge" href="" class="state_details" style="display:none"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Go<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a><span id="merge_msg" class="error" style="display:none"></span></td>
   </tr>
   </table>
   
   </div>
   
<div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'communications'): ?>display:none<?php endif; ?>;min-height:260px"  id="d_communications">
    
    
    
    
<table class="edit">
 <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Emails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>
 
 <tr>
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send Newsletter<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Newsletter') == 'Yes'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Newsletter','Yes')" id="Customer Send Newsletter_Yes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Newsletter') == 'No'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Newsletter','No')" id="Customer Send Newsletter_No"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </div>
 </td>
 </tr>
  <tr>
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send Marketing Emails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Email Marketing') == 'Yes'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Email Marketing','Yes')" id="Customer Send Email Marketing_Yes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Email Marketing') == 'No'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Email Marketing','No')" id="Customer Send Email Marketing_No"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </div>
 </td>
 </tr>
 
  <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Post<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>
 

  <tr>
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send Marketing Post<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Postal Marketing') == 'Yes'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Send Postal Marketing') == 'No'): ?>selected<?php endif; ?>" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span><br/><br/>
   </div>
 </td>
 </tr>


<tbody id="add_to_post_cue" style="display:none">

  <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send Post <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>
 <tr>
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add Customer To Send Post<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Send Post Status') == 'To Send'): ?>selected<?php endif; ?>" onclick="save_comunications_send_post('Send Post Status','To Send')" id="Send Post Status_To Send"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Send Post Status') == 'Cancelled'): ?>selected<?php endif; ?>" onclick="save_comunications_send_post('Send Post Status','Cancelled')" id="Send Post Status_Cancelled"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </div>
 </td>
 </tr>
<tr>
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Post Type<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Post Type') == 'Letter'): ?>selected<?php endif; ?>" onclick="save_comunications_send_post('Post Type','Letter')" id="Post Type_Letter"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Letter<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Post Type') == 'Catalogue'): ?>selected<?php endif; ?>" onclick="save_comunications_send_post('Post Type','Catalogue')" id="Post Type_Catalogue"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Catalogue<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </div>
 </td>
 </tr>
 </tbody>
 
 

</table>
</div>
<div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'categories'): ?>display:none<?php endif; ?>;min-height:260px"  id="d_categories">

<table class="edit">
 <tr class="title"><td colspan=5><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Categories<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td></tr>
 
 <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['cat_key'] => $this->_tpl_vars['cat']):
        $this->_foreach['foo']['iteration']++;
?>
 <tr>
 
 <td class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['cat']->get('Category Name'); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
  <select id="cat<?php echo $this->_tpl_vars['cat_key']; ?>
" cat_key="<?php echo $this->_tpl_vars['cat_key']; ?>
"  onChange="save_category(this)">
    <?php $_from = $this->_tpl_vars['cat']->get_children_objects(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub_cat_key'] => $this->_tpl_vars['sub_cat']):
        $this->_foreach['foo2']['iteration']++;
?>
        <?php if (($this->_foreach['foo2']['iteration'] <= 1)): ?>
        <option <?php if ($this->_tpl_vars['categories_value'][$this->_tpl_vars['cat_key']] == ''): ?>selected="selected"<?php endif; ?> value=""><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Unknown<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></option>
        <?php endif; ?>
        <option <?php if ($this->_tpl_vars['categories_value'][$this->_tpl_vars['cat_key']] == $this->_tpl_vars['sub_cat_key']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['sub_cat']->get('Category Key'); ?>
"><?php echo $this->_tpl_vars['sub_cat']->get('Category Name'); ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
  </select>
  
 </td>   
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>

</div>
<div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'delivery'): ?>display:none<?php endif; ?>;min-height:260px"  id="d_delivery">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'edit_delivery_address_splinter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>
<div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'details'): ?>display:none<?php endif; ?>;"  id="d_details">
  
      

   <table class="edit" border=0 style="clear:both;margin-bottom:40px;width:100%">
<tr>
<td></td>
<td style="text-align:right;color:#777;font-size:90%">
<div id="delete_customer_warning" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none">
<h2><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delete Customer<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
<p>
<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This operation cannot be undone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>.<br> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Would you like to proceed?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</p>
<p id="delete_customer_msg"></p>
<span id="cancel_delete_customer"  style="cursor:pointer;display:none;font-weight:800" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No i dont want to delete it<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="save_delete_customer"  style="cursor:pointer;display:none;margin-left:20px;"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes, delete it!<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<p id="deleting" style="display:none;"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Deleting customer, wait please<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></p>
</div>
<span id="delete_customer" class="state_details" style="<?php if ($this->_tpl_vars['customer']->get('Customer With Orders') == 'Yes'): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delete Customer<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

</td>
<td>
 <div class="general_options" style="float:right">
	        <span  style="margin-right:10px;visibility:hidden"  id="save_edit_customer" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	        <span style="margin-right:10px;visibility:hidden" id="reset_edit_customer" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      </div>
</td>
</tr>

<tr>
<td style="width:150px"></td>
<td style="text-align:right;color:#777;font-size:90%;width:300px">
<div  id="convert_to_person_info" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none;margin-bottom:5px">
<p>
<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This operation will delete the company<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</p>
<div style="color:#999">
<span id="cancel_convert_to_person" class="state_details" style="display:none" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="save_convert_to_person" class="state_details" style="display:none;margin-left:10px;color:#777"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Do it!<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
</div>
</div>
<span id="convert_to_person" class="state_details" style="<?php if ($this->_tpl_vars['customer_type'] != 'Company'): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Convert to Person<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
</td>
</tr>


<tr>
<td></td>
<td style="text-align:right;color:#777;font-size:90%">
<span id="convert_to_company" class="state_details" style="<?php if ($this->_tpl_vars['customer_type'] == 'Company'): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Convert to Company<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="cancel_convert_to_company" class="state_details" style="display:none" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="save_convert_to_company" class="disabled state_details" style="display:none;margin-left:10px;;color:#777;"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save Conversion to Company<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
</td>
</tr>
   
  <tr id="New_Company_Name_tr"  style="display:none" class="first">
  <td style=""  class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="New_Company_Name" value="" ovalue="" valid="0">
       <div id="New_Company_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="New_Company_Name_msg"  class="edit_td_alert"></td>
 </tr> 
 
 
 
   
 <tr style="display:none"><td class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Type<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
	       <td > 
		 <div class="options" style="margin:5px 0" id="shelf_type_type_container">
		   <input type="hidden" value="<?php echo $this->_tpl_vars['shelf_default_type']; ?>
" ovalue="<?php echo $this->_tpl_vars['shelf_default_type']; ?>
" id="shelf_type_type"  >
		  <span class="radio<?php if ($this->_tpl_vars['customer_type'] == 'Company'): ?> selected<?php endif; ?>"  id="radio_shelf_type_<?php echo $this->_tpl_vars['customer_type']; ?>
" radio_value="<?php echo $this->_tpl_vars['customer_type']; ?>
"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 
		    <span class="radio<?php if ($this->_tpl_vars['customer_type'] == 'Person'): ?> selected<?php endif; ?>"  id="radio_shelf_type_<?php echo $this->_tpl_vars['customer_type']; ?>
" radio_value="<?php echo $this->_tpl_vars['customer_type']; ?>
"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Person<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 

		 </div>



		 
 
 <tr <?php if ($this->_tpl_vars['customer_type'] != 'Company'): ?>style="display:none"<?php endif; ?> class="first"><td style="" class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="Customer_Name" value="<?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
" valid="0">
       <div id="Customer_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Name_msg"  class="edit_td_alert"></td>
 </tr>
 
  <tr  class="first"><td style="" class="label"><?php if ($this->_tpl_vars['customer_type'] == 'Company'): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Registration Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Identification Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?>:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="Customer_Registration_Number" value="<?php echo $this->_tpl_vars['customer']->get('Customer Registration Number'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Registration Number'); ?>
" valid="0">
       <div id="Customer_Registration_Number_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Registration_Number_msg"  class="edit_td_alert"></td>
 </tr>
 
 
 

 <tr class=""><td style="" class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left;">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Contact_Name" value="<?php echo $this->_tpl_vars['customer']->get('Customer Main Contact Name'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Main Contact Name'); ?>
" valid="0">
       <div id="Customer_Main_Contact_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Main_Contact_Name_msg" class="edit_td_alert"></td>
 </tr>


 
 <tr class=""><td style="" class="label"><?php if ($this->_tpl_vars['customer']->get('customer main Plain Email') == $this->_tpl_vars['login_stat']['UserHandle']): ?>xxx<?php endif; ?><img   id="comment_icon_email" src="<?php if ($this->_tpl_vars['customer']->get_principal_email_comment() == ''): ?>art/icons/comment.gif<?php else: ?>art/icons/comment_filled.gif<?php endif; ?>" style="cursor:pointer;<?php if ($this->_tpl_vars['customer']->get('Customer Main Email Key') == ''): ?>display:none<?php endif; ?>" onClick="change_comment(this,'email',<?php echo $this->_tpl_vars['customer']->get('Customer Main Email Key'); ?>
)"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Email" value="<?php echo $this->_tpl_vars['customer']->get('Customer Main Plain Email'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Main Plain Email'); ?>
" valid="0">
       <div id="Customer_Main_Email_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="display_add_other_email" class="state_details" style="font-size:80%;color:#777;"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add other Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   <span id="Customer_Main_Email_msg" class="edit_td_alert"><?php echo $this->_tpl_vars['main_email_warning']; ?>
</span>
   </td>
 </tr>



 <?php $_from = $this->_tpl_vars['customer']->get_other_emails_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_email_key'] => $this->_tpl_vars['other_email']):
?>
  <tr  id="tr_other_email<?php echo $this->_tpl_vars['other_email_key']; ?>
"><td style="" class="label"><?php if ($this->_tpl_vars['other_email_login_handle'][$this->_tpl_vars['other_email']['email']] == $this->_tpl_vars['other_email']['email']): ?>xxx<?php endif; ?><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'email',<?php echo $this->_tpl_vars['other_email_key']; ?>
)">  <span id="tr_other_email_label<?php echo $this->_tpl_vars['other_email_key']; ?>
"><?php if ($this->_tpl_vars['other_email']['label'] == ''): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php echo $this->_tpl_vars['other_email']['label']; ?>
 (Email)<?php endif; ?>:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Email<?php echo $this->_tpl_vars['other_email_key']; ?>
" value="<?php echo $this->_tpl_vars['other_email']['email']; ?>
" ovalue="<?php echo $this->_tpl_vars['other_email']['email']; ?>
" valid="0">
       <div id="Customer_Email<?php echo $this->_tpl_vars['other_email_key']; ?>
_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Email<?php echo $this->_tpl_vars['other_email_key']; ?>
_msg" class="edit_td_alert"></span>
   </td>
 </tr>
<?php endforeach; endif; unset($_from); ?>


 <tr id="tr_add_other_email"  style="display:none"><td style="" class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Email" value="" ovalue="" valid="0">
       <div id="Customer_Other_Email_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Email_msg" class="edit_td_alert"></td>
 </tr>




 <tr class=""><td style="" class="label"><img  id="comment_icon_telephone" src="<?php if ($this->_tpl_vars['customer']->get_principal_telecom_comment('Telephone') == ''): ?>art/icons/comment.gif<?php else: ?>art/icons/comment_filled.gif<?php endif; ?>" style="cursor:pointer;<?php if ($this->_tpl_vars['customer']->get('Customer Main Telephone Key') == ''): ?>display:none<?php endif; ?>" onClick="change_comment(this,'telephone',<?php if ($this->_tpl_vars['customer']->get('Customer Main Telephone Key') == NULL): ?><?php echo 0; ?>
<?php else: ?><?php echo $this->_tpl_vars['customer']->get('Customer Main Telephone Key'); ?>
<?php endif; ?>)"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Telephone" value="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Telephone'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Telephone'); ?>
" valid="0">
       <div id="Customer_Main_Telephone_Container" style="" ></div>
     </div>
   </td>
   <td>
    <span id="display_add_other_telephone" class="state_details" style="font-size:80%;color:#777;<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML Telephone') == ''): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add other Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
    <span id="Customer_Main_Telephone_msg" class="edit_td_alert"><?php echo $this->_tpl_vars['main_telephone_warning']; ?>
</span>
   </td>
 </tr>
 
 
 
  <?php $_from = $this->_tpl_vars['customer']->get_other_telephones_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_telephone_key'] => $this->_tpl_vars['other_telephone']):
?>
 <tr  id="tr_other_telephone<?php echo $this->_tpl_vars['other_telephone_key']; ?>
"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'telephone',<?php echo $this->_tpl_vars['other_telephone_key']; ?>
)">  <span id="tr_other_telephone_label<?php echo $this->_tpl_vars['other_telephone_key']; ?>
"><?php if ($this->_tpl_vars['other_telephone']['label'] == ''): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php echo $this->_tpl_vars['other_telephone']['label']; ?>
 (Telephone)<?php endif; ?>:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Telephone<?php echo $this->_tpl_vars['other_telephone_key']; ?>
" value="<?php echo $this->_tpl_vars['other_telephone']['xhtml']; ?>
" ovalue="<?php echo $this->_tpl_vars['other_telephone']['xhtml']; ?>
" valid="0">
       <div id="Customer_Telephone<?php echo $this->_tpl_vars['other_telephone_key']; ?>
_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Telephone<?php echo $this->_tpl_vars['other_telephone_key']; ?>
_msg" class="edit_td_alert"></span>
   </td>
 </tr>
<?php endforeach; endif; unset($_from); ?>

 <tr id="tr_add_other_telephone"  style="display:none"><td style="" class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Telephone" value="" ovalue="" valid="0">
       <div id="Customer_Other_Telephone_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Telephone_msg" class="edit_td_alert"></td>
 </tr>

 
 
 
  <tr class=""><td style="" class="label"><img  id="comment_icon_mobile" src="<?php if ($this->_tpl_vars['customer']->get_principal_telecom_comment('Mobile') == ''): ?>art/icons/comment.gif<?php else: ?>art/icons/comment_filled.gif<?php endif; ?>" style="cursor:pointer;<?php if ($this->_tpl_vars['customer']->get('Customer Main Mobile Key') == ''): ?>display:none<?php endif; ?>" onClick="change_comment(this,'mobile',<?php if ($this->_tpl_vars['customer']->get('Customer Main Mobile Key') == NULL): ?><?php echo 0; ?>
<?php else: ?><?php echo $this->_tpl_vars['customer']->get('Customer Main Mobile Key'); ?>
<?php endif; ?>)"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Mobile" value="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Mobile'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Mobile'); ?>
" valid="0">
       <div id="Customer_Main_Mobile_Container" style="" ></div>
     </div>
   </td>
  
   
      <td>
    <span id="display_add_other_mobile" class="state_details" style="font-size:80%;color:#777;<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML Mobile') == ''): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add other Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
    <span id="Customer_Main_Mobile_msg" class="edit_td_alert"><?php echo $this->_tpl_vars['main_mobile_warning']; ?>
</span>
   </td>
   
 </tr>
 
 
 
   <?php $_from = $this->_tpl_vars['customer']->get_other_mobiles_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_mobile_key'] => $this->_tpl_vars['other_mobile']):
?>
 <tr  id="tr_other_mobile<?php echo $this->_tpl_vars['other_mobile_key']; ?>
"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'mobile',<?php echo $this->_tpl_vars['other_mobile_key']; ?>
)">  <span id="tr_other_mobile_label<?php echo $this->_tpl_vars['other_mobile_key']; ?>
"><?php if ($this->_tpl_vars['other_mobile']['label'] == ''): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php echo $this->_tpl_vars['other_mobile']['label']; ?>
 (Mobile)<?php endif; ?>:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Mobile<?php echo $this->_tpl_vars['other_mobile_key']; ?>
" value="<?php echo $this->_tpl_vars['other_mobile']['number']; ?>
" ovalue="<?php echo $this->_tpl_vars['other_mobile']['number']; ?>
" valid="0">
       <div id="Customer_Mobile<?php echo $this->_tpl_vars['other_mobile_key']; ?>
_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Mobile<?php echo $this->_tpl_vars['other_mobile_key']; ?>
_msg" class="edit_td_alert"></span>
   </td>
 </tr>
<?php endforeach; endif; unset($_from); ?>

 <tr id="tr_add_other_mobile"  style="display:none"><td style="" class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Mobile" value="" ovalue="" valid="0">
       <div id="Customer_Other_Mobile_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Mobile_msg" class="edit_td_alert"></td>
 </tr>
 
 
<tr class=""><td style="" class="label"><img  id="comment_icon_fax" src="<?php if ($this->_tpl_vars['customer']->get_principal_telecom_comment('FAX') == ''): ?>art/icons/comment.gif<?php else: ?>art/icons/comment_filled.gif<?php endif; ?>" style="cursor:pointer;<?php if ($this->_tpl_vars['customer']->get('Customer Main FAX Key') == ''): ?>display:none<?php endif; ?>" onClick="change_comment(this,'fax',<?php if ($this->_tpl_vars['customer']->get('Customer Main FAX Key') == NULL): ?><?php echo 0; ?>
<?php else: ?><?php echo $this->_tpl_vars['customer']->get('Customer Main FAX Key'); ?>
<?php endif; ?>)"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_FAX" value="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML FAX'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML FAX'); ?>
" valid="0">
       <div id="Customer_Main_FAX_Container" style="" ></div>
     </div>
   </td>
   <td>
<span id="display_add_other_fax" class="state_details" style="font-size:80%;color:#777;<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML FAX') == ''): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add other Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
    <span id="Customer_Main_FAX_msg" class="edit_td_alert"><?php echo $this->_tpl_vars['main_fax_warning']; ?>
</span>
   </td>
 </tr>

 <?php $_from = $this->_tpl_vars['customer']->get_other_faxes_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_fax_key'] => $this->_tpl_vars['other_fax']):
?>
 <tr  id="tr_other_fax<?php echo $this->_tpl_vars['other_fax_key']; ?>
"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'fax',<?php echo $this->_tpl_vars['other_fax_key']; ?>
)">  <span id="tr_other_fax_label<?php echo $this->_tpl_vars['other_fax_key']; ?>
"><?php if ($this->_tpl_vars['other_fax']['label'] == ''): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php echo $this->_tpl_vars['other_fax']['label']; ?>
 (Fax)<?php endif; ?>:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_FAX<?php echo $this->_tpl_vars['other_fax_key']; ?>
" value="<?php echo $this->_tpl_vars['other_fax']['number']; ?>
" ovalue="<?php echo $this->_tpl_vars['other_fax']['number']; ?>
" valid="0">
       <div id="Customer_FAX<?php echo $this->_tpl_vars['other_fax_key']; ?>
_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_FAX<?php echo $this->_tpl_vars['other_fax_key']; ?>
_msg" class="edit_td_alert"></span>
   </td>
 </tr>
<?php endforeach; endif; unset($_from); ?>

 <tr id="tr_add_other_fax"  style="display:none"><td style="" class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_FAX" value="" ovalue="" valid="0">
       <div id="Customer_Other_FAX_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_FAX_msg" class="edit_td_alert"></td>
 </tr>
 
  
 <?php $_from = $this->_tpl_vars['show_case']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['custom_field_key'] => $this->_tpl_vars['custom_field_value']):
?>
 <tr  id="tr_<?php echo $this->_tpl_vars['custom_field_value']['lable']; ?>
"><td style="" class="label"><?php echo $this->_tpl_vars['custom_field_key']; ?>
:</td>
   <td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Customer_<?php echo $this->_tpl_vars['custom_field_value']['lable']; ?>
" value="<?php echo $this->_tpl_vars['custom_field_value']['value']; ?>
" ovalue="<?php echo $this->_tpl_vars['custom_field_value']['value']; ?>
" valid="0">
       <div id="Customer_<?php echo $this->_tpl_vars['custom_field_value']['lable']; ?>
_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_<?php echo $this->_tpl_vars['custom_field_value']['lable']; ?>
_msg" class="edit_td_alert"></span>
   </td>
 </tr>
<?php endforeach; endif; unset($_from); ?>


 <tr id="tr_Customer_Preferred_Contact_Number"   style="<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML Mobile') == '' || $this->_tpl_vars['customer']->get('Customer Main XHTML Telephone') == ''): ?>display:none<?php endif; ?>" >
 <td class="label" style="width:200px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Preferred contact number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
 <td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
   <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Preferred Contact Number') == 'Telephone'): ?>selected<?php endif; ?>" onclick="save_preferred(this,'Telephone')" id="Customer_Preferred_Contact_Number_Telephone"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Customer Preferred Contact Number') == 'Mobile'): ?>selected<?php endif; ?>" onclick="save_preferred(this,'Mobile')" id="Customer_Preferred_Contact_Number_Mobile"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   </div>
 </td>
 </tr>


     </table>

   <div id="customer_contact_address" style="float:left;xborder:1px solid #ddd;width:430px;margin-right:20px;min-height:300px">
     <div style="border-bottom:1px solid #777;margin-bottom:5px">
       <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:
     </div>
     <table border=0 style="width:100%">
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'edit_address_splinter.tpl', 'smarty_include_vars' => array('address_identifier' => 'contact_','hide_type' => true,'hide_description' => true,'show_components' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     </table>
     <div style="display:none" id='contact_current_address' ></div>
     <div style="display:none" id='contact_address_display<?php echo $this->_tpl_vars['customer']->get('Customer Main Address Key'); ?>
' ></div>
   </div>

 <div id="customer_billing_address" style="float:left;xborder:1px solid #ddd;width:400px;margin-bottom:20px;">
     <div style="border-bottom:1px solid #777;margin-bottom:7px">
     
       <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing Information<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:<span class="state_details" style="float:right;display:none" address_key="" id="billing_cancel_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     
     </div>
     
     
       <table border=0>
       
       
       <tr class="">
 <td  class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Tax Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td  style="text-align:left;width:280px">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Tax_Number" value="<?php echo $this->_tpl_vars['customer']->get('Customer Tax Number'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Tax Number'); ?>
" valid="0">
       <div id="Customer_Tax_Number_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Tax_Number_msg" style="" class="edit_td_alert"></td>
 </tr>
       
       
	
     <tr  style="<?php if ($this->_tpl_vars['customer']->get('Customer Type') != 'Company'): ?>display:none<?php endif; ?>"><td class="lavel"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Fiscal Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
        <td style="text-align:left;">
     <div   >
       <input style="text-align:left;" id="Customer_Fiscal_Name" value="<?php echo $this->_tpl_vars['customer']->get('Customer Fiscal Name'); ?>
" ovalue="<?php echo $this->_tpl_vars['customer']->get('Customer Fiscal Name'); ?>
" valid="0">
       <div id="Customer_Fiscal_Name_Container" style="" ></div>
     </div>
   </td>
   </tr><tr> <td id="Customer_Fiscal_Name_msg" class="edit_td_alert"></td>
   <td><span  style="margin-right:10px;visibility:hidden"  id="save_edit_billing_data" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_billing_data" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td>
   
   
  
     
     </tr>


<?php if ($this->_tpl_vars['hq_country'] == 'ES'): ?>
<tr>
<td>RE:</td>
<td>
   <div id="cat_<?php echo $this->_tpl_vars['cat2_id']; ?>
" default_cat="<?php echo $this->_tpl_vars['cat2']['default_id']; ?>
"   class="options" style="margin:0">
      <span class="<?php if ($this->_tpl_vars['customer']->get('Recargo Equivalencia') == 'Yes'): ?>selected<?php endif; ?>" onclick="save_comunications('Recargo Equivalencia','Yes')" id="Recargo Equivalencia_Yes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> <span class="<?php if ($this->_tpl_vars['customer']->get('Recargo Equivalencia') == 'No'): ?>selected<?php endif; ?>" onclick="save_comunications('Recargo Equivalencia','No')" id="Recargo Equivalencia_No"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span><br/><br/>
   </div>
<td>
</tr>
<?php else: ?>
<tr style="display:none">
<td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Tax Code<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td>
<select id="tax_code">
  <?php $_from = $this->_tpl_vars['tax_codes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub_cat_key'] => $this->_tpl_vars['sub_cat']):
        $this->_foreach['foo2']['iteration']++;
?>
    
        <option <?php if ($this->_tpl_vars['customer']->get('Customer Tax Category Code') == $this->_tpl_vars['sub_cat']['code']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['sub_cat']['code']; ?>
"><?php echo $this->_tpl_vars['sub_cat']['name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
</select></td>
</tr>
<?php endif; ?>
      
      
     </table>
     
       
       <div id="billing_address_block" style="margin-bottom:10px">
       <table style="width:100%" border=0>
       <tr style="border-bottom:1px solid #777">
       <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
       <td class="aright">
       <span style="font-size:90%;display:none" id="set_contact_address_as_billing"  class="edit aright state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Use contact address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

       <span style="font-size:90%;<?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') != 'Contact' )): ?>display:none<?php endif; ?>" id="show_new_billing_address"  same_as_contact="<?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') == 'Contact' )): ?>1<?php else: ?>0<?php endif; ?>"  class="edit aright state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set up different address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
       <span style="font-size:90%;<?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') == 'Contact' )): ?>display:none<?php endif; ?>" id="show_edit_billing_address"  same_as_contact="<?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') == 'Contact' )): ?>1<?php else: ?>0<?php endif; ?>"  address_key="<?php echo $this->_tpl_vars['customer']->get('Customer Billing Address Key'); ?>
" class="edit aright state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Edit address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

       </td>
       </tr>
       <tr >
       <td colspan=2 id="billing_address">
        
            <?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') == 'Contact' )): ?>
   <span style="font-weight:600"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Same as contact address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 
   <?php else: ?>
   <?php echo $this->_tpl_vars['customer']->billing_address_xhtml(); ?>

   <?php endif; ?>
   
       </td>
       </tr>
      
      
      
       </table> 
      
        <table id="new_billing_address_table" border=0 style="width:100%;display:none">
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'edit_address_splinter.tpl', 'smarty_include_vars' => array('close_if_reset' => true,'address_identifier' => 'billing_','address_type' => 'Shop','show_tel' => false,'show_contact' => false,'address_function' => 'Billing','hide_type' => true,'hide_description' => true,'show_form' => false,'show_components' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     </table>
      
</div>
       
       
   </div>
 
 <div id="customer_delivery_address" style="display:none;float:left;xborder:1px solid #ddd;width:400px;">
     <div style="border-bottom:1px solid #777;margin-bottom:5px">
       <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:<span class="state_details" style="float:right;display:none" address_key="" id="billing_cancel_edit_address"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     </div>
     
     <div id="delivery_current_address_bis" style="margin-bottom:10px">
     <?php if (( $this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Contact' ) || ( $this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Billing' && ( $this->_tpl_vars['customer']->get('Customer Main Address Key') == $this->_tpl_vars['customer']->get('Customer Billing Address Key') ) )): ?>
     
     <span style="font-weight:600"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Same as contact address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 

     
     <?php elseif ($this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Billing'): ?>
     
     <span style="font-weight:600"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Same as billing address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 

     
     <?php else: ?>
     <?php echo $this->_tpl_vars['customer']->delivery_address_xhtml(); ?>

    
     
     <?php endif; ?>
     <div id="billing_address_display<?php echo $this->_tpl_vars['customer']->get('Customer Billing Address Key'); ?>
" style="display:none"></div>
      </div>
    <span id="delivery2" class="state_details">Set up different address</span>

    

   </div>



<div style="clear:both"></div>


   </div>
   
 <?php if ($this->_tpl_vars['customer_type'] == 'Company'): ?>
   <div  class="edit_block" style="<?php if ($this->_tpl_vars['edit'] != 'company'): ?>display:none<?php endif; ?>"  id="d_company">
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;display:none"  id="save_new_customer" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	<span style="margin-right:10px;display:none" id="close_add_customer" class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	
      </div>


      <div id="new_customer_messages" class="messages_block"></div>

      


     
	  
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'edit_company_splinter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

     
   </div>
<?php else: ?>
<div  class="edit_block" style="display:none"  id="d_company"></div>
<?php endif; ?>
  
   
</div>

</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Filter options<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</li>
      <?php $_from = $this->_tpl_vars['filter_menu0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('<?php echo $this->_tpl_vars['menu']['db_key']; ?>
','<?php echo $this->_tpl_vars['menu']['label']; ?>
',0)"> <?php echo $this->_tpl_vars['menu']['menu_label']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
</div>

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Rows per Page<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</li>
      <?php $_from = $this->_tpl_vars['paginator_menu0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp(<?php echo $this->_tpl_vars['menu']; ?>
,0)"> <?php echo $this->_tpl_vars['menu']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
</div>

<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Country List<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
            
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 100,'filter_name' => $this->_tpl_vars['filter_name100'],'filter_value' => $this->_tpl_vars['filter_value100'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div  id="table100"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>


<div id="dialog_other_field_label">
  <div id="other_field_label_msg"></div>
    <input type="hidden" value="" id="other_field_label_scope"/>

  <input type="hidden" value="" id="other_field_label_scope_key"/>
  <table style="padding:20px;margin:20px 10px 10px 5px" >
 
    <tr><td colspan=2>
	<input  id="other_field_label" value=""  /> (<span id="other_field_label_scope_name"></span>)
      </td>
    </tr>
    <tr class="buttons" style="font-size:100%;">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button"    style="visibility:hidden;" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td>
  <td style="text-align:center;width:50%">
    <span  style="display:block;margin-top:5px" onclick="save_other_field_label()" id="note_save"  class="unselectable_text button"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td></tr>
</table>
</div>


<div id="dialog_comment">
  <div id="comment_msg"></div>
    <input type="hidden" value="" id="comment_scope"/>

  <input type="hidden" value="" id="comment_scope_key"/>
  
  
    <input type="hidden" value="<?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('Telephone'); ?>
" id="comment_telephone"/>
        <input type="hidden" value="<?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('FAX'); ?>
" id="comment_fax"/>
    <input type="hidden" value="<?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('Mobile'); ?>
" id="comment_mobile"/>
    <input type="hidden" value="<?php echo $this->_tpl_vars['customer']->get_principal_email_comment(); ?>
" id="comment_email"/>

  <table style="padding:20px;margin:20px 10px 10px 5px" >
 <tr><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Comment<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td></tr>
    <tr><td colspan=2>
	<input  id="comment" value=""  /> 
      </td>
    </tr>
    <tr class="buttons" style="font-size:100%;">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button"    style="visibility:hidden;" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td>
  <td style="text-align:center;width:50%">
    <span  style="display:block;margin-top:5px" onclick="save_comment()" id="comment_save"  class="unselectable_text button"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></td></tr>
</table>
</div>



 





<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>