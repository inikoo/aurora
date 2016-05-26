/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 17:50:46 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function open_edit_this_field(scope) {


    var field = $(scope).closest('tr').attr('field')

    // console.log($('#' + field + '_lock'))
    if ($('#' + field + '_lock').hasClass('hide')) {
        open_edit_field($('#fields').attr('object'), $('#fields').attr('key'), field)
    }
}


function open_edit_field(object, key, field) {


    var type = $('#' + field + '_container').attr('field_type')




    $('#' + field + '_formatted_value').addClass('hide')
    $('#' + field + '_edit_button').addClass('hide')
    $('#' + field + '_reset_button').removeClass('hide')
    $('#' + field + '_msg').html('').removeClass('success error')


    switch (type) {
    case 'string':
    case 'handle':
    case 'textarea':
    case 'email':
    case 'new_email':
    case 'numeric':
    case 'amount':
    case 'amount_margin':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
    case 'pin':
    case 'password':
    case 'dimensions':


        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'barcode':


        if ($('#' + field + '_value').val() == '') {

            $('#' + field + '_assign_available_barcode').removeClass('hide')
        }

        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'dropdown_select':
        $('#' + field + '_dropdown_select_label').removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'country_select':
        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')

        $('#' + field + '_field div.country-select.inside .flag-dropdown').css({
            'display': 'block'
        })

        break;




    case 'telephone':
    case 'new_telephone':

        $('#' + field).removeClass('hide')
        $('#' + field).focus()

        // console.log('#' + field + '_save_button')
        $('#' + field + '_save_button').removeClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'block'
        })

    case 'address':
    case 'country':
    case 'new_delivery_address':



        $('#' + field).removeClass('hide')

        $('#' + field + '_save_button').removeClass('hide')


        $('#' + field + '_field div.country-select.inside .flag-dropdown').css({
            'display': 'block'
        })

        break;

    case 'other_delivery_address':
        $('#' + field + '_field').removeClass('hide')

        $('#show_new_delivery_address_field').addClass('hide')
        $('#other_delivery_addresses_field').addClass('hide')


        $('#' + field).removeClass('hide')

        $('#' + field + '_save_button').removeClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
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
        // $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')


        break;
    case 'radio_option':
        $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'date':

        $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_datepicker').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;

    case 'working_hours':
        $('#working_hours').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'salary':
        $('#salary').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'parts_list':
        $('#parts_list').removeClass('hide')
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

function close_edit_this_field(scope) {
    close_edit_field($(scope).closest('tr').attr('field'))
}


