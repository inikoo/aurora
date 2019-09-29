<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>
    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>
    <link href="/assets/au_app.min.css?v=190929" rel="stylesheet">

    {if !$is_devel}
        <script
                src="https://browser.sentry-cdn.com/5.6.3/bundle.min.js"
                integrity="sha384-/Cqa/8kaWn7emdqIBLk3AkFMAHBk0LObErtMhO+hr52CntkaurEnihPmqYj3uJho"
                crossorigin="anonymous"></script>

    {/if}
    <script src="https://d3js.org/d3.v4.min.js"></script>

    <script src="/assets/aurora_libs.min.js?v190910v3"></script>

    <script src="/assets/aurora.min.js?v190924v2"></script>

    <script src="/utils/country_data.js.php?v=v190124&locale={$locale}"></script>


    {if $user->get('User Type')=='Staff' or $user->get('User Type')=='Contractor'}

    {elseif $user->get('User Type')=='Agent' }
        <script src="/js/agent.order.js?v181115"></script>
    {/if}


    <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js" async></script>


</head>
<body  data-labels='{
"save":"{t}Save{/t}",
"undo":"{t}Undo{/t}",
"add":"{t}Add{/t}",
"remove":"{t}Remove{/t}",
"error":"{t}Error{/t}",
"invalid_val":"{t}Invalid value{/t}"

}' class="{$user->get('theme_raw')}">
<input type="hidden" id="_request" value="{$_request}">
<input type="hidden" id="is_devel" value="{$is_devel}">


{if !$is_devel}
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-100778677-2', 'auto');
        ga('set', 'userId', '{$account->get('Code')}_{"%05d"|sprintf:$user->id}');
        ga('send', 'pageview');

        //Sentry.init({ dsn: 'https://6b74919f310546d2a64bbf7c856d0820@sentry.io/1482169' });
        Sentry.init({ dsn: 'https://8f17945abb95493692010f7026553f71@sentry.io/1329970' });


        Sentry.configureScope((scope) => {
            scope
                .setUser({
                    "id":       "{$user->id}",
                    "username": "{$user->get('Alias')}",
                    "email":    "{$user->get_staff_email()}"
                });
        })
        ;

    </script>
{/if}


<div id="top_bar" >
    <div class="timezone_info">{$timezone_info}</div>

    <div id="view_position"></div>

</div>
<input type="hidden" id="_labels" data-labels='{ "error":"{t}Error{/t}"}'>

