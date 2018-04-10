{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2018 at 15:37:23 GMT+8, Kuala Lumpur, Malaysia
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

    <link rel="stylesheet" type="text/css" href="/css/tablet.min.css?v3">

    <script src="/js/tablet.min.js?v5"></script>
    {if $logged_in}<script src="/js/mobile.logged_in.min.js"></script>
    {if $webpage->get('Webpage Code')=='checkout.sys'}<script src="/js/mobile.forms.min.js"></script><script src="/js/mobile.checkout.min.js?v2"></script>
    {elseif $webpage->get('Webpage Code')=='basket.sys' or $webpage->get('Webpage Code')=='profile.sys' or $webpage->get('Webpage Code')=='reset_pwd.sys'}<script src="/js/mobile.forms.min.js"></script>{/if}
    {else}
        {if $webpage->get('Webpage Code')=='register.sys' or  $webpage->get('Webpage Code')=='login.sys'}<script src="/js/mobile.forms.min.js"></script>{/if}
    {/if}




</head>




