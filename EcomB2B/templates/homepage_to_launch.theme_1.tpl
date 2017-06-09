{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 June 2017 at 13:16:18 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3
-->*}
<!doctype html>
<!--[if IE 7 ]>    <html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en-gb" class="isie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en-gb" class="no-js"> <!--<![endif]-->

<head>
    <title>{$webpage->get('Webpage Browser Title')}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>


    <!--
    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>


    <link rel="shortcut icon" href="images/favicon.ico">
    -->


    <!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google fonts - witch you want to use - (rest you can just remove) -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script:700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- ######### CSS STYLES ######### -->

    <link rel="stylesheet" href="/theme_1/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/theme_1/css/style_full.css" type="text/css"/>

    <link rel="stylesheet" href="/theme_1/local/font-awesome/css/font-awesome.min.css">

    <!-- animations -->
    <link href="/theme_1/animations/css/animations.min.css" rel="stylesheet" type="text/css" media="all"/>

    <!-- responsive devices styles -->
    <link rel="stylesheet" media="screen" href="/theme_1/css/responsive-layouts.css" type="text/css"/>

    <!-- shortcodes -->
    <link rel="stylesheet" media="screen" href="/theme_1/css/shortcodes.css" type="text/css"/>









    <link rel="stylesheet" href="/theme_1/flipclock/flipclock.css">
    <link rel="stylesheet" media="screen" href="/theme_1/comingsoon/homepage_to_launch.css" type="text/css"/>


    <script src="/theme_1/local/jquery.js" type="text/javascript"></script>
    <script src="/theme_1/local/moment.min.js" type="text/javascript"></script>


</head>


<body>
<script>
    (function(i,s,o,g,r,a,m){
        i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-100778677-1', 'auto');
    ga('send', 'pageview');

</script>
<div id="bg-body"></div><!--end -->


<div class="site_wrapper">

    <div class="comingsoon_page">
        <div class="container" style=""  >

            <div class="topcontsoon">

                {if $content._img!=''  and  $content._img!='art/image_350x150.png'  }
                <div id="show_img" class="show_div   {if !$content.show_img   }hide{/if}">


                            <img id="_img"   src="{$content._img}" alt=""/>


                </div>

                <div class="clearfix"></div>
                {/if}
                <h5  id="_title" >{$content._title}</h5>



            </div><!-- end section -->
            <div class="clearfix"></div>



                <div id="show_countdown" launch_date="{$content._launch_date}"  class="show_div {if !$content.show_countdown  or $content._launch_date=='' }hide{/if}">


                    <div class="countdown_dashboard" style="" >

                        <div class="clock" ></div>



                </div>

            </div>

            <div class="clearfix"></div>



            <div class="text_email">

                <p  id="_text">{$content._text}</p>
                <div id="show_email_form" class="show_div {if !$content.show_email_form}hide{/if}">

                <div class="clearfix marb4"></div>
                
                </div>
                <div class="clearfix"></div>


            </div><!-- end section -->



        </div>
    </div>

</div>



<script src="/theme_1/animations/js/animations.min.js" type="text/javascript"></script>


<script src="/theme_1/flipclock/flipclock.min.js"></script>


<script type="text/javascript">




    var seconds = -1*moment().diff("{$content._launch_date}", 'seconds');


    console.log(seconds)

    var clock = $('.clock').FlipClock(seconds, {
        clockFace: 'DailyCounter',
        countdown: true
    });
</script>


</body>
</html>
