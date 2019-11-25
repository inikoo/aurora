{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 July 2017 at 11:03:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">
        <div style="float:left;min-width: 200px;">
            <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
            <span class="label">{$block.label}</span>


            <span style="margin-left:50px">{t}Margin{/t}:</span>
            <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" placeholder="0">


        </div>

    </div>
    <div style="clear: both"></div>
</div>