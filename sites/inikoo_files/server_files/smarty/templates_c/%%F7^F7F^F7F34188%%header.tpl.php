<?php /* Smarty version 2.6.22, created on 2011-10-11 12:45:38
         compiled from header.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->_tpl_vars['title']; ?>
</title>
    <link href="art/inikoo-icon.png" rel="shortcut icon" type="image/x-icon" />
    <?php $_from = $this->_tpl_vars['css_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <link rel="stylesheet" href="<?php echo $this->_tpl_vars['i']; ?>
" type="text/css" />
    <?php endforeach; endif; unset($_from); ?>	

    <link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>

    <?php $_from = $this->_tpl_vars['js_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <script type="text/javascript" src="<?php echo $this->_tpl_vars['i']; ?>
"></script>
    <?php endforeach; endif; unset($_from); ?>
    <?php if ($this->_tpl_vars['script']): ?><script type="text/javascript"><?php echo $this->_tpl_vars['script']; ?>
</script><?php endif; ?>
        
  </head>

  <body  class="">
    <div id="" class="">
    

	  