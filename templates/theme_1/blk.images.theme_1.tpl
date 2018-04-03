{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:20:58 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >



        {if  $data.images|@count==0}
            <div class="blk_images "  template="4">
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250"  data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">

        <figure>
            <img class="button" src="https://placehold.it/300x250" data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
</div>
{else}
    <div class="blk_images "  template="{if empty($data.template)}1{else}{$data.template}{/if}"  >
    {foreach from=$data.images item=image}
        <span class=" image">
        <figure>
            <img class="button"  data-width="{$image.width}" src="{$image.src}" alt="{$image.title}" link="{if isset($image.link)}{$image.link}{else}{/if}" display_class="{$image.caption_class}">
            <figcaption contenteditable="true" class="{$image.caption_class}" >{$image.caption}</figcaption>
        </figure>
    </span>
    {/foreach}
    </div>
{/if}


</div>




