{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 17:49:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if !isset($block.height_mobile)}
    {assign var="height_mobile" value="250"}
{else}
    {assign var="height_mobile" value=$block.height_mobile}
{/if}

{if !isset($block.src_mobile)}
    {assign var="src_mobile" value=""}
{else}
    {assign var="src_mobile" value=$block.src_mobile}
{/if}

<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">
        <div style="float:left;min-width: 200px;position: relative;top:2px">
            <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
            <span class="label">{$block.label}</span>
            <i class="device_type desktop fa button fa-fw fa-desktop valid_save" aria-hidden="true"></i>
            <i class="device_type mobile fa button fa-fw fa-mobile very_discreet" aria-hidden="true"></i>
        </div>

        <span class="device_controls desktop ">
            <textarea class="web_block_code_source_input">{$block.src}</textarea> <i data-type="code_src" data-device="desktop" class="apply_changes fa button super_discreet fa-flip-vertical fa-triangle margin_left_10" ></i>

        </span>

        <span class="device_controls mobile hide">
            <textarea class="web_block_code_source_input">{$block.mobile_src}</textarea> <i data-type="code_src" data-device="mobile"  class="apply_changes fa button super_discreet fa-flip-vertical fa-triangle margin_left_10" ></i>
        </span>




    </div>
    <div style="clear: both"></div>
</div>


