{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2017 at 00:17:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="block_{$key}" class="{$data.type} _block " style="padding-left:20px;padding-right: 20px;padding-top: 20px;padding-bottom: 60px">

    <div class="one-half-responsive">
        <img style="width:100%;" class="  " src="{$data._image}" alt="">
    </div>
    <div class="one-half-responsive last-column">
        <h1>{$data._title}</h1>
        {if !empty($data._subtitle)}<h3 style="margin-bottom:10px">{$data._subtitle}</h3>{/if}
        <div class="text">{$data._text|replace:'':''}</div>
    </div>
    <div class="clear"></div>


</div>

