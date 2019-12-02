{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 21:51:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div class="hide">
    <span class="button" style="position: relative;top: 5px; left:20px;"><i class="fas fa-plus "></i> {t}Add category{/t}</span> <span class="button" style="margin-left:20px;position: relative;top: 5px; left:20px;"><i
                class="fas fa-trash-alt "></i> {t}Delete this section{/t}</span>
</div>


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="    _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <h1  class="products_title {if !$block.show_title}hide{/if}" style="margin-left:20px;" >{if !empty($lables._see_also)}{$lables._see_also}{else}{t}See also{/t}{/if}</h1>



    <div class="category_blocks  see_also  {if $data.auto}no_edit{/if} "
         data-auto="{$data.auto}"
         data-auto_scope="{$data.auto_scope}"
         data-auto_items="{$data.auto_items}"
         data-auto_last_updated="{$data.auto_last_updated}"

    >


        {foreach from=$data.items item=item_data}
           {include file='splinters/see_also_item.splinter.tpl'}
        {/foreach}


    </div>
    <div style="clear:both"></div>

</div>





