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
        <script>(function(w,d,s,l,i){ w[l]=w[l]||[];w[l].push({ 'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                    })(window,document,'script','dataLayer','{$client_tag_google_manager_id}');</script>
        <!-- End Google Tag Manager -->
    {/if}
    <title>{$webpage->get('Webpage Browser Title')}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content=""/>
    <meta name="description" content="{$webpage->get('Webpage Meta Description')}"/>

    <link rel="shortcut icon" type="image/png" href="art/favicon.png"/>

    <!--
    <link rel="shortcut icon" href="images/favicon.ico">
 Favicon -->


    <!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google fonts - witch you want to use - (rest you can just remove) -->
    <!--

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Dancing+Script:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans:400,100,100italic,300,300italic,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" id="ms-fonts" href="https://fonts.googleapis.com/css?family=Kaushan+Script:regular|Raleway:regular|Playfair+Display:700" type="text/css" media="all" />
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,700" rel="stylesheet">
-->

    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- ######### CSS STYLES ######### -->
    <link rel="stylesheet" href="https://use.fontawesome.com/3052dece40.css">

    <link rel="stylesheet" href="css/style.theme_1.EcomB2B.min.css?v180112" type="text/css"/>
    <!-- #
       <link rel="stylesheet" href="/theme_1/css/reset.css" type="text/css"/>
               <link rel="stylesheet" href="/theme_1/css/style.css" type="text/css"/>

   -->



    <!--
        <link rel="stylesheet" href="/theme_1/local/font-awesome/css/font-awesome.min.css">

    -->


       <!--
  <link href="/theme_1/mainmenu/bootstrap.min4.css" rel="stylesheet">

    <link href="/theme_1/mainmenu/menu-5.css" rel="stylesheet">
   <link href="/css/sweetalert.css" rel="stylesheet">
       <link href="/theme_1/animations/css/animations.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel="stylesheet" media="screen" href="/theme_1/css/responsive-layouts.css" type="text/css"/>

    <link rel="stylesheet" media="screen" href="/theme_1/css/shortcodes.css" type="text/css"/>

    -->






    <!--



    <link rel="stylesheet" href="/theme_1/masterslider/style/masterslider.css"/>
    <link rel="stylesheet" href="/theme_1/masterslider/skins/default/style.css"/>

    <link href="/theme_1/carouselowl/owl.transitions.css" rel="stylesheet">
    <link href="/theme_1/carouselowl/owl.carousel.css" rel="stylesheet">

    <link rel="stylesheet" href="/theme_1/iconhoverefs/component.css"/>
    <link rel="stylesheet" href="/theme_1/basicslider/bacslider.css"/>


    <link rel="stylesheet" href="/theme_1/flexslider/flexslider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="/theme_1/flexslider/skin.css"/>
    <link rel="stylesheet" type="text/css" href="/theme_1/tooltips/darktooltip.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">


    <link rel="stylesheet" href="/theme_1/cubeportfolio/css/cubeportfolio.min.css">
        <link href="/css/fotorama.css" rel="stylesheet">
    <link rel="stylesheet" href="/theme_1/sky_forms/css/sky-forms.css" type="text/css" media="all">

 <link rel="stylesheet" type="text/css" href="/theme_1/tabs/assets/css/responsive-tabs.css">
    <link rel="stylesheet" type="text/css" href="/theme_1/tabs/assets/css/responsive-tabs3.css">
    <link rel="stylesheet" href="/theme_1/css/aurora.theme_1.EcomB2B.css" type="text/css"/>

     -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">















    <!--  <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
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


    <!--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->

    <!--  <script src="/theme_1/local/bootstrap.js"></script> -->


    <script  src="/js/sweetalert.min.js"></script>





    <script  src="/theme_1/tooltips/jquery.darktooltip.js"></script>

     <script src="/theme_1/aninum/jquery.animateNumber.min.js"></script>
    <script  src="/theme_1/animations/js/animations.min.js" type="text/javascript"></script>

     <script  src="/theme_1/cubeportfolio/js/jquery.cubeportfolio.js"></script>



    <script  src="/theme_1/tabs/assets/js/responsive-tabs.min.js"></script>



        <script src="/theme_1/mainmenu/customeUI.js"></script>
        <script  src="/theme_1/mainmenu/sticky.js"></script>
        <script  src="/theme_1/mainmenu/modernizr.custom.75180.js"></script>





    <script  src="/js/jquery.form.min.js"></script>
    <script  src="/js/sha256.js"></script>
    <script  src="/js/aurora.js?20180115"></script>
    <script  src="/js/validation.js"></script>

    <script  src="/js/ordering.js?20180115v2"></script>
    <script  src="/js/fotorama.js"></script>

    <link rel="stylesheet" href="/website.color.EcomB2B.css.php?&theme=theme_1" type="text/css"/>


    <script  src="/js/braintree.js"></script>


    {if $smarty.server.SERVER_NAME!='ecom.bali' and $zendesk_chat_code!=''}
        <!--Start of Zendesk Chat Script-->
        <script type="text/javascript">
            window.$zopim||(function(d,s){ var z=$zopim=function(c){
            z._.push(c)},$=z.s=
                d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o) {
                z.set.
            _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
            $.src='https://v2.zopim.com/?{$zendesk_chat_code}';z.t=+new Date;$.
                type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');


            $zopim(function(){
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
        body{
            font-family: '{$website->get('Website Text Font')}', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: '{$website->get('Website Text Font')}', sans-serif;

        }

    {/if}




        header  #topHeader {

            background-color: {$header_data.color.header_background};
        {if $header_data.background_image!=''}
            background-image: url({$header_data.background_image});
        {/if}

            color: {$header_data.color.header};


        }

        header   #trueHeader{
            background-color: {$header_data.color.menu_background};
            border-bottom-color:  {$header_data.color.menu_background_highlight};
            color: {$header_data.color.menu};
        }



        header    #trueHeader a {
            color: {$header_data.color.menu};
            cursor: pointer;

        }
        header   #trueHeader a:hover {
            color: {$header_data.color.menu_text_background_highlight};

        }

        header   #_columns  .dropdown a:hover {
            background-color: transparent;
        }


        {if isset($header_data.color.items_title)}
        header    #_columns  .dropdown li.item_li:hover > a * {
            color:{$header_data.color.items_title};
        }
        {/if}

        header   #trueHeader .dropdown-menu{

            color: {$header_data.color.items};
        }

        header   #trueHeader .dropdown-menu a{

            color: {$header_data.color.items};
        }

        {if isset($header_data.color.items_title)}

        header  #trueHeader .dropdown-menu a:hover{

            color: {$header_data.color.items_title};
        }
        {/if}


        header   #menu_control_panel .button {
            background-color:  {$header_data.color.menu_background_highlight};
        }

        header  #logo {
            background-image: url({$header_data.logo});


        }

        header  .yamm .dropdown-menu {
            background: {$header_data.color.items_background};
        }



        header   .dropdown-menu li a:hover{
            background:{$header_data.color.items};
            color: {$header_data.color.items_background};
        }




        header  .list-unstyled span.link,.list-unstyled a.link {
            color: {$header_data.color.items};

        }

        header  .list-unstyled li p{
            color: {$header_data.color.items}
        }

        header   .dart {
            color: {$header_data.color.items}

        }
        header  .list-unstyled li i {
            color: {$header_data.color.items}
        }

        header  .list-unstyled li span {
            color: {$header_data.color.items}
        }





    </style>

</head>


