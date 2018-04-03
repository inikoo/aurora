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

    <!-- Favicon -->


    <!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- ######### CSS STYLES ######### -->


    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/editor_v1/froala_editor.css" rel="stylesheet"/>

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



    <link rel="stylesheet" href="/EcomB2B/css/fontawesome-all.min.css" type="text/css"/>

    <link rel="stylesheet" href="/EcomB2B/css/style.theme_1.EcomB2B.css?v180401v1" type="text/css"/>









    <!--
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">




    <link rel="stylesheet" href="/css/webpage_preview/webpage_preview.css?v20180329v4" type="text/css"/>



       <!--  <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
      <script src="/theme_1/local/jquery.js"></script>



    <!--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->

    <!--  <script src="/theme_1/local/bootstrap.js"></script> -->



    <script src="/theme_1/tabs/assets/js/responsive-tabs.min.js"></script>


    <script src="/js/libs/sweetalert.min.js"></script>


    <script src="/js/libs/editor_v1/froala_editor.min.js"></script>
    <script src="/js/libs/editor_v1/codemirror.js"></script>
    <script src="/js/libs/editor_v1/codemirror.xml.js"></script>
    <script src="/js/libs/editor_v1/codemirror_active-line.js"></script>

    <script src="/js/libs/editor_v1/plugins/align.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/draggable.min.js"></script>

    <script src="/js/libs/editor_v1/plugins/char_counter.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/code_beautifier.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/code_view.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/colors.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/emoticons.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/entities.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/file.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/font_family.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/font_size.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/fullscreen.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/image.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/image_manager.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/inline_style.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/line_breaker.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/link.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/lists.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/paragraph_format.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/paragraph_style.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/quick_insert.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/quote.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/table.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/save.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/url.min.js"></script>
    <script src="/js/libs/editor_v1/plugins/video.min.js"></script>


     <script src="/theme_1/tooltips/jquery.darktooltip.js"></script>

     <script src="/theme_1/aninum/jquery.animateNumber.min.js"></script>
    <script  src="/theme_1/animations/js/animations.min.js" ></script>

     <script src="/theme_1/cubeportfolio/js/jquery.cubeportfolio.js"></script>


    <!-- mega menu -->

    <script src="/theme_1/mainmenu/customeUI.js"></script>


   {if isset($header)}
    <script src="/theme_1/mainmenu/sticky.js"></script>
    <script src="/theme_1/mainmenu/modernizr.custom.75180.js"></script>
   {/if}

    <script src="/js/libs/jquery-ui.js"></script>


    <script src="/theme_1/masterslider/masterslider.min.js"></script>

    <script src="/js/libs/base64.js?v3"></script>

    <script src="/js/edit.js"></script>
    <script src="/js/validation.js"></script>
    <script src="/js/common_webpage_preview.js"></script>

    <script src="/js/libs/tinycolorpicker.js"></script>

    <link rel="stylesheet" href="/website.color.css.php?website_key={$website->id}&theme=theme_1" type="text/css"/>


    {if $website->get('Website Text Font')!=''}
        <link href="https://fonts.googleapis.com/css?family={$website->get('Website Text Font')}:400,700" rel="stylesheet">
    {/if}

    <style>
        {if $website->get('Website Text Font')!=''}
        body{
            font-family: '{$website->get('Website Text Font')}', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: '{$website->get('Website Text Font')}', sans-serif;

        }

        {/if}

        </style>

</head>


