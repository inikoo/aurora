{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2016 at 23:25:56 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<!doctype html>

<html lang="en" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Aurora.systems manual">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Aurora.Systems</title>

    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="art/aurora-desktop.png">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Aurora.systems help">
    <link rel="apple-touch-icon-precomposed" href="art/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="art/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="art/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="css/font.Roboto.css">
    <link rel="stylesheet" href="css/font.MaterialIcons.css">
    <link rel="stylesheet" href="css/material.blue_grey-deep_orange.css">
    <link rel="stylesheet" href="css/app.mobile.css">
    <style>
        .mdl-layout {
            align-items: center;
            justify-content: center;
        }
        .mdl-layout__content {
            padding: 24px;
            flex: none;
        }
    </style>
</head>
<body >


<div class="mdl-layout mdl-js-layout mdl-color--grey-100" >
    <main class="mdl-layout__content" >
        <div class="mdl-card mdl-shadow--6dp">
            <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                <h2 class="mdl-card__title-text">{$account_code}@aurora.systems</h2>
            </div>
            <div class="mdl-card__supporting-text">

                    <form class="form form--login" name="login_form" id="login_form" method="post" autocomplete="off" action="authorization.php">
                        <input type="hidden" id="blow_fish"  value="{$st}"/>
                        <input type="hidden" id="token" name="token" value=""/>
                        <input type="hidden" name="url" value="{$url}"/>

                    <div class="mdl-textfield mdl-js-textfield">
                        <input class="mdl-textfield__input" type="text" id="login__username" name="login__username"  />
                        <label class="mdl-textfield__label" for="username">{t}Username{/t}</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield">
                        <input class="mdl-textfield__input" type="password" id="login__password" />
                        <label class="mdl-textfield__label" for="userpass">{t}Password{/t}</label>
                    </div>
                </form>
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <button onclick="on_my_Submit()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">{t}Log in{/t}</button>
            </div>
        </div>
    </main>

</div>
<div id="notification" class="mdl-js-snackbar mdl-snackbar"> <div class="mdl-snackbar__text"></div> <button class="mdl-snackbar__action" type="button"></button> </div>
<span class="hide" id="error_message" error="{$error}">{t}Was not possible to log in with these credentials{/t}</span>

<input id="_request" type="hidden" val="{$_request}">
<script src="js/libs/material.min.js"></script>
<script src="js/libs/jquery-2.2.1.js"></script>
<script type="text/javascript" src="js/libs/sha256.js"></script>
<script type="text/javascript" src="js/libs/aes.js"></script>
<script src="js/login/login.js"></script>

<script src="js/login/login.mobile.js"></script>


</body>
</html>