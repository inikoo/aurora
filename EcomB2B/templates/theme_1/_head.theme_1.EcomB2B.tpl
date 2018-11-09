﻿{*
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
    {if $smarty.server.SERVER_NAME!='ecom.bali'  and $client_tag_google_manager_id!=''}
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
    <title>{$webpage->get('Webpage Browser Title')}</title>
    <meta charset="utf-8">
    <meta name="keywords" content=""/>
    <meta name="description" content="{$webpage->get('Webpage Meta Description')}"/>
    <link rel="shortcut icon" type="image/png" href="art/favicon.png"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>


    <![endif]-->

    {if $smarty.server.SERVER_NAME!='ecom.bali' }
        <script src="https://browser.sentry-cdn.com/4.3.0/bundle.min.js" crossorigin="anonymous"></script>
        <script>
            Sentry.init({ dsn: 'https://bdeef00d9ed04614a5b3245c0ba178ec@sentry.io/1319896' });
        </script>
    {/if}
    <link rel="canonical" href="{$webpage->get('URL')}"/>


    <link rel="stylesheet" href="css/desktop.min.css?v3.0.1" type="text/css"/>

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
    {assign "with_reset_password" false}
    {assign "checkout" false}
    {assign "profile" false}
    {assign "favourites" false}
    {assign "thanks" false}
    {assign "checkout" false}

    {assign "with_order" false}

    {foreach from=$content.blocks item=$block }
        {if $block.show}




        {if $block.type=='profile'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "profile" 1} {assign "with_forms" 1}
            {/if}
        {elseif $block.type=='checkout'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "checkout" 1} {assign "with_forms" 1} {assign "with_order" 1}
            {/if}
        {elseif $block.type=='favourites'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "favourites" 1}
            {/if}
        {elseif $block.type=='thanks'}
            {if !$logged_in}
                {assign "with_not_found" 1}
            {else}
                {assign "thanks" 1} {assign "with_order" 1}
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

        {elseif $block.type=='reset_password' }
            {assign "with_reset_password" 1}
            {assign "with_forms" 1}
        {elseif $block.type=='unsubscribe'}
            {assign "with_reset_password" 1}
            {assign "with_forms" 1}



            <script src="js/desktop.min.js"></script>

            <script src="js/desktop.forms.min.js"></script>
            <script src="js/sweetalert.min.js"></script>



        {else}
            {if $block.type=='search'   }{assign "with_search" 1}{/if}
            {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
            {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
            {if $block.type=='not_found'   }{assign "with_not_found" 1}{/if}
            {if $block.type=='offline'   }{assign "with_offline" 1}{/if}

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







    {if $website->get('Website Text Font')!=''  and $logged_in}
        <link href="https://fonts.googleapis.com/css?family={$website->get('Website Text Font')}:400,700" rel="stylesheet">
    {/if}




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



        {if $with_not_found==1 or $with_offline==1}

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

        {if $with_order}
        .order_header{
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

        .totals  table  tr.total{
            font-weight: 800;
        }

        .totals  table tr:first-child td{
            border-top:1px solid #c5c5c5;
        }



        .totals  table tr:last-child td{
            border-bottom:2px solid #bbb;
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

        {if $checkout==1}




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

        {if $profile==1}
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

</head>


