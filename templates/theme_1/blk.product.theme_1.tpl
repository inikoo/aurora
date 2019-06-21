{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:11 April 2018 at 01:42:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}
{assign 'origin' $product->get('Origin')}
{assign 'weight' $product->get('Unit Weight')}
{assign 'materials' $product->get('Materials')}
{assign 'cpnp' $product->get('CPNP Number')}
{assign 'dimensions' $product->get('Unit Dimensions')}
{assign 'barcode' $product->get('Barcode Number')}




<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="    _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div class="product" style="display: flex; justify-content: space-evenly" itemscope itemtype="http://schema.org/Product">
        <div class="images" style="flex-grow:1;padding-left: 20px;min-width: 350px;flex-basis: auto;flex-grow: 1;">


            <figure class="main_image" style="margin: 0px;padding:0px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"
                    data-key="{$data.image.key}"
                    data-src="{$data.image.src}"
                    data-caption="{$data.image.caption}"
                    data-width="{$data.image.width}"
                    data-height="{$data.image.height}"
                    data-image_website="{$data.image.image_website}"


            >

                <a href="{$data.image.src}" itemprop="contentUrl" data-w="{$data.image.width}" data-h="{$data.image.height}">
                    <img style="max-width: 330px;max-height: 330px" src="{$data.image.image_website}" itemprop="image" alt="{$data.image.caption}">
                </a>
            </figure>

            <div class="gallery" style="display: flex;;max-width: 330px;flex-wrap: wrap " itemscope itemtype="http://schema.org/ImageGallery">

                {foreach from=$data.other_images item=image name=foo}
                    <figure style="margin: 0px 5px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"
                            data-src="{$image.src}"
                            data-caption="{$image.caption}"
                            data-key="{$image.key}"
                            data-width="{$image.width}"
                            data-height="{$image.height}"
                            data-image_website="{$image.image_website}"
                    >
                        <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                            <img style="height: 50px" src="{$image.image_website}" itemprop="thumbnail" alt="{$image.caption}"/>
                        </a>
                    </figure>
                {/foreach}


            </div>


        </div>
        <div class="information" style="padding: 0px 30px;min-width: 500px;flex-basis: auto;flex-grow: 1;">
            <h1 style="padding-top:5px;margin:2px 0;font-size:150%" itemprop="name">
                {$product->get('Name')} <i style="float: right;padding-right: 50px" class="far fa-heart"></i>
            </h1>
            <div class="highlight_box">
                <div style="float:left;margin-right:4px;min-width:200px">
                    {t}Product code{/t}: <span class="code">{$product->get('Code')} </span>
                </div>

            </div>
            <div class="ordering-container  log_in" style="display: flex;margin-top:40px;">

                <div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 120%;width:250px">
                    <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')} <small>{$product->get('Price Per Unit')}</small>   </div>






                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}
                        <div style="margin-top:4px">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                </div>

                <div style="margin-left:10px;">
                    <div class="order_row empty   ">
                        <input maxlength=6 class='order_input ' type="text" size='2' value='' ovalue=''>
                        <span class="order_button">
                            <i class="fa fa-hand-pointer" aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}
                        </span>
                    </div>
                </div>

            </div>
            <div id="_product_description" class="product_description_block fr-view " style="border:1px dashed #ccc">
                {$data.text}
            </div>

        </div>
        <table class="properties" style=flex-grow:1;padding-right: 20px">
        <tr class="{if $origin==''}hide{/if}">
            <td>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</td>
            <td>{$origin}</td>
        </tr>

        <tr class="{if $weight==''}hide{/if}">
            <td>{if empty($labels._product_weight)}{t}Weight{/t}{else}{$labels._product_weight}{/if}</td>
            <td>{$weight}</td>
        </tr>
        <tr class="{if $dimensions==''}hide{/if}">
            <td>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</td>
            <td>{$dimensions}</td>
        </tr>
        <tr class="{if $materials==''}hide{/if}">
            <td>{if empty($labels._product_materials)}{t}Materials{/t}/{t}Ingredients{/t}{else}{$labels._product_materials}{/if}</td>
            <td>
                {$materials}
            </td>
        </tr>
        <tr class="{if $cpnp==''}hide{/if}">
            <td title="{if empty($labels._product_cpnp)}{t}Cosmetic Products Notification Portal{/t}{else}{$labels._product_cpnp}{/if} - Europa.eu">CPNP</td>
            <td>{$cpnp}</td>
        </tr>
        <tr class="{if $barcode==''}hide{/if}">
            <td>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</td>
            <td>{$barcode}</td>

        </tr>

        {foreach from=$product->get_attachments() item=attachment}
            <tr>
                <td>{$attachment.label} <i class="fa fa-paperclip" style="margin-left:5px" aria-hidden="true"></i></td>
                <td><a href="attachment.php?id={$attachment.id}" target="_blank">{$attachment.name}</a></td>
            </tr>
        {/foreach}

        </table>
    </div>


    <div style="clear:both"></div>



</div>


<script>


    $(document).on( "dblclick", ".product_description_block", function() {

        if($(this).hasClass('fr-box')){
            return;
        }





        set_up_froala_editor($(this).attr('id'))

    })


</script>



