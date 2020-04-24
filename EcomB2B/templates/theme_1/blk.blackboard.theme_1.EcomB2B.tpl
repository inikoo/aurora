{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2018 at 12:42:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div id="blackboard_{$key}" class="blackboard  " style="position:relative;height:{if isset($data.height)}{$data.height}{else}200{/if}px;width: 1240px">

        {foreach from=$data.images item=image}
            <div id="{$image.id}" class="blackboard_image" style="position: absolute;top:{$image.top}px;left:{$image.left}px;width:{$image.width}px;height:{$image.height}px; ">
                <img src="{$image.image_website}" style="width:{$image.width}px;" link="{if !empty($image.link)}{$image.link}{/if}" alt="{if isset($image.title)}{$image.title}{/if}"
                     title="{if isset($image.title)}{$image.title}{/if}"/>
            </div>
        {/foreach}
        {foreach from=$data.texts item=text}
            <div id="{$text.id}" class="blackboard_text _au_vw_" style="position: absolute;top:{$text.top}px;left:{$text.left}px;width:{$text.width}px;height:{$text.height}px; ">{$text.text}</div>
        {/foreach}
    </div>


</div>


