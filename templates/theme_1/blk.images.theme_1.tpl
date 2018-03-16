{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:20:58 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>

    #image_control_panel{
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px 10px 10px 10px;
        z-index: 3000;
    }
    #image_control_panel td{
        padding-bottom: 10px;
    }

    div.blk_images figure {
        margin:0px

    }

    div.blk_images {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    div.blk_images figcaption{
        font-family: "Ubuntu", Helvetica, Arial, sans-serif;
        color:#999

    }

    .label{
        padding-right: 20px;
    }

    .caption_align i{
        padding-right: 10px;cursor: pointer;
    }

    figcaption.caption_left{
        text-align: left;

    }
    figcaption.caption_right{
        text-align: right;
    }
    figcaption.caption_center{
        text-align: center;

    }
    figcaption.caption_hide{
        visibility: hidden;
    }

    .success{
        color:#26A65B;
    }


</style>

<div id="template_1" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/1240x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

</div>

<div id="template_2" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

</div>


<div id="template_3" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
</div>


<div id="template_3" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

</div>



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%" >


    <div class="blk_images " top_margin="{if isset($block.top_margin)}{$block.top_margin}{/if}" bottom_margin="{if isset($block.bottom_margin)}{$block.bottom_margin}{/if}" style="{if !empty($block.top_margin)}margin-top:{$block.top_margin}px{/if};{if !empty($block.bottom_margin)}margin-top:{$block.bottom_margin}px{/if}"  >




{if  $data.images|@count==0}

    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">

        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

{else}
    {foreach from=$data.images item=image}
        <span class=" image">
        <figure>
            <img class="button" src="{$image.src}" alt="{$image.title}" display_class="{$image.caption_class}">
            <figcaption contenteditable="true" class="{$image.caption_class}" >{$image.caption}</figcaption>
        </figure>
    </span>
    {/foreach}

{/if}




    </div>




    <div class="clearfix"></div>
</div>

<div class="hide">
    <div id="image_layout_1">
        <span class=" image">
            <figure>
                <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
                <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
            </figure>
        </span>
    </div>


</div>



<div id="image_control_panel" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="close_image_control_panel()"></i>
    </div>

    <table>
        <tr>
            <td class="label">{t}Image{/t}</td><td>
                <input style="display:none" type="file" block_key="{$key}" name="{$data.type}" id="update_image_{$key}" class="image_upload" data-options='{ }'/>
                <label style="font-weight: normal;cursor: pointer;width:100%"  for="update_image_{$key}">
                {t}Click to upload image{/t} <i class="hide fa fa-check success" aria-hidden="true"></i>
                </label>
            </td>
        </tr>
        <tr>
            <td class="label">{t}Tooltip{/t}</td><td><input class="image_tooltip" style="width: 200px" placeholder="tooltip"></td>
        </tr>
        <tr>
            <td class="label">{t}Link{/t}</td><td><input class="image_link" style="width: 200px" placeholder="https://"></td>
        </tr>
        <tr>
            <td class="label">{t}Caption{/t}</td>
            <td class="caption_align">
                <i class="fa fa-align-left super_discreet caption_left" display_class="caption_left" aria-hidden="true"></i>
                <i class="fa fa-align-center super_discreet caption_center" display_class="caption_center" aria-hidden="true"></i>
                <i class="fa fa-align-right super_discreet caption_right" display_class="caption_right" aria-hidden="true"></i>
                <i class="fa fa-ban error super_discreet caption_hide" display_class="caption_hide" aria-hidden="true"></i>
            </td>
        </tr>
    </table>

    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <span class="button"  onclick="update_image()"><i class="fa fa-check-square "></i> {t}OK{/t}</span>
    </div>

</div>

