/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2016 at 11:27:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function toggle_order_operation_dialog(dialog_name) {

   // console.log($('#' + dialog_name + '_dialog'))

    if ($('#' + dialog_name + '_dialog').hasClass('hide')) {

        $('.order_operation_dialog').addClass('hide')

       // console.log($('#' + dialog_name + '_operations').parents('#back_operations').length)

        if ($('#' + dialog_name + '_operations').parent('div#back_operations').length) {
            $('#' + dialog_name + '_dialog').removeClass('hide').css({
                'left': -2
            });
        } else {

            $('#' + dialog_name + '_dialog').removeClass('hide').css({
                'right': -2
            });
        }
    } else {
        close_dialog(dialog_name)
    }
}


function close_dialog(dialog_name) {
    $('#' + dialog_name + '_dialog').addClass('hide');
}

function save_order_operation(element) {

    var data = $(element).data("data")

    console.log(data)

    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key



    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
        console.log('#' + dialog_name + '_save_buttons')
        return;
    }

    $('#' + dialog_name + '_save_buttons').removeClass('button');
    $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
    $('#' + dialog_name + '_save_buttons .label').addClass('hide')


    var metadata = {}

console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function() {
        var settings = $(this).data("settings")
        if (settings.type == 'datetime') {
            metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

        }


    });


    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)
    console.log(request)
     //  return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("object", object)
    form_data.append("key", key)
    form_data.append("field", field)
    form_data.append("value", value)
    form_data.append("metadata", JSON.stringify(metadata))

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function(data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')



        if (data.state == 200) {

            close_dialog(dialog_name)

            console.log(data)

            for (var key in data.update_metadata.class_html) {
                console.log(key + ' ' + data.update_metadata.class_html[key])

                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.order_operation').addClass('hide')
            $('.items_operation').addClass('hide')

            for (var key in data.update_metadata.operations) {
                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }




            $('.timeline .li').removeClass('complete')

            if (data.update_metadata.state_index >= 30) {
                $('#submitted_node').addClass('complete')
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


function save_item_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin');


    var input = $(element).closest('span').find('input.order_qty')

    if ($(element).hasClass('fa-plus')) {

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() ==0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }

        input.val(qty).addClass('discreet')

    } else {
        qty = parseFloat(input.val())

    }

    if (qty == '') qty = 0;


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))

    var request = '/ar_edit.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&item_key=' + $(element).closest('span').attr('item_key') + '&item_historic_key=' + $(element).closest('span').attr('item_historic_key') + '&qty=' + qty
    console.log(request)
    // return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", $(element).closest('span').attr('item_key'))
    form_data.append("item_historic_key", $(element).closest('span').attr('item_historic_key'))
    form_data.append("qty", qty)

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function(data) {

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {

            input.val(data.transaction_data.qty).removeClass('discreet')

            //$('#subtotals_potf_' + data.transaction_data.transaction_key).html(data.transaction_data.subtotals)
            $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)

            console.log('#subtotals_potf_' + data.transaction_data.transaction_key)
            console.log(data.transaction_data.subtotals)

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

            change_tab('supplier.order.items',{'create_delivery':1})
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


function save_create_delivery() {

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


    console.log(request)
    return;
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

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {

            input.val(data.transaction_data.qty).removeClass('discreet')

            //$('#subtotals_potf_' + data.transaction_data.transaction_key).html(data.transaction_data.subtotals)
            $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)

            console.log('#subtotals_potf_' + data.transaction_data.transaction_key)
            console.log(data.transaction_data.subtotals)

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
