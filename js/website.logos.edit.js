/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 16:48:53 BST, Sheffield UK
 Copyright (c) 2018, Inikoo
 Version 3.0*/






$(document).on('change', '.image_upload_XXXX', function (e) {





    var ajaxData = new FormData();

    //var ajaxData = new FormData( );
    if (droppedFiles) {
        $.each(droppedFiles, function (i, file) {
            ajaxData.append('files', file);
            return false;
        });
    }


    $.each($(this).prop("files"), function (i, file) {
        ajaxData.append("files[" + i + "]", file);
        return false;
    });


    ajaxData.append("tipo", 'upload_images')
    ajaxData.append("parent", 'website')
    ajaxData.append("parent_key", $('#webpage_data').data('website_key'))
    ajaxData.append("options", JSON.stringify($(this).data('options')))
    ajaxData.append("response_type", 'website')

    var element = $(this)

    $.ajax({
        url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


        complete: function () {

        }, success: function (data) {


            console.log(element.attr('name'))

            if (data.state == '200') {

                $('#save_button', window.parent.document).addClass('save button changed valid')


                switch (element.attr('name')){
                    case '_Website_Favicon':
                        $('#_Website_Favicon').find('img').attr('src',data.image_src);

                }


                //$('#save_button', window.parent.document).addClass('save button changed valid')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                });
            }

            element.val('')

        }, error: function () {

        }
    });


});
