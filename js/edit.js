/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 17:50:46 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function open_edit_field(object, key, field) {

    var type = $('#' + field + '_container').attr('field_type')
    var offset = $('#' + field + '_label').position();

    $('#' + field + '_formated_value').addClass('hide')
    $('#' + field + '_edit_button').addClass('hide')
    $('#' + field + '_reset_button').removeClass('hide')

    // $('#' + field).val($('#' + field + '_value').html())
    $('#' + field + '_msg').html('').removeClass('success error')

    switch (type) {
    case 'string':
    case 'textarea':
    case 'email':

    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
    case 'pin':
    case 'password':
        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'telephone':
        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        $('.intl-tel-input .flag-container').css({
            'display': 'block'
        })



        break;
    case 'pin_with_confirmation':
    case 'password_with_confirmation':
        $('#' + field).removeClass('hide')
        $('#' + field + '_confirm_button').removeClass('hide')
        $('#' + field).focus()

        break;

    case 'option':
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_formated').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')


        break;
    case 'radio_option':
        $('#' + field + '_formated').removeClass('hide')
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'date':

        $('#' + field + '_formated').removeClass('hide')
        $('#' + field + '_datepicker').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;

    case 'working_hours':
        $('#working_hours').removeClass('hide')

        break;
    case 'salary':
        $('#salary').removeClass('hide')

        break;

    default:

    }
    key_scope = {
        type: type,
        object: object,
        key: key,
        field: field
    };
}


function close_edit_field(field) {

    var type = $('#' + field + '_container').attr('field_type')

    $('#' + field + '_formated_value').removeClass('hide')


    $('#' + field + '_edit_button').removeClass('hide')
    $('#' + field + '_reset_button').addClass('hide')
    $('#' + field + '_save_button').addClass('hide')
    switch (type) {
    case 'string':
    case 'email':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
    case 'pin':
    case 'password':
    case 'textarea':

        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        break;



    case 'telephone':
        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('.intl-tel-input .flag-container').css({
            'display': 'none'
        })



    case 'pin_with_confirmation':
    case 'password_with_confirmation':


        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_confirm_button').addClass('hide')



        $('#' + field + '_confirm').addClass('hide')

        break;
    case 'option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formated').addClass('hide')

        $('#' + field + '_options li.selected').removeClass('selected')
        $('#' + field + '_option_' + $('#' + field + '_value').html()).addClass('selected')

        $('#' + field + '_formated').val($('#' + field + '_formated_value').html())
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'radio_option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formated').addClass('hide')




        $('#' + field + '_options li').attr('is_selected', 0)
        $('#' + field + '_options li  .checkbox').removeClass('fa-check-square-o').addClass('fa-square-o')

        var values = $('#' + field + '_value').html().split(",");

        for (var i = 0; i < values.length; i++) {

            $('#' + field + '_option_' + values[i]).attr('is_selected', 1)
            $('#' + field + '_option_' + values[i] + ' .checkbox').addClass('fa-check-square-o').removeClass('fa-square-o')




        }
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'date':
        $('#' + field + '_formated').addClass('hide')
        $('#' + field + '_datepicker').addClass('hide')


        $('#' + field + '_formated').val($('#' + field + '_formated_value').html())
        $("#" + field + '_editor').removeClass('changed')
        var date = chrono.parseDate($('#' + field + '_formated').val())

        var value = date.toISOString().slice(0, 10)
        $('#' + field + '_datepicker').datepicker("setDate", date);



        break;
    case 'working_hours':
        $('#working_hours').addClass('hide')

        break;
    case 'salary':
        $('#salary').addClass('hide')

        break;
    default:

    }

    $('#' + field + '_editor').removeClass('invalid valid')


    if (!$('#' + field + '_msg').hasClass('success')) {
        $('#' + field + '_msg').html('').addClass('hide')
    }

    key_scope = false

}

function delayed_on_change_field(object, timeout) {
    var field = object.attr('id');
    var field_element = $('#' + field);
    var new_value = field_element.val()

    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {
        on_changed_value(field, new_value)
    }, timeout));
}

function on_changed_confirm_value(field, confirm_value) {

    if (confirm_value != '') {
        $("#" + field + '_editor').addClass('changed')

    } else {
        $("#" + field + '_editor').removeClass('changed')

    }
    $('#' + field + '_editor').removeClass('invalid valid')

    var value = $('#' + field).val()
    if (value == confirm_value) {
        validation = 'valid'
    } else if (value.substring(0, confirm_value.length) == confirm_value) {
        validation = 'potentially_valid'
    } else {
        validation = 'invalid'
    }






    $('#' + field + '_editor').addClass(validation)

    if (validation == 'invalid') {
        if ($('#' + field + '_no_match_invalid_msg').length) {
            var msg = $('#' + field + '_no_match_invalid_msg').html()
        } else {
            var msg = $('#not_match_invalid_msg').html()
        }

        msg = msg + ' '

    } else {
        var msg = '';
    }
    $('#' + field + '_msg').html(msg)


}

