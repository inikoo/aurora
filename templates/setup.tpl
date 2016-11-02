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
        <link href="/css/d3fc.css" rel="stylesheet">
        <link href="/css/backgrid.css" rel="stylesheet">
        <link href="/css/backgrid-filter.css" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
        <script type="text/javascript" src="/js/libs/jquery-2.2.1.js"></script>
        <script type="text/javascript" src="/js/libs/jquery-ui.js"></script>
        <script type="text/javascript" src="/js/libs/moment-with-locales.js"></script>
        <script type="text/javascript" src="/js/libs/chrono.js"></script>
        <script type="text/javascript" src="/js/libs/sha256.js"></script>
        <script type="text/javascript" src="/js/libs/underscore.js"></script>
        <script type="text/javascript" src="/js/libs/backbone.js"></script>
        <script type="text/javascript" src="/js/libs/backbone.paginator.js"></script>
        <script type="text/javascript" src="/js/libs/backgrid.js"></script>
        <script type="text/javascript" src="/js/libs/backgrid-filter.js"></script>
        <script type="text/javascript" src="/js/libs/intlTelInput.js"></script>
        <script type="text/javascript" src="/js/libs/d3.js"></script>
        <script type="text/javascript" src="/js/libs/d3fc.layout.js"></script>
        <script type="text/javascript" src="/js/libs/d3fc.js"></script>
        <script type="text/javascript" src="/js/keyboard_shorcuts.js"></script>
        <script type="text/javascript" src="/js/search.js"></script>
        <script type="text/javascript" src="/js/table.js"></script>
        <script type="text/javascript" src="/js/validation.js"></script>
        <script type="text/javascript" src="/js/edit.js"></script>
        <script type="text/javascript" src="/js/new.js"></script>
        <script type="text/javascript" src="/js/help.js"></script>
        <script type="text/javascript" src="/js/setup/setup.js"></script>
    {else}
        <link href="/build/css/libs.min.css" rel="stylesheet">
        <link href="/build/css/app.min.css" rel="stylesheet">
        <script type="text/javascript" src="/utils/country_data.js.php?locale={$locale}"></script>
        <script type="text/javascript" src="/build/js/libs.min.js"></script>
        <script type="text/javascript" src="/build/js/app.min.js"></script>
        <script type="text/javascript" src="/build/js/setup.min.js"></script>
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
