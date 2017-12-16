function open_edit_stock() {

    $('#part_stock_value').addClass('hide')

    process_edit_stock()

    $('.unlink_operations').removeClass('hide')


    $('#stock_table').addClass('super_discreet')
    $('#close_edit_stock').removeClass('hide')
    $('#edit_stock_saving_buttons').removeClass('hide')
    $('#open_edit_stock').addClass('hide')

    // $('#stock_table tbody.info').addClass('hide')
    //   $('#edit_stock_controls').removeClass('hide')
    $('#locations_table .formatted_stock').addClass('hide')
    $('#locations_table .stock_input').removeClass('hide')

    $('#add_location_tr').removeClass('hide')


}

function close_edit_stock() {


    $('#part_stock_value').removeClass('hide')


    if (!$('#move_stock_tr').hasClass('hide')) {
        close_move();
    }

    $('.unlink_operations').addClass('hide')

    //$('#stock_table tbody.info').removeClass('hide')
    // $('#edit_stock_controls').addClass('hide')
    $('#stock_table').removeClass('super_discreet')
    $('#close_edit_stock').addClass('hide')
    $('#edit_stock_saving_buttons').addClass('hide')
    $('#open_edit_stock').removeClass('hide')


    $('#locations_table .formatted_stock').removeClass('hide')
    $('#locations_table .stock_input').addClass('hide')

    $('#add_location_tr').addClass('hide')

    $('#locations_table  input.stock ').each(function (i, obj) {

        $(obj).val($(obj).attr('ovalue'))
        stock_changed($(obj))
    })


}

function move(element) {


    $('.locations  .fa-dot-circle-o ').each(function (i, obj) {
        $(obj).addClass('invisible')
    })


    //   $('#edit_stock_saving_buttons').addClass('hide')
    if ($(element).hasClass('fa-caret-square-o-right')) {

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
                $(obj).removeClass('fa-caret-square-o-right super_discreet  invisible').addClass('fa-caret-square-o-left')
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

    $('#edit_stock_saving_buttons').removeClass('hide')


    $('#move_from').html('')
    $('#move_to').html('')
    $('#locations_table  .fa-unlink ').removeClass('invisible')
    $('#locations_table  input.stock ').prop('readonly', false)
    $('#move_stock_tr').removeClass('valid invalid')
    $('#move_stock_qty').val('')
    $('#add_location_tr').removeClass('hide')

    if (movements) {


        var from_input = $('#locations_table  .from ').closest('tr').find('input.stock')

        old_from_input = from_input.val()
        from_input.val(parseFloat(from_input.val()) + parseFloat(movements.move_qty))

        stock_changed($(from_input))

        var to_input = $('#locations_table  .to ').closest('tr').find('input.stock')
        old_to_input = to_input.val()


        console.log($('#locations_table  .to '))
        //to_input.val('cc')
        to_input.val(parseFloat(to_input.val()) - +movements.move_qty)
        stock_changed(to_input)

    }
    $('#locations_table .move_trigger').removeClass('fa-caret-square-o-left from to').addClass('fa-caret-square-o-right very_discreet')
    $('#move_stock_tr').addClass('hide')


    $('.locations  .move_trigger ').each(function (i, obj) {
        $(obj).addClass('super_discreet')


        if ($(obj).closest('tr').find('input.stock').val() > 0) {
            $(obj).addClass('visible')
        }

    })
    $('.locations  .fa-dot-circle-o ').each(function (i, obj) {
        $(obj).addClass('visible')
    })

}


function move_qty_changed(element) {


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


            // console.log(to_input)
            // console.log(parseFloat(to_input.attr('ovalue'))  )
            to_input.val(parseFloat(to_input.attr('ovalue')) + move_qty)
            stock_changed(to_input)


            movements = {
                part_sku: $('#locations_table').attr('part_sku'),
                from_location_key: from_input.attr('location_key'),
                from_location_stock: old_from_input,
                to_location_key: to_input.attr('location_key'),
                to_location_stock: old_to_input,
                move_qty: move_qty
            }

        }


    }


}


