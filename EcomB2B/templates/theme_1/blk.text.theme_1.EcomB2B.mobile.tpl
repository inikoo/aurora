{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:49:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">
    <div   class="text_blocks container text_template_{$data.template}"  >
        {foreach from=$data.text_blocks item=text_block key=text_block_key}
            <div class="text_block">{$text_block.text}</div>
        {/foreach}
    </div>
</div>

