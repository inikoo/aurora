{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 21:52:22 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->hxds
*}






<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_items fa-fw fal fa-cogs   button hide" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>


        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class=" edit_margin bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
                                                                                                                                                      placeholder="0">

        <span onclick="toggle_block_title(this)" class="toggle_items_title padding_left_20 unselectable button"><i class="fa {if $block.show_title}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Title{/t}</span>
        <span onclick="toggle_see_also_auto(this)" class="toggle_items_auto padding_left_20 unselectable button"><i class="fa {if $block.auto}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Automatic{/t}</span>


        <span class="auto_controls  {if !$block.auto}hide{/if}">
            <input  style="margin-left:20px;width: 30px;text-align: center" class=" edit_see_also_auto_number_items " value="{if !$block.auto_items}5{else}{$block.auto_items}{/if}">
            <span onclick="apply_see_also_changes(this)" style="color:#0EBFE9" class="apply_auto_see_also_items hide button padding_left_5">{t}Apply{/t}</span>
            <i onclick="refresh_see_also(this)" style="margin-left: 5px" class="refresh_auto_see_also_items far fa-fw fa-sync-alt  button"></i>
        </span>

        <span class="manual_controls  {if $block.auto}hide{/if}">
        <span  onclick="open_add_category_dialog(this)" class="padding_left_20 unselectable button"><i class="fa fa-plus"></i> {t}Category{/t}</span>
        </span>


    </div>


    <div style="clear: both"></div>
</div>

{*
for scripts look webpage_preview.tpl
*}
<script>




</script>