{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2019 at 12:18:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class=" edit_mode"  type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div class="unselectable" style="float:left;margin-right:20px;min-width: 200px;">



        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0"><input data-margin="bottom" class=" edit_margin bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" placeholder="0">

    </div>
    <div style="clear: both"></div>

</div>