function stock_field_changed(element) {

    stock_changed(element)
    $('.locations  .move_trigger ').each(function (i, obj) {


        if (!$(obj).hasClass('from')) {

            //console.log($(obj))
            $(obj).addClass('invisible')

        }

    })


}

function stock_changed(element) {


    var value = element.val()


    if (value == '') {
        value = 0
    }


    var validation = validate_number(value, 0, 999999999)

    if (!validation) {
        validation = {
            class: 'valid', type: 'valid'
        }
    }

    element.closest('tr').removeClass('valid invalid').addClass(validation.class)


    if (element.attr('ovalue') != value) {

        if (validation.class == 'invalid') {
            element.closest('tr').find('.stock_change').html('')
            element.closest('tr').find('.set_as_audit').addClass('super_discreet').addClass('hide')

        } else {

            // console.log(value)
            // console.log(element.attr('ovalue'))

            var _diff = parseFloat(value) - parseFloat((element.attr('ovalue') == '' ? 0 : element.attr('ovalue')))


            var diff = _diff.toFixed(2).replace(/[.,]00$/, "")

            if (_diff > 0) {
                diff = '+' + diff
            }

            var change = '(' + diff + ')';
            element.closest('tr').find('.stock_change').html(change)

            element.closest('tr').find('.set_as_audit').addClass('super_discreet').addClass('hide')

            element.closest('tr').find('.add_note').removeClass('super_discreet invisible').addClass('visible')


        }

    } else {
        element.closest('tr').find('.stock_change').html('')
        element.closest('tr').find('.set_as_audit').removeClass('hide')

    }

    process_edit_stock()

}

function process_edit_stock() {

    var total_new_stock = 0;
    var diff_up = 0
    var diff_down = 0

    var has_invalid = false;

    var set_as_audit = 0
    var disassociate = 0

    var potential_more_outs = 0;
    var editable_locations = 0;

    $('#locations_table  input.stock ').each(function (i, obj) {

        var can_move_out = true;
        var editable_location = true;

        if (!$(obj).closest('tr').find('.set_as_audit').hasClass('super_discreet')) {
            set_as_audit++;
            can_move_out = false;
            editable_location = false;


        }

        if (!$(obj).closest('tr').find('.unlink_operations i').hasClass('fa-unlink')) {
            disassociate++;
            can_move_out = false;
            editable_location = false;
        }


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

    if (disassociate == 1) {
        diff_msg = '<i class="fa fa-unlink" aria-hidden="true"></i> '

    } else if (disassociate > 1) {
        diff_msg = disassociate + '<i class="fa fa-unlink" aria-hidden="true"></i>'
    }


    if (set_as_audit == 1) {
        diff_msg += ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> '

    } else if (set_as_audit > 1) {
        diff_msg += set_as_audit + ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i>'
    }


    if (diff_down != 0) {
        diff_msg += ' (' + diff_down.toFixed(2).replace(/[.,]00$/, "") + ') '
    }
    if (diff_up != 0) {
        diff_msg += ' (+' + diff_up.toFixed(2).replace(/[.,]00$/, "") + ') '
    }

    $('#new_stock').html(total_new_stock)


    $('#stock_diff').html(diff_msg)

    if (has_invalid) {
        $('#edit_stock_saving_buttons').removeClass('valid changed').addClass('invalid')
    } else {
        $('#edit_stock_saving_buttons').removeClass('invalid')

        if (diff_down != 0 || diff_up != 0 || set_as_audit > 0 || disassociate > 0) {
            $('#edit_stock_saving_buttons').addClass('valid changed')
        } else {
            $('#edit_stock_saving_buttons').removeClass('valid changed')
        }
    }

}


function disassociate_location(element) {


    if ($(element).hasClass('fa-unlink')) {

        $(element).removeClass('fa-unlink').addClass('fa-chain')
        $(element).closest('tr').find('.stock').val('').attr('action', 'disassociate')
        $(element).closest('tr').find('.location_code').addClass('deleted')
        $(element).closest('tr').find('.move_trigger').addClass('invisible')
        $(element).closest('tr').find('input').prop('readonly', true)
        $(element).closest('tr').find('.set_as_audit').addClass('invisible')
        $(element).closest('tr').find('.recommendations').addClass('hide')
        stock_changed($(element).closest('tr').find('.stock'))
    } else {

        $(element).addClass('fa-unlink').removeClass('fa-chain')
        $(element).closest('tr').find('.stock').val($(element).closest('tr').find('.stock').attr('ovalue')).attr('action', '')
        $(element).closest('tr').find('.location_code').removeClass('deleted')
        $(element).closest('tr').find('.move_trigger').removeClass('invisible')
        $(element).closest('tr').find('input').prop('readonly', false)
        $(element).closest('tr').find('.set_as_audit').removeClass('invisible')
        $(element).closest('tr').find('.recommendations').removeClass('hide')
        stock_changed($(element).closest('tr').find('.stock'))
    }

}

function set_as_audit(element) {

    if ($(element).hasClass('super_discreet')) {
        $(element).removeClass('super_discreet')
        $(element).closest('tr').find('input').prop('readonly', true)
        $(element).closest('tr').find('.add_note').removeClass('super_discreet invisible').addClass('visible')

    } else {
        $(element).addClass('super_discreet')
        $(element).closest('tr').find('input').prop('readonly', false)

    }

    process_edit_stock()

}

function open_add_location() {

    if ($('#add_location_label').hasClass('hide')) {
        close_add_location()
    } else {

        $('#add_location_label').addClass('hide')
        $('#add_location').removeClass('hide').focus()
        $('#save_add_location').removeClass('hide')
    }

}

function close_add_location() {

    $('#add_location_label').removeClass('hide')
    $('#add_location').addClass('hide').val('')
    $('#save_add_location').addClass('hide')

}


function delayed_on_change_add_location_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        get_locations_select()
    }, timeout));
}

