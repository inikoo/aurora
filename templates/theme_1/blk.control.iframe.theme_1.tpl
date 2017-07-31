{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 17:49:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">
        <div style="float:left;min-width: 200px;position: relative;top:2px">
            <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
            <span class="label">{$block.label}</span>
        </div>


        <div id="iframe_height_edit_block_{$key}" name="iframe_height_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Height{/t} <input value="{$block.height}" style="width: 30px">px <i class="apply_changes fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>
        <div id="iframe_src_edit_block_{$key}" name="iframe_src_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
            {t}Src{/t} https://<input value="{$block.src}" style="width: 900px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>

        <span>{t}Width{/t} 1240px {t}Height{/t} <span id="iframe_height_{$key}" class="button iframe_height" key="{$key}" style="border:1px solid #ccc;padding:2px 4px">{$block.height}px</span> r=<span
                    class="iframe_ratio">{math equation="w/h" w=1240 h=$block.height format="%.2f"}</span>

                                <span style="margin-left:20px"> src:<span id="iframe_src{$key}" class="button iframe_src" key="{$key}"
                                                                          style="border:1px solid #ccc;padding:2px 4px;">https://{$block.src|truncate:60}</span>
                            </span>
    </div>
    <div style="clear: both"></div>
</div>