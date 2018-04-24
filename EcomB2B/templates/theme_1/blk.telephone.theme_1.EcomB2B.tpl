{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 11:12:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "40"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "40"}{/if}

<div id="block_{$key}" class="{$data.type} _block  "
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px;"
>


    <div class="telephone_block ">

        <h2>{$data._title}</h2>
        <strong>{if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}</strong> <em>{$data._text}</em>
    </div>



<div class="clear"></div>
</div>


