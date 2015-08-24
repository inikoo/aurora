<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Inikoo</title>
	<link href="art/inikoo_logo.png" rel="shortcut icon" type="image/x-icon" />
	<link href="css/font-awesome.min.css" rel="stylesheet"> 
	<link href="css/app.css" rel="stylesheet"> <script type="text/javascript" src="js/jquery.min.js"></script> <script type="text/javascript" src="js/app.js"></script> 
</head>
<body>
<div class="grid">
	<section>
		<div id="app_leftmenu">
		
		
			<div id="top_info">
				<div id="inikoo_logo">
					<img src="art/inikoo_logo_small.png" /> 
				</div>
				<div id="hello_user">
					<span><a href="user.php">{$user->get('User Alias')}</a></span> 
				</div>
				
			</div>
			<div id="inikoo_account_name">
				{$inikoo_account->get('Account Name')}
			</div>
			<div id="menu">
				<ul >
					{foreach from=$nav_menu item=menu } 
					<li id="{$menu[2]}" onclick="change_inikoo_content('{$menu[1]}')" class="header {if $menu[2]=='index'} selected{/if}">{$menu[0]}</li>
					{/foreach} 
				</ul>
				<div style="clear:both">
				</div>
			</div>
		</div>
		<div id="app_main">
			<iframe id="inikoo_content" name="inikoo_content"  src="{$app_view_url}" style="padding:0px;border:none;width:100%;height:100%"></iframe> 
		</div>
	</section>
	<aside id="notifications">
	</aside>
</div>
<div id="footer">
</div>
</body>
</html>