function get_locations_select() {

    $('#location_data_msg').removeClass('error').html('')

    $('#add_location_tr').removeClass('invalid')

    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_location').val()) + '&scope=locations&state=' + JSON.stringify(state)

    $.getJSON(request, function (data) {


        if (data.number_results > 0) {
            $('#add_location_results_container').removeClass('hide').addClass('show')
        } else {


            $('#add_location_results_container').addClass('hide').removeClass('show')

            //console.log(data)
            if ($('#add_location').val() != '') {
                $('#add_location_tr').addClass('invalid')
            }

            // $('#add_location').val('')
            // on_changed_value(field, '')
        }


        $("#add_location_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#add_location_search_result_template").clone()
            clone.prop('id', 'add_location_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            // clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            // clone.children(".code").html(data.results[result_key].code)
            clone.children(".label").html(data.results[result_key].description)

            $("#add_location_results").append(clone)


        }

    })


}

function select_add_location_option(element) {


    $('#add_location').val($(element).attr('formatted_value'))
    $('#save_add_location').attr('location_key', $(element).attr('value'))

    $('#save_add_location').addClass('valid')
    $('#add_location_tr').addClass('valid')
    $('#add_location_results_container').addClass('hide').removeClass('show')
    //console.log($(element).attr('value'))
    //console.log($('#save_add_location').attr('location_key'))
}

