{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 March 2018 at 12:26:55 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class="edit_mode"  type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div class="unselectable" style="float:left;margin-right:20px;min-width: 200px;">
        <span   id="open_text_layout_ideas" onclick="change_text_template(this)" class="button unselectable"  ><i class="fa fa-columns" aria-hidden="true"></i>  {t}Change layout{/t}</span>
        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0">
        <input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" placeholder="0">
        <input data-margin="left" class="edit_block_margin edit_block_input left" value="{if isset($block.left_margin)}{$block.left_margin}{else}0{/if}"  placeholder="0">
        <input data-margin="right" class="edit_block_margin edit_block_input right" value="{if isset($block.right_margin)}{$block.right_margin}{else}0{/if}" placeholder="0">
        </div>
    <div style="clear: both"></div>

</div>



