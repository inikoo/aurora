{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:20:58 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;" >


        {if $data.link!=''}<a href="{$data.link}">{/if}
        <img src="{if $data.src!=''}{$data.src}{else}https://placehold.it/1240x250{/if}"  class=" {if $data.link!=''}like_button{/if}" link="{$data.link}"  width="100%"  alt="{$data.tooltip}" title="{$data.tooltip}" />
            {if $data.link!=''}</a>{/if}

    <div class="clearfix"></div>
</div>