function save_add_location() {


    $('#save_add_location').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    var request = '/ar_edit_stock.php?tipo=new_part_location&object=part&part_sku=' + $('#locations_table').attr('part_sku') + '&location_key=' + $('#save_add_location').attr('location_key')
    console.log(request)
    //return;
    //=====
    var form_data = new FormData();
    form_data.append("tipo", 'new_part_location')
    form_data.append("object", 'part')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("location_key", $('#save_add_location').attr('location_key'))

    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {
        $('#save_add_location').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

        if (data.state == 200) {

            console.log(data)

            if (state.tab == 'part.stock.transactions') {
                rows.fetch({
                    reset: true
                });
            }
            close_add_location()


            var clone = $("#add_location_template").clone().removeClass('hide').addClass('locations').attr('id', 'part_location_edit_' + data.location_key).attr('location_key', data.location_key)

            clone.find(".location_info")

            clone.find(".picking_location_icon").html(data.picking_location_icon)
            clone.find(".location_code").html(data.location_code).attr('onclick', "change_view('" + data.location_link + "')")


            if (data.can_pick == 'No') {
                clone.find(".open_edit_min_max").addClass('hide')
            }

            clone.find(".formatted_recommended_min").html(data.formatted_min_qty)
            clone.find(".formatted_recommended_max").html(data.formatted_max_qty)
            clone.find(".recommended_min").attr('ovalue', data.min_qty).val(data.min_qty)
            clone.find(".recommended_max").attr('ovalue', data.max_qty).val(data.max_qty)

            if (data.can_pick == 'Yes') {
                clone.find(".open_edit_recommended_move").addClass('hide')
            }

            clone.find(".formatted_recommended_move").html(data.formatted_move_qty)

            clone.find(".recommended_move").attr('ovalue', data.move_qty).val(data.move_qty)


            clone.find(".formatted_stock").html(data.formatted_stock)
            clone.find(".stock").attr('location_key', data.location_key)
            clone.find('.add_note').removeClass('super_discreet invisible').addClass('visible')


            $("#add_location_template").before(clone)


            for (var key in data.updated_fields) {

                $('.' + key).html(data.updated_fields[key])
            }
        } else if (data.state == 400) {
            $('#location_data_msg').addClass('error').html(data.msg)
        }


    })

    request.fail(function (jqXHR, textStatus) {
    });


}

function save_stock(element) {


    if (!$('#edit_stock_saving_buttons').hasClass('valid')) {

        return;
    }

    $('#inventory_transaction_note').addClass('hide')
    $(element).removeClass('fa-cloud').addClass('fa-spinner fa-spin ')

    var icon = $(element);

    var parts_locations_data = []

    $('#locations_table tr.locations input.stock ').each(function (i, obj) {

        parts_locations_data.push({
            qty: $(obj).val(),
            location_key: $(obj).attr('location_key'),
            part_sku: $('#locations_table').attr('part_sku'),
            audit: ($(obj).closest('tr').find('.set_as_audit').hasClass('super_discreet') ? false : true),
            disassociate: ($(obj).closest('tr').find('.unlink_operations i').hasClass('fa-unlink') ? false : true),
            note: $(obj).closest('tr').find('.note').val()
        })
    })

    if (!movements) {
        var _movements = {}
    } else {
        var _movements = movements
    }

    // used only for debug
    var request = '/ar_edit_stock.php?tipo=edit_stock&object=part&key=' + $('#locations_table').attr('part_sku') + '&parts_locations_data=' + JSON.stringify(parts_locations_data) + '&movements=' + JSON.stringify(_movements)


    //=====
    var form_data = new FormData();
    form_data.append("tipo", 'edit_stock')
    form_data.append("object", 'part')
    form_data.append("key", $('#locations_table').attr('part_sku'))
    //        form_data.append("parent_key", $('#fields').attr('parent_key'))
    form_data.append("parts_locations_data", JSON.stringify(parts_locations_data))
    form_data.append("movements", JSON.stringify(_movements))

    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {


        if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock') {
            rows.fetch({
                reset: true
            });
        }


        icon.addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#edit_stock_saving_buttons').removeClass('valid')


        close_edit_stock()

        for (var key in data.updated_fields) {
            //console.log(key)
            //console.log(data.updated_fields[key])
            $('.' + key).html(data.updated_fields[key])
        }


        if (data.Part_Unknown_Location_Stock != 0) {
            $('#unknown_location_tr').removeClass('hide')
        }

        $('#Part_Unknown_Location_Stock').attr('qty',data.Part_Unknown_Location_Stock)

    })

    request.fail(function (jqXHR, textStatus) {
    });

}

//'Move','Order In Process','No Dispatched','Sale','Audit','In','Adjust','Broken','Lost','Not Found','Associate','Disassociate','Move In','Move Out','Other Out','Restock','FailSale'

