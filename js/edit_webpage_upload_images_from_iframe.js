/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27-06-2019 21:35:22 MYT Kuala Lumpur Malaysia
 Copyright (c) 2010, Inikoo
 Version 3.0*/

var webpage_scope_droppedFiles = false;

$(document).on('change', '.image_upload_from_iframe', function (e) {


    var ajaxData = new FormData();

    if (webpage_scope_droppedFiles) {
        $.each(webpage_scope_droppedFiles, function (i, file) {
            ajaxData.append('files', file);
            return false;
        });
    }

    $.each($(this).prop("files"), function (i, file) {
        ajaxData.append("files[" + i + "]", file);
        return false;
    });


    var response_type = $(this).data('response_type')

    ajaxData.append("tipo", 'upload_images')
    ajaxData.append("parent", $(this).data('parent'))
    ajaxData.append("parent_key", $(this).data('parent_key'))
    ajaxData.append("parent_object_scope", $(this).data('parent_object_scope'))
    if ($(this).data('metadata') != '') {
        ajaxData.append("metadata", JSON.stringify($(this).data('metadata')))
    }
    if ($(this).data('options') != '') {
        ajaxData.append("options", JSON.stringify($(this).data('options')))
    }
    ajaxData.append("response_type", response_type)


    var element = $(this)

    $.ajax({
        url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


        complete: function () {

        }, success: function (data) {

            // console.log(data)

            if (data.state == '200') {



                switch (element.attr('name')) {

                    case 'logo':
                        $('#website_logo').attr('src', '/wi.php?id='+data.img_key);
                        break;


                    case 'menu':
                        $('#image_control_panel').data('element').attr('src', '/wi.php?id='+data.img_key).attr('image_key', data.img_key).data('src', '/wi.php?id='+data.img_key)

                        break;

                    case 'two_pack':
                        $(element).closest('.one_half').find('img').attr('src', '/wi.php?id='+data.img_key).attr('image_key', data.img_key)
                        break;
                    case 'images':
                    case 'category_categories':
                    case 'category_products':

                        var img_element = $('#image_control_panel').find('.image_upload').data('img')
                        $(img_element).attr('src', '/wi.php?id='+data.img_key);
                        $(img_element).data('src', '/wi.php?id='+data.img_key);

                        break;
                    case 'category_categories_category':
                        var img_element = element.closest('.category_wrap').find('.wrap_to_center img')
                        $(img_element).attr('src', '/wi.php?id='+data.img_key);
                        $(img_element).data('src', '/wi.php?id='+data.img_key);
                        break;
                    case 'blackboard_image':
                        var img_element = $('#image_control_panel').find('.image_upload').data('img')


                        $(img_element).resizable('destroy')
                        $(img_element).closest('.blackboard_image').draggable('destroy')

                        old_height = $(img_element).height()
                        old_width = $(img_element).width()

                        ratio = old_width / old_height


                        if (ratio < data.ratio) {
                            width = old_width
                            height = width / data.ratio

                        } else {
                            height = old_height
                            width = data.ratio * height
                        }

                        $(img_element).height(height)
                        $(img_element).width(width)

                        $(img_element).closest('.blackboard_image').height(height)
                        $(img_element).closest('.blackboard_image').width(width)

                        $(img_element).attr('src', '/wi.php?id='+data.img_key);
                        $(img_element).data('src','/wi.php?id='+data.img_key);


                        set_up_blackboard_image($(img_element).closest('.blackboard_image').attr('id'))

                        update_image()
                        break;
                    case 'update_image_block':
                        $("#preview").contents().find("#block_" + element.attr('block_key')).find('img').attr('src', '/wi.php?id='+data.img_key);
                        break;
                    case 'button_bg':
                        $("#preview").contents().find("#block_" + element.attr('block_key')).find('div.button_block').css('background-image', 'url(' + '/wi.php?id='+data.img_key + ')').attr('button_bg', '/wi.php?id='+data.img_key);
                        break;
                    case 'menu_image':
                        $('#image_control_panel').data('element').attr('src',  '/wi.php?id='+data.img_key).attr('image_key', data.img_key).data('src', '/wi.php?id='+data.img_key)
                        break;
                    case 'footer':
                        $('#change_image').data('element').attr('src', 'wi.php?id=' + data.img_key).attr('web_image_key', data.img_key)
                        break;
                }

                $('#save_button', window.parent.document).addClass('save button changed valid')

            } else if (data.state == '400') {
                swal.fire({
                    title: data.title, text: data.msg
                });
            }

            element.val('')

        }, error: function () {

        }
    });


});
