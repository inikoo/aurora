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


function scanned_barcode_in_supplier_delivery_note(value) {


    console.log(state.tab)

    if (state.tab == 'supplier.delivery.items') {


        if ($('#reading_location_barcode').hasClass('invisible')) {



            var picking_barcode_feedback = $('#booking_in_barcode_feedback')
            picking_barcode_feedback.find('.fa-barcode').addClass('valid_save')
            window.setTimeout(function () {
                picking_barcode_feedback.find('.fa-barcode').removeClass('valid_save')
            }, 200);

            var found = false;

            $('#table .part_sko_item').each(function (i, obj) {


                //console.log('bad >' + $(obj).attr('barcode') + '< >' + value + '<')
                if ($(obj).attr('barcode') == value) {
                    console.log($(obj).attr('barcode') + ' ' + value)
                    var settings = $(obj).closest('span').data('barcode_settings')


                    $('#booking_in_barcode_feedback').data('tr',$(obj).closest('tr'))

                    console.log(settings)
                    $('#booking_in_barcode_part_reference').html(settings.reference)
                    $('#booking_in_barcode_part_description').html(settings.description)

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


            $('#booking_in_barcode_qty_input').val('')
            booking_in_barcode_qty_input_changed()


            if (found) {

                picking_barcode_feedback.find('.barcode_found').removeClass('hide')
                picking_barcode_feedback.find('.barcode_not_found').addClass('hide')


            } else {
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

        } else {


            console.log('xxxx')
            // lock for location
            var request = '/ar_find.php?tipo=find_object&scope=location&field=code&parent=warehouse&parent_key=' + state.current_warehouse + '&query=' + value + '&state=' + JSON.stringify(state)
            console.log(request)
            $.getJSON(request, function (data) {


                if (data.results == 1) {



                    var tr=$('#booking_in_barcode_feedback').data('tr')


                    var table_metadata = JSON.parse(atob($('#table').data("metadata")))

                    tr.find('.checked_quantity').data('settings')

                    var supplier_delivery_key = 1;
                    var item_key = '';
                    var qty = $('#booking_in_barcode_qty_input').val()
                    var transaction_key = ''
                    var item_historic_key = '';


                    var request = '/ar_edit_orders.php?tipo=edit_item_in_order&parent=supplierdelivery&field=Supplier Delivery Checked Quantity&parent_key=' + supplier_delivery_key + '&item_key=' + item_key + '&qty=' + qty + '&transaction_key=' + transaction_key + '&item_historic_key=' + item_historic_key


                    // ar_edit_stock.php ? tipo = place_part & object = supplierdelivery & key = 4 & transaction_key = 2334 & part_sku = 4158 & location_key = 394 & qty = 100 & note =

                    var request = '/ar_find.php?tipo=find_object&scope=location&field=code&parent=warehouse&parent_key=' + state.current_warehouse + '&query=' + value + '&state=' + JSON.stringify(state)


                    console.log('ok')

                } else {

                    $('#booking_in_barcode_location_input').val(value)

                }


            });


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
