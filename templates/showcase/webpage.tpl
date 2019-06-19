{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 10:36:01 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<div class="webpage_showcase">
<div class="name_and_categories">
    {if $webpage->get('Webpage Scope')=='Info'}
        <span class="strong">{t}Information page{/t}</span>
    {else}
        <span class="strong">{$webpage->get('Webpage Scope')}</span>
    {/if}

    <div style="clear:both"></div>
</div>

<div class="asset_container">
</div>
</div>