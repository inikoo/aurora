{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 March 2019 at 17:49:32 GMT+8, Kuala Lumpur, Malaysia
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


        <div id="banner_height_edit_block_{$key}" name="banner_height_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Height{/t} <input value="" style="width: 30px">px <i class="apply_changes fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>
        <div id="banner_src_edit_block_{$key}" name="banner_src_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Src{/t} https://<input value="" style="width: 900px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>


        <span class="device_controls desktop ">{t}Width:{/t} 1240px {t}Height:{/t}
            <span id="banner_height_{$key}" class="button banner_height" key="{$key}" value="{$height_mobile}" device="desktop" style="border:1px solid #ccc;padding:2px 4px">{$block.height}px</span>
            Ratio=<span class="banner_ratio">{math equation="w/h" w=1240 h=$block.height format="%.2f"} </span>{t} Background:{/t}
             <span id="banner_slide_{$key}" class="button banner_slide" key="{$key}" value="" device="desktop" style="border:1px solid #ccc;padding:2px 4px">slide_1.jpg</span>
{*            <span style="margin-left:20px"> src:
                <span id="banner_src{$key}" class="button banner_src" key="{$key}" device="desktop" value="{$src_mobile}" style="border:1px solid #ccc;padding:2px 4px;">https://{$block.src|truncate:60}</span>
            </span>*}

{*    <ul id="columns" class="sortable_webpage_blocks columns " style="width:1100px;" >


    {foreach from=$content.blocks item=$block key=key}
        {assign var="block_type" value=$block['type']}
        {include file="theme_1/blk.control_label.theme_1.tpl" }
    {/foreach}
        <span class="column  unselectable button "  style="min-width:auto;padding:4px 16px 4px 16px;" ><i class="fa fa-plus" aria-hidden="false"></i></span>
<span id="block_label_4" class="column  unselectable  " key="4" block="images">
            <span class="button open_edit">
            <i class="fa   fa-photo" aria-hidden="true"></i>
             <span class="label  ">Images</span>
            </span>
    <i class="fa button  fa-eye block_show" aria-hidden="true"></i>
    <i class="fa handle2 fa-arrows ui-sortable-handle" aria-hidden="true"></i>
</span>
    </ul>*}
        </span>


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

        <span class="device_controls mobile hide">{t}Width{/t} 420px {t}Height{/t} <span id="banner_height_mobile_{$key}" class="button banner_height" key="{$key}" value="{$height_mobile}" device="mobile"  style="border:1px solid #ccc;padding:2px 4px">{$height_mobile}px</span> r=<span
                    class="banner_ratio_mobile">{math equation="w/h" w=1240 h=$height_mobile format="%.2f"}</span>

                                <span style="margin-left:20px"> src:<span id="banner_src_mobile{$key}" class="button banner_src" key="{$key}" device="mobile" value="{$src_mobile}"
                                                                          style="border:1px solid #ccc;padding:2px 4px;">https://{$src_mobile|truncate:60}</span>
                            </span>

        </span>

    </div>
    <div style="clear: both"></div>
</div>


