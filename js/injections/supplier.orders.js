/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 16:39:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$("#new_purchase_order").click(function () {
    open_new_purchase_order()
})

function open_new_purchase_order() {


    if (!$('#new_purchase_order i').hasClass('fa-plus')) {
        return;
    }

    $('#new_purchase_order i').removeClass('fa-plus').addClass('fa-spinner fa-spin')


    var request = '/ar_find.php?tipo=new_purchase_order_options&parent=' + $('#new_purchase_order').attr('parent') + '&parent_key=' + $('#new_purchase_order').attr('parent_key')

    $.getJSON(request, function (data) {


        if (data.orders_in_process > 0 || data.warehouses > 1) {
            $('#new_purchase_order i').addClass('fa-plus').removeClass('fa-spinner fa-spin')

        } else {
            new_purchase_order(data.warehouse_key);
        }


    })

}

function new_purchase_order(warehouse_key) {


    var object = 'PurchaseOrder'
    var parent = $('#new_purchase_order').attr('parent')
    var parent_key = $('#new_purchase_order').attr('parent_key')
    var fields_data = {
        warehouse_key: warehouse_key
    };


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
            change_view($('#new_purchase_order').attr('parent') + '/' + $('#new_purchase_order').attr('parent_key') + '/order/' + data.new_id, {
                tab: 'supplier.order.items'
            })

        }
        else if (data.state == 400) {
            //TODO make a nice msg
            alert(data.msg)


        }
    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}