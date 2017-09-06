{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:07:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">


    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}



    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->


            {assign 'parent_family'  $product->get_parent_category('data') }
            {assign 'prev_product'  $product->get_prev_product('data') }
            {assign 'next_product'  $product->get_next_product('data') }

            <div class="menu-bar menu-bar-text" style="margin-bottom: 0px">


                <span  class="menu-bar-text-1">
                  {if $prev_product}<a href="{$prev_product.webpage_code}" class="color-black " style="margin-right: 5px"><i class="fa fa-arrow-left"></i></a>{/if}
                    &nbsp;&nbsp;<a href="/{$parent_family.webpage_code}" class="color-black " ><i class="fa fa-arrow-up"></i> {$parent_family.code}</a>
                </span>
                <span  class="menu-bar-title">{$product->get('Code')}</span>


                {if $next_product}<span  class="menu-bar-text-2 "><a href="{$next_product.webpage_code}" class="color-black"><i class="fa fa-arrow-right"></i></a></span>{/if}
            </div>

            <div class="content-fullscren">



                <div class="store-slider no-bottom">
                    <div class="swiper-wrapper">

                        {foreach from=$product->get_images_slidesshow() item=image name=foo}
                            <a href="#" class="swiper-slide store-slider-item">
                                <img class="responsive-image no-bottom" src="{$image.image_product_webpage}" alt="{$product->get('Code')|escape}">
                            </a>
                        {/foreach}



                    </div>
                </div>

                <div class="decoration-lines container-fullscreen">
                    <div class="deco-0"></div>
                    <div class="deco-1"></div>
                    <div class="deco-2"></div>
                    <div class="deco-3"></div>
                    <div class="deco-4"></div>
                    <div class="deco-5"></div>
                    <div class="deco-6"></div>
                    <div class="deco-7"></div>
                    <div class="deco-8"></div>
                    <div class="deco-9"></div>
                </div>
            </div>

            <div class="content single_line_height">
                <div class="store-product-header">
                    <h2 class="center-text">{$product->get('Name')}</h2>

                    {if $logged_in}
                    <div class="store-product-socials full-bottom " style="text-align: center">


                        {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }



                        <div class="mobile_ordering" data-settings='{ "pid":{$product->id} }'>
                            <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                            <input  type="number" min="0" value="{$quantity_ordered}" class="needsclick order_qty">
                            <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save fa fa-fw fa-floppy-o color-blue-dark"></i>
                            <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                        </div>

                    </div>
                    {else}

                        <div class="notification-small bg-red-light tap-hide animate-right">
                            <strong class="bg-red-dark"><i class="ion-information-circled"></i></strong>
                            <p>
                                {if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}
                            </p>
                        </div>




                    {/if}

                    <div class="decoration half-bottom full-top"></div>

                    {if $logged_in}
                    <div class="store-product-rating half-top">
                        <h2>{t}Price{/t}: {$product->get('Price')}</h2>
                        <span>{t}RRP{/t}: {$product->get('RRP')}</span>
                    </div>
                     {else}

                        <div class="container">
                            <div class="one-half">
                                <a href="/login.sys" class="button button-icon button-blue button-round button-full button-xs no-bottom"><i class="ion-log-in"></i>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</a>
                            </div>
                            <div class="one-half last-column">
                                <a href="/register.sys" class="button button-icon button-green button-round button-full button-xs no-bottom"><i class="ion-android-add-circle"></i>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</a>
                            </div>
                            <div class="clear"></div>
                        </div>

                    {/if}
                    <div class="store-product-icons">

                    </div>
                    <div class="decoration half-top"></div>

                    <p >
                        {$content.description_block.content|replace:'<p><br></p>':''}
                    </p>
                    <div class="clear"></div>

                    <div class="activity-page">

                        <div class="activity-item one-half-responsive {if $Origin==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</strong>
                            <span style="float:right" class="origin">{$Origin}</span>
                        </div>

                        <div class="activity-item one-half-responsive {if $Weight==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>{if empty($labels._product_weight)}{t}Weight{/t}{else}{$labels._product_weight}{/if}</strong>
                            <span style="float:right">{$Weight}</span>
                        </div>

                        <div class="activity-item one-half-responsive {if $Dimensions==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</strong>
                            <span style="float:right">{$Dimensions}</span>
                        </div>

                        <div class="activity-item one-half-responsive {if $Barcode==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</strong>
                            <span style="float:right">{$Barcode}</span>
                        </div>
                        <div class="activity-item one-half-responsive {if $CPNP==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>CPNP</strong>
                            <span style="float:right">{$CPNP}</span>
                        </div>
                        <div class="activity-item one-half-responsive {if $Materials==''}hide{/if}">
                            <i class="ion-record color-green-dark"></i>
                            <strong>{if empty($labels._product_materials)}{t}Materials{/t}/{t}Ingredients{/t}{else}{$labels._product_materials}{/if}</strong>
                            <span style="float:right;line-height: 150%;text-align: right">{$Materials}</span>
                        </div>
                    </div>








                    </div>
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
</body>

