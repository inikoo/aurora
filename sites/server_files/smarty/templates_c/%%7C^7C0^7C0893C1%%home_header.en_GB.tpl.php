<?php /* Smarty version 2.6.22, created on 2010-10-20 14:29:24
         compiled from templates/home_header.en_GB.tpl */ ?>
<div id="header" >
  <div id="header_home" >
    <div>
      <span id="second_slogan"><?php echo $this->_tpl_vars['store_slogan']; ?>
</span>
      
    </div>
  </div>
  <div id="header_title" >

    <div id="header_login" >
<?php if ($this->_tpl_vars['logged_in']): ?>
      <?php echo $this->_tpl_vars['traslated_labels']['hello']; ?>
 <?php echo $this->_tpl_vars['user']->get('User Alias'); ?>
<a href="logout.php" style="margin-left:20px"><?php echo $this->_tpl_vars['traslated_labels']['logout']; ?>
</a>  
<?php else: ?>
   <a href="register.php"><?php echo $this->_tpl_vars['traslated_labels']['register']; ?>
</a>
      <a href="login.php" style="margin-left:20px"><?php echo $this->_tpl_vars['traslated_labels']['login']; ?>
</a>
<?php endif; ?>
    </div>
 


   <div id="header_info" >
      <h1><?php echo $this->_tpl_vars['header_title']; ?>
</h1>
      <h2><?php echo $this->_tpl_vars['header_subtitle']; ?>
</h2>
      <div id="header_commentary" style="font-size:80%">
	<?php echo $this->_tpl_vars['comentary']; ?>

      </div>  
      
    </div>
    <a href="index.php" alt="home"><img src="art/logo.png"/></a><br/>
    <div  id="slogan"><?php echo $this->_tpl_vars['slogan']; ?>
</div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['main_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
</div>