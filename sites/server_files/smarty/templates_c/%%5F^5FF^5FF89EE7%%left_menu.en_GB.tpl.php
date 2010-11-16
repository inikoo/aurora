<?php /* Smarty version 2.6.22, created on 2010-10-16 14:55:36
         compiled from templates/left_menu.en_GB.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mailto', 'templates/left_menu.en_GB.tpl', 6, false),)), $this); ?>
<div id="left_menu">
  <div class="contacts">
    <h3><a href="info.php?page=contact"><?php echo $this->_tpl_vars['traslated_labels']['contact']; ?>
</a></h3>
    <?php if ($this->_tpl_vars['tel']): ?><span>T <?php echo $this->_tpl_vars['tel']; ?>
</span><br/><?php endif; ?>
    <?php if ($this->_tpl_vars['fax']): ?><span>F <?php echo $this->_tpl_vars['fax']; ?>
</span><br/><?php endif; ?>
    <?php if ($this->_tpl_vars['email']): ?><?php echo smarty_function_mailto(array('address' => ($this->_tpl_vars['email']),'encode' => 'javascript'), $this);?>
<?php endif; ?>

  </div>
  <div class="catalogue">
    <h3><a href="catalogue.php"><?php echo $this->_tpl_vars['traslated_labels']['catalogue']; ?>
</a></h3>
    <ul>
      <?php $_from = $this->_tpl_vars['departments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['department']):
?>
      <li><a href="department.php?code=<?php echo $this->_tpl_vars['department']['code']; ?>
"><?php echo $this->_tpl_vars['department']['name']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
  </div>
</div>