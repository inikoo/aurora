{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 December 2016 at 22:33:01 GMT+8, Plane KL-London
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="add_item_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 100">

    <i class="fa fa-window-close button" aria-hidden="true" style="position:absolute;top:10px;left:10px;"></i>


    <table class="edit_container" style="margin-left:20px">
        <tr>
            <td>


                <input id="add_item" type="hidden" class=" input_field" value="" has_been_valid="0"/>
                <input id="add_item_dropdown_select_label" field="add_item" style="width:200px"
                       scope="category_webpages" parent="store"
                       parent_key="{$store_key}" class=" dropdown_select"
                       value="" has_been_valid="0"
                       placeholder="{t}Family / category code{/t}"/>
                <span id="add_item_msg" class="msg"></span>
                <i id="add_item_save_button" class="fa fa-cloud save dropdown_select hide"
                   onclick="save_this_field(this)"></i>
                <div id="add_item_results_container" class="search_results_container">

                    <table id="add_item_results" border="0" hello="caca" >

                        <tr class="hide" id="add_item_search_result_template" field="" value=""
                            formatted_value="" onClick="select_dropdown_item(this)">
                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                    $("#add_item_dropdown_select_label").on("input propertychange", function (evt) {

                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>


            </td>
            <td style="padding-left:10px;" ><i class="fa fa-plus very_discreet" aria-hidden="true"></i> </td>

        </tr>
    </table>


</div>


<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>

<div id="webpage_preview"  webpage_key="{$webpage->id}"  style="padding:0px;border-bottom:1px solid #ccc;padding-bottom:20px;margin-bottom:200px">


<div style="padding:20px;border-bottom:1px solid #ccc">

    <span style="margin-right:20px" class="hide">
        <i class="fa fa-desktop padding_right_5 " aria-hidden="true"></i>
        <i class="fa fa-tablet padding_right_5" aria-hidden="true"></i>
        <i class="fa fa-mobile" aria-hidden="true"></i>
    </span>

    <span class="button " onclick="toggle_logged_in_view(this)"><i class="fa fa-toggle-on " aria-hidden="true" alt="{t}On{/t}"></i> <span class="unselectable">{t}Logged in{/t}</span></span>
    <span id="publish" class="button save {if $webpage->get('Publish')}changed valid{/if}" style="float:right" onclick="publish(this)"><span class="unselectable">{t}Publish{/t}</span> <i class="fa fa-rocket" aria-hidden="true"></i></span>

    <span style="float:right;margin-right:60px" >
        <i id="description_block_on" class="fa toggle_description_block fa-header fa-fw button" aria-hidden="true"  ></i>
        <span id="description_block_off"  class="toggle_description_block fa-stack hide button" style="position:relative;top:-5px;left:5px"  >
            <i class="fa fa-header fa-stack-1x"></i>
            <i class="fa fa-close fa-stack-2x very_discreet error"></i>
        </span>
    </span>
</div>


{assign 'see_also'  $category->webpage->get_see_also() }


    {assign 'css'  $category->webpage->get('CSS') }

{include file="category.webpage.preview.style.tpl" }







<div id="page_content" style="position:relative">


    <div id="description_block" class="section description_block {$content_data.description_block.class} " >


        <i class="create_text fa fa-align-center fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:10px"></i>
        <i class="create_image fa fa-picture-o fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:30px"></i>


        <div id="image_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>

            <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                <label for="file_upload">
                    <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i><br>
                </label>
            </form>



            <i class="fa caption_icon fa-comment  fa-fw button " style="margin-top:5px"  aria-hidden="true"></i><br>
            <div class="caption hide" >
                <input id="caption_input" value="" >
            </div>
            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>
      </div>



        <div id="text_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>


            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>
        </div>





        {foreach from=$content_data.description_block.blocks key=id item=data}
            {if $data.type=='text'}

                <div id="{$id}" class="webpage_content_header webpage_content_header_text">
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



    <div style="position:relative;">



        <div id="overview_container" class="hide product_blocks" style="margin-bottom:80px;">


        {foreach from=$sections item=section_data}
            {include file="webpage.preview.categories_showcase.overview_section.tpl" section_data=$section_data   }

        {/foreach}

    </div>



    <div id="items_container" class="product_blocks"   style="margin-bottom:80px;"  >

        {foreach from=$sections item=section_data}
            {include file="webpage.preview.categories_showcase.section.tpl" section_data=$section_data   }

        {/foreach}

        <div style="clear:both"></div>
    </div>


    </div>



     <div id="bottom_see_also"  class="{if $see_also|@count eq 0}hide{/if}">
         <div class="title">{t}See also{/t}:</div>
         <div>
         {foreach from=$see_also item=see_also_item name=foo}
                <div class="item" >
                    <div class="image_container" >
                        <a href="http://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style="" /> </a>
                    </div>
                    <div class="label" >
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
             </div>
            <div style="clear:both">
            </div>

    </div>

    <div style="clear:both"></div>

</div>

<script>

    {include file="js/webpage.preview.publish.tpl.js" }
    {include file="js/webpage.preview.description_block.tpl.js" }
    {include file="js/webpage.preview.sections.tpl.js" }




    function toggle_logged_in_view(element){

        var icon=$(element).find('i')
        if(icon.hasClass('fa-toggle-on')){
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

            $('.product_prices.log_in').addClass('hide')
            $('.product_prices.log_out').removeClass('hide')

            $('.ordering.log_in').addClass('hide')
            $('.ordering.log_out').removeClass('hide')


        }else{
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')

            $('.product_prices.log_in').removeClass('hide')
            $('.product_prices.log_out').addClass('hide')
            $('.ordering.log_in').removeClass('hide')
            $('.ordering.log_out').addClass('hide')

        }



    }





</script>