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
    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- ######### CSS STYLES ######### -->


    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/editor/froala_editor.css" rel="stylesheet"/>
    <link href="/css/editor/froala_style.css" rel="stylesheet"/>

    <link href="/css/editor/codemirror.css" rel="stylesheet">
    <link href="/css/editor/codemirror_dracula.css" rel="stylesheet">
    <link href="/css/editor/plugins/char_counter.css" rel="stylesheet">
    <link href="/css/editor/plugins/code_view.css" rel="stylesheet">
    <link href="/css/editor/plugins/colors.css" rel="stylesheet">
    <link href="/css/editor/plugins/emoticons.css" rel="stylesheet">
    <link href="/css/editor/plugins/file.css" rel="stylesheet">
    <link href="/css/editor/plugins/fullscreen.css" rel="stylesheet">
    <link href="/css/editor/plugins/image.css" rel="stylesheet">
    <link href="/css/editor/plugins/image_manager.css" rel="stylesheet">
    <link href="/css/editor/plugins/line_breaker.css" rel="stylesheet">
    <link href="/css/editor/plugins/quick_insert.css" rel="stylesheet">
    <link href="/css/editor/plugins/table.css" rel="stylesheet">
    <link href="/css/editor/plugins/video.css" rel="stylesheet">
    <link href="/css/editor/plugins/draggable.css" rel="stylesheet">



    <link rel="stylesheet" href="/EcomB2B/css/style.theme_1.EcomB2B.css" type="text/css"/>







       <!-- <link rel="stylesheet" href="https://use.fontawesome.com/3052dece40.css"> -->
    <link rel="stylesheet" href="/theme_1/local/font-awesome/css/font-awesome.min.css">

    <!--
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">




    <link rel="stylesheet" href="/theme_1/css/aurora.css">
    <link rel="stylesheet" href="/css/webpage_preview.css" type="text/css"/>



       <!--  <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
      <script src="/theme_1/local/jquery.js"></script>



    <!--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->

    <!--  <script src="/theme_1/local/bootstrap.js"></script> -->



    <script type="text/javascript" src="/theme_1/tabs/assets/js/responsive-tabs.min.js"></script>


    <script type="text/javascript" src="/js/libs/sweetalert.min.js"></script>


    <script type="text/javascript" src="/js/libs/editor/froala_editor.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/codemirror.js"></script>
    <script type="text/javascript" src="/js/libs/editor/codemirror.xml.js"></script>
    <script type="text/javascript" src="/js/libs/editor/codemirror_active-line.js"></script>

    <script type="text/javascript" src="/js/libs/editor/plugins/align.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/draggable.min.js"></script>

    <script type="text/javascript" src="/js/libs/editor/plugins/char_counter.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/code_beautifier.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/code_view.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/colors.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/emoticons.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/entities.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/file.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/font_family.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/font_size.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/fullscreen.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/image.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/image_manager.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/inline_style.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/line_breaker.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/link.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/lists.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/paragraph_format.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/paragraph_style.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/quick_insert.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/quote.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/table.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/save.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/url.min.js"></script>
    <script type="text/javascript" src="/js/libs/editor/plugins/video.min.js"></script>


     <script type="text/javascript" src="/theme_1/tooltips/jquery.darktooltip.js"></script>

     <script src="/theme_1/aninum/jquery.animateNumber.min.js"></script>
    <script  src="/theme_1/animations/js/animations.min.js" type="text/javascript"></script>

     <script type="text/javascript" src="/theme_1/cubeportfolio/js/jquery.cubeportfolio.js"></script>


    <!-- mega menu -->

    <script src="/theme_1/mainmenu/customeUI.js"></script>


   {if isset($header)}
    <script type="text/javascript" src="/theme_1/mainmenu/sticky.js"></script>
    <script type="text/javascript" src="/theme_1/mainmenu/modernizr.custom.75180.js"></script>
   {/if}

    <script type="text/javascript" src="/js/libs/jquery-ui.js"></script>


    <script type="text/javascript" src="/theme_1/masterslider/masterslider.min.js"></script>

    <script type="text/javascript" src="/js/libs/base64.js"></script>

    <script type="text/javascript" src="/js/edit.js"></script>
    <script type="text/javascript" src="/js/validation.js"></script>
    <script type="text/javascript" src="/js/common_webpage_preview.js"></script>

    <script type="text/javascript" src="/js/libs/tinycolorpicker.js"></script>

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


