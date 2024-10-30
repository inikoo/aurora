{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 15:30:02 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>


    .activity-item strong{
        padding-left: 0px;
    }

</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" class="{if !$data.show}hide{/if} product_container"  data-product_id="{$product->id}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

                <div style="padding:20px">
                <div class="images one-half-responsive">

                    <figure class="main_image" style="margin: 0px;padding:0px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">

                        <a href="{$data.image.src}" itemprop="contentUrl" data-w="{$data.image.width}" data-h="{$data.image.height}">
                            <img style="max-height: 450px;margin:0px auto" src="{if $data.image.image_website=='' }{$data.image.src}{else}{$data.image.image_website}{/if}" itemprop="image" alt="{$data.image.caption}">
                        </a>
                    </figure>

                    <div class="gallery tw-w-[95%] tw-overflow-x-auto tw-mx-auto tw-flex tw-h-[110px] tw-gap-x-2"  itemscope itemtype="http://schema.org/ImageGallery">


                        {if $product->get('Video ID')}

                            <script src="https://player.vimeo.com/api/player.js"></script>




                            </script>

                            <script>
                              function show_video(){

                                $('#the_big_video_modal').removeClass('hide');

                              }

                              function close_video_modal(){
                                var iframe = document.querySelector('#the_big_video');
                                var player = new Vimeo.Player(iframe);

                                $('#the_big_video_modal').addClass('hide');
                                player.pause();

                              }


                            </script>


                            <div id="the_big_video_modal" class="hide tw-fixed tw-top-0 tw-left-0 tw-w-[100vw] tw-h-[100vh] tw-bg-black/70 tw-isolate tw-z-[9999999999999]">
                                <div class="tw-absolute tw-top-1/2 tw-left-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-w-[90%]">

                                    <iframe id="the_big_video" src="https://player.vimeo.com/video/{$product->get('Video ID')}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&pip=0&title=0&vimeo_logo=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" style="aspect-ratio: 1 / 1; height: auto; width:100%; max-height: 400px" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    <div onclick="close_video_modal()" class="tw-mt-4 tw-flex tw-flex-col tw-items-center tw-absolute tw-left-1/2 -tw-translate-x-1/2 tw-text-white">
                                        <div class="tw-h-5 tw-w-5 tw-rounded-full tw-flex tw-justify-center tw-items-center tw-border tw-border-solid tw-border-white">
                                            <i class="fas fa-times tw-text-2xl"></i>
                                        </div>
                                        <div>
                                            Close
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tw-w-[100px] tw-h-[100px] tw-relative tw-isolate">
                                <iframe id="the_video" src="https://player.vimeo.com/video/{$product->get('Video ID')}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&&background=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" style="aspect-ratio: 1 / 1; height: 100%; width:auto;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                <div onclick="show_video()" style="cursor:pointer"  class="tw-absolute tw-inset-0 tw-z-10 tw-flex tw-justify-center tw-items-center"><i style="padding:10px;font-size:30px;color:#4B5058;--fa-secondary-opacity:.9;--fa-primary-opacity:1;--fa-primary-color:white" class="fad fa-play-circle"></i></div>


                            </div>

                        {/if}

                        {foreach from=$data.other_images item=image name=foo}
                            <figure style="margin: 0px 5px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                                    <img style="height: 100px" src="wi.php?id={$image.key}&s=400x400'" itemprop="thumbnail" alt="{$image.caption}"/>
                                </a>
                            </figure>
                        {/foreach}
                    </div>
                </div>

                <div class="one-half-responsive last-column ">
                    <h1 >{$product->get('Code')}</h1>
                    <h2 >{$product->get('Name')}</h2>
                    {if $logged_in}


                        {if $product->get('Web State')=='Out of Stock'}
                            <div style="margin-top: 10px" class="notification-small  bg-red-light ">
                                <strong class="bg-red-dark ">
                                    <i class="fa fa-frown"></i></strong>
                                <p style="line-height: 50px;font-size: 120%">
                                    {if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}

                                    <i data-product_id="{$product->id}"
                                       data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                       data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$product->id} margin_left_5" aria-hidden="true"></i>
                                </p>


                            </div>
                            {if   $product->get('Next Supplier Shipment Timestamp')>$smarty.now   }
                                <div class="color-red-dark " style="line-height: 20px;font-size: 100%">{t}Expected{/t}: {$product->get('Next Supplier Shipment Timestamp')|date_format:"%x"}</div>
                            {/if}
                        {elseif $product->get('Web State')=='For Sale'}
                            {if $store->get('Store Type')=='Dropshipping'}
                                <div class="portfolio_row  portfolio_row_{$product->id} "  style="background: none;color:#000;border-left:1px solid #ccc;border-right:1px solid #ccc" >

                                    <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button " style="text-align: center"> <i class="fa fa-plus padding_right_5"></i>
                                        {if empty($labels._add_to_portfolio)}{t}Portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                                    </div>
                                    <div class="edit_portfolio_item remove_from_portfolio hide " style="position:relative;"> <i class="fa fa-store-alt padding_right_5"></i>
                                        {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if} <i style="position: absolute;right:10px;bottom:7.5px" class="far edit_portfolio_item_trigger fa-trash-alt  sim_button" title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                                    </div>

                                </div>

                            {else}
                            <div>
                                <div class="mobile_ordering" data-settings='{ "pid":{$product->id} }'>
                                    <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                    <input type="number" min="0" value="" class="needsclick order_qty">
                                    <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                                    <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                                </div>
                            </div>
                            {/if}
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
                        <div class="container">
                            <div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 140%;">
                                <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')} <span style="font-size: 90%">{$product->get('Price Per Unit')}</span></div>
                                {assign 'rrp' $product->get('RRP')}
                                {if $rrp!=''}
                                    <div style="margin-top:4px">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}

                                {if $logged_in and  isset($settings['Display Stock Levels in Product']) and $settings['Display Stock Levels in Product']=='Yes'}
                                    <div style="margin-top:15px">
                                        {t}Stock{/t}: <i class="product_stock_dot fa fa-circle stock_level_{$product->id}"></i> <span class="product_stock_label_{$product->id}"></span>
                                    </div>
                                {/if}

                            </div>






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

                        <div class="one-half-responsive">
                        <p>
                            {$data.text|replace:'<p><br></p>':''}
                        </p>

                        </div>

                        <div class="one-half-responsive last-column ">
                            {assign 'origin' $product->get('Origin')}
                            {assign 'weight' $product->get('Unit Weight Formatted')}
                            {assign 'weight_gross' $product->get('Package Weight')}
                            {assign 'dimensions' $product->get('Unit Dimensions')}
                            {assign 'materials' $product->get('Materials')}
                            {assign 'barcode' $product->get('Barcode Number')}
                            {assign 'cpnp' $product->get('CPNP Number')}
                            {assign 'ufi' $product->get('UFI')}


                            <div >

                            <div class="activity-item {if $origin==''}hide{/if}">
                                <div class=" one-half-responsive ">

                                    <strong>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column ">

                                    <span style="float:right" class="origin">{$origin}</span>
                                </div>
                            </div>

                            <div class="activity-item {if $weight=='' or $weight=='0Kg'}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_weight)}{t}Net weight{/t}{else}{$labels._product_weight}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$weight}</span>
                                </div>
                            </div>
                                <div class="activity-item {if $weight_gross=='' or $weight_gross=='0Kg'}hide{/if}">
                                    <div class=" one-half-responsive ">
                                        <i class="ion-record color-green-dark"></i>
                                        <strong>{if empty($labels._product_weight_gross)}{t}Shipping weight{/t}{else}{$labels._product_weight_gross}{/if}</strong>
                                    </div>
                                    <div class="one-half-responsive last-column"  >

                                        <span style="float:right" class="origin">{$weight_gross}</span>
                                    </div>
                                </div>

                            <div class="activity-item {if $dimensions==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$dimensions}</span>
                                </div>
                            </div>


                            <div class="activity-item {if $barcode==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$barcode}</span>
                                </div>
                            </div>

                            <div class="activity-item {if $cpnp==''}hide{/if}">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>CPNP</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <span style="float:right" class="origin">{$cpnp}</span>
                                </div>
                            </div>

                                <div class="activity-item {if $ufi==''}hide{/if}">
                                    <div class=" one-half-responsive ">
                                        <i class="ion-record color-green-dark"></i>
                                        <strong>UFI</strong>
                                    </div>
                                    <div class="one-half-responsive last-column"  >

                                        <span style="float:right" class="origin">{$ufi}</span>
                                    </div>
                                </div>


                            <div class="activity-item {if $materials==''}hide{/if}" style="border-bottom: none">
                                <div class=" one-half-responsive ">
                                    <i class="ion-record color-green-dark"></i>
                                    <strong>{if empty($labels._product_materials)}{t}Materials{/t}/{t}Ingredients{/t}{else}{$labels._product_materials}{/if}</strong>
                                </div>
                                <div class="one-half-responsive last-column"  >

                                    <div style="float:right;line-height: 150%;text-align: right">{$materials}</div>
                                    <div class="clear"></div>
                                </div>
                            </div>


                        </div>

                            <div class="clear"></div>
                        </div>


                    </div>
                </div>
                <div class="clear"></div>
</div>

<script>
    ga('auTracker.ec:addProduct', { 'id': '{$product->get('Code')}',  'category': '{$product->get('Family Code')}','price': '{$product->get('Product Price')}','name': '{$product->get('Name')|escape:'quotes'}', });
    ga('auTracker.ec:setAction', 'detail');
</script>



