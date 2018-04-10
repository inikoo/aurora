{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 March 2018 at 13:20:38 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="block_{$key}" class="{$data.type} _block blk_images  template_{$data.template} {if !$data.show}hide{/if} " style="Width:100%;">
    {foreach from=$data.images item=image}
        <figure >
               {if !empty($image.link)}<a href="{$image.link}">{/if}
                <img src="{$image.src}" alt="{$image.title}" title="{$image.title}" style="width:100%"/>
                {if !empty($image.link)}</a>{/if}
            <figcaption class="{$image.caption_class}">{$image.caption}</figcaption>
        </figure>

    {/foreach}

</div>
