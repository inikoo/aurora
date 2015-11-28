<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_code}">
<head>
	<link href="/art/aurora_log_v2_orange.png" rel="shortcut icon" type="image/x-icon" />

	<title>{t}Inikoo Login{/t}</title>
	<link href="/css/font-awesome.min.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/login.css"> 
	<script type="text/javascript" src="/js/jquery.min.js"></script> 
	<script type="text/javascript" src="/js/sha256.js"></script> 
	<script type="text/javascript" src="/js/aes.js"></script> 
	<script type="text/javascript" src="/js/login.js"></script> 
</head>
<body class="align">
<div class="site__container">
	<div class="grid__container">
		<div style="margin-bottom:30px">
			<div class="text--center">
				<img style="width:150px" src="art/aurora_log_v2_purple.png">
			</div>
			<div class="text--center" style="font-size:30px;
			background: -webkit-linear-gradient(#FDD017, #D4A017);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
			">
				aurora
			</div>
		</div>
		<form class="form form--login" name="login_form" id="login_form" method="post" autocomplete="off" action="authorization.php">
			<input type="hidden" id="ep" name="ep" value="{$st}" />
			<div class="form__field">
				<label for="login__username"><i class="fa fa-user fa-fw"></i> <span class="hidden">{t}Username{/t}</span></label> 
				<input name="login__username" id="login__username" type="text" class="form__input" placeholder="Username" required> 
			</div>
			<div class="form__field">
				<label for="login__password"><i class="fa fa-lock fa-fw"></i> <span class="hidden">{t}Password{/t}</span></label> 
				<input id="login__password" type="password" class="form__input" placeholder="Password" required> 
			</div>
			<div class="form__field">
				<input type="submit" value="{t}Log In{/t}"> 
			</div>
		</form>
		<div id="error_message" class="text--center error" style="visibility:{if $error==1}visible{else}hidden{/if}">
		    {t}Was not possible to log in with these credentials{/t}
		</div>
	</div>
</div>

</body>
</html>
