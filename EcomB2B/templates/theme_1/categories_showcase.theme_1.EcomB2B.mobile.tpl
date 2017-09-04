{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2017 at 01:24:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->


            <br>


            {foreach from=$sections item=section_data key=section_key}



            <div id="section_{$section_data.key}_container"  class="content" >


                    {if $section_data.type!='anchor'}
<div>
                            <h6  class="single_line_height">{$section_data.title}</h6>
    <div class="decoration deco-7 decoration-margins" style="margin: 0px;margin-top: 4px"></div>

    <div class="single_line_height" style="" >{$section_data.subtitle}</div>
</div>
                    {/if}


                <div class="store-items clear" style="margin-top:20px;clear: both">
                    {counter assign=i start=0 print=false}

                    {foreach from=$section_data.items item=category_data key=key name=families}
                                {if $category_data.type=='category'}
                                    {counter}
                                    <div class="store-item"><a href="/{$category_data.webpage_code|lower}"><img src="{$category_data.image_src}&r=600x375" alt="img"></a><div class="single_line_height center-text " style="min-height: 32px">{$category_data.header_text|strip_tags}</div></div>
                                {/if}
                    {/foreach}
                    {if $i%2==1}<div class="store-item invisible"></div>{/if}
                    <div class="clear"></div>
                </div>

                </div>



            {/foreach}





            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="ion-ios-arrow-up"></i></a>

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
