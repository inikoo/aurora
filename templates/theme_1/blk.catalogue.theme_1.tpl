{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4:59 pm Friday, 28 February 2020 (MYT), Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="_block {if !$data.show}hide{/if}"  top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div class="text_blocks container text_template_1">

    <h1 id="title" contenteditable="true">{if !empty($data.labels.title)}{$data.labels.title}{else}{t}Catalogue{/t}{/if}</h1>

    </div>

</div>



