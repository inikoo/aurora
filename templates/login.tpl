<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_code}">
<head>
	<title>{t}Inikoo Login{/t}</title>
	<link href="art/inikoo_logo_small.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} {foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} 
</head>
<body class=" yui-skin-sam inikoo" >
<div id="doc4" class="">
	<div id="hd">
		<div class="top_navigation">
			<div id="top_navigation_right_block">
				<span id="top_navigation_message"></span> <a id="top_navigation_help" href="help.php?page=index.php" style="margin-left:20px;margin-right:15px">Help</a> <span id="language_flag" style="margin-right:20px"><img src="art/flags/gb.gif" alt="gb" /></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<img src="art/inikoo_logo_small.png" style="position:absolute;margin-left:20px;margin-top:2px;height:34px" /> 
		<div class="buttons menu" style="background:#245e86 url('art/themes/cobalt.jpg') bottom left repeat-x;color:#fff;">
			<button id="supplier_login" class="header  {if $login_type=='supplier'}selected{/if}">Suppliers Login</button> <button id="staff_login" class="header {if $login_type=='staff'}selected{/if}">Staff Login</button> 
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div id="bd" style="padding-top:30px">
		
		 <div id="login_message">
    	<h2>{$message_showcase}</h2>
    	<p>{$message}</p>
    	
    </div>
		
		<div id="login_box" style="border:1px solid #ccc;padding:0px 20px 10px 20px ;width:240px;xmargin-top:0px;xmargin-right:30px;margin:0px auto">
			<h2 id="login_title_staff" style="margin-top:10px;{if $login_type!='staff'}display:none{/if}">
				{t}Staff Login{/t} 
			</h2>
			<h2 id="login_title_suppliers" style="margin-top:10px;{if $login_type!='supplier'}display:none{/if}">
				{t}Suppliers Login{/t} 
			</h2>
			<div id="mensage">
			</div>
			
			
			<form name="loginform" id="loginform" method="post" autocomplete="off" action="authorization.php">
				<table style="width:100%;margin-top:20px">
					<input type="hidden" name="_lang" value="{$lang_id}" />
					<input type="hidden" id="ep" name="ep" value="{$st}" />
					<input type="hidden" id="user_type" name="user_type" value="{$login_type}" />
					<tr>
						<td>{t}User{/t}:</td>
						<td> 
						<input style="width:100%" type="text" class="text" id="_login_" name="_login_" maxlength="80" value="" />
						</td>
					</tr>
					<tr>
						<td>{t}Password{/t}:</td>
						<td> 
						<input style="width:100%" type="password" class="password" id="_passwd_" name="_passwd_" maxlength="80" value="" />
						</td>
					</tr>
				</table>
			</form>
			<table style="width:100%;">
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px">
						<button class="positive" id="login_button">{t}Log in{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
	</div>
	<div id="footer">
		<div class="links">
			<a href="terms_use.php">{t}Terms of use{/t}</a> 
		</div>
		<div class='adv'>
			<img src="art/inikoo_logo_mini.png"/> <a href="http://www.inikoo.com">{t}Inikoo{/t}</a> <a href="http://www.inikoo.com/changelog.php/v={$inikoo_version}">v{$inikoo_version}</a> 
		</div>
		<div style="clear:both">
		</div>
	</div>
</div>	
</body>
</html>
