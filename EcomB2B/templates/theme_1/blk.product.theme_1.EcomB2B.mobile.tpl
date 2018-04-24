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
        <img style="max-height: 300px;margin:0 auto" src="{$data.image.image_website}" itemprop="image" alt="{$data.image.caption}">
    </a>
</figure>

<div class="gallery " style="display: none" itemscope itemtype="http://schema.org/ImageGallery">

    {foreach from=$data.other_images item=image name=foo}
        <figure style="margin: 0px 5px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"

        >
            <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                <img style="height: 50px" src="{$image.image_website}" itemprop="thumbnail" alt="{$image.caption}"/>
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
                <div style="margin-top: 10px" class="notification-small  {if $product->get('Out of Stock Class')=='launching_soon'}bg-green-light{else}bg-red-light{/if} ">
                    <strong class="{if $product->get('Out of Stock Class')=='launching_soon'}bg-green-dark{else}bg-red-dark{/if} "><i class="ion-information-circled"></i></strong>
                    <p style="line-height: 50px;">
                        {$product->get('Out of Stock Label')}
                    </p>
                </div>
            {elseif $product->get('Web State')=='For Sale'}
                <div class="store-product-socials full-bottom " style="text-align: center">
                    <div class="mobile_ordering" data-settings='{ "pid":{$product->id} }'>
                        <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                        <input type="number" min="0" value="" class="needsclick order_qty">
                        <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save far fa-fw fa-save color-blue-dark"></i>
                        <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                    </div>

                </div>
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
                <h2>{t}Price{/t}: {$product->get('Price')}</h2>
                {if $product->get('RRP')!=''}<span>{t}RRP{/t}: {$product->get('RRP')}</span>{/if}
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



           



