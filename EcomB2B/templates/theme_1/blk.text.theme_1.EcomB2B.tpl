{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 March 2018 at 12:47:06 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">
    <div  class="_text  container" style="margin-top:{if isset($data.top_margin)}{$data.top_margin}{else}20{/if}px;margin-bottom: {if isset($data.bottom_margin)}{$data.bottom_margin}{else}20{/if}px">
        {$data._text}
    </div>
</div>

