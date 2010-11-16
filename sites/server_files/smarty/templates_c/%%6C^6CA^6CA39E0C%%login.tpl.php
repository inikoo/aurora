<?php /* Smarty version 2.6.22, created on 2010-10-22 08:50:35
         compiled from ../templates/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', '../templates/login.tpl', 23, false),)), $this); ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->_tpl_vars['lang_code']; ?>
">
<head>
	<title><?php echo $this->_tpl_vars['title']; ?>
</title>
	<link href="art/kaktus-icon.png" rel="shortcut icon" type="image/x-icon" />
	  <?php $_from = $this->_tpl_vars['css_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['i']; ?>
" type="text/css" />
	<?php endforeach; endif; unset($_from); ?>	
	<?php $_from = $this->_tpl_vars['js_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['i']; ?>
"></script>
	<?php endforeach; endif; unset($_from); ?>
</head>
<body  class="<?php echo $this->_tpl_vars['theme']; ?>
">

<div id="custom-doc">

 <div id="loginbd" >
<h1><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h1>

<div id="mensage">
</div>

<form name="loginform" id="loginform" method="post"   autocomplete="off" action="index.php">
<table style="margin:60px auto;" >
            <tr>
                <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
                <td style="width:10em" ><input type="text"  class="text"  id="_login_" name="_login_" maxlength="80"  value="" /></td><td>
            </tr>
             <tr  >
                <td><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
                <td><input type="password"  class="password" id="_passwd_"  name="_passwd_" maxlength="80" value="" /></td>

            </tr>
                <td colspan="2">
                    <div style="text-align:center">
                        <button id="login_go"   ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Log in<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></button>
                        <input type="hidden" name="_lang" value="<?php echo $this->_tpl_vars['lang_id']; ?>
" />
			<input type="hidden" id="ep" name="ep" value="<?php echo $this->_tpl_vars['st']; ?>
" />
                    </div>
                </td>
            </tr>
</table>

</form>
<div id="other_langs">
<?php $_from = $this->_tpl_vars['other_langs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <a class="choose_lang"  href="index.php?_lang=<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['i']; ?>
</a>
<?php endforeach; endif; unset($_from); ?>
</div>
</div> 