function close_edit_field(field) {
    var type = $('#' + field + '_container').attr('field_type')

    $('#' + field + '_formatted_value').removeClass('hide')


    $('#' + field + '_edit_button').removeClass('hide')
    $('#' + field + '_reset_button').addClass('hide')


    $('#' + field + '_save_button').addClass('hide')



    switch (type) {
    case 'string':
    case 'handle':
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
    case 'numeric':
    case 'amount':
    case 'amount_margin':
    case 'dimensions':

        $('#' + field).addClass('hide')


        //$('#' + field + '_editor').removeClass('changed')
        break;

    case 'barcode':
        $('#' + field + '_assign_available_barcode').addClass('hide')
        $('#' + field).addClass('hide')
        break;

    case 'country_select':

        $('#' + field + '_field div.country-select.inside .flag-dropdown').css({
            'display': 'none'
        })
        $('#' + field).addClass('hide')


        //$('#' + field + '_editor').removeClass('changed')
        break;
    case 'dropdown_select':
        $('#' + field + '_dropdown_select_label').addClass('hide')
        // $('#' + field + '_save_button').removeClass('hide')
        break;


    case 'new_email':
        $('#new_email_formatted_value').html('')
        $('#new_email_value').val('')
        $('#new_email').val('')
        on_changed_value('new_email', '')
        $('#new_email_field').addClass('hide')
        $('#show_new_email_field').removeClass('hide')
        break;
    case 'telephone':
        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })

        break;
    case 'new_telephone':


        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })

        $('#new_telephone_field').addClass('hide')
        $('#show_new_telephone_field').removeClass('hide')

        break;

    case 'address':
    case 'country':



        $('#' + field).addClass('hide')

        $('#' + field + '_save_button').addClass('hide')
        $('#' + field + '_field div.country-select.inside .flag-dropdown').css({
            'display': 'block'
        })
        break;

    case 'other_delivery_address':

        $('#' + field + '_field').addClass('hide')

        $('#show_new_delivery_address_field').removeClass('hide')
        $('#other_delivery_addresses_field').removeClass('hide')

        $('#' + field).addClass('hide')

        $('#' + field + '_save_button').addClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })



        break;

    case 'new_delivery_address':

        $('#new_delivery_address_field').addClass('hide')

        $('#show_new_delivery_address_field').removeClass('hide')


        break;

    case 'pin_with_confirmation':
    case 'password_with_confirmation':


        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_confirm_button').addClass('hide')



        $('#' + field + '_confirm').addClass('hide')

        break;
    case 'option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formatted').addClass('hide')

        $('#' + field + '_options li.selected').removeClass('selected')
        $('#' + field + '_option_' + $('#' + field + '_value').val()).addClass('selected')

        $('#' + field + '_formatted').val($('#' + field + '_formatted_value').html())
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'radio_option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formatted').addClass('hide')




        $('#' + field + '_options li').attr('is_selected', 0)
        $('#' + field + '_options li  .checkbox').removeClass('fa-check-square-o').addClass('fa-square-o')

        var values = $('#' + field + '_value').val().split(",");

        for (var i = 0; i < values.length; i++) {

            $('#' + field + '_option_' + values[i]).attr('is_selected', 1)
            $('#' + field + '_option_' + values[i] + ' .checkbox').addClass('fa-check-square-o').removeClass('fa-square-o')




        }
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'date':
        $('#' + field + '_formatted').addClass('hide')
        $('#' + field + '_datepicker').addClass('hide')


        $('#' + field + '_formatted').val($('#' + field + '_formatted_value').html())
        $("#" + field + '_editor').removeClass('changed')
        var date = chrono.parseDate($('#' + field + '_formatted').val())

        var value = date.toISOString().slice(0, 10)
        $('#' + field + '_datepicker').datepicker("setDate", date);



        break;
    case 'working_hours':
        $('#working_hours').addClass('hide')

        break;
    case 'salary':
        $('#salary').addClass('hide')
        break;
    case 'parts_list':
        $('#parts_list').addClass('hide')
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
    //console.log(object)
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




    //console.log('changed: ' + field)
    var object = $('#fields').attr('object');

    if ($('#' + object + '_save').hasClass('hide')) {
        reset_controls()
    }
    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')

    if (type == 'date') {
        new_value = new_value + ' ' + $('#' + field + '_time').val()
    }


    if (new_value != $('#' + field + '_value').val()) {
        var changed = true;

        //$('#' + field + '_field').addClass('changed')
    } else {
        var changed = false;
        //$('#' + field + '_field').removeClass('changed')
    }


    $('#' + field).closest('tbody.address_fields').attr('has_been_changed', 1)

    $('#' + field).attr('has_been_changed', 1)


    $('#' + field + '_field').removeClass('invalid valid potentially_valid')





    var validation = validate(field, new_value)



    process_validation(validation, field, false)









}


function validate(field, value) {

    var field_data = $('#' + field + '_container')



    $('#' + field + '_field').addClass('waiting_validation changed')
    $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

    var server_validation = field_data.attr('server_validation')
    var parent = field_data.attr('parent')
    var parent_key = field_data.attr('parent_key')
    var _object = field_data.attr('object')
    var key = field_data.attr('key')
    var type = field_data.attr('field_type')

    if (field_data.hasClass('address_value')) {
        var required = field_data.closest('tbody.address_fields').attr('_required')

    } else {
        var required = field_data.attr('_required')
    }


    if (type == 'salary') {
        return validate_salary_components();
    } else {

        return validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)
    }


}


