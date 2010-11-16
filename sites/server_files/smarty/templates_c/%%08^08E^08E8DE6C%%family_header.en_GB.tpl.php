<?php /* Smarty version 2.6.22, created on 2010-10-19 11:34:37
         compiled from ../templates/family_header.en_GB.tpl */ ?>
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
  <?php if ($this->_tpl_vars['logged_in']): ?>


 <div id="login_from_left_menu" style="margin-top:0px">
    <table class="mini_basket" >
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['items']; ?>
</td><td id="basket_items"><?php echo $this->_tpl_vars['order']['amount_items']; ?>
</td><td id="basket_discounts" style="width:52px"><?php if ($this->_tpl_vars['order']['discounts'] != 0): ?>(-<?php echo $this->_tpl_vars['order']['amount_discounts']; ?>
)<?php endif; ?></td></tr>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['shipping_and_handing']; ?>
</td><td id="basket_shipping_and_handing"><?php echo $this->_tpl_vars['order']['amount_shipping_and_handing']; ?>
</td></tr>
        <tr id="tr_basket_net" <?php if ($this->_tpl_vars['order']['tax'] == 0): ?>style="display:none"<?php endif; ?> ><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['total_net']; ?>
</td><td id="basket_net" ><?php echo $this->_tpl_vars['order']['amount_total_net']; ?>
</td></tr>
      <tr id="tr_basket_tax" <?php if ($this->_tpl_vars['order']['tax'] == 0): ?>style="display:none"<?php endif; ?> ><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['tax']; ?>
</td><td id="basket_tax" ><?php echo $this->_tpl_vars['order']['amount_tax']; ?>
</td></tr>
          <tr><td class="label" ><?php echo $this->_tpl_vars['traslated_labels']['total']; ?>
</td><td id="basket_total" ><?php echo $this->_tpl_vars['order']['amount_total']; ?>
</td></tr>
<tr class="checkout"><td></td><td ><button id="checkout" style="font-size:95%;position:relative;left:5px"><?php echo $this->_tpl_vars['traslated_labels']['checkout']; ?>
</button></td></tr>



    </table>    



  </div>

  <?php else: ?>
  
  
   <div id="header_login" >
      <a href="register.php"><?php echo $this->_tpl_vars['traslated_labels']['register']; ?>
</a>
      <a href="login.php" style="margin-left:20px"><?php echo $this->_tpl_vars['traslated_labels']['login']; ?>
</a>
    </div>
    <div style="text-align:center;border:1px solid #ccc;padding:0 5px;margin-top:5px; margin-left:10px">
      <?php echo $this->_tpl_vars['traslated_labels']['public_msg']; ?>

    </div>
    <?php endif; ?>
  </div>
  <div style="clear:both"></div>
</div> 
  