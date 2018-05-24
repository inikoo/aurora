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


    {foreach from=$data.items item=item key=stack_index}


        {if $item.type=='product'}

            <div class="store-item-list">
                    <span class="sub_wrap" style="">


                        <a href="{$item.link}" style="z-index: 10000;"><img src="{$item.image_mobile_website}" alt="{$item.name|escape}"></a>

                         <a class="go_product" href="{$item.link}" style="z-index: 10000;"><i  class="fal fa-external-link"></i></a>

                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">

                            <div class="description"  {if ($item.name|count_characters)>40} style="font-size: 80% {elseif ($item.name|count_characters)>35}{/if}">{$item.name}</div>
                            {if $logged_in}
                                <div class="price" style="margin-top: 5px">
                                {t}Price{/t}: {$item.price} {$item.price_unit}
                                </div>
                                {if $item.rrp!=''}
                                <div class="price">
                                  {t}RRP{/t}: {$item.rrp}
                                </div>
                                {/if}

                            {if $item.web_state=='Out of Stock'}

                                <div style="margin-top:10px;"><span style="padding:5px 10px" class="highlight-red color-white">{if empty($labels.out_of_stock)}{t}Out of stock{/t}{else}{$labels.out_of_stock}{/if}</span></div>
                            {elseif $item.web_state=='For Sale'}
                                <div class="mobile_ordering"  data-settings='{ "pid":{$item.product_id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="" class="order_qty_{$item.product_id} needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save far fa-save fa-fw color-blue-dark"></i>
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
