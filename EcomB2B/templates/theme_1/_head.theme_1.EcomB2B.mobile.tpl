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





        <link href="/css/mobile.min.css" rel="stylesheet" type="text/css">



{*
        <link href="/css/sweetalert.css" rel="stylesheet">
        <link rel="stylesheet" href="/css/fontawesome-all.min.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="/theme_1/mobile/style.css?v=180301v6">
        <link rel="stylesheet" type="text/css" href="/theme_1/mobile/skin.css">
        <link rel="stylesheet" type="text/css" href="/theme_1/mobile/framework.css?v=171011b">
        <link rel="stylesheet" type="text/css" href="/theme_1/mobile/ionicons.min.css">
        <link rel="stylesheet" type="text/css" href="/theme_1/sky_forms/css/sky-forms.css?v=2"  media="all">
        <link rel="stylesheet" type="text/css" href="/theme_1/css/aurora.theme_1.EcomB2B.mobile.css?v=180321v1" />
  *}
    <script src="/js/mobile.min.js"></script>

{*

    <script src="/theme_1/mobile/jquery.js"></script>
    <script src="/theme_1/mobile/plugins.js"></script>
    <script src="/theme_1/mobile/custom.js"></script>
    <script src="/js/sweetalert.min.js"></script>

    <script src="/theme_1/local/jquery-ui.js"></script>
    <script src="/js/sha256.js"></script>

    <script src="/theme_1/sky_forms/js/jquery.form.min.js"></script>
    <script src="/theme_1/sky_forms/js/jquery.validate.min.js"></script>
    <script src="/theme_1/sky_forms/js/additional-methods.min.js"></script>

    <script src="/js/aurora.js?20180319"></script>
    <script src="/js/validation.js"></script>

    <script src="/js/ordering.touch.js?20180115"></script>
    <script src="/js/braintree.js"></script>
*}


</head>




