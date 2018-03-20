{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2017 at 16:50:32 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="xpage-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">


        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            <div class="menu-bar xmenu-bar-text" style="margin:0px;height:50px;position: relative;top:-10px;border-bottom:1px solid #ccc">

                    <em class="menu-bar-text-1   ">
                        <a href="/" style="color:#1f2f1f"> <i class="fa fa-home" aria-hidden="true"></i></a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                        {$content._title} <i class="fa fa-heart padding_left_5"></i>
                    </em>



                <div class="menu-bar-title" style="position: relative;"></div>
            </div>




            <div class="content">

                <div class="asset_description   fr-view">
                    <div id="_with_items_div" class="{if $products|@count==0}hide{/if}">{$content._text}</div>
                    <div id="_no_items_div" class="{if ($products|@count)>0}hide{/if}">{$content._text_empty}</div>
                </div>


                {foreach from=$products item=product_data key=stack_index}



                        {assign 'product' $product_data.object}
                        <div class="store-item-list">
                    <span class="sub_wrap" style="">


                        <a href="{$product->get('Code')|strtolower}" style="z-index: 10000;"><img src="{$product->get('Image Mobile In Family Webpage')}" alt="{$product->get('Name')|escape}"></a>



                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">

                            <div class="description"  {if ($product->get('Name')|count_characters)>40} style="font-size: 80% {elseif ($product->get('Name')|count_characters)>35}{/if}">{$product->get('Name')}</div>
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
                                <div class="mobile_ordering"  data-settings='{ "pid":{$product->id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="{$quantity_ordered}" class="needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save far fa-fw fa-save color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                            </div>
                            {/if}

                            {/if}
                        </em>
                             <u>{$product->get('Code')}</u>

                    </span>


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
