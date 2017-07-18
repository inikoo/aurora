{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 18:08:19 GMT+8, Kuala Lumpur, Malaysia
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


        {foreach from=$block.columns key=column_key  item=column }
            <div id="counter_link_edit_block_{$key}_{$column_key}" name="counter_link_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc;z-index: 4000">
                <input value="{$column.link}" style="width: 450px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
            </div>
            <input id="counter_number_{$key}_{$column_key}" type="number" key="{$key}" column_key="{$column_key}" value="{$column.number}" style="width: 60px" class="counter_number"/>
            <i id="counter_link_{$key}_{$column_key}" style="margin-right: 10px" key="{$key}" column_key="{$column_key}" class="fa fa-link button counter_link  {if $column.link=='' }very_discreet{/if} "
               aria-hidden="true"></i>
        {/foreach}

    </div>
    <div style="clear: both"></div>
</div>