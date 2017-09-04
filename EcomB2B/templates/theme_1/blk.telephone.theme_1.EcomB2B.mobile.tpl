{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 September 2017 at 17:26:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}





<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  ">


  
           

             
                  <div class="content footer-links">
                     <h3>{$data._title}</h3>
                         <h2>{if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}</h2>
                     
        <p style="clear: right">{$data._text}</p>

                </div>

          


  


</div>

