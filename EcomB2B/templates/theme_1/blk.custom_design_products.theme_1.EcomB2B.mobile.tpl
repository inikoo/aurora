{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2021 17:59 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div style="display: flex;padding: 10px 0px 2px 0px">
    <a style="flex-grow:1;color:#888" href="customer_discounts.sys"><div style="flex-grow:1;text-align: center;border-bottom: 1px solid #ccc;padding-bottom: 5px">{t}My discounts{/t}</div></a>
    <div style="flex-grow:1;text-align: center;border-bottom: 2px solid purple;font-weight: 800;padding-bottom: 5px" >{t}Personalized products{/t}</div>
</div>

<div class="content" style="margin-top: 10px">



    <div class=" _au_vw_">
        {if  ($products|@count)==0}
            <div  >{$data.labels.no_items}</div>
        {else}
            <div  >{$data.labels.with_items}</div>
        {/if}
    </div>


    {counter start=-1 print=false assign="counter"}
    {foreach from=$products item=product_data }
        {counter print=false assign="counter"}


        <div class="store-item-list">
                    <span class="sub_wrap" >


                        <a href="{$product_data.link}"
                           data-analytics='{ "id": "{$product_data.code}", "name": "{$product_data.name|escape:'quotes'}",{if isset($product_data.category)} "category": "{$product_data.category}",{/if}{if isset($product_data.raw_price)} "price": "{$product_data.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                           data-list="Products"
                           onclick="go_product(this); return !ga.loaded;"

                           style="z-index: 10000;"><img style="height: auto"
                                                         {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}
                                                             class="image_stock_hint image_stock_hint_{$product_data.product_id} "
                                                         {/if}
                                                        src="{$product_data.image_mobile_website}" alt="{$product_data.name|escape}"></a>



                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">
                            <div class="description">{$product_data.code}
                                {if isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Dot'}
                                    <i class="stock_dot inline stock_level_{$product_data.product_id} fa fa-fw fa-circle" ></i>
                                {/if}
                            </div>
                            <div class="description" {if ($product_data.name|count_characters)>40} style="font-size: 80% {elseif ($product_data.name|count_characters)>35}{/if}">{$product_data.name}</div>
                            {if $logged_in}
                                <div class="price" style="margin-top: 5px">
                                {t}Price{/t}:{$product_data.price}
                            </div>
                                <div class="price">
                                  {t}RRP{/t}: {$product_data.rrp}
                            </div>


                            {if $product_data.web_state=='Out of Stock'}
                                <div style="margin-top:10px;"><span style="padding:5px 10px;" class="highlight-red color-white">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}

                                        <i data-product_id="{$product_data.product_id}"
                                           data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                           data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$product_data.product_id} margin_left_5" aria-hidden="true"></i>

                                    </span>


                                </div>
                            {elseif $product_data.web_state=='For Sale'}

                                <div class="mobile_ordering" data-settings='{ "pid":{$product_data.product_id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input  type="number" min="0" value="" class="needsclick order_qty  order_qty_{$product_data.product_id}">
                                <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                            </div>
                            {/if}

                            {/if}
                        </em>

                    </span>


        </div>
    {/foreach}
</div>

<script>
    {foreach from=$products item=item  name=analytics_data}
    {if $item.type=='product'}ga('auTracker.ec:addImpression', { 'id': '{$item.code}', 'name': '{$item.name|escape:'quotes'}',{if isset($item.category)} 'category': '{$item.category}',{/if}{if isset($item.raw_price)} 'price': '{$item.raw_price}',{/if}'list': 'Favourites', 'position':{$smarty.foreach.analytics_data.index}});
    {/if}
    {/foreach}
</script>