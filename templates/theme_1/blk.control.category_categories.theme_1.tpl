{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 March 2018 at 16:10:04 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
   #sortable_sections {
       width: 100%;border-top:1px solid #ccc;border-bottom:1px solid #ccc;margin-top:10px
   }
   #sortable_sections tr{
       border-bottom:1px solid #ddd
   }
   #sortable_sections tr:last-of-type{
       border-bottom:1px solid #ccc
   }
   #sortable_sections .handle{
       cursor: move;padding-left: 5px;padding-right: 5px
   }
   #sortable_sections .action i{
       padding-left: 5px;padding-right: 5px
   }
</style>


<div id="category_categories_add_panel_dialog" class="hide" style="width:300px;position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 1005">
    <div style="margin-bottom:5px">  <i  onClick="$('#category_categories_add_panel_dialog').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>
    <div  class="add_panel" style="display:flex;">
        <div class="flex-item button" data-size="1" >1x</div>
        <div class="flex-item button" data-size="2">2x</div>
        <div class="flex-item button" data-size="3">3x</div>
        <div class="flex-item button" data-size="4">4x</div>
        <div class="flex-item button" data-size="5">5x</div>

    </div>


</div>





<div id="category_categories_add_category_dialog" class="hide" style="width:300px;position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 1002">
    <div style="margin-bottom:5px">  <i  onClick="close_add_category_to_department_dialog()" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>
    <table class="edit_container" >
        <tr>
            <td>


                <input id="add_category" type="hidden" class=" input_field" value="" has_been_valid="0"/>
                <input id="add_category_dropdown_select_label" field="add_category" style="width:200px" scope="category_webpages" parent="store" data-metadata='{ "parent_category_key":"{$webpage->get('Webpage Scope Key')}"}'

                       parent_key="{$website->get('Website Store Key')}" class=" dropdown_select" value="" has_been_valid="0" placeholder="{t}Family / category code{/t}" action="add_category_to_webpage"

                />
                <span id="add_category_msg" class="msg"></span>
                <div id="add_category_results_container" class="search_results_container hide" style="position: relative;left:-430px" >

                    <table id="add_category_results" >

                        <tr class="hide" id="add_category_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_add_category(this)">
                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                    $("#add_category_dropdown_select_label").on("input propertychange", function (evt) {

                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>


            </td>


        </tr>
    </table>


</div>


<div id="category_categories_items_showcase" class="hide" style="z-index: 2000;background-color: #fff;padding:20px;border:1px solid #ccc;width: 300px;position: absolute;"  >
    <div style="margin-bottom:5px">  <i  onClick="$('#category_categories_items_showcase').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>

    <table style="width:100%;border-bottom: 1px solid #ccc;margin-top: 10px">
        <tr><td  onclick="open_add_category_to_department_dialog('category_categories_items_showcase')" style="border-top: 1px solid #ccc"  class=" button"><span  ><i class="fa fa-sitemap fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Category{/t}</span> </td></tr>
        <tr><td  onclick="open_add_panel_dialog('text')" style="border-top: 1px solid #ccc"  class=" button"><span  ><i class="fa fa-font fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Text{/t}</span> </td></tr>
        <tr><td  onclick="open_add_panel_dialog('image')" style="border-top: 1px solid #ccc"  class=" button"><span  ><i class="fa fa-camera fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Image{/t}</span> </td></tr>

    </table>

</div>

<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_category_categories fa-fw fal fa-cogs   button" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>


        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
                                                                                                                                                      placeholder="0">


        <span id='open_add_category_to_anchor_dialog' onclick="$('#category_categories_add_category_dialog').data('section_index',0);open_add_category_to_department_dialog('open_add_category_to_anchor_dialog')" class="padding_left_20 unselectable button"><i class="fa fa-plus"></i> {t}Category{/t}</span>
        <span onclick="show_sections_overview(this)" class="padding_left_20 unselectable button"><i class="fa fa-cogs"></i> {t}Sections{/t}</span>


        <div id="sections_overview" class="hide" style="position:absolute;z-index:1000;width: 500px;padding:20px;border:1px solid #ccc;background: #fff;color:#555">


            <span onclick="add_category_categories_section('{$key}')" class=" unselectable button"><i class="fa fa-plus"></i> {t}Section{/t}</span>
            <i onclick="$('#sections_overview').addClass('hide')" style="float: right" class="fa fa-window-close button"></i>

            <table id="sortable_sections">
                {foreach from=$block.sections item=section_data name=foo}
                    {if $section_data.type=='anchor'}
                        <tr class="anchor">
                            <td><i class="fas fa-thumbtack fa-fw" style="padding-left: 5px;padding-right: 5px"></i></td>
                            <td>{t}Pinned section{/t}</td>
                            <td class="action"><i class="fa fa-fw fa-plus button"></i></td>
                            <td class="action"></td>
                        </tr>
                    {/if}
                {/foreach}

                <tbody id="sortable_sections_tbody">
                {foreach from=$block.sections item=section_data name=foo}
                    {if $section_data.type!='anchor'}
                        <tr>
                            <td><i class="fa fa-arrows handle fa-fw"></i></td>
                            <td class="_title">{if $section_data.title==''}<span class="discreet">{t}No title{/t}</span>{else}{$section_data.title}{/if}</td>
                            <td class="action"><i class="fa fa-fw fa-plus button"></i></td>
                            <td class="action"><i class="fa fa-fw fa-trash-alt button delete"></i></td>
                        </tr>
                    {/if}
                {/foreach}
                </tbody>
            </table>

        </div>


    </div>


    <div style="clear: both"></div>
</div>

<script>

    function add_category_categories_section(block_key){


        var new_section=$('<tr><td><i class="fa fa-arrows handle"></i></td> <td class="_title">{t}Section title{/t}</td><td class="action"><i class="fa fa-fw fa-plus button"></i></td><td class="action"><i class="fa fa-fw fa-trash-alt button delete"></i></td></tr>')
        $('#sortable_sections_tbody').prepend(new_section)

        $('#preview')[0].contentWindow.add_category_categories_section(block_key)
    }

    function show_sections_overview(element) {

        if ($('#sections_overview').hasClass('hide')) {
            $('#sections_overview').removeClass('hide').offset({
                left: $(element).offset().left
            })
        } else {
            $('#sections_overview').addClass('hide')

        }


    }



    $('#sortable_sections_tbody').sortable(
        {
            handle:'.handle',
            start: function (event, ui) {
                pre = ui.item.index();

                $('#sortable_sections .delete').addClass('hide')

            }, stop: function (event, ui) {
                $('#sortable_sections .delete').removeClass('hide')

                post = ui.item.index();
                $('#preview')[0].contentWindow.move_category_categories_sections('{$key}',pre,post);
                $('#save_button').addClass('save button changed valid')
            }


        })


    $(document).on('click', '#sortable_sections_tbody .delete', function (e) {
        $('#preview')[0].contentWindow.delete_category_categories_section('{$key}',$(this).closest('tr').index());
        $(this).closest('tr').remove()
        $('#save_button').addClass('save button changed valid')
    })

    $(document).on('click', '#sortable_sections .fa-plus', function (e) {
       // $('#preview')[0].contentWindow.delete_category_categories_section('{$key}',$(this).closest('tr').index());

       $('#category_categories_items_showcase').removeClass('hide').offset({
        left: $(this).offset().left,top: $(this).offset().top-15
    }).data('element',this)

        var tr=$($('#category_categories_items_showcase').data('element')).closest('tr')

        var index= tr.index()

        if(!tr.hasClass('anchor')){
            index++;
        }
        $('#category_categories_add_category_dialog').data('section_index',index)


    })


    $(document).on('click', '#category_categories_add_panel_dialog .add_panel div', function (e) {


        var type=$('#category_categories_add_panel_dialog').data('type')
        var size=$(this).data('size')

        var tr=$($('#category_categories_items_showcase').data('element')).closest('tr')

        var index= tr.index()

        if(!tr.hasClass('anchor')){
            index++;
        }


        $('#preview')[0].contentWindow.add_panel('{$key}',type,size,'category_categories',index)


        $('#category_categories_items_showcase').addClass('hide')
        $('#category_categories_add_panel_dialog').addClass('hide')
        $('#sections_overview').addClass('hide')


    })




    function category_categories_section_title_changed(index,text){
        $('#sortable_sections_tbody tr:eq('+index+') ._title').html(text)
    }

    function open_add_category_to_department_dialog(offset_parent_id){



        $('#category_categories_add_category_dialog').removeClass('hide').offset({
            left: $('#'+offset_parent_id).offset().left,top: $('#'+offset_parent_id).offset().top
        }).find('input').focus()

        if(offset_parent_id=='category_categories_items_showcase'){
            $('#category_categories_items_showcase').addClass('hide')

        }


    }

    function open_add_panel_dialog(type){



        $('#category_categories_add_panel_dialog').removeClass('hide').offset({
            left: $('#category_categories_items_showcase').offset().left,top: $('#category_categories_items_showcase').offset().top
        }).data('type',type)

        $('#category_categories_items_showcase').addClass('hide')

    }

    function close_add_category_to_department_dialog(){

        $('#category_categories_add_category_dialog').addClass('hide')
        $('#add_category_dropdown_select_label').val('');


        $('#add_category_results .result').remove();

        $('#add_category_results_container').addClass('hide').removeClass('show')

    }


    function select_dropdown_add_category(element){

        var data=JSON.parse($(element).data('metadata'))
        var value = $(element).attr('value')

        if (value == 0) {
            return
        }


        var new_category=$('' +
            '<div class="category_wrap wrap" data-type="category">' +
            '<div class="category_block" style="position:relative" data-category_key="'+data.category_key+'"  data-category_webpage_key="'+data.category_webpage_key+'"  data-item_type="Guest"  data-link="'+data.category_webpage_link+'" data-webpage_code="'+data.category_webpage_code+'"  >\n' +
            '<div class="item_header_text">'+data.title+'</div>' +
            '<div class="wrap_to_center button"><img src="'+data.image+'" data-image_mobile_website="" data-image_website="" data-src="'+data.image+'"  /></div></div>\n'+
            '<div class="category_block item_overlay hide" >\n'+
                '<div class="item_overlay_item_header_text " style="text-align: center;padding-top:9px" contenteditable="true">'+data.title+'</div>' +
                '<div class="button_container"  >\n'+
                    '<div class="flex-item category_code">'+data.code+'</div>\n'+
                    '<div class="flex-item"> <span class="number_products">'+data.number_products+'</span> <i class="fa fa-cube" aria-hidden="true"></i></div>\n'+
                    '<div class="flex-item" style="border:none;width:40px"><i style="position: relative;top:-12px;margin-left: 5px" class="fa fa-window-close button close_category_block"></i></div>' +
                '</div>\n'+
                '<div class="button_container" >' +
                    '<div class="flex-item full move_to_other_section button">{t}Move to other section{/t}</div>\n'+
                '</div>\n'+
            '<div class="button_container"><div class="flex-item full change_category_image button"><input type="file" name="category_categories_category"  id="file_upload_'+data.category_key+'" class="image_upload_from_iframe hide"   data-parent="Webpage"  data-parent_key="{$webpage->id}"  data-parent_object_scope="Item" data-metadata=\'{ "block":"category_categories", "scope":"category", "scope_key":"'+data.category_key+'"}\'  data-options=\'\' data-response_type="webpage"  /><label for="file_upload_'+data.category_key+'"><i class="fa  fa-image fa-fw button" aria-hidden="true"></i> {t}Change image{/t}</label></div></div>'
        );







        $('#preview')[0].contentWindow.add_guest_to_category_categories('{$key}',$('#category_categories_add_category_dialog').data('section_index'),new_category);
        close_add_category_to_department_dialog();
        $('#save_button').addClass('save button changed valid')


    }


</script>