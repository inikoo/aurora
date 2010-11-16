<?php /* Smarty version 2.6.22, created on 2010-10-21 04:40:06
         compiled from templates/main_menu.tpl */ ?>
<ul class="menu" id="menu">
<li><a href="index.php" class="menulink"><?php echo $this->_tpl_vars['traslated_labels']['home']; ?>
</a></li>
	<li><a href="#" class="menulink"><?php echo $this->_tpl_vars['traslated_labels']['info']; ?>
</a>
	<ul>
		  <?php $_from = $this->_tpl_vars['info_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
		  <li><a href="<?php echo $this->_tpl_vars['page']['url']; ?>
"  class="underline" ><?php echo $this->_tpl_vars['page']['short_title']; ?>
</a></li>
		  <?php endforeach; endif; unset($_from); ?>
		</ul>
	
	</li>
	<li>
		<a href="#" class="menulink"><?php echo $this->_tpl_vars['traslated_labels']['catalogues']; ?>
</a>
	
	<ul>
		  <?php $_from = $this->_tpl_vars['departments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['department']):
?>
		  <li><a href="department.php?code=<?php echo $this->_tpl_vars['department']['code']; ?>
"  class="underline" ><?php echo $this->_tpl_vars['department']['name']; ?>
</a></li>
		  <?php endforeach; endif; unset($_from); ?>
		</ul>
	
	
	</li>
	<li>
		<a href="#" class="menulink"><?php echo $this->_tpl_vars['traslated_labels']['incentives']; ?>
</a>
		<ul>
		  <?php $_from = $this->_tpl_vars['incentive_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
		  <li><a href="<?php echo $this->_tpl_vars['page']['url']; ?>
"  class="underline" ><?php echo $this->_tpl_vars['page']['short_title']; ?>
</a></li>
		  <?php endforeach; endif; unset($_from); ?>
		</ul>
		
		
	</li>
	<li>
		<a href="#" class="menulink"><?php echo $this->_tpl_vars['traslated_labels']['inspiration']; ?>
</a>
		<ul>
		  <?php $_from = $this->_tpl_vars['inspiration_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
		  <li><a href="<?php echo $this->_tpl_vars['page']['url']; ?>
"  class="underline" ><?php echo $this->_tpl_vars['page']['short_title']; ?>
</a></li>
		  <?php endforeach; endif; unset($_from); ?>
		  
		</ul>
	</li>
</ul>












	
<script type="text/javascript">
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>