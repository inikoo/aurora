{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 17:54:41 GMT+8, Kuala Lumpur, Malaysia
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


        <div style="float:left">
            {foreach from=$block.sliders key=slider_key  item=slider name=sliders}
                <div key="{$slider_key}" style="" class="button slider_preview   "></div>
            {/foreach}
        </div>


        {foreach from=$block.sliders key=slider_key  item=slider name=sliders}
            <div id="slider_preview_options_{$slider_key}" class="hide slider_preview_options" style="float:left;height: 22px;line-height: 22px">
                <span class="button" style="margin-left:50px;margin-right: 20px"><i class="fa fa-television" aria-hidden="true"></i> {t}Background{/t}</span>

                <i class="fa fa-align-center" aria-hidden="true" style="margin-right: 5px"></i>
                <i class="fa fa-link" aria-hidden="true" style="margin-right: 5px"></i>
                <i class="fa fa-youtube-play" aria-hidden="true" title="{t}Button{/t}" style="margin-right: 5px"></i>
                <i class="fa fa-arrows-alt " aria-hidden="true" title="{t}Click anywhere{/t}" style="margin-right: 5px"></i>

            </div>
        {/foreach}

    </div>
    <div style="clear: both"></div>
</div>
