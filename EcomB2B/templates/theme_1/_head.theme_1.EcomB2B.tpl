{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 May 2017 at 09:10:29 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}<!doctype html><!--[if IE 7 ]>
<html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]--><!--[if IE 8 ]>
<html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]--><!--[if IE 9 ]>
<html lang="en-gb" class="isie ie9 no-js"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-gb" class="no-js"> <!--<![endif]-->
<head>

    <script>
      window.dataLayer = window.dataLayer || [];
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    {if  $account_code=='AW'  }
    {if $website->get('Website Code')=='AW.biz'}
        <script src="https://scripts.luigisbox.com/LBX-588294.js"></script>
    {elseif $website->get('Website Code')=='AWD'}
        <script src="https://scripts.luigisbox.com/LBX-621865.js"></script>
    {/if}


    {elseif  $account_code=='AROMA'  }
    {if $website->get('Website Code')=='Aroma'}
        <script src="https://scripts.luigisbox.com/LBX-621871.js"></script>
    {elseif $website->get('Website Code')=='AC'}
        <script src="https://scripts.luigisbox.com/LBX-621949.js"></script>
    {/if}

    {elseif  $account_code=='INDO'  }
    

    {elseif  $account_code=='AWEU'  }
    {if $website->get('Website Code')=='aw.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622491.js"></script>
    {elseif $website->get('Website Code')=='AWD'}
        <script src="https://scripts.luigisbox.com/LBX-622636.js"></script>
    {elseif $website->get('Website Code')=='HR'}
        <script src="https://scripts.luigisbox.com/LBX-622391.js"></script>
    {elseif $website->get('Website Code')=='it.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622449.js"></script>
    {elseif $website->get('Website Code')=='RO'}
        <script src="https://scripts.luigisbox.com/LBX-622387.js"></script>
    {elseif $website->get('Website Code')=='hu.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622237.js"></script>
    {elseif $website->get('Website Code')=='fr.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622603.js"></script>
    {elseif $website->get('Website Code')=='sk.com'}
        <script src="https://scripts.luigisbox.com/LBX-622511.js"></script>
    {elseif $website->get('Website Code')=='cz.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622467.js"></script>
    {elseif $website->get('Website Code')=='pl.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622535.js"></script>
    {elseif $website->get('Website Code')=='NL'}
        <script src="https://scripts.luigisbox.com/LBX-622609.js"></script>
    {elseif $website->get('Website Code')=='de.eu'}
        <script src="https://scripts.luigisbox.com/LBX-622457.js"></script>
    {elseif $website->get('Website Code')=='SE'}
        <script src="https://scripts.luigisbox.com/LBX-622292.js"></script>
    {/if}


    {elseif  $account_code=='ES'  }
    {if $website->get('Website Code')=='ADE'}
        <script src="https://scripts.luigisbox.com/LBX-622183.js"></script>
    {elseif $website->get('Website Code')=='EU'}
        <script src="https://scripts.luigisbox.com/LBX-622130.js"></script>
    {elseif $website->get('Website Code')=='PT'}
        <script src="https://scripts.luigisbox.com/LBX-622079.js"></script>
    {elseif $website->get('Website Code')=='ES'}
        <script src="https://scripts.luigisbox.com/LBX-622012.js"></script>
    {elseif $website->get('Website Code')=='FR'}
        <script src="https://scripts.luigisbox.com/LBX-621970.js"></script>
    {elseif $website->get('Website Code')=='DSE'}
        <script src="https://scripts.luigisbox.com/LBX-622153.js"></script>
    {/if}
    {/if}

    {if !isset($is_devel) or !$is_devel }
       {if  $client_tag_google_manager_id!=''}
        <!-- Google Tag Manager -->





        <script>


          (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{$client_tag_google_manager_id}');</script>



        <!-- End Google Tag Manager -->
        {/if}
        {literal}


        <script>
            function getCookieValue(a) {
                var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
                return b ? b.pop() : '';
            }

            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            {/literal}



            ga('create', 'UA-100778677-1', 'auto', 'auTracker');
            ga('auTracker.require', 'ec');
            ga('auTracker.set', 'transport', 'beacon');
            ga('auTracker.set', 'dimension1', '{$account_code}.{$webpage->id}');
            ga('auTracker.set', 'dimension2', '{$account_code}.{$webpage->get('Webpage Website Key')}');
            ga('auTracker/set', 'dimension3', '{$account_code}');

            var analytics_user_key=getCookieValue('AUK');
            if(analytics_user_key!=''){
                ga('auTracker.set', 'dimension4', analytics_user_key);
                ga('auTracker.set', 'userId', analytics_user_key);
            }else{
                ga('auTracker.set', 'dimension4', '{$account_code}.');
            }


            ga('auTracker.set', 'currencyCode', '{$store->get('Store Currency Code')}');

            ga('auTracker.set', 'contentGroup1', '{$account_code}');

            ga('auTracker.set', 'contentGroup2', '{$smarty.server.SERVER_NAME}');
            ga('auTracker.set', 'contentGroup3', '{if $logged_in}Logged in{else}Logged out{/if}');

            function go_product(element) {
                ga('auTracker.ec:addProduct', element.dataset.analytics  );
                ga('auTracker.ec:setAction', 'click', { list: element.dataset.list});
                var link = element.getAttribute('href')
                if (navigator.sendBeacon) {
                    ga('auTracker.send', 'event', 'UX', 'click', element.dataset.list);
                    document.location = link;
                } else {
                    ga('auTracker.send', 'event', 'UX', 'click', element.dataset.list, {
                        hitCallback: function () {
                            document.location = link;
                        }
                    });
                }
            }



        </script>



    {else}
    {literal}
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            {/literal}
            ga('create', 'UA-100778677-3', 'auto', 'auTracker');
            ga('auTracker.require', 'ec');
            ga('auTracker.set', 'transport', 'beacon');
            {if !empty($analytics_user_key)}
            ga('auTracker.set', 'userId', '{$analytics_user_key}');
            {/if}
            ga('auTracker.set', 'currencyCode', '{$store->get('Store Currency Code')}');
            {if  !empty($account_code)}
            ga('auTracker.set', 'contentGroup1', '{$account_code}');
            {/if}
            ga('auTracker.set', 'contentGroup2', '{$smarty.server.SERVER_NAME}');
            ga('auTracker.set', 'contentGroup3', '{if $logged_in}Logged in{else}Logged out{/if}');

            function go_product(element) {
                ga('auTracker.ec:addProduct', element.dataset.analytics  );
                ga('auTracker.ec:setAction', 'click', { list: element.dataset.list});
                var link = element.getAttribute('href')
                if (navigator.sendBeacon) {
                    ga('auTracker.send', 'event', 'UX', 'click', element.dataset.list);
                    document.location = link;
                } else {
                    ga('auTracker.send', 'event', 'UX', 'click', element.dataset.list, {
                        hitCallback: function () {
                            document.location = link;
                        }
                    });
                }
            }
        </script>
    {/if}

    <title>{$webpage->get('Browser Title')}</title>
    <meta charset="utf-8">
    <meta name="keywords" content="giftware aromatherapy"/>
    <meta name="description" content="{$webpage->get('Webpage Meta Description')}"/>
    {if !empty($settings['favicon_website'])}
       <link rel="shortcut icon" type="image/png" href="{$settings['favicon_website']}"/>
    {/if}

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {if $website->get('Website Code')=='AW.biz'}
        <script id="mcjs">!function(c,h,i,m,p){
            m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/2dfc4018e22b28a66ec6cc1bd/b9c276fd71aa5eecf3a87d6d5.js");</script>
    {/if}

    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    {if isset($sentry_js) and false }

        <script
                src="https://browser.sentry-cdn.com/6.7.1/bundle.min.js"
                integrity="sha384-+GoWV2WnDUGsSmipBvyBrWuhW8hPa/D21fH+j3NoZxDf9RgDryqh4Ug4L+7E1nxM"
                crossorigin="anonymous"
        ></script>

        <script>
            Sentry.init({
                dsn: '{$sentry_js}' ,
                release: "__AURORA_RELEASE__" ,
                denyUrls: [
                    /load\.sumo\.com/i,

                ],
                beforeSend(event, hint) {
                    const error = hint.originalException;
                    if (
                        error &&
                        error.message &&
                        error.message.match(/Non*Error promise rejection captured with value/i)
                    ) {
                        return null;
                    }
                    return event;
                }
            });
        </script>
    {/if}


    <link rel="canonical" href="{$webpage->get('URL')}"/>


    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>



    {if $logged_in and $store->get('Store Type')=='Dropshipping' }
        <script src="/assets/dropshipping.logged_in.min.js"></script>
    {/if}

    {if $logged_in}
        <script>
            var websocket_connected = false;
            var websocket_connected_connecting = false;
            var ws_connection =false;
        </script>
     {/if}
    <script>
        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1), sURLVariables = sPageURL.split('&'), sParameterName, i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        }
    </script>
    {if !empty($loqate)}
        <script>(function(n,t,i,r){
                var u,f;n[i]=n[i]||{

                },n[i].initial={
                    accountCode:"{$loqate}",host:"{$loqate}.pcapredict.com"},n[i].on=n[i].on||function(){
                    (n[i].onq=n[i].onq||[]).push(arguments)},u=t.createElement("script"),u.async=!0,u.src=r,f=t.getElementsByTagName("script")[0],f.parentNode.insertBefore(u,f)})(window,document,"pca","//{$loqate}.pcapredict.com/js/sensor.js")</script>
    {/if}


    <link rel="stylesheet" href="js/luigi_theme.css?v=13" type="text/css"/>
    <link rel="stylesheet" href="js/animations.css" type="text/css"/>


    <link rel="stylesheet" href="assets/desktop.min.css" type="text/css"/>
    {assign "with_forms" false}
    {assign "with_not_found" 0}
    {assign "with_offline" 0}
    {assign "with_in_process" 0}
    {assign "with_iframe" false}
    {assign "with_login" false}
    {assign "with_register" false}
    {assign "with_basket" false}
    {assign "with_checkout" false}
    {assign "with_profile" false}
    {assign "with_favourites" false}
    {assign "with_custom_design_products" false}
    {assign "with_customer_discounts" false}
    {assign "with_search" false}
    {assign "with_thanks" false}
    {assign "with_gallery" false}
    {assign "with_telephone" false}
    {assign "with_reviews" false}
    {assign "with_product_order_input" false}
    {assign "with_reset_password" false}
    {assign "with_order" false}
    {assign "with_datatables" false}



    {if !empty($content.blocks) and  $content.blocks|is_array}
    {foreach from=$content.blocks item=$block }

        {if $block.show}
        {if $block.type=='profile'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_profile" 1} {assign "with_forms" 1}
            {/if}
            {elseif $block.type=='client'}
                {if !$logged_in}
                    {assign "with_not_found" 1}
                {else}
                    {assign "with_datatables" 1} {assign "with_forms" 1}
                {/if}
        {elseif $block.type=='checkout'  or $block.type=='top_up' }
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_checkout" 1} {assign "with_forms" 1} {assign "with_order" 1}
            {/if}
        {elseif $block.type=='favourites'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_favourites" 1}
            {/if}
        {elseif $block.type=='custom_design_products'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_custom_design_products" 1}
            {/if}
        {elseif $block.type=='customer_discounts'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_customer_discounts" 1}
            {/if}
        {elseif $block.type=='catalogue'}
            {assign "with_datatables" 1}
        {elseif $block.type=='portfolio' or $block.type=='clients' or $block.type=='client_order_new' or  $block.type=='clients_orders' or  $block.type=='balance'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_datatables" 1}
                {assign "with_forms" 1}
            {/if}
        {elseif  $block.type=='client_order'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_datatables" 1}
            {/if}
        {elseif $block.type=='reviews'}
            {assign "with_reviews" 1}
        {elseif $block.type=='thanks'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_thanks" 1} {assign "with_order" 1}
            {/if}
        {elseif $block.type=='basket' or $block.type=='client_basket'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_basket" 1} {assign "with_forms" 1} {assign "with_order" 1}
            {/if}
        {elseif $block.type=='login'}
            {if $logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_login" 1} {assign "with_forms" 1}
            {/if}
        {elseif $block.type=='register'}
            {if $logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "with_register" 1} {assign "with_forms" 1}
            {/if}

        {elseif $block.type=='reset_password' }
            {assign "with_reset_password" 1}
            {assign "with_forms" 1}
        {elseif $block.type=='unsubscribe'}
            {assign "with_reset_password" 1}
            {assign "with_forms" 1}

            <script src="assets/desktop.in.min.js"></script>
            <script src="assets/desktop.forms.min.js"></script>
            <script src="js/libs/sweetalert.min.js"></script>


        {else}
            {if $block.type=='search'   }{assign "with_search" 1}{/if}
            {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
            {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
            {if $block.type=='not_found'   }{assign "with_not_found" 1}{/if}
            {if $block.type=='offline'   }{assign "with_offline" 1}{/if}
            {if $block.type=='in_process'   }{assign "with_in_process" 1}{/if}

            {if $block.type=='telephone'   }{assign "with_telephone" 1}{/if}
            {if $block.type=='category_products' or   $block.type=='products'  or   $block.type=='product' }{assign "with_product_order_input" 1}{/if}

        {/if}
        {/if}
    {/foreach}
    {/if}
    {if $with_reviews==1}
        <script src="https://widget.reviews.io/rich-snippet-reviews-widgets/dist.js" ></script>
    {/if}
    {if $with_forms==1}
        <link rel="stylesheet" href="assets/forms.min.css" type="text/css"/>
    {/if}
    {if $with_datatables==1}
        <link rel="stylesheet" href="assets/datatables.min.css" type="text/css"/>
    {/if}
    {if $with_gallery==1}
        <link rel="stylesheet" href="assets/image_gallery.min.css" type="text/css"/>
    {/if}

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>




    {if !isset($is_devel) or !$is_devel  }
    {if  $zendesk_chat_code!='' }

        <script>
            window.$zopim || (function (d, s) {
                var z = $zopim = function (c) {
                    z._.push(c)
                }, $ = z.s = d.createElement(s), e = d.getElementsByTagName(s)[0];
                z.set = function (o) {
                    z.set._.push(o)
                };
                z._ = [];
                z.set._ = [];
                $.async = !0;
                $.setAttribute('charset', 'utf-8');
                $.src = 'https://v2.zopim.com/?{$zendesk_chat_code}';
                z.t = +new Date;
                $.type = 'text/javascript';
                e.parentNode.insertBefore($, e)
            })(document, 'script');


            $zopim(function () {
                $zopim.livechat.setLanguage('{$language}');
            });


        </script>


    {/if}
    {/if}
    {if !isset($is_devel) or !$is_devel  }
        {if  !empty($sumo_code)}
            <script async>(function(s,u,m,o,j,v){
                    j=u.createElement(m);v=u.getElementsByTagName(m)[0];j.async=1;j.src=o;j.dataset.sumoSiteId=‘{$sumo_code}’;v.parentNode.insertBefore(j,v)})(window,document,‘script’,‘//load.sumo.com/’);</script>

        {/if}
    {/if}


    {if $website->get('Website Text Font')!='' }
        <link href="https://fonts.googleapis.com/css?family={$website->get('Website Text Font')}:400,700" rel="stylesheet">
    {/if}

    <link href="https://fonts.googleapis.com/css2?family=Comfortaa&family=Inter&family=Laila&family=Lobster&family=Playfair&family=Port+Lligat+Slab&family=Quicksand&family=Yatra+One" rel="stylesheet">


    {if ($with_basket==1 or  $with_checkout==1 ) and !( $account_code=='AROMA' or   $account_code=='AWEU'  )  }
        <script src="https://www.paypalobjects.com/api/checkout.min.js" async></script>
    {/if}



    {if $with_basket==1 or  $with_checkout==1 and $account_code=='AROMA' and isset($paypal_client_id)  }
        <script
                data-sdk-integration-source="integrationbuilder_sc"
                src="https://www.paypal.com/sdk/js?client-id={$paypal_client_id}&components=buttons&enable-funding=paylater&currency={$paypal_currency}"></script>

        </script>
    {/if}

    {if $with_basket==1 or  $with_checkout==1 and  $account_code=='AWEU' and isset($paypal_client_id)  }

    {if $website->get('Website Code')=='NL'}
        <script
                data-sdk-integration-source="integrationbuilder_sc"
                src="https://www.paypal.com/sdk/js?client-id={$paypal_client_id}&components=buttons,fields,marks,funding-eligibility&enable-funding=ideal&currency={$paypal_currency}"></script>


    {else }

        <script
                data-sdk-integration-source="integrationbuilder_sc"
                src="https://www.paypal.com/sdk/js?client-id={$paypal_client_id}&components=buttons&currency={$paypal_currency}"></script>

        </script>
        {/if}
    {/if}

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            important: true,
            prefix: 'tw-',
            corePlugins: {
                preflight: false,
            },
            theme: {
                extend: {
                    colors: {
                        color1: '#4b5058',
                        color2: '#957a65',
                        color3: '#e87928',
                    }
                }
            }
        }
    </script>

    <style>
        {if $website->get('Website Text Font')!=''}
        body {
            font-family: '{$website->get('Website Text Font')}', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: '{$website->get('Website Text Font')}', sans-serif;

        }

        {/if}


        {foreach from=$website->style  item=style  }
        {$style[0]}{ {$style[1]}: {$style[2]}}
        {/foreach}



        {if $with_not_found==1 or $with_offline==1 or $with_in_process==1}

        .page_not_found {
            padding: 50px 30px 58px 30px;
            margin: 0 auto;
            width: 59%;
            background-color: #fff;
            border: 1px solid #eee;
            border-bottom: 5px solid #eee;
            text-align: center;
        }
        .page_not_found strong {
            display: block;
            font-size: 145px;
            line-height: 100px;
            color: #e3e3e3;
            font-weight: normal;
            margin-bottom: 10px;
            text-shadow: 5px 5px 1px #fafafa;
        }
        .page_not_found b {
            display: block;
            font-size: 40px;
            line-height: 50px;
            color: #999;
            margin: 0;
            font-weight: 300;
        }
        .page_not_found em {
            display: block;
            font-size: 18px;
            line-height: 59px;
            color: #e54c4c;
            margin: 0;
            font-style: normal;
        }

        .page_not_found .separator{
            margin-bottom:40px
        }
        {/if}

        {if $with_telephone==1}
        .telephone_block {
            float: left;
            width: 100%;
            padding-bottom:7px;
            background: #fff;
            text-align: center;
        }
        .telephone_block h2 {

            font-weight: 300;
            margin-bottom: 40px;
            color: #333;
        }
        .telephone_block strong {
            color: #fff;
            font-size: 35px;
            font-weight: 600;
            background: #333;
            padding: 5px 20px;
            margin-right: 20px;
        }
        .telephone_block em {

            font-size: 25px;
            color: #999;
            font-weight: normal;
        }
    {/if}

        {if $with_order==1}
        .order_header caca{
            padding:0px 30px
        }

        .order_header .totals{
            padding-right: 20px;
            text-align: right;

        }

        .totals  table{

            float: right;
        }

        .totals  table  td{
            padding:6px 20px 6px 50px; ;
            border-bottom:1px solid #ccc;
        }



        .totals  table {
            border-top:1px solid #777;
            border-bottom:1px solid #777;
        }



        .totals  table tr:last-child td{
            border-bottom:1px solid #777;
        }

        .totals  table  tr.before_total td{

            border-bottom:1px solid #777;
        }

        .totals  table  tr.total td{
            font-weight: 800;
            border-top:1px solid #777;
            border-bottom:1px solid #777;
        }

        .order table{
            margin:40px 0px 30px 0px;
        }

        .order table td{
            border-top:1px solid #ccc;
            padding:4px 3px;
        }

        .order table tr:last-child td{
            border-bottom:1px solid #c5c5c5;
        }



        {/if}

        {if $with_checkout==1}




        .tabs3 {
            margin: 0;
            padding: 0;
            list-style-type: none;
            border: 1px solid #e0e0e0;
            border-bottom: none;
            height: 54px;
            width: 99%;
            background-color: #eee;
            position: relative;
            z-index: 4;
        }
        .tabs3 li {
            margin: 0;
            text-align: left;
            font-family: 'Raleway', sans-serif;
        }
        .tabs3 li a {
            float: left;
            color: #272727;
            height: 54px;
            padding: 0px 40px;
            font-weight: 400;
            text-decoration: none;
            line-height: 50px;
            font-size: 14px;
            background-color: #eee;
            border-right: 1px solid #fff;
        }
        .tabs3 li a:hover {
            color: #e54c4c;
        }
        .tabs3 li.active a {
            color: #e54c4c;
            border-bottom: 1px solid #fff;
            background-color: #fff;
        }
        .tabs-content3 {
            float: left;
            width: 91%;
            padding: 5% 4% 3% 4%;
            text-align: left;
            margin-bottom: 0px;
            margin-top: -1px;
            border: 1px solid #e0e0e0;
            background-color: #fff;
            position: relative;
            z-index: 3;
        }
        .tabs-content3 img.img_left2 {
            width: auto;
            margin-right: 25px;
            margin-bottom: 20px;
        }
        .tabs-content3 .tabs-panel {
            padding: 20px;
        }
        .tabs-content3 .tabs-panel3 .tab-title3 {
            display: none;
        }


        @media only screen and (min-width: 1000px) and (max-width: 1169px){

            .tabs3 li a {
                padding: 0px 20px;
            }

            .tabs3.three li a {
                padding: 0px 10px;
            }

        }

        @media only screen and (min-width: 768px) and (max-width: 999px){

            .tabs3 li a {
                padding: 0px 10px;
                font-size: 13px;
            }

            .tabs3.two li a {
                padding: 0px 10px;
            }

            .tabs3.three li a {
                padding: 0px 4px;
            }


        }


        @media only screen and (min-width: 480px) and (max-width: 767px){
            .tabs3 li a {
                padding: 0px 10px;
            }

            .tabs-content3.three {
                padding: 7% 5%;
            }

        }


        @media only screen and (max-width: 479px){
            .tabs3 li a {
                padding: 0px 5px;
                font-size: 13px;
            }

            .tabs-content3 img.img_left2 {
                width: 100%;
                margin-right: 0px;
            }

            .tabs3.three li a {
                padding: 0px 10px;
            }

        }


        {/if}

        {if $with_profile==1}
        #profile_menu{
            padding-left: 0px;
        }

        #profile_menu li{
            list-style-type: none;
            margin-bottom: 10px;
            font-size: 16px;
        }
        table.orders tr{
            height: 35px;
        }
        table.orders th{
            border-bottom:1px solid #777
        }
        table.orders td{

            border-bottom:1px solid #ccc
        }
        table.orders .like_link{
            cursor: pointer;
        }

        table.orders .like_link:hover{
            text-decoration: underline;
        }

        {/if}

    </style>

    {if  $google_ads_id!=''}
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-10991626478"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){
            dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'AW-10991626478');
    </script>
    {/if}

    {if !empty($zaraz)}
        <script src="/cdn-cgi/zaraz/i.js" referrerpolicy="origin"></script>
    {/if}

</head>

