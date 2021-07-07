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
    <div style="flex-grow:1;text-align: center;border-bottom: 2px solid purple;font-weight: 800;padding-bottom: 5px"><i class="fa fa-user-tag"></i> {t}Customer discounts{/t}</div>
    <a style="flex-grow:1;" href="custom_design_products.sys"><div style="flex-grow:1;text-align: center;border-bottom: 1px solid #ccc;padding-bottom: 5px"><i class="fa fa-user-shield"></i> {t}Personalized products{/t}</div></a>
</div>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}   {if !$data.show}hide{/if}"  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >

<div class="container _au_vw_">
{if  ($products|@count)==0  and ($families|@count)==0  }
    <div  >{$data.labels.no_items}</div>

{else}
    <div  >{$data.labels.with_items}</div>

{/if}


</div>

    {if  ($families|@count)>0  }
        <h4 style="padding-left: 20px;padding-top:30px">{t}Discounted families{/t}</h4>

        <div class="section " >



            <div class="section_items">
                {foreach from=$families item=category_data}
                <div class="category_wrap"">


                    <div class="category_block" style="position:relative" >

                        <div class="item_header_text" style="margin-bottom: 0;padding: 4px 0 0 0 ">
                            <a href="{$category_data.link}" >
                                <div style="font-weight: 800;padding-bottom: 3px;color: purple">{$category_data.allowance}</div> {$category_data.header_text|strip_tags}
                            </a>
                        </div>
                        <div  style="position: relative;top:-2px;left:3px" class="wrap_to_center "   >
                            <a href="{$category_data.link}">
                                <img src="{if empty($category_data.image_website)}{$category_data.image_src}{else}{$category_data.image_website}{/if}"  />
                            </a>
                        </div>
                        <div style="text-align: center;position: relative;bottom: 5px;font-size: small">
                            {$category_data.expire}
                        </div>
                    </div>



            </div>
            {/foreach}</div>

    <div style="clear:both"></div>
</div>

    {/if}

    {if  ($products|@count)>0  }


    <h4 style="padding-left: 20px;padding-top:30px">{t}Discounted products{/t}</h4>
<div class="warp products  clear">

    {counter start=-1 print=false assign="counter"}
    {foreach from=$products item=item }
        {counter print=false assign="counter"}
    <div style="clear: both" class="product_wrap  {if $logged_in and isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Hint_Bar'}stock_info_hint{/if} wrap type_{$item.type} " data-type="{$item.type}" {if $item.type=='product'} data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} ">
        <div class="product_block item product_container" data-product_id="{$item.product_id}">

            <div class="product_header_text _au_vw_" style="text-align: center;font-weight: 800;color: purple">
                {$item.allowance} <div style="font-size: x-small;font-weight: normal;opacity: .75">{$item.expire}</div>
            </div>

            <div class="wrap_to_center product_image" >
                <a href="{$item.link}"
                   data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                   data-list="Products"
                   onclick="go_product(this); return !ga.loaded;"
                ><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i></a>

                {if $logged_in}
                    <i    data-product_id="{$item.product_id}" data-product_code="{$item.code}" data-favourite_key="0" class="favourite_{$item.product_id} favourite far  fa-heart" aria-hidden="true"></i>

                    {if isset($settings['Display Stock Levels in Category']) and $settings['Display Stock Levels in Category']=='Dot'}

                        <i class="stock_dot stock_level_{$item.product_id}  fa fa-circle" ></i>
                    {/if}

                {/if}
                <a href="{$item.link}"
                   data-analytics='{ "id": "{$item.code}", "name": "{$item.name|escape:'quotes'}",{if isset($item.category)} "category": "{$item.category}",{/if}{if isset($item.raw_price)} "price": "{$item.raw_price}",{/if}"list": "Family", "position":{$counter}}'
                   data-list="Products"
                   onclick="go_product(this); return !ga.loaded;"
                >
                <img src="{$item.image_src}"  />
                </a>
            </div>


            <div class="product_description"  >
                <span class="code">{$item.code}</span>
                <div class="name item_name">{$item.name}</div>

            </div>
            {if $logged_in}
                <div class="product_prices  " >
                    <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$item.price}</div>
                    {assign 'rrp' $item.rrp}
                    {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                </div>
            {else}
                <div class="product_prices  " >
                    <div class="product_price"><small>{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</small></div>

                </div>
            {/if}


            {if $logged_in}

                {if $item.web_state=='Out of Stock'}
                    <div class="ordering log_in can_not_order  out_of_stock_row  {$item.out_of_stock_class} ">

                        <span class="product_footer label ">{$item.out_of_stock_label}</span>
                        <i data-product_id="{$item.product_id}"
                           data-label_remove_notification="{if empty($labels.remove_notification)}{t}Click to remove notification{/t},{else}{$labels.remove_notification}{/if}"
                           data-label_add_notification="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"   title="{if empty($labels.add_notification)}{t}Click to be notified by email when back in stock{/t},{else}{$labels.add_notification}{/if}"    class="far fa-envelope like_button reminder out_of_stock_reminders_{$item.product_id} margin_left_5" aria-hidden="true"></i>




                    </div>
                {elseif  $item.web_state=='For Sale'}

                    <div class="order_row empty  order_row_{$item.product_id} ">
                        <input maxlength=6 class='order_input  ' type="text"' size='2' value='' data-ovalue=''>

                        <div class="label sim_button" style="margin-left:57px">
                            <i class="hide fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span class="hide">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                        </div>


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
    <div style="clear:both"></div>
</div>

</div>
<script>
    {foreach from=$products item=item  name=analytics_data}
    {if $item.type=='product'}ga('auTracker.ec:addImpression', { 'id': '{$item.code}', 'name': '{$item.name|escape:'quotes'}',{if isset($item.category)} 'category': '{$item.category}',{/if}{if isset($item.raw_price)} 'price': '{$item.raw_price}',{/if}'list': 'Favourites', 'position':{$smarty.foreach.analytics_data.index}});
    {/if}
    {/foreach}
</script>

{/if}