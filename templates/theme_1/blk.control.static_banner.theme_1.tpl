{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 July 2017 at 13:44:11 CEST, Trnava, Slovakia
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


        <div id="static_banner_text_edit_block_{$key}" name="static_banner_text_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc;z-index: 4000">
            <input value="{$block.text}" style="width: 900px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>
        <div id="static_banner_link_edit_block_{$key}" name="static_banner_link_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc;z-index: 4000">
            <input value="{$block.link}" style="width: 450px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>

        <span style="font-style: italic">{t}Must be{/t}: 1240px x 768px  </span>
        <input style="display:none" type="file" block_key="{$key}" name="update_static_banner_block" id="update_static_banner_{$key}" class="static_banner_upload" data-options='{ "min_width":"1240"}'/>
        <label style="margin-left:10px;font-weight: normal;cursor: pointer" for="update_static_banner_{$key}"><i class="fa fa-upload" aria-hidden="true"></i> {t}Upload{/t}</label>


        <span id="static_banner_text_{$key}" key="{$key}" class="static_banner_text button" key="{$key}" style="margin-left:30px">
                                   <i class="fa fa-align-center    " aria-hidden="true"></i>

                               </span>

        <span id="static_banner_link_{$key}" key="{$key}" class="static_banner_link button" style="margin-left:10px">

                                <i class="fa fa-link   {if $block.link=='' }very_discreet{/if} " aria-hidden="true"></i>
                                   <span class="button  {if $block.link=='' }hide{/if} " style="border:1px solid #ccc;padding:2px 4px;">{$block.link|truncate:30}</span>
                                </span>

    </div>
    <div style="clear: both"></div>
</div>
