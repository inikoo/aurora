{*/*
Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 23:15:29 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo
 Version 3.0
*/*}
{strip}
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link href="/art/aurora_log_v2_orange.png" rel="shortcut icon" type="image/x-icon"/>

    <title>{t}Set up{/t}</title>


    <link href="/css/login.min.css?v=3" rel="stylesheet">


    <script src="https://browser.sentry-cdn.com/5.4.0/bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
        Sentry.init({
            dsn: 'https://6b74919f310546d2a64bbf7c856d0820@sentry.io/1482169'});
    </script>


    <script src="js/libs/jquery-3.3.1.min.js"></script>
    <script src="js/libs/sha256.js"></script>
    <script src="js/libs/aes.js"></script>
    <script src="/js/libs/base64.js"></script>




</head>
<body class="align">
<div class="site__container ">
    <div class="grid__container">
        <div class="branding">
            <div class="text--center">
                <img class="logo " src="art/aurora_log_v2_orange.png">
            </div>
            <div class="text--center brand">
                aurora
            </div>
        </div>
        <form class="form form--login" name="login_form" id="login_form" method="post" autocomplete="off"
              action="setup.php">


            <div class="form__field">
                <label for="login__password" title="{t}Key{/t}"><i class="fa fa-key fa-fw"></i> <span
                            class="hidden"></span></label>
                <input id="login__password" name="key" type="text" class="form__input" placeholder="{t}Key{/t}"
                       required>
            </div>
            <div class="form__field">
                <button onclick="document.getElementById("loginform").submit()">{t}Set up{/t}</button>
            </div>
        </form>
        <div id="error_message" class="text--center error" style="visibility:{if $error==1}visible{else}hidden{/if}">
            {t}Invalid key{/t}
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {

        $("#login__password").focus();


        $("#error_message").animate({
            opacity: 0,
        }, 5000, function () {
            $("#error_message").css('visibility', 'hidden')
        });

    })

</script>

</body>
</html>
{/strip}

