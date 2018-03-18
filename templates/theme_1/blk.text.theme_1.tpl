{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:49:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($bottom_margin.top_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} "    >
    <div data-template="{$data.template}"  class="text_blocks container text_template_{$data.template}  "  top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px" >
        {foreach from=$data.text_blocks item=text_block key=text_block_key}
            <div  id="block_{$key}_{$text_block_key}_editor" data-text_block_key="{$text_block_key}" class="text_block">{$text_block.text}</div>
        {/foreach}
    </div>
</div>

