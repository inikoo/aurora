{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2017 at 15:07:09 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>

    #catalogue_images {
        padding-top: 10px;
        border-top: 1px solid #ccc;
        width: 100%
    }

    .image_data_container {

        float: left;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .image_container {
        width: 150px;
        height: 100px;

        display: table-cell;
        vertical-align: middle;
        text-align: center;

        background-image: url("/art/binding_pink.jpeg ");
        background-size: contain;
        cursor: pointer;
    }

    .image_container img {
        vertical-align: middle;
        max-width: 150px;
        max-height: 100px;
    }

    .image_info {
        font-size: 70%
    }

    .image_info.right {
        float: right
    }

    .image_data_container_error .image_container {
        cursor: not-allowed
    }

    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 100%
    }

    #sortable li {
        margin: 3px 3px 3px 0;
        padding: 1px;
        float: left;
        width: 127px;
        height: 94px;
        font-size: 4em;
        text-align: center;
    }



</style>


<div id="webpage_section_{$block_name}"  section="{$block_name}"   class="webpage_section {if $show_block!=1}hide{/if}"   >
    <div class="container">

        <div id="grid-filters-container" class="cbp-l-filters-alignRight">
            <div data-filter="*" class="cbp-filter-item-active cbp-filter-item anchor_filter ">
                {t}All{/t}
                <div class="cbp-filter-counter"></div>
            </div>


            {foreach from=$content.catalogue.filters  item=filter key=filters_key }
                <div id="{$filter.id|replace:'filter_':''}" data-filter=".{$filter.id}" class="cbp-filter-item cbp-filter-item-tag">
                    <span class="filter_label">{$filter.label}</span>
                    <div class="cbp-filter-counter"></div>
                </div>

                <div id="filter_edit_link_{$filter.id}" data-filter="{$filter.id}" class="cbp-filter-item-edit cbp-filter-item-link hide" style="border-right:none;margin-right:0px;padding-right:10px;padding-left:10px "><i class="fa fa-link fa-fw" aria-hidden="true"></i></div>
                <div id="filter_edit_label_{$filter.id}" class="cbp-filter-item-edit cbp-filter-item-edit-label hide" data-linked_label="{$filter.id|replace:'filter_':''}" style="margin-right:0px;margin-left:0px" contenteditable="true">{$filter.label}</div>
               <div id="filter_edit_delete_{$filter.id}" class=" cbp-filter-item-edit cbp-filter-item-delete hide" data-linked_label="{$filter.id|replace:'filter_':''}" style="border-left:none;margin-left:-5px;padding-right:10px;padding-left:15px "> <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i></div>
            {/foreach}





            <div id="edit_filter" data-filter=""  style="float:right;padding-left:20px;position:relative;top:5px;cursor:pointer">
                <i class="fa fa-pencil" aria-hidden="true"></i>

            </div>


            <div id="add_filter" data-filter="" class="hide" style="float:right;padding-left:20px;position:relative;top:5px;cursor:pointer">
                <i class="fa fa-plus" aria-hidden="true"></i>

            </div>

            <div id="new_filter_template"  >
                <div   class="to_clone hide"><span >{t}tag{/t}</span><div ></div></div></div>

        </div>

        <div id="grid-container" class="cbp">



            {foreach from=$content.catalogue.items  item=item key=key }
                <div id="cbp_item_{$item.category_key}" class="cbp-item {$item.tags}" data-category_key="{$item.category_key}" data-webpage_key="{$item.webpage_key}" data-key="{$key}" data-guest="{$item.guest}">
                    <a href="" class="cbp-caption ">
                        <div class="cbp-caption-defaultWrap">
                            <img src="{if $item.image_375x250==''}/art/image_375x250.png{else}{$item.image_375x250}{/if}" data-src="{$item.image_375x250}" width="375" height="250"/>
                        </div>
                        <div class="cbp-caption-activeWrap hide">
                            <div class="cbp-l-caption-alignCenter">
                                <div class="cbp-l-caption-body">
                                    <div class="cbp-l-caption-title">{$item.hover_code}</div>
                                    <div class="cbp-l-caption-desc">{$item.hover_label}</div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="cbp-l-grid-projects-title"><span class="item_code" contenteditable="true">{$item.code}</span></div>
                    <i class="fa fa-link  link_to_filter like_button super_discreet hide" style="margin-left:5px;float:right;position:relative;top:-15px" aria-hidden="true"></i>
                    <div class="cbp-l-grid-projects-desc"><span class="item_desc" contenteditable="true">{$item.label}</span></div>


                </div>
            {/foreach}


        </div>

    </div>
    <div class="clearfix marb4"></div>
