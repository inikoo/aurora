/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 November 2018 at 13:31:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/

function open_part_edit_move_stock() {

    $('.Part_Cost_in_Warehouse_info_set_up').addClass('invisible')

    $('.edit_stock_close_button').removeClass('hide')

    $('.showcase_component').addClass('super_discreet')
    process_part_location_move_locations()


    $('.location_to_be_disassociated_icon').addClass('hide')


    $('.location_code').removeClass('link')
    $('.undo_unlink_operations').css('width', '0px')


    $('.picking_location_note').addClass('hide')




    $('.add_note').addClass('hide')


    // $('.last_audit_days').addClass('hide')
    $('.set_as_audit').addClass('hide')
    // $('.recommendations').addClass('hide')
    $('.picking_location_icon').addClass('hide')
    //  $('.disassociate_info').removeClass('hide')


    $('input.stock').removeClass('hide').prop('readonly', true)


    $('#locations_table .formatted_stock').addClass('hide')
    $('#locations_table .stock_input').removeClass('hide')


    $('.edit_stock_open_button').addClass('hide')
    $('.part_locations_move_stock_button').removeClass('hide')


}


function move(element) {


    $('.locations  far.fa-dot-circle ').each(function (i, obj) {
        $(obj).addClass('invisible')
    })


    //   $('#edit_stock_saving_buttons').addClass('hide')
    if (!$(element).hasClass('fa-flip-horizontal')) {


        console.log('xxx')

        movements = false

        $('#move_from').html($(element).closest('tr').find('.location_code').html())

        $('#locations_table  .fa-unlink ').addClass('invisible')
        $('#add_location_tr').addClass('hide')

        $('#locations_table  input.stock ').prop('readonly', true)

        $(element).addClass('invisible').addClass('from')


        $('#move_stock_tr').removeClass('hide').attr('max', $(element).closest('tr').find('input').val())

        var possible_to_locations = 0;
        var to;
        $('.locations  .move_trigger ').each(function (i, obj) {
            if (!$(obj).hasClass('from')) {

                //console.log($(obj))
                $(obj).removeClass('super_discreet  invisible').addClass('fa-flip-horizontal')
                possible_to_locations++;
                to = obj
            }

        })
        console.log(possible_to_locations)
        if (possible_to_locations == 1) {
            move(to)
        }

        $('#move_stock_qty').focus()

    } else {

        $(element).addClass('to')


        $('#move_to').html($(element).closest('tr').find('.location_code').html())

        $('.locations  .move_trigger ').each(function (i, obj) {
            $(obj).addClass('invisible')
        })


        $('#move_stock_qty').focus()


        move_qty_changed($('#move_stock_qty'))

        console.log(movements)

    }

}


function close_move() {


    var movements = $('#move_stock_tr').data('movements')

    $('#edit_stock_saving_buttons').removeClass('hide')


    $('#move_from').html('')
    $('#move_to').html('')
    $('#locations_table  input.stock ').prop('readonly', false)
    $('#move_stock_tr').removeClass('valid invalid')
    $('#move_stock_qty').val('')



    movements = {}

    var from_input = $('#locations_table  .from ').closest('tr').find('input.stock')
    from_input.val(from_input.attr('ovalue'))
    var to_input = $('#locations_table  .to ').closest('tr').find('input.stock')
    to_input.val(to_input.attr('ovalue'))
    stock_changed($(from_input))
    stock_changed(to_input)
    process_part_location_move_locations()


    $('#locations_table .move_trigger').removeClass('fa-flip-horizontal from to').addClass('very_discreet')
    $('#move_stock_tr').addClass('hide')


    $('.locations  .move_trigger ').each(function (i, obj) {
        $(obj).addClass('super_discreet')


        if ($(obj).closest('tr').find('input.stock').val() > 0) {
            $(obj).addClass('visible')
        }

    })

    $('#move_stock_tr').data('movements', movements)
    part_locations_move_stock_look_for_changes()

}


function move_qty_changed(element) {


    var movements = $('#move_stock_tr').data('movements')

    var value = element.val()


    if (value == '') {
        $('#move_stock_tr').removeClass('valid invalid')
    } else {
        validation = client_validation('numeric_unsigned', false, value, '')

        if (validation.class == 'valid') {
            //console.log($('#locations_table  .from ').closest('tr').find('input.stock').val())
//console.log(value)
//console.log($('.locations  .from ').closest('tr').find('input.stock').val())

            if (parseInt(value) > parseInt($('.locations  .from ').closest('tr').find('input.stock').attr('ovalue'))) {
                validation.class = 'invalid'
            }
        }
        $('#move_stock_tr').removeClass('valid invalid').addClass(validation.class)


        if (validation.class == 'valid') {


            var move_qty = parseFloat($('#move_stock_qty').val())

            if (isNaN(move_qty)) return

            // $('#move_stock_qty').val('');
            var from_input = $('.locations   .from ').closest('tr').find('input.stock')

            old_from_input = from_input.attr('ovalue')
            from_input.val(parseFloat(from_input.attr('ovalue')) - move_qty)

            stock_changed($(from_input))

            var to_input = $('.locations  .to ').closest('tr').find('input.stock')
            old_to_input = to_input.attr('ovalue')


            to_input.val(parseFloat(to_input.attr('ovalue')) + move_qty)
            stock_changed(to_input)

            process_part_location_move_locations()
            movements = {
                part_sku: $('#locations_table').attr('part_sku'),
                from_location_key: from_input.attr('location_key'),
                from_location_stock: old_from_input,
                to_location_key: to_input.attr('location_key'),
                to_location_stock: old_to_input,
                move_qty: move_qty
            }

        } else {

            movements = {}

            var from_input = $('#locations_table  .from ').closest('tr').find('input.stock')
            from_input.val(from_input.attr('ovalue'))
            var to_input = $('#locations_table  .to ').closest('tr').find('input.stock')
            to_input.val(to_input.attr('ovalue'))
            stock_changed($(from_input))
            stock_changed(to_input)
            process_part_location_move_locations()


        }


    }



    $('#move_stock_tr').data('movements', movements)
    part_locations_move_stock_look_for_changes()
}