function process_validation(validation, field, final_value) {

    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')



    if (validation.class == 'potentially_valid' && final_value) {
        validation.class = 'invalid';

        if (type == 'salary') {

            $('#salary  input.salary_input_field').each(function(i, obj) {
                // console.log($(obj))
                if ($(obj).hasClass('potentially_valid')) {
                    $(obj).removeClass('potentially_valid').addClass('invalid')
                }


            });

        }

    }

    if (validation.class == 'waiting') return;

    $('#' + field + '_field').removeClass('waiting_validation')
    $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


    var msg = '';

    if (validation.class == 'valid') {

        $('#' + field).attr('has_been_valid', 1)
        $('#' + field + '_field').removeClass('invalid potentially_valid').addClass('valid')
        //console.log('#' + field + '_field')
    } else if (validation.class == 'invalid') {

        $('#' + field + '_field').removeClass('valid').addClass('invalid')

        //  console.log($('#' + field + '_' + validation.type + '_invalid_msg'))
        if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
            msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
        } else {

            msg = $('#invalid_msg').html()
        }
    }

    $('#' + field + '_msg').html(msg)


    if ($('#fields').hasClass('new_object')) {


        $('#' + $('#' + field + '_container').attr('object') + '_msg').html('')

        var form_validation = get_form_validation_state()
        process_form_validation(form_validation)
    }

    if ($('#inline_new_object').attr('object') != undefined) {
        //console.log(validation.class)
        $('#inline_new_object_msg').removeClass('invalid valid potentially_valid')
        $('#inline_new_object_msg').html(msg).addClass(validation.class).removeClass('hide')

        $('#inline_new_object').removeClass('invalid valid potentially_valid')
        $('#inline_new_object').addClass(validation.class)

    }




}


function show_invalid_messages(field) {
    var validation = validate(field, $('#' + field).val(), $('#' + field + '_container'))

    process_validation(validation, field, true)

}


function select_option(field, value, label) {
    $('#' + field).val(value)
    $('#' + field + '_formatted').val(label)
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
    var selected_formatted = [];


    $('#' + field + '_options li').each(function() {
        if ($(this).attr('is_selected') == 1) {
            count_selected++;
            selected.push($(this).attr('value'))
            selected_formatted.push($(this).attr('label'))
        }
    });


    if ($('#' + field + '_container').hasClass('new')) {
        $('#' + field + '_formatted').val(selected_formatted.sort().join())
    }

    $('#' + field).val(selected.sort().join())
    on_changed_value(field, selected.sort().join())


}

function set_this_as_main(scope) {

    set_as_main($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('tr').attr('field'))

}

function set_as_main(object, key, field) {
    var request = '/ar_edit.php?tipo=set_as_main&object=' + object + '&key=' + key + '&field=' + field


    $.getJSON(request, function(data) {


        //$('#' + field + '_save_button').addClass('fa-star').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {


            if (data.action == 'new_field') {
                if (data.new_fields) {

                    for (var key in data.new_fields) {

                        create_new_field(data.new_fields[key])

                    }
                }
            }



            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')

                }
            }

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            post_set_as_main(data)

        } else if (data.state == 400) {


        }
    })
}

function post_set_as_main(data) {

}

function save_this_field(scope) {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('tr').attr('field'))
}

function save_this_address(scope) {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('table').attr('field'))
}