function on_changed_value(field, new_value) {

    var object = $('#fields').attr('object');

    if ($('#' + object + '_save').hasClass('hide')) {
        reset_controls()
    }
    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')
    required = field_data.attr('_required')

    if (type == 'date') {
        new_value = new_value + ' ' + $('#' + field + '_time').val()
    }

    if (new_value != $('#' + field + '_value').html()) {
        $("#" + field + '_editor').addClass('changed')
        var changed = true;
    } else {
        $("#" + field + '_editor').removeClass('changed')
        var changed = false;
    }

    $('#' + field + '_save_button').removeClass('invalid valid potentially_valid')
    $('#' + field + '_msg').removeClass('invalid valid potentially_valid')
    $("#" + field + '_validation').removeClass('invalid valid potentially_valid')


    if (changed) {


        $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('object')

        var validation = validate_field(field, new_value, type, required, server_validation, parent, parent_key, _object, key)
        process_validation(validation, field, true)


    } else {
        $('#' + field + '_msg').html('')
    }

    if ($('#fields').hasClass('new_object')) {
        if (validation.class != 'waiting') check_if_form_is_valid()
    }


}


function process_validation(validation, field, mark_invalid_if_previously_valid) {
    if (validation.class == 'potentially_valid' && $('#' + field).attr('has_been_valid') == 1 && mark_invalid_if_previously_valid) {
        validation.class = 'invalid';
    }
    $('#' + field + '_save_button').addClass(validation.class)
    $("#" + field + '_validation').addClass(validation.class)
    $("#" + field + '_msg').addClass(validation.class)


    if (validation.class == 'waiting') {

    } else {

        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')
        if (validation.class == 'valid') {
            $('#' + field).attr('has_been_valid', 1)
        }


        if (validation.class == 'invalid') {



            if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
                var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
            } else {
                var msg = $('#invalid_msg').html()
            }
        } else {
            var msg = '';
        }
        $('#' + field + '_msg').html(msg)
    }
}



function select_option(field, value, label) {
    $('#' + field).val(value)
    $('#' + field + '_formated').val(label)
    $('#' + field + '_options li').removeClass('selected')


    $('#' + field + '_option_' + value.replace(".", "\.")).addClass('selected')

    if ($('#' + field + '_container').hasClass('new')) {
        $('#' + field + '_options').addClass('hide')
    }

    on_changed_value(field, value)

}

function select_radio_option(field, value, label) {

    var checkbox_option = $('#' + field + '_option_' + value);


    if (checkbox_option.attr('is_selected') == 1) {
        $('#' + field + '_option_' + value + ' .checkbox').removeClass('fa-check-square-o').addClass('fa-square-o')
        checkbox_option.attr('is_selected', 0)
    } else {

        $('#' + field + '_option_' + value + ' .checkbox').addClass('fa-check-square-o').removeClass('fa-square-o')
        checkbox_option.attr('is_selected', 1)

    }

    var count_selected = 0;
    var selected = [];
    var selected_formated = [];


    $('#' + field + '_options li').each(function() {
        if ($(this).attr('is_selected') == 1) {
            count_selected++;
            selected.push($(this).attr('value'))
            selected_formated.push($(this).attr('label'))
        }
    });


    if ($('#' + field + '_container').hasClass('new')) {
        $('#' + field + '_formated').val(selected_formated.sort().join())
    }

    $('#' + field).val(selected.sort().join())
    on_changed_value(field, selected.sort().join())


}







