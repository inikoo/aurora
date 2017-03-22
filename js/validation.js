/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function validate_field(field, new_value, field_type, required, server_validation_settings, parent, parent_key, object, key) {

    var validation = client_validation(field_type, required, new_value, field)


    //console.log(field+' '+validation.class)
    if (validation.class == 'valid' && (server_validation_settings != undefined && server_validation_settings != '')) {


        var settings = JSON.parse(server_validation_settings)


        if (settings.tipo == 'check_for_duplicates' && new_value == '') {
            post_validate_field(validation, field)

            return validation;
        }

        var validation = {
            class: 'waiting',
            type: ''
        }

        server_validation(settings, parent, parent_key, object, key, field, new_value)
    }


    post_validate_field(validation, field)


    return validation;
}

function post_validate_field(validation, field) {

}

function validate_address(field) {

    //  console.log('validating address')
    var valid_state = {
        class: 'valid',
        type: ''
    }

    var invalid_fields = 0;

    $('#' + field + ' input.address_input_field').each(function (i, obj) {


        var tr = $(obj).closest('tr')

        var asterisk = tr.find('.fa-asterisk')

        if (!asterisk.hasClass('hide')) {
            // console.log($(obj).attr('field_name'))
            if ($(obj).val() == '') {
                invalid_fields++;
                tr.find('.show_buttons').removeClass('super_discret success').addClass('error')


                if ($(obj).attr('field_name') == 'Address Recipient') {
                    valid_state_type = 'missing_recipient'
                } else if ($(obj).attr('field_name') == 'Address Line 1') {
                    valid_state_type = 'missing_addressLine1'
                } else if ($(obj).attr('field_name') == 'Address Postal Code') {
                    valid_state_type = 'missing_postalCode'
                } else {
                    valid_state_type = 'missing_field'
                }

                valid_state = {
                    class: 'invalid',
                    type: valid_state_type
                }
            } else {
                tr.find('.show_buttons').addClass(' success').removeClass('super_discret error')

            }

        }


    });

    if (invalid_fields > 1) {
        valid_state = {
            class: 'invalid',
            type: 'missing_fields'
        }
    }


    return valid_state;
}