function show_dialog_consolidate_unknown_location(element) {

    part_sku = $(element).attr('part_sku')




    qty = $(element).attr('qty')


    $('#edit_stock_dialog_consolidate_unknown_location').removeClass('hide').offset({
        top: $(element).position().top - 3, left: $(element).position().left + $(element).width() - $('#edit_stock_dialog_consolidate_unknown_location').width() - 20
    }).attr('part_sku', part_sku).attr('max_qty', qty)


    $('#edit_stock_dialog_consolidate_unknown_location').attr('factor',(qty>0?1:-1));

    $('#part_leakage_qty_input').val(Math.abs(qty))
    $('#part_leakage_note_input').val('')
    $('#unknown_location_save_buttons td').addClass('super_discreet').removeClass('button')



    if(qty>0){
        $('#unknown_location_save_buttons .label').removeClass('hide')

        $('#unknown_location_save_buttons .lost_error').removeClass('hide')
        $('#unknown_location_save_buttons .found_error').addClass('hide')


    }else{
        $('#unknown_location_save_buttons .label').addClass('hide')
        $('#unknown_location_save_buttons .label._error').removeClass('hide')

        $('#unknown_location_save_buttons .lost_error').addClass('hide')
        $('#unknown_location_save_buttons .found_error').removeClass('hide')


    }




}


$(document).on('input propertychange', '#part_leakage_qty_input', function () {
    validate_part_leakage()


});


$(document).on('input propertychange', '#part_leakage_note_input', function () {
    validate_part_leakage()


});



function open_sent_part_to_production(element){


    $('#edit_stock_dialog_to_production').removeClass('hide').offset({
        top: $(element).position().top - 3, left: $(element).position().left-$('#edit_stock_dialog_to_production').width()
    }).attr('max', $(element).attr('max')).find('.max').html($(element).attr('max'))
    $('#edit_stock_dialog_to_production').attr('location_key',$(element).attr('location_key'))
}



$(document).on('input propertychange', '#part_to_production_qty_input', function () {
    validate_part_to_production()


});


$(document).on('input propertychange', '#part_to_production_note_input', function () {
    validate_part_to_production()


});




function validate_part_to_production() {
    var error = false;
    max = $('#edit_stock_dialog_to_production').attr('max')


    var validate_qty=validate_number($('#part_to_production_qty_input').val(), 0,Math.abs(max));



    if (validate_qty) {
        error = true;
        $('#part_to_production_qty_input').addClass('error')

    } else {
        $('#part_to_production_qty_input').removeClass('error')
    }


    if ($('#part_to_production_note_input').val() == '') {
        error = true;
        $('#part_to_production_note_input').addClass('error')
    } else {
        $('#part_to_production_note_input').removeClass('error')

    }


    $('#edit_stock_dialog_to_production .save').addClass('changed')

    if (error) {
        $('#edit_stock_dialog_to_production .save').addClass('invalid').removeClass('valid')
    } else {
        $('#edit_stock_dialog_to_production .save').removeClass('invalid').addClass('valid')

    }
}




function validate_part_leakage() {
    var error = false;
    max = $('#edit_stock_dialog_consolidate_unknown_location').attr('max_qty')


    var validate_qty=validate_number($('#part_leakage_qty_input').val(), 0,Math.abs(max));


    console.log(validate_qty)

    if (validate_qty) {
        error = true;
        $('#part_leakage_qty_input').addClass('error')

    } else {
        $('#part_leakage_qty_input').removeClass('error')
    }


    if ($('#part_leakage_note_input').val() == '') {
        error = true;
        $('#part_leakage_note_input').addClass('error')
    } else {
        $('#part_leakage_note_input').removeClass('error')

    }
    if (error) {
        $('#unknown_location_save_buttons td').addClass('super_discreet').removeClass('button')
    } else {
        $('#unknown_location_save_buttons td').removeClass('super_discreet').addClass('button')

    }
}