function save_field(object, key, field) {

    var field_data = $('#' + field + '_container')

    var type = field_data.attr('field_type')
    required = field_data.attr('_required')
    var field_element = $('#' + field);
    var value = field_element.val()





    if (!$("#" + field + '_field').hasClass('changed')) {

        console.log('no_change :(' + field)
        return;
    }

    if (!$("#" + field + '_field').hasClass('valid')) {
        console.log('field invalid x')
        show_invalid_messages(field)

        return;
    }
    if (!$("#" + field + '_save_button').hasClass('fa-cloud')) {
        console.log('waiting for validation')

        return;
    }
    $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    if ($("#" + field + '_save_button').hasClass('potentially_valid')) {

        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('key')


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
        $('#' + field + '_field').addClass('invalid')
        $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

        return;
    }


    var metadata = {};

    if (type == 'date') {
        value = value + ' ' + $('#' + field + '_time').val()
    } else if (type == 'address' || type == 'new_delivery_address') {
        value = get_address_value(field)
    } else if (type == 'password' || type == 'password_with_confirmation' || type == 'password_with_confirmation_paranoid' || type == 'pin' || type == 'pin_with_confirmation' || type == 'pin_with_confirmation_paranoid') {
        value = sha256_digest(value)
    } else if (type == 'telephone') {
        value = $('#' + field).intlTelInput("getNumber");

    } else if (type == 'country_select') {
        value = $('#' + field).countrySelect("getSelectedCountryData").code

    }

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)
    $.getJSON(request, function(data) {




        $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {

            $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')




            $('#' + field + '_value').val(data.value)

            //console.log(field)
            $('.' + field).html(data.formatted_value)
            if (type == 'option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')


            } else if (type == 'radio_option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')


            } else if (type == 'dropdown_select') {
                //  $('#' + field + '').removeClass('current')
                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')


            } else {
                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')

            }



            if (data.action == 'deleted') {

                $('#' + field + '_edit_button').parent('.show_buttons').css('visibility', 'hidden')
                $('#' + field + '_label').find('.button').addClass('hide')

            }


            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')
                }

            }
            if (data.action == 'new_field') {
                if (data.new_fields) {

                    for (var key in data.new_fields) {

                        create_new_field(data.new_fields[key])

                    }
                }
            }


            close_edit_field(field)

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            if (data.deleted_fields) {
                for (var key in data.deleted_fields) {
                    delete_field(data.deleted_fields[key])
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

function create_new_field(_data) {






    console.log(_data)
    var clone_field = _data.field
    var clone = $('#' + _data.clone_from + '_field').clone()
    clone.prop('id', clone_field + '_field');
    clone.removeClass('hide')
    clone.attr('field', clone_field);

    $('#' + _data.clone_from + '_field').after(clone)



    clone.find('.label').prop('id', clone_field + '_label')
    clone.find('i.reset_button').prop('id', clone_field + '_reset_button')
    clone.find('i.edit_button').prop('id', clone_field + '_edit_button')
    clone.find('i.lock').prop('id', clone_field + '_lock')


    clone.find('td.container').prop('id', clone_field + '_container')

    clone.find('span.editor').prop('id', clone_field + '_editor')

    clone.find('span.formatted_value').prop('id', clone_field + '_formatted_value').removeClass(_data.clone_from).addClass(clone_field).html(_data.formatted_value)
    clone.find('input.unformatted_value').prop('id', clone_field + '_value').val(_data.value)


    if (_data.edit == 'string' || _data.edit == 'email' || _data.edit == 'new_email' || _data.edit == 'int_unsigned' || _data.edit == 'smallint_unsigned' || _data.edit == 'mediumint_unsigned' || _data.edit == 'int' || _data.edit == 'smallint' || _data.edit == 'mediumint' || _data.edit == 'anything' || _data.edit == 'numeric') {
        clone.removeClass('hide')
        clone.find('input.input_field').prop('id', clone_field).val(_data.value)
        clone.find('i.save').prop('id', clone_field + '_save_button').addClass(_data.edit)

        clone.find('span.msg').prop('id', clone_field + '_msg')

    } else if (_data.edit == 'telephone' || _data.edit == 'new_telephone') {

        clone.removeClass('hide')
        clone.find('input.input_field').prop('id', clone_field).val(_data.value)
        clone.find('i.save').prop('id', clone_field + '_save_button').addClass(_data.edit)

        clone.find('span.msg').prop('id', clone_field + '_msg')


        $("#" + clone_field).intlTelInput({
            utilsScript: "/js/libs/telephone_utils.js",
            defaultCountry: 'GB',
            preferredCountries: ['GB', 'US']
        });
        $("#" + clone_field).intlTelInput("setNumber", _data.value);


    } else if (_data.edit == 'address') {



        clone.find('table').prop('id', clone_field).attr('field', clone_field)



        //console.log(clone_field)
        clone.find('tr.recipient ').prop('id', clone_field + '_recipient')
        clone.find('tr.organization ').prop('id', clone_field + '_organization')
        clone.find('tr.addressLine1 ').prop('id', clone_field + '_addressLine1')
        clone.find('tr.addressLine2 ').prop('id', clone_field + '_addressLine2')
        clone.find('tr.sortingCode ').prop('id', clone_field + '_sortingCode')
        clone.find('tr.postalCode ').prop('id', clone_field + '_postalCode')
        clone.find('tr.dependentLocality ').prop('id', clone_field + '_dependentLocality')
        clone.find('tr.locality ').prop('id', clone_field + '_locality')
        clone.find('tr.administrativeArea ').prop('id', clone_field + '_administrativeArea')
        clone.find('tr.country ').prop('id', clone_field + '_country')
        clone.find('input.country_select ').prop('id', clone_field + '_country_select')


        var address_fields = jQuery.parseJSON(_data.value)

        $('#' + clone_field + '_recipient  input ').val(decodeEntities(address_fields['Address Recipient']))
        $('#' + clone_field + '_organization  input ').val(decodeEntities(address_fields['Address Organization']))
        $('#' + clone_field + '_addressLine1  input ').val(decodeEntities(address_fields['Address Line 1']))
        $('#' + clone_field + '_addressLine2  input ').val(decodeEntities(address_fields['Address Line 2']))
        $('#' + clone_field + '_sortingCode  input ').val(decodeEntities(address_fields['Address Sorting Code']))
        $('#' + clone_field + '_postalCode  input ').val(decodeEntities(address_fields['Address Postal Code']))
        $('#' + clone_field + '_dependentLocality  input ').val(decodeEntities(address_fields['Address Dependent Locality']))
        $('#' + clone_field + '_locality  input ').val(decodeEntities(address_fields['Address Locality']))
        $('#' + clone_field + '_administrativeArea  input ').val(decodeEntities(address_fields['Address Administrative Area']))
        var initial_country = address_fields['Address Country 2 Alpha Code'].toLowerCase();



        $('#' + clone_field + '_country_select').intlTelInput({
            initialCountry: initial_country,
            preferredCountries: $('#preferred_countries').val().split(',')
        });

        $('#' + clone_field + '_country_select').on("country-change", function(event, arg) {


            var country_name = $('#' + clone_field + '_country_select').intlTelInput("getSelectedCountryData").name
            var country_code = $('#' + clone_field + '_country_select').intlTelInput("getSelectedCountryData").iso2.toUpperCase()


            if (country_name.match(/\)\s+\(.+\)$/)) {
                country_name = country_name.replace(/\)\s+\(.+\)$/, ")")
            } else {
                country_name = country_name.replace(/\s+\(.+\)$/, "")

            }




            $('#' + clone_field + '_country  input.address_input_field ').val(country_code)



            update_address_fields(clone_field, country_code, hide_recipient_fields = false)
            $('#' + clone_field + '_country_select').val(country_name)
            if (arg != 'init') {

                on_changed_address_value(clone_field, clone_field + '_country', country_code)
            }

        });

        $('#' + clone_field + '_country_select').trigger("country-change", 'init');



    }

    if (_data.label != undefined) {
        $('#' + clone_field + '_label').html(_data.label)
    }

    post_create_action(_data)



}

