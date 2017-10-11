{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2017 at 16:33:31 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="xpage-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">


        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            <div class="menu-bar menu-bar-text" style="margin-bottom:5px">
                <a href="{$parent.code|strtolower}" class="menu-bar-text-1"><i class="fa fa-arrow-up"></i><em style="position: relative;left:-4px">{$parent.label|truncate:8:"."}</em></a>
                <span class="menu-bar-title" style="position: relative;left:100px">{$category->get('Label')}</span>
            </div>


            {if !$logged_in}
                <a href="login.sys">
                    <div class="notification-medium bg-yellow-light animate-fade">
                        <strong class="bg-yellow-dark"><i class="ion-alert-circled"></i></strong>
                        <h1>{if empty($labels.trade_only)}{t}Trade only website{/t}{else}{$labels.trade_only}{/if}</h1>
                        <p>
                            {if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}
                        </p>

                    </div>
                </a>
            {/if}

            <div class="content">

                <div class="asset_description   fr-view">
                    <div class="asset_description_wrap">
                        {foreach from=$content_data.description_block.blocks key=id item=data name=foo}


                            {if $data.type=='text' and $data.content!=''}
                                <p>{$data.content}</p>
                            {elseif $data.type=='image'}
                                <img src="{$data.image_src}" style="width:40%;;{if $smarty.foreach.foo.iteration%2} float:left;margin-right:15px;{else}float:right;margin-left:15px;{/if}"
                                     title="{if isset($data.caption)}{$data.caption}{/if}"/>
                            {/if}
                        {/foreach}
                    </div>
                    <div class="clear"></div>
                    <div class="decoration-zig-zag decoration-margins"></div>

                    <p class="read-more"><span class="show_all fa-stack "><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-arrow-down fa-stack-1x fa-inverse"></i></span></p>

                </div>


                {foreach from=$products item=product_data key=stack_index}


                    {if $product_data.type=='product'}
                        {assign 'product' $product_data.object}
                        <div class="store-item-list">
                    <span style="position:relative">


                        <a href="{$product->get('Code')|strtolower}" style="z-index: 10000;"><img src="{$product->get('Image Mobile In Family Webpage')}" alt="{$product->get('Name')|escape}"></a>



                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">

                            <div class="description">
                            {$product->get('Name')}
                            </div>
                            {if $logged_in}
                                <div class="price" style="margin-top: 5px">
                                {t}Price{/t}:{$product->get('Price')}
                            </div>
                                <div class="price">
                                  {t}RRP{/t}: {$product->get('RRP')}
                            </div>

                            {if $product->get('Web State')=='Out of Stock'}

                                <div style="margin-top:10px;"><span style="padding:5px 10px" class="{if $product->get('Out of Stock Class')=='launching_soon'}highlight-green color-white{else}highlight-red color-white{/if}">{$product->get('Out of Stock Label')}</span></div>
                            {elseif $product->get('Web State')=='For Sale'}
                               {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }
                                <div class="mobile_ordering" data-settings='{ "pid":{$product->id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="{$quantity_ordered}" class="needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save fa fa-fw fa-floppy-o color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                            </div>
                            {/if}

                            {/if}
                        </em>
                             <u>{$product->get('Code')}</u>

                    </span>


                        </div>
                    {/if}
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
