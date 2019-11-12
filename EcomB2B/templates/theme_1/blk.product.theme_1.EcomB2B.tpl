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
                    <img style="max-width: 330px;max-height: 330px" src="{if $data.image.image_website=='' }{$data.image.src}{else}{$data.image.image_website}{/if}" itemprop="image" title="{$data.image.caption}" alt="{$data.image.caption}">
                </a>
            </figure>

            <div class="gallery" style="display: flex;max-width: 330px;flex-wrap: wrap " itemscope itemtype="http://schema.org/ImageGallery">

                {foreach from=$data.other_images item=image name=foo}
                    <figure style="margin: 0px 5px" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                        <a href="{$image.src}" itemprop="contentUrl" data-w="{$image.width}" data-h="{$image.height}">
                            <img style="height: 50px" src="{if $image.image_website=='' }{$image.src}{else}{$image.image_website}{/if}" itemprop="thumbnail" alt="{$image.caption}"/>
                        </a>
                    </figure>
                {/foreach}


            </div>


        </div>
        <div class="information product_information" style="">
            <h1 style="padding-top:5px;margin:2px 0;font-size:150%" itemprop="name">
                {$product->get('Name')}

            </h1>
            <div class="highlight_box" >
                <div style="float:left;margin-right:4px;min-width:200px">
                    {t}Product code{/t}: <span class="code">{$product->get('Code')} </span>
                </div>
                {if $logged_in}
                    {if $store->get('Store Type')!='Dropshipping'}
                    <i style="float: right;font-size: 22px" data-product_code="{$product->get('Code')}" data-product_id="{$product->id}" data-favourite_key="0" class="sim_button favourite_{$product->id} favourite  far  fa-heart" aria-hidden="true"></i>
                    {/if}
                {/if}
            </div>


            <div style="clear:both ">
                {if $logged_in and  isset($settings['Display Stock Levels in Product']) and $settings['Display Stock Levels in Product']=='Yes'}
                    {t}Stock{/t}: <i class="product_stock_dot fa fa-circle stock_level_{$product->id}"></i> <span class="product_stock_label_{$product->id}"></span>
                {/if}
            </div>


            <div class="ordering-container  log_in" style="display: flex;margin-top:15px;">

                {if $logged_in}


                <div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 120%;width:250px">
                    <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')} <small>{$product->get('Price Per Unit')}</small></div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}
                        <div style="margin-top:4px">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                </div>
                    {if $product->get('Web State')=='Out of Stock'}
                        <div style="height:40px;line-height:40px;padding:0px 20px"   class="   out_of_stock ">
                            <span class="product_footer label ">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>
                        </div>



                        <i data-product_id="{$product->id}"
                           data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                           data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$product->id} margin_left_5" aria-hidden="true"></i>


                    {elseif  $product->get('Web State')=='For Sale'}

                        {if $store->get('Store Type')=='Dropshipping'}
                            <div class="portfolio_row  portfolio_row_{$product->id} "  style="background: none;color:#000;border-left:1px solid #ccc;border-right:1px solid #ccc" >

                                <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button " style="text-align: center"> <i class="fa fa-plus padding_right_5"></i>
                                    {if empty($labels._add_to_portfolio)}{t}Add to portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                                </div>
                                <div class="edit_portfolio_item remove_from_portfolio hide " style="position:relative;"> <i class="fa fa-store-alt padding_right_5"></i>
                                    {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if} <i style="position: absolute;right:10px;bottom:7.5px" class="far edit_portfolio_item_trigger fa-trash-alt  sim_button" title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                                </div>

                            </div>
                        {else}
                        <div style="margin-left:10px;">
                            <div class="order_row empty  order_row_{$product->id} ">
                                <input maxlength=6 class='order_input ' type="text" size='2' value='' data-ovalue=''>
                                <span class="order_button label sim_button">
                                    <i class="fa fa-hand-pointer  fa-fw" aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}
                                </span>
                            </div>
                        </div>
                        {/if}
                    {/if}
                {else}
                    <div class="ordering log_out " >
                        <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                        <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                    </div>

                {/if}

            </div>
            {if $logged_in and $product->get('Web State')=='Out of Stock' and  $product->get('Next Supplier Shipment Timestamp')>$smarty.now   }
            <div style="padding-left: 262px">{t}Expected{/t}: {$product->get('Next Supplier Shipment Timestamp')|date_format:"%x"}<br></div>
            {/if}


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

        <div class="product_properties" >

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
            <td>{if empty($labels._product_materials)}{t}Materials{/t} / {t}Ingredients{/t}{else}{$labels._product_materials}{/if}</td>
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

         <table class="{if $materials==''}hide{/if}"   style="margin-top: 20px;min-width: 270px">
             <tr class="{if $materials==''}hide{/if}">

                 <td ><a href="asset_label.php?object=product&key={$product->id}&type=unit_ingredients" target="_blank" style="text-decoration: none">
                         <span style="border:1px solid #ccc;padding:5px 15px 5px 10px"><img  style="width: 50px;height:16px;position: relative;top:3px;margin-right: 5px" src="/art/pdf.gif"> {if empty($labels._product_materials)}{t}Materials{/t} / {t}Ingredients{/t}{else}{$labels._product_materials}</span>{/if}
                     </a></td>
             </tr>
             <tr style="height: 20px"><td></td></tr>
             <tr class="{if $barcode==''}hide{/if}">

                 <td ><a href="asset_label.php?object=product&key={$product->id}&type=unit_barcode" target="_blank" style="text-decoration: none">
                         <span style="border:1px solid #ccc;padding:5px 15px 5px 10px"><img  style="width: 50px;height:16px;position: relative;top:3px;margin-right: 5px" src="/art/pdf.gif"> {if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}</span>{/if}
                     </a></td>
             </tr>

         </table>



        </div>
    </div>
    <div style="clear:both"></div>
</div>


<script>
    ga('auTracker.ec:addProduct', { 'id': '{$product->get('Code')}',  'category': '{$product->get('Family Code')}','price': '{$product->get('Product Price')}','name': '{$product->get('Name')|escape:'quotes'}', });
    ga('auTracker.ec:setAction', 'detail');
</script>



