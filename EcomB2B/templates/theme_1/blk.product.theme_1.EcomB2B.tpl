{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 14:15:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" class="{if !$data.show}hide{/if}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">




    <div class="product product_container" data-product_id="{$product->id}" style="display: flex; justify-content: space-evenly" itemscope itemtype="http://schema.org/Product">
        <div class="images" style="flex-grow:1;padding-left: 20px;min-width: 350px;flex-basis: auto;flex-grow: 1;">


            <figure class="main_image" style="margin: 0px;padding:0px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">

                <a href="{$data.image.src}" itemprop="contentUrl" data-w="{$data.image.width}" data-h="{$data.image.height}">
                    <img style="max-width: 330px;max-height: 330px" src="{$data.image.image_website}" itemprop="image" alt="{$data.image.caption}">
                </a>
            </figure>

            <div class="gallery" style="display: flex;;max-width: 330px;flex-wrap: wrap " itemscope itemtype="http://schema.org/ImageGallery">

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
        <div class="information" style="padding: 0px 30px;min-width: 500px;flex-basis: auto;flex-grow: 1"

        >
            <h1 style="padding-top:5px;margin:2px 0;font-size:150%" itemprop="name">
                {$product->get('Name')}

            </h1>
            <div class="highlight_box" >
                <div style="float:left;margin-right:4px;min-width:200px">
                    {t}Product code{/t}: <span class="code">{$product->get('Code')} </span>
                </div>

                {if $logged_in}
                    <i  style="float: right;font-size: 22px"  data-product_id="{$product->id}" data-favourite_key="0" class="sim_button favourite_{$product->id} favourite  far  fa-heart" aria-hidden="true"></i>
                {/if}

            </div>
            <div class="ordering-container  log_in" style="display: flex;margin-top:40px;clear:both">

                {if $logged_in}
                <div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 120%;width:250px">
                    <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')}</div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}
                        <div style="margin-top:4px">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                </div>

                <div style="margin-left:10px;">
                    <div class="order_row empty  order_row_{$product->id} ">
                        <input maxlength=6 class='order_input ' type="text" size='2' value='' data-ovalue=''>
                        <span class="order_button label sim_button">
                            <i class="fa fa-hand-pointer  fa-fw" aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}
                        </span>
                    </div>
                </div>
                {else}
                    <div class="ordering log_out " >

                        <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                        <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                    </div>

                {/if}

            </div>
            <div id="product_description" class="product_description_block fr-view ">
                {$data.text}
            </div>

        </div>


        {assign 'origin' $product->get('Origin')}
        {assign 'weight' $product->get('Unit Weight')}
        {assign 'dimensions' $product->get('Unit Dimensions')}
        {assign 'materials' $product->get('Materials')}
        {assign 'barcode' $product->get('Barcode Number')}
        {assign 'cpnp' $product->get('CPNP Number')}

        <div style="flex-grow:1;padding-left: 0px;;flex-basis: auto;flex-grow: 1">

        <table class="properties" >
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
    </div>



    <div style="clear:both"></div>



</div>


<script>





</script>



