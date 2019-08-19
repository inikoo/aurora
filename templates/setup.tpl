<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>
    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>


    <link href="/assets/au_app.min.css?v=190712" rel="stylesheet">


    <script src="/assets/aurora_libs.min.js?v190701"></script>

    <script src="/assets/aurora_setup.min.js?v190817"></script>

    <script src="/utils/country_data.js.php?v=v190124&locale={$locale}"></script>








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
            <div id="account_name" class="link Account_Name">

            </div>


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
