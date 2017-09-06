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





            <div class="content" >

                <div class="asset_description   fr-view"  style="margin-bottom:0px">
                    <div class="asset_description_wrap">
                        {foreach from=$content_data.description_block.blocks key=id item=data name=foo}


                            {if $data.type=='text' and $data.content!=''}
                                <p>{$data.content}</p>
                            {elseif $data.type=='image'}

                                {if $smarty.foreach.foo.iteration==1}
                                    <img src="{$data.image_src}" style="width:100%;padding-top:15px"
                                         title="{if isset($data.caption)}{$data.caption}{/if}"/>
                                {else}
                                    <img src="{$data.image_src}" style="width:40%;;{if $smarty.foreach.foo.iteration%2} float:left;margin-right:15px;{else}float:right;margin-left:15px;{/if}"
                                         title="{if isset($data.caption)}{$data.caption}{/if}"/>
                                {/if}





                            {/if}
                        {/foreach}
                    </div>
                    <div class="clear"></div>
                    <div class="decoration-zig-zag decoration-margins" style="margin-bottom:0px"></div>

                    <p class="read-more"><span class="show_all fa-stack "><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-arrow-down fa-stack-1x fa-inverse"></i></span></p>

                </div>




            {foreach from=$sections item=section_data key=section_key}
                <div id="section_{$section_data.key}_container" >


                    {if $section_data.type!='anchor'}
                        <div>
                            <h6 class="single_line_height">{$section_data.title}</h6>
                            <div class="decoration deco-7 decoration-margins" style="margin: 0px;margin-top: 4px"></div>

                            <div class="single_line_height" style="">{$section_data.subtitle}</div>
                        </div>
                    {/if}


                    <div class="store-items clear" style="margin-top:20px;clear: both">
                        {counter assign=i start=0 print=false}

                        {foreach from=$section_data.items item=category_data key=key name=families}
                            {if $category_data.type=='category'}
                                {counter}
                                <div class="store-item"><a href="/{$category_data.webpage_code|lower}"><img src="{$category_data.image_mobile_website}" alt="{$category_data.header_text|strip_tags|escape}"></a>
                                    <div class="single_line_height center-text " style="min-height: 32px">{$category_data.header_text|strip_tags}</div>
                                </div>
                            {/if}
                        {/foreach}
                        {if $i%2==1}
                            <div class="store-item invisible"></div>
                        {/if}
                        <div class="clear"></div>
                    </div>

                </div>
            {/foreach}

            </div>



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
</body>{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body></html>
