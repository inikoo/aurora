{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 23:37:24 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->

            <div class="content-center">
                <div class="page-404">
                    <h1 class="animate-zoom animate-time-1000">{$content._strong_title}</h1>
                    <h2 class="animate-zoom animate-time-1000">{$content._title}</h2>
                    <p class="animate-fade">
                        {$content._text}

                        {$content._home_guide}
                    </p>



                    <a href="index.php" class="color-gray-dark border-gray-dark animate-fade"><i class="ion-android-home"></i></a>
                </div>
            </div>
            <div class="coverpage-clear"></div>



            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>

    
</div>
</body>
{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body>
</html>

