<?php /* Smarty version 2.6.22, created on 2010-10-16 15:03:41
         compiled from templates/head.en_GB.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->_tpl_vars['lang_code']; ?>
">
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo $this->_tpl_vars['store_code']; ?>
/art/favicon.png" rel="shortcut icon" type="image/x-icon" />

    <title><?php echo $this->_tpl_vars['title']; ?>
</title>
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
    <script type="text/javascript"><?php echo $this->_tpl_vars['script']; ?>
</script>
    


  </head>