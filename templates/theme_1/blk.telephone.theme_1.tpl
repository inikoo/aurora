{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:52:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">

      
        <div class="features_sec49 two">
            <div class="container">

                <h2 class="_title" contenteditable="true">{$data._title}</h2>

                <strong class="_telephone" contenteditable="true"   >{$data._telephone}</strong> <em class="_text" contenteditable="true">{$data._text}</em>
                <!--  {if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}   -->
            </div>
        </div>
        </div>
        
        <div class="clearfix"></div>
      
</div>

