{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2017 at 14:16:07 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}
<div id="block_{$key}" class="{$data.type} _block blk_images {if !$data.show}hide{/if} "  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px" >
    {foreach from=$data.images item=image}
    <span class=" image">
        <figure>
               {if !empty($image.link)}<a href="{$image.link}">{/if}
                <img src="{$image.src}" alt="{$image.title}"  title="{$image.title}" />
                {if !empty($image.link)}</a>{/if}
            <figcaption  class="{$image.caption_class}">{$image.caption}</figcaption>
        </figure>
     </span>
    {/foreach}
</div>
