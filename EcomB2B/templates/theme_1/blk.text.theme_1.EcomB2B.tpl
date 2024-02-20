{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 March 2018 at 12:47:06 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}
{if isset($data.right_margin)}{assign "right_margin" $data.right_margin}{else}{assign "right_margin" "0"}{/if}
{if isset($data.left_margin)}{assign "left_margin" $data.left_margin}{else}{assign "left_margin" "0"}{/if}


<style>

</style>

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} "
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px;padding-right:{$right_margin}px;padding-left:{$left_margin}px">

    {assign "running_total" 0}

    {foreach from=$data.text_blocks item=text_block key=text_block_key}

        {assign var=running_total value=$running_total+ str_word_count($text_block.text) }

    {/foreach}

    {if $running_total<=200}
    <div class="text_blocks  container  text_template_{$data.template}">
        {foreach from=$data.text_blocks item=text_block key=text_block_key}
            <div class="text_block_container" style="position: relative">
                <div class="text_block _au_vw_"
                     style="border-style:solid;
                     {if isset($text_block.styles)}
                         {foreach from=$text_block.styles item=style key=style_key}
                             {$style_key}:{$style};
                         {/foreach}
                     {else}
                             border-style:solid;border-top-width: 0px;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-color: #ccc;
                     {/if}
                             "

                >{$text_block.text}</div>
            </div>
        {/foreach}
    </div>
    {else}
    <div class="asset_description">
        <div class="asset_description_wrap">

        <div class="text_blocks  container  text_template_{$data.template}">
            {foreach from=$data.text_blocks item=text_block key=text_block_key}
                <div class="text_block_container" style="position: relative">
                    <div class="text_block _au_vw_"
                         style="border-style:solid;
                         {if isset($text_block.styles)}
                             {foreach from=$text_block.styles item=style key=style_key}
                                 {$style_key}:{$style};
                             {/foreach}
                         {else}
                                 border-style:solid;border-top-width: 0px;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-color: #ccc;
                         {/if}
                                 "

                    >{$text_block.text}</div>
                </div>
            {/foreach}
        </div>

        </div>
        <div class="clear"></div>
        <p class="read-more">
            <span class="show_all fa-stack "><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-arrow-down fa-stack-1x fa-inverse"></i></span>
        </p>
    </div>
    {/if}

</div>