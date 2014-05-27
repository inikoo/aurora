<?php /* Smarty version Smarty-3.1.5, created on 2013-10-18 10:27:54
         compiled from "templates/registration.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15056589305260ff1a62feb8-22678655%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f094b9ad2532236f558e2a6818241873eb19867c' => 
    array (
      0 => 'templates/registration.tpl',
      1 => 1359128645,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15056589305260ff1a62feb8-22678655',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'store' => 0,
    'site' => 0,
    'welcome' => 0,
    'categories' => 0,
    'cat' => 0,
    'cat_key' => 0,
    'sub_cat' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5260ff1ac0a31',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5260ff1ac0a31')) {function content_5260ff1ac0a31($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
"> 
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
"> 
<?php if ($_smarty_tpl->tpl_vars['welcome']->value){?> 
<div style="padding:20px">
	<?php echo $_smarty_tpl->getSubTemplate ("string:".($_smarty_tpl->tpl_vars['site']->value->get_welcome_template()), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
</div>
<?php }else{ ?> 
<div id="dialog_check_email" class="dialog_inikoo">
	<h2>
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Registration<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	
	<p style="padding:0 0 10px 0">
	<?php echo $_smarty_tpl->tpl_vars['site']->value->get('Site Registration Disclaimer');?>

	</p>
	
	<div style="border:1px solid #ccc;padding:20px;width:500px;float:left">
		<table border="0">
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td> 
				<input id="check_email" />
				</td>
			</tr>
			<tr class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_check_email" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Continue<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div id="message_check_email_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
We need your email address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
	<div id="message_check_email_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email address invalid<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
</div>
<div id="dialog_email_in_db" class="dialog_inikoo" style="display:none">
	<h2>
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email already in our Database<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<p id="email_in_db_instructions" style="width:440px;margin-bottom:10px">
		<span id="email_in_db"></span> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
is already in our records, fill the anti-spam field and we will send you an email with instructions of how to access your account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</p>
	<div style="float:left;border:1px solid #ccc;padding:20px;width:500px">
		<table border="0" style="margin:0 auto;">
			<tr id="tr_email_in_db_captcha">
				<td class="label" style="text-align:left"> <img id="captcha3" src="art/x.png" alt="CAPTCHA Image" /> <br> <a class="captcha_show_other" href="#" onclick="document.getElementById('captcha3').src ='securimage_show.php?'+ Math.random(); return false"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Different Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a> </td>
				<td style="vertical-align:top"> <span class="captcha_instructions"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
input the letters shown on the left<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span><br />
				<input type="text" id="captcha_code3" name="captcha_code" style="width:50%" />
				</td>
			</tr>
			<tr id="tr_email_in_db_buttons" class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_forgot_password_from_email_in_db" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Send Instructions<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="hide_email_in_db_dialog" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tr id="tr_forgot_password_wait2" style="display:none" class="button">
				<td colspan="2"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processing request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<tr id="tr_forgot_password_send2" style="display:none" class="button" style="">
				<td colspan="2"> 
				<p style="width:300px">
					<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
An email has been sent to you with instructions how to access your account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

				</p>
				<div style="margin-top:20px" class="buttons"><button  id="hide_email_in_db_dialog2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
				</div>
				</td>
			</tr>
			<tr id="tr_forgot_password_error2" style="display:none" class="button" style="">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Sorry, an automatic password reset could not be done, try later or call us<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.<br><div class="buttons"><button  id="hide_email_in_db_dialog3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button></div></td>
			</tr>
		</table>
	</div>
	<div id="message_email_in_db_missing_captcha" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Please fill the Captcha<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
</div>
<div id="dialog_register" class="dialog_inikoo" style="position:relative; display:none;margin-bottom:60px">
	<input id="epw2" value="" type="hidden" />
	<h2>
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Registration<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:500px;float:left">
		<table border="0" class="edit">
			<tbody>
				<tr class="title">
					<td colspan="3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Login info<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				</tr>
				<tr>
				<input type="hidden" id="confirmed_register_email_value" value=""/>
					<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
					<td id="confirmed_register_email"></td>
					<td style="width:40px"></td>
				</tr>
				<tr>
					<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
					<td> 
					<input type="password" id="register_password1"></td>
				</tr>
				<tr>
					<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Confirm pwd<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
					<td> 
					<input type="password" id="register_password2"></td>
				</tr>
			</tbody>
			<tr class="title">
				<td colspan="3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact Info<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact Name<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_contact_name"></td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Company<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_company_name"></td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Registration Number<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_registration_number"></td>
			</tr>
	<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
VAT Number<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_tax_number"></td>
			</tr>		
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephone<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_telephone"></td>
			</tr>
			<tr class="title">
				<td colspan="3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Address<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Line 1<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_address_line1" />
				</td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Line 2<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_address_line2" />
				</td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Town<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_address_town" />
				</td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Postal Code<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input id="register_address_postcode" />
				</td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Country<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td> 
				<select size="1" id="register_address_country_2alpha_code">
					<option value="XX"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Select One<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<?php if ($_smarty_tpl->tpl_vars['site']->value->get('Site Locale')=='en_GB'){?>
					
					<option value="GB"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
United Kingdom<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="IE"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Ireland<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="XX">----------</option>
					<?php echo $_smarty_tpl->getSubTemplate ("country_select.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<?php }elseif($_smarty_tpl->tpl_vars['site']->value->get('Site Locale')=='es_ES'){?>
					<option value="ES"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Spain<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="PT"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Portugal<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="XX">----------</option>
					<?php echo $_smarty_tpl->getSubTemplate ("country_select.es_ES.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<?php }elseif($_smarty_tpl->tpl_vars['site']->value->get('Site Locale')=='de_DE'){?>
					<option value="DE"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Germany<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="AT"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Austria<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="CH"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Switzerland<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="NL"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Netherlands<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
					<option value="XX">----------</option>
					<?php echo $_smarty_tpl->getSubTemplate ("country_select.de_DE.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<?php }else{ ?>
					<option value="XX">----------</option>
					<?php echo $_smarty_tpl->getSubTemplate ("country_select.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<?php }?>
					
						
				</select>
				</td>
			</tr>
			<tr class="title">
				<td colspan="3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Other Details<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
			</tr>
			<?php if (isset($_smarty_tpl->tpl_vars['categories']->value)){?> <?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['cat_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value){
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['cat_key']->value = $_smarty_tpl->tpl_vars['cat']->key;
?> 
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['cat']->value->get('Category Label');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
				<td> 
				<select id="cat<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" cat_key="<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" onchange="update_category(this)">
					<?php  $_smarty_tpl->tpl_vars['sub_cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub_cat']->_loop = false;
 $_smarty_tpl->tpl_vars['sub_cat_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cat']->value->get_children_objects_public_new_subject(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
					<?php }?> ` 
					<option other="<?php if ($_smarty_tpl->tpl_vars['sub_cat']->value->get('Is Category Field Other')=='Yes'){?>true<?php }else{ ?>false<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['sub_cat']->value->get('Category Key');?>
"><?php echo $_smarty_tpl->tpl_vars['sub_cat']->value->get('Category Label');?>
</option>
					<?php } ?> 
				</select>
				</td>
			</tr>
			<tbody id="other_tbody_<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
" style="display:none">
				<tr>
					<td></td>
					<td><textarea rows='2' cols="20" id="category_other_value_textarea_<?php echo $_smarty_tpl->tpl_vars['cat_key']->value;?>
"></textarea></td>
				</tr>
			</tbody>
			<?php } ?> <?php }?> 
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Catalogue (post)<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td> 
				<input checked="checked" onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="catalogue" value="Yes"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
				<input onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="catalogue" value="No"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			</tr>
			<tr>
				<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Offers by email & e-Newsletter<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
				<td style="vertical-align:top;"> 
				<input checked="checked" onchange="change_allow(this)" style="border:none;width:10px" type="radio" name="newsletter" value="Yes"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Yes<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
				<input onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="newsletter" value="No"> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
No<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 </td>
			</tr>
			<tr class="title">
				<td colspan="3">CAPTCHA: </td>
			</tr>
			<tr>
				<td class="label"> <img id="captcha" src="art/x.png" /> <br> <a href="#" style="color:black" onclick="document.getElementById('captcha').src = 'securimage_show.php?' + Math.random(); return false"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Different Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a> </td>
				<td> 
				<input type="text" id="captcha_code" name="captcha_code" style="width:50%" />
				</td>
			</tr>
			<tr id="tr_register_part_2_buttons" class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_register" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Register<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="cancel_register" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
				</div>
				</td>
			</tr>
			<tr id="tr_register_part_2_wait" style="display:none" class="button">
				<td colspan="2"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Creating your account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
		</table>
		
	<div style="margin-top:20px">	
	<div id="message_register_fields_missing" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Fill all fields please<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
	<div id="register_error_password_not_march" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Passwords don't match<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
	<div id="register_error_password_too_short" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Password is too short<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
	<div id="message_register_error_captcha" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
The Captcha field is incorrect<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
	<div id="processing_register" class="info_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<img style="vertical-align:top" src="art/loading.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Creating your account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
	</div>
	<div id="message_register_error" class="info_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
An error incurred during your registration please try again later<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
	</div>
</div>
	</div>

</div>
<div style="clear:both;margin-bottom:30px">
</div>
<?php }?><?php }} ?>