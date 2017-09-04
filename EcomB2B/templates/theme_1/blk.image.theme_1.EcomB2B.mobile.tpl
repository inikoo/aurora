{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 September 2017 at 01:52:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  ">
    
        {if $data.link!=''}
            <a href="{$data.link}"><img class="preload-image responsive-image" data-original="{$data.src}" alt="img"></a>
        {else}
             <img class="preload-image responsive-image" data-original="{$data.src}" alt="img">
        {/if}
              
</div>

