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

<html lang="en">
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
    <link href="/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/font.Roboto.css">
    <link rel="stylesheet" href="/css/font.MaterialIcons.css">
    <link rel="stylesheet" href="/css/material.blue_grey-deep_orange.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/app.mobile.css">

    <style>
        #view-source {
            position: fixed;
            display: block;
            right: 0;
            bottom: 0;
            margin-right: 40px;
            margin-bottom: 40px;
            z-index: 900;
        }
    </style>
</head>
<body>

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span id="title" class="mdl-layout-title"></span>
            <div class="mdl-layout-spacer"></div>
            <div id="search_container" class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right">
                <label class="mdl-button mdl-js-button mdl-button--icon" for="fixed-header-drawer-exp">
                    <i class="material-icons">search</i>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <input class="mdl-textfield__input" type="text" name="sample" id="fixed-header-drawer-exp">
                </div>
            </div>
        </div>
    </header>
    <div id="menu" class="mdl-layout__drawer">

    </div>

    <main id="search_results" class="hide  mdl-layout__content">


        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--4-col">
                <span id="no_results_msg" class="padding_left_20 discreet hide" onclick="close_search_results()">
                    <button class="button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" v>
  <i class="material-icons">clear</i>

</button>
                    <span class="padding_left_10">{t}No results found{/t} </span>
                </span>
                <span id="results_msg" class="padding_left_20 discreet hide" onclick="close_search_results()">
                    <button class="button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" v>
  <i class="material-icons">clear</i>

</button>
                    <span class="padding_left_10">{t}clear results{/t} </span>
                </span>
            </div>
            <div class="mdl-cell mdl-cell--4-col" style="margin-top:0px">


                <ul id="results" class=" mdl-list" style="margin-top:0px">


                </ul>

            </div>

        </div>


        <ul class="hide">
            <li style="border-top:1px solid #ccc;margin-top:0px;padding-top:0px" id="search_result_template" class="hide mdl-list__item" view="" onClick="go_to_search_result(this.getAttribute('view'))"><span
                        class="mdl-list__item-primary-content">
                    <span class="label"></span>
                    <span class="details"></span>

                </span></li>

        </ul>


    </main>

    <main id="content" class="mdl-layout__content">


    </main>
</div>
<input id="_request" type="hidden" value="{$_request}">

<script src="/js/libs/material.min.js"></script>
<script src="/js/libs/jquery-2.2.1.js"></script>
<script src="/js/libs/jquery.mobile-1.4.5.js"></script>

<script src="/js/app.mobile.js"></script>
<script src="/js/keyboard_shorcuts.js"></script>
<script src="/js/search.mobile.js"></script>


</body>
</html>