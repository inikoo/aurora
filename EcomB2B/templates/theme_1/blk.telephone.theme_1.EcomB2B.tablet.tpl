{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 15:35:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="block_{$key}" class="{$data.type} _block  " style="padding-top: 20px;padding-bottom: 60px">


    <div class=" " style="text-align: center;">
        <h3>{$data._title}</h3>
        <h2>{if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}</h2>
        <p style="clear: right;padding-bottom: 0px;margin-bottom: 0px">{$data._text}</p>
    </div>


</div>

