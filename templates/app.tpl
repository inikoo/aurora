<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aurora</title>
    <link href="/art/aurora_log_v2_orange_small.png" rel="shortcut icon" type="image/x-icon"/>
    <link href="/assets/au_app.min.css?v3" rel="stylesheet">
    {if !$is_devel}



        {if !empty($sentry_js)}
            <script nonce="{$csp_nonce}"
                    src="https://browser.sentry-cdn.com/6.6.0/bundle.min.js"
                    integrity="sha384-vPBC54nCGwq3pbZ+Pz+wRJ/AakVC5QupQkiRoGc7OuSGE9NDfsvOKeHVvx0GUSYp"
                    crossorigin="anonymous"
            ></script>
        {/if}

    {/if}
    <script nonce="{$csp_nonce}" src="https://d3js.org/d3.v4.min.js"></script>

    <script nonce="{$csp_nonce}" src="/assets/aurora_libs.min.js"></script>
    <script nonce="{$csp_nonce}" src="/assets/aurora.min.js"></script>

    <script nonce="{$csp_nonce}" src="/utils/country_data.js.php?v=v190124&locale={$locale}"></script>


    {if $user->get('User Type')=='Staff' or $user->get('User Type')=='Contractor'}

    {elseif $user->get('User Type')=='Agent' }
        <script nonce="{$csp_nonce}" src="/js/agent.order.js?v181115"></script>
    {/if}


    // <script nonce="{$csp_nonce}" src="https://app-rsrc.getbee.io/plugin/BeePlugin.js" async></script>
    <script nonce="{$csp_nonce}" src="https://app-rsrc.getbee.io/plugin/BeePlugin.js" defer></script>


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
    <script nonce="{$csp_nonce}">
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
            dsn: '{$sentry_js}', release: "__AURORA_RELEASE__"

        });


        Sentry.configureScope((scope) => {
            scope
                .setUser({
                    "id": "{$user->id}", "username": "{$user->get('Alias')}", "email": "{$user->get_staff_email()}"
                });
        })
        {/if}

    </script>
{/if}
{if $jira_widget!=''}
    <script nonce="{$csp_nonce}" data-jsd-embedded data-key="{$jira_widget}" data-base-url="https://jsd-widget.atlassian.com" src="https://jsd-widget.atlassian.com/assets/embed.js"></script>
{/if}
<div id="top_bar">


    <div id="view_position"></div>

    <div id="profile_section">

        <span class="button" style="margin-right: 15px" " onclick="change_view('profile')">
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
            <div id="top_info" class="button" onclick="change_view('dashboard')">
                <div id="aurora_logo">
                    <i class="fal fa-tachometer-alt" style="font-size: 18px;margin-top: 6px"></i>
                </div>
                <div id="account_name" class=" Account_Name" style="text-align: left;">
                    {$account->get('Account Name')}
                </div>


            </div>


            <div class="current_store_label invisible" style="color:#ddd;font-size: 12px;height: 20px;line-height: 20px;margin-top:4px;text-align: center">

                <i class="fal fa-store-alt margin_right_5"></i>
                <span></span>
            </div>


            <div id="menu"></div>
            <ul class="bookmarks">

                {if  $user->can_view('account')  }
                    <li onclick="change_view('/account')"><i class="fal fa-sliders-h fa-fw"></i><span class="label"> {t}Settings{/t}</span>
                    </li>
                {/if}
                {if  $user->get('User Type')=='Staff' or   $user->get('User Type')=='Contractor' }
                    <li class="hide_desktop" onclick="change_view('/fire')"><i class="fa fa-chess-clock fa-fw" title="{t}Attendance{/t}"></i> <span class="label"> {t}Attendance{/t}</span></li>
                {/if}


            </ul>



            <div class="aurora_version">
                <div class="timezone_info">{$timezone_info}</div>


                <img src="/art/aurora_log_v2_orange_small.png"/>
                <div class="aurora">aurora</div>
                {if $status_page!=''}
                <div class="status"><a href="{$status_page}" target="_blank" >{t}status{/t}</a></div>
                {/if}

                {if $jira_portal!=''}
                    <div class="help_center"><a href="{$jira_portal}" target="_blank" ><span class="label"> {t}help center{/t}</a></div>
                {/if}

                <div class=" full"></div>
                <div class=" small"></div>


            </div>

        </div>
        <div id="app_main">
            <div id="navigation">
                <div id="address_bar" style="display: flex;position: relative;">

                    <div id="top_menu">

                    </div>

                    <div class="smart_search_input">

                        <label for="smart_search" aria-label="{t}Search{/t}">
                            <i class="far fa-search"></i>
                        </label>
                        <form>
                            <input/>
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
                        <div class="search_result_title small">
                            <div class="result_info italic">{t}Lightweight results{/t} <span class="num"></span></div>


                            <div class="options">
            <span>
                <button style="padding-right: 10px">
                <i class="small hide save valid changed far fa-fw fa-search-plus"></i>
                </button>
            </span>
                            </div>

                        </div>
                        <table class="search_results" data-search_index="" data-search_mtime="">

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
    <aside id="notifications" data-current_side_view="{$_side_block}">
        <div class="top_buttons">


            <div data-type="help" onclick="show_side_content('help')" class="hide help_button side_content_icon square_button {if $_side_block=='help'}selected{/if}" title="{t}Help{/t}">
                <i class="fa fa-question-circle fa-fw  "></i>
            </div>

            <div data-type="real_time_users" onclick="show_side_content('real_time_users')" class="real_time_users_button side_content_icon square_button {if $_side_block=='real_time_users'}selected{/if}"
                 title="{t}Real time users{/t}">
                <i class="fa fa-user-circle fa-fw  "></i>
            </div>

            <div data-type="fire" onclick="change_view('/fire')" title="{t}Attendance{/t}" class=" right_bookmarks side_content_icon square_button">
                <i class="fa fa-chess-clock fa-fw "></i>
            </div>


            <div style="clear:both"></div>
        </div>




        <div class="real_time_users side_content hide">
            <div class="top">
                {t}Active users{/t}
            </div>

            <div class="content">
                <table class="real_time_users_table ">
                </table>

            </div>
        </div>




    </aside>
