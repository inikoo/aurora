<?php /* Smarty version 2.6.22, created on 2011-10-10 15:18:49
         compiled from table_splinter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'table_splinter.tpl', 6, false),)), $this); ?>
 <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;">
	  <div id="table_info<?php echo $this->_tpl_vars['table_id']; ?>
" class="clean_table_info"><span id="rtext<?php echo $this->_tpl_vars['table_id']; ?>
"></span> <span class="rtext_rpp" id="rtext_rpp<?php echo $this->_tpl_vars['table_id']; ?>
"></span> <span class="filter_msg"  id="filter_msg<?php echo $this->_tpl_vars['table_id']; ?>
"></span></div>
	</div>
	<div style="<?php if ($this->_tpl_vars['no_filter'] == 1): ?>display:none<?php endif; ?>">
	<div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show<?php echo $this->_tpl_vars['table_id']; ?>
" <?php if ($this->_tpl_vars['filter_show'] || $this->_tpl_vars['filter_value'] != ''): ?>style="display:none"<?php endif; ?>><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>filter results<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
	<div class="clean_table_filter" id="clean_table_filter<?php echo $this->_tpl_vars['table_id']; ?>
" <?php if (! $this->_tpl_vars['filter_show'] && $this->_tpl_vars['filter_value'] == ''): ?>style="display:none"<?php endif; ?>>
	  <div class="clean_table_info" style="padding-bottom:1px; ">
	    <span id="filter_name<?php echo $this->_tpl_vars['table_id']; ?>
" class="filter_name"  style="margin-right:5px"><?php echo $this->_tpl_vars['filter_name']; ?>
:</span>
	    <input style="border-bottom:none;width:6em;" id='f_input<?php echo $this->_tpl_vars['table_id']; ?>
' value="<?php echo $this->_tpl_vars['filter_value']; ?>
" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide<?php echo $this->_tpl_vars['table_id']; ?>
" style="margin-left:8px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Close filter<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
	    <div id='f_container<?php echo $this->_tpl_vars['table_id']; ?>
'></div>
	  </div>
	</div>	
	</div>
	<div class="clean_table_controls" style="" >
	    <div><span  style="margin:0 5px" id="paginator<?php echo $this->_tpl_vars['table_id']; ?>
"></span></div>
	 </div>
</div>