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



                if (data.metadata.to_pay == 0) {
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

         console.log($('#' + dialog_name + '_operations').parents('#back_operations').length)



        if ($('#' + dialog_name + '_operations').parent('div#back_operations').length) {

            var offset=  $('#' + dialog_name + '_dialog').closest('.block').position();


            $('#' + dialog_name + '_dialog').removeClass('hide').offset({ left:offset.left })



        } else {


           // var offset = $('#columns_frequency .fa').position();

          var offset=  $('#' + dialog_name + '_dialog').closest('.block').position();


            $('#' + dialog_name + '_dialog').removeClass('hide').offset({ left:offset.left+30 })
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

    //console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
        var settings = $(this).data("settings")



        if (settings.type == 'datetime') {
            metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

        }


    });

    console.log(field)

    if(field=='Replacement State'){
        metadata['Delivery Note Key']=data.replacement_key;
    }


    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)



    //console.log(request)
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



                $('#invoices').html(data.update_metadata.invoices_xhtml)

                if(data.update_metadata.number_invoices==0){
                    $('#invoices').addClass('hide')
                }else{
                    $('#invoices').removeClass('hide')

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







            }
            else if (object == 'supplierdelivery') {

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

                        $('#all_available_items,#new_item').removeClass('hide')

                        change_tab('supplier.order.items_in_process')
                    } else if (data.value == 'Submitted') {
                        $('#all_available_items,#new_item').addClass('hide')


                       // if (state.tab == 'supplier.order.all_supplier_parts') {
                            change_tab('supplier.order.items')





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

                        grid.columns.findWhere({ name: 'ordered'}).set("renderable", false)

                        grid.columns.findWhere({ name: 'quantity'}).set("renderable", true)

                    } else if (data.value == 'Submitted') {

                        grid.columns.findWhere({ name: 'ordered'}).set("renderable", true)

                        grid.columns.findWhere({ name: 'quantity'}).set("renderable", false)


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

    //console.log(_icon)

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
        request+='&item_historic_key='+settings.item_historic_key
    }


    if (settings.item_historic_key != undefined) {
        request = request + '&item_historic_key=' + settings.item_historic_key
    }

    if (settings.field == 'Picked') {
        request = request + '&picker_key=' + $('#dn_data').attr('picker_key')
    }

    if (settings.field == 'Packed') {
        request = request + '&packer_key=' + $('#dn_data').attr('packer_key')
    }
