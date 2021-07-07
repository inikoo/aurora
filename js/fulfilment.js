/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  7 July 2021 at 21:17:16 GMT+8 MYT Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo
 Version 3.0*/

$(document).on('click', "#new_customer_delivery", function (ev) {


    if (!$('#new_customer_delivery i').hasClass('fa-plus')) {
        return;
    }

    $('#new_customer_delivery i').removeClass('fa-plus').addClass('fa-spinner fa-spin')

    var object = 'Fulfilment_Delivery'
    var parent = 'customer'
    var parent_key = $('#customer').attr('key')
    var fields_data = {
        warehouse_key:$(this).attr('warehouse_key')
    };


    var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)

    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    })

    request.done(function (data) {

        if (data.state == 200) {
            change_view(data.redirect)

        } else if (data.state == 400) {
            //TODO make a nice msg
            alert(data.msg)


        }
    })

    request.fail(function (jqXHR, textStatus) {
        alert('Server error please contact Aurora support')
    });



})
