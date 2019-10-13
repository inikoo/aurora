/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  Sat 12 Oct 2019 09:35:27 +0800 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/


$(document).on('click', "#take_customer_client_order", function (ev) {
    new_customer_client_order()
});

function new_customer_client_order() {


    const object = 'Order';
    const parent = 'customer_client';
    const parent_key = $('#customer_client').data('customer_client_key');
    const fields_data = {};


    const form_data = new FormData();
    form_data.append("tipo", 'new_object');
    form_data.append("object", object);
    form_data.append("parent", parent);
    form_data.append("parent_key", parent_key);
    form_data.append("fields_data", JSON.stringify(fields_data));

    const request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    });

    request.done(function (data) {


        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        if (data.state === 200) {
            change_view('orders/' + $('#customer_client').data('store_key') + '/' + data.new_id)

        } else if (data.state === 400) {
            Swal.fire({
                type: 'error', title: data.msg
            })

        }
    })

    request.fail(function (jqXHR, textStatus) {

        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

        Swal.fire({
            type: 'error', title: 'Server error please contact Aurora support'
        })


    });


}

