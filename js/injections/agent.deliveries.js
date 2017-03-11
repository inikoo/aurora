/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 March 2017 at 14:23:06 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$("#new_agent_delivery").click(function () {
    open_new_delivery()
})

function open_new_delivery() {


    if (!$('#new_agent_delivery i').hasClass('fa-plus')) {
        return;
    }

    $('#new_agent_delivery i').removeClass('fa-plus').addClass('fa-spinner fa-spin')


    var request = '/ar_find.php?tipo=new_agent_delivery_options&parent=' + $('#new_agent_delivery').attr('parent') + '&parent_key=' + $('#new_agent_delivery').attr('parent_key')
console.log(request)
    $.getJSON(request, function (data) {


        if (data.warehouses > 1) {

         alert('to do: dialog to choose warehouse')

          //  $('#new_agent_delivery i').addClass('fa-plus').removeClass('fa-spinner fa-spin')

        } else {
            new_agent_delivery(data.warehouse_key);
        }


    })

}

function new_agent_delivery(warehouse_key) {


    var object = 'SupplierDelivery'
    var parent = $('#new_agent_delivery').attr('parent')
    var parent_key = $('#new_agent_delivery').attr('parent_key')
    var fields_data = {
        warehouse_key: warehouse_key,
        agent_key:$('#new_agent_delivery').attr('agent_key')
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
            change_view($('#new_agent_delivery').attr('parent') + '/' + $('#new_agent_delivery').attr('parent_key') + '/order/' + data.new_id, {
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