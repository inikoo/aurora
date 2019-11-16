{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 March 2018 at 13:49:48 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">




        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0"><input data-margin="bottom" class=" edit_margin bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" placeholder="0">


        <span onclick="$('#preview')[0].contentWindow.add_image_to_blackboard('{$key}')" class="padding_left_20 unselectable button"><i class="fa fa-plus"></i> {t}Image{/t}</span>
        <span onclick="$('#preview')[0].contentWindow.add_text_to_blackboard('{$key}')"class="padding_left_10 unselectable button"><i class="fa fa-plus"></i> {t}Text{/t}</span>


    </div>


    <div id="edit_mode_text_block_{$key}" class="text_block hide" style="float:left;margin-right:20px;min-width: 200px;">

        <span style="margin-left:10px">{t}Text{/t}:</span>


        <span style="margin-left:50px" onclick="close_blackboard_text_edit_view('{$key}')"class="padding_left_10 unselectable button"><i class="fas fa-flip-horizontal fa-sign-out-alt"></i> {t}Exit edit text{/t}</span>



        <span onclick="delete_blackboard_text_edit_view('{$key}')"class="padding_left_10 unselectable button"><i class="fal fa-trash"></i> {t}Delete{/t}</span>


    </div>

    <div style="clear: both"></div>
</div>

