/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2017 at 14:23:05 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/

function change_section_visibility(id, value) {




    if (value === 'hide') {
        $('#' + id).addClass('hide')
    } else {
        $('#' + id).removeClass('hide')
    }
    $('#save_button', window.parent.document).addClass('save button changed valid')


}

function change_section_order(pre,post) {

    if (post > pre) {


        $('#sections .webpage_section:eq(' + pre + ')').insertAfter('#sections .webpage_section:eq(' + post + ')');
    } else {


        $('#sections .webpage_section:eq(' + pre + ')').insertBefore('#sections .webpage_section:eq(' + post + ')');
    }

}

jQuery(document).ready(function($) {


    $(document).on('change', '.standard_image', function () {




        var ajaxData = new FormData();

        if (droppedFiles_upload_item_image) {
            $.each(droppedFiles_upload_item_image, function (i, file) {
                ajaxData.append('files', file);
            });
        }

        $.each($(this).prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", $(this).data('_parent'))
        ajaxData.append("parent_key",  $(this).data('parent_key'))
        ajaxData.append("parent_object_scope",  $(this).data('parent_object_scope'))
        ajaxData.append("options",  JSON.stringify($(this).data('options')))
        ajaxData.append("response_type", 'upload_item_image')

        var img=$(this).closest("div").find('img')

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {


                    console.log(data.image_src)
                    console.log(img)

                    img.attr('src', data.image_src)
                    img.data('image_key', data.image_key)
                    img.data('src', data.image_src)

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }

                clearFileInput(document.getElementById("update_image"))


            }, error: function () {

            }
        });


    });

});

function change_background_type(element){

    var _element=$(element).data('element')

    switch($(element).data('type')){
        case 'no_repeat':

            var icon='fa-arrows-h';
            var type='expand_horizontal';

            break;
        case 'expand_horizontal':

            var icon='fa-arrows-v';
            var type='expand_vertical';

            break;
        case 'expand_vertical':

            var icon='fa-arrows';
            var type='expand';

            break;
        case 'expand':

            var icon='fa-ellipsis-h';
            var type='repeat_horizontal';

            break;
        case 'repeat_horizontal':
            var icon='fa-ellipsis-v';
            var type='repeat_vertical';
            break;
        case 'repeat_vertical':
            var icon='fa-th';
            var type='repeat';
            break;
        case 'repeat':
            var icon='fa-arrow-to-left';
            var type='no_repeat';
            break;

    }
    $(element).data('type',type)
    $(_element).removeClass('no_repeat expand_horizontal expand_vertical expand  repeat_horizontal repeat_vertical  repeat')
    $(_element).addClass(type)

    $(element).removeClass('fa-arrows fa-arrows-h fa-arrows-v fa-album fa-arrow-to-left  fa-ellipsis-h fa-ellipsis-v fa-th ')
    $(element).addClass(icon);

    $('#save_button', window.parent.document).addClass('save button changed valid')


}

function delete_background(element){

    var _element=$(element).data('element')

    var editor=$(element).closest('.background_editor')

    $(_element).css('background-image',   'none'   );

    editor.find('.background_delete').addClass('hide')
    editor.find('.background_type').addClass('hide')
    editor.find('.add_background').addClass('very_discreet')

    styles[_element+' background-image']='none'

    $('#save_button', window.parent.document).addClass('save button changed valid')


}