function client_validation(type, required, value, field) {


  //  console.log(type + ' ' + value + ' ' + field)

    var valid_state = {
        class: 'valid',
        type: ''
    }


    if (value == '') {
        if (required) {


            if (type == 'dropdown_select') {

                if ($('#' + field + '_dropdown_select_label').val() == '') {

                    return {
                        class: 'invalid',
                        type: 'empty'
                    }
                } else {
                    return {
                        class: 'invalid',
                        type: 'not_found'
                    }
                }


            } else {

                return {
                    class: 'invalid',
                    type: 'empty'
                }
            }

        } else {
            return {
                class: 'valid',
                type: ''
            }

        }

    }


    switch (type) {


        case 'dropdown_select':

            if (value == '__error__' && $('#' + field).val() == '') {
                return {
                    class: 'invalid',
                    type: 'not_found'
                }
            } else if ($('#' + field).val() == '') {


                return {
                    class: 'potentially_valid',
                    type: 'not_selected'
                }
            }


            break;

        case 'string':
            break;

        case 'handle':

            if (value.length < 4) {
                return {
                    class: 'potentially_valid',

                    type: 'short'
                }
            }

            break;

        case 'pin':

            if (value.length < 4) {
                return {
                    class: 'potentially_valid',

                    type: 'short'
                }
            }

            break;
        case 'password':

            if (value.length < 6) {
                return {
                    class: 'potentially_valid',

                    type: 'short'
                }
            }

            break;

        case 'password_with_confirmation':

            if (value.length < 6) {
                return {
                    class: 'potentially_valid',
                    type: 'short'
                }
            }

            break;

        case 'date':
            break;

        case 'barcode':

            var res = validate_barcode(value, 12)

            if (res) return res


            break;


        case 'barcode_range':

            if (value.match(/\-/g)) {
                barcodes = value.split('-')
                if (barcodes.length > 2) {
                    return {
                        class: 'invalid',
                        type: 'invalid'
                    }
                }


                var res = validate_barcode(barcodes[0], 12, 12)


                if (res) {
                    return {
                        class: 'invalid',
                        type: res.type
                    }
                }

                if (barcodes[1] == '') {
                    return {
                        class: 'potentially_valid',
                        type: 'short'
                    }
                }

                res = validate_barcode(barcodes[1], 12, 12)
                console.log(barcodes)
                if (res) return res


                if (barcodes[1] < barcodes[0]) {
                    return {
                        class: 'invalid',
                        type: 'range_minmax'
                    }
                }


            } else {
                var res = validate_barcode(value, 12, 13)


                if (res) return res


            }

            break;

        case 'telephone':


            value = value.replace(/(\s|\-|\(|\))/g, '')


            if (value.length < 4) {
                value = value.replace(/^\+/g, '')
                if (value == '' || $.isNumeric(value)) {
                    return {
                        class: 'potentially_valid',
                        type: 'short'
                    }
                } else {
                    return {
                        class: 'invalid',
                        type: 'invalid'
                    }
                }

            } else {


                if (!$('#' + field).intlTelInput("isValidNumber")) {
                    var error = $('#' + field).intlTelInput("getValidationError");
                    console.log(error)
                    if (error == intlTelInputUtils.validationError.TOO_SHORT) {
                        return {
                            class: 'potentially_valid',
                            type: 'short'
                        }
                    } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
                        return {
                            class: 'invalid',
                            type: 'long'
                        }
                    } else if (error == intlTelInputUtils.validationError.NOT_A_NUMBER) {
                        return {
                            class: 'invalid',
                            type: 'invalid'
                        }
                    } else if (error == intlTelInputUtils.validationError.INVALID_COUNTRY_CODE) {
                        return {
                            class: 'invalid',
                            type: 'invalid_code'
                        }
                    }

                }
            }

            break;


        case 'email':
        case 'new_email':


            var tmp = value.replace(/"[^"]*"/g, '')
            if (tmp.match(/"/g)) {
                // console.log('has quote')
            } else {
                //  console.log('dont has quote')
                if (tmp.match(/\s/g)) {


                    return {
                        class: 'invalid',
                        type: 'spaces'
                    }
                }


                if (tmp.match(/\(|\)|\,|:|;|<|>|\[|\]/g)) {


                    if (tmp.match(/,/g)) {


                        return {
                            class: 'invalid',
                            type: 'comma'
                        }
                    } else {


                        return {
                            class: 'invalid',
                            type: 'invalid_character'
                        }
                    }

                }
                if (tmp.match(/^([^@]*@){2,}[^@]*$/g)) {
                    console.log('error')

                    return {
                        class: 'invalid',
                        type: 'double_at'
                    }

                }


            }


            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,63})?$/


            if (!emailReg.test(value)) {

                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }

            break;

        case 'time':


            var timelReg = /^[0-9\:]+$/
            if (!timelReg.test(value)) {

                return {
                    class: 'invalid',
                    type: 'invalid'
                }
            }

            if (value.length > 5) {

                return {
                    class: 'invalid',
                    type: 'invalid'
                }
            } else if (value.length == 1) {

                var partial_timeReg = /^[0-9]$/
                if (!partial_timeReg.test(value)) {
                    return {
                        class: 'invalid',
                        type: 'invalid'
                    }
                }

            } else if (value.length == 2) {


                var partial_timeReg = /^(1?[0-9]|2[0-3])|[0-9]:$/
                if (!partial_timeReg.test(value)) {
                    return {
                        class: 'invalid',

                        type: 'invalid'
                    }
                }


            } else if (value.length == 4) {


                var timelReg = /^(1?[0-9]|2[0-3]|0[0-9])[0-5][0-9]$/
                if (timelReg.test(value)) {

                    return {
                        class: 'valid',
                        type: 'valid'
                    }
                }

                var timelReg = /^(1?[0-9]|2[0-3]|0[0-9]):[0-5]$/
                if (timelReg.test(value)) {

                    return {
                        class: 'potentially_valid',
                        type: 'invalid'
                    }
                }


            }


            var timelReg = /^(1?[0-9]|2[0-3]|0[0-9]):[0-5][0-9]$/
            if (!timelReg.test(value)) {

                if (value.length == 5 || value.length == 4) {

                    return {
                        class: 'invalid',
                        type: 'invalid'
                    }
                } else {

                    return {
                        class: 'potentially_valid',
                        type: 'invalid'
                    }
                }
            } else {
                return {
                    class: 'valid',
                    type: ''
                }

            }

        case 'smallint_unsigned':
            var res = validate_signed_integer(value, 65535)
            if (res) return res
            break;
        case 'int_unsigned':
            var res = validate_signed_integer(value, 4294967295)
            if (res) return res
            break;
        case 'minutes_in_day':
            var res = validate_signed_integer(value, 1440)
            if (res) return res
            break;

        case 'minutes_in_break':

            if (value == 0) {

                return {
                    class: 'invalid',
                    type: 'invalid_break_duration'
                }
            }

            var res = validate_signed_integer(value, 1440)
            if (res) return res
            break;

        case 'seconds_in_day':
            var res = validate_signed_integer(value, 86400)
            if (res) return res
            break;
        case 'seconds_in_hour':
            var res = validate_signed_integer(value, 3600)
            if (res) return res
            break;

        case 'day_of_month':

            if (value == 0) {

                return {
                    class: 'invalid',
                    type: 'invalid_day_of_month'
                }
            }

            var res = validate_signed_integer(value, 31)
            if (res) return res
            break;

        case 'amount':

            var comma = ','
            var dot = '.'

            var re = new RegExp(comma, "g");
            value = value.replace(re, "")


            if (value == dot) {
                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }


            var regex = /^\d*\.?\d{0,6}$/
            if (!regex.test(value)) {
                return {
                    class: 'invalid',
                    type: 'invalid_amount'
                }
            }
            break

        case 'percentage':


            console.log('caca')

            var comma = ','
            var dot = '.'

            var re = new RegExp(comma, "g");
            value = value.replace(re, "")


            if (value == dot) {
                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }


            var regex = /^\d*\.?\d{0,6}\%$/
            if (regex.test(value)) {

                return {
                    class: 'valid',
                    type: 'valid'
                }
            }

            var regex = /^\d*\.?\d{0,6}$/
            if (!regex.test(value)) {
                return {
                    class: 'invalid',
                    type: 'invalid_amount'
                }
            }else{
                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }





            break


        case 'amount_margin':


            value = value.replace(/\%$/g, "")


            var comma = ','
            var dot = '.'


            var re = new RegExp(comma, "g");
            value = value.replace(re, "")

            if (value == dot) {
                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }


            var regex = /^\d*\.?\d{0,6}$/
            if (!regex.test(value)) {
                /// console.log('ccc')
                return {
                    class: 'invalid',
                    type: 'invalid_amount'
                }
            }


        default:

    }


    return valid_state;
}


