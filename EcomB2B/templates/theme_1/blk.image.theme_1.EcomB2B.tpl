{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:58:18 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >


        
        {if $data.link!=''}
                    <a href="{$data.link}">    <img src="{$data.src}"  class="rimg "   alt="{$data.tooltip}" title="{$data.tooltip}" /></a>

        {else}
                <img src="{$data.src}"  class="rimg "   alt="{$data.tooltip}" title="{$data.tooltip}" />

        {/if}
        
        

    <div class="clearfix"></div>
</div>
