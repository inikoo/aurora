{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:07:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="images">
<figure class="main_image" style="margin: 0px;padding:0px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">

    <a href="{$data.image.src}" itemprop="contentUrl" data-w="{$data.image.width}" data-h="{$data.image.height}">
        <img style="max-height: 450px;margin:0px auto" src="{if $data.image.image_website=='' }{$data.image.src}{else}{$data.image.image_website}{/if}" itemprop="image" alt="{$data.image.caption}">
    </a>
</figure>

<div class="gallery " style="display: none" itemscope itemtype="http://schema.org/ImageGallery">

    {foreach from=$data.other_images item=image name=foo}
        <figure style="margin: 0px 5px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"

        >
            <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                <img style="height: 50px" src="{if $image.image_website=='' }{$image.src}{else}{$image.image_website}{/if}" itemprop="thumbnail" alt="{$image.caption}"/>
            </a>
        </figure>
    {/foreach}


</div>
</div>

<div class="content single_line_height">
    <div class="store-product-header">
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



                <div class="log_out_prod_links" >
                    <div style="width: 100%;" class=" center-text"><span style="padding: 10px 20px;" class="empty"> <i class="fa fa-store-alt  fa-fw" style="padding-right: 5px"></i> {if empty($labels._add_to_portfolio)}{t}Add to portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span></div>
                    <div class="clear"></div>
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
            <div class="store-product-rating half-top">
                <h2>{t}Price{/t}: {$product->get('Price')} <span style="font-size:80%">{$product->get('Price Per Unit')}</span></h2>
                {if $product->get('RRP')!=''}<span>{t}RRP{/t}: {$product->get('RRP')}</span>{/if}

                {if $logged_in and  isset($settings['Display Stock Levels in Product']) and $settings['Display Stock Levels in Product']=='Yes'}
                    <br><span style="line-height: 20px "> {t}Stock{/t}: <i class="product_stock_dot fa fa-circle stock_level_{$product->id}"></i> <span class="product_stock_label_{$product->id}"></span></span>
                {/if}


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
        {assign 'weight' $product->get('Unit Weight')}
        {assign 'dimensions' $product->get('Unit Dimensions')}
        {assign 'materials' $product->get('Materials')}
        {assign 'barcode' $product->get('Barcode Number')}
        {assign 'cpnp' $product->get('CPNP Number')}


        <table>
            <tr class="{if $origin==''}hide{/if}">
                <td>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</td>
                <td class="origin">{$origin}</td>
            </tr>
            <tr class="{if $weight==''}hide{/if}">
                <td>{if empty($labels._product_weight)}{t}Weight{/t}{else}{$labels._product_weight}{/if}</td>
                <td class="origin">{$weight}</td>
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
            <tr class="{if $materials==''}hide{/if}">
                <td>{if empty($labels._product_materials)}{t}Materials{/t}{else}{$labels._product_materials}{/if}</td>
                <td class="origin">{$materials}</td>
            </tr>
            <tr class="{if $weight==''}hide{/if}">


        </table>


    </div>
</div>

<script>
    ga('auTracker.ec:addProduct', { 'id': '{$product->get('Code')}',  'category': '{$product->get('Family Code')}','price': '{$product->get('Product Price')}','name': '{$product->get('Name')|escape:'quotes'}', });
    ga('auTracker.ec:setAction', 'detail');
</script>



           