<div class="grid">
    <section>
        <div id="app_leftmenu">
            <div id="top_info">
                <div id="aurora_logo" class="link" onclick="help()">
                    <img src="/art/aurora_log_v2_orange_small.png"/>
                </div>
                <div id="hello_user" class="link" data-user_key="{$user->id}" onclick="change_view('profile')">
                    {$user->get('User Alias')}
                </div>
            </div>
            <div id="account_name" class="link Account_Name"
                 data-user_handle="{$user->get('Handle')}" data-account_code="{$account->get('Account Code')}" onclick="change_view('account')">{$account->get('Account Name')}
            </div>
            <div id="aurora_logo_small_screen">
                <img src="/art/aurora_log_v2_orange_small.png"/>
            </div>
            <div id="menu"></div>
            <ul style="margin-top:5px">
                {if  $user->can_view('users_reports')  }
                    <li onclick="change_view('/fire')"><i class="fa fa-fire fa-fw" style="color:orange;opacity:.8"></i><span id="fire_label" class="label"> {t}Fire{/t}</span>
                    </li>
                {/if}
                {*
                <li title="{t}Share screen{/t}"><a href="https://get.teamviewer.com/txww6bm" target="_blank"><i class="far fa-desktop fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label"
                                                                                                                                                                                                class="label"> {t}Share screen{/t}</span></a>
                </li>
                 <li class="hide" title="{t}Help{/t}" onclick="window.fcWidget.open(); return false;"><i class="fas fa-headset fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label" class="label"> {t}Online chat{/t}</span>
                </li>
                *}

                <li title="{t}Help{/t}" onclick="FreshWidget.show(); return false;"><i class="fal fa-hands-helping fa-fw" style="color:cornflowerblue;opacity:.75"></i><span id="fire_label"
                                                                                                                                                                             class="label"> {t}Help{/t}</span>
                </li>
            </ul>
            <ul style="margin-top:20px">
                <li onclick="logout()"><i title="{t}Logout{/t}" class="fa fa-sign-out fa-fw fa-flip-horizontal"></i><span id="logout_label" class="label"> {t}Logout{/t}</span>
                </li>

            </ul>

        </div>
        <div id="app_main">
            <div id="navigation"></div>
            <div id="web_navigation"></div>
            <div id="object_showcase"></div>
            <div id="tabs"></div>
            <div id="tab"></div>
            <div style="clear:both;margin-bottom:100px"></div>
        </div>
    </section>
    <aside id="notifications"   data-current_side_view="{$_side_block}">
        <div class="top_buttons">


            <div data-type="help" onclick="show_side_content('help')" class="help_button side_content_icon square_button {if $_side_block=='help'}selected{/if}" title="{t}Help{/t}">
                <i class="fa fa-question-circle fa-fw  "></i>
            </div>

            <div data-type="real_time_users" onclick="show_side_content('real_time_users')" class="real_time_users_button side_content_icon square_button {if $_side_block=='real_time_users'}selected{/if}" title="{t}Real time users{/t}">
                <i class="fa fa-user-circle fa-fw  "></i>
            </div>
            <div style="clear:both"></div>
        </div>


        <div  class="help side_content hide">
            <div class="top">
                {t}Help{/t}
            </div>
            <div class="navigation">
                <span class="help_title"></span>
            </div>
            <div class="content"></div>
        </div>

        <div  class="real_time_users side_content hide">
            <div class="top">
                {t}Active users{/t}
            </div>

            <div class="content">
                <table  class="real_time_users_table ">
                </table>

            </div>
        </div>

        <div  class="whiteboard side_content hide">
            <div class="top">
                {t}Whiteboard{/t}
            </div>
            <div class="navigation">
                <span id="whiteboard_content_title" style="font-size:90%;padding-left:5px" class="help_title">{t}Page{/t}</span>
            </div>
            <div id="whiteboard_content" data-block="page" class="content" style="min-height: 200px;padding:10px" contenteditable="true"></div>
            <div class="navigation">
                <span id="whiteboard_content_tab_title" style="font-size:90%;padding-left:5px" class="help_title">{t}Tab{/t}</span>
            </div>
            <div id="whiteboard_content_tab" data-block="tab" class="content" style="min-height: 300px;padding:10px" contenteditable="true"></div>


        </div>


    </aside>
</div>

{if !$is_devel}
    <style>
        #freshwidget-frame, #fc_widget {
            background-color: initial
        }

        .d-hotline.h-btn {
            opacity: .5;
            transform: scale(.5) !important;
        }


    </style>
    <script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
    <script type="text/javascript">
        FreshWidget.init("", {
            "queryString": "{if $user->get('User Password Recovery Email')!=''}helpdesk_ticket[requester]={$user->get('User Password Recovery Email')}&disable[requester]=true{/if}&widgetType=popup&submitTitle=Submit+ticket",
            "utf8":        "✓",
            "widgetType":  "popup",
            "buttonType":  "text",
            "buttonText":  "Support",
            "buttonColor": "white",
            "buttonBg":    "#006063",
            "alignment":   "3",
            "offset":      "-1000px",
            "formHeight":  "580px",
            "url":         "https://inikoo.freshdesk.com"
        });
    </script>
    {*
    <script>
        window.fcWidget.init({
            token:  "869f028d-7877-4611-a735-7021ae47fab0",
            host:   "https://wchat.freshchat.com",
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
    *}
{/if}
</body>
</html>
