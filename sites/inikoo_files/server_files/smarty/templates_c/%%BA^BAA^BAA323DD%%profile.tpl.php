<?php /* Smarty version 2.6.22, created on 2011-10-10 15:18:49
         compiled from profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'profile.tpl', 32, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'contacts_navigation.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>



<div id="no_details_title"  style="clear:left;xmargin:0 20px;display:none">
    <h1 style="padding-bottom:0px"><?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
 <span style="color:SteelBlue"><?php echo $this->_tpl_vars['id']; ?>
</span>
     
      
    </h1> 

<?php if ($this->_tpl_vars['customer']->get('Customer Tax Number') != ''): ?><h2 style="padding:0"><?php echo $this->_tpl_vars['customer']->get('Customer Tax Number'); ?>
</h2><?php endif; ?>    
  </div>
  
 
     

       

     
<div  style="width:490px;float:left" >    
     
<table id="customer_data" border=0 style="width:100%">
    <tr>
        <?php if ($this->_tpl_vars['customer']->get('Customer Main Address Key')): ?><td valign="top"><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Address'); ?>
</td><?php endif; ?>
        <td  valign="top">
            <table border=0 style="padding:0">
                <?php if ($this->_tpl_vars['customer']->get('Customer Main Contact Key')): ?><tr><td colspan=2  class="aright"><?php echo $this->_tpl_vars['customer']->get('Customer Main Contact Name'); ?>
</td ></tr><?php endif; ?>
                <?php if ($this->_tpl_vars['customer']->get('Customer Main Email Key')): ?><tr><td colspan=2  class="aright"><?php echo $this->_tpl_vars['customer']->get('customer main XHTML email'); ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/email.png"/></td><?php if ($this->_tpl_vars['customer']->get('customer main Plain Email') == $this->_tpl_vars['login_stat']['UserHandle']): ?><td><img src="art/icons/user_go.png" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"></td><?php endif; ?><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['customer']->get_principal_email_comment(); ?>
</td></tr><?php endif; ?>
                <?php $_from = $this->_tpl_vars['customer']->get_other_emails_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_email']):
?>
                    <tr><td colspan=2   class="aright"><?php echo $this->_tpl_vars['other_email']['xhtml']; ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/email.png"/></td><?php if ($this->_tpl_vars['other_email_login_handle'][$this->_tpl_vars['other_email']['email']] == $this->_tpl_vars['other_email']['email']): ?><td><img src="art/icons/user_go.png"/></td><?php endif; ?><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['other_email']['label']; ?>
</td></tr>
                <?php endforeach; endif; unset($_from); ?>
                <?php if ($this->_tpl_vars['customer']->get('Customer Main Telephone Key')): ?><tr><td colspan=2 class="aright"  style="<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML Mobile') && $this->_tpl_vars['customer']->get('Customer Preferred Contact Number') == 'Telephone'): ?>font-weight:800<?php endif; ?>"   ><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Telephone'); ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" src="art/icons/telephone.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('Telephone'); ?>
</td></tr><?php endif; ?>
                <?php $_from = $this->_tpl_vars['customer']->get_other_telephones_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_tel']):
?>
                    <tr><td colspan=2   class="aright"><?php echo $this->_tpl_vars['other_tel']['xhtml']; ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/telephone.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['other_tel']['label']; ?>
</td></tr>
                <?php endforeach; endif; unset($_from); ?>

                <?php if ($this->_tpl_vars['customer']->get('Customer Main Mobile Key')): ?><tr><td colspan=2 class="aright"  style="<?php if ($this->_tpl_vars['customer']->get('Customer Main XHTML Telephone') && $this->_tpl_vars['customer']->get('Customer Preferred Contact Number') == 'Mobile'): ?>font-weight:800<?php endif; ?>" ><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Mobile'); ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" src="art/icons/phone.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('Mobile'); ?>
</td></tr><?php endif; ?>
                <?php $_from = $this->_tpl_vars['customer']->get_other_mobiles_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_tel']):
?>
                    <tr><td colspan=2   class="aright"><?php echo $this->_tpl_vars['other_tel']['xhtml']; ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mobile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/phone.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['other_tel']['label']; ?>
</td></tr>
                <?php endforeach; endif; unset($_from); ?>

                <?php if ($this->_tpl_vars['customer']->get('Customer Main FAX Key')): ?><tr><td colspan=2 class="aright"><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML FAX'); ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/printer.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['customer']->get_principal_telecom_comment('FAX'); ?>
</td></tr><?php endif; ?>
                <?php $_from = $this->_tpl_vars['customer']->get_other_faxes_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other_tel']):
?>
                    <tr><td colspan=2   class="aright"><?php echo $this->_tpl_vars['other_tel']['xhtml']; ?>
</td ><td><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" title="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"  src="art/icons/printer.png"/></td><td style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['other_tel']['label']; ?>
</td></tr>
                <?php endforeach; endif; unset($_from); ?>

				<?php $_from = $this->_tpl_vars['show_case']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
				<?php if ($this->_tpl_vars['value'] != ''): ?>
				<tr>
				<td colspan=2 class="aright"><?php echo $this->_tpl_vars['value']; ?>
</td><td <td colspan=2 class="aleft" style="color:#777;font-size:80%"><?php echo $this->_tpl_vars['name']; ?>
</td>
				</tr>
				<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
		</table>
        </td>
    </tr>
    
  <?php if ($this->_tpl_vars['customer']->get('Customer Billing Address Link') != 'Contact' || $this->_tpl_vars['customer']->get('Customer Delivery Address Link') != 'Contact'): ?>  
  <tbody>  
  <tr style="font-size:90%;height:30px;vertical-align:bottom">
  <td style=";vertical-align:bottom"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
   <td style=";vertical-align:bottom"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    </tr>
  <tr style="font-size:90%;border-top:1px solid #ccc">
  <td >
  
 
 
  <span><?php echo $this->_tpl_vars['customer']->get('Customer Fiscal Name'); ?>
</span><br/>
  <div>
  <?php if (( $this->_tpl_vars['customer']->get('Customer Billing Address Link') == 'Contact' )): ?>
   <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing Address Same as contact address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 
   <?php else: ?>
   <?php echo $this->_tpl_vars['customer']->billing_address_xhtml(); ?>

   <?php endif; ?>
  </div>
</td>
<td>

 
  <div>
  <?php if (( $this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Contact' ) || ( $this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Billing' && ( $this->_tpl_vars['customer']->get('Customer Main Address Key') == $this->_tpl_vars['customer']->get('Customer Billing Address Key') ) )): ?>
     
     <span style="font-weight:600"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Same as contact address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 

     
     <?php elseif ($this->_tpl_vars['customer']->get('Customer Delivery Address Link') == 'Billing'): ?>
     
     <span style="font-weight:600"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Same as billing address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> 

     
     <?php else: ?>
     <?php echo $this->_tpl_vars['customer']->delivery_address_xhtml(); ?>

    
     
     <?php endif; ?>
  </div>

  </td>
  </tr>
  </tbody>  
 <?php endif; ?>
    
    
</table>
<div id="overviews" style="border-top:1px solid #eee;width:800px">

<div id="orders_overview" style="float:left;;margin-right:40px;width:300px; display:none" >
  <h2 style="font-size:120%;left-align:0 "><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Overview<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>


  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;min-width:300px">
  <tr>
  <td>

  <?php if ($this->_tpl_vars['customer']->get('Customer Type by Activity') == 'Losing'): ?>
  
    <?php elseif ($this->_tpl_vars['customer']->get('Customer Type by Activity') == 'Lost'): ?>
 <span style="font-weight:800"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Lost Customer<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span> (<?php echo $this->_tpl_vars['customer']->get('Lost Date'); ?>
)

  
  <?php else: ?>
 <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Since<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: <?php echo $this->_tpl_vars['customer']->get('First Contacted Date'); ?>

  <?php endif; ?>
  
  </td></tr>
  <tr><td><?php if ($this->_tpl_vars['customer_type']): ?>User is registered in the site<?php endif; ?></td></tr>
  <tr><td><?php echo $this->_tpl_vars['correlation_msg']; ?>
</td></tr>
  
<?php if ($this->_tpl_vars['customer']->get('Customer Send Newsletter') == 'No' || $this->_tpl_vars['customer']->get('Customer Send Email Marketing') == 'No' || $this->_tpl_vars['customer']->get('Customer Send Postal Marketing') == 'No'): ?>

   <tr><td>
   <div style="font-size:90%">
   <?php if ($this->_tpl_vars['customer']->get('Customer Send Newsletter') == 'No'): ?><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Attention<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" width='14' src="art/icons/exclamation.png" /> <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Don't send newsletters<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><span><br/><?php endif; ?>
   <?php if ($this->_tpl_vars['customer']->get('Customer Send Email Marketing') == 'No'): ?><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Attention<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" width='14' src="art/icons/exclamation.png" /> <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Don't send marketing by email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><span><br/><?php endif; ?>
   <?php if ($this->_tpl_vars['customer']->get('Customer Send Postal Marketing') == 'No'): ?><img alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Attention<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" width='14' src="art/icons/exclamation.png" /> <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Don't send marketing by post<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><span><br/><?php endif; ?>
   </div>
	</td></tr>
<?php endif; ?>
  </table>

</div>

<?php if ($this->_tpl_vars['customer']->get('Customer Orders') > 0): ?>
<div id="customer_overview"  style="float:left;width:400px" >
  <h2 style="font-size:120%; text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Orders Overview<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;">
    <tr><td>
	<?php if ($this->_tpl_vars['customer']->get('Customer Orders') == 1): ?>
	<?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
 <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>has place one order<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>.  
	<?php elseif ($this->_tpl_vars['customer']->get('Customer Orders') > 1): ?> 
	<?php echo $this->_tpl_vars['customer']->get('customer name'); ?>
 <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>has placed<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <b><?php echo $this->_tpl_vars['customer']->get('Customer Orders'); ?>
</b> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>orders so far<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>, <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>which amounts to a total of<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <b><?php echo $this->_tpl_vars['customer']->get('Net Balance'); ?>
</b> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>plus tax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>an average of<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo $this->_tpl_vars['customer']->get('Total Net Per Order'); ?>
 <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>per order<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>).
	<?php if ($this->_tpl_vars['customer']->get('Customer Orders Invoiced')): ?><br/><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This customer usually places an order every<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo $this->_tpl_vars['customer']->get('Order Interval'); ?>
.<?php endif; ?>
	<?php else: ?>
	Customer has not place any order yet.
	<?php endif; ?>
	</td></tr>
  </table>
</div>
<?php endif; ?>

</div>
</div>

<div id="sticky_note_div" class="sticky_note" style="width:270px; display:none">
<img id="sticky_note" style="float:right;cursor:pointer"src="art/icons/edit.gif">
<div  id="sticky_note_content" style="padding:10px 15px 10px 15px;"><?php echo $this->_tpl_vars['customer']->get('Sticky Note'); ?>
</div>
</div>



<div style="clear:both"></div>
</div>



















  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item <?php if ($this->_tpl_vars['view'] == 'details'): ?>selected<?php endif; ?>"  id="details">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
    <li> <span class="item <?php if ($this->_tpl_vars['view'] == 'history'): ?>selected<?php endif; ?>"  id="history">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>History, Notes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
	<?php if ($this->_tpl_vars['customer_type']): ?>
	<li> <span class="item <?php if ($this->_tpl_vars['view'] == 'login_stat'): ?>selected<?php endif; ?>"  id="login_stat">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login Status<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
	<?php endif; ?>
    <li <?php if (! $this->_tpl_vars['customer']->get('Customer Orders')): ?>style="display:none"<?php endif; ?>> <span class="item <?php if ($this->_tpl_vars['view'] == 'products'): ?>selected<?php endif; ?>" id="products"  ><span>  <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Products Ordered<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
    <li <?php if (! $this->_tpl_vars['customer']->get('Customer Orders')): ?>style="display:none"<?php endif; ?>> <span class="item <?php if ($this->_tpl_vars['view'] == 'orders'): ?>selected<?php endif; ?>"  id="orders">  <span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Order Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></span></li>
	
 </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">

  </div>
  
 
  <div id="block_details"  style="<?php if ($this->_tpl_vars['view'] != 'details'): ?>display:none;<?php endif; ?>clear:both;margin:20px 0 40px 0;padding:0 20px">


<h2 style="clear:both;text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Custom Fields<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		  <?php $_from = $this->_tpl_vars['customer_custom_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
		  <tr>
		  <td><?php echo $this->_tpl_vars['name']; ?>
:</td><td><?php echo $this->_tpl_vars['value']; ?>
</td>
		  </tr>
		  <?php endforeach; endif; unset($_from); ?>
		</table>
</div>  
  
  
<h2 style="clear:both; text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

<div style="float:left;width:450px">
<table    class="show_info_product">


  <tr>
		    <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Tax Category Code<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Tax Category Code'); ?>
</td>
		    </tr>
		 <tr style="<?php if ($this->_tpl_vars['hq_country'] != 'ES'): ?>display:none;<?php endif; ?>;border-top:1px solid #ccc">
		    <td>Recargo Equivalencia</td><td><?php echo $this->_tpl_vars['customer']->get('Recargo Equivalencia'); ?>
</td>
		    </tr>
		
		  <tr style="border-top:1px solid #ccc">
		  		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Usual Payment Method<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Usual Payment Method'); ?>
</td>

		    </tr>
		    <?php if ($this->_tpl_vars['customer']->get('Customer Usual Payment Method') != $this->_tpl_vars['customer']->get('Customer Last Payment Method')): ?>
		   <tr>
		   		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Last Payment Method<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Last Payment Method'); ?>
</td>

		    </tr>
		 <?php endif; ?>
		   <tr style="border-top:1px solid #ccc">
		  		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer XHTML Billing Address'); ?>
</td>

		    </tr>
		</table>
</div>

<h2 style="clear:both;text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
<div style="float:both;width:450px">
<table    class="show_info_product">
  <tr>
		    <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customer Type<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Type'); ?>
</td>
		    </tr>
		    
		    <?php if ($this->_tpl_vars['customer']->get('Customer Type') == 'Company'): ?>
		    <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Name'); ?>
</td>
		    </tr>
		     <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Tax Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Tax Number'); ?>
</td>
		    </tr>
		    <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Registration Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Registration Number'); ?>
</td>
		    </tr>
		  <?php endif; ?>
		  <tr style="border-top:1px solid #ccc">
		  
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Main Contact Name'); ?>
</td>
		    </tr>
		   <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Email'); ?>
</td>
		    </tr>
		  <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML Telephone'); ?>
</td>
		    </tr>
		  
		  <tr>
		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact Fax<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer Main XHTML FAX'); ?>
</td>
		    </tr>
		  

		</table>
</div>

<div class="contact_cards" style="display:none" >
<?php $_from = $this->_tpl_vars['customer']->get_contact_cards(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['card']):
?>
<?php echo $this->_tpl_vars['card']; ?>

<?php endforeach; endif; unset($_from); ?>
</div>


<h2 style="clear:both; text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		   <tr >
		  		      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delivery Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['customer']->get('Customer XHTML Main Delivery Address'); ?>
</td>

		    </tr>
		</table>
</div>
<div style="clear:both"></div>

<?php if ($this->_tpl_vars['customer_type']): ?>
<h2 style="clear:both; text-align:left"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login Details<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
<div style="float:left;width:450px">
<table    class="show_info_product">

		<tr ><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Last Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserLastLogin']; ?>
</td></tr>
		<tr ><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Login Count<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserLoginCount']; ?>
</td></tr>
		<tr><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Last Login IP<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserLastLoginIP']; ?>
</td></tr>
		<tr><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Failed Login Count<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserFailedLoginCount']; ?>
</td></tr>
		<tr><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Last Failed Login IP<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserLastFailedLoginIP']; ?>
</td></tr>
		<tr><td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User Last Failed Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['login_stat']['UserLastFailedLogin']; ?>
</td></tr>
		</table>
</div>
<?php endif; ?>

<div style="clear:both"></div>

</div>
 
 <div id="block_history" class="data_table" style="<?php if ($this->_tpl_vars['view'] != 'history'): ?>display:none;<?php endif; ?>clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>History/Notes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
           <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details <?php if ($this->_tpl_vars['elements']['Changes']): ?>selected<?php endif; ?> label_customer_history_changes"  id="elements_changes" table_type="changes"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Changes History<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<span id="elements_changes_number"><?php echo $this->_tpl_vars['elements_number']['Changes']; ?>
</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details <?php if ($this->_tpl_vars['elements']['Orders']): ?>selected<?php endif; ?> label_customer_history_orders"  id="elements_orders" table_type="orders"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Order History<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<span id="elements_orders_number"><?php echo $this->_tpl_vars['elements_number']['Orders']; ?>
</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details <?php if ($this->_tpl_vars['elements']['Notes']): ?>selected<?php endif; ?> label_customer_history_notes"  id="elements_notes" table_type="notes"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Staff Notes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<span id="elements_notes_number"><?php echo $this->_tpl_vars['elements_number']['Notes']; ?>
</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details <?php if ($this->_tpl_vars['elements']['Attachments']): ?>selected<?php endif; ?> label_customer_history_attachments"  id="elements_attachments" table_type="attachments"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Attachments<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<span id="elements_notes_number"><?php echo $this->_tpl_vars['elements_number']['Attachments']; ?>
</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details <?php if ($this->_tpl_vars['elements']['Emails']): ?>selected<?php endif; ?> label_customer_history_emails"  id="elements_emails" table_type="emails"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Emails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<span id="elements_notes_number"><?php echo $this->_tpl_vars['elements_number']['Emails']; ?>
</span>)</span>

        </div>
     </div>
          <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>

      
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 0,'filter_name' => $this->_tpl_vars['filter_name0'],'filter_value' => $this->_tpl_vars['filter_value0'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>

 <div id="block_login_stat" class="data_table" style="<?php if ($this->_tpl_vars['view'] != 'login_stat'): ?>display:none;<?php endif; ?>clear:both;margin:20px 0 40px 0;padding:0 20px">

      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 3,'filter_name' => $this->_tpl_vars['filter_name3'],'filter_value' => $this->_tpl_vars['filter_value3'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div  id="table3"   class="data_table_container dtable btable "> </div>
 
 </div>
	
<div id="block_products" class="data_table" style="<?php if ($this->_tpl_vars['view'] != 'products'): ?>display:none;<?php endif; ?>clear:both;margin:20px 0 40px 0;padding:0 20px">

 
	<div style="float:left" id="plot1">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_departments_pie&customer_key=<?php echo $this->_tpl_vars['customer']->id; ?>
")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot1");
		// ]]>
	</script>

<div style="float:left" id="plot2">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_families_pie&customer_key=<?php echo $this->_tpl_vars['customer']->id; ?>
")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot2");
		// ]]>
	</script>
	
	
      <span class="clean_table_title" style="clear:both"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Product Families Ordered<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 1,'filter_name' => $this->_tpl_vars['filter_name1'],'filter_value' => $this->_tpl_vars['filter_value1'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
       <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

<div id="block_orders" class="data_table" style="<?php if ($this->_tpl_vars['view'] != 'orders'): ?>display:none;<?php endif; ?>clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Orders<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 2,'filter_name' => $this->_tpl_vars['filter_name2'],'filter_value' => $this->_tpl_vars['filter_value2'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
       <div  id="table2"   class="data_table_container dtable btable "> </div>
  </div>
</div> 

<div>








<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
