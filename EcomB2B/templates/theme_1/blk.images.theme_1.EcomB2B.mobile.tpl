{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 March 2018 at 13:20:38 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div id="block_{$key}" class="{$data.type} _block blk_images  template_{$data.template} {if !$data.show}hide{/if} tw-flex tw-flex-wrap" style="Width:100%;">
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