{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 18:10:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="    _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <h1 class="products_title {if !$block.show_title}hide{/if}" style="margin-left:20px;" >{if !empty($data.overwrite_title)}{$data.overwrite_title}{elseif !empty($lables._see_also)}{$lables._see_also}{else}{t}See also{/t}{/if}</h1>


    <div class="store-items clear" style="margin-top:20px;clear: both">
        {counter assign=i start=0 print=false}

        {foreach from=$data.items item=category_data}
                {counter}
                <div class="store-item"><a href="/{$category_data.webpage_code|lower}"><img src="{$category_data.image_mobile_website}" alt="{$category_data.header_text|strip_tags|escape}"></a>
                    <div class="single_line_height center-text " style="min-height: 32px">{$category_data.header_text|strip_tags}</div>
                </div>

        {/foreach}
        {if $i%2==1}
            <div class="store-item invisible"></div>
        {/if}
        <div class="clear"></div>
    </div>


    <div style="clear:both"></div>

</div>





