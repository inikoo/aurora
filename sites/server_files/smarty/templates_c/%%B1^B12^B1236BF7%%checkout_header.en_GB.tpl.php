<?php /* Smarty version 2.6.22, created on 2010-11-16 10:08:38
         compiled from templates/checkout_header.en_GB.tpl */ ?>
<div id="header" >
  <div id="header_family" >
 
  
    <a href="index.php" alt="home"><img src="<?php echo $this->_tpl_vars['store_code']; ?>
/art/logo.png"/></a>
    <div>
      <span id="category"><?php echo $this->_tpl_vars['department_slogan']; ?>
</span><br/>
      <span id="slogan"><?php echo $this->_tpl_vars['store_slogan']; ?>
</span>
    </div>
  </div>
  <div id="header_title" >
  
    <?php if ($this->_tpl_vars['logged_in']): ?>
<div style="" id="top_menu">
     <div style="display:none"><?php echo $this->_tpl_vars['traslated_labels']['hello']; ?>
 <?php echo $this->_tpl_vars['user']->get('User Alias'); ?>
</div>
          
          <a href="myaccount.php" style="margin-left:20px"><?php echo $this->_tpl_vars['traslated_labels']['myaccount']; ?>
</a>  
          <a href="orders.php" style="margin-left:20px"><?php echo $this->_tpl_vars['traslated_labels']['orders']; ?>
</a>  
          <a href="logout.php" style="margin-left:20px;left-right:10px"><?php echo $this->_tpl_vars['traslated_labels']['logout']; ?>
</a>  

</div>
<?php endif; ?>
  
    <h1><?php echo $this->_tpl_vars['header_title']; ?>
</h1>
    <h2><?php echo $this->_tpl_vars['header_subtitle']; ?>
</h2>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['main_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <div id="header_info" >
 
  </div>
  <div style="clear:both"></div>
</div> 
  