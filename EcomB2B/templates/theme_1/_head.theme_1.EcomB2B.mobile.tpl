{*
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
    {if $smarty.server.SERVER_NAME!='ecom.bali' and  $client_tag_google_manager_id!=''}
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){ w[l]=w[l]||[];w[l].push({ 'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{$client_tag_google_manager_id}');</script>
        <!-- End Google Tag Manager -->
    {/if}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <title>{$webpage->get('Webpage Browser Title')}</title>
    <meta name="description" content="{$webpage->get('Webpage Meta Description')}"/>


    <link rel="stylesheet" type="text/css" href="/css/mobile.min.css?v3">



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
        <link rel="stylesheet" href="css/forms.min.css" type="text/css"/>

    {/if}
    {if $with_gallery==1}
    <link rel="stylesheet" href="css/image_gallery.min.css">
    {/if}

    {if $website->get('Website Text Font')!=''}
        <link href="https://fonts.googleapis.com/css?family={$website->get('Website Text Font')}:400,700" rel="stylesheet">
    {/if}

    <style>


         .header-logo{
             background-image:url({if !empty($settings['logo_website'])}{$settings['logo_website']}{else}/art/mobile_logo.png{/if});
             background-size: auto 100%;
         };


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



    </style>


</head>




