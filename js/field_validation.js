/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function validate_field(field, new_value) {

    var field_data = $('#' + field + '_container')



    var validation = client_validation(field_data.attr('field_type'), field_data.attr('_required'), new_value, field)

    if (validation.class == 'valid' && field_data.attr('server_validation')) {


        var validation = {
            class: 'waiting',
            type: ''
        }

        server_validation(field_data.attr('server_validation'), field_data.attr('parent'), field_data.attr('parent_key'), field_data.attr('object'), field_data.attr('key'), field, new_value)
    }



    return validation;
}


function client_validation(type, required, value, field) {

    var valid_state = {
        class: 'valid',

        type: ''
    }



    if (value == '') {
        if (required) {
            return {
                class: 'invalid',
                type: 'empty'
            }


        } else {
            return {
                class: 'valid',
                type: ''
            }

        }

    }


    switch (type) {
    case 'smallint_unsigned':

        if (!$.isNumeric(value)) {
            return {
                class: 'invalid',

                type: 'not_integer'
            }
        }

        if (value > 65535) {
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


        break;
    case 'int_unsigned':


        if (!$.isNumeric(value)) {
            return {
                class: 'invalid',

                type: 'not_integer'
            }
        }

        if (value > 4294967295) {
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






        break;
    case 'string':
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

    case 'telephone':




        if (value.length == 1) {
            if ($.isNumeric(value)) {
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
                //   console.log(error)
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

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,63})?$/


        if (!emailReg.test(value)) {

            return {
                class: 'potentially_valid',

                type: 'invalid'
            }
        }

        break;

    case 'time':

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


        }else if (value.length == 4) {


           var timelReg = /^(1?[0-9]|2[0-3])[0-5][0-9]$/
        if (timelReg.test(value)) {

            return {
                class: 'valid',
                type: 'valid'
            }
        }

        }



        var timelReg = /^(1?[0-9]|2[0-3]):[0-5][0-9]$/
        if (!timelReg.test(value)) {

            return {
                class: 'potentially_valid',
                type: 'invalid'
            }
        }



    default:

    }



    return valid_state;
}

function server_validation(tipo, parent, parent_key, object, key, field, value) {
    $("#" + field + '_editor').addClass('waiting')
    var request = '/ar_validation.php?tipo=' + tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value

    $.getJSON(request, function(data) {



        $("#" + field + '_editor').removeClass('waiting invalid valid')
        $("#" + field + '_validation').removeClass('waiting invalid valid')

        if (!$('#' + field + '_value').hasClass('hide')) {
            return;
        }

        if (data.state == 200) {

            var validation = data.validation
            var msg = data.msg

        } else {
            var validation = 'invalid'
            var msg = "Error, can't verify value on server"

        }

        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')

        if (validation == 'valid') {
            var msg = '';
        } else {
            $('#' + object + '_save').removeClass('valid').addClass('invalid')

        }



        $('#' + field + '_validation').addClass(validation)

        $('#' + field + '_msg').html(msg)
        $('#' + field + '_editor').addClass(validation)

        if ($('#fields').hasClass('new_object')) {
            check_if_form_is_valid()
        }



    })


}
