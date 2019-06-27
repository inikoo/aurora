{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 June 2018 at 12:12:56 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}






<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_items fa-fw fal fa-cogs   button hide" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>

        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class=" edit_margin bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
  

    </div>


    <div style="clear: both"></div>
</div>

