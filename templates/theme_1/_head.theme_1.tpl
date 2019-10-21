{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 May 2017 at 09:10:29 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<!doctype html><!--[if IE 7 ]>
<html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]--><!--[if IE 8 ]>
<html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]--><!--[if IE 9 ]>
<html lang="en-gb" class="isie ie9 no-js"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-gb" class="no-js"> <!--<![endif]-->

<head>
    <title>{$webpage->get('Webpage Browser Title')}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>

     <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="/css/jquery-ui.css" rel="stylesheet">

    <link href="/css/editor_v1/froala_editor.css?v=2a" rel="stylesheet"/>
    <link href="/css/editor_v1/codemirror.css" rel="stylesheet">
    <link href="/css/editor_v1/codemirror_dracula.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/char_counter.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/code_view.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/colors.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/emoticons.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/file.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/fullscreen.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/image.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/image_manager.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/line_breaker.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/quick_insert.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/table.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/video.css" rel="stylesheet">
    <link href="/css/editor_v1/plugins/draggable.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://widget.reviews.io/rich-snippet-reviews-widgets/dist.js"></script>

    <script src="EcomB2B/assets/desktop.logged_in.min.js"></script>




    <script src="/js/edit_webpage_upload_images_from_iframe.js"></script>


    <script src="/js_libs/sweetalert.min.js"></script>

    <link rel="stylesheet" href="EcomB2B/assets/desktop.min.css" type="text/css"/>
    <link rel="stylesheet" href="EcomB2B/assets/forms.min.css" type="text/css"/>

    {assign "logged_in" true}

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
    {assign "with_reset_password" false}
    {assign "with_telephone" false}
    {assign "with_product_order_input" false}
    {assign "basket" false}
    {assign "profile" false}
    {assign "checkout" false}
    {assign "favourites" false}
    {assign "thanks" false}


    {foreach from=$content.blocks item=$block }
        {if $block.show}

            {if $block.type=='basket'} {if $logged_in}{assign "with_basket" 1} {assign "with_forms" 1} {/if}
            {elseif $block.type=='profile'} {if $logged_in}{assign "profile" 1} {assign "with_forms" 1} {/if}
            {elseif $block.type=='checkout'} {if $logged_in}{assign "checkout" 1} {assign "with_forms" 1} {/if}
            {elseif $block.type=='favourites'} {if $logged_in}{assign "favourites" 1} {/if}
            {elseif $block.type=='thanks'} {if $logged_in}{assign "thanks" 1} {/if}
            {elseif $block.type=='reset_password'} {if $logged_in}{assign "with_reset_password" 1} {/if}
            {else}
                {if $block.type=='search'   }{assign "with_search" 1}{/if}
                {if $block.type=='login'   }{assign "with_login" 1} {assign "with_forms" 1}{/if}
                {if $block.type=='register'   }{assign "with_register" 1} {assign "with_forms" 1}{/if}
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
        <link rel="stylesheet" href="EcomB2B/css/forms.min.css" type="text/css"/>

    {/if}




    <link rel="stylesheet" href="/css/color_picker.css" type="text/css"/>

    <link rel="stylesheet" href="/css/webpage_preview/webpage_preview.css?v20180422v1" type="text/css"/>


    <script src="EcomB2B/assets/desktop.in.min.js"></script>


    <script src="js_libs/jquery-ui.js"></script>
    <script src="js_libs/color_picker.js"></script>
    <script src="js/webpage_blocks.text_block.edit.js"></script>


    <script src="/js_libs/editor_v1/froala_editor.min.js"></script>
    <script src="/js_libs/editor_v1/codemirror.js"></script>
    <script src="/js_libs/editor_v1/codemirror.xml.js"></script>
    <script src="/js_libs/editor_v1/codemirror_active-line.js"></script>

    <script src="/js_libs/editor_v1/plugins/align.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/draggable.min.js"></script>

    <script src="/js_libs/editor_v1/plugins/char_counter.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/code_beautifier.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/code_view.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/colors.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/emoticons.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/entities.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/file.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/font_family.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/font_size.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/fullscreen.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/image.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/image_manager.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/inline_style.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/line_breaker.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/link.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/lists.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/paragraph_format.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/paragraph_style.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/quick_insert.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/quote.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/table.min.js"></script>



    <script src="/js_libs/editor_v1/plugins/url.min.js"></script>
    <script src="/js_libs/editor_v1/plugins/video.min.js"></script>



    <script src="/js_libs/base64.js"></script>

    <script src="/js/edit.js"></script>
    <script src="/js/validation.js"></script>
    <script src="/js/common_webpage_preview.js"></script>


    <style>

        a[href="https://www.froala.com/wysiwyg-editor?k=u"] {
            display: none !important;
            position: absolute;
            top: -99999999px;
        }


         .custom-layer {
             text-align: center;
             padding: 10px;
         }

    </style>





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



        {if isset($extra_style)}{$extra_style}{/if}


        {if isset($extra_style)}{$extra_style}{/if}

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

        {if $checkout==1}


        .search_container,#favorites_button,#profile_button,#header_order_totals,#logout{
            display:none
        }

        #go_back_basket{
            display: initial;
        }

        #bottom_header .menu{
            display:none
        }
        .order_header{
            padding:0px 30px
        }

        .order_header .totals{
            padding-right: 20px;
            text-align: right;

        }

        .totals  table{
            width: initial;
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
            padding-left: 20px;
        }

        #profile_menu li{
            list-style-type: none;
            margin-bottom: 10px;
            font-size: 16px;
        }
        {/if}
        .bannerl-009-spin img {
            animation-name: bannerl-009-spin;
            animation-duration: 1.6s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes bannerl-009-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .bannerb051 .i { position:absolute; cursor:pointer;}
        .bannerb051 .i .b { fill:#fff; fill-opacity:0.5; }
        .bannerb051 .i:hover .b { fill-opacity:.7; }
        .bannerb051 .iav .b { fill-opacity: 1; }
        .bannerb051 .i.idn { opacity:.3; }

        .bannera051 { display:block; position:absolute; cursor:pointer; }
        .bannera051 .a { fill:none; stroke:#fff; stroke-width:360; stroke-miterlimit:10; }
        .bannera051:hover { opacity:.8; }
        .bannera051.bannera051dn { opacity:.5; }
        .bannera051.bannera051ds { opacity:.3; pointer-events:none; }
    </style>



    <style >

        {foreach from=$website->style  item=style  }
            {$style[0]}{ {$style[1]}: {$style[2]}}
        {/foreach}


    </style>



</head>


