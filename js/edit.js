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

    console.log(type)

    switch (type) {
        case 'string':
        case 'handle':
        case 'textarea':
        case 'email':
        case 'new_email':
        case 'numeric':
        case 'amount':
        case 'amount_margin':
        case 'amount_percentage':
        case 'int_unsigned':
        case 'smallint_unsigned':
        case 'mediumint_unsigned':
        case 'numeric_unsigned':


        case 'int':
        case 'smallint':
        case 'mediumint':
        case 'pin':
        case 'password':
        case 'dimensions':
        case 'percentage':


            $('#' + field).removeClass('hide')
            $('#' + field).trigger('focus')
            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'editor':

            $('#editor_container_' + field).removeClass('hide')

            break;
        case 'upload':

            $('#edit_object_upload_' + field).removeClass('hide')

            break;
        case 'html_editor':
            $('#' + field).removeClass('hide')
            $('#' + field).trigger('focus')
            $('#' + field + '_save_button').removeClass('hide')

            break;
        case 'barcode':


            if ($('#' + field + '_value').val() == '') {

                $('#' + field + '_assign_available_barcode').removeClass('hide')
            }

            $('#' + field).removeClass('hide')
            $('#' + field).trigger('focus')
            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'dropdown_select':
            $('#' + field + '_dropdown_select_label').removeClass('hide').focus()

            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'country_select':
            $('#' + field).removeClass('hide')
            $('#' + field).trigger('focus')
            $('#' + field + '_save_button').removeClass('hide')

            $('#' + field + '_field div.country-select.inside .flag-dropdown').css({
                'display': 'block'
            })

            break;


        case 'telephone':
        case 'new_telephone':

            $('#' + field).removeClass('hide')
            $('#' + field).trigger('focus')

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
            $('#' + field).trigger('focus')

            break;

        case 'option':


            $('#' + field + '_add_other_option').removeClass('hide')
            $('#' + field + '_options').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')


            break;
        case 'option_multiple_choices':
            $('#' + field + '_formatted').removeClass('hide')
            $('#' + field + '_options').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')

            break;
        case 'date':

            $('#' + field + '_formatted').removeClass('hide')
            $('#' + field + '_datepicker').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')


            if ($('#' + field + '_eraser').attr('display') == 'yes' && $('#' + field + '_formatted').val() != '') {
                $('#' + field + '_eraser').removeClass('hide')
            }


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
            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'raw_materials':
            $('#raw_materials_list').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'mixed_recipients':
            $('#' + field + '_container').find('.mixed_recipients_container').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')
            break;
        case 'timezone':
            $('.timezone_edit').removeClass('hide')
            $('#' + field + '_save_button').removeClass('hide')

            break;

        default:

    }


    key_scope = {
        type: type, object: object, key: key, field: field
    };
}

function show_add_other_option(field) {

    if ($('#' + field + '_add_other_option').hasClass('fa-plus')) {
        $('#' + field).removeClass('hide')
        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_add_other_option').removeClass('fa-plus').addClass('fa-list-ul')
    } else {
        $('#' + field).addClass('hide')
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_add_other_option').addClass('fa-plus').removeClass('fa-list-ul')

    }

}


function close_edit_this_field(scope) {
    close_edit_field($(scope).closest('tr').attr('field'))
}


