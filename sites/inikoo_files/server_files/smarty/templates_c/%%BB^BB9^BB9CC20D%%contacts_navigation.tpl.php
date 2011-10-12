<?php /* Smarty version 2.6.22, created on 2011-10-11 16:23:26
         compiled from contacts_navigation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'contacts_navigation.tpl', 2, false),)), $this); ?>
<input type='hidden' id="store_id" value="<?php echo $this->_tpl_vars['store_id']; ?>
">
<span id="search_no_results" style="display:none"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No results found, try te a more comprensive search<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <a style="font-weight:800" href="search_customers.php<?php if ($this->_tpl_vars['store_id']): ?>?store=<?php echo $this->_tpl_vars['store_id']; ?>
<?php endif; ?>"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>here<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>.</span>

<span class="nav2 onright" style="padding:0px"><?php if ($this->_tpl_vars['next']['id'] > 0): ?><a class="next" href="customer.php?<?php echo $this->_tpl_vars['parent_info']; ?>
id=<?php echo $this->_tpl_vars['next']['id']; ?>
" ><img src="art/icons/next_white.png" style="padding:0px 10px" alt=">" title="<?php echo $this->_tpl_vars['next']['name']; ?>
"  /></a><?php endif; ?></span>
<?php if ($this->_tpl_vars['parent_url']): ?><span class="nav2 onright"><a   href="<?php echo $this->_tpl_vars['parent_url']; ?>
"><?php echo $this->_tpl_vars['parent_title']; ?>
</a></span><?php endif; ?>
<span class="nav2 onright" style="margin-left:20px; padding:0px"> <?php if ($this->_tpl_vars['prev']['id'] > 0): ?><a class="prev" href="customer.php?<?php echo $this->_tpl_vars['parent_info']; ?>
id=<?php echo $this->_tpl_vars['prev']['id']; ?>
" ><img src="art/icons/previous_white.png" style="padding:0px 10px" alt="<" title="<?php echo $this->_tpl_vars['prev']['name']; ?>
"  /></a><?php endif; ?></span>

<table class="search"  border=0 style="<?php if ($this->_tpl_vars['search_label'] == ''): ?>display:none<?php endif; ?>">
<tr>
<td class="label"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Search<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
<td class="form" >
<div id="search" class="asearch_container"  style=";float:left;<?php if (! $this->_tpl_vars['search_scope']): ?>display:none<?php endif; ?>">
  <input style="width:300px" class="search" id="<?php echo $this->_tpl_vars['search_scope']; ?>
_search" value="" state="" name="search"/>
      <img style="position:relative;left:305px" align="absbottom" id="<?php echo $this->_tpl_vars['search_scope']; ?>
_clean_search" class="submitsearch" src="art/icons/zoom.png">

    <div id="<?php echo $this->_tpl_vars['search_scope']; ?>
_search_Container" style="display:none"></div>
</div>    
  
</td></tr>
</table>  
<div id="<?php echo $this->_tpl_vars['search_scope']; ?>
_search_results" style="font-size:10px;float:right;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;top:-500px">
<table id="<?php echo $this->_tpl_vars['search_scope']; ?>
_search_results_table"></table>
</div>

<div style="clear:both;margin-top:0px;margin-right:0px;width:<?php if ($this->_tpl_vars['options_box_width']): ?><?php echo $this->_tpl_vars['options_box_width']; ?>
<?php else: ?>300px<?php endif; ?>;float:right;margin-bottom:10px" class="right_box">
  <div class="general_options">
    <?php $_from = $this->_tpl_vars['general_options_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['options']):
?>
    <?php if ($this->_tpl_vars['options']['tipo'] == 'url'): ?>
    <span class="<?php echo $this->_tpl_vars['options']['class']; ?>
" onclick="window.location.href='<?php echo $this->_tpl_vars['options']['url']; ?>
'" ><?php echo $this->_tpl_vars['options']['label']; ?>
</span>
    <?php else: ?>
    <span  class="<?php echo $this->_tpl_vars['options']['class']; ?>
" id="<?php echo $this->_tpl_vars['options']['id']; ?>
" state="<?php echo $this->_tpl_vars['options']['state']; ?>
"><?php echo $this->_tpl_vars['options']['label']; ?>
</span>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </div>
</div>