//console.log(request)



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
    form_data.append("tab", state.tab)


    form_data.append("qty", qty)


    var request = $.ajax({

        url: "/ar_edit_orders.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {


        $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus')
        $(element).closest('span').find('i.minus').removeClass('fa-spinner fa-spin invisible').addClass('fa-minus')


        if (data.state == 200) {

            //console.log(data)
            //console.log(table_metadata)


            input.val(data.transaction_data.qty).removeClass('discreet')
            input.attr('ovalue', data.transaction_data.qty)
            if (table_metadata.parent == 'order') {



                $('.order_operation').addClass('hide')
                for (var key in data.metadata.operations) {
                    $('#' + data.metadata.operations[key]).removeClass('hide')
                }


                $(element).closest('tr').find('._order_item_net').html(data.transaction_data.to_charge)



                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)



                $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue',data.metadata.shipping)
                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue',data.metadata.charges)




                if (data.metadata.to_pay == 0) {
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



                for (var key in data.discounts_data) {
                    console.log(key+': '+data.discounts_data[key])
                    $('#transaction_deal_info_'+key).html(data.discounts_data[key]['deal_info'])
                    $('#transaction_discounts_'+key).html(data.discounts_data[key]['discounts'])
                    $('#transaction_item_net_'+key).html(data.discounts_data[key]['item_net'])

                    //$('.' + key).html(data.metadata.class_html[key])
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

console.log(data)

                $('.order_operation').addClass('hide')
                for (var key in data.metadata.operations) {


                    $('#' + data.metadata.operations[key]).removeClass('hide')
                    console.log('#' + data.metadata.operations[key])

                }


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




function get_orders_table( order_flow, metadata) {

    $('.order_flow').removeClass('selected')
    $('.blue').removeClass('blue')

 //   console.log(order_flow)

    switch (order_flow){


        case 'website':
            $('#order_flow_website').addClass('selected')
            widget='orders.website.wget'
            $('.Orders_In_Basket_Number').addClass('blue')
            $('.Orders_In_Basket_Amount').addClass('blue')

            break;
        case 'website_purges':
            $('#order_flow_website').addClass('selected')
            widget='orders.website.purges.wget'
            $('.Orders_In_Basket_Number').addClass('blue')
            $('.Orders_In_Basket_Amount').addClass('blue')

            break;
        case 'website_mailshots':
            $('#order_flow_website').addClass('selected')
            widget='orders.website.mailshots.wget'
            $('.Orders_In_Basket_Number').addClass('blue')
            $('.Orders_In_Basket_Amount').addClass('blue')

            break;
        case 'submitted_not_paid':
            $('#order_flow_submitted').addClass('selected').find('')

            widget='orders.in_process.not_paid.wget'

            $('.Orders_In_Process_Not_Paid_Number').addClass('blue')
            $('.Orders_In_Process_Not_Paid_Amount').addClass('blue')

            break;
        case 'submitted':
            $('#order_flow_submitted').addClass('selected')
            widget='orders.in_process.paid.wget'
            $('.Orders_In_Process_Paid_Number').addClass('blue')
            $('.Orders_In_Process_Paid_Amount').addClass('blue')

            break;
        case 'in_warehouse':
            $('#order_flow_in_warehouse').addClass('selected')
            widget='orders.in_warehouse_no_alerts.wget'
            $('.Orders_In_Warehouse_No_Alerts_Number').addClass('blue')
            $('.Orders_In_Warehouse_No_Alerts_Amount').addClass('blue')

            break;
        case 'in_warehouse_with_alerts':
            $('#order_flow_in_warehouse').addClass('selected')
            widget='orders.in_warehouse_with_alerts.wget'
            $('.Orders_In_Warehouse_With_Alerts_Number').addClass('blue')
            $('.Orders_In_Warehouse_With_Alerts_Amount').addClass('blue')

            break;
        case 'packed_done':
            $('#order_flow_packed').addClass('selected')
            widget='orders.packed_done.wget'
            $('.Orders_Packed_Number').addClass('blue')
            $('.Orders_Packed_Amount').addClass('blue')

            break;
        case 'approved':
            $('#order_flow_packed').addClass('selected')
            widget='orders.approved.wget'
            $('.Orders_Dispatch_Approved_Number').addClass('blue')
            $('.Orders_Dispatch_Approved_Amount').addClass('blue')

            break;

        case 'dispatched_today':
            $('#order_flow_dispatched').addClass('selected')
            widget='orders.dispatched_today.wget'
            $('.Orders_Dispatched_Today_Number').addClass('blue')
            $('.Orders_Dispatched_Today_Amount').addClass('blue')

            break;

        default:
            order_flow='submitted'

            $('#order_flow_submitted').addClass('selected')
            widget='orders.in_process.paid.wget'
            $('.Orders_In_Process_Paid_Number').addClass('blue')
            $('.Orders_In_Process_Paid_Amount').addClass('blue')


    }

    if(current_order_flow==''){
        current_order_flow=order_flow
    }


    if(current_order_flow!=order_flow) {
        new_url = window.location.pathname.replace(/dashboard.*$/, '') + 'dashboard/' + order_flow


        console.log(new_url)


        window.top.history.pushState({
            request: new_url}, null, new_url)
    }



    // history.pushState(null, null, new_url)

    var request = "/ar_views.php?tipo=widget_details&widget=" + widget + '&metadata=' + JSON.stringify(metadata)

    //console.log(request)

    $.getJSON(request, function (data) {


        $('#widget_details').html(data.widget_details).removeClass('hide');

    });

}





function select_payment_account(element) {

    var settings = $(element).data('settings')


    $('.payment_button').removeClass('selected')
    $(element).addClass('selected')


    console.log(settings)
    $('#new_payment_payment_account_key').val(settings.payment_account_key).data('settings',settings)
    $('#new_payment_payment_method').val(settings.payment_method)

    $("#add_payment :input").attr("disabled", false);
    $("#add_payment .payment_fields").removeClass("just_hinted");


    if(settings.block=='Accounts'){
        $('#new_payment_reference').closest('tr').addClass('hide')
    }else{
        $('#new_payment_reference').closest('tr').removeClass('hide')
    }


    $('.new_payment_payment_account_button').addClass('super_discreet')
    $(element).removeClass('super_discreet')


    if ($('#new_payment_amount').val() == '') {
        $('#new_payment_amount').focus()
    } else {
        $('#new_payment_reference').focus()
    }

}



function try_to_pay(element) {

    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))


    console.log($(element).attr('amount'))

    if ($(element).attr('amount') > 0  ||  object_data.order_type=='Refund'   ) {




        if ($('#add_payment').hasClass('hide')) {
            show_add_payment_to_order()


        }
        if(!$('#new_payment_amount').is(':disabled')){
            $('#new_payment_amount').val($(element).attr('amount'))

            validate_new_payment();
        }




    } else if ($(element).attr('amount') < 0) {


        if ($('#payment_refund_amount').is(':visible')) {

            var amount=Math.abs($(element).attr('amount'))



            var max_amount= $('#payment_refund_dialog').data('settings').amount

            if(max_amount<amount){
                amount=max_amount
            }



            $('#payment_refund_amount').val(amount)
            validate_refund_form()
        }

        //payment_refund_amount

    }


}


function show_add_payment_to_order() {


    if ($('#add_payment').hasClass('hide')) {

        var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
        if(object_data.object=='invoice') {
            change_tab('invoice.payments', {'add_payment': 1})
        }else{
            change_tab('order.payments', {'add_payment': 1})

        }


        $('#tabs').addClass('hide')


        $("#add_payment :input").attr("disabled", true);
        $(".payment_fields").addClass("just_hinted");
        $('#new_payment_reference').closest('tr').removeClass('hide')


        $('#add_payment').removeClass('hide')

        $('#new_payment_payment_account_key').val('')
        $('#new_payment_payment_method').val('')

        $('.new_payment_payment_account_button').removeClass('super_discreet')


        // $('#delivery_number').val('').focus()

    }

}


function close_add_payment_to_order() {

    $('#add_payment').addClass('hide')

    //change_tab('order.items')
    $('#tabs').removeClass('hide')


}



$(document).on('input propertychange', '.new_payment_field', function (evt) {

    validate_new_payment();
})


function validate_new_payment() {

     console.log($('#new_payment_reference').val() != '')
     console.log(!validate_number($('#new_payment_amount').val(), 0, 999999999))
    console.log($('#new_payment_payment_account_key').val() > 0)


    var settings=$('#new_payment_payment_account_key').data('settings')



    if(settings.block=='Accounts'){
        var valid_reference=true;


    }else{
        var valid_reference=($('#new_payment_reference').val()==''?false:true);
    }


    if(settings.max_amount!=''){

        console.log(settings.max_amount)
        console.log($('#new_payment_amount').val())

        var valid_max_amount=(parseFloat(settings.max_amount)<parseFloat($('#new_payment_amount').val())?false:true)

    }else{
        var valid_max_amount=true;

    }


    if( !validate_number($('#new_payment_amount').val(), 0, 999999999) && $('#new_payment_payment_account_key').val() > 0){
        var valid_amount=true;
    }else{
        var valid_amount=false;

    }

    console.log(valid_reference)
    console.log(valid_max_amount)

    console.log(valid_amount)

    if (valid_reference && valid_max_amount &&  valid_amount) {
        console.log('xx')
        $('#save_new_payment').addClass('valid changed')
    } else {
        $('#save_new_payment').removeClass('valid changed')
    }


}

function save_new_payment() {



    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))


    if ($('#save_new_payment').hasClass('wait')) {
        return;
    }
    $('#save_new_payment').addClass('wait')

    $('#save_new_payment i').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'new_payment')
    ajaxData.append("parent", object_data.object)

    ajaxData.append("parent_key", object_data.key)
    ajaxData.append("payment_account_key", $('#new_payment_payment_account_key').val())
    ajaxData.append("amount", $('#new_payment_amount').val())
    ajaxData.append("payment_method", $('#new_payment_payment_method').val())

    ajaxData.append("reference", $('#new_payment_reference').val())


    console.log("/ar_edit_orders.php?tipo=new_payment&parent="+object_data.object+"&parent_key="+ object_data.key+"&payment_account_key="+$('#new_payment_payment_account_key').val()+ "&amount="+$('#new_payment_amount').val()+'&payment_method='+$('#new_payment_payment_method').val()+'&reference='+$('#new_payment_reference').val()
    )

    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {




            $('#save_new_payment').removeClass('wait')
            $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


            console.log(data)

            if (data.state == '200') {



                if(object_data.object=='invoice') {

                    $('#tabs').removeClass('hide')

                    change_view(state.request, {'reload_showcase': 1})
                }else {

                    $('#new_payment_amount').val('')
                    $('#new_payment_reference').val('')

                    validate_new_payment()

                    $('.Total_Amount').attr('amount', data.metadata.to_pay)
                    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                    $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue', data.metadata.shipping)
                    $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                    if (data.metadata.to_pay == 0) {
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


                    if (state.tab == 'order.payments') {
                        rows.fetch({
                            reset: true
                        });
                    }

                    close_add_payment_to_order()
                }

            } else if (data.state == '400') {
                $('#tabs').removeClass('hide')

                swal("Error!", data.msg, "error")
            }


        }, error: function () {
            $('#save_new_payment').removeClass('wait')
            $('#save_new_payment i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        }
    });


}

