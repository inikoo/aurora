<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>
    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>
    <link href="/assets/au_app.min.css" rel="stylesheet">
    {if !$is_devel}
    <script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script>
    <script>
        window.LogRocket && window.LogRocket.init('lrrztl/aurora');
        LogRocket.identify('{$user->get('User Handle')}_{$account->get('Account Code')}', {
            name:'{$user->get('User Alias')}',
            url: '{$account->get('Account Code')|lower}.aurora.systems'
        });

    </script>
        {if !empty($sentry_js)}

        <script
                src="https://browser.sentry-cdn.com/5.9.1/bundle.min.js"
                integrity="sha384-/x1aHz0nKRd6zVUazsV6CbQvjJvr6zQL2CHbQZf3yoLkezyEtZUpqUNnOLW9Nt3v"
                crossorigin="anonymous"></script>
         {/if}

    {/if}
    <script src="https://d3js.org/d3.v4.min.js"></script>

    <script src="/assets/aurora_libs.min.js"></script>
    <script src="/assets/aurora.min.js"></script>

    <script src="/utils/country_data.js.php?v=v190124&locale={$locale}"></script>


    {if $user->get('User Type')=='Staff' or $user->get('User Type')=='Contractor'}

    {elseif $user->get('User Type')=='Agent' }
        <script src="/js/agent.order.js?v181115"></script>
    {/if}


    <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js" async></script>


</head>
<body
        data-user_handle="{$user->get('Handle')}" data-account_code="{$account->get('Account Code')}"
        data-user_key="{$user->id}"
        data-labels='{
"save":"{t}Save{/t}",
"undo":"{t}Undo{/t}",
"add":"{t}Add{/t}",
"remove":"{t}Remove{/t}",
"error":"{t}Error{/t}",
"invalid_val":"{t}Invalid value{/t}"

}' class="{$user->get('User Theme')}">
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

        {if !empty($sentry_js)}

        Sentry.init({
            dsn: '{$sentry_js}',
            release: "__AURORA_RELEASE__"

        });


        Sentry.configureScope((scope) => {
            scope
                .setUser({
                    "id":       "{$user->id}",
                    "username": "{$user->get('Alias')}",
                    "email":    "{$user->get_staff_email()}"
                });
        })
        {/if}

    </script>
{/if}


<div id="top_bar" >


    <div id="view_position"></div>

    <div id="profile_section" style="">

        <span  class="button" style="margin-right: 15px" " onclick="change_view('profile')">
            <i class="button fa fa-user-circle  " style="margin-right: 4px"></i>
            {$user->get('User Alias')}
        </span>

        <span class="logout button" onclick="logout()"><i title="{t}Logout{/t}" class="small fa fa-sign-out fa-fw fa-flip-horizontal "></i><span id="logout_label" class="label"> {t}Logout{/t}</span>
        </span>

    </div>

</div>
<input type="hidden" id="_labels" data-labels='{ "error":"{t}Error{/t}"}'>

<div class="grid">
    <section>
        <div id="app_left_menu">
            <div id="top_info" class="button"   onclick="change_view('dashboard')" >
                <div id="aurora_logo">
                <i class="fal fa-tachometer-alt" style="font-size: 18px;margin-top: 6px"></i>
                </div>
                <div id="account_name" class=" Account_Name" style="text-align: left;">
                    {$account->get('Account Name')}
                </div>


            </div>



            <div class="current_store_label invisible" style="color:#ddd;font-size: 12px;height: 20px;line-height: 20px;margin-top:4px;text-align: center">

                <i class="fal fa-store-alt"></i> <span class=""></span>
            </div>




            <div id="menu"></div>
            <ul  class="bookmarks"  >






                {if  $user->can_view('account')  }
                    <li onclick="change_view('/account')"><i class="fal fa-sliders-h-square fa-fw" ></i><span class="label"> </span>
                    </li>
                 {/if}


                {if  $user->can_view('users_reports')  }
                    <li onclick="change_view('/fire')"><i class="fa fa-fire fa-fw" style="color:orange;opacity:.8" title="{t}Fire{/t}" ></i>
                    </li>
                {/if}


            </ul>


            <div class="aurora_version">
            <div class="timezone_info">{$timezone_info}</div>


                <img src="/art/aurora_log_v2_orange_small.png"/>
                <div class="aurora">aurora</div>

                <div class=" full"></div>
            <div class=" small"></div>


                <div id="submit_ticket"  style="margin-top: 10px"  onclick="FreshWidget.show(); return false;">
                    <span class="button" onclick="show_side_content('help')"  >{t}Submit ticket{/t}</span>

                    <i class=" far fa-headset" title="{t}Submit ticket{/t}"></i>
                </div>


            </div>

        </div>
        <div id="app_main">
            <div id="navigation">
                <div id="address_bar" style="display: flex;position: relative;">

                    <div id="top_menu" style="">

                    </div>

                    <div class="smart_search_input" style="width: 500px;border-bottom:1px solid #ccc" >

                        <label for="smart_search" aria-label="{t}Search{/t}" >
                            <i class="far fa-search"></i>
                        </label>
                             <form>
                            <input  style="position: relative;bottom: 2px"/>
                             </form>
                        <div class="options">
        <span class="close_search ">
                <button>
                <i class="fal fa-fw fa-times"></i>
                </button>
            </span>
                        </div>
                        <div class="options hide">
            <span>
                <button ">
                <i class="small fal fa-fw fa-sliders-h"></i>
                </button>
            </span>
                        </div>


                    </div>

                    <div class="smart_search_result hide">
                        <div class="search_result_title  small">
                            <div class="result_info italic">{t}Lightweight results{/t} <span class="num"></span></div>


                            <div class="options">
            <span>
                <button style="padding-right: 10px">
                <i class="small save valid changed far fa-fw fa-search-plus"></i>
                </button>
            </span>
                            </div>

                        </div>
                        <table class="results">

                        </table>
                    </div>

                </div>
                <div id="au_header">
                </div>
            </div>
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

            {if  $user->can_view('users_reports')  }
            <div data-type="fire" onclick="change_view('/fire')"  title="{t}Fire{/t}"   style="float: right" class=" right_bookmarks side_content_icon square_button">
            <i class="fa fa-fire fa-fw  " style="color:orange;" ></i>
            </div>
            {/if}



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

{/if}
</body>
</html>