function save_field(object, key, field) {

    var field_data = $('#' + field + '_container')

    var type = field_data.attr('field_type')
    required = field_data.attr('_required')

    var field_element = $('#' + field);

    var value = field_element.val()



    if (!$("#" + field + '_editor').hasClass('changed')) {
        return;
    }

    if ($("#" + field + '_editor').hasClass('invalid')) {
        return;
    }
    if ($("#" + field + '_editor').hasClass('waiting')) {
        return;
    }

    if ($("#" + field + '_editor').hasClass('potentially_valid')) {

        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('object')

        var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)



        $('#' + field).attr('has_been_valid', 1)

        if ((type == 'password_with_confirmation' || type == 'in_with_confirmation') && !$('#' + field + '_confirm').hasClass('hide')) {

            if ($('#' + field + '_no_match_invalid_msg').length) {
                var msg = $('#' + field + '_no_match_invalid_msg').html()
            } else {
                var msg = $('#not_match_invalid_msg').html()
            }

            msg = msg + $('#' + field + '_cancel_confirm_button').html()


        } else {

            if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
                var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
            } else {
                var msg = $('#invalid_msg').html()
            }
        }

        $('#' + field + '_msg').html(msg)
        $('#' + field + '_editor').addClass('invalid')

        return;
    }


    var metadata = {};

    if (type == 'date') {
        value = value + ' ' + $('#' + field + '_time').val()
    } else if (type == 'password' || type == 'password_with_confirmation' || type == 'password_with_confirmation_paranoid' || type == 'pin' || type == 'pin_with_confirmation' || type == 'pin_with_confirmation_paranoid') {
        value = sha256_digest(value)
    } else if (type == 'telephone') {
        value = $('#' + field).intlTelInput("getNumber");
        metadata = {

            'extra_fields': [{
                field: field + '_Formated',
                value: fixedEncodeURIComponent($('#' + field).intlTelInput("getNumber", intlTelInputUtils.numberFormat.INTERNATIONAL))
            }]

        }
    }

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)

    $.getJSON(request, function(data) {
        console.log(request)
        if (data.state == 200) {

            $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
            $('#' + field + '_value').html(data.value)

            if (type == 'option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                $('.' + field).html(data.formated_value)

            } else if (type == 'radio_option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                $('.' + field).html(data.formated_value)

            } else if (type == 'date') {
                $('.' + field).html(data.formated_value)

            } else {
                $('.' + field).html(data.formated_value)

            }


            close_edit_field(field)

            if (data.other_fields) {
                for (var key in data.other_fields) {

                    update_field(data.other_fields[key])

                }
            }

            post_save_actions(field, data)

        } else if (data.state == 400) {
            $('#' + field + '_editor').removeClass('valid potentially_valid').addClass('invalid')

            $('#' + field + '_msg').html(data.msg).removeClass('hide')

        }
    })
}

function post_save_actions(field, data) {

    switch (field) {
    case 'User_Preferred_Locale':
        change_view(state.request, {
            'reload': true
        })
        break;


    default:

    }


}

function update_field(data) {

    var field = data.field
    var type = $('#' + field + '_container').attr('field_type')
    if (data.render) {
        $('#' + field + '_field').removeClass('hide')
    } else {
        $('#' + field + '_field').addClass('hide')
        close_edit_field(field)
    }

    if (type == 'date') {
        $('.' + field).html(data.formated_value)
        $("#" + field + "_datepicker").datepicker("setDate", new Date(data.formated_value));
        $("#" + field).val(data.value)
        $("#" + field + '_formated').val(data.formated_value)
    }
    if (type == 'option') {

    } else {
        $('.' + field).html(data.value_formated)
        $("#" + field).val(data.value)

    }
}

function hide_edit_field_msg(field) {
    $('#' + field + '_msg').html('').addClass('hide')
}

function confirm_field(field) {

    $('#' + field).addClass('hide')
    $('#' + field + '_confirm_button').addClass('hide')

    $('#' + field + '_confirm').removeClass('hide')
    $('#' + field + '_save_button').removeClass('hide')
    $('#' + field + '_editor').removeClass('invalid valid changed')

    $('#' + field + '_confirm').focus()

}

function cancel_confirm_field(field) {

    $('#' + field).removeClass('hide')
    $('#' + field + '_confirm_button').removeClass('hide')

    $('#' + field + '_confirm').addClass('hide')
    $('#' + field + '_save_button').addClass('hide')
    $('#' + field + '_editor').removeClass('invalid valid changed')
    $('#' + field + '_msg').html('')
    $('#' + field).val('')
    $('#' + field).attr('has_been_valid', 0)
    $('#' + field).focus()


}

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}

function addZero2dateComponent(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}


function clean_time(value) {

    var Reg = /^[0-9]{4}$/
    if (Reg.test(value)) {
        time_components = [value.substring(0, 2), value.substring(2, 4)]
    } else {
        var time_components = value.split(':');
    }

    var hours = addZero2dateComponent(parseInt(time_components[0]))
    var minutes = addZero2dateComponent(parseInt(time_components[1]))
    var seconds = '00';
    return hours + ':' + minutes + ':' + seconds


}



function add_minutes_to_time(time, minutes) {

    var time_components = time.split(':');
    var d = new Date(2000, 0, 1, time_components[0], parseInt(time_components[1]) + parseInt(minutes), time_components[2])
    time = addZero2dateComponent(d.getHours()) + ':' + addZero2dateComponent(d.getMinutes()) + ':00'
    return time;

}
