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
    <span class="image">
        {if !empty($image.src)}
        <figure>
               {if !empty($image.link)}<a href="{$image.link}">{/if}
                <img src="{$image.src}"   {if !empty($image.title)} alt="{$image.title}"title="{$image.title}"{/if} />
                {if !empty($image.link)}</a>{/if}
            <figcaption  {if !empty($image.caption_class)}class="{$image.caption_class}"{/if}>{if !empty($image.caption)}{$image.caption}{/if}</figcaption>
        </figure>
        {/if}
     </span>
    {/foreach}
</div>