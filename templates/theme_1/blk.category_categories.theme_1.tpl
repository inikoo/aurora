{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 March 2018 at 16:20:52 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
#sections_list{
     z-index:1001;position:absolute;width: 300px;padding:10px 20px 20px 20px;border:1px solid #ccc;background: #fff;color:#555
 }
#sections_list table{
    width: 100%;
}
#sections_list td{
       border:1px solid #ccc;padding:3px 7px;

}
#sections_list td.discreet{
   cursor:no-drop;opacity: .5;

}


</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="sections_list" class="hide" data-block_key="{$key}">


    <i onclick="$('#sections_list').addClass('hide')" style="float: right;margin-bottom: 4px" class="fa fa-window-close button"></i>

    <table >
        {foreach from=$block.sections item=section_data name=foo}
            {if $section_data.type=='anchor'}
                <tr class="anchor">
                    <td class="button"><i class="fas fa-thumbtack fa-fw" style="padding-right: 5px"></i> {t}Pinned section{/t}</td>

                </tr>
            {/if}
        {/foreach}

        <tbody id="sections_list_tbody">
        {foreach from=$block.sections item=section_data name=foo}
            {if $section_data.type!='anchor'}
                <tr>
                <td class="_title button">{if $section_data.title==''}<span class="discreet">{t}No title{/t}</span>{else}{$section_data.title}{/if}</td>

                </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>

</div>




<div class="hide">
    <span class="button" style="position: relative;top: 5px; left:20px;"><i class="fas fa-plus "></i> {t}Add category{/t}</span> <span class="button" style="margin-left:20px;position: relative;top: 5px; left:20px;"><i class="fas fa-trash-alt "></i> {t}Delete this section{/t}</span>
</div>


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="clear:both;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >




        <div id="category_sections_{$key}" class="category_blocks cats" >

            {foreach from=$data.sections item=section_data}
                <div class="section {if $section_data.type=='anchor'}anchor{else}non_anchor{/if}" >


                    {if $section_data.type!='anchor'}
                    <div class="page_break">
                        <span class="section_header title items_view " contenteditable="true" ">{$section_data.title}</span> <i onclick="show_add_category_to_category_categories_section(this)"  style="margin-top:9px;margin-left:15px" class="fa fa-plus button hide" title="{t}Add category to this section{/t}"></i>
                        <span class="section_header sub_title items_view "  contenteditable="true">{$section_data.subtitle}</span>
                    </div>
                    {/if}

                    <div class="section_items connectedSortable">
                        {foreach from=$section_data.items item=category_data}
                            <div class="category_wrap wrap" data-type="{$category_data.type}">
                                {if $category_data.type=='category'}


                                    {include file='splinters/category.splinter.tpl'}

                                {elseif $category_data.type=='text'}
                                        <div  class="panel_txt_control hide" >
                                            <span class="hide"><i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20"></span>
                                            <i class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>
                                            <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

                                        </div>
                                        <div style="padding:{$category_data.padding}px" size_class="{$category_data.size_class}" data-padding="{$category_data.padding}" class="fr-view txt {$category_data.size_class}">{$category_data.text}</div>

                                {elseif $category_data.type=='image'}

                                        <img class="panel edit {$category_data.size_class}" size_class="{$category_data.size_class}" src="{if !preg_match('/^http/',$category_data.image_website)}EcomB2B/{/if}{$category_data.image_website}"  data-image_website="{$category_data.image_website}"  data-src="{$category_data.image_src}"    link="{$category_data.link}"  alt="{$category_data.title}" />


                                {/if}

                            </div>
                        {/foreach}</div>
                </div>

            {/foreach}

            <div style="clear:both"></div>
        </div>




</div>


<script>

</script>



