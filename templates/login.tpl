<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link href="/art/aurora_log_v2_orange.png" rel="shortcut icon" type="image/x-icon"/>
    <title>{t}Login{/t}</title>

    <link href="/assets/login.min.css" rel="stylesheet">


    <script src="https://browser.sentry-cdn.com/5.4.0/bundle.min.js" crossorigin="anonymous">
    </script>
    {if !$is_devel}
    <script>
        Sentry.init({
            dsn: 'https://6b74919f310546d2a64bbf7c856d0820@sentry.io/1482169'});
    </script>
    {/if}

    <script src="/assets/login_libs.min.js"></script>
    <script src="/assets/login.min.js"></script>


</head>
<body class="align">
<div class="site__container">
    <div class="grid__container">
        <div class="branding">
            <div class="text--center">
                <img id="logo" src="art/aurora_log_v2_orange.png">
            </div>
            <div class="text--center brand">
                aurora
            </div>
        </div>
        <form class="form form--login" name="login_form" id="login_form" method="post"  action="authorization.php">
            <input type="hidden" id="blow_fish" value="{$st}"/>
            <input type="hidden" id="token" name="token" value=""/>
            <input type="hidden" name="url" value="{$url}">
            <input type="hidden" id="timezone" name="timezone" value="">

            <div class="form__field">
                <label for="login__username" title="{t}Username{/t}"><i class="fa fa-user fa-fw"></i> <span class="hidden"></span></label>
                <input name="login__username" id="login__username" type="text" class="form__input" placeholder="{t}Username{/t}" required>
            </div>
            <div class="form__field">
                <label for="login__password" title="{t}Password{/t}"><i class="fa fa-lock fa-fw"></i> <span class="hidden"></span></label>
                <input id="login__password" type="password" class="form__input" placeholder="{t}Password{/t}" required>
            </div>
            <div class="form__field">
                <button onclick="on_my_Submit()">{t}Log In{/t}</button>
            </div>
        </form>
        <div id="error_message" class="text--center error"
             style="visibility:{if $error==1}visible{else}hidden{/if}">
            {t}Was not possible to log in with these credentials{/t}
        </div>
    </div>
</div>


<script>
    $.backstretch('{$bg_image}');
</script>


</body>
</html>
