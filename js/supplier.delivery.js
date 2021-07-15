/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 July 2016 at 13:32:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/



$(document).on('input propertychange','.place_item .location_code',function () {
    let delay = 100;
    delayed_on_change_location_code_field($(this), delay)
});


$(document).on('input propertychange','.place_item .place_qty',function () {
    let delay = 100;
    delayed_on_change_place_qty_field($(this), delay)
});




function set_placement_location(element) {
    $(element).closest('tr').find('.location_code').removeClass('invalid').val($(element).find('.code').html())
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

    let max = element.attr('max')

    let error = validate_number(element.val(), 0, max);

    if (!error) {

        if (element.val() == '' || element.val() == 0) {

            error = {
                class: 'invalid', type: 'empty'
            }

        }
    }


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

    // object.removeClass('invalid')

    let request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(object.val()) + '&scope=locations&state=' + JSON.stringify(state)

    $.getJSON(request, function (data) {


        var offset = object.offset();


        if (data.number_results > 0) {
            $('#location_results_container').removeClass('hide').addClass('show')
            $('#location_results_container').offset({
                top: (offset.top + object.outerHeight() - 1), left: offset.left
            })

        } else {


            $('#location_results_container').addClass('hide').removeClass('show')
            if (object.val() != '') {
                object.addClass('invalid')
               // object.closest('.place_item').removeClass('valid').addClass('invalid')
            } else {
                object.removeClass('invalid')
            }


        }


        $("#location_results .result").remove();

        let first = true;

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


        let container=object.closest('.place_item');
        container.find('i.save').attr('location_key',0)

        validate_place_item(container)
    })


}




function select_location_option(element) {
    let container = $('#place_item_' + $(element).attr('transaction_key'))
    let location_code_input = container.find('.location_code');

    location_code_input.val($(element).attr('formatted_value'));
    location_code_input.removeClass('invalid')

    container.find('i.save').attr('location_key', $(element).attr('value'))
    $('#location_results_container').addClass('hide').removeClass('show')
    validate_place_item(container)
}

function validate_place_item(element) {

    if ($(element).find('.place_qty').hasClass('invalid') || $(element).find('.location_code').hasClass('invalid')) {


        $(element).addClass('invalid changed')
    } else {


        $(element).removeClass('invalid changed valid')

        if ($(element).find('i.save').attr('location_key') > 0) {
            $(element).addClass('changed valid')
        }

    }


}

function place_item(element) {


    let save_container = $(element).closest('.place_item');


    if (save_container.hasClass('wait') || !save_container.hasClass('valid') || !save_container.hasClass('changed')   ) {
        return;
    }

    $('#placement_note').addClass('hide');

    save_container.addClass('wait');

    $(element).removeClass('fa-cloud').addClass('fa-spinner fa-spin ');


    let object_data = $('#object_showcase div.order').data("object");

    let object = object_data.object;
    let key = object_data.key;


    let part_sku = $(element).closest('.place_item').attr('part_sku');
    let transaction_key = $(element).closest('.place_item').attr('transaction_key');
    let location_key = $(element).attr('location_key');
    let qty = $(element).closest('.place_item').find('.place_qty').val();

    let note = $(element).closest('tr').find('.note').val();


    if (note === undefined) {
        note = '';
    }


    var form_data = new FormData();
    form_data.append("tipo", 'place_part');
    form_data.append("object", object);
    form_data.append("key", key);
    form_data.append("transaction_key", transaction_key);
    form_data.append("part_sku", part_sku);
    form_data.append("location_key", location_key);
    form_data.append("qty", qty);
    form_data.append("note", note);

    let request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {

        save_container.removeClass('wait');

        $(element).addClass('fa-cloud').removeClass('fa-spinner fa-spin');


        var table_metadata = $('#table').data("metadata")


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

        $('#order_node').addClass('complete')
        $('#order_dispatched_node').addClass('complete')

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

        if (data.update_metadata.state_index >= 100) {
            $('#placed_node').addClass('complete')
        }


        if (table_metadata.type == 'return') {

            if (data.update_metadata.state_index >= 100) {

                $("div[id='tab_return']").addClass('hide')

                $("div[id='tab_return.items_done']").removeClass('hide')

                change_tab('return.items_done')


            }

        } else {
            if (data.update_metadata.state_index == 100) {


                if (state.tab == 'supplier.delivery.items') {


                    change_tab('supplier.delivery.items')

                }


            }
        }


        if (data.placed === 'Yes') {
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


