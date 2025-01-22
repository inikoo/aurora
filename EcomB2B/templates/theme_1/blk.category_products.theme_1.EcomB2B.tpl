{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 April 2018 at 11:35:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}"
     class="{$data.type} _block   {if $store->get('Store Type')=='Dropshipping'}dropshipping{/if}   {if !$data.show}hide{/if}"
     top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div class="products {if !$data.item_headers}no_items_header{/if}" data-sort="{$data.sort}">


        {counter start=-1 print=false assign="counter"}
        {foreach from=$data.items item=item  name=items}


            {if isset($item.family_key)}
            {assign "item_family_key" $item.family_key}
            {else}
            {assign "item_family_key" "xxx"}
            {/if}

            <div class="product_wrap
                {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}stock_info_hint{/if}
                wrap type_{$item.type} " data-type="{$item.type}" {if $item.type=='product'}
                 data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} "
                style="height: {if !$data.item_headers}auto !important{else}400px{/if}"
            >


                {if $item.type=='product'}
                    {counter print=false assign="counter"}
                    <div class="product_block item product_container tw-relative" data-product_id="{$item.product_id}">
                        <div class="product_header_text _au_vw_">
                            <p style="margin-bottom: 0px!important;"> {$item.header_text}</p>
                        </div>


                        <div class="wrap_to_center product_image">
                            <a href="{$item.link}"
                               data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                               data-list="Family"
                               onclick="go_product(this); return !ga.loaded;">
                                <i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"
                                   title="{t}More info{/t}"></i>
                            </a>

                            {if $logged_in}
                                {if $store->get('Store Type')!='Dropshipping'}
                                    <i data-product_id="{$item.product_id}" data-product_code="{$item.code}"
                                       data-favourite_key="0"
                                       class="favourite_{$item.product_id} favourite far fa-heart"></i>
                                {/if}
                                {if isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Dot'}
                                    <i class="stock_dot stock_level_{$item.product_id}  fa fa-fw fa-circle"></i>
                                {/if}
                            {/if}

                            <a href="{$item.link}"
                               title="{$item.name|escape:'quotes'}"

                               data-version="2"
                               data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                               data-list="Family"
                               onclick="go_product(this); return !ga.loaded;"

                            ><img alt="{$item.name|escape:'quotes'}" src="{$item.image_website}" style="margin: auto" /></a>
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
                                <h4 class="name item_name Product_Name" style="font-size: 15px;height: 42px; display: flex; text-align: center; background: #ffffff; margin-bottom: 2px; border: 1px solid #d1d5db; border-radius: 3px; padding: 3px 6px;justify-items: center;place-content: center;align-items: center;" class="name item_name {if $item.name|strlen < 40}smallish{elseif $item.name|strlen < 60} small{else}very_small{/if}  ">{$item.variants[0].name}</h4>

                                <div style="display:flex;clear: both;font-size: smaller">
                                    <div style="font-size: smaller;flex-grow: 1;" class="code">
                                        <small class="Product_Code">{$item.variants[0].code}</small>
                                    </div>
                                    {if !empty($item.rrp)}
                                        <div style="font-size: smaller;flex-grow: 1;text-align: right; color: rgb(243, 121, 52);" class="code">
                                            {if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}
                                                : {$item.rrp}
                                        </div>
                                    {/if}
                                </div>
                            {/if}
                        </div>

                        {if $logged_in}

                            {if !isset($item.number_visible_variants)  or $item.number_visible_variants==0}
                                <div class="product_prices x1">
                                    <div style="display:none" class="product_price">
                                        {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                        : {$item.price}
                                        {if isset($item.price_unit)}
                                            <small>{$item.price_unit}</small>{/if}
                                    </div>

                                    <table id="price_block_{$item.product_id}" class="price_block discount_info_family_{$item_family_key}  " >
                                        <tr class="original_price_tr" >
                                            <td style="width:75px">
                                                {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                                <i class="original_price_checked  fal fa-check" style="color: #727272;font-size: 0.6rem;"></i>
                                            </td>
                                            <td class="original_price ">{$item.price}</td>
                                            {if isset($item.price_unit)}
                                                <td  style="text-align: right; font-size: 0.7rem"  class="original_price">{$item.price_unit}</td>
                                            {/if}
                                        </tr>


                                        <tr style="color: rgb(243, 121, 52);"  class="gold_reward_product_price hide">
                                            <td style="width:75px"  data-family_key="{$item_family_key}"   >
                                                <div class="hide discount_info_applied">
                                                    <div style="display:flex; align-items: center;column-gap: 3px;">
                                                        <div style="cursor: pointer; border-radius: 4px; font-size: 0.7rem;background-color: #4ade8044;padding: 1px 6px;width: fit-content;border: 1px solid #16a34a;color: #0b7933;">
                                                            <i class="gold_reward_badge  fas fa-star" style="color: green; opacity: 0.6"></i>
                                                            <span class="gold_reward_percentage"></span>
                                                        </div>
                                                        <i style="color: seagreen;font-size: 0.5rem;" class="hide gold_reward_applied_check fal fa-check"></i>
                                                    </div>
                                                </div>

                                                <div
                                                    style="cursor: pointer; border-radius: 4px; font-size: 0.7rem;background-color: #75757545;padding: 1px 6px;width: fit-content;border: 1px solid #8f8f8f;color: #282828;"
                                                    class=" discount_info_unappeased  "
                                                >
                                                    <i class="gold_reward_badge  fas fa-star-half-alt" style="color: #3f3f3f;"></i>
                                                    <span class="gold_reward_percentage"></span>

                                                    <i style="color: #3b3b3b;/*! font-size: 0.5rem; */opacity: 0.7;" class="hide gold_reward_applied fal fa-question-circle"></i>
                                                </div>
                                            </td>
                                            <td class="gold_reward_price "></td>
                                            <td style="text-align: right; font-size: 0.7rem"  class="gold_reward_unit_price"></td>
                                        </tr>
                                    </table>
                                </div>

                                

                                {if $store->get('Store Type')=='Dropshipping'}
                                    <div class="portfolio_row  portfolio_row_{$item.product_id} "
                                         style="background: none;color:#000">

                                        <div class=" edit_portfolio_item edit_portfolio_item_trigger add_to_portfolio sim_button "
                                             style="text-align: center"><i class="fa fa-plus padding_right_5"></i>
                                            {if empty($labels._add_to_portfolio)}{t}portfolio{/t}{else}{$labels._add_to_portfolio}{/if}</span>
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
                                            <div class="  out_of_stock_row  out_of_stock {if  $item.next_shipment_timestamp<$smarty.now}hide{/if} "
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
                                <!-- If product have variant (piece/units/carton) -->
                                {foreach from=$item.variants item=variant name=variant}
                                    <div id="ordering_variant_{$variant.id}" class="ccccc ordering_variant {if !$smarty.foreach.variant.first}hide{/if}">
                                        <div style="flex-grow:1; padding: 0px 10px; box-sizing: border-box">
                                            <div onclick="open_variant_chooser(this, {$item.product_id})"
                                                style="width: 100%; cursor:pointer; position:relative; padding:3px 0px 3px 10px; border:1px solid #ccc; border-radius: 3px; display: inline-block; box-sizing: border-box">
                                                <span class="open_variant_chooser">{$variant.label}</span>
                                                <div style="display:none;font-size: xx-small;position: absolute;bottom: -14px;text-align: right;width: 100px;">
                                                    <span >{if empty($labels._variant_options)}{t}More buying options{/t}{else}{$labels._variant_options}{/if} ☝</span>
                                                </div>
                                                <i style="position:absolute;right:12px;top:3px" class="fas fa-angle-up"></i>
                                            </div>
                                        </div>

                                        <div class="tw-px-[10px] tw-mt-1.5 tw-h-[52px]">
                                            <table id="price_block_{$item.product_id}" class="price_block discount_info_family_{$variant.id}" >
                                                <tr class="original_price_tr" >
                                                    <td style="width:75px">
                                                        {if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}
                                                        <i class="original_price_checked  fal fa-check" style="color: #727272;font-size: 0.6rem;"></i>
                                                    </td>
                                                    <td class="original_price ">{$variant.price}</td>
                                                    {if isset($variant.price_unit_bis)}
                                                        <td  style="text-align: right; font-size: 0.7rem"  class="original_price">{$variant.price_unit_bis}</td>
                                                    {/if}
                                                </tr>
        
                                                <tr style="color: rgb(243, 121, 52);"  class="gold_reward_product_price hide">
                                                    <td style="width:75px"  data-family_key="{$item_family_key}"   >
                                                        <div class="hide discount_info_applied">
                                                            <div style="display:flex; align-items: center;column-gap: 3px;">
                                                                <div style="cursor: pointer; border-radius: 4px; font-size: 0.7rem;background-color: #4ade8044;padding: 1px 6px;width: fit-content;border: 1px solid #16a34a;color: #0b7933;">
                                                                    <i class="gold_reward_badge  fas fa-star" style="color: green; opacity: 0.6"></i>
                                                                    <span class="gold_reward_percentage"></span>
                                                                </div>
                                                                <i style="color: seagreen;font-size: 0.5rem;" class="hide gold_reward_applied_check fal fa-check"></i>
                                                            </div>
                                                        </div>
        
                                                        <div
                                                            style="cursor: pointer; border-radius: 4px; font-size: 0.7rem;background-color: #75757545;padding: 1px 6px;width: fit-content;border: 1px solid #8f8f8f;color: #282828;"
                                                            class=" discount_info_unappeased  "
                                                        >
                                                            <i class="gold_reward_badge  fas fa-star-half-alt" style="color: #3f3f3f;"></i>
                                                            <span class="gold_reward_percentage"></span>
        
                                                            <i style="color: #3b3b3b;/*! font-size: 0.5rem; */opacity: 0.7;" class="hide gold_reward_applied fal fa-question-circle"></i>
                                                        </div>
                                                    </td>
                                                    <td class="gold_reward_price "></td>
                                                    <td style="text-align: right; font-size: 0.7rem"  class="gold_reward_unit_price"></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="product_pricesxxxx hide" style="margin: 6px 0px">
                                            <div class="product_price" style="font-size: small;display: flex; column-gap: 3px;">
                                                <div>{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}:</div>
                                                <div style="display: flex; justify-content: space-between; align-items: center; flex-grow: 1;">
                                                    <span>{$variant.price}</span>
                                                    {if isset($variant.price_unit_bis)}
                                                        <small>{$variant.price_unit_bis}</small>
                                                    {/if}
                                                </div>
                                            </div>
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
                    <div class="panel_txt_control hide">
                        <span class="hide"><i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2"
                                                                                                      style="height: 16px;"
                                                                                                      value="20"></span>
                        <i class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>
                        <i onclick="close_panel_text(this)" class="fa fa-window-close button"
                           style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

                    </div>
                    <div class="_au_vw_ txt {$item.size_class}">
                        {$item.text}
                    </div>
                {elseif $item.type=='image'}

                    {if $item.link!=''}
                        <a href="{$item.link}">
                    {/if}
                    <img class="panel edit {$item.size_class}"
                         src="{if !preg_match('/^http/',$item.image_website)}{/if}{$item.image_website}"
                         alt="{$item.title}"/>
                    {if $item.link!=''}
                        </a>
                    {/if}

                {elseif $item.type=='video'}
                    <div class="panel  {$item.type} {$item.size_class}" size_class="{$item.size_class}"
                         video_id="{$item.video_id}">
                        <iframe width="470" height="{if $data.item_headers}330{else}290{/if}" frameallowfullscreen=""
                                src="https://www.youtube.com/embed/{$item.video_id}?rel=0&amp;controls=0&amp;showinfo=0"></iframe>

                    </div>
                {/if}

            </div>
        {/foreach}
    </div>

    <div style="clear:both"></div>
</div>


{include file="theme_1/_variants.common.category_products.theme_1.EcomB2B.tpl" device="desktop"   }


<script>
    {foreach from=$data.items item=item  name=analytics_data}
    {if $item.type=='product'}ga('auTracker.ec:addImpression', {
        'id': '{$item.code}',
        'name': '{$item.name|escape:'quotes'}',{if isset($item.category)}
        'category': '{$item.category}',{/if}{if isset($item.raw_price)}
        'price': '{$item.raw_price}',
        {/if}'list': 'Family',
        'position': {$smarty.foreach.analytics_data.index}
    });
    {/if}
    {/foreach}
</script>


