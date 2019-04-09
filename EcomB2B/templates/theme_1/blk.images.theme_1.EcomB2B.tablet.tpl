{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 March 2018 at 13:21:34 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}
<div id="block_{$key}" class="{$data.type} _block blk_images {if !$data.show}hide{/if} " style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
    <div class="image_blocks image_template_{$data.template}">
        {foreach from=$data.images item=image}
            {if !empty($image.src)}
                <figure >
                    {if !empty($image.link)}<a href="{$image.link}">{/if}
                        <img src="{$image.src}" {if !empty($image.title)} alt="{$image.title}"title="{$image.title}"{/if} style="width:100%"/>
                        {if !empty($image.link)}</a>{/if}
                    <figcaption  {if !empty($image.caption_class)}class="{$image.caption_class}"{/if}>{if !empty($image.caption)}{$image.caption}{/if}</figcaption>
                </figure>
            {/if}
        {/foreach}
    </div>
</div>