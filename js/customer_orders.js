/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  Sat 12 Oct 2019 09:52:58 +0800 MYTKuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/

$(document).on('click', "#take_order", function(ev){
    open_new_order()
})

function open_new_order() {


    if (!$('#take_order i').hasClass('fa-shopping-cart')) {
        return;
    }

    $('#take_order i').removeClass('fa-shopping-cart').addClass('fa-spinner fa-spin')

    new_order();


}

function new_order() {


    var object = 'Order'
    var parent = 'customer'
    var parent_key = $('#customer').attr('key')
    var fields_data = {};


    var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'
    })

    request.done(function (data) {


        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        //console.log(data)
        if (data.state == 200) {
            change_view('orders/' + $('#customer').attr('store_key') + '/' + data.new_id)

        }
        else if (data.state == 400) {
            //TODO make a nice msg
            alert(data.msg)


        }
    })

    request.fail(function (jqXHR, textStatus) {

        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}