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



{if $public_product}
    {assign 'rrp' $product->get('RRP')}
    {assign 'variants' $product->get_variants()}

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
                            <img style="height: 200px" src="{if $image.image_website=='' }{$image.src}{else}{$image.image_website}{/if}" itemprop="thumbnail" alt="{$image.caption}"/>
                        </a>
                    </figure>
                {/foreach}
            </div>


            {if $product->get('Video ID')}

                <script>
                  function show_video(){



                  }
                </script>


                <div class="tw-mt-5 tw-relative tw-isolate">
                    <iframe    src="https://player.vimeo.com/video/{$product->get('Video ID')}?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&app_id=58479&background=true"   frameborder="0"  style="aspect-ratio: 1 / 1; height: auto; width:100%;" ></iframe>

                    <div onclick="show_video()" style="cursor:pointer"  class="tw-absolute tw-inset-0 tw-z-10"></div>
                </div>




            {/if}



        </div>
        <div class="information product_information tw-relative" >
            <h1 style="padding-top:5px;margin:2px 0;font-size:150%" itemprop="name" class="Product_Name">
                {if $product->get('number_visible_variants')==0}{$product->get('Name')}{else}{$variants[0]->get('Name')}{/if}
            </h1>
            <div class="highlight_box tw-flex tw-justify-between">
                <div style="float:left;margin-right:4px;min-width:200px">
                    <span class="code Product_Code"> {if $product->get('number_visible_variants')==0}{$product->get('Code')}{else}{$variants[0]->get('Code')}{/if}</span>
                </div>
                
                {if $logged_in  and $product->get('number_visible_variants')>0 }
                    <div>
                        {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                    </div>
                {/if}

                {if $rrp!='' and $product->get('number_visible_variants')==0 }
                    <div>
                        {if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}
                    </div>
                {/if}

            </div>


            <div class="tw-flex tw-justify-between">
                <div style="clear:both ">
                    {if $logged_in and  isset($settings['Display Stock Levels in Product']) and $settings['Display Stock Levels in Product']=='Yes'}
                        {t}Stock{/t}: <i class="product_stock_dot fa fa-circle stock_level_{$product->id}"></i> <span class="product_stock_label_{$product->id}"></span>
                    {/if}
                </div>
                
                {if $logged_in}
                    {if $store->get('Store Type')!='Dropshipping'}
                        <i style="float: right;font-size: 22px" data-product_code="{$product->get('Code')}" data-product_id="{$product->id}" data-favourite_key="0" class="sim_button favourite_{$product->id} favourite  far  fa-heart" aria-hidden="true"></i>
                    {/if}
                {/if}
            </div>

            {if $product->get('number_visible_variants')==0}
            <div class="ordering-container log_in tw-flex tw-flex-col tw-mt-[15px]">
                {if $logged_in}

                    <div id="price_block_{$product->id}" class="price_block discount_info_family_{$product->get('Product Family Category Key')} tw-mb-4" >
                        <div class="original_price_tr tw-flex tw-gap-x-2 tw-items-center" >
                            <div>
                                {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                <i class="original_price_checked  fal fa-check" style="color: #727272;font-size: 0.8rem;"></i>
                            </div>
                            <div class="original_price tw-text-[1.1rem]">{$product->get('Price')}</div>
                            {if isset($product->get('Price Per Unit'))}
                                <div  style="text-align: right; font-size: 0.85rem"  class="original_price">{$product->get('Price Per Unit')}</div>
                            {/if}
                        </div>

                        <div style="color: rgb(243, 121, 52);"  class="hide gold_reward_product_price tw-flex tw-gap-x-2 tw-items-center">
                            <div data-family_key="{$product->get('Product Family Category Key')}"   >
                                <div class="hide discount_info_applied">
                                    <div style="display:flex; align-items: center;column-gap: 3px;">
                                        <div class="tw-cursor-pointer tw-rounded tw-text-[0.9rem] tw-bg-[#4ade8044] tw-text-[#0b7933] tw-px-1.5 tw-py-[1px] tw-w-fit" style="border: 1px solid #16a34a;">
                                            <i class="gold_reward_badge  fas fa-star" style="color: green; opacity: 0.6"></i>
                                            <span class="gold_reward_percentage"></span>
                                        </div>
                                        <i style="color: seagreen;font-size: 0.8rem;" class="hide gold_reward_applied_check fal fa-check"></i>
                                    </div>
                                </div>

                                <div class="hide discount_info_unappeased">
                                    <div class="tw-cursor-pointer tw-rounded tw-text-[0.9rem] tw-bg-[#75757545] tw-py-[1px] tw-px-1.5 tw-w-fit tw-text-[#282828]"
                                        style="border: 1px solid #8f8f8f;"
                                    >
                                        <i class="gold_reward_badge  fas fa-star-half-alt" style="color: #3f3f3f;"></i>
                                        <span class="gold_reward_percentage"></span>
                                        <i style="color: #3b3b3b; opacity: 0.7;" class="hide gold_reward_applied fal fa-question-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="gold_reward_price tw-text-[1.1rem]"></div>
                            <div style="text-align: right; font-size: 0.85rem"  class="gold_reward_unit_price"></div>
                        </div>
                    </div>


                    {if $product->get('Web State')=='Out of Stock'}
                        <div style="height:40px;line-height:40px;padding:0px 20px" class="out_of_stock ">
                            <i data-product_id="{$product->id}"
                                data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                class="far fa-envelope like_button reminder out_of_stock_reminders_{$product->id} tw-mr-2 tw-ml-0 tw-top-0 tw-left-0 tw-text-[1rem] hover:tw-text-gray-100"
                                aria-hidden="true">
                            </i>
                            <span class="product_footer label ">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>
                        </div>

                    {elseif  $product->get('Web State')=='For Sale'}

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
                            <div class="tw-mt-5 tw-w-1/2">
                                <div class="order_row empty order_row_{$product->id} ">
                                    <input maxlength=6 class='order_input ' type="text" size='2' value='' data-ovalue=''>
                                    <span class="order_button label sim_button">
                                        <i class="fa fa-hand-pointer fa-fw" aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}
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
            {else}

                {foreach from=$variants item=$variant name=variant}
                    <div id="ordering_variant_{$variant->id}" class="ddddd ordering_variant {if !$smarty.foreach.variant.first}hide{/if}">
                        <div class="ordering-container log_in tw-flex tw-flex-col tw-mt-[15px]">
                            {if $logged_in}
                                <div class="tw-relative">
                                    <span onclick="open_variant_chooser(this, {$product->id})" class="open_variant_chooser"
                                        style="cursor:pointer;position:relative;padding:3px 0px 3px 10px;border:1px solid #ccc;width: 130px;display: inline-block;">
                                        {$variant->get('Product Variant Short Name')}
                                        <i style="position:absolute;right:12px;top:7px" class="fas fa-angle-down"></i>
                                    </span>
                                    <div style="font-size: x-small;">
                                        <span style="padding-right: 10px">
                                            {if empty($labels._variant_options)}
                                                {t}More buying options{/t}
                                            {else}
                                                {$labels._variant_options}
                                            {/if} ☝
                                        </span>
                                    </div>

                                </div>

                                <div class="product_prices log_in aaaaa" style="margin-left:0px;padding-left:0px;font-size: 120%;width:120px">
                                    <div class="product_price">
                                        {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$variant->get('Price')}
                                    </div>
                        
                                    <div class="product_price" style="margin-top:3px"><small>{$variant->get('Price Per Unit Bis')}</small></div>
                                </div>

                                <!-- <div id="price_block_{$product->id}" class="aaa1 price_block discount_info_family_{$product->get('Product Family Category Key')} tw-mb-4" >
                                    <div class="original_price_tr tw-flex tw-gap-x-2 tw-items-center" >
                                        <div>
                                            {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                            <i class="original_price_checked  fal fa-check" style="color: #727272;font-size: 0.8rem;"></i>
                                        </div>
                                        <div class="original_price tw-text-[1.1rem]">{$product->get('Price')}</div>
                                        {if isset($product->get('Price Per Unit'))}
                                            <div  style="text-align: right; font-size: 0.85rem"  class="original_price">{$product->get('Price Per Unit')}</div>
                                        {/if}
                                    </div>
            
                                    <div style="color: rgb(243, 121, 52);"  class="hide gold_reward_product_price tw-flex tw-gap-x-2 tw-items-center">
                                        <div data-family_key="{$product->get('Product Family Category Key')}"   >
                                            <div class="hide discount_info_applied">
                                                <div style="display:flex; align-items: center;column-gap: 3px;">
                                                    <div class="tw-cursor-pointer tw-rounded tw-text-[0.9rem] tw-bg-[#4ade8044] tw-text-[#0b7933] tw-px-1.5 tw-py-[1px] tw-w-fit" style="border: 1px solid #16a34a;">
                                                        <i class="gold_reward_badge  fas fa-star" style="color: green; opacity: 0.6"></i>
                                                        <span class="gold_reward_percentage"></span>
                                                    </div>
                                                    <i style="color: seagreen;font-size: 0.8rem;" class="hide gold_reward_applied_check fal fa-check"></i>
                                                </div>
                                            </div>
            
                                            <div class="hide discount_info_unappeased">
                                                <div class="tw-cursor-pointer tw-rounded tw-text-[0.9rem] tw-bg-[#75757545] tw-py-[1px] tw-px-1.5 tw-w-fit tw-text-[#282828]"
                                                    style="border: 1px solid #8f8f8f;"
                                                >
                                                    <i class="gold_reward_badge  fas fa-star-half-alt" style="color: #3f3f3f;"></i>
                                                    <span class="gold_reward_percentage"></span>
                                                    <i style="color: #3b3b3b; opacity: 0.7;" class="hide gold_reward_applied fal fa-question-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gold_reward_price tw-text-[1.1rem]"></div>
                                        <div style="text-align: right; font-size: 0.85rem"  class="gold_reward_unit_price"></div>
                                    </div>
                                </div> -->


                                {if $variant->get('Web State')=='Out of Stock'}
                                    <div style="height:40px;line-height:40px;padding:0px 20px" class="   out_of_stock ">
                                        <span class="product_footer label ">
                                            {if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}
                                        </span>
                                        <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i> </span>
                                    </div>
                            
                                    <i data-product_id="{$variant->id}"
                                        data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                        data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                        title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                        class="far fa-envelope like_button reminder out_of_stock_reminders_{$variant->id} margin_left_5"
                                        aria-hidden="true"></i>                    
                                {elseif $variant->get('Web State')=='For Sale'}
                                    {if $store->get('Store Type')=='Dropshipping'}
                                        <div class="portfolio_row  portfolio_row_{$variant->id} "
                                            style="background: none;color:#000;border-left:1px solid #ccc;border-right:1px solid #ccc">
                                
                                            <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button "
                                                style="text-align: center"> <i class="fa fa-plus padding_right_5"></i>
                                                {if empty($labels._add_to_portfolio)}{t}Portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                                            </div>
                                            <div class="edit_portfolio_item remove_from_portfolio hide " style="position:relative;">
                                                <i class="fa fa-store-alt padding_right_5"></i>
                                                {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if}
                                                <i
                                                    style="position: absolute;right:10px;bottom:7.5px"
                                                    class="far edit_portfolio_item_trigger fa-trash-alt  sim_button"
                                                    title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                                            </div>
                                        </div>
                                    {else}
                                        <div class="tw-mt-5 tw-w-1/2">
                                            <div class="order_row empty  order_row_{$variant->id} ">
                                                <input maxlength=6 class='order_input ' type="text" size='2' value='' data-ovalue=''>
                                                <span class="order_button label sim_button">
                                                    <i class="fa fa-hand-pointer  fa-fw   " aria-hidden="true"></i>
                                                    {if empty($labels._ordering_order_now)}
                                                        Order now
                                                    {else}
                                                        {assign _ordering_order_now $labels._ordering_order_now}
                                                        {if ($_ordering_order_now|count_characters:true)>10}
                                                            <span style="font-size: smaller">{$labels._ordering_order_now}</span>
                                                        {else}
                                                            {$labels._ordering_order_now}
                                                        {/if}
                                                    {/if}
                                                </span>
                                            </div>
                                        </div>
                                    {/if}
                                {/if}
                    

                            {else}
                                <div class="ordering log_out ">
                                    <div onclick='window.location.href = "/login.sys"' class="mark_on_hover">
                                        <span class="login_button">
                                            {if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}
                                        </span>
                                    </div>
                                    <div onclick='window.location.href = "/register.sys"' class="mark_on_hover">
                                        <span class="register_button">
                                            {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}
                                        </span>
                                    </div>
                                </div>
                            {/if}
                    
                        </div>
                    </div>
                {/foreach}

                {include file="theme_1/_variants.theme_1.EcomB2B.tpl" variants=$variants master_id={$product->id} }

            {/if}

            {if $logged_in and $product->get('Web State')=='Out of Stock' and  $product->get('Next Supplier Shipment Timestamp')>$smarty.now   }
                <div style="padding-left: 262px">{t}Expected{/t}: {$product->get('Next Supplier Shipment Timestamp')|date_format:"%x"}<br></div>
            {/if}


            <div id="product_description" class="product_description_block _au_vw_ ">
                {$data.text}
            </div>

        </div>
        {assign 'origin' $product->get('Origin')}
        {assign 'weight' $product->get('Unit Weight Formatted')}

        {if $product->get('number_visible_variants')==0}
        {assign 'weight_gross' $product->get('Package Weight')}
        {else}
            {assign 'weight_gross' $variants[0]->get('Package Weight')}
        {/if}

        {assign 'dimensions' $product->get('Unit Dimensions')}
        {assign 'materials' $product->get('Materials')}
        {assign 'barcode' $product->get('Barcode Number')}
        {assign 'cpnp' $product->get('CPNP Number')}
        {assign 'ufi' $product->get('UFI')}

        <div class="product_properties" >

        <table class="properties" >
        <tr class="{if $origin==''}hide{/if}">
            <td class="small">{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</td>
            <td>{$origin}</td>
        </tr>

        <tr class="{if $weight==''}hide{/if}">
            <td class="small">{if empty($labels._product_weight)}{t}Net weight{/t}{else}{$labels._product_weight}{/if}</td>
            <td>{$weight}</td>
        </tr>
            <tr class="Package_Weight_Container {if $weight_gross==''}hide{/if}">
                <td class="small">{if empty($labels._product_weight_gross)}{t}Shipping weight{/t}{else}{$labels._product_weight_gross}{/if}</td>
                <td class="Package_Weight">{$weight_gross}</td>
            </tr>
        <tr class="{if $dimensions==''}hide{/if}">
            <td class="small">{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</td>
            <td>{$dimensions}</td>
        </tr>
        <tr class="{if $materials==''}hide{/if}">
            <td class="small">{if empty($labels._product_materials)}{t}Materials{/t} / {t}Ingredients{/t}{else}{$labels._product_materials}{/if}</td>
            <td>
                {$materials}
            </td>
        </tr>
        <tr class="{if $cpnp==''}hide{/if}">
            <td class="small" title="{if empty($labels._product_cpnp)}{t}Cosmetic Products Notification Portal{/t}{else}{$labels._product_cpnp}{/if} - Europa.eu">CPNP</td>
            <td>{$cpnp}</td>
        </tr>
            <tr class="{if $ufi==''}hide{/if}">
                <td class="small" title="{if empty($labels._product_ufi)}{t}Unique Formula Identifier - Poison Centres{/t}{else}{$labels._product_ufi}{/if}">UFI</td>
                <td>{$ufi}</td>
            </tr>
        <tr class="{if $barcode==''}hide{/if}">
            <td class="small">{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</td>
            <td>{$barcode}</td>

        </tr>

        {foreach from=$product->get_attachments() item=attachment}
            <tr>
                <td>{$attachment.label} <i class="fa fa-paperclip" style="margin-left:5px" aria-hidden="true"></i></td>
                <td><a href="attachment.php?id={$attachment.id}" target="_blank">{$attachment.name}</a></td>
            </tr>
        {/foreach}



        </table>
            {if $webpage->get('Webpage Blog URL')!=''}
            <table   style="margin-top: 20px;min-width: 270px">

                <tr >

                    <td ><a href="{$webpage->get('Webpage Blog URL')}" style="text-decoration: none">
                              <span style="border:1px solid #ccc;padding:5px 15px 5px 10px"> <i style="margin-right: 3px" class="fab fa-blogger-b"></i>    Blog </span>
                        </a></td>
                </tr>
                <tr style="height: 20px"><td></td></tr>


            </table>
            {/if}

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

{if $logged_in  and $product->get('number_visible_variants')>0 }

    {include file="theme_1/_variants.common.theme_1.EcomB2B.tpl"  }

{/if}



<script>
    ga('auTracker.ec:addProduct', { 'id': '{$product->get('Code')}',  'category': '{$product->get('Family Code')}','price': '{$product->get('Product Price')}','name': '{$product->get('Name')|escape:'quotes'}', });
    ga('auTracker.ec:setAction', 'detail');
</script>
{else}
    <div style="padding: 150px 0px;text-align: center;font-weight: 800;font-size: large">{t}Private product{/t}</div>
{/if}


