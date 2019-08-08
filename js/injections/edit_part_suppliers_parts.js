/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 08-08-2019 13:45:00 MYTKuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


function set_as_principal_supplier_part(part_sku,supplier_part_key) {




    var form_data = new FormData();
    form_data.append("tipo", 'edit_field')
    form_data.append("object", 'Part')
    form_data.append("field", 'Part Main Supplier Part Key')
    form_data.append("key", part_sku)

    form_data.append("value", supplier_part_key)

    var request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    })

    request.done(function (data) {



        console.log(data)
        if (data.state == 200) {

            //todo make change with ajax nicely
            location.reload();

          


        } else if (data.state == 400) {

            swal('Oops...', data.msg, 'error')
        }
    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}