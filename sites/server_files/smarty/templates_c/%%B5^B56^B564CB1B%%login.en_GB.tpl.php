<?php /* Smarty version 2.6.22, created on 2010-10-22 10:01:30
         compiled from ../templates/login.en_GB.tpl */ ?>
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
     <div id="central_content" style="min-height:600px">
       <div id="no_logged_error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;<?php if (! $this->_tpl_vars['login']): ?>display:none<?php endif; ?>" > 
	     <p>You are already login.</p>
       </div>
       <?php if (! $this->_tpl_vars['login']): ?>
       <div  style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <div style="width:250px">
	 <input type="hidden" value="<?php echo $this->_tpl_vars['secret_string']; ?>
" id="ep">


      <?php echo $this->_tpl_vars['traslated_labels']['email']; ?>
:
      <input id="login_handle" type="text" value="" style="width:95%"/>
      <?php echo $this->_tpl_vars['traslated_labels']['password']; ?>
:
      <input  id="login_password"  type="password"  value=""  style="width:95%"/>
      <button id="sing_in" onclick="login()" style="float:right;margin-right:5px;font-size:95%"><?php echo $this->_tpl_vars['traslated_labels']['login']; ?>
</button>
      
      <div style="margin-top:36px;display:none" id="invalid_credentials"><?php echo $this->_tpl_vars['traslated_labels']['invalid_credentials']; ?>
</div>

      
      <div style="margin-top:36px"><a href="lost_password.php"><?php echo $this->_tpl_vars['traslated_labels']['forgot_password']; ?>
</a></div>
      
	 </div>
	 
	
	 </div>
       <?php endif; ?>
       </div>
     
     <div style="clear:both"></div>

      </div>
     
   
     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     
   </div>
 </body>