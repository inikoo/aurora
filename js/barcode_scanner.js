/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 January 2017 at 11:54:31 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function scanned_barcode(value) {


    console.log(state.object)

    if (state.object == 'delivery_note') {
        scanned_barcode_in_delivery_note(value)
    } else if (state.object == 'supplierdelivery') {
        scanned_barcode_in_supplier_delivery_note(value)
    }

}



function process_supplier_delivery_sko_barcode(value){


    var picking_barcode_feedback = $('#booking_in_barcode_feedback')
    picking_barcode_feedback.find('.fa-barcode').addClass('valid_save')
    window.setTimeout(function () {
        picking_barcode_feedback.find('.fa-barcode').removeClass('valid_save')
    }, 200);

    var found = false;
    checked=false

    var found_tr=false;

    $('#table .part_sko_item').each(function (i, obj) {




        //console.log('bad >' + $(obj).attr('barcode') + '< >' + value + '<')
        if ($(obj).attr('barcode') == value) {
            console.log($(obj).attr('barcode') + ' ' + value)
            var settings = $(obj).closest('span').data('barcode_settings')

            console.log(settings)


             found_tr=$(obj).closest('tr')
            $('#booking_in_barcode_feedback').data('tr',$(obj).closest('tr'))

            console.log($(obj).closest('span').attr('_checked'))
             checked=$(obj).closest('span').attr('_checked')

            $('#booking_in_barcode_part_reference').html(settings.reference)
            $('#booking_in_barcode_part_description').html(atob(settings.description))

            if (settings.image_src > 0) {
                $('#booking_in_barcode_part_image_src').attr('src', settings.image_src)
            } else {
                $('#booking_in_barcode_part_image_src').attr('src', '/art/nopic.png')
            }

            $('#copy_qty_from_barcode_feedback').attr('qty', settings.qty).html('<span class="discreet">' + settings.cartons + '</span>/' + settings.skos + '<span class="discreet">/' + settings.units + '</span>')


            found = true;
            return false
        }
    });
    $('#table tr').addClass('hide')

    $('#booking_in_barcode_qty_input').val('')
    booking_in_barcode_qty_input_changed()

    $('.booking_in_barcode_feedback_block').addClass('hide')

    if (found) {



        picking_barcode_feedback.find('.barcode_found').removeClass('hide')

        console.log(checked)

        if(!checked){
            $('.fast_track').removeClass('hide')



        }else{
            $('.fast_track').addClass('hide')

console.log(found_tr)
            $(found_tr).removeClass('hide')

        }




    }
    else {
        picking_barcode_feedback.find('.barcode_not_found').removeClass('hide')
        picking_barcode_feedback.find('.barcode_found').addClass('hide')
        picking_barcode_feedback.find('.not_found_barcode_number').html(value)


        var request = '/ar_find.php?tipo=find_object&scope=part&field=barcode&parent=warehouse&parent_key=' + state.current_warehouse + '&query=' + value + '&state=' + JSON.stringify(state)
        console.log(request)
        $.getJSON(request, function (data) {


            if (data.results == 1) {
                $('#booking_in_barcode_feedback .assign_barcode').addClass('hide')
                $('#booking_in_barcode_feedback .find_outside_order').removeClass('hide')

            } else {

                $('#booking_in_barcode_feedback .assign_barcode').removeClass('hide')
                $('#booking_in_barcode_feedback .find_outside_order').addClass('hide')

            }


        });


    }

}


