<?php /* Smarty version 2.6.22, created on 2010-11-15 22:51:08
         compiled from ../templates/family.en_GB.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', '../templates/family.en_GB.tpl', 55, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['head_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body class="yui-skin-sam kaktus">
  <div id="container" >
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['header_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      
      
     
	<div id="top_content" >
	  <div id="found_in">
	   
	   <a href="department.php?code=<?php echo $this->_tpl_vars['family']->get('Product Family Main Department Code'); ?>
"><?php echo $this->_tpl_vars['family']->get('Product Family Main Department Name'); ?>
</a>
	   
	    
	  </div>
	  <div   id="search" style="float:left" >
	  <?php echo $this->_tpl_vars['traslated_labels']['search']; ?>
: <input type="text"/>
	  </div>
	 
	  <div style="clear:both"></div>
	</div>
	
	<?php if (! $this->_tpl_vars['logged_in']): ?>
	<div id="register_banner" style="font-size:12px">
	  <div style="text-align:center;font-size:10px;float:right;width:180px;height:120px;margin-right:60px;margin-top:40px">
	    Registered customers login here.
	    <table>
	    <tr><td style="text-align:left">Email:</td></tr>
	    <tr><td><input style="border:1px solid #fff;width:100%" value=""></td></tr>

	    <tr><td style="text-align:left">Password:</td></tr>
	    <tr><td><input style="border:1px solid #fff;width:100%" value=""></td></tr>
	    <tr><td style="text-align:right"><button>Submit</button></td></tr>
	    </table>
	    Forgot you password?
	  </div>

	  <div style="position:relative;left:40px;width:400px;top:30px">
	    <h1>We supply wholesale to the gift trade</h1>
	    <p>To see product prices <b><a href="register.php">please register first</a></b>, it's easy and should not take you more than 5 minutes.</p>
	    <p>Keep in mind that this is a <b>trade only</b> web site. You should intent to resell the items purchased from us.</p>
	    <button>Register</button>
	  </div>


	</div>	 
	<?php endif; ?>
	<?php if (file_exists ( "splinters/presentation/".($this->_tpl_vars['page_key']).".tpl" )): ?>
	<div style="font-size:10px;;margin-top:10px;padding:10px">
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "splinters/presentation/".($this->_tpl_vars['page_key']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <div style="clear:both"></div>
	</div>	 
	<?php endif; ?>
	<?php if ($this->_tpl_vars['logged_in']): ?>
        <div class="data_table"  style="clear:both">
     <span id="table_title" class="clean_table_title"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Products<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

     <div id="table_type">
          <span id="table_type_slideshow" style="float:right;<?php if (! $this->_tpl_vars['can_view_slideshow']): ?>display:none;<?php endif; ?>" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'slideshow'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Slideshow<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     <span id="table_type_list" style="float:right;margin-right:10px;<?php if (! $this->_tpl_vars['can_view_list']): ?>display:none;<?php endif; ?>" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'list'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>List<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     <span id="table_type_thumbnails" style="float:right;margin-right:10px;<?php if (! $this->_tpl_vars['can_view_thumbnails']): ?>display:none;<?php endif; ?>" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'thumbnails'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Thumbnails<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
     <span id="table_type_manual" style="float:right;margin-right:10px;<?php if (! $this->_tpl_vars['can_view_manual']): ?>display:none;<?php endif; ?>" class="table_type state_details <?php if ($this->_tpl_vars['table_type'] == 'manual'): ?>selected<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>e-Showroom<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

     </div>
     
     
<div id="list_options0"></div>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'templates/table_splinter.tpl', 'smarty_include_vars' => array('table_id' => 0,'filter_name' => $this->_tpl_vars['filter_name0'],'filter_value' => $this->_tpl_vars['filter_value0'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;<?php if ($this->_tpl_vars['table_type'] != 'thumbnails'): ?>display:none<?php endif; ?>"></div>
    <div id="table0"   class="data_table_container dtable btable "  style="<?php if ($this->_tpl_vars['table_type'] != 'list'): ?>display:none<?php endif; ?>"   > </div>
    <div id="manual0" class="manual" style="border-top:1px solid SteelBlue;clear:both;<?php if ($this->_tpl_vars['table_type'] != 'manual'): ?>display:none<?php endif; ?>"></div>
    <div id="slideshow0" class="slideshow" style="border-top:1px solid SteelBlue;clear:both;<?php if ($this->_tpl_vars['table_type'] != 'slideshow'): ?>display:none<?php endif; ?>"></div>
    <div id="none0" class="none" style="border-top:1px solid SteelBlue;clear:both;<?php if ($this->_tpl_vars['table_type'] != 'none'): ?>display:none<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Products not availeables<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

  
  
  
</div>
	<?php else: ?>
	<div>
	  
	  <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
	  <?php if ($this->_tpl_vars['product']['image'] != 'art/nopic.png'): ?>
	  <div style="float:left;margin-top:15px">
	    <img src="<?php echo $this->_tpl_vars['product']['image']; ?>
" alt="<?php echo $this->_tpl_vars['product']['code']; ?>
" height="200" style="margin:3px;5px;margin-bottom:7px" />
	    <div style="font-size:10px;text-align:center;border:1px solid #ccc;width:110px;padding:5px;margin:8px;margin-left:20px">
	    <span><b><?php echo $this->_tpl_vars['product']['code']; ?>
</b></span><br>
	    <span><?php echo $this->_tpl_vars['product']['name']; ?>
</span>

	    </div>
	  </div>
	  <?php endif; ?>
	  <?php endforeach; endif; unset($_from); ?>
	</div>
	
	<?php endif; ?>
 
    
	<div style="clear:both;height:20px"></div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </body>