{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 21:08:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;"   >


    <div class=" desktop">{$data.src}</div>


    <textarea class="hide desktop">{$data.src}</textarea>
    <textarea class="hide mobile">{$data.mobile_src}</textarea>

</div>