function scanned_barcode_in_supplier_delivery_note(value) {


    console.log(state.tab)

    if (state.tab == 'supplier.delivery.items') {


        if(!$('#booking_in_barcode_feedback').hasClass('hide')) {


            $('.booking_in_barcode_feedback_block').addClass('hide')


            $('#ready_for_scan_label').addClass('hide')

            if ($('#reading_location_barcode').hasClass('invisible')) {

                process_supplier_delivery_sko_barcode(value)


            } else {



                // lock for location
                var request = '/ar_find.php?tipo=find_object&scope=location&field=code&parent=warehouse&parent_key=' + state.current_warehouse + '&query=' + value + '&state=' + JSON.stringify(state)
                console.log(request)
                $.getJSON(request, function (data) {


                    if (data.results == 1) {


                        $('#booking_in_barcode_feedback').attr('location_key', data.data.key)

                        var qty = $('#booking_in_barcode_qty_input').val()


                        var tr = $('#booking_in_barcode_feedback').data('tr')

                        var element = tr.find('.checked_quantity i')


                        var input = tr.find('.checked_quantity input')
                        var table_metadata = JSON.parse(atob($('#table').data("metadata")))

                        var settings = tr.find('.checked_quantity').data('settings')


                        var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&field=' + settings.field + '&parent_key=' + table_metadata.parent_key + '&item_key=' + settings.item_key + '&qty=' + qty + '&transaction_key=' + settings.transaction_key

                        if (settings.item_historic_key != undefined) {
                            request = request + '&item_historic_key=' + settings.item_historic_key
                        }


                        var form_data = new FormData();

                        form_data.append("tipo", 'edit_item_in_order')
                        form_data.append("field", settings.field)
                        form_data.append("parent", table_metadata.parent)
                        form_data.append("parent_key", table_metadata.parent_key)
                        form_data.append("item_key", settings.item_key)
                        if (settings.item_historic_key != undefined) {
                            form_data.append("item_historic_key", settings.item_historic_key)
                        }


                        form_data.append("transaction_key", settings.transaction_key)


                        form_data.append("qty", qty)


                        var request = $.ajax({

                            url: "/ar_edit_orders.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

                        })


                        request.done(function (data) {


                            if (data.state == 200) {

                                console.log(data)
                                console.log(table_metadata)


                                input.val(data.transaction_data.qty).removeClass('discreet')
                                input.attr('ovalue', data.transaction_data.qty)


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


                                for (var key in data.metadata.class_html) {
                                    $('.' + key).html(data.metadata.class_html[key])
                                }


                                for (var key in data.metadata.hide) {
                                    $('#' + data.metadata.hide[key]).addClass('hide')
                                }
                                for (var key in data.metadata.show) {
                                    $('#' + data.metadata.show[key]).removeClass('hide')
                                }


                                // place the stock


                                var element = tr.find('.place_item_button')
                                var qty = $('#booking_in_barcode_qty_input').val()

                                var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

                                var object = object_data.object
                                var key = object_data.key


                                var part_sku = $(element).closest('.place_item').attr('part_sku')
                                var transaction_key = $(element).closest('.place_item').attr('transaction_key')


                                var location_key = $('#booking_in_barcode_feedback').attr('location_key')

                                //var location_key = $(element).attr('location_key')

                                var note = $(element).closest('tr').find('.note').val()


                                var request = '/ar_edit_stock.php?tipo=place_part&object=' + object + '&key=' + key + '&transaction_key=' + transaction_key + '&part_sku=' + part_sku + '&location_key=' + location_key + '&qty=' + qty + '&note=' + note
                                console.log(request)
                                //return;
                                //=====
                                var form_data = new FormData();
                                form_data.append("tipo", 'place_part')
                                form_data.append("object", object)
                                form_data.append("key", key)
                                form_data.append("transaction_key", transaction_key)
                                form_data.append("part_sku", part_sku)
                                form_data.append("location_key", location_key)
                                form_data.append("qty", qty)
                                form_data.append("note", note)

                                var request = $.ajax({

                                    url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

                                })

                                request.done(function (data) {


                                    console.log(data)


                                    $(element).addClass('fa-cloud').removeClass('fa-spinner fa-spin')

                                    var place_item = $('#place_item_' + transaction_key)
                                    var tr = place_item.closest('tr')
                                    tr.find('.placement').html(data.update_metadata.placement)
                                    tr.find('.part_locations').html(data.part_locations)


                                    tr.find('.place_qty').val(data.place_qty)
                                    tr.find('.place_qty').attr('ovalue', data.place_qty)
                                    tr.find('.place_qty').attr('max', data.place_qty)


                                    $('.order_operation').addClass('hide')
                                    $('.items_operation').addClass('hide')

                                    for (var key in data.update_metadata.operations) {
                                        $('#' + data.update_metadata.operations[key]).removeClass('hide')
                                    }


                                    $('.timeline .li').removeClass('complete')


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


                                    if (data.placed == 'Yes') {
                                        place_item.addClass('hide')
                                    } else {
                                        place_item.removeClass('hide')

                                    }

                                    for (var key in data.update_metadata.class_html) {

                                        $('.' + key).html(data.update_metadata.class_html[key])
                                    }


                                    for (var key in data.updated_fields) {

                                        $('.' + key).html(data.updated_fields[key])
                                    }

                                    $('.booking_in_barcode_feedback_block').addClass('hide')

                                    $('#booking_in_barcode_feedback .placement_success').removeClass('hide').find('.placement').html(data.update_metadata.placement)
                                    $('#reading_location_barcode').addClass('invisible')
                                    $('#booking_in_barcode_location_input').val('')

                                })

                                request.fail(function (jqXHR, textStatus) {
                                });


                            } else if (data.state == 400) {


                                sweetAlert(data.msg);
                                input.val(input.attr('ovalue'))


                            }

                        })


                        /*



                        var supplier_delivery_key = 1;
                        var item_key = '';
                        var qty = $('#booking_in_barcode_qty_input').val()
                        var transaction_key = ''
                        var item_historic_key = '';


                        var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=supplierdelivery&field=Supplier Delivery Checked Quantity&parent_key=' + supplier_delivery_key + '&item_key=' + item_key + '&qty=' + qty + '&transaction_key=' + transaction_key + '&item_historic_key=' + item_historic_key


                        // ar_edit_stock.php?tipo=place_part & object = supplierdelivery & key = 4 & transaction_key = 2334 & part_sku = 4158 & location_key = 394 & qty = 100 & note =

                        var request = '/ar_find.php?tipo=find_object&scope=location&field=code&parent=warehouse&parent_key=' + state.current_warehouse + '&query=' + value + '&state=' + JSON.stringify(state)


                        console.log('ok')

                        */

                    } else {

                        $('#booking_in_barcode_location_input').val(value)

                    }


                });


            }
        }

    }

}


