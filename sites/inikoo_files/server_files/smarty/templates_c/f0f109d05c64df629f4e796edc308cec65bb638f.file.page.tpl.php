<?php /* Smarty version Smarty-3.1.5, created on 2014-05-21 18:35:18
         compiled from "templates/page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:903539538521ccdf70be5d2-82502187%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0f109d05c64df629f4e796edc308cec65bb638f' => 
    array (
      0 => 'templates/page.tpl',
      1 => 1400690069,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '903539538521ccdf70be5d2-82502187',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_521ccdf7459c0',
  'variables' => 
  array (
    'language' => 0,
    'title' => 0,
    'page' => 0,
    'site' => 0,
    'css_files' => 0,
    'i' => 0,
    'js_files' => 0,
    'request' => 0,
    'selfurl' => 0,
    'checkout_order_button_url' => 0,
    'checkout_order_list_url' => 0,
    'type_content' => 0,
    'see_also' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521ccdf7459c0')) {function content_521ccdf7459c0($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><?php echo '<?xml';?> version="1.0" encoding="utf-8"<?php echo '?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='<?php echo $_smarty_tpl->tpl_vars['language']->value;?>
' xml:lang='<?php echo $_smarty_tpl->tpl_vars['language']->value;?>
' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
	<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['page']->value->get('Page Keywords')!='';?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?> 
	<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['page']->value->get('Page Keywords');?>
"> <?php }?> <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['page']->value->get('Page Store Resume')!='';?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2){?> 
	<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['page']->value->get('Page Store Resume');?>
"> <?php }?> 
	<link href="<?php echo $_smarty_tpl->tpl_vars['site']->value->get_favicon_url();?>
" rel="shortcut icon" type="image/x-icon" />
	<?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['css_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" type="text/css" />
	<?php } ?> 
	<?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['js_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
?><script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"></script> 
	<?php } ?> 
	<style type="text/css"><?php echo $_smarty_tpl->tpl_vars['page']->value->get_css();?>
</style> <script type="text/javascript"><?php echo $_smarty_tpl->tpl_vars['page']->value->get_javascript();?>
</script> 
<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Search Method')=='Custome'){?>
	<link rel="stylesheet" href="public_search.css.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" type="text/css" />
	<script type="text/javascript" src="public_search.js.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
"></script> 

<?php }else{ ?>
	<link rel="stylesheet" href="css/bar_search.css" type="text/css" />
	<script type="text/javascript" src="js/bar_search.js"></script> 

<?php }?>
<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Checkout Method')=='Mals'){?>
	<script type="text/javascript" src="js/basket_emals_commerce.js"></script> 

<?php }?>





	<link rel="stylesheet" href="public_menu.css.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" type="text/css" />
<script type="text/javascript" src="public_menu.js.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
"></script> 
<?php echo $_smarty_tpl->tpl_vars['page']->value->get_head_includes();?>
 
</head>
<body class="yui-skin-sam inikoo" >
<?php echo $_smarty_tpl->tpl_vars['page']->value->get_body_includes();?>
 
<div id="doc4">
<input type="hidden" id="request" value="<?php echo $_smarty_tpl->tpl_vars['request']->value;?>
" />
<input type="hidden" id="selfurl" value="<?php echo $_smarty_tpl->tpl_vars['selfurl']->value;?>
" />
<input type="hidden" id="checkout_order_button_url" value="<?php echo $_smarty_tpl->tpl_vars['checkout_order_button_url']->value;?>
" />
<input type="hidden" id="checkout_order_list_url" value="<?php echo $_smarty_tpl->tpl_vars['checkout_order_list_url']->value;?>
" />



	<iframe id="basket_iframe" src="dummy.html" style="display:none"></iframe> 
	
	
	<div id="hd" style="padding:0;margin:0;z-index:3;">
		<?php echo $_smarty_tpl->getSubTemplate ("string:".($_smarty_tpl->tpl_vars['page']->value->get_header_template()), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
	</div>
	<div id="bd" style="z-index:1;">
		<div <?php if ($_smarty_tpl->tpl_vars['type_content']->value=='string'){?> id="content" class="content" style="position:relative;height:<?php echo $_smarty_tpl->tpl_vars['page']->value->get('Page Content Height');?>
px;overflow-x:hidden;overflow-y:auto;clear:both;width:100%;" <?php }else{ ?>style="min-height:475px" <?php }?>>
			<?php echo $_smarty_tpl->getSubTemplate (($_smarty_tpl->tpl_vars['type_content']->value).":".($_smarty_tpl->tpl_vars['template_string']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
			
		
			
		</div>
			<?php if ($_smarty_tpl->tpl_vars['page']->value->data['Number See Also Links']>0){?>
			<div id="bottom_see_also" style="margin:auto;padding:20px;margin-top:10px">
			<span style="font-weight:800;font-size:110%"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
See also<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</span>
			<div style="margin-top:7px">
				<?php  $_smarty_tpl->tpl_vars['see_also'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['see_also']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['page']->value->get_see_also(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['see_also']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['see_also']->key => $_smarty_tpl->tpl_vars['see_also']->value){
$_smarty_tpl->tpl_vars['see_also']->_loop = true;
 $_smarty_tpl->tpl_vars['see_also']->index++;
 $_smarty_tpl->tpl_vars['see_also']->first = $_smarty_tpl->tpl_vars['see_also']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['foo']['first'] = $_smarty_tpl->tpl_vars['see_also']->first;
?>
				
				<div style="height:220px;width:170px;float:left;text-align:center;<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['foo']['first']){?>margin-left:20px<?php }?>">
					<div style="border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;">
					<a href="http://<?php echo $_smarty_tpl->tpl_vars['see_also']->value['see_also_url'];?>
">
					<img src="<?php if ($_smarty_tpl->tpl_vars['see_also']->value['see_also_image_key']){?>public_image.php?size=small&id=<?php echo $_smarty_tpl->tpl_vars['see_also']->value['see_also_image_key'];?>
<?php }else{ ?>art/nopic.png<?php }?>" style="max-height:168px;max-width: 168px;overflow:hidden;"/>
					</a>
					</div>
					<div style="font-size:90%;margin-top:5px">
					<?php echo $_smarty_tpl->tpl_vars['see_also']->value['see_also_label'];?>

					</div>
					</div>
				<?php } ?>
				<div style="clear:both"></div>
		</div>
		</div>
			<?php }?>
	</div>
	<div id="ft" style="z-index:2" style="<?php if ($_smarty_tpl->tpl_vars['page']->value->get('Page Footer Type')=='None'){?>display:none<?php }?>">

		<?php echo $_smarty_tpl->getSubTemplate ("string:".($_smarty_tpl->tpl_vars['page']->value->get_footer_template()), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
	</div>
</div>
</body>
</html>
<?php }} ?>