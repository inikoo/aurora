{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 15:30:02 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tablet.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">


    {include file="theme_1/header.theme_1.EcomB2B.tablet.tpl"}

    <style>
        .swiper-container {
            width: 100%;

        }

    </style>


    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->


            {assign 'parent_family'  $product->get_parent_category('data') }
            {assign 'prev_product'  $product->get_prev_product('data') }
            {assign 'next_product'  $product->get_next_product('data') }


            <div class="menu-bar" style="margin:0px;height:50px;position: relative;top:-5px;border-bottom:1px solid #ccc">

                <em class="menu-bar-text-1   ">
                    <a href="/" style="color:#1f2f1f"> <i class="fa fa-home" aria-hidden="true"></i></a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                    <a href="{$parent_family.parent.code|strtolower}" style="color:#1f2f1f"> {$parent_family.parent.label|truncate:35:"."}</a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>

                    <a href="{$parent_family.code|strtolower}" style="color:#1f2f1f"> {$parent_family.label|truncate:35:"."}</a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                </em>

                <em class="menu-bar-text-2   " >

                    {if $prev_product}<a href="{$prev_product.webpage_code}" class="color-black " style="margin-right: 10px"><i class="fa fa-arrow-left"></i></a>{/if}
                    {$product->get('Code')}
                    {if $next_product}<a href="{$next_product.webpage_code}" class="color-black" style="margin-left: 10px"><i class="fa fa-arrow-right"></i></a>{/if}
                </em>

                <div class="menu-bar-title" style="position: relative;"></div>




            </div>



            <div>

                <div style="padding:20px">
                <div class="one-half-responsive">

                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            {foreach from=$product->get_images_slidesshow() item=image name=foo}
                                <div class="swiper-slide">
                                    <img class="responsive-image no-bottom" src="{$image.image_product_webpage}" alt="{$product->get('Code')|escape}">
                                </div>
                            {/foreach}
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                </div>

                <div class="one-half-responsive last-column ">
                    <h1 class="">{$product->get('Code')}</h1>
                    <h2 class="">{$product->get('Name')}</h2>

                    {if $logged_in}


                        {if $product->get('Web State')=='Out of Stock'}
                            <div style="margin-top: 10px" class="notification-small  {if $product->get('Out of Stock Class')=='launching_soon'}bg-green-light{else}bg-red-light{/if} ">
                                <strong class="{if $product->get('Out of Stock Class')=='launching_soon'}bg-green-dark{else}bg-red-dark{/if} "><i class="ion-information-circled"></i></strong>
                                <p style="line-height: 50px;">
                                    {$product->get('Out of Stock Label')}
                                </p>
                            </div>
                        {elseif $product->get('Web State')=='For Sale'}
                            <div  >


                                {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }


                                <div class="mobile_ordering" data-settings='{ "pid":{$product->id} }'>
                                    <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                    <input type="number" min="0" value="{$quantity_ordered}" class="needsclick order_qty">
                                    <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save far fa-fw fa-save color-blue-dark"></i>
                                    <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                                </div>

                            </div>
                        {/if}
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
                            {if $product->get('RRP')!=''}<span>{t}RRP{/t}: {$product->get('RRP')}</span>{/if}
                        </div>
                    {else}
                        <div class="container">
                            <div class="one-half">
                                <a href="/login.sys" class="button button-icon button-blue button-round button-full button-xs no-bottom"><i
                                            class="ion-log-in"></i>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</a>
                            </div>
                            <div class="one-half last-column">
                                <a href="/register.sys" class="button button-icon button-green button-round button-full button-xs no-bottom"><i
                                            class="ion-android-add-circle"></i>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    {/if}




                </div>
                    <div class="clear"></div>
                </div>

                <div class="content single_line_height clear">
                    <div class="store-product-header">


                        <p>
                            {$content.description_block.content|replace:'<p><br></p>':''}
                        </p>
                        <div class="clear"></div>


                        <div >

                            <div class="activity-item {if $Origin==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column ">

                                    <span style="float:right" class="origin">{$Origin}</span>
                                </div>
                            </div>

                            <div class="activity-item {if $Weight=='' or $Weight=='0Kg'}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_weight)}{t}Weight{/t}{else}{$labels._product_weight}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$Weight}</span>
                                </div>
                            </div>

                            <div class="activity-item {if $Dimensions==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$Dimensions}</span>
                                </div>
                            </div>


                            <div class="activity-item {if $Barcode==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$Barcode}</span>
                                </div>
                            </div>

                            <div class="activity-item {if $CPNP==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>CPNP</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$CPNP}</span>
                                </div>
                            </div>


                            <div class="activity-item {if $Materials==''}hide{/if}" style="border-bottom: none">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_materials)}{t}Materials{/t}/{t}Ingredients{/t}{else}{$labels._product_materials}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <div style="float:right;line-height: 150%;text-align: right">{$Materials}</div>
                                    <div class="clear"></div>
                                </div>
                            </div>




                            <div class="clear"></div>
                        </div>


                    </div>
                </div>
                <div class="clear"></div>

                {include file="theme_1/footer.theme_1.EcomB2B.tablet.tpl"}



            </div>
        </div>


    </div>

    <script>
        var swiper = new Swiper('.swiper-container', {
            navigation: {
                nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev',
            },
        });
        console.log(swiper)
    </script>


</body>

