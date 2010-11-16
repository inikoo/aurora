<?php /* Smarty version 2.6.22, created on 2010-10-19 11:26:36
         compiled from ../templates/department.en_GB.tpl */ ?>
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
    <div id="page_content">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['left_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div id="central_content" style="width:655px;">
	<div id="search" >
	  <?php echo $this->_tpl_vars['traslated_labels']['search']; ?>
: <input type="text"/>
	</div>
        
	<div class="block" id="product_block_layout">
	    <?php $_from = $this->_tpl_vars['families']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['family']):
?>
	    <div style="width:100px;height:120px;float:left;margin:10px;margin-bottom:15px">
	      <div style="width:105px;height:105px;border:1px solid #ccc;cursor:pointer" onclick="location.href='family.php?code=<?php echo $this->_tpl_vars['family']['code']; ?>
'" >
		<span style="background-image:url('art/background_fam_code.png') ;color:#fff;padding:2px 5px;position:relative;bottom:4px;left:-5px;font-size:80%"  ><a style="color:#fff"  href="family.php?code=<?php echo $this->_tpl_vars['family']['code']; ?>
" ><?php echo $this->_tpl_vars['family']['code']; ?>
</a></span>
		
	      </div>
	      <div style="text-align:center;font-size:10px"><?php echo $this->_tpl_vars['family']['name']; ?>
</div>
	    </div>
	  <?php endforeach; endif; unset($_from); ?>
	</div>
	
	<div class="block" id="product_list_layout" style="display:none">
	  <table class="families">
	    <?php $_from = $this->_tpl_vars['families']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['family']):
?>
	    <tr><td><a href="family.php?code=<?php echo $this->_tpl_vars['family']['code']; ?>
"><?php echo $this->_tpl_vars['family']['code']; ?>
</a></td><td><a href="family.php?id=<?php echo $this->_tpl_vars['family']['code']; ?>
"   ><?php echo $this->_tpl_vars['family']['name']; ?>
</a></td></tr>
	    <?php endforeach; endif; unset($_from); ?>
	  </table>
	</div>
      </div>
      <div style="clear:both"></div>
    </div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>