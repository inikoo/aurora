{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 11:12:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  ">

      
        <div class="features_sec49 two">
            <div class="container">
                <h2  >{$data._title}</h2>
                <strong  >{if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}</strong> <em  >{$data._text}</em>
            </div>
        </div>
        </div>
        
        <div class="clearfix"></div>
      
</div>

