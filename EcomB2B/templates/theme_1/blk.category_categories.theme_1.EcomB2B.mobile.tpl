{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2018 at 19:08:35 GMT+8, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" class="{$data.type}  {if !$data.show}hide{/if}" style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
    {foreach from=$data.sections item=section_data key=section_key}
        {if $section_data.type!='anchor'}
            <div class="sec_div">
                <h6 class="title" >{$section_data.title}</h6>
                <div class="decoration deco-7 decoration-margins" style="margin: 0px;margin-top: 4px"></div>
                <div class="subtitle"  >{$section_data.subtitle}</div>
            </div>
        {/if}
        <div class="store-items cat_cats_sec">
            {counter assign=i start=0 print=false}

            {foreach from=$section_data.items item=category_data name=families}
                {if $category_data.type=='category'}
                    {counter}
                    <div class="store-item" ">
                        <a href="{$category_data.link}">
                            <img src="{$category_data.image_mobile_website}" alt="{$category_data.header_text|strip_tags|escape}">
                        </a>
                        <div class="center-text cat_cats_fam_name" style="height: 40px">
                            {$category_data.header_text|strip_tags}
                        </div>
                    </div>
                {/if}
            {/foreach}
            {if $i%2==1}
                <div class="store-item invisible"></div>
            {/if}
            <div class="clear"></div>
        </div>
    {/foreach}


</div>
