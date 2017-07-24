/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 July 2016 at 13:32:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function set_placement_location(element) {
    $(element).closest('tr').find('.location_code').val($(element).find('.code').html())
    $(element).closest('tr').find('i.save').attr('location_key', $(element).attr('location_key'))
    validate_place_item($(element).closest('tr').find('.place_item'))
}


function delayed_on_change_place_qty_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        on_change_place_qty_field(object)
    }, timeout));
}

function on_change_place_qty_field(element) {

    var max = element.attr('max')

    error = validate_number(element.val(), 0, max);

    if (!error) {

        if (element.val() == '' || element.val() == 0) {

            error = {
                class: 'invalid',
                type: 'empty'
            }

        }
    }

    console.log(error)

    if (error) {
        element.addClass(error.class)

    } else {
        element.removeClass('invalid')
    }

    validate_place_item(element.closest('.place_item'))

}

function delayed_on_change_location_code_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        get_placement_locations_select(object)
    }, timeout));
}

function get_placement_locations_select(object) {

    object.removeClass('invalid')

    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(object.val()) + '&scope=locations&state=' + JSON.stringify(state)
    console.log(request)
    $.getJSON(request, function (data) {


        var offset = object.offset();


        if (data.number_results > 0) {
            $('#location_results_container').removeClass('hide').addClass('show')
            $('#location_results_container').offset({
                top: (offset.top + object.outerHeight() - 1),
                left: offset.left
            })

        } else {


            $('#location_results_container').addClass('hide').removeClass('show')
            if (object.val() != '') {
                object.addClass('invalid')
            }


        }


        $("#location_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            //console.log(result_key)
            var clone = $("#location_search_result_template").clone()
            clone.prop('id', 'location_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('transaction_key', object.closest('.place_item').attr('transaction_key'))
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            // clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            // clone.children(".code").html(data.results[result_key].code)
            clone.children(".label").html(data.results[result_key].description)

            $("#location_results").append(clone)


        }

    })


}

function select_location_option(element) {
    var container = $('#place_item_' + $(element).attr('transaction_key'))
    container.find('.location_code').val($(element).attr('formatted_value'))
    container.find('i.save').attr('location_key', $(element).attr('value'))
    $('#location_results_container').addClass('hide').removeClass('show')
    validate_place_item(container)
}

function validate_place_item(element) {


    if ($(element).find('.place_qty').hasClass('invalid') || $(element).find('.location_code').hasClass('invalid')) {


        $(element).addClass('invalid changed')
    } else {


        $(element).removeClass('invalid changed')

        if ($(element).find('i.save').attr('location_key') > 0) {
            $(element).addClass('changed valid')
        }

    }


}

function place_item(element) {


    $(element).removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

    var object = object_data.object
    var key = object_data.key


    var part_sku = $(element).closest('.place_item').attr('part_sku')
    var transaction_key = $(element).closest('.place_item').attr('transaction_key')
    var location_key = $(element).attr('location_key')
    var qty = $(element).closest('.place_item').find('.place_qty').val()

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

        url: "/ar_edit_stock.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

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


    })

    request.fail(function (jqXHR, textStatus) {
    });


}

function show_part_locations(element) {

    var part_locations = $(element).closest('tr').find('.part_locations')
    if (part_locations.hasClass('hide')) {
        part_locations.removeClass('hide')
        $(element).prop('title', part_locations.attr('hide_title')).removeClass('discreet')
    } else {
        part_locations.addClass('hide')
        $(element).prop('title', part_locations.attr('show_title')).addClass('discreet')

    }


}


