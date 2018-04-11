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
    <!--<link rel="shortcut icon" href="images/favicon.ico"> Favicon -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="canonical" href="{$webpage->get('URL')}"/>

    
    <link rel="stylesheet" href="css/desktop.min.css?v4" type="text/css"/>
    <script src="/js/desktop.min.js"></script>
    {if $logged_in}
        <script src="/js/desktop.logged_in.min.js"></script>
        {if $webpage->get('Webpage Code')=='basket.sys'}
        <script src="/js/desktop.forms.min.js"></script>
        <!--[if lt IE 10]>
        <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
        <![endif]-->
        <script src="/js/desktop.basket.min.js"></script>
        {elseif $webpage->get('Webpage Code')=='checkout.sys'}
        <script src="/theme_1/tabs/assets/js/responsive-tabs.min.js"></script>
        <script src="/js/desktop.forms.min.js"></script>
        <!--[if lt IE 10]>
        <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
        <![endif]-->
        <script src="/js/desktop.checkout.min.js"></script>

        {elseif $webpage->get('Webpage Code')=='profile.sys'}
        <script src="/js/desktop.forms.min.js"></script>
        <!--[if lt IE 10]>
        <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
        <![endif]-->
        <script src="/js/desktop.checkout.min.js"></script>
        {elseif $webpage->get('Webpage Code')=='reset_pwd.sys'}
        <script src="/js/desktop.forms.min.js"></script>
        <!--[if lt IE 10]>
        <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
        <![endif]-->
        {/if}
    {else}
    {if $webpage->get('Webpage Code')=='register.sys' or  $webpage->get('Webpage Code')=='login.sys'}
        <script src="/js/desktop.forms.min.js"></script>
        <!--[if lt IE 10]>
        <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
        <![endif]-->
    {/if}
    {/if}




    {*

    <script src="/theme_1/local/jquery.js"></script>


    <script src="/theme_1/local/jquery-ui.js"></script>
    <script src="/theme_1/sky_forms/js/jquery.form.min.js"></script>
    <script src="/theme_1/sky_forms/js/jquery.validate.min.js"></script>
    <script src="/theme_1/sky_forms/js/additional-methods.min.js"></script>

    <!--[if lt IE 10]>
    <script src="/theme_1/sky_formsjs/jquery.placeholder.min.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="/theme_1/sky_formsjs/sky-forms-ie8.js"></script>
    <![endif]-->


    <script src="/js/sweetalert.min.js"></script>
    <script src="/theme_1/tooltips/jquery.darktooltip.js"></script>

    <script src="/theme_1/aninum/jquery.animateNumber.min.js"></script>
    <script src="/theme_1/animations/js/animations.min.js"></script>
    <script src="/theme_1/cubeportfolio/js/jquery.cubeportfolio.js"></script>
    checkout <script src="/theme_1/tabs/assets/js/responsive-tabs.min.js"></script>


    <script src="/theme_1/mainmenu/customeUI.js"></script>
    <script src="/theme_1/mainmenu/sticky.js"></script>
    <script src="/theme_1/mainmenu/modernizr.custom.75180.js"></script>


    <script src="/js/jquery.form.min.js"></script>
    <script src="/js/sha256.js"></script>

    <script src="/js/aurora.js?20180319v2"></script>
    <script src="/js/validation.js"></script>

    <script src="/js/ordering.js?20180115v3"></script>
    <script src="/js/fotorama.js"></script>



    <script src="/js/braintree.js"></script>
*}

    <link rel="stylesheet" href="/website.color.EcomB2B.css.php?&theme=theme_1" type="text/css"/>


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



    {if $website->get('Website Text Font')!=''}
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

        header #topHeader {

            background-color: {$header_data.color.header_background};
        {if $header_data.background_image!=''} background-image: url({$header_data.background_image});
        {/if} color: {$header_data.color.header};

        }

        header #trueHeader {
            background-color: {$header_data.color.menu_background};
            border-bottom-color: {$header_data.color.menu_background_highlight};
            color: {$header_data.color.menu};
        }

        header #trueHeader a {
            color: {$header_data.color.menu};
            cursor: pointer;

        }

        header #trueHeader a:hover {
            color: {$header_data.color.menu_text_background_highlight};

        }

        header #_columns .dropdown a:hover {
            background-color: transparent;
        }

        {if isset($header_data.color.items_title)}
        header #_columns .dropdown li.item_li:hover > a * {
            color: {$header_data.color.items_title};
        }

        {/if}

        header #trueHeader .dropdown-menu {

            color: {$header_data.color.items};
        }

        header #trueHeader .dropdown-menu a {

            color: {$header_data.color.items};
        }

        {if isset($header_data.color.items_title)}

        header #trueHeader .dropdown-menu a:hover {

            color: {$header_data.color.items_title};
        }

        {/if}

        header #menu_control_panel .button {
            background-color: {$header_data.color.menu_background_highlight};
        }

        header #logo {
            background-image: url({$header_data.logo});

        }

        header .yamm .dropdown-menu {
            background: {$header_data.color.items_background};
        }

        header .dropdown-menu li a:hover {
            background: {$header_data.color.items};
            color: {$header_data.color.items_background};
        }

        header .list-unstyled span.link, .list-unstyled a.link {
            color: {$header_data.color.items};

        }

        header .list-unstyled li p {
            color: {$header_data.color.items}
        }

        header .dart {
            color: {$header_data.color.items}

        }

        header .list-unstyled li i {
            color: {$header_data.color.items}
        }

        header .list-unstyled li span {
            color: {$header_data.color.items}
        }

        .order_row, .out_of_stock_row, .product_order {
            background-color: {$website->get('Website Button Color')};
            color: {$website->get('Website Button Text Color')}

        }

        .order_row.empty:hover {
            background-color: {$website->get('Website Active Button Color')};
            color: {$website->get('Website Active Button Text Color')}

        }

        .order_row.ordered {
            background-color: {$website->get('Website Active Button Color')};
            color: {$website->get('Website Active Button Text Color')}

        }

        {if isset($extra_style)}{$extra_style}{/if}

    </style>

</head>