function save_stock_dialog_to_production(element) {


    if (!$(element).hasClass('valid') ||  $(element).hasClass('wait')  ) {

        return;
    }

    $(element).addClass('wait')
    $(element).find('i').addClass('fa-spinner fa-spin')

    // used only for debug
    var request = '/ar_edit_stock.php?tipo=send_to_production&part_sku=' + $('#locations_table').attr('part_sku') + '&qty=' +$('#part_to_production_qty_input').val()+'&note='+$('#part_to_production_note_input').val()+'&location_key='+$('#edit_stock_dialog_to_production').attr('location_key')

    console.log(request)


    var form_data = new FormData();
    form_data.append("tipo", 'send_to_production')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("qty",$('#part_to_production_qty_input').val())
    form_data.append("note",$('#part_to_production_note_input').val())
    form_data.append("location_key",$('#edit_stock_dialog_to_production').attr('location_key'))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {


        if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock') {
            rows.fetch({
                reset: true
            });
        }



        $('#edit_stock_dialog_to_production').addClass('hide')

        $(element).removeClass('wait')
        $(element).find('i').removeClass('fa-spinner fa-spin')



        for (var key in data.updated_fields) {
            //console.log(key)
            //console.log(data.updated_fields[key])
            $('.' + key).html(data.updated_fields[key])
        }


        if (data.Part_Unknown_Location_Stock != 0) {
            $('#unknown_location_tr').removeClass('hide')
        }

    })

    request.fail(function (jqXHR, textStatus) {
    });

}

function save_leakage(element) {


    if ($(element).hasClass('super_discreet') ||  $(element).hasClass('wait')  ) {

        return;
    }

    $('#unknown_location_save_buttons td').addClass('super_discreet')
    $(element).addClass('wait').removeClass('super_discreet').find('span.label').addClass('hide')
    $(element).find('i').removeClass('hide')

    // used only for debug
    var request = '/ar_edit_stock.php?tipo=edit_leakages&part_sku=' + $('#locations_table').attr('part_sku') + '&qty=' +$('#part_leakage_qty_input').val()* $('#edit_stock_dialog_consolidate_unknown_location').attr('factor')+'&note='+$('#part_leakage_note_input').val()+'&type='+$(element).attr('type')

   console.log(request)

    //=====
    var form_data = new FormData();
    form_data.append("tipo", 'edit_leakages')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("qty",$('#part_leakage_qty_input').val()* $('#edit_stock_dialog_consolidate_unknown_location').attr('factor'))
    form_data.append("note",$('#part_leakage_note_input').val())
    form_data.append("type",$(element).attr('type'))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {


        if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock') {
            rows.fetch({
                reset: true
            });
        }

        $('#edit_stock_dialog_consolidate_unknown_location').addClass('hide');

        $('#unknown_location_save_buttons i').addClass('hide')
        $('#unknown_location_save_buttons span.label').removeClass('hide')


        $('#edit_stock_saving_buttons').removeClass('valid')


        close_edit_stock()

        for (var key in data.updated_fields) {
            //console.log(key)
            //console.log(data.updated_fields[key])
            $('.' + key).html(data.updated_fields[key])
        }


        if (data.Part_Unknown_Location_Stock != 0) {
            $('#unknown_location_tr').removeClass('hide')
        }

    })

    request.fail(function (jqXHR, textStatus) {
    });

}

function open_edit_min_max(element) {

    location_key = $(element).attr('location_key')
    min = $(element).attr('min')
    max = $(element).attr('max')


    $('#edit_stock_min_max').removeClass('hide').offset({
        top: $(element).position().top - 3, left: $(element).position().left
    }).attr('location_key', location_key)


    $('#edit_stock_min_max').find('.recommended_min').val(min).attr('ovalue', min)
    $('#edit_stock_min_max').find('.recommended_max').val(max).attr('ovalue', max)


//    $(element).addClass('invisible').next().removeClass('hide').find('input:first').focus()
//    $(element).closest('tr').find('.stock_input').addClass('hide')
//    $(element).closest('td').attr('colspan', 2)

    //   $(element).closest('tr').find('.unlink_operations').addClass('invisible')


}






