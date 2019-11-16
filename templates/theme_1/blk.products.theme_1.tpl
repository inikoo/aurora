{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 18:08:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}





<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class=" _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <h1 class="products_title {if !$block.show_title}hide{/if}" style="margin-left:20px;" contenteditable="true">{$data.title}</h1>

    <div class="products {if !$data.item_headers}no_items_header{/if}"  data-sort="{$data.sort}" >
    {foreach from=$data.items item=item}

        <div class="product_wrap wrap type_{$item.type}" data-type="{$item.type}" {if $item.type=='product'} data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} ">




                <div class="product_block item"
                     data-product_id="{$item.product_id}"
                     data-web_state="{$item.web_state}"
                     data-price="{$item.price}"
                     data-rrp="{$item.rrp}"
                     data-code="{$item.code}"
                     data-name="{$item.name}"
                     data-link="{$item.link}"
                     data-webpage_code="{$item.webpage_code}"
                     data-webpage_key="{$item.webpage_key}"
                     data-out_of_stock_class="{$item.out_of_stock_class}"
                     data-out_of_stock_label=""   >





                    <div class="panel_txt_control hide" >
                        <i onclick="close_product_header_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>
                    </div>


                    <div class="product_header_text fr-view" >
                        {$item.header_text}
                    </div>

                    <div class="wrap_to_center product_image" >
                        <i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"></i>
                        <i onclick="remove_product_from_products(this)" style="top:50px;color:red" class="far fa-fw fa-trash-alt more_info delete_product  " title="{t}Remove product{/t}" aria-hidden="true"></i>
                        <i class="far fa-fw  fa-heart favourite" aria-hidden="true"></i>
                        <img src="{$item.image_website}" data-src="{$item.image_src}"  data-image_website="{$item.image_website}" />
                    </div>


                    <div class="product_description"  >
                        <span class="code">{$item.code}</span>
                        <div class="name item_name">{$item.name}</div>
                    </div>

                    <div class="product_prices log_in " >
                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$item.price} {if isset($item.price_unit)}<small>{$item.price_unit}</small>{/if}</div>
                        {if !empty($item.rrp)}<div><small>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$item.rrp}</small></div>{/if}
                    </div>


                    {if $item.web_state=='Out of Stock'}
                        <div class="ordering log_in can_not_order  out_of_stock_row  {$item.out_of_stock_class} ">
                            <span class="product_footer label ">{$item.out_of_stock_label}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>
                        </div>
                    {elseif  $item.web_state=='For Sale'}

                        <div class="order_row empty">
                            <input maxlength=6 class='order_input ' type="text"' size='2' value='' data-ovalue=''>
                            <div class="label sim_button" style="margin-left:57px"><i class="fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span >{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span></div>

                        </div>
                    {/if}



                </div>

        </div>


    {/foreach}
    </div>

    <div style="clear: both"></div>
</div>