</div>


<div id="add_item_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 100">

    <i onclick="close_add_item_dialog()" class="fa fa-window-close like_button" aria-hidden="true" style="position:absolute;top:10px;left:10px;"></i>


    <table class="edit_container" style="margin-left:20px">
        <tr>
            <td>


                <input id="add_item" type="hidden" class=" input_field" value="" has_been_valid="0"/>
                <input id="add_item_dropdown_select_label" field="add_item" style="width:200px" scope="category_webpages" parent="store"

                       parent_key="{$store->id}" class=" dropdown_select" value="" has_been_valid="0" placeholder="{t}Family / category code{/t}" action="add_category_to_webpage"

                />
                <span id="add_item_msg" class="msg"></span>
                <div id="add_item_results_container" class="search_results_container hide">

                    <table id="add_item_results" border="0">

                        <tr class="hide" id="add_item_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_item(this)">
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


        </tr>
    </table>


</div>

<div id="edit_item_container" class="hide" style="clear:both;width:100%;min-height: 200px;background: #fff;padding:0px 20px 20px 20px; position:fixed;top:0px">


    <div class="container" style="padding-bottom 5px;text-align: right;padding-right: 20px">
        <span id="disassociate_category" class="like_button hide" style="margin-right:20px">{t}Remove category{/t} <i class="fa fa-trash-o  " style="" aria-hidden="true"></i></span> <span id="insert_category"
                                                                                                                                                                                            class="like_button">{t}Insert category{/t}
            <i class="fa fa-flip-horizontal" style="position:relative;top:3px"><i class="fa fa-reply fa-rotate-270   " style="margin-left:20px" aria-hidden="true"></i></i></span> <i id="prev_item"
                                                                                                                                                                                      class="fa fa-arrow-left like_button"
                                                                                                                                                                                      style="margin-left:20px"
                                                                                                                                                                                      aria-hidden="true"></i> <i
                id="next_item" class="fa fa-arrow-right like_button" style="margin-left:10px" aria-hidden="true"></i> <i class="fa fa-window-close like_button" onclick="hide_image_dialog()" style="margin-left:20px"
                                                                                                                         aria-hidden="true"></i>

    </div>

    <div class="container">


        <div style="float:left;width:450px;">

            <div class="image_info">375x250 <i class="fa fa-expand" style="margin-left:20px" aria-hidden="true"></i> 1.5 (3:2)

                <input style="display:none" type="file" name="update_image" id="update_image" class="input_file"/>
                <label for="update_image">
                    <span class="update_image like_button"><i class="fa fa-upload" style="margin-left:20px" aria-hidden="true"></i> {t}Update image{/t}</span>
                </label>

            </div>
            <img id="edit_image" src="data:image/gif;base64,R0lGODlhAQABAPAAAP///////yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="375" height="250" style="border:1px solid #ccc">

            <div id="edit_title"  contenteditable="true"></div>
            <div id="edit_desc"  contenteditable="true"></div>

            <div style="clear:both"></div>

        </div>

        <div style="float:right;width: 700px;margin-right:20px">

            <h4 style="padding:0px;margin:0px"><span style="color:#777">{t}Category{/t}:</span> <span id="catalogue_code"></span></h4>
            <span id="catalogue_name"></span>


            <div id="catalogue_images" style="">

                <h6 style="clear:both;margin-bottom:5px">{t}Usable images{/t}</h6>
                <div id="catalogue_usable_images"></div>
                <h6 id="header_catalogue_other_images" style="clear:both;margin-bottom:5px">{t}Other images{/t}</h6>
                <div id="catalogue_other_images"></div>

            </div>
            <div style="clear:both"></div>
        </div>

    </div>

    <div class="container" style="border-top: 1px solid #ccc;padding-top:20px">

        <h6>{t}Bird's-eye view{/t}</h6>

        <ul id="sortable">
            {foreach from=$content.catalogue.items  item=item key=key }
                <li class="cbp_thumbnail_item like_button" id="cbp_thumbnail_item_{$item.category_key}" category_key="{$item.category_key}">
                    <img src="{if $item.image_375x250==''}/art/image_375x250.png{else}{$item.image_375x250}{/if}" width="125" height="83">
                    <div class="" style="font-size:10px;margin-top:-5px">{$item.code|truncate:30}</div>
                </li>
            {/foreach}
        </ul>
    </div>