function open_edit_recommended_move(element) {


    location_key = $(element).attr('location_key')
    recommended_move = $(element).attr('recommended_move')


    $('#edit_recommended_move').removeClass('hide').offset({
        top: $(element).position().top - 3, left: $(element).position().left
    }).attr('location_key', location_key)


    $('#edit_recommended_move').find('.recommended_move').val(recommended_move).attr('ovalue', recommended_move)


    //$(element).addClass('invisible').next().removeClass('hide').find('input:first').focus()
    //$(element).closest('tr').find('.stock_input').addClass('hide')
    //$(element).closest('td').attr('colspan', 2)
    //$(element).closest('tr').find('.unlink_operations').addClass('invisible')


}

function close_edit_recommended_move(element) {


    var recommended_move = $('#edit_recommended_move').find('.recommended_move').removeClass('valid invalid')
    recommended_move.val(recommended_move.attr('ovalue'))

    $('#edit_recommended_move').removeClass('valid invalid').addClass('hide')


}

function close_edit_min_max(element) {

    var min_input = $('#edit_stock_min_max').find('.recommended_min').removeClass('valid invalid')
    min_input.val(min_input.attr('ovalue'))
    var max_input = $('#edit_stock_min_max').find('.recommended_max').removeClass('valid invalid')
    max_input.val(max_input.attr('ovalue'))

    $('#edit_stock_min_max').removeClass('valid invalid').addClass('hide')


}


function min_max_changed(element) {


    var min_input = $('#edit_stock_min_max').find('.recommended_min')
    var max_input = $('#edit_stock_min_max').find('.recommended_max')

    var min_validation = client_validation('smallint_unsigned', false, min_input.val(), '')
    var max_validation = client_validation('smallint_unsigned', false, max_input.val(), '')


    //console.log(max_validation)
    if (min_input.val() != '' && max_input.val() != '' && min_validation.class == 'valid' && max_validation.class == 'valid' && parseFloat(min_input.val()) > parseFloat(max_input.val())) {

        min_validation.class = 'invalid'
        max_validation.class = 'invalid'

    }


    if (min_validation.class == 'invalid' || max_validation.class == 'invalid') {
        validation = 'invalid'
    } else {
        validation = 'valid'
    }

    min_input.removeClass('valid invalid').addClass(min_validation.class)
    max_input.removeClass('valid invalid').addClass(max_validation.class)


    //console.log($('#edit_stock_min_max'))
    $('#edit_stock_min_max').removeClass('valid invalid').addClass(validation).addClass('changed')


}


function recommended_move_changed(element) {

    var move_input = $('#edit_recommended_move').find('.recommended_move')
    var validation = client_validation('smallint_unsigned', false, move_input.val(), '')
    //console.log(validation)
    //  $('#edit_recommended_move').removeClass('valid invalid').addClass(validation.class)

    move_input.removeClass('valid invalid').addClass(validation.class)

    $('#edit_recommended_move').removeClass('valid invalid').addClass(validation.class).addClass('changed')

}

