{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 March 2018 at 14:24:27 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >

    {foreach from=$data.sections item=section_data}

        <div id="section_{$section_data.key}_container" style="position:relative;margin-bottom:10px;;margin-top:0px;" class="section" >


            {if $section_data.type!='anchor'}
                <div  style="position:relative" class="page_break  " >
                    <span  class="section_header title">{$section_data.title}</span>
                    <span class="section_header sub_title" >{$section_data.subtitle}</span>
                </div>
            {/if}

            <div style="display:flex;flex-flow: row wrap;clear:both;">
                {foreach from=$section_data.items item=category_data}
                    {assign stack_index $category_data.stack_index}
                    <div class="category_wrap"   >


                        {if $category_data.type=='category'}
                            <a href="/{$category_data.webpage_code|lower}">
                                <div   class="category_block category_showcase button" style="position:relative"  >


                                    <div class="item_header_text fr-view"  style="text-align: center">{$category_data.header_text}</div>
                                    <div class="wrap_to_center " >
                                        <img  src="{$category_data.image_src}" />
                                    </div>



                                </div>
                            </a>





                        {else}
                            {if $category_data.data.type=='text'}
                                <div id="{$category_data.data.id}" style="position:relative" class=" panel  panel_{$category_data.data.size} {$category_data.data.class}">
                                    <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                                        <i class="fa close_edit_text fa-window-close fa-fw button text" style="margin-bottom:10px" aria-hidden="true"></i><br>
                                        <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                                    </div>

                                    <div class="panel_content fr-view">
                                        {$category_data.data.content}
                                    </div>




                                    <div class="panel_controls hide">
                                        <div class="panel_settings buttons hide" >


                                            <div class="flex-item button" type="update_text"><i class="fa fa-pencil edit_text_icon" aria-hidden="true"></i></div>
                                            <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>

                                            <div class="flex-item button invisible" type="update_class"><i class="fa fa-css3  class_icon" aria-hidden="true"></i></div>
                                            <div class="flex-item button invisible" ><i class="fa" aria-hidden="true"></i></div>

                                        </div>
                                    </div>

                                </div>
                            {elseif $category_data.data.type=='image'}
                                <div id="{$category_data.data.id}" style="position:relative" class=" panel image panel_{$category_data.data.size}">

                                    {if $category_data.data.link!=''}
                                        <a href="{$category_data.data.link}"><img  class="panel_image" src="{$category_data.data.image_src}"  title="{$category_data.data.caption}" /></a>
                                    {else}
                                        <img  class="panel_image" src="{$category_data.data.image_src}"  title="{$category_data.data.caption}" />
                                    {/if}



                                </div>
                            {elseif $category_data.data.type=='code'}
                                <div id="{$category_data.data.id}" code_key="{$category_data.data.key}" style="position:relative;" class=" panel image panel_{$category_data.data.size}">


                                    <iframe class="" src="/panel_code.php?id={$category_data.data.key}"  style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px;background-color:white "
                                            marginwidth="0"
                                            marginheight="0"
                                            hspace="0"
                                            vspace="0"
                                            frameborder="0"
                                            scrolling="no"

                                            sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation" ></iframe>


                                </div>
                            {elseif $category_data.data.type=='page_break'}
                                <div id="{$category_data.data.id}" style="position:relative" class="page_break panel_{$category_data.data.size}">
                        <span class="title" contenteditable="true">
                           {$category_data.data.title}
                       </span>
                                    <span class="sub_title" contenteditable="true">

                        </span>






                                </div>
                            {/if}

                        {/if}

                    </div>
                {/foreach}
            </div>
        </div>



    {/foreach}
       

</div>


<script>

</script>



