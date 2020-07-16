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


        <div id="iframe_height_edit_block_{$key}" name="iframe_height_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Height{/t} <input value="" style="width: 30px">px <i  data-type="iframe_height_edit_block" class="apply_changes fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>
        <div id="iframe_src_edit_block_{$key}" name="iframe_src_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Src{/t} https://<input value="" style="width: 900px"> <i data-type="iframe_src_edit_block" class="apply_changes fa button fa-check-square margin_left_10" ></i>
        </div>


        <span class="device_controls desktop ">{t}Width{/t} 1240px {t}Height{/t} <span id="iframe_height_{$key}" class="button iframe_height" key="{$key}" value="{$height_mobile}" device="desktop" style="border:1px solid #ccc;padding:2px 4px">{$block.height}px</span> r=<span
                    class="iframe_ratio">{math equation="w/h" w=1240 h=$block.height format="%.2f"}</span>

                                <span style="margin-left:20px"> src:<span id="iframe_src{$key}" class="button iframe_src" key="{$key}" device="desktop" value="{$src_mobile}"
                                                                          style="border:1px solid #ccc;padding:2px 4px;">https://{$block.src|truncate:60}</span>
                            </span>

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

        <span class="device_controls mobile hide">{t}Width{/t} 420px {t}Height{/t} <span id="iframe_height_mobile_{$key}" class="button iframe_height" key="{$key}" value="{$height_mobile}" device="mobile"  style="border:1px solid #ccc;padding:2px 4px">{$height_mobile}px</span> r=<span
                    class="iframe_ratio_mobile">{math equation="w/h" w=1240 h=$height_mobile format="%.2f"}</span>

                                <span style="margin-left:20px"> src:<span id="iframe_src_mobile{$key}" class="button iframe_src" key="{$key}" device="mobile" value="{$src_mobile}"
                                                                          style="border:1px solid #ccc;padding:2px 4px;">https://{$src_mobile|truncate:60}</span>
                            </span>

        </span>


    </div>
    <div style="clear: both"></div>
</div>


