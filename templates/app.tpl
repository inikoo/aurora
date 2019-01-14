<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>

    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>


    {if $_DEVEL}



{*
        <link href="/css/jquery-ui.css" rel="stylesheet">
        <link href="/css/fa/fontawesome-all.css?v5.5" rel="stylesheet">
        <link href="/css/intlTelInput.css" rel="stylesheet">
        <link href="/css/countrySelect.css" rel="stylesheet">
        <link href="/css/d3fc.css" rel="stylesheet">
        <link href="/css/backgrid.css?v=211917" rel="stylesheet">
        <link href="/css/backgrid-filter.css" rel="stylesheet">




        <link href="/css/editor_v1/froala_editor.css?v2" rel="stylesheet">
        <link href="/css/editor_v1/froala_style.css?v2" rel="stylesheet">
        <link href="/css/editor_v1/codemirror.css" rel="stylesheet">
        <link href="/css/editor_v1/codemirror_dracula.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/char_counter.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/code_view.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/colors.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/emoticons.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/file.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/fullscreen.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/image.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/image_manager.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/line_breaker.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/quick_insert.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/table.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/video.css" rel="stylesheet">
        <link href="/css/editor_v1/plugins/draggable.css" rel="stylesheet">
        <link href="/css/amcharts/style.css" rel="stylesheet">
        <link href="/css/jquery.fancybox.min.css" rel="stylesheet">
        <link href="/css/tooltipster.bundle.min.css" rel="stylesheet" type="text/css">
        <link href="/css/app.css?v181122" rel="stylesheet">
  /*}


        <link href="/css/au_app.min.css?v181228v3" rel="stylesheet">


        {if $_server_name!='au.bali'}
        <script src="https://browser.sentry-cdn.com/4.3.4/bundle.min.js" crossorigin="anonymous"></script>
        {/if}

        <script src="/js/libs/jquery-3.3.1.min.js"></script>
        <script src="/js/libs/jquery-migrate-3.0.1.js"></script>
        <script src="/js/libs/jquery-ui.1.12.1.js"></script>

        <script src="/js/libs/moment-with-locales.js"></script>
        <script src="/js/libs/chrono.js"></script>
        <script src="/js/libs/sha256.js"></script>
        <script src="/js/libs/underscore.min.js"></script>
        <script src="/js/libs/backbone.min.js"></script>
        <script src="/js/libs/backbone.paginator.js"></script>
        <script src="/js/libs/backgrid.js"></script>
        <script src="/js/libs/backgrid-filter.js"></script>
        <script src="/js/libs/snap.svg.js"></script>
        <script src="/js/libs/svg-dial.js"></script>
        <script src="/js/libs/countrySelect.js?v181115"></script>
        <script src="/js/libs/intlTelInput-jquery.14.0.6.js"></script>
        <script src="/js/libs/d3.js"></script>
        <script src="/js/libs/d3fc.layout.js"></script>
        <script src="/js/libs/d3fc.js"></script>
        <script src="/js/libs/sweetalert.min.js?v2a"></script>

        <script src="/js/libs/tooltipster.bundle.min.js"></script>
        <script src="/js/libs/jquery-qrcode-0.14.0.min.js"></script>


        <script src="/js/help.js?v181115"></script>
        <script src="/js/keyboard_shortcuts.js?v181115"></script>
        <script src="/js/barcode_scanner.js?v181115"></script>

        <script src="/js/app.js?v181122bis"></script>
        <script src="/js/search.js"></script>
        <script src="/js/table.js?v201808v2"></script>
        <script src="/js/validation.js?v171206v6v2"></script>
        <script src="/js/pdf.js?v1800612v2"></script>
        <script src="/utils/country_data.js.php?v=v181115&locale={$locale}"></script>
        <script src="/js/edit.js?v181115"></script>
        <script src="https://wchat.freshchat.com/js/widget.js"></script>
    {if $user->get('User Type')=='Staff' or $user->get('User Type')=='Contractor'}

        <script src="/js/edit_webpage_edit.js?v181115"></script>
        <script src="/js/new.js?v181123"></script>
        <script src="/js/order.common.js?v181218v1"></script>
        <script src="/js/email_campaign.common.js?v1901"></script>
        <script src="/js/supplier.order.js?v181115"></script>
        <script src="/js/supplier.delivery.js?v181218v1"></script>
        <script src="/js/part_locations.edit.js?v=v181115v2"></script>
        <script src="/js/part_locations.edit_locations.js"></script>
        <script src="/js/part_locations.stock_check.js"></script>
        <script src="/js/part_locations.move_stock.js"></script>
        <script src="/js/picking_and_packing.js?v2"></script>
        <script src="/js/alert_dial.js?v181122"></script>
        <script src="/js/libs/editor_v1/froala_editor.min.js?v1"></script>
        <script src="/js/libs/editor_v1/codemirror.js"></script>
        <script src="/js/libs/editor_v1/codemirror.xml.js"></script>
        <script src="/js/libs/editor_v1/codemirror_active-line.js"></script>
        <script src="/js/libs/editor_v1/plugins/align.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/draggable.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/char_counter.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/code_beautifier.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/code_view.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/colors.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/emoticons.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/entities.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/file.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/font_family.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/font_size.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/fullscreen.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/image.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/image_manager.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/inline_style.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/line_breaker.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/link.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/lists.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/paragraph_format.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/paragraph_style.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/quick_insert.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/quote.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/table.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/save.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/url.min.js"></script>
        <script src="/js/libs/editor_v1/plugins/video.min.js"></script>
        <script src="/js/libs/amcharts/amcharts.js"></script>
        <script src="/js/libs/amcharts/serial.js"></script>
        <script src="/js/libs/amcharts/amstock.js"></script>
        <script src="/js/libs/amcharts/plugins/dataloader/dataloader.min.js"></script>
        <script src="/js/libs/amcharts/plugins/export/export.min.js"></script>

        {elseif $user->get('User Type')=='Agent' }
        <script src="/js/agent.order.js?v181115"></script>

        {/if}

        <script src="/js/libs/jquery.scannerdetection.js?v181115"></script>
        <script src="/js/libs/jquery.fancybox.min.js"></script>
        <script src="/js/libs/jquery.awesome-cursor.min.js"></script>
        <script src="/js/libs/base64.js?v2"></script>
        <script src="/js/libs/jquery.formatCurrency-1.4.0.min.js"></script>
        <script src="/js/libs/autobahn.v1.js"></script>





    {else}
        <link href="/build/css/libs.min.css" rel="stylesheet">
        <link href="/build/css/app.min.css" rel="stylesheet">
        <script src="/build/js/libs.js"></script>
        <script src="/build/js/app.js"></script>
        <script src="/utils/country_data.js.php?locale={$locale}"></script>
    {/if}


        <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js"></script>


</head>
<body>
<input type="hidden" id="_request" value="{$_request}">
<input type="hidden" id="_server_name" value="{$_server_name}">



{if $_server_name!='au.bali'}

    <script>
        (function(i,s,o,g,r,a,m){
            i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-100778677-2', 'auto');
        ga('set', 'userId', '{$account->get('Code')}_{"%05d"|sprintf:$user->id}');
        ga('send', 'pageview');

        Sentry.init({ dsn: 'https://8f17945abb95493692010f7026553f71@sentry.io/1329970' });

        Sentry.configureScope((scope) => {
            scope.setUser({
            "id": "{$user->id}",
            "username": "{$user->get('Alias')}",
            "email": "{$user->get_staff_email()}"
            });
        });

    </script>


{/if}



<div id="top_bar">
    <div id="view_position">
    </div>
</div>
<input  type="hidden" id="_labels" data-labels='{ "error":"{t}Error{/t}"}' >

<div class="grid">
    <section>
        <div id="app_leftmenu">
            <div id="top_info">
                <div id="aurora_logo" class="link" onclick="help()">
                    <img src="/art/aurora_log_v2_orange_small.png"/>
                </div>
                <div id="hello_user" class="link"  data-user_key="{$user->id}"  onclick="change_view('profile')">
                    {$user->get('User Alias')}
                </div>
            </div>
            <div id="account_name" class="link Account_Name"  data-account_code="{$account->get('Account Code')}"  onclick="change_view('account')">{$account->get('Account Name')}</div>

            <div id="aurora_logo_small_screen">
                <img src="/art/aurora_log_v2_orange_small.png"/>
            </div>


            <div id="menu">
            </div>

                <ul style="margin-top:5px">
                    {if $user->get('User Type')=='Staff' or $user->get('User Type')=='Contractor' }
                    <li onclick="change_view('/fire')"><i class="fa fa-fire fa-fw" style="color:orange;opacity:.8"></i><span id="fire_label" class="label"> {t}Fire{/t}</span>
                    </li>
                    {/if}
                    <li title="{t}Share screen{/t}" ><a href="https://get.teamviewer.com/txww6bm" target="_blank"><i class="far fa-desktop fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label" class="label"> {t}Share screen{/t}</span></a>
                    </li>
                    <li title="{t}Help{/t}" onclick="window.fcWidget.open(); return false;" ><i class="fas fa-headset fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label" class="label"> {t}Online chat{/t}</span>
                    </li>
                    <li title="{t}Help{/t}" onclick="FreshWidget.show(); return false;" ><i class="fal fa-hands-helping fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label" class="label"> {t}Help{/t}</span>
                    </li>
                </ul>





            <ul style="margin-top:20px">
                <li onclick="logout()"><i title="{t}Logout{/t}" class="fa fa-sign-out fa-fw fa-flip-horizontal"></i><span id="logout_label" class="label" > {t}Logout{/t}</span>
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
    <aside id="notifications" data-current_side_view="{$_side_block}" >
        <div class="top_buttons">

            <div id="whiteboard_button" onclick="show_side_content('whiteboard')" class="side_content_icon square_button {if $_side_block='whiteboard'}selected{/if}" title="{t}Whiteboard{/t}">
                <i class="fa fa-edit fa-fw  "></i>
            </div>
            <div id="help_button" onclick="show_side_content('help')" class="side_content_icon square_button {if $_side_block=='help'}selected{/if}" title="{t}Help{/t}">
                <i class="fa fa-question-circle fa-fw  "></i>
            </div>


            <div style="clear:both"></div>
        </div>
        <div id="help" class="side_content {if $_side_block!='help'}hide{/if}">
            <div class="top">
                {t}Help{/t}
            </div>
            <div class="navigation">
                <span class="help_title"></span>
            </div>
            <div class="content">
            </div>
        </div>
        <div id="whiteboard" class="side_content {if $_side_block!='whiteboard'}hide{/if}">
            <div class="top">
                {t}Whiteboard{/t}
            </div>
            <div class="navigation">
                <span id="whiteboard_content_title" style="font-size:90%;padding-left:5px" class="help_title">{t}Page{/t}</span>
            </div>
            <div id="whiteboard_content" data-block="page" class="content" style="min-height: 200px;padding:10px" contenteditable="true"  ></div>
            <div class="navigation">
                <span id="whiteboard_content_tab_title" style="font-size:90%;padding-left:5px" class="help_title">{t}Tab{/t}</span>
            </div>
            <div id="whiteboard_content_tab"  data-block="tab"  class="content" style="min-height: 300px;padding:10px" contenteditable="true"  ></div>



        </div>

    </aside>
</div>

{if $_server_name!='au.bali'}

    <style>
        #freshwidget-frame,#fc_widget{
            background-color:initial
        }
        .d-hotline.h-btn    {
            opacity:.5;transform: scale(.5) !important;
        }


    </style>
<script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
<script type="text/javascript">
    FreshWidget.init("", {
        "queryString": "{if $user->get('User Password Recovery Email')!=''}helpdesk_ticket[requester]={$user->get('User Password Recovery Email')}&disable[requester]=true{/if}&widgetType=popup&submitTitle=Submit+ticket", "utf8": "✓", "widgetType": "popup", "buttonType": "text", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#006063", "alignment": "3", "offset": "-1000px", "formHeight": "580px", "url": "https://inikoo.freshdesk.com"} );
</script>
<script>
    window.fcWidget.init({
        token: "869f028d-7877-4611-a735-7021ae47fab0",
        host: "https://wchat.freshchat.com",
        config: {
            headerProperty: {
                hideChatButton: true
            }
        },
    });


    {if $user->get('User Password Recovery Email')!=''}
    window.fcWidget.user.setEmail("{$user->get('User Password Recovery Email')}");
    {/if}


    window.fcWidget.setExternalId("{$account->get('Code')}.{$user->get('Handle')}");

    window.fcWidget.user.setFirstName("{$user->get('Alias')}");
</script>
{/if}
</body>
</html>
