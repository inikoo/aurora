/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   25 July 2021  17:00 Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/

function toggle_location_picking_pipeline(element) {

    let icon = $(element).find('i')

    let field = $(element).data('field')


    let value = $(element).data('value')
    icon.addClass('fa-spinner fa-spin')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Location')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", field)

    ajaxData.append("value", value)


    console.log(value)

    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
                }

                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }


                for (var key in data.update_metadata.hide) {
                    $('.' + data.update_metadata.hide[key]).addClass('hide')
                }

                for (var key in data.update_metadata.show) {

                    $('.' + data.update_metadata.show[key]).removeClass('hide')
                }

                //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }
    });


}
