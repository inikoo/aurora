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


function save_create_delivery(element) {

    $(element).find('i').addClass('fa-spinner fa-spin fa-cloud').removeClass('fa-plus');

    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

    var fields_data = {};

    fields_data['Supplier Delivery Public ID'] = $('#delivery_number').val()
    fields_data['items'] = {}

    $('.delivery_quantity').each(function() {
        if ($(this).attr('on') == 1) {
            fields_data['items'][$(this).attr('key')] = $(this).find('input').val()
        }
    });

    var request = '/ar_edit.php?tipo=new_object&object=SupplierDelivery&parent=' + object_data.object + '&parent_key=' + object_data.key + '&fields_data=' + JSON.stringify(fields_data)


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


    request.done(function(data) {

        $(element).find('i').removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {
        
        
            $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)

            for (var key in data.metadata.class_html) {
                console.log(key)
                $('.' + key).html(data.metadata.class_html[key])
            }



        } else if (data.state == 400) {
            console.log(data)
        }

    })


    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });



}
