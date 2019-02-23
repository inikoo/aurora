﻿{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:34:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}<!DOCTYPE HTML>
<html lang="en">
<head>

    {if $smarty.server.SERVER_NAME!='ecom.bali'  }

    {if  $client_tag_google_manager_id!=''}
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
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
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            {/literal}
            ga('create', 'UA-100778677-1', 'auto', 'auTracker');
            ga('auTracker.send', 'pageview');
            {if  !empty($account_code)}
            ga('auTracker.set', 'contentGroup1', '{$account_code}');
            {/if}
            ga('auTracker.set', 'contentGroup2', '{$smarty.server.SERVER_NAME}');

        </script>

    {/if}


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <title>{$webpage->get('Webpage Browser Title')}</title>
    <meta name="description" content="{$webpage->get('Webpage Meta Description')}"/>
    {if $smarty.server.SERVER_NAME!='ecom.bali' }
        <script src="https://browser.sentry-cdn.com/4.3.4/bundle.min.js" crossorigin="anonymous"></script>
        <script>
            Sentry.init({ dsn: 'https://ca602819cbd14ce99a6d3ab94e1c5f04@sentry.io/1329969',
                release: "au-web@1.0" });
        </script>
    {/if}
    <link rel="stylesheet" type="text/css" href="/css/mobile.min.css?v2.4">

    {assign "with_forms" false}
    {assign "with_not_found" 0}
    {assign "with_offline" 0}
    {assign "with_iframe" false}
    {assign "with_login" false}
    {assign "with_register" false}
    {assign "with_basket" false}
    {assign "with_checkout" false}
    {assign "with_profile" false}
    {assign "with_favourites" false}
    {assign "with_search" false}
    {assign "with_thanks" false}
    {assign "with_gallery" false}
    {assign "with_telephone" false}
    {assign "with_product_order_input" false}
    {assign "with_order" false}
    {assign "with_reviews" false}


    {foreach from=$content.blocks item=$block }
        {if $block.show}




            {if $block.type=='profile'}
                {if !$logged_in}
                    {assign "with_not_found" 1}
                {else}
                    {assign "with_profile" 1} {assign "with_forms" 1}
                {/if}
            {elseif $block.type=='checkout'}
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
            {elseif $block.type=='thanks'}
                {if !$logged_in}
                    {assign "with_not_found" 1}
                {else}
                    {assign "with_thanks" 1} {assign "with_order" 1}
                {/if}
            {elseif $block.type=='basket'}
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
            {elseif $block.type=='unsubscribe'}
                {assign "with_reset_password" 1}
                {assign "with_forms" 1}

                <script src="js/tablet.min.js"></script>
                <script src="js/mobile.forms.min.js"></script>
                <script src="js/sweetalert.min.js"></script>
            {elseif $block.type=='reviews'}
                {assign "with_reviews" 1}
            {else}
                {if $block.type=='search'   }{assign "with_search" 1}{/if}
                {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
                {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
                {if $block.type=='not_found'   }{assign "with_not_found" 1}{/if}
                {if $block.type=='offline'   }{assign "with_offline" 1}{/if}
                {if $block.type=='reset_password'   }{assign "with_forms" 1}{/if}
                {if $block.type=='telephone'   }{assign "with_telephone" 1}{/if}
                {if $block.type=='category_products' or   $block.type=='products'  or   $block.type=='product' }{assign "with_product_order_input" 1}{/if}

            {/if}
        {/if}
    {/foreach}

    {if $with_forms==1}
        <link rel="stylesheet" href="css/forms.min.css?v2.0" type="text/css"/>

    {/if}
    {if $with_gallery==1}
    <link rel="stylesheet" href="css/image_gallery.min.css">
    {/if}

    {if $website->get('Website Text Font')!=''  and $logged_in}
        <link href="https://fonts.googleapis.com/css?family={$website->get('Website Text Font')}:400,700" rel="stylesheet">
    {/if}


    {if $with_reviews==1}
        <script src="https://widget.reviews.io/rich-snippet-reviews-widgets/dist.js"></script>

    {/if}
    {if $with_basket==1}
        <script src="https://www.paypalobjects.com/api/checkout.min.js" async></script>
    {/if}
    {if $with_checkout==1 }
        <script src="https://www.paypalobjects.com/api/checkout.min.js" async></script>
    {/if}

    <style>




         .header-logo{
             background-image:url({if !empty($settings['logo_website'])}{$settings['logo_website']}{/if});
             background-size: auto 100%;
         }

         .sidebar-header-image .sidebar-logo {
             padding-left:0px;
         }

         .sidebar-header-image .sidebar-logo strong{
             padding-left: 75px;
         }



         {if $website->get('Website Text Font')!=''}
        body {
            font-family: '{$website->get('Website Text Font')}', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: '{$website->get('Website Text Font')}', sans-serif;

        }

        {/if}

        {foreach from=$website->mobile_style  item=style  }
        {$style[0]}{ {$style[1]}: {$style[2]}}
        {/foreach}

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
             height: 55px;
             min-height: 55px;
         }
         table.orders th{
             font-weight: normal;
             border-bottom:1px solid #777;text-align: center;
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

         table.orders th.text-right{
             text-align: right;padding-right: 4px;
         }
         table.orders td.text-right{
             text-align: right;padding-right: 4px;
         }

         h3{
             display: block;
             padding: 20px 30px;
             border-bottom: 1px solid rgba(0,0,0,.1);
             background: rgba(248,248,248,.9);
             font-size: 25px;
             -weight: 300;
             color: #232323;
             font-weight: 800;
             font-family: Ubuntu,Helvetica,Arial,sans-serif;
             margin-top: 0;
             border: 1px solid #ccc;
             text-align: center;
         }

         {/if}


    </style>

    {if $smarty.server.SERVER_NAME!='ecom.bali' and $zendesk_chat_code!=''}
        <!--Start of Zendesk Chat Script-->
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
        <!--End of Zendesk Chat Script-->

    {/if}



</head>




