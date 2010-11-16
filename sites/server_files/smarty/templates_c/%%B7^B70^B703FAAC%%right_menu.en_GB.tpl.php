<?php /* Smarty version 2.6.22, created on 2010-10-16 14:55:36
         compiled from templates/right_menu.en_GB.tpl */ ?>
<div id="right_menu">
<?php if ($this->_tpl_vars['logged_in']): ?>

 <div id="login_from_left_menu" style="margin-top:10px">
    <h3 style="border-bottom:1px solid #aaa"><?php echo $this->_tpl_vars['traslated_labels']['basket']; ?>
:</h3>
    <table class="mini_basket">
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['items']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_items']; ?>
</td></tr>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['shipping']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_shipping']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['order']['charges'] != 0): ?>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['charges']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_charges']; ?>
</td></tr>
      <?php endif; ?>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['discounts']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_discounts']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['order']['tax'] == 0): ?>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['total']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_total']; ?>
</td></tr>
      <?php else: ?>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['total_net']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_total_net']; ?>
</td></tr>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['tax']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_tax']; ?>
</td></tr>
      <tr><td class="label"><?php echo $this->_tpl_vars['traslated_labels']['total']; ?>
</td><td><?php echo $this->_tpl_vars['order']['amount_total']; ?>
</td></tr>
      <?php endif; ?>
    



    </table>    


    <button id="sing_in" style="float:right;margin-right:5px;font-size:95%"><?php echo $this->_tpl_vars['traslated_labels']['checkout']; ?>
</button>

  </div>

  <?php else: ?>
  <div id="login_from_left_menu" style="margin-top:10px">
    <h3 style="border-bottom:1px solid #aaa"><?php echo $this->_tpl_vars['traslated_labels']['access']; ?>
:</h3>

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
  <div id="register">
    <p><?php echo $this->_tpl_vars['traslated_labels']['new_customer']; ?>
</p>
    <h3 style="text-align:center;border:1px solid #ccc;padding:2px 3px;margin-bottom:5px;background:#990000">
      <a href="register.php" ><?php echo $this->_tpl_vars['traslated_labels']['register_here']; ?>
</a>
    </h3>
    <?php echo $this->_tpl_vars['traslated_labels']['public_msg']; ?>

  </div>
<?php endif; ?>
</div>