</div>

{if !$is_devel}

{if !empty($firebase)}
    <script nonce="{$csp_nonce}" src="https://www.gstatic.com/firebasejs/7.7.0/firebase-app.js"></script>
    <script nonce="{$csp_nonce}" src="https://www.gstatic.com/firebasejs/7.7.0/firebase-analytics.js"></script>
    <script nonce="{$csp_nonce}" src="https://www.gstatic.com/firebasejs/7.7.0/firebase-messaging.js"></script>
    <script nonce="{$csp_nonce}">
        var firebaseConfig = {
            apiKey: "{$firebase.apiKey}",
            authDomain: "{$firebase.projectId}.firebaseapp.com",
            databaseURL: "https://{$firebase.projectId}.firebaseio.com",
            projectId: "{$firebase.projectId}",
            storageBucket: "{$firebase.projectId}.appspot.com",
            messagingSenderId: "{$firebase.messagingSenderId}",
            appId: "{$firebase.appId}",
            measurementId: "{$firebase.measurementId}"
        };
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

        //const messaging = firebase.messaging();
        // messaging.usePublicVapidKey('<YOUR_PUBLIC_VAPID_KEY_HERE>');


    </script>



{/if}
{/if}
{if $status_page_widget!=''}
    <script nonce="{$csp_nonce}" src="https://{$status_page_widget}.statuspage.io/embed/script.js"></script>
{/if}
{if $jira_widget!=''}

<script nonce="{$csp_nonce}">
    $(document).ready(function() {
        $('#jsd-widget').ready(function() {

            $('#jsd-widget')
                .contents().find("head")
                .append($('<style>body{ background-color:white !important;}  #button-container{ opacity: .7} #help-button{  height: 30px !important;line-height: 30px!important;font-weight:normal !important;} #help-button.text { font-size: 14px !important;} </style>')
                );
        });
    })

</script>
{/if}

</body>
</html>