function close_edit_field(field) {


    console.log(field)

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
        case 'amount_percentage':
        case 'dimensions':
        case 'percentage':
        case 'numeric_unsigned':


            $('#' + field).addClass('hide')


            //$('#' + field + '_editor').removeClass('changed')
            break;
        case 'editor':

            $('#editor_container_' + field).addClass('hide')

            break;
        case 'upload':

            $('#edit_object_upload_' + field).addClass('hide')

            break;
        case 'html_editor':
            $('#' + field).addClass('hide')

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

            $('#' + field + '_results_container').addClass('hide').removeClass('show')

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
        case 'option_multiple_choices':


            $('#' + field + '_options').addClass('hide')
            $('#' + field + '_formatted').addClass('hide')


            $('#' + field + '_options li').attr('is_selected', 0)
            $('#' + field + '_options li  .checkbox').removeClass('fa-check-square').addClass('fa-square')

            var values = $('#' + field + '_value').val().split(",");

            for (var i = 0; i < values.length; i++) {

                $('#' + field + '_option_' + values[i]).attr('is_selected', 1)
                $('#' + field + '_option_' + values[i] + ' .checkbox').addClass('fa-check-square').removeClass('fa-square')


            }
            $("#" + field + '_editor').removeClass('changed')

            break;
        case 'date':
            $('#' + field + '_formatted').addClass('hide')
            $('#' + field + '_datepicker').addClass('hide')
            $('#' + field + '_eraser').addClass('hide')


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
        case 'raw_materials':
            $('#raw_materials_list').addClass('hide')
            break;
        case 'mixed_recipients':
            $('#' + field + '_container').find('.mixed_recipients_container').addClass('hide')
            break;
        case 'timezone':
            $('.timezone_edit').addClass('hide')

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

    object.data("timeout", setTimeout(function () {
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



    if(field=='Shipment_Shipper'){


        $('tr.shipper_service').addClass('hide')
        $('tr.shipper_service_'+new_value).removeClass('hide')


    }


    var object = $('#fields').attr('object');

    if ($('#' + object + '_save').hasClass('hide')) {
        reset_controls()
    }
    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')

    if (type == 'date' || type == 'date_interval') {
        new_value = new_value + ' ' + $('#' + field + '_time').val()
    }


    /*

    if (new_value != $('#' + field + '_value').val()) {
        var changed = true;

        $('#' + field + '_field').addClass('changed')
    } else {
        var changed = false;
        $('#' + field + '_field').removeClass('changed')
    }
*/

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
        var required = field_data.data('object_field_required')
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

            $('#salary  input.salary_input_field').each(function (i, obj) {
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
//        console.log('#' + field + '_field')
    } else if (validation.class == 'invalid') {

        $('#' + field + '_field').removeClass('valid').addClass('invalid')

        //console.log($('#' + field + '_' + validation.type + '_invalid_msg'))
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


function select_option(element, field, value) {

    $('#' + field).val(value)
    $('#' + field + '_options li').removeClass('selected')
    $(element).addClass('selected')



    on_changed_value(field, value)

}

function select_option_multiple_choices(field, value, label) {

    var checkbox_option = $('#' + field + '_option_' + value);


    if (checkbox_option.attr('is_selected') == 1) {
        $('#' + field + '_option_' + value + ' .checkbox').removeClass('fa-check-square').addClass('fa-square')
        checkbox_option.attr('is_selected', 0)
    } else {

        $('#' + field + '_option_' + value + ' .checkbox').addClass('fa-check-square').removeClass('fa-square')
        checkbox_option.attr('is_selected', 1)

    }

    var count_selected = 0;
    var selected = [];
    var selected_formatted = [];


    $('#' + field + '_options li').each(function () {
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


    $.getJSON(request, function (data) {
        console.log(data)

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

            switch (object) {
                case 'Customer Client':


                    if (data.action == 'set_main_contact_number_Mobile') {


                        $("#Customer_Client_Main_Plain_Mobile_display").insertAfter("#display_telephones");
                    } else if (data.action == 'set_main_contact_number_Telephone') {


                        $("#Customer_Client_Main_Plain_Telephone_display").insertAfter("#display_telephones");

                    }
                    break;
                case 'Customer':

                    if (data.action == 'set_main_contact_number_Mobile') {


                        $("#Customer_Main_Plain_Mobile_display").insertAfter("#display_telephones");
                    } else if (data.action == 'set_main_contact_number_Telephone') {


                        $("#Customer_Main_Plain_Telephone_display").insertAfter("#display_telephones");

                    } else if (data.action == 'set_main_delivery_address') {


                        var address_fields = JSON.parse(data.value)

                        $('#Customer_Delivery_Address_recipient  input ').val(decodeEntities(address_fields['Address Recipient']))
                        $('#Customer_Delivery_Address_organization  input ').val(decodeEntities(address_fields['Address Organization']))
                        $('#Customer_Delivery_Address_addressLine1  input ').val(decodeEntities(address_fields['Address Line 1']))
                        $('#Customer_Delivery_Address_addressLine2  input ').val(decodeEntities(address_fields['Address Line 2']))
                        $('#Customer_Delivery_Address_sortingCode  input ').val(decodeEntities(address_fields['Address Sorting Code']))
                        $('#Customer_Delivery_Address_postalCode  input ').val(decodeEntities(address_fields['Address Postal Code']))
                        $('#Customer_Delivery_Address_dependentLocality  input ').val(decodeEntities(address_fields['Address Dependent Locality']))
                        $('#Customer_Delivery_Address_locality  input ').val(decodeEntities(address_fields['Address Locality']))
                        $('#Customer_Delivery_Address_administrativeArea  input ').val(decodeEntities(address_fields['Address Administrative Area']))

                        $('#Customer_Delivery_Address_country_select').intlTelInput("setCountry", address_fields['Address Country 2 Alpha Code'].toLowerCase());
                        $('#Customer_Delivery_Address_country_select').trigger("country-change", 'init');

                    }
                    break;
                case 'Supplier':
                    if (data.action == 'set_main_contact_number_Mobile') {
                        $("#Supplier_Main_Plain_Mobile_display").insertAfter("#display_telephones");
                    } else if (data.action == 'set_main_contact_number_Telephone') {
                        $("#Supplier_Main_Plain_Telephone_display").insertAfter("#display_telephones");
                    }
                    break;
                case 'Agent':
                    if (data.action == 'set_main_contact_number_Mobile') {
                        $("#Agent_Main_Plain_Mobile_display").insertAfter("#display_telephones");
                    } else if (data.action == 'set_main_contact_number_Telephone') {
                        $("#Agent_Main_Plain_Telephone_display").insertAfter("#display_telephones");
                    }
                    break;

            }



        } else if (data.state == 400) {


        }
    })
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
    var required = field_data.data('object_field_required')
    var field_element = $('#' + field);
    var value = field_element.val()


    if (!$("#" + field + '_field').hasClass('changed')) {
        console.log($("#" + field + '_field'))
        console.log('>>>>>>no_change :(' + field)
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

    console.log(type)

    if (type == 'date') {
        value = value + ' ' + $('#' + field + '_time').val()
    } else if (type == 'address' || type == 'new_delivery_address' || type == 'other_delivery_address') {
        value = get_address_value(field)
    } else if (type == 'password' || type == 'password_with_confirmation' || type == 'password_with_confirmation_paranoid' || type == 'pin' || type == 'pin_with_confirmation' || type == 'pin_with_confirmation_paranoid') {
        value = sha256_digest(value)
    } else if (type == 'telephone') {
        value = $('#' + field).intlTelInput("getNumber");

    } else if (type == 'country_select') {
        value = $('#' + field).countrySelect("getSelectedCountryData").code

    } else if (type == 'parts_list') {
        var part_list_data = [];

        $('#parts_list_items  tr.part_tr').each(function (i, obj) {

            if (!$(obj).find('.sku').val()) return true;

            if ($(obj).hasClass('very_discreet')) {
                var ratio = 0;
            } else {
                var ratio = $(obj).find('.parts_per_product').val()
            }
            var part_data = {
                'Key': $(obj).find('.product_part_key').val(), 'Part SKU': $(obj).find('.sku').val(), 'Ratio': ratio, 'Note': $(obj).find('.note').val(),

            }
            part_list_data.push(part_data)

        });


        value = JSON.stringify(part_list_data)

    } else if (type == 'raw_materials') {
        var raw_material_list_data = [];

        $('#raw_materials_list  tr.raw_material_tr').each(function (i, obj) {

            if (!$(obj).find('.raw_material_key').val()) return true;

            if ($(obj).hasClass('very_discreet')) {
                var ratio = 0;
            } else {
                var ratio = $(obj).find('.raw_material_qty').val()
            }

            var part_data = {
                'Key': $(obj).find('.production_part_raw_material_key').val(), 'Production Part': $(obj).find('.raw_material_key').val(), 'Ratio': ratio, 'Note': $(obj).find('.note').val(),

            }
            raw_material_list_data.push(part_data)

        });


        value = JSON.stringify(raw_material_list_data)

    } else if (type == 'mixed_recipients') {

        external_emails = [];
        user_keys = [];


        var mixed_recipients_container = $('#' + field + '_field').find('.mixed_recipients_container')

        $('.external_email_mixed_recipients_value', mixed_recipients_container).each(function (i, obj) {


            if (!$(obj).closest('tr').hasClass('very_discreet')) {

                if ($(obj).val() != '') {
                    external_emails.push($(obj).val())
                }
            }

        });

        $('.user_key', mixed_recipients_container).each(function (i, obj) {


            if (!$(obj).closest('tr').hasClass('very_discreet')) {

                if ($(obj).val() != '') {
                    user_keys.push($(obj).val())
                }
            }

        });

        var mixed_recipients = {
            external_emails: external_emails, user_keys: user_keys
        }

        value = JSON.stringify(mixed_recipients)

    }

    if ($('#fields').attr('form_type') == 'setup') {
        var request_file = '/ar_upload.php';
        //var request = '/ar_setup.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)

    } else {

        var request_file = '/ar_edit.php';
        //var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)
    }

    // console.log(request)


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", object)
    ajaxData.append("key", key)
    ajaxData.append('field', field)
    ajaxData.append('value', value)
    ajaxData.append('metadata', JSON.stringify(metadata))

    $.ajax({
        url: request_file, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


        complete: function () {

        },
        success: function (data) {

            $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
            if (data.state == 100) {
                pre_save_actions(field, data)

            }
            if (data.state == 200) {


                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
                $('#' + field + '_value').val(data.value)


                $("#" + field + '_field').removeClass('changed')
                $("#" + field + '_field').removeClass('valid')


//            console.log(data.formatted_value)
                $('.' + field).html(data.formatted_value)
                if (type == 'option') {

                    //   $('#' + field + '_options li .current_mark')
                    $('#' + field + '_options li i.current_mark').removeClass('current')
                    $('#' + field + '_options li.selected  i.current_mark').addClass('current')

                    // console.log('#' + field + '_option_' + value + ' .current_mark')
                    //  $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                } else if (type == 'option_multiple_choices') {
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

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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


                post_save_actions(field, data)

            } else if (data.state == 400) {
                $('#' + field + '_editor').removeClass('valid potentially_valid').addClass('invalid')

                $('#' + field + '_msg').html(data.msg).removeClass('hide')

            }

        }, error: function () {

        }
    });


}

function pre_save_actions(field, data) {

}

function post_save_actions(field, data) {

    console.log(field)
    console.log(data)


    switch (field) {

        case 'Invoice_Recargo_Equivalencia':
        case 'Invoice_Tax_Number':
        case 'Invoice_Address':

            change_view(state.request, {
                reload_showcase: 1
            })
            break;
        case 'Prospect_Customer_Key':
            change_view(state.request, {
                reload_showcase: 1
            })

            break;

        case 'User_Preferred_Locale':
            change_view(state.request, {
                'reload': true
            })
            break;
        case 'Product_Parts':
            post_save_product_parts(data)
            break;
        case 'Raw_Materials':
            post_save_raw_materials_list(data)
            break;
        case 'Staff_Type':
            if (state.section == 'contractor' && data.value != 'Contractor') {
                change_view('/employee/' + state.key)
            }
            if (state.section == 'employee' && data.value == 'Contractor') {
                change_view('/contractor/' + state.key)
            }
            break;
        case 'Staff_Currently_Working':
            if (state.section == 'employee') {
                change_view('/employee/' + state.key, {
                    reload_showcase: 1
                })
            }
            if (state.section == 'contractor') {
                change_view('/contractor/' + state.key, {
                    reload_showcase: 1
                })
            }
            break;
        case 'Supplier_Part_Supplier_Key':
            change_view(data.update_metadata.request, {
                reload_showcase: 1
            })

        default:

    }


}

function create_new_field(_data) {


    //console.log(_data)
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
            utilsScript: "/js_libs/telephone_utils.js", defaultCountry: 'GB', preferredCountries: ['GB', 'US']
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


        var address_fields = JSON.parse(_data.value)

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
            initialCountry: initial_country, preferredCountries: $('#preferred_countries').val().split(',')
        });

        $('#' + clone_field + '_country_select').on("country-change", function (event, arg) {


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


    var clone = $('#' + data.clone_from + '_display').clone()
    clone.prop('id', data.field + '_display').removeClass('hide');

    if (data.clone_from == 'Customer_Other_Email') {
        value = clone.find('.Customer_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Customer_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }else if (data.clone_from == 'Supplier_Other_Email') {
        value = clone.find('.Supplier_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    }else if (data.clone_from == 'Supplier_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }else if (data.clone_from == 'Agent_Other_Email') {
        value = clone.find('.Agent_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Agent_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }


    $('#' + data.clone_from + '_display').before(clone)




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


    if (data.required != undefined) {

        if (data.required) {
            $("#" + field + '_validation').addClass('required')
            $("#" + field + '_container').data('object_field_required', true)

        } else {

            $("#" + field + '_validation').removeClass('required')
            $("#" + field + '_container').data('object_field_required', false)

        }

    }

    if (data.placeholder != undefined) {


        $("#" + field).attr('placeholder', data.placeholder)
    }

    if (data.server_validation != undefined) {


        $("#" + field + '_container').attr('server_validation', data.server_validation)
    }

    if (data.options != undefined) {


        $("#" + field + '_options ul ').html(data.options)
    }


    if (data.locked != undefined) {

        if (data.locked == 1) {
            $("#" + field + '_validation').addClass('hide')
            $("#" + field + '_locked_tag').removeClass('hide')
            $("#" + field).attr("readonly", true);
        } else {
            $("#" + field + '_validation').removeClass('hide')
            $("#" + field + '_locked_tag').addClass('hide')
            $("#" + field).attr("readonly", false);

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



    switch ($('#' + data.field + '_container').attr('object')){
        case 'Customer':
            customer_post_update_field(data);
            break;
        case 'Agent':
            agent_post_update_field(data);
            break;
        case 'Part':
            part_post_update_field(data);
            break;
        case 'Supplier':
            supplier_post_update_field(data);
            break;

    }

}

function customer_post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Customer_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Customer_Main_Plain_Mobile' || data.field == 'Customer_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Customer_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Customer_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Customer_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Customer_Other_Telephone') {
                if (data.value != '') {
                    $('#' + data.field + '_display').find('span').html(data.formatted_value)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            }
        }

    }
}

function agent_post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Agent_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Agent_Main_Plain_Mobile' || data.field == 'Agent_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Agent_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Agent_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Agent_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Agent_Other_Telephone') {
                if (data.value != '') {
                    $('#' + data.field + '_display').find('span').html(data.formatted_value)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            }
        }

    }
}

function part_post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Part_Barcode_Number') {

            if (data.value == '') {
                $('#barcode_data').addClass('hide')
            } else {
                $('#barcode_data').removeClass('hide')

                if (data.barcode_key) {
                    $('#barcode_data').find('.barcode_labels').removeClass('hide')
                    $('#barcode_data').find('td.label i').addClass('button').attr("onclick", "change_view('inventory/barcode/" + data.barcode_key + "')")
                } else {
                    $('#barcode_data').find('.barcode_labels').addClass('hide')
                    $('#barcode_data').find('td.label i').removeClass('button').attr("onclick", "return false")

                }

            }


        }

    }
}

function supplier_post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Supplier_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Supplier_Main_Plain_Mobile' || data.field == 'Supplier_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Supplier_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Supplier_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Supplier_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Supplier_Other_Telephone') {
                if (data.value != '') {
                    $('#' + data.field + '_display').find('span').html(data.formatted_value)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            }
        }

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
    $('#' + field).trigger('focus')


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
    $.getJSON(request, function (data) {

        //console.log(field)
        if (data.state == 200) {


            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key)
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
//                             console.log(field_data)
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
    object.data("timeout", setTimeout(function () {
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

    $('#' + field + ' input.address_input_field').each(function (i, obj) {
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

    $.getJSON(request, function (data) {


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



        } else if (data.state == 400) {


        }
    })

}


function delayed_on_change_dropdown_select_field(object, timeout) {
    var field = object.attr('id');


    var field_element = $('#' + field);
    var new_value = field_element.val()

    key_scope = {
        type: 'dropdown_select', field: field_element.attr('field')
    };


    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        get_dropdown_select(field, new_value)
    }, timeout));
}

function get_dropdown_select(dropdown_input, new_value) {


    var parent_key = $('#' + dropdown_input).attr('parent_key')
    var parent = $('#' + dropdown_input).attr('parent')
    var scope = $('#' + dropdown_input).attr('scope')
    var field = $('#' + dropdown_input).attr('field')
    var metadata = $('#' + dropdown_input).data('metadata')


    console.log(metadata)

    if (metadata == undefined) {
        metadata = {}
    }

    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&metadata=' + JSON.stringify(metadata) + '&state=' + JSON.stringify(state)


    if ($('#' + dropdown_input).attr('action') != undefined) {
        request += '&action=' + $('#' + dropdown_input).attr('action');
    }


    $.getJSON(request, function (data) {


        if (data.number_results > 0) {


            $('#' + field + '_results_container').removeClass('hide').addClass('show')
            $('#' + field + '_msg').html('').addClass('hide')
            $('#' + field).val('')
            on_changed_value(field, new_value)


        } else {

            if (new_value.length > 0) {


                if ($('#' + dropdown_input).attr('create_new') == 1) {

                    if ($('#' + field + '_new_object_invalid_msg').length) {
                        msg = $('#' + field + '_new_object_invalid_msg').html()
                        $('#' + field + '_msg').html(msg).removeClass('hide')
                    }


                } else {


                    $('#' + field + '_results_container').addClass('hide').removeClass('show')
                    $('#' + field).val('')
                    on_changed_value(field, '__error__')
                }
            } else {
                $('#' + field + '_results_container').addClass('hide').removeClass('show')


                $('#' + field).val('')
                on_changed_value(field, '')
            }
        }


        $("#" + field + "_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#" + field + "_search_result_template").clone()
            clone.prop('id', field + '_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)


            // console.log(data.results[result_key].metadata)
            clone.data('metadata', data.results[result_key].metadata)


            clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }


            clone.children(".code").html(data.results[result_key].code)

            clone.children(".label").html(data.results[result_key].description)


            $("#" + field + "_results").append(clone)


            //   console.log($('#' + field + '_result_' + result_key).data('metadata'))
        }

    })


}

function select_dropdown_option(element) {


    field = $(element).attr('field')
    value = $(element).attr('value')
    formatted_value = $(element).attr('formatted_value')
    metadata = $(element).data('metadata')


    $('#' + field + '_dropdown_select_label').val(formatted_value)


    $('#' + field).val(value)
    on_changed_value(field, value)

    $('#' + field + '_results_container').addClass('hide').removeClass('show')

    console.log(metadata)
    if (metadata != undefined) {

        if (metadata.other_fields) {
            for (var key in metadata.other_fields) {
                update_field(metadata.other_fields[key])
            }
        }
    }
}


function assign_available_barcode(field) {
    var request = '/ar_edit.php?tipo=get_available_barcode'

    $.getJSON(request, function (data) {
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


function not_authorised_toggle_unlock_delete_object(element, right_code,right_scope) {

    var _labels = $(element).data('labels');


    var request = '/ar_find.php?tipo=users_with_right&right=' + right_code
    $.getJSON(request, function (data) {
        var a = [];
        $.each(data.users_data, function (key, value) {
            if (value['UIR'] == 'No') {
                a.push(value['User Alias'])
            }
        });
        var authorised_users = a.join(', ');
        footer_text = _labels.footer + authorised_users


        Swal.fire({
            type: 'error', title: _labels.title, text: _labels.text, footer: footer_text,
        })

    })

}

function toggle_unlock_delete_object(element) {

    if ($(element).hasClass('fa-lock-alt')) {
        $(element).removeClass('fa-lock-alt').addClass('fa-unlock')
        $(element).nextAll('span:first').removeClass('disabled').addClass('button')
    } else {
        $(element).addClass('fa-lock-alt').removeClass('fa-unlock')
        $(element).nextAll('span:first').addClass('disabled').removeClass('button')
    }
}


function toggle_prospect_link_to_customer(element) {


    var field = 'Prospect_Customer_Key'
    if ($(element).hasClass('fa-lock')) {
        $(element).removeClass('fa-lock').addClass('fa-unlock')
        $(element).nextAll('span:first').removeClass('disabled').addClass('button')


        open_edit_field($('#fields').attr('object'), $('#fields').attr('key'), field)


    } else {
        $(element).addClass('fa-lock').removeClass('fa-unlock')
        $(element).nextAll('span:first').addClass('disabled').removeClass('button')


        close_edit_field(field)

    }
}


function delete_object(element) {
    save_object_operation('delete', element)
}


function clean_cache(element) {
    save_object_operation('clean_cache', element)
}

function approve_object(element) {
    save_object_operation('approve', element)
}


function reject_object(element) {
    save_object_operation('reject', element)
}

function suspend_object(element) {
    save_object_operation('suspend', element)
}

function activate_object(element) {
    save_object_operation('activate', element)
}

function set_up_raw_material_object(element) {
    save_object_operation('set_up_raw_material', element)
}


function finish_object(element) {
    save_object_operation('finish', element)
}

function suspend_parent_object(element) {
    save_object_operation('suspend_parent', element)
}

function activate_parent_object(element) {
    save_object_operation('activate_parent', element)
}

function reset_object(element) {
    save_object_operation('reset', element)
}


function reindex_object(element) {
    save_object_operation('reindex', element)
}

function archive_object(element) {
    save_object_operation('archive', element)
}

function unarchive_object(element) {
    save_object_operation('unarchive', element)
}

function unlink_customer_object(element) {
    save_object_operation('unlink_customer', element)
}

function set_object_policy(type,element) {
    save_object_operation('policy', element,{"policy":type})
}


function save_object_operation(type, element, metadata) {


    console.log(type)
    console.log(metadata)

    if ($(element).hasClass('disabled')) {
        return
    }


    if (type == 'delete') var icon = 'fa-trash-alt'; else if (type == 'policy') var icon = 'fa-arrow-circle-right'; else if (type == 'archive') var icon = 'fa-archive'; else if (type == 'unarchive') var icon = 'fa-folder-open';


    $(element).find('i').removeClass(icon).addClass('fa-spinner fa-spin')

    var request = '/ar_edit.php?tipo=object_operation&operation=' + type + '&object=' + $(element).data('data').object + '&key=' + $(element).data('data').key + '&state=' + JSON.stringify(state)

    if (metadata != undefined) {
        request = request + '&metadata=' + JSON.stringify(metadata)
    }


    $.getJSON(request, function (data) {
        if (data.state == 200) {


            if (data.request != undefined) {
                change_view(data.request)
            } else {
                change_view(state.request, {'reload_showcase': 1})
            }

        } else if (data.state == 400) {
            $(element).find('i').addClass(icon).removeClass('fa-spinner fa-spin')
            Swal.fire({
                type: 'error', title: data.resp
            })

        }
    })


}

function delete_attachment(element) {


    if ($(element).hasClass('disabled')) {
        return
    }


    if (!$(element).find('i.fa').removeClass('fa-trash')) return;

    $(element).find('i.fa').removeClass('fa-trash').addClass('fa-spinner fa-spin')

    var request = '/ar_edit.php?tipo=delete_attachment&attachment_bridge_key=' + $(element).data('data').attachment_bridge_key

    $.getJSON(request, function (data) {
        if (data.state == 200) {

            console.log(data)
            if (data.request != undefined) {
                change_view(data.request)
            } else {
                change_view(state.request)
            }

        } else if (data.state == 400) {
            $(element).find('i.fa').addClass('fa-trash').removeClass('fa-spinner fa-spin')

        }


    })


}


$(document).on("click", "#edit_table", function () {

    change_view(state.request + '&tab=' + state.tab + '_edit', {
        reload_showcase: 1
    })

});

$(document).on("click", "#exit_edit_table", function () {

    change_view(state.request + '&tab=' + state.tab.replace(/\_edit$/i, ""))

});


function publish(element, type) {


    var icon = $(element).find('i')

    if (icon.hasClass('fa-spin')) return;


    icon.addClass('fa-spinner fa-spin')

    var request = '/ar_edit_website.php?tipo=' + type + '&parent_key=' + $(element).attr('webpage_key')
    console.log(request)
    $.getJSON(request, function (data) {


        if (type == 'publish_webpage') {
            icon.addClass('fa-rocket').removeClass('fa-spinner fa-spin')

            $('#publish').removeClass('changed valid')


        } else if (type == 'set_webpage_as_ready') {

            icon.addClass('fa-check-circle').removeClass('fa-spinner fa-spin')

        } else if (type == 'set_webpage_as_not_ready') {

            icon.addClass('fa-child').removeClass('fa-spinner fa-spin')

        } else if (type == 'unpublish_webpage') {


            icon.addClass('fa-rocket').removeClass('fa-spinner fa-spin')
            $('#publish').addClass('changed valid')

        }

        console.log(data)

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

        for (var key in data.update_metadata.class_html) {

            console.log('.' + key)
            console.log(data.update_metadata.class_html[key])

            $('.' + key).html(data.update_metadata.class_html[key])
        }


        for (var key in data.update_metadata.hide_by_id) {
            $('#' + data.update_metadata.hide_by_id[key]).addClass('hide')
        }

        for (var key in data.update_metadata.show_by_id) {
            $('#' + data.update_metadata.show_by_id[key]).removeClass('hide')
        }

        for (var key in data.update_metadata.visible_by_id) {
            $('#' + data.update_metadata.visible_by_id[key]).removeClass('invisible')
        }

        for (var key in data.update_metadata.invisible_by_id) {
            $('#' + data.update_metadata.invisible_by_id[key]).addClass('invisible')
        }


    })


}

function erase_date_field(field) {

    console.log(field)

    $('#' + field).val('')
    $('#' + field + '_formatted').val('')
    on_changed_value(field, $('#'.field).val())

}


function toggle_field_value(element) {


    var icon = $(element).find('i')

    if (icon.hasClass('fa-toggle-on')) {
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off').next('span').addClass('discreet')

    } else if (icon.hasClass('fa-toggle-off')) {
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on').next('span').removeClass('discreet')
    }


}

function check_field_value(element) {

    var icon = $(element).find('i')

    if (icon.hasClass('fa-check-square')) {
        icon.removeClass('fa-check-square').addClass('fa-square').next('span').addClass('discreet')

    } else if (icon.hasClass('fa-square')) {
        icon.removeClass('fa-square').addClass('fa-check-square').next('span').removeClass('discreet')
    }

}



$(document).on('click', '.permissions .permission_type', function (evt) {

    console.log($(this))

    var icon = $(this).find('i')

    console.log(icon)

    $('.permissions .save').addClass('changed valid')
    $('.permissions .updated_msg').addClass('hide')

    if (icon.hasClass('fa-square')) {
        icon.removeClass('fa-square').addClass('fa-check-square')
        $(this).removeClass('discreet_on_hover')

    } else if (icon.hasClass('fa-check-square')) {

        icon.addClass('fa-square').removeClass('fa-check-square')
        $(this).addClass('discreet_on_hover')

    } else if (icon.hasClass('fa-circle')) {


        $(this).closest('tr').find('i').removeClass('fa-dot-circle').addClass('fa-circle')
        $(this).closest('tr').find('.permission_type').addClass('discreet_on_hover')

        icon.removeClass('fa-circle').addClass('fa-dot-circle')
        $(this).removeClass('discreet_on_hover')


    } else if (icon.hasClass('fa-dot-circle')) {


        $(this).closest('tr').find('i').removeClass('fa-dot-circle').addClass('fa-circle')
        $(this).closest('tr').find('.permission_type').addClass('discreet_on_hover')

        icon.addClass('fa-circle').removeClass('fa-dot-circle')
        $(this).addClass('discreet_on_hover')


    }

    var permission_store_scope = false

    $('.permissions  .permission_store_scope i').each(function (i, obj) {
        if ($(obj).hasClass('fa-check-square') || $(obj).hasClass('fa-dot-circle')) {
            permission_store_scope = true
        }

    });


    if (permission_store_scope) {
        $('.permission_stores').removeClass('invisible')

    } else {
        $('.permission_stores').addClass('invisible')

    }


});

$(document).on('click', '.permissions .permission_store', function (evt) {

    var icon = $(this).find('i')

    $('.permissions .save').addClass('changed valid')

    $('.permissions .updated_msg').addClass('hide')

    if (icon.hasClass('fa-square')) {
        icon.removeClass('fa-square ').addClass('fa-check-square')
        $(this).removeClass('discreet_on_hover')

    } else if (icon.hasClass('fa-check-square')) {

        icon.addClass('fa-square ').removeClass('fa-check-square')
        $(this).addClass('discreet_on_hover')


    }


});


function save_permissions() {


    var save_button = $('.permissions .save')

    if (!save_button.hasClass('valid') || save_button.hasClass('wait')) {
        return;
    }


    save_button.addClass('wait').find('i.save_icon').addClass('fa-spin fa-spinner')

    var user_groups = [];
    var stores = [];

    $('.permissions  .permission_type i').each(function (i, obj) {
        if ($(obj).hasClass('fa-check-square') || $(obj).hasClass('fa-dot-circle')) {
            user_groups.push($(obj).closest('.permission_type').data('group_id'))
        }

    });
    $('.permissions  .permission_store i').each(function (i, obj) {
        if ($(obj).hasClass('fa-check-square') || $(obj).hasClass('fa-dot-circle')) {
            stores.push($(obj).closest('.permission_store').data('store_key'))
        }

    });

    value = JSON.stringify({
        user_groups: user_groups, stores: stores
    })

    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'user')

    ajaxData.append("key", save_button.data('user_key'))
    ajaxData.append("value", value)
    ajaxData.append("field", 'Permissions')


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            console.log(data)
            save_button.removeClass('wait').find('i.save_icon').removeClass('fa-spin fa-spinner')
            if (data.state == '200') {

                save_button.removeClass('valid changed')

                $('.permissions .updated_msg').removeClass('hide')

            } else if (data.state == '400') {
                swal(data.msg);
            }


        }, error: function () {

        }
    });


}


$(document).on('click', '.locked_show_edit_button', function (evt) {


    var _labels = $(this).data('labels');

    console.log(_labels)

    if (_labels == undefined) {
        _labels = {footer: '', title: $('body').data('labels').error, text: ''}
    }

    if (_labels.footer == undefined) {
        _labels.footer = '';
    }
    if (_labels.title == undefined) {
        _labels.title = $('body').data('labels').error;
    }
    if (_labels.text == undefined) {
        _labels.text = 'yy';
    }

    var right_code = $(this).data('right_code')
    if (right_code != '') {
        var request = '/ar_find.php?tipo=users_with_right&right=' + right_code
        $.getJSON(request, function (data) {
            var a = [];
            $.each(data.users_data, function (key, value) {
                if (value['UIR'] == 'No') {
                    a.push(value['User Alias'])
                }
            });
            var authorised_users = a.join(', ');
            footer_text = _labels.footer + authorised_users


            Swal.fire({
                type: 'error', title: _labels.title, text: _labels.text, footer: footer_text,
            })

        })
    } else {
        Swal.fire({
            type: 'error', title: _labels.title, text: _labels.text, footer: '',
        })
    }


});


async function delete_object_with_note(element) {
    if ($(element).hasClass('disabled')) {
        return;
    }

    var labels = $(element).data('labels');

    var _ref = await Swal.fire({
            title: labels.title,
            text: labels.text,
            type: 'warning',
            input: 'textarea',
            inputPlaceholder: labels.placeholder,
            confirmButtonText: labels.button_text
        }),
        text = _ref.value;

    if (text) {
        save_object_operation('delete', element, {
            note: text
        });
    } else {
        Swal.fire({
            type: 'error',
            title: labels.no_message
        });
    }
}

function launch_website(element){

    if($(element).hasClass('wait')){
        return;
    }

    var referer=$(element).data('referer')

    $(element).addClass('wait')
    $(element).find('i').addClass('fa-spinner fa-spin').removeClass('fa-rocket')

    var website_key=$(element).data('website_key')


    var ajaxData = new FormData();


    ajaxData.append("tipo", 'launch_website')
    ajaxData.append("key", website_key)



    $.ajax({
        url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                if(referer!=''){
                    change_view(referer);
                }else{
                    $('.website_status_icon').removeClass('discreet').addClass('success')

                    $('#launch_webpage_field').addClass('hide')
                    $('.launch_website_header').addClass('hide')
                }




            } else if (data.state == '400') {
                $(element).find('i').removeClass('fa-spinner fa-spin').addClass('fa-rocket')

                swal(data.msg);
            }

            }, error: function () {

        }
    });

}

function select_dropdown_handler(type, element) {


    const field = $(element).attr('field')
    const value = $(element).attr('value')

    console.log(element)
    console.log(value)

    if (value == 0) {
        return 0;
    }

    formatted_value = $(element).attr('formatted_value')
    $('#' + field + '_dropdown_select_label').val(formatted_value)
    $('#' + field).val(value)
    $('#' + field + '_results_container').addClass('hide').removeClass('show')

    return value;

}

function save_toggle_switch_part(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        var value='No'
    }else if(icon.hasClass('fa-toggle-off')){
        var value='Yes'
    }else{

        return
    }
    icon.removeClass('fa-toggle-on fa-toggle-off').addClass(' fa-spinner fa-spin')




    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Part')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", $(element).attr('field'))

    ajaxData.append("value", value)


    $.ajax({
               url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
               complete: function () {
               }, success: function (data) {

            if (data.state == '200') {


                if(value=='Yes'){
                    icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                    icon.next('span').removeClass('discreet')
                    $('.'+$(element).attr('field')).removeClass('error discreet')

                }else{
                    icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                    icon.next('span').addClass('discreet')
                    $('.'+$(element).attr('field')).addClass('error discreet')

                }

                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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

                //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                         title: data.title, text: data.msg, confirmButtonText: "OK"
                     });
            }



        }, error: function () {

        }
           });


}

