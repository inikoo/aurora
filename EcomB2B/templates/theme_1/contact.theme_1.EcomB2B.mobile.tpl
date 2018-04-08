{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 September 2017 at 16:51:17 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}

    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->

            <div class="content-fullscreen">
                <iframe class="responsive-image maps no-bottom" src="{$store->get('Store Google Map URL')}"></iframe>
                <a href="pageapp-map.html" class="button button-red button-s button-full uppercase bold">FullScreen Map</a>
            </div>
            <div class="content ">


                <div class="one-half-responsive contact-information last-column">
                    <div class="container no-bottom">
                        <h4>Contact Information</h4>


                        <p class="contact-information">
                            <a href="tel:{$store->get('Telephone')}" class="contact-call"><i class="ion-ios-telephone"></i>{$store->get('Telephone')}</a>
                            <a href="mailto:{$store->get('Email')}" class="contact-mail"><i class="ion-email"></i>{$store->get('Email')}</a>

                        </p>
                        <p class="contact-information">
                            <strong>{t}Address{/t}</strong><br>
                            {$store->get('Address')}
                        </p>
                    </div>
                </div>

            </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>

    <div class="share-bottom share-light">
        <h3>Share Page</h3>
        <div class="share-socials-bottom">
            <a href="https://www.facebook.com/sharer/sharer.php?u=http://www.themeforest.net/">
                <i class="ion-social-facebook facebook-bg"></i>
                Facebook
            </a>
            <a href="https://twitter.com/home?status=Check%20out%20ThemeForest%20http://www.themeforest.net">
                <i class="ion-social-twitter twitter-bg"></i>
                Twitter
            </a>
            <a href="https://plus.google.com/share?url=http://www.themeforest.net">
                <i class="ion-social-googleplus google-bg"></i>
                Google
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=http://www.themeforest.net/&media=https://0.s3.envato.com/files/63790821/profile-image.jpg&description=Themes%20and%20Templates">
                <i class="ion-social-pinterest-outline pinterest-bg"></i>
                Pinterest
            </a>
            <a href="sms:">
                <i class="ion-ios-chatboxes-outline sms-bg"></i>
                Text
            </a>
            <a href="mailto:?&subject=Check this page out!&body=http://www.themeforest.net">
                <i class="ion-ios-email-outline mail-bg"></i>
                Email
            </a>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>



{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body>

</html>

