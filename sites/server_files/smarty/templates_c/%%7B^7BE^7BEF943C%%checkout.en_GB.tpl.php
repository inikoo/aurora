<?php /* Smarty version 2.6.22, created on 2010-11-16 10:08:38
         compiled from templates/checkout.en_GB.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'templates/checkout.en_GB.tpl', 25, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['head_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body class="yui-skin-sam kaktus">
  <div id="container" >
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "templates/checkout_header.en_GB.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   
      
      
     <div class="order_edit" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 0px 0">

<div class="order_edit_block" >

<div class="payment">
<h2>Payment Method</h2>
<?php echo $this->_tpl_vars['order']->get('Order Payment Method'); ?>

</div>
<span class="state_details options">Change Payment Method</span>
</div>


<div class="order_edit_block" style="margin-left:20px" >
<div class="address">
<h2>Billing Address</h2>
<?php echo $this->_tpl_vars['order']->get('Order XHTML Ship Tos'); ?>

</div>
<span id="change_billing_address" class="state_details options" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Change Billing Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

</div>


<div class="order_edit_block" style="margin-left:20px">

<div class="address">
<h2>Delivery Address</h2>
<?php echo $this->_tpl_vars['order']->get('Order XHTML Ship Tos'); ?>

</div>
<span id="change_delivery_address" class="state_details" style="display:block;margin-top:10px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Change Delivery Address<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set this order is for collection<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<div id="for_collection"  style="<?php if ($this->_tpl_vars['order']->get('Order For Collection') == 'No'): ?>display:none;<?php endif; ?>float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
<span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>For collection<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
<span id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Set for shipping<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
</div>



</div>


     

          

       
       
       <div style="clear:both"></div>
      </div>
	
	
 
    
	<div style="clear:both;height:20px"></div>
	<div style="border:0px solid #ddd;width:210px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   
	   <tr  <?php if ($this->_tpl_vars['order']->get('Order Items Discount Amount') == 0): ?>style="display:none"<?php endif; ?> id="tr_order_items_gross"  ><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Items Gross<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td width=100 class="aright" id="order_items_gross"><?php echo $this->_tpl_vars['order']->get('Items Gross Amount'); ?>
</td></tr>
	   <tr  <?php if ($this->_tpl_vars['order']->get('Order Items Discount Amount') == 0): ?>style="display:none"<?php endif; ?>   id="tr_order_items_discounts"  ><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Discounts<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td width=100 class="aright"  id="order_items_discount">-<?php echo $this->_tpl_vars['order']->get('Items Discount Amount'); ?>
</td></tr>
	   
	   
	   <tr style="display:none"><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Items Net<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td width=100 class="aright" id="order_items_net"><?php echo $this->_tpl_vars['order']->get('Items Net Amount'); ?>
</td></tr>
	 
	   <tr  <?php if ($this->_tpl_vars['order']->get('Order Net Credited Amount') == 0): ?>style="display:none"<?php endif; ?>><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Credits<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td width=100 class="aright" id="order_credits"  ><?php echo $this->_tpl_vars['order']->get('Net Credited Amount'); ?>
</td></tr>
	   
	   <tr <?php if ($this->_tpl_vars['order']->get('Order Charges Net Amount') == 0): ?> style="display:none"<?php endif; ?>  id="tr_order_items_charges"    ><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Charges<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td id="order_charges"  width=100 class="aright"><?php echo $this->_tpl_vars['order']->get('Charges Net Amount'); ?>
</td></tr>
	   
	   <tr id="tr_order_shipping" style="<?php if ($this->_tpl_vars['order']->get('Order Shipping Method') == 'Calculated' && $this->_tpl_vars['order']->get('Order Shipping Net Amount') != ''): ?><?php else: ?>display:none;<?php endif; ?>"><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Shipping<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	   <td id="order_shipping" width=100 class="aright"><?php echo $this->_tpl_vars['order']->get('Shipping Net Amount'); ?>
</td>
	   
	   
	   </tr>
	   
	 <tr id="tr_order_shipping_on_demand" style="<?php if ($this->_tpl_vars['order']->get('Order Shipping Method') == 'On Demand' || ( $this->_tpl_vars['order']->get('Order Shipping Method') == 'Calculated' && $this->_tpl_vars['order']->get('Order Shipping Net Amount') == '' )): ?><?php else: ?>display:none;<?php endif; ?>"><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Shipping<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	   <td  width=100 class="aright"><span id="given_shipping"  ><?php if ($this->_tpl_vars['order']->get('Order Shipping Net Amount') != ''): ?><?php echo $this->_tpl_vars['order']->get('Shipping Net Amount'); ?>
</span><?php endif; ?>
	   
	   <br/><span class="state_details" id="set_shipping"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Change Shipping<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

	   </td>
	   
	   
	   </tr>
	   
	   
	   <tr style="border-top:1px solid #777"><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Net<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td id="order_net" width=100 class="aright"><?php echo $this->_tpl_vars['order']->get('Total Net Amount'); ?>
</td></tr>
	   
	   
	   <tr style="border-bottom:1px solid #777"><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>VAT<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td id="order_tax" width=100 class="aright"><?php echo $this->_tpl_vars['order']->get('Total Tax Amount'); ?>
</td></tr>
	   <tr><td  class="aright" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Total<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><td id="order_total" width=100 class="aright"><b><?php echo $this->_tpl_vars['order']->get('Total Amount'); ?>
</b></td></tr>
	   
	 </table>
       </div>
	
	<div class="data_table"  style="clear:both;margin-bottom:40px">
	<span id="table_title" class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Items<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

	<div id="table_type">
	 	</div>
	
     

     
    <div id="list_options0"> 
      

 
  </div>


    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0" style="display:none"><div class="clean_table_info"><span id="filter_name0"><?php echo $this->_tpl_vars['filter_name']; ?>
</span>: <input style="border-bottom:none" id='f_input0' value="<?php echo $this->_tpl_vars['filter_value']; ?>
" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;display:none"></div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  
</div>
	
	
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </body>