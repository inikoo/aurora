<?php /* Smarty version 2.6.22, created on 2010-10-19 11:25:09
         compiled from ../templates/department.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', '../templates/department.tpl', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="bd" >
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'assets_navigation.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div style="clear:left;"> 
 <span class="branch" ><a  href="store.php?id=<?php echo $this->_tpl_vars['store']->id; ?>
"><?php echo $this->_tpl_vars['store']->get('Store Name'); ?>
</a> &rarr; <?php echo $this->_tpl_vars['department']->get('Product Department Name'); ?>
</span>
 </div>
 <div id="no_details_title" style="clear:both;<?php if ($this->_tpl_vars['show_details']): ?>display:none;<?php endif; ?>">
    <h1>Department: <?php echo $this->_tpl_vars['department']->get('Product Department Name'); ?>
 (<?php echo $this->_tpl_vars['department']->get('Product Department Code'); ?>
)</h1>
  </div> 

<div id="info" style="margin:10px 0;padding:0;<?php if (! $this->_tpl_vars['show_details']): ?>display:none;<?php endif; ?>">

<h2 style="margin:0;padding:0"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Department Information<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</h2>
<div style="width:350px;float:left">
  <table  class="show_info_product">

    <tr >
      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Code<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="price"><?php echo $this->_tpl_vars['department']->get('Product Department Code'); ?>
</td>
    </tr>
    <tr >
      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Name<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['department']->get('Product Department Name'); ?>
</td>
    </tr>
   </table>
    <table    class="show_info_product">
    <tr>
	    <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Families<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="number"><div><?php echo $this->_tpl_vars['department']->get('Families'); ?>
</div></td>
	  </tr>
	  <tr>
	    <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Products<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="number"><div><?php echo $this->_tpl_vars['department']->get('For Sale Products'); ?>
</div></td>
	  </tr>
 
     <tr >
      <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Web Page<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><?php echo $this->_tpl_vars['department']->get('Web Page Links'); ?>
</td>
    </tr>

  </table>
</div>
<div style="width:15em;float:left;margin-left:20px">

<table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title"><?php echo $this->_tpl_vars['store_period_title']; ?>
</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="<?php if ($this->_tpl_vars['store_period'] != 'all'): ?>display:none<?php endif; ?>">
	 <tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('Total Customers'); ?>
</td>
	</tr>
	 	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Invoices<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('Total Invoices'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('Total Invoiced Amount'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('Total Profit'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Outers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('Total Quantity Delivered'); ?>
</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="<?php if ($this->_tpl_vars['store_period'] != 'year'): ?>display:none<?php endif; ?>">
      	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Year Acc Customers'); ?>
</td>
	</tr>
		<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Invoices<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Year Acc Invoices'); ?>
</td>
	</tr>

	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Year Acc Invoiced Amount'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Year Acc Profit'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Outers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Year Acc Quantity Delivered'); ?>
</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="<?php if ($this->_tpl_vars['store_period'] != 'quarter'): ?>display:none<?php endif; ?>"  >
        <tr >
	     <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Orders<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Quarter Acc Invoices'); ?>
</td>
	    </tr>
        <tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Quarter Acc Customers'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Quarter Acc Invoiced Amount'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Quarter Acc Profit'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Outers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Quarter Acc Quantity Delivered'); ?>
</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="<?php if ($this->_tpl_vars['store_period'] != 'month'): ?>display:none<?php endif; ?>"  >
        <tr >
	     <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Orders<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Month Acc Invoices'); ?>
</td>
	    </tr>
        <tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Month Acc Customers'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Month Acc Invoiced Amount'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Month Acc Profit'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Outers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Month Acc Quantity Delivered'); ?>
</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="<?php if ($this->_tpl_vars['store_period'] != 'week'): ?>display:none<?php endif; ?>"  >
        <tr >
	     <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Orders<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Week Acc Invoices'); ?>
</td>
	    </tr>
        <tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Customers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Week Acc Customers'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Week Acc Invoiced Amount'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class=" aright"><?php echo $this->_tpl_vars['department']->get('1 Week Acc Profit'); ?>
</td>
	</tr>
	<tr >
	  <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Outers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td class="aright"><?php echo $this->_tpl_vars['department']->get('1 Week Acc Quantity Delivered'); ?>
</td>
	</tr>	
      </tbody>
 </table>
</div>

</div>


<div id="plot" class="top_bar" style="position:relative;left:-20px;clear:both;padding:0;margin:0;<?php if (! $this->_tpl_vars['show_details']): ?>display:none;<?php endif; ?>">

      
      
      
      <div display="none" id="plot_info"   style="border:none;padding:0;margin0;"    keys="<?php echo $this->_tpl_vars['department']->id; ?>
" ></div>
      <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	<li>
	  <span class="item <?php if ($this->_tpl_vars['plot_tipo'] == 'department'): ?>selected<?php endif; ?>" onClick="change_plot(this)" id="plot_department" tipo="department" category="<?php echo $this->_tpl_vars['plot_data']['department']['category']; ?>
" period="<?php echo $this->_tpl_vars['plot_data']['department']['period']; ?>
" >
	    <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Department Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	  </span>
	</li>
	<li>
	  <span class="item <?php if ($this->_tpl_vars['plot_tipo'] == 'top_families'): ?>selected<?php endif; ?>"  id="plot_top_families" onClick="change_plot(this)" tipo="top_families" category="<?php echo $this->_tpl_vars['plot_data']['top_families']['category']; ?>
" period="<?php echo $this->_tpl_vars['plot_data']['top_families']['period']; ?>
" name=""  >
	    <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Top Families<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	  </span>
	</li>
	<li>
	  <span class="item <?php if ($this->_tpl_vars['plot_tipo'] == 'pie'): ?>selected<?php endif; ?>" onClick="change_plot(this)" id="plot_pie" tipo="pie"   category="<?php echo $this->_tpl_vars['plot_data']['pie']['category']; ?>
" period="<?php echo $this->_tpl_vars['plot_data']['pie']['period']; ?>
" forecast="<?php echo $this->_tpl_vars['plot_data']['pie']['forecast']; ?>
" date="<?php echo $this->_tpl_vars['plot_data']['pie']['date']; ?>
"  >
	    <span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Family Pie<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	  </span>
	</li>
      </ul> 
       <ul id="plot_options" class="tabs" style="<?php if ($this->_tpl_vars['plot_tipo'] == 'pie'): ?>display:none<?php endif; ?>;position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	<li><span class="item"> <span id="plot_category" category="<?php echo $this->_tpl_vars['plot_category']; ?>
" style="xborder:1px solid black;display:inline-block; vertical-align:middle"><?php echo $this->_tpl_vars['plot_formated_category']; ?>
</span></span></li>
	<li><span class="item"> <span id="plot_period"   period="<?php echo $this->_tpl_vars['plot_period']; ?>
" style="xborder:1px solid black;display:inline-block; vertical-align:middle"><?php echo $this->_tpl_vars['plot_formated_period']; ?>
</span></span></li>
      </ul> 


      <div style="clear:both;margin:0 20px;padding:0 20px ;border-bottom:1px solid #999">
      </div>

      <div id="pie_options"  style="<?php if ($this->_tpl_vars['plot_tipo'] != 'pie'): ?>display:none;<?php endif; ?>border:1px solid #ddd;float:right;margin:20px 0px;margin-right:40px;width:300px;padding:10px">
	<table id="pie_category_options" style="float:none;margin-bottom:10px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  <?php if ($this->_tpl_vars['plot_data']['pie']['category'] == 'sales'): ?>class="selected"<?php endif; ?> period="sales"  id="pie_category_sales" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	    <td <?php if ($this->_tpl_vars['plot_data']['pie']['category'] == 'profit'): ?>class="selected"<?php endif; ?>  period="profit"  id="pie_category_profit"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	  </tr>
	</table>
	<table id="pie_period_options" style="float:none;margin-bottom:20px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  <?php if ($this->_tpl_vars['plot_data']['pie']['period'] == 'all'): ?>class="selected"<?php endif; ?> period="all"  id="pie_period_all" onclick="change_plot_period('all')" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>All<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	    <td <?php if ($this->_tpl_vars['plot_data']['pie']['period'] == 'y'): ?>class="selected"<?php endif; ?>  period="year"  id="pie_period_year" onclick="change_plot_period('y')"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Year<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	    <td  <?php if ($this->_tpl_vars['plot_data']['pie']['period'] == 'q'): ?>class="selected"<?php endif; ?>  period="quarter"  id="pie_period_quarter" onclick="change_plot_period('q')"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Quarter<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	    <td <?php if ($this->_tpl_vars['plot_data']['pie']['period'] == 'm'): ?>class="selected"<?php endif; ?>  period="month"  id="pie_period_month" onclick="change_plot_period('m')"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Month<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	    <td  <?php if ($this->_tpl_vars['plot_data']['pie']['period'] == 'w'): ?>class="selected"<?php endif; ?> period="week"  id="pie_period_week" onclick="change_plot_period('w')"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Week<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	  </tr>
	</table>
		<div style="font-size:90%;margin-left:30px">
	  <span><?php echo $this->_tpl_vars['plot_formated_period']; ?>
</span>: <input class="text" type="text" value="<?php echo $this->_tpl_vars['plot_formated_date']; ?>
" style="width:6em"/> <img style="display:none" src="art/icons/chart_pie.png" alt="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>update<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/>
	</div>
      </div>

	<div  id="plot_div" class="product_plot"  style="width:885px;xheight:325px;">
	  <iframe id="the_plot" src ="<?php echo $this->_tpl_vars['plot_page']; ?>
?<?php echo $this->_tpl_vars['plot_args']; ?>
" frameborder=0 height="325" scrolling="no" width="<?php if ($this->_tpl_vars['plot_tipo'] == 'pie'): ?>500px<?php else: ?>100%<?php endif; ?>"></iframe>
	</div>
	

     
     </div>
   
 
 
  
<div class="data_table" style="clear:both;">
  <span id="table_title" class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Families<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
  
    <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'list'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>List<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'thumbnails'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Thumbnails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     </div>
     
     
  
  
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  
      <div id="list_options0"> 

  
  <span   style="float:right;margin-left:40px" class="state_details" state="<?php echo $this->_tpl_vars['show_percentages']; ?>
"  id="show_percentages"  atitle="<?php if ($this->_tpl_vars['show_percentages']): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Normal Mode<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Comparison Mode<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?>"  ><?php if ($this->_tpl_vars['show_percentages']): ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Comparison Mode<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Normal Mode<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?></span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_for_sale"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>For Sale<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<?php echo $this->_tpl_vars['department']->get('For Public For Sale Families'); ?>
)</span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_discontinued"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Discontinued<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<?php echo $this->_tpl_vars['department']->get('For Public Discontinued Families'); ?>
)</span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_all"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>All<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<?php echo $this->_tpl_vars['department']->get('Families'); ?>
)</span>


  <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
    <tr><td  <?php if ($this->_tpl_vars['view'] == 'general'): ?>class="selected"<?php endif; ?> id="general" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>General<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <?php if ($this->_tpl_vars['view_stock']): ?><td <?php if ($this->_tpl_vars['view'] == 'stock'): ?>class="selected"<?php endif; ?>  id="stock"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Stock<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><?php endif; ?>
      <?php if ($this->_tpl_vars['view_sales']): ?><td  <?php if ($this->_tpl_vars['view'] == 'sales'): ?>class="selected"<?php endif; ?>  id="sales"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Sales<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td><?php endif; ?>
    </tr>
  </table>
  
  <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0<?php if ($this->_tpl_vars['view'] != 'sales'): ?>;display:none<?php endif; ?>"  class="options_mini" >
    <tr>
      
      <td  <?php if ($this->_tpl_vars['period'] == 'all'): ?>class="selected"<?php endif; ?> period="all"  id="period_all" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>All<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> </td>
      <td <?php if ($this->_tpl_vars['period'] == 'year'): ?>class="selected"<?php endif; ?>  period="year"  id="period_year"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>1Yr<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <td  <?php if ($this->_tpl_vars['period'] == 'quarter'): ?>class="selected"<?php endif; ?>  period="quarter"  id="period_quarter"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>1Qtr<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <td <?php if ($this->_tpl_vars['period'] == 'month'): ?>class="selected"<?php endif; ?>  period="month"  id="period_month"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>1M<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <td  <?php if ($this->_tpl_vars['period'] == 'week'): ?>class="selected"<?php endif; ?> period="week"  id="period_week"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>1W<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
    </tr>
  </table>
	
  <table  id="avg_options" style="float:left;margin:0 0 0 25px ;padding:0 <?php if ($this->_tpl_vars['view'] != 'sales'): ?>;display:none<?php endif; ?>"  class="options_mini" >
    <tr>
      <td <?php if ($this->_tpl_vars['avg'] == 'totals'): ?>class="selected"<?php endif; ?> avg="totals"  id="avg_totals" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Totals<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <td <?php if ($this->_tpl_vars['avg'] == 'month'): ?>class="selected"<?php endif; ?>  avg="month"  id="avg_month"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>M AVG<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      <td <?php if ($this->_tpl_vars['avg'] == 'week'): ?>class="selected"<?php endif; ?>  avg="week"  id="avg_week"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>W AVG<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
      
    </tr>
  </table>
  </div>
  <div  class="clean_table_caption"  style="clear:both;">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 0,'filter_name' => $this->_tpl_vars['filter_name0'],'filter_value' => $this->_tpl_vars['filter_value0'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
  

      <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;<?php if ($this->_tpl_vars['table_type'] != 'thumbnails'): ?>display:none<?php endif; ?>"></div>

  <div  id="table0"  style="<?php if ($this->_tpl_vars['table_type'] == 'thumbnails'): ?>display:none<?php endif; ?>"  class="data_table_container dtable btable with_total"> </div>
</div>

</div> 

<div id="plot_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Plot frequency<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</li>
      <?php $_from = $this->_tpl_vars['plot_period_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_period('<?php echo $this->_tpl_vars['menu']['period']; ?>
')"> <?php echo $this->_tpl_vars['menu']['label']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
</div>

<div id="plot_category_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Plot Type<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</li>
      <?php $_from = $this->_tpl_vars['plot_category_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_category('<?php echo $this->_tpl_vars['menu']['category']; ?>
')"> <?php echo $this->_tpl_vars['menu']['label']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
</div>

<div id="info_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Period<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</li>
      <?php $_from = $this->_tpl_vars['info_period_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_info_period('<?php echo $this->_tpl_vars['menu']['period']; ?>
','<?php echo $this->_tpl_vars['menu']['title']; ?>
')"> <?php echo $this->_tpl_vars['menu']['label']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals(<?php echo $this->_tpl_vars['menu']; ?>
,0)"> <?php echo $this->_tpl_vars['menu']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
