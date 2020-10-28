/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2016 at 11:27:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/



$(document).on('input propertychange', '.order_qty', function () {

    if ($(this).val() == $(this).attr('ovalue')) {
        $(this).closest('span').find('i.plus').removeClass('fa-cloud fa-exclamation-circle error').addClass('fa-plus')
        $(this).closest('span').find('i.minus').removeClass('invisible')

    } else {

        var labels=$('body').data('labels');



        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-exclamation-circle error').addClass('fa-cloud').prop('title',labels.save)
            $(this).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title',labels.undo)

            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-cloud').addClass('fa-exclamation-circle error').prop('title',labels.invalid_val)
            $(this).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title',labels.undo)


        }
    }
});



$(document).on('click', '.order_item_percentage_discount', function () {

    $(this).addClass('hide').closest('td').find('.order_item_percentage_discount_form').removeClass('hide')


})



$(document).on('input propertychange', '.order_item_percentage_discount_input', function () {


    icon=$(this).closest('.order_item_percentage_discount_form').find('i')

    icon.addClass('changed')


    percentage=$(this).val().replace("%", "");

    if( ! validate_number(percentage, 0, 100) || percentage=='' ){
        icon.addClass('valid').removeClass('error')

    }else{
        icon.removeClass('valid').addClass('error ')

    }




})



