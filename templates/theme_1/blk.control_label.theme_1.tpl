{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 July 2017 at 11:19:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<li id="block_label_{$key}" class="column  unselectable  {if !$block.show}very_discreet{/if}" key="{$key}" block="{$block.type}">
            <span class="button open_edit">
            <i class="fa   {$block.icon}" aria-hidden="true"></i>
             <span class="label  ">{$block.label}</span>
            </span>
    <i class="fa button  {if $block.show}fa-eye{else}fa-eye-slash{/if} block_show" aria-hidden="true"></i>
    <i class="fa handle2 fa-arrows" aria-hidden="true"></i>
</li>