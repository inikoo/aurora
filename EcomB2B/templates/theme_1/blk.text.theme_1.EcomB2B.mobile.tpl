{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:49:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div style="clear:both"></div>
<div id="block_{$key}"  class=" {if !$data.show}hide{/if} ">
    <div  style="padding:5px 10px" class="text_blocks    text_template_{$data.template}"  >

        {assign "running_total" 0}

        {foreach from=$data.text_blocks item=text_block key=text_block_key}

            {assign var=running_total value=$running_total+ str_word_count($text_block.text) }

        {/foreach}


        {if $running_total<=50}
        {foreach from=$data.text_blocks item=text_block key=text_block_key}
        <div class="text_block">{$text_block.text}</div>
        {/foreach}

        {else}
        <div class="asset_description text">
            <div class="asset_description_wrap">

                {foreach from=$data.text_blocks item=text_block key=text_block_key}
                    <div class="text_block">{$text_block.text}</div>
                {/foreach}
            </div>
            <div class="clear"></div>
            <p class="read-more">
                <span class="show_all fa-stack "><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-arrow-down fa-stack-1x fa-inverse"></i></span>
            </p>
        </div>
        {/if}



    </div>

</div>

