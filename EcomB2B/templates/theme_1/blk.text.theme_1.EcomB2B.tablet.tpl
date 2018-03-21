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
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} "   style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >
    <div   class="text_blocks  text_template_{$data.template}"  >
        {foreach from=$data.text_blocks item=text_block key=text_block_key}
            <div class="text_block">{$text_block.text|replace:'<p><br></p>',''}</div>
        {/foreach}
    </div>
</div>