function save_recommendations(type, element) {

    if (type == 'min_max') {
        if (!$('#edit_stock_min_max').hasClass('valid')) {
            return
        }


        $('#edit_stock_min_max').find('.save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')
        var min = $('#edit_stock_min_max').find('.recommended_min').val()
        var max = $('#edit_stock_min_max').find('.recommended_max').val()
        var location_key = $('#edit_stock_min_max').attr('location_key')

        var value = JSON.stringify({
            min: min, max: max
        });
        var field = 'Part_Location_min_max'
    } else {

        if (!$('#edit_recommended_move').hasClass('valid')) {
            return
        }

        $('#edit_recommended_move').find('.save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')
        var value = $('#edit_recommended_move').find('.recommended_move').val()
        var location_key = $('#edit_recommended_move').attr('location_key')


        var field = 'Part_Location_Moving_Quantity'
    }


    var request = '/ar_edit.php?tipo=edit_field&object=part_location&key=' + $('#locations_table').attr('part_sku') + '_' + location_key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)

    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'edit_field')
    form_data.append("object", 'part_location')
    form_data.append("key", $('#locations_table').attr('part_sku') + '_' + location_key)
    form_data.append("field", field)
    form_data.append("value", value)

    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {
        // console.log(data)
        if (data.state == 200) {

            //console.log(type)
            if (type == 'min_max') {


                $('#part_location_edit_' + location_key).find('.open_edit_min_max').attr('min', data.value[0])
                $('#part_location_edit_' + location_key).find('.open_edit_min_max').attr('max', data.value[1])
                $('#part_location_edit_' + location_key).find('.formatted_recommended_min').html(data.formatted_value[0])
                $('#part_location_edit_' + location_key).find('.formatted_recommended_max').html(data.formatted_value[1])

                $('#edit_stock_min_max').find('.save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
                $('#edit_stock_min_max').find('.recommended_min').val(data.value[0]).attr('ovalue', data.value[0])
                $('#edit_stock_min_max').find('.recommended_max').val(data.value[1]).attr('ovalue', data.value[1])
                close_edit_min_max()

            } else {


                $('#edit_recommended_move').find('.save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

                $('#part_location_edit_' + location_key).find('.recommended_move').val(data.value).attr('ovalue', data.value)
                $('#part_location_edit_' + location_key).find('.formatted_recommended_move').html(data.formatted_value)

                close_edit_recommended_move($(element).closest('tr').find('.close_move'))
            }
        }

    })

    request.fail(function (jqXHR, textStatus) {
    });


}

function inventory_transaction_note_changed() {

    var element = $('#inventory_transaction_note').data('element')
    var textarea = $('#inventory_transaction_note').find('textarea')


    $(element).closest('tr').find('.note').val($(textarea).val())
    if ($(textarea).val() == '') {
        $(element).closest('tr').find('.add_note').addClass('fa-sticky-note-o').removeClass('fa-sticky-note')

    } else {
        $(element).closest('tr').find('.add_note').removeClass('fa-sticky-note-o').addClass('fa-sticky-note')
    }

}

function set_inventory_transaction_note(element) {
    $('#inventory_transaction_note').find('textarea').val($(element).closest('tr').find('.note').val())

    $(element).uniqueId()
    console.log($(element).attr('id'))
    if ($('#inventory_transaction_note').hasClass('hide') || $(element).attr('id') != $('#inventory_transaction_note').data('id')) {

        $('#inventory_transaction_note').data({
            'element': $(element), 'id': $(element).attr('id')
        })

        $('#inventory_transaction_note').removeClass('hide')

        var position = $(element).closest('tr').find('.stock').position();


        $('#inventory_transaction_note').css({
            'left': position.left - $('#inventory_transaction_note').width(), 'top': position.top
        })


        $('#inventory_transaction_note').find('textarea').focus()
    } else {
        $('#inventory_transaction_note').addClass('hide')

    }

}

function set_as_picking_location(part_sku, location_key) {

    var request = '/ar_edit_stock.php?tipo=set_as_picking_location&part_sku=' + part_sku + '&location_key=' + location_key
    $.getJSON(request, function (data) {

        if (data.state == 200) {

            for (i in data.part_locations_data) {
                console.log(data.part_locations_data[i])


                var tr = $('#part_location_edit_' + data.part_locations_data[i].location_key)

                var icon = tr.find('.picking_location_icon i')

                if (data.part_locations_data[i].can_pick == 'Yes') {

                    icon.removeClass('super_discreet_on_hover button').attr('title', data.part_locations_data[i].label)
                    tr.find('.open_edit_min_max').removeClass('hide')
                    tr.find('.open_edit_recommended_move').addClass('hide')

                } else {
                    icon.addClass('super_discreet_on_hover button').attr('title', data.part_locations_data[i].label)
                    tr.find('.open_edit_min_max').addClass('hide')
                    tr.find('.open_edit_recommended_move').removeClass('hide')

                }


            }

        }

    })

}