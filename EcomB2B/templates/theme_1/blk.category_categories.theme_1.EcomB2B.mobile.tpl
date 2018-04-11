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


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


    
  {foreach from=$data.sections item=section_data key=section_key}
                <div  >


                    {if $section_data.type!='anchor'}
                        <div>
                            <h6 class="single_line_height" style="margin-left:10px">{$section_data.title}</h6>
                            <div class="decoration deco-7 decoration-margins" style="margin: 0px;margin-top: 4px"></div>

                            <div class="single_line_height" style="padding-left:10px">{$section_data.subtitle}</div>
                        </div>
                    {/if}


                    <div class="store-items clear" style="margin-top:20px;clear: both">
                        {counter assign=i start=0 print=false}

                        {foreach from=$section_data.items item=category_data name=families}
                            {if $category_data.type=='category'}
                                {counter}
                                <div class="store-item" style="border:1px solid #ccc">
                                    <a href="/{$category_data.webpage_code|lower}">
                                        <img src="{$category_data.image_mobile_website}" alt="{$category_data.header_text|strip_tags|escape}">
                                    </a>
                                    <div class="single_line_height center-text " style="min-height: 36px">
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

                </div>
            {/foreach}


</div>