function scanned_barcode_in_delivery_note(value) {

    if (state.tab == 'delivery_note.items') {


        var selected_tab = $('.table_views .view.selected')


        if (selected_tab.attr('id') == 'view_picking_aid') {

            var picking_barcode_feedback = $('#picking_barcode_feedback')
            picking_barcode_feedback.find('.fa-barcode').addClass('valid_save')
            window.setTimeout(function () {
                picking_barcode_feedback.find('.fa-barcode').removeClass('valid_save')
            }, 200);

            var found = false;

            $('#table .picking').each(function (i, obj) {


                console.log('bad >' + $(obj).attr('barcode') + '< >' + value + '<')
                if ($(obj).attr('barcode') == value) {
                    console.log($(obj).attr('barcode') + ' ' + value)
                    var settings = $(obj).closest('span').data('settings')
                    console.log(settings)
                    $('#picking_barcode_part_reference').html(settings.reference)
                    $('#picking_barcode_part_description').html(atob(settings.description))
                    $('#picking_barcode_part_image_src').html(settings.image_src)
                    return false
                }
            });


        }


    }

}


function copy_qty_from_barcode_feedback(element) {

    $('#booking_in_barcode_qty_input').val($(element).attr('qty'))

    booking_in_barcode_qty_input_changed()

}



$(document).on('click', '#book_in_with_barcode,#close_booking_in_barcode_feedback', function (evt) {

if($('#booking_in_barcode_feedback').hasClass('hide')){
    $('#booking_in_barcode_feedback').removeClass('hide')

    $('#elements').addClass('hide')

    $('#table tr').addClass('hide')

    $('.table_info').addClass('hide')

}else{
    $('#booking_in_barcode_feedback').addClass('hide')
    $('#elements').removeClass('hide')

    $('#table tr').removeClass('hide')

    $('.table_info').removeClass('hide')
}



});

$(document).on('input propertychange', '#booking_in_barcode_qty_input', function (evt) {


    booking_in_barcode_qty_input_changed()

});


function booking_in_barcode_qty_input_changed() {

    var element = $('#booking_in_barcode_qty_input')

    console.log(element.val())


    if (element.val() == '') {

        element.closest('div').find('i').removeClass('fa-cloud exclamation-circle error').addClass('fa-plus')
        $('#reading_location_barcode').addClass('invisible')
    } else {

        if (!validate_signed_integer(element.val(), 4294967295) || element.val() == '') {
            element.closest('div').find('i').removeClass(' exclamation-circle error invisible').addClass('fa-plus')
            element.addClass('discreet')

            $('#reading_location_barcode').removeClass('invisible')
            $('#booking_in_barcode_location_input').focus()

        } else {
            element.closest('div').find('i').removeClass('fa-plus fa-cloud invisible').addClass('fa-exclamation-circle error')
            $('#reading_location_barcode').addClass('invisible')
        }
    }
}


function select_assign_barcode_to_part_option(element){

    var td=$(element)


    if(td.find('.code').attr('barcode')==undefined){
        console.log(td.attr('value'))


        http://au.bali/ar_edit.php?tipo=edit_field&object=Part&key=2446&field=Part_SKO_Barcode&value=sdsffdsfdsd&metadata={}



            var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("object", 'Part')
        form_data.append("key", td.attr('value'))
        form_data.append("field",'Part_SKO_Barcode')
        form_data.append("value", $('#not_found_barcode_number').html())





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

                console.log('xxxxxxxxxxx')
                console.log($('#not_found_barcode_number').html())

                $('#part_sko_item_'+td.attr('value')).attr('barcode',$('#not_found_barcode_number').html())

                process_supplier_delivery_sko_barcode($('#not_found_barcode_number').html())




            } else if (data.state == 400) {


                sweetAlert(data.msg);



            }

        })


    }






}