$(document).on('click', '.order_item_percentage_discount_form i', function () {

    var icon=$(this)

    if(icon.hasClass('wait') || !(icon.hasClass('valid')    )  ){
        return
    }

    icon.removeClass('fa-cloud').addClass('wait fa-spinner fa-spin')

    var settings= $(this).closest('.order_item_percentage_discount_form').data('settings')

    var input=$(this).closest('.order_item_percentage_discount_form').find('input')



    value=input.val()

    if(value==''){
        value=0
    }

    var table_metadata = $('#table').data("metadata")



   // var request = '/ar_edit_orders.php?tipo=edit_item_discount&parent=' + table_metadata.parent + '&field=' + settings.field + '&parent_key=' + table_metadata.parent_key + '&item_key=' + settings.item_key + '&value=' + value + '&transaction_key=' + settings.transaction_key

    //console.log(request)


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
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
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
    var table_metadata = $('#table').data("metadata")
    var object_data = $('#object_showcase div.order').data("object")
    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key


    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
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


    if(field=='Replacement State'){
        metadata['Delivery Note Key']=data.replacement_key;
    }

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
            for (var key in data.update_metadata.hide) {
                $('.' + data.update_metadata.hide[key]).addClass('hide')
            }
            for (var key in data.update_metadata.show) {
                $('.' + data.update_metadata.show[key]).removeClass('hide')
            }
            $('.order_operation').addClass('hide')
            for (var key in data.update_metadata.operations) {
                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }

            $('.timeline .li').removeClass('complete')


            if(object == 'order'){


                if (data.update_metadata.state_index == 10) {
                    $('#tab_order\\.details').addClass('hide')
                    $('#tab_order\\.delivery_notes').addClass('hide')
                    $('#tab_order\\.invoices').addClass('hide')
                    $('#tab_order\\.sent_emails').addClass('hide')


                    if(
                        state.tab=='order.details' ||
                        state.tab=='order.delivery_notes' ||
                        state.tab=='order.invoices' ||
                        state.tab=='order.sent_emails'
                    ){
                        change_tab('order.items')
                    }


                }else{
                    $('#tab_order\\.details').removeClass('hide').addClass('icon_only right')
                    $('#tab_order\\.delivery_notes').removeClass('hide').addClass('icon_only right')
                    $('#tab_order\\.invoices').removeClass('hide').addClass('icon_only right')
                    $('#tab_order\\.sent_emails').removeClass('hide').addClass('icon_only right')
                }


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

                $('#order_dispatched_node').addClass('complete')
                $('#order_node').addClass('complete')


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



                if(table_metadata.type=='return'){
                    if(dialog_name == 'received'){
                        change_tab('return.items')
                        $('#received_operations')
                    }

                }else{
                    if (data.update_metadata.state_index >= 100) {
                        $('#placed_node').addClass('complete')



                        if (state.tab == 'supplier.delivery.items') {
                            change_tab('supplier.delivery.items')

                        }


                    }


                    if( dialog_name=='undo_costing'){
                        change_tab('supplier.delivery.costing')

                    }
                    else if ((dialog_name == 'undo_received' || dialog_name == 'received') && state.tab == 'supplier.delivery.items') {


                        change_tab('supplier.delivery.items')

                    }
                }






            }
            else if (object == 'purchase_order') {
                console.log(object_data.type)

                if(object_data.type=='Production'){


                    post_production_job_order_state_change(data)


                }
            else{
                    if (data.update_metadata.state_index >= 30) {
                        $('#submitted_node').addClass('complete')
                    }
                    if (data.update_metadata.state_index >= 40) {
                        $('#confirm_node').addClass('complete')
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



                        }else if (data.value == 'Confirmed') {
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

                    if (state.tab == 'supplier.order.history' || state.tab == 'supplier.delivery.history') {
                        rows.fetch({
                            reset: true
                        });
                    }
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



function save_item_qty_change(element) {

    if ($(element).hasClass('fa-exclamation-circle')) {
        return;
    }

    $(element).addClass('fa-spinner fa-spin')

    var input = $(element).closest('span').find('input')

    var settings = $(element).closest('span').data('settings')

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

    }else if ($(element).hasClass('fa-undo')) {


            qty = input.attr('ovalue')



        var _icon = 'fa-minus'

    } else {

        if (isNaN(parseFloat(input.val())) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val())
        }

        var _icon = 'fa-cloud'

    }

    //console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;



    //console.log(settings)

    var table_metadata = $('#table').data("metadata")




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

    if (settings.field == 'Supplier Delivery Checked Units' ) {
        qty=qty*settings.sko_factor
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
    console.log(request)
    //return;


    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", settings.field)
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    if(settings.item_key!= undefined){
        form_data.append("item_key", settings.item_key)

    }
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

        var labels=$('body').data('labels');
        $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin fa-cloud error fa-exclamation-circle').addClass('fa-plus').prop('title',labels.add)
        $(element).closest('span').find('i.minus').removeClass('fa-spinner fa-spin invisible fa-undo very_discreet').addClass('fa-minus').prop('title',labels.remove)


        if (data.state == 200) {

            console.log(data)
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
                    $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
                } else {
                    $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
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
                    $('.' + data.metadata.hide[key]).addClass('hide')
                }

                for (var key in data.metadata.show) {
                    $('.' + data.metadata.show[key]).removeClass('hide')
                }
                for (var key in data.metadata.add_class) {

                    $('.' + key).addClass( data.metadata.add_class[key])
                }
                for (var key in data.metadata.remove_class) {
                    $('.' + key).removeClass( data.metadata.remove_class[key])
                }

                for (var key in data.metadata.deleted_otfs) {
                    $('#transaction_item_net_' + data.metadata.deleted_otfs[key]).closest('tr').remove()
                }
                if(data.metadata.new_otfs.length>0){
                    rows.fetch({
                        reset: true
                    });
                }


                for (var key in data.discounts_data) {
                    console.log(key+': '+data.discounts_data[key])
                    $('#transaction_deal_info_'+key).html(data.discounts_data[key]['deal_info'])
                    $('#transaction_discounts_'+key).html(data.discounts_data[key]['discounts'])
                    $('#transaction_item_net_'+key).html(data.discounts_data[key]['item_net'])

                    //$('.' + key).html(data.metadata.class_html[key])
                }

                /*
                if (data.metadata.items == 0) {
                    $('.payments').addClass('hide')
                }
                else {
                    $('.payments').removeClass('hide')
                }
                */




            }
            else if (table_metadata.parent == 'delivery_note') {

                //console.log(data.metadata.location_components)
                //console.log(data.metadata.picked_quantity_components)
                //console.log(data.metadata.pending)


                $(element).closest('tr').find('.location_components').html(data.metadata.location_components)
                $(element).closest('tr').find('.picked_quantity_components').html(data.metadata.picked_quantity_components)


                if (data.metadata.state_index >= 30) {
                    $('#picked_node').addClass('complete')
                }


            }
            else if (table_metadata.parent == 'purchase_order'){




                input.closest('tr').find('.order_units_qty').val(data.transaction_data.qty_units)
                input.closest('tr').find('.order_skos_qty').val(data.transaction_data.qty_skos)
                input.closest('tr').find('.order_cartons_qty').val(data.transaction_data.qty_cartons)
                input.closest('tr').find('input.order_qty').removeClass('error').addClass(data.transaction_data.input_class)


                $('.order_operation').addClass('hide')
                for (var key in data.metadata.operations) {


                    $('#' + data.metadata.operations[key]).removeClass('hide')
                    //console.log('#' + data.metadata.operations[key])

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
            else if (table_metadata.parent == 'supplierdelivery' || table_metadata.parent=='supplier_delivery'){

                //console.log(data)

                $('.order_operation').addClass('hide')
                for (var key in data.metadata.operations) {


                    $('#' + data.metadata.operations[key]).removeClass('hide')
                    //console.log('#' + data.metadata.operations[key])

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


                if(table_metadata.type=='agent_delivery' ||  table_metadata.type=='delivery'){
                    if( data.metadata.checked_items!=undefined &&  data.metadata.checked_items>0 ){



                        $('.Mismatched_Items').removeClass('hide')
                        $("div[id='tab_supplier.delivery.items_mismatch']").removeClass('hide')

                    }else{
                        $("div[id='tab_supplier.delivery.items_mismatch']").addClass('hide')
                        $('.Mismatched_Items').addClass('hide')


                    }
                }else if(table_metadata.type=='return'){

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

/*
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


    var table_metadata = $('#table').data("metadata")


    var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&field=Out_of_stock&parent_key=' + table_metadata.parent_key + '&item_key=' + $('#set_out_of_stock_items_dialog').attr('item_key') + '&qty=' + qty + '&transaction_key=' + $('#set_out_of_stock_items_dialog').attr('transaction_key')


    //console.log(request)

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

            //console.log(data)

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
*/



function get_orders_table( order_flow, metadata, force) {

    $('.order_flow').removeClass('selected')
    $('.blue').removeClass('blue')



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
        case 'packed':
            $('#order_flow_packed').addClass('selected')
            widget='orders.packed.wget'
            $('.Orders_Packed_Number').addClass('blue')
            $('.Orders_Packed_Amount').addClass('blue')

            break;
        case 'packed_done':
            $('#order_flow_packed').addClass('selected')
            widget='orders.packed_done.wget'
            $('.Orders_Packed_Done_Number').addClass('blue')
            $('.Orders_Packed_Done_Amount').addClass('blue')

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

    if($('#order_flow_website').data('current_order_flow')!=order_flow  || force=='Yes' ){

        $('#order_flow_website').data('current_order_flow',order_flow)

            var new_url = window.location.pathname.replace(/dashboard.*$/, '') + 'dashboard/' + order_flow
            window.top.history.pushState({
                request: new_url}, null, new_url)
            var request = "/ar_views.php?tipo=widget_details&widget=" + widget + '&metadata=' + JSON.stringify(metadata)
            $.getJSON(request, function (data) {
                $('#widget_details').html(data.widget_details).removeClass('hide');
            });

    }




}





function select_payment_account(element) {

    var settings = $(element).data('settings')


    $('.payment_button').removeClass('selected')
    $(element).addClass('selected')


    //console.log(settings)
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

    var object_data = $('#object_showcase div.order').data("object")


    console.log($(element).attr('amount'))


    console.log(object_data.order_type)


    if ($(element).attr('amount') > 0  ||  object_data.order_type=='Refund'   ) {




        if ($('#add_payment').hasClass('hide')) {
            show_add_payment_to_order()


        }
        if(!$('#new_payment_amount').is(':disabled')){
            $('#new_payment_amount').val($(element).attr('amount'))

            validate_new_payment();
        }




    }else if ($(element).attr('amount') < 0  &&  object_data.order_type=='Order'   ) {

        return;

        if ($('#add_credit').hasClass('hide')) {
            console.log('caca')
            show_create_credit(element)


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
        }else{



       }

        //payment_refund_amount

    }


}


function show_create_credit(element) {


    if ($('#add_credit').hasClass('hide')) {

        var object_data = $('#object_showcase div.order').data("object")
        if(object_data.object=='invoice') {
            change_tab('invoice.payments', {'add_payment': 1})
        }else{
            change_tab('order.payments', {'add_payment': 1})

        }



        $('#tabs').addClass('hide')


        $('#new_credit_amount').val($(element).attr('absolute_amount'))

        $('#add_credit').removeClass('hide')


        // $('#delivery_number').val('').focus()

    }

}


function open_add_payment_to_order(element) {


    if($(element).hasClass('fa-lock')){

        var _labels = $(element).data('labels');


        swal({
            title: _labels.title,
            text: _labels.text,
            type: "warning",
            showCloseButton: true,
            showCancelButton: true,
            cancelButtonText:_labels.no_text_no_stock,
            confirmButtonText:_labels.yes_text_no_stock

        }).then(function (result) {

            console.log(result)

            if (result.value) {
                $(element).removeClass('fa-lock super_discreet').addClass('fa-plus')
            }else{


            }
        });


    }else{
        show_add_payment_to_order();

    }
}



function show_add_payment_to_order() {




    if ($('#add_payment').hasClass('hide')) {

        var object_data = $('#object_showcase div.order').data("object")
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

     //console.log($('#new_payment_reference').val() != '')
     //console.log(!validate_number($('#new_payment_amount').val(), 0, 999999999))
    //console.log($('#new_payment_payment_account_key').val() > 0)


    var settings=$('#new_payment_payment_account_key').data('settings')



    if(settings.block=='Accounts'){
        var valid_reference=true;


    }else{
        var valid_reference=($('#new_payment_reference').val()==''?false:true);
    }


    if(settings.max_amount!=''){

        //console.log(settings.max_amount)
        //console.log($('#new_payment_amount').val())

        var valid_max_amount=(parseFloat(settings.max_amount)<parseFloat($('#new_payment_amount').val())?false:true)

    }else{
        var valid_max_amount=true;

    }


    if( !validate_number($('#new_payment_amount').val(), 0, 999999999) && $('#new_payment_payment_account_key').val() > 0){
        var valid_amount=true;
    }else{
        var valid_amount=false;

    }

    //console.log(valid_reference)
    //console.log(valid_max_amount)

    //console.log(valid_amount)

    if (valid_reference && valid_max_amount &&  valid_amount) {
        //console.log('xx')
        $('#save_new_payment').addClass('valid changed')
    } else {
        $('#save_new_payment').removeClass('valid changed')
    }


}

function save_new_payment() {



    var object_data = $('#object_showcase div.order').data("object")


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


            //console.log(data)

            if (data.state == '200') {



                if(object_data.object=='invoice' ) {

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
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
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



function show_add_offer_to_order() {




}


function  toggle_selected_by_customer_charge(element){


    if($(element).hasClass('wait')){
        return;
    }

    if($(element).hasClass('fa-toggle-on')){
        var operation='remove_charge';
    }else{
        var operation='add_charge';
    }


    $(element).addClass('wait fa-spin fa-spinner').removeClass('fa-toggle-on fa-toggle-off')

    var object_data = $('#object_showcase div.order').data("object")


    var ajaxData = new FormData();

    ajaxData.append("tipo",'toggle_charge')
    ajaxData.append("operation",operation)

    ajaxData.append("parent", object_data.object)

    ajaxData.append("parent_key", object_data.key)
    ajaxData.append("charge_key", $(element).data('charge_key'))





    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {




            $(element).removeClass('wait')



            console.log(data)

            if (data.state == '200') {



                if(data.operation=='add_charge' ) {
                     $(element).addClass('fa-toggle-on').removeClass('fa-spinner fa-spin');
                     $(element).closest('tr').find('.selected_by_customer_charge').html(data.transaction_data.amount)


                    $('.Order_Priority_Icon').removeClass('hide')
                    $('.priority_label').removeClass('hide')


                }else{

                    $(element).addClass('fa-toggle-off').removeClass('fa-spinner fa-spin');
                    $(element).closest('tr').find('.selected_by_customer_charge').html('')

                    $('.Order_Priority_Icon').addClass('hide')
                    $('.priority_label').addClass('hide')

                }



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
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                    if (data.metadata.to_pay == 0) {
                        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                    } else {
                        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

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



            }
            else {

                swal("Error!", data.msg, "error")


                console.log(element)
                $(element).removeClass('fa-spinner fa-spin');

                if(operation=='remove_charge'){
                    $(element).addClass('fa-toggle-on')
                }else{
                    $(element).addClass('fa-toggle-off')
                }
            }


        }, error: function () {
            $(element).removeClass('fa-spinner fa-spin');

            if(operation=='remove_charge'){
                $(element).addClass('fa-toggle-on')
            }else{
                $(element).addClass('fa-toggle-off')
            }


        }
    });



}


function select_deal_component_choose_by_customer(element) {

    var container=$(element).closest('.deal_component_choose_by_customer')


    if($(element).data('product_id')==container.data('selected')){
        return;
    }


    if(container.hasClass('wait')){
        return;
    }

    container.addClass('wait')


    container.find('i').removeClass('fa-dot-circle').addClass('fa-circle')

    $(element).find('i').addClass('fa-spin fa-spinner').removeClass('fa-circle')




    var object_data = $('#object_showcase div.order').data("object")


    var ajaxData = new FormData();

    ajaxData.append("tipo",'toggle_deal_component_choose_by_customer')


    ajaxData.append("order_key", object_data.key)
    ajaxData.append("deal_component_key", container.data('deal_component_key'))
    ajaxData.append("order_transaction_deal_bridge_key", container.data('order_transaction_deal_bridge_key'))
    ajaxData.append("product_id",$(element).data('product_id'))





    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {






            console.log(data)

            if (data.state == '200') {



                $(element).find('i').addClass('fa-spin fa-spinner').removeClass('fa-circle')


                var tr=$('#transaction_item_net_'+data.transaction_deal_data.otf_key).closest('tr')


                tr.find('.item_code').html(data.transaction_deal_data.Code)
                tr.find('.item_description').html(data.transaction_deal_data.Description)



                container.removeClass('wait').data('selected',data.transaction_deal_data.product_id)

                $(element).find('i').removeClass('fa-spin fa-spinner').addClass('fa-dot-circle')



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
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                if (data.metadata.to_pay == 0) {
                    $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                } else {
                    $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

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



            }
            else {

                swal("Error!", data.msg, "error")


                console.log(element)
                $(element).removeClass('fa-spinner fa-spin');

                if(operation=='remove_charge'){
                    $(element).addClass('fa-toggle-on')
                }else{
                    $(element).addClass('fa-toggle-off')
                }
            }


        }, error: function () {
            $(element).removeClass('fa-spinner fa-spin');



        }
    });



}

// used in order showcase


$(document).on('click', '#Shipping_Net_Amount', function (evt) {
    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }
    $('#Shipping_Net_Amount').addClass('hide')
    $('#Shipping_Net_Amount_form').removeClass('hide')

})

$(document).on('click', '#Shipping_Net_Amount_label', function (evt) {
    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }
    $('#Shipping_Net_Amount').removeClass('hide')
    $('#Shipping_Net_Amount_form').addClass('hide')

})

$(document).on('input propertychange', '#Shipping_Net_Amount_input', function (evt) {


    $('#Shipping_Net_Amount_save').addClass('changed')

    if (!validate_number($('#Shipping_Net_Amount_input').val(), 0, 999999999) || $('#Shipping_Net_Amount_input').val() == '') {
        $('#Shipping_Net_Amount_save').addClass('valid').removeClass('error')

    } else {
        $('#Shipping_Net_Amount_save').removeClass('valid').addClass('error ')

    }


})


function save_shipping_value() {

    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }

    if ($('#Shipping_Net_Amount_save').hasClass('wait')) {
        return;
    }
    $('#Shipping_Net_Amount_save').addClass('wait')

    $('#Shipping_Net_Amount_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


    if ($('#Shipping_Net_Amount_input').val() == '') {
        $('#Shipping_Net_Amount_input').val(0)
    }

    var amount = parseFloat($('#Shipping_Net_Amount_input').val())


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_shipping_value')

    ajaxData.append("order_key", $('#object_showcase div.order').data("object").key)
    ajaxData.append("amount", amount)


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {


            $('#Shipping_Net_Amount_save').removeClass('wait')
            $('#Shipping_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


            console.log(data)

            if (data.state == '200') {


                $('#Shipping_Net_Amount').removeClass('hide')
                $('#Shipping_Net_Amount_form').addClass('hide')

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
                    $('.add_payment_to_order_button').addClass('fa-lock-alt super_discreet').removeClass('fa-plus')
                } else {
                    $('.add_payment_to_order_button').removeClass('fa-lock-alt super_discreet').addClass('fa-plus')
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


            } else if (data.state == '400') {
                swal($('body').data('labels').error, data.msg, "error")
            }


        }, error: function () {
            $('#Shipping_Net_Amount_save').removeClass('wait')
            $('#Shipping_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        }
    });


}


function set_shipping_as_auto() {

    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }

    if ($('#set_shipping_as_auto').hasClass('wait')) {
        return;
    }
    $('#set_shipping_as_auto').addClass('wait')

    $('#set_shipping_as_auto').removeClass('fa-magic').addClass('fa-spinner fa-spin');


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_shipping_as_auto')

    ajaxData.append("order_key", $('#object_showcase div.order').data("object").key)


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {


            $('#set_shipping_as_auto').removeClass('wait')
            $('#set_shipping_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


            console.log(data)

            if (data.state == '200') {


                $('#Shipping_Net_Amount').removeClass('hide')
                $('#Shipping_Net_Amount_form').addClass('hide')

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
                    $('.add_payment_to_order_button').addClass('fa-lock-alt super_discreet').removeClass('fa-plus')
                } else {
                    $('.add_payment_to_order_button').removeClass('fa-lock-alt super_discreet').addClass('fa-plus')
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


            } else if (data.state == '400') {
                swal($('body').data('labels').error, data.msg, "error")
            }


        }, error: function () {
            $('#set_shipping_as_auto').removeClass('wait')
            $('#set_shipping_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


        }
    });

}


$(document).on('click', '#Charges_Net_Amount', function (evt) {
    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }
    $('#Charges_Net_Amount').addClass('hide')
    $('#Charges_Net_Amount_form').removeClass('hide')

})

$(document).on('click', '#Charges_Net_Amount_label', function (evt) {
    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }
    $('#Charges_Net_Amount').removeClass('hide')
    $('#Charges_Net_Amount_form').addClass('hide')

})

$(document).on('input propertychange', '#Charges_Net_Amount_input', function (evt) {


    $('#Charges_Net_Amount_save').addClass('changed')

    if (!validate_number($('#Charges_Net_Amount_input').val(), 0, 999999999) || $('#Charges_Net_Amount_input').val() == '') {
        $('#Charges_Net_Amount_save').addClass('valid').removeClass('error')

    } else {
        $('#Charges_Net_Amount_save').removeClass('valid').addClass('error ')

    }


})

function save_charges_value() {


    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }


    if ($('#Charges_Net_Amount_save').hasClass('wait')) {
        return;
    }
    $('#Charges_Net_Amount_save').addClass('wait')

    $('#Charges_Net_Amount_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


    if ($('#Charges_Net_Amount_input').val() == '') {
        $('#Charges_Net_Amount_input').val(0)
    }

    var amount = parseFloat($('#Charges_Net_Amount_input').val())


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_hanging_charges_value')

    ajaxData.append("order_key", $('#object_showcase div.order').data("object").key)
    ajaxData.append("amount", amount)


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {


            $('#Charges_Net_Amount_save').removeClass('wait')
            $('#Charges_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');


            console.log(data)

            if (data.state == '200') {


                $('#Charges_Net_Amount').removeClass('hide')
                $('#Charges_Net_Amount_form').addClass('hide')

                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)
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
                    $('.add_payment_to_order_button').addClass('fa-lock-alt super_discreet').removeClass('fa-plus')
                } else {
                    $('.add_payment_to_order_button').removeClass('fa-lock-alt super_discreet').addClass('fa-plus')
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


            } else if (data.state == '400') {
                swal($('body').data('labels').error, data.msg, "error")
            }


        }, error: function () {
            $('#Charges_Net_Amount_save').removeClass('wait')
            $('#Charges_Net_Amount_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        }
    });


}

function set_charges_as_auto() {

    if($('#order').data('state_index')>=90 || $('#order').data('state_index')<=0 ){
        return;
    }

    if ($('#set_charges_as_auto').hasClass('wait')) {
        return;
    }
    $('#set_charges_as_auto').addClass('wait')

    $('#set_charges_as_auto').removeClass('fa-magic').addClass('fa-spinner fa-spin');


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_charges_as_auto')

    ajaxData.append("order_key", $('#object_showcase div.order').data("object").key)


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {


            $('#set_charges_as_auto').removeClass('wait')
            $('#set_charges_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


            console.log(data)

            if (data.state == '200') {


                $('#Charges_Net_Amount').removeClass('hide')
                $('#Charges_Net_Amount_form').addClass('hide')

                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)
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
                    $('.add_payment_to_order_button').addClass('fa-lock-alt super_discreet').removeClass('fa-plus')
                } else {
                    $('.add_payment_to_order_button').removeClass('fa-lock-alt super_discreet').addClass('fa-plus')
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


            } else if (data.state == '400') {
                swal($('body').data('labels').error, data.msg, "error")
            }


        }, error: function () {
            $('#set_charges_as_auto').removeClass('wait')
            $('#set_charges_as_auto').addClass('fa-magic').removeClass('fa-spinner fa-spin');


        }
    });

}

$(document).on('click', '#edit_order_in_warehouse_items', function (evt) {
    edit_order_in_warehouse();
})

function edit_order_in_warehouse(){


        Swal.fire({
            title: $('.edit_order_in_warehouse').data('title'),
            html: $('.edit_order_in_warehouse').data('html') + ' <div style="margin-top:10px" class="small error">This is a <em>beta</em> (untested) feature</div>',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: $('.edit_order_in_warehouse').data('confirm')
        }).then(function (result) {
            if (result.value) {

                $('#new_item').removeClass('hide')
                $('#edit_order_in_warehouse_items').addClass('hide')

                grid.columns.findWhere({
                    name: 'quantity'
                }).set("renderable", false);
                grid.columns.findWhere({
                    name: 'quantity_edit'
                }).set("renderable", true);
                $('#forward_operations .edit_order_in_warehouse i').removeClass('far').addClass('fas');
            }
        });


}


function show_add_deal_to_order(){
    $('.open_add_discounts').addClass('hide')
    $('.discounts_label').addClass('hide')

    $('.save_add_discounts').removeClass('hide')
    $('.close_add_discounts').removeClass('hide')
    $('#add_discount').removeClass('hide').focus()
    $('#add_item_results_container').removeClass('hide')


}

function hide_add_deal_to_order(){
    $('.open_add_discounts').removeClass('hide')
    $('.discounts_label').removeClass('hide')

    $('.save_add_discounts').addClass('hide')
    $('.close_add_discounts').addClass('hide')
    $('#add_discount').addClass('hide').focus()
    $('#add_item_results_container').addClass('hide')


}

function save_add_deal_to_order(){
    console.log('to do')

}

$(".clone_order_open_button").on( 'click',function () {
    open_clone_order()
})


function close_clone_order(){
    $('#clone_order_dialog').addClass('hide')
}

function open_clone_order() {


    if (!$('.clone_order_open_button i').hasClass('fa-clone')) {
        return;
    }

    $('.clone_order_open_button i').removeClass('fa-clone').addClass('fa-spinner fa-spin')


    var request = '/ar_find.php?tipo=orders_in_process&customer_key=' + $('#order').data('customer_key')

    $.getJSON(request, function (data) {

        $('.clone_order_open_button i').addClass('fa-clone').removeClass('fa-spinner fa-spin')


        $('#clone_order_dialog').find('.clone_order_msg').html(data.clone_msg)

        if (data.orders_in_process > 1) {
            $('#clone_order_dialog').find('.save').removeClass('valid').addClass('invalid')
        }


        $('#clone_order_dialog').removeClass('hide').offset({
            top:  $('.clone_order_open_button').offset().top  + $('.clone_order_open_button').height(), left: $('.clone_order_open_button').offset().left-$('#clone_order_dialog').width()
        })


    })

}

function clone_order(element){

    if($(element).hasClass('invalid')){
        return ;
    }

    var icon=$(element).find('i')

    if(icon.hasClass('wait')){
        return ;
    }

    icon.addClass('wait fa-spinner fa-spin').removeClass('fa-clone')



    var request = '/ar_edit_orders.php?tipo=clone_order&order_key='+ $('#order').attr('order_key')
    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'clone_order')
    form_data.append("order_key", $('#order').attr('order_key'))

    var request = $.ajax({
        url: "/ar_edit_orders.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'
    })

    request.done(function (data) {

        if (data.state == 200) {
            change_view(data.redirect)

        }
        else if (data.state == 400) {
            alert(data.msg)


        }
    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });





}

function post_modify_item_order(data){

    $('.order_operation').addClass('hide')

    for (var key in data.metadata.operations) {
        $('#' + data.metadata.operations[key]).removeClass('hide')
    }

    $('.Total_Amount').attr('amount', data.metadata.to_pay)
    $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


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
        $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
    } else {
        $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
    }


    if (data.metadata.to_pay == 0) {
        $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

    } else {
        $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

    }


    if (data.metadata.items == 0) {
        $('#submit_operation').addClass('hide')
        $('#send_to_warehouse_operation').addClass('hide')

    }
    else {
        $('#submit_operation').removeClass('hide')
        $('#send_to_warehouse_operation').removeClass('hide')
    }


    rows.fetch({
        reset: true
    });

    for (var key in data.metadata.class_html) {
        $('.' + key).html(data.metadata.class_html[key])
    }

    for (var key in data.metadata.hide) {
        $('#' + data.metadata.hide[key]).addClass('hide')
    }
    for (var key in data.metadata.show) {
        $('#' + data.metadata.show[key]).removeClass('hide')
    }


}
