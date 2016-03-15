<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_code}">
<head>
	<link href="/art/aurora_log_v2_orange.png" rel="shortcut icon" type="image/x-icon" />

	<title>{t}Set up{/t}</title>
	<link href="/css/font-awesome.min.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/login.css"> 
	<script type="text/javascript" src="/js/jquery.min.js"></script> 

	<script type="text/javascript" src="/js/login.setup.js"></script> 
</head>
<body class="align">
<div class="site__container ">
	<div class="grid__container">
		<div class="branding">
			<div class="text--center">
				<img class="logo " src="art/aurora_log_v2_orange.png">
			</div>
			<div class="text--center brand" >
				aurora
			</div>
		</div>
		<form class="form form--login" name="login_form" id="login_form" method="post" autocomplete="off" action="setup.php">
			
			
			<div class="form__field">
				<label for="login__password" title="{t}Key{/t}" ><i class="fa fa-key fa-fw"></i> <span class="hidden"></span></label> 
				<input id="login__password" name="key" type="text" class="form__input" placeholder="{t}Key{/t}" required > 
			</div>
			<div class="form__field">
				<input type="submit" value="{t}Set up{/t}"> 
			</div>
		</form>
		<div id="error_message" class="text--center error" style="visibility:{if $error==1}visible{else}hidden{/if}">
		    {t}Invalid key{/t}
		</div>
	</div>
</div>

</body>
</html>
