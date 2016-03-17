{strip} 
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<link href="/art/aurora_log_v2_orange.png" rel="shortcut icon" type="image/x-icon" />

	<title>{t}Login{/t}</title>
	<link href="/build/css/libs.min.css" rel="stylesheet">
	<link href="/build/css/login.min.css" rel="stylesheet">
	<script type="text/javascript" src="/build/js/login.min.js"></script>

	 
</head>
<body class="align">
<div class="site__container">
	<div class="grid__container">
		<div class="branding">
			<div class="text--center">
				<img id="logo"  src="art/aurora_log_v2_orange.png">
			</div>
			<div class="text--center brand">aurora</div>
		</div>
		<form class="form form--login" name="login_form" id="login_form" method="post" autocomplete="off" action="authorization.php">
			<input type="hidden" id="ep" name="ep" value="{$st}" />
			<input type="hidden" name="url" value="{$url}" />

			<div class="form__field">
				<label for="login__username" title="{t}Username{/t}"><i class="fa fa-user fa-fw"></i> <span class="hidden"></span></label> 
				<input name="login__username" id="login__username" type="text" class="form__input" placeholder="{t}Username{/t}" required> 
			</div>
			<div class="form__field">
				<label for="login__password" title="{t}Password{/t}"><i class="fa fa-lock fa-fw"></i> <span class="hidden"></span></label> 
				<input id="login__password" type="password" class="form__input" placeholder="{t}Password{/t}" required> 
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
{/strip}