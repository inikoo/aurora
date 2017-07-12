{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2017 at 14:24:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;" >


        {if $data.link!=''}<a href="{$data.link}">{/if}
        <img src="{if $data.src!=''}{$data.src}{else}http://placehold.it/1240x250{/if}"  class=" {if $data.link!=''}like_button{/if}" link="{$data.link}"  width="100%"  alt="{$data.tooltip}" title="{$data.tooltip}" />
            {if $data.link!=''}</a>{/if}

    <div class="clearfix"></div>
</div>
