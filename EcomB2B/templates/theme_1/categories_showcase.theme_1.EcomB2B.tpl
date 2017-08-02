{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 19:51:47 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

{include file="style.tpl" css=$webpage->get('Published CSS') }


<body xmlns="http://www.w3.org/1999/html">


<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less2">



            <div id="page_content" class="container">

                <span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>

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
                                            <img  class="panel_image" src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" />
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

        <div style="clear:both"></div>
    </div>






    <div style="clear:both"></div>

</div>



            </div>
        </div>


        <div class="clearfix marb12"></div>

        {include file="theme_1/footer.EcomB2B.tpl"}

    </div>

</div>


</body>

</html>