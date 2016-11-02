/*Author: Raul Perusquia <raul@inikoo.com>
 Created:14 July 2016 at 13:43:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function show_create_delivery() {


    if ($('#new_delivery').hasClass('hide')) {


        if (state.tab == 'supplier.order.items') {
            grid.columns.findWhere({
                name: 'checkbox'
            }).set("renderable", true)

            grid.columns.findWhere({
                name: 'operations'
            }).set("renderable", true)

            grid.columns.findWhere({
                name: 'delivery_quantity'
            }).set("renderable", true)
        } else {

            change_tab('supplier.order.items', {
                'create_delivery': 1
            })
        }

        $('#tabs').addClass('hide')
        $('#new_delivery').removeClass('hide')


        $('#delivery_number').val('').focus()

    } else {
        close_create_delivery()
    }

}

function close_create_delivery() {
    $('#tabs').removeClass('hide')
    $('#new_delivery').addClass('hide')

    grid.columns.findWhere({
        name: 'checkbox'
    }).set("renderable", false)

    grid.columns.findWhere({
        name: 'operations'
    }).set("renderable", false)

    grid.columns.findWhere({
        name: 'delivery_quantity'
    }).set("renderable", false)
}

function save_delivery_qty_change(element) {
    console.log('x')


}


function change_on_delivery(element) {

    if ($(element).hasClass('fa-pause')) {
        $(element).removeClass('fa-pause').addClass('fa-truck')
        $('#delivery_quantity_' + $(element).attr('key')).css({
            'visibility': 'visible'
        }).attr('on', 1)

    } else {
        $(element).addClass('fa-pause').removeClass('fa-truck')
        $('#delivery_quantity_' + $(element).attr('key')).css({
            'visibility': 'hidden'
        }).attr('on', 0)

    }


}

function quick_create_delivery() {

    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
    $('#delivery_number').val(object_data.purchase_order_number)
    $('#quick_create_delivery_operation').addClass('valid')
    save_create_delivery('#quick_create_delivery_operation')
}


function save_create_delivery(element) {


    if (!$(element).hasClass('valid') || $(element).hasClass('wait')) {
        return;
    }

    $(element).addClass('wait')
    $(element).find('i').addClass('fa-spinner fa-spin fa-cloud').removeClass('fa-plus');
    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
    var fields_data = {};

    fields_data['Supplier Delivery Public ID'] = $('#delivery_number').val()
    fields_data['items'] = {}
    $('.delivery_quantity').each(function () {
        if ($(this).attr('on') == 1) {
            fields_data['items'][$(this).attr('key')] = $(this).find('input').val()
        }
    });

    var request = '/ar_edit.php?tipo=new_object&object=SupplierDelivery&parent=' + object_data.object + '&parent_key=' + object_data.key + '&fields_data=' + JSON.stringify(fields_data)
//console.log(request)
//return;

    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'new_object')
    form_data.append("parent", object_data.object)
    form_data.append("parent_key", object_data.key)
    form_data.append("object", 'SupplierDelivery')
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


        if (data.state == 200) {
            change_view(object_data.order_parent.toLowerCase() + '/' + object_data.order_parent_key + '/delivery/' + data.new_id, {
                tab: 'supplier.delivery.items'
            })
        } else if (data.state == 400) {
            $(element).removeClass('wait')
            $(element).find('i').removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');


            console.log(data)
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)
        console.log(jqXHR.responseText)

    });


}
