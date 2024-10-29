{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:07:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="images tw-mb-2">
    <figure class="main_image" style="margin: 0px;padding:0px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
        <a href="{$data.image.src}" itemprop="contentUrl" data-w="{$data.image.width}" data-h="{$data.image.height}">
            <img style="max-height: 450px;margin:0px auto"  src="wi.php?id={$data.image.key}&s=800x800'" itemprop="image" alt="{$data.image.caption}">
        </a>
    </figure>

    <div class="gallery tw-w-[95%] tw-overflow-x-auto tw-mx-auto tw-flex tw-h-[110px] tw-gap-x-2"  itemscope itemtype="http://schema.org/ImageGallery">


        {if $product->get('Video ID')}

            <script>
              function show_video(){



              }


            </script>


            <div class="tw-w-[100px] tw-h-[100px] tw-relative tw-isolate">
                <iframe src="https://player.vimeo.com/video/{$product->get('Video ID')}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&playsinline=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" style="aspect-ratio: 1 / 1; height: 100%; width:auto;" ></iframe>
            </div>

        {/if}

        {foreach from=$data.other_images item=image name=foo}
            <figure onclick="show_video()" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                    <img style="height: 100px" src="wi.php?id={$image.key}&s=400x400'" itemprop="thumbnail" alt="{$image.caption}"/>
                </a>
            </figure>
        {/foreach}
    </div>
</div>

<div class="content single_line_height product_container" data-product_id="{$product->id}" >
    <div class="store-product-header">
        <h3 class="center-text">{$product->get('Code')}</h3>
        <h2 class="center-text">{$product->get('Name')}</h2>

        {if $logged_in}
            {if $product->get('Web State')=='Out of Stock'}
                <div style="margin-top: 10px" class="notification-small  bg-red-light ">
                    <strong class="bg-red-dark "><i class="fa fa-frown"></i></strong>
                    <p style="line-height: 50px;font-size: 140%">
                        {if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}


                        <i data-product_id="{$product->id}"
                           data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                           data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$product->id} margin_left_5" aria-hidden="true"></i>

                    </p>

                    {if   $product->get('Next Supplier Shipment Timestamp')>$smarty.now   }
                        <div class="color-red-dark " style="line-height: 20px;font-size: 140%">{t}Expected{/t}: {$product->get('Next Supplier Shipment Timestamp')|date_format:"%x"}<br></div>
                    {/if}
                </div>

            </div>



            {elseif $product->get('Web State')=='For Sale'}

            {if $store->get('Store Type')=='Dropshipping'}

                <div class="portfolio_row  product portfolio_row_{$product->id} "  >

                    <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button " style="text-align: center"> <i class="fa fa-plus padding_right_5"></i>
                        {if empty($labels._add_to_portfolio)}{t}Portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                    </div>
                    <div class="edit_portfolio_item remove_from_portfolio hide " style="position:relative;"> <i class="fa fa-store-alt padding_right_5"></i>
                        {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if} <i style="position: absolute;right:10px;bottom:7.5px" class="far edit_portfolio_item_trigger fa-trash-alt  sim_button" title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                    </div>

                </div>



             {else}

                <div class="store-product-socials full-bottom " style="text-align: center">
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
                <strong class="bg-red-dark"><i class="fa fa-info-circle"></i></strong>
                <p style="line-height: 50px">
                    {if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}
                </p>
            </div>

            <div class="log_out_prod_links" >
                <div class="one-half center-text" onclick='window.location.href = "/login.sys"'  ><span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                <div class="one-half last-column center-text" onclick='window.location.href = "/register.sys"'><span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                <div class="clear"></div>
            </div>

        {/if}



        {if $logged_in}
            <div id="price_block_{$product->id}" class="discount_info_family_{$product->get('Product Family Category Key')} store-product-rating half-top">
                {if $product->get('RRP')!=''}<span>{t}RRP{/t}: {$product->get('RRP')}</span><br>{/if}

                <div class="tw-flex tw-justify-between tw-items-center">
                    {if $logged_in and  isset($settings['Display Stock Levels in Product']) and $settings['Display Stock Levels in Product']=='Yes'}
                        <span style="line-height: 20px "> {t}Stock{/t}: <i class="product_stock_dot fa fa-circle stock_level_{$product->id}"></i>
                            <span class="product_stock_label_{$product->id}"></span>
                        </span>
                    {/if}
                    {if $logged_in}
                        {if $store->get('Store Type')!='Dropshipping'}
                            <i style="float: right;font-size: 22px" data-product_code="{$product->get('Code')}" data-product_id="{$product->id}" data-favourite_key="0" class="tw-p-0 sim_button favourite_{$product->id} favourite far fa-heart" aria-hidden="true"></i>
                        {/if}
                    {/if}
                </div>

                <h2 class="tw-text-[1.3rem]">{t}Price{/t}: <span class="original_price">{$product->get('Price')}</span> <span class="original_price" style="font-size:80%">{$product->get('Price Per Unit')}</span></h2>

                <div style="color: rgb(243, 121, 52);" class="gold_reward_product_price tw-flex tw-gap-x-2 tw-items-center">
                    <div data-family_key="{$product->get('Product Family Category Key')}">
                        <div class="hide discount_info_applied">
                            <div class="tw-flex tw-items-center tw-gap-x-1.5">
                                <div class="tw-cursor-pointer tw-rounded tw-text-[1rem] tw-bg-[#4ade8044] tw-text-[#0b7933] tw-px-1.5 tw-py-[1px] tw-w-fit" style="border: 1px solid #16a34a;">
                                    <i class="gold_reward_badge fas fa-star" style="color: green; opacity: 0.6"></i>
                                    <span class="gold_reward_percentage"></span>
                                </div>
                                <i style="color: seagreen;font-size: 0.9rem;" class="hide gold_reward_applied_check fal fa-check"></i>
                            </div>
                        </div>
                        <div class="hide discount_info_unappeased">
                            <div class="tw-cursor-pointer tw-rounded tw-text-[1rem] tw-bg-[#75757545] tw-py-[1px] tw-px-1.5 tw-w-fit tw-text-[#282828]"
                                style="border: 1px solid #8f8f8f;"
                            >
                                <i class="gold_reward_badge fas fa-star-half-alt" style="color: #3f3f3f;"></i>
                                <span class="gold_reward_percentage"></span>
                                <i style="color: #3b3b3b; opacity: 0.8;" class="hide gold_reward_applied fal fa-question-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="gold_reward_price tw-text-[1.3rem]"></div>
                    <div class="gold_reward_unit_price tw-text-right tw-text-[0.95rem]"></div>
                </div>

            </div>
        {/if}

        <div class="store-product-icons">

        </div>
        <div class="decoration half-top"></div>

        <p >
            {$data.text|replace:'<p><br></p>':''}
        </p>










        <div class="clear"></div>

        {assign 'origin' $product->get('Origin')}
        {assign 'weight' $product->get('Unit Weight Formatted')}
        {assign 'weight_gross' $product->get('Package Weight')}
        {assign 'dimensions' $product->get('Unit Dimensions')}
        {assign 'materials' $product->get('Materials')}
        {assign 'barcode' $product->get('Barcode Number')}
        {assign 'cpnp' $product->get('CPNP Number')}
        {assign 'ufi' $product->get('UFI')}

        <table class="tw-border tw-border-solid tw-border-[#e9e9e9]">
            <tr class="{if $origin==''}hide{/if}">
                <td>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</td>
                <td class="origin">{$origin}</td>
            </tr>
            <tr class="{if $weight==''}hide{/if}">
                <td>{if empty($labels._product_weight)}{t}Net weight{/t}{else}{$labels._product_weight}{/if}</td>
                <td class="origin">{$weight}</td>
            </tr>
            <tr class="{if $weight_gross==''}hide{/if}">
                <td>{if empty($labels._product_weight_gross)}{t}Shipping weight{/t}{else}{$labels._product_weight_gross}{/if}</td>
                <td class="origin">{$weight_gross}</td>
            </tr>

            <tr class="{if $dimensions==''}hide{/if}">
                <td>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</td>
                <td class="origin">{$dimensions}</td>
            </tr>

            <tr class="{if $barcode==''}hide{/if}">
                <td>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</td>
                <td class="origin">{$barcode}</td>
            </tr>
            <tr class="{if $cpnp==''}hide{/if}">
                <td>CPNP</td>
                <td class="origin">{$cpnp}</td>
            </tr>
            <tr class="{if $ufi==''}hide{/if}">
                <td>UFI</td>
                <td class="origin">{$ufi}</td>
            </tr>
            <tr class="{if $materials==''}hide{/if}">
                <td>{if empty($labels._product_materials)}{t}Materials{/t}{else}{$labels._product_materials}{/if}</td>
                <td class="origin">{$materials}</td>
            </tr>
            <tr class="{if $weight==''}hide{/if}">


                {foreach from=$product->get_attachments() item=attachment}
            <tr>
                <td>{$attachment.label} <i class="fa fa-paperclip" style="margin-left:5px" aria-hidden="true"></i></td>
                <td><a href="attachment.php?id={$attachment.id}" target="_blank">{$attachment.name}</a></td>
            </tr>
            {/foreach}



        </table>


    </div>
</div>

<script>
    ga('auTracker.ec:addProduct', { 'id': '{$product->get('Code')}',  'category': '{$product->get('Family Code')}','price': '{$product->get('Product Price')}','name': '{$product->get('Name')|escape:'quotes'}', });
    ga('auTracker.ec:setAction', 'detail');
</script>



           



