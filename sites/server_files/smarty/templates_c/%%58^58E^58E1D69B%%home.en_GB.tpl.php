<?php /* Smarty version 2.6.22, created on 2010-10-16 14:55:36
         compiled from templates/home.en_GB.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['head_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <body>
   <div id="container" >
     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['home_header_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     <div id="page_content" >
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['left_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     
       <div id="central_content">

	
	
	 <div id="search_container" >
    <span class="search_title" ><?php echo $this->_tpl_vars['traslated_labels']['search']; ?>
:</span>
    <input size="25" class="text search" id="search" store_key="<?php echo $this->_tpl_vars['store_key']; ?>
"  value="" state="" name="search"/><img align="absbottom" id="clean_search"  class="submitsearch" src="art/icons/zoom.png" >
    <div id="search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;xleft:-520px">
	<table id="search_results_table">
	
	</table>
      </div>
    </div>
  </div>
	
	
	
	
	<div >
	   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['main_showcase']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	 </div>
	 <div id="banner_top" class="banner"  >
	   <a href="<?php echo $this->_tpl_vars['banners']['top']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['banners']['top']['src']; ?>
"/></a>
	 </div>
	 <?php if ($this->_tpl_vars['second_showcase']): ?>	 
	 <div id="second_showcase" >
	   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['second_showcase']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	 </div>
	 <?php endif; ?>
	 <div id="banner_bottom" class="banner" >
	   <a href="<?php echo $this->_tpl_vars['banners']['bottom']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['banners']['bottom']['src']; ?>
"/></a>
	 </div>
       </div>
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['right_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div style="clear:both"></div>
     </div>
     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </body>