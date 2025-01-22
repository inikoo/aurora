{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2018 at 22:50:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}



<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class=" _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <h3 class="products_title hello_world  {if !$block.show_title}hide{/if}" style="margin-left:20px;">{$data.title}</h3>

    <div class="products {if !$data.item_headers}no_items_header{/if}"  data-sort="{$data.sort}" >
        {counter start=-1 print=false assign="counter"}
        {foreach from=$data.items item=item}


            <div class="product_wrap {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}stock_info_hint{/if}  wrap type_{$item.type} " data-type="{$item.type}" {if $item.type=='product'} data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} ">


                {if $item.type=='product'}
                    {counter print=false assign="counter"}

                    <div class="product_block item product_container tw-relative" data-product_id="{$item.product_id}">
                        <div class="product_header_text _au_vw_" >
                            {$item.header_text}
                        </div>


                        <div class="wrap_to_center product_image" >
                            <a href="{$item.link}"
                               data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                               data-list="Products"
                               onclick="go_product(this); return !ga.loaded;"

                            ><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i></a>

                            {if $logged_in}
                                <i  data-product_code="{$item.code}"  data-product_id="{$item.product_id}" data-favourite_key="0" class="favourite_{$item.product_id} favourite far  fa-heart" aria-hidden="true"></i>

                                {if isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Dot'}
                                    <i class="stock_dot stock_level_{$item.product_id}  fa fa-circle" ></i>
                                {/if}

                            {/if}
                            <a href="{$item.link}"
                               data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                               data-list="Products"
                               onclick="go_product(this); return !ga.loaded;"

                            ><img src="{$item.image_website}"  /></a>
                        </div>


                        <div class="product_description" style="position: relative; height: fit-content !important; margin-bottom: 5px">
                            {if !isset($item.number_visible_variants)  or   $item.number_visible_variants==0}
                                <h4 style="font-size: 15px;height: 42px; display: flex; text-align: center; background: #ffffff; margin-bottom: 2px; border: 1px solid #d1d5db; border-radius: 3px; padding: 3px 6px;justify-items: center;place-content: center;align-items: center;" class="name item_name {if $item.name|strlen < 40}smallish{elseif $item.name|strlen < 60} small{else}very_small{/if}  ">{$item.name}</h4>
                                
                                <div style="display:flex;clear: both;font-size: smaller">
                                    <div style="font-size: smaller;flex-grow: 1;" class="code">
                                        <small class="Product_Code">{$item.code}</small>
                                    </div>
                                    {if !empty($item.rrp)}
                                        <div style="font-size: smaller;flex-grow: 1;text-align: right" class="code">
                                            <span style="color: rgb(243, 121, 52);">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}
                                                : {$item.rrp}</span>
                                        </div>
                                    {/if}
                                </div>
                            {else}
                                <!-- <div class="name item_name Product_Name ">{$item.variants[0].name}</div> -->
                                <h4
                                    class="name item_name Product_Name"
                                    style="font-size: 15px;height: 42px; display: flex; text-align: center; background: #ffffff; margin-bottom: 2px; border: 1px solid #d1d5db; border-radius: 3px; padding: 3px 6px;justify-items: center;place-content: center;align-items: center;"
                                    class="name item_name {if $item.name|strlen < 40}smallish{elseif $item.name|strlen < 60} small{else}very_small{/if}"
                                >
                                    {$item.variants[0].name}
                                </h4>

                                <div style="display:flex;clear: both">
                                    <div style="flex-grow: 1;font-size: smaller" class="code">
                                        <small class="Product_Code">{$item.variants[0].code}</small>
                                    </div>
                                    {if !empty($item.rrp)}
                                        <div style="flex-grow: 1;font-size: smaller;text-align: right" class="code">
                                            <small>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}
                                                : {$item.rrp}</small>

                                        </div>
                                    {/if}
                                </div>
                            {/if}
                        </div>



                        {if $logged_in}

                            {if !isset($item.number_visible_variants)  or $item.number_visible_variants==0}
                                <div class="product_prices tw-relative">
                                    <div style="display:none" class="product_price">
                                        {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                        : {$item.price} {if isset($item.price_unit)}
                                            <small>{$item.price_unit}</small>{/if}
                                    </div>

                                    <table id="price_block_{$item.product_id}" class="price_block" >
                                        <tr class="product_price original_price_tr" >
                                            <td style="width:75px">
                                                {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                            </td>
                                            <td class="original_price ">{$item.price}</td>
                                            {if isset($item.price_unit)}
                                                <td  style="text-align: right"  class="original_price">{$item.price_unit}</td>
                                            {/if}
                                        </tr>

                                        <tr class="product_discounted_price hide product_price"   >
                                            <td style="font-size: smaller"><i class="far fa-arrow-down"></i> <span class="_percentage"></span></td>
                                            <td class="_price"></td>
                                            <td ><span class="_unit_price"></span></td>
                                        </tr>

                                    </table>

                                {if isset($item.category)}
                                    <div id="visit_family_page_information_{$item.product_id}" class="tw-absolute tw-bottom-1 tw-left-0 tw-w-full tw-text-center tw-text-gray-400 tw-text-[0.7rem]">
                                        <i class="fal fa-badge-percent tw-text-xs"></i> {t}More discounts in{/t} <a href="/{$item.category}" class="tw-underline tw-inline tw-text-gray-600">{t}family page{/t}</a>
                                    </div>
                                {/if}


                                    {if !empty($item.rrp) and false }
                                        <div>
                                        <small>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$item.rrp}</small></div>{/if}
                                </div>


                                {if $store->get('Store Type')=='Dropshipping'}
                                    <div class="portfolio_row  portfolio_row_{$item.product_id} "
                                         style="background: none;color:#000">

                                        <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button "
                                             style="text-align: center"><i class="fa fa-plus padding_right_5"></i>
                                            {if empty($labels._add_to_portfolio)}{t}Portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                                        </div>
                                        <div class="edit_portfolio_item remove_from_portfolio hide "><i
                                                    class="fa fa-store-alt padding_right_5"></i>
                                            {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if}
                                            <i style="position: absolute;right:10px;bottom:-1px"
                                               class="far edit_portfolio_item_trigger fa-trash-alt  sim_button"
                                               title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                                        </div>

                                    </div>
                                {else}
                                    {if $item.web_state=='Out of Stock'}


                                        {if !empty($item.next_shipment_timestamp)  }
                                            <div product_theme="" class="  out_of_stock_row  out_of_stock {if  $item.next_shipment_timestamp<$smarty.now}hide{/if} "
                                                 style="opacity:1;font-style: italic;;position:absolute;bottom:30px;height: 6px;line-height: 16px;padding:0px;padding-top:0px;font-size: 8px;width: 226px">
                                                <span style="padding-left: 10px">{t}Expected{/t}: {$item.next_shipment_timestamp|date_format:"%x"}</span>
                                            </div>
                                        {/if}
                                        <div class="ordering log_in can_not_order  out_of_stock_row  out_of_stock ">
                                            <span class="product_footer label ">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span>
                                            <i data-product_id="{$item.product_id}"
                                               data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                               data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                               title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                               class="far fa-envelope like_button reminder out_of_stock_reminders_{$item.product_id} margin_left_5"
                                               aria-hidden="true"></i>


                                        </div>
                                    {elseif  $item.web_state=='For Sale'}
                                        <div class="order_row empty  order_row_{$item.product_id} ">
                                            <input maxlength=6 class='order_input  ' type="text"' size='2' value=''
                                            data-ovalue=''>

                                            <div class="label sim_button" style="margin-left:57px">
                                                <i class="hide fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span
                                                        class="hide">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                                            </div>


                                        </div>
                                    {/if}

                                {/if}


                            {else}
                                {foreach from=$item.variants item=variant name=variant}
                                    <div id="ordering_variant_{$variant.id}"
                                         class="fffff ordering_variant {if !$smarty.foreach.variant.first}hide{/if}">
                                        <div style="display: flex">
                                            <div class="product_prices  ">
                                                <div class="product_price" style="font-size: small">
                                                    {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                                    : {$variant.price} {if isset($variant.price_unit_bis)}
                                                        <div><small>{$variant.price_unit_bis}</small></div>{/if}
                                                </div>

                                            </div>

                                            <div style="flex-grow:1">
                                            <span onclick="open_variant_chooser(this,{$item.product_id})"
                                                  class="open_variant_chooser"
                                                  style="cursor:pointer;position:relative;padding:3px 0px 3px 10px;border:1px solid #ccc;width: 105px;display: inline-block;">
                                {$variant.label}
                                <div style="display:none;font-size: xx-small;position: absolute;bottom: -14px;text-align: right;width: 100px;"><span >{if empty($labels._variant_options)}{t}More buying options{/t}{else}{$labels._variant_options}{/if} ☝</span></div><i style="position:absolute;right:12px;top:3px" class="fas fa-angle-up"></i></span></div>


                                        </div>


                                        {if $store->get('Store Type')=='Dropshipping'}
                                            <div class="portfolio_row  portfolio_row_{$variant.product_id} "
                                                 style="background: none;color:#000">

                                                <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button "
                                                     style="text-align: center"><i class="fa fa-plus padding_right_5"></i>
                                                    {if empty($labels._add_to_portfolio)}{t}Portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
                                                </div>
                                                <div class="edit_portfolio_item remove_from_portfolio hide "><i
                                                            class="fa fa-store-alt padding_right_5"></i>
                                                    {if empty($labels._in_portfolio)}{t}In portfolio{/t}{else}{$labels._in_portfolio}{/if}
                                                    <i style="position: absolute;right:10px;bottom:-1px"
                                                       class="far edit_portfolio_item_trigger fa-trash-alt  sim_button"
                                                       title="{if empty($labels._remove_from_portfolio)}{t}Remove from portfolio{/t}{else}{$labels._remove_from_portfolio}{/if}"></i>
                                                </div>

                                            </div>
                                        {else}

                                            {if $variant.web_state=='Out of Stock'}


                                                {if !empty($variant.next_shipment_timestamp)  }
                                                    <div class="  out_of_stock_row  out_of_stock {if  $variant.next_shipment_timestamp<$smarty.now}hide{/if} "
                                                         style="opacity:1;font-style: italic;;position:absolute;bottom:30px;height: 6px;line-height: 16px;padding:0px;padding-top:0px;font-size: 8px;width: 226px">
                                                        <span style="padding-left: 10px">{t}Expected{/t}: {$variant.next_shipment_timestamp|date_format:"%x"}</span>
                                                    </div>
                                                {/if}
                                                <div class="ordering log_in can_not_order  out_of_stock_row  out_of_stock ">
                                                    <span class="product_footer label ">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span>
                                                    <i data-product_id="{$variant.product_id}"
                                                       data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                                       data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                                       title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"
                                                       class="far fa-envelope like_button reminder out_of_stock_reminders_{$variant.product_id} margin_left_5"
                                                       aria-hidden="true"></i>


                                                </div>
                                            {elseif  $variant.web_state=='For Sale'}
                                                <div class="order_row empty  order_row_{$variant.id} ">
                                                    <input maxlength=6 class='order_input  ' type="text"' size='2' value=''
                                                    data-ovalue=''>

                                                    <div class="label sim_button" style="margin-left:57px">
                                                        <i class="hide fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span
                                                                class="hide">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                                                    </div>


                                                </div>
                                            {/if}

                                        {/if}


                                    </div>









                                {/foreach}
                                {include file="theme_1/_variants.category_products.theme_1.EcomB2B.tpl" variants=$item.variants master_id={$item.product_id} }



                            {/if}

                        {else}
                            <div style="display:flex" class="product_prices  ">
                                <div class="product_price">
                                    <small>{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</small>
                                </div>
                            </div>

                            <div class="ordering log_out ">

                                <div onclick='window.location.href = "/login.sys"' class="mark_on_hover"><span
                                            class="login_button">{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span>
                                </div>
                                <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span
                                            class="register_button"> {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span>
                                </div>
                            </div>
                        {/if}


                        {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}
                            <div style="width: 100%;height: 5px;" class=" stock_hint stock_level_{$item.product_id}">
                            </div>
                        {/if}



                    </div>

                {elseif $item.type=='text'}
                    <div  class="panel_txt_control hide" >
                        <span class="hide"><i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20"></span>
                        <i class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>
                        <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

                    </div>
                    <div style="padding:{$item.padding}px" size_class="{$item.size_class}" data-padding="{$item.padding}" class="_au_vw_ txt {$item.size_class}">{$item.text}</div>


                {elseif $item.type=='image'}


                    <img class="panel edit {$item.size_class}" size_class="{$item.size_class}" src="{if !preg_match('/^http/',$item.image_website)}EcomB2B/{/if}{$item.image_website}"  data-image_website="{$item.image_website}"  data-src="{$item.image_src}"    link="{$item.link}"  alt="{$item.title}" />


                {elseif $item.type=='video'}

                    <div class="panel  {$item.type} {$item.size_class}" size_class="{$item.size_class}" video_id="{$item.video_id}">
                        <iframe width="470" height="{if $data.item_headers}330{else}290{/if}" frameallowfullscreen="" src="https://www.youtube.com/embed/{$item.video_id}?rel=0&amp;controls=0&amp;showinfo=0"></iframe>
                        <div class="block_video" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
                    </div>




                {/if}

            </div>


        {/foreach}
    </div>



    <div style="clear: both"></div>
</div>


<script>
    {foreach from=$data.items item=item  name=analytics_data}
    {if $item.type=='product'}ga('auTracker.ec:addImpression', { 'id': '{$item.code}', 'name': '{$item.name|escape:'quotes'}',{if isset($item.category)} 'category': '{$item.category}',{/if}{if isset($item.raw_price)} 'price': '{$item.raw_price}',{/if}'list': 'Products', 'position':{$smarty.foreach.analytics_data.index}});
    {/if}
    {/foreach}
</script>

