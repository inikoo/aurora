<?php /* Smarty version Smarty-3.1.5, created on 2014-05-21 09:10:13
         compiled from "templates/login.chrome.tpl" */ ?>
<?php /*%%SmartyHeaderCode:837017091537c5f650cbf43-01204058%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '54b204f0b5dc5818ad6201ba99e56f3283f87436' => 
    array (
      0 => 'templates/login.chrome.tpl',
      1 => 1350295619,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '837017091537c5f650cbf43-01204058',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'block' => 0,
    'store' => 0,
    'site' => 0,
    'St' => 0,
    'referral' => 0,
    'logged_out' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_537c5f65353d7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537c5f65353d7')) {function content_537c5f65353d7($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?>	<script type="text/javascript" src="js/jquery.min.js"></script>
<input type="hidden" id="block" value="<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
"> 

<input type="hidden" id="store_key" value="<?php echo $_smarty_tpl->tpl_vars['store']->value->id;?>
"> 
<input type="hidden" id="site_key" value="<?php echo $_smarty_tpl->tpl_vars['site']->value->id;?>
"> 
<div class="dialog_inikoo">
	<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['St']->value;?>
" id="ep" />
	<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['referral']->value;?>
" id="referral" />

	<div id="dialog_login" style="<?php if ($_smarty_tpl->tpl_vars['block']->value!='login'){?>display:none;<?php }?>min-height:400px;float:left">
		<h2>
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Login<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</h2>
		<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">
			
			<form  method="post" target="dummy">
				<fieldset>
					<table style="margin-bottom:10px">
						<tr>
							<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
							<td id="email_placeholder"> 
							<input type="password" />
							</td>
						</tr>
						<tr>
							<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
							<td id="password_placeholder"> 
							<input type="password" />
							</td>
						</tr>
						<tr>
							<td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Remember Me<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
							<td style="text-align:left;"> 
							<input style="width:20px;border:none" type="checkbox" name="remember_me" id="remember_me" value="0" />
							</td>
						</tr>
						<tr class="button space" style="">
							<td colspan="2"> 
							<div class="buttons">
								<button id="submit_login" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Log In<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button style="display:none" class="negative" id="hide_login_dialog"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Close<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
							</div>
							</td>
						</tr>
					</table>
				</fieldset>
			</form>
		</div>
		<div id="message_log_out" class="ok_block" style="<?php if (!isset($_smarty_tpl->tpl_vars['logged_out']->value)){?>display:none;<?php }?>width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
You have successfully logged out, see you soon<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		<div id="message_login_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Fill all fields please<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		<div id="message_forgot_password_send" class="ok_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<p><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
An email has been sent to you with a link to access your account<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.</p>
			<p><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
The link will expire in 24 hours<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
.</p>

		</div>
		<div id="message_login_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email address invalid<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		<div id="invalid_credentials" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Invalid username or password!<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		<div id="wrong_password" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Wrong password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		<div id="wrong_email" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email is not in our records<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		
		<div style="clear:both">
		</div>
		<table style="clear:left;float;left;margin-top:10px;">
			<tr class="link space">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Forgot your password?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" id="link_forgot_password_from_login"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Click Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<tr id="tr_link_register_from_login" class="link">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
First visit?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" onclick='window.location="registration.php"' id="link_register_from_login"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Register Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<tr style="display:none" id="tr_link_register_from_login2" class="link">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Use another email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
, <span class="link" id="link_register_from_login2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Register Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<?php if ($_smarty_tpl->tpl_vars['site']->value->get_login_help_page_key()){?> 
			<tr class="link">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Troubles with Log in?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" onclick='window.location="page.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->get_login_help_page_key();?>
"' id="link_register_from_login"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Read Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<?php }?> 
		</table>
	</div>

	<div id="dialog_forgot_password" style="<?php if ($_smarty_tpl->tpl_vars['block']->value!='forgot_password'){?>display:none;<?php }?>float:left">
		<h2>
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Forgotten password<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</h2>
		<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">
			<div class="dialog_inikoo">
				<table>
					<tbody id="forgot_password_form">
						<tr>
							<td class="label" style="text-align:right;width:120px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: </td>
							<td> 
							<input id="forgot_password_handle"></td>
						</tr>
						<tr>
							<td class="label" style="text-align:left;width:120px"> <img id="captcha2" src="art/x.png" style="height:40px;width:115px" alt="CAPTCHA Image" /> <span class="captcha_change_image" onclick="document.getElementById('captcha2').src = 'securimage_show.php?' + Math.random(); return false"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Change Image<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span> </td>
							<td style="vertical-align:top"> <span style="font-size:10px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
input the letters shown on the left<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span><br />
							<input type="text" id="captcha_code2" name="captcha_code" style="width:50%" />
							</td>
						</tr>
					</tbody>
					<tr class="button space">
						<td id="forgot_password_buttons" colspan="2"> 
						<div class="buttons">
							<button id="submit_forgot_password" class="positive"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Send Email<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> <button id="cancel_forgot_password" class="negative"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="processing_change_password" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<img style="vertical-align:top" src="art/loading.gif" alt=""> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Processing Request<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		<div id="message_forgot_password_error" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Sorry, an automatic password reset could not be done, try later or call us<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		<div id="message_forgot_password_not_found" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Sorry, that email is not in our records<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 
		</div>
		<div id="message_forgot_password_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Fill all fields please<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		<div id="message_forgot_password_error_captcha" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
The Captcha field is incorrect<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		<div id="message_forgot_password_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Email address invalid<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
. 
		</div>
		
		<div style="clear:both">
		</div>
		<table style="margin-top:15px;clear:left">
			<tr class="link space">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Want to try to login again?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" id="show_login_dialog2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Click Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<tr id="tr_link_register_from_login" class="link">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
First visit?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" onclick='window.location="registration.php"' id="link_register_from_login"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Register Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<?php if ($_smarty_tpl->tpl_vars['site']->value->get_login_help_page_key()){?> 
			<tr class="link">
				<td colspan="2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Troubles with Log in?<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 <span class="link" onclick='window.location="page.php?id=<?php echo $_smarty_tpl->tpl_vars['site']->value->get_login_help_page_key();?>
"' id="link_register_from_login"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Read Here<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></td>
			</tr>
			<?php }?> 
		</table>
	</div>
	<div style="clear:both;margin-bottom:30px">
	</div>
</div>
<iframe src="dummy.html" name="dummy" style="width:100%;display: none"></iframe>
<div id="dont_forget" style="display: none">
		<form action="" method="post">
			<input type="text" name="email" id="email"/>
			<input type="password" name="password" id="password"/>
			<input type="submit" value="Login" id="dummy_submit"/>
		</form>
	</div><?php }} ?>