function post_create_action(_data) {

}

function delete_field(data) {
    var field = data.field

    $('#' + field + '_field').addClass('hide')
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

    if (data.label != undefined) {
        $('#' + field + '_label').html(data.label)
    }

    if (data.placeholder != undefined) {
      
    
      $("#" + field).attr('placeholder',data.placeholder)
    }


 if (data.locked != undefined) {
      
        if(data.locked==1){
         $("#" + field+'_validation').addClass('hide')
         $("#" + field+'_locked_tag').removeClass('hide')
        }else{
           $("#" + field+'_validation').removeClass('hide')
         $("#" + field+'_locked_tag').addClass('hide')
        }
     
    }


    if (data.value != undefined) {

        if (type == 'date') {
            $('.' + field).html(data.formatted_value)
            $("#" + field + "_datepicker").datepicker("setDate", new Date(data.formatted_value));
            $("#" + field).val(data.value)
            $("#" + field + '_formatted').val(data.formatted_value)
        }
        if (type == 'option') {
            //console.log(data.formatted_value)
            $("#" + field + '_formatted_value').html(data.formatted_value)


            $('#' + field).val(data.value)
            $('#' + field + '_options li').removeClass('selected').removeClass('current')


            $('#' + field + '_option_' + data.value.replace(".", "\.")).addClass('selected').addClass('current')
        } else {

            $('.' + field).html(data.formatted_value)
            $("#" + field).val(data.value)
        }
    }
    post_update_field(data)

}

