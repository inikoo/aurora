{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 January 2018 at 15:51:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div class="name_and_categories">
    <span class="strong">{t}Department{/t}: <span class="link" onclick="change_view('category/{$category->id}')">{$category->get('Code')}</span> {$category->get('Label')} </span>
    <ul class="tags " style="float:right">
        {foreach from=$webpage->get_parents_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')" title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both"></div>
</div>

<div class="asset_container">
</div>
