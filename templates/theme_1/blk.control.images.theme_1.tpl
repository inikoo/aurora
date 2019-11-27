{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:18:33 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">



        <span   id="open_images_layout_ideas" onclick="change_images_template(this)" class="button unselectable"  ><i class="fa fa-columns" aria-hidden="true"></i>  {t}Change layout{/t}</span>

        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" placeholder="0">


    </div>
    <div style="clear: both"></div>
</div>

