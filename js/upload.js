/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  17 November 2019  13:18::23  +0100, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/

$(document).on('change', '.table_input_file', function () {

    const ajaxData = new FormData();


    $.each($(this).prop("files"), function (i, file) {
        ajaxData.append("files[" + i + "]", file);

    });


    $(this).closest('form').get(0).reset();
    ajaxData.append("tipo", $(this).data('data').tipo);
    ajaxData.append("parent", $(this).data('data').parent);
    ajaxData.append("parent_key", $(this).data('data').parent_key);
    ajaxData.append("objects", $(this).data('data').object);
    ajaxData.append("field", $(this).data('field'));
    ajaxData.append("upload_type", $(this).data('data').upload_type)


    if ($('.allow_duplicate_part_reference').length) {
        ajaxData.append("allow_duplicate_part_reference", ($('.allow_duplicate_part_reference').hasClass('fa-check-square') ? 'Yes' : 'No'))

    }

    $.ajax({
        url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {
            if (data.state == '200') {
                if (data.tipo == 'upload_images') {

                    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
                    rows.fetch({
                        reset: true
                    });
                    $('.Number_Images').html('(' + data.number_images + ')')
                    if (data.number_images == 0) {
                        $('div.main_image').addClass('hide');
                        $('form.main_image').removeClass('hide');
                        $('div.main_image img').attr('src', '/art/nopic.png');
                    } else {
                        $('div.main_image').removeClass('hide');
                        $('form.main_image').addClass('hide');
                        $('div.main_image img').attr('src', '/image.php?id=' + data.main_image_key + '&s=270x270');
                    }
                } else if (data.tipo == 'upload_objects') {
                    change_view(state.request + '/upload/' + data.upload_key);
                } else if (data.tipo == 'add_items_to_order') {
                    post_modify_item_order(data)
                }

            } else if (data.state == '400') {
                swal(data.title, data.msg, "error")
            }
        }, error: function () {

        }
    });

});
