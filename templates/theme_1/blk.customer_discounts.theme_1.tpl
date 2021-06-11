{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 Jun 2021 at 17:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="_block {if !$data.show}hide{/if}"  top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div class="text_blocks container text_template_1">
        <div id="block_{$key}_with_items_editor"  class="text_block with_items">{$data.labels.with_items}</div>
        <div id="block_{$key}_no_items_editor"  class="text_block no_items hide">{$data.labels.no_items}</div>
    </div>
</div>


<script>
    function  change_webpage_customer_discounts(block_key,view) {
        if (view=='with_items') {
            $('#block_'+block_key+'_with_items_editor').addClass('hide')
            $('#block_'+block_key+'_no_items_editor').removeClass('hide')
        } else {
            $('#block_'+block_key+'_with_items_editor').removeClass('hide')
            $('#block_'+block_key+'_no_items_editor').addClass('hide')
        }
    }

</script>




