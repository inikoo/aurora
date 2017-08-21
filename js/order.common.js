/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2016 at 11:27:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/






$(document).on('click', '.order_item_percentage_discount', function (evt) {

    $(this).addClass('hide').closest('td').find('.order_item_percentage_discount_form').removeClass('hide')


})



$(document).on('input propertychange', '.order_item_percentage_discount_input', function (evt) {


    icon=$(this).closest('.order_item_percentage_discount_form').find('i')

    icon.addClass('changed')


    percentage=$(this).val().replace("%", "");

    if( ! validate_number(percentage, 0, 100) || percentage=='' ){
        icon.addClass('valid').removeClass('error')

    }else{
        icon.removeClass('valid').addClass('error ')

    }




})



$(document).on('click', '.order_item_percentage_discount_form i', function (evt) {

    var icon=$(this)

    if(icon.hasClass('wait')){
        return
    }

    icon.removeClass('fa-cloud').addClass('wait fa-spinner fa-spin')

    var settings= $(this).closest('.order_item_percentage_discount_form').data('settings')

    var input=$(this).closest('.order_item_percentage_discount_form').find('input')



    value=input.val()

    if(value==''){
        value=0
    }

    var table_metadata = JSON.parse(atob($('#table').data("metadata")))



    var request = '/ar_edit_orders.php?tipo=edit_item_discount&parent=' + table_metadata.parent + '&field=' + settings.field + '&parent_key=' + table_metadata.parent_key + '&item_key=' + settings.item_key + '&value=' + value + '&transaction_key=' + settings.transaction_key

console.log(request)


    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_discount')
    form_data.append("field", settings.field)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("transaction_key", settings.transaction_key)
    form_data.append("value", value)


    var request = $.ajax({

        url: "/ar_edit_orders.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {


        icon.removeClass('fa-spinner fa-spin wait').addClass('fa-cloud')


        if (data.state == 200) {

            console.log(data)
            console.log(table_metadata)






            icon.closest('tr').find('._order_item_net').html(data.transaction_data.to_charge)
            icon.closest('tr').find('._item_discounts').html(data.transaction_data.item_discounts)



                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)



                $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue',data.metadata.shipping)
                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue',data.metadata.charges)

                if (data.metadata.to_pay == 0 || data.metadata.payments == 0) {
                    $('.Order_Payments_Amount').addClass('hide')
                    $('.Order_To_Pay_Amount').addClass('hide')

                } else {
                    $('.Order_Payments_Amount').removeClass('hide')
                    $('.Order_To_Pay_Amount').removeClass('hide')

                }

                if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                    $('.Order_Paid').addClass('hide')
                } else {
                    $('.Order_Paid').removeClass('hide')
                }

                if (data.metadata.to_pay <= 0) {
                    $('.payment_operation').addClass('hide')

                } else {
                    $('.payment_operation').removeClass('hide')
                }


                if (data.metadata.to_pay == 0) {
                    $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                } else {
                    $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                }


                $('#payment_nodes').html(data.metadata.payments_xhtml)


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }






                if (data.metadata.items == 0) {
                    $('.payments').addClass('hide')
                    $('#submit_operation').addClass('hide')
                    $('#send_to_warehouse_operation').addClass('hide')





                }
                else {


                    $('.payments').removeClass('hide')
                    $('#submit_operation').removeClass('hide')
                    $('#send_to_warehouse_operation').removeClass('hide')
                }







        } else if (data.state == 400) {


            sweetAlert(data.msg);
            input.val(input.attr('ovalue'))


        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


})



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

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)




            if (data.value == 'Cancelled') {
                change_view(state.request, {
                    reload_showcase: true
                })
            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.order_operation').addClass('hide')
           // $('.items_operation').addClass('hide')




            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }




            $('.timeline .li').removeClass('complete')


            if(object == 'order'){

                if (data.update_metadata.state_index >= 30) {
                    $('#submitted_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 40) {
                    $('#in_warehouse_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 80) {
                    $('#packed_done_node').addClass('complete')
                }
                if (data.update_metadata.state_index >=90) {
                    $('#approved_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 100) {
                    $('#dispatched_node').addClass('complete')
                }






                $('#delivery_notes').html(data.update_metadata.deliveries_xhtml)

                if(data.update_metadata.number_deliveries==0){
                    $('#delivery_notes').addClass('hide')
                }else{
                    $('#delivery_notes').removeClass('hide')

                }


            }

            else if (object == 'delivery_note') {


                $('#order_node').addClass('complete')



                if (data.update_metadata.state_index >= 20) {
                    $('#start_picking_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 30) {
                    $('#picked_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 70) {
                    $('#packed_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 80) {
                    $('#packed_done_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 90) {
                    $('#dispatch_approved_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 100) {
                    $('#dispatched_node').addClass('complete')
                }


                if(data.update_metadata.state_index > 10){
                    $('.delivery_note_handling_fields').removeClass('hide')
                }else{
                    $('.delivery_note_handling_fields').addClass('hide')

                }



                $('#Delivery_Note_State_Index').val(data.update_metadata.state_index)







            }else if (object == 'supplierdelivery') {

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


            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}




function picked_offline_items_qty_change(element) {


    var input = $(element).closest('span').find('input')
    var icon = $(element)

    if ($(element).hasClass('fa-plus')) {


        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }






    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }


        var _icon = 'fa-minus'

    } else {
        qty = parseFloat(input.val())

        var _icon = 'fa-cloud'

    }

    if(qty>input.attr('max')){

        qty=input.attr('max')
    }


    input.val(qty).addClass('discreet')


    console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;


    var settings = $(element).closest('span').data('settings')


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))








}


function save_item_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin')

    var input = $(element).closest('span').find('input')
    var icon = $(element)

    if ($(element).hasClass('fa-plus')) {


        var _icon = 'fa-plus'

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

        var _icon = 'fa-minus'

    } else {
        qty = parseFloat(input.val())

        var _icon = 'fa-cloud'

    }

    console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;


    var settings = $(element).closest('span').data('settings')


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))


    if (settings.field == 'Picked' && $('#dn_data').attr('picker_key') == '') {
        $(element).removeClass('fa-spinner fa-spin')
        sweetAlert($('#dn_data').attr('no_picker_msg'));
        return;
    }

    if (settings.field == 'Packed' && $('#dn_data').attr('packer_key') == '') {
        $(element).removeClass('fa-spinner fa-spin')
        sweetAlert($('#dn_data').attr('no_packer_msg'));
        return;
    }

    var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&field=' + settings.field + '&parent_key=' + table_metadata.parent_key + '&item_key=' + settings.item_key + '&qty=' + qty + '&transaction_key=' + settings.transaction_key



    if (settings.item_historic_key != undefined) {
        request = request + '&item_historic_key=' + settings.item_historic_key
    }

    if (settings.field == 'Picked') {
        request = request + '&picker_key=' + $('#dn_data').attr('picker_key')
    }

    if (settings.field == 'Packed') {
        request = request + '&packer_key=' + $('#dn_data').attr('packer_key')
    }



    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", settings.field)
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", settings.item_key)
    if (settings.item_historic_key != undefined) {
        form_data.append("item_historic_key", settings.item_historic_key)
    }

    if (settings.field == 'Picked') {
        form_data.append("picker_key", $('#dn_data').attr('picker_key'))
    }
    if (settings.field == 'Packed') {
        form_data.append("packer_key", $('#dn_data').attr('packer_key'))
    }


    form_data.append("transaction_key", settings.transaction_key)


    form_data.append("qty", qty)


    var request = $.ajax({

        url: "/ar_edit_orders.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {


        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus')


        if (data.state == 200) {

           // console.log(data)
           // console.log(table_metadata)


            input.val(data.transaction_data.qty).removeClass('discreet')
            input.attr('ovalue', data.transaction_data.qty)
            if (table_metadata.parent == 'order') {



                $('.order_operation').addClass('hide')
                //$('.items_operation').addClass('hide')


                for (var key in data.metadata.operations) {
                    $('#' + data.metadata.operations[key]).removeClass('hide')
                }


                $(element).closest('tr').find('._order_item_net').html(data.transaction_data.to_charge)



                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)



                $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue',data.metadata.shipping)
                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue',data.metadata.charges)

                if (data.metadata.to_pay == 0 || data.metadata.payments == 0) {
                    $('.Order_Payments_Amount').addClass('hide')
                    $('.Order_To_Pay_Amount').addClass('hide')

                } else {
                    $('.Order_Payments_Amount').removeClass('hide')
                    $('.Order_To_Pay_Amount').removeClass('hide')

                }

                if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                    $('.Order_Paid').addClass('hide')
                } else {
                    $('.Order_Paid').removeClass('hide')
                }

                if (data.metadata.to_pay <= 0) {
                    $('.payment_operation').addClass('hide')

                } else {
                    $('.payment_operation').removeClass('hide')
                }


                if (data.metadata.to_pay == 0) {
                    $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                } else {
                    $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                }


                $('#payment_nodes').html(data.metadata.payments_xhtml)


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }






                if (data.metadata.items == 0) {
                    $('.payments').addClass('hide')





                }
                else {


                    $('.payments').removeClass('hide')
                        }


            } else if (table_metadata.parent == 'delivery_note') {

                console.log(data.metadata.location_components)
                console.log(data.metadata.picked_quantity_components)
                console.log(data.metadata.pending)


                $(element).closest('tr').find('.location_components').html(data.metadata.location_components)
                $(element).closest('tr').find('.picked_quantity_components').html(data.metadata.picked_quantity_components)


                if (data.metadata.state_index >= 30) {
                    $('#picked_node').addClass('complete')
                }


            } else {


                $(element).closest('tr').find('.part_sko_item').attr('_checked', data.transaction_data.qty)


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
                } else {
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
            input.val(input.attr('ovalue'))


        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function save_item_out_of_stock_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin');


    var input = $('#set_out_of_stock_items_dialog').find('input')

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

    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", 'Out_of_stock')
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", $('#set_out_of_stock_items_dialog').attr('item_key'))
    form_data.append("transaction_key", $('#set_out_of_stock_items_dialog').attr('transaction_key'))


    form_data.append("qty", qty)

    var request = $.ajax({

        url: "/ar_edit_orders.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');

        if (data.state == 200) {

            console.log(data)

            input.val(data.transaction_data.qty).removeClass('discreet')


            $(element).closest('tr').find('.location_components').html(data.metadata.location_components)
            $(element).closest('tr').find('.picked_quantity_components').html(data.metadata.picked_quantity_components)


            if (data.metadata.state_index >= 30) {
                $('#picked_node').addClass('complete')
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