function validate_barcode(value, min_length, max_length) {

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid',
            type: 'invalid'
        }
    }
    if (value < 0) {
        return {
            class: 'invalid',

            type: 'negative'
        }
    }

    if (Math.floor(value) != value) {
        return {
            class: 'invalid',

            type: 'invalid'
        }
    }


    if (value.length < min_length) {
        return {
            class: 'potentially_valid',
            type: 'short'
        }
    }

    if (value.length > max_length) {
        return {
            class: 'invalid',
            type: 'long'
        }
    }


    return false
}


function validate_number(value, min, max) {

    var value = parseFloat(value)
    var min = parseFloat(min)
    var max = parseFloat(max)

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid',
            type: 'no_numeric'
        }
    }


    if (min != undefined && value < min) {

        return {
            class: 'invalid',
            type: 'negative'
        }

    }

    if (max != undefined && value > max) {

        console.log(value)
        console.log(max)

        return {
            class: 'invalid',
            type: 'max_limit'
        }

    }

    return false

}


function validate_signed_integer(value, max_value) {

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid',
            type: 'not_integer'
        }
    }

    if (value > max_value) {
        return {
            class: 'invalid',

            type: 'too_big'
        }
    }

    if (value < 0) {
        return {
            class: 'invalid',

            type: 'negative'
        }
    }
    if (Math.floor(value) != value) {


        return {
            class: 'invalid',

            type: 'not_integer'
        }
    }

    return false
}


function server_validation(settings, parent, parent_key, object, key, field, value) {



    // console.log(settings.setup)
    if (settings.parent != null) {
        parent = settings.parent;
    }
    if (settings.parent_key != null) {
        parent_key = settings.parent_key;
    }
    if (settings.object != null) {
        object = settings.object;
    }

    if (settings.key != null) {
        key = settings.key;
    }


    if (settings.parent_key_field != null) {
        parent_key = $('#' + settings.parent_key_field).val()
    }


    $("#" + field + '_editor').addClass('waiting')

    if (settings.setup != null) {
        var request = '/ar_setup.php?tipo=' + settings.tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value

    } else {

        var request = '/ar_validation.php?tipo=' + settings.tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value

        if (settings.actual_field != null) {
            request += '&actual_field=' + settings.actual_field;
        }


    }

    console.log(request)
    $.getJSON(request, function (data) {


        $("#" + field + '_field').removeClass('waiting invalid valid')


        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')

        if (!$('#' + field + '_formatted_value').hasClass('hide')) {

            return;
        }

        if (data.state == 200) {

            var validation = data.validation
            var msg = data.msg

        } else {
            var validation = 'invalid'
            var msg = "Error, can't verify value on server"

        }
        post_validate_field(validation, field)


        $('#' + field + '_msg').html(msg)

        $('#' + field + '_field').addClass(validation)


        if ($('#fields').hasClass('new_object')) {
            var form_validation = get_form_validation_state()
            process_form_validation(form_validation)

        }


    })


}