</div>

<script>

    var state ={

    }



    var options = {
        filters: '#grid-filters-container', layoutMode: 'grid', defaultFilter: '*', animationType: 'fadeOut', gapHorizontal: 20, gapVertical: 20, gridAdjustment: 'responsive', mediaQueries: [{
            width: 1500, cols: 5,
        }, {
            width: 1100, cols: 4,
        }, {
            width: 800, cols: 3,
        }, {
            width: 480, cols: 2, options: {
                caption: '', gapHorizontal: 10, gapVertical: 10,
            }
        }], caption: '', displayType: 'default', displayTypeSpeed: 100


    }


    $("#add_filter").click(function (event) {





      //  $('#add_filter').before('<div   class="cbp-filter-item cbp-filter-item-tag"><span class="filter_label">{t}tag{/t}</span><div class="cbp-filter-counter"></div></div>');


        var clone =$('#new_filter_template').find('div.to_clone').clone()



    var id= Math.random().toString(36).substr(2, 18)+'_'+$('.cbp-filter-item-tag').length
        clone.attr('id',id)
        console.log(id)

        clone.addClass('cbp-filter-item cbp-filter-item-tag').data("filter", '.filter_' + id)
        clone.find('span').addClass('filter_label')
        clone.find('div').addClass('cbp-filter-counter')



        clone.insertBefore('#add_filter')



        $('#add_filter').before('<div id="filter_edit_link_filter_'+id+'" class="cbp-filter-item-edit cbp-filter-item-link hide"  data-filter="filter_'+id+'"  style="border-right:none;margin-right:0px;padding-right:10px;padding-left:10px "><i class="fa fa-link fa-fw" aria-hidden="true"></i></div>');


        $('#add_filter').before('<div id="filter_edit_label_filter_'+id+'" class="cbp-filter-item-edit cbp-filter-item-edit-label hide" data-linked_label="'+id+'" style="margin-right:0px;margin-left:0px" contenteditable="true">{t}tag{/t}</div>')
        $('#add_filter').before('<div id="filter_edit_delete_filter_'+id+'" class=" cbp-filter-item-edit cbp-filter-item-delete hide" data-linked_label="'+id+'" style="border-left:none;margin-left:-5px;padding-right:10px;padding-left:15px "><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i></div>')




        if ($('#edit_filter').hasClass('active')) {
            $('.cbp-filter-item-edit').removeClass('hide')
            $('.cbp-filter-item').addClass('hide')
        }


        $('#edit_filter').removeClass('hide')

        $("#grid-container").cubeportfolio('destroy').cubeportfolio(options);

        $('#save_button', window.parent.document).addClass('save button changed valid')


    });

    $('#grid-filters-container').on('click', '.cbp-filter-item-delete', function () {


        $('#'+$(this).data('linked_label')).remove()

        console.log('#filter_edit_link_filter_'+$(this).data('linked_label'))

        $('#filter_edit_link_filter_'+$(this).data('linked_label')).remove()
        $('#filter_edit_label_filter_'+$(this).data('linked_label')).remove()
        $('#filter_edit_delete_filter_'+$(this).data('linked_label')).remove()

       $('.cbp-item').removeClass('filter_'+$(this).data('linked_label'))

        $('#save_button', window.parent.document).addClass('save button changed valid')

    })

    $("#edit_filter").click(function (event) {

        if ($('#edit_filter').hasClass('active')) {

            $('#edit_filter').removeClass('active')

            $('#edit_filter i').addClass('fa-pencil').removeClass('fa-window-close')

            $('.anchor_filter').css('visibility','visible')
            $('.cbp-filter-item').removeClass('hide')
            $('.cbp-filter-item-edit').addClass('hide')
            $("#grid-container").cubeportfolio('destroy').cubeportfolio(options);

            $('.link_to_filter').addClass('hide')

            $('.cbp-filter-item-link').removeClass('cbp-filter-item-edit-active')

            $('#add_filter').addClass('hide')

        } else {
            // open edit

            $('#add_filter').removeClass('hide')
            $('#edit_filter').addClass('active')

            $('#edit_filter i').removeClass('fa-pencil').addClass('fa-window-close')

            $('.cbp-filter-item:not(.anchor_filter) ').addClass('hide')

            $('.anchor_filter').css('visibility','hidden')
            $('.cbp-filter-item-edit').removeClass('hide')


            $("#grid-container").cubeportfolio('filter', '*');


        }


    });


    $('#grid-filters-container').on('click', '.cbp-filter-item-link', function () {

        var filter_class = $(this).data('filter')


        console.log(filter_class)

        if ($(this).hasClass('cbp-filter-item-edit-active')) {
            // close linking sections
            $(this).removeClass('cbp-filter-item-edit-active')
            $('.link_to_filter').addClass('hide')
        } else {
            // open linking sections
            $('.cbp-filter-item-link').removeClass('cbp-filter-item-edit-active')
            $(this).addClass('cbp-filter-item-edit-active')

            $('.link_to_filter').each(function (i, obj) {



                if ($(obj).closest('.cbp-item').hasClass(filter_class)) {
                    $(obj).removeClass('super_discreet fa-chain-broken').addClass('fa-link')
                } else {
                    $(obj).addClass('super_discreet fa-link').addClass('fa-chain-broken')
                }

            });

            $('.link_to_filter').removeClass('hide')


        }


    });


    $('#grid-container').on('click', '.link_to_filter', function () {

        var filter_class = $('#grid-filters-container').find('.cbp-filter-item-edit-active').data('filter')

        console.log(filter_class)

        if ($(this).hasClass('super_discreet')) {
            $(this).removeClass('super_discreet fa-chain-broken ').addClass('fa-link').closest('.cbp-item').addClass(filter_class)
        } else {
            $(this).addClass('super_discreet fa-link').addClass('fa-chain-broken').closest('.cbp-item').removeClass(filter_class)
        }

        $('#save_button', window.parent.document).addClass('save button changed valid')

    });


    $('#grid-container').on('click', 'a', function (e) {

        $('.webpage_section').addClass('hide')
        $('#edit_item_container').removeClass('hide')

        render_image_dialog($(this).closest('.cbp-item'))




        e.preventDefault();
    })

    $('#edit_item_container').on('click', '.cbp_thumbnail_item', function (e) {
        var item_container = $('#cbp_item_' + $(this).attr('category_key'))
        render_image_dialog(item_container)
    })


    $('#edit_item_container').on('click', '.image_container img', function () {


        if ($(this).closest('.image_data_container').hasClass('image_data_container_ok')) {

            $('#edit_image').attr('src', ($(this).attr('src')))


            var src = $(this).attr('src');

            $('#cbp_item_' + $('#catalogue_code').data('category_key')).find('.cbp-caption-defaultWrap img').attr('src', src).data('src', src)
            $('#cbp_thumbnail_item_' + $('#catalogue_code').data('category_key')).find('img').attr('src', src)

            $('#save_button', window.parent.document).addClass('save button changed valid')

        }
    });



    function hide_image_dialog(element) {

        $('.webpage_section').removeClass('hide')
        $('#edit_item_container').addClass('hide')

        $("#grid-container").cubeportfolio('destroy').cubeportfolio(options);


    }


    function render_image_dialog(item_container) {



        if (item_container.find('.cbp-caption-defaultWrap img').data('cbp-src') == undefined) {
            var src = item_container.find('.cbp-caption-defaultWrap img').attr('src')
        } else {
            var src = item_container.find('.cbp-caption-defaultWrap img').data('cbp-src')
        }


        $('#edit_image').attr('src', src)
        $('#edit_title').html(item_container.find('.cbp-l-grid-projects-title span').html())
        $('#edit_desc').html(item_container.find('.cbp-l-grid-projects-desc span').html())



        if (item_container.data('guest') == true) {
            $('#disassociate_category').removeClass('hide')
        } else {
            $('#disassociate_category').addClass('hide')
        }

        var request = '/ar_products.php?tipo=category_data&key=' + item_container.data('category_key')

        $.getJSON(request, function (r) {


            $('#catalogue_code').html(r.data.code).data('category_key', r.data.category_key)
            $('#catalogue_name').html(r.data.label)
            render_category_images(r.data.images)
        })
    }


    function render_category_images(images) {

        $('#catalogue_usable_images').html('');
        $('#catalogue_other_images').html('');

        images.forEach(function (entry) {


            var ratio = parseFloat(entry.ratio);

            var image_class = 'ok';
            var size_class = '';
            var ratio_class = '';


            if (entry.width % 375 == 0 && entry.height % 250 == 0 && entry.width > 0 && entry.height > 0) {
                size_class = 'ok'
            } else if (entry.width < 375 || entry.height < 250) {
                size_class = 'error'
                image_class = 'error'
            } else {
                size_class = 'warning'
            }

            if (image_class != 'error') {
                if (ratio == 1.5) {
                    ratio_class = 'ok'
                } else if (entry.ratio > 1.4 && entry.ratio < 1.6) {
                    ratio_class = 'warning'
                } else {
                    ratio_class = 'error'
                    image_class = 'error'
                    size_class = ''
                }

            }


            var image_html = '<div class="image_data_container image_data_container_' + image_class + ' "><div class="image_container"><img src="/' + entry.small_url + '"></div><span class="image_info  "><i class="fa fa-external-link" style="cursor:pointer" aria-hidden="true"></i> <span class="' + size_class + '">' + entry.width + 'x' + entry.height + '</span>  </span><span class="image_info right ' + ratio_class + '"><i class="fa fa-expand " aria-hidden="true"></i> ' + entry.formatted_ratio + '</span></div>';

            if (image_class != 'error') {
                $('#catalogue_usable_images').append(image_html)
            } else {
                $('#catalogue_other_images').append(image_html)
            }


        });

        var image_html = '<div class="image_data_container "><div class="image_container" style="cursor:default"><img src="/art/image_375x250.png"/></div> <input style="display:none" type="file" name="upload_item_image" id="upload_item_image" class="input_file" /> <label for="upload_item_image"> <span class="image_info  " style="margin-left:20px;cursor:pointer" ><i class="fa fa-upload" aria-hidden="true"></i> {t}Upload new image{/t}  </span></div> </label>';

        $('#catalogue_usable_images').append(image_html)
    }


    var droppedFiles_update_image = false;

    $('#update_image').on('change', function (e) {

        var ajaxData = new FormData();

        if (droppedFiles_update_image) {
            $.each(droppedFiles_update_image, function (i, file) {
                ajaxData.append('files', file);
            });
        }

        $.each($('#update_image').prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
        });

        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'webpage')
        ajaxData.append("parent_key", '{$webpage->id}')
        ajaxData.append("parent_object_scope", JSON.stringify({ scope: 'item'}))
        ajaxData.append("options", JSON.stringify({ width: 375, height: 250}))
        ajaxData.append("response_type", 'webpage')

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {
                    $('#edit_image').attr('src', data.image_src + '&size=small').attr('image_key', data.img_key)
                    var src = data.image_src + '&size=small'
                    $('#cbp_item_' + $('#catalogue_code').data('category_key')).find('.cbp-caption-defaultWrap img').attr('src', src).data('src', src)
                    $('#save_button', window.parent.document).addClass('save button changed valid')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                    });
                }

                clearFileInput(document.getElementById("update_image"))


            }, error: function () {

            }
        });


    });

    var droppedFiles_upload_item_image = false;


    $('#edit_item_container').on('change', '#upload_item_image', function () {


        console.log('caca')

        var ajaxData = new FormData();

        if (droppedFiles_upload_item_image) {
            $.each(droppedFiles_upload_item_image, function (i, file) {
                ajaxData.append('files', file);
            });
        }

        $.each($('#upload_item_image').prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'category')
        ajaxData.append("parent_key", $('#catalogue_code').data('category_key'))
        ajaxData.append("parent_object_scope", 'webpage')
        ajaxData.append("options", JSON.stringify({ width: 375, height: 250}))
        ajaxData.append("response_type", 'upload_item_image')

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {


                    render_category_images(data.images)

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                    });
                }

                clearFileInput(document.getElementById("update_image"))


            }, error: function () {

            }
        });


    });


    function clearFileInput(ctrl) {
        try {
            ctrl.value = null;
        } catch (ex) {
        }
        if (ctrl.value) {
            ctrl.parentNode.replaceChild(ctrl.cloneNode(true), ctrl);
        }
    }

    $('#next_item').click(function (event) {

        var next_item = $('#cbp_item_' + $('#catalogue_code').data('category_key')).next('.cbp-item')
        if (next_item.length) {
            render_image_dialog(next_item)
        } else {
            render_image_dialog($('.cbp-item:first'))
        }

    });

    $('#prev_item').click(function (event) {

        var prev_item = $('#cbp_item_' + $('#catalogue_code').data('category_key')).prev('.cbp-item')
        if (prev_item.length) {
            render_image_dialog(prev_item)
        } else {
            render_image_dialog($('.cbp-item:last'))
        }

    });

    $('#insert_category').click(function (event) {

        $('#add_item_dialog').removeClass('hide').offset({
            top: $(this).offset().top + $(this).height() + 3, left: $(this).offset().left + $(this).width() - $('#add_item_dialog').width()})
        $('#add_item_dropdown_select_label').focus()

    });


    $('#disassociate_category').click(function (event) {

        $('#cbp_thumbnail_item_' + $('#catalogue_code').data('category_key')).remove()

        $("#grid-container").cubeportfolio('remove', $('#cbp_item_' + $('#catalogue_code').data('category_key')));
        $('#cbp_item_' + $('#catalogue_code').data('category_key')).remove()

        var next_item = $('#cbp_item_' + $('#catalogue_code').data('category_key')).next('.cbp-item')
        if (next_item.length) {
            render_image_dialog(next_item)
        } else {
            render_image_dialog($('.cbp-item:first'))
        }

        $('#save_button', window.parent.document).addClass('save button changed valid')

    });


    function close_add_item_dialog(){

        $('#add_item_dialog').addClass('hide')
        $('#add_item_dropdown_select_label').val('');


        $('#add_item_results .result').remove();

        $('#add_item_results_container').addClass('hide').removeClass('show')

    }


    function select_dropdown_item(element) {

        field = $(element).attr('field')
        value = $(element).attr('value')


        if (value == 0) {
            console.log('cacacaca')


            return;
        }


        section_key = $('#add_item_dialog').attr('section_key')

        formatted_value = $(element).attr('formatted_value')
        metadata = $(element).data('metadata')


        $('#add_item_dialog').addClass('hide')
        $('#add_item_dropdown_select_label').val('');


        $('#add_item_results .result').remove();

        $('#add_item_results_container').addClass('hide').removeClass('show')



        var request = '/ar_products.php?tipo=category_data&key=' + value


        $.getJSON(request, function (r) {



            if($('#cbp_thumbnail_item_'+r.data.category_key).length){
                swal({
                    title: "{t}Can't add category{/t}", text:"{t}Category already in the web page{/t}", confirmButtonText: "{t}OK{/t}"
                });
                return

            }


            var anchor_item_key = $('#catalogue_code').data('category_key');


            $('#edit_image').attr('src', '/art/image_375x250.png')
            $('#edit_title').html(r.data.code)
            $('#edit_desc').html(r.data.label)


            $('#catalogue_code').html(r.data.code).data('category_key', r.data.category_key)
            $('#catalogue_name').html(r.data.label)
            render_category_images(r.data.images)

            $('#disassociate_category').removeClass('hide')


            var new_item_html = '\
                <div id="cbp_item_' + r.data.category_key + '" class="cbp-item" data-category_key="' + r.data.category_key + '" data-webpage_key="' + r.data.webpage_key + '" data-guest="true"  > \
                <a href="" class="cbp-caption ">  \
                <div class="cbp-caption-defaultWrap"> \
                <img src="/art/image_375x250.png"  data-src="" width="375" height="250"   > \
                </div>\
                <div class="cbp-caption-activeWrap hide">\
                <div class="cbp-l-caption-alignCenter">\
                <div class="cbp-l-caption-body">\
                <div class="cbp-l-caption-title">' + r.data.code + '</div>\
                <div class="cbp-l-caption-desc">' + r.data.label + '</div>\
                </div>\
                </div>\
                </div>\
                </a>\
                <div class="cbp-l-grid-projects-title"><span  class="item_code" contenteditable="true">' + r.data.code + '</span></div>\
                <i class="fa fa-link  link_to_filter like_button super_discreet hide" style="margin-left:5px;float:right;position:relative;top:-15px" aria-hidden="true"></i>\
                <div class="cbp-l-grid-projects-desc"><span  class="item_desc" contenteditable="true">' + r.data.code + '</span></div>\
                </div>\ '

console.log(anchor_item_key)

            $('#cbp_item_' + anchor_item_key).after(new_item_html)


            var new_item_thumbnail_html = '<li class="cbp_thumbnail_item like_button" id="cbp_thumbnail_item_' + r.data.category_key + '" category_key="'+r.data.category_key+'">\
                <img src="/art/image_375x250.png" width="125" height="83"   >\
                <div style="font-size:10px;margin-top:-5px">' + jQuery.trim(r.data.code).substring(0, 30).trim(this)  + '</div>\
               </li>';


            $('#cbp_thumbnail_item_' + anchor_item_key).after(new_item_thumbnail_html)
            $('#save_button', window.parent.document).addClass('save button changed valid')

        })


    }


    $(function () {
        $("#sortable").sortable();
    });


    $("#sortable").sortable({
        start: function (event, ui) {
            pre = ui.item.index();
        }, stop: function (event, ui) {

            post = ui.item.index();

            if (post > pre) {


                $('#grid-container .cbp-item:eq(' + pre + ')').insertAfter('#grid-container .cbp-item:eq(' + post + ')');
            } else {


                $('#grid-container .cbp-item:eq(' + pre + ')').insertBefore('#grid-container .cbp-item:eq(' + post + ')');
            }
            $('#save_button', window.parent.document).addClass('save button changed valid')

        }
    }).disableSelection();


    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
    }

    $('#grid-container').on('input', '.item_code', function () {
        $('#cbp_thumbnail_item_'+$(this).closest('.cbp-item').data('category_key')).find('div').html($(this).html())
        $(this).closest('.cbp-item').find('.cbp-l-caption-title').html($(this).html())

    })

    $('#grid-container').on('input', '.item_desc', function () {
        $(this).closest('.cbp-item').find('.cbp-l-caption-desc').html($(this).html())

    })


    $('#edit_item_container').on('input', '#edit_title', function () {


        $('#cbp_item_'+$('#catalogue_code').data('category_key')).find('.cbp-l-caption-title').html($(this).html())
        $('#cbp_item_'+$('#catalogue_code').data('category_key')).find('.item_code').html($(this).html())

        $('#cbp_thumbnail_item_'+$('#catalogue_code').data('category_key')).find('div').html($(this).html())



    })

    $('#edit_item_container').on('input', '#edit_desc', function () {


        $('#cbp_item_'+$('#catalogue_code').data('category_key')).find('.cbp-l-caption-desc').html($(this).html())
        $('#cbp_item_'+$('#catalogue_code').data('category_key')).find('.item_desc').html($(this).html())




    })

    document.querySelector("span[contenteditable]").addEventListener("paste", function(e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        var temp = document.createElement("div");
        temp.innerHTML = text;
        document.execCommand("insertHTML", false, temp.textContent);
    });


    $('#grid-filters-container').on('input paste', '.cbp-filter-item-edit-label', function () {


console.log($(this).data('linked_label'))
        $('#'+$(this).data('linked_label')).find('.filter_label').html($(this).html())





    })



    function get_catalogue_section_data() {


        var catalogue_data = {
            'items': [], 'filters': []
        }


        $('.cbp-item').each(function (i, obj) {

            var data = {

            }




            data['image_375x250'] = $(obj).find('.cbp-caption-defaultWrap img').data('src')
            data['category_key'] = $(obj).data('category_key')
            data['webpage_key'] = $(obj).data('webpage_key')

            data['code'] = $(obj).find('.item_code').html()
            data['label'] = $(obj).find('.item_desc').html()
            data['hover_code'] = $(obj).find('.cbp-l-caption-title').html()
            data['hover_label'] = $(obj).find('.cbp-l-caption-desc').html()
            data['tags'] = $(obj).attr('class').replace(/cbp-item/g, '').trim();
            data['guest'] = $(obj).data('guest')





            catalogue_data.items.push(data)

        });

        $('.cbp-filter-item-tag').each(function (i, obj) {

            var data = {

            }



            data['id'] = $(obj).data('filter').replace('.','')
            data['label'] = $(obj).find('.filter_label').html()



            catalogue_data.filters.push(data)

        });


        console.log( catalogue_data.filters)

        return catalogue_data;

    }




    ;(function ($, window, document, undefined) {
        'use strict';


        $('#grid-container').cubeportfolio(options);
    })(jQuery, window, document);

</script>