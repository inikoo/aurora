{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 03:36:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<style>

    .google-map {
        width: 100%;
        height: 450px;
    }


</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="width:100%;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >
    <iframe class="google-map"  _src="{$data.src}" src="{if $data.src=='#map'}{$store->get('Store Google Map URL')}{else}{$data.src}{/if}" framescrolling="no" marginheight="0" marginwidth="0" allowfullscreen></iframe>
</div>