function post_update_field(data) {

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

    //console.log(time_components)
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


function update_address_fields(field, country_code, hide_recipient_fields) {

    var request = '/ar_address.php?tipo=fields_data&country_code=' + country_code
    // console.log(request)
    $.getJSON(request, function(data) {

        //console.log(field)
        if (data.state == 200) {



            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key)
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
                //                console.log(field_data)
                if (field_data.required) {




                    field_tr.find('.fa-asterisk').removeClass('hide')

                } else {
                    field_tr.find('.fa-asterisk').addClass('hide')

                }





                if (!field_data.render || (hide_recipient_fields && (key == 'recipient' || key == 'organization'))) {
                    field_tr.addClass('hide')
                } else {
                    field_tr.removeClass('hide')
                }
                field_tr.insertBefore('#' + field + '_country')


            }
        } else if (data.state == 400) {


        }
    })

}

function delayed_on_change_address_field(object, timeout) {

    var field = object.closest('table').attr('field');

    var address_field = object.closest('tr').attr('id');
    //console.log(object)
    var new_value = object.val()

    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {
        on_changed_address_value(field, address_field, new_value)
    }, timeout));
}


function get_address_value(field) {
    var value = {
        "Address Recipient": null,
        "Address Organization": null,
        "Address Line 1": null,
        "Address Line 2": null,
        "Address Sorting Code": null,
        "Address Postal Code": null,
        "Address Dependent Locality": null,
        "Address Locality": null,
        "Address Administrative Area": null,
        "Address Country 2 Alpha Code": null
    }

    $('#' + field + ' input.address_input_field').each(function(i, obj) {
        var tmp = $(obj)
        if (tmp.val() != '') {
            //value[tmp.attr('field_name')] = htmlEncode(tmp.val())
            value[tmp.attr('field_name')] = tmp.val()

        }


    });
    //console.log(value)
    return JSON.stringify(value);
}



function on_changed_address_value(field, address_field, new_address_field_value) {


    var new_value = get_address_value(field);

    console.log('xxxxx')
    //console.log($('#' + field + '_value').val())
    if (new_value != $('#' + field + '_value').val()) {
        $("#" + field + '_editor').addClass('changed')
        //console.log("#" + field + '_editor')
        var changed = true;

        $('#' + field + '_address_fields').attr('has_been_changed', 1)



    } else {
        $("#" + field + '_editor').removeClass('changed')
        var changed = false;
        $('#' + field + '_address_fields').attr('has_been_changed', 0)

    }





    $('#' + field + '_save_button').removeClass('invalid valid potentially_valid')
    $('#' + field + '_msg').removeClass('invalid valid potentially_valid')
    $("#" + field + '_validation').removeClass('invalid valid potentially_valid')


    if (changed) {
        $('#' + field + '_field').addClass('waiting_validation changed')
        $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

        var validation = validate_address(field)


        process_validation(validation, field, true)






    } else {
        $('#' + field + '_msg').html('')
    }




}




function set_directory_item_as_main(field) {
    set_as_main($('#fields').attr('object'), $('#fields').attr('key'), field)

}

function show_directory_item_edit(field) {

    open_edit_field($('#fields').attr('object'), $('#fields').attr('key'), field)
}

function delete_directory_item(directory_field, field) {


    var request = '/ar_edit.php?tipo=delete_object_component&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key') + '&field=' + field

    $.getJSON(request, function(data) {


        //$('#' + field + '_save_button').addClass('fa-star').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {

            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')

                }
            }





/*
            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            post_set_as_main(data)
*/
        } else if (data.state == 400) {


        }
    })

}

function show_sticky_note_edit_dialog(anchor) {
    console.log('==============')
    if ($('#edit_sticky_note_dialog').hasClass('hide')) {
        $('#edit_sticky_note_dialog').removeClass('hide')
        $('#sticky_note_value').focus()

        if (anchor == 'sticky_note_button') {
            var position = $('#' + anchor).position();


            $('#edit_sticky_note_dialog').css({
                'left': position.left - $('#edit_sticky_note_dialog').width(),
                'top': position.top + $('#' + anchor).height()
            })
        } else {
            var position = $('#showcase_sticky_note .sticky_note').position();
            $('#edit_sticky_note_dialog').css({
                'left': position.left,
                'top': position.top
            })
        }


    } else {

        close_sticky_note_dialog()
    }


}

function close_sticky_note_dialog() {
    $('#edit_sticky_note_dialog').addClass('hide')
}

