{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 14:39:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >

    {counter start=-1 print=false assign="counter"}
    {foreach from=$data.items item=item key=stack_index}


        {if $item.type=='product'}
            {counter print=false assign="counter"}

            <div class="store-item-list">
                    <span class="sub_wrap" style="">


                        <a href="{$item.link}"

                           data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                           data-list="Family"
                           onclick="go_product(this); return !ga.loaded;"

                           style="z-index: 10000;"><img src="{$item.image_mobile_website}" alt="{$item.name|escape}"></a>

                         <a class="go_product" href="{$item.link}"

                            data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                            data-list="Family"
                            onclick="go_product(this); return !ga.loaded;"

                            style="z-index: 10000;"><i  class="fal fa-external-link"></i></a>

                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">

                            <div class="description"  {if ($item.name|count_characters)>40} style="font-size: 80% {elseif ($item.name|count_characters)>35}{/if}">{$item.name}</div>
                            {if $logged_in}

                                <div class="price" style="margin-top: 5px">
                                {t}Price{/t}: {$item.price} {if isset($item.price_unit)}{$item.price_unit}{/if}
                                </div>
                                {if !empty($item.rrp)}<div class="price">{t}RRP{/t}: {$item.rrp}</div>{/if}

                            {if $item.web_state=='Out of Stock'}

                                <div style="margin-top:10px;"><span style="padding:5px 10px;" class="highlight-red color-white">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}

                                        <i data-product_id="{$item.product_id}"
                                           data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                                           data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$item.product_id} margin_left_5" aria-hidden="true"></i>

                                    </span>


                                </div>
                                 {if !empty($item.next_shipment_timestamp)  and $item.next_shipment_timestamp>$smarty.now }
                                <div  style="margin-top:8px;padding-left: 0px;font-size: 90%">{t}Expected{/t}: {$item.next_shipment_timestamp|date_format:"%x"}</div>
                                {/if}
                            {elseif $item.web_state=='For Sale'}
                                <div class="mobile_ordering"  data-settings='{ "pid":{$item.product_id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="" class="order_qty_{$item.product_id} needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                            </div>
                            {/if}
                            {else}

                            <div class="log_out_prod_info" >
                                {if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}
                                </div>
                               <div class="log_out_prod_links" >
                                   <div onclick='window.location.href = "/login.sys"'  ><span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                    <div onclick='window.location.href = "/register.sys"'><span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                               </div>

                            {/if}
                        </em>
                             <u>{$item.code}</u>

                    </span>


            </div>
        {/if}
    {/foreach}

</div>

<script>
    {foreach from=$data.items item=item  name=analytics_data}
    {if $item.type=='product'}ga('auTracker.ec:addImpression', { 'id': '{$item.code}', 'name': '{$item.name|escape:'quotes'}',{if isset($item.category)} 'category': '{$item.category}',{/if}{if isset($item.raw_price)} 'price': '{$item.raw_price}',{/if}'list': 'Family', 'position':{$smarty.foreach.analytics_data.index}});
    {/if}
    {/foreach}
</script>
