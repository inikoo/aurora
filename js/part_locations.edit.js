function open_edit_stock() {

    $('.part_stock_value_info').addClass('hide')



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


    $('.part_stock_value_info').removeClass('hide')


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
        diff_msg += ' <i class="far fa-dot-circle" aria-hidden="true"></i> '

    } else if (set_as_audit > 1) {
        diff_msg += set_as_audit + ' <i class="far fa-dot-circle" aria-hidden="true"></i>'
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

        if (state.tab == 'part.locations') {
            rows.fetch({reset: true});
        }

    })

    request.fail(function (jqXHR, textStatus) {
    });

}


function show_dialog_consolidate_unknown_location(element) {

    part_sku = $(element).attr('part_sku')




    qty = $(element).attr('qty')


    console.log($(element).offset().top)

    $('#edit_stock_dialog_consolidate_unknown_location').removeClass('hide').offset({
        top: $(element).offset().top - 3, left: $(element).offset().left + $(element).width() - $('#edit_stock_dialog_consolidate_unknown_location').width() - 20
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
        top: $(element).offset().top - 3, left: $(element).offset().left-$('#edit_stock_dialog_to_production').width()
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

        if (state.tab == 'part.locations') {
            rows.fetch({reset: true});
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
    form_data.append("warehouse_key", $('#locations_table').attr('part_sku'))

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

        if (state.tab == 'part.locations') {
            rows.fetch({reset: true});
        }

    })

    request.fail(function (jqXHR, textStatus) {
    });

}

function open_edit_min_max(element) {

    console.log($(element).offset())


    location_key = $(element).attr('location_key')
    min = $(element).attr('min')
    max = $(element).attr('max')


    $('#edit_stock_min_max').removeClass('hide').offset({
        top: $(element).offset().top - 3, left: $(element).offset().left
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

    console.log('caca')


    $('#edit_recommended_move').removeClass('hide').offset({
        top: $(element).offset().top - 3, left: $(element).offset().left
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
        $(element).closest('tr').find('.add_note').addClass('fa-sticky-note').removeClass('fa-sticky-note')

    } else {
        $(element).closest('tr').find('.add_note').removeClass('fa-sticky-note').addClass('fa-sticky-note')
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

        var position = $(element).closest('tr').find('.stock').offset();


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

            if (state.tab == 'part.locations') {
                rows.fetch({reset: true});
            }

        }

    })

}

function set_part_location_note_bis(element) {

    var element=$(element)
    var offset = element.offset()

    $('#set_part_location_note_bis').removeClass('hide').attr('key',element.attr('key')).offset({
        top: offset.top -7.5,
        left: offset.left +element.width()- $('#set_part_location_note_bis').width()-20}).data('element',element).find('textarea').val(element.closest('span').find('.picking_location_note_value').html())
}




$(document).on('input propertychange', '#set_part_location_note_bis', function () {


    $('#set_part_location_note_bis').find('i.save').addClass('changed valid')
})



function close_part_location_notes_bis(element) {
    $('#set_part_location_note_bis').addClass('hide')

}

function save_part_location_notes_bis(){

    if($('#set_part_location_note_bis').find('i.save').hasClass('valid')) {
        $('#set_part_location_note_bis').find('i.save').addClass('fa-spinner fa-spin').removeClass('valid changed')
        var request = '/ar_edit_stock.php?tipo=edit_part_location_note&part_location_code=' + $('#set_part_location_note_bis').attr('key') + '&note=' + $('#set_part_location_note_bis').find('textarea').val()



        $.getJSON(request, function (r) {


            close_part_location_notes_bis();

            console.log(r)
            $('#set_part_location_note_bis').find('i.save').removeClass('fa-spinner fa-spin')


            element = $('#set_part_location_note_bis').data('element');
            element.closest('span').find('.picking_location_note_value').html(r.value)
            if(r.value==''){
                element.addClass('super_discreet_on_hover far').removeClass('fas')
            }else{
                element.addClass('fas').removeClass('super_discreet_on_hover far')

            }


            if (state.tab == 'part.locations') {
                rows.fetch({reset: true});
            }


        });

    }

}


function process_part_locations_changed() {


    var warnings=0;
    var disassociate = 0
    var associate = 0



    $('#locations_table  locations').each(function (i, obj) {



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
        diff_msg += ' <i class="far fa-dot-circle" aria-hidden="true"></i> '

    } else if (set_as_audit > 1) {
        diff_msg += set_as_audit + ' <i class="far fa-dot-circle" aria-hidden="true"></i>'
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