{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 17:50:11 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="    _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <h1 class="products_title {if !$block.show_title}hide{/if}" style="margin-left:20px;">{$data.title}</h1>


    <div class="category_blocks see_also">
        {foreach from=$data.items item=category_data}
            <div class="category_wrap wrap" data-type="{$category_data.type}">


                <div class="category_block" style="position:relative">
                    <div class="item_header_text"><a href="{$category_data.link}">{$category_data.header_text|strip_tags}</a></div>
                    <div style="position: relative;top:-2px;left:3px" class="wrap_to_center ">
                        <a href="{$category_data.link}">
                            <img src="{$category_data.image_website}"/>
                        </a>
                    </div>
                </div>


            </div>
        {/foreach}
    </div>
    <div style="clear:both"></div>

</div>





