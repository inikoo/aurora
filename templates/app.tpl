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
        <link href="/css/font-awesome-animation.css" rel="stylesheet">



        <link href="/css/intlTelInput.css" rel="stylesheet">
        <link href="/css/countrySelect.css" rel="stylesheet">
        <link href="/css/d3fc.css" rel="stylesheet">
        <link href="/css/backgrid.css" rel="stylesheet">
        <link href="/css/backgrid-filter.css" rel="stylesheet">
        <link href="/css/editor/froala_editor.css" rel="stylesheet">
        <link href="/css/editor/froala_style.css" rel="stylesheet">
        <link href="/css/editor/codemirror.css" rel="stylesheet">
        <link href="/css/editor/codemirror_dracula.css" rel="stylesheet">
        <link href="/css/editor/plugins/char_counter.css" rel="stylesheet">
        <link href="/css/editor/plugins/code_view.css" rel="stylesheet">
        <link href="/css/editor/plugins/colors.css" rel="stylesheet">
        <link href="/css/editor/plugins/emoticons.css" rel="stylesheet">
        <link href="/css/editor/plugins/file.css" rel="stylesheet">
        <link href="/css/editor/plugins/fullscreen.css" rel="stylesheet">
        <link href="/css/editor/plugins/image.css" rel="stylesheet">
        <link href="/css/editor/plugins/image_manager.css" rel="stylesheet">
        <link href="/css/editor/plugins/line_breaker.css" rel="stylesheet">
        <link href="/css/editor/plugins/quick_insert.css" rel="stylesheet">
        <link href="/css/editor/plugins/table.css" rel="stylesheet">
        <link href="/css/editor/plugins/video.css" rel="stylesheet">
        <link href="/css/editor/plugins/draggable.css" rel="stylesheet">

        <link href="/css/amcharts/style.css" rel="stylesheet">
        <link href="/css/sweetalert.css" rel="stylesheet">


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
        <script type="text/javascript" src="/js/libs/snap.svg.js"></script>
        <script type="text/javascript" src="/js/libs/svg-dial.js"></script>
        <script type="text/javascript" src="/js/libs/countrySelect.js"></script>
        <script type="text/javascript" src="/js/libs/intlTelInput.js"></script>
        <script type="text/javascript" src="/js/libs/d3.js"></script>
        <script type="text/javascript" src="/js/libs/d3fc.layout.js"></script>
        <script type="text/javascript" src="/js/libs/d3fc.js"></script>
        <script type="text/javascript" src="/js/libs/sweetalert.min.js"></script>



        <script type="text/javascript" src="/js/app.js"></script>
        <script type="text/javascript" src="/js/keyboard_shortcuts.js"></script>
        <script type="text/javascript" src="/js/barcode_scanner.js"></script>
        <script type="text/javascript" src="/js/search.js"></script>
        <script type="text/javascript" src="/js/table.js"></script>
        <script type="text/javascript" src="/js/validation.js"></script>
        <script type="text/javascript" src="/js/edit.js"></script>
        <script type="text/javascript" src="/js/new.js"></script>
        <script type="text/javascript" src="/js/order.common.js"></script>
        <script type="text/javascript" src="/js/supplier.order.js"></script>
        <script type="text/javascript" src="/js/supplier.delivery.js"></script>
        <script type="text/javascript" src="/js/part_locations.edit.js"></script>
        <script type="text/javascript" src="/js/alert_dial.js"></script>
        <script type="text/javascript" src="/js/help.js"></script>
        <script type="text/javascript" src="/utils/country_data.js.php?locale={$locale}"></script>
        <script type="text/javascript" src="/js/libs/editor/froala_editor.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/codemirror.js"></script>
        <script type="text/javascript" src="/js/libs/editor/codemirror.xml.js"></script>
        <script type="text/javascript" src="/js/libs/editor/codemirror_active-line.js"></script>

        <script type="text/javascript" src="/js/libs/editor/plugins/align.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/draggable.min.js"></script>

        <script type="text/javascript" src="/js/libs/editor/plugins/char_counter.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/code_beautifier.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/code_view.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/colors.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/emoticons.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/entities.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/file.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/font_family.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/font_size.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/fullscreen.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/image.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/image_manager.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/inline_style.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/line_breaker.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/link.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/lists.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/paragraph_format.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/paragraph_style.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/quick_insert.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/quote.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/table.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/save.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/url.min.js"></script>
        <script type="text/javascript" src="/js/libs/editor/plugins/video.min.js"></script>
        <script type="text/javascript" src="/js/libs/amcharts/amcharts.js"></script>
        <script type="text/javascript" src="/js/libs/amcharts/serial.js"></script>
        <script type="text/javascript" src="/js/libs/amcharts/amstock.js"></script>
        <script type="text/javascript" src="/js/libs/amcharts/plugins/dataloader/dataloader.min.js"></script>
        <script type="text/javascript" src="/js/libs/amcharts/plugins/export/export.min.js"></script>
        <script type="text/javascript" src="/js/libs/jquery.scannerdetection.js"></script>




    {else}
        <link href="/build/css/libs.min.css" rel="stylesheet">
        <link href="/build/css/app.min.css" rel="stylesheet">
        <script type="text/javascript" src="/build/js/libs.js"></script>
        <script type="text/javascript" src="/build/js/app.js"></script>
        <script type="text/javascript" src="/utils/country_data.js.php?locale={$locale}"></script>
    {/if}

    {if false}
        <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js" type="text/javascript"></script>
    {/if}
</head>
<body>
<input type="hidden" id="_request" value="{$_request}">
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
                <div id="hello_user" class="link" onclick="change_view('profile')">
                    {$user->get('User Alias')}
                </div>
            </div>
            <div id="account_name" class="link Account_Name"
                 onclick="change_view('account')">{$account->get('Account Name')}</div>

            <div id="aurora_logo_small_screen">
                <img src="/art/aurora_log_v2_orange_small.png"/>
            </div>

            <div id="menu">
            </div>
            {if $user->get('User Type')=='Staff' }
                <ul style="margin-top:5px">
                    <li onclick="change_view('/fire')"><i class="fa fa-fire fa-fw" style="color:orange;opacity:.8"></i><span id="fire_label" class="label"> {t}Fire{/t}</span>
                    </li>
                </ul>
            {/if}
            <ul style="margin-top:20px">
                <li onclick="logout()"><i class="fa fa-sign-out fa-fw"></i><span id="logout_label" class="label"> {t}Logout{/t}</span>
                </li>
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
        <div class="top_buttons">


            <div id="help_button" onclick="show_help()" class="square_button {if $show_help}selected{/if}"
                 title="{t}Help{/t}">
                <i class="fa fa-question-circle fa-fw  "></i>
            </div>

            <div style="clear:both"></div>
        </div>
        <div id="help" class="{if !$show_help}hide{/if}">
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
</div>
</body>
</html>
