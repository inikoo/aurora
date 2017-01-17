{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 January 2017 at 18:37:23 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>
{include file="style.tpl" css=$category->webpage->get('Published CSS') }


<div id="page_content">


<div id="description_block" class="description_block {$content_data.description_block.class}" >


    {foreach from=$content_data.description_block.blocks key=id item=data}


        {if $data.type=='text'}

            <div id="{$id}" class="webpage_content_header fr-view">
                {$data.content}
            </div>
        {elseif $data.type=='image'}
            <div  id="{$id}" class="webpage_content_header webpage_content_header_image"  >
                <img  src="{$data.image_src}"  style="width:100%"  title="{if isset($data.caption)}{$data.caption}{/if}" />
            </div>
        {/if}
    {/foreach}






    <div style="clear:both"></div>
</div>




    <div id="items_container" class="product_blocks"   style="margin-bottom:80px;"  >

        {foreach from=$sections item=section_data}

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
                                <a href="page.php?id={$category_data.webpage_key}">
                                <div   class="category_block category_showcase button" style="position:relative"  >


                                    <div class="category_header_text fr-view"  style="text-align: center">
                                        {$category_data.header_text}
                                    </div>
                                    <div class="wrap_to_center " >
                                        <img draggable="false" src="{$category_data.image_src}" />
                                    </div>

                                    <div style=" display: none;border-top:1px solid #ccc;margin-top:5px" >
                                        <span style="width: 50%;text-align: center;border-right :1px solid #ccc">{$category_data.category_code}</span>
                                        <span style="width: 50%;text-align: center"> {$category_data.number_products} <i class="fa fa-cube" aria-hidden="true"></i></span>
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



                                        <img  class="panel_image" src="{$category_data.data.image_src}"  title="{$category_data.data.caption}" />

                                        <div class="panel_controls hide">
                                            <div class="panel_settings buttons hide">




                                                <div class="flex-item button" type="update_image" title="{t}Change image{/t}">
                                                    <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                                                        <input type="file" name="image_upload"  panel_key="{$category_data.data.id}" id="file_upload_{$category_data.data.id}" class="input_file input_file_panel " multiple/>
                                                        <label for="file_upload_{$category_data.data.id}">
                                                            <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i><br>
                                                        </label>
                                                    </form>
                                                </div>
                                                <div class="flex-item button" type="update_caption"><i class="fa fa-comment caption_icon" aria-hidden="true"></i></div>
                                                <div class="flex-item button" type="update_link"><i class="fa fa-link link_url_icon link_icon" aria-hidden="true"></i></div>
                                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                                            </div>





                                            <div style="position: absolute;color: white;top:60px;left:12px">
                                                {if $category_data.data.size=='1x'}
                                                    220 x 220
                                                {elseif $category_data.data.size=='2x'}
                                                    457 x 320
                                                {elseif $category_data.data.size=='3x'}
                                                    696 x 320
                                                {elseif $category_data.data.size=='4x'}
                                                    934 x {t}any height{/t}
                                                {/if}
                                            </div>

                                            {if  $category_data.stack_index%4==0}

                                                <div class="buttons add_page_break " style="top:80px" >
                                                    <div class="flex-item button" type="page_break" title="Page break" ><i class="fa  fa-window-minimize" aria-hidden="true" ></i></div>

                                                </div>
                                            {/if}



                                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                                <input  value="{$category_data.data.caption}" >
                                            </div>
                                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                                <input  value="{$category_data.data.link}" placeholder="http://">
                                            </div>
                                        </div>
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

                                                sandbox="allow-scripts allow-same-origin allow-popups" ></iframe>


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

        <div style="clear:both"></div>
    </div>






    <div style="clear:both"></div>

</div>

