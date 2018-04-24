{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 22:57:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div   class="{if !$data.show}hide{/if}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
        <div  class="container">{$data.text}</div>
</div>


