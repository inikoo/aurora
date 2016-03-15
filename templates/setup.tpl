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
    <link href="/external_libs/d3fc/d3fc.css" rel="stylesheet"/>

	<script type="text/javascript" src="/utils/country_data.js.php"></script> 



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
	<script type="text/javascript" src="js/telephone_utils.js"></script> 

	
	<script type="text/javascript" src="/js/setup.js"></script> 

	<script type="text/javascript" src="/js/keyboard_shorcuts.js"></script> 
	<script type="text/javascript" src="/js/search.js"></script> 
	<script type="text/javascript" src="/js/table.js"></script> 
	<script type="text/javascript" src="/js/validation.js"></script> 
	<script type="text/javascript" src="/js/edit.js"></script> 
	<script type="text/javascript" src="/js/new.js"></script> 
	<script type="text/javascript" src="/js/intlTelInput.js"></script> 
	
	
	<script src="/external_libs/d3fc/d3.min.js"></script>
   <script src="/external_libs/d3fc/layout.js"></script>
   <script src="/external_libs/d3fc/d3fc.js"></script>
	
	
	
</head>
<body>
<input type="hidden" id="_request" value="{$request}">
<div id="top_bar">
	<div id="view_position">
	</div>
</div>
<div class="grid">
	<section>
		<div id="app_leftmenu">
			<div id="top_info">
				<div id="aurora_logo" class="link" onclick="help()">
					<img src="/art/aurora_log_v2_orange_small.png" /> 
				</div>
				<div id="hello_user" class="link" onclick="change_view('profile')">
					
				</div>
			</div>
			<div id="account_name" class="link" onclick="change_view('account')">
				
			</div>
			<div id="menu">
			</div>
			<ul style="margin-top:20px" class="">
				<li onclick="logout()"><i class="fa fa-sign-out fa-fw"></i> <span id="logout_label">{t}Exit{/t}</li>
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
		<div class="top_buttons" style="padding:2px 10px;border-bottom:1px solid #eee">
			
			<div>
	</aside>
</div>
</body>
</html>
