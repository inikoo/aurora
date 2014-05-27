<?php /* Smarty version Smarty-3.1.5, created on 2014-05-22 21:43:31
         compiled from "templates/profile_contact.tpl" */ ?>
<?php /*%%SmartyHeaderCode:156783611152ea2aba609321-04025993%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4eae5e9684ada846c88517f2ff955b6fa71ca13d' => 
    array (
      0 => 'templates/profile_contact.tpl',
      1 => 1400787809,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156783611152ea2aba609321-04025993',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_52ea2abb42d5c',
  'variables' => 
  array (
    'user' => 0,
    'store' => 0,
    'site' => 0,
    'page' => 0,
    'other_value' => 0,
    'key' => 0,
    'other' => 0,
    'enable_other' => 0,
    'other_email' => 0,
    'custom_fields' => 0,
    'custom_field' => 0,
    'categories' => 0,
    'cat' => 0,
    'cat_key' => 0,
    'categories_value' => 0,
    'sub_cat_key' => 0,
    'sub_cat' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ea2abb42d5c')) {function content_52ea2abb42d5c($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="user_key" value="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
" />
<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
" />
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
" />
<input type="hidden" id="customer_key" value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->id;?>
" />
<input type="hidden" id="parent_category_key" value="0" />
<input type="hidden" id="category_key" value="0" />
<?php  $_smarty_tpl->tpl_vars['other'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['other']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['other_value']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['other']->key => $_smarty_tpl->tpl_vars['other']->value){
$_smarty_tpl->tpl_vars['other']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['other']->key;
?> 
<input type="hidden" id="other_value_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['other']->value;?>
" />
<?php } ?> <?php  $_smarty_tpl->tpl_vars['other'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['other']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['enable_other']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['other']->key => $_smarty_tpl->tpl_vars['other']->value){
$_smarty_tpl->tpl_vars['other']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['other']->key;
?> 
<input type="hidden" id="enable_other_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['other']->value;?>
" />
<?php } ?> 
<?php echo $_smarty_tpl->getSubTemplate ('profile_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('select'=>'contact'), 0);?>
 

<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Show Site Badges')=='Yes'){?> 
<div style="border:0px solid #ccc;padding:0px 0px 0 0;width:890px;font-size:15px;margin-left:20px;margin-top:20px">
	<div style="float:left;;border:0px solid #ccc;;height:60px;width:350px;;padding:5px 20px;margin-left:20px;font-size:80%">
		This profile page is your way to tell us something about you that will help us to help you. The awards on the right illuminate as you get to know us better. Mouse over the awards to see how to get them, a full set will trigger your <i>Most Favoured Trader</i> status.
	</div>
	<?php echo $_smarty_tpl->getSubTemplate ('customer_badges.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('customer'=>$_smarty_tpl->tpl_vars['page']->value->customer), 0);?>
 
	
	<div style="clear:both">
	</div>
</div>
<?php }?> 

<div style="padding:0px 20px;float:left">
	<h2 style="padding-top:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact Details<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
		
		<div style="float:right;border:0px solid #ccc;;margin-right:0px;margin-bottom:10px" id="show_upload_image">
		<?php if ($_smarty_tpl->tpl_vars['user']->value->get_image_src()){?>
		<img id="avatar" src="<?php echo $_smarty_tpl->tpl_vars['user']->value->get_image_src();?>
" style="cursor:pointer;border:1px solid #eee;width:50px;max-height:50px"> 
		<?php }else{ ?>
		<img id="avatar" src="art/avatar.jpg" style="cursor:pointer;"> 
		<?php }?>
	</div>
		<h3>
			<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Name');?>
 (<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get_formated_id();?>
) 
		</h3>
		<table id="customer_data" border="0" style="width:100%;margin-top:20px">
			<tr style="<?php if (!($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Type')=='Company')){?>display:none<?php }?>">
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Company<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img id="show_edit_name" style="cursor:pointer" src="art/edit.gif" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
				<td class="aright"><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Company Name');?>
</td>
			</tr>
			<tr>
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Name<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img style="cursor:pointer" id="show_edit_contact" src="art/edit.gif" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
				<td class="aright"><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Contact Name');?>
</td>
			</tr>
			<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Email Key')){?> 
			<tr id="main_email_tr">
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img src="art/lock.png"></td>
				<td id="main_email" class="aright"><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Plain Email');?>
</td>
			</tr>
			<?php }?> <?php  $_smarty_tpl->tpl_vars['other_email'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['other_email']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value->customer->get_other_emails_data(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['other_email']->key => $_smarty_tpl->tpl_vars['other_email']->value){
$_smarty_tpl->tpl_vars['other_email']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['other_email']->key;
?> 
			<tr id="other_email_tr">
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img src="art/lock.png"></td>
				<td id="email<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="aright"><?php echo $_smarty_tpl->tpl_vars['other_email']->value['email'];?>
</td>
			</tr>
			<?php } ?> 
			<tr>
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephone<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img style="cursor:pointer" src="art/edit.gif" id="show_edit_telephone" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
				<td class="aright"><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Plain Telephone');?>
</td>
			</tr>
			<tr style="border-bottom:1px solid #eee">
				<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Website<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td><img style="cursor:pointer" src="art/edit.gif" id="show_edit_website" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
				<td class="aright"><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Website');?>
</td>
			</tr>
			<?php  $_smarty_tpl->tpl_vars['custom_field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['custom_field']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['custom_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['custom_field']->key => $_smarty_tpl->tpl_vars['custom_field']->value){
$_smarty_tpl->tpl_vars['custom_field']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['custom_field']->key;
?> 
			<tr>
				<td><?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
:</td>
				<td><img style="cursor:pointer" src="art/edit.gif" id="show_edit_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
				<td class="aright"><?php echo $_smarty_tpl->tpl_vars['custom_field']->value['value'];?>
</td>
			</tr>
			<?php } ?> 
			<tr style="display:none;">
				<td> 
				<div class="buttons">
					<button style="display:none" onclick="window.location='client.php'"><img src="art/icons/chart_pie.png" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit Profile<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
				
			</tr>
			
			
			<tr class="space2"><td></td></tr>
			
			<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td><img style="cursor:pointer" src="art/edit.gif" id="show_edit_address" alt="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit contact address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit contact address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" /></td>
			<td class="aright">
					<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main XHTML Address');?>

				</td>
			</tr>
			
		</table>
	</div>
</div>
<div style="padding:0px 20px;float:right;display:none">
	<h2 style="padding-top:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Notes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
	</div>
</div>
<div style="padding:0px 20px;float:right">
	<h2 style="padding-top:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Let's connect together<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
		<table class="edit" style="width:390px" border="0">
		
		<tr >
				<td colspan="5" style="text-align:right"><div style="font-size:120%;font-weight:800"><a style="text-decoration:none;color:#000" href="mailto:<?php echo $_smarty_tpl->tpl_vars['store']->value->get('Store Email');?>
"><?php echo $_smarty_tpl->tpl_vars['store']->value->get('Store Email');?>
</a><br><?php echo $_smarty_tpl->tpl_vars['store']->value->get('Store Telephone');?>
</div></td>
			</tr>
		<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr class="title">
				<td colspan="5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Newsletter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px"><?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Newsletter Custom Label')==''){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Newsletter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Newsletter Custom Label');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?>:</td>
				<td> 
				<div class="buttons small">
					<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Newsletter')=='Yes'){?>selected<?php }?> positive" onclick="save_comunications('Customer Send Newsletter','Yes')" id="Customer Send Newsletter_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Newsletter')=='No'){?>selected<?php }?> negative" onclick="save_comunications('Customer Send Newsletter','No')" id="Customer Send Newsletter_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px"><?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Email Marketing Custom Label')==''){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Latest Offers & Updates<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Email Marketing Custom Label');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?>:</td>
				<td> 
				<div class="buttons small">
					<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Email Marketing')=='Yes'){?>selected<?php }?> positive" onclick="save_comunications('Customer Send Email Marketing','Yes')" id="Customer Send Email Marketing_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Email Marketing')=='No'){?>selected<?php }?> negative" onclick="save_comunications('Customer Send Email Marketing','No')" id="Customer Send Email Marketing_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tr class="title">
				<td colspan="5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Post<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px"><?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Postal Marketing Custom Label')==''){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Catalogues & Vouchers<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Postal Marketing Custom Label');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?>:</td>
				<td> 
				<div class="buttons small">
					<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Postal Marketing')=='Yes'){?>selected<?php }?> positive" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Send Postal Marketing')=='No'){?>selected<?php }?> negative" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tbody id="add_to_post_cue" style="display:none">
				<tr class="title">
					<td colspan="5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Send Post <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
				</tr>
				<tr>
					<td class="label" style="width:200px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Add Customer To Send Post<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
					<td> 
					<div class="buttons small">
						<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Send Post Status')=='To Send'){?>selected<?php }?> positive" onclick="save_comunications_send_post('Send Post Status','To Send')" id="Send Post Status_To Send"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Send Post Status')=='Cancelled'){?>selected<?php }?> negative" onclick="save_comunications_send_post('Send Post Status','Cancelled')" id="Send Post Status_Cancelled"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Post Type<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
					<td> 
					<div class="buttons small">
						<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Post Type')=='Letter'){?>selected<?php }?> positive" onclick="save_comunications_send_post('Post Type','Letter')" id="Post Type_Letter"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Letter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Post Type')=='Catalogue'){?>selected<?php }?> negative" onclick="save_comunications_send_post('Post Type','Catalogue')" id="Post Type_Catalogue"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Catalogue<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
				</tr>
			</tbody>
			<tbody style="display:none" id="social_media">
				<tr class="title">
					<td colspan="5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Social Media<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
				<tr style="height:30px;<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Twitter')=='No'){?>display:none<?php }?>">
					<td class="label" style="width:200px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Follower on Twitter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
					<td> 
					<div class="buttons small">
						<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Follower On Twitter')=='Yes'){?>selected<?php }?> positive" onclick="save_comunications('Customer Follower On Twitter','Yes')" id="Customer Follower On Twitter_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Follower On Twitter')=='No'){?>selected<?php }?> negative" onclick="save_comunications('Customer Follower On Twitter','No')" id="Customer Follower On Twitter_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
				</tr>
				<tr style="height:30px;<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Facebook')=='No'){?>display:none<?php }?>">
					<td class="label" style="width:200px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Friend on Facebook<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
					<td> 
					<div class="buttons small">
						<button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Friend On Facebook')=='Yes'){?>selected<?php }?> positive" onclick="save_comunications('Customer Friend On Facebook','Yes')" id="Customer Friend On Facebook_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Friend On Facebook')=='No'){?>selected<?php }?> negative" onclick="save_comunications('Customer Friend On Facebook','No')" id="Customer Friend On Facebook_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
				</tr>
				
				
			</tbody>
			
			<tr>
			
			
				<tr style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Facebook')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Twitter')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Google')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show LinkedIn')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Youtube')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Flickr')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Blog')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Digg')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show RSS')=='No'&&$_smarty_tpl->tpl_vars['site']->value->get('Site Show Skype')=='No'){?>none<?php }?>" class="title">
						<td colspan="5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Social Sites<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
				<td colspan="3">
									<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Skype')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Skype URL');?>
"><img src="art/grunge_skype.png" style="height:40px"/></a>

					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Facebook')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Facebook URL');?>
"><img src="art/grunge_facebook.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Twitter')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Twitter URL');?>
"><img src="art/grunge_twitter.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Google')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Google URL');?>
"><img src="art/grunge_google_plus.png" style="height:40px"/></a>

					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show LinkedIn')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site LinkedIn URL');?>
"><img src="art/grunge_linkedin.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Youtube')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Youtube URL');?>
"><img src="art/grunge_youtube.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Flickr')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Flickr URL');?>
"><img src="art/grunge_flickr.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Blog')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Blog URL');?>
"><img src="art/grunge_blog.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show Digg')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Digg URL');?>
"><img src="art/grunge_digg.png" style="height:40px"/></a>
					<a style="display:<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Show RSS')=='No'){?>none<?php }?>" href="http://<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site RSS URL');?>
"><img src="art/grunge_rss.png" style="height:40px"/></a>

				</td>
			</tr>
			
		</table>
	</div>
</div>
<div style="clear:left">
</div>
<div style="padding:0px 20px 20px 20px;float:left">
	<h2 style="padding-top:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
About you<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px;">
		
		
		<table style="margin:10px">
		
		
		
		
			<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['cat_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value){
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['cat_key']->value = $_smarty_tpl->tpl_vars['cat']->key;
?> 
			<tr>
				<td class="label"> 
				<div style="width:150px">
					<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['cat']->value->get('Category Label');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: 
				</div>
				</td>
				<td> 
				<select id="cat<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" cat_key="<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" onchange="save_category(this)">
					<?php  $_smarty_tpl->tpl_vars['sub_cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub_cat']->_loop = false;
 $_smarty_tpl->tpl_vars['sub_cat_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cat']->value->get_children_objects_public_edit(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['sub_cat']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['sub_cat']->key => $_smarty_tpl->tpl_vars['sub_cat']->value){
$_smarty_tpl->tpl_vars['sub_cat']->_loop = true;
 $_smarty_tpl->tpl_vars['sub_cat_key']->value = $_smarty_tpl->tpl_vars['sub_cat']->key;
 $_smarty_tpl->tpl_vars['sub_cat']->index++;
 $_smarty_tpl->tpl_vars['sub_cat']->first = $_smarty_tpl->tpl_vars['sub_cat']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['foo2']['first'] = $_smarty_tpl->tpl_vars['sub_cat']->first;
?> <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['foo2']['first']){?> 
					<option value=""><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Unknown<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<?php }?> 
					<option <?php if ($_smarty_tpl->tpl_vars['categories_value']->value[$_smarty_tpl->tpl_vars['cat_key']->value]==$_smarty_tpl->tpl_vars['sub_cat_key']->value){?>selected='selected' <?php }?> other="<?php if ($_smarty_tpl->tpl_vars['sub_cat']->value->get('Is Category Field Other')=='Yes'){?>true<?php }else{ ?>false<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['sub_cat']->value->get('Category Key');?>
"><?php echo $_smarty_tpl->tpl_vars['sub_cat']->value->get('Category Label');?>
</option>
					<?php } ?> 
				</select>
				</td>
			</tr>
			<tbody id="show_other_tbody_<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" style="<?php if (!$_smarty_tpl->tpl_vars['cat']->value->number_of_children_with_other_value('Customer',$_smarty_tpl->tpl_vars['page']->value->customer->id)||!$_smarty_tpl->tpl_vars['cat']->value->get_children_key_is_other_value_public_edit()){?>display:none<?php }?>">
				<tr>
					<td> 
					<div class="buttons small">
						<button onclick="show_save_other(<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
)"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Edit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
					<td style="border:1px solid #ccc;"><?php echo $_smarty_tpl->tpl_vars['cat']->value->get_other_value('Customer',$_smarty_tpl->tpl_vars['page']->value->customer->id);?>
 </td>
				</tr>
			</tbody>
			<tbody id="other_tbody_<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" style="display:none">
				<tr>
					<td></td>
					<td><textarea rows='2' cols="20" id="other_textarea_<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['cat']->value->get_other_value('Customer',$_smarty_tpl->tpl_vars['page']->value->customer->id);?>
</textarea></td>
				</tr>
				<tr>
					<td></td>
					<td> 
					<div class="buttons small left">
						<button onclick="save_category_other_value(<?php echo $_smarty_tpl->tpl_vars['cat']->value->get_children_key_is_other_value();?>
,<?php echo $_smarty_tpl->tpl_vars['cat']->value->id;?>
)"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
					</div>
					</td>
				</tr>
			</tbody>
			<tr style="height:15px">
				<td colspan="2"></td>
			</tr>
			<?php } ?> 
		</table>
	</div>
</div>

<div style="clear:both;margin-bottom:25px">
</div>


<div style="top:180px;left:490px;position:absolute;display:none;background-image:url('art/background_badge_info.jpg');width:200px;height:223px;" id="gold_reward_badge_info">
	<p style="padding:40px 20px;font-size:20px;margin:20px auto">
		bla bla bla <br />
		<a href="">More Info</a> 
	</p>
</div>


<div id="dialog_quick_edit_Customer_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Customer Name:<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Name" value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Company Name');?>
" ovalue="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Company Name');?>
" valid="0"> 
				<div id="Customer_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Name_msg"></span> <button class="positive" onclick="save_quick_edit_name()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" id="close_quick_edit_name"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Contact" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Name:<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Contact" value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Contact Name');?>
" ovalue="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Contact Name');?>
" valid="0"> 
				<div id="Customer_Contact_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Contact_msg"></span> <button class="positive" onclick="save_quick_edit_contact()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" id="close_quick_edit_contact"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Telephone" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephone:<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Telephone" value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Plain Telephone');?>
" ovalue="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Main Plain Telephone');?>
" valid="0"> 
				<div id="Customer_Telephone_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Telephone_msg"></span> <button class="positive" onclick="save_quick_edit_telephone()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" id="close_quick_edit_telephone"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Website" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Website:<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Website" value="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Website');?>
" ovalue="<?php echo $_smarty_tpl->tpl_vars['page']->value->customer->get('Customer Website');?>
" valid="0"> 
				<div id="Customer_Website_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Website_msg"></span> <button class="positive" onclick="save_quick_edit_website()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" id="close_quick_edit_website"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<?php  $_smarty_tpl->tpl_vars['custom_field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['custom_field']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['custom_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['custom_field']->key => $_smarty_tpl->tpl_vars['custom_field']->value){
$_smarty_tpl->tpl_vars['custom_field']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['custom_field']->key;
?> <?php if ($_smarty_tpl->tpl_vars['custom_field']->value['type']=='Enum'){?> 
<div id="dialog_quick_edit_Customer_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td class="label" style="width:"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
			<td> 
			<div class="buttons">
				<button class="<?php if ($_smarty_tpl->tpl_vars['custom_field']->value['value']=='Yes'){?>selected<?php }?> positive" onclick="save_custom_enum('<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
','Yes')" id="<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
_Yes"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="<?php if ($_smarty_tpl->tpl_vars['custom_field']->value['value']=='No'){?>selected<?php }?> negative" onclick="save_custom_enum('<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
','No')" id="<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
_No"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<?php }else{ ?> 
<div id="dialog_quick_edit_Customer_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
:<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['value'];?>
" ovalue="<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['value'];?>
" valid="0"> 
				<div id="Customer_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
_msg"></span> <button class="positive" onclick="save_quick_edit_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
()"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button class="negative" id="close_quick_edit_<?php echo $_smarty_tpl->tpl_vars['custom_field']->value['name'];?>
"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<?php }?> <?php } ?> <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['name'] = 'foo';
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total']);
?> 
<div id="dialog_badge_info_<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['foo']['iteration'];?>
" style="padding:10px;display:none">
	<table style="margin:10px">
		<tr>
			<td><?php echo $_smarty_tpl->tpl_vars['page']->value->customer->badge_info($_smarty_tpl->getVariable('smarty')->value['section']['foo']['iteration']);?>
</td>
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons" style="margin-top:10px">
				<button class="negative" id="close_badge_info_<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['foo']['iteration'];?>
"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<?php endfor; endif; ?> 


<div id="dialog_image_upload" style="padding:10px">
	<table>
	
	
	<tr style="<?php if ($_smarty_tpl->tpl_vars['user']->value->get_image_src()){?>display:inline<?php }else{ ?>display:none<?php }?>">
			<td> 
			<div class="buttons left" image_id="<?php echo $_smarty_tpl->tpl_vars['user']->value->get_image_key();?>
">
			<button onClick="delete_image(this)"  > <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Delete Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
	</button> 
			</div>
			</td>
		</tr>
	
	<tr style="height:10px">
			<td></td>
		</tr>
	
		<tr>
			<td><?php if ($_smarty_tpl->tpl_vars['user']->value->get_image_src()){?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Change Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }else{ ?><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Upload Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?></td>
		</tr>
		
		<tr style="height:10px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
				<input id="upload_image_input" style="border:1px solid #ddd;" type="file" name="testFile" />
			</form>
			</td>
			<td> 
			<div class="buttons left">
				<button id="uploadButton" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Upload<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<?php }} ?>