/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2016 at 11:27:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function toggle_order_operation_dialog(dialog_name) {

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

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
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


    request.done(function (data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)

            console.log(data)


            if (data.value == 'Cancelled') {
                change_view(state.request, {
                    reload_showcase: true
                })
            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.order_operation').addClass('hide')
            $('.items_operation').addClass('hide')

            for (var key in data.update_metadata.operations) {
                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }


            $('.timeline .li').removeClass('complete')


            if (object == 'supplierdelivery') {

                $('#inputted_node').addClass('complete')
                $('#purchase_order_node').addClass('complete')

                if (data.update_metadata.state_index >= 30) {
                    $('#dispatched_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 40) {
                    $('#received_node').addClass('complete')
                }

                if (data.update_metadata.state_index >= 50) {
                    $('#checked_node').addClass('complete')
                }
                if (data.update_metadata.state_index == 100) {
                    $('#placed_node').addClass('complete')

                    if (state.tab == 'supplier.delivery.items') {
                        change_tab('supplier.delivery.items')

                    }


                }


                if ((dialog_name == 'undo_received' || dialog_name == 'received') && state.tab == 'supplier.delivery.items') {


                    change_tab('supplier.delivery.items')

                }


            }
            else if (object == 'purchase_order') {
                if (data.update_metadata.state_index >= 30) {
                    $('#submitted_node').addClass('complete')
                }

                if (field == 'Purchase Order State') {

                    console.log(state.tab)


                    if (data.value == 'InProcess') {
                        $('#create_delivery').addClass('hide')
                    } else if (data.value == 'Submitted') {


                        if (state.tab == 'supplier.order.all_supplier_parts') {
                            change_tab('supplier.order.items')

                        }

                        if (data.update_metadata.pending_items_in_delivery > 0) {

                            if (object_data.skip_inputting == 'No') {
                                $('#create_delivery').removeClass('hide')

                            } else {

                                $('#quick_create_delivery_operations').removeClass('hide')


                            }

                        } else {
                            $('#create_delivery').addClass('hide')
                            $('#quick_create_delivery_operations').addClass('hide')

                        }

                    }

                }

                if (state.tab == 'supplier.order.items') {
                    if (data.value == 'InProcess') {

                        grid.columns.findWhere({
                            name: 'ordered'
                        }).set("renderable", false)

                        grid.columns.findWhere({
                            name: 'quantity'
                        }).set("renderable", true)

                    } else if (data.value == 'Submitted') {

                        grid.columns.findWhere({
                            name: 'ordered'
                        }).set("renderable", true)

                        grid.columns.findWhere({
                            name: 'quantity'
                        }).set("renderable", false)


                    }

                } else if (state.tab == 'supplier.order.history' || state.tab == 'supplier.delivery.history') {
                    rows.fetch({
                        reset: true
                    });
                }


            }


        } else if (data.state == 400) {
            console.log(data)

        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function save_item_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin');


    var input = $(element).closest('span').find('input')

    if ($(element).hasClass('fa-plus')) {

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }

        input.val(qty).addClass('discreet')

    } else {
        qty = parseFloat(input.val())

    }

    if (qty == '') qty = 0;

    console.log($(element).closest('span').data('settings'))

    var settings = $(element).closest('span').data('settings')


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))




    console.log(table_metadata)

    if(settings.field=='Picked' &&  $('#dn_data').attr('picker_key')=='') {

        sweetAlert($('#dn_data').attr('no_picker_msg'));
        return;
    }

    var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&field=' + settings.field + '&parent_key=' + table_metadata.parent_key + '&item_key=' + settings.item_key  + '&qty=' + qty + '&transaction_key=' + settings.transaction_key

    if(settings.item_historic_key!=undefined){
        request=request+'&item_historic_key=' + settings.item_historic_key
    }

    if(settings.field=='Picked'){
        request=request+'&picker_key=' +  $('#dn_data').attr('picker_key')
    }

    if(settings.field=='Packed'){
        request=request+'&packer_key=' +  $('#dn_data').attr('packer_key')
    }

    console.log(request)


    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", settings.field)
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", settings.item_key)
    if(settings.item_historic_key!=undefined){
        form_data.append("item_historic_key", settings.item_historic_key)
    }

    if(settings.field=='Picked'){
        form_data.append("picker_key", $('#dn_data').attr('picker_key'))
    }
    if(settings.field=='Packed'){
        form_data.append("packer_key", $('#dn_data').attr('packer_key'))
    }


    form_data.append("transaction_key", settings.transaction_key)


    form_data.append("qty", qty)

    var request = $.ajax({

        url: "/ar_edit_orders.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function (data) {

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {

            console.log(data)

            input.val(data.transaction_data.qty).removeClass('discreet')

            if(table_metadata.parent=='delivery_note'){

                console.log(data.metadata.location_components)
                console.log(data.metadata.picked_quantity_components)
                console.log(data.metadata.pending)


                $(element).closest('tr').find('.location_components').html(data.metadata.location_components)
                $(element).closest('tr').find('.picked_quantity_components').html(data.metadata.picked_quantity_components)


                if (data.metadata.state_index >= 30) {
                    $('#picked_node').addClass('complete')
                }



            }else{
                $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)
                $(element).closest('tr').find('.placement').html(data.metadata.placement)
                $(element).closest('.checked_quantity').find('.checked_qty').attr('ovalue', data.transaction_data.qty)

                $('#inputted_node').addClass('complete')
                $('#purchase_order_node').addClass('complete')

                if (data.metadata.state_index >= 30) {
                    $('#dispatched_node').addClass('complete')
                }
                if (data.metadata.state_index >= 40) {
                    $('#received_node').addClass('complete')
                }

                if (data.metadata.state_index >= 50) {
                    $('#checked_node').addClass('complete')
                }
                if (data.metadata.state_index == 100) {
                    $('#placed_node').addClass('complete')
                    if (state.tab == 'supplier.delivery.items') {
                        change_tab('supplier.delivery.items')
                    }
                }else{
                    $('#placed_node').removeClass('complete')
                }
            }







            for (var key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }


            for (var key in data.metadata.hide) {
                $('#' + data.metadata.hide[key]).addClass('hide')
            }
            for (var key in data.metadata.show) {
                $('#' + data.metadata.show[key]).removeClass('hide')
            }


        } else if (data.state == 400) {
            sweetAlert(data.msg);
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}


function save_item_out_of_stock_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin');


    var input =  $('#set_out_of_stock_items_dialog').find('input')

    if ($(element).hasClass('fa-plus')) {

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
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






    var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&field=Out_of_stock&parent_key=' + table_metadata.parent_key + '&item_key=' + $('#set_out_of_stock_items_dialog').attr('item_key') + '&qty=' + qty + '&transaction_key=' + $('#set_out_of_stock_items_dialog').attr('transaction_key')


    console.log(request)

    return;

    if(settings.item_historic_key!=undefined){
        request=request+'&item_historic_key=' + settings.item_historic_key
    }

    if(settings.field=='Picked'){
        request=request+'&picker_key=' +  $('#dn_data').attr('picker_key')
    }

    if(settings.field=='Packed'){
        request=request+'&packer_key=' +  $('#dn_data').attr('packer_key')
    }

    console.log(request)


    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", settings.field)
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", settings.item_key)
    if(settings.item_historic_key!=undefined){
        form_data.append("item_historic_key", settings.item_historic_key)
    }

    if(settings.field=='Picked'){
        form_data.append("picker_key", $('#dn_data').attr('picker_key'))
    }
    if(settings.field=='Packed'){
        form_data.append("packer_key", $('#dn_data').attr('packer_key'))
    }


    form_data.append("transaction_key", settings.transaction_key)


    form_data.append("qty", qty)

    var request = $.ajax({

        url: "/ar_edit_orders.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function (data) {

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {

            console.log(data)

            input.val(data.transaction_data.qty).removeClass('discreet')

            if(table_metadata.parent=='delivery_note'){

                console.log(data.metadata.location_components)
                console.log(data.metadata.picked_quantity_components)
                console.log(data.metadata.pending)


                $(element).closest('tr').find('.location_components').html(data.metadata.location_components)
                $(element).closest('tr').find('.picked_quantity_components').html(data.metadata.picked_quantity_components)


                if (data.metadata.state_index >= 30) {
                    $('#picked_node').addClass('complete')
                }



            }else{
                $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)
                $(element).closest('tr').find('.placement').html(data.metadata.placement)
                $(element).closest('.checked_quantity').find('.checked_qty').attr('ovalue', data.transaction_data.qty)

                $('#inputted_node').addClass('complete')
                $('#purchase_order_node').addClass('complete')

                if (data.metadata.state_index >= 30) {
                    $('#dispatched_node').addClass('complete')
                }
                if (data.metadata.state_index >= 40) {
                    $('#received_node').addClass('complete')
                }

                if (data.metadata.state_index >= 50) {
                    $('#checked_node').addClass('complete')
                }
                if (data.metadata.state_index == 100) {
                    $('#placed_node').addClass('complete')
                    if (state.tab == 'supplier.delivery.items') {
                        change_tab('supplier.delivery.items')
                    }
                }else{
                    $('#placed_node').removeClass('complete')
                }
            }







            for (var key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }


            for (var key in data.metadata.hide) {
                $('#' + data.metadata.hide[key]).addClass('hide')
            }
            for (var key in data.metadata.show) {
                $('#' + data.metadata.show[key]).removeClass('hide')
            }


        } else if (data.state == 400) {
            sweetAlert(data.msg);
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}
