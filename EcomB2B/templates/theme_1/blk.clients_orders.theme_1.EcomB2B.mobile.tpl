﻿{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 25 Oct 2019 21:24:48 +0800 MYT, Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}

<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}   {if !$data.show}hide{/if}" style="padding-top:0;padding-bottom:{$bottom_margin}px">
    <div class="table_top">
        <span class="title">{t}Orders{/t}</span>
    </div>
    <div id="table_container"></div>
</div>