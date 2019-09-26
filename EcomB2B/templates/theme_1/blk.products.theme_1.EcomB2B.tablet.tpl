{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 17:52:00 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}



<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class=" _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <h1 class="products_title {if !$block.show_title}hide{/if}" style="margin-left:20px;">{$data.title}</h1>



    <div class="products {if !$data.item_headers}no_items_header{/if}"  data-sort="{$data.sort}" >
        {counter start=-1 print=false assign="counter"}
        {foreach from=$data.items item=item}
            {counter print=false assign="counter"}

            <div class="product_wrap
                        {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}stock_info_hint{/if}
                        wrap type_{$item.type} " data-type="{$item.type}" {if $item.type=='product'} data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} ">


                <div class="product_block item product_container tablet" >
                    <div class="product_header_text fr-view" >
                        {$item.header_text}
                    </div>

                    <div class="wrap_to_center product_image" >
                        <a href="{$item.link}"
                           data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                           data-list="Products"
                           onclick="go_product(this); return !ga.loaded;"><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"></i></a>

                        {if $logged_in}
                            <i  data-product_code="{$item.code}"  data-product_id="{$item.product_id}" data-favourite_key="0" class="favourite_{$item.product_id} favourite far  fa-heart" aria-hidden="true"></i>
                            {if isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Dot'}
                                <i class="stock_dot stock_level_{$item.product_id}  fa fa-fw fa-circle" ></i>
                            {/if}
                        {/if}
                        <img src="{$item.image_website}"  />
                    </div>


                    <div class="product_description" style="font-size: 14px;padding:0px 4px;margin-bottom:4px" >
                        <span class="code">{$item.code}</span>
                        <div class="name item_name">{$item.name}</div>

                    </div>
                    {if $logged_in}
                        <div class="product_prices  " style="font-size: 14px;padding:0px 4px">
                            <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$item.price} {if isset($item.price_unit)}<small>{$item.price_unit}</small>{/if}</div>
                            {if !empty($item.rrp)}<div><small>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$item.rrp}</small></div>{/if}
                        </div>
                    {else}
                        <div class="product_prices  " style="font-size: 14px;padding:0px 4px">
                            <div class="product_price"><small>{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</small></div>

                        </div>
                    {/if}


                    {if $logged_in}

                            {if $item.web_state=='Out of Stock'}
                                <div class="ordering log_in can_not_order  out_of_stock_row  out_of_stock ">

                                    <span class="product_footer label ">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span>
                                    <i data-product_id="{$item.product_id}"
                                       data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                       data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$item.product_id} margin_left_5" aria-hidden="true"></i>



                                </div>
                        {elseif  $item.web_state=='For Sale'}

                            <div class="mobile_ordering" style="text-align:center;font-size: 14px;margin-bottom:5px;margin-top:5px" data-settings='{ "pid":{$item.product_id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="" class="order_qty_{$item.product_id}  needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                            </div>
                        {/if}

                    {else}
                        <div class="ordering log_out " >

                            <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                            <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                        </div>

                    {/if}
                    {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}
                        <div  style="width: 100%;height: 5px;" class=" stock_hint stock_level_{$item.product_id}" >
                        </div>
                    {/if}
                </div>
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


