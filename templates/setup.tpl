<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>
    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>


    {if $_DEVEL}
        <link href="/css/jquery-ui.css" rel="stylesheet">
        <link href="/css/font-awesome.css" rel="stylesheet">
        <link href="/css/intlTelInput.css" rel="stylesheet">

        <link href="/css/countrySelect.css" rel="stylesheet">

        <link href="/css/d3fc.css" rel="stylesheet">
        <link href="/css/backgrid.css" rel="stylesheet">
        <link href="/css/backgrid-filter.css" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
        <script src="/js/libs/jquery-2.2.1.js"></script>
        <script src="/js/libs/jquery-ui.js"></script>
        <script src="/js/libs/moment-with-locales.js"></script>
        <script src="/js/libs/chrono.js"></script>
        <script src="/js/libs/sha256.js"></script>
        <script src="/js/libs/underscore.js"></script>
        <script src="/js/libs/backbone.js"></script>
        <script src="/js/libs/backbone.paginator.js"></script>
        <script src="/js/libs/backgrid.js"></script>
        <script src="/js/libs/backgrid-filter.js"></script>
        <script src="/js/libs/intlTelInput-jquery.14.0.6.js"></script>
        <script src="/js/libs/d3.js"></script>
        <script src="/js/libs/d3fc.layout.js"></script>
        <script src="/js/libs/d3fc.js"></script>
        <script src="/js/keyboard_shortcuts.js"></script>
        <script src="/js/search.js"></script>
        <script src="/js/table.js"></script>
        <script src="/js/validation.js"></script>
        <script src="/js/edit.js"></script>
        <script src="/js/new.js"></script>
        <script src="/js/help.js"></script>
        <script src="/js/setup/setup.js"></script>

        <script src="/js/libs/countrySelect.js"></script>

        <script src="/utils/country_data.js.php?locale={$locale}"></script>


    {else}
        <link href="/build/css/libs.min.css" rel="stylesheet">
        <link href="/build/css/app.min.css" rel="stylesheet">
        <script src="/js/libs/countrySelect.js"></script>

        <script src="/utils/country_data.js.php?locale={$locale}"></script>
        <script src="/build/js/libs.min.js"></script>
        <script src="/build/js/app.min.js"></script>
        <script src="/build/js/setup.min.js"></script>
    {/if}


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
                    <img src="/art/aurora_log_v2_orange_small.png"/>
                </div>
                <div id="hello_user" class="link">

                </div>
            </div>
            <div id="account_name" class="link Account_Name"></div>


            <div id="aurora_logo_small_screen">
                <img src="/art/aurora_log_v2_orange_small.png"/>
            </div>

            <div id="menu">
            </div>
            <ul style="margin-top:20px" class="">
                <li onclick="logout()"><i class="fa fa-sign-out fa-fw"></i> <span id="logout_label" class="label">{t}
                        Exit{/t}</li>
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
        <aside id="notifications">

            <div id="help">
                <div class="top">
                    {t}Help{/t}
                </div>
                <div class="navigation">
                    <span class="help_title"></span>
                </div>
                <div class="content">
                </div>
            </div>

        </aside>
    </aside>
</div>
</body>
</html>
