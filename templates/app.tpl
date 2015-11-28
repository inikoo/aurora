<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Aurora</title>
	<link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon" />
	<link href="/css/jquery-ui.min.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet">
	<link href="/css/backgrid.css" rel="stylesheet">
	<link href="/css/backgrid-filter.css" rel="stylesheet">
	<link href="/css/intlTelInput.css" rel="stylesheet">
	
	
	
	<link href="/css/app.css" rel="stylesheet">
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/moment.min.js"></script> 
 
	<script type="text/javascript" src="/js/chrono.min.js"></script> 
	<script type="text/javascript" src="/js/sha256.js"></script> 
	<script type="text/javascript" src="/js/underscore.js"></script> 
	<script type="text/javascript" src="/js/backbone.js"></script> 
	<script type="text/javascript" src="/js/backbone.paginator.js"></script> 
	<script type="text/javascript" src="/js/backgrid.js"></script> 
	<script type="text/javascript" src="/js/backgrid-filter.js"></script> 
	<script type="text/javascript" src="/js/app.js"></script> 
	<script type="text/javascript" src="/js/keyboard_shorcuts.js"></script> 
	<script type="text/javascript" src="/js/table.js"></script> 
	<script type="text/javascript" src="/js/field_validation.js"></script> 
	<script type="text/javascript" src="/js/edit.js"></script> 
	<script type="text/javascript" src="/js/new.js"></script> 
	<script type="text/javascript" src="/js/intlTelInput.min.js"></script> 
	
	
	
</head>
<body>
<input type="hidden" id="_request" value="{$_request}">
<div id="top_bar">
	<div id="view_position">
	</div>
</div>
<div class="grid">
	<section>
		<div id="app_leftmenu">
			<div id="top_info">
				<div id="aurora_logo" class="link" onclick="help()" >
					<img src="/art/aurora_log_v2_orange_small.png" /> 
				</div>
				<div id="hello_user" class="link" onclick="change_view('profile')">
					<span>{$user->get('User Alias')}</span> 
				</div>
			</div>
			<div id="account_name" class="link" onclick="change_view('account')">
				{$account->get('Account Name')} 
			</div>
			<div id="menu">
			</div>
			<ul style="margin-top:20px">
				<li onclick="logout()" ><i class="fa fa-sign-out fa-fw"></i> <span id="logout_label">{t}Logout{/t}</li>
			</ul>
		</div>
		<div id="app_main">
			<div id="navigation">
			</div>
			<div id="object_showcase">
			</div>
			<div id="tabs">
			</div>
			<div id="tab">
			</div>
			<div style="clear:both;margin-bottom:100px">
			</div>
		</div>
	</section>
	<aside id="notifications">
	</aside>
</div>
</body>
</html>
