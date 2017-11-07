{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 11:11:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if $data.src=='' or ($data.src=='#map' and  $store->get('Store Google Map URL')=='' )}

    <div style="height: 20px"></div>

{else}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >



    <div class="one_full">
        <iframe     class="google-map2"  _src="{$data.src}" src="{if $data.src=='#map'}{$store->get('Store Google Map URL')}{else}{$data.src}{/if}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" allowfullscreen></iframe>

    </div>

    <div class="clearfix marb6"></div>

</div>

{/if}