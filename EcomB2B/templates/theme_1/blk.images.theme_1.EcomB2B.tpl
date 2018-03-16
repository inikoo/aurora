{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2017 at 14:16:07 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="block_{$key}" class="{$data.type} _block blk_images {if !$data.show}hide{/if} " style="Width:100%;">
    {foreach from=$data.images item=image}
    <span class=" image">
        <figure>
               {if !empty($image.link)}<a href="{$image.link}">{/if}
                <img src="{$image.src}" alt="{$image.title}"  title="{$image.title}" />
                {if !empty($image.link)}</a>{/if}
            <figcaption class="{$image.caption_class}">{$image.caption}</figcaption>
        </figure>
 </span>
    {/foreach}


</div>
<div class="clearfix"></div>
