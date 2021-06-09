{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 May 2021 at 20:03:00 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">
        <i class="toggle_view_items fa-fw fal fa-cogs   button hide" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>
        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0">
        <input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}">

        <span class="padding_left_20 padding_right_10">{t}View{/t}:</span>
        <span class="with_items button unselectable  " onClick="change_webpage_custom_design_products('with_items')"><i class="far fa-box-full  fa-fw"></i> {t}With products{/t}</span>
        <span class="no_items button unselectable hide " onClick="change_webpage_custom_design_products('no_items')"><i class="far fa-box-open  fa-fw"></i> {t}No products{/t}</span>
    </div>


    <div style="clear: both"></div>
</div>


<script>

    function change_webpage_custom_design_products(view) {

        if (view == 'with_items') {
            $('#edit_mode_{$key} .with_items').addClass('hide')
            $('#edit_mode_{$key} .no_items').removeClass('hide')
        } else {
            $('#edit_mode_{$key} .with_items').removeClass('hide')
            $('#edit_mode_{$key} .no_items').addClass('hide')
        }

        $('#preview')[0].contentWindow.change_webpage_custom_design_products({$key}, view)


    }

</script>