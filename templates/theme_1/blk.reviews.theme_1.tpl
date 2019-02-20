{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2019 at 13:50:45 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
    {$data.html}
</div>