function save_sticky_note() {


    var value = $('#sticky_note_value').val()
    var object = $('#edit_sticky_note_dialog').attr('object')
    var key = $('#edit_sticky_note_dialog').attr('key')
    var field = $('#edit_sticky_note_dialog').attr('field')

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)
    console.log(request)
    $.getJSON(request, function(data) {


        if (data.state == 200) {
            console.log(data)
            $('#sticky_note_value').val(data.value)
            $('#showcase_sticky_note .sticky_note').html(data.formatted_value)
            if (data.value == '') {
                $('#showcase_sticky_note').addClass('hide')
                $('#sticky_note_button').removeClass('hide')
            } else {
                $('#showcase_sticky_note').removeClass('hide')
                $('#sticky_note_button').addClass('hide')

            }

            close_sticky_note_dialog()
        } else if (data.state == 400) {


        }
    })

}

function delayed_on_change_dropdown_select_field(object, timeout) {
    var field = object.attr('id');

    var field_element = $('#' + field);
    var new_value = field_element.val()


    key_scope = {
        type: 'dropdown_select',
        field: field_element.attr('field')
    };


    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_dropdown_select(field, new_value)
    }, timeout));
}

function get_dropdown_select(dropdown_input, new_value) {

    var scope = $('#' + dropdown_input).attr('scope')
    var field = $('#' + dropdown_input).attr('field')
    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&state=' + JSON.stringify(state)

    $.getJSON(request, function(data) {


        if (data.number_results > 0) {
            $('#' + field + '_results_container').removeClass('hide').addClass('show')
        } else {



            $('#' + field + '_results_container').addClass('hide').removeClass('show')
            $('#' + field).val('')
            on_changed_value(field, '')
        }


        $("#" + field + "_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#" + field + "_search_result_template").clone()
            clone.prop('id', field + '_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            
            
            //  console.log(data.results[result_key].metadata)
            clone.data('metadata', data.results[result_key].metadata)
          
          
          
            clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".code").html(data.results[result_key].code)

            clone.children(".label").html(data.results[result_key].description)

            $("#" + field + "_results").append(clone)
            
            
            console.log($('#'+field + '_result_' + result_key).data('metadata'))


        }

    })


}

function select_dropdown_option(element) {


field=$(element).attr('field')
value=$(element).attr('value')
formatted_value=$(element).attr('formatted_value')
metadata=$(element).data('metadata')

    $('#' + field + '_dropdown_select_label').val(formatted_value)
    $('#' + field).val(value)
    on_changed_value(field, value)

    $('#' + field + '_results_container').addClass('hide').removeClass('show')




    if (metadata.other_fields) {
        for (var key in metadata.other_fields) {
            update_field(metadata.other_fields[key])
        }
    }

}




function assign_available_barcode(field) {
    var request = '/ar_edit.php?tipo=get_available_barcode'

    $.getJSON(request, function(data) {
        if (data.state == 200) {
            if (data.barcode_number == '') {

            } else {
                $('#' + field).val(data.barcode_number)
                $("#" + field + '_field').addClass('changed').addClass('valid')
                save_field($('#fields').attr('object'), $('#fields').attr('key'), field)
            }

        } else if (data.state == 400) {

        }


    })


}



function toggle_unlock_delete_object(element) {

    if ($(element).hasClass('fa-lock')) {
        $(element).removeClass('fa-lock').addClass('fa-unlock')
        $(element).nextAll('span:first').removeClass('disabled').addClass('button')
    } else {
        $(element).addClass('fa-lock').removeClass('fa-unlock')
        $(element).nextAll('span:first').addClass('disabled').removeClass('button')
    }
}


function delete_object(element) {
    if ($(element).hasClass('disabled')) {
        return
    }


    if (!$(element).find('i.fa').removeClass('fa-trash')) return;

    $(element).find('i.fa').removeClass('fa-trash').addClass('fa-spinner fa-spin')

    var request = '/ar_edit.php?tipo=delete&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key')

    $.getJSON(request, function(data) {
        if (data.state == 200) {
            change_view(state.request)

        } else if (data.state == 400) {
            $(element).find('i.fa').addClass('fa-trash').remove('fa-spinner fa-spin')

        }


    })


}