function process_part_location_move_locations() {

    var total_new_stock = 0;
    var diff_up = 0
    var diff_down = 0

    var has_invalid = false;


    var potential_more_outs = 0;
    var editable_locations = 0;

    $('#part_locations  input.stock ').each(function (i, obj) {


        var can_move_out = true;
        var editable_location = true;


        if ($(obj).closest('tr').hasClass('invalid')) {
            has_invalid = true;
            can_move_out = false;
            editable_location = false;
        }


        if ($(obj).val() == 0 || $(obj).val() == '') {
            can_move_out = false;
        }

        var new_stock = $(obj).val();
        var old_stock = $(obj).attr('ovalue');

        if (new_stock == '') new_stock = 0;
        if (old_stock == '') old_stock = 0;

        new_stock = parseFloat(new_stock)
        old_stock = parseFloat(old_stock)


        var diff = new_stock - old_stock;

        if (diff > 0) {
            diff_up += diff

        } else {
            diff_down += diff
        }


        total_new_stock += new_stock


        if (can_move_out) {
            potential_more_outs++;
        } else {
        }


        if (editable_location) {
            editable_locations++;

            if ($('#move_stock_tr').hasClass('hide')) {

                if ($(obj).closest('tr').find('input.stock').val() <= 0) {
                    $(obj).closest('tr').find('.move_trigger').addClass('invisible')
                } else {
                    $(obj).closest('tr').find('.move_trigger').removeClass('invisible')
                }
            } else {


            }

        } else {

            $(obj).closest('tr').find('.move_trigger').addClass('invisible')

        }

    })
    var diff_msg = '';
    //console.log(potential_more_outs)
    if (editable_locations < 2) {
        $('.move_trigger').addClass('invisible')
    }


    if (diff_down != 0) {
        diff_msg += ' (' + diff_down.toFixed(2).replace(/[.,]00$/, "") + ') '
    }
    if (diff_up != 0) {
        diff_msg += ' (+' + diff_up.toFixed(2).replace(/[.,]00$/, "") + ') '
    }

    $('#new_stock').html(total_new_stock)


    $('#stock_diff').html(diff_msg)


}




function part_locations_move_stock_look_for_changes() {

    var movements = $('#move_stock_tr').data('movements')

    if(movements.from_location_key>0 && movements.to_location_key>0 && movements.move_qty>0){
        $('.part_locations_move_stock_button').addClass('changed valid')

        $('.part_locations_move_stock_button span').html($('.part_locations_move_stock_button span').data('labels').save_changes)


    }else{
        $('.part_locations_move_stock_button').removeClass('changed valid')
    }



}


function save_part_locations_move_stock(element) {

    if (!$(element).hasClass('valid')) {

        return;
    }


    var icon = $(element).find('i');

    icon.removeClass('fa-cloud').addClass('fa-spinner fa-spin ')


    var movements = $('#move_stock_tr').data('movements')

    var form_data = new FormData();

    form_data.append("tipo", 'edit_part_move_stock')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("movements", JSON.stringify(movements))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {

        console.log(data)

        icon.addClass('fa-cloud').removeClass('fa-spinner fa-spin ')

        if (data.state == 200) {
            if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock' || state.tab == 'part.locations') {
                rows.fetch({
                    reset: true
                });
            }

            $('#part_locations').html(data.part_locations)

            $('.edit_stock_open_button').removeClass('hide')
            $('.edit_stock_close_button').addClass('hide')
            $('.part_locations_stock_check_button').addClass('hide')

            $('.showcase_component').removeClass('super_discreet')


            for (var key in data.updated_fields) {
                $('.' + key).html(data.updated_fields[key])
            }


            if (data.Part_Unknown_Location_Stock != 0) {
                $('#unknown_location_tr').removeClass('hide')
            }

            $('#Part_Unknown_Location_Stock').attr('qty', data.Part_Unknown_Location_Stock)

            $('.edit_stock_save_button').addClass('hide')

            $('.part_locations_move_stock_button span').html($('.part_locations_move_stock_button span').data('labels').no_change)
            $('.part_locations_move_stock_button').removeClass('changed valid')

            $('#move_stock_qty').val('')
            $('#move_from').html('')
            $('#move_to').html('')
            $('#move_stock_tr').addClass('hide')

        } else {
            swal(data.state.toString(), data.msg, "error")
        }


    })

    request.fail(function (jqXHR, textStatus) {
    });

}