function save_toggle_switch_product(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        var value='No'
    }else if(icon.hasClass('fa-toggle-off')){
        var value='Yes'
    }else{

        return
    }
    icon.removeClass('fa-toggle-on fa-toggle-off').addClass(' fa-spinner fa-spin')




    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Product')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", $(element).attr('field'))

    ajaxData.append("value", value)


    $.ajax({
               url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
               complete: function () {
               }, success: function (data) {

            if (data.state == '200') {


                if(value=='Yes'){
                    icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                    icon.next('span').removeClass('discreet')
                    $('.'+$(element).attr('field')).removeClass('error discreet')

                }else{
                    icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                    icon.next('span').addClass('discreet')
                    $('.'+$(element).attr('field')).addClass('error discreet')

                }

                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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

                //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                         title: data.title, text: data.msg, confirmButtonText: "OK"
                     });
            }



        }, error: function () {

        }
           });


}

function save_toggle_switch(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        var value='No'
    }else if(icon.hasClass('fa-toggle-off')){
        var value='Yes'
    }else{

        return
    }
    icon.removeClass('fa-toggle-on fa-toggle-off').addClass(' fa-spinner fa-spin')




    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Customer')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", $(element).attr('field'))

    ajaxData.append("value", value)


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if(value=='Yes'){
                    icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                    icon.next('span').removeClass('discreet')
                    $('.'+$(element).attr('field')).removeClass('error discreet')

                }else{
                    icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                    icon.next('span').addClass('discreet')
                    $('.'+$(element).attr('field')).addClass('error discreet')

                }

                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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

                //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}

function toggle_switch(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-toggle-off')){
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on').next('span').removeClass('discreet')
    }
}