{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2018 at 12:44:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}" " style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >




        <div id="category_sections_{$key}" class="category_blocks cats" >

            {foreach from=$data.sections item=section_data}
                <div class="section {if $section_data.type=='anchor'}anchor{else}non_anchor{/if}" >


                    {if $section_data.type!='anchor'}
                    <div style="display: flex" class="page_break">
                        <h2 style="flex-grow: 2;font-size: 20px"> <span class=" title  ">{$section_data.title}</span></h2>
                        <span style="flex-grow: 1;font-size: 15px"  class=" sub_title  "  >{$section_data.subtitle}</span>
                    </div>
                    {/if}

                    <div class="section_items">
                        {foreach from=$section_data.items item=category_data}
                            <div class="category_wrap" style="margin-bottom: 44px">
                                {if $category_data.type=='category'}

                                    <div class="category_block" style="position:relative;border:none"  >
                                        <a href="{$category_data.link}">
                                            <img style="object-fit: cover;width: 226px;height: 100%"
                                                 src="{if empty($category_data.image_website)}{$category_data.image_src}{else}{$category_data.image_website}{/if}"  />
                                        </a>



                                    </div>
                                    <div style="max-width: 226px;padding-top: 6px;padding-bottom: 0px">
                                    <h3  style="font-size: 15px;text-align: center;font-weight: normal;"> <a href="{$category_data.link}">{$category_data.header_text|strip_tags}</a></h3>
                                    </div>
                        {elseif $category_data.type=='categoryx'}

                            <div class="category_block" style="position:relative" >

                                <h3 class="item_header_text" style="font-size: 15px"> <a href="{$category_data.link}">{$category_data.header_text|strip_tags}</a></h3>
                                <div  style="position: relative;top:-2px;left:3px" class="wrap_to_center "   >

                                    <a href="{$category_data.link}">
                                        <img src="{if empty($category_data.image_website)}{$category_data.image_src}{else}{$category_data.image_website}{/if}"  />
                                    </a>

                                </div>

                            </div>

                                {elseif $category_data.type=='text'}

                                        <div style="padding:{$category_data.padding}px"  class="_au_vw_ txt  {$category_data.size_class}">{$category_data.text}</div>

                                {elseif $category_data.type=='image'}
                                        {if !empty($category_data.link)}
                                            <a href="{$category_data.link}"><img class="panel {$category_data.size_class}" src="{$category_data.image_website}"  alt="{$category_data.title}" title="{$category_data.title}" /></a>

                                        {else}
                                            <img class="panel {$category_data.size_class}"  src="{$category_data.image_website}"   alt="{$category_data.title}"  title="{$category_data.title}" />

                                        {/if}


                                {/if}

                            </div>
                        {/foreach}</div>
                </div>

            {/foreach}

            <div style="clear:both"></div>
        </div>